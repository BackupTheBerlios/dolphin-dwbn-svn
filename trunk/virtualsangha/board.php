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

// --------------- page variables and login

$_page['name_index']	= 57;
$_page['css_name']		= 'ray_chat.css';

$_page['header'] = _t( "_Boards" );
$_page['header_text'] = _t( "_Boards" );

$logged['member'] = member_auth( 0 );

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode() {
	global $oTemplConfig;
	global $logged;

	$iId = (int)$_COOKIE['memberID'];
	if ($iId > 0) {
		$sPassword = getPassword($iId);
		$bEnableRay = (getParam( 'enable_ray' ) == 'on');
		$check_res = checkAction( $iId, ACTION_ID_USE_RAY_CHAT );

		if($bEnableRay && $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED) {
			$ret .=
				'<div class="mainWindow">' .
					getApplicationContent('board', 'user', array('id' => $iId, 'password' => $sPassword), true) .
				'</div>';
		} else {
			$ret .= MsgBox($check_res[CHECK_ACTION_MESSAGE]);
		}
		return DesignBoxContent( _t('_RAY_CHAT'), $ret, $oTemplConfig -> PageCompThird_db_num );
	} else {
		return DesignBoxContent(_t('_LOGIN_ERROR') , MsgBox(_t('_Please login before using Ray chat')), 1 );
	}
}
?>
