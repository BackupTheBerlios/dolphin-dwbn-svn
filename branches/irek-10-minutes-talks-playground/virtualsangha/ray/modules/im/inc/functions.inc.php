<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

/**
 * Refresh user's status.
 */
function refreshIMUsers($iSndId, $iRspId)
{
        global $sModule;

        $iIdleTime = (int)getSettingValue($sModule, "idleTime");
        $iDeleteTime = (int)getSettingValue($sModule, "deleteTime");

        //--- update user's online state ---//
        getResult("UPDATE `" . MODULE_DB_PREFIX . "Contacts` SET `When`=UNIX_TIMESTAMP() WHERE `SenderID`='" . $iSndId . "' AND `RecipientID` = '" . $iRspId . "'");
        //--- delete idle users ---//
        getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Contacts` WHERE `When`<=(UNIX_TIMESTAMP()-" . $iIdleTime . ")");
        //--- delete old messages ---//
        getResult("DELETE FROM `" . MODULE_DB_PREFIX . "Messages` WHERE `When`<=(UNIX_TIMESTAMP()-" . $iDeleteTime . ")");
}

/**
 * Add pending message function
 */
function addPend($iSndId, $iRspId, $sMsg)
{
        $sQuery = "INSERT INTO `" . MODULE_DB_PREFIX . "Pendings`(`SenderID`, `RecipientID`, `Msg`, `When`) VALUES('" . $iSndId . "', '" . $iRspId . "', '" . $sMsg . "', UNIX_TIMESTAMP())";
        return getResult($sQuery);
}
?>