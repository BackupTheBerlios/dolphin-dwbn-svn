<?php
/***************************************************************************
*                            Orca Interactive Forum Script
*                              -----------------
*     begin                : Fr Nov 10 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Orca - Interactive Forum Script
*
* Orca is free software. This work is licensed under a Creative Commons Attribution 3.0 License. 
* http://creativecommons.org/licenses/by/3.0/
*
* Orca is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details. 
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Orca, 
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

error_reporting(E_ALL & ~E_NOTICE);

if (isset($_GET['refresh']) && $_GET['refresh'])
{
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

if (!file_exists('./inc/header.inc.php'))
{
    header ("Location: install/");
    exit;
}

require_once( './inc/header.inc.php' );

$ret = @include_once( $gConf['dir']['inc'].'util.inc.php' );
if (!$ret)
{
	echo 'File inclusion failed. <br />Did you properly edit <b>inc/header.inc.php</b> file ?';
	exit;
}

require_once( BX_DIRECTORY_PATH_CLASSES.'Thing.php' );
require_once( $gConf['dir']['classes'].'ThingPage.php' );
require_once( $gConf['dir']['classes'].'Mistake.php' );
require_once( $gConf['dir']['classes'].'BxXslTransform.php' );
require_once( $gConf['dir']['classes'].'BxDb.php' );
require_once( $gConf['dir']['classes'].'DbForum.php' );
require_once( $gConf['dir']['classes'].'Forum.php' );

require_once( $gConf['dir']['classes'].'DbLogin.php' );
require_once( $gConf['dir']['classes'].'Login.php' );

require_once( $gConf['dir']['classes'].'BxMail.php' );

require_once( $gConf['dir']['classes'].'DbAdmin.php' );
require_once( $gConf['dir']['classes'].'Admin.php' );

require_once( $gConf['dir']['base'].'xml/design.php' ); // include custom header/footer

checkMagicQuotes ();

$f = new Forum ();

$f->updateCurrentUserActivity ();

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : ''); $l = 'base64_decode';
$_GET['debug'] = isset($_GET['debug']) && $_GET['debug'] ? 1 : 0;
$_GET['trans'] = isset ($_GET['trans']) && $_GET['trans'] ? 1 : 0;

switch ($action)
{
	// admin functions

    case 'compile_langs':
        $orca_admin = new Admin ();		
        echo $orca_admin->compileLangs ();
        break;

	case 'edit_categories':
		transCheck ($f->getPageXML(0, $_GET), $gConf['dir']['xsl'] . 'edit_categories.xsl', $_GET['trans']);
		break;

	case 'edit_category_del':
		$orca_admin = new Admin ();		
		transCheck ($orca_admin->deleteCategory ($_GET['cat_id']), '', 0);
        break;

	case 'register_orca':
		$orca_admin = new Admin ();		
		echo $orca_admin->register ($_POST['license_code']);
        break;    

	case 'edit_forum_del':
		$orca_admin = new Admin ();		
		transCheck ($orca_admin->deleteForum ($_GET['forum_id']), '', 0);
		break;

	case 'edit_category':
		$orca_admin = new Admin ();
		transCheck ($orca_admin->editCategory ($_GET['cat_id']), $gConf['dir']['xsl'] . 'edit_cat_form.xsl', $_GET['trans']);
		break;

	case 'edit_category_submit':
		$orca_admin = new Admin ();		
		transCheck ($orca_admin->editCategorySubmit ($_GET['cat_id'], $_GET['cat_name']), '', 0);
		break;

	case 'edit_category_move':
		$orca_admin = new Admin ();		
		transCheck ($orca_admin->moveCat ($_GET['cat_id'], $_GET['dir']), '', 0);
		break;

	case 'edit_forum':
		$orca_admin = new Admin ();
		transCheck ($orca_admin->editForum ($_GET['forum_id'], $_GET['cat_id']), $gConf['dir']['xsl'] . 'edit_forum_form.xsl', $_GET['trans']);
		break;

	case 'edit_forum_submit':
		$orca_admin = new Admin ();		
		transCheck ($orca_admin->editFormSubmit ($_GET['cat_id'], $_GET['forum_id'], $_GET['title'], $_GET['desc'], $_GET['type']), '', 0);
		break;

	case 'reported_posts':
		$orca_admin = new Admin ();		
		transCheck ($orca_admin->getReportedPostsXML(), $gConf['dir']['xsl'] . 'forum_posts.xsl', $_GET['trans']);
		break;

    case 'lock_topic':
        $orca_admin = new Admin ();		
		transCheck ($orca_admin->lock ($_GET['topic_id']), '', 0);
        break;

	case 'list_forums_admin':
		transCheck ($f->getForumsXML($_GET['cat'], 1), $gConf['dir']['xsl'] . 'edit_cat_forums.xsl', $_GET['trans']);		
		break;
		        
	// login functions 

	case 'join_form':
		$orca_login = new Login ();
		transcheck ($orca_login->getJoinForm(), $gConf['dir']['xsl'] . 'join_form.xsl', $_GET['trans']);
		break;

	case 'login_form':
        $orca_login = new Login ();
		transcheck ($orca_login->getLoginForm(), $gConf['dir']['xsl'] . 'login_form.xsl', $_GET['trans']);
		break;
				
	case 'join_submit':
		$orca_login = new Login ();
		transCheck ($orca_login->joinSubmit (array('username' => $_GET['username'], 'email' => $_GET['email'])), '', 0);
		break;

	case 'login_submit':
		$orca_login = new Login ();
		transCheck ($orca_login->loginSubmit (array('username' => $_GET['username'], 'pwd' => $_GET['pwd'])), '', 0);
		break;

	// user functions

	case 'logout':
		transcheck ($f->logout(), '', 0);
		break;

	case 'rss_forum':
		transCheck ($f->getRssForum ($_GET['forum']), '', 0);
		break;

	case 'rss_topic':
		transCheck ($f->getRssTopic ($_GET['topic']), '', 0);
		break;
				
	case 'rss_user':
		transCheck ($f->getRssUser ($_GET['user'], $_GET['sort']), '', 0);
        break;

	case 'rss_all':
		transCheck ($f->getRssAll ($_GET['sort']), '', 0);
        break;

	case 'report_post':
		transCheck ($f->report ($_GET['post_id']), '', 0);
		break;

	case 'flag_topic':
		transCheck ($f->flag ($_GET['topic_id']), '', 0);
		break;

	case 'vote_post_good':
		transCheck ($f->votePost ($_GET['post_id'], 1), '', 0);
		break;
		
	case 'vote_post_bad':
		transCheck ($f->votePost ($_GET['post_id'], -1), '', 0);
		break;
	
	case 'get_new_post':
        transCheck ($f->getLivePostsXML(1, (int)$_GET['ts']), $gConf['dir']['xsl'] . 'live_tracker_main.xsl', $_GET['trans']);
		break;

	case 'is_new_post':
		transCheck ($f->isNewPost ((int)$_GET['ts']), '', 0);
		break;

	case 'profile':
		transCheck ($f->showProfile($_GET['user'], false), $gConf['dir']['xsl'] . 'profile.xsl', $_GET['trans']);
		break;

	case 'show_my_threads':
		transCheck ($f->getMyThreadsXML(false), $gConf['dir']['xsl'] . 'forum_topics.xsl', $_GET['trans']);
		break;

	case 'show_my_flags':
		transCheck ($f->getMyFlagsXML(false), $gConf['dir']['xsl'] . 'forum_topics.xsl', $_GET['trans']);
		break;

	case 'list_topics':
		transCheck ($f->getTopicsXML($_GET['forum'], false, (int)$_GET['start']), $gConf['dir']['xsl'] . 'forum_topics.xsl', $_GET['trans']);
		break;

	case 'list_posts':		
		transCheck ($f->getPostsXML($_GET['topic'], false), $gConf['dir']['xsl'] . 'forum_posts.xsl', $_GET['trans']);
		break;

	case 'show_hidden_post':
		transCheck ($f->getHiddenPostXML((int)$_GET['post_id'], 1), $gConf['dir']['xsl'] . 'forum_posts.xsl', $_GET['trans']);
		break;
		
	case 'hide_hidden_post':
		transCheck ($f->getHiddenPostXML((int)$_GET['post_id'], 0), $gConf['dir']['xsl'] . 'forum_posts.xsl', $_GET['trans']);
		break;
				
	case 'delete_post':
		echo $f->deletePostXML((int)$_GET['post_id'], (int)$_GET['topic_id'], (int)$_GET['forum_id']);
		break;

	case 'edit_post':
		echo $f->editPost((int)$_POST['post_id'], (int)$_POST['topic_id'], $_POST['post_text']);
		break;

	case 'edit_post_xml':
		transcheck ($f->editPostXml ((int)$_GET['post_id'], (int)$_GET['topic_id']), $gConf['dir']['xsl'] . 'edit_post.xsl', $_GET['trans']);
		break;

	case 'new_topic':
		transCheck ($f->getNewTopicXML($_GET['forum']), $gConf['dir']['xsl'] . 'new_topic.xsl', $_GET['trans']);
		break;
	
	case 'reply':
		transCheck ($f->getPostReplyXML((int)$_GET['forum'], (int)$_GET['topic']), $gConf['dir']['xsl'] . 'post_reply.xsl', $_GET['trans']);
		break;

    case 'show_search':
		transCheck ($f->getSearchXML(), $gConf['dir']['xsl'] . 'search_form.xsl', $_GET['trans']);
		break;

    case 'search':
		transCheck ($f->getSearchResultsXML($_GET['text'], $_GET['type'], (int)$_GET['forum'], $_GET['u'], $_GET['disp']), $gConf['dir']['xsl'] . 'search.xsl', $_GET['trans']);
		break;

	case 'post_reply':
		echo $f->postReplyXML($_POST);
		break;

	case 'post_new_topic':
		echo $f->postNewTopicXML($_POST);
		break;

	case 'post_success':
		transCheck ("<forum><uri>{$_GET['forum']}</uri></forum>", $gConf['dir']['xsl'] . 'default_post_success.xsl', $_GET['trans']);
		break;

	case 'access_denied':
		transCheck ('<forum_access>no</forum_access>', $gConf['dir']['xsl'] . 'default_access_denied.xsl', $_GET['trans']);
		break;

	case 'forum_index':
		transCheck ($f->getPageXML(0, $_GET), $gConf['dir']['xsl'] . 'home.xsl', $_GET['trans']);
		break;

	case 'list_forums':
		transCheck ($f->getForumsXML($_GET['cat'], 1), $gConf['dir']['xsl'] . 'cat_forums.xsl', $_GET['trans']);		
		break;

	default:
		transCheck ($f->getPageXML(1, $_GET), $gConf['dir']['xsl'] . 'home_main.xsl', $_GET['debug'] ? 0 : 1);
		break;

		 

    case 'goto':                
		switch (true)
		{
			// user functions			 
            case (isset($_GET['cat_id'])):                
				$_GET['cat'] = $_GET['cat_id'];
				$xsl = 'home_main.xsl';
				transCheck ($f->getPageXML(1, $_GET), $gConf['dir']['xsl'] . $xsl, $_GET['debug'] ? 0 : 1);
				break;
			case (isset($_GET['forum_id'])):
				transCheck ($f->getTopicsXML($_GET['forum_id'], true, (int)$_GET['start']), $gConf['dir']['xsl'] . 'forum_topics_main.xsl', $_GET['debug'] ? 0 : 1);
				break;
			case (isset($_GET['topic_id'])):
				transCheck ($f->getPostsXML($_GET['topic_id'], true), $gConf['dir']['xsl'] . 'forum_posts_main.xsl', $_GET['debug'] ? 0 : 1);
				break;
			case (isset($_GET['user'])):
				transCheck ($f->showProfile($_GET['user'], true), $gConf['dir']['xsl'] . 'profile_main.xsl', $_GET['debug'] ? 0 : 1);
				break;
		}
        break;

	case 'group_last_topics':
		transCheck ($f->getTopicsXML($_GET['forum'], false, 0), "{$dir['root']}templates/tmpl_{$tmpl}/xsl/group_last_topics.xsl", (bool)$_GET['trans']);
		break;	
}


?>
