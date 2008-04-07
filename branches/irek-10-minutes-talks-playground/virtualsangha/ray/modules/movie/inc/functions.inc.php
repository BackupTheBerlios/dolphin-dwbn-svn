<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

function convert($sUserId)
{
	global $sModule;
	global $sFfmpegPath;
	global $sFilesPath;
	
	@set_time_limit(1000);
	$sTempFile = $sUserId . TEMP_FILE_NAME;
	$sInput = " -y -i ";
	$sSize = " -s " . VIDEO_SIZE . " ";
	$sRate = "-r 25 -ar 44100 ";
	$sQuality = getSettingValue($sModule, "sameQuality") == TRUE_VAL ? "-sameq " : "";
	$sBackMode = " >" . $sFilesPath . "log.txt 2>&1 &";
	@chdir($sFilesPath);
	
	$sFile = $sTempFile . PLAY_EXTENSION;
	$sCommand = $sFfmpegPath . $sInput . $sTempFile . $sSize . $sRate . $sQuality . $sTempFile . PLAY_EXTENSION;
	@exec($sCommand);
	if(!file_exists($sFilesPath . $sFile) || filesize($sFilesPath . $sFile) == 0) return false;
	
	$sFile = $sTempFile . SAVE_EXTENSION;
	$sCommand = $sFfmpegPath . $sInput . $sTempFile . $sSize . $sRate . $sQuality . $sTempFile . SAVE_EXTENSION;
	@exec($sCommand);
	if(!file_exists($sFilesPath . $sFile) || filesize($sFilesPath . $sFile) == 0) return false;
	
	return grabImages($sTempFile, $sTempFile);
}

function grabImages($sInputFile, $sOutputFile, $iSecond = 0)
{
	global $sFfmpegPath;
	global $sFilesPath;
	$sInput = " -y -i ";
	
	@chdir($sFilesPath);
	$sFile = $sOutputFile . IMAGE_EXTENSION;
	$sCommand = $sFfmpegPath . $sInput . $sInputFile . " -ss " . $iSecond . " -vframes 1 -an -sameq -f image2 -s " . VIDEO_SIZE . " " . $sFile;
	@exec($sCommand);
	if(!file_exists($sFilesPath . $sFile) || filesize($sFilesPath . $sFile) == 0) return false;
	
	$sFile = $sOutputFile . THUMB_FILE_NAME . IMAGE_EXTENSION;
	$sCommand = $sFfmpegPath . $sInput . $sInputFile . " -ss " . $iSecond . " -vframes 1 -an -sameq -f image2 -s " . THUMB_SIZE . " " . $sFile;
	@exec($sCommand);
	if(!file_exists($sFilesPath . $sFile) || filesize($sFilesPath . $sFile) == 0) return false;
	
	return true;
}

function renameFiles($sUserId, $sFileId)
{
	global $sFilesPath;
	
	$sTempFile = $sFilesPath . $sUserId . TEMP_FILE_NAME;
	return 	rename($sTempFile . IMAGE_EXTENSION, $sFileId . IMAGE_EXTENSION) && 
			rename($sTempFile . THUMB_FILE_NAME . IMAGE_EXTENSION, $sFileId . THUMB_FILE_NAME . IMAGE_EXTENSION) && 
			rename($sTempFile . PLAY_EXTENSION, $sFileId . PLAY_EXTENSION) && 
			rename($sTempFile . SAVE_EXTENSION, $sFileId . SAVE_EXTENSION);
}

function deleteTempFiles($sUserId, $bSourceOnly = false)
{
	global $sFilesPath;
	
	$sTempFile = $sUserId . TEMP_FILE_NAME;
	@unlink($sFilesPath . $sTempFile);
	if($bSourceOnly) return;
	@unlink($sFilesPath . $sTempFile . IMAGE_EXTENSION);
	@unlink($sFilesPath . $sTempFile . THUMB_FILE_NAME . IMAGE_EXTENSION);
	@unlink($sFilesPath . $sTempFile . PLAY_EXTENSION);
	@unlink($sFilesPath . $sTempFile . SAVE_EXTENSION);
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
	$sFileName = $sFilesPath . $sFile;
	$bResult = @unlink($sFileName . PLAY_EXTENSION);
	$bResult = @unlink($sFileName . SAVE_EXTENSION);
	$bResult = @unlink($sFileName . IMAGE_EXTENSION);
	return $bResult;
}
?>