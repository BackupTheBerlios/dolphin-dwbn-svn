--
-- Database: v 6.1
-- 

-- --------------------------------------------------------

SET NAMES 'utf8';
DROP TABLE IF EXISTS `AdminBanList`, `AdminLinks`, `AdminMenu`, `AdminMenuCateg`, `Admins`, `aff`, `aff_banners`, `aff_members`, `Articles`, `ArticlesCategory`, `Banners`, `BannersClicks`, `BannersShows`, `BlockList`, `BlogCategories`, `BlogPosts`, `Blogs`, `BoughtContacts`, `Classifieds`, `ClassifiedsAdvertisements`, `ClassifiedsAdvertisementsMedia`, `ClassifiedsSubs`, `CmtsBlogPosts`, `CmtsClassifieds`, `CmtsProfile`, `CmtsSharedMusic`, `CmtsSharedPhoto`, `CmtsSharedVideo`, `CmtsTrack`, `ColorBase`, `Countries`, `DailyQuotes`, `FriendList`, `GalleryAlbums`, `GalleryObjects`, `GlParams`, `GlParamsKateg`, `gmusic_rating`, `gmusic_voting_track`, `gphoto_rating`, `gphoto_voting_track`, `Groups`, `GroupsCateg`, `GroupsGallery`, `GroupsMembers`, `grp_forum`, `grp_forum_cat`, `grp_forum_flag`, `grp_forum_post`, `grp_forum_report`, `grp_forum_topic`, `grp_forum_user`, `grp_forum_user_activity`, `grp_forum_user_stat`, `grp_forum_vote`, `Guestbook`, `gvideo_rating`, `gvideo_voting_track`, `HotList`, `IMessages`, `Links`, `LocalizationCategories`, `LocalizationKeys`, `LocalizationLanguages`, `LocalizationStringParams`, `LocalizationStrings`, `media`, `media_rating`, `media_voting_track`, `MemActions`, `MemActionsTrack`, `members_as_aff`, `MemLevelActions`, `MemLevelPrices`, `MemLevels`, `Messages`, `moderators`, `Modules`, `News`, `NotifyEmails`, `NotifyMsgs`, `NotifyQueue`, `PageCompose`, `PaymentParameters`, `PaymentProviders`, `PaymentSubscriptions`, `polls_a`, `polls_q`, `PreValues`, `pre_forum`, `pre_forum_cat`, `pre_forum_flag`, `pre_forum_post`, `pre_forum_report`, `pre_forum_topic`, `pre_forum_user`, `pre_forum_user_activity`, `pre_forum_user_stat`, `pre_forum_vote`, `PrivPhotosRequests`, `ProfileFields`, `ProfileMemLevels`, `Profiles`, `ProfilesMatch`, `ProfilesPolls`, `ProfilesSettings`, `ProfilesTrack`, `profile_rating`, `profile_voting_track`, `RayBoardBoards`, `RayChatCurrentUsers`, `RayChatMessages`, `RayChatProfiles`, `RayChatRooms`, `RayChatRoomsUsers`, `RayGlobalTrackUsers`, `RayImContacts`, `RayImMessages`, `RayImPendings`, `RayImProfiles`, `RayMovieFiles`, `RayMoviePlayLists`, `RayMp3Categories`, `RayMp3Files`, `RayMp3PlayLists`, `RayMusicCategories`, `RayMusicFiles`, `RayMusicPlayLists`, `RayShoutboxMessages`, `RayVideoStats`, `SDatingEvents`, `SDatingMatches`, `SDatingParticipants`, `shareMusicFavorites`, `sharePhotoFavorites`, `sharePhotoFiles`, `shareVideoFavorites`, `shoutbox`, `SiteStat`, `smiles`, `Stories`, `Tags`, `TopMenu`, `Transactions`, `VKisses`, `Votes`, `VotesPhotos`, `ZIPCodes`;
ALTER DATABASE DEFAULT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci';

--
-- Table structure for table `AdminBanList`
--

CREATE TABLE `AdminBanList` (
  `ProfID` int(11) NOT NULL default '0',
  `Time` int(20) NOT NULL default '0',
  `DateTime` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `AdminBanList`
--


-- --------------------------------------------------------

--
-- Table structure for table `AdminLinks`
-- 

CREATE TABLE `AdminLinks` (
  `Title` varchar(30) NOT NULL default '',
  `Url` varchar(150) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `AdminLinks`
-- 

INSERT INTO `AdminLinks` VALUES ('Website Homepage', '{site}');
INSERT INTO `AdminLinks` VALUES('Search Profiles', 'profiles.php');
INSERT INTO `AdminLinks` VALUES('Dolphin Documentation', 'http://www.boonex.com/trac/dolphin/wiki/DolphinDocs');
INSERT INTO `AdminLinks` VALUES ('Dolphin Support', 'http://www.expertzzz.com/forumz/');
INSERT INTO `AdminLinks` VALUES('Dolphin Development', 'http://www.boonex.com/trac/dolphin/');
INSERT INTO `AdminLinks` VALUES ('Dolphin Extras', 'http://www.expertzzz.com/Downloadz/home/dolphin/');
INSERT INTO `AdminLinks` VALUES ('BoonEx', 'http://www.boonex.com/');
INSERT INTO `AdminLinks` VALUES('BoonEx Blog', 'http://www.boonex.com/unity/');
INSERT INTO `AdminLinks` VALUES('BoonEx Dolphin', 'http://www.boonex.com/trac/products/dolphin/');
INSERT INTO `AdminLinks` VALUES ('BoonEx Ray', 'http://www.boonex.com/products/ray/');
INSERT INTO `AdminLinks` VALUES ('BoonEx Orca', 'http://www.boonex.com/products/orca/');
INSERT INTO `AdminLinks` VALUES ('BoonEx Barracuda', 'http://www.boonex.com/products/barracuda/');
INSERT INTO `AdminLinks` VALUES ('BoonEx Shark', 'http://www.boonex.com/products/shark/');
INSERT INTO `AdminLinks` VALUES('BoonEx Affiliate', 'http://www.boonex.com/affiliate/');

-- --------------------------------------------------------

-- 
-- Table structure for table `AdminMenu`
-- 

CREATE TABLE `AdminMenu`(
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Title` varchar(50) NOT NULL default '',
  `Url` varchar(255) NOT NULL default '',
  `Desc` varchar(255) NOT NULL default '',
  `Check` varchar(255) NOT NULL default '',
  `Order` float NOT NULL default '0',
  `Categ` int(10) unsigned NOT NULL default '0',
  `Icon` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `AdminMenu`
-- 

INSERT INTO `AdminMenu` VALUES (1, 'Members', 'profiles.php', 'For members profiles management\n', '', 0, 1, 'members.gif');
INSERT INTO `AdminMenu` VALUES (2, 'Affiliates', '../aff/partners.php', 'This is for setting up your affiliate programs and your affiliate program members', '', 1, 1, 'affilates.gif');
INSERT INTO `AdminMenu` VALUES (3, 'Moderators', 'moderators.php', 'For managing your moderators who can help you with site administration', '', 2, 1, 'moderators.gif');
INSERT INTO `AdminMenu` VALUES (4, 'Events', 'sdating_admin.php', 'Provides you with the ability to manage Events created by members and by the administrator, too. You have the capacity to edit, delete or view event participants and matches', '', 6, 2, 'events.gif');
INSERT INTO `AdminMenu` VALUES (5, 'Groups', 'groups.php', 'Here you are able to manage groups and its categories', '', 8, 2, 'groups.gif');
INSERT INTO `AdminMenu` VALUES (6, 'Feedback', '../story.php', 'This is for feedback administration, and you can edit, delete or activate site member feedback', '', 12, 2, 'feedback.gif');
INSERT INTO `AdminMenu` VALUES (8, 'Links Page', 'links.php', 'Here you can manage links on your links page', '', 4, 3, 'links_page.gif');
INSERT INTO `AdminMenu` VALUES (9, 'Admin Articles', 'articles.php', 'This is the place for controlling your site articles: edit, delete or add them', '', 5, 3, 'articles.gif');
INSERT INTO `AdminMenu` VALUES (10, 'Site News', 'news.php', 'This is for managing the news area on your web site - add, edit, activate or delete the old items', '', 6, 3, 'site_news.gif');
INSERT INTO `AdminMenu` VALUES (11, 'Random Quotes', 'quotes.php', 'You can manage quotes, which appear on the index page, from this section', '', 7, 3, 'random_quotes.gif');
INSERT INTO `AdminMenu` VALUES (12, 'Mass Mailer', 'notifies.php', 'Using this function you are able to send a newsletter to your site members', '', 0, 3, 'mass_mailer.gif');
INSERT INTO `AdminMenu` VALUES (13, 'Money Calculator', 'finance.php', 'Provides you with site income information to help you in administration', '', 1, 3, 'money_calculator.gif');
INSERT INTO `AdminMenu` VALUES (14, 'Database Backup', 'db.php', 'Make a backup of your site database with this utility', '', 2, 3, 'database_backup.gif');
INSERT INTO `AdminMenu` VALUES (15, 'Ray Suite', 'javascript:openRayWidget(''global'', ''admin'', ''{adminLogin}'', ''{adminPass}'');', 'Ray Community Widget Suite administration panel is available here', 'return ( ''on'' == getParam( ''enable_ray'' ) );', 0, 4, 'boonex_ray_widgets.gif');
INSERT INTO `AdminMenu` VALUES (16, 'Orca Forum', '../orca/', 'Administration Panel for Orca - Interactive Forum Script', '', 1, 4, 'boonex_orca_forum.gif');
INSERT INTO `AdminMenu` VALUES (17, 'Polls', 'post_mod_ppolls.php', 'Members can create their own polls, and you can moderate them right here', '', 10, 2, 'polls.gif');
INSERT INTO `AdminMenu` VALUES (19, 'Banners', 'banners.php', 'Provides you with the ability to manage banners on your web site', '', 8, 3, 'banners.gif');
INSERT INTO `AdminMenu` VALUES (20, 'Photos', 'browseMedia.php?type=photo', 'For management of pictures uploaded / shared by site members', '', 0, 2, 'photos.gif');
INSERT INTO `AdminMenu` VALUES (22, 'Blogs', 'post_mod_blog.php', 'Site administrators can check the content written in the users'' blog to avoid unwanted or prohibited expressions', '', 4, 2, 'blogs.gif');
INSERT INTO `AdminMenu` VALUES (23, 'Profiles', '../aff/profiles.php', '', '', 0, 6, '');
INSERT INTO `AdminMenu` VALUES (24, 'Money Calculator', '../aff/finance.php', '', '', 1, 6, 'money_calculator.gif');
INSERT INTO `AdminMenu` VALUES (25, 'My Link', '../aff/help.php', '', '', 2, 6, 'links_page.gif');
INSERT INTO `AdminMenu` VALUES (26, 'Admin Password', 'global_settings.php?cat=ap', 'Change a password for access to administration panel here', '', 0, 5, 'admin_password.gif');
INSERT INTO `AdminMenu` VALUES(27, 'Email Templates', 'global_settings.php?cat=4', 'For setting up email texts which are sent from your website to members automatically', '', 3, 5, 'email_templates.gif');
INSERT INTO `AdminMenu` VALUES(28, 'Membership Levels', 'memb_levels.php', 'For setting up different membership levels, different actions for each membership level and action limits', '', 5, 5, 'membership_levels.gif');
INSERT INTO `AdminMenu` VALUES(31, 'CSS Styles Editor', 'css_file.php', 'For CSS files management: to make changes in your current template', '', 6, 5, 'css_styles_editor.gif');
INSERT INTO `AdminMenu` VALUES (34, 'Payments Settings', 'payment_providers.php', 'For setting up Payment Providers you want to use', '', 8, 5, 'payment_settings.gif');
INSERT INTO `AdminMenu` VALUES(35, 'Fields Builder', 'fields.php', 'For member profile fields management', '', 0, 7, 'photo_page_builder.gif');
INSERT INTO `AdminMenu` VALUES (39, 'Blogs Settings', 'global_settings.php?cat=22', 'For member blogs settings management', '', 9, 5, 'blogs_settings.gif');
INSERT INTO `AdminMenu` VALUES (40, 'News Settings', 'global_settings.php?cat=10', 'For setting up News parameters', '', 10, 5, 'news_settings.gif');
INSERT INTO `AdminMenu` VALUES (41, 'Polls Settings', 'global_settings.php?cat=20', 'For enabling/disabling polls, setting up number of polls a site member can create', '', 13, 5, 'polls.gif');
INSERT INTO `AdminMenu` VALUES (42, 'Groups Settings', 'global_settings.php?cat=24', 'Group feature management: notification emails, the thumbs size, etc.', '', 11, 5, 'groups_settings.gif');
INSERT INTO `AdminMenu` VALUES (43, 'Tags Settings', 'global_settings.php?cat=25', 'For tags settings, which will work for search and browse options', '', 12, 5, 'tags_settings.gif');
INSERT INTO `AdminMenu` VALUES (66, 'Advanced Settings', 'global_settings.php?cat=1&', 'More enhanced settings for your site features', '', 2, 5, 'adv_settings.gif');
INSERT INTO `AdminMenu` VALUES(50, 'Database Pruning', 'global_settings.php?cat=11', 'For Database management: clearing of old, unnecessary information', '', 15, 5, 'database_prunning.gif');
INSERT INTO `AdminMenu` VALUES (52, 'Basic Settings', 'basic_settings.php', 'For managing site system settings', '', 1, 5, 'basic_settings.gif');
INSERT INTO `AdminMenu` VALUES(55, 'Meta Tags', 'global_settings.php?cat=19', 'Setting up Meta Tags to facilitate search engine indexing for your website', '', 16, 5, 'meta_tags.gif');
INSERT INTO `AdminMenu` VALUES(59, 'Moderation Settings', 'global_settings.php?cat=6', 'To enable/disable pre-moderation of members profiles, members photos, etc.', '', 14, 5, 'members.gif');
INSERT INTO `AdminMenu` VALUES(60, 'Languages Settings', 'lang_file.php', 'For languages management your website is using and making changes in your website content', '', 4, 5, 'languages_settings.gif');
INSERT INTO `AdminMenu` VALUES(62, 'Pages Builder', 'pageBuilder.php', 'Compose blocks for the site pages here', '', 2, 7, 'homepage_builder.gif');
INSERT INTO `AdminMenu` VALUES(63, 'Navigation Menu Builder', 'menu_compose.php', 'For top menu items management', '', 1, 7, 'navigation_menu_builder.gif');
INSERT INTO `AdminMenu` VALUES (65, 'Classifieds', 'manage_classifieds.php', 'Administrator can manage classifieds categories, subcategories, etc.', '', 11, 2, 'classifieds.gif');
INSERT INTO `AdminMenu` VALUES (67, 'Videos', 'browseMedia.php?type=video', 'For management of video files which have been uploaded / shared by site members', '', 1, 2, 'videos.gif');
INSERT INTO `AdminMenu` VALUES (68, 'Music', 'browseMedia.php?type=music', 'For management of music files which have been uploaded / shared by site members', '', 2, 2, 'music.gif');
INSERT INTO `AdminMenu` VALUES(74, 'Admin Polls', 'polls.php', 'For site poll posting and management', '', 3, 3, 'admin_polls.gif');
INSERT INTO `AdminMenu` VALUES (76, 'Profile Photos', 'post_mod_photos.php?media=photo&status=passive', 'For pictures uploaded by a member for pre-moderation. This can be helpful to protect your site from nude or other unsuitable pics', '', 3, 2, 'photos.gif');
INSERT INTO `AdminMenu` VALUES (77, 'Profile Music', 'post_mod_audio.php', 'For management of music files which have been uploaded by members to their profiles.', '', 7, 2, 'music.gif');
INSERT INTO `AdminMenu` VALUES (78, 'Profile Videos', 'javascript:window.open(''../ray/modules/video/app/admin.swf?nick={adminLogin}&password={adminPass}&url=../../../XML.php'',''RayVideoAdmin'',''width=700,height=330,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=0'');', 'For management of video files which have been uploaded by members to their profiles.', '', 5, 2, 'videos.gif');
INSERT INTO `AdminMenu` VALUES (79, 'Profile Backgrounds', 'post_mod_profiles.php', 'For post-moderation of pictures which have been uploaded by members for their profile background.', '', 9, 2, 'backgrounds.gif');
INSERT INTO `AdminMenu` VALUES (80, 'Modules', 'modules.php', 'Manage and configure integration modules for 3d party scripts', '', 9, 3, 'modules.gif');
INSERT INTO `AdminMenu` VALUES(81, 'Permalinks', 'global_settings.php?cat=26', 'Friendly permalinks activation', '', 17, 5, 'permalinks.gif');
INSERT INTO `AdminMenu` VALUES(82, 'Predefined Values', 'preValues.php', '', '', 7, 5, 'preValues.gif');

-- --------------------------------------------------------

-- 
-- Table structure for table `AdminMenuCateg`
-- 

CREATE TABLE `AdminMenuCateg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Title` varchar(50) NOT NULL default '',
  `Order` int(11) NOT NULL default '0',
  `Icon` varchar(50) NOT NULL default '',
  `Icon_thumb` varchar(50) NOT NULL default '',
  `User` enum('admin','aff','moderator') NOT NULL default 'admin',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `AdminMenuCateg`
-- 

INSERT INTO `AdminMenuCateg` VALUES (1, 'Users', 0, 'guy.png', 'guy_t.png', 'admin');
INSERT INTO `AdminMenuCateg` VALUES (2, 'Content', 1, 'attach.png', 'attach_t.png', 'admin');
INSERT INTO `AdminMenuCateg` VALUES (3, 'Tools', 2, 'tools.png', 'tools_t.png', 'admin');
INSERT INTO `AdminMenuCateg` VALUES (4, 'Plugins', 3, 'plugin.png', 'plugin_t.png', 'admin');
INSERT INTO `AdminMenuCateg` VALUES (5, 'Settings', 5, 'setup.png', 'setup_t.png', 'admin');
INSERT INTO `AdminMenuCateg` VALUES (6, 'Affiliate', 6, 'guy.png', 'guy_t.png', 'aff');
INSERT INTO `AdminMenuCateg` VALUES (7, 'Builders', 4, 'cubes.png', 'cubes_t.png', 'admin');

-- --------------------------------------------------------

-- 
-- Table structure for table `Admins`
-- 

CREATE TABLE `Admins` (
  `Name` varchar(10) NOT NULL default '',
  `Password` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `Admins`
-- 

INSERT INTO `Admins` VALUES ( 'admin', '36cdf8b887a5cffc78dcd5c08991b993');

-- --------------------------------------------------------

-- 
-- Table structure for table `aff`
-- 

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

-- 
-- Dumping data for table `aff`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `aff_banners`
-- 

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

-- 
-- Dumping data for table `aff_banners`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `aff_members`
-- 

CREATE TABLE `aff_members` (
  `idAff` bigint(8) NOT NULL default '0',
  `idProfile` bigint(8) NOT NULL default '0',
  PRIMARY KEY  (`idAff`,`idProfile`),
  UNIQUE KEY `idProfile` (`idProfile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `aff_members`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `Articles`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `Articles`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ArticlesCategory`
-- 

CREATE TABLE `ArticlesCategory` (
  `CategoryID` int(11) NOT NULL auto_increment,
  `CategoryName` varchar(255) NOT NULL default '',
  `CategoryUri` varchar(255) NOT NULL default '',
  `CategoryDescription` varchar(255) default NULL,
  PRIMARY KEY  (`CategoryID`),
  UNIQUE KEY `CategoryUri` (`CategoryUri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `ArticlesCategory`
-- 

INSERT INTO `ArticlesCategory` VALUES (1, 'Default','Default', 'Default category for article');

-- --------------------------------------------------------

-- 
-- Table structure for table `Banners`
-- 

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

-- 
-- Dumping data for table `Banners`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `BannersClicks`
-- 

CREATE TABLE `BannersClicks` (
  `ID` int(10) unsigned NOT NULL default '0',
  `Date` date NOT NULL default '0000-00-00',
  `IP` varchar(16) NOT NULL default '',
  UNIQUE KEY `ID_2` (`ID`,`Date`,`IP`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `BannersClicks`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `BannersShows`
-- 

CREATE TABLE `BannersShows` (
  `ID` int(10) unsigned NOT NULL default '0',
  `Date` date NOT NULL default '0000-00-00',
  `IP` varchar(16) NOT NULL default '',
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `BannersShows`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `BlockList`
-- 

CREATE TABLE `BlockList` (
  `ID` bigint(8) NOT NULL default '0',
  `Profile` bigint(8) NOT NULL default '0',
  UNIQUE KEY `BlockPair` (`ID`,`Profile`),
  KEY `ID` (`ID`),
  KEY `Profile` (`Profile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `BlockList`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `BlogCategories`
-- 

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

-- 
-- Dumping data for table `BlogCategories`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `BlogPosts`
-- 

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

-- 
-- Dumping data for table `BlogPosts`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `Blogs`
-- 

CREATE TABLE `Blogs` (
  `ID` int(5) unsigned NOT NULL auto_increment,
  `OwnerID` int(3) unsigned NOT NULL default '0',
  `Description` varchar(255) NOT NULL default '',
  `Other` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `OwnerID` (`OwnerID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `Blogs`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `BoughtContacts`
-- 

CREATE TABLE `BoughtContacts` (
  `IDBuyer` bigint(20) unsigned NOT NULL default '0',
  `IDContact` bigint(20) unsigned NOT NULL default '0',
  `TransactionID` bigint(20) unsigned default NULL,
  `HideFromBuyer` tinyint(1) NOT NULL default '0',
  `HideFromContact` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`IDBuyer`,`IDContact`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `BoughtContacts`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `Classifieds`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `Classifieds`
-- 

INSERT INTO `Classifieds` VALUES (1, 'Jobs', 'Jobs', 'There is Jobs description', 'salary', 'salary', '>', '<', 'EUR');
INSERT INTO `Classifieds` VALUES (2, 'Music Exchange', 'Music-Exchange', 'music exchange desc', 'price', 'price', '>', '<', '$');
INSERT INTO `Classifieds` VALUES (4, 'Housing & Rentals', 'Housing-_-Rentals', 'Housing & Rentals desc', 'rental', NULL, '>', NULL, '$');
INSERT INTO `Classifieds` VALUES (5, 'Services', 'Services', 'Services desc', 'price', NULL, '=', NULL, '$');
INSERT INTO `Classifieds` VALUES (7, 'Casting Calls', 'Casting-Calls', 'Casting Calls desc', NULL, NULL, NULL, NULL, '$');
INSERT INTO `Classifieds` VALUES (8, 'Personals', 'Personals', 'Personals desc', 'payment', NULL, '=', NULL, '$');
INSERT INTO `Classifieds` VALUES (9, 'For Sale', 'For-Sale', 'For Sale desc', 'price', 'price', '>', '<', '$');
INSERT INTO `Classifieds` VALUES(10, 'Cars For Sale', 'Cars-For-Sale', 'Cars For Sale desc', 'price', 'price', '>', '<', '€');

-- --------------------------------------------------------

-- 
-- Table structure for table `ClassifiedsAdvertisements`
-- 

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

-- 
-- Dumping data for table `ClassifiedsAdvertisements`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ClassifiedsAdvertisementsMedia`
-- 

CREATE TABLE `ClassifiedsAdvertisementsMedia` (
  `MediaID` int(11) unsigned NOT NULL auto_increment,
  `MediaProfileID` int(11) unsigned NOT NULL default '0',
  `MediaType` enum('photo','other') NOT NULL default 'photo',
  `MediaFile` varchar(50) NOT NULL default '',
  `MediaDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`MediaID`),
  KEY `med_prof_id` (`MediaProfileID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `ClassifiedsAdvertisementsMedia`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ClassifiedsSubs`
-- 

CREATE TABLE `ClassifiedsSubs` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `IDClassified` int(11) unsigned default NULL,
  `NameSub` varchar(128) NOT NULL default '',
  `SEntryUri` varchar(128) NOT NULL default '',
  `Description` varchar(150) default 'No description',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `SEntryUri` (`SEntryUri`),
  KEY `IDClassified` (`IDClassified`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `ClassifiedsSubs`
-- 

INSERT INTO `ClassifiedsSubs` VALUES (4, 2, 'positions and openings', 'positions-and-openings', 'positions and openings desc');
INSERT INTO `ClassifiedsSubs` VALUES (5, 2, 'instruments for sale', 'instruments-for-sale', 'instruments for sale desc');
INSERT INTO `ClassifiedsSubs` VALUES (6, 2, 'instruments wanted', 'instruments-wanted', 'instruments wanted desc');
INSERT INTO `ClassifiedsSubs` VALUES (7, 3, 'activities', 'activities', 'activities desc');
INSERT INTO `ClassifiedsSubs` VALUES (8, 3, 'artists', 'artists', 'artists desc');
INSERT INTO `ClassifiedsSubs` VALUES (9, 3, 'childcare', 'childcare', 'childcare desc');
INSERT INTO `ClassifiedsSubs` VALUES (10, 4, 'apartments / housing', 'apartments-housing', 'apartments / housing description');
INSERT INTO `ClassifiedsSubs` VALUES (11, 4, 'real estate for sale', 'real-estate-for-sale', 'real estate for sale description');
INSERT INTO `ClassifiedsSubs` VALUES (12, 4, 'roommates', 'roommates', 'roommates description');
INSERT INTO `ClassifiedsSubs` VALUES (38, 1, 'accounting / finance', 'accounting-finance', 'accounting / finance desc');
INSERT INTO `ClassifiedsSubs` VALUES (36, 5, 'automotive', 'automotive', 'automotive desc');
INSERT INTO `ClassifiedsSubs` VALUES (43, 1, 'education / nonprofit sec', 'education-nonprofit sec', 'education / nonprofit sector desc');
INSERT INTO `ClassifiedsSubs` VALUES (47, 1, 'government / legal', 'government-legal', 'government/legal desc');
INSERT INTO `ClassifiedsSubs` VALUES (84, 1, 'programming / web design', 'programming-web design', 'programming / web design desc');
INSERT INTO `ClassifiedsSubs` VALUES (54, 1, 'other', 'other', 'other desc');
INSERT INTO `ClassifiedsSubs` VALUES (55, 4, 'temporary vacation rental', 'temporary-vacation-rental', 'temporary vacation rentals desc');
INSERT INTO `ClassifiedsSubs` VALUES (56, 4, 'office / commercial', 'office-commercial', 'office / commercial  desc');
INSERT INTO `ClassifiedsSubs` VALUES (58, 5, 'financial', 'financial', 'financial');
INSERT INTO `ClassifiedsSubs` VALUES (60, 5, 'labor / move', 'labor-move', 'labor/move desc');
INSERT INTO `ClassifiedsSubs` VALUES (61, 5, 'legal', 'legal', 'legal desc');
INSERT INTO `ClassifiedsSubs` VALUES (62, 5, 'educational', 'educational', 'educational desc');
INSERT INTO `ClassifiedsSubs` VALUES (64, 7, 'acting', 'acting', 'acting desc');
INSERT INTO `ClassifiedsSubs` VALUES (65, 7, 'dance', 'dance', 'dance desc');
INSERT INTO `ClassifiedsSubs` VALUES (83, 7, 'musicians', 'musicians', 'musicians desc');
INSERT INTO `ClassifiedsSubs` VALUES (67, 7, 'modeling', 'modeling', 'modeling desc');
INSERT INTO `ClassifiedsSubs` VALUES (68, 7, 'reality shows', 'reality-shows', 'reality shows  desc');
INSERT INTO `ClassifiedsSubs` VALUES (69, 8, 'men seeking women', 'men-seeking-women', 'men seeking women desc');
INSERT INTO `ClassifiedsSubs` VALUES (70, 8, 'women seeking men', 'women-seeking-men', 'women seeking men desc');
INSERT INTO `ClassifiedsSubs` VALUES (71, 8, 'women seeking women', 'women-seeking-women', 'women seeking women desc');
INSERT INTO `ClassifiedsSubs` VALUES (72, 8, 'men seeking men', 'men-seeking-men', 'men seeking men desc');
INSERT INTO `ClassifiedsSubs` VALUES (73, 8, 'missed connections', 'missed-connections', 'missed connections desc');
INSERT INTO `ClassifiedsSubs` VALUES (74, 9, 'barter', 'barter', 'barter desc');
INSERT INTO `ClassifiedsSubs` VALUES (77, 9, 'clothing', 'clothing', 'clothing desc');
INSERT INTO `ClassifiedsSubs` VALUES (78, 9, 'collectibles', 'collectibles', 'collectibles desc');
INSERT INTO `ClassifiedsSubs` VALUES (79, 9, 'miscellaneous', 'miscellaneous', 'miscellaneous desc');
INSERT INTO `ClassifiedsSubs` VALUES (80, 10, 'autos / trucks', 'autos-trucks', 'autos / trucks desc');
INSERT INTO `ClassifiedsSubs` VALUES (81, 10, 'motorcycles', 'motorcycles', 'motorcycles desc');
INSERT INTO `ClassifiedsSubs` VALUES (82, 10, 'auto parts', 'auto-parts', 'auto parts desc');

-- --------------------------------------------------------

--
-- Table structure for table `CmtsBlogPosts`
--

CREATE TABLE `CmtsBlogPosts` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(11) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CmtsBlogPosts`
--


-- --------------------------------------------------------

--
-- Table structure for table `CmtsClassifieds`
--

CREATE TABLE `CmtsClassifieds` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(11) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CmtsClassifieds`
--


-- --------------------------------------------------------

--
-- Table structure for table `CmtsProfile`
--

CREATE TABLE `CmtsProfile` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(11) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CmtsProfile`
--


-- --------------------------------------------------------

--
-- Table structure for table `CmtsSharedMusic`
--

CREATE TABLE `CmtsSharedMusic` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(12) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CmtsSharedMusic`
--


-- --------------------------------------------------------

--
-- Table structure for table `CmtsSharedPhoto`
--

CREATE TABLE `CmtsSharedPhoto` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(12) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CmtsSharedPhoto`
--


-- --------------------------------------------------------

--
-- Table structure for table `CmtsSharedVideo`
--

CREATE TABLE `CmtsSharedVideo` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(12) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CmtsSharedVideo`
--


-- --------------------------------------------------------

--
-- Table structure for table `CmtsTrack`
--

CREATE TABLE `CmtsTrack` (
  `cmt_system_id` int(11) NOT NULL default '0',
  `cmt_id` int(11) NOT NULL default '0',
  `cmt_rate` tinyint(4) NOT NULL default '0',
  `cmt_rate_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_rate_author_nip` int(11) unsigned NOT NULL default '0',
  `cmt_rate_ts` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_system_id`,`cmt_id`,`cmt_rate_author_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CmtsTrack`
--


-- --------------------------------------------------------

-- 
-- Table structure for table `ColorBase`
-- 

CREATE TABLE `ColorBase` (
  `ColorName` varchar(20) NOT NULL default '',
  `ColorCode` varchar(10) NOT NULL default '',
  UNIQUE KEY `ColorName` (`ColorName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `ColorBase`
-- 

INSERT INTO `ColorBase` VALUES ('AliceBlue', '#F0F8FF');
INSERT INTO `ColorBase` VALUES ('AntiqueWhite', '#FAEBD7');
INSERT INTO `ColorBase` VALUES ('Aqua', '#00FFFF');
INSERT INTO `ColorBase` VALUES ('Aquamarine', '#7FFFD4');
INSERT INTO `ColorBase` VALUES ('Azure', '#F0FFFF');
INSERT INTO `ColorBase` VALUES ('Beige', '#F5F5DC');
INSERT INTO `ColorBase` VALUES ('Bisque', '#FFE4C4');
INSERT INTO `ColorBase` VALUES ('Black', '#000000');
INSERT INTO `ColorBase` VALUES ('BlanchedAlmond', '#FFEBCD');
INSERT INTO `ColorBase` VALUES ('Blue', '#0000FF');
INSERT INTO `ColorBase` VALUES ('BlueViolet', '#8A2BE2');
INSERT INTO `ColorBase` VALUES ('Brown', '#A52A2A');
INSERT INTO `ColorBase` VALUES ('BurlyWood', '#DEB887');
INSERT INTO `ColorBase` VALUES ('CadetBlue', '#5F9EA0');
INSERT INTO `ColorBase` VALUES ('Chartreuse', '#7FFF00');
INSERT INTO `ColorBase` VALUES ('Chocolate', '#D2691E');
INSERT INTO `ColorBase` VALUES ('Coral', '#FF7F50');
INSERT INTO `ColorBase` VALUES ('CornflowerBlue', '#6495ED');
INSERT INTO `ColorBase` VALUES ('Cornsilk', '#FFF8DC');
INSERT INTO `ColorBase` VALUES ('Crimson', '#DC143C');
INSERT INTO `ColorBase` VALUES ('Cyan', '#00FFFF');
INSERT INTO `ColorBase` VALUES ('DarkBlue', '#00008B');
INSERT INTO `ColorBase` VALUES ('DarkCyan', '#008B8B');
INSERT INTO `ColorBase` VALUES ('DarkGoldenRod', '#B8860B');
INSERT INTO `ColorBase` VALUES ('DarkGray', '#A9A9A9');
INSERT INTO `ColorBase` VALUES ('DarkGreen', '#006400');
INSERT INTO `ColorBase` VALUES ('DarkKhaki', '#BDB76B');
INSERT INTO `ColorBase` VALUES ('DarkMagenta', '#8B008B');
INSERT INTO `ColorBase` VALUES ('DarkOliveGreen', '#556B2F');
INSERT INTO `ColorBase` VALUES ('Darkorange', '#FF8C00');
INSERT INTO `ColorBase` VALUES ('DarkOrchid', '#9932CC');
INSERT INTO `ColorBase` VALUES ('DarkRed', '#8B0000');
INSERT INTO `ColorBase` VALUES ('DarkSalmon', '#E9967A');
INSERT INTO `ColorBase` VALUES ('DarkSeaGreen', '#8FBC8F');
INSERT INTO `ColorBase` VALUES ('DarkSlateBlue', '#483D8B');
INSERT INTO `ColorBase` VALUES ('DarkSlateGray', '#2F4F4F');
INSERT INTO `ColorBase` VALUES ('DarkTurquoise', '#00CED1');
INSERT INTO `ColorBase` VALUES ('DarkViolet', '#9400D3');
INSERT INTO `ColorBase` VALUES ('DeepPink', '#FF1493');
INSERT INTO `ColorBase` VALUES ('DeepSkyBlue', '#00BFFF');
INSERT INTO `ColorBase` VALUES ('DimGray', '#696969');
INSERT INTO `ColorBase` VALUES ('DodgerBlue', '#1E90FF');
INSERT INTO `ColorBase` VALUES ('Feldspar', '#D19275');
INSERT INTO `ColorBase` VALUES ('FireBrick', '#B22222');
INSERT INTO `ColorBase` VALUES ('FloralWhite', '#FFFAF0');
INSERT INTO `ColorBase` VALUES ('ForestGreen', '#228B22');
INSERT INTO `ColorBase` VALUES ('Fuchsia', '#FF00FF');
INSERT INTO `ColorBase` VALUES ('Gainsboro', '#DCDCDC');
INSERT INTO `ColorBase` VALUES ('GhostWhite', '#F8F8FF');
INSERT INTO `ColorBase` VALUES ('Gold', '#FFD700');
INSERT INTO `ColorBase` VALUES ('GoldenRod', '#DAA520');
INSERT INTO `ColorBase` VALUES ('Gray', '#808080');
INSERT INTO `ColorBase` VALUES ('Green', '#008000');
INSERT INTO `ColorBase` VALUES ('GreenYellow', '#ADFF2F');
INSERT INTO `ColorBase` VALUES ('HoneyDew', '#F0FFF0');
INSERT INTO `ColorBase` VALUES ('HotPink', '#FF69B4');
INSERT INTO `ColorBase` VALUES ('IndianRed', '#CD5C5C');
INSERT INTO `ColorBase` VALUES ('Indigo', '#4B0082');
INSERT INTO `ColorBase` VALUES ('Ivory', '#FFFFF0');
INSERT INTO `ColorBase` VALUES ('Khaki', '#F0E68C');
INSERT INTO `ColorBase` VALUES ('Lavender', '#E6E6FA');
INSERT INTO `ColorBase` VALUES ('LavenderBlush', '#FFF0F5');
INSERT INTO `ColorBase` VALUES ('LawnGreen', '#7CFC00');
INSERT INTO `ColorBase` VALUES ('LemonChiffon', '#FFFACD');
INSERT INTO `ColorBase` VALUES ('LightBlue', '#ADD8E6');
INSERT INTO `ColorBase` VALUES ('LightCoral', '#F08080');
INSERT INTO `ColorBase` VALUES ('LightCyan', '#E0FFFF');
INSERT INTO `ColorBase` VALUES ('LightGoldenRodYellow', '#FAFAD2');
INSERT INTO `ColorBase` VALUES ('LightGrey', '#D3D3D3');
INSERT INTO `ColorBase` VALUES ('LightGreen', '#90EE90');
INSERT INTO `ColorBase` VALUES ('LightPink', '#FFB6C1');
INSERT INTO `ColorBase` VALUES ('LightSalmon', '#FFA07A');
INSERT INTO `ColorBase` VALUES ('LightSeaGreen', '#20B2AA');
INSERT INTO `ColorBase` VALUES ('LightSkyBlue', '#87CEFA');
INSERT INTO `ColorBase` VALUES ('LightSlateBlue', '#8470FF');
INSERT INTO `ColorBase` VALUES ('LightSlateGray', '#778899');
INSERT INTO `ColorBase` VALUES ('LightSteelBlue', '#B0C4DE');
INSERT INTO `ColorBase` VALUES ('LightYellow', '#FFFFE0');
INSERT INTO `ColorBase` VALUES ('Lime', '#00FF00');
INSERT INTO `ColorBase` VALUES ('LimeGreen', '#32CD32');
INSERT INTO `ColorBase` VALUES ('Linen', '#FAF0E6');
INSERT INTO `ColorBase` VALUES ('Magenta', '#FF00FF');
INSERT INTO `ColorBase` VALUES ('Maroon', '#800000');
INSERT INTO `ColorBase` VALUES ('MediumAquaMarine', '#66CDAA');
INSERT INTO `ColorBase` VALUES ('MediumBlue', '#0000CD');
INSERT INTO `ColorBase` VALUES ('MediumOrchid', '#BA55D3');
INSERT INTO `ColorBase` VALUES ('MediumPurple', '#9370D8');
INSERT INTO `ColorBase` VALUES ('MediumSeaGreen', '#3CB371');
INSERT INTO `ColorBase` VALUES ('MediumSlateBlue', '#7B68EE');
INSERT INTO `ColorBase` VALUES ('MediumSpringGreen', '#00FA9A');
INSERT INTO `ColorBase` VALUES ('MediumTurquoise', '#48D1CC');
INSERT INTO `ColorBase` VALUES ('MediumVioletRed', '#C71585');
INSERT INTO `ColorBase` VALUES ('MidnightBlue', '#191970');
INSERT INTO `ColorBase` VALUES ('MintCream', '#F5FFFA');
INSERT INTO `ColorBase` VALUES ('MistyRose', '#FFE4E1');
INSERT INTO `ColorBase` VALUES ('Moccasin', '#FFE4B5');
INSERT INTO `ColorBase` VALUES ('NavajoWhite', '#FFDEAD');
INSERT INTO `ColorBase` VALUES ('Navy', '#000080');
INSERT INTO `ColorBase` VALUES ('OldLace', '#FDF5E6');
INSERT INTO `ColorBase` VALUES ('Olive', '#808000');
INSERT INTO `ColorBase` VALUES ('OliveDrab', '#6B8E23');
INSERT INTO `ColorBase` VALUES ('Orange', '#FFA500');
INSERT INTO `ColorBase` VALUES ('OrangeRed', '#FF4500');
INSERT INTO `ColorBase` VALUES ('Orchid', '#DA70D6');
INSERT INTO `ColorBase` VALUES ('PaleGoldenRod', '#EEE8AA');
INSERT INTO `ColorBase` VALUES ('PaleGreen', '#98FB98');
INSERT INTO `ColorBase` VALUES ('PaleTurquoise', '#AFEEEE');
INSERT INTO `ColorBase` VALUES ('PaleVioletRed', '#D87093');
INSERT INTO `ColorBase` VALUES ('PapayaWhip', '#FFEFD5');
INSERT INTO `ColorBase` VALUES ('PeachPuff', '#FFDAB9');
INSERT INTO `ColorBase` VALUES ('Peru', '#CD853F');
INSERT INTO `ColorBase` VALUES ('Pink', '#FFC0CB');
INSERT INTO `ColorBase` VALUES ('Plum', '#DDA0DD');
INSERT INTO `ColorBase` VALUES ('PowderBlue', '#B0E0E6');
INSERT INTO `ColorBase` VALUES ('Purple', '#800080');
INSERT INTO `ColorBase` VALUES ('Red', '#FF0000');
INSERT INTO `ColorBase` VALUES ('RosyBrown', '#BC8F8F');
INSERT INTO `ColorBase` VALUES ('RoyalBlue', '#4169E1');
INSERT INTO `ColorBase` VALUES ('SaddleBrown', '#8B4513');
INSERT INTO `ColorBase` VALUES ('Salmon', '#FA8072');
INSERT INTO `ColorBase` VALUES ('SandyBrown', '#F4A460');
INSERT INTO `ColorBase` VALUES ('SeaGreen', '#2E8B57');
INSERT INTO `ColorBase` VALUES ('SeaShell', '#FFF5EE');
INSERT INTO `ColorBase` VALUES ('Sienna', '#A0522D');
INSERT INTO `ColorBase` VALUES ('Silver', '#C0C0C0');
INSERT INTO `ColorBase` VALUES ('SkyBlue', '#87CEEB');
INSERT INTO `ColorBase` VALUES ('SlateBlue', '#6A5ACD');
INSERT INTO `ColorBase` VALUES ('SlateGray', '#708090');
INSERT INTO `ColorBase` VALUES ('Snow', '#FFFAFA');
INSERT INTO `ColorBase` VALUES ('SpringGreen', '#00FF7F');
INSERT INTO `ColorBase` VALUES ('SteelBlue', '#4682B4');
INSERT INTO `ColorBase` VALUES ('Tan', '#D2B48C');
INSERT INTO `ColorBase` VALUES ('Teal', '#008080');
INSERT INTO `ColorBase` VALUES ('Thistle', '#D8BFD8');
INSERT INTO `ColorBase` VALUES ('Tomato', '#FF6347');
INSERT INTO `ColorBase` VALUES ('Turquoise', '#40E0D0');
INSERT INTO `ColorBase` VALUES ('Violet', '#EE82EE');
INSERT INTO `ColorBase` VALUES ('VioletRed', '#D02090');
INSERT INTO `ColorBase` VALUES ('Wheat', '#F5DEB3');
INSERT INTO `ColorBase` VALUES ('White', '#FFFFFF');
INSERT INTO `ColorBase` VALUES ('WhiteSmoke', '#F5F5F5');
INSERT INTO `ColorBase` VALUES ('Yellow', '#FFFF00');
INSERT INTO `ColorBase` VALUES ('YellowGreen', '#9ACD32');

-- --------------------------------------------------------

-- 
-- Table structure for table `Countries`
-- 

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

-- 
-- Dumping data for table `Countries`
-- 

INSERT INTO `Countries` VALUES ('AD', 'AND', 20, 'Andorra', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('AE', 'ARE', 784, 'United Arab Emirates', 'Middle East', 'UAE Dirham', 'AED');
INSERT INTO `Countries` VALUES ('AF', 'AFG', 4, 'Afghanistan', 'Asia', 'Afghani', 'AFA');
INSERT INTO `Countries` VALUES ('AG', 'ATG', 28, 'Antigua and Barbuda', 'Central America and the Caribbean', 'East Caribbean Dollar', 'XCD');
INSERT INTO `Countries` VALUES ('AI', 'AIA', 660, 'Anguilla', 'Central America and the Caribbean', 'East Caribbean Dollar', 'XCD');
INSERT INTO `Countries` VALUES ('AL', 'ALB', 8, 'Albania', 'Europe', 'Lek', 'ALL');
INSERT INTO `Countries` VALUES ('AM', 'ARM', 51, 'Armenia', 'Commonwealth of Independent States', 'Armenian Dram', 'AMD');
INSERT INTO `Countries` VALUES ('AN', 'ANT', 530, 'Netherlands Antilles', 'Central America and the Caribbean', 'Netherlands Antillean guilder', 'ANG');
INSERT INTO `Countries` VALUES ('AO', 'AGO', 24, 'Angola', 'Africa', 'Kwanza', 'AOA');
INSERT INTO `Countries` VALUES ('AQ', 'ATA', 10, 'Antarctica', 'Antarctic Region', NULL, NULL);
INSERT INTO `Countries` VALUES ('AR', 'ARG', 32, 'Argentina', 'South America', 'Argentine Peso', 'ARS');
INSERT INTO `Countries` VALUES ('AS', 'ASM', 16, 'American Samoa', 'Oceania', 'US Dollar', 'USD');
INSERT INTO `Countries` VALUES ('AT', 'AUT', 40, 'Austria', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('AU', 'AUS', 36, 'Australia', 'Oceania', 'Australian dollar', 'AUD');
INSERT INTO `Countries` VALUES ('AW', 'ABW', 533, 'Aruba', 'Central America and the Caribbean', 'Aruban Guilder', 'AWG');
INSERT INTO `Countries` VALUES ('AZ', 'AZE', 31, 'Azerbaijan', 'Commonwealth of Independent States', 'Azerbaijani Manat', 'AZM');
INSERT INTO `Countries` VALUES ('BA', 'BIH', 70, 'Bosnia and Herzegovina', 'Bosnia and Herzegovina, Europe', 'Convertible Marka', 'BAM');
INSERT INTO `Countries` VALUES ('BB', 'BRB', 52, 'Barbados', 'Central America and the Caribbean', 'Barbados Dollar', 'BBD');
INSERT INTO `Countries` VALUES ('BD', 'BGD', 50, 'Bangladesh', 'Asia', 'Taka', 'BDT');
INSERT INTO `Countries` VALUES ('BE', 'BEL', 56, 'Belgium', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('BF', 'BFA', 854, 'Burkina Faso', 'Africa', 'CFA Franc BCEAO', 'XOF');
INSERT INTO `Countries` VALUES ('BG', 'BGR', 100, 'Bulgaria', 'Europe', 'Lev', 'BGL');
INSERT INTO `Countries` VALUES ('BH', 'BHR', 48, 'Bahrain', 'Middle East', 'Bahraini Dinar', 'BHD');
INSERT INTO `Countries` VALUES ('BI', 'BDI', 108, 'Burundi', 'Africa', 'Burundi Franc', 'BIF');
INSERT INTO `Countries` VALUES ('BJ', 'BEN', 204, 'Benin', 'Africa', 'CFA Franc BCEAO', 'XOF');
INSERT INTO `Countries` VALUES ('BM', 'BMU', 60, 'Bermuda', 'North America', 'Bermudian Dollar', 'BMD');
INSERT INTO `Countries` VALUES ('BN', 'BRN', 96, 'Brunei Darussalam', 'Southeast Asia', 'Brunei Dollar', 'BND');
INSERT INTO `Countries` VALUES ('BO', 'BOL', 68, 'Bolivia', 'South America', 'Boliviano', 'BOB');
INSERT INTO `Countries` VALUES ('BR', 'BRA', 76, 'Brazil', 'South America', 'Brazilian Real', 'BRL');
INSERT INTO `Countries` VALUES ('BS', 'BHS', 44, 'The Bahamas', 'Central America and the Caribbean', 'Bahamian Dollar', 'BSD');
INSERT INTO `Countries` VALUES ('BT', 'BTN', 64, 'Bhutan', 'Asia', 'Ngultrum', 'BTN');
INSERT INTO `Countries` VALUES ('BV', 'BVT', 74, 'Bouvet Island', 'Antarctic Region', 'Norwegian Krone', 'NOK');
INSERT INTO `Countries` VALUES ('BW', 'BWA', 72, 'Botswana', 'Africa', 'Pula', 'BWP');
INSERT INTO `Countries` VALUES ('BY', 'BLR', 112, 'Belarus', 'Commonwealth of Independent States', 'Belarussian Ruble', 'BYR');
INSERT INTO `Countries` VALUES ('BZ', 'BLZ', 84, 'Belize', 'Central America and the Caribbean', 'Belize Dollar', 'BZD');
INSERT INTO `Countries` VALUES ('CA', 'CAN', 124, 'Canada', 'North America', 'Canadian Dollar', 'CAD');
INSERT INTO `Countries` VALUES ('CC', 'CCK', 166, 'Cocos (Keeling) Islands', 'Southeast Asia', 'Australian Dollar', 'AUD');
INSERT INTO `Countries` VALUES ('CD', 'COD', 180, 'Congo, Democratic Republic of the', 'Africa', 'Franc Congolais', 'CDF');
INSERT INTO `Countries` VALUES ('CF', 'CAF', 140, 'Central African Republic', 'Africa', 'CFA Franc BEAC', 'XAF');
INSERT INTO `Countries` VALUES ('CG', 'COG', 178, 'Congo, Republic of the', 'Africa', 'CFA Franc BEAC', 'XAF');
INSERT INTO `Countries` VALUES ('CH', 'CHE', 756, 'Switzerland', 'Europe', 'Swiss Franc', 'CHF');
INSERT INTO `Countries` VALUES ('CI', 'CIV', 384, 'Cote d''Ivoire', 'Africa', 'CFA Franc BCEAO', 'XOF');
INSERT INTO `Countries` VALUES ('CK', 'COK', 184, 'Cook Islands', 'Oceania', 'New Zealand Dollar', 'NZD');
INSERT INTO `Countries` VALUES ('CL', 'CHL', 152, 'Chile', 'South America', 'Chilean Peso', 'CLP');
INSERT INTO `Countries` VALUES ('CM', 'CMR', 120, 'Cameroon', 'Africa', 'CFA Franc BEAC', 'XAF');
INSERT INTO `Countries` VALUES ('CN', 'CHN', 156, 'China', 'Asia', 'Yuan Renminbi', 'CNY');
INSERT INTO `Countries` VALUES ('CO', 'COL', 170, 'Colombia', 'South America, Central America and the Caribbean', 'Colombian Peso', 'COP');
INSERT INTO `Countries` VALUES ('CR', 'CRI', 188, 'Costa Rica', 'Central America and the Caribbean', 'Costa Rican Colon', 'CRC');
INSERT INTO `Countries` VALUES ('CU', 'CUB', 192, 'Cuba', 'Central America and the Caribbean', 'Cuban Peso', 'CUP');
INSERT INTO `Countries` VALUES ('CV', 'CPV', 132, 'Cape Verde', 'World', 'Cape Verdean Escudo', 'CVE');
INSERT INTO `Countries` VALUES ('CX', 'CXR', 162, 'Christmas Island', 'Southeast Asia', 'Australian Dollar', 'AUD');
INSERT INTO `Countries` VALUES ('CY', 'CYP', 196, 'Cyprus', 'Middle East', 'Cyprus Pound', 'CYP');
INSERT INTO `Countries` VALUES ('CZ', 'CZE', 203, 'Czech Republic', 'Europe', 'Czech Koruna', 'CZK');
INSERT INTO `Countries` VALUES ('DE', 'DEU', 276, 'Germany', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('DJ', 'DJI', 262, 'Djibouti', 'Africa', 'Djibouti Franc', 'DJF');
INSERT INTO `Countries` VALUES ('DK', 'DNK', 208, 'Denmark', 'Europe', 'Danish Krone', 'DKK');
INSERT INTO `Countries` VALUES ('DM', 'DMA', 212, 'Dominica', 'Central America and the Caribbean', 'East Caribbean Dollar', 'XCD');
INSERT INTO `Countries` VALUES ('DO', 'DOM', 214, 'Dominican Republic', 'Central America and the Caribbean', 'Dominican Peso', 'DOP');
INSERT INTO `Countries` VALUES ('DZ', 'DZA', 12, 'Algeria', 'Africa', 'Algerian Dinar', 'DZD');
INSERT INTO `Countries` VALUES ('EC', 'ECU', 218, 'Ecuador', 'South America', 'US dollar', 'USD');
INSERT INTO `Countries` VALUES ('EE', 'EST', 233, 'Estonia', 'Europe', 'Kroon', 'EEK');
INSERT INTO `Countries` VALUES ('EG', 'EGY', 818, 'Egypt', 'Africa', 'Egyptian Pound', 'EGP');
INSERT INTO `Countries` VALUES ('EH', 'ESH', 732, 'Western Sahara', 'Africa', 'Moroccan Dirham', 'MAD');
INSERT INTO `Countries` VALUES ('ER', 'ERI', 232, 'Eritrea', 'Africa', 'Nakfa', 'ERN');
INSERT INTO `Countries` VALUES ('ES', 'ESP', 724, 'Spain', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('ET', 'ETH', 231, 'Ethiopia', 'Africa', 'Ethiopian Birr', 'ETB');
INSERT INTO `Countries` VALUES ('FI', 'FIN', 246, 'Finland', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('FJ', 'FJI', 242, 'Fiji', 'Oceania', 'Fijian Dollar', 'FJD');
INSERT INTO `Countries` VALUES ('FK', 'FLK', 238, 'Falkland Islands (Islas Malvinas)', 'South America', 'Falkland Islands Pound', 'FKP');
INSERT INTO `Countries` VALUES ('FM', 'FSM', 583, 'Micronesia, Federated States of', 'Oceania', 'US dollar', 'USD');
INSERT INTO `Countries` VALUES ('FO', 'FRO', 234, 'Faroe Islands', 'Europe', 'Danish Krone', 'DKK');
INSERT INTO `Countries` VALUES ('FR', 'FRA', 250, 'France', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('GA', 'GAB', 266, 'Gabon', 'Africa', 'CFA Franc BEAC', 'XAF');
INSERT INTO `Countries` VALUES ('GB', 'GBR', 826, 'United Kingdom', 'Europe', 'Pound Sterling', 'GBP');
INSERT INTO `Countries` VALUES ('GD', 'GRD', 308, 'Grenada', 'Central America and the Caribbean', 'East Caribbean Dollar', 'XCD');
INSERT INTO `Countries` VALUES ('GE', 'GEO', 268, 'Georgia', 'Commonwealth of Independent States', 'Lari', 'GEL');
INSERT INTO `Countries` VALUES ('GF', 'GUF', 254, 'French Guiana', 'South America', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('GH', 'GHA', 288, 'Ghana', 'Africa', 'Cedi', 'GHC');
INSERT INTO `Countries` VALUES ('GI', 'GIB', 292, 'Gibraltar', 'Europe', 'Gibraltar Pound', 'GIP');
INSERT INTO `Countries` VALUES ('GL', 'GRL', 304, 'Greenland', 'Arctic Region', 'Danish Krone', 'DKK');
INSERT INTO `Countries` VALUES ('GM', 'GMB', 270, 'The Gambia', 'Africa', 'Dalasi', 'GMD');
INSERT INTO `Countries` VALUES ('GN', 'GIN', 324, 'Guinea', 'Africa', 'Guinean Franc', 'GNF');
INSERT INTO `Countries` VALUES ('GP', 'GLP', 312, 'Guadeloupe', 'Central America and the Caribbean', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('GQ', 'GNQ', 226, 'Equatorial Guinea', 'Africa', 'CFA Franc BEAC', 'XAF');
INSERT INTO `Countries` VALUES ('GR', 'GRC', 300, 'Greece', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('GS', 'SGS', 239, 'South Georgia and the South Sandwich Islands', 'Antarctic Region', 'Pound Sterling', 'GBP');
INSERT INTO `Countries` VALUES ('GT', 'GTM', 320, 'Guatemala', 'Central America and the Caribbean', 'Quetzal', 'GTQ');
INSERT INTO `Countries` VALUES ('GU', 'GUM', 316, 'Guam', 'Oceania', 'US Dollar', 'USD');
INSERT INTO `Countries` VALUES ('GW', 'GNB', 624, 'Guinea-Bissau', 'Africa', 'CFA Franc BCEAO', 'XOF');
INSERT INTO `Countries` VALUES ('GY', 'GUY', 328, 'Guyana', 'South America', 'Guyana Dollar', 'GYD');
INSERT INTO `Countries` VALUES ('HK', 'HKG', 344, 'Hong Kong (SAR)', 'Southeast Asia', 'Hong Kong Dollar', 'HKD');
INSERT INTO `Countries` VALUES ('HM', 'HMD', 334, 'Heard Island and McDonald Islands', 'Antarctic Region', 'Australian Dollar', 'AUD');
INSERT INTO `Countries` VALUES ('HN', 'HND', 340, 'Honduras', 'Central America and the Caribbean', 'Lempira', 'HNL');
INSERT INTO `Countries` VALUES ('HR', 'HRV', 191, 'Croatia', 'Europe', 'Kuna', 'HRK');
INSERT INTO `Countries` VALUES ('HT', 'HTI', 332, 'Haiti', 'Central America and the Caribbean', 'Gourde', 'HTG');
INSERT INTO `Countries` VALUES ('HU', 'HUN', 348, 'Hungary', 'Europe', 'Forint', 'HUF');
INSERT INTO `Countries` VALUES ('ID', 'IDN', 360, 'Indonesia', 'Southeast Asia', 'Rupiah', 'IDR');
INSERT INTO `Countries` VALUES ('IE', 'IRL', 372, 'Ireland', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('IL', 'ISR', 376, 'Israel', 'Middle East', 'New Israeli Sheqel', 'ILS');
INSERT INTO `Countries` VALUES ('IN', 'IND', 356, 'India', 'Asia', 'Indian Rupee', 'INR');
INSERT INTO `Countries` VALUES ('IO', 'IOT', 86, 'British Indian Ocean Territory', 'World', 'US Dollar', 'USD');
INSERT INTO `Countries` VALUES ('IQ', 'IRQ', 368, 'Iraq', 'Middle East', 'Iraqi Dinar', 'IQD');
INSERT INTO `Countries` VALUES ('IR', 'IRN', 364, 'Iran', 'Middle East', 'Iranian Rial', 'IRR');
INSERT INTO `Countries` VALUES ('IS', 'ISL', 352, 'Iceland', 'Arctic Region', 'Iceland Krona', 'ISK');
INSERT INTO `Countries` VALUES ('IT', 'ITA', 380, 'Italy', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('JM', 'JAM', 388, 'Jamaica', 'Central America and the Caribbean', 'Jamaican dollar', 'JMD');
INSERT INTO `Countries` VALUES ('JO', 'JOR', 400, 'Jordan', 'Middle East', 'Jordanian Dinar', 'JOD');
INSERT INTO `Countries` VALUES ('JP', 'JPN', 392, 'Japan', 'Asia', 'Yen', 'JPY');
INSERT INTO `Countries` VALUES ('KE', 'KEN', 404, 'Kenya', 'Africa', 'Kenyan shilling', 'KES');
INSERT INTO `Countries` VALUES ('KG', 'KGZ', 417, 'Kyrgyzstan', 'Commonwealth of Independent States', 'Som', 'KGS');
INSERT INTO `Countries` VALUES ('KH', 'KHM', 116, 'Cambodia', 'Southeast Asia', 'Riel', 'KHR');
INSERT INTO `Countries` VALUES ('KI', 'KIR', 296, 'Kiribati', 'Oceania', 'Australian dollar', 'AUD');
INSERT INTO `Countries` VALUES ('KM', 'COM', 174, 'Comoros', 'Africa', 'Comoro Franc', 'KMF');
INSERT INTO `Countries` VALUES ('KN', 'KNA', 659, 'Saint Kitts and Nevis', 'Central America and the Caribbean', 'East Caribbean Dollar', 'XCD');
INSERT INTO `Countries` VALUES ('KP', 'PRK', 408, 'Korea, North', 'Asia', 'North Korean Won', 'KPW');
INSERT INTO `Countries` VALUES ('KR', 'KOR', 410, 'Korea, South', 'Asia', 'Won', 'KRW');
INSERT INTO `Countries` VALUES ('KW', 'KWT', 414, 'Kuwait', 'Middle East', 'Kuwaiti Dinar', 'KWD');
INSERT INTO `Countries` VALUES ('KY', 'CYM', 136, 'Cayman Islands', 'Central America and the Caribbean', 'Cayman Islands Dollar', 'KYD');
INSERT INTO `Countries` VALUES ('KZ', 'KAZ', 398, 'Kazakhstan', 'Commonwealth of Independent States', 'Tenge', 'KZT');
INSERT INTO `Countries` VALUES ('LA', 'LAO', 418, 'Laos', 'Southeast Asia', 'Kip', 'LAK');
INSERT INTO `Countries` VALUES ('LB', 'LBN', 422, 'Lebanon', 'Middle East', 'Lebanese Pound', 'LBP');
INSERT INTO `Countries` VALUES ('LC', 'LCA', 662, 'Saint Lucia', 'Central America and the Caribbean', 'East Caribbean Dollar', 'XCD');
INSERT INTO `Countries` VALUES ('LI', 'LIE', 438, 'Liechtenstein', 'Europe', 'Swiss Franc', 'CHF');
INSERT INTO `Countries` VALUES ('LK', 'LKA', 144, 'Sri Lanka', 'Asia', 'Sri Lanka Rupee', 'LKR');
INSERT INTO `Countries` VALUES ('LR', 'LBR', 430, 'Liberia', 'Africa', 'Liberian Dollar', 'LRD');
INSERT INTO `Countries` VALUES ('LS', 'LSO', 426, 'Lesotho', 'Africa', 'Loti', 'LSL');
INSERT INTO `Countries` VALUES ('LT', 'LTU', 440, 'Lithuania', 'Europe', 'Lithuanian Litas', 'LTL');
INSERT INTO `Countries` VALUES ('LU', 'LUX', 442, 'Luxembourg', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('LV', 'LVA', 428, 'Latvia', 'Europe', 'Latvian Lats', 'LVL');
INSERT INTO `Countries` VALUES ('LY', 'LBY', 434, 'Libya', 'Africa', 'Libyan Dinar', 'LYD');
INSERT INTO `Countries` VALUES ('MA', 'MAR', 504, 'Morocco', 'Africa', 'Moroccan Dirham', 'MAD');
INSERT INTO `Countries` VALUES ('MC', 'MCO', 492, 'Monaco', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('MD', 'MDA', 498, 'Moldova', 'Commonwealth of Independent States', 'Moldovan Leu', 'MDL');
INSERT INTO `Countries` VALUES ('MG', 'MDG', 450, 'Madagascar', 'Africa', 'Malagasy Franc', 'MGF');
INSERT INTO `Countries` VALUES ('MH', 'MHL', 584, 'Marshall Islands', 'Oceania', 'US dollar', 'USD');
INSERT INTO `Countries` VALUES ('MK', 'MKD', 807, 'Macedonia, The Former Yugoslav Republic of', 'Europe', 'Denar', 'MKD');
INSERT INTO `Countries` VALUES ('ML', 'MLI', 466, 'Mali', 'Africa', 'CFA Franc BCEAO', 'XOF');
INSERT INTO `Countries` VALUES ('MM', 'MMR', 104, 'Burma', 'Southeast Asia', 'kyat', 'MMK');
INSERT INTO `Countries` VALUES ('MN', 'MNG', 496, 'Mongolia', 'Asia', 'Tugrik', 'MNT');
INSERT INTO `Countries` VALUES ('MO', 'MAC', 446, 'Macao', 'Southeast Asia', 'Pataca', 'MOP');
INSERT INTO `Countries` VALUES ('MP', 'MNP', 580, 'Northern Mariana Islands', 'Oceania', 'US Dollar', 'USD');
INSERT INTO `Countries` VALUES ('MQ', 'MTQ', 474, 'Martinique', 'Central America and the Caribbean', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('MR', 'MRT', 478, 'Mauritania', 'Africa', 'Ouguiya', 'MRO');
INSERT INTO `Countries` VALUES ('MS', 'MSR', 500, 'Montserrat', 'Central America and the Caribbean', 'East Caribbean Dollar', 'XCD');
INSERT INTO `Countries` VALUES ('MT', 'MLT', 470, 'Malta', 'Europe', 'Maltese Lira', 'MTL');
INSERT INTO `Countries` VALUES ('MU', 'MUS', 480, 'Mauritius', 'World', 'Mauritius Rupee', 'MUR');
INSERT INTO `Countries` VALUES ('MV', 'MDV', 462, 'Maldives', 'Asia', 'Rufiyaa', 'MVR');
INSERT INTO `Countries` VALUES ('MW', 'MWI', 454, 'Malawi', 'Africa', 'Kwacha', 'MWK');
INSERT INTO `Countries` VALUES ('MX', 'MEX', 484, 'Mexico', 'North America', 'Mexican Peso', 'MXN');
INSERT INTO `Countries` VALUES ('MY', 'MYS', 458, 'Malaysia', 'Southeast Asia', 'Malaysian Ringgit', 'MYR');
INSERT INTO `Countries` VALUES ('MZ', 'MOZ', 508, 'Mozambique', 'Africa', 'Metical', 'MZM');
INSERT INTO `Countries` VALUES ('NA', 'NAM', 516, 'Namibia', 'Africa', 'Namibian Dollar', 'NAD');
INSERT INTO `Countries` VALUES ('NC', 'NCL', 540, 'New Caledonia', 'Oceania', 'CFP Franc', 'XPF');
INSERT INTO `Countries` VALUES ('NE', 'NER', 562, 'Niger', 'Africa', 'CFA Franc BCEAO', 'XOF');
INSERT INTO `Countries` VALUES ('NF', 'NFK', 574, 'Norfolk Island', 'Oceania', 'Australian Dollar', 'AUD');
INSERT INTO `Countries` VALUES ('NG', 'NGA', 566, 'Nigeria', 'Africa', 'Naira', 'NGN');
INSERT INTO `Countries` VALUES ('NI', 'NIC', 558, 'Nicaragua', 'Central America and the Caribbean', 'Cordoba Oro', 'NIO');
INSERT INTO `Countries` VALUES ('NL', 'NLD', 528, 'Netherlands', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('NO', 'NOR', 578, 'Norway', 'Europe', 'Norwegian Krone', 'NOK');
INSERT INTO `Countries` VALUES ('NP', 'NPL', 524, 'Nepal', 'Asia', 'Nepalese Rupee', 'NPR');
INSERT INTO `Countries` VALUES ('NR', 'NRU', 520, 'Nauru', 'Oceania', 'Australian Dollar', 'AUD');
INSERT INTO `Countries` VALUES ('NU', 'NIU', 570, 'Niue', 'Oceania', 'New Zealand Dollar', 'NZD');
INSERT INTO `Countries` VALUES ('NZ', 'NZL', 554, 'New Zealand', 'Oceania', 'New Zealand Dollar', 'NZD');
INSERT INTO `Countries` VALUES ('OM', 'OMN', 512, 'Oman', 'Middle East', 'Rial Omani', 'OMR');
INSERT INTO `Countries` VALUES ('PA', 'PAN', 591, 'Panama', 'Central America and the Caribbean', 'balboa', 'PAB');
INSERT INTO `Countries` VALUES ('PE', 'PER', 604, 'Peru', 'South America', 'Nuevo Sol', 'PEN');
INSERT INTO `Countries` VALUES ('PF', 'PYF', 258, 'French Polynesia', 'Oceania', 'CFP Franc', 'XPF');
INSERT INTO `Countries` VALUES ('PG', 'PNG', 598, 'Papua New Guinea', 'Oceania', 'Kina', 'PGK');
INSERT INTO `Countries` VALUES ('PH', 'PHL', 608, 'Philippines', 'Southeast Asia', 'Philippine Peso', 'PHP');
INSERT INTO `Countries` VALUES ('PK', 'PAK', 586, 'Pakistan', 'Asia', 'Pakistan Rupee', 'PKR');
INSERT INTO `Countries` VALUES ('PL', 'POL', 616, 'Poland', 'Europe', 'Zloty', 'PLN');
INSERT INTO `Countries` VALUES ('PM', 'SPM', 666, 'Saint Pierre and Miquelon', 'North America', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('PN', 'PCN', 612, 'Pitcairn Islands', 'Oceania', 'New Zealand Dollar', 'NZD');
INSERT INTO `Countries` VALUES ('PR', 'PRI', 630, 'Puerto Rico', 'Central America and the Caribbean', 'US dollar', 'USD');
INSERT INTO `Countries` VALUES ('PS', 'PSE', 275, 'Palestinian Territory, Occupied', NULL, NULL, NULL);
INSERT INTO `Countries` VALUES ('PT', 'PRT', 620, 'Portugal', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('PW', 'PLW', 585, 'Palau', 'Oceania', 'US dollar', 'USD');
INSERT INTO `Countries` VALUES ('PY', 'PRY', 600, 'Paraguay', 'South America', 'Guarani', 'PYG');
INSERT INTO `Countries` VALUES ('QA', 'QAT', 634, 'Qatar', 'Middle East', 'Qatari Rial', 'QAR');
INSERT INTO `Countries` VALUES ('RE', 'REU', 638, 'Reunion', 'World', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('RO', 'ROU', 642, 'Romania', 'Europe', 'Leu', 'ROL');
INSERT INTO `Countries` VALUES ('RU', 'RUS', 643, 'Russia', 'Asia', 'Russian Ruble', 'RUB');
INSERT INTO `Countries` VALUES ('RW', 'RWA', 646, 'Rwanda', 'Africa', 'Rwanda Franc', 'RWF');
INSERT INTO `Countries` VALUES ('SA', 'SAU', 682, 'Saudi Arabia', 'Middle East', 'Saudi Riyal', 'SAR');
INSERT INTO `Countries` VALUES ('SB', 'SLB', 90, 'Solomon Islands', 'Oceania', 'Solomon Islands Dollar', 'SBD');
INSERT INTO `Countries` VALUES ('SC', 'SYC', 690, 'Seychelles', 'Africa', 'Seychelles Rupee', 'SCR');
INSERT INTO `Countries` VALUES ('SD', 'SDN', 736, 'Sudan', 'Africa', 'Sudanese Dinar', 'SDD');
INSERT INTO `Countries` VALUES ('SE', 'SWE', 752, 'Sweden', 'Europe', 'Swedish Krona', 'SEK');
INSERT INTO `Countries` VALUES ('SG', 'SGP', 702, 'Singapore', 'Southeast Asia', 'Singapore Dollar', 'SGD');
INSERT INTO `Countries` VALUES ('SH', 'SHN', 654, 'Saint Helena', 'Africa', 'Saint Helenian Pound', 'SHP');
INSERT INTO `Countries` VALUES ('SI', 'SVN', 705, 'Slovenia', 'Europe', 'Tolar', 'SIT');
INSERT INTO `Countries` VALUES ('SJ', 'SJM', 744, 'Svalbard', 'Arctic Region', 'Norwegian Krone', 'NOK');
INSERT INTO `Countries` VALUES ('SK', 'SVK', 703, 'Slovakia', 'Europe', 'Slovak Koruna', 'SKK');
INSERT INTO `Countries` VALUES ('SL', 'SLE', 694, 'Sierra Leone', 'Africa', 'Leone', 'SLL');
INSERT INTO `Countries` VALUES ('SM', 'SMR', 674, 'San Marino', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('SN', 'SEN', 686, 'Senegal', 'Africa', 'CFA Franc BCEAO', 'XOF');
INSERT INTO `Countries` VALUES ('SO', 'SOM', 706, 'Somalia', 'Africa', 'Somali Shilling', 'SOS');
INSERT INTO `Countries` VALUES ('SR', 'SUR', 740, 'Suriname', 'South America', 'Suriname Guilder', 'SRG');
INSERT INTO `Countries` VALUES ('ST', 'STP', 678, 'Sao Tome and Principe', 'Africa', 'Dobra', 'STD');
INSERT INTO `Countries` VALUES ('SV', 'SLV', 222, 'El Salvador', 'Central America and the Caribbean', 'El Salvador Colon', 'SVC');
INSERT INTO `Countries` VALUES ('SY', 'SYR', 760, 'Syria', 'Middle East', 'Syrian Pound', 'SYP');
INSERT INTO `Countries` VALUES ('SZ', 'SWZ', 748, 'Swaziland', 'Africa', 'Lilangeni', 'SZL');
INSERT INTO `Countries` VALUES ('TC', 'TCA', 796, 'Turks and Caicos Islands', 'Central America and the Caribbean', 'US Dollar', 'USD');
INSERT INTO `Countries` VALUES ('TD', 'TCD', 148, 'Chad', 'Africa', 'CFA Franc BEAC', 'XAF');
INSERT INTO `Countries` VALUES ('TF', 'ATF', 260, 'French Southern and Antarctic Lands', 'Antarctic Region', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('TG', 'TGO', 768, 'Togo', 'Africa', 'CFA Franc BCEAO', 'XOF');
INSERT INTO `Countries` VALUES ('TH', 'THA', 764, 'Thailand', 'Southeast Asia', 'Baht', 'THB');
INSERT INTO `Countries` VALUES ('TJ', 'TJK', 762, 'Tajikistan', 'Commonwealth of Independent States', 'Somoni', 'TJS');
INSERT INTO `Countries` VALUES ('TK', 'TKL', 772, 'Tokelau', 'Oceania', 'New Zealand Dollar', 'NZD');
INSERT INTO `Countries` VALUES ('TL', 'TLS', 626, 'East Timor', NULL, 'Timor Escudo', 'TPE');
INSERT INTO `Countries` VALUES ('TM', 'TKM', 795, 'Turkmenistan', 'Commonwealth of Independent States', 'Manat', 'TMM');
INSERT INTO `Countries` VALUES ('TN', 'TUN', 788, 'Tunisia', 'Africa', 'Tunisian Dinar', 'TND');
INSERT INTO `Countries` VALUES ('TO', 'TON', 776, 'Tonga', 'Oceania', 'Pa''anga', 'TOP');
INSERT INTO `Countries` VALUES ('TR', 'TUR', 792, 'Turkey', 'Middle East', 'Turkish Lira', 'TRL');
INSERT INTO `Countries` VALUES ('TT', 'TTO', 780, 'Trinidad and Tobago', 'Central America and the Caribbean', 'Trinidad and Tobago Dollar', 'TTD');
INSERT INTO `Countries` VALUES ('TV', 'TUV', 798, 'Tuvalu', 'Oceania', 'Australian Dollar', 'AUD');
INSERT INTO `Countries` VALUES ('TW', 'TWN', 158, 'Taiwan', 'Southeast Asia', 'New Taiwan Dollar', 'TWD');
INSERT INTO `Countries` VALUES ('TZ', 'TZA', 834, 'Tanzania', 'Africa', 'Tanzanian Shilling', 'TZS');
INSERT INTO `Countries` VALUES ('UA', 'UKR', 804, 'Ukraine', 'Commonwealth of Independent States', 'Hryvnia', 'UAH');
INSERT INTO `Countries` VALUES ('UG', 'UGA', 800, 'Uganda', 'Africa', 'Uganda Shilling', 'UGX');
INSERT INTO `Countries` VALUES ('UM', 'UMI', 581, 'United States Minor Outlying Islands', NULL, 'US Dollar', 'USD');
INSERT INTO `Countries` VALUES ('US', 'USA', 840, 'United States', 'North America', 'US Dollar', 'USD');
INSERT INTO `Countries` VALUES ('UY', 'URY', 858, 'Uruguay', 'South America', 'Peso Uruguayo', 'UYU');
INSERT INTO `Countries` VALUES ('UZ', 'UZB', 860, 'Uzbekistan', 'Commonwealth of Independent States', 'Uzbekistan Sum', 'UZS');
INSERT INTO `Countries` VALUES ('VA', 'VAT', 336, 'Holy See (Vatican City)', 'Europe', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('VC', 'VCT', 670, 'Saint Vincent and the Grenadines', 'Central America and the Caribbean', 'East Caribbean Dollar', 'XCD');
INSERT INTO `Countries` VALUES ('VE', 'VEN', 862, 'Venezuela', 'South America, Central America and the Caribbean', 'Bolivar', 'VEB');
INSERT INTO `Countries` VALUES ('VG', 'VGB', 92, 'British Virgin Islands', 'Central America and the Caribbean', 'US dollar', 'USD');
INSERT INTO `Countries` VALUES ('VI', 'VIR', 850, 'Virgin Islands', 'Central America and the Caribbean', 'US Dollar', 'USD');
INSERT INTO `Countries` VALUES ('VN', 'VNM', 704, 'Vietnam', 'Southeast Asia', 'Dong', 'VND');
INSERT INTO `Countries` VALUES ('VU', 'VUT', 548, 'Vanuatu', 'Oceania', 'Vatu', 'VUV');
INSERT INTO `Countries` VALUES ('WF', 'WLF', 876, 'Wallis and Futuna', 'Oceania', 'CFP Franc', 'XPF');
INSERT INTO `Countries` VALUES ('WS', 'WSM', 882, 'Samoa', 'Oceania', 'Tala', 'WST');
INSERT INTO `Countries` VALUES ('YE', 'YEM', 887, 'Yemen', 'Middle East', 'Yemeni Rial', 'YER');
INSERT INTO `Countries` VALUES ('YT', 'MYT', 175, 'Mayotte', 'Africa', 'Euro', 'EUR');
INSERT INTO `Countries` VALUES ('YU', 'YUG', 891, 'Yugoslavia', 'Europe', 'Yugoslavian Dinar', 'YUM');
INSERT INTO `Countries` VALUES ('ZA', 'ZAF', 710, 'South Africa', 'Africa', 'Rand', 'ZAR');
INSERT INTO `Countries` VALUES ('ZM', 'ZWB', 894, 'Zambia', 'Africa', 'Kwacha', 'ZMK');
INSERT INTO `Countries` VALUES ('ZW', 'ZWE', 716, 'Zimbabwe', 'Africa', 'Zimbabwe Dollar', 'ZWD');

-- --------------------------------------------------------

-- 
-- Table structure for table `DailyQuotes`
-- 

CREATE TABLE `DailyQuotes` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Text` mediumtext NOT NULL,
  `Author` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `DailyQuotes`
-- 

INSERT INTO `DailyQuotes` VALUES (1, 'Give me a woman who loves beer and I will conquer the world.', 'Kaiser Wilhelm');
INSERT INTO `DailyQuotes` VALUES (2, 'All right, Brain, I don''t like you and you don''t like me - so let''s just do this and I''ll get back to killing you with beer.', 'Homer Simpson');
INSERT INTO `DailyQuotes` VALUES (3, 'If it was so, it might be; and if it were so,it would be; but as it isn'' t, it ain'' t. That''s logic.', 'Lewis Carrol');
INSERT INTO `DailyQuotes` VALUES (4, 'God does not care about our mathematical difficulties. He integrates empirically.', 'Albert Einstein');
INSERT INTO `DailyQuotes` VALUES (5, 'Treat your friend as if he might become an enemy.', 'Publilius Syrus');
INSERT INTO `DailyQuotes` VALUES (13, 'Time to have tea!', 'Me');

-- --------------------------------------------------------

-- 
-- Table structure for table `FriendList`
-- 

CREATE TABLE `FriendList` (
  `ID` bigint(8) NOT NULL default '0',
  `Profile` bigint(8) NOT NULL default '0',
  `Check` tinyint(2) NOT NULL default '0',
  UNIQUE KEY `FriendPair` (`ID`,`Profile`),
  KEY `ID` (`ID`),
  KEY `Profile` (`Profile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `FriendList`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `GalleryAlbums`
-- 

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

-- 
-- Dumping data for table `GalleryAlbums`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `GalleryObjects`
-- 

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

-- 
-- Dumping data for table `GalleryObjects`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `GlParams`
-- 

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

-- 
-- Dumping data for table `GlParams`
-- 

INSERT INTO `GlParams` VALUES ('anon_mode', '', 1, 'Anonymous mode (no contact information)', 'checkbox', '', '', 12);
INSERT INTO `GlParams` VALUES ('autoApproval_ifJoin', 'on', 6, 'Automatic profile activation after joining', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('autoApproval_ifPhoto', 'on', 6, 'Do not change profile status after photo uploading', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('autoApproval_ifProfile', 'on', 6, 'Do not change profile status after editing profile information', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('autoApproval_ifSound', 'on', 6, 'Do not change profile status after sound uploading', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('autoApproval_ifVideo', 'on', 6, 'Do not change profile status after video uploading', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('autoApproval_Photo', 'on', 6, 'Automatic photo activation after uploading', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('blogCaptionMaxLenght', '150', 22, 'Maximum length of Blog Caption', 'digit', '', '', 5);
INSERT INTO `GlParams` VALUES ('blogCategoryCaptionMaxLenght', '150', 22, 'Maximum length of Blog Category caption', 'digit', '', '', 3);
INSERT INTO `GlParams` VALUES ('blogCommentMaxLenght', '250', 22, 'Maximum length of Blog comment', 'digit', '', '', 2);
INSERT INTO `GlParams` VALUES ('blogAutoApproval', 'on', 22, 'Enable AutoApproval of Blogs', 'checkbox', '', '', 7);
INSERT INTO `GlParams` VALUES ('blog_step', '10', 22, 'How many blogs showing on page', 'digit', '', '', 15);
INSERT INTO `GlParams` VALUES ('cmdDay', '10', 0, '', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('compose_index_cols', 'content,menu', 0, '', 'select', '', '', NULL);
INSERT INTO `GlParams` VALUES ('currency_code', 'USD', 0, 'Currency code (for checkout system)', 'combobox', 'return strlen($arg0) > 0;', 'cannot be empty.', NULL);
INSERT INTO `GlParams` VALUES ('currency_sign', '$', 15, 'Currency sign (for display purposes only)', 'digit', 'return strlen($arg0) > 0;', 'cannot be empty.', 9);
INSERT INTO `GlParams` VALUES ('date_format', '%m-%d-%y %H:%i', 15, 'Long Date Format <a href="#" onclick="javascript: window.open(''/admin/help.html'', ''DateFormat'', ''width=500,height=400,scrollbars=yes,menubar=no,resizable=no''); return false;">?</a>', 'digit', '', '', 15);
INSERT INTO `GlParams` VALUES ('db_clean_msg', '180', 11, 'Clean old messages ( days )', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('db_clean_priv_msg', '2', 11, 'Clean old private messages ( days )', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('db_clean_profiles', '180', 11, 'Clean old profiles by last log in ( days )', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('db_clean_views', '180', 11, 'Clean old profile views ( days )', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('db_clean_vkiss', '90', 11, 'Clean old greetings ( days )', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('default_country', 'US', 0, 'Default Country on Index Page', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('default_online_users_num', '50', 3, 'Maximum number of online members shown in the member control panel', 'digit', '', '', 6);
INSERT INTO `GlParams` VALUES ('enable_aff', '', 15, 'Enable affiliate support', 'checkbox', '', '', 1);
INSERT INTO `GlParams` VALUES ('enable_contact_form', 'on', 15, 'Show contact form on contact us page', 'checkbox', '', '', 2);
INSERT INTO `GlParams` VALUES ('enable_cupid', 'on', 12, 'Enable cupid mails', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('enable_customization', 'on', 1, 'Enable profile customization', 'checkbox', '', '', 10);
INSERT INTO `GlParams` VALUES ('enable_event_creating', 'on', 1, 'Allow members to create events', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('enable_gallery', 'on', 2, 'Enable gallery', 'checkbox', '', '', 1);
INSERT INTO `GlParams` VALUES ('enable_gd', 'on', 15, 'Use GD library for image processing', 'checkbox', '', '', 5);
INSERT INTO `GlParams` VALUES ('enable_im', '', 3, 'Enable Instant Messenger', 'checkbox', '', '', 1);
INSERT INTO `GlParams` VALUES ('enable_inbox_notify', '', 17, 'Enable new message notifications', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('enable_match', 'on', 12, 'Enable matchmaking', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('enable_msg_dest_choice', 'on', 17, 'Enable message destination user choice', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('enable_poll', 'on', 20, 'Enable members polls', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('enable_profileComments', 'on', 1, 'Enable Comments for profiles', 'checkbox', '', '', 7);
INSERT INTO `GlParams` VALUES ('enable_promotion_membership', 'on', 7, 'Enable promotional membership', 'checkbox', '', '', 1);
INSERT INTO `GlParams` VALUES ('enable_ray', 'on', 15, 'Enable Ray', 'checkbox', '', '', 2);
INSERT INTO `GlParams` VALUES ('enable_ray_pro', '', 15, 'Enable Ray Pro (must be installed and Ray must be enabled)', 'checkbox', '', '', 7);
INSERT INTO `GlParams` VALUES ('enable_recurring', 'on', 0, 'Enable recurring billings', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('enable_security_image', 'on', 3, 'Enable security image on join page', 'checkbox', '', '', 2);
INSERT INTO `GlParams` VALUES ('enable_shoutBox', 'on', 15, 'Enable ShoutBox', 'checkbox', '', '', 6);
INSERT INTO `GlParams` VALUES ('enable_template', '', 15, 'Enable Users to Change Templates', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('enable_watermark', 'on', 16, 'Enable Watermark', 'checkbox', '', '', 1);
INSERT INTO `GlParams` VALUES ('enable_zip_loc', 'on', 15, 'Enable search by ZIP codes', 'checkbox', '', '', 0);
INSERT INTO `GlParams` VALUES ('expire_notification_days', '1', 5, 'Number of days before membership expiration to notify members ( -1 = after expiration )', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('expire_notify_once', 'on', 5, 'Notify members about membership expiration only once (every day otherwise)', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('featured_mode', 'horizontal', 0, 'Featured members layout direction', 'combobox', 'return $arg0 == ''vertical'' || $arg0 == ''horizontal'' ? true : false;', 'posible values : horizontal, vertical', NULL);
INSERT INTO `GlParams` VALUES ('featured_num', '6', 0, 'Number of featured members displayed on front page', 'digit', 'return $arg0 >= 0;', 'must be equal to or greater than zero.', NULL);
INSERT INTO `GlParams` VALUES ('free_mode', 'on', 0, 'Site is running in free mode', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('friendlist', 'on', 15, 'Show Friend List', 'checkbox', '', '', 3);
INSERT INTO `GlParams` VALUES ('gallery_alboms', '20', 2, 'How many albums allowed for one member in one category', 'digit', '', '', 3);
INSERT INTO `GlParams` VALUES ('gallery_audio_size', '16777216', 2, 'Maximum size for audio file in gallery (in byte)', 'digit', '', '', 8);
INSERT INTO `GlParams` VALUES ('gallery_objects', '100', 2, 'How many objects allowed for one member in one album', 'digit', '', '', 4);
INSERT INTO `GlParams` VALUES ('gallery_objects_step', '9', 2, 'How many objects showing on page', 'digit', '', '', 4);
INSERT INTO `GlParams` VALUES ('gallery_photo_height', '250', 2, 'Height of gallery photo in pixels', 'digit', '', '', 6);
INSERT INTO `GlParams` VALUES ('gallery_photo_size', '83886080', 2, 'Maximum size for photo file in gallery (in byte)', 'digit', '', '', 7);
INSERT INTO `GlParams` VALUES ('gallery_photo_width', '250', 2, 'Width of gallery photo in pixels', 'digit', '', '', 5);
INSERT INTO `GlParams` VALUES ('gallery_show_unapproved', 'on', 2, 'Show unapproved objects in gallery', 'checkbox', '', '', 2);
INSERT INTO `GlParams` VALUES ('gallery_video_size', '16777216', 2, 'Maximum size for video file in gallery (in byte)', 'digit', '', '', 9);
INSERT INTO `GlParams` VALUES ('lang_default', 'en', 0, 'Default site language', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('match_percent', '70', 12, 'Send a cupid mail if the recently joined profile matches more than this percentage', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('max_icon_height', '45', 23, 'Max height of profile icon (in pixels)', 'digit', '', '', 8);
INSERT INTO `GlParams` VALUES ('max_icon_width', '45', 23, 'Max width of profile icon (in pixels)', 'digit', '', '', 7);
INSERT INTO `GlParams` VALUES ('max_inbox_messages', '5', 3, 'Maximum number of messages stored in inbox', 'digit', '', '', 3);
INSERT INTO `GlParams` VALUES ('max_inbox_message_size', '1500', 3, 'Maximum message size in symbols', 'digit', '', '', 4);
INSERT INTO `GlParams` VALUES ('max_media_title', '150', 23, 'Max length of title for media file', 'digit', '', '', 3.1);
INSERT INTO `GlParams` VALUES ('max_news_header', '50', 10, 'Maximum length of news header', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('max_news_on_home', '2', 10, 'Maximum number of news items to show on homepage', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('max_news_preview', '128', 10, 'Maximum length of news preview', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('max_news_text', '4096', 10, 'Maximum length of news text', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('max_photo_files', '20', 23, 'Max number of profile photos', 'digit', '', '', 13);
INSERT INTO `GlParams` VALUES ('max_photo_height', '340', 23, 'Max height of profile photo (in pixels)', 'digit', '', '', 12);
INSERT INTO `GlParams` VALUES ('max_photo_width', '340', 23, 'Max width of profile photo (in pixels)', 'digit', '', '', 11);
INSERT INTO `GlParams` VALUES ('max_story_header', '32', 10, 'Maximum length of story header', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('max_story_preview', '400', 10, 'Maximum length of story preview text', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('max_story_text', '4096', 10, 'Maximum length of story text', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('max_thumb_height', '110', 23, 'Max height of profile thumbnail (in pixels)', 'digit', '', '', 10);
INSERT INTO `GlParams` VALUES ('max_thumb_width', '110', 23, 'Max width of profile thumbnail (in pixels)', 'digit', '', '', 9);
INSERT INTO `GlParams` VALUES ('membership_only', 'on', 5, 'Membership only (without shopping cart)', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('member_online_time', '5', 3, 'Time period in minutes within which a member is considered to be online', 'digit', '', '', 5);
INSERT INTO `GlParams` VALUES ('MetaDescription', '', 19, 'Insert Meta description on site  pages', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('MetaKeyWords', '', 19, 'Insert Meta keywords on site pages (comma-separated list)', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('min_media_title', '1', 23, 'Min length of title for media file', 'digit', '', '', 3.2);
INSERT INTO `GlParams` VALUES ('more_photos_on_searchrow', 'on', 1, 'Show "More Photos" link on search result', 'checkbox', '', '', 11);
INSERT INTO `GlParams` VALUES ('msgs_per_start', '20', 8, 'Send emails from queue, it happens every cron execution (5m-1h)', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('news_enable', '1', 0, 'show boonex news in admin panel', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('newusernotify', 'on', 1, 'New User Notify', 'checkbox', '', '', 2);
INSERT INTO `GlParams` VALUES ('profile_poll_num', '4', 20, 'Number of polls that user can create', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('profile_poll_act', 'on', 20, 'Enable profile polls activation', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('promotion_membership_days', '7', 7, 'Number of days for promotional membership', 'digit', '', '', 2);
INSERT INTO `GlParams` VALUES ('search_end_age', '75', 1, 'Highest age possible for site members', 'digit', '', '', 21);
INSERT INTO `GlParams` VALUES ('search_start_age', '18', 1, 'Lowest age possible for site members', 'digit', '', '', 20);
INSERT INTO `GlParams` VALUES ('short_date_format', '%m-%d-%y', 15, 'Short Date Format <a href="#" onclick="javascript: window.open(''/admin/help.html'', ''DateFormat'', ''width=500,height=400,scrollbars=yes,menubar=no,resizable=no''); return false;">?</a>', 'digit', '', '', 14);
INSERT INTO `GlParams` VALUES ('template', 'uni', 15, 'Template', 'combobox', 'global $dir; return (strlen($arg0) > 0 && file_exists($dir["root"]."templates/tmpl_".$arg0) ) ? true : false;', 'cannot be empty and template must be valid.', 17);
INSERT INTO `GlParams` VALUES ('top_members_max_num', '6', 0, 'How many results show on index page in top members area', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('top_members_mode', 'rand', 0, 'Show members on index page<br /> (if enabled in the template)', 'combobox', 'return $arg0 == ''online'' || $arg0 == ''rand'' || $arg0 == ''last'' || $arg0 == ''top'' ? true : false;', 'posible values : online, rand, last, top', NULL);
INSERT INTO `GlParams` VALUES ('track_profile_view', 'on', 1, 'Track all profile views. Later a member can manage these "views".', 'checkbox', '', '', 1);
INSERT INTO `GlParams` VALUES ('transparent1', '15', 16, 'Transparency for first image', 'digit', '', '', 2);
INSERT INTO `GlParams` VALUES ('transparent2', '15', 16, 'Transparency for second image', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_Activation', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>Your profile was reviewed and activated !</p>\r\n\r\n<p>Simply follow the link below to enjoy our services:<br /><a href="<Domain>member.php"><Domain>member.php</a></p>\r\n\r\n<p>Your identification number (ID): <span style="color:#FF6633"><recipientID></span></p>\r\n\r\n<p>Your e-mail used for registration: <span style="color:#FF6633"><Email></span></p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Profile activation message template. Automatically sent to a member, when profile status is changed to "Active".', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_Activation_subject', 'Profile status was changed to Active', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_AdminEmail', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>Administration of the <a href="<Domain>"><SiteName></a> is glad to inform you that </p>\r\n\r\n<p>=========================</p>\r\n<p style="color:#3B5C8E"><MessageText></p>\r\n<p>=========================</p>\r\n\r\n\r\n <p style="font-size:10px;">NOTE: You received this message because our records show that you are a registered member of <a href="<Domain>"><SiteName></a> (<Domain>).\r\n If you wish to unregister, log in to your member account and hit "Unregister".</p>\r\n\r\n<p>-----</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\nAuto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Email template for message sending from the Admin Panel.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_AdminEmail_subject', 'Message from <SiteName> Admin', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_Compose', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Hello <RealName></b>,</p>\r\n\r\n<p>You have received a message from <ProfileReference>!</p>\r\n\r\n<p>To check this message login to your account here: <a href="<Domain>member.php"><Domain>member.php</a></p>\r\n\r\n<p>---</p>\r\nBest regards,  <SiteName> \r\n<p style="font: bold 10px Verdana; color:red">!!!Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Email template for notification about new messages in the inbox.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_Compose_subject', 'Notification about new messages in the inbox', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_Confirmation', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>Thank you for registering at <SiteName>!</p>\r\n\r\n<p style="color:#3B5C8E">CONFIRMATION CODE: <ConfCode></p>\r\n\r\n<p>Or you can also simply follow the link below:\r\n<a href="<ConfirmationLink>"><ConfirmationLink></a></p>\r\n\r\n<p>This is necessary to complete your registration.<br />Without doing that you won''t be submitted to our database.</p>\r\n\r\n<p>Your identification number (ID): <span style="color:#FF6633; font-weight:bold;"><recipientID></span></p>\r\n\r\n<p>Your e-mail used for registration: \r\n<span style="color:#FF6633"><Email></span></p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Profile e-mail confirmation message template. Automatically sent to a registered member, and also can be sent by admin to the "Unconfirmed" members.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_Confirmation_subject', 'Confirm your profile', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_CupidMail', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Hello <RealName></b>,</p>\r\n\r\n<p>We are glad to inform you that a profile was added or modified at <Domain> that matches yours.</p>\r\n\r\n<p>Match profile:<span style="color:#FF6633"><a href="<MatchProfileLink>"><MatchProfileLink></a></span></p>\r\n\r\n<p>Your Member ID:<span style="color:#FF6633"><StrID></span></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Cupid mail template', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_CupidMail_subject', 'Match Notification', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_Forgot', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>Your member ID: <span style="color:#FF6633"><recipientID></span></p>\r\n\r\n<p>Your password: <span style="color:#FF6633"><Password></span></p>\r\n\r\n<p>You must login here: <span style="color:#FF6633"><a href="<Domain>member.php"><Domain>member.php</a></span></p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Forgot password email message', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_Forgot_subject', 'Forgot password email message', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_FreeEmail', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>You have requested <strong><profileNickName></strong>''s contact information.</p>\r\n\r\n<p><ContactInfo></p>\r\n\r\n<p>View member''s profile: <a href="<Domain>profile.php?ID=<profileID>"><Domain>profile.php?ID=<profileID></a></p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Free contact information letter template sent to members requesting contact information of those members available for free.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_FreeEmail_subject', 'Free contact information', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_MemExpiration', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Hello <RealName></b>,</p>\r\n\r\n<p>We are notifying you that your <SiteName> <MembershipName> will expire in <ExpireDays> days (-1 = already expired).\r\n\r\n To renew your membership login to your <SiteName> account at <a href="<Domain>member.php"><Domain>member.php</a> and go to <a href="<Domain>membership.php"><Domain>membership.php</a></p>\r\n\r\n<p>Your Member ID: <span style="color:#FF6633; font-weight:bold;"><recipientID></span></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Membership expiration letter sent to members whose membership level expires.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_MemExpiration_subject', '<your subject here>', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_Message', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>We are glad to inform you that the member\r\n<ProfileReference> has sent you a message! </p>\r\n\r\n<p>-------- Message ------------------------------------------------<br />\r\n<span style="color:#3B5C8E"><MessageText></span><br />\r\n---------------------------------------------------------------------\r\n</p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Message template sent to members when they receive messages from other members.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_Message_subject', 'You receive messages from other members', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_PrivPhotosAnswer', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Hello <NickName></b>,</p>\r\n\r\n<p>We are informing you that <PrivPhotosMember> granted you a password for their private photos.</p>\r\n\r\n<p>Link to <PrivPhotosMember> profile <a href="<Profile>"><Profile></a></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Answer for Private Photos Request template', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_PrivPhotosAnswer_subject', '<your subject here>', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_PrivPhotosRequest', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Hello <NickName></b>,</p>\r\n\r\n<p>We are informing you that <strong><profileNickName></strong> asks for a password for your private photos.</p>\r\n\r\n<p>Link to <profileNickName>''s profile <a href="<Domain>profile.php?ID=<profileID>"><Domain>profile.php?ID=<profileID></a></p>\r\n\r\n<p>----------</p>\r\n\r\n<p style="font: bold 10px Verdana; color:red"><site></p></body></html>', 4, 'Request fot Private Photos template', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_PrivPhotosRequest_subject', 'Request for private photo password at <SiteName>', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_PurchaseContacts', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Hello <RealName></b>,</p>\r\n\r\n<p>You purchased the following profiles on <b><SiteName></b>:</p>\r\n\r\n<ProfileList>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Purchase contacts letter template', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_PurchaseContacts_subject', 'Your purchase at <SiteName>', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_Rejection', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>Your profile was reviewed and rejected due to the following reasons:</p>\r\n\r\n<p>1) Your profile information was supplied in the wrong  language. <br />\r\n2) Your profile contains illegal information. Make sure that you: do not use black language, do not specify your contact information in the wrong text fields;<br />\r\n3) You have uploaded unacceptable photos to your profile;<br />\r\n4) We doubt that you are a real person. </p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Profile rejection message template. Automatically sent to a member, when profile status is changed to "Reject".', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_Rejection_subject', 'Profile status was changed to Rejected', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_SDatingAdminEmail', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>Administration of the <a href="<Domain>"><Domain></a> <b><SiteName></b> is glad to inform you that</p>\r\n\r\n<p><MessageText></p>\r\n\r\n<p>We are reminding you that your Unique ID is <b><PersonalUID></b>.</p>\r\n\r\n<p>-----</p>\r\n<p>NOTE: You received this message because you are a registered member of <b><SiteName></b>\r\nand also are a participant of the SpeedDating "<NameSDating>" held at "<PlaceSDating>" <WhenStarSDating>.<br />\r\nPlease visit <a href="<LinkSDatingEvent>"><LinkSDatingEvent></a> to see the event details.</p>\r\n\r\n<p>---</p>\r\n<p style="font: bold 10px Verdana; color:red">!!!Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Email template for message sending from the SpeedDating''s Admin Panel.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_SDatingAdminEmail_subject', 'Additional information on SpeedDating.', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_SDatingCongratulation', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>We are glad to inform you that You successfully purchased a ticket for SpeedDating "<NameSDating>" which will take place at "<PlaceSDating>" <WhenStarSDating>.<br />\r\nYour personal Unique ID is <b><PersonalUID></b>. If you want to change it please click <a href="<LinkSDatingEvent>">here</a>.</p>\r\n\r\n<p>Please visit <a href="<LinkSDatingEvent>"><LinkSDatingEvent></a> to see event details.</p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>---</p>\r\n<p style="font: bold 10px Verdana; color:red">!!!Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'SpeedDating message template. Automatically sent to a member after ticket purchase.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_SDatingCongratulation_subject', 'SpeedDating ticket purchase', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_SDatingMatch', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>We are glad to inform you that You were matched with the following participant of SpeedDating "<NameSDating>" which took place at "<PlaceSDating>" <WhenStarSDating>: <a href="<MatchLink>"><MatchLink></a></p>\r\n\r\n<p>Please visit <a href="<LinkSDatingEvent>"><LinkSDatingEvent></a> to see the event details.</p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>---</p>\r\n<p style="font: bold 10px Verdana; color:red">!!!Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'SpeedDating message template. Automatically sent to a member when there is a match.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_SDatingMatch_subject', 'Congratulations! You were successfully matched during SpeedDating!', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_SpamReport', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><a href="<Domain>profile.php?ID=<reporterID>">User <b><reporterNick> (<reporterID>)</b></a> reported that user <a href="<Domain>profile.php?ID=<spamerID>"><b><spamerNick> (<spamerID>)</b></a> spammed.</p>\r\n\r\n<p>Reporter: <span style="color:#FF6633;"><a href="<Domain>profile.php?ID=<reporterID>"><Domain>profile.php?ID=<reporterID></a></span>\r\n<br />Spammer: <span style="color:#FF6633;"><a href="<Domain>profile.php?ID=<spamerID>"><Domain>profile.php?ID=<spamerID></a></span></p></body></html>', 4, 'Template for a "Report Spam" feature.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_SpamReport_subject', 'Spam report from <SiteName>', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_TellFriend', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Hello</b>,</p>\r\n\r\n<p>I surfed the web and found a cool site: <a href="<Link>"><Link></a><br />\r\nI thought it might be interesting to you.</p>\r\n\r\n<p><span style="color:#FF6633"><FromName></span></p></body></html>', 4, 'Template for "Invite a Friend" feature.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_TellFriendProfile', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Hello</b>,</p>\r\n\r\n<p>I surfed the web and found a cool member''s profile: <a href="<Link>"><Link></a><br />\r\nI thought it might be interesting to you.</p>\r\n\r\n<p><span style="color:#FF6633"><FromName></span></p></body></html>', 4, 'Template for "Email profile to a friend" feature.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_TellFriendProfile_subject', 'Email profile to a friend', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_TellFriend_subject', 'Invite a Friend', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_VKiss', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>We are glad to inform you that member <ProfileReference> sent you a greeting!</p>\r\n\r\n<p>A greeting means that the member is interested in contacting you. Please, be polite and answer with your greeting in return. You can send it by merely following the link:<br />\r\n<VKissLink>\r\n</p>\r\n\r\n<p><b>Thank you for using our services!</b></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Greeting notification letter template sent to members when they receive greetings from other members. The letter also allows you to instantly send a greeting back.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_VKiss_visitor', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Dear <RealName></b>,</p>\r\n\r\n<p>We are glad to inform you that <b>Visitor</b> sent you a greeting!</p>\r\n\r\n<p>A greeting means that the person visited your profile and liked it. Have a nice day and enjoy!</p>\r\n\r\n<p>Thank you for using our services!</p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 4, 'Greeting notification letter template sent to members when they receive greetings from other members. The letter also allows you to instantly send a greeting back.', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('t_VKiss_subject', 'Greeting notification', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('votes', 'on', 1, 'Enable profile votes', 'checkbox', '', '', 5);
INSERT INTO `GlParams` VALUES ('votes_pic', 'on', 1, 'Enable photos votes', 'checkbox', '', '', 6);
INSERT INTO `GlParams` VALUES ('Water_Mark', '', 16, 'Water Mark', 'text', '', '', 3);
INSERT INTO `GlParams` VALUES ('zodiac', '', 1, 'Show zodiac signs', 'checkbox', '', '', 9);
INSERT INTO `GlParams` VALUES ('php_date_format', 'F j, Y', 15, 'PHP date format', 'digit', '', '', 16);
INSERT INTO `GlParams` VALUES ('group_img_width', '600', 24, 'Gallery max image width', 'digit', '', '', 1);
INSERT INTO `GlParams` VALUES ('group_img_height', '600', 24, 'Gallery max image height', 'digit', '', '', 2);
INSERT INTO `GlParams` VALUES ('group_img_tmb_width', '100', 24, 'Gallery max image thumb width', 'digit', '', '', 3);
INSERT INTO `GlParams` VALUES ('group_img_tmb_height', '100', 24, 'Gallery max image thumb height', 'digit', '', '', 4);
INSERT INTO `GlParams` VALUES ('group_invitation_text', '<b>{sender}</b> has invited you to join <b>{group}</b>.<br />\r\nGroups allow users to communicate on the forums on interesting topics, share pictures, etc.<br />\r\nYou may accept or reject this invitation below:<br />\r\n<b>{accept} &nbsp; &nbsp; &nbsp; {reject}</b>', 24, 'Group invitation text', 'text', '', '', 5);
INSERT INTO `GlParams` VALUES ('group_approve_notify', 'The creator of the {group} allows you to join the group.<br />\r\nNow you''re an active member of this group and you can share your opinion, post images and communicate on message boards.\r\n', 24, 'Group member approve notification', 'text', '', '', 6);
INSERT INTO `GlParams` VALUES ('group_creator_request', 'Hello, {creator}.<br />\r\nMember {member} would like to join your group {group}.<br />\r\nYou may approve or reject this join request below:<br />\r\n{approve} &nbsp; &nbsp; &nbsp; {reject}', 24, 'Request message to group creator', 'text', '', '', 8);
INSERT INTO `GlParams` VALUES ('group_reject_notify', 'Dear {member},<br />\r\nSorry but the creator of the group {group} doesn''t allow you to join the group. If you wish try again later.\r\n', 24, 'Group member reject notification', 'text', '', '', 7);
INSERT INTO `GlParams` VALUES ('top_photos_max_num', '8', 0, 'How many photos show on index page in photos area', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('top_photos_mode', 'rand', 0, 'Show members on index page<br /> (if enabled in the template)', 'combobox', 'return $arg0 == ''rand'' || $arg0 == ''last'' || $arg0 == ''top'' ? true : false;', 'posible values: rand, last, top', NULL);
INSERT INTO `GlParams` VALUES ('tags_non_parsable', 'hi, hey, hello, all, i, i''m, i''d, am, for, in, to, a, the, on, it''s, is, my, of, are, from, i''m, me, you, and, we, not, will, at, where, there', 25, 'Non-parsable tags (type all tags in lower case, delimit them by comma)', 'text', '', '', 0);
INSERT INTO `GlParams` VALUES ('tags_last_parse_time', '0', 0, 'Temporary value when tags cron-job was runed last time', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('tags_min_rating', '2', 25, 'Minimum rating of tag to show it', 'digit', '', '', 2);
INSERT INTO `GlParams` VALUES ('max_blogs_on_home', '3', 22, 'Maximum number of Blogs to show on homepage', 'digit', '', '', 2);
INSERT INTO `GlParams` VALUES ('max_blog_preview', '128', 22, 'Maximum length of Blog preview', 'digit', '', '', 3);
INSERT INTO `GlParams` VALUES ('profile_view_cols', 'thin,thick', 0, 'Profile view columns order', 'digit', '', '', NULL);
INSERT INTO `GlParams` VALUES ('a_max_live_days_classifieds', '30', 3, 'How long can Classifieds live (days)', 'digit', '', '', 10);
INSERT INTO `GlParams` VALUES ('autoApproval_ifNoConfEmail', 'on', 6, 'Automatic profile confirmation without Confirmation Email', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES('enable_paid_system', 'on', 3, 'Enable Ability to work with Buy Now button in Classifieds', 'checkbox', '', '', 9);
INSERT INTO `GlParams` VALUES('t_BuyNow', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<div style="border:1px solid #CCCCCC;">\r\n<div style="color:#666666; font-weight:bold; height:23px; padding:3px 0px 0px 6px; text-transform:uppercase;">\r\nCongratulations! <String1>.\r\n</div>\r\n<div style="padding:3px 3px 10px;">\r\nItem title: <Subject><br/>\r\nSeller`s Name: <NickName><br/>\r\nSeller`s email: <EmailS><br/><br/>\r\nBuyer Name: <NickNameB><br/>\r\nBuyer email: <EmailB><br/><br/>\r\nPrice details: <sCustDetails><br/><br/>\r\nLink to Item<br/>\r\n<a href="<ShowAdvLnk>"><ShowAdvLnk></a><br/><br/>\r\nContact the <Who> directly to arrange payment and delivery. To avoid fraud, we recommend dealing locally, avoiding Western Union and wire transfers.<br/><br/>\r\nThank you for using our site,<br/>\r\n<sPowDol><br/>\r\nP.S. If you ever need support or have comments for us, e-mail us at <site[''email'']></div></div>\r\n</body></html>', 4, 'BuyNow notification letter template for Buyer', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES('t_BuyNow_subject', 'You have purchased an item', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES('t_BuyNowS', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<div style="border:1px solid #CCCCCC;">\r\n<div style="color:#666666; font-weight:bold; height:23px; padding:3px 0px 0px 6px; text-transform:uppercase;">\r\nCongratulations! <String1>.\r\n</div>\r\n<div style="padding:3px 3px 10px;">\r\nItem title: <Subject><br/>\r\nSeller`s Name: <NickName><br/>\r\nSeller`s email: <EmailS><br/><br/>\r\nBuyer Name: <NickNameB><br/>\r\nBuyer email: <EmailB><br/><br/>\r\nPrice details: <sCustDetails><br/><br/>\r\nLink to Item<br/>\r\n<a href="<ShowAdvLnk>"><ShowAdvLnk></a><br/><br/>\r\nContact the <Who> directly to arrange payment and delivery. To avoid fraud, we recommend dealing locally, avoiding Western Union and wire transfers.<br/><br/>\r\nThank you for using our site,<br/>\r\n<sPowDol><br/>\r\nP.S. If you ever need support or have comments for us, e-mail us at <site[''email'']></div></div>\r\n</body></html>', 4, 'BuyNow notification letter template for Seller', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES('t_BuyNowS_subject', 'An item offered by you  has been purchased', 4, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('enable_shPhotoActivation', 'on', 23, 'Enable auto-activation for gallery photos', 'checkbox', '', '', 19);
INSERT INTO `GlParams` VALUES ('shPhotoLimit', '10', 23, 'Number of gallery photos which can be uploaded by user', 'digit', '', '', 20);
INSERT INTO `GlParams` VALUES ('enable_flash_promo', 'on', 0, '', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('custom_promo_code', '', 0, '', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('license_code', '', 1, 'Dolphin License Code', 'digit', '', '', 0);
INSERT INTO `GlParams` VALUES ('enable_get_boonex_id', 'on', 1, 'Enable BoonEx ID import', 'checkbox', '', '', 0.1);
INSERT INTO `GlParams` VALUES('enable_dolphin_footer', 'on', 0, 'enable boonex footers', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES('enable_orca_footer', 'on', 0, 'enable boonex footers', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES('enable_ray_footer', 'on', 0, 'enable boonex footers', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES ('enable_classifieds_sort', 'on', 3, 'Enable Sort in Classifieds', 'checkbox', '', '', 12);
INSERT INTO `GlParams` VALUES('topmenu_items_perline', '0', 15, 'Number of items per line in top menu. 0 - no breaking.', 'digit', '', '', 20);
INSERT INTO `GlParams` VALUES ('autoApproval_Classifieds', 'on', 3, 'Automatic advertisements activation after adding', 'checkbox', '', '', 13);
INSERT INTO `GlParams` VALUES ('number_articles', '2', 0, 'Number of articles displayed on front page', 'digit', 'return $arg0 >= 0;', 'must be equal to or greater than zero.', NULL);
INSERT INTO `GlParams` VALUES ('enable_modrewrite', 'on', 26, 'Enable friendly profile permalinks', 'checkbox', '', '', 1);
INSERT INTO `GlParams` VALUES ('permalinks_articles', 'on', 26, 'Enable friendly articles permalinks', 'checkbox', '', '', 2);
INSERT INTO `GlParams` VALUES ('permalinks_news', 'on', 26, 'Enable friendly news permalinks', 'checkbox', '', '', 3);
INSERT INTO `GlParams` VALUES ('permalinks_blogs', 'on', 26, 'Enable friendly blogs permalinks', 'checkbox', '', '', 4);
INSERT INTO `GlParams` VALUES ('permalinks_events', 'on', 26, 'Enable friendly events permalinks', 'checkbox', '', '', 5);
INSERT INTO `GlParams` VALUES ('permalinks_classifieds', 'on', 26, 'Enable friendly classifieds permalinks', 'checkbox', '', '', 6);
INSERT INTO `GlParams` VALUES ('permalinks_gallery_photos', 'on', 26, 'Enable friendly gallery photos permalinks', 'checkbox', '', '', 7);
INSERT INTO `GlParams` VALUES ('permalinks_gallery_music', 'on', 26, 'Enable friendly gallery music permalinks', 'checkbox', '', '', 8);
INSERT INTO `GlParams` VALUES ('permalinks_gallery_videos', 'on', 26, 'Enable friendly gallery videos permalinks', 'checkbox', '', '', 9);
INSERT INTO `GlParams` VALUES ('permalinks_groups', 'on', 26, 'Enable friendly groups permalinks', 'checkbox', '', '', 10);
INSERT INTO `GlParams` VALUES ('cupid_last_cron', '0', 0, 'Temporary value when cupid mails checked was runed last time', 'text', '', '', NULL);
INSERT INTO `GlParams` VALUES ('reg_by_inv_only', '', 3, 'Registration by invitation only (need before Enable affiliate support)', 'checkbox', '', '', 13);
INSERT INTO `GlParams` VALUES('main_div_width', '960px', 0, 'Width of the main container of the site', 'digit', '', '', 0);
INSERT INTO `GlParams` VALUES('promoWidth', '960', 0, 'Default Width of the Promo Images for resizing', 'digit', '', '', 0);
INSERT INTO `GlParams` VALUES('ads_gallery_feature', '', 3, 'New Gallery Feature for Classifieds', 'checkbox', '', '', NULL);
INSERT INTO `GlParams` VALUES('profile_gallery_feature', '', 3, 'New Gallery Feature for Profile Photos', 'checkbox', '', '', NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `GlParamsKateg`
-- 

CREATE TABLE `GlParamsKateg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `menu_order` float default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `GlParamsKateg`
-- 

INSERT INTO `GlParamsKateg` VALUES (1, 'Profiles', 1);
INSERT INTO `GlParamsKateg` VALUES (2, 'Galleries', 2);
INSERT INTO `GlParamsKateg` VALUES (3, 'Other', 3);
INSERT INTO `GlParamsKateg` VALUES (4, 'Emails', 4);
INSERT INTO `GlParamsKateg` VALUES (5, 'Memberships', 5);
INSERT INTO `GlParamsKateg` VALUES (6, 'Postmoderation', 6);
INSERT INTO `GlParamsKateg` VALUES (7, 'Promotions', 7);
INSERT INTO `GlParamsKateg` VALUES (8, 'Notifies', 8);
INSERT INTO `GlParamsKateg` VALUES (10, 'News', 10);
INSERT INTO `GlParamsKateg` VALUES (11, 'Pruning', 11);
INSERT INTO `GlParamsKateg` VALUES (12, 'Matches', 12);
INSERT INTO `GlParamsKateg` VALUES (15, 'Variables', 14);
INSERT INTO `GlParamsKateg` VALUES (16, 'Watermarks', 15);
INSERT INTO `GlParamsKateg` VALUES (17, 'Messages', 16);
INSERT INTO `GlParamsKateg` VALUES (19, 'Meta Tags', 18);
INSERT INTO `GlParamsKateg` VALUES (20, 'Polls', 21);
INSERT INTO `GlParamsKateg` VALUES (21, 'Events', 20);
INSERT INTO `GlParamsKateg` VALUES (22, 'Blogs', 9);
INSERT INTO `GlParamsKateg` VALUES (23, 'Media', 22);
INSERT INTO `GlParamsKateg` VALUES (24, 'Groups', 24);
INSERT INTO `GlParamsKateg` VALUES (25, 'Tags', 25);

-- --------------------------------------------------------

-- 
-- Table structure for table `gmusic_rating`
-- 

CREATE TABLE `gmusic_rating` (
  `gal_id` int(12) NOT NULL default '0',
  `gal_rating_count` int(11) NOT NULL default '0',
  `gal_rating_sum` int(11) NOT NULL default '0',
  UNIQUE KEY `med_id` (`gal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `gmusic_rating`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `gmusic_voting_track`
-- 

CREATE TABLE `gmusic_voting_track` (
  `gal_id` int(12) NOT NULL default '0',
  `gal_ip` varchar(20) default NULL,
  `gal_date` datetime default NULL,
  KEY `med_ip` (`gal_ip`,`gal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `gmusic_voting_track`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `gphoto_rating`
-- 

CREATE TABLE `gphoto_rating` (
  `gal_id` int(12) NOT NULL default '0',
  `gal_rating_count` int(11) NOT NULL default '0',
  `gal_rating_sum` int(11) NOT NULL default '0',
  UNIQUE KEY `med_id` (`gal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `gphoto_rating`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `gphoto_voting_track`
-- 

CREATE TABLE `gphoto_voting_track` (
  `gal_id` int(12) NOT NULL default '0',
  `gal_ip` varchar(20) default NULL,
  `gal_date` datetime default NULL,
  KEY `med_ip` (`gal_ip`,`gal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `gphoto_voting_track`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `Groups`
-- 

CREATE TABLE `Groups` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `categID` int(10) unsigned NOT NULL default '0',
  `Name` varchar(64) NOT NULL default '',
  `Uri` varchar(64) NOT NULL default '',
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

-- 
-- Dumping data for table `Groups`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `GroupsCateg`
-- 

CREATE TABLE `GroupsCateg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL default '',
  `Uri` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Uri` (`Uri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `GroupsCateg`
--

INSERT INTO `GroupsCateg` VALUES(9, 'Arts & Literature', 'Arts-Literature');
INSERT INTO `GroupsCateg` VALUES(8, 'Animals & Pets', 'Animals-Pets');
INSERT INTO `GroupsCateg` VALUES(7, 'Activities', 'Activities');
INSERT INTO `GroupsCateg` VALUES(10, 'Automotive', 'Automotive');
INSERT INTO `GroupsCateg` VALUES(11, 'Business & Money', 'Business-Money');
INSERT INTO `GroupsCateg` VALUES(12, 'Companies & Co-workers', 'Companies-Co-workers');
INSERT INTO `GroupsCateg` VALUES(13, 'Cultures & Nations', 'Cultures-Nations');
INSERT INTO `GroupsCateg` VALUES(14, 'Dolphin Community', 'Dolphin Community');
INSERT INTO `GroupsCateg` VALUES(15, 'Family & Friends', 'Family-Friends');
INSERT INTO `GroupsCateg` VALUES(16, 'Fan Clubs', 'Fan Clubs');
INSERT INTO `GroupsCateg` VALUES(17, 'Fashion & Style', 'Fashion-Style');
INSERT INTO `GroupsCateg` VALUES(18, 'Fitness & Body Building', 'Fitness-Body Building');
INSERT INTO `GroupsCateg` VALUES(19, 'Food & Drink', 'Food-Drink');
INSERT INTO `GroupsCateg` VALUES(20, 'Gay, Lesbian & Bi', 'Gay, Lesbian-Bi');
INSERT INTO `GroupsCateg` VALUES(21, 'Health & Wellness', 'Health-Wellness');
INSERT INTO `GroupsCateg` VALUES(22, 'Hobbies & Entertainment', 'Hobbies-Entertainment');
INSERT INTO `GroupsCateg` VALUES(23, 'Internet & Computers', 'Internet-Computers');
INSERT INTO `GroupsCateg` VALUES(24, 'Love & Relationships', 'Love-Relationships');
INSERT INTO `GroupsCateg` VALUES(25, 'Mass Media', 'Mass Media');
INSERT INTO `GroupsCateg` VALUES(26, 'Music & Cinema', 'Music-Cinema');
INSERT INTO `GroupsCateg` VALUES(27, 'Other', 'Other');
INSERT INTO `GroupsCateg` VALUES(28, 'Places & Travel', 'Places-Travel');
INSERT INTO `GroupsCateg` VALUES(29, 'Politics', 'Politics');
INSERT INTO `GroupsCateg` VALUES(30, 'Recreation & Sports', 'Recreation-Sports');
INSERT INTO `GroupsCateg` VALUES(31, 'Religion', 'Religion');
INSERT INTO `GroupsCateg` VALUES(32, 'Science & Innovations', 'Science-Innovations');
INSERT INTO `GroupsCateg` VALUES(33, 'Sex', 'Sex');
INSERT INTO `GroupsCateg` VALUES(34, 'Teens & Schools', 'Teens-Schools');

-- --------------------------------------------------------

-- 
-- Table structure for table `GroupsGallery`
-- 

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

-- 
-- Dumping data for table `GroupsGallery`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `GroupsMembers`
-- 

CREATE TABLE `GroupsMembers` (
  `memberID` int(10) unsigned NOT NULL default '0',
  `groupID` int(10) unsigned NOT NULL default '0',
  `status` varchar(25) NOT NULL default '',
  `Date` datetime NOT NULL default '0000-00-00 00:00:00',
  KEY `groupID` (`groupID`,`memberID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `GroupsMembers`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `grp_forum`
-- 

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

-- 
-- Dumping data for table `grp_forum`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `grp_forum_cat`
-- 

CREATE TABLE `grp_forum_cat` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `cat_uri` varchar(255) NOT NULL default '',
  `cat_name` varchar(255) default NULL,
  `cat_icon` varchar(32) NOT NULL default '',
  `cat_order` float NOT NULL default '0',
  PRIMARY KEY  (`cat_id`),
  KEY `cat_order` (`cat_order`),
  KEY `cat_uri` (`cat_uri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `grp_forum_cat`
-- 

INSERT INTO `grp_forum_cat` VALUES(1, '', 'Groups', '', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `grp_forum_flag`
-- 

CREATE TABLE `grp_forum_flag` (
  `user` varchar(16) NOT NULL default '',
  `topic_id` int(11) NOT NULL default '0',
  `when` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user`,`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `grp_forum_flag`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `grp_forum_post`
-- 

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

-- 
-- Dumping data for table `grp_forum_post`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `grp_forum_report`
-- 

CREATE TABLE `grp_forum_report` (
  `user_name` varchar(16) NOT NULL default '',
  `post_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_name`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `grp_forum_report`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `grp_forum_topic`
-- 

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

-- 
-- Dumping data for table `grp_forum_topic`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `grp_forum_user`
-- 

CREATE TABLE `grp_forum_user` (
  `user_name` varchar(16) NOT NULL default '',
  `user_pwd` varchar(32) NOT NULL default '',
  `user_email` varchar(128) NOT NULL default '',
  `user_join_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `grp_forum_user`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `grp_forum_user_activity`
-- 

CREATE TABLE `grp_forum_user_activity` (
  `user` varchar(16) NOT NULL default '',
  `act_current` int(11) NOT NULL default '0',
  `act_last` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `grp_forum_user_activity`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `grp_forum_user_stat`
-- 

CREATE TABLE `grp_forum_user_stat` (
  `user` varchar(16) NOT NULL default '',
  `posts` int(11) NOT NULL default '0',
  `user_last_post` int(11) NOT NULL default '0',
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `grp_forum_user_stat`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `grp_forum_vote`
-- 

CREATE TABLE `grp_forum_vote` (
  `user_name` varchar(16) NOT NULL default '',
  `post_id` int(11) NOT NULL default '0',
  `vote_when` int(11) NOT NULL default '0',
  `vote_point` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`user_name`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `grp_forum_vote`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `Guestbook`
-- 

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

-- 
-- Dumping data for table `Guestbook`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `gvideo_rating`
-- 

CREATE TABLE `gvideo_rating` (
  `gal_id` int(12) NOT NULL default '0',
  `gal_rating_count` int(11) NOT NULL default '0',
  `gal_rating_sum` int(11) NOT NULL default '0',
  UNIQUE KEY `med_id` (`gal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `gvideo_rating`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `gvideo_voting_track`
-- 

CREATE TABLE `gvideo_voting_track` (
  `gal_id` int(12) NOT NULL default '0',
  `gal_ip` varchar(20) default NULL,
  `gal_date` datetime default NULL,
  KEY `med_ip` (`gal_ip`,`gal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `gvideo_voting_track`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `HotList`
-- 

CREATE TABLE `HotList` (
  `ID` bigint(8) NOT NULL default '0',
  `Profile` bigint(8) NOT NULL default '0',
  UNIQUE KEY `HotPair` (`ID`,`Profile`),
  KEY `ID` (`ID`),
  KEY `Profile` (`Profile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `HotList`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `IMessages`
-- 

CREATE TABLE `IMessages` (
  `IDFrom` bigint(8) NOT NULL default '0',
  `IDTo` bigint(8) NOT NULL default '0',
  `When` datetime NOT NULL default '0000-00-00 00:00:00',
  `Msg` char(255) NOT NULL default '',
  KEY `IDFrom` (`IDFrom`),
  KEY `IDTo` (`IDTo`),
  KEY `IDFrom_2` (`IDFrom`,`IDTo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `IMessages`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `Links`
-- 

CREATE TABLE `Links` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `Title` varchar(250) default NULL,
  `URL` varchar(100) NOT NULL default '',
  `Description` mediumtext,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `Links`
-- 

INSERT INTO `Links` VALUES (6, 'Free Online Dating Personals - 4ppl', 'http://www.4ppl.com/', 'Looking for friends, a match or pen pals? 4PPL is exactly for you. Join the First Absolutely Free dating site without any membership payments or annoying ads. Enjoy its simple, pleasurable design and modern features. Use it for free!');
INSERT INTO `Links` VALUES (8, 'Ray Community Widget Suite', 'http://www.boonex.com/products/ray/', 'Expand your community site with A/V chat, A/V Instant Messenger, A/V Recorder, MP3 Player, Web Presence and Desktop Application. Bring life to your community site!');
INSERT INTO `Links` VALUES (10, 'Expertzzz Community Software Support', 'http://www.expertzzz.com/', 'The goal of our site is to unite people who can deliver support for any community software and who want to receive qualified support. Here you can sell your products and services, as well as buy everything you want to make a Unique Community website. ');
INSERT INTO `Links` VALUES (11, 'LoveLandia: Love Poems', 'http://www.lovelandia.com/', 'LoveLandia is the best place to share your love poems, love quotes, stories, songs, tips and much more about Love. Just visit our LoveLandia site and enjoy its modern features: chat, A/V Recorder, Forum, "Transfer to" and more! Disclose your talent! Impress your Lover!');
INSERT INTO `Links` VALUES (12, 'Shark Enterprise Community Platform', 'http://www.boonex.com/products/shark/', 'Specially developed software for big community websites. Turn your small community site into a serious moneymaking business!');
INSERT INTO `Links` VALUES (13, 'Orca Interactive Forum Script', 'http://www.boonex.com/products/orca/', 'The first Interactive Forum Script based on AJAX technology! Self-Ruling, Integrable and under General Public License.');
INSERT INTO `Links` VALUES (14, 'Barracuda Collaborative Directory Software', 'http://www.boonex.com/products/barracuda/', 'Collaborative Directory Software is a free and handy  web directory software bundle, combining the power of PHP+AJAX technologies, modern design and professional support by the BoonEx team. Barracuda will help you create a powerful links directory and make your website traffic grow.');
INSERT INTO `Links` VALUES (15, 'Dolphin Smart Community Builder', 'http://www.boonex.com/products/dolphin/', 'Dolphin is secure, modifiable and reliably tested Community Software which will help you to build a Unique Community website.');
INSERT INTO `Links` VALUES (17, 'DreamSCat: Sharing Ideas & Dreams', 'http://www.dreamscat.com/', 'Simply the best place to share and learn genius ideas on science, environment and fun. You can also post your know-how concerning business, beauty and cooking as well as declare your miracles and dreams. Look for creative and useful tips here on self-improvement, health and home.');
INSERT INTO `Links` VALUES (18, 'Make Your Car Famous :: AboutMyCar', 'http://www.aboutmycar.com/', 'Car stories about real and dream cars with photos, experience and impressions. Latest auto news on new car models, car high tech, motorsports; histories of car builders; vintage automotive proverbs & sayings, auto humor.');
INSERT INTO `Links` VALUES (19, 'Boonex Community Software Experts', 'http://www.boonex.com/', 'BoonEx delivers quality community web  application software with more features than you ever dreamed of. We offer community software development which responds to rapid changes in Internet-related technologies. You can find everything you need to create your own community!\r\n\r\n');
INSERT INTO `Links` VALUES (22, 'BoonEx Blog', 'http://www.boonex.org/', 'BoonEx.org, hosted by Andrey Sivtsov - general Director of BoonEx, is a discussion venue for future releases of BoonEx products and functionality of BoonEx websites. BoonEx Blog is a part of Unite People movement, supported by BoonEx. Anyone is welcome to participate by sharing ideas, testing products and providing suggestions.');
INSERT INTO `Links` VALUES (21, 'BoonEx Development Zone', 'http://www.boonex.net/', 'Have a suggestion for the new BoonEx products versions? You are welcome to use BoonEx.net where you can make your deposit in the Ray or Shark development process via a "Ticket" system. To be up-to-date you can view the "Timeline" of the development progress and the "Roadmap" of what will be done. Many of those who wish to help build a better product are already there.');

-- --------------------------------------------------------

-- 
-- Table structure for table `LocalizationCategories`
-- 

CREATE TABLE `LocalizationCategories` (
  `ID` tinyint(3) unsigned NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `LocalizationCategories`
-- 

INSERT INTO `LocalizationCategories` VALUES (1, 'Page texts');
INSERT INTO `LocalizationCategories` VALUES (2, 'Page titles');
INSERT INTO `LocalizationCategories` VALUES (3, 'Action messages');
INSERT INTO `LocalizationCategories` VALUES (4, 'Membership');
INSERT INTO `LocalizationCategories` VALUES (5, 'Blog');
INSERT INTO `LocalizationCategories` VALUES (6, 'Gallery');
INSERT INTO `LocalizationCategories` VALUES (7, 'Events');
INSERT INTO `LocalizationCategories` VALUES (8, 'Promotional texts');
INSERT INTO `LocalizationCategories` VALUES (9, 'Months');
INSERT INTO `LocalizationCategories` VALUES (10, 'Age ranges');
INSERT INTO `LocalizationCategories` VALUES (11, 'Body type');
INSERT INTO `LocalizationCategories` VALUES (12, 'Countries');
INSERT INTO `LocalizationCategories` VALUES (13, 'Education');
INSERT INTO `LocalizationCategories` VALUES (14, 'Ethnicity');
INSERT INTO `LocalizationCategories` VALUES (15, 'Income');
INSERT INTO `LocalizationCategories` VALUES (16, 'Language');
INSERT INTO `LocalizationCategories` VALUES (17, 'Marital status');
INSERT INTO `LocalizationCategories` VALUES (18, 'Person''s height');
INSERT INTO `LocalizationCategories` VALUES (19, 'Profile status');
INSERT INTO `LocalizationCategories` VALUES (20, 'Relationship');
INSERT INTO `LocalizationCategories` VALUES (21, 'Religion');
INSERT INTO `LocalizationCategories` VALUES (22, 'Smoking/drinking levels');
INSERT INTO `LocalizationCategories` VALUES (23, 'Zodiac signs');
INSERT INTO `LocalizationCategories` VALUES (24, 'Profile fields relevant');
INSERT INTO `LocalizationCategories` VALUES (25, 'Instant Messenger');
INSERT INTO `LocalizationCategories` VALUES (26, 'Checkout');
INSERT INTO `LocalizationCategories` VALUES (27, 'Polls');
INSERT INTO `LocalizationCategories` VALUES (100, 'Misc');
INSERT INTO `LocalizationCategories` VALUES (101, 'media');
INSERT INTO `LocalizationCategories` VALUES (102, 'Groups');
INSERT INTO `LocalizationCategories` VALUES (103, 'QSearch');
INSERT INTO `LocalizationCategories` VALUES (105, 'Classifieds');
INSERT INTO `LocalizationCategories` VALUES(32, 'Profile Fields');
INSERT INTO `LocalizationCategories` VALUES(106, 'Comments');

-- --------------------------------------------------------

-- 
-- Table structure for table `LocalizationKeys`
-- 

CREATE TABLE `LocalizationKeys` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `IDCategory` tinyint(3) unsigned NOT NULL default '0',
  `Key` varchar(255) character set utf8 collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Key` (`Key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `LocalizationKeys`
-- 

INSERT INTO `LocalizationKeys` VALUES (1, 8, '_bottom_text');
INSERT INTO `LocalizationKeys` VALUES (2, 8, '_copyright');
INSERT INTO `LocalizationKeys` VALUES (3, 100, '_Notify me about News, tips');
INSERT INTO `LocalizationKeys` VALUES (4, 100, '_compose_message_notify');
INSERT INTO `LocalizationKeys` VALUES (5, 100, '_GuestBook');
INSERT INTO `LocalizationKeys` VALUES (32, 8, '_promo_text_act_1');
INSERT INTO `LocalizationKeys` VALUES (33, 8, '_promo_act_0');
INSERT INTO `LocalizationKeys` VALUES (34, 8, '_promo_act_1');
INSERT INTO `LocalizationKeys` VALUES (35, 8, '_promo_act_2');
INSERT INTO `LocalizationKeys` VALUES (57, 100, '_AOL');
INSERT INTO `LocalizationKeys` VALUES (58, 100, '_IM now (not allowed)');
INSERT INTO `LocalizationKeys` VALUES (59, 100, '_im_up');
INSERT INTO `LocalizationKeys` VALUES (60, 100, '_aol');
INSERT INTO `LocalizationKeys` VALUES (61, 100, '_a man');
INSERT INTO `LocalizationKeys` VALUES (62, 100, '_a woman');
INSERT INTO `LocalizationKeys` VALUES (63, 100, '_a man or a woman');
INSERT INTO `LocalizationKeys` VALUES (64, 100, '_a_female');
INSERT INTO `LocalizationKeys` VALUES (65, 100, '_a_male');
INSERT INTO `LocalizationKeys` VALUES (66, 100, '_a_couple');
INSERT INTO `LocalizationKeys` VALUES (67, 100, '_About me');
INSERT INTO `LocalizationKeys` VALUES (70, 100, '_About Us');
INSERT INTO `LocalizationKeys` VALUES (71, 100, '_About you');
INSERT INTO `LocalizationKeys` VALUES (72, 100, '_Activate account');
INSERT INTO `LocalizationKeys` VALUES (73, 100, '_active_story');
INSERT INTO `LocalizationKeys` VALUES (74, 100, '_Add comment');
INSERT INTO `LocalizationKeys` VALUES (75, 100, '_Add to cart');
INSERT INTO `LocalizationKeys` VALUES (76, 100, '_Add New Profile');
INSERT INTO `LocalizationKeys` VALUES (77, 100, '_Add story');
INSERT INTO `LocalizationKeys` VALUES (78, 100, '_Add new object');
INSERT INTO `LocalizationKeys` VALUES (79, 100, '_add stories');
INSERT INTO `LocalizationKeys` VALUES (80, 100, '_Add to Hot List');
INSERT INTO `LocalizationKeys` VALUES (81, 100, '_Add to Friend List');
INSERT INTO `LocalizationKeys` VALUES (82, 100, '_Adding member...');
INSERT INTO `LocalizationKeys` VALUES (83, 100, '_Additional contact information');
INSERT INTO `LocalizationKeys` VALUES (84, 100, '_Admin');
INSERT INTO `LocalizationKeys` VALUES (85, 100, '_adv_search');
INSERT INTO `LocalizationKeys` VALUES (86, 100, '_Affiliates');
INSERT INTO `LocalizationKeys` VALUES (87, 100, '_Aged from');
INSERT INTO `LocalizationKeys` VALUES (88, 100, '_aged');
INSERT INTO `LocalizationKeys` VALUES (89, 100, '_all');
INSERT INTO `LocalizationKeys` VALUES (90, 100, '_All');
INSERT INTO `LocalizationKeys` VALUES (91, 100, '_Amount');
INSERT INTO `LocalizationKeys` VALUES (92, 100, '_Anonymous');
INSERT INTO `LocalizationKeys` VALUES (94, 9, '_April');
INSERT INTO `LocalizationKeys` VALUES (95, 100, '_Articles');
INSERT INTO `LocalizationKeys` VALUES (96, 100, '_ascending');
INSERT INTO `LocalizationKeys` VALUES (97, 100, '_Attention');
INSERT INTO `LocalizationKeys` VALUES (98, 9, '_August');
INSERT INTO `LocalizationKeys` VALUES (100, 100, '_available');
INSERT INTO `LocalizationKeys` VALUES (101, 100, '_average rate');
INSERT INTO `LocalizationKeys` VALUES (102, 100, '_avff');
INSERT INTO `LocalizationKeys` VALUES (103, 100, '_Back to');
INSERT INTO `LocalizationKeys` VALUES (104, 100, '_Back Invite');
INSERT INTO `LocalizationKeys` VALUES (105, 100, '_Bid');
INSERT INTO `LocalizationKeys` VALUES (106, 100, '_Block');
INSERT INTO `LocalizationKeys` VALUES (107, 100, '_Block list');
INSERT INTO `LocalizationKeys` VALUES (108, 100, '_block member');
INSERT INTO `LocalizationKeys` VALUES (109, 100, '_Blog');
INSERT INTO `LocalizationKeys` VALUES (110, 24, '_Body type');
INSERT INTO `LocalizationKeys` VALUES (111, 24, '_BodyType');
INSERT INTO `LocalizationKeys` VALUES (112, 24, '_BodyType2');
INSERT INTO `LocalizationKeys` VALUES (113, 100, '_Both');
INSERT INTO `LocalizationKeys` VALUES (114, 100, '_both2');
INSERT INTO `LocalizationKeys` VALUES (115, 100, '_Brief Profile');
INSERT INTO `LocalizationKeys` VALUES (116, 100, '_Browse Profiles');
INSERT INTO `LocalizationKeys` VALUES (117, 100, '_by age');
INSERT INTO `LocalizationKeys` VALUES (118, 100, '_by contact price');
INSERT INTO `LocalizationKeys` VALUES (119, 100, '_by rate');
INSERT INTO `LocalizationKeys` VALUES (120, 100, '_by times contacted');
INSERT INTO `LocalizationKeys` VALUES (121, 25, '_imerr membership_required');
INSERT INTO `LocalizationKeys` VALUES (122, 25, '_imerr to_n_active');
INSERT INTO `LocalizationKeys` VALUES (123, 25, '_imerr from_n_active');
INSERT INTO `LocalizationKeys` VALUES (124, 25, '_imerr blocked');
INSERT INTO `LocalizationKeys` VALUES (125, 100, '_characters');
INSERT INTO `LocalizationKeys` VALUES (126, 100, '_chars_to_chars');
INSERT INTO `LocalizationKeys` VALUES (127, 100, '_Chat');
INSERT INTO `LocalizationKeys` VALUES (129, 100, '_Chat Now');
INSERT INTO `LocalizationKeys` VALUES (130, 100, '_chat now');
INSERT INTO `LocalizationKeys` VALUES (131, 100, '_Chatting');
INSERT INTO `LocalizationKeys` VALUES (132, 100, '_chat window');
INSERT INTO `LocalizationKeys` VALUES (133, 100, '_Cancel password(s)');
INSERT INTO `LocalizationKeys` VALUES (134, 100, '_Cart');
INSERT INTO `LocalizationKeys` VALUES (135, 100, '_CartDiscount title');
INSERT INTO `LocalizationKeys` VALUES (136, 100, '_CartDiscount NumOfProf');
INSERT INTO `LocalizationKeys` VALUES (137, 100, '_CartDiscount Discount');
INSERT INTO `LocalizationKeys` VALUES (138, 100, '_CastVote');
INSERT INTO `LocalizationKeys` VALUES (139, 100, '_Check Out');
INSERT INTO `LocalizationKeys` VALUES (140, 100, '_Check all');
INSERT INTO `LocalizationKeys` VALUES (141, 24, '_Children');
INSERT INTO `LocalizationKeys` VALUES (142, 100, '_Choose file for upload');
INSERT INTO `LocalizationKeys` VALUES (143, 24, '_children');
INSERT INTO `LocalizationKeys` VALUES (144, 24, '_City');
INSERT INTO `LocalizationKeys` VALUES (145, 3, '_City reqired');
INSERT INTO `LocalizationKeys` VALUES (146, 100, '_CLICK_AGREE');
INSERT INTO `LocalizationKeys` VALUES (147, 100, '_Close');
INSERT INTO `LocalizationKeys` VALUES (148, 100, '_Communicator');
INSERT INTO `LocalizationKeys` VALUES (149, 100, '_Communication');
INSERT INTO `LocalizationKeys` VALUES (150, 100, '_Community Stats');
INSERT INTO `LocalizationKeys` VALUES (151, 100, '_Compose New Message');
INSERT INTO `LocalizationKeys` VALUES (152, 100, '_Contact Sales');
INSERT INTO `LocalizationKeys` VALUES (153, 100, '_contacts');
INSERT INTO `LocalizationKeys` VALUES (156, 100, '_contact');
INSERT INTO `LocalizationKeys` VALUES (157, 100, '_contact list');
INSERT INTO `LocalizationKeys` VALUES (158, 100, '_Confirm E-mail');
INSERT INTO `LocalizationKeys` VALUES (159, 100, '_Confirm');
INSERT INTO `LocalizationKeys` VALUES (160, 100, '_Confirm password');
INSERT INTO `LocalizationKeys` VALUES (161, 100, '_Confirm your e-mail');
INSERT INTO `LocalizationKeys` VALUES (162, 100, '_Confirm your password');
INSERT INTO `LocalizationKeys` VALUES (163, 100, '_Confirmation code');
INSERT INTO `LocalizationKeys` VALUES (164, 100, '_Confirmation e-mail');
INSERT INTO `LocalizationKeys` VALUES (165, 100, '_Congratulations');
INSERT INTO `LocalizationKeys` VALUES (166, 100, '_Contact');
INSERT INTO `LocalizationKeys` VALUES (168, 100, '_Contacts');
INSERT INTO `LocalizationKeys` VALUES (169, 100, '_Contacts purchased');
INSERT INTO `LocalizationKeys` VALUES (170, 100, '_Contact information');
INSERT INTO `LocalizationKeys` VALUES (171, 100, '_Contact information sent');
INSERT INTO `LocalizationKeys` VALUES (172, 100, '_Contact information not sent');
INSERT INTO `LocalizationKeys` VALUES (173, 100, '_Contact price');
INSERT INTO `LocalizationKeys` VALUES (174, 100, '_Contact details');
INSERT INTO `LocalizationKeys` VALUES (175, 100, '_Continue');
INSERT INTO `LocalizationKeys` VALUES (176, 100, '_Control Panel');
INSERT INTO `LocalizationKeys` VALUES (177, 24, '_Country');
INSERT INTO `LocalizationKeys` VALUES (178, 24, '_couple');
INSERT INTO `LocalizationKeys` VALUES (180, 100, '_clc');
INSERT INTO `LocalizationKeys` VALUES (181, 100, '_Current deposit');
INSERT INTO `LocalizationKeys` VALUES (182, 100, '_Current membership');
INSERT INTO `LocalizationKeys` VALUES (183, 100, '_Currently Online');
INSERT INTO `LocalizationKeys` VALUES (184, 100, '_Custom menu');
INSERT INTO `LocalizationKeys` VALUES (185, 100, '_Date');
INSERT INTO `LocalizationKeys` VALUES (186, 24, '_Date of birth');
INSERT INTO `LocalizationKeys` VALUES (187, 24, '_DateOfBirth');
INSERT INTO `LocalizationKeys` VALUES (188, 24, '_DateOfBirth2');
INSERT INTO `LocalizationKeys` VALUES (189, 9, '_December');
INSERT INTO `LocalizationKeys` VALUES (190, 100, '_Delete');
INSERT INTO `LocalizationKeys` VALUES (191, 100, '_Delete account');
INSERT INTO `LocalizationKeys` VALUES (192, 100, '_Delete from Friend List');
INSERT INTO `LocalizationKeys` VALUES (193, 100, '_Deleting member...');
INSERT INTO `LocalizationKeys` VALUES (194, 100, '_Details of the person you are looking for');
INSERT INTO `LocalizationKeys` VALUES (195, 100, '_descending');
INSERT INTO `LocalizationKeys` VALUES (196, 24, '_Description');
INSERT INTO `LocalizationKeys` VALUES (1730, 4, '_ACTION_LIMIT_REACHED');
INSERT INTO `LocalizationKeys` VALUES (199, 100, '_Do you really want to delete this entry?');
INSERT INTO `LocalizationKeys` VALUES (200, 100, '_Download and listen');
INSERT INTO `LocalizationKeys` VALUES (201, 100, '_Download and see');
INSERT INTO `LocalizationKeys` VALUES (202, 100, '_Doesnt matter');
INSERT INTO `LocalizationKeys` VALUES (203, 100, '_Drinking');
INSERT INTO `LocalizationKeys` VALUES (204, 100, '_Drinking?');
INSERT INTO `LocalizationKeys` VALUES (205, 24, '_Drinker');
INSERT INTO `LocalizationKeys` VALUES (206, 24, '_Drinker2');
INSERT INTO `LocalizationKeys` VALUES (207, 100, '_E-mail');
INSERT INTO `LocalizationKeys` VALUES (208, 100, '_E-mail required');
INSERT INTO `LocalizationKeys` VALUES (209, 100, '_E-mail valid required');
INSERT INTO `LocalizationKeys` VALUES (210, 100, '_E-mail sent');
INSERT INTO `LocalizationKeys` VALUES (211, 100, '_E-mail or ID');
INSERT INTO `LocalizationKeys` VALUES (212, 100, '_Email confirmation');
INSERT INTO `LocalizationKeys` VALUES (213, 100, '_Email confirmation Ex');
INSERT INTO `LocalizationKeys` VALUES (214, 100, '_Email was successfully sent');
INSERT INTO `LocalizationKeys` VALUES (215, 100, '_Email sent failed');
INSERT INTO `LocalizationKeys` VALUES (216, 24, '_Education');
INSERT INTO `LocalizationKeys` VALUES (217, 24, '_Education2');
INSERT INTO `LocalizationKeys` VALUES (218, 100, '_Edit Profile');
INSERT INTO `LocalizationKeys` VALUES (219, 100, '_edit profile');
INSERT INTO `LocalizationKeys` VALUES (220, 100, '_Edit');
INSERT INTO `LocalizationKeys` VALUES (221, 100, '_Empty');
INSERT INTO `LocalizationKeys` VALUES (222, 100, '_Empty Cart');
INSERT INTO `LocalizationKeys` VALUES (223, 100, '_Emptying cart...');
INSERT INTO `LocalizationKeys` VALUES (224, 100, '_Enter profile ID');
INSERT INTO `LocalizationKeys` VALUES (225, 100, '_Enter what you see:');
INSERT INTO `LocalizationKeys` VALUES (226, 100, '_Error');
INSERT INTO `LocalizationKeys` VALUES (227, 100, '_Error code');
INSERT INTO `LocalizationKeys` VALUES (228, 100, '_Error sending kiss');
INSERT INTO `LocalizationKeys` VALUES (229, 24, '_Ethnicity');
INSERT INTO `LocalizationKeys` VALUES (230, 24, '_Ethnicity2');
INSERT INTO `LocalizationKeys` VALUES (231, 100, '_Extended search');
INSERT INTO `LocalizationKeys` VALUES (232, 100, '_Explanation');
INSERT INTO `LocalizationKeys` VALUES (233, 100, '_F');
INSERT INTO `LocalizationKeys` VALUES (234, 100, '_FAQ');
INSERT INTO `LocalizationKeys` VALUES (235, 9, '_February');
INSERT INTO `LocalizationKeys` VALUES (236, 100, '_Female');
INSERT INTO `LocalizationKeys` VALUES (237, 100, '_Female_');
INSERT INTO `LocalizationKeys` VALUES (238, 100, '_Fetch');
INSERT INTO `LocalizationKeys` VALUES (239, 100, '_Find');
INSERT INTO `LocalizationKeys` VALUES (240, 100, '_Find!');
INSERT INTO `LocalizationKeys` VALUES (241, 100, '_Finance');
INSERT INTO `LocalizationKeys` VALUES (242, 100, '_First');
INSERT INTO `LocalizationKeys` VALUES (243, 100, '_Free e-mail');
INSERT INTO `LocalizationKeys` VALUES (244, 100, '_free_sign_up');
INSERT INTO `LocalizationKeys` VALUES (245, 100, '_Free sound');
INSERT INTO `LocalizationKeys` VALUES (246, 100, '_Friend email');
INSERT INTO `LocalizationKeys` VALUES (247, 100, '_Friends:');
INSERT INTO `LocalizationKeys` VALUES (248, 100, '_Friends');
INSERT INTO `LocalizationKeys` VALUES (249, 100, '_female');
INSERT INTO `LocalizationKeys` VALUES (250, 100, '_featured');
INSERT INTO `LocalizationKeys` VALUES (251, 100, '_featured members');
INSERT INTO `LocalizationKeys` VALUES (252, 100, '_featured profiles');
INSERT INTO `LocalizationKeys` VALUES (253, 100, '_for free');
INSERT INTO `LocalizationKeys` VALUES (254, 100, '_for');
INSERT INTO `LocalizationKeys` VALUES (256, 100, '_Forgot');
INSERT INTO `LocalizationKeys` VALUES (257, 100, '_Forgot?');
INSERT INTO `LocalizationKeys` VALUES (258, 100, '_Forgot password?');
INSERT INTO `LocalizationKeys` VALUES (259, 100, '_former USSR');
INSERT INTO `LocalizationKeys` VALUES (260, 100, '_Forum');
INSERT INTO `LocalizationKeys` VALUES (261, 100, '_From');
INSERT INTO `LocalizationKeys` VALUES (262, 100, '_From Primary');
INSERT INTO `LocalizationKeys` VALUES (263, 100, '_from');
INSERT INTO `LocalizationKeys` VALUES (264, 100, '_from zip/postal code');
INSERT INTO `LocalizationKeys` VALUES (265, 100, '_from ZIP');
INSERT INTO `LocalizationKeys` VALUES (266, 100, '_free');
INSERT INTO `LocalizationKeys` VALUES (267, 100, '_Gallery');
INSERT INTO `LocalizationKeys` VALUES (268, 100, '_my_gallery');
INSERT INTO `LocalizationKeys` VALUES (269, 100, '_General description');
INSERT INTO `LocalizationKeys` VALUES (270, 100, '_General self-description');
INSERT INTO `LocalizationKeys` VALUES (271, 100, '_Go');
INSERT INTO `LocalizationKeys` VALUES (272, 100, '_Affiliate Program');
INSERT INTO `LocalizationKeys` VALUES (273, 100, '_Congratulation');
INSERT INTO `LocalizationKeys` VALUES (274, 100, '_Got_members_part_1');
INSERT INTO `LocalizationKeys` VALUES (275, 100, '_Got_members_part_2');
INSERT INTO `LocalizationKeys` VALUES (276, 100, '_Need_more_members');
INSERT INTO `LocalizationKeys` VALUES (277, 100, '_Choose_membership');
INSERT INTO `LocalizationKeys` VALUES (278, 100, '_Got_new_membership_part_1');
INSERT INTO `LocalizationKeys` VALUES (279, 100, '_Got_new_membership_part_2');
INSERT INTO `LocalizationKeys` VALUES (280, 100, '_Got_new_membership_part_3');
INSERT INTO `LocalizationKeys` VALUES (281, 100, '_Gold Members');
INSERT INTO `LocalizationKeys` VALUES (282, 100, '_Gold Membership Subscriptions');
INSERT INTO `LocalizationKeys` VALUES (283, 100, '_grant password');
INSERT INTO `LocalizationKeys` VALUES (284, 100, '_granted');
INSERT INTO `LocalizationKeys` VALUES (285, 100, '_guestbook');
INSERT INTO `LocalizationKeys` VALUES (286, 24, '_GuestbookMode');
INSERT INTO `LocalizationKeys` VALUES (287, 24, '_GuestbookAccess');
INSERT INTO `LocalizationKeys` VALUES (288, 100, '_my_guestbook');
INSERT INTO `LocalizationKeys` VALUES (289, 100, '_blog');
INSERT INTO `LocalizationKeys` VALUES (290, 100, '_my_blog');
INSERT INTO `LocalizationKeys` VALUES (291, 100, '_no_info');
INSERT INTO `LocalizationKeys` VALUES (292, 100, '_Enable');
INSERT INTO `LocalizationKeys` VALUES (293, 100, '_Disable');
INSERT INTO `LocalizationKeys` VALUES (294, 100, '_Suspend');
INSERT INTO `LocalizationKeys` VALUES (295, 100, '_Registered only');
INSERT INTO `LocalizationKeys` VALUES (296, 100, '_Friends only');
INSERT INTO `LocalizationKeys` VALUES (297, 100, '_Add record');
INSERT INTO `LocalizationKeys` VALUES (298, 100, '_Visitor');
INSERT INTO `LocalizationKeys` VALUES (299, 100, '_Have N children');
INSERT INTO `LocalizationKeys` VALUES (300, 100, '_Have no children');
INSERT INTO `LocalizationKeys` VALUES (301, 24, '_Height');
INSERT INTO `LocalizationKeys` VALUES (302, 24, '_Height2');
INSERT INTO `LocalizationKeys` VALUES (303, 100, '_Header');
INSERT INTO `LocalizationKeys` VALUES (305, 24, '_Headline');
INSERT INTO `LocalizationKeys` VALUES (306, 100, '_Hide');
INSERT INTO `LocalizationKeys` VALUES (307, 100, '_Home');
INSERT INTO `LocalizationKeys` VALUES (308, 24, '_Home address');
INSERT INTO `LocalizationKeys` VALUES (309, 24, '_Homepage');
INSERT INTO `LocalizationKeys` VALUES (310, 100, '_Hot list');
INSERT INTO `LocalizationKeys` VALUES (311, 100, '_hot member');
INSERT INTO `LocalizationKeys` VALUES (312, 100, '_Friend list');
INSERT INTO `LocalizationKeys` VALUES (313, 100, '_friend member');
INSERT INTO `LocalizationKeys` VALUES (314, 100, '_HTML');
INSERT INTO `LocalizationKeys` VALUES (315, 100, '_I AGREE');
INSERT INTO `LocalizationKeys` VALUES (316, 100, '_I am');
INSERT INTO `LocalizationKeys` VALUES (317, 100, '_I am a');
INSERT INTO `LocalizationKeys` VALUES (318, 100, '_I seek a');
INSERT INTO `LocalizationKeys` VALUES (319, 100, '_I can receive');
INSERT INTO `LocalizationKeys` VALUES (320, 100, '_I look for a');
INSERT INTO `LocalizationKeys` VALUES (321, 100, '_I prefer not to say');
INSERT INTO `LocalizationKeys` VALUES (322, 100, '_I will tell you later');
INSERT INTO `LocalizationKeys` VALUES (323, 24, '_ICQ UIN');
INSERT INTO `LocalizationKeys` VALUES (324, 24, '_ICQ');
INSERT INTO `LocalizationKeys` VALUES (325, 24, '_icq');
INSERT INTO `LocalizationKeys` VALUES (326, 24, '_IM UIN');
INSERT INTO `LocalizationKeys` VALUES (327, 100, '_IM title');
INSERT INTO `LocalizationKeys` VALUES (328, 100, '_starts immediately');
INSERT INTO `LocalizationKeys` VALUES (329, 100, '_IM now');
INSERT INTO `LocalizationKeys` VALUES (330, 25, '_im_textInit');
INSERT INTO `LocalizationKeys` VALUES (331, 25, '_im_textNoCurrUser');
INSERT INTO `LocalizationKeys` VALUES (332, 25, '_im_textNoAcessA');
INSERT INTO `LocalizationKeys` VALUES (333, 25, '_im_textNoAcessG');
INSERT INTO `LocalizationKeys` VALUES (334, 25, '_im_textLogin');
INSERT INTO `LocalizationKeys` VALUES (335, 25, '_im_textSend');
INSERT INTO `LocalizationKeys` VALUES (336, 100, '_ID');
INSERT INTO `LocalizationKeys` VALUES (337, 100, '_Ideal match description');
INSERT INTO `LocalizationKeys` VALUES (338, 24, '_Income');
INSERT INTO `LocalizationKeys` VALUES (339, 24, '_Income2');
INSERT INTO `LocalizationKeys` VALUES (340, 100, '_Incorrect Email');
INSERT INTO `LocalizationKeys` VALUES (342, 100, '_Interest');
INSERT INTO `LocalizationKeys` VALUES (343, 100, '_Invalid ID');
INSERT INTO `LocalizationKeys` VALUES (344, 100, '_Invite a friend');
INSERT INTO `LocalizationKeys` VALUES (345, 9, '_January');
INSERT INTO `LocalizationKeys` VALUES (346, 100, '_Join');
INSERT INTO `LocalizationKeys` VALUES (347, 100, '_Join For Free');
INSERT INTO `LocalizationKeys` VALUES (348, 100, '_Join Free');
INSERT INTO `LocalizationKeys` VALUES (349, 100, '_Join now');
INSERT INTO `LocalizationKeys` VALUES (350, 9, '_June');
INSERT INTO `LocalizationKeys` VALUES (351, 9, '_July');
INSERT INTO `LocalizationKeys` VALUES (352, 100, '_kilometers');
INSERT INTO `LocalizationKeys` VALUES (353, 100, '_kb');
INSERT INTO `LocalizationKeys` VALUES (354, 100, '_Kisses');
INSERT INTO `LocalizationKeys` VALUES (355, 100, '_Language');
INSERT INTO `LocalizationKeys` VALUES (356, 24, '_Language 1');
INSERT INTO `LocalizationKeys` VALUES (357, 24, '_Language 2');
INSERT INTO `LocalizationKeys` VALUES (358, 24, '_Language 3');
INSERT INTO `LocalizationKeys` VALUES (359, 24, '_Language1');
INSERT INTO `LocalizationKeys` VALUES (360, 24, '_Language12');
INSERT INTO `LocalizationKeys` VALUES (361, 100, '_Last');
INSERT INTO `LocalizationKeys` VALUES (362, 100, '_Last login');
INSERT INTO `LocalizationKeys` VALUES (363, 100, '_Last logged in');
INSERT INTO `LocalizationKeys` VALUES (364, 100, '_Last changes');
INSERT INTO `LocalizationKeys` VALUES (365, 100, '_Last modified');
INSERT INTO `LocalizationKeys` VALUES (366, 100, '_latest news');
INSERT INTO `LocalizationKeys` VALUES (367, 100, '_Latest Members');
INSERT INTO `LocalizationKeys` VALUES (368, 100, '_launch IM');
INSERT INTO `LocalizationKeys` VALUES (369, 100, '_Links');
INSERT INTO `LocalizationKeys` VALUES (370, 24, '_living with me');
INSERT INTO `LocalizationKeys` VALUES (371, 100, '_living within');
INSERT INTO `LocalizationKeys` VALUES (372, 100, '_listen voice');
INSERT INTO `LocalizationKeys` VALUES (373, 100, '_length');
INSERT INTO `LocalizationKeys` VALUES (374, 100, '_Location');
INSERT INTO `LocalizationKeys` VALUES (375, 100, '_Log In');
INSERT INTO `LocalizationKeys` VALUES (376, 100, '_Login');
INSERT INTO `LocalizationKeys` VALUES (377, 100, '_LOG_IN_1');
INSERT INTO `LocalizationKeys` VALUES (378, 100, '_Log Out');
INSERT INTO `LocalizationKeys` VALUES (379, 100, '_Log Out2');
INSERT INTO `LocalizationKeys` VALUES (380, 100, '_Logged in');
INSERT INTO `LocalizationKeys` VALUES (381, 100, '_Login required');
INSERT INTO `LocalizationKeys` VALUES (382, 100, '_login_title');
INSERT INTO `LocalizationKeys` VALUES (384, 100, '_LookingFor');
INSERT INTO `LocalizationKeys` VALUES (385, 100, '_LookingAge');
INSERT INTO `LocalizationKeys` VALUES (386, 100, '_LookingHeight');
INSERT INTO `LocalizationKeys` VALUES (387, 100, '_LookingBodyType');
INSERT INTO `LocalizationKeys` VALUES (388, 100, '_Looking for');
INSERT INTO `LocalizationKeys` VALUES (389, 100, '_looking for');
INSERT INTO `LocalizationKeys` VALUES (390, 100, '_M');
INSERT INTO `LocalizationKeys` VALUES (391, 100, '_Must be valid');
INSERT INTO `LocalizationKeys` VALUES (392, 100, '_MSN');
INSERT INTO `LocalizationKeys` VALUES (393, 100, '_msn');
INSERT INTO `LocalizationKeys` VALUES (394, 100, '_MAIN MENU');
INSERT INTO `LocalizationKeys` VALUES (395, 100, '_Main Menu');
INSERT INTO `LocalizationKeys` VALUES (1729, 4, '_ACTION_NOT_ACTIVE');
INSERT INTO `LocalizationKeys` VALUES (397, 100, '_Make thumb out of primary');
INSERT INTO `LocalizationKeys` VALUES (398, 100, '_Make Failed thumb out of primary');
INSERT INTO `LocalizationKeys` VALUES (399, 100, '_Make Success thumb out of primary');
INSERT INTO `LocalizationKeys` VALUES (400, 100, '_Male');
INSERT INTO `LocalizationKeys` VALUES (401, 100, '_Male_');
INSERT INTO `LocalizationKeys` VALUES (402, 100, '_Male or female');
INSERT INTO `LocalizationKeys` VALUES (403, 100, '_Male or female_');
INSERT INTO `LocalizationKeys` VALUES (404, 9, '_March');
INSERT INTO `LocalizationKeys` VALUES (405, 100, '_man');
INSERT INTO `LocalizationKeys` VALUES (406, 100, '_Men');
INSERT INTO `LocalizationKeys` VALUES (407, 100, '_men');
INSERT INTO `LocalizationKeys` VALUES (408, 100, '_men and women');
INSERT INTO `LocalizationKeys` VALUES (409, 100, '_man_');
INSERT INTO `LocalizationKeys` VALUES (410, 100, '_Manage objects');
INSERT INTO `LocalizationKeys` VALUES (411, 100, '_Manage alboms');
INSERT INTO `LocalizationKeys` VALUES (413, 100, '_male');
INSERT INTO `LocalizationKeys` VALUES (414, 24, '_Marital status');
INSERT INTO `LocalizationKeys` VALUES (415, 24, '_MaritalStatus');
INSERT INTO `LocalizationKeys` VALUES (416, 100, '_Mark as New');
INSERT INTO `LocalizationKeys` VALUES (417, 100, '_Mark as old');
INSERT INTO `LocalizationKeys` VALUES (418, 100, '_Mark as Featured');
INSERT INTO `LocalizationKeys` VALUES (419, 100, '_Maximum characters');
INSERT INTO `LocalizationKeys` VALUES (420, 9, '_May');
INSERT INTO `LocalizationKeys` VALUES (421, 100, '_Maybe');
INSERT INTO `LocalizationKeys` VALUES (422, 100, '_MEMBER MENU');
INSERT INTO `LocalizationKeys` VALUES (423, 100, '_Member');
INSERT INTO `LocalizationKeys` VALUES (424, 100, '_Member control panel');
INSERT INTO `LocalizationKeys` VALUES (425, 100, '_Member Information');
INSERT INTO `LocalizationKeys` VALUES (426, 100, '_Member Login');
INSERT INTO `LocalizationKeys` VALUES (427, 100, '_Member menu');
INSERT INTO `LocalizationKeys` VALUES (428, 100, '_Member Profile');
INSERT INTO `LocalizationKeys` VALUES (429, 100, '_Member Profile NA for view');
INSERT INTO `LocalizationKeys` VALUES (430, 100, '_Member sound');
INSERT INTO `LocalizationKeys` VALUES (431, 100, '_Member video');
INSERT INTO `LocalizationKeys` VALUES (432, 100, '_members');
INSERT INTO `LocalizationKeys` VALUES (433, 100, '_members Hot');
INSERT INTO `LocalizationKeys` VALUES (434, 100, '_members Friend');
INSERT INTO `LocalizationKeys` VALUES (435, 100, '_members Block');
INSERT INTO `LocalizationKeys` VALUES (436, 100, '_members Kissed');
INSERT INTO `LocalizationKeys` VALUES (437, 100, '_members Viewed');
INSERT INTO `LocalizationKeys` VALUES (438, 100, '_members Contacted');
INSERT INTO `LocalizationKeys` VALUES (439, 100, '_members Contacted for free');
INSERT INTO `LocalizationKeys` VALUES (440, 100, '_members Private_photos_access');
INSERT INTO `LocalizationKeys` VALUES (441, 100, '_member info');
INSERT INTO `LocalizationKeys` VALUES (442, 100, '_Membership');
INSERT INTO `LocalizationKeys` VALUES (443, 100, '_membership');
INSERT INTO `LocalizationKeys` VALUES (444, 100, '_Membership2');
INSERT INTO `LocalizationKeys` VALUES (448, 100, '_COMPOSE_REJECT_MEMBER_NOT_FOUND');
INSERT INTO `LocalizationKeys` VALUES (449, 100, '_Membership NEW');
INSERT INTO `LocalizationKeys` VALUES (450, 100, '_days');
INSERT INTO `LocalizationKeys` VALUES (452, 100, '_Membership Credits');
INSERT INTO `LocalizationKeys` VALUES (453, 100, '_Membership Status');
INSERT INTO `LocalizationKeys` VALUES (454, 100, '_Message from');
INSERT INTO `LocalizationKeys` VALUES (455, 100, '_Message not available');
INSERT INTO `LocalizationKeys` VALUES (456, 100, '_Message Preview');
INSERT INTO `LocalizationKeys` VALUES (457, 100, '_Message text');
INSERT INTO `LocalizationKeys` VALUES (458, 100, '_Messages');
INSERT INTO `LocalizationKeys` VALUES (459, 100, '_Messages in Inbox');
INSERT INTO `LocalizationKeys` VALUES (460, 100, '_Messages in Outbox');
INSERT INTO `LocalizationKeys` VALUES (461, 100, '_miles');
INSERT INTO `LocalizationKeys` VALUES (462, 100, '_km');
INSERT INTO `LocalizationKeys` VALUES (463, 100, '_Moderator');
INSERT INTO `LocalizationKeys` VALUES (464, 100, '_More Photos');
INSERT INTO `LocalizationKeys` VALUES (465, 100, '_More Photos2');
INSERT INTO `LocalizationKeys` VALUES (466, 100, '_more');
INSERT INTO `LocalizationKeys` VALUES (467, 100, '_More..');
INSERT INTO `LocalizationKeys` VALUES (468, 100, '_My Email');
INSERT INTO `LocalizationKeys` VALUES (469, 100, '_My Inbox');
INSERT INTO `LocalizationKeys` VALUES (470, 100, '_My Outbox');
INSERT INTO `LocalizationKeys` VALUES (471, 100, '_My Membership');
INSERT INTO `LocalizationKeys` VALUES (472, 100, '_My Messenger');
INSERT INTO `LocalizationKeys` VALUES (473, 100, '_My Panel');
INSERT INTO `LocalizationKeys` VALUES (474, 100, '_My Photos');
INSERT INTO `LocalizationKeys` VALUES (475, 100, '_My Profile');
INSERT INTO `LocalizationKeys` VALUES (476, 100, '_NA_image');
INSERT INTO `LocalizationKeys` VALUES (477, 100, '_Name');
INSERT INTO `LocalizationKeys` VALUES (478, 100, '_never');
INSERT INTO `LocalizationKeys` VALUES (479, 100, '_new');
INSERT INTO `LocalizationKeys` VALUES (480, 100, '_New Message');
INSERT INTO `LocalizationKeys` VALUES (481, 100, '_New Member');
INSERT INTO `LocalizationKeys` VALUES (482, 100, '_New Member Add Here');
INSERT INTO `LocalizationKeys` VALUES (483, 100, '_New profile created');
INSERT INTO `LocalizationKeys` VALUES (484, 100, '_New picture');
INSERT INTO `LocalizationKeys` VALUES (485, 100, '_New sound');
INSERT INTO `LocalizationKeys` VALUES (486, 100, '_New video');
INSERT INTO `LocalizationKeys` VALUES (487, 100, '_New this week');
INSERT INTO `LocalizationKeys` VALUES (488, 100, '_newsletter');
INSERT INTO `LocalizationKeys` VALUES (489, 100, '_Next');
INSERT INTO `LocalizationKeys` VALUES (490, 100, '_Next>>');
INSERT INTO `LocalizationKeys` VALUES (491, 100, '_news_archive');
INSERT INTO `LocalizationKeys` VALUES (492, 100, '_NickName''s profile');
INSERT INTO `LocalizationKeys` VALUES (493, 24, '_NickName');
INSERT INTO `LocalizationKeys` VALUES (494, 24, '_Nickname');
INSERT INTO `LocalizationKeys` VALUES (495, 24, '_ProfileType');
INSERT INTO `LocalizationKeys` VALUES (496, 100, '_Nickname required');
INSERT INTO `LocalizationKeys` VALUES (497, 100, '_No');
INSERT INTO `LocalizationKeys` VALUES (498, 100, '_No matters');
INSERT INTO `LocalizationKeys` VALUES (499, 100, '_No member to add');
INSERT INTO `LocalizationKeys` VALUES (500, 100, '_No member to delete');
INSERT INTO `LocalizationKeys` VALUES (501, 100, '_No member specified');
INSERT INTO `LocalizationKeys` VALUES (502, 100, '_No membership available');
INSERT INTO `LocalizationKeys` VALUES (503, 100, '_No messages in Inbox');
INSERT INTO `LocalizationKeys` VALUES (504, 100, '_No messages in Outbox');
INSERT INTO `LocalizationKeys` VALUES (505, 100, '_No modification');
INSERT INTO `LocalizationKeys` VALUES (506, 100, '_No news available');
INSERT INTO `LocalizationKeys` VALUES (507, 100, '_No polls available');
INSERT INTO `LocalizationKeys` VALUES (508, 100, '_No pics available');
INSERT INTO `LocalizationKeys` VALUES (509, 100, '_No results found');
INSERT INTO `LocalizationKeys` VALUES (510, 100, '_No sounds available');
INSERT INTO `LocalizationKeys` VALUES (511, 100, '_No success story available.');
INSERT INTO `LocalizationKeys` VALUES (512, 100, '_no such name or no message');
INSERT INTO `LocalizationKeys` VALUES (513, 100, '_No video available');
INSERT INTO `LocalizationKeys` VALUES (514, 100, '_None');
INSERT INTO `LocalizationKeys` VALUES (515, 100, '_none');
INSERT INTO `LocalizationKeys` VALUES (517, 100, '_not living with me');
INSERT INTO `LocalizationKeys` VALUES (518, 100, '_not granted');
INSERT INTO `LocalizationKeys` VALUES (519, 100, '_Not read');
INSERT INTO `LocalizationKeys` VALUES (520, 100, '_not read');
INSERT INTO `LocalizationKeys` VALUES (521, 100, '_Not Recognized');
INSERT INTO `LocalizationKeys` VALUES (522, 100, '_Not sure');
INSERT INTO `LocalizationKeys` VALUES (523, 24, '_NotNotifyMe');
INSERT INTO `LocalizationKeys` VALUES (524, 100, '_Notification send failed');
INSERT INTO `LocalizationKeys` VALUES (525, 100, '_Notify me about news, tips');
INSERT INTO `LocalizationKeys` VALUES (526, 24, '_NotifyMe');
INSERT INTO `LocalizationKeys` VALUES (527, 24, '_Notify by e-mail');
INSERT INTO `LocalizationKeys` VALUES (528, 9, '_November');
INSERT INTO `LocalizationKeys` VALUES (529, 24, '_Occupation');
INSERT INTO `LocalizationKeys` VALUES (530, 9, '_October');
INSERT INTO `LocalizationKeys` VALUES (532, 100, '_only');
INSERT INTO `LocalizationKeys` VALUES (533, 100, '_Online');
INSERT INTO `LocalizationKeys` VALUES (534, 100, '_Online Members');
INSERT INTO `LocalizationKeys` VALUES (535, 100, '_online only');
INSERT INTO `LocalizationKeys` VALUES (536, 100, '_Offline');
INSERT INTO `LocalizationKeys` VALUES (537, 3, '_Ok, entry not deleted.');
INSERT INTO `LocalizationKeys` VALUES (538, 3, '_Ok, entry was deleted successful.');
INSERT INTO `LocalizationKeys` VALUES (539, 3, '_Oops, cannot delete this entry.');
INSERT INTO `LocalizationKeys` VALUES (540, 100, '_Open in new window');
INSERT INTO `LocalizationKeys` VALUES (541, 100, '_or send request for password');
INSERT INTO `LocalizationKeys` VALUES (542, 100, '_Other details');
INSERT INTO `LocalizationKeys` VALUES (543, 100, '_page navigation');
INSERT INTO `LocalizationKeys` VALUES (544, 100, '_Pages');
INSERT INTO `LocalizationKeys` VALUES (545, 24, '_Password');
INSERT INTO `LocalizationKeys` VALUES (546, 100, '_pass_adl');
INSERT INTO `LocalizationKeys` VALUES (547, 100, '_Password granted');
INSERT INTO `LocalizationKeys` VALUES (548, 100, '_Password required');
INSERT INTO `LocalizationKeys` VALUES (549, 100, '_Password retrieval');
INSERT INTO `LocalizationKeys` VALUES (550, 100, '_Pay-per-contact');
INSERT INTO `LocalizationKeys` VALUES (551, 24, '_Personal details');
INSERT INTO `LocalizationKeys` VALUES (552, 24, '_Personal details2');
INSERT INTO `LocalizationKeys` VALUES (553, 24, '_Phone');
INSERT INTO `LocalizationKeys` VALUES (554, 3, '_Photo successfully deleted');
INSERT INTO `LocalizationKeys` VALUES (555, 100, '_pics_only');
INSERT INTO `LocalizationKeys` VALUES (556, 100, '_Picture');
INSERT INTO `LocalizationKeys` VALUES (557, 100, '_Polls');
INSERT INTO `LocalizationKeys` VALUES (558, 100, '_post my feedback');
INSERT INTO `LocalizationKeys` VALUES (559, 24, '_PPNotify');
INSERT INTO `LocalizationKeys` VALUES (560, 24, '_PPNotifyNote');
INSERT INTO `LocalizationKeys` VALUES (561, 100, '_Prev');
INSERT INTO `LocalizationKeys` VALUES (562, 100, '_Preview');
INSERT INTO `LocalizationKeys` VALUES (564, 100, '_Price,');
INSERT INTO `LocalizationKeys` VALUES (565, 100, '_Primary Picture');
INSERT INTO `LocalizationKeys` VALUES (566, 100, '_Primary photo successfully deleted');
INSERT INTO `LocalizationKeys` VALUES (567, 100, '_Primary photo remove failed');
INSERT INTO `LocalizationKeys` VALUES (568, 100, '_Private message');
INSERT INTO `LocalizationKeys` VALUES (569, 100, '_Private photo');
INSERT INTO `LocalizationKeys` VALUES (570, 100, '_Privacy');
INSERT INTO `LocalizationKeys` VALUES (571, 100, '_Private photo password');
INSERT INTO `LocalizationKeys` VALUES (572, 100, '_Private passwod');
INSERT INTO `LocalizationKeys` VALUES (573, 100, '_Profile last modified');
INSERT INTO `LocalizationKeys` VALUES (574, 100, '_Profile status');
INSERT INTO `LocalizationKeys` VALUES (575, 100, '_Profile NA');
INSERT INTO `LocalizationKeys` VALUES (576, 100, '_Profile Not found');
INSERT INTO `LocalizationKeys` VALUES (577, 100, '_Profile Not found Ex');
INSERT INTO `LocalizationKeys` VALUES (578, 100, '_Profiles');
INSERT INTO `LocalizationKeys` VALUES (579, 100, '_Profile');
INSERT INTO `LocalizationKeys` VALUES (580, 100, '_Profile activation failed');
INSERT INTO `LocalizationKeys` VALUES (581, 100, '_Profile of the week');
INSERT INTO `LocalizationKeys` VALUES (582, 100, '_Profile of the month');
INSERT INTO `LocalizationKeys` VALUES (583, 100, '_Purchased contacts');
INSERT INTO `LocalizationKeys` VALUES (584, 100, '_private');
INSERT INTO `LocalizationKeys` VALUES (585, 100, '_public');
INSERT INTO `LocalizationKeys` VALUES (586, 100, '_friends only');
INSERT INTO `LocalizationKeys` VALUES (587, 100, '_random profiles');
INSERT INTO `LocalizationKeys` VALUES (588, 100, '_Random Members');
INSERT INTO `LocalizationKeys` VALUES (589, 100, '_rate');
INSERT INTO `LocalizationKeys` VALUES (590, 100, '_rate profile');
INSERT INTO `LocalizationKeys` VALUES (591, 100, '_rate photo');
INSERT INTO `LocalizationKeys` VALUES (592, 100, '_Rate Photo');
INSERT INTO `LocalizationKeys` VALUES (593, 100, '_Read access:');
INSERT INTO `LocalizationKeys` VALUES (594, 100, '_Read more');
INSERT INTO `LocalizationKeys` VALUES (595, 100, '_Readed');
INSERT INTO `LocalizationKeys` VALUES (596, 100, '_Read news in archive');
INSERT INTO `LocalizationKeys` VALUES (597, 24, '_Real name');
INSERT INTO `LocalizationKeys` VALUES (598, 3, '_Real name required');
INSERT INTO `LocalizationKeys` VALUES (599, 100, '_Recognized');
INSERT INTO `LocalizationKeys` VALUES (600, 100, '_Registration error');
INSERT INTO `LocalizationKeys` VALUES (601, 100, '_Reject invite');
INSERT INTO `LocalizationKeys` VALUES (602, 100, '_Reject Invite');
INSERT INTO `LocalizationKeys` VALUES (603, 24, '_Relationship');
INSERT INTO `LocalizationKeys` VALUES (604, 3, '_Relationship required');
INSERT INTO `LocalizationKeys` VALUES (605, 24, '_Religion');
INSERT INTO `LocalizationKeys` VALUES (606, 24, '_Religion2');
INSERT INTO `LocalizationKeys` VALUES (607, 100, '_Reply');
INSERT INTO `LocalizationKeys` VALUES (608, 100, '_Report about spam was sent');
INSERT INTO `LocalizationKeys` VALUES (609, 100, '_Report about spam failed to sent');
INSERT INTO `LocalizationKeys` VALUES (610, 100, '_report member');
INSERT INTO `LocalizationKeys` VALUES (611, 100, '_Results per page');
INSERT INTO `LocalizationKeys` VALUES (612, 100, '_Results');
INSERT INTO `LocalizationKeys` VALUES (613, 100, '_Retrieve');
INSERT INTO `LocalizationKeys` VALUES (614, 100, '_Retrieve my information');
INSERT INTO `LocalizationKeys` VALUES (615, 100, '_Quick Links');
INSERT INTO `LocalizationKeys` VALUES (616, 100, '_Quick Search');
INSERT INTO `LocalizationKeys` VALUES (617, 100, '_Save Changes');
INSERT INTO `LocalizationKeys` VALUES (618, 100, '_Services');
INSERT INTO `LocalizationKeys` VALUES (619, 100, '_services');
INSERT INTO `LocalizationKeys` VALUES (620, 100, '_sec');
INSERT INTO `LocalizationKeys` VALUES (621, 100, '_send a kiss');
INSERT INTO `LocalizationKeys` VALUES (622, 100, '_email to frend');
INSERT INTO `LocalizationKeys` VALUES (623, 100, '_score');
INSERT INTO `LocalizationKeys` VALUES (624, 100, '_size');
INSERT INTO `LocalizationKeys` VALUES (625, 100, '_single');
INSERT INTO `LocalizationKeys` VALUES (626, 100, '_Sign Up FREE');
INSERT INTO `LocalizationKeys` VALUES (627, 100, '_SIMG_ERR');
INSERT INTO `LocalizationKeys` VALUES (628, 100, '_Search');
INSERT INTO `LocalizationKeys` VALUES (629, 100, '_Search result');
INSERT INTO `LocalizationKeys` VALUES (630, 100, '_Search by ID');
INSERT INTO `LocalizationKeys` VALUES (631, 100, '_Search by Nickname');
INSERT INTO `LocalizationKeys` VALUES (632, 100, '_Search by distance');
INSERT INTO `LocalizationKeys` VALUES (633, 100, '_Search Profiles');
INSERT INTO `LocalizationKeys` VALUES (634, 100, '_Search profiles');
INSERT INTO `LocalizationKeys` VALUES (635, 100, '_Secondary Picture');
INSERT INTO `LocalizationKeys` VALUES (636, 100, '_Secondary photo successfully deleted');
INSERT INTO `LocalizationKeys` VALUES (637, 100, '_Secondary photo remove failed');
INSERT INTO `LocalizationKeys` VALUES (638, 100, '_See profile');
INSERT INTO `LocalizationKeys` VALUES (639, 100, '_See PERSON''s Profile');
INSERT INTO `LocalizationKeys` VALUES (640, 100, '_seeking a');
INSERT INTO `LocalizationKeys` VALUES (641, 100, '_Seeking for a');
INSERT INTO `LocalizationKeys` VALUES (642, 100, '_search_Sex');
INSERT INTO `LocalizationKeys` VALUES (643, 100, '_search profiles');
INSERT INTO `LocalizationKeys` VALUES (644, 100, '_search_Country');
INSERT INTO `LocalizationKeys` VALUES (645, 100, '_search_DateOfBirth');
INSERT INTO `LocalizationKeys` VALUES (646, 100, '_search_Height');
INSERT INTO `LocalizationKeys` VALUES (647, 100, '_search_BodyType');
INSERT INTO `LocalizationKeys` VALUES (648, 100, '_search_Religion');
INSERT INTO `LocalizationKeys` VALUES (649, 100, '_search_Ethnicity');
INSERT INTO `LocalizationKeys` VALUES (650, 100, '_search_MaritalStatus');
INSERT INTO `LocalizationKeys` VALUES (651, 100, '_search_Education');
INSERT INTO `LocalizationKeys` VALUES (652, 100, '_search_Income');
INSERT INTO `LocalizationKeys` VALUES (653, 100, '_search_Smoker');
INSERT INTO `LocalizationKeys` VALUES (654, 100, '_search_Drinker');
INSERT INTO `LocalizationKeys` VALUES (655, 100, '_search_LookingFor');
INSERT INTO `LocalizationKeys` VALUES (656, 100, '_search_Language1');
INSERT INTO `LocalizationKeys` VALUES (657, 100, '_Selected messages');
INSERT INTO `LocalizationKeys` VALUES (658, 100, '_Send');
INSERT INTO `LocalizationKeys` VALUES (659, 100, '_Send a message');
INSERT INTO `LocalizationKeys` VALUES (660, 100, '_Send a message to');
INSERT INTO `LocalizationKeys` VALUES (661, 100, '_Send e-mail');
INSERT INTO `LocalizationKeys` VALUES (662, 100, '_Send kiss');
INSERT INTO `LocalizationKeys` VALUES (663, 100, '_Send Kiss');
INSERT INTO `LocalizationKeys` VALUES (664, 100, '_Send Kiss cannt');
INSERT INTO `LocalizationKeys` VALUES (665, 100, '_Send to communicator');
INSERT INTO `LocalizationKeys` VALUES (666, 100, '_Send to e-mail');
INSERT INTO `LocalizationKeys` VALUES (667, 100, '_Send virtual kiss');
INSERT INTO `LocalizationKeys` VALUES (668, 100, '_Send virtual kiss2');
INSERT INTO `LocalizationKeys` VALUES (669, 100, '_Send virtual kiss3');
INSERT INTO `LocalizationKeys` VALUES (670, 100, '_Send Letter');
INSERT INTO `LocalizationKeys` VALUES (671, 9, '_September');
INSERT INTO `LocalizationKeys` VALUES (672, 100, '_Settings');
INSERT INTO `LocalizationKeys` VALUES (673, 100, '_Set membership');
INSERT INTO `LocalizationKeys` VALUES (674, 24, '_Sex');
INSERT INTO `LocalizationKeys` VALUES (675, 100, '_Shopping Cart');
INSERT INTO `LocalizationKeys` VALUES (676, 100, '_Shopping cart emptied');
INSERT INTO `LocalizationKeys` VALUES (677, 100, '_Short Profiles Search');
INSERT INTO `LocalizationKeys` VALUES (678, 100, '_shout_box_title');
INSERT INTO `LocalizationKeys` VALUES (679, 100, '_Show');
INSERT INTO `LocalizationKeys` VALUES (680, 100, '_Show me');
INSERT INTO `LocalizationKeys` VALUES (683, 24, '_Smoker');
INSERT INTO `LocalizationKeys` VALUES (684, 24, '_Smoker2');
INSERT INTO `LocalizationKeys` VALUES (685, 100, '_sometimes living with me');
INSERT INTO `LocalizationKeys` VALUES (686, 100, '_Sorry');
INSERT INTO `LocalizationKeys` VALUES (687, 100, '_sorry, i can not define you ip adress. IT''S TIME TO COME OUT !');
INSERT INTO `LocalizationKeys` VALUES (688, 100, '_Sorry, user is OFFLINE');
INSERT INTO `LocalizationKeys` VALUES (689, 100, '_sort');
INSERT INTO `LocalizationKeys` VALUES (690, 100, '_Sort order');
INSERT INTO `LocalizationKeys` VALUES (691, 100, '_Sort results');
INSERT INTO `LocalizationKeys` VALUES (692, 100, '_Sound');
INSERT INTO `LocalizationKeys` VALUES (693, 100, '_Spam report');
INSERT INTO `LocalizationKeys` VALUES (694, 100, '_spam member');
INSERT INTO `LocalizationKeys` VALUES (695, 100, '_Special offer');
INSERT INTO `LocalizationKeys` VALUES (696, 100, '_Spoken languages');
INSERT INTO `LocalizationKeys` VALUES (697, 100, '_speak');
INSERT INTO `LocalizationKeys` VALUES (698, 100, '_Status');
INSERT INTO `LocalizationKeys` VALUES (699, 100, '_Stories');
INSERT INTO `LocalizationKeys` VALUES (700, 100, '_Stories2');
INSERT INTO `LocalizationKeys` VALUES (701, 100, '_Submit');
INSERT INTO `LocalizationKeys` VALUES (702, 100, '_Submit request');
INSERT INTO `LocalizationKeys` VALUES (703, 100, '_Subscribe');
INSERT INTO `LocalizationKeys` VALUES (704, 100, '_Subject');
INSERT INTO `LocalizationKeys` VALUES (705, 100, '_Successfully uploaded');
INSERT INTO `LocalizationKeys` VALUES (706, 100, '_success story');
INSERT INTO `LocalizationKeys` VALUES (707, 100, '_Suspend account');
INSERT INTO `LocalizationKeys` VALUES (708, 100, '_survey');
INSERT INTO `LocalizationKeys` VALUES (709, 100, '_Text');
INSERT INTO `LocalizationKeys` VALUES (710, 100, '_Terms_of_use');
INSERT INTO `LocalizationKeys` VALUES (711, 100, '_Tell a friend');
INSERT INTO `LocalizationKeys` VALUES (712, 100, '_Theme');
INSERT INTO `LocalizationKeys` VALUES (713, 3, '_This guestbook disabled by it''s owner');
INSERT INTO `LocalizationKeys` VALUES (714, 3, '_This guestbook allowed for registered members only');
INSERT INTO `LocalizationKeys` VALUES (715, 3, '_This guestbook allowed for friends only');
INSERT INTO `LocalizationKeys` VALUES (716, 100, '_You can not write any messages, this guestbook is suspended');
INSERT INTO `LocalizationKeys` VALUES (717, 100, '_Thumbnail');
INSERT INTO `LocalizationKeys` VALUES (718, 100, '_Thumbnail successfully deleted');
INSERT INTO `LocalizationKeys` VALUES (719, 100, '_Thumbnail remove failed');
INSERT INTO `LocalizationKeys` VALUES (720, 100, '_Thumb');
INSERT INTO `LocalizationKeys` VALUES (721, 100, '_Timely');
INSERT INTO `LocalizationKeys` VALUES (722, 100, '_time(s)');
INSERT INTO `LocalizationKeys` VALUES (723, 100, '_to');
INSERT INTO `LocalizationKeys` VALUES (724, 100, '_To');
INSERT INTO `LocalizationKeys` VALUES (725, 100, '_to_post');
INSERT INTO `LocalizationKeys` VALUES (726, 100, '_To view the photos you have to become a gold member. Go to <a href="membership.php" target="_blank">Gold Membership page</a> to purchase membership.');
INSERT INTO `LocalizationKeys` VALUES (727, 100, '_Top Rated');
INSERT INTO `LocalizationKeys` VALUES (728, 100, '_Top Members');
INSERT INTO `LocalizationKeys` VALUES (729, 100, '_Total');
INSERT INTO `LocalizationKeys` VALUES (730, 100, '_total');
INSERT INTO `LocalizationKeys` VALUES (731, 100, '_Total amount');
INSERT INTO `LocalizationKeys` VALUES (732, 100, '_Total price');
INSERT INTO `LocalizationKeys` VALUES (733, 100, '_Total Registered');
INSERT INTO `LocalizationKeys` VALUES (734, 100, '_total votes');
INSERT INTO `LocalizationKeys` VALUES (735, 100, '_Uncheck all');
INSERT INTO `LocalizationKeys` VALUES (736, 100, '_Unblock');
INSERT INTO `LocalizationKeys` VALUES (737, 100, '_Undefined error');
INSERT INTO `LocalizationKeys` VALUES (738, 100, '_mem_status');
INSERT INTO `LocalizationKeys` VALUES (739, 100, '__Silver');
INSERT INTO `LocalizationKeys` VALUES (740, 100, '__Gold');
INSERT INTO `LocalizationKeys` VALUES (741, 100, '__Platinum');
INSERT INTO `LocalizationKeys` VALUES (742, 100, '__Standard');
INSERT INTO `LocalizationKeys` VALUES (743, 100, '__silver');
INSERT INTO `LocalizationKeys` VALUES (744, 100, '__standard');
INSERT INTO `LocalizationKeys` VALUES (745, 100, '_Unknown action');
INSERT INTO `LocalizationKeys` VALUES (746, 100, '_uknown');
INSERT INTO `LocalizationKeys` VALUES (747, 100, '_Unregister');
INSERT INTO `LocalizationKeys` VALUES (748, 100, '_Upload');
INSERT INTO `LocalizationKeys` VALUES (749, 100, '_Upload Photos');
INSERT INTO `LocalizationKeys` VALUES (750, 100, '_Upload Sound');
INSERT INTO `LocalizationKeys` VALUES (751, 100, '_Upload Video');
INSERT INTO `LocalizationKeys` VALUES (752, 100, '_Update story');
INSERT INTO `LocalizationKeys` VALUES (753, 100, '_Use latin set');
INSERT INTO `LocalizationKeys` VALUES (756, 3, '_User was added to block list');
INSERT INTO `LocalizationKeys` VALUES (757, 3, '_User was added to hot list');
INSERT INTO `LocalizationKeys` VALUES (758, 3, '_User was added to friend list');
INSERT INTO `LocalizationKeys` VALUES (759, 3, '_User was invited to friend list');
INSERT INTO `LocalizationKeys` VALUES (760, 3, '_already_in_friend_list');
INSERT INTO `LocalizationKeys` VALUES (761, 3, '_User was added to im');
INSERT INTO `LocalizationKeys` VALUES (762, 100, '_Video');
INSERT INTO `LocalizationKeys` VALUES (763, 100, '_Video file successfully deleted');
INSERT INTO `LocalizationKeys` VALUES (764, 100, '_Video file remove failed');
INSERT INTO `LocalizationKeys` VALUES (765, 100, '_view video');
INSERT INTO `LocalizationKeys` VALUES (766, 100, '_View profile');
INSERT INTO `LocalizationKeys` VALUES (767, 100, '_View Profile');
INSERT INTO `LocalizationKeys` VALUES (768, 100, '_view as profile details');
INSERT INTO `LocalizationKeys` VALUES (769, 100, '_view as photo gallery');
INSERT INTO `LocalizationKeys` VALUES (770, 100, '_Visitor menu');
INSERT INTO `LocalizationKeys` VALUES (771, 100, '_Vote profile');
INSERT INTO `LocalizationKeys` VALUES (772, 100, '_VoteBad');
INSERT INTO `LocalizationKeys` VALUES (773, 100, '_VoteGood');
INSERT INTO `LocalizationKeys` VALUES (774, 100, '_Vote Average Mark');
INSERT INTO `LocalizationKeys` VALUES (775, 100, '_Vote accepted');
INSERT INTO `LocalizationKeys` VALUES (776, 100, '_votes');
INSERT INTO `LocalizationKeys` VALUES (777, 100, '_Write Message');
INSERT INTO `LocalizationKeys` VALUES (778, 24, '_Want children');
INSERT INTO `LocalizationKeys` VALUES (1728, 4, '_ACTION_NOT_ALLOWED');
INSERT INTO `LocalizationKeys` VALUES (780, 100, '_Was contacted');
INSERT INTO `LocalizationKeys` VALUES (781, 100, '_Welcome');
INSERT INTO `LocalizationKeys` VALUES (782, 100, '_with');
INSERT INTO `LocalizationKeys` VALUES (783, 100, '_With photos only');
INSERT INTO `LocalizationKeys` VALUES (784, 100, '_What do you seek somebody for?');
INSERT INTO `LocalizationKeys` VALUES (785, 100, '_within');
INSERT INTO `LocalizationKeys` VALUES (786, 100, '_Whom do you look for?');
INSERT INTO `LocalizationKeys` VALUES (787, 100, '_who is from');
INSERT INTO `LocalizationKeys` VALUES (788, 100, '_Women');
INSERT INTO `LocalizationKeys` VALUES (789, 100, '_women');
INSERT INTO `LocalizationKeys` VALUES (790, 100, '_woman');
INSERT INTO `LocalizationKeys` VALUES (791, 100, '_woman_');
INSERT INTO `LocalizationKeys` VALUES (792, 100, '_Write access:');
INSERT INTO `LocalizationKeys` VALUES (793, 100, '_write message');
INSERT INTO `LocalizationKeys` VALUES (794, 100, '_XX match');
INSERT INTO `LocalizationKeys` VALUES (795, 100, '_y/o');
INSERT INTO `LocalizationKeys` VALUES (796, 100, '_your rate');
INSERT INTO `LocalizationKeys` VALUES (797, 100, '_Yes');
INSERT INTO `LocalizationKeys` VALUES (798, 100, '_Yahoo');
INSERT INTO `LocalizationKeys` VALUES (799, 100, '_yahoo');
INSERT INTO `LocalizationKeys` VALUES (800, 100, '_You can get my');
INSERT INTO `LocalizationKeys` VALUES (801, 100, '_You are');
INSERT INTO `LocalizationKeys` VALUES (802, 100, '_You already voted');
INSERT INTO `LocalizationKeys` VALUES (803, 100, '_Your email');
INSERT INTO `LocalizationKeys` VALUES (804, 100, '_You have to wait for PERIOD minutes before you can write another message!');
INSERT INTO `LocalizationKeys` VALUES (805, 100, '_Your name');
INSERT INTO `LocalizationKeys` VALUES (806, 100, '_Your Shopping Cart');
INSERT INTO `LocalizationKeys` VALUES (807, 100, '_Your private messages here');
INSERT INTO `LocalizationKeys` VALUES (808, 24, '_Zip/Postal Code');
INSERT INTO `LocalizationKeys` VALUES (809, 100, '_Post date');
INSERT INTO `LocalizationKeys` VALUES (810, 2, '_AECHAT_H');
INSERT INTO `LocalizationKeys` VALUES (811, 2, '_AECHAT_H1');
INSERT INTO `LocalizationKeys` VALUES (812, 2, '_ABOUT_US_H');
INSERT INTO `LocalizationKeys` VALUES (813, 2, '_ABOUT_US_H1');
INSERT INTO `LocalizationKeys` VALUES (814, 2, '_ACTIVATION_EMAIL_H');
INSERT INTO `LocalizationKeys` VALUES (815, 2, '_ACTIVATION_EMAIL_H1');
INSERT INTO `LocalizationKeys` VALUES (816, 2, '_AFFILIATES_H1');
INSERT INTO `LocalizationKeys` VALUES (817, 2, '_AFFILIATES_H');
INSERT INTO `LocalizationKeys` VALUES (819, 2, '_ADD_TO_CART');
INSERT INTO `LocalizationKeys` VALUES (820, 2, '_ARTICLES_H');
INSERT INTO `LocalizationKeys` VALUES (821, 2, '_ARTICLES_H1');
INSERT INTO `LocalizationKeys` VALUES (822, 2, '_CART_H');
INSERT INTO `LocalizationKeys` VALUES (823, 2, '_CART_H1');
INSERT INTO `LocalizationKeys` VALUES (824, 2, '_CC_H');
INSERT INTO `LocalizationKeys` VALUES (825, 2, '_CC_H1');
INSERT INTO `LocalizationKeys` VALUES (826, 2, '_CHANGE_STATUS_H');
INSERT INTO `LocalizationKeys` VALUES (827, 2, '_CHANGE_STATUS_H1');
INSERT INTO `LocalizationKeys` VALUES (828, 2, '_CHAT_WITH_');
INSERT INTO `LocalizationKeys` VALUES (829, 2, '_COMING_SOON_H');
INSERT INTO `LocalizationKeys` VALUES (830, 2, '_COMPOSE_H');
INSERT INTO `LocalizationKeys` VALUES (831, 2, '_COMPOSE_H1');
INSERT INTO `LocalizationKeys` VALUES (832, 2, '_COMPOSE_STORY_H');
INSERT INTO `LocalizationKeys` VALUES (833, 2, '_COMPOSE_STORY_H1');
INSERT INTO `LocalizationKeys` VALUES (834, 2, '_CONFIRM_H');
INSERT INTO `LocalizationKeys` VALUES (835, 2, '_COMPOSE_STORY_VIEW_H');
INSERT INTO `LocalizationKeys` VALUES (836, 2, '_COMPOSE_STORY_VIEW_H1');
INSERT INTO `LocalizationKeys` VALUES (837, 2, '_COMPOSE_NEWS_VIEW_H');
INSERT INTO `LocalizationKeys` VALUES (838, 2, '_COMPOSE_NEWS_VIEW_H1');
INSERT INTO `LocalizationKeys` VALUES (839, 2, '_CONTACT_H');
INSERT INTO `LocalizationKeys` VALUES (840, 2, '_CONTACT_H1');
INSERT INTO `LocalizationKeys` VALUES (841, 2, '_EMAIL_CONF_H');
INSERT INTO `LocalizationKeys` VALUES (842, 2, '_EXPLANATION_H');
INSERT INTO `LocalizationKeys` VALUES (843, 2, '_FAQ_H');
INSERT INTO `LocalizationKeys` VALUES (844, 2, '_FAQ_H1');
INSERT INTO `LocalizationKeys` VALUES (845, 2, '_FEATURED_H');
INSERT INTO `LocalizationKeys` VALUES (846, 2, '_FEATURED_H1');
INSERT INTO `LocalizationKeys` VALUES (847, 2, '_FORGOT_H');
INSERT INTO `LocalizationKeys` VALUES (848, 2, '_FREEMAIL_H');
INSERT INTO `LocalizationKeys` VALUES (849, 2, '_HOTORNOT_H');
INSERT INTO `LocalizationKeys` VALUES (850, 2, '_HOTORNOT_H1');
INSERT INTO `LocalizationKeys` VALUES (851, 2, '_INBOX_H');
INSERT INTO `LocalizationKeys` VALUES (852, 2, '_INBOX_H1');
INSERT INTO `LocalizationKeys` VALUES (853, 2, '_INBOX_g4');
INSERT INTO `LocalizationKeys` VALUES (854, 2, '_INDEX_H');
INSERT INTO `LocalizationKeys` VALUES (856, 2, '_GET_EMAIL');
INSERT INTO `LocalizationKeys` VALUES (858, 2, '_GET_SOUND');
INSERT INTO `LocalizationKeys` VALUES (859, 2, '_JOIN_H');
INSERT INTO `LocalizationKeys` VALUES (860, 2, '_JOIN_AFF_H');
INSERT INTO `LocalizationKeys` VALUES (861, 2, '_LINKS_H');
INSERT INTO `LocalizationKeys` VALUES (862, 2, '_LINKS_H1');
INSERT INTO `LocalizationKeys` VALUES (863, 2, '_MEMBER_LOGIN_H');
INSERT INTO `LocalizationKeys` VALUES (864, 2, '_MEMBER_PANEL_H');
INSERT INTO `LocalizationKeys` VALUES (865, 2, '_MEMBER_PANEL_H1');
INSERT INTO `LocalizationKeys` VALUES (866, 2, '_MEMBERSHIP_H');
INSERT INTO `LocalizationKeys` VALUES (867, 2, '_MEMBERSHIP_H1');
INSERT INTO `LocalizationKeys` VALUES (868, 2, '_NEWS_H');
INSERT INTO `LocalizationKeys` VALUES (869, 2, '_OUTBOX_H');
INSERT INTO `LocalizationKeys` VALUES (870, 2, '_OUTBOX_H1');
INSERT INTO `LocalizationKeys` VALUES (871, 2, '_OUTBOX_g4');
INSERT INTO `LocalizationKeys` VALUES (872, 2, '_OUR_SERV');
INSERT INTO `LocalizationKeys` VALUES (873, 2, '_PRIVACY_H');
INSERT INTO `LocalizationKeys` VALUES (874, 2, '_PRIVACY_H1');
INSERT INTO `LocalizationKeys` VALUES (875, 2, '_PHOTOS_H');
INSERT INTO `LocalizationKeys` VALUES (876, 2, '_PHOTOS_H2');
INSERT INTO `LocalizationKeys` VALUES (877, 2, '_PIC_GALLERY_H');
INSERT INTO `LocalizationKeys` VALUES (878, 2, '_PIC_GALLERY_H1');
INSERT INTO `LocalizationKeys` VALUES (882, 2, '_PROFILE_VIEW_H');
INSERT INTO `LocalizationKeys` VALUES (883, 2, '_RESULT0_H');
INSERT INTO `LocalizationKeys` VALUES (884, 2, '_RESULT-1_H');
INSERT INTO `LocalizationKeys` VALUES (885, 2, '_RESULT1_H');
INSERT INTO `LocalizationKeys` VALUES (886, 2, '_SEARCH_H');
INSERT INTO `LocalizationKeys` VALUES (887, 2, '_SEARCH_FOR');
INSERT INTO `LocalizationKeys` VALUES (888, 2, '_SEARCH_RESULT_H');
INSERT INTO `LocalizationKeys` VALUES (889, 2, '_SEARCH_RESULT_ALLOWED_PROFILES');
INSERT INTO `LocalizationKeys` VALUES (890, 2, '_SOUND_H');
INSERT INTO `LocalizationKeys` VALUES (891, 2, '_STORY_VIEW_H1');
INSERT INTO `LocalizationKeys` VALUES (892, 2, '_STORY_VIEW_H');
INSERT INTO `LocalizationKeys` VALUES (893, 2, '_TERMS_OF_USE_H');
INSERT INTO `LocalizationKeys` VALUES (894, 2, '_TERMS_OF_USE_H1');
INSERT INTO `LocalizationKeys` VALUES (896, 2, '_VIDEO_H');
INSERT INTO `LocalizationKeys` VALUES (897, 2, '_VKISS_H');
INSERT INTO `LocalizationKeys` VALUES (899, 1, '_ABOUT_US');
INSERT INTO `LocalizationKeys` VALUES (901, 1, '_ADM_PROFILE_CONFIRM_EM');
INSERT INTO `LocalizationKeys` VALUES (902, 1, '_ADM_PROFILE_SEND_MSG');
INSERT INTO `LocalizationKeys` VALUES (906, 1, '_AFFILIATES');
INSERT INTO `LocalizationKeys` VALUES (907, 3, '_ALREADY_ACTIVATED');
INSERT INTO `LocalizationKeys` VALUES (909, 1, '_ATT_UNCONFIRMED_E');
INSERT INTO `LocalizationKeys` VALUES (910, 1, '_ATT_UNCONFIRMED');
INSERT INTO `LocalizationKeys` VALUES (911, 1, '_ATT_APPROVAL');
INSERT INTO `LocalizationKeys` VALUES (912, 1, '_ATT_APPROVAL_E');
INSERT INTO `LocalizationKeys` VALUES (913, 1, '_ATT_ACTIVE');
INSERT INTO `LocalizationKeys` VALUES (914, 1, '_ATT_ACTIVE_E');
INSERT INTO `LocalizationKeys` VALUES (917, 1, '_ATT_REJECTED');
INSERT INTO `LocalizationKeys` VALUES (918, 1, '_ATT_REJECTED_E');
INSERT INTO `LocalizationKeys` VALUES (919, 1, '_ATT_SUSPENDED');
INSERT INTO `LocalizationKeys` VALUES (920, 1, '_ATT_SUSPENDED_E');
INSERT INTO `LocalizationKeys` VALUES (921, 1, '_ATT_MESSAGE');
INSERT INTO `LocalizationKeys` VALUES (923, 1, '_ATT_VKISS');
INSERT INTO `LocalizationKeys` VALUES (924, 1, '_ATT_FRIEND');
INSERT INTO `LocalizationKeys` VALUES (925, 1, '_CANT_SEND_VKISS7');
INSERT INTO `LocalizationKeys` VALUES (927, 1, '_CANT_VIEW_PROFILE');
INSERT INTO `LocalizationKeys` VALUES (928, 3, '_CART_EMPTY');
INSERT INTO `LocalizationKeys` VALUES (929, 3, '_CART_EMPTIED');
INSERT INTO `LocalizationKeys` VALUES (930, 3, '_CITY_REQUIRED');
INSERT INTO `LocalizationKeys` VALUES (932, 3, '_COMPOSE_REJECT2');
INSERT INTO `LocalizationKeys` VALUES (934, 1, '_COMING_SOON');
INSERT INTO `LocalizationKeys` VALUES (936, 1, '_CONTACT');
INSERT INTO `LocalizationKeys` VALUES (937, 3, '_CONTACTS_CHOSEN');
INSERT INTO `LocalizationKeys` VALUES (939, 3, '_DELETE_SUCCESS');
INSERT INTO `LocalizationKeys` VALUES (940, 1, '_DELETE_TEXT');
INSERT INTO `LocalizationKeys` VALUES (941, 3, '_PWD_INVALID');
INSERT INTO `LocalizationKeys` VALUES (942, 3, '_PWD_INVALID2');
INSERT INTO `LocalizationKeys` VALUES (943, 3, '_DESC_LEAST');
INSERT INTO `LocalizationKeys` VALUES (946, 3, '_EMAIL_ALREADY_USED_BY');
INSERT INTO `LocalizationKeys` VALUES (947, 3, '_EMAIL_CONF_FAILED');
INSERT INTO `LocalizationKeys` VALUES (948, 3, '_EMAIL_CONF_FAILED_EX');
INSERT INTO `LocalizationKeys` VALUES (949, 3, '_EMAIL_CONF_NOT_SENT');
INSERT INTO `LocalizationKeys` VALUES (950, 3, '_EMAIL_CONF_SENT');
INSERT INTO `LocalizationKeys` VALUES (951, 3, '_EMAIL_CONF_SUCCEEDED');
INSERT INTO `LocalizationKeys` VALUES (952, 3, '_EMAIL_INVALID');
INSERT INTO `LocalizationKeys` VALUES (953, 3, '_EMAIL_INVALID_AFF');
INSERT INTO `LocalizationKeys` VALUES (954, 3, '_EMAIL_REQUIRED');
INSERT INTO `LocalizationKeys` VALUES (956, 1, '_ENTER_CONF_CODE');
INSERT INTO `LocalizationKeys` VALUES (957, 3, '_ERROR_OCCURED');
INSERT INTO `LocalizationKeys` VALUES (958, 3, '_FAILED_TO_UPLOAD_SOUND');
INSERT INTO `LocalizationKeys` VALUES (959, 3, '_FAILED_TO_UPLOAD_VIDEO');
INSERT INTO `LocalizationKeys` VALUES (960, 3, '_FAILED_TO_DELETE_PIC');
INSERT INTO `LocalizationKeys` VALUES (961, 3, '_FAILED_TO_MAKE_THUMB_FROM_PRIMARY');
INSERT INTO `LocalizationKeys` VALUES (962, 3, '_FAILED_TO_SEND_MESSAGE');
INSERT INTO `LocalizationKeys` VALUES (964, 3, '_FAILED_TO_SEND_MESSAGE_BLOCK');
INSERT INTO `LocalizationKeys` VALUES (965, 3, '_FAILED_TO_SEND_MESSAGE_MEMBERSHIP');
INSERT INTO `LocalizationKeys` VALUES (966, 3, '_FAILED_TO_SEND_MESSAGE_NO_CREDITS');
INSERT INTO `LocalizationKeys` VALUES (967, 3, '_FAILED_TO_SEND_MESSAGE_NOT_ACTIVE');
INSERT INTO `LocalizationKeys` VALUES (968, 3, '_FAILED_TO_UPDATE_PROFILE');
INSERT INTO `LocalizationKeys` VALUES (969, 3, '_FAILED_TO_UPLOAD_PIC');
INSERT INTO `LocalizationKeys` VALUES (970, 1, '_FAQ_INFO');
INSERT INTO `LocalizationKeys` VALUES (971, 1, '_FIELDS*_OPTIONAL');
INSERT INTO `LocalizationKeys` VALUES (972, 1, '_FORGOT');
INSERT INTO `LocalizationKeys` VALUES (973, 3, '_FREEMAIL_ALREADY_SENT');
INSERT INTO `LocalizationKeys` VALUES (974, 3, '_FREEMAIL_CHOOSE_ID');
INSERT INTO `LocalizationKeys` VALUES (975, 3, '_FREEMAIL_BLOCK');
INSERT INTO `LocalizationKeys` VALUES (976, 3, '_FREEMAIL_ERROR');
INSERT INTO `LocalizationKeys` VALUES (977, 3, '_FREEMAIL_NOT_ALLOWED');
INSERT INTO `LocalizationKeys` VALUES (978, 3, '_FREEMAIL_NOT_KISSED');
INSERT INTO `LocalizationKeys` VALUES (980, 3, '_FREEMAIL_SENT');
INSERT INTO `LocalizationKeys` VALUES (983, 3, '_INCORRECT_EMAIL');
INSERT INTO `LocalizationKeys` VALUES (987, 3, '_INVALID_ID');
INSERT INTO `LocalizationKeys` VALUES (988, 3, '_INVALID_PASSWD');
INSERT INTO `LocalizationKeys` VALUES (989, 1, '_JOIN1');
INSERT INTO `LocalizationKeys` VALUES (990, 1, '_JOIN1_AFF');
INSERT INTO `LocalizationKeys` VALUES (992, 1, '_JOIN3');
INSERT INTO `LocalizationKeys` VALUES (993, 1, '_JOIN_AFF2');
INSERT INTO `LocalizationKeys` VALUES (994, 1, '_JOIN_AFF_ID');
INSERT INTO `LocalizationKeys` VALUES (995, 1, '_GIVE_MY_INFO_GM');
INSERT INTO `LocalizationKeys` VALUES (996, 1, '_HEADLINE_LEAST');
INSERT INTO `LocalizationKeys` VALUES (997, 3, '_LOGGING_OUT');
INSERT INTO `LocalizationKeys` VALUES (998, 3, '_LOGIN_ERROR');
INSERT INTO `LocalizationKeys` VALUES (999, 3, '_LOGIN_OBSOLETE');
INSERT INTO `LocalizationKeys` VALUES (1000, 1, '_LOGIN_REQUIRED1');
INSERT INTO `LocalizationKeys` VALUES (1002, 1, '_LOGIN_REQUIRED_AE1');
INSERT INTO `LocalizationKeys` VALUES (1003, 1, '_LOGIN_REQUIRED_AE2');
INSERT INTO `LocalizationKeys` VALUES (1005, 1, '_MAX_CHARS_TO_CC_UNLIM_TO_EMAIL');
INSERT INTO `LocalizationKeys` VALUES (1006, 1, '_MEMBER_ADDED_TO_CART');
INSERT INTO `LocalizationKeys` VALUES (1007, 1, '_MEMBER_ALREADY_CONTACTED');
INSERT INTO `LocalizationKeys` VALUES (1008, 1, '_MEMBER_ALREADY_IN_CART');
INSERT INTO `LocalizationKeys` VALUES (1009, 1, '_MEMBER_NOT_RECOGNIZED');
INSERT INTO `LocalizationKeys` VALUES (1011, 1, '_MEMBER_RECOGNIZED_MAIL_NOT_SENT');
INSERT INTO `LocalizationKeys` VALUES (1012, 1, '_MEMBER_RECOGNIZED_MAIL_SENT');
INSERT INTO `LocalizationKeys` VALUES (1013, 1, '_MEMBERS_ONLINE');
INSERT INTO `LocalizationKeys` VALUES (1014, 1, '_MEMBERS_YOU_CONTACTED');
INSERT INTO `LocalizationKeys` VALUES (1015, 1, '_MEMBERS_YOU_CONTACTED_BY');
INSERT INTO `LocalizationKeys` VALUES (1016, 1, '_MEMBERS_YOU_CONTACTED_FREE');
INSERT INTO `LocalizationKeys` VALUES (1017, 1, '_MEMBERS_YOU_CONTACTED_BY_FREE');
INSERT INTO `LocalizationKeys` VALUES (1018, 1, '_MEMBERS_YOU_KISSED');
INSERT INTO `LocalizationKeys` VALUES (1019, 1, '_MEMBERS_YOU_KISSED_BY');
INSERT INTO `LocalizationKeys` VALUES (1020, 1, '_MEMBERS_YOU_VIEWED');
INSERT INTO `LocalizationKeys` VALUES (1021, 1, '_MEMBERS_YOU_VIEWED_BY');
INSERT INTO `LocalizationKeys` VALUES (1022, 1, '_MEMBERS_YOU_HOTLISTED');
INSERT INTO `LocalizationKeys` VALUES (1023, 1, '_MEMBERS_YOU_HOTLISTED_BY');
INSERT INTO `LocalizationKeys` VALUES (1024, 1, '_MEMBERS_INVITE_YOU_FRIENDLIST');
INSERT INTO `LocalizationKeys` VALUES (1025, 1, '_MEMBERS_YOU_INVITED_FRIENDLIST');
INSERT INTO `LocalizationKeys` VALUES (1026, 1, '_MEMBERS_YOU_BLOCKLISTED');
INSERT INTO `LocalizationKeys` VALUES (1027, 1, '_MEMBERS_YOU_BLOCKLISTED_BY');
INSERT INTO `LocalizationKeys` VALUES (1028, 1, '_MEMBERS_YOU_PPAL_BY');
INSERT INTO `LocalizationKeys` VALUES (1029, 1, '_MEMBERS_YOU_PPAL');
INSERT INTO `LocalizationKeys` VALUES (1036, 1, '_MEMBERSHIP_CREDITS');
INSERT INTO `LocalizationKeys` VALUES (1037, 1, '_MEMBERSHIP_CREDITS_E');
INSERT INTO `LocalizationKeys` VALUES (1038, 1, '_MEMBERSHIP_CREDITS_YES');
INSERT INTO `LocalizationKeys` VALUES (1039, 1, '_MEMBERSHIP_CREDITS_NO');
INSERT INTO `LocalizationKeys` VALUES (1040, 1, '_MEMBERSHIP_BUY_MORE_DAYS');
INSERT INTO `LocalizationKeys` VALUES (1041, 1, '_MEMBERSHIP_EXPIRES_IN_DAYS');
INSERT INTO `LocalizationKeys` VALUES (1042, 1, '_MEMBERSHIP_EXPIRES_NEVER');
INSERT INTO `LocalizationKeys` VALUES (1043, 1, '_MEMBERSHIP_EXPIRES_TODAY');
INSERT INTO `LocalizationKeys` VALUES (1044, 1, '_VIEW_MEMBERSHIP_ACTIONS');
INSERT INTO `LocalizationKeys` VALUES (1046, 1, '_MEMBERSHIP_PRIVILEGED');
INSERT INTO `LocalizationKeys` VALUES (1047, 1, '_TODAY_EXCEEDED');
INSERT INTO `LocalizationKeys` VALUES (1732, 4, '_ACTION_NOT_ALLOWED_AFTER');
INSERT INTO `LocalizationKeys` VALUES (1055, 1, '_MEMBERSHIP_STANDARD');
INSERT INTO `LocalizationKeys` VALUES (1056, 1, '_MEMBERSHIP_UPGRADE_FROM_STANDARD');
INSERT INTO `LocalizationKeys` VALUES (1057, 1, '_MEMBERSHIP_STANDARD_CHOOSE');
INSERT INTO `LocalizationKeys` VALUES (1058, 1, '_MEMBERSHIP_T_BUY_MORE');
INSERT INTO `LocalizationKeys` VALUES (1059, 3, '_MESSAGE_SENT');
INSERT INTO `LocalizationKeys` VALUES (1060, 3, '_MODIFICATIONS_APPLIED');
INSERT INTO `LocalizationKeys` VALUES (1061, 1, '_MUST_BE_VALID');
INSERT INTO `LocalizationKeys` VALUES (1062, 1, '_MUST_HAVE_COOKIES');
INSERT INTO `LocalizationKeys` VALUES (1063, 1, '_NEW_KISS_ARRIVED');
INSERT INTO `LocalizationKeys` VALUES (1064, 1, '_NEW_MESSAGE_ARRIVED');
INSERT INTO `LocalizationKeys` VALUES (1065, 3, '_NICK_LEAST');
INSERT INTO `LocalizationKeys` VALUES (1066, 3, '_NICK_LEAST2');
INSERT INTO `LocalizationKeys` VALUES (1067, 1, '_NICK_IS_AVFF');
INSERT INTO `LocalizationKeys` VALUES (1068, 1, '_NICK_YOU_CAN_BUY');
INSERT INTO `LocalizationKeys` VALUES (1069, 1, '_NO_ARTICLES');
INSERT INTO `LocalizationKeys` VALUES (1070, 1, '_NO_LINKS');
INSERT INTO `LocalizationKeys` VALUES (1071, 1, '_NO_MEMBER_SPECIFIED');
INSERT INTO `LocalizationKeys` VALUES (1072, 1, '_NO_MEMBER_TO_DELETE');
INSERT INTO `LocalizationKeys` VALUES (1073, 1, '_NO_NEED_TO_CONFIRM_EMAIL');
INSERT INTO `LocalizationKeys` VALUES (1074, 1, '_NO_RESULTS');
INSERT INTO `LocalizationKeys` VALUES (1075, 1, '_NO_STORIES');
INSERT INTO `LocalizationKeys` VALUES (1076, 1, '_NOT_RECOGNIZED');
INSERT INTO `LocalizationKeys` VALUES (1078, 3, '_PASSWD_CONF_FAILED');
INSERT INTO `LocalizationKeys` VALUES (1079, 3, '_PASSWD_LEAST');
INSERT INTO `LocalizationKeys` VALUES (1080, 1, '_PHOTOS');
INSERT INTO `LocalizationKeys` VALUES (1081, 1, '_PHOTOS_WARNING');
INSERT INTO `LocalizationKeys` VALUES (1082, 3, '_PIC_DELETED');
INSERT INTO `LocalizationKeys` VALUES (1083, 3, '_PIC_UPLOADED');
INSERT INTO `LocalizationKeys` VALUES (1085, 2, '_POLLS_VIEW_H');
INSERT INTO `LocalizationKeys` VALUES (1086, 2, '_POLLS_VIEW_H1');
INSERT INTO `LocalizationKeys` VALUES (1087, 2, '_POLL_VIEW_H');
INSERT INTO `LocalizationKeys` VALUES (1088, 2, '_POLL_VIEW_H1');
INSERT INTO `LocalizationKeys` VALUES (1089, 1, '_PRIVATE PHOTO TEXT');
INSERT INTO `LocalizationKeys` VALUES (1090, 1, '_PROFILE_CAN_ACTIVATE');
INSERT INTO `LocalizationKeys` VALUES (1091, 1, '_PROFILE_CAN_SUSPEND');
INSERT INTO `LocalizationKeys` VALUES (1092, 1, '_PROFILE_CANT_ACTIVATE/SUSPEND');
INSERT INTO `LocalizationKeys` VALUES (1093, 1, '_PROFILE_NOT_AVAILABLE');
INSERT INTO `LocalizationKeys` VALUES (1094, 1, '_PROFILE_WARNING1');
INSERT INTO `LocalizationKeys` VALUES (1095, 1, '_PROFILE_WARNING2');
INSERT INTO `LocalizationKeys` VALUES (1096, 1, '_PRIVACY');
INSERT INTO `LocalizationKeys` VALUES (1097, 1, '_HOTORNOT');
INSERT INTO `LocalizationKeys` VALUES (1098, 1, '_HOTORNOT_NA');
INSERT INTO `LocalizationKeys` VALUES (1099, 3, '_PROFILE_ERR');
INSERT INTO `LocalizationKeys` VALUES (1100, 3, '_RECOGNIZED');
INSERT INTO `LocalizationKeys` VALUES (1101, 3, '_REGISTRATION_ERROR');
INSERT INTO `LocalizationKeys` VALUES (1102, 1, '_REGISTRATION_GOLD_MEMB_TEXT');
INSERT INTO `LocalizationKeys` VALUES (1103, 1, '_REALNAME_REQUIRED');
INSERT INTO `LocalizationKeys` VALUES (1104, 3, '_RELATIONSHIP_REQUIRED');
INSERT INTO `LocalizationKeys` VALUES (1106, 3, '_REQUEST SENT');
INSERT INTO `LocalizationKeys` VALUES (1107, 3, '_RESULT0');
INSERT INTO `LocalizationKeys` VALUES (1108, 3, '_RESULT0_A');
INSERT INTO `LocalizationKeys` VALUES (1110, 3, '_RESULT-1');
INSERT INTO `LocalizationKeys` VALUES (1111, 3, '_RESULT-1_A');
INSERT INTO `LocalizationKeys` VALUES (1112, 3, '_RESULT-1_D');
INSERT INTO `LocalizationKeys` VALUES (1113, 3, '_RESULT1000');
INSERT INTO `LocalizationKeys` VALUES (1115, 3, '_RESULT1_DESC');
INSERT INTO `LocalizationKeys` VALUES (1116, 3, '_RESULT1_THANK');
INSERT INTO `LocalizationKeys` VALUES (1118, 3, '_RESULT2DESC');
INSERT INTO `LocalizationKeys` VALUES (1119, 1, '_quick_links_content');
INSERT INTO `LocalizationKeys` VALUES (1120, 1, '_SEARCH_SORTED');
INSERT INTO `LocalizationKeys` VALUES (1121, 1, '_SEND_MESSAGE');
INSERT INTO `LocalizationKeys` VALUES (1122, 1, '_SEND_MSG_TO');
INSERT INTO `LocalizationKeys` VALUES (1123, 1, '_SERV_DESC');
INSERT INTO `LocalizationKeys` VALUES (1125, 1, '_SOUND');
INSERT INTO `LocalizationKeys` VALUES (1126, 1, '_SOUND_WARNING');
INSERT INTO `LocalizationKeys` VALUES (1127, 3, '_STORY_ADDED');
INSERT INTO `LocalizationKeys` VALUES (1128, 3, '_STORY_ADDED_FAILED');
INSERT INTO `LocalizationKeys` VALUES (1129, 3, '_STORY_UPDATED');
INSERT INTO `LocalizationKeys` VALUES (1130, 3, '_STORY_UPDATED_FAILED');
INSERT INTO `LocalizationKeys` VALUES (1131, 3, '_STORY_DELETED');
INSERT INTO `LocalizationKeys` VALUES (1132, 3, '_STORY_DELETED_FAILED');
INSERT INTO `LocalizationKeys` VALUES (1133, 3, '_STORY_EMPTY_HEADER');
INSERT INTO `LocalizationKeys` VALUES (1134, 1, '_SUBSCRIBE_TEXT');
INSERT INTO `LocalizationKeys` VALUES (1135, 1, '_THANK_YOU');
INSERT INTO `LocalizationKeys` VALUES (1136, 1, '_TELLAFRIEND');
INSERT INTO `LocalizationKeys` VALUES (1137, 1, '_TELLAFRIEND2');
INSERT INTO `LocalizationKeys` VALUES (1138, 1, '_TERMINATE_ACCOUNT');
INSERT INTO `LocalizationKeys` VALUES (1139, 1, '_TERMS_OF_USE');
INSERT INTO `LocalizationKeys` VALUES (1731, 4, '_ACTION_NOT_ALLOWED_BEFORE');
INSERT INTO `LocalizationKeys` VALUES (1143, 3, '_UNDEFINED_ERROR');
INSERT INTO `LocalizationKeys` VALUES (1144, 1, '_UPLOAD_WHILE_WAITING');
INSERT INTO `LocalizationKeys` VALUES (1145, 1, '_VIDEO');
INSERT INTO `LocalizationKeys` VALUES (1146, 1, '_VIDEO_WARNING');
INSERT INTO `LocalizationKeys` VALUES (1147, 3, '_VKISS_SENT');
INSERT INTO `LocalizationKeys` VALUES (1148, 1, '_VKISS_FROM');
INSERT INTO `LocalizationKeys` VALUES (1149, 1, '_WELCOME_MEMBER');
INSERT INTO `LocalizationKeys` VALUES (1150, 1, '_WILL_BE_RESIZED');
INSERT INTO `LocalizationKeys` VALUES (1151, 1, '_YOU_ACQUIRED');
INSERT INTO `LocalizationKeys` VALUES (1152, 1, '_YOUR_INFO_ACQUIRED');
INSERT INTO `LocalizationKeys` VALUES (1153, 1, '_YOUR PROFILE_IS_NOT_ACTIVE');
INSERT INTO `LocalizationKeys` VALUES (1154, 1, '_YOUR_EMAIL_HERE');
INSERT INTO `LocalizationKeys` VALUES (1155, 1, '_YOUR_SEARCH');
INSERT INTO `LocalizationKeys` VALUES (1157, 3, '_VKISS_OK');
INSERT INTO `LocalizationKeys` VALUES (1158, 3, '_VKISS_BAD');
INSERT INTO `LocalizationKeys` VALUES (1733, 4, '_ACTION_EVERY_PERIOD');
INSERT INTO `LocalizationKeys` VALUES (1160, 3, '_VKISS_BAD_COUSE_A2');
INSERT INTO `LocalizationKeys` VALUES (1161, 3, '_VKISS_BAD_COUSE_NO_PERM');
INSERT INTO `LocalizationKeys` VALUES (1162, 3, '_VKISS_BAD_COUSE_A3');
INSERT INTO `LocalizationKeys` VALUES (1163, 3, '_VKISS_BAD_COUSE_A4');
INSERT INTO `LocalizationKeys` VALUES (1164, 3, '_VKISS_BAD_COUSE_B');
INSERT INTO `LocalizationKeys` VALUES (1165, 3, '_VKISS_BAD_COUSE_X');
INSERT INTO `LocalizationKeys` VALUES (1166, 3, '_VKISS_BAD_COUSE_Y');
INSERT INTO `LocalizationKeys` VALUES (1167, 3, '_VKISS_BAD_COUSE_C');
INSERT INTO `LocalizationKeys` VALUES (1168, 3, '_VKISS_BAD_COUSE_D');
INSERT INTO `LocalizationKeys` VALUES (1169, 3, '_ZIP_REQUIRED');
INSERT INTO `LocalizationKeys` VALUES (1170, 100, '_Character counter');
INSERT INTO `LocalizationKeys` VALUES (1171, 100, '_logged in forum as');
INSERT INTO `LocalizationKeys` VALUES (1172, 9, '_01');
INSERT INTO `LocalizationKeys` VALUES (1173, 9, '_02');
INSERT INTO `LocalizationKeys` VALUES (1174, 9, '_03');
INSERT INTO `LocalizationKeys` VALUES (1175, 9, '_04');
INSERT INTO `LocalizationKeys` VALUES (1176, 9, '_05');
INSERT INTO `LocalizationKeys` VALUES (1177, 9, '_06');
INSERT INTO `LocalizationKeys` VALUES (1178, 9, '_07');
INSERT INTO `LocalizationKeys` VALUES (1179, 9, '_08');
INSERT INTO `LocalizationKeys` VALUES (1180, 9, '_09');
INSERT INTO `LocalizationKeys` VALUES (1181, 9, '_10');
INSERT INTO `LocalizationKeys` VALUES (1182, 9, '_11');
INSERT INTO `LocalizationKeys` VALUES (1183, 9, '_12');
INSERT INTO `LocalizationKeys` VALUES (1184, 100, '__I prefer not to say');
INSERT INTO `LocalizationKeys` VALUES (1185, 7, '_sdating');
INSERT INTO `LocalizationKeys` VALUES (1186, 7, '_sdating_h');
INSERT INTO `LocalizationKeys` VALUES (1232, 6, '_Name:');
INSERT INTO `LocalizationKeys` VALUES (1233, 6, '_Comment:');
INSERT INTO `LocalizationKeys` VALUES (1237, 6, '_Apply changes');
INSERT INTO `LocalizationKeys` VALUES (1238, 6, '_Last changes:');
INSERT INTO `LocalizationKeys` VALUES (1242, 6, '_Back');
INSERT INTO `LocalizationKeys` VALUES (1244, 6, '_Add new albom:');
INSERT INTO `LocalizationKeys` VALUES (1245, 6, '_Add new albom');
INSERT INTO `LocalizationKeys` VALUES (1246, 6, '_Access level:');
INSERT INTO `LocalizationKeys` VALUES (1247, 6, '_No one object was found');
INSERT INTO `LocalizationKeys` VALUES (1248, 6, '_Delete albom');
INSERT INTO `LocalizationKeys` VALUES (1249, 6, '_Albom successfully added');
INSERT INTO `LocalizationKeys` VALUES (1250, 6, '_Album updated');
INSERT INTO `LocalizationKeys` VALUES (1251, 6, '_Objects successfully deleted');
INSERT INTO `LocalizationKeys` VALUES (1252, 6, '_Objects successfully deleted from');
INSERT INTO `LocalizationKeys` VALUES (1253, 6, '_Object successfully deleted');
INSERT INTO `LocalizationKeys` VALUES (1254, 6, '_Object successfully uploaded');
INSERT INTO `LocalizationKeys` VALUES (1255, 6, '_Cannot delete some objects');
INSERT INTO `LocalizationKeys` VALUES (1256, 6, '_Cannot delete some objects from');
INSERT INTO `LocalizationKeys` VALUES (1257, 6, '_Cannot delete object');
INSERT INTO `LocalizationKeys` VALUES (1258, 6, '_Alboms successfully deleted');
INSERT INTO `LocalizationKeys` VALUES (1260, 6, '_FAILED_TO_ADD_ALBOM');
INSERT INTO `LocalizationKeys` VALUES (1261, 6, '_FAILED_TO_UPLOAD_FILE');
INSERT INTO `LocalizationKeys` VALUES (1262, 6, '_VIDEO_DISABLED');
INSERT INTO `LocalizationKeys` VALUES (1263, 6, '_AUDIO_DISABLED');
INSERT INTO `LocalizationKeys` VALUES (1264, 6, '_ERROR_WHILE_PROCESSING');
INSERT INTO `LocalizationKeys` VALUES (1267, 6, '_ALBOMS_MAX_REACHED');
INSERT INTO `LocalizationKeys` VALUES (1268, 6, '_OBJECTS_MAX_REACHED');
INSERT INTO `LocalizationKeys` VALUES (1269, 6, '_SIZE_TOO_BIG');
INSERT INTO `LocalizationKeys` VALUES (1271, 6, '_View alboms');
INSERT INTO `LocalizationKeys` VALUES (1272, 6, '_View objects');
INSERT INTO `LocalizationKeys` VALUES (1273, 6, '_Alboms:');
INSERT INTO `LocalizationKeys` VALUES (1274, 6, '_Failed to apply changes');
INSERT INTO `LocalizationKeys` VALUES (1275, 6, '_Cannot delete some alboms');
INSERT INTO `LocalizationKeys` VALUES (1276, 6, '_Cannot delete albom');
INSERT INTO `LocalizationKeys` VALUES (1277, 6, '_Object');
INSERT INTO `LocalizationKeys` VALUES (1278, 6, '_Objects:');
INSERT INTO `LocalizationKeys` VALUES (1279, 6, '_Object moved up');
INSERT INTO `LocalizationKeys` VALUES (1280, 6, '_Object moved down');
INSERT INTO `LocalizationKeys` VALUES (1281, 6, '_Failed to move object');
INSERT INTO `LocalizationKeys` VALUES (1282, 6, '_Cannot copy file');
INSERT INTO `LocalizationKeys` VALUES (1283, 6, '_Object updated');
INSERT INTO `LocalizationKeys` VALUES (1284, 6, '_No objects to approve');
INSERT INTO `LocalizationKeys` VALUES (1287, 6, '_Objects approved');
INSERT INTO `LocalizationKeys` VALUES (1288, 6, '_move_up_object_alt');
INSERT INTO `LocalizationKeys` VALUES (1289, 6, '_move_down_object_alt');
INSERT INTO `LocalizationKeys` VALUES (1290, 6, '_edit_object_alt');
INSERT INTO `LocalizationKeys` VALUES (1291, 6, '_delete_object_alt');
INSERT INTO `LocalizationKeys` VALUES (1292, 5, '_Delete entry');
INSERT INTO `LocalizationKeys` VALUES (1293, 5, '_Edit entry');
INSERT INTO `LocalizationKeys` VALUES (1294, 5, '_Write comment');
INSERT INTO `LocalizationKeys` VALUES (1295, 5, '_No entries found');
INSERT INTO `LocalizationKeys` VALUES (1296, 5, '_comments');
INSERT INTO `LocalizationKeys` VALUES (1297, 5, '_comment');
INSERT INTO `LocalizationKeys` VALUES (1298, 11, '__Average');
INSERT INTO `LocalizationKeys` VALUES (1299, 11, '__Ample');
INSERT INTO `LocalizationKeys` VALUES (1300, 11, '__Athletic');
INSERT INTO `LocalizationKeys` VALUES (1301, 11, '__Cuddly');
INSERT INTO `LocalizationKeys` VALUES (1302, 11, '__Slim');
INSERT INTO `LocalizationKeys` VALUES (1303, 11, '__Very Cuddly');
INSERT INTO `LocalizationKeys` VALUES (1304, 12, '__Afghanistan');
INSERT INTO `LocalizationKeys` VALUES (1305, 12, '__Albania');
INSERT INTO `LocalizationKeys` VALUES (1306, 12, '__Algeria');
INSERT INTO `LocalizationKeys` VALUES (1307, 12, '__American Samoa');
INSERT INTO `LocalizationKeys` VALUES (1308, 12, '__Andorra');
INSERT INTO `LocalizationKeys` VALUES (1309, 12, '__Angola');
INSERT INTO `LocalizationKeys` VALUES (1310, 12, '__Anguilla');
INSERT INTO `LocalizationKeys` VALUES (1311, 12, '__Antarctica');
INSERT INTO `LocalizationKeys` VALUES (1312, 12, '__Antigua and Barbuda');
INSERT INTO `LocalizationKeys` VALUES (1313, 12, '__Argentina');
INSERT INTO `LocalizationKeys` VALUES (1314, 12, '__Armenia');
INSERT INTO `LocalizationKeys` VALUES (1315, 12, '__Aruba');
INSERT INTO `LocalizationKeys` VALUES (1316, 12, '__Australia');
INSERT INTO `LocalizationKeys` VALUES (1317, 12, '__Austria');
INSERT INTO `LocalizationKeys` VALUES (1318, 12, '__Azerbaijan');
INSERT INTO `LocalizationKeys` VALUES (1319, 12, '__Bahamas');
INSERT INTO `LocalizationKeys` VALUES (1320, 12, '__Bahrain');
INSERT INTO `LocalizationKeys` VALUES (1321, 12, '__Bangladesh');
INSERT INTO `LocalizationKeys` VALUES (1322, 12, '__Barbados');
INSERT INTO `LocalizationKeys` VALUES (1323, 12, '__Belarus');
INSERT INTO `LocalizationKeys` VALUES (1324, 12, '__Belgium');
INSERT INTO `LocalizationKeys` VALUES (1325, 12, '__Belize');
INSERT INTO `LocalizationKeys` VALUES (1326, 12, '__Benin');
INSERT INTO `LocalizationKeys` VALUES (1327, 12, '__Bermuda');
INSERT INTO `LocalizationKeys` VALUES (1328, 12, '__Bhutan');
INSERT INTO `LocalizationKeys` VALUES (1329, 12, '__Bolivia');
INSERT INTO `LocalizationKeys` VALUES (1330, 12, '__Bosnia/Herzegowina');
INSERT INTO `LocalizationKeys` VALUES (1331, 12, '__Botswana');
INSERT INTO `LocalizationKeys` VALUES (1332, 12, '__Bouvet Island');
INSERT INTO `LocalizationKeys` VALUES (1333, 12, '__Brazil');
INSERT INTO `LocalizationKeys` VALUES (1334, 12, '__British Ind. Ocean Terr.');
INSERT INTO `LocalizationKeys` VALUES (1335, 12, '__British Ind. Ocean');
INSERT INTO `LocalizationKeys` VALUES (1336, 12, '__Brunei Darussalam');
INSERT INTO `LocalizationKeys` VALUES (1337, 12, '__Bulgaria');
INSERT INTO `LocalizationKeys` VALUES (1338, 12, '__Burkina Faso');
INSERT INTO `LocalizationKeys` VALUES (1339, 12, '__Burundi');
INSERT INTO `LocalizationKeys` VALUES (1340, 12, '__Cambodia');
INSERT INTO `LocalizationKeys` VALUES (1341, 12, '__Cameroon');
INSERT INTO `LocalizationKeys` VALUES (1342, 12, '__Cape Verde');
INSERT INTO `LocalizationKeys` VALUES (1343, 12, '__Cayman Islands');
INSERT INTO `LocalizationKeys` VALUES (1344, 12, '__Central African Rep.');
INSERT INTO `LocalizationKeys` VALUES (1345, 12, '__Chad');
INSERT INTO `LocalizationKeys` VALUES (1346, 12, '__Canada');
INSERT INTO `LocalizationKeys` VALUES (1347, 12, '__Chile');
INSERT INTO `LocalizationKeys` VALUES (1348, 12, '__China');
INSERT INTO `LocalizationKeys` VALUES (1349, 12, '__Christmas Island');
INSERT INTO `LocalizationKeys` VALUES (1350, 12, '__Cocoa (Keeling) Is.');
INSERT INTO `LocalizationKeys` VALUES (1351, 12, '__Colombia');
INSERT INTO `LocalizationKeys` VALUES (1352, 12, '__Comoros');
INSERT INTO `LocalizationKeys` VALUES (1353, 12, '__Congo');
INSERT INTO `LocalizationKeys` VALUES (1354, 12, '__Cook Islands');
INSERT INTO `LocalizationKeys` VALUES (1355, 12, '__Costa Rica');
INSERT INTO `LocalizationKeys` VALUES (1356, 12, '__Cote Divoire');
INSERT INTO `LocalizationKeys` VALUES (1357, 12, '__Croatia');
INSERT INTO `LocalizationKeys` VALUES (1358, 12, '__Cuba');
INSERT INTO `LocalizationKeys` VALUES (1359, 12, '__Cyprus');
INSERT INTO `LocalizationKeys` VALUES (1360, 12, '__Czech Republic');
INSERT INTO `LocalizationKeys` VALUES (1361, 12, '__Denmark');
INSERT INTO `LocalizationKeys` VALUES (1362, 12, '__Djibouti');
INSERT INTO `LocalizationKeys` VALUES (1363, 12, '__Dominica');
INSERT INTO `LocalizationKeys` VALUES (1364, 12, '__Dominican Republic');
INSERT INTO `LocalizationKeys` VALUES (1365, 12, '__East Timor');
INSERT INTO `LocalizationKeys` VALUES (1366, 12, '__Ecuador');
INSERT INTO `LocalizationKeys` VALUES (1367, 12, '__Egypt');
INSERT INTO `LocalizationKeys` VALUES (1368, 12, '__El Salvador');
INSERT INTO `LocalizationKeys` VALUES (1369, 12, '__Equatorial Guinea');
INSERT INTO `LocalizationKeys` VALUES (1370, 12, '__Eritrea');
INSERT INTO `LocalizationKeys` VALUES (1371, 12, '__Estonia');
INSERT INTO `LocalizationKeys` VALUES (1372, 12, '__Ethiopia');
INSERT INTO `LocalizationKeys` VALUES (1373, 12, '__Falkland Islands');
INSERT INTO `LocalizationKeys` VALUES (1374, 12, '__Faroe Islands');
INSERT INTO `LocalizationKeys` VALUES (1375, 12, '__Fiji');
INSERT INTO `LocalizationKeys` VALUES (1376, 12, '__Finland');
INSERT INTO `LocalizationKeys` VALUES (1377, 12, '__France');
INSERT INTO `LocalizationKeys` VALUES (1378, 12, '__Gabon');
INSERT INTO `LocalizationKeys` VALUES (1379, 12, '__Gambia');
INSERT INTO `LocalizationKeys` VALUES (1380, 12, '__Georgia');
INSERT INTO `LocalizationKeys` VALUES (1381, 12, '__Germany');
INSERT INTO `LocalizationKeys` VALUES (1382, 12, '__Ghana');
INSERT INTO `LocalizationKeys` VALUES (1383, 12, '__Gibraltar');
INSERT INTO `LocalizationKeys` VALUES (1384, 12, '__Greece');
INSERT INTO `LocalizationKeys` VALUES (1385, 12, '__Greenland');
INSERT INTO `LocalizationKeys` VALUES (1386, 12, '__Grenada');
INSERT INTO `LocalizationKeys` VALUES (1387, 12, '__Guadeloupe');
INSERT INTO `LocalizationKeys` VALUES (1388, 12, '__Guam');
INSERT INTO `LocalizationKeys` VALUES (1389, 12, '__Guatemala');
INSERT INTO `LocalizationKeys` VALUES (1390, 12, '__Guinea');
INSERT INTO `LocalizationKeys` VALUES (1391, 12, '__Guinea-Bissau');
INSERT INTO `LocalizationKeys` VALUES (1392, 12, '__Guyana');
INSERT INTO `LocalizationKeys` VALUES (1393, 12, '__Haiti');
INSERT INTO `LocalizationKeys` VALUES (1394, 12, '__Honduras');
INSERT INTO `LocalizationKeys` VALUES (1395, 12, '__Hong Kong');
INSERT INTO `LocalizationKeys` VALUES (1396, 12, '__Hungary');
INSERT INTO `LocalizationKeys` VALUES (1397, 12, '__Iceland');
INSERT INTO `LocalizationKeys` VALUES (1398, 12, '__India');
INSERT INTO `LocalizationKeys` VALUES (1399, 12, '__Indonesia');
INSERT INTO `LocalizationKeys` VALUES (1400, 12, '__Iran');
INSERT INTO `LocalizationKeys` VALUES (1401, 12, '__Iraq');
INSERT INTO `LocalizationKeys` VALUES (1402, 12, '__Ireland');
INSERT INTO `LocalizationKeys` VALUES (1403, 12, '__Israel');
INSERT INTO `LocalizationKeys` VALUES (1404, 12, '__Italy');
INSERT INTO `LocalizationKeys` VALUES (1405, 12, '__Jamaica');
INSERT INTO `LocalizationKeys` VALUES (1406, 12, '__Japan');
INSERT INTO `LocalizationKeys` VALUES (1407, 12, '__Jordan');
INSERT INTO `LocalizationKeys` VALUES (1408, 12, '__Kazakhstan');
INSERT INTO `LocalizationKeys` VALUES (1409, 12, '__Kenya');
INSERT INTO `LocalizationKeys` VALUES (1410, 12, '__Kiribati');
INSERT INTO `LocalizationKeys` VALUES (1411, 12, '__Korea');
INSERT INTO `LocalizationKeys` VALUES (1412, 12, '__Kuwait');
INSERT INTO `LocalizationKeys` VALUES (1413, 12, '__Kyrgyzstan');
INSERT INTO `LocalizationKeys` VALUES (1414, 12, '__Lao');
INSERT INTO `LocalizationKeys` VALUES (1415, 12, '__Latvia');
INSERT INTO `LocalizationKeys` VALUES (1416, 12, '__Lebanon');
INSERT INTO `LocalizationKeys` VALUES (1417, 12, '__Lesotho');
INSERT INTO `LocalizationKeys` VALUES (1418, 12, '__Liberia');
INSERT INTO `LocalizationKeys` VALUES (1419, 12, '__Liechtenstein');
INSERT INTO `LocalizationKeys` VALUES (1420, 12, '__Lithuania');
INSERT INTO `LocalizationKeys` VALUES (1421, 12, '__Luxembourg');
INSERT INTO `LocalizationKeys` VALUES (1422, 12, '__Macau');
INSERT INTO `LocalizationKeys` VALUES (1423, 12, '__Macedonia');
INSERT INTO `LocalizationKeys` VALUES (1424, 12, '__Madagascar');
INSERT INTO `LocalizationKeys` VALUES (1425, 12, '__Malawi');
INSERT INTO `LocalizationKeys` VALUES (1426, 12, '__Malaysia');
INSERT INTO `LocalizationKeys` VALUES (1427, 12, '__Maldives');
INSERT INTO `LocalizationKeys` VALUES (1428, 12, '__Mali');
INSERT INTO `LocalizationKeys` VALUES (1429, 12, '__Malta');
INSERT INTO `LocalizationKeys` VALUES (1430, 12, '__Marshall Islands');
INSERT INTO `LocalizationKeys` VALUES (1431, 12, '__Martinique');
INSERT INTO `LocalizationKeys` VALUES (1432, 12, '__Mauritania');
INSERT INTO `LocalizationKeys` VALUES (1433, 12, '__Mauritius');
INSERT INTO `LocalizationKeys` VALUES (1434, 12, '__Mayotte');
INSERT INTO `LocalizationKeys` VALUES (1435, 12, '__Mexico');
INSERT INTO `LocalizationKeys` VALUES (1436, 12, '__Micronesia');
INSERT INTO `LocalizationKeys` VALUES (1437, 12, '__Moldova');
INSERT INTO `LocalizationKeys` VALUES (1438, 12, '__Monaco');
INSERT INTO `LocalizationKeys` VALUES (1439, 12, '__Mongolia');
INSERT INTO `LocalizationKeys` VALUES (1440, 12, '__Montserrat');
INSERT INTO `LocalizationKeys` VALUES (1441, 12, '__Morocco');
INSERT INTO `LocalizationKeys` VALUES (1442, 12, '__Mozambique');
INSERT INTO `LocalizationKeys` VALUES (1443, 12, '__Myanmar');
INSERT INTO `LocalizationKeys` VALUES (1444, 12, '__Namibia');
INSERT INTO `LocalizationKeys` VALUES (1445, 12, '__Nauru');
INSERT INTO `LocalizationKeys` VALUES (1446, 12, '__Nepal');
INSERT INTO `LocalizationKeys` VALUES (1447, 12, '__Netherlands');
INSERT INTO `LocalizationKeys` VALUES (1448, 12, '__New Caledonia');
INSERT INTO `LocalizationKeys` VALUES (1449, 12, '__New Zealand');
INSERT INTO `LocalizationKeys` VALUES (1450, 12, '__Nicaragua');
INSERT INTO `LocalizationKeys` VALUES (1451, 12, '__Niger');
INSERT INTO `LocalizationKeys` VALUES (1452, 12, '__Nigeria');
INSERT INTO `LocalizationKeys` VALUES (1453, 12, '__Niue');
INSERT INTO `LocalizationKeys` VALUES (1454, 12, '__Norfolk Island');
INSERT INTO `LocalizationKeys` VALUES (1455, 12, '__Norway');
INSERT INTO `LocalizationKeys` VALUES (1456, 12, '_no data given');
INSERT INTO `LocalizationKeys` VALUES (1457, 12, '__Oman');
INSERT INTO `LocalizationKeys` VALUES (1458, 12, '__Pakistan');
INSERT INTO `LocalizationKeys` VALUES (1459, 12, '__Palau');
INSERT INTO `LocalizationKeys` VALUES (1460, 12, '__Panama');
INSERT INTO `LocalizationKeys` VALUES (1461, 12, '__Papua New Guinea');
INSERT INTO `LocalizationKeys` VALUES (1462, 12, '__Paraguay');
INSERT INTO `LocalizationKeys` VALUES (1463, 12, '__Peru');
INSERT INTO `LocalizationKeys` VALUES (1464, 12, '__Philippines');
INSERT INTO `LocalizationKeys` VALUES (1465, 12, '__Pitcairn');
INSERT INTO `LocalizationKeys` VALUES (1466, 12, '__Poland');
INSERT INTO `LocalizationKeys` VALUES (1467, 12, '__Portugal');
INSERT INTO `LocalizationKeys` VALUES (1468, 12, '__Puerto Rico');
INSERT INTO `LocalizationKeys` VALUES (1469, 12, '__Qatar');
INSERT INTO `LocalizationKeys` VALUES (1470, 12, '__Reunion');
INSERT INTO `LocalizationKeys` VALUES (1471, 12, '__Romania');
INSERT INTO `LocalizationKeys` VALUES (1472, 12, '__Russia');
INSERT INTO `LocalizationKeys` VALUES (1473, 12, '__Rwanda');
INSERT INTO `LocalizationKeys` VALUES (1474, 12, '__Saint Lucia');
INSERT INTO `LocalizationKeys` VALUES (1475, 12, '__Samoa');
INSERT INTO `LocalizationKeys` VALUES (1476, 12, '__San Marino');
INSERT INTO `LocalizationKeys` VALUES (1477, 12, '__Saudi Arabia');
INSERT INTO `LocalizationKeys` VALUES (1478, 12, '__Senegal');
INSERT INTO `LocalizationKeys` VALUES (1479, 12, '__Seychelles');
INSERT INTO `LocalizationKeys` VALUES (1480, 12, '__Sierra Leone');
INSERT INTO `LocalizationKeys` VALUES (1481, 12, '__Singapore');
INSERT INTO `LocalizationKeys` VALUES (1482, 12, '__Slovakia');
INSERT INTO `LocalizationKeys` VALUES (1483, 12, '__Solomon Islands');
INSERT INTO `LocalizationKeys` VALUES (1484, 12, '__Somalia');
INSERT INTO `LocalizationKeys` VALUES (1485, 12, '__South Africa');
INSERT INTO `LocalizationKeys` VALUES (1486, 12, '__Spain');
INSERT INTO `LocalizationKeys` VALUES (1487, 12, '__Sri Lanka');
INSERT INTO `LocalizationKeys` VALUES (1488, 12, '__St. Helena');
INSERT INTO `LocalizationKeys` VALUES (1489, 12, '__Sudan');
INSERT INTO `LocalizationKeys` VALUES (1490, 12, '__Suriname');
INSERT INTO `LocalizationKeys` VALUES (1491, 12, '__Swaziland');
INSERT INTO `LocalizationKeys` VALUES (1492, 12, '__Sweden');
INSERT INTO `LocalizationKeys` VALUES (1493, 12, '__Switzerland');
INSERT INTO `LocalizationKeys` VALUES (1494, 12, '__Syria');
INSERT INTO `LocalizationKeys` VALUES (1495, 12, '__Taiwan');
INSERT INTO `LocalizationKeys` VALUES (1496, 12, '__Tajikistan');
INSERT INTO `LocalizationKeys` VALUES (1497, 12, '__Tanzania');
INSERT INTO `LocalizationKeys` VALUES (1498, 12, '__Thailand');
INSERT INTO `LocalizationKeys` VALUES (1499, 12, '__Togo');
INSERT INTO `LocalizationKeys` VALUES (1500, 12, '__Tokelau');
INSERT INTO `LocalizationKeys` VALUES (1501, 12, '__Tonga');
INSERT INTO `LocalizationKeys` VALUES (1502, 12, '__Trinidad and Tobago');
INSERT INTO `LocalizationKeys` VALUES (1503, 12, '__Tunisia');
INSERT INTO `LocalizationKeys` VALUES (1504, 12, '__Turkey');
INSERT INTO `LocalizationKeys` VALUES (1505, 12, '__Turkmenistan');
INSERT INTO `LocalizationKeys` VALUES (1506, 12, '__Tuvalu');
INSERT INTO `LocalizationKeys` VALUES (1507, 12, '__Uganda');
INSERT INTO `LocalizationKeys` VALUES (1508, 12, '__Ukraine');
INSERT INTO `LocalizationKeys` VALUES (1509, 12, '__United Arab Emirates');
INSERT INTO `LocalizationKeys` VALUES (1510, 12, '__United Kingdom');
INSERT INTO `LocalizationKeys` VALUES (1511, 12, '__USA');
INSERT INTO `LocalizationKeys` VALUES (1512, 12, '__Uruguay');
INSERT INTO `LocalizationKeys` VALUES (1513, 12, '__Uzbekistan');
INSERT INTO `LocalizationKeys` VALUES (1514, 12, '__Vanuatu');
INSERT INTO `LocalizationKeys` VALUES (1515, 12, '__Vatican');
INSERT INTO `LocalizationKeys` VALUES (1516, 12, '__Venezuela');
INSERT INTO `LocalizationKeys` VALUES (1517, 12, '__Viet Nam');
INSERT INTO `LocalizationKeys` VALUES (1518, 12, '__Virgin Islands');
INSERT INTO `LocalizationKeys` VALUES (1519, 12, '__Western Sahara');
INSERT INTO `LocalizationKeys` VALUES (1520, 12, '__Yemen');
INSERT INTO `LocalizationKeys` VALUES (1521, 12, '__Yugoslavia');
INSERT INTO `LocalizationKeys` VALUES (1522, 12, '__Zaire');
INSERT INTO `LocalizationKeys` VALUES (1523, 12, '__Zambia');
INSERT INTO `LocalizationKeys` VALUES (1524, 12, '__Zimbabwe');
INSERT INTO `LocalizationKeys` VALUES (1810, 12, '__Netherlands Antilles');
INSERT INTO `LocalizationKeys` VALUES (1811, 12, '__Bosnia and Herzegovina');
INSERT INTO `LocalizationKeys` VALUES (1812, 12, '__The Bahamas');
INSERT INTO `LocalizationKeys` VALUES (1813, 12, '__Cocos (Keeling) Islands');
INSERT INTO `LocalizationKeys` VALUES (1814, 12, '__Congo, Democratic Republic of the');
INSERT INTO `LocalizationKeys` VALUES (1815, 12, '__Central African Republic');
INSERT INTO `LocalizationKeys` VALUES (1816, 12, '__Congo, Republic of the');
INSERT INTO `LocalizationKeys` VALUES (1817, 12, '__Cote d''Ivoire');
INSERT INTO `LocalizationKeys` VALUES (1818, 12, '__Falkland Islands (Islas Malvinas)');
INSERT INTO `LocalizationKeys` VALUES (1819, 12, '__Micronesia, Federated States of');
INSERT INTO `LocalizationKeys` VALUES (1820, 12, '__French Guiana');
INSERT INTO `LocalizationKeys` VALUES (1821, 12, '__The Gambia');
INSERT INTO `LocalizationKeys` VALUES (1822, 12, '__South Georgia and the South Sandwich Islands');
INSERT INTO `LocalizationKeys` VALUES (1823, 12, '__Hong Kong (SAR)');
INSERT INTO `LocalizationKeys` VALUES (1824, 12, '__Heard Island and McDonald Islands');
INSERT INTO `LocalizationKeys` VALUES (1825, 12, '__British Indian Ocean Territory');
INSERT INTO `LocalizationKeys` VALUES (1826, 12, '__Saint Kitts and Nevis');
INSERT INTO `LocalizationKeys` VALUES (1827, 12, '__Korea, North');
INSERT INTO `LocalizationKeys` VALUES (1828, 12, '__Korea, South');
INSERT INTO `LocalizationKeys` VALUES (1829, 12, '__Laos');
INSERT INTO `LocalizationKeys` VALUES (1830, 12, '__Libya');
INSERT INTO `LocalizationKeys` VALUES (1831, 12, '__Macedonia, The Former Yugoslav Republic of');
INSERT INTO `LocalizationKeys` VALUES (1832, 12, '__Burma');
INSERT INTO `LocalizationKeys` VALUES (1833, 12, '__Macao');
INSERT INTO `LocalizationKeys` VALUES (1834, 12, '__Northern Mariana Islands');
INSERT INTO `LocalizationKeys` VALUES (1835, 12, '__French Polynesia');
INSERT INTO `LocalizationKeys` VALUES (1836, 12, '__Saint Pierre and Miquelon');
INSERT INTO `LocalizationKeys` VALUES (1837, 12, '__Pitcairn Islands');
INSERT INTO `LocalizationKeys` VALUES (1838, 12, '__Palestinian Territory, Occupied');
INSERT INTO `LocalizationKeys` VALUES (1839, 12, '__Saint Helena');
INSERT INTO `LocalizationKeys` VALUES (1840, 12, '__Slovenia');
INSERT INTO `LocalizationKeys` VALUES (1841, 12, '__Svalbard');
INSERT INTO `LocalizationKeys` VALUES (1842, 12, '__Sao Tome and Principe');
INSERT INTO `LocalizationKeys` VALUES (1843, 12, '__Turks and Caicos Islands');
INSERT INTO `LocalizationKeys` VALUES (1844, 12, '__French Southern and Antarctic Lands');
INSERT INTO `LocalizationKeys` VALUES (1845, 12, '__United States Minor Outlying Islands');
INSERT INTO `LocalizationKeys` VALUES (1846, 12, '__United States');
INSERT INTO `LocalizationKeys` VALUES (1847, 12, '__Holy See (Vatican City)');
INSERT INTO `LocalizationKeys` VALUES (1848, 12, '__Saint Vincent and the Grenadines');
INSERT INTO `LocalizationKeys` VALUES (1849, 12, '__British Virgin Islands');
INSERT INTO `LocalizationKeys` VALUES (1850, 12, '__Vietnam');
INSERT INTO `LocalizationKeys` VALUES (1851, 12, '__Wallis and Futuna');
INSERT INTO `LocalizationKeys` VALUES (1525, 13, '__High School graduate');
INSERT INTO `LocalizationKeys` VALUES (1526, 13, '__Some college');
INSERT INTO `LocalizationKeys` VALUES (1527, 13, '__College student');
INSERT INTO `LocalizationKeys` VALUES (1528, 13, '__AA (2 years college)');
INSERT INTO `LocalizationKeys` VALUES (1529, 13, '__BA/BS (4 years college)');
INSERT INTO `LocalizationKeys` VALUES (1530, 13, '__Some grad school');
INSERT INTO `LocalizationKeys` VALUES (1531, 13, '__Grad school student');
INSERT INTO `LocalizationKeys` VALUES (1532, 13, '__MA/MS/MBA');
INSERT INTO `LocalizationKeys` VALUES (1533, 13, '__PhD/Post doctorate');
INSERT INTO `LocalizationKeys` VALUES (1534, 13, '__JD');
INSERT INTO `LocalizationKeys` VALUES (1535, 14, '__African');
INSERT INTO `LocalizationKeys` VALUES (1536, 14, '__African American');
INSERT INTO `LocalizationKeys` VALUES (1537, 14, '__Asian');
INSERT INTO `LocalizationKeys` VALUES (1538, 14, '__Caucasian');
INSERT INTO `LocalizationKeys` VALUES (1539, 14, '__East Indian');
INSERT INTO `LocalizationKeys` VALUES (1540, 14, '__Hispanic');
INSERT INTO `LocalizationKeys` VALUES (1541, 14, '__Indian');
INSERT INTO `LocalizationKeys` VALUES (1542, 14, '__Latino');
INSERT INTO `LocalizationKeys` VALUES (1543, 14, '__Mediterranean');
INSERT INTO `LocalizationKeys` VALUES (1544, 14, '__Middle Eastern');
INSERT INTO `LocalizationKeys` VALUES (1545, 14, '__Mixed');
INSERT INTO `LocalizationKeys` VALUES (1546, 18, '__4''7" (140cm) or below');
INSERT INTO `LocalizationKeys` VALUES (1547, 18, '__4''8" - 4''11" (141-150cm)');
INSERT INTO `LocalizationKeys` VALUES (1548, 18, '__5''0" - 5''3" (151-160cm)');
INSERT INTO `LocalizationKeys` VALUES (1549, 18, '__5''4" - 5''7" (161-170cm)');
INSERT INTO `LocalizationKeys` VALUES (1550, 18, '__5''8" - 5''11" (171-180cm)');
INSERT INTO `LocalizationKeys` VALUES (1551, 18, '__6''0" - 6''3" (181-190cm)');
INSERT INTO `LocalizationKeys` VALUES (1552, 18, '__6''4" (191cm) or above');
INSERT INTO `LocalizationKeys` VALUES (1553, 15, '__$10,000/year and less');
INSERT INTO `LocalizationKeys` VALUES (1554, 15, '__$10,000-$30,000/year');
INSERT INTO `LocalizationKeys` VALUES (1555, 15, '__$30,000-$50,000/year');
INSERT INTO `LocalizationKeys` VALUES (1556, 15, '__$50,000-$70,000/year');
INSERT INTO `LocalizationKeys` VALUES (1557, 15, '__$70,000/year and more');
INSERT INTO `LocalizationKeys` VALUES (1558, 16, '__English');
INSERT INTO `LocalizationKeys` VALUES (1559, 16, '__Afrikaans');
INSERT INTO `LocalizationKeys` VALUES (1560, 16, '__Arabic');
INSERT INTO `LocalizationKeys` VALUES (1561, 16, '__Bulgarian');
INSERT INTO `LocalizationKeys` VALUES (1562, 16, '__Burmese');
INSERT INTO `LocalizationKeys` VALUES (1563, 16, '__Cantonese');
INSERT INTO `LocalizationKeys` VALUES (1564, 16, '__Croatian');
INSERT INTO `LocalizationKeys` VALUES (1565, 16, '__Danish');
INSERT INTO `LocalizationKeys` VALUES (1566, 16, '_Database Error');
INSERT INTO `LocalizationKeys` VALUES (1567, 16, '__Dutch');
INSERT INTO `LocalizationKeys` VALUES (1568, 16, '__Esperanto');
INSERT INTO `LocalizationKeys` VALUES (1569, 16, '__Estonian');
INSERT INTO `LocalizationKeys` VALUES (1570, 16, '__Finnish');
INSERT INTO `LocalizationKeys` VALUES (1571, 16, '__French');
INSERT INTO `LocalizationKeys` VALUES (1572, 16, '__German');
INSERT INTO `LocalizationKeys` VALUES (1573, 16, '__Greek');
INSERT INTO `LocalizationKeys` VALUES (1574, 16, '__Gujrati');
INSERT INTO `LocalizationKeys` VALUES (1575, 16, '__Hebrew');
INSERT INTO `LocalizationKeys` VALUES (1576, 16, '__Hindi');
INSERT INTO `LocalizationKeys` VALUES (1577, 16, '__Hungarian');
INSERT INTO `LocalizationKeys` VALUES (1578, 16, '__Icelandic');
INSERT INTO `LocalizationKeys` VALUES (1579, 16, '__Indonesian');
INSERT INTO `LocalizationKeys` VALUES (1580, 16, '__Italian');
INSERT INTO `LocalizationKeys` VALUES (1581, 16, '__Japanese');
INSERT INTO `LocalizationKeys` VALUES (1582, 16, '__Korean');
INSERT INTO `LocalizationKeys` VALUES (1583, 16, '__Latvian');
INSERT INTO `LocalizationKeys` VALUES (1584, 16, '__Lithuanian');
INSERT INTO `LocalizationKeys` VALUES (1585, 16, '__Malay');
INSERT INTO `LocalizationKeys` VALUES (1586, 16, '__Mandarin');
INSERT INTO `LocalizationKeys` VALUES (1587, 16, '__Marathi');
INSERT INTO `LocalizationKeys` VALUES (1588, 16, '__Moldovian');
INSERT INTO `LocalizationKeys` VALUES (1589, 16, '__Nepalese');
INSERT INTO `LocalizationKeys` VALUES (1590, 16, '__Norwegian');
INSERT INTO `LocalizationKeys` VALUES (1591, 16, '__Persian');
INSERT INTO `LocalizationKeys` VALUES (1592, 16, '__Polish');
INSERT INTO `LocalizationKeys` VALUES (1593, 16, '__Portuguese');
INSERT INTO `LocalizationKeys` VALUES (1594, 16, '__Punjabi');
INSERT INTO `LocalizationKeys` VALUES (1595, 16, '__Romanian');
INSERT INTO `LocalizationKeys` VALUES (1596, 16, '__Russian');
INSERT INTO `LocalizationKeys` VALUES (1597, 16, '__Serbian');
INSERT INTO `LocalizationKeys` VALUES (1598, 16, '__Spanish');
INSERT INTO `LocalizationKeys` VALUES (1599, 16, '__Swedish');
INSERT INTO `LocalizationKeys` VALUES (1600, 16, '__Tagalog');
INSERT INTO `LocalizationKeys` VALUES (1601, 16, '__Taiwanese');
INSERT INTO `LocalizationKeys` VALUES (1602, 16, '__Tamil');
INSERT INTO `LocalizationKeys` VALUES (1603, 16, '__Telugu');
INSERT INTO `LocalizationKeys` VALUES (1604, 16, '__Thai');
INSERT INTO `LocalizationKeys` VALUES (1605, 16, '__Tongan');
INSERT INTO `LocalizationKeys` VALUES (1606, 16, '__Turkish');
INSERT INTO `LocalizationKeys` VALUES (1607, 16, '__Ukrainian');
INSERT INTO `LocalizationKeys` VALUES (1608, 16, '__Urdu');
INSERT INTO `LocalizationKeys` VALUES (1609, 16, '__Vietnamese');
INSERT INTO `LocalizationKeys` VALUES (1610, 16, '__Visayan');
INSERT INTO `LocalizationKeys` VALUES (1611, 17, '__Single');
INSERT INTO `LocalizationKeys` VALUES (1612, 17, '__Attached');
INSERT INTO `LocalizationKeys` VALUES (1613, 17, '__Divorced');
INSERT INTO `LocalizationKeys` VALUES (1614, 17, '__Married');
INSERT INTO `LocalizationKeys` VALUES (1615, 17, '__Separated');
INSERT INTO `LocalizationKeys` VALUES (1616, 17, '__Widow');
INSERT INTO `LocalizationKeys` VALUES (1617, 19, '__Unconfirmed');
INSERT INTO `LocalizationKeys` VALUES (1618, 19, '__Approval');
INSERT INTO `LocalizationKeys` VALUES (1619, 19, '__Active');
INSERT INTO `LocalizationKeys` VALUES (1620, 19, '__Suspended');
INSERT INTO `LocalizationKeys` VALUES (1621, 19, '__Rejected');
INSERT INTO `LocalizationKeys` VALUES (1622, 19, '_Unconfirmed');
INSERT INTO `LocalizationKeys` VALUES (1623, 19, '_Approval');
INSERT INTO `LocalizationKeys` VALUES (1624, 19, '_Active');
INSERT INTO `LocalizationKeys` VALUES (1625, 19, '_Suspended');
INSERT INTO `LocalizationKeys` VALUES (1626, 19, '_Rejected');
INSERT INTO `LocalizationKeys` VALUES (1627, 19, '_SelectType');
INSERT INTO `LocalizationKeys` VALUES (1628, 20, '__Activity Partner');
INSERT INTO `LocalizationKeys` VALUES (1629, 20, '__Casual');
INSERT INTO `LocalizationKeys` VALUES (1630, 20, '__Friendship');
INSERT INTO `LocalizationKeys` VALUES (1631, 20, '__Marriage');
INSERT INTO `LocalizationKeys` VALUES (1632, 20, '__Relationship');
INSERT INTO `LocalizationKeys` VALUES (1633, 20, '__Romance');
INSERT INTO `LocalizationKeys` VALUES (1634, 20, '__Travel Partner');
INSERT INTO `LocalizationKeys` VALUES (1635, 20, '__Pen Pal');
INSERT INTO `LocalizationKeys` VALUES (1636, 20, '__PenPal');
INSERT INTO `LocalizationKeys` VALUES (1637, 20, '_act');
INSERT INTO `LocalizationKeys` VALUES (1638, 20, '_cas');
INSERT INTO `LocalizationKeys` VALUES (1639, 20, '_fri');
INSERT INTO `LocalizationKeys` VALUES (1640, 20, '_mar');
INSERT INTO `LocalizationKeys` VALUES (1641, 20, '_rel');
INSERT INTO `LocalizationKeys` VALUES (1642, 20, '_rom');
INSERT INTO `LocalizationKeys` VALUES (1643, 20, '_tra');
INSERT INTO `LocalizationKeys` VALUES (1644, 20, '_pen');
INSERT INTO `LocalizationKeys` VALUES (1645, 21, '__Adventist');
INSERT INTO `LocalizationKeys` VALUES (1646, 21, '__Agnostic');
INSERT INTO `LocalizationKeys` VALUES (1647, 21, '__Atheist');
INSERT INTO `LocalizationKeys` VALUES (1648, 21, '__Baptist');
INSERT INTO `LocalizationKeys` VALUES (1649, 21, '__Buddhist');
INSERT INTO `LocalizationKeys` VALUES (1650, 21, '__Caodaism');
INSERT INTO `LocalizationKeys` VALUES (1651, 21, '__Catholic');
INSERT INTO `LocalizationKeys` VALUES (1652, 21, '__Christian');
INSERT INTO `LocalizationKeys` VALUES (1653, 21, '__Hindu');
INSERT INTO `LocalizationKeys` VALUES (1654, 21, '__Iskcon');
INSERT INTO `LocalizationKeys` VALUES (1655, 21, '__Jainism');
INSERT INTO `LocalizationKeys` VALUES (1656, 21, '__Jewish');
INSERT INTO `LocalizationKeys` VALUES (1657, 21, '__Methodist');
INSERT INTO `LocalizationKeys` VALUES (1658, 21, '__Mormon');
INSERT INTO `LocalizationKeys` VALUES (1659, 21, '__Moslem');
INSERT INTO `LocalizationKeys` VALUES (1660, 21, '__Orthodox');
INSERT INTO `LocalizationKeys` VALUES (1661, 21, '__Pentecostal');
INSERT INTO `LocalizationKeys` VALUES (1662, 21, '__Protestant');
INSERT INTO `LocalizationKeys` VALUES (1663, 21, '__Quaker');
INSERT INTO `LocalizationKeys` VALUES (1664, 21, '__Scientology');
INSERT INTO `LocalizationKeys` VALUES (1665, 21, '__Shinto');
INSERT INTO `LocalizationKeys` VALUES (1666, 21, '__Sikhism');
INSERT INTO `LocalizationKeys` VALUES (1667, 21, '__Spiritual');
INSERT INTO `LocalizationKeys` VALUES (1668, 21, '__Taoism');
INSERT INTO `LocalizationKeys` VALUES (1669, 21, '__Wiccan');
INSERT INTO `LocalizationKeys` VALUES (1670, 21, '__Other');
INSERT INTO `LocalizationKeys` VALUES (1671, 22, '__No');
INSERT INTO `LocalizationKeys` VALUES (1672, 22, '__Rarely');
INSERT INTO `LocalizationKeys` VALUES (1673, 22, '__Often');
INSERT INTO `LocalizationKeys` VALUES (1674, 22, '__Very often');
INSERT INTO `LocalizationKeys` VALUES (1675, 4, '_Allowed actions');
INSERT INTO `LocalizationKeys` VALUES (1676, 4, '_Action');
INSERT INTO `LocalizationKeys` VALUES (1677, 4, '_Times allowed');
INSERT INTO `LocalizationKeys` VALUES (1678, 4, '_Period (hours)');
INSERT INTO `LocalizationKeys` VALUES (1679, 4, '_Allowed Since');
INSERT INTO `LocalizationKeys` VALUES (1680, 4, '_Allowed Until');
INSERT INTO `LocalizationKeys` VALUES (1681, 4, '_No actions allowed for this membership');
INSERT INTO `LocalizationKeys` VALUES (1682, 4, '_no limit');
INSERT INTO `LocalizationKeys` VALUES (1683, 4, '_send kisses');
INSERT INTO `LocalizationKeys` VALUES (1684, 4, '_use chat');
INSERT INTO `LocalizationKeys` VALUES (1685, 4, '_use instant messenger');
INSERT INTO `LocalizationKeys` VALUES (1686, 4, '_view profiles');
INSERT INTO `LocalizationKeys` VALUES (1687, 4, '_use forum');
INSERT INTO `LocalizationKeys` VALUES (1688, 4, '_make search');
INSERT INTO `LocalizationKeys` VALUES (1689, 4, '_rate photos');
INSERT INTO `LocalizationKeys` VALUES (1690, 4, '_send messages');
INSERT INTO `LocalizationKeys` VALUES (1691, 4, '_view photos');
INSERT INTO `LocalizationKeys` VALUES (1692, 4, '_use Ray instant messenger');
INSERT INTO `LocalizationKeys` VALUES (1693, 4, '_use Ray video recorder');
INSERT INTO `LocalizationKeys` VALUES (1694, 4, '_use Ray chat');
INSERT INTO `LocalizationKeys` VALUES (1695, 4, '_use guestbook');
INSERT INTO `LocalizationKeys` VALUES (1696, 4, '_view other members'' guestbooks');
INSERT INTO `LocalizationKeys` VALUES (1697, 4, '_get other members'' emails');
INSERT INTO `LocalizationKeys` VALUES (1698, 100, '_Contact_us');
INSERT INTO `LocalizationKeys` VALUES (1699, 100, '_Rating');
INSERT INTO `LocalizationKeys` VALUES (1700, 100, '_ATT_MESSAGE_NONE');
INSERT INTO `LocalizationKeys` VALUES (1701, 100, '_ATT_VKISS_NONE');
INSERT INTO `LocalizationKeys` VALUES (1702, 100, '_ATT_FRIEND_NONE');
INSERT INTO `LocalizationKeys` VALUES (1703, 10, '_18-20');
INSERT INTO `LocalizationKeys` VALUES (1704, 10, '_21-25');
INSERT INTO `LocalizationKeys` VALUES (1705, 10, '_26-30');
INSERT INTO `LocalizationKeys` VALUES (1706, 10, '_31-35');
INSERT INTO `LocalizationKeys` VALUES (1707, 10, '_36-40');
INSERT INTO `LocalizationKeys` VALUES (1708, 10, '_41-45');
INSERT INTO `LocalizationKeys` VALUES (1709, 10, '_46-50');
INSERT INTO `LocalizationKeys` VALUES (1710, 10, '_51-55');
INSERT INTO `LocalizationKeys` VALUES (1711, 10, '_56-60');
INSERT INTO `LocalizationKeys` VALUES (1712, 10, '_61-65');
INSERT INTO `LocalizationKeys` VALUES (1713, 10, '_66-70');
INSERT INTO `LocalizationKeys` VALUES (1714, 10, '_71-75');
INSERT INTO `LocalizationKeys` VALUES (1715, 23, '_Aries');
INSERT INTO `LocalizationKeys` VALUES (1716, 23, '_Taurus');
INSERT INTO `LocalizationKeys` VALUES (1717, 23, '_Gemini');
INSERT INTO `LocalizationKeys` VALUES (1718, 23, '_Cancer');
INSERT INTO `LocalizationKeys` VALUES (1719, 23, '_Leo');
INSERT INTO `LocalizationKeys` VALUES (1720, 23, '_Virgo');
INSERT INTO `LocalizationKeys` VALUES (1721, 23, '_Libra');
INSERT INTO `LocalizationKeys` VALUES (1722, 23, '_Scorpio');
INSERT INTO `LocalizationKeys` VALUES (1723, 23, '_Sagittarius');
INSERT INTO `LocalizationKeys` VALUES (1724, 23, '_Capricorn');
INSERT INTO `LocalizationKeys` VALUES (1725, 23, '_Aquarius');
INSERT INTO `LocalizationKeys` VALUES (1726, 23, '_Pisces');
INSERT INTO `LocalizationKeys` VALUES (1727, 23, '_Zodiac');
INSERT INTO `LocalizationKeys` VALUES (1734, 2, '_Choose forum');
INSERT INTO `LocalizationKeys` VALUES (1735, 100, '_Module_access_error');
INSERT INTO `LocalizationKeys` VALUES (1736, 100, '_Choose the forum to log in');
INSERT INTO `LocalizationKeys` VALUES (1737, 100, '_Choose the forum from the following');
INSERT INTO `LocalizationKeys` VALUES (1738, 100, '_Dolphin Administrator');
INSERT INTO `LocalizationKeys` VALUES (1739, 2, '_GETMEM_H');
INSERT INTO `LocalizationKeys` VALUES (1740, 2, '_GETMEM_H1');
INSERT INTO `LocalizationKeys` VALUES (1741, 100, '_requires_N_members');
INSERT INTO `LocalizationKeys` VALUES (1742, 100, '_No forums installed');
INSERT INTO `LocalizationKeys` VALUES (1743, 100, '_No chats installed');
INSERT INTO `LocalizationKeys` VALUES (1744, 100, '_Click here to change your membership status');
INSERT INTO `LocalizationKeys` VALUES (1745, 7, '_SpeedDating events');
INSERT INTO `LocalizationKeys` VALUES (1746, 7, '_No events available');
INSERT INTO `LocalizationKeys` VALUES (1747, 7, '_SDating photo alt');
INSERT INTO `LocalizationKeys` VALUES (1748, 7, '_No photo');
INSERT INTO `LocalizationKeys` VALUES (1749, 7, '_Select events to show');
INSERT INTO `LocalizationKeys` VALUES (1750, 7, '_Show events by country');
INSERT INTO `LocalizationKeys` VALUES (1751, 7, '_Show all events');
INSERT INTO `LocalizationKeys` VALUES (1752, 7, '_Show info');
INSERT INTO `LocalizationKeys` VALUES (1753, 7, '_Participants');
INSERT INTO `LocalizationKeys` VALUES (1754, 7, '_Choose participants you liked');
INSERT INTO `LocalizationKeys` VALUES (1755, 7, '_Status message');
INSERT INTO `LocalizationKeys` VALUES (1756, 7, '_Appointed date/time');
INSERT INTO `LocalizationKeys` VALUES (1757, 7, '_Place');
INSERT INTO `LocalizationKeys` VALUES (1758, 7, '_There are no participants for this event');
INSERT INTO `LocalizationKeys` VALUES (1759, 7, '_You are not participant of specified event');
INSERT INTO `LocalizationKeys` VALUES (1760, 7, '_Apply choice');
INSERT INTO `LocalizationKeys` VALUES (1761, 7, '_Event is unavailable');
INSERT INTO `LocalizationKeys` VALUES (1762, 7, '_Event start');
INSERT INTO `LocalizationKeys` VALUES (1763, 7, '_Event end');
INSERT INTO `LocalizationKeys` VALUES (1764, 7, '_Ticket sale start');
INSERT INTO `LocalizationKeys` VALUES (1765, 7, '_Ticket sale end');
INSERT INTO `LocalizationKeys` VALUES (1766, 7, '_Responsible person');
INSERT INTO `LocalizationKeys` VALUES (1767, 7, '_Tickets left');
INSERT INTO `LocalizationKeys` VALUES (1768, 7, '_Ticket price');
INSERT INTO `LocalizationKeys` VALUES (1769, 7, '_Sale status');
INSERT INTO `LocalizationKeys` VALUES (1770, 7, '_Sale finished');
INSERT INTO `LocalizationKeys` VALUES (1771, 7, '_Sale not started yet');
INSERT INTO `LocalizationKeys` VALUES (1772, 7, '_No tickets left');
INSERT INTO `LocalizationKeys` VALUES (1773, 7, '_Event started');
INSERT INTO `LocalizationKeys` VALUES (1774, 7, '_Event finished');
INSERT INTO `LocalizationKeys` VALUES (1775, 7, '_You are participant of event');
INSERT INTO `LocalizationKeys` VALUES (1776, 7, '_You can buy the ticket');
INSERT INTO `LocalizationKeys` VALUES (1777, 7, '_Buy ticket');
INSERT INTO `LocalizationKeys` VALUES (1778, 7, '_Change');
INSERT INTO `LocalizationKeys` VALUES (1779, 7, '_Cant change participant UID');
INSERT INTO `LocalizationKeys` VALUES (1780, 7, '_UID already exists');
INSERT INTO `LocalizationKeys` VALUES (1781, 7, '_RESULT_SDATING_MAIL_NOT_SENT');
INSERT INTO `LocalizationKeys` VALUES (1782, 7, '_Event participants');
INSERT INTO `LocalizationKeys` VALUES (1783, 7, '_Event UID');
INSERT INTO `LocalizationKeys` VALUES (1787, 7, '_Participants you liked');
INSERT INTO `LocalizationKeys` VALUES (1788, 7, '_Show calendar');
INSERT INTO `LocalizationKeys` VALUES (1789, 7, '_Calendar');
INSERT INTO `LocalizationKeys` VALUES (1790, 7, '_Sunday_short');
INSERT INTO `LocalizationKeys` VALUES (1791, 7, '_Monday_short');
INSERT INTO `LocalizationKeys` VALUES (1792, 7, '_Tuesday_short');
INSERT INTO `LocalizationKeys` VALUES (1793, 7, '_Wednesday_short');
INSERT INTO `LocalizationKeys` VALUES (1794, 7, '_Thursday_short');
INSERT INTO `LocalizationKeys` VALUES (1795, 7, '_Friday_short');
INSERT INTO `LocalizationKeys` VALUES (1796, 7, '_Saturday_short');
INSERT INTO `LocalizationKeys` VALUES (1797, 7, '_SpeedDating tickets');
INSERT INTO `LocalizationKeys` VALUES (1798, 100, '_Invalid module type selected.');
INSERT INTO `LocalizationKeys` VALUES (1799, 100, '_Module directory was not set. Module must be re-configurated');
INSERT INTO `LocalizationKeys` VALUES (1800, 100, '_Select module type');
INSERT INTO `LocalizationKeys` VALUES (1801, 100, '_Please login before using Ray chat');
INSERT INTO `LocalizationKeys` VALUES (1802, 100, '_Ray is not enabled');
INSERT INTO `LocalizationKeys` VALUES (1803, 100, '_No modules of this type installed');
INSERT INTO `LocalizationKeys` VALUES (1804, 100, '_Module selection');
INSERT INTO `LocalizationKeys` VALUES (1805, 100, '_Choose module to log in');
INSERT INTO `LocalizationKeys` VALUES (1806, 100, '_Choose module type');
INSERT INTO `LocalizationKeys` VALUES (1807, 100, '_Module type selection');
INSERT INTO `LocalizationKeys` VALUES (1808, 100, '_No modules found');
INSERT INTO `LocalizationKeys` VALUES (1809, 100, '_Ray is not enabled. Select <link> another module');
INSERT INTO `LocalizationKeys` VALUES (1852, 2, '_CHECKOUT_H');
INSERT INTO `LocalizationKeys` VALUES (1853, 26, '_Membership purchase');
INSERT INTO `LocalizationKeys` VALUES (1854, 26, '_SpeedDating ticket purchase');
INSERT INTO `LocalizationKeys` VALUES (1855, 26, '_Credits purchase');
INSERT INTO `LocalizationKeys` VALUES (1856, 26, '_Profiles purchase');
INSERT INTO `LocalizationKeys` VALUES (1857, 26, '_Payment description');
INSERT INTO `LocalizationKeys` VALUES (1858, 26, '_Payment amount');
INSERT INTO `LocalizationKeys` VALUES (1859, 26, '_Possible subscription period');
INSERT INTO `LocalizationKeys` VALUES (1860, 26, '_Payment info');
INSERT INTO `LocalizationKeys` VALUES (1861, 26, '_Payment methods');
INSERT INTO `LocalizationKeys` VALUES (1862, 26, '_Credit balance');
INSERT INTO `LocalizationKeys` VALUES (1864, 26, '_recurring payment');
INSERT INTO `LocalizationKeys` VALUES (1865, 26, '_recurring not supported');
INSERT INTO `LocalizationKeys` VALUES (1866, 26, '_recurring not allowed');
INSERT INTO `LocalizationKeys` VALUES (1867, 4, '_Lifetime');
INSERT INTO `LocalizationKeys` VALUES (1868, 26, '_every N days');
INSERT INTO `LocalizationKeys` VALUES (1869, 26, '_Subscriptions');
INSERT INTO `LocalizationKeys` VALUES (1870, 26, '_Start date');
INSERT INTO `LocalizationKeys` VALUES (1871, 26, '_Period');
INSERT INTO `LocalizationKeys` VALUES (1872, 26, '_Charges number');
INSERT INTO `LocalizationKeys` VALUES (1873, 26, '_Cancel');
INSERT INTO `LocalizationKeys` VALUES (1874, 26, '_Subscription cancellation request was successfully sent');
INSERT INTO `LocalizationKeys` VALUES (1875, 26, '_Fail to sent subscription cancellation request');
INSERT INTO `LocalizationKeys` VALUES (1876, 3, '_message_subject');
INSERT INTO `LocalizationKeys` VALUES (1877, 100, '_Customize Profile');
INSERT INTO `LocalizationKeys` VALUES (1878, 100, '_Background color');
INSERT INTO `LocalizationKeys` VALUES (1879, 100, '_Background picture');
INSERT INTO `LocalizationKeys` VALUES (1880, 100, '_Font color');
INSERT INTO `LocalizationKeys` VALUES (1881, 100, '_Font size');
INSERT INTO `LocalizationKeys` VALUES (1882, 100, '_Font family');
INSERT INTO `LocalizationKeys` VALUES (1883, 26, '_Credit card number');
INSERT INTO `LocalizationKeys` VALUES (1884, 26, '_Expiration date');
INSERT INTO `LocalizationKeys` VALUES (1885, 3, '_no_messages_from');
INSERT INTO `LocalizationKeys` VALUES (1886, 3, '_no_messages_to');
INSERT INTO `LocalizationKeys` VALUES (1887, 3, '_messages_to');
INSERT INTO `LocalizationKeys` VALUES (1888, 3, '_messages_from');
INSERT INTO `LocalizationKeys` VALUES (1889, 100, '_Reset');
INSERT INTO `LocalizationKeys` VALUES (1890, 100, '_Customize');
INSERT INTO `LocalizationKeys` VALUES (1891, 3, '_no_top_week');
INSERT INTO `LocalizationKeys` VALUES (1892, 3, '_no_top_month');
INSERT INTO `LocalizationKeys` VALUES (1893, 2, '_RAY_CHAT_H');
INSERT INTO `LocalizationKeys` VALUES (1894, 2, '_RAY_IM_H');
INSERT INTO `LocalizationKeys` VALUES (1895, 1, '_web_community_site');
INSERT INTO `LocalizationKeys` VALUES (1896, 1, '_powered_by_Dolphin');
INSERT INTO `LocalizationKeys` VALUES (1897, 1, '_welcome_and_join');
INSERT INTO `LocalizationKeys` VALUES (1898, 1, '_promo_list_1');
INSERT INTO `LocalizationKeys` VALUES (1899, 1, '_promo_list_2');
INSERT INTO `LocalizationKeys` VALUES (1900, 1, '_promo_list_3');
INSERT INTO `LocalizationKeys` VALUES (1901, 1, '_promo_list_4');
INSERT INTO `LocalizationKeys` VALUES (1902, 1, '_promo_list_5');
INSERT INTO `LocalizationKeys` VALUES (1903, 3, '_index_login_question');
INSERT INTO `LocalizationKeys` VALUES (1904, 3, '_not_a_member');
INSERT INTO `LocalizationKeys` VALUES (1905, 3, '_username');
INSERT INTO `LocalizationKeys` VALUES (1906, 3, '_forgot_username_or_password');
INSERT INTO `LocalizationKeys` VALUES (1907, 3, '_browse');
INSERT INTO `LocalizationKeys` VALUES (1908, 3, '_previous_photo_results');
INSERT INTO `LocalizationKeys` VALUES (1909, 3, '_this_nick_already_used');
INSERT INTO `LocalizationKeys` VALUES (1910, 3, '_to_compose_new_message');
INSERT INTO `LocalizationKeys` VALUES (1911, 2, '_RAY_RECORDER_H');
INSERT INTO `LocalizationKeys` VALUES (1912, 1, '_match');
INSERT INTO `LocalizationKeys` VALUES (1913, 3, '_enter_message_text');
INSERT INTO `LocalizationKeys` VALUES (1914, 3, '_profile_comments');
INSERT INTO `LocalizationKeys` VALUES (1915, 7, '_Add new event');
INSERT INTO `LocalizationKeys` VALUES (1916, 7, '_Title');
INSERT INTO `LocalizationKeys` VALUES (1917, 7, '_Venue photo');
INSERT INTO `LocalizationKeys` VALUES (1918, 7, '_Female ticket count');
INSERT INTO `LocalizationKeys` VALUES (1919, 7, '_Male ticket count');
INSERT INTO `LocalizationKeys` VALUES (1920, 7, '_Couple ticket count');
INSERT INTO `LocalizationKeys` VALUES (1921, 7, '_Please fill up all fields');
INSERT INTO `LocalizationKeys` VALUES (1922, 7, '_Wrong date format or wrong date order');
INSERT INTO `LocalizationKeys` VALUES (1923, 7, '_Error during photo resizing');
INSERT INTO `LocalizationKeys` VALUES (1924, 3, '_Sound file successfully deleted');
INSERT INTO `LocalizationKeys` VALUES (1926, 3, '_read_comments');
INSERT INTO `LocalizationKeys` VALUES (1927, 3, '_read_new_comments');
INSERT INTO `LocalizationKeys` VALUES (1928, 6, '_No albums found');
INSERT INTO `LocalizationKeys` VALUES (1929, 6, '_Delete object');
INSERT INTO `LocalizationKeys` VALUES (1930, 27, '_poll created');
INSERT INTO `LocalizationKeys` VALUES (1931, 27, '_max_poll_reached');
INSERT INTO `LocalizationKeys` VALUES (1932, 27, '_controls');
INSERT INTO `LocalizationKeys` VALUES (1933, 27, '_are you sure?');
INSERT INTO `LocalizationKeys` VALUES (1934, 27, '_no poll');
INSERT INTO `LocalizationKeys` VALUES (1935, 27, '_question');
INSERT INTO `LocalizationKeys` VALUES (1936, 27, '_answer variants');
INSERT INTO `LocalizationKeys` VALUES (1937, 27, '_add answer');
INSERT INTO `LocalizationKeys` VALUES (1938, 27, '_generate poll');
INSERT INTO `LocalizationKeys` VALUES (1939, 27, '_create poll');
INSERT INTO `LocalizationKeys` VALUES (1940, 27, '_random polls');
INSERT INTO `LocalizationKeys` VALUES (1941, 27, '_latest polls');
INSERT INTO `LocalizationKeys` VALUES (1942, 27, '_top polls');
INSERT INTO `LocalizationKeys` VALUES (1943, 27, '_No profile polls available.');
INSERT INTO `LocalizationKeys` VALUES (1944, 27, '_my_polls');
INSERT INTO `LocalizationKeys` VALUES (1945, 27, '_delete');
INSERT INTO `LocalizationKeys` VALUES (1946, 27, '_this poll');
INSERT INTO `LocalizationKeys` VALUES (1947, 27, '_loading ...');
INSERT INTO `LocalizationKeys` VALUES (1948, 27, '_poll successfully deleted');
INSERT INTO `LocalizationKeys` VALUES (1949, 27, '_make it');
INSERT INTO `LocalizationKeys` VALUES (1950, 4, '_use gallery');
INSERT INTO `LocalizationKeys` VALUES (1951, 4, '_view other members'' galleries');
INSERT INTO `LocalizationKeys` VALUES (1952, 3, '_Gallery disabled for the member');
INSERT INTO `LocalizationKeys` VALUES (1953, 100, '_Original letter');
INSERT INTO `LocalizationKeys` VALUES (1954, 100, '_Recipient');
INSERT INTO `LocalizationKeys` VALUES (1955, 3, '__All');
INSERT INTO `LocalizationKeys` VALUES (1956, 100, '_fast_and_secure_');
INSERT INTO `LocalizationKeys` VALUES (1957, 3, '_post_a_free_');
INSERT INTO `LocalizationKeys` VALUES (1958, 3, '_instantly_find_');
INSERT INTO `LocalizationKeys` VALUES (1959, 100, '_creating_lifestyle_');
INSERT INTO `LocalizationKeys` VALUES (1961, 3, '_NGallery');
INSERT INTO `LocalizationKeys` VALUES (1962, 3, '_friends');
INSERT INTO `LocalizationKeys` VALUES (1963, 3, '_privateAlb');
INSERT INTO `LocalizationKeys` VALUES (1964, 6, '_AlbumBaner');
INSERT INTO `LocalizationKeys` VALUES (1966, 3, '_ObjectBaner');
INSERT INTO `LocalizationKeys` VALUES (1967, 3, '_domain_without_http');
INSERT INTO `LocalizationKeys` VALUES (1968, 3, '_forgot_your_password');
INSERT INTO `LocalizationKeys` VALUES (1969, 3, '_log_in');
INSERT INTO `LocalizationKeys` VALUES (1970, 3, '_about');
INSERT INTO `LocalizationKeys` VALUES (1971, 3, '_photos');
INSERT INTO `LocalizationKeys` VALUES (1972, 3, '_contact_us');
INSERT INTO `LocalizationKeys` VALUES (1973, 3, '_copyright_text');
INSERT INTO `LocalizationKeys` VALUES (1974, 3, '_Random');
INSERT INTO `LocalizationKeys` VALUES (1975, 3, '_Latest');
INSERT INTO `LocalizationKeys` VALUES (1976, 3, '_more_members');
INSERT INTO `LocalizationKeys` VALUES (1977, 3, '_community');
INSERT INTO `LocalizationKeys` VALUES (1978, 3, '_mp3_player');
INSERT INTO `LocalizationKeys` VALUES (1980, 3, '_must_disable_popup_blocker_to_editor');
INSERT INTO `LocalizationKeys` VALUES (1981, 3, '_must_disable_popup_blocker_to_admin');
INSERT INTO `LocalizationKeys` VALUES (1982, 3, '_ACTION_MESSAGE');
INSERT INTO `LocalizationKeys` VALUES (1984, 101, '_day(s)');
INSERT INTO `LocalizationKeys` VALUES (1985, 101, '_hour(s)');
INSERT INTO `LocalizationKeys` VALUES (1986, 101, '_minute(s)');
INSERT INTO `LocalizationKeys` VALUES (1987, 101, '_deleted_N_rows');
INSERT INTO `LocalizationKeys` VALUES (1988, 101, '_failed_delete_rows');
INSERT INTO `LocalizationKeys` VALUES (1990, 3, '_age');
INSERT INTO `LocalizationKeys` VALUES (1991, 3, '_promo_head_');
INSERT INTO `LocalizationKeys` VALUES (1992, 3, '_p1_');
INSERT INTO `LocalizationKeys` VALUES (1993, 3, '_p2_');
INSERT INTO `LocalizationKeys` VALUES (1994, 3, '_p3_');
INSERT INTO `LocalizationKeys` VALUES (1995, 3, '_p4_');
INSERT INTO `LocalizationKeys` VALUES (1996, 3, '_p5_');
INSERT INTO `LocalizationKeys` VALUES (1997, 3, '_p6_');
INSERT INTO `LocalizationKeys` VALUES (1998, 3, '_p7_');
INSERT INTO `LocalizationKeys` VALUES (1999, 3, '_p8_');
INSERT INTO `LocalizationKeys` VALUES (2000, 100, '_failed_to_upload_file_too_big');
INSERT INTO `LocalizationKeys` VALUES (2001, 5, '_blog_caption');
INSERT INTO `LocalizationKeys` VALUES (2002, 3, '_blog_text');
INSERT INTO `LocalizationKeys` VALUES (2003, 3, '_category');
INSERT INTO `LocalizationKeys` VALUES (2004, 5, '_please_fill_next_fields_first');
INSERT INTO `LocalizationKeys` VALUES (2005, 5, '_please_select');
INSERT INTO `LocalizationKeys` VALUES (2006, 5, '_associated_image');
INSERT INTO `LocalizationKeys` VALUES (2007, 5, '_post_comment_per');
INSERT INTO `LocalizationKeys` VALUES (2008, 5, '_post_read_per');
INSERT INTO `LocalizationKeys` VALUES (2009, 5, '_apply_changes');
INSERT INTO `LocalizationKeys` VALUES (2010, 5, '_add_blog');
INSERT INTO `LocalizationKeys` VALUES (2011, 5, '_category_description');
INSERT INTO `LocalizationKeys` VALUES (2012, 5, '_category_caption');
INSERT INTO `LocalizationKeys` VALUES (2013, 5, '_max_chars');
INSERT INTO `LocalizationKeys` VALUES (2014, 5, '_add_category');
INSERT INTO `LocalizationKeys` VALUES (2015, 3, '_Members_blog');
INSERT INTO `LocalizationKeys` VALUES (2016, 3, '_edit_category');
INSERT INTO `LocalizationKeys` VALUES (2017, 5, '_characters_left');
INSERT INTO `LocalizationKeys` VALUES (2018, 3, '_there_is_nothig_to_view');
INSERT INTO `LocalizationKeys` VALUES (2019, 5, '_this_blog_only_for_friends');
INSERT INTO `LocalizationKeys` VALUES (2020, 5, '_commenting_this_blog_allowed_only_for_friends');
INSERT INTO `LocalizationKeys` VALUES (2021, 5, '_you_have_no_permiss_to_edit');
INSERT INTO `LocalizationKeys` VALUES (2022, 5, '_blog_comments_deleted_successfully');
INSERT INTO `LocalizationKeys` VALUES (2023, 5, '_blog_comments_delet_failed');
INSERT INTO `LocalizationKeys` VALUES (2024, 5, '_category_blogs_deleted');
INSERT INTO `LocalizationKeys` VALUES (2025, 5, '_category_blogs_delete_failed');
INSERT INTO `LocalizationKeys` VALUES (2026, 5, '_category_deleted');
INSERT INTO `LocalizationKeys` VALUES (2027, 3, '_category_delete_failed');
INSERT INTO `LocalizationKeys` VALUES (2028, 5, '_category_successfully_added');
INSERT INTO `LocalizationKeys` VALUES (2029, 5, '_failed_to_add_category');
INSERT INTO `LocalizationKeys` VALUES (2030, 3, '_changes_successfully_applied');
INSERT INTO `LocalizationKeys` VALUES (2031, 3, '_failed_to_add_blog');
INSERT INTO `LocalizationKeys` VALUES (2032, 5, '_comment_added_successfully');
INSERT INTO `LocalizationKeys` VALUES (2033, 5, '_failed_to_add_comment');
INSERT INTO `LocalizationKeys` VALUES (2034, 3, '_deleted successfully');
INSERT INTO `LocalizationKeys` VALUES (2035, 3, '_failed to delete');
INSERT INTO `LocalizationKeys` VALUES (2036, 3, '_blog_deleted_successfully');
INSERT INTO `LocalizationKeys` VALUES (2037, 3, '_blog_delete_failed');
INSERT INTO `LocalizationKeys` VALUES (2038, 5, '_blog disabled for the member');
INSERT INTO `LocalizationKeys` VALUES (2039, 5, '_edit_blog');
INSERT INTO `LocalizationKeys` VALUES (2040, 3, '_join_our_community');
INSERT INTO `LocalizationKeys` VALUES (2041, 3, '_find_your_fr');
INSERT INTO `LocalizationKeys` VALUES (2042, 3, '_share_interests');
INSERT INTO `LocalizationKeys` VALUES (2043, 3, '_stay_tuned');
INSERT INTO `LocalizationKeys` VALUES (2044, 3, '_com');
INSERT INTO `LocalizationKeys` VALUES (2045, 100, '_RayPresence');
INSERT INTO `LocalizationKeys` VALUES (2046, 100, '_RayPresenceNotAllowed');
INSERT INTO `LocalizationKeys` VALUES (2047, 5, '_use Blog');
INSERT INTO `LocalizationKeys` VALUES (2048, 3, '_Orca forum');
INSERT INTO `LocalizationKeys` VALUES (2051, 3, '_my pages');
INSERT INTO `LocalizationKeys` VALUES (2052, 3, '_help');
INSERT INTO `LocalizationKeys` VALUES (2053, 3, '_Dolphin');
INSERT INTO `LocalizationKeys` VALUES (2054, 3, '_smart_community');
INSERT INTO `LocalizationKeys` VALUES (2055, 3, '_christmas_text');
INSERT INTO `LocalizationKeys` VALUES (2056, 3, '_not_member_yet');
INSERT INTO `LocalizationKeys` VALUES (2057, 3, '_click_here');
INSERT INTO `LocalizationKeys` VALUES (2058, 3, '_more_profiles');
INSERT INTO `LocalizationKeys` VALUES (2059, 3, '_user_cookie_save');
INSERT INTO `LocalizationKeys` VALUES (2060, 3, '_title_min_lenght');
INSERT INTO `LocalizationKeys` VALUES (2061, 101,'_limit_was_reached');
INSERT INTO `LocalizationKeys` VALUES (2062, 101, '_photo');
INSERT INTO `LocalizationKeys` VALUES (2063, 101, '_video');
INSERT INTO `LocalizationKeys` VALUES (2064, 101, '_audio');
INSERT INTO `LocalizationKeys` VALUES (2065, 3, '_add_new');
INSERT INTO `LocalizationKeys` VALUES (2066, 101, '_there_is_no_photo_that_you_can_rate');
INSERT INTO `LocalizationKeys` VALUES (2067, 3, '_ratio');
INSERT INTO `LocalizationKeys` VALUES (2068, 3, '_My Pages');
INSERT INTO `LocalizationKeys` VALUES (2069, 3, '_That_is');
INSERT INTO `LocalizationKeys` VALUES (2070, 101, '_download');
INSERT INTO `LocalizationKeys` VALUES (2071, 101, '_UPLOAD_MEDIA');
INSERT INTO `LocalizationKeys` VALUES (2072, 3, '_delete_');
INSERT INTO `LocalizationKeys` VALUES (2073, 101, '_make_primary');
INSERT INTO `LocalizationKeys` VALUES (2074, 101, '_get_media');
INSERT INTO `LocalizationKeys` VALUES (2075, 101, '_MEDIA_GALLERY_H');
INSERT INTO `LocalizationKeys` VALUES (2076, 4, '_Non-member');
INSERT INTO `LocalizationKeys` VALUES (2077, 4, '_Standard');
INSERT INTO `LocalizationKeys` VALUES (2078, 4, '_Promotion');
INSERT INTO `LocalizationKeys` VALUES (2079, 100, '_AGE_INVALID');
INSERT INTO `LocalizationKeys` VALUES (2081, 102, '_Showing results:');
INSERT INTO `LocalizationKeys` VALUES (2082, 102, '_groups count');
INSERT INTO `LocalizationKeys` VALUES (2083, 102, '_Groups');
INSERT INTO `LocalizationKeys` VALUES (2084, 102, '_My Groups');
INSERT INTO `LocalizationKeys` VALUES (2085, 102, '_Group not found');
INSERT INTO `LocalizationKeys` VALUES (2086, 102, '_Group not found_desc');
INSERT INTO `LocalizationKeys` VALUES (2087, 102, '_Group is hidden');
INSERT INTO `LocalizationKeys` VALUES (2088, 102, '_Sorry, group is hidden');
INSERT INTO `LocalizationKeys` VALUES (2089, 102, '_Category');
INSERT INTO `LocalizationKeys` VALUES (2090, 102, '_Created');
INSERT INTO `LocalizationKeys` VALUES (2091, 102, '_Members count');
INSERT INTO `LocalizationKeys` VALUES (2092, 102, '_Group creator');
INSERT INTO `LocalizationKeys` VALUES (2093, 102, '_About group');
INSERT INTO `LocalizationKeys` VALUES (2094, 102, '_Group type');
INSERT INTO `LocalizationKeys` VALUES (2095, 102, '_Public group');
INSERT INTO `LocalizationKeys` VALUES (2096, 102, '_Private group');
INSERT INTO `LocalizationKeys` VALUES (2097, 102, '_Group members');
INSERT INTO `LocalizationKeys` VALUES (2098, 102, '_View all members');
INSERT INTO `LocalizationKeys` VALUES (2099, 102, '_Edit members');
INSERT INTO `LocalizationKeys` VALUES (2100, 102, '_Invite others');
INSERT INTO `LocalizationKeys` VALUES (2101, 102, '_Upload image');
INSERT INTO `LocalizationKeys` VALUES (2102, 102, '_Post topic');
INSERT INTO `LocalizationKeys` VALUES (2103, 102, '_Edit group');
INSERT INTO `LocalizationKeys` VALUES (2104, 102, '_Resign group');
INSERT INTO `LocalizationKeys` VALUES (2105, 102, '_Join group');
INSERT INTO `LocalizationKeys` VALUES (2106, 102, '_Are you sure want to Resign group?');
INSERT INTO `LocalizationKeys` VALUES (2107, 102, '_Are you sure want to Join group?');
INSERT INTO `LocalizationKeys` VALUES (2108, 102, '_Create Group');
INSERT INTO `LocalizationKeys` VALUES (2109, 102, '_Group creation successful');
INSERT INTO `LocalizationKeys` VALUES (2110, 102, '_Group creation unknown error');
INSERT INTO `LocalizationKeys` VALUES (2111, 102, '_Edit Group');
INSERT INTO `LocalizationKeys` VALUES (2112, 102, '_You''re not creator');
INSERT INTO `LocalizationKeys` VALUES (2113, 102, '_Groups Home');
INSERT INTO `LocalizationKeys` VALUES (2114, 102, '_Groups categories');
INSERT INTO `LocalizationKeys` VALUES (2115, 102, '_Keyword');
INSERT INTO `LocalizationKeys` VALUES (2116, 102, '_Advanced search');
INSERT INTO `LocalizationKeys` VALUES (2117, 102, '_Group gallery');
INSERT INTO `LocalizationKeys` VALUES (2118, 102, '_You cannot view gallery while not a group member');
INSERT INTO `LocalizationKeys` VALUES (2119, 102, '_Uploaded by');
INSERT INTO `LocalizationKeys` VALUES (2120, 102, '_Set as thumbnail');
INSERT INTO `LocalizationKeys` VALUES (2121, 102, '_Are you sure want to delete this image?');
INSERT INTO `LocalizationKeys` VALUES (2122, 102, '_Delete image');
INSERT INTO `LocalizationKeys` VALUES (2123, 102, '_You cannot view group members while not a group member');
INSERT INTO `LocalizationKeys` VALUES (2124, 102, '_group creator');
INSERT INTO `LocalizationKeys` VALUES (2125, 102, '_Are you sure want to delete this member?');
INSERT INTO `LocalizationKeys` VALUES (2126, 102, '_Delete member');
INSERT INTO `LocalizationKeys` VALUES (2127, 102, '_Search Groups');
INSERT INTO `LocalizationKeys` VALUES (2128, 102, '_Search by');
INSERT INTO `LocalizationKeys` VALUES (2129, 102, '_by group name');
INSERT INTO `LocalizationKeys` VALUES (2130, 102, '_by keyword');
INSERT INTO `LocalizationKeys` VALUES (2131, 102, '_Any');
INSERT INTO `LocalizationKeys` VALUES (2132, 102, '_Sort by');
INSERT INTO `LocalizationKeys` VALUES (2133, 102, '_by popular');
INSERT INTO `LocalizationKeys` VALUES (2134, 102, '_by newest');
INSERT INTO `LocalizationKeys` VALUES (2135, 102, '_Sorry, no groups found');
INSERT INTO `LocalizationKeys` VALUES (2136, 102, '_Groups search results');
INSERT INTO `LocalizationKeys` VALUES (2137, 102, '_No my groups found');
INSERT INTO `LocalizationKeys` VALUES (2138, 102, '_Choose');
INSERT INTO `LocalizationKeys` VALUES (2139, 102, '_Open join');
INSERT INTO `LocalizationKeys` VALUES (2140, 102, '_Hidden group');
INSERT INTO `LocalizationKeys` VALUES (2141, 102, '_Members can post images');
INSERT INTO `LocalizationKeys` VALUES (2142, 102, '_Members can invite');
INSERT INTO `LocalizationKeys` VALUES (2143, 102, '_Group description');
INSERT INTO `LocalizationKeys` VALUES (2144, 102, '_Group name already exists');
INSERT INTO `LocalizationKeys` VALUES (2145, 102, '_Name is required');
INSERT INTO `LocalizationKeys` VALUES (2146, 102, '_Category is required');
INSERT INTO `LocalizationKeys` VALUES (2147, 102, '_Country is required');
INSERT INTO `LocalizationKeys` VALUES (2148, 102, '_City is required');
INSERT INTO `LocalizationKeys` VALUES (2149, 102, '_About is required');
INSERT INTO `LocalizationKeys` VALUES (2150, 102, '_Country doesn''t exists');
INSERT INTO `LocalizationKeys` VALUES (2151, 102, '_Category doesn''t exists');
INSERT INTO `LocalizationKeys` VALUES (2152, 102, '_Select file');
INSERT INTO `LocalizationKeys` VALUES (2153, 102, '_Group action');
INSERT INTO `LocalizationKeys` VALUES (2154, 102, '_Upload to group gallery error');
INSERT INTO `LocalizationKeys` VALUES (2155, 102, '_You should specify file');
INSERT INTO `LocalizationKeys` VALUES (2156, 102, '_Upload to group gallery');
INSERT INTO `LocalizationKeys` VALUES (2157, 102, '_Upload succesfull');
INSERT INTO `LocalizationKeys` VALUES (2158, 102, '_You should select correct image file');
INSERT INTO `LocalizationKeys` VALUES (2159, 102, '_Upload error');
INSERT INTO `LocalizationKeys` VALUES (2160, 102, '_Gallery upload_desc');
INSERT INTO `LocalizationKeys` VALUES (2161, 102, '_You cannot upload images because members of this group not allowed to upload images');
INSERT INTO `LocalizationKeys` VALUES (2162, 102, '_You cannot upload images because you''re not group member');
INSERT INTO `LocalizationKeys` VALUES (2163, 102, '_Group join error');
INSERT INTO `LocalizationKeys` VALUES (2164, 102, '_You''re already in group');
INSERT INTO `LocalizationKeys` VALUES (2165, 102, '_Group join');
INSERT INTO `LocalizationKeys` VALUES (2166, 102, '_Congrats. Now you''re group member');
INSERT INTO `LocalizationKeys` VALUES (2167, 102, '_Request sent to the group creator. You will become active group member when he approve you.');
INSERT INTO `LocalizationKeys` VALUES (2168, 102, '_Group resign error');
INSERT INTO `LocalizationKeys` VALUES (2169, 102, '_You cannot resign the group because you''re creator');
INSERT INTO `LocalizationKeys` VALUES (2170, 102, '_Group resign');
INSERT INTO `LocalizationKeys` VALUES (2171, 102, '_You succesfully resigned from group');
INSERT INTO `LocalizationKeys` VALUES (2172, 102, '_You cannot resign the group because you''re not group member');
INSERT INTO `LocalizationKeys` VALUES (2173, 102, '_Group thumnail set');
INSERT INTO `LocalizationKeys` VALUES (2174, 102, '_You cannot set group thumnail because you are not group creator');
INSERT INTO `LocalizationKeys` VALUES (2175, 102, '_Group image delete');
INSERT INTO `LocalizationKeys` VALUES (2176, 102, '_You cannot delete image because you are not group creator');
INSERT INTO `LocalizationKeys` VALUES (2177, 102, '_Group member delete error');
INSERT INTO `LocalizationKeys` VALUES (2178, 102, '_You cannot delete yourself from group because you are group creator');
INSERT INTO `LocalizationKeys` VALUES (2179, 102, '_You cannot delete group member because you are not group creator');
INSERT INTO `LocalizationKeys` VALUES (2180, 102, '_Group member approve');
INSERT INTO `LocalizationKeys` VALUES (2181, 102, '_Member succesfully approved');
INSERT INTO `LocalizationKeys` VALUES (2182, 102, '_Group member approve error');
INSERT INTO `LocalizationKeys` VALUES (2183, 102, '_Some error occured');
INSERT INTO `LocalizationKeys` VALUES (2184, 102, '_You cannot approve group member because you are not group creator');
INSERT INTO `LocalizationKeys` VALUES (2185, 102, '_Group member reject');
INSERT INTO `LocalizationKeys` VALUES (2186, 102, '_Member succesfully rejected');
INSERT INTO `LocalizationKeys` VALUES (2187, 102, '_Group member reject error');
INSERT INTO `LocalizationKeys` VALUES (2188, 102, '_You cannot reject group member because you are not group creator');
INSERT INTO `LocalizationKeys` VALUES (2189, 102, '_Group action error');
INSERT INTO `LocalizationKeys` VALUES (2190, 102, '_Unknown group action');
INSERT INTO `LocalizationKeys` VALUES (2191, 102, '_Group name');
INSERT INTO `LocalizationKeys` VALUES (2192, 102, '_Please select at least one search parameter');
INSERT INTO `LocalizationKeys` VALUES (2193, 102, '_Group invite_desc');
INSERT INTO `LocalizationKeys` VALUES (2194, 102, '_Sorry, no members are found');
INSERT INTO `LocalizationKeys` VALUES (2195, 102, '_Back to group');
INSERT INTO `LocalizationKeys` VALUES (2197, 102, '_Groups help');
INSERT INTO `LocalizationKeys` VALUES (2198, 102, '_Groups help_1');
INSERT INTO `LocalizationKeys` VALUES (2199, 102, '_Groups help_2');
INSERT INTO `LocalizationKeys` VALUES (2200, 102, '_close window');
INSERT INTO `LocalizationKeys` VALUES (2201, 102, '_Groups help_4');
INSERT INTO `LocalizationKeys` VALUES (2202, 102, '_Groups help_3');
INSERT INTO `LocalizationKeys` VALUES (2203, 102, '_Groups help_5');
INSERT INTO `LocalizationKeys` VALUES (2204, 102, '_Groups help_6');
INSERT INTO `LocalizationKeys` VALUES (2205, 102, '_Groups help_7');
INSERT INTO `LocalizationKeys` VALUES (2206, 102, '_Group invite');
INSERT INTO `LocalizationKeys` VALUES (2207, 102, '_Your friends');
INSERT INTO `LocalizationKeys` VALUES (2208, 102, '_Invite list');
INSERT INTO `LocalizationKeys` VALUES (2209, 102, '_Add ->');
INSERT INTO `LocalizationKeys` VALUES (2210, 102, '_<- Remove');
INSERT INTO `LocalizationKeys` VALUES (2211, 102, '_Find more...');
INSERT INTO `LocalizationKeys` VALUES (2212, 102, '_Send invites');
INSERT INTO `LocalizationKeys` VALUES (2213, 102, '_Invites succesfully sent');
INSERT INTO `LocalizationKeys` VALUES (2214, 102, '_You should specify at least one member');
INSERT INTO `LocalizationKeys` VALUES (2215, 102, '_Group invite accept');
INSERT INTO `LocalizationKeys` VALUES (2216, 102, '_You succesfully accepted group invite');
INSERT INTO `LocalizationKeys` VALUES (2217, 102, '_Group invite accept error');
INSERT INTO `LocalizationKeys` VALUES (2218, 102, '_You cannot accept group invite');
INSERT INTO `LocalizationKeys` VALUES (2219, 102, '_Group invite reject');
INSERT INTO `LocalizationKeys` VALUES (2220, 102, '_You succesfully rejected group invite');
INSERT INTO `LocalizationKeys` VALUES (2221, 103, '_Quick Search Members');
INSERT INTO `LocalizationKeys` VALUES (2222, 103, '_Enter search parameters');
INSERT INTO `LocalizationKeys` VALUES (2225, 103, '_Quick search results');
INSERT INTO `LocalizationKeys` VALUES (2224, 103, '_Enter member NickName or ID');
INSERT INTO `LocalizationKeys` VALUES (2226, 103, '_Add member');
INSERT INTO `LocalizationKeys` VALUES (2227, 102, '_Post a new topic');
INSERT INTO `LocalizationKeys` VALUES (2228, 102, '_Group forum');
INSERT INTO `LocalizationKeys` VALUES (2229, 102, '_View all topics');
INSERT INTO `LocalizationKeys` VALUES (2230, 3, '_Hello member');
INSERT INTO `LocalizationKeys` VALUES (2231, 3, '_Top');
INSERT INTO `LocalizationKeys` VALUES (2232, 3, '_More photos');
INSERT INTO `LocalizationKeys` VALUES (2233, 3, '_My account');
INSERT INTO `LocalizationKeys` VALUES (2234, 3, '_Submitted by');
INSERT INTO `LocalizationKeys` VALUES (2235, 100, '_Members');
INSERT INTO `LocalizationKeys` VALUES (2236, 100, '_News');
INSERT INTO `LocalizationKeys` VALUES (2237, 3, '_Next page');
INSERT INTO `LocalizationKeys` VALUES (2238, 3, '_Previous page');
INSERT INTO `LocalizationKeys` VALUES (2239, 3, '_Group is suspended');
INSERT INTO `LocalizationKeys` VALUES (2240, 3, '_Sorry, group is suspended');
INSERT INTO `LocalizationKeys` VALUES (2241, 3, '_Group status');
INSERT INTO `LocalizationKeys` VALUES (2242, 3, '_Groups help_8');
INSERT INTO `LocalizationKeys` VALUES (2243, 100, '_N profiles');
INSERT INTO `LocalizationKeys` VALUES (2244, 3, '_Tags');
INSERT INTO `LocalizationKeys` VALUES (2245, 102, '_You must be active member to create groups');
INSERT INTO `LocalizationKeys` VALUES (2246, 3, '_more_tags');
INSERT INTO `LocalizationKeys` VALUES (2247, 3, '_Please');
INSERT INTO `LocalizationKeys` VALUES (2248, 3, '_No blogs available');
INSERT INTO `LocalizationKeys` VALUES (2249, 3, '_Blogs');
INSERT INTO `LocalizationKeys` VALUES (2250, 5, '_By Author');
INSERT INTO `LocalizationKeys` VALUES (2251, 5, '_in Category');
INSERT INTO `LocalizationKeys` VALUES (2252, 3, '_comments N');
INSERT INTO `LocalizationKeys` VALUES (2253, 5, '_More blogs');
INSERT INTO `LocalizationKeys` VALUES (2254, 3, '_Videos');
INSERT INTO `LocalizationKeys` VALUES (2255, 3, '_Forums');
INSERT INTO `LocalizationKeys` VALUES (2256, 3, '_N times');
INSERT INTO `LocalizationKeys` VALUES (2257, 2, '_My Account');
INSERT INTO `LocalizationKeys` VALUES (2258, 2, '_My Mail');
INSERT INTO `LocalizationKeys` VALUES (2259, 2, '_Inbox');
INSERT INTO `LocalizationKeys` VALUES (2260, 2, '_Sent');
INSERT INTO `LocalizationKeys` VALUES (2261, 2, '_Write');
INSERT INTO `LocalizationKeys` VALUES (2262, 2, '_I Blocked');
INSERT INTO `LocalizationKeys` VALUES (2263, 2, '_Blocked Me');
INSERT INTO `LocalizationKeys` VALUES (2264, 2, '_Browse My Photos');
INSERT INTO `LocalizationKeys` VALUES (2265, 2, '_Upload Photo');
INSERT INTO `LocalizationKeys` VALUES (2266, 2, '_My Videos');
INSERT INTO `LocalizationKeys` VALUES (2267, 2, '_My Audio');
INSERT INTO `LocalizationKeys` VALUES (2268, 2, '_My Events');
INSERT INTO `LocalizationKeys` VALUES (2269, 2, '_My Blog');
INSERT INTO `LocalizationKeys` VALUES (2270, 2, '_My Polls');
INSERT INTO `LocalizationKeys` VALUES (2271, 2, '_My Guestbook');
INSERT INTO `LocalizationKeys` VALUES (2272, 2, '_My Greets');
INSERT INTO `LocalizationKeys` VALUES (2273, 2, '_My Faves');
INSERT INTO `LocalizationKeys` VALUES (2274, 2, '_My Friends');
INSERT INTO `LocalizationKeys` VALUES (2275, 2, '_My Views');
INSERT INTO `LocalizationKeys` VALUES (2276, 2, '_Who''s Online');
INSERT INTO `LocalizationKeys` VALUES (2277, 2, '_My Albums');
INSERT INTO `LocalizationKeys` VALUES (2278, 2, '_Browse My Videos');
INSERT INTO `LocalizationKeys` VALUES (2279, 2, '_Browse My Audio');
INSERT INTO `LocalizationKeys` VALUES (2280, 2, '_Upload Audio');
INSERT INTO `LocalizationKeys` VALUES (2281, 2, '_Photos');
INSERT INTO `LocalizationKeys` VALUES (2282, 2, '_Audio');
INSERT INTO `LocalizationKeys` VALUES (2283, 2, '_Albums');
INSERT INTO `LocalizationKeys` VALUES (2284, 2, '_Browse My Groups');
INSERT INTO `LocalizationKeys` VALUES (2285, 2, '_Browse All Groups');
INSERT INTO `LocalizationKeys` VALUES (2286, 2, '_View My Blog');
INSERT INTO `LocalizationKeys` VALUES (2287, 2, '_Add Category');
INSERT INTO `LocalizationKeys` VALUES (2288, 2, '_New Post');
INSERT INTO `LocalizationKeys` VALUES (2289, 2, '_View My Guestbook');
INSERT INTO `LocalizationKeys` VALUES (2290, 2, '_Add Post');
INSERT INTO `LocalizationKeys` VALUES (2291, 2, '_Browse My Albums');
INSERT INTO `LocalizationKeys` VALUES (2292, 2, '_Add Album');
INSERT INTO `LocalizationKeys` VALUES (2293, 2, '_I Greeted');
INSERT INTO `LocalizationKeys` VALUES (2294, 2, '_Greeted Me');
INSERT INTO `LocalizationKeys` VALUES (2295, 2, '_Faved Me');
INSERT INTO `LocalizationKeys` VALUES (2296, 2, '_I Invited');
INSERT INTO `LocalizationKeys` VALUES (2297, 2, '_Invited Me');
INSERT INTO `LocalizationKeys` VALUES (2298, 2, '_I Viewed');
INSERT INTO `LocalizationKeys` VALUES (2299, 2, '_Viewed Me');
INSERT INTO `LocalizationKeys` VALUES (2300, 2, '_Send Message');
INSERT INTO `LocalizationKeys` VALUES (2301, 2, '_Add To Faves');
INSERT INTO `LocalizationKeys` VALUES (2302, 2, '_Invite To Friends');
INSERT INTO `LocalizationKeys` VALUES (2303, 2, '_Send A Greet');
INSERT INTO `LocalizationKeys` VALUES (2304, 2, '_Get E-mail');
INSERT INTO `LocalizationKeys` VALUES (2305, 2, '_Block Profile');
INSERT INTO `LocalizationKeys` VALUES (2306, 2, '_Report Profile');
INSERT INTO `LocalizationKeys` VALUES (2307, 2, '_Send To Friend');
INSERT INTO `LocalizationKeys` VALUES (2308, 2, '_Actions');
INSERT INTO `LocalizationKeys` VALUES (2309, 2, '_Browse My Events');
INSERT INTO `LocalizationKeys` VALUES (2310, 2, '_Create New Event');
INSERT INTO `LocalizationKeys` VALUES (2311, 2, '_Browse Events');
INSERT INTO `LocalizationKeys` VALUES (2312, 2, '_Events Calendar');
INSERT INTO `LocalizationKeys` VALUES (2313, 3, '_Members Polls');
INSERT INTO `LocalizationKeys` VALUES (2331, 3, '_Site Polls');
INSERT INTO `LocalizationKeys` VALUES (2315, 3, '_Members Polls H1');
INSERT INTO `LocalizationKeys` VALUES (2316, 3, '_Members Polls H');
INSERT INTO `LocalizationKeys` VALUES (2317, 3, '_Member Poll H1');
INSERT INTO `LocalizationKeys` VALUES (2318, 3, '_Member Poll H');
INSERT INTO `LocalizationKeys` VALUES (2319, 101, '_Average rating');
INSERT INTO `LocalizationKeys` VALUES (2320, 101, '_Your rate');
INSERT INTO `LocalizationKeys` VALUES (2321, 101, '_Total votes');
INSERT INTO `LocalizationKeys` VALUES (2322, 3, '_Previous rated');
INSERT INTO `LocalizationKeys` VALUES (2323, 101, '_Recent Videos');
INSERT INTO `LocalizationKeys` VALUES (2324, 3, '_Top Photos');
INSERT INTO `LocalizationKeys` VALUES (2325, 3, '_Recent Photos');
INSERT INTO `LocalizationKeys` VALUES (2326, 3, '_My Contacts');
INSERT INTO `LocalizationKeys` VALUES (2327, 3, '_Couples');
INSERT INTO `LocalizationKeys` VALUES (2328, 3, '_Poll not available');
INSERT INTO `LocalizationKeys` VALUES (2329, 3, '_Flag');
INSERT INTO `LocalizationKeys` VALUES (2330, 3, '_Click to sort');
INSERT INTO `LocalizationKeys` VALUES (2332, 2, '_Simple Search');
INSERT INTO `LocalizationKeys` VALUES (2333, 2, '_Advanced Search');
INSERT INTO `LocalizationKeys` VALUES (2334, 3, '_Site Poll');
INSERT INTO `LocalizationKeys` VALUES (2335, 2, '_Top Groups');
INSERT INTO `LocalizationKeys` VALUES (2336, 3, '_All Blogs');
INSERT INTO `LocalizationKeys` VALUES (2337, 3, '_No members found here');
INSERT INTO `LocalizationKeys` VALUES (2338, 3, '_You must create category');
INSERT INTO `LocalizationKeys` VALUES (2339, 3, '_No profile tags found');
INSERT INTO `LocalizationKeys` VALUES (2340, 3, '_Bookmark');
INSERT INTO `LocalizationKeys` VALUES (2341, 3, '_or');
INSERT INTO `LocalizationKeys` VALUES (2342, 3, '_Classifieds');
INSERT INTO `LocalizationKeys` VALUES (2343, 5, '_Recently Posted');
INSERT INTO `LocalizationKeys` VALUES (2344, 3, '_Events');
INSERT INTO `LocalizationKeys` VALUES (2345, 3, '_Feedback');
INSERT INTO `LocalizationKeys` VALUES (2346, 3, '_Contact us');
INSERT INTO `LocalizationKeys` VALUES (2347, 3, '_Sorry, you''re already joined');
INSERT INTO `LocalizationKeys` VALUES (2348, 3, '_Profile details');
INSERT INTO `LocalizationKeys` VALUES (2349, 3, '_Age');
INSERT INTO `LocalizationKeys` VALUES (2350, 3, '_answer');
INSERT INTO `LocalizationKeys` VALUES (2351, 3, '_Member photos');
INSERT INTO `LocalizationKeys` VALUES (2352, 3, '_To The Community');
INSERT INTO `LocalizationKeys` VALUES (2353, 105, '_classifieds');
INSERT INTO `LocalizationKeys` VALUES (2354, 105, '_CLASSIFIEDS_VIEW_H');
INSERT INTO `LocalizationKeys` VALUES (2355, 105, '_CLASSIFIEDS_VIEW_H1');
INSERT INTO `LocalizationKeys` VALUES (2356, 105, '_Search Ad Form');
INSERT INTO `LocalizationKeys` VALUES (2357, 105, '_Browse All Ads');
INSERT INTO `LocalizationKeys` VALUES (2358, 105, '_My Classifieds');
INSERT INTO `LocalizationKeys` VALUES (2359, 105, '_Browse My Ads');
INSERT INTO `LocalizationKeys` VALUES (2360, 105, '_PostAd');
INSERT INTO `LocalizationKeys` VALUES (2361, 105, '_Browse All Members');
INSERT INTO `LocalizationKeys` VALUES (2362, 105, '_Categories');
INSERT INTO `LocalizationKeys` VALUES (2363, 105, '_Keywords');
INSERT INTO `LocalizationKeys` VALUES (2364, 105, '_Posted by');
INSERT INTO `LocalizationKeys` VALUES (2365, 105, '_Details');
INSERT INTO `LocalizationKeys` VALUES (2366, 105, '_AdminArea');
INSERT INTO `LocalizationKeys` VALUES (2367, 105, '_My Advertisements');
INSERT INTO `LocalizationKeys` VALUES (2368, 105, '_Life Time');
INSERT INTO `LocalizationKeys` VALUES (2369, 105, '_Message');
INSERT INTO `LocalizationKeys` VALUES (2370, 105, '_Pictures');
INSERT INTO `LocalizationKeys` VALUES (2371, 105, '_Send these files');
INSERT INTO `LocalizationKeys` VALUES (2372, 105, '_Add file field');
INSERT INTO `LocalizationKeys` VALUES (2373, 105, '_Filtered');
INSERT INTO `LocalizationKeys` VALUES (2374, 105, '_Listing');
INSERT INTO `LocalizationKeys` VALUES (2375, 105, '_out');
INSERT INTO `LocalizationKeys` VALUES (2376, 105, '_of');
INSERT INTO `LocalizationKeys` VALUES (2377, 105, '_SubCategories');
INSERT INTO `LocalizationKeys` VALUES (2378, 105, '_Moderating (new messages)');
INSERT INTO `LocalizationKeys` VALUES (2379, 105, '_Add');
INSERT INTO `LocalizationKeys` VALUES (2380, 105, '_Add this');
INSERT INTO `LocalizationKeys` VALUES (2381, 105, '_Desctiption');
INSERT INTO `LocalizationKeys` VALUES (2382, 105, '_CustomField1');
INSERT INTO `LocalizationKeys` VALUES (2383, 105, '_CustomField2');
INSERT INTO `LocalizationKeys` VALUES (2384, 105, '_Apply');
INSERT INTO `LocalizationKeys` VALUES (2385, 105, '_Activate');
INSERT INTO `LocalizationKeys` VALUES (2386, 105, '_Entity');
INSERT INTO `LocalizationKeys` VALUES (2387, 105, '_Return Back');
INSERT INTO `LocalizationKeys` VALUES (2388, 105, '_Tree Classifieds Browse');
INSERT INTO `LocalizationKeys` VALUES (2389, 105, '_equal');
INSERT INTO `LocalizationKeys` VALUES (2390, 105, '_bigger');
INSERT INTO `LocalizationKeys` VALUES (2391, 105, '_smaller');
INSERT INTO `LocalizationKeys` VALUES (2392, 105, '_FAILED_RUN_SQL');
INSERT INTO `LocalizationKeys` VALUES (2393, 105, '_WARNING_MAX_LIVE_DAYS');
INSERT INTO `LocalizationKeys` VALUES (2394, 105, '_WARNING_MAX_SIZE_FILE');
INSERT INTO `LocalizationKeys` VALUES (2395, 105, '_SUCC_ADD_ADV');
INSERT INTO `LocalizationKeys` VALUES (2396, 105, '_FAIL_ADD_ADV');
INSERT INTO `LocalizationKeys` VALUES (2397, 105, '_SUCC_DEL_ADV');
INSERT INTO `LocalizationKeys` VALUES (2398, 105, '_FAIL_DEL_ADV');
INSERT INTO `LocalizationKeys` VALUES (2399, 105, '_TREE_C_BRW');
INSERT INTO `LocalizationKeys` VALUES (2400, 105, '_MODERATING');
INSERT INTO `LocalizationKeys` VALUES (2401, 105, '_SUCC_ACT_ADV');
INSERT INTO `LocalizationKeys` VALUES (2402, 105, '_FAIL_ACT_ADV');
INSERT INTO `LocalizationKeys` VALUES (2403, 105, '_SUCC_UPD_ADV');
INSERT INTO `LocalizationKeys` VALUES (2404, 105, '_FAIL_UPD_ADV');
INSERT INTO `LocalizationKeys` VALUES (2405, 105, '_Filter');
INSERT INTO `LocalizationKeys` VALUES (2406, 105, '_choose');
INSERT INTO `LocalizationKeys` VALUES (2407, 105, '_Are you sure');
INSERT INTO `LocalizationKeys` VALUES (2408, 105, '_Apply Changes');
INSERT INTO `LocalizationKeys` VALUES (2409, 105, '_Offer Details');
INSERT INTO `LocalizationKeys` VALUES (2410, 3, '_USER_CONF_SUCCEEDED');
INSERT INTO `LocalizationKeys` VALUES (2411, 3, '_USER_ACTIVATION_SUCCEEDED');
INSERT INTO `LocalizationKeys` VALUES (2412, 105, '_wholesale');
INSERT INTO `LocalizationKeys` VALUES (2413, 105, '_CLS_BUYMSG_1');
INSERT INTO `LocalizationKeys` VALUES (2414, 105, '_CLS_BUY_DET1');
INSERT INTO `LocalizationKeys` VALUES (2415, 105, '_CLS_BUYMSG_2');
INSERT INTO `LocalizationKeys` VALUES (2416, 105, '_SUCC_ADD_COMM');
INSERT INTO `LocalizationKeys` VALUES (2417, 105, '_FAIL_ADD_COMM');
INSERT INTO `LocalizationKeys` VALUES (2418, 105, '_LeaveComment');
INSERT INTO `LocalizationKeys` VALUES (2419, 105, '_Post Comment');
INSERT INTO `LocalizationKeys` VALUES (2420, 105, '_Unit');
INSERT INTO `LocalizationKeys` VALUES (2421, 105, '_Users other listing');
INSERT INTO `LocalizationKeys` VALUES (2422, 105, '_Subject is required');
INSERT INTO `LocalizationKeys` VALUES (2423, 105, '_Message must be 50 symbols at least');
INSERT INTO `LocalizationKeys` VALUES (2424, 105, '_Manage classifieds');
INSERT INTO `LocalizationKeys` VALUES (2425, 1, '_Befriend');
INSERT INTO `LocalizationKeys` VALUES (2426, 1, '_SendLetter');
INSERT INTO `LocalizationKeys` VALUES (2427, 1, '_Fave');
INSERT INTO `LocalizationKeys` VALUES (2428, 1, '_Share');
INSERT INTO `LocalizationKeys` VALUES (2429, 1, '_Report');
INSERT INTO `LocalizationKeys` VALUES (2430, 1, '_seconds ago');
INSERT INTO `LocalizationKeys` VALUES (2431, 1, '_minutes ago');
INSERT INTO `LocalizationKeys` VALUES (2432, 1, '_hours ago');
INSERT INTO `LocalizationKeys` VALUES (2433, 1, '_days ago');
INSERT INTO `LocalizationKeys` VALUES (2434, 1, '_Info');
INSERT INTO `LocalizationKeys` VALUES (2435, 1, '_ProfileMusic');
INSERT INTO `LocalizationKeys` VALUES (2436, 1, '_ProfileVideos');
INSERT INTO `LocalizationKeys` VALUES (2437, 1, '_ProfilePhotos');
INSERT INTO `LocalizationKeys` VALUES (2438, 1, '_ChatNow');
INSERT INTO `LocalizationKeys` VALUES (2439, 1, '_Greet');
INSERT INTO `LocalizationKeys` VALUES (2440, 105, '_Advertisement');
INSERT INTO `LocalizationKeys` VALUES (2441, 105, '_Buy Now');
INSERT INTO `LocalizationKeys` VALUES (2442, 3, '_Account Home');
INSERT INTO `LocalizationKeys` VALUES (2443, 3, '_My Settings');
INSERT INTO `LocalizationKeys` VALUES (2444, 2, '_Members3');
INSERT INTO `LocalizationKeys` VALUES (2445, 2, '_Test');
INSERT INTO `LocalizationKeys` VALUES (2446, 2, '_All Members');
INSERT INTO `LocalizationKeys` VALUES (2447, 2, '_All Groups');
INSERT INTO `LocalizationKeys` VALUES (2448, 2, '_All Videos');
INSERT INTO `LocalizationKeys` VALUES (2449, 101, '_No video');
INSERT INTO `LocalizationKeys` VALUES (2465, 101, '_browseVideo');
INSERT INTO `LocalizationKeys` VALUES (2466, 101, '_File was added to favorite');
INSERT INTO `LocalizationKeys` VALUES (2467, 101, '_File already is favorite');
INSERT INTO `LocalizationKeys` VALUES (2468, 101, '_Enter email(s)');
INSERT INTO `LocalizationKeys` VALUES (2469, 101, '_view Video');
INSERT INTO `LocalizationKeys` VALUES (2470, 101, '_See all videos of this user');
INSERT INTO `LocalizationKeys` VALUES (2471, 101, '_File title');
INSERT INTO `LocalizationKeys` VALUES (2472, 101, '_File tags');
INSERT INTO `LocalizationKeys` VALUES (2473, 101, '_Upload Files');
INSERT INTO `LocalizationKeys` VALUES (2474, 101, '_Page');
INSERT INTO `LocalizationKeys` VALUES (2475, 101, '_Music files');
INSERT INTO `LocalizationKeys` VALUES (2476, 101, '_browseMusic');
INSERT INTO `LocalizationKeys` VALUES (2477, 101, '_Playbacks');
INSERT INTO `LocalizationKeys` VALUES (2478, 101, '_upload Photo');
INSERT INTO `LocalizationKeys` VALUES (2479, 2, '_Boards');
INSERT INTO `LocalizationKeys` VALUES (2480, 2, '_All Classifieds');
INSERT INTO `LocalizationKeys` VALUES (2481, 2, '_Add Classified');
INSERT INTO `LocalizationKeys` VALUES (2482, 2, '_Music');
INSERT INTO `LocalizationKeys` VALUES (2483, 2, '_All Music');
INSERT INTO `LocalizationKeys` VALUES (2484, 2, '_Upload Music');
INSERT INTO `LocalizationKeys` VALUES (2485, 2, '_All Photos');
INSERT INTO `LocalizationKeys` VALUES (2486, 2, '_Top Blogs');
INSERT INTO `LocalizationKeys` VALUES (2487, 2, '_All Events');
INSERT INTO `LocalizationKeys` VALUES (2488, 2, '_Add Event');
INSERT INTO `LocalizationKeys` VALUES (2489, 2, '_All Polls');
INSERT INTO `LocalizationKeys` VALUES (2490, 3, '_ProfileMp3');
INSERT INTO `LocalizationKeys` VALUES (2491, 2, '_Guestbook');
INSERT INTO `LocalizationKeys` VALUES (2492, 3, '_File description');
INSERT INTO `LocalizationKeys` VALUES (2493, 3, '_upload Video');
INSERT INTO `LocalizationKeys` VALUES (2494, 3, '_Upload File');
INSERT INTO `LocalizationKeys` VALUES (2495, 101, '_Sorry, nothing found');
INSERT INTO `LocalizationKeys` VALUES (2496, 101, '_File was uploaded');
INSERT INTO `LocalizationKeys` VALUES (2497, 101, '_Added');
INSERT INTO `LocalizationKeys` VALUES (2498, 101, '_URL');
INSERT INTO `LocalizationKeys` VALUES (2499, 101, '_Embed');
INSERT INTO `LocalizationKeys` VALUES (2500, 101, '_Views');
INSERT INTO `LocalizationKeys` VALUES (2501, 101, '_Video Info');
INSERT INTO `LocalizationKeys` VALUES (2502, 101, '_Download');
INSERT INTO `LocalizationKeys` VALUES (2503, 101, '_File info was sent');
INSERT INTO `LocalizationKeys` VALUES (2504, 101, '_Latest files from this user');
INSERT INTO `LocalizationKeys` VALUES (2505, 101, '_View Comments');
INSERT INTO `LocalizationKeys` VALUES (2506, 101, '_upload Music');
INSERT INTO `LocalizationKeys` VALUES (2507, 101, '_browsePhoto');
INSERT INTO `LocalizationKeys` VALUES (2508, 101, '_Upload failed');
INSERT INTO `LocalizationKeys` VALUES (2509, 101, '_Photo Info');
INSERT INTO `LocalizationKeys` VALUES (2510, 101, '_view Photo');
INSERT INTO `LocalizationKeys` VALUES (2511, 101, '_Music File Info');
INSERT INTO `LocalizationKeys` VALUES (2512, 101, '_view Music');
INSERT INTO `LocalizationKeys` VALUES (2513, 2, '_My Favorites');
INSERT INTO `LocalizationKeys` VALUES (2514, 2, '_My Music');
INSERT INTO `LocalizationKeys` VALUES (2515, 3, '_RAY_CHAT');
INSERT INTO `LocalizationKeys` VALUES (2516, 1, '_Photo');
INSERT INTO `LocalizationKeys` VALUES (2517, 1, '_Resize succesful');
INSERT INTO `LocalizationKeys` VALUES (2518, 3, '_Make Primary');
INSERT INTO `LocalizationKeys` VALUES (2519, 3, '_See all photos of this user');
INSERT INTO `LocalizationKeys` VALUES (2520, 1, '_Untitled');
INSERT INTO `LocalizationKeys` VALUES (2521, 3, '_Original_Size');
INSERT INTO `LocalizationKeys` VALUES (2522, 1, '_Rate');
INSERT INTO `LocalizationKeys` VALUES (2523, 2, '_Advertisement Photos');
INSERT INTO `LocalizationKeys` VALUES (2524, 2, '_Comments');
INSERT INTO `LocalizationKeys` VALUES (2525, 2, '_Users Other Listing');
INSERT INTO `LocalizationKeys` VALUES (2526, 2, '_Top Video');
INSERT INTO `LocalizationKeys` VALUES (2527, 2, '_Top Music');
INSERT INTO `LocalizationKeys` VALUES (2528, 2, '_Profile Photos');
INSERT INTO `LocalizationKeys` VALUES (2529, 2, '_Profile Music');
INSERT INTO `LocalizationKeys` VALUES (2530, 2, '_Profile Video');
INSERT INTO `LocalizationKeys` VALUES (2531, 7, '_You have successfully joined this Event');
INSERT INTO `LocalizationKeys` VALUES (2532, 7, '_List');
INSERT INTO `LocalizationKeys` VALUES (2533, 7, '_Event');
INSERT INTO `LocalizationKeys` VALUES (2534, 7, '_Post Event');
INSERT INTO `LocalizationKeys` VALUES (2535, 7, '_By');
INSERT INTO `LocalizationKeys` VALUES (2536, 3, '_Please Wait');
INSERT INTO `LocalizationKeys` VALUES (2537, 3, '_Vote');
INSERT INTO `LocalizationKeys` VALUES (2538, 2, '_My Favorite Photos');
INSERT INTO `LocalizationKeys` VALUES (2539, 2, '_My Favorite Videos');
INSERT INTO `LocalizationKeys` VALUES (2540, 2, '_My Favorite Music');
INSERT INTO `LocalizationKeys` VALUES (2541, 2, '_Music Gallery');
INSERT INTO `LocalizationKeys` VALUES (2542, 2, '_Photos Gallery');
INSERT INTO `LocalizationKeys` VALUES (2543, 2, '_Video Gallery');
INSERT INTO `LocalizationKeys` VALUES (2544, 5, '_Post');
INSERT INTO `LocalizationKeys` VALUES (2545, 5, '_Caption');
INSERT INTO `LocalizationKeys` VALUES (2546, 5, '_Please, Create a Blog');
INSERT INTO `LocalizationKeys` VALUES (2547, 5, '_Create My Blog');
INSERT INTO `LocalizationKeys` VALUES (2548, 5, '_Create Blog');
INSERT INTO `LocalizationKeys` VALUES (2549, 5, '_Posts');
INSERT INTO `LocalizationKeys` VALUES (2554, 3, '_PROFILE Photos');
INSERT INTO `LocalizationKeys` VALUES (2555, 5, '_Top Posts');
INSERT INTO `LocalizationKeys` VALUES (2564, 3, '_PROFILE Info');
INSERT INTO `LocalizationKeys` VALUES (2568, 2, '_BoonEx News');
INSERT INTO `LocalizationKeys` VALUES (2569, 3, '_Visit Source');
INSERT INTO `LocalizationKeys` VALUES (2570, 5, '_post_successfully_deleted');
INSERT INTO `LocalizationKeys` VALUES (2571, 5, '_failed_to_delete_post');
INSERT INTO `LocalizationKeys` VALUES (2572, 5, '_failed_to_add_post');
INSERT INTO `LocalizationKeys` VALUES (2573, 5, '_post_successfully_added');
INSERT INTO `LocalizationKeys` VALUES (2574, 2, '_Leaders');
INSERT INTO `LocalizationKeys` VALUES (2575, 3, '_Day');
INSERT INTO `LocalizationKeys` VALUES (2576, 3, '_Month');
INSERT INTO `LocalizationKeys` VALUES (2577, 3, '_Week');
INSERT INTO `LocalizationKeys` VALUES (2578, 3, '_no_top_day');
INSERT INTO `LocalizationKeys` VALUES (2579, 2, '_Hacker String');
INSERT INTO `LocalizationKeys` VALUES (2581, 5, '_Write a description for your Blog.');
INSERT INTO `LocalizationKeys` VALUES (2582, 5, '_Error Occured');
INSERT INTO `LocalizationKeys` VALUES (2584, 3, '_Forum Posts');
INSERT INTO `LocalizationKeys` VALUES (2585, 3, '_ID_CREATE');
INSERT INTO `LocalizationKeys` VALUES (2586, 3, '_Get BoonEx ID');
INSERT INTO `LocalizationKeys` VALUES (2587, 1, '_Import BoonEx ID');
INSERT INTO `LocalizationKeys` VALUES (2588, 3, '_Import');
INSERT INTO `LocalizationKeys` VALUES (2589, 1, '_Posted');
INSERT INTO `LocalizationKeys` VALUES (2590, 1, '_No articles available');
INSERT INTO `LocalizationKeys` VALUES (2591, 1, '_Read All Articles');
INSERT INTO `LocalizationKeys` VALUES (2592, 1, '_Shared Photos');
INSERT INTO `LocalizationKeys` VALUES (2593, 1, '_Shared Videos');
INSERT INTO `LocalizationKeys` VALUES (2594, 1, '_Shared Music FIles');
INSERT INTO `LocalizationKeys` VALUES (2595, 1, '_This Week');
INSERT INTO `LocalizationKeys` VALUES (2596, 1, '_This Month');
INSERT INTO `LocalizationKeys` VALUES (2597, 1, '_This Year');
INSERT INTO `LocalizationKeys` VALUES (2598, 1, '_Topics');
INSERT INTO `LocalizationKeys` VALUES (2599, 1, '_No tags found here');
INSERT INTO `LocalizationKeys` VALUES (2600, 3, '_Ads');
INSERT INTO `LocalizationKeys` VALUES (2601, 1, '_New Today');
INSERT INTO `LocalizationKeys` VALUES (2602, 2, '_Photo Gallery');
INSERT INTO `LocalizationKeys` VALUES (2603, 1, '_No classifieds available');
INSERT INTO `LocalizationKeys` VALUES (2604, 1, '_No groups available');
INSERT INTO `LocalizationKeys` VALUES (2605, 2, '_My Music Gallery');
INSERT INTO `LocalizationKeys` VALUES (2606, 2, '_My Photo Gallery');
INSERT INTO `LocalizationKeys` VALUES (2607, 2, '_My Video Gallery');
INSERT INTO `LocalizationKeys` VALUES (2608, 1, '_Count');
INSERT INTO `LocalizationKeys` VALUES (2609, 2, '_Site Stats');
INSERT INTO `LocalizationKeys` VALUES (2610, 3, '_I agree');
INSERT INTO `LocalizationKeys` VALUES (2611, 3, '_Media upload Agreement');
INSERT INTO `LocalizationKeys` VALUES (2612, 3, '_License Agreement');
INSERT INTO `LocalizationKeys` VALUES (2613, 2, '_event_deleted');
INSERT INTO `LocalizationKeys` VALUES (2614, 24, '_Tags_caption');
INSERT INTO `LocalizationKeys` VALUES (2615, 24, '_Tags_desc');
INSERT INTO `LocalizationKeys` VALUES (2616, 24, '_Tags_err_msg');
INSERT INTO `LocalizationKeys` VALUES (2617, 2, '_Member Friends');
INSERT INTO `LocalizationKeys` VALUES (2618, 2, '_Select');
INSERT INTO `LocalizationKeys` VALUES (2619, 2, '_Join Now Top');
INSERT INTO `LocalizationKeys` VALUES (2620, 5, '_Tag');
INSERT INTO `LocalizationKeys` VALUES (2621, 103, '_Sorry, no members found');
INSERT INTO `LocalizationKeys` VALUES (2622, 105, '_no posts');
INSERT INTO `LocalizationKeys` VALUES (2623, 3, '_PWD_INVALID3');
INSERT INTO `LocalizationKeys` VALUES (2624, 3, '_Change Password');
INSERT INTO `LocalizationKeys` VALUES (2625, 2, '_SUCC_UPD_POST');
INSERT INTO `LocalizationKeys` VALUES (2626, 2, '_FAIL_UPD_POST');
INSERT INTO `LocalizationKeys` VALUES (2627, 24, '_DateOfBirth_err_msg');
INSERT INTO `LocalizationKeys` VALUES (2628, 3, '_No file');
INSERT INTO `LocalizationKeys` VALUES (2629, 3, '_Admin Panel');
INSERT INTO `LocalizationKeys` VALUES (2630, 3, '_File upload error');
INSERT INTO `LocalizationKeys` VALUES (2631, 4, '_send greetings');
INSERT INTO `LocalizationKeys` VALUES (2632, 105, '_AddMainCategory successfully added');
INSERT INTO `LocalizationKeys` VALUES (2633, 105, '_Failed to Insert AddMainCategory');
INSERT INTO `LocalizationKeys` VALUES (2634, 105, '_AddSubCategory successfully added');
INSERT INTO `LocalizationKeys` VALUES (2635, 105, '_Failed to Insert AddSubCategory');
INSERT INTO `LocalizationKeys` VALUES (2636, 105, '_DeleteMainCategory was successfully');
INSERT INTO `LocalizationKeys` VALUES (2637, 105, '_Failed to DeleteMainCategory');
INSERT INTO `LocalizationKeys` VALUES (2638, 105, '_DeleteSubCategory was successfully');
INSERT INTO `LocalizationKeys` VALUES (2639, 105, '_Failed to DeleteSubCategory');
INSERT INTO `LocalizationKeys` VALUES (2640, 100, '_Add New Article');
INSERT INTO `LocalizationKeys` VALUES (2641, 100, '_Category Caption');
INSERT INTO `LocalizationKeys` VALUES (2642, 100, '_Articles Deleted Successfully');
INSERT INTO `LocalizationKeys` VALUES (2643, 100, '_Articles are not deleted');
INSERT INTO `LocalizationKeys` VALUES (2644, 100, '_Category Deleted Successfully');
INSERT INTO `LocalizationKeys` VALUES (2645, 100, '_Category are not deleted');
INSERT INTO `LocalizationKeys` VALUES (2646, 2, '_Hot or Not');
INSERT INTO `LocalizationKeys` VALUES (2647,100,'_affiliate_system_was_disabled');
INSERT INTO `LocalizationKeys` VALUES(2648, 101, '_DescriptionMedia');
INSERT INTO `LocalizationKeys` VALUES(2649, 1, '_Mutual Friends');
INSERT INTO `LocalizationKeys` VALUES(2650, 3, '_Photo Actions');
INSERT INTO `LocalizationKeys` VALUES(2651, 3, '_Notification');
INSERT INTO `LocalizationKeys` VALUES(2652, 7, '_You have successfully unsubscribe from Event');
INSERT INTO `LocalizationKeys` VALUES(2653, 7, '_Unsubscribe');
INSERT INTO `LocalizationKeys` VALUES(2654, 100, '_not_active_story');
INSERT INTO `LocalizationKeys` VALUES(2655, 1, '_Profile Videos');
INSERT INTO `LocalizationKeys` VALUES(2656, 1, '_My Flags');
INSERT INTO `LocalizationKeys` VALUES(2657, 1, '_My Topics');
INSERT INTO `LocalizationKeys` VALUES(2658, 5, '_Uncategorized');
INSERT INTO `LocalizationKeys` VALUES(2659, 1, '_upload Music (Music Gallery)');
INSERT INTO `LocalizationKeys` VALUES(2660, 1, '_upload Photos (Photo Gallery)');
INSERT INTO `LocalizationKeys` VALUES(2661, 1, '_upload Video (Video Gallery)');
INSERT INTO `LocalizationKeys` VALUES(2662, 1, '_play Music (Music Gallery)');
INSERT INTO `LocalizationKeys` VALUES(2663, 1, '_view Photos (Photo Gallery)');
INSERT INTO `LocalizationKeys` VALUES(2664, 1, '_play Video (Video Gallery)');
INSERT INTO `LocalizationKeys` VALUES (2665, 3, '_PROFILE_CONFIRM');
INSERT INTO `LocalizationKeys` VALUES(2666, 32, '_FieldCaption_Profile Type_Join');
INSERT INTO `LocalizationKeys` VALUES(2667, 32, '_FieldCaption_Couple_Join');
INSERT INTO `LocalizationKeys` VALUES(2668, 32, '_FieldDesc_Couple_Join');
INSERT INTO `LocalizationKeys` VALUES(2669, 32, '_FieldCaption_General Info_Join');
INSERT INTO `LocalizationKeys` VALUES(2670, 32, '_FieldCaption_NickName_Join');
INSERT INTO `LocalizationKeys` VALUES(2671, 32, '_FieldDesc_NickName_Join');
INSERT INTO `LocalizationKeys` VALUES(2672, 32, '_FieldError_NickName_Mandatory');
INSERT INTO `LocalizationKeys` VALUES(2673, 32, '_FieldError_NickName_Min');
INSERT INTO `LocalizationKeys` VALUES(2674, 32, '_FieldError_NickName_Max');
INSERT INTO `LocalizationKeys` VALUES(2675, 32, '_FieldError_NickName_Unique');
INSERT INTO `LocalizationKeys` VALUES(2676, 32, '_FieldError_NickName_Check');
INSERT INTO `LocalizationKeys` VALUES(2677, 32, '_FieldCaption_Email_Join');
INSERT INTO `LocalizationKeys` VALUES(2678, 32, '_FieldDesc_Email_Join');
INSERT INTO `LocalizationKeys` VALUES(2679, 32, '_FieldError_Email_Mandatory');
INSERT INTO `LocalizationKeys` VALUES(2680, 32, '_FieldError_Email_Min');
INSERT INTO `LocalizationKeys` VALUES(2681, 32, '_FieldError_Email_Unique');
INSERT INTO `LocalizationKeys` VALUES(2682, 32, '_FieldError_Email_Check');
INSERT INTO `LocalizationKeys` VALUES(2683, 32, '_FieldCaption_Password_Join');
INSERT INTO `LocalizationKeys` VALUES(2684, 32, '_FieldDesc_Password_Join');
INSERT INTO `LocalizationKeys` VALUES(2685, 32, '_FieldError_Password_Mandatory');
INSERT INTO `LocalizationKeys` VALUES(2686, 32, '_FieldError_Password_Min');
INSERT INTO `LocalizationKeys` VALUES(2687, 32, '_FieldError_Password_Max');
INSERT INTO `LocalizationKeys` VALUES(2688, 32, '_FieldCaption_Misc Info_Join');
INSERT INTO `LocalizationKeys` VALUES(2689, 32, '_FieldCaption_Sex_Join');
INSERT INTO `LocalizationKeys` VALUES(2690, 32, '_FieldDesc_Sex_Join');
INSERT INTO `LocalizationKeys` VALUES(2691, 32, '_FieldError_Sex_Mandatory');
INSERT INTO `LocalizationKeys` VALUES(2692, 32, '_FieldCaption_LookingFor_Join');
INSERT INTO `LocalizationKeys` VALUES(2693, 32, '_FieldDesc_LookingFor_Join');
INSERT INTO `LocalizationKeys` VALUES(2694, 32, '_FieldCaption_DateOfBirth_Join');
INSERT INTO `LocalizationKeys` VALUES(2695, 32, '_FieldDesc_DateOfBirth_Join');
INSERT INTO `LocalizationKeys` VALUES(2696, 32, '_FieldError_DateOfBirth_Mandatory');
INSERT INTO `LocalizationKeys` VALUES(2697, 32, '_FieldError_DateOfBirth_Min');
INSERT INTO `LocalizationKeys` VALUES(2698, 32, '_FieldError_DateOfBirth_Max');
INSERT INTO `LocalizationKeys` VALUES(2699, 32, '_FieldCaption_Headline_Join');
INSERT INTO `LocalizationKeys` VALUES(2700, 32, '_FieldDesc_Headline_Join');
INSERT INTO `LocalizationKeys` VALUES(2701, 32, '_FieldCaption_DescriptionMe_Join');
INSERT INTO `LocalizationKeys` VALUES(2702, 32, '_FieldDesc_DescriptionMe_Join');
INSERT INTO `LocalizationKeys` VALUES(2703, 32, '_FieldError_DescriptionMe_Mandatory');
INSERT INTO `LocalizationKeys` VALUES(2704, 32, '_FieldError_DescriptionMe_Min');
INSERT INTO `LocalizationKeys` VALUES(2705, 32, '_FieldCaption_Country_Join');
INSERT INTO `LocalizationKeys` VALUES(2706, 32, '_FieldDesc_Country_Join');
INSERT INTO `LocalizationKeys` VALUES(2707, 32, '_FieldCaption_City_Join');
INSERT INTO `LocalizationKeys` VALUES(2708, 32, '_FieldDesc_City_Join');
INSERT INTO `LocalizationKeys` VALUES(2709, 32, '_FieldCaption_Security Image_Join');
INSERT INTO `LocalizationKeys` VALUES(2710, 32, '_FieldCaption_Captcha_Join');
INSERT INTO `LocalizationKeys` VALUES(2711, 32, '_FieldDesc_Captcha_Join');
INSERT INTO `LocalizationKeys` VALUES(2712, 32, '_FieldCaption_Admin Controls_Join');
INSERT INTO `LocalizationKeys` VALUES(2713, 32, '_FieldCaption_Description_Join');
INSERT INTO `LocalizationKeys` VALUES(2714, 32, '_FieldCaption_zip_Join');
INSERT INTO `LocalizationKeys` VALUES(2715, 32, '_FieldDesc_zip_Join');
INSERT INTO `LocalizationKeys` VALUES(2716, 32, '_FieldCaption_Tags_Join');
INSERT INTO `LocalizationKeys` VALUES(2717, 32, '_FieldDesc_Tags_Join');
INSERT INTO `LocalizationKeys` VALUES(2718, 32, '_FieldCaption_General Info_Edit');
INSERT INTO `LocalizationKeys` VALUES(2719, 32, '_FieldCaption_NickName_Edit');
INSERT INTO `LocalizationKeys` VALUES(2720, 32, '_FieldCaption_Email_Edit');
INSERT INTO `LocalizationKeys` VALUES(2721, 32, '_FieldCaption_Sex_Edit');
INSERT INTO `LocalizationKeys` VALUES(2722, 32, '_FieldCaption_Password_Edit');
INSERT INTO `LocalizationKeys` VALUES(2723, 32, '_FieldDesc_Password_Edit');
INSERT INTO `LocalizationKeys` VALUES(2724, 32, '_FieldCaption_Misc Info_Edit');
INSERT INTO `LocalizationKeys` VALUES(2725, 32, '_FieldCaption_LookingFor_Edit');
INSERT INTO `LocalizationKeys` VALUES(2726, 32, '_FieldCaption_DateOfBirth_Edit');
INSERT INTO `LocalizationKeys` VALUES(2727, 32, '_FieldCaption_Headline_Edit');
INSERT INTO `LocalizationKeys` VALUES(2728, 32, '_FieldCaption_DescriptionMe_Edit');
INSERT INTO `LocalizationKeys` VALUES(2729, 32, '_FieldCaption_Country_Edit');
INSERT INTO `LocalizationKeys` VALUES(2730, 32, '_FieldCaption_City_Edit');
INSERT INTO `LocalizationKeys` VALUES(2731, 32, '_FieldCaption_Admin Controls_Edit');
INSERT INTO `LocalizationKeys` VALUES(2732, 32, '_FieldCaption_Status_Edit');
INSERT INTO `LocalizationKeys` VALUES(2733, 32, '_FieldDesc_Status_Edit');
INSERT INTO `LocalizationKeys` VALUES(2734, 32, '_FieldCaption_Featured_Edit');
INSERT INTO `LocalizationKeys` VALUES(2735, 32, '_FieldDesc_Featured_Edit');
INSERT INTO `LocalizationKeys` VALUES(2736, 32, '_FieldCaption_General Info_View');
INSERT INTO `LocalizationKeys` VALUES(2737, 32, '_FieldCaption_ID_View');
INSERT INTO `LocalizationKeys` VALUES(2738, 32, '_FieldCaption_NickName_View');
INSERT INTO `LocalizationKeys` VALUES(2739, 32, '_FieldCaption_Status_View');
INSERT INTO `LocalizationKeys` VALUES(2740, 32, '_FieldCaption_Sex_View');
INSERT INTO `LocalizationKeys` VALUES(2741, 32, '_FieldCaption_LookingFor_View');
INSERT INTO `LocalizationKeys` VALUES(2742, 32, '_FieldCaption_Misc Info_View');
INSERT INTO `LocalizationKeys` VALUES(2743, 32, '_FieldCaption_DateOfBirth_View');
INSERT INTO `LocalizationKeys` VALUES(2744, 32, '_FieldCaption_Country_View');
INSERT INTO `LocalizationKeys` VALUES(2745, 32, '_FieldCaption_City_View');
INSERT INTO `LocalizationKeys` VALUES(2746, 32, '_FieldCaption_Description_View');
INSERT INTO `LocalizationKeys` VALUES(2747, 32, '_FieldCaption_Headline_View');
INSERT INTO `LocalizationKeys` VALUES(2748, 32, '_FieldCaption_DescriptionMe_View');
INSERT INTO `LocalizationKeys` VALUES(2749, 32, '_FieldCaption_Admin Controls_View');
INSERT INTO `LocalizationKeys` VALUES(2750, 32, '_FieldCaption_Email_View');
INSERT INTO `LocalizationKeys` VALUES(2751, 32, '_FieldCaption_DateReg_View');
INSERT INTO `LocalizationKeys` VALUES(2752, 32, '_FieldCaption_DateLastLogin_View');
INSERT INTO `LocalizationKeys` VALUES(2753, 32, '_FieldCaption_DateLastEdit_View');
INSERT INTO `LocalizationKeys` VALUES(2754, 32, '_FieldCaption_General Info_Search');
INSERT INTO `LocalizationKeys` VALUES(2755, 32, '_FieldCaption_Couple_Search');
INSERT INTO `LocalizationKeys` VALUES(2756, 32, '_FieldCaption_Sex_Search');
INSERT INTO `LocalizationKeys` VALUES(2757, 32, '_FieldCaption_DateOfBirth_Search');
INSERT INTO `LocalizationKeys` VALUES(2758, 32, '_FieldCaption_Country_Search');
INSERT INTO `LocalizationKeys` VALUES(2759, 32, '_FieldCaption_Keyword_Search');
INSERT INTO `LocalizationKeys` VALUES(2760, 32, '_FieldCaption_Tags_Search');
INSERT INTO `LocalizationKeys` VALUES(2761, 32, '_FieldCaption_Location_Search');
INSERT INTO `LocalizationKeys` VALUES(2763, 32, '_First Person');
INSERT INTO `LocalizationKeys` VALUES(2764, 32, '_Second Person');
INSERT INTO `LocalizationKeys` VALUES(2765, 32, '_Single');
INSERT INTO `LocalizationKeys` VALUES(2766, 32, '_Couple');
INSERT INTO `LocalizationKeys` VALUES(2767, 32, '_Confirm password descr');
INSERT INTO `LocalizationKeys` VALUES(2768, 32, '_Password confirmation failed');
INSERT INTO `LocalizationKeys` VALUES(2769, 32, '_First value must be bigger');
INSERT INTO `LocalizationKeys` VALUES(2770, 32, '_Captcha check failed');
INSERT INTO `LocalizationKeys` VALUES(2771, 32, '_Join failed');
INSERT INTO `LocalizationKeys` VALUES(2772, 32, '_Join complete');
INSERT INTO `LocalizationKeys` VALUES(2773, 32, '_Select it');
INSERT INTO `LocalizationKeys` VALUES(2774, 32, '_Profile not specified');
INSERT INTO `LocalizationKeys` VALUES(2775, 32, '_You cannot edit this profile');
INSERT INTO `LocalizationKeys` VALUES(2776, 32, '_Profile not found');
INSERT INTO `LocalizationKeys` VALUES(2777, 32, '_Couple profile not found');
INSERT INTO `LocalizationKeys` VALUES(2778, 32, '_Save profile successful');
INSERT INTO `LocalizationKeys` VALUES(2779, 32, '_Cast my vote');
INSERT INTO `LocalizationKeys` VALUES(2780, 32, '_LookinMale');
INSERT INTO `LocalizationKeys` VALUES(2781, 32, '_LookinFemale');
INSERT INTO `LocalizationKeys` VALUES(2782, 32, '_FieldDesc_DateLastEdit_View');
INSERT INTO `LocalizationKeys` VALUES(2783, 32, '_FieldDesc_DateLastLogin_View');
INSERT INTO `LocalizationKeys` VALUES(2784, 32, '_FieldDesc_ID_View');
INSERT INTO `LocalizationKeys` VALUES(2785, 32, '_FieldCaption_Misc Info_Search');
INSERT INTO `LocalizationKeys` VALUES(2786, 100, '_enable able to rate');
INSERT INTO `LocalizationKeys` VALUES(2787, 100, '_disable able to rate');
INSERT INTO `LocalizationKeys` VALUES(2788, 100, '_Remember password');
INSERT INTO `LocalizationKeys` VALUES(2789, 100, '_nick_already_in_group');
INSERT INTO `LocalizationKeys` VALUES(2790, 100, '_member_banned');
INSERT INTO `LocalizationKeys` VALUES(2791, 1, '_x_minute_ago');
INSERT INTO `LocalizationKeys` VALUES(2792, 1, '_x_hour_ago');
INSERT INTO `LocalizationKeys` VALUES(2793, 1, '_x_day_ago');
INSERT INTO `LocalizationKeys` VALUES(2794, 1, '_in_x_minute');
INSERT INTO `LocalizationKeys` VALUES(2795, 1, '_in_x_hour');
INSERT INTO `LocalizationKeys` VALUES(2796, 1, '_in_x_day');
INSERT INTO `LocalizationKeys` VALUES(2797, 1, '_Shoutbox');
INSERT INTO `LocalizationKeys` VALUES(2798, 1, '_powered_by');
INSERT INTO `LocalizationKeys` VALUES(2799, 1, '_about_BoonEx');
INSERT INTO `LocalizationKeys` VALUES(2800, 32, '_FieldCaption_TermsOfUse_Join');
INSERT INTO `LocalizationKeys` VALUES(2801, 32, '_You must agree with terms of use');
INSERT INTO `LocalizationKeys` VALUES(2802, 106, '_Show <b>N</b>-<u>N</u> of N discussions');
INSERT INTO `LocalizationKeys` VALUES(2803, 106, '_There are no comments yet');
INSERT INTO `LocalizationKeys` VALUES(2804, 106, '_Error occured');
INSERT INTO `LocalizationKeys` VALUES(2805, 106, '_Duplicate vote');
INSERT INTO `LocalizationKeys` VALUES(2806, 106, '_No such comment');
INSERT INTO `LocalizationKeys` VALUES(2807, 106, '_Are you sure?');
INSERT INTO `LocalizationKeys` VALUES(2808, 106, '_buried');
INSERT INTO `LocalizationKeys` VALUES(2809, 106, '_toggle');
INSERT INTO `LocalizationKeys` VALUES(2810, 106, '_N point');
INSERT INTO `LocalizationKeys` VALUES(2811, 106, '_N points');
INSERT INTO `LocalizationKeys` VALUES(2812, 106, '_Thumb Up');
INSERT INTO `LocalizationKeys` VALUES(2813, 106, '_Thumb Down');
INSERT INTO `LocalizationKeys` VALUES(2814, 106, '_Remove');
INSERT INTO `LocalizationKeys` VALUES(2815, 106, '_(available for <span>N</span> seconds)');
INSERT INTO `LocalizationKeys` VALUES(2816, 106, '_Show N replies');
INSERT INTO `LocalizationKeys` VALUES(2817, 106, '_Reply to this comment');
INSERT INTO `LocalizationKeys` VALUES(2818, 106, '_Add Your Comment');
INSERT INTO `LocalizationKeys` VALUES(2819, 106, '_Submit Comment');
INSERT INTO `LocalizationKeys` VALUES(2820, 106, '_Can not delete comments with replies');
INSERT INTO `LocalizationKeys` VALUES(2821, 106, '_Access denied');
INSERT INTO `LocalizationKeys` VALUES(2822, 1, '_Save');
INSERT INTO `LocalizationKeys` VALUES(2823, 1, '_Search by Tag');
INSERT INTO `LocalizationKeys` VALUES(2824, 1, '_Approve');
INSERT INTO `LocalizationKeys` VALUES(2825, 1, '_Disapprove');
INSERT INTO `LocalizationKeys` VALUES(2826, 1, '_Edit Article');
INSERT INTO `LocalizationKeys` VALUES(2827, 1, '_Article');
INSERT INTO `LocalizationKeys` VALUES(2828, 1, '_Article Title');
INSERT INTO `LocalizationKeys` VALUES(2829, 1, '_Select Category');
INSERT INTO `LocalizationKeys` VALUES(2830, 1, '_Print As');
INSERT INTO `LocalizationKeys` VALUES(2831, 106, '_Hide N replies');
INSERT INTO `LocalizationKeys` VALUES(2832, 3, '_Counter');
INSERT INTO `LocalizationKeys` VALUES(2833, 1, '_Articles were deleted successfully');
INSERT INTO `LocalizationKeys` VALUES(2834, 1, '_Article was deleted successfully');
INSERT INTO `LocalizationKeys` VALUES(2835, 1, '_Article was not deleted');
INSERT INTO `LocalizationKeys` VALUES(2836, 106, '_Reply to Someone comment');
INSERT INTO `LocalizationKeys` VALUES(2837, 3, '_See all music of this user');
INSERT INTO `LocalizationKeys` VALUES(2838, 3, '_View All');
INSERT INTO `LocalizationKeys` VALUES(2839, 1, '_Photo gallery limit was reached');
INSERT INTO `LocalizationKeys` VALUES(2840, 1, '_too_many_files');
INSERT INTO `LocalizationKeys` VALUES(2841, 7, '_event_post_wrong_time');

-- --------------------------------------------------------

-- 
-- Table structure for table `LocalizationLanguages`
-- 

CREATE TABLE `LocalizationLanguages` (
  `ID` tinyint(3) unsigned NOT NULL auto_increment,
  `Name` varchar(5) NOT NULL default '',
  `Flag` varchar(2) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `LocalizationLanguages`
-- 

INSERT INTO `LocalizationLanguages` VALUES(1, 'en', 'gb', 'English');

-- --------------------------------------------------------

-- 
-- Table structure for table `LocalizationStringParams`
-- 

CREATE TABLE `LocalizationStringParams` (
  `IDKey` smallint(5) unsigned NOT NULL default '0',
  `IDParam` tinyint(3) unsigned NOT NULL default '0',
  `Description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`IDKey`,`IDParam`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `LocalizationStringParams`
-- 

INSERT INTO `LocalizationStringParams` VALUES (1, 0, 'Current year');
INSERT INTO `LocalizationStringParams` VALUES (2, 0, 'Current year');
INSERT INTO `LocalizationStringParams` VALUES (121, 0, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (126, 0, 'Minimum chars count');
INSERT INTO `LocalizationStringParams` VALUES (126, 1, 'Maximum chars count');
INSERT INTO `LocalizationStringParams` VALUES (299, 0, 'Children count');
INSERT INTO `LocalizationStringParams` VALUES (315, 0, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (329, 0, 'Person''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (465, 0, 'Person''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (549, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (639, 0, 'Person''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (755, 0, 'Credits per message');
INSERT INTO `LocalizationStringParams` VALUES (794, 0, 'Match percent');
INSERT INTO `LocalizationStringParams` VALUES (795, 0, 'Person''s age');
INSERT INTO `LocalizationStringParams` VALUES (804, 0, 'Wait period in minutes');
INSERT INTO `LocalizationStringParams` VALUES (813, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (820, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (827, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (844, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (861, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (862, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (865, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (887, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (889, 0, 'Search profiles limit');
INSERT INTO `LocalizationStringParams` VALUES (906, 0, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (910, 0, 'Explanation window width');
INSERT INTO `LocalizationStringParams` VALUES (910, 1, 'Explanation window height');
INSERT INTO `LocalizationStringParams` VALUES (911, 0, 'Explanation window width');
INSERT INTO `LocalizationStringParams` VALUES (911, 1, 'Explanation window height');
INSERT INTO `LocalizationStringParams` VALUES (913, 0, 'Explanation window width');
INSERT INTO `LocalizationStringParams` VALUES (913, 1, 'Explanation window height');
INSERT INTO `LocalizationStringParams` VALUES (917, 0, 'Explanation window width');
INSERT INTO `LocalizationStringParams` VALUES (917, 1, 'Explanation window height');
INSERT INTO `LocalizationStringParams` VALUES (919, 0, 'Explanation window width');
INSERT INTO `LocalizationStringParams` VALUES (919, 1, 'Explanation window height');
INSERT INTO `LocalizationStringParams` VALUES (920, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (921, 0, 'Message ID');
INSERT INTO `LocalizationStringParams` VALUES (921, 1, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (923, 0, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (924, 0, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (934, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (937, 0, 'Number of chosen contacts');
INSERT INTO `LocalizationStringParams` VALUES (946, 0, 'Email address');
INSERT INTO `LocalizationStringParams` VALUES (946, 1, 'Person''s link');
INSERT INTO `LocalizationStringParams` VALUES (951, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (960, 0, 'Picture deletion error code');
INSERT INTO `LocalizationStringParams` VALUES (969, 0, 'Upload picture filename');
INSERT INTO `LocalizationStringParams` VALUES (969, 1, 'Picture upload error code');
INSERT INTO `LocalizationStringParams` VALUES (972, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (973, 0, 'Person''s ID');
INSERT INTO `LocalizationStringParams` VALUES (978, 0, 'Person''s ID');
INSERT INTO `LocalizationStringParams` VALUES (980, 0, 'Person''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (989, 0, 'Page number');
INSERT INTO `LocalizationStringParams` VALUES (993, 0, 'Your site affiliate url');
INSERT INTO `LocalizationStringParams` VALUES (993, 1, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (994, 0, 'Affiliate''s ID');
INSERT INTO `LocalizationStringParams` VALUES (1003, 0, 'Your site images url');
INSERT INTO `LocalizationStringParams` VALUES (1003, 1, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (1003, 2, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1005, 0, 'Maximum message length');
INSERT INTO `LocalizationStringParams` VALUES (1007, 0, 'Person''s ID');
INSERT INTO `LocalizationStringParams` VALUES (1009, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1011, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1012, 0, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (1012, 1, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1013, 0, 'Online members count');
INSERT INTO `LocalizationStringParams` VALUES (1038, 0, 'Credits amount');
INSERT INTO `LocalizationStringParams` VALUES (1041, 0, 'Expire days count');
INSERT INTO `LocalizationStringParams` VALUES (1043, 0, 'Expire time');
INSERT INTO `LocalizationStringParams` VALUES (1043, 1, 'Server time');
INSERT INTO `LocalizationStringParams` VALUES (1065, 0, 'Minimum nickname length');
INSERT INTO `LocalizationStringParams` VALUES (1065, 1, 'Maximum nickname length');
INSERT INTO `LocalizationStringParams` VALUES (1066, 0, 'Minimum nickname length');
INSERT INTO `LocalizationStringParams` VALUES (1066, 1, 'Maximum nickname length');
INSERT INTO `LocalizationStringParams` VALUES (1076, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1079, 0, 'Minimum password length');
INSERT INTO `LocalizationStringParams` VALUES (1079, 1, 'Maximum password length');
INSERT INTO `LocalizationStringParams` VALUES (1081, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1094, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1095, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1100, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1116, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1118, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1119, 0, 'Member''s ID');
INSERT INTO `LocalizationStringParams` VALUES (1126, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1134, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1136, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1137, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1144, 0, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (1145, 0, 'Video files extension');
INSERT INTO `LocalizationStringParams` VALUES (1146, 0, 'Your site title');
INSERT INTO `LocalizationStringParams` VALUES (1149, 0, 'Member''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (1150, 0, 'Image resize width');
INSERT INTO `LocalizationStringParams` VALUES (1150, 1, 'Image resize height');
INSERT INTO `LocalizationStringParams` VALUES (1151, 0, 'Number of purchased contacts');
INSERT INTO `LocalizationStringParams` VALUES (1152, 0, 'Number of members who purchased your contact info');
INSERT INTO `LocalizationStringParams` VALUES (1261, 0, 'Upload picture filename');
INSERT INTO `LocalizationStringParams` VALUES (1700, 0, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (1701, 0, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (1702, 0, 'Your site url');
INSERT INTO `LocalizationStringParams` VALUES (1728, 1, 'Membership action name');
INSERT INTO `LocalizationStringParams` VALUES (1728, 2, 'Membership level name');
INSERT INTO `LocalizationStringParams` VALUES (1729, 7, 'Your site email');
INSERT INTO `LocalizationStringParams` VALUES (1730, 1, 'Membership action name');
INSERT INTO `LocalizationStringParams` VALUES (1730, 2, 'Membership level name');
INSERT INTO `LocalizationStringParams` VALUES (1730, 3, 'Membership action limit');
INSERT INTO `LocalizationStringParams` VALUES (1731, 1, 'Membership action name');
INSERT INTO `LocalizationStringParams` VALUES (1731, 2, 'Membership level name');
INSERT INTO `LocalizationStringParams` VALUES (1731, 6, 'Membership level allowed before');
INSERT INTO `LocalizationStringParams` VALUES (1732, 1, 'Membership action name');
INSERT INTO `LocalizationStringParams` VALUES (1732, 2, 'Membership level name');
INSERT INTO `LocalizationStringParams` VALUES (1732, 5, 'Membership level allowed after');
INSERT INTO `LocalizationStringParams` VALUES (1733, 4, 'Membership action period');
INSERT INTO `LocalizationStringParams` VALUES (1741, 0, 'Members count');
INSERT INTO `LocalizationStringParams` VALUES (1747, 0, 'Event title');
INSERT INTO `LocalizationStringParams` VALUES (1809, 0, 'Select module link');
INSERT INTO `LocalizationStringParams` VALUES (1868, 0, 'Number of days');
INSERT INTO `LocalizationStringParams` VALUES (1885, 0, 'Member''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (1886, 0, 'Member''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (1887, 0, 'Member''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (1888, 0, 'Member''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (1896, 0, 'BoonEx Site URL');
INSERT INTO `LocalizationStringParams` VALUES (1897, 1, 'Join Page');
INSERT INTO `LocalizationStringParams` VALUES (1897, 0, 'Site URL');
INSERT INTO `LocalizationStringParams` VALUES (1910, 0, 'Recipient NickName');
INSERT INTO `LocalizationStringParams` VALUES (1910, 1, 'Recipient ID');
INSERT INTO `LocalizationStringParams` VALUES (1910, 2, 'Site URL');
INSERT INTO `LocalizationStringParams` VALUES (1953, 0, 'member''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (1954, 0, 'member''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (1961, 0, 'member''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (1966, 0, 'member of allowed objects in album');
INSERT INTO `LocalizationStringParams` VALUES (1984, 0, 'number of days');
INSERT INTO `LocalizationStringParams` VALUES (1985, 0, 'number of hours');
INSERT INTO `LocalizationStringParams` VALUES (1986, 0, 'number of minutes');
INSERT INTO `LocalizationStringParams` VALUES (1987, 0, 'number of deleted rows');
INSERT INTO `LocalizationStringParams` VALUES (2000, 0, 'file size');
INSERT INTO `LocalizationStringParams` VALUES (2015, 0, 'member''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (2060, 0, 'number of characters');
INSERT INTO `LocalizationStringParams` VALUES (2065, 0, 'add new what');
INSERT INTO `LocalizationStringParams` VALUES (2081, 2, 'total');
INSERT INTO `LocalizationStringParams` VALUES (2081, 1, 'to');
INSERT INTO `LocalizationStringParams` VALUES (2081, 0, 'from');
INSERT INTO `LocalizationStringParams` VALUES (2082, 0, 'groups count');
INSERT INTO `LocalizationStringParams` VALUES (2109, 0, 'group home link');
INSERT INTO `LocalizationStringParams` VALUES (2230, 0, 'member NickName');
INSERT INTO `LocalizationStringParams` VALUES (2234, 0, 'member nickname');
INSERT INTO `LocalizationStringParams` VALUES (2243, 0, 'number');
INSERT INTO `LocalizationStringParams` VALUES (2250, 0, 'member id');
INSERT INTO `LocalizationStringParams` VALUES (2250, 1, 'member nickname');
INSERT INTO `LocalizationStringParams` VALUES (2252, 0, 'image url');
INSERT INTO `LocalizationStringParams` VALUES (2251, 0, 'image url');
INSERT INTO `LocalizationStringParams` VALUES (2251, 1, 'category url');
INSERT INTO `LocalizationStringParams` VALUES (2256, 0, 'number');
INSERT INTO `LocalizationStringParams` VALUES (2554, 0, 'member''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (2564, 0, 'member''s nickname');
INSERT INTO `LocalizationStringParams` VALUES (2585, 0, 'BoonEx ID URL');
INSERT INTO `LocalizationStringParams` VALUES (2611, 0, 'media type');
INSERT INTO `LocalizationStringParams` VALUES (2251, 2, 'category name');
INSERT INTO `LocalizationStringParams` VALUES (2252, 1, 'number of comments');

-- --------------------------------------------------------

-- 
-- Table structure for table `LocalizationStrings`
-- 

CREATE TABLE `LocalizationStrings` (
  `IDKey` smallint(5) unsigned NOT NULL default '0',
  `IDLanguage` tinyint(3) unsigned NOT NULL default '0',
  `String` mediumtext NOT NULL,
  PRIMARY KEY  (`IDKey`,`IDLanguage`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `LocalizationStrings`
-- 

INSERT INTO `LocalizationStrings` VALUES (1, 1, '2002-{0}. Product of <a class="bottom_text" href="http://www.boonex.com/">BoonEx Group</a>.');
INSERT INTO `LocalizationStrings` VALUES (2, 1, 'Copyright &copy; {0} Your Company.');
INSERT INTO `LocalizationStrings` VALUES (3, 1, 'Notify me about News, tips');
INSERT INTO `LocalizationStrings` VALUES (4, 1, '<p><b>Hello <YourRealName></b>,</p><p>You have received a message from <a href="<Domain>profile.php?ID=<ID>"><NickName>(<Domain>profile.php?ID=<ID>)</a>!</p><p>To get this message you have to check your e-mail</p><p>---</p>Best regards,  <SiteName>');
INSERT INTO `LocalizationStrings` VALUES (5, 1, 'GuestBook');
INSERT INTO `LocalizationStrings` VALUES (32, 1, 'Sign up today!');
INSERT INTO `LocalizationStrings` VALUES (33, 1, 'Talk with other members in online chat or privately via instant messenger');
INSERT INTO `LocalizationStrings` VALUES (34, 1, 'Instantly create your own personal profile with photos, video and audio');
INSERT INTO `LocalizationStrings` VALUES (35, 1, 'Find singles in your area using our ZIP-code-based locating system');
INSERT INTO `LocalizationStrings` VALUES (57, 1, 'AOL');
INSERT INTO `LocalizationStrings` VALUES (58, 1, 'IM not allowed');
INSERT INTO `LocalizationStrings` VALUES (59, 1, 'IM not available');
INSERT INTO `LocalizationStrings` VALUES (60, 1, 'AOL');
INSERT INTO `LocalizationStrings` VALUES (61, 1, 'a man');
INSERT INTO `LocalizationStrings` VALUES (62, 1, 'a woman');
INSERT INTO `LocalizationStrings` VALUES (63, 1, 'a man or a woman');
INSERT INTO `LocalizationStrings` VALUES (64, 1, 'F');
INSERT INTO `LocalizationStrings` VALUES (65, 1, 'M');
INSERT INTO `LocalizationStrings` VALUES (66, 1, 'C');
INSERT INTO `LocalizationStrings` VALUES (67, 1, 'About me');
INSERT INTO `LocalizationStrings` VALUES (70, 1, 'About us');
INSERT INTO `LocalizationStrings` VALUES (71, 1, 'About you');
INSERT INTO `LocalizationStrings` VALUES (72, 1, 'Activate account');
INSERT INTO `LocalizationStrings` VALUES (73, 1, 'Active Story');
INSERT INTO `LocalizationStrings` VALUES (74, 1, 'Add comment');
INSERT INTO `LocalizationStrings` VALUES (75, 1, 'add to cart');
INSERT INTO `LocalizationStrings` VALUES (76, 1, 'Add New Profile');
INSERT INTO `LocalizationStrings` VALUES (77, 1, 'Add feedback');
INSERT INTO `LocalizationStrings` VALUES (78, 1, 'Add new object');
INSERT INTO `LocalizationStrings` VALUES (79, 1, 'add feedback');
INSERT INTO `LocalizationStrings` VALUES (80, 1, 'Add to Hot List');
INSERT INTO `LocalizationStrings` VALUES (81, 1, 'Accept Invite');
INSERT INTO `LocalizationStrings` VALUES (82, 1, 'Adding member...');
INSERT INTO `LocalizationStrings` VALUES (83, 1, 'Additional contact information');
INSERT INTO `LocalizationStrings` VALUES (84, 1, 'Admin');
INSERT INTO `LocalizationStrings` VALUES (85, 1, 'advanced search');
INSERT INTO `LocalizationStrings` VALUES (86, 1, 'Affiliates');
INSERT INTO `LocalizationStrings` VALUES (87, 1, 'Aged from');
INSERT INTO `LocalizationStrings` VALUES (88, 1, 'aged');
INSERT INTO `LocalizationStrings` VALUES (89, 1, 'all');
INSERT INTO `LocalizationStrings` VALUES (90, 1, 'All');
INSERT INTO `LocalizationStrings` VALUES (91, 1, 'Amount');
INSERT INTO `LocalizationStrings` VALUES (92, 1, 'Anonymous');
INSERT INTO `LocalizationStrings` VALUES (94, 1, 'April');
INSERT INTO `LocalizationStrings` VALUES (95, 1, 'Articles');
INSERT INTO `LocalizationStrings` VALUES (96, 1, 'ascending');
INSERT INTO `LocalizationStrings` VALUES (97, 1, 'Attention');
INSERT INTO `LocalizationStrings` VALUES (98, 1, 'August');
INSERT INTO `LocalizationStrings` VALUES (100, 1, 'available');
INSERT INTO `LocalizationStrings` VALUES (101, 1, 'average rate');
INSERT INTO `LocalizationStrings` VALUES (102, 1, 'Availability for FREE');
INSERT INTO `LocalizationStrings` VALUES (103, 1, 'Back to');
INSERT INTO `LocalizationStrings` VALUES (104, 1, 'Back Invite');
INSERT INTO `LocalizationStrings` VALUES (105, 1, 'Bid');
INSERT INTO `LocalizationStrings` VALUES (106, 1, 'Block');
INSERT INTO `LocalizationStrings` VALUES (107, 1, 'Block list');
INSERT INTO `LocalizationStrings` VALUES (108, 1, 'block member');
INSERT INTO `LocalizationStrings` VALUES (109, 1, 'Blog');
INSERT INTO `LocalizationStrings` VALUES (110, 1, 'Body type');
INSERT INTO `LocalizationStrings` VALUES (111, 1, 'Body type');
INSERT INTO `LocalizationStrings` VALUES (112, 1, 'Body type of person 2');
INSERT INTO `LocalizationStrings` VALUES (113, 1, 'Both');
INSERT INTO `LocalizationStrings` VALUES (114, 1, 'both');
INSERT INTO `LocalizationStrings` VALUES (115, 1, 'Brief Profile');
INSERT INTO `LocalizationStrings` VALUES (116, 1, 'Browse Profiles');
INSERT INTO `LocalizationStrings` VALUES (117, 1, 'by age');
INSERT INTO `LocalizationStrings` VALUES (118, 1, 'by contact price');
INSERT INTO `LocalizationStrings` VALUES (119, 1, 'by rate');
INSERT INTO `LocalizationStrings` VALUES (120, 1, 'by times contacted');
INSERT INTO `LocalizationStrings` VALUES (121, 1, 'cannot send message, click <a target="_parent" href="{0}membership.php"> here </a> to start sending private messages.');
INSERT INTO `LocalizationStrings` VALUES (122, 1, 'cannot send message to inactive member.');
INSERT INTO `LocalizationStrings` VALUES (123, 1, 'your profile is not in active mode.');
INSERT INTO `LocalizationStrings` VALUES (124, 1, 'you are in the block list of this member.');
INSERT INTO `LocalizationStrings` VALUES (125, 1, 'characters');
INSERT INTO `LocalizationStrings` VALUES (126, 1, '{0} to {1} characters');
INSERT INTO `LocalizationStrings` VALUES (127, 1, 'chat');
INSERT INTO `LocalizationStrings` VALUES (129, 1, 'Online Chat');
INSERT INTO `LocalizationStrings` VALUES (130, 1, 'chat now');
INSERT INTO `LocalizationStrings` VALUES (131, 1, 'Chatting');
INSERT INTO `LocalizationStrings` VALUES (132, 1, 'chat window');
INSERT INTO `LocalizationStrings` VALUES (133, 1, 'Cancel password(s)');
INSERT INTO `LocalizationStrings` VALUES (134, 1, 'Cart');
INSERT INTO `LocalizationStrings` VALUES (135, 1, 'Continue shopping! You will receive these discounts:');
INSERT INTO `LocalizationStrings` VALUES (136, 1, 'Number of profiles');
INSERT INTO `LocalizationStrings` VALUES (137, 1, 'Total price');
INSERT INTO `LocalizationStrings` VALUES (138, 1, 'Cast my vote');
INSERT INTO `LocalizationStrings` VALUES (139, 1, 'Check Out');
INSERT INTO `LocalizationStrings` VALUES (140, 1, 'Check all');
INSERT INTO `LocalizationStrings` VALUES (141, 1, 'Children');
INSERT INTO `LocalizationStrings` VALUES (142, 1, 'Choose file for upload');
INSERT INTO `LocalizationStrings` VALUES (143, 1, 'children');
INSERT INTO `LocalizationStrings` VALUES (144, 1, 'City');
INSERT INTO `LocalizationStrings` VALUES (145, 1, 'City is required.');
INSERT INTO `LocalizationStrings` VALUES (146, 1, 'Please read "Terms of Use" before joining');
INSERT INTO `LocalizationStrings` VALUES (147, 1, 'Close');
INSERT INTO `LocalizationStrings` VALUES (148, 1, 'communicator');
INSERT INTO `LocalizationStrings` VALUES (149, 1, 'Communication');
INSERT INTO `LocalizationStrings` VALUES (150, 1, 'Community Stats');
INSERT INTO `LocalizationStrings` VALUES (151, 1, 'Compose New Message');
INSERT INTO `LocalizationStrings` VALUES (152, 1, 'Contact Sales');
INSERT INTO `LocalizationStrings` VALUES (153, 1, 'contacts');
INSERT INTO `LocalizationStrings` VALUES (156, 1, 'contact');
INSERT INTO `LocalizationStrings` VALUES (157, 1, 'contact list');
INSERT INTO `LocalizationStrings` VALUES (158, 1, 'Confirm E-mail');
INSERT INTO `LocalizationStrings` VALUES (159, 1, 'Confirm');
INSERT INTO `LocalizationStrings` VALUES (160, 1, 'Confirm password');
INSERT INTO `LocalizationStrings` VALUES (161, 1, 'Confirm your e-mail');
INSERT INTO `LocalizationStrings` VALUES (162, 1, 'Confirm your password');
INSERT INTO `LocalizationStrings` VALUES (163, 1, 'Confirmation code');
INSERT INTO `LocalizationStrings` VALUES (164, 1, 'Confirmation e-mail');
INSERT INTO `LocalizationStrings` VALUES (165, 1, 'Congratulations');
INSERT INTO `LocalizationStrings` VALUES (166, 1, 'Contact');
INSERT INTO `LocalizationStrings` VALUES (168, 1, 'Contacts');
INSERT INTO `LocalizationStrings` VALUES (169, 1, 'Contacts purchased');
INSERT INTO `LocalizationStrings` VALUES (170, 1, 'Contact information');
INSERT INTO `LocalizationStrings` VALUES (171, 1, 'Contact information sent');
INSERT INTO `LocalizationStrings` VALUES (172, 1, 'Contact information not sent');
INSERT INTO `LocalizationStrings` VALUES (173, 1, 'Contact price');
INSERT INTO `LocalizationStrings` VALUES (174, 1, 'Contact details');
INSERT INTO `LocalizationStrings` VALUES (175, 1, 'Continue');
INSERT INTO `LocalizationStrings` VALUES (176, 1, 'My Account');
INSERT INTO `LocalizationStrings` VALUES (177, 1, 'Country');
INSERT INTO `LocalizationStrings` VALUES (178, 1, 'Couple');
INSERT INTO `LocalizationStrings` VALUES (180, 1, 'creating lifestyle communities');
INSERT INTO `LocalizationStrings` VALUES (181, 1, 'Current deposit');
INSERT INTO `LocalizationStrings` VALUES (182, 1, 'Current membership');
INSERT INTO `LocalizationStrings` VALUES (183, 1, 'Currently Online');
INSERT INTO `LocalizationStrings` VALUES (184, 1, 'custom menu');
INSERT INTO `LocalizationStrings` VALUES (185, 1, 'Date');
INSERT INTO `LocalizationStrings` VALUES (186, 1, 'Date of birth');
INSERT INTO `LocalizationStrings` VALUES (187, 1, 'Age');
INSERT INTO `LocalizationStrings` VALUES (188, 1, 'Age of person 2');
INSERT INTO `LocalizationStrings` VALUES (189, 1, 'December');
INSERT INTO `LocalizationStrings` VALUES (190, 1, 'Delete');
INSERT INTO `LocalizationStrings` VALUES (191, 1, 'Delete account');
INSERT INTO `LocalizationStrings` VALUES (192, 1, 'Delete from Friend List');
INSERT INTO `LocalizationStrings` VALUES (193, 1, 'Deleting member...');
INSERT INTO `LocalizationStrings` VALUES (194, 1, 'Details of the person you are looking for');
INSERT INTO `LocalizationStrings` VALUES (195, 1, 'descending');
INSERT INTO `LocalizationStrings` VALUES (196, 1, 'Description');
INSERT INTO `LocalizationStrings` VALUES (199, 1, 'Do you really want to delete this entry?');
INSERT INTO `LocalizationStrings` VALUES (200, 1, 'Download and listen');
INSERT INTO `LocalizationStrings` VALUES (201, 1, 'Download and see');
INSERT INTO `LocalizationStrings` VALUES (202, 1, 'Doesn''t matter');
INSERT INTO `LocalizationStrings` VALUES (203, 1, 'Drinking');
INSERT INTO `LocalizationStrings` VALUES (204, 1, 'Drinking?');
INSERT INTO `LocalizationStrings` VALUES (205, 1, 'Drinker');
INSERT INTO `LocalizationStrings` VALUES (206, 1, '2nd person drinker');
INSERT INTO `LocalizationStrings` VALUES (207, 1, 'E-mail');
INSERT INTO `LocalizationStrings` VALUES (208, 1, 'Personal e-mail address required.');
INSERT INTO `LocalizationStrings` VALUES (209, 1, 'E-mail address doesn''t look valid.');
INSERT INTO `LocalizationStrings` VALUES (210, 1, 'E-mail sent');
INSERT INTO `LocalizationStrings` VALUES (211, 1, 'Nickname');
INSERT INTO `LocalizationStrings` VALUES (212, 1, 'Email confirmation');
INSERT INTO `LocalizationStrings` VALUES (213, 1, 'E-mail confirmed');
INSERT INTO `LocalizationStrings` VALUES (214, 1, 'Email was successfully sent');
INSERT INTO `LocalizationStrings` VALUES (215, 1, 'Email send failed');
INSERT INTO `LocalizationStrings` VALUES (216, 1, 'Education');
INSERT INTO `LocalizationStrings` VALUES (217, 1, 'Education of person 2');
INSERT INTO `LocalizationStrings` VALUES (218, 1, 'Edit Profile');
INSERT INTO `LocalizationStrings` VALUES (219, 1, 'edit profile');
INSERT INTO `LocalizationStrings` VALUES (220, 1, 'Edit');
INSERT INTO `LocalizationStrings` VALUES (221, 1, 'Empty');
INSERT INTO `LocalizationStrings` VALUES (222, 1, 'Empty Cart');
INSERT INTO `LocalizationStrings` VALUES (223, 1, 'Emptying cart...');
INSERT INTO `LocalizationStrings` VALUES (224, 1, 'Enter profile ID');
INSERT INTO `LocalizationStrings` VALUES (225, 1, 'Enter what you see:');
INSERT INTO `LocalizationStrings` VALUES (226, 1, 'Error');
INSERT INTO `LocalizationStrings` VALUES (227, 1, 'Error code');
INSERT INTO `LocalizationStrings` VALUES (228, 1, 'Error sending greeting');
INSERT INTO `LocalizationStrings` VALUES (229, 1, 'Ethnicity');
INSERT INTO `LocalizationStrings` VALUES (230, 1, 'Ethnicity of person 2');
INSERT INTO `LocalizationStrings` VALUES (231, 1, 'Extended search');
INSERT INTO `LocalizationStrings` VALUES (232, 1, 'Explanation');
INSERT INTO `LocalizationStrings` VALUES (233, 1, 'F');
INSERT INTO `LocalizationStrings` VALUES (234, 1, 'FAQ');
INSERT INTO `LocalizationStrings` VALUES (235, 1, 'February');
INSERT INTO `LocalizationStrings` VALUES (236, 1, 'Female');
INSERT INTO `LocalizationStrings` VALUES (237, 1, 'Female');
INSERT INTO `LocalizationStrings` VALUES (238, 1, 'Search');
INSERT INTO `LocalizationStrings` VALUES (239, 1, 'Find');
INSERT INTO `LocalizationStrings` VALUES (240, 1, 'Find!');
INSERT INTO `LocalizationStrings` VALUES (241, 1, 'Finance');
INSERT INTO `LocalizationStrings` VALUES (242, 1, 'First');
INSERT INTO `LocalizationStrings` VALUES (243, 1, 'get e-mail');
INSERT INTO `LocalizationStrings` VALUES (244, 1, 'Free Sign Up');
INSERT INTO `LocalizationStrings` VALUES (245, 1, 'Free sound');
INSERT INTO `LocalizationStrings` VALUES (246, 1, 'Friend email');
INSERT INTO `LocalizationStrings` VALUES (247, 1, 'Friends: ');
INSERT INTO `LocalizationStrings` VALUES (248, 1, 'Friends');
INSERT INTO `LocalizationStrings` VALUES (249, 1, 'Female');
INSERT INTO `LocalizationStrings` VALUES (250, 1, 'featured');
INSERT INTO `LocalizationStrings` VALUES (251, 1, 'featured members');
INSERT INTO `LocalizationStrings` VALUES (252, 1, 'featured profiles');
INSERT INTO `LocalizationStrings` VALUES (253, 1, 'for <b>free</b>');
INSERT INTO `LocalizationStrings` VALUES (254, 1, 'for');
INSERT INTO `LocalizationStrings` VALUES (256, 1, 'Forgot');
INSERT INTO `LocalizationStrings` VALUES (257, 1, 'Forgot password?');
INSERT INTO `LocalizationStrings` VALUES (258, 1, 'Forgot password?');
INSERT INTO `LocalizationStrings` VALUES (259, 1, 'former USSR');
INSERT INTO `LocalizationStrings` VALUES (260, 1, 'Forum');
INSERT INTO `LocalizationStrings` VALUES (261, 1, 'From');
INSERT INTO `LocalizationStrings` VALUES (262, 1, 'From Primary');
INSERT INTO `LocalizationStrings` VALUES (263, 1, 'from');
INSERT INTO `LocalizationStrings` VALUES (264, 1, 'from zip/postal code');
INSERT INTO `LocalizationStrings` VALUES (265, 1, 'from ZIP');
INSERT INTO `LocalizationStrings` VALUES (266, 1, 'free');
INSERT INTO `LocalizationStrings` VALUES (267, 1, 'Gallery');
INSERT INTO `LocalizationStrings` VALUES (268, 1, 'My gallery');
INSERT INTO `LocalizationStrings` VALUES (269, 1, 'General description');
INSERT INTO `LocalizationStrings` VALUES (270, 1, 'General self-description');
INSERT INTO `LocalizationStrings` VALUES (271, 1, 'Go');
INSERT INTO `LocalizationStrings` VALUES (272, 1, 'Affiliate Program');
INSERT INTO `LocalizationStrings` VALUES (273, 1, '<font color=red>Congratulations!!!</font><br />');
INSERT INTO `LocalizationStrings` VALUES (274, 1, 'You''ve got ');
INSERT INTO `LocalizationStrings` VALUES (275, 1, ' member(s) engaged. ');
INSERT INTO `LocalizationStrings` VALUES (276, 1, '(Need more members engaged to get new membership status )');
INSERT INTO `LocalizationStrings` VALUES (277, 1, ' You may choose your membership status');
INSERT INTO `LocalizationStrings` VALUES (278, 1, '<center>Congratulations!!!<br />You''re <font color=red>');
INSERT INTO `LocalizationStrings` VALUES (279, 1, ' </font>member now. Your membership will expire in ');
INSERT INTO `LocalizationStrings` VALUES (280, 1, ' days.</center>');
INSERT INTO `LocalizationStrings` VALUES (281, 1, 'Privileged Members');
INSERT INTO `LocalizationStrings` VALUES (282, 1, 'Membership Subscriptions');
INSERT INTO `LocalizationStrings` VALUES (283, 1, 'grant password');
INSERT INTO `LocalizationStrings` VALUES (284, 1, 'granted');
INSERT INTO `LocalizationStrings` VALUES (285, 1, 'GuestBook');
INSERT INTO `LocalizationStrings` VALUES (286, 1, 'Guestbook mode');
INSERT INTO `LocalizationStrings` VALUES (287, 1, 'Guestbook access');
INSERT INTO `LocalizationStrings` VALUES (288, 1, 'My GuestBook');
INSERT INTO `LocalizationStrings` VALUES (289, 1, 'Blog');
INSERT INTO `LocalizationStrings` VALUES (290, 1, 'My Blog');
INSERT INTO `LocalizationStrings` VALUES (291, 1, 'No info');
INSERT INTO `LocalizationStrings` VALUES (292, 1, 'Enable');
INSERT INTO `LocalizationStrings` VALUES (293, 1, 'Disable');
INSERT INTO `LocalizationStrings` VALUES (294, 1, 'Suspend');
INSERT INTO `LocalizationStrings` VALUES (295, 1, 'Registered only');
INSERT INTO `LocalizationStrings` VALUES (296, 1, 'Friends only');
INSERT INTO `LocalizationStrings` VALUES (297, 1, 'Add record');
INSERT INTO `LocalizationStrings` VALUES (298, 1, 'Visitor');
INSERT INTO `LocalizationStrings` VALUES (299, 1, 'Have {0} children');
INSERT INTO `LocalizationStrings` VALUES (300, 1, 'Have no children');
INSERT INTO `LocalizationStrings` VALUES (301, 1, 'Height');
INSERT INTO `LocalizationStrings` VALUES (302, 1, 'Height of person 2');
INSERT INTO `LocalizationStrings` VALUES (303, 1, 'Header');
INSERT INTO `LocalizationStrings` VALUES (305, 1, 'Headline');
INSERT INTO `LocalizationStrings` VALUES (306, 1, 'Hide');
INSERT INTO `LocalizationStrings` VALUES (307, 1, 'Home');
INSERT INTO `LocalizationStrings` VALUES (308, 1, 'Home address');
INSERT INTO `LocalizationStrings` VALUES (309, 1, 'Homepage');
INSERT INTO `LocalizationStrings` VALUES (310, 1, 'Hot list');
INSERT INTO `LocalizationStrings` VALUES (311, 1, 'hot member');
INSERT INTO `LocalizationStrings` VALUES (312, 1, 'Friend list');
INSERT INTO `LocalizationStrings` VALUES (313, 1, 'friend member');
INSERT INTO `LocalizationStrings` VALUES (314, 1, 'HTML');
INSERT INTO `LocalizationStrings` VALUES (315, 1, 'I agree with <a target="_blank" href="{0}terms_of_use.php"> Terms</a> and <a target="_blank" href="{0}privacy.php"> Privacy Policy</a>');
INSERT INTO `LocalizationStrings` VALUES (316, 1, 'I am');
INSERT INTO `LocalizationStrings` VALUES (317, 1, 'I am a');
INSERT INTO `LocalizationStrings` VALUES (318, 1, 'I seek a');
INSERT INTO `LocalizationStrings` VALUES (319, 1, 'I can receive');
INSERT INTO `LocalizationStrings` VALUES (320, 1, 'I''m looking for a');
INSERT INTO `LocalizationStrings` VALUES (321, 1, 'I prefer not to say');
INSERT INTO `LocalizationStrings` VALUES (322, 1, 'I will tell you later');
INSERT INTO `LocalizationStrings` VALUES (323, 1, 'ICQ UIN');
INSERT INTO `LocalizationStrings` VALUES (324, 1, 'ICQ');
INSERT INTO `LocalizationStrings` VALUES (325, 1, 'ICQ');
INSERT INTO `LocalizationStrings` VALUES (326, 1, 'IM UIN');
INSERT INTO `LocalizationStrings` VALUES (327, 1, 'Private Messages');
INSERT INTO `LocalizationStrings` VALUES (328, 1, 'starts immediately');
INSERT INTO `LocalizationStrings` VALUES (329, 1, 'IM {0} now!');
INSERT INTO `LocalizationStrings` VALUES (330, 1, 'Add users to IM and start chatting');
INSERT INTO `LocalizationStrings` VALUES (331, 1, 'Please select a user first');
INSERT INTO `LocalizationStrings` VALUES (332, 1, 'You should be an active member to use IM');
INSERT INTO `LocalizationStrings` VALUES (333, 1, 'You should be a gold member to use IM');
INSERT INTO `LocalizationStrings` VALUES (334, 1, 'Please login first');
INSERT INTO `LocalizationStrings` VALUES (335, 1, 'Send');
INSERT INTO `LocalizationStrings` VALUES (336, 1, 'E-Mail or ID');
INSERT INTO `LocalizationStrings` VALUES (337, 1, 'Ideal match description');
INSERT INTO `LocalizationStrings` VALUES (338, 1, 'Income');
INSERT INTO `LocalizationStrings` VALUES (339, 1, 'Income of person 2');
INSERT INTO `LocalizationStrings` VALUES (340, 1, 'Incorrect Email');
INSERT INTO `LocalizationStrings` VALUES (342, 1, 'Interest');
INSERT INTO `LocalizationStrings` VALUES (343, 1, 'Invalid ID');
INSERT INTO `LocalizationStrings` VALUES (344, 1, 'Invite a friend');
INSERT INTO `LocalizationStrings` VALUES (345, 1, 'January');
INSERT INTO `LocalizationStrings` VALUES (346, 1, 'Join');
INSERT INTO `LocalizationStrings` VALUES (347, 1, 'Join For Free');
INSERT INTO `LocalizationStrings` VALUES (348, 1, 'Join Free');
INSERT INTO `LocalizationStrings` VALUES (349, 1, 'Join Now');
INSERT INTO `LocalizationStrings` VALUES (350, 1, 'June');
INSERT INTO `LocalizationStrings` VALUES (351, 1, 'July');
INSERT INTO `LocalizationStrings` VALUES (352, 1, 'kilometers');
INSERT INTO `LocalizationStrings` VALUES (353, 1, 'kb');
INSERT INTO `LocalizationStrings` VALUES (354, 1, 'Greetings');
INSERT INTO `LocalizationStrings` VALUES (355, 1, 'Language');
INSERT INTO `LocalizationStrings` VALUES (356, 1, 'Language 1');
INSERT INTO `LocalizationStrings` VALUES (357, 1, 'Language 2');
INSERT INTO `LocalizationStrings` VALUES (358, 1, 'Language 3');
INSERT INTO `LocalizationStrings` VALUES (359, 1, 'Language');
INSERT INTO `LocalizationStrings` VALUES (360, 1, 'Language of person 2');
INSERT INTO `LocalizationStrings` VALUES (361, 1, 'Last');
INSERT INTO `LocalizationStrings` VALUES (362, 1, 'Last login');
INSERT INTO `LocalizationStrings` VALUES (363, 1, 'Last logged in');
INSERT INTO `LocalizationStrings` VALUES (364, 1, 'Last changes');
INSERT INTO `LocalizationStrings` VALUES (365, 1, 'Last modified');
INSERT INTO `LocalizationStrings` VALUES (366, 1, 'latest news');
INSERT INTO `LocalizationStrings` VALUES (367, 1, 'Latest Members');
INSERT INTO `LocalizationStrings` VALUES (368, 1, 'launch IM');
INSERT INTO `LocalizationStrings` VALUES (369, 1, 'Links');
INSERT INTO `LocalizationStrings` VALUES (370, 1, 'living with me');
INSERT INTO `LocalizationStrings` VALUES (371, 1, 'living within');
INSERT INTO `LocalizationStrings` VALUES (372, 1, 'listen to voice');
INSERT INTO `LocalizationStrings` VALUES (373, 1, 'length');
INSERT INTO `LocalizationStrings` VALUES (374, 1, 'Location');
INSERT INTO `LocalizationStrings` VALUES (375, 1, 'Log in');
INSERT INTO `LocalizationStrings` VALUES (376, 1, 'Login');
INSERT INTO `LocalizationStrings` VALUES (377, 1, 'LOG IN');
INSERT INTO `LocalizationStrings` VALUES (378, 1, 'log out');
INSERT INTO `LocalizationStrings` VALUES (379, 1, 'Log Out');
INSERT INTO `LocalizationStrings` VALUES (380, 1, 'Logged in');
INSERT INTO `LocalizationStrings` VALUES (381, 1, 'Login required');
INSERT INTO `LocalizationStrings` VALUES (382, 1, 'Already a member?');
INSERT INTO `LocalizationStrings` VALUES (384, 1, 'Looking for');
INSERT INTO `LocalizationStrings` VALUES (385, 1, 'Looking for an age range');
INSERT INTO `LocalizationStrings` VALUES (386, 1, 'Looking for a height');
INSERT INTO `LocalizationStrings` VALUES (387, 1, 'Looking for a body type');
INSERT INTO `LocalizationStrings` VALUES (388, 1, 'Looking for');
INSERT INTO `LocalizationStrings` VALUES (389, 1, 'looking for');
INSERT INTO `LocalizationStrings` VALUES (390, 1, 'M');
INSERT INTO `LocalizationStrings` VALUES (391, 1, 'Must be valid');
INSERT INTO `LocalizationStrings` VALUES (392, 1, 'MSN');
INSERT INTO `LocalizationStrings` VALUES (393, 1, 'MSN');
INSERT INTO `LocalizationStrings` VALUES (394, 1, 'MAIN MENU');
INSERT INTO `LocalizationStrings` VALUES (395, 1, 'Main Menu');
INSERT INTO `LocalizationStrings` VALUES (397, 1, 'Make thumb out of primary');
INSERT INTO `LocalizationStrings` VALUES (398, 1, 'Failed to make thumb out of primary!');
INSERT INTO `LocalizationStrings` VALUES (399, 1, 'Successfully made thumb out of primary!');
INSERT INTO `LocalizationStrings` VALUES (400, 1, 'Male');
INSERT INTO `LocalizationStrings` VALUES (401, 1, 'Male');
INSERT INTO `LocalizationStrings` VALUES (402, 1, 'Male or female');
INSERT INTO `LocalizationStrings` VALUES (403, 1, 'Male or female');
INSERT INTO `LocalizationStrings` VALUES (404, 1, 'March');
INSERT INTO `LocalizationStrings` VALUES (405, 1, 'man');
INSERT INTO `LocalizationStrings` VALUES (406, 1, 'Men');
INSERT INTO `LocalizationStrings` VALUES (407, 1, 'men');
INSERT INTO `LocalizationStrings` VALUES (408, 1, 'men and women');
INSERT INTO `LocalizationStrings` VALUES (409, 1, 'man');
INSERT INTO `LocalizationStrings` VALUES (410, 1, 'Manage objects');
INSERT INTO `LocalizationStrings` VALUES (411, 1, 'Manage albums');
INSERT INTO `LocalizationStrings` VALUES (413, 1, 'Male');
INSERT INTO `LocalizationStrings` VALUES (414, 1, 'Marital status');
INSERT INTO `LocalizationStrings` VALUES (415, 1, 'Marital status');
INSERT INTO `LocalizationStrings` VALUES (416, 1, 'Mark as New');
INSERT INTO `LocalizationStrings` VALUES (417, 1, 'Mark as read');
INSERT INTO `LocalizationStrings` VALUES (418, 1, 'Mark as Featured');
INSERT INTO `LocalizationStrings` VALUES (419, 1, 'Maximum characters');
INSERT INTO `LocalizationStrings` VALUES (420, 1, 'May');
INSERT INTO `LocalizationStrings` VALUES (421, 1, 'Maybe');
INSERT INTO `LocalizationStrings` VALUES (422, 1, 'member menu');
INSERT INTO `LocalizationStrings` VALUES (423, 1, 'Member');
INSERT INTO `LocalizationStrings` VALUES (424, 1, 'Member control panel');
INSERT INTO `LocalizationStrings` VALUES (425, 1, 'Member Information');
INSERT INTO `LocalizationStrings` VALUES (426, 1, 'Member Login');
INSERT INTO `LocalizationStrings` VALUES (427, 1, 'member menu');
INSERT INTO `LocalizationStrings` VALUES (428, 1, 'Member Profile');
INSERT INTO `LocalizationStrings` VALUES (429, 1, 'Member profile not available for view.');
INSERT INTO `LocalizationStrings` VALUES (430, 1, 'Member sound');
INSERT INTO `LocalizationStrings` VALUES (431, 1, 'Member video');
INSERT INTO `LocalizationStrings` VALUES (432, 1, 'members');
INSERT INTO `LocalizationStrings` VALUES (433, 1, 'Hot members');
INSERT INTO `LocalizationStrings` VALUES (434, 1, 'Friend members');
INSERT INTO `LocalizationStrings` VALUES (435, 1, 'Blocked members');
INSERT INTO `LocalizationStrings` VALUES (436, 1, 'Greeted members');
INSERT INTO `LocalizationStrings` VALUES (437, 1, 'Viewed members');
INSERT INTO `LocalizationStrings` VALUES (438, 1, 'Contacted members');
INSERT INTO `LocalizationStrings` VALUES (439, 1, 'Contacted for free members');
INSERT INTO `LocalizationStrings` VALUES (440, 1, 'Private photos passwords');
INSERT INTO `LocalizationStrings` VALUES (441, 1, 'member info');
INSERT INTO `LocalizationStrings` VALUES (442, 1, 'membership');
INSERT INTO `LocalizationStrings` VALUES (443, 1, 'Membership');
INSERT INTO `LocalizationStrings` VALUES (444, 1, 'Membership');
INSERT INTO `LocalizationStrings` VALUES (448, 1, 'Recipient not found');
INSERT INTO `LocalizationStrings` VALUES (449, 1, 'Available Membership Types');
INSERT INTO `LocalizationStrings` VALUES (450, 1, ' days');
INSERT INTO `LocalizationStrings` VALUES (452, 1, 'Credits');
INSERT INTO `LocalizationStrings` VALUES (453, 1, 'Membership Status');
INSERT INTO `LocalizationStrings` VALUES (454, 1, 'Message from');
INSERT INTO `LocalizationStrings` VALUES (455, 1, 'Message not available');
INSERT INTO `LocalizationStrings` VALUES (456, 1, 'Message Preview');
INSERT INTO `LocalizationStrings` VALUES (457, 1, 'Message text');
INSERT INTO `LocalizationStrings` VALUES (458, 1, 'Messages');
INSERT INTO `LocalizationStrings` VALUES (459, 1, 'Messages in Inbox');
INSERT INTO `LocalizationStrings` VALUES (460, 1, 'Messages in Outbox');
INSERT INTO `LocalizationStrings` VALUES (461, 1, 'miles');
INSERT INTO `LocalizationStrings` VALUES (462, 1, 'km');
INSERT INTO `LocalizationStrings` VALUES (463, 1, 'Moderator');
INSERT INTO `LocalizationStrings` VALUES (464, 1, 'more photo(s)');
INSERT INTO `LocalizationStrings` VALUES (465, 1, '<b>View other photos of {0}</b>');
INSERT INTO `LocalizationStrings` VALUES (466, 1, 'more');
INSERT INTO `LocalizationStrings` VALUES (467, 1, '...');
INSERT INTO `LocalizationStrings` VALUES (468, 1, 'My Email');
INSERT INTO `LocalizationStrings` VALUES (469, 1, 'my inbox');
INSERT INTO `LocalizationStrings` VALUES (470, 1, 'my outbox');
INSERT INTO `LocalizationStrings` VALUES (471, 1, 'My Membership');
INSERT INTO `LocalizationStrings` VALUES (472, 1, 'My Messenger');
INSERT INTO `LocalizationStrings` VALUES (473, 1, 'My Panel');
INSERT INTO `LocalizationStrings` VALUES (474, 1, 'My Photo Gallery');
INSERT INTO `LocalizationStrings` VALUES (475, 1, 'my profile');
INSERT INTO `LocalizationStrings` VALUES (476, 1, 'Cannot save image');
INSERT INTO `LocalizationStrings` VALUES (477, 1, 'Name');
INSERT INTO `LocalizationStrings` VALUES (478, 1, 'never');
INSERT INTO `LocalizationStrings` VALUES (479, 1, 'new');
INSERT INTO `LocalizationStrings` VALUES (480, 1, 'New message');
INSERT INTO `LocalizationStrings` VALUES (481, 1, 'New Member');
INSERT INTO `LocalizationStrings` VALUES (482, 1, 'Add a new member here');
INSERT INTO `LocalizationStrings` VALUES (483, 1, 'New profile has been successfully created.');
INSERT INTO `LocalizationStrings` VALUES (484, 1, 'New picture');
INSERT INTO `LocalizationStrings` VALUES (485, 1, 'New sound');
INSERT INTO `LocalizationStrings` VALUES (486, 1, 'New video');
INSERT INTO `LocalizationStrings` VALUES (487, 1, 'New this week');
INSERT INTO `LocalizationStrings` VALUES (488, 1, 'newsletter');
INSERT INTO `LocalizationStrings` VALUES (489, 1, 'Next');
INSERT INTO `LocalizationStrings` VALUES (490, 1, 'Next >>');
INSERT INTO `LocalizationStrings` VALUES (491, 1, 'News Archive');
INSERT INTO `LocalizationStrings` VALUES (492, 1, '''s profile');
INSERT INTO `LocalizationStrings` VALUES (493, 1, 'Username');
INSERT INTO `LocalizationStrings` VALUES (494, 1, 'Username');
INSERT INTO `LocalizationStrings` VALUES (495, 1, 'Profile type');
INSERT INTO `LocalizationStrings` VALUES (496, 1, 'Username is required.');
INSERT INTO `LocalizationStrings` VALUES (497, 1, 'No');
INSERT INTO `LocalizationStrings` VALUES (498, 1, 'Doesn''t matter');
INSERT INTO `LocalizationStrings` VALUES (499, 1, 'No member to add');
INSERT INTO `LocalizationStrings` VALUES (500, 1, 'No member to delete');
INSERT INTO `LocalizationStrings` VALUES (501, 1, 'No member specified');
INSERT INTO `LocalizationStrings` VALUES (502, 1, 'No gold membership options available for the moment. Please, check later.');
INSERT INTO `LocalizationStrings` VALUES (503, 1, 'No messages in Inbox');
INSERT INTO `LocalizationStrings` VALUES (504, 1, 'No messages in Outbox');
INSERT INTO `LocalizationStrings` VALUES (505, 1, 'No modifications were done.');
INSERT INTO `LocalizationStrings` VALUES (506, 1, 'No news available');
INSERT INTO `LocalizationStrings` VALUES (507, 1, 'No polls available');
INSERT INTO `LocalizationStrings` VALUES (508, 1, 'No pictures available');
INSERT INTO `LocalizationStrings` VALUES (509, 1, 'No results found.');
INSERT INTO `LocalizationStrings` VALUES (510, 1, 'No sounds available');
INSERT INTO `LocalizationStrings` VALUES (511, 1, 'No feedback available.');
INSERT INTO `LocalizationStrings` VALUES (512, 1, ' no such name or no message');
INSERT INTO `LocalizationStrings` VALUES (513, 1, 'No video available');
INSERT INTO `LocalizationStrings` VALUES (514, 1, 'None');
INSERT INTO `LocalizationStrings` VALUES (515, 1, 'none');
INSERT INTO `LocalizationStrings` VALUES (517, 1, 'not living with me');
INSERT INTO `LocalizationStrings` VALUES (518, 1, 'not granted');
INSERT INTO `LocalizationStrings` VALUES (519, 1, 'Not read');
INSERT INTO `LocalizationStrings` VALUES (520, 1, 'not read');
INSERT INTO `LocalizationStrings` VALUES (521, 1, 'Not Recognized');
INSERT INTO `LocalizationStrings` VALUES (522, 1, 'Not sure');
INSERT INTO `LocalizationStrings` VALUES (523, 1, 'Do Not Notify Me');
INSERT INTO `LocalizationStrings` VALUES (524, 1, 'Notification email send failed');
INSERT INTO `LocalizationStrings` VALUES (525, 1, 'Notify me about news, tips');
INSERT INTO `LocalizationStrings` VALUES (526, 1, 'Notify Me');
INSERT INTO `LocalizationStrings` VALUES (527, 1, 'Notify by e-mail');
INSERT INTO `LocalizationStrings` VALUES (528, 1, 'November');
INSERT INTO `LocalizationStrings` VALUES (529, 1, 'Occupation');
INSERT INTO `LocalizationStrings` VALUES (530, 1, 'October');
INSERT INTO `LocalizationStrings` VALUES (532, 1, 'only');
INSERT INTO `LocalizationStrings` VALUES (533, 1, 'Online');
INSERT INTO `LocalizationStrings` VALUES (534, 1, 'Online Members');
INSERT INTO `LocalizationStrings` VALUES (535, 1, 'online only');
INSERT INTO `LocalizationStrings` VALUES (536, 1, 'Offline');
INSERT INTO `LocalizationStrings` VALUES (537, 1, 'Ok, entry not deleted.');
INSERT INTO `LocalizationStrings` VALUES (538, 1, 'Ok, entry was deleted successful.');
INSERT INTO `LocalizationStrings` VALUES (539, 1, 'Oops, cannot delete this entry.');
INSERT INTO `LocalizationStrings` VALUES (540, 1, 'Open in new window');
INSERT INTO `LocalizationStrings` VALUES (541, 1, 'or send request for password');
INSERT INTO `LocalizationStrings` VALUES (542, 1, 'Other details');
INSERT INTO `LocalizationStrings` VALUES (543, 1, 'page navigation');
INSERT INTO `LocalizationStrings` VALUES (544, 1, 'Pages');
INSERT INTO `LocalizationStrings` VALUES (545, 1, 'Password');
INSERT INTO `LocalizationStrings` VALUES (546, 1, 'Pass');
INSERT INTO `LocalizationStrings` VALUES (547, 1, 'Password granted');
INSERT INTO `LocalizationStrings` VALUES (548, 1, 'Password must be from 4 to 8 characters long.');
INSERT INTO `LocalizationStrings` VALUES (549, 1, 'Member password retrieval at {0}');
INSERT INTO `LocalizationStrings` VALUES (550, 1, 'Pay-per-contact');
INSERT INTO `LocalizationStrings` VALUES (551, 1, 'Personal details');
INSERT INTO `LocalizationStrings` VALUES (552, 1, 'Details of second person');
INSERT INTO `LocalizationStrings` VALUES (553, 1, 'Phone');
INSERT INTO `LocalizationStrings` VALUES (554, 1, 'Photo successfully deleted');
INSERT INTO `LocalizationStrings` VALUES (555, 1, 'with pics only');
INSERT INTO `LocalizationStrings` VALUES (556, 1, 'Picture');
INSERT INTO `LocalizationStrings` VALUES (557, 1, 'Polls');
INSERT INTO `LocalizationStrings` VALUES (558, 1, 'post my feedback');
INSERT INTO `LocalizationStrings` VALUES (559, 1, 'Notify about requests');
INSERT INTO `LocalizationStrings` VALUES (560, 1, 'Notify me about requests for private photos via email');
INSERT INTO `LocalizationStrings` VALUES (561, 1, 'Prev');
INSERT INTO `LocalizationStrings` VALUES (562, 1, 'Preview');
INSERT INTO `LocalizationStrings` VALUES (564, 1, 'Price, ');
INSERT INTO `LocalizationStrings` VALUES (565, 1, 'Primary Picture');
INSERT INTO `LocalizationStrings` VALUES (566, 1, 'Primary photo successfully deleted.');
INSERT INTO `LocalizationStrings` VALUES (567, 1, 'Primary photo remove failed.');
INSERT INTO `LocalizationStrings` VALUES (568, 1, 'private message');
INSERT INTO `LocalizationStrings` VALUES (569, 1, 'Private photo');
INSERT INTO `LocalizationStrings` VALUES (570, 1, 'Privacy');
INSERT INTO `LocalizationStrings` VALUES (571, 1, 'Private photo password');
INSERT INTO `LocalizationStrings` VALUES (572, 1, 'Private password');
INSERT INTO `LocalizationStrings` VALUES (573, 1, 'Profile last modified');
INSERT INTO `LocalizationStrings` VALUES (574, 1, 'Profile status');
INSERT INTO `LocalizationStrings` VALUES (575, 1, 'Profile not available for view');
INSERT INTO `LocalizationStrings` VALUES (576, 1, 'Profile has not been found');
INSERT INTO `LocalizationStrings` VALUES (577, 1, 'Specified profile not found in the database. It must have been removed earlier.');
INSERT INTO `LocalizationStrings` VALUES (578, 1, 'Profiles');
INSERT INTO `LocalizationStrings` VALUES (579, 1, 'Profile');
INSERT INTO `LocalizationStrings` VALUES (580, 1, 'Profile activation failed.');
INSERT INTO `LocalizationStrings` VALUES (581, 1, 'Profile of the week');
INSERT INTO `LocalizationStrings` VALUES (582, 1, 'Profile of the month');
INSERT INTO `LocalizationStrings` VALUES (583, 1, 'Purchased contacts');
INSERT INTO `LocalizationStrings` VALUES (584, 1, 'private');
INSERT INTO `LocalizationStrings` VALUES (585, 1, 'public');
INSERT INTO `LocalizationStrings` VALUES (586, 1, 'friends only');
INSERT INTO `LocalizationStrings` VALUES (587, 1, 'random profiles');
INSERT INTO `LocalizationStrings` VALUES (588, 1, 'Random Members');
INSERT INTO `LocalizationStrings` VALUES (589, 1, 'rate');
INSERT INTO `LocalizationStrings` VALUES (590, 1, 'rate profile');
INSERT INTO `LocalizationStrings` VALUES (591, 1, 'rate photos');
INSERT INTO `LocalizationStrings` VALUES (592, 1, 'Rate Photos');
INSERT INTO `LocalizationStrings` VALUES (593, 1, 'Read access:');
INSERT INTO `LocalizationStrings` VALUES (594, 1, 'Read more');
INSERT INTO `LocalizationStrings` VALUES (595, 1, 'Read');
INSERT INTO `LocalizationStrings` VALUES (596, 1, 'Read news in archive');
INSERT INTO `LocalizationStrings` VALUES (597, 1, 'Real name');
INSERT INTO `LocalizationStrings` VALUES (598, 1, 'Real name required.');
INSERT INTO `LocalizationStrings` VALUES (599, 1, 'Recognized');
INSERT INTO `LocalizationStrings` VALUES (600, 1, 'Registration error');
INSERT INTO `LocalizationStrings` VALUES (601, 1, 'Reject invite');
INSERT INTO `LocalizationStrings` VALUES (602, 1, 'Reject Invite');
INSERT INTO `LocalizationStrings` VALUES (603, 1, 'Relationship');
INSERT INTO `LocalizationStrings` VALUES (604, 1, 'Relationship: at least one type must be specified.');
INSERT INTO `LocalizationStrings` VALUES (605, 1, 'Religion');
INSERT INTO `LocalizationStrings` VALUES (606, 1, 'Religion of person 2');
INSERT INTO `LocalizationStrings` VALUES (607, 1, 'Reply');
INSERT INTO `LocalizationStrings` VALUES (608, 1, 'Report about spam was sent');
INSERT INTO `LocalizationStrings` VALUES (609, 1, 'Report about spam failed to send');
INSERT INTO `LocalizationStrings` VALUES (610, 1, 'report member');
INSERT INTO `LocalizationStrings` VALUES (611, 1, 'Results per page');
INSERT INTO `LocalizationStrings` VALUES (612, 1, 'Results');
INSERT INTO `LocalizationStrings` VALUES (613, 1, 'Retrieve');
INSERT INTO `LocalizationStrings` VALUES (614, 1, 'Retrieve my information');
INSERT INTO `LocalizationStrings` VALUES (615, 1, 'Quick Links');
INSERT INTO `LocalizationStrings` VALUES (616, 1, 'Quick Search');
INSERT INTO `LocalizationStrings` VALUES (617, 1, 'Save Changes');
INSERT INTO `LocalizationStrings` VALUES (618, 1, 'Services');
INSERT INTO `LocalizationStrings` VALUES (619, 1, 'services');
INSERT INTO `LocalizationStrings` VALUES (620, 1, 'sec.');
INSERT INTO `LocalizationStrings` VALUES (621, 1, 'send a greeting');
INSERT INTO `LocalizationStrings` VALUES (622, 1, 'email to friend');
INSERT INTO `LocalizationStrings` VALUES (623, 1, 'score');
INSERT INTO `LocalizationStrings` VALUES (624, 1, 'size');
INSERT INTO `LocalizationStrings` VALUES (625, 1, 'single');
INSERT INTO `LocalizationStrings` VALUES (626, 1, 'sign up free');
INSERT INTO `LocalizationStrings` VALUES (627, 1, 'Code from security images is incorrect');
INSERT INTO `LocalizationStrings` VALUES (628, 1, 'Search');
INSERT INTO `LocalizationStrings` VALUES (629, 1, 'Search result');
INSERT INTO `LocalizationStrings` VALUES (630, 1, 'Search by ID');
INSERT INTO `LocalizationStrings` VALUES (631, 1, 'Search by Nickname');
INSERT INTO `LocalizationStrings` VALUES (632, 1, 'Search by distance');
INSERT INTO `LocalizationStrings` VALUES (633, 1, 'Search Profiles');
INSERT INTO `LocalizationStrings` VALUES (634, 1, 'Search profiles');
INSERT INTO `LocalizationStrings` VALUES (635, 1, 'Secondary Picture');
INSERT INTO `LocalizationStrings` VALUES (636, 1, 'Secondary photo successfully deleted.');
INSERT INTO `LocalizationStrings` VALUES (637, 1, 'Secondary photo remove failed');
INSERT INTO `LocalizationStrings` VALUES (638, 1, 'See profile');
INSERT INTO `LocalizationStrings` VALUES (639, 1, 'See {0}''s Profile');
INSERT INTO `LocalizationStrings` VALUES (640, 1, 'seeking a');
INSERT INTO `LocalizationStrings` VALUES (641, 1, 'Seeking a');
INSERT INTO `LocalizationStrings` VALUES (642, 1, 'I am a');
INSERT INTO `LocalizationStrings` VALUES (643, 1, 'search profiles');
INSERT INTO `LocalizationStrings` VALUES (644, 1, 'Country');
INSERT INTO `LocalizationStrings` VALUES (645, 1, 'Age');
INSERT INTO `LocalizationStrings` VALUES (646, 1, 'Height');
INSERT INTO `LocalizationStrings` VALUES (647, 1, 'Body Type');
INSERT INTO `LocalizationStrings` VALUES (648, 1, 'Religion');
INSERT INTO `LocalizationStrings` VALUES (649, 1, 'Ethnicity');
INSERT INTO `LocalizationStrings` VALUES (650, 1, 'Marital Status');
INSERT INTO `LocalizationStrings` VALUES (651, 1, 'Education');
INSERT INTO `LocalizationStrings` VALUES (652, 1, 'Income');
INSERT INTO `LocalizationStrings` VALUES (653, 1, 'Smoker');
INSERT INTO `LocalizationStrings` VALUES (654, 1, 'Drinker');
INSERT INTO `LocalizationStrings` VALUES (655, 1, 'Looking for a');
INSERT INTO `LocalizationStrings` VALUES (656, 1, 'Language');
INSERT INTO `LocalizationStrings` VALUES (657, 1, 'Selected messages');
INSERT INTO `LocalizationStrings` VALUES (658, 1, 'Send');
INSERT INTO `LocalizationStrings` VALUES (659, 1, 'Send a message');
INSERT INTO `LocalizationStrings` VALUES (660, 1, 'Send a message to:');
INSERT INTO `LocalizationStrings` VALUES (661, 1, 'Send e-mail');
INSERT INTO `LocalizationStrings` VALUES (662, 1, 'Greeting');
INSERT INTO `LocalizationStrings` VALUES (663, 1, 'greeting');
INSERT INTO `LocalizationStrings` VALUES (664, 1, 'Cannot send now');
INSERT INTO `LocalizationStrings` VALUES (665, 1, 'to site e-mail');
INSERT INTO `LocalizationStrings` VALUES (666, 1, 'to personal e-mail');
INSERT INTO `LocalizationStrings` VALUES (667, 1, 'Send greeting');
INSERT INTO `LocalizationStrings` VALUES (668, 1, 'Greeting sent');
INSERT INTO `LocalizationStrings` VALUES (669, 1, 'Greeting NOT sent');
INSERT INTO `LocalizationStrings` VALUES (670, 1, 'Send Letter');
INSERT INTO `LocalizationStrings` VALUES (671, 1, 'September');
INSERT INTO `LocalizationStrings` VALUES (672, 1, 'Settings');
INSERT INTO `LocalizationStrings` VALUES (673, 1, 'Set membership');
INSERT INTO `LocalizationStrings` VALUES (674, 1, 'Sex');
INSERT INTO `LocalizationStrings` VALUES (675, 1, 'Shopping Cart');
INSERT INTO `LocalizationStrings` VALUES (676, 1, 'Shopping cart emptied');
INSERT INTO `LocalizationStrings` VALUES (677, 1, 'Short Profiles Search');
INSERT INTO `LocalizationStrings` VALUES (678, 1, 'ShoutBox');
INSERT INTO `LocalizationStrings` VALUES (679, 1, 'Show');
INSERT INTO `LocalizationStrings` VALUES (680, 1, 'Show me');
INSERT INTO `LocalizationStrings` VALUES (683, 1, 'Smoker');
INSERT INTO `LocalizationStrings` VALUES (684, 1, '2nd person smoker');
INSERT INTO `LocalizationStrings` VALUES (685, 1, 'sometimes living with me');
INSERT INTO `LocalizationStrings` VALUES (686, 1, 'Sorry');
INSERT INTO `LocalizationStrings` VALUES (687, 1, 'Sorry, I can''t define your IP address. IT''S TIME TO COME OUT!');
INSERT INTO `LocalizationStrings` VALUES (688, 1, 'Sorry, but user is OFFLINE at the moment.\\nPlease try later...');
INSERT INTO `LocalizationStrings` VALUES (689, 1, 'sort');
INSERT INTO `LocalizationStrings` VALUES (690, 1, 'Sort order');
INSERT INTO `LocalizationStrings` VALUES (691, 1, 'Sort results');
INSERT INTO `LocalizationStrings` VALUES (692, 1, 'Sound');
INSERT INTO `LocalizationStrings` VALUES (693, 1, 'Report Spam');
INSERT INTO `LocalizationStrings` VALUES (694, 1, 'spam report');
INSERT INTO `LocalizationStrings` VALUES (695, 1, 'Special offer');
INSERT INTO `LocalizationStrings` VALUES (696, 1, 'Spoken languages');
INSERT INTO `LocalizationStrings` VALUES (697, 1, 'can speak');
INSERT INTO `LocalizationStrings` VALUES (698, 1, 'Status');
INSERT INTO `LocalizationStrings` VALUES (699, 1, 'feedback');
INSERT INTO `LocalizationStrings` VALUES (700, 1, 'Feedback');
INSERT INTO `LocalizationStrings` VALUES (701, 1, 'Submit');
INSERT INTO `LocalizationStrings` VALUES (702, 1, 'Submit request');
INSERT INTO `LocalizationStrings` VALUES (703, 1, 'Subscribe');
INSERT INTO `LocalizationStrings` VALUES (704, 1, 'Subject');
INSERT INTO `LocalizationStrings` VALUES (705, 1, 'Successfully uploaded!');
INSERT INTO `LocalizationStrings` VALUES (706, 1, 'feedback');
INSERT INTO `LocalizationStrings` VALUES (707, 1, 'Suspend account');
INSERT INTO `LocalizationStrings` VALUES (708, 1, 'Site poll');
INSERT INTO `LocalizationStrings` VALUES (709, 1, 'Text');
INSERT INTO `LocalizationStrings` VALUES (710, 1, 'Terms');
INSERT INTO `LocalizationStrings` VALUES (711, 1, 'Invite a friend');
INSERT INTO `LocalizationStrings` VALUES (712, 1, 'Theme');
INSERT INTO `LocalizationStrings` VALUES (713, 1, 'This guestbook disabled by it''s owner');
INSERT INTO `LocalizationStrings` VALUES (714, 1, 'This guestbook allowed for registered members only');
INSERT INTO `LocalizationStrings` VALUES (715, 1, 'This guestbook allowed for friends only');
INSERT INTO `LocalizationStrings` VALUES (716, 1, 'You can not write any messages, this guestbook is suspended');
INSERT INTO `LocalizationStrings` VALUES (717, 1, 'Thumbnail');
INSERT INTO `LocalizationStrings` VALUES (718, 1, 'Thumbnail successfully deleted.');
INSERT INTO `LocalizationStrings` VALUES (719, 1, '_Thumbnail remove failed.');
INSERT INTO `LocalizationStrings` VALUES (720, 1, 'Thumb');
INSERT INTO `LocalizationStrings` VALUES (721, 1, 'Timely');
INSERT INTO `LocalizationStrings` VALUES (722, 1, 'time(s)');
INSERT INTO `LocalizationStrings` VALUES (723, 1, 'to');
INSERT INTO `LocalizationStrings` VALUES (724, 1, 'to');
INSERT INTO `LocalizationStrings` VALUES (725, 1, 'To post you must be logged in!');
INSERT INTO `LocalizationStrings` VALUES (726, 1, '_To view the photos you have to become a gold member. Go to <a href=\\"{$site[''url'']}membership.php\\" target=\\"_blank\\">Gold Membership page</a> to purchase membership.');
INSERT INTO `LocalizationStrings` VALUES (727, 1, 'Top Rated');
INSERT INTO `LocalizationStrings` VALUES (728, 1, 'Top Members');
INSERT INTO `LocalizationStrings` VALUES (729, 1, 'Total');
INSERT INTO `LocalizationStrings` VALUES (730, 1, 'total');
INSERT INTO `LocalizationStrings` VALUES (731, 1, 'Total amount');
INSERT INTO `LocalizationStrings` VALUES (732, 1, 'Total price');
INSERT INTO `LocalizationStrings` VALUES (733, 1, 'Total Registered');
INSERT INTO `LocalizationStrings` VALUES (734, 1, 'total votes');
INSERT INTO `LocalizationStrings` VALUES (735, 1, 'Uncheck all');
INSERT INTO `LocalizationStrings` VALUES (736, 1, 'Unblock');
INSERT INTO `LocalizationStrings` VALUES (737, 1, 'Undefined error');
INSERT INTO `LocalizationStrings` VALUES (738, 1, 'Membership Status');
INSERT INTO `LocalizationStrings` VALUES (739, 1, 'Silver');
INSERT INTO `LocalizationStrings` VALUES (740, 1, 'Gold');
INSERT INTO `LocalizationStrings` VALUES (741, 1, 'Platinum');
INSERT INTO `LocalizationStrings` VALUES (742, 1, 'Standard');
INSERT INTO `LocalizationStrings` VALUES (743, 1, 'silver');
INSERT INTO `LocalizationStrings` VALUES (744, 1, 'standard');
INSERT INTO `LocalizationStrings` VALUES (745, 1, 'Unknown action');
INSERT INTO `LocalizationStrings` VALUES (746, 1, 'unknown');
INSERT INTO `LocalizationStrings` VALUES (747, 1, 'Unregister');
INSERT INTO `LocalizationStrings` VALUES (748, 1, 'My Uploads');
INSERT INTO `LocalizationStrings` VALUES (749, 1, 'Upload Photos');
INSERT INTO `LocalizationStrings` VALUES (750, 1, 'upload sound');
INSERT INTO `LocalizationStrings` VALUES (751, 1, 'Upload Video');
INSERT INTO `LocalizationStrings` VALUES (752, 1, 'Update feedback');
INSERT INTO `LocalizationStrings` VALUES (753, 1, 'Use latin set');
INSERT INTO `LocalizationStrings` VALUES (756, 1, 'User was added to block list');
INSERT INTO `LocalizationStrings` VALUES (757, 1, 'User was added to hot list');
INSERT INTO `LocalizationStrings` VALUES (758, 1, 'User was added to friend list');
INSERT INTO `LocalizationStrings` VALUES (759, 1, 'User was invited to friend list');
INSERT INTO `LocalizationStrings` VALUES (760, 1, 'This user already in your friend list!');
INSERT INTO `LocalizationStrings` VALUES (761, 1, 'User was added to the instant messenger');
INSERT INTO `LocalizationStrings` VALUES (762, 1, 'Video Gallery');
INSERT INTO `LocalizationStrings` VALUES (763, 1, 'Video file successfully deleted');
INSERT INTO `LocalizationStrings` VALUES (764, 1, 'Video file remove failed');
INSERT INTO `LocalizationStrings` VALUES (765, 1, 'view video');
INSERT INTO `LocalizationStrings` VALUES (766, 1, 'view profile');
INSERT INTO `LocalizationStrings` VALUES (767, 1, 'view profile');
INSERT INTO `LocalizationStrings` VALUES (768, 1, 'view as profile details');
INSERT INTO `LocalizationStrings` VALUES (769, 1, 'view as photo gallery');
INSERT INTO `LocalizationStrings` VALUES (770, 1, 'visitor menu');
INSERT INTO `LocalizationStrings` VALUES (771, 1, 'Vote profile');
INSERT INTO `LocalizationStrings` VALUES (772, 1, 'Bad');
INSERT INTO `LocalizationStrings` VALUES (773, 1, 'Good');
INSERT INTO `LocalizationStrings` VALUES (774, 1, 'Vote Average Mark');
INSERT INTO `LocalizationStrings` VALUES (775, 1, 'Vote accepted');
INSERT INTO `LocalizationStrings` VALUES (776, 1, 'votes');
INSERT INTO `LocalizationStrings` VALUES (777, 1, 'Write Message');
INSERT INTO `LocalizationStrings` VALUES (778, 1, 'Want children');
INSERT INTO `LocalizationStrings` VALUES (780, 1, 'Was contacted');
INSERT INTO `LocalizationStrings` VALUES (781, 1, 'Welcome');
INSERT INTO `LocalizationStrings` VALUES (782, 1, 'with');
INSERT INTO `LocalizationStrings` VALUES (783, 1, 'with photos only');
INSERT INTO `LocalizationStrings` VALUES (784, 1, 'What do you seek someone for?');
INSERT INTO `LocalizationStrings` VALUES (785, 1, 'within');
INSERT INTO `LocalizationStrings` VALUES (786, 1, 'Whom do you look for?');
INSERT INTO `LocalizationStrings` VALUES (787, 1, 'who is from');
INSERT INTO `LocalizationStrings` VALUES (788, 1, 'Women');
INSERT INTO `LocalizationStrings` VALUES (789, 1, 'women');
INSERT INTO `LocalizationStrings` VALUES (790, 1, 'woman');
INSERT INTO `LocalizationStrings` VALUES (791, 1, 'woman');
INSERT INTO `LocalizationStrings` VALUES (792, 1, 'Write access:');
INSERT INTO `LocalizationStrings` VALUES (793, 1, 'write message');
INSERT INTO `LocalizationStrings` VALUES (794, 1, '{0}% match');
INSERT INTO `LocalizationStrings` VALUES (795, 1, '{0} y/o');
INSERT INTO `LocalizationStrings` VALUES (796, 1, 'your rate');
INSERT INTO `LocalizationStrings` VALUES (797, 1, 'Yes');
INSERT INTO `LocalizationStrings` VALUES (798, 1, 'Yahoo');
INSERT INTO `LocalizationStrings` VALUES (799, 1, 'Yahoo');
INSERT INTO `LocalizationStrings` VALUES (800, 1, 'You can get my');
INSERT INTO `LocalizationStrings` VALUES (801, 1, 'You are');
INSERT INTO `LocalizationStrings` VALUES (802, 1, 'You already voted');
INSERT INTO `LocalizationStrings` VALUES (803, 1, 'Your email');
INSERT INTO `LocalizationStrings` VALUES (804, 1, 'You have to wait for {0} minute(s) before you can write another message!');
INSERT INTO `LocalizationStrings` VALUES (805, 1, 'Your name');
INSERT INTO `LocalizationStrings` VALUES (806, 1, 'Your Shopping Cart');
INSERT INTO `LocalizationStrings` VALUES (807, 1, 'your private messages here');
INSERT INTO `LocalizationStrings` VALUES (808, 1, 'Zip/Postal Code');
INSERT INTO `LocalizationStrings` VALUES (809, 1, 'Post date');
INSERT INTO `LocalizationStrings` VALUES (810, 1, 'Chat');
INSERT INTO `LocalizationStrings` VALUES (811, 1, 'Chat');
INSERT INTO `LocalizationStrings` VALUES (812, 1, 'About Us');
INSERT INTO `LocalizationStrings` VALUES (813, 1, 'About us');
INSERT INTO `LocalizationStrings` VALUES (814, 1, 'Email Confirmation');
INSERT INTO `LocalizationStrings` VALUES (815, 1, 'Your e-mail confirmation');
INSERT INTO `LocalizationStrings` VALUES (816, 1, 'Affiliates');
INSERT INTO `LocalizationStrings` VALUES (817, 1, 'Affiliates');
INSERT INTO `LocalizationStrings` VALUES (819, 1, 'add to cart');
INSERT INTO `LocalizationStrings` VALUES (820, 1, '{0} Articles');
INSERT INTO `LocalizationStrings` VALUES (821, 1, 'Articles');
INSERT INTO `LocalizationStrings` VALUES (822, 1, 'Your Shopping Cart');
INSERT INTO `LocalizationStrings` VALUES (823, 1, 'You can check out selected profiles and receive contact information here');
INSERT INTO `LocalizationStrings` VALUES (824, 1, 'Communication Center');
INSERT INTO `LocalizationStrings` VALUES (825, 1, 'Storage for greetings, contacts and messages');
INSERT INTO `LocalizationStrings` VALUES (826, 1, 'Change Account Status');
INSERT INTO `LocalizationStrings` VALUES (827, 1, 'Suspend/Activate your {0} account');
INSERT INTO `LocalizationStrings` VALUES (828, 1, 'add to IM');
INSERT INTO `LocalizationStrings` VALUES (829, 1, 'Coming soon');
INSERT INTO `LocalizationStrings` VALUES (830, 1, 'Compose a new message');
INSERT INTO `LocalizationStrings` VALUES (831, 1, 'Compose and send a message');
INSERT INTO `LocalizationStrings` VALUES (832, 1, 'Feedback');
INSERT INTO `LocalizationStrings` VALUES (833, 1, 'Feedback');
INSERT INTO `LocalizationStrings` VALUES (834, 1, 'Receive Confirmation E-mail');
INSERT INTO `LocalizationStrings` VALUES (835, 1, 'View Feedback');
INSERT INTO `LocalizationStrings` VALUES (836, 1, 'View Feedback');
INSERT INTO `LocalizationStrings` VALUES (837, 1, 'News View');
INSERT INTO `LocalizationStrings` VALUES (838, 1, 'News View');
INSERT INTO `LocalizationStrings` VALUES (839, 1, 'Contact us');
INSERT INTO `LocalizationStrings` VALUES (840, 1, 'Feedback section - questions, comments, regards');
INSERT INTO `LocalizationStrings` VALUES (841, 1, 'E-mail Confirmation');
INSERT INTO `LocalizationStrings` VALUES (842, 1, 'Explanation');
INSERT INTO `LocalizationStrings` VALUES (843, 1, 'FAQ');
INSERT INTO `LocalizationStrings` VALUES (844, 1, 'FAQ');
INSERT INTO `LocalizationStrings` VALUES (845, 1, 'Featured profiles');
INSERT INTO `LocalizationStrings` VALUES (846, 1, 'Featured profiles');
INSERT INTO `LocalizationStrings` VALUES (847, 1, 'Forgot?');
INSERT INTO `LocalizationStrings` VALUES (848, 1, 'Get contact information for FREE!');
INSERT INTO `LocalizationStrings` VALUES (849, 1, 'Rate photo');
INSERT INTO `LocalizationStrings` VALUES (850, 1, 'Rate photo');
INSERT INTO `LocalizationStrings` VALUES (851, 1, 'My Inbox');
INSERT INTO `LocalizationStrings` VALUES (852, 1, 'Inbox');
INSERT INTO `LocalizationStrings` VALUES (853, 1, 'Inbox');
INSERT INTO `LocalizationStrings` VALUES (854, 1, 'Title of the main page (homepage) of your site');
INSERT INTO `LocalizationStrings` VALUES (856, 1, 'get e-mail');
INSERT INTO `LocalizationStrings` VALUES (858, 1, 'get sound');
INSERT INTO `LocalizationStrings` VALUES (859, 1, 'Join');
INSERT INTO `LocalizationStrings` VALUES (860, 1, 'Affiliate sign up');
INSERT INTO `LocalizationStrings` VALUES (861, 1, '{0} Links');
INSERT INTO `LocalizationStrings` VALUES (862, 1, '{0} Links');
INSERT INTO `LocalizationStrings` VALUES (863, 1, 'Member Login');
INSERT INTO `LocalizationStrings` VALUES (864, 1, 'Control Panel');
INSERT INTO `LocalizationStrings` VALUES (865, 1, '{0} Member Panel');
INSERT INTO `LocalizationStrings` VALUES (866, 1, 'Membership');
INSERT INTO `LocalizationStrings` VALUES (867, 1, 'View status/upgrade your membership');
INSERT INTO `LocalizationStrings` VALUES (868, 1, 'News');
INSERT INTO `LocalizationStrings` VALUES (869, 1, 'Outbox');
INSERT INTO `LocalizationStrings` VALUES (870, 1, 'Outbox');
INSERT INTO `LocalizationStrings` VALUES (871, 1, 'Outbox');
INSERT INTO `LocalizationStrings` VALUES (872, 1, 'Our services');
INSERT INTO `LocalizationStrings` VALUES (873, 1, 'Privacy Policy');
INSERT INTO `LocalizationStrings` VALUES (874, 1, 'Privacy policy');
INSERT INTO `LocalizationStrings` VALUES (875, 1, 'Manage your photos');
INSERT INTO `LocalizationStrings` VALUES (876, 1, 'Upload/change your photos here');
INSERT INTO `LocalizationStrings` VALUES (877, 1, 'Photo gallery');
INSERT INTO `LocalizationStrings` VALUES (878, 1, 'Photo gallery');
INSERT INTO `LocalizationStrings` VALUES (882, 1, 'Profile view');
INSERT INTO `LocalizationStrings` VALUES (883, 1, 'Order failure');
INSERT INTO `LocalizationStrings` VALUES (884, 1, 'Possible security attack');
INSERT INTO `LocalizationStrings` VALUES (885, 1, 'Purchase success');
INSERT INTO `LocalizationStrings` VALUES (886, 1, 'Extended Profile Search');
INSERT INTO `LocalizationStrings` VALUES (887, 1, 'Search');
INSERT INTO `LocalizationStrings` VALUES (888, 1, 'Search Result');
INSERT INTO `LocalizationStrings` VALUES (889, 1, 'You are allowed to see only the first {0} profiles that match your search criteria.');
INSERT INTO `LocalizationStrings` VALUES (890, 1, 'Manage your sound');
INSERT INTO `LocalizationStrings` VALUES (891, 1, 'Feedback');
INSERT INTO `LocalizationStrings` VALUES (892, 1, 'Feedback');
INSERT INTO `LocalizationStrings` VALUES (893, 1, 'Terms of use');
INSERT INTO `LocalizationStrings` VALUES (894, 1, 'Terms');
INSERT INTO `LocalizationStrings` VALUES (896, 1, 'Manage your video');
INSERT INTO `LocalizationStrings` VALUES (897, 1, 'Send a greeting');
INSERT INTO `LocalizationStrings` VALUES (899, 1, '<div class="about_us_cont">\r\n<div class="about_us_snippet">\r\n\r\n<a href="http://www.boonex.com/products/dolphin/">Dolphin Smart Community Builder</a> was developed by <a href="http://www.boonex.com/">BoonEx Community Software Experts</a>.<br><br> \r\n<a href="http://www.boonex.com/products/dolphin/">Dolphin</a> Smart Community Builder is based on aeDating, the most popular dating software on the internet. Since the first Dolphin version was released on May 2006, it has been modernized, supplemented, improved considerably and become an even more popular Community software than the aeDating script was.<br> \r\nIn conformity with the <a href="http://www.boonex.com/mission/">"Unite People"</a> mission, BoonEx strongly believes that Community software should be offered free of charge, since the Community unites people of different cultures, nationalities and races.<br><br> \r\n\r\nBoonEx carries out its mission through Dolphin by improving it constantly and releasing at least 4 versions every six months. Thus Dolphin offers you advanced <a href="http://www.boonex.com/products/dolphin/features/">features</a> which Internet users love very much: groups, photo gallery, blog, and much more. Dolphin is also integrated with <a href="http://www.boonex.com/products/orca/">Orca Interactive Forum Script</a> and all the <a href="http://www.boonex.com/products/ray/">Ray Widgets</a>, such as: <a href="http://www.boonex.com/products/ray/widgets/im/">Instant Messenger</a>, <a href="http://www.boonex.com/products/ray/widgets/chat/">Chat</a>, <a href="http://www.boonex.com/products/ray/widgets/presence/">Web Presence</a>, <a href="http://www.boonex.com/products/ray/widgets/whiteboard/">Whiteboard</a>, <a href="http://www.boonex.com/products/ray/widgets/mp3/">Music Player</a>, <a href="http://www.boonex.com/products/ray/widgets/recorder/">Video Recorder</a>, Video Player.<br><br>\r\n \r\nDolphin, as well as other BoonEx products, is supported by the <a href="http://www.expertzzz.com/">Expertzzz Community Software Support</a> system. This system has been operating since Dolphin 5.3 and other BoonEx products were released under General Public License. The license entitles other developers to make changes in the code, make their modules and even sell them. There are a lot of developers who have already joined Expertzzz.com and are making money selling their <a href="http://www.expertzzz.com/Downloadz/home/8">modifications</a>, <a href="http://www.expertzzz.com/Downloadz/home/11">templates</a>, <a href="http://www.expertzzz.com/Downloadz/home/10">zip/post codes</a>, <a href="http://www.expertzzz.com/Downloadz/home/12">language files</a> and other <a href="http://www.expertzzz.com/Downloadz/home/13">plug ins</a> and offering their support services.<br><br>\r\nIn aspiring to achieve perfection BoonEx has launched a special Web Blog at <a href="http://www.boonex.org/">www.boonex.org</a>  where General director Andrey Sivtsov discusses themes concerning the future versions of all BoonEx products with everyone interested.\r\nAll interested persons are welcome to bring their contribution to Dolphin development.\r\n\r\n</div>\r\n</div>');
INSERT INTO `LocalizationStrings` VALUES (901, 1, 'Email was successfully confirmed.');
INSERT INTO `LocalizationStrings` VALUES (902, 1, 'Message was successfully sent.');
INSERT INTO `LocalizationStrings` VALUES (906, 1, '<div class="affiliates_cont">\r\n<div class="affiliates_snippet">\r\nWe offer commissions for webmasters who refer visitors to our site. Go to <a href="{0}join_aff.php">sign up page</a> to become an affilliate.\r\n</div>\r\n</div>');
INSERT INTO `LocalizationStrings` VALUES (907, 1, 'Your account is already activated. There is no need to do it again.');
INSERT INTO `LocalizationStrings` VALUES (909, 1, 'You will need to follow the link supplied in the e-mail to get your account submitted for approval. <br />Send me <a target=_blank href="activation_email.php">confirmation e-mail</a>.');
INSERT INTO `LocalizationStrings` VALUES (910, 1, '(<a href="javascript:void(0);" onclick="javascript:window.open( ''explanation.php?explain=Unconfirmed'', '''', ''width={0},height={1},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no'' );">Explanation</a>)');
INSERT INTO `LocalizationStrings` VALUES (911, 1, '(<a href="javascript:void(0);" onclick="javascript:window.open( ''explanation.php?explain=Approval'', '''', ''width={0},height={1},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no'' );">Explanation</a>)');
INSERT INTO `LocalizationStrings` VALUES (912, 1, 'Your profile activation is in progress. Usually it takes up to 24 hours. Thank you for your patience.');
INSERT INTO `LocalizationStrings` VALUES (913, 1, '(<a href="javascript:void(0);" onclick="javascript:window.open( ''explanation.php?explain=Active'', '''', ''width={0},height={1},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no'' );">Explanation</a>,<a href="change_status.php">Suspend</a>)');
INSERT INTO `LocalizationStrings` VALUES (914, 1, 'You are a full-featured member of our community. You can however suspend your profile to become temporarily unavailable for others.');
INSERT INTO `LocalizationStrings` VALUES (917, 1, '(<a href="javascript:void(0);" onclick="javascript:window.open( ''explanation.php?explain=Rejected'', '''', ''width={0},height={1},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no'' );">Explanation</a>)');
INSERT INTO `LocalizationStrings` VALUES (918, 1, 'Your profile was rejected by the system administrator because it contains illegal information or is missing some information. If you have any questions, please, <a target=_blank href="contact.php">contact us</a>, and don''t forget to specify your profile ID.');
INSERT INTO `LocalizationStrings` VALUES (919, 1, '(<a href="javascript:void(0);" onclick="javascript:window.open( ''explanation.php?explain=Suspended'', '''', ''width={0},height={1},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no'' );">Explanation</a>)');
INSERT INTO `LocalizationStrings` VALUES (920, 1, 'Your profile is temporarily out of {0} system services. You can activate it <a target=_blank href="change_status.php">here</a>. If you have any questions, please <a target=_blank href="contact.php">contact administrators</a>.');
INSERT INTO `LocalizationStrings` VALUES (921, 1, '<b><a href="{1}messages_inbox.php?message={0}">New message</a></b> waiting for you!');
INSERT INTO `LocalizationStrings` VALUES (923, 1, '<b><a href="{0}contacts.php?show=greet&list=me">New greeting</a></b> waiting for you!');
INSERT INTO `LocalizationStrings` VALUES (924, 1, '<b><a href="{0}contacts.php?show=friends_inv&amp;list=me">New Friend</a></b> waiting for you!');
INSERT INTO `LocalizationStrings` VALUES (925, 1, 'Profile not available. Choose a profile to send a greeting to:<br />');
INSERT INTO `LocalizationStrings` VALUES (927, 1, 'Sorry, you cannot browse this profile.');
INSERT INTO `LocalizationStrings` VALUES (928, 1, 'Your shopping cart is empty');
INSERT INTO `LocalizationStrings` VALUES (929, 1, 'Your shopping cart has been emptied');
INSERT INTO `LocalizationStrings` VALUES (930, 1, 'Your city is required.');
INSERT INTO `LocalizationStrings` VALUES (932, 1, 'Can not send message, the status of your profile is "approval"');
INSERT INTO `LocalizationStrings` VALUES (934, 1, 'Sorry, this feature is still unavailable at {0}. It is coming pretty soon, though, so please, try later. We appreciate your patience.<br /><br />Thank you.');
INSERT INTO `LocalizationStrings` VALUES (936, 1, 'Your contact information here');
INSERT INTO `LocalizationStrings` VALUES (937, 1, 'Your cart has {0} contact(s)');
INSERT INTO `LocalizationStrings` VALUES (939, 1, 'You account was successfully deleted');
INSERT INTO `LocalizationStrings` VALUES (940, 1, 'Your profile and photos will be deleted. Are you sure you want to delete your account?');
INSERT INTO `LocalizationStrings` VALUES(941, 1, 'Password must be from 3 to 32 characters long.');
INSERT INTO `LocalizationStrings` VALUES (942, 1, 'Password must be from 5 to 8 characters long or password confirmation failed.');
INSERT INTO `LocalizationStrings` VALUES (943, 1, 'Description must be at least 20 characters long.');
INSERT INTO `LocalizationStrings` VALUES (946, 1, 'E-mail address <b>{0}</b> is already used by member <b>{1}</b>.');
INSERT INTO `LocalizationStrings` VALUES (947, 1, 'E-mail confirmation failed.');
INSERT INTO `LocalizationStrings` VALUES (948, 1, 'This could happen because of improper web links displayed by some web mail services. If you see this message, please try to <u>exactly</u> copy the link supplied with the confirmation e-mail and paste it into your browser''s address bar <b>or</b> just enter the confirmation code (which also comes with the e-mail) below:');
INSERT INTO `LocalizationStrings` VALUES (949, 1, '<b>Mail has NOT been sent.</b><br />Unfortunately we could not send the confirmation e-mail to you at this time. Please, try later. We appologize for any inconvenience. <a href="contact.php">Please report this bug to</a> the administrator</a>.');
INSERT INTO `LocalizationStrings` VALUES (950, 1, '<b>Mail has been successfully sent.</b><br />You will receive it within a minute.');
INSERT INTO `LocalizationStrings` VALUES (951, 1, 'Congratulations! Your e-mail confirmation succeeded.<br /><br />Your account will be activated within 12 hours. Our administrators will personally look through your details to make sure you have set everything correctly. This helps {0} be the most accurate community service in the world. We care about the quality of our profiles and guarantee that every user of our system is real, so if you purchase someone''s contact information, you can be sure that your money isn''t wasted.');
INSERT INTO `LocalizationStrings` VALUES (952, 1, 'E-mail address doesn''t seem to be valid or E-mail confirmation failed or E-mail is used by another member.');
INSERT INTO `LocalizationStrings` VALUES (953, 1, 'E-mail address doesn''t seem to be valid.');
INSERT INTO `LocalizationStrings` VALUES (954, 1, 'Personal e-mail address is required.');
INSERT INTO `LocalizationStrings` VALUES (956, 1, 'Enter confirmation code');
INSERT INTO `LocalizationStrings` VALUES (957, 1, 'Sorry, an error occured. Please, try later.');
INSERT INTO `LocalizationStrings` VALUES (958, 1, 'Failed to upload sound.');
INSERT INTO `LocalizationStrings` VALUES (959, 1, 'Failed to upload video.');
INSERT INTO `LocalizationStrings` VALUES (960, 1, 'Failed to delete picture.<br /><div class=small>(Error code: {0})</div>');
INSERT INTO `LocalizationStrings` VALUES (961, 1, 'Failed to make thumbnail out of primary.');
INSERT INTO `LocalizationStrings` VALUES (962, 1, 'Failed to send message to one or more recipients.');
INSERT INTO `LocalizationStrings` VALUES (964, 1, 'Failed to send message. You are in the block list of this member');
INSERT INTO `LocalizationStrings` VALUES (965, 1, 'Failed to send message. You are a standard member. <a href="membership.php">Click here</a> to upgrade.');
INSERT INTO `LocalizationStrings` VALUES (966, 1, 'Failed to send message. You do not have enough credits to send a message. <a href="membership.php">Click here</a> to buy more credits.');
INSERT INTO `LocalizationStrings` VALUES (967, 1, 'Failed to send message. Recipient is not an active member.');
INSERT INTO `LocalizationStrings` VALUES (968, 1, 'Failed to update profile.');
INSERT INTO `LocalizationStrings` VALUES (969, 1, 'Failed to upload file <b>{0}</b>! Make sure it''s a picture of <b>jpg</b>, <b>gif</b>, or <b>png</b> format.<br /><div class=small>(Error code: {1})</div><br /><br />');
INSERT INTO `LocalizationStrings` VALUES (970, 1, '<div class="faq_cont">\r\n<div class="faq_header">\r\nWhere can I download the latest Dolphin version?</div>\r\n<div class="faq_snippet">You can learn more about the latest Dolphin version, its improvements and newly implemented features on the <a href="http://www.boonex.com/products/">BoonEx products</a> page.</div>\r\n</div>\r\n\r\n<div class="faq_cont">\r\n<div class="faq_header">\r\nHow can I test the latest version?</div>\r\n<div class="faq_snippet">The latest versions of all BoonEx products are available for testing at <a href="http://www.demozzz.com/">Demozzz.com</a></div>\r\n</div>\r\n\r\n<div class="faq_cont">\r\n<div class="faq_header">\r\nDo you release beta versions?\r\n</div>\r\n<div class="faq_snippet">\r\nSure! We release several Beta versions and Release Candidates before the final release. All Beta versions of all BoonEx products are available for download at BoonEx Blog.\r\n</div>\r\n</div>\r\n\r\n<div class="faq_cont">\r\n<div class="faq_header">\r\nWhere can I get support services?</div>\r\n<div class="faq_snippet">Dolphin, and other BoonEx products, is supported via <a href="http://www.expertzzz.com/">Expertzzz Community Software Support</a> system.</div>\r\n</div>\r\n\r\n<div class="faq_cont">\r\n<div class="faq_header">\r\nWhere can I find/order modifications, templates and other plug ins for my Community website powered by Dolphin?</div>\r\n<div class="faq_snippet">All miscellaneous products for Dolphin and other BoonEx products are offered at <a href="http://www.expertzzz.com/Downloadz/home/">Expertzzz.com</a></div>\r\n</div>\r\n\r\n<div class="faq_cont">\r\n<div class="faq_header">\r\nWhat if I have some development skills and can develop modifications or other things for Dolphin?</div>\r\n<div class="faq_snippet">You are welcome to join <a href="http://www.expertzzz.com/Join/join">Expertzzz</a>, where you can register as an expert and offer your products and support services.</div>\r\n</div>\r\n\r\n<div class="faq_cont">\r\n<div class="faq_header">\r\nI have some good ideas for future Dolphin versions</div>\r\n<div class="faq_snippet">You are welcome to use the BoonEx web <a href="http://www.boonex.org/">Blog</a> or <a href="http://www.boonex.net/">TRAC</a> system to contribute to the Dolphin development process.</div>\r\n</div>');
INSERT INTO `LocalizationStrings` VALUES (971, 1, 'Fields with (*) are optional.');
INSERT INTO `LocalizationStrings` VALUES (972, 1, 'Forgot your ID and/or password? No problem! Please, supply your e-mail address below and you will be sent your {0} account ID and password.');
INSERT INTO `LocalizationStrings` VALUES (973, 1, 'You already requested !!! this member''s contact information for free. You can see it in <a href="profile.php?ID={0}">their profile</a>, or in <a href="contacts.php">your communicator</a>.');
INSERT INTO `LocalizationStrings` VALUES (974, 1, 'You must choose a member to retrieve e-mail for FREE by specifying their ID:');
INSERT INTO `LocalizationStrings` VALUES (975, 1, 'Sorry, contact information could not be sent to you. You are in the block list of this member');
INSERT INTO `LocalizationStrings` VALUES (976, 1, 'Sorry, contact information could not be sent to you at this time. Make sure that:<br /><br /><ul><li>You are logged in;</li><li>Your profile is in active mode.</li></ul><br /><br />Thank you.');
INSERT INTO `LocalizationStrings` VALUES (977, 1, 'Sorry, this member''s contact information cannot be received for free. You must purchase it.');
INSERT INTO `LocalizationStrings` VALUES (978, 1, 'You were not greeted by {0} member.');
INSERT INTO `LocalizationStrings` VALUES (980, 1, 'You have just been sent an e-mail with {0}''s contact information.');
INSERT INTO `LocalizationStrings` VALUES (983, 1, 'The e-mail you entered doesn''t seem to be valid. Please, try again.');
INSERT INTO `LocalizationStrings` VALUES (987, 1, 'Invalid member ID specified. You should enter a numeric ID like 12345.');
INSERT INTO `LocalizationStrings` VALUES (988, 1, 'Sorry, invalid password! Please, try again.');
INSERT INTO `LocalizationStrings` VALUES (989, 1, '<b>Congratulations!</b> You are on your way to joining the fascinating community of singles looking for a serious relationship.<br /><br />Membership is free and is not time-limited, however, you should keep your profile up-to-date and expose communication activity. Every profile is thoroughly checked before joining the system. Now, simply fill out our forms and complete your registration.<br /><br /><br /><center><b>Step 1 - Your personal information. Page - {0}</b></center>');
INSERT INTO `LocalizationStrings` VALUES (990, 1, 'Go here to become an affiliate of this site for free.<br /><br />');
INSERT INTO `LocalizationStrings` VALUES (992, 1, '<b>Great job!</b> Each member is required to supply their personal e-mail only. In order to avoid spamming someone''s mailboxes we need to check your e-mail. We will now send you a confirmation e-mail, which is necessary for your email confirmation. This e-mail includes a link which you may click, <b>or</b> you may enter the secret confirmation code below. This code is also included in the e-mail you receive. This will prove your ownership of the e-mail address you specified.<br /><br /><center><b>Step 2 - Confirmation</b></center>');
INSERT INTO `LocalizationStrings` VALUES (993, 1, 'Congratulations! You are an affiliate of {1} now. You can login <a href="{0}">here</a>');
INSERT INTO `LocalizationStrings` VALUES (994, 1, 'Use <font color=red><b>{0}</b></font> ID number for login, please do not forget your ID number');
INSERT INTO `LocalizationStrings` VALUES (995, 1, 'Give my contact information only to those to whom I sent greetings.');
INSERT INTO `LocalizationStrings` VALUES (996, 1, 'Headline must be at least 2 characters long.');
INSERT INTO `LocalizationStrings` VALUES (997, 1, 'You have been logged out.');
INSERT INTO `LocalizationStrings` VALUES (998, 1, 'Login error. Try again:');
INSERT INTO `LocalizationStrings` VALUES (999, 1, 'Your login information seems to be obsolete, please re-login.');
INSERT INTO `LocalizationStrings` VALUES (1000, 1, 'Sorry, you need to login before you can use this page.<br />Please enter your email (or ID) and password below:');
INSERT INTO `LocalizationStrings` VALUES (1002, 1, 'Sorry, you need to login before you can use this page.');
INSERT INTO `LocalizationStrings` VALUES (1003, 1, 'If you are not registered at {2} you can do it right now for FREE and get all the advantages our system offers for both free and fee.<br />');
INSERT INTO `LocalizationStrings` VALUES (1005, 1, 'Maximum {0} characters when writing to communicator, unlimited to email');
INSERT INTO `LocalizationStrings` VALUES (1006, 1, 'Member has been added to your shopping cart');
INSERT INTO `LocalizationStrings` VALUES (1007, 1, 'You already requested this member''s contact information. You can see it in their <a href="profile.php?ID={0}">profile</a>, or in your <a href="contacts.php">communicator</a>.');
INSERT INTO `LocalizationStrings` VALUES (1008, 1, 'This member is already in your shopping cart');
INSERT INTO `LocalizationStrings` VALUES (1009, 1, 'Sorry, you have not been recognized as a {0} member. Please, make sure that you entered the e-mail you used in creating your account.');
INSERT INTO `LocalizationStrings` VALUES (1011, 1, 'You have been recognized as a {0} member, but it was impossible to send you an e-mail with your account details right now. Please, try later.');
INSERT INTO `LocalizationStrings` VALUES (1012, 1, 'You have been recognized as a {1} member and your account details have just been sent to you. Once you receive the letter from us, go <a href="{0}member.php">here</a> and log in.');
INSERT INTO `LocalizationStrings` VALUES (1013, 1, 'There are <b>{0}</b> member(s) online now');
INSERT INTO `LocalizationStrings` VALUES (1014, 1, 'Members you have contacted');
INSERT INTO `LocalizationStrings` VALUES (1015, 1, 'Members you have been contacted by');
INSERT INTO `LocalizationStrings` VALUES (1016, 1, 'Members you have contacted for FREE');
INSERT INTO `LocalizationStrings` VALUES (1017, 1, 'Members who contacted you for FREE');
INSERT INTO `LocalizationStrings` VALUES (1018, 1, 'Members you have greeted');
INSERT INTO `LocalizationStrings` VALUES (1019, 1, 'Members you were greeted by');
INSERT INTO `LocalizationStrings` VALUES (1020, 1, 'Members you have viewed');
INSERT INTO `LocalizationStrings` VALUES (1021, 1, 'Members you were viewed by');
INSERT INTO `LocalizationStrings` VALUES (1022, 1, 'Members you have hotlisted');
INSERT INTO `LocalizationStrings` VALUES (1023, 1, 'Members you were hotlisted by');
INSERT INTO `LocalizationStrings` VALUES (1024, 1, 'Members you have invited');
INSERT INTO `LocalizationStrings` VALUES (1025, 1, 'Members you were invited by');
INSERT INTO `LocalizationStrings` VALUES (1026, 1, 'Members you have blocked');
INSERT INTO `LocalizationStrings` VALUES (1027, 1, 'Members you were blocked by');
INSERT INTO `LocalizationStrings` VALUES (1028, 1, 'Requests to view your private photos');
INSERT INTO `LocalizationStrings` VALUES (1029, 1, 'Private photos requests');
INSERT INTO `LocalizationStrings` VALUES (1036, 1, 'Credits');
INSERT INTO `LocalizationStrings` VALUES (1037, 1, 'The Credits System is a convenient money equivalent. You can buy membership, contact information, etc for credits like using real money. Credits can be purchased by standard payment means (check payment, credit card, etc)');
INSERT INTO `LocalizationStrings` VALUES (1038, 1, 'you have: <b>{0}</b> credits');
INSERT INTO `LocalizationStrings` VALUES (1039, 1, 'you have <b>no</b> credits');
INSERT INTO `LocalizationStrings` VALUES (1040, 1, 'extend membership period');
INSERT INTO `LocalizationStrings` VALUES (1041, 1, 'expires: in {0} day(s)');
INSERT INTO `LocalizationStrings` VALUES (1042, 1, 'expires: never');
INSERT INTO `LocalizationStrings` VALUES (1043, 1, 'expires: today at {0}. (Server time: {1})');
INSERT INTO `LocalizationStrings` VALUES (1044, 1, 'View Allowed Actions');
INSERT INTO `LocalizationStrings` VALUES (1046, 1, '<font color=red> Member <br /></font>');
INSERT INTO `LocalizationStrings` VALUES (1047, 1, 'Sorry, but you''ve reached your limit for today. Please <a href="membership.php">click here</a> to check for more advanced membership levels');
INSERT INTO `LocalizationStrings` VALUES (1055, 1, 'You are a standard member.');
INSERT INTO `LocalizationStrings` VALUES (1056, 1, '<a href="membership.php">Click here</a> to upgrade.');
INSERT INTO `LocalizationStrings` VALUES (1057, 1, 'You are a standard member. Choose one of the following options:');
INSERT INTO `LocalizationStrings` VALUES (1058, 1, 'Buy more days');
INSERT INTO `LocalizationStrings` VALUES (1059, 1, 'Message has been successfully sent.');
INSERT INTO `LocalizationStrings` VALUES (1060, 1, 'Modifications have been successfully applied.');
INSERT INTO `LocalizationStrings` VALUES (1061, 1, 'Must be valid. Otherwise, you won''t be able to finish the registration.');
INSERT INTO `LocalizationStrings` VALUES (1062, 1, 'You must have cookies enabled in your browser');
INSERT INTO `LocalizationStrings` VALUES (1063, 1, 'New greeting arrived!');
INSERT INTO `LocalizationStrings` VALUES (1064, 1, 'New message arrived!');
INSERT INTO `LocalizationStrings` VALUES (1065, 1, 'Nickname must be unique and from {0} to {1} characters long.');
INSERT INTO `LocalizationStrings` VALUES (1066, 1, 'Nickname must be from {0} to {1} characters long.');
INSERT INTO `LocalizationStrings` VALUES (1067, 1, 'Attention: <NickName> is available for FREE! You can get their contact information right now for free! All you need to do is just log in to your member account and follow the link "Free Email" in <NickName>''s profile.');
INSERT INTO `LocalizationStrings` VALUES (1068, 1, 'You can buy <NickName>''s contact information or send them a greeting in return by merely following the link: <VKissLink>');
INSERT INTO `LocalizationStrings` VALUES (1069, 1, 'No articles available');
INSERT INTO `LocalizationStrings` VALUES (1070, 1, 'No links available');
INSERT INTO `LocalizationStrings` VALUES (1071, 1, 'No member specified');
INSERT INTO `LocalizationStrings` VALUES (1072, 1, 'No member to delete');
INSERT INTO `LocalizationStrings` VALUES (1073, 1, 'There is no need to confirm your account e-mail because it''s already confirmed and you proved your ownership of the e-mail address.');
INSERT INTO `LocalizationStrings` VALUES (1074, 1, '<b>No results found.</b> <br /> <a href="search.php">Start again</a> and try to broaden your search.');
INSERT INTO `LocalizationStrings` VALUES (1075, 1, 'No feedback available');
INSERT INTO `LocalizationStrings` VALUES (1076, 1, 'You are NOT recognized as a {0} member');
INSERT INTO `LocalizationStrings` VALUES (1078, 1, 'Password confirmation failed.');
INSERT INTO `LocalizationStrings` VALUES (1079, 1, 'Password must be from {0} to {1} characters long.');
INSERT INTO `LocalizationStrings` VALUES (1080, 1, 'This is your photo management page. This is where you may upload, remove and change your photos. You may use <b>.jpg</b>, <b>.gif</b> and <b>.png</b> files for your pictures. There are no limits of size or proportions, because our system will automatically resample your pictures.<br /><br />Your thumbnail is used for search results.');
INSERT INTO `LocalizationStrings` VALUES (1081, 1, 'Each time you upload or change your photos, your account is set to "<b>Approval</b>" mode and will be reviewed by {0} administration team for activation.<br /><br />You will be informed of the activation success within 24 hours.');
INSERT INTO `LocalizationStrings` VALUES (1082, 1, 'Picture successfully deleted.<br /><br />');
INSERT INTO `LocalizationStrings` VALUES (1083, 1, 'Picture successfully uploaded.<br /><br />');
INSERT INTO `LocalizationStrings` VALUES (1085, 1, 'Site Polls');
INSERT INTO `LocalizationStrings` VALUES (1086, 1, 'Site Polls');
INSERT INTO `LocalizationStrings` VALUES (1087, 1, 'Site Polls');
INSERT INTO `LocalizationStrings` VALUES (1088, 1, 'Site Polls');
INSERT INTO `LocalizationStrings` VALUES (1089, 1, 'Please input password to get access to a user''s private photos');
INSERT INTO `LocalizationStrings` VALUES (1090, 1, 'You can activate your account to make it available again for search and contacts.');
INSERT INTO `LocalizationStrings` VALUES (1091, 1, 'You can suspend your account to make it temporarily unavailable for search and contact. Later you can always reactivate it.');
INSERT INTO `LocalizationStrings` VALUES (1092, 1, 'You can not activate or suspend your account because it is not in <b>Active</b> or <b>Suspended</b> status.');
INSERT INTO `LocalizationStrings` VALUES (1093, 1, 'Profile is not available.');
INSERT INTO `LocalizationStrings` VALUES (1094, 1, 'Each time you change any of the following profile fields: <b>Nickname</b>, <b>Real name</b>, <b>City</b>, <b>Occupation</b>, <b>"About me" description</b>, <b>"About you" description</b>, <b>Phone</b>, <b>HomeAddress</b>, <b>Homepage</b>, your account is set to "<b>Approval</b>" mode and will be reviewed by {0} administration team for activation.<br />You will be informed of activation success within 24 hours.');
INSERT INTO `LocalizationStrings` VALUES (1095, 1, 'Each time you change your account e-mail, your account is set to "<b>Unconfirmed</b>" mode and a confirmation letter is sent to the new e-mail. After you confirm your e-mail, your profile will be accepted to be reviewed by {0} administration team for activation.<br /><br />You will be informed of the activation success within 24 hours.');
INSERT INTO `LocalizationStrings` VALUES (1096, 1, '<div class="privacy_cont">\r\n<div class="privacy_snippet">\r\nWe are glad to welcome you to <a href="http://www.boonex.com/products/dolphin/">Dolphin Smart Community Builder</a>. Please read this privacy statement to ensure that we are committed to keeping secure the privacy of our members'' (customers) details.<br><br> \r\n<b>What information do we collect?</b><br>\r\nSince <a href="http://www.boonex.com/">BoonEx</a> is providing you the software and support to build a website, we may require from you some information that may be considered as personally identifiable.<br><br>\r\n\r\nPlease provide us with the following information about yourself:\r\n<ul>\r\n<li>nickname</li>\r\n<li>real name</li>\r\n<li>password</li>\r\n<li>e-mail address</li></ul><br>\r\nOther personal information that we may possibly need:\r\nTo be able to render the services you''ve ordered, we may need information about your website: site URL, FTP, cPanel or SSH accesses.<br><br>\r\n<b>Copyrights</b><br>\r\nAll <a href="http://www.boonex.com/">BoonEx.com</a> site contents copyrights are reserved by BoonEx Ltd. and content copying and duplication are strongly prohibited.<br><br>\r\n<b>Acceptance of agreements</b><br>\r\nBy reading this you agree to our Privacy Statement. If you do not agree to our terms and conditions you may not use this site.\r\nWe may update our Privacy Statement from time to time so please visit this page regularly.\r\n</div>\r\n</div>');
INSERT INTO `LocalizationStrings` VALUES (1097, 1, 'Rate photo');
INSERT INTO `LocalizationStrings` VALUES (1098, 1, 'Rate photo not available now');
INSERT INTO `LocalizationStrings` VALUES (1099, 1, 'Profile error. Please, try again.');
INSERT INTO `LocalizationStrings` VALUES (1100, 1, 'You are recognized as a {0} member');
INSERT INTO `LocalizationStrings` VALUES (1101, 1, 'Sorry, your registration process could not be handled at this time.<br /><a href="contact.php">Please report this bug</a> to the administrator.');
INSERT INTO `LocalizationStrings` VALUES (1102, 1, 'Registration and gold membership sign-up promotion text');
INSERT INTO `LocalizationStrings` VALUES (1103, 1, 'Your real name is required.');
INSERT INTO `LocalizationStrings` VALUES (1104, 1, 'You must specify at least one type of desired relationship.');
INSERT INTO `LocalizationStrings` VALUES (1106, 1, 'Request sent');
INSERT INTO `LocalizationStrings` VALUES (1107, 1, '<b>The transaction didn''t proceed.</b> Make sure you have entered your credit card information correctly and try again.');
INSERT INTO `LocalizationStrings` VALUES (1108, 1, 'The transaction didn''t proceed.');
INSERT INTO `LocalizationStrings` VALUES (1110, 1, '<b>Transaction verification failed.</b> You seem to have tried to cheat our security system. Your IP is logged and reported. If you are persistent in your attempts you will be banned from our system services access.');
INSERT INTO `LocalizationStrings` VALUES (1111, 1, 'Transaction verification failed.');
INSERT INTO `LocalizationStrings` VALUES (1112, 1, 'You seem to have tried to cheat our security system. Your IP is logged and reported. If you are persistent in your attempts you will be banned from our system services access.');
INSERT INTO `LocalizationStrings` VALUES (1113, 1, 'You do not have enough credits');
INSERT INTO `LocalizationStrings` VALUES (1115, 1, 'The e-mail with contact information has been just sent to you.');
INSERT INTO `LocalizationStrings` VALUES (1116, 1, 'Thank you for your participation in {0} We appreciate your purchase and you will be responded to via email at {1}');
INSERT INTO `LocalizationStrings` VALUES (1118, 1, 'You have successfully made your purchase at {0}, however the e-mail with contact information could not be sent to you right now. Don''t worry, if you are our member your purchase has been recorded and you can retrieve all information in your <a href="contacts.php">Communicator</a>.');
INSERT INTO `LocalizationStrings` VALUES(1119, 1, '\r\n                    - <a href="profile_photos.php">upload my photos...</a><br />\r\n                    - <a href="pedit.php?ID={0}">view/change details in my profile...</a><br />\r\n                    - <a href="membership.php">receive/update gold membership...</a><br />\r\n                    - <a href="mail.php?mode=inbox">read my messages...</a><br />\r\n                    - <a href="search.php">conduct a detailed search...</a><br />\r\n                    - <a href="unregister.php">unregister me (can not be undone)...</a><br />\r\n                ');
INSERT INTO `LocalizationStrings` VALUES (1120, 1, 'Search result');
INSERT INTO `LocalizationStrings` VALUES (1121, 1, 'send message');
INSERT INTO `LocalizationStrings` VALUES (1122, 1, 'Send a message to:');
INSERT INTO `LocalizationStrings` VALUES (1123, 1, 'Your services description here');
INSERT INTO `LocalizationStrings` VALUES (1125, 1, 'This is your audio management page where you may upload, remove and change your audio files. You may use <b>.wav, .mp3</b> files for your audio.');
INSERT INTO `LocalizationStrings` VALUES (1126, 1, 'Each time you upload or change your audio, your account is set to "<b>Approval</b>" mode and will be reviewed by {0} administration team for activation.<br /><br />You will be informed of the activation success within 24 hours.');
INSERT INTO `LocalizationStrings` VALUES (1127, 1, 'Feedback was added');
INSERT INTO `LocalizationStrings` VALUES (1128, 1, 'Feedback was not added');
INSERT INTO `LocalizationStrings` VALUES (1129, 1, 'Feedback was updated');
INSERT INTO `LocalizationStrings` VALUES (1130, 1, 'Feedback was not updated');
INSERT INTO `LocalizationStrings` VALUES (1131, 1, 'Feedback was deleted');
INSERT INTO `LocalizationStrings` VALUES (1132, 1, 'Feedback was not deleted');
INSERT INTO `LocalizationStrings` VALUES (1133, 1, 'Feedback header is empty');
INSERT INTO `LocalizationStrings` VALUES (1134, 1, 'Subscribe now for {0}  newsletter to receive news, updates, photos of top rated members, feedback, tips and articles to your e-mail.');
INSERT INTO `LocalizationStrings` VALUES (1135, 1, 'Thank you for using our services!');
INSERT INTO `LocalizationStrings` VALUES (1136, 1, 'Invite a friend to {0}');
INSERT INTO `LocalizationStrings` VALUES (1137, 1, 'Invite a friend to view the profile at {0}');
INSERT INTO `LocalizationStrings` VALUES (1138, 1, 'Your terms of use here');
INSERT INTO `LocalizationStrings` VALUES(1139, 1, '<p align="justify"><site> is a social networking service that allows members to create unique personal profiles online in order to find and communicate with old and new friends. The service is operated by <site>. By using the <site> Website you agree to be bound by these Terms of Use (this &quot;Agreement&quot;), whether or not you register as a member (&quot;Member&quot;). If you wish to become a Member, communicate with other Members and make use of the <site> services (the &quot;Service&quot;), please read this Agreement and indicate your acceptance by following the instructions in the Registration process.</p>\r\n<p align="justify">&nbsp;</p>\r\n<p align="justify">This Agreement sets out the legally binding terms for your use of the Website and your Membership in the Service.<br />\r\n  <site> may modify this Agreement from time to time and such modification shall be effective upon posting by <site> on the Website. You agree to be bound to any changes to this Agreement when you use the Service after any such modification is posted. This Agreement includes <site>''s policy for acceptable use and content posted on the Website, your rights, obligations and restrictions regarding your use of the Website and the Service and <site>''s Privacy Policy.</p>\r\n<p align="justify">&nbsp;</p>\r\n<p align="justify">Please choose carefully the information you post on <site> and that you provide to other Members. Any photographs posted by you may not contain nudity, violence, or offensive subject matter. Information provided by other <site> Members (for instance, in their Profile) may contain inaccurate, inappropriate or offensive material, products or services and <site> assumes no responsibility nor liability for this material.</p>\r\n<p align="justify">&nbsp;</p>\r\n<p align="justify"><site> reserves the right, in its sole discretion, to reject, refuse to post or remove any posting (including email) by you, or to restrict, suspend, or terminate your access to all or any part of the Website and/or Services at any time, for any or no reason, with or without prior notice, and without lability.</p>\r\n<p align="justify">&nbsp;</p>\r\n<p align="justify">By participating in any offline <site> event, you agree to release and hold <site> harmless from any and all losses, damages, rights, claims, and actions of any kind including, without limitation, personal injuries, death, and property damage, either directly or indirectly related to or arising from your participation in any such offline <site> event.</p>\r\n<h2 align="center"><b>Terms of Use</b></h2>\r\n\r\n\r\n<p><b>1) Your Interactions.</b></p>\r\n<p>You are solely responsible for your interactions and communication with other Members. You understand that <site> does not in any way screen its Members, nor does <site>  inquire into the backgrounds of its Members or attempt to verify the statements of its Members. <site> makes no representations or warranties as to the conduct of Members or their compatibility with any current or future Members. We do however recommend that if you  choose to meet or exchange personal information with any member of <site> then you should take it upon yourself to do a background check on said person.</p>\r\n<p>In no event shall <site> be liable for any damages whatsoever, whether direct, indirect, general, special, compensatory, consequential, and/or incidental, arising out of or relating to the conduct of you or anyone else in connection with the use of the Service, including without limitation, bodily injury, emotional distress, and/or any other damages resulting from communications or meetings with other registered users of this Service or persons you meet through this Service.</p>\r\n<p>&nbsp;</p>\r\n\r\n<p><b>2) Eligibility.</b></p>\r\n<p align="justify">Membership in the Service where void is prohibited. By using the Website and the Service, you represent and warrant that all registration information you submit is truthful and accurate and that you agree to maintain the accuracy of such information. You further represent and warrant that you are 18 years of age or older and that your use of the <site> shall not violate any applicable law or regulation. Your profile may be deleted without warning, if it is found that you are misrepresenting your age. Your Membership is solely for your personal use, and you shall not authorize others to use your account, including your profile or email address. You are solely responsible for all content published or displayed through your account, including any email messages, and for your interactions with other members. </p>\r\n<p>&nbsp;</p>\r\n\r\n<p><b>3) Term/Fees.</b></p>\r\n<p align="justify">This Agreement shall remain in full force and effect while you use the Website, the Service, and/or are a Member. You may terminate your membership at any time. <site> may terminate your membership for any reason, effective upon sending notice to you at the email address you provide in your Membership application or other email address as you may subsequently provide to <site>. By using the Service and by becoming a Member, you acknowledge that <site> reserves the right to charge for the Service and has the right to terminate a Member''s Membership if Member should breach this Agreement or fail to pay for the Service, as required by this Agreement.</p>\r\n<p>&nbsp;</p>\r\n\r\n<p><b>4) Non Commercial Use by Members.</b></p>\r\n<p align="justify">The Website is for the personal use of Members only and may not be used in connection with any commercial endeavors except those that are specifically endorsed or approved by the management of <site>. Illegal and/or unauthorized use of the Website, including collecting usernames and/or email addresses of Members by electronic or other means for the purpose of sending unsolicited email or unauthorized framing of or linking to the Website will be investigated. Commercial advertisements, affiliate links, and other forms of solicitation may be removed from member profiles without notice and may result in termination of membership privileges. Appropriate legal action will be taken by <site> for any illegal or unauthorized use of the Website.</p>\r\n<p>&nbsp;</p>\r\n\r\n<p><b>5)  Proprietary Rights in Content on <site>.</b></p>\r\n<p align="justify"><site> owns and retains all proprietary rights in the Website and the Service. The Website contains copyrighted material, trademarks, and other proprietary information of <site> \r\nand its licensors. Except for that information which is in the public domain or for which you have been given written permission, you may not copy, modify, publish, transmit, distribute, perform, display, or sell any such proprietary information.</p>\r\n<p>&nbsp;</p>\r\n\r\n<p><b>6)  Content Posted on the Site.</b></p>\r\n<p align="justify">a. You understand and agree that <site> may review and delete any content, messages, <site> Messenger messages, photos or profiles (collectively, &quot;Content&quot;) that in the sole judgment of <site> violate this Agreement or which may be offensive, illegal or violate the rights, harm, or threaten the safety of any Member. </p>\r\n<p>&nbsp;</p>\r\n\r\n<p align="justify">b. You are solely responsible for the Content that you publish or display (hereinafter, &quot;post&quot;) on the Service or any material or information that you transmit to other Members.</p>\r\n<p>&nbsp;</p>\r\n<p align="justify">c. By posting any Content to the public areas of the Website, you hereby grant to <site> the non-exclusive, fully paid, worldwide license to use, publicly perform and display such Content on the Website. This license will terminate at the time you remove such Content from the Website.</p>\r\n<p><br />\r\n</p>\r\n<p align="justify">d. The following is a partial list of the kind of Content that is illegal or prohibited on the Website. <site> reserves the right to investigate and take appropriate legal action in its sole discretion against anyone who violates this provision, including without limitation, removing the offending communication from the Service and terminating the membership of such violators. Prohibited Content includes Content that:</p>\r\n<p>&nbsp;</p>\r\n<p align="justify">  i. is patently offensive and promotes racism, bigotry, hatred or physical harm of any kind against any group or individual; </p>\r\n<p align="justify"><br />\r\n  ii. harasses or advocates harassment of another person;</p>\r\n<p align="justify"><br />\r\n  iii. involves the transmission of &quot;junk mail&quot;, &quot;chain letters,&quot; or unsolicited mass mailing or &quot;spamming&quot;;</p>\r\n<p align="justify"><br />\r\n  iv. promotes information that you know is false or misleading or promotes illegal activities or conduct that is abusive, threatening, \r\n  obscene, defamatory or libelous;</p>\r\n<p align="justify"><br />\r\n  v. promotes an illegal or unauthorized copy of another person''s copyrighted work, such as providing pirated computer programs or links\r\n  to them, providing information to circumvent manufacture-installed copy-protect devices, or providing pirated music or links to \r\n  pirated music files;</p>\r\n<p align="justify"><br />\r\n  vi. contains restricted or password only access pages or hidden pages or images (those not linked to or from another accessible page);</p>\r\n<p align="justify"><br />\r\n  vii. provides material that exploits people under the age of 18 in a sexual or violent manner, or solicits personal information from \r\n  anyone under 18;</p>\r\n<p align="justify"><br />\r\n  viii. provides instructional information about illegal activities such as making or buying illegal weapons, violating someone''s privacy, \r\n  or providing or creating computer viruses; </p>\r\n<p align="justify"><br />\r\n  ix. solicits passwords or personal identifying information for commercial or unlawful purposes from other users;</p>\r\n<p align="justify"><br />\r\n  or x. involves commercial activities and/or sales without our prior written consent such as contests, sweepstakes, barter, advertising, \r\n  or pyramid schemes.</p>\r\n<p>&nbsp;</p>\r\n<p align="justify">e. You must use the Service in a manner consistent with any and all applicable laws and regulations. f. You may not engage in advertising to, or solicitation of, any Member to buy or sell any products or services through the Service. You may not transmit any chain letters or junk email to other Members. Although <site> cannot monitor the conduct of its Members off the Website, it is also a violation of these rules to use any information obtained from the Service in order to harass, abuse, or harm another person, or in order to contact, advertise to, solicit, or sell to any Member without their prior explicit consent. In order to protect our Members from such advertising or solicitation, <site> reserves the right to restrict the number of emails which a Member may send to other Members in any 24-hour period to a number which <site> deems appropriate in its sole discretion.</p>\r\n<p align="justify">&nbsp;</p>\r\n<p align="justify">g. You may not cover or obscure the banner advertisements on your personal profile page, or any <site> page via HTML/CSS or any other means.</p>\r\n<p>&nbsp;</p>\r\n<p align="justify">  h. Any automated use of the system, such as using scripts to add friends, is prohibited.</p>\r\n<p>&nbsp;</p>\r\n<p align="justify"> i. You may not attempt to impersonate another user or person who is not a member of <site>.</p>\r\n<p>&nbsp;</p>\r\n<p align="justify"> j. You may not use the account, username, or password of another Member at any time nor may you disclose your password to any third party \r\n  or permit any third party to access your account.</p>\r\n<p>&nbsp;</p>\r\n<p align="justify"> k. You may not sell or otherwise transfer your profile.</p>\r\n<p>&nbsp;</p>\r\n<p><b>7)  Copyright Policy.</b></p>\r\n<p align="justify">You may not post, distribute, or reproduce in any way any copyrighted material, trademarks, or other proprietary information without obtaining the prior written consent of the owner of such proprietary rights. It is the policy of <site> to terminate membership privileges of any member who repeatedly infringes copyright upon prompt notification to <site> by the copyright owner or the copyright owner''s legal agent. Without limiting the foregoing, if you believe that your work has been copied and posted on the Service in a way that constitutes copyright infringement, please provide our Copyright Agent with the following information: an electronic or physical signature of the person authorized to act on behalf of the owner of the copyright interest; a description of the copyrighted work that you claim has been infringed; a description of where the material that you claim is infringing is located on the Website; your address, telephone number, and email address; a written statement by you that you have a good faith belief that the disputed use is not authorized by the copyright owner, its agent, or the law; a statement by you, made under penalty of perjury, that the above information in your notice is accurate and that you are the copyright owner or authorized to act on the copyright owner''s behalf. <site>''s Copyright Agent for notice of claims of copyright infringement can be reached via email address.</p>\r\n<p><br />\r\n</p>\r\n<p><b>8)  Member Disputes.</b></p>\r\n<p align="justify">You are solely responsible for your interactions with other <site> Members. <site> reserves the right, but has no obligation,  to monitor disputes between you and other Members.</p>\r\n<p>&nbsp;</p>\r\n<p><b>9) Disclaimers.</b></p>\r\n<p align="justify"><site> is not responsible for any incorrect or inaccurate content posted on the Website or in connection with the Service provided, whether caused by users of the Website, Members or by any of the equipment or programming associated with or utilized in the Service. <site> is not responsible for the conduct, whether online or offline, of any user of the Website or Member of the Service. <site> assumes no responsibility for any error, omission, interruption, deletion, defect, delay in operation or transmission, communications line failure, theft or destruction or unauthorized access to, or alteration of, any user or Member communication. <site> is not responsible for any problems or technical malfunction of any telephone network or lines, computer online systems, servers or providers, computer equipment, software, failure of any email or players due to technical problems or traffic congestion on the Internet or at any Website or combination thereof, including any injury or damage to users and/or Members or to any person''s computer related to or resulting from participation or downloading materials in connection with the Website and/or in connection with the Service. Under no circumstances shall <site> be responsible for any loss or damage, including personal injury or death, resulting from use of the Website or the Service or from any Content posted on the Website or transmitted to Members, or any interactions between users of the Website, whether online or offline. The Website and the Service are provided &quot;AS-IS&quot; and <site> expressly disclaims any warranty of fitness for a particular purpose or non-infringement. <site> cannot guarantee and does not promise any specific results from use of the Website and/or the Service.</p>\r\n<p>&nbsp;</p>\r\n<p><b>10</b><b>) Limitation on Liability.</b></p>\r\n<p align="justify">IN NO EVENT SHALL <site> BE LIABLE TO YOU OR ANY THIRD PARTY FOR ANY INDIRECT, CONSEQUENTIAL, EXEMPLARY, INCIDENTAL, SPECIAL OR PUNITIVE DAMAGES, INCLUDING LOST PROFIT DAMAGES ARISING FROM YOUR USE OF THE WEB SITE OR THE SERVICE, EVEN IF <site> HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. NOTWITHSTANDING ANYTHING TO THE CONTRARY CONTAINED HEREIN, <site>.S LIABILITY TO YOU FOR ANY CAUSE WHATSOEVER AND REGARDLESS OF THE FORM OF THE ACTION, WILL AT ALL TIMES BE LIMITED TO AMOUNT PAID, IF ANY, BY YOU TO <site> FOR THE SERVICE DURING THE TERM OF MEMBERSHIP.</p>\r\n<p><br />\r\n</p>\r\n<p><b>11)  Disputes.</b></p>\r\n<p align="justify">If there is any dispute about or involving the Website and/or the Service, by using the Website, you agree that any dispute shall be governed by the laws of the area in which we are based without regard to conflict of law provisions and you agree to personal jurisdiction by and venue in the area in which we are based.</p>\r\n<p>&nbsp;</p>\r\n<p><b>12) Indemnity.</b></p>\r\n<p align="justify">You agree to indemnify and hold <site>, its subsidiaries, affiliates, officers, agents, and other partners and employees, harmless from any loss, liability, claim, or demand, including reasonable attorneys'' fees, made by any third party due to or arising out of your use of the Service in violation of this Agreement and/or arising from a breach of this Agreement and/or any breach of your representations and warranties set forth above. </p>\r\n<p>&nbsp;</p>\r\n<p><b>13) Other.</b></p>\r\n<p align="justify">This Agreement is accepted upon your use of the Website and is further affirmed by you becoming a Member of the Service. This Agreement constitutes the entire agreement between you and <site> regarding the use of the Website and/or the Service. The failure of <site> to exercise or enforce any right or provision of this Agreement shall not operate as a waiver of such right or provision. The section titles in this Agreement are for convenience only and have no legal or contractual effect. Please contact us with any questions regarding this Agreement. <site> is a trademark of &lt;owners business name&gt;.</p>\r\n<p><br />\r\n</p>\r\n<p><b>I HAVE READ THIS AGREEMENT AND AGREE TO ALL OF THE PROVISIONS CONTAINED ABOVE.</b></p>\r\n');
INSERT INTO `LocalizationStrings` VALUES (1143, 1, 'Sorry, the server could not process your request. An undefined error occured. Please, try later.');
INSERT INTO `LocalizationStrings` VALUES (1144, 1, 'While you are waiting for the confirmation letter why not <b><a href="{0}upload_media.php" target=_blank>upload your photos right now</a></b>?');
INSERT INTO `LocalizationStrings` VALUES (1145, 1, 'This is your video management page where you may upload, remove and change your videos. You may use <b>.{0}</b> files for your video.');
INSERT INTO `LocalizationStrings` VALUES (1146, 1, 'Each time you upload or change your video, your account is set to "<b>Approval</b>" mode and will be reviewed by {0} administration team for activation.<br /><br />You will be informed of the activation success within 24 hours.');
INSERT INTO `LocalizationStrings` VALUES (1147, 1, 'Greeting has been successfully sent.');
INSERT INTO `LocalizationStrings` VALUES (1148, 1, 'Greetings from');
INSERT INTO `LocalizationStrings` VALUES (1149, 1, 'Welcome, <b>{0}</b>!<br />Please, wait. Logging you in...');
INSERT INTO `LocalizationStrings` VALUES (1150, 1, 'Will be resized to <b>{0}</b>x<b>{1}</b> pixels.<br /><br />');
INSERT INTO `LocalizationStrings` VALUES (1151, 1, 'You purchased {0} contact(s).');
INSERT INTO `LocalizationStrings` VALUES (1152, 1, 'Your contacts were taken {0} time(s).');
INSERT INTO `LocalizationStrings` VALUES (1153, 1, 'Your profile is not active for this operation.');
INSERT INTO `LocalizationStrings` VALUES (1154, 1, 'Write your e-mail here');
INSERT INTO `LocalizationStrings` VALUES (1155, 1, 'Your search text here');
INSERT INTO `LocalizationStrings` VALUES (1733, 1, ' every {4} hours');
INSERT INTO `LocalizationStrings` VALUES (1157, 1, 'Greeting has been successfully sent.');
INSERT INTO `LocalizationStrings` VALUES (1158, 1, 'Sorry, a greeting has <b>not</b> been sent.');
INSERT INTO `LocalizationStrings` VALUES (1160, 1, 'Your profile is not in active mode.');
INSERT INTO `LocalizationStrings` VALUES (1161, 1, 'You have no permissions to send a greeting.');
INSERT INTO `LocalizationStrings` VALUES (1162, 1, 'Profile authentification failed');
INSERT INTO `LocalizationStrings` VALUES(1163, 1, 'If you are not logged in, please, <a href="member.php">login here</a>, or <a href="join_form.php">sign up</a> for a free membership.');
INSERT INTO `LocalizationStrings` VALUES (1164, 1, 'Member not available');
INSERT INTO `LocalizationStrings` VALUES (1165, 1, 'Sorry, max number of greetings per day reached');
INSERT INTO `LocalizationStrings` VALUES (1166, 1, 'Sorry, you are in the block list of this member');
INSERT INTO `LocalizationStrings` VALUES (1167, 1, 'Email send failed. Please, try later.');
INSERT INTO `LocalizationStrings` VALUES (1168, 1, 'An error occured. Please, try later.');
INSERT INTO `LocalizationStrings` VALUES (1169, 1, 'Your zip/postal code is required.');
INSERT INTO `LocalizationStrings` VALUES (1170, 1, 'Character counter: ');
INSERT INTO `LocalizationStrings` VALUES (1171, 1, 'Dolphin automatically logged you in as');
INSERT INTO `LocalizationStrings` VALUES (1172, 1, 'January');
INSERT INTO `LocalizationStrings` VALUES (1173, 1, 'February');
INSERT INTO `LocalizationStrings` VALUES (1174, 1, 'March');
INSERT INTO `LocalizationStrings` VALUES (1175, 1, 'April');
INSERT INTO `LocalizationStrings` VALUES (1176, 1, 'May');
INSERT INTO `LocalizationStrings` VALUES (1177, 1, 'June');
INSERT INTO `LocalizationStrings` VALUES (1178, 1, 'July');
INSERT INTO `LocalizationStrings` VALUES (1179, 1, 'August');
INSERT INTO `LocalizationStrings` VALUES (1180, 1, 'September');
INSERT INTO `LocalizationStrings` VALUES (1181, 1, 'October');
INSERT INTO `LocalizationStrings` VALUES (1182, 1, 'November');
INSERT INTO `LocalizationStrings` VALUES (1183, 1, 'December');
INSERT INTO `LocalizationStrings` VALUES (1184, 1, 'I prefer not to say');
INSERT INTO `LocalizationStrings` VALUES (1185, 1, 'Events');
INSERT INTO `LocalizationStrings` VALUES (1186, 1, 'Events');
INSERT INTO `LocalizationStrings` VALUES (1232, 1, 'Name');
INSERT INTO `LocalizationStrings` VALUES (1233, 1, 'Comment');
INSERT INTO `LocalizationStrings` VALUES (1237, 1, 'Apply changes');
INSERT INTO `LocalizationStrings` VALUES (1238, 1, 'Last changes');
INSERT INTO `LocalizationStrings` VALUES (1242, 1, 'Back');
INSERT INTO `LocalizationStrings` VALUES (1244, 1, 'Add new album');
INSERT INTO `LocalizationStrings` VALUES (1245, 1, 'Add new album');
INSERT INTO `LocalizationStrings` VALUES (1246, 1, 'Access level');
INSERT INTO `LocalizationStrings` VALUES (1247, 1, 'No object was found');
INSERT INTO `LocalizationStrings` VALUES (1248, 1, 'Delete album');
INSERT INTO `LocalizationStrings` VALUES (1249, 1, 'Album was successfully added');
INSERT INTO `LocalizationStrings` VALUES (1250, 1, 'Album updated');
INSERT INTO `LocalizationStrings` VALUES (1251, 1, 'Objects were successfully deleted');
INSERT INTO `LocalizationStrings` VALUES (1252, 1, 'Objects were successfully deleted from album ');
INSERT INTO `LocalizationStrings` VALUES (1253, 1, 'Object was successfully deleted');
INSERT INTO `LocalizationStrings` VALUES (1254, 1, 'Object was successfully uploaded');
INSERT INTO `LocalizationStrings` VALUES (1255, 1, 'Some objects could not be deleted');
INSERT INTO `LocalizationStrings` VALUES (1256, 1, 'Could not delete some objects from album ');
INSERT INTO `LocalizationStrings` VALUES (1257, 1, 'Cannot delete object');
INSERT INTO `LocalizationStrings` VALUES (1258, 1, 'Albums were successfully deleted');
INSERT INTO `LocalizationStrings` VALUES (1260, 1, 'Failed to add album');
INSERT INTO `LocalizationStrings` VALUES (1261, 1, 'Failed to upload unknown file {0}');
INSERT INTO `LocalizationStrings` VALUES (1262, 1, 'Video uploads disabled');
INSERT INTO `LocalizationStrings` VALUES (1263, 1, 'Audio uploads disabled');
INSERT INTO `LocalizationStrings` VALUES (1264, 1, 'Error while processing uploaded image');
INSERT INTO `LocalizationStrings` VALUES (1267, 1, 'The maximum allowed number of albums in this category has been reached');
INSERT INTO `LocalizationStrings` VALUES (1268, 1, 'The maximum allowed number of objects in this album has been reached');
INSERT INTO `LocalizationStrings` VALUES (1269, 1, 'The file size is too large');
INSERT INTO `LocalizationStrings` VALUES (1271, 1, 'View albums');
INSERT INTO `LocalizationStrings` VALUES (1272, 1, 'View objects');
INSERT INTO `LocalizationStrings` VALUES (1273, 1, 'Albums: ');
INSERT INTO `LocalizationStrings` VALUES (1274, 1, 'Failed to apply changes');
INSERT INTO `LocalizationStrings` VALUES (1275, 1, 'Some albums could not be deleted');
INSERT INTO `LocalizationStrings` VALUES (1276, 1, 'Could not delete album');
INSERT INTO `LocalizationStrings` VALUES (1277, 1, 'Object');
INSERT INTO `LocalizationStrings` VALUES (1278, 1, 'Objects: ');
INSERT INTO `LocalizationStrings` VALUES (1279, 1, 'Object moved up');
INSERT INTO `LocalizationStrings` VALUES (1280, 1, 'Object moved down');
INSERT INTO `LocalizationStrings` VALUES (1281, 1, 'Failed to move object');
INSERT INTO `LocalizationStrings` VALUES (1282, 1, 'Cannot copy file');
INSERT INTO `LocalizationStrings` VALUES (1283, 1, 'Object updated');
INSERT INTO `LocalizationStrings` VALUES (1284, 1, 'There are no objects to approve');
INSERT INTO `LocalizationStrings` VALUES (1287, 1, 'Objects were successfully approved');
INSERT INTO `LocalizationStrings` VALUES (1288, 1, 'move up');
INSERT INTO `LocalizationStrings` VALUES (1289, 1, 'move down');
INSERT INTO `LocalizationStrings` VALUES (1290, 1, 'edit object');
INSERT INTO `LocalizationStrings` VALUES (1291, 1, 'delete object');
INSERT INTO `LocalizationStrings` VALUES (1292, 1, 'Delete entry');
INSERT INTO `LocalizationStrings` VALUES (1293, 1, 'Edit entry');
INSERT INTO `LocalizationStrings` VALUES (1294, 1, 'Write comment');
INSERT INTO `LocalizationStrings` VALUES (1295, 1, 'No entries found');
INSERT INTO `LocalizationStrings` VALUES (1296, 1, 'comments');
INSERT INTO `LocalizationStrings` VALUES (1297, 1, 'comment');
INSERT INTO `LocalizationStrings` VALUES (1298, 1, 'Average');
INSERT INTO `LocalizationStrings` VALUES (1299, 1, 'Ample');
INSERT INTO `LocalizationStrings` VALUES (1300, 1, 'Athletic');
INSERT INTO `LocalizationStrings` VALUES (1301, 1, 'Cuddly');
INSERT INTO `LocalizationStrings` VALUES (1302, 1, 'Slim');
INSERT INTO `LocalizationStrings` VALUES (1303, 1, 'Very Cuddly');
INSERT INTO `LocalizationStrings` VALUES (1304, 1, 'Afghanistan');
INSERT INTO `LocalizationStrings` VALUES (1305, 1, 'Albania');
INSERT INTO `LocalizationStrings` VALUES (1306, 1, 'Algeria');
INSERT INTO `LocalizationStrings` VALUES (1307, 1, 'American Samoa');
INSERT INTO `LocalizationStrings` VALUES (1308, 1, 'Andorra');
INSERT INTO `LocalizationStrings` VALUES (1309, 1, 'Angola');
INSERT INTO `LocalizationStrings` VALUES (1310, 1, 'Anguilla');
INSERT INTO `LocalizationStrings` VALUES (1311, 1, 'Antarctica');
INSERT INTO `LocalizationStrings` VALUES (1312, 1, 'Antigua and Barbuda');
INSERT INTO `LocalizationStrings` VALUES (1313, 1, 'Argentina');
INSERT INTO `LocalizationStrings` VALUES (1314, 1, 'Armenia');
INSERT INTO `LocalizationStrings` VALUES (1315, 1, 'Aruba');
INSERT INTO `LocalizationStrings` VALUES (1316, 1, 'Australia');
INSERT INTO `LocalizationStrings` VALUES (1317, 1, 'Austria');
INSERT INTO `LocalizationStrings` VALUES (1318, 1, 'Azerbaijan');
INSERT INTO `LocalizationStrings` VALUES (1319, 1, 'Bahamas');
INSERT INTO `LocalizationStrings` VALUES (1320, 1, 'Bahrain');
INSERT INTO `LocalizationStrings` VALUES (1321, 1, 'Bangladesh');
INSERT INTO `LocalizationStrings` VALUES (1322, 1, 'Barbados');
INSERT INTO `LocalizationStrings` VALUES (1323, 1, 'Belarus');
INSERT INTO `LocalizationStrings` VALUES (1324, 1, 'Belgium');
INSERT INTO `LocalizationStrings` VALUES (1325, 1, 'Belize');
INSERT INTO `LocalizationStrings` VALUES (1326, 1, 'Benin');
INSERT INTO `LocalizationStrings` VALUES (1327, 1, 'Bermuda');
INSERT INTO `LocalizationStrings` VALUES (1328, 1, 'Bhutan');
INSERT INTO `LocalizationStrings` VALUES (1329, 1, 'Bolivia');
INSERT INTO `LocalizationStrings` VALUES (1330, 1, 'Bosnia/Herzegovina');
INSERT INTO `LocalizationStrings` VALUES (1331, 1, 'Botswana');
INSERT INTO `LocalizationStrings` VALUES (1332, 1, 'Bouvet Island');
INSERT INTO `LocalizationStrings` VALUES (1333, 1, 'Brazil');
INSERT INTO `LocalizationStrings` VALUES (1334, 1, 'British Ind. Ocean Terr.');
INSERT INTO `LocalizationStrings` VALUES (1335, 1, 'British Ind. Ocean');
INSERT INTO `LocalizationStrings` VALUES (1336, 1, 'Brunei');
INSERT INTO `LocalizationStrings` VALUES (1337, 1, 'Bulgaria');
INSERT INTO `LocalizationStrings` VALUES (1338, 1, 'Burkina Faso');
INSERT INTO `LocalizationStrings` VALUES (1339, 1, 'Burundi');
INSERT INTO `LocalizationStrings` VALUES (1340, 1, 'Cambodia');
INSERT INTO `LocalizationStrings` VALUES (1341, 1, 'Cameroon');
INSERT INTO `LocalizationStrings` VALUES (1342, 1, 'Cape Verde');
INSERT INTO `LocalizationStrings` VALUES (1343, 1, 'Cayman Islands');
INSERT INTO `LocalizationStrings` VALUES (1344, 1, 'Central African Rep.');
INSERT INTO `LocalizationStrings` VALUES (1345, 1, 'Chad');
INSERT INTO `LocalizationStrings` VALUES (1346, 1, 'Canada');
INSERT INTO `LocalizationStrings` VALUES (1347, 1, 'Chile');
INSERT INTO `LocalizationStrings` VALUES (1348, 1, 'China');
INSERT INTO `LocalizationStrings` VALUES (1349, 1, 'Christmas Island');
INSERT INTO `LocalizationStrings` VALUES (1350, 1, 'Cocoa (Keeling) Is.');
INSERT INTO `LocalizationStrings` VALUES (1351, 1, 'Colombia');
INSERT INTO `LocalizationStrings` VALUES (1352, 1, 'Comoros');
INSERT INTO `LocalizationStrings` VALUES (1353, 1, 'Congo');
INSERT INTO `LocalizationStrings` VALUES (1354, 1, 'Cook Islands');
INSERT INTO `LocalizationStrings` VALUES (1355, 1, 'Costa Rica');
INSERT INTO `LocalizationStrings` VALUES (1356, 1, 'Cote d''Ivoire');
INSERT INTO `LocalizationStrings` VALUES (1357, 1, 'Croatia');
INSERT INTO `LocalizationStrings` VALUES (1358, 1, 'Cuba');
INSERT INTO `LocalizationStrings` VALUES (1359, 1, 'Cyprus');
INSERT INTO `LocalizationStrings` VALUES (1360, 1, 'Czech Republic');
INSERT INTO `LocalizationStrings` VALUES (1361, 1, 'Denmark');
INSERT INTO `LocalizationStrings` VALUES (1362, 1, 'Djibouti');
INSERT INTO `LocalizationStrings` VALUES (1363, 1, 'Dominica');
INSERT INTO `LocalizationStrings` VALUES (1364, 1, 'Dominican Republic');
INSERT INTO `LocalizationStrings` VALUES (1365, 1, 'East Timor');
INSERT INTO `LocalizationStrings` VALUES (1366, 1, 'Ecuador');
INSERT INTO `LocalizationStrings` VALUES (1367, 1, 'Egypt');
INSERT INTO `LocalizationStrings` VALUES (1368, 1, 'El Salvador');
INSERT INTO `LocalizationStrings` VALUES (1369, 1, 'Equatorial Guinea');
INSERT INTO `LocalizationStrings` VALUES (1370, 1, 'Eritrea');
INSERT INTO `LocalizationStrings` VALUES (1371, 1, 'Estonia');
INSERT INTO `LocalizationStrings` VALUES (1372, 1, 'Ethiopia');
INSERT INTO `LocalizationStrings` VALUES (1373, 1, 'Falkland Islands');
INSERT INTO `LocalizationStrings` VALUES (1374, 1, 'Faroe Islands');
INSERT INTO `LocalizationStrings` VALUES (1375, 1, 'Fiji');
INSERT INTO `LocalizationStrings` VALUES (1376, 1, 'Finland');
INSERT INTO `LocalizationStrings` VALUES (1377, 1, 'France');
INSERT INTO `LocalizationStrings` VALUES (1378, 1, 'Gabon');
INSERT INTO `LocalizationStrings` VALUES (1379, 1, 'Gambia');
INSERT INTO `LocalizationStrings` VALUES (1380, 1, 'Georgia');
INSERT INTO `LocalizationStrings` VALUES (1381, 1, 'Germany');
INSERT INTO `LocalizationStrings` VALUES (1382, 1, 'Ghana');
INSERT INTO `LocalizationStrings` VALUES (1383, 1, 'Gibraltar');
INSERT INTO `LocalizationStrings` VALUES (1384, 1, 'Greece');
INSERT INTO `LocalizationStrings` VALUES (1385, 1, 'Greenland');
INSERT INTO `LocalizationStrings` VALUES (1386, 1, 'Grenada');
INSERT INTO `LocalizationStrings` VALUES (1387, 1, 'Guadeloupe');
INSERT INTO `LocalizationStrings` VALUES (1388, 1, 'Guam');
INSERT INTO `LocalizationStrings` VALUES (1389, 1, 'Guatemala');
INSERT INTO `LocalizationStrings` VALUES (1390, 1, 'Guinea');
INSERT INTO `LocalizationStrings` VALUES (1391, 1, 'Guinea-Bissau');
INSERT INTO `LocalizationStrings` VALUES (1392, 1, 'Guyana');
INSERT INTO `LocalizationStrings` VALUES (1393, 1, 'Haiti');
INSERT INTO `LocalizationStrings` VALUES (1394, 1, 'Honduras');
INSERT INTO `LocalizationStrings` VALUES (1395, 1, 'Hong Kong');
INSERT INTO `LocalizationStrings` VALUES (1396, 1, 'Hungary');
INSERT INTO `LocalizationStrings` VALUES (1397, 1, 'Iceland');
INSERT INTO `LocalizationStrings` VALUES (1398, 1, 'India');
INSERT INTO `LocalizationStrings` VALUES (1399, 1, 'Indonesia');
INSERT INTO `LocalizationStrings` VALUES (1400, 1, 'Iran');
INSERT INTO `LocalizationStrings` VALUES (1401, 1, 'Iraq');
INSERT INTO `LocalizationStrings` VALUES (1402, 1, 'Ireland');
INSERT INTO `LocalizationStrings` VALUES (1403, 1, 'Israel');
INSERT INTO `LocalizationStrings` VALUES (1404, 1, 'Italy');
INSERT INTO `LocalizationStrings` VALUES (1405, 1, 'Jamaica');
INSERT INTO `LocalizationStrings` VALUES (1406, 1, 'Japan');
INSERT INTO `LocalizationStrings` VALUES (1407, 1, 'Jordan');
INSERT INTO `LocalizationStrings` VALUES (1408, 1, 'Kazakhstan');
INSERT INTO `LocalizationStrings` VALUES (1409, 1, 'Kenya');
INSERT INTO `LocalizationStrings` VALUES (1410, 1, 'Kiribati');
INSERT INTO `LocalizationStrings` VALUES (1411, 1, 'Korea');
INSERT INTO `LocalizationStrings` VALUES (1412, 1, 'Kuwait');
INSERT INTO `LocalizationStrings` VALUES (1413, 1, 'Kyrgyzstan');
INSERT INTO `LocalizationStrings` VALUES (1414, 1, 'Lao');
INSERT INTO `LocalizationStrings` VALUES (1415, 1, 'Latvia');
INSERT INTO `LocalizationStrings` VALUES (1416, 1, 'Lebanon');
INSERT INTO `LocalizationStrings` VALUES (1417, 1, 'Lesotho');
INSERT INTO `LocalizationStrings` VALUES (1418, 1, 'Liberia');
INSERT INTO `LocalizationStrings` VALUES (1419, 1, 'Liechtenstein');
INSERT INTO `LocalizationStrings` VALUES (1420, 1, 'Lithuania');
INSERT INTO `LocalizationStrings` VALUES (1421, 1, 'Luxembourg');
INSERT INTO `LocalizationStrings` VALUES (1422, 1, 'Macau');
INSERT INTO `LocalizationStrings` VALUES (1423, 1, 'Macedonia');
INSERT INTO `LocalizationStrings` VALUES (1424, 1, 'Madagascar');
INSERT INTO `LocalizationStrings` VALUES (1425, 1, 'Malawi');
INSERT INTO `LocalizationStrings` VALUES (1426, 1, 'Malaysia');
INSERT INTO `LocalizationStrings` VALUES (1427, 1, 'Maldives');
INSERT INTO `LocalizationStrings` VALUES (1428, 1, 'Mali');
INSERT INTO `LocalizationStrings` VALUES (1429, 1, 'Malta');
INSERT INTO `LocalizationStrings` VALUES (1430, 1, 'Marshall Islands');
INSERT INTO `LocalizationStrings` VALUES (1431, 1, 'Martinique');
INSERT INTO `LocalizationStrings` VALUES (1432, 1, 'Mauritania');
INSERT INTO `LocalizationStrings` VALUES (1433, 1, 'Mauritius');
INSERT INTO `LocalizationStrings` VALUES (1434, 1, 'Mayotte');
INSERT INTO `LocalizationStrings` VALUES (1435, 1, 'Mexico');
INSERT INTO `LocalizationStrings` VALUES (1436, 1, 'Micronesia');
INSERT INTO `LocalizationStrings` VALUES (1437, 1, 'Moldova');
INSERT INTO `LocalizationStrings` VALUES (1438, 1, 'Monaco');
INSERT INTO `LocalizationStrings` VALUES (1439, 1, 'Mongolia');
INSERT INTO `LocalizationStrings` VALUES (1440, 1, 'Montserrat');
INSERT INTO `LocalizationStrings` VALUES (1441, 1, 'Morocco');
INSERT INTO `LocalizationStrings` VALUES (1442, 1, 'Mozambique');
INSERT INTO `LocalizationStrings` VALUES (1443, 1, 'Myanmar');
INSERT INTO `LocalizationStrings` VALUES (1444, 1, 'Namibia');
INSERT INTO `LocalizationStrings` VALUES (1445, 1, 'Nauru');
INSERT INTO `LocalizationStrings` VALUES (1446, 1, 'Nepal');
INSERT INTO `LocalizationStrings` VALUES (1447, 1, 'Netherlands');
INSERT INTO `LocalizationStrings` VALUES (1448, 1, 'New Caledonia');
INSERT INTO `LocalizationStrings` VALUES (1449, 1, 'New Zealand');
INSERT INTO `LocalizationStrings` VALUES (1450, 1, 'Nicaragua');
INSERT INTO `LocalizationStrings` VALUES (1451, 1, 'Niger');
INSERT INTO `LocalizationStrings` VALUES (1452, 1, 'Nigeria');
INSERT INTO `LocalizationStrings` VALUES (1453, 1, 'Niue');
INSERT INTO `LocalizationStrings` VALUES (1454, 1, 'Norfolk Island');
INSERT INTO `LocalizationStrings` VALUES (1455, 1, 'Norway');
INSERT INTO `LocalizationStrings` VALUES (1456, 1, 'no data given');
INSERT INTO `LocalizationStrings` VALUES (1457, 1, 'Oman');
INSERT INTO `LocalizationStrings` VALUES (1458, 1, 'Pakistan');
INSERT INTO `LocalizationStrings` VALUES (1459, 1, 'Palau');
INSERT INTO `LocalizationStrings` VALUES (1460, 1, 'Panama');
INSERT INTO `LocalizationStrings` VALUES (1461, 1, 'Papua New Guinea');
INSERT INTO `LocalizationStrings` VALUES (1462, 1, 'Paraguay');
INSERT INTO `LocalizationStrings` VALUES (1463, 1, 'Peru');
INSERT INTO `LocalizationStrings` VALUES (1464, 1, 'Philippines');
INSERT INTO `LocalizationStrings` VALUES (1465, 1, 'Pitcairn');
INSERT INTO `LocalizationStrings` VALUES (1466, 1, 'Poland');
INSERT INTO `LocalizationStrings` VALUES (1467, 1, 'Portugal');
INSERT INTO `LocalizationStrings` VALUES (1468, 1, 'Puerto Rico');
INSERT INTO `LocalizationStrings` VALUES (1469, 1, 'Qatar');
INSERT INTO `LocalizationStrings` VALUES (1470, 1, 'Reunion');
INSERT INTO `LocalizationStrings` VALUES (1471, 1, 'Romania');
INSERT INTO `LocalizationStrings` VALUES (1472, 1, 'Russia');
INSERT INTO `LocalizationStrings` VALUES (1473, 1, 'Rwanda');
INSERT INTO `LocalizationStrings` VALUES (1474, 1, 'Saint Lucia');
INSERT INTO `LocalizationStrings` VALUES (1475, 1, 'Samoa');
INSERT INTO `LocalizationStrings` VALUES (1476, 1, 'San Marino');
INSERT INTO `LocalizationStrings` VALUES (1477, 1, 'Saudi Arabia');
INSERT INTO `LocalizationStrings` VALUES (1478, 1, 'Senegal');
INSERT INTO `LocalizationStrings` VALUES (1479, 1, 'Seychelles');
INSERT INTO `LocalizationStrings` VALUES (1480, 1, 'Sierra Leone');
INSERT INTO `LocalizationStrings` VALUES (1481, 1, 'Singapore');
INSERT INTO `LocalizationStrings` VALUES (1482, 1, 'Slovakia');
INSERT INTO `LocalizationStrings` VALUES (1483, 1, 'Solomon Islands');
INSERT INTO `LocalizationStrings` VALUES (1484, 1, 'Somalia');
INSERT INTO `LocalizationStrings` VALUES (1485, 1, 'South Africa');
INSERT INTO `LocalizationStrings` VALUES (1486, 1, 'Spain');
INSERT INTO `LocalizationStrings` VALUES (1487, 1, 'Sri Lanka');
INSERT INTO `LocalizationStrings` VALUES (1488, 1, 'St. Helena');
INSERT INTO `LocalizationStrings` VALUES (1489, 1, 'Sudan');
INSERT INTO `LocalizationStrings` VALUES (1490, 1, 'Suriname');
INSERT INTO `LocalizationStrings` VALUES (1491, 1, 'Swaziland');
INSERT INTO `LocalizationStrings` VALUES (1492, 1, 'Sweden');
INSERT INTO `LocalizationStrings` VALUES (1493, 1, 'Switzerland');
INSERT INTO `LocalizationStrings` VALUES (1494, 1, 'Syria');
INSERT INTO `LocalizationStrings` VALUES (1495, 1, 'Taiwan');
INSERT INTO `LocalizationStrings` VALUES (1496, 1, 'Tajikistan');
INSERT INTO `LocalizationStrings` VALUES (1497, 1, 'Tanzania');
INSERT INTO `LocalizationStrings` VALUES (1498, 1, 'Thailand');
INSERT INTO `LocalizationStrings` VALUES (1499, 1, 'Togo');
INSERT INTO `LocalizationStrings` VALUES (1500, 1, 'Tokelau');
INSERT INTO `LocalizationStrings` VALUES (1501, 1, 'Tonga');
INSERT INTO `LocalizationStrings` VALUES (1502, 1, 'Trinidad and Tobago');
INSERT INTO `LocalizationStrings` VALUES (1503, 1, 'Tunisia');
INSERT INTO `LocalizationStrings` VALUES (1504, 1, 'Turkey');
INSERT INTO `LocalizationStrings` VALUES (1505, 1, 'Turkmenistan');
INSERT INTO `LocalizationStrings` VALUES (1506, 1, 'Tuvalu');
INSERT INTO `LocalizationStrings` VALUES (1507, 1, 'Uganda');
INSERT INTO `LocalizationStrings` VALUES (1508, 1, 'Ukraine');
INSERT INTO `LocalizationStrings` VALUES (1509, 1, 'United Arab Emirates');
INSERT INTO `LocalizationStrings` VALUES (1510, 1, 'United Kingdom');
INSERT INTO `LocalizationStrings` VALUES (1511, 1, 'USA');
INSERT INTO `LocalizationStrings` VALUES (1512, 1, 'Uruguay');
INSERT INTO `LocalizationStrings` VALUES (1513, 1, 'Uzbekistan');
INSERT INTO `LocalizationStrings` VALUES (1514, 1, 'Vanuatu');
INSERT INTO `LocalizationStrings` VALUES (1515, 1, 'Vatican');
INSERT INTO `LocalizationStrings` VALUES (1516, 1, 'Venezuela');
INSERT INTO `LocalizationStrings` VALUES (1517, 1, 'Viet Nam');
INSERT INTO `LocalizationStrings` VALUES (1518, 1, 'Virgin Islands');
INSERT INTO `LocalizationStrings` VALUES (1519, 1, 'Western Sahara');
INSERT INTO `LocalizationStrings` VALUES (1520, 1, 'Yemen');
INSERT INTO `LocalizationStrings` VALUES (1521, 1, 'Yugoslavia');
INSERT INTO `LocalizationStrings` VALUES (1522, 1, 'Zaire');
INSERT INTO `LocalizationStrings` VALUES (1523, 1, 'Zambia');
INSERT INTO `LocalizationStrings` VALUES (1524, 1, 'Zimbabwe');
INSERT INTO `LocalizationStrings` VALUES (1810, 1, 'Netherlands Antilles');
INSERT INTO `LocalizationStrings` VALUES (1811, 1, 'Bosnia and Herzegovina');
INSERT INTO `LocalizationStrings` VALUES (1812, 1, 'The Bahamas');
INSERT INTO `LocalizationStrings` VALUES (1813, 1, 'Cocos (Keeling) Islands');
INSERT INTO `LocalizationStrings` VALUES (1814, 1, 'Congo, Democratic Republic of the');
INSERT INTO `LocalizationStrings` VALUES (1815, 1, 'Central African Republic');
INSERT INTO `LocalizationStrings` VALUES (1816, 1, 'Congo, Republic of the');
INSERT INTO `LocalizationStrings` VALUES (1817, 1, 'Cote d''Ivoire');
INSERT INTO `LocalizationStrings` VALUES (1818, 1, 'Falkland Islands (Islas Malvinas)');
INSERT INTO `LocalizationStrings` VALUES (1819, 1, 'Micronesia, Federated States of');
INSERT INTO `LocalizationStrings` VALUES (1820, 1, 'French Guiana');
INSERT INTO `LocalizationStrings` VALUES (1821, 1, 'The Gambia');
INSERT INTO `LocalizationStrings` VALUES (1822, 1, 'South Georgia and the South Sandwich Islands');
INSERT INTO `LocalizationStrings` VALUES (1823, 1, 'Hong Kong (SAR)');
INSERT INTO `LocalizationStrings` VALUES (1824, 1, 'Heard Island and McDonald Islands');
INSERT INTO `LocalizationStrings` VALUES (1825, 1, 'British Indian Ocean Territory');
INSERT INTO `LocalizationStrings` VALUES (1826, 1, 'Saint Kitts and Nevis');
INSERT INTO `LocalizationStrings` VALUES (1827, 1, 'Korea, North');
INSERT INTO `LocalizationStrings` VALUES (1828, 1, 'Korea, South');
INSERT INTO `LocalizationStrings` VALUES (1829, 1, 'Laos');
INSERT INTO `LocalizationStrings` VALUES (1830, 1, 'Libya');
INSERT INTO `LocalizationStrings` VALUES (1831, 1, 'Macedonia, The Former Yugoslav Republic of');
INSERT INTO `LocalizationStrings` VALUES (1832, 1, 'Burma');
INSERT INTO `LocalizationStrings` VALUES (1833, 1, 'Macao');
INSERT INTO `LocalizationStrings` VALUES (1834, 1, 'Northern Mariana Islands');
INSERT INTO `LocalizationStrings` VALUES (1835, 1, 'French Polynesia');
INSERT INTO `LocalizationStrings` VALUES (1836, 1, 'Saint Pierre and Miquelon');
INSERT INTO `LocalizationStrings` VALUES (1837, 1, 'Pitcairn Islands');
INSERT INTO `LocalizationStrings` VALUES (1838, 1, 'Palestinian Territory, Occupied');
INSERT INTO `LocalizationStrings` VALUES (1839, 1, 'Saint Helena');
INSERT INTO `LocalizationStrings` VALUES (1840, 1, 'Slovenia');
INSERT INTO `LocalizationStrings` VALUES (1841, 1, 'Svalbard');
INSERT INTO `LocalizationStrings` VALUES (1842, 1, 'Sao Tome and Principe');
INSERT INTO `LocalizationStrings` VALUES (1843, 1, 'Turks and Caicos Islands');
INSERT INTO `LocalizationStrings` VALUES (1844, 1, 'French Southern and Antarctic Lands');
INSERT INTO `LocalizationStrings` VALUES (1845, 1, 'United States Minor Outlying Islands');
INSERT INTO `LocalizationStrings` VALUES (1846, 1, 'United States');
INSERT INTO `LocalizationStrings` VALUES (1847, 1, 'Holy See (Vatican City)');
INSERT INTO `LocalizationStrings` VALUES (1848, 1, 'Saint Vincent and the Grenadines');
INSERT INTO `LocalizationStrings` VALUES (1849, 1, 'British Virgin Islands');
INSERT INTO `LocalizationStrings` VALUES (1850, 1, 'Vietnam');
INSERT INTO `LocalizationStrings` VALUES (1851, 1, 'Wallis and Futuna');
INSERT INTO `LocalizationStrings` VALUES (1525, 1, 'High School graduate');
INSERT INTO `LocalizationStrings` VALUES (1526, 1, 'Some college');
INSERT INTO `LocalizationStrings` VALUES (1527, 1, 'College student');
INSERT INTO `LocalizationStrings` VALUES (1528, 1, 'AA (2 years college)');
INSERT INTO `LocalizationStrings` VALUES (1529, 1, 'BA/BS (4 years college)');
INSERT INTO `LocalizationStrings` VALUES (1530, 1, 'Some grad school');
INSERT INTO `LocalizationStrings` VALUES (1531, 1, 'Grad school student');
INSERT INTO `LocalizationStrings` VALUES (1532, 1, 'MA/MS/MBA');
INSERT INTO `LocalizationStrings` VALUES (1533, 1, 'PhD/Post doctorate');
INSERT INTO `LocalizationStrings` VALUES (1534, 1, 'JD');
INSERT INTO `LocalizationStrings` VALUES (1535, 1, 'African');
INSERT INTO `LocalizationStrings` VALUES (1536, 1, 'African American');
INSERT INTO `LocalizationStrings` VALUES (1537, 1, 'Asian');
INSERT INTO `LocalizationStrings` VALUES (1538, 1, 'Caucasian');
INSERT INTO `LocalizationStrings` VALUES (1539, 1, 'East Indian');
INSERT INTO `LocalizationStrings` VALUES (1540, 1, 'Hispanic');
INSERT INTO `LocalizationStrings` VALUES (1541, 1, 'Indian');
INSERT INTO `LocalizationStrings` VALUES (1542, 1, 'Latino');
INSERT INTO `LocalizationStrings` VALUES (1543, 1, 'Mediterranean');
INSERT INTO `LocalizationStrings` VALUES (1544, 1, 'Middle Eastern');
INSERT INTO `LocalizationStrings` VALUES (1545, 1, 'Mixed');
INSERT INTO `LocalizationStrings` VALUES (1546, 1, '4''7" (140cm) or below');
INSERT INTO `LocalizationStrings` VALUES (1547, 1, '4''8" - 4''11" (141-150cm)');
INSERT INTO `LocalizationStrings` VALUES (1548, 1, '5''0" - 5''3" (151-160cm)');
INSERT INTO `LocalizationStrings` VALUES (1549, 1, '5''4" - 5''7" (161-170cm)');
INSERT INTO `LocalizationStrings` VALUES (1550, 1, '5''8" - 5''11" (171-180cm)');
INSERT INTO `LocalizationStrings` VALUES (1551, 1, '6''0" - 6''3" (181-190cm)');
INSERT INTO `LocalizationStrings` VALUES (1552, 1, '6''4" (191cm) or above');
INSERT INTO `LocalizationStrings` VALUES (1553, 1, '$10,000/year and less');
INSERT INTO `LocalizationStrings` VALUES (1554, 1, '$10,000-$30,000/year');
INSERT INTO `LocalizationStrings` VALUES (1555, 1, '$30,000-$50,000/year');
INSERT INTO `LocalizationStrings` VALUES (1556, 1, '$50,000-$70,000/year');
INSERT INTO `LocalizationStrings` VALUES (1557, 1, '$70,000/year and more');
INSERT INTO `LocalizationStrings` VALUES (1558, 1, 'English');
INSERT INTO `LocalizationStrings` VALUES (1559, 1, 'Afrikaans');
INSERT INTO `LocalizationStrings` VALUES (1560, 1, 'Arabic');
INSERT INTO `LocalizationStrings` VALUES (1561, 1, 'Bulgarian');
INSERT INTO `LocalizationStrings` VALUES (1562, 1, 'Burmese');
INSERT INTO `LocalizationStrings` VALUES (1563, 1, 'Cantonese');
INSERT INTO `LocalizationStrings` VALUES (1564, 1, 'Croatian');
INSERT INTO `LocalizationStrings` VALUES (1565, 1, 'Danish');
INSERT INTO `LocalizationStrings` VALUES (1566, 1, 'Database Error');
INSERT INTO `LocalizationStrings` VALUES (1567, 1, 'Dutch');
INSERT INTO `LocalizationStrings` VALUES (1568, 1, 'Esperanto');
INSERT INTO `LocalizationStrings` VALUES (1569, 1, 'Estonian');
INSERT INTO `LocalizationStrings` VALUES (1570, 1, 'Finnish');
INSERT INTO `LocalizationStrings` VALUES (1571, 1, 'French');
INSERT INTO `LocalizationStrings` VALUES (1572, 1, 'German');
INSERT INTO `LocalizationStrings` VALUES (1573, 1, 'Greek');
INSERT INTO `LocalizationStrings` VALUES (1574, 1, 'Gujrati');
INSERT INTO `LocalizationStrings` VALUES (1575, 1, 'Hebrew');
INSERT INTO `LocalizationStrings` VALUES (1576, 1, 'Hindi');
INSERT INTO `LocalizationStrings` VALUES (1577, 1, 'Hungarian');
INSERT INTO `LocalizationStrings` VALUES (1578, 1, 'Icelandic');
INSERT INTO `LocalizationStrings` VALUES (1579, 1, 'Indonesian');
INSERT INTO `LocalizationStrings` VALUES (1580, 1, 'Italian');
INSERT INTO `LocalizationStrings` VALUES (1581, 1, 'Japanese');
INSERT INTO `LocalizationStrings` VALUES (1582, 1, 'Korean');
INSERT INTO `LocalizationStrings` VALUES (1583, 1, 'Latvian');
INSERT INTO `LocalizationStrings` VALUES (1584, 1, 'Lithuanian');
INSERT INTO `LocalizationStrings` VALUES (1585, 1, 'Malay');
INSERT INTO `LocalizationStrings` VALUES (1586, 1, 'Mandarin');
INSERT INTO `LocalizationStrings` VALUES (1587, 1, 'Marathi');
INSERT INTO `LocalizationStrings` VALUES (1588, 1, 'Moldovian');
INSERT INTO `LocalizationStrings` VALUES (1589, 1, 'Nepalese');
INSERT INTO `LocalizationStrings` VALUES (1590, 1, 'Norwegian');
INSERT INTO `LocalizationStrings` VALUES (1591, 1, 'Persian');
INSERT INTO `LocalizationStrings` VALUES (1592, 1, 'Polish');
INSERT INTO `LocalizationStrings` VALUES (1593, 1, 'Portuguese');
INSERT INTO `LocalizationStrings` VALUES (1594, 1, 'Punjabi');
INSERT INTO `LocalizationStrings` VALUES (1595, 1, 'Romanian');
INSERT INTO `LocalizationStrings` VALUES (1596, 1, 'Russian');
INSERT INTO `LocalizationStrings` VALUES (1597, 1, 'Serbian');
INSERT INTO `LocalizationStrings` VALUES (1598, 1, 'Spanish');
INSERT INTO `LocalizationStrings` VALUES (1599, 1, 'Swedish');
INSERT INTO `LocalizationStrings` VALUES (1600, 1, 'Tagalog');
INSERT INTO `LocalizationStrings` VALUES (1601, 1, 'Taiwanese');
INSERT INTO `LocalizationStrings` VALUES (1602, 1, 'Tamil');
INSERT INTO `LocalizationStrings` VALUES (1603, 1, 'Telugu');
INSERT INTO `LocalizationStrings` VALUES (1604, 1, 'Thai');
INSERT INTO `LocalizationStrings` VALUES (1605, 1, 'Tongan');
INSERT INTO `LocalizationStrings` VALUES (1606, 1, 'Turkish');
INSERT INTO `LocalizationStrings` VALUES (1607, 1, 'Ukrainian');
INSERT INTO `LocalizationStrings` VALUES (1608, 1, 'Urdu');
INSERT INTO `LocalizationStrings` VALUES (1609, 1, 'Vietnamese');
INSERT INTO `LocalizationStrings` VALUES (1610, 1, 'Visayan');
INSERT INTO `LocalizationStrings` VALUES (1611, 1, 'Single');
INSERT INTO `LocalizationStrings` VALUES (1612, 1, 'Attached');
INSERT INTO `LocalizationStrings` VALUES (1613, 1, 'Divorced');
INSERT INTO `LocalizationStrings` VALUES (1614, 1, 'Married');
INSERT INTO `LocalizationStrings` VALUES (1615, 1, 'Separated');
INSERT INTO `LocalizationStrings` VALUES (1616, 1, 'Widow/er');
INSERT INTO `LocalizationStrings` VALUES (1617, 1, 'Unconfirmed');
INSERT INTO `LocalizationStrings` VALUES (1618, 1, 'Approval');
INSERT INTO `LocalizationStrings` VALUES (1619, 1, 'Active');
INSERT INTO `LocalizationStrings` VALUES (1620, 1, 'Suspended');
INSERT INTO `LocalizationStrings` VALUES (1621, 1, 'Rejected');
INSERT INTO `LocalizationStrings` VALUES (1622, 1, 'Unconfirmed');
INSERT INTO `LocalizationStrings` VALUES (1623, 1, 'Approval');
INSERT INTO `LocalizationStrings` VALUES (1624, 1, 'Active');
INSERT INTO `LocalizationStrings` VALUES (1625, 1, 'Suspended');
INSERT INTO `LocalizationStrings` VALUES (1626, 1, 'Rejected');
INSERT INTO `LocalizationStrings` VALUES (1627, 1, 'Choose your profile type');
INSERT INTO `LocalizationStrings` VALUES (1628, 1, 'Activity Partner');
INSERT INTO `LocalizationStrings` VALUES (1629, 1, 'Casual');
INSERT INTO `LocalizationStrings` VALUES (1630, 1, 'Friendship');
INSERT INTO `LocalizationStrings` VALUES (1631, 1, 'Marriage');
INSERT INTO `LocalizationStrings` VALUES (1632, 1, 'Relationship');
INSERT INTO `LocalizationStrings` VALUES (1633, 1, 'Romance');
INSERT INTO `LocalizationStrings` VALUES (1634, 1, 'Travel Partner');
INSERT INTO `LocalizationStrings` VALUES (1635, 1, 'Pen Pal');
INSERT INTO `LocalizationStrings` VALUES (1636, 1, 'Pen Pal');
INSERT INTO `LocalizationStrings` VALUES (1637, 1, 'Activity Partner');
INSERT INTO `LocalizationStrings` VALUES (1638, 1, 'Casual');
INSERT INTO `LocalizationStrings` VALUES (1639, 1, 'Friendship');
INSERT INTO `LocalizationStrings` VALUES (1640, 1, 'Marriage');
INSERT INTO `LocalizationStrings` VALUES (1641, 1, 'Relationship');
INSERT INTO `LocalizationStrings` VALUES (1642, 1, 'Romance');
INSERT INTO `LocalizationStrings` VALUES (1643, 1, 'Travel Partner');
INSERT INTO `LocalizationStrings` VALUES (1644, 1, 'Pen Pal');
INSERT INTO `LocalizationStrings` VALUES (1645, 1, 'Adventist');
INSERT INTO `LocalizationStrings` VALUES (1646, 1, 'Agnostic');
INSERT INTO `LocalizationStrings` VALUES (1647, 1, 'Atheist');
INSERT INTO `LocalizationStrings` VALUES (1648, 1, 'Baptist');
INSERT INTO `LocalizationStrings` VALUES (1649, 1, 'Buddhist');
INSERT INTO `LocalizationStrings` VALUES (1650, 1, 'Caodaism');
INSERT INTO `LocalizationStrings` VALUES (1651, 1, 'Catholic');
INSERT INTO `LocalizationStrings` VALUES (1652, 1, 'Christian');
INSERT INTO `LocalizationStrings` VALUES (1653, 1, 'Hindu');
INSERT INTO `LocalizationStrings` VALUES (1654, 1, 'Iskcon');
INSERT INTO `LocalizationStrings` VALUES (1655, 1, 'Jainism');
INSERT INTO `LocalizationStrings` VALUES (1656, 1, 'Jewish');
INSERT INTO `LocalizationStrings` VALUES (1657, 1, 'Methodist');
INSERT INTO `LocalizationStrings` VALUES (1658, 1, 'Mormon');
INSERT INTO `LocalizationStrings` VALUES (1659, 1, 'Moslem');
INSERT INTO `LocalizationStrings` VALUES (1660, 1, 'Orthodox');
INSERT INTO `LocalizationStrings` VALUES (1661, 1, 'Pentecostal');
INSERT INTO `LocalizationStrings` VALUES (1662, 1, 'Protestant');
INSERT INTO `LocalizationStrings` VALUES (1663, 1, 'Quaker');
INSERT INTO `LocalizationStrings` VALUES (1664, 1, 'Scientology');
INSERT INTO `LocalizationStrings` VALUES (1665, 1, 'Shinto');
INSERT INTO `LocalizationStrings` VALUES (1666, 1, 'Sikhism');
INSERT INTO `LocalizationStrings` VALUES (1667, 1, 'Spiritual');
INSERT INTO `LocalizationStrings` VALUES (1668, 1, 'Taoism');
INSERT INTO `LocalizationStrings` VALUES (1669, 1, 'Wiccan');
INSERT INTO `LocalizationStrings` VALUES (1670, 1, 'Other');
INSERT INTO `LocalizationStrings` VALUES (1671, 1, 'No');
INSERT INTO `LocalizationStrings` VALUES (1672, 1, 'Rarely');
INSERT INTO `LocalizationStrings` VALUES (1673, 1, 'Often');
INSERT INTO `LocalizationStrings` VALUES (1674, 1, 'Very often');
INSERT INTO `LocalizationStrings` VALUES (1675, 1, 'Allowed actions');
INSERT INTO `LocalizationStrings` VALUES (1676, 1, 'Action');
INSERT INTO `LocalizationStrings` VALUES (1677, 1, 'Times allowed');
INSERT INTO `LocalizationStrings` VALUES (1678, 1, 'Period (hours)');
INSERT INTO `LocalizationStrings` VALUES (1679, 1, 'Allowed Since');
INSERT INTO `LocalizationStrings` VALUES (1680, 1, 'Allowed Until');
INSERT INTO `LocalizationStrings` VALUES (1681, 1, 'No actions allowed for this membership');
INSERT INTO `LocalizationStrings` VALUES (1682, 1, 'no limit');
INSERT INTO `LocalizationStrings` VALUES (1683, 1, 'send greetings');
INSERT INTO `LocalizationStrings` VALUES (1684, 1, 'use chat');
INSERT INTO `LocalizationStrings` VALUES (1685, 1, 'use instant messenger');
INSERT INTO `LocalizationStrings` VALUES (1686, 1, 'view profiles');
INSERT INTO `LocalizationStrings` VALUES (1687, 1, 'use forum');
INSERT INTO `LocalizationStrings` VALUES (1688, 1, 'make search');
INSERT INTO `LocalizationStrings` VALUES (1689, 1, 'rate photos');
INSERT INTO `LocalizationStrings` VALUES (1690, 1, 'send messages');
INSERT INTO `LocalizationStrings` VALUES (1691, 1, 'view photos');
INSERT INTO `LocalizationStrings` VALUES (1692, 1, 'use Ray instant messenger');
INSERT INTO `LocalizationStrings` VALUES (1693, 1, 'use Ray video recorder');
INSERT INTO `LocalizationStrings` VALUES (1694, 1, 'use Ray chat');
INSERT INTO `LocalizationStrings` VALUES (1695, 1, 'use guestbook');
INSERT INTO `LocalizationStrings` VALUES (1696, 1, 'view other members'' guestbooks');
INSERT INTO `LocalizationStrings` VALUES (1697, 1, 'get other members'' emails');
INSERT INTO `LocalizationStrings` VALUES (1698, 1, 'CONTACT US');
INSERT INTO `LocalizationStrings` VALUES (1699, 1, 'Rate');
INSERT INTO `LocalizationStrings` VALUES (1700, 1, 'No new messages (<a href="{0}mail.php?mode=inbox">go to Inbox</a>)');
INSERT INTO `LocalizationStrings` VALUES (1701, 1, 'No new greetings (<a href="{0}contacts.php?show=greet&amp;list=i">go to My Greetings</a>)');
INSERT INTO `LocalizationStrings` VALUES (1702, 1, 'No new friends (<a href="{0}contacts.php?show=friends">go to My Friends</a>)');
INSERT INTO `LocalizationStrings` VALUES (1703, 1, '18-20');
INSERT INTO `LocalizationStrings` VALUES (1704, 1, '21-25');
INSERT INTO `LocalizationStrings` VALUES (1705, 1, '26-30');
INSERT INTO `LocalizationStrings` VALUES (1706, 1, '31-35');
INSERT INTO `LocalizationStrings` VALUES (1707, 1, '36-40');
INSERT INTO `LocalizationStrings` VALUES (1708, 1, '41-45');
INSERT INTO `LocalizationStrings` VALUES (1709, 1, '46-50');
INSERT INTO `LocalizationStrings` VALUES (1710, 1, '51-55');
INSERT INTO `LocalizationStrings` VALUES (1711, 1, '56-60');
INSERT INTO `LocalizationStrings` VALUES (1712, 1, '61-65');
INSERT INTO `LocalizationStrings` VALUES (1713, 1, '66-70');
INSERT INTO `LocalizationStrings` VALUES (1714, 1, '71-75');
INSERT INTO `LocalizationStrings` VALUES (1715, 1, 'Aries');
INSERT INTO `LocalizationStrings` VALUES (1716, 1, 'Taurus');
INSERT INTO `LocalizationStrings` VALUES (1717, 1, 'Gemini');
INSERT INTO `LocalizationStrings` VALUES (1718, 1, 'Cancer');
INSERT INTO `LocalizationStrings` VALUES (1719, 1, 'Leo');
INSERT INTO `LocalizationStrings` VALUES (1720, 1, 'Virgo');
INSERT INTO `LocalizationStrings` VALUES (1721, 1, 'Libra');
INSERT INTO `LocalizationStrings` VALUES (1722, 1, 'Scorpio');
INSERT INTO `LocalizationStrings` VALUES (1723, 1, 'Sagittarius');
INSERT INTO `LocalizationStrings` VALUES (1724, 1, 'Capricorn');
INSERT INTO `LocalizationStrings` VALUES (1725, 1, 'Aquarius');
INSERT INTO `LocalizationStrings` VALUES (1726, 1, 'Pisces');
INSERT INTO `LocalizationStrings` VALUES (1727, 1, 'Zodiac');
INSERT INTO `LocalizationStrings` VALUES (1728, 1, '<div style="width: 80%">Your current membership (<b>{2}</b>) doesn''t allow you to <b>{1}</b>.</div>');
INSERT INTO `LocalizationStrings` VALUES (1729, 1, '<div style="width: 80%">You are not currently an active member. Please ask the site <a href="mailto:{7}">administrator</a> to make you an active member so you can use this feature.</div>');
INSERT INTO `LocalizationStrings` VALUES (1730, 1, 'You have reached your limit for now. Your current membership (<b>{2}</b>) allows you to {1} no more than {3} times');
INSERT INTO `LocalizationStrings` VALUES (1731, 1, '<div style="width: 80%">Your current membership (<b>{2}</b>) doesn''t allow you to <b>{1}</b> until <b>{6}</b>.</div>');
INSERT INTO `LocalizationStrings` VALUES (1732, 1, '<div style="width: 80%">Your current membership (<b>{2}</b>) doesn''t allow you to <b>{1}</b> since <b>{5}</b>.</div>');
INSERT INTO `LocalizationStrings` VALUES (1734, 1, 'Choose forum');
INSERT INTO `LocalizationStrings` VALUES (1735, 1, 'Module access error');
INSERT INTO `LocalizationStrings` VALUES (1736, 1, 'Choose the forum to log in');
INSERT INTO `LocalizationStrings` VALUES (1737, 1, 'Choose the forum from the following:');
INSERT INTO `LocalizationStrings` VALUES (1738, 1, 'Dolphin Administrator');
INSERT INTO `LocalizationStrings` VALUES (1739, 1, 'Get membership');
INSERT INTO `LocalizationStrings` VALUES (1740, 1, 'Get new membership');
INSERT INTO `LocalizationStrings` VALUES (1741, 1, 'requires {0} members');
INSERT INTO `LocalizationStrings` VALUES (1742, 1, 'No forums installed');
INSERT INTO `LocalizationStrings` VALUES (1743, 1, 'No chats installed');
INSERT INTO `LocalizationStrings` VALUES (1744, 1, '<a href="getmem.php">Click here</a> to change your membership status');
INSERT INTO `LocalizationStrings` VALUES (1745, 1, 'Events');
INSERT INTO `LocalizationStrings` VALUES (1746, 1, 'No events available');
INSERT INTO `LocalizationStrings` VALUES (1747, 1, '{0} photo');
INSERT INTO `LocalizationStrings` VALUES (1748, 1, 'No photo');
INSERT INTO `LocalizationStrings` VALUES (1749, 1, 'Select events to show');
INSERT INTO `LocalizationStrings` VALUES (1750, 1, 'Show events by country');
INSERT INTO `LocalizationStrings` VALUES (1751, 1, 'Show all events');
INSERT INTO `LocalizationStrings` VALUES (1752, 1, 'Show info');
INSERT INTO `LocalizationStrings` VALUES (1753, 1, 'Participants');
INSERT INTO `LocalizationStrings` VALUES (1754, 1, 'Choose participants you liked');
INSERT INTO `LocalizationStrings` VALUES (1755, 1, 'Status message');
INSERT INTO `LocalizationStrings` VALUES (1756, 1, 'Appointed date/time');
INSERT INTO `LocalizationStrings` VALUES (1757, 1, 'Place');
INSERT INTO `LocalizationStrings` VALUES (1758, 1, 'There are no participants for this event');
INSERT INTO `LocalizationStrings` VALUES (1759, 1, 'You are not a participant of the specified event');
INSERT INTO `LocalizationStrings` VALUES (1760, 1, 'Apply choice');
INSERT INTO `LocalizationStrings` VALUES (1761, 1, 'Event is unavailable');
INSERT INTO `LocalizationStrings` VALUES (1762, 1, 'Event start');
INSERT INTO `LocalizationStrings` VALUES (1763, 1, 'Event end');
INSERT INTO `LocalizationStrings` VALUES (1764, 1, 'Ticket sale start');
INSERT INTO `LocalizationStrings` VALUES (1765, 1, 'Ticket sale end');
INSERT INTO `LocalizationStrings` VALUES (1766, 1, 'Responsible person');
INSERT INTO `LocalizationStrings` VALUES (1767, 1, 'Tickets left');
INSERT INTO `LocalizationStrings` VALUES (1768, 1, 'Ticket price');
INSERT INTO `LocalizationStrings` VALUES (1769, 1, 'Sale status');
INSERT INTO `LocalizationStrings` VALUES (1770, 1, 'Sale finished');
INSERT INTO `LocalizationStrings` VALUES (1771, 1, 'Sale not started yet');
INSERT INTO `LocalizationStrings` VALUES (1772, 1, 'No tickets left');
INSERT INTO `LocalizationStrings` VALUES (1773, 1, 'Event has already started');
INSERT INTO `LocalizationStrings` VALUES (1774, 1, 'Event has already finished');
INSERT INTO `LocalizationStrings` VALUES (1775, 1, 'You are already a participant of this event. Here is your personal Unique ID for this event:');
INSERT INTO `LocalizationStrings` VALUES (1776, 1, 'You can buy the ticket');
INSERT INTO `LocalizationStrings` VALUES (1777, 1, 'Buy ticket');
INSERT INTO `LocalizationStrings` VALUES (1778, 1, 'Change');
INSERT INTO `LocalizationStrings` VALUES (1779, 1, 'Can''t change participant UID');
INSERT INTO `LocalizationStrings` VALUES (1780, 1, 'UID already exists');
INSERT INTO `LocalizationStrings` VALUES (1781, 1, 'You successfully purchased the Event ticket, but an e-mail with event information wasn''t sent. Don''t worry, you can view this data on the event information page.');
INSERT INTO `LocalizationStrings` VALUES (1782, 1, 'Event participants');
INSERT INTO `LocalizationStrings` VALUES (1783, 1, 'Event UID');
INSERT INTO `LocalizationStrings` VALUES (1787, 1, 'Participants you liked');
INSERT INTO `LocalizationStrings` VALUES (1788, 1, 'Show calendar');
INSERT INTO `LocalizationStrings` VALUES (1789, 1, 'Calendar');
INSERT INTO `LocalizationStrings` VALUES (1790, 1, 'Sun');
INSERT INTO `LocalizationStrings` VALUES (1791, 1, 'Mon');
INSERT INTO `LocalizationStrings` VALUES (1792, 1, 'Tue');
INSERT INTO `LocalizationStrings` VALUES (1793, 1, 'Wed');
INSERT INTO `LocalizationStrings` VALUES (1794, 1, 'Thu');
INSERT INTO `LocalizationStrings` VALUES (1795, 1, 'Fri');
INSERT INTO `LocalizationStrings` VALUES (1796, 1, 'Sat');
INSERT INTO `LocalizationStrings` VALUES (1797, 1, 'Events tickets');
INSERT INTO `LocalizationStrings` VALUES (1798, 1, 'Invalid module type selected');
INSERT INTO `LocalizationStrings` VALUES (1799, 1, 'Module directory was not set. Module must be re-configured.');
INSERT INTO `LocalizationStrings` VALUES (1800, 1, 'Select module type');
INSERT INTO `LocalizationStrings` VALUES (1801, 1, 'Please login before using Ray chat');
INSERT INTO `LocalizationStrings` VALUES (1802, 1, 'Ray is not enabled');
INSERT INTO `LocalizationStrings` VALUES (1803, 1, 'No modules of this type installed');
INSERT INTO `LocalizationStrings` VALUES (1804, 1, 'Module selection');
INSERT INTO `LocalizationStrings` VALUES (1805, 1, 'Choose module to log in');
INSERT INTO `LocalizationStrings` VALUES (1806, 1, 'Choose module type');
INSERT INTO `LocalizationStrings` VALUES (1807, 1, 'Module type selection');
INSERT INTO `LocalizationStrings` VALUES (1808, 1, 'No modules found');
INSERT INTO `LocalizationStrings` VALUES (1809, 1, 'Ray is not enabled. Please <a href="{0}">select another module</a>');
INSERT INTO `LocalizationStrings` VALUES (1852, 1, 'Check out');
INSERT INTO `LocalizationStrings` VALUES (1853, 1, 'Membership purchase');
INSERT INTO `LocalizationStrings` VALUES (1854, 1, 'Event ticket purchase');
INSERT INTO `LocalizationStrings` VALUES (1855, 1, 'Credits purchase');
INSERT INTO `LocalizationStrings` VALUES (1856, 1, 'Profiles purchase');
INSERT INTO `LocalizationStrings` VALUES (1857, 1, 'Payment description');
INSERT INTO `LocalizationStrings` VALUES (1858, 1, 'Payment amount');
INSERT INTO `LocalizationStrings` VALUES (1859, 1, 'Possible subscription period');
INSERT INTO `LocalizationStrings` VALUES (1860, 1, 'Payment info');
INSERT INTO `LocalizationStrings` VALUES (1861, 1, 'Payment methods');
INSERT INTO `LocalizationStrings` VALUES (1862, 1, 'Credit balance');
INSERT INTO `LocalizationStrings` VALUES (1864, 1, 'recurring payment');
INSERT INTO `LocalizationStrings` VALUES (1865, 1, 'recurring not supported');
INSERT INTO `LocalizationStrings` VALUES (1866, 1, 'recurring not allowed');
INSERT INTO `LocalizationStrings` VALUES (1867, 1, 'Lifetime');
INSERT INTO `LocalizationStrings` VALUES (1868, 1, 'every {0} days');
INSERT INTO `LocalizationStrings` VALUES (1869, 1, 'Subscriptions');
INSERT INTO `LocalizationStrings` VALUES (1870, 1, 'Start date');
INSERT INTO `LocalizationStrings` VALUES (1871, 1, 'Period');
INSERT INTO `LocalizationStrings` VALUES (1872, 1, 'Charges number');
INSERT INTO `LocalizationStrings` VALUES (1873, 1, 'Cancel');
INSERT INTO `LocalizationStrings` VALUES (1874, 1, 'Subscription cancellation request was successfully sent');
INSERT INTO `LocalizationStrings` VALUES (1875, 1, 'Failed to send subscription cancellation request');
INSERT INTO `LocalizationStrings` VALUES (1876, 1, 'Message Subject');
INSERT INTO `LocalizationStrings` VALUES (1877, 1, 'Customize Profile');
INSERT INTO `LocalizationStrings` VALUES (1878, 1, 'Background color');
INSERT INTO `LocalizationStrings` VALUES (1879, 1, 'Background picture');
INSERT INTO `LocalizationStrings` VALUES (1880, 1, 'Font color');
INSERT INTO `LocalizationStrings` VALUES (1881, 1, 'Font size');
INSERT INTO `LocalizationStrings` VALUES (1882, 1, 'Font family');
INSERT INTO `LocalizationStrings` VALUES (1883, 1, 'Credit card number');
INSERT INTO `LocalizationStrings` VALUES (1884, 1, 'Expiration date');
INSERT INTO `LocalizationStrings` VALUES (1885, 1, 'You did not receive any messages from {0}');
INSERT INTO `LocalizationStrings` VALUES (1886, 1, 'You did not write any message to {0}');
INSERT INTO `LocalizationStrings` VALUES (1887, 1, 'Your messages to {0}');
INSERT INTO `LocalizationStrings` VALUES (1888, 1, 'Messages from {0} to you');
INSERT INTO `LocalizationStrings` VALUES (1889, 1, 'Reset');
INSERT INTO `LocalizationStrings` VALUES (1890, 1, 'Customize');
INSERT INTO `LocalizationStrings` VALUES (1891, 1, 'No rated profiles this week');
INSERT INTO `LocalizationStrings` VALUES (1892, 1, 'No rated profiles this month');
INSERT INTO `LocalizationStrings` VALUES (1893, 1, 'Ray chat');
INSERT INTO `LocalizationStrings` VALUES (1894, 1, 'Ray instant messenger');
INSERT INTO `LocalizationStrings` VALUES (1895, 1, 'web community site');
INSERT INTO `LocalizationStrings` VALUES (1896, 1, 'Powered by <a href="http://www.boonex.com/products/dolphin/">Dolphin Smart Community Builder</a> &nbsp; <a href="http://www.boonex.com/products/orca/">Orca Interactive Forum Script</a> &nbsp; <a href="http://www.boonex.com/products/ray/">Ray Community Widget Suite</a>');
INSERT INTO `LocalizationStrings` VALUES (1897, 1, 'Welcome to our online community. <a href="{0}{1}">Join now</a><br>to enjoy the exciting features offered by our site:');
INSERT INTO `LocalizationStrings` VALUES (1898, 1, 'Profiles with galleries and audio/video');
INSERT INTO `LocalizationStrings` VALUES (1899, 1, 'Personal weblogs and friends-lists');
INSERT INTO `LocalizationStrings` VALUES (1900, 1, 'Audio/video chat, IM and recorder');
INSERT INTO `LocalizationStrings` VALUES (1901, 1, 'SPAM-blocking and anti-SPAM system');
INSERT INTO `LocalizationStrings` VALUES (1902, 1, 'ZIP-codes search, matchmaking and so much more');
INSERT INTO `LocalizationStrings` VALUES (1903, 1, '<strong>Are you a member?</strong> Login to your member account:');
INSERT INTO `LocalizationStrings` VALUES (1904, 1, 'Not a member?');
INSERT INTO `LocalizationStrings` VALUES (1905, 1, 'username');
INSERT INTO `LocalizationStrings` VALUES (1906, 1, 'Forgot username or password');
INSERT INTO `LocalizationStrings` VALUES (1907, 1, 'browse');
INSERT INTO `LocalizationStrings` VALUES (1908, 1, 'Previous photo results');
INSERT INTO `LocalizationStrings` VALUES (1909, 1, 'This Nickname already used !!');
INSERT INTO `LocalizationStrings` VALUES (1910, 1, 'To compose new message for <strong>{0}</strong> click <a href="{2}compose.php?ID={1}">here</a>.');
INSERT INTO `LocalizationStrings` VALUES (1911, 1, 'Ray recorder');
INSERT INTO `LocalizationStrings` VALUES (1912, 1, 'Match');
INSERT INTO `LocalizationStrings` VALUES (1913, 1, 'Please enter you message text');
INSERT INTO `LocalizationStrings` VALUES (1914, 1, 'Profile Comments');
INSERT INTO `LocalizationStrings` VALUES (1915, 1, 'Add new event');
INSERT INTO `LocalizationStrings` VALUES (1916, 1, 'Title');
INSERT INTO `LocalizationStrings` VALUES (1917, 1, 'Venue photo');
INSERT INTO `LocalizationStrings` VALUES (1918, 1, 'Female ticket count');
INSERT INTO `LocalizationStrings` VALUES (1919, 1, 'Male ticket count');
INSERT INTO `LocalizationStrings` VALUES (1920, 1, 'Couples ticket count');
INSERT INTO `LocalizationStrings` VALUES (1921, 1, 'Please fill out all fields');
INSERT INTO `LocalizationStrings` VALUES (1922, 1, 'Wrong date format or wrong date order');
INSERT INTO `LocalizationStrings` VALUES (1923, 1, 'Error during photo resizing');
INSERT INTO `LocalizationStrings` VALUES (1924, 1, 'Audio file successfully deleted');
INSERT INTO `LocalizationStrings` VALUES (1926, 1, 'Read Comments');
INSERT INTO `LocalizationStrings` VALUES (1927, 1, 'Read New Comments');
INSERT INTO `LocalizationStrings` VALUES (1928, 1, 'No albums found');
INSERT INTO `LocalizationStrings` VALUES (1929, 1, 'Delete object');
INSERT INTO `LocalizationStrings` VALUES (1930, 1, 'Poll created');
INSERT INTO `LocalizationStrings` VALUES (1931, 1, 'Maximum number of allowed polls reached');
INSERT INTO `LocalizationStrings` VALUES (1932, 1, 'controls');
INSERT INTO `LocalizationStrings` VALUES (1933, 1, 'Are you sure?');
INSERT INTO `LocalizationStrings` VALUES (1934, 1, 'no poll');
INSERT INTO `LocalizationStrings` VALUES (1935, 1, 'Question');
INSERT INTO `LocalizationStrings` VALUES (1936, 1, 'Answer variants');
INSERT INTO `LocalizationStrings` VALUES (1937, 1, 'add answer');
INSERT INTO `LocalizationStrings` VALUES (1938, 1, 'generate poll');
INSERT INTO `LocalizationStrings` VALUES (1939, 1, 'Create poll');
INSERT INTO `LocalizationStrings` VALUES (1940, 1, 'random polls');
INSERT INTO `LocalizationStrings` VALUES (1941, 1, 'latest polls');
INSERT INTO `LocalizationStrings` VALUES (1942, 1, 'top polls');
INSERT INTO `LocalizationStrings` VALUES (1943, 1, 'No profile polls available.');
INSERT INTO `LocalizationStrings` VALUES (1944, 1, 'My polls');
INSERT INTO `LocalizationStrings` VALUES (1945, 1, 'delete');
INSERT INTO `LocalizationStrings` VALUES (1946, 1, 'this poll');
INSERT INTO `LocalizationStrings` VALUES (1947, 1, 'loading ...');
INSERT INTO `LocalizationStrings` VALUES (1948, 1, 'Poll successfully deleted');
INSERT INTO `LocalizationStrings` VALUES (1949, 1, 'make it');
INSERT INTO `LocalizationStrings` VALUES (1950, 1, 'use gallery');
INSERT INTO `LocalizationStrings` VALUES (1951, 1, 'view other member galleries');
INSERT INTO `LocalizationStrings` VALUES (1952, 1, 'Gallery disabled for the member');
INSERT INTO `LocalizationStrings` VALUES (1953, 1, 'Original letter');
INSERT INTO `LocalizationStrings` VALUES (1954, 1, 'Recipient');
INSERT INTO `LocalizationStrings` VALUES (1955, 1, 'All');
INSERT INTO `LocalizationStrings` VALUES (1956, 1, 'Fast and secure online community');
INSERT INTO `LocalizationStrings` VALUES (1957, 1, 'Post a free personal ad');
INSERT INTO `LocalizationStrings` VALUES (1958, 1, 'Instantly find that special someone');
INSERT INTO `LocalizationStrings` VALUES (1959, 1, 'creating lifestyle communities');
INSERT INTO `LocalizationStrings` VALUES (1961, 1, '{0}''s Gallery');
INSERT INTO `LocalizationStrings` VALUES (1962, 1, 'friends friends friends friends friends friends friends friends friends friends friends friends friends friends ');
INSERT INTO `LocalizationStrings` VALUES (1963, 1, 'private private private private private private private private private private private private private ');
INSERT INTO `LocalizationStrings` VALUES (1964, 1, 'To use Gallery you need to<br />\r\n1) Add New Album,<br />\r\n2) Upload objects into the Album,<br />\r\n3) Share the album with others.');
INSERT INTO `LocalizationStrings` VALUES (1966, 1, '<b>Basic objects Guidelines</b>\r\n-Only send photo files with a JPG, JPEG, BMP, GIF, PNG extension.\r\n-Audio files with a MP3, WAV extension.\r\n- Video AVI files.\r\n{0}');
INSERT INTO `LocalizationStrings` VALUES (1967, 1, 'Please type domain without http://\r\nfor example: www.yourdomain.com');
INSERT INTO `LocalizationStrings` VALUES (1968, 1, 'Forgot Password');
INSERT INTO `LocalizationStrings` VALUES (1969, 1, 'Log In');
INSERT INTO `LocalizationStrings` VALUES (1970, 1, 'About');
INSERT INTO `LocalizationStrings` VALUES (1971, 1, 'Photos');
INSERT INTO `LocalizationStrings` VALUES (1972, 1, 'Contact Us');
INSERT INTO `LocalizationStrings` VALUES (1973, 1, 'Copyright &copy; 2007 Your Company.');
INSERT INTO `LocalizationStrings` VALUES (1974, 1, 'random');
INSERT INTO `LocalizationStrings` VALUES (1975, 1, 'latest');
INSERT INTO `LocalizationStrings` VALUES (1976, 1, 'more online members');
INSERT INTO `LocalizationStrings` VALUES (1977, 1, 'COMMUNITY');
INSERT INTO `LocalizationStrings` VALUES (1978, 1, 'MP3 Player');
INSERT INTO `LocalizationStrings` VALUES (1980, 1, 'You must disable your popup blocker software to view RAY MP3 Editor');
INSERT INTO `LocalizationStrings` VALUES (1981, 1, 'You must disable your popup blocker software to view RAY MP3 Admin');
INSERT INTO `LocalizationStrings` VALUES (1982, 1, 'Your membership doesn''t allow you to read messages.  ');
INSERT INTO `LocalizationStrings` VALUES (1984, 1, '{0} day(s)');
INSERT INTO `LocalizationStrings` VALUES (1985, 1, '{0} hour(s)');
INSERT INTO `LocalizationStrings` VALUES (1986, 1, '{0} minute(s)');
INSERT INTO `LocalizationStrings` VALUES (1987, 1, 'Successfully deleted {0} row(s)');
INSERT INTO `LocalizationStrings` VALUES (1988, 1, 'Failed to delete rows');
INSERT INTO `LocalizationStrings` VALUES (1990, 1, 'age');
INSERT INTO `LocalizationStrings` VALUES (1991, 1, 'Best personals script ever!');
INSERT INTO `LocalizationStrings` VALUES (1992, 1, '- post your own free ad with photos');
INSERT INTO `LocalizationStrings` VALUES (1993, 1, '- chat online with other members');
INSERT INTO `LocalizationStrings` VALUES (1994, 1, '- talk privately via instant messenger');
INSERT INTO `LocalizationStrings` VALUES (1995, 1, '- upload voice and video messages');
INSERT INTO `LocalizationStrings` VALUES (1996, 1, '- use our great matchmaking system');
INSERT INTO `LocalizationStrings` VALUES (1997, 1, '- surf the site in multiple languages');
INSERT INTO `LocalizationStrings` VALUES (1998, 1, '- find singles with ZIP code locator');
INSERT INTO `LocalizationStrings` VALUES (1999, 1, '- buy membership or contact stamps');
INSERT INTO `LocalizationStrings` VALUES (2000, 1, 'Failed to upload file because it too big.\r\nFile should be no bigger than {0} KB');
INSERT INTO `LocalizationStrings` VALUES (2001, 1, 'Blog title');
INSERT INTO `LocalizationStrings` VALUES (2002, 1, 'blog text');
INSERT INTO `LocalizationStrings` VALUES (2003, 1, 'category');
INSERT INTO `LocalizationStrings` VALUES (2004, 1, 'please fill in these fields first');
INSERT INTO `LocalizationStrings` VALUES (2005, 1, 'please select');
INSERT INTO `LocalizationStrings` VALUES (2006, 1, 'associated image');
INSERT INTO `LocalizationStrings` VALUES (2007, 1, 'post comment permissions');
INSERT INTO `LocalizationStrings` VALUES (2008, 1, 'read permissions');
INSERT INTO `LocalizationStrings` VALUES (2009, 1, 'apply changes');
INSERT INTO `LocalizationStrings` VALUES (2010, 1, 'add blog');
INSERT INTO `LocalizationStrings` VALUES (2011, 1, 'Category description');
INSERT INTO `LocalizationStrings` VALUES (2012, 1, 'Category title');
INSERT INTO `LocalizationStrings` VALUES (2013, 1, 'max chars');
INSERT INTO `LocalizationStrings` VALUES (2014, 1, 'add category');
INSERT INTO `LocalizationStrings` VALUES (2015, 1, '{0}''s blog');
INSERT INTO `LocalizationStrings` VALUES (2016, 1, 'edit category');
INSERT INTO `LocalizationStrings` VALUES (2017, 1, 'characters left');
INSERT INTO `LocalizationStrings` VALUES (2018, 1, 'there is nothing to view');
INSERT INTO `LocalizationStrings` VALUES (2019, 1, 'this blog only for friends');
INSERT INTO `LocalizationStrings` VALUES (2020, 1, 'commenting in this blog allowed only for friends');
INSERT INTO `LocalizationStrings` VALUES (2021, 1, 'you don''t have permission to edit');
INSERT INTO `LocalizationStrings` VALUES (2022, 1, 'comments of the blog deleted successfully');
INSERT INTO `LocalizationStrings` VALUES (2023, 1, 'failed to delete comments of the blog');
INSERT INTO `LocalizationStrings` VALUES (2024, 1, 'blogs of the category deleted successfully');
INSERT INTO `LocalizationStrings` VALUES (2025, 1, 'failed to delete blogs of the category');
INSERT INTO `LocalizationStrings` VALUES (2026, 1, 'category deleted');
INSERT INTO `LocalizationStrings` VALUES (2027, 1, 'failed to delete the category');
INSERT INTO `LocalizationStrings` VALUES (2028, 1, 'category successfully added');
INSERT INTO `LocalizationStrings` VALUES (2029, 1, 'failed to add category');
INSERT INTO `LocalizationStrings` VALUES (2030, 1, 'changes successfully applied');
INSERT INTO `LocalizationStrings` VALUES (2031, 1, 'failed to add blog');
INSERT INTO `LocalizationStrings` VALUES (2032, 1, 'comment added successfully');
INSERT INTO `LocalizationStrings` VALUES (2033, 1, 'failed to add comment');
INSERT INTO `LocalizationStrings` VALUES (2034, 1, 'deleted successfully');
INSERT INTO `LocalizationStrings` VALUES (2035, 1, 'failed to delete');
INSERT INTO `LocalizationStrings` VALUES (2036, 1, 'blog deleted successfully');
INSERT INTO `LocalizationStrings` VALUES (2037, 1, 'failed to delete blog');
INSERT INTO `LocalizationStrings` VALUES (2038, 1, 'blog disabled for the member');
INSERT INTO `LocalizationStrings` VALUES (2039, 1, 'edit blog');
INSERT INTO `LocalizationStrings` VALUES (2040, 1, 'Join Our');
INSERT INTO `LocalizationStrings` VALUES (2041, 1, 'Find your friends,');
INSERT INTO `LocalizationStrings` VALUES (2042, 1, 'share your interests ');
INSERT INTO `LocalizationStrings` VALUES (2043, 1, 'and just stay tuned!');
INSERT INTO `LocalizationStrings` VALUES (2044, 1, 'community!');
INSERT INTO `LocalizationStrings` VALUES (2045, 1, 'My Presence');
INSERT INTO `LocalizationStrings` VALUES (2046, 1, 'Ray Presence not allowed for your membership level.');
INSERT INTO `LocalizationStrings` VALUES (2047, 1, 'use blog');
INSERT INTO `LocalizationStrings` VALUES (2048, 1, 'Orca forum');
INSERT INTO `LocalizationStrings` VALUES (2051, 1, 'My pages');
INSERT INTO `LocalizationStrings` VALUES (2052, 1, 'Help');
INSERT INTO `LocalizationStrings` VALUES (2053, 1, 'Dolphin');
INSERT INTO `LocalizationStrings` VALUES (2054, 1, 'Smart Community Builder');
INSERT INTO `LocalizationStrings` VALUES (2055, 1, 'Dolphin has put on its Christmas cap and<br />wishes everyone a Merry Christmas!<br />Have a great holiday with your loved ones!');
INSERT INTO `LocalizationStrings` VALUES (2056, 1, 'Not a member yet?');
INSERT INTO `LocalizationStrings` VALUES (2057, 1, 'CLICK HERE');
INSERT INTO `LocalizationStrings` VALUES (2058, 1, 'more profiles');
INSERT INTO `LocalizationStrings` VALUES (2059, 1, '<b>Remember ME</b>');
INSERT INTO `LocalizationStrings` VALUES (2060, 1, 'Title should be {0} characters minimum ');
INSERT INTO `LocalizationStrings` VALUES (2061, 1, 'You have reached the maximum allowable number files to upload');
INSERT INTO `LocalizationStrings` VALUES (2062, 1, 'photo');
INSERT INTO `LocalizationStrings` VALUES (2063, 1, 'video');
INSERT INTO `LocalizationStrings` VALUES (2064, 1, 'audio');
INSERT INTO `LocalizationStrings` VALUES (2065, 1, 'add new {0}');
INSERT INTO `LocalizationStrings` VALUES (2066, 1, 'there is no photo that you can rate');
INSERT INTO `LocalizationStrings` VALUES (2067, 1, 'ratio');
INSERT INTO `LocalizationStrings` VALUES (2068, 1, 'My Pages');
INSERT INTO `LocalizationStrings` VALUES (2069, 1, 'That is MY COMMUNITY!');
INSERT INTO `LocalizationStrings` VALUES (2070, 1, 'download');
INSERT INTO `LocalizationStrings` VALUES (2071, 1, 'UPLOAD MEDIA');
INSERT INTO `LocalizationStrings` VALUES (2072, 1, 'delete {0}');
INSERT INTO `LocalizationStrings` VALUES (2073, 1, 'make primary');
INSERT INTO `LocalizationStrings` VALUES (2074, 1, 'Get Media');
INSERT INTO `LocalizationStrings` VALUES (2075, 1, 'profile media gallery');
INSERT INTO `LocalizationStrings` VALUES (2076, 1, 'Non-member');
INSERT INTO `LocalizationStrings` VALUES (2077, 1, 'Standard');
INSERT INTO `LocalizationStrings` VALUES (2078, 1, 'Promotion');
INSERT INTO `LocalizationStrings` VALUES (2079, 1, 'Your age doesn''t allow access to this site');
INSERT INTO `LocalizationStrings` VALUES (2081, 1, 'Showing results: <b>{0}</b> - <b>{1}</b> of <b>{2}</b>');
INSERT INTO `LocalizationStrings` VALUES (2082, 1, '{0} groups');
INSERT INTO `LocalizationStrings` VALUES (2083, 1, 'Groups');
INSERT INTO `LocalizationStrings` VALUES (2084, 1, 'My Groups');
INSERT INTO `LocalizationStrings` VALUES (2085, 1, 'Group is not found');
INSERT INTO `LocalizationStrings` VALUES (2086, 1, 'Group is not found by ID');
INSERT INTO `LocalizationStrings` VALUES (2087, 1, 'Group is hidden');
INSERT INTO `LocalizationStrings` VALUES (2088, 1, '<div align="center">Sorry, the group is hidden. To make it available you must be invited by the creator or member of the group.</div>');
INSERT INTO `LocalizationStrings` VALUES (2089, 1, 'Category');
INSERT INTO `LocalizationStrings` VALUES (2090, 1, 'Founded');
INSERT INTO `LocalizationStrings` VALUES (2091, 1, 'Members');
INSERT INTO `LocalizationStrings` VALUES (2092, 1, 'Group Creator');
INSERT INTO `LocalizationStrings` VALUES (2093, 1, 'Group title');
INSERT INTO `LocalizationStrings` VALUES (2094, 1, 'Group type');
INSERT INTO `LocalizationStrings` VALUES (2095, 1, 'Public group');
INSERT INTO `LocalizationStrings` VALUES (2096, 1, 'Private group');
INSERT INTO `LocalizationStrings` VALUES (2097, 1, 'Group members');
INSERT INTO `LocalizationStrings` VALUES (2098, 1, 'View all members');
INSERT INTO `LocalizationStrings` VALUES (2099, 1, 'Edit members');
INSERT INTO `LocalizationStrings` VALUES (2100, 1, 'Invite others');
INSERT INTO `LocalizationStrings` VALUES (2101, 1, 'Upload image');
INSERT INTO `LocalizationStrings` VALUES (2102, 1, 'Post topic');
INSERT INTO `LocalizationStrings` VALUES (2103, 1, 'Edit group');
INSERT INTO `LocalizationStrings` VALUES (2104, 1, 'Resign group');
INSERT INTO `LocalizationStrings` VALUES (2105, 1, 'Join group');
INSERT INTO `LocalizationStrings` VALUES (2106, 1, 'Are you sure you want to resign from the group?');
INSERT INTO `LocalizationStrings` VALUES (2107, 1, 'Are you sure you want to join the group?');
INSERT INTO `LocalizationStrings` VALUES (2108, 1, 'Create Group');
INSERT INTO `LocalizationStrings` VALUES (2109, 1, 'The group has been successfully created! Now you can upload a default group image or <a href="{0}">go to group home</a>.');
INSERT INTO `LocalizationStrings` VALUES (2110, 1, 'Error occurred while creating the group');
INSERT INTO `LocalizationStrings` VALUES (2111, 1, 'Edit Group');
INSERT INTO `LocalizationStrings` VALUES (2112, 1, 'You''re not the creator');
INSERT INTO `LocalizationStrings` VALUES (2113, 1, 'Groups Home');
INSERT INTO `LocalizationStrings` VALUES (2114, 1, 'Groups categories');
INSERT INTO `LocalizationStrings` VALUES (2115, 1, 'Keyword');
INSERT INTO `LocalizationStrings` VALUES (2116, 1, 'Advanced search');
INSERT INTO `LocalizationStrings` VALUES (2117, 1, 'Groups gallery');
INSERT INTO `LocalizationStrings` VALUES (2118, 1, 'You cannot view gallery since you''re not a group member');
INSERT INTO `LocalizationStrings` VALUES (2119, 1, 'Uploaded by');
INSERT INTO `LocalizationStrings` VALUES (2120, 1, 'Set as thumbnail');
INSERT INTO `LocalizationStrings` VALUES (2121, 1, 'Are you sure you want to delete this image?');
INSERT INTO `LocalizationStrings` VALUES (2122, 1, 'Delete image');
INSERT INTO `LocalizationStrings` VALUES (2123, 1, 'You cannot view group members since you''re not a group member');
INSERT INTO `LocalizationStrings` VALUES (2124, 1, 'Group Creator');
INSERT INTO `LocalizationStrings` VALUES (2125, 1, 'Are you sure you want to delete this member?');
INSERT INTO `LocalizationStrings` VALUES (2126, 1, 'Delete member');
INSERT INTO `LocalizationStrings` VALUES (2127, 1, 'Search Groups');
INSERT INTO `LocalizationStrings` VALUES (2128, 1, 'Search by');
INSERT INTO `LocalizationStrings` VALUES (2129, 1, 'group name');
INSERT INTO `LocalizationStrings` VALUES (2130, 1, 'keyword');
INSERT INTO `LocalizationStrings` VALUES (2131, 1, '- Any -');
INSERT INTO `LocalizationStrings` VALUES (2132, 1, 'Sort by');
INSERT INTO `LocalizationStrings` VALUES (2133, 1, 'popular');
INSERT INTO `LocalizationStrings` VALUES (2134, 1, 'newest');
INSERT INTO `LocalizationStrings` VALUES (2135, 1, '<div align="center">Sorry, no groups are found</div>');
INSERT INTO `LocalizationStrings` VALUES (2136, 1, 'Groups search results');
INSERT INTO `LocalizationStrings` VALUES (2137, 1, '<div align="center">There are no groups</div>');
INSERT INTO `LocalizationStrings` VALUES (2138, 1, 'Choose');
INSERT INTO `LocalizationStrings` VALUES (2139, 1, 'Open join');
INSERT INTO `LocalizationStrings` VALUES (2140, 1, 'Hidden group');
INSERT INTO `LocalizationStrings` VALUES (2141, 1, 'Members can post images');
INSERT INTO `LocalizationStrings` VALUES (2142, 1, 'Members can invite');
INSERT INTO `LocalizationStrings` VALUES (2143, 1, 'Group description');
INSERT INTO `LocalizationStrings` VALUES (2144, 1, 'Group name already exists');
INSERT INTO `LocalizationStrings` VALUES (2145, 1, 'Name is required');
INSERT INTO `LocalizationStrings` VALUES (2146, 1, 'Category is required');
INSERT INTO `LocalizationStrings` VALUES (2147, 1, 'Country is required');
INSERT INTO `LocalizationStrings` VALUES (2148, 1, 'City is required');
INSERT INTO `LocalizationStrings` VALUES (2149, 1, 'Group title is required');
INSERT INTO `LocalizationStrings` VALUES (2150, 1, 'Country doesn''t exist');
INSERT INTO `LocalizationStrings` VALUES (2151, 1, 'Category doesn''t exist');
INSERT INTO `LocalizationStrings` VALUES (2152, 1, 'Select file');
INSERT INTO `LocalizationStrings` VALUES (2153, 1, 'Group action');
INSERT INTO `LocalizationStrings` VALUES (2154, 1, 'Error occurred while uploading image to group gallery');
INSERT INTO `LocalizationStrings` VALUES (2155, 1, 'You should specify file');
INSERT INTO `LocalizationStrings` VALUES (2156, 1, 'Upload image to group gallery');
INSERT INTO `LocalizationStrings` VALUES (2157, 1, 'Image has been successfully uploaded!');
INSERT INTO `LocalizationStrings` VALUES (2158, 1, 'You should select correct image file');
INSERT INTO `LocalizationStrings` VALUES (2159, 1, 'Upload error');
INSERT INTO `LocalizationStrings` VALUES (2160, 1, 'You must choose a file with .jpeg, .gif, .png extensions.');
INSERT INTO `LocalizationStrings` VALUES (2161, 1, 'You cannot upload images because members of this group are not allowed to upload images');
INSERT INTO `LocalizationStrings` VALUES (2162, 1, 'You cannot upload images because you''re not a group member');
INSERT INTO `LocalizationStrings` VALUES (2163, 1, 'Error occurred while joining the group');
INSERT INTO `LocalizationStrings` VALUES (2164, 1, 'You''re already in this group');
INSERT INTO `LocalizationStrings` VALUES (2165, 1, 'Group join');
INSERT INTO `LocalizationStrings` VALUES (2166, 1, 'Congratulations. You''re a group member now.');
INSERT INTO `LocalizationStrings` VALUES (2167, 1, 'Request has been sent to the group creator. You will become an active group member after approval.');
INSERT INTO `LocalizationStrings` VALUES (2168, 1, 'Error occurred while resigning from the group');
INSERT INTO `LocalizationStrings` VALUES (2169, 1, 'You cannot resign from the group because you''re the creator');
INSERT INTO `LocalizationStrings` VALUES (2170, 1, 'Group resign');
INSERT INTO `LocalizationStrings` VALUES (2171, 1, 'You successfully resigned from the group');
INSERT INTO `LocalizationStrings` VALUES (2172, 1, 'You cannot resign from the group because you''re not a group member');
INSERT INTO `LocalizationStrings` VALUES (2173, 1, 'Group thumbnail set');
INSERT INTO `LocalizationStrings` VALUES (2174, 1, 'You cannot set the group thumbnail because you are not a group creator');
INSERT INTO `LocalizationStrings` VALUES (2175, 1, 'Group image delete');
INSERT INTO `LocalizationStrings` VALUES (2176, 1, 'You cannot delete the image because you are not the group creator');
INSERT INTO `LocalizationStrings` VALUES (2177, 1, 'Error occurred while deleting the group member');
INSERT INTO `LocalizationStrings` VALUES (2178, 1, 'You cannot delete yourself from the group because you are the group creator');
INSERT INTO `LocalizationStrings` VALUES (2179, 1, 'You cannot delete the group member because you are not the group creator');
INSERT INTO `LocalizationStrings` VALUES (2180, 1, 'Group member approved');
INSERT INTO `LocalizationStrings` VALUES (2181, 1, 'Member has been successfully approved');
INSERT INTO `LocalizationStrings` VALUES (2182, 1, 'Error occurred while approving a member');
INSERT INTO `LocalizationStrings` VALUES (2183, 1, 'An error occurred. The user might have resigned from the group prior to obtaining your approval.');
INSERT INTO `LocalizationStrings` VALUES (2184, 1, 'You cannot approve the group member because you are not the group creator');
INSERT INTO `LocalizationStrings` VALUES (2185, 1, 'Group member rejected');
INSERT INTO `LocalizationStrings` VALUES (2186, 1, 'Member has been rejected');
INSERT INTO `LocalizationStrings` VALUES (2187, 1, 'Error occurred while rejecting a member');
INSERT INTO `LocalizationStrings` VALUES (2188, 1, 'You cannot reject the group member because you are not the group creator');
INSERT INTO `LocalizationStrings` VALUES (2189, 1, 'Group action error');
INSERT INTO `LocalizationStrings` VALUES (2190, 1, 'Unknown group action');
INSERT INTO `LocalizationStrings` VALUES (2191, 1, 'Group name');
INSERT INTO `LocalizationStrings` VALUES (2192, 1, 'Please select at least one search parameter');
INSERT INTO `LocalizationStrings` VALUES (2193, 1, 'Choose members you want to send an invitation to');
INSERT INTO `LocalizationStrings` VALUES (2194, 1, '<div align="center">There are no members in this group</div>');
INSERT INTO `LocalizationStrings` VALUES (2195, 1, 'Go to {0} group');
INSERT INTO `LocalizationStrings` VALUES (2197, 1, 'Groups help');
INSERT INTO `LocalizationStrings` VALUES (2198, 1, '<b>Open Join</b><br />\r\n<b>Yes</b> - you can choose "yes" if you want users to join your group without your approval.<br />\r\n<b>No</b> - you can choose "no" if you want users to join your group only after your approval.');
INSERT INTO `LocalizationStrings` VALUES (2199, 1, '<b>Hidden Group</b><br />\r\n<b>Yes</b> - you can choose &quot;yes&quot; if you want your group unavailable for viewing. You should invite the members before they can see your group.<br />\r\n<b>No</b> - you can choose &quot;no&quot; if you want any member can see your group whether he/she is a group member or not.');
INSERT INTO `LocalizationStrings` VALUES (2200, 1, 'close window');
INSERT INTO `LocalizationStrings` VALUES (2201, 1, '<b>Members can invite</b><br />\r\n<b>Yes</b> - if you choose &quot;yes&quot; you allow your group''s members to invite other members without your approval.<br />\r\n<b>No</b> - if you choose &quot;no&quot; you will be the only person who can invite others to your group.');
INSERT INTO `LocalizationStrings` VALUES (2202, 1, '<b>Members can post images</b><br />\r\n<b>Yes</b> - if you choose &quot;yes&quot; you allow members to post images.<br />\r\n<b>No</b> - if you choose &quot;no&quot; only you, the creator, can post images.');
INSERT INTO `LocalizationStrings` VALUES (2203, 1, '<b>Public group</b><br />\r\nYou can view this group and easily join it');
INSERT INTO `LocalizationStrings` VALUES (2204, 1, '<b>Private group</b><br />\r\nYou can view the group but to become a group member you need to be approved by the creator');
INSERT INTO `LocalizationStrings` VALUES (2205, 1, '<b>Private group</b><br />\r\nTo view this group you must be invited by the group creator or a member of this group');
INSERT INTO `LocalizationStrings` VALUES (2206, 1, 'Group invite');
INSERT INTO `LocalizationStrings` VALUES (2207, 1, 'Your friends');
INSERT INTO `LocalizationStrings` VALUES (2208, 1, 'Invite list');
INSERT INTO `LocalizationStrings` VALUES (2209, 1, 'Add ->');
INSERT INTO `LocalizationStrings` VALUES (2210, 1, '<- Remove');
INSERT INTO `LocalizationStrings` VALUES (2211, 1, 'Find more...');
INSERT INTO `LocalizationStrings` VALUES (2212, 1, 'Send invites');
INSERT INTO `LocalizationStrings` VALUES (2213, 1, 'Invites succesfully sent');
INSERT INTO `LocalizationStrings` VALUES (2214, 1, 'You should specify at least one member');
INSERT INTO `LocalizationStrings` VALUES (2215, 1, 'Group invite accepted');
INSERT INTO `LocalizationStrings` VALUES (2216, 1, 'You successfully accepted the group invitation. Now you''re an active member of this group.');
INSERT INTO `LocalizationStrings` VALUES (2217, 1, 'Group invite accept error');
INSERT INTO `LocalizationStrings` VALUES (2218, 1, 'You cannot accept group invite');
INSERT INTO `LocalizationStrings` VALUES (2219, 1, 'Group invite reject');
INSERT INTO `LocalizationStrings` VALUES (2220, 1, 'You succesfully rejected the group invitation');
INSERT INTO `LocalizationStrings` VALUES (2221, 1, 'Quick Search Members');
INSERT INTO `LocalizationStrings` VALUES (2222, 1, 'Enter search parameters');
INSERT INTO `LocalizationStrings` VALUES (2225, 1, 'Quick search results');
INSERT INTO `LocalizationStrings` VALUES (2224, 1, 'Enter member NickName or ID');
INSERT INTO `LocalizationStrings` VALUES (2226, 1, 'Add member');
INSERT INTO `LocalizationStrings` VALUES (2227, 1, 'Post a new topic');
INSERT INTO `LocalizationStrings` VALUES (2228, 1, 'Group forum');
INSERT INTO `LocalizationStrings` VALUES (2229, 1, 'View all topics');
INSERT INTO `LocalizationStrings` VALUES (2230, 1, 'Hello, <b>{0}</b>!');
INSERT INTO `LocalizationStrings` VALUES (2231, 1, 'Top');
INSERT INTO `LocalizationStrings` VALUES (2232, 1, 'More photos');
INSERT INTO `LocalizationStrings` VALUES (2233, 1, 'My account');
INSERT INTO `LocalizationStrings` VALUES (2234, 1, 'Submitted by {0}');
INSERT INTO `LocalizationStrings` VALUES (2235, 1, 'Members');
INSERT INTO `LocalizationStrings` VALUES (2236, 1, 'News');
INSERT INTO `LocalizationStrings` VALUES (2237, 1, 'Next page');
INSERT INTO `LocalizationStrings` VALUES (2238, 1, 'Previous page');
INSERT INTO `LocalizationStrings` VALUES (2239, 1, 'Group is suspended');
INSERT INTO `LocalizationStrings` VALUES (2240, 1, 'Sorry, group is unavailable because it was suspended by site admin');
INSERT INTO `LocalizationStrings` VALUES (2241, 1, 'Status');
INSERT INTO `LocalizationStrings` VALUES (2242, 1, '<b>Suspended group</b><br />\r\nThe administrator of the site has suspended your group for some reason.<br />\r\nThis means that members will not see your group until the administrator activates it.');
INSERT INTO `LocalizationStrings` VALUES (2243, 1, '{0} profiles');
INSERT INTO `LocalizationStrings` VALUES (2244, 1, 'Tags');
INSERT INTO `LocalizationStrings` VALUES (2245, 1, 'You must be an active member to create groups');
INSERT INTO `LocalizationStrings` VALUES (2246, 1, 'More Tags');
INSERT INTO `LocalizationStrings` VALUES (2247, 1, 'Please');
INSERT INTO `LocalizationStrings` VALUES (2248, 1, 'No blogs available');
INSERT INTO `LocalizationStrings` VALUES (2249, 1, 'Blogs');
INSERT INTO `LocalizationStrings` VALUES (2250, 1, 'Author: <b><a href="{0}">{0}</a></b>');
INSERT INTO `LocalizationStrings` VALUES (2251, 1, '<img src="{0}" alt="" /><a href="{1}">{2}</a>');
INSERT INTO `LocalizationStrings` VALUES (2252, 1, '<img src="{0}" />{1} comments');
INSERT INTO `LocalizationStrings` VALUES (2253, 1, 'More blogs');
INSERT INTO `LocalizationStrings` VALUES (2254, 1, 'Videos');
INSERT INTO `LocalizationStrings` VALUES (2255, 1, 'Forums');
INSERT INTO `LocalizationStrings` VALUES (2256, 1, '{0} time(s)');
INSERT INTO `LocalizationStrings` VALUES (2257, 1, 'My Account');
INSERT INTO `LocalizationStrings` VALUES (2258, 1, 'My Mail');
INSERT INTO `LocalizationStrings` VALUES (2259, 1, 'Inbox');
INSERT INTO `LocalizationStrings` VALUES (2260, 1, 'Sent');
INSERT INTO `LocalizationStrings` VALUES (2261, 1, 'Write');
INSERT INTO `LocalizationStrings` VALUES (2262, 1, 'I Blocked');
INSERT INTO `LocalizationStrings` VALUES (2263, 1, 'Blocked Me');
INSERT INTO `LocalizationStrings` VALUES (2264, 1, 'Browse My Photos');
INSERT INTO `LocalizationStrings` VALUES (2265, 1, 'Upload Photo');
INSERT INTO `LocalizationStrings` VALUES (2266, 1, 'My Video Gallery');
INSERT INTO `LocalizationStrings` VALUES (2267, 1, 'My Audio');
INSERT INTO `LocalizationStrings` VALUES (2268, 1, 'My Events');
INSERT INTO `LocalizationStrings` VALUES (2269, 1, 'My Blog');
INSERT INTO `LocalizationStrings` VALUES (2270, 1, 'My Polls');
INSERT INTO `LocalizationStrings` VALUES (2271, 1, 'My Guestbook');
INSERT INTO `LocalizationStrings` VALUES (2272, 1, 'My Greetings');
INSERT INTO `LocalizationStrings` VALUES (2273, 1, 'My Faves');
INSERT INTO `LocalizationStrings` VALUES (2274, 1, 'My Friends');
INSERT INTO `LocalizationStrings` VALUES (2275, 1, 'My Views');
INSERT INTO `LocalizationStrings` VALUES (2276, 1, 'Who''s Online');
INSERT INTO `LocalizationStrings` VALUES (2277, 1, 'My Albums');
INSERT INTO `LocalizationStrings` VALUES (2278, 1, 'Browse My Videos');
INSERT INTO `LocalizationStrings` VALUES (2279, 1, 'Browse My Audio');
INSERT INTO `LocalizationStrings` VALUES (2280, 1, 'Upload Audio');
INSERT INTO `LocalizationStrings` VALUES (2281, 1, 'Photos');
INSERT INTO `LocalizationStrings` VALUES (2282, 1, 'Audio');
INSERT INTO `LocalizationStrings` VALUES (2283, 1, 'Albums');
INSERT INTO `LocalizationStrings` VALUES (2284, 1, 'Browse My Groups');
INSERT INTO `LocalizationStrings` VALUES (2285, 1, 'Browse All Groups');
INSERT INTO `LocalizationStrings` VALUES (2286, 1, 'View My Blog');
INSERT INTO `LocalizationStrings` VALUES (2287, 1, 'Add Category');
INSERT INTO `LocalizationStrings` VALUES (2288, 1, 'New Post');
INSERT INTO `LocalizationStrings` VALUES (2289, 1, 'View My Guestbook');
INSERT INTO `LocalizationStrings` VALUES (2290, 1, 'Add Post');
INSERT INTO `LocalizationStrings` VALUES (2291, 1, 'Browse My Albums');
INSERT INTO `LocalizationStrings` VALUES (2292, 1, 'Add Album');
INSERT INTO `LocalizationStrings` VALUES (2293, 1, 'I Greeted');
INSERT INTO `LocalizationStrings` VALUES (2294, 1, 'Greeted Me');
INSERT INTO `LocalizationStrings` VALUES (2295, 1, 'Faved Me');
INSERT INTO `LocalizationStrings` VALUES (2296, 1, 'I Invited');
INSERT INTO `LocalizationStrings` VALUES (2297, 1, 'Invited Me');
INSERT INTO `LocalizationStrings` VALUES (2298, 1, 'I Viewed');
INSERT INTO `LocalizationStrings` VALUES (2299, 1, 'Viewed Me');
INSERT INTO `LocalizationStrings` VALUES (2300, 1, 'Send Message');
INSERT INTO `LocalizationStrings` VALUES (2301, 1, 'Add To Faves');
INSERT INTO `LocalizationStrings` VALUES (2302, 1, 'Invites To Friends');
INSERT INTO `LocalizationStrings` VALUES (2303, 1, 'Send A Greeting');
INSERT INTO `LocalizationStrings` VALUES (2304, 1, 'Get E-mail');
INSERT INTO `LocalizationStrings` VALUES (2305, 1, 'Block Profile');
INSERT INTO `LocalizationStrings` VALUES (2306, 1, 'Report Profile');
INSERT INTO `LocalizationStrings` VALUES (2307, 1, 'Send To Friend');
INSERT INTO `LocalizationStrings` VALUES (2308, 1, 'Actions');
INSERT INTO `LocalizationStrings` VALUES (2309, 1, 'Browse My Events');
INSERT INTO `LocalizationStrings` VALUES (2310, 1, 'Create New Event');
INSERT INTO `LocalizationStrings` VALUES (2311, 1, 'Browse Events');
INSERT INTO `LocalizationStrings` VALUES (2312, 1, 'Events Calendar');
INSERT INTO `LocalizationStrings` VALUES (2313, 1, 'Members Polls');
INSERT INTO `LocalizationStrings` VALUES (2331, 1, 'Site Polls');
INSERT INTO `LocalizationStrings` VALUES (2315, 1, 'Members Polls');
INSERT INTO `LocalizationStrings` VALUES (2316, 1, 'Members Polls');
INSERT INTO `LocalizationStrings` VALUES (2317, 1, 'Member Poll');
INSERT INTO `LocalizationStrings` VALUES (2318, 1, 'Member Poll');
INSERT INTO `LocalizationStrings` VALUES (2319, 1, 'Average rating');
INSERT INTO `LocalizationStrings` VALUES (2320, 1, 'Your rating');
INSERT INTO `LocalizationStrings` VALUES (2321, 1, 'Total votes');
INSERT INTO `LocalizationStrings` VALUES (2322, 1, 'Previously rated');
INSERT INTO `LocalizationStrings` VALUES (2323, 1, 'Recent Videos');
INSERT INTO `LocalizationStrings` VALUES (2324, 1, 'Top Photos');
INSERT INTO `LocalizationStrings` VALUES (2325, 1, 'Recent Photos');
INSERT INTO `LocalizationStrings` VALUES (2326, 1, 'My Contacts');
INSERT INTO `LocalizationStrings` VALUES (2327, 1, 'Couples');
INSERT INTO `LocalizationStrings` VALUES (2328, 1, 'Poll not available');
INSERT INTO `LocalizationStrings` VALUES (2329, 1, 'Flag');
INSERT INTO `LocalizationStrings` VALUES (2330, 1, 'Click to sort');
INSERT INTO `LocalizationStrings` VALUES (2332, 1, 'Simple Search');
INSERT INTO `LocalizationStrings` VALUES (2333, 1, 'Advanced Search');
INSERT INTO `LocalizationStrings` VALUES (2334, 1, 'Site Poll');
INSERT INTO `LocalizationStrings` VALUES (2335, 1, 'Top Groups\r\n');
INSERT INTO `LocalizationStrings` VALUES (2336, 1, 'All Blogs\r\n');
INSERT INTO `LocalizationStrings` VALUES (2337, 1, 'No members found here');
INSERT INTO `LocalizationStrings` VALUES (2338, 1, 'You must <a href="{0}">Create a Category</a> before making a new post to Blogs');
INSERT INTO `LocalizationStrings` VALUES (2339, 1, 'No profile tags found');
INSERT INTO `LocalizationStrings` VALUES (2340, 1, 'Bookmark');
INSERT INTO `LocalizationStrings` VALUES (2341, 1, 'or');
INSERT INTO `LocalizationStrings` VALUES (2342, 1, 'Classifieds');
INSERT INTO `LocalizationStrings` VALUES (2343, 1, 'Recently Posted');
INSERT INTO `LocalizationStrings` VALUES (2344, 1, 'Events');
INSERT INTO `LocalizationStrings` VALUES (2345, 1, 'Feedback');
INSERT INTO `LocalizationStrings` VALUES (2346, 1, 'Contact us');
INSERT INTO `LocalizationStrings` VALUES (2347, 1, 'Sorry, you''ve already joined');
INSERT INTO `LocalizationStrings` VALUES (2348, 1, 'Profile details');
INSERT INTO `LocalizationStrings` VALUES (2349, 1, 'Age');
INSERT INTO `LocalizationStrings` VALUES (2350, 1, 'answer');
INSERT INTO `LocalizationStrings` VALUES (2351, 1, 'Member photos');
INSERT INTO `LocalizationStrings` VALUES (2352, 1, 'To The Community');
INSERT INTO `LocalizationStrings` VALUES (2353, 1, 'Classifieds');
INSERT INTO `LocalizationStrings` VALUES (2354, 1, 'Classifieds');
INSERT INTO `LocalizationStrings` VALUES (2355, 1, 'Classifieds Advertisements field');
INSERT INTO `LocalizationStrings` VALUES (2356, 1, 'Advanced Search');
INSERT INTO `LocalizationStrings` VALUES (2357, 1, 'Browse All Ads');
INSERT INTO `LocalizationStrings` VALUES (2358, 1, 'My Classifieds');
INSERT INTO `LocalizationStrings` VALUES (2359, 1, 'Browse My Ads');
INSERT INTO `LocalizationStrings` VALUES (2360, 1, 'Post New Advertisement');
INSERT INTO `LocalizationStrings` VALUES (2361, 1, 'Browse All Members');
INSERT INTO `LocalizationStrings` VALUES (2362, 1, 'Categories');
INSERT INTO `LocalizationStrings` VALUES (2363, 1, 'Keywords');
INSERT INTO `LocalizationStrings` VALUES (2364, 1, 'Posted by');
INSERT INTO `LocalizationStrings` VALUES (2365, 1, 'Details');
INSERT INTO `LocalizationStrings` VALUES (2366, 1, 'Admin Local Area');
INSERT INTO `LocalizationStrings` VALUES (2367, 1, 'My Advertisements');
INSERT INTO `LocalizationStrings` VALUES (2368, 1, 'Life Time');
INSERT INTO `LocalizationStrings` VALUES (2369, 1, 'Message');
INSERT INTO `LocalizationStrings` VALUES (2370, 1, 'Pictures');
INSERT INTO `LocalizationStrings` VALUES (2371, 1, 'Send these files');
INSERT INTO `LocalizationStrings` VALUES (2372, 1, 'Add more pics');
INSERT INTO `LocalizationStrings` VALUES (2373, 1, 'Filtered');
INSERT INTO `LocalizationStrings` VALUES (2374, 1, 'Listing');
INSERT INTO `LocalizationStrings` VALUES (2375, 1, 'Out');
INSERT INTO `LocalizationStrings` VALUES (2376, 1, 'of');
INSERT INTO `LocalizationStrings` VALUES (2377, 1, 'SubCategories');
INSERT INTO `LocalizationStrings` VALUES (2378, 1, 'Moderating (new messages)');
INSERT INTO `LocalizationStrings` VALUES (2379, 1, 'Add');
INSERT INTO `LocalizationStrings` VALUES (2380, 1, 'Add this');
INSERT INTO `LocalizationStrings` VALUES (2381, 1, 'Desctiption');
INSERT INTO `LocalizationStrings` VALUES (2382, 1, 'CustomField1');
INSERT INTO `LocalizationStrings` VALUES (2383, 1, 'CustomField2');
INSERT INTO `LocalizationStrings` VALUES (2384, 1, 'Apply');
INSERT INTO `LocalizationStrings` VALUES (2385, 1, 'Activate');
INSERT INTO `LocalizationStrings` VALUES (2386, 1, 'Entity');
INSERT INTO `LocalizationStrings` VALUES (2387, 1, 'Back');
INSERT INTO `LocalizationStrings` VALUES (2388, 1, 'Tree Classifieds Browse');
INSERT INTO `LocalizationStrings` VALUES (2389, 1, 'Equal');
INSERT INTO `LocalizationStrings` VALUES (2390, 1, 'Max');
INSERT INTO `LocalizationStrings` VALUES (2391, 1, 'Min');
INSERT INTO `LocalizationStrings` VALUES (2392, 1, 'Could not successfully run query {0} from DB: {1}');
INSERT INTO `LocalizationStrings` VALUES (2393, 1, ' Your ad will be active for {0} days');
INSERT INTO `LocalizationStrings` VALUES (2394, 1, 'File: {0} very large to upload.<br>');
INSERT INTO `LocalizationStrings` VALUES (2395, 1, 'Advertisement successfully added');
INSERT INTO `LocalizationStrings` VALUES (2396, 1, 'Failed to Insert Advertisement');
INSERT INTO `LocalizationStrings` VALUES (2397, 1, 'Advertisement successfully deleted');
INSERT INTO `LocalizationStrings` VALUES (2398, 1, '_Failed to Delete Advertisement');
INSERT INTO `LocalizationStrings` VALUES (2399, 1, 'Tree Classifieds Browse');
INSERT INTO `LocalizationStrings` VALUES (2400, 1, 'Moderating (new messages)');
INSERT INTO `LocalizationStrings` VALUES (2401, 1, 'Advertisement successfully activated');
INSERT INTO `LocalizationStrings` VALUES (2402, 1, 'Failed to Activate Advertisement');
INSERT INTO `LocalizationStrings` VALUES (2403, 1, 'Advertisement successfully updated');
INSERT INTO `LocalizationStrings` VALUES (2404, 1, 'Failed to Update Advertisement');
INSERT INTO `LocalizationStrings` VALUES (2405, 1, 'Filter');
INSERT INTO `LocalizationStrings` VALUES (2406, 1, 'choose');
INSERT INTO `LocalizationStrings` VALUES (2407, 1, 'Are you sure');
INSERT INTO `LocalizationStrings` VALUES (2408, 1, 'Apply Changes');
INSERT INTO `LocalizationStrings` VALUES (2409, 1, 'Offer Details');
INSERT INTO `LocalizationStrings` VALUES (2410, 1, 'Congratulations! Your account has been successfully confirmed.<br /><br />It will be activated within 12 hours. Our administrators will personally look through your details to make sure you have set everything correctly. This helps {0} be the most accurate community service in the world. We care about the quality of our profiles and guarantee that every user of our system is real, so if you purchase someone''s contact information, you can be sure that your money isn''t wasted.');
INSERT INTO `LocalizationStrings` VALUES (2411, 1, 'Congratulations!<br /><br />Your account has been successfully confirmed and activated.<br />You can log into your account now.');
INSERT INTO `LocalizationStrings` VALUES (2412, 1, 'wholesale');
INSERT INTO `LocalizationStrings` VALUES (2413, 1, 'You have chosen the "Buy Now" option to purchase the item above. If you wish to proceed and make an immediate purchase of this item at the price listed below, please click the "Buy Now" button. This will close the auction allowing you and the seller to complete the transaction.');
INSERT INTO `LocalizationStrings` VALUES (2414, 1, 'Buy Now Amount Details:');
INSERT INTO `LocalizationStrings` VALUES (2415, 1, 'Your "Buy it Now" bid has been received. Please contact the seller to complete the transaction.');
INSERT INTO `LocalizationStrings` VALUES (2416, 1, 'Comment was successfully added');
INSERT INTO `LocalizationStrings` VALUES (2417, 1, 'Comment addition failed');
INSERT INTO `LocalizationStrings` VALUES (2418, 1, 'Leave your comment');
INSERT INTO `LocalizationStrings` VALUES (2419, 1, 'Post Comment');
INSERT INTO `LocalizationStrings` VALUES (2420, 1, 'Unit');
INSERT INTO `LocalizationStrings` VALUES (2421, 1, 'Users other listing');
INSERT INTO `LocalizationStrings` VALUES (2422, 1, 'Subject is required');
INSERT INTO `LocalizationStrings` VALUES (2423, 1, 'Message must be at least 50 symbols');
INSERT INTO `LocalizationStrings` VALUES (2424, 1, 'Manage classifieds');
INSERT INTO `LocalizationStrings` VALUES (2425, 1, 'Befriend');
INSERT INTO `LocalizationStrings` VALUES (2426, 1, 'Send Letter');
INSERT INTO `LocalizationStrings` VALUES (2427, 1, 'Fave');
INSERT INTO `LocalizationStrings` VALUES (2428, 1, 'Share');
INSERT INTO `LocalizationStrings` VALUES (2429, 1, 'Report');
INSERT INTO `LocalizationStrings` VALUES (2430, 1, 'seconds ago');
INSERT INTO `LocalizationStrings` VALUES (2431, 1, 'minutes ago');
INSERT INTO `LocalizationStrings` VALUES (2432, 1, 'hours ago');
INSERT INTO `LocalizationStrings` VALUES (2433, 1, 'days ago');
INSERT INTO `LocalizationStrings` VALUES (2434, 1, 'Info');
INSERT INTO `LocalizationStrings` VALUES (2435, 1, 'Profile Music');
INSERT INTO `LocalizationStrings` VALUES (2436, 1, 'Profile Videos');
INSERT INTO `LocalizationStrings` VALUES (2437, 1, 'Profile Photos');
INSERT INTO `LocalizationStrings` VALUES (2438, 1, 'Chat Now');
INSERT INTO `LocalizationStrings` VALUES (2439, 1, 'Greeting');
INSERT INTO `LocalizationStrings` VALUES (2440, 1, 'Advertisement');
INSERT INTO `LocalizationStrings` VALUES (2441, 1, 'Buy Now');
INSERT INTO `LocalizationStrings` VALUES (2442, 1, 'Account Home');
INSERT INTO `LocalizationStrings` VALUES (2443, 1, 'My Settings');
INSERT INTO `LocalizationStrings` VALUES (2444, 1, '_Members3');
INSERT INTO `LocalizationStrings` VALUES (2445, 1, 'Test');
INSERT INTO `LocalizationStrings` VALUES (2446, 1, 'All Members');
INSERT INTO `LocalizationStrings` VALUES (2447, 1, 'All Groups');
INSERT INTO `LocalizationStrings` VALUES (2448, 1, 'All Videos');
INSERT INTO `LocalizationStrings` VALUES (2449, 1, 'No video');
INSERT INTO `LocalizationStrings` VALUES (2465, 1, 'Browse Video');
INSERT INTO `LocalizationStrings` VALUES (2466, 1, 'File was added to favorite');
INSERT INTO `LocalizationStrings` VALUES (2467, 1, 'File already is a favorite');
INSERT INTO `LocalizationStrings` VALUES (2468, 1, 'Enter email(s)');
INSERT INTO `LocalizationStrings` VALUES (2469, 1, 'view Video');
INSERT INTO `LocalizationStrings` VALUES (2470, 1, 'See all videos of this user');
INSERT INTO `LocalizationStrings` VALUES (2471, 1, 'File title');
INSERT INTO `LocalizationStrings` VALUES (2472, 1, 'File tags');
INSERT INTO `LocalizationStrings` VALUES (2473, 1, 'Upload Files');
INSERT INTO `LocalizationStrings` VALUES (2474, 1, 'Page');
INSERT INTO `LocalizationStrings` VALUES (2475, 1, 'Music files');
INSERT INTO `LocalizationStrings` VALUES (2476, 1, 'Browse music files');
INSERT INTO `LocalizationStrings` VALUES (2477, 1, 'Playbacks');
INSERT INTO `LocalizationStrings` VALUES (2478, 1, 'Upload Photo');
INSERT INTO `LocalizationStrings` VALUES (2479, 1, 'Boards');
INSERT INTO `LocalizationStrings` VALUES (2480, 1, 'All Classifieds');
INSERT INTO `LocalizationStrings` VALUES (2481, 1, 'Add Classified');
INSERT INTO `LocalizationStrings` VALUES (2482, 1, 'Music');
INSERT INTO `LocalizationStrings` VALUES (2483, 1, 'All Music');
INSERT INTO `LocalizationStrings` VALUES (2484, 1, 'Upload Music');
INSERT INTO `LocalizationStrings` VALUES (2485, 1, 'All Photos');
INSERT INTO `LocalizationStrings` VALUES (2486, 1, 'Top Blogs');
INSERT INTO `LocalizationStrings` VALUES (2487, 1, 'All Events');
INSERT INTO `LocalizationStrings` VALUES (2488, 1, 'Add Event');
INSERT INTO `LocalizationStrings` VALUES (2489, 1, 'All Polls');
INSERT INTO `LocalizationStrings` VALUES (2490, 1, 'Profile Music');
INSERT INTO `LocalizationStrings` VALUES (2491, 1, 'Guestbook');
INSERT INTO `LocalizationStrings` VALUES (2492, 1, 'File description');
INSERT INTO `LocalizationStrings` VALUES (2493, 1, 'Upload Video');
INSERT INTO `LocalizationStrings` VALUES (2494, 1, 'Upload File');
INSERT INTO `LocalizationStrings` VALUES (2495, 1, 'Sorry, nothing found');
INSERT INTO `LocalizationStrings` VALUES (2496, 1, 'File was uploaded succesfully');
INSERT INTO `LocalizationStrings` VALUES (2497, 1, 'Added');
INSERT INTO `LocalizationStrings` VALUES (2498, 1, 'URL');
INSERT INTO `LocalizationStrings` VALUES (2499, 1, 'Embed');
INSERT INTO `LocalizationStrings` VALUES (2500, 1, 'Views');
INSERT INTO `LocalizationStrings` VALUES (2501, 1, 'Video Info');
INSERT INTO `LocalizationStrings` VALUES (2502, 1, 'Download');
INSERT INTO `LocalizationStrings` VALUES (2503, 1, 'File info was sent');
INSERT INTO `LocalizationStrings` VALUES (2504, 1, 'Latest files from this user');
INSERT INTO `LocalizationStrings` VALUES (2505, 1, 'View Comments');
INSERT INTO `LocalizationStrings` VALUES (2506, 1, 'Upload Music');
INSERT INTO `LocalizationStrings` VALUES (2507, 1, 'Browse Photo');
INSERT INTO `LocalizationStrings` VALUES (2508, 1, 'Upload failed');
INSERT INTO `LocalizationStrings` VALUES (2509, 1, 'Photo Info');
INSERT INTO `LocalizationStrings` VALUES (2510, 1, 'View Photo');
INSERT INTO `LocalizationStrings` VALUES (2511, 1, 'Music File Info');
INSERT INTO `LocalizationStrings` VALUES (2512, 1, 'View Music');
INSERT INTO `LocalizationStrings` VALUES (2513, 1, 'My Favorite Photos');
INSERT INTO `LocalizationStrings` VALUES (2514, 1, 'My Music Gallery');
INSERT INTO `LocalizationStrings` VALUES (2515, 1, 'Ray Chat');
INSERT INTO `LocalizationStrings` VALUES (2516, 1, 'Photo');
INSERT INTO `LocalizationStrings` VALUES (2517, 1, 'Resize succesful');
INSERT INTO `LocalizationStrings` VALUES (2518, 1, 'Make Primary');
INSERT INTO `LocalizationStrings` VALUES (2519, 1, 'See all photos of this user');
INSERT INTO `LocalizationStrings` VALUES (2520, 1, 'Untitled');
INSERT INTO `LocalizationStrings` VALUES (2521, 1, 'Original Size');
INSERT INTO `LocalizationStrings` VALUES (2522, 1, 'Rate');
INSERT INTO `LocalizationStrings` VALUES (2523, 1, 'Advertisement Photos');
INSERT INTO `LocalizationStrings` VALUES (2524, 1, 'Comments');
INSERT INTO `LocalizationStrings` VALUES (2525, 1, 'Users Other Listings');
INSERT INTO `LocalizationStrings` VALUES (2526, 1, 'Top Videos');
INSERT INTO `LocalizationStrings` VALUES (2527, 1, 'Top Music');
INSERT INTO `LocalizationStrings` VALUES (2528, 1, 'Profile Photos');
INSERT INTO `LocalizationStrings` VALUES (2529, 1, 'Profile Music');
INSERT INTO `LocalizationStrings` VALUES (2530, 1, 'Profile Video');
INSERT INTO `LocalizationStrings` VALUES (2531, 1, 'You have successfully joined this Event');
INSERT INTO `LocalizationStrings` VALUES (2532, 1, 'List');
INSERT INTO `LocalizationStrings` VALUES (2533, 1, 'Event');
INSERT INTO `LocalizationStrings` VALUES (2534, 1, 'Post Event');
INSERT INTO `LocalizationStrings` VALUES (2535, 1, 'By');
INSERT INTO `LocalizationStrings` VALUES (2536, 1, 'Please Wait');
INSERT INTO `LocalizationStrings` VALUES (2537, 1, 'Vote');
INSERT INTO `LocalizationStrings` VALUES (2538, 1, 'My Favorite Photos');
INSERT INTO `LocalizationStrings` VALUES (2539, 1, 'My Favorite Videos');
INSERT INTO `LocalizationStrings` VALUES (2540, 1, 'My Favorite Music');
INSERT INTO `LocalizationStrings` VALUES (2541, 1, 'Music Gallery');
INSERT INTO `LocalizationStrings` VALUES (2542, 1, 'Photos Gallery');
INSERT INTO `LocalizationStrings` VALUES (2543, 1, 'Video Gallery');
INSERT INTO `LocalizationStrings` VALUES (2544, 1, 'Post');
INSERT INTO `LocalizationStrings` VALUES (2545, 1, 'Caption');
INSERT INTO `LocalizationStrings` VALUES (2546, 1, 'Please, Create a Blog');
INSERT INTO `LocalizationStrings` VALUES (2547, 1, 'Create My Blog');
INSERT INTO `LocalizationStrings` VALUES (2548, 1, 'Create Blog');
INSERT INTO `LocalizationStrings` VALUES (2549, 1, 'Posts');
INSERT INTO `LocalizationStrings` VALUES (2554, 1, '{0} Photos');
INSERT INTO `LocalizationStrings` VALUES (2555, 1, 'Top Posts');
INSERT INTO `LocalizationStrings` VALUES (2564, 1, '{0} Info');
INSERT INTO `LocalizationStrings` VALUES (2568, 1, 'BoonEx News');
INSERT INTO `LocalizationStrings` VALUES (2569, 1, 'Visit Source');
INSERT INTO `LocalizationStrings` VALUES (2570, 1, 'post successfully deleted');
INSERT INTO `LocalizationStrings` VALUES (2571, 1, 'failed to delete post');
INSERT INTO `LocalizationStrings` VALUES (2572, 1, 'failed to add post');
INSERT INTO `LocalizationStrings` VALUES (2573, 1, 'post successfully added');
INSERT INTO `LocalizationStrings` VALUES (2574, 1, 'Leaders');
INSERT INTO `LocalizationStrings` VALUES (2575, 1, 'Day');
INSERT INTO `LocalizationStrings` VALUES (2576, 1, 'Month');
INSERT INTO `LocalizationStrings` VALUES (2577, 1, 'Week');
INSERT INTO `LocalizationStrings` VALUES (2578, 1, 'No rated profiles today');
INSERT INTO `LocalizationStrings` VALUES (2579, 1, 'This may be a hacker string');
INSERT INTO `LocalizationStrings` VALUES (2581, 1, 'Write a description for your Blog.');
INSERT INTO `LocalizationStrings` VALUES (2582, 1, 'Error Occured');
INSERT INTO `LocalizationStrings` VALUES (2584, 1, 'Forum Posts');
INSERT INTO `LocalizationStrings` VALUES (2585, 1, 'Create a <a href="{0}">BoonEx ID</a> for me');
INSERT INTO `LocalizationStrings` VALUES (2586, 1, 'Get BoonEx ID');
INSERT INTO `LocalizationStrings` VALUES (2587, 1, 'Import BoonEx ID');
INSERT INTO `LocalizationStrings` VALUES (2588, 1, 'Import');
INSERT INTO `LocalizationStrings` VALUES (2589, 1, 'Posted');
INSERT INTO `LocalizationStrings` VALUES (2590, 1, 'No articles available');
INSERT INTO `LocalizationStrings` VALUES (2591, 1, 'Read All Articles');
INSERT INTO `LocalizationStrings` VALUES (2592, 1, 'Shared Photos');
INSERT INTO `LocalizationStrings` VALUES (2593, 1, 'Shared Videos');
INSERT INTO `LocalizationStrings` VALUES (2594, 1, 'Shared Music Files');
INSERT INTO `LocalizationStrings` VALUES (2595, 1, 'This Week');
INSERT INTO `LocalizationStrings` VALUES (2596, 1, 'This Month');
INSERT INTO `LocalizationStrings` VALUES (2597, 1, 'This Year');
INSERT INTO `LocalizationStrings` VALUES (2598, 1, 'Topics');
INSERT INTO `LocalizationStrings` VALUES (2599, 1, 'No tags found here');
INSERT INTO `LocalizationStrings` VALUES (2600, 1, 'Ads');
INSERT INTO `LocalizationStrings` VALUES (2601, 1, 'New Today');
INSERT INTO `LocalizationStrings` VALUES (2602, 1, 'Photo Gallery');
INSERT INTO `LocalizationStrings` VALUES (2603, 1, 'No classifieds available');
INSERT INTO `LocalizationStrings` VALUES (2604, 1, 'No groups available');
INSERT INTO `LocalizationStrings` VALUES (2605, 1, 'My Music Gallery');
INSERT INTO `LocalizationStrings` VALUES (2606, 1, 'My Photo Gallery');
INSERT INTO `LocalizationStrings` VALUES (2607, 1, 'My Video Gallery');
INSERT INTO `LocalizationStrings` VALUES (2608, 1, 'Count');
INSERT INTO `LocalizationStrings` VALUES (2609, 1, 'Site Stats');
INSERT INTO `LocalizationStrings` VALUES (2610, 1, 'I agree');
INSERT INTO `LocalizationStrings` VALUES (2611, 1, '{0} Upload Agreement');
INSERT INTO `LocalizationStrings` VALUES (2612, 1, 'The terms of the Agreement in a nutshell:\r\n1. You have permission to upload the material, or you have obtained permission from the relevant rights holder(s).\r\n2. {0} may use your material for its content and you have the right to provide this material for free downloads.\r\n3. The list of PROHIBITED actions.\r\n\r\n1. LICENSED MATERIAL\r\nWhen uploading licensed material you confirm that you have the right or permission to upload it. You confirm that your material can be used by you and has not been stolen. You are only responsible for the uploaded material and, in case someone declares that the material has been stolen and will provide us with all the license documents, {0} has the right to remove your files and provide the material owner with your contact information. \r\n\r\n2. GRANTING OF LICENSE\r\n\r\nWhen uploading the material, you provide {0} and its members with the right to use it. You understand that our site is an open site, therefore you agree that the material uploaded by you can be downloaded and used by other site members. {0} isn&#8217;t responsible for the usage of your material on third party sites. \r\n\r\n3. STRONGLY PROHIBITED\r\n\r\n- Media files having negative or any other psychological or mental influence.\r\n- Media files containing children''s porno. \r\n- Media containing naked views of you or your children.\r\nIf you do not agree with these stipulations, you may not upload any media files.');
INSERT INTO `LocalizationStrings` VALUES (2613, 1, 'Event Deleted');
INSERT INTO `LocalizationStrings` VALUES (2614, 1, 'Tags');
INSERT INTO `LocalizationStrings` VALUES (2615, 1, 'Tags separated by spaces');
INSERT INTO `LocalizationStrings` VALUES (2616, 1, 'You must enter your Tags');
INSERT INTO `LocalizationStrings` VALUES (2617, 1, 'Member Friends');
INSERT INTO `LocalizationStrings` VALUES (2618, 1, 'Select');
INSERT INTO `LocalizationStrings` VALUES (2619, 1, 'Join Now');
INSERT INTO `LocalizationStrings` VALUES (2620, 1, 'Tag');
INSERT INTO `LocalizationStrings` VALUES (2621, 1, 'Sorry, no members found');
INSERT INTO `LocalizationStrings` VALUES (2622, 1, 'Sorry, you didn''t post any ads');
INSERT INTO `LocalizationStrings` VALUES (2623, 1, 'Password confirmation failed');
INSERT INTO `LocalizationStrings` VALUES (2624, 1, 'Change Password');
INSERT INTO `LocalizationStrings` VALUES (2625, 1, 'Blog Post successfully updated');
INSERT INTO `LocalizationStrings` VALUES (2626, 1, 'Failed to update Blog Post');
INSERT INTO `LocalizationStrings` VALUES (2627, 1, 'Your age doesn''t allow access to this site');
INSERT INTO `LocalizationStrings` VALUES (2628, 1, 'Requested File Doesn''t Exist');
INSERT INTO `LocalizationStrings` VALUES (2629, 1, 'Admin Panel');
INSERT INTO `LocalizationStrings` VALUES (2630, 1, 'File upload error');
INSERT INTO `LocalizationStrings` VALUES (2631, 1, 'send greetings');
INSERT INTO `LocalizationStrings` VALUES (2632, 1, 'AddMainCategory successfully added');
INSERT INTO `LocalizationStrings` VALUES (2633, 1, 'Failed to Insert AddMainCategory');
INSERT INTO `LocalizationStrings` VALUES (2634, 1, 'AddSubCategory successfully added');
INSERT INTO `LocalizationStrings` VALUES (2635, 1, 'Failed to Insert AddSubCategory');
INSERT INTO `LocalizationStrings` VALUES (2636, 1, 'DeleteMainCategory was successful');
INSERT INTO `LocalizationStrings` VALUES (2637, 1, 'Failed to DeleteMainCategory');
INSERT INTO `LocalizationStrings` VALUES (2638, 1, 'DeleteSubCategory was successful');
INSERT INTO `LocalizationStrings` VALUES (2639, 1, 'Failed to DeleteSubCategory');
INSERT INTO `LocalizationStrings` VALUES (2640, 1, 'Add New Article');
INSERT INTO `LocalizationStrings` VALUES (2641, 1, 'Category Caption');
INSERT INTO `LocalizationStrings` VALUES (2642, 1, 'Articles Deleted Successfully');
INSERT INTO `LocalizationStrings` VALUES (2643, 1, 'Articles are not deleted');
INSERT INTO `LocalizationStrings` VALUES (2644, 1, 'Category Deleted Successfully');
INSERT INTO `LocalizationStrings` VALUES (2645, 1, 'Category not deleted');
INSERT INTO `LocalizationStrings` VALUES (2646, 1, 'Hot or Not');
INSERT INTO `LocalizationStrings` VALUES (2647, 1, 'Affiliate system was disabled');
INSERT INTO `LocalizationStrings` VALUES(2648, 1, 'Description');
INSERT INTO `LocalizationStrings` VALUES(2649, 1, 'Mutual Friends');
INSERT INTO `LocalizationStrings` VALUES(2650, 1, 'Photo Actions');
INSERT INTO `LocalizationStrings` VALUES(2651, 1, 'Notification');
INSERT INTO `LocalizationStrings` VALUES(2652, 1, 'You have successfully unsubscribed from Event');
INSERT INTO `LocalizationStrings` VALUES(2653, 1, 'Unsubscribe');
INSERT INTO `LocalizationStrings` VALUES(2654, 1, 'Inactive Story');
INSERT INTO `LocalizationStrings` VALUES(2655, 1, 'Profile Videos');
INSERT INTO `LocalizationStrings` VALUES(2656, 1, 'My Flags');
INSERT INTO `LocalizationStrings` VALUES(2657, 1, 'My Topics');
INSERT INTO `LocalizationStrings` VALUES(2658, 1, 'Uncategorized');
INSERT INTO `LocalizationStrings` VALUES(2659, 1, 'upload Music (Music Gallery)');
INSERT INTO `LocalizationStrings` VALUES(2660, 1, 'upload Photos (Photo Gallery)');
INSERT INTO `LocalizationStrings` VALUES(2661, 1, 'upload Video (Video Gallery)');
INSERT INTO `LocalizationStrings` VALUES(2662, 1, 'play Music (Music Gallery)');
INSERT INTO `LocalizationStrings` VALUES(2663, 1, 'view Photos (Photo Gallery)');
INSERT INTO `LocalizationStrings` VALUES(2664, 1, 'play Video (Video Gallery)');
INSERT INTO `LocalizationStrings` VALUES (2665, 1, 'Congratulations! Your e-mail confirmation succeeded and your profile has been activated!<br />\r\nPlease click "Continue" below to navigate to the home page of the site.');
INSERT INTO `LocalizationStrings` VALUES(2666, 1, 'Profile Type');
INSERT INTO `LocalizationStrings` VALUES(2667, 1, 'Profile Type');
INSERT INTO `LocalizationStrings` VALUES(2668, 1, 'Select "Couple" if you are joining as a couple');
INSERT INTO `LocalizationStrings` VALUES(2669, 1, 'General Info');
INSERT INTO `LocalizationStrings` VALUES(2670, 1, 'NickName');
INSERT INTO `LocalizationStrings` VALUES(2671, 1, 'Select NickName which will be used for logging in to the site');
INSERT INTO `LocalizationStrings` VALUES(2672, 1, 'You must enter NickName');
INSERT INTO `LocalizationStrings` VALUES(2673, 1, 'Your NickName must be at least {0} characters long');
INSERT INTO `LocalizationStrings` VALUES(2674, 1, 'Your NickName should be no longer than {0} characters long');
INSERT INTO `LocalizationStrings` VALUES(2675, 1, 'This NickName already used by another. Please select another NickName.');
INSERT INTO `LocalizationStrings` VALUES(2676, 1, 'Your NickName must contain only latin symbols, numbers or underscore ( _ ) or minus ( - ) signs');
INSERT INTO `LocalizationStrings` VALUES(2677, 1, 'Email');
INSERT INTO `LocalizationStrings` VALUES(2678, 1, 'Enter your Email. Your password will be sent to this email.');
INSERT INTO `LocalizationStrings` VALUES(2679, 1, 'You must enter Email');
INSERT INTO `LocalizationStrings` VALUES(2680, 1, 'Your email too short');
INSERT INTO `LocalizationStrings` VALUES(2681, 1, 'Your email already used by another member');
INSERT INTO `LocalizationStrings` VALUES(2682, 1, 'Please enter correct email');
INSERT INTO `LocalizationStrings` VALUES(2683, 1, 'Password');
INSERT INTO `LocalizationStrings` VALUES(2684, 1, 'Please specify your password. It will be used for logging in to the site. This storage is secure, because we are using an encrypted format.');
INSERT INTO `LocalizationStrings` VALUES(2685, 1, 'You must enter password');
INSERT INTO `LocalizationStrings` VALUES(2686, 1, 'Your password must be at least {0} characters long');
INSERT INTO `LocalizationStrings` VALUES(2687, 1, 'Your password should be no longer than {0} characters');
INSERT INTO `LocalizationStrings` VALUES(2688, 1, 'Miscellaneous Info');
INSERT INTO `LocalizationStrings` VALUES(2689, 1, 'Sex');
INSERT INTO `LocalizationStrings` VALUES(2690, 1, 'Please specify your gender');
INSERT INTO `LocalizationStrings` VALUES(2691, 1, 'You must specify your gender');
INSERT INTO `LocalizationStrings` VALUES(2692, 1, 'We are looking for');
INSERT INTO `LocalizationStrings` VALUES(2693, 1, 'Please specify whom you are looking for');
INSERT INTO `LocalizationStrings` VALUES(2694, 1, 'Date of birth');
INSERT INTO `LocalizationStrings` VALUES(2695, 1, 'Please specify your birth date using the calendar or with this format: Day/Month/Year');
INSERT INTO `LocalizationStrings` VALUES(2696, 1, 'You must specify your birth date');
INSERT INTO `LocalizationStrings` VALUES(2697, 1, 'You cannot join the site if you are younger than {0} years');
INSERT INTO `LocalizationStrings` VALUES(2698, 1, 'You cannot be older than {0} years');
INSERT INTO `LocalizationStrings` VALUES(2699, 1, 'Headline');
INSERT INTO `LocalizationStrings` VALUES(2700, 1, 'Enter your life headline');
INSERT INTO `LocalizationStrings` VALUES(2701, 1, 'Description');
INSERT INTO `LocalizationStrings` VALUES(2702, 1, 'Describe yourself in a few words. Your description should be at least {0} characters long.');
INSERT INTO `LocalizationStrings` VALUES(2703, 1, 'You must enter your description');
INSERT INTO `LocalizationStrings` VALUES(2704, 1, 'Your description should be at least 20 characters long');
INSERT INTO `LocalizationStrings` VALUES(2705, 1, 'Country');
INSERT INTO `LocalizationStrings` VALUES(2706, 1, 'Please select the country where are you living');
INSERT INTO `LocalizationStrings` VALUES(2707, 1, 'City');
INSERT INTO `LocalizationStrings` VALUES(2708, 1, 'Enter the name of the city where are you living');
INSERT INTO `LocalizationStrings` VALUES(2709, 1, 'Security Image');
INSERT INTO `LocalizationStrings` VALUES(2710, 1, 'Captcha');
INSERT INTO `LocalizationStrings` VALUES(2711, 1, 'Let us check that you are not a bot. Just enter the text which you see on the picture.');
INSERT INTO `LocalizationStrings` VALUES(2712, 1, 'Admin Controls');
INSERT INTO `LocalizationStrings` VALUES(2713, 1, 'Description');
INSERT INTO `LocalizationStrings` VALUES(2714, 1, 'Zip Code');
INSERT INTO `LocalizationStrings` VALUES(2715, 1, 'Enter your postal zip-code');
INSERT INTO `LocalizationStrings` VALUES(2716, 1, 'Tags');
INSERT INTO `LocalizationStrings` VALUES(2717, 1, 'Enter a few words delimited by commas that describe your character');
INSERT INTO `LocalizationStrings` VALUES(2718, 1, 'General Info');
INSERT INTO `LocalizationStrings` VALUES(2719, 1, 'NickName');
INSERT INTO `LocalizationStrings` VALUES(2720, 1, 'Email');
INSERT INTO `LocalizationStrings` VALUES(2721, 1, 'Sex');
INSERT INTO `LocalizationStrings` VALUES(2722, 1, 'Change Password');
INSERT INTO `LocalizationStrings` VALUES(2723, 1, 'To save old password, just leave this field empty. To change, enter new password and confirm it below.');
INSERT INTO `LocalizationStrings` VALUES(2724, 1, 'Misc Info');
INSERT INTO `LocalizationStrings` VALUES(2725, 1, 'Looking For');
INSERT INTO `LocalizationStrings` VALUES(2726, 1, 'Date Of Birth');
INSERT INTO `LocalizationStrings` VALUES(2727, 1, 'Headline');
INSERT INTO `LocalizationStrings` VALUES(2728, 1, 'Description');
INSERT INTO `LocalizationStrings` VALUES(2729, 1, 'Country');
INSERT INTO `LocalizationStrings` VALUES(2730, 1, 'City');
INSERT INTO `LocalizationStrings` VALUES(2731, 1, 'Admin Controls');
INSERT INTO `LocalizationStrings` VALUES(2732, 1, 'Status');
INSERT INTO `LocalizationStrings` VALUES(2733, 1, 'System user status');
INSERT INTO `LocalizationStrings` VALUES(2734, 1, 'Featured');
INSERT INTO `LocalizationStrings` VALUES(2735, 1, 'Show this member in "Featured" block of index page');
INSERT INTO `LocalizationStrings` VALUES(2736, 1, 'General Info');
INSERT INTO `LocalizationStrings` VALUES(2737, 1, 'Member ID');
INSERT INTO `LocalizationStrings` VALUES(2738, 1, 'NickName');
INSERT INTO `LocalizationStrings` VALUES(2739, 1, 'Status');
INSERT INTO `LocalizationStrings` VALUES(2740, 1, 'Sex');
INSERT INTO `LocalizationStrings` VALUES(2741, 1, 'LookingFor');
INSERT INTO `LocalizationStrings` VALUES(2742, 1, 'Misc Info');
INSERT INTO `LocalizationStrings` VALUES(2743, 1, 'Date Of Birth');
INSERT INTO `LocalizationStrings` VALUES(2744, 1, 'Country');
INSERT INTO `LocalizationStrings` VALUES(2745, 1, 'City');
INSERT INTO `LocalizationStrings` VALUES(2746, 1, 'Description');
INSERT INTO `LocalizationStrings` VALUES(2747, 1, 'Headline');
INSERT INTO `LocalizationStrings` VALUES(2748, 1, 'Description');
INSERT INTO `LocalizationStrings` VALUES(2749, 1, 'Admin Controls');
INSERT INTO `LocalizationStrings` VALUES(2750, 1, 'Email');
INSERT INTO `LocalizationStrings` VALUES(2751, 1, 'Registration Date');
INSERT INTO `LocalizationStrings` VALUES(2752, 1, 'Last Login Date');
INSERT INTO `LocalizationStrings` VALUES(2753, 1, 'Last profile edition date');
INSERT INTO `LocalizationStrings` VALUES(2754, 1, 'General Info');
INSERT INTO `LocalizationStrings` VALUES(2755, 1, 'Profile Type');
INSERT INTO `LocalizationStrings` VALUES(2756, 1, 'Sex');
INSERT INTO `LocalizationStrings` VALUES(2757, 1, 'Age');
INSERT INTO `LocalizationStrings` VALUES(2758, 1, 'Country');
INSERT INTO `LocalizationStrings` VALUES(2759, 1, 'Keyword');
INSERT INTO `LocalizationStrings` VALUES(2760, 1, 'With Tag');
INSERT INTO `LocalizationStrings` VALUES(2761, 1, 'Location');
INSERT INTO `LocalizationStrings` VALUES(2763, 1, 'First Person');
INSERT INTO `LocalizationStrings` VALUES(2764, 1, 'Second Person');
INSERT INTO `LocalizationStrings` VALUES(2765, 1, 'Single');
INSERT INTO `LocalizationStrings` VALUES(2766, 1, 'Couple');
INSERT INTO `LocalizationStrings` VALUES(2767, 1, 'Enter the same password here');
INSERT INTO `LocalizationStrings` VALUES(2768, 1, 'Password confirmation failed');
INSERT INTO `LocalizationStrings` VALUES(2769, 1, 'First value must be bigger');
INSERT INTO `LocalizationStrings` VALUES(2770, 1, 'Captcha check failed');
INSERT INTO `LocalizationStrings` VALUES(2771, 1, 'Join failed');
INSERT INTO `LocalizationStrings` VALUES(2772, 1, 'Join complete');
INSERT INTO `LocalizationStrings` VALUES(2773, 1, 'Select it');
INSERT INTO `LocalizationStrings` VALUES(2774, 1, 'Profile not specified');
INSERT INTO `LocalizationStrings` VALUES(2775, 1, 'You cannot edit this profile');
INSERT INTO `LocalizationStrings` VALUES(2776, 1, 'Profile not found');
INSERT INTO `LocalizationStrings` VALUES(2777, 1, 'Couple profile not found');
INSERT INTO `LocalizationStrings` VALUES(2778, 1, 'The profile was succesfully saved');
INSERT INTO `LocalizationStrings` VALUES(2779, 1, 'Cast my vote');
INSERT INTO `LocalizationStrings` VALUES(2780, 1, 'Male');
INSERT INTO `LocalizationStrings` VALUES(2781, 1, 'Female');
INSERT INTO `LocalizationStrings` VALUES(2782, 1, 'Last profile edit');
INSERT INTO `LocalizationStrings` VALUES(2783, 1, 'Last log in');
INSERT INTO `LocalizationStrings` VALUES(2784, 1, 'ID');
INSERT INTO `LocalizationStrings` VALUES(2785, 1, 'Misc Info');
INSERT INTO `LocalizationStrings` VALUES(2786, 1, 'Enable rate');
INSERT INTO `LocalizationStrings` VALUES(2787, 1, 'Disable rate');
INSERT INTO `LocalizationStrings` VALUES(2788, 1, 'Remember Me');
INSERT INTO `LocalizationStrings` VALUES(2789, 1, '{0} has already joined this group');
INSERT INTO `LocalizationStrings` VALUES(2790, 1, 'Sorry, you''ve been banned');
INSERT INTO `LocalizationStrings` VALUES(2791, 1, '{0} Minute{1} Ago');
INSERT INTO `LocalizationStrings` VALUES(2792, 1, '{0} Hour{1} Ago');
INSERT INTO `LocalizationStrings` VALUES(2793, 1, '{0} Day{1} Ago');
INSERT INTO `LocalizationStrings` VALUES(2794, 1, 'In {0} Minute{1}');
INSERT INTO `LocalizationStrings` VALUES(2795, 1, 'In {0} Hour{1}');
INSERT INTO `LocalizationStrings` VALUES(2796, 1, 'In {0} Day{1}');
INSERT INTO `LocalizationStrings` VALUES(2797, 1, 'Shoutbox');
INSERT INTO `LocalizationStrings` VALUES(2798, 1, 'Powered by');
INSERT INTO `LocalizationStrings` VALUES(2799, 1, 'BoonEx - Community Software;  Dating And Social Networking Scripts; Video Chat And More.');
INSERT INTO `LocalizationStrings` VALUES(2800, 1, 'I have read and agreed with <a href="terms_of_use.php" target="_blank">terms of use</a>.');
INSERT INTO `LocalizationStrings` VALUES(2801, 1, 'You must agree with terms of use');
INSERT INTO `LocalizationStrings` VALUES(2802, 1, 'Show <b>{0}</b>-<u>{1}</u> of {2} discussions');
INSERT INTO `LocalizationStrings` VALUES(2803, 1, 'There are no comments yet');
INSERT INTO `LocalizationStrings` VALUES(2804, 1, 'Error occurred');
INSERT INTO `LocalizationStrings` VALUES(2805, 1, 'Duplicate vote');
INSERT INTO `LocalizationStrings` VALUES(2806, 1, 'No such comment');
INSERT INTO `LocalizationStrings` VALUES(2807, 1, 'Are you sure?');
INSERT INTO `LocalizationStrings` VALUES(2808, 1, 'buried\r\n');
INSERT INTO `LocalizationStrings` VALUES(2809, 1, 'toggle\r\n');
INSERT INTO `LocalizationStrings` VALUES(2810, 1, '<span>{0}</span> point');
INSERT INTO `LocalizationStrings` VALUES(2811, 1, '<span>{0}</span> points');
INSERT INTO `LocalizationStrings` VALUES(2812, 1, 'Thumb Up');
INSERT INTO `LocalizationStrings` VALUES(2813, 1, 'Thumb Down');
INSERT INTO `LocalizationStrings` VALUES(2814, 1, 'Remove');
INSERT INTO `LocalizationStrings` VALUES(2815, 1, '(available for <span>{0}</span> seconds)');
INSERT INTO `LocalizationStrings` VALUES(2816, 1, 'Show <span>{0}</span> replies');
INSERT INTO `LocalizationStrings` VALUES(2817, 1, 'Reply to this comment');
INSERT INTO `LocalizationStrings` VALUES(2818, 1, 'Add Your Comment');
INSERT INTO `LocalizationStrings` VALUES(2819, 1, 'Submit Comment');
INSERT INTO `LocalizationStrings` VALUES(2820, 1, 'Cannot delete comments with replies');
INSERT INTO `LocalizationStrings` VALUES(2821, 1, 'Access denied');
INSERT INTO `LocalizationStrings` VALUES(2822, 1, 'Save');
INSERT INTO `LocalizationStrings` VALUES(2823, 1, 'Search by Tag');
INSERT INTO `LocalizationStrings` VALUES(2824, 1, 'Approve');
INSERT INTO `LocalizationStrings` VALUES(2825, 1, 'Disapprove');
INSERT INTO `LocalizationStrings` VALUES(2826, 1, 'Edit Article');
INSERT INTO `LocalizationStrings` VALUES(2827, 1, 'Article');
INSERT INTO `LocalizationStrings` VALUES(2828, 1, 'Article Title');
INSERT INTO `LocalizationStrings` VALUES(2829, 1, 'Select Category');
INSERT INTO `LocalizationStrings` VALUES(2830, 1, 'Print As');
INSERT INTO `LocalizationStrings` VALUES(2831, 1, 'Hide <span>{0}</span> replies');
INSERT INTO `LocalizationStrings` VALUES(2832, 1, 'Counter');
INSERT INTO `LocalizationStrings` VALUES(2833, 1, 'Articles were deleted successfully');
INSERT INTO `LocalizationStrings` VALUES(2834, 1, 'Article was deleted successfully');
INSERT INTO `LocalizationStrings` VALUES(2835, 1, 'Article was not deleted');
INSERT INTO `LocalizationStrings` VALUES(2836, 1, 'Reply to {0}''s comment');
INSERT INTO `LocalizationStrings` VALUES(2837, 1, 'See all music files of this user');
INSERT INTO `LocalizationStrings` VALUES(2838, 1, 'View All');
INSERT INTO `LocalizationStrings` VALUES(2839, 1, 'You have reached the allowed photo gallery upload limit');
INSERT INTO `LocalizationStrings` VALUES(2840, 1, 'You have reached allowed file limit');
INSERT INTO `LocalizationStrings` VALUES(2841, 1, 'You cannot create events using past dates');

-- --------------------------------------------------------

-- 
-- Table structure for table `media`
-- 

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

-- 
-- Dumping data for table `media`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `media_rating`
-- 

CREATE TABLE `media_rating` (
  `med_id` int(11) NOT NULL default '0',
  `med_rating_count` int(11) NOT NULL default '0',
  `med_rating_sum` int(11) NOT NULL default '0',
  UNIQUE KEY `med_id` (`med_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `media_rating`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `media_voting_track`
-- 

CREATE TABLE `media_voting_track` (
  `med_id` int(11) NOT NULL default '0',
  `med_ip` varchar(20) default NULL,
  `med_date` datetime default NULL,
  KEY `med_ip` (`med_ip`,`med_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `media_voting_track`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `MemActions`
-- 

CREATE TABLE `MemActions` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL default '',
  `AdditionalParamName` varchar(80) default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `MemActions`
-- 

INSERT INTO `MemActions` VALUES (1, 'send greetings', NULL);
INSERT INTO `MemActions` VALUES (2, 'use chat', NULL);
INSERT INTO `MemActions` VALUES (3, 'use instant messenger', NULL);
INSERT INTO `MemActions` VALUES (4, 'view profiles', NULL);
INSERT INTO `MemActions` VALUES (5, 'use forum', NULL);
INSERT INTO `MemActions` VALUES (6, 'make search', 'Max. number of profiles shown in search result (0 = unlimited)');
INSERT INTO `MemActions` VALUES (7, 'vote', NULL);
INSERT INTO `MemActions` VALUES (8, 'send messages', NULL);
INSERT INTO `MemActions` VALUES (9, 'view photos', NULL);
INSERT INTO `MemActions` VALUES (10, 'use Ray instant messenger', NULL);
INSERT INTO `MemActions` VALUES (11, 'use Ray video recorder', NULL);
INSERT INTO `MemActions` VALUES (12, 'use Ray chat', NULL);
INSERT INTO `MemActions` VALUES (13, 'use guestbook', NULL);
INSERT INTO `MemActions` VALUES (14, 'view other members'' guestbooks', NULL);
INSERT INTO `MemActions` VALUES (15, 'get other members'' emails', NULL);
INSERT INTO `MemActions` VALUES (16, 'use gallery', NULL);
INSERT INTO `MemActions` VALUES (17, 'view other members'' galleries', NULL);
INSERT INTO `MemActions` VALUES (18, 'use Ray mp3 player', NULL);
INSERT INTO `MemActions` VALUES (19, 'use Blog', NULL);
INSERT INTO `MemActions` VALUES (20, 'view other members'' Blog', NULL);
INSERT INTO `MemActions` VALUES (21, 'use Ray video player', NULL);
INSERT INTO `MemActions` VALUES (22, 'use Ray presence', NULL);
INSERT INTO `MemActions` VALUES (23, 'can add_delete classifieds', NULL);
INSERT INTO `MemActions` VALUES (24, 'rate photos', NULL);
INSERT INTO `MemActions` VALUES (25, 'use Orca public forums', NULL);
INSERT INTO `MemActions` VALUES (26, 'use Orca private forums', NULL);
INSERT INTO `MemActions` VALUES (27, 'upload Music (Music Gallery)', NULL);
INSERT INTO `MemActions` VALUES (28, 'upload Photos (Photo Gallery)', NULL);
INSERT INTO `MemActions` VALUES (29, 'upload Video (Video Gallery)', NULL);
INSERT INTO `MemActions` VALUES (30, 'play Music (Music Gallery)', NULL);
INSERT INTO `MemActions` VALUES (31, 'view Photos (Photo Gallery)', NULL);
INSERT INTO `MemActions` VALUES (32, 'play Video (Video Gallery)', NULL);
INSERT INTO `MemActions` VALUES (33, 'comments post ', NULL);
INSERT INTO `MemActions` VALUES (34, 'comments vote', NULL);
INSERT INTO `MemActions` VALUES (35, 'comments edit own', NULL);
INSERT INTO `MemActions` VALUES (36, 'comments remove own', NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `MemActionsTrack`
-- 

CREATE TABLE `MemActionsTrack` (
  `IDAction` smallint(5) unsigned NOT NULL default '0',
  `IDMember` bigint(20) unsigned NOT NULL default '0',
  `ActionsLeft` smallint(5) unsigned NOT NULL default '0',
  `ValidSince` datetime default NULL,
  PRIMARY KEY  (`IDAction`,`IDMember`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `MemActionsTrack`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `members_as_aff`
-- 

CREATE TABLE `members_as_aff` (
  `ID` bigint(10) NOT NULL auto_increment,
  `num_of_mem` int(5) NOT NULL default '0',
  `num_of_days` int(5) NOT NULL default '0',
  `MID` int(10) NOT NULL default '0',
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `members_as_aff`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `MemLevelActions`
-- 

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

-- 
-- Dumping data for table `MemLevelActions`
-- 

INSERT INTO `MemLevelActions` VALUES (1, 6, NULL, NULL, NULL, NULL, '10');
INSERT INTO `MemLevelActions` VALUES (1, 7, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (1, 14, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (2, 1, 4, 24, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (2, 4, 2, 24, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (2, 6, 5, 24, NULL, NULL, '0');
INSERT INTO `MemLevelActions` VALUES (2, 7, 15, 24, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (2, 8, 2, 24, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 2, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 3, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 4, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 5, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 6, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 7, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 8, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 9, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 10, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 11, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 12, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 13, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 14, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 15, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 16, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 17, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (2, 9, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (2, 23, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES(2, 24, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (1, 25, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (2, 25, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 25, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (3, 26, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (2, 33, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `MemLevelActions` VALUES (2, 34, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `MemLevelPrices`
-- 

CREATE TABLE `MemLevelPrices` (
  `IDLevel` smallint(5) unsigned NOT NULL default '0',
  `Days` int(10) unsigned NOT NULL default '1',
  `Price` float unsigned NOT NULL default '1',
  PRIMARY KEY  (`IDLevel`,`Days`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `MemLevelPrices`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `MemLevels`
-- 

CREATE TABLE `MemLevels` (
  `ID` smallint(6) NOT NULL auto_increment,
  `Name` varchar(100) NOT NULL default '',
  `Active` enum('yes','no') NOT NULL default 'no',
  `Purchasable` enum('yes','no') NOT NULL default 'yes',
  `Removable` enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `MemLevels`
-- 

INSERT INTO `MemLevels` VALUES (1, 'Non-member', 'yes', 'no', 'no');
INSERT INTO `MemLevels` VALUES (2, 'Standard', 'yes', 'no', 'no');
INSERT INTO `MemLevels` VALUES (3, 'Promotion', 'yes', 'no', 'no');

-- --------------------------------------------------------

-- 
-- Table structure for table `Messages`
-- 

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

-- 
-- Dumping data for table `Messages`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `moderators`
-- 

CREATE TABLE `moderators` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(10) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `Password` varchar(32) NOT NULL default '',
  `status` enum('suspended','active','approval') NOT NULL default 'suspended',
  `reg_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Holds moderator accounts';

-- 
-- Dumping data for table `moderators`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `Modules`
-- 

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

-- 
-- Dumping data for table `Modules`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `News`
-- 

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

-- 
-- Dumping data for table `News`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `NotifyEmails`
-- 

CREATE TABLE `NotifyEmails` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(64) NOT NULL default '',
  `Email` varchar(128) NOT NULL default '',
  `EmailFlag` enum('NotifyMe','NotNotifyMe') NOT NULL default 'NotifyMe',
  `EmailText` enum('HTML','Text','Not sure') NOT NULL default 'HTML',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `NotifyEmails`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `NotifyMsgs`
-- 

CREATE TABLE `NotifyMsgs` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Subj` varchar(128) NOT NULL default '',
  `Text` mediumtext NOT NULL,
  `HTML` mediumtext NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `NotifyMsgs`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `NotifyQueue`
-- 

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

-- 
-- Dumping data for table `NotifyQueue`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `PageCompose`
--

CREATE TABLE `PageCompose` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Page` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `PageWidth` varchar(10) collate utf8_unicode_ci NOT NULL default '960px',
  `Desc` text collate utf8_unicode_ci NOT NULL,
  `Caption` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `Column` tinyint(3) unsigned NOT NULL default '0',
  `Order` int(10) unsigned NOT NULL default '0',
  `Func` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `Content` text collate utf8_unicode_ci NOT NULL,
  `DesignBox` tinyint(3) unsigned NOT NULL default '1',
  `ColWidth` tinyint(3) unsigned NOT NULL default '0',
  `Visible` set('non','memb') collate utf8_unicode_ci NOT NULL default 'non,memb',
  `MinWidth` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `PageCompose`
--

INSERT INTO `PageCompose` VALUES(1, 'index', '960px', 'Shows statistic information concerning your profiles database', '_Site Stats', 1, 0, 'SiteStats', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(2, 'index', '960px', 'Show list of site news', '_latest news', 2, 4, 'News', '', 1, 40, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(3, 'index', '960px', 'Display form to subscribe to newsletters', '_Subscribe', 2, 6, 'Subscribe', '', 1, 40, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(4, 'index', '960px', 'Quick search form', '_Quick Search', 2, 2, 'QuickSearch', '', 1, 40, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(5, 'index', '960px', 'Top rated profiles', '_Leaders', 1, 3, 'Leaders', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(6, 'index', '960px', 'Feedback (Success Story) from your customers', '_Feedback', 2, 8, 'Feedback', '', 1, 40, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(7, 'index', '960px', 'List of featured profiles randomly selected from database', '_featured members', 1, 5, 'Featured', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(8, 'index', '960px', 'Personal profile polls', '_Polls', 2, 7, 'ProfilePoll', '', 1, 40, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(9, 'index', '960px', 'Site Tags', '_Tags', 2, 3, 'Tags', '', 1, 40, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(10, 'index', '960px', 'Short list of top profiles selected by given criteria', '_Members', 1, 2, 'Members', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(11, 'index', '960px', 'Recently posted blogs', '_Blogs', 1, 4, 'Blogs', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(12, 'index', '960px', 'Top rated photos', '_Profile Photos', 1, 9, 'ProfilePhotos', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(13, 'index', '960px', 'Shoutbox', '_Shoutbox', 2, 1, 'Shoutbox', '', 1, 40, 'non,memb', 330);
INSERT INTO `PageCompose` VALUES(14, 'index', '960px', 'Shows Login Form', '_Member Login', 2, 0, 'LoginSection', '', 1, 40, 'non', 0);
INSERT INTO `PageCompose` VALUES(15, 'index', '960px', '', '_BoonEx News', 1, 1, 'RSS', 'http://www.boonex.com/unity/blog/featured_posts/?rss=1#4', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(16, 'index', '960px', 'Classifieds', '_Classifieds', 1, 13, 'Classifieds', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(17, 'index', '960px', 'Events', '_Events', 1, 10, 'Events', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(18, 'index', '960px', 'Groups', '_Groups', 1, 12, 'Groups', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(19, 'index', '960px', '', '_Forum Posts', 2, 5, 'RSS', '{SiteUrl}orca/?action=rss_all#4', 1, 40, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(20, 'index', '960px', 'Photos Shared By Members', '_Photo Gallery', 1, 7, 'SharePhotos', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(21, 'index', '960px', 'Videos Shared By Members', '_Video Gallery', 1, 6, 'ShareVideos', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(22, 'index', '960px', 'Music Files Shared By Members', '_Music Gallery', 1, 8, 'ShareMusic', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(23, 'index', '960px', 'Articles', '_Articles', 1, 11, 'Articles', '', 1, 60, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(24, 'music', '960px', '', '_Music', 1, 0, 'ViewFile', '', 1, 50, 'non,memb', 380);
INSERT INTO `PageCompose` VALUES(25, 'music', '960px', '', '_Rate', 2, 1, 'Rate', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(26, 'music', '960px', '', '_Actions', 1, 1, 'ActionList', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(27, 'music', '960px', '', '_View Comments', 1, 2, 'ViewComments', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(28, 'music', '960px', '', '_Music File Info', 2, 0, 'FileInfo', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(29, 'music', '960px', '', '_Latest files from this user', 2, 2, 'LastFiles', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(30, 'music', '960px', '', '_BoonEx News', 0, 0, 'RSS', 'http://www.boonex.com/unity/blog/featured_posts/?rss=1#4', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(31, 'video', '960px', '', '_Video', 1, 0, 'ViewFile', '', 1, 50, 'non,memb', 380);
INSERT INTO `PageCompose` VALUES(32, 'video', '960px', '', '_Rate', 2, 1, 'Rate', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(33, 'video', '960px', '', '_Actions', 1, 1, 'ActionList', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(34, 'video', '960px', '', '_View Comments', 1, 2, 'ViewComments', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(35, 'video', '960px', '', '_Video Info', 2, 0, 'FileInfo', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(36, 'video', '960px', '', '_Latest files from this user', 2, 2, 'LastFiles', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(37, 'video', '960px', '', '_BoonEx News', 0, 0, 'RSS', 'http://www.boonex.com/unity/blog/featured_posts/?rss=1#4', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(38, 'photo', '960px', '', '_Photo', 1, 0, 'ViewFile', '', 1, 50, 'non,memb', 380);
INSERT INTO `PageCompose` VALUES(39, 'photo', '960px', '', '_Rate', 2, 1, 'Rate', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(40, 'photo', '960px', '', '_Actions', 1, 1, 'ActionList', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(41, 'photo', '960px', '', '_View Comments', 1, 2, 'ViewComments', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(42, 'photo', '960px', '', '_Photo Info', 2, 0, 'FileInfo', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(43, 'photo', '960px', '', '_Latest files from this user', 2, 2, 'LastFiles', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(44, 'photo', '960px', '', '_BoonEx News', 0, 0, 'RSS', 'http://www.boonex.com/unity/blog/featured_posts/?rss=1#4', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(45, 'ads', '960px', '', '_Advertisement Photos', 1, 0, 'AdPhotos', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(46, 'ads', '960px', '', '_Actions', 1, 1, 'ActionList', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(47, 'ads', '960px', '', '_Comments', 1, 2, 'ViewComments', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(48, 'ads', '960px', '', '_Info', 2, 0, 'AdInfo', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(49, 'ads', '960px', '', '_Description', 2, 1, 'Description', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(50, 'ads', '960px', '', '_Users Other Listing', 2, 2, 'UserOtherAds', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(51, 'ads', '960px', '', '_BoonEx News', 0, 0, 'RSS', 'http://www.boonex.com/unity/blog/featured_posts/?rss=1#4', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(52, 'member', '960px', '', '_Site Stats', 1, 1, 'SiteStats', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(53, 'member', '960px', '', '_member info', 2, 0, 'MemberInfo', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(54, 'member', '960px', '', '_contacts', 2, 1, 'Contacts', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(55, 'member', '960px', '', '_latest news', 2, 2, 'News', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(56, 'member', '960px', '', '_BoonEx News', 0, 0, 'RSS', 'http://www.boonex.org/author/admin/feed#4', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(57, 'member', '960px', 'Classifieds', '_Classifieds', 1, 0, 'Classifieds', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(58, 'member', '960px', 'Events', '_Events', 1, 2, 'Events', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(59, 'member', '960px', 'Groups', '_Groups', 1, 3, 'Groups', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(60, 'member', '960px', '', '_Forum Posts', 2, 3, 'RSS', '{SiteUrl}orca/?action=rss_user&user={NickName}#4', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(61, 'member', '960px', '', '_My Music Gallery', 2, 4, 'ShareMusic', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(62, 'member', '960px', '', '_My Photo Gallery', 2, 5, 'SharePhotos', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(63, 'member', '960px', '', '_My Video Gallery', 2, 6, 'ShareVideos', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(64, 'profile', '960px', 'Member polls block', '_Polls', 1, 4, 'ProfilePolls', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(65, 'profile', '960px', 'Actions that other members can do', '_Actions', 1, 0, 'ActionsMenu', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(66, 'profile', '960px', 'Profile rating form', '_rate profile', 2, 4, 'RateProfile', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(67, 'profile', '960px', 'Member friends list', '_Friends', 2, 6, 'Friends', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(68, 'profile', '960px', 'Comments on member profile', '_profile_comments', 2, 10, 'Cmts', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(69, 'profile', '960px', 'Member blog block', '_Blog', 2, 5, 'Blog', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(70, 'profile', '960px', 'Profile Mp3 Player', '_ProfileMp3', 2, 8, 'Mp3', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(71, 'profile', '960px', 'Last posts of a member in the forum', '_Forum Posts', 2, 9, 'RSS', '{SiteUrl}orca/?action=rss_user&user={NickName}#4', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(72, 'profile', '960px', '', '_BoonEx News', 0, 0, 'RSS', 'http://www.boonex.com/unity/blog/featured_posts/?rss=1#4', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(73, 'profile', '960px', 'Classifieds', '_Classifieds', 1, 1, 'Classifieds', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(74, 'profile', '960px', 'Events', '_Events', 1, 2, 'Events', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(75, 'profile', '960px', 'Groups', '_Groups', 1, 3, 'Groups', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(76, 'profile', '960px', 'Music Shared By The Member', '_Music Gallery', 1, 5, 'ShareMusic', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(77, 'profile', '960px', 'Photos Shared By The Member', '_Photo Gallery', 1, 6, 'SharePhotos', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(78, 'profile', '960px', 'Videos Shared By The Member', '_Video Gallery', 1, 7, 'ShareVideos', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(79, 'profile', '960px', 'Mutual friends of viewing and viewed members', '_Mutual Friends', 2, 7, 'MutualFriends', '', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(80, 'profile', '960px', 'Profile Fields Block', '_FieldCaption_General Info_View', 2, 1, 'PFBlock', '17', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(81, 'profile', '960px', 'Profile Fields Block', '_FieldCaption_Misc Info_View', 2, 2, 'PFBlock', '20', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(82, 'profile', '960px', 'Profile Fields Block', '_FieldCaption_Admin Controls_View', 2, 0, 'PFBlock', '21', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(83, 'profile', '960px', 'Profile Fields Block', '_FieldCaption_Description_View', 2, 3, 'PFBlock', '22', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(84, 'profile', '960px', 'Profile Fields Block', '_FieldCaption_Security Image_View', 0, 0, 'PFBlock', '25', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(85, 'profile', '960px', 'Profile Fields Block', '_FieldCaption_Profile Type_View', 0, 0, 'PFBlock', '30', 1, 50, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(86, '', '960px', 'RSS Feed', '_RSS Feed', 0, 0, 'Sample', 'RSS', 1, 0, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(87, '', '960px', 'Simple HTML Block', '_HTML Block', 0, 0, 'Sample', 'Echo', 1, 0, 'non,memb', 0);
INSERT INTO `PageCompose` VALUES(88, 'member', '960px', 'Member Friends', '_My Friends', 1, 4, 'Friends', '', 1, 50, 'memb', 0);

-- --------------------------------------------------------

--
-- Table structure for table `PaymentParameters`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `PaymentParameters`
-- 

INSERT INTO `PaymentParameters` VALUES (1, 1, 'business', 'Business', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (2, 1, 'process_type', 'Process type', 'enum', '''Direct'',''PDT'',''IPN''', 'IPN', 1);
INSERT INTO `PaymentParameters` VALUES (3, 1, 'connection_type', 'Connection type', 'enum', '''SSL'',''HTTP''', 'SSL', 1);
INSERT INTO `PaymentParameters` VALUES (4, 1, 'auth_token', 'Identity token', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (5, 1, 'no_note', 'Don''t prompt customer to include a note', 'check', NULL, 'on', 1);
INSERT INTO `PaymentParameters` VALUES (6, 1, 'test_business', 'SandBox Business', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (7, 2, 'sid', 'Account number', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (8, 2, 'pay_method', 'Pay method', 'enum', '''CC'',''CK''', 'CC', 1);
INSERT INTO `PaymentParameters` VALUES (9, 2, 'secret_word', 'Secret word', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (10, 3, 'x_login', 'Login', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (11, 3, 'x_tran_key', 'Transaction key', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (12, 3, 'implementation', 'Implementation', 'enum', '''SIM'',''AIM''', 'AIM', 1);
INSERT INTO `PaymentParameters` VALUES (13, 3, 'x_delim_char', 'Delimiter char', 'text', NULL, ';', 0);
INSERT INTO `PaymentParameters` VALUES (14, 3, 'x_encap_char', 'Encapsulate char', 'text', NULL, '|', 0);
INSERT INTO `PaymentParameters` VALUES (15, 3, 'curl_binary', 'cURL binary', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (16, 3, 'md5_hash_value', 'MD5 Hash', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (17, 4, 'client_accnum', 'Account number', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (18, 4, 'client_subacc', 'Subaccount number', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (19, 4, 'form_name', 'Form name', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (20, 4, 'allowed_types', 'Allowed types', 'text', NULL, '', 1);
INSERT INTO `PaymentParameters` VALUES (21, 4, 'subscription_type_id', 'Subscription type id', 'text', NULL, '', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `PaymentProviders`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `PaymentProviders`
-- 

INSERT INTO `PaymentProviders` VALUES (1, 'paypal', 'PayPal', 1, 'live', 0, '', '', 1, 'paypal.gif', '<p class="help_caption">Parameters description:</p>\r\n\r\n<p class="help_text"><b>Business</b> - your live PayPal account ID. This ID will be used if module \r\nis in live mode.</p>\r\n\r\n<p class="help_text"><b>Process type</b> - Direct, PDT or IPN. See configuration description below \r\nfor details.</p>\r\n\r\n<p class="help_text"><b>Connection type</b> - SSL or HTTP. This parameter defines validation \r\nback-connection method to the PayPal gateway. SSL is more safe and secure, but \r\nit could be unsupported by your server. If SSL is not supported by your server, \r\nuse HTTP connection type instead.</p>\r\n\r\n<p class="help_text"><b>Identity token</b> - your account''s identification token which is used for \r\ntransaction validation in PDT process type. You can obtain it on your PayPal \r\naccount by enabling Payment Data Transfer (<b>My Account</b> -&gt; <b>Profile</b> \r\n-&gt; <b>Website Payment Preferences</b> -&gt; <b>Payment Data Transfer</b>)</p>\r\n\r\n<p class="help_text"><b>Don''t prompt customer to include a note</b> - indicates should PayPal \r\ngateway prompt customer to write payment note or not. This note could be found \r\nin transaction info hint in finance calculator of admin panel later.</p>\r\n\r\n<p class="help_text"><b>SandBox Business</b> - your test PayPal SandBox account ID. This ID will \r\nbe used if module is in test-approve or test-decline mode.</p>\r\n\r\n<p class="help_caption">Configuration description:</p>\r\n\r\n<p class="help_text">Your PayPal account configuration settings depend on <b>Process type</b> \r\nparameter value:</p>\r\n\r\n<p class="help_text"><b>Direct.</b> In this payment process type script sends payment info to the \r\nPayPal gateway, then PayPal redirects you to script''s payment page, which checks \r\nif payment was successful and makes appropriate data changing. After payment \r\ncheck script shows you payment result. For this payment type you don''t need to \r\nmake any PayPal account configuration. One thing you should know is if you \r\ndecide to enable Auto-Return option you should specify your PayPal module \r\nlocation as return URL (by default it''s paypal.php in your script''s checkout \r\ndirectory). <b>Note:</b> this process type couldn''t be used for recurring \r\nbillings.</p>\r\n\r\n<p class="help_text"><b>PDT</b>. This process type is almost the same as Direct, except one \r\ndetail. PayPal doesn''t send all transactions details to your script. It just \r\nsends transaction token, which is used along with identity token to obtain \r\ntransaction details in notify-synch request. For this process type you should \r\nenable Auto-Return option in your PayPal account (<b>My Account</b> -&gt; <b>\r\nProfile</b> -&gt; <b>Website Payment Preferences</b> -&gt; <b>Auto Return for Website \r\nPayments</b>), set Return URL to your PayPal module URL (by default it''s \r\npaypal.php in your script''s checkout directory), enable Payment Data Transfer (<b>My \r\nAccount</b> -&gt; <b>Profile</b> -&gt; <b>Website Payment Preferences</b> -&gt; <b>\r\nPayment Data Transfer</b>) and copy your Identity Token to appropriate field \r\n(see parameters description above).</p>\r\n\r\n<p class="help_text"><b>IPN.</b> Instant Payment Notification process type differs from Direct and \r\nPDT process type. After payment script redirects you to member area without any \r\nresult message. PayPal sends notification to payment module about any payment \r\nevent on the gateway. Disadvantage of this method is that there is no any result \r\nmessage after payment. You can only check payment result in fact, but this is \r\nonly way you can enable recurring billings for PayPal. Note: you should disable \r\nInstant Payment Notification in your PayPal account (<b>My Account</b> -&gt; <b>\r\nProfile</b> -&gt; <b>Instant Payment Notification Preferences</b>), as payment \r\nmodule sends notification request to PayPal gateway by itself. If you decide to \r\nenable Auto-Return option you should specify your PayPal module location as \r\nreturn URL (by default it''s paypal.php in your script''s checkout directory).</p>');
INSERT INTO `PaymentProviders` VALUES (2, '2checkoutv2', '2Checkout.com v2', 1, 'live', 0, '', '', 0, '2checkout.gif', '<p class="help_caption">Parameters description:</p>\r\n\r\n<p class="help_text"><b>Account number</b> - your 2checkout vendor account number.</p>\r\n\r\n<p class="help_text"><b>Pay method</b> - CC for Credit Card or CK for check (Online checks must \r\nbe enabled within your account first!). This will select the payment method during the checkout \r\nprocess.</p>\r\n\r\n<p class="help_text"><b>Secret word</b> - it is used to check the MD5 hash passback. You can set \r\nit up on your account (<b>Helpful Links</b> -&gt; <b>Look and Feel</b> -&gt; <b>Your Secret \r\nWord</b>)</p>\r\n\r\n<p class="help_caption">Configuration description:</p>\r\n\r\n<p class="help_text">Login to your account, under the "Helpful Links" section click on "Settings" \r\nnear the "Look and Feel" section, input 2Checkout module \r\nlocation (by default it''s 2checkoutv2.php in your script''s checkout \r\ndirectory) into the Approved URL box and URL of member area (http://yoursite.com/member.php \r\nfor example) into the Pending URL box, click "Save changes"</p>');
INSERT INTO `PaymentProviders` VALUES (3, 'authorizenet', 'Authorize.Net', 1, 'live', 0, '', '', 0, 'authorizenet.gif', '<p class="help_caption">Parameters description:</p>\r\n\r\n<p class="help_text"><b>Login</b> - your Authorize.Net login.</p>\r\n\r\n<p class="help_text"><b>Transaction key</b> - transaction key which should be obtained from \r\nMerchant Interface (<b>Settings</b> -&gt; <b>Security section</b> -&gt; <b>Obtain Transaction \r\nKey</b>).</p>\r\n\r\n<p class="help_text"><b>Implementation</b> - determs payment mechanism. If SIM value selected, \r\nscript will redirect customer to payment gateway and then handle response from the Authorize.Net \r\nserver. If AIM value selected, then script will prompt customer to enter credit card details and \r\nsend them to Authorize.Net gateway without any redirections.</p>\r\n\r\n<p class="help_text"><b>cURL binary</b> - full path to the curl binary including filename itself \r\n(i.e. /usr/bin/curl). This value used if cURL extension is not installed on your server.</p>\r\n\r\n<p class="help_text"><b>MD5 Hash</b> - it is used to check the MD5 hash passback. You can set \r\nit up on your account (<b>Settings</b> -&gt; <b>Security section</b> -&gt; <b>MD5 Hash</b>)</p>');
INSERT INTO `PaymentProviders` VALUES (4, 'ccbill', 'CCBill', 1, 'live', 0, '', '', 0, 'ccbill.gif', '<p class="help_caption">No instructions available yet</p>');

-- --------------------------------------------------------

-- 
-- Table structure for table `PaymentSubscriptions`
-- 

CREATE TABLE `PaymentSubscriptions` (
  `TransactionID` bigint(20) unsigned NOT NULL default '0',
  `StartDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `Period` smallint(5) unsigned NOT NULL default '0',
  `ChargesNumber` int(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`TransactionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `PaymentSubscriptions`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `polls_a`
-- 

CREATE TABLE `polls_a` (
  `IDanswer` int(10) unsigned NOT NULL auto_increment,
  `ID` int(11) NOT NULL default '0',
  `Answer` varchar(255) NOT NULL default '',
  `Votes` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`IDanswer`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `polls_a`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `polls_q`
-- 

CREATE TABLE `polls_q` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Question` varchar(255) NOT NULL default '',
  `Active` varchar(2) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- 
-- Dumping data for table `polls_q`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `PreValues`
--

CREATE TABLE `PreValues` (
  `Key` varchar(255) collate utf8_unicode_ci NOT NULL default '' COMMENT 'Key which defines link to values list',
  `Value` varchar(255) collate utf8_unicode_ci NOT NULL default '' COMMENT 'Simple value stored in the database',
  `Order` int(10) unsigned NOT NULL default '0',
  `LKey` varchar(255) collate utf8_unicode_ci NOT NULL default '' COMMENT 'Primary language key used for displaying this value',
  `LKey2` varchar(255) collate utf8_unicode_ci NOT NULL default '' COMMENT 'Additional key used in some other places',
  `LKey3` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `Extra` varchar(255) collate utf8_unicode_ci NOT NULL default '' COMMENT 'Some extra values. For example image link for sex',
  `Extra2` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `Extra3` varchar(255) collate utf8_unicode_ci NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `PreValues`
--

INSERT INTO `PreValues` VALUES('Country', 'TR', 213, '__Turkey', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TT', 214, '__Trinidad and Tobago', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TO', 212, '__Tonga', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TN', 211, '__Tunisia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TM', 210, '__Turkmenistan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TL', 209, '__East Timor', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TK', 208, '__Tokelau', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TJ', 207, '__Tajikistan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TH', 206, '__Thailand', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TG', 205, '__Togo', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TF', 204, '__French Southern and Antarctic Lands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TD', 203, '__Chad', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TC', 202, '__Turks and Caicos Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AI', 201, '__Anguilla', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BI', 200, '__Burundi', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BZ', 199, '__Belize', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CM', 198, '__Cameroon', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CZ', 197, '__Czech Republic', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'FR', 196, '__France', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GI', 195, '__Gibraltar', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GQ', 194, '__Equatorial Guinea', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GR', 193, '__Greece', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'KE', 191, '__Kenya', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'HM', 192, '__Heard Island and McDonald Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'KG', 190, '__Kyrgyzstan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'KM', 189, '__Comoros', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'KW', 188, '__Kuwait', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'LB', 187, '__Lebanon', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MK', 186, '__Macedonia, The Former Yugoslav Republic of', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MO', 185, '__Macao', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MV', 184, '__Maldives', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MY', 183, '__Malaysia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'NU', 182, '__Niue', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'OM', 181, '__Oman', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'RO', 180, '__Romania', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AD', 179, '__Andorra', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AE', 178, '__United Arab Emirates', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AF', 177, '__Afghanistan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AG', 176, '__Antigua and Barbuda', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AL', 175, '__Albania', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AM', 174, '__Armenia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AN', 173, '__Netherlands Antilles', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AO', 172, '__Angola', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AQ', 171, '__Antarctica', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AS', 170, '__American Samoa', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AR', 169, '__Argentina', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AT', 168, '__Austria', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AW', 166, '__Aruba', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AU', 167, '__Australia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'AZ', 165, '__Azerbaijan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BB', 164, '__Barbados', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BA', 163, '__Bosnia and Herzegovina', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BD', 162, '__Bangladesh', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BE', 161, '__Belgium', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BF', 160, '__Burkina Faso', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BG', 159, '__Bulgaria', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BH', 158, '__Bahrain', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BJ', 157, '__Benin', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BM', 156, '__Bermuda', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BN', 155, '__Brunei Darussalam', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BO', 154, '__Bolivia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BR', 153, '__Brazil', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BS', 152, '__The Bahamas', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BT', 151, '__Bhutan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BV', 150, '__Bouvet Island', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BY', 148, '__Belarus', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'BW', 149, '__Botswana', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CA', 147, '__Canada', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CC', 146, '__Cocos (Keeling) Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CD', 145, '__Congo, Democratic Republic of the', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CF', 144, '__Central African Republic', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CG', 143, '__Congo, Republic of the', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CH', 142, '__Switzerland', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CI', 141, '__Cote d''Ivoire', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CK', 140, '__Cook Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CL', 139, '__Chile', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CN', 138, '__China', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CO', 137, '__Colombia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CR', 136, '__Costa Rica', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CU', 135, '__Cuba', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CX', 133, '__Christmas Island', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CV', 134, '__Cape Verde', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'CY', 132, '__Cyprus', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'DJ', 131, '__Djibouti', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'DE', 130, '__Germany', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'DK', 129, '__Denmark', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'DM', 128, '__Dominica', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'DO', 127, '__Dominican Republic', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'DZ', 126, '__Algeria', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'EC', 125, '__Ecuador', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'EE', 124, '__Estonia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'EG', 123, '__Egypt', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'EH', 122, '__Western Sahara', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'ER', 121, '__Eritrea', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'ES', 120, '__Spain', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'ET', 119, '__Ethiopia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'FI', 118, '__Finland', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'FJ', 117, '__Fiji', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'FK', 116, '__Falkland Islands (Islas Malvinas)', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'FM', 115, '__Micronesia, Federated States of', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'FO', 114, '__Faroe Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GA', 113, '__Gabon', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GB', 112, '__United Kingdom', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GD', 111, '__Grenada', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GE', 110, '__Georgia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GF', 109, '__French Guiana', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GL', 107, '__Greenland', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GH', 108, '__Ghana', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GM', 106, '__The Gambia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GP', 104, '__Guadeloupe', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GN', 105, '__Guinea', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GS', 103, '__South Georgia and the South Sandwich Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GT', 102, '__Guatemala', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GU', 101, '__Guam', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GW', 100, '__Guinea-Bissau', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Sex', 'female', 1, '_Female', '_LookinFemale', '', '', '', '');
INSERT INTO `PreValues` VALUES('Sex', 'male', 0, '_Male', '_LookinMale', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'GY', 99, '__Guyana', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'HK', 98, '__Hong Kong (SAR)', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'HN', 97, '__Honduras', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'HR', 96, '__Croatia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'HT', 95, '__Haiti', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'HU', 94, '__Hungary', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'ID', 93, '__Indonesia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'IE', 92, '__Ireland', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'IL', 91, '__Israel', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'IN', 90, '__India', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'IO', 89, '__British Indian Ocean Territory', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'IQ', 88, '__Iraq', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'IR', 87, '__Iran', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'IS', 86, '__Iceland', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'JM', 84, '__Jamaica', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'IT', 85, '__Italy', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'JO', 83, '__Jordan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'JP', 82, '__Japan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'KH', 81, '__Cambodia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'KI', 80, '__Kiribati', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'KN', 79, '__Saint Kitts and Nevis', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'KP', 78, '__Korea, North', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'KR', 77, '__Korea, South', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TV', 215, '__Tuvalu', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'KY', 76, '__Cayman Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'KZ', 75, '__Kazakhstan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'LA', 74, '__Laos', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'LC', 73, '__Saint Lucia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'LK', 72, '__Sri Lanka', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'LI', 71, '__Liechtenstein', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'LR', 70, '__Liberia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'LS', 69, '__Lesotho', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'LT', 68, '__Lithuania', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'LV', 67, '__Latvia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'LU', 66, '__Luxembourg', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'LY', 65, '__Libya', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MA', 64, '__Morocco', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MC', 63, '__Monaco', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MD', 62, '__Moldova', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MG', 61, '__Madagascar', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MH', 60, '__Marshall Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'ML', 59, '__Mali', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MM', 58, '__Burma', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MN', 57, '__Mongolia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MP', 56, '__Northern Mariana Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MR', 55, '__Mauritania', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MQ', 54, '__Martinique', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MS', 53, '__Montserrat', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MT', 52, '__Malta', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MU', 51, '__Mauritius', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MW', 50, '__Malawi', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MX', 49, '__Mexico', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'MZ', 48, '__Mozambique', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'NA', 47, '__Namibia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'NC', 46, '__New Caledonia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'NE', 45, '__Niger', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'NF', 44, '__Norfolk Island', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'NG', 43, '__Nigeria', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'NI', 42, '__Nicaragua', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'NL', 41, '__Netherlands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'NO', 40, '__Norway', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'NP', 39, '__Nepal', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'NR', 38, '__Nauru', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'NZ', 37, '__New Zealand', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PA', 36, '__Panama', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PE', 35, '__Peru', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PF', 34, '__French Polynesia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PG', 33, '__Papua New Guinea', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PH', 32, '__Philippines', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PL', 31, '__Poland', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PK', 30, '__Pakistan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PM', 29, '__Saint Pierre and Miquelon', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PN', 28, '__Pitcairn Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PR', 27, '__Puerto Rico', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PS', 26, '__Palestinian Territory, Occupied', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PT', 25, '__Portugal', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PW', 24, '__Palau', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'PY', 23, '__Paraguay', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'QA', 22, '__Qatar', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'RE', 21, '__Reunion', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'RU', 20, '__Russia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'RW', 19, '__Rwanda', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SA', 18, '__Saudi Arabia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SB', 17, '__Solomon Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SC', 16, '__Seychelles', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SD', 15, '__Sudan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SE', 14, '__Sweden', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SG', 13, '__Singapore', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SH', 12, '__Saint Helena', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SI', 11, '__Slovenia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SJ', 10, '__Svalbard', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SK', 9, '__Slovakia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SL', 8, '__Sierra Leone', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SM', 7, '__San Marino', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SN', 6, '__Senegal', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SO', 5, '__Somalia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SR', 4, '__Suriname', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'ST', 3, '__Sao Tome and Principe', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SV', 2, '__El Salvador', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SY', 1, '__Syria', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'SZ', 0, '__Swaziland', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TW', 216, '__Taiwan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'TZ', 217, '__Tanzania', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'UA', 218, '__Ukraine', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'UG', 219, '__Uganda', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'UM', 220, '__United States Minor Outlying Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'US', 221, '__United States', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'UY', 222, '__Uruguay', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'UZ', 223, '__Uzbekistan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'VA', 224, '__Holy See (Vatican City)', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'VC', 225, '__Saint Vincent and the Grenadines', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'VE', 226, '__Venezuela', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'VG', 227, '__British Virgin Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'VI', 228, '__Virgin Islands', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'VN', 229, '__Vietnam', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'VU', 230, '__Vanuatu', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'WF', 231, '__Wallis and Futuna', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'WS', 232, '__Samoa', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'YE', 233, '__Yemen', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'YT', 234, '__Mayotte', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'YU', 235, '__Yugoslavia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'ZA', 236, '__South Africa', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'ZM', 237, '__Zambia', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Country', 'ZW', 238, '__Zimbabwe', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Height', '1', 1, '__4''7" (140cm) or below', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Height', '2', 2, '__4''8" - 4''11" (141-150cm)', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Height', '3', 3, '__5''0" - 5''3" (151-160cm)', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Height', '4', 4, '__5''4" - 5''7" (161-170cm)', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Height', '5', 5, '__5''8" - 5''11" (171-180cm)', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Height', '6', 6, '__6''0" - 6''3" (181-190cm)', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Height', '7', 7, '__6''4" (191cm) or above', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('BodyType', '1', 1, '__Average', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('BodyType', '2', 2, '__Ample', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('BodyType', '3', 3, '__Athletic', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('BodyType', '4', 4, '__Cuddly', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('BodyType', '5', 5, '__Slim', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('BodyType', '6', 6, '__Very Cuddly', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '1', 1, '__Adventist', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '2', 2, '__Agnostic', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '3', 3, '__Atheist', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '4', 4, '__Baptist', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '5', 5, '__Buddhist', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '6', 6, '__Caodaism', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '7', 7, '__Catholic', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '8', 8, '__Christian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '9', 9, '__Hindu', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '10', 10, '__Iskcon', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '11', 11, '__Jainism', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '12', 12, '__Jewish', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '13', 13, '__Methodist', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '14', 14, '__Mormon', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '15', 15, '__Moslem', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '16', 16, '__Orthodox', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '17', 17, '__Pentecostal', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '18', 18, '__Protestant', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '19', 19, '__Quaker', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '20', 20, '__Scientology', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '21', 21, '__Shinto', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '22', 22, '__Sikhism', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '23', 23, '__Spiritual', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '24', 24, '__Taoism', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '25', 25, '__Wiccan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Religion', '26', 26, '__Other', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Ethnicity', '1', 1, '__African', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Ethnicity', '2', 2, '__African American', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Ethnicity', '3', 3, '__Asian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Ethnicity', '4', 4, '__Caucasian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Ethnicity', '5', 5, '__East Indian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Ethnicity', '6', 6, '__Hispanic', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Ethnicity', '7', 7, '__Indian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Ethnicity', '8', 8, '__Latino', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Ethnicity', '9', 9, '__Mediterranean', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Ethnicity', '10', 10, '__Middle Eastern', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Ethnicity', '11', 11, '__Mixed', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('MaritalStatus', '1', 1, '__Single', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('MaritalStatus', '2', 2, '__Attached', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('MaritalStatus', '3', 3, '__Divorced', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('MaritalStatus', '4', 4, '__Married', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('MaritalStatus', '5', 5, '__Separated', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('MaritalStatus', '6', 6, '__Widow', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '0', 0, '__English', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '1', 1, '__Afrikaans', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '2', 2, '__Arabic', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '3', 3, '__Bulgarian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '4', 4, '__Burmese', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '5', 5, '__Cantonese', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '6', 6, '__Croatian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '7', 7, '__Danish', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '8', 8, '__Dutch', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '9', 9, '__Esperanto', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '10', 10, '__Estonian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '11', 11, '__Finnish', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '12', 12, '__French', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '13', 13, '__German', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '14', 14, '__Greek', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '15', 15, '__Gujrati', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '16', 16, '__Hebrew', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '17', 17, '__Hindi', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '18', 18, '__Hungarian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '19', 19, '__Icelandic', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '20', 20, '__Indian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '21', 21, '__Indonesian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '22', 22, '__Italian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '23', 23, '__Japanese', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '24', 24, '__Korean', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '25', 25, '__Latvian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '26', 26, '__Lithuanian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '27', 27, '__Malay', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '28', 28, '__Mandarin', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '29', 29, '__Marathi', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '30', 30, '__Moldovian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '31', 31, '__Nepalese', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '32', 32, '__Norwegian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '33', 33, '__Persian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '34', 34, '__Polish', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '35', 35, '__Portuguese', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '36', 36, '__Punjabi', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '37', 37, '__Romanian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '38', 38, '__Russian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '39', 39, '__Serbian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '40', 40, '__Spanish', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '41', 41, '__Swedish', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '42', 42, '__Tagalog', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '43', 43, '__Taiwanese', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '44', 44, '__Tamil', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '45', 45, '__Telugu', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '46', 46, '__Thai', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '47', 47, '__Tongan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '48', 48, '__Turkish', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '49', 49, '__Ukrainian', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '50', 50, '__Urdu', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '51', 51, '__Vietnamese', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Language', '52', 52, '__Visayan', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Education', '1', 1, '__High School graduate', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Education', '2', 2, '__Some college', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Education', '3', 3, '__College student', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Education', '4', 4, '__AA (2 years college)', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Education', '5', 5, '__BA/BS (4 years college)', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Education', '6', 6, '__Some grad school', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Education', '7', 7, '__Grad school student', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Education', '8', 8, '__MA/MS/MBA', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Education', '9', 9, '__PhD/Post doctorate', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Education', '10', 10, '__JD', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Income', '1', 1, '__$10,000/year and less', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Income', '2', 2, '__$10,000-$30,000/year', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Income', '3', 3, '__$30,000-$50,000/year', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Income', '4', 4, '__$50,000-$70,000/year', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Income', '5', 5, '__$70,000/year and more', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Smoker', '1', 1, '__No', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Smoker', '2', 2, '__Rarely', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Smoker', '3', 3, '__Often', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Smoker', '4', 4, '__Very often', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Drinker', '1', 1, '__No', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Drinker', '2', 2, '__Rarely', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Drinker', '3', 3, '__Often', '', '', '', '', '');
INSERT INTO `PreValues` VALUES('Drinker', '4', 4, '__Very often', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pre_forum`
-- 

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

-- 
-- Dumping data for table `pre_forum`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pre_forum_cat`
-- 

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

-- 
-- Dumping data for table `pre_forum_cat`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pre_forum_flag`
-- 

CREATE TABLE `pre_forum_flag` (
  `user` varchar(16) NOT NULL default '',
  `topic_id` int(11) NOT NULL default '0',
  `when` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user`,`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `pre_forum_flag`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pre_forum_post`
-- 

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

-- 
-- Dumping data for table `pre_forum_post`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pre_forum_report`
-- 

CREATE TABLE `pre_forum_report` (
  `user_name` varchar(16) NOT NULL default '',
  `post_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_name`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `pre_forum_report`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pre_forum_topic`
-- 

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

-- 
-- Dumping data for table `pre_forum_topic`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pre_forum_user`
-- 

CREATE TABLE `pre_forum_user` (
  `user_name` varchar(16) NOT NULL default '',
  `user_pwd` varchar(32) NOT NULL default '',
  `user_email` varchar(128) NOT NULL default '',
  `user_join_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `pre_forum_user`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pre_forum_user_activity`
-- 

CREATE TABLE `pre_forum_user_activity` (
  `user` varchar(16) NOT NULL default '',
  `act_current` int(11) NOT NULL default '0',
  `act_last` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `pre_forum_user_activity`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pre_forum_user_stat`
-- 

CREATE TABLE `pre_forum_user_stat` (
  `user` varchar(16) NOT NULL default '',
  `posts` int(11) NOT NULL default '0',
  `user_last_post` int(11) NOT NULL default '0',
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `pre_forum_user_stat`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pre_forum_vote`
-- 

CREATE TABLE `pre_forum_vote` (
  `user_name` varchar(16) NOT NULL default '',
  `post_id` int(11) NOT NULL default '0',
  `vote_when` int(11) NOT NULL default '0',
  `vote_point` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`user_name`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `pre_forum_vote`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `PrivPhotosRequests`
-- 

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

-- 
-- Dumping data for table `PrivPhotosRequests`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ProfileFields`
--

CREATE TABLE `ProfileFields` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(255) character set utf8 collate utf8_bin NOT NULL default '',
  `Type` enum('text','area','pass','date','select_one','select_set','num','range','bool','system','block') collate utf8_unicode_ci NOT NULL default 'text',
  `Control` enum('select','checkbox','radio') collate utf8_unicode_ci NOT NULL default 'select' COMMENT 'input element for selectors',
  `Extra` text collate utf8_unicode_ci NOT NULL,
  `Min` float default NULL,
  `Max` float default NULL,
  `Values` text collate utf8_unicode_ci NOT NULL,
  `UseLKey` enum('LKey','LKey2','LKey3') collate utf8_unicode_ci NOT NULL default 'LKey',
  `Check` text collate utf8_unicode_ci NOT NULL,
  `Unique` tinyint(1) NOT NULL default '0',
  `Default` text collate utf8_unicode_ci NOT NULL,
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
  `SearchParams` text collate utf8_unicode_ci NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ProfileFields`
--

INSERT INTO `ProfileFields` VALUES(1, 'ID', 'system', '', '', NULL, NULL, '', 'LKey', '', 1, '', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 17, 1, 17, 1, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(2, 'NickName', 'text', '', '', 4, 16, '', 'LKey', 'return ( preg_match( ''/^[a-zA-Z0-9_-]+$/'', $arg0 ) and !file_exists( $dir[''root''] . $arg0 ) );', 1, '', 1, 0, 0, 17, 1, 17, 1, 17, 1, 17, 1, 17, 1, 17, 2, 17, 2, 17, 1, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(3, 'Password', 'pass', '', '', 5, 16, '', 'LKey', '', 0, '', 1, 0, 0, 17, 3, 17, 4, 17, 4, 17, 4, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(4, 'Email', 'text', '', '', 6, NULL, '', 'LKey', 'return (bool)preg_match( ''/^[a-z0-9_\\-]+(\\.[_a-z0-9\\-]+)*@([_a-z0-9\\-]+\\.)+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)$/i'', $arg0 );', 1, '', 1, 0, 0, 17, 2, 17, 2, 17, 2, 17, 2, 0, NULL, 21, 1, 21, 1, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(5, 'DateReg', 'system', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 21, 2, 21, 2, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(6, 'DateLastEdit', 'system', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 21, 4, 21, 4, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(7, 'Status', 'system', 'select', '', NULL, NULL, 'Unconfirmed\nApproval\nActive\nRejected\nSuspended', 'LKey', '', 0, '', 0, 0, 0, 0, NULL, 0, NULL, 21, 1, 21, 1, 0, NULL, 17, 3, 17, 3, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(8, 'DateLastLogin', 'system', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 21, 3, 21, 3, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(9, 'Featured', 'system', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 0, 0, 0, NULL, 0, NULL, 21, 2, 21, 2, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(10, 'Sex', 'select_one', 'radio', '', NULL, NULL, '#!Sex', 'LKey', '', 0, 'male', 1, 1, 0, 20, 1, 17, 3, 17, 3, 17, 3, 17, 2, 17, 4, 17, 4, 17, 2, '', 17, 2, 0, NULL, 17, 2, 11, 40);
INSERT INTO `ProfileFields` VALUES(11, 'LookingFor', 'select_set', 'checkbox', '', NULL, NULL, '#!Sex', 'LKey2', '', 0, '', 0, 0, 0, 20, 2, 20, 1, 20, 1, 20, 1, 17, 3, 17, 5, 17, 5, 17, 3, '', 0, NULL, 0, NULL, 0, NULL, 10, 40);
INSERT INTO `ProfileFields` VALUES(12, 'DescriptionMe', 'area', '', '', 20, NULL, '', 'LKey', '', 0, '', 1, 1, 0, 20, 5, 20, 4, 20, 4, 20, 4, 22, 2, 22, 2, 22, 2, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(13, 'DateOfBirth', 'date', '', '', 18, 75, '', 'LKey', '', 0, '', 1, 1, 0, 20, 3, 20, 2, 20, 2, 20, 2, 20, 1, 20, 1, 20, 1, 0, NULL, '', 17, 3, 0, NULL, 17, 3, 0, 0);
INSERT INTO `ProfileFields` VALUES(14, 'Headline', 'text', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 1, 0, 20, 4, 20, 3, 20, 3, 20, 3, 22, 1, 22, 1, 22, 1, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(15, 'Country', 'select_one', 'select', '', NULL, NULL, '#!Country', 'LKey', '', 0, 'US', 0, 1, 0, 20, 6, 20, 5, 20, 5, 20, 5, 20, 2, 20, 2, 20, 2, 20, 1, '', 20, 1, 0, NULL, 20, 1, 15, 20);
INSERT INTO `ProfileFields` VALUES(16, 'City', 'text', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 1, 0, 20, 7, 20, 6, 20, 6, 20, 6, 20, 3, 20, 3, 20, 3, 20, 2, '', 0, NULL, 0, NULL, 20, 2, 0, 0);
INSERT INTO `ProfileFields` VALUES(17, 'General Info', 'block', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 1, 0, 0, 2, 0, 1, 0, 2, 0, 2, 0, 1, 0, 2, 0, 1, 0, 1, '', 0, 1, 0, NULL, 0, 1, 0, 0);
INSERT INTO `ProfileFields` VALUES(18, 'Location', 'system', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 20, 5, 0, 0);
INSERT INTO `ProfileFields` VALUES(19, 'Keyword', 'system', '', 'DescriptionMe\nHeadline', NULL, NULL, '', 'LKey', '', 0, '', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 20, 2, 0, NULL, 20, 3, 0, 0);
INSERT INTO `ProfileFields` VALUES(20, 'Misc Info', 'block', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 1, 0, 0, 3, 0, 2, 0, 3, 0, 3, 0, 2, 0, 3, 0, 2, 0, 2, '', 0, 2, 0, NULL, 0, 2, 0, 0);
INSERT INTO `ProfileFields` VALUES(21, 'Admin Controls', 'block', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 1, 0, 0, NULL, 0, NULL, 0, 1, 0, 1, 0, NULL, 0, 5, 0, 4, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(22, 'Description', 'block', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 1, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, 3, 0, 4, 0, 3, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(23, 'Couple', 'system', 'select', 'Country\nCity', NULL, NULL, '', 'LKey', '', 0, '', 1, 0, 0, 30, 1, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 17, 1, 0, NULL, 17, 1, 0, 0);
INSERT INTO `ProfileFields` VALUES(24, 'Captcha', 'system', '', '', NULL, NULL, '', 'LKey', '', 0, '', 1, 0, 0, 25, 1, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(25, 'Security Image', 'block', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 1, 0, 0, 4, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(30, 'Profile Type', 'block', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 1, 0, 0, 1, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(41, 'EmailNotify', 'system', '', '', NULL, NULL, 'NotifyMe\nNotNotifyMe', 'LKey', '', 0, '', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(39, 'zip', 'text', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 0, 0, 20, 8, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(34, 'DateLastNav', 'system', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(35, 'PrimPhoto', 'system', '', '', NULL, NULL, '', 'LKey', '', 0, '0', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(36, 'Picture', 'system', '', '', NULL, NULL, '', 'LKey', '', 0, '0', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(37, 'aff_num', 'system', '', '', NULL, NULL, '', 'LKey', '', 0, '0', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);
INSERT INTO `ProfileFields` VALUES(38, 'Tags', 'text', '', '', NULL, NULL, '', 'LKey', '', 0, '', 0, 0, 0, 20, 9, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 20, 4, 0, 0);
INSERT INTO `ProfileFields` VALUES(42, 'TermsOfUse', 'system', '', '', NULL, NULL, '', 'LKey', '', 0, '', 1, 1, 0, 25, 2, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `ProfileMemLevels`
-- 

CREATE TABLE `ProfileMemLevels` (
  `IDMember` bigint(20) unsigned NOT NULL default '0',
  `IDLevel` smallint(5) unsigned NOT NULL default '0',
  `DateStarts` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateExpires` datetime default NULL,
  `TransactionID` bigint(20) unsigned default NULL,
  PRIMARY KEY  (`IDMember`,`IDLevel`,`DateStarts`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `ProfileMemLevels`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `Profiles`
-- 

CREATE TABLE `Profiles` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `NickName` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `Email` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `Password` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `Status` enum('Unconfirmed','Approval','Active','Rejected','Suspended') collate utf8_unicode_ci NOT NULL default 'Unconfirmed',
  `Couple` int(10) unsigned NOT NULL default '0',
  `Sex` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `LookingFor` set('male','female') collate utf8_unicode_ci NOT NULL default '',
  `Headline` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `DescriptionMe` text collate utf8_unicode_ci NOT NULL,
  `Country` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `City` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `DateOfBirth` date NOT NULL default '0000-00-00',
  `Featured` tinyint(1) NOT NULL default '0',
  `DateReg` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateLastEdit` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateLastLogin` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateLastNav` datetime NOT NULL default '0000-00-00 00:00:00',
  `PrimPhoto` int(10) unsigned NOT NULL default '0',
  `Picture` tinyint(1) NOT NULL default '0',
  `aff_num` int(10) unsigned NOT NULL default '0',
  `Tags` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `zip` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `EmailNotify` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `Profiles`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ProfilesMatch`
--

CREATE TABLE `ProfilesMatch` (
  `PID1` int(10) unsigned NOT NULL default '0' COMMENT 'Profile ID',
  `PID2` int(10) unsigned NOT NULL default '0',
  `Percent` tinyint(4) NOT NULL default '0',
  KEY `MatchPair` (`PID1`,`PID2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ProfilesMatch`
--


-- --------------------------------------------------------

--
-- Table structure for table `ProfilesPolls`
-- 

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

-- 
-- Dumping data for table `ProfilesPolls`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ProfilesSettings`
-- 

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

-- 
-- Dumping data for table `ProfilesSettings`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ProfilesTrack`
-- 

CREATE TABLE `ProfilesTrack` (
  `Member` bigint(8) unsigned NOT NULL default '0',
  `Profile` bigint(8) unsigned NOT NULL default '0',
  `Arrived` date NOT NULL default '0000-00-00',
  `Hide` tinyint(4) NOT NULL default '0',
  UNIQUE KEY `Member_2` (`Member`,`Profile`),
  KEY `Member` (`Member`),
  KEY `Profile` (`Profile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `ProfilesTrack`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `profile_rating`
-- 

CREATE TABLE `profile_rating` (
  `pr_id` bigint(8) NOT NULL default '0',
  `pr_rating_count` int(11) NOT NULL default '0',
  `pr_rating_sum` int(11) NOT NULL default '0',
  UNIQUE KEY `med_id` (`pr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `profile_rating`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `profile_voting_track`
-- 

CREATE TABLE `profile_voting_track` (
  `pr_id` bigint(8) NOT NULL default '0',
  `pr_ip` varchar(20) default NULL,
  `pr_date` datetime default NULL,
  KEY `pr_ip` (`pr_ip`,`pr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `profile_voting_track`
-- 

-- RAY AS IT WAS INTEGRATED

-- --------------------------------------------------------

--
-- Table structure for table `RayBoardBoards`
--

CREATE TABLE `RayBoardBoards` (
  `ID` int(11) NOT NULL auto_increment,
  `UserID` varchar(64) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  `Track` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayBoardBoards`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayChatCurrentUsers`
--

CREATE TABLE `RayChatCurrentUsers` (
  `ID` varchar(20) NOT NULL default '',
  `Nick` varchar(36) NOT NULL default '',
  `Sex` enum('M','F') NOT NULL default 'M',
  `Age` int(11) NOT NULL default '0',
  `Desc` text NOT NULL,
  `Photo` varchar(255) NOT NULL default '',
  `Profile` varchar(255) NOT NULL default '',
  `Online` enum('online','busy','away') NOT NULL default 'online',
  `Start` int(11) NOT NULL default '0',
  `When` int(11) NOT NULL default '0',
  `Status` enum('new','old','idle','kick','type','online') NOT NULL default 'new',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayChatCurrentUsers`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayChatMessages`
--

CREATE TABLE `RayChatMessages` (
  `ID` int(11) NOT NULL auto_increment,
  `Room` int(11) NOT NULL default '0',
  `Sender` varchar(20) NOT NULL default '',
  `Recipient` varchar(20) NOT NULL default '',
  `Whisper` enum('true','false') NOT NULL default 'false',
  `Message` text NOT NULL,
  `Style` text NOT NULL,
  `Type` enum('text','file') NOT NULL default 'text',
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayChatMessages`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayChatProfiles`
--

CREATE TABLE `RayChatProfiles` (
  `ID` varchar(20) NOT NULL default '0',
  `Banned` enum('true','false') NOT NULL default 'false',
  `Type` enum('view','text','full','moder') NOT NULL default 'full',
  `Smileset` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayChatProfiles`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayChatRooms`
--

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayChatRooms`
--

INSERT INTO `RayChatRooms` VALUES(1, 'Lobby', '', 'Welcome to our chat! You are in the "Lobby" now, but you can pass into any other public room you wish to - take a look at the "All rooms" box. If you have any problems with using this chat, there is a "Help" button on the right at the top (a question icon). Simply click on it and find the answers to your questions.', '0', 0, 'normal');
INSERT INTO `RayChatRooms` VALUES(2, 'Friends', '', 'Welcome to the "Friends" room! This is a public room where you can have a fun chat with existing friends or make new ones! Enjoy!', '0', 1, 'normal');

-- --------------------------------------------------------

--
-- Table structure for table `RayChatRoomsUsers`
--

CREATE TABLE `RayChatRoomsUsers` (
  `ID` int(11) NOT NULL auto_increment,
  `Room` int(11) NOT NULL default '0',
  `User` varchar(20) NOT NULL default '',
  `When` int(11) default NULL,
  `Status` enum('normal','delete') NOT NULL default 'normal',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayChatRoomsUsers`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayGlobalTrackUsers`
--

CREATE TABLE `RayGlobalTrackUsers` (
  `ID` int(11) unsigned NOT NULL default '0',
  `When` bigint(20) unsigned NOT NULL default '0',
  `Status` enum('online','offline') NOT NULL default 'online',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayGlobalTrackUsers`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayImContacts`
--

CREATE TABLE `RayImContacts` (
  `ID` int(11) NOT NULL auto_increment,
  `SenderID` int(11) NOT NULL default '0',
  `RecipientID` int(11) NOT NULL default '0',
  `Online` enum('online','busy','away') NOT NULL default 'online',
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayImContacts`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayImMessages`
--

CREATE TABLE `RayImMessages` (
  `ID` int(11) NOT NULL auto_increment,
  `ContactID` int(11) NOT NULL default '0',
  `Message` text NOT NULL,
  `Style` text NOT NULL,
  `Type` enum('text','file') NOT NULL default 'text',
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayImMessages`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayImPendings`
--

CREATE TABLE `RayImPendings` (
  `ID` int(11) NOT NULL auto_increment,
  `SenderID` int(11) NOT NULL default '0',
  `RecipientID` int(11) NOT NULL default '0',
  `Message` varchar(255) NOT NULL default '',
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `RecipientID` (`RecipientID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayImPendings`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayImProfiles`
--

CREATE TABLE `RayImProfiles` (
  `ID` int(11) NOT NULL default '0',
  `Smileset` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayImProfiles`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayMovieFiles`
--

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

--
-- Dumping data for table `RayMovieFiles`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayMoviePlayLists`
--

CREATE TABLE `RayMoviePlayLists` (
  `FileId` int(11) NOT NULL default '0',
  `Owner` varchar(64) NOT NULL default '',
  `Order` tinyint(4) NOT NULL default '0',
  KEY `FileId` (`FileId`,`Owner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayMoviePlayLists`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayMp3Categories`
--

CREATE TABLE `RayMp3Categories` (
  `ID` int(11) NOT NULL auto_increment,
  `Parent` int(11) NOT NULL default '0',
  `Title` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `Parent` (`Parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayMp3Categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayMp3Files`
--

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

--
-- Dumping data for table `RayMp3Files`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayMp3PlayLists`
--

CREATE TABLE `RayMp3PlayLists` (
  `FileId` int(11) NOT NULL default '0',
  `Owner` varchar(64) NOT NULL default '',
  `Order` tinyint(4) NOT NULL default '0',
  KEY `FileId` (`FileId`,`Owner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayMp3PlayLists`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayMusicCategories`
--

CREATE TABLE `RayMusicCategories` (
  `ID` int(11) NOT NULL auto_increment,
  `Parent` int(11) NOT NULL default '0',
  `Title` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `Parent` (`Parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayMusicCategories`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayMusicFiles`
--

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

--
-- Dumping data for table `RayMusicFiles`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayMusicPlayLists`
--

CREATE TABLE `RayMusicPlayLists` (
  `FileId` int(11) NOT NULL default '0',
  `Owner` varchar(64) NOT NULL default '',
  `Order` tinyint(4) NOT NULL default '0',
  KEY `FileId` (`FileId`,`Owner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayMusicPlayLists`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayShoutboxMessages`
--

CREATE TABLE `RayShoutboxMessages` (
  `ID` int(11) NOT NULL auto_increment,
  `UserID` varchar(20) NOT NULL default '0',
  `Msg` text NOT NULL,
  `When` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayShoutboxMessages`
--


-- --------------------------------------------------------

--
-- Table structure for table `RayVideoStats`
--

CREATE TABLE `RayVideoStats` (
  `User` varchar(64) NOT NULL default '',
  `Approved` int(20) NOT NULL default '0',
  `Pending` int(20) NOT NULL default '0',
  PRIMARY KEY  (`User`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RayVideoStats`
--

INSERT INTO `RayVideoStats` VALUES('', 0, 0);

-- --------------------------------------------------------
-- RAY AS IT WAS INTEGRATED [END]

-- 
-- Table structure for table `SDatingEvents`
-- 

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

-- 
-- Dumping data for table `SDatingEvents`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `SDatingMatches`
-- 

CREATE TABLE `SDatingMatches` (
  `IDChooser` bigint(10) unsigned NOT NULL default '0',
  `IDChosen` bigint(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`IDChooser`,`IDChosen`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `SDatingMatches`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `SDatingParticipants`
-- 

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

-- 
-- Dumping data for table `SDatingParticipants`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `shareMusicFavorites`
-- 

CREATE TABLE `shareMusicFavorites` (
  `medID` int(12) NOT NULL default '0',
  `userID` bigint(12) unsigned NOT NULL default '0',
  `favDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`medID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `shareMusicFavorites`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sharePhotoFavorites`
-- 

CREATE TABLE `sharePhotoFavorites` (
  `medID` int(12) NOT NULL default '0',
  `userID` bigint(12) unsigned NOT NULL default '0',
  `favDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`medID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `sharePhotoFavorites`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sharePhotoFiles`
-- 

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

-- 
-- Dumping data for table `sharePhotoFiles`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `shareVideoFavorites`
-- 

CREATE TABLE `shareVideoFavorites` (
  `medID` int(12) NOT NULL default '0',
  `userID` bigint(12) unsigned NOT NULL default '0',
  `favDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`medID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `shareVideoFavorites`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `shoutbox`
-- 

CREATE TABLE `shoutbox` (
  `id` bigint(8) unsigned NOT NULL default '0',
  `text` varchar(120) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `class` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `shoutbox`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `SiteStat`
--

CREATE TABLE `SiteStat` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Name` varchar(3) NOT NULL default '',
  `Title` varchar(50) NOT NULL default '',
  `UserLink` varchar(255) NOT NULL default '',
  `UserQuery` varchar(150) NOT NULL default '',
  `AdminLink` varchar(255) NOT NULL default '',
  `AdminQuery` varchar(255) NOT NULL default '',
  `IconName` varchar(50) NOT NULL default '',
  `StatOrder` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `SiteStat`
--

INSERT INTO `SiteStat` VALUES(1, 'all', 'Members', 'browse.php', 'SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = ''Active'' AND (`Couple`=''0'' OR `Couple`>`ID`)', 'profiles.php?profiles=Approval', 'SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status`!=''Active'' AND (`Couple`=''0'' OR `Couple`>`ID`)', 'mbs.gif', 0);
INSERT INTO `SiteStat` VALUES(2, 'pph', 'Photos', 'browsePhoto.php', 'SELECT COUNT(`medID`) FROM `sharePhotoFiles` WHERE `Approved`=''true''', 'browseMedia.php?type=photo', 'SELECT COUNT(`medID`) FROM `sharePhotoFiles` WHERE `Approved`=''false''', 'pph.gif', 0);
INSERT INTO `SiteStat` VALUES(3, 'evs', 'Events', 'events.php?show_events=all&action=show', 'SELECT COUNT(`ID`) FROM `SDatingEvents` WHERE `Status`=''Active''', 'sdating_admin.php', 'SELECT COUNT(`ID`) FROM `SDatingEvents` WHERE `Status`!=''Active''', 'evs.gif', 0);
INSERT INTO `SiteStat` VALUES(4, 'onl', 'Online', 'search.php?online_only=1', 'SELECT COUNT(`ID`) AS `count_onl` FROM `Profiles` WHERE `DateLastNav` > SUBDATE(NOW(), INTERVAL 5 MINUTE) AND (`Couple`=0 OR `Couple`>`ID`)', '', '', 'mbs.gif', 0);
INSERT INTO `SiteStat` VALUES(5, 'pvi', 'Videos', 'browseVideo.php', 'SELECT COUNT(`ID`) FROM `RayMovieFiles` WHERE `Approved`=''true''', 'browseMedia.php?type=video', 'SELECT COUNT(`ID`) FROM `RayMovieFiles` WHERE `Approved`!=''true''', 'pvi.gif', 0);
INSERT INTO `SiteStat` VALUES(6, 'pls', 'Polls', 'polls.php', 'SELECT COUNT(`id_poll`) FROM `ProfilesPolls` WHERE `poll_approval`=''1''', 'post_mod_ppolls.php', 'SELECT COUNT(`id_poll`) FROM `ProfilesPolls` WHERE `poll_approval`!=''1''', 'pls.gif', 0);
INSERT INTO `SiteStat` VALUES(7, 'ntd', 'New Today', '', 'SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = ''Active'' AND (TO_DAYS(NOW()) - TO_DAYS(`DateLastNav`)) <= 1 AND (`Couple`=0 OR `Couple`>`ID`)', '', '', 'mbs.gif', 0);
INSERT INTO `SiteStat` VALUES(8, 'pmu', 'Music', 'browseMusic.php', 'SELECT COUNT(`ID`) FROM `RayMusicFiles` WHERE `Approved`=''true''', 'browseMedia.php?type=music', 'SELECT COUNT(`ID`) FROM `RayMusicFiles` WHERE `Approved`!=''true''', 'pmu.gif', 0);
INSERT INTO `SiteStat` VALUES(9, 'tps', 'Topics', 'orca', 'SELECT IF( NOT ISNULL( SUM(`forum_topics`)), SUM(`forum_posts`), 0) AS `Num` FROM `pre_forum`', '', '', 'tps.gif', 0);
INSERT INTO `SiteStat` VALUES(10, 'nwk', 'This Week', '', 'SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = ''Active'' AND (TO_DAYS(NOW()) - TO_DAYS(`DateLastNav`)) <= 7 AND (`Couple`=0 OR `Couple`>`ID`)', '', '', 'mbs.gif', 0);
INSERT INTO `SiteStat` VALUES(11, 'pvd', 'Profile Videos', '', 'SELECT `Approved` FROM `RayVideoStats`', 'javascript:window.open(''../ray/modules/video/app/admin.swf?nick={adminLogin}&password={adminPass}&url=../../../XML.php'',''RayVideoAdmin'',''width=700,height=330,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=0'');', '', 'pvi.gif', 0);
INSERT INTO `SiteStat` VALUES(12, 'pts', 'Posts', 'orca', 'SELECT IF( NOT ISNULL( SUM(`forum_posts`)), SUM(`forum_posts`), 0) AS `Num` FROM `pre_forum`', '', '', 'pts.gif', 0);
INSERT INTO `SiteStat` VALUES(13, 'nmh', 'This Month', '', 'SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = ''Active'' AND (TO_DAYS(NOW()) - TO_DAYS(`DateLastNav`)) <= 30 AND (`Couple`=0 OR `Couple`>`ID`)', '', '', 'mbs.gif', 0);
INSERT INTO `SiteStat` VALUES(14, 'tgs', 'Tags', '', 'SELECT COUNT( DISTINCT `Tag` ) FROM `Tags`', '', '', 'tgs.gif', 0);
INSERT INTO `SiteStat` VALUES(15, 'ars', 'Articles', 'articles.php', 'SELECT COUNT(`ArticlesID`) FROM `Articles`', '', '', 'ars.gif', 0);
INSERT INTO `SiteStat` VALUES(16, 'nyr', 'This Year', '', 'SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = ''Active'' AND (TO_DAYS(NOW()) - TO_DAYS(`DateLastNav`)) <= 365 AND (`Couple`=0 OR `Couple`>`ID`)', '', '', 'mbs.gif', 0);
INSERT INTO `SiteStat` VALUES(17, 'grs', 'Groups', 'grp.php', 'SELECT COUNT(`ID`) FROM `Groups` WHERE `status`=''Active''', '', '', 'grs.gif', 0);
INSERT INTO `SiteStat` VALUES(18, 'cls', 'Classifieds', 'classifieds.php?Browse=1', 'SELECT COUNT(`ID`) FROM `ClassifiedsAdvertisements` WHERE `Status`=''active''', '', '', 'cls.gif', 0);
INSERT INTO `SiteStat` VALUES(19, 'frs', 'Friends', '', 'SELECT COUNT(`ID`) FROM `FriendList` WHERE `Check`=''1''', '', '', 'frs.gif', 0);
INSERT INTO `SiteStat` VALUES(20, 'blg', 'Blogs', 'blogs.php', 'SELECT COUNT(*) FROM `Blogs`', '', '', 'pts.gif', 0);

-- --------------------------------------------------------

--
-- Table structure for table `smiles`
-- 

CREATE TABLE `smiles` (
  `ID` int(10) unsigned NOT NULL default '0',
  `code` varchar(8) NOT NULL default '',
  `smile_url` varchar(255) NOT NULL default '',
  `emoticon` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `smile` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `smiles`
-- 

INSERT INTO `smiles` VALUES (1, ':D', 'icon_biggrin.gif', 'Very Happy');
INSERT INTO `smiles` VALUES (2, ':-D', 'icon_biggrin.gif', 'Very Happy');
INSERT INTO `smiles` VALUES (3, ':grin:', 'icon_biggrin.gif', 'Very Happy');
INSERT INTO `smiles` VALUES (4, ':)', 'icon_smile.gif', 'Smile');
INSERT INTO `smiles` VALUES (5, ':-)', 'icon_smile.gif', 'Smile');
INSERT INTO `smiles` VALUES (6, ':smile:', 'icon_smile.gif', 'Smile');
INSERT INTO `smiles` VALUES (7, ':(', 'icon_sad.gif', 'Sad');
INSERT INTO `smiles` VALUES (8, ':-(', 'icon_sad.gif', 'Sad');
INSERT INTO `smiles` VALUES (9, ':sad:', 'icon_sad.gif', 'Sad');
INSERT INTO `smiles` VALUES (10, ':o', 'icon_surprised.gif', 'Surprised');
INSERT INTO `smiles` VALUES (11, ':-o', 'icon_surprised.gif', 'Surprised');
INSERT INTO `smiles` VALUES (12, ':eek:', 'icon_surprised.gif', 'Surprised');
INSERT INTO `smiles` VALUES (13, ':shock:', 'icon_eek.gif', 'Shocked');
INSERT INTO `smiles` VALUES (14, ':?', 'icon_confused.gif', 'Confused');
INSERT INTO `smiles` VALUES (15, ':-?', 'icon_confused.gif', 'Confused');
INSERT INTO `smiles` VALUES (16, ':???:', 'icon_confused.gif', 'Confused');
INSERT INTO `smiles` VALUES (17, '8)', 'icon_cool.gif', 'Cool');
INSERT INTO `smiles` VALUES (18, '8-)', 'icon_cool.gif', 'Cool');
INSERT INTO `smiles` VALUES (19, ':cool:', 'icon_cool.gif', 'Cool');
INSERT INTO `smiles` VALUES (20, ':lol:', 'icon_lol.gif', 'Laughing');
INSERT INTO `smiles` VALUES (21, ':x', 'icon_mad.gif', 'Mad');
INSERT INTO `smiles` VALUES (22, ':-x', 'icon_mad.gif', 'Mad');
INSERT INTO `smiles` VALUES (23, ':mad:', 'icon_mad.gif', 'Mad');
INSERT INTO `smiles` VALUES (24, ':P', 'icon_razz.gif', 'Razz');
INSERT INTO `smiles` VALUES (25, ':-P', 'icon_razz.gif', 'Razz');
INSERT INTO `smiles` VALUES (26, ':razz:', 'icon_razz.gif', 'Razz');
INSERT INTO `smiles` VALUES (27, ':oops:', 'icon_redface.gif', 'Embarassed');
INSERT INTO `smiles` VALUES (28, ':cry:', 'icon_cry.gif', 'Crying or Very sad');
INSERT INTO `smiles` VALUES (29, ':evil:', 'icon_evil.gif', 'Evil or Very Mad');
INSERT INTO `smiles` VALUES (30, ':twisted', 'icon_twisted.gif', 'Twisted Evil');
INSERT INTO `smiles` VALUES (31, ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes');
INSERT INTO `smiles` VALUES (32, ':wink:', 'icon_wink.gif', 'Wink');
INSERT INTO `smiles` VALUES (33, ';)', 'icon_wink.gif', 'Wink');
INSERT INTO `smiles` VALUES (34, ';-)', 'icon_wink.gif', 'Wink');
INSERT INTO `smiles` VALUES (35, ':!:', 'icon_exclaim.gif', 'Exclamation');
INSERT INTO `smiles` VALUES (36, ':?:', 'icon_question.gif', 'Question');
INSERT INTO `smiles` VALUES (37, ':idea:', 'icon_idea.gif', 'Idea');
INSERT INTO `smiles` VALUES (38, ':arrow:', 'icon_arrow.gif', 'Arrow');
INSERT INTO `smiles` VALUES (39, ':|', 'icon_neutral.gif', 'Neutral');
INSERT INTO `smiles` VALUES (40, ':-|', 'icon_neutral.gif', 'Neutral');
INSERT INTO `smiles` VALUES (41, ':neutral', 'icon_neutral.gif', 'Neutral');
INSERT INTO `smiles` VALUES (42, ':mrgreen', 'icon_mrgreen.gif', 'Mr. Green');

-- --------------------------------------------------------

-- 
-- Table structure for table `Stories`
-- 

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

-- 
-- Dumping data for table `Stories`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `Tags`
-- 

CREATE TABLE `Tags` (
  `Tag` varchar(32) NOT NULL default '',
  `ID` bigint(8) unsigned NOT NULL default '0',
  `Type` enum('profile','blog','event','photo','video','music','ad') NOT NULL default 'profile',
  PRIMARY KEY  (`Tag`,`ID`,`Type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `Tags`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `TopMenu`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `TopMenu`
-- 

INSERT INTO `TopMenu` VALUES (1, 0, 'My Account', '_My Account', 'member.php', 0, 'memb', '', '', '', 0, 0, 1, 'system', 0);
INSERT INTO `TopMenu` VALUES (2, 1, 'Account Home', '_Account Home', 'member.php', 0, 'memb', '', '', '', 0, 0, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (3, 0, 'My Mail', '_My Mail', 'mail.php', 0, 'memb', '', '', '', 0, 0, 1, 'system', 0);
INSERT INTO `TopMenu` VALUES (4, 0, 'My Profile', '_My Profile', '{memberNick}|change_status.php', 0, 'memb', '', '', '', 0, 0, 1, 'system', 0);
INSERT INTO `TopMenu` VALUES (5, 0, 'Home', '_Home', 'index.php', 0, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (6, 0, 'Members', '_Members', 'browse.php|search.php', 1, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (7, 6, 'All members', '_All Members', 'browse.php', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (8, 6, 'Search Members', '_Search', 'search.php', 2, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (28, 0, 'Videos', '_Videos', 'browseVideo.php|viewVideo.php', 4, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (10, 1, 'My Presense', '_RayPresence', 'javascript:void(0);', 1, 'memb', '', 'window.open( ''presence_pop.php'' , ''Presence'', ''width=240,height=600,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=1'');', 'return ( ''on'' == getParam( ''enable_ray'' ) );', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (11, 4, 'View My Profile', '_View Profile', '{memberLink}|{memberNick}|profile.php?ID={memberID}', 0, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (12, 3, 'Mail Write', '_Write', 'compose.php', 0, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (13, 3, 'I Blocked', '_I Blocked', 'contacts.php?show=block&list=i', 1, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (14, 3, 'Mail Sent', '_Sent', 'mail.php?mode=outbox', 2, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (15, 1, 'My Membership', '_My Membership', 'membership.php', 2, 'memb', '', '', 'return ( getParam(''free_mode'') != ''on'' );', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (16, 1, 'My Settings', '_My Settings', 'pedit.php?ID={memberID}', 3, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (17, 3, 'Mail Inbox', '_Inbox', 'mail.php?mode=inbox', 3, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (18, 3, 'Blocked Me', '_Blocked Me', 'contacts.php?show=block&list=me', 4, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (20, 4, 'Edit My Profile', '_Edit Profile', 'pedit.php?ID={memberID}', 1, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (21, 4, 'Member Photos', '_Profile Photos', 'upload_media.php?show=photo', 2, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (22, 0, 'Groups', '_Groups', 'grp.php', 6, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (23, 22, 'All Groups', '_All Groups', 'grp.php', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', 1);
INSERT INTO `TopMenu` VALUES (24, 22, 'Groups Search', '_Search', 'grp.php?action=categ', 1, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (25, 6, 'Online Members', '_Online', 'search.php?online_only=1', 1, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (26, 22, 'My Groups', '_My Groups', 'grp.php?action=mygroups', 2, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (27, 22, 'Create Group', '_Create Group', 'grp.php?action=create', 3, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (29, 28, 'All Videos', '_All Videos', 'browseVideo.php|viewVideo.php', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', 1);
INSERT INTO `TopMenu` VALUES (30, 28, 'Upload Video', '_Upload Video', 'uploadShareVideo.php', 1, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (31, 0, 'Classifieds', '_Classifieds', 'classifieds.php?Browse=1|classifieds.php|classifiedsmy.php', 7, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (32, 0, 'Chat', '_Chat', 'chat.php', 13, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (35, 31, 'Search Classifieds', '_Search', 'classifieds.php?SearchForm=1', 1, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (33, 0, 'Boards', '_Boards', 'board.php', 12, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (50, 44, 'Add Blog Post', '_Add Post', 'blogs.php?action=new_post', 5, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (34, 31, 'All Classifieds', '_All Classifieds', 'classifieds.php?Browse=1', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', 1);
INSERT INTO `TopMenu` VALUES (36, 31, 'My Classifieds', '_My Classifieds', 'classifiedsmy.php?MyAds=1', 2, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (37, 31, 'Add Classified', '_Add Classified', 'classifiedsmy.php?PostAd=1', 3, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (38, 0, 'Music', '_Music', 'browseMusic.php|viewMusic.php', 5, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (46, 44, 'Top Blogs', '_Top Blogs', 'blogs.php?action=top_blogs', 1, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (39, 38, 'All Music', '_All Music', 'browseMusic.php|viewMusic.php', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', 1);
INSERT INTO `TopMenu` VALUES (40, 38, 'Upload Music', '_Upload Music', 'uploadShareMusic.php', 1, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (41, 0, 'Photos', '_Photos', 'browsePhoto.php|viewPhoto.php', 3, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (53, 51, 'Events Calendar', '_Calendar', 'events.php?action=calendar', 1, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (42, 41, 'All Photos', '_All Photos', 'browsePhoto.php|viewPhoto.php', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', 1);
INSERT INTO `TopMenu` VALUES (43, 41, 'Upload Photos', '_Upload Photos', 'uploadSharePhoto.php', 1, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (44, 0, 'Blogs', '_Blogs', 'blogs.php', 2, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (45, 44, 'All Blogs', '_All Blogs', 'blogs.php', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', 1);
INSERT INTO `TopMenu` VALUES (47, 44, 'My Blog', '_My Blog', 'blogs.php?action=show_member_blog&ownerID={memberID}', 3, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (48, 0, 'Forums', '_Forums', 'orca/', 10, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (49, 44, 'Add Blog Category', '_Add Category', 'blogs.php?action=add_category&ownerID={memberID}', 4, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (51, 0, 'Events', '_Events', 'events.php?show_events=all&action=show|events.php', 8, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (58, 56, 'My Polls', '_My Polls', 'profile_poll.php', 1, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (52, 51, 'All Events', '_All Events', 'events.php?show_events=all&action=show', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (54, 51, 'My Events', '_My Events', 'events.php?action=show&show_events=my', 3, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (55, 51, 'Add Event', '_Add Event', 'events.php?action=new', 4, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (56, 0, 'Polls', '_Polls', 'polls.php', 9, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES (57, 56, 'All Polls', '_All Polls', 'polls.php', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (59, 0, 'Articles', '_Articles', 'articles.php', 11, 'non,memb', '', '', '', 1, 1, 1, 'top', 0);
INSERT INTO `TopMenu` VALUES(9, 0, 'Profile View', '{profileNick}', '{profileNick}|pedit.php?ID={profileID}|photos_gallery.php?ID={profileID}', 0, 'non,memb', '', '', '', 0, 0, 1, 'system', 0);
INSERT INTO `TopMenu` VALUES (60, 9, 'View Profile', '_View Profile', '{profileLink}|{profileNick}|profile.php?ID={profileID}', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (61, 9, 'Profile Video Gallery', '_Video Gallery', 'browseVideo.php?userID={profileID}', 2, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (62, 9, 'Profile Music Gallery', '_Music Gallery', 'browseMusic.php?userID={profileID}', 3, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (63, 4, 'Member Music', '_Profile Music', 'javascript:void(0);', 3, 'memb', '', 'openRayWidget(''mp3'', ''editor'', ''{memberID}'', ''{memberPass}'');', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (64, 4, 'Member Video', '_Profile Video', 'javascript:void(0);', 4, 'memb', '', 'openRayWidget(''video'', ''recorder'', ''{memberID}'', ''{memberPass}'');', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (65, 9, 'Profile Photos Gallery', '_Photos Gallery', 'browsePhoto.php?userID={profileID}', 1, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (66, 9, 'Profile Blog', '_Blog', 'blogs.php?action=show_member_blog&ownerID={profileID}|blogs.php?action=show_member_post&ownerID={profileID}', 4, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (67, 9, 'Member Guestbook', '_Guestbook', 'guestbook.php?owner={profileID}', 5, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (68, 28, 'My Videos', '_My Videos', 'browseVideo.php?userID={memberID}', 2, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (69, 41, 'My Photos', '_My Photos', 'browsePhoto.php?userID={memberID}', 2, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (70, 38, 'My Music', '_My Music', 'browseMusic.php?userID={memberID}', 2, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (71, 41, 'My Favorite Photos', '_My Favorite Photos', 'browsePhoto.php?action=fav', 3, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (72, 28, 'My Favorite Videos', '_My Favorite Videos', 'browseVideo.php?action=fav', 3, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (73, 38, 'My Favorite Music', '_My Favorite Music', 'browseMusic.php?action=fav', 3, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (74, 4, 'Customize My Profile', '_Customize Profile', 'profile_customize.php', 5, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (75, 28, 'Top Videos', '_Top Video', 'browseVideo.php?rate=top', 4, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (76, 41, 'Top Photos', '_Top Photos', 'browsePhoto.php?rate=top', 4, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (77, 38, 'Top Music', '_Top Music', 'browseMusic.php?rate=top', 4, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (78, 51, 'Search Events', '_Search', 'events.php?action=search', 2, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (79, 44, 'Top Posts', '_Top Posts', 'blogs.php?action=top_posts', 2, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (80, 4, 'My Friends', '_My Friends', 'viewFriends.php?iUser={memberID}', 6, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (81, 6, 'My Friends', '_My Friends', 'viewFriends.php?iUser={memberID}', 3, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (82, 9, 'Member Friends', '_Member Friends', 'viewFriends.php?iUser={profileID}', 6, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (83, 1, 'My Contacts', '_My Contacts', 'contacts.php', 4, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (84, 4, 'My Guestbook', '_My Guestbook', 'guestbook.php?owner={memberID}', 7, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (85, 6, 'Hot or Not', '_Hot or Not', 'rate.php', 4, 'non,memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (86, 1, 'Unregister', '_Unregister', 'unregister.php', 5, 'memb', '', '', '', 1, 1, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (87, 48, 'My Flags', '_My Flags', 'orca/#action=goto&my_flags=1', 1, 'memb', '', '', '', 0, 0, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (88, 48, 'My Topics', '_My Topics', 'orca/#action=goto&my_threads=1', 2, 'memb', '', '', '', 0, 0, 1, 'custom', 0);
INSERT INTO `TopMenu` VALUES (89, 48, 'Search', '_Search', 'orca/#action=goto&search=1', 0, 'non,memb', '', '', '', 0, 0, 1, 'custom', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `Transactions`
-- 

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

-- 
-- Dumping data for table `Transactions`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `VKisses`
-- 

CREATE TABLE `VKisses` (
  `ID` bigint(8) unsigned NOT NULL default '0',
  `Member` bigint(8) unsigned NOT NULL default '0',
  `Number` smallint(5) unsigned NOT NULL default '0',
  `Arrived` date NOT NULL default '0000-00-00',
  `New` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`ID`,`Member`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `VKisses`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `Votes`
-- 

CREATE TABLE `Votes` (
  `Member` bigint(8) NOT NULL default '0',
  `Mark` int(11) NOT NULL default '0',
  `IP` varchar(18) NOT NULL default '',
  `Date` date NOT NULL default '0000-00-00',
  UNIQUE KEY `Member` (`Member`,`IP`,`Date`),
  KEY `Member_2` (`Member`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `Votes`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `VotesPhotos`
-- 

CREATE TABLE `VotesPhotos` (
  `Member` bigint(8) NOT NULL default '0',
  `Mark` int(11) NOT NULL default '0',
  `Pic` int(11) NOT NULL default '0',
  `IP` varchar(18) NOT NULL default '',
  `Date` date NOT NULL default '0000-00-00',
  UNIQUE KEY `Member` (`Member`,`Pic`,`IP`,`Date`),
  KEY `Member_2` (`Member`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `VotesPhotos`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ZIPCodes`
-- 

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

