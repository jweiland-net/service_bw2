<?php

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
use GuzzleHttp\Exception\ClientException;
use JWeiland\ServiceBw2\Controller\ControllerTypeEnum;
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use JWeiland\ServiceBw2\Domain\Repository\RepositoryFactory;
use JWeiland\ServiceBw2\Domain\Repository\RepositoryInterface;
use JWeiland\ServiceBw2\Service\SolrIndexService;
use JWeiland\ServiceBw2\Utility\ServiceBwUtility;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
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
    public function __construct(
        protected readonly LoggerInterface $logger,
        protected readonly RepositoryFactory $repositoryFactory,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Prepare records of service_bw2 to be indexed by EXT:solr.')
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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repository = $this->repositoryFactory->getRepository(
            ControllerTypeEnum::from(strtolower($input->getArgument('request-class'))),
        );

        $recordList = $repository->findAll();

        if ($repository::class === OrganisationseinheitenRepository::class) {
            if ($input->getOption('content-uid')) {
                $recordList = ServiceBwUtility::filterOrganisationseinheitenByParentIds(
                    $recordList,
                    $this->getInitialRecords((int)$input->getOption('content-uid')),
                );
            } else {
                $message = 'In case of request-class = ' . OrganisationseinheitenRepository::class . ' you also have to set content-uid';
                $this->logger->error($message);
                throw new \InvalidArgumentException($message);
            }
        }

        if (ExtensionManagementUtility::isLoaded('solr')) {
            $progressBar = new ProgressBar($output, count($recordList));
            $progressBar->start();

            try {
                $solrIndexType = $input->getArgument('solr-index-type');
                $rootPageUid = (int)$input->getArgument('root-page');
                $solrSite = $this->getSiteRepository()->getSiteByRootPageId($rootPageUid);
            } catch (InvalidArgumentException | SiteNotFoundException $e) {
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
                foreach ($this->generatorForLiveRecords($recordList, $repository) as $liveRecord) {
                    $solrIndexService->indexServiceBWRecord($liveRecord, $solrIndexType, $solrSite);
                    $progressBar->advance();
                }
            } catch (\RuntimeException | InvalidConnectionException $e) {
                $this->logger->error(
                    'Skip EXT:solr index because of given solr configuration "' . $solrIndexType . '"could not be found',
                );
            }

            $progressBar->finish();
        }

        return 0;
    }

    protected function getInitialRecords(int $contentUid): array
    {
        $ttContentRecord = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tt_content')
            ->select(
                ['pi_flexform'],
                'tt_content',
                ['uid' => $contentUid],
            )->fetchOne();

        if (
            !is_array($ttContentRecord)
            || !array_key_exists('pi_flexform', $ttContentRecord)
        ) {
            return [];
        }

        $flexform = GeneralUtility::xml2array($ttContentRecord['pi_flexform']);
        if (!is_array($flexform)) {
            return [];
        }

        try {
            return GeneralUtility::intExplode(
                ',',
                ArrayUtility::getValueByPath($flexform, 'data/sDEFAULT/lDEF/settings.organisationseinheiten.listItems/vDEF'),
                true,
            );
        } catch (\InvalidArgumentException | \RuntimeException $e) {
            return [];
        }
    }

    /**
     * Loop through all records and request individual data from Service BW API.
     * The individual response data will be stored in Cache for faster response in FE.
     */
    protected function generatorForLiveRecords(
        array $recordsToIndex,
        RepositoryInterface $repository,
    ): \Generator {
        foreach ($recordsToIndex as $recordToIndex) {
            try {
                $liveRecordWithFullData = $repository->findById($recordToIndex['id']);
                if ($liveRecordWithFullData === []) {
                    $this->logger->warning(sprintf(
                        'Record of type %s with ID %s could not be found',
                        $repository::class,
                        $recordToIndex['id'],
                    ));
                    continue;
                }
            } catch (ClientException) {
                continue;
            }

            if (isset($liveRecordWithFullData['textbloecke']) && is_array($liveRecordWithFullData['textbloecke'])) {
                $liveRecordWithFullData['processed_textbloecke'] = $this->buildCSVListOfTextBloecke(
                    $liveRecordWithFullData['textbloecke'],
                );
            }

            yield $liveRecordWithFullData;
        }
    }

    /**
     * Extract all non-empty "text" (array-key) elements from $textBloecke, combine them to a concatenated string
     * and remove all HTML tags.
     */
    protected function buildCSVListOfTextBloecke(array $textBloecke): string
    {
        return strip_tags(
            implode(
                ',',
                array_filter(
                    array_column($textBloecke, 'text'),
                ),
            ),
        );
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
