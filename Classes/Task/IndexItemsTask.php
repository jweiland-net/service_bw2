<?php

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Task;

use GuzzleHttp\Exception\ClientException;
use JWeiland\ServiceBw2\Request\AbstractRequest;
use JWeiland\ServiceBw2\Request\Portal\Lebenslagen;
use JWeiland\ServiceBw2\Request\Portal\Leistungen;
use JWeiland\ServiceBw2\Request\Portal\Organisationseinheiten;
use JWeiland\ServiceBw2\Service\SolrIndexService;
use JWeiland\ServiceBw2\Utility\ServiceBwUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class IndexItemsTask
 */
class IndexItemsTask extends AbstractTask
{
    /**
     * @var string
     */
    public $typeToIndex = '';

    /**
     * @var string
     */
    public $solrConfig = '';

    /**
     * @var string
     */
    public $pluginTtContentUid = '';

    /**
     * @var int
     */
    public $rootPage = 0;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var AbstractRequest
     */
    protected $requestClass;

    /**
     * @var array
     */
    protected $classMapping = [
        Organisationseinheiten::class => [
            'method' => 'findAll',
        ],
        Leistungen::class => [
            'method' => 'findAll'
        ],
        Lebenslagen::class => [
            'method' => 'findAll'
        ]
    ];

    /**
     * Execute task
     *
     * @return bool
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    public function execute(): bool
    {
        $this->typeToIndex = ServiceBwUtility::getRepositoryReplacement($this->typeToIndex);
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->requestClass = $this->objectManager->get($this->typeToIndex);

        $recordList = call_user_func([$this->requestClass, $this->classMapping[$this->typeToIndex]['method']]);
        if ($this->classMapping[$this->typeToIndex] === Organisationseinheiten::class) {
            $initialRecords = $this->getInitialRecords('settings.organisationseinheiten.listItems');
            $recordList = ServiceBwUtility::filterOrganisationseinheitenByParentIds($recordList, $initialRecords);
        }

        $solrIndexService = $this->objectManager->get(SolrIndexService::class);
        $solrIndexService->indexerDeleteByType($this->solrConfig, $this->rootPage);
        $solrIndexService->indexRecords($this->getLiveDataForRecords($recordList), $this->solrConfig, $this->rootPage);

        return true;
    }

    /**
     * This method is designed to return some additional information about the task,
     * that may help to set it apart from other tasks from the same class
     * This additional information is used - for example - in the Scheduler's BE module
     * This method should be implemented in most task classes
     *
     * @return string Information to display
     */
    public function getAdditionalInformation(): string
    {
        return parent::getAdditionalInformation();
    }

    /**
     * Gets initial records
     *
     * @param string $settings
     * @return array
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
        return GeneralUtility::trimExplode(',', $flexform['data']['sDEFAULT']['lDEF'][$settings]['vDEF'], true);
    }

    /**
     * Gets live data of given records
     *
     * @param array $records
     * @return array
     */
    protected function getLiveDataForRecords(array $records): array
    {
        $recordsToIndex = [];

        foreach ($records as $recordToIndex) {
            try {
                $record = $this->requestClass->findById($recordToIndex['id']);
            } catch (ClientException $exception) {
            }

            // TODO: Search can be optimized by imploding for sub arrays in sections like address
            if ($record['textbloecke']) {
                $record['processed_textbloecke'] = $this->resolveTextbloeckeText($record['textbloecke']);
            }
            $recordsToIndex[] = $record;
        }

        return $recordsToIndex;
    }

    /**
     * Resolve text of textbloecke
     *
     * @param array $textbloecke
     * @return string
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
}
