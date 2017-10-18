<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'JWeiland.' . $_EXTKEY,
    'ServiceBw',
    array(
        'Service' => '',
    ),
    // non-cacheable actions
    array()
);

if (TYPO3_MODE === 'BE') {
    // create scheduler to create/update days with recurrency
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\JWeiland\ServiceBw2\Task\SynchronizeServiceBw::class] = [
        'extension' => $_EXTKEY,
        'title' => 'Synchronize with Service BW',
        'description' => 'Synchronize local records with Service BW',
        'additionalFields' => \JWeiland\ServiceBw2\Task\AdditionalFieldsForSynchronization::class,
    ];
}

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
