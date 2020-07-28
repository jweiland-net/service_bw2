<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Task;

use GuzzleHttp\Exception\ClientException;
use JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository;
use JWeiland\ServiceBw2\Domain\Repository\LeistungenRepository;
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use JWeiland\ServiceBw2\Service\SolrIndexService;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Repository;
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
     * @var Repository
     */
    protected $repository;

    /**
     * @var array
     */
    protected $classMapping = [
        OrganisationseinheitenRepository::class => [
            'method' => 'getRecordsWithChildren',
            'methodLiveById' => 'getLiveOrganisationseinheitById',
            'initialRecordsSettings' => 'settings.organisationseinheiten.listItems',
            'useInitialRecords' => true
        ],
        LeistungenRepository::class => [
            'method' => 'getAll',
            'methodLiveById' => 'getLiveById'
        ],
        LebenslagenRepository::class => [
            'method' => 'getAll',
            'methodLiveById' => 'getLiveLebenslagen'
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
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->repository = $this->objectManager->get($this->typeToIndex);

        if ($this->classMapping[$this->typeToIndex]['useInitialRecords']) {
            $initialRecords = $this->getInitialRecords($this->classMapping[$this->typeToIndex]['initialRecordsSettings']);
            $recordList = call_user_func([$this->repository, $this->classMapping[$this->typeToIndex]['method']], $initialRecords);
        } else {
            $recordList = call_user_func([$this->repository, $this->classMapping[$this->typeToIndex]['method']]);
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
                $record = call_user_func(
                    [$this->repository, $this->classMapping[$this->typeToIndex]['methodLiveById']],
                    $recordToIndex['id']
                );
            } catch (ClientException $exception) {
            }

            // TODO: Search can be optimized by imploding for sub arrays in sections like address
            if ($record['sections']) {
                $record['processed_sections'] = $this->resolveSectionsText($record['sections']);
            }
            if ($record['organisationseinheit']) {
                $record['processed_organisationseinheit'] = $this->multi_implode($record['organisationseinheit'], ',');
            }
            if ($record['preamble']) {
                $record['preamble'] = strip_tags($record['preamble']);
            }

            $recordsToIndex[] = $record;
            if ($recordToIndex['_children']) {
                $recordsToIndex = array_merge($recordsToIndex, $this->getLiveDataForRecords($recordToIndex['_children']));
            }
        }

        return $recordsToIndex;
    }

    /**
     * Resolve sections text
     *
     * @param array $sections
     * @return string
     */
    protected function resolveSectionsText(array $sections): string
    {
        $result = '';
        foreach ($sections as $section) {
            if ($section['text']) {
                $result .= $section['text'] . ',';
            }
        }

        return strip_tags(rtrim($result, ','));
    }

    /**
     * Multi implode
     *
     * @param $array
     * @param $glue
     * @return bool|string
     */
    protected function multi_implode($array, $glue): string
    {
        $result = '';

        foreach ($array as $item) {
            if (is_array($item)) {
                $result .= $this->multi_implode($item, $glue) . $glue;
            } else {
                $result .= $item . $glue;
            }
        }

        $result = substr($result, 0, 0 - strlen($glue));

        return strip_tags($result);
    }
}
