<?php
namespace JWeiland\ServiceBw2\Controller;

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

use JWeiland\ServiceBw2\Configuration\ExtConf;
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
     * @var ExtConf
     */
    protected $extConf;

    /**
     * @param ExtConf $extConf
     */
    public function injectExtConf(ExtConf $extConf)
    {
        $this->extConf = $extConf;
    }

    /**
     * Add "error while fetching records" error message
     *
     * @param \Exception $exception
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
     */
    protected function setPageTitle(string $title)
    {
        if ($this->settings['overridePageTitle'] === '1') {
            $this->objectManager->get(PageRenderer::class)->setTitle($title);
        }
    }

    /**
     * Initializes the controller before invoking an action method.
     */
    public function initializeAction()
    {
        $this->validateExtConf();
    }

    /**
     * Validates the given ext_emconf by checking
     * if the setting is filled or empty. Throws an exception in case of a
     * misconfiguration.
     *
     * @throws \InvalidArgumentException
     */
    protected function validateExtConf()
    {
        $requiredSettings = ['username', 'password', 'mandant', 'baseUrl', 'allowedLanguages', 'regionIds'];
        $missingSettings = [];
        foreach ($requiredSettings as $requiredSetting) {
            $getterMethodName = 'get' . ucfirst($requiredSetting);
            if (empty($this->extConf->{$getterMethodName}())) {
                $missingSettings[] = $requiredSetting;
            }
        }
        if ($missingSettings) {
            throw new \InvalidArgumentException(
                'The extension service_bw2 requires the following settings: "'
                . implode(', ', $requiredSettings) . '". The following settings are missing: "'
                . implode(', ', $missingSettings) . '"! Please check your configuration in TYPO3'
                . ' backend > Extensions > service_bw2 > Configure.',
                1525787713
            );
        }
    }
}
