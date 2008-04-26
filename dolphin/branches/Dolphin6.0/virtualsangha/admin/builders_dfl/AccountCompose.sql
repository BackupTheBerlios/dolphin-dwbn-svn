TRUNCATE TABLE `AccountCompose`;

INSERT INTO `AccountCompose` VALUES (1, 'My Photos', 'my_photos', '_My Photos', '', 'MyPhotos', '', '', 1, 0);
INSERT INTO `AccountCompose` VALUES (2, 'Site Stats', 'member_stat', '_Site Stats', '', 'MembersStats', '', '', 1, 1);
INSERT INTO `AccountCompose` VALUES (3, 'Member Info', 'member_info', '_member info', '', 'MemberInfo', '', '', 2, 0);
INSERT INTO `AccountCompose` VALUES (4, 'Contacts', 'contacts', '_contacts', '', 'Contacts', '', '', 2, 1);
INSERT INTO `AccountCompose` VALUES (5, 'Latest News', 'latest_news', '_latest news', '', 'News', '', '', 2, 2);
INSERT INTO `AccountCompose` VALUES (6, 'RSS Feed', '', '_BoonEx News', '', 'RSS', 'http://www.boonex.org/author/admin/feed#4', 'non,memb', 0, 0);
INSERT INTO `AccountCompose` VALUES (7, 'Classifieds', '', '_Classifieds', 'Classifieds', 'Classifieds', '', 'non,memb', 1, 2);
INSERT INTO `AccountCompose` VALUES (8, 'Events', '', '_Events', 'Events', 'Events', '', 'non,memb', 1, 3);
INSERT INTO `AccountCompose` VALUES (9, 'Groups', '', '_Groups', 'Groups', 'Groups', '', 'non,memb', 1, 4);
INSERT INTO `AccountCompose` VALUES (10, 'Forum RSS Feed', '', '_Forum Posts', '', 'RSS', '{SiteUrl}orca/?action=rss_user&user={NickName}#4', 'non,memb', 2, 3);
INSERT INTO `AccountCompose` VALUES (11, 'My Music Gallery', '', '_My Music Gallery', '', 'ShareMusic', '', 'non,memb', 2, 4);
INSERT INTO `AccountCompose` VALUES (12, 'My Photo Gallery', '', '_My Photo Gallery', '', 'SharePhotos', '', 'non,memb', 2, 5);
INSERT INTO `AccountCompose` VALUES (13, 'My Video Gallery', '', '_My Video Gallery', '', 'ShareVideos', '', 'non,memb', 2, 6);
