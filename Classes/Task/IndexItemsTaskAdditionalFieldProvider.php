<?php
namespace JWeiland\ServiceBw2\Task;

/*
 * This file is part of the service_bw2 project.
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
use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class IndexItemsTaskAdditionalFieldProvider
 */
class IndexItemsTaskAdditionalFieldProvider implements AdditionalFieldProviderInterface
{
    /**
     * This fields can not be empty!
     *
     * @var array
     */
    protected $requiredFields = [
        'typeToIndex',
        'solrConfig',
        'pluginTtContentUid',
        'rootPage'
    ];

    /**
     * Fields to insert from task if empty
     *
     * @var array
     */
    protected $insertFields = [
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
    ) {
        foreach ($this->insertFields as $fieldID) {
            if (empty($taskInfo[$fieldID])) {
                $taskInfo[$fieldID] = $task->$fieldID;
            }
        }

        $additionalFields = [];

        $fieldID = 'typeToIndex';
        $fieldCode = '<select name="tx_scheduler[' . $fieldID . ']" class="form-control">' . $this->getTypeToIndexOptions($taskInfo[$fieldID]) . '</select>';
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

    /**
     * self describing
     *
     * @param array $submittedData
     * @param SchedulerModuleController $schedulerModule
     * @return bool
     */
    public function validateAdditionalFields(
        array &$submittedData,
        SchedulerModuleController $schedulerModule
    ) {
        return true;
    }

    /**
     * Saves the submitted data from additional fields
     *
     * @param array $submittedData
     * @param AbstractTask $task
     */
    public function saveAdditionalFields(array $submittedData, AbstractTask $task)
    {
        /** @var IndexItemsTask $task */
        $task->typeToIndex = $submittedData['typeToIndex'];
        $task->solrConfig = $submittedData['solrConfig'];
        $task->pluginTtContentUid = $submittedData['pluginTtContentUid'];
        $task->rootPage = (int)$submittedData['rootPage'];
    }

    /**
     * Gets "typeToIndex" options
     *
     * @param string $selected
     * @return string
     */
    protected function getTypeToIndexOptions($selected = '')
    {
        $availableTypes = [
            'OrganisationsEinheiten' => OrganisationseinheitenRepository::class,
            'Lebenslagen' => LebenslagenRepository::class,
            'Leistungen' => LeistungenRepository::class
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
