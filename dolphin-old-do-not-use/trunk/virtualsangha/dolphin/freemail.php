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

// --------------- page variables and login

//$_page['name_index'] 	= 38;
$_page['name_index'] 	= 44;
$_page['css_name']		= 'freemail.css';

$_page['header'] = _t( "_FREEMAIL_H" );

$logged['member'] = member_auth(0, false);

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = DesignBoxContent( $_page['header'], PageCompPageMainCode(), $oTemplConfig -> PageFreeMailPop_db_num);
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

	$member['ID'] = (int)$_COOKIE['memberID'];

	if ( !$_REQUEST['ID'] )
	{
	    return _t_err( "_No member specified" );
	}

	$ID = getID($_REQUEST['ID'], 0);

	if( !$ID )
		return _t_err("_PROFILE_NOT_AVAILABLE");
	
	$profile = getProfileInfo( $ID );


	// Check if member can get email ADD CART CHECK HERE
	$check_res = checkAction( $member['ID'], ACTION_ID_GET_EMAIL );
	if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED )
	{
		$ret = '<div class="soundPop">' . $check_res[CHECK_ACTION_MESSAGE] . '</div>';
		return $ret;
	}

	// Check if profile found

	if( !$profile )
	{
		$ret = _t_err("_PROFILE_NOT_AVAILABLE");
		return $ret;
	}

	$action_result = "";
	$get_result = MemberFreeEmail( $member['ID'], $profile );

	switch ( $get_result )
	{
		case 7:
			$action_result = _t_err( "_PROFILE_NOT_AVAILABLE" );
			break;
		case 13:
			$action_result = _t_err( "_YOUR PROFILE_IS_NOT_ACTIVE" );
			break;
		case 20:
			$action_result = _t_err( "_FREEMAIL_NOT_ALLOWED" );
			break;
		case 21:
			$action_result = _t_err( "_FREEMAIL_ALREADY_SENT", $ID );
			break;
		case 25:
			$action_result = _t_err( "_FREEMAIL_BLOCK", $ID );
			break;
		case 44:
			$action_result = _t_err( "_FREEMAIL_NOT_KISSED", $ID );
			break;
		case 45:
			$action_result = _t_err("_FREEMAIL_ERROR");
			break;
		default:
			$action_result = _t( "_FREEMAIL_SENT", $profile['NickName'] );
			break;
	}

	if ( $get_result )
	{
		$_page['header_text'] = _t( "_Contact information not sent" );
	}
	else
	{
		$_page['header_text'] = _t( "_Contact information sent" );
	}


	/*
	if ( $get_result != 0 && $get_result != 25 )
		$send_form = send_form();
	else
		$send_form = "";
*/
	$ret = '<div class="soundPop">' . $action_result . '</div>' . "\n";

	return $ret;
}

function MemberFreeEmail( $recipientID, $profile )
{
	global $site;
	global $anon_mode;

	$recipientID = (int)$recipientID;
	$aRecipientArr = db_arr( "SELECT `Email` FROM `Profiles` WHERE `ID` = '$recipientID' AND `Status` = 'Active'", 0 );

	if ( db_arr( "SELECT `ID` FROM `BlockList` WHERE `ID` = '{$profile['ID']}' AND `Profile` = '$recipientID';", 0 ) )
	{
		return 25;
	}


	if ( !db_arr( "SELECT `ID` FROM `Profiles` WHERE `ID` = '{$profile['ID']}' AND `Status` = 'Active'", 0 ) )
	{
		return 7;
	}

	if ($anon_mode)
	{
		return 20;
	}

	$message = getParam( "t_FreeEmail" );
	$subject = getParam('t_FreeEmail_subject');

	if ( $recipientID )
	{
		$recipient = $aRecipientArr['Email'];
	}
	else
	{
		if ( $_GET['Email'] )
			$recipient = $_GET['Email'];
		else
			return 45;
	}

	$contact_info = "Email: {$profile['Email']}";
	if ( strlen( $profile['Phone'] ) )
		$contact_info .= "\nPhone: {$profile['Phone']}";
	if ( strlen( $profile['HomeAddress'] ) )
		$contact_info .= "\nHomeAddress: {$profile['HomeAddress']}";
	if ( strlen( $profile['HomePage'] ) )
		$contact_info .= "\nHomePage: {$profile['HomePage']}";
	if ( strlen( $profile['IcqUIN'] ) )
		$contact_info .= "\nICQ: {$profile['IcqUIN']}";

		$message = str_replace( "<ContactInfo>", $contact_info, $message );
		$message = str_replace( "<YourRealName>", ($memberID ? $memb_arr['NickName'] : _t("_Visitor")), $message );
		$message = str_replace( "<NickName>", $profile['NickName'], $message );
		$message = str_replace( "<StrID>", $profile['ID'], $message );
		$message = str_replace( "<ID>", $profile['ID'], $message );

	$aPlus = array();
	$aPlus['profileContactInfo'] = $contact_info;
	$aPlus['profileNickName'] = $profile['NickName'];
	$aPlus['profileID'] = $profile['ID'];

	$mail_ret = sendMail( $aRecipientArr['Email'], $subject, $message, $recipientID, $aPlus );

    if ( $mail_ret )
    	// Perform action
		checkAction( $memberID, ACTION_ID_GET_EMAIL, true );
    else
    	return 10;

	return 0;
}

/**
 * Prints HTML code for enter ID
 */
/*
function send_form()
{
	global $logged;

	$ret = '
		<form action="'. $_SERVER['PHP_SELF'] .'" method=get>
			<div style="position:relative; border:0px solid red; margin-left:30px; margin-top:10px;">
				<div style="position:relative; border:0px solid red; width:150px; text-align:right; float:left; padding-right:5px; margin-bottom:5px;">
					'. _t("_Enter profile ID") .':
				</div>

				<div style="postion:relative; border:0px solid red; text-align:left; width:300px; margin-bottom:5px;">
					<input class="no" type="text" size="30" name="ID" />
				</div>';
	if ( !$logged['member'] )
	{
		$ret .= '
				<div style="position:relative; width:150px; text-align:right; float:left; padding-right:5px; margin-bottom:5px;">
					'. _t("_Your email") . ':
				</div>

				<div style="postion:relative; text-align:left; width:300px; margin-bottom:5px;">
					<input class="no" type="text" size="30" name="Email" />
				</div>';
	}
	$ret .= '
				<div style="postion:relative; border:0px solid red; text-align:left; width:300px; margin-bottom:5px; margin-left: 100px;">
					<input class=no type="submit" value="'. _t("_Submit") .'" />
				</div>
			</div>
		</form>';

	return $ret;
}
*/

?>