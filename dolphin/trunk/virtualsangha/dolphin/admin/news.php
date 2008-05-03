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
require_once( BX_DIRECTORY_PATH_INC . 'news.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$logged['admin'] = member_auth( 1, true, true );

$_page['css_name'] = 'news.css';

$_page['extraCodeInHead'] = '
	<!-- tinyMCE -->
	<script language="javascript" type="text/javascript" src="' . $site['plugins'] . 'tiny_mce/tiny_mce.js"></script>
	<script language="javascript" type="text/javascript">
	// Notice: The simple theme does not use all options some of them are limited to the advanced theme
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		content_css : "' . $site['base'] . 'css/tiny_mce.css",
		editor_selector : "articl",
		plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen",
		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,separator,forecolor,backcolor",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "emotions",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_disable : "link,unlink,insertanchor,image,subscript,superscript,help,anchor,styleselect",
		plugi2n_insertdate_dateFormat : "%Y-%m-%d",
	    plugi2n_insertdate_timeFormat : "%H:%M:%S",
		paste_use_dialog : false,
		theme_advanced_resizing : false,
		theme_advanced_resize_horizontal : false,
		theme_advanced_link_targets : "_something=My somthing;_something2=My somthing2;_something3=My somthing3;",
		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : false,
		paste_strip_class_attributes : "all",
		paste_remove_spans : false,
		paste_remove_styles : false

		});
	</script>
	<!-- /tinyMCE -->

';

function MemberPrintNews()
{
	global $site;
	global $short_date_format;
	
	$bNewsFriendly = getParam('permalinks_news') == 'on' ? true : false;
	
	$res = getNewsList();
	if ( !$res )
		return;

	echo "<table cellspacing=1 cellpadding=2 class=small width='100%'>\n";

	if ( !mysql_num_rows($res) )
	{
		echo "<tr class=panel><td align=center>No news available.</td></tr>\n";
	}

	while ( $news_arr = mysql_fetch_array($res) )
	{
		$news_header = process_line_output( $news_arr['Header'] );
		$sNewsUrl = getNewsUrl($news_arr['newsID'], $news_arr['NewsUri'], $bNewsFriendly);
		echo "
			<tr >
				<td align=center width=15%>
					<a href=\"news.php?edit_id={$news_arr['newsID']}\">Edit</a> |
					<a href=\"news.php?action=delete&delete_id={$news_arr['newsID']}\">Delete</a>
				</td>
				<td align=center width=15%>".date( str_replace('%','',$short_date_format), $news_arr['Date'] )."</td>
				<td aling=left>&nbsp;<a target='_blank' href='{$sNewsUrl}'>{$news_header}</a></td>
			</tr>\n";

	}
	echo "</table>\n";

}

function MemberDeleteNews()
{
	$res = db_res( "DELETE FROM `News` WHERE `ID` = ". (int)$_GET['delete_id'] );

	return $res;
}

function MemberAddNews()
{
	global $max_l;
	global $max_h;

	$news_text = strlen( $_POST['text'] ) > $max_l ? "LEFT ( '". process_db_input( $_POST['text'] ) ."', $max_l )" : "'". process_db_input( $_POST['text'] ) ."'";
	$news_header = strlen( $_POST['header'] ) > $max_h ? "LEFT ( '". process_db_input( $_POST['header'] ) ."', $max_h )" : "'". process_db_input( $_POST['header'] ). "'";
	$news_snippet = "'".process_db_input( $_POST['snippet'] )."'";
	
	$sNewsUri = uriGenerate($news_header, 'News', 'NewsUri', 50);

	$res = db_res( "INSERT INTO `News` ( `Date`, `Header`, `Text`, `Snippet`, `NewsUri` ) VALUES ( NOW(), $news_header, $news_text, $news_snippet, '$sNewsUri')" );

	return $res;
}

function MemberEditNews()
{
	global $max_l;
	global $max_h;

	$news_id = (int)$_POST['edit_id'];
	$news_text = strlen( $_POST['text'] ) > $max_l ? "LEFT ( '". process_db_input( $_POST['text'] ) ."', $max_l )" : "'". process_db_input( $_POST['text'] ) ."'";
	$news_header = strlen( $_POST['header'] ) > $max_h ? "LEFT ( '". process_db_input( $_POST['header'] ) ."', $max_h )" : "'". process_db_input( $_POST['header'] ). "'";
	$news_snippet = "'".process_db_input( $_POST['snippet'] )."'";
	
	$res = db_res( "UPDATE `News` SET `Date`=NOW(), `Header`=$news_header, `Text`=$news_text, `Snippet`=$news_snippet WHERE `ID` = $news_id" );

	return $res;
}

$max_l  = getParam( "max_news_text" );
$max_p  = getParam( "max_news_preview" );
$max_h  = getParam( "max_news_header" );

if ( !$max_l )
	$max_l = 4096;
if ( !$max_h )
	$max_h = 32;

$action_result = "";
if ( !$demo_mode && $_POST['action'] == 'new' )
{
	if ( MemberAddNews() )
		$action_result .= "News was added";
	else
		$action_result .= "News adding failed";
}

if ( !$demo_mode && $_POST['action'] == 'edit' && ((int)$_POST['edit_id'] != 0) )
{
	if ( MemberEditNews() )
		$action_result .= "News was updated";
	else
		$action_result .= "News updating failed";
}

if ( !$demo_mode && (int)$_GET['delete_id'] != 0 && $_GET['action'] == "delete" )
{
	if ( MemberDeleteNews() )
		$action_result .= "News was deleted";
	else
		$action_result .= "News deleting failed";
}


$_page['header'] = "News";
$_page['header_text'] = "News compose";

TopCodeAdmin();
ContentBlockHead("News");

if ( strlen($action_result) )
	echo "<br><center><div class=\"err\">$action_result</div></center><br>\n";

MemberPrintNews();

if ( (int)$_GET['edit_id'] != 0 )
{
	$news_arr = db_arr( "SELECT `ID`, DATE_FORMAT(`Date`, '$short_date_format' ) AS 'Date', `Header`, `Snippet`, `Text` FROM `News` WHERE `ID` = ". (int)$_GET['edit_id'] );
}

{

ContentBlockFoot();
ContentBlockHead("News compose");
?>

<form method="post" action="news.php">

<table style="border:1px solid gray;background-color:#EEEEEE;margin:0px auto;">
	<tr>
		<td style="text-align:right;font-weight:bold;padding:3px;">Header:</td>
		<td style="padding:3px;">
			<input name="header" style="width:380px;" value="<?= htmlspecialchars($news_arr['Header']) ?>">
		</td>
	</tr>

	<tr>
		<td style="text-align:right;font-weight:bold;padding:3px;">Snippet:</td>
		<td style="padding:3px;">
			<textarea name="snippet" class="articl" rows="3" style="width:380px;height:380px;"><?= htmlspecialchars($news_arr['Snippet']) ?></textarea>
		</td>
	</tr>
	
	<tr >
		<td style="text-align:right;font-weight:bold;padding:3px;">Text:</td>
		<td style="padding:3px;">
			<textarea name="text" class="articl" rows="10" style="width:380px;height:380px;"><?= htmlspecialchars($news_arr['Text']) ?></textarea>
		</td>
	</tr>

	<tr >
		<td align="center" colspan="2">
<?php
	if ( (int)$_GET['edit_id'] == 0 )
	{
		echo '<input type="hidden" name="action" value="new">';
		echo '<input class="no" type="submit" value="Add news">';
	}
	else
	{
		echo '<input type="hidden" name="action" value="edit">';
		echo '<input type="hidden" name="edit_id" value="'. ( (int)$_GET['edit_id'] ) .'">';
		echo '<input class="no" type="submit" value="Update news">';
	}
?>
		</td>
	</tr>
</table>
</form>
<?
ContentBlockFoot();
}
BottomCode();
?>