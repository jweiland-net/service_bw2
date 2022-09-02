<?php

/*
 * This file is part of the package jweiland/service-bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Task;

use JWeiland\ServiceBw2\Request\Portal\Lebenslagen;
use JWeiland\ServiceBw2\Request\Portal\Leistungen;
use JWeiland\ServiceBw2\Request\Portal\Organisationseinheiten;
use JWeiland\ServiceBw2\Utility\ServiceBwUtility;
use TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class IndexItemsTaskAdditionalFieldProvider
 */
class IndexItemsTaskAdditionalFieldProvider extends AbstractAdditionalFieldProvider
{
    /**
     * This fields can not be empty!
     */
    protected array $requiredFields = [
        'typeToIndex',
        'solrConfig',
        'pluginTtContentUid',
        'rootPage'
    ];

    /**
     * Fields to insert from task if empty
     */
    protected array $insertFields = [
        'typeToIndex',
        'solrConfig',
        'pluginTtContentUid',
        'rootPage'
    ];

    /**
     * Gets the additional fields
     *
     * @param array $taskInfo
     * @param IndexItemsTask $task
     * @param SchedulerModuleController $schedulerModule
     * @return array
     */
    public function getAdditionalFields(
        array &$taskInfo,
        $task,
        SchedulerModuleController $schedulerModule
    ): array {
        foreach ($this->insertFields as $fieldID) {
            if (empty($taskInfo[$fieldID])) {
                $taskInfo[$fieldID] = $task->$fieldID;
            }
        }

        $additionalFields = [];

        $fieldID = 'typeToIndex';
        $fieldCode = '<select name="tx_scheduler[' . $fieldID . ']" class="form-control">' . $this->getTypeToIndexOptions((string)$taskInfo[$fieldID]) . '</select>';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_scheduler_indexitems.xlf:' . $fieldID
        ];

        $fieldID = 'solrConfig';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[' . $fieldID . ']" id="' . $fieldID . '" value="' . htmlspecialchars($taskInfo[$fieldID]) . '" size="30" placeholder="type that is defined in solr index queue"/>';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_scheduler_indexitems.xlf:' . $fieldID
        ];

        $fieldID = 'pluginTtContentUid';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[' . $fieldID . ']" id="' . $fieldID . '" value="' . htmlspecialchars($taskInfo[$fieldID]) . '" size="30" placeholder="plugin tt_content uid"/>';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_scheduler_indexitems.xlf:' . $fieldID
        ];

        $fieldID = 'rootPage';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[' . $fieldID . ']" id="' . $fieldID . '" value="' . htmlspecialchars($taskInfo[$fieldID]) . '" size="30" placeholder="root page uid"/>';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_scheduler_indexitems.xlf:' . $fieldID
        ];

        return $additionalFields;
    }

    public function validateAdditionalFields(
        array &$submittedData,
        SchedulerModuleController $schedulerModule
    ): bool {
        return true;
    }

    public function saveAdditionalFields(array $submittedData, AbstractTask $task): void
    {
        /** @var IndexItemsTask $task */
        $task->typeToIndex = $submittedData['typeToIndex'];
        $task->solrConfig = $submittedData['solrConfig'];
        $task->pluginTtContentUid = $submittedData['pluginTtContentUid'];
        $task->rootPage = (int)$submittedData['rootPage'];
    }

    protected function getTypeToIndexOptions(string $selected = ''): string
    {
        $selected = ServiceBwUtility::getRepositoryReplacement($selected);
        $availableTypes = [
            'Organisationseinheiten' => Organisationseinheiten::class,
            'Lebenslagen' => Lebenslagen::class,
            'Leistungen' => Leistungen::class
        ];

        $optionString = '';

        foreach ($availableTypes as $type => $repository) {
            $optionString .= sprintf(
                '<option%s value="%s">%s</option>',
                $selected === $repository ? ' selected' : '',
                $repository,
                $type
            );
        }

        return $optionString;
    }
}
