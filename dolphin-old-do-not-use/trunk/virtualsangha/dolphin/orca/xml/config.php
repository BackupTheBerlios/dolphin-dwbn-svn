<?php

/**
 *
 * Overwrite necessary variables or add new in this file
 *
 *******************************************************************************/

$dir = array ();

require_once ('./../inc/header.inc.php');

$path = $dir['root'] . 'orca/'; // path to orca files

/**
 * directories configuration
 */ 
$gConf['dir']['error_log'] = $path . 'log/orca.error.log'; // error log file path
$gConf['dir']['classes'] = $path . 'classes/'; // classes directiry path
$gConf['dir']['js'] = $path . 'js/'; // js directiry path
$gConf['dir']['inc'] = $path . 'inc/';	// include files path
$gConf['dir']['xmlcache'] = $path . 'xml/'; // not used
$gConf['dir']['xml'] = $path . 'xml/'; // path to integratiom directory
$gConf['dir']['base'] = $path;  // base dir
$gConf['dir']['cache'] = $path . 'cachejs/'; // js files cache 
$gConf['dir']['config'] = $path . 'conf/params.conf'; // config
$gConf['dir']['layouts'] = $path . 'layout/'; // layouts dir
$gConf['dir']['integration'] = $path . 'xml/orca.php'; // xml integration file path
$gConf['dir']['editor'] = $dir['plugins'] . 'tiny_mce/'; // path to javascript editor
$gConf['dir']['langs'] = $path . 'xml/langs/'; // lang files locaiton


/**
 * skin configuration
 */ 
$skin = isset($_GET['skin']) && $_GET['skin'] ? $_GET['skin'] : (isset($_COOKIE['skin']) ? $_COOKIE['skin'] : '');
if (preg_match("/^\w+$/",$skin) && file_exists($path . 'layout/' . $skin))
{
	$gConf['skin'] = $skin;
	setcookie ('skin', $skin);
}
else
{
	$gConf['skin'] = 'uni';
}

/**
 * language configuration
 */ 
$gConf['lang'] = isset($_GET['lang']) && $_GET['lang'] ? $_GET['lang'] : (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : '');
if (!preg_match("/^[a-z]{2}$/",$gConf['lang']))
{
    $gConf['lang'] = 'en';
}

if (file_exists($path . 'layout/base_' . $gConf['lang']))
{
	setcookie ('lang', $gConf['lang']);
}
else
{
	$gConf['lang'] = 'en';
}


/**
 * urls configuration
 */ 
$gConf['url']['base'] = $site['url'] . 'orca/';	// base url
$gConf['url']['layouts'] = $gConf['url']['base'] . 'layout/'; // layouts url
$gConf['url']['integration'] = $gConf['url']['base'] . 'xml/orca.php'; // xml integration file url
$gConf['url']['js'] = $gConf['url']['base'] . 'js/'; // layouts url
$gConf['url']['editor'] = $site['plugins'] . 'tiny_mce/'; // url to javascript editor

/**
 * langs pathes configuration
 */ 
if ($gConf['lang'] && file_exists($path . 'layout/base_' . $gConf['lang']))
{
    $gConf['dir']['classes'] = $gConf['dir']['classes'] . $gConf['lang'] . '/';
    $gConf['dir']['js'] = $gConf['dir']['js'] . $gConf['lang'] . '/';
    $gConf['url']['js'] = $gConf['url']['js'] . $gConf['lang'] . '/';
    $gConf['skin'] = $gConf['skin'] . '_' . $gConf['lang'];
}

/**
 * include custom template patches
 */ 
require_once ($gConf['dir']['layouts'] . $gConf['skin'] . '/params.php'); 


/**
 * database configuration
 */ 
$gConf['db']['host'] = DATABASE_HOST;	// hostname
$gConf['db']['db'] = DATABASE_NAME;		// database name
$gConf['db']['user'] = DATABASE_USER;	// database username
$gConf['db']['pwd'] = DATABASE_PASS;	// database password
$gConf['db']['port'] = DATABASE_PORT;    // database port
$gConf['db']['sock'] = DATABASE_SOCK;    // database socket
$gConf['db']['prefix'] = 'pre_';       // tables names prefix



function isXsltEnabled ()
{
	if (((int)phpversion()) >= 5)
	{				

		if (class_exists ('DOMDocument') && class_exists ('XsltProcessor'))
			return true;
	}
	else
	{

		if (function_exists('domxml_xslt_stylesheet_file'))
			return true;
		elseif (function_exists ('xslt_create'))
			return true;
	}
	return false;
}

if ('auto' == $gConf['xsl_mode'])
{
	$gConf['xsl_mode'] = isXsltEnabled() ? 'server' : 'client';
}


$gConf['integration'] = 'file'; // url - read integration setting from url, file - read integration settings from file

?>
