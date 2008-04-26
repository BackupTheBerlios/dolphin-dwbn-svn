-- 
-- Table structure for table `ShoutboxMessages`
-- 
CREATE TABLE IF NOT EXISTS `[module_db_prefix]Messages` (
  `ID` int(11) NOT NULL auto_increment,
  `UserID` varchar(20) NOT NULL default '0', 
  `Msg` text NOT NULL default '',
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
);

TRUNCATE TABLE `[module_db_prefix]Messages`;