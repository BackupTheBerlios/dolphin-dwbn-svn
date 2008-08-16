-- MySQL dump 10.11
--
-- Host: localhost    Database: test2
-- ------------------------------------------------------
-- Server version	5.0.51a

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `AdminBanList`
--

DROP TABLE IF EXISTS `AdminBanList`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `AdminBanList` (
  `ProfID` int(11) NOT NULL default '0',
  `Time` int(20) NOT NULL default '0',
  `DateTime` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `AdminBanList`
--

LOCK TABLES `AdminBanList` WRITE;
/*!40000 ALTER TABLE `AdminBanList` DISABLE KEYS */;
/*!40000 ALTER TABLE `AdminBanList` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AdminLinks`
--

DROP TABLE IF EXISTS `AdminLinks`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `AdminLinks` (
  `Title` varchar(30) NOT NULL default '',
  `Url` varchar(150) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `AdminLinks`
--

LOCK TABLES `AdminLinks` WRITE;
/*!40000 ALTER TABLE `AdminLinks` DISABLE KEYS */;
INSERT INTO `AdminLinks` VALUES ('Website Homepage','{site}'),('Search Profiles','profiles.php'),('Dolphin Documentation','http://www.boonex.com/trac/dolphin/wiki/DolphinDocs'),('Dolphin Support','http://boonex.com/unity/forums/'),('Dolphin Development','http://www.boonex.com/trac/dolphin/'),('Dolphin Extras','http://www.boonex.com/unity/extensions/all'),('BoonEx','http://www.boonex.com/'),('BoonEx Blog','http://www.boonex.com/unity/'),('BoonEx Dolphin','http://www.boonex.com/products/dolphin/'),('BoonEx Ray','http://www.boonex.com/products/ray/'),('BoonEx Orca','http://www.boonex.com/products/orca/'),('BoonEx Shark','http://www.boonex.com/products/shark/'),('BoonEx Affiliate','http://www.boonex.com/affiliate/');
/*!40000 ALTER TABLE `AdminLinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AdminMenu`
--

DROP TABLE IF EXISTS `AdminMenu`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `AdminMenu` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Title` varchar(50) NOT NULL default '',
  `Url` varchar(255) NOT NULL default '',
  `Desc` varchar(255) NOT NULL default '',
  `Check` varchar(255) NOT NULL default '',
  `Order` float NOT NULL default '0',
  `Categ` int(10) unsigned NOT NULL default '0',
  `Icon` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `AdminMenu`
--

LOCK TABLES `AdminMenu` WRITE;
/*!40000 ALTER TABLE `AdminMenu` DISABLE KEYS */;
INSERT INTO `AdminMenu` VALUES (1,'Members','profiles.php','For members profiles management\n','',0,1,'members.gif'),(2,'Affiliates','../aff/partners.php','This is for setting up your affiliate programs and your affiliate program members','',1,1,'affilates.gif'),(3,'Moderators','moderators.php','For managing your moderators who can help you with site administration','',2,1,'moderators.gif'),(4,'Events','sdating_admin.php','Provides you with the ability to manage Events created by members and by the administrator, too. You have the capacity to edit, delete or view event participants and matches','',6,2,'events.gif'),(5,'Groups','groups.php','Here you are able to manage groups and its categories','',8,2,'groups.gif'),(6,'Feedback','../story.php','This is for feedback administration, and you can edit, delete or activate site member feedback','',12,2,'feedback.gif'),(8,'Links Page','links.php','Here you can manage links on your links page','',4,3,'links_page.gif'),(9,'Admin Articles','articles.php','This is the place for controlling your site articles: edit, delete or add them','',5,3,'articles.gif'),(10,'Site News','news.php','This is for managing the news area on your web site - add, edit, activate or delete the old items','',6,3,'site_news.gif'),(11,'Random Quotes','quotes.php','You can manage quotes, which appear on the index page, from this section','',7,3,'random_quotes.gif'),(12,'Mass Mailer','notifies.php','Using this function you are able to send a newsletter to your site members','',0,3,'mass_mailer.gif'),(13,'Money Calculator','finance.php','Provides you with site income information to help you in administration','',1,3,'money_calculator.gif'),(14,'Database Backup','db.php','Make a backup of your site database with this utility','',2,3,'database_backup.gif'),(15,'Ray Suite','javascript:openRayWidget(\'global\', \'admin\', \'{adminLogin}\', \'{adminPass}\');','Ray Community Widget Suite administration panel is available here','return ( \'on\' == getParam( \'enable_ray\' ) );',0,4,'boonex_ray_widgets.gif'),(16,'Orca Forum','../orca/','Administration Panel for Orca - Interactive Forum Script','',1,4,'boonex_orca_forum.gif'),(17,'Polls','post_mod_ppolls.php','Members can create their own polls, and you can moderate them right here','',10,2,'polls.gif'),(19,'Banners','banners.php','Provides you with the ability to manage banners on your web site','',8,3,'banners.gif'),(20,'Photos','browseMedia.php?type=photo','For management of pictures uploaded / shared by site members','',0,2,'photos.gif'),(22,'Blogs','post_mod_blog.php','Site administrators can check the content written in the users\' blog to avoid unwanted or prohibited expressions','',4,2,'blogs.gif'),(23,'Profiles','../aff/profiles.php','','',0,6,''),(24,'Money Calculator','../aff/finance.php','','',1,6,'money_calculator.gif'),(25,'My Link','../aff/help.php','','',2,6,'links_page.gif'),(26,'Admin Password','global_settings.php?cat=ap','Change a password for access to administration panel here','',0,5,'admin_password.gif'),(27,'Email Templates','global_settings.php?cat=4','For setting up email texts which are sent from your website to members automatically','',3,5,'email_templates.gif'),(28,'Membership Levels','memb_levels.php','For setting up different membership levels, different actions for each membership level and action limits','',5,5,'membership_levels.gif'),(31,'CSS Styles Editor','css_file.php','For CSS files management: to make changes in your current template','',6,5,'css_styles_editor.gif'),(34,'Payments Settings','payment_providers.php','For setting up Payment Providers you want to use','',8,5,'payment_settings.gif'),(35,'Fields Builder','fields.php','For member profile fields management','',0,7,'photo_page_builder.gif'),(39,'Blogs Settings','global_settings.php?cat=22','For member blogs settings management','',9,5,'blogs_settings.gif'),(40,'News Settings','global_settings.php?cat=10','For setting up News parameters','',10,5,'news_settings.gif'),(41,'Polls Settings','global_settings.php?cat=20','For enabling/disabling polls, setting up number of polls a site member can create','',13,5,'polls.gif'),(42,'Groups Settings','global_settings.php?cat=24','Group feature management: notification emails, the thumbs size, etc.','',11,5,'groups_settings.gif'),(43,'Tags Settings','global_settings.php?cat=25','For tags settings, which will work for search and browse options','',12,5,'tags_settings.gif'),(66,'Advanced Settings','global_settings.php?cat=1&','More enhanced settings for your site features','',2,5,'adv_settings.gif'),(50,'Database Pruning','global_settings.php?cat=11','For Database management: clearing of old, unnecessary information','',15,5,'database_prunning.gif'),(52,'Basic Settings','basic_settings.php','For managing site system settings','',1,5,'basic_settings.gif'),(55,'Meta Tags','global_settings.php?cat=19','Setting up Meta Tags to facilitate search engine indexing for your website','',16,5,'meta_tags.gif'),(59,'Moderation Settings','global_settings.php?cat=6','To enable/disable pre-moderation of members profiles, members photos, etc.','',14,5,'members.gif'),(60,'Languages Settings','lang_file.php','For languages management your website is using and making changes in your website content','',4,5,'languages_settings.gif'),(62,'Pages Builder','pageBuilder.php','Compose blocks for the site pages here','',2,7,'homepage_builder.gif'),(63,'Navigation Menu Builder','menu_compose.php','For top menu items management','',1,7,'navigation_menu_builder.gif'),(65,'Classifieds','manage_classifieds.php','Administrator can manage classifieds categories, subcategories, etc.','',11,2,'classifieds.gif'),(67,'Videos','browseMedia.php?type=video','For management of video files which have been uploaded / shared by site members','',1,2,'videos.gif'),(68,'Music','browseMedia.php?type=music','For management of music files which have been uploaded / shared by site members','',2,2,'music.gif'),(74,'Admin Polls','polls.php','For site poll posting and management','',3,3,'admin_polls.gif'),(76,'Profile Photos','post_mod_photos.php?media=photo&status=passive','For pictures uploaded by a member for pre-moderation. This can be helpful to protect your site from nude or other unsuitable pics','',3,2,'photos.gif'),(77,'Profile Music','post_mod_audio.php','For management of music files which have been uploaded by members to their profiles.','',7,2,'music.gif'),(78,'Profile Videos','javascript:window.open(\'../ray/modules/video/app/admin.swf?nick={adminLogin}&password={adminPass}&url=../../../XML.php\',\'RayVideoAdmin\',\'width=700,height=330,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=0\');','For management of video files which have been uploaded by members to their profiles.','',5,2,'videos.gif'),(79,'Profile Backgrounds','post_mod_profiles.php','For post-moderation of pictures which have been uploaded by members for their profile background.','',9,2,'backgrounds.gif'),(80,'Modules','modules.php','Manage and configure integration modules for 3d party scripts','',9,3,'modules.gif'),(81,'Permalinks','global_settings.php?cat=26','Friendly permalinks activation','',17,5,'permalinks.gif'),(82,'Predefined Values','preValues.php','','',7,5,'preValues.gif');
/*!40000 ALTER TABLE `AdminMenu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AdminMenuCateg`
--

DROP TABLE IF EXISTS `AdminMenuCateg`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `AdminMenuCateg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Title` varchar(50) NOT NULL default '',
  `Order` int(11) NOT NULL default '0',
  `Icon` varchar(50) NOT NULL default '',
  `Icon_thumb` varchar(50) NOT NULL default '',
  `User` enum('admin','aff','moderator') NOT NULL default 'admin',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `AdminMenuCateg`
--

LOCK TABLES `AdminMenuCateg` WRITE;
/*!40000 ALTER TABLE `AdminMenuCateg` DISABLE KEYS */;
INSERT INTO `AdminMenuCateg` VALUES (1,'Users',0,'guy.png','guy_t.png','admin'),(2,'Content',1,'attach.png','attach_t.png','admin'),(3,'Tools',2,'tools.png','tools_t.png','admin'),(4,'Plugins',3,'plugin.png','plugin_t.png','admin'),(5,'Settings',5,'setup.png','setup_t.png','admin'),(6,'Affiliate',6,'guy.png','guy_t.png','aff'),(7,'Builders',4,'cubes.png','cubes_t.png','admin');
/*!40000 ALTER TABLE `AdminMenuCateg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Admins`
--

DROP TABLE IF EXISTS `Admins`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Admins` (
  `Name` varchar(10) NOT NULL default '',
  `Password` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Admins`
--

LOCK TABLES `Admins` WRITE;
/*!40000 ALTER TABLE `Admins` DISABLE KEYS */;
INSERT INTO `Admins` VALUES ('adminusern','e3274be5c857fb42ab72d786e281b4b8');
/*!40000 ALTER TABLE `Admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Articles`
--

DROP TABLE IF EXISTS `Articles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Articles` (
  `ArticlesID` bigint(11) NOT NULL auto_increment,
  `CategoryID` int(11) default NULL,
  `Date` date NOT NULL default '0000-00-00',
  `Title` varchar(100) NOT NULL default '',
  `ArticleUri` varchar(100) NOT NULL default '',
  `Text` mediumtext,
  `ArticleFlag` enum('Text','HTML') NOT NULL default 'Text',
  `ownerID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ArticlesID`),
  UNIQUE KEY `ArticleUri` (`ArticleUri`),
  KEY `CategoryID` (`CategoryID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Articles`
--

LOCK TABLES `Articles` WRITE;
/*!40000 ALTER TABLE `Articles` DISABLE KEYS */;
INSERT INTO `Articles` VALUES (1,2,'2007-05-08','Introduction','Introduction','<div style=\"font-size:12pt; font-family:Arial; line-height: 115%;\"><p>We have turned our 8-years of continuous efforts into a few but highly acknowledged brands in the community software industry. Our <a href=\"http://www.boonex.com/products/dolphin\">Dolphin</a> and <a href=\"http://www.boonex.com/products/ray/\">Ray</a> Community builders have been created by the talented professional developers dedicated to the <a href=\"http://www.boonex.com/mission/\">mission</a> determined by BoonEx to unite people and are now recognized as the leaders in its segment.</p><p>&nbsp;</p><p>While the previous versions have already been a tremendous success, BoonEx introduced an exceptional upgrade of Dolphin, Ray, and Orca – all mature and highly competitive software with outstanding security, power, speed, and customization parameters.</p></div>','HTML',0),(2,2,'2007-05-08','Part one, Dolphin','Part-one-Dolphin','<div style=\"font-size:12pt; font-family:Arial; line-height: 115%;\"><p>Take a Ride with BoonEx! To the world of Communication and Socialization!</p><p>&nbsp;</p><p>If you ever had a dream to set up your own social network with any crazy idea your imagination allows you and with minimum start up capital, BoonEx is inviting you to explore our website development tools and make your dream come true.</p><p>&nbsp;</p><p>Welcome aboard!</p><p>&nbsp;</p><p>A <a href=\"http://www.boonex.com/products/dolphin\">Dolphin</a> Smart Community Builder will be the first stop in your journey with numerous customization possibilities, access to the source code, and superior security parameters.</p><p>&nbsp;</p><p>Dolphin is a universal, full-featured community script that allows you to build a unique on-line community web site. It is amazing! And it is Free for you!</p><p>&nbsp;</p><p>Dolphin is simple to manage and easy to customize. And you can go even further with our BoonEx/Unity support – utilizing open source, the support community will empower you to implement your wildest ideas.</p><p>&nbsp;</p><p>YouTube, MySpace, Odeo, Flickr, Match and Facebook – all in one, customizable and under your full control. You’re limited only by your imagination – not by the software.</p><p>&nbsp;</p><p>Dolphin is a mature software with a superior upgrade history and Dolphin 6.1 takes it even further.</p><p>&nbsp;</p><p>Our latest version, Dolphin 6.1, offers features that you are very unlikely to find in any comparable software in the industry. With fluid-width layout; clustered server support; new fields and pages builders; couples profiles; optimized database; advanced AJAX comments system; extended configuration options; new homepage promo; dozens of new features and improvements, Dolphin 6.1 is ready for any challenge.<p><p><ul>What’s New in Dolphin 6.1:<li>Now you’re not limited to just two site width options. Instead, you can set any width for your site or individual page types. Now, it can be anything from a mere 760px up to a stretchy 100% width! Plus, you do it with Dolphin style!<li>On top of that – you can now create your own pages! Create your own pages and launch them within your Dolphin-based community adding all the blocks you see in standard pages builders. Now you can change your site layout with absolutely unmatched flexibility. Instead of the default 2 columns you can create 1-4 columns and adjust their width. This gives you HUGE customization opportunities!<li>Now it also has the coolest comments system available in any community script out there, with multi-step nesting, voting, delayed editing and deleting. Don’t underestimate comments – this is where 50% of the community life happens, and now they’re better than ever.<li>The login system has been improved. Now we have a new pop-up login window here and there and that fancy ‘remember me’ feature.<li>Massive code optimization. Large amounts of code have been re-written to a classes-based structure.</ul></p><p>&nbsp;</p><p>Finally, Dolphin is always in forward motion in terms of technology and research, and it outperforms all free and commercial dating, social networking and community scripts in functionality, popularity and public support. Go for it and Enjoy!!!</p><p>&nbsp;</p></div>','HTML',0),(3,2,'2007-05-08','Part two, Ray','Part-two-Ray','<div style=\"font-size:12pt; font-family:Arial; line-height: 115%;\"><p>We have been composing here for a while and came up with some fancy widgets that will liven up your community. So, the next station in your tour will be with <a href=\"http://www.boonex.com/products/ray/\">Ray Community Widgets Suite</a>.</p><p>&nbsp;</p><p>Add interactive multimedia tools to your site, and convert your visitors into addicted members. You will have no limits, no monthly fees, no ads, multiple templates, your own server, full customization and full control.</p><p>&nbsp;</p><p>Ray will energize your community site with cool Flash audio/video communication, multimedia and media sharing tools.</p><p>&nbsp;</p><p><ul>Explore these features offered by Ray:<li>Audio/Video Flash Chat widget is the web’s most advanced, sophisticated and easy to use Flash chat platform with real-time audio and video conferencing. The latest version, Ray Chat 3.5, features a tabbed room experience which enables chatting in multiple rooms simultaneously with tabs-popping notifications and 1-to-1 private chat built into the main interface.<li>With Audio/Video Flash Instant Messenger you get instant A/V activation, a draggable interface, stylish animated emoticons, text formatting, presence status indicators and now also the option of sending messages to a specific user and/or even in the new “whisper” mode.<li>Audio/Video Flash Recorder files can be uploaded directly to member profiles or can be stored in a website database. Simple and elegant!<li>Music Player converts any music format and includes a user part player, playlist editor and administrator panel. Your members can use the Music Player widget to become closer via music sharing.<li>Web Presence is the tool that turns any site into a real community where people can group and see their friends around them. Web presence supports website native avatars and profile info, tracks new messages and easily connects to Ray Messenger.<li>With Whiteboard, your users will be able to stream their drawings in live mode, so that viewers can see the process… in real-time! Moreover, they can now collaborate and make real-time drawing presentations mastered by two or more authors simultaneously.<li>Video Player provides your members with the ability to upload any kind of video to your site – in any format easily…and it’s even faster than YouTube, Myspacetv, Grouper and others!<li>Ray Desktop redefines the way online communities work. Now, you can enable your site members to stay online, receive messages, talk privately and chat with other members anytime – on your site or not. With audio and video!</ul></p><p>&nbsp;</p><p>Finally, all Ray widgets undergo constant development and upgrade. Stay tuned with BoonEx and prepare yourselves for the upcoming challenges from Ray!</p><p>&nbsp;</p></div>','HTML',0),(4,2,'2007-05-08','Part three, Orca','Part-three-Orca','<div style=\"font-size:12pt; font-family:Arial; line-height: 115%;\"><p>Now that you have explored Dolphin and Ray, it’s time to introduce Orca, our mighty forum script.</p><p>&nbsp;</p><p><a href=\"http://www.boonex.com/products/orca/\">Orca Interactive Forum Script</a> introduces a new approach to building online discussion boards with the main focus given to self-moderation which brings unique user experiences, true freedom, and considerable server load savings.</p><p>&nbsp;</p><p>Here is more for you. Orca works as a standalone forum or can be plugged-in to any website with a member database. Orca is remarkably easy to integrate with your existing user database. You just add it to your site, and it accepts existing members with their photos and passwords.</p><p>&nbsp;</p><p>Orca is search-engine-friendly, open-source and is completely free… features which should uncover its potential and make it the #1 forum software on the net.</p><p>&nbsp;</p><p>Last, while being a 100% AJAX-based interface, ORCA has managed to overcome AJAX-related flaws in terms of programming and usability.</p><p>&nbsp;</p><p>Enjoy the spirit of Web 2.0 with 100% AJAX forum software for a self-moderated community!</p></div>','HTML',0),(5,3,'2007-05-08','How to create a modern Community website?','How-to-create-a-modern-Community-website-','<p class=\"MsoNormal\"><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;\">There are a host of community software development companies who offer scripts for community websites these days. Some of them are free and others are paid and cost from $200 to $800. When you decide to use a purchased script for your site you think that you will be able to pay once and everything will be OK. <span style=\"color: black\">Practice shows that it&rsquo;s not quite so simple. You pay for a script and spe</span></span><span style=\"position: relative; bottom: 9px\"><a href=\"notifies.php\" class=\"menu\"></a></span><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;\"><span style=\"color: black\">nd an additional pile of money to modify it as you want for your community, and in only this sense is the software customizable.</span></span></p><p class=\"MsoNormal\">&nbsp;</p>  <p class=\"MsoNormal\"><a href=\"http://www.boonex.com/\"><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;\">BoonEx Community Software Experts</span></a> <span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;\"> strongly believes that community software should be offered for free. We give you free software which is fully customizable, and there are a lot of modifications, plug ins and templates at <a href=\"boonex.com/unity/\">Unity</a> for you to use. They are reliably tested &ndash; we release each BoonEx product as a beta version to give our customers an opportunity to test it and make reports and suggestions for several weeks before the full version is released. Thus, our software is a result of the collaborative work of BoonEx, software developers and webmasters.</span></p>  <p class=\"MsoNormal\"><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;\">To start your own community website just follow this link and download <a href=\"http://www.boonex.com/products/dolphin/download/\">Dolphin Smart Community Builder</a> . </span></p>','HTML',0),(6,3,'2007-05-08','How to create a Unique Community website?','How-to-create-a-Unique-Community-website-','<p><span style=\"font-size: 12pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;\">After you&rsquo;ve obtained Dolphin your next step will be installation.<span>&nbsp; </span>It&rsquo;s easy to install Dolphin if your hosting provides all the <a href=\"http://www.boonex.com/products/dolphin/download/\"><u>Dolphin technical requirements</u></a>. If you are not <span style=\"color: black\">sure, you can address our<a href=\"http://www.boonex.com/unity/\"> <u>Unity &ndash; - the Community of Communities</u></a>. </span></span></p><p><span style=\"font-size: 12pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;\"><span style=\"color: black\">There are many professional experts who offer their products and services for your website.</span></span></p>','HTML',0),(7,3,'2007-05-08','How to earn money?','How-to-earn-money-','<p class=\"MsoNormal\"><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: black\">Besides the opportunity to earn money by charging a payment to your site members, </span><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial CYR&quot;,&quot;sans-serif&quot;; color: black\">BoonEx </span><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: black\">gives you the opportunity for additional earnings through the <a href=\"http://www.boonex.com/unity/\">Unity</a>.</span></p><p class=\"MsoNormal\">&nbsp;</p>  <p class=\"MsoNormal\"><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: black\">We have launched the Unity system as the main platform for those who need to get support for their website and for those who can provide support services. If you are a creative person and have even a few programming skills &ndash; you are welcome to join our Unity community. We have more than 25,000 customers all over the internet who use Dolphin or other BoonEx products. All of them, at least once, have needed support or other help while building their Community websites. Unity provides a great opportunity to improve your programmer&rsquo;s skills and to earn money. </span></p>  <p class=\"MsoNormal\"><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: black\">Don&rsquo;t miss this chance to increase your income!</span></p>  ','HTML',0),(8,3,'2007-05-08','How to find the right sort of people?','How-to-find-the-right-sort-of-people-','<p class=\"MsoNormal\"><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: black\">You are tired of searching for the person who will understand you completely and who will like you just as you are. Or, maybe you just like making internet acquaintances, and you like to chat with people from all over the world, send pics and just have fun. <a href=\"http://www.4ppl.com/\"><u>4PPL</u></a>  is exactly for you!</span></p><p class=\"MsoNormal\">&nbsp;</p>  <p class=\"MsoNormal\"><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: black\">You are welcome to join the first absolutely free dating website without annoying ads. <a href=\"http://www.4ppl.com/\">4PPL</a>  presents you with a simple design, without any hard to load features that steal additional traffic and your money. It&rsquo;s easy to use, fast and without any restrictions!</span></p>  <p class=\"MsoNormal\"><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: black\"><a href=\"http://www.4ppl.com/\">4PPL</a>  is open for all nations, cultures and races! <a href=\"http://www.4ppl.com/\">4PPL</a>  is for people.</span></p>  ','HTML',0),(9,1,'2007-05-08','How to become famous?','How-to-become-famous-','<p class=\"MsoNormal\"><span style=\"font-size: 12pt; line-height: 115%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: black\">You are a special person. You have a unique talent for writing poems, stories and songs. Join <a href=\"http://www.lovelandia.com/\">LoveLandia</a>  to gain acknowledgement for your talent! This is a place for authors from all over the world who post their works and share them with the great <a href=\"http://www.lovelandia.com/\">LoveLandia</a>  Community!</span></p>  ','HTML',0);
/*!40000 ALTER TABLE `Articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ArticlesCategory`
--

DROP TABLE IF EXISTS `ArticlesCategory`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ArticlesCategory` (
  `CategoryID` int(11) NOT NULL auto_increment,
  `CategoryName` varchar(255) NOT NULL default '',
  `CategoryUri` varchar(255) NOT NULL default '',
  `CategoryDescription` varchar(255) default NULL,
  PRIMARY KEY  (`CategoryID`),
  UNIQUE KEY `CategoryUri` (`CategoryUri`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ArticlesCategory`
--

LOCK TABLES `ArticlesCategory` WRITE;
/*!40000 ALTER TABLE `ArticlesCategory` DISABLE KEYS */;
INSERT INTO `ArticlesCategory` VALUES (1,'Default','Default','Default category for article'),(2,'BoonEx Products','BoonEx-Products','Learn more about the latest BoonEx releases'),(3,'Some useful info','Some-useful-info','Some useful and interesting information for you');
/*!40000 ALTER TABLE `ArticlesCategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Banners`
--

DROP TABLE IF EXISTS `Banners`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Banners` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `Title` varchar(32) NOT NULL default '',
  `Url` varchar(255) NOT NULL default '',
  `Text` mediumtext NOT NULL,
  `Active` tinyint(4) NOT NULL default '0',
  `Created` date NOT NULL default '0000-00-00',
  `campaign_start` date NOT NULL default '2005-01-01',
  `campaign_end` date NOT NULL default '2007-01-01',
  `Position` int(4) NOT NULL default '4',
  `lhshift` int(5) NOT NULL default '-200',
  `lvshift` int(5) NOT NULL default '-750',
  `rhshift` int(5) NOT NULL default '100',
  `rvshift` int(5) NOT NULL default '-750',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Banners`
--

LOCK TABLES `Banners` WRITE;
/*!40000 ALTER TABLE `Banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `Banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BannersClicks`
--

DROP TABLE IF EXISTS `BannersClicks`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `BannersClicks` (
  `ID` int(10) unsigned NOT NULL default '0',
  `Date` date NOT NULL default '0000-00-00',
  `IP` varchar(16) NOT NULL default '',
  UNIQUE KEY `ID_2` (`ID`,`Date`,`IP`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `BannersClicks`
--

LOCK TABLES `BannersClicks` WRITE;
/*!40000 ALTER TABLE `BannersClicks` DISABLE KEYS */;
/*!40000 ALTER TABLE `BannersClicks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BannersShows`
--

DROP TABLE IF EXISTS `BannersShows`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `BannersShows` (
  `ID` int(10) unsigned NOT NULL default '0',
  `Date` date NOT NULL default '0000-00-00',
  `IP` varchar(16) NOT NULL default '',
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `BannersShows`
--

LOCK TABLES `BannersShows` WRITE;
/*!40000 ALTER TABLE `BannersShows` DISABLE KEYS */;
/*!40000 ALTER TABLE `BannersShows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BlockList`
--

DROP TABLE IF EXISTS `BlockList`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `BlockList` (
  `ID` bigint(8) NOT NULL default '0',
  `Profile` bigint(8) NOT NULL default '0',
  UNIQUE KEY `BlockPair` (`ID`,`Profile`),
  KEY `ID` (`ID`),
  KEY `Profile` (`Profile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `BlockList`
--

LOCK TABLES `BlockList` WRITE;
/*!40000 ALTER TABLE `BlockList` DISABLE KEYS */;
/*!40000 ALTER TABLE `BlockList` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BlogCategories`
--

DROP TABLE IF EXISTS `BlogCategories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `BlogCategories` (
  `CategoryID` int(11) NOT NULL auto_increment,
  `OwnerID` int(11) default NULL,
  `CategoryName` varchar(150) default NULL,
  `CategoryUri` varchar(150) NOT NULL default '',
  `CategoryType` int(4) unsigned NOT NULL default '1',
  `CategoryPhoto` varchar(150) default NULL,
  `Date` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`CategoryID`),
  UNIQUE KEY `CategoryUri` (`CategoryUri`),
  KEY `OwnerID` (`OwnerID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `BlogCategories`
--

LOCK TABLES `BlogCategories` WRITE;
/*!40000 ALTER TABLE `BlogCategories` DISABLE KEYS */;
/*!40000 ALTER TABLE `BlogCategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BlogPosts`
--

DROP TABLE IF EXISTS `BlogPosts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `BlogPosts` (
  `PostID` int(11) NOT NULL auto_increment,
  `CategoryID` int(11) default NULL,
  `PostCaption` varchar(255) NOT NULL default '',
  `PostUri` varchar(255) NOT NULL default '',
  `PostText` text NOT NULL,
  `PostDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `PostReadPermission` enum('public','friends') NOT NULL default 'public',
  `PostCommentPermission` enum('public','friends') NOT NULL default 'public',
  `PostStatus` enum('approval','disapproval') NOT NULL default 'disapproval',
  `PostPhoto` varchar(50) default NULL,
  `Tags` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`PostID`),
  UNIQUE KEY `PostUri` (`PostUri`),
  KEY `CategoryID` (`CategoryID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `BlogPosts`
--

LOCK TABLES `BlogPosts` WRITE;
/*!40000 ALTER TABLE `BlogPosts` DISABLE KEYS */;
/*!40000 ALTER TABLE `BlogPosts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Blogs`
--

DROP TABLE IF EXISTS `Blogs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Blogs` (
  `ID` int(5) unsigned NOT NULL auto_increment,
  `OwnerID` int(3) unsigned NOT NULL default '0',
  `Description` varchar(255) NOT NULL default '',
  `Other` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `OwnerID` (`OwnerID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Blogs`
--

LOCK TABLES `Blogs` WRITE;
/*!40000 ALTER TABLE `Blogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `Blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BoughtContacts`
--

DROP TABLE IF EXISTS `BoughtContacts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `BoughtContacts` (
  `IDBuyer` bigint(20) unsigned NOT NULL default '0',
  `IDContact` bigint(20) unsigned NOT NULL default '0',
  `TransactionID` bigint(20) unsigned default NULL,
  `HideFromBuyer` tinyint(1) NOT NULL default '0',
  `HideFromContact` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`IDBuyer`,`IDContact`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `BoughtContacts`
--

LOCK TABLES `BoughtContacts` WRITE;
/*!40000 ALTER TABLE `BoughtContacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `BoughtContacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Classifieds`
--

DROP TABLE IF EXISTS `Classifieds`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Classifieds` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `Name` varchar(64) NOT NULL default '',
  `CEntryUri` varchar(64) NOT NULL default '',
  `Description` varchar(128) default NULL,
  `CustomFieldName1` varchar(50) default NULL,
  `CustomFieldName2` varchar(50) default NULL,
  `CustomAction1` varchar(10) default NULL,
  `CustomAction2` varchar(10) default NULL,
  `Unit` varchar(8) NOT NULL default '$',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `CEntryUri` (`CEntryUri`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Classifieds`
--

LOCK TABLES `Classifieds` WRITE;
/*!40000 ALTER TABLE `Classifieds` DISABLE KEYS */;
INSERT INTO `Classifieds` VALUES (1,'Jobs','Jobs','There is Jobs description','salary','salary','>','<','EUR'),(2,'Music Exchange','Music-Exchange','music exchange desc','price','price','>','<','$'),(4,'Housing & Rentals','Housing-Rentals','Housing & Rentals desc','rental',NULL,'>',NULL,'$'),(5,'Services','Services','Services desc','price',NULL,'=',NULL,'$'),(7,'Casting Calls','Casting-Calls','Casting Calls desc',NULL,NULL,NULL,NULL,'$'),(8,'Personals','Personals','Personals desc','payment',NULL,'=',NULL,'$'),(9,'For Sale','For-Sale','For Sale desc','price','price','>','<','$'),(10,'Cars For Sale','Cars-For-Sale','Cars For Sale desc','price','price','>','<','€');
/*!40000 ALTER TABLE `Classifieds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ClassifiedsAdvertisements`
--

DROP TABLE IF EXISTS `ClassifiedsAdvertisements`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ClassifiedsAdvertisements` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `IDProfile` int(11) unsigned NOT NULL default '0',
  `IDClassifiedsSubs` int(11) unsigned NOT NULL default '0',
  `DateTime` datetime NOT NULL default '0000-00-00 00:00:00',
  `Subject` varchar(50) NOT NULL default '',
  `EntryUri` varchar(50) NOT NULL default '',
  `Message` text NOT NULL,
  `Status` enum('new','active','inactive') NOT NULL default 'new',
  `CustomFieldValue1` varchar(50) default NULL,
  `CustomFieldValue2` varchar(50) default NULL,
  `LifeTime` int(3) NOT NULL default '30',
  `Media` varchar(50) default NULL,
  `Tags` text NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `EntryUri` (`EntryUri`),
  KEY `IDProfile` (`IDProfile`),
  KEY `IDClassifiedsSubs` (`IDClassifiedsSubs`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ClassifiedsAdvertisements`
--

LOCK TABLES `ClassifiedsAdvertisements` WRITE;
/*!40000 ALTER TABLE `ClassifiedsAdvertisements` DISABLE KEYS */;
/*!40000 ALTER TABLE `ClassifiedsAdvertisements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ClassifiedsAdvertisementsMedia`
--

DROP TABLE IF EXISTS `ClassifiedsAdvertisementsMedia`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ClassifiedsAdvertisementsMedia` (
  `MediaID` int(11) unsigned NOT NULL auto_increment,
  `MediaProfileID` int(11) unsigned NOT NULL default '0',
  `MediaType` enum('photo','other') NOT NULL default 'photo',
  `MediaFile` varchar(50) NOT NULL default '',
  `MediaDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`MediaID`),
  KEY `med_prof_id` (`MediaProfileID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ClassifiedsAdvertisementsMedia`
--

LOCK TABLES `ClassifiedsAdvertisementsMedia` WRITE;
/*!40000 ALTER TABLE `ClassifiedsAdvertisementsMedia` DISABLE KEYS */;
/*!40000 ALTER TABLE `ClassifiedsAdvertisementsMedia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ClassifiedsSubs`
--

DROP TABLE IF EXISTS `ClassifiedsSubs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ClassifiedsSubs` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `IDClassified` int(11) unsigned default NULL,
  `NameSub` varchar(128) NOT NULL default '',
  `SEntryUri` varchar(128) NOT NULL default '',
  `Description` varchar(150) default 'No description',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `SEntryUri` (`SEntryUri`),
  KEY `IDClassified` (`IDClassified`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ClassifiedsSubs`
--

LOCK TABLES `ClassifiedsSubs` WRITE;
/*!40000 ALTER TABLE `ClassifiedsSubs` DISABLE KEYS */;
INSERT INTO `ClassifiedsSubs` VALUES (4,2,'positions and openings','positions-and-openings','positions and openings desc'),(5,2,'instruments for sale','instruments-for-sale','instruments for sale desc'),(6,2,'instruments wanted','instruments-wanted','instruments wanted desc'),(7,3,'activities','activities','activities desc'),(8,3,'artists','artists','artists desc'),(9,3,'childcare','childcare','childcare desc'),(10,4,'apartments / housing','apartments-housing','apartments / housing description'),(11,4,'real estate for sale','real-estate-for-sale','real estate for sale description'),(12,4,'roommates','roommates','roommates description'),(38,1,'accounting / finance','accounting-finance','accounting / finance desc'),(36,5,'automotive','automotive','automotive desc'),(43,1,'education / nonprofit sec','education-nonprofit-sec','education / nonprofit sector desc'),(47,1,'government / legal','government-legal','government/legal desc'),(84,1,'programming / web design','programming-web-design','programming / web design desc'),(54,1,'other','other','other desc'),(55,4,'temporary vacation rental','temporary-vacation-rental','temporary vacation rentals desc'),(56,4,'office / commercial','office-commercial','office / commercial  desc'),(58,5,'financial','financial','financial'),(60,5,'labor / move','labor-move','labor/move desc'),(61,5,'legal','legal','legal desc'),(62,5,'educational','educational','educational desc'),(64,7,'acting','acting','acting desc'),(65,7,'dance','dance','dance desc'),(83,7,'musicians','musicians','musicians desc'),(67,7,'modeling','modeling','modeling desc'),(68,7,'reality shows','reality-shows','reality shows  desc'),(69,8,'men seeking women','men-seeking-women','men seeking women desc'),(70,8,'women seeking men','women-seeking-men','women seeking men desc'),(71,8,'women seeking women','women-seeking-women','women seeking women desc'),(72,8,'men seeking men','men-seeking-men','men seeking men desc'),(73,8,'missed connections','missed-connections','missed connections desc'),(74,9,'barter','barter','barter desc'),(77,9,'clothing','clothing','clothing desc'),(78,9,'collectibles','collectibles','collectibles desc'),(79,9,'miscellaneous','miscellaneous','miscellaneous desc'),(80,10,'autos / trucks','autos-trucks','autos / trucks desc'),(81,10,'motorcycles','motorcycles','motorcycles desc'),(82,10,'auto parts','auto-parts','auto parts desc');
/*!40000 ALTER TABLE `ClassifiedsSubs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CmtsBlogPosts`
--

DROP TABLE IF EXISTS `CmtsBlogPosts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `CmtsBlogPosts` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(11) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text NOT NULL,
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `CmtsBlogPosts`
--

LOCK TABLES `CmtsBlogPosts` WRITE;
/*!40000 ALTER TABLE `CmtsBlogPosts` DISABLE KEYS */;
/*!40000 ALTER TABLE `CmtsBlogPosts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CmtsClassifieds`
--

DROP TABLE IF EXISTS `CmtsClassifieds`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `CmtsClassifieds` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(11) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text NOT NULL,
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `CmtsClassifieds`
--

LOCK TABLES `CmtsClassifieds` WRITE;
/*!40000 ALTER TABLE `CmtsClassifieds` DISABLE KEYS */;
/*!40000 ALTER TABLE `CmtsClassifieds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CmtsProfile`
--

DROP TABLE IF EXISTS `CmtsProfile`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `CmtsProfile` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(11) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text NOT NULL,
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `CmtsProfile`
--

LOCK TABLES `CmtsProfile` WRITE;
/*!40000 ALTER TABLE `CmtsProfile` DISABLE KEYS */;
/*!40000 ALTER TABLE `CmtsProfile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CmtsSharedMusic`
--

DROP TABLE IF EXISTS `CmtsSharedMusic`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `CmtsSharedMusic` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(12) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text NOT NULL,
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `CmtsSharedMusic`
--

LOCK TABLES `CmtsSharedMusic` WRITE;
/*!40000 ALTER TABLE `CmtsSharedMusic` DISABLE KEYS */;
/*!40000 ALTER TABLE `CmtsSharedMusic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CmtsSharedPhoto`
--

DROP TABLE IF EXISTS `CmtsSharedPhoto`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `CmtsSharedPhoto` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(12) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text NOT NULL,
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `CmtsSharedPhoto`
--

LOCK TABLES `CmtsSharedPhoto` WRITE;
/*!40000 ALTER TABLE `CmtsSharedPhoto` DISABLE KEYS */;
/*!40000 ALTER TABLE `CmtsSharedPhoto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CmtsSharedVideo`
--

DROP TABLE IF EXISTS `CmtsSharedVideo`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `CmtsSharedVideo` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(12) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text NOT NULL,
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `CmtsSharedVideo`
--

LOCK TABLES `CmtsSharedVideo` WRITE;
/*!40000 ALTER TABLE `CmtsSharedVideo` DISABLE KEYS */;
/*!40000 ALTER TABLE `CmtsSharedVideo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CmtsTrack`
--

DROP TABLE IF EXISTS `CmtsTrack`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `CmtsTrack` (
  `cmt_system_id` int(11) NOT NULL default '0',
  `cmt_id` int(11) NOT NULL default '0',
  `cmt_rate` tinyint(4) NOT NULL default '0',
  `cmt_rate_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_rate_author_nip` int(11) unsigned NOT NULL default '0',
  `cmt_rate_ts` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_system_id`,`cmt_id`,`cmt_rate_author_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `CmtsTrack`
--

LOCK TABLES `CmtsTrack` WRITE;
/*!40000 ALTER TABLE `CmtsTrack` DISABLE KEYS */;
/*!40000 ALTER TABLE `CmtsTrack` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ColorBase`
--

DROP TABLE IF EXISTS `ColorBase`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ColorBase` (
  `ColorName` varchar(20) NOT NULL default '',
  `ColorCode` varchar(10) NOT NULL default '',
  UNIQUE KEY `ColorName` (`ColorName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ColorBase`
--

LOCK TABLES `ColorBase` WRITE;
/*!40000 ALTER TABLE `ColorBase` DISABLE KEYS */;
INSERT INTO `ColorBase` VALUES ('AliceBlue','#F0F8FF'),('AntiqueWhite','#FAEBD7'),('Aqua','#00FFFF'),('Aquamarine','#7FFFD4'),('Azure','#F0FFFF'),('Beige','#F5F5DC'),('Bisque','#FFE4C4'),('Black','#000000'),('BlanchedAlmond','#FFEBCD'),('Blue','#0000FF'),('BlueViolet','#8A2BE2'),('Brown','#A52A2A'),('BurlyWood','#DEB887'),('CadetBlue','#5F9EA0'),('Chartreuse','#7FFF00'),('Chocolate','#D2691E'),('Coral','#FF7F50'),('CornflowerBlue','#6495ED'),('Cornsilk','#FFF8DC'),('Crimson','#DC143C'),('Cyan','#00FFFF'),('DarkBlue','#00008B'),('DarkCyan','#008B8B'),('DarkGoldenRod','#B8860B'),('DarkGray','#A9A9A9'),('DarkGreen','#006400'),('DarkKhaki','#BDB76B'),('DarkMagenta','#8B008B'),('DarkOliveGreen','#556B2F'),('Darkorange','#FF8C00'),('DarkOrchid','#9932CC'),('DarkRed','#8B0000'),('DarkSalmon','#E9967A'),('DarkSeaGreen','#8FBC8F'),('DarkSlateBlue','#483D8B'),('DarkSlateGray','#2F4F4F'),('DarkTurquoise','#00CED1'),('DarkViolet','#9400D3'),('DeepPink','#FF1493'),('DeepSkyBlue','#00BFFF'),('DimGray','#696969'),('DodgerBlue','#1E90FF'),('Feldspar','#D19275'),('FireBrick','#B22222'),('FloralWhite','#FFFAF0'),('ForestGreen','#228B22'),('Fuchsia','#FF00FF'),('Gainsboro','#DCDCDC'),('GhostWhite','#F8F8FF'),('Gold','#FFD700'),('GoldenRod','#DAA520'),('Gray','#808080'),('Green','#008000'),('GreenYellow','#ADFF2F'),('HoneyDew','#F0FFF0'),('HotPink','#FF69B4'),('IndianRed','#CD5C5C'),('Indigo','#4B0082'),('Ivory','#FFFFF0'),('Khaki','#F0E68C'),('Lavender','#E6E6FA'),('LavenderBlush','#FFF0F5'),('LawnGreen','#7CFC00'),('LemonChiffon','#FFFACD'),('LightBlue','#ADD8E6'),('LightCoral','#F08080'),('LightCyan','#E0FFFF'),('LightGoldenRodYellow','#FAFAD2'),('LightGrey','#D3D3D3'),('LightGreen','#90EE90'),('LightPink','#FFB6C1'),('LightSalmon','#FFA07A'),('LightSeaGreen','#20B2AA'),('LightSkyBlue','#87CEFA'),('LightSlateBlue','#8470FF'),('LightSlateGray','#778899'),('LightSteelBlue','#B0C4DE'),('LightYellow','#FFFFE0'),('Lime','#00FF00'),('LimeGreen','#32CD32'),('Linen','#FAF0E6'),('Magenta','#FF00FF'),('Maroon','#800000'),('MediumAquaMarine','#66CDAA'),('MediumBlue','#0000CD'),('MediumOrchid','#BA55D3'),('MediumPurple','#9370D8'),('MediumSeaGreen','#3CB371'),('MediumSlateBlue','#7B68EE'),('MediumSpringGreen','#00FA9A'),('MediumTurquoise','#48D1CC'),('MediumVioletRed','#C71585'),('MidnightBlue','#191970'),('MintCream','#F5FFFA'),('MistyRose','#FFE4E1'),('Moccasin','#FFE4B5'),('NavajoWhite','#FFDEAD'),('Navy','#000080'),('OldLace','#FDF5E6'),('Olive','#808000'),('OliveDrab','#6B8E23'),('Orange','#FFA500'),('OrangeRed','#FF4500'),('Orchid','#DA70D6'),('PaleGoldenRod','#EEE8AA'),('PaleGreen','#98FB98'),('PaleTurquoise','#AFEEEE'),('PaleVioletRed','#D87093'),('PapayaWhip','#FFEFD5'),('PeachPuff','#FFDAB9'),('Peru','#CD853F'),('Pink','#FFC0CB'),('Plum','#DDA0DD'),('PowderBlue','#B0E0E6'),('Purple','#800080'),('Red','#FF0000'),('RosyBrown','#BC8F8F'),('RoyalBlue','#4169E1'),('SaddleBrown','#8B4513'),('Salmon','#FA8072'),('SandyBrown','#F4A460'),('SeaGreen','#2E8B57'),('SeaShell','#FFF5EE'),('Sienna','#A0522D'),('Silver','#C0C0C0'),('SkyBlue','#87CEEB'),('SlateBlue','#6A5ACD'),('SlateGray','#708090'),('Snow','#FFFAFA'),('SpringGreen','#00FF7F'),('SteelBlue','#4682B4'),('Tan','#D2B48C'),('Teal','#008080'),('Thistle','#D8BFD8'),('Tomato','#FF6347'),('Turquoise','#40E0D0'),('Violet','#EE82EE'),('VioletRed','#D02090'),('Wheat','#F5DEB3'),('White','#FFFFFF'),('WhiteSmoke','#F5F5F5'),('Yellow','#FFFF00'),('YellowGreen','#9ACD32');
/*!40000 ALTER TABLE `ColorBase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Countries`
--

DROP TABLE IF EXISTS `Countries`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Countries` (
  `ISO2` varchar(2) NOT NULL default '',
  `ISO3` varchar(3) NOT NULL default '',
  `ISONo` smallint(3) NOT NULL default '0',
  `Country` varchar(100) NOT NULL default '',
  `Region` varchar(100) default NULL,
  `Currency` varchar(100) default NULL,
  `CurrencyCode` varchar(3) default NULL,
  PRIMARY KEY  (`ISO2`),
  KEY `CurrencyCode` (`CurrencyCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Countries`
--

LOCK TABLES `Countries` WRITE;
/*!40000 ALTER TABLE `Countries` DISABLE KEYS */;
INSERT INTO `Countries` VALUES ('AD','AND',20,'Andorra','Europe','Euro','EUR'),('AE','ARE',784,'United Arab Emirates','Middle East','UAE Dirham','AED'),('AF','AFG',4,'Afghanistan','Asia','Afghani','AFA'),('AG','ATG',28,'Antigua and Barbuda','Central America and the Caribbean','East Caribbean Dollar','XCD'),('AI','AIA',660,'Anguilla','Central America and the Caribbean','East Caribbean Dollar','XCD'),('AL','ALB',8,'Albania','Europe','Lek','ALL'),('AM','ARM',51,'Armenia','Commonwealth of Independent States','Armenian Dram','AMD'),('AN','ANT',530,'Netherlands Antilles','Central America and the Caribbean','Netherlands Antillean guilder','ANG'),('AO','AGO',24,'Angola','Africa','Kwanza','AOA'),('AQ','ATA',10,'Antarctica','Antarctic Region',NULL,NULL),('AR','ARG',32,'Argentina','South America','Argentine Peso','ARS'),('AS','ASM',16,'American Samoa','Oceania','US Dollar','USD'),('AT','AUT',40,'Austria','Europe','Euro','EUR'),('AU','AUS',36,'Australia','Oceania','Australian dollar','AUD'),('AW','ABW',533,'Aruba','Central America and the Caribbean','Aruban Guilder','AWG'),('AZ','AZE',31,'Azerbaijan','Commonwealth of Independent States','Azerbaijani Manat','AZM'),('BA','BIH',70,'Bosnia and Herzegovina','Bosnia and Herzegovina, Europe','Convertible Marka','BAM'),('BB','BRB',52,'Barbados','Central America and the Caribbean','Barbados Dollar','BBD'),('BD','BGD',50,'Bangladesh','Asia','Taka','BDT'),('BE','BEL',56,'Belgium','Europe','Euro','EUR'),('BF','BFA',854,'Burkina Faso','Africa','CFA Franc BCEAO','XOF'),('BG','BGR',100,'Bulgaria','Europe','Lev','BGL'),('BH','BHR',48,'Bahrain','Middle East','Bahraini Dinar','BHD'),('BI','BDI',108,'Burundi','Africa','Burundi Franc','BIF'),('BJ','BEN',204,'Benin','Africa','CFA Franc BCEAO','XOF'),('BM','BMU',60,'Bermuda','North America','Bermudian Dollar','BMD'),('BN','BRN',96,'Brunei Darussalam','Southeast Asia','Brunei Dollar','BND'),('BO','BOL',68,'Bolivia','South America','Boliviano','BOB'),('BR','BRA',76,'Brazil','South America','Brazilian Real','BRL'),('BS','BHS',44,'The Bahamas','Central America and the Caribbean','Bahamian Dollar','BSD'),('BT','BTN',64,'Bhutan','Asia','Ngultrum','BTN'),('BV','BVT',74,'Bouvet Island','Antarctic Region','Norwegian Krone','NOK'),('BW','BWA',72,'Botswana','Africa','Pula','BWP'),('BY','BLR',112,'Belarus','Commonwealth of Independent States','Belarussian Ruble','BYR'),('BZ','BLZ',84,'Belize','Central America and the Caribbean','Belize Dollar','BZD'),('CA','CAN',124,'Canada','North America','Canadian Dollar','CAD'),('CC','CCK',166,'Cocos (Keeling) Islands','Southeast Asia','Australian Dollar','AUD'),('CD','COD',180,'Congo, Democratic Republic of the','Africa','Franc Congolais','CDF'),('CF','CAF',140,'Central African Republic','Africa','CFA Franc BEAC','XAF'),('CG','COG',178,'Congo, Republic of the','Africa','CFA Franc BEAC','XAF'),('CH','CHE',756,'Switzerland','Europe','Swiss Franc','CHF'),('CI','CIV',384,'Cote d\'Ivoire','Africa','CFA Franc BCEAO','XOF'),('CK','COK',184,'Cook Islands','Oceania','New Zealand Dollar','NZD'),('CL','CHL',152,'Chile','South America','Chilean Peso','CLP'),('CM','CMR',120,'Cameroon','Africa','CFA Franc BEAC','XAF'),('CN','CHN',156,'China','Asia','Yuan Renminbi','CNY'),('CO','COL',170,'Colombia','South America, Central America and the Caribbean','Colombian Peso','COP'),('CR','CRI',188,'Costa Rica','Central America and the Caribbean','Costa Rican Colon','CRC'),('CU','CUB',192,'Cuba','Central America and the Caribbean','Cuban Peso','CUP'),('CV','CPV',132,'Cape Verde','World','Cape Verdean Escudo','CVE'),('CX','CXR',162,'Christmas Island','Southeast Asia','Australian Dollar','AUD'),('CY','CYP',196,'Cyprus','Middle East','Cyprus Pound','CYP'),('CZ','CZE',203,'Czech Republic','Europe','Czech Koruna','CZK'),('DE','DEU',276,'Germany','Europe','Euro','EUR'),('DJ','DJI',262,'Djibouti','Africa','Djibouti Franc','DJF'),('DK','DNK',208,'Denmark','Europe','Danish Krone','DKK'),('DM','DMA',212,'Dominica','Central America and the Caribbean','East Caribbean Dollar','XCD'),('DO','DOM',214,'Dominican Republic','Central America and the Caribbean','Dominican Peso','DOP'),('DZ','DZA',12,'Algeria','Africa','Algerian Dinar','DZD'),('EC','ECU',218,'Ecuador','South America','US dollar','USD'),('EE','EST',233,'Estonia','Europe','Kroon','EEK'),('EG','EGY',818,'Egypt','Africa','Egyptian Pound','EGP'),('EH','ESH',732,'Western Sahara','Africa','Moroccan Dirham','MAD'),('ER','ERI',232,'Eritrea','Africa','Nakfa','ERN'),('ES','ESP',724,'Spain','Europe','Euro','EUR'),('ET','ETH',231,'Ethiopia','Africa','Ethiopian Birr','ETB'),('FI','FIN',246,'Finland','Europe','Euro','EUR'),('FJ','FJI',242,'Fiji','Oceania','Fijian Dollar','FJD'),('FK','FLK',238,'Falkland Islands (Islas Malvinas)','South America','Falkland Islands Pound','FKP'),('FM','FSM',583,'Micronesia, Federated States of','Oceania','US dollar','USD'),('FO','FRO',234,'Faroe Islands','Europe','Danish Krone','DKK'),('FR','FRA',250,'France','Europe','Euro','EUR'),('GA','GAB',266,'Gabon','Africa','CFA Franc BEAC','XAF'),('GB','GBR',826,'United Kingdom','Europe','Pound Sterling','GBP'),('GD','GRD',308,'Grenada','Central America and the Caribbean','East Caribbean Dollar','XCD'),('GE','GEO',268,'Georgia','Commonwealth of Independent States','Lari','GEL'),('GF','GUF',254,'French Guiana','South America','Euro','EUR'),('GH','GHA',288,'Ghana','Africa','Cedi','GHC'),('GI','GIB',292,'Gibraltar','Europe','Gibraltar Pound','GIP'),('GL','GRL',304,'Greenland','Arctic Region','Danish Krone','DKK'),('GM','GMB',270,'The Gambia','Africa','Dalasi','GMD'),('GN','GIN',324,'Guinea','Africa','Guinean Franc','GNF'),('GP','GLP',312,'Guadeloupe','Central America and the Caribbean','Euro','EUR'),('GQ','GNQ',226,'Equatorial Guinea','Africa','CFA Franc BEAC','XAF'),('GR','GRC',300,'Greece','Europe','Euro','EUR'),('GS','SGS',239,'South Georgia and the South Sandwich Islands','Antarctic Region','Pound Sterling','GBP'),('GT','GTM',320,'Guatemala','Central America and the Caribbean','Quetzal','GTQ'),('GU','GUM',316,'Guam','Oceania','US Dollar','USD'),('GW','GNB',624,'Guinea-Bissau','Africa','CFA Franc BCEAO','XOF'),('GY','GUY',328,'Guyana','South America','Guyana Dollar','GYD'),('HK','HKG',344,'Hong Kong (SAR)','Southeast Asia','Hong Kong Dollar','HKD'),('HM','HMD',334,'Heard Island and McDonald Islands','Antarctic Region','Australian Dollar','AUD'),('HN','HND',340,'Honduras','Central America and the Caribbean','Lempira','HNL'),('HR','HRV',191,'Croatia','Europe','Kuna','HRK'),('HT','HTI',332,'Haiti','Central America and the Caribbean','Gourde','HTG'),('HU','HUN',348,'Hungary','Europe','Forint','HUF'),('ID','IDN',360,'Indonesia','Southeast Asia','Rupiah','IDR'),('IE','IRL',372,'Ireland','Europe','Euro','EUR'),('IL','ISR',376,'Israel','Middle East','New Israeli Sheqel','ILS'),('IN','IND',356,'India','Asia','Indian Rupee','INR'),('IO','IOT',86,'British Indian Ocean Territory','World','US Dollar','USD'),('IQ','IRQ',368,'Iraq','Middle East','Iraqi Dinar','IQD'),('IR','IRN',364,'Iran','Middle East','Iranian Rial','IRR'),('IS','ISL',352,'Iceland','Arctic Region','Iceland Krona','ISK'),('IT','ITA',380,'Italy','Europe','Euro','EUR'),('JM','JAM',388,'Jamaica','Central America and the Caribbean','Jamaican dollar','JMD'),('JO','JOR',400,'Jordan','Middle East','Jordanian Dinar','JOD'),('JP','JPN',392,'Japan','Asia','Yen','JPY'),('KE','KEN',404,'Kenya','Africa','Kenyan shilling','KES'),('KG','KGZ',417,'Kyrgyzstan','Commonwealth of Independent States','Som','KGS'),('KH','KHM',116,'Cambodia','Southeast Asia','Riel','KHR'),('KI','KIR',296,'Kiribati','Oceania','Australian dollar','AUD'),('KM','COM',174,'Comoros','Africa','Comoro Franc','KMF'),('KN','KNA',659,'Saint Kitts and Nevis','Central America and the Caribbean','East Caribbean Dollar','XCD'),('KP','PRK',408,'Korea, North','Asia','North Korean Won','KPW'),('KR','KOR',410,'Korea, South','Asia','Won','KRW'),('KW','KWT',414,'Kuwait','Middle East','Kuwaiti Dinar','KWD'),('KY','CYM',136,'Cayman Islands','Central America and the Caribbean','Cayman Islands Dollar','KYD'),('KZ','KAZ',398,'Kazakhstan','Commonwealth of Independent States','Tenge','KZT'),('LA','LAO',418,'Laos','Southeast Asia','Kip','LAK'),('LB','LBN',422,'Lebanon','Middle East','Lebanese Pound','LBP'),('LC','LCA',662,'Saint Lucia','Central America and the Caribbean','East Caribbean Dollar','XCD'),('LI','LIE',438,'Liechtenstein','Europe','Swiss Franc','CHF'),('LK','LKA',144,'Sri Lanka','Asia','Sri Lanka Rupee','LKR'),('LR','LBR',430,'Liberia','Africa','Liberian Dollar','LRD'),('LS','LSO',426,'Lesotho','Africa','Loti','LSL'),('LT','LTU',440,'Lithuania','Europe','Lithuanian Litas','LTL'),('LU','LUX',442,'Luxembourg','Europe','Euro','EUR'),('LV','LVA',428,'Latvia','Europe','Latvian Lats','LVL'),('LY','LBY',434,'Libya','Africa','Libyan Dinar','LYD'),('MA','MAR',504,'Morocco','Africa','Moroccan Dirham','MAD'),('MC','MCO',492,'Monaco','Europe','Euro','EUR'),('MD','MDA',498,'Moldova','Commonwealth of Independent States','Moldovan Leu','MDL'),('MG','MDG',450,'Madagascar','Africa','Malagasy Franc','MGF'),('MH','MHL',584,'Marshall Islands','Oceania','US dollar','USD'),('MK','MKD',807,'Macedonia, The Former Yugoslav Republic of','Europe','Denar','MKD'),('ML','MLI',466,'Mali','Africa','CFA Franc BCEAO','XOF'),('MM','MMR',104,'Burma','Southeast Asia','kyat','MMK'),('MN','MNG',496,'Mongolia','Asia','Tugrik','MNT'),('MO','MAC',446,'Macao','Southeast Asia','Pataca','MOP'),('MP','MNP',580,'Northern Mariana Islands','Oceania','US Dollar','USD'),('MQ','MTQ',474,'Martinique','Central America and the Caribbean','Euro','EUR'),('MR','MRT',478,'Mauritania','Africa','Ouguiya','MRO'),('MS','MSR',500,'Montserrat','Central America and the Caribbean','East Caribbean Dollar','XCD'),('MT','MLT',470,'Malta','Europe','Maltese Lira','MTL'),('MU','MUS',480,'Mauritius','World','Mauritius Rupee','MUR'),('MV','MDV',462,'Maldives','Asia','Rufiyaa','MVR'),('MW','MWI',454,'Malawi','Africa','Kwacha','MWK'),('MX','MEX',484,'Mexico','North America','Mexican Peso','MXN'),('MY','MYS',458,'Malaysia','Southeast Asia','Malaysian Ringgit','MYR'),('MZ','MOZ',508,'Mozambique','Africa','Metical','MZM'),('NA','NAM',516,'Namibia','Africa','Namibian Dollar','NAD'),('NC','NCL',540,'New Caledonia','Oceania','CFP Franc','XPF'),('NE','NER',562,'Niger','Africa','CFA Franc BCEAO','XOF'),('NF','NFK',574,'Norfolk Island','Oceania','Australian Dollar','AUD'),('NG','NGA',566,'Nigeria','Africa','Naira','NGN'),('NI','NIC',558,'Nicaragua','Central America and the Caribbean','Cordoba Oro','NIO'),('NL','NLD',528,'Netherlands','Europe','Euro','EUR'),('NO','NOR',578,'Norway','Europe','Norwegian Krone','NOK'),('NP','NPL',524,'Nepal','Asia','Nepalese Rupee','NPR'),('NR','NRU',520,'Nauru','Oceania','Australian Dollar','AUD'),('NU','NIU',570,'Niue','Oceania','New Zealand Dollar','NZD'),('NZ','NZL',554,'New Zealand','Oceania','New Zealand Dollar','NZD'),('OM','OMN',512,'Oman','Middle East','Rial Omani','OMR'),('PA','PAN',591,'Panama','Central America and the Caribbean','balboa','PAB'),('PE','PER',604,'Peru','South America','Nuevo Sol','PEN'),('PF','PYF',258,'French Polynesia','Oceania','CFP Franc','XPF'),('PG','PNG',598,'Papua New Guinea','Oceania','Kina','PGK'),('PH','PHL',608,'Philippines','Southeast Asia','Philippine Peso','PHP'),('PK','PAK',586,'Pakistan','Asia','Pakistan Rupee','PKR'),('PL','POL',616,'Poland','Europe','Zloty','PLN'),('PM','SPM',666,'Saint Pierre and Miquelon','North America','Euro','EUR'),('PN','PCN',612,'Pitcairn Islands','Oceania','New Zealand Dollar','NZD'),('PR','PRI',630,'Puerto Rico','Central America and the Caribbean','US dollar','USD'),('PS','PSE',275,'Palestinian Territory, Occupied',NULL,NULL,NULL),('PT','PRT',620,'Portugal','Europe','Euro','EUR'),('PW','PLW',585,'Palau','Oceania','US dollar','USD'),('PY','PRY',600,'Paraguay','South America','Guarani','PYG'),('QA','QAT',634,'Qatar','Middle East','Qatari Rial','QAR'),('RE','REU',638,'Reunion','World','Euro','EUR'),('RO','ROU',642,'Romania','Europe','Leu','ROL'),('RU','RUS',643,'Russia','Asia','Russian Ruble','RUB'),('RW','RWA',646,'Rwanda','Africa','Rwanda Franc','RWF'),('SA','SAU',682,'Saudi Arabia','Middle East','Saudi Riyal','SAR'),('SB','SLB',90,'Solomon Islands','Oceania','Solomon Islands Dollar','SBD'),('SC','SYC',690,'Seychelles','Africa','Seychelles Rupee','SCR'),('SD','SDN',736,'Sudan','Africa','Sudanese Dinar','SDD'),('SE','SWE',752,'Sweden','Europe','Swedish Krona','SEK'),('SG','SGP',702,'Singapore','Southeast Asia','Singapore Dollar','SGD'),('SH','SHN',654,'Saint Helena','Africa','Saint Helenian Pound','SHP'),('SI','SVN',705,'Slovenia','Europe','Tolar','SIT'),('SJ','SJM',744,'Svalbard','Arctic Region','Norwegian Krone','NOK'),('SK','SVK',703,'Slovakia','Europe','Slovak Koruna','SKK'),('SL','SLE',694,'Sierra Leone','Africa','Leone','SLL'),('SM','SMR',674,'San Marino','Europe','Euro','EUR'),('SN','SEN',686,'Senegal','Africa','CFA Franc BCEAO','XOF'),('SO','SOM',706,'Somalia','Africa','Somali Shilling','SOS'),('SR','SUR',740,'Suriname','South America','Suriname Guilder','SRG'),('ST','STP',678,'Sao Tome and Principe','Africa','Dobra','STD'),('SV','SLV',222,'El Salvador','Central America and the Caribbean','El Salvador Colon','SVC'),('SY','SYR',760,'Syria','Middle East','Syrian Pound','SYP'),('SZ','SWZ',748,'Swaziland','Africa','Lilangeni','SZL'),('TC','TCA',796,'Turks and Caicos Islands','Central America and the Caribbean','US Dollar','USD'),('TD','TCD',148,'Chad','Africa','CFA Franc BEAC','XAF'),('TF','ATF',260,'French Southern and Antarctic Lands','Antarctic Region','Euro','EUR'),('TG','TGO',768,'Togo','Africa','CFA Franc BCEAO','XOF'),('TH','THA',764,'Thailand','Southeast Asia','Baht','THB'),('TJ','TJK',762,'Tajikistan','Commonwealth of Independent States','Somoni','TJS'),('TK','TKL',772,'Tokelau','Oceania','New Zealand Dollar','NZD'),('TL','TLS',626,'East Timor',NULL,'Timor Escudo','TPE'),('TM','TKM',795,'Turkmenistan','Commonwealth of Independent States','Manat','TMM'),('TN','TUN',788,'Tunisia','Africa','Tunisian Dinar','TND'),('TO','TON',776,'Tonga','Oceania','Pa\'anga','TOP'),('TR','TUR',792,'Turkey','Middle East','Turkish Lira','TRL'),('TT','TTO',780,'Trinidad and Tobago','Central America and the Caribbean','Trinidad and Tobago Dollar','TTD'),('TV','TUV',798,'Tuvalu','Oceania','Australian Dollar','AUD'),('TW','TWN',158,'Taiwan','Southeast Asia','New Taiwan Dollar','TWD'),('TZ','TZA',834,'Tanzania','Africa','Tanzanian Shilling','TZS'),('UA','UKR',804,'Ukraine','Commonwealth of Independent States','Hryvnia','UAH'),('UG','UGA',800,'Uganda','Africa','Uganda Shilling','UGX'),('UM','UMI',581,'United States Minor Outlying Islands',NULL,'US Dollar','USD'),('US','USA',840,'United States','North America','US Dollar','USD'),('UY','URY',858,'Uruguay','South America','Peso Uruguayo','UYU'),('UZ','UZB',860,'Uzbekistan','Commonwealth of Independent States','Uzbekistan Sum','UZS'),('VA','VAT',336,'Holy See (Vatican City)','Europe','Euro','EUR'),('VC','VCT',670,'Saint Vincent and the Grenadines','Central America and the Caribbean','East Caribbean Dollar','XCD'),('VE','VEN',862,'Venezuela','South America, Central America and the Caribbean','Bolivar','VEB'),('VG','VGB',92,'British Virgin Islands','Central America and the Caribbean','US dollar','USD'),('VI','VIR',850,'Virgin Islands','Central America and the Caribbean','US Dollar','USD'),('VN','VNM',704,'Vietnam','Southeast Asia','Dong','VND'),('VU','VUT',548,'Vanuatu','Oceania','Vatu','VUV'),('WF','WLF',876,'Wallis and Futuna','Oceania','CFP Franc','XPF'),('WS','WSM',882,'Samoa','Oceania','Tala','WST'),('YE','YEM',887,'Yemen','Middle East','Yemeni Rial','YER'),('YT','MYT',175,'Mayotte','Africa','Euro','EUR'),('YU','YUG',891,'Yugoslavia','Europe','Yugoslavian Dinar','YUM'),('ZA','ZAF',710,'South Africa','Africa','Rand','ZAR'),('ZM','ZWB',894,'Zambia','Africa','Kwacha','ZMK'),('ZW','ZWE',716,'Zimbabwe','Africa','Zimbabwe Dollar','ZWD');
/*!40000 ALTER TABLE `Countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DailyQuotes`
--

DROP TABLE IF EXISTS `DailyQuotes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `DailyQuotes` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Text` mediumtext NOT NULL,
  `Author` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `DailyQuotes`
--

LOCK TABLES `DailyQuotes` WRITE;
/*!40000 ALTER TABLE `DailyQuotes` DISABLE KEYS */;
INSERT INTO `DailyQuotes` VALUES (1,'Give me a woman who loves beer and I will conquer the world.','Kaiser Wilhelm'),(2,'All right, Brain, I don\'t like you and you don\'t like me - so let\'s just do this and I\'ll get back to killing you with beer.','Homer Simpson'),(3,'If it was so, it might be; and if it were so,it would be; but as it isn\' t, it ain\' t. That\'s logic.','Lewis Carrol'),(4,'God does not care about our mathematical difficulties. He integrates empirically.','Albert Einstein'),(5,'Treat your friend as if he might become an enemy.','Publilius Syrus'),(13,'Time to have tea!','Me');
/*!40000 ALTER TABLE `DailyQuotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `FriendList`
--

DROP TABLE IF EXISTS `FriendList`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `FriendList` (
  `ID` bigint(8) NOT NULL default '0',
  `Profile` bigint(8) NOT NULL default '0',
  `Check` tinyint(2) NOT NULL default '0',
  UNIQUE KEY `FriendPair` (`ID`,`Profile`),
  KEY `ID` (`ID`),
  KEY `Profile` (`Profile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `FriendList`
--

LOCK TABLES `FriendList` WRITE;
/*!40000 ALTER TABLE `FriendList` DISABLE KEYS */;
/*!40000 ALTER TABLE `FriendList` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GalleryAlbums`
--

DROP TABLE IF EXISTS `GalleryAlbums`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `GalleryAlbums` (
  `ID` int(8) unsigned NOT NULL auto_increment,
  `IDMember` bigint(8) unsigned NOT NULL default '0',
  `Name` varchar(255) NOT NULL default '',
  `Comment` tinytext,
  `Created` datetime NOT NULL default '0000-00-00 00:00:00',
  `Modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `Access` enum('public','private','friends') NOT NULL default 'public',
  PRIMARY KEY  (`ID`),
  KEY `IDMember` (`IDMember`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `GalleryAlbums`
--

LOCK TABLES `GalleryAlbums` WRITE;
/*!40000 ALTER TABLE `GalleryAlbums` DISABLE KEYS */;
/*!40000 ALTER TABLE `GalleryAlbums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GalleryObjects`
--

DROP TABLE IF EXISTS `GalleryObjects`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `GalleryObjects` (
  `ID` bigint(10) unsigned NOT NULL auto_increment,
  `IDAlbum` int(8) unsigned NOT NULL default '0',
  `Filename` varchar(255) NOT NULL default '',
  `ThumbFilename` varchar(255) default NULL,
  `ObjectType` enum('photo','audio','video') NOT NULL default 'photo',
  `Comment` tinytext,
  `Created` datetime NOT NULL default '0000-00-00 00:00:00',
  `Modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `OrderInAlbum` int(4) unsigned NOT NULL default '0',
  `Approved` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `IDAlbum` (`IDAlbum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `GalleryObjects`
--

LOCK TABLES `GalleryObjects` WRITE;
/*!40000 ALTER TABLE `GalleryObjects` DISABLE KEYS */;
/*!40000 ALTER TABLE `GalleryObjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GlParams`
--

DROP TABLE IF EXISTS `GlParams`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `GlParams` (
  `Name` varchar(32) NOT NULL default '',
  `VALUE` mediumtext NOT NULL,
  `kateg` int(11) NOT NULL default '0',
  `desc` varchar(255) NOT NULL default '',
  `Type` enum('digit','text','checkbox','select','combobox') NOT NULL default 'digit',
  `check` text NOT NULL,
  `err_text` varchar(255) NOT NULL default '',
  `order_in_kateg` float default NULL,
  PRIMARY KEY  (`Name`),
  KEY `kateg` (`kateg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `GlParams`
--

LOCK TABLES `GlParams` WRITE;
/*!40000 ALTER TABLE `GlParams` DISABLE KEYS */;
INSERT INTO `GlParams` VALUES ('anon_mode','',1,'Anonymous mode (no contact information)','checkbox','','',12),('autoApproval_ifJoin','on',6,'Automatic profile activation after joining','checkbox','','',NULL),('autoApproval_ifPhoto','on',6,'Do not change profile status after photo uploading','checkbox','','',NULL),('autoApproval_ifProfile','on',6,'Do not change profile status after editing profile information','checkbox','','',NULL),('autoApproval_ifSound','on',6,'Do not change profile status after sound uploading','checkbox','','',NULL),('autoApproval_ifVideo','on',6,'Do not change profile status after video uploading','checkbox','','',NULL),('autoApproval_Photo','on',6,'Automatic photo activation after uploading','checkbox','','',NULL),('blogCaptionMaxLenght','150',22,'Maximum length of Blog Caption','digit','','',5),('blogCategoryCaptionMaxLenght','150',22,'Maximum length of Blog Category caption','digit','','',3),('blogCommentMaxLenght','250',22,'Maximum length of Blog comment','digit','','',2),('blogAutoApproval','on',22,'Enable AutoApproval of Blogs','checkbox','','',7),('blog_step','10',22,'How many blogs showing on page','digit','','',15),('cmdDay','10',0,'','digit','','',NULL),('compose_index_cols','content,menu',0,'','select','','',NULL),('currency_code','USD',0,'Currency code (for checkout system)','combobox','return strlen($arg0) > 0;','cannot be empty.',NULL),('currency_sign','$',15,'Currency sign (for display purposes only)','digit','return strlen($arg0) > 0;','cannot be empty.',9),('date_format','%m-%d-%y %H:%i',15,'Long Date Format <a href=\"#\" onclick=\"javascript: window.open(\'/admin/help.html\', \'DateFormat\', \'width=500,height=400,scrollbars=yes,menubar=no,resizable=no\'); return false;\">?</a>','digit','','',15),('db_clean_msg','180',11,'Clean old messages ( days )','digit','','',NULL),('db_clean_priv_msg','2',11,'Clean old private messages ( days )','digit','','',NULL),('db_clean_profiles','180',11,'Clean old profiles by last log in ( days )','digit','','',NULL),('db_clean_views','180',11,'Clean old profile views ( days )','digit','','',NULL),('db_clean_vkiss','90',11,'Clean old greetings ( days )','digit','','',NULL),('default_country','US',0,'Default Country on Index Page','text','','',NULL),('default_online_users_num','50',3,'Maximum number of online members shown in the member control panel','digit','','',6),('enable_aff','',15,'Enable affiliate support','checkbox','','',1),('enable_contact_form','on',15,'Show contact form on contact us page','checkbox','','',2),('enable_cupid','on',12,'Enable cupid mails','checkbox','','',NULL),('enable_customization','on',1,'Enable profile customization','checkbox','','',10),('enable_event_creating','on',1,'Allow members to create events','checkbox','','',NULL),('enable_gallery','on',2,'Enable gallery','checkbox','','',1),('enable_gd','on',15,'Use GD library for image processing','checkbox','','',5),('enable_im','',3,'Enable Instant Messenger','checkbox','','',1),('enable_inbox_notify','',17,'Enable new message notifications','checkbox','','',NULL),('enable_match','on',12,'Enable matchmaking','checkbox','','',NULL),('enable_msg_dest_choice','on',17,'Enable message destination user choice','checkbox','','',NULL),('enable_poll','on',20,'Enable members polls','checkbox','','',NULL),('enable_profileComments','on',1,'Enable Comments for profiles','checkbox','','',7),('enable_promotion_membership','on',7,'Enable promotional membership','checkbox','','',1),('enable_ray','on',15,'Enable Ray','checkbox','','',2),('enable_ray_pro','',15,'Enable Ray Pro (must be installed and Ray must be enabled)','checkbox','','',7),('enable_recurring','on',0,'Enable recurring billings','checkbox','','',NULL),('enable_template','',15,'Enable Users to Change Templates','checkbox','','',NULL),('enable_watermark','on',16,'Enable Watermark','checkbox','','',1),('enable_zip_loc','on',15,'Enable search by ZIP codes','checkbox','','',0),('expire_notification_days','1',5,'Number of days before membership expiration to notify members ( -1 = after expiration )','digit','','',NULL),('expire_notify_once','on',5,'Notify members about membership expiration only once (every day otherwise)','checkbox','','',NULL),('featured_mode','horizontal',0,'Featured members layout direction','combobox','return $arg0 == \'vertical\' || $arg0 == \'horizontal\' ? true : false;','posible values : horizontal, vertical',NULL),('featured_num','6',0,'Number of featured members displayed on front page','digit','return $arg0 >= 0;','must be equal to or greater than zero.',NULL),('free_mode','on',0,'Site is running in free mode','checkbox','','',NULL),('friendlist','on',15,'Show Friend List','checkbox','','',3),('gallery_alboms','20',2,'How many albums allowed for one member in one category','digit','','',3),('gallery_audio_size','16777216',2,'Maximum size for audio file in gallery (in byte)','digit','','',8),('gallery_objects','100',2,'How many objects allowed for one member in one album','digit','','',4),('gallery_objects_step','9',2,'How many objects showing on page','digit','','',4),('gallery_photo_height','250',2,'Height of gallery photo in pixels','digit','','',6),('gallery_photo_size','83886080',2,'Maximum size for photo file in gallery (in byte)','digit','','',7),('gallery_photo_width','250',2,'Width of gallery photo in pixels','digit','','',5),('gallery_show_unapproved','on',2,'Show unapproved objects in gallery','checkbox','','',2),('gallery_video_size','16777216',2,'Maximum size for video file in gallery (in byte)','digit','','',9),('lang_default','en',0,'Default site language','text','','',NULL),('match_percent','70',12,'Send a cupid mail if the recently joined profile matches more than this percentage','digit','','',NULL),('max_icon_height','45',23,'Max height of profile icon (in pixels)','digit','','',8),('max_icon_width','45',23,'Max width of profile icon (in pixels)','digit','','',7),('max_inbox_messages','5',3,'Maximum number of messages stored in inbox','digit','','',3),('max_inbox_message_size','1500',3,'Maximum message size in symbols','digit','','',4),('max_media_title','150',23,'Max length of title for media file','digit','','',3.1),('max_news_header','50',10,'Maximum length of news header','digit','','',NULL),('max_news_on_home','2',10,'Maximum number of news items to show on homepage','digit','','',NULL),('max_news_preview','128',10,'Maximum length of news preview','digit','','',NULL),('max_news_text','4096',10,'Maximum length of news text','digit','','',NULL),('max_photo_files','20',23,'Max number of profile photos','digit','','',13),('max_photo_height','340',23,'Max height of profile photo (in pixels)','digit','','',12),('max_photo_width','340',23,'Max width of profile photo (in pixels)','digit','','',11),('max_story_header','32',10,'Maximum length of story header','digit','','',NULL),('max_story_preview','400',10,'Maximum length of story preview text','digit','','',NULL),('max_story_text','4096',10,'Maximum length of story text','digit','','',NULL),('max_thumb_height','110',23,'Max height of profile thumbnail (in pixels)','digit','','',10),('max_thumb_width','110',23,'Max width of profile thumbnail (in pixels)','digit','','',9),('member_online_time','5',3,'Time period in minutes within which a member is considered to be online','digit','','',5),('MetaDescription','',19,'Insert Meta description on site  pages','text','','',NULL),('MetaKeyWords','',19,'Insert Meta keywords on site pages (comma-separated list)','text','','',NULL),('min_media_title','1',23,'Min length of title for media file','digit','','',3.2),('more_photos_on_searchrow','on',1,'Show \"More Photos\" link on search result','checkbox','','',11),('msgs_per_start','20',8,'Send emails from queue, it happens every cron execution (5m-1h)','digit','','',NULL),('news_enable','1',0,'show boonex news in admin panel','digit','','',NULL),('newusernotify','on',1,'New User Notify','checkbox','','',2),('profile_poll_num','4',20,'Number of polls that user can create','digit','','',NULL),('profile_poll_act','on',20,'Enable profile polls activation','checkbox','','',NULL),('promotion_membership_days','7',7,'Number of days for promotional membership','digit','','',2),('search_end_age','75',1,'Highest age possible for site members','digit','','',21),('search_start_age','18',1,'Lowest age possible for site members','digit','','',20),('short_date_format','%m-%d-%y',15,'Short Date Format <a href=\"#\" onclick=\"javascript: window.open(\'/admin/help.html\', \'DateFormat\', \'width=500,height=400,scrollbars=yes,menubar=no,resizable=no\'); return false;\">?</a>','digit','','',14),('template','uni',15,'Template','combobox','global $dir; return (strlen($arg0) > 0 && file_exists($dir[\"root\"].\"templates/tmpl_\".$arg0) ) ? true : false;','cannot be empty and template must be valid.',17),('top_members_max_num','6',0,'How many results show on index page in top members area','digit','','',NULL),('top_members_mode','rand',0,'Show members on index page<br /> (if enabled in the template)','combobox','return $arg0 == \'online\' || $arg0 == \'rand\' || $arg0 == \'last\' || $arg0 == \'top\' ? true : false;','posible values : online, rand, last, top',NULL),('track_profile_view','on',1,'Track all profile views. Later a member can manage these \"views\".','checkbox','','',1),('transparent1','15',16,'Transparency for first image','digit','','',2),('transparent2','15',16,'Transparency for second image','digit','','',NULL),('t_Activation','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>Your profile was reviewed and activated !</p>\r\n\r\n<p>Simply follow the link below to enjoy our services:<br /><a href=\"<Domain>member.php\"><Domain>member.php</a></p>\r\n\r\n<p>Your identification number (ID): <span style=\"color:#FF6633\"><recipientID></span></p>\r\n\r\n<p>Your e-mail used for registration: <span style=\"color:#FF6633\"><Email></span></p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Profile activation message template. Automatically sent to a member, when profile status is changed to \"Active\".','text','','',NULL),('t_Activation_subject','Profile status was changed to Active',4,'','text','','',NULL),('t_AdminEmail','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>Administration of the <a href=\"<Domain>\"><SiteName></a> is glad to inform you that </p>\r\n\r\n<p>=========================</p>\r\n<p style=\"color:#3B5C8E\"><MessageText></p>\r\n<p>=========================</p>\r\n\r\n\r\n <p style=\"font-size:10px;\">NOTE: You received this message because our records show that you are a registered member of <a href=\"<Domain>\"><SiteName></a> (<Domain>).\r\n If you wish to unregister, log in to your member account and hit \"Unregister\".</p>\r\n\r\n<p>-----</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\nAuto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Email template for message sending from the Admin Panel.','text','','',NULL),('t_AdminEmail_subject','Message from <SiteName> Admin',4,'','text','','',NULL),('t_Compose','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Hello <RealName></b>,</p>\r\n\r\n<p>You have received a message from <ProfileReference>!</p>\r\n\r\n<p>To check this message login to your account here: <a href=\"<Domain>member.php\"><Domain>member.php</a></p>\r\n\r\n<p>---</p>\r\nBest regards,  <SiteName> \r\n<p style=\"font: bold 10px Verdana; color:red\">!!!Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Email template for notification about new messages in the inbox.','text','','',NULL),('t_Compose_subject','Notification about new messages in the inbox',4,'','text','','',NULL),('t_Confirmation','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>Thank you for registering at <SiteName>!</p>\r\n\r\n<p style=\"color:#3B5C8E\">CONFIRMATION CODE: <ConfCode></p>\r\n\r\n<p>Or you can also simply follow the link below:\r\n<a href=\"<ConfirmationLink>\"><ConfirmationLink></a></p>\r\n\r\n<p>This is necessary to complete your registration.<br />Without doing that you won\'t be submitted to our database.</p>\r\n\r\n<p>Your identification number (ID): <span style=\"color:#FF6633; font-weight:bold;\"><recipientID></span></p>\r\n\r\n<p>Your e-mail used for registration: \r\n<span style=\"color:#FF6633\"><Email></span></p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Profile e-mail confirmation message template. Automatically sent to a registered member, and also can be sent by admin to the \"Unconfirmed\" members.','text','','',NULL),('t_Confirmation_subject','Confirm your profile',4,'','text','','',NULL),('t_CupidMail','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Hello <RealName></b>,</p>\r\n\r\n<p>We are glad to inform you that a profile was added or modified at <Domain> that matches yours.</p>\r\n\r\n<p>Match profile:<span style=\"color:#FF6633\"><a href=\"<MatchProfileLink>\"><MatchProfileLink></a></span></p>\r\n\r\n<p>Your Member ID:<span style=\"color:#FF6633\"><StrID></span></p>\r\n\r\n<p>--</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Cupid mail template','text','','',NULL),('t_CupidMail_subject','Match Notification',4,'','text','','',NULL),('t_Forgot','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>Your member ID: <span style=\"color:#FF6633\"><recipientID></span></p>\r\n\r\n<p>Your password: <span style=\"color:#FF6633\"><Password></span></p>\r\n\r\n<p>You must login here: <span style=\"color:#FF6633\"><a href=\"<Domain>member.php\"><Domain>member.php</a></span></p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Forgot password email message','text','','',NULL),('t_Forgot_subject','Forgot password email message',4,'','text','','',NULL),('t_FreeEmail','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>You have requested <strong><profileNickName></strong>\'s contact information.</p>\r\n\r\n<p><ContactInfo></p>\r\n\r\n<p>View member\'s profile: <a href=\"<Domain>profile.php?ID=<profileID>\"><Domain>profile.php?ID=<profileID></a></p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Free contact information letter template sent to members requesting contact information of those members available for free.','text','','',NULL),('t_FreeEmail_subject','Free contact information',4,'','text','','',NULL),('t_MemExpiration','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Hello <RealName></b>,</p>\r\n\r\n<p>We are notifying you that your <SiteName> <MembershipName> will expire in <ExpireDays> days (-1 = already expired).\r\n\r\n To renew your membership login to your <SiteName> account at <a href=\"<Domain>member.php\"><Domain>member.php</a> and go to <a href=\"<Domain>membership.php\"><Domain>membership.php</a></p>\r\n\r\n<p>Your Member ID: <span style=\"color:#FF6633; font-weight:bold;\"><recipientID></span></p>\r\n\r\n<p>--</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Membership expiration letter sent to members whose membership level expires.','text','','',NULL),('t_MemExpiration_subject','<your subject here>',4,'','text','','',NULL),('t_Message','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>We are glad to inform you that the member\r\n<ProfileReference> has sent you a message! </p>\r\n\r\n<p>-------- Message ------------------------------------------------<br />\r\n<span style=\"color:#3B5C8E\"><MessageText></span><br />\r\n---------------------------------------------------------------------\r\n</p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Message template sent to members when they receive messages from other members.','text','','',NULL),('t_Message_subject','You receive messages from other members',4,'','text','','',NULL),('t_PrivPhotosAnswer','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Hello <NickName></b>,</p>\r\n\r\n<p>We are informing you that <PrivPhotosMember> granted you a password for their private photos.</p>\r\n\r\n<p>Link to <PrivPhotosMember> profile <a href=\"<Profile>\"><Profile></a></p>\r\n\r\n<p>--</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Answer for Private Photos Request template','text','','',NULL),('t_PrivPhotosAnswer_subject','<your subject here>',4,'','text','','',NULL),('t_PrivPhotosRequest','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Hello <NickName></b>,</p>\r\n\r\n<p>We are informing you that <strong><profileNickName></strong> asks for a password for your private photos.</p>\r\n\r\n<p>Link to <profileNickName>\'s profile <a href=\"<Domain>profile.php?ID=<profileID>\"><Domain>profile.php?ID=<profileID></a></p>\r\n\r\n<p>----------</p>\r\n\r\n<p style=\"font: bold 10px Verdana; color:red\"><site></p></body></html>',4,'Request fot Private Photos template','text','','',NULL),('t_PrivPhotosRequest_subject','Request for private photo password at <SiteName>',4,'','text','','',NULL),('t_PurchaseContacts','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Hello <RealName></b>,</p>\r\n\r\n<p>You purchased the following profiles on <b><SiteName></b>:</p>\r\n\r\n<ProfileList>\r\n\r\n<p>--</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Purchase contacts letter template','text','','',NULL),('t_PurchaseContacts_subject','Your purchase at <SiteName>',4,'','text','','',NULL),('t_Rejection','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>Your profile was reviewed and rejected due to the following reasons:</p>\r\n\r\n<p>1) Your profile information was supplied in the wrong  language. <br />\r\n2) Your profile contains illegal information. Make sure that you: do not use black language, do not specify your contact information in the wrong text fields;<br />\r\n3) You have uploaded unacceptable photos to your profile;<br />\r\n4) We doubt that you are a real person. </p>\r\n\r\n<p>--</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Profile rejection message template. Automatically sent to a member, when profile status is changed to \"Reject\".','text','','',NULL),('t_Rejection_subject','Profile status was changed to Rejected',4,'','text','','',NULL),('t_SDatingAdminEmail','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>Administration of the <a href=\"<Domain>\"><Domain></a> <b><SiteName></b> is glad to inform you that</p>\r\n\r\n<p><MessageText></p>\r\n\r\n<p>We are reminding you that your Unique ID is <b><PersonalUID></b>.</p>\r\n\r\n<p>-----</p>\r\n<p>NOTE: You received this message because you are a registered member of <b><SiteName></b>\r\nand also are a participant of the SpeedDating \"<NameSDating>\" held at \"<PlaceSDating>\" <WhenStarSDating>.<br />\r\nPlease visit <a href=\"<LinkSDatingEvent>\"><LinkSDatingEvent></a> to see the event details.</p>\r\n\r\n<p>---</p>\r\n<p style=\"font: bold 10px Verdana; color:red\">!!!Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Email template for message sending from the SpeedDating\'s Admin Panel.','text','','',NULL),('t_SDatingAdminEmail_subject','Additional information on SpeedDating.',4,'','text','','',NULL),('t_SDatingCongratulation','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>We are glad to inform you that You successfully purchased a ticket for SpeedDating \"<NameSDating>\" which will take place at \"<PlaceSDating>\" <WhenStarSDating>.<br />\r\nYour personal Unique ID is <b><PersonalUID></b>. If you want to change it please click <a href=\"<LinkSDatingEvent>\">here</a>.</p>\r\n\r\n<p>Please visit <a href=\"<LinkSDatingEvent>\"><LinkSDatingEvent></a> to see event details.</p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>---</p>\r\n<p style=\"font: bold 10px Verdana; color:red\">!!!Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'SpeedDating message template. Automatically sent to a member after ticket purchase.','text','','',NULL),('t_SDatingCongratulation_subject','SpeedDating ticket purchase',4,'','text','','',NULL),('t_SDatingMatch','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>We are glad to inform you that You were matched with the following participant of SpeedDating \"<NameSDating>\" which took place at \"<PlaceSDating>\" <WhenStarSDating>: <a href=\"<MatchLink>\"><MatchLink></a></p>\r\n\r\n<p>Please visit <a href=\"<LinkSDatingEvent>\"><LinkSDatingEvent></a> to see the event details.</p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>---</p>\r\n<p style=\"font: bold 10px Verdana; color:red\">!!!Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'SpeedDating message template. Automatically sent to a member when there is a match.','text','','',NULL),('t_SDatingMatch_subject','Congratulations! You were successfully matched during SpeedDating!',4,'','text','','',NULL),('t_SpamReport','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><a href=\"<Domain>profile.php?ID=<reporterID>\">User <b><reporterNick> (<reporterID>)</b></a> reported that user <a href=\"<Domain>profile.php?ID=<spamerID>\"><b><spamerNick> (<spamerID>)</b></a> spammed.</p>\r\n\r\n<p>Reporter: <span style=\"color:#FF6633;\"><a href=\"<Domain>profile.php?ID=<reporterID>\"><Domain>profile.php?ID=<reporterID></a></span>\r\n<br />Spammer: <span style=\"color:#FF6633;\"><a href=\"<Domain>profile.php?ID=<spamerID>\"><Domain>profile.php?ID=<spamerID></a></span></p></body></html>',4,'Template for a \"Report Spam\" feature.','text','','',NULL),('t_SpamReport_subject','Spam report from <SiteName>',4,'','text','','',NULL),('t_TellFriend','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Hello</b>,</p>\r\n\r\n<p>I surfed the web and found a cool site: <a href=\"<Link>\"><Link></a><br />\r\nI thought it might be interesting to you.</p>\r\n\r\n<p><span style=\"color:#FF6633\"><FromName></span></p></body></html>',4,'Template for \"Invite a Friend\" feature.','text','','',NULL),('t_TellFriendProfile','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Hello</b>,</p>\r\n\r\n<p>I surfed the web and found a cool member\'s profile: <a href=\"<Link>\"><Link></a><br />\r\nI thought it might be interesting to you.</p>\r\n\r\n<p><span style=\"color:#FF6633\"><FromName></span></p></body></html>',4,'Template for \"Email profile to a friend\" feature.','text','','',NULL),('t_TellFriendProfile_subject','Email profile to a friend',4,'','text','','',NULL),('t_TellFriend_subject','Invite a Friend',4,'','text','','',NULL),('t_VKiss','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>We are glad to inform you that member <ProfileReference> sent you a greeting!</p>\r\n\r\n<p>A greeting means that the member is interested in contacting you. Please, be polite and answer with your greeting in return. You can send it by merely following the link:<br />\r\n<VKissLink>\r\n</p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Greeting notification letter template sent to members when they receive greetings from other members. The letter also allows you to instantly send a greeting back.','text','','',NULL),('t_VKiss_visitor','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>We are glad to inform you that <b>Visitor</b> sent you a greeting!</p>\r\n\r\n<p>A greeting means that the person visited your profile and liked it. Have a nice day and enjoy!</p>\r\n\r\n<p>Thank you for using our services!</p>\r\n\r\n<p>--</p>\r\n<p style=\"font: bold 10px Verdana; color:red\"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>',4,'Greeting notification letter template sent to members when they receive greetings from other members. The letter also allows you to instantly send a greeting back.','text','','',NULL),('t_VKiss_subject','Greeting notification',4,'','text','','',NULL),('votes','on',1,'Enable profile votes','checkbox','','',5),('votes_pic','on',1,'Enable photos votes','checkbox','','',6),('Water_Mark','',16,'Water Mark','text','','',3),('zodiac','',1,'Show zodiac signs','checkbox','','',9),('php_date_format','F j, Y',15,'PHP date format','digit','','',16),('group_img_width','600',24,'Gallery max image width','digit','','',1),('group_img_height','600',24,'Gallery max image height','digit','','',2),('group_img_tmb_width','100',24,'Gallery max image thumb width','digit','','',3),('group_img_tmb_height','100',24,'Gallery max image thumb height','digit','','',4),('group_invitation_text','<b>{sender}</b> has invited you to join <b>{group}</b>.<br />\r\nGroups allow users to communicate on the forums on interesting topics, share pictures, etc.<br />\r\nYou may accept or reject this invitation below:<br />\r\n<b>{accept} &nbsp; &nbsp; &nbsp; {reject}</b>',24,'Group invitation text','text','','',5),('group_approve_notify','The creator of the {group} allows you to join the group.<br />\r\nNow you\'re an active member of this group and you can share your opinion, post images and communicate on message boards.\r\n',24,'Group member approve notification','text','','',6),('group_creator_request','Hello, {creator}.<br />\r\nMember {member} would like to join your group {group}.<br />\r\nYou may approve or reject this join request below:<br />\r\n{approve} &nbsp; &nbsp; &nbsp; {reject}',24,'Request message to group creator','text','','',8),('group_reject_notify','Dear {member},<br />\r\nSorry but the creator of the group {group} doesn\'t allow you to join the group. If you wish try again later.\r\n',24,'Group member reject notification','text','','',7),('top_photos_max_num','8',0,'How many gallery files show on index page in photos area','digit','','',NULL),('top_photos_mode','rand',0,'Show members on index page<br /> (if enabled in the template)','combobox','return $arg0 == \'rand\' || $arg0 == \'last\' || $arg0 == \'top\' ? true : false;','posible values: rand, last, top',NULL),('tags_non_parsable','hi, hey, hello, all, i, i\'m, i\'d, am, for, in, to, a, the, on, it\'s, is, my, of, are, from, i\'m, me, you, and, we, not, will, at, where, there',25,'Non-parsable tags (type all tags in lower case, delimit them by comma)','text','','',0),('tags_last_parse_time','0',0,'Temporary value when tags cron-job was runed last time','digit','','',NULL),('tags_min_rating','2',25,'Minimum rating of tag to show it','digit','','',2),('max_blogs_on_home','3',22,'Maximum number of Blogs to show on homepage','digit','','',2),('max_blog_preview','128',22,'Maximum length of Blog preview','digit','','',3),('profile_view_cols','thin,thick',0,'Profile view columns order','digit','','',NULL),('a_max_live_days_classifieds','30',3,'How long can Classifieds live (days)','digit','','',10),('autoApproval_ifNoConfEmail','on',6,'Automatic profile confirmation without Confirmation Email','checkbox','','',NULL),('enable_paid_system','on',3,'Enable Ability to work with Buy Now button in Classifieds','checkbox','','',9),('t_BuyNow','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<div style=\"border:1px solid #CCCCCC;\">\r\n<div style=\"color:#666666; font-weight:bold; height:23px; padding:3px 0px 0px 6px; text-transform:uppercase;\">\r\nCongratulations! <String1>.\r\n</div>\r\n<div style=\"padding:3px 3px 10px;\">\r\nItem title: <Subject><br/>\r\nSeller`s Name: <NickName><br/>\r\nSeller`s email: <EmailS><br/><br/>\r\nBuyer Name: <NickNameB><br/>\r\nBuyer email: <EmailB><br/><br/>\r\nPrice details: <sCustDetails><br/><br/>\r\nLink to Item<br/>\r\n<a href=\"<ShowAdvLnk>\"><ShowAdvLnk></a><br/><br/>\r\nContact the <Who> directly to arrange payment and delivery. To avoid fraud, we recommend dealing locally, avoiding Western Union and wire transfers.<br/><br/>\r\nThank you for using our site,<br/>\r\n<sPowDol><br/>\r\nP.S. If you ever need support or have comments for us, e-mail us at <site[\'email\']></div></div>\r\n</body></html>',4,'BuyNow notification letter template for Buyer','text','','',NULL),('t_BuyNow_subject','You have purchased an item',4,'','text','','',NULL),('t_BuyNowS','<html><head></head><body style=\"font: 12px Verdana; color:#000000\">\r\n<div style=\"border:1px solid #CCCCCC;\">\r\n<div style=\"color:#666666; font-weight:bold; height:23px; padding:3px 0px 0px 6px; text-transform:uppercase;\">\r\nCongratulations! <String1>.\r\n</div>\r\n<div style=\"padding:3px 3px 10px;\">\r\nItem title: <Subject><br/>\r\nSeller`s Name: <NickName><br/>\r\nSeller`s email: <EmailS><br/><br/>\r\nBuyer Name: <NickNameB><br/>\r\nBuyer email: <EmailB><br/><br/>\r\nPrice details: <sCustDetails><br/><br/>\r\nLink to Item<br/>\r\n<a href=\"<ShowAdvLnk>\"><ShowAdvLnk></a><br/><br/>\r\nContact the <Who> directly to arrange payment and delivery. To avoid fraud, we recommend dealing locally, avoiding Western Union and wire transfers.<br/><br/>\r\nThank you for using our site,<br/>\r\n<sPowDol><br/>\r\nP.S. If you ever need support or have comments for us, e-mail us at <site[\'email\']></div></div>\r\n</body></html>',4,'BuyNow notification letter template for Seller','text','','',NULL),('t_BuyNowS_subject','An item offered by you  has been purchased',4,'','text','','',NULL),('enable_shPhotoActivation','on',23,'Enable auto-activation for gallery photos','checkbox','','',19),('shPhotoLimit','10',23,'Number of gallery photos which can be uploaded by user','digit','','',20),('enable_flash_promo','on',0,'','checkbox','','',NULL),('custom_promo_code','',0,'','text','','',NULL),('license_code','',1,'Dolphin License Code','digit','','',0),('enable_get_boonex_id','on',1,'Enable BoonEx ID import','checkbox','','',0.1),('enable_dolphin_footer','on',0,'enable boonex footers','checkbox','','',NULL),('enable_orca_footer','on',0,'enable boonex footers','checkbox','','',NULL),('enable_ray_footer','on',0,'enable boonex footers','checkbox','','',NULL),('enable_classifieds_sort','on',3,'Enable Sort in Classifieds','checkbox','','',12),('topmenu_items_perline','0',15,'Number of items per line in top menu. 0 - no breaking.','digit','','',20),('autoApproval_Classifieds','on',3,'Automatic advertisements activation after adding','checkbox','','',13),('number_articles','2',0,'Number of articles displayed on front page','digit','return $arg0 >= 0;','must be equal to or greater than zero.',NULL),('enable_modrewrite','on',26,'Enable friendly profile permalinks','checkbox','','',1),('permalinks_articles','on',26,'Enable friendly articles permalinks','checkbox','','',2),('permalinks_news','on',26,'Enable friendly news permalinks','checkbox','','',3),('permalinks_blogs','on',26,'Enable friendly blogs permalinks','checkbox','','',4),('permalinks_events','on',26,'Enable friendly events permalinks','checkbox','','',5),('permalinks_classifieds','on',26,'Enable friendly classifieds permalinks','checkbox','','',6),('permalinks_gallery_photos','on',26,'Enable friendly gallery photos permalinks','checkbox','','',7),('permalinks_gallery_music','on',26,'Enable friendly gallery music permalinks','checkbox','','',8),('permalinks_gallery_videos','on',26,'Enable friendly gallery videos permalinks','checkbox','','',9),('permalinks_groups','on',26,'Enable friendly groups permalinks','checkbox','','',10),('cupid_last_cron','0',0,'Temporary value when cupid mails checked was runed last time','text','','',NULL),('reg_by_inv_only','',3,'Registration by invitation only (need before Enable affiliate support)','checkbox','','',13),('main_div_width','960px',0,'Width of the main container of the site','digit','','',0),('promoWidth','960',0,'Default Width of the Promo Images for resizing','digit','','',0),('ads_gallery_feature','',3,'New Gallery Feature for Classifieds','checkbox','','',NULL),('profile_gallery_feature','',3,'New Gallery Feature for Profile Photos','checkbox','','',NULL),('boonexAffID','',1,'My BoonEx Affiliate ID','digit','','',0.5);
/*!40000 ALTER TABLE `GlParams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GlParamsKateg`
--

DROP TABLE IF EXISTS `GlParamsKateg`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `GlParamsKateg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `menu_order` float default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `GlParamsKateg`
--

LOCK TABLES `GlParamsKateg` WRITE;
/*!40000 ALTER TABLE `GlParamsKateg` DISABLE KEYS */;
INSERT INTO `GlParamsKateg` VALUES (1,'Profiles',1),(2,'Galleries',2),(3,'Other',3),(4,'Emails',4),(5,'Memberships',5),(6,'Postmoderation',6),(7,'Promotions',7),(8,'Notifies',8),(10,'News',10),(11,'Pruning',11),(12,'Matches',12),(15,'Variables',14),(16,'Watermarks',15),(17,'Messages',16),(19,'Meta Tags',18),(20,'Polls',21),(21,'Events',20),(22,'Blogs',9),(23,'Media',22),(24,'Groups',24),(25,'Tags',25);
/*!40000 ALTER TABLE `GlParamsKateg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Groups`
--

DROP TABLE IF EXISTS `Groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Groups` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `categID` int(10) unsigned NOT NULL default '0',
  `Name` varchar(255) NOT NULL default '',
  `Uri` varchar(255) NOT NULL default '',
  `open_join` tinyint(1) NOT NULL default '0',
  `hidden_group` tinyint(1) NOT NULL default '0',
  `members_post_images` tinyint(1) NOT NULL default '0',
  `members_invite` tinyint(1) NOT NULL default '0',
  `Country` varchar(2) NOT NULL default '',
  `City` varchar(64) NOT NULL default '',
  `About` varchar(255) NOT NULL default '',
  `Desc` text NOT NULL,
  `thumb` int(10) unsigned NOT NULL default '0',
  `creatorID` int(10) unsigned NOT NULL default '0',
  `created` date NOT NULL default '0000-00-00',
  `status` enum('Active','Suspended') NOT NULL default 'Active',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Uri` (`Uri`),
  KEY `categID` (`categID`),
  KEY `creatorID` (`creatorID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Groups`
--

LOCK TABLES `Groups` WRITE;
/*!40000 ALTER TABLE `Groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `Groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GroupsCateg`
--

DROP TABLE IF EXISTS `GroupsCateg`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `GroupsCateg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL default '',
  `Uri` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Uri` (`Uri`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `GroupsCateg`
--

LOCK TABLES `GroupsCateg` WRITE;
/*!40000 ALTER TABLE `GroupsCateg` DISABLE KEYS */;
INSERT INTO `GroupsCateg` VALUES (9,'Arts & Literature','Arts-Literature'),(8,'Animals & Pets','Animals-Pets'),(7,'Activities','Activities'),(10,'Automotive','Automotive'),(11,'Business & Money','Business-Money'),(12,'Companies & Co-workers','Companies-Co-workers'),(13,'Cultures & Nations','Cultures-Nations'),(14,'Dolphin Community','Dolphin-Community'),(15,'Family & Friends','Family-Friends'),(16,'Fan Clubs','Fan-Clubs'),(17,'Fashion & Style','Fashion-Style'),(18,'Fitness & Body Building','Fitness-Body-Building'),(19,'Food & Drink','Food-Drink'),(20,'Gay, Lesbian & Bi','Gay-Lesbian-Bi'),(21,'Health & Wellness','Health-Wellness'),(22,'Hobbies & Entertainment','Hobbies-Entertainment'),(23,'Internet & Computers','Internet-Computers'),(24,'Love & Relationships','Love-Relationships'),(25,'Mass Media','Mass-Media'),(26,'Music & Cinema','Music-Cinema'),(27,'Other','Other'),(28,'Places & Travel','Places-Travel'),(29,'Politics','Politics'),(30,'Recreation & Sports','Recreation-Sports'),(31,'Religion','Religion'),(32,'Science & Innovations','Science-Innovations'),(33,'Sex','Sex'),(34,'Teens & Schools','Teens-Schools');
/*!40000 ALTER TABLE `GroupsCateg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GroupsGallery`
--

DROP TABLE IF EXISTS `GroupsGallery`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `GroupsGallery` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `groupID` int(10) unsigned NOT NULL default '0',
  `ext` enum('jpg','gif','png') NOT NULL default 'jpg',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  `width_` int(10) unsigned NOT NULL default '0',
  `height_` int(10) unsigned NOT NULL default '0',
  `by` int(10) unsigned NOT NULL default '0',
  `seed` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `groupID` (`groupID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `GroupsGallery`
--

LOCK TABLES `GroupsGallery` WRITE;
/*!40000 ALTER TABLE `GroupsGallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `GroupsGallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GroupsMembers`
--

DROP TABLE IF EXISTS `GroupsMembers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `GroupsMembers` (
  `memberID` int(10) unsigned NOT NULL default '0',
  `groupID` int(10) unsigned NOT NULL default '0',
  `status` varchar(25) NOT NULL default '',
  `Date` datetime NOT NULL default '0000-00-00 00:00:00',
  KEY `groupID` (`groupID`,`memberID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `GroupsMembers`
--

LOCK TABLES `GroupsMembers` WRITE;
/*!40000 ALTER TABLE `GroupsMembers` DISABLE KEYS */;
/*!40000 ALTER TABLE `GroupsMembers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Guestbook`
--

DROP TABLE IF EXISTS `Guestbook`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Guestbook` (
  `ID` bigint(20) NOT NULL auto_increment,
  `Date` datetime NOT NULL default '0000-00-00 00:00:00',
  `IP` varchar(16) default NULL,
  `Sender` bigint(8) unsigned NOT NULL default '0',
  `Recipient` bigint(8) unsigned NOT NULL default '0',
  `Text` mediumtext NOT NULL,
  `New` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`ID`),
  KEY `Pair` (`Sender`,`Recipient`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Guestbook`
--

LOCK TABLES `Guestbook` WRITE;
/*!40000 ALTER TABLE `Guestbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `Guestbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `HotList`
--

DROP TABLE IF EXISTS `HotList`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `HotList` (
  `ID` bigint(8) NOT NULL default '0',
  `Profile` bigint(8) NOT NULL default '0',
  UNIQUE KEY `HotPair` (`ID`,`Profile`),
  KEY `ID` (`ID`),
  KEY `Profile` (`Profile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `HotList`
--

LOCK TABLES `HotList` WRITE;
/*!40000 ALTER TABLE `HotList` DISABLE KEYS */;
/*!40000 ALTER TABLE `HotList` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `IMessages`
--

DROP TABLE IF EXISTS `IMessages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `IMessages` (
  `IDFrom` bigint(8) NOT NULL default '0',
  `IDTo` bigint(8) NOT NULL default '0',
  `When` datetime NOT NULL default '0000-00-00 00:00:00',
  `Msg` char(255) NOT NULL default '',
  KEY `IDFrom` (`IDFrom`),
  KEY `IDTo` (`IDTo`),
  KEY `IDFrom_2` (`IDFrom`,`IDTo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `IMessages`
--

LOCK TABLES `IMessages` WRITE;
/*!40000 ALTER TABLE `IMessages` DISABLE KEYS */;
/*!40000 ALTER TABLE `IMessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Links`
--

DROP TABLE IF EXISTS `Links`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Links` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `Title` varchar(250) default NULL,
  `URL` varchar(100) NOT NULL default '',
  `Description` mediumtext,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Links`
--

LOCK TABLES `Links` WRITE;
/*!40000 ALTER TABLE `Links` DISABLE KEYS */;
INSERT INTO `Links` VALUES (6,'Free Online Dating Personals - 4ppl','http://www.4ppl.com/','Looking for friends, a match or pen pals? 4PPL is exactly for you. Join the First Absolutely Free dating site without any membership payments or annoying ads. Enjoy its simple, pleasurable design and modern features. Use it for free!'),(8,'Ray Community Widget Suite','http://www.boonex.com/products/ray/','Expand your community site with A/V chat, A/V Instant Messenger, A/V Recorder, MP3 Player, Web Presence and Desktop Application. Bring life to your community site!'),(10,'Unity - the Community of Communitites','http://www.boonex.com/unity/','Our goal is to unite people who can deliver support for any community software and who want to receive qualified support. Here you can sell your products and services, as well as buy everything you want to make a Unique Community website. '),(11,'LoveLandia: Love Poems','http://www.lovelandia.com/','LoveLandia is the best place to share your love poems, love quotes, stories, songs, tips and much more about Love. Just visit our LoveLandia site and enjoy its modern features: chat, A/V Recorder, Forum, \"Transfer to\" and more! Disclose your talent! Impress your Lover!'),(12,'Shark Enterprise Community Platform','http://www.boonex.com/products/shark/','Specially developed software for big community websites. Turn your small community site into a serious moneymaking business!'),(13,'Orca Interactive Forum Script','http://www.boonex.com/products/orca/','The first Interactive Forum Script based on AJAX technology! Self-Ruling, Integrable and under General Public License.'),(15,'Dolphin Smart Community Builder','http://www.boonex.com/products/dolphin/','Dolphin is secure, modifiable and reliably tested Community Software which will help you to build a Unique Community website.'),(17,'DreamSCat: Sharing Ideas & Dreams','http://www.dreamscat.com/','Simply the best place to share and learn genius ideas on science, environment and fun. You can also post your know-how concerning business, beauty and cooking as well as declare your miracles and dreams. Look for creative and useful tips here on self-improvement, health and home.'),(18,'Make Your Car Famous :: AboutMyCar','http://www.aboutmycar.com/','Car stories about real and dream cars with photos, experience and impressions. Latest auto news on new car models, car high tech, motorsports; histories of car builders; vintage automotive proverbs & sayings, auto humor.'),(19,'Boonex Community Software Experts','http://www.boonex.com/','BoonEx delivers quality community web  application software with more features than you ever dreamed of. We offer community software development which responds to rapid changes in Internet-related technologies. You can find everything you need to create your own community!\r\n\r\n'),(22,'BoonEx Blog','http://www.boonex.com/unity/','BoonEx Blog, hosted by Andrey Sivtsov - general Director of BoonEx, is a discussion venue for future releases of BoonEx products and functionality of BoonEx websites. This Unity blog is a part of Unite People movement, supported by BoonEx. Anyone is welcome to participate by sharing ideas, testing products and providing suggestions.'),(21,'BoonEx Development Zone','http://www.boonex.com/trac/','Have a suggestion for the new BoonEx products versions? You are welcome to use BoonEx development zone where you can make your deposit in the Ray, Dolphin, or Orca development process via a \"Ticket\" system. To be up-to-date you can view the \"Timeline\" of the development progress and the \"Roadmap\" of what will be done. Many of those who wish to help build a better product are already there.');
/*!40000 ALTER TABLE `Links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LocalizationCategories`
--

DROP TABLE IF EXISTS `LocalizationCategories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `LocalizationCategories` (
  `ID` tinyint(3) unsigned NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `LocalizationCategories`
--

LOCK TABLES `LocalizationCategories` WRITE;
/*!40000 ALTER TABLE `LocalizationCategories` DISABLE KEYS */;
INSERT INTO `LocalizationCategories` VALUES (1,'Page texts'),(2,'Page titles'),(3,'Action messages'),(4,'Membership'),(5,'Blog'),(6,'Gallery'),(7,'Events'),(8,'Promotional texts'),(9,'Months'),(10,'Age ranges'),(11,'Body type'),(12,'Countries'),(13,'Education'),(14,'Ethnicity'),(15,'Income'),(16,'Language'),(17,'Marital status'),(18,'Person\'s height'),(19,'Profile status'),(20,'Relationship'),(21,'Religion'),(22,'Smoking/drinking levels'),(23,'Zodiac signs'),(24,'Profile fields relevant'),(25,'Instant Messenger'),(26,'Checkout'),(27,'Polls'),(100,'Misc'),(101,'media'),(102,'Groups'),(103,'QSearch'),(105,'Classifieds'),(32,'Profile Fields'),(106,'Comments');
/*!40000 ALTER TABLE `LocalizationCategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LocalizationKeys`
--

DROP TABLE IF EXISTS `LocalizationKeys`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `LocalizationKeys` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `IDCategory` tinyint(3) unsigned NOT NULL default '0',
  `Key` varchar(255) character set utf8 collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Key` (`Key`)
) ENGINE=MyISAM AUTO_INCREMENT=2884 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `LocalizationKeys`
--

LOCK TABLES `LocalizationKeys` WRITE;
/*!40000 ALTER TABLE `LocalizationKeys` DISABLE KEYS */;
INSERT INTO `LocalizationKeys` VALUES (1,8,'_bottom_text'),(2,8,'_copyright'),(70,100,'_About Us'),(72,100,'_Activate account'),(73,100,'_active_story'),(74,100,'_Add comment'),(77,100,'_Add story'),(80,100,'_Add to Hot List'),(81,100,'_Add to Friend List'),(84,100,'_Admin'),(86,100,'_Affiliates'),(87,100,'_Aged from'),(88,100,'_aged'),(89,100,'_all'),(90,100,'_All'),(92,100,'_Anonymous'),(94,9,'_April'),(95,100,'_Articles'),(104,100,'_Back Invite'),(106,100,'_Block'),(107,100,'_Block list'),(108,100,'_block member'),(109,100,'_Blog'),(114,100,'_both2'),(116,100,'_Browse Profiles'),(126,100,'_chars_to_chars'),(127,100,'_Chat'),(130,100,'_chat now'),(139,100,'_Check Out'),(140,100,'_Check all'),(143,24,'_children'),(144,24,'_City'),(153,100,'_contacts'),(158,100,'_Confirm E-mail'),(160,100,'_Confirm password'),(161,100,'_Confirm your e-mail'),(162,100,'_Confirm your password'),(163,100,'_Confirmation code'),(166,100,'_Contact'),(168,100,'_Contacts'),(171,100,'_Contact information sent'),(172,100,'_Contact information not sent'),(175,100,'_Continue'),(176,100,'_Control Panel'),(177,24,'_Country'),(185,100,'_Date'),(187,24,'_DateOfBirth'),(189,9,'_December'),(190,100,'_Delete'),(191,100,'_Delete account'),(192,100,'_Delete from Friend List'),(196,24,'_Description'),(1730,4,'_ACTION_LIMIT_REACHED'),(207,100,'_E-mail'),(211,100,'_E-mail or ID'),(212,100,'_Email confirmation'),(213,100,'_Email confirmation Ex'),(214,100,'_Email was successfully sent'),(215,100,'_Email sent failed'),(218,100,'_Edit Profile'),(220,100,'_Edit'),(224,100,'_Enter profile ID'),(225,100,'_Enter what you see:'),(226,100,'_Error'),(232,100,'_Explanation'),(234,100,'_FAQ'),(235,9,'_February'),(236,100,'_Female'),(238,100,'_Fetch'),(239,100,'_Find'),(242,100,'_First'),(246,100,'_Friend email'),(248,100,'_Friends'),(249,100,'_female'),(251,100,'_featured members'),(258,100,'_Forgot password?'),(261,100,'_From'),(263,100,'_from'),(264,100,'_from zip/postal code'),(265,100,'_from ZIP'),(266,100,'_free'),(270,100,'_General self-description'),(272,100,'_Affiliate Program'),(273,100,'_Congratulation'),(274,100,'_Got_members_part_1'),(275,100,'_Got_members_part_2'),(276,100,'_Need_more_members'),(277,100,'_Choose_membership'),(278,100,'_Got_new_membership_part_1'),(279,100,'_Got_new_membership_part_2'),(280,100,'_Got_new_membership_part_3'),(285,100,'_guestbook'),(290,100,'_my_blog'),(291,100,'_no_info'),(297,100,'_Add record'),(298,100,'_Visitor'),(303,100,'_Header'),(306,100,'_Hide'),(307,100,'_Home'),(310,100,'_Hot list'),(311,100,'_hot member'),(312,100,'_Friend list'),(313,100,'_friend member'),(316,100,'_I am'),(317,100,'_I am a'),(324,24,'_ICQ'),(329,100,'_IM now'),(331,25,'_im_textNoCurrUser'),(334,25,'_im_textLogin'),(336,100,'_ID'),(340,100,'_Incorrect Email'),(344,100,'_Invite a friend'),(345,9,'_January'),(346,100,'_Join'),(349,100,'_Join now'),(350,9,'_June'),(351,9,'_July'),(352,100,'_kilometers'),(354,100,'_Kisses'),(361,100,'_Last'),(362,100,'_Last login'),(366,100,'_latest news'),(369,100,'_Links'),(371,100,'_living within'),(374,100,'_Location'),(375,100,'_Log In'),(378,100,'_Log Out'),(379,100,'_Log Out2'),(391,100,'_Must be valid'),(1729,4,'_ACTION_NOT_ACTIVE'),(400,100,'_Male'),(404,9,'_March'),(413,100,'_male'),(416,100,'_Mark as New'),(417,100,'_Mark as old'),(420,9,'_May'),(423,100,'_Member'),(426,100,'_Member Login'),(428,100,'_Member Profile'),(441,100,'_member info'),(443,100,'_membership'),(444,100,'_Membership2'),(448,100,'_COMPOSE_REJECT_MEMBER_NOT_FOUND'),(449,100,'_Membership NEW'),(450,100,'_days'),(453,100,'_Membership Status'),(457,100,'_Message text'),(458,100,'_Messages'),(461,100,'_miles'),(462,100,'_km'),(464,100,'_More Photos'),(466,100,'_more'),(468,100,'_My Email'),(471,100,'_My Membership'),(474,100,'_My Photos'),(475,100,'_My Profile'),(477,100,'_Name'),(478,100,'_never'),(479,100,'_new'),(480,100,'_New Message'),(489,100,'_Next'),(493,24,'_NickName'),(494,24,'_Nickname'),(497,100,'_No'),(501,100,'_No member specified'),(503,100,'_No messages in Inbox'),(504,100,'_No messages in Outbox'),(506,100,'_No news available'),(507,100,'_No polls available'),(509,100,'_No results found'),(511,100,'_No success story available.'),(521,100,'_Not Recognized'),(524,100,'_Notification send failed'),(527,24,'_Notify by e-mail'),(533,100,'_Online'),(535,100,'_online only'),(536,100,'_Offline'),(544,100,'_Pages'),(545,24,'_Password'),(549,100,'_Password retrieval'),(553,24,'_Phone'),(554,3,'_Photo successfully deleted'),(556,100,'_Picture'),(557,100,'_Polls'),(558,100,'_post my feedback'),(561,100,'_Prev'),(562,100,'_Preview'),(570,100,'_Privacy'),(574,100,'_Profile status'),(575,100,'_Profile NA'),(576,100,'_Profile Not found'),(577,100,'_Profile Not found Ex'),(578,100,'_Profiles'),(580,100,'_Profile activation failed'),(585,100,'_public'),(586,100,'_friends only'),(590,100,'_rate profile'),(594,100,'_Read more'),(595,100,'_Readed'),(596,100,'_Read news in archive'),(599,100,'_Recognized'),(602,100,'_Reject Invite'),(607,100,'_Reply'),(608,100,'_Report about spam was sent'),(609,100,'_Report about spam failed to sent'),(611,100,'_Results per page'),(612,100,'_Results'),(614,100,'_Retrieve my information'),(616,100,'_Quick Search'),(617,100,'_Save Changes'),(618,100,'_Services'),(619,100,'_services'),(627,100,'_SIMG_ERR'),(628,100,'_Search'),(629,100,'_Search result'),(630,100,'_Search by ID'),(631,100,'_Search by Nickname'),(640,100,'_seeking a'),(641,100,'_Seeking for a'),(657,100,'_Selected messages'),(658,100,'_Send'),(662,100,'_Send kiss'),(663,100,'_Send Kiss'),(665,100,'_Send to communicator'),(666,100,'_Send to e-mail'),(667,100,'_Send virtual kiss'),(668,100,'_Send virtual kiss2'),(669,100,'_Send virtual kiss3'),(670,100,'_Send Letter'),(673,100,'_Set membership'),(674,24,'_Sex'),(679,100,'_Show'),(680,100,'_Show me'),(687,100,'_sorry, i can not define you ip adress. IT\'S TIME TO COME OUT !'),(688,100,'_Sorry, user is OFFLINE'),(693,100,'_Spam report'),(698,100,'_Status'),(700,100,'_Stories2'),(701,100,'_Submit'),(703,100,'_Subscribe'),(704,100,'_Subject'),(705,100,'_Successfully uploaded'),(707,100,'_Suspend account'),(709,100,'_Text'),(710,100,'_Terms_of_use'),(711,100,'_Tell a friend'),(713,3,'_This guestbook disabled by it\'s owner'),(723,100,'_to'),(724,100,'_To'),(735,100,'_Uncheck all'),(736,100,'_Unblock'),(746,100,'_uknown'),(747,100,'_Unregister'),(749,100,'_Upload Photos'),(751,100,'_Upload Video'),(752,100,'_Update story'),(756,3,'_User was added to block list'),(757,3,'_User was added to hot list'),(758,3,'_User was added to friend list'),(759,3,'_User was invited to friend list'),(760,3,'_already_in_friend_list'),(761,3,'_User was added to im'),(762,100,'_Video'),(766,100,'_View profile'),(767,100,'_View Profile'),(768,100,'_view as profile details'),(769,100,'_view as photo gallery'),(775,100,'_Vote accepted'),(776,100,'_votes'),(1728,4,'_ACTION_NOT_ALLOWED'),(783,100,'_With photos only'),(785,100,'_within'),(794,100,'_XX match'),(795,100,'_y/o'),(797,100,'_Yes'),(801,100,'_You are'),(802,100,'_You already voted'),(803,100,'_Your email'),(804,100,'_You have to wait for PERIOD minutes before you can write another message!'),(805,100,'_Your name'),(812,2,'_ABOUT_US_H'),(813,2,'_ABOUT_US_H1'),(814,2,'_ACTIVATION_EMAIL_H'),(815,2,'_ACTIVATION_EMAIL_H1'),(816,2,'_AFFILIATES_H1'),(817,2,'_AFFILIATES_H'),(820,2,'_ARTICLES_H'),(821,2,'_ARTICLES_H1'),(826,2,'_CHANGE_STATUS_H'),(827,2,'_CHANGE_STATUS_H1'),(830,2,'_COMPOSE_H'),(831,2,'_COMPOSE_H1'),(832,2,'_COMPOSE_STORY_H'),(833,2,'_COMPOSE_STORY_H1'),(835,2,'_COMPOSE_STORY_VIEW_H'),(836,2,'_COMPOSE_STORY_VIEW_H1'),(839,2,'_CONTACT_H'),(840,2,'_CONTACT_H1'),(842,2,'_EXPLANATION_H'),(843,2,'_FAQ_H'),(844,2,'_FAQ_H1'),(848,2,'_FREEMAIL_H'),(849,2,'_HOTORNOT_H'),(850,2,'_HOTORNOT_H1'),(851,2,'_INBOX_H'),(852,2,'_INBOX_H1'),(859,2,'_JOIN_H'),(860,2,'_JOIN_AFF_H'),(861,2,'_LINKS_H'),(862,2,'_LINKS_H1'),(866,2,'_MEMBERSHIP_H'),(867,2,'_MEMBERSHIP_H1'),(868,2,'_NEWS_H'),(869,2,'_OUTBOX_H'),(870,2,'_OUTBOX_H1'),(872,2,'_OUR_SERV'),(873,2,'_PRIVACY_H'),(874,2,'_PRIVACY_H1'),(878,2,'_PIC_GALLERY_H1'),(883,2,'_RESULT0_H'),(884,2,'_RESULT-1_H'),(885,2,'_RESULT1_H'),(888,2,'_SEARCH_RESULT_H'),(891,2,'_STORY_VIEW_H1'),(892,2,'_STORY_VIEW_H'),(893,2,'_TERMS_OF_USE_H'),(894,2,'_TERMS_OF_USE_H1'),(899,1,'_ABOUT_US'),(902,1,'_ADM_PROFILE_SEND_MSG'),(906,1,'_AFFILIATES'),(907,3,'_ALREADY_ACTIVATED'),(909,1,'_ATT_UNCONFIRMED_E'),(910,1,'_ATT_UNCONFIRMED'),(911,1,'_ATT_APPROVAL'),(912,1,'_ATT_APPROVAL_E'),(913,1,'_ATT_ACTIVE'),(914,1,'_ATT_ACTIVE_E'),(917,1,'_ATT_REJECTED'),(918,1,'_ATT_REJECTED_E'),(919,1,'_ATT_SUSPENDED'),(920,1,'_ATT_SUSPENDED_E'),(921,1,'_ATT_MESSAGE'),(923,1,'_ATT_VKISS'),(924,1,'_ATT_FRIEND'),(936,1,'_CONTACT'),(939,3,'_DELETE_SUCCESS'),(940,1,'_DELETE_TEXT'),(942,3,'_PWD_INVALID2'),(948,3,'_EMAIL_CONF_FAILED_EX'),(949,3,'_EMAIL_CONF_NOT_SENT'),(950,3,'_EMAIL_CONF_SENT'),(951,3,'_EMAIL_CONF_SUCCEEDED'),(953,3,'_EMAIL_INVALID_AFF'),(956,1,'_ENTER_CONF_CODE'),(960,3,'_FAILED_TO_DELETE_PIC'),(962,3,'_FAILED_TO_SEND_MESSAGE'),(964,3,'_FAILED_TO_SEND_MESSAGE_BLOCK'),(967,3,'_FAILED_TO_SEND_MESSAGE_NOT_ACTIVE'),(969,3,'_FAILED_TO_UPLOAD_PIC'),(970,1,'_FAQ_INFO'),(972,1,'_FORGOT'),(973,3,'_FREEMAIL_ALREADY_SENT'),(975,3,'_FREEMAIL_BLOCK'),(976,3,'_FREEMAIL_ERROR'),(977,3,'_FREEMAIL_NOT_ALLOWED'),(978,3,'_FREEMAIL_NOT_KISSED'),(980,3,'_FREEMAIL_SENT'),(983,3,'_INCORRECT_EMAIL'),(988,3,'_INVALID_PASSWD'),(990,1,'_JOIN1_AFF'),(993,1,'_JOIN_AFF2'),(994,1,'_JOIN_AFF_ID'),(998,3,'_LOGIN_ERROR'),(999,3,'_LOGIN_OBSOLETE'),(1002,1,'_LOGIN_REQUIRED_AE1'),(1003,1,'_LOGIN_REQUIRED_AE2'),(1009,1,'_MEMBER_NOT_RECOGNIZED'),(1011,1,'_MEMBER_RECOGNIZED_MAIL_NOT_SENT'),(1012,1,'_MEMBER_RECOGNIZED_MAIL_SENT'),(1018,1,'_MEMBERS_YOU_KISSED'),(1019,1,'_MEMBERS_YOU_KISSED_BY'),(1020,1,'_MEMBERS_YOU_VIEWED'),(1021,1,'_MEMBERS_YOU_VIEWED_BY'),(1022,1,'_MEMBERS_YOU_HOTLISTED'),(1023,1,'_MEMBERS_YOU_HOTLISTED_BY'),(1024,1,'_MEMBERS_INVITE_YOU_FRIENDLIST'),(1025,1,'_MEMBERS_YOU_INVITED_FRIENDLIST'),(1026,1,'_MEMBERS_YOU_BLOCKLISTED'),(1027,1,'_MEMBERS_YOU_BLOCKLISTED_BY'),(1040,1,'_MEMBERSHIP_BUY_MORE_DAYS'),(1041,1,'_MEMBERSHIP_EXPIRES_IN_DAYS'),(1042,1,'_MEMBERSHIP_EXPIRES_NEVER'),(1043,1,'_MEMBERSHIP_EXPIRES_TODAY'),(1044,1,'_VIEW_MEMBERSHIP_ACTIONS'),(1732,4,'_ACTION_NOT_ALLOWED_AFTER'),(1055,1,'_MEMBERSHIP_STANDARD'),(1056,1,'_MEMBERSHIP_UPGRADE_FROM_STANDARD'),(1059,3,'_MESSAGE_SENT'),(1066,3,'_NICK_LEAST2'),(1070,1,'_NO_LINKS'),(1073,1,'_NO_NEED_TO_CONFIRM_EMAIL'),(1074,1,'_NO_RESULTS'),(1075,1,'_NO_STORIES'),(1076,1,'_NOT_RECOGNIZED'),(1090,1,'_PROFILE_CAN_ACTIVATE'),(1091,1,'_PROFILE_CAN_SUSPEND'),(1092,1,'_PROFILE_CANT_ACTIVATE/SUSPEND'),(1093,1,'_PROFILE_NOT_AVAILABLE'),(1096,1,'_PRIVACY'),(1099,3,'_PROFILE_ERR'),(1100,3,'_RECOGNIZED'),(1107,3,'_RESULT0'),(1110,3,'_RESULT-1'),(1111,3,'_RESULT-1_A'),(1112,3,'_RESULT-1_D'),(1113,3,'_RESULT1000'),(1115,3,'_RESULT1_DESC'),(1116,3,'_RESULT1_THANK'),(1118,3,'_RESULT2DESC'),(1121,1,'_SEND_MESSAGE'),(1122,1,'_SEND_MSG_TO'),(1123,1,'_SERV_DESC'),(1127,3,'_STORY_ADDED'),(1128,3,'_STORY_ADDED_FAILED'),(1129,3,'_STORY_UPDATED'),(1130,3,'_STORY_UPDATED_FAILED'),(1133,3,'_STORY_EMPTY_HEADER'),(1134,1,'_SUBSCRIBE_TEXT'),(1136,1,'_TELLAFRIEND'),(1137,1,'_TELLAFRIEND2'),(1139,1,'_TERMS_OF_USE'),(1731,4,'_ACTION_NOT_ALLOWED_BEFORE'),(1153,1,'_YOUR PROFILE_IS_NOT_ACTIVE'),(1154,1,'_YOUR_EMAIL_HERE'),(1157,3,'_VKISS_OK'),(1158,3,'_VKISS_BAD'),(1733,4,'_ACTION_EVERY_PERIOD'),(1162,3,'_VKISS_BAD_COUSE_A3'),(1164,3,'_VKISS_BAD_COUSE_B'),(1165,3,'_VKISS_BAD_COUSE_X'),(1166,3,'_VKISS_BAD_COUSE_Y'),(1167,3,'_VKISS_BAD_COUSE_C'),(1186,7,'_sdating_h'),(1242,6,'_Back'),(1264,6,'_ERROR_WHILE_PROCESSING'),(1274,6,'_Failed to apply changes'),(1296,5,'_comments'),(1298,11,'__Average'),(1299,11,'__Ample'),(1300,11,'__Athletic'),(1301,11,'__Cuddly'),(1302,11,'__Slim'),(1303,11,'__Very Cuddly'),(1304,12,'__Afghanistan'),(1305,12,'__Albania'),(1306,12,'__Algeria'),(1307,12,'__American Samoa'),(1308,12,'__Andorra'),(1309,12,'__Angola'),(1310,12,'__Anguilla'),(1311,12,'__Antarctica'),(1312,12,'__Antigua and Barbuda'),(1313,12,'__Argentina'),(1314,12,'__Armenia'),(1315,12,'__Aruba'),(1316,12,'__Australia'),(1317,12,'__Austria'),(1318,12,'__Azerbaijan'),(1320,12,'__Bahrain'),(1321,12,'__Bangladesh'),(1322,12,'__Barbados'),(1323,12,'__Belarus'),(1324,12,'__Belgium'),(1325,12,'__Belize'),(1326,12,'__Benin'),(1327,12,'__Bermuda'),(1328,12,'__Bhutan'),(1329,12,'__Bolivia'),(1331,12,'__Botswana'),(1332,12,'__Bouvet Island'),(1333,12,'__Brazil'),(1336,12,'__Brunei Darussalam'),(1337,12,'__Bulgaria'),(1338,12,'__Burkina Faso'),(1339,12,'__Burundi'),(1340,12,'__Cambodia'),(1341,12,'__Cameroon'),(1342,12,'__Cape Verde'),(1343,12,'__Cayman Islands'),(1345,12,'__Chad'),(1346,12,'__Canada'),(1347,12,'__Chile'),(1348,12,'__China'),(1349,12,'__Christmas Island'),(1351,12,'__Colombia'),(1352,12,'__Comoros'),(1354,12,'__Cook Islands'),(1355,12,'__Costa Rica'),(1357,12,'__Croatia'),(1358,12,'__Cuba'),(1359,12,'__Cyprus'),(1360,12,'__Czech Republic'),(1361,12,'__Denmark'),(1362,12,'__Djibouti'),(1363,12,'__Dominica'),(1364,12,'__Dominican Republic'),(1365,12,'__East Timor'),(1366,12,'__Ecuador'),(1367,12,'__Egypt'),(1368,12,'__El Salvador'),(1369,12,'__Equatorial Guinea'),(1370,12,'__Eritrea'),(1371,12,'__Estonia'),(1372,12,'__Ethiopia'),(1374,12,'__Faroe Islands'),(1375,12,'__Fiji'),(1376,12,'__Finland'),(1377,12,'__France'),(1378,12,'__Gabon'),(1380,12,'__Georgia'),(1381,12,'__Germany'),(1382,12,'__Ghana'),(1383,12,'__Gibraltar'),(1384,12,'__Greece'),(1385,12,'__Greenland'),(1386,12,'__Grenada'),(1387,12,'__Guadeloupe'),(1388,12,'__Guam'),(1389,12,'__Guatemala'),(1390,12,'__Guinea'),(1391,12,'__Guinea-Bissau'),(1392,12,'__Guyana'),(1393,12,'__Haiti'),(1394,12,'__Honduras'),(1396,12,'__Hungary'),(1397,12,'__Iceland'),(1398,12,'__India'),(1399,12,'__Indonesia'),(1400,12,'__Iran'),(1401,12,'__Iraq'),(1402,12,'__Ireland'),(1403,12,'__Israel'),(1404,12,'__Italy'),(1405,12,'__Jamaica'),(1406,12,'__Japan'),(1407,12,'__Jordan'),(1408,12,'__Kazakhstan'),(1409,12,'__Kenya'),(1410,12,'__Kiribati'),(1412,12,'__Kuwait'),(1413,12,'__Kyrgyzstan'),(1415,12,'__Latvia'),(1416,12,'__Lebanon'),(1417,12,'__Lesotho'),(1418,12,'__Liberia'),(1419,12,'__Liechtenstein'),(1420,12,'__Lithuania'),(1421,12,'__Luxembourg'),(1424,12,'__Madagascar'),(1425,12,'__Malawi'),(1426,12,'__Malaysia'),(1427,12,'__Maldives'),(1428,12,'__Mali'),(1429,12,'__Malta'),(1430,12,'__Marshall Islands'),(1431,12,'__Martinique'),(1432,12,'__Mauritania'),(1433,12,'__Mauritius'),(1434,12,'__Mayotte'),(1435,12,'__Mexico'),(1437,12,'__Moldova'),(1438,12,'__Monaco'),(1439,12,'__Mongolia'),(1440,12,'__Montserrat'),(1441,12,'__Morocco'),(1442,12,'__Mozambique'),(1444,12,'__Namibia'),(1445,12,'__Nauru'),(1446,12,'__Nepal'),(1447,12,'__Netherlands'),(1448,12,'__New Caledonia'),(1449,12,'__New Zealand'),(1450,12,'__Nicaragua'),(1451,12,'__Niger'),(1452,12,'__Nigeria'),(1453,12,'__Niue'),(1454,12,'__Norfolk Island'),(1455,12,'__Norway'),(1456,12,'_no data given'),(1457,12,'__Oman'),(1458,12,'__Pakistan'),(1459,12,'__Palau'),(1460,12,'__Panama'),(1461,12,'__Papua New Guinea'),(1462,12,'__Paraguay'),(1463,12,'__Peru'),(1464,12,'__Philippines'),(1466,12,'__Poland'),(1467,12,'__Portugal'),(1468,12,'__Puerto Rico'),(1469,12,'__Qatar'),(1470,12,'__Reunion'),(1471,12,'__Romania'),(1472,12,'__Russia'),(1473,12,'__Rwanda'),(1474,12,'__Saint Lucia'),(1475,12,'__Samoa'),(1476,12,'__San Marino'),(1477,12,'__Saudi Arabia'),(1478,12,'__Senegal'),(1479,12,'__Seychelles'),(1480,12,'__Sierra Leone'),(1481,12,'__Singapore'),(1482,12,'__Slovakia'),(1483,12,'__Solomon Islands'),(1484,12,'__Somalia'),(1485,12,'__South Africa'),(1486,12,'__Spain'),(1487,12,'__Sri Lanka'),(1489,12,'__Sudan'),(1490,12,'__Suriname'),(1491,12,'__Swaziland'),(1492,12,'__Sweden'),(1493,12,'__Switzerland'),(1494,12,'__Syria'),(1495,12,'__Taiwan'),(1496,12,'__Tajikistan'),(1497,12,'__Tanzania'),(1498,12,'__Thailand'),(1499,12,'__Togo'),(1500,12,'__Tokelau'),(1501,12,'__Tonga'),(1502,12,'__Trinidad and Tobago'),(1503,12,'__Tunisia'),(1504,12,'__Turkey'),(1505,12,'__Turkmenistan'),(1506,12,'__Tuvalu'),(1507,12,'__Uganda'),(1508,12,'__Ukraine'),(1509,12,'__United Arab Emirates'),(1510,12,'__United Kingdom'),(1512,12,'__Uruguay'),(1513,12,'__Uzbekistan'),(1514,12,'__Vanuatu'),(1516,12,'__Venezuela'),(1518,12,'__Virgin Islands'),(1519,12,'__Western Sahara'),(1520,12,'__Yemen'),(1521,12,'__Yugoslavia'),(1523,12,'__Zambia'),(1524,12,'__Zimbabwe'),(1810,12,'__Netherlands Antilles'),(1811,12,'__Bosnia and Herzegovina'),(1812,12,'__The Bahamas'),(1813,12,'__Cocos (Keeling) Islands'),(1814,12,'__Congo, Democratic Republic of the'),(1815,12,'__Central African Republic'),(1816,12,'__Congo, Republic of the'),(1817,12,'__Cote d\'Ivoire'),(1818,12,'__Falkland Islands (Islas Malvinas)'),(1819,12,'__Micronesia, Federated States of'),(1820,12,'__French Guiana'),(1821,12,'__The Gambia'),(1822,12,'__South Georgia and the South Sandwich Islands'),(1823,12,'__Hong Kong (SAR)'),(1824,12,'__Heard Island and McDonald Islands'),(1825,12,'__British Indian Ocean Territory'),(1826,12,'__Saint Kitts and Nevis'),(1827,12,'__Korea, North'),(1828,12,'__Korea, South'),(1829,12,'__Laos'),(1830,12,'__Libya'),(1831,12,'__Macedonia, The Former Yugoslav Republic of'),(1832,12,'__Burma'),(1833,12,'__Macao'),(1834,12,'__Northern Mariana Islands'),(1835,12,'__French Polynesia'),(1836,12,'__Saint Pierre and Miquelon'),(1837,12,'__Pitcairn Islands'),(1838,12,'__Palestinian Territory, Occupied'),(1839,12,'__Saint Helena'),(1840,12,'__Slovenia'),(1841,12,'__Svalbard'),(1842,12,'__Sao Tome and Principe'),(1843,12,'__Turks and Caicos Islands'),(1844,12,'__French Southern and Antarctic Lands'),(1845,12,'__United States Minor Outlying Islands'),(1846,12,'__United States'),(1847,12,'__Holy See (Vatican City)'),(1848,12,'__Saint Vincent and the Grenadines'),(1849,12,'__British Virgin Islands'),(1850,12,'__Vietnam'),(1851,12,'__Wallis and Futuna'),(1525,13,'__High School graduate'),(1526,13,'__Some college'),(1527,13,'__College student'),(1528,13,'__AA (2 years college)'),(1529,13,'__BA/BS (4 years college)'),(1530,13,'__Some grad school'),(1531,13,'__Grad school student'),(1532,13,'__MA/MS/MBA'),(1533,13,'__PhD/Post doctorate'),(1534,13,'__JD'),(1535,14,'__African'),(1536,14,'__African American'),(1537,14,'__Asian'),(1538,14,'__Caucasian'),(1539,14,'__East Indian'),(1540,14,'__Hispanic'),(1541,14,'__Indian'),(1542,14,'__Latino'),(1543,14,'__Mediterranean'),(1544,14,'__Middle Eastern'),(1545,14,'__Mixed'),(1553,15,'__$10,000/year and less'),(1554,15,'__$10,000-$30,000/year'),(1555,15,'__$30,000-$50,000/year'),(1556,15,'__$50,000-$70,000/year'),(1557,15,'__$70,000/year and more'),(1558,16,'__English'),(1559,16,'__Afrikaans'),(1560,16,'__Arabic'),(1561,16,'__Bulgarian'),(1562,16,'__Burmese'),(1563,16,'__Cantonese'),(1564,16,'__Croatian'),(1565,16,'__Danish'),(1566,16,'_Database Error'),(1567,16,'__Dutch'),(1568,16,'__Esperanto'),(1569,16,'__Estonian'),(1570,16,'__Finnish'),(1571,16,'__French'),(1572,16,'__German'),(1573,16,'__Greek'),(1574,16,'__Gujrati'),(1575,16,'__Hebrew'),(1576,16,'__Hindi'),(1577,16,'__Hungarian'),(1578,16,'__Icelandic'),(1579,16,'__Indonesian'),(1580,16,'__Italian'),(1581,16,'__Japanese'),(1582,16,'__Korean'),(1583,16,'__Latvian'),(1584,16,'__Lithuanian'),(1585,16,'__Malay'),(1586,16,'__Mandarin'),(1587,16,'__Marathi'),(1588,16,'__Moldovian'),(1589,16,'__Nepalese'),(1590,16,'__Norwegian'),(1591,16,'__Persian'),(1592,16,'__Polish'),(1593,16,'__Portuguese'),(1594,16,'__Punjabi'),(1595,16,'__Romanian'),(1596,16,'__Russian'),(1597,16,'__Serbian'),(1598,16,'__Spanish'),(1599,16,'__Swedish'),(1600,16,'__Tagalog'),(1601,16,'__Taiwanese'),(1602,16,'__Tamil'),(1603,16,'__Telugu'),(1604,16,'__Thai'),(1605,16,'__Tongan'),(1606,16,'__Turkish'),(1607,16,'__Ukrainian'),(1608,16,'__Urdu'),(1609,16,'__Vietnamese'),(1610,16,'__Visayan'),(1611,17,'__Single'),(1612,17,'__Attached'),(1613,17,'__Divorced'),(1614,17,'__Married'),(1615,17,'__Separated'),(1616,17,'__Widow'),(1619,19,'__Active'),(1620,19,'__Suspended'),(1624,19,'_Active'),(1625,19,'_Suspended'),(1645,21,'__Adventist'),(1646,21,'__Agnostic'),(1647,21,'__Atheist'),(1648,21,'__Baptist'),(1649,21,'__Buddhist'),(1650,21,'__Caodaism'),(1651,21,'__Catholic'),(1652,21,'__Christian'),(1653,21,'__Hindu'),(1654,21,'__Iskcon'),(1655,21,'__Jainism'),(1656,21,'__Jewish'),(1657,21,'__Methodist'),(1658,21,'__Mormon'),(1659,21,'__Moslem'),(1660,21,'__Orthodox'),(1661,21,'__Pentecostal'),(1662,21,'__Protestant'),(1663,21,'__Quaker'),(1664,21,'__Scientology'),(1665,21,'__Shinto'),(1666,21,'__Sikhism'),(1667,21,'__Spiritual'),(1668,21,'__Taoism'),(1669,21,'__Wiccan'),(1670,21,'__Other'),(1671,22,'__No'),(1672,22,'__Rarely'),(1673,22,'__Often'),(1674,22,'__Very often'),(1675,4,'_Allowed actions'),(1676,4,'_Action'),(1677,4,'_Times allowed'),(1678,4,'_Period (hours)'),(1679,4,'_Allowed Since'),(1680,4,'_Allowed Until'),(1681,4,'_No actions allowed for this membership'),(1682,4,'_no limit'),(1684,4,'_use chat'),(1686,4,'_view profiles'),(1687,4,'_use forum'),(1688,4,'_make search'),(1689,4,'_rate photos'),(1690,4,'_send messages'),(1691,4,'_view photos'),(1692,4,'_use Ray instant messenger'),(1693,4,'_use Ray video recorder'),(1694,4,'_use Ray chat'),(1695,4,'_use guestbook'),(1696,4,'_view other members\' guestbooks'),(1697,4,'_get other members\' emails'),(1700,100,'_ATT_MESSAGE_NONE'),(1701,100,'_ATT_VKISS_NONE'),(1702,100,'_ATT_FRIEND_NONE'),(1734,2,'_Choose forum'),(1735,100,'_Module_access_error'),(1739,2,'_GETMEM_H'),(1740,2,'_GETMEM_H1'),(1741,100,'_requires_N_members'),(1744,100,'_Click here to change your membership status'),(1745,7,'_SpeedDating events'),(1746,7,'_No events available'),(1747,7,'_SDating photo alt'),(1748,7,'_No photo'),(1749,7,'_Select events to show'),(1750,7,'_Show events by country'),(1751,7,'_Show all events'),(1752,7,'_Show info'),(1753,7,'_Participants'),(1754,7,'_Choose participants you liked'),(1755,7,'_Status message'),(1757,7,'_Place'),(1758,7,'_There are no participants for this event'),(1761,7,'_Event is unavailable'),(1762,7,'_Event start'),(1763,7,'_Event end'),(1764,7,'_Ticket sale start'),(1765,7,'_Ticket sale end'),(1766,7,'_Responsible person'),(1767,7,'_Tickets left'),(1768,7,'_Ticket price'),(1769,7,'_Sale status'),(1770,7,'_Sale finished'),(1771,7,'_Sale not started yet'),(1772,7,'_No tickets left'),(1773,7,'_Event started'),(1774,7,'_Event finished'),(1775,7,'_You are participant of event'),(1776,7,'_You can buy the ticket'),(1777,7,'_Buy ticket'),(1778,7,'_Change'),(1779,7,'_Cant change participant UID'),(1780,7,'_UID already exists'),(1781,7,'_RESULT_SDATING_MAIL_NOT_SENT'),(1782,7,'_Event participants'),(1783,7,'_Event UID'),(1788,7,'_Show calendar'),(1789,7,'_Calendar'),(1790,7,'_Sunday_short'),(1791,7,'_Monday_short'),(1792,7,'_Tuesday_short'),(1793,7,'_Wednesday_short'),(1794,7,'_Thursday_short'),(1795,7,'_Friday_short'),(1796,7,'_Saturday_short'),(1798,100,'_Invalid module type selected.'),(1799,100,'_Module directory was not set. Module must be re-configurated'),(1800,100,'_Select module type'),(1801,100,'_Please login before using Ray chat'),(1803,100,'_No modules of this type installed'),(1804,100,'_Module selection'),(1806,100,'_Choose module type'),(1807,100,'_Module type selection'),(1808,100,'_No modules found'),(1809,100,'_Ray is not enabled. Select <link> another module'),(1852,2,'_CHECKOUT_H'),(1853,26,'_Membership purchase'),(1854,26,'_SpeedDating ticket purchase'),(1856,26,'_Profiles purchase'),(1857,26,'_Payment description'),(1858,26,'_Payment amount'),(1859,26,'_Possible subscription period'),(1860,26,'_Payment info'),(1861,26,'_Payment methods'),(1864,26,'_recurring payment'),(1865,26,'_recurring not supported'),(1866,26,'_recurring not allowed'),(1867,4,'_Lifetime'),(1869,26,'_Subscriptions'),(1870,26,'_Start date'),(1871,26,'_Period'),(1872,26,'_Charges number'),(1873,26,'_Cancel'),(1874,26,'_Subscription cancellation request was successfully sent'),(1875,26,'_Fail to sent subscription cancellation request'),(1876,3,'_message_subject'),(1877,100,'_Customize Profile'),(1878,100,'_Background color'),(1879,100,'_Background picture'),(1880,100,'_Font color'),(1881,100,'_Font size'),(1882,100,'_Font family'),(1883,26,'_Credit card number'),(1884,26,'_Expiration date'),(1885,3,'_no_messages_from'),(1886,3,'_no_messages_to'),(1887,3,'_messages_to'),(1888,3,'_messages_from'),(1889,100,'_Reset'),(1890,100,'_Customize'),(1891,3,'_no_top_week'),(1892,3,'_no_top_month'),(1896,1,'_powered_by_Dolphin'),(1904,3,'_not_a_member'),(1910,3,'_to_compose_new_message'),(1914,3,'_profile_comments'),(1915,7,'_Add new event'),(1916,7,'_Title'),(1917,7,'_Venue photo'),(1918,7,'_Female ticket count'),(1919,7,'_Male ticket count'),(1921,7,'_Please fill up all fields'),(1930,27,'_poll created'),(1931,27,'_max_poll_reached'),(1932,27,'_controls'),(1933,27,'_are you sure?'),(1934,27,'_no poll'),(1935,27,'_question'),(1936,27,'_answer variants'),(1937,27,'_add answer'),(1938,27,'_generate poll'),(1939,27,'_create poll'),(1943,27,'_No profile polls available.'),(1945,27,'_delete'),(1947,27,'_loading ...'),(1948,27,'_poll successfully deleted'),(1949,27,'_make it'),(1950,4,'_use gallery'),(1951,4,'_view other members\' galleries'),(1954,100,'_Recipient'),(1955,3,'__All'),(1968,3,'_forgot_your_password'),(1971,3,'_photos'),(1972,3,'_contact_us'),(1974,3,'_Random'),(1975,3,'_Latest'),(1984,101,'_day(s)'),(1985,101,'_hour(s)'),(1986,101,'_minute(s)'),(2004,5,'_please_fill_next_fields_first'),(2005,5,'_please_select'),(2006,5,'_associated_image'),(2007,5,'_post_comment_per'),(2008,5,'_post_read_per'),(2011,5,'_category_description'),(2012,5,'_category_caption'),(2014,5,'_add_category'),(2015,3,'_Members_blog'),(2016,3,'_edit_category'),(2017,5,'_characters_left'),(2019,5,'_this_blog_only_for_friends'),(2020,5,'_commenting_this_blog_allowed_only_for_friends'),(2021,5,'_you_have_no_permiss_to_edit'),(2026,5,'_category_deleted'),(2027,3,'_category_delete_failed'),(2028,5,'_category_successfully_added'),(2029,5,'_failed_to_add_category'),(2030,3,'_changes_successfully_applied'),(2032,5,'_comment_added_successfully'),(2033,5,'_failed_to_add_comment'),(2045,100,'_RayPresence'),(2047,5,'_use Blog'),(2052,3,'_help'),(2060,3,'_title_min_lenght'),(2065,3,'_add_new'),(2066,101,'_there_is_no_photo_that_you_can_rate'),(2067,3,'_ratio'),(2070,101,'_download'),(2071,101,'_UPLOAD_MEDIA'),(2075,101,'_MEDIA_GALLERY_H'),(2081,102,'_Showing results:'),(2082,102,'_groups count'),(2083,102,'_Groups'),(2084,102,'_My Groups'),(2085,102,'_Group not found'),(2086,102,'_Group not found_desc'),(2087,102,'_Group is hidden'),(2088,102,'_Sorry, group is hidden'),(2089,102,'_Category'),(2090,102,'_Created'),(2091,102,'_Members count'),(2092,102,'_Group creator'),(2093,102,'_About group'),(2094,102,'_Group type'),(2095,102,'_Public group'),(2096,102,'_Private group'),(2097,102,'_Group members'),(2098,102,'_View all members'),(2099,102,'_Edit members'),(2100,102,'_Invite others'),(2101,102,'_Upload image'),(2102,102,'_Post topic'),(2103,102,'_Edit group'),(2104,102,'_Resign group'),(2105,102,'_Join group'),(2106,102,'_Are you sure want to Resign group?'),(2107,102,'_Are you sure want to Join group?'),(2108,102,'_Create Group'),(2109,102,'_Group creation successful'),(2110,102,'_Group creation unknown error'),(2111,102,'_Edit Group'),(2112,102,'_You\'re not creator'),(2113,102,'_Groups Home'),(2114,102,'_Groups categories'),(2115,102,'_Keyword'),(2116,102,'_Advanced search'),(2117,102,'_Group gallery'),(2118,102,'_You cannot view gallery while not a group member'),(2119,102,'_Uploaded by'),(2120,102,'_Set as thumbnail'),(2121,102,'_Are you sure want to delete this image?'),(2122,102,'_Delete image'),(2123,102,'_You cannot view group members while not a group member'),(2124,102,'_group creator'),(2125,102,'_Are you sure want to delete this member?'),(2126,102,'_Delete member'),(2127,102,'_Search Groups'),(2128,102,'_Search by'),(2129,102,'_by group name'),(2130,102,'_by keyword'),(2131,102,'_Any'),(2132,102,'_Sort by'),(2133,102,'_by popular'),(2134,102,'_by newest'),(2135,102,'_Sorry, no groups found'),(2136,102,'_Groups search results'),(2137,102,'_No my groups found'),(2138,102,'_Choose'),(2139,102,'_Open join'),(2140,102,'_Hidden group'),(2141,102,'_Members can post images'),(2142,102,'_Members can invite'),(2143,102,'_Group description'),(2144,102,'_Group name already exists'),(2145,102,'_Name is required'),(2146,102,'_Category is required'),(2147,102,'_Country is required'),(2148,102,'_City is required'),(2149,102,'_About is required'),(2152,102,'_Select file'),(2153,102,'_Group action'),(2154,102,'_Upload to group gallery error'),(2155,102,'_You should specify file'),(2156,102,'_Upload to group gallery'),(2157,102,'_Upload succesfull'),(2158,102,'_You should select correct image file'),(2159,102,'_Upload error'),(2160,102,'_Gallery upload_desc'),(2161,102,'_You cannot upload images because members of this group not allowed to upload images'),(2162,102,'_You cannot upload images because you\'re not group member'),(2163,102,'_Group join error'),(2164,102,'_You\'re already in group'),(2165,102,'_Group join'),(2166,102,'_Congrats. Now you\'re group member'),(2167,102,'_Request sent to the group creator. You will become active group member when he approve you.'),(2168,102,'_Group resign error'),(2169,102,'_You cannot resign the group because you\'re creator'),(2170,102,'_Group resign'),(2171,102,'_You succesfully resigned from group'),(2172,102,'_You cannot resign the group because you\'re not group member'),(2173,102,'_Group thumnail set'),(2174,102,'_You cannot set group thumnail because you are not group creator'),(2175,102,'_Group image delete'),(2176,102,'_You cannot delete image because you are not group creator'),(2177,102,'_Group member delete error'),(2178,102,'_You cannot delete yourself from group because you are group creator'),(2179,102,'_You cannot delete group member because you are not group creator'),(2180,102,'_Group member approve'),(2181,102,'_Member succesfully approved'),(2182,102,'_Group member approve error'),(2183,102,'_Some error occured'),(2184,102,'_You cannot approve group member because you are not group creator'),(2185,102,'_Group member reject'),(2186,102,'_Member succesfully rejected'),(2187,102,'_Group member reject error'),(2188,102,'_You cannot reject group member because you are not group creator'),(2189,102,'_Group action error'),(2190,102,'_Unknown group action'),(2191,102,'_Group name'),(2192,102,'_Please select at least one search parameter'),(2193,102,'_Group invite_desc'),(2194,102,'_Sorry, no members are found'),(2195,102,'_Back to group'),(2197,102,'_Groups help'),(2198,102,'_Groups help_1'),(2199,102,'_Groups help_2'),(2200,102,'_close window'),(2201,102,'_Groups help_4'),(2202,102,'_Groups help_3'),(2203,102,'_Groups help_5'),(2204,102,'_Groups help_6'),(2205,102,'_Groups help_7'),(2206,102,'_Group invite'),(2207,102,'_Your friends'),(2208,102,'_Invite list'),(2209,102,'_Add ->'),(2210,102,'_<- Remove'),(2211,102,'_Find more...'),(2212,102,'_Send invites'),(2213,102,'_Invites succesfully sent'),(2214,102,'_You should specify at least one member'),(2215,102,'_Group invite accept'),(2216,102,'_You succesfully accepted group invite'),(2217,102,'_Group invite accept error'),(2218,102,'_You cannot accept group invite'),(2219,102,'_Group invite reject'),(2220,102,'_You succesfully rejected group invite'),(2221,103,'_Quick Search Members'),(2222,103,'_Enter search parameters'),(2225,103,'_Quick search results'),(2224,103,'_Enter member NickName or ID'),(2226,103,'_Add member'),(2227,102,'_Post a new topic'),(2228,102,'_Group forum'),(2229,102,'_View all topics'),(2230,3,'_Hello member'),(2231,3,'_Top'),(2233,3,'_My account'),(2234,3,'_Submitted by'),(2235,100,'_Members'),(2236,100,'_News'),(2237,3,'_Next page'),(2238,3,'_Previous page'),(2239,3,'_Group is suspended'),(2240,3,'_Sorry, group is suspended'),(2241,3,'_Group status'),(2242,3,'_Groups help_8'),(2244,3,'_Tags'),(2245,102,'_You must be active member to create groups'),(2248,3,'_No blogs available'),(2249,3,'_Blogs'),(2250,5,'_By Author'),(2251,5,'_in Category'),(2252,3,'_comments N'),(2254,3,'_Videos'),(2255,3,'_Forums'),(2256,3,'_N times'),(2257,2,'_My Account'),(2258,2,'_My Mail'),(2259,2,'_Inbox'),(2260,2,'_Sent'),(2261,2,'_Write'),(2262,2,'_I Blocked'),(2263,2,'_Blocked Me'),(2266,2,'_My Videos'),(2268,2,'_My Events'),(2269,2,'_My Blog'),(2270,2,'_My Polls'),(2271,2,'_My Guestbook'),(2274,2,'_My Friends'),(2281,2,'_Photos'),(2287,2,'_Add Category'),(2288,2,'_New Post'),(2290,2,'_Add Post'),(2300,2,'_Send Message'),(2304,2,'_Get E-mail'),(2308,2,'_Actions'),(2331,3,'_Site Polls'),(2315,3,'_Members Polls H1'),(2316,3,'_Members Polls H'),(2317,3,'_Member Poll H1'),(2318,3,'_Member Poll H'),(2322,3,'_Previous rated'),(2324,3,'_Top Photos'),(2326,3,'_My Contacts'),(2328,3,'_Poll not available'),(2329,3,'_Flag'),(2330,3,'_Click to sort'),(2332,2,'_Simple Search'),(2333,2,'_Advanced Search'),(2334,3,'_Site Poll'),(2335,2,'_Top Groups'),(2336,3,'_All Blogs'),(2337,3,'_No members found here'),(2340,3,'_Bookmark'),(2341,3,'_or'),(2342,3,'_Classifieds'),(2344,3,'_Events'),(2345,3,'_Feedback'),(2347,3,'_Sorry, you\'re already joined'),(2354,105,'_CLASSIFIEDS_VIEW_H'),(2355,105,'_CLASSIFIEDS_VIEW_H1'),(2357,105,'_Browse All Ads'),(2358,105,'_My Classifieds'),(2359,105,'_Browse My Ads'),(2360,105,'_PostAd'),(2362,105,'_Categories'),(2363,105,'_Keywords'),(2364,105,'_Posted by'),(2365,105,'_Details'),(2366,105,'_AdminArea'),(2367,105,'_My Advertisements'),(2368,105,'_Life Time'),(2369,105,'_Message'),(2370,105,'_Pictures'),(2371,105,'_Send these files'),(2372,105,'_Add file field'),(2373,105,'_Filtered'),(2374,105,'_Listing'),(2375,105,'_out'),(2376,105,'_of'),(2377,105,'_SubCategories'),(2379,105,'_Add'),(2380,105,'_Add this'),(2381,105,'_Desctiption'),(2382,105,'_CustomField1'),(2383,105,'_CustomField2'),(2384,105,'_Apply'),(2385,105,'_Activate'),(2387,105,'_Return Back'),(2389,105,'_equal'),(2390,105,'_bigger'),(2391,105,'_smaller'),(2392,105,'_FAILED_RUN_SQL'),(2393,105,'_WARNING_MAX_LIVE_DAYS'),(2394,105,'_WARNING_MAX_SIZE_FILE'),(2395,105,'_SUCC_ADD_ADV'),(2396,105,'_FAIL_ADD_ADV'),(2397,105,'_SUCC_DEL_ADV'),(2398,105,'_FAIL_DEL_ADV'),(2399,105,'_TREE_C_BRW'),(2400,105,'_MODERATING'),(2401,105,'_SUCC_ACT_ADV'),(2402,105,'_FAIL_ACT_ADV'),(2403,105,'_SUCC_UPD_ADV'),(2404,105,'_FAIL_UPD_ADV'),(2405,105,'_Filter'),(2406,105,'_choose'),(2407,105,'_Are you sure'),(2408,105,'_Apply Changes'),(2409,105,'_Offer Details'),(2410,3,'_USER_CONF_SUCCEEDED'),(2411,3,'_USER_ACTIVATION_SUCCEEDED'),(2412,105,'_wholesale'),(2413,105,'_CLS_BUYMSG_1'),(2414,105,'_CLS_BUY_DET1'),(2415,105,'_CLS_BUYMSG_2'),(2416,105,'_SUCC_ADD_COMM'),(2417,105,'_FAIL_ADD_COMM'),(2418,105,'_LeaveComment'),(2419,105,'_Post Comment'),(2420,105,'_Unit'),(2421,105,'_Users other listing'),(2422,105,'_Subject is required'),(2423,105,'_Message must be 50 symbols at least'),(2424,105,'_Manage classifieds'),(2425,1,'_Befriend'),(2426,1,'_SendLetter'),(2427,1,'_Fave'),(2428,1,'_Share'),(2429,1,'_Report'),(2430,1,'_seconds ago'),(2431,1,'_minutes ago'),(2432,1,'_hours ago'),(2433,1,'_days ago'),(2434,1,'_Info'),(2435,1,'_ProfileMusic'),(2436,1,'_ProfileVideos'),(2437,1,'_ProfilePhotos'),(2438,1,'_ChatNow'),(2439,1,'_Greet'),(2440,105,'_Advertisement'),(2441,105,'_Buy Now'),(2442,3,'_Account Home'),(2443,3,'_My Settings'),(2446,2,'_All Members'),(2447,2,'_All Groups'),(2448,2,'_All Videos'),(2465,101,'_browseVideo'),(2466,101,'_File was added to favorite'),(2467,101,'_File already is favorite'),(2468,101,'_Enter email(s)'),(2469,101,'_view Video'),(2470,101,'_See all videos of this user'),(2474,101,'_Page'),(2475,101,'_Music files'),(2476,101,'_browseMusic'),(2477,101,'_Playbacks'),(2478,101,'_upload Photo'),(2479,2,'_Boards'),(2480,2,'_All Classifieds'),(2481,2,'_Add Classified'),(2482,2,'_Music'),(2483,2,'_All Music'),(2484,2,'_Upload Music'),(2485,2,'_All Photos'),(2486,2,'_Top Blogs'),(2487,2,'_All Events'),(2488,2,'_Add Event'),(2489,2,'_All Polls'),(2490,3,'_ProfileMp3'),(2491,2,'_Guestbook'),(2493,3,'_upload Video'),(2494,3,'_Upload File'),(2495,101,'_Sorry, nothing found'),(2496,101,'_File was uploaded'),(2497,101,'_Added'),(2498,101,'_URL'),(2499,101,'_Embed'),(2500,101,'_Views'),(2501,101,'_Video Info'),(2503,101,'_File info was sent'),(2504,101,'_Latest files from this user'),(2505,101,'_View Comments'),(2506,101,'_upload Music'),(2507,101,'_browsePhoto'),(2508,101,'_Upload failed'),(2509,101,'_Photo Info'),(2510,101,'_view Photo'),(2511,101,'_Music File Info'),(2512,101,'_view Music'),(2514,2,'_My Music'),(2515,3,'_RAY_CHAT'),(2516,1,'_Photo'),(2518,3,'_Make Primary'),(2519,3,'_See all photos of this user'),(2520,1,'_Untitled'),(2521,3,'_Original_Size'),(2522,1,'_Rate'),(2523,2,'_Advertisement Photos'),(2524,2,'_Comments'),(2525,2,'_Users Other Listing'),(2526,2,'_Top Video'),(2527,2,'_Top Music'),(2528,2,'_Profile Photos'),(2529,2,'_Profile Music'),(2530,2,'_Profile Video'),(2531,7,'_You have successfully joined this Event'),(2532,7,'_List'),(2533,7,'_Event'),(2534,7,'_Post Event'),(2535,7,'_By'),(2536,3,'_Please Wait'),(2537,3,'_Vote'),(2538,2,'_My Favorite Photos'),(2539,2,'_My Favorite Videos'),(2540,2,'_My Favorite Music'),(2541,2,'_Music Gallery'),(2542,2,'_Photos Gallery'),(2543,2,'_Video Gallery'),(2544,5,'_Post'),(2545,5,'_Caption'),(2546,5,'_Please, Create a Blog'),(2547,5,'_Create My Blog'),(2548,5,'_Create Blog'),(2549,5,'_Posts'),(2554,3,'_PROFILE Photos'),(2555,5,'_Top Posts'),(2568,2,'_BoonEx News'),(2570,5,'_post_successfully_deleted'),(2571,5,'_failed_to_delete_post'),(2572,5,'_failed_to_add_post'),(2573,5,'_post_successfully_added'),(2574,2,'_Leaders'),(2575,3,'_Day'),(2576,3,'_Month'),(2577,3,'_Week'),(2578,3,'_no_top_day'),(2579,2,'_Hacker String'),(2581,5,'_Write a description for your Blog.'),(2582,5,'_Error Occured'),(2584,3,'_Forum Posts'),(2586,3,'_Get BoonEx ID'),(2587,1,'_Import BoonEx ID'),(2588,3,'_Import'),(2590,1,'_No articles available'),(2591,1,'_Read All Articles'),(2592,1,'_Shared Photos'),(2593,1,'_Shared Videos'),(2594,1,'_Shared Music FIles'),(2595,1,'_This Week'),(2596,1,'_This Month'),(2597,1,'_This Year'),(2598,1,'_Topics'),(2599,1,'_No tags found here'),(2600,3,'_Ads'),(2601,1,'_New Today'),(2602,2,'_Photo Gallery'),(2603,1,'_No classifieds available'),(2604,1,'_No groups available'),(2605,2,'_My Music Gallery'),(2606,2,'_My Photo Gallery'),(2607,2,'_My Video Gallery'),(2608,1,'_Count'),(2609,2,'_Site Stats'),(2610,3,'_I agree'),(2611,3,'_Media upload Agreement'),(2612,3,'_License Agreement'),(2613,2,'_event_deleted'),(2614,24,'_Tags_caption'),(2615,24,'_Tags_desc'),(2616,24,'_Tags_err_msg'),(2617,2,'_Member Friends'),(2618,2,'_Select'),(2619,2,'_Join Now Top'),(2620,5,'_Tag'),(2621,103,'_Sorry, no members found'),(2622,105,'_no posts'),(2623,3,'_PWD_INVALID3'),(2624,3,'_Change Password'),(2625,2,'_SUCC_UPD_POST'),(2626,2,'_FAIL_UPD_POST'),(2627,24,'_DateOfBirth_err_msg'),(2628,3,'_No file'),(2629,3,'_Admin Panel'),(2630,3,'_File upload error'),(2631,4,'_send greetings'),(2632,105,'_AddMainCategory successfully added'),(2633,105,'_Failed to Insert AddMainCategory'),(2634,105,'_AddSubCategory successfully added'),(2635,105,'_Failed to Insert AddSubCategory'),(2636,105,'_DeleteMainCategory was successfully'),(2637,105,'_Failed to DeleteMainCategory'),(2638,105,'_DeleteSubCategory was successfully'),(2639,105,'_Failed to DeleteSubCategory'),(2640,100,'_Add New Article'),(2641,100,'_Category Caption'),(2642,100,'_Articles Deleted Successfully'),(2643,100,'_Articles are not deleted'),(2644,100,'_Category Deleted Successfully'),(2645,100,'_Category are not deleted'),(2646,2,'_Hot or Not'),(2647,100,'_affiliate_system_was_disabled'),(2648,101,'_DescriptionMedia'),(2649,1,'_Mutual Friends'),(2650,3,'_Photo Actions'),(2651,3,'_Notification'),(2652,7,'_You have successfully unsubscribe from Event'),(2653,7,'_Unsubscribe'),(2654,100,'_not_active_story'),(2655,1,'_Profile Videos'),(2656,1,'_My Flags'),(2657,1,'_My Topics'),(2658,5,'_Uncategorized'),(2659,1,'_upload Music (Music Gallery)'),(2660,1,'_upload Photos (Photo Gallery)'),(2661,1,'_upload Video (Video Gallery)'),(2662,1,'_play Music (Music Gallery)'),(2663,1,'_view Photos (Photo Gallery)'),(2664,1,'_play Video (Video Gallery)'),(2665,3,'_PROFILE_CONFIRM'),(2666,32,'_FieldCaption_Profile Type_Join'),(2667,32,'_FieldCaption_Couple_Join'),(2668,32,'_FieldDesc_Couple_Join'),(2669,32,'_FieldCaption_General Info_Join'),(2670,32,'_FieldCaption_NickName_Join'),(2671,32,'_FieldDesc_NickName_Join'),(2672,32,'_FieldError_NickName_Mandatory'),(2673,32,'_FieldError_NickName_Min'),(2674,32,'_FieldError_NickName_Max'),(2675,32,'_FieldError_NickName_Unique'),(2676,32,'_FieldError_NickName_Check'),(2677,32,'_FieldCaption_Email_Join'),(2678,32,'_FieldDesc_Email_Join'),(2679,32,'_FieldError_Email_Mandatory'),(2680,32,'_FieldError_Email_Min'),(2681,32,'_FieldError_Email_Unique'),(2682,32,'_FieldError_Email_Check'),(2683,32,'_FieldCaption_Password_Join'),(2684,32,'_FieldDesc_Password_Join'),(2685,32,'_FieldError_Password_Mandatory'),(2686,32,'_FieldError_Password_Min'),(2687,32,'_FieldError_Password_Max'),(2688,32,'_FieldCaption_Misc Info_Join'),(2689,32,'_FieldCaption_Sex_Join'),(2690,32,'_FieldDesc_Sex_Join'),(2691,32,'_FieldError_Sex_Mandatory'),(2692,32,'_FieldCaption_LookingFor_Join'),(2693,32,'_FieldDesc_LookingFor_Join'),(2694,32,'_FieldCaption_DateOfBirth_Join'),(2695,32,'_FieldDesc_DateOfBirth_Join'),(2696,32,'_FieldError_DateOfBirth_Mandatory'),(2697,32,'_FieldError_DateOfBirth_Min'),(2698,32,'_FieldError_DateOfBirth_Max'),(2699,32,'_FieldCaption_Headline_Join'),(2700,32,'_FieldDesc_Headline_Join'),(2701,32,'_FieldCaption_DescriptionMe_Join'),(2702,32,'_FieldDesc_DescriptionMe_Join'),(2703,32,'_FieldError_DescriptionMe_Mandatory'),(2704,32,'_FieldError_DescriptionMe_Min'),(2705,32,'_FieldCaption_Country_Join'),(2706,32,'_FieldDesc_Country_Join'),(2707,32,'_FieldCaption_City_Join'),(2708,32,'_FieldDesc_City_Join'),(2709,32,'_FieldCaption_Security Image_Join'),(2710,32,'_FieldCaption_Captcha_Join'),(2711,32,'_FieldDesc_Captcha_Join'),(2712,32,'_FieldCaption_Admin Controls_Join'),(2713,32,'_FieldCaption_Description_Join'),(2714,32,'_FieldCaption_zip_Join'),(2715,32,'_FieldDesc_zip_Join'),(2716,32,'_FieldCaption_Tags_Join'),(2717,32,'_FieldDesc_Tags_Join'),(2718,32,'_FieldCaption_General Info_Edit'),(2719,32,'_FieldCaption_NickName_Edit'),(2720,32,'_FieldCaption_Email_Edit'),(2721,32,'_FieldCaption_Sex_Edit'),(2722,32,'_FieldCaption_Password_Edit'),(2723,32,'_FieldDesc_Password_Edit'),(2724,32,'_FieldCaption_Misc Info_Edit'),(2725,32,'_FieldCaption_LookingFor_Edit'),(2726,32,'_FieldCaption_DateOfBirth_Edit'),(2727,32,'_FieldCaption_Headline_Edit'),(2728,32,'_FieldCaption_DescriptionMe_Edit'),(2729,32,'_FieldCaption_Country_Edit'),(2730,32,'_FieldCaption_City_Edit'),(2731,32,'_FieldCaption_Admin Controls_Edit'),(2732,32,'_FieldCaption_Status_Edit'),(2733,32,'_FieldDesc_Status_Edit'),(2734,32,'_FieldCaption_Featured_Edit'),(2735,32,'_FieldDesc_Featured_Edit'),(2736,32,'_FieldCaption_General Info_View'),(2737,32,'_FieldCaption_ID_View'),(2738,32,'_FieldCaption_NickName_View'),(2739,32,'_FieldCaption_Status_View'),(2740,32,'_FieldCaption_Sex_View'),(2741,32,'_FieldCaption_LookingFor_View'),(2742,32,'_FieldCaption_Misc Info_View'),(2743,32,'_FieldCaption_DateOfBirth_View'),(2744,32,'_FieldCaption_Country_View'),(2745,32,'_FieldCaption_City_View'),(2746,32,'_FieldCaption_Description_View'),(2747,32,'_FieldCaption_Headline_View'),(2748,32,'_FieldCaption_DescriptionMe_View'),(2749,32,'_FieldCaption_Admin Controls_View'),(2750,32,'_FieldCaption_Email_View'),(2751,32,'_FieldCaption_DateReg_View'),(2752,32,'_FieldCaption_DateLastLogin_View'),(2753,32,'_FieldCaption_DateLastEdit_View'),(2754,32,'_FieldCaption_General Info_Search'),(2755,32,'_FieldCaption_Couple_Search'),(2756,32,'_FieldCaption_Sex_Search'),(2757,32,'_FieldCaption_DateOfBirth_Search'),(2758,32,'_FieldCaption_Country_Search'),(2759,32,'_FieldCaption_Keyword_Search'),(2760,32,'_FieldCaption_Tags_Search'),(2761,32,'_FieldCaption_Location_Search'),(2763,32,'_First Person'),(2764,32,'_Second Person'),(2765,32,'_Single'),(2766,32,'_Couple'),(2767,32,'_Confirm password descr'),(2768,32,'_Password confirmation failed'),(2769,32,'_First value must be bigger'),(2770,32,'_Captcha check failed'),(2771,32,'_Join failed'),(2772,32,'_Join complete'),(2773,32,'_Select it'),(2774,32,'_Profile not specified'),(2775,32,'_You cannot edit this profile'),(2776,32,'_Profile not found'),(2777,32,'_Couple profile not found'),(2778,32,'_Save profile successful'),(2779,32,'_Cast my vote'),(2780,32,'_LookinMale'),(2781,32,'_LookinFemale'),(2782,32,'_FieldDesc_DateLastEdit_View'),(2783,32,'_FieldDesc_DateLastLogin_View'),(2784,32,'_FieldDesc_ID_View'),(2785,32,'_FieldCaption_Misc Info_Search'),(2786,100,'_enable able to rate'),(2787,100,'_disable able to rate'),(2788,100,'_Remember password'),(2789,100,'_nick_already_in_group'),(2790,100,'_member_banned'),(2791,1,'_x_minute_ago'),(2792,1,'_x_hour_ago'),(2793,1,'_x_day_ago'),(2794,1,'_in_x_minute'),(2795,1,'_in_x_hour'),(2796,1,'_in_x_day'),(2797,1,'_Shoutbox'),(2798,1,'_powered_by'),(2799,1,'_about_BoonEx'),(2800,32,'_FieldCaption_TermsOfUse_Join'),(2801,32,'_You must agree with terms of use'),(2802,106,'_Show <b>N</b>-<u>N</u> of N discussions'),(2803,106,'_There are no comments yet'),(2804,106,'_Error occured'),(2805,106,'_Duplicate vote'),(2806,106,'_No such comment'),(2807,106,'_Are you sure?'),(2808,106,'_buried'),(2809,106,'_toggle'),(2810,106,'_N point'),(2811,106,'_N points'),(2812,106,'_Thumb Up'),(2813,106,'_Thumb Down'),(2814,106,'_Remove'),(2815,106,'_(available for <span>N</span> seconds)'),(2816,106,'_Show N replies'),(2817,106,'_Reply to this comment'),(2818,106,'_Add Your Comment'),(2819,106,'_Submit Comment'),(2820,106,'_Can not delete comments with replies'),(2821,106,'_Access denied'),(2822,1,'_Save'),(2823,1,'_Search by Tag'),(2824,1,'_Approve'),(2825,1,'_Disapprove'),(2826,1,'_Edit Article'),(2827,1,'_Article'),(2828,1,'_Article Title'),(2829,1,'_Select Category'),(2830,1,'_Print As'),(2831,106,'_Hide N replies'),(2832,3,'_Counter'),(2833,1,'_Articles were deleted successfully'),(2834,1,'_Article was deleted successfully'),(2835,1,'_Article was not deleted'),(2836,106,'_Reply to Someone comment'),(2837,3,'_See all music of this user'),(2838,3,'_View All'),(2839,1,'_Photo gallery limit was reached'),(2840,1,'_too_many_files'),(2841,7,'_event_post_wrong_time'),(2842,5,'_view other members\' Blog'),(2843,1,'_Music Actions'),(2844,1,'_Video Actions'),(2845,1,'_Edit event'),(2846,1,'_Write new Message'),(2848,32,'_FieldCaption_Membership_Edit'),(2849,32,'_FieldDesc_Membership_Edit'),(2850,32,'_FieldCaption_Tags_View'),(2851,1,'_use Orca private forums'),(2852,1,'_use Orca public forums'),(2853,1,'_vote'),(2854,1,'_Upload successful'),(2855,32,'_FieldCaption_zip_Edit'),(2856,32,'_FieldDesc_zip_Edit'),(2857,32,'_FieldValues_Unconfirmed'),(2858,32,'_FieldValues_Approval'),(2859,32,'_FieldValues_Active'),(2860,32,'_FieldValues_Rejected'),(2861,32,'_FieldValues_Suspended'),(2862,105,'_SubClassified is required'),(2863,1,'_for'),(2864,1,'_starts immediately'),(2865,100,'_day_of_1'),(2866,100,'_day_of_2'),(2867,100,'_day_of_3'),(2868,100,'_day_of_4'),(2869,100,'_day_of_5'),(2870,100,'_day_of_6'),(2871,100,'_day_of_7'),(2872,100,'_day_of_8'),(2873,100,'_day_of_9'),(2874,100,'_day_of_10'),(2875,100,'_day_of_11'),(2876,100,'_day_of_12'),(2877,100,'_Clear'),(2878,100,'_SubCategory is required'),(2879,100,'_Send eCard'),(2880,100,'_send eCards'),(2881,1,'_Total'),(2882,3,'_Message successfully deleted'),(2883,100,'_Disabled');
/*!40000 ALTER TABLE `LocalizationKeys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LocalizationLanguages`
--

DROP TABLE IF EXISTS `LocalizationLanguages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `LocalizationLanguages` (
  `ID` tinyint(3) unsigned NOT NULL auto_increment,
  `Name` varchar(5) NOT NULL default '',
  `Flag` varchar(2) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `LocalizationLanguages`
--

LOCK TABLES `LocalizationLanguages` WRITE;
/*!40000 ALTER TABLE `LocalizationLanguages` DISABLE KEYS */;
INSERT INTO `LocalizationLanguages` VALUES (1,'en','gb','English');
/*!40000 ALTER TABLE `LocalizationLanguages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LocalizationStringParams`
--

DROP TABLE IF EXISTS `LocalizationStringParams`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `LocalizationStringParams` (
  `IDKey` smallint(5) unsigned NOT NULL default '0',
  `IDParam` tinyint(3) unsigned NOT NULL default '0',
  `Description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`IDKey`,`IDParam`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `LocalizationStringParams`
--

LOCK TABLES `LocalizationStringParams` WRITE;
/*!40000 ALTER TABLE `LocalizationStringParams` DISABLE KEYS */;
INSERT INTO `LocalizationStringParams` VALUES (1,0,'Current year'),(2,0,'Current year'),(121,0,'Your site url'),(126,0,'Minimum chars count'),(126,1,'Maximum chars count'),(299,0,'Children count'),(315,0,'Your site url'),(329,0,'Person\'s nickname'),(465,0,'Person\'s nickname'),(549,0,'Your site title'),(639,0,'Person\'s nickname'),(755,0,'Credits per message'),(794,0,'Match percent'),(795,0,'Person\'s age'),(804,0,'Wait period in minutes'),(813,0,'Your site title'),(820,0,'Your site title'),(827,0,'Your site title'),(844,0,'Your site title'),(861,0,'Your site title'),(862,0,'Your site title'),(865,0,'Your site title'),(887,0,'Your site title'),(889,0,'Search profiles limit'),(906,0,'Your site url'),(910,0,'Explanation window width'),(910,1,'Explanation window height'),(911,0,'Explanation window width'),(911,1,'Explanation window height'),(913,0,'Explanation window width'),(913,1,'Explanation window height'),(917,0,'Explanation window width'),(917,1,'Explanation window height'),(919,0,'Explanation window width'),(919,1,'Explanation window height'),(920,0,'Your site title'),(921,0,'Message ID'),(921,1,'Your site url'),(923,0,'Your site url'),(924,0,'Your site url'),(934,0,'Your site title'),(937,0,'Number of chosen contacts'),(946,0,'Email address'),(946,1,'Person\'s link'),(951,0,'Your site title'),(960,0,'Picture deletion error code'),(969,0,'Upload picture filename'),(969,1,'Picture upload error code'),(972,0,'Your site title'),(973,0,'Person\'s ID'),(978,0,'Person\'s ID'),(980,0,'Person\'s nickname'),(989,0,'Page number'),(993,0,'Your site affiliate url'),(993,1,'Your site url'),(994,0,'Affiliate\'s ID'),(1003,0,'Your site images url'),(1003,1,'Your site url'),(1003,2,'Your site title'),(1005,0,'Maximum message length'),(1007,0,'Person\'s ID'),(1009,0,'Your site title'),(1011,0,'Your site title'),(1012,0,'Your site url'),(1012,1,'Your site title'),(1013,0,'Online members count'),(1038,0,'Credits amount'),(1041,0,'Expire days count'),(1043,0,'Expire time'),(1043,1,'Server time'),(1065,0,'Minimum nickname length'),(1065,1,'Maximum nickname length'),(1066,0,'Minimum nickname length'),(1066,1,'Maximum nickname length'),(1076,0,'Your site title'),(1079,0,'Minimum password length'),(1079,1,'Maximum password length'),(1081,0,'Your site title'),(1094,0,'Your site title'),(1095,0,'Your site title'),(1100,0,'Your site title'),(1116,0,'Your site title'),(1118,0,'Your site title'),(1119,0,'Member\'s ID'),(1126,0,'Your site title'),(1134,0,'Your site title'),(1136,0,'Your site title'),(1137,0,'Your site title'),(1144,0,'Your site url'),(1145,0,'Video files extension'),(1146,0,'Your site title'),(1149,0,'Member\'s nickname'),(1150,0,'Image resize width'),(1150,1,'Image resize height'),(1151,0,'Number of purchased contacts'),(1152,0,'Number of members who purchased your contact info'),(1261,0,'Upload picture filename'),(1700,0,'Your site url'),(1701,0,'Your site url'),(1702,0,'Your site url'),(1728,1,'Membership action name'),(1728,2,'Membership level name'),(1729,7,'Your site email'),(1730,1,'Membership action name'),(1730,2,'Membership level name'),(1730,3,'Membership action limit'),(1731,1,'Membership action name'),(1731,2,'Membership level name'),(1731,6,'Membership level allowed before'),(1732,1,'Membership action name'),(1732,2,'Membership level name'),(1732,5,'Membership level allowed after'),(1733,4,'Membership action period'),(1741,0,'Members count'),(1747,0,'Event title'),(1809,0,'Select module link'),(1868,0,'Number of days'),(1885,0,'Member\'s nickname'),(1886,0,'Member\'s nickname'),(1887,0,'Member\'s nickname'),(1888,0,'Member\'s nickname'),(1896,0,'BoonEx Site URL'),(1897,1,'Join Page'),(1897,0,'Site URL'),(1910,0,'Recipient NickName'),(1910,1,'Recipient ID'),(1910,2,'Site URL'),(1953,0,'member\'s nickname'),(1954,0,'member\'s nickname'),(1961,0,'member\'s nickname'),(1966,0,'member of allowed objects in album'),(1984,0,'number of days'),(1985,0,'number of hours'),(1986,0,'number of minutes'),(1987,0,'number of deleted rows'),(2000,0,'file size'),(2015,0,'member\'s nickname'),(2060,0,'number of characters'),(2065,0,'add new what'),(2081,2,'total'),(2081,1,'to'),(2081,0,'from'),(2082,0,'groups count'),(2109,0,'group home link'),(2230,0,'member NickName'),(2234,0,'member nickname'),(2243,0,'number'),(2250,0,'member id'),(2250,1,'member nickname'),(2252,0,'image url'),(2251,0,'image url'),(2251,1,'category url'),(2256,0,'number'),(2554,0,'member\'s nickname'),(2564,0,'member\'s nickname'),(2585,0,'BoonEx ID URL'),(2611,0,'media type'),(2251,2,'category name'),(2252,1,'number of comments');
/*!40000 ALTER TABLE `LocalizationStringParams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LocalizationStrings`
--

DROP TABLE IF EXISTS `LocalizationStrings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `LocalizationStrings` (
  `IDKey` smallint(5) unsigned NOT NULL default '0',
  `IDLanguage` tinyint(3) unsigned NOT NULL default '0',
  `String` mediumtext NOT NULL,
  PRIMARY KEY  (`IDKey`,`IDLanguage`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `LocalizationStrings`
--

LOCK TABLES `LocalizationStrings` WRITE;
/*!40000 ALTER TABLE `LocalizationStrings` DISABLE KEYS */;
INSERT INTO `LocalizationStrings` VALUES (1,1,'2002-{0}. Product of <a class=\"bottom_text\" href=\"http://www.boonex.com/\">BoonEx Group</a>.'),(2,1,'Copyright &copy; {0} Your Company.'),(70,1,'About us'),(72,1,'Activate account'),(73,1,'Active Story'),(74,1,'Add comment'),(77,1,'Add feedback'),(80,1,'Add to Hot List'),(81,1,'Accept Invite'),(84,1,'Admin'),(86,1,'Affiliates'),(87,1,'Aged from'),(88,1,'aged'),(89,1,'all'),(90,1,'All'),(92,1,'Anonymous'),(94,1,'April'),(95,1,'Articles'),(104,1,'Back Invite'),(106,1,'Block'),(107,1,'Block list'),(108,1,'block member'),(109,1,'Blog'),(114,1,'both'),(116,1,'Browse Profiles'),(126,1,'{0} to {1} characters'),(127,1,'chat'),(130,1,'chat now'),(139,1,'Check Out'),(140,1,'Check all'),(143,1,'children'),(144,1,'City'),(153,1,'contacts'),(158,1,'Confirm E-mail'),(160,1,'Confirm password'),(161,1,'Confirm your e-mail'),(162,1,'Confirm your password'),(163,1,'Confirmation code'),(166,1,'Contact'),(168,1,'Contacts'),(171,1,'Contact information sent'),(172,1,'Contact information not sent'),(175,1,'Continue'),(176,1,'My Account'),(177,1,'Country'),(185,1,'Date'),(187,1,'Age'),(189,1,'December'),(190,1,'Delete'),(191,1,'Delete account'),(192,1,'Delete from Friend List'),(196,1,'Description'),(207,1,'E-mail'),(211,1,'Nickname'),(212,1,'Email confirmation'),(213,1,'E-mail confirmed'),(214,1,'Email was successfully sent'),(215,1,'Email send failed'),(218,1,'Edit Profile'),(220,1,'Edit'),(224,1,'Enter profile ID'),(225,1,'Enter what you see:'),(226,1,'Error'),(232,1,'Explanation'),(234,1,'FAQ'),(235,1,'February'),(236,1,'Female'),(238,1,'Search'),(239,1,'Find'),(242,1,'First'),(246,1,'Friend email'),(248,1,'Friends'),(249,1,'Female'),(251,1,'featured members'),(258,1,'Forgot password?'),(261,1,'From'),(263,1,'from'),(264,1,'from zip/postal code'),(265,1,'from ZIP'),(266,1,'free'),(270,1,'General self-description'),(272,1,'Affiliate Program'),(273,1,'<font color=red>Congratulations!!!</font><br />'),(274,1,'You\'ve got '),(275,1,' member(s) engaged. '),(276,1,'(Need more members engaged to get new membership status )'),(277,1,' You may choose your membership status'),(278,1,'<center>Congratulations!!!<br />You\'re <font color=red>'),(279,1,' </font>member now. Your membership will expire in '),(280,1,' days.</center>'),(285,1,'GuestBook'),(290,1,'My Blog'),(291,1,'No info'),(297,1,'Add record'),(298,1,'Visitor'),(303,1,'Header'),(306,1,'Hide'),(307,1,'Home'),(310,1,'Hot list'),(311,1,'hot member'),(312,1,'Friend list'),(313,1,'friend member'),(316,1,'I am'),(317,1,'I am a'),(324,1,'ICQ'),(329,1,'IM {0} now!'),(331,1,'Please select a user first'),(334,1,'Please login first'),(336,1,'E-Mail or ID'),(340,1,'Incorrect Email'),(344,1,'Invite a friend'),(345,1,'January'),(346,1,'Join'),(349,1,'Join Now'),(350,1,'June'),(351,1,'July'),(352,1,'kilometers'),(354,1,'Greetings'),(361,1,'Last'),(362,1,'Last login'),(366,1,'latest news'),(369,1,'Links'),(371,1,'living within'),(374,1,'Location'),(375,1,'Log in'),(378,1,'log out'),(379,1,'Log Out'),(391,1,'Must be valid'),(400,1,'Male'),(404,1,'March'),(413,1,'Male'),(416,1,'Mark as New'),(417,1,'Mark as read'),(420,1,'May'),(423,1,'Member'),(426,1,'Member Login'),(428,1,'Member Profile'),(441,1,'member info'),(443,1,'Membership'),(444,1,'Membership'),(448,1,'Recipient not found'),(449,1,'Available Membership Types'),(450,1,' days'),(453,1,'Membership Status'),(457,1,'Message text'),(458,1,'Messages'),(461,1,'miles'),(462,1,'km'),(464,1,'more photo(s)'),(466,1,'more'),(468,1,'My Email'),(471,1,'My Membership'),(474,1,'My Photo Gallery'),(475,1,'my profile'),(477,1,'Name'),(478,1,'never'),(479,1,'new'),(480,1,'New message'),(489,1,'Next'),(493,1,'Username'),(494,1,'Username'),(497,1,'No'),(501,1,'No member specified'),(503,1,'No messages in Inbox'),(504,1,'No messages in Outbox'),(506,1,'No news available'),(507,1,'No polls available'),(509,1,'No results found.'),(511,1,'No feedback available.'),(521,1,'Not Recognized'),(524,1,'Notification email send failed'),(527,1,'Notify by e-mail'),(533,1,'Online'),(535,1,'online only'),(536,1,'Offline'),(544,1,'Pages'),(545,1,'Password'),(549,1,'Member password retrieval at {0}'),(553,1,'Phone'),(554,1,'Photo successfully deleted'),(556,1,'Picture'),(557,1,'Polls'),(558,1,'post my feedback'),(561,1,'Prev'),(562,1,'Preview'),(570,1,'Privacy'),(574,1,'Profile status'),(575,1,'Profile not available for view'),(576,1,'Profile has not been found'),(577,1,'Specified profile not found in the database. It must have been removed earlier.'),(578,1,'Profiles'),(580,1,'Profile activation failed.'),(585,1,'public'),(586,1,'friends only'),(590,1,'rate profile'),(594,1,'Read more'),(595,1,'Read'),(596,1,'Read news in archive'),(599,1,'Recognized'),(602,1,'Reject Invite'),(607,1,'Reply'),(608,1,'Report about spam was sent'),(609,1,'Report about spam failed to send'),(611,1,'Results per page'),(612,1,'Results'),(614,1,'Retrieve my information'),(616,1,'Quick Search'),(617,1,'Save Changes'),(618,1,'Services'),(619,1,'services'),(627,1,'Code from security images is incorrect'),(628,1,'Search'),(629,1,'Search result'),(630,1,'Search by ID'),(631,1,'Search by Nickname'),(640,1,'seeking a'),(641,1,'Seeking a'),(657,1,'Selected messages'),(658,1,'Send'),(662,1,'Greeting'),(663,1,'greeting'),(665,1,'to site e-mail'),(666,1,'to personal e-mail'),(667,1,'Send greeting'),(668,1,'Greeting sent'),(669,1,'Greeting NOT sent'),(670,1,'Send Letter'),(673,1,'Set membership'),(674,1,'Sex'),(679,1,'Show'),(680,1,'Show me'),(687,1,'Sorry, I can\'t define your IP address. IT\'S TIME TO COME OUT!'),(688,1,'Sorry, but user is OFFLINE at the moment.\\nPlease try later...'),(693,1,'Report Spam'),(698,1,'Status'),(700,1,'Feedback'),(701,1,'Submit'),(703,1,'Subscribe'),(704,1,'Subject'),(705,1,'Successfully uploaded!'),(707,1,'Suspend account'),(709,1,'Text'),(710,1,'Terms'),(711,1,'Invite a friend'),(713,1,'This guestbook disabled by it\'s owner'),(723,1,'to'),(724,1,'to'),(735,1,'Uncheck all'),(736,1,'Unblock'),(746,1,'unknown'),(747,1,'Unregister'),(749,1,'Upload Photos'),(751,1,'Upload Video'),(752,1,'Update feedback'),(756,1,'User was added to block list'),(757,1,'User was added to hot list'),(758,1,'User was added to friend list'),(759,1,'User was invited to friend list'),(760,1,'This user already in your friend list!'),(761,1,'User was added to the instant messenger'),(762,1,'Video Gallery'),(766,1,'view profile'),(767,1,'view profile'),(768,1,'view as profile details'),(769,1,'view as photo gallery'),(775,1,'Vote accepted'),(776,1,'votes'),(783,1,'with photos only'),(785,1,'within'),(794,1,'{0}% match'),(795,1,'{0} y/o'),(797,1,'Yes'),(801,1,'You are'),(802,1,'You already voted'),(803,1,'Your email'),(804,1,'You have to wait for {0} minute(s) before you can write another message!'),(805,1,'Your name'),(812,1,'About Us'),(813,1,'About us'),(814,1,'Email Confirmation'),(815,1,'Your e-mail confirmation'),(816,1,'Affiliates'),(817,1,'Affiliates'),(820,1,'{0} Articles'),(821,1,'Articles'),(826,1,'Change Account Status'),(827,1,'Suspend/Activate your {0} account'),(830,1,'Compose a new message'),(831,1,'Compose and send a message'),(832,1,'Feedback'),(833,1,'Feedback'),(835,1,'View Feedback'),(836,1,'View Feedback'),(839,1,'Contact us'),(840,1,'Feedback section - questions, comments, regards'),(842,1,'Explanation'),(843,1,'FAQ'),(844,1,'FAQ'),(848,1,'Get contact information for FREE!'),(849,1,'Rate photo'),(850,1,'Rate photo'),(851,1,'My Inbox'),(852,1,'Inbox'),(859,1,'Join'),(860,1,'Affiliate sign up'),(861,1,'{0} Links'),(862,1,'{0} Links'),(866,1,'Membership'),(867,1,'View status/upgrade your membership'),(868,1,'News'),(869,1,'Outbox'),(870,1,'Outbox'),(872,1,'Our services'),(873,1,'Privacy Policy'),(874,1,'Privacy policy'),(878,1,'Photo gallery'),(883,1,'Order failure'),(884,1,'Possible security attack'),(885,1,'Purchase success'),(888,1,'Search Result'),(891,1,'Feedback'),(892,1,'Feedback'),(893,1,'Terms of use'),(894,1,'Terms'),(899,1,'<div class=\"about_us_cont\">\r\n<div class=\"about_us_snippet\">\r\n\r\n<a href=\"http://www.boonex.com/products/dolphin/\">Dolphin Smart Community Builder</a> was developed by <a href=\"http://www.boonex.com/\">BoonEx Community Software Experts</a>.<br><br> \r\n<a href=\"http://www.boonex.com/products/dolphin/\">Dolphin</a> Smart Community Builder is based on aeDating, the most popular dating software on the internet. Since the first Dolphin version was released on May 2006, it has been modernized, supplemented, improved considerably and become an even more popular Community software than the aeDating script was.<br> \r\nIn conformity with the <a href=\"http://www.boonex.com/mission/\">\"Unite People\"</a> mission, BoonEx strongly believes that Community software should be offered free of charge, since the Community unites people of different cultures, nationalities and races.<br><br> \r\n\r\nBoonEx carries out its mission through Dolphin by improving it constantly and releasing at least 4 versions every six months. Thus Dolphin offers you advanced <a href=\"http://www.boonex.com/products/dolphin/features/\">features</a> which Internet users love very much: groups, photo gallery, blog, members articles and much more. Dolphin is also integrated with <a href=\"http://www.boonex.com/products/orca/\">Orca Interactive Forum Script</a> and all the <a href=\"http://www.boonex.com/products/ray/\">Ray Widgets</a>, such as: <a href=\"http://www.boonex.com/products/ray/widgets/im/\">Instant Messenger</a>, <a href=\"http://www.boonex.com/products/ray/widgets/chat/\">Chat</a>, <a href=\"http://www.boonex.com/products/ray/widgets/presence/\">Web Presence</a>, <a href=\"http://www.boonex.com/products/ray/widgets/whiteboard/\">Whiteboard</a>, <a href=\"http://www.boonex.com/products/ray/widgets/mp3/\">Music Player</a>, <a href=\"http://www.boonex.com/products/ray/widgets/recorder/\">Video Recorder</a>, Video Player.<br><br>\r\n \r\nDolphin, as well as other BoonEx products, is supported by the <a href=\"http://www.boonex.com/unity/\">Unity - the Community of Communities</a> system. At Unity you may get a high quality services and plugins to expand you site functionality. Unity is a moderated system so each product is tested by Unity moderators, pundits and administrators. \r\n\r\nIn aspiring to achieve perfection <a href=\"http://www.boonex.com/unity/\">BoonEx Unity</a> system has a special Web Blog where General director Andrey Sivtsov discusses themes concerning the future versions of all BoonEx products with everyone interested.\r\nAll interested persons are welcome to bring their contribution to Dolphin development.\r\n\r\n</div>\r\n</div>'),(902,1,'Message was successfully sent.'),(906,1,'<div class=\"affiliates_cont\">\r\n<div class=\"affiliates_snippet\">\r\nWe offer commissions for webmasters who refer visitors to our site. Go to <a href=\"{0}join_aff.php\">sign up page</a> to become an affilliate.\r\n</div>\r\n</div>'),(907,1,'Your account is already activated. There is no need to do it again.'),(909,1,'You will need to follow the link supplied in the e-mail to get your account submitted for approval. <br />Send me <a target=_blank href=\"activation_email.php\">confirmation e-mail</a>.'),(910,1,'(<a href=\"javascript:void(0);\" onclick=\"javascript:window.open( \'explanation.php?explain=Unconfirmed\', \'\', \'width={0},height={1},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no\' );\">Explanation</a>)'),(911,1,'(<a href=\"javascript:void(0);\" onclick=\"javascript:window.open( \'explanation.php?explain=Approval\', \'\', \'width={0},height={1},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no\' );\">Explanation</a>)'),(912,1,'Your profile activation is in progress. Usually it takes up to 24 hours. Thank you for your patience.'),(913,1,'(<a href=\"javascript:void(0);\" onclick=\"javascript:window.open( \'explanation.php?explain=Active\', \'\', \'width={0},height={1},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no\' );\">Explanation</a>,<a href=\"change_status.php\">Suspend</a>)'),(914,1,'You are a full-featured member of our community. You can however suspend your profile to become temporarily unavailable for others.'),(917,1,'(<a href=\"javascript:void(0);\" onclick=\"javascript:window.open( \'explanation.php?explain=Rejected\', \'\', \'width={0},height={1},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no\' );\">Explanation</a>)'),(918,1,'Your profile was rejected by the system administrator because it contains illegal information or is missing some information. If you have any questions, please, <a target=_blank href=\"contact.php\">contact us</a>, and don\'t forget to specify your profile ID.'),(919,1,'(<a href=\"javascript:void(0);\" onclick=\"javascript:window.open( \'explanation.php?explain=Suspended\', \'\', \'width={0},height={1},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no\' );\">Explanation</a>)'),(920,1,'Your profile is temporarily out of {0} system services. You can activate it <a target=_blank href=\"change_status.php\">here</a>. If you have any questions, please <a target=_blank href=\"contact.php\">contact administrators</a>.'),(921,1,'<b><a href=\"{1}messages_inbox.php?message={0}\">New message</a></b> waiting for you!'),(923,1,'<b><a href=\"{0}contacts.php?show=greet&list=me\">New greeting</a></b> waiting for you!'),(924,1,'<b><a href=\"{0}contacts.php?show=friends_inv&amp;list=me\">New Friend</a></b> waiting for you!'),(936,1,'Your contact information here'),(939,1,'You account was successfully deleted'),(940,1,'Your profile and photos will be deleted. Are you sure you want to delete your account?'),(942,1,'Password must be from 5 to 8 characters long or password confirmation failed.'),(948,1,'This could happen because of improper web links displayed by some web mail services. If you see this message, please try to <u>exactly</u> copy the link supplied with the confirmation e-mail and paste it into your browser\'s address bar <b>or</b> just enter the confirmation code (which also comes with the e-mail) below:'),(949,1,'<b>Mail has NOT been sent.</b><br />Unfortunately we could not send the confirmation e-mail to you at this time. Please, try later. We appologize for any inconvenience. <a href=\"contact.php\">Please report this bug to</a> the administrator</a>.'),(950,1,'<b>Mail has been successfully sent.</b><br />You will receive it within a minute.'),(951,1,'Congratulations! Your e-mail confirmation succeeded.<br /><br />Your account will be activated within 12 hours. Our administrators will personally look through your details to make sure you have set everything correctly. This helps {0} be the most accurate community service in the world. We care about the quality of our profiles and guarantee that every user of our system is real, so if you purchase someone\'s contact information, you can be sure that your money isn\'t wasted.'),(953,1,'E-mail address doesn\'t seem to be valid.'),(956,1,'Enter confirmation code'),(960,1,'Failed to delete picture.<br /><div class=small>(Error code: {0})</div>'),(962,1,'Failed to send message to one or more recipients.'),(964,1,'Failed to send message. You are in the block list of this member'),(967,1,'Failed to send message. Recipient is not an active member.'),(969,1,'Failed to upload file <b>{0}</b>! Make sure it\'s a picture of <b>jpg</b>, <b>gif</b>, or <b>png</b> format.<br /><div class=small>(Error code: {1})</div><br /><br />'),(970,1,'<div class=\"faq_cont\">\r\n<div class=\"faq_header\">\r\nWhere can I download the latest Dolphin version?</div>\r\n<div class=\"faq_snippet\">You can learn more about the latest Dolphin version, its improvements and newly implemented features on the <a href=\"http://www.boonex.com/products/\">BoonEx products</a> page.</div>\r\n</div>\r\n\r\n<div class=\"faq_cont\">\r\n<div class=\"faq_header\">\r\nHow can I test the latest version?</div>\r\n<div class=\"faq_snippet\">The latest versions of all BoonEx products are available for testing at <a href=\"http://www.demozzz.com/\">Demozzz.com</a></div>\r\n</div>\r\n\r\n<div class=\"faq_cont\">\r\n<div class=\"faq_header\">\r\nDo you release beta versions?\r\n</div>\r\n<div class=\"faq_snippet\">\r\nSure! We release several Beta versions and Release Candidates before the final release. All Beta versions of all BoonEx products are available for download at BoonEx Blog.\r\n</div>\r\n</div>\r\n\r\n<div class=\"faq_cont\">\r\n<div class=\"faq_header\">\r\nWhere can I get support services?</div>\r\n<div class=\"faq_snippet\">Dolphin, and other BoonEx products, is supported via <a href=\"http://www.boonex.com/unity/\">Unity</a> system.</div>\r\n</div>\r\n\r\n<div class=\"faq_cont\">\r\n<div class=\"faq_header\">\r\nWhere can I find/order modifications, templates and other plug ins for my Community website powered by Dolphin?</div>\r\n<div class=\"faq_snippet\">All miscellaneous products for Dolphin and other BoonEx products are offered at <a href=\"http://www.boonex.com/unity/\">Unity</a></div>\r\n</div>\r\n\r\n<div class=\"faq_cont\">\r\n<div class=\"faq_header\">\r\nWhat if I have some development skills and can develop modifications or other things for Dolphin?</div>\r\n<div class=\"faq_snippet\">You are welcome to join <a href=\"http://www.boonex.com/unity/\">Unity</a>, where you can register as an expert and offer your products and support services.</div>\r\n</div>\r\n\r\n<div class=\"faq_cont\">\r\n<div class=\"faq_header\">\r\nI have some good ideas for future Dolphin versions</div>\r\n<div class=\"faq_snippet\">You are welcome to discuss your ideas at <a href=\"http://www.boonex.com/unity/\">Unity</a> or <a href=\"http://www.boonex.com/unity/\">TRAC</a> system to contribute to the Dolphin development process.</div>\r\n</div>'),(972,1,'Forgot your ID and/or password? No problem! Please, supply your e-mail address below and you will be sent your {0} account ID and password.'),(973,1,'You already requested !!! this member\'s contact information for free. You can see it in <a href=\"profile.php?ID={0}\">their profile</a>, or in <a href=\"contacts.php\">your communicator</a>.'),(975,1,'Sorry, contact information could not be sent to you. You are in the block list of this member'),(976,1,'Sorry, contact information could not be sent to you at this time. Make sure that:<br /><br /><ul><li>You are logged in;</li><li>Your profile is in active mode.</li></ul><br /><br />Thank you.'),(977,1,'Sorry, this member\'s contact information cannot be received for free. You must purchase it.'),(978,1,'You were not greeted by {0} member.'),(980,1,'You have just been sent an e-mail with {0}\'s contact information.'),(983,1,'The e-mail you entered doesn\'t seem to be valid. Please, try again.'),(988,1,'Sorry, invalid password! Please, try again.'),(990,1,'Go here to become an affiliate of this site for free.<br /><br />'),(993,1,'Congratulations! You are an affiliate of {1} now. You can login <a href=\"{0}\">here</a>'),(994,1,'Use <font color=red><b>{0}</b></font> ID number for login, please do not forget your ID number'),(998,1,'Login error. Try again:'),(999,1,'Your login information seems to be obsolete, please re-login.'),(1002,1,'Sorry, you need to login before you can use this page.'),(1003,1,'If you are not registered at {2} you can do it right now for FREE and get all the advantages our system offers for both free and fee.<br />'),(1009,1,'Sorry, you have not been recognized as a {0} member. Please, make sure that you entered the e-mail you used in creating your account.'),(1011,1,'You have been recognized as a {0} member, but it was impossible to send you an e-mail with your account details right now. Please, try later.'),(1012,1,'You have been recognized as a {1} member and your account details have just been sent to you. Once you receive the letter from us, go <a href=\"{0}member.php\">here</a> and log in.'),(1018,1,'Members you have greeted'),(1019,1,'Members you were greeted by'),(1020,1,'Members you have viewed'),(1021,1,'Members you were viewed by'),(1022,1,'Members you have hotlisted'),(1023,1,'Members you were hotlisted by'),(1024,1,'Members you have invited'),(1025,1,'Members you were invited by'),(1026,1,'Members you have blocked'),(1027,1,'Members you were blocked by'),(1040,1,'extend membership period'),(1041,1,'expires: in {0} day(s)'),(1042,1,'expires: never'),(1043,1,'expires: today at {0}. (Server time: {1})'),(1044,1,'View Allowed Actions'),(1055,1,'You are a standard member.'),(1056,1,'<a href=\"membership.php\">Click here</a> to upgrade.'),(1059,1,'Message has been successfully sent.'),(1066,1,'Nickname must be from {0} to {1} characters long.'),(1070,1,'No links available'),(1073,1,'There is no need to confirm your account e-mail because it\'s already confirmed and you proved your ownership of the e-mail address.'),(1074,1,'<b>No results found.</b> <br /> <a href=\"search.php\">Start again</a> and try to broaden your search.'),(1075,1,'No feedback available'),(1076,1,'You are NOT recognized as a {0} member'),(1090,1,'You can activate your account to make it available again for search and contacts.'),(1091,1,'You can suspend your account to make it temporarily unavailable for search and contact. Later you can always reactivate it.'),(1092,1,'You can not activate or suspend your account because it is not in <b>Active</b> or <b>Suspended</b> status.'),(1093,1,'Profile is not available.'),(1096,1,'<div class=\"privacy_cont\">\r\n<div class=\"privacy_snippet\">\r\nWe are glad to welcome you to <a href=\"http://www.boonex.com/products/dolphin/\">Dolphin Smart Community Builder</a>. Please read this privacy statement to ensure that we are committed to keeping secure the privacy of our members\' (customers) details.<br><br> \r\n<b>What information do we collect?</b><br>\r\nSince <a href=\"http://www.boonex.com/\">BoonEx</a> is providing you the software and support to build a website, we may require from you some information that may be considered as personally identifiable.<br><br>\r\n\r\nPlease provide us with the following information about yourself:\r\n<ul>\r\n<li>nickname</li>\r\n<li>real name</li>\r\n<li>password</li>\r\n<li>e-mail address</li></ul><br>\r\nOther personal information that we may possibly need:\r\nTo be able to render the services you\'ve ordered, we may need information about your website: site URL, FTP, cPanel or SSH accesses.<br><br>\r\n<b>Copyrights</b><br>\r\nAll <a href=\"http://www.boonex.com/\">BoonEx.com</a> site contents copyrights are reserved by BoonEx Ltd. and content copying and duplication are strongly prohibited.<br><br>\r\n<b>Acceptance of agreements</b><br>\r\nBy reading this you agree to our Privacy Statement. If you do not agree to our terms and conditions you may not use this site.\r\nWe may update our Privacy Statement from time to time so please visit this page regularly.\r\n</div>\r\n</div>'),(1099,1,'Profile error. Please, try again.'),(1100,1,'You are recognized as a {0} member'),(1107,1,'<b>The transaction didn\'t proceed.</b> Make sure you have entered your credit card information correctly and try again.'),(1110,1,'<b>Transaction verification failed.</b> You seem to have tried to cheat our security system. Your IP is logged and reported. If you are persistent in your attempts you will be banned from our system services access.'),(1111,1,'Transaction verification failed.'),(1112,1,'You seem to have tried to cheat our security system. Your IP is logged and reported. If you are persistent in your attempts you will be banned from our system services access.'),(1113,1,'You do not have enough credits'),(1115,1,'The e-mail with contact information has been just sent to you.'),(1116,1,'Thank you for your participation in {0} We appreciate your purchase and you will be responded to via email at {1}'),(1118,1,'You have successfully made your purchase at {0}, however the e-mail with contact information could not be sent to you right now. Don\'t worry, if you are our member your purchase has been recorded and you can retrieve all information in your <a href=\"contacts.php\">Communicator</a>.'),(1121,1,'send message'),(1122,1,'Send a message to:'),(1123,1,'Your services description here'),(1127,1,'Feedback was added'),(1128,1,'Feedback was not added'),(1129,1,'Feedback was updated'),(1130,1,'Feedback was not updated'),(1133,1,'Feedback header is empty'),(1134,1,'Subscribe now for {0}  newsletter to receive news, updates, photos of top rated members, feedback, tips and articles to your e-mail.'),(1136,1,'Invite a friend to {0}'),(1137,1,'Invite a friend to view the profile at {0}'),(1139,1,'<p align=\"justify\"><site> is a social networking service that allows members to create unique personal profiles online in order to find and communicate with old and new friends. The service is operated by <site>. By using the <site> Website you agree to be bound by these Terms of Use (this &quot;Agreement&quot;), whether or not you register as a member (&quot;Member&quot;). If you wish to become a Member, communicate with other Members and make use of the <site> services (the &quot;Service&quot;), please read this Agreement and indicate your acceptance by following the instructions in the Registration process.</p>\r\n<p align=\"justify\">&nbsp;</p>\r\n<p align=\"justify\">This Agreement sets out the legally binding terms for your use of the Website and your Membership in the Service.<br />\r\n  <site> may modify this Agreement from time to time and such modification shall be effective upon posting by <site> on the Website. You agree to be bound to any changes to this Agreement when you use the Service after any such modification is posted. This Agreement includes <site>\'s policy for acceptable use and content posted on the Website, your rights, obligations and restrictions regarding your use of the Website and the Service and <site>\'s Privacy Policy.</p>\r\n<p align=\"justify\">&nbsp;</p>\r\n<p align=\"justify\">Please choose carefully the information you post on <site> and that you provide to other Members. Any photographs posted by you may not contain nudity, violence, or offensive subject matter. Information provided by other <site> Members (for instance, in their Profile) may contain inaccurate, inappropriate or offensive material, products or services and <site> assumes no responsibility nor liability for this material.</p>\r\n<p align=\"justify\">&nbsp;</p>\r\n<p align=\"justify\"><site> reserves the right, in its sole discretion, to reject, refuse to post or remove any posting (including email) by you, or to restrict, suspend, or terminate your access to all or any part of the Website and/or Services at any time, for any or no reason, with or without prior notice, and without lability.</p>\r\n<p align=\"justify\">&nbsp;</p>\r\n<p align=\"justify\">By participating in any offline <site> event, you agree to release and hold <site> harmless from any and all losses, damages, rights, claims, and actions of any kind including, without limitation, personal injuries, death, and property damage, either directly or indirectly related to or arising from your participation in any such offline <site> event.</p>\r\n<h2 align=\"center\"><b>Terms of Use</b></h2>\r\n\r\n\r\n<p><b>1) Your Interactions.</b></p>\r\n<p>You are solely responsible for your interactions and communication with other Members. You understand that <site> does not in any way screen its Members, nor does <site>  inquire into the backgrounds of its Members or attempt to verify the statements of its Members. <site> makes no representations or warranties as to the conduct of Members or their compatibility with any current or future Members. We do however recommend that if you  choose to meet or exchange personal information with any member of <site> then you should take it upon yourself to do a background check on said person.</p>\r\n<p>In no event shall <site> be liable for any damages whatsoever, whether direct, indirect, general, special, compensatory, consequential, and/or incidental, arising out of or relating to the conduct of you or anyone else in connection with the use of the Service, including without limitation, bodily injury, emotional distress, and/or any other damages resulting from communications or meetings with other registered users of this Service or persons you meet through this Service.</p>\r\n<p>&nbsp;</p>\r\n\r\n<p><b>2) Eligibility.</b></p>\r\n<p align=\"justify\">Membership in the Service where void is prohibited. By using the Website and the Service, you represent and warrant that all registration information you submit is truthful and accurate and that you agree to maintain the accuracy of such information. You further represent and warrant that you are 18 years of age or older and that your use of the <site> shall not violate any applicable law or regulation. Your profile may be deleted without warning, if it is found that you are misrepresenting your age. Your Membership is solely for your personal use, and you shall not authorize others to use your account, including your profile or email address. You are solely responsible for all content published or displayed through your account, including any email messages, and for your interactions with other members. </p>\r\n<p>&nbsp;</p>\r\n\r\n<p><b>3) Term/Fees.</b></p>\r\n<p align=\"justify\">This Agreement shall remain in full force and effect while you use the Website, the Service, and/or are a Member. You may terminate your membership at any time. <site> may terminate your membership for any reason, effective upon sending notice to you at the email address you provide in your Membership application or other email address as you may subsequently provide to <site>. By using the Service and by becoming a Member, you acknowledge that <site> reserves the right to charge for the Service and has the right to terminate a Member\'s Membership if Member should breach this Agreement or fail to pay for the Service, as required by this Agreement.</p>\r\n<p>&nbsp;</p>\r\n\r\n<p><b>4) Non Commercial Use by Members.</b></p>\r\n<p align=\"justify\">The Website is for the personal use of Members only and may not be used in connection with any commercial endeavors except those that are specifically endorsed or approved by the management of <site>. Illegal and/or unauthorized use of the Website, including collecting usernames and/or email addresses of Members by electronic or other means for the purpose of sending unsolicited email or unauthorized framing of or linking to the Website will be investigated. Commercial advertisements, affiliate links, and other forms of solicitation may be removed from member profiles without notice and may result in termination of membership privileges. Appropriate legal action will be taken by <site> for any illegal or unauthorized use of the Website.</p>\r\n<p>&nbsp;</p>\r\n\r\n<p><b>5)  Proprietary Rights in Content on <site>.</b></p>\r\n<p align=\"justify\"><site> owns and retains all proprietary rights in the Website and the Service. The Website contains copyrighted material, trademarks, and other proprietary information of <site> \r\nand its licensors. Except for that information which is in the public domain or for which you have been given written permission, you may not copy, modify, publish, transmit, distribute, perform, display, or sell any such proprietary information.</p>\r\n<p>&nbsp;</p>\r\n\r\n<p><b>6)  Content Posted on the Site.</b></p>\r\n<p align=\"justify\">a. You understand and agree that <site> may review and delete any content, messages, <site> Messenger messages, photos or profiles (collectively, &quot;Content&quot;) that in the sole judgment of <site> violate this Agreement or which may be offensive, illegal or violate the rights, harm, or threaten the safety of any Member. </p>\r\n<p>&nbsp;</p>\r\n\r\n<p align=\"justify\">b. You are solely responsible for the Content that you publish or display (hereinafter, &quot;post&quot;) on the Service or any material or information that you transmit to other Members.</p>\r\n<p>&nbsp;</p>\r\n<p align=\"justify\">c. By posting any Content to the public areas of the Website, you hereby grant to <site> the non-exclusive, fully paid, worldwide license to use, publicly perform and display such Content on the Website. This license will terminate at the time you remove such Content from the Website.</p>\r\n<p><br />\r\n</p>\r\n<p align=\"justify\">d. The following is a partial list of the kind of Content that is illegal or prohibited on the Website. <site> reserves the right to investigate and take appropriate legal action in its sole discretion against anyone who violates this provision, including without limitation, removing the offending communication from the Service and terminating the membership of such violators. Prohibited Content includes Content that:</p>\r\n<p>&nbsp;</p>\r\n<p align=\"justify\">  i. is patently offensive and promotes racism, bigotry, hatred or physical harm of any kind against any group or individual; </p>\r\n<p align=\"justify\"><br />\r\n  ii. harasses or advocates harassment of another person;</p>\r\n<p align=\"justify\"><br />\r\n  iii. involves the transmission of &quot;junk mail&quot;, &quot;chain letters,&quot; or unsolicited mass mailing or &quot;spamming&quot;;</p>\r\n<p align=\"justify\"><br />\r\n  iv. promotes information that you know is false or misleading or promotes illegal activities or conduct that is abusive, threatening, \r\n  obscene, defamatory or libelous;</p>\r\n<p align=\"justify\"><br />\r\n  v. promotes an illegal or unauthorized copy of another person\'s copyrighted work, such as providing pirated computer programs or links\r\n  to them, providing information to circumvent manufacture-installed copy-protect devices, or providing pirated music or links to \r\n  pirated music files;</p>\r\n<p align=\"justify\"><br />\r\n  vi. contains restricted or password only access pages or hidden pages or images (those not linked to or from another accessible page);</p>\r\n<p align=\"justify\"><br />\r\n  vii. provides material that exploits people under the age of 18 in a sexual or violent manner, or solicits personal information from \r\n  anyone under 18;</p>\r\n<p align=\"justify\"><br />\r\n  viii. provides instructional information about illegal activities such as making or buying illegal weapons, violating someone\'s privacy, \r\n  or providing or creating computer viruses; </p>\r\n<p align=\"justify\"><br />\r\n  ix. solicits passwords or personal identifying information for commercial or unlawful purposes from other users;</p>\r\n<p align=\"justify\"><br />\r\n  or x. involves commercial activities and/or sales without our prior written consent such as contests, sweepstakes, barter, advertising, \r\n  or pyramid schemes.</p>\r\n<p>&nbsp;</p>\r\n<p align=\"justify\">e. You must use the Service in a manner consistent with any and all applicable laws and regulations. f. You may not engage in advertising to, or solicitation of, any Member to buy or sell any products or services through the Service. You may not transmit any chain letters or junk email to other Members. Although <site> cannot monitor the conduct of its Members off the Website, it is also a violation of these rules to use any information obtained from the Service in order to harass, abuse, or harm another person, or in order to contact, advertise to, solicit, or sell to any Member without their prior explicit consent. In order to protect our Members from such advertising or solicitation, <site> reserves the right to restrict the number of emails which a Member may send to other Members in any 24-hour period to a number which <site> deems appropriate in its sole discretion.</p>\r\n<p align=\"justify\">&nbsp;</p>\r\n<p align=\"justify\">g. You may not cover or obscure the banner advertisements on your personal profile page, or any <site> page via HTML/CSS or any other means.</p>\r\n<p>&nbsp;</p>\r\n<p align=\"justify\">  h. Any automated use of the system, such as using scripts to add friends, is prohibited.</p>\r\n<p>&nbsp;</p>\r\n<p align=\"justify\"> i. You may not attempt to impersonate another user or person who is not a member of <site>.</p>\r\n<p>&nbsp;</p>\r\n<p align=\"justify\"> j. You may not use the account, username, or password of another Member at any time nor may you disclose your password to any third party \r\n  or permit any third party to access your account.</p>\r\n<p>&nbsp;</p>\r\n<p align=\"justify\"> k. You may not sell or otherwise transfer your profile.</p>\r\n<p>&nbsp;</p>\r\n<p><b>7)  Copyright Policy.</b></p>\r\n<p align=\"justify\">You may not post, distribute, or reproduce in any way any copyrighted material, trademarks, or other proprietary information without obtaining the prior written consent of the owner of such proprietary rights. It is the policy of <site> to terminate membership privileges of any member who repeatedly infringes copyright upon prompt notification to <site> by the copyright owner or the copyright owner\'s legal agent. Without limiting the foregoing, if you believe that your work has been copied and posted on the Service in a way that constitutes copyright infringement, please provide our Copyright Agent with the following information: an electronic or physical signature of the person authorized to act on behalf of the owner of the copyright interest; a description of the copyrighted work that you claim has been infringed; a description of where the material that you claim is infringing is located on the Website; your address, telephone number, and email address; a written statement by you that you have a good faith belief that the disputed use is not authorized by the copyright owner, its agent, or the law; a statement by you, made under penalty of perjury, that the above information in your notice is accurate and that you are the copyright owner or authorized to act on the copyright owner\'s behalf. <site>\'s Copyright Agent for notice of claims of copyright infringement can be reached via email address.</p>\r\n<p><br />\r\n</p>\r\n<p><b>8)  Member Disputes.</b></p>\r\n<p align=\"justify\">You are solely responsible for your interactions with other <site> Members. <site> reserves the right, but has no obligation,  to monitor disputes between you and other Members.</p>\r\n<p>&nbsp;</p>\r\n<p><b>9) Disclaimers.</b></p>\r\n<p align=\"justify\"><site> is not responsible for any incorrect or inaccurate content posted on the Website or in connection with the Service provided, whether caused by users of the Website, Members or by any of the equipment or programming associated with or utilized in the Service. <site> is not responsible for the conduct, whether online or offline, of any user of the Website or Member of the Service. <site> assumes no responsibility for any error, omission, interruption, deletion, defect, delay in operation or transmission, communications line failure, theft or destruction or unauthorized access to, or alteration of, any user or Member communication. <site> is not responsible for any problems or technical malfunction of any telephone network or lines, computer online systems, servers or providers, computer equipment, software, failure of any email or players due to technical problems or traffic congestion on the Internet or at any Website or combination thereof, including any injury or damage to users and/or Members or to any person\'s computer related to or resulting from participation or downloading materials in connection with the Website and/or in connection with the Service. Under no circumstances shall <site> be responsible for any loss or damage, including personal injury or death, resulting from use of the Website or the Service or from any Content posted on the Website or transmitted to Members, or any interactions between users of the Website, whether online or offline. The Website and the Service are provided &quot;AS-IS&quot; and <site> expressly disclaims any warranty of fitness for a particular purpose or non-infringement. <site> cannot guarantee and does not promise any specific results from use of the Website and/or the Service.</p>\r\n<p>&nbsp;</p>\r\n<p><b>10</b><b>) Limitation on Liability.</b></p>\r\n<p align=\"justify\">IN NO EVENT SHALL <site> BE LIABLE TO YOU OR ANY THIRD PARTY FOR ANY INDIRECT, CONSEQUENTIAL, EXEMPLARY, INCIDENTAL, SPECIAL OR PUNITIVE DAMAGES, INCLUDING LOST PROFIT DAMAGES ARISING FROM YOUR USE OF THE WEB SITE OR THE SERVICE, EVEN IF <site> HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. NOTWITHSTANDING ANYTHING TO THE CONTRARY CONTAINED HEREIN, <site>.S LIABILITY TO YOU FOR ANY CAUSE WHATSOEVER AND REGARDLESS OF THE FORM OF THE ACTION, WILL AT ALL TIMES BE LIMITED TO AMOUNT PAID, IF ANY, BY YOU TO <site> FOR THE SERVICE DURING THE TERM OF MEMBERSHIP.</p>\r\n<p><br />\r\n</p>\r\n<p><b>11)  Disputes.</b></p>\r\n<p align=\"justify\">If there is any dispute about or involving the Website and/or the Service, by using the Website, you agree that any dispute shall be governed by the laws of the area in which we are based without regard to conflict of law provisions and you agree to personal jurisdiction by and venue in the area in which we are based.</p>\r\n<p>&nbsp;</p>\r\n<p><b>12) Indemnity.</b></p>\r\n<p align=\"justify\">You agree to indemnify and hold <site>, its subsidiaries, affiliates, officers, agents, and other partners and employees, harmless from any loss, liability, claim, or demand, including reasonable attorneys\' fees, made by any third party due to or arising out of your use of the Service in violation of this Agreement and/or arising from a breach of this Agreement and/or any breach of your representations and warranties set forth above. </p>\r\n<p>&nbsp;</p>\r\n<p><b>13) Other.</b></p>\r\n<p align=\"justify\">This Agreement is accepted upon your use of the Website and is further affirmed by you becoming a Member of the Service. This Agreement constitutes the entire agreement between you and <site> regarding the use of the Website and/or the Service. The failure of <site> to exercise or enforce any right or provision of this Agreement shall not operate as a waiver of such right or provision. The section titles in this Agreement are for convenience only and have no legal or contractual effect. Please contact us with any questions regarding this Agreement. <site> is a trademark of &lt;owners business name&gt;.</p>\r\n<p><br />\r\n</p>\r\n<p><b>I HAVE READ THIS AGREEMENT AND AGREE TO ALL OF THE PROVISIONS CONTAINED ABOVE.</b></p>\r\n'),(1153,1,'Your profile is not active for this operation.'),(1154,1,'Write your e-mail here'),(1733,1,' every {4} hours'),(1157,1,'Greeting has been successfully sent.'),(1158,1,'Sorry, a greeting has <b>not</b> been sent.'),(1162,1,'Profile authentification failed'),(1164,1,'Member not available'),(1165,1,'Sorry, max number of greetings per day reached'),(1166,1,'Sorry, you are in the block list of this member'),(1167,1,'Email send failed. Please, try later.'),(1186,1,'Events'),(1242,1,'Back'),(1264,1,'Error while processing uploaded image'),(1274,1,'Failed to apply changes'),(1296,1,'comments'),(1298,1,'Average'),(1299,1,'Ample'),(1300,1,'Athletic'),(1301,1,'Cuddly'),(1302,1,'Slim'),(1303,1,'Very Cuddly'),(1304,1,'Afghanistan'),(1305,1,'Albania'),(1306,1,'Algeria'),(1307,1,'American Samoa'),(1308,1,'Andorra'),(1309,1,'Angola'),(1310,1,'Anguilla'),(1311,1,'Antarctica'),(1312,1,'Antigua and Barbuda'),(1313,1,'Argentina'),(1314,1,'Armenia'),(1315,1,'Aruba'),(1316,1,'Australia'),(1317,1,'Austria'),(1318,1,'Azerbaijan'),(1320,1,'Bahrain'),(1321,1,'Bangladesh'),(1322,1,'Barbados'),(1323,1,'Belarus'),(1324,1,'Belgium'),(1325,1,'Belize'),(1326,1,'Benin'),(1327,1,'Bermuda'),(1328,1,'Bhutan'),(1329,1,'Bolivia'),(1331,1,'Botswana'),(1332,1,'Bouvet Island'),(1333,1,'Brazil'),(1336,1,'Brunei'),(1337,1,'Bulgaria'),(1338,1,'Burkina Faso'),(1339,1,'Burundi'),(1340,1,'Cambodia'),(1341,1,'Cameroon'),(1342,1,'Cape Verde'),(1343,1,'Cayman Islands'),(1345,1,'Chad'),(1346,1,'Canada'),(1347,1,'Chile'),(1348,1,'China'),(1349,1,'Christmas Island'),(1351,1,'Colombia'),(1352,1,'Comoros'),(1354,1,'Cook Islands'),(1355,1,'Costa Rica'),(1357,1,'Croatia'),(1358,1,'Cuba'),(1359,1,'Cyprus'),(1360,1,'Czech Republic'),(1361,1,'Denmark'),(1362,1,'Djibouti'),(1363,1,'Dominica'),(1364,1,'Dominican Republic'),(1365,1,'East Timor'),(1366,1,'Ecuador'),(1367,1,'Egypt'),(1368,1,'El Salvador'),(1369,1,'Equatorial Guinea'),(1370,1,'Eritrea'),(1371,1,'Estonia'),(1372,1,'Ethiopia'),(1374,1,'Faroe Islands'),(1375,1,'Fiji'),(1376,1,'Finland'),(1377,1,'France'),(1378,1,'Gabon'),(1380,1,'Georgia'),(1381,1,'Germany'),(1382,1,'Ghana'),(1383,1,'Gibraltar'),(1384,1,'Greece'),(1385,1,'Greenland'),(1386,1,'Grenada'),(1387,1,'Guadeloupe'),(1388,1,'Guam'),(1389,1,'Guatemala'),(1390,1,'Guinea'),(1391,1,'Guinea-Bissau'),(1392,1,'Guyana'),(1393,1,'Haiti'),(1394,1,'Honduras'),(1396,1,'Hungary'),(1397,1,'Iceland'),(1398,1,'India'),(1399,1,'Indonesia'),(1400,1,'Iran'),(1401,1,'Iraq'),(1402,1,'Ireland'),(1403,1,'Israel'),(1404,1,'Italy'),(1405,1,'Jamaica'),(1406,1,'Japan'),(1407,1,'Jordan'),(1408,1,'Kazakhstan'),(1409,1,'Kenya'),(1410,1,'Kiribati'),(1412,1,'Kuwait'),(1413,1,'Kyrgyzstan'),(1415,1,'Latvia'),(1416,1,'Lebanon'),(1417,1,'Lesotho'),(1418,1,'Liberia'),(1419,1,'Liechtenstein'),(1420,1,'Lithuania'),(1421,1,'Luxembourg'),(1424,1,'Madagascar'),(1425,1,'Malawi'),(1426,1,'Malaysia'),(1427,1,'Maldives'),(1428,1,'Mali'),(1429,1,'Malta'),(1430,1,'Marshall Islands'),(1431,1,'Martinique'),(1432,1,'Mauritania'),(1433,1,'Mauritius'),(1434,1,'Mayotte'),(1435,1,'Mexico'),(1437,1,'Moldova'),(1438,1,'Monaco'),(1439,1,'Mongolia'),(1440,1,'Montserrat'),(1441,1,'Morocco'),(1442,1,'Mozambique'),(1444,1,'Namibia'),(1445,1,'Nauru'),(1446,1,'Nepal'),(1447,1,'Netherlands'),(1448,1,'New Caledonia'),(1449,1,'New Zealand'),(1450,1,'Nicaragua'),(1451,1,'Niger'),(1452,1,'Nigeria'),(1453,1,'Niue'),(1454,1,'Norfolk Island'),(1455,1,'Norway'),(1456,1,'no data given'),(1457,1,'Oman'),(1458,1,'Pakistan'),(1459,1,'Palau'),(1460,1,'Panama'),(1461,1,'Papua New Guinea'),(1462,1,'Paraguay'),(1463,1,'Peru'),(1464,1,'Philippines'),(1466,1,'Poland'),(1467,1,'Portugal'),(1468,1,'Puerto Rico'),(1469,1,'Qatar'),(1470,1,'Reunion'),(1471,1,'Romania'),(1472,1,'Russia'),(1473,1,'Rwanda'),(1474,1,'Saint Lucia'),(1475,1,'Samoa'),(1476,1,'San Marino'),(1477,1,'Saudi Arabia'),(1478,1,'Senegal'),(1479,1,'Seychelles'),(1480,1,'Sierra Leone'),(1481,1,'Singapore'),(1482,1,'Slovakia'),(1483,1,'Solomon Islands'),(1484,1,'Somalia'),(1485,1,'South Africa'),(1486,1,'Spain'),(1487,1,'Sri Lanka'),(1489,1,'Sudan'),(1490,1,'Suriname'),(1491,1,'Swaziland'),(1492,1,'Sweden'),(1493,1,'Switzerland'),(1494,1,'Syria'),(1495,1,'Taiwan'),(1496,1,'Tajikistan'),(1497,1,'Tanzania'),(1498,1,'Thailand'),(1499,1,'Togo'),(1500,1,'Tokelau'),(1501,1,'Tonga'),(1502,1,'Trinidad and Tobago'),(1503,1,'Tunisia'),(1504,1,'Turkey'),(1505,1,'Turkmenistan'),(1506,1,'Tuvalu'),(1507,1,'Uganda'),(1508,1,'Ukraine'),(1509,1,'United Arab Emirates'),(1510,1,'United Kingdom'),(1512,1,'Uruguay'),(1513,1,'Uzbekistan'),(1514,1,'Vanuatu'),(1516,1,'Venezuela'),(1518,1,'Virgin Islands'),(1519,1,'Western Sahara'),(1520,1,'Yemen'),(1521,1,'Yugoslavia'),(1523,1,'Zambia'),(1524,1,'Zimbabwe'),(1810,1,'Netherlands Antilles'),(1811,1,'Bosnia and Herzegovina'),(1812,1,'The Bahamas'),(1813,1,'Cocos (Keeling) Islands'),(1814,1,'Congo, Democratic Republic of the'),(1815,1,'Central African Republic'),(1816,1,'Congo, Republic of the'),(1817,1,'Cote d\'Ivoire'),(1818,1,'Falkland Islands (Islas Malvinas)'),(1819,1,'Micronesia, Federated States of'),(1820,1,'French Guiana'),(1821,1,'The Gambia'),(1822,1,'South Georgia and the South Sandwich Islands'),(1823,1,'Hong Kong (SAR)'),(1824,1,'Heard Island and McDonald Islands'),(1825,1,'British Indian Ocean Territory'),(1826,1,'Saint Kitts and Nevis'),(1827,1,'Korea, North'),(1828,1,'Korea, South'),(1829,1,'Laos'),(1830,1,'Libya'),(1831,1,'Macedonia, The Former Yugoslav Republic of'),(1832,1,'Burma'),(1833,1,'Macao'),(1834,1,'Northern Mariana Islands'),(1835,1,'French Polynesia'),(1836,1,'Saint Pierre and Miquelon'),(1837,1,'Pitcairn Islands'),(1838,1,'Palestinian Territory, Occupied'),(1839,1,'Saint Helena'),(1840,1,'Slovenia'),(1841,1,'Svalbard'),(1842,1,'Sao Tome and Principe'),(1843,1,'Turks and Caicos Islands'),(1844,1,'French Southern and Antarctic Lands'),(1845,1,'United States Minor Outlying Islands'),(1846,1,'United States'),(1847,1,'Holy See (Vatican City)'),(1848,1,'Saint Vincent and the Grenadines'),(1849,1,'British Virgin Islands'),(1850,1,'Vietnam'),(1851,1,'Wallis and Futuna'),(1525,1,'High School graduate'),(1526,1,'Some college'),(1527,1,'College student'),(1528,1,'AA (2 years college)'),(1529,1,'BA/BS (4 years college)'),(1530,1,'Some grad school'),(1531,1,'Grad school student'),(1532,1,'MA/MS/MBA'),(1533,1,'PhD/Post doctorate'),(1534,1,'JD'),(1535,1,'African'),(1536,1,'African American'),(1537,1,'Asian'),(1538,1,'Caucasian'),(1539,1,'East Indian'),(1540,1,'Hispanic'),(1541,1,'Indian'),(1542,1,'Latino'),(1543,1,'Mediterranean'),(1544,1,'Middle Eastern'),(1545,1,'Mixed'),(1553,1,'$10,000/year and less'),(1554,1,'$10,000-$30,000/year'),(1555,1,'$30,000-$50,000/year'),(1556,1,'$50,000-$70,000/year'),(1557,1,'$70,000/year and more'),(1558,1,'English'),(1559,1,'Afrikaans'),(1560,1,'Arabic'),(1561,1,'Bulgarian'),(1562,1,'Burmese'),(1563,1,'Cantonese'),(1564,1,'Croatian'),(1565,1,'Danish'),(1566,1,'Database Error'),(1567,1,'Dutch'),(1568,1,'Esperanto'),(1569,1,'Estonian'),(1570,1,'Finnish'),(1571,1,'French'),(1572,1,'German'),(1573,1,'Greek'),(1574,1,'Gujrati'),(1575,1,'Hebrew'),(1576,1,'Hindi'),(1577,1,'Hungarian'),(1578,1,'Icelandic'),(1579,1,'Indonesian'),(1580,1,'Italian'),(1581,1,'Japanese'),(1582,1,'Korean'),(1583,1,'Latvian'),(1584,1,'Lithuanian'),(1585,1,'Malay'),(1586,1,'Mandarin'),(1587,1,'Marathi'),(1588,1,'Moldovian'),(1589,1,'Nepalese'),(1590,1,'Norwegian'),(1591,1,'Persian'),(1592,1,'Polish'),(1593,1,'Portuguese'),(1594,1,'Punjabi'),(1595,1,'Romanian'),(1596,1,'Russian'),(1597,1,'Serbian'),(1598,1,'Spanish'),(1599,1,'Swedish'),(1600,1,'Tagalog'),(1601,1,'Taiwanese'),(1602,1,'Tamil'),(1603,1,'Telugu'),(1604,1,'Thai'),(1605,1,'Tongan'),(1606,1,'Turkish'),(1607,1,'Ukrainian'),(1608,1,'Urdu'),(1609,1,'Vietnamese'),(1610,1,'Visayan'),(1611,1,'Single'),(1612,1,'Attached'),(1613,1,'Divorced'),(1614,1,'Married'),(1615,1,'Separated'),(1616,1,'Widow/er'),(1619,1,'Active'),(1620,1,'Suspended'),(1624,1,'Active'),(1625,1,'Suspended'),(1645,1,'Adventist'),(1646,1,'Agnostic'),(1647,1,'Atheist'),(1648,1,'Baptist'),(1649,1,'Buddhist'),(1650,1,'Caodaism'),(1651,1,'Catholic'),(1652,1,'Christian'),(1653,1,'Hindu'),(1654,1,'Iskcon'),(1655,1,'Jainism'),(1656,1,'Jewish'),(1657,1,'Methodist'),(1658,1,'Mormon'),(1659,1,'Moslem'),(1660,1,'Orthodox'),(1661,1,'Pentecostal'),(1662,1,'Protestant'),(1663,1,'Quaker'),(1664,1,'Scientology'),(1665,1,'Shinto'),(1666,1,'Sikhism'),(1667,1,'Spiritual'),(1668,1,'Taoism'),(1669,1,'Wiccan'),(1670,1,'Other'),(1671,1,'No'),(1672,1,'Rarely'),(1673,1,'Often'),(1674,1,'Very often'),(1675,1,'Allowed actions'),(1676,1,'Action'),(1677,1,'Times allowed'),(1678,1,'Period (hours)'),(1679,1,'Allowed Since'),(1680,1,'Allowed Until'),(1681,1,'No actions allowed for this membership'),(1682,1,'no limit'),(1684,1,'use chat'),(1686,1,'view profiles'),(1687,1,'use forum'),(1688,1,'make search'),(1689,1,'rate photos'),(1690,1,'send messages'),(1691,1,'view photos'),(1692,1,'use Ray instant messenger'),(1693,1,'use Ray video recorder'),(1694,1,'use Ray chat'),(1695,1,'use guestbook'),(1696,1,'view other members\' guestbooks'),(1697,1,'get other members\' emails'),(1700,1,'No new messages (<a href=\"{0}mail.php?mode=inbox\">go to Inbox</a>)'),(1701,1,'No new greetings (<a href=\"{0}contacts.php?show=greet&amp;list=i\">go to My Greetings</a>)'),(1702,1,'No new friends (<a href=\"{0}contacts.php?show=friends\">go to My Friends</a>)'),(1728,1,'<div style=\"width: 80%\">Your current membership (<b>{2}</b>) doesn\'t allow you to <b>{1}</b>.</div>'),(1729,1,'<div style=\"width: 80%\">You are not currently an active member. Please ask the site <a href=\"mailto:{7}\">administrator</a> to make you an active member so you can use this feature.</div>'),(1730,1,'You have reached your limit for now. Your current membership (<b>{2}</b>) allows you to {1} no more than {3} times'),(1731,1,'<div style=\"width: 80%\">Your current membership (<b>{2}</b>) doesn\'t allow you to <b>{1}</b> until <b>{6}</b>.</div>'),(1732,1,'<div style=\"width: 80%\">Your current membership (<b>{2}</b>) doesn\'t allow you to <b>{1}</b> since <b>{5}</b>.</div>'),(1734,1,'Choose forum'),(1735,1,'Module access error'),(1739,1,'Get membership'),(1740,1,'Get new membership'),(1741,1,'requires {0} members'),(1744,1,'<a href=\"getmem.php\">Click here</a> to change your membership status'),(1745,1,'Events'),(1746,1,'No events available'),(1747,1,'{0} photo'),(1748,1,'No photo'),(1749,1,'Select events to show'),(1750,1,'Show events by country'),(1751,1,'Show all events'),(1752,1,'Show info'),(1753,1,'Participants'),(1754,1,'Choose participants you liked'),(1755,1,'Status message'),(1757,1,'Place'),(1758,1,'There are no participants for this event'),(1761,1,'Event is unavailable'),(1762,1,'Event start'),(1763,1,'Event end'),(1764,1,'Ticket sale start'),(1765,1,'Ticket sale end'),(1766,1,'Responsible person'),(1767,1,'Tickets left'),(1768,1,'Ticket price'),(1769,1,'Sale status'),(1770,1,'Sale finished'),(1771,1,'Sale not started yet'),(1772,1,'No tickets left'),(1773,1,'Event has already started'),(1774,1,'Event has already finished'),(1775,1,'You are already a participant of this event. Here is your personal Unique ID for this event:'),(1776,1,'You can buy the ticket'),(1777,1,'Buy ticket'),(1778,1,'Change'),(1779,1,'Can\'t change participant UID'),(1780,1,'UID already exists'),(1781,1,'You successfully purchased the Event ticket, but an e-mail with event information wasn\'t sent. Don\'t worry, you can view this data on the event information page.'),(1782,1,'Event participants'),(1783,1,'Event UID'),(1788,1,'Show calendar'),(1789,1,'Calendar'),(1790,1,'Sun'),(1791,1,'Mon'),(1792,1,'Tue'),(1793,1,'Wed'),(1794,1,'Thu'),(1795,1,'Fri'),(1796,1,'Sat'),(1798,1,'Invalid module type selected'),(1799,1,'Module directory was not set. Module must be re-configured.'),(1800,1,'Select module type'),(1801,1,'Please login before using Ray chat'),(1803,1,'No modules of this type installed'),(1804,1,'Module selection'),(1806,1,'Choose module type'),(1807,1,'Module type selection'),(1808,1,'No modules found'),(1809,1,'Ray is not enabled. Please <a href=\"{0}\">select another module</a>'),(1852,1,'Check out'),(1853,1,'Membership purchase'),(1854,1,'Event ticket purchase'),(1856,1,'Profiles purchase'),(1857,1,'Payment description'),(1858,1,'Payment amount'),(1859,1,'Possible subscription period'),(1860,1,'Payment info'),(1861,1,'Payment methods'),(1864,1,'recurring payment'),(1865,1,'recurring not supported'),(1866,1,'recurring not allowed'),(1867,1,'Lifetime'),(1869,1,'Subscriptions'),(1870,1,'Start date'),(1871,1,'Period'),(1872,1,'Charges number'),(1873,1,'Cancel'),(1874,1,'Subscription cancellation request was successfully sent'),(1875,1,'Failed to send subscription cancellation request'),(1876,1,'Message Subject'),(1877,1,'Customize Profile'),(1878,1,'Background color'),(1879,1,'Background picture'),(1880,1,'Font color'),(1881,1,'Font size'),(1882,1,'Font family'),(1883,1,'Credit card number'),(1884,1,'Expiration date'),(1885,1,'You did not receive any messages from {0}'),(1886,1,'You did not write any message to {0}'),(1887,1,'Your messages to {0}'),(1888,1,'Messages from {0} to you'),(1889,1,'Reset'),(1890,1,'Customize'),(1891,1,'No rated profiles this week'),(1892,1,'No rated profiles this month'),(1896,1,'Powered by <a href=\"http://www.boonex.com/products/dolphin/\">Dolphin Smart Community Builder</a> &nbsp; <a href=\"http://www.boonex.com/products/orca/\">Orca Interactive Forum Script</a> &nbsp; <a href=\"http://www.boonex.com/products/ray/\">Ray Community Widget Suite</a>'),(1904,1,'Not a member?'),(1910,1,'To compose new message for <strong>{0}</strong> click <a href=\"{2}compose.php?ID={1}\">here</a>.'),(1914,1,'Profile Comments'),(1915,1,'Add new event'),(1916,1,'Title'),(1917,1,'Venue photo'),(1918,1,'Female ticket count'),(1919,1,'Male ticket count'),(1921,1,'Please fill out all fields'),(1930,1,'Poll created'),(1931,1,'Maximum number of allowed polls reached'),(1932,1,'controls'),(1933,1,'Are you sure?'),(1934,1,'no poll'),(1935,1,'Question'),(1936,1,'Answer variants'),(1937,1,'add answer'),(1938,1,'generate poll'),(1939,1,'Create poll'),(1943,1,'No profile polls available.'),(1945,1,'delete'),(1947,1,'loading ...'),(1948,1,'Poll successfully deleted'),(1949,1,'make it'),(1950,1,'use gallery'),(1951,1,'view other member galleries'),(1954,1,'Recipient'),(1955,1,'All'),(1968,1,'Forgot Password'),(1971,1,'Photos'),(1972,1,'Contact Us'),(1974,1,'random'),(1975,1,'latest'),(1984,1,'{0} day(s)'),(1985,1,'{0} hour(s)'),(1986,1,'{0} minute(s)'),(2004,1,'please fill in these fields first'),(2005,1,'please select'),(2006,1,'associated image'),(2007,1,'post comment permissions'),(2008,1,'read permissions'),(2011,1,'Category description'),(2012,1,'Category title'),(2014,1,'add category'),(2015,1,'{0}\'s blog'),(2016,1,'edit category'),(2017,1,'characters left'),(2019,1,'this blog only for friends'),(2020,1,'commenting in this blog allowed only for friends'),(2021,1,'you don\'t have permission to edit'),(2026,1,'category deleted'),(2027,1,'failed to delete the category'),(2028,1,'category successfully added'),(2029,1,'failed to add category'),(2030,1,'changes successfully applied'),(2032,1,'comment added successfully'),(2033,1,'failed to add comment'),(2045,1,'My Presence'),(2047,1,'use blog'),(2052,1,'Help'),(2060,1,'Title should be {0} characters minimum '),(2065,1,'add new {0}'),(2066,1,'there is no photo that you can rate'),(2067,1,'ratio'),(2070,1,'download'),(2071,1,'UPLOAD MEDIA'),(2075,1,'profile media gallery'),(2081,1,'Showing results: <b>{0}</b> - <b>{1}</b> of <b>{2}</b>'),(2082,1,'{0} groups'),(2083,1,'Groups'),(2084,1,'My Groups'),(2085,1,'Group is not found'),(2086,1,'Group is not found by ID'),(2087,1,'Group is hidden'),(2088,1,'<div align=\"center\">Sorry, the group is hidden. To make it available you must be invited by the creator or member of the group.</div>'),(2089,1,'Category'),(2090,1,'Founded'),(2091,1,'Members'),(2092,1,'Group Creator'),(2093,1,'Group title'),(2094,1,'Group type'),(2095,1,'Public group'),(2096,1,'Private group'),(2097,1,'Group members'),(2098,1,'View all members'),(2099,1,'Edit members'),(2100,1,'Invite others'),(2101,1,'Upload image'),(2102,1,'Post topic'),(2103,1,'Edit group'),(2104,1,'Resign group'),(2105,1,'Join group'),(2106,1,'Are you sure you want to resign from the group?'),(2107,1,'Are you sure you want to join the group?'),(2108,1,'Create Group'),(2109,1,'The group has been successfully created! Now you can upload a default group image or <a href=\"{0}\">go to group home</a>.'),(2110,1,'Error occurred while creating the group'),(2111,1,'Edit Group'),(2112,1,'You\'re not the creator'),(2113,1,'Groups Home'),(2114,1,'Groups categories'),(2115,1,'Keyword'),(2116,1,'Advanced search'),(2117,1,'Groups gallery'),(2118,1,'You cannot view gallery since you\'re not a group member'),(2119,1,'Uploaded by'),(2120,1,'Set as thumbnail'),(2121,1,'Are you sure you want to delete this image?'),(2122,1,'Delete image'),(2123,1,'You cannot view group members since you\'re not a group member'),(2124,1,'Group Creator'),(2125,1,'Are you sure you want to delete this member?'),(2126,1,'Delete member'),(2127,1,'Search Groups'),(2128,1,'Search by'),(2129,1,'group name'),(2130,1,'keyword'),(2131,1,'- Any -'),(2132,1,'Sort by'),(2133,1,'popular'),(2134,1,'newest'),(2135,1,'<div align=\"center\">Sorry, no groups are found</div>'),(2136,1,'Groups search results'),(2137,1,'<div align=\"center\">There are no groups</div>'),(2138,1,'Choose'),(2139,1,'Open join'),(2140,1,'Hidden group'),(2141,1,'Members can post images'),(2142,1,'Members can invite'),(2143,1,'Group description'),(2144,1,'Group name already exists'),(2145,1,'Name is required'),(2146,1,'Category is required'),(2147,1,'Country is required'),(2148,1,'City is required'),(2149,1,'Group title is required'),(2152,1,'Select file'),(2153,1,'Group action'),(2154,1,'Error occurred while uploading image to group gallery'),(2155,1,'You should specify file'),(2156,1,'Upload image to group gallery'),(2157,1,'Image has been successfully uploaded!'),(2158,1,'You should select correct image file'),(2159,1,'Upload error'),(2160,1,'You must choose a file with .jpeg, .gif, .png extensions.'),(2161,1,'You cannot upload images because members of this group are not allowed to upload images'),(2162,1,'You cannot upload images because you\'re not a group member'),(2163,1,'Error occurred while joining the group'),(2164,1,'You\'re already in this group'),(2165,1,'Group join'),(2166,1,'Congratulations. You\'re a group member now.'),(2167,1,'Request has been sent to the group creator. You will become an active group member after approval.'),(2168,1,'Error occurred while resigning from the group'),(2169,1,'You cannot resign from the group because you\'re the creator'),(2170,1,'Group resign'),(2171,1,'You successfully resigned from the group'),(2172,1,'You cannot resign from the group because you\'re not a group member'),(2173,1,'Group thumbnail set'),(2174,1,'You cannot set the group thumbnail because you are not a group creator'),(2175,1,'Group image delete'),(2176,1,'You cannot delete the image because you are not the group creator'),(2177,1,'Error occurred while deleting the group member'),(2178,1,'You cannot delete yourself from the group because you are the group creator'),(2179,1,'You cannot delete the group member because you are not the group creator'),(2180,1,'Group member approved'),(2181,1,'Member has been successfully approved'),(2182,1,'Error occurred while approving a member'),(2183,1,'An error occurred. The user might have resigned from the group prior to obtaining your approval.'),(2184,1,'You cannot approve the group member because you are not the group creator'),(2185,1,'Group member rejected'),(2186,1,'Member has been rejected'),(2187,1,'Error occurred while rejecting a member'),(2188,1,'You cannot reject the group member because you are not the group creator'),(2189,1,'Group action error'),(2190,1,'Unknown group action'),(2191,1,'Group name'),(2192,1,'Please select at least one search parameter'),(2193,1,'Choose members you want to send an invitation to'),(2194,1,'<div align=\"center\">There are no members in this group</div>'),(2195,1,'Go to {0} group'),(2197,1,'Groups help'),(2198,1,'<b>Open Join</b><br />\r\n<b>Yes</b> - you can choose \"yes\" if you want users to join your group without your approval.<br />\r\n<b>No</b> - you can choose \"no\" if you want users to join your group only after your approval.'),(2199,1,'<b>Hidden Group</b><br />\r\n<b>Yes</b> - you can choose &quot;yes&quot; if you want your group unavailable for viewing. You should invite the members before they can see your group.<br />\r\n<b>No</b> - you can choose &quot;no&quot; if you want any member can see your group whether he/she is a group member or not.'),(2200,1,'close window'),(2201,1,'<b>Members can invite</b><br />\r\n<b>Yes</b> - if you choose &quot;yes&quot; you allow your group\'s members to invite other members without your approval.<br />\r\n<b>No</b> - if you choose &quot;no&quot; you will be the only person who can invite others to your group.'),(2202,1,'<b>Members can post images</b><br />\r\n<b>Yes</b> - if you choose &quot;yes&quot; you allow members to post images.<br />\r\n<b>No</b> - if you choose &quot;no&quot; only you, the creator, can post images.'),(2203,1,'<b>Public group</b><br />\r\nYou can view this group and easily join it'),(2204,1,'<b>Private group</b><br />\r\nYou can view the group but to become a group member you need to be approved by the creator'),(2205,1,'<b>Private group</b><br />\r\nTo view this group you must be invited by the group creator or a member of this group'),(2206,1,'Group invite'),(2207,1,'Your friends'),(2208,1,'Invite list'),(2209,1,'Add ->'),(2210,1,'<- Remove'),(2211,1,'Find more...'),(2212,1,'Send invites'),(2213,1,'Invites succesfully sent'),(2214,1,'You should specify at least one member'),(2215,1,'Group invite accepted'),(2216,1,'You successfully accepted the group invitation. Now you\'re an active member of this group.'),(2217,1,'Group invite accept error'),(2218,1,'You cannot accept group invite'),(2219,1,'Group invite reject'),(2220,1,'You succesfully rejected the group invitation'),(2221,1,'Quick Search Members'),(2222,1,'Enter search parameters'),(2225,1,'Quick search results'),(2224,1,'Enter member NickName or ID'),(2226,1,'Add member'),(2227,1,'Post a new topic'),(2228,1,'Group forum'),(2229,1,'View all topics'),(2230,1,'Hello, <b>{0}</b>!'),(2231,1,'Top'),(2233,1,'My account'),(2234,1,'Submitted by {0}'),(2235,1,'Members'),(2236,1,'News'),(2237,1,'Next page'),(2238,1,'Previous page'),(2239,1,'Group is suspended'),(2240,1,'Sorry, group is unavailable because it was suspended by site admin'),(2241,1,'Status'),(2242,1,'<b>Suspended group</b><br />\r\nThe administrator of the site has suspended your group for some reason.<br />\r\nThis means that members will not see your group until the administrator activates it.'),(2244,1,'Tags'),(2245,1,'You must be an active member to create groups'),(2248,1,'No blogs available'),(2249,1,'Blogs'),(2250,1,'Author: <b><a href=\"{0}\">{0}</a></b>'),(2251,1,'<img src=\"{0}\" alt=\"\" /><a href=\"{1}\">{2}</a>'),(2252,1,'<img src=\"{0}\" />{1} comments'),(2254,1,'Videos'),(2255,1,'Forums'),(2256,1,'{0} time(s)'),(2257,1,'My Account'),(2258,1,'My Mail'),(2259,1,'Inbox'),(2260,1,'Sent'),(2261,1,'Write'),(2262,1,'I Blocked'),(2263,1,'Blocked Me'),(2266,1,'My Video Gallery'),(2268,1,'My Events'),(2269,1,'My Blog'),(2270,1,'My Polls'),(2271,1,'My Guestbook'),(2274,1,'My Friends'),(2281,1,'Photos'),(2287,1,'Add Category'),(2288,1,'New Post'),(2290,1,'Add Post'),(2300,1,'Send Message'),(2304,1,'Get E-mail'),(2308,1,'Actions'),(2331,1,'Site Polls'),(2315,1,'Members Polls'),(2316,1,'Members Polls'),(2317,1,'Member Poll'),(2318,1,'Member Poll'),(2322,1,'Previously rated'),(2324,1,'Top Photos'),(2326,1,'My Contacts'),(2328,1,'Poll not available'),(2329,1,'Flag'),(2330,1,'Click to sort'),(2332,1,'Simple Search'),(2333,1,'Advanced Search'),(2334,1,'Site Poll'),(2335,1,'Top Groups\r\n'),(2336,1,'All Blogs\r\n'),(2337,1,'No members found here'),(2340,1,'Bookmark'),(2341,1,'or'),(2342,1,'Classifieds'),(2344,1,'Events'),(2345,1,'Feedback'),(2347,1,'Sorry, you\'ve already joined'),(2354,1,'Classifieds'),(2355,1,'Classifieds Advertisements field'),(2357,1,'Browse All Ads'),(2358,1,'My Classifieds'),(2359,1,'Browse My Ads'),(2360,1,'Post New Advertisement'),(2362,1,'Categories'),(2363,1,'Keywords'),(2364,1,'Posted by'),(2365,1,'Details'),(2366,1,'Admin Local Area'),(2367,1,'My Advertisements'),(2368,1,'Life Time'),(2369,1,'Message'),(2370,1,'Pictures'),(2371,1,'Send these files'),(2372,1,'Add more pics'),(2373,1,'Filtered'),(2374,1,'Listing'),(2375,1,'Out'),(2376,1,'of'),(2377,1,'SubCategories'),(2379,1,'Add'),(2380,1,'Add this'),(2381,1,'Desctiption'),(2382,1,'CustomField1'),(2383,1,'CustomField2'),(2384,1,'Apply'),(2385,1,'Activate'),(2387,1,'Back'),(2389,1,'Equal'),(2390,1,'Max'),(2391,1,'Min'),(2392,1,'Could not successfully run query {0} from DB: {1}'),(2393,1,' Your ad will be active for {0} days'),(2394,1,'File: {0} very large to upload.<br>'),(2395,1,'Advertisement successfully added'),(2396,1,'Failed to Insert Advertisement'),(2397,1,'Advertisement successfully deleted'),(2398,1,'_Failed to Delete Advertisement'),(2399,1,'Tree Classifieds Browse'),(2400,1,'Moderating (new messages)'),(2401,1,'Advertisement successfully activated'),(2402,1,'Failed to Activate Advertisement'),(2403,1,'Advertisement successfully updated'),(2404,1,'Failed to Update Advertisement'),(2405,1,'Filter'),(2406,1,'choose'),(2407,1,'Are you sure'),(2408,1,'Apply Changes'),(2409,1,'Offer Details'),(2410,1,'Congratulations! Your account has been successfully confirmed.<br /><br />It will be activated within 12 hours. Our administrators will personally look through your details to make sure you have set everything correctly. This helps {0} be the most accurate community service in the world. We care about the quality of our profiles and guarantee that every user of our system is real, so if you purchase someone\'s contact information, you can be sure that your money isn\'t wasted.'),(2411,1,'Congratulations!<br /><br />Your account has been successfully confirmed and activated.<br />You can log into your account now.'),(2412,1,'wholesale'),(2413,1,'You have chosen the \"Buy Now\" option to purchase the item above. If you wish to proceed and make an immediate purchase of this item at the price listed below, please click the \"Buy Now\" button. This will close the auction allowing you and the seller to complete the transaction.'),(2414,1,'Buy Now Amount Details:'),(2415,1,'Your \"Buy it Now\" bid has been received. Please contact the seller to complete the transaction.'),(2416,1,'Comment was successfully added'),(2417,1,'Comment addition failed'),(2418,1,'Leave your comment'),(2419,1,'Post Comment'),(2420,1,'Unit'),(2421,1,'Users other listing'),(2422,1,'Subject is required'),(2423,1,'Message must be at least 50 symbols'),(2424,1,'Manage classifieds'),(2425,1,'Befriend'),(2426,1,'Send Letter'),(2427,1,'Fave'),(2428,1,'Share'),(2429,1,'Report'),(2430,1,'seconds ago'),(2431,1,'minutes ago'),(2432,1,'hours ago'),(2433,1,'days ago'),(2434,1,'Info'),(2435,1,'Profile Music'),(2436,1,'Profile Videos'),(2437,1,'Profile Photos'),(2438,1,'Chat Now'),(2439,1,'Greeting'),(2440,1,'Advertisement'),(2441,1,'Buy Now'),(2442,1,'Account Home'),(2443,1,'My Settings'),(2446,1,'All Members'),(2447,1,'All Groups'),(2448,1,'All Videos'),(2465,1,'Browse Video'),(2466,1,'File was added to favorite'),(2467,1,'File already is a favorite'),(2468,1,'Enter email(s)'),(2469,1,'view Video'),(2470,1,'See all videos of this user'),(2474,1,'Page'),(2475,1,'Music files'),(2476,1,'Browse music files'),(2477,1,'Playbacks'),(2478,1,'Upload Photo'),(2479,1,'Boards'),(2480,1,'All Classifieds'),(2481,1,'Add Classified'),(2482,1,'Music'),(2483,1,'All Music'),(2484,1,'Upload Music'),(2485,1,'All Photos'),(2486,1,'Top Blogs'),(2487,1,'All Events'),(2488,1,'Add Event'),(2489,1,'All Polls'),(2490,1,'Profile Music'),(2491,1,'Guestbook'),(2493,1,'Upload Video'),(2494,1,'Upload File'),(2495,1,'Sorry, nothing found'),(2496,1,'File was uploaded succesfully'),(2497,1,'Added'),(2498,1,'URL'),(2499,1,'Embed'),(2500,1,'Views'),(2501,1,'Video Info'),(2503,1,'File info was sent'),(2504,1,'Latest files from this user'),(2505,1,'View Comments'),(2506,1,'Upload Music'),(2507,1,'Browse Photo'),(2508,1,'Upload failed'),(2509,1,'Photo Info'),(2510,1,'View Photo'),(2511,1,'Music File Info'),(2512,1,'View Music'),(2514,1,'My Music Gallery'),(2515,1,'Ray Chat'),(2516,1,'Photo'),(2518,1,'Make Primary'),(2519,1,'See all photos of this user'),(2520,1,'Untitled'),(2521,1,'Original Size'),(2522,1,'Rate'),(2523,1,'Advertisement Photos'),(2524,1,'Comments'),(2525,1,'Users Other Listings'),(2526,1,'Top Videos'),(2527,1,'Top Music'),(2528,1,'Profile Photos'),(2529,1,'Profile Music'),(2530,1,'Profile Video'),(2531,1,'You have successfully joined this Event'),(2532,1,'List'),(2533,1,'Event'),(2534,1,'Post Event'),(2535,1,'By'),(2536,1,'Please Wait'),(2537,1,'Vote'),(2538,1,'My Favorite Photos'),(2539,1,'My Favorite Videos'),(2540,1,'My Favorite Music'),(2541,1,'Music Gallery'),(2542,1,'Photos Gallery'),(2543,1,'Video Gallery'),(2544,1,'Post'),(2545,1,'Caption'),(2546,1,'Please, Create a Blog'),(2547,1,'Create My Blog'),(2548,1,'Create Blog'),(2549,1,'Posts'),(2554,1,'{0} Photos'),(2555,1,'Top Posts'),(2568,1,'BoonEx News'),(2570,1,'post successfully deleted'),(2571,1,'failed to delete post'),(2572,1,'failed to add post'),(2573,1,'post successfully added'),(2574,1,'Leaders'),(2575,1,'Day'),(2576,1,'Month'),(2577,1,'Week'),(2578,1,'No rated profiles today'),(2579,1,'This may be a hacker string'),(2581,1,'Write a description for your Blog.'),(2582,1,'Error Occured'),(2584,1,'Forum Posts'),(2586,1,'Get BoonEx ID'),(2587,1,'Import BoonEx ID'),(2588,1,'Import'),(2590,1,'No articles available'),(2591,1,'Read All Articles'),(2592,1,'Shared Photos'),(2593,1,'Shared Videos'),(2594,1,'Shared Music Files'),(2595,1,'This Week'),(2596,1,'This Month'),(2597,1,'This Year'),(2598,1,'Topics'),(2599,1,'No tags found here'),(2600,1,'Ads'),(2601,1,'New Today'),(2602,1,'Photo Gallery'),(2603,1,'No classifieds available'),(2604,1,'No groups available'),(2605,1,'My Music Gallery'),(2606,1,'My Photo Gallery'),(2607,1,'My Video Gallery'),(2608,1,'Count'),(2609,1,'Site Stats'),(2610,1,'I agree'),(2611,1,'{0} Upload Agreement'),(2612,1,'The terms of the Agreement in a nutshell:\r\n1. You have permission to upload the material, or you have obtained permission from the relevant rights holder(s).\r\n2. {0} may use your material for its content and you have the right to provide this material for free downloads.\r\n3. The list of PROHIBITED actions.\r\n\r\n1. LICENSED MATERIAL\r\nWhen uploading licensed material you confirm that you have the right or permission to upload it. You confirm that your material can be used by you and has not been stolen. You are only responsible for the uploaded material and, in case someone declares that the material has been stolen and will provide us with all the license documents, {0} has the right to remove your files and provide the material owner with your contact information. \r\n\r\n2. GRANTING OF LICENSE\r\n\r\nWhen uploading the material, you provide {0} and its members with the right to use it. You understand that our site is an open site, therefore you agree that the material uploaded by you can be downloaded and used by other site members. {0} isn&#8217;t responsible for the usage of your material on third party sites. \r\n\r\n3. STRONGLY PROHIBITED\r\n\r\n- Media files having negative or any other psychological or mental influence.\r\n- Media files containing children\'s porno. \r\n- Media containing naked views of you or your children.\r\nIf you do not agree with these stipulations, you may not upload any media files.'),(2613,1,'Event Deleted'),(2614,1,'Tags'),(2615,1,'Tags separated by spaces'),(2616,1,'You must enter your Tags'),(2617,1,'Member Friends'),(2618,1,'Select'),(2619,1,'Join Now'),(2620,1,'Tag'),(2621,1,'Sorry, no members found'),(2622,1,'Sorry, you didn\'t post any ads'),(2623,1,'Password confirmation failed'),(2624,1,'Change Password'),(2625,1,'Blog Post successfully updated'),(2626,1,'Failed to update Blog Post'),(2627,1,'Your age doesn\'t allow access to this site'),(2628,1,'Requested File Doesn\'t Exist'),(2629,1,'Admin Panel'),(2630,1,'File upload error'),(2631,1,'send greetings'),(2632,1,'AddMainCategory successfully added'),(2633,1,'Failed to Insert AddMainCategory'),(2634,1,'AddSubCategory successfully added'),(2635,1,'Failed to Insert AddSubCategory'),(2636,1,'DeleteMainCategory was successful'),(2637,1,'Failed to DeleteMainCategory'),(2638,1,'DeleteSubCategory was successful'),(2639,1,'Failed to DeleteSubCategory'),(2640,1,'Add New Article'),(2641,1,'Category Caption'),(2642,1,'Articles Deleted Successfully'),(2643,1,'Articles are not deleted'),(2644,1,'Category Deleted Successfully'),(2645,1,'Category not deleted'),(2646,1,'Hot or Not'),(2647,1,'Affiliate system was disabled'),(2648,1,'Description'),(2649,1,'Mutual Friends'),(2650,1,'Photo Actions'),(2651,1,'Notification'),(2652,1,'You have successfully unsubscribed from Event'),(2653,1,'Unsubscribe'),(2654,1,'Inactive Story'),(2655,1,'Profile Videos'),(2656,1,'My Flags'),(2657,1,'My Topics'),(2658,1,'Uncategorized'),(2659,1,'upload Music (Music Gallery)'),(2660,1,'upload Photos (Photo Gallery)'),(2661,1,'upload Video (Video Gallery)'),(2662,1,'play Music (Music Gallery)'),(2663,1,'view Photos (Photo Gallery)'),(2664,1,'play Video (Video Gallery)'),(2665,1,'Congratulations! Your e-mail confirmation succeeded and your profile has been activated!<br />\r\nPlease click \"Continue\" below to navigate to the home page of the site.'),(2666,1,'Profile Type'),(2667,1,'Profile Type'),(2668,1,'Select \"Couple\" if you are joining as a couple'),(2669,1,'General Info'),(2670,1,'NickName'),(2671,1,'Select NickName which will be used for logging in to the site'),(2672,1,'You must enter NickName'),(2673,1,'Your NickName must be at least {0} characters long'),(2674,1,'Your NickName should be no longer than {0} characters long'),(2675,1,'This NickName already used by another. Please select another NickName.'),(2676,1,'Your NickName must contain only latin symbols, numbers or underscore ( _ ) or minus ( - ) signs'),(2677,1,'Email'),(2678,1,'Enter your Email. Your password will be sent to this email.'),(2679,1,'You must enter Email'),(2680,1,'Your email too short'),(2681,1,'Your email already used by another member'),(2682,1,'Please enter correct email'),(2683,1,'Password'),(2684,1,'Please specify your password. It will be used for logging in to the site. This storage is secure, because we are using an encrypted format.'),(2685,1,'You must enter password'),(2686,1,'Your password must be at least {0} characters long'),(2687,1,'Your password should be no longer than {0} characters'),(2688,1,'Miscellaneous Info'),(2689,1,'Sex'),(2690,1,'Please specify your gender'),(2691,1,'You must specify your gender'),(2692,1,'Looking for'),(2693,1,'Please specify whom you are looking for'),(2694,1,'Date of birth'),(2695,1,'Please specify your birth date using the calendar or with this format: Day/Month/Year'),(2696,1,'You must specify your birth date'),(2697,1,'You cannot join the site if you are younger than {0} years'),(2698,1,'You cannot be older than {0} years'),(2699,1,'Headline'),(2700,1,'Enter your life headline'),(2701,1,'Description'),(2702,1,'Describe yourself in a few words. Your description should be at least {0} characters long.'),(2703,1,'You must enter your description'),(2704,1,'Your description should be at least 20 characters long'),(2705,1,'Country'),(2706,1,'Please select the country where are you living'),(2707,1,'City'),(2708,1,'Enter the name of the city where are you living'),(2709,1,'Security Image'),(2710,1,'Captcha'),(2711,1,'Let us check that you are not a bot. Just enter the text which you see on the picture.'),(2712,1,'Admin Controls'),(2713,1,'Description'),(2714,1,'Zip Code'),(2715,1,'Enter your postal zip-code'),(2716,1,'Tags'),(2717,1,'Enter a few words delimited by commas that describe your character'),(2718,1,'General Info'),(2719,1,'NickName'),(2720,1,'Email'),(2721,1,'Sex'),(2722,1,'Change Password'),(2723,1,'To save old password, just leave this field empty. To change, enter new password and confirm it below.'),(2724,1,'Misc Info'),(2725,1,'Looking For'),(2726,1,'Date Of Birth'),(2727,1,'Headline'),(2728,1,'Description'),(2729,1,'Country'),(2730,1,'City'),(2731,1,'Admin Controls'),(2732,1,'Status'),(2733,1,'System user status'),(2734,1,'Featured'),(2735,1,'Show this member in \"Featured\" block of index page'),(2736,1,'General Info'),(2737,1,'Member ID'),(2738,1,'NickName'),(2739,1,'Status'),(2740,1,'Sex'),(2741,1,'Looking For'),(2742,1,'Misc Info'),(2743,1,'Date Of Birth'),(2744,1,'Country'),(2745,1,'City'),(2746,1,'Description'),(2747,1,'Headline'),(2748,1,'Description'),(2749,1,'Admin Controls'),(2750,1,'Email'),(2751,1,'Registration Date'),(2752,1,'Last Login Date'),(2753,1,'Last profile edition date'),(2754,1,'General Info'),(2755,1,'Profile Type'),(2756,1,'Sex'),(2757,1,'Age'),(2758,1,'Country'),(2759,1,'Keyword'),(2760,1,'With Tag'),(2761,1,'Location'),(2763,1,'First Person'),(2764,1,'Second Person'),(2765,1,'Single'),(2766,1,'Couple'),(2767,1,'Enter the same password here'),(2768,1,'Password confirmation failed'),(2769,1,'First value must be bigger'),(2770,1,'Captcha check failed'),(2771,1,'Join failed'),(2772,1,'Join complete'),(2773,1,'Select it'),(2774,1,'Profile not specified'),(2775,1,'You cannot edit this profile'),(2776,1,'Profile not found'),(2777,1,'Couple profile not found'),(2778,1,'The profile was succesfully saved'),(2779,1,'Cast my vote'),(2780,1,'Male'),(2781,1,'Female'),(2782,1,'Last profile edit'),(2783,1,'Last log in'),(2784,1,'ID'),(2785,1,'Misc Info'),(2786,1,'Enable rate'),(2787,1,'Disable rate'),(2788,1,'Remember Me'),(2789,1,'{0} has already joined this group'),(2790,1,'Sorry, you\'ve been banned'),(2791,1,'{0} Minute{1} Ago'),(2792,1,'{0} Hour{1} Ago'),(2793,1,'{0} Day{1} Ago'),(2794,1,'In {0} Minute{1}'),(2795,1,'In {0} Hour{1}'),(2796,1,'In {0} Day{1}'),(2797,1,'Shoutbox'),(2798,1,'Powered by'),(2799,1,'BoonEx - Community Software;  Dating And Social Networking Scripts; Video Chat And More.'),(2800,1,'I have read and agreed with <a href=\"terms_of_use.php\" target=\"_blank\">terms of use</a>.'),(2801,1,'You must agree with terms of use'),(2802,1,'Show <b>{0}</b>-<u>{1}</u> of {2} discussions'),(2803,1,'There are no comments yet'),(2804,1,'Error occurred'),(2805,1,'Duplicate vote'),(2806,1,'No such comment'),(2807,1,'Are you sure?'),(2808,1,'buried\r\n'),(2809,1,'toggle\r\n'),(2810,1,'<span>{0}</span> point'),(2811,1,'<span>{0}</span> points'),(2812,1,'Thumb Up'),(2813,1,'Thumb Down'),(2814,1,'Remove'),(2815,1,'(available for <span>{0}</span> seconds)'),(2816,1,'Show <span>{0}</span> replies'),(2817,1,'Reply to this comment'),(2818,1,'Add Your Comment'),(2819,1,'Submit Comment'),(2820,1,'Cannot delete comments with replies'),(2821,1,'Access denied'),(2822,1,'Save'),(2823,1,'Search by Tag'),(2824,1,'Approve'),(2825,1,'Disapprove'),(2826,1,'Edit Article'),(2827,1,'Article'),(2828,1,'Article Title'),(2829,1,'Select Category'),(2830,1,'Print As'),(2831,1,'Hide <span>{0}</span> replies'),(2832,1,'Counter'),(2833,1,'Articles were deleted successfully'),(2834,1,'Article was deleted successfully'),(2835,1,'Article was not deleted'),(2836,1,'Reply to {0}\'s comment'),(2837,1,'See all music files of this user'),(2838,1,'View All'),(2839,1,'You have reached the allowed photo gallery upload limit'),(2840,1,'You have reached allowed file limit'),(2841,1,'You cannot create events using past dates'),(2842,1,'view other members\' Blog'),(2843,1,'Music actions'),(2844,1,'Video actions'),(2845,1,'Edit event'),(2846,1,'Write new Message'),(2848,1,'Membership level'),(2849,1,'Member membership level'),(2850,1,'Tags'),(2851,1,'use Orca private forums'),(2852,1,'use Orca public forums'),(2853,1,'vote'),(2854,1,'Upload successful'),(2855,1,'Zip Code'),(2856,1,'Enter your postal zip-code'),(2857,1,'Unconfirmed'),(2858,1,'Approval'),(2859,1,'Active'),(2860,1,'Rejected'),(2861,1,'Suspended'),(2862,1,'SubClassified is required'),(2863,1,'for'),(2864,1,'starts immediately'),(2865,1,'{0} January, {1}'),(2866,1,'{0} February, {1}'),(2867,1,'{0} March, {1}'),(2868,1,'{0} April, {1}'),(2869,1,'{0} May, {1}'),(2870,1,'{0} June, {1}'),(2871,1,'{0} July, {1}'),(2872,1,'{0} August, {1}'),(2873,1,'{0} September, {1}'),(2874,1,'{0} October, {1}'),(2875,1,'{0} November, {1}'),(2876,1,'{0} December, {1}'),(2877,1,'Clear'),(2878,1,'SubCategory is required'),(2879,1,'Send eCard'),(2880,1,'send eCards'),(2881,1,'Total'),(2882,1,'Message successfully deleted'),(2883,1,'Disabled');
/*!40000 ALTER TABLE `LocalizationStrings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MemActions`
--

DROP TABLE IF EXISTS `MemActions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `MemActions` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL default '',
  `AdditionalParamName` varchar(80) default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `MemActions`
--

LOCK TABLES `MemActions` WRITE;
/*!40000 ALTER TABLE `MemActions` DISABLE KEYS */;
INSERT INTO `MemActions` VALUES (1,'send greetings',NULL),(2,'use chat',NULL),(4,'view profiles',NULL),(5,'use forum',NULL),(6,'make search','Max. number of profiles shown in search result (0 = unlimited)'),(7,'vote',NULL),(8,'send messages',NULL),(9,'view photos',NULL),(10,'use Ray instant messenger',NULL),(11,'use Ray video recorder',NULL),(12,'use Ray chat',NULL),(13,'use guestbook',NULL),(14,'view other members\' guestbooks',NULL),(15,'get other members\' emails',NULL),(16,'use gallery',NULL),(17,'view other members\' galleries',NULL),(18,'use Ray mp3 player',NULL),(19,'use Blog',NULL),(20,'view other members\' Blog',NULL),(21,'use Ray video player',NULL),(22,'use Ray presence',NULL),(23,'can add_delete classifieds',NULL),(24,'rate photos',NULL),(25,'use Orca public forums',NULL),(26,'use Orca private forums',NULL),(27,'upload Music (Music Gallery)',NULL),(28,'upload Photos (Photo Gallery)',NULL),(29,'upload Video (Video Gallery)',NULL),(30,'play Music (Music Gallery)',NULL),(31,'view Photos (Photo Gallery)',NULL),(32,'play Video (Video Gallery)',NULL),(33,'comments post',NULL),(34,'comments vote',NULL),(35,'comments edit own',NULL),(36,'comments remove own',NULL);
/*!40000 ALTER TABLE `MemActions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MemActionsTrack`
--

DROP TABLE IF EXISTS `MemActionsTrack`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `MemActionsTrack` (
  `IDAction` smallint(5) unsigned NOT NULL default '0',
  `IDMember` bigint(20) unsigned NOT NULL default '0',
  `ActionsLeft` smallint(5) unsigned NOT NULL default '0',
  `ValidSince` datetime default NULL,
  PRIMARY KEY  (`IDAction`,`IDMember`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `MemActionsTrack`
--

LOCK TABLES `MemActionsTrack` WRITE;
/*!40000 ALTER TABLE `MemActionsTrack` DISABLE KEYS */;
/*!40000 ALTER TABLE `MemActionsTrack` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MemLevelActions`
--

DROP TABLE IF EXISTS `MemLevelActions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `MemLevelActions` (
  `IDLevel` smallint(5) unsigned NOT NULL default '0',
  `IDAction` smallint(5) unsigned NOT NULL default '0',
  `AllowedCount` smallint(5) unsigned default NULL,
  `AllowedPeriodLen` smallint(5) unsigned default NULL,
  `AllowedPeriodStart` datetime default NULL,
  `AllowedPeriodEnd` datetime default NULL,
  `AdditionalParamValue` varchar(255) default NULL,
  PRIMARY KEY  (`IDLevel`,`IDAction`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `MemLevelActions`
--

LOCK TABLES `MemLevelActions` WRITE;
/*!40000 ALTER TABLE `MemLevelActions` DISABLE KEYS */;
INSERT INTO `MemLevelActions` VALUES (1,6,NULL,NULL,NULL,NULL,'10'),(1,7,NULL,NULL,NULL,NULL,NULL),(1,14,NULL,NULL,NULL,NULL,NULL),(2,1,4,24,NULL,NULL,NULL),(2,4,2,24,NULL,NULL,NULL),(2,6,5,24,NULL,NULL,'0'),(2,7,15,24,NULL,NULL,NULL),(2,8,2,24,NULL,NULL,NULL),(3,1,NULL,NULL,NULL,NULL,NULL),(3,2,NULL,NULL,NULL,NULL,NULL),(3,3,NULL,NULL,NULL,NULL,NULL),(3,4,NULL,NULL,NULL,NULL,NULL),(3,5,NULL,NULL,NULL,NULL,NULL),(3,6,NULL,NULL,NULL,NULL,NULL),(3,7,NULL,NULL,NULL,NULL,NULL),(3,8,NULL,NULL,NULL,NULL,NULL),(3,9,NULL,NULL,NULL,NULL,NULL),(3,10,NULL,NULL,NULL,NULL,NULL),(3,11,NULL,NULL,NULL,NULL,NULL),(3,12,NULL,NULL,NULL,NULL,NULL),(3,13,NULL,NULL,NULL,NULL,NULL),(3,14,NULL,NULL,NULL,NULL,NULL),(3,15,NULL,NULL,NULL,NULL,NULL),(3,16,NULL,NULL,NULL,NULL,NULL),(3,17,NULL,NULL,NULL,NULL,NULL),(2,9,NULL,NULL,NULL,NULL,NULL),(2,23,NULL,NULL,NULL,NULL,NULL),(2,24,NULL,NULL,NULL,NULL,NULL),(1,25,NULL,NULL,NULL,NULL,NULL),(2,25,NULL,NULL,NULL,NULL,NULL),(3,25,NULL,NULL,NULL,NULL,NULL),(3,26,NULL,NULL,NULL,NULL,NULL),(2,33,NULL,NULL,NULL,NULL,NULL),(2,34,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `MemLevelActions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MemLevelPrices`
--

DROP TABLE IF EXISTS `MemLevelPrices`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `MemLevelPrices` (
  `IDLevel` smallint(5) unsigned NOT NULL default '0',
  `Days` int(10) unsigned NOT NULL default '1',
  `Price` float unsigned NOT NULL default '1',
  PRIMARY KEY  (`IDLevel`,`Days`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `MemLevelPrices`
--

LOCK TABLES `MemLevelPrices` WRITE;
/*!40000 ALTER TABLE `MemLevelPrices` DISABLE KEYS */;
/*!40000 ALTER TABLE `MemLevelPrices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MemLevels`
--

DROP TABLE IF EXISTS `MemLevels`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `MemLevels` (
  `ID` smallint(6) NOT NULL auto_increment,
  `Name` varchar(100) NOT NULL default '',
  `Active` enum('yes','no') NOT NULL default 'no',
  `Purchasable` enum('yes','no') NOT NULL default 'yes',
  `Removable` enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `MemLevels`
--

LOCK TABLES `MemLevels` WRITE;
/*!40000 ALTER TABLE `MemLevels` DISABLE KEYS */;
INSERT INTO `MemLevels` VALUES (1,'Non-member','yes','no','no'),(2,'Standard','yes','no','no'),(3,'Promotion','yes','no','no');
/*!40000 ALTER TABLE `MemLevels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Messages`
--

DROP TABLE IF EXISTS `Messages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Messages` (
  `ID` bigint(20) NOT NULL auto_increment,
  `Date` datetime NOT NULL default '0000-00-00 00:00:00',
  `Sender` bigint(8) unsigned NOT NULL default '0',
  `Recipient` bigint(8) unsigned NOT NULL default '0',
  `Text` mediumtext NOT NULL,
  `Subject` varchar(255) NOT NULL default '',
  `New` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`ID`),
  KEY `Pair` (`Sender`,`Recipient`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Messages`
--

LOCK TABLES `Messages` WRITE;
/*!40000 ALTER TABLE `Messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `Messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Modules`
--

DROP TABLE IF EXISTS `Modules`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Modules` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(32) NOT NULL default '',
  `Conf` mediumtext NOT NULL,
  `FuncAdd` mediumtext NOT NULL,
  `FuncDel` mediumtext NOT NULL,
  `FuncUpdate` mediumtext NOT NULL,
  `FuncBlock` mediumtext NOT NULL,
  `FuncUnblock` mediumtext NOT NULL,
  `Help` mediumtext NOT NULL,
  `LogIn` mediumtext NOT NULL,
  `Type` enum('chat','forum') NOT NULL default 'chat',
  `ReadableName` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Modules`
--

LOCK TABLES `Modules` WRITE;
/*!40000 ALTER TABLE `Modules` DISABLE KEYS */;
/*!40000 ALTER TABLE `Modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `News`
--

DROP TABLE IF EXISTS `News`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `News` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Date` datetime NOT NULL default '0000-00-00 00:00:00',
  `Header` varchar(50) NOT NULL default '',
  `NewsUri` varchar(50) NOT NULL default '',
  `Snippet` varchar(255) NOT NULL default '',
  `Text` text NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `NewsUri` (`NewsUri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `News`
--

LOCK TABLES `News` WRITE;
/*!40000 ALTER TABLE `News` DISABLE KEYS */;
/*!40000 ALTER TABLE `News` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NotifyEmails`
--

DROP TABLE IF EXISTS `NotifyEmails`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `NotifyEmails` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(64) NOT NULL default '',
  `Email` varchar(128) NOT NULL default '',
  `EmailFlag` enum('NotifyMe','NotNotifyMe') NOT NULL default 'NotifyMe',
  `EmailText` enum('HTML','Text','Not sure') NOT NULL default 'HTML',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `NotifyEmails`
--

LOCK TABLES `NotifyEmails` WRITE;
/*!40000 ALTER TABLE `NotifyEmails` DISABLE KEYS */;
/*!40000 ALTER TABLE `NotifyEmails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NotifyMsgs`
--

DROP TABLE IF EXISTS `NotifyMsgs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `NotifyMsgs` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Subj` varchar(128) NOT NULL default '',
  `Text` mediumtext NOT NULL,
  `HTML` mediumtext NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `NotifyMsgs`
--

LOCK TABLES `NotifyMsgs` WRITE;
/*!40000 ALTER TABLE `NotifyMsgs` DISABLE KEYS */;
/*!40000 ALTER TABLE `NotifyMsgs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NotifyQueue`
--

DROP TABLE IF EXISTS `NotifyQueue`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `NotifyQueue` (
  `Email` int(10) unsigned NOT NULL auto_increment,
  `Msg` int(10) unsigned NOT NULL default '0',
  `Creation` datetime NOT NULL default '0000-00-00 00:00:00',
  `From` enum('Profiles','NotifyEmails','ProfilesMsgText') NOT NULL default 'Profiles',
  `MsgText` mediumtext NOT NULL,
  `MsgSubj` varchar(255) NOT NULL default '',
  KEY `Msg` (`Msg`),
  KEY `Email` (`Email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `NotifyQueue`
--

LOCK TABLES `NotifyQueue` WRITE;
/*!40000 ALTER TABLE `NotifyQueue` DISABLE KEYS */;
/*!40000 ALTER TABLE `NotifyQueue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PageCompose`
--

DROP TABLE IF EXISTS `PageCompose`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `PageCompose` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Page` varchar(255) NOT NULL default '',
  `PageWidth` varchar(10) NOT NULL default '960px',
  `Desc` text NOT NULL,
  `Caption` varchar(255) NOT NULL default '',
  `Column` tinyint(3) unsigned NOT NULL default '0',
  `Order` int(10) unsigned NOT NULL default '0',
  `Func` varchar(255) NOT NULL default '',
  `Content` text NOT NULL,
  `DesignBox` tinyint(3) unsigned NOT NULL default '1',
  `ColWidth` tinyint(3) unsigned NOT NULL default '0',
  `Visible` set('non','memb') NOT NULL default 'non,memb',
  `MinWidth` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `PageCompose`
--

LOCK TABLES `PageCompose` WRITE;
/*!40000 ALTER TABLE `PageCompose` DISABLE KEYS */;
INSERT INTO `PageCompose` VALUES (1,'index','960px','Shows statistic information concerning your profiles database','_Site Stats',1,0,'SiteStats','',1,60,'non,memb',0),(2,'index','960px','Show list of site news','_latest news',2,4,'News','',1,40,'non,memb',0),(3,'index','960px','Display form to subscribe to newsletters','_Subscribe',2,6,'Subscribe','',1,40,'non,memb',0),(4,'index','960px','Quick search form','_Quick Search',2,2,'QuickSearch','',1,40,'non,memb',0),(5,'index','960px','Top rated profiles','_Leaders',1,3,'Leaders','',1,60,'non,memb',0),(6,'index','960px','Feedback (Success Story) from your customers','_Feedback',2,8,'Feedback','',1,40,'non,memb',0),(7,'index','960px','List of featured profiles randomly selected from database','_featured members',1,5,'Featured','',1,60,'non,memb',0),(8,'index','960px','Personal profile polls','_Polls',2,7,'ProfilePoll','',1,40,'non,memb',0),(9,'index','960px','Site Tags','_Tags',2,3,'Tags','',1,40,'non,memb',0),(10,'index','960px','Short list of top profiles selected by given criteria','_Members',1,2,'Members','',1,60,'non,memb',0),(11,'index','960px','Recently posted blogs','_Blogs',1,4,'Blogs','',1,60,'non,memb',0),(12,'index','960px','Top rated photos','_Profile Photos',1,9,'ProfilePhotos','',1,60,'non,memb',0),(13,'index','960px','Shoutbox','_Shoutbox',2,1,'Shoutbox','',1,40,'non,memb',330),(14,'index','960px','Shows Login Form','_Member Login',2,0,'LoginSection','',1,40,'non',0),(15,'index','960px','','_BoonEx News',1,1,'RSS','http://www.boonex.com/unity/blog/featured_posts/?rss=1#4',1,60,'non,memb',0),(16,'index','960px','Classifieds','_Classifieds',1,13,'Classifieds','',1,60,'non,memb',0),(17,'index','960px','Events','_Events',1,10,'Events','',1,60,'non,memb',0),(18,'index','960px','Groups','_Groups',1,12,'Groups','',1,60,'non,memb',0),(19,'index','960px','','_Forum Posts',2,5,'RSS','{SiteUrl}orca/?action=rss_all#4',1,40,'non,memb',0),(20,'index','960px','Photos Shared By Members','_Photo Gallery',1,7,'SharePhotos','',1,60,'non,memb',0),(21,'index','960px','Videos Shared By Members','_Video Gallery',1,6,'ShareVideos','',1,60,'non,memb',0),(22,'index','960px','Music Files Shared By Members','_Music Gallery',1,8,'ShareMusic','',1,60,'non,memb',0),(23,'index','960px','Articles','_Articles',1,11,'Articles','',1,60,'non,memb',0),(24,'music','960px','','_Music',1,0,'ViewFile','',1,50,'non,memb',380),(25,'music','960px','','_Rate',2,1,'Rate','',1,50,'non,memb',0),(26,'music','960px','','_Actions',1,1,'ActionList','',1,50,'non,memb',0),(27,'music','960px','','_View Comments',1,2,'ViewComments','',1,50,'non,memb',0),(28,'music','960px','','_Music File Info',2,0,'FileInfo','',1,50,'non,memb',0),(29,'music','960px','','_Latest files from this user',2,2,'LastFiles','',1,50,'non,memb',0),(30,'music','960px','','_BoonEx News',0,0,'RSS','http://www.boonex.com/unity/blog/featured_posts/?rss=1#4',1,50,'non,memb',0),(31,'video','960px','','_Video',1,0,'ViewFile','',1,50,'non,memb',380),(32,'video','960px','','_Rate',2,1,'Rate','',1,50,'non,memb',0),(33,'video','960px','','_Actions',1,1,'ActionList','',1,50,'non,memb',0),(34,'video','960px','','_View Comments',1,2,'ViewComments','',1,50,'non,memb',0),(35,'video','960px','','_Video Info',2,0,'FileInfo','',1,50,'non,memb',0),(36,'video','960px','','_Latest files from this user',2,2,'LastFiles','',1,50,'non,memb',0),(37,'video','960px','','_BoonEx News',0,0,'RSS','http://www.boonex.com/unity/blog/featured_posts/?rss=1#4',1,50,'non,memb',0),(38,'photo','960px','','_Photo',1,0,'ViewFile','',1,50,'non,memb',380),(39,'photo','960px','','_Rate',2,1,'Rate','',1,50,'non,memb',0),(40,'photo','960px','','_Actions',1,1,'ActionList','',1,50,'non,memb',0),(41,'photo','960px','','_View Comments',1,2,'ViewComments','',1,50,'non,memb',0),(42,'photo','960px','','_Photo Info',2,0,'FileInfo','',1,50,'non,memb',0),(43,'photo','960px','','_Latest files from this user',2,2,'LastFiles','',1,50,'non,memb',0),(44,'photo','960px','','_BoonEx News',0,0,'RSS','http://www.boonex.com/unity/blog/featured_posts/?rss=1#4',1,50,'non,memb',0),(45,'ads','960px','','_Advertisement Photos',1,0,'AdPhotos','',1,50,'non,memb',0),(46,'ads','960px','','_Actions',1,1,'ActionList','',1,50,'non,memb',0),(47,'ads','960px','','_Comments',1,2,'ViewComments','',1,50,'non,memb',0),(48,'ads','960px','','_Info',2,0,'AdInfo','',1,50,'non,memb',0),(49,'ads','960px','','_Description',2,1,'Description','',1,50,'non,memb',0),(50,'ads','960px','','_Users Other Listing',2,2,'UserOtherAds','',1,50,'non,memb',0),(51,'ads','960px','','_BoonEx News',0,0,'RSS','http://www.boonex.com/unity/blog/featured_posts/?rss=1#4',1,50,'non,memb',0),(52,'member','960px','','_Site Stats',1,1,'SiteStats','',1,50,'non,memb',0),(53,'member','960px','','_member info',2,0,'MemberInfo','',1,50,'non,memb',0),(54,'member','960px','','_contacts',2,1,'Contacts','',1,50,'non,memb',0),(55,'member','960px','','_latest news',2,2,'News','',1,50,'non,memb',0),(56,'member','960px','','_BoonEx News',0,0,'RSS','http://www.boonex.com/unity/blog/featured_posts/?rss=1#4',1,50,'non,memb',0),(57,'member','960px','Classifieds','_Classifieds',1,0,'Classifieds','',1,50,'non,memb',0),(58,'member','960px','Events','_Events',1,2,'Events','',1,50,'non,memb',0),(59,'member','960px','Groups','_Groups',1,3,'Groups','',1,50,'non,memb',0),(60,'member','960px','','_Forum Posts',2,3,'RSS','{SiteUrl}orca/?action=rss_user&user={NickName}#4',1,50,'non,memb',0),(61,'member','960px','','_My Music Gallery',2,4,'ShareMusic','',1,50,'non,memb',0),(62,'member','960px','','_My Photo Gallery',2,5,'SharePhotos','',1,50,'non,memb',0),(63,'member','960px','','_My Video Gallery',2,6,'ShareVideos','',1,50,'non,memb',0),(64,'profile','960px','Member polls block','_Polls',1,4,'ProfilePolls','',1,50,'non,memb',0),(65,'profile','960px','Actions that other members can do','_Actions',1,0,'ActionsMenu','',1,50,'non,memb',0),(66,'profile','960px','Profile rating form','_rate profile',2,4,'RateProfile','',1,50,'non,memb',0),(67,'profile','960px','Member friends list','_Friends',2,6,'Friends','',1,50,'non,memb',0),(68,'profile','960px','Comments on member profile','_profile_comments',2,10,'Cmts','',1,50,'non,memb',0),(69,'profile','960px','Member blog block','_Blog',2,5,'Blog','',1,50,'non,memb',0),(70,'profile','960px','Profile Mp3 Player','_ProfileMp3',2,8,'Mp3','',1,50,'non,memb',0),(71,'profile','960px','Last posts of a member in the forum','_Forum Posts',2,9,'RSS','{SiteUrl}orca/?action=rss_user&user={NickName}#4',1,50,'non,memb',0),(72,'profile','960px','','_BoonEx News',0,0,'RSS','http://www.boonex.com/unity/blog/featured_posts/?rss=1#4',1,50,'non,memb',0),(73,'profile','960px','Classifieds','_Classifieds',1,1,'Classifieds','',1,50,'non,memb',0),(74,'profile','960px','Events','_Events',1,2,'Events','',1,50,'non,memb',0),(75,'profile','960px','Groups','_Groups',1,3,'Groups','',1,50,'non,memb',0),(76,'profile','960px','Music Shared By The Member','_Music Gallery',1,5,'ShareMusic','',1,50,'non,memb',0),(77,'profile','960px','Photos Shared By The Member','_Photo Gallery',1,6,'SharePhotos','',1,50,'non,memb',0),(78,'profile','960px','Videos Shared By The Member','_Video Gallery',1,7,'ShareVideos','',1,50,'non,memb',0),(79,'profile','960px','Mutual friends of viewing and viewed members','_Mutual Friends',2,7,'MutualFriends','',1,50,'non,memb',0),(80,'profile','960px','Profile Fields Block','_FieldCaption_General Info_View',2,1,'PFBlock','17',1,50,'non,memb',0),(81,'profile','960px','Profile Fields Block','_FieldCaption_Misc Info_View',2,2,'PFBlock','20',1,50,'non,memb',0),(82,'profile','960px','Profile Fields Block','_FieldCaption_Admin Controls_View',2,0,'PFBlock','21',1,50,'non,memb',0),(83,'profile','960px','Profile Fields Block','_FieldCaption_Description_View',2,3,'PFBlock','22',1,50,'non,memb',0),(84,'profile','960px','Profile Fields Block','_FieldCaption_Security Image_View',0,0,'PFBlock','25',1,50,'non,memb',0),(85,'profile','960px','Profile Fields Block','_FieldCaption_Profile Type_View',0,0,'PFBlock','30',1,50,'non,memb',0),(86,'','960px','RSS Feed','_RSS Feed',0,0,'Sample','RSS',1,0,'non,memb',0),(87,'','960px','Simple HTML Block','_HTML Block',0,0,'Sample','Echo',1,0,'non,memb',0),(88,'member','960px','Member Friends','_My Friends',1,4,'Friends','',1,50,'memb',0);
/*!40000 ALTER TABLE `PageCompose` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PaymentParameters`
--

DROP TABLE IF EXISTS `PaymentParameters`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `PaymentParameters` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `IDProvider` smallint(6) unsigned NOT NULL default '0',
  `Name` varchar(255) NOT NULL default '',
  `Caption` varchar(255) default NULL,
  `Type` enum('check','enum','text') NOT NULL default 'text',
  `Extra` text,
  `Value` text NOT NULL,
  `Changable` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `ParamName` (`IDProvider`,`Name`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `PaymentParameters`
--

LOCK TABLES `PaymentParameters` WRITE;
/*!40000 ALTER TABLE `PaymentParameters` DISABLE KEYS */;
INSERT INTO `PaymentParameters` VALUES (1,1,'business','Business','text',NULL,'',1),(2,1,'process_type','Process type','enum','\'Direct\',\'PDT\',\'IPN\'','IPN',1),(3,1,'connection_type','Connection type','enum','\'SSL\',\'HTTP\'','SSL',1),(4,1,'auth_token','Identity token','text',NULL,'',1),(5,1,'no_note','Don\'t prompt customer to include a note','check',NULL,'on',1),(6,1,'test_business','SandBox Business','text',NULL,'',1),(7,2,'sid','Account number','text',NULL,'',1),(8,2,'pay_method','Pay method','enum','\'CC\',\'CK\'','CC',1),(9,2,'secret_word','Secret word','text',NULL,'',1),(10,3,'x_login','Login','text',NULL,'',1),(11,3,'x_tran_key','Transaction key','text',NULL,'',1),(12,3,'implementation','Implementation','enum','\'SIM\',\'AIM\'','AIM',1),(13,3,'x_delim_char','Delimiter char','text',NULL,';',0),(14,3,'x_encap_char','Encapsulate char','text',NULL,'|',0),(15,3,'curl_binary','cURL binary','text',NULL,'',1),(16,3,'md5_hash_value','MD5 Hash','text',NULL,'',1),(17,4,'client_accnum','Account number','text',NULL,'',1),(18,4,'client_subacc','Subaccount number','text',NULL,'',1),(19,4,'form_name','Form name','text',NULL,'',1),(20,4,'allowed_types','Allowed types','text',NULL,'',1),(21,4,'subscription_type_id','Subscription type id','text',NULL,'',1);
/*!40000 ALTER TABLE `PaymentParameters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PaymentProviders`
--

DROP TABLE IF EXISTS `PaymentProviders`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `PaymentProviders` (
  `ID` smallint(6) unsigned NOT NULL auto_increment,
  `Name` varchar(30) NOT NULL default '',
  `Caption` varchar(50) NOT NULL default '',
  `Active` tinyint(1) unsigned NOT NULL default '0',
  `Mode` enum('live','test-approve','test-decline') NOT NULL default 'live',
  `Debug` tinyint(1) unsigned NOT NULL default '0',
  `CheckoutFilename` varchar(255) NOT NULL default '',
  `CheckoutURL` varchar(255) NOT NULL default '',
  `SupportsRecurring` tinyint(1) unsigned NOT NULL default '0',
  `LogoFilename` varchar(100) default NULL,
  `Help` text,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `PaymentProviders`
--

LOCK TABLES `PaymentProviders` WRITE;
/*!40000 ALTER TABLE `PaymentProviders` DISABLE KEYS */;
INSERT INTO `PaymentProviders` VALUES (1,'paypal','PayPal',1,'live',0,'','',1,'paypal.gif','<p class=\"help_caption\">Parameters description:</p>\r\n\r\n<p class=\"help_text\"><b>Business</b> - your live PayPal account ID. This ID will be used if module \r\nis in live mode.</p>\r\n\r\n<p class=\"help_text\"><b>Process type</b> - Direct, PDT or IPN. See configuration description below \r\nfor details.</p>\r\n\r\n<p class=\"help_text\"><b>Connection type</b> - SSL or HTTP. This parameter defines validation \r\nback-connection method to the PayPal gateway. SSL is more safe and secure, but \r\nit could be unsupported by your server. If SSL is not supported by your server, \r\nuse HTTP connection type instead.</p>\r\n\r\n<p class=\"help_text\"><b>Identity token</b> - your account\'s identification token which is used for \r\ntransaction validation in PDT process type. You can obtain it on your PayPal \r\naccount by enabling Payment Data Transfer (<b>My Account</b> -&gt; <b>Profile</b> \r\n-&gt; <b>Website Payment Preferences</b> -&gt; <b>Payment Data Transfer</b>)</p>\r\n\r\n<p class=\"help_text\"><b>Don\'t prompt customer to include a note</b> - indicates should PayPal \r\ngateway prompt customer to write payment note or not. This note could be found \r\nin transaction info hint in finance calculator of admin panel later.</p>\r\n\r\n<p class=\"help_text\"><b>SandBox Business</b> - your test PayPal SandBox account ID. This ID will \r\nbe used if module is in test-approve or test-decline mode.</p>\r\n\r\n<p class=\"help_caption\">Configuration description:</p>\r\n\r\n<p class=\"help_text\">Your PayPal account configuration settings depend on <b>Process type</b> \r\nparameter value:</p>\r\n\r\n<p class=\"help_text\"><b>Direct.</b> In this payment process type script sends payment info to the \r\nPayPal gateway, then PayPal redirects you to script\'s payment page, which checks \r\nif payment was successful and makes appropriate data changing. After payment \r\ncheck script shows you payment result. For this payment type you don\'t need to \r\nmake any PayPal account configuration. One thing you should know is if you \r\ndecide to enable Auto-Return option you should specify your PayPal module \r\nlocation as return URL (by default it\'s paypal.php in your script\'s checkout \r\ndirectory). <b>Note:</b> this process type couldn\'t be used for recurring \r\nbillings.</p>\r\n\r\n<p class=\"help_text\"><b>PDT</b>. This process type is almost the same as Direct, except one \r\ndetail. PayPal doesn\'t send all transactions details to your script. It just \r\nsends transaction token, which is used along with identity token to obtain \r\ntransaction details in notify-synch request. For this process type you should \r\nenable Auto-Return option in your PayPal account (<b>My Account</b> -&gt; <b>\r\nProfile</b> -&gt; <b>Website Payment Preferences</b> -&gt; <b>Auto Return for Website \r\nPayments</b>), set Return URL to your PayPal module URL (by default it\'s \r\npaypal.php in your script\'s checkout directory), enable Payment Data Transfer (<b>My \r\nAccount</b> -&gt; <b>Profile</b> -&gt; <b>Website Payment Preferences</b> -&gt; <b>\r\nPayment Data Transfer</b>) and copy your Identity Token to appropriate field \r\n(see parameters description above).</p>\r\n\r\n<p class=\"help_text\"><b>IPN.</b> Instant Payment Notification process type differs from Direct and \r\nPDT process type. After payment script redirects you to member area without any \r\nresult message. PayPal sends notification to payment module about any payment \r\nevent on the gateway. Disadvantage of this method is that there is no any result \r\nmessage after payment. You can only check payment result in fact, but this is \r\nonly way you can enable recurring billings for PayPal. Note: you should disable \r\nInstant Payment Notification in your PayPal account (<b>My Account</b> -&gt; <b>\r\nProfile</b> -&gt; <b>Instant Payment Notification Preferences</b>), as payment \r\nmodule sends notification request to PayPal gateway by itself. If you decide to \r\nenable Auto-Return option you should specify your PayPal module location as \r\nreturn URL (by default it\'s paypal.php in your script\'s checkout directory).</p>'),(2,'2checkoutv2','2Checkout.com v2',1,'live',0,'','',0,'2checkout.gif','<p class=\"help_caption\">Parameters description:</p>\r\n\r\n<p class=\"help_text\"><b>Account number</b> - your 2checkout vendor account number.</p>\r\n\r\n<p class=\"help_text\"><b>Pay method</b> - CC for Credit Card or CK for check (Online checks must \r\nbe enabled within your account first!). This will select the payment method during the checkout \r\nprocess.</p>\r\n\r\n<p class=\"help_text\"><b>Secret word</b> - it is used to check the MD5 hash passback. You can set \r\nit up on your account (<b>Helpful Links</b> -&gt; <b>Look and Feel</b> -&gt; <b>Your Secret \r\nWord</b>)</p>\r\n\r\n<p class=\"help_caption\">Configuration description:</p>\r\n\r\n<p class=\"help_text\">Login to your account, under the \"Helpful Links\" section click on \"Settings\" \r\nnear the \"Look and Feel\" section, input 2Checkout module \r\nlocation (by default it\'s 2checkoutv2.php in your script\'s checkout \r\ndirectory) into the Approved URL box and URL of member area (http://yoursite.com/member.php \r\nfor example) into the Pending URL box, click \"Save changes\"</p>'),(3,'authorizenet','Authorize.Net',1,'live',0,'','',0,'authorizenet.gif','<p class=\"help_caption\">Parameters description:</p>\r\n\r\n<p class=\"help_text\"><b>Login</b> - your Authorize.Net login.</p>\r\n\r\n<p class=\"help_text\"><b>Transaction key</b> - transaction key which should be obtained from \r\nMerchant Interface (<b>Settings</b> -&gt; <b>Security section</b> -&gt; <b>Obtain Transaction \r\nKey</b>).</p>\r\n\r\n<p class=\"help_text\"><b>Implementation</b> - determs payment mechanism. If SIM value selected, \r\nscript will redirect customer to payment gateway and then handle response from the Authorize.Net \r\nserver. If AIM value selected, then script will prompt customer to enter credit card details and \r\nsend them to Authorize.Net gateway without any redirections.</p>\r\n\r\n<p class=\"help_text\"><b>cURL binary</b> - full path to the curl binary including filename itself \r\n(i.e. /usr/bin/curl). This value used if cURL extension is not installed on your server.</p>\r\n\r\n<p class=\"help_text\"><b>MD5 Hash</b> - it is used to check the MD5 hash passback. You can set \r\nit up on your account (<b>Settings</b> -&gt; <b>Security section</b> -&gt; <b>MD5 Hash</b>)</p>'),(4,'ccbill','CCBill',1,'live',0,'','',0,'ccbill.gif','<p class=\"help_caption\">No instructions available yet</p>');
/*!40000 ALTER TABLE `PaymentProviders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PaymentSubscriptions`
--

DROP TABLE IF EXISTS `PaymentSubscriptions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `PaymentSubscriptions` (
  `TransactionID` bigint(20) unsigned NOT NULL default '0',
  `StartDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `Period` smallint(5) unsigned NOT NULL default '0',
  `ChargesNumber` int(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`TransactionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `PaymentSubscriptions`
--

LOCK TABLES `PaymentSubscriptions` WRITE;
/*!40000 ALTER TABLE `PaymentSubscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `PaymentSubscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PreValues`
--

DROP TABLE IF EXISTS `PreValues`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `PreValues` (
  `Key` varchar(255) NOT NULL default '' COMMENT 'Key which defines link to values list',
  `Value` varchar(255) NOT NULL default '' COMMENT 'Simple value stored in the database',
  `Order` int(10) unsigned NOT NULL default '0',
  `LKey` varchar(255) NOT NULL default '' COMMENT 'Primary language key used for displaying this value',
  `LKey2` varchar(255) NOT NULL default '' COMMENT 'Additional key used in some other places',
  `LKey3` varchar(255) NOT NULL default '',
  `Extra` varchar(255) NOT NULL default '' COMMENT 'Some extra values. For example image link for sex',
  `Extra2` varchar(255) NOT NULL default '',
  `Extra3` varchar(255) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `PreValues`
--

LOCK TABLES `PreValues` WRITE;
/*!40000 ALTER TABLE `PreValues` DISABLE KEYS */;
INSERT INTO `PreValues` VALUES ('Country','TR',213,'__Turkey','','','','',''),('Country','TT',214,'__Trinidad and Tobago','','','','',''),('Country','TO',212,'__Tonga','','','','',''),('Country','TN',211,'__Tunisia','','','','',''),('Country','TM',210,'__Turkmenistan','','','','',''),('Country','TL',209,'__East Timor','','','','',''),('Country','TK',208,'__Tokelau','','','','',''),('Country','TJ',207,'__Tajikistan','','','','',''),('Country','TH',206,'__Thailand','','','','',''),('Country','TG',205,'__Togo','','','','',''),('Country','TF',204,'__French Southern and Antarctic Lands','','','','',''),('Country','TD',203,'__Chad','','','','',''),('Country','TC',202,'__Turks and Caicos Islands','','','','',''),('Country','AI',201,'__Anguilla','','','','',''),('Country','BI',200,'__Burundi','','','','',''),('Country','BZ',199,'__Belize','','','','',''),('Country','CM',198,'__Cameroon','','','','',''),('Country','CZ',197,'__Czech Republic','','','','',''),('Country','FR',196,'__France','','','','',''),('Country','GI',195,'__Gibraltar','','','','',''),('Country','GQ',194,'__Equatorial Guinea','','','','',''),('Country','GR',193,'__Greece','','','','',''),('Country','KE',191,'__Kenya','','','','',''),('Country','HM',192,'__Heard Island and McDonald Islands','','','','',''),('Country','KG',190,'__Kyrgyzstan','','','','',''),('Country','KM',189,'__Comoros','','','','',''),('Country','KW',188,'__Kuwait','','','','',''),('Country','LB',187,'__Lebanon','','','','',''),('Country','MK',186,'__Macedonia, The Former Yugoslav Republic of','','','','',''),('Country','MO',185,'__Macao','','','','',''),('Country','MV',184,'__Maldives','','','','',''),('Country','MY',183,'__Malaysia','','','','',''),('Country','NU',182,'__Niue','','','','',''),('Country','OM',181,'__Oman','','','','',''),('Country','RO',180,'__Romania','','','','',''),('Country','AD',179,'__Andorra','','','','',''),('Country','AE',178,'__United Arab Emirates','','','','',''),('Country','AF',177,'__Afghanistan','','','','',''),('Country','AG',176,'__Antigua and Barbuda','','','','',''),('Country','AL',175,'__Albania','','','','',''),('Country','AM',174,'__Armenia','','','','',''),('Country','AN',173,'__Netherlands Antilles','','','','',''),('Country','AO',172,'__Angola','','','','',''),('Country','AQ',171,'__Antarctica','','','','',''),('Country','AS',170,'__American Samoa','','','','',''),('Country','AR',169,'__Argentina','','','','',''),('Country','AT',168,'__Austria','','','','',''),('Country','AW',166,'__Aruba','','','','',''),('Country','AU',167,'__Australia','','','','',''),('Country','AZ',165,'__Azerbaijan','','','','',''),('Country','BB',164,'__Barbados','','','','',''),('Country','BA',163,'__Bosnia and Herzegovina','','','','',''),('Country','BD',162,'__Bangladesh','','','','',''),('Country','BE',161,'__Belgium','','','','',''),('Country','BF',160,'__Burkina Faso','','','','',''),('Country','BG',159,'__Bulgaria','','','','',''),('Country','BH',158,'__Bahrain','','','','',''),('Country','BJ',157,'__Benin','','','','',''),('Country','BM',156,'__Bermuda','','','','',''),('Country','BN',155,'__Brunei Darussalam','','','','',''),('Country','BO',154,'__Bolivia','','','','',''),('Country','BR',153,'__Brazil','','','','',''),('Country','BS',152,'__The Bahamas','','','','',''),('Country','BT',151,'__Bhutan','','','','',''),('Country','BV',150,'__Bouvet Island','','','','',''),('Country','BY',148,'__Belarus','','','','',''),('Country','BW',149,'__Botswana','','','','',''),('Country','CA',147,'__Canada','','','','',''),('Country','CC',146,'__Cocos (Keeling) Islands','','','','',''),('Country','CD',145,'__Congo, Democratic Republic of the','','','','',''),('Country','CF',144,'__Central African Republic','','','','',''),('Country','CG',143,'__Congo, Republic of the','','','','',''),('Country','CH',142,'__Switzerland','','','','',''),('Country','CI',141,'__Cote d\'Ivoire','','','','',''),('Country','CK',140,'__Cook Islands','','','','',''),('Country','CL',139,'__Chile','','','','',''),('Country','CN',138,'__China','','','','',''),('Country','CO',137,'__Colombia','','','','',''),('Country','CR',136,'__Costa Rica','','','','',''),('Country','CU',135,'__Cuba','','','','',''),('Country','CX',133,'__Christmas Island','','','','',''),('Country','CV',134,'__Cape Verde','','','','',''),('Country','CY',132,'__Cyprus','','','','',''),('Country','DJ',131,'__Djibouti','','','','',''),('Country','DE',130,'__Germany','','','','',''),('Country','DK',129,'__Denmark','','','','',''),('Country','DM',128,'__Dominica','','','','',''),('Country','DO',127,'__Dominican Republic','','','','',''),('Country','DZ',126,'__Algeria','','','','',''),('Country','EC',125,'__Ecuador','','','','',''),('Country','EE',124,'__Estonia','','','','',''),('Country','EG',123,'__Egypt','','','','',''),('Country','EH',122,'__Western Sahara','','','','',''),('Country','ER',121,'__Eritrea','','','','',''),('Country','ES',120,'__Spain','','','','',''),('Country','ET',119,'__Ethiopia','','','','',''),('Country','FI',118,'__Finland','','','','',''),('Country','FJ',117,'__Fiji','','','','',''),('Country','FK',116,'__Falkland Islands (Islas Malvinas)','','','','',''),('Country','FM',115,'__Micronesia, Federated States of','','','','',''),('Country','FO',114,'__Faroe Islands','','','','',''),('Country','GA',113,'__Gabon','','','','',''),('Country','GB',112,'__United Kingdom','','','','',''),('Country','GD',111,'__Grenada','','','','',''),('Country','GE',110,'__Georgia','','','','',''),('Country','GF',109,'__French Guiana','','','','',''),('Country','GL',107,'__Greenland','','','','',''),('Country','GH',108,'__Ghana','','','','',''),('Country','GM',106,'__The Gambia','','','','',''),('Country','GP',104,'__Guadeloupe','','','','',''),('Country','GN',105,'__Guinea','','','','',''),('Country','GS',103,'__South Georgia and the South Sandwich Islands','','','','',''),('Country','GT',102,'__Guatemala','','','','',''),('Country','GU',101,'__Guam','','','','',''),('Country','GW',100,'__Guinea-Bissau','','','','',''),('Country','GY',99,'__Guyana','','','','',''),('Country','HK',98,'__Hong Kong (SAR)','','','','',''),('Country','HN',97,'__Honduras','','','','',''),('Country','HR',96,'__Croatia','','','','',''),('Country','HT',95,'__Haiti','','','','',''),('Country','HU',94,'__Hungary','','','','',''),('Country','ID',93,'__Indonesia','','','','',''),('Country','IE',92,'__Ireland','','','','',''),('Country','IL',91,'__Israel','','','','',''),('Country','IN',90,'__India','','','','',''),('Country','IO',89,'__British Indian Ocean Territory','','','','',''),('Country','IQ',88,'__Iraq','','','','',''),('Country','IR',87,'__Iran','','','','',''),('Country','IS',86,'__Iceland','','','','',''),('Country','JM',84,'__Jamaica','','','','',''),('Country','IT',85,'__Italy','','','','',''),('Country','JO',83,'__Jordan','','','','',''),('Country','JP',82,'__Japan','','','','',''),('Country','KH',81,'__Cambodia','','','','',''),('Country','KI',80,'__Kiribati','','','','',''),('Country','KN',79,'__Saint Kitts and Nevis','','','','',''),('Country','KP',78,'__Korea, North','','','','',''),('Country','KR',77,'__Korea, South','','','','',''),('Country','TV',215,'__Tuvalu','','','','',''),('Country','KY',76,'__Cayman Islands','','','','',''),('Country','KZ',75,'__Kazakhstan','','','','',''),('Country','LA',74,'__Laos','','','','',''),('Country','LC',73,'__Saint Lucia','','','','',''),('Country','LK',72,'__Sri Lanka','','','','',''),('Country','LI',71,'__Liechtenstein','','','','',''),('Country','LR',70,'__Liberia','','','','',''),('Country','LS',69,'__Lesotho','','','','',''),('Country','LT',68,'__Lithuania','','','','',''),('Country','LV',67,'__Latvia','','','','',''),('Country','LU',66,'__Luxembourg','','','','',''),('Country','LY',65,'__Libya','','','','',''),('Country','MA',64,'__Morocco','','','','',''),('Country','MC',63,'__Monaco','','','','',''),('Country','MD',62,'__Moldova','','','','',''),('Country','MG',61,'__Madagascar','','','','',''),('Country','MH',60,'__Marshall Islands','','','','',''),('Country','ML',59,'__Mali','','','','',''),('Country','MM',58,'__Burma','','','','',''),('Country','MN',57,'__Mongolia','','','','',''),('Country','MP',56,'__Northern Mariana Islands','','','','',''),('Country','MR',55,'__Mauritania','','','','',''),('Country','MQ',54,'__Martinique','','','','',''),('Country','MS',53,'__Montserrat','','','','',''),('Country','MT',52,'__Malta','','','','',''),('Country','MU',51,'__Mauritius','','','','',''),('Country','MW',50,'__Malawi','','','','',''),('Country','MX',49,'__Mexico','','','','',''),('Country','MZ',48,'__Mozambique','','','','',''),('Country','NA',47,'__Namibia','','','','',''),('Country','NC',46,'__New Caledonia','','','','',''),('Country','NE',45,'__Niger','','','','',''),('Country','NF',44,'__Norfolk Island','','','','',''),('Country','NG',43,'__Nigeria','','','','',''),('Country','NI',42,'__Nicaragua','','','','',''),('Country','NL',41,'__Netherlands','','','','',''),('Country','NO',40,'__Norway','','','','',''),('Country','NP',39,'__Nepal','','','','',''),('Country','NR',38,'__Nauru','','','','',''),('Country','NZ',37,'__New Zealand','','','','',''),('Country','PA',36,'__Panama','','','','',''),('Country','PE',35,'__Peru','','','','',''),('Country','PF',34,'__French Polynesia','','','','',''),('Country','PG',33,'__Papua New Guinea','','','','',''),('Country','PH',32,'__Philippines','','','','',''),('Country','PL',31,'__Poland','','','','',''),('Country','PK',30,'__Pakistan','','','','',''),('Country','PM',29,'__Saint Pierre and Miquelon','','','','',''),('Country','PN',28,'__Pitcairn Islands','','','','',''),('Country','PR',27,'__Puerto Rico','','','','',''),('Country','PS',26,'__Palestinian Territory, Occupied','','','','',''),('Country','PT',25,'__Portugal','','','','',''),('Country','PW',24,'__Palau','','','','',''),('Country','PY',23,'__Paraguay','','','','',''),('Country','QA',22,'__Qatar','','','','',''),('Country','RE',21,'__Reunion','','','','',''),('Country','RU',20,'__Russia','','','','',''),('Country','RW',19,'__Rwanda','','','','',''),('Country','SA',18,'__Saudi Arabia','','','','',''),('Country','SB',17,'__Solomon Islands','','','','',''),('Country','SC',16,'__Seychelles','','','','',''),('Country','SD',15,'__Sudan','','','','',''),('Country','SE',14,'__Sweden','','','','',''),('Country','SG',13,'__Singapore','','','','',''),('Country','SH',12,'__Saint Helena','','','','',''),('Country','SI',11,'__Slovenia','','','','',''),('Country','SJ',10,'__Svalbard','','','','',''),('Country','SK',9,'__Slovakia','','','','',''),('Country','SL',8,'__Sierra Leone','','','','',''),('Country','SM',7,'__San Marino','','','','',''),('Country','SN',6,'__Senegal','','','','',''),('Country','SO',5,'__Somalia','','','','',''),('Country','SR',4,'__Suriname','','','','',''),('Country','ST',3,'__Sao Tome and Principe','','','','',''),('Country','SV',2,'__El Salvador','','','','',''),('Country','SY',1,'__Syria','','','','',''),('Country','SZ',0,'__Swaziland','','','','',''),('Country','TW',216,'__Taiwan','','','','',''),('Country','TZ',217,'__Tanzania','','','','',''),('Country','UA',218,'__Ukraine','','','','',''),('Country','UG',219,'__Uganda','','','','',''),('Country','UM',220,'__United States Minor Outlying Islands','','','','',''),('Country','US',221,'__United States','','','','',''),('Country','UY',222,'__Uruguay','','','','',''),('Country','UZ',223,'__Uzbekistan','','','','',''),('Country','VA',224,'__Holy See (Vatican City)','','','','',''),('Country','VC',225,'__Saint Vincent and the Grenadines','','','','',''),('Country','VE',226,'__Venezuela','','','','',''),('Country','VG',227,'__British Virgin Islands','','','','',''),('Country','VI',228,'__Virgin Islands','','','','',''),('Country','VN',229,'__Vietnam','','','','',''),('Country','VU',230,'__Vanuatu','','','','',''),('Country','WF',231,'__Wallis and Futuna','','','','',''),('Country','WS',232,'__Samoa','','','','',''),('Country','YE',233,'__Yemen','','','','',''),('Country','YT',234,'__Mayotte','','','','',''),('Country','YU',235,'__Yugoslavia','','','','',''),('Country','ZA',236,'__South Africa','','','','',''),('Country','ZM',237,'__Zambia','','','','',''),('Country','ZW',238,'__Zimbabwe','','','','',''),('Sex','female',1,'_Female','_LookinFemale','','','',''),('Sex','male',0,'_Male','_LookinMale','','','',''),('Height','1',1,'__4\'7\" (140cm) or below','','','','',''),('Height','2',2,'__4\'8\" - 4\'11\" (141-150cm)','','','','',''),('Height','3',3,'__5\'0\" - 5\'3\" (151-160cm)','','','','',''),('Height','4',4,'__5\'4\" - 5\'7\" (161-170cm)','','','','',''),('Height','5',5,'__5\'8\" - 5\'11\" (171-180cm)','','','','',''),('Height','6',6,'__6\'0\" - 6\'3\" (181-190cm)','','','','',''),('Height','7',7,'__6\'4\" (191cm) or above','','','','',''),('BodyType','1',1,'__Average','','','','',''),('BodyType','2',2,'__Ample','','','','',''),('BodyType','3',3,'__Athletic','','','','',''),('BodyType','4',4,'__Cuddly','','','','',''),('BodyType','5',5,'__Slim','','','','',''),('BodyType','6',6,'__Very Cuddly','','','','',''),('Religion','1',1,'__Adventist','','','','',''),('Religion','2',2,'__Agnostic','','','','',''),('Religion','3',3,'__Atheist','','','','',''),('Religion','4',4,'__Baptist','','','','',''),('Religion','5',5,'__Buddhist','','','','',''),('Religion','6',6,'__Caodaism','','','','',''),('Religion','7',7,'__Catholic','','','','',''),('Religion','8',8,'__Christian','','','','',''),('Religion','9',9,'__Hindu','','','','',''),('Religion','10',10,'__Iskcon','','','','',''),('Religion','11',11,'__Jainism','','','','',''),('Religion','12',12,'__Jewish','','','','',''),('Religion','13',13,'__Methodist','','','','',''),('Religion','14',14,'__Mormon','','','','',''),('Religion','15',15,'__Moslem','','','','',''),('Religion','16',16,'__Orthodox','','','','',''),('Religion','17',17,'__Pentecostal','','','','',''),('Religion','18',18,'__Protestant','','','','',''),('Religion','19',19,'__Quaker','','','','',''),('Religion','20',20,'__Scientology','','','','',''),('Religion','21',21,'__Shinto','','','','',''),('Religion','22',22,'__Sikhism','','','','',''),('Religion','23',23,'__Spiritual','','','','',''),('Religion','24',24,'__Taoism','','','','',''),('Religion','25',25,'__Wiccan','','','','',''),('Religion','26',26,'__Other','','','','',''),('Ethnicity','1',1,'__African','','','','',''),('Ethnicity','2',2,'__African American','','','','',''),('Ethnicity','3',3,'__Asian','','','','',''),('Ethnicity','4',4,'__Caucasian','','','','',''),('Ethnicity','5',5,'__East Indian','','','','',''),('Ethnicity','6',6,'__Hispanic','','','','',''),('Ethnicity','7',7,'__Indian','','','','',''),('Ethnicity','8',8,'__Latino','','','','',''),('Ethnicity','9',9,'__Mediterranean','','','','',''),('Ethnicity','10',10,'__Middle Eastern','','','','',''),('Ethnicity','11',11,'__Mixed','','','','',''),('MaritalStatus','1',1,'__Single','','','','',''),('MaritalStatus','2',2,'__Attached','','','','',''),('MaritalStatus','3',3,'__Divorced','','','','',''),('MaritalStatus','4',4,'__Married','','','','',''),('MaritalStatus','5',5,'__Separated','','','','',''),('MaritalStatus','6',6,'__Widow','','','','',''),('Language','0',0,'__English','','','','',''),('Language','1',1,'__Afrikaans','','','','',''),('Language','2',2,'__Arabic','','','','',''),('Language','3',3,'__Bulgarian','','','','',''),('Language','4',4,'__Burmese','','','','',''),('Language','5',5,'__Cantonese','','','','',''),('Language','6',6,'__Croatian','','','','',''),('Language','7',7,'__Danish','','','','',''),('Language','8',8,'__Dutch','','','','',''),('Language','9',9,'__Esperanto','','','','',''),('Language','10',10,'__Estonian','','','','',''),('Language','11',11,'__Finnish','','','','',''),('Language','12',12,'__French','','','','',''),('Language','13',13,'__German','','','','',''),('Language','14',14,'__Greek','','','','',''),('Language','15',15,'__Gujrati','','','','',''),('Language','16',16,'__Hebrew','','','','',''),('Language','17',17,'__Hindi','','','','',''),('Language','18',18,'__Hungarian','','','','',''),('Language','19',19,'__Icelandic','','','','',''),('Language','20',20,'__Indian','','','','',''),('Language','21',21,'__Indonesian','','','','',''),('Language','22',22,'__Italian','','','','',''),('Language','23',23,'__Japanese','','','','',''),('Language','24',24,'__Korean','','','','',''),('Language','25',25,'__Latvian','','','','',''),('Language','26',26,'__Lithuanian','','','','',''),('Language','27',27,'__Malay','','','','',''),('Language','28',28,'__Mandarin','','','','',''),('Language','29',29,'__Marathi','','','','',''),('Language','30',30,'__Moldovian','','','','',''),('Language','31',31,'__Nepalese','','','','',''),('Language','32',32,'__Norwegian','','','','',''),('Language','33',33,'__Persian','','','','',''),('Language','34',34,'__Polish','','','','',''),('Language','35',35,'__Portuguese','','','','',''),('Language','36',36,'__Punjabi','','','','',''),('Language','37',37,'__Romanian','','','','',''),('Language','38',38,'__Russian','','','','',''),('Language','39',39,'__Serbian','','','','',''),('Language','40',40,'__Spanish','','','','',''),('Language','41',41,'__Swedish','','','','',''),('Language','42',42,'__Tagalog','','','','',''),('Language','43',43,'__Taiwanese','','','','',''),('Language','44',44,'__Tamil','','','','',''),('Language','45',45,'__Telugu','','','','',''),('Language','46',46,'__Thai','','','','',''),('Language','47',47,'__Tongan','','','','',''),('Language','48',48,'__Turkish','','','','',''),('Language','49',49,'__Ukrainian','','','','',''),('Language','50',50,'__Urdu','','','','',''),('Language','51',51,'__Vietnamese','','','','',''),('Language','52',52,'__Visayan','','','','',''),('Education','1',1,'__High School graduate','','','','',''),('Education','2',2,'__Some college','','','','',''),('Education','3',3,'__College student','','','','',''),('Education','4',4,'__AA (2 years college)','','','','',''),('Education','5',5,'__BA/BS (4 years college)','','','','',''),('Education','6',6,'__Some grad school','','','','',''),('Education','7',7,'__Grad school student','','','','',''),('Education','8',8,'__MA/MS/MBA','','','','',''),('Education','9',9,'__PhD/Post doctorate','','','','',''),('Education','10',10,'__JD','','','','',''),('Income','1',1,'__$10,000/year and less','','','','',''),('Income','2',2,'__$10,000-$30,000/year','','','','',''),('Income','3',3,'__$30,000-$50,000/year','','','','',''),('Income','4',4,'__$50,000-$70,000/year','','','','',''),('Income','5',5,'__$70,000/year and more','','','','',''),('Smoker','1',1,'__No','','','','',''),('Smoker','2',2,'__Rarely','','','','',''),('Smoker','3',3,'__Often','','','','',''),('Smoker','4',4,'__Very often','','','','',''),('Drinker','1',1,'__No','','','','',''),('Drinker','2',2,'__Rarely','','','','',''),('Drinker','3',3,'__Often','','','','',''),('Drinker','4',4,'__Very often','','','','','');
/*!40000 ALTER TABLE `PreValues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PrivPhotosRequests`
--

DROP TABLE IF EXISTS `PrivPhotosRequests`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `PrivPhotosRequests` (
  `IDauto` int(10) unsigned NOT NULL auto_increment,
  `IDFrom` bigint(8) NOT NULL default '0',
  `IDTo` bigint(20) NOT NULL default '0',
  `Grant` int(11) NOT NULL default '0',
  `Hide` tinyint(4) NOT NULL default '0',
  `Arrived` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`IDauto`),
  KEY `IDFrom` (`IDFrom`),
  KEY `IDTo` (`IDTo`),
  KEY `IDFrom_2` (`IDFrom`,`IDTo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `PrivPhotosRequests`
--

LOCK TABLES `PrivPhotosRequests` WRITE;
/*!40000 ALTER TABLE `PrivPhotosRequests` DISABLE KEYS */;
/*!40000 ALTER TABLE `PrivPhotosRequests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ProfileFields`
--

DROP TABLE IF EXISTS `ProfileFields`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ProfileFields` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(255) character set utf8 collate utf8_bin NOT NULL default '',
  `Type` enum('text','area','pass','date','select_one','select_set','num','range','bool','system','block') NOT NULL default 'text',
  `Control` enum('select','checkbox','radio') NOT NULL default 'select' COMMENT 'input element for selectors',
  `Extra` text NOT NULL,
  `Min` float default NULL,
  `Max` float default NULL,
  `Values` text NOT NULL,
  `UseLKey` enum('LKey','LKey2','LKey3') NOT NULL default 'LKey',
  `Check` text NOT NULL,
  `Unique` tinyint(1) NOT NULL default '0',
  `Default` text NOT NULL,
  `Mandatory` tinyint(1) NOT NULL default '0',
  `Deletable` tinyint(1) NOT NULL default '1',
  `JoinPage` int(10) unsigned NOT NULL default '0',
  `JoinBlock` int(10) unsigned NOT NULL default '0',
  `JoinOrder` float default NULL,
  `EditOwnBlock` int(10) unsigned NOT NULL default '0',
  `EditOwnOrder` float default NULL,
  `EditAdmBlock` int(10) unsigned NOT NULL default '0',
  `EditAdmOrder` float default NULL,
  `EditModBlock` int(10) unsigned NOT NULL default '0',
  `EditModOrder` float default NULL,
  `ViewMembBlock` int(10) unsigned NOT NULL default '0',
  `ViewMembOrder` float default NULL,
  `ViewAdmBlock` int(10) unsigned NOT NULL default '0',
  `ViewAdmOrder` float default NULL,
  `ViewModBlock` int(10) unsigned NOT NULL default '0',
  `ViewModOrder` float default NULL,
  `ViewVisBlock` int(10) unsigned NOT NULL default '0',
  `ViewVisOrder` float default NULL,
  `SearchParams` text NOT NULL,
  `SearchSimpleBlock` int(10) unsigned NOT NULL default '0',
  `SearchSimpleOrder` float default NULL,
  `SearchQuickBlock` int(10) unsigned NOT NULL default '0',
  `SearchQuickOrder` float default NULL,
  `SearchAdvBlock` int(10) unsigned NOT NULL default '0',
  `SearchAdvOrder` float default NULL,
  `MatchField` int(10) unsigned NOT NULL default '0',
  `MatchPercent` tinyint(7) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ProfileFields`
--

LOCK TABLES `ProfileFields` WRITE;
/*!40000 ALTER TABLE `ProfileFields` DISABLE KEYS */;
INSERT INTO `ProfileFields` VALUES (1,'ID','system','select','',NULL,NULL,'','LKey','',1,'',0,0,0,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,17,1,17,1,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(2,'NickName','text','select','',4,16,'','LKey','return ( preg_match( \'/^[a-zA-Z0-9_-]+$/\', $arg0 ) and !file_exists( $dir[\'root\'] . $arg0 ) );',1,'',1,0,0,17,1,17,1,17,1,17,1,17,1,17,2,17,2,17,1,'',0,NULL,0,NULL,0,NULL,0,0),(3,'Password','pass','select','',5,16,'','LKey','',0,'',1,0,0,17,3,17,4,17,4,17,4,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(4,'Email','text','select','',6,NULL,'','LKey','return (bool)preg_match( \'/^[a-z0-9_\\-]+(\\.[_a-z0-9\\-]+)*@([_a-z0-9\\-]+\\.)+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)$/i\', $arg0 );',1,'',1,0,0,17,2,17,2,17,2,17,2,0,NULL,21,1,21,1,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(5,'DateReg','system','select','',NULL,NULL,'','LKey','',0,'',0,0,0,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,21,2,21,2,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(6,'DateLastEdit','system','select','',NULL,NULL,'','LKey','',0,'',0,0,0,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,21,4,21,4,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(7,'Status','system','select','',NULL,NULL,'Unconfirmed\nApproval\nActive\nRejected\nSuspended','LKey','',0,'',0,0,0,0,NULL,0,NULL,21,1,21,1,0,NULL,17,3,17,3,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(8,'DateLastLogin','system','select','',NULL,NULL,'','LKey','',0,'',0,0,0,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,21,3,21,3,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(9,'Featured','system','select','',NULL,NULL,'','LKey','',0,'',0,0,0,0,NULL,0,NULL,21,2,21,2,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(10,'Sex','select_one','radio','',NULL,NULL,'#!Sex','LKey','',0,'male',1,0,0,20,1,17,3,17,3,17,3,17,2,17,4,17,4,17,2,'',17,2,0,NULL,17,2,11,40),(11,'LookingFor','select_set','checkbox','',NULL,NULL,'#!Sex','LKey2','',0,'',0,0,0,20,2,20,1,20,1,20,1,17,3,17,5,17,5,17,3,'',0,NULL,0,NULL,0,NULL,10,40),(12,'DescriptionMe','area','select','',20,NULL,'','LKey','',0,'',1,0,0,20,5,20,4,20,4,20,4,22,2,22,2,22,2,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(13,'DateOfBirth','date','select','',18,75,'','LKey','',0,'',1,0,0,20,3,20,2,20,2,20,2,20,1,20,1,20,1,0,NULL,'',17,3,0,NULL,17,3,0,0),(14,'Headline','text','select','',NULL,NULL,'','LKey','',0,'',0,0,0,20,4,20,3,20,3,20,3,22,1,22,1,22,1,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(15,'Country','select_one','select','',NULL,NULL,'#!Country','LKey','',0,'US',0,0,0,20,6,20,5,20,5,20,5,20,2,20,2,20,2,20,1,'',20,1,0,NULL,20,1,15,20),(16,'City','text','select','',NULL,NULL,'','LKey','',0,'',0,0,0,20,7,20,6,20,6,20,6,20,3,20,3,20,3,20,2,'',0,NULL,0,NULL,20,2,0,0),(17,'General Info','block','select','',NULL,NULL,'','LKey','',0,'',0,1,0,0,2,0,1,0,2,0,2,0,1,0,2,0,1,0,1,'',0,1,0,NULL,0,1,0,0),(18,'Location','system','select','',NULL,NULL,'','LKey','',0,'',0,0,0,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,20,5,0,0),(19,'Keyword','system','select','DescriptionMe\nHeadline',NULL,NULL,'','LKey','',0,'',0,0,0,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',20,2,0,NULL,20,3,0,0),(20,'Misc Info','block','select','',NULL,NULL,'','LKey','',0,'',0,1,0,0,3,0,2,0,3,0,3,0,2,0,3,0,2,0,2,'',0,2,0,NULL,0,2,0,0),(21,'Admin Controls','block','select','',NULL,NULL,'','LKey','',0,'',0,1,0,0,NULL,0,NULL,0,1,0,1,0,NULL,0,5,0,4,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(22,'Description','block','select','',NULL,NULL,'','LKey','',0,'',0,1,0,0,NULL,0,NULL,0,NULL,0,NULL,0,3,0,4,0,3,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(23,'Couple','system','select','Country\nCity',NULL,NULL,'','LKey','',0,'',1,0,0,30,1,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',17,1,0,NULL,17,1,0,0),(24,'Captcha','system','select','',NULL,NULL,'','LKey','',0,'',1,0,0,25,1,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(25,'Security Image','block','select','',NULL,NULL,'','LKey','',0,'',0,1,0,0,4,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(30,'Profile Type','block','select','',NULL,NULL,'','LKey','',0,'',0,1,0,0,1,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(41,'EmailNotify','system','select','',NULL,NULL,'NotifyMe\nNotNotifyMe','LKey','',0,'',0,0,0,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(39,'zip','text','select','',NULL,NULL,'','LKey','',0,'',0,0,0,20,8,20,7,20,7,20,7,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(34,'DateLastNav','system','select','',NULL,NULL,'','LKey','',0,'',0,0,0,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(35,'PrimPhoto','system','select','',NULL,NULL,'','LKey','',0,'0',0,0,0,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(36,'Picture','system','select','',NULL,NULL,'','LKey','',0,'0',0,0,0,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(37,'aff_num','system','select','',NULL,NULL,'','LKey','',0,'0',0,0,0,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(38,'Tags','text','select','',NULL,NULL,'','LKey','',0,'',0,0,0,20,9,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,20,4,0,0),(42,'TermsOfUse','system','select','',NULL,NULL,'','LKey','',0,'',1,0,0,25,2,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0),(43,'Membership','system','select','',NULL,NULL,'','LKey','',0,'',0,0,0,0,NULL,0,NULL,21,3,21,3,0,NULL,0,NULL,0,NULL,0,NULL,'',0,NULL,0,NULL,0,NULL,0,0);
/*!40000 ALTER TABLE `ProfileFields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ProfileMemLevels`
--

DROP TABLE IF EXISTS `ProfileMemLevels`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ProfileMemLevels` (
  `IDMember` bigint(20) unsigned NOT NULL default '0',
  `IDLevel` smallint(5) unsigned NOT NULL default '0',
  `DateStarts` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateExpires` datetime default NULL,
  `TransactionID` bigint(20) unsigned default NULL,
  PRIMARY KEY  (`IDMember`,`IDLevel`,`DateStarts`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ProfileMemLevels`
--

LOCK TABLES `ProfileMemLevels` WRITE;
/*!40000 ALTER TABLE `ProfileMemLevels` DISABLE KEYS */;
/*!40000 ALTER TABLE `ProfileMemLevels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Profiles`
--

DROP TABLE IF EXISTS `Profiles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Profiles` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `NickName` varchar(255) NOT NULL default '',
  `Email` varchar(255) NOT NULL default '',
  `Password` varchar(32) NOT NULL default '',
  `Status` enum('Unconfirmed','Approval','Active','Rejected','Suspended') NOT NULL default 'Unconfirmed',
  `Couple` int(10) unsigned NOT NULL default '0',
  `Sex` varchar(255) NOT NULL default '',
  `LookingFor` set('male','female') NOT NULL default '',
  `Headline` varchar(255) NOT NULL default '',
  `DescriptionMe` text NOT NULL,
  `Country` varchar(255) NOT NULL default '',
  `City` varchar(255) NOT NULL default '',
  `DateOfBirth` date NOT NULL default '0000-00-00',
  `Featured` tinyint(1) NOT NULL default '0',
  `DateReg` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateLastEdit` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateLastLogin` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateLastNav` datetime NOT NULL default '0000-00-00 00:00:00',
  `PrimPhoto` int(10) unsigned NOT NULL default '0',
  `Picture` tinyint(1) NOT NULL default '0',
  `aff_num` int(10) unsigned NOT NULL default '0',
  `Tags` varchar(255) NOT NULL default '',
  `zip` varchar(255) NOT NULL default '',
  `EmailNotify` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `NickName` (`NickName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Profiles`
--

LOCK TABLES `Profiles` WRITE;
/*!40000 ALTER TABLE `Profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `Profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ProfilesMatch`
--

DROP TABLE IF EXISTS `ProfilesMatch`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ProfilesMatch` (
  `PID1` int(10) unsigned NOT NULL default '0' COMMENT 'Profile ID',
  `PID2` int(10) unsigned NOT NULL default '0',
  `Percent` tinyint(4) NOT NULL default '0',
  KEY `MatchPair` (`PID1`,`PID2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ProfilesMatch`
--

LOCK TABLES `ProfilesMatch` WRITE;
/*!40000 ALTER TABLE `ProfilesMatch` DISABLE KEYS */;
/*!40000 ALTER TABLE `ProfilesMatch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ProfilesPolls`
--

DROP TABLE IF EXISTS `ProfilesPolls`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ProfilesPolls` (
  `id_poll` int(11) NOT NULL auto_increment,
  `id_profile` int(11) NOT NULL default '0',
  `poll_question` varchar(255) NOT NULL default '',
  `poll_answers` text NOT NULL,
  `poll_results` varchar(60) NOT NULL default '',
  `poll_total_votes` int(11) NOT NULL default '0',
  `poll_status` varchar(20) NOT NULL default '',
  `poll_approval` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_poll`),
  KEY `id_profile` (`id_profile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ProfilesPolls`
--

LOCK TABLES `ProfilesPolls` WRITE;
/*!40000 ALTER TABLE `ProfilesPolls` DISABLE KEYS */;
/*!40000 ALTER TABLE `ProfilesPolls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ProfilesSettings`
--

DROP TABLE IF EXISTS `ProfilesSettings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ProfilesSettings` (
  `IDMember` int(10) NOT NULL default '0',
  `BackgroundFilename` varchar(40) default NULL,
  `BackgroundColor` varchar(60) default NULL,
  `FontColor` varchar(60) default NULL,
  `FontSize` varchar(60) default NULL,
  `FontFamily` varchar(60) default NULL,
  `Status` varchar(20) default NULL,
  UNIQUE KEY `profile_id` (`IDMember`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ProfilesSettings`
--

LOCK TABLES `ProfilesSettings` WRITE;
/*!40000 ALTER TABLE `ProfilesSettings` DISABLE KEYS */;
/*!40000 ALTER TABLE `ProfilesSettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ProfilesTrack`
--

DROP TABLE IF EXISTS `ProfilesTrack`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ProfilesTrack` (
  `Member` bigint(8) unsigned NOT NULL default '0',
  `Profile` bigint(8) unsigned NOT NULL default '0',
  `Arrived` date NOT NULL default '0000-00-00',
  `Hide` tinyint(4) NOT NULL default '0',
  UNIQUE KEY `Member_2` (`Member`,`Profile`),
  KEY `Member` (`Member`),
  KEY `Profile` (`Profile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ProfilesTrack`
--

LOCK TABLES `ProfilesTrack` WRITE;
/*!40000 ALTER TABLE `ProfilesTrack` DISABLE KEYS */;
/*!40000 ALTER TABLE `ProfilesTrack` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayBoardBoards`
--

DROP TABLE IF EXISTS `RayBoardBoards`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayBoardBoards` (
  `ID` int(11) NOT NULL auto_increment,
  `UserID` varchar(64) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  `Track` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayBoardBoards`
--

LOCK TABLES `RayBoardBoards` WRITE;
/*!40000 ALTER TABLE `RayBoardBoards` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayBoardBoards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayChatCurrentUsers`
--

DROP TABLE IF EXISTS `RayChatCurrentUsers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayChatCurrentUsers` (
  `ID` varchar(20) NOT NULL default '',
  `Nick` varchar(36) NOT NULL default '',
  `Sex` enum('M','F') NOT NULL default 'M',
  `Age` int(11) NOT NULL default '0',
  `Desc` text NOT NULL,
  `Photo` varchar(255) NOT NULL default '',
  `Profile` varchar(255) NOT NULL default '',
  `Online` varchar(10) NOT NULL default 'online',
  `Start` int(11) NOT NULL default '0',
  `When` int(11) NOT NULL default '0',
  `Status` enum('new','old','idle','kick','type','online') NOT NULL default 'new',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayChatCurrentUsers`
--

LOCK TABLES `RayChatCurrentUsers` WRITE;
/*!40000 ALTER TABLE `RayChatCurrentUsers` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayChatCurrentUsers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayChatMessages`
--

DROP TABLE IF EXISTS `RayChatMessages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayChatMessages` (
  `ID` int(11) NOT NULL auto_increment,
  `Room` int(11) NOT NULL default '0',
  `Sender` varchar(20) NOT NULL default '',
  `Recipient` varchar(20) NOT NULL default '',
  `Whisper` enum('true','false') NOT NULL default 'false',
  `Message` text NOT NULL,
  `Style` text NOT NULL,
  `Type` varchar(10) NOT NULL default 'text',
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayChatMessages`
--

LOCK TABLES `RayChatMessages` WRITE;
/*!40000 ALTER TABLE `RayChatMessages` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayChatMessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayChatProfiles`
--

DROP TABLE IF EXISTS `RayChatProfiles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayChatProfiles` (
  `ID` varchar(20) NOT NULL default '0',
  `Banned` enum('true','false') NOT NULL default 'false',
  `Type` varchar(10) NOT NULL default 'full',
  `Smileset` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayChatProfiles`
--

LOCK TABLES `RayChatProfiles` WRITE;
/*!40000 ALTER TABLE `RayChatProfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayChatProfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayChatRooms`
--

DROP TABLE IF EXISTS `RayChatRooms`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayChatRooms` (
  `ID` int(11) NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL default '',
  `Password` varchar(255) NOT NULL default '',
  `Desc` text NOT NULL,
  `OwnerID` varchar(20) NOT NULL default '0',
  `When` int(11) default NULL,
  `Status` enum('normal','delete') NOT NULL default 'normal',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayChatRooms`
--

LOCK TABLES `RayChatRooms` WRITE;
/*!40000 ALTER TABLE `RayChatRooms` DISABLE KEYS */;
INSERT INTO `RayChatRooms` VALUES (1,'Lobby','','Welcome to our chat! You are in the \"Lobby\" now, but you can pass into any other public room you wish to - take a look at the \"All rooms\" box. If you have any problems with using this chat, there is a \"Help\" button on the right at the top (a question icon). Simply click on it and find the answers to your questions.','0',0,'normal'),(2,'Friends','','Welcome to the \"Friends\" room! This is a public room where you can have a fun chat with existing friends or make new ones! Enjoy!','0',1,'normal');
/*!40000 ALTER TABLE `RayChatRooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayChatRoomsUsers`
--

DROP TABLE IF EXISTS `RayChatRoomsUsers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayChatRoomsUsers` (
  `ID` int(11) NOT NULL auto_increment,
  `Room` int(11) NOT NULL default '0',
  `User` varchar(20) NOT NULL default '',
  `When` int(11) default NULL,
  `Status` enum('normal','delete') NOT NULL default 'normal',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayChatRoomsUsers`
--

LOCK TABLES `RayChatRoomsUsers` WRITE;
/*!40000 ALTER TABLE `RayChatRoomsUsers` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayChatRoomsUsers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayGlobalTrackUsers`
--

DROP TABLE IF EXISTS `RayGlobalTrackUsers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayGlobalTrackUsers` (
  `ID` int(11) unsigned NOT NULL default '0',
  `When` bigint(20) unsigned NOT NULL default '0',
  `Status` enum('online','offline') NOT NULL default 'online',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayGlobalTrackUsers`
--

LOCK TABLES `RayGlobalTrackUsers` WRITE;
/*!40000 ALTER TABLE `RayGlobalTrackUsers` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayGlobalTrackUsers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayImContacts`
--

DROP TABLE IF EXISTS `RayImContacts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayImContacts` (
  `ID` int(11) NOT NULL auto_increment,
  `SenderID` int(11) NOT NULL default '0',
  `RecipientID` int(11) NOT NULL default '0',
  `Online` varchar(10) NOT NULL default 'online',
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayImContacts`
--

LOCK TABLES `RayImContacts` WRITE;
/*!40000 ALTER TABLE `RayImContacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayImContacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayImMessages`
--

DROP TABLE IF EXISTS `RayImMessages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayImMessages` (
  `ID` int(11) NOT NULL auto_increment,
  `ContactID` int(11) NOT NULL default '0',
  `Message` text NOT NULL,
  `Style` text NOT NULL,
  `Type` varchar(10) NOT NULL default 'text',
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayImMessages`
--

LOCK TABLES `RayImMessages` WRITE;
/*!40000 ALTER TABLE `RayImMessages` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayImMessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayImPendings`
--

DROP TABLE IF EXISTS `RayImPendings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayImPendings` (
  `ID` int(11) NOT NULL auto_increment,
  `SenderID` int(11) NOT NULL default '0',
  `RecipientID` int(11) NOT NULL default '0',
  `Message` varchar(255) NOT NULL default '',
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `RecipientID` (`RecipientID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayImPendings`
--

LOCK TABLES `RayImPendings` WRITE;
/*!40000 ALTER TABLE `RayImPendings` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayImPendings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayImProfiles`
--

DROP TABLE IF EXISTS `RayImProfiles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayImProfiles` (
  `ID` int(11) NOT NULL default '0',
  `Smileset` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayImProfiles`
--

LOCK TABLES `RayImProfiles` WRITE;
/*!40000 ALTER TABLE `RayImProfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayImProfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayMovieFiles`
--

DROP TABLE IF EXISTS `RayMovieFiles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayMovieFiles` (
  `ID` int(11) NOT NULL auto_increment,
  `CategoryId` int(11) NOT NULL default '-1',
  `Title` varchar(255) NOT NULL default '',
  `Uri` varchar(255) NOT NULL default '',
  `Tags` text NOT NULL,
  `Description` text NOT NULL,
  `Time` int(11) NOT NULL default '0',
  `Date` int(20) NOT NULL default '0',
  `Reports` int(11) NOT NULL default '0',
  `Owner` varchar(64) NOT NULL default '',
  `Views` int(12) default '0',
  `Approved` enum('true','false') NOT NULL default 'true',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Uri` (`Uri`),
  KEY `CatalogId` (`CategoryId`,`Owner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayMovieFiles`
--

LOCK TABLES `RayMovieFiles` WRITE;
/*!40000 ALTER TABLE `RayMovieFiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayMovieFiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayMoviePlayLists`
--

DROP TABLE IF EXISTS `RayMoviePlayLists`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayMoviePlayLists` (
  `FileId` int(11) NOT NULL default '0',
  `Owner` varchar(64) NOT NULL default '',
  `Order` tinyint(4) NOT NULL default '0',
  KEY `FileId` (`FileId`,`Owner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayMoviePlayLists`
--

LOCK TABLES `RayMoviePlayLists` WRITE;
/*!40000 ALTER TABLE `RayMoviePlayLists` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayMoviePlayLists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayMp3Categories`
--

DROP TABLE IF EXISTS `RayMp3Categories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayMp3Categories` (
  `ID` int(11) NOT NULL auto_increment,
  `Parent` int(11) NOT NULL default '0',
  `Title` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `Parent` (`Parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayMp3Categories`
--

LOCK TABLES `RayMp3Categories` WRITE;
/*!40000 ALTER TABLE `RayMp3Categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayMp3Categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayMp3Files`
--

DROP TABLE IF EXISTS `RayMp3Files`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayMp3Files` (
  `ID` int(11) NOT NULL auto_increment,
  `CategoryId` int(11) NOT NULL default '-1',
  `Title` varchar(255) NOT NULL default '',
  `Uri` varchar(255) NOT NULL default '',
  `Tags` text NOT NULL,
  `Description` text NOT NULL,
  `Time` int(11) NOT NULL default '0',
  `Date` int(20) NOT NULL default '0',
  `Reports` int(11) NOT NULL default '0',
  `Owner` varchar(64) NOT NULL default '',
  `Approved` enum('true','false') NOT NULL default 'true',
  PRIMARY KEY  (`ID`),
  KEY `CatalogId` (`CategoryId`,`Owner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayMp3Files`
--

LOCK TABLES `RayMp3Files` WRITE;
/*!40000 ALTER TABLE `RayMp3Files` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayMp3Files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayMp3PlayLists`
--

DROP TABLE IF EXISTS `RayMp3PlayLists`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayMp3PlayLists` (
  `FileId` int(11) NOT NULL default '0',
  `Owner` varchar(64) NOT NULL default '',
  `Order` tinyint(4) NOT NULL default '0',
  KEY `FileId` (`FileId`,`Owner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayMp3PlayLists`
--

LOCK TABLES `RayMp3PlayLists` WRITE;
/*!40000 ALTER TABLE `RayMp3PlayLists` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayMp3PlayLists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayMusicCategories`
--

DROP TABLE IF EXISTS `RayMusicCategories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayMusicCategories` (
  `ID` int(11) NOT NULL auto_increment,
  `Parent` int(11) NOT NULL default '0',
  `Title` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `Parent` (`Parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayMusicCategories`
--

LOCK TABLES `RayMusicCategories` WRITE;
/*!40000 ALTER TABLE `RayMusicCategories` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayMusicCategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayMusicFiles`
--

DROP TABLE IF EXISTS `RayMusicFiles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayMusicFiles` (
  `ID` int(11) NOT NULL auto_increment,
  `CategoryId` int(11) NOT NULL default '-1',
  `Title` varchar(255) NOT NULL default '',
  `Uri` varchar(255) NOT NULL default '',
  `Tags` text NOT NULL,
  `Description` text NOT NULL,
  `Time` int(11) NOT NULL default '0',
  `Date` int(20) NOT NULL default '0',
  `Reports` int(11) NOT NULL default '0',
  `Owner` varchar(64) NOT NULL default '',
  `Listens` int(12) default '0',
  `Approved` enum('true','false') NOT NULL default 'true',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Uri` (`Uri`),
  KEY `CatalogId` (`CategoryId`,`Owner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayMusicFiles`
--

LOCK TABLES `RayMusicFiles` WRITE;
/*!40000 ALTER TABLE `RayMusicFiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayMusicFiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayMusicPlayLists`
--

DROP TABLE IF EXISTS `RayMusicPlayLists`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayMusicPlayLists` (
  `FileId` int(11) NOT NULL default '0',
  `Owner` varchar(64) NOT NULL default '',
  `Order` tinyint(4) NOT NULL default '0',
  KEY `FileId` (`FileId`,`Owner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayMusicPlayLists`
--

LOCK TABLES `RayMusicPlayLists` WRITE;
/*!40000 ALTER TABLE `RayMusicPlayLists` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayMusicPlayLists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayShoutboxMessages`
--

DROP TABLE IF EXISTS `RayShoutboxMessages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayShoutboxMessages` (
  `ID` int(11) NOT NULL auto_increment,
  `UserID` varchar(20) NOT NULL default '0',
  `Msg` text NOT NULL,
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayShoutboxMessages`
--

LOCK TABLES `RayShoutboxMessages` WRITE;
/*!40000 ALTER TABLE `RayShoutboxMessages` DISABLE KEYS */;
/*!40000 ALTER TABLE `RayShoutboxMessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RayVideoStats`
--

DROP TABLE IF EXISTS `RayVideoStats`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `RayVideoStats` (
  `User` varchar(64) NOT NULL default '',
  `Approved` int(20) NOT NULL default '0',
  `Pending` int(20) NOT NULL default '0',
  PRIMARY KEY  (`User`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `RayVideoStats`
--

LOCK TABLES `RayVideoStats` WRITE;
/*!40000 ALTER TABLE `RayVideoStats` DISABLE KEYS */;
INSERT INTO `RayVideoStats` VALUES ('',0,0);
/*!40000 ALTER TABLE `RayVideoStats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SDatingEvents`
--

DROP TABLE IF EXISTS `SDatingEvents`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `SDatingEvents` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Title` varchar(100) NOT NULL default '',
  `EntryUri` varchar(100) NOT NULL default '',
  `Description` text NOT NULL,
  `Status` enum('Active','Inactive','Canceled') NOT NULL default 'Active',
  `StatusMessage` varchar(255) NOT NULL default '',
  `Country` varchar(2) NOT NULL default 'US',
  `City` varchar(50) NOT NULL default '',
  `Place` varchar(100) NOT NULL default '',
  `PhotoFilename` varchar(255) NOT NULL default '',
  `EventStart` datetime NOT NULL default '0000-00-00 00:00:00',
  `EventEnd` datetime NOT NULL default '0000-00-00 00:00:00',
  `TicketSaleStart` datetime NOT NULL default '0000-00-00 00:00:00',
  `TicketSaleEnd` datetime NOT NULL default '0000-00-00 00:00:00',
  `ResponsibleID` bigint(8) NOT NULL default '0',
  `ResponsibleName` varchar(50) NOT NULL default '',
  `ResponsibleEmail` varchar(50) NOT NULL default '',
  `ResponsiblePhone` varchar(30) NOT NULL default '',
  `EventSexFilter` set('female','male','couple') NOT NULL default 'female,male',
  `EventAgeLowerFilter` tinyint(2) unsigned NOT NULL default '18',
  `EventAgeUpperFilter` tinyint(2) unsigned NOT NULL default '75',
  `EventMembershipFilter` varchar(100) NOT NULL default '',
  `TicketCountFemale` smallint(5) unsigned NOT NULL default '0',
  `TicketCountMale` smallint(5) unsigned NOT NULL default '0',
  `TicketCountCouple` smallint(5) unsigned NOT NULL default '0',
  `TicketPriceFemale` float(5,2) NOT NULL default '0.00',
  `TicketPriceMale` float(5,2) NOT NULL default '0.00',
  `TicketPriceCouple` float(5,2) NOT NULL default '0.00',
  `ChoosePeriod` smallint(4) unsigned NOT NULL default '0',
  `AllowViewParticipants` tinyint(1) unsigned NOT NULL default '0',
  `Tags` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `EntryUri` (`EntryUri`),
  KEY `ResponsibleID` (`ResponsibleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `SDatingEvents`
--

LOCK TABLES `SDatingEvents` WRITE;
/*!40000 ALTER TABLE `SDatingEvents` DISABLE KEYS */;
/*!40000 ALTER TABLE `SDatingEvents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SDatingMatches`
--

DROP TABLE IF EXISTS `SDatingMatches`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `SDatingMatches` (
  `IDChooser` bigint(10) unsigned NOT NULL default '0',
  `IDChosen` bigint(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`IDChooser`,`IDChosen`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `SDatingMatches`
--

LOCK TABLES `SDatingMatches` WRITE;
/*!40000 ALTER TABLE `SDatingMatches` DISABLE KEYS */;
/*!40000 ALTER TABLE `SDatingMatches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SDatingParticipants`
--

DROP TABLE IF EXISTS `SDatingParticipants`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `SDatingParticipants` (
  `ID` bigint(10) unsigned NOT NULL auto_increment,
  `IDEvent` int(10) unsigned NOT NULL default '0',
  `IDMember` bigint(8) unsigned NOT NULL default '0',
  `ParticipantUID` varchar(30) NOT NULL default '0',
  `TransactionID` bigint(20) unsigned default NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `ParticipantKey` (`IDEvent`,`IDMember`),
  UNIQUE KEY `UIDKey` (`IDEvent`,`ParticipantUID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `SDatingParticipants`
--

LOCK TABLES `SDatingParticipants` WRITE;
/*!40000 ALTER TABLE `SDatingParticipants` DISABLE KEYS */;
/*!40000 ALTER TABLE `SDatingParticipants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SiteStat`
--

DROP TABLE IF EXISTS `SiteStat`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `SiteStat` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(3) NOT NULL default '',
  `Title` varchar(50) NOT NULL default '',
  `UserLink` varchar(255) NOT NULL default '',
  `UserQuery` varchar(255) NOT NULL default '',
  `AdminLink` varchar(255) NOT NULL default '',
  `AdminQuery` varchar(255) NOT NULL default '',
  `IconName` varchar(50) NOT NULL default '',
  `StatOrder` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `SiteStat`
--

LOCK TABLES `SiteStat` WRITE;
/*!40000 ALTER TABLE `SiteStat` DISABLE KEYS */;
INSERT INTO `SiteStat` VALUES (1,'all','Members','browse.php','SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = \'Active\' AND (`Couple`=\'0\' OR `Couple`>`ID`)','profiles.php?profiles=Approval','SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status`!=\'Active\' AND (`Couple`=\'0\' OR `Couple`>`ID`)','mbs.gif',0),(2,'pph','Photos','browsePhoto.php','SELECT COUNT(`medID`) FROM `sharePhotoFiles` WHERE `Approved`=\'true\'','browseMedia.php?type=photo','SELECT COUNT(`medID`) FROM `sharePhotoFiles` WHERE `Approved`=\'false\'','pph.gif',0),(3,'evs','Events','events.php?show_events=all&action=show','SELECT COUNT(`ID`) FROM `SDatingEvents` WHERE `Status`=\'Active\'','sdating_admin.php','SELECT COUNT(`ID`) FROM `SDatingEvents` WHERE `Status`!=\'Active\'','evs.gif',0),(4,'onl','Online','search.php?online_only=1','SELECT COUNT(`ID`) AS `count_onl` FROM `Profiles` WHERE `DateLastNav` > SUBDATE(NOW(), INTERVAL 5 MINUTE) AND (`Couple`=0 OR `Couple`>`ID`)','','','mbs.gif',0),(5,'pvi','Videos','browseVideo.php','SELECT COUNT(`ID`) FROM `RayMovieFiles` WHERE `Approved`=\'true\'','browseMedia.php?type=video','SELECT COUNT(`ID`) FROM `RayMovieFiles` WHERE `Approved`!=\'true\'','pvi.gif',0),(6,'pls','Polls','polls.php','SELECT COUNT(`id_poll`) FROM `ProfilesPolls` WHERE `poll_approval`=\'1\'','post_mod_ppolls.php','SELECT COUNT(`id_poll`) FROM `ProfilesPolls` WHERE `poll_approval`!=\'1\'','pls.gif',0),(7,'ntd','New Today','','SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = \'Active\' AND (TO_DAYS(NOW()) - TO_DAYS(`DateReg`)) <= 1 AND (`Couple`=0 OR `Couple`>`ID`)','','','mbs.gif',0),(8,'pmu','Music','browseMusic.php','SELECT COUNT(`ID`) FROM `RayMusicFiles` WHERE `Approved`=\'true\'','browseMedia.php?type=music','SELECT COUNT(`ID`) FROM `RayMusicFiles` WHERE `Approved`!=\'true\'','pmu.gif',0),(9,'tps','Topics','orca','SELECT IF( NOT ISNULL( SUM(`forum_topics`)), SUM(`forum_posts`), 0) AS `Num` FROM `pre_forum`','','','tps.gif',0),(10,'nwk','This Week','','SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = \'Active\' AND (TO_DAYS(NOW()) - TO_DAYS(`DateReg`)) <= 7 AND (`Couple`=0 OR `Couple`>`ID`)','','','mbs.gif',0),(11,'pvd','Profile Videos','','SELECT `Approved` FROM `RayVideoStats`','javascript:window.open(\'../ray/modules/video/app/admin.swf?nick={adminLogin}&password={adminPass}&url=../../../XML.php\',\'RayVideoAdmin\',\'width=700,height=330,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=0\');','','pvi.gif',0),(12,'pts','Posts','orca','SELECT IF( NOT ISNULL( SUM(`forum_posts`)), SUM(`forum_posts`), 0) AS `Num` FROM `pre_forum`','','','pts.gif',0),(13,'nmh','This Month','','SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = \'Active\' AND (TO_DAYS(NOW()) - TO_DAYS(`DateReg`)) <= 30 AND (`Couple`=0 OR `Couple`>`ID`)','','','mbs.gif',0),(14,'tgs','Tags','','SELECT COUNT( DISTINCT `Tag` ) FROM `Tags`','','','tgs.gif',0),(15,'ars','Articles','articles.php','SELECT COUNT(`ArticlesID`) FROM `Articles`','','','ars.gif',0),(16,'nyr','This Year','','SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = \'Active\' AND (TO_DAYS(NOW()) - TO_DAYS(`DateReg`)) <= 365 AND (`Couple`=0 OR `Couple`>`ID`)','','','mbs.gif',0),(17,'grs','Groups','grp.php','SELECT COUNT(`ID`) FROM `Groups` WHERE `status`=\'Active\'','','','grs.gif',0),(18,'cls','Classifieds','classifieds.php?Browse=1','SELECT COUNT(`ID`) FROM `ClassifiedsAdvertisements` WHERE `Status`=\'active\' AND DATE_ADD( `ClassifiedsAdvertisements`.`DateTime` , INTERVAL `ClassifiedsAdvertisements`.`LifeTime` DAY ) > NOW( )','','','cls.gif',0),(19,'frs','Friends','','SELECT COUNT(`ID`) FROM `FriendList` WHERE `Check`=\'1\'','','','frs.gif',0),(20,'blg','Blogs','blogs.php','SELECT COUNT(*) FROM `Blogs`','','','pts.gif',0);
/*!40000 ALTER TABLE `SiteStat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Stories`
--

DROP TABLE IF EXISTS `Stories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Stories` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Date` date NOT NULL default '0000-00-00',
  `Sender` bigint(8) unsigned NOT NULL default '0',
  `Header` varchar(50) NOT NULL default '',
  `Text` longtext NOT NULL,
  `active` varchar(4) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `Sender` (`Sender`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Stories`
--

LOCK TABLES `Stories` WRITE;
/*!40000 ALTER TABLE `Stories` DISABLE KEYS */;
/*!40000 ALTER TABLE `Stories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tags`
--

DROP TABLE IF EXISTS `Tags`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Tags` (
  `Tag` varchar(32) NOT NULL default '',
  `ID` bigint(8) unsigned NOT NULL default '0',
  `Type` enum('profile','blog','event','photo','video','music','ad') NOT NULL default 'profile',
  PRIMARY KEY  (`Tag`,`ID`,`Type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Tags`
--

LOCK TABLES `Tags` WRITE;
/*!40000 ALTER TABLE `Tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `Tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TopMenu`
--

DROP TABLE IF EXISTS `TopMenu`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `TopMenu` (
  `ID` int(8) unsigned NOT NULL auto_increment,
  `Parent` int(8) unsigned NOT NULL default '0',
  `Name` varchar(50) NOT NULL default '',
  `Caption` varchar(50) NOT NULL default '',
  `Link` varchar(255) NOT NULL default '',
  `Order` int(8) unsigned NOT NULL default '0',
  `Visible` set('non','memb') NOT NULL default '',
  `Target` varchar(20) NOT NULL default '',
  `Onclick` mediumtext NOT NULL,
  `Check` varchar(255) NOT NULL default '',
  `Editable` tinyint(1) NOT NULL default '1',
  `Deletable` tinyint(1) NOT NULL default '1',
  `Active` tinyint(1) NOT NULL default '1',
  `Type` enum('system','top','custom') NOT NULL default 'top',
  `Strict` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `TopMenu`
--

LOCK TABLES `TopMenu` WRITE;
/*!40000 ALTER TABLE `TopMenu` DISABLE KEYS */;
INSERT INTO `TopMenu` VALUES (1,0,'My Account','_My Account','member.php',0,'memb','','','',0,0,1,'system',0),(2,1,'Account Home','_Account Home','member.php',0,'memb','','','',0,0,1,'custom',0),(3,0,'My Mail','_My Mail','mail.php',0,'memb','','','',0,0,1,'system',0),(4,0,'My Profile','_My Profile','{memberNick}|change_status.php',0,'memb','','','',0,0,1,'system',0),(5,0,'Home','_Home','index.php',0,'non,memb','','','',1,1,1,'top',0),(6,0,'Members','_Members','browse.php|search.php',1,'non,memb','','','',1,1,1,'top',0),(7,6,'All members','_All Members','browse.php',0,'non,memb','','','',1,1,1,'custom',0),(8,6,'Search Members','_Search','search.php',2,'non,memb','','','',1,1,1,'custom',0),(28,0,'Videos','_Videos','browseVideo.php|viewVideo.php',4,'non,memb','','','',1,1,1,'top',0),(10,1,'My Presense','_RayPresence','javascript:void(0);',1,'memb','','window.open( \'presence_pop.php\' , \'Presence\', \'width=240,height=600,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=1\');','return ( \'on\' == getParam( \'enable_ray\' ) );',1,1,1,'custom',0),(11,4,'View My Profile','_View Profile','{memberLink}|{memberNick}|profile.php?ID={memberID}',0,'memb','','','',1,1,1,'custom',0),(12,3,'Mail Write','_Write','compose.php',0,'memb','','','',1,1,1,'custom',0),(13,3,'I Blocked','_I Blocked','contacts.php?show=block&list=i',1,'memb','','','',1,1,1,'custom',0),(14,3,'Mail Sent','_Sent','mail.php?mode=outbox',2,'memb','','','',1,1,1,'custom',0),(15,1,'My Membership','_My Membership','membership.php',2,'memb','','','return ( getParam(\'free_mode\') != \'on\' );',1,1,1,'custom',0),(16,1,'My Settings','_My Settings','pedit.php?ID={memberID}',3,'memb','','','',1,1,1,'custom',0),(17,3,'Mail Inbox','_Inbox','mail.php?mode=inbox',3,'memb','','','',1,1,1,'custom',0),(18,3,'Blocked Me','_Blocked Me','contacts.php?show=block&list=me',4,'memb','','','',1,1,1,'custom',0),(20,4,'Edit My Profile','_Edit Profile','pedit.php?ID={memberID}',1,'memb','','','',1,1,1,'custom',0),(21,4,'Member Photos','_Profile Photos','upload_media.php?show=photo',2,'memb','','','',1,1,1,'custom',0),(22,0,'Groups','_Groups','grp.php',6,'non,memb','','','',1,1,1,'top',0),(23,22,'All Groups','_All Groups','grp.php',0,'non,memb','','','',1,1,1,'custom',1),(24,22,'Groups Search','_Search','grp.php?action=search',1,'non,memb','','','',1,1,1,'custom',0),(25,6,'Online Members','_Online','search.php?online_only=1',1,'non,memb','','','',1,1,1,'custom',0),(26,22,'My Groups','_My Groups','grp.php?action=mygroups',2,'memb','','','',1,1,1,'custom',0),(27,22,'Create Group','_Create Group','grp.php?action=create',3,'memb','','','',1,1,1,'custom',0),(29,28,'All Videos','_All Videos','browseVideo.php|viewVideo.php',0,'non,memb','','','',1,1,1,'custom',1),(30,28,'Upload Video','_Upload Video','uploadShareVideo.php',1,'memb','','','',1,1,1,'custom',0),(31,0,'Classifieds','_Classifieds','classifieds.php?Browse=1|classifieds.php|classifiedsmy.php',7,'non,memb','','','',1,1,1,'top',0),(32,0,'Chat','_Chat','chat.php',13,'non,memb','','','',1,1,1,'top',0),(35,31,'Search Classifieds','_Search','classifieds.php?SearchForm=1',1,'non,memb','','','',1,1,1,'custom',0),(33,0,'Boards','_Boards','board.php',12,'non,memb','','','',1,1,1,'top',0),(50,44,'Add Blog Post','_Add Post','blogs.php?action=new_post',5,'memb','','','',1,1,1,'custom',0),(34,31,'All Classifieds','_All Classifieds','classifieds.php?Browse=1',0,'non,memb','','','',1,1,1,'custom',1),(36,31,'My Classifieds','_My Classifieds','classifiedsmy.php?MyAds=1',2,'memb','','','',1,1,1,'custom',0),(37,31,'Add Classified','_Add Classified','classifiedsmy.php?PostAd=1',3,'memb','','','',1,1,1,'custom',0),(38,0,'Music','_Music','browseMusic.php|viewMusic.php',5,'non,memb','','','',1,1,1,'top',0),(46,44,'Top Blogs','_Top Blogs','blogs.php?action=top_blogs',1,'non,memb','','','',1,1,1,'custom',0),(39,38,'All Music','_All Music','browseMusic.php|viewMusic.php',0,'non,memb','','','',1,1,1,'custom',1),(40,38,'Upload Music','_Upload Music','uploadShareMusic.php',1,'memb','','','',1,1,1,'custom',0),(41,0,'Photos','_Photos','browsePhoto.php|viewPhoto.php',3,'non,memb','','','',1,1,1,'top',0),(53,51,'Events Calendar','_Calendar','events.php?action=calendar',1,'non,memb','','','',1,1,1,'custom',0),(42,41,'All Photos','_All Photos','browsePhoto.php|viewPhoto.php',0,'non,memb','','','',1,1,1,'custom',1),(43,41,'Upload Photos','_Upload Photos','uploadSharePhoto.php',1,'memb','','','',1,1,1,'custom',0),(44,0,'Blogs','_Blogs','blogs.php',2,'non,memb','','','',1,1,1,'top',0),(45,44,'All Blogs','_All Blogs','blogs.php',0,'non,memb','','','',1,1,1,'custom',1),(47,44,'My Blog','_My Blog','blogs.php?action=show_member_blog&ownerID={memberID}',3,'memb','','','',1,1,1,'custom',0),(48,0,'Forums','_Forums','orca/',10,'non,memb','','','',1,1,1,'top',0),(49,44,'Add Blog Category','_Add Category','blogs.php?action=add_category&ownerID={memberID}',4,'memb','','','',1,1,1,'custom',0),(51,0,'Events','_Events','events.php?show_events=all&action=show|events.php',8,'non,memb','','','',1,1,1,'top',0),(58,56,'My Polls','_My Polls','profile_poll.php',1,'memb','','','',1,1,1,'custom',0),(52,51,'All Events','_All Events','events.php?show_events=all&action=show',0,'non,memb','','','',1,1,1,'custom',0),(54,51,'My Events','_My Events','events.php?action=show&show_events=my',3,'memb','','','',1,1,1,'custom',0),(55,51,'Add Event','_Add Event','events.php?action=new',4,'memb','','','',1,1,1,'custom',0),(56,0,'Polls','_Polls','polls.php',9,'non,memb','','','',1,1,1,'top',0),(57,56,'All Polls','_All Polls','polls.php',0,'non,memb','','','',1,1,1,'custom',0),(59,0,'Articles','_Articles','articles.php',11,'non,memb','','','',1,1,1,'top',0),(9,0,'Profile View','{profileNick}','{profileNick}|pedit.php?ID={profileID}|photos_gallery.php?ID={profileID}',0,'non,memb','','','',0,0,1,'system',0),(60,9,'View Profile','_View Profile','{profileLink}|{profileNick}|profile.php?ID={profileID}',0,'non,memb','','','',1,1,1,'custom',0),(61,9,'Profile Video Gallery','_Video Gallery','browseVideo.php?userID={profileID}',2,'non,memb','','','',1,1,1,'custom',0),(62,9,'Profile Music Gallery','_Music Gallery','browseMusic.php?userID={profileID}',3,'non,memb','','','',1,1,1,'custom',0),(63,4,'Member Music','_Profile Music','javascript:void(0);',3,'memb','','openRayWidget(\'mp3\', \'editor\', \'{memberID}\', \'{memberPass}\');','',1,1,1,'custom',0),(64,4,'Member Video','_Profile Video','javascript:void(0);',4,'memb','','openRayWidget(\'video\', \'recorder\', \'{memberID}\', \'{memberPass}\');','',1,1,1,'custom',0),(65,9,'Profile Photos Gallery','_Photos Gallery','browsePhoto.php?userID={profileID}',1,'non,memb','','','',1,1,1,'custom',0),(66,9,'Profile Blog','_Blog','blogs.php?action=show_member_blog&ownerID={profileID}|blogs.php?action=show_member_post&ownerID={profileID}',4,'non,memb','','','',1,1,1,'custom',0),(67,9,'Member Guestbook','_Guestbook','guestbook.php?owner={profileID}',5,'non,memb','','','',1,1,1,'custom',0),(68,28,'My Videos','_My Videos','browseVideo.php?userID={memberID}',2,'memb','','','',1,1,1,'custom',0),(69,41,'My Photos','_My Photos','browsePhoto.php?userID={memberID}',2,'memb','','','',1,1,1,'custom',0),(70,38,'My Music','_My Music','browseMusic.php?userID={memberID}',2,'memb','','','',1,1,1,'custom',0),(71,41,'My Favorite Photos','_My Favorite Photos','browsePhoto.php?action=fav',3,'memb','','','',1,1,1,'custom',0),(72,28,'My Favorite Videos','_My Favorite Videos','browseVideo.php?action=fav',3,'memb','','','',1,1,1,'custom',0),(73,38,'My Favorite Music','_My Favorite Music','browseMusic.php?action=fav',3,'memb','','','',1,1,1,'custom',0),(74,4,'Customize My Profile','_Customize Profile','profile_customize.php',5,'memb','','','',1,1,1,'custom',0),(75,28,'Top Videos','_Top Video','browseVideo.php?rate=top',4,'non,memb','','','',1,1,1,'custom',0),(76,41,'Top Photos','_Top Photos','browsePhoto.php?rate=top',4,'non,memb','','','',1,1,1,'custom',0),(77,38,'Top Music','_Top Music','browseMusic.php?rate=top',4,'non,memb','','','',1,1,1,'custom',0),(78,51,'Search Events','_Search','events.php?action=search',2,'non,memb','','','',1,1,1,'custom',0),(79,44,'Top Posts','_Top Posts','blogs.php?action=top_posts',2,'non,memb','','','',1,1,1,'custom',0),(80,4,'My Friends','_My Friends','viewFriends.php?iUser={memberID}',6,'memb','','','',1,1,1,'custom',0),(81,6,'My Friends','_My Friends','viewFriends.php?iUser={memberID}',3,'memb','','','',1,1,1,'custom',0),(82,9,'Member Friends','_Member Friends','viewFriends.php?iUser={profileID}',6,'non,memb','','','',1,1,1,'custom',0),(83,1,'My Contacts','_My Contacts','contacts.php',4,'memb','','','',1,1,1,'custom',0),(84,4,'My Guestbook','_My Guestbook','guestbook.php?owner={memberID}',7,'memb','','','',1,1,1,'custom',0),(85,6,'Hot or Not','_Hot or Not','rate.php',4,'non,memb','','','',1,1,1,'custom',0),(86,1,'Unregister','_Unregister','unregister.php',5,'memb','','','',1,1,1,'custom',0),(87,48,'My Flags','_My Flags','orca/#action=goto&my_flags=1',1,'memb','','','',0,0,1,'custom',0),(88,48,'My Topics','_My Topics','orca/#action=goto&my_threads=1',2,'memb','','','',0,0,1,'custom',0),(89,48,'Search','_Search','orca/#action=goto&search=1',0,'non,memb','','','',0,0,1,'custom',0);
/*!40000 ALTER TABLE `TopMenu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Transactions`
--

DROP TABLE IF EXISTS `Transactions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Transactions` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `IDMember` bigint(20) unsigned NOT NULL default '0',
  `IDProvider` smallint(6) unsigned NOT NULL default '0',
  `gtwTransactionID` varchar(32) NOT NULL default '',
  `Date` datetime NOT NULL default '0000-00-00 00:00:00',
  `Amount` float unsigned NOT NULL default '0',
  `Currency` varchar(3) NOT NULL default 'USD',
  `Status` enum('pending','approved','declined') NOT NULL default 'pending',
  `Data` text NOT NULL,
  `Description` tinytext,
  `Note` tinytext,
  PRIMARY KEY  (`ID`),
  KEY `IDMember` (`IDMember`),
  KEY `IDProvider` (`IDProvider`),
  KEY `gtwTransactionID` (`gtwTransactionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Transactions`
--

LOCK TABLES `Transactions` WRITE;
/*!40000 ALTER TABLE `Transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `Transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VKisses`
--

DROP TABLE IF EXISTS `VKisses`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `VKisses` (
  `ID` bigint(8) unsigned NOT NULL default '0',
  `Member` bigint(8) unsigned NOT NULL default '0',
  `Number` smallint(5) unsigned NOT NULL default '0',
  `Arrived` date NOT NULL default '0000-00-00',
  `New` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`ID`,`Member`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `VKisses`
--

LOCK TABLES `VKisses` WRITE;
/*!40000 ALTER TABLE `VKisses` DISABLE KEYS */;
/*!40000 ALTER TABLE `VKisses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Votes`
--

DROP TABLE IF EXISTS `Votes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `Votes` (
  `Member` bigint(8) NOT NULL default '0',
  `Mark` int(11) NOT NULL default '0',
  `IP` varchar(18) NOT NULL default '',
  `Date` date NOT NULL default '0000-00-00',
  UNIQUE KEY `Member` (`Member`,`IP`,`Date`),
  KEY `Member_2` (`Member`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `Votes`
--

LOCK TABLES `Votes` WRITE;
/*!40000 ALTER TABLE `Votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `Votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VotesPhotos`
--

DROP TABLE IF EXISTS `VotesPhotos`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `VotesPhotos` (
  `Member` bigint(8) NOT NULL default '0',
  `Mark` int(11) NOT NULL default '0',
  `Pic` int(11) NOT NULL default '0',
  `IP` varchar(18) NOT NULL default '',
  `Date` date NOT NULL default '0000-00-00',
  UNIQUE KEY `Member` (`Member`,`Pic`,`IP`,`Date`),
  KEY `Member_2` (`Member`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `VotesPhotos`
--

LOCK TABLES `VotesPhotos` WRITE;
/*!40000 ALTER TABLE `VotesPhotos` DISABLE KEYS */;
/*!40000 ALTER TABLE `VotesPhotos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ZIPCodes`
--

DROP TABLE IF EXISTS `ZIPCodes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ZIPCodes` (
  `ZIPCode` varchar(8) default NULL,
  `ZIPCodeType` char(1) default NULL,
  `City` varchar(28) default NULL,
  `CityType` char(1) default NULL,
  `County` varchar(25) default NULL,
  `CountyFIPS` varchar(5) default NULL,
  `State` varchar(30) default NULL,
  `StateCode` varchar(2) default NULL,
  `StateFIPS` varchar(2) default NULL,
  `MSA` varchar(4) default NULL,
  `AreaCode` varchar(3) default NULL,
  `TimeZone` varchar(10) default NULL,
  `GMTOffset` int(11) default NULL,
  `DST` char(1) default NULL,
  `Latitude` double default NULL,
  `Longitude` double default NULL,
  KEY `Latitude` (`Latitude`),
  KEY `Longitude` (`Longitude`),
  KEY `ZIPCode` (`ZIPCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ZIPCodes`
--

LOCK TABLES `ZIPCodes` WRITE;
/*!40000 ALTER TABLE `ZIPCodes` DISABLE KEYS */;
/*!40000 ALTER TABLE `ZIPCodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aff`
--

DROP TABLE IF EXISTS `aff`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `aff` (
  `ID` bigint(8) NOT NULL auto_increment,
  `Name` varchar(10) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `Password` varchar(32) NOT NULL default '',
  `Percent` double NOT NULL default '0',
  `seed` int(11) NOT NULL default '0',
  `RegDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `Status` enum('Approval','Active') NOT NULL default 'Approval',
  `www1` varchar(10) NOT NULL default '',
  `www2` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `aff`
--

LOCK TABLES `aff` WRITE;
/*!40000 ALTER TABLE `aff` DISABLE KEYS */;
/*!40000 ALTER TABLE `aff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aff_banners`
--

DROP TABLE IF EXISTS `aff_banners`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `aff_banners` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `XSize` smallint(11) unsigned NOT NULL default '0',
  `YSize` smallint(11) unsigned NOT NULL default '0',
  `Banner` varchar(32) NOT NULL default '',
  `BannerName` varchar(32) NOT NULL default '',
  `Text` text NOT NULL,
  `Added` tinyint(4) unsigned NOT NULL default '1',
  `Status` enum('Approval','Active') NOT NULL default 'Active',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `aff_banners`
--

LOCK TABLES `aff_banners` WRITE;
/*!40000 ALTER TABLE `aff_banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `aff_banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aff_members`
--

DROP TABLE IF EXISTS `aff_members`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `aff_members` (
  `idAff` bigint(8) NOT NULL default '0',
  `idProfile` bigint(8) NOT NULL default '0',
  PRIMARY KEY  (`idAff`,`idProfile`),
  UNIQUE KEY `idProfile` (`idProfile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `aff_members`
--

LOCK TABLES `aff_members` WRITE;
/*!40000 ALTER TABLE `aff_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `aff_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gmusic_rating`
--

DROP TABLE IF EXISTS `gmusic_rating`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `gmusic_rating` (
  `gal_id` int(12) NOT NULL default '0',
  `gal_rating_count` int(11) NOT NULL default '0',
  `gal_rating_sum` int(11) NOT NULL default '0',
  UNIQUE KEY `med_id` (`gal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `gmusic_rating`
--

LOCK TABLES `gmusic_rating` WRITE;
/*!40000 ALTER TABLE `gmusic_rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `gmusic_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gmusic_voting_track`
--

DROP TABLE IF EXISTS `gmusic_voting_track`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `gmusic_voting_track` (
  `gal_id` int(12) NOT NULL default '0',
  `gal_ip` varchar(20) default NULL,
  `gal_date` datetime default NULL,
  KEY `med_ip` (`gal_ip`,`gal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `gmusic_voting_track`
--

LOCK TABLES `gmusic_voting_track` WRITE;
/*!40000 ALTER TABLE `gmusic_voting_track` DISABLE KEYS */;
/*!40000 ALTER TABLE `gmusic_voting_track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gphoto_rating`
--

DROP TABLE IF EXISTS `gphoto_rating`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `gphoto_rating` (
  `gal_id` int(12) NOT NULL default '0',
  `gal_rating_count` int(11) NOT NULL default '0',
  `gal_rating_sum` int(11) NOT NULL default '0',
  UNIQUE KEY `med_id` (`gal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `gphoto_rating`
--

LOCK TABLES `gphoto_rating` WRITE;
/*!40000 ALTER TABLE `gphoto_rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `gphoto_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gphoto_voting_track`
--

DROP TABLE IF EXISTS `gphoto_voting_track`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `gphoto_voting_track` (
  `gal_id` int(12) NOT NULL default '0',
  `gal_ip` varchar(20) default NULL,
  `gal_date` datetime default NULL,
  KEY `med_ip` (`gal_ip`,`gal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `gphoto_voting_track`
--

LOCK TABLES `gphoto_voting_track` WRITE;
/*!40000 ALTER TABLE `gphoto_voting_track` DISABLE KEYS */;
/*!40000 ALTER TABLE `gphoto_voting_track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_forum`
--

DROP TABLE IF EXISTS `grp_forum`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `grp_forum` (
  `forum_id` int(10) unsigned NOT NULL auto_increment,
  `forum_uri` varchar(255) NOT NULL default '',
  `cat_id` int(11) NOT NULL default '0',
  `forum_title` varchar(255) default NULL,
  `forum_desc` varchar(255) NOT NULL default '',
  `forum_posts` int(11) NOT NULL default '0',
  `forum_topics` int(11) NOT NULL default '0',
  `forum_last` int(11) NOT NULL default '0',
  `forum_type` enum('public','private') NOT NULL default 'public',
  PRIMARY KEY  (`forum_id`),
  KEY `cat_id` (`cat_id`),
  KEY `forum_uri` (`forum_uri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `grp_forum`
--

LOCK TABLES `grp_forum` WRITE;
/*!40000 ALTER TABLE `grp_forum` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_forum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_forum_cat`
--

DROP TABLE IF EXISTS `grp_forum_cat`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `grp_forum_cat` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `cat_uri` varchar(255) NOT NULL default '',
  `cat_name` varchar(255) default NULL,
  `cat_icon` varchar(32) NOT NULL default '',
  `cat_order` float NOT NULL default '0',
  PRIMARY KEY  (`cat_id`),
  KEY `cat_order` (`cat_order`),
  KEY `cat_uri` (`cat_uri`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `grp_forum_cat`
--

LOCK TABLES `grp_forum_cat` WRITE;
/*!40000 ALTER TABLE `grp_forum_cat` DISABLE KEYS */;
INSERT INTO `grp_forum_cat` VALUES (1,'Groups','Groups','',0);
/*!40000 ALTER TABLE `grp_forum_cat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_forum_flag`
--

DROP TABLE IF EXISTS `grp_forum_flag`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `grp_forum_flag` (
  `user` varchar(32) NOT NULL default '',
  `topic_id` int(11) NOT NULL default '0',
  `when` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user`,`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `grp_forum_flag`
--

LOCK TABLES `grp_forum_flag` WRITE;
/*!40000 ALTER TABLE `grp_forum_flag` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_forum_flag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_forum_post`
--

DROP TABLE IF EXISTS `grp_forum_post`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `grp_forum_post` (
  `post_id` int(10) unsigned NOT NULL auto_increment,
  `topic_id` int(11) NOT NULL default '0',
  `forum_id` int(11) NOT NULL default '0',
  `user` varchar(32) NOT NULL default '0',
  `post_text` mediumtext NOT NULL,
  `when` int(11) NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  PRIMARY KEY  (`post_id`),
  KEY `topic_id` (`topic_id`),
  KEY `forum_id` (`forum_id`),
  KEY `user` (`user`),
  KEY `when` (`when`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `grp_forum_post`
--

LOCK TABLES `grp_forum_post` WRITE;
/*!40000 ALTER TABLE `grp_forum_post` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_forum_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_forum_report`
--

DROP TABLE IF EXISTS `grp_forum_report`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `grp_forum_report` (
  `user_name` varchar(32) NOT NULL default '',
  `post_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_name`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `grp_forum_report`
--

LOCK TABLES `grp_forum_report` WRITE;
/*!40000 ALTER TABLE `grp_forum_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_forum_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_forum_topic`
--

DROP TABLE IF EXISTS `grp_forum_topic`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `grp_forum_topic` (
  `topic_id` int(10) unsigned NOT NULL auto_increment,
  `topic_uri` varchar(255) NOT NULL default '',
  `forum_id` int(11) NOT NULL default '0',
  `topic_title` varchar(255) NOT NULL default '',
  `when` int(11) NOT NULL default '0',
  `topic_posts` int(11) NOT NULL default '0',
  `first_post_user` varchar(32) NOT NULL default '0',
  `first_post_when` int(11) NOT NULL default '0',
  `last_post_user` varchar(32) NOT NULL default '',
  `last_post_when` int(11) NOT NULL default '0',
  `topic_sticky` int(11) NOT NULL default '0',
  `topic_locked` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`topic_id`),
  KEY `forum_id` (`forum_id`),
  KEY `forum_id_2` (`forum_id`,`when`),
  KEY `topic_uri` (`topic_uri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `grp_forum_topic`
--

LOCK TABLES `grp_forum_topic` WRITE;
/*!40000 ALTER TABLE `grp_forum_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_forum_topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_forum_user`
--

DROP TABLE IF EXISTS `grp_forum_user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `grp_forum_user` (
  `user_name` varchar(32) NOT NULL default '',
  `user_pwd` varchar(32) NOT NULL default '',
  `user_email` varchar(128) NOT NULL default '',
  `user_join_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `grp_forum_user`
--

LOCK TABLES `grp_forum_user` WRITE;
/*!40000 ALTER TABLE `grp_forum_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_forum_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_forum_user_activity`
--

DROP TABLE IF EXISTS `grp_forum_user_activity`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `grp_forum_user_activity` (
  `user` varchar(32) NOT NULL default '',
  `act_current` int(11) NOT NULL default '0',
  `act_last` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `grp_forum_user_activity`
--

LOCK TABLES `grp_forum_user_activity` WRITE;
/*!40000 ALTER TABLE `grp_forum_user_activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_forum_user_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_forum_user_stat`
--

DROP TABLE IF EXISTS `grp_forum_user_stat`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `grp_forum_user_stat` (
  `user` varchar(32) NOT NULL default '',
  `posts` int(11) NOT NULL default '0',
  `user_last_post` int(11) NOT NULL default '0',
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `grp_forum_user_stat`
--

LOCK TABLES `grp_forum_user_stat` WRITE;
/*!40000 ALTER TABLE `grp_forum_user_stat` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_forum_user_stat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grp_forum_vote`
--

DROP TABLE IF EXISTS `grp_forum_vote`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `grp_forum_vote` (
  `user_name` varchar(32) NOT NULL default '',
  `post_id` int(11) NOT NULL default '0',
  `vote_when` int(11) NOT NULL default '0',
  `vote_point` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`user_name`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `grp_forum_vote`
--

LOCK TABLES `grp_forum_vote` WRITE;
/*!40000 ALTER TABLE `grp_forum_vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `grp_forum_vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gvideo_rating`
--

DROP TABLE IF EXISTS `gvideo_rating`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `gvideo_rating` (
  `gal_id` int(12) NOT NULL default '0',
  `gal_rating_count` int(11) NOT NULL default '0',
  `gal_rating_sum` int(11) NOT NULL default '0',
  UNIQUE KEY `med_id` (`gal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `gvideo_rating`
--

LOCK TABLES `gvideo_rating` WRITE;
/*!40000 ALTER TABLE `gvideo_rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `gvideo_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gvideo_voting_track`
--

DROP TABLE IF EXISTS `gvideo_voting_track`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `gvideo_voting_track` (
  `gal_id` int(12) NOT NULL default '0',
  `gal_ip` varchar(20) default NULL,
  `gal_date` datetime default NULL,
  KEY `med_ip` (`gal_ip`,`gal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `gvideo_voting_track`
--

LOCK TABLES `gvideo_voting_track` WRITE;
/*!40000 ALTER TABLE `gvideo_voting_track` DISABLE KEYS */;
/*!40000 ALTER TABLE `gvideo_voting_track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `media` (
  `med_id` int(11) NOT NULL auto_increment,
  `med_prof_id` int(11) unsigned default NULL,
  `med_type` enum('audio','video','photo') NOT NULL default 'photo',
  `med_file` varchar(50) default NULL,
  `med_title` varchar(150) default NULL,
  `med_status` enum('active','passive') NOT NULL default 'passive',
  `med_date` datetime default NULL,
  `rate_able` int(1) NOT NULL default '1',
  PRIMARY KEY  (`med_id`),
  KEY `med_prof_id` (`med_prof_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_rating`
--

DROP TABLE IF EXISTS `media_rating`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `media_rating` (
  `med_id` int(11) NOT NULL default '0',
  `med_rating_count` int(11) NOT NULL default '0',
  `med_rating_sum` int(11) NOT NULL default '0',
  UNIQUE KEY `med_id` (`med_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `media_rating`
--

LOCK TABLES `media_rating` WRITE;
/*!40000 ALTER TABLE `media_rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_voting_track`
--

DROP TABLE IF EXISTS `media_voting_track`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `media_voting_track` (
  `med_id` int(11) NOT NULL default '0',
  `med_ip` varchar(20) default NULL,
  `med_date` datetime default NULL,
  KEY `med_ip` (`med_ip`,`med_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `media_voting_track`
--

LOCK TABLES `media_voting_track` WRITE;
/*!40000 ALTER TABLE `media_voting_track` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_voting_track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members_as_aff`
--

DROP TABLE IF EXISTS `members_as_aff`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `members_as_aff` (
  `ID` bigint(10) NOT NULL auto_increment,
  `num_of_mem` int(5) NOT NULL default '0',
  `num_of_days` int(5) NOT NULL default '0',
  `MID` int(10) NOT NULL default '0',
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `members_as_aff`
--

LOCK TABLES `members_as_aff` WRITE;
/*!40000 ALTER TABLE `members_as_aff` DISABLE KEYS */;
/*!40000 ALTER TABLE `members_as_aff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `moderators`
--

DROP TABLE IF EXISTS `moderators`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `moderators` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(10) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `Password` varchar(32) NOT NULL default '',
  `status` enum('suspended','active','approval') NOT NULL default 'suspended',
  `reg_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Holds moderator accounts';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `moderators`
--

LOCK TABLES `moderators` WRITE;
/*!40000 ALTER TABLE `moderators` DISABLE KEYS */;
/*!40000 ALTER TABLE `moderators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `polls_a`
--

DROP TABLE IF EXISTS `polls_a`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `polls_a` (
  `IDanswer` int(10) unsigned NOT NULL auto_increment,
  `ID` int(11) NOT NULL default '0',
  `Answer` varchar(255) NOT NULL default '',
  `Votes` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`IDanswer`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `polls_a`
--

LOCK TABLES `polls_a` WRITE;
/*!40000 ALTER TABLE `polls_a` DISABLE KEYS */;
/*!40000 ALTER TABLE `polls_a` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `polls_q`
--

DROP TABLE IF EXISTS `polls_q`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `polls_q` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Question` varchar(255) NOT NULL default '',
  `Active` varchar(2) NOT NULL default 'on',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `polls_q`
--

LOCK TABLES `polls_q` WRITE;
/*!40000 ALTER TABLE `polls_q` DISABLE KEYS */;
/*!40000 ALTER TABLE `polls_q` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_forum`
--

DROP TABLE IF EXISTS `pre_forum`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pre_forum` (
  `forum_id` int(10) unsigned NOT NULL auto_increment,
  `forum_uri` varchar(255) NOT NULL default '',
  `cat_id` int(11) NOT NULL default '0',
  `forum_title` varchar(255) default NULL,
  `forum_desc` varchar(255) NOT NULL default '',
  `forum_posts` int(11) NOT NULL default '0',
  `forum_topics` int(11) NOT NULL default '0',
  `forum_last` int(11) NOT NULL default '0',
  `forum_type` enum('public','private') NOT NULL default 'public',
  PRIMARY KEY  (`forum_id`),
  KEY `cat_id` (`cat_id`),
  KEY `forum_uri` (`forum_uri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pre_forum`
--

LOCK TABLES `pre_forum` WRITE;
/*!40000 ALTER TABLE `pre_forum` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_forum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_forum_cat`
--

DROP TABLE IF EXISTS `pre_forum_cat`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pre_forum_cat` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `cat_uri` varchar(255) NOT NULL default '',
  `cat_name` varchar(255) default NULL,
  `cat_icon` varchar(32) NOT NULL default '',
  `cat_order` float NOT NULL default '0',
  PRIMARY KEY  (`cat_id`),
  KEY `cat_order` (`cat_order`),
  KEY `cat_uri` (`cat_uri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pre_forum_cat`
--

LOCK TABLES `pre_forum_cat` WRITE;
/*!40000 ALTER TABLE `pre_forum_cat` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_forum_cat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_forum_flag`
--

DROP TABLE IF EXISTS `pre_forum_flag`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pre_forum_flag` (
  `user` varchar(32) NOT NULL default '',
  `topic_id` int(11) NOT NULL default '0',
  `when` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user`,`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pre_forum_flag`
--

LOCK TABLES `pre_forum_flag` WRITE;
/*!40000 ALTER TABLE `pre_forum_flag` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_forum_flag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_forum_post`
--

DROP TABLE IF EXISTS `pre_forum_post`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pre_forum_post` (
  `post_id` int(10) unsigned NOT NULL auto_increment,
  `topic_id` int(11) NOT NULL default '0',
  `forum_id` int(11) NOT NULL default '0',
  `user` varchar(32) NOT NULL default '0',
  `post_text` mediumtext NOT NULL,
  `when` int(11) NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  PRIMARY KEY  (`post_id`),
  KEY `topic_id` (`topic_id`),
  KEY `forum_id` (`forum_id`),
  KEY `user` (`user`),
  KEY `when` (`when`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pre_forum_post`
--

LOCK TABLES `pre_forum_post` WRITE;
/*!40000 ALTER TABLE `pre_forum_post` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_forum_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_forum_report`
--

DROP TABLE IF EXISTS `pre_forum_report`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pre_forum_report` (
  `user_name` varchar(32) NOT NULL default '',
  `post_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_name`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pre_forum_report`
--

LOCK TABLES `pre_forum_report` WRITE;
/*!40000 ALTER TABLE `pre_forum_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_forum_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_forum_topic`
--

DROP TABLE IF EXISTS `pre_forum_topic`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pre_forum_topic` (
  `topic_id` int(10) unsigned NOT NULL auto_increment,
  `topic_uri` varchar(255) NOT NULL default '',
  `forum_id` int(11) NOT NULL default '0',
  `topic_title` varchar(255) NOT NULL default '',
  `when` int(11) NOT NULL default '0',
  `topic_posts` int(11) NOT NULL default '0',
  `first_post_user` varchar(32) NOT NULL default '0',
  `first_post_when` int(11) NOT NULL default '0',
  `last_post_user` varchar(32) NOT NULL default '',
  `last_post_when` int(11) NOT NULL default '0',
  `topic_sticky` int(11) NOT NULL default '0',
  `topic_locked` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`topic_id`),
  KEY `forum_id` (`forum_id`),
  KEY `forum_id_2` (`forum_id`,`when`),
  KEY `topic_uri` (`topic_uri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pre_forum_topic`
--

LOCK TABLES `pre_forum_topic` WRITE;
/*!40000 ALTER TABLE `pre_forum_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_forum_topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_forum_user`
--

DROP TABLE IF EXISTS `pre_forum_user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pre_forum_user` (
  `user_name` varchar(32) NOT NULL default '',
  `user_pwd` varchar(32) NOT NULL default '',
  `user_email` varchar(128) NOT NULL default '',
  `user_join_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pre_forum_user`
--

LOCK TABLES `pre_forum_user` WRITE;
/*!40000 ALTER TABLE `pre_forum_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_forum_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_forum_user_activity`
--

DROP TABLE IF EXISTS `pre_forum_user_activity`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pre_forum_user_activity` (
  `user` varchar(32) NOT NULL default '',
  `act_current` int(11) NOT NULL default '0',
  `act_last` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pre_forum_user_activity`
--

LOCK TABLES `pre_forum_user_activity` WRITE;
/*!40000 ALTER TABLE `pre_forum_user_activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_forum_user_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_forum_user_stat`
--

DROP TABLE IF EXISTS `pre_forum_user_stat`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pre_forum_user_stat` (
  `user` varchar(32) NOT NULL default '',
  `posts` int(11) NOT NULL default '0',
  `user_last_post` int(11) NOT NULL default '0',
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pre_forum_user_stat`
--

LOCK TABLES `pre_forum_user_stat` WRITE;
/*!40000 ALTER TABLE `pre_forum_user_stat` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_forum_user_stat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_forum_vote`
--

DROP TABLE IF EXISTS `pre_forum_vote`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pre_forum_vote` (
  `user_name` varchar(32) NOT NULL default '',
  `post_id` int(11) NOT NULL default '0',
  `vote_when` int(11) NOT NULL default '0',
  `vote_point` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`user_name`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pre_forum_vote`
--

LOCK TABLES `pre_forum_vote` WRITE;
/*!40000 ALTER TABLE `pre_forum_vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_forum_vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_rating`
--

DROP TABLE IF EXISTS `profile_rating`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `profile_rating` (
  `pr_id` bigint(8) NOT NULL default '0',
  `pr_rating_count` int(11) NOT NULL default '0',
  `pr_rating_sum` int(11) NOT NULL default '0',
  UNIQUE KEY `med_id` (`pr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `profile_rating`
--

LOCK TABLES `profile_rating` WRITE;
/*!40000 ALTER TABLE `profile_rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_voting_track`
--

DROP TABLE IF EXISTS `profile_voting_track`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `profile_voting_track` (
  `pr_id` bigint(8) NOT NULL default '0',
  `pr_ip` varchar(20) default NULL,
  `pr_date` datetime default NULL,
  KEY `pr_ip` (`pr_ip`,`pr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `profile_voting_track`
--

LOCK TABLES `profile_voting_track` WRITE;
/*!40000 ALTER TABLE `profile_voting_track` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile_voting_track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shareMusicFavorites`
--

DROP TABLE IF EXISTS `shareMusicFavorites`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `shareMusicFavorites` (
  `medID` int(12) NOT NULL default '0',
  `userID` bigint(12) unsigned NOT NULL default '0',
  `favDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`medID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `shareMusicFavorites`
--

LOCK TABLES `shareMusicFavorites` WRITE;
/*!40000 ALTER TABLE `shareMusicFavorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `shareMusicFavorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sharePhotoFavorites`
--

DROP TABLE IF EXISTS `sharePhotoFavorites`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sharePhotoFavorites` (
  `medID` int(12) NOT NULL default '0',
  `userID` bigint(12) unsigned NOT NULL default '0',
  `favDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`medID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `sharePhotoFavorites`
--

LOCK TABLES `sharePhotoFavorites` WRITE;
/*!40000 ALTER TABLE `sharePhotoFavorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `sharePhotoFavorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sharePhotoFiles`
--

DROP TABLE IF EXISTS `sharePhotoFiles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sharePhotoFiles` (
  `medID` int(12) NOT NULL auto_increment,
  `medProfId` int(12) unsigned default NULL,
  `medExt` varchar(4) default '',
  `medTitle` varchar(255) default '',
  `medUri` varchar(255) NOT NULL default '',
  `medDesc` text NOT NULL,
  `medTags` varchar(255) NOT NULL default '',
  `medDate` int(20) NOT NULL default '0',
  `medViews` int(12) default '0',
  `Approved` enum('true','false') NOT NULL default 'true',
  PRIMARY KEY  (`medID`),
  UNIQUE KEY `medUri` (`medUri`),
  KEY `medProfId` (`medProfId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `sharePhotoFiles`
--

LOCK TABLES `sharePhotoFiles` WRITE;
/*!40000 ALTER TABLE `sharePhotoFiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `sharePhotoFiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shareVideoFavorites`
--

DROP TABLE IF EXISTS `shareVideoFavorites`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `shareVideoFavorites` (
  `medID` int(12) NOT NULL default '0',
  `userID` bigint(12) unsigned NOT NULL default '0',
  `favDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`medID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `shareVideoFavorites`
--

LOCK TABLES `shareVideoFavorites` WRITE;
/*!40000 ALTER TABLE `shareVideoFavorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `shareVideoFavorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `smiles`
--

DROP TABLE IF EXISTS `smiles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `smiles` (
  `ID` int(10) unsigned NOT NULL default '0',
  `code` varchar(8) NOT NULL default '',
  `smile_url` varchar(255) NOT NULL default '',
  `emoticon` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `smile` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `smiles`
--

LOCK TABLES `smiles` WRITE;
/*!40000 ALTER TABLE `smiles` DISABLE KEYS */;
INSERT INTO `smiles` VALUES (1,':D','icon_biggrin.gif','Very Happy'),(2,':-D','icon_biggrin.gif','Very Happy'),(3,':grin:','icon_biggrin.gif','Very Happy'),(4,':)','icon_smile.gif','Smile'),(5,':-)','icon_smile.gif','Smile'),(6,':smile:','icon_smile.gif','Smile'),(7,':(','icon_sad.gif','Sad'),(8,':-(','icon_sad.gif','Sad'),(9,':sad:','icon_sad.gif','Sad'),(10,':o','icon_surprised.gif','Surprised'),(11,':-o','icon_surprised.gif','Surprised'),(12,':eek:','icon_surprised.gif','Surprised'),(13,':shock:','icon_eek.gif','Shocked'),(14,':?','icon_confused.gif','Confused'),(15,':-?','icon_confused.gif','Confused'),(16,':???:','icon_confused.gif','Confused'),(17,'8)','icon_cool.gif','Cool'),(18,'8-)','icon_cool.gif','Cool'),(19,':cool:','icon_cool.gif','Cool'),(20,':lol:','icon_lol.gif','Laughing'),(21,':x','icon_mad.gif','Mad'),(22,':-x','icon_mad.gif','Mad'),(23,':mad:','icon_mad.gif','Mad'),(24,':P','icon_razz.gif','Razz'),(25,':-P','icon_razz.gif','Razz'),(26,':razz:','icon_razz.gif','Razz'),(27,':oops:','icon_redface.gif','Embarassed'),(28,':cry:','icon_cry.gif','Crying or Very sad'),(29,':evil:','icon_evil.gif','Evil or Very Mad'),(30,':twisted','icon_twisted.gif','Twisted Evil'),(31,':roll:','icon_rolleyes.gif','Rolling Eyes'),(32,':wink:','icon_wink.gif','Wink'),(33,';)','icon_wink.gif','Wink'),(34,';-)','icon_wink.gif','Wink'),(35,':!:','icon_exclaim.gif','Exclamation'),(36,':?:','icon_question.gif','Question'),(37,':idea:','icon_idea.gif','Idea'),(38,':arrow:','icon_arrow.gif','Arrow'),(39,':|','icon_neutral.gif','Neutral'),(40,':-|','icon_neutral.gif','Neutral'),(41,':neutral','icon_neutral.gif','Neutral'),(42,':mrgreen','icon_mrgreen.gif','Mr. Green');
/*!40000 ALTER TABLE `smiles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-08-15 23:42:55
