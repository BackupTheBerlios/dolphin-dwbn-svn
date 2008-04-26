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
//user's password
$sPassword = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";

//skin name
$sSkin = isset($_REQUEST['skin']) ? $_REQUEST['skin'] : "";
//language name
$sLanguage = isset($_REQUEST['language']) ? $_REQUEST['language'] : "english";

//board's ID
$iBoardId = isset($_REQUEST['boardId']) ? (int)$_REQUEST['boardId'] : 0;
//board's title
$sTitle = isset($_REQUEST['title']) ? $_REQUEST['title'] : "Board's title";


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
	 * Get config
	 */
	case 'config':
			$sFileName = $sModulesPath . $sModule . "/xml/config.xml";
			$rHandle = fopen($sFileName, "rt");
			$sContents = fread($rHandle, filesize($sFileName)) ;
			fclose($rHandle);

			$sContents = str_replace("#siteName#", getSiteName(), $sContents);
			$sContents = str_replace("#useServer#", useServer() ? TRUE_VAL : FALSE_VAL, $sContents);
			$sContents = str_replace("#serverUrl#", getRMSUrl($sServerApp), $sContents);
			break;

	/**
	 * Authorize user
	 */
	case 'userAuthorize':
			$sResult = loginUser($sUserId, $sPassword);
			$sContents = parseXml($aXmlTemplates['result'], $sResult);
			if($sResult == TRUE_VAL)
			{
				$rResult = getResult("SELECT `ID` FROM `" . MODULE_DB_PREFIX . "Boards` WHERE `UserID`='" . $sUserId . "'");
				for($i=0; $i<mysql_num_rows($rResult); $i++)
				{
					$aBoard = mysql_fetch_assoc($rResult);
					@unlink($sFilesPath . $aBoard['ID'] . $sFileExtension);
				}
				getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Boards` WHERE `UserID`='" . $sUserId . "'");
			}
			break;

	/**
	 * Get users' data
	 */
	case 'userInfo':
			$aUserInfo = getUserInfo($sUserId);
			$sContents = parseXml($aXmlTemplates['user'], $aUserInfo['nick'], $aUserInfo['profile']);
			break;

		

	//-------------------------------------//
	//--- Actions for LITE Version Only ---//
	//-------------------------------------//
	/**
	 * Create new Board
	 * param - boardId
	 * param - title
	 */
	case 'create':
		getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Boards`(`UserID`, `Title`, `Track`) VALUES('" . $sUserId . "', '" . $sTitle . "', '" . time() . "')");
		$iBoardId = (int)getLastInsertId();
		if(!empty($iBoardId))	$sContents = parseXml($aXmlTemplates['result'], $iBoardId, SUCCESS_VAL);
		else 					$sContents = parseXml($aXmlTemplates['result'], 'msgErrorDB', FAILED_VAL);
		break;
	
	/**
	 * Destroy specified Board.
	 * param - boardId
	 */
	case 'destroy':
		getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Boards` WHERE `ID`='" . $iBoardId . "'");
		@unlink($sFilesPath . $iBoardId . $sFileExtension);
		break;

	/**
	 * Update board's information.
	 * param - id - user's ID.
	 * param - current - currently viewed boards.
	 * param - all  - all current boards.
	 */
	case 'update':
		//--- Update boards where current user is author ---// 
		$sContents = "";
		$iUpdateInterval = getSettingValue($sModule, 'updateInterval');
		$iCurrentTime = time();
		getResult("UPDATE `" . MODULE_DB_PREFIX . "Boards` SET `Track`='" . $iCurrentTime . "' WHERE `UserID`='" . $sUserId . "'");
		getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Boards` WHERE `Track`<'" . ($iCurrentTime-$iUpdateInterval) . "'");
			
		//--- Update boards information where current user is viewer ---//
		$aCurrent = isset($_REQUEST['current']) && $_REQUEST['current'] != '' ? explode(',', $_REQUEST['current']) : array();
		$aAll = isset($_REQUEST['all']) && $_REQUEST['all'] != '' ? explode(',', $_REQUEST['all']) : array();
		$iAllCount = count($aAll);
		
		$rResult = getResult("SELECT * FROM `" . MODULE_DB_PREFIX . "Boards`");
		$aBoards = array();
		$iNumRows = mysql_num_rows($rResult);
		for($i=0; $i<$iNumRows; $i++)
		{
			$aBoard = mysql_fetch_assoc($rResult);
			$aBoards[$aBoard['ID']] = $aBoard;
		}
		if($iNumRows == 0) getResult("TRUNCATE `" . MODULE_DB_PREFIX . "Boards`");
		//print_r($aBoards);
		
		clearstatcache();
		foreach($aBoards as $sBoardId => $aBoard)
		{
			if($sUserId == $aBoard['UserID']) continue;
			if(!in_array($sBoardId, $aAll))
			{
				$aUserInfo = getUserInfo($aBoard['UserID']);
				$sImage = $sFilesUrl . $sBoardId . $sFileExtension;
				$sContents .= parseXml($aXmlTemplates['board'], $sBoardId, BOARD_STATUS_NEW, $aBoard['Title'], $aUserInfo['nick'], $aUserInfo['profile'], $sImage);
			}
			else
			{
				$iModifiedTime = filemtime($sFilesPath . $sBoardId . $sFileExtension); 
				if($iModifiedTime >= ($iCurrentTime - $iUpdateInterval))
					$sContents .= parseXml($aXmlTemplates['board'], $sBoardId, BOARD_STATUS_UPDATED);
			}
		}
		for($i=0; $i<$iAllCount; $i++)
			if(!isset($aBoards[$aAll[$i]]))
			{
				$sBoardId = $aAll[$i];
				@unlink($sFilesPath . $sBoardId . $sFileExtension);
				$sContents .= parseXml($aXmlTemplates['board'], $sBoardId, BOARD_STATUS_CLOSED);
			}
			
		$sContents = makeGroup($sContents, 'boards');
		break;

	/**
	 * Transmit new Scene file from specified Board.
	 * param - boardId
	 * param - width
	 * param - height
	 * param - data
	 */
	case 'transmit':
		//--- Prepare data ---//
		$iWidth = isset($_REQUEST['width']) ? (int)$_REQUEST['width'] : 0;
		$iHeight = isset($_REQUEST['height']) ? (int)$_REQUEST['height'] : 0;
		$sData = isset($_REQUEST['data']) ? $_REQUEST['data'] : "";
		$iQuality = 100;
		$aImageData = explode(',', $sData);
		$iLength = count($aImageData);
		for($i=0; $i<$iLength; $i++)
			$aImageData[$i] = base_convert($aImageData[$i], 36, 10);
		if($iLength != $iWidth * $iHeight || !function_exists("imagecreatetruecolor"))
		{
			$sContents = parseXml($aXmlTemplates['result'], 'msgErrorGD', FAILED_VAL);
			break;
		}

		//--- Create Image Resource ---//
		$rImage = @imagecreatetruecolor($iWidth, $iHeight);
		for ($i = 0, $y = 0; $y < $iHeight; $y++ )
			for ( $x = 0; $x < $iWidth; $x++, $i++)
				@imagesetpixel ($rImage, $x, $y, $aImageData[$i]);

		//--- Save image file ---//
		$sFileName = $sFilesPath . $iBoardId . $sFileExtension;
		if(!@imagejpeg($rImage, $sFileName, $iQuality))
			$sContents = parseXml($aXmlTemplates['result'], 'msgErrorFile', FAILED_VAL);
		else
			$sContents = parseXml($aXmlTemplates['result'], '', SUCCESS_VAL);
		break;
}
?>