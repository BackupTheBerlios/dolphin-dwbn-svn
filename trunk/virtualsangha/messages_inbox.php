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

$_page['name_index'] = 45;
$_page['css_name'] = 'compose.css';

$logged['member'] = member_auth(0);

$_page['header'] = 'Messages';
$_page['header_text'] = _t( "_INBOX_H1" );

// this is dynamic page -  send headers to do not cache this page
send_headers_page_changed();

$preview_length = 45;

// --------------- page components

$_ni = $_page['name_index'];

if( $_GET['action'] == 'delete' )
{
	$mes_id = (int)$_GET['message'];
	$delete_query = "DELETE FROM `Messages` WHERE `ID` = '$mes_id'";
	if(db_res($delete_query))
	{
		$_page_cont[$_ni]['page_main_code'] = DesignBoxContent( '', _t( '_Message successfully deleted' ), $oTemplConfig -> PageMessagesInboxMessageDeleted_db_num );
	}
}
elseif($_GET['message'] != 0)
{
	$_page_cont[$_ni]['page_main_code'] = DesignBoxContent( '', PageCompPageMainCode(), $oTemplConfig -> PageMessagesInboxMainCode_db_num );
	db_res("UPDATE `Messages` SET `New` = '0' WHERE `ID` = '" . (int)$_GET['message'] . "'");
}
else
{
	echo '<script type="text/javascript">location.href=\'mail.php?mode=inbox\';</script>';
}

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
	global	$site;
	global $short_date_format;


	$messageID	= (int)$_GET['message'];
	$memberID	= (int)$_COOKIE['memberID'];
	$sender_sql	= "SELECT `Sender` FROM `Messages` WHERE `Recipient` = '$memberID' AND `ID` = '$messageID'";
	$sender_arr	= db_arr( $sender_sql );
	$senderID 	= (int)$sender_arr['Sender'];

	$message_sql= "SELECT m.`Text`, m.`Subject`, DATE_FORMAT( m.`Date`, '$short_date_format' ) AS `Date`, p.Status FROM `Messages` AS m LEFT JOIN Profiles AS p ON m.Sender = p.ID  WHERE m.`ID` = '$messageID' AND `Recipient` = '$memberID'";

	$message_arr= db_arr($message_sql);

	$ret = '';
	$ret .= '<div class="profile_ditails_block">';
		$ret .= ProfileDetails( $senderID );
		$ret .= '<div class="clear_both"></div>';
	$ret .= '</div>';
	$ret .= '<div class="m_block">';
		$ret .= '<div class="m_subject_date">';
			$ret .= '<div class="name">';
				$ret .= _t('_Date').':';
			$ret .= '</div>';

			$ret .= '<div class="value">';
				$ret .= $message_arr['Date'];
			$ret .= '</div>';

			$ret .= '<div class="name">';
				$ret .= _t('_Subject').':';
			$ret .= '</div>';

			$ret .= '<div class="value">';
			$ret .= process_smiles( process_line_output($message_arr['Subject']) );
			$ret .= '</div>';

		$ret .= '</div>';


		$ret .= '<div class="m_text">';
			$ret .= process_html_output($message_arr['Text'], 150);
		$ret .= '</div>';
		$ret .= '<div class="clear_both"></div>';

	$ret .= '</div>';

	if( $message_arr['Status'] == 'Active' )
	{
		$ret .= '<div class="reply">';
			$ret .= '<a href="' . $site['url'] . 'compose.php?ID=' . $senderID . '&messageID=' . $messageID . '&amp;subject='.process_smiles( process_line_output($message_arr['Subject']) ).'">'. _t('_Reply') .'</a>';
		$ret .= '</div>';
		$ret .= '<div class="reply">';
			$ret .= "<a onClick=\"javascript:window.open( 'list_pop.php?action=spam&ID=" . $senderID . "', '', 'width=500,height=200,menubar=no,status=no,resizeable=no,scrollbars=no,toolbar=no,location=no' );\" href=\"javascript:void(0)\">". _t('_Spam report') ."</a>";
		$ret .= '</div>';
		$ret .= '<div class="reply">';
			$ret .= '<a href="messages_inbox.php?action=delete&message=' . $messageID . '">'. _t('_Delete') .'</a>';
		$ret .= '</div>';
		$ret .= '<div class="clear_both"></div>';
	}
	else
	{
		$ret .= '';
	}
	//$ret .= '</div>';
//####################################################
	$ret .= '<div style="position:relative; height:20px;"></div>';
	$ret .= PageCorespondence( $memberID, $senderID );
	$ret .= '<div class="clear_both"></div>';



	return $ret;

}

function PageCorespondence( $memberID, $senderID )
{
	global $site;
	global $date_format;

	$preview_leight = 25;

	$sender_nickname = getProfileInfo( $senderID ); //db_arr("SELECT `NickName` FROM `Profiles` WHERE `ID` = '$senderID'");

	$member_to_sender_query = "SELECT `Profiles`.`ID` AS pID, `Profiles`.`NickName`, `Profiles`.`Status`, `Messages`.`ID` AS mID, `New`, DATE_FORMAT( `Date`, '$date_format') as 'Date', `Subject`, LEFT( `Text`, {$preview_leight} ) AS Preview FROM `Messages` LEFT JOIN `Profiles` ON `Messages`.`Recipient` = `Profiles`.`ID` WHERE `Sender` = '" . (int)$memberID . "' AND `Recipient` = '" . (int)$senderID . "' ORDER BY `Date` DESC";
	$member_to_sender_res = db_res($member_to_sender_query);

	$member_to_sender_num = mysql_num_rows($member_to_sender_res);

	$sender_to_member_query = "SELECT `Messages`.`ID` AS mID, `New`, DATE_FORMAT( `Date`, '$date_format') as 'Date', `Subject`, LEFT( `Text`, {$preview_leight} ) AS Preview FROM `Messages` LEFT JOIN `Profiles` ON Messages.Sender = Profiles.ID WHERE `Sender` = '" . (int)$senderID . "' AND `Recipient` = '" . (int)$memberID . "' ORDER BY `Date` DESC";
	$sender_to_member_res = db_res($sender_to_member_query);

	$sender_to_member_num = mysql_num_rows($sender_to_member_res);

	$ret = '';
	$ret .= '<div class="member_to_sender">';
	if($member_to_sender_num > 0 )
	{

		$ret .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';
			$ret .= '<tr>';
				$ret .= '<td colspan="3" class="cor_title">';
					$ret .= _t('_messages_to', $sender_nickname['NickName']);
				$ret .= '</td>';
			$ret .= '</tr>';
			$ret .= '<tr>';
				$ret .= '<td width="17">';
					$ret .= '<img src="' . getTemplateIcon('convert.gif') . '" alt="" />';
				$ret .= '</td>';
				$ret .= '<td width="70">';
					$ret .= _t('_Date');
				$ret .= '</td>';
				$ret .= '<td>';
					$ret .= _t('_Subject');
				$ret .= '</td>';
			$ret .= '</tr>';

		$j_out = '0';
		while($member_to_sender_arr = mysql_fetch_assoc($member_to_sender_res))
		{
			if(strlen( $member_to_sender_arr['Subject'] ) == 0 )
			{

				$cor_subject_out = $member_to_sender_arr['Preview'];
				if ( strlen( $member_to_sender_arr['Preview'] ) >= $preview_length )
				{
					$cor_subject_out .= "...";
				}
			}
			else
			{
				$cor_subject_out = $member_to_sender_arr['Subject'];
				if ( strlen( $member_to_sender_arr['Subject'] ) >= $preview_length )
				{
					$cor_subject_out .= "...";
				}
			}

			if( '1' == $member_to_sender_arr['New'] )
			{
				$new_out = 'not_readed.gif';
				$style_add = 'style="font-weight:bold;"';
			}
			else
			{
				$new_out = 'readed.gif';
				$style_add = '';
			}

			if( ($j_out%2) != '0')
			{
				$bgcolor = '#FFFFFF';
			}
			else
			{
				$bgcolor = '#EAF6C6';
			}


			$ret .= '<tr class="tr_hover" bgcolor="' . $bgcolor . '">';
				$ret .= '<td height="20">';
					$ret .= '<img src="' . getTemplateIcon($new_out) . '" alt="" />';
				$ret .= '</td>';
				$ret .= '<td ' . $style_add . '>';
					$ret .= $member_to_sender_arr['Date'];
				$ret .= '</td>';
				$ret .= '<td ' . $style_add . ' align="left">';
					$ret .= '<a href="' . $site['url'] . 'messages_outbox.php?message=' . $member_to_sender_arr['mID']. '">';
						$ret .= process_line_output(html2txt($cor_subject_out));
					$ret .= '</a>';
				$ret .= '</td>';
			$ret .= '</tr>';
			$j_out++;
		}
		$ret .= '</table>';

	}
	else
	{
		$ret .= '<div class="no_result"><div>';
			$ret .= _t('_no_messages_to', $sender_nickname['NickName'] );
		$ret .= '</div></div>';
	}
	$ret .= '</div>';

//#################################################################################

	$ret .= '<div class="sender_to_member">';
	if( $sender_to_member_num )
	{
		$ret .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';
			$ret .= '<tr>';
				$ret .= '<td colspan="3" class="cor_title">';
					$ret .= _t('_messages_from', $sender_nickname['NickName']);
				$ret .= '</td>';
			$ret .= '</tr>';
			$ret .= '<tr>';
				$ret .= '<td width="17">';
					$ret .= '<img src="' . getTemplateIcon('convert.gif') . '" alt="" />';
				$ret .= '</td>';
				$ret .= '<td width="70">';
					$ret .= _t('_Date');
				$ret .= '</td>';
				$ret .= '<td>';
					$ret .= _t('_Subject');
				$ret .= '</td>';
			$ret .= '</tr>';

		$j_in = '0';
		while ($sender_to_member_arr = mysql_fetch_assoc($sender_to_member_res))
		{
			if(strlen( $sender_to_member_arr['Subject'] ) == 0 )
			{
				$cor_subject_in = $sender_to_member_arr['Preview'];
				if ( strlen( $sender_to_member_arr['Preview'] ) >= $preview_length )
				{
					$cor_subject_in .= "...";
				}
			}
			else
			{
				$cor_subject_in = $sender_to_member_arr['Subject'];
				if ( strlen( $sender_to_member_arr['Subject'] ) >= $preview_length )
				{
					$cor_subject_in .= "...";
				}
			}

			if( $_GET['message'] == $sender_to_member_arr['mID'] )
			{
				$new_in = 'current_mes.png';
				$style_add_in = '';
			}
			else
			{
				if( '1' == $sender_to_member_arr['New'] )
				{
					$new_in = 'not_readed.gif';
					$style_add_in = 'style="font-weight:bold;"';
				}
				else
				{
					$new_in = 'readed.gif';
					$style_add_in = '';
				}
			}

			if( ($j_in%2) != '0')
			{
				$bgcolor = '#FFFFFF';
			}
			else
			{
				$bgcolor = '#EAF6C6';
			}

			$ret .= '<tr class="tr_hover" bgcolor="' . $bgcolor . '">';
				$ret .= '<td height="20">';
					$ret .= '<img src="' . getTemplateIcon($new_in) . '" alt="" />';
				$ret .= '</td>';
				$ret .= '<td ' . $style_add_in . '>';
					$ret .= $sender_to_member_arr['Date'];
				$ret .= '</td>';
				$ret .= '<td ' . $style_add_in . ' align="left">';
					$ret .= '<a href="' . $site['url'] . 'messages_inbox.php?message=' . $sender_to_member_arr['mID'] . '">';
						$ret .= process_line_output(html2txt($cor_subject_in));
					$ret .= '</a>';
				$ret .= '</td>';
		$j_in++;
		}

		$ret .= '</table>';
	}
	else
	{
		$ret .= '<div class="no_result"></div>';
			$ret .= _t('_no_messages_from', $sender_nickname['NickName'] );
		$ret .= '</div></div>';
	}
	$ret .= '</div>';


	return $ret;
}

?>