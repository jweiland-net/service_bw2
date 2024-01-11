<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

$pluginConfig = [
    'OrganizationalUnitsList',
    'OrganizationalUnitsShow',
    'ServicesList',
    'ServicesShow',
    'LifeSituationsList',
    'LifeSituationsShow',
    'ServiceBw2Search',
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
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$contentTypeName] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$contentTypeName] = 'select_key';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        $contentTypeName,
        'FILE:EXT:service_bw2/Configuration/FlexForms/ServiceBw2' . $pluginName . '.xml'
    );

    // Plugin Icon
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$contentTypeName] = $iconIdentifier;

    //$GLOBALS['TCA']['tt_content']['types'][$contentTypeName]['previewRenderer'] = \GeorgRinger\News\Hooks\PluginPreviewRenderer::class;
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
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$contentTypeName] = 'recursive, pages';
}
