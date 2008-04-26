<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

$sId = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
$sPassword = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
$sMsg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : "";
$sGot = isset($_REQUEST['got']) ? $_REQUEST['got'] : "";

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
	* Get chat's config.
	*/
	case 'config':
		$sFileName = $sModulesPath . $sModule . "/xml/config.xml";
		$rHandle = fopen($sFileName, "rt");
		$sContents = fread($rHandle, filesize($sFileName)) ;
		fclose($rHandle);
		break;

	/**
	* Authorize user.
	*/
	case 'userAuthorize':
		$sContents = parseXml($aXmlTemplates['result'], "msgErrorAuthorize", FAILED_VAL);
		if(loginUser($sId, $sPassword) != TRUE_VAL) break;

		$aUserInfo = getUserInfo($sId);
		$sContents = parseXml($aXmlTemplates['result'], "", SUCCESS_VAL, $aUserInfo['nick'], $aUserInfo['profile']);
		break;

	/**
	* Check for new messages.
	*/
	case 'update':
		$iUpdateInterval = (int)getSettingValue($sModule, "updateInterval");
		$iUpdateInterval += $iUpdateInterval / 2;
		
		$iMaxCount = (int)getSettingValue($sModule, "messagesCount");
		$iCount = getValue("SELECT COUNT(`ID`) FROM `" . MODULE_DB_PREFIX . "Messages`");
		if($iCount > $iMaxCount) getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Messages` ORDER BY `ID` LIMIT " . ($iCount - $iMaxCount));
		
		$sIn = empty($sGot) ? "1" : "`ID` NOT IN (" . $sGot . ")";
		$sUserFactor = empty($sId) ? "" : " AND `UserID`<>'" . $sId . "'";
		$sSql = "SELECT * FROM `" . MODULE_DB_PREFIX . "Messages` WHERE " . $sIn . $sUserFactor . " AND `When`>=(UNIX_TIMESTAMP()-" . $iUpdateInterval . ") ORDER BY `ID`";
		//break shouldn't be here

	case 'getMessages':
		$sContents = "";
		if(!isset($sSql)) $sSql = "SELECT * FROM `" . MODULE_DB_PREFIX . "Messages` ORDER BY `ID`";
		$rResult = getResult($sSql);
		while($aMsg = mysql_fetch_assoc($rResult))
		{
			$aUserInfo = empty($aMsg['UserID']) ? array('nick' => "", 'profile' => "") : getUserInfo($aMsg['UserID']);
			$sContents .= parseXml($aXmlTemplates['message'], $aMsg['ID'], stripslashes($aMsg['Msg']), $aUserInfo['nick'], $aUserInfo['profile']);
		}
		$sContents = makeGroup($sContents, "messages");
		break;

	/**
	* Add message to database.
	*/
	case 'newMessage':
		getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Messages`(`UserID`, `Msg`, `When`) VALUES('" . $sId . "', '" . addslashes($sMsg) . "', UNIX_TIMESTAMP())");
		break;
}
?>