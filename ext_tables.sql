#
# Table structure for table 'tx_servicebw2_organisationseinheit'
#
CREATE TABLE tx_servicebw2_organisationseinheit (
  identifier varchar(250) DEFAULT '0' NOT NULL,

  tx_maps2_poi int(11) unsigned DEFAULT '0' NOT NULL,
  PRIMARY KEY (identifier)
);
