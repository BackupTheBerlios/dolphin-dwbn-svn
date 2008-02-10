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

// page index
$_page['name_index'] = 46;

// authenticate administrator
$logged['admin'] = member_auth( 1 );

// defina page headers
$_page['header'] = "Daily Quotes";
$_page['header_text'] = "Add, remove and edit Daily Quotes";

// describe table strure to display page ==============================================================

$allow_edit = 1;
$allow_add = 1;
$allow_delete = 1;

$use_table  = 'DailyQuotes';

// fields definition array
//  indexes - exact names of the table fields
//  values  - types
//    index - primary key if the field (integer)
//    char  - string fieled max 255 chars (char/varchar)
//    area  - string fieled (mediumtext)
$fields_types  = array('ID' => 'index', 'Text' => 'area', 'Author' => 'char');
$fields_titles = array('ID' => 'ID', 'Text' => 'Qutes text', 'Author' => 'Quote author');
$fields_sizes  = array('ID' => '10%', 'Text' => '60%', 'Author' => '30%');
$fields_align  = array('ID' => 'center', 'Text' => 'left', 'Author' => 'left');

$class_titles = "panel";
$class_data   = "table";
$class_error  = "table_err";
$table_width  = "90%";

// ====================================================================================================

// put common top HTML code
TopCodeAdmin ();
ContentBlockHead("Daily Quotes");

	echo "<table class=\"text\" align=\"center\" width=\"$table_width\"><tr><td height=\"30\"><a href=\"quotes.php?action=insert\">Add new<a></td></tr></table>\n\n";

	if ('update' == $_POST['action'])
	{
		$result = update_db();
	}
	else if ('insert' == $_POST['action'])
	{
		$result = insert_db();

	}
	else if ( 0 < strlen ($_POST['rows_form_submit']))
	{
		$result = delete_records();
	}

	if ( strlen($result) )
		echo "<br><center>$result</center><br>";

	if ('edit' == $_GET['action'])
	{
		echo get_edit_new_form (1);
	}
	else
	if ('insert' == $_GET['action'])
	{
		echo get_edit_new_form (0);
	}

	PrintData();

// put bottom top HTML code
BottomCode();

// public functions ===================================================================================

/**
 * print list of the table date
 */
function PrintData()
{
	global $fields_types;
	global $fields_titles;
	global $fields_sizes;
	global $fields_align;
	global $class_titles;
	global $class_data;
	global $class_error;
	global $table_width;
	global $use_table;
	global $allow_delete;
	global $allow_edit;

	$query = "SELECT * FROM $use_table";

	$res = db_res ($query);

	if ( !mysql_num_rows($res) )
	{
		echo "<div align=center class=\"err\">No data available</div>";
		return;
	}

	echo "<table cellspacing=2 cellpadding=1 class=\"text\" width=\"$table_width\" align=\"center\">\n\n";

	// print titles

	echo "<tr class=\"$class_titles\">\n";
	if ( $allow_delete )
	{
		echo "<form name=\"rows_form\" method=\"POST\" action=\"quotes.php\">";
		echo "<td>&nbsp;</td>\n";
	}
	foreach ($fields_titles as $k => $v)
	{
		echo "\t<td width=\"{$fields_sizes[$k]}\" align=\"{$fields_align[$k]}\">$v</td>\n";
	}
	if ( $allow_edit )
	{
		echo "<td>&nbsp;</td>\n";
	}
	echo "</tr>\n\n";

	// print data

	while ( $row = mysql_fetch_array ($res) )
	{
		if ( $row[get_index_fieldname()] == $_GET['index_field'] || $row[get_index_fieldname()] == $_POST['index_field'])
		{
			echo "<tr class=\"$class_error\">\n";
		}
		else
		{
			echo "<tr class=\"$class_data\">\n";
		}

		// checkboxes
		if ( $allow_delete )
		{
			echo "<td align=center><input type=checkbox name=\"".$row[get_index_fieldname()]."\"></td>\n";
		}

		// data
		foreach ( $fields_sizes as $k => $v )
		{
			echo "\t<td width=\"$v\" align=\"{$fields_align[$k]}\">". process_text_output($row[$k]) ."</td>\n";
		}

		// edit button
		if ( $allow_edit )
		{
			echo "<td align=center><a href=\"?action=edit&index_field=". $row[get_index_fieldname()]. "\">Edit</a></td>\n";
		}

		echo "</tr>\n\n";
	}

	echo "</table>\n\n";

	if ( $allow_delete )
	{
		echo get_checkbox_menu() . "</form>";
	}


ContentBlockFoot();
}

// private functions ==================================================================================


/**
 * get fields list
 */
function get_fields_list ()
{
	global $fields_types;

	$ret = "";
	foreach ($fields_types as $k)
	{
		$ret += "`$k`,";
	}
	return substr ($ret, 0, -1);
}

/**
 * get index field name
 */
function get_index_fieldname ()
{
	global $fields_types;

	$ret = "";
	foreach ($fields_types as $k => $v)
	{
		if ($v == 'index' ) return $k;
	}
	return $fields_types[0];
}


/**
 * get HTML code for delete records menu
 */
function get_checkbox_menu ()
{
	global $table_width;
	return <<<EOS
<table class="text" width="$table_width" align="center" border="0">
<tr>
    <td width="30%">&nbsp;<a href="javascript: void(0);" onclick="setCheckboxes( 'rows_form', true ); return false;">Check all</a> / <a href="javascript: void(0);" onclick="setCheckboxes( 'rows_form', false ); return false;">Uncheck all</a>&nbsp;</td>
    <td width="20%">with selected:</td>
    <td width="50%"><input class=text type=submit name="rows_form_submit" value="Delete"></td>
</tr>
</table>
EOS;
}

/**
 * get HTML code for delete records menu
 */
function get_edit_new_form ($edit_form)
{
	global $table_width;
	global $fields_types;
	global $fields_titles;
	global $use_table;
	global $class_data;

	if ( $edit_form )
	{
		$query = "SELECT * FROM $use_table WHERE ". get_index_fieldname() ."=". (int)$_GET['index_field']. " LIMIT 1";
		$quote_arr = db_arr ($query);
	}

	$ret = "";
	$ret .= "<table class=\"text\" width=\"$table_width\" align=\"center\">\n\n";
	$ret .= "<form method=\"post\" action=\"quotes.php\">\n\n";
	if ( $edit_form )
	{
		$ret .= "<input type=\"hidden\" name=\"action\" value=\"update\" \>\n";
		$ret .= "<input type=\"hidden\" name=\"index_field\" value=\"". (int)$_GET['index_field']. "\" \>\n";
	}
	else
	{
		$ret .= "<input type=\"hidden\" name=\"action\" value=\"insert\" \>\n";
	}
	foreach ($fields_types as $k => $v)
	{
		switch ($v)
		{
		case 'index':
			break;
		case 'area':
			$ret .= "<tr class=\"$class_data\">\n<td valign=top>{$fields_titles[$k]}</td>\n";
			$ret .= "<td><textarea name=\"$k\" rows=5 cols=60>\n";
			if ( $edit_form )
			{
				$ret .= htmlspecialchars($quote_arr[$k]);
			}
			$ret .= "</textarea></td>\n</tr>\n\n";
			break;
		case 'char':
			$ret .= "<tr class=\"$class_data\">\n<td>{$fields_titles[$k]}</td>\n";
			$ret .= "<td><input name=\"$k\" size=60 value=\"";
			if ( $edit_form )
			{
				$ret .= htmlspecialchars($quote_arr[$k]);
			}
			$ret .= "\"/></td>\n</tr>\n\n";
			break;
		}
	}

	$ret .= "<tr><td height=\"40\" colspan=\"2\" align=\"center\">\n";
	$ret .= "<input class =text type=\"submit\" name=\"form_sibmit\" value=\"";
	if ( $edit_form )
	{
		$ret .= "Update";
	}
	else
	{
		$ret .= "Insert";
	}
	$ret .= "\" />";
	$ret .= "</td></tr>\n\n";

	$ret .= "</form>\n";
	$ret .= "</table>\n\n";
	return $ret;
}

/**
 * insert new record to the database
 */
function insert_db ()
{
	global $use_table;
	global $fields_types;


	$query = "INSERT INTO $use_table SET ";

    foreach ($fields_types as $k => $v)
    {
        switch ($v)
        {
        case 'index':
            break;
        case 'area':
		case 'char':
			$query .= "$k = '". process_db_input( $_POST[$k] ) ."', ";
			break;
		}
	}
	$query = substr ($query, 0, -2);

	if (db_res ($query))
	{
		return "<div class=\"err\">New data was successfully added</div>";
	}
	else
	{
		return "<div class=\"err\">New data was NOT successfully added</div>";
	}
}

/**
 * update record to the database
 */
function update_db ()
{
    global $use_table;
    global $fields_types;


    $query = "UPDATE $use_table SET ";

    foreach ($fields_types as $k => $v)
    {
        switch ($v)
        {
        case 'index':
            break;
        case 'area':
        case 'char':
            $query .= "$k = '". process_db_input( $_POST[$k] ) ."', ";
            break;
        }
    }

    $query = substr ($query, 0, -2);

	$query .= " WHERE ".get_index_fieldname()." = ". (int)$_POST['index_field'] ." LIMIT 1";

    if (db_res ($query))
    {
        return "<div class=\"err\">Data was successfully updated</div>";
    }
    else
    {
        return "<div class=\"err\">Data was NOT updated</div>";
    }
}

/**
 * delete the market records from the database
 */
function delete_records ()
{
    global $use_table;
    global $fields_types;
	global $MySQL;

	$num = 0;
	foreach ($_POST as $k => $v)
	{
		$k = (int)$k;
		if ( $k > 0 && $v == 'on' )
		{
			$query = "DELETE FROM $use_table WHERE ". get_index_fieldname() ." = $k";
			if ( db_res ($query) )
				$num += mysql_affected_rows( $MySQL->link );
		}
	}
	return "<div class=\"err\">$num rows was deleted</div>";
}


?>
