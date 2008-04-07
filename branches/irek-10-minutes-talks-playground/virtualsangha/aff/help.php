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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );

if (getParam("enable_aff") != 'on')
{
	$sCode = MsgBox( _t( '_affiliate_system_was_disabled' ) );
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = $sCode;
	PageCode();
	exit();
}

$logged['aff'] = member_auth( 2 );
$AFF = (int)$_COOKIE['affID'];

// - GET variables --------------

$_page['header'] = "Affiliate's insructions page";
$_page['header_text'] = "Affiliate's insructions page";
$_page['js'] = 1;

TopCodeAdmin();
ContentBlockHead("Link");
?>

<table class="text" cellspacing=0 cellpadding=4 width=500>
	<tr>
		<td align="center">Place following link on your site:</td>
	</tr>
	<tr>
		<td align="center"><input class="no" size="60" value="<?= $site['url'] ?>?idAff=<?= $AFF ?>" readonly /></td>
	</tr>
</table>

<?
ContentBlockFoot();
$respd = db_res("SELECT * FROM `aff_banners` WHERE `Added` = '1' AND `Status` = 'Active' ORDER BY `ID` DESC");
$rows_num = mysql_num_rows($respd);
if ( $rows_num > 0 )
{
ContentBlockHead("Banners");
?>
<table class="text" align="center" cellpadding="4" cellspacing="2" width="500">
<?php
	while( $arrpd = mysql_fetch_array($respd) )
	{
		if ( strpos($arrpd['Banner'], 'swf') === false )
		{
			$link_code = "<div align=\"center\">
<!-- Start link code -->
<a href=\"{$site['url']}?idAff={$AFF}\"><img src=\"{$site['banners']}{$arrpd['Banner']}\" width=\"{$arrpd['XSize']}\" height=\"{$arrpd['YSize']}\" border=\"0\"></a><br />
<a href=\"{$site['url']}?idAff={$AFF}\">{$arrpd['Text']}</a>
<!-- End link code -->
</div>";
		}
		else
		{
			$size = getimagesize("{$site['banners']}{$arrpd['Banner']}");
			$link_code = "<div align=\"center\">
<!-- Start link code -->
<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" {$size[3]}>
	<param name=\"movie\" value=\"{$site['banners']}{$arrpd['Banner']}\" />
	<param name=\"quality\" value=\"high\" />
	<embed src=\"{$site['banners']}{$arrpd['Banner']}\" quality=\"high\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" {$size[3]} />
</object><br />
<a href=\"{$site['url']}?idAff={$AFF}\">{$arrpd['Text']}</a>
<!-- End link code -->
</div>";
		}
?>
	<tr>
		<td align=center>
			<b><?= $arrpd['BannerName'].' '.$arrpd['XSize'].'x'.$arrpd['YSize'];?></b>
		</td>
	</tr>
	<tr class="table1">
		<td align="center">
<?
		if( $arrpd['XSize'] > 400 )
		{
?>
			<a href="<?= $site['banners'] . $arrpd['Banner'] ?>">Large Banner</a>
<?
		}
		else
		{
?>
			<img align="middle" src="<?= $site['banners'] . $arrpd['Banner'] ?>">
<?
		}
?>
		</td>
	</tr>
	<tr>
		<td align="center"><?= $arrpd['Text'] ?></td>
	</tr>
	<tr>
		<td align="center">
			Place following banner code on your site: <br />
			<textarea name="textfield" cols="55" rows="10" readonly="all"><?= htmlspecialchars($link_code) ?></textarea>
		</td>
	</tr>
<?php
	}
?>
</table>
<?
ContentBlockFoot();
}

BottomCode();
?>