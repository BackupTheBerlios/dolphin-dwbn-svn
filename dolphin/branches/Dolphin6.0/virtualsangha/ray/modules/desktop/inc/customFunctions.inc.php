<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

/**
 * Get user's identifier using user's nickname.
 */
function getIdByNick($sNick)
{
   return (int)getValue("SELECT `ID` FROM `Profiles` WHERE `NickName` = '" . $sNick . "' LIMIT 1");
}

/**
 * Gets new user's mails except already got mails($sGotMails) by specified user id
 */
function getMails($sId, $sGotMails)
{
        $sQuery = "SELECT `ID` AS id, `Sender` AS uid, SUBSTRING(`Text`, 1, 150) AS body FROM `Messages` WHERE `Recipient` = '$sId' AND `New` = '1' AND IF('{$sGotMails}' <> '-1',`ID` NOT IN ({$sGotMails}), '1')";
        return getResult($sQuery);
}

/**
 * This function automatically logins user to your site and makes necessary redirects
 */
function redirect($sLocation, $iId, $sNick, $sPassword, $iMessageId, $iRecipientId, $sRecipientNick)
{
         global $sScriptHomeUrl;

         /**
          * Automatic login to your Web site.
          * You need to change this section according to your login algorithm.
          */
		$sPassword = md5( $sPassword );
         setcookie( "memberID", $iId, 0, '/' );
         setcookie( "memberPassword", $sPassword, 0, '/' );

         //--- this section makes redirects depending on the specified action ---//
         switch($sLocation)
         {
                case "goToInbox":
                        header("Location: " . $sScriptHomeUrl . getSettingValue("desktop", "scriptMailboxPage"));
                        break;
                case "goToReadMsg":
                        header("Location: " . $sScriptHomeUrl . getSettingValue("desktop", "scriptMessagePage") . "?message=" . $iMessageId);
                        break;
                case "goToNewMsg":
                        header("Location: " . $sScriptHomeUrl . getSettingValue("desktop", "scriptComposePage") . "?ID=" . $sRecipientNick);
                        break;
                default:
                        header("Location: " . $sScriptHomeUrl);
         }
         exit();
}
?>