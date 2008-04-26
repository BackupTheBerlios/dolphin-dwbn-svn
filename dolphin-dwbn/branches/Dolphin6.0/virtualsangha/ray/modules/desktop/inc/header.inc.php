<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

/**
 * Default skin name. The file of the skin will be taken from skins directory.
 * NOTE: The name has to be equal to file name, but doesn't contain extension.
 */
$sSkinNames = "default";
$sDefSkinName = "default";

/**
 * Default language name. Language file will be taken from langs directory.
 * NOTE: The name has to be equal to file name, but doesn't contain extension.
 */
$sLangNames = "english";
$sDefLangName = "english";


/**
 * Dynamic Links.
 * The number of dynamic links (extra tray menu items)
 */
$iUrlItemCount = 2;

/**
 * Extra tray menu items' captions.
 * The caption needs to be translated in language files.
 */
$sCaptionItem_1 = "iCapt1";
$sCaptionItem_2 = "iCapt2";

/**
 * Extra module names
 */
$sImModule = "im";
$sChatModule = "chat";
$sVideoModule = "video";

/**
 * Extra module DB Prefixes
 */
$sImDBPrefix = DB_PREFIX . strtoupper(substr($sImModule, 0, 1)) . substr($sImModule, 1);
$sChatDBPrefix = DB_PREFIX . strtoupper(substr($sChatModule, 0, 1)) . substr($sChatModule, 1);
$sVideoDBPrefix = DB_PREFIX . strtoupper(substr($sVideoModule, 0, 1)) . substr($sVideoModule, 1);
?>