<?php

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
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// --------------- page variables and login



$logged['member'] = member_auth( 0 );

$_page['name_index'] 	= 19;
$_page['header'] 		= _t( "_COMPOSE_H" );
$_page['header_text'] 	= _t( "_COMPOSE_H1" );
$_page['css_name']		= 'compose.css';

$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorCompactJS;

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = DesignBoxContent( '', PageCompPageMainCode(), $oTemplConfig -> PageCompose_db_num );

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
* page code function
*/
function PageCompPageMainCode()
{
	global $site;

	$ret = '';
	$en_inbox_notify 		= getParam("enable_inbox_notify");
	$en_dest_choice 		= getParam("enable_msg_dest_choice");
	$free_mode 				= getParam( "free_mode" );

	$member['ID'] 			= (int)$_COOKIE['memberID'];
	$member['Password'] 	= $_COOKIE['memberPassword'];
	$recipientID 			= getID( $_REQUEST['ID'], 0 );

	$recipient = getProfileInfo( $recipientID );
	
	$contact_allowed 		= contact_allowed($member['ID'], $recipientID);

	// Check if credits could be used for message sending
	$could_use_credits = false;

	// Check if member can send messages
	$check_res = checkAction( $member['ID'], ACTION_ID_SEND_MESSAGE );
	if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED
		&& !$could_use_credits && !$contact_allowed )
	{
		$ret = '
			<table width="100%" cellpadding="4" cellspacing="4" border="0">
				<tr>
					<td align="center">' . $check_res[CHECK_ACTION_MESSAGE] . '</td>
				</tr>
			</table>';
		return $ret;
	}

	// Set if credits should be used anyway
	$must_use_credits = ($could_use_credits && $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED);

	//ob_start();

	$ret = '';

	if ( $_POST['action'] == "send" && strlen($_POST['text']) )
	{
		$action_result = "";

		// Check if recipient found
		if( !$recipient )
		{
			$ret = "
				<table width=\"100%\" cellpadding=\"4\" cellspacing=\"4\" align=\"center\">
					<tr>
						<td align=center>". _t("_COMPOSE_REJECT_MEMBER_NOT_FOUND") ."</td>
					</tr>
				</table>\n";
			return $ret;
		}

		// Perform sending
		$send_result = MemberSendMessage( $member, $recipient, $must_use_credits );
		
		switch ( $send_result )
		{
			case 1:
				$action_result .= _t_err( "_FAILED_TO_SEND_MESSAGE" );
				$hide_form = '0';
				break;
			case 3:
				$action_result .= _t_err( "_You have to wait for PERIOD minutes before you can write another message!", 1 );
				$hide_form = '1';
				break;
			case 5:
				$action_result .= _t_err( "_FAILED_TO_SEND_MESSAGE_BLOCK" );
				$hide_form = '0';
				break;
			case 10:
				$action_result .= _t_err( "_FAILED_TO_SEND_MESSAGE_NOT_ACTIVE" );
				$hide_form = '0';
				break;
			case 21:
				$action_result .= _t_err( "_FAILED_TO_SEND_MESSAGE_NO_CREDITS" );
				$hide_form = '0';
				break;
			default:
				$action_result .= _t_action( "_MESSAGE_SENT" );
				$hide_form = '1';
				break;
		}
    }
	$ret .= '<div class="com_box"">';
	if ( strlen($action_result) )
	{
		$ret .= $action_result;
	}


	if ( $recipient &&  $hide_form != '1' )
	{
		$ret .= '<div class="clear_both"></div>';
		$ret .= ProfileDetails( $recipient['ID'] );
		$ret .= '<div class="clear_both"></div>';
	}

	if( '1' != $hide_form )
	{
		$sSubject = (isset($_REQUEST['subject'])) ? 'Re: '.process_db_input($_REQUEST['subject']) : '';
		ob_start()
		
		?>
		<script type="text/javascript">
			<!--
			function changeDest(control)
			{
				if ( control.value == 'lovemail' )
				{
					z = document.getElementById("id0004");
					z.disabled = false;
				}
				else
				{
					xxx = document.getElementById("id0004");
					xxx.disabled = true;
				}
			}
			
			function checkForm()
			{
				var el;
				var hasErr = false;
				var fild = "";
				el = document.getElementById("inpSubj");
				if( el.value.length < 3 )
				{
					el.style.backgroundColor = "pink";
					el.style.border = "1px solid silver";
					hasErr = true;
					fild += " <?= _t('_Subject') ?>";
				}
				else
					el.style.backgroundColor = "#fff";
				
				if (hasErr)
				{
					alert( "<?= _t('_please_fill_next_fields_first') ?>!" + fild )
					return false;
				}
				else
				{
					return true;
				}
				return false;
			}
			//-->
		</script>
		
		<form name="compose_form" method="post"
		  action="<?= $_SERVER['PHP_SELF'] . ( $recipient ? "?ID={$recipient['ID']}" : "" ) ?>" onsubmit="return checkForm();">
			<table class="composeTable">
				<tr>
		<?
		if ( !$recipient )
		{
			?>
					<td class="form_label"><?= _t( "_SEND_MSG_TO" )?></td>
					<td class="form_value">
						<input class="inpMessageTo" type="text" name="ID" />
					</td>
				</tr>
				<tr>
			<?
		}
		?>
					<td class="form_label"><?= _t('_Subject') ?>:</td>
					<td class="form_value">
						<input class="inpSubj" id="inpSubj" name="mes_subject" type="text" value="<?= $sSubject; ?>" />
					</td>
				</tr>
				<tr>
					<td class="form_label"><?= _t( "_Message text" ) ?>:</td>
					<td class="form_value">
						<textarea class="blogText" id="blogText" name="text"></textarea>
					</td>
				</tr>
		<?
		
		if ( $_POST['notify'] == 'on' )
			$notify_sel = "checked";
		else
			$notify_sel = "";

		switch( $_POST['sendto'] )
		{
			case 'email':
				$email_sel = ' checked="checked" ';
				$lovemail_sel = "";
				$both_sel = "";
				break;
			case 'lovemail':
				$email_sel = "";
				$lovemail_sel = ' checked="checked"';
				$both_sel = "";
				break;
			default:
				$email_sel = "";
				$lovemail_sel = "";
				$both_sel = ' checked="checked"';
				break;
		}



		if ( 'on' == $en_dest_choice )
		{
			$javascript = ( $en_inbox_notify ? "" : "onClick=\"javascript: changeDest(this);\"" );
			
			$notify_dis = "";
			if ( !$lovemail_sel && $en_dest_choice )
			{
				$notify_sel = "";
				$notify_dis = ' disabled="disabled"';
			}
			
			?>
				<tr>
					<td>&nbsp;</td>
					<td nowrap="nowrap">
						<input type="radio" id="id0001" name="sendto" value="email"  <?= $javascript . $email_sel ?> />
						<label for="id0001"><?= _t( "_Send to e-mail" )?></label>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="radio" id="id0002" name="sendto" value="lovemail" <?= $javascript . $lovemail_sel ?> />
						<label for="id0002"><?= _t( "_Send to communicator" ) ?></label>
						
						<input type="checkbox" id="id0004" name="notify" <?= $notify_sel  . $notify_dis ?> />
						<label for="id0004"><?=  _t( "_Notify by e-mail" ) ?></label>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td nowrap="nowrap" colspan="3" align="left" style="padding-left:5px;">
						<input type="radio" id="id0003" name="sendto" value="both" <?= $javascript .  $both_sel ?> />
						<label for=id0003> <?= _t( "_both2" ) ?></label>
					</td>
				</tr>
			<?
		}
		else
		{
			?>
				<input type="hidden" name="sendto" value="lovemail" />
			<?
		}
		?>
				<tr>
					<td colspan="2" class="form_colspan">
						<input class="button" type="submit" value=" <?=_t( "_Send" ) ?>" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="action" value="send" />
		</form>
		
		<?
		$ret .= ob_get_clean();
	}
	else
	{
		$ret .= '<div style="margin:15px 0px; text-align:center;">' . _t('_to_compose_new_message', $recipient['NickName'], $recipient['ID'], $site['url'] ) . '</div>';
	}
	$ret .= '</div>';


	return $ret;
}

/**
 * Send message
 */
function MemberSendMessage( $member, $recipient, $must_use_credits = false )
{
	global $site;

	$en_dest_choice 	= getParam( "enable_msg_dest_choice" );
	$max_message_size  	= getParam( "max_inbox_message_size" );
	$max_messages  		= getParam( "max_inbox_messages" );

	// Check if recipient is active
	if( 'Active' != $recipient['Status'] )
	{
		return 10;
	}

	// Check if member is blocked
	if ( db_arr( "SELECT `ID`, `Profile` FROM `BlockList` WHERE `Profile` = {$member['ID']} AND `ID` = '{$recipient['ID']}';" ) )
	{
		return 5;
	}

	// If must use credits then check for enough amount
	if ( $must_use_credits && getProfileCredits( $member['ID'] ) < (float)$msg_credits )
	{
		return 21;
	}

// antispam ))
	if ( db_arr("SELECT `ID` FROM `Messages` WHERE `Sender` = {$member[ID]} AND date_add(`Date`, INTERVAL 1 MINUTE) > Now()") )
	{
		return 3;
	}


	// Get sender info
	$sender = getProfileInfo( $member['ID'] );

	$aPlus = array();
	$aPlus['ProfileReference'] = $sender ? '<a href="' . getProfileLink($member['ID']) . '">' . $sender['NickName'] . '</a> (' . getProfileLink($member['ID']) . ') ' : '<b>'. _t("_Visitor") .'</b>';

	// Don't send notification if message is sending to email
	if ( $_POST['notify'] && !($_POST['sendto'] == "email" || $_POST['sendto'] == "both") )
	{
		$message_text = getParam("t_Compose");
		$subject = getParam('t_Compose_subject');

		$aPlus['senderNickName'] = $sender ? $sender['NickName'] : _t("_Visitor");

		$notify_res = sendMail( $recipient['Email'], $subject, $message_text, $recipient['ID'], $aPlus );

		if ( !$notify_res )
			echo "<div class=\"err\">". _t("_Notification send failed") ."</div><br />\n";
	}

	// Send message to email
	if ( $en_dest_choice && ($_POST['sendto'] == "email" || $_POST['sendto'] == "both") )
	{
		$message_text = getParam("t_Message");
		$subject	  = process_pass_data( $_POST['mes_subject'] );

		$aPlus['MessageText'] = strmaxtextlen( clear_xss( replace_full_uris( process_pass_data( $_POST['text'] ) ) ), $max_message_size);
		
		$result = sendMail( $recipient['Email'], $subject, $message_text, $recipient['ID'], $aPlus );
	}

	// Send message to communicator
	if ( $_POST['sendto'] == "lovemail" || $_POST['sendto'] == "both" )
	{
		// Restrict with total messages count
		$messages_count = db_arr( "SELECT COUNT(*) AS `mess_count` FROM `Messages` WHERE `Recipient` = '{$recipient['ID']}'" );
		$messages_count = $messages_count['mess_count'];
		if ( ($messages_count - 1) > $max_messages )
		{
			$del_res = db_res( "SELECT `ID` FROM `Messages` WHERE `Recipient` = '{$recipient['ID']}' ORDER BY `Date` ASC LIMIT ". ($messages_count - $max_messages + 1) );
			while ( $del_arr = mysql_fetch_array($del_res) )
				db_res( "DELETE FROM `Messages` WHERE `ID` = {$del_arr['ID']}" );
		}

		// Insert message into database
		$message_text    = strmaxtextlen( addslashes( clear_xss( process_pass_data( $_POST['text'] ) ) ), $max_message_size );
		$message_subject = strmaxwordlen( process_db_input( $_POST['mes_subject'] ), 30);
		$result = db_res( "INSERT INTO `Messages` ( `Date`, `Sender`, `Recipient`, `Text`, `Subject`, `New` ) VALUES ( NOW(), {$member['ID']}, {$recipient['ID']}, '$message_text', '$message_subject', '1' )" );
	}

	// If sending successful then mark as performed action
	if ( $result )
	{
		checkAction( $member['ID'], ACTION_ID_SEND_MESSAGE, true );
		if ( $must_use_credits )
			decProfileCredits( $member['ID'], $msg_credits );
	}
	else
		return 1;

	return 0;

}
?>