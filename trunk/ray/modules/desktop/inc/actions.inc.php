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
//user's status
$sStatus = isset($_REQUEST['status']) ? $_REQUEST['status'] : "";
//user's Nick
$sNick = isset($_REQUEST['nick']) ? $_REQUEST['nick'] : "";
//user's password
$sPass = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";

switch ($sAction)
{
        /**
         * Gets configuration for Ray Desctop
         */
        case "config":
                        $sFileName = $sModulesPath . $sModule . "/xml/config.xml";
                        $rHandle = fopen($sFileName, "rt");
                        $sContents = fread($rHandle, filesize($sFileName));
                        fclose($rHandle);

                        //--- Process Info ---//
                        $sContents = str_replace("#scriptHomePage#", $sScriptHomeUrl, $sContents);
                        $sContents = str_replace("#scriptPageCount#", $iUrlItemCount, $sContents);
                        $sContents = str_replace("#scriptPage1Capt#", $sCaptionItem_1, $sContents);
                        $sContents = str_replace("#scriptPage2Capt#", $sCaptionItem_2, $sContents);
                        $sContents = str_replace("#chatModule#", $sChatModule, $sContents);
                        $sContents = str_replace("#imModule#", $sImModule, $sContents);
                        $sContents = str_replace("#videoModule#", $sVideoModule, $sContents);

                        $aFiles = getAvailableFiles($sModule, "skins");
                        $sContents = str_replace("#skin#", $aFiles['default'], $sContents);
                        $sContents = str_replace("#skins#", implode(",", $aFiles['files']), $sContents);
                        $sContents = str_replace("#skinDates#", implode(",", $aFiles['dates']), $sContents);

                        $aFiles = getAvailableFiles($sModule, "langs");
                        $sContents = str_replace("#lang#", $aFiles['default'], $sContents);
                        $sContents = str_replace("#langs#", implode(",", $aFiles['files']), $sContents);
                        $sContents = str_replace("#langDates#", implode(",", $aFiles['dates']), $sContents);
                        break;

        /**
        * Makes login process for user by specified login and password
        * @param login - user's login name.
        * @param password - user's password.
        */
        case "userAuthorize":
                        if(loginUser($sNick, md5($sPass), true) == TRUE_VAL)
                        {
                                        $sId = getIdByNick($sNick);
                                        $aUserInfo = getUserInfo($sId);
                                        updateOnline($sId);
                                        $sContents = parseXml($aXmlTemplates['user'], $sId, $aUserInfo['nick'], md5($sPass), $aUserInfo['sex'], $aUserInfo['age'], TRUE_VAL);
                        }
                        break;

        /**
         * Gets Friend's information.
         * @param id - user's ID, whose friends' info need to be got.
         */
        case "getFriends":
                        $aFriendIds = getFriends($sId);
                        $aOnlineFriends = getOnline($aFriendIds);
                        foreach($aFriendIds as $iFriendId)
                        {
                                        $aUserInfo = getUserInfo($iFriendId);
                                        $sOnline = in_array($iFriendId, $aOnlineFriends) ? TRUE_VAL : FALSE_VAL;
                                        $sContents .= parseXml($aXmlTemplates['user'], $iFriendId, $aUserInfo['nick'], "", $aUserInfo['sex'], $aUserInfo['age'], $sOnline, $aUserInfo['photo'], $aUserInfo['profile']);
                        }
                        $sContents = makeGroup($sContents, "users");
                        break;

        /**
         * Update friend's information.
         * @param id - user's ID, whose friends' info need to be updated.
         */
        case "updFriends":
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
							$sContents .= parseXml($aXmlTemplates['user'], $aOnlineUsers[$i], $aUserInfo['nick'], "", $aUserInfo['sex'], $aUserInfo['age'], TRUE_VAL, $aUserInfo['photo'], $aUserInfo['profile']);
                        }
                        $sContents = makeGroup($sContents, "users");
                        break;

        /**
         * Update online user's information.
         * @param online - currently online users, whose info was got earlier.
         */
        case "updOnlineUsers":
                        $aCurDbOnline = getOnline();
                        $aCurProgOnline = empty($_REQUEST['got']) ? array() : explode(",",$_REQUEST['got']);

                        //--- Get new online users ---//
                        $aNewOnline = array_diff($aCurDbOnline, $aCurProgOnline);
                        foreach($aNewOnline as $iNewOnline)
                        {
                                        $aUserInfo = getUserInfo($iNewOnline);
                                        $sContents .= parseXml($aXmlTemplates['user'], $iNewOnline, $aUserInfo['nick'], "", $aUserInfo['sex'], $aUserInfo['age'], TRUE_VAL, $aUserInfo['photo'], $aUserInfo['profile']);
                        }

                        //--- Get new offline users ---//
                        $aNewOffline = array_diff($aCurProgOnline, $aCurDbOnline);
                        foreach($aNewOffline as $iNewOffline) $sContents .= parseXml($aXmlTemplates['user'], $iNewOffline);
                        $sContents = makeGroup($sContents, "users");
                        break;

        /**
         * Gets new mails.
         * @param id - user's ID, whose mails need to be checked.
         * @param got - a list of message's IDs, which were got previously (For example: 1,25,63 or -1 if this list is empty).
         */
        case "getMails":
                        $aMails = getMails($sId, $_REQUEST['got']);
                        while($aMail = mysql_fetch_array($aMails))
                        {
                                        $aUserInfo = getUserInfo($aMail['uid']);
                                        $sContents .= parseXml($aXmlTemplates['message'], $aMail['body'], $aMail['id'], $aMail['uid'], $aUserInfo['nick'], $aUserInfo['sex'], $aUserInfo['age'], $aUserInfo['photo'], $aUserInfo['profile']);
                        }
                        $sContents = makeGroup($sContents, "msgs");
                        break;

        /**
         * Gets new IM notifications.
         * @param id - user's ID, whose IM notifications need to be checked.
         * @param got - a list of notification's IDs, which were got previously (For example: 1,25,63 or -1 if this list is empty).
         */
        case "getIms":
                        $sQuery = "SELECT `ID`, `SenderID`, SUBSTRING(`Msg`, 1, 15) AS `Msg` FROM `" . $sImDBPrefix ."Pendings` WHERE `RecipientID`='" . $sId . "' AND IF('" .  $_REQUEST['got'] . "'<>'-1', `ID` NOT IN (" . $_REQUEST['got'] . "), '1')";
                        $aIms = getResult($sQuery);
                        while($aIm = mysql_fetch_array($aIms))
                        {
                                        $aUserInfo = getUserInfo($aIm['SenderID']);
                                        $sContents .= parseXml($aXmlTemplates['message'], $aIm['Msg'], $aIm['ID'], $aIm['SenderID'], $aUserInfo['nick'], $aUserInfo['sex'], $aUserInfo['age'], $aUserInfo['photo'], $aUserInfo['profile']);
                        }
                        $sContents = makeGroup($sContents, "msgs");
                        break;

        /**
         * Declines received IM message.
         * @param id - user's ID.
         */
        case "declineIm":
                        getResult("DELETE FROM `" . $sImDBPrefix . "Pendings` WHERE `ID`='" . $sId . "'");
                        break;

        /**
         * Update information from remote server.
         * @param type - the type of information to be updated.
         * @param name - the name of the file to be updated.
         */
        case "update":
                        $sFileName = $sModulesPath . "desktop/" . $_REQUEST['type'] .  "/" . $_REQUEST['name'];

                        $rHandler = fopen($sFileName, "rb");
                        $sContents = fread($rHandler, filesize($sFileName));
                        $sContentsType = $_REQUEST['type'] == "skins" ? CONTENTS_TYPE_OTHER : CONTENTS_TYPE_XML;
                        fclose($rHandler);

                        break;

        /**
         * @param location - the location where you need to redirect.
         * @param id - user's ID.
         * @param  nick - user's nick name.
         * @param password - user's password.
         * @param  mid - mail's ID.
         * @param rid - ID of the recipient of a message, which will be created.
         * @param rnick - Nick name of the recipient of a message, which will be created.
         */
        case "redirect":
                        redirect($_REQUEST['location'], $_REQUEST['id'],  $_REQUEST['nick'], $_REQUEST['password'], $_REQUEST['mid'], $_REQUEST['rid'], $_REQUEST['rnick']);
                        break;

        /**
         * Updates user's information in RayTrackUsers table.
         * For Users, who didn't login into Ray Presence, but logged in into site.
         * @param id - user's ID.
         */
        case "updOnlineStatus":
                        updateOnline($sId, $sStatus);
                        break;
}
?>
