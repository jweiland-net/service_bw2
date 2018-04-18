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

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
    // RealUrl auto configuration
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration']['service_bw2'] = \JWeiland\ServiceBw2\Hooks\RealUrlAutoConfiguration::class . '->addConfig';
}
