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

// --------------- page variables

$_page['name_index'] 	= 44;
$_page['css_name']		= 'vkiss.css';

$logged['member'] = member_auth(0, false);

$_page['header'] = _t("_Send virtual kiss");

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();
$_page_cont[$_ni]['body_onload'] = '';
// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
	global $_page;
	global $oTemplConfig;

	$ret = "";

	$member['ID'] = (int)$_COOKIE['memberID'];
	$member['Password'] = $_COOKIE['memberPassword'];
	$recipientID = getID( $_REQUEST['sendto'], 0 );
	$recipient = getProfileInfo( $recipientID ); //db_arr( "SELECT `ID`, `Status`, `Email` FROM `Profiles` WHERE `ID` = '" . $recipientID . "' LIMIT 1;" );
	$contact_allowed = contact_allowed($member['ID'], $recipientID);

	if ( $_REQUEST['ConfCode'] && $_REQUEST['from'] &&
		( strcmp( $_REQUEST['ConfCode'], base64_encode( base64_encode( crypt( $_REQUEST['from'], "vkiss_secret_string" ) ) ) ) == 0 ) )
	{
		$member['ID'] = (int)$_REQUEST['from'];
	}

	//
	// Check if member can send messages
	$check_res = checkAction( $member['ID'], ACTION_ID_SEND_VKISS );
	if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED
		&& !$contact_allowed )
	{
		$_page['header_text'] = _t("_Send virtual kiss3");
		$ret = "
			<table width=\"100%\" cellpadding=\"4\" cellspacing=\"4\" border=\"0\">
				<tr>
					<td align=center class=text2>". $check_res[CHECK_ACTION_MESSAGE] ."</td>
				</tr>
			</table>\n";
		return $ret;
	}

	$action_result = "";

	// Check if recipient found
	if( !$recipient )
	{
		$_page['header_text'] = _t("_Send virtual kiss3");
		$ret = "
			<table width=\"100%\" cellpadding=\"4\" cellspacing=\"4\">
				<tr>
					<td align=center class=text2>
						<form method=\"GET\" action=\"{$_SERVER['PHP_SELF']}\">
							<input class=no size=15 type=\"text\" name=\"sendto\">&nbsp;<input class=no type=\"submit\" value=\"". _t("_Send kiss") ."!\">
						</form>
					</td>
				</tr>
			</table>\n";
		return $ret;
	}

	// Perform sending
	$send_result = MemberSendVKiss( $member, $recipient );
	switch ( $send_result )
	{
		case 1:
			$action_result .= _t_err( "_VKISS_BAD" );
			break;
		case 7:
			$action_result .= _t_err( "_VKISS_BAD_COUSE_B" );
			break;
		case 10:
			$action_result .= _t_err( "_VKISS_BAD_COUSE_C" );
			break;
		case 13:
			$action_result .= _t_err( "_VKISS_BAD_COUSE_A3" );
			break;
		case 23:
			$action_result .= _t_err( "_VKISS_BAD_COUSE_X" );
			break;
		case 24:
			$action_result .= _t_err( "_VKISS_BAD_COUSE_Y" );
			break;
		default:
			$action_result .= _t( "_VKISS_OK" );
			break;
	}
	if ( $send_result == 0 )
		$_page['header_text'] = _t("_Send virtual kiss2");
	else
		$_page['header_text'] = _t("_Send virtual kiss3");

	$ret = "
		<table width=\"100%\" cellpadding=\"4\" cellspacing=\"4\">
			<tr>
				<td align=center class=text2>
					{$action_result}<br />
				</td>
			</tr>
		</table>\n";
	return DesignBoxContent( _t("_Send virtual kiss"), $ret, $oTemplConfig -> PageVkiss_db_num );
}

/**
 * Send virtual kiss
 */
function MemberSendVKiss( $member, $recipient )
{
	global $site;
	global $logged;

	// Check if recipient is active
	if( 'Active' != $recipient['Status'] )
	{
		return 7;
	}

	// block members
	if ( db_arr( "SELECT `ID`, `Profile` FROM `BlockList` WHERE `ID` = {$recipient['ID']} AND `Profile` = {$member['ID']}", 0 ) )
	{
		return 24;
	}

	// Get sender info
	$sender = getProfileInfo( $member['ID'] ); //db_arr( "SELECT `NickName` FROM `Profiles` WHERE `ID` = {$member['ID']}" );

	// Send email notification
	$message	= $logged['member'] ? getParam( "t_VKiss" ) : getParam( "t_VKiss_visitor" );
	$subject	= getParam('t_VKiss_subject');

	$ConfCode	= urlencode( base64_encode( base64_encode( crypt( $recipient['ID'], "vkiss_secret_string" ) ) ) );

	$aPlus = array();
	$aPlus['ConfCode'] = $ConfCode;
	$aPlus['ProfileReference'] = $sender ? '<a href="' . getProfileLink($member['ID']) . '">' . $sender['NickName'] . ' (' . getProfileLink($member['ID'])  . ') </a>' : '<b>' . _t("_Visitor") . '</b>';
	$aPlus['VKissLink'] = $sender ? '<a href="' . $site['url'] . 'greet.php?sendto=' . $member['ID'] . '&amp;from=' . $recipient['ID'] . '&amp;ConfCode=' . $ConfCode . '">' . $site['url'] . 'greet.php?sendto=' . $member['ID'] . '&amp;from=' . $recipient['ID'] . '&amp;ConfCode=' . $ConfCode . '</a>' : '<a href="' . $site['url'] . 'contacts.php">' . $site['url'] . 'contacts.php</a>';

	$mail_ret = sendMail( $recipient['Email'], $subject, $message, $recipient['ID'], $aPlus );

	if ( !$mail_ret )
	{
		return 10;
	}

	// Insert kiss into database
	$kiss_arr = db_arr( "SELECT `ID` FROM `VKisses` WHERE `ID` = {$member['ID']} AND `Member` = {$recipient['ID']} LIMIT 1", 0 );
	if ( !$kiss_arr )
		$result = db_res( "INSERT INTO `VKisses` ( `ID`, `Member`, `Number`, `Arrived`, `New` ) VALUES ( {$member['ID']}, {$recipient['ID']}, 1, NOW(), '1' )", 0 );
	else
		$result = db_res( "UPDATE `VKisses` SET `Number` = `Number` + 1, `New` = '1' WHERE `ID` = {$member['ID']} AND `Member` = {$recipient['ID']}", 0 );

	// If success then perform actions
	if ( $result )
		checkAction( $member['ID'], ACTION_ID_SEND_VKISS, true );
	else
		return 1;

	return 0;
}

?>