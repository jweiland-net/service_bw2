<?php

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

ExtensionManagementUtility::addStaticFile(
    'service_bw2',
    'Configuration/TypoScript',
    'Service BW',
);

ExtensionManagementUtility::addStaticFile(
    'service_bw2',
    'Configuration/TypoScript/Solr',
    'Service BW2 - Search',
);
