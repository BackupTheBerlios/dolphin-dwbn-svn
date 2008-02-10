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
require_once( BX_DIRECTORY_PATH_INC . 'groups.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// --------------- page variables and login


$_page['name_index']	= 73;
$_page['css_name']		= 'groups.css';


$logged['member'] = member_auth( 0, true );
$memberID = (int)$_COOKIE['memberID'];



$groupID = (int)$_REQUEST['ID'];

if ( !$groupID )
{
	Header( "Location: {$site['url']}groups_home.php" );
	exit;
}

$_page['header'] = _t( "_Edit Group" );
$_page['header_text'] = _t( "_Edit Group" );
$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorCompactJS;

$_ni = $_page['name_index'];

if ( $arrGroup = getGroupInfo( $groupID ) )
{
	if ( $arrGroup['creatorID'] == $memberID ) //only creator can edit group
		$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();
	else
		$_page_cont[$_ni]['page_main_code'] = _t( "_You're not creator" );
}
else
	$_page_cont[$_ni]['page_main_code'] = _t( "_Group not found_desc" );


// --------------- page components

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode()
{
	global $memberID;
	global $groupID;
	global $arrGroup;
	global $site;
	
	$arrGroupFields = getDefaultGroupEditArr();
	fillGroupArrByDBValues( $arrGroupFields, $arrGroup );
	$arrErr = array();
	
	if( isset( $_POST['do_submit'] ) )
	{
		$arrOldGroupFields = $arrGroupFields;
		fillGroupArrByPostValues( $arrGroupFields );
		$arrUpdGroupFields = compareUpdatedGroupFields( $arrOldGroupFields, $arrGroupFields );
		
		if( !empty( $arrUpdGroupFields ) )
		{
			$arrErr = checkGroupErrors( $arrUpdGroupFields );
			
			if( empty( $arrErr ) )
			{
				saveGroup( $arrUpdGroupFields, $groupID );
				Header( "Location: {$site['url']}group.php?ID=$groupID" );
				exit;
			}
		}
		else
		{
			Header( "Location: {$site['url']}group.php?ID=$groupID" );
			exit;
		}
	}
	
	$res = genGroupEditForm( $arrGroupFields, $arrErr, false, $groupID );
	
	return $res;
}

?>