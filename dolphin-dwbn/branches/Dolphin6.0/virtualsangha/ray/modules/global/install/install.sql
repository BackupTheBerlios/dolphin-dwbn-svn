-- 
-- Table structure for table `GlobalTrackUsers`
-- 
CREATE TABLE IF NOT EXISTS `[db_prefix]GlobalTrackUsers` (
  `ID` int(11) unsigned NOT NULL default '0',
  `When` bigint(20) unsigned NOT NULL default '0',
  `Status` ENUM('online','offline') NOT NULL default 'online',
  PRIMARY KEY  (`ID`)
);

TRUNCATE TABLE `[db_prefix]GlobalTrackUsers`;