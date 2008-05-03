<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

$sId = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
$sFile = isset($_REQUEST['file']) ? $_REQUEST['file'] : "0";
$sSecondFile = isset($_REQUEST['file2']) ? $_REQUEST['file2'] : "0";
$sTitle = isset($_REQUEST['title']) ? addslashes($_REQUEST['title']) : "Unknown title";
$sTags = isset($_REQUEST['tags']) ? addslashes($_REQUEST['tags']) : "";
$sDesc = isset($_REQUEST['description']) ? addslashes($_REQUEST['description']) : "";
$sTime = isset($_REQUEST['time']) ? $_REQUEST['time'] : "0";
$sCategory = isset($_REQUEST['category']) ? $_REQUEST['category'] : "-1";
$sNick = isset($_REQUEST['nick']) ? $_REQUEST['nick'] : "";
$sPassword = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
$bOwner = isset($_REQUEST['owner']) && $_REQUEST['owner'] == TRUE_VAL ? true : false;
$bAdmin = isset($_REQUEST['admin']) && $_REQUEST['admin'] == TRUE_VAL ? true : false;

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
	* Get mp3 config
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
		$rResult = getResult("SELECT `Owner`, COUNT(`ID`) AS `All`, SUM(IF(`Approved` = '" . FALSE_VAL . "', 1, 0)) AS `Approval` FROM `" . MODULE_DB_PREFIX . "Files` WHERE `Owner`<>'' AND `Owner`<>'0' GROUP BY `Owner`");
		for($i=0; $i<mysql_num_rows($rResult); $i++)
		{
			$aUserStat = mysql_fetch_assoc($rResult);
			$aUserInfo = getUserInfo($aUserStat['Owner']);
			if(empty($aUserInfo['nick'])) continue;
			$sContents .= parseXml($aXmlTemplates['user'], $aUserStat['Owner'], $aUserInfo['nick'], $aUserInfo['profile'], $aUserStat['All'], $aUserStat['Approval']);
		}
		$sContents = makeGroup($sContents, "users");
		break;

	/**
	* Authorize admin
	*/
	case 'adminAuthorize':
		$sContents .= parseXml($aXmlTemplates['result'], loginAdmin($sNick, $sPassword));
		break;

	/**
	* Authorize user.
	*/
	case 'userAuthorize':
		$sContents .= parseXml($aXmlTemplates['result'], loginUser($sId, $sPassword));
		break;

	/**
	* Add category
	*/
	case 'addCategory':
		$sContents = parseXml($aXmlTemplates['result'], "", FAILED_VAL);
		$sId = getValue("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Categories` WHERE `Parent`='" . $sCategory . "' AND `Title`='" . $sTitle . "' LIMIT 1");
		if(!empty($sId)) break;

		getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Categories`(`Parent`, `Title`) VALUES('" . $sCategory . "', '" . $sTitle . "')");
		$sContents = parseXml($aXmlTemplates['result'], getLastInsertId(), SUCCESS_VAL);
		break;

	/**
	* Rename category
	*/
	case 'renameCategory':
		getResult("UPDATE `" . MODULE_DB_PREFIX . "Categories` SET `Title`='" . $sTitle . "' WHERE `ID`='" . $sCategory . "' LIMIT 1");
		break;

	/**
	* Get category contents
	*/
	case 'getCategoryContents':
		if($sCategory != "0")
		{
			$aCategory = getArray("SELECT * FROM `" . MODULE_DB_PREFIX . "Categories` WHERE `ID`='" . $sCategory . "' LIMIT 1");
			$sPath = getPath($sCategory);
			$sContents = parseXml($aXmlTemplates['cat'], $aCategory['ID'], $aCategory['Parent'], $sPath);
		}
		else $sContents = parseXml($aXmlTemplates['cat'], "0", "0", "/");
		$res = getResult("SELECT * FROM `" . MODULE_DB_PREFIX . "Categories` WHERE `Parent`='" . $sCategory . "' ORDER BY `ID` DESC");
		$sContent = "";
		for($i=0; $i<mysql_num_rows($res); $i++)
		{
			$aCat = mysql_fetch_assoc($res);
			$sContent .= parseXml($aXmlTemplates['cat'], $aCat['ID'], $aCat['Title']);
		}
		$sContents .= makeGroup($sContent, "categories");
		$res = getResult("SELECT * FROM `" . MODULE_DB_PREFIX . "Files` WHERE `CategoryId`='" . $sCategory . "' ORDER BY `ID` DESC");
		$sContent = "";
		for($i=0; $i<mysql_num_rows($res); $i++)
		{
			$aFile = mysql_fetch_assoc($res);
			$sContent .= parseXml($aXmlTemplates['file'], $aFile['ID'], $aFile['ID'] . MP3_EXTENSION, $aFile['Time'], $aFile['Title'], $aFile['Tags'], $aFile['Description']);
		}
		$sContents .= makeGroup($sContent, "files");
		break;

	/**
	* Delete categories and then files
	*/
	case 'deleteCategories':
		if($sCategory == "") $bResult = false;
		else
		{
			$aCategories = explode(",", $sCategory);
			for($i=0; $i<count($aCategories); $i++)
				$bResult = deleteCategory($aCategories[$i]);
		}
		//break shouldn't be here

	/**
	* Delete files (reported files)
	*/
	case 'deleteFiles':
		if($sFile == "" && $sCategory == "")
			$sContents = parseXml($aXmlTemplates['result'], "msgErrorDelete", FAILED_VAL);
		elseif($sFile != "")
		{
			$aFiles = explode(",", $sFile);
			for($i=0; $i<count($aFiles); $i++)
				$bResult = deleteFile($aFiles[$i]);
			$sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL);
		}
		break;

	case 'getFiles':
		$sSql = "SELECT * FROM `" . MODULE_DB_PREFIX . "Files` WHERE `Owner` = '" . $sId . "' ORDER BY `ID`";
		//break shouldn't be here

	/**
	* Get user's playlist by ID
	*/
	case 'getPlayList':
		$sFiles = "";
		$sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL);
		if(!isset($sSql))
		{
			$sApprovedFactor = $bOwner ? "" : "`F`.`Approved`='" . TRUE_VAL . "' AND ";
			$sSql = "SELECT * FROM `" . MODULE_DB_PREFIX . "Files` AS `F` INNER JOIN `" . MODULE_DB_PREFIX . "PlayLists` AS `PL` ON `ID` = `FileId` WHERE " . $sApprovedFactor . "`PL`.`Owner` = '" . $sId . "' ORDER BY `PL`.`Order`";
		}
		$res = getResult($sSql);
		for($i=0; $i<mysql_num_rows($res); $i++)
		{
			$aFile = mysql_fetch_assoc($res);
			$sFileName = $aFile['ID'] . MP3_EXTENSION;
			if(!file_exists($sFilesPath . $sFileName)) continue;
			$sFiles .= parseXml($aXmlTemplates['file'], $aFile['ID'], $sFileName, $aFile['Time'], $aFile['Approved'], $aFile['Title'], $aFile['Tags'], $aFile['Description']);
		}
		$sContents .= makeGroup($sFiles, "files");
		break;

	/**
	* Get user's playlist by ID
	*/
	case 'getFile':
		$sFilename = $sFile . MP3_EXTENSION;
		if(!file_exists($sFilesPath . $sFilename))
		{
			$sContents = parseXml($aXmlTemplates['result'], "msgFileNotFound", FAILED_VAL);
			break;
		}
		$aFile = getArray("SELECT * FROM `" . MODULE_DB_PREFIX . "Files` WHERE `ID` = '" . $sFile . "' LIMIT 1");
		if($aFile['Approved'] != TRUE_VAL)
		{
			$sContents = parseXml($aXmlTemplates['result'], "msgFileNotApproved", FAILED_VAL);
			break;
		}
		$sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL);
		$sContents .= parseXml($aXmlTemplates['file'], $aFile['ID'], $sFilename, $aFile['Time'], $aFile['Title'], $aFile['Tags'], $aFile['Description']);
		break;

	/**
	* Change playlist elements playing order
	*/
	case 'changeOrder':
		$res = getResult("SELECT `FileId`, `Order` FROM `" . MODULE_DB_PREFIX . "PlayLists` WHERE `Owner`='" . $sId . "' AND (`FileId`='" . $sFile . "' OR `FileId`='" . $sSecondFile . "')");
		$oSong1 = mysql_fetch_assoc($res);
		$oSong2 = mysql_fetch_assoc($res);
		getResult("UPDATE `" . MODULE_DB_PREFIX . "PlayLists` SET `Order`='" . $oSong1['Order'] . "' WHERE `FileId`='" . $oSong2['FileId'] . "' AND `Owner`='" . $sId . "'");
		getResult("UPDATE `" . MODULE_DB_PREFIX . "PlayLists` SET `Order`='" . $oSong2['Order'] . "' WHERE `FileId`='" . $oSong1['FileId'] . "' AND `Owner`='" . $sId ."'");
		$sContents .= parseXml($aXmlTemplates['result'], TRUE_VAL);
		break;

	/**
	* Report file
	*/
	case 'reportFile':
		getResult("UPDATE `" . MODULE_DB_PREFIX . "Files` SET `Reports`=(`Reports` + 1) WHERE `ID`='" . $sFile . "'");
		$sContents .= parseXml($aXmlTemplates['result'], TRUE_VAL);
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

	case 'getTempFile':
		$sContents = parseXml($aXmlTemplates['result'], "msgErrorUpload", FAILED_VAL);
		$sFilename = $sId . TEMP_FILE_NAME;
		if(file_exists($sFilesPath . $sFilename))	$sContents = parseXml($aXmlTemplates['result'], $sFilename, SUCCESS_VAL);
		else 										$sContents = parseXml($aXmlTemplates['result'], "msgErrorUpload", FAILED_VAL);
		break;
	
	case 'initFile':
		$sContents = parseXml($aXmlTemplates['result'], "msgErrorUpload", FAILED_VAL);
		$sTempFileName = $sFilesPath . $sId . TEMP_FILE_NAME;
		if(!file_exists($sTempFileName)) break;
		$sConvert = isset($_REQUEST['convert']) ? $_REQUEST['convert'] : TRUE_VAL;
		$bJustRename = $sConvert != TRUE_VAL;
		if(!convert($sId, $bJustRename))
		{
			deleteTempFiles($sId);
			break;
		}
		$sAutoApprove = $bAdmin ? TRUE_VAL : getSettingValue($sModule, "autoApprove");
		$sUri = genUri($sTitle);
		getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Files`(`CategoryId`, `Title`, `Uri`, `Tags`, `Description`, `Date`, `Owner`, `Approved`) VALUES ('" . $sCategory . "', '" . $sTitle . "', '" . $sUri . "', '" . $sTags . "', '" . $sDesc . "', '" . time() . "', '" . $sId . "', '" . $sAutoApprove . "')");
		$sFileId = getLastInsertId();
		if(!renameFile($sId, $sFileId))
		{
			deleteTempFiles($sId);
			getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Files` WHERE `ID`='" . $sFileId . "' LIMIT 1");
			break;
		}
		$sNewFileName = $sFileId . MP3_EXTENSION;
		deleteTempFiles($sId, true);
		parseTags($sFileId);
			
		if($sId != "0")
		{
			getResult("UPDATE `" . MODULE_DB_PREFIX . "PlayLists` SET `Order`=`Order`+1 WHERE `Owner` = '" . $sId . "'");
			getResult("INSERT INTO `" . MODULE_DB_PREFIX . "PlayLists` VALUES('" . $sFileId . "', '" . $sId . "', '1')");
		}
		$sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL);
		$sContents .= parseXml($aXmlTemplates['file'], $sFileId, $sNewFileName);
		break;

	case 'updateFile':
		getResult("UPDATE `" . MODULE_DB_PREFIX . "Files` SET `Title`='" . $sTitle . "', `Tags`='" . $sTags . "', `Description`='" . $sDesc . "' WHERE `ID`='" . $sFile . "' LIMIT 1");
		parseTags($sFile);
		break;

	case 'approveFile':
		$aFiles = empty($sFile) ? array() : explode(",", $sFile);
		$sIn = count($aFiles > 0) ? " IN('" . implode("','", $aFiles) . "')" : " IN('0')";
		getResult("UPDATE `" . MODULE_DB_PREFIX . "Files` SET `Approved`='" . TRUE_VAL . "' WHERE `ID` " . $sIn);
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
		$sContents = parseXml($aXmlTemplates['result'], "msgErrorDelete", FAILED_VAL);
		if(!loginUser($sId, $sPassword)) break;
		$aFiles = empty($sFile) ? array() : explode(",", $sFile);
		$sIn = count($aFiles > 0) ? " IN('" . implode("','", $aFiles) . "')" : " IN('0')";
		$sQuery = "SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Files` WHERE `Owner`='" . $sId . "' AND `ID`" . $sIn;
		$sQuery1 = "DELETE FROM `" . MODULE_DB_PREFIX . "PlayLists` WHERE `Owner`='" . $sId . "' AND `FileId`" . $sIn;
		$res = getResult($sQuery);
		getResult($sQuery1);
		$bResult = true;
		for($i=0; $i<mysql_num_rows($res); $i++)
		{
			$aFile = mysql_fetch_assoc($res);
			$bResult = deleteFile($aFile["ID"]);
		}
		if($bResult) $sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL);
		break;

	/**
	* Delete files by admin
	*/
	case 'deleteByAdmin':
		$sContents = parseXml($aXmlTemplates['result'], "Error deleting files", FAILED_VAL);
		if(!loginAdmin($sNick, $sPassword)) break;
		$aFiles = empty($sFile) ? array() : explode(",", $sFile);
		$sIn = count($aFiles > 0) ? " IN('" . implode("','", $aFiles) . "')" : " IN('0')";
		$sQuery = "SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Files` WHERE `ID`" . $sIn;
		$sQuery1 = "DELETE FROM `" . MODULE_DB_PREFIX . "PlayLists` WHERE `FileId`" . $sIn;
		$res = getResult($sQuery);
		getResult($sQuery1);
		$bResult = true;
		for($i=0; $i<mysql_num_rows($res); $i++)
		{
			$aFile = mysql_fetch_assoc($res);
			$bResult = deleteFile($aFile["ID"]);
		}
		if($bResult) $sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL);
		break;

	/**
	* add files to playlist
	*/
	case 'addFilesToPlayList':
		$aFiles = explode(",", $sFile);
		$iFilesCount = count($aFiles);
		if($iFilesCount == 0)
		{
			$sContents = parseXml($aXmlTemplates['result'], FALSE_VAL);
			break;
		}
		getResult("UPDATE `" . MODULE_DB_PREFIX . "PlayLists` SET `Order`=`Order`+" . $iFilesCount . " WHERE `Owner` = '" . $sId . "'");
		for($i=0; $i<$iFilesCount; $i++)
			getResult("INSERT INTO `" . MODULE_DB_PREFIX . "PlayLists` VALUES('" . $aFiles[$i] . "', '" . $sId . "', '" . ($i+1) . "')");
		$sContents = parseXml($aXmlTemplates['result'], TRUE_VAL);
		break;
}
?>