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

if (isset($_REQUEST['type'])) {
    $sType = htmlspecialchars_adv($_REQUEST['type']);
    if( $sType != 'photo' and $sType != 'music' and $sType != 'video' )
        exit;
}

$_page['header'] = _t( "_".ucfirst($sType)." Actions" );
$_page['header_text'] = _t("_".ucfirst($sType)."Actions");

$_ni = $_page['name_index'];

$member['ID'] = (int)$_COOKIE['memberID'];

$oMedia = new BxDolSharedMedia($sType, $site, $dir, $member);

// -------------------------------------------------------------------------------------------------- //

if (isset($_POST['fileID']) && isset($_POST['send']) && isset($_POST['email'])) {
	$iFile    = (int)$_POST['fileID'];
	$sEmail   = $_POST['email'];
	$sMessage = htmlspecialchars_adv($_POST['messageText']);
	$sUrl 	  = process_pass_data($_POST['fileUrl']);
	$sCode .=  $oMedia->sendFileInfo($iFile, $sEmail, $sMessage, $sUrl);
}

if (isset($_POST['mediaAction']) && $_POST['mediaAction'] == 'edit') {
	$iFile   = (int)$_POST['fileID'];
	$oMedia->saveChanges($iFile);
	echo '<script language="javascript">window.parent.opener.location = window.parent.opener.location; window.parent.close();</script>';
}

if (isset($_GET['action']) && isset($_GET['fileID'])) {
	$sAct = htmlspecialchars_adv($_GET['action']);
	$aAction['fileID'] = (int)$_GET['fileID'];
	switch ($sAct) {
		case 'favorite': $sCode = $oMedia->addToFavorites($aAction['fileID']); break;
		case 'edit': 	 $sCode = $oMedia->displayMediaEditForm($aAction['fileID']); break;
		case 'report':
		case 'share':    $aAction['action'] = $sAct; $aAction['fileUrl'] = urlencode($_GET['fileUrl']); $sCode = $oMedia->showSubmitForm($aAction); break;
	}
}

$_page_cont[$_ni]['page_main_code'] = DesignBoxContent( _t( '_Notification' ), $sCode, 1);

PageCode();

?>