<?php

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

error_reporting(E_ALL & ~E_NOTICE);
set_magic_quotes_runtime(0);
ini_set('magic_quotes_sybase', 0);


/*------------------------------*/
/*----------Vars----------------*/
	$aConf = array();
	$aConf['release'] = '28.04.08';
	$aConf['iVersion'] = '6.1';
	$aConf['iPatch'] = '0';
	$aConf['dolFile'] = '../inc/header.inc.php';
	$aConf['periodicFile'] = '../periodic/periodic.file';
	$aConf['cmdFile'] = '../periodic/cmd.php';
	$aConf['notifiesFile'] = '../periodic/notifies.php';
	$aConf['cupidFile'] = '../periodic/cupid.php';
	$aConf['confDir'] = '../inc/';
	$aConf['rayHeader'] = '../ray/modules/global/inc/header.inc.php';
	$aConf['headerTempl'] = <<<EOS
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

\$site['ver']               = '{$aConf['iVersion']}';
\$site['build']             = '{$aConf['iPatch']}';
\$site['title']             = '%site_title%';
\$site['url']               = "%site_url%";
\$admin_dir                 = "admin";
\$site['url_admin']         = "{\$site['url']}\$admin_dir/";
\$site['url_aff']           = "{\$site['url']}aff/";
\$site['profileImage']      = "{\$site['url']}media/images/profile/";
\$site['profileBackground'] = "{\$site['url']}media/images/profile_bg/";
\$site['profileSound']      = "{\$site['url']}media/sound/";
\$site['profileVideo']      = "{\$site['url']}media/video/";
\$site['sharingImages']     = "{\$site['url']}media/images/sharingImages/";

\$site['mediaImages']       = "{\$site['url']}media/images/";
\$site['gallery']           = "{\$site['url']}media/images/gallery/";
\$site['flags']             = "{\$site['url']}media/images/flags/";
\$site['blogImage']         = "{\$site['url']}media/images/blog/";
\$site['sdatingImage']      = "{\$site['url']}media/images/sdating/";
\$site['smiles']            = "{\$site['url']}media/images/smiles/";
\$site['banners']           = "{\$site['url']}media/images/banners/";
\$site['imagesPromo']       = "{\$site['url']}media/images/promo/";
\$site['tmp']               = "{\$site['url']}tmp/";
\$site['preCheckout']       = "{\$site['url']}checkout/pre_checkout.php";
\$site['plugins']           = "{\$site['url']}plugins/";
\$site['base']              = "{\$site['url']}templates/base/";




\$site['email']             = "%site_email%";
\$site['email_notify']      = "%notify_email%";
\$site['bugReportMail']     = "%bug_report_email%";



\$dir['root']               = "%dir_root%";
\$dir['inc']                = "{\$dir['root']}inc/";
\$dir['profileImage']       = "{\$dir['root']}media/images/profile/";
\$dir['profileBackground']  = "{\$dir['root']}media/images/profile_bg/";
\$dir['profileSound']       = "{\$dir['root']}media/sound/";
\$dir['profileVideo']       = "{\$dir['root']}media/video/";
\$dir['sharingImages']     = "{\$dir['root']}media/images/sharingImages/";

\$dir['mediaImages']        = "{\$dir['root']}media/images/";
\$dir['gallery']            = "{\$dir['root']}media/images/gallery/";
\$dir['flags']              = "{\$dir['root']}media/images/flags/";
\$dir['blogImage']          = "{\$dir['root']}media/images/blog/";
\$dir['sdatingImage']       = "{\$dir['root']}media/images/sdating/";
\$dir['smiles']             = "{\$dir['root']}media/images/smiles/";
\$dir['banners']            = "{\$dir['root']}media/images/banners/";
\$dir['imagesPromo']        = "{\$dir['root']}media/images/promo/";
\$dir['tmp']                = "{\$dir['root']}tmp/";
\$dir['cache']              = "{\$dir['root']}cache/";
\$dir['plugins']            = "{\$dir['root']}plugins/";
\$dir['base']               = "{\$dir['root']}templates/base/";
\$dir['classes']            = "{\$dir['inc']}classes/";

\$video_ext                 = 'avi';
\$MOGRIFY                   = "%dir_mogrify%";
\$CONVERT                   = "%dir_convert%";
\$COMPOSITE                 = "%dir_composite%";
\$PHPBIN                    = "%dir_php%";

\$db['host']                = '%db_host%';
\$db['sock']                = '%db_sock%';
\$db['port']                = '%db_port%';
\$db['user']                = '%db_user%';
\$db['passwd']              = '%db_password%';
\$db['db']                  = '%db_name%';


define('BX_DIRECTORY_PATH_INC', \$dir['inc']);
define('BX_DIRECTORY_PATH_ROOT', \$dir['root']);
define('BX_DIRECTORY_PATH_BASE', \$dir['base']);
define('BX_DIRECTORY_PATH_CACHE', \$dir['cache']);
define('BX_DIRECTORY_PATH_CLASSES', \$dir['classes']);

define('DATABASE_HOST', \$db['host']);
define('DATABASE_SOCK', \$db['sock']);
define('DATABASE_PORT', \$db['port']);
define('DATABASE_USER', \$db['user']);
define('DATABASE_PASS', \$db['passwd']);
define('DATABASE_NAME', \$db['db']);



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
\$aUrl = parse_url( \$site['url'] );
if( isset(\$_SERVER['HTTP_HOST']) and \$_SERVER['HTTP_HOST'] != \$aUrl['host'] and \$_SERVER['HTTP_HOST'] != \$aUrl['host'] . ':80' )
{
	header( "Location:http://{\$aUrl['host']}{\$_SERVER['REQUEST_URI']}" );
	exit;
}



// check if install folder exists
if ( file_exists( \$dir['root'] . 'install' ) )
{
	\$ret = <<<EOJ
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
	echo \$ret;
	exit();
}

// set error reporting level
error_reporting(E_ALL & ~E_NOTICE);
set_magic_quotes_runtime(0);
ini_set('magic_quotes_sybase', 0);

// set default encoding for multibyte functions
mb_internal_encoding('UTF-8');

//--- Ray Integration ---//
require_once(\$dir['root'] . "ray/modules/global/inc/header.inc.php");
require_once(\$dir['root'] . "ray/modules/global/inc/content.inc.php");
//--- Ray Integration ---//
?>
EOS;

	$aConf['periodicTempl'] = <<<EOS
MAILTO=%site_email%
0 0 * * *  %dir_php% -q %dir_root%periodic/cmd.php
*/10 * * * *  %dir_php% -q %dir_root%periodic/notifies.php
* */1 * * *  %dir_php% -q %dir_root%periodic/cupid.php
EOS;

//*/10 * * * *  %dir_php% -q %dir_root%periodic/tags.php


	$permDirectories = array(
		'backup',
		'cache',
		'inc',
		'langs',
		'media/images/classifieds',
		'groups/gallery',
		'media/images',
		'media/images/banners',
		'media/images/blog',
		'media/images/gallery',
		'media/images/profile',
		'media/images/profile_bg',
		'media/images/sdating',
		'media/images/promo',
		'media/images/promo/original',
		'media/images/sharingImages',
		'media/sound',
		'media/video',
		'periodic',
		'tmp',
		'orca/classes',
		'orca/conf',
		'orca/layout',
		'orca/log',
		'orca/js',
		'orca/cachejs',
		'groups/orca/classes',
		'groups/orca/layout',
		'groups/orca/log',
		'groups/orca/js',
		'groups/orca/cachejs',
	);

	//'periodic/tags.php',
	$permFiles = array(
		'periodic/cmd.php',
		'periodic/notifies.php',
		'periodic/cupid.php',
		'inc/prof.inc.php',
		'inc/params.inc.php',
		'inc/db_cached/MenuContent.inc',
		'inc/db_cached/PageView.inc',
		'inc/db_cached/SiteStat.inc',
		'inc/db_cached/ProfileFields.inc'
	);

	$aRayFolders = array(
		'ray/modules/board/files',
		'ray/modules/chat/files',
		'ray/modules/im/files',
		'ray/modules/movie/files',
		'ray/modules/mp3/files',
		'ray/modules/music/files'
	);

	$aRayFiles = array(
		'ray/modules/global/data/integration.dat',
		'ray/modules/board/xml/config.xml',
		'ray/modules/board/xml/langs.xml',
		'ray/modules/board/xml/main.xml',
		'ray/modules/board/xml/skins.xml',
		'ray/modules/chat/xml/config.xml',
		'ray/modules/chat/xml/langs.xml',
		'ray/modules/chat/xml/main.xml',
		'ray/modules/chat/xml/skins.xml',
		'ray/modules/desktop/xml/config.xml',
		'ray/modules/desktop/xml/langs.xml',
		'ray/modules/desktop/xml/main.xml',
		'ray/modules/desktop/xml/skins.xml',
		'ray/modules/global/app/ffmpeg.exe',
		'ray/modules/global/inc/cron.inc.php',
		'ray/modules/global/inc/header.inc.php',
		'ray/modules/global/xml/config.xml',
		'ray/modules/global/xml/main.xml',
		'ray/modules/im/xml/config.xml',
		'ray/modules/im/xml/langs.xml',
		'ray/modules/im/xml/main.xml',
		'ray/modules/im/xml/skins.xml',
		'ray/modules/movie/xml/config.xml',
		'ray/modules/movie/xml/langs.xml',
		'ray/modules/movie/xml/main.xml',
		'ray/modules/movie/xml/skins.xml',
		'ray/modules/mp3/xml/config.xml',
		'ray/modules/mp3/xml/langs.xml',
		'ray/modules/mp3/xml/main.xml',
		'ray/modules/mp3/xml/skins.xml',
		'ray/modules/music/xml/config.xml',
		'ray/modules/music/xml/langs.xml',
		'ray/modules/music/xml/main.xml',
		'ray/modules/music/xml/skins.xml',
		'ray/modules/presence/xml/config.xml',
		'ray/modules/presence/xml/langs.xml',
		'ray/modules/presence/xml/main.xml',
		'ray/modules/presence/xml/skins.xml',
		'ray/modules/shoutbox/xml/config.xml',
		'ray/modules/shoutbox/xml/langs.xml',
		'ray/modules/shoutbox/xml/main.xml',
		'ray/modules/shoutbox/xml/skins.xml',
		'ray/modules/video/xml/config.xml',
		'ray/modules/video/xml/langs.xml',
		'ray/modules/video/xml/main.xml',
		'ray/modules/video/xml/skins.xml'
	);
	
	$reversalDirectories = array(
		'inc',
		'periodic'
	);

	//'periodic/tags.php',
	$reversalFiles = array(
		'periodic/cmd.php',
		'periodic/notifies.php',
		'periodic/cupid.php',
		'ray/modules/global/inc/header.inc.php',
	);

	$confFirst = array();
	$confFirst['site_url'] = array(
	name => "Site URL",
	ex => "http://www.mydomain.com/path/",
	desc => "Your site URL here (backslash at the end required)",
	def => "http://",
    def_exp => '
		$str = "http://".$_SERVER[\'HTTP_HOST\'].$_SERVER[\'SCRIPT_NAME\'];
	    return preg_replace("/install\/(index\.php$)/","",$str);',
	check => 'return strlen($arg0) >= 10 ? true : false;'
	);
	$confFirst['dir_root'] = array(
	name => "Directory root",
	ex => "/path/to/your/script/files/",
	desc => "Path to directory where your php script files stored.",
    def_exp => '
		$str = $_SERVER[\'DOCUMENT_ROOT\'].$_SERVER[\'SCRIPT_NAME\'];
	    return preg_replace("/install\/(index\.php$)/","",$str);',
	check => 'return strlen($arg0) >= 1 ? true : false;'
	);
	$confFirst['dir_php'] = array(
	name => "Path to php binary",
	ex => "/usr/local/bin/php",
	desc => "You should specify full path to your PHP interpreter here.",
	def => "/usr/local/bin/php",
    def_exp => "
		if ( file_exists(\"/usr/local/bin/php\") ) return \"/usr/local/bin/php\";
	    \$fp = popen ( \"whereis php\", \"r\");
	    if ( \$fp )
	    {
	    	\$s = fgets(\$fp);
	    	\$s = sscanf(\$s, \"php: %s\");
	    	if ( file_exists(\"\$s[0]\") ) return \"\$s[0]\";
	   	}
	   	return '';",
	check => 'return strlen($arg0) >= 7 ? true : false;'
	);

	$confFirst['dir_mogrify'] = array(
	name => "Path to mogrify",
	ex => "/usr/local/bin/mogrify",
	desc => "If mogrify binary doesn't exist please install <a href='http://www.imagemagick.org/'>ImageMagick</a>",
	def => "/usr/local/bin/mogrify",
    def_exp => "
		if ( file_exists(\"/usr/X11R6/bin/mogrify\") ) return \"/usr/X11R6/bin/mogrify\";
		if ( file_exists(\"/usr/local/bin/mogrify\") ) return \"/usr/local/bin/mogrify\";
		if ( file_exists(\"/usr/bin/mogrify\") ) return \"/usr/bin/mogrify\";
		if ( file_exists(\"/usr/local/X11R6/bin/mogrify\") ) return \"/usr/local/X11R6/bin/mogrify\";
		if ( file_exists(\"/usr/bin/X11/mogrify\") ) return \"/usr/bin/X11/mogrify\";
		return '';",
	check => 'return strlen($arg0) >= 7 ? true : false;'
	);

$confFirst['dir_convert'] = array(
	name => "Path to convert",
	ex => "/usr/local/bin/convert",
	desc => "If convert binary doesn't exist please install <a href='http://www.imagemagick.org/'>ImageMagick</a>",
	def => "/usr/local/bin/convert",
    def_exp => "
		if ( file_exists(\"/usr/X11R6/bin/convert\") ) return \"/usr/X11R6/bin/convert\";
		if ( file_exists(\"/usr/local/bin/convert\") ) return \"/usr/local/bin/convert\";
		if ( file_exists(\"/usr/bin/convert\") ) return \"/usr/bin/convert\";
		if ( file_exists(\"/usr/local/X11R6/bin/convert\") ) return \"/usr/local/X11R6/bin/convert\";
		if ( file_exists(\"/usr/bin/X11/convert\") ) return \"/usr/bin/X11/convert\";
		return '';",
	check => 'return strlen($arg0) >= 7 ? true : false;'
	);

$confFirst['dir_composite'] = array(
	name => "Path to composite",
	ex => "/usr/local/bin/composite",
	desc => "If composite binary doesn't exist please install <a href='http://www.imagemagick.org/'>ImageMagick</a>",
	def => "/usr/local/bin/composite",
    def_exp => "
		if ( file_exists(\"/usr/X11R6/bin/composite\") ) return \"/usr/X11R6/bin/composite\";
		if ( file_exists(\"/usr/local/bin/composite\") ) return \"/usr/local/bin/composite\";
		if ( file_exists(\"/usr/bin/composite\") ) return \"/usr/bin/composite\";
		if ( file_exists(\"/usr/local/X11R6/bin/composite\") ) return \"/usr/local/X11R6/bin/composite\";
		if ( file_exists(\"/usr/bin/X11/composite\") ) return \"/usr/bin/X11/composite\";
		return '';",
	check => 'return strlen($arg0) >= 7 ? true : false;'
	);

	$aDbConf = array();
	$aDbConf['sql_file'] = array(
	    name => "SQL file",
	    ex => "/home/dolphin/public_html/install/sql/vXX.sql",
	    desc => "SQL file location",
		def => "./sql/vXX.sql",
		def_exp => '
			if ( !( $dir = opendir( "sql/" ) ) )
		        return "";
			while (false !== ($file = readdir($dir)))
		        {
			    if ( substr($file,-3) != \'sql\' ) continue;
				closedir( $dir );
				return "./sql/$file";
			}
			closedir( $dir );
			return "";',
		check => 'return strlen($arg0) >= 4 ? true : false;'
    );

    $aDbConf['db_host'] = array(
		name => "Database host name",
		ex => "localhost",
		desc => "Your MySQL database host name here.",
		def => "localhost",
		check => 'return strlen($arg0) >= 1 ? true : false;'
	);

    $aDbConf['db_port'] = array(
		name => "Database host port number",
		ex => "5506",
		desc => "Leave blank or specify MySQL Database host port number.",
		def => "",
		check => ''
	);
	
    $aDbConf['db_sock'] = array(
		name => "Database socket path",
		ex => "/tmp/mysql50.sock",
		desc => "Leave blank or specify MySQL Database socket path.",
		def => "",
		check => ''
	);
	
	$aDbConf['db_name'] = array(
	    name => "Database name",
	    ex => "YourDatabaseName",
	    desc => "Your MySQL database name here.",
	    check => 'return strlen($arg0) >= 1 ? true : false;'
    );

	$aDbConf['db_user'] = array(
		name => "Database user",
		ex => "YourName",
		desc => "Your MySQL database read/write user name here.",
		check => 'return strlen($arg0) >= 1 ? true : false;'
	);

	$aDbConf['db_password'] = array(
		name => "Database password",
		ex => "YourPassword",
		desc => "Your MySQL database password here.",
		check => 'return strlen($arg0) >= 0 ? true : false;'
	);
	$aGeneral = array();
	$aGeneral['site_title'] = array(
		name => "Site Title",
		ex => "The Best Community",
		desc => "The name of your site",
		check => 'return strlen($arg0) >= 1 ? true : false;'
	);
	$aGeneral['site_email'] = array(
		name => "Site e-mail",
		ex => "your@email.here",
		desc => "Your site e-mail.",
		check => 'return strlen($arg0) > 0 AND strstr($arg0,"@") ? true : false;'
	);
	$aGeneral['notify_email'] = array(
		name => "Notify e-mail",
		ex => "your@email.here",
		desc => "Envelope \"From:\" address for notification messages",
		check => 'return strlen($arg0) > 0 AND strstr($arg0,"@") ? true : false;'
	);
	$aGeneral['bug_report_email'] = array(
		name => "Bug report email",
		ex => "your@email.here",
		desc => "Your email for receiving bug reports.",
		check => 'return strlen($arg0) > 0 AND strstr($arg0,"@") ? true : false;'
	);
	$aGeneral['admin_username'] = array(
		name => "Admin Username",
		ex => "admin",
		desc => "Specify the admin name here",
		check => 'return strlen($arg0) >= 1 ? true : false;'
		);
	$aGeneral['admin_password'] = array(
		name => "Admin Password",
		ex => "dolphin",
		desc => "Specify the admin password here",
		check => 'return strlen($arg0) >= 1 ? true : false;'
		);

/*----------Vars----------------*/
/*------------------------------*/


$sAction = $_REQUEST['action'];
$sError = '';

$cont = PageContent( $sError );

mb_internal_encoding('UTF-8');

echo PageHeader( $sAction, $sError );
echo $cont;
echo PageFooter( $sAction );

function PageContent( &$sError )
{
	global $aConf, $permDirectories, $permFiles, $reversalDirectories, $reversalFiles;
	global $confFirst, $aDbConf, $aGeneral, $aRayFolders, $aRayFiles;

	$ret = '';
	switch( $_REQUEST['action'] )
	{
		case 'step7':
			$ret .= loadDolphin();
			break;
			
		case 'step6':
			$dirName = '';
			$fileName = '';
			$errorMessage = '';

			foreach ($reversalDirectories as $dir)
			{
				if ( isFullAccessible('../'.$dir) )
				{
					//$passOk = false;
					$dirName .= '&nbsp;&nbsp;&nbsp;' . $dir . ';<br />';
				}
			}
			if( strlen( $dirName ) )
			{
				$sError = 'error';
				$errorMessage .= '<strong>Next directories have inappropriate permissions</strong>:<br />' . $dirName;
			}
			foreach ( $reversalFiles as $file )
			{
				if ( isRWAccessible ('../'.$file) )
				{
					//$passOk = false;
					$fileName .= '&nbsp;&nbsp;&nbsp;' . $file . ';<br /> ';
				}
			}
			if( strlen( $fileName ) )
			{
				$sError = 'error';
				$errorMessage .= '<strong>Next files have inappropriate permissions</strong>:<br />' . $fileName;
			}

			if( strlen( $errorMessage ) )
			{
				$ret .= showPermissions ( $errorMessage );
			}
			else
			{
				$ret .= loadDolphin();
			}
		break;
		
		
		
		case 'step5':
			$ret .= showPermissions();
		break;

		case 'step4':
			$errorMessage = '';
			foreach ( $aGeneral as $key => $value )
			{
				if ( !strlen($value['check']) ) continue;
				$funcbody = $value['check'];
		        $func = create_function('$arg0', $funcbody);

		        if ( !$func($_POST[$key]) )
				{
					$errorMessage .= "Please, input valid data to <b>{$value['name']}</b> field<br />";
					$error_arr[$key] = 1;
					$passOk = false;
					unset($_POST[$key]);
				}
				else
					$error_arr[$key] = 0;
				$config_arr[$key]['def'] = $_POST[$key];
			}

			if( strlen( $errorMessage ) )
			{
				$sError = 'error';
				$ret .= installGeneralInfo( $errorMessage );
			}
			else
			{
				$ret .= installFinish();
			}
		break;
		
		case 'step3':
			$errorMessage = '';
			foreach ( $aDbConf as $key => $value )
			{
				if ( !strlen($value['check']) )
					continue;
				
				$funcbody = $value['check'];
		        $func = create_function('$arg0', $funcbody);

		        if ( !$func($_POST[$key]) )
				{
					$errorMessage .= "Please, input valid data to <b>{$value['name']}</b> field<br />";
					$error_arr[$key] = 1;
					$passOk = false;
					unset($_POST[$key]);
				}
				else
					$error_arr[$key] = 0;
				$config_arr[$key]['def'] = $_POST[$key];
			}

			if( strlen( $errorMessage ) )
			{
				$sError = 'error';
				$ret .= installDBConf( $errorMessage );
			}
			else
			{
				$ret .= installGeneralInfo();
			}
		break;
		
		case 'step2':
			$errorMessage = '';
			foreach ( $confFirst as $key => $value )
			{
				if ( !strlen($value['check']) ) continue;
				$funcbody = $value['check'];
		        $func = create_function('$arg0', $funcbody);

		        if ( !$func($_POST[$key]) )
				{
					$errorMessage .= "Please, input valid data to <b>{$value['name']}</b> field<br />";
					$error_arr[$key] = 1;
					$passOk = false;
					unset($_POST[$key]);
				}
				else
					$error_arr[$key] = 0;
				$config_arr[$key]['def'] = $_POST[$key];
			}

			if( strlen( $errorMessage ) )
			{
				$sError = 'error';
				$ret .= installStepFirst( $errorMessage );
			}
			else
			{
				$ret .= installDBConf();
			}

		break;
		
		case 'step1':
			$dirName = '';
			$errorMessage = '';
			foreach ($permDirectories as $dir)
			{
				if ( !isFullAccessible('../'.$dir) )
				{
					//$passOk = false;
					$dirName .= '&nbsp;&nbsp;&nbsp;' . $dir . ';<br />';
				}
			}
			
			foreach ($aRayFolders as $dir)
			{
				if ( !isFullAccessible('../'.$dir) )
				{
					//$passOk = false;
					$dirName .= '&nbsp;&nbsp;&nbsp;' . $dir . ';<br />';
				}
			}
			
			if( strlen( $dirName ) )
			{
				$sError = 'error';
				$errorMessage .= '<strong>Next directories have inappropriate permissions</strong>:<br />' . $dirName;
			}
			
			foreach ($permFiles as $file)
			{
				if ( !isRWAccessible('../'.$file) )
				{
					//$passOk = false;
					$fileName .= '&nbsp;&nbsp;&nbsp;' . $file . ';<br /> ';
				}
			}

			foreach( $aRayFiles as $file )
			{
				if (strpos($file,'ffmpeg') === FALSE)
				{
					if ( !isRWAccessible('../'.$file) )
					{
						//$passOk = false;
						$fileName .= '&nbsp;&nbsp;&nbsp;' . $file . ';<br /> ';
					}
				}
				else
				{
					if ( !isFullAccessible('../'.$file) )
					{
						//$passOk = false;
						$fileName .= '&nbsp;&nbsp;&nbsp;' . $file . ';<br /> ';
					}
				}
			}
			
			if( strlen( $fileName ) )
			{
				$sError = 'error';
				$errorMessage .= '<strong>Next files have inappropriate permissions</strong>:<br />' . $fileName;
			}

			if( strlen( $errorMessage ) )
			{
				$ret .= PreInstall( $errorMessage );
			}
			else
			{
				$ret .= installStepFirst();
			}
		break;

		case 'preInstall':
			$ret .= PreInstall();
		break;
		
		default:
			$ret .= StartInstall();
	}


	return $ret;
}

function installFinish( $errorMessage = '' )
{
	global $aConf,  $confFirst, $aDbConf, $aGeneral;
	global $reversalDirectories, $reversalFiles;

	$sAdminName = $_REQUEST['admin_username'];
	$sAdminPassword = $_REQUEST['admin_password'];
	$resRunSQL = RunSQL( $sAdminName, $sAdminPassword );

	$sRet = '';
	
	if( 'done' ==  $resRunSQL )
	{
		$sRet = '
		<div class="formKeeper">
			<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
				<input type="image" src="images/next.gif" />
				<input type="hidden" name="action" value="step5" />
			</form>
		</div>
		<div class="clearBoth"></div>';
	}
	else
	{
		$sRet = $resRunSQL;
		$sRet .= '
		<div class="formKeeper">
			<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
				<input type="image" src="images/back.gif" />';
		foreach( $_POST as $sKey => $sValue )
		{
			if( $sKey != "action" )
				$sRet .= '<input type="hidden" name="' . $sKey . '" value="' . $sValue . '" />';
		}
			$sRet .= '<input type="hidden" name="action" value="step2" />
			</form>
		</div>
		<div class="clearBoth"></div>';
		return $sRet;
	}
	
	foreach ( $confFirst as $key => $val )
	{
		$aConf['headerTempl'] = str_replace ( "%$key%", $_POST[$key], $aConf['headerTempl'] );
	}
	foreach ( $aDbConf as $key => $val )
	{
		$aConf['headerTempl'] = str_replace ( "%$key%", $_POST[$key], $aConf['headerTempl'] );
	}
	foreach ( $aGeneral as $key => $val )
	{
		$aConf['headerTempl'] = str_replace ( "%$key%", $_POST[$key], $aConf['headerTempl'] );
	}

	$aConf['periodicTempl'] = str_replace ( "%site_email%", $_POST['site_email'], $aConf['periodicTempl'] );
	$aConf['periodicTempl'] = str_replace ( "%dir_root%",   $_POST['dir_root'],   $aConf['periodicTempl'] );
	$aConf['periodicTempl'] = str_replace ( "%dir_php%",    $_POST['dir_php'],    $aConf['periodicTempl'] );

	$ret = '';
	$ret .= '<div class="position">Cron Jobs</div>';
	$ret .= '<div class="LeftRirght">';
				$fp = fopen ( $aConf['dolFile'], 'w');
				if ( $fp )
				{
					fputs ( $fp, $aConf['headerTempl'] );
					fclose ( $fp );
					chmod( $aConf['dolFile'], 0666 );
					//$ret .='Config file was successfully written to <strong>' . $aConf['dolFile'] . '</strong><br />';
				}
				else
				{
					$text = 'Warning!!! can not get write access to config file ' . $aConf['dolFile'] . '. Here is config file</font><br>';
					$ret .= printInstallError( $text );
					$trans = get_html_translation_table(HTML_ENTITIES);
					$templ = strtr($aConf['headerTempl'], $trans);
					$ret .= '<textarea cols="20" rows="10" class="headerTextarea">' . $aConf['headerTempl'] . '</textarea>';
				}
				$fp = fopen ( $aConf['periodicFile'], 'w');
				if ( $fp )
				{
					fputs ( $fp, $aConf['periodicTempl'] );
					fclose ( $fp );
					chmod( $aConf['dolFile'], 0666 );
					//$ret .='Config file was successfully written to <strong>' . $aConf['periodicFile'] . '</strong><br />';
					$ret .= '<div class="left">
							Please, setup Cron Jobs as specified below. Helpful info about Cron Jobs is <a href="http://www.boonex.net/dolphin/wiki/DetailedInstall#SettingupCronjobs">available here</a>.';
					$ret .= '</div>';

					$ret .= '<div class="debug">';
						$aFileLines = file( $aConf['periodicFile'] );
						foreach( $aFileLines as $sLine )
							$ret .= $sLine . '<br />';
					$ret .= '</div>';
				}
				else
				{
					$text = '<font color=red>Warning!!! can not get write access to config file ' . $aConf['periodicFile'] . '. Here is config file</font><br>';
					$ret .= printInstallError( $text );
					$trans = get_html_translation_table(HTML_ENTITIES);
					$templ = strtr($aConf['periodicTempl'], $trans);
					$ret .= '<textarea cols="20" rows="10" class="headerTextarea">' . $aConf['periodicTempl'] . '</textarea>';
				}
				@rewriteFile( '[path_to]', $_POST['dir_root'], $aConf['cmdFile'] );
				@rewriteFile( '[path_to]', $_POST['dir_root'], $aConf['notifiesFile'] );
				@rewriteFile( '[path_to]', $_POST['dir_root'], $aConf['cupidFile'] );
				//@rewriteFile( '[path_to]', $_POST['dir_root'], $aConf['tagsFile'] );
				@rewriteFile( '[path_to]', $_POST['dir_root'], $aConf['rayHeader'] );

				$ret .= $sRet;
				
			$ret .= '</div>';

	return $ret;
}

function showPermissions( $errorMessage='' )
{
	global $reversalDirectories, $reversalFiles;
	
	$ret .= '<div class="position">Permissions Reversal</div>';

	if( strlen( $errorMessage ) )
	{
		$ret .= printInstallError( $errorMessage );
	}

	$ret .= '<div class="LeftRight">';
	$ret .= '<div class="clearBoth"></div>';	
	$ret .= '<div class="left">Now, when Dolphin completed installation, you should change permissions for some files to keep your site secure. Please, change permissions as specified in the chart below. Helpful info about permissions is <a href="http://www.boonex.net/cgi-bin/trac_dolphin.cgi/wiki/DetailedInstall#Permissions" target="_blank">available here</a>.</div>';
		$ret .= '<div class="right">
			<table cellpadding="0" cellspacing="1" width="100%" border="0" style="background-color:silver;">
			<tr class="head">
				<td>Directories</td>
				<td>Current Level</td>
				<td>Desired Level</td>
			</tr>';
			
			$i = 0;
			foreach($reversalDirectories as $dir)
			{
				if( ($i%2) == 0 )
				{
					$styleAdd = 'background-color:#ede9e9;';
				}
				else
				{
					$styleAdd = 'background-color:#fff;';
				}
				$ret .= '<tr style="' . $styleAdd . '" class="cont">';
					$ret .= '<td>' . $dir . '</td>';
					$ret .= '<td class="span">';
						if ( isFullAccessible('../'.$dir) )
						{
							$ret .= '<span class="unwritable">' . getPermissions( '../' . $dir ) . '</span><span>Writable</span>';
						}
						else
						{
							$ret .= '<span class="writable">' . getPermissions( '../' . $dir ) . '</span><span>Non-writable</span>';
						}
					$ret .= '</td>';
					$ret .= '<td class="span">';
						$ret .= '<span class="desired">755</span><span>Non-writable</span>';
					$ret .= '</td>';
				$ret .= '</tr>';
				$i ++;
			}
			
			$ret .= '<tr class="head">
				<td>Files</td>
				<td>Current Level</td>
				<td>Desired Level</td>
			</tr>
			';
			$i = 0;
			foreach($reversalFiles as $file)
			{

				$str = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_NAME'];
				$dir = preg_replace("/install\/(index\.php$)/","",$str);

				if( file_exists(  $dir . $file) )
				{
					if( ($i%2) == 0 )
					{
						$styleAdd = 'background-color:#ede9e9;';
					}
					else
					{
						$styleAdd = 'background-color:#fff;';
					}
					$ret .= '<tr style="' . $styleAdd . '" class="cont">';
						$ret .= '<td>' . $file . '</td>';
						$ret .= '<td class="span">';
						if ( isRWAccessible('../'.$file) )
						{
							$ret .= '<span class="unwritable">' . getPermissions( '../' . $file ) . '</span><span>Writable</span>';
						}
						else
						{
							$ret .= '<span class="writable">' . getPermissions( '../' . $file ) . '</span><span>Non-writable</span>';
						}
						$ret .= '</td>';
						$ret .= '<td class="span">';
							$ret .= '<span class="desired">644</span><span>Non-writable</span>';
						$ret .= '</td>';
					$ret .= '</tr>';
					$i ++;
				}
			}
			$ret .= '
					</table>
					<div class="formKeeper1">
						<div class="button_area_1">
							<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
								<input id="button" type="image" src="images/check.gif" />
								<input type="hidden" name="action" value="step5" />
							</form>
						</div>';
					$ret .= '
						<div class="button_area_1">
							<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
								<input id="button" type="image" src="images/next.gif" />
								<input type="hidden" name="action" value="step6" />
							</form>
						</div>';
					$ret .= '
						<div class="button_area_2">
							<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
								<input id="button" type="image" src="images/skip.gif" />
								<input type="hidden" name="action" value="step7" />
							</form>
						</div>
					</div>
				</div>
		<div class="clearBoth"></div>
	</div>';
	
	return $ret;
}

function installGeneralInfo( $errorMessage = '' )
{
	global $aGeneral;

	$ret = '';
	$ret .= '<div class="position">Configuration</div>';
	if( strlen( $errorMessage ) )
	{
		$ret .= printInstallError( $errorMessage );
		unset($_POST['site_title']);
		unset($_POST['site_email']);
		unset($_POST['notify_email']);
		unset($_POST['bug_report_email']);
	}
	$ret .= '<div class="LeftRirght">
		<div class="clearBoth"></div>
		<div class="left">
		</div>
		<div class="right">
		<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
			<table cellpadding="0" cellspacing="1" width="100%" border="0" style="background-color:silver;">
				<tr class="head">
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>';
				$ret .= createTable( $aGeneral );
		$ret .= '</table>
		<div class="formKeeper">
			<input id="button" type="image" src="images/next.gif" />
			<input type="hidden" name="action" value="step4" />';
			foreach($_POST as $postKey => $postValue)
			{
				if( 'action' == $postKey || isset( $aGeneral[$postKey] ) )
				{
					$ret .= '';
				}
				else
				{
					$ret .= '<input type="hidden" name="' . $postKey . '" value="' . $postValue . '" />';
				}

			}
		$ret .= '</div>
		</form>
		</div>
		';
	$ret .= '<div class="clearBoth"></div>
	</div>';

	return $ret;
}


function installDBConf( $errorMessage = '')
{
	global $aDbConf;

	$ret = '';
	$ret .= '<div class="position">Database</div>';
	if( strlen( $errorMessage ) )
	{
		$ret .= printInstallError( $errorMessage );
		unset($_POST['db_name']);
		unset($_POST['db_user']);
		unset($_POST['db_password']);
	}
	$ret .= '<div class="LeftRirght">
		<div class="clearBoth"></div>
		<div class="left">
		Please <a href="http://www.boonex.net/cgi-bin/trac_dolphin.cgi/wiki/DetailedInstall#Step2:CreateaDatabaseandaUser">create a database</a> and tell Dolphin about it.		
		</div>
		<div class="right">
		<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
			<table cellpadding="0" cellspacing="1" width="100%" border="0" style="background-color:silver;">
				<tr class="head">
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>';
				$ret .= createTable( $aDbConf );
		$ret .= '</table>
		<div class="formKeeper">
			<input id="button" type="image" src="images/next.gif" />
			<input type="hidden" name="action" value="step3" />';
			foreach($_POST as $postKey => $postValue)
			{
				if( 'action' == $postKey || isset( $aDbConf[$postKey] ) )
				{
					$ret .= '';
				}
				else
				{
					$ret .= '<input type="hidden" name="' . $postKey . '" value="' . $postValue . '" />';
				}

			}
		$ret .= '</div>
		</form>
		</div>
		';
	$ret .= '<div class="clearBoth"></div>
	</div>';


	return $ret;
}

function installStepFirst( $errorMessage = '' )
{
	global  $aConf, $confFirst;

/*-------------------------------*/


/*-------------------------------*/

	$ret = '';
	$ret .= '<div class="position">Paths Check</div>';
	if( strlen( $errorMessage ) )
	{
		$ret .= printInstallError( $errorMessage );
	}
	$ret .= '<div class="LeftRirght">';
		$ret .= '<div class="clearBoth"></div>';
		$ret .= '<div class="left">';
			$ret .= 'Dolphin checks general script paths.';
		$ret .= '</div>';
		$ret .= '<div class="right">
			<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
		';
		$ret .= '<table cellpadding="0" cellspacing="1" width="100%" border="0" style="background-color:silver;">
				<tr class="head">
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>';
				$ret .= createTable( $confFirst );
				$ret .= '
					<tr class="cont" style="background-color:#ede9e9;">
						<td>
							Check GD Installed
						</td>
						<td>';
							if ( extension_loaded( 'gd' ) )
							{
								$ret .= '<span class="writable">GD library installed</span>';
							}
							else
							{
								$ret .= '<span class="unwritable">GD library NOT installed</span>';
							}

				$ret .= '</td>
					</tr>
				';

		$ret .= '</table>
		<div class="formKeeper">
			<input id="button" type="image" src="images/next.gif" />
				<input type="hidden" name="action" value="step2" />
			</div>
				</form>';
		$ret .= '</div>';
		$ret .= '<div class="clearBoth"></div>';


	$ret .= '</div>';

	return $ret;
}


function PreInstall( $errorMessage = '' )
{
	global $aConf, $permFiles, $permDirectories, $aRayFolders, $aRayFiles;
	
	if ( ini_get('safe_mode') == 1 || ini_get('safe_mode') == 'On' )
	{
		$errorMessage .= "Please turn off <b>safe_mode</b> in your php.ini file configuration";
	}

	$ret = '';
	$ret .= '<div class="position">Permissions</div>';
	if( strlen( $errorMessage ) )
	{
		$ret .= printInstallError( $errorMessage );
	}
	$ret .= '<div class="LeftRirght">';
		$ret .= '<div class="clearBoth"></div>';
		$ret .= '<div class="left">
			Dolphin needs special access for certain files and directories. Please, change permissions as 
specified in the chart below. Helpful info about permissions is <a href="http://www.boonex.net/cgi-bin/trac_dolphin.cgi/wiki/DetailedInstall#Permissions" target="_blank">available here</a>.
		</div>';
		$ret .= '<div class="clear_both"></div>';
		$ret .= '<div class="right">
			<table cellpadding="0" cellspacing="1" width="100%" border="0" style="background-color:silver;">
				<tr class="head">
					<td>Directories</td>
					<td>Current Level</td>
					<td>Desired Level</td>
				</tr>';
				$i = 0;
				asort( $permDirectories );
				asort( $permFiles );
				foreach($permDirectories as $dir)
				{
					if( ($i%2) == 0 )
					{
						$styleAdd = 'background-color:#ede9e9;';
					}
					else
					{
						$styleAdd = 'background-color:#fff;';
					}
					$ret .= '<tr style="' . $styleAdd . '" class="cont">';
						$ret .= '<td>' . $dir . '</td>';
						$ret .= '<td class="span">';
							if ( isFullAccessible('../'.$dir) )
							{
								$ret .= '<span class="writable">' . getPermissions( '../' . $dir ) . '</span><span>Writable</span>';
							}
							else
							{
								$ret .= '<span class="unwritable">' . getPermissions( '../' . $dir ) . '</span><span>Non-writable</span>';
							}
						$ret .= '</td>';
						$ret .= '<td class="span">';
							$ret .= '<span class="desired">777</span><span>Writable</span>';
						$ret .= '</td>';
					$ret .= '</tr>';
					$i ++;
				}
				$ret .= '<tr class="head">
					<td>Files</td>
					<td>Current Level</td>
					<td>Desired Level</td>
				</tr>
				';
				$i = 0;
				foreach($permFiles as $file)
				{
					$str = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_NAME'];
					$dir = preg_replace("/install\/(index\.php$)/","",$str);

					if( file_exists(  $dir . $file) )
					{
						if( ($i%2) == 0 )
						{
							$styleAdd = 'background-color:#ede9e9;';
						}
						else
						{
							$styleAdd = 'background-color:#fff;';
						}
						$ret .= '<tr style="' . $styleAdd . '" class="cont">';
							$ret .= '<td>' . $file . '</td>';
							$ret .= '<td class="span">';
								if ( isRWAccessible('../'.$file) )
								{
									$ret .= '<span class="writable">' . getPermissions( '../' . $file ) . '</span><span>Writable</span>';
								}
								else
								{
									$ret .= '<span class="unwritable">' . getPermissions( '../' . $file ) . '</span><span>Non-writable</span>';
								}
							$ret .= '</td>';
							$ret .= '<td class="span">';
								$ret .= '<span class="desired">666</span><span>Writable</span>';
							$ret .= '</td>';
						$ret .= '</tr>';
						$i ++;
					}
				}
				
				$ret .= '<tr class="head">
					<td>Ray Folders</td>
					<td>Current Level</td>
					<td>Desired Level</td>
				</tr>';
				$i = 0;
				foreach($aRayFolders as $dir)
				{
					if( ($i%2) == 0 )
					{
						$styleAdd = 'background-color:#ede9e9;';
					}
					else
					{
						$styleAdd = 'background-color:#fff;';
					}
					$ret .= '<tr style="' . $styleAdd . '" class="cont">';
						$ret .= '<td>' . $dir . '</td>';
						$ret .= '<td class="span">';
							if ( isFullAccessible('../'.$dir) )
							{
								$ret .= '<span class="writable">' . getPermissions( '../' . $dir ) . '</span><span>Writable</span>';
							}
							else
							{
								$ret .= '<span class="unwritable">' . getPermissions( '../' . $dir ) . '</span><span>Non-writable</span>';
							}
						$ret .= '</td>';
						$ret .= '<td class="span">';
							$ret .= '<span class="desired">777</span><span>Writable</span>';
						$ret .= '</td>';
					$ret .= '</tr>';
					$i ++;
				}
				
				$ret .= '<tr class="head">
					<td>Ray Files</td>
					<td>Current Level</td>
					<td>Desired Level</td>
				</tr>
				';
				$i = 0;
				foreach( $aRayFiles as $file )
				{
					$str = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_NAME'];
					$dir = preg_replace("/install\/(index\.php$)/","",$str);

					if( file_exists(  $dir . $file) )
					{
						if( ($i%2) == 0 )
						{
							$styleAdd = 'background-color:#ede9e9;';
						}
						else
						{
							$styleAdd = 'background-color:#fff;';
						}
						$ret .= '<tr style="' . $styleAdd . '" class="cont">';
							$ret .= '<td>' . $file . '</td>';
							$ret .= '<td class="span">';
							if ( strpos($file,'ffmpeg') === FALSE )
							{
								if ( isRWAccessible('../'.$file) )
								{
									$ret .= '<span class="writable">' . getPermissions( '../' . $file ) . '</span><span>Writable</span>';
								}
								else
								{
									$ret .= '<span class="unwritable">' . getPermissions( '../' . $file ) . '</span><span>Non-writable</span>';
								}
							}
							else
							{
								if ( isFullAccessible('../'.$file) )
								{
									$ret .= '<span class="writable">' . getPermissions( '../' . $file ) . '</span><span>Executable</span>';
								}
								else
								{
									$ret .= '<span class="unwritable">' . getPermissions( '../' . $file ) . '</span><span>Unexecutable</span>';
									$bError = TRUE;
								}
							}
							$ret .= '</td>';
							$ret .= '<td class="span">';
								if ( strpos($file,'ffmpeg') === FALSE )
								{
									$ret .= '<span class="desired">666</span><span>Writable</span>';
								}
								else 
								{
									$ret .= '<span class="desired">777</span><span>Executable</span>';
								}
							$ret .= '</td>';
						$ret .= '</tr>';
						$i ++;
					}
				}
				
				

			$ret .= '
			</table>
			<div class="formKeeper">
				<div class="button_area_1">
					<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
						<input id="button" type="image" src="images/check.gif" />
						<input type="hidden" name="action" value="preInstall" />
					</form>
				</div>';
			$ret .= '
				<div class="button_area_2">
					<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
						<input id="button" type="image" src="images/next.gif" />
						<input type="hidden" name="action" value="step1" />
					</form>
				</div>
				<div class="clearBoth"></div>
			</div>
		</div>';

	$ret .= '</div>';

	return $ret;
}

function StartInstall()
{
	global $aConf;
	
	$ret .= '<div class="install_pic">';
		$ret .= 'Dolphin ' . $aConf['iVersion'] . '.' . $aConf['iPatch'];
	$ret .= '</div>';

	$ret .= '<div class="install_text">';
		$ret .= 'Thank you for choosing Dolphin Smart Community Builder!<br />';
		$ret .= 'Click the button below to create your own community.';
	$ret .= '</div>';
	
	$ret .= '<div class="install_button">';
		$ret .= '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
		$ret .= '<input id="button" type="image" src="images/install.gif" />';
		$ret .= '<input type="hidden" name="action" value="preInstall" />';
		$ret .= '</form>';
	$ret .= '</div>';
	
	return $ret;
}

function loadDolphin()
{
	$ret .= '<script type="text/javascript">
			window.location = "../index.php";
			</script>';
	return $ret;
}

function PageHeader( $sAction = '', $sError = '' )
{
	global $aConf;
	
	$aActions = array(
		"startInstall" => "Dolphin Installation",
		"preInstall" => "Permissions",
		"step1" => "Paths",
		"step2" => "Database",
		"step3" => "Configuration",
		"step4" => "Cron Jobs",
		"step5" => "Permissions Reversal"
	);
	if( !strlen( $sAction ) )
		$sAction = "startInstall";

	$ret = <<<EOJ
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
		<head>
			<title>Dolphin Smart Community Builder Installation Script</title>
			<link href="general.css" rel="stylesheet" type="text/css" />
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<script src="../inc/js/functions.js" type="text/javascript" language="javascript"></script>
			<!--[if lt IE 7.]>
			<script defer type="text/javascript" src="../inc/js/pngfix.js"></script>
			<![endif]-->
		</head>
		<body>
			<div id="main">
EOJ;
	
	if( $sAction == "step5" )
		$ret .= '<div id="topMenuActive">';
	else
		$ret .= '<div id="topMenuInactive">';

	$iCounterCurrent = 1;
	$iCounterActive	 = 1;

	foreach ( $aActions as $sActionKey => $sActionValue )
	{
		if( $sAction != $sActionKey )
		{
			$iCounterActive++;
		}
		else
			break;
	}

	if( strlen( $sError ) )
		$iCounterActive--;
				
	foreach ( $aActions as $sActionKey => $sActionValue )
	{
		if( $iCounterActive == $iCounterCurrent )
		{
			$ret .= '<div id="topActive">';
				$ret .= $sActionValue;
			$ret .= '</div>';
		}

		elseif( ($iCounterActive - $iCounterCurrent) == -1 )
		{
				$ret .= '<img src="images/active_inactive.gif" />';
			$ret .= '<div id="topInactive">';
				$ret .= $sActionValue;
			$ret .= '</div>';
			$ret .= '<img src="images/inactive_inactive.gif" />';
		}

		elseif( ($iCounterActive - $iCounterCurrent) == 1 )
		{
			$ret .= '<div id="topInactive">';
				$ret .= $sActionValue;
			$ret .= '</div>';
			$ret .= '<img src="images/inactive_active.gif" />';
		}
		
		else
		{
			$ret .= '<div id="topInactive">';
				$ret .= $sActionValue;
			$ret .= '</div>';
			if( $sActionKey != "step5" )
				$ret .= '<img src="images/inactive_inactive.gif" />';				
		}
		
		$iCounterCurrent++;
	}
		
	
	$ret .= '
				</div>
			<div id="header">
				<img src="images/boonex_logo.gif" alt="" /></div>
			<div id="content">';

	
	return $ret;
}

function PageFooter( $sAction )
{
	$ret = '
			</div>';
	
	if( $sAction )
		$ret .= '
			<div id="footer">
				<img src="images/dolphin_transparent.jpg" alt="" />
			</div>';
	
	$ret .= '
		</div>
	</body>
</html>';

	return $ret;
}

function printInstallError( $text )
{

	$ret .= '<div class="error">';
	$ret .= $text;
	$ret .= '</div>';



	return $ret;
}

function getPermissions( $filename )
{
	clearstatcache();
	$perms = fileperms($filename);
	$ret = substr( decoct( $perms ), -3 );
	return $ret;
}

function isFullAccessible($filename)
{
	clearstatcache();
	$perms = fileperms($filename);
	return ($perms & 0x0004 && $perms & 0x0002 && $perms & 0x0001 && !($perms & 0x0200)) ? true : false;
}

function isRWAccessible($filename)
{
	clearstatcache();
	$perms = fileperms($filename);
	return ($perms & 0x0004 && $perms & 0x0002) ? true : false;
}

function createTable( $arr )
{
	$ret = '';
	$i = '';
	foreach($arr as $key => $value)
	{
		if( ($i%2) == 0 )
		{
			$styleAdd = 'background-color:#ede9e9;';
		}
		else
		{
			$styleAdd = 'background-color:#fff;';
		}

		$def_exp_text = "";
		if ( strlen($value['def_exp']) )
		{
		    $funcbody = $value['def_exp'];
		    $func = create_function("", $funcbody);
		    $def_exp = $func();
			if ( strlen($def_exp) )
			{
				$def_exp_text = "&nbsp;<font color=green>found</font>";
				$value['def'] = $def_exp;
			}
			else
			{
				$def_exp_text = "&nbsp;<font color=red>not found</font>";
			}

		}

		if ( $error_arr[$key] == 1 )
		{
			$st_err = ' style="background-color:#FFDDDD;" ';
		}
		else $st_err = '';
		$ret .= '
		<tr class="cont" style="' . $styleAdd . '">
			<td>
			<div>
			' . $value['name'] . '
			</div>
			<div>
				Description:
			</div>
			<div>
				Example:
			</div>
			</td>
			<td>
				<div>
					<input ' . $st_err . ' size="30" name="' . $key . '" value="' . $value['def'] . '" />' . $def_exp_text . '
				</div>
				<div>
					' .  $value['desc'] . '
				</div>
				<div>
					' . $value['ex'] . '
				</div>
			</td>
		</tr>';
		$i ++;
	}

	return $ret;
}

function rewriteFile($sCode, $sReplace, $sFile)
{
	$ret = '';
	$fs = filesize( $sFile );
	$fp = fopen ( $sFile, 'r' );
	if ( $fp )
	{
		$fcontent = fread( $fp, $fs );
		$fcontent = str_replace( $sCode, $sReplace, $fcontent );
		fclose( $fp );
		$fp = fopen ( $sFile, 'w' );
		if ( $fp )
		{
			if( fputs ( $fp, $fcontent ) )
			{
				$ret .= true;
			}
			else
			{
				$ret .= false;
			}
			fclose ( $fp );
		}
		else
		{
			$ret .= false;
		}
	}
	else
	{
		$ret .= false;
	}

	return $ret;
}

function RunSQL( $sAdminName, $sAdminPassword )
{
	$db['host']        = $_POST['db_host'];
	$db['sock']        = $_POST['db_sock'];
	$db['port']        = $_POST['db_port'];
	$db['user']        = $_POST['db_user'];
	$db['passwd']      = $_POST['db_password'];
	$db['db']          = $_POST['db_name'];
	
	$db['host'] .= ( $db['port'] ? ":{$db['port']}" : '' ) . ( $db['sock'] ? ":{$db['sock']}" : '' );
	
	$pass = true;
	$errorMes = '';
	$filename = $_POST['sql_file'];

	$link = @mysql_connect ( $db['host'], $db['user'], $db['passwd']  );

	if( !$link )
		return printInstallError( mysql_error() );
		//return ("<font color=red>Could not connect to MySQL server: " . mysql_error()."</font>");

	if (!mysql_select_db ($db['db'], $link))
		return printInstallError( $db['db'] . ': ' . mysql_error() );
        //return ("<font color=red>Could not select database '{$db['db']}': " . mysql_error()."</font>");

    if ( !($f = fopen ( $filename, "r" )) )
    	return printInstallError( 'Could not open file with sql instructions:' . $filename  );
       //return ("<font color=red>Could not open file with sql instructions: $filename </font>");


	//Begin SQL script executing
	$s_sql = "";
	while ( $s = fgets ( $f, 10240) )
	{
		$s = trim( $s ); //Utf with BOM only

		if( !strlen( $s ) ) continue;
		if ( mb_substr( $s, 0, 1 ) == '#'  ) continue; //pass comments
		if ( mb_substr( $s, 0, 2 ) == '--' ) continue;
		if ( substr( $s, 0, 5 ) == "\xEF\xBB\xBF\x2D\x2D" ) continue;
		
		$s_sql .= $s;
		
		if ( mb_substr( $s, -1 ) != ';'    ) continue;
		
		$res = mysql_query ( $s_sql, $link );
		if ( !$res )
			$errorMes .= 'Error while executing: ' . $s_sql  . '<br />' . mysql_error() . '<hr />';
		
		$s_sql = "";
	}
	mysql_query ( "UPDATE `Admins` SET `Name`='{$sAdminName}', `Password`=md5( '{$sAdminPassword}' ) WHERE `Name`='admin'", $link );
	if ( !$res )
		$errorMes .= 'Error while executing: ' . $s_sql  . '<br />' . mysql_error() . '<hr />';

    fclose($f);

	$enable_gd_value = extension_loaded( 'gd' ) ? 'on' : '';
	if ( !(mysql_query ( "UPDATE `GlParams` SET `VALUE`='$enable_gd_value' WHERE `Name`='enable_gd'", $link ) ) )
		$ret .= "<font color=red><i><b>Error</b>:</i> ".mysql_error()."</font><hr>";

    mysql_close($link);

    $errorMes .= $ret;

    if( strlen( $errorMes ) )
    {
    	return printInstallError( $errorMes );
    }
    else
    {
    	return 'done';
    }

//    return $ret."Truncating tables finished.<br>";
}

// set error reporting level
error_reporting(E_ALL & ~E_NOTICE);
?>