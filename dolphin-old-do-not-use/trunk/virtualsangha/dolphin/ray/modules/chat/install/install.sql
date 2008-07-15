CREATE TABLE IF NOT EXISTS `[module_db_prefix]Profiles` (
  `ID` varchar(20) NOT NULL default '0',
  `Banned` enum('true','false') NOT NULL default 'false',
  `Type` enum('view','text','full','moder') NOT NULL default 'full',
  `Smileset` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
);

CREATE TABLE IF NOT EXISTS `[module_db_prefix]CurrentUsers` (
  `ID` varchar(20) NOT NULL default '',
  `Nick` varchar(36) NOT NULL,
  `Sex` enum('M','F') NOT NULL default 'M',
  `Age` int(11) NOT NULL default '0',
  `Desc` text NOT NULL default '',
  `Photo` varchar(255) NOT NULL default '',
  `Profile` varchar(255) NOT NULL default '',
  `Online` enum('online','busy','away') NOT NULL default 'online',
  `Start` int(11) NOT NULL default '0',
  `When` int(11) NOT NULL default '0',
  `Status` enum('new','old','idle','kick','type','online') NOT NULL default 'new',
  PRIMARY KEY  (`ID`)
);

TRUNCATE TABLE `[module_db_prefix]CurrentUsers`;

CREATE TABLE IF NOT EXISTS `[module_db_prefix]Messages` (
  `ID` int(11) NOT NULL auto_increment,
  `Room` int(11) NOT NULL default '', 
  `Sender` varchar(20) NOT NULL default '', 
  `Recipient` varchar(20) NOT NULL default '', 
  `Whisper` enum('true','false') NOT NULL default 'false', 
  `Message` text NOT NULL default '',
  `Style` text NOT NULL,
  `Type` enum('text','file') NOT NULL default 'text', 
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
);

TRUNCATE TABLE `[module_db_prefix]Messages`;

CREATE TABLE IF NOT EXISTS `[module_db_prefix]Rooms` (
  `ID` int(11) NOT NULL auto_increment,  
  `Name` varchar(255) NOT NULL default '',
  `Password` varchar(255) NOT NULL default '',
  `Desc` TEXT NOT NULL default '',
  `OwnerID` varchar(20) NOT NULL default '0', 
  `When` int(11) default NULL,
  `Status` enum('normal','delete') NOT NULL default 'normal',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
);

TRUNCATE TABLE `[module_db_prefix]Rooms`;
INSERT INTO `[module_db_prefix]Rooms` (`Name`, `OwnerID`, `Desc`, `When`, `Status`) VALUES ('Lobby', '0', 'Welcome to our chat! You are in the \"Lobby\" now, but you can pass into any other public room you wish: take a look at the \"All rooms\" box. If you have any problems with using this chat, there is a \"Help\" button on the right of the top (a question icon). Simply click on it and find the answers to your questions.', '0', 'normal');
INSERT INTO `[module_db_prefix]Rooms` (`Name`, `OwnerID`, `Desc`, `When`, `Status`) VALUES ('Friends', '0', 'Welcome to the \"Friends\" room! This is a public room where you can have a fun chat with existing friends or make new ones! Enjoy!', '1', 'normal');

CREATE TABLE IF NOT EXISTS `[module_db_prefix]RoomsUsers` (
  `ID` int(11) NOT NULL auto_increment,  
  `Room` int(11) NOT NULL default '',
  `User` varchar(20) NOT NULL default '',
  `When` int(11) default NULL,
  `Status` enum('normal','delete') NOT NULL default 'normal',
   PRIMARY KEY  (`ID`)
);
TRUNCATE TABLE `[module_db_prefix]RoomsUsers`;