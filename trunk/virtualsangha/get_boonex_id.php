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
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$sUrl = 'http://www.boonex.com/unity/id/export';

$sID       = process_pass_data( $_GET['ID'] );
$sPassword = process_pass_data( $_GET['Password'] );

$sRequest  = $sUrl . '?username=' . urlencode( $sID ) . '&password=' . md5( $sPassword ) . '&r=' . rand();

header ('Content-Type: application/xml');
echo file_get_contents( $sRequest );

?>