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

require_once( 'header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'params.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );

function defineTimeInterval($iTime) {
	$iTime = time() - (int)$iTime;
	$sCode = _format_when($iTime);
	
	return $sCode;
}

function commentNavigation($iNumber,$iDivis, $iCurr = 0) {
	global $site;
	global $aFile;
	
	$iPages = $iNumber >= 2 ? round($iNumber/2) : 1;

	$sCode = '<div id="commentNavigation">';

	for ($i = 1; $i < $iPages + 1; $i++)
	{
		$sCapt = $i == 1 ? _t("_Page").': ' : '' ;
		$sCode .= '<div class="commentNavUnit">'.$sCapt;
		$sLink =  $i != $iCurr ? '<a href="'.$_SERVER['PHP_SELF'].'?fileID='.$aFile['medID'].'&commPage='.$i.'">'.$i.'</a>' : $iCurr;
		$sCode .= $sLink.'</div>';
	}
	$sCode .= '<div class="clear_both"></div>';
	$sCode .= '</div>';
	
	return $sCode;
}

function getFileIdByUri($sFileName, $sType = '') {
	$sFileName =  process_db_input($sFileName);
	
	switch ($sType) {
		case 'photo':
			$sqlQuery = "SELECT `medID` FROM `sharePhotoFiles` WHERE `medUri`='$sFileName'";
			break;
		case 'music':
			$sqlQuery = "SELECT `ID` FROM `RayMusicFiles` WHERE `Uri`='$sFileName'";
			break;
		case 'video':
			$sqlQuery = "SELECT `ID` FROM `RayMovieFiles` WHERE `Uri`='$sFileName'";
			break;
		default: break;	
	}

	return (int)db_value($sqlQuery);
}

function getFileUrl($iFileId, $sFileUri, $sType, $bPermalink) {
	if ($bPermalink)
		$sLink = $sType.'/gallery/'.$sFileUri;
	else
		$sLink = 'view'.ucfirst($sType).'.php?fileID='.$iFileId;
	
	return $GLOBALS['site']['url'].$sLink;
}

?>