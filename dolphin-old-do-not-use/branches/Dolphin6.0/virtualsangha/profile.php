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
require_once( BX_DIRECTORY_PATH_INC . 'members.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profile_disp.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplProfileView.php" );

$_page['name_index']	= 7;
$_page['css_name']		= 'profile_view.css';

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );

$profileID = getID( $_REQUEST['ID'] );

if( $logged['member'] )
	$memberID = (int)$_COOKIE['memberID'];
else
	$memberID = 0;

if ( !$profileID )
{
	$_page['header'] = "{$site['title']} ". _t("_Member Profile");
	$_page['header_text'] = _t("_View profile");
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = MsgBox( _t("_Profile NA") );
	PageCode();
	exit;
}

// Check if member can view profile
$contact_allowed = contact_allowed($memberID, $profileID);
$check_res = checkAction( $memberID, ACTION_ID_VIEW_PROFILES, true );

if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED
	&& !$logged['admin'] && !$logged['moderator'] && $memberID != $profileID && !$contact_allowed )
{
	$_page['header'] = "{$site['title']} "._t("_Member Profile");
	$_page['header_text'] = "{$site['title']} "._t("_Member Profile");
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = '<center>'. $check_res[CHECK_ACTION_MESSAGE] .'</center><br />';
	PageCode();
	exit;
}


$oProfile = new BxTemplProfileView( $profileID );

$_page['extra_js'] 	=
	$oTemplConfig -> sTinyMceEditorMiniJS .
	$oProfile->oVotingView->getExtraJs() .
	'<script type="text/javascript">urlIconLoading = "'.getTemplateIcon('loading.gif').'";</script>';

$_page['extra_css'] = $oProfile -> genProfileCSS( $profileID );
$p_arr              = $oProfile -> _aProfile;

if ( !($p_arr['ID'] && ($logged['admin'] || $logged['moderator'] || $oProfile -> owner || $p_arr['Status'] = 'Active') ) )
{
	$_page['header'] = "{$site['title']} ". _t("_Member Profile");
	$_page['header_text'] = "{$site['title']} ". _t("_Member Profile");
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = '<div class="no_result"><div>' . _t("_Profile NA") .'.</div></div>';
	PageCode();
	exit;
}

//Ajax loaders

if( $_GET['show_only'] )
{
	switch( $_GET['show_only'] )
	{
		case 'shareMusic':
			$sCaption = db_value( "SELECT `Caption` FROM `ProfileCompose` WHERE `Func` = 'ShareMusic'" );
			echo PageCompShareMusicContent( $sCaption, $profileID );
		break;
		case 'sharePhotos':
			$sCaption = db_value( "SELECT `Caption` FROM `ProfileCompose` WHERE `Func` = 'SharePhotos'" );
			echo PageCompSharePhotosContent($sCaption, $profileID);
		break;
		case 'shareVideos':
			$sCaption = db_value( "SELECT `Caption` FROM `ProfileCompose` WHERE `Func` = 'ShareVideos'" );
			echo PageCompShareVideosContent($sCaption, $profileID);
		break;
	}
	
	exit;
}




$_page['header']      = process_line_output( $p_arr['NickName'] ) . ": ". htmlspecialchars_adv( $p_arr['Headline'] );
//$_page['header_text'] = process_line_output( $p_arr['Headline'] );

//post comment
if( $_POST['commentsubmit'] )
	$ret .= addComment($profileID);

//delete comment
if( $_GET['action'] == 'commentdelete' )
	$ret .= deleteComment( (int)$_GET['commentID'] );

// track profile views
if ( $track_profile_view && $memberID && !$oProfile -> owner )
{
    db_res( "DELETE FROM `ProfilesTrack` WHERE `Member` = {$memberID} AND `Profile` = $profileID", 0);
    db_res( "INSERT INTO `ProfilesTrack` SET `Arrived` = NOW(), `Member` = {$memberID}, `Profile` = $profileID", 0);
}

$_ni = $_page['name_index'];

$_page_cont[$_ni]['page_main_code'] = $oProfile -> genColumns();

PageCode();



function addComment( $profileID )
{
	global $logged;
	global $oProfile;
	
	if( $logged['member'] )
		$record_sender = (int)$_COOKIE['memberID'];
	else
		return;
	
	$period = 1; // time period before user can add another record (in minutes)
	$record_maxlength = 1600; // max length of record
	
	// Test if IP is defined
	$ip = getVisitorIP();
	if( $ip == '0.0.0.0' )
		return _t_err("_sorry, i can not define you ip adress. IT'S TIME TO COME OUT !");
	
	// get record text
	$record_text = addslashes( clear_xss( trim( process_pass_data( $_POST['commenttext']))));
	if( strlen($record_text) < 2 )
		return _t_err("_enter_message_text");
	
	// Test if last message is old enough
	$last_count = db_value( "SELECT COUNT(*) FROM `ProfilesComments` WHERE `IP` = '{$ip}' AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`Date`) < {$period}*60)" );
	if( $last_count != 0 )
		return _t_err("_You have to wait for PERIOD minutes before you can write another message!", $period);

	$replyTO = (int)$_POST['replyTO'];
	
	// Perform insertion
	$query = "
		INSERT INTO `ProfilesComments` SET
			`Date` = NOW(),
			`IP` = '$ip',
			`Sender` = $record_sender,
			`Recipient` = {$oProfile -> _iProfileID},
			`Text` = '$record_text',
			`New` = '1',
			`ReplyTO` = $replyTO
		";
	db_res( $query );
}

function deleteComment( $commentID )
{
	global $logged;
	global $oProfile;

	$commentID = (int)$commentID;

	if( $oProfile -> owner || $logged['admin'] )
	{
		$del = db_res( "SELECT `ID` FROM `ProfilesComments` WHERE `ReplyTO` = '$commentID' ");
		while ( $del_arr = mysql_fetch_array($del))
			deleteComment( $del_arr['ID'] );
		
		db_res("DELETE FROM `ProfilesComments` WHERE `ID` = '$commentID'");
	}
}

?>
