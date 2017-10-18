<?php
$localizationFile = 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xml:tx_servicebw2_anschrift.';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xlf:tx_servicebw2_anschrift',
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
        'searchFields' => 'id,mandant,strasse,postleitzahl,postfach,ort,zusatz',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('service_bw2') . 'Resources/Public/Icons/tx_servicebw2_anschrift.png'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,id,mandant,type,anfahrtskizze_asset_id,anfahrtskizze_asset_url,strasse,hausnummer,postleitzahl,postfach,ort,ortsteil,zusatz,kennzeichen_aufzug,kennzeichen_rollstuhlgerecht,geo_kodierung,verwaltungspolitische_kodierung,gueltigkeit,kommunikation,kontakt_person',
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
                'foreign_table' => 'tx_servicebw2_anschrift',
                'foreign_table_where' => 'AND tx_servicebw2_anschrift.pid=###CURRENT_PID### AND tx_servicebw2_anschrift.sys_language_uid IN (-1,0)',
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
        'mandant' => [
            'exclude' => 1,
            'label' => $localizationFile . 'mandant',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'type' => [
            'exclude' => 1,
            'label' => $localizationFile . 'type',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'anfahrtskizze_asset_id' => [
            'exclude' => 1,
            'label' => $localizationFile . 'anfahrtskizze_asset_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'anfahrtskizze_asset_url' => [
            'exclude' => 1,
            'label' => $localizationFile . 'anfahrtskizze_asset_url',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'strasse' => [
            'exclude' => 1,
            'label' => $localizationFile . 'strasse',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'hausnummer' => [
            'exclude' => 1,
            'label' => $localizationFile . 'hausnummer',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'postleitzahl' => [
            'exclude' => 1,
            'label' => $localizationFile . 'postleitzahl',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'postfach' => [
            'exclude' => 1,
            'label' => $localizationFile . 'postfach',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'ort' => [
            'exclude' => 1,
            'label' => $localizationFile . 'ort',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'ortsteil' => [
            'exclude' => 1,
            'label' => $localizationFile . 'ortsteil',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'zusatz' => [
            'exclude' => 1,
            'label' => $localizationFile . 'zusatz',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'kennzeichen_aufzug' => [
            'exclude' => 1,
            'label' => $localizationFile . 'kennzeichen_aufzug',
            'config' => [
                'type' => 'check',
            ],
        ],
        'kennzeichen_rollstuhlgerecht' => [
            'exclude' => 1,
            'label' => $localizationFile . 'kennzeichen_rollstuhlgerecht',
            'config' => [
                'type' => 'check',
            ],
        ],
        'geo_kodierungen' => [
            'exclude' => 1,
            'label' => $localizationFile . 'geo_kodierungen',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_geo_kodierung'
            ],
        ],
        'verwaltungspolitische_kodierung' => [
            'exclude' => 1,
            'label' => $localizationFile . 'verwaltungspolitische_kodierung',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_verwaltungspolitische_kodierung'
            ],
        ],
        'gueltigkeit' => [
            'exclude' => 1,
            'label' => $localizationFile . 'gueltigkeit',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_gueltigkeit'
            ],
        ],
        'kommunikation' => [
            'exclude' => 1,
            'label' => $localizationFile . 'kommunikation',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_kommunikation'
            ],
        ],
        'kontakt_person' => [
            'exclude' => 1,
            'label' => $localizationFile . 'kontakt_person',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_kontakt_person'
            ],
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'id,mandant,type,anfahrtskizze_asset_id,anfahrtskizze_asset_url,strasse,hausnummer,postleitzahl,postfach,ort,ortsteil,zusatz,kennzeichen_aufzug,kennzeichen_rollstuhlgerecht,geo_kodierung,verwaltungspolitische_kodierung,gueltigkeit,kommunikation,kontakt_person']
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
];
