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
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\StringFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
        'options' => [],
        'groups' => []
    ];
}

// Solr queue hook
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['postProcessFetchRecordsForIndexQueueItem']['servicebw'] =
    \JWeiland\ServiceBw2\Hook\SolrQueueHook::class . '->addRecordsAfterFetching';
