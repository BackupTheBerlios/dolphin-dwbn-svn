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
require_once( BX_DIRECTORY_PATH_INC . 'profile_disp.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

if (getParam("enable_aff") != 'on')
{
	$sCode = MsgBox( _t( '_affiliate_system_was_disabled' ) );
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = $sCode;
	PageCode();
	exit();
}

$logged['aff'] = member_auth( 2 );
$AFF = (int)$_COOKIE['affID'];

// - GET variables --------------

$page	    = (int)$_GET['page'];
$p_per_page = (int)$_GET['p_per_page'];
$profiles   = $_GET['profiles'];
$sex	    = $_GET['sex'];

if ( !$page )
    $page = 1;

if ( !$p_per_page )
    $p_per_page = 30;

switch( $profiles )
{
    case 'Unconfirmed':
    case 'Approval':
    case 'Active':
    case 'Rejected':
    case 'Suspended':
        $prof_part = "AND Status = '$profiles'";
        break;
    default:
        $prof_part = '';
}

if ( strlen($sex) )
	$sex_part = "AND Sex = '" . process_db_input($sex, 1) . "'";
else
	$sex_part = '';

// ------------------------------

$p_num = db_arr( "SELECT COUNT(*) FROM `Profiles` INNER JOIN `aff_members` ON (`idProfile` = `ID`) WHERE `idAff` = $AFF $prof_part $sex_part" );
$p_num = $p_num[0];
$pages_num = ceil( $p_num / $p_per_page );

$real_first_p = (int)($page - 1) * $p_per_page;
$page_first_p = $real_first_p + 1;

$result = db_res( "SELECT `Profiles`.* FROM `Profiles` INNER JOIN `aff_members` ON (`idProfile` = `ID`) WHERE `idAff` = $AFF $prof_part $sex_part ORDER BY `LastModified` DESC LIMIT $real_first_p, $p_per_page;" );
$page_p_num = mysql_num_rows( $result );

$_page['header'] = "Members' Profiles";
$_page['header_text'] = "Members profiles sorted by modification date";
$_page['js'] = 1;

TopCodeAdmin();
ContentBlockHead("Profiles");
?>
<center>
<? echo ResNavigationRet( 'ProfilesUpper', 0 ); ?>
</center>
<form action="profiles.php<? $get_vars = get_vars(); echo substr($get_vars, 0, strlen($get_vars) - 1); ?>" method=post name="prf_form">
<table align="center" width=580 cellspacing=1 cellpadding=0 class=small>
<?

if ( !$p_num )
    echo "<td class=panel>No profiles available</td>";
else
{
?>
<tr class=panel>
<td align=center>&nbsp;ID&nbsp;</td>
<td align=center>&nbsp;Photo&nbsp;</td>
<td align=center>&nbsp;Last Visited&nbsp;</td>
<td align=center>&nbsp;NickName&nbsp;</td>
<td align=center>&nbsp;Sex&nbsp;</td>
<td align=center colspan=2>&nbsp;Contacts&nbsp</td>
</tr>
<?
    while ( $p_arr = mysql_fetch_array( $result ) )
    {
        $col = "prof_stat_$p_arr[Status]";

		if ( $col == "prof_stat_Active" )
			$col = "table";
?>
<tr class=<? echo $col; ?>>
<td height="20">&nbsp;<a href="../profile.php?ID=<? echo $p_arr[ID]; ?>"><? echo $id_addon_arr[$p_arr[AffiliateID]] . $p_arr[ID]; ?></a>&nbsp;</td>
<td height="20" align=center><?
        if ( $p_arr[Picture] )
			echo "Yes";
?></td>
<td height="20" align=center><? echo $p_arr[LastLoggedIn]; ?></td>
<td height="20">&nbsp;<? echo $p_arr[NickName]; ?>&nbsp;</td>
<td height="20" align=center><? echo _t("_a_".$p_arr[Sex]); ?></td>
<td height="20" align=center><? $c = MemberContacted( $p_arr[ID] ); if ( $c ) echo $c; else echo "-"; ?></td>
<td height="20" align=center><? $wc = MemberWasContacted( $p_arr[ID] ); if ( $wc ) echo $wc; else echo "-"; ?></td>
</tr>
<?
    }
}
?>
</table>

<center>
<? echo ResNavigationRet( 'ProfilesLower', 0 ); ?>
</center>
<?
ContentBlockFoot();
ContentBlockHead("Legend");
?>
	<center>
			<table cellpadding="1" cellspacing="2" border="0" width="40%" align="center" class="brd">
			  <tr><td colspan="2" class="panel" align="right">Status of profile</td></tr>
			  <tr>
				<td class="prof_stat_Unconfirmed" width="30" style="border: 1px solid silver;">&nbsp;</td>
				<td class="brd" align="right">Unconfirmed</td>
              </tr>
			  <tr>
				<td class="prof_stat_Approval" width="30" style="border: 1px solid silver;">&nbsp;</td>
				<td class="brd" align="right">Approval</td>
              </tr>
			  <tr>
				<td bgcolor="#ffffff" width="30" style="border: 1px solid silver;">&nbsp;</td>
				<td class="brd" align="right">Active</td>
              </tr>
			  <tr>
				<td class="prof_stat_Rejected" width="30" style="border: 1px solid silver;">&nbsp;</td>
				<td class="brd" align="right">Rejected</td>
              </tr>
			  <tr>
				<td class="prof_stat_Suspended" width="30" style="border: 1px solid silver;">&nbsp;</td>
				<td class="brd" align="right">Suspended</td>
              </tr>

			</table>
	</center>
<?
ContentBlockFoot();
BottomCode();
?>
