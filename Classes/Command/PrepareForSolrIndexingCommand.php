<?php

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Command;

use GuzzleHttp\Exception\ClientException;
use JWeiland\ServiceBw2\Indexer\Indexer;
use JWeiland\ServiceBw2\Request\EntityRequestInterface;
use JWeiland\ServiceBw2\Request\Portal\Lebenslagen;
use JWeiland\ServiceBw2\Request\Portal\Leistungen;
use JWeiland\ServiceBw2\Request\Portal\Organisationseinheiten;
use JWeiland\ServiceBw2\Service\SolrIndexService;
use JWeiland\ServiceBw2\Utility\ServiceBwUtility;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Command to prepare service_bw2 records for EXT:solr index
 */
class PrepareForSolrIndexingCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Argument to class mapping
     * Helps to prevent inserting the FQCN of one of the request types on CLI
     *
     * @var array
     */
    protected $classMapping = [
        'Lebenslagen' => Lebenslagen::class,
        'Leistungen' => Leistungen::class,
        'Organisationseinheiten' => Organisationseinheiten::class,
    ];

    /**
     * This is an object of one of the service_bw2 request types
     *
     * @var EntityRequestInterface
     */
    protected $request;

    protected function configure(): void
    {
        $this
            ->setDescription('Prepare records of service_bw2 to be indexed by EXT:solr.')
            ->addArgument(
                'request-class',
                InputArgument::REQUIRED,
                'Enter one of the service_bw2 request types. Choose one of "Leistungen", "Lebenslagen" or "Organisationseinheiten"'
            )
            ->addArgument(
                'root-page',
                InputArgument::REQUIRED,
                'Enter the TYPO3 root page UID. This is needed to use the correct EXT:solr configuration for indexing'
            )
            ->addArgument(
                'solr-index-type',
                InputArgument::REQUIRED,
                'Enter the EXT:solr index type which should be used to index the records of the chosen request-class'
            )
            ->addOption(
                'content-uid',
                null,
                InputOption::VALUE_OPTIONAL,
                'Enter the tt_content UID of the service_bw2 plugin where you have assigned the ' .
                'Organisationseinheiten. Only needed, if request-class is set to: "' . Organisationseinheiten::class . '"'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $requestClassName = $this->getRequestClass($input);
        $this->request = $this->getRequestObject($requestClassName);

        $recordList = $this->request->findAll();
        if ($requestClassName === Organisationseinheiten::class) {
            if ($input->getOption('content-uid')) {
                $recordList = ServiceBwUtility::filterOrganisationseinheitenByParentIds(
                    $recordList,
                    $this->getInitialRecords((int)$input->getOption('content-uid'))
                );
            } else {
                $message = 'In case of request-class = ' . Organisationseinheiten::class . ' you also have to set content-uid';
                $this->logger->error($message);
                throw new \InvalidArgumentException($message);
            }
        }

        if (ExtensionManagementUtility::isLoaded('solr')) {
            $progressBar = new ProgressBar($output, count($recordList));
            $progressBar->start();

            $solrIndexService = $this->getSolrIndexService();
            $solrIndexType = $input->getArgument('solr-index-type');
            $rootPageUid = (int)$input->getArgument('root-page');

            try {
                // Keep that at first. If there is an error because of solr type or root page
                // it will throw an exception and prevents collecting all the records from API
                // which can be really slow
                $solrIndexService->indexerDeleteByType($solrIndexType, $rootPageUid);

                // Following method can take a very long time, as it retrieves details from API call for each record.
                // The result of each API call will be cached for better performance in frontend.
                // To speed up this process you can call CacheWarmupCommand before.
                foreach ($this->generatorForLiveRecords($recordList) as $liveRecord) {
                    $solrIndexService->indexRecord($liveRecord, $solrIndexType, $rootPageUid);
                    $progressBar->advance();
                }
            } catch (\RuntimeException $runtimeException) {
                $this->logger->error(
                    'Skip EXT:solr index because of given solr configuration "' . $solrIndexType . '"could not be found',
                );
            }

            $progressBar->finish();
        }

        return true;
    }

    protected function getInitialRecords(int $contentUid): array
    {
        $ttContentRecord = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tt_content')
            ->select(
                ['pi_flexform'],
                'tt_content',
                ['uid' => $contentUid]
            )->fetch();

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
            return GeneralUtility::trimExplode(
                ',',
                ArrayUtility::getValueByPath($flexform, 'data/sDEFAULT/lDEF/settings.organisationseinheiten.listItems/vDEF'),
                true
            );
        } catch (\InvalidArgumentException | \RuntimeException $e) {
            return [];
        }
    }

    /**
     * Loop through all records and request individual data from Service BW API.
     * The individual response data will be stored in Cache for faster response in FE.
     */
    protected function generatorForLiveRecords(array $recordsToIndex): \Generator
    {
        foreach ($recordsToIndex as $recordToIndex) {
            try {
                $liveRecordWithFullData = $this->request->findById($recordToIndex['id']);
            } catch (ClientException $exception) {
                continue;
            }

            if (isset($liveRecordWithFullData['textbloecke']) && is_array($liveRecordWithFullData['textbloecke'])) {
                $liveRecordWithFullData['processed_textbloecke'] = $this->buildCSVListOfTextBloecke(
                    $liveRecordWithFullData['textbloecke']
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
                    array_column($textBloecke, 'text')
                )
            )
        );
    }

    protected function getRequestClass(InputInterface $input): string
    {
        if (
            $input->hasArgument('request-class')
            && ($requestClass = $input->getArgument('request-class'))
            && array_key_exists($requestClass, $this->classMapping)
        ) {
            return $this->classMapping[$requestClass];
        }

        $message = 'Given request-type is not allowed';
        $this->logger->error($message);
        throw new \InvalidArgumentException($message);
    }

    protected function getRequestObject(string $className): EntityRequestInterface
    {
        if (
            class_exists($className)
            && ($requestObject = GeneralUtility::makeInstance($className))
            && $requestObject instanceof EntityRequestInterface
        ) {
            return $requestObject;
        }

        $message = 'Invalid classname ' . $className . ' for request detected';
        $this->logger->error($message);
        throw new \InvalidArgumentException($message);
    }

    protected function getSolrIndexService(): SolrIndexService
    {
        $indexer = GeneralUtility::makeInstance(Indexer::class);
        return GeneralUtility::makeInstance(SolrIndexService::class, $indexer);
    }
}
