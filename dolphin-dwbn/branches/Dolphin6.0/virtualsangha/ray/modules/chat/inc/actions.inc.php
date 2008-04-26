<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

//user's ID
$sId = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
//moderator's ID
$sModeratorId = isset($_REQUEST['moderator']) ? $_REQUEST['moderator'] : "";
//user's Nick
$sNick = isset($_REQUEST['nick']) ? $_REQUEST['nick'] : "";
//user's password
$sPassword = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
//user's type
$sType = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";

//user's message
$sMsg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : "";
//user's smileset
$sSmileset = isset($_REQUEST['smileset']) ? $_REQUEST['smileset'] : "";

//room parameter
$sRoom = isset($_REQUEST['room']) ? $_REQUEST['room'] : "";
//room ID parameter
$iRoomId = isset($_REQUEST['roomId']) ? (int)$_REQUEST['roomId'] : 0;

//configuration parameter name
$sParamName = isset($_REQUEST['param']) ? $_REQUEST['param'] : "";
//configuration parameter value
$sParamValue = isset($_REQUEST['value']) ? $_REQUEST['value'] : "";

//skin name
$sSkin = isset($_REQUEST['skin']) ? $_REQUEST['skin'] : "";
//language name
$sLanguage = isset($_REQUEST['language']) ? $_REQUEST['language'] : "english";

//shows that current user is admin
//true - admin, false - usual user
$bAdmin = isset($_REQUEST['admin'])? true: false;

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
     * Get chat's config.
     */
    case 'config':
                $sFileName = $sModulesPath . $sModule . "/xml/config.xml";
                $rHandle = fopen($sFileName, "rt");
                $sContents = fread($rHandle, filesize($sFileName)) ;
                fclose($rHandle);

                $sContents = str_replace("#siteName#", getSiteName(), $sContents);
				$sContents = str_replace("#imModule#", $sImModule, $sContents);
                $sContents = str_replace("#soundsUrl#", $sSoundsUrl, $sContents);
                $sContents = str_replace("#smilesetsUrl#", $sSmilesetsUrl, $sContents);
                $sContents = str_replace("#useServer#", useServer() ? TRUE_VAL : FALSE_VAL, $sContents);
                $sContents = str_replace("#serverUrl#", getRMSUrl($sServerApp), $sContents);
                break;

	/**
	* Authorize user.
	*/
	case 'userAuthorize':
		if(loginUser($sId, $sPassword) == TRUE_VAL && doBan("check", $sId) != TRUE_VAL)
		{
			$sResult = getValue("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Profiles` WHERE `ID`='" . $sId . "'");
			if(empty($sResult)) getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Profiles` SET `ID`='" . $sId . "', `Smileset`='" . $sDefSmileset . "'");
			$sContents = parseXml($aXmlTemplates['result'], TRUE_VAL);
		}
		else $sContents = parseXml($aXmlTemplates['result'], FALSE_VAL);
		break;

	/**
	* Get user info.
	*/
	case 'userInfo':
		$aUserInfo = getUserInfo($sId);
		$sType = getUserType($sId);
		$sContents = parseXml($aXmlTemplates['user'], $sId, "new", $aUserInfo['nick'], $aUserInfo['sex'], $aUserInfo['age'], $aUserInfo['desc'], $aUserInfo['photo'], $aUserInfo['profile'], $sType, 0);
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
		$sContents = makeGroup("", "smilesets");
		$aSmilesets = array();
		if($rDirHandle = opendir($sSmilesetsPath))
			while(false !== ($sDir = readdir($rDirHandle)))
				if($sDir != "." && $sDir != ".." && is_dir($sSmilesetsPath . $sDir) && file_exists($sSmilesetsPath . $sDir . "/config.xml"))
					$aSmilesets[] = $sDir;
		closedir($rDirHandle);
		if(count($aSmilesets) == 0) break;
			
		if(!in_array($sDefSmileset, $aSmilesets)) $sDefSmileset = $aSmilesets[0];
		$sUserSmileset = getValue("SELECT `Smileset` FROM `" . MODULE_DB_PREFIX . "Profiles` WHERE `ID`='" . $sId . "'");
		if(empty($sUserSmileset)) $sUserSmileset = $sDefSmileset;
		
		$sContents = "";
		for($i=0; $i<count($aSmilesets); $i++)
			$sContents .= parseXml($aXmlTemplates['smileset'], $aSmilesets[$i], $sSmilesetsUrl . $aSmilesets[$i] . "/", ($aSmilesets[$i] == $sUserSmileset) ? TRUE_VAL : FALSE_VAL);
		$sContents = makeGroup($sContents, "smilesets");
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
                $sRooms = getRooms();
                $sContents .= makeGroup($sRooms, "rooms");
                break;

        /**
         * Creats new room.
         * Note. This action is used in both modes and by admin.
         */
        case 'createRoom':
				$iRoomId = doRoom('insert', $sId, 0, $sRoom);
				if(empty($iRoomId))	$sContents = parseXml($aXmlTemplates['result'], "msgErrorCreatingRoom", FAILED_VAL);
				else 				$sContents = parseXml($aXmlTemplates['result'], $iRoomId, SUCCESS_VAL);
                break;

        /**
         * Delete room from database.
         * Note. This action is used in both modes and by admin.
         */
        case 'deleteRoom':
                doRoom('delete', 0, $iRoomId, "");
                $sContents .= parseXml($aXmlTemplates['result'], TRUE_VAL);
                break;

        /**
         * Chat user changes room.
         * Note. This action is used in both modes.
         */
        case 'changeRoom':
                getResult("UPDATE `" . MODULE_DB_PREFIX . "CurrentUsers` SET `RoomID`='" . $iRoomId . "', `When`=UNIX_TIMESTAMP(), `Status`='room' WHERE `ID`='" . $sId . "'");
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
                if(mysql_num_rows($rResult) == 0) getResult("TRUNCATE TABLE `" . MODULE_DB_PREFIX . "Messages`");

                //--- Insert user, if not exists, or update user otherwise. ---//
                if(!empty($sId))
                {
                        $aUserInfo = getUserInfo($sId);
                        $iRoomId = (int)getValue("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Rooms` LIMIT 1");
                        getResult("REPLACE `" . MODULE_DB_PREFIX . "CurrentUsers` SET `ID`='" . $sId . "', `Nick`='" . $aUserInfo['nick'] . "', `RoomID`='" . $iRoomId . "', `Sex`='" . $aUserInfo['sex'] . "', `Age`='" . $aUserInfo['age'] . "', `Desc`='" . addslashes($aUserInfo['desc']) . "', `Photo`='" . $aUserInfo['photo'] . "', `Profile`='" . $aUserInfo['profile'] . "', `When` = UNIX_TIMESTAMP(), `Status` = '" . USER_STATUS_NEW . "'");
                }

                //--- Update user's info and return info about all online users. ---//
                $sContents = refreshUsersInfo($sId);
                break;

        /**
         * Check for chat changes: new users, rooms, messages.
         * Note. This action is used in XML mode and by ADMIN.
         */
        case 'update':
                //--- check for new rooms ---//
                $sContents .= makeGroup(getRooms('upd', $sId), "rooms");

                //--- update user's info ---//
                $sContents .= refreshUsersInfo($sId, 'upd');

                //--- check for new messages ---//
                $iUpdateInterval = (int)getSettingValue($sModule, "updateInterval");
                $sMsgs = "";
                $res = getResult("SELECT cm.`ID` AS `ID`, cm.`Msg` AS `Msg`, cm.`Style` AS `Style`, ccu.`ID` AS `userID` FROM `" . MODULE_DB_PREFIX . "Messages` AS cm, `" . MODULE_DB_PREFIX . "CurrentUsers` AS ccu WHERE cm.`UserID`=ccu.`ID` AND cm.`UserID`<>'" . $sId . "' AND cm.`When`>=(UNIX_TIMESTAMP()-" . $iUpdateInterval . ") ORDER BY cm.`ID`");
                while($aMsg = mysql_fetch_assoc($res))
                {
                        $aStyle = unserialize($aMsg['Style']);
                        $sMsgs .= parseXml($aXmlTemplates['message'], $aMsg['ID'], stripslashes($aMsg['Msg']), $aMsg['userID'], $aStyle['color'], $aStyle['bold'], $aStyle['underline'], $aStyle['italic'], $aStyle['smileset']);
                }
                $sContents .= makeGroup($sMsgs, "msgs");
                break;

        /**
         * Add message to database.
         */
        case 'newMessage':
        $c = $_REQUEST['color'] ? $_REQUEST['color'] : "0";
        $b = $_REQUEST['bold'] ? $_REQUEST['bold'] : "false";
        $u = $_REQUEST['underline'] ? $_REQUEST['underline'] : "false";
        $i = $_REQUEST['italic'] ? $_REQUEST['italic'] : "false";
                $sStyle = serialize(array('color' => $c, 'bold' => $b, 'underline' => $u, 'italic' => $i, 'smileset' => $sSmileset));
                getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Messages`(`UserID`, `Msg`, `Style`, `When`) VALUES('" . $sId . "', '" . addslashes($sMsg) . "', '" . $sStyle . "', UNIX_TIMESTAMP())");
                break;




        /**
         * ===> Next actions are needed for ADMIN only. <===
         * Authorization.
         */
        case 'adminAuthorize':

                $sContents .= parseXml($aXmlTemplates['result'], loginAdmin($sNick, $sPassword));

                //--- for Lite version only ---//
                getResult("REPLACE `" . MODULE_DB_PREFIX . "CurrentUsers` SET `ID`='0', `Nick`='admin', `When` = UNIX_TIMESTAMP(), `Status` = '" . USER_STATUS_OLD . "'");
                break;

        /**
         * Sets parameters.
         * @param param - parameter name.
         * @param value - parameter value.
         */
        case 'setParameter':
                if(loginAdmin($sNick, $sPassword) == TRUE_VAL)
                {
                        setSettingValue($sModule, $sParamName, $sParamValue);
                        $sContents .= parseXml($aXmlTemplates['result'], TRUE_VAL);
                }
                else $sContents .= parseXml($aXmlTemplates['result'], FALSE_VAL);
                break;

        /**
         * Search user by ID or by Nick.
         */
        case 'searchUser':
                $sId = searchUser($sParamValue, $sParamName);

                //--- if such user exists, than print his info ---//
                if(!empty($sId))
                {
                        $sContents .= parseXml($aXmlTemplates['result'], TRUE_VAL);
                        $aUserInfo = getUserInfo($sId);
                        $sType = getValue("SELECT `Type` FROM `" . MODULE_DB_PREFIX . "Profiles` WHERE `ID`='" . $sId . "'");
                        if(empty($sType))
                        {
                                getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Profiles` SET `ID`='" . $sId . "', `Smileset`='" . $sDefSmileset . "'");
                                $sType = getValue("SELECT `Type` FROM `" . MODULE_DB_PREFIX . "Profiles` WHERE `ID`='" . $sId . "'");
                        }
                        $sContents .= parseXml($aXmlTemplates['user'], $sId, $aUserInfo['nick'], $aUserInfo['sex'], $aUserInfo['age'], $aUserInfo['desc'], $aUserInfo['photo'], $aUserInfo['profile'], $sType,  doBan('check', $sId));
                }
                else $sContents .= parseXml($aXmlTemplates['result'], FALSE_VAL);
                break;

        /**
         * Ban/unban user by specified ID.
         */
        case 'banUser':
                if( ($bAdmin && loginAdmin($sNick, $sPassword)) || (!$bAdmin && loginUser($sModeratorId, $sPassword) && getUserType($sModeratorId) == CHAT_TYPE_MODER) )
                {
                        doBan($sParamValue == TRUE_VAL ? 'ban' : 'unban', $sId);
                        $sContents = parseXml($aXmlTemplates['result'], TRUE_VAL);
                }
				else $sContents = parseXml($aXmlTemplates['result'], FALSE_VAL);
                break;

        /**
         * Changes user's type.
         */
        case 'changeType':
                if(loginAdmin($sNick, $sPassword))
                {
                        getResult("UPDATE `" . MODULE_DB_PREFIX . "Profiles` SET `Type`='" . $sType . "' WHERE `ID`='" . $sId . "'");
                        //--- For XML version only ---//
                        getResult("UPDATE `" . MODULE_DB_PREFIX . "CurrentUsers` SET `Status`='" . USER_STATUS_TYPE . "', `When`=UNIX_TIMESTAMP() WHERE `ID`='" . $sId . "'");

                        $sContents .= parseXml($aXmlTemplates['result'], TRUE_VAL);
                }
                else $sContents .= parseXml($aXmlTemplates['result'], FALSE_VAL);
                break;


        case 'kickUser':
                if( ($bAdmin && loginAdmin($sNick, $sPassword)) || (!$bAdmin && loginUser($sModeratorId, $sPassword) && getUserType($sModeratorId) == CHAT_TYPE_MODER) )
                {
                        getResult("UPDATE `" . MODULE_DB_PREFIX . "CurrentUsers` SET `Status`='" . USER_STATUS_KICK . "', `When`=UNIX_TIMESTAMP() WHERE `ID`='" . $sId . "'");
                        $sContents .= parseXml($aXmlTemplates['result'], TRUE_VAL);
                }
                else $sContents .= parseXml($aXmlTemplates['result'], FALSE_VAL);
                break;
}
?>