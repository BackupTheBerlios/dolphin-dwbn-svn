<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License. 
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details. 
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin, 
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

$site['ver']               = '6.0';
$site['build']             = '0005';
$site['title']             = 'Virtual Sangha';
$site['url']               = "http://localhost/";
$admin_dir                 = "admin";
$site['url_admin']         = "{$site['url']}$admin_dir/";
$site['url_aff']           = "{$site['url']}aff/";
$site['profileImage']      = "{$site['url']}media/images/profile/";
$site['profileBackground'] = "{$site['url']}media/images/profile_bg/";
$site['profileSound']      = "{$site['url']}media/sound/";
$site['profileVideo']      = "{$site['url']}media/video/";
$site['sharingImages']     = "{$site['url']}media/images/sharingImages/";

$site['mediaImages']       = "{$site['url']}media/images/";
$site['gallery']           = "{$site['url']}media/images/gallery/";
$site['flags']             = "{$site['url']}media/images/flags/";
$site['blogImage']         = "{$site['url']}media/images/blog/";
$site['sdatingImage']      = "{$site['url']}media/images/sdating/";
$site['smiles']            = "{$site['url']}media/images/smiles/";
$site['banners']           = "{$site['url']}media/images/banners/";
$site['imagesPromo']       = "{$site['url']}media/images/promo/";
$site['tmp']               = "{$site['url']}tmp/";
$site['preCheckout']       = "{$site['url']}checkout/pre_checkout.php";
$site['plugins']           = "{$site['url']}plugins/";
$site['base']              = "{$site['url']}templates/base/";




$site['email']             = "emaho@irekjozwiak.com";
$site['email_notify']      = "emaho@irekjozwiak.com";
$site['bugReportMail']     = "emaho@irekjozwiak.com";



$dir['root']               = "/Library/WebServer/Documents/";
$dir['inc']                = "{$dir['root']}inc/";
$dir['profileImage']       = "{$dir['root']}media/images/profile/";
$dir['profileBackground']  = "{$dir['root']}media/images/profile_bg/";
$dir['profileSound']       = "{$dir['root']}media/sound/";
$dir['profileVideo']       = "{$dir['root']}media/video/";
$dir['sharingImages']     = "{$dir['root']}media/images/sharingImages/";

$dir['mediaImages']        = "{$dir['root']}media/images/";
$dir['gallery']            = "{$dir['root']}media/images/gallery/";
$dir['flags']              = "{$dir['root']}media/images/flags/";
$dir['blogImage']          = "{$dir['root']}media/images/blog/";
$dir['sdatingImage']       = "{$dir['root']}media/images/sdating/";
$dir['smiles']             = "{$dir['root']}media/images/smiles/";
$dir['banners']            = "{$dir['root']}media/images/banners/";
$dir['imagesPromo']        = "{$dir['root']}media/images/promo/";
$dir['tmp']                = "{$dir['root']}tmp/";
$dir['cache']              = "{$dir['root']}cache/";
$dir['plugins']            = "{$dir['root']}plugins/";
$dir['base']               = "{$dir['root']}templates/base/";
$dir['classes']            = "{$dir['inc']}classes/";

$video_ext                 = 'avi';
$MOGRIFY                   = "/usr/local/bin/mogrify";
$CONVERT                   = "/usr/local/bin/convert";
$COMPOSITE                 = "/usr/local/bin/composite";
$PHPBIN                    = "/usr/bin/php";

$db['host']                = 'localhost';
$db['sock']                = '/tmp/mysql.sock';
$db['port']                = '';
$db['user']                = 'irek';
$db['passwd']              = 'macbook4ir';
$db['db']                  = 'virtualsangha';


define('BX_DIRECTORY_PATH_INC', $dir['inc']);
define('BX_DIRECTORY_PATH_ROOT', $dir['root']);
define('BX_DIRECTORY_PATH_BASE', $dir['base']);
define('BX_DIRECTORY_PATH_CACHE', $dir['cache']);
define('BX_DIRECTORY_PATH_CLASSES', $dir['classes']);

define('DATABASE_HOST', $db['host']);
define('DATABASE_SOCK', $db['sock']);
define('DATABASE_PORT', $db['port']);
define('DATABASE_USER', $db['user']);
define('DATABASE_PASS', $db['passwd']);
define('DATABASE_NAME', $db['db']);



//check safe_mode
if( (int)ini_get( 'safe_mode' ) )
{
	echo '<b>Warning.</b> Dolphin cannot work in safe mode';
	exit;
}

//check mbstring
if( !extension_loaded( 'mbstring' ) ) {
	echo '<b>Warning!</b> Dolphin cannot work without <b>mbstring</b> extension.
		Please go to the
		<a href="http://www.boonex.com/trac/dolphin/wiki/GenDolTShooter">Dolphin Troubleshooter</a>
		and solve the problem.';
	exit;
}

//check correct hostname
$aUrl = parse_url( $site['url'] );
if( isset($_SERVER['HTTP_HOST']) and $_SERVER['HTTP_HOST'] != $aUrl['host'] and $_SERVER['HTTP_HOST'] != $aUrl['host'] . ':80' )
{
	header( "Location:http://{$aUrl['host']}{$_SERVER['REQUEST_URI']}" );
	exit;
}



// check if install folder exists
if ( file_exists( $dir['root'] . 'install' ) )
{
	$ret = <<<EOJ
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
		<head>
			<title>Dolphin Smart Community Builder Installed</title>
			<link href="install/general.css" rel="stylesheet" type="text/css" />
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		</head>
		<body>
			<div id="main">
			<div id="header">
				<img src="install/images/boonex_logo.gif" alt="" /></div>
			<div id="content">
				<div class="installed_pic">
					<img alt="Dolphin Installed" src="install/images/dolphin_installed.jpg" />
			</div>

			<div class="installed_text">
				Please, remove INSTALL directory from your server and reload this page to activate your community site.
			</div>
		</body>
	</html>
EOJ;
	echo $ret;
	exit();
}

// set error reporting level
error_reporting(E_ALL & ~E_NOTICE);
set_magic_quotes_runtime(0);
ini_set('magic_quotes_sybase', 0);

// set default encoding for multibyte functions
mb_internal_encoding('UTF-8');

//--- Ray Integration ---//
require_once($dir['root'] . "ray/modules/global/inc/header.inc.php");
require_once($dir['root'] . "ray/modules/global/inc/content.inc.php");
//--- Ray Integration ---//
?>