<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

$sId = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
$sNick = isset($_REQUEST['nick']) ? $_REQUEST['nick'] : "";
$sPassword = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
$sFile = isset($_REQUEST['file']) ? $_REQUEST['file'] : "";
$sSecondFile = isset($_REQUEST['file2']) ? $_REQUEST['file2'] : "0";
$sTitle = isset($_REQUEST['title']) ? addslashes($_REQUEST['title']) : "Untitled";
$sTags = isset($_REQUEST['tags']) ? addslashes($_REQUEST['tags']) : "";
$sDesc = isset($_REQUEST['description']) ? addslashes($_REQUEST['description']) : "";
$sTime = isset($_REQUEST['time']) ? $_REQUEST['time'] : "0";
$sCategory = isset($_REQUEST['category']) ? $_REQUEST['category'] : "-1";
$bOwner = isset($_REQUEST['owner']) && $_REQUEST['owner'] == TRUE_VAL ? true : false;

$sSkin = isset($_REQUEST['skin']) ? $_REQUEST['skin'] : "";
$sLanguage = isset($_REQUEST['language']) ? $_REQUEST['language'] : "english";

switch ($sAction)
{
	/**
	* gets skins
	*/
	case 'getSkins':
		$sContents = printFiles($sModule, "skins");
		break;

    /**
     * Sets default skin.
     */
    case 'setSkin':
            setCurrentFile($sModule, $sSkin, "skins");
            break;

    /**
     * gets languages
     */
    case 'getLanguages':
          $sContents = printFiles($sModule, "langs");
          break;

    /**
     * Sets default language.
     */
    case 'setLanguage':
          setCurrentFile($sModule, $sLanguage, "langs");
          break;

	/**
	* Authorize user.
	*/
	case 'userAuthorize':
		$sContents .= parseXml($aXmlTemplates['result'], loginUser($sId, $sPassword));
		break;

	/**
	* Authorize admin
	*/
	case 'adminAuthorize':
		$sContents = parseXml($aXmlTemplates['result'], loginAdmin($sNick, $sPassword));
		break;

	/**
	* Get config
	*/
	case 'config':
		$sFileName = $sModulesPath . $sModule . "/xml/config.xml";
		$rHandle = fopen($sFileName, "rt");
		$sContents = fread($rHandle, filesize($sFileName)) ;
		fclose($rHandle);
		$iMaxSize = getSettingValue($sModule, "fileSize");
		$iMaxFileSize = min($iMaxSize, (ini_get('upload_max_filesize') + 0), (ini_get('post_max_size') + 0));
		$sContents = str_replace("#fileMaxSize#", $iMaxFileSize, $sContents);
		$sContents = str_replace("#filesUrl#", $sFilesUrl, $sContents);
		break;

	case 'getUsers':
		$sContents = "";
		$rResult = getResult("SELECT `Owner`, COUNT(`ID`) AS `All`, SUM(IF(`Approved` = '" . FALSE_VAL . "', 1, 0)) AS `Approval` FROM `" . MODULE_DB_PREFIX . "Files` GROUP BY `Owner`");
		for($i=0; $i<mysql_num_rows($rResult); $i++)
		{
			$aUserStat = mysql_fetch_assoc($rResult);
			$aUserInfo = getUserInfo($aUserStat['Owner']);
			$sContents .= parseXml($aXmlTemplates['user'], $aUserStat['Owner'], $aUserInfo['nick'], $aUserInfo['profile'], $aUserStat['All'], $aUserStat['Approval']);
		}
		$sContents = makeGroup($sContents, "users");
		break;

	case 'getFile':
		$sPlayFile = $sId . PLAY_EXTENSION;
		$sSaveFile = $sId . SAVE_EXTENSION;
		$sImageFile = $sId . IMAGE_EXTENSION;
		if(!file_exists($sFilesPath . $sPlayFile) || !file_exists($sFilesPath . $sSaveFile) || !file_exists($sFilesPath . $sImageFile))
		{
			$sContents = parseXml($aXmlTemplates['result'], "msgFileNotFound", FAILED_VAL);
			break;
		}
		$aFile = getArray("SELECT * FROM `" . MODULE_DB_PREFIX . "Files` WHERE `ID`='" . $sId . "' LIMIT 1");
		if($aFile['Approved'] != TRUE_VAL)
		{
			$sContents = parseXml($aXmlTemplates['result'], "msgFileNotApproved", FAILED_VAL);
			break;
		}
		$sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL);
		$sFile .= parseXml($aXmlTemplates['file'], $sId, $sPlayFile, $sSaveFile, $sImageFile, $aFile['Time'], $aFile['Title'], $aFile['Tags'], $aFile['Description']);
		$sContents .= makeGroup($sFile, "files");
		break;

	/**
	* Delete files (reported files)
	*/
	case 'deleteFiles':
		if($sFile == "")
			$sContents = parseXml($aXmlTemplates['result'], "msgErrorDelete", FAILED_VAL);
		elseif($sFile != "")
		{
			$aFiles = explode(",", $sFile);
			for($i=0; $i<count($aFiles); $i++)
				$bResult = deleteFile($aFiles[$i]);
			$sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL);
		}
		break;
		
	/**
	* Get user's playlist by ID
	*/
	case 'getPlayList':
		$sContents = "";
		$sApprovedFactor = $bOwner ? "" : "`F`.`Approved`='" . TRUE_VAL . "' AND ";
		$res = getResult("SELECT * FROM `" . MODULE_DB_PREFIX . "Files` AS `F` INNER JOIN `" . MODULE_DB_PREFIX . "PlayLists` AS `PL` ON `ID` = `FileId` WHERE " . $sApprovedFactor . "`PL`.`Owner` = '" . $sId . "' ORDER BY `PL`.`Order`");
		for($i=0; $i<mysql_num_rows($res); $i++)
		{
			$aFile = mysql_fetch_assoc($res);
			$sPlayFile = $aFile['ID'] . PLAY_EXTENSION;
			$sSaveFile = $aFile['ID'] . SAVE_EXTENSION;
			$sImageFile = $aFile['ID'] . IMAGE_EXTENSION;
			$sThumbFile = $aFile['ID'] . THUMB_FILE_NAME . IMAGE_EXTENSION;
			if(!file_exists($sFilesPath . $sThumbFile)) $sThumbFile = $sImageFile;
			if(!file_exists($sFilesPath . $sPlayFile) || !file_exists($sFilesPath . $sSaveFile) || !file_exists($sFilesPath . $sImageFile)) continue;
			$sContents .= parseXml($aXmlTemplates['file'], $aFile['ID'], $sPlayFile, $sSaveFile, $sImageFile, $sThumbFile, $aFile['Time'], $aFile['Approved'], $aFile['Title'], $aFile['Tags'], $aFile['Description']);
		}
		$sContents = makeGroup($sContents, "files");
		$sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL) . $sContents;
		break;
		
	/**
	* Change playlist elements playing order
	*/
	case 'changeOrder':
		$res = getResult("SELECT `FileId`, `Order` FROM `" . MODULE_DB_PREFIX . "PlayLists` WHERE `Owner`='" . $sId . "' AND (`FileId`='" . $sFile . "' OR `FileId`='" . $sSecondFile . "')");
		$oFile1 = mysql_fetch_assoc($res);
		$oFile2 = mysql_fetch_assoc($res);
		getResult("UPDATE `" . MODULE_DB_PREFIX . "PlayLists` SET `Order`='" . $oFile1['Order'] . "' WHERE `FileId`='" . $oFile2['FileId'] . "' AND `Owner`='" . $sId . "'");
		getResult("UPDATE `" . MODULE_DB_PREFIX . "PlayLists` SET `Order`='" . $oFile2['Order'] . "' WHERE `FileId`='" . $oFile1['FileId'] . "' AND `Owner`='" . $sId ."'");
		$sContents = parseXml($aXmlTemplates['result'], TRUE_VAL);
		break;
		
	/**
	* Upload user's file
	*/
	case 'uploadFile':
		$sTempFileName = $sFilesPath . $sId . TEMP_FILE_NAME;
		@unlink($sTempFileName);
		if(is_uploaded_file($_FILES['Filedata']['tmp_name']))
			move_uploaded_file($_FILES['Filedata']['tmp_name'], $sTempFileName);
		break;

	case 'initFile':
		$sContents = parseXml($aXmlTemplates['result'], "msgErrorUpload", FAILED_VAL);
		$sTempFileName = $sFilesPath . $sId . TEMP_FILE_NAME;
		//echo $sTempFileName . " - " . file_exists($sTempFileName) . "!!!";
		if(!file_exists($sTempFileName)) break;
		//echo "before convert";
		if(!convert($sId))
		{
			deleteTempFiles($sId);
			break;
		}
		//echo "after convert";
		$sAutoApprove = getSettingValue($sModule, "autoApprove");
		getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Files`(`CategoryId`, `Title`, `Tags`, `Description`, `Date`, `Owner`, `Approved`) VALUES ('" . $sCategory . "', '" . $sTitle . "', '" . $sTags . "', '" . $sDesc . "', '" . time() . "', '" . $sId . "', '" . $sAutoApprove . "')");
		$sFileId = getLastInsertId();
		//echo "before rename";
		if(!renameFiles($sId, $sFileId))
		{
			deleteTempFiles($sId);
			getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Files` WHERE `ID`='" . $sFileId . "' LIMIT 1");
			break;
		}
		//echo "after rename";
		deleteTempFiles($sId, true);
		parseTags($sFileId);
			
		if($sId != "0")
		{
			getResult("UPDATE `" . MODULE_DB_PREFIX . "PlayLists` SET `Order`=`Order`+1 WHERE `Owner` = '" . $sId . "'");
			getResult("INSERT INTO `" . MODULE_DB_PREFIX . "PlayLists` VALUES('" . $sFileId . "', '" . $sId . "', '1')");
		}
		$sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL);
		$sContents .= parseXml($aXmlTemplates['file'], $sFileId, $sFileId . PLAY_EXTENSION, $sFileId . SAVE_EXTENSION, $sFileId . IMAGE_EXTENSION, $sFileId . THUMB_FILE_NAME . IMAGE_EXTENSION);
		break;

	case 'screenshot':
		if(grabImages($sId . PLAY_EXTENSION, $sId, $sTime)) 
			 $sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL);
		else $sContents = parseXml($aXmlTemplates['result'], "msgErrorScreenshot", FAILED_VAL);
		break;

	case 'updateFile':
		getResult("UPDATE `" . MODULE_DB_PREFIX . "Files` SET `Title`='" . $sTitle . "', `Tags`='" . $sTags . "', `Description`='" . $sDesc . "' WHERE `ID`='" . $sFile . "' LIMIT 1");
		parseTags($sFile);
		break;
		
	case 'approveFile':
		getResult("UPDATE `" . MODULE_DB_PREFIX . "Files` SET `Approved`='" . TRUE_VAL . "' WHERE `ID`='" . $sFile . "' LIMIT 1");
		break;

	/**
	* set user's uploaded file time
	*/
	case 'updateFileTime':
		getResult("UPDATE `" . MODULE_DB_PREFIX . "Files` SET `Time`='" . $sTime . "' WHERE `ID`='" . $sFile . "'");
		$sContents = parseXml($aXmlTemplates['result'], TRUE_VAL);
		break;
	
	/**
	* Delete files from playlist
	*/
	case 'deleteFromPlayList':
		$aFiles = explode(",", $sFile);
		$sQuery = "SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Files` WHERE `Owner`='" . $sId . "' AND (0";
		$sQuery1 = "DELETE FROM `" . MODULE_DB_PREFIX . "PlayLists` WHERE `Owner`='" . $sId . "' AND (0";
		for($i=0; $i<count($aFiles); $i++)
		{
			$sQuery .= " OR `ID`=".$aFiles[$i];
			$sQuery1 .= " OR `FileId`=".$aFiles[$i];
		}
		$sQuery .= ")";
		$sQuery1 .= ")";
		$res = getResult($sQuery);
		getResult($sQuery1);
		$bResult = true;
		for($i=0; $i<mysql_num_rows($res); $i++)
		{
			$aFile = mysql_fetch_assoc($res);
			$bResult = deleteFile($aFile["ID"]);
		}
		$sContents = parseXml($aXmlTemplates['result'], $bResult ? TRUE_VAL : FALSE_VAL);
		break;
}
?>