<?php
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'JWeiland.' . $_EXTKEY,
    'ServiceBw',
    [
        'Service' => '',
    ],
    // non-cacheable actions
    []
);

// Register type converters
// \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(\JWeiland\ServiceBw2\Property\TypeConverter\ServiceBwObjectConverter::class);

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['servicebw_request'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['servicebw_request'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend::class,
        'options' => [],
        'groups' => []
    ];
}
