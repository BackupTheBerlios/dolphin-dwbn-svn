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

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// --------------- page variables / login

$_page['name_index'] 	= 30;
$_page['css_name']		= 'stories.css';

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );


$_page['header'] = _t( "_STORY_VIEW_H" );
$_page['header_text'] = _t( "_STORY_VIEW_H1" );
//$_page['header_text'] = ('g4' != $tmpl) ? _t( "_STORY_VIEW_H1" ) : "<img src=\"{$site['images']}feedback.gif\">";
// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

// --------------- [END] page components

// this is dynamic page -  send headers to do not cache this page
send_headers_page_changed();
PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
	global $site;
	global $short_date_format;

	$out = "";

	$query = "SELECT `Stories`.`ID`, `Profiles`.`ID` as `MembID`, DATE_FORMAT(`Stories`.`Date`,  '$short_date_format' ) AS 'Date', `Stories`.`Header`, `Stories`.`Text`, `Profiles`.`ID` as `prID`, `Profiles`.`NickName` FROM `Stories` INNER JOIN `Profiles` ON (`Stories`.`Sender` = `Profiles`.`ID`) WHERE `Stories`.`active` = 'on' ORDER BY `Stories`.`Date` DESC";

	$res = db_res($query);
	if ( !$res )
		return 0;

	$out .= "
		<br />
		<div align=left>&nbsp;&nbsp;<a href=\"".$site['url']."story.php\">"._t("_post my feedback")."</a></div>
		<br />
		<table width=\"100%\" cellspacing=1 cellpadding=2 class=small width=100%>";
	$num = mysql_num_rows($res);
	if ( !$num )
		$out .= "
				<tr>
					<td><center>"._t("_NO_STORIES")."</center></td>
				</tr>";
	else
	{
		while ( $arr = mysql_fetch_array($res))
		{
			$story_header = process_line_output( $arr['Header'] );
			$out .= "
				<tr class=panel>
					<td align=center width=15%>{$arr['Date']}</td>
					<td align=center width=15%><a href=\"".getProfileLink($arr['prID'])."\">{$arr['NickName']}</a></td>
					<td aling=left>&nbsp;<a href='story_view.php?ID={$arr['ID']}'>$story_header</a></td>
				</tr>";

		}
	}

	$out .= "
		</table>\n";

	return $out;
}

?>