<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

ExtensionManagementUtility::addStaticFile(
    'service_bw2',
    'Configuration/TypoScript',
    'Service BW'
);

ExtensionManagementUtility::addStaticFile(
    'service_bw2',
    'Configuration/TypoScript/Solr',
    'Service BW2 - Search'
);
