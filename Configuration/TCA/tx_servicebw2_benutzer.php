<?php
$localizationFile = 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xml:tx_servicebw2_benutzer.';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xlf:tx_servicebw2_benutzer',
        'label' => 'uid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'hideTable' => false,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'id,benutzername,vorname,nachname,email,fullname',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('service_bw2') . 'Resources/Public/Icons/tx_servicebw2_benutzer.png'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,id,benutzername,vorname,nachname,email,idp,idp_id,legacy_id,modify_date,create_date,feature_accepted_date,benutzergruppen_string,fullname',
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0]
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_servicebw2_benutzer',
                'foreign_table_where' => 'AND tx_servicebw2_benutzer.pid=###CURRENT_PID### AND tx_servicebw2_benutzer.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'id' => [
            'exclude' => 1,
            'label' => $localizationFile . 'id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'int,trim'
            ],
        ],
        'benutzername' => [
            'exclude' => 1,
            'label' => $localizationFile . 'benutzername',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'vorname' => [
            'exclude' => 1,
            'label' => $localizationFile . 'vorname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'nachname' => [
            'exclude' => 1,
            'label' => $localizationFile . 'nachname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'email' => [
            'exclude' => 1,
            'label' => $localizationFile . 'email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'idp' => [
            'exclude' => 1,
            'label' => $localizationFile . 'idp',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'idp_id' => [
            'exclude' => 1,
            'label' => $localizationFile . 'idp_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'legacy_id' => [
            'exclude' => 1,
            'label' => $localizationFile . 'legacy_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'modify_date' => [
            'exclude' => 1,
            'label' => $localizationFile . 'modify_date',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'create_date' => [
            'exclude' => 1,
            'label' => $localizationFile . 'create_date',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'feature_accepted_date' => [
            'exclude' => 1,
            'label' => $localizationFile . 'feature_accepted_date',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'benutzergruppen_string' => [
            'exclude' => 1,
            'label' => $localizationFile . 'benutzergruppen_string',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'fullname' => [
            'exclude' => 1,
            'label' => $localizationFile . 'fullname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'id,benutzername,vorname,nachname,email,idp,idp_id,legacy_id,modify_date,create_date,feature_accepted_date,benutzergruppen_string,fullname']
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
];
