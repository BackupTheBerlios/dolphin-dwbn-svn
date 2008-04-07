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
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );

if ($_POST['ID'])
{
    // Set query string.
    $mod_name = process_db_input( $_POST['ID'] );
    $mod_password = md5( $_POST['Password'] );
    $q_str = <<<EOD
        SELECT `id`, `name`, `email`, `Password`, `status`, DATE_FORMAT(`reg_date`, '$date_format' ) AS reg_date FROM `moderators`
        WHERE `name` = '$mod_name' AND
            `Password` = '$mod_password'
EOD;


    $result = db_res($q_str);
    if (mysql_num_rows($result) != 1)
    {
        login_form('Login failed. Please, try again', 3);
    }
    else
    {
        setcookie('moderatorID', $_POST[ID], 0, "/");
        setcookie('moderatorPassword', md5($_POST['Password']), 0, "/");
?>
Welcome back, <b><? echo $_POST['ID']; ?></b>. Logging you in...
<script language="Javascript">location.href='<? echo $_SERVER[PHP_SELF]; ?>';</script>
<?
        exit;
    }
}

if ( !$_COOKIE['moderatorID'] || !$_COOKIE['moderatorPassword'] )
{
	send_headers_page_changed();
	// Display log in form if user is not logged in.
	login_form('', 3);
}

$logged['moderator'] = member_auth(3);

$_page['header'] = 'Moderator Panel';

TopCodeAdmin();

// Get number of total registered members.
$total_members = db_arr('SELECT COUNT(*) FROM `Profiles`;');
$total_members = $total_members[0];
//

$status_arr[0] = "Unconfirmed";
$status_arr[1] = "Approval";
$status_arr[2] = "Active";
$status_arr[3] = "Rejected";
$status_arr[4] = "Suspended";

ContentBlockHead("Total registered members");

?>
								<center>
								<table cellspacing=1 cellpadding=3 border=0 width="70%" align="center" bgcolor="#cccccc" >
									<tr>
										<td bgcolor="E5E5E5" class="text" align="left"><a href="<?php echo $site['url']; ?>moderators/profiles.php">Total registered members:</a></td>
										<td bgcolor="E5E5E5" width=50 class="text" align="right"><b><?php echo $total_members; ?></b></td>
									</tr>
<?php
$i = 0;
while( list( $key, $val ) = each( $status_arr ) )
{
    $n_arr = db_arr( "SELECT COUNT(*) FROM `Profiles` WHERE Status = '$val'" );
    if ( $n_arr[0] )
    {
		?>
									<tr class="text">
										<td class="text"  bgcolor="#ffffff" align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?= $site['url_admin'] ?>images/arrow.gif">
											<a href="profiles.php?profiles=<? echo $val; ?>"><? echo $val; ?></a>
										</td>
										<td class="prof_stat_<? echo $val; ?>" width="50" align="right"><? echo $n_arr[0]; ?></td>
									</tr>
		<?
    }
}
?>
								</table>
								</center>
<?
ContentBlockFoot();

BottomCode();
?>