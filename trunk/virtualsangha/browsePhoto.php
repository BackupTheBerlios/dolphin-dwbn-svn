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
require_once(BX_DIRECTORY_PATH_INC . 'sharing.inc.php');
require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolSharedMedia.php');

check_logged();

$member['ID'] = (int)$_COOKIE['memberID'];

$oNew = new BxDolSharedMedia('photo', $site, $dir, $member);

$_page['name_index'] = 82;
$_page['css_name'] = $oNew->sCssName;

$_page['header'] = _t( "_browsePhoto" );
$_page['header_text'] = _t("_browsePhoto");

$_ni = $_page['name_index'];

$aCondition  = $oNew->getConditionArray($logged);
if ($aCondition !== false) {
	$aSqlQuery   = $aCondition['query'];
	$iTotalPages = $aCondition['total'];
	$iCurPage	 = $aCondition['cur_page'];
	$iPerPage	 = $aCondition['per_page'];
}
else {
	$_page_cont[$_ni]['page_main_code'] = _t( '_Sorry, nothing found' );
	PageCode();
	exit;
}

$rData = $oNew->getFilesList($aSqlQuery);

while ($aData = mysql_fetch_assoc($rData))
	$sCode .= $oNew->showBrowseUnit($aData);

$sCode .= '<div class="clear_both"></div>';

$sCode .= $oNew->showPagination($iTotalPages, $iCurPage, $iPerPage);

$_page_cont[$_ni]['page_main_code'] = $sCode;

PageCode();

?>