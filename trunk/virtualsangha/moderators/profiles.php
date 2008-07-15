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
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$logged['moderator'] = member_auth(3);
$ADMIN = $logged['moderator'];

if ( $_POST[prf_form_submit] && !$demo_mode)
{
    $sel_str = "";
    while( list( $key, $val ) = each( $_POST ) )
        if ( (int)$key && $val )
            $sel_str .= ",$key";
    $sel_str = substr( $sel_str, 1 );
    $sel_arr = explode( ",", $sel_str );

    $owner = $PARTNER ? $_COOKIE[partnerID] : 0;
    while( list( $key, $val ) = each( $sel_arr ) )
    {
        switch ( $_POST[prf_form_submit] )
        {
            case "Delete": profile_delete( $val ); break;
            case "Confirm Email": activation_mail( $val, 0 ); break;
            case "Send Message": profile_send_message( $val, $_POST[Message] ); break;
        }
    }
}

// - GET variables --------------

$page            = (int)$_GET[page];
$p_per_page = (int)$_GET[p_per_page];
$profiles   = $_GET['profiles'];
$sex            = $_GET['sex'];
$search		= $_GET['search'];
$showAffMembers = (int)$_GET[showAffMembers];

if ( !$page )
    $page = 1;

if ( !$p_per_page )
    $p_per_page = 30;


if ( $showAffMembers > 0 )
{
        $aff_part_w = " AND idAff = $showAffMembers AND idProfile = ID";
        $aff_part_f = ",aff_members ";
}

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

if (strlen($search))
{
    if ($_GET[s_mail])
	$email_part = " AND `Email` LIKE '%$search%' ";
    elseif ($_GET[s_nickname])
	$email_part = " AND `NickName` LIKE '%$search%' ";
    elseif ($_GET[s_id]) 
        $email_part = " AND `ID` = '$search' ";

}

// ------------------------------


$sql = "SELECT COUNT(*) FROM Profiles $aff_part_f WHERE 1 $email_part $aff_part_w $prof_part $sex_part";
$p_num = db_arr( $sql );
$p_num = $p_num[0];
$pages_num = ceil( $p_num / $p_per_page );

$real_first_p = (int)($page - 1) * $p_per_page;
$page_first_p = $real_first_p + 1;

$result = db_res( "
	SELECT
		`Profiles`.*,
		DATE_FORMAT(`DateLastLogin`, '$date_format' ) AS `DateLastLogin`
	FROM `Profiles`
	$aff_part_f
	WHERE
		1
		$email_part
		$aff_part_w
		$prof_part
		$sex_part
	ORDER BY `DateLastEdit` DESC
	LIMIT $real_first_p, $p_per_page
" );
$page_p_num = mysql_num_rows( $result );

$_page['header'] = "Members' Profiles";
$_page['header_text'] = "Members profiles sorted by modification date";
$_page['js'] = 1;

TopCodeAdmin();
ContentBlockHead("Profiles");
?>
<center>
<table align="center" width="90%" cellspacing=2 cellpadding=2 class=panel border=0>
<form method=get action=profiles.php>
<tr>
    <td align=center colspan="3"> <input class=text name='search' size=50> </td>
	</tr>
	<tr>
    <td align=right> <input class=text name='s_mail' type=submit value="Search by Email"> </td>
    <td align=center> <input class=text name='s_nickname' type=submit value="Search by Nickname"> </td>
    <td align=left> <input class=text name='s_id' type=submit value="Search by ID"> </td>
</tr>
</form>
</table>
</center>
<br>

<center>
<? echo ResNavigationRet( 'ProfilesUpper', 0 ); ?>
</center>
<form action="profiles.php<? $get_vars = get_vars(); echo substr($get_vars, 0, strlen($get_vars) - 1); ?>" method=post name="prf_form">
<table align="center" width=580 cellspacing=1 cellpadding=0 class=small border=0>
<?

if ( !$p_num )
    echo "<td class=panel>No profiles available</td>";
else
{
?>
<tr class=panel>
<td>&nbsp;</td>
<td align=center>&nbsp;ID&nbsp;</td>
<td align=center>&nbsp;Photo&nbsp;</td>
<td align=center>&nbsp;Last Visited&nbsp;</td>
<td align=center>&nbsp;NickName&nbsp;</td>
<td align=center>&nbsp;Sex&nbsp;</td>
<td align=center colspan=2>&nbsp;Contacts&nbsp;</td>
</tr>
<?
    while ( $p_arr = mysql_fetch_array( $result ) )
    {
        $col = "prof_stat_$p_arr[Status]";

                if ( $col == "prof_stat_Active" )
                        $col = "table";
?>
<tr class=<? echo $col; ?>>
<td align=center><input type=checkbox name="<? echo $p_arr[ID]?>"></td>
<td>&nbsp;<a href="../pedit.php?ID=<? echo $p_arr[ID]; ?>"><? echo $id_addon_arr[$p_arr[AffiliateID]] . $p_arr[ID]; ?></a>&nbsp;</td>
<td align=center><?
        if ( $p_arr[Picture] )
                        echo "Yes";
?></td>
<td align=center><? echo $p_arr[DateLastLogin]; ?></td>
<td align=center>&nbsp;<? echo $p_arr[NickName]; ?>&nbsp;</td>
<td align=center><? echo _t("_".$p_arr[Sex]); ?></td>
<td align=center><? $c = MemberContacted( $p_arr[ID] ); if ( $c ) echo $c; else echo "-"; ?></td>
<td align=center><? $wc = MemberWasContacted( $p_arr[ID] ); if ( $wc ) echo $wc; else echo "-"; ?></td>

</tr>
<?
    }
}
?>
</table>

<table class=text border=0 width=580 align=center border="1">
<tr>
    <td>&nbsp;<a href="javascript: void(0);" onclick="setCheckboxes( 'prf_form', true ); return false;">Check all</a> / <a href="javascript: void(0);" onclick="setCheckboxes( 'prf_form', false ); return false;">Uncheck all</a>&nbsp;</td>
    <td width="100">Selected profiles:</td>
    <td width="55"><input class=no type=submit name="prf_form_submit" value="Delete"></td>
    <td width="1">|</td>
    <td width="120"><input class=no type=submit name="prf_form_submit" value="Confirm Email"></td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr>
    <td colspan=7 align="center"><textarea name=Message cols=50 rows=7></textarea></td>
</tr>
<tr>
    <td colspan=7 align="center"><input class=no type=submit name="prf_form_submit" value="Send Message"></td>
</tr>
</table>
</form>

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
