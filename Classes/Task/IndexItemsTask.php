<?php
namespace JWeiland\ServiceBw2\Task;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository;
use JWeiland\ServiceBw2\Domain\Repository\LeistungenRepository;
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use JWeiland\ServiceBw2\Service\SolrIndexService;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class IndexItemsTask
 *
 * @package JWeiland\ServiceBw2\Task
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
     * @var array
     */
    protected $classMapping = [
        OrganisationseinheitenRepository::class => [
            'method' => 'getRecordsWithChildren',
            'initialRecordsSettings' => 'settings.organisationseinheiten.listItems',
            'useInitialRecords' => true
        ],
        LeistungenRepository::class => [
            'method' => 'getAll'
        ],
        LebenslagenRepository::class => [
            'method' => 'getAll'
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
        $repository = $this->objectManager->get($this->typeToIndex);
        $mapping = $this->classMapping[$this->typeToIndex];

        if ($mapping['useInitialRecords']) {
            $initialRecords = $this->getInitialRecords($mapping['initialRecordsSettings']);
            $recordsToIndex = call_user_func([$repository, $mapping['method']], $initialRecords);
        } else {
            $recordsToIndex = call_user_func([$repository, $mapping['method']]);
        }

        $solrIndexService = $this->objectManager->get(SolrIndexService::class);
        $solrIndexService->indexerDeleteByType($this->solrConfig, $this->rootPage);
        $solrIndexService->indexRecords($recordsToIndex, $this->solrConfig, $this->rootPage);

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
        return explode(',', $flexform['data']['sDEFAULT']['lDEF'][$settings]['vDEF']);
    }
}
