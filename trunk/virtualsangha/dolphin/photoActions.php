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

$_page['header'] = _t( "_Photo Actions" );
$_page['header_text'] = _t("_Photo Actions");

$_ni = $_page['name_index'];

$member['ID'] = (int)$_COOKIE['memberID'];

$oMedia = new BxDolSharedMedia('photo', $site, $dir, $member);

// ----------------------------------- main variables for actions ------------------------------------ //

$sTable = 'sharePhotoFiles';
$sIdent = 'medID';
$aInfo = array(
	'Owner'=> 'medProfId',
	'Title'=> 'medTitle',
	'Tags' => 'medTags',
	'Desc' => 'medDesc',
	'Uri'  => 'medUri'
);

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
	saveChanges($iFile);
	echo '<script language="javascript">window.parent.opener.location = window.parent.opener.location; window.parent.close();</script>';
}

if (isset($_GET['action']) && isset($_GET['fileID'])) {
	$sAct = htmlspecialchars_adv($_GET['action']);
	$aAction['fileID'] = (int)$_GET['fileID'];
	switch ($sAct) {
		case 'favorite': $sCode = $oMedia->addToFavorites($aAction['fileID']); break;
		case 'edit': 	 $sCode = displayMediaEditForm($aAction['fileID']); break;
		case 'report':
		case 'share':    $aAction['action'] = $sAct; $aAction['fileUrl'] = urlencode($_GET['fileUrl']); $sCode = $oMedia->showSubmitForm($aAction); break;
	}
}

$_page_cont[$_ni]['page_main_code'] = DesignBoxContent( _t( '_Notification' ), $sCode, 1);

PageCode();

function displayMediaEditForm($iFile) {
	global $member;
	global $sTable;
	global $sIdent;
	global $aInfo;
	
	$sqlQuery = "SELECT ";
	
	$sTempl = '<div>__Key__</div>';
	$sLine = '';
	
	foreach ($aInfo as $sKey => $sVal)
	{
		$sqlQuery .= "`$sVal`,";
		if ($sKey != 'Owner' && $sKey != 'Uri')
		{
			$sHead  = str_replace('__Key__', $sKey, $sTempl);
			$sPatt  = $sKey != 'Desc' ? '<input type="text" size="40" name="'.$sKey.'" value="'.$sVal.'"/>' : '<textarea cols="30" rows="10" name="'.$sKey.'">'.$sVal.'</textarea>' ;
			$sMain  = str_replace('__Key__', $sPatt, $sTempl);
			$sLine .= $sHead.$sMain;
		}	
	}
	
	$sqlQuery = trim($sqlQuery, ','). "FROM `$sTable` WHERE `$sIdent`='$iFile'";
	
	$aData = db_arr($sqlQuery);
	
	if ($aData[$aInfo['Owner']] != $member['ID'])
		exit;

	foreach ($aInfo as $sKey => $sValue)
		$sLine = str_replace($sValue, $aData[$sValue], $sLine);

	$sCode  = '<div class="mediaInfo">';
		
		$sCode .= '<iframe name="Edit" style="display: none;"></iframe>
			<form target="Edit" name="submitAction" method="post" action="'.$_SERVER['PHP_SELF'].'">';
			
			$sCode .= $sLine;
			$sCode .= '<div><input type="submit" size="15" name="save" value="'._t('_Save Changes').'">';
			$sCode .= '<input type="reset" size="15" name="send" value="Reset"></div>';
			$sCode .= '<input type="hidden" name="fileID" value="'.$iFile.'">';
			$sCode .= '<input type="hidden" name="mediaAction" value="edit">';
			
		$sCode .= '</form>';
	
	$sCode .= '</div>';
	
	return $sCode;
}

function saveChanges($iFile) {
	global $aInfo;
	global $sTable;
	global $member;
	global $sIdent;
	
	$sqlQuery = "UPDATE `$sTable` SET ";
	foreach ($aInfo as $sKey => $sVal)
	{
		switch ($sKey)
		{
			case 'Owner': break;
			case 'Uri' : break;
			default: 
				$sInput    = process_db_input($_POST[$sKey]);
				$sqlQuery .= "`$sVal` = '$sInput',";
		}
	}	

	$sqlQuery = rtrim($sqlQuery,',')." WHERE `$sIdent` = '$iFile' AND `{$aInfo['Owner']}`='{$member['ID']}'";
	db_res($sqlQuery);
	if (!mysql_affected_rows())
		exit;
}

?>