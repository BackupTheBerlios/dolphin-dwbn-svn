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

$_page['name_index'] = 82;
$_page['css_name'] = 'browse.css';


if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );

if ( isset($_GET['iUser']) )
{
	$iID = (int)$_GET['iUser'];
	$_page['header'] = getNickName( $iID)."'s "._t("_Friends");
	$_page['header_text'] = getNickName( $iID)."'s "._t("_Friends");
}

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

PageCode();

function PageCompPageMainCode()
{
	global $iID;
	
	$sFriendList = getFriendList( $iID );
	
	return $sFriendList;
}

function getFriendList( $id )
{
	global $site;
	global $max_thumb_width;
	global $max_thumb_height;

	$id = (int)$id;
	$friend_list_query = "SELECT `Profiles`.* FROM `FriendList`
								 LEFT JOIN `Profiles` ON (`Profiles`.`ID` = `FriendList`.`Profile` AND `FriendList`.`ID` = '$id' OR `Profiles`.`ID` = `FriendList`.`ID` AND `FriendList`.`Profile` = '$id')
								 WHERE (`FriendList`.`Profile` = '$id' OR `FriendList`.`ID` = '$id') AND `FriendList`.`Check` = '1' ORDER BY `Profiles`.`Picture` DESC";

	$friend_list_res = db_res("$friend_list_query");
	
	$iCounter = 0;
	while ( $friend_list_arr = mysql_fetch_assoc( $friend_list_res ) )
	{
		$iCounter ++;
		$sKey = '1';
		if( $iCounter == 3 )
		{
			$sKey = '1';
		}

		$ret .= '<div class="friends_thumb_'.$sKey.'">' . get_member_thumbnail($friend_list_arr['ID'], 'none') . '<div class="browse_nick"><a href="' . getProfileLink($friend_list_arr['ID']) . '">' . $friend_list_arr['NickName'] . '</a></div><div class="clear_both"></div></div>';
		
		if( $iCounter == 3)
			$iCounter = 0;
	}

	return $ret;
}