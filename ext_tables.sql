#
# Table structure for table 'tx_servicebw2_anschrift'
#
CREATE TABLE tx_servicebw2_anschrift (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_bank_verbindung'
#
CREATE TABLE tx_servicebw2_bank_verbindung (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  beschreibung varchar(255) DEFAULT '' NOT NULL,
  empfaenger varchar(255) DEFAULT '' NOT NULL,
  bank_institut varchar(255) DEFAULT '' NOT NULL,
  bank_verbindung_national varchar(255) DEFAULT '' NOT NULL,
  bank_verbindung_international varchar(255) DEFAULT '' NOT NULL,
  gueltigkeit varchar(255) DEFAULT '' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_behoerde'
#
CREATE TABLE tx_servicebw2_behoerde (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_benutzer'
#
CREATE TABLE tx_servicebw2_benutzer (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  benutzername varchar(255) DEFAULT '' NOT NULL,
  vorname varchar(255) DEFAULT '' NOT NULL,
  nachname varchar(255) DEFAULT '' NOT NULL,
  email varchar(255) DEFAULT '' NOT NULL,
  idp varchar(255) DEFAULT '' NOT NULL,
  idp_id varchar(255) DEFAULT '' NOT NULL,
  legacy_id varchar(255) DEFAULT '' NOT NULL,
  modify_date varchar(255) DEFAULT '' NOT NULL,
  create_date varchar(255) DEFAULT '' NOT NULL,
  feature_accepted_date varchar(255) DEFAULT '' NOT NULL,
  benutzergruppen_string varchar(255) DEFAULT '' NOT NULL,
  fullname varchar(255) DEFAULT '' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_geo_kodierung'
#
CREATE TABLE tx_servicebw2_geo_kodierung (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  srs_name varchar(255) DEFAULT '' NOT NULL,
  x int(11) DEFAULT '0' NOT NULL,
  y int(11) DEFAULT '0' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_gueltigkeit'
#
CREATE TABLE tx_servicebw2_gueltigkeit (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  zusatz varchar(255) DEFAULT '' NOT NULL,
  beginn int(11) DEFAULT '0' NOT NULL,
  ende int(11) DEFAULT '0' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_info115'
#
CREATE TABLE tx_servicebw2_info115 (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  teilnehmer varchar(255) DEFAULT '' NOT NULL,
  teilnehmer_nr varchar(255) DEFAULT '' NOT NULL,
  organisations_nummer varchar(255) DEFAULT '' NOT NULL,
  zusatz_info varchar(255) DEFAULT '' NOT NULL,
  barriere_freiheit varchar(255) DEFAULT '' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_internet_adresse'
#
CREATE TABLE tx_servicebw2_internet_adresse (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  mandant varchar(255) DEFAULT '' NOT NULL,
  kennzeichen_anzeige_neues_fenster tinyint(1) DEFAULT '0' NOT NULL,
  uri varchar(255) DEFAULT '' NOT NULL,
  titel varchar(255) DEFAULT '' NOT NULL,
  beschreibung varchar(255) DEFAULT '' NOT NULL,
  alternativ_text varchar(255) DEFAULT '' NOT NULL,
  legacy_id varchar(255) DEFAULT '' NOT NULL,
  position_darstellung varchar(255) DEFAULT '' NOT NULL,
  modify_date int(11) DEFAULT '0' NOT NULL,
  create_date int(11) DEFAULT '0' NOT NULL,
  broken tinyint(1) DEFAULT '0' NOT NULL,
  unused tinyint(1) DEFAULT '0' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_kommunikation'
#
CREATE TABLE tx_servicebw2_kommunikation (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  kanal varchar(255) DEFAULT '' NOT NULL,
  reihenfolge int(11) DEFAULT '0' NOT NULL,
  kennung varchar(255) DEFAULT '' NOT NULL,
  kennungszusatz varchar(255) DEFAULT '' NOT NULL,
  zusatz varchar(255) DEFAULT '' NOT NULL,
  gueltigkeit varchar(255) DEFAULT '' NOT NULL,
  oeffentlich tinyint(1) DEFAULT '0' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_kontakt_person'
#
CREATE TABLE tx_servicebw2_kontakt_person (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  mandant varchar(10) DEFAULT '' NOT NULL,
  anrede varchar(255) DEFAULT '' NOT NULL,
  sprechzeiten varchar(255) DEFAULT '' NOT NULL,
  titel varchar(255) DEFAULT '' NOT NULL,
  position varchar(255) DEFAULT '' NOT NULL,
  rolle varchar(255) DEFAULT '' NOT NULL,
  infotext varchar(255) DEFAULT '' NOT NULL,
  vorname varchar(255) DEFAULT '' NOT NULL,
  familienname varchar(255) DEFAULT '' NOT NULL,
  raum varchar(255) DEFAULT '' NOT NULL,
  gebaeude varchar(255) DEFAULT '' NOT NULL,
  foto_asset_id varchar(255) DEFAULT '' NOT NULL,
  foto_asset_url varchar(255) DEFAULT '' NOT NULL,
  foto_asset_alt_de varchar(255) DEFAULT '' NOT NULL,
  foto_asset_alt_en varchar(255) DEFAULT '' NOT NULL,
  foto_asset_alt_fr varchar(255) DEFAULT '' NOT NULL,
  reihenfolge int(11) DEFAULT '0' NOT NULL,
  legacy_id varchar(255) DEFAULT '' NOT NULL,
  has_leitungsfunktion tinyint(1) DEFAULT '0' NOT NULL,
  is_published_in_ma_list tinyint(1) DEFAULT '0' NOT NULL,
  is_published_in_portal tinyint(1) DEFAULT '0' NOT NULL,
  anschrift varchar(255) DEFAULT '' NOT NULL,
  zustaendigkeit varchar(255) DEFAULT '' NOT NULL,
  gueltigkeit varchar(255) DEFAULT '' NOT NULL,
  kommunikation varchar(255) DEFAULT '' NOT NULL,
  internet_adresse varchar(255) DEFAULT '' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_kontakt_person_mm'
#
CREATE TABLE tx_servicebw2_kontakt_person_mm (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  organisations_einheit_id varchar(255) DEFAULT '' NOT NULL,
  kontakt_person varchar(255) DEFAULT '' NOT NULL,
  kontakt_person_id varchar(255) DEFAULT '' NOT NULL,
  reihenfolge int(11) DEFAULT '0' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_oeffnungszeiten'
#
CREATE TABLE tx_servicebw2_oeffnungszeiten (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  zusatz varchar(255) DEFAULT '' NOT NULL,
  beginn int(11) DEFAULT '0' NOT NULL,
  ende int(11) DEFAULT '0' NOT NULL,
  tages_position varchar(255) DEFAULT '' NOT NULL,
  tages_typ varchar(255) DEFAULT '' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_oeffnungszeiten_mm'
#
CREATE TABLE tx_servicebw2_oeffnungszeiten_mm (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  typ varchar(255) DEFAULT '' NOT NULL,
  hinweis_text varchar(255) DEFAULT '' NOT NULL,
  regulaere_zeiten varchar(255) DEFAULT '' NOT NULL,
  abweichende_zeiten varchar(255) DEFAULT '' NOT NULL,
  gueltigkeit varchar(255) DEFAULT '' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_organisations_einheit'
#
CREATE TABLE tx_servicebw2_organisations_einheit (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  mandant varchar(10) DEFAULT '' NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  kurz_beschreibung varchar(255) DEFAULT '' NOT NULL,
  info_oeffnungszeiten_text varchar(255) DEFAULT '' NOT NULL,
  region_id varchar(255) DEFAULT '' NOT NULL,
  oe_behoerdengruppen varchar(255) DEFAULT '' NOT NULL,
  assigned_behoerden_gruppen varchar(255) DEFAULT '' NOT NULL,
  pfad varchar(255) DEFAULT '' NOT NULL,
  legacy_id int(11) DEFAULT '0' NOT NULL,
  parent_id int(11) DEFAULT '0' NOT NULL,
  asset_id varchar(255) DEFAULT '' NOT NULL,
  asset_url varchar(255) DEFAULT '' NOT NULL,
  asset_alt_text_de varchar(255) DEFAULT '' NOT NULL,
  asset_alt_text_fr varchar(255) DEFAULT '' NOT NULL,
  asset_alt_text_en varchar(255) DEFAULT '' NOT NULL,
  uebergeordnet varchar(255) DEFAULT '' NOT NULL,
  behoerde tinyint(1) unsigned DEFAULT '0' NOT NULL,
  benutzer varchar(255) DEFAULT '' NOT NULL,
  info_oeffnungszeiten_strukturiert varchar(255) DEFAULT '' NOT NULL,
  anschrift varchar(255) DEFAULT '' NOT NULL,
  kommunikation varchar(255) DEFAULT '' NOT NULL,
  kontakt_person varchar(255) DEFAULT '' NOT NULL,
  kommunikations_system varchar(255) DEFAULT '' NOT NULL,
  bank_verbindung varchar(255) DEFAULT '' NOT NULL,
  behoerdenschluessel varchar(10) DEFAULT '' NOT NULL,
  glaeubiger_identifikations_nummer varchar(10) DEFAULT '' NOT NULL,
  gueltigkeit varchar(10) DEFAULT '' NOT NULL,
  info115 varchar(10) DEFAULT '' NOT NULL,
  publish_status varchar(10) DEFAULT '' NOT NULL,
  publish_date int(13) DEFAULT '0' NOT NULL,
  published_version varchar(10) DEFAULT '' NOT NULL,
  version varchar(10) DEFAULT '' NOT NULL,
  modify_date int(13) DEFAULT '0' NOT NULL,
  create_date int(13) DEFAULT '0' NOT NULL,
  created_by varchar(10) DEFAULT '' NOT NULL,
  created_by_mandant varchar(10) DEFAULT '' NOT NULL,
  modified_by varchar(10) DEFAULT '' NOT NULL,
  modified_by_mandant varchar(10) DEFAULT '' NOT NULL,
  release_date int(13) DEFAULT '0' NOT NULL,
  last_published_release_date int(13) DEFAULT '0' NOT NULL,
  zugehoerige_behoerde varchar(255) DEFAULT '' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_schluessel'
#
CREATE TABLE tx_servicebw2_schluessel (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  schluessel varchar(255) DEFAULT '' NOT NULL,
  bezeichnung varchar(255) DEFAULT '' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_verwaltungspolitische_kodierung'
#
CREATE TABLE tx_servicebw2_verwaltungspolitische_kodierung (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  kreisdestatis varchar(255) DEFAULT '' NOT NULL,
  bezirk varchar(255) DEFAULT '' NOT NULL,
  bundesland varchar(255) DEFAULT '' NOT NULL,
  gemeindeschluessel varchar(255) DEFAULT '' NOT NULL,
  regionalschluessel varchar(255) DEFAULT '' NOT NULL,
  staat varchar(255) DEFAULT '' NOT NULL,
  gemeindedeteilschluessel_code varchar(255) DEFAULT '' NOT NULL,
  gemeindedeteilschluessel_name varchar(255) DEFAULT '' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_zustaendigkeit'
#
CREATE TABLE tx_servicebw2_zustaendigkeit (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  zustaendigkeit_id varchar(255) DEFAULT '' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_domain_model_service'
#
CREATE TABLE tx_servicebw2_domain_model_service (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  mandant varchar(10) DEFAULT '' NOT NULL,
  comment varchar(255) DEFAULT '' NOT NULL,
  fim_leistung varchar(255) DEFAULT '' NOT NULL,
  landes_leistung_id int(11) DEFAULT '0' NOT NULL,
  landes_zustaendigkeit_id int(11) DEFAULT '0' NOT NULL,
  type varchar(255) DEFAULT '' NOT NULL,
  display_name varchar(255) DEFAULT '' NOT NULL,
  stufe varchar(255) DEFAULT '' NOT NULL,
  legacy_id int(11) DEFAULT '0' NOT NULL,
  struktur varchar(255) DEFAULT '' NOT NULL,
  verrichtung varchar(255) DEFAULT '' NOT NULL,
  modul_frist varchar(255) DEFAULT '' NOT NULL,
  modul_kosten varchar(255) DEFAULT '' NOT NULL,
  modul_bearbeitungsdauer varchar(255) DEFAULT '' NOT NULL,
  modul_begriff_im_kontext varchar(255) DEFAULT '' NOT NULL,
  modul_fachliche_freigabe varchar(255) DEFAULT '' NOT NULL,
  typisierung varchar(255) DEFAULT '' NOT NULL,
  leika_individuell varchar(255) DEFAULT '' NOT NULL,
  referenz_lei_ka varchar(255) DEFAULT '' NOT NULL,
  gueltigkeits_gebiet varchar(255) DEFAULT '' NOT NULL,
  spezialisiert_fuer_gebiet varchar(255) DEFAULT '' NOT NULL,
  modul_dokument varchar(255) DEFAULT '' NOT NULL,
  modul_text_individuell varchar(255) DEFAULT '' NOT NULL,
  relevant_fuer_wirtschaftszweig varchar(255) DEFAULT '' NOT NULL,
  relevant_fuer_rechtsform varchar(255) DEFAULT '' NOT NULL,
  relevant_fuer_staatsangehoerigkeit varchar(255) DEFAULT '' NOT NULL,
  zusatzinformation varchar(255) DEFAULT '' NOT NULL,
  gueltigkeit varchar(255) DEFAULT '' NOT NULL,
  weitere_information_id int(11) DEFAULT '0' NOT NULL,
  publish_status varchar(10) DEFAULT '' NOT NULL,
  publish_date int(13) DEFAULT '0' NOT NULL,
  published_version varchar(10) DEFAULT '' NOT NULL,
  version varchar(10) DEFAULT '' NOT NULL,
  modify_date int(13) DEFAULT '0' NOT NULL,
  create_date int(13) DEFAULT '0' NOT NULL,
  created_by varchar(10) DEFAULT '' NOT NULL,
  created_by_mandant varchar(10) DEFAULT '' NOT NULL,
  modified_by varchar(10) DEFAULT '' NOT NULL,
  modified_by_mandant varchar(10) DEFAULT '' NOT NULL,
  release_date int(13) DEFAULT '0' NOT NULL,
  last_published_release_date int(13) DEFAULT '0' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_servicebw2_domain_model_keyword'
#
CREATE TABLE tx_servicebw2_domain_model_keyword (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  id int(11) unsigned DEFAULT '0' NOT NULL,
  mandant varchar(10) DEFAULT '' NOT NULL,
  visible_portal tinyint(1) DEFAULT '0' NOT NULL,
  legacy_id int(11) DEFAULT '0' NOT NULL,
  legacy_type varchar(255) DEFAULT '' NOT NULL,
  verwendung varchar(255) DEFAULT '' NOT NULL,
  modify_date int(13) DEFAULT '0' NOT NULL,
  create_date int(13) DEFAULT '0' NOT NULL,

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

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY record_id (id),
  KEY language (l10n_parent,sys_language_uid)
);
