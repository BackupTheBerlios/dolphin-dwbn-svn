<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

//sender's ID
$sSndId = isset($_REQUEST['sndId']) ? $_REQUEST['sndId'] : "";
//sender's Nick
$sSndNick = isset($_REQUEST['nick']) ? $_REQUEST['nick'] : "";
//sender's password
$sSndPassword = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
//name of smileset
$sSndSmileset = isset($_REQUEST['smileset']) ? $_REQUEST['smileset'] : "";

//recipient's ID
$sRspId = isset($_REQUEST['rspId']) ? $_REQUEST['rspId'] : "";

//user's message
$sMsg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : "";
//sex parameter
$sSex = isset($_REQUEST['sex']) ? $_REQUEST['sex'] : "Male";
//user's file name
$sFile = isset($_REQUEST['file']) ? $_REQUEST['file'] : "";

//skin name
$sSkin = isset($_REQUEST['skin']) ? $_REQUEST['skin'] : "";
//language name
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
	* Get IM config
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
		$sContents = str_replace("#soundsUrl#", $sSoundsUrl, $sContents);
		$sContents = str_replace("#smilesetsUrl#", $sSmilesetsUrl, $sContents);
		$sContents = str_replace("#useServer#", useServer() ? TRUE_VAL : FALSE_VAL, $sContents);
		$sContents = str_replace("#serverUrl#", getRMSUrl($sServerApp), $sContents);
		break;

        /**
         * IM ACTIONS.
         * Authorize sender for IM dialog.
         */
        case 'senderAuthorize':
                if(loginUser($sSndId, $sSndPassword) == TRUE_VAL)
                {
                        $sContents .= parseXml($aXmlTemplates['result'], TRUE_VAL);

                        //--- return sender's information ---//
                        $aUserInfo = getUserInfo($sSndId);
                        $sContents .= parseXml($aXmlTemplates['user'], $sSndId, $aUserInfo['nick']);
                }
                else $sContents .= parseXml($aXmlTemplates['result'], FALSE_VAL);
                break;

        /**
         * Authorize recipient for IM dialog.
         */
        case 'recipientAuthorize':
                $sSearchResult = searchUser($sRspId);
                if(!empty($sSearchResult))
                {
                        $sContents = parseXml($aXmlTemplates['result'], TRUE_VAL);

                        //--- return recipients's information ---//
                        $aUserInfo = getUserInfo($sRspId);
                        $sContents .= parseXml($aXmlTemplates['user'], $sRspId, $aUserInfo['nick'], $aUserInfo['sex'], $aUserInfo['age'], $aUserInfo['photo'], $aUserInfo['profile']);
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
                        $sContents = fread($rHandle, filesize($sFileName)) ;
                        fclose($rHandle);
                }
                else
                {
                        $sContents = "<items></items>";
                }
                break;


        /**
         * gets smilesets
         */
        case 'getSmilesets':
                $sUserSmileset = getValue("SELECT `Smileset` FROM `" . MODULE_DB_PREFIX . "Profiles` WHERE `ID`='" . $sSndId . "'");
                $sUserSmileset = !empty($sUserSmileset) ? $sUserSmileset : $sDefSmileset;
                if ($rDirHandle = opendir($sSmilesetsPath))
                        while (false !== ($sDir = readdir($rDirHandle)))
                                if($sDir != "." && $sDir != ".." && is_dir($sSmilesetsPath . $sDir) && file_exists($sSmilesetsPath . $sDir . "/config.xml"))
                                        $sSmilesets .= parseXml($aXmlTemplates['smileset'], $sDir, $sSmilesetsUrl . $sDir . "/", ($sDir == $sUserSmileset) ? TRUE_VAL : FALSE_VAL);
                $sContents .= makeGroup($sSmilesets, "smilesets");
                closedir($rDirHandle);
                break;

        /**
         * Sets default smileset.
         */
        case 'setSmileset':
                getResult("UPDATE `" . MODULE_DB_PREFIX . "Profiles` SET `Smileset`='" . $sSndSmileset . "' WHERE `ID`='" . $sSndId . "'");
                break;

        /**
         * Add pending message and return result of this operation
         */
        case 'addPend':
                $sContents .= parseXml($aXmlTemplates['result'], addPend( $sSndId, $sRspId, $sMsg ) ? TRUE_VAL : FALSE_VAL);
                break;

	/**
	* Pending messages are deleted, after obtaining.
	*/
	case 'getPend':
		//--- get pending messages ---//
		$sMsgs = "";
		$sQuery = "SELECT `Msg` FROM `" . MODULE_DB_PREFIX . "Pendings` WHERE `SenderID` = '" . $sSndId . "' AND `RecipientID` = '" . $sRspId . "' ORDER BY `ID`";
		$rResult = getResult($sQuery);
		while($aMsg = mysql_fetch_assoc($rResult)) $sMsgs .= parseXml($aXmlTemplates['message'], $aMsg['Msg']);
		$sContents .= makeGroup($sMsgs, "msgs");
		//"break" shouldn't be here

	/**
	* Deleting pending messages.
	* Used by IM_invite also.
	*/
	case 'deletePend':
		getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Pendings` WHERE `SenderID`='" . $sSndId . "' AND `RecipientID`='" . $sRspId . "'");
		break;
		
	/**
	* Check pending
	*/
	case 'checkPend':
		$iCount = getValue("SELECT COUNT(`ID`) FROM `" . MODULE_DB_PREFIX . "Pendings` WHERE `SenderID`='" . $sSndId . "' AND `RecipientID`='" . $sRspId . "'");
		$sContents = parseXml($aXmlTemplates['result'], $iCount > 0 ? TRUE_VAL : FALSE_VAL);
		break;

	/**
	* Upload user's file
	*/
	case 'uploadFile':
		//--- Check for old(non-deleted) files.
		if($rDirHandle = opendir($sFilesPath))
		{
			while(($sFileName = readdir($rDirHandle)) !== false)
				if($sFileName != ".." && $sFileName != ".")
				{
					clearstatcache();
					if(filemtime($sFileName) <= time() - 86400) unlink($sFilesPath . $sFileName);
				}
			closedir($rDirHandle);
		}

		if(is_uploaded_file($_FILES['Filedata']['tmp_name']))
			move_uploaded_file($_FILES['Filedata']['tmp_name'], $sFilesPath . $sFile);
		break;

                /**
                 * Delete user's file
                 */
                case 'deleteFile':
                        unlink($sFilesPath . $sFile);
                        break;


        /**
         * >>> ACTIONS FOR INVITE <<<
         * Check for pending messages for given user
         */
        case 'updateInvite':
                $sQuery = "SELECT `SenderID`, `Msg` FROM `" . MODULE_DB_PREFIX ."Pendings` WHERE `RecipientID`='" . $sRspId . "' ORDER BY `ID` LIMIT 1";
                $aMsg = getArray($sQuery);
                //--- if there is a message return it and some information about it's author ---//
                if(!empty($aMsg['SenderID']))
                {
                        $aUserInfo = getUserInfo($aMsg['SenderID']);
                        $sContents .= parseXml($aXmlTemplates['result'], TRUE_VAL, $aMsg['Msg'], $aMsg['SenderID'], $aUserInfo['nick'], $aUserInfo['photo'], $aUserInfo['profile']);
                }
                else $sContents .= parseXml($aXmlTemplates['result'], FALSE_VAL);
                break;


        /**
         * >>> ACTIONS LITE VERSION ONLY <<<
         * Refreshs IM users' states and insert current user's connection in connections table.
                 * Is used during authorize process.
         */
        case 'refreshStatus':
                                //--- checks whether user is online and if not then insert new contact for the user. ---//
                                $iContactId = (int)getValue("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Contacts` WHERE `SenderID`='" . $sSndId . "' AND `RecipientID`='" . $sRspId . "' LIMIT 1");
                                if(empty($iContactId)) getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Contacts`(`SenderID`, `RecipientID`, `When`) VALUES ('" . $sSndId . "', '" . $sRspId . "', UNIX_TIMESTAMP())");

                                refreshIMUsers($sSndId, $sRspId);
                break;

                /**
         * Checking IM messages and user online status
         */
        case 'recipientUpdate':
                //--- check for IM changes ---//
                refreshIMUsers($sSndId, $sRspId);
                //--- checking online status of Recipient ---//
                $iId = getValue("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Contacts` WHERE `SenderID`='" . $sRspId . "' AND `RecipientID`='" . $sSndId . "' LIMIT 1");
                $sContents .= parseXml($aXmlTemplates['result'], (int)$iId > 0 ? TRUE_VAL : FALSE_VAL);

                //--- checking for new messages ---//
                $sMsgs = "";
                $sQuery = "SELECT * FROM `" . MODULE_DB_PREFIX . "Contacts` AS imc, `" . MODULE_DB_PREFIX . "Messages` AS imm WHERE imc.`ID`=imm.`ContactID` AND imc.`SenderID`='" . $sRspId . "' AND imc.`RecipientID`='" . $sSndId . "' ORDER BY imm.`ID`";
                $res = getResult($sQuery);
                while($aMsg = mysql_fetch_assoc($res))
                {
                        $aStyle = unserialize($aMsg['Style']);
                        $sMsgs .= parseXml($aXmlTemplates['message'], $aMsg['Msg'], $aStyle['color'], $aStyle['bold'], $aStyle['underline'], $aStyle['italic'], $aStyle['smileset']);
                }
                $sContents .= makeGroup($sMsgs, "msgs");

                //--- delete new messages ---//
                $sQuery = "DELETE FROM `" . MODULE_DB_PREFIX . "Messages` WHERE `ContactID`='" . $iId . "'";
                getResult($sQuery);
                break;

        /**
         * New message for IM
         */
        case 'newMessage':
                //--- check online status of the recipient ---//
                                $iConnectRid = (int)getValue("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Contacts` WHERE `SenderID`='" . $sRspId . "' AND `RecipientID`='" . $sSndId . "' LIMIT 1");
                if(!empty($iConnectRid))
                {
                        //--- if he's online, than insert new message in messages table ---//
                        $c = $_REQUEST['color'] ? $_REQUEST['color'] : "0";
                        $b = $_REQUEST['bold'] ? $_REQUEST['bold'] : "false";
                        $u = $_REQUEST['underline'] ? $_REQUEST['underline'] : "false";
                        $i = $_REQUEST['italic'] ? $_REQUEST['italic'] : "false";
                        $sStyle = serialize(array('color' => $c, 'bold' => $b, 'underline' => $u, 'italic' => $i, 'smileset' => $sSndSmileset));
                                                $iContactSid = getValue("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Contacts` WHERE `SenderID`='" . $sSndId . "' AND `RecipientID`='" . $sRspId . "' LIMIT 1");
                                                if(!empty($iContactSid))
                                                        $rResult = getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Messages` (`ContactID`, `Msg`, `Style`, `When`) VALUES ('" . $iContactSid . "', '" . $sMsg . "', '" . $sStyle . "', UNIX_TIMESTAMP())");
                }
                else $rResult = addPend( $sSndId, $sRspId, $sMsg);
                $sContents .= parseXml($aXmlTemplates['result'], $rResult ? TRUE_VAL : FALSE_VAL);
                break;
}
?>