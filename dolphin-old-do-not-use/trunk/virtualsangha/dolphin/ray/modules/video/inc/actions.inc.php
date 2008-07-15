<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

//user's ID
$sUserId = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
//user's nickname for authorization purposes
$sNick = isset($_REQUEST['nick']) ? $_REQUEST['nick'] : "";
//user's password
$sPassword = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";

//configuration parameter name
$sParamName = isset($_REQUEST['param']) ? $_REQUEST['param'] : "";
//configuration parameter value
$sParamValue = isset($_REQUEST['value']) ? $_REQUEST['value'] : "";

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
	 * Get video config
	 */
	case 'config':
		$sFileName = $sModulesPath . $sModule . "/xml/config.xml";
		$rHandle = fopen($sFileName, "rt");
		$sContents = fread($rHandle, filesize($sFileName)) ;
		fclose($rHandle);

		$sContents = str_replace("#serverUrl#", getRMSUrl($sServerApp), $sContents);
		$sContents = str_replace("#serverHttpUrl#", getRMSUrl($sServerApp, true), $sContents);
		break;

	/**
	* Authorize user
	*/
	case 'userAuthorize':
	if(loginUser($sUserId, $sPassword) == TRUE_VAL)
	{
		$aUser = getUserInfo($sUserId);
		$sContents = parseXml($aXmlTemplates['result'], TRUE_VAL, $aUser['nick'], $aUser['profile']);
	}
	else $sContents = parseXml($aXmlTemplates['result'], FALSE_VAL);
	break;

	/**
	* Authorize admin
	*/
	case 'adminAuthorize':
		$sContents .= parseXml($aXmlTemplates['result'], loginAdmin($sNick, $sPassword));
		break;

	/**
	* Search user by ID or by Nick.
	*/
	case 'searchUser':
		$sId = searchUser($sParamValue, $sParamName);

		//--- if such user exists, than print his info ---//
		if(!empty($sId)) $sContents = parseXml($aXmlTemplates['result'], $sId, SUCCESS_VAL);
		else             $sContents = parseXml($aXmlTemplates['result'], "User not found", FAILED_VAL);
		break;

	case 'stat':
		$iApproved = isset($_REQUEST['approved']) ? $_REQUEST['approved'] : 0;
		$iPending = isset($_REQUEST['pending']) ? $_REQUEST['pending'] : 0;
		getResult("UPDATE `" . MODULE_DB_PREFIX . "Stats` SET `Approved`='" . $iApproved . "', `Pending`='" . $iPending . "' WHERE `User`='' LIMIT 1");
		break;
		
	case 'userStat':
		$aApproved = isset($_REQUEST['approved']) ? explode(",", $_REQUEST['approved']) : array();
		$aPending = isset($_REQUEST['pending']) ? explode(",", $_REQUEST['pending']) : array();
		$aUsers = empty($sUserId) ? array() : explode(",", $sUserId);
		for($i=0; $i<count($aUsers); $i++)
		{
			if(empty($aUsers[$i])) continue;
			getResult("REPLACE `" . MODULE_DB_PREFIX . "Stats` SET `User`='" . $aUsers[$i] . "', `Approved`='" . $aApproved[$i] . "', `Pending`='" . $aPending[$i] . "'");
		}
		break;
}
?>