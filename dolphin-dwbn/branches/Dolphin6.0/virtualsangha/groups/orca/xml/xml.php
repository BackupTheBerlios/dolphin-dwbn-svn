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


/**
 *
 * Add xml contents to whole xml output 
 * put xml content to $integration_xml variable
 *******************************************************************************/

// check if user logged in

global $site;
global $glHeader;
global $glFooter;

$integration_xml .= '<url_dolphin>' . $site['url'] . '</url_dolphin>';
$integration_xml .= '<header><![CDATA['.$glHeader.']]></header>';
$integration_xml .= '<footer><![CDATA['.$glFooter.']]></footer>';

?>
