-- 
-- Table structure for table `IMProfiles`
-- 
CREATE TABLE IF NOT EXISTS `[module_db_prefix]Profiles` (
  `ID` varchar(20) NOT NULL default '0',
  `Smileset` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
);

TRUNCATE TABLE `[module_db_prefix]Profiles`;

-- 
-- Table structure for table `IMContacts`
-- 
CREATE TABLE IF NOT EXISTS `[module_db_prefix]Contacts` (
  `ID` int(11) NOT NULL auto_increment,
  `SenderID` varchar(20) NOT NULL default '',
  `RecipientID` varchar(20) NOT NULL default '',
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
);

TRUNCATE TABLE `[module_db_prefix]Contacts`;

-- 
-- Table structure for table `IMMessages`
-- 
CREATE TABLE IF NOT EXISTS `[module_db_prefix]Messages` (
  `ID` int(11) NOT NULL auto_increment,
  `ContactID` int(11) NOT NULL default '0',  
  `Msg` text NOT NULL default '',
  `Style` varchar(255) NOT NULL default '',
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
);

TRUNCATE TABLE `[module_db_prefix]Messages`;

-- 
-- Table structure for table `IMPendings`
-- 
CREATE TABLE IF NOT EXISTS `[module_db_prefix]Pendings` (
  `ID` int(11) NOT NULL auto_increment,
  `SenderID` varchar(20) NOT NULL default '0',
  `RecipientID` varchar(20) NOT NULL default '0',
  `Msg` varchar(255) NOT NULL default '',
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
);

ALTER TABLE `[module_db_prefix]Pendings` ADD INDEX ( `RecipientID` );
TRUNCATE TABLE `[module_db_prefix]Pendings`;