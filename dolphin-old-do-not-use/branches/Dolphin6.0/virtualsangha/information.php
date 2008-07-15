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

$_page['name_index']	= 11;
$_page['css_name']		= 'about_us.css';


if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );

$_page['header'] = _t( "Information" );
$_page['header_text'] = _t( "Information", $site['title'] );

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode()
{
	global $oTemplConfig;

	$ret = "We are sorry, but this application is not available to you, because you have the Ray Lite version integrated into Dolphin. To make the desktop IM function available we recommend that you purchase the <a href=\"http://www.expertzzz.com/WDownloads/home/40/\">Ray Pro</a>. <br /><br /> Ray Pro - #1 Community Widget Suite is an advanced, well-designed, fully controlled and multifunctional web communication package; including Flash chat withA/V conferencing, A/V recorder, Instant Messenger, MP3 player, online Presence and Desktop Presence (the ability to communicate without browsing to your site). Ray comes with it's own free multimedia server that allows hosting our product any where you want. We also recommend \"hostforweb\" for hosting services. For more information about Ray Pro follow this <a href=\"http://www.boonex.com/products/ray/ \">link</a>.";

	return DesignBoxContent( "Information", $ret, $oTemplConfig -> PageCompThird_db_num );
}
?>