<?php

/**
 *
 * Overwrite necessary variables or add new in this file
 *
 *******************************************************************************/

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

/******************************************************************************/


$dir = array ();

require_once ('./../../inc/header.inc.php');

/**
 * directories configuration
 */ 
$path = $dir['root']  . 'groups/orca/'; // path to orca files
$gConf['dir']['error_log'] = $path . 'log/orca.error.log'; // error log file path
$gConf['dir']['classes'] = $path . 'classes/'; // classes directiry path
$gConf['dir']['inc'] = $path . 'inc/';	// include files path
$gConf['dir']['xmlcache'] = $path . 'xml/'; // not used
$gConf['dir']['base'] = $path;  // base dir
$gConf['dir']['cache'] = $path . 'cachejs/'; // js files cache 
$gConf['dir']['config'] = $path . '../../orca/log/params.conf'; // config

/**
 * urls configuration
 */ 
$gConf['url']['base'] = $site['url'] . 'groups/orca/';	// base url
$gConf['url']['xml'] = $gConf['url']['base'] . 'xml/orca.php'; // xml integration file url

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


$gConf['dir']['xsl'] = $path . 'layout/' . $gConf['skin'] . '/xsl/';	// xsl dir
$gConf['dir']['smile'] = $path . 'layout/' . $gConf['skin'] . '/smiles/';	// smiles dir


$gConf['url']['icon'] = $gConf['url']['base'] . 'layout/' . $gConf['skin'] . '/icons/';	// icons url
$gConf['url']['img'] = $gConf['url']['base'] . 'layout/' . $gConf['skin'] . '/img/';	// img url
$gConf['url']['css'] = $gConf['url']['base'] . 'layout/' . $gConf['skin'] . '/css/';	// css url
$gConf['url']['smile'] = $gConf['url']['base'] . 'layout/' . $gConf['skin'] . '/smiles/';	// smiles url
$gConf['url']['xsl'] = $gConf['url']['base'] . 'layout/' . $gConf['skin'] . '/xsl/';	// xsl url


/**
 * database configuration
 */ 
$gConf['db']['host'] = DATABASE_HOST; // hostname
$gConf['db']['db'] = DATABASE_NAME; // database name
$gConf['db']['user'] = DATABASE_USER; // database username
$gConf['db']['pwd'] = DATABASE_PASS; // database password
$gConf['db']['port'] = DATABASE_PORT; // database port
$gConf['db']['sock'] = DATABASE_SOCK; // database socket
$gConf['db']['prefix'] = 'grp_';

$gConf['email']['sender'] = $site['email_notify']; // email sender

?>
