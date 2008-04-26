<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License. 
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details. 
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin, 
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// --------------- page variables and login

$_page['name_index'] 	= 29;
$_page['css_name']		= 'tellfriend.css';

$logged['member'] = member_auth( 0, false );

$_page['header'] = _t("_Tell a friend");
$_page['header_text'] = _t("_Tell a friend");

$profileID = (int)($_GET['ID'] ? $_GET['ID'] : $_POST['ID']);

if ( $profileID > 0 )
{
	$yourID = (int)$_COOKIE['memberID'];

	if ( $yourID > 0 )
	{
		$your_arr = getProfileInfo( $yourID ); //db_arr("SELECT `NickName`, `Email` FROM `Profiles` WHERE `ID` = $yourID", 0);
		$yourName = $your_arr['NickName'];
		$yourEmail = $your_arr['Email'];
	}
}


// --------------- GET/POST actions

if ( $_POST['submit'] )
{
	if ( SendTellFriend() )
	{
		$tell_friend_text = "<b>"._t("_Email was successfully sent")."</b>";
	}
	else
	{
		$tell_friend_text = "<b style=\"color:red;\">"._t("_Email sent failed")."</b>";
	}
}
else
{
	if ( $profileID > 0)
		$tell_friend_text = _t("_TELLAFRIEND2", $site['title']);
	else
		$tell_friend_text = '';//_t("_TELLAFRIEND", $site['title']);
}


// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['invite_friend_text'] = $tell_friend_text;
$_page_cont[$_ni]['id'] = $profileID;
$_page_cont[$_ni]['your_name'] = _t("_Your name");
$_page_cont[$_ni]['your_name_val'] = $yourName;
$_page_cont[$_ni]['your_email'] = _t("_Your email");
$_page_cont[$_ni]['your_email_val'] = $yourEmail;
$_page_cont[$_ni]['friend_email'] = _t("_Friend email");
$_page_cont[$_ni]['send_letter'] = _t("_Send Letter");

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * send "tell a friend" email
 */

function SendTellFriend()
{
	global $site;
	global $profileID;
	global $yourID;
	global $yourEmail;
	global $yourName;
	global $logged;

	if ( strlen( trim($_POST['friends_emails']) ) <= 0 )
		return 0;
	if ( strlen( trim($_POST['email']) ) <= 0 )
		return 0;

	// Get notification email and subject from global settings.
	if ( $profileID )
	{
		$message = getParam( "t_TellFriendProfile" );
		$subject = getParam('t_TellFriendProfile_subject');
	}
	else
	{
		$message = getParam( "t_TellFriend" );
		$subject = getParam('t_TellFriend_subject');
	}

	$recipient = $_POST['friends_emails'];

	$headers .= "From: =?UTF-8?B?" . base64_encode( $_POST['name'] ) . "?= <{$_POST['email']}>";
	$headers = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=UTF-8\r\n" . $headers;
	$headers2 .= "-f{$_POST['email']}";

	$sLinkAdd = $logged['member'] ? 'idFriend='. (int)$_COOKIE['memberID'] : '';
	
	if ( $profileID )
		$Link = getProfileLink($profileID, $sLinkAdd);
	else
		$Link = "{$site['url']}" . ( $sLinkAdd ? "?{$sLinkAdd}" : '' );
	
	$subject = '=?UTF-8?B?' . base64_encode( $subject ) . '?=';
	
	$message = str_replace( "<Link>", $Link, $message );
	$message = str_replace( "<FromName>", $_POST['name'], $message );

	return mail( $recipient, $subject, $message, $headers, $headers2 );
}

?>