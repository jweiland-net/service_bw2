<?php
$localizationFile = 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xml:tx_servicebw2_domain_model_organizationalunit.';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xlf:tx_servicebw2_domain_model_organizationalunit',
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
        'searchFields' => 'id,mandant,behoerdenschluessel',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('service_bw2') . 'Resources/Public/Icons/tx_servicebw2_domain_model_organizationalunit.png'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,id,mandant,name,kurz_beschreibung,info_oeffnungszeiten_text,region_id,oe_behoerdengruppen,assigned_behoerden_gruppen,pfad,legacy_id,parent_id,asset_id,asset_url,asset_alt_text_de,asset_alt_text_fr,asset_alt_text_en,uebergeordnet,behoerde,benutzer,info_oeffnungszeiten_strukturiert,anschrift,kommunikation,kontakt_person,kumminukations_system,bank_verbindung,behoerdenschluessel,glaeubiger_identifikations_nummer,gueltigkeit,info115,publish_status,publish_date,published_version,version,modify_date,create_date,created_by,created_by_mandant,modified_by,modified_by_mandant,release_date,last_published_release_date,zugehoerige_behoerde',
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
                'foreign_table' => 'tx_servicebw2_domain_model_organizationalunit',
                'foreign_table_where' => 'AND tx_servicebw2_domain_model_organizationalunit.pid=###CURRENT_PID### AND tx_servicebw2_domain_model_organizationalunit.sys_language_uid IN (-1,0)',
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
        'name' => [
            'exclude' => 1,
            'label' => $localizationFile . 'name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'kurz_beschreibung' => [
            'exclude' => 1,
            'label' => $localizationFile . 'kurz_beschreibung',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'info_oeffnungszeiten_text' => [
            'exclude' => 1,
            'label' => $localizationFile . 'info_oeffnungszeiten_text',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'region_id' => [
            'exclude' => 1,
            'label' => $localizationFile . 'region_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'oe_behoerdengruppen' => [
            'exclude' => 1,
            'label' => $localizationFile . 'oe_behoerdengruppen',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'assigned_behoerden_gruppen' => [
            'exclude' => 1,
            'label' => $localizationFile . 'assigned_behoerden_gruppen',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'pfad' => [
            'exclude' => 1,
            'label' => $localizationFile . 'pfad',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'legacy_id' => [
            'exclude' => 1,
            'label' => $localizationFile . 'legacy_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'int,trim,required'
            ],
        ],
        'parent_id' => [
            'exclude' => 1,
            'label' => $localizationFile . 'parent_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'int,trim,required'
            ],
        ],
        'asset_id' => [
            'exclude' => 1,
            'label' => $localizationFile . 'asset_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'asset_url' => [
            'exclude' => 1,
            'label' => $localizationFile . 'asset_url',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'asset_alt_text_de' => [
            'exclude' => 1,
            'label' => $localizationFile . 'asset_alt_text_de',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'asset_alt_text_fr' => [
            'exclude' => 1,
            'label' => $localizationFile . 'asset_alt_text_fr',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'asset_alt_text_en' => [
            'exclude' => 1,
            'label' => $localizationFile . 'asset_alt_text_en',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'uebergeordnet' => [
            'exclude' => 1,
            'label' => $localizationFile . 'uebergeordnet',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'behoerde' => [
            'exclude' => 1,
            'label' => $localizationFile . 'behoerde',
            'config' => [
                'type' => 'check',
            ],
        ],
        'benutzer' => [
            'exclude' => 1,
            'label' => $localizationFile . 'benutzer',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_benutzer'
            ],
        ],
        'info_oeffnungszeiten_strukturiert' => [
            'exclude' => 1,
            'label' => $localizationFile . 'info_oeffnungszeiten_strukturiert',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_oeffnungszeiten_mm'
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
        'kommunikations_system' => [
            'exclude' => 1,
            'label' => $localizationFile . 'kommunikations_system',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_kommunikation'
            ],
        ],
        'bank_verbindung' => [
            'exclude' => 1,
            'label' => $localizationFile . 'bank_verbindung',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_bank_verbindung'
            ],
        ],
        'behoerdenschluessel' => [
            'exclude' => 1,
            'label' => $localizationFile . 'behoerdenschluessel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'glaeubiger_identifikations_nummer' => [
            'exclude' => 1,
            'label' => $localizationFile . 'glaeubiger_identifikations_nummer',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
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
        'info115' => [
            'exclude' => 1,
            'label' => $localizationFile . 'info115',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_info115'
            ],
        ],
        'publish_status' => [
            'exclude' => 1,
            'label' => $localizationFile . 'publish_status',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'publish_date' => [
            'exclude' => 1,
            'label' => $localizationFile . 'publish_date',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'published_version' => [
            'exclude' => 1,
            'label' => $localizationFile . 'published_version',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'version' => [
            'exclude' => 1,
            'label' => $localizationFile . 'version',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'modify_date' => [
            'exclude' => 1,
            'label' => $localizationFile . 'modify_date',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'create_date' => [
            'exclude' => 1,
            'label' => $localizationFile . 'create_date',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'created_by' => [
            'exclude' => 1,
            'label' => $localizationFile . 'created_by',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'created_by_mandant' => [
            'exclude' => 1,
            'label' => $localizationFile . 'created_by_mandant',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'modified_by' => [
            'exclude' => 1,
            'label' => $localizationFile . 'modified_by',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'modified_by_mandant' => [
            'exclude' => 1,
            'label' => $localizationFile . 'modified_by_mandant',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'release_date' => [
            'exclude' => 1,
            'label' => $localizationFile . 'release_date',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'last_published_release_date' => [
            'exclude' => 1,
            'label' => $localizationFile . 'last_published_release_date',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'zugehoerige_behoerde' => [
            'exclude' => 1,
            'label' => $localizationFile . 'zugehoerige_behoerde',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_behoerde'
            ],
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'id,mandant,name,kurz_beschreibung,info_oeffnungszeiten_text,region_id,oe_behoerdengruppen,assigned_behoerden_gruppen,pfad,legacy_id,parent_id,asset_id,asset_url,asset_alt_text_de,asset_alt_text_fr,asset_alt_text_en,uebergeordnet,behoerde,benutzer,info_oeffnungszeiten_strukturiert,anschrift,kommunikation,kontakt_person,kumminukations_system,bank_verbindung,behoerdenschluessel,glaeubiger_identifikations_nummer,gueltigkeit,info115,publish_status,publish_date,published_version,version,modify_date,create_date,created_by,created_by_mandant,modified_by,modified_by_mandant,release_date,last_published_release_date,zugehoerige_behoerde']
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
];
