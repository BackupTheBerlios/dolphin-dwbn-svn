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
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// --------------- page variables / login

$_page['name_index'] 	= 0;
$_page['css_name']		= 'story_view.css';


if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );


$_page['header'] = _t( "_COMPOSE_STORY_VIEW_H" );
$_page['header_text'] = _t( "_COMPOSE_STORY_VIEW_H1" );

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
	global $short_date_format;

	$story_id = (int)( $_GET['ID'] );
	$out = "";

	$query = "SELECT `Profiles`.`ID`, DATE_FORMAT(`Stories`.`Date`,  '$short_date_format' ) AS 'Date', `Stories`.`Header`, `Stories`.`Text`, `Profiles`.`NickName` FROM `Stories` LEFT JOIN `Profiles` ON (`Stories`.`Sender` = `Profiles`.`ID`) WHERE `Stories`.`ID` = {$story_id} ORDER BY  `Stories`.`Date` DESC";
	$arr = db_arr( $query );

	if ( !$arr )
		return "<center>". _t("_No success story available.") ."</center>";

	$story_header = process_text_output( $arr['Header'] );
	$story_text = process_html_output( $arr['Text'] );
	$out .= "
		<table width=\"100%\" cellpadding=4 cellspacing=4><td align=center class=text2>
			<td>
				<table cellspacing=1 cellpadding=2 class=small width=100%>
					<tr class=panel>
						<td align=center width=15%>{$arr['Date']}</td>
						<td align=center width=15%><a href='".getProfileLink($arr['ID'])."'>{$arr['NickName']}</td>
						<td aling=left>&nbsp;<b>{$story_header}</b></td>
					</tr>
				</table>
				<table cellspacing=1 cellpadding=2 class=small width=100%>
					<tr class=panel>
						<td aling=left>{$story_text}</td>
					</tr>
				</table>
			</td>
		</table>\n";

	return $out;
}


?>