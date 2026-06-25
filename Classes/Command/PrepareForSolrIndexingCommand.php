<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Command;

use ApacheSolrForTypo3\Solr\ConnectionManager;
use ApacheSolrForTypo3\Solr\Domain\Site\SiteRepository;
use ApacheSolrForTypo3\Solr\Exception\InvalidArgumentException;
use ApacheSolrForTypo3\Solr\Exception\InvalidConnectionException;
use ApacheSolrForTypo3\Solr\IndexQueue\Queue;
use Doctrine\DBAL\Exception;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use JWeiland\ServiceBw2\Controller\ControllerTypeEnum;
use JWeiland\ServiceBw2\Domain\Model\Record;
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use JWeiland\ServiceBw2\Domain\Repository\RepositoryFactory;
use JWeiland\ServiceBw2\Domain\Repository\RepositoryInterface;
use JWeiland\ServiceBw2\Service\SolrIndexService;
use JWeiland\ServiceBw2\Traits\FilterAllowedLanguagesTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Command to prepare service_bw2 records for EXT:solr index
 */
#[AsCommand(
    name: 'servicebw:preparesolrindex',
    description: 'Prepare records of service_bw2 to be indexed by EXT:solr',
)]
class PrepareForSolrIndexingCommand extends Command
{
    use FilterAllowedLanguagesTrait;

    public function __construct(
        protected readonly LoggerInterface $logger,
        protected readonly RepositoryFactory $repositoryFactory,
        protected readonly ExtConf $extConf,
        protected readonly ConnectionPool $connectionPool,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'request-class',
                InputArgument::REQUIRED,
                'Enter one of the service_bw2 request types. Choose one of "Leistungen", "Lebenslagen" or "Organisationseinheiten"',
            )
            ->addArgument(
                'root-page',
                InputArgument::REQUIRED,
                'Enter the TYPO3 root page UID. This is needed to use the correct EXT:solr configuration for indexing',
            )
            ->addArgument(
                'solr-index-type',
                InputArgument::REQUIRED,
                'Enter the EXT:solr index type which should be used to index the records of the chosen request-class',
            )
            ->addOption(
                'content-uid',
                null,
                InputOption::VALUE_OPTIONAL,
                'Enter the tt_content UID of the service_bw2 plugin where you have assigned the '
                    . 'Organisationseinheiten. Only needed, if request-class is set to: "' . OrganisationseinheitenRepository::class . '"',
            )
            ->addOption(
                'locales',
                null,
                InputOption::VALUE_OPTIONAL,
                'Comma-separated list of Service BW language codes to warm up, e.g. "de,en,fr". If omitted or invalid, all allowed Service BW languages will be used.',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $repository = $this->repositoryFactory->getRepository(
            ControllerTypeEnum::from(strtolower($input->getArgument('request-class'))),
        );

        foreach ($this->filterAllowedLanguages($input, $this->extConf->getAllowedLanguages()) as $languageCode) {
            $io->section('Language for current cache warmup: ' . $languageCode);

            if ($repository instanceof OrganisationseinheitenRepository) {
                if (!$input->getOption('content-uid')) {
                    $message = 'In case of request-class = ' . OrganisationseinheitenRepository::class . ' you also have to set content-uid';
                    $this->logger->error($message);
                    throw new \InvalidArgumentException($message, 5940254917);
                }
                $records = $this->flattenOrganisationseinheiten(
                    $repository->getOrganisationseinheitenTree(
                        $this->getInitialRecords((int)$input->getOption('content-uid')),
                        $languageCode,
                    ),
                );
            } else {
                $records = iterator_to_array($repository->findAll($languageCode));
            }

            if (ExtensionManagementUtility::isLoaded('solr')) {
                try {
                    $solrIndexType = $input->getArgument('solr-index-type');
                    $rootPageUid = (int)$input->getArgument('root-page');
                    $solrSite = $this->getSiteRepository()->getSiteByRootPageId($rootPageUid);
                } catch (InvalidArgumentException | SiteNotFoundException) {
                    return Command::INVALID;
                }

                try {
                    $solrIndexService = $this->getSiteIndexService();

                    // Keep that at first. If there is an error because of solr type or root page,
                    // it will throw an exception and prevents collecting all the records from API,
                    // which can be really slow
                    $solrIndexService->clearSolrIndexByType($solrIndexType, $solrSite);

                    // The following method can take a very long time, as it retrieves details from the API call
                    // for each record. The result of each API call will be cached for better performance in the frontend.
                    // To speed up this process, you can call CacheWarmupCommand before.
                    foreach ($this->generatorForLiveRecords($records, $repository) as $liveRecord) {
                        $solrIndexService->indexServiceBWRecord($liveRecord->asArray(), $solrIndexType, $solrSite);

                        if ($io->isVerbose()) {
                            $io->writeln(sprintf(
                                '<info>➜</info> ID <comment>%s</comment>, name <comment>%s</comment>',
                                $liveRecord->getId(),
                                $liveRecord->getName() ?? '[no name]',
                            ));
                        }
                    }
                } catch (\RuntimeException | InvalidConnectionException) {
                    $this->logger->error(
                        'Skip EXT:solr index because of given solr configuration "' . $solrIndexType . '"could not be found',
                    );
                }
            }
        }

        return Command::SUCCESS;
    }

    /**
     * @param array<int, Record> $tree
     * @return array<int, Record>
     */
    private function flattenOrganisationseinheiten(array $tree): array
    {
        $flat = [];
        foreach ($tree as $record) {
            $flat[] = $record;
            array_push($flat, ...$this->flattenOrganisationseinheiten($record->getUntergeordneteOEs()));
        }

        return $flat;
    }

    protected function getInitialRecords(int $contentUid): array
    {
        $connection = $this->connectionPool->getConnectionForTable('tt_content');

        try {
            $ttContentRecord = $connection
                ->select(
                    ['pi_flexform'],
                    'tt_content',
                    [
                        'uid' => $contentUid,
                    ],
                )->fetchAssociative();
        } catch (Exception) {
            return [];
        }

        if (
            !is_array($ttContentRecord)
            || !array_key_exists('pi_flexform', $ttContentRecord)
        ) {
            return [];
        }

        $flexForm = GeneralUtility::xml2array($ttContentRecord['pi_flexform']);
        if (!is_array($flexForm)) {
            return [];
        }

        try {
            return GeneralUtility::intExplode(
                ',',
                ArrayUtility::getValueByPath($flexForm, 'data/sDEFAULT/lDEF/settings.organisationseinheiten.listItems/vDEF'),
                true,
            );
        } catch (\InvalidArgumentException | \RuntimeException) {
            return [];
        }
    }

    /**
     * Loop through all records and request individual data from Service BW API.
     * The individual response data will be stored in Cache for faster response in FE.
     *
     * @param Record[] $recordsToIndex
     * @return \Generator<Record>
     */
    protected function generatorForLiveRecords(
        array $recordsToIndex,
        RepositoryInterface $repository,
    ): \Generator {
        foreach ($recordsToIndex as $recordToIndex) {
            $liveRecordWithFullData = $repository->findById($recordToIndex->getId());
            if (!$liveRecordWithFullData instanceof Record) {
                $this->logger->warning(sprintf(
                    'Record of type %s with ID %s could not be found',
                    $repository::class,
                    $recordToIndex->getId(),
                ));
                continue;
            }

            yield $liveRecordWithFullData;
        }
    }

    /**
     * Do not add this method as a constructor argument, as it is unclear
     * if TYPO3 extension "solr" is loaded or not.
     */
    protected function getSiteIndexService(): SolrIndexService
    {
        return GeneralUtility::makeInstance(
            SolrIndexService::class,
            $this->getSiteRepository(),
            GeneralUtility::makeInstance(Queue::class),
            GeneralUtility::makeInstance(ConnectionManager::class),
        );
    }

    /**
     * Do not add this method as a constructor argument, as it is unclear
     * if TYPO3 extension "solr" is loaded or not.
     */
    protected function getSiteRepository(): SiteRepository
    {
        return GeneralUtility::makeInstance(SiteRepository::class);
    }
}
