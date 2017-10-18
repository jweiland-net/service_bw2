<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'JWeiland.' . $_EXTKEY,
    'ServiceBw',
    'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xlf:plugin.title'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Service BW');
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_service'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_service'] = 'select_key';
//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY . '_service', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/ServiceBw.xml');

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'JWeiland.service_bw2',
        'web',
        'tx_servicebw2_mod1',
        '',
        ['Service' => 'overview,listOrganizationalUnits,responsibilityFinder'],
        [
            'access' => 'user,group',
            'icon' => 'EXT:service_bw2/Resources/Public/Icons/module_service.svg',
            'labels' => 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_mod.xlf',
        ]
    );
}
