<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

$sId = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
$sModeratorId = isset($_REQUEST['moderator']) ? $_REQUEST['moderator'] : "";
$sNick = isset($_REQUEST['nick']) ? $_REQUEST['nick'] : "";
$sPassword = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
$sType = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";
$sOnline = isset($_REQUEST['online']) ? $_REQUEST['online'] : USER_STATUS_ONLINE;

$sMsg = isset($_REQUEST['msg']) ? addslashes($_REQUEST['msg']) : "";
$sSmileset = isset($_REQUEST['smileset']) ? $_REQUEST['smileset'] : "";
$sSender = $_REQUEST['sender'] ? $_REQUEST['sender'] : "";
$sRcp = $_REQUEST['recipient'] ? $_REQUEST['recipient'] : "";
$sMessage = isset($_REQUEST['message']) ? addslashes($_REQUEST['message']) : "";

$iRoomId = isset($_REQUEST['roomId']) ? (int)$_REQUEST['roomId'] : 0;
$sRoom = isset($_REQUEST['room']) ? addslashes($_REQUEST['room']) : "";
$sDesc = isset($_REQUEST['desc']) ? addslashes($_REQUEST['desc']) : "";

$sParamName = isset($_REQUEST['param']) ? $_REQUEST['param'] : "";
$sParamValue = isset($_REQUEST['value']) ? $_REQUEST['value'] : "";

$sSkin = isset($_REQUEST['skin']) ? $_REQUEST['skin'] : "";
$sLanguage = isset($_REQUEST['language']) ? $_REQUEST['language'] : "english";

//shows that current user is admin
//true - admin, false - usual user
$bAdmin = isset($_REQUEST['admin'])? true: false;

switch ($sAction)
{
	case 'getPlugins':
		$sContents = "";
		if($rDirHandle = opendir($sModulesPath . $sModule . "/plugins/"))
			while(false !== ($sPlugin = readdir($rDirHandle)))
				if(substr($sPlugin, 0, 1) != '.')
					$sContents .= parseXml(array(1 => '<plugin><![CDATA[#1#]]></plugin>'), $sModulesUrl . $sModule . "/plugins/" . $sPlugin);
		closedir($rDirHandle);
		$sContents = makeGroup($sContents, "plugins");
		break;
		
	/**
	* gets skins
	*/
	case 'getSkins':
		$sContents = printFiles($sModule, "skins", false, true);
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
		$sContents = printFiles($sModule, "langs", false, true);
		break;

	/**
	* Sets default language.
	*/
	case 'setLanguage':
		setCurrentFile($sModule, $sLanguage, "langs");
		break;

	/**
	* Get chat's config.
	*/
    case 'config':
		$sFileName = $sModulesPath . $sModule . "/xml/config.xml";
		$rHandle = fopen($sFileName, "rt");
		$sContents = fread($rHandle, filesize($sFileName)) ;
		fclose($rHandle);
		
		$sContents = str_replace("#siteName#", getSiteName(), $sContents);
		$iMaxFileSize = min((ini_get('upload_max_filesize') + 0), (ini_get('post_max_size') + 0));
		$sContents = str_replace("#fileMaxSize#", $iMaxFileSize, $sContents);
		$sContents = str_replace("#soundsUrl#", $sSoundsUrl, $sContents);
		$sContents = str_replace("#smilesetsUrl#", $sSmilesetsUrl, $sContents);
		$sContents = str_replace("#filesUrl#", $sFilesUrl, $sContents);
		$sContents = str_replace("#useServer#", useServer() ? TRUE_VAL : FALSE_VAL, $sContents);
		$sContents = str_replace("#serverUrl#", getRMSUrl($sServerApp), $sContents);
		break;

	/**
	* Authorize user.
	*/
	case 'userAuthorize':
		if(loginUser($sId, $sPassword) == TRUE_VAL/* && doBan("check", $sId) != TRUE_VAL*/)
		{
			$sResult = getValue("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Profiles` WHERE `ID`='" . $sId . "'");
			if(empty($sResult)) getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Profiles` SET `ID`='" . $sId . "', `Smileset`='" . $sDefSmileset . "'");
			$iCurrentTime = time();
			$aUser = getUserInfo($sId);
			$aUser['sex'] = $aUser['sex'] == 'female' ? "F" : "M";
			getResult("REPLACE `" . MODULE_DB_PREFIX . "CurrentUsers` SET `ID`='" . $sId . "', `Nick`='" . $aUser['nick'] . "', `Sex`='" . $aUser['sex'] . "', `Age`='" . $aUser['age'] . "', `Desc`='" . addslashes($aUser['desc']) . "', `Photo`='" . $aUser['photo'] . "', `Profile`='" . $aUser['profile'] . "', `Start`='" . $iCurrentTime . "', `When`='" . $iCurrentTime . "', `Status`='" . USER_STATUS_NEW . "'");
			getResult("DELETE FROM `" . MODULE_DB_PREFIX . "RoomsUsers` WHERE `User`='" . $sId . "'");
			$rFiles = getResult("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Messages` WHERE `Recipient`='" . sId . "' AND `Type`='file'");
			while($aFile = mysql_fetch_assoc($rFiles)) removeFile($aFile['ID']);
			
			$sContents = parseXml($aXmlTemplates['result'], TRUE_VAL);
			$sContents .= parseXml($aXmlTemplates['user'], $sId, USER_STATUS_NEW, $aUser['nick'], $aUser['sex'], $aUser['age'], $aUser['desc'], $aUser['photo'], $aUser['profile'], CHAT_TYPE_FULL, USER_STATUS_ONLINE);
		}
		else $sContents = parseXml($aXmlTemplates['result'], FALSE_VAL);
		break;

	/**
	* Get sounds
	*/
	case 'getSounds':
		$sFileName = $sModulesPath . $sModule . "/xml/sounds.xml";
		if(file_exists($sFileName))
		{
			$rHandle = fopen($sFileName, "rt");
			$sContents = fread($rHandle, filesize($sFileName));
			fclose($rHandle);
		}
		else $sContents = makeGroup("", "items");
		break;

	/**
         * gets smilesets
         */
	case 'getSmilesets':
		$sConfigFile = "config.xml";
		$sContents = parseXml($aXmlTemplates['smileset'], "", "") . makeGroup("", "smilesets");
		$aSmilesets = array();
		if($rDirHandle = opendir($sSmilesetsPath))
			while(false !== ($sDir = readdir($rDirHandle)))
				if($sDir != "." && $sDir != ".." && is_dir($sSmilesetsPath . $sDir) && file_exists($sSmilesetsPath . $sDir . "/" . $sConfigFile))
					$aSmilesets[] = $sDir;
		closedir($rDirHandle);
		if(count($aSmilesets) == 0) break;
			
		if(!in_array($sDefSmileset, $aSmilesets)) $sDefSmileset = $aSmilesets[0];
		$sUserSmileset = getValue("SELECT `Smileset` FROM `" . MODULE_DB_PREFIX . "Profiles` WHERE `ID`='" . $sId . "'");
		if(empty($sUserSmileset) || !file_exists($sSmilesetsPath . $sUserSmileset)) $sUserSmileset = $sDefSmileset;
		
		$sContents = parseXml($aXmlTemplates['smileset'], $sUserSmileset . "/", $sSmilesetsUrl);
		$sData = "";
		for($i=0; $i<count($aSmilesets); $i++)
		{
			$sName = getSettingValue(GLOBAL_MODULE, "name", "config", false, $sDataDir . $sSmilesetsDir . $aSmilesets[$i]);
			$sData .= parseXml($aXmlTemplates['smileset'], $aSmilesets[$i] . "/", $sConfigFile, empty($sName) ? $aSmilesets[$i] : $sName);
		}
		$sContents .= makeGroup($sData, "smilesets");
		break;

	/**
	* Sets default smileset.
	*/
	case 'setSmileset':
		getResult("UPDATE `" . MODULE_DB_PREFIX . "Profiles` SET `Smileset`='" . $sSmileset . "' WHERE `ID`='" . $sId . "'");
		break;

	/**
	* Get rooms.
	*/
	case 'getRooms':
		$sContents = makeGroup(getRooms("all", $sId), "rooms");
		break;

	/**
	* Creats new room.
	* Note. This action is used in both modes and by admin.
	*/
	case 'createRoom':
		$iRoomId = doRoom('insert', $sId, 0, $sRoom, $sPassword, $sDesc);
		if(empty($iRoomId))	$sContents = parseXml($aXmlTemplates['result'], "msgErrorCreatingRoom", FAILED_VAL);
		else 				$sContents = parseXml($aXmlTemplates['result'], $iRoomId, SUCCESS_VAL);
		break;

	case 'editRoom':
		doRoom('update', 0, $iRoomId, $sRoom, $sPassword, $sDesc);
		$sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL);
		break;
		
	/**
	* Delete room from database.
	* Note. This action is used in both modes and by admin.
	*/
	case 'deleteRoom':
		doRoom('delete', 0, $iRoomId);
		$sContents = parseXml($aXmlTemplates['result'], TRUE_VAL);
		break;

	case 'enterRoom':
		doRoom('enter', $sId, $iRoomId);
		break;

	case 'exitRoom':
		doRoom('exit', $sId, $iRoomId);
		break;

	case 'checkRoomPassword':
		$sId = getValue("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Rooms` WHERE `ID`='" . $iRoomId . "' AND `Password`='" . $sPassword . "' LIMIT 1");
		if(empty($sId)) $sContents = parseXml($aXmlTemplates['result'], "msgWrongRoomPassword", FAILED_VAL);
		else			$sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL);
		break;


	/**
	* ===> Next actions are needed for XML version only. <===
	* Gets information about all online users.
	* NOTE. This action is used in XML mode and by ADMIN.
	* @comment Use this function instead of admin function "getOnline".
	*/
	case 'getOnlineUsers':
		//--- Check RayChatMessages table and drop autoincrement if it is possible. ---//
		$rResult = getResult("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "CurrentUsers`");
		if(mysql_num_rows($rResult) == 0) getResult("TRUNCATE TABLE `" . MODULE_DB_PREFIX . "CurrentUsers`");
		$rResult = getResult("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Messages`");
		if(mysql_num_rows($rResult) == 0) getResult("TRUNCATE TABLE `" . MODULE_DB_PREFIX . "Messages`");
		//--- Update user's info and return info about all online users. ---//
		$sContents = refreshUsersInfo($sId);
		break;

	/**
	*	set user online status
	*/
	case 'setOnline':
		getResult("UPDATE `" . MODULE_DB_PREFIX . "CurrentUsers` SET `Online`='" . $sOnline . "', `When`='" . time() . "', `Status`='" . USER_STATUS_ONLINE . "' WHERE `ID`='" . $sId . "'");
		break;
		
	/**
	* Check for chat changes: new users, rooms, messages.
	* Note. This action is used in XML mode and by ADMIN.
	*/
	case 'update':
		$sFiles = "";
		$res = getResult("SELECT * FROM `" . MODULE_DB_PREFIX . "Messages` WHERE `Type`='file' AND `Recipient`='" . $sId . "'");
		while($aFile = mysql_fetch_assoc($res))
		{
			$sFileName = $aFile['ID'] . ".file";
			if(!file_exists($sFilesPath . $sFileName)) continue;
			$sFiles .= parseXml($aXmlTemplates['file'], $aFile['Sender'], $sFileName, $aFile['Message']);
		}
		getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Messages` WHERE `Type`='file' AND `Recipient`='" . $sId . "'");
		$sContents = makeGroup($sFiles, "files");
		
		//--- update user's info ---//
		$sContents .= refreshUsersInfo($sId, 'update');
		//--- check for new rooms ---//
		$sContents .= makeGroup(getRooms('update', $sId), "rooms");
		$sContents .= makeGroup(getRooms('updateUsers', $sId), "roomsUsers");

		//--- check for new messages ---//
		$iUpdateInterval = (int)getSettingValue($sModule, "updateInterval");
		$sMsgs = "";
		$sRooms = getValue("SELECT GROUP_CONCAT(DISTINCT `Room` SEPARATOR ',') FROM `" . MODULE_DB_PREFIX . "RoomsUsers` WHERE `User`='" . $sId . "' AND `Status`='" . ROOM_STATUS_NORMAL ."'");
		if(empty($sRooms)) $sRooms = "''";
		$sSql = "SELECT * FROM `" . MODULE_DB_PREFIX . "Messages` WHERE `Type`='text' AND `Sender`<>'" . $sId . "' AND ((`Room` IN (" . $sRooms . ") AND `Whisper`='" . FALSE_VAL . "') OR `Recipient`='" . $sId . "') AND `When`>='" . (time() - $iUpdateInterval) . "' ORDER BY `ID`";
		$res = getResult($sSql);
		while($aMsg = mysql_fetch_assoc($res))
		{
			$aStyle = unserialize($aMsg['Style']);
			$sMsgs .= parseXml($aXmlTemplates['message'], $aMsg['ID'], stripslashes($aMsg['Message']), $aMsg['Room'], $aMsg['Sender'], $aMsg['Recipient'], $aMsg['Whisper'], $aStyle['color'], $aStyle['bold'], $aStyle['underline'], $aStyle['italic'], $aStyle['size'], $aStyle['font'], $aStyle['smileset'], $aMsg['When']);
		}
		$sContents .= makeGroup($sMsgs, "messages");
		break;

	/**
	* Add message to database.
	*/
	case 'newMessage':
		if(empty($sSender)) break;
        $sWhisper = isset($_REQUEST['whisper']) ? $_REQUEST['whisper'] : FALSE_VAL;
		$sColor = $_REQUEST['color'] ? $_REQUEST['color'] : "0";
		$sBold = $_REQUEST['bold'] ? $_REQUEST['bold'] : FALSE_VAL;
		$sUnderline = $_REQUEST['underline'] ? $_REQUEST['underline'] : FALSE_VAL;
		$sItalic = $_REQUEST['italic'] ? $_REQUEST['italic'] : FALSE_VAL;
		$iSize = $_REQUEST['size'] ? $_REQUEST['size'] : 12;
		$sFont = $_REQUEST['font'] ? $_REQUEST['font'] : "Arial";
		$sStyle = serialize(array('color' => $sColor, 'bold' => $sBold, 'underline' => $sUnderline, 'italic' => $sItalic, 'smileset' => $sSmileset, 'size' => $iSize, 'font' => $sFont));
		getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Messages`(`Room`, `Sender`, `Recipient`, `Message`, `Whisper`, `Style`, `When`) VALUES('" . $iRoomId . "', '" . $sSender . "', '" . $sRcp . "', '" . $sMessage . "', '" . $sWhisper . "', '" . $sStyle . "', '" . time() . "')");
		break;

	case 'uploadFile':
		if(empty($sSender)) break;
		if(is_uploaded_file($_FILES['Filedata']['tmp_name']))
		{
			$sFilePath = $sFilesPath . $sSender . ".temp";
			@unlink($sFilePath);
			move_uploaded_file($_FILES['Filedata']['tmp_name'], $sFilePath);
			@chmod($sFilePath, 0644);
		}
		break;
		
	case 'initFile':
		$sFilePath = $sFilesPath . $sSender . ".temp";
		$sContents = parseXml($aXmlTemplates['result'], "msgErrorUpload", FAILED_VAL);
		if(empty($sSender) || !file_exists($sFilePath) || filesize($sFilePath) == 0) break;
		
		getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Messages`(`Sender`, `Recipient`, `Message`, `Type`, `When`) VALUES('" . $sSender . "', '" . $sRcp . "', '" . $sMessage . "', 'file', '" . time() . "')");
		$sFileName = getLastInsertId() . ".file";
		if(!@rename($sFilePath, $sFilesPath . $sFileName)) break;
		
		$sContents = parseXml($aXmlTemplates['result'], $sFileName, SUCCESS_VAL);
		break;
		
	case 'removeFile':
		$sId = str_replace(".file", "", $sId);
		removeFile($sId);
		break;

	case 'help':
		$sApp = isset($_REQUEST['app']) ? $_REQUEST['app'] : "user";
		$sContents = makeGroup("", "topics");
		$sFileName = $sModulesPath . $sModule . "/help/" . $sApp . ".xml";
		if(file_exists($sFileName))
		{
			$rHandle = @fopen($sFileName, "rt");
			$sContents = @fread($rHandle, filesize($sFileName)) ;
			fclose($rHandle);
		}
		break;
}
?>