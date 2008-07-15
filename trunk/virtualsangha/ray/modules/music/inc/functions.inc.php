<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

function convert($sUserId, $bJustRename = false)
{
	global $sModule;
	global $sFfmpegPath;
	global $sFilesPath;
	
	$aBitrates = array(64, 96, 128, 192, 256);
	$iBitrate = (int)getSettingValue($sModule, "convertBitrate");
	if(!in_array($iBitrate, $aBitrates)) $iBitrate = 192;
	@set_time_limit(500);
	chdir($sFilesPath);
	$sTempFile = $sUserId . TEMP_FILE_NAME;
	$sFile = $sTempFile . MP3_EXTENSION;
	if($bJustRename) return rename($sFilesPath . $sTempFile, $sFilesPath . $sFile);
	
	$sCommand = $sFfmpegPath . " -y -i " . $sTempFile . " -vn -ar 44100 -ab " . $iBitrate . "k " . $sFile;
	exec($sCommand);
	if(!file_exists($sFilesPath . $sFile) || filesize($sFilesPath . $sFile) == 0) return false;
	return true;
}

function renameFile($sUserId, $sFileId)
{
	global $sFilesPath;
	
	$sTempFile = $sFilesPath . $sUserId . TEMP_FILE_NAME . MP3_EXTENSION;
	return rename($sTempFile, $sFileId . MP3_EXTENSION);
}

function deleteTempFiles($sUserId, $bSourceOnly = false)
{
	global $sFilesPath;
	
	$sTempFile = $sUserId . TEMP_FILE_NAME;
	@unlink($sFilesPath . $sTempFile);
	if($bSourceOnly) return;
	@unlink($sFilesPath . $sTempFile . MP3_EXTENSION);	
}

/**
* Delete file
* @param $sFile - file identificator
* @return $bResult - result of operation (true/false)
*/
function deleteFile($sFile)
{
	global $sFilesPath;

	getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Files` WHERE `ID`='" . $sFile . "'");
	getResult("DELETE FROM `" . MODULE_DB_PREFIX . "PlayLists` WHERE `FileId`='" . $sFile . "'");
	parseTags($sFile);
	$sFileName = $sFilesPath . $sFile . MP3_EXTENSION;
	$bResult = @unlink($sFileName);
	return $bResult;
}

/**
* Delete category
* @param $sCategory - category identificator
* @return $bResult: true - success / false - failure
*/
function deleteCategory($sCategory)
{
	$bResult = false;
	if(!isEmptyCategory($sCategory))
	{
		$res = getResult("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Categories` WHERE `Parent`='" . $sCategory . "'");
		for($i=0; $i<mysql_num_rows($res); $i++)
		{
			$aCat = mysql_fetch_assoc($res);
			$bResult = deleteCategory($aCat['ID']);
		}
		$res = getResult("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Files` WHERE `CategoryId`='" . $sCategory . "'");
		for($i=0; $i<mysql_num_rows($res); $i++)
		{
			$aFile = mysql_fetch_assoc($res);
			$bResult = deleteFile($aFile['ID']);
		}
	}
	else $bResult = true;
	getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Categories` WHERE `ID`='" . $sCategory . "'");
	return $bResult;
}

/**
* check if the category is empty
* @param $sCategory - category identificator
* @return $bIsEmpty - true - empty / false - not empty
*/
function isEmptyCategory($sCategory)
{
	$resCats = getResult("SELECT * FROM `" . MODULE_DB_PREFIX . "Categories` WHERE `Parent`='" . $sCategory . "'");
	$resFiles = getResult("SELECT * FROM `" . MODULE_DB_PREFIX . "Files` WHERE `CategoryId`='" . $sCategory . "'");
	$bIsEmpty = !(mysql_num_rows($resCats) || mysql_num_rows($resFiles));
	return $bIsEmpty;
}

/**
* gets given directory path
* @param $sCategory - category identificator
* @return directory path
*/
function getPath($sCategory)
{
	$sPath = "";
	$aCategory = getArray("SELECT * FROM `" . MODULE_DB_PREFIX . "Categories` WHERE `ID`='" . $sCategory . "'");
	$sTitle = $aCategory["Title"];
	if($aCategory["Parent"] != "0") $sPath .= getPath($aCategory["Parent"]);
	return $sPath . "/" . $sTitle;
}
?>
