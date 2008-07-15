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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$logged['admin'] = member_auth( 1, true, true );
$_page['header'] = 'Edit CSS File';

$path = "{$dir['root']}templates/tmpl_{$tmpl}/css/";
$fname = preg_match("/^\w+\.css$/", $_POST['css_file']) ? $_POST['css_file'] : '';

$action_result = "";
$demo_text = 'This Action Restricted in Demo Mode';

if ( isset($_POST['save']) )
{
	if ( !$demo_mode )
	{
		$file_exists = file_exists( $path . $fname );

		if ( $file_exists && isRWAccessible($path . $fname) )
		{
			$fp = fopen( $path . $fname, 'w');
			$content = process_pass_data( $_POST['content'] );
			if ( $fp )
			{
				fwrite( $fp, $content );
				fclose( $fp );
				$action_result = "File {$fname} was successfully saved";
			}
			else
			{
				$action_result = "Fail to save file {$fname}";
			}
		}
		else
		{
			$action_result = "File {$fname} is not writable";
		}
	}
	else
	{
		$action_result = $demo_text;
	}

}

TopCodeAdmin();
ContentBlockHead("");

if ( strlen($action_result) )
	echo "
<center>
	<div class=\"err\" style=\"margin: 5px;\">{$action_result}</div>
</center>";
?>

<div style="margin-left: auto; margin-right: auto; text-align: center;">
	<form action="<?= $_SERVER['PHP_SELF'] ?>" name="css_file" method="post" style="margin: 0px;">
		Select CSS file you want edit:&nbsp;<?= getDirContent( $path, "document.forms['css_file'].submit();", $fname ) ?>
<?

if ( strlen($fname) && $fname != '0' && file_exists($path.$fname) && is_file($path.$fname) )
{
	$fp = fopen( $path . $fname, 'r');
	$content = '';
	if ( $fp )
	{
		while ( !feof($fp) )
			$content .= fgets($fp, 4096);
		fclose( $fp );
	}
?>
		<br /><br />
		<div style="padding-left: 4px; padding-right: 4px; text-align: left;">
			Editing file <b><?= $fname ?></b>: <?= ( !isRWAccessible( $path . $fname ) ? "<span class=\"err\" style=\"margin-left:5px;\">File {$fname} is not writable</span>"
 : "<span class=\"succ\" style=\"margin-left:5px;\">File {$fname} has write permissions</span>" ) ?><br />
			<textarea cols="81" rows="30" name="content" style="font-family: 'Courier New'; font-size: 11px;"><?= htmlspecialchars($content) ?></textarea>
		</div>
		<br />
		<input type="submit" name="save" class="no" value="Save" style="width: 60px" />
<?
}
?>
	</form>
</div>
<?

ContentBlockFoot();
BottomCode();

function getDirContent( $path, $onchange, $selItem )
{
	$handle = opendir( $path );

	$res = "<select name=\"css_file\" onchange=\"{$onchange}\">\n";
	$res .= "<option value=\"0\" ". (!strlen($selItem) ? 'selected="selected"' : '') .">-- select file --</option>\n";

	while ( false !== ($filename = readdir($handle)) )
	{
		if ( is_file($path . $filename) && 'css' == substr($filename, -3) )
			$res .= "<option value=\"$filename\" ". ($filename == $selItem ? 'selected="selected"' : '') . ">{$filename}</option>\n";
	}

	$res .= "</select>\n";

	closedir( $handle );

	return $res;
}

?>