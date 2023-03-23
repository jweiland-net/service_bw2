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

    // Create our own logger file
    if (!isset($GLOBALS['TYPO3_CONF_VARS']['LOG']['JWeiland']['ServiceBw2']['writerConfiguration'])) {
        $GLOBALS['TYPO3_CONF_VARS']['LOG']['JWeiland']['ServiceBw2']['writerConfiguration'] = [
            \Psr\Log\LogLevel::INFO => [
                \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                    'logFileInfix' => 'servicebw2',
                ],
            ],
        ];
    }

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
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:service_bw2/Configuration/TSconfig/ContentElementWizard.tsconfig">'
    );

    // Register an Aspect to map various ID of Service BW API to uid-title
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['ServiceBwTitleMapper'] = \JWeiland\ServiceBw2\Routing\Aspect\ServiceBwTitleMapper::class;
});
