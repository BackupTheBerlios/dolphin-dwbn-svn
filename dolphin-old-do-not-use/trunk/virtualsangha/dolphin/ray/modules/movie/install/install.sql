-- 
-- Table structure for table `Mp3Files`
-- 
CREATE TABLE IF NOT EXISTS `[module_db_prefix]Files` (
  `ID` int(11) NOT NULL auto_increment,
  `CategoryId` int(11) NOT NULL default '-1',
  `Title` varchar(255) NOT NULL default '',
  `Uri` varchar(255) NOT NULL default '',
  `Tags` TEXT NOT NULL default '',
  `Description` TEXT NOT NULL default '',
  `Time` int(11) NOT NULL default '0',
  `Date` int(20) NOT NULL default '0',
  `Reports` int(11) NOT NULL default '0',
  `Owner` varchar(64) NOT NULL default '',
  `Approved` enum('true','false') NOT NULL default 'true',
   PRIMARY KEY  (`ID`),
  KEY `CatalogId` (`CategoryId`,`Owner`)
);

-- 
-- Table structure for table `Mp3PlayLists`
-- 
CREATE TABLE IF NOT EXISTS `[module_db_prefix]PlayLists` (
  `FileId` int(11) NOT NULL default '0',
  `Owner` varchar(64) NOT NULL default '',
  `Order` tinyint(4) NOT NULL default '0',
  KEY `FileId` (`FileId`,`Owner`)
);