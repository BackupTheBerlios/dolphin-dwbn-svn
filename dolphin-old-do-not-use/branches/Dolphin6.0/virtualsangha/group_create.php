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

$_page['name_index']	= 72;
$_page['css_name']		= 'groups.css';


$logged['member'] = member_auth( 0, true );
$memberID = (int)$_COOKIE['memberID'];

$_page['header'] = _t( "_Create Group" );
$_page['header_text'] = _t( "_Create Group" );
$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorCompactJS;

// --------------- page components
$_ni = $_page['name_index'];

$arrMember = getProfileInfo( $memberID ); //db_arr( "SELECT `Status` FROM `Profiles` WHERE `ID`=$memberID" );

if( $arrMember['Status'] == 'Active' )
	$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();
else
	$_page_cont[$_ni]['page_main_code'] = _t( '_You must be active member to create groups' );

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode()
{
	global $memberID;
	global $site;
	
	$arrNewGroup = getDefaultGroupEditArr();
	$arrErr = array();
	
	if( isset( $_POST['do_submit'] ) )
	{
		fillGroupArrByPostValues( $arrNewGroup );
		$arrErr = checkGroupErrors( $arrNewGroup );
		
		if( md5( $_POST['simg'] ) != $_COOKIE['strSec'] )
			$arrErr['simg'] = 'SIMG_ERR';
		unset( $_COOKIE['strSec'] );
		
		if( empty( $arrErr ) )
		{
			$arrNewGroup['creatorID'] = array(
				'Name' => 'creatorID',
				'Type' => 'text',
				'Value' => $memberID
				);
			
			$newGroupID = saveGroup( $arrNewGroup );
			if( $newGroupID )
			{
				addMember2Group( $memberID, $newGroupID, 'Active' );
				
				$groupHomeLink = "{$site['url']}group.php?ID=$newGroupID";
				$res = _t( '_Group creation successful', $groupHomeLink );
				$res .= "<br />";
				$res .= _t('_Gallery upload_desc');
				$res .= genUploadForm( $newGroupID, true, true );
			}
			else
				$res = _t('_Group creation unknown error');
			
			return $res;
		}
	}
	
	$res = genGroupEditForm( $arrNewGroup, $arrErr, true );
	
	return $res;
}

?>
