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
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'languages.inc.php' );

// Check if administrator is logged in.  If not display login form.
$logged['admin'] = member_auth( 1 );

$_page['header'] = 'Profile Fields';
$_page['css_name'] = 'profile_fields.css';


// List of mandatory fields.
$mandatory = array (
	'NickName',
	'Sex',
	'Email',
	'LookingFor',
	'Password',
	'Password,Password1',
	'Status',
	'Country',
	'City',
	'Tags',
	'zip',
	'Headline',
	'DescriptionMe',
	'DateOfBirth',
	'EmailFlag',
	'EmailNotify',
	);

TopCodeAdmin();
ContentBlockHead("");

// Check GET variables.
if ( $_GET['ID'] && ( 'move_up' == $_GET['action'] ) )
{
	$query_str = "SELECT * FROM `ProfilesDesc` ORDER BY `order` ASC";
	$res = db_res( $query_str );
	// Process moving
	if ( !move_up( $_GET['ID'], $res ) )
		echo "<p><span style=\"color:#ff6666;font-weight:bold\">Can't move up field.</span></p>\n";
	else
		$modified = (int)$_GET['ID'];
}

if ( $_GET['ID'] && ( 'move_down' == $_GET['action'] ) )
{
	$query_str = "SELECT * FROM `ProfilesDesc` ORDER BY `order` ASC";
	$res = db_res( $query_str );
	// Process moving
	if ( !move_down( $_GET['ID'], $res ) )
		echo "<p><span style=\"color:#ff6666;font-weight:bold\">Can't move down field.</span></p>\n";
	else
		$modified = (int)$_GET['ID'];
}

if ( $_GET['ID'] && ( 'delete' == $_GET['action'] ) )
{
	$query_str = "SELECT * FROM `ProfilesDesc` ORDER BY `order` ASC";
	$res = db_res( $query_str );
	// Process deleting
	delete_field( $_GET['ID'], $res );
	echo "<p><span style=\"color:#ff6666;font-weight:bold\">Field deleted.</span></p>\n";
}

// Display add or edit form
if ( 'add' == $_GET['action'] || 'edit' == $_GET['action'] )
{
	// Display if user just got into the page, or refreshed field type,
	// otherwise if the user pressed 'Add' button, add new field.
	if ( !$_POST['add_button'] )
	{
		display_controls();
	}
	else
	{
		// Check input parameters.
		$errors = check_parameters( $_POST['field_type'] );
		if ( count( $errors ) > 0 )
		{
			// Display error messages and controls for entering.
			foreach ( $errors as $value )
			{
				echo "<span style=\"color:#ff6666;font-weight:bold\">Error: $value</span><br />";
			}
			display_controls();
		}
		else
		{
			// Perform database actions
			edit_or_add_field();
		}
	}
}
else
{
	echo "<p class=text><a href=\"profile_fields.php?action=add\">Add new field</a></p>\n";
	// Get a list of all fields.
	$query_str = "SELECT * FROM `ProfilesDesc` ORDER BY `order` ASC";
	$res = db_res( $query_str );
	display_fields( $res );
}

ContentBlockFoot();

BottomCode();

/**
 * Display profile fields in a table.
 * @param $res		Recordset of profile fields.
 */
function display_fields( $res )
{
	global $modified;
	global $mandatory;

	echo "<table width=\"100%\" border=\"1\" class=\"profile_fields\">\n";
	echo '
		<tr style="font-weight:bold">
			<td>name</td>
			<td>caption</td>
			<td>type</td>
			<td colspan="2">order</td>
			<td>del</td>
			<td>edit</td>
		</tr>
		';
	$odd_cnt = 0;
	$cnt = 0;
	$total_rows = mysql_num_rows( $res );
	// Collect all fields and determine which of them are group
	while ( $row = mysql_fetch_array( $res ) )
	{
		$rows[$cnt] = $row;
		$fname = get_field_name( $row );
		$rows[$cnt]['db_name'] = $fname;
		$field_groups[$fname]['count']++;
		$cnt++;
	}

	for ($cnt = 0; $cnt < $total_rows; $cnt++)
	{
		if ( $modified == $rows[$cnt]['ID'] )
		{
			// Class for modified row.
			$class = 'class="modified_row"';
		}
		else if ( '0' == $rows[$cnt]['type'] )
		{
			// Class for section row.
			$class = 'class="section"';
		}
		else if ( 0 == $odd_cnt % 2 )
		{
			// Class for even row.
			$class = 'class="even_row"';
		}
		else
		{
			$class = 'class="odd_row"';
		}
		echo "<tr $class>\n";

		// Generate table data class.
		$namedisp = _t( $rows[$cnt]['namedisp'] );
		if ( '0' == $rows[$cnt]['type'] )
		{
			if ( $modified == $rows[$cnt]['ID'] )
			{
				$class = 'class="modified_row"';
			}
			else
			{
				$class = 'class="section"';
			}
			echo "<td colspan=\"3\">$namedisp</td>\n";
			$odd_cnt = 0;
		}
		else
		{
			if ( $modified == $rows[$cnt]['ID'] )
			{
				$class = 'class="modified_row"';
			}
			else if ( 0 == $odd_cnt % 2 )
			{
				$class = 'class="odd_row"';
			}
			else
			{
				$class = '';
			}

			if ( $rows[$cnt]['group_mark'] != '' )
			{
				$row_style = 'style="color:#777777"';
			}
			else
			{
				$row_style = '';
			}

			$type_name = get_type_name( $rows[$cnt]['type'] );
			echo "<td width=\"30%\" $row_style>{$rows[$cnt]['name']}</td>\n";
			echo "<td width=\"30%\" $row_style>$namedisp</td>\n";
			echo "<td $row_style>$type_name</td>\n";
			$odd_cnt++;
		}

		// Generate links.
		// Generate 'Move up' link.
		if ( 0 == $cnt )
		{
			// Don't generate any link for the first field.
			echo "<td width=\"15px\"></td>\n";
		}
		else
		{
			$move_up = "profile_fields.php?ID={$rows[$cnt]['ID']}&action=move_up";
			echo "<td width=\"15px\"><a href=\"$move_up\"><img src=\"images/arrow_up.gif\" alt=\"Move up\" border=\"0\"/></a></td>\n";
		}
		// Generate 'Move down' link.
		if ( $cnt == ($total_rows - 1) )
		{
			// Don't generate any link for the last field.
			echo "<td width=\"15px\">&nbsp;</td>\n";
		}
		else
		{
			$move_down = "profile_fields.php?ID={$rows[$cnt]['ID']}&action=move_down";
			echo "<td width=\"15px\"><a href=\"$move_down\"><img src=\"images/arrow_down.gif\" alt=\"Move down\" border=\"0\"/></a></td>\n";
		}

		// Check if field can be deleted.
		if ( !in_array( $rows[$cnt]['db_name'], $mandatory ) )
		{
			echo "<td width=\"15px\" align=\"center\"><a href=\"profile_fields.php?ID={$rows[$cnt][ID]}&action=delete\"><img src=\"images/delete.gif\" alt=\"Delete\" border=\"0\" title=\"Delete\" /></a></td>\n";
		}
		else
		{
			echo "<td width=\"15px\">&nbsp;</td>\n";
		}

		if ( false != field_editable( $rows[$cnt] ) )
		{
			echo "<td width=\"15px\" align=\"center\"><a href=\"profile_fields.php?ID={$rows[$cnt]['ID']}&action=edit\"><img src=\"images/edit.gif\" alt=\"Edit\" border=\"0\" title=\"Edit\" /></a></td>\n";
		}
		else
		{
			echo "<td width=\"15px\"></td>\n";
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
}


/**
 * Move the field up.
 * @param $ID		Field ID.
 */
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

	// Get the order of the given field.
	$src_order = $rows[$field_index]['order'];
	$src_id = $rows[$field_index]['ID'];

	// Get the order of the field preceding the given one.
	$dest_order = $rows[$field_index - 1]['order'];
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
			swap_groups_order( $rows, $border_info );
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
			swap_groups_order( $rows, $border_info );
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
			swap_groups_order( $rows, $border_info );
			break;

		case 'G ':
			// Move lower field up
			$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$field_index - $dest_group_fields_num]['order'] ." WHERE `ID` = $src_id";
			db_res( $query_str );
			for ($i = $field_index - $dest_group_fields_num; $i < $field_index; $i++)
			{
				// Change order of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$i + 1]['order'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}
			break;

		case ' G':
			// Move upper field down
			$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$field_index + $src_group_fields_num - 1]['order'] ." WHERE `ID` = $dest_id";
			db_res( $query_str );
			for ($i = $field_index; $i < $field_index + $src_group_fields_num; $i++)
			{
				// Change order of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$i - 1]['order'] ." WHERE `ID` = ". $rows[$i]['ID'];
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
			$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$row_end_index]['order'] ." WHERE `ID` = $dest_id";
			db_res( $query_str );
			for ($i = $field_index; $i <= $row_end_index; $i++)
			{
				// Change order of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$i - 1]['order'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}

			break;

		case 'bc':
			// Change order for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $dest_order, `group_mark` = 'b' WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change order for the preceding field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $src_order, `group_mark` = 'c' WHERE `ID` = $dest_id";
			db_res( $query_str );

			break;

		case 'be':
			// Change order for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $dest_order, `group_mark` = 'b' WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change order for the preceding field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $src_order, `group_mark` = 'e' WHERE `ID` = $dest_id";
			db_res( $query_str );

			break;

		case 'ce':
			// Change order for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $dest_order, `group_mark` = 'c' WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change order for the preceding field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $src_order, `group_mark` = 'e' WHERE `ID` = $dest_id";
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
			$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$row_start_index]['order'] ." WHERE `ID` = $src_id";
			db_res( $query_str );
			for ($i = $row_start_index; $i < $field_index; $i++)
			{
				// Change order of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$i + 1]['order'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}

			break;

		case '  ':
		case 'cc':
			// Change order for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $dest_order WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change order for the preceding field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $src_order WHERE `ID` = $dest_id";
			db_res( $query_str );
	}

	return true;
}

/**
 * Move the field down.
 * @param $ID		Field ID.
 */
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
	$src_order = $rows[$field_index]['order'];
	$src_id = $rows[$field_index]['ID'];

	// Get the order of the field preceding the given one.
	$dest_order = $rows[$field_index + 1]['order'];
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
			swap_groups_order( $rows, $border_info );
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
			swap_groups_order( $rows, $border_info );
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
			swap_groups_order( $rows, $border_info );
			break;

		case 'G ':
			// Move lower field up
			$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$field_index - $src_group_fields_num + 1]['order'] ." WHERE `ID` = $dest_id";
			db_res( $query_str );
			for ($i = $field_index - $src_group_fields_num + 1; $i <= $field_index; $i++)
			{
				// Change order of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$i + 1]['order'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}
			break;

		case ' G':
			// Move upper field down
			$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$field_index + $dest_group_fields_num]['order'] ." WHERE `ID` = $src_id";
			db_res( $query_str );
			for ($i = $field_index + 1; $i <= $field_index + $dest_group_fields_num; $i++)
			{
				// Change order of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$i - 1]['order'] ." WHERE `ID` = ". $rows[$i]['ID'];
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
			$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$row_end_index]['order'] ." WHERE `ID` = $src_id";
			db_res( $query_str );
			for ($i = $field_index + 1; $i <= $row_end_index; $i++)
			{
				// Change order of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$i - 1]['order'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}

			break;

		case 'bc':
			// Change order for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $dest_order, `group_mark` = 'c' WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change order for the following field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $src_order, `group_mark` = 'b' WHERE `ID` = $dest_id";
			db_res( $query_str );

			break;

		case 'be':
			// Change order for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $dest_order, `group_mark` = 'e' WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change order for the following field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $src_order, `group_mark` = 'b' WHERE `ID` = $dest_id";
			db_res( $query_str );

			break;

		case 'ce':
			// Change order for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $dest_order, `group_mark` = 'e' WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change order for the following field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $src_order, `group_mark` = 'c' WHERE `ID` = $dest_id";
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
			$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$row_start_index]['order'] ." WHERE `ID` = $dest_id";
			db_res( $query_str );
			for ($i = $row_start_index; $i <= $field_index; $i++)
			{
				// Change order of the current field.
				$query_str = "UPDATE `ProfilesDesc` SET `order` = ". $rows[$i + 1]['order'] ." WHERE `ID` = ". $rows[$i]['ID'];
				db_res( $query_str );
			}

			break;

		case '  ':
		case 'cc':
			// Change order for the given field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $dest_order WHERE `ID` = $src_id";
			db_res( $query_str );

			// Change order for the following field.
			$query_str = "UPDATE `ProfilesDesc` SET `order` = $src_order WHERE `ID` = $dest_id";
			db_res( $query_str );
	}

	return true;
}

/**
 * Get type name given its abbreviation.
 * @param $abbr		Type name abbreviation.
 * @retval			Type full name.
 */
function get_type_name( $abbr )
{
	switch ( $abbr )
	{
		case 'c': return 'edit box';
		case 'e': return 'drop-down box';
		case 'en': return 'drop-down box (numeric)';
		case 'a': return 'memo';
		case '0': return 'divider';
		case 'r': return 'reference';
		case 'rb': return 'set of radio buttons';
		case 'set': return 'set of checkboxes';
		case 'p': return 'password';
		case 'rr': return 'reference to reference';
		default: return $abbr;
	}
}

/**
 * Display controls for adding new field.
 */
function display_controls()
{
	// Display controls for adding new field or editing existing one.

	//  Select values from database
	if ( 'edit' == $_GET['action'] && $_GET['ID'] )
	{
		$q_str = "SELECT * FROM `ProfilesDesc` WHERE `ID` = {$_GET['ID']}";
		$field = db_arr( $q_str );
	}

	// Start displaying...
	if ( !$field )
	{
		// 'Add' form.
		echo "<form name=\"add_field\" action=\"profile_fields.php?action=add\" method=\"post\">\n";
	}
	else
	{
		// 'Edit' form.
		echo "<form name=\"add_field\" action=\"profile_fields.php?action=edit&ID={$_GET['ID']}\" method=\"post\">\n";
	}
	echo "<input type=\"hidden\" name=\"reenter\" value=\"on\" />";
	echo "<table style=\"font-size: 11px\" width=\"100%\" cellspacing=\"10px\">\n";
	echo "<tr><td width=\"30%\">\n";


	// Display field type.
	echo "<tr><td>Field type</td>\n";
	// Get value.
	if ( isset( $field ) )
	{
		$value = $field['type'];
	}
	else
	{
		$value = isset( $_GET['field_type'] ) ? $_GET['field_type'] : ( isset( $_POST['field_type'] ) ? $_POST['field_type'] : 'c' ) ;
	}
	$read_only = isset( $field );
	display_field_type( $value, $read_only );
	// Set field type.
	$field_type = $value;

	// Display drop-down box for selecting the field
	// after which to insert the new one.
	if ( !isset( $field ) )
	{
		echo "<tr><td>Add new field</td>\n";
		// Get default value.
		$value = $_POST['insert_after'] ? $_POST['insert_after'] : '';
		display_insert_after( $value );
	}

	// Display control for field name.
	$value = isset( $field ) ? $field['name'] : $_POST['field_name'];
	display_field_name( $value, $read_only );

	// Display control for entering caption.
	echo "<tr><td>Caption</td>\n";
	if ( $field && !$_POST['reenter'] )
	{
		$value = $field['namedisp'];
		$value = _t( $value );
	}
	else
	{
		$value = $_POST['caption'];
	}
	echo "<td><input type=\"text\" name=\"caption\" value=\"$value\" size=\"60\" />\n";

	// Display control for description.
	if ( $field_type != '0' )
	{
		echo "<tr><td>Description</td>\n";
		if ( $field && !$_POST['reenter'] )
		{
			$value = $field['namenote'];
			$value = _t( $value );
		}
		else
		{
			$value = $_POST['desc'];
		}
		echo "<td><input type=\"text\" name=\"desc\" value=\"$value\" size=\"60\" /></td>\n";
	}

	// Display control for 'mandatory' field.
	echo "<tr><td>Mandatory</td>";
	$checked = '';
	if ( $field && !$_POST['reenter'] )
	{
		if ( strlen( $field['check'] ) > 0 )
		{
			$checked = 'checked';
		}
	}
	else if ( 'on' == $_POST['mandatory'] )
	{
		$checked = 'checked';
	}
	echo "<td><input type=\"checkbox\" name=\"mandatory\" $checked /></td></tr>";

	// Display control for error message.
	echo "<tr><td>Error message</td>";
	$value = '';
	if ( $field && !$_POST['reenter'] )
	{
		$value = $field['because'];
		$value = _t( $value );
	}
	else
	{
		$value = $_POST['err_msg'];
	}
	echo "<td><input type=\"text\" name=\"err_msg\" value=\"$value\" size=\"60\" /></td></tr>";

	// Display control for user visibility.
	echo "<tr><td>Visible to</td>\n";
	// Check if checkbox is checked :)
	$checked = '';
	if ( $field && !$_POST['reenter'] )
	{
		// Check if visible to user.
		if ( strpos( $field['visible'], 'user' ) !== false )
		{
			$checked = 'checked';
		}
	}
	else if ( 'on' == $_POST['visible_to_visitor'] )
	{
		$checked = 'checked';
	}
	echo "<td><input type=\"checkbox\" name=\"visible_to_visitor\" $checked />Visitor<br />";

	$checked = '';
	if ( $field && !$_POST['reenter'] )
	{
		// Check if visible to member.
		if ( strpos( $field['visible'], 'memb' ) !== false )
		{
			$checked = 'checked';
		}
	}
	else if ( 'on' == $_POST['visible_to_member'] )
	{
		$checked = 'checked';
	}
	echo "<input type=\"checkbox\" name=\"visible_to_member\" $checked />Member<br />";

	$checked = '';
	if ( $field && !$_POST['reenter'] )
	{
		// Check if visible to admin.
		if ( strpos( $field['visible'], 'adm' ) !== false  )
		{
			$checked = 'checked';
		}
	}
	else if ( 'on' == $_POST['visible_to_admin'] )
	{
		$checked = 'checked';
	}
	echo "<input type=\"checkbox\" name=\"visible_to_admin\" $checked />Admin<br /></td></tr>\n";

	// Display control for edit possibility.
	echo "<tr><td>Editable for</td>\n";
	// Check if checkbox is checked :)
	$checked = '';
	if ( $field && !$_POST['reenter'] )
	{
		// Check if editable for member.
		if ( strpos( $field['editable'], 'memb' ) !== false )
		{
			$checked = 'checked';
		}
	}
	else if ( 'on' == $_POST['editable_for_member'] )
	{
		$checked = 'checked';
	}
	echo "<td><input type=\"checkbox\" name=\"editable_for_member\" $checked />Member<br />";

	$checked = '';
	if ( $field && !$_POST['reenter'] )
	{
		// Check if editable for admin.
		if ( strpos( $field['editable'], 'adm' ) !== false  )
		{
			$checked = 'checked';
		}
	}
	else if ( 'on' == $_POST['editable_for_admin'] )
	{
		$checked = 'checked';
	}
	echo "<input type=\"checkbox\" name=\"editable_for_admin\" $checked />Admin<br /></td></tr>\n";

	// Display controls for page visibility.
	echo "<tr><td>Show on page</td>\n";
	$checked = '';
	if ( $field && !$_POST['reenter'] )
	{
		// Check if visible on all pages.
		if ( strpos( $field['show_on_page'], '0' ) !== false )
		{
			$checked = 'checked';
		}
	}
	else if ( 'on' == $_POST['show_on_all'] )
	{
		$checked = 'checked';
	}
	echo "<td><input type=\"checkbox\" name=\"show_on_all\" $checked/>All<br />";
	$checked = '';
	if ( $field && !$_POST['reenter'] )
	{
		// Check if visible on join page.
		if ( strpos( $field['show_on_page'], '3' ) !== false )
		{
			$checked = 'checked';
		}
	}
	else if ( 'on' == $_POST['show_on_join'] )
	{
		$checked = 'checked';
	}
	echo "<input type=\"checkbox\" name=\"show_on_join\" $checked/>Join page<br />";
	$checked = '';
	if ( $field && !$_POST['reenter'] )
	{
		// Check if visible on view profile page.
		if ( strpos( $field['show_on_page'], '7' ) !== false )
		{
			$checked = 'checked';
		}
	}
	else if ( 'on' == $_POST['show_on_view_profile'] )
	{
		$checked = 'checked';
	}
	echo "<input type=\"checkbox\" name=\"show_on_view_profile\" $checked/>View Profile page<br />";
	$checked = '';
	if ( $field && !$_POST['reenter'] )
	{
		// Check if visible on edit profile page.
		if ( strpos( $field['show_on_page'], '25' ) !== false )
		{
			$checked = 'checked';
		}
	}
	else if ( 'on' == $_POST['show_on_edit_profile'] )
	{
		$checked = 'checked';
	}
	echo "<input type=\"checkbox\" name=\"show_on_edit_profile\" $checked/>Edit Profile page</td></tr>";

	// Display controls specific to different input types.
	switch ( $field_type )
	{
	case 'c':
	case 'p':
		echo "<tr><td>Edit box length</td>";
		if ( $field && !$_POST['reenter'] )
			$value = $field['extra'];
		else
			$value = $_POST['edit_box_length'];
		
		echo "<td><input type=\"text\" name=\"edit_box_length\" value=\"$value\" size=\"5\" /></td></tr>";
		echo "<tr><td>Min length</td>";
		
		if ( $field && !$_POST['reenter'] )
			$value = $field['min_length'];
		else
			$value = $_POST['min_value'];
		
		echo "<td><input type=\"text\" name=\"min_value\" value=\"$value\" size=\"5\" /></td></tr>";
		echo "<tr><td>Max length</td>";
		
		if ( $field && !$_POST['reenter'] )
			$value = $field['max_length'];
		else
			$value = $_POST['max_value'];
		
		echo "<td><input type=\"text\" name=\"max_value\" value=\"$value\" size=\"5\" /></td></tr>";
	break;
	case 'date':
		echo "<tr><td>Max and Min age</td>";
		if ( $field && !$_POST['reenter'] )
			$value = $field['extra'];
		else
			$value = $_POST['edit_box_length'];
		
		echo "<td><input type=\"text\" name=\"edit_box_length\" value=\"$value\" size=\"5\" /></td></tr>";
	break;

	case 'e':
	case 'rb':
	case 'set':
		if ( 'e' == $field_type )
		{
			$caption = 'Drop-down choices<br>(line by line)';
		}
		else if ( 'rb' == $field_type )
		{
			$caption = 'Radio button choices<br>(line by line)';
		}
		else if ( 'set' == $field_type )
		{
			$caption = 'Set choices<br>(line by line)';
		}
		echo "<tr><td>$caption</td>";
		if ( $field && !$_POST['reenter'] )
		{
			$temp = explode( ",", $field['extra'] );
			// Strip single quotes from beginning and end and pass through language.
			array_walk( $temp, create_function( '&$value', '$value = _t(\'_\'.trim($value, "\'"));' ) );
			$value = implode( "\r\n", $temp );
		}
		else
		{
			$value = $_POST['choices'];
		}
		//$value = ( $_POST['choices'] ) ? $_POST['choices'] : '';
		echo "<td><textarea style=\"font-family:Courier\" name=\"choices\" cols=\"30\" rows=\"10\">$value</textarea>";
	break;

	case 'a':
		echo "<tr><td>Memo rows</td>";
		if ( $field && !$_POST['reenter'] )
		{
			list( $cols, $rows ) = split( 'x', $field['extra'] );
			$value = $cols;

		}
		else
		{
			$value = $_POST['memo_cols'];
		}
		echo "<td><input type=\"text\" name=\"memo_rows\" value=\"$value\" size=\"5\" /></td></tr>";
		echo "<tr><td>Memo columns</td>";
		$value = $rows ? $rows : $_POST['memo_cols'];
		echo "<td><input type=\"text\" name=\"memo_cols\" value=\"$value\" size=\"5\" /></td></tr>";
		echo "<tr><td>Min length</td>";
		if ( $field && !$_POST['reenter'] )
		{
			$value = $field['min_length'];
		}
		else
		{
			$value = $_POST['min_value'];
		}
		echo "<td><input type=\"text\" name=\"min_value\" value=\"$value\" size=\"5\" /></td></tr>";
		if ( $field && !$_POST['reenter'] )
		{
			$value = $field['max_length'];
		}
		else
		{
			$value = $_POST['max_value'];
		}
		echo "<tr><td>Max length</td>";
		echo "<td><input type=\"text\" name=\"max_value\" value=\"$value\" size=\"5\" /></td></tr>";
	break;
	}

	// Display controls for search.
	// 'Allow search' checkbox.
	if ( $field_type != '0' )
	{
		echo "<tr><td>Search type</td>";

		// Full list
/*
		$search_types = array (
			'none' => 'non-searchable',
			'radio' => 'radio search',
			'list' => 'list search',
			'check' => 'enum search',
			'daterange' => 'date range search',
			'check_set' => 'checkboxes set search',
			'text' => 'text search'
		);
*/
		// Limited list
		switch ( $field_type )
		{
			case 'r':
			case 'e':
				$search_types = array (
					'none' => 'non-searchable',
					'radio' => 'radio search',
					'list' => 'list search',
					'check' => 'enum search'
				);
			break;
			
			case 'date':
				$search_types = array (
					'none' => 'non-searchable',
					'daterange' => 'date range search'
				);
			break;

			case 'c':
			case 'a':
			case 'p':
				$search_types = array (
					'none' => 'non-searchable',
					'radio' => 'radio search',
					'text' => 'text search'
				);
			break;

			case 'rb':
				$search_types = array (
					'none' => 'non-searchable',
					'radio' => 'radio search',
					'list' => 'list search'
				);
			break;

			case 'set':
				$search_types = array (
					'none' => 'non-searchable',
					'check_set' => 'checkboxes set search'
				);
			break;

			default:
				$search_types = array (
					'none' => 'non-searchable'
				);
			break;
		}

		$value = 'none';
		if ( $field && !$_POST['reenter'] )
		{
			$value = $field['search_type'];
		}
		else
		{
			if ( $_GET['search_t'] )
				$value = $_GET['search_t'];
			else
				$value = ( isset( $_POST['search_t'] ) ? $_POST['search_t'] : 'none' );
		}
		$search_type = $value;

		echo "<td><select name=\"search_t\" onChange=\"javascript: var val = this.value; window.location.href = 'profile_fields.php?action=add&field_type={$field_type}&search_t=' + val;\">\n";
		foreach ( $search_types as $key => $val )
		{
			if ( 0 == strcmp( $value, $key ) )
			{
				echo "<option value=\"$key\" selected>$val</option>\n";
			}
			else
			{
				echo "<option value=\"$key\">$val</option>\n";
			}
		}
		echo "</td></tr>\n";
	}

	// 'Initially hidden' checkbox.
	echo "<tr><td>Initially hidden on search page</td>";
	$checked = '';
	if ( $field && !$_POST['reenter'] )
	{
		$checked = (1 == $_POST['search_hide'] ? 'checked' : '');
	}
	else if ( 'on' == $_POST['search_hidden'] )
	{
		$checked = 'checked';
	}
	echo "<td><input type=\"checkbox\" name=\"search_hidden\" $checked /></td></tr>";

	if ( 'radio' == $search_type || 'check' == $search_type || 'check_set' == $search_type )
	{
		// 'Search columns' control.
		$arr = array( 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5');
		$index = 0;
		if ( $field && !$_POST['reenter'] )
		{
			$index = $field['search_cols'];
		}
		else if ( $_POST['search_cols'] )
		{
			$index = $_POST['search_cols'];
		}
		echo "<tr><td>Search columns<br>(Number of columns on search page)</td>\n";
		echo "<td><select name=\"search_cols\">";
		foreach ( $arr as $key => $value )
		{
			if ( $index == $key )
			{
				echo "<option value=\"$key\" selected>$value</option>\n";
			}
			else
			{
				echo "<option value=\"$key\">$value</option>\n";
			}
		}
		echo "</select>";
		echo "</td></tr>";
	}

	if ( $field_type != '0' )
	{
		// Match type combo box
		echo "<tr><td>Match type</td>";
		switch ( $field_type )
		{
			case 'r':
			case 'rb':
			case 'e':
				$match_types = array (
					'none' => 'non-matchable',
					'enum' => 'radio match',
					'enum_ref' => 'reference match'
				);
			break;

			case 'set':
				$match_types = array (
					'none' => 'non-matchable',
					'set' => 'checkboxes set match'
				);
			break;

			default:
				$match_types = array (
					'none' => 'non-matchable'
				);
			break;
		}

		$value = 'none';
		if ( $field && !$_POST['reenter'] )
		{
			$value = $field['match_type'];
		}
		else
		{
			$value = ( isset( $_POST['match_t'] ) ? $_POST['match_t'] : 'none' );
		}

		echo "<td><select name=\"match_t\" >\n";
		foreach ( $match_types as $key => $val )
		{
			if ( 0 == strcmp( $value, $key ) )
			{
				echo "<option value=\"$key\" selected>$val</option>\n";
			}
			else
			{
				echo "<option value=\"$key\">$val</option>\n";
			}
		}
		echo "</td></tr>\n";

		// Match field combo box
		echo "<tr><td>Match field</td>\n";
		// Get default value.
		if ( $field && !$_POST['reenter'] )
		{
			$value = $field['match_field'];
		}
		else
		{
			$value = $_POST['match_f'] ? $_POST['match_f'] : 'none';
		}
		display_match_field( $value );

		// Match extra edit
		echo "<tr><td>Match percents (%)</td>";
		if ( $field && !$_POST['reenter'] )
		{
			$value = $field['match_extra'];
		}
		else
		{
			$value = $_POST['match_perc'];
		}
		echo "<td><input type=\"text\" name=\"match_perc\" value=\"$value\" size=\"5\" /></td></tr>";
	}

	// Display submit button.
	if ( $field )
	{
		$caption = 'Update';
	}
	else
	{
		$caption = 'Add';
	}
	echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"add_button\" value=\"$caption\" /></td></tr>";

	echo "</table>\n";
	echo "</form>\n";
	echo "<a href=\"profile_fields.php\">Back</a>";
}

/**
 * Perform edit ar add field database actions
 * @param
 */
function edit_or_add_field()
{
	// Get field type.
	$field_type = $_POST['field_type'];

	// Select old field values
	if ( 'add' != $_GET['action'] )
	{
		$q_str = "SELECT * FROM `ProfilesDesc` WHERE `ID` = {$_GET['ID']}";
		$field = db_arr( $q_str );
	}

	// Get new field order.
	if ( 'add' == $_GET['action'] )
	{
		$order = get_field_order( $_POST['insert_after'] );
	}

	// Get field name.
	$name = process_pass_data($_POST['field_name']);
	$name = str_replace(' ', '_', $name);
	$name = str_replace("'", '_', $name);

	// Get caption and determ if it was changed
	if ( 'add' == $_GET['action'] )
	{
		$namedisp = '_' . $name . '_caption';
		$namedisp_changed = true;
	}
	else
	{
		if ( $_POST['caption'] == _t( $field['namedisp'] ) )
		{
			$namedisp = $field['namedisp'];
			$namedisp_changed = false;
		}
		else
		{
			$namedisp = '_' . $name . '_caption';
			$namedisp_changed = true;
		}
	}

	// Get field description.
	if ( 'add' == $_GET['action'] )
	{
		$namenote = ( $_POST['desc'] ? '_' . $name . '_desc' : '' );
		$namenote_changed = true;
	}
	else
	{
		if ( $_POST['desc'] == _t( $field['namenote'] ) )
		{
			$namenote = $field['namedisp'];
			$namenote_changed = false;
		}
		else
		{
			$namenote = ( $_POST['desc'] ? '_' . $name . '_desc' : '' );
			$namenote_changed = true;
		}
	}

	// Get user visibility.
	$visible = '';
	if ( 'on' == $_POST['visible_to_visitor'] )
	{
		$visible .= 'user';
	}
	if ( 'on' == $_POST['visible_to_member'] )
	{
		$visible = cat_string( $visible, 'memb' );
	}
	if ( 'on' == $_POST['visible_to_admin'] )
	{
		$visible = cat_string( $visible, 'adm' );
	}

	// Get user edit possibility.
	$editable = '';
	if ( 'on' == $_POST['editable_for_member'] )
	{
		$editable .= 'memb';
	}
	if ( 'on' == $_POST['editable_for_admin'] )
	{
		$editable = cat_string( $editable, 'adm' );
	}

	// Get page visibility.
	$show_on_page = '';
	if ( 'on' == $_POST['show_on_all'] )
	{
		$show_on_page = '0';
	}
	if ( 'on' == $_POST['show_on_join'] )
	{
		$show_on_page = cat_string( $show_on_page, '3' );
	}
	if ( 'on' == $_POST['show_on_view_profile'] )
	{
		$show_on_page = cat_string( $show_on_page, '7' );
	}
	if ( 'on' == $_POST['show_on_edit_profile'] )
	{
		$show_on_page = cat_string( $show_on_page, '25' );
	}

	// Generate extra field content.
	$extra = '';
	switch ( $field_type )
	{
	case 'p':
	case 'c':
		$extra = (int)$_POST['edit_box_length'];
		break;

	case 'date':
		$extra = process_db_input( $_POST['edit_box_length'] );
		break;
	
	case 'e':
	case 'rb':
	case 'set':
		$temp = explode( "\r\n", $_POST['choices'] );
		$extra = '';
		foreach ( $temp as $value )
		{
			$value = process_pass_data($value);
			$value = str_replace("'", '`', $value);
			$replace_arr = array(' ', '.', ',', "\\");
			$value = str_replace($replace_arr, '_', $value);
			$extra = cat_string( $extra, "\'$value\'" );
		}

		$result = db_arr("SHOW COLUMNS FROM `Profiles` LIKE '$name'");
		$extratmp = str_replace("\\", "", $extra);

		// Alter existing field
		if ( $result['Default'] )
		{
			$alter_field_type = ($field_type == 'set' ? "SET($extratmp)" : "ENUM($extratmp)");
			if ( strstr($extratmp, "'". $result['Default'] ."'") )
			{
				db_res("ALTER TABLE `Profiles` MODIFY `$name` $alter_field_type NOT NULL DEFAULT '{$result['Default']}'");
			}
			else
			{
				$defval = substr($extratmp, 0, strpos($extratmp, ","));
				db_res("ALTER TABLE `Profiles` MODIFY `$name` $alter_field_type NOT NULL DEFAULT {$defval}");
			}
		}
	break;

	case 'a':
		$extra = (int)$_POST['memo_rows'] ."x". (int)$_POST['memo_cols'];
	break;
	}

	// Generate check script.
	if ( 'Email' != $name && 'Email,Email1' != $name && 'NickName' != $name && 'Password,Password1' != $name )
	{
		if ( $_POST['min_value'] && $_POST['max_value'] )
		{
			$check = 'return (strlen($arg0) >= '. $_POST['min_value'] .' && strlen($arg0) <= '. $_POST['max_value'] .' ) ? true : false;';
		}
		else if ( $_POST['min_value'] )
		{
			$check = 'return (strlen($arg0) >= '. $_POST['min_value'] .') ? true : false;';
		}
		else if ( $_POST['max_value'] )
		{
			$check = 'return (strlen($arg0) <= '. $_POST['max_value'] .') ? true : false;';
		}
		else if ( 'on' == $_POST['mandatory'] )
		{
			$check = 'return strlen($arg0) > 0 ? true : false;';
		}
	}
	else
	{
		$length_check = ( $_POST['min_value'] ? 'strlen($arg0) >= '.$_POST['min_value'] . ' && ' : "" );
		$length_check .= ( $_POST['max_value'] ? ' strlen($arg0) <= '.$_POST['max_value']. ' && ' : "" );
		if ( 'Email' == $name )
		{
			$check = 'return (' . $length_check . 'strstr($arg0,"@") && strstr($arg0,".")  && conf_email($arg0,$_COOKIE[memberID]))  ? true : false;';
		}
		else if ( 'NickName' == $name )
		{
			$check = 'return (' . $length_check . 'conf_nick($arg0,$_COOKIE[memberID]) && preg_match("/^[0-9A-Za-z]+$/",$arg0)) ? true : false;';
		}
		else if ( 'Password,Password1' == $name )
		{
			$check = 'return (' . $length_check . '!strcmp($arg0,$_POST[Password2])) ? true : false;';
		}
		else if ( 'Email,Email1' == $name )
		{
			$check = 'return (eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$", $arg0) && !strcmp($arg0,$_POST[Email2]) && conf_email($arg0,$_COOKIE[memberID])) ? true : false;';
		}
		else
		{
			$check = '';
		}
	}

	// Generate error message.
	if ( 'add' == $_GET['action'] )
	{
		$err_msg = '_' . $name . '_err_msg';
		$err_msg_changed = true;
	}
	else
	{
		if ( $_POST['err_msg'] == _t( $field['because'] ) )
		{
			$err_msg = $field['because'];
			$err_msg_changed = false;
		}
		else
		{
			$err_msg = '_' . $name . '_err_msg';
			$err_msg_changed = true;
		}
	}

	// Generate search type.
	$search_type = ( $_POST['search_t'] ? $_POST['search_t'] : 'none' );

	// Generate 'search_hide' value.
	$search_hide = 0;
	if ( 'on' == $_POST['search_hidden'] )
	{
		$search_hide = 1;
	}

	// Generate 'search_cols' value.
	$search_cols = ( $_POST['search_cols'] ? (int)$_POST['search_cols'] : 0 );

	// Generate 'search_order' value.
	$q_str = 'SELECT `search_order` FROM `ProfilesDesc` ORDER BY `search_order` DESC LIMIT 1';
	$row = db_arr( $q_str );
	$search_order = $row['search_order'] + 1;

	// Generate match type
	$match_type = ( $_POST['match_t'] ? $_POST['match_t'] : 'none' );

	// Generate match field
	$match_field = ( $_POST['match_f'] && $_POST['match_f'] != 'none' ? $_POST['match_f'] : '' );

	// Generate match extra
	$match_extra = ( $_POST['match_perc'] ? $_POST['match_perc'] : '' );

	$min_length = process_db_input($_POST['min_value']);
	$max_length = process_db_input($_POST['max_value']);

	if ( 'add' == $_GET['action'] )
	{
		// Generate query to add record to ProfilesDesc.
		$q_str = "INSERT INTO `ProfilesDesc` (`name`, `namedisp`, `namenote`, `extra`, `type`, `order`, `visible`, `editable`, `show_on_page`, `check`, `because`, `min_length`, `max_length`, `search_type`, `search_hide`, `search_cols`, `search_order`, `match_type`, `match_field`, `match_extra`)";
		$q_str .= "VALUES ('$name', '$namedisp', '$namenote', '$extra', '$field_type', '$order', '$visible', '$editable', '$show_on_page', '$check', '$err_msg', '$min_length', '$max_length', '$search_type', '$search_hide', '$search_cols', '$search_order', '$match_type', '$match_field', '$match_extra')";
	}
	elseif ( 'edit' == $_GET['action'] )
	{
		$namedisp_sql = ( $namedisp_changed ? "`namedisp` = '$namedisp'," : "" );
		$namenote_sql = ( $namenote_changed ? "`namenote` = '$namenote'," : "" );
		$err_msg_sql = ( $err_msg_changed ? "`because` = '$err_msg'," : "" );
		$q_str = "UPDATE `ProfilesDesc` SET {$namedisp_sql} {$namenote_sql} `extra` = '$extra', `visible` = '$visible', `editable` = '$editable', `show_on_page` = '$show_on_page', `check` = '$check', {$err_msg_sql} `min_length` = '$min_length', `max_length` = '$max_length', `search_type` = '$search_type', `search_hide` = '$search_hide', `search_cols` = '$search_cols', `match_type` = '$match_type', `match_field` = '$match_field', `match_extra` = '$match_extra' WHERE ID = {$_GET['ID']}";
	}
	// Execute query.
	db_res( $q_str );

	$type = '';
	// Generate field type for Profiles table.
	switch ( $field_type )
	{
	case 'c':
		$type = 'VARCHAR(255)';
		break;

	case 'e':
	case 'rb':
		$arr = explode( "\r\n", $_POST['choices'] );
		$type = '';
		foreach ( $arr as $value )
		{
			$value = process_pass_data($value);
			$value = str_replace("'", '`', $value);
			$replace_arr = array(' ', '.', ',', "\\");
			$value = str_replace($replace_arr, '_', $value);
			$type = cat_string( $type, "'$value'" );
		}
		$type = "ENUM ($type)";
	break;

	case 'a':
		$type = 'MEDIUMTEXT';
		break;

	case 'set':
		$arr = explode( "\r\n", $_POST['choices'] );
		$type = '';
		foreach ( $arr as $value )
		{
			$value = process_pass_data($value);
			$value = str_replace("'", '`', $value);
			$replace_arr = array(' ', '.', ',', "\\");
			$value = str_replace($replace_arr, '_', $value);
			$type = cat_string( $type, "'$value'" );
		}
		$type = "SET ($type)";
	break;
	}

	if ( $field_type != '0' && 'add' == $_GET['action'] )
	{
		// Generate query to add new field to Profiles.
		$vals = split (",", $name);
		$db_name = $vals[0];
		$q_str = "ALTER TABLE `Profiles` ADD `$db_name` {$type} NOT NULL";
		db_res( $q_str );
	}

	// Generate language file content.
	$lang_file = '';
	$langFailFields = '';
	if ( $namedisp_changed )
	{
		$lang_file .= "'{$_POST['caption']}';<br />";
		if ( !addStringToLanguage( $namedisp, $_POST['caption'] ) && !updateStringInLanguage( $namedisp, $_POST['caption'] ) )
		{
			$langFailFields .= "'$namedisp';<br />";
		}
	}
	if ( $namenote_changed && $namenote )
	{
		$lang_file .= "'{$_POST['desc']}';<br />";
		if ( !addStringToLanguage( $namenote, $_POST['desc'] ) && !updateStringInLanguage( $namenote, $_POST['desc'] ) )
		{
			$langFailFields .= "'$namenote';<br />";
		}
	}
	// Error message.
	if ( $err_msg_changed )
	{
		$lang_file .= "'{$_POST['err_msg']}';<br />";
		if ( !addStringToLanguage( $err_msg, $_POST['err_msg'] ) && !updateStringInLanguage( $err_msg, $_POST['err_msg'] ) )
		{
			$langFailFields .= "'$err_msg';<br />";
		}
	}
	// Drop-down box options.
	if ( $_POST['choices'] )
	{
		$arr = explode( "\r\n", $_POST['choices'] );
		foreach( $arr as $value )
		{
			$value = process_pass_data($value);
			$entered_value = $value;
			$value = str_replace("'", '`', $value);
			$replace_arr = array(' ', '.', ',', "\\");
			$value = str_replace($replace_arr, '_', $value);
			$lang_file .= "'{$entered_value}';<br />";
			if ( !addStringToLanguage( "_{$value}", $entered_value ) && !updateStringInLanguage( "_{$value}", $entered_value ) )
			{
				$langFailFields .= "'{$entered_value}';<br />";
			}
		}
	}

	// Compile language files if needed
	if ( strlen($lang_file) )
		compileLanguage();

	if ( 'add' == $_GET['action'] )
	{
		echo "<p><span style=\"color:#ff6666;font-weight:bold\">New field has been added.</span></p>";
	}
	else if ( 'edit' == $_GET['action'] )
	{
		echo "<p><span style=\"color:#ff6666;font-weight:bold\">Field has been updated.</span></p>";
	}

	if ( strlen($lang_file) )
	{
		echo "Following strings were added or updated in your language files:<br />";
		echo "<pre>$lang_file</pre>";
		if ( strlen($langFailFields) )
		{
			echo "Fail to insert or update following strings:<br />";
			echo "<pre>$langFailFields</pre>";
		}
	}
	echo "<p><a href=\"profile_fields.php\">Continue</a></p>";
}

/**
 * Check general purpose and type-specific parameters.
 * @param $field_type		Field type.
 * @retval					Array of error messages.
 */
function check_parameters( $field_type )
{
	$retval = array ();
	// Check general purpose parameters.

	// Check field name.
	if ( !$_POST['field_name'] )
	{
		array_push( $retval, 'Field name may not be empty.' );
	}

	// Check caption.
	if ( !$_POST['caption'] )
	{
		array_push( $retval, 'Caption may not be empty.' );
	}
/*
	// Check member visibility.
	if ( 'on' != $_POST['visible_to_visitor']
		&& 'on' != $_POST['visible_to_member']
		&& 'on' != $_POST['visible_to_admin'] )
	{
		array_push( $retval, 'You must specify who is authorized to see the field ("Visible to" property).' );
	}
*/
	// Check page visibility.
	if ( 'on' != $_POST['show_on_all']
		&& 'on' != $_POST['show_on_join']
		&& 'on' != $_POST['show_on_view_profile']
		&& 'on' != $_POST['show_on_edit_profile'] )
	{
		array_push( $retval, 'You must specify where to display the field ("Show on page" property).' );
	}

	// Check field type specific values.
	switch ( $field_type )
	{
	case 'c':
		$len = $_POST['edit_box_length'];
		if ( !$len || !is_numeric( $len ) || $len < 0 || $len > 100 )
		{
			array_push( $retval, 'Invalid edit box length (must be a number between 0 and 100).' );
		}
		$min_value = $_POST['min_value'];
		if ( $min_value && !is_numeric( $min_value ) )
		{
			array_push( $retval, 'Invalid min length value (must be a number or empty).' );
		}
		$max_value = $_POST['max_value'];
		if ( $max_value && !is_numeric( $max_value ) )
		{
			array_push( $retval, 'Invalid max length value (must be a number or empty).' );
		}
	break;

	case 'e':
	case 'rb':
	case 'set':
		if ( 'e' == $field_type )
		{
			$msg = 'Drop-down box choices are not set.';
		}
		else if ( 'rb' == $field_type )
		{
			$msg = 'Radio button choices are not set.';
		}
		else if ( 'set' == $field_type )
		{
			$msg = 'Set choices are not set.';
		}
		$options = $_POST['choices'];
		if ( 0 == strlen( $options ) )
		{
			array_push( $retval, $msg );
		}
	break;

	case 'a':
		$memo_rows = $_POST['memo_rows'];
		if ( !$memo_rows || !is_numeric( $memo_rows ) || $memo_rows < 0 || $memo_rows > 100)
		{
			array_push( $retval, 'Invalid number of memo rows (must be a number between 0 and 100).' );
		}
		$memo_cols = $_POST['memo_cols'];
		if ( !$memo_cols || !is_numeric( $memo_cols ) || $memo_cols < 0 || $memo_cols > 100 )
		{
			array_push( $retval, 'Invalid number of memo columns (must be a number between 0 and 100).' );
		}
		$min_value = $_POST['min_value'];
		if ( $min_value && !is_numeric( $min_value ) )
		{
			array_push( $retval, 'Invalid min length value (must be a number or empty).' );
		}
		$max_value = $_POST['max_value'];
		if ( $max_value && !is_numeric( $max_value ) )
		{
			array_push( $retval, 'Invalid max length value (must be a number or empty).' );
		}
	break;
	}

	return $retval;
}

/**
 * Get new field order relative to another field.
 * @param $order		The order of another field (this can be 'begin',
 *						'end', or id of the preceding field).
 * @retval				Field order.
 */
function get_field_order( $order )
{
	$order = (int)$order;

	$retval = 0;
	if ( 'begin' == $order )
	{
		$q_str = "SELECT `order` FROM ProfilesDesc ORDER BY `order` ASC LIMIT 1";
		$row = db_arr( $q_str );
		$retval = $row['order'] - 0.1;
	}
	else if ( 'end' == $order )
	{
		$q_str = "SELECT `order` FROM ProfilesDesc ORDER BY `order` DESC LIMIT 1";
		$row = db_arr( $q_str );
		$retval = $row['order'] + 1;
	}
	else
	{
		// Get upper order.
		$q_str = "SELECT `order` FROM ProfilesDesc WHERE ID = $order";
		$row = db_arr( $q_str );
		$lower_order = $row['order'];

		// Get lower order.
		$q_str = "SELECT `order` FROM ProfilesDesc WHERE `order` > $lower_order ORDER BY `order` ASC";
		$row = db_arr( $q_str );
		if ( !$row )
		{
			$retval = $lower_order + 1;
		}
		else
		{
			$upper_order = $row['order'];
			$retval = ($lower_order + $upper_order) / 2;
		}
	}
	return $retval;
}

/**
 * Concatenate strings adding a comma.
 * @param $str1			First string.
 * @param $str1			Second string.
 * @retval				Concatenated string.
 */
function cat_string( $str1, $str2 )
{
	if ( strlen( $str1 ) > 0 )
	{
		$str1 .= ',';
	}
	$str1 .= $str2;
	return $str1;
}

/**
 * Delete profile field.
 */
function delete_field( $ID, $res )
{
	// Make int from ID
	$ID = (int)$ID;

	// Collect all fields and determine which of them are group
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
	$group_fields_num = $field_groups[$rows[$field_index]['db_name']]['count'];

	// Get field name and type
	$name = $rows[$field_index]['db_name'];
	$type = $rows[$field_index]['type'];

	// Break row group
	if ( $rows[$field_index]['group_mark'] != '' )
	{
		$i = $field_index - 1;
		while ( $rows[$i]['group_mark'] != '' && $i >= 0 )
		{
			$query_str = "UPDATE `ProfilesDesc` SET `group_mark` = '' WHERE ID = {$rows[$i]['ID']}";
			db_res( $query_str );
			$i--;
		}
		$i = $field_index + 1;
		while ( $rows[$i]['group_mark'] != '' && $i < $total_rows )
		{
			$query_str = "UPDATE `ProfilesDesc` SET `group_mark` = '' WHERE ID = {$rows[$i]['ID']}";
			db_res( $query_str );
			$i++;
		}
	}

	// Delete records from ProfilesDesc table
	if ( $group_fields_num > 1 )
	{
		for ( $i = 0; $i < $total_rows; $i++ )
			if ( $rows[$i]['db_name'] == $rows[$field_index]['db_name'] )
			{
				$query_str = "DELETE FROM `ProfilesDesc` WHERE ID = {$rows[$i]['ID']}";
				db_res( $query_str );
			}
	}
	else
	{
		$query_str = "DELETE FROM `ProfilesDesc` WHERE ID = {$rows[$field_index]['ID']}";
		db_res( $query_str );
	}

	if ( $type != '0' )
	{
		// Delete field from Profiles table.
		$query_str = "ALTER TABLE `Profiles` DROP `$name`";
		db_res( $query_str );
	}
}

/**
 * Display field type control.
 * @param $value			Default value.
 * @param $read_only		Is read only.
 */
function display_field_type( $value, $read_only )
{
	$types = array (
		'c' => 'edit box',
		'e' => 'drop-down box',
		'a' => 'memo',
		'0' => 'divider',
		'rb' => 'set of radio buttons',
		'set' => 'set of checkboxes',
		'p' => 'password',
		'date' => 'date'
	);

	if ( false != $read_only )
	{
		echo "<td><input type=\"hidden\" name=\"field_type\" value=\"$value\" />$types[$value]</td>\n";
	}
	else
	{
		echo "<td><select name=\"field_type\" onChange=\"javascript: var val = this.value; window.location.href = 'profile_fields.php?action=add&field_type=' + val;\">\n";
		foreach ( $types as $key => $val )
		{
			if ( 0 == strcmp( $value, $key ) )
			{
				echo "<option value=\"$key\" selected=\"selected\">$val</option>\n";
			}
			else
			{
				echo "<option value=\"$key\">$val</option>\n";
			}
		}
		echo "</td></tr>\n";
	}
}

/**
 * Display control for selecting 'insert after' value.
 * @param $value			Default value.
 */
function display_insert_after( $value )
{
	// Generate an array of possible values.
	$insert_after = array ( 'begin' => 'At the beginning', 'end' => 'At the end' );
	$q_str = "SELECT `ID`, `name` FROM `ProfilesDesc` ORDER BY `order` ASC";
	$res = db_res( $q_str );
	while ( $row = mysql_fetch_array( $res ) )
	{
		$insert_after[$row['ID']] = "After {$row['name']}";
	}
	// Display values.
	echo "<td><select name=\"insert_after\">\n";
	foreach ( $insert_after as $key => $val )
	{
		if ( $key == $value )
		{
			$selected = 'selected';
		}
		else
		{
			$selected = '';
		}
		echo "<option value=\"$key\" $selected>$val</option>\n";
	}
	echo "</select></td></tr>\n";
}

/**
 * Display control for selecting 'match field' value.
 * @param $value			Default value.
 */
function display_match_field( $value )
{
	// Generate an array of possible values.
	$match_field = array ( 'none' => 'None' );
	$q_str = "SELECT `ID`, `name` FROM `ProfilesDesc` WHERE `type` != '0' ORDER BY `order` ASC";
	$res = db_res( $q_str );
	while ( $row = mysql_fetch_array( $res ) )
	{
		$fname = get_field_name( $row );
		$field_groups[$fname]['count']++;
		if ( $field_groups[$fname]['count'] == 1 )
			$match_field[$fname] = $fname;
	}
	// Display values.
	echo "<td><select name=\"match_f\">\n";
	foreach ( $match_field as $key => $val )
	{
		if ( $key == $value )
		{
			$selected = 'selected';
		}
		else
		{
			$selected = '';
		}
		echo "<option value=\"$key\" $selected>$val</option>\n";
	}
	echo "</select></td></tr>\n";
}

/**
 * Display field name.
 * @param $value			Default value.
 * @param $read_only		Is field read-only.
 */
function display_field_name( $value, $read_only )
{
	if ( false != $read_only )
	{
		echo "<tr><td>Field name</td>\n";
		echo "<td><input type=\"hidden\" name=\"field_name\" value=\"$value\" />$value</td>\n";
	}
	else
	{
		echo "<tr><td>Field name<br>(Please use alphanumerical chars only)</td>\n";
		echo "<td><input type=\"text\" name=\"field_name\" value=\"$value\"/></td></tr>\n";
	}
}

/**
 * Display field description.
 * @param $value			Default value.
 * @param $read_only		Is field read-only.
 */
function display_field_desc( $value, $read_only )
{
	if ( false != $read_only )
	{
		echo "<td><input type=\"hidden\" name=\"desc\" value=\"$value\" />$value</td></tr>\n";
	}
	else
	{
		echo "<td><input type=\"text\" name=\"desc\" value=\"$value\" size=\"60\" /></td></tr>\n";
	}
}

/**
 * Determine if field could be edited from admin panel.
 * @param $field			Array of extracted from database values
 */
function field_editable( $field )
{
	$aForbid = array( 'Status'/*'EmailNotify'*/);
	if (!in_array($field['name'], $aForbid))
	{
		switch ( $field['type'] )
		{
			case 'r':
			case 'rr':
			case 'en':
			case 'eny':
				return false;
				break;
			default:
				return true;
		}
	}
	else
	{
		return false;
	}
}

/**
 * Swap order of two field groups
 * @param $field_buffer - array of fields in format [$index]['value']
 * @param $border_info - info about field groups borders
 *		$border_info['first_start'] - start of first group
 *		$border_info['first_end'] - end of first group
 *		$border_info['second_start'] - start of second group
 *		$border_info['second_end'] - end of second group
 *		WARNING!!! $border_info['first_end'] + 1 = $border_info['second_start']
 */
function swap_groups_order( $field_buffer, $border_info )
{
	// First swap action
	$offset = 0;
	for ($i = $border_info['second_start']; $i <= $border_info['second_end']; $i++)
	{
		// Get the order of the given field.
		$src_order = $field_buffer[$border_info['first_start'] + $offset]['order'];

		// Get the order of the field preceding the given one.
		$dest_id = $field_buffer[$i]['ID'];

		// Change order for the given field.
		$query_str = "UPDATE `ProfilesDesc` SET `order` = $src_order WHERE `ID` = $dest_id";
		db_res( $query_str );

		$offset++;
	}

	// Second swap action
	$offset = $border_info['second_end'] - $border_info['second_start'] - $border_info['first_end'] + $border_info['first_start'];
	for ($i = $border_info['first_start']; $i <= $border_info['first_end']; $i++)
	{
		// Get the order of the given field.
		$src_order = $field_buffer[$border_info['second_start'] + $offset]['order'];

		// Get the order of the field preceding the given one.
		$dest_id = $field_buffer[$i]['ID'];

		// Change order for the given field.
		$query_str = "UPDATE `ProfilesDesc` SET `order` = $src_order WHERE `ID` = $dest_id";
		db_res( $query_str );

		$offset++;
	}

}
?>
