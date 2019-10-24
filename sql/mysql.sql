# Table structure for table `pedigree_owner`


CREATE TABLE `pedigree_owner` (
  `id`          INT(11)     NOT NULL AUTO_INCREMENT,
  `firstname`   VARCHAR(30) NOT NULL DEFAULT '',
  `lastname`    VARCHAR(30) NOT NULL DEFAULT '',
  `postcode`    VARCHAR(7)  NOT NULL DEFAULT '',
  `city`        VARCHAR(50) NOT NULL DEFAULT '',
  `streetname`  VARCHAR(40) NOT NULL DEFAULT '',
  `housenumber` VARCHAR(6)  NOT NULL DEFAULT '',
  `phonenumber` VARCHAR(14) NOT NULL DEFAULT '',
  `emailadres`  VARCHAR(40) NOT NULL DEFAULT '',
  `website`     VARCHAR(60) NOT NULL DEFAULT '',
  `user`        VARCHAR(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `lastname` (`lastname`(5))
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1
  COMMENT = 'owner information tree';

# --------------------------------------------------------


# Table structure for table `pedigree_tree`


CREATE TABLE `pedigree_tree` (
  `id`         MEDIUMINT(7) UNSIGNED NOT NULL AUTO_INCREMENT,
  `naam`       TEXT                  NOT NULL,
  `id_owner`   SMALLINT(5)           NOT NULL DEFAULT '0',
  `id_breeder` SMALLINT(5)           NOT NULL DEFAULT '0',
  `user`       VARCHAR(25)           NOT NULL DEFAULT '',
  `roft`       ENUM ('0', '1')       NOT NULL DEFAULT '0',
  `mother`     INT(5)                NOT NULL DEFAULT '0',
  `father`     INT(5)                NOT NULL DEFAULT '0',
  `foto`       VARCHAR(255)          NOT NULL DEFAULT '',
  `coi`        VARCHAR(10)           NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mother` (`mother`),
  KEY `father` (`father`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

# --------------------------------------------------------


# Table structure for table `pedigree_fields`


CREATE TABLE `pedigree_fields` (
  `id`                TINYINT(2)                                                                                    NOT NULL AUTO_INCREMENT,
  `isactive`          TINYINT(1)                                                                                    NOT NULL DEFAULT '0',
  `fieldname`         VARCHAR(50)                                                                                   NOT NULL DEFAULT '',
  `fieldtype`         ENUM ('dateselect', 'textbox', 'selectbox', 'radiobutton', 'textarea', 'urlfield', 'picture') NOT NULL DEFAULT 'dateselect',
  `lookuptable`       TINYINT(1)                                                                                    NOT NULL DEFAULT '0',
  `defaultvalue`      VARCHAR(50)                                                                                   NOT NULL DEFAULT '',
  `fieldexplanation`  TINYTEXT                                                                                      NOT NULL,
  `hassearch`         TINYINT(1)                                                                                    NOT NULL DEFAULT '0',
  `litter`            TINYINT(1)                                                                                    NOT NULL DEFAULT '0',
  `generallitter`     TINYINT(1)                                                                                    NOT NULL DEFAULT '0',
  `searchname`        VARCHAR(50)                                                                                   NOT NULL DEFAULT '',
  `searchexplanation` TINYTEXT                                                                                      NOT NULL,
  `viewinpedigree`    TINYINT(1)                                                                                    NOT NULL DEFAULT '0',
  `viewinadvanced`    TINYINT(1)                                                                                    NOT NULL DEFAULT '0',
  `viewinpie`         TINYINT(1)                                                                                    NOT NULL DEFAULT '0',
  `viewinlist`        TINYINT(1)                                                                                    NOT NULL DEFAULT '0',
  `locked`            TINYINT(1)                                                                                    NOT NULL DEFAULT '0',
  `order`             TINYINT(3)                                                                                    NOT NULL DEFAULT '0',
  UNIQUE KEY `ID` (`id`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

# --------------------------------------------------------


# Table structure for table `pedigree_temp`


CREATE TABLE `pedigree_temp` (
  `id`         INT(11)      NOT NULL DEFAULT '0',
  `naam`       TEXT         NOT NULL,
  `id_owner`   INT(11)      NOT NULL DEFAULT '0',
  `id_breeder` INT(11)      NOT NULL DEFAULT '0',
  `user`       VARCHAR(25)  NOT NULL DEFAULT '',
  `roft`       TINYTEXT     NOT NULL,
  `mother`     INT(5)       NOT NULL DEFAULT '0',
  `father`     INT(5)       NOT NULL DEFAULT '0',
  `foto`       VARCHAR(255) NOT NULL DEFAULT '',
  `coi`        VARCHAR(10)  NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mother` (`mother`),
  KEY `father` (`father`)
)
  ENGINE = MyISAM
  COMMENT = 'temporary pedigree table to create detailed extracts';

# --------------------------------------------------------


# Table structure for table `pedigree_trash`


CREATE TABLE `pedigree_trash` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `naam`       TEXT         NOT NULL,
  `id_owner`   INT(11)      NOT NULL DEFAULT '0',
  `id_breeder` INT(11)      NOT NULL DEFAULT '0',
  `user`       VARCHAR(25)  NOT NULL DEFAULT '',
  `roft`       CHAR(1)      NOT NULL DEFAULT '',
  `mother`     INT(5)       NOT NULL DEFAULT '0',
  `father`     INT(5)       NOT NULL DEFAULT '0',
  `foto`       VARCHAR(255) NOT NULL DEFAULT '',
  `coi`        VARCHAR(10)  NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1
  COMMENT = 'pedigree chart for deleted dogs';
