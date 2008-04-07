<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

include("/Library/WebServer/Documents/inc/header.inc.php");
/**
 * Current version information.
 */
if(!defined("VERSION")) define("VERSION", "3.1.0000");

/**
 * Data Base Settings
 */
if(!defined("DB_HOST")) define("DB_HOST", $db['host']);
if(!defined("DB_PORT")) define("DB_PORT", $db['port']);
if(!defined("DB_SOCKET")) define("DB_SOCKET", $db['sock']);
if(!defined("DB_NAME")) define("DB_NAME", $db['db']);
if(!defined("DB_USER")) define("DB_USER", $db['user']);
if(!defined("DB_PASSWORD")) define("DB_PASSWORD", $db['passwd']);
if(!defined("DB_PREFIX")) define("DB_PREFIX", "Ray");
if(!defined("GLOBAL_MODULE")) define("GLOBAL_MODULE", "global");
if(!defined("GLOBAL_DB_PREFIX")) define("GLOBAL_DB_PREFIX", DB_PREFIX . "Global");
$sDBModule = strtoupper(substr($sModule, 0, 1)) . substr($sModule, 1);
if(!defined("MODULE_DB_PREFIX")) define("MODULE_DB_PREFIX", DB_PREFIX . $sDBModule);

/**
 * Login and password for admin.
 */
$sAdminLogin = "admin";
$sAdminPassword = "dolphin";

/**
 * General Settings
 * URL and absolute path for the Ray location directory.
 */
$sRootPath = $dir['root'];
$sRootURL = $site['url'];
$sRayHomeDir = "ray/";

$sHomeUrl = $sRootURL . $sRayHomeDir;
$sHomePath = $sRootPath . $sRayHomeDir;


/**
 * Pathes to the system directories and necessary files.
 */
$sModulesDir = "modules/";
$sModulesUrl = $sHomeUrl . $sModulesDir;
$sModulesPath = $sHomePath . $sModulesDir;

$sGlobalDir = "global/";
$sGlobalUrl = $sModulesUrl . $sGlobalDir;
$sGlobalPath = $sModulesPath . $sGlobalDir;

$sFfmpegPath = $sGlobalPath . "app/ffmpeg.exe";

$sIncPath = $sGlobalPath . "inc/";

$sDataDir = "data/";
$sDataUrl = $sGlobalUrl . $sDataDir;
$sDataPath = $sGlobalPath . $sDataDir;

$sSmilesetsDir = "smilesets/";
$sSmilesetsUrl = $sDataUrl . $sSmilesetsDir;
$sSmilesetsPath = $sDataPath . $sSmilesetsDir;

/**
 * Default smileset name. It has to be equel to the name of some directory in the "smilesets" directory.
 * The default path to smilesets directory is [path_to_ray]/data/smilesets
 */
$sDefSmileset = "DefaultSmiles";

$sNoImageUrl = $sDataUrl . "no_photo.jpg";

/**
 * Cron Update Interval (in seconds)
 */
$iCronUpdateInterval = 600;

/**
 * Integration parameters.
 * URL of the site in which Ray is integrated.
 */
$sScriptHomeDir = "";
$sScriptHomeUrl = $sRootURL . $sScriptHomeDir;

/**
 * Path to images direcrory
 */
$sImagesPath = $sScriptHomeUrl . "media/images/profile/";

/**
 * URL of the profile view page
 */
$sProfileUrl = $sScriptHomeUrl . "profile.php";
?>