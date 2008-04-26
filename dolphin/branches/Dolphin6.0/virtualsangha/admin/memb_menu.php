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
require_once( BX_DIRECTORY_PATH_INC . 'menu.inc.php' );

// Check if administrator is logged in.  If not display login form.
$logged['admin'] = member_auth( 1 );

$_page['header'] = 'Member Menu';
$_page['css_name'] = 'memb_menu.css';

// Check GET variables.
if ( isset($_GET['ID']) && ( 'move_up' == $_GET['action'] ) )
{
	move_up($_GET['ID']);
	compileMenus();
	header('location:' . $_SERVER['PHP_SELF']);
}

if ( isset($_GET['ID']) && ( 'move_down' == $_GET['action'] ) )
{
	move_down($_GET['ID']);
	compileMenus();
	header('location:' . $_SERVER['PHP_SELF']);
}

if ( $_GET['ID'] && ( 'delete' == $_GET['action'] ) )
{
	// Process deleting
	delete_item($_GET['ID'], $res);
	compileMenus();
	header('location:' . $_SERVER['PHP_SELF']);
}

if (isset($_POST['Add']))
{
	add_or_edit_item();
	compileMenus();
}

if ('reset' == $_GET['action'])
{
	reset_menu();
	compileMenus();
	header('location:' . $_SERVER['PHP_SELF']);
}

TopCodeAdmin();
ContentBlockHead("Member Menu");

	// Get a list of all fields.
	$query_str = "SELECT * FROM `MemberMenu` ORDER BY `MenuOrder` ASC";
	$res = db_res($query_str);

	if ( $_GET['action'] == 'upload' )
	{
		display_form( (int)$_GET['ID'] );
	}
	else
	{
		echo "<p class=text><a href=\"{$_SERVER['PHP_SELF']}?action=upload\">Add new item</a></p>\n";
		echo "<p class=text><a href=\"{$_SERVER['PHP_SELF']}?action=reset\">Reset items</a></p>\n";
		display_menu($res);
	}


ContentBlockFoot();

BottomCode();


//display all current menu items
function display_menu ($res)
{
	?>
	<table width="590" border="1" class="profile_fields">
			<tr class="section">
				<th>Name</th>
				<th>Link</th>
				<th>Group</th>
				<th colspan="2">Visible</th>
				<th colspan="2">Order</th>
				<th>Del</th>
				<th>Edit</th>
			</tr>
			<tr class="section">
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th aligh>visitor</th>
				<th>member</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
	<?
	
	$nums = 0;
	$total_rows = mysql_num_rows($res);
	while($arr = mysql_fetch_array($res))
	{
		$move_up   = "";
		$move_down = "";
		$delete    = "";
		if ($nums != 0)
			$move_up = "<a href=\"{$_SERVER['PHP_SELF']}?ID={$arr['ID']}&action=move_up\"><img src=\"images/arrow_up.gif\" alt=\"Move up\" border=\"0\"/></a>";
		else
			$move_up = '&nbsp;';
		
		if ($nums != $total_rows - 1)
			$move_down = "<a href=\"{$_SERVER['PHP_SELF']}?ID={$arr['ID']}&action=move_down\"><img src=\"images/arrow_down.gif\" alt=\"Move down\" border=\"0\"/></a>";
		else
			$move_down = '&nbsp;';
		
		if ($arr['Editable'] == '1')
			$delete = "<a href=\"{$_SERVER['PHP_SELF']}?ID={$arr['ID']}&action=delete\"><img src=\"images/delete.gif\" alt=\"Delete\" border=\"0\" title=\"Delete\" /></a>";
		else
			$delete = '&nbsp;';
		
		$edit =   "<a href=\"{$_SERVER['PHP_SELF']}?ID={$arr['ID']}&action=upload\"><img src=\"images/edit.gif\" alt=\"Edit\" border=\"0\" title=\"Edit\" /></a>";
		
		$vis_vis = (strpos($arr['Visible'],"non")!==FALSE) ? "yes" : "&nbsp;";
		$vis_mem = (strpos($arr['Visible'],"mem")!==FALSE) ? "yes" : "&nbsp;";
		$group   = ($arr['MenuGroup'] > 0) ? get_group_name($arr['MenuGroup']) : "&nbsp;";
		
		$tr_class = ($nums == 0 || $nums%2 == 0) ? "odd_row" : "even_row";
		
		$short_link = substr( $arr['Link'], 0, 35 );
		if( strlen($short_link) < strlen($arr['Link']) )
			$short_link .= '...';
		
		echo "<tr class=\"$tr_class\">";
		echo "<td>"._t($arr['Caption'])."</td>";
		echo "<td title=\"{$arr['Link']}\">{$short_link}</td>";
		echo "<td>$group</td>";
		echo "<td align=\"center\">$vis_vis</td>";
		echo "<td align=\"center\">$vis_mem</td>";
		echo "<td>$move_up</td>";
		echo "<td>$move_down</td>";
		echo "<td>$delete</td>";
		echo "<td>$edit</td>";
		echo "</tr>";
		$nums++;
	}
	echo "</table>";
}

//moving up item ($id - id of field)
function move_up ($id)
{
	$curr_up = 0;
	$curr_down_id = 0;

	$arr = db_arr("SELECT * FROM `MemberMenu` WHERE `ID` = $id");
	$curr_up = $arr['MenuOrder'];
	$curr_down = $curr_up - 1;

	$arr = db_arr("SELECT * FROM `MemberMenu` WHERE `MenuOrder` = $curr_down");
	$curr_down_id = $arr['ID'];

	db_res( "UPDATE `MemberMenu` set `MenuOrder` = $curr_up WHERE `ID` = $curr_down_id" );
	db_res( "UPDATE `MemberMenu` set `MenuOrder` = $curr_down WHERE `ID` = $id" );

}

//moving item down ($id - id of field)
function move_down ($id)
{
	$curr_up = 0;
	$curr_up_id = 0;

	$arr = db_arr("SELECT * FROM `MemberMenu` WHERE `ID` = $id");
	$curr_down = $arr['MenuOrder'];
	$curr_up = $curr_down + 1;

	$arr = db_arr("SELECT * FROM `MemberMenu` WHERE `MenuOrder` = $curr_up");
	$curr_up_id = $arr['ID'];
	db_res("UPDATE `MemberMenu` set `MenuOrder` = $curr_down WHERE `ID` = $curr_up_id");
	db_res("UPDATE `MemberMenu` set `MenuOrder` = $curr_up WHERE `ID` = $id");
}

//delete item ($id - id of field)
function delete_item ($id)
{
	$curr_del = 0;
	$arr = db_arr("SELECT * FROM `MemberMenu` WHERE `ID` = $id");
	$curr_del = $arr['MenuOrder'];
	if ($arr['MenuType'] == 'group')
	{
		db_res("UPDATE `MemberMenu` set `MenuGroup` = 0 WHERE `MenuGroup` = {$arr['ID']}");
	}
	db_res("DELETE FROM `MemberMenu` WHERE `ID` = $id");
	db_res("UPDATE `MemberMenu` set `MenuOrder` = `MenuOrder` - 1 WHERE `MenuOrder` > $curr_del");
}

//inserting or upldoaing item
function add_or_edit_item ()
{
	$m_name = "";
	$m_link = "";
	$m_order = 0;
	$m_group = "";
	$visible = "";
	$m_name  = isset($_POST['Name']) ? process_db_input($_POST['Name']) : process_db_input($_POST['NameH']);
	$m_link  = isset($_POST['Link']) ? addslashes(htmlspecialchars(process_pass_data($_POST['Link']))) : process_db_input($_POST['LinkH']);
	$m_capt  = isset($_POST['Caption']) ? process_db_input($_POST['Caption']) : "" ;
	$m_group = isset($_POST['MenuGroup']) ? (int)process_db_input($_POST['MenuGroup']) : 0 ;
	$m_order = (int)process_db_input($_POST['MenuOrder']);
	$m_type  = process_db_input($_POST['MenuType']);
	$m_targ  = isset($_POST['Target']) && $m_type != 'group' ? '_blank' : '';
	$vis_vis = process_db_input($_POST['VisibleVisitor']);
	$vis_mem = process_db_input($_POST['VisibleMember']);
	$vis_vis.= strlen($vis_mem)>0 && strlen($vis_vis)>0 ? "," : "";
	$visible = $vis_vis.$vis_mem;
	$item_id = process_db_input($_POST['ItemID']);
	$i_ed    = process_db_input($_POST['Editable']);

	$m_oncl  = $m_type == 'group' ? "display_node(\'submenu_{*}\',{URL}); return false;" : process_db_input( $_POST['Onclick'] );

	$query_tmp = "`MemberMenu` SET `Name`='$m_name',`Link`='$m_link',`Caption`='$m_capt',`MenuGroup`=$m_group,`MenuType`='$m_type',`Visible`='$visible',`Editable`='$i_ed',`Target`='$m_targ',`Onclick`='$m_oncl'";

	if($item_id == 0)
	{
		db_res("UPDATE `MemberMenu` SET `MenuOrder` = `MenuOrder` + 1 WHERE `MenuOrder` >= $m_order + 1 ");
		$query = "INSERT INTO".$query_tmp.",`MenuOrder`=$m_order+1 ";
	}
	else
	{
		$query = "UPDATE ".$query_tmp." WHERE `ID` = $item_id";
	}

	$res = db_res( $query );

	return $res;
}

//display manage form ($id - id of editing menu, $id == 0 for new item
function display_form ($id = 0)
{
	$type_event   = "onClick=\"javascript: document.getElementById('menu_link').disabled=";
	$group_en_sw  = "document.getElementById('menu_group').disabled=";
	$group_beg    = "<select name=\"MenuGroup\" id=\"menu_group\"";
	$group_end    = "</select>";

	if ($id != 0)
	{
		$res   = db_arr("SELECT * FROM `MemberMenu` WHERE `ID` = $id");
		$name  = $res['Name'];
		$capt  = $res['Caption'];
		$link  = $res['Link'];
		$group = $res['MenuGroup'];
		$vis_v = strpos($res['Visible'],"non")  !== FALSE ? "checked" : "";
		$vis_m = strpos($res['Visible'],"memb") !== FALSE ? "checked" : "";
		$type  = $res['MenuType'];
		
		$Onclick = htmlspecialchars_adv( $res['Onclick'] );

//hidden params of item's editing
		$ed    = $res['Editable'];
//disabling name and link for sript's menu items
		$set   = $ed == '0' ? "disabled" : "";

//type_group/type_link - check for enabling group/link in load process of curent item
		$type_group       = $res['MenuType'] == 'group' ? $type_group = "checked=\"checked\"" : "";
		$type_link        = $res['MenuType'] == 'link'  ? $type_link = "checked=\"checked\"" : "";

//enable/disable of group and link for script menu items
		$type_event_group = $res['Editable'] == '1' ? $type_event."true;".$group_en_sw."true;\"" : "";
		$type_event_link  = $res['Editable'] == '1' ? $type_event."false;".$group_en_sw."false;\"" : "";

//enabling of group choice
		$group_en = $res['MenuType']=='group' ? "disabled=\"disabled\">" : ">";
//group body
		$group_body = $group_beg." ".$group_en.get_menu_group($res['MenuGroup']).$group_end;
		
		$sTargetChecked = ( $res['Target'] == '_blank' ) ? ' checked="checked"' : '' ;
	}
	else
	{
		$name   = "";
		$link   = "";
		$group  = "";
		$vis_v  = "";
		$vis_m  = "";
		$set    = "";
		$ed     = 1;
		$Onclick = "";

		$type_group       = "";
		$type_link        = "";
		$type_event_group =  $type_event."true;".$group_en_sw."true;\"";
		$type_event_link  =  $type_event."false;".$group_en_sw."false;\"";
		$group_body = $group_beg." ".$group_en.get_menu_group().$group_end;
		$sTargetChecked = '';
	}

	echo "<form name=\"add_field\" action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">\n";
	echo "<table style=\"font-size: 11px\" width=\"100%\" cellspacing=\"10px\">\n";
	echo "<tr>";
	echo "<td>Name</td><td><input type=\"text\" name=\"Name\" $set value=\"$name\" />
		  	<input type=\"hidden\" name=\"NameH\" value = \"$name\" /></td></tr>
		  	<input type=\"hidden\" name=\"Onclick\" value = \"$Onclick\" /></td></tr>";
	echo "<tr><td>Type</td><td>
		  	<input type=\"radio\" name=\"MenuType\" $type_group value=\"group\" $type_event_group />Group
		  	<input type=\"radio\" name=\"MenuType\" $type_link value=\"link\" $type_event_link />Link
		  </td></tr>";
	echo "<tr><td>Link</td><td><input type=\"text\" id=\"menu_link\" name=\"Link\" $set value=\"$link\" />
		  Use http:// prefix if you want use URL out of your site
		  <input type=\"hidden\" name=\"LinkH\" value = \"$link\"/></td></tr>";
	echo "<tr><td>Caption</td><td><input type=\"text\" name=\"Caption\" value=\"$capt\" />
		  Don't forget add Language Key to your Language File ! </td></tr>";
	echo "<tr><td>Group</td>
		  <td>$group_body</td></tr>";

	if ($id == 0)
	{
		echo "<tr><td>After item</td><td>".get_menu_list()."</td></tr>";
	}
	echo "<tr><td>Visible</td><td>
			<input type=\"checkbox\" name=\"VisibleVisitor\" value=\"non\" $vis_v/>Visitors
			<input type=\"checkbox\" name=\"VisibleMember\" value=\"memb\" $vis_m/>Members
		 </td></tr>";
	echo "<tr><td width=\"70\">Open in new window</td><td style=\"padding-left:35px\">
			<input type=\"checkbox\" name=\"Target\" $sTargetChecked />
		 </td></tr>";
	echo "<input type=\"hidden\" name=\"ItemID\" value=\"{$id}\" />";
	echo "<input type=\"hidden\" name=\"Editable\" value=\"{$ed}\" />";
	echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Add\" value=\"Add\"/></td></tr>";

	echo "</form><tr><td colspan=\"2\">";
	echo "<a href=\"{$_SERVER['PHP_SELF']}\">Back</a></td></tr></table>";
}

//get group name
function get_group_name ($gr_id = 0)
{
	$res = db_arr("SELECT `Caption` FROM `MemberMenu` WHERE `ID` = $gr_id");
	$capt = _t($res['Caption']);
	return $capt;
}

//get list of existing group
function get_menu_group ($sCurrent = 0)
{
	$res = db_res( "SELECT `ID`,`Name`,`Caption`,`MenuGroup` FROM `MemberMenu` WHERE `MenuType` = 'group' ORDER BY `MenuOrder` ASC" );

	if ( mysql_num_rows($res) > 0 )
	{
		$ret  .= "<option value=\"\"></option>";
		while ($arr = mysql_fetch_array($res))
		{
			$sSel = $sCurrent == $arr['ID'] ? ' selected="selected" ' : '';
			$ret .= "<option value=\"{$arr['ID']}\" $sSel>"._t($arr['Caption'])."</option>";
		}
	}
	else
	{
		$ret .= "There is no any groups!";
	}
	return $ret;
}

//show all existing items in member menu
function get_menu_list ()
{
	$count = 0;
	$min   = 0;

	$res   = db_res( "SELECT `ID`, `Name`, `MenuOrder` FROM `MemberMenu` ORDER BY `MenuOrder` ASC" );
	$ret  .= "<select name=\"MenuOrder\">";
	while ($arr = mysql_fetch_array($res))
	{
		$ret .= "<option value=\"{$arr['MenuOrder']}\">{$arr['Name']}</option>";
		$min = ($count == 0) ? $arr['MenuOrder'] : $min;
		db_res( "UPDATE `MemberMenu` SET `MenuOrder` = $min+$count WHERE `ID` = {$arr['ID']}");
		$count++;
	}
	$ret .= "</select>";

	return $ret;
}

function reset_menu()
{
    // SQL script executing
    if ( !($f = fopen ( './memb_menu_dfl.sql', "r" )) )
    	return false;
	
	$s_sql = '';
	while ( $s = fgets ( $f, 10240) )
    {
        $s = trim ($s);
		
		//find comment
        if ( $s[0] == '#' ) continue;
        if ( $s[0] == '' ) continue;
        if ( $s[0].$s[1] == '--' ) continue;

        $s_sql .= $s;
		
        if ( $s[strlen($s)-1] != ';' )
            continue;
		
		db_res( rtrim( $s_sql, ';' ) );
		
        $s_sql = '';
    }
    fclose($f);
}

?>