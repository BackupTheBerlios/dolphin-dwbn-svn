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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'languages.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'menu.inc.php' );

header('Content-Type: application/xml');
$result = '<?xml version="1.0" encoding="'.$langHTMLCharset.'"?>';

$result .= '<root>';

$iId = (int)$_COOKIE['memberID'];
    
if ( 'menu' == $_REQUEST['action'] )
{

    if ( $iName = $_REQUEST['ID'] )
    {
	$result .= '<menu>';
	$result .= showMenuGroup( $iName, $iId );
	$result .= '</menu>';	
    }
    
}


    $result .= '</root>';

    echo $result;

// ===================================================================================
// ================================= FUNCTIONS : =====================================
// ===================================================================================

function showMenuGroup( $iName, $iId = 0 )
{
	global $aMenu;
	foreach ($aMenu as $iVal => $aValue)
	{
		if ($aValue['MenuGroup'] != $iName) continue;
		if (check_condition($aValue['Check']) != TRUE) continue;
		$sMenuLink = $iId <> 0 ? add_id($aValue['Link'], $iId) : $aValue['Link'];
		$sCaption  = _t($aValue['Caption']);
		$ret  .= '<node><name>'.$sCaption. '</name>';
		$ret  .= '<link>'.$sMenuLink.'</link></node>';
	}
	return $ret;
}