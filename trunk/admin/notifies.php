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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );

function PrintStatus()
{
	global $site;

	$queue_not_empty = false;

	echo "
		<center>
		<table cellspacing=2 cellpadding=2 class=text border=0>
			<tr class=header align=\"center\">
				<td colspan=3>Queue status:</td>
			</tr>";

	// Select count of emails in queue per one message
	$query = "SELECT NotifyMsgs.ID, NotifyMsgs.Subj, COUNT(NotifyQueue.Email) AS `count_per_msg` FROM NotifyMsgs INNER JOIN NotifyQueue ON (NotifyQueue.Msg = NotifyMsgs.ID) GROUP BY NotifyMsgs.ID";
	$res = db_res($query);
	if ( !mysql_num_rows($res) )
	{
		echo "
			<tr>
				<td colspan=3 align=center><b><font color=red>There are no emails in queue</font></b></td>
			</tr>";
	}
	else
	{
		while ($arr = mysql_fetch_array($res))
		{
			echo "
			<tr class=table align=\"center\">
				<td>ID: <b>{$arr['ID']}</b> </td>
				<td align=left>Subj: <b>{$arr['Subj']}</b> </td>
				<td>  <b>{$arr['count_per_msg']} emails</b> </td>
			</tr>";
			$queue_not_empty = true;
		}
	}
	echo "
		</table>
		<hr>
		<table cellspacing=2 cellpadding=2 class=text>
			<tr class=header>
				<td colspan=3 align=\"center\">Cupid mails status:</td>
			</tr>";

    // Select count of messages in queue for cupid mails
	$arr_count = db_arr("SELECT COUNT(*) `count` FROM `NotifyQueue` WHERE `From` = 'ProfilesMsgText'");
	$numrows = $arr_count['count'];
	if ( !$numrows )
	{
		echo "
			<tr>
				<td colspan=3 align=center><b><font color=red>There are no emails in queue</font></b></td>
			</tr>";
	}
	else
	{
		echo "
			<tr class=table>
				<td></td>
				<td align=\"center\"><b>{$numrows} emails</b></td>
				<td></td>
			</tr>";
		$queue_not_empty = true;
    }
	echo "
		</table>
		<hr>";

	// If queue is not empty then show link to clear it
	if ( $queue_not_empty )
	{
		echo "
		<table class=\"text\" width=\"50%\" style=\"height: 30px;\">
			<tr class=\"table\">
				<td align=\"center\" colspan=\"3\">
					<a href=\"{$site['url_admin']}notifies.php?action=empty\">Empty Queue</a>
				</td>
			</tr>
		</table>
		<hr>";
	}

	echo "
		</center>";
}

function QueueMessage()
{
	$msg_id = (int)$_POST['msgs_id'];
	$ret = "";

	$query = "SELECT `ID` FROM `NotifyMsgs` WHERE `ID` = $msg_id";
	$arr_arr = db_arr( $query );
	if ( !$arr_arr )
	{
		return "Failed to queue emails (ID: {$msg_id}).";
	}

	// Initially no emails queued
	$emails = 0;

	// Send to all emails in NotifyEmails table
	if ( $_POST['send_to_subscribers'] == 'on' )
	{
		$res_eml = db_res( "SELECT `ID` FROM `NotifyEmails`" );
		while ($arr_eml = mysql_fetch_array($res_eml))
		{
			$res = db_res("SELECT `NotifyEmails`.`Email` FROM `NotifyQueue` INNER JOIN `NotifyEmails` ON (`NotifyQueue`.`Email` = `NotifyEmails`.`ID`) WHERE `NotifyQueue`.`Email` = {$arr_eml['ID']} AND `NotifyQueue`.`Msg` = $msg_id AND `From` = 'NotifyEmails'");
			if ( $res && ($arr = mysql_fetch_array($res)) )
			{
				$ret .= "Email(notify) <b><u>{$arr['Email']}</u></b> already exists in queue.<br>";
				continue;
			}

			$res = db_res("INSERT INTO `NotifyQueue` SET `Email` = {$arr_eml['ID']}, `Msg` = $msg_id, `From` = 'NotifyEmails', `Creation` = NOW()");
			if ( !$res )
			{
				$ret .= "Email <b><u>{$arr['Email']}</u></b> was not added to queue.<br>";
				continue;
			}

			$emails++;
		}
	}

	// Send to all profiles
	if ( $_POST['send_to_members'] == 'on' )
	{
		// Sex filter
		$apply_filter = false;
		$arrpd = db_arr( "SELECT `extra` FROM `ProfilesDesc` WHERE `name` = 'Sex'" );
		$vals = preg_split ("/[,\']+/", $arrpd['extra'], -1, PREG_SPLIT_NO_EMPTY);
		foreach ( $vals as $v )
		{
			if ( !isset($_POST["sex_{$v}"]) || $_POST["sex_{$v}"] != 'on' )
			{
				$apply_filter = true;
				break;
			}
		}
		if ( $apply_filter )
		{
			$sex_string_buffer = "'-1'";
			foreach ( $vals as $v )
			{
				if ( isset($_POST["sex_{$v}"]) && $_POST["sex_{$v}"] == 'on' )
					$sex_string_buffer .= ",'{$v}'";
			}
		}
		else
		{
			$sex_filter_sql = '';
		}

		// Age filter
		$age_start = (int)$_POST['age_start'];
		$age_end = (int)$_POST['age_end'];
		if ( $age_start && $age_end )
		{
			$date_start = (int)( date( "Y" ) - $age_start );
			$date_end = (int)( date( "Y" ) - $age_end - 1 );
			$date_start = $date_start . date( "-m-d" );
			$date_end = $date_end . date( "-m-d" );
			$age_filter_sql = "AND (TO_DAYS(`DateOfBirth`) BETWEEN TO_DAYS('{$date_end}') AND (TO_DAYS('{$date_start}')+1))";
		}
		else
		{
			$age_filter_sql = '';
		}

		// Country filter
		if ( $_POST['country'] != 'all' )
		{
			$country = process_db_input($_POST['country']);
			$country_filter_sql = "AND `Country` = '{$country}'";
		}
		else
		{
			$country_filter_sql = '';
		}

		// Membership filter
		if ( $_POST['membership'] != 'all' )
		{
			$membershipID = (int)$_POST['membership'];
		}
		else
		{
			$membershipID = -1;
		}

		$res_eml = db_res("SELECT `ID` FROM `Profiles` WHERE `Status` <> 'Unconfirmed' AND `EmailNotify` = 'NotifyMe' $sex_filter_sql $age_filter_sql $country_filter_sql");
		while ($arr_eml = mysql_fetch_array($res_eml))
		{
			// Dynamic membership filter
			$membership_info = getMemberMembershipInfo($arr_eml['ID']);
			if ( $membershipID != -1 && $membership_info['ID'] != $membershipID )
				continue;

			$res = db_res("SELECT `Profiles`.`Email` FROM `NotifyQueue` INNER JOIN `Profiles` ON (`NotifyQueue`.`Email` = `Profiles`.`ID`) WHERE `NotifyQueue`.`Email` = {$arr_eml['ID']} AND `NotifyQueue`.`Msg` = $msg_id  AND `From` = 'Profiles' ");
			if ( $res && ($arr = mysql_fetch_array($res)) )
			{
				$ret .= "Email(profiles) <b><u>{$arr['Email']}</u></b> already exists in queue.<br>";
				continue;
			}

			$res = db_res("INSERT INTO `NotifyQueue` SET `Email` = {$arr_eml['ID']}, `Msg` = $msg_id, `From` = 'Profiles', `Creation` = NOW()");
			if ( !$res )
			{
				$ret .= "Email <b><u>{$arr['Email']}</u></b> was not added to queue.<br>";
				continue;
			}

			$emails++;
		}
	}

	$ret .= (int)$emails." emails was successfully added to queue.";
	return $ret;
}


function AddMessage()
{
	$query = "INSERT INTO `NotifyMsgs` SET `Subj` = '". process_db_input($_POST['subj']) ."', `Text` = '". process_db_input(html2txt($_POST['body_html'])) ."',`HTML` = '". process_db_input($_POST['body_html']) ."'";

	$res = db_res( $query );
	if ( $res )
		$_POST['msgs_id'] = mysql_insert_id();

	return $res;
}

function UpdateMessage()
{
	$query = "UPDATE `NotifyMsgs` SET `Subj` = '". process_db_input($_POST['subj']) ."', `Text` = '". process_db_input(html2txt($_POST['body_html'])) ."', `HTML` = '". process_db_input($_POST['body_html']) ."' WHERE ID = '". (int)$_POST['msgs_id'] ."'";

	$res = db_res( $query );

	return $res;
}

function PreviewMessage()
{
	global $site;

	$body_html = process_pass_data( $_POST['body_html'] );

	ContentBlockHead("Preview");
?>
		<center>
			<iframe id="IFramePreview" name="IFramePreview" frameborder="1" scrolling="yes" height="200" width="500" src="<?= $site['url_admin'] ?>notify_preview.php">
			</iframe>
		</center>

		<form name="form_preview" action="<?= $site['url_admin'] ?>notify_preview.php" method="POST" target="IFramePreview">
			<input type="hidden" name="post_data" value="<?= htmlspecialchars($body_html) ?>">
		</form>

		<script language="JavaScript">
			<!--
			document.forms['form_preview'].submit();
			-->
		</script>
<?
	ContentBlockFoot();
}

function DeleteMessage()
{
	$query = "DELETE FROM `NotifyMsgs` WHERE `ID` = ". (int)$_POST['msgs_id'];
	if ( !($res = db_res( $query )) )
		return $res;

	$_POST['msgs_id'] = 0;

	$query = "DELETE FROM `NotifyQueue` WHERE `Msg` = ". (int)$_POST['msgs_id'];
	$res = db_res( $query );

	return $res;
}

function EmptyQueue()
{
	return db_res("TRUNCATE TABLE `NotifyQueue`");
}

$logged['admin'] = member_auth( 1 );

$_page['header'] = "Notify Letter";
$_page['header_text'] = "Send Notify letters";

TopCodeAdmin();

ContentBlockHead("Mass Mailer Status");

if ( $_POST['queue_message'] )
	$action = 'queue';
if ( $_POST['add_message'] )
	$action = 'add';
if ( $_POST['update_message'] )
	$action = 'update';
if ( $_POST['delete_message'] )
	$action = 'delete';
if ( $_POST['preview_message'] )
	$action = 'preview';
if ( $_REQUEST['action'] == 'empty' )
	$action = 'empty';

// demo mode check ommited here on purpose
if ( $action == 'add' && strlen($_POST['body_html']) )
{
	if ( AddMessage() )
		$action_result .= "Message was added.<br>";
	else
		$action_result .= "Message was not added.<br>";
}

if ( !$demo_mode && $action == 'update' && $_POST['msgs_id'] && strlen($_POST['body_html']) )
{
	if ( UpdateMessage() )
		$action_result .= "Message was updated.<br>";
	else
		$action_result .= "Message was not updated.<br>";
}

if ( !$demo_mode && $action == 'delete' && $_POST['msgs_id'] )
{
	if ( DeleteMessage() )
		$action_result .= "Message was deleted.<br>";
	else
		$action_result .= "Message was not deleted.<br>";
}

if ( !$demo_mode && $action == 'empty' )
{
	if ( EmptyQueue() )
		$action_result .= "Queue empty.<br>";
	else
		$action_result .= "Queue emptying failed.<br>";
}

if ( !$demo_mode && $action == 'queue' && $_POST['msgs_id'] )
{
	$action_result .= QueueMessage();
}

if ( strlen($action_result) )
	echo "<br><center><div class=\"err\">$action_result</div></center><br>\n";

PrintStatus();

// Print combobox with all messages
?>
	<form name="form_messages" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
		<input type="hidden" name="action" value="view">
		<center class="text">Messages:&nbsp;
			<select name=msgs_id onChange="javascript: document.forms['form_messages'].submit();">
				<option value=0>NONE</option>
<?

$res_msgs = db_res("SELECT `ID`, `Subj`, `Text`, `HTML`, (`ID` = ". (int)$_POST['msgs_id'] ." OR `Subj` = '". process_db_input($_POST['subj']) ."' ) AS `Selected` FROM `NotifyMsgs`");
while ( $arr_msgs = mysql_fetch_array($res_msgs) )
{
	$sel = ($arr_msgs['Selected'] ? "selected" : "");
	echo "
				<option value=\"{$arr_msgs['ID']}\" $sel>{$arr_msgs['Subj']}</option>";
}

?>
			</select>
		</center>
	</form>

<?
ContentBlockFoot();
ContentBlockHead( "E-mail message" );

$body_html = "";
$body_text = "";
$subject = "";

if ( $_POST['body_html'] && $_POST['action'] != 'delete' )
{
	$body_html = process_pass_data( $_POST['body_html'] );
	$body_text = html2txt($body_html);
	$subject = process_pass_data( $_POST['subj'] );
}
elseif ( $_POST['msgs_id'] )
{
	$arr = db_arr( "SELECT * FROM `NotifyMsgs` WHERE `ID` = ". (int)$_POST['msgs_id'] );
	$body_html = $arr['HTML'];
	$body_text = $arr['Text'];
	$subject = $arr['Subj'];
}

?>
<form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
<center>
<table cellspacing=2 cellpadding=2 class=text border="0">
	<tr class=table>
		<td align="left">&nbsp;E-mail subject&nbsp;</td>
		<td align="left"><input class=no type=text size=60 name="subj" value="<?= htmlspecialchars($subject) ?>"></td>
	</tr>

	<tr class=table>
		<td valign=top align="left">&nbsp;HTML e-mail body&nbsp;</td>
		<td align="left"><textarea cols="55" rows="10" name="body_html" style="text-align: justify;"><?= htmlspecialchars($body_html) ?></textarea></td>
	</tr>
<?

if ( strlen($body_html) )
{
?>
	<tr class="table">
		<td valign="top" align="left" width="120">&nbsp;Text e-mail body&nbsp;</td>
		<td width="390" align="justify" style="border: 1px solid #666666"><?= process_text_output($body_text) ?></td>
	</tr>
<?
}

?>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan=2 align=center>
<?

if ( $_POST['msgs_id'] )
{
?>
			<input class="text" type="submit" style="width: 120px" name="add_message" value="Add message">
			<input class="text" type="submit" style="width: 120px" name="update_message" value="Update message">
			<input class="text" type="submit" style="width: 120px" name="delete_message" value="Delete message">
			<input class="text" type="submit" style="width: 120px" name="preview_message" value="Preview message">
<?
}
else
{
?>
			<input class="text" type="submit" style="width: 140px" name="add_message" value="Add message">
			<input class="text" type="submit" style="width: 140px" name="preview_message" value="Preview message">
<?
}
?>
			<input class="text" type="hidden" name="msgs_id" value="<?= $_POST['msgs_id'] ?>">
		</td>
	</tr>
</table>
</center>
</form>

<?
ContentBlockFoot();


if ( $_POST['msgs_id'] )
{


ContentBlockHead("Queue message");
?>

<script type="text/javascript" language="JavaScript">
	<!--

	function setControlsState( enabled )
	{
		var state_disabled = ( enabled ? false : true );
<?
	$arrpd = db_arr( "SELECT `extra` FROM `ProfilesDesc` WHERE `name` = 'Sex'" );
	$sex_vals = preg_split ("/[,\']+/", $arrpd['extra'], -1, PREG_SPLIT_NO_EMPTY);
	foreach ( $sex_vals as $v )
	{
		echo "
		document.forms['form_queue'].elements['sex_{$v}'].disabled = state_disabled;";
	}
?>
		document.forms['form_queue'].elements['age_start'].disabled = state_disabled;
		document.forms['form_queue'].elements['age_end'].disabled = state_disabled;
		document.forms['form_queue'].elements['country'].disabled = state_disabled;
		document.forms['form_queue'].elements['membership'].disabled = state_disabled;
	}

	-->
</script>

<form id="form_queue" action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
<center>
<table cellpadding="0" cellspacing="0" class="text" border="0" width="450">
	<tr class="panel">
		<td align="left" valign="middle" style="padding: 2px;">
		 	<input class="text" type="checkbox" name="send_to_subscribers" id="id_subscribers" checked style="vertical-align: middle;" />
		 	&nbsp;<label for="id_subscribers"><b>Send to subscribers</b></label>
		</td>
	</tr>
	<tr class="panel">
		<td align="left" valign="middle" style="padding: 2px;">
		 	<input class="text" type="checkbox" name="send_to_members" id="id_members" checked style="vertical-align: middle;" onclick="javascript: setControlsState( this.checked ? true : false );">
		 	&nbsp;<label for="id_members"><b>Send to members</b></label>
		</td>
	</tr>
	<tr class="table">
		<td align="left">
		 	<table cellpadding="2" cellspacing="0" class="text" id="members_table" style="border: 1px solid silver;" width="100%">
		 		<tr class="table">
		 			<td align="right" valign="middle">Sex:</td>
		 			<td width="12"></td>
		 			<td align="left" valign="middle">
<?
	foreach ( $sex_vals as $v )
	{
		echo "
						<input class=\"text\" type=\"checkbox\" name=\"sex_{$v}\" id=\"id_sex_{$v}\" checked=\"checked\" style=\"vertical-align: middle;\">&nbsp;<label for=\"id_sex_{$v}\">{$v}</label>";
	}
?>
		 			</td>
		 		</tr>
		 		<tr class="table">
		 			<td align="right" valign="middle">Age:</td>
		 			<td width="12"></td>
		 			<td align="left" valign="middle">
		 				from&nbsp;<select class="text" name="age_start">
<?
	$gl_search_start_age = (int)getParam( 'search_start_age' );
	$gl_search_end_age = (int)getParam( 'search_end_age' );
	for ( $i = $gl_search_start_age ; $i <= $gl_search_end_age ; $i++ )
	{
		$sel = ($i == $gl_search_start_age ? 'selected' : '');
		echo "
									<option value=\"$i\" $sel>$i</option>";
	}
?>
									</select>
						to&nbsp;<select class="text" name="age_end">
<?
	for ( $i = $gl_search_start_age ; $i <= $gl_search_end_age ; $i++ )
	{
		$sel = ($i == $gl_search_end_age ? 'selected' : '');
		echo "
									<option value=\"$i\" $sel>$i</option>";
	}
?>
						</select>
		 			</td>
		 		</tr>
		 		<tr class="table">
		 			<td align="right" valign="middle">Country:</td>
		 			<td width="12"></td>
		 			<td align="left" valign="middle">
		 				<select class="text" name="country">
		 					<option value="all" selected>All</option>
<?
	foreach ( $prof['countries'] as $key => $value )
	{
		echo "
							<option value=\"{$key}\">". _t('__'.$value) ."</option>";
	}
?>
						</select>
		 			</td>
		 		</tr>
		 		<tr class="table">
		 			<td align="right" valign="middle">Membership level:</td>
		 			<td width="12"></td>
		 			<td align="left" valign="middle">
		 				<select class="text" name="membership">
		 					<option value="all" selected>All</option>
<?
	$memberships_arr = getMemberships();
	foreach ( $memberships_arr as $membershipID => $membershipName )
	{
		if ( $membershipID == MEMBERSHIP_ID_NON_MEMBER )
			continue;
		echo "
							<option value=\"{$membershipID}\">{$membershipName}</option>";
	}
?>
						</select>
		 			</td>
		 		</tr>
		 	</table>
		</td>
	</tr>
	<tr class="table">
		<td align="center" style="padding: 5px;">
			<input class="text" type="hidden" name="msgs_id" value="<?= $_POST['msgs_id'] ?>">
			<input class="text" type="submit" style="width: 140px" name="queue_message" value="Queue message">
		</td>
	</tr>
</table>
</center>
</form>

<?
ContentBlockFoot();
}

if ( !$demo_mode && $action == 'preview' && strlen($_POST['body_html']) )
{
	PreviewMessage();
}

BottomCode();
?>
