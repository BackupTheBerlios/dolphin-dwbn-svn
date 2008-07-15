<?php
/**
*                            Orca Interactive Forum Script
*                              ---------------
*     Started             : Mon Mar 23 2006
*     Copyright           : (C) 2007 BoonEx Group
*     Website             : http://www.boonex.com
* This file is part of Orca - Interactive Forum Script
* GPL
**/

// generate custom $glHeader and $glFooter variables here

// ******************* include dolphin header/footer [begin]

require_once( BX_DIRECTORY_PATH_INC . 'params.inc.php' );
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/functions.php" );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );

$_page['name_index'] 	= 55;

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
{
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
	{
		if ( !( $logged['aff'] = member_auth( 2, false ) ) )
		{
			$logged['moderator'] = member_auth( 3, false );
		}
	}
}

$_page['header'] = $gConf['def_title'];
$_page['header_text'] = $gConf['def_title'];

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = '-=++=-';

ob_start();
PageCode();
$sDolphinDesign = ob_get_clean();
$iPosBody = strpos($sDolphinDesign, '<body');
$iPosBodyX = strpos($sDolphinDesign, '>', $iPosBody);
$iPosBodyEnd = strpos($sDolphinDesign, '</body>');
$iPos = strpos($sDolphinDesign, '-=++=-');
$glHeader = substr ($sDolphinDesign, $iPosBody+$iPosBodyX-$iPosBody+1, $iPos-$iPosBody-$iPosBodyX+$iPosBody-1);
$glFooter = substr ($sDolphinDesign, $iPos+6, $iPosBodyEnd - $iPos - 6 );

// ******************* include dolphin header/footer [ end ]

?>
