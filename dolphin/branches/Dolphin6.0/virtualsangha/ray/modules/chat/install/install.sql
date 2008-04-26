-- 
-- Table structure for table `ChatProfiles`
-- 
CREATE TABLE IF NOT EXISTS `[module_db_prefix]Profiles` (
  `ID` varchar(20) NOT NULL default '',
  `Banned` enum('true','false') NOT NULL default 'false',
  `Type` enum('view','text','full','moder') NOT NULL default 'full',
  `Smileset` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
);

TRUNCATE TABLE `[module_db_prefix]Profiles`;
-- 
-- Table structure for table `ChatCurrentUsers`
-- 
CREATE TABLE IF NOT EXISTS `[module_db_prefix]CurrentUsers` (
  `ID` varchar(20) NOT NULL default '',
  `Nick` varchar(36) NOT NULL,
  `RoomID` int(11) NOT NULL default '0',
  `Sex` enum('Male','Female') NOT NULL default 'Male',
  `Age` int(11) NOT NULL default '0',
  `Desc` text NOT NULL default '',
  `Photo` varchar(255) NOT NULL default '',
  `Profile` varchar(255) NOT NULL default '',
  `When` int(11) NOT NULL default '0',
  `Status` enum('new','room','old','idle','kick','type') NOT NULL default 'new',
  PRIMARY KEY  (`ID`)
);

TRUNCATE TABLE `[module_db_prefix]CurrentUsers`;

-- 
-- Table structure for table `ChatMessages`
-- 
CREATE TABLE IF NOT EXISTS `[module_db_prefix]Messages` (
  `ID` int(11) NOT NULL auto_increment,
  `UserID` varchar(20) NOT NULL default '', 
  `Msg` text NOT NULL default '',
  `Style` text NOT NULL,
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
);

TRUNCATE TABLE `[module_db_prefix]Messages`;

-- 
-- Table structure for table `ChatRooms`
-- 
CREATE TABLE IF NOT EXISTS `[module_db_prefix]Rooms` (
  `ID` int(11) NOT NULL auto_increment,  
  `Name` varchar(255) NOT NULL default '',
  `OwnerID` varchar(36) NOT NULL default '',
  `When` int(11) default NULL,
  `Status` enum('normal','delete') NOT NULL default 'normal',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
);

TRUNCATE TABLE `[module_db_prefix]Rooms`;
-- 
-- Dumping data for table `ChatRooms`
-- 
INSERT INTO `[module_db_prefix]Rooms` (`Name`, `OwnerID`, `When`, `Status`) VALUES ('Lobby', '0', '0', 'normal');
INSERT INTO `[module_db_prefix]Rooms` (`Name`, `OwnerID`, `When`, `Status`) VALUES ('Friends', '0', '1', 'normal');