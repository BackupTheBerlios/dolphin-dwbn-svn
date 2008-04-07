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
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$_page['name_index'] = 28;

$def_new_polls = 6;

$logged['admin'] = member_auth( 1 );

function MemberPrintPolls()
{
	global $site;

	$res = db_res( "SELECT `ID`, `Question`, `Active` FROM `polls_q` ORDER BY `Active`, `Question` DESC" );

	if ( !$res )
		return;

	echo "<table cellspacing=1 cellpadding=2 class=small width='100%'>\n";

    if ( !mysql_num_rows($res) )
    {
        echo "<tr class=panel><td align=center>No polls available.</td></tr>\n";
    }

	while ( $poll_arr = mysql_fetch_array($res) )
	{
		if ( $poll_arr['Active'] == 'on' )
			$active = "<td width=15 bgcolor=green>&nbsp;</td>";
		else
			$active = "<td width=15 bgcolor=red>&nbsp;</td>";
		$poll_question = process_line_output( $poll_arr['Question'] );

		echo "
			<tr class=panel>
				$active
				<td align=center width=15%>
					<a href=\"polls.php?edit_id={$poll_arr['ID']}\">Edit</a> |
					<a href=\"polls.php?action=delete&delete_id={$poll_arr['ID']}\">Delete</a>
				</td>
				<td aling=left>&nbsp;<a target='_blank' href='{$site['url']}poll.php?ID={$poll_arr['ID']}'>{$poll_question}</a></td>
			</tr>\n";

	}
	echo "</table>\n";
}


function MemberDeletePoll()
{
	$res = db_res( "DELETE FROM `polls_q` WHERE ID = ". (int)$_GET['delete_id'] );
	if ( $res )
		$res = db_res( "DELETE FROM `polls_a` WHERE ID = ". (int)$_GET['delete_id'] );

	return $res;
}

function MemberAddPoll()
{
	global $def_new_polls;
	global $MySQL;

	$poll_question = process_db_input( $_POST['Question'] );

	$res = db_res( "INSERT INTO `polls_q` SET `Question` = '$poll_question'" );
	$poll_id = mysql_insert_id( $MySQL->link );

	if ( $res )
	{
		for ( $i=0 ; $i < $def_new_polls ; ++$i )
		{
			$poll_answer = process_db_input( trim($_POST["Answer{$i}"]) );
			if ( strlen($poll_answer) == 0 )
				continue;
			$res = db_res( "INSERT INTO `polls_a` (`ID`, `Answer`) VALUES ($poll_id, '$poll_answer')" );
	    }
	}

	return $res;
}

function MemberEditPoll()
{
	$poll_id = (int)$_POST['edit_id'];
	$poll_question = process_db_input( $_POST['Question'] );
	$poll_active = ($_POST['Active'] ? "on" : "" );

	$res = db_res( "UPDATE `polls_q` SET `Question` = '$poll_question', `Active` = '$poll_active' WHERE ID = $poll_id" );

	$res = db_res( "SELECT `IDanswer` FROM `polls_a` WHERE `ID` = $poll_id" );
	while ( $answ_arr = mysql_fetch_array($res) )
	{
		$poll_answer = process_db_input( trim($_POST[$answ_arr['IDanswer']]) );
		if ( strlen($poll_answer) > 0 )
			db_res( "UPDATE `polls_a` SET `Answer` = '$poll_answer' WHERE `IDanswer` = {$answ_arr['IDanswer']} " );
		else
			db_res( "DELETE FROM `polls_a` WHERE `IDanswer` = {$answ_arr['IDanswer']} " );
	}

	$poll_answer = process_db_input( trim($_POST['NewAnswer']) );
	if ( strlen($poll_answer) > 0 )
	{
		$res = db_res( "INSERT INTO `polls_a` (`ID`, `Answer`) VALUES ($poll_id, '$poll_answer')" );
	}

	return $res;
}

$action_result = "";
if ( !$demo_mode && $_POST['action'] == 'new' && strlen($_POST['Question']) )
{
	if ( MemberAddPoll() )
		$action_result .= "Poll was added";
	else
		$action_result .= "Poll adding failed";
}

if ( !$demo_mode && $_POST['action'] == 'edit' && ((int)$_POST['edit_id'] != 0) && strlen($_POST['Question']) )
{
	if ( MemberEditPoll() )
		$action_result .= ( "Poll was updated" );
	else
		$action_result .= ( "Poll updating failed" );
}

if ( !$demo_mode && (int)$_GET['delete_id'] != 0 && $_GET['action'] == "delete" )
{
	if ( MemberDeletePoll() )
		$action_result .= ( "Poll was deleted" );
	else
		$action_result .= ( "Poll deleting failed" );
}

$_page['header'] = ( "Polls administration" );
$_page['header_text'] = ( "" );

TopCodeAdmin();
ContentBlockHead("Polls administration");

if ( strlen($action_result) )
	echo "<br><center><div class=\"err\">$action_result</div></center><br>\n";

MemberPrintPolls();

$m_per_row = 1;
if ( (int)$_GET['edit_id'] != 0 )
{
	$poll_arr = db_arr( "SELECT * FROM `polls_q` WHERE `ID` = ". (int)$_GET['edit_id'] );
	$res_answers = db_res( "SELECT `IDanswer`, `ID`, `Answer`, `Votes` FROM `polls_a` WHERE `ID` = ". (int)$_GET['edit_id'] ." ORDER BY `IDanswer` ASC" );
}

ContentBlockFoot();
ContentBlockHead("Polls");
?>

<form method=post action="polls.php">

<table border=0 cellspacing=1 cellpadding=0 width=100% class="text">
	<tr>
		<td colspan=<? echo $m_per_row; ?>>
			<table cellspacing=0 class=text width=100%>
				<td class=panel><img src="<? echo $site['icons']; ?>artic_papr.gif"></td>
				<td class=panel>&nbsp;Question&nbsp;</td>
				<td width=100% class=panel><input class=no name=Question size=55 value="<?= htmlspecialchars($poll_arr['Question']) ?>"></td>
			</table>
		</td>
	</tr>

	<tr class=panel>
		<td colspan=<? echo $m_per_row; ?>><br>
			<table cellspacing=0 cellpadding=2 class=small>
<?php
	if ( (int)$_GET['edit_id'] == 0 )
	{
		for ( $i=0 ; $i < $def_new_polls ; ++$i )
		{
			echo "
				<tr>
					<td>Answer ". ($i + 1) ." : &nbsp;</td>
					<td><input class=no name=Answer$i size=65></td>
				</tr>
				";
		}
	}
 	else
	{
		$i = 0;
		while ( $arr_answers = mysql_fetch_array($res_answers) )
		{
			echo "
				<tr>
					<td>Answer ". ($i + 1) ." : &nbsp;</td>
					<td><input class=no name=\"{$arr_answers['IDanswer']}\" value=\"". htmlspecialchars($arr_answers['Answer']) ."\" size=65></td>
				</tr>
				";
			++$i;
		}
		echo "
				<tr>
					<td>New Answer : &nbsp;</td>
					<td><input class=no name=NewAnswer size=65></td>
				</tr>
				";
	}
?>
			</table>
			<br>
		</td>
	</tr>
	<tr class=panel>
		<td align=center>
<?php
	if ( (int)$_GET['edit_id'] == 0 )
	{
		echo "<input type=hidden name=action value=new>\n";
		echo "<input class=no type=submit value=\"Add poll\"></td>\n";
	}
	else
	{
		$checked = ($poll_arr['Active'] == 'on' ? 'checked' : '');
		echo "Active Poll <input type=checkbox name=Active $checked> &nbsp; &nbsp; &nbsp; &nbsp;\n";
		echo "<input type=hidden name=edit_id value=". ( (int)$_GET['edit_id'] ) .">\n";
		echo "<input type=hidden name=action value=edit>\n";
		echo "<input class=no type=submit value=\"Update poll\"></td>\n";
	}
?>
		</td>
	</tr>
</table>
</form>
<?

ContentBlockFoot();
BottomCode();
?>
