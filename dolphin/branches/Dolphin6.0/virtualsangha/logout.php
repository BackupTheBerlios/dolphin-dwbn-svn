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

$action = $_GET['action'];

if ( strcmp( $action, 'member_logout' ) == 0
    && isset( $_COOKIE['memberID'] )
    && isset( $_COOKIE['memberPassword'] ) )
{
    setcookie( 'memberID', $_COOKIE['memberID'], time() - 48 * 3600, '/' );
    setcookie( 'memberPassword', $_COOKIE['memberPassword'], time() - 48 * 3600, '/' );
}

if ( strcmp( $action, 'admin_logout' ) == 0
    && isset( $_COOKIE['adminID'] )
    && isset( $_COOKIE['adminPassword'] ) )
{
    setcookie( 'adminID', $_COOKIE['adminID'], time() - 48 * 3600, '/' );
    setcookie( 'adminPassword', $_COOKIE['adminPassword'], time() - 48 * 3600, '/' );
    setcookie( 'new_version', $_COOKIE['new_version'], time() - 48 * 3600, '/' );
}

if ( strcmp( $action, 'aff_logout' ) == 0
    && isset( $_COOKIE['affID'] )
    && isset( $_COOKIE['affPassword'] ) )
{
    setcookie( 'affID', $_COOKIE['affID'], time() - 48 * 3600, '/' );
    setcookie( 'affPassword', $_COOKIE['affPassword'], time() - 48 * 3600, '/' );
}

if ( strcmp( $action, 'moderator_logout' ) == 0
    && isset( $_COOKIE['moderatorID'] )
    && isset( $_COOKIE['moderatorPassword'] ) )
{
    setcookie( 'moderatorID', $_COOKIE['moderatorID'], time() - 48 * 3600, '/' );
    setcookie( 'moderatorPassword', $_COOKIE['moderatorPassword'], time() - 48 * 3600, '/' );
}


$_page['name_index'] = 150;
$_page['css_name'] = '';

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = MsgBox( 'Please Wait' );
$_page_cont[$_ni]['url_relocate'] = $site['url'];

send_headers_page_changed();
PageCode();
?>