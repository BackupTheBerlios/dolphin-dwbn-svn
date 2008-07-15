-- 
-- Table structure for table `BoardBoards`
-- 
CREATE TABLE IF NOT EXISTS `[module_db_prefix]Boards` (
  `ID` int(11) NOT NULL auto_increment,
  `UserID` varchar(64) NOT NULL,
  `Title` varchar(255) NOT NULL default '',
  `Track` int(11) NOT NULL default '0',
  PRIMARY KEY (`ID`)
);