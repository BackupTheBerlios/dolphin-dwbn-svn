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

$_page['name_index'] = 130;
$_page['css_name'] = 'mail.css';

$logged['member'] = member_auth(0);
$memberID = (int)$_COOKIE['memberID'];

$_page['extra_js'] = $oTemplConfig -> sMailBoxSortingInit;
$_page['js_name'] = 'sort_table.js';

// this is dynamic page -  send headers to do not cache this page
send_headers_page_changed();

$_ni = $_page['name_index'];

$mode = $_REQUEST['mode'];
switch( $mode )
{
	case 'outbox':
		$_page['header'] = _t( "_OUTBOX_H" );
		$_page['header_text'] = _t( "_OUTBOX_H1" );
		$_page_cont[$_ni]['page_main_code'] = PageCodeOutbox();
	break;
	case 'inbox':
	default:
		$mode = 'inbox';
		$_page['header'] = _t( "_INBOX_H" );
		$_page['header_text'] = _t( "_INBOX_H1" );
		$_page_cont[$_ni]['page_main_code'] = PageCodeInbox();
}

PageCode();



function PageCodeInbox()
{
	global $memberID;
	global $dir;
	global $site;
	global $tmpl;
	global $date_format;
	
	if( $_REQUEST['do_delete'] or $_REQUEST['do_mark_new'] or $_REQUEST['do_mark_old'] )
	{
		if( $_REQUEST['do_delete'] )
			$sqlDoQuery = "DELETE FROM `Messages` WHERE";
		
		if( $_REQUEST['do_mark_new'] )
			$sqlDoQuery = "UPDATE `Messages` SET `New`='1' WHERE";
		
		if( $_REQUEST['do_mark_old'] )
			$sqlDoQuery = "UPDATE `Messages` SET `New`='0' WHERE";
		
		if( $aMsgs = $_POST['m'] and is_array( $aMsgs ) and !empty( $aMsgs ) )
		{
			foreach( $aMsgs as $msgID => $on )
			{
				$msgID = (int)$msgID;
				if( !$msgID or $on != 'on' )
					continue;
				
				db_res( "$sqlDoQuery `ID`=$msgID AND `Recipient` = $memberID" );
			}
		}
	}
	//echoDbg( $_POST );
	
	
	
	$query = "
		SELECT
			`Messages`.`ID`,
			UNIX_TIMESTAMP( `Date` ) AS `timestamp`,
			DATE_FORMAT(`Date`, '$date_format') AS `Date`,
			`Sender`,
			`Profiles`.`NickName` AS `SenderNickName`,
			`Subject`,
			`New`
		FROM `Messages`
		LEFT JOIN `Profiles` ON
			`Profiles`.`ID` = `Sender`
		WHERE `Recipient` = $memberID
		ORDER BY `Date` DESC
		";
	
	$rMsgs = db_res( $query );
	
	if( !mysql_num_rows( $rMsgs ) )
		return '<div class="no_result"><div>'._t( "_No messages in Inbox" ).'</div></div>';
	
	
	// --- get template and replace headers ---
	$aReplace = array();
	
	$aReplace['mailbox_header_img'] = '<img src="'.getTemplateIcon( 'inbox.gif' ) .'" class="mailbox_header_img" />';
	//$aReplace['summary'] = _t(
	$aReplace['flag']    = _t( '_Flag' );
	$aReplace['from']    = _t( '_From' );
	$aReplace['date']    = _t( '_Date' );
	$aReplace['subject'] = _t( '_Subject' );
	$aReplace['click_to_sort'] = _t( '_Click to sort' );
	
	$aReplace['actions_l'] = _t( "_Selected messages" ) .':';
	$aReplace['actions']   = '
		<input type="submit" class="mailbox_submit" name="do_delete"   value="'._t('_Delete').'" onclick="return confirm(\''._t('_are you sure?').'\');" />
		<input type="submit" class="mailbox_submit" name="do_mark_new" value="'._t( "_Mark as New" ).'" />
		<input type="submit" class="mailbox_submit" name="do_mark_old" value="'._t( "_Mark as old" ).'" />
		';
		
	$ret = file_get_contents("{$dir['root']}templates/tmpl_{$tmpl}/mail_box.html");
	foreach( $aReplace as $key => $val )
		$ret = str_replace( "__{$key}__", $val, $ret );
	
	$aMyTmpl = preg_split( "/\{\/?InsertRows\}/", $ret );
	$tmplRow = $aMyTmpl[1];
	$ret  = "{$aMyTmpl[0]}{InsertRows /}{$aMyTmpl[2]}";
	
	$InsertRows = '';
	$tr_class = 'odd';
	while( $aMsg = mysql_fetch_assoc( $rMsgs ) )
	{
		$sSubject = process_line_output( html2txt( $aMsg['Subject'] ));
		
		$aReplace = array();
		
		$aReplace['tr_class'] = $tr_class;
		$aReplace['new_add']  = $aMsg['New'] ? 'new' : '';
		
		$aReplace['ID']       = $aMsg['ID'];
		$aReplace['CheckBox'] = '<input type="checkbox" class="td_mailbox_checkbox" id="sel_msg_'.$aMsg['ID'].'" name="m['.$aMsg['ID'].']" value="on" />';
		$aReplace['Flag']     = '<img class="mailbox_flag_img" src="'.getTemplateIcon( $aMsg['New'] ? 'not_readed.gif' : 'readed.gif' ).'" title="'._t( $aMsg['New'] ? '_New Message' : '_Readed' ).'" />';
		$aReplace['From']     = "<!--{$aMsg['SenderNickName']}--> <a href=\"".getProfileLink($aMsg['Sender'])."\">{$aMsg['SenderNickName']}</a>";
		$aReplace['Date']     = "<!--{$aMsg['timestamp']}--> {$aMsg['Date']}";
		$aReplace['Subject']  = "<!--$sSubject--> <a href=\"{$site['url']}messages_inbox.php?message={$aMsg['ID']}\">$sSubject</a>";
		
		
		$sInsertRow = $tmplRow;
		foreach( $aReplace as $key => $val )
			$sInsertRow = str_replace( "{{$key}}", $val, $sInsertRow );
		
		$sInsertRows .= $sInsertRow;
		$tr_class = ( $tr_class == 'odd' ? 'even' : 'odd' );
	}
	
	$ret = str_replace( "{InsertRows /}", $sInsertRows, $ret );
	
	$ret = 
		'<form name="mailbox_form" action="'.$_SERVER['PHP_SELF'].'?mode=inbox" method="post">'.$ret.'</form>';
	
	return $ret;
}


function PageCodeOutbox()
{
	global $memberID;
	global $dir;
	global $site;
	global $tmpl;
	global $date_format;
	
	
	$query = "
		SELECT
			`Messages`.`ID`,
			UNIX_TIMESTAMP( `Date` ) AS `timestamp`,
			DATE_FORMAT(`Date`, '$date_format') AS `Date`,
			`Recipient`,
			`Profiles`.`NickName` AS `RecipientNickName`,
			`Subject`,
			`New`
		FROM `Messages`
		LEFT JOIN `Profiles` ON
			`Profiles`.`ID` = `Recipient`
		WHERE `Sender` = $memberID
		ORDER BY `Date` DESC
		";
	
	$rMsgs = db_res( $query );
	
	if( !mysql_num_rows( $rMsgs ) )
		return '<div class="no_result"><div>'._t( "_No messages in Outbox" ).'</div></div>';
	
	
	// --- get template and replace headers ---
	$aReplace = array();
	
	$aReplace['mailbox_header_img'] = '<img src="'.getTemplateIcon( 'outbox.gif' ) .'" class="mailbox_header_img" />';
	//$aReplace['summary'] = _t(
	$aReplace['flag']    = _t( '_Flag' );
	$aReplace['from']    = _t( '_Recipient' );
	$aReplace['date']    = _t( '_Date' );
	$aReplace['subject'] = _t( '_Subject' );
	$aReplace['click_to_sort'] = _t( '_Click to sort' );
	
	$aReplace['actions_l'] = '';
	$aReplace['actions']   = '';
		
	$ret = file_get_contents("{$dir['root']}templates/tmpl_{$tmpl}/mail_box.html");
	foreach( $aReplace as $key => $val )
		$ret = str_replace( "__{$key}__", $val, $ret );
	
	$aMyTmpl = preg_split( "/\{\/?InsertRows\}/", $ret );
	$tmplRow = $aMyTmpl[1];
	$ret  = "{$aMyTmpl[0]}{InsertRows /}{$aMyTmpl[2]}";
	
	$InsertRows = '';
	$tr_class = 'odd';
	while( $aMsg = mysql_fetch_assoc( $rMsgs ) )
	{
		$sSubject = process_line_output( html2txt( $aMsg['Subject'] ));
		
		$aReplace = array();
		
		$aReplace['tr_class'] = $tr_class;
		$aReplace['new_add']  = $aMsg['New'] ? 'new' : '';
		
		$aReplace['ID']       = $aMsg['ID'];
		$aReplace['CheckBox'] = '';
		$aReplace['Flag']     = '<img class="mailbox_flag_img" src="'.getTemplateIcon( $aMsg['New'] ? 'not_readed.gif' : 'readed.gif' ).'" title="'._t( $aMsg['New'] ? '_Not Readed' : '_Readed' ).'" />';
		$aReplace['From']     = "<!--{$aMsg['RecipientNickName']}--> <a href=\"{$site['url']}{$aMsg['RecipientNickName']}\">{$aMsg['RecipientNickName']}</a>";
		$aReplace['Date']     = "<!--{$aMsg['timestamp']}--> {$aMsg['Date']}";
		$aReplace['Subject']  = "<!--$sSubject--> <a href=\"{$site['url']}messages_outbox.php?message={$aMsg['ID']}\">$sSubject</a>";
		
		
		$sInsertRow = $tmplRow;
		foreach( $aReplace as $key => $val )
			$sInsertRow = str_replace( "{{$key}}", $val, $sInsertRow );
		
		$sInsertRows .= $sInsertRow;
		$tr_class = ( $tr_class == 'odd' ? 'even' : 'odd' );
	}
	
	$ret = str_replace( "{InsertRows /}", $sInsertRows, $ret );
	
	return $ret;
}


?>