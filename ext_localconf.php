<?php

use Psr\Log\LogLevel;
use JWeiland\ServiceBw2\Controller\LebenslagenController;
use JWeiland\ServiceBw2\Controller\LeistungenController;
use JWeiland\ServiceBw2\Controller\OrganisationseinheitenController;
use JWeiland\ServiceBw2\Controller\SucheController;
use JWeiland\ServiceBw2\Hook\ClearCacheHook;
use JWeiland\ServiceBw2\Routing\Aspect\ServiceBwTitleMapper;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend;
use TYPO3\CMS\Core\Log\Writer\FileWriter;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

call_user_func(static function () {
    // 1. Organizational Units List Plugin
    ExtensionUtility::configurePlugin(
        'ServiceBw2',
        'OrganizationalUnitsList',
        [
            OrganisationseinheitenController::class => 'list, show',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    // 2. Organizational Units Show Plugin
    ExtensionUtility::configurePlugin(
        'ServiceBw2',
        'OrganizationalUnitsShow',
        [
            OrganisationseinheitenController::class => 'show',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    // 3. Services List Plugin
    ExtensionUtility::configurePlugin(
        'ServiceBw2',
        'ServicesList',
        [
            LeistungenController::class => 'list, show',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    // 4. Services Show Plugin
    ExtensionUtility::configurePlugin(
        'ServiceBw2',
        'ServicesShow',
        [
            LeistungenController::class => 'show',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    // 5. Life Situations List Plugin
    ExtensionUtility::configurePlugin(
        'ServiceBw2',
        'LifeSituationsList',
        [
            LebenslagenController::class => 'list, show',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    // 6. Life Situations Show Plugin
    ExtensionUtility::configurePlugin(
        'ServiceBw2',
        'LifeSituationsShow',
        [
            LebenslagenController::class => 'show',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    // 7. Search Plugin
    ExtensionUtility::configurePlugin(
        'ServiceBw2',
        'ServiceBw2Search',
        [
            SucheController::class => 'list',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    // Create our own logger file
    if (!isset($GLOBALS['TYPO3_CONF_VARS']['LOG']['JWeiland']['ServiceBw2']['writerConfiguration'])) {
        $GLOBALS['TYPO3_CONF_VARS']['LOG']['JWeiland']['ServiceBw2']['writerConfiguration'] = [
            LogLevel::INFO => [
                FileWriter::class => [
                    'logFileInfix' => 'servicebw2',
                ],
            ],
        ];
    }

    // Register caches for requests to Service BW API.
    // Set group to system. So, pages cache can be flushed, without lost of Service BW data.
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['servicebw_request'] = [
        'frontend' => VariableFrontend::class,
        'backend' => Typo3DatabaseBackend::class,
        'options' => [],
        'groups' => [
            0 => 'system',
        ],
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['servicebw_additionalstuff'] = [
        'frontend' => VariableFrontend::class,
        'backend' => Typo3DatabaseBackend::class,
        'options' => [],
        'groups' => [
            0 => 'system',
        ],
    ];

    // Remove sys_registry entry, if System Cache was cleared, to allow switching the Authentication
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['servicebw2_clearcache']
        = ClearCacheHook::class . '->clearCachePostProc';

    // Register an Aspect to map various ID of Service BW API to uid-title
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['ServiceBwTitleMapper'] = ServiceBwTitleMapper::class;
});
