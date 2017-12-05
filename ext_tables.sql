#
# Table structure for table 'tx_servicebw2_organisationseinheit'
#
CREATE TABLE tx_servicebw2_organisationseinheit (
  identifier varchar(250) DEFAULT '0' NOT NULL,

  tx_maps2_poi int(11) unsigned DEFAULT '0' NOT NULL,
  mandant varchar(10) DEFAULT '' NOT NULL,
  type varchar(255) DEFAULT '' NOT NULL,
  anfahrtskizze_asset_id varchar(255) DEFAULT '' NOT NULL,
  anfahrtskizze_asset_url varchar(255) DEFAULT '' NOT NULL,
  strasse varchar(255) DEFAULT '' NOT NULL,
  hausnummer varchar(255) DEFAULT '' NOT NULL,
  postleitzahl varchar(255) DEFAULT '' NOT NULL,
  postfach varchar(255) DEFAULT '' NOT NULL,
  ort varchar(255) DEFAULT '' NOT NULL,
  ortsteil varchar(255) DEFAULT '' NOT NULL,
  zusatz varchar(255) DEFAULT '' NOT NULL,
  kennzeichen_aufzug varchar(255) DEFAULT '' NOT NULL,
  kennzeichen_rollstuhlgerecht varchar(255) DEFAULT '' NOT NULL,
  gueltigkeit varchar(255) DEFAULT '' NOT NULL,
  kommunikation varchar(255) DEFAULT '' NOT NULL,
  kontakt_person varchar(255) DEFAULT '' NOT NULL,

  tstamp int(11) unsigned DEFAULT '0' NOT NULL,
  crdate int(11) unsigned DEFAULT '0' NOT NULL,
  cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
  deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
  hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
  starttime int(11) unsigned DEFAULT '0' NOT NULL,
  endtime int(11) unsigned DEFAULT '0' NOT NULL,

  t3_origuid int(11) DEFAULT '0' NOT NULL,
  sys_language_uid int(11) DEFAULT '0' NOT NULL,
  l10n_parent int(11) DEFAULT '0' NOT NULL,
  l10n_diffsource mediumblob,

  PRIMARY KEY (uid)
);
