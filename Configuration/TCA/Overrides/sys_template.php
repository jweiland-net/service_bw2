<?php
if (!defined('TYPO3')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'service_bw2',
    'Configuration/TypoScript',
    'Service BW'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'service_bw2',
    'Configuration/TypoScript/Solr',
    'Service BW2 - Search'
);
