<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(static function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'ServiceBw2',
        'ServiceBw',
        [
            \JWeiland\ServiceBw2\Controller\OrganisationseinheitenController::class => 'list,show',
            \JWeiland\ServiceBw2\Controller\LebenslagenController::class => 'list,show',
            \JWeiland\ServiceBw2\Controller\LeistungenController::class => 'list,show',
            \JWeiland\ServiceBw2\Controller\SucheController::class => 'list',
        ],
        // non-cacheable actions
        [
            \JWeiland\ServiceBw2\Controller\SucheController::class => 'list',
        ]
    );

    // Register caches for requests to Service BW API.
    // Set group to system. So, pages cache can be flushed, without lost of Service BW data.
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['servicebw_request'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
        'options' => [],
        'groups' => [
            0 => 'system',
        ],
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['servicebw_additionalstuff'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
        'options' => [],
        'groups' => [
            0 => 'system',
        ],
    ];

    // Remove sys_registry entry, if System Cache was cleared, to allow switching the Authentication
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['servicebw2_clearcache']
        = \JWeiland\ServiceBw2\Hook\ClearCacheHook::class . '->clearCachePostProc';

    // Register SVG Icon Identifier
    $svgIcons = [
        'ext-servicebw-wizard-icon' => 'plugin_wizard.svg',
    ];
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    foreach ($svgIcons as $identifier => $fileName) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:service_bw2/Resources/Public/Icons/' . $fileName]
        );
    }

    // Add service_bw2 to new element wizard
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:service_bw2/Configuration/TSconfig/ContentElementWizard.txt">'
    );

    // Create scheduler to create/update solr index for service_bw2
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\JWeiland\ServiceBw2\Task\IndexItemsTask::class] = [
        'extension' => 'service_bw2',
        'title' => 'Index service_bw2',
        'description' => 'Re-Generate solr index for service_bw2',
        'additionalFields' => \JWeiland\ServiceBw2\Task\IndexItemsTaskAdditionalFieldProvider::class,
    ];

    // Register an Aspect to map various ID of Service BW API to uid-title
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['ServiceBwTitleMapper'] = \JWeiland\ServiceBw2\Routing\Aspect\ServiceBwTitleMapper::class;
});
