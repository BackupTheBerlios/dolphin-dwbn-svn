CREATE TABLE IF NOT EXISTS `[module_db_prefix]Stats` (
  `User` varchar(64) NOT NULL default '',
  `Approved` int(20) NOT NULL default '0', 
  `Pending` int(20) NOT NULL default '0',
  PRIMARY KEY (`User`)
);

TRUNCATE TABLE `[module_db_prefix]Stats`;

INSERT INTO `[module_db_prefix]Stats` VALUES('', '0', '0');