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
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'checkout.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

if (getParam("enable_aff") != 'on')
{
	$sCode = MsgBox( _t( '_affiliate_system_was_disabled' ) );
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = $sCode;
	PageCode();
	exit();
}

if ( $_POST['ID'] )
{
	$aff_id = process_db_input($_POST['ID']);
	$aff_password = md5( $_POST['Password'] );
	
	if ( !strstr( $_POST['ID'], "@") )
	{
		$result = db_res( "SELECT `ID`, `Name`, `email`, `Password`, `Percent`, `seed`, DATE_FORMAT(`RegDate`, '$date_format' ) AS RegDate, `Status`, `www1`, `www2` FROM `aff` WHERE `ID` = '$aff_id' AND `Password` = '$aff_password'" );
		$aff_arr = mysql_fetch_array( $result );
	}
	else
	{
		$result = db_res( "SELECT `ID`, `Name`, `email`, `Password`, `Percent`, `seed`, DATE_FORMAT(`RegDate`, '$date_format' ) AS RegDate, `Status`, `www1`, `www2` FROM `aff` WHERE `email` = '$aff_id' AND `Password` = '$aff_password'" );
        $aff_arr = mysql_fetch_array( $result );
        $_POST['ID'] = $aff_arr['ID'];
	}

	if ( mysql_num_rows( $result ) != 1 )
		login_form( 'Login failed. Please, try again.', 2 );
	else
	{
		setcookie( "affID", $_POST['ID'], 0, "/" );
		setcookie( "affPassword", md5( $_POST['Password'] ), 0, "/" );
		
		?>
		Welcome back, <b><?= $aff_arr['Name'] ?></b>. Logging you in...
		<script language="Javascript">location.href='<? echo $_SERVER['PHP_SELF']; ?>';</script>
		<?
		
    	exit();
    }
}

if ( !$_COOKIE['affID'] || !$_COOKIE['affPassword'] )
{
	send_headers_page_changed();
	login_form( '', 2 );
}

$logged['aff'] = member_auth( 2 );

if( (int)$_GET['admin_categ'] )
{
	TopCodeAdmin();
		getAdminCategIndex();
	BottomCode();
}


$status_arr[0] = "Unconfirmed";
$status_arr[1] = "Approval";
$status_arr[2] = "Active";
$status_arr[3] = "Rejected";
$status_arr[4] = "Suspended";

$AFF = (int)$_COOKIE['affID'];
$n_arr = db_arr( "SELECT COUNT(*) FROM `Profiles` INNER JOIN `aff_members` ON (`idProfile` = `ID`) WHERE `idAff` = $AFF" );

// Finance
$tr_array = array();
$fin = getFinanceAffStat( $AFF, $tr_array );
$full_amount = $fin['total'];

$_page['header'] = "Affiliate Panel";
//$_page['header_text'] = "Control Panel</b> (Server time: " . date( "H:i, d-M-Y" ) . ")";

TopCodeAdmin();

ContentBlockHead("Profiles");
?>
								<center>
								<table cellspacing=1 cellpadding=3 border=0 width="70%" align="center" bgcolor="#cccccc" >
									<tr>
										<td bgcolor=E5E5E5 class="text" align="left"><a href="<?php echo $site['url_aff']; ?>profiles.php">Profiles</a></td>
										<td bgcolor=E5E5E5 width=50 class="text" align="right"><b><?php echo $n_arr[0]; ?></b></td>
									</tr>
<?php
$i = 0;
while( list( $key, $val ) = each( $status_arr ) )
{
    $n_arr = db_arr( "SELECT COUNT(*) FROM `Profiles` INNER JOIN `aff_members` ON (`idProfile` = `ID`) WHERE `Status` = '$val' AND `idAff` = $AFF" );
    if ( $n_arr[0] )
    {
?>
									<tr class="text">
										<td class="text"  bgcolor="#ffffff" align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;<img src=<?= $site['url_admin'] ?>images/arrow.gif>
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

ContentBlockHead("Finance");
?>
			<!--  Total registered members: Section End -->
					<center>
					<table bgcolor=CCCCCC width=70% cellspacing=0 cellpadding=1 align="center" width="100%">
						<tr>
							<td>
								<table cellspacing=1 cellpadding=3 width=100% bgcolor="#cccccc">
									<tr>
										<td colspan=2 bgcolor=E5E5E5 class="text" align="left"><a href="<?php echo $site['url_aff']; ?>finance.php">Finance</a></td>
									</tr>
									<tr class=text>
										<td bgcolor="#ffffff" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?= $site['url_admin'] ?>images/arrow.gif"><font color=0000FF>Total amount</font></td>
										<td bgcolor="#ffffff" align="right" width="50"><?= $doll ?><b><? echo $full_amount; ?></b></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					</center>
<?
ContentBlockFoot();

BottomCode();

?>
