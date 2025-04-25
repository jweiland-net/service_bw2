#
# Table structure for table 'tx_servicebw2_organisationseinheit'
#
CREATE TABLE tx_servicebw2_organisationseinheit
(
	id             INT(11) DEFAULT 0            NOT NULL,
	hashed_address CHAR(32) DEFAULT '' NOT NULL,
	tx_maps2_poi   INT(11) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (id)
);
