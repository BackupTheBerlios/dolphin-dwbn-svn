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

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

// --------------- page variables and login

$_page['name_index'] 	= 50;
$_page['css_name']		= 'getmem.css';


if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );


if ( !$logged['member'] )
{
	$_page['header'] = _t("_GETMEM_H");
	$_page['header_text'] = _t("_GETMEM_H1");
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = _t( '_LOGIN_REQUIRED_AE1' );
	PageCode();
	exit();
}

$member['ID'] = (int)$_COOKIE['memberID'];

$_page['header'] = _t("_GETMEM_H");
$_page['header_text'] = _t("_GETMEM_H1");

$affnum_arr = db_arr ( "SELECT `aff_num` FROM `Profiles` WHERE `ID` = '{$member['ID']}' LIMIT 1" );
$levels_res = db_res ( "SELECT * FROM `members_as_aff` WHERE `num_of_mem` <= '{$affnum_arr['aff_num']}'" );

// --------------- GET/POST actions

if ( $_POST['upgrade'] == 'YES' )
{
	$memtype_arr = split('-', $_POST['MemType']);
	$membershipID = (int)$memtype_arr[0];
	$membershipNumOfDays = (int)$memtype_arr[1];
	$arr = db_arr( "SELECT * FROM `members_as_aff` WHERE `MID` = '{$membershipID}' AND `num_of_days` = '{$membershipNumOfDays}' AND `num_of_mem` <= '{$affnum_arr['aff_num']}' LIMIT 1" );

	if ( $arr )
	{
		setMembership($member['ID'], $arr['MID'], $arr['num_of_days']);
		$membership_info = getMembershipInfo($arr['MID']);
		
		db_res( "UPDATE `Profiles` SET `aff_num` = `aff_num` - {$arr['num_of_mem']} WHERE `ID` = '{$member['ID']}'" );
		createUserDataFile( $member['ID'] );

		$upgrade_out = "
			<table align=center width=\"400\" class=\"text\">
				<tr>
					<td>
						". _t ( "_Got_new_membership_part_1" ). $membership_info['Name']. _t ( "_Got_new_membership_part_2" ). $arr['num_of_days']. _t ( "_Got_new_membership_part_3" ) ."
					</td>
				</tr>
			</table>";
	}
	else
	{
		$upgrade_out = "";
	}
}

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = ($_POST['upgrade'] == 'YES' && strlen($upgrade_out)) ? $upgrade_out : PageCompPageMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

function PageCompPageMainCode()
{
	global $affnum_arr;
	global $levels_res;

	ob_start();

?>

<form action=<?= $_SERVER['PHP_SELF'] ?> method="post">
	<input type=hidden name=upgrade value=YES>
		<table class="text" width="400" align=center border="0" cellpadding="2" cellspacing="0">
<?
	if ( mysql_num_rows( $levels_res ) > 0 )
	{
		echo "
			<tr>
				<td align=center colspan=2>
				". _t ( "_Congratulation" ). "<br />" ._t ( "_Got_members_part_1" ). $affnum_arr[0] . _t ( "_Got_members_part_2" ) . _t ( "_Choose_membership" ) ."<br /><br />
				</td>
			</tr>";
		$i = 0;
		while ( $levels_arr = mysql_fetch_array( $levels_res ) )
		{
			$membership_info = getMembershipInfo($levels_arr['MID']);
?>
			<tr>
				<td align="right"><input type=radio name=MemType id="MemType<?= $levels_arr['MID'].'-'.$levels_arr['num_of_days'] ?>" value="<?= $levels_arr['MID'].'-'.$levels_arr['num_of_days'] ?>"></td>
				<td align="left" nowrap>
					<label for="MemType<?= $levels_arr['MID'].'-'.$levels_arr['num_of_days'] ?>">
						<font color=red><?= $membership_info['Name'] ?></font> ( <?= $levels_arr['num_of_days'] .' '. _t('_days') ?>, <?= _t('_requires_N_members', $levels_arr['num_of_mem']) ?> )
					</label>
					<br />
				</td>
			</tr>
<?
			$i++;
		}
?>
			<tr>
				<td align=center colspan=2>
					<br />
					<input type="submit" name="submit" class="no" value=Apply>
				</td>
			</tr>
<?
	}
?>
		</table>
</form>
<?
	$ret = ob_get_contents();
	ob_end_clean();

	return $ret;
}

?>