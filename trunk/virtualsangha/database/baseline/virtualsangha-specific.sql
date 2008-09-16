DROP TABLE IF EXISTS `versions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `versions` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Number` int(10) NOT NULL default '0',
  `When_Applied` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

LOCK TABLES `versions` WRITE;
/*!40000 ALTER TABLE `versions` DISABLE KEYS */;
INSERT INTO `versions` (`number`) VALUES ('0');
/*!40000 ALTER TABLE `versions` ENABLE KEYS */;
UNLOCK TABLES;