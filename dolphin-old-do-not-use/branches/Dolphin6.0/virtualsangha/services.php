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

$_page['name_index'] 	= 12;
$_page['css_name']		= 'services.css';

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );

$_page['header'] = _t( "_OUR_SERV" );
$_page['header_text'] = _t("_services");

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
	global $oTemplConfig;


    $ret = _t( "_SERV_DESC" );


    return DesignBoxContent( _t("_services"),$ret, $oTemplConfig -> PageCompThird_db_num );
}
?>
