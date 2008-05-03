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
//user's Nick
$sNick = isset($_REQUEST['nick']) ? $_REQUEST['nick'] : "";
//user's password
$sPass = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
//user's status
$sStatus = isset($_REQUEST['status']) ? $_REQUEST['status'] : "";

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
        * Gets configuration for Ray Presence
        */
        case "config":
                $sFileName = $sModulesPath . $sModule . "/xml/config.xml";
                $rHandle = fopen($sFileName, "rt");
                $sContents = fread($rHandle, filesize($sFileName));
                fclose($rHandle);

                //--- Process Info ---//
                $sContents = str_replace("#siteName#", getSiteName(), $sContents);
				$sContents = str_replace("#imModule#", $sImModule, $sContents);
                break;

        /**
        * Makes login process for user by specified login and password
        * @param login - user's login name.
        * @param password - user's password.
        */
        case "userAuthorize":
                if(loginUser($sId, $sPass) == TRUE_VAL)
                {
                        updateOnline($sId, USER_STATUS_ONLINE);
                        $sContents = parseXml($aXmlTemplates['result'], TRUE_VAL, USER_STATUS_ONLINE);
                }
                else $sContents = parseXml($aXmlTemplates['result'], FALSE_VAL);
                break;

        /**
         * Gets Friend's information.
         * @param id - user's ID, whose friends' info need to be got.
         */
        case "getFriends":
                $aFriendIds = getFriends($sId);
                $aOnlineFriends = getOnline($aFriendIds);
                foreach($aFriendIds as $sFriendId)
                {
                        $aUserInfo = getUserInfo($sFriendId);
                        $sOnline = in_array($iFriendId, $aOnlineFriends) ? TRUE_VAL : FALSE_VAL;
                        $sContents .= parseXml($aXmlTemplates['user'], $sFriendId, $aUserInfo['nick'], $aUserInfo['sex'], $aUserInfo['age'], $sOnline, $aUserInfo['photo'], $aUserInfo['profile']);
                }
                $sContents = makeGroup($sContents, "users");
                break;

        /**
         * Update friend's information.
         * @param id - user's ID, whose friends' info need to be updated.
         */
        case "updateFriends":
                $aFriendIds = getFriends($sId);
                if(count($aFriendIds) == 0)$sContents = "";
                else
                {
                    $aOnlineFriends = getOnline($aFriendIds);
                    for($i=0; $i<count($aOnlineFriends); $i++)
                        $sContents .= parseXml($aXmlTemplates['user'], $aOnlineFriends[$i]);
                }
                $sContents = makeGroup($sContents, "users");
                break;

        /**
         * Gets online user's information
         */
        case "getOnlineUsers":
                $aOnlineUsers = getOnline();
                for($i=0; $i<count($aOnlineUsers); $i++)
                {
                        $aUserInfo = getUserInfo($aOnlineUsers[$i]);
                        $sContents .= parseXml($aXmlTemplates['user'], $aOnlineUsers[$i], $aUserInfo['nick'], $aUserInfo['sex'], $aUserInfo['age'], TRUE_VAL, $aUserInfo['photo'], $aUserInfo['profile']);
                }
                $sContents = makeGroup($sContents, "users");
                break;

        /**
         * Update online user's information.
         * @param online - currently online users, whose info was got earlier.
         */
        case "updateOnlineUsers":
                $aCurDbOnline = getOnline();
                $aCurProgOnline = empty($_REQUEST['online']) ? array() : explode(",",$_REQUEST['online']);

                //--- Get new online users ---//
                $aNewOnline = array_diff($aCurDbOnline, $aCurProgOnline);
                foreach($aNewOnline as $iNewOnline)
                {
                                $aUserInfo = getUserInfo($iNewOnline);
                                $sContents .= parseXml($aXmlTemplates['user'], $iNewOnline, $aUserInfo['nick'], $aUserInfo['sex'], $aUserInfo['age'], TRUE_VAL, $aUserInfo['photo'], $aUserInfo['profile']);
                }

                //--- Get new offline users ---//
                $aNewOffline = array_diff($aCurProgOnline, $aCurDbOnline);
                foreach($aNewOffline as $iNewOffline) $sContents .= parseXml($aXmlTemplates['user'], $iNewOffline, FALSE_VAL);
                $sContents = makeGroup($sContents, "users");
                break;

        /**
         * Updates user's information in RayPresenceUsers table.
         * For Users, who didn't login into Ray Presence, but logged in into site.
         * @param id - user's ID.
         * @param status - user's status.
         */
        case "updateOnlineStatus":
                updateOnline($sId, $sStatus);
                break;
}
?>