<?php
$localizationFile = 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xml:tx_servicebw2_kontakt_person.';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xlf:tx_servicebw2_kontakt_person',
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
        'searchFields' => 'id,position,infotext,vorname,familienname',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('service_bw2') . 'Resources/Public/Icons/tx_servicebw2_kontakt_person.png'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,id,mandant,anrede,sprechzeiten,titel,position,rolle,infotext,vorname,familienname,raum,gebaeude,foto_asset_id,foto_asset_url,foto_asset_alt_de,foto_asset_alt_en,foto_asset_alt_fr,reihenfolge,legacy_id,has_leitungsfunktion,is_public_in_ma_list,is_public_in_portal,anschrift,zustaendigkeit,gueltigkeit,kommunikation,internet_adresse',
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
                'foreign_table' => 'tx_servicebw2_kontakt_person',
                'foreign_table_where' => 'AND tx_servicebw2_kontakt_person.pid=###CURRENT_PID### AND tx_servicebw2_kontakt_person.sys_language_uid IN (-1,0)',
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
        'anrede' => [
            'exclude' => 1,
            'label' => $localizationFile . 'anrede',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'sprechzeiten' => [
            'exclude' => 1,
            'label' => $localizationFile . 'sprechzeiten',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'titel' => [
            'exclude' => 1,
            'label' => $localizationFile . 'titel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'position' => [
            'exclude' => 1,
            'label' => $localizationFile . 'position',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'rolle' => [
            'exclude' => 1,
            'label' => $localizationFile . 'rolle',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        /*'zustaendigkeit' => [
            'exclude' => 1,
            'label' => $localizationFile . 'zustaendigkeit',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],*/
        'infotext' => [
            'exclude' => 1,
            'label' => $localizationFile . 'infotext',
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
                'eval' => 'trim,required'
            ],
        ],
        'familienname' => [
            'exclude' => 1,
            'label' => $localizationFile . 'familienname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'raum' => [
            'exclude' => 1,
            'label' => $localizationFile . 'raum',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'gebaeude' => [
            'exclude' => 1,
            'label' => $localizationFile . 'gebaeude',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'foto_asset_id' => [
            'exclude' => 1,
            'label' => $localizationFile . 'foto_asset_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'foto_asset_url' => [
            'exclude' => 1,
            'label' => $localizationFile . 'foto_asset_url',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'foto_asset_alt_de' => [
            'exclude' => 1,
            'label' => $localizationFile . 'foto_asset_alt_de',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'foto_asset_alt_en' => [
            'exclude' => 1,
            'label' => $localizationFile . 'foto_asset_alt_en',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'foto_asset_alt_fr' => [
            'exclude' => 1,
            'label' => $localizationFile . 'foto_asset_alt_fr',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'reihenfolge' => [
            'exclude' => 1,
            'label' => $localizationFile . 'reihenfolge',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,int'
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
        'has_leitungsfunktion' => [
            'exclude' => 1,
            'label' => $localizationFile . 'has_leitungsfunktion',
            'config' => [
                'type' => 'check',
            ],
        ],
        'is_published_in_ma_list' => [
            'exclude' => 1,
            'label' => $localizationFile . 'is_published_in_ma_list',
            'config' => [
                'type' => 'check',
            ],
        ],
        'is_published_in_portal' => [
            'exclude' => 1,
            'label' => $localizationFile . 'is_published_in_portal',
            'config' => [
                'type' => 'check',
            ],
        ],
        'anschrift' => [
            'exclude' => 1,
            'label' => $localizationFile . 'anschrift',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_anschrift'
            ],
        ],
        'zustaendigkeit' => [
            'exclude' => 1,
            'label' => $localizationFile . 'zustaendigkeit',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_zustaendigkeit'
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
        'internet_adresse' => [
            'exclude' => 1,
            'label' => $localizationFile . 'internet_adresse',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_internet_adresse'
            ],
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'id,mandant,anrede,sprechzeiten,titel,position,rolle,infotext,vorname,familienname,raum,gebaeude,foto_asset_id,foto_asset_url,foto_asset_alt_de,foto_asset_alt_en,foto_asset_alt_fr,reihenfolge,legacy_id,has_leitungsfunktion,is_public_in_ma_list,is_public_in_portal,anschrift,zustaendigkeit,gueltigkeit,kommunikation,internet_adresse']
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
];
