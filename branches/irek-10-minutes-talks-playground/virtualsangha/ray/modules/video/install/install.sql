CREATE TABLE IF NOT EXISTS `[module_db_prefix]Stats` (
  `Approved` int(20) NOT NULL default '0', 
  `Pending` int(20) NOT NULL default '0'
);

INSERT INTO `[module_db_prefix]Stats` VALUES('0', '0');