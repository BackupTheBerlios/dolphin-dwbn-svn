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

/**
 * ATTENTION! DO NOT CHANGE THIS FILE!
 * Use Global Settings in Admin Panel instead!
 */

/**
 * Vars from Variables Category
 */




$doll = '$';
$en_sdating = 1;
//$en_dir	= 0;
$en_aff = 0;
//$en_up = 0;
$en_ziploc = 1;
$pic_num = 5;
//$max_voting_mark = 5;
$enable_friendlist = 1;
//$enable_auto_thumbnail = 1;

$en_inbox_notify = 0;
$date_format = '%m-%d-%y %H:%i';
$short_date_format = '%m-%d-%y';

$search_start_age = 18;
$search_end_age = 75;
$track_profile_view = 1;
$votes = 1;
$votes_pic = 1;
$anon_mode = 0;
$enable_zodiac = 0;
$newusernotify = 1;
$blog_step = 10;
$enable_blog = 1;
$enable_guestbook = 1;
$enable_couple = 0;

$enable_video_upload = 1;
$enable_audio_upload = 1;

$max_voting_mark = 5;
$min_voting_mark = 1;
$max_voting_period = 24;

$max_icon_width = 45;
$max_icon_height = 45;
$max_thumb_width = 110;
$max_thumb_height = 110;
$max_photo_width = 340;
$max_photo_height = 340;

$max_photo_files = 20;
$max_video_files = 20;
$max_video_size = 2;

$max_audio_files = 20;
$max_audio_size = 2;
$max_media_title = 150;
$min_media_title = 1;


$tmpl = 'uni';
// change skin



if ($_GET['skin'] && file_exists($dir['root'].'templates/tmpl_'.$_GET['skin']) && !is_file($dir['root'].'templates/tmpl_'.$_GET['skin']))
{
	$tmpl = $_GET['skin'];
	setcookie( "skin", $_GET['skin'], 0, '/' );
}
elseif ($_COOKIE['skin'] && file_exists($dir['root'].'templates/tmpl_'.$_COOKIE['skin']) && !is_file($dir['root'].'templates/tmpl_'.$_COOKIE['skin']))
	$tmpl = $_COOKIE['skin'];


if( $_COOKIE['memberID'] && file_exists($dir['cache'] . 'user' . $_COOKIE['memberID'] . '.php') && is_file($dir['cache'] . 'user' . $_COOKIE['memberID'] . '.php'))
	require_once( BX_DIRECTORY_PATH_CACHE . 'user' . (int)$_COOKIE['memberID'] . '.php');

require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplConfig.php" );
$oTemplConfig = new BxTemplConfig($site);

?>
