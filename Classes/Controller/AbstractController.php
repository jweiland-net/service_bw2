<?php
namespace JWeiland\ServiceBw2\Controller;

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

use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class AbstractController
 */
abstract class AbstractController extends ActionController
{
    /**
     * Add "error while fetching records" error message
     *
     * @param \Exception $exception
     * @return void
     */
    protected function addErrorWhileFetchingRecordsMessage(\Exception $exception)
    {
        $this->addFlashMessage(
            LocalizationUtility::translate('error_message.error_while_fetching_records', 'service_bw2'),
            '',
            AbstractMessage::ERROR
        );
        GeneralUtility::sysLog(
            'Got the following exception while fetching records: ' . $exception->getMessage(),
            'service_bw2',
            GeneralUtility::SYSLOG_SEVERITY_ERROR
        );
    }

    /**
     * Sets the page title if the setting
     * overridePageTitle equals 1
     *
     * @param string $title
     * @return void
     */
    protected function setPageTitle(string $title)
    {
        if ($this->settings['overridePageTitle'] === '1') {
            $this->objectManager->get(PageRenderer::class)->setTitle($title);
        }
    }
}
