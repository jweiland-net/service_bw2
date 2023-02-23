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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Command to prepare service_bw2 records for EXT:solr index
 */
class PrepareForSolrIndexingCommand extends Command
{
    /**
     * @var string
     */
    protected $solrConfig = '';

    /**
     * @var string
     */
    protected $pluginTtContentUid = '';

    /**
     * @var int
     */
    protected $rootPage = 0;

    /**
     * @var EntityRequestInterface
     */
    protected $requestClass;

    /**
     * @var array[]
     */
    protected $classMapping = [
        Organisationseinheiten::class => [
            'method' => 'findAll',
        ],
        Leistungen::class => [
            'method' => 'findAll',
        ],
        Lebenslagen::class => [
            'method' => 'findAll',
        ],
    ];

    protected function configure(): void
    {
        $this
            ->setDescription('Prepare records of service_bw2 to be indexed by EXT:solr')
            ->addOption(
                'record-type',
                null,
                InputOption::VALUE_REQUIRED,
                'Warmup caches of Lebenslagen (Life situations)'
            );

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getRegistry()->remove('servicebw2.scheduler.index', 'progress');
        $this->requestClass = GeneralUtility::makeInstance($this->typeToIndex);

        $recordList = call_user_func([$this->requestClass, $this->classMapping[$this->typeToIndex]['method']]);
        // SF: I don't understand that condition. As Organisationseinheiten:::class is always contained in
        // $this->classMapping this condition does not make sense to me. Further, the value on the left
        // is an array!
        // ToDo: CleanUp somehow
        if ($this->classMapping[$this->typeToIndex] === Organisationseinheiten::class) {
            $initialRecords = $this->getInitialRecords('settings.organisationseinheiten.listItems');
            $recordList = ServiceBwUtility::filterOrganisationseinheitenByParentIds($recordList, $initialRecords);
        }

        // While executing this method, all detail data for records will be stored in cache
        $liveDataForRecords = $this->getLiveDataForRecords($recordList);

        if ($this->solrConfig !== '' && ExtensionManagementUtility::isLoaded('solr')) {
            $indexer = GeneralUtility::makeInstance(Indexer::class);
            $solrIndexService = GeneralUtility::makeInstance(SolrIndexService::class, $indexer);
            try {
                $solrIndexService->indexerDeleteByType($this->solrConfig, $this->rootPage);
                $solrIndexService->indexRecords($liveDataForRecords, $this->solrConfig, $this->rootPage);
            } catch (\RuntimeException $runtimeException) {
                $service = GeneralUtility::makeInstance(FlashMessageService::class);
                $queue = $service->getMessageQueueByIdentifier();
                $queue->enqueue(
                    GeneralUtility::makeInstance(
                        FlashMessage::class,
                        'Given solr configuration "' . $this->solrConfig . '"could not be found',
                        'Skip Solr Indexing',
                        AbstractMessage::WARNING
                    )
                );
            }
        }

        return true;
    }

    public function getAdditionalInformation(): string
    {
        if ($this->solrConfig === '') {
            return 'Note: Solr type not configured. Skipping Solr indexing.';
        }

        return '';
    }

    public function getProgress(): float
    {
        $progress = $this->getRegistry()->get('servicebw2.scheduler.index', 'progress');
        if (is_array($progress)) {
            return 100 / $progress['records'] * $progress['counter'];
        }

        return 0.0;
    }

    /**
     * Gets initial records
     *
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    protected function getInitialRecords(string $settings): array
    {
        $resultRows = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tt_content')
            ->select(
                ['pi_flexform'],
                'tt_content',
                ['uid' => $this->pluginTtContentUid]
            )->fetch();
        $flexform = GeneralUtility::xml2array($resultRows['pi_flexform']);

        return GeneralUtility::trimExplode(
            ',',
            $flexform['data']['sDEFAULT']['lDEF'][$settings]['vDEF'],
            true
        );
    }

    /**
     * Loop through all records and request individual data from Service BW API.
     * The individual response data will be stored in Cache for faster response in FE.
     */
    protected function getLiveDataForRecords(array $records): array
    {
        $recordsToIndex = [];
        $amountOfRecords = count($records);
        $counter = 0;

        foreach ($records as $recordToIndex) {
            $counter++;

            try {
                $record = $this->requestClass->findById($recordToIndex['id']);
                $this->getRegistry()->set(
                    'servicebw2.scheduler.index',
                    'progress',
                    [
                        'records' => $amountOfRecords,
                        'counter' => $counter,
                    ]
                );

                // TODO: Search can be optimized by imploding for sub arrays in sections like address
                if (isset($record['textbloecke']) && is_array($record['textbloecke'])) {
                    $record['processed_textbloecke'] = $this->resolveTextbloeckeText($record['textbloecke']);
                }

                $recordsToIndex[] = $record;
            } catch (ClientException $exception) {
            }
        }

        return $recordsToIndex;
    }

    /**
     * Resolve text of textbloecke
     */
    protected function resolveTextbloeckeText(array $textbloecke): string
    {
        $result = '';
        foreach ($textbloecke as $textblock) {
            if ($textblock['text']) {
                $result .= $textblock['text'] . ',';
            }
        }

        return strip_tags(rtrim($result, ','));
    }

    protected function getRegistry(): Registry
    {
        static $registry = null;

        if ($registry === null) {
            $registry = GeneralUtility::makeInstance(Registry::class);
        }

        return $registry;
    }
}
