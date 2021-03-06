<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Controller;

use GuzzleHttp\Exception\ClientException;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use JWeiland\ServiceBw2\Service\TypoScriptService;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Log\LogManagerInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

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
     * @var LoggerInterface
     */
    protected $logger;

    public function injectExtConf(ExtConf $extConf): void
    {
        $this->extConf = $extConf;
    }

    public function injectLogger(LogManagerInterface $logManager): void
    {
        $this->logger = $logManager->getLogger(__CLASS__);
    }

    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;

        $typoScriptSettings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'servicebw2',
            'servicebw2_servicebw' // invalid plugin name, to get fresh unmerged settings
        );
        if (empty($typoScriptSettings['settings'])) {
            throw new \Exception('You have forgotten to add TS-Template of service_bw2', 1580294227);
        }
        $mergedFlexFormSettings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
        );

        // start override
        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        $typoScriptService->override(
            $mergedFlexFormSettings,
            $typoScriptSettings['settings']
        );
        $this->settings = $mergedFlexFormSettings;
    }

    protected function callActionMethod(): void
    {
        try {
            parent::callActionMethod();
        } catch (ClientException $clientException) {
            $this->logger->error(
                sprintf('Client exception in  %s', __CLASS__),
                [
                    'exception' => $clientException,
                    'controller' => __CLASS__,
                    'action' => $this->actionMethodName,
                    'arguments' => $this->request->getArguments()
                ]
            );
            $this->view->assign('exception', $clientException);
            $this->response->setStatus($clientException->getCode());
            $this->response->setContent($this->view->render('ApiError'));
        }
    }

    /**
     * Sets the page title if the setting
     * overridePageTitle equals 1
     *
     * @param string $title
     */
    protected function setPageTitle(string $title): void
    {
        if ($this->settings['overridePageTitle'] === '1') {
            $this->objectManager->get(PageRenderer::class)->setTitle($title);
        }
    }

    /**
     * Initializes the controller before invoking an action method.
     */
    public function initializeAction(): void
    {
        $this->validateExtConf();

        // if this value was not set, then it will be filled with 0
        // but that is not good, because UriBuilder accepts 0 as pid, so it's better to set it to NULL
        $this->settings['organisationseinheiten']['pidOfListPage'] ?: null;
        $this->settings['organisationseinheiten']['pidOfDetailPage'] ?: null;
        $this->settings['leistungen']['pidOfListPage'] ?: null;
        $this->settings['leistungen']['pidOfDetailPage'] ?: null;
        $this->settings['lebenslagen']['pidOfListPage'] ?: null;
        $this->settings['lebenslagen']['pidOfDetailPage'] ?: null;
    }

    /**
     * Validates the given ext_emconf by checking
     * if the setting is filled or empty. Throws an exception in case of a
     * misconfiguration.
     *
     * @throws \InvalidArgumentException
     */
    protected function validateExtConf(): void
    {
        $requiredSettings = ['username', 'password', 'mandant', 'baseUrl', 'allowedLanguages'];
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
