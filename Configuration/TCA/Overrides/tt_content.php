<?php
if (!defined('TYPO3')) {
    die('Access denied.');
}

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['servicebw2_servicebw'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['servicebw2_servicebw'] = 'select_key';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'servicebw2_servicebw',
    'FILE:EXT:service_bw2/Configuration/FlexForms/ServiceBw2.xml'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'ServiceBw2',
    'ServiceBw',
    'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xlf:plugin.servicebw.title'
);
