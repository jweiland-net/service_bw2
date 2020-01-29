<?php
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'JWeiland.service_bw2',
    'ServiceBw',
    [
        'Organisationseinheiten' => 'list,show',
        'Lebenslagen' => 'list,show',
        'Leistungen' => 'list,show'
    ],
    // non-cacheable actions
    []
);

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['servicebw_request'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['servicebw_request'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
        'options' => [],
        'groups' => ['all', 'pages']
    ];
}

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
    // RealUrl auto configuration
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration']['service_bw2'] = \JWeiland\ServiceBw2\Hooks\RealUrlAutoConfiguration::class . '->addConfig';
}

// create scheduler to create/update solr index for service_bw2
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\JWeiland\ServiceBw2\Task\IndexItemsTask::class] = [
    'extension' => 'service_bw2',
    'title' => 'Index service_bw2',
    'description' => 'Re-Generate solr index for service_bw2',
    'additionalFields' => \JWeiland\ServiceBw2\Task\IndexItemsTaskAdditionalFieldProvider::class
];
