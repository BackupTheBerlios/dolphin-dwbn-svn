<?php

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

require_once('inc/header.inc.php');
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'sharing.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolSharedMedia.php' );

$_page['name_index']	= 44;
$_page['css_name']		= 'explanation.css';

$_page['extra_js'] = '';

check_logged();

$_page['header'] = _t( "_Music Actions" );
$_page['header_text'] = _t("_Music Actions");

$_ni = $_page['name_index'];

$member['ID'] = (int)$_COOKIE['memberID'];

$oMedia = new BxDolSharedMedia('music', $site, $dir, $member);

if (isset($_POST['fileID']) && isset($_POST['send']) && isset($_POST['email'])) {
	$iFile    = (int)$_POST['fileID'];
	$sEmail   = $_POST['email'];
	$sMessage = htmlspecialchars_adv($_POST['messageText']);
	
	$sUrl 	  = process_pass_data($_POST['fileUrl']);
	$sCode .=  $oMedia->sendFileInfo($iFile, $sEmail, $sMessage, $sUrl);
}


if (isset($_GET['action']) && isset($_GET['fileID'])) {
	$sAct = htmlspecialchars_adv($_GET['action']);
	$aAction['fileID'] = (int)$_GET['fileID'];
	switch ($sAct) {
		case 'favorite': $sCode = $oMedia->addToFavorites($aAction['fileID']); break;
		case 'share':    $aAction['action'] = $sAct; $aAction['fileUrl'] = urlencode($_GET['fileUrl']); $sCode = $oMedia->showSubmitForm($aAction); break;
		case 'report':   $aAction['action'] = $sAct; $sCode = $oMedia->showSubmitForm($aAction); break;
	}
}

$_page_cont[$_ni]['page_main_code'] = DesignBoxContent( _t( '_Notification' ), $sCode, 1);

PageCode();

?>