<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

/**
 * Ban actions.
 * Check if this user is banned, ban this user, unban this user.
 * @param sSwitch - the name of the action which will be processed.
 * @param iId - user's identifier;
 */
function doBan($sSwitch, $iId = "0")
{
	global $sModule;
	global $aXmlTemplates;
	global $sDefSmileset;
	
	$sBan = FALSE_VAL;
	switch($sSwitch)
	{
		case 'check': //--- check if user specified by ID is banned or not.
			return getValue("SELECT `Banned` FROM `" . MODULE_DB_PREFIX ."Profiles` WHERE `ID` = '" . $iId . "' LIMIT 1");
			
		case 'ban': //--- ban the user specified by ID.
			$sBan = TRUE_VAL;
			//break shouldn't be here
		case 'unban': //--- unban the user, specified by ID.
		default:
			$sUserId = getValue("SELECT `ID` FROM `" . MODULE_DB_PREFIX ."Profiles` WHERE `ID` = '" . $iId . "' LIMIT 1");
			if(empty($sUserId))
			{
				$sDefType = getSettingValue($sModule, "defaultType");
				$sSql = "INSERT INTO `" . MODULE_DB_PREFIX . "Profiles`(`ID`, `Banned`, `Type`, `Smileset`) VALUES('" . $iId . "', '" . $sBan . "', '" . $sDefType . "', '" . $sDefSmileset . "')";
			}
			else $sSql = "UPDATE `" . MODULE_DB_PREFIX . "Profiles` SET `Banned`='" . $sBan . "'";
			return getResult($sSql);
	}
}

/**
 * Get information about avaliable rooms in XML format.
 * @comment - Refreshed
 */
function getRooms($sMode = 'new', $iId = 0)
{
        global $aXmlTemplates;
        global $sModule;

        $iUpdateInterval = (int)getSettingValue($sModule, "updateInterval");
        $iDeleteTime = (int)getSettingValue($sModule, "deleteTime");

        switch ($sMode)
        {
        case 'upd': //--- Return new and deleted rooms.
                        $rResult = getResult("SELECT `ID`, `Name`, `OwnerID`, `Status` FROM `" . MODULE_DB_PREFIX . "Rooms` WHERE IF('" . $iId . "'='0', 1, `OwnerID`<>'" . $iId . "') AND (`When` >= (UNIX_TIMESTAMP() - " . $iUpdateInterval . ") OR `Status`='" . ROOM_STATUS_DELETE . "') ORDER BY `When`");
                    while($aRoom = mysql_fetch_assoc($rResult))
                                switch($aRoom['Status'])
                                {
                                        case ROOM_STATUS_NORMAL:
                                                $sRooms .= parseXml($aXmlTemplates['room'], $aRoom['ID'], $aRoom['Status'], stripslashes($aRoom['Name']), $aRoom['OwnerID']);
                                                break;
                                        case ROOM_STATUS_DELETE:
                                                $sRooms .= parseXml($aXmlTemplates['room'], $aRoom['ID'], $aRoom['Status']);
                                                break;
                                }

                        break;
                case 'new': //--- Return all rooms except already deleted.
                        getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Rooms` WHERE `Status`='" . ROOM_STATUS_DELETE . "' AND `When`<=(UNIX_TIMESTAMP()-" . $iDeleteTime . ")");

                        $rResult = getResult("SELECT `ID`, `Name`, `OwnerID`, `Status` FROM `" . MODULE_DB_PREFIX ."Rooms` WHERE `Status`<>'" . ROOM_STATUS_DELETE . "' ORDER BY `ID` LIMIT " . getSettingValue($sModule, "maxRoomsNumber"));
                    while($aRoom = mysql_fetch_assoc($rResult))
                                $sRooms .= parseXml($aXmlTemplates['room'], $aRoom['ID'], $aRoom['Status'], stripslashes($aRoom['Name']), $aRoom['OwnerID']);
					if(mysql_num_rows($rResult) == 0) $sRooms = parseXml($aXmlTemplates['room'], "1", ROOM_STATUS_NORMAL, "Lobby", "0");
            break;
        }
    return $sRooms;
}

/**
 * Actions with specified room
 */
function doRoom($sSwitch, $iUserId = 0, $iRoomId = 0, $sRoom = "")
{
    switch ($sSwitch)
	{
		case 'insert':
			$aCurRoom = getArray("SELECT `ID`, `Status` FROM `" . MODULE_DB_PREFIX . "Rooms` WHERE `Name`='" . $sRoom . "'");
			if(!empty($aCurRoom['ID']) && $aCurRoom['Status'] == ROOM_STATUS_DELETE)
			{
				getResult("UPDATE `" . MODULE_DB_PREFIX . "Rooms` SET `OwnerID`='" . $iUserId . "', `When`=UNIX_TIMESTAMP(), `Status`='" . ROOM_STATUS_NORMAL . "' WHERE `ID`='" . $aCurRoom['ID'] . "'");
				return $aCurRoom['ID'];
			}
			else if(empty($aCurRoom['ID']))
			{
				getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Rooms` (`ID`, `Name`, `OwnerID`, `When`, `Status`) VALUES ('" . $iRoomId . "', '" . addslashes($sRoom) . "', '" . $iUserId . "', UNIX_TIMESTAMP(), '" . ROOM_STATUS_NORMAL . "')");
				return getLastInsertId();
			}
			else 
				return 0;
			break;
		case 'delete':
			$sQuery = "UPDATE `" . MODULE_DB_PREFIX . "Rooms` SET `When`=UNIX_TIMESTAMP(), `Status`='" . ROOM_STATUS_DELETE . "' WHERE `ID` = '" . $iRoomId . "'";
			getResult($sQuery);
			break;
    }            
}


/**
 * ===> The rest of functions is for XML version only. <===
 * Update user's status
 * @comment - Refreshed
 */
function refreshUsersInfo($iId = 0, $sMode = 'all')
{
        global $aXmlTemplates;
        global $sModule;

        $iUpdateInterval = (int)getSettingValue($sModule, "updateInterval");
        $iIdleTime = (int)getSettingValue($sModule, "idleTime");
        $iDeleteTime = (int)getSettingValue($sModule, "deleteTime");


        //--- refresh current user's track ---//
        $sQuery = "UPDATE `" . MODULE_DB_PREFIX . "CurrentUsers` SET `Status`='" . USER_STATUS_OLD . "', `When`=UNIX_TIMESTAMP() WHERE `Status`<>'" . USER_STATUS_KICK . "' AND IF(`Status` IN ('" . USER_STATUS_NEW . "', '" . USER_STATUS_TYPE . "', '" . USER_STATUS_ROOM . "') AND `When`>=(UNIX_TIMESTAMP()-" . $iUpdateInterval . "), `ID`='-1', `ID`='" . $iId . "')";
        getResult($sQuery);

        //--- refresh other users' states ---//
        $sQuery = "UPDATE `" . MODULE_DB_PREFIX . "CurrentUsers` SET `When`=UNIX_TIMESTAMP(), `Status`='" . USER_STATUS_IDLE . "' WHERE `Status`<>'" . USER_STATUS_IDLE . "' AND `When`<=(UNIX_TIMESTAMP()-" . $iIdleTime . ")";
        getResult($sQuery);

        //--- delete idle users, whose track was not refreshed more than delete time ---//
        $sQuery = "DELETE FROM `" . MODULE_DB_PREFIX . "CurrentUsers` WHERE `Status`='" . USER_STATUS_IDLE . "' AND `When`<=(UNIX_TIMESTAMP()-" . $iDeleteTime . ")";
        getResult($sQuery);

        //--- delete old rooms ---//
        $sQuery = "DELETE FROM `" . MODULE_DB_PREFIX . "Rooms` WHERE `Status`='" . ROOM_STATUS_DELETE . "' AND `When`<=(UNIX_TIMESTAMP()-" . $iDeleteTime . ")";
        getResult($sQuery);

        //--- delete old messages ---//
        $sQuery = "DELETE FROM `" . MODULE_DB_PREFIX . "Messages` WHERE `When`<=(UNIX_TIMESTAMP()-" . $iDeleteTime . ")";
        getResult($sQuery);

        //--- Get information about users in the chat ---//
        switch($sMode)
        {
                case 'upd':
                        $rRes = getResult("SELECT ccu.`ID` AS `ID`, ccu.`Nick` AS `Nick`, ccu.`Sex` AS `Sex`, ccu.`Age` AS `Age`, ccu.`Desc` AS `Desc`, ccu.`Photo` AS `Photo`, ccu.`Profile` AS `Profile`, ccu.`Status` AS `Status`, ccu.`RoomID` AS `RoomID`, rp.`Type` AS `Type` FROM `" . MODULE_DB_PREFIX . "Profiles` AS rp, `" . MODULE_DB_PREFIX . "CurrentUsers` AS ccu WHERE rp.`ID`=ccu.`ID` ORDER BY ccu.`When`");
                        while($aUser = mysql_fetch_assoc($rRes))
                        {
                                if($aUser['ID'] == $iId && !($aUser['Status'] == USER_STATUS_KICK || $aUser['Status'] == USER_STATUS_TYPE)) continue;
                                switch($aUser['Status'])
                                {
                                        case USER_STATUS_NEW:
                                                $sContent .= parseXml($aXmlTemplates['user'], $aUser['ID'], $aUser['Status'], $aUser['Nick'], $aUser['Sex'], $aUser['Age'], $aUser['Desc'], $aUser['Photo'], $aUser['Profile'], $aUser['Type'], $aUser['RoomID']);
                                                break;
                                        case USER_STATUS_ROOM:
                                                $sContent .= parseXml($aXmlTemplates['user'], $aUser['ID'], $aUser['Status'], $aUser['RoomID']);
                                                break;
                                        case USER_STATUS_TYPE:
                                                $sContent .= parseXml($aXmlTemplates['user'], $aUser['ID'], $aUser['Status'], $aUser['RoomID'], $aUser['Type']);
                                                break;
                                        case USER_STATUS_IDLE:
                                        case USER_STATUS_KICK:
                                                $sContent .= parseXml($aXmlTemplates['user'], $aUser['ID'], $aUser['Status']);
                                                break;
                                }
                        }
                        break;

                case 'all':
                        $rRes = getResult("SELECT ccu.`ID` AS `ID`, ccu.`Nick` AS `Nick`, ccu.`Sex` AS `Sex`, ccu.`Age` AS `Age`, ccu.`Desc` AS `Desc`, ccu.`Photo` AS `Photo`, ccu.`Profile` AS `Profile`, ccu.`RoomID` AS `RoomID`, rp.`Type` AS `Type` FROM `" . MODULE_DB_PREFIX . "Profiles` AS rp, `" . MODULE_DB_PREFIX . "CurrentUsers` AS ccu WHERE rp.`ID`=ccu.`ID` AND ccu.`Status` NOT IN ('" . USER_STATUS_IDLE . "', '" . USER_STATUS_KICK . "') AND rp.`Banned`='false' ORDER BY ccu.`When`");
                        while($aUser = mysql_fetch_assoc($rRes))
                                $sContent .= parseXml($aXmlTemplates['user'], $aUser['ID'], "new", $aUser['Nick'], $aUser['Sex'], $aUser['Age'], $aUser['Desc'], $aUser['Photo'], $aUser['Profile'], $aUser['Type'], $aUser['RoomID']);
                        break;
        }
        return makeGroup($sContent, "users");
}

/**
  * get user type
 * @param $sId - user ID
 * @return $sType - user type
 */
function getUserType($sId)
{
	global $sModule;
	global $sDefSmileset;
	
	$sType = getValue("SELECT `Type` FROM `" . MODULE_DB_PREFIX . "Profiles` WHERE `ID`='" . $sId . "'");
    if(empty($sType)) 
	{
		$sType = getSettingValue($sModule, "defaultType");
        getResult("INSERT INTO `" . MODULE_DB_PREFIX . "Profiles` SET `ID`='" . $sId . "', `Type`='" . $sType . "', `Smileset`='" . $sDefSmileset . "'");
    }
	return $sType;
}
?>