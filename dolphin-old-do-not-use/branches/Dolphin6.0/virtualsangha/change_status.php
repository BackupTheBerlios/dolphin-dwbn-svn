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
require_once( BX_DIRECTORY_PATH_INC . 'modules.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'tags.inc.php' );

// --------------- page variables and login

$_page['name_index']	= 36;
$_page['css_name']		= 'change_status.css';

$logged['member'] = member_auth(0);

$_page['header'] = _t( "_CHANGE_STATUS_H" );
$_page['header_text'] = _t( "_CHANGE_STATUS_H1", $site['title'] );

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
	global $dir;

	$member['ID'] = (int)$_COOKIE['memberID'];

	ob_start();

	$p_arr = getProfileInfo( $member['ID'] );
	
	if ( $_POST['CHANGE_STATUS'] )
	{
		switch( $_POST['CHANGE_STATUS'] )
		{
			case 'SUSPEND':
				if ( $p_arr['Status'] == 'Active' )
				{
					db_res( "UPDATE `Profiles` SET `Status` = 'Suspended' WHERE `ID` = '{$member['ID']}';" );
					
					modules_block($p_arr['ID']);
				}
			break;

			case 'ACTIVATE':
				if ( $p_arr['Status'] == 'Suspended' )
				{
					db_res( "UPDATE `Profiles` SET `Status` = 'Active' WHERE `ID` = {$member['ID']}" );
					
					// call modules to add user to modules
					$check_res = checkAction($member['ID'], ACTION_ID_USE_CHAT);
					if ( $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED )
						modules_unblock($p_arr['ID'], 'chat');
					
					$check_res = checkAction($member['ID'], ACTION_ID_USE_FORUM);
					if ( $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED )
						modules_unblock($p_arr['ID'], 'forum');
				}
			break;
		}

		createUserDataFile( $p_arr['ID'] );
		reparseObjTags( 'profile', $member['ID'] );
		
		$p_arr = getProfileInfo( $member['ID'] );
	}

	echo "<table width=\"100%\" cellpadding=4 cellspacing=4><td align=center class=text2>";

	echo _t( "_Profile status" );

?>: <b><font class=prof_stat_<? echo $p_arr['Status']; ?>>&nbsp;<? echo _t( "__$p_arr[Status]" ); ?>&nbsp;</font></b><br />
<?
    switch ( $p_arr['Status'] )
    {
	case 'Active':
		echo _t( "_PROFILE_CAN_SUSPEND" );
?>
<br /><br /><form action="<? echo $_SERVER['PHP_SELF']; ?>" method=post>
<input type=hidden name=CHANGE_STATUS value=SUSPEND>
<center><input class=no type=submit value="<? echo _t( "_Suspend account" ); ?>"></center>
</form>
<?
		break;

	case 'Suspended':
		echo _t( "_PROFILE_CAN_ACTIVATE" );
?>
<br /><br /><form action="<? echo $_SERVER['PHP_SELF']; ?>" method=post>
<input type=hidden name=CHANGE_STATUS value=ACTIVATE>
<center><input class=no type=submit value="<? echo _t( "_Activate account" ); ?>"></center>
</form>
<?
		break;
	default:
		echo _t( "_PROFILE_CANT_ACTIVATE/SUSPEND" );
		break;
    }

	echo "</td></table>";

	$ret = ob_get_contents();
    ob_end_clean();

	return $ret;
}

?>
