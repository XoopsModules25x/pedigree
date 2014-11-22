# Table structure for table `pedigree_owner`


CREATE TABLE `pedigree_owner` (
  `ID` int(11) NOT NULL auto_increment,
  `firstname` varchar(30) NOT NULL default '',
  `lastname` varchar(30) NOT NULL default '',
  `postcode` varchar(7) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `streetname` varchar(40) NOT NULL default '',
  `housenumber` varchar(6) NOT NULL default '',
  `phonenumber` varchar(14) NOT NULL default '',
  `emailadres` varchar(40) NOT NULL default '',
  `website` varchar(60) NOT NULL default '',
  `user` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `lastname` (`lastname`(5))
) ENGINE=MyISAM AUTO_INCREMENT=1  COMMENT='owner information tree';

# --------------------------------------------------------


# Table structure for table `pedigree_tree`


CREATE TABLE `pedigree_tree` (
  `ID` mediumint(7) unsigned NOT NULL auto_increment,
  `NAAM` text NOT NULL,
  `id_owner` smallint(5) NOT NULL default '0',
  `id_breeder` smallint(5) NOT NULL default '0',
  `user` varchar(25) NOT NULL default '',
  `roft` enum('0','1') NOT NULL default '0',
  `mother` int(5) NOT NULL default '0',
  `father` int(5) NOT NULL default '0',
  `foto` varchar(255) NOT NULL default '',
  `coi` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `mother` (`mother`),
  KEY `father` (`father`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------


# Table structure for table `pedigree_fields`


CREATE TABLE `pedigree_fields` (
  `ID` tinyint(2) NOT NULL auto_increment,
  `isActive` tinyint(1) NOT NULL default '0',
  `FieldName` varchar(50) NOT NULL default '',
  `FieldType` enum('dateselect','textbox','selectbox','radiobutton','textarea','urlfield','Picture') NOT NULL default 'dateselect',
  `LookupTable` tinyint(1) NOT NULL default '0',
  `DefaultValue` varchar(50) NOT NULL default '',
  `FieldExplenation` tinytext NOT NULL,
  `HasSearch` tinyint(1) NOT NULL default '0',
  `Litter` tinyint(1) NOT NULL default '0',
  `Generallitter` tinyint(1) NOT NULL default '0',
  `SearchName` varchar(50) NOT NULL default '',
  `SearchExplenation` tinytext NOT NULL,
  `ViewInPedigree` tinyint(1) NOT NULL default '0',
  `ViewInAdvanced` tinyint(1) NOT NULL default '0',
  `ViewInPie` tinyint(1) NOT NULL default '0',
  `ViewInList` tinyint(1) NOT NULL default '0',
  `locked` tinyint(1) NOT NULL default '0',
  `order` tinyint(3) NOT NULL default '0',
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

# --------------------------------------------------------


# Table structure for table `pedigree_temp`


CREATE TABLE `pedigree_temp` (
  `ID` int(11) NOT NULL default '0',
  `NAAM` text NOT NULL,
  `id_owner` int(11) NOT NULL default '0',
  `id_breeder` int(11) NOT NULL default '0',
  `user` varchar(25) NOT NULL default '',
  `roft` tinytext NOT NULL,
  `mother` int(5) NOT NULL default '0',
  `father` int(5) NOT NULL default '0',
  `foto` varchar(255) NOT NULL default '',
  `coi` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `mother` (`mother`),
  KEY `father` (`father`)
) ENGINE=MyISAM COMMENT='temporary pedigree table to create detailed extracts';

# --------------------------------------------------------


# Table structure for table `pedigree_trash`


CREATE TABLE `pedigree_trash` (
  `ID` int(11) NOT NULL auto_increment,
  `NAAM` text NOT NULL,
  `id_owner` int(11) NOT NULL default '0',
  `id_breeder` int(11) NOT NULL default '0',
  `user` varchar(25) NOT NULL default '',
  `roft` char(1) NOT NULL default '',
  `mother` int(5) NOT NULL default '0',
  `father` int(5) NOT NULL default '0',
  `foto` varchar(255) NOT NULL default '',
  `coi` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 COMMENT='pedigree chart for deleted dogs' ;
