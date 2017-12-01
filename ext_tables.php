<?php
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'JWeiland.service_bw2',
    'ServiceBw',
    'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xlf:plugin.title'
);

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

