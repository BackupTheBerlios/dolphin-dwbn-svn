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

// Authentification no required here. Just check if somebody logged in.

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );


// --------------- page variables and login

$_page['name_index']	= 47;
$_page['css_name']		= 'guestbook.css';
$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorJS;


$period = 1; // time period before user can add another record (in minutes)
$records_on_page = 16; // number of records at the page
$record_maxlength = 1600; // max length of record
$record_limit = 100; // maximum number of records in the guest book


$_page['header'] = _t("_guestbook");
/* $_page['header_text'] = ('g4' != $tmpl) ? _t("_guestbook") : "<img src=\"{$site['images']}guestbook.gif\">"; */
$_page['header_text'] =_t("_guestbook");
// --------------- page components

//$w_ex = 20;

$_ni = $_page['name_index'];

$_page_cont[$_ni]['page_main_code'] = ThisPageMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function ThisPageMainCode()
{
	global $logged;

	$ret = "";

	$member['ID'] = (int)$_COOKIE['memberID'];
	$owner = $_REQUEST['owner'] ? (int)$_REQUEST['owner'] : (int)$_COOKIE['memberID'];

	// Check if membership allows this action
	$check_res = checkAction( $member['ID'], ACTION_ID_VIEW_GUESTBOOK );
	if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED && !$logged['admin'] && $member['ID'] != $owner )
	{
		$ret .= "<br />
			<table width=\"100%\" cellpadding=1 cellspacing=1 border=0>
				<tr>
					<td class=text align=center>
						<br />". $check_res[CHECK_ACTION_MESSAGE] ."<br />
					</td>
				</tr>
			</table>\n";
		return $ret;
	}
	
	$check_res = checkAction( $owner, ACTION_ID_USE_GUESTBOOK );
	if( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED && !$logged['admin'] )
	{
		$ret .= $member['ID'] == $owner ? $check_res[CHECK_ACTION_MESSAGE] : _t_err("_This guestbook disabled by it's owner");
		return $ret;
	}
	
	if( $_GET['action'] == 'show_add' && $_GET['owner'] )
	{
		$ret .= ShowAddRecord();
		return $ret;
	}
	
	if( $_POST['action'] == 'new' && $_POST['owner'] && strlen($_POST['newrecord']) )
		$ret .= AddRecord();

	if( $_GET['action'] == 'delete' && $_GET['owner'] && (int)$_GET['delete_id'] != 0 )
		$ret .= DeleteRecord();

	$ret .= PrintGuestbook();

	return $ret;
}

function PrintGuestbook()
{
	global $logged;
	global $site;
	global $records_on_page;
	global $date_format;
	global $oTemplConfig;

	$ret = "";
	$owner = $_REQUEST['owner'] ? (int)$_REQUEST['owner'] : (int)$_COOKIE['memberID'];
	$id = ($_COOKIE['memberID'] ? $_COOKIE['memberID'] : 0);
	$from = (int)$_REQUEST['from'];

	if ( !$owner )
		return $ret;

	// Print owner's information
	$ret .= "<br />
		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr>
				<td colspan=\"2\">" .
					ProfileDetails( $owner ) .
				"</td>
			</tr>
		</table>\n";

	// Print page controls
	$records_num = db_arr("SELECT COUNT( * ) AS `rec_num` FROM `Guestbook` WHERE `Recipient` = '{$owner}'");
	if ( $records_num['rec_num'] > $records_on_page )
	{
		$ret .= "<br />
			<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
				<tr>
					<td align=\"center\" class=\"text\">";

		if( $from >= $records_on_page )
		{
			$nfrom = (0 < ($from - $records_on_page)) ? ($from - $records_on_page) : 0;
			$ret .= "
						<a href=\"guestbook.php?owner={$owner}&from={$nfrom}\">&lt;&lt;&nbsp;</a>";
		}

		$i = 0;
		$pages = 1;
		while ( $i < $records_num['rec_num'] )
		{
			if ($i == $from)
				$ret .= "
						{$pages}&nbsp;";
			else
				$ret .= "
						<a href=\"guestbook.php?owner={$owner}&from=". $i ."\">{$pages}&nbsp;</a>";
			$i = $i + $records_on_page;
			$pages++;
		}

		if ( $records_num['rec_num'] > ($from + $records_on_page) )
		{
			$nfrom = $from + $records_on_page;
			$ret .= "
						<a href=\"guestbook.php?owner={$owner}&from={$nfrom}\">&nbsp;>></a>";
		}

		$ret .= "
					</td>
				</tr>
			</table>\n";
	}

	// Print guestbook entries
	$query = "
		SELECT
			`Guestbook`.`ID`,
			DATE_FORMAT(`Date`, '$date_format' ) AS 'Date',
			`IP`,
			`Sender`,
			`Profiles`.`NickName`,
			`Recipient`,
			`Text`,
			`New`
		FROM `Guestbook`
		LEFT JOIN `Profiles` ON
			`Profiles`.`ID` = `Sender`
		WHERE
			`Recipient`='{$owner}'
		ORDER BY `Date` DESC
		LIMIT {$from}, {$records_on_page}
		";
	$records_res = db_res( $query );
	if ( $records_num['rec_num'] > 0 )
	{
		$ret .= "<br />
			<table class=\"gb_msgs_table\">
				<tr>
					<th width=\"20%\">" . _t( "_From") . "</th>
					<th width=\"80%\">" . _t( "_Text") . "</th>
				</tr>";
		
		$tr_class = 'odd';
		
		while ( $records_arr = mysql_fetch_array($records_res) )
		{
			$record_text =  $records_arr['Text'] ;
			$ret .= "
				<tr class=\"gb_msg_row_{$tr_class}\">
					<td width=\"20%\" class=\"picPosition\">" .
						get_member_thumbnail($records_arr['Sender'], 'none' ) .
						'<b><a href="'.getProfileLink($owner).'">'.$records_arr['NickName'].'</a></b><br />'.
						$records_arr['Date'] .
					"</td>
					<td width=\"80%\" valign=\"top\">";
			
			if ( $owner == $id || $logged['admin'] )
			{
				$ret .= "
							<div class=\"gb_msg_actions\">
								<a href=\"guestbook.php?owner={$owner}&action=delete&delete_id={$records_arr['ID']}\">".
									_t("_Delete") .
								"</a>
							</div>";
			}
			
			$ret .= "
							<div>{$record_text}</div>
						
					</td>
				</tr>";
			
			$tr_class = ($tr_class == 'odd') ? 'even' : 'odd';
		}
		$ret .= "
			</table>";
	}
	
	// Print add new entry link
    $ret .= "
        	<div class=\"add_link\">
				<a href=\"{$_SERVER['PHP_SELF']}?owner={$owner}&amp;action=show_add\">" . _t( "_Add record") . "</a>
			</div>";
	return $ret;
}

// Print add new entry form
function ShowAddRecord()
{
	$owner = $_REQUEST['owner'] ? (int)$_REQUEST['owner'] : (int)$_COOKIE['memberID'];
	
    $ret = "
        	<form name=\"guestbook_form\" method=\"POST\" action=\"guestbook.php?owner={$owner}\">
        	<input type=\"hidden\" name=\"owner\" value=\"{$owner}\">
        	<input type=\"hidden\" name=\"action\" value=\"new\">
			<table width=\"100%\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\">
				<tr>
					<td align=\"center\" class=\"text\"><b>" . _t( "_Add record") . "</b></td>
				</tr>
				<tr>
					<td style=\"text-align:center;\" class=\"text\">
					<textarea name=\"newrecord\" class=\"guestbookTextArea\" id=\"newrecord\"></textarea></td>
				</tr>
				<tr>
					<td align=\"center\"><input class=\"no\" name=\"add\" type=\"submit\" value=\"". _t("Add record") ."\"></td>
				</tr>
			</table>
			</form>\n";
	return $ret;
}

function AddRecord()
{
	global $record_maxlength;
	global $period;
	global $record_limit;
	global $logged;

	$ret = "";

	$record_text = addslashes(clear_xss( process_pass_data($_POST['newrecord'])));
	$record_sender = strlen($_COOKIE['memberID']) ? (int)$_COOKIE['memberID'] : "";
	$record_recipient = (int)$_REQUEST['owner'];
	$ip = ( getenv('HTTP_CLIENT_IP') ? getenv('HTTP_CLIENT_IP') : getenv('REMOTE_ADDR') );
	if ( !$record_recipient )
		return $ret;

	// Test if IP is defined
	if ( !$ip )
	{
		$ret .= "<br />
			<table width=\"100%\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\">
				<tr>
					<td class=\"text\" align=\"center\">
						<br />". _t_err("_sorry, i can not define you ip adress. IT'S TIME TO COME OUT !") ."<br />
					</td>
				</tr>
			</table>\n";
		return $ret;
	}

	// Test if last message is old enough
	$last_count = db_arr( "SELECT COUNT( * ) AS `last_count` FROM `Guestbook` WHERE `IP` = '{$ip}' AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`Date`) < {$period}*60)" );
	if ( $last_count['last_count'] != 0 )
	{
		$ret .= "<br />
			<table width=\"100%\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\">
				<tr>
					<td class=\"text\" align=\"center\">
						<br />". _t_err("_You have to wait for PERIOD minutes before you can write another message!", $period) ."<br />
					</td>
				</tr>
			</table>\n";
		return $ret;
	}

	// Restrict with total records count
	$total_count = db_arr( "SELECT COUNT(*) AS `total_count` FROM `Guestbook` WHERE `Recipient` = '{$record_recipient}'" );
	if ( ($total_count['total_count'] - 1) > $record_limit )
	{
		$del_res = db_res( "SELECT `ID` FROM `Guestbook` WHERE `Recipient` = '{$record_recipient}' ORDER BY `Date` ASC LIMIT ". ($total_count['total_count'] - $record_limit + 1) );
		while ( $del_arr = mysql_fetch_array($del_res) )
			db_res( "DELETE FROM `Guestbook` WHERE `ID` = {$del_arr['ID']}" );
	}

	// Perform insertion
	db_res( "INSERT INTO `Guestbook` SET `Date` = NOW(), `IP` = '{$ip}', `Sender` = '{$record_sender}', `Recipient` = '{$record_recipient}', `Text` = '{$record_text}', `New` = '1'" );

	return $ret;
}

function DeleteRecord()
{
	global $logged;

	$ret = "";
	$owner = (int)$_REQUEST['owner'];
	$id = ($_COOKIE['memberID'] ? $_COOKIE['memberID'] : 0);
	$delete_id = (int)$_GET['delete_id'];

	if ( !$owner || !($owner == $id || $logged['admin']) )
		return $ret;

	db_res( "DELETE FROM `Guestbook` WHERE `ID` = '$delete_id'" );

	return $ret;
}

function PrintInfo( $id = 0 )
{
	if ( $id > 0 )
	{
		$info_arr = getProfileInfo( $id );
		$info_sex = _t( "_{$info_arr['Sex']}" );
		$info_age = age( $info_arr['DateOfBirth'] );
		$ret = "<p align=\"left\">". _t("_Nickname") .": <strong>{$info_arr['NickName']}</strong><br />". _t("_Sex") .": <strong>{$info_sex}</strong><br />". _t("_DateOfBirth") .": <strong>{$info_age}</strong><br /></p>";
	}
	else
	{
		$ret = _t("_no_info");
	}

	return $ret;
}

?>