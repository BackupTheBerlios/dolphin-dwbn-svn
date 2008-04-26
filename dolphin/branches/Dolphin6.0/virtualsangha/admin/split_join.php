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

/*
 * Page for displaying and editing profile fields.
 */

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );


// Check if administrator is logged in.  If not display login form.
$logged['admin'] = member_auth( 1 );

$_page['header'] = 'Split Join Form';
$_page['css_name'] = 'profile_fields.css';


TopCodeAdmin();
ContentBlockHead("Split join form");
	echo main_code();
ContentBlockFoot();
BottomCode();


function main_code()
{
	global $site;
	global $modified;

	switch ( $_REQUEST['action'] )
	{
		// menu to move field to another join page
		case 'edit':
			// get element name, etc
			$id = (int)$_GET['id'];
			// get properties of field by it's id
			$query = "SELECT `name`, `namedisp`, `join_page`, FLOOR(`join_page` / 1000) as `jp`, `check` FROM `ProfilesDesc` WHERE `ID` = '$id'";
			$field_arr = db_arr($query);
			if ( !$field_arr )
				return "";

			// calculate number of join pages
			$query = "SELECT FLOOR(`join_page` / 1000) as `jp` FROM `ProfilesDesc` WHERE `visible` & 2 AND (FIND_IN_SET('0',`show_on_page`) OR FIND_IN_SET('3',`show_on_page`)) AND `join_page` >= 1000 GROUP BY `jp`";
			$respd = db_res($query);
			$pages_num = mysql_num_rows($respd);
			$pages_num++;

			$content = "
				<form name=\"j_editform\" id=\"j_editform\" action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\">
					<table width=\"100%\">
						<tr>
							<td valign=\"top\">
								Field name - " . $field_arr['name'] . "<br />
								Field caption - " . _t($field_arr['namedisp']) . "<br />
							</td>
							<td valign=\"top\">
								select part of join page:&nbsp;
								<input type=\"hidden\" name=\"action\" value=\"change_page\">
								<input type=\"hidden\" name=\"id\" value=\"$id\">
								<select name=\"new_jp\" id=\"j_page\" onchange=\"javascript: document.forms['j_editform'].submit();\">
									<option value=\"0\">invisible</option>";

			for ($i = 1; $i <= $pages_num; $i++)
			{
				$selected = $i == $field_arr['jp'] ? 'selected' : '';
				$content .= "
									<option value=\"$i\" $selected>join page, part $i</option>";
			}

			$content .= "
								</select>
							</td>
							<td valign=\"top\">
								<a href=\"{$_SERVER['PHP_SELF']}\">Back</a>
							</td>
						</tr>
					</table>
				</form>";

			return $content;

	// reset join form
	case 'reset':

		$query = "UPDATE `ProfilesDesc` SET `join_page` = `order` + 1000";
		$respd = db_res($query);

		break;

	// move field to another join page
	case 'change_page':

		$res = db_res( "SELECT `ID`, `name`, `namedisp`, `join_page`, `type`, `group_mark`, FLOOR(`join_page` / 1000) as `jp` FROM `ProfilesDesc` WHERE `visible` & 2 AND (FIND_IN_SET('0',`show_on_page`) OR FIND_IN_SET('3',`show_on_page`)) ORDER BY `join_page` ASC" );
		// Process deleting
		change_page( $_POST['id'], $_POST['new_jp'], $res );
		echo "<p><span style=\"color:#ff6666;font-weight:bold\">Join page changed.</span></p>\n";
		$modified = (int)$_POST['id'];
		break;

	case 'up':

		$res = db_res( "SELECT `ID`, `name`, `namedisp`, `join_page`, `type`, `group_mark`, FLOOR(`join_page` / 1000) as `jp` FROM `ProfilesDesc` WHERE `visible` & 2 AND (FIND_IN_SET('0',`show_on_page`) OR FIND_IN_SET('3',`show_on_page`)) ORDER BY `join_page` ASC" );
		if ( !move_up( $_GET['id'], $res ) )
			echo "<p><span style=\"color:#ff6666;font-weight:bold\">Can't move up field.</span></p>\n";
		else
			$modified = (int)$_GET['id'];
		break;

	case 'down':

		$res = db_res( "SELECT `ID`, `name`, `namedisp`, `join_page`, `type`, `group_mark`, FLOOR(`join_page` / 1000) as `jp` FROM `ProfilesDesc` WHERE `visible` & 2 AND (FIND_IN_SET('0',`show_on_page`) OR FIND_IN_SET('3',`show_on_page`)) ORDER BY `join_page` ASC" );
		if ( !move_down( $_GET['id'], $res ) )
			echo "<p><span style=\"color:#ff6666;font-weight:bold\">Can't move down field.</span></p>\n";
		else
			$modified = (int)$_GET['id'];
		break;
	}

	$reset_link = "<a href=\"{$_SERVER['PHP_SELF']}?action=reset\">reset join form to default</a><br>&nbsp;<br>";
	return $reset_link . display_fields();
}

function display_fields()
{
	global $modified;

	$content = "
				<table width=100% border=\"1\" class=\"profile_fields\">
					<tr>
						<td><b>name</b></td>
						<td><b>caption</b></td>
						<td colspan=\"2\"><b>order</b></td>
						<td><b>edit</b></td>
					</tr>";

	// if 'hidden' fields exists - we have to create caption for it
	$query = "SELECT COUNT(`join_page`) FROM `ProfilesDesc` WHERE `join_page` < 1000;";
	$arr = db_arr($query);
	if ( $arr[0] > 0 )
	{
		$content .= "
					<tr>
						<td style=\"font-weight: bold; background-color: #757575;\" colspan=\"6\" align=\"center\">List of hidden fields</td>
					</tr>";
	}

	$current_page = 0;
	$respd = db_res( "SELECT `ID`, `name`, `namedisp`, `join_page`, `type`, `group_mark`, FLOOR(`join_page` / 1000) as `jp` FROM `ProfilesDesc` WHERE `visible` & 2 AND (FIND_IN_SET('0',`show_on_page`) OR FIND_IN_SET('3',`show_on_page`)) ORDER BY `join_page` ASC" );
	$total_num = mysql_num_rows($respd);
	$i = 0;
	while ( $arrpd = mysql_fetch_array($respd) )
	{
		if ( $arrpd['jp'] != $current_page )
		{
			$content .= "
					<tr>
						<td style=\"font-weight: bold; background-color: #ffc482;\" colspan=\"6\" align=\"center\">Join page number {$arrpd['jp']}</td>
					</tr>";
			$current_page = $arrpd['jp'];
		}
		if ( $modified == $arrpd['ID'] )
		{
			$style = 'class="modified_row"';
		}
		else
		{
			$style = (( '0' == $arrpd['type'] ) ? 'class="section""' :
				(( $i % 2 == 0 ) ? 'class="odd_row"' : ''));
		}

		if ( $arrpd['group_mark'] != '' )
		{
			$row_style = 'color:#777777;';
		}
		else
		{
			$row_style = '';
		}

		$content .= "
					<tr $style>";
		if ( '0' != $arrpd['type'] )
		{
			$content .= "
						<td width=\"30%\" style=\"padding-right: 5px; $row_style\">{$arrpd['name']}</td>
						<td style=\"$row_style\">". _t($arrpd['namedisp']) ."</td>";
		}
		else
		{
			$content .= "
						<td colspan=\"2\">". _t($arrpd['namedisp']) ."</td>";
		}
		$content .= "
						<td width=\"15px\">". ($i != 0 ? "<a href='{$_SERVER['PHP_SELF']}?action=up&id={$arrpd['ID']}'><img src=\"images/arrow_up.gif\" border=\"0\"></a>" : "&nbsp;") ."</td>
						<td width=\"15px\">". ($i != $total_num - 1 ? "<a href='{$_SERVER['PHP_SELF']}?action=down&id={$arrpd['ID']}'><img src=\"images/arrow_down.gif\" border=\"0\"></a>" : '&nbsp;') . "</td>
						<td width=\"15px\"><a href='{$_SERVER['PHP_SELF']}?action=edit&id={$arrpd['ID']}'><img src=\"images/edit.gif\" border=\"0\"></a></td>
					</tr>";
		$i++;
	}

	$content .= "
				</table>";

	return $content;
}

function change_page( $ID, $join_page, $res )
{
	// Make int from ID and join_page
	$ID = (int) $ID;
	$join_page = (int) $join_page;

	// Collect all fields and determine which of them are group
	$cnt = 0;
	$total_rows = mysql_num_rows( $res );
	while ( $row = mysql_fetch_array( $res ) )
	{
		$rows[$cnt] = $row;
		$fname = get_field_name( $row );
		$rows[$cnt]['db_name'] = $fname;
		$rows[$cnt]['change_jp'] = false;
		$field_groups[$fname]['count']++;
		if ( $row['ID'] == $ID )
			$field_index = $cnt;
		$cnt++;
	}

	if ( !isset($field_index) )
		return;

	// Determ the last order for destination join page
	$query = "SELECT `join_page` FROM `ProfilesDesc` WHERE `join_page` >= ". ($join_page * 1000) . " AND `join_page` < ".( ($join_page + 1) * 1000) ." ORDER BY `join_page` DESC LIMIT 1";
	$order_arr = db_arr($query);
	if ( !$order_arr['join_page'] )
		$order = $join_page * 1000 + 1;
	else
		$order = $order_arr['join_page'] + 1;

	// Keep changing join page for all row group
	if ( $rows[$field_index]['group_mark'] != '' )
	{
		$i = $field_index - 1;
		while ( $rows[$i]['group_mark'] != '' && $i >= 0 )
		{
			$rows[$i]['change_jp'] = true;
			$i--;
		}
		$i = $field_index + 1;
		while ( $rows[$i]['group_mark'] != '' && $i < $total_rows )
		{
			$rows[$i]['change_jp'] = true;
			$i++;
		}
	}

	// Change join page for all necessary fields
	for ( $i = 0; $i < $total_rows; $i++ )
		if ( $rows[$i]['db_name'] == $rows[$field_index]['db_name'] || $rows[$i]['change_jp'] )
		{
			db_res( "UPDATE `ProfilesDesc` SET `join_page` = '$order' WHERE ID = {$rows[$i]['ID']}" );
			$order++;
		}
}

function move_up( $ID, $res )
{
	// Make int from ID
	$ID = (int) $ID;

	// Collect fields and determine which of them are group
	$cnt = 0;
	while ( $row = mysql_fetch_array( $res ) )
	{
		$rows[$cnt] = $row;
		$fname = get_field_name( $row );
		$rows[$cnt]['db_name'] = $fname;
		$field_groups[$fname]['count']++;
		if ( $row['ID'] == $ID )
			$field_index = $cnt;
		$cnt++;
	}

	// Number of fields in current group
	$src_group_fields_num = $field_groups[$rows[$field_index]['db_name']]['count'];
	// Number of fields in destination group
	$dest_group_fields_num = $field_groups[$rows[$field_index - 1]['db_name']]['count'];

	if ( $field_index == 0 )
		return false;

	// Get the join page of given field.
	$src_jp = $rows[$field_index]['join_page'];
	$src_id = $rows[$field_index]['ID'];

	// Get the join page of field preceding the given one.
	$dest_jp = $rows[$field_index - 1]['join_page'];
	$dest_id = $rows[$field_index - 1]['ID'];

	// This is for prevent group intersection
	if ( $src_group_fields_num > 1 )
		$src_group_char = 'G';
	else
		$src_group_char = ( $rows[$field_index]['group_mark'] == '' ? ' ' : $rows[$field_index]['group_mark'] );
	if ( $dest_group_fields_num > 1 )
		$dest_group_char = 'G';
	else
		$dest_group_char = ( $rows[$field_index - 1]['group_mark'] == '' ? ' ' : $rows[$field_index - 1]['group_mark'] );
	if ( $dest_group_char . $src_group_char == 'GG' && $rows[$field_index]['db_name'] == $rows[$field_index - 1]['db_name'] )
	{
		$src_group_char = ( $rows[$field_index]['group_mark'] == '' ? ' ' : $rows[$field_index]['group_mark'] );
		$dest_group_char = ( $rows[$field_index - 1]['group_mark'] == '' ? ' ' : $rows[$field_index - 1]['group_mark'] );
	}

	switch ( $dest_group_char . $src_group_char )
	{
		case 'GG':
			$border_info['first_start'] = $field_index - $dest_group_fields_num;
			$border_info['first_end'] = $field_index - 1;
			$border_info['second_start'] = $field_index;
			$border_info['second_end'] = $field_index + $src_group_fields_num - 1;
			swap_groups_jp( $rows, $border_info );
			break;

		case 'Gb':
			$border_info['first_start'] = $field_index - $dest_group_fields_num;
			$border_info['first_end'] = $field_index - 1;
			$border_info['second_start'] = $field_index;
			// Determine index of last field in the row
			$row_end_index = $field_index + 1;
			while ( $rows[$row_end_index]['group_mark'] != 'e' && $rows[$row_end_index]['group_mark'] != '' )
				$row_end_index++;
			if ( $rows[$row_end_index]['group_mark'] == '' )
				$row_end_index--;
			$border_info['second_end'] = $row_end_index;
			swap_groups_jp( $rows, $border_info );
			break;

		case 'eG':
			// Determine index of last field in the row
			$row_start_index = $field_index - 2;
			while ( $rows[$row_start_index]['group_mark'] != 'b' && $rows[$row_start_index]['group_mark'] != '' )
				$row_start_index--;
			if ( $rows[$row_start_index]['group_mark'] == '' )
				$row_start_index++;
			$border_info['first_start'] = $row_start_index;
			$border_info['first_end'] = $field_index - 1;
			$border_info['second_start'] = $field_index;
			$border_info['second_end'] = $field_index + $src_group_fields_num - 1;
			swap_groups_jp( $rows, $border_info );
			break;

		case 'G ':
			// Move lower field up
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$field_index - $dest_group_fields_num]['join_page'] ." WHERE `ID` = $src_id";
			db_res( $query_str );
			for ($i = $field_index - $dest_group_fields_num; $i < $field_index; $i++)
			{
				// Change join page of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$i + 1]['join_page'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}
			break;

		case ' G':
			// Move upper field down
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$field_index + $src_group_fields_num - 1]['join_page'] ." WHERE `ID` = $dest_id";
			db_res( $query_str );
			for ($i = $field_index; $i < $field_index + $src_group_fields_num; $i++)
			{
				// Change join page of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$i - 1]['join_page'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}
			break;

		case ' b':
			// Determine index of last field in the row
			$row_end_index = $field_index + 1;
			while ( $rows[$row_end_index]['group_mark'] != 'e' && $rows[$row_end_index]['group_mark'] != '' )
				$row_end_index++;
			if ( $rows[$row_end_index]['group_mark'] == '' )
				$row_end_index--;

			// Move upper field down
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$row_end_index]['join_page'] ." WHERE `ID` = $dest_id";
			db_res( $query_str );
			for ($i = $field_index; $i <= $row_end_index; $i++)
			{
				// Change join page of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$i - 1]['join_page'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}

			break;

		case 'bc':
			// Change join page for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $dest_jp, `group_mark` = 'b' WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change join page for the preceding field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $src_jp, `group_mark` = 'c' WHERE `ID` = $dest_id";
			db_res( $query_str );

			break;

		case 'be':
			// Change join page for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $dest_jp, `group_mark` = 'b' WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change join page for the preceding field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $src_jp, `group_mark` = 'e' WHERE `ID` = $dest_id";
			db_res( $query_str );

			break;

		case 'ce':
			// Change join page for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $dest_jp, `group_mark` = 'c' WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change join page for the preceding field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $src_jp, `group_mark` = 'e' WHERE `ID` = $dest_id";
			db_res( $query_str );

			break;

		case 'e ':
			// Determine index of last field in the row
			$row_start_index = $field_index - 2;
			while ( $rows[$row_start_index]['group_mark'] != 'b' && $rows[$row_start_index]['group_mark'] != '' )
				$row_start_index--;
			if ( $rows[$row_start_index]['group_mark'] == '' )
				$row_start_index++;
			// Move lower field up
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$row_start_index]['join_page'] ." WHERE `ID` = $src_id";
			db_res( $query_str );
			for ($i = $row_start_index; $i < $field_index; $i++)
			{
				// Change join page of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$i + 1]['join_page'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}

			break;

		case '  ':
		case 'cc':
			// Change join page for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $dest_jp WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change join page for the preceding field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $src_jp WHERE `ID` = $dest_id";
			db_res( $query_str );
	}

	return true;
}

function move_down( $ID, $res )
{
	// Make int from ID
	$ID = (int) $ID;

	// Collect fields and determine which of them are group
	$cnt = 0;
	$total_rows = mysql_num_rows( $res );
	while ( $row = mysql_fetch_array( $res ) )
	{
		$rows[$cnt] = $row;
		$fname = get_field_name( $row );
		$rows[$cnt]['db_name'] = $fname;
		$field_groups[$fname]['count']++;
		if ( $row['ID'] == $ID )
			$field_index = $cnt;
		$cnt++;
	}

	// Number of fields in current group
	$src_group_fields_num = $field_groups[$rows[$field_index]['db_name']]['count'];
	// Number of fields in destination group
	$dest_group_fields_num = $field_groups[$rows[$field_index + 1]['db_name']]['count'];

	if ( $field_index == ($total_rows - 1) )
		return false;

	// Get the order of the given field.
	$src_jp = $rows[$field_index]['join_page'];
	$src_id = $rows[$field_index]['ID'];

	// Get the order of the field preceding the given one.
	$dest_jp = $rows[$field_index + 1]['join_page'];
	$dest_id = $rows[$field_index + 1]['ID'];

	// This is for prevent group intersection
	if ( $src_group_fields_num > 1 )
		$src_group_char = 'G';
	else
		$src_group_char = ( $rows[$field_index]['group_mark'] == '' ? ' ' : $rows[$field_index]['group_mark'] );
	if ( $dest_group_fields_num > 1 )
		$dest_group_char = 'G';
	else
		$dest_group_char = ( $rows[$field_index + 1]['group_mark'] == '' ? ' ' : $rows[$field_index + 1]['group_mark'] );
	if ( $src_group_char . $dest_group_char == 'GG' && $rows[$field_index]['db_name'] == $rows[$field_index + 1]['db_name'] )
	{
		$src_group_char = ( $rows[$field_index]['group_mark'] == '' ? ' ' : $rows[$field_index]['group_mark'] );
		$dest_group_char = ( $rows[$field_index + 1]['group_mark'] == '' ? ' ' : $rows[$field_index + 1]['group_mark'] );
	}

	switch ( $src_group_char . $dest_group_char )
	{
		case 'GG':
			$border_info['first_start'] = $field_index - $src_group_fields_num + 1;
			$border_info['first_end'] = $field_index;
			$border_info['second_start'] = $field_index + 1;
			$border_info['second_end'] = $field_index + $dest_group_fields_num;
			swap_groups_jp( $rows, $border_info );
			break;

		case 'Gb':
			$border_info['first_start'] = $field_index - $src_group_fields_num + 1;
			$border_info['first_end'] = $field_index;
			$border_info['second_start'] = $field_index + 1;
			// Determine index of last field in the row
			$row_end_index = $field_index + 2;
			while ( $rows[$row_end_index]['group_mark'] != 'e' && $rows[$row_end_index]['group_mark'] != '' )
				$row_end_index++;
			if ( $rows[$row_end_index]['group_mark'] == '' )
				$row_end_index--;
			$border_info['second_end'] = $row_end_index;
			swap_groups_jp( $rows, $border_info );
			break;

		case 'eG':
			// Determine index of last field in the row
			$row_start_index = $field_index - 1;
			while ( $rows[$row_start_index]['group_mark'] != 'b' && $rows[$row_start_index]['group_mark'] != '' )
				$row_start_index--;
			if ( $rows[$row_start_index]['group_mark'] == '' )
				$row_start_index++;
			$border_info['first_start'] = $row_start_index;
			$border_info['first_end'] = $field_index;
			$border_info['second_start'] = $field_index + 1;
			$border_info['second_end'] = $field_index + $dest_group_fields_num;
			swap_groups_jp( $rows, $border_info );
			break;

		case 'G ':
			// Move lower field up
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$field_index - $src_group_fields_num + 1]['join_page'] ." WHERE `ID` = $dest_id";
			db_res( $query_str );
			for ($i = $field_index - $src_group_fields_num + 1; $i <= $field_index; $i++)
			{
				// Change join page of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$i + 1]['join_page'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}
			break;

		case ' G':
			// Move upper field down
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$field_index + $dest_group_fields_num]['join_page'] ." WHERE `ID` = $src_id";
			db_res( $query_str );
			for ($i = $field_index + 1; $i <= $field_index + $dest_group_fields_num; $i++)
			{
				// Change join page of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$i - 1]['join_page'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}
			break;

		case ' b':
			// Determine index of last field in the row
			$row_end_index = $field_index + 2;
			while ( $rows[$row_end_index]['group_mark'] != 'e' && $rows[$row_end_index]['group_mark'] != '' )
				$row_end_index++;
			if ( $rows[$row_end_index]['group_mark'] == '' )
				$row_end_index--;

			// Move upper field down
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$row_end_index]['join_page'] ." WHERE `ID` = $src_id";
			db_res( $query_str );
			for ($i = $field_index + 1; $i <= $row_end_index; $i++)
			{
				// Change join page of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$i - 1]['join_page'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}

			break;

		case 'bc':
			// Change join page for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $dest_jp, `group_mark` = 'c' WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change join page for the following field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $src_jp, `group_mark` = 'b' WHERE `ID` = $dest_id";
			db_res( $query_str );

			break;

		case 'be':
			// Change join page for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $dest_jp, `group_mark` = 'e' WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change join page for the following field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $src_jp, `group_mark` = 'b' WHERE `ID` = $dest_id";
			db_res( $query_str );

			break;

		case 'ce':
			// Change join page for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $dest_jp, `group_mark` = 'e' WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change join page for the following field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $src_jp, `group_mark` = 'c' WHERE `ID` = $dest_id";
			db_res( $query_str );

			break;

		case 'e ':
			// Determine index of last field in the row
			$row_start_index = $field_index - 1;
			while ( $rows[$row_start_index]['group_mark'] != 'b' && $rows[$row_start_index]['group_mark'] != '' )
				$row_start_index--;
			if ( $rows[$row_start_index]['group_mark'] == '' )
				$row_start_index++;

			// Move lower field up
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$row_start_index]['join_page'] ." WHERE `ID` = $dest_id";
			db_res( $query_str );
			for ($i = $row_start_index; $i <= $field_index; $i++)
			{
				// Change join page of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `join_page` = ". $rows[$i + 1]['join_page'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}

			break;

		case '  ':
		case 'cc':
			// Change join page for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $dest_jp WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change join page for the following field.
			$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $src_jp WHERE `ID` = $dest_id";
			db_res( $query_str );
	}

	return true;
}

/**
 * Swap join form order of two field groups
 * @param $field_buffer - array of fields in format [$index]['value']
 * @param $border_info - info about field groups borders
 *		$border_info['first_start'] - start of first group
 *		$border_info['first_end'] - end of first group
 *		$border_info['second_start'] - start of second group
 *		$border_info['second_end'] - end of second group
 *		WARNING!!! $border_info['first_end'] + 1 = $border_info['second_start']
 */
function swap_groups_jp( $field_buffer, $border_info )
{
	// First swap action
	$offset = 0;
	for ($i = $border_info['second_start']; $i <= $border_info['second_end']; $i++)
	{
		// Get the join page of the given field.
		$src_jp = $field_buffer[$border_info['first_start'] + $offset]['join_page'];

		// Get the join page of the field preceding the given one.
		$dest_id = $field_buffer[$i]['ID'];

		// Change order for the given field.
		$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $src_jp WHERE `ID` = $dest_id";
		db_res( $query_str );

		$offset++;
	}

	// Second swap action
	$offset = $border_info['second_end'] - $border_info['second_start'] - $border_info['first_end'] + $border_info['first_start'];
	for ($i = $border_info['first_start']; $i <= $border_info['first_end']; $i++)
	{
		// Get the order of the given field.
		$src_jp = $field_buffer[$border_info['second_start'] + $offset]['join_page'];

		// Get the order of the field preceding the given one.
		$dest_id = $field_buffer[$i]['ID'];

		// Change order for the given field.
		$query_str = "UPDATE `ProfilesDesc` SET `join_page` = $src_jp WHERE `ID` = $dest_id";
		db_res( $query_str );

		$offset++;
	}

}

?>