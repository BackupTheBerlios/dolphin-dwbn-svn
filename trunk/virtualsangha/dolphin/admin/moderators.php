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
This file displays page for managing moderator accounts.
*/
require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// Check if admin is logged in and save this info into $logged var.
$logged['admin'] = member_auth( 1, true, true );
// Local variable for brevity only.
$admin = $logged['admin'];

// 'Delete' transaction.
if (!$demo_mode && ('Delete' == $_POST['prf_form_submit']))
{
    foreach ($_POST as $key => $value)
    {
        $err = false;
        if ((int)$key && 'on' == $value)
        {
            // Set query string.
            $q_str = "DELETE FROM `moderators` WHERE `id` = $key;";
            // Delete moderator via query.
            $res = db_res($q_str);
            if (!res)
            {
                $err = true;
                break;
            }
        }
        // Set status string.
        if (!$err)
        {
            $status_text = 'Deleted successfully';
        }
        else
        {
            $status_text = 'Failed to delete';
        }
    }
}

// 'Add' transaction.
if (!$demo_mode && $_POST['add_moderator'])
{
    // Add new moderator to database.
    $mod_name = process_db_input( $_POST['name'] );
    $mod_email = process_db_input( $_POST['email'] );
    $mod_password = md5( $_POST['password'] );
    $mod_status = process_db_input( $_POST['status'] );
    // Set query string -- get moderator prop values via $_POST variable.
    $q_str = <<<EOD
        INSERT INTO `moderators`
            (`name`, `email`, `Password`, `status`)
        VALUES
            ('$mod_name', '$mod_email', '$mod_password', '$mod_status');
EOD;
    // Add moderator via query.
    $res = db_res($q_str);
    if ($res)
    {
        $status_text = 'Added moderator successfully.';
    }
    else
    {
        $status_text = 'Failed to add moderator.';
    }
}

// 'Update' transaction.
if (!$demo_mode && (int)$_GET['editdis'])
{
    // Init moderator properties array.
    $q_str = "
    	SELECT
    			`id`,
    			`name`,
    			`email`,
    			`Password`,
    			`status`,
    			DATE_FORMAT(`reg_date`, '$date_format' ) AS reg_date
    	FROM
    			`moderators`
    	WHERE
    			`id` = '{$_GET['editdis']}';";
    $editdis_arr = db_arr($q_str);
}
if ($_POST['update_moderator'])
{
    // Update moderator.
    $mod_id = (int)$_POST['id'];
    $mod_name = process_db_input( $_POST['name'] );
    $mod_email = process_db_input( $_POST['email'] );
    $mod_password = strlen( $_POST['password'] ) ? ( "'" . md5( $_POST['password'] ) . "'" ) : '`Password`';
    $mod_status = process_db_input( $_POST['status'] );
    // Set query string -- get moderator prop values via $_POST variable.
    $q_str = <<<EOD
        UPDATE `moderators` SET
            `name` = '$mod_name',
            `email` = '$mod_email',
            `Password` = $mod_password,
            `status` = '$mod_status'
        WHERE `id` = $mod_id;
EOD;
    // Update moderator via query.
    $res = db_res($q_str);
    if ($res)
    {
        $status_text = 'Moderator was successfully updated.';
    }
    else
    {
        $status_text = 'Failed to update moderator.';
    }
}

// Init global variables from GET array.
// Init current page.
$page = (int)$_GET['page'];
if (!$page)
{
    $page = 1;
}
// Init number of records per page.
$p_per_page = (int)$_GET['p_per_page'];
if (!$p_per_page)
{
    $p_per_page = 30;
}
// Init total number of records to display.
$p_num = db_arr('SELECT COUNT(*) FROM `moderators`;');
$p_num = $p_num[0];
// Init page number.
$pages_num = ceil($p_num / $p_per_page);

$real_first_p = (int)($page - 1) * $p_per_page;
$page_first_p = $real_first_p + 1;


$sQuery = "
	SELECT
			`id`,
			`name`,
			`email`,
			`status`,
			DATE_FORMAT(`reg_date`, '$date_format' ) AS reg_date
	FROM
			`moderators`
	LIMIT $real_first_p, $p_per_page;";
$result = db_res( "$sQuery" );
$page_p_num = mysql_num_rows($result);

$_page['header'] = 'Manage Moderators'; // Set page title.

TopCodeAdmin();

ContentBlockHead('');
?>

<?
if ( strlen($status_text) )
	echo "<br><center><div class=\"err\">$status_text</div></center><br>\n";
?>
<SCRIPT language="JavaScript">
function setCheckboxess(the_form, do_check)
{
    var elts = document.forms[the_form].elements;
    var elts_cnt  = elts.length;

    for (var i = 0; i < elts_cnt; i++) {
        elts[i].checked = do_check;
    } // end for

    return true;
} // end of the 'setCheckboxes()' function
</SCRIPT>

<center>
  <!-- Page navigation controls -->
  <?php echo ResNavigationRet( 'ModeratorsUpper', 0 ); ?>
</center>

<form action="<?php echo $_SERVER[PHP_SELF]; ?>" method="post" name="moderator_frm">
<table align="center" cellspacing=2 cellpadding=0 class=small width=90%>
<?php

if (!$p_num)
{
    echo '<td class="panel">No moderators</td>';
}
else
{
?>
<tr class="panel">
<td align="center">&nbsp;ID&nbsp;</td>
<td align="center">&nbsp;Name&nbsp;</td>
<td align="center">&nbsp;E-Mail&nbsp;</td>
<td align="center">&nbsp;Status&nbsp;</td>
<td align="center">&nbsp;&nbsp;</td>
</tr>
<?php
    // Iterate thru moderators in the result recordset.
    while ($p_arr = mysql_fetch_array($result))
    {
?>
<tr class="table">

<td align="center">
  <a href="moderators.php?editdis=<?=$p_arr[id];?>"><?=$p_arr['id'];?>
</td>
<td align="center"><?=process_line_output($p_arr['name'])?></td>
<td align="center"><?=process_line_output($p_arr['email'])?></td>
<td align="center"><?=process_line_output($p_arr['status'])?></td>
<td align="center"><input type="checkbox" name="<?=$p_arr[id];?>"></td>
</tr>
<?php
    }
}
?>
</table>

<!-- Check all/Uncheck all controls -->
<center>
<table class=text>
<tr>
  <td>&nbsp;<a href="javascript: void(0);"
    onclick="setCheckboxess('moderator_frm', true ); return false;">Check all</a> /
    <a href="javascript: void(0);"
    onclick="setCheckboxess( 'moderator_frm', false ); return false;">Uncheck all</a>&nbsp;</td>
  <td>Selected partners:</td>
  <td><input class="no" type="submit" name="prf_form_submit" value="Delete"></td>
</tr>
</table>
</form>
</center>

<center>
<?php echo ResNavigationRet( 'ModeratorsLower', 0 ); ?>
</center>

<?php
// Check if user selected to update some moderator properties.
if ((int)$_GET['editdis'])
{
?>
<form method="post" action="<?=$_SERVER[PHP_SELF]?>">
<table align="center" cellspacing="1" cellpadding="2" class="small" width="90%">
<tr class="panel">
<td align="center" width="10%">&nbsp;ID&nbsp;</td>
<td align="center" width="30%">&nbsp;Name&nbsp;</td>
<td align="center" width="30%">&nbsp;E-Mail&nbsp;</td>
<td align="center" width="10%">&nbsp;Password&nbsp;</td>
<td align="center" width="10%">&nbsp;Status&nbsp;</td>
</tr>
<tr class="panel">
<input type="hidden" name="id" value="<?=$editdis_arr['id']?>">
<td align="center" width="10%"><?=$editdis_arr['id']?></td>
<td align="center" width="30%"><input class="no" size="10" name="name" value="<?=htmlspecialchars($editdis_arr['name'])?>"></td>
<td align="center" width="30%"><input class="no" size="20" name="email" value="<?=htmlspecialchars($editdis_arr['email'])?>"></td>
<td align="center" width="10%"><input class="no" size="8" name="password" value=""></td>
<td align="center" width="10%">

<select class="no" name="status">
    <option value="approval"  <?=$editdis_arr['status'] == 'approval'  ? 'selected' : '' ?>>approval</option>
    <option value="active"    <?=$editdis_arr['status'] == 'active'    ? 'selected' : ''?>>active</option>
    <option value="suspended" <?=$editdis_arr['status'] == 'suspended' ? 'selected' : ''?>>suspended</option>
</select>

</td>
</tr>
</table>
<br>
<center><input class="no" type="submit" name="update_moderator" value="Update"></center>
</form>
<?php
}
else
{
?>
<!-- New moderator form -->
<form method="post" action="<?php echo $_SERVER[PHP_SELF]; ?>">
<table align="center" cellspacing=1 cellpadding=2 class=small width=90%>
<tr class="panel">
<td align="center" width="30%">&nbsp;Name&nbsp;</td>
<td align="center" width="30%">&nbsp;E-Mail&nbsp;</td>
<td align="center" width="10%">&nbsp;Password&nbsp;</td>
<td align="center" width="10%">&nbsp;Status&nbsp;</td>

</tr>
<tr class="panel">
<td align="center" width="30%"><input class="no" size="10" name="name"></td>
<td align="center" width="30%"><input class="no" size="20" name="email"></td>
<td align="center" width="10%"><input class="no" size="8" name="password"></td>
<td align="center" width="10%">

<select class="no" name="status">
        <option value="approval">approval</option>
        <option value="active">active</option>
        <option value="suspended">suspended</option>
</select>

</td>
</tr>
</table>
<br>
<center><input class="no" type="submit" name="add_moderator" value="Add"></center>
</form>

<?php
}
ContentBlockFoot();
?>

</table>
</form>
<?php
BottomCode();
?>