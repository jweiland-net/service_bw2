<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

$pluginConfig = [
    'organizational_units_list',
    'organizational_units_show',
    'services_list',
    'services_show',
    'life_situations_list',
    'life_situations_show',
    'service_bw2_search',
];

foreach ($pluginConfig as $pluginName) {
    $contentTypeName = 'servicebw2_' . strtolower(str_replace('_', '', $pluginName));
    $iconIdentifier = 'ext-' . str_replace('_', '-', $contentTypeName) . '-icon';

    // Plugin Registration
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'ServiceBw2',
        \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($pluginName),
        'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xlf:plugin.' . $contentTypeName . '.title',
        $iconIdentifier,
        'ServiceBw2'
    );

    // FlexForm Configurations
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:service_bw2/Configuration/FlexForms/ServiceBw2FlexFormConfiguration.xml',
        $contentTypeName
    );

    // Plugin Icon
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$contentTypeName] = $iconIdentifier;

    $GLOBALS['TCA']['tt_content']['types'][$contentTypeName]['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,
            pi_flexform,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    ';

    // Remove fields pages and recursive
    $GLOBALS['TCA']['tt_content']['types'][$contentTypeName]['subtypes_excludelist'][$contentTypeName] = 'recursive, pages';
}
