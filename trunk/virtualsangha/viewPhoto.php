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
require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolSharedMediaView.php');

$iFile = 0;
if (isset($_GET['fileUri']))
	$iFile = getFileIdByUri($_GET['fileUri'], 'photo');
elseif (isset($_GET['fileID']))
	$iFile = (int)$_GET['fileID'];

$member['ID'] = (int)$_COOKIE['memberID'];
	
check_logged();

$oNew = new BxDolSharedMediaView($iFile,'photo', $site, $dir, $member);

$oVotingView = new BxTemplVotingView('gphoto', 0, 0);
$_page['extra_js'] 	= $oVotingView->getExtraJs();

$_page['name_index'] = 81;
$_ni = $_page['name_index'];
$_page['css_name'] = $oNew->oShared->sCssName;
$_page['extra_css'] = $oNew->oCmtsView->getExtraCss();
$_page['extra_js'] 	.= $oNew->oCmtsView->getExtraJs();

$_page['header'] = _t( "_view Photo" );
$_ni = $_page['name_index'];

$check_res = checkAction( $member['ID'], $oNew->oShared->sViewActionName );
if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED && !$logged['admin'] && !$logged['moderator'] ) {
    $sCode  = "
    	<table width=100% height=100% cellpadding=0 cellspacing=0 class=text2>
    		<td align=center bgcolor=$boxbg2>
    			". $check_res[CHECK_ACTION_MESSAGE] ."<br />
    		</td>
    	</table>\n";

	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = $sCode;
	PageCode();
	exit();
}

if (!is_array($oNew->aInfo)) {
	$sCode = MsgBox( _t( '_No file' ) );
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = $sCode;
	PageCode();
	exit();
}
else { 
	$_page['header'] = $oNew->aInfo['medTitle'];
	db_res("UPDATE `{$oNew->oShared->sMainTable}` SET `{$oNew->oShared->aTableFields['medViews']}` = `{$oNew->oShared->aTableFields['medViews']}` + 1 WHERE `{$oNew->oShared->aTableFields['medID']}`='$iFile'");
	
	$_page_cont[$_ni]['page_main_code'] = $oNew->getCode();
	PageCode();
	
	checkAction( $member['ID'], $oNew->oShared->sViewActionName, true );
}

?>