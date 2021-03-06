TRUNCATE TABLE `MemberMenu`;
INSERT INTO `MemberMenu` VALUES (1, 'My_Account', '_My Account', 'member.php', 1, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (2, 'My_Profile', '_My Profile', 'profile_edit.php?ID={ID}|profile.php?ID={ID}|profile_customize.php?ID={ID}', 2, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (3, 'My_Mail', '_My Mail', 'mail.php?mode=inbox|mail.php|compose.php|messages_inbox.php|messages_outbox.php|contacts.php?show=block', 3, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (4, 'My_Photos', '_My Photos', 'upload_media.php?show=photos', 4, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (5, 'My_Videos', '_My Videos', 'upload_media.php?show=video', 5, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (6, 'My_Audio', '_My Audio', 'upload_media.php?show=audio', 6, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (7, 'My_Groups', '_My Groups', 'groups.php|group_create.php', 7, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (8, 'My_Events', '_My Events', 'events.php?action=show&amp;show_events=my|events.php?action=new', 8, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (9, 'My_Blog', '_My Blog', 'blog.php?owner={ID}', 9, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (10, 'My_Polls', '_My Polls', 'profile_poll.php', 10, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (11, 'My_Albums', '_My Albums', 'gallery.php?owner={ID}', 11, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (12, 'My_Guestbook', '_My Guestbook', 'guestbook.php?owner={ID}', 12, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (13, 'My_Greets', '_My Greets', 'contacts.php?show=greet&amp;list=i|contacts.php?show=greet&amp;list=me', 13, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (14, 'My_Faves', '_My Faves', 'contacts.php?show=hot&amp;list=i|contacts.php?show=hot&amp;list=me', 14, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (15, 'My_Friends', '_My Friends', 'contacts.php?show=friends|contacts.php?show=friends_inv&amp;list=i|contacts.php?show=friends_inv&amp;list=me', 15, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (16, 'My_Views', '_My Views', 'contacts.php?show=view&amp;list=i|contacts.php?show=view&amp;list=me', 16, 'link', 0, 'memb', '', '', '', '1');
INSERT INTO `MemberMenu` VALUES (18, 'Presence', '_RayPresence', 'javascript:void(0);', 18, 'link', 0, 'memb', '', 'window.open( ''presence_pop.php'' , ''Presence'', ''width=240,height=495,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=1'');', 'return ( ''on'' == getParam( ''enable_ray'' ) );', '1');
INSERT INTO `MemberMenu` VALUES (19, 'My_Classifieds', '_My Classifieds', 'classifiedsmy.php?MyAds=1|classifieds.php?Browse=2|classifiedsmy.php?PostAd=1', 19, 'link', 0, 'memb', '', '', '', '1');