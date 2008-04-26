<?php
/***************************************************************************
*                            Orca Interactive Forum Script
*                              ---------------
*     Started             : Fr Nov 10 2006
*     Copyright        : (C) 2007 BoonEx Group
*     Website             : http://www.boonex.com
* This file is part of Orca - Interactive Forum Script
*
* Orca is free software; you can redistribute it and/or modify it under 
* the terms of the GNU General Public License as published by the 
* Free Software Foundation; either version 2 of the 
* License, or any later version.      
*
* Orca is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details. 
* You should have received a copy of the GNU General Public License along with Orca, 
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/


$gConf['ver'] = 'Orca-v.1.2b6';
$gConf['def_title'] = 'Orca :: Interactive Forum Script';

/**
 * directories configuration
 */ 
$path = ''; // path to orca files
$gConf['dir']['error_log'] = $path . 'log/orca.error.log'; // error log file path
$gConf['dir']['classes'] = $path . 'classes/'; // classes directiry path
$gConf['dir']['inc'] = $path . 'inc/';	// include files path
$gConf['dir']['xmlcache'] = $path . 'xml/'; // not used
$gConf['dir']['xml'] = $path . 'xml/'; // path to integratiom directory
$gConf['dir']['base'] = $path;  // base dir
$gConf['dir']['cache'] = $path . 'cachejs/'; // js files cache 
$gConf['dir']['config'] = $path . 'conf/params.conf'; // config

/**
 * urls configuration
 */ 
$gConf['url']['base'] = '';	// base url
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
	$gConf['skin'] = 'default';
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
$gConf['db']['host'] = 'localhost';		// hostname
$gConf['db']['db'] = ''; // database name
$gConf['db']['user'] = ''; // database username
$gConf['db']['pwd'] = ''; // database password
$gConf['db']['port'] = ''; 
$gConf['db']['sock'] = ''; 
$gConf['db']['prefix'] = 'pre_'; // tables names prefix

/**
 * forum tweaks
 */ 
$gConf['date_format'] = '%b %d, %Y %H:%i'; // time/date format
$gConf['topics_per_page'] = 20;	// topics per page
$gConf['topics_desc_len'] = 64;
$gConf['live_tracker_desc_len'] = 128;

$gConf['email']['sender'] = 'no-reply@example.com'; // email sender

$gConf['user']['admin'] = 'admin'; // admin user

$gConf['min_point'] = -4; // min points to hide post automatically

$gConf['online'] = 72000; // online user timeout (seconds) default: 20 min 

$xsl_mode = isset($_GET['xsl_mode']) && $_GET['xsl_mode'] ? $_GET['xsl_mode'] : (isset($_COOKIE['xsl_mode']) ? $_COOKIE['xsl_mode'] : '');
if (preg_match("/^\w+$/",$xsl_mode))
{
        $gConf['xsl_mode'] = $xsl_mode;
        setcookie ('xsl_mode', $xsl_mode);
}
else
{
        $gConf['xsl_mode'] = 'auto'; // client, server
}

require_once ('./xml/config.php');

?>