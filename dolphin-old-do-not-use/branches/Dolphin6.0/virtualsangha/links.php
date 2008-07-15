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

$_page['name_index'] 	= 15;
$_page['css_name']		= 'links.css';


if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );


$_page['header'] = _t( "_LINKS_H", $site['title'] );
$_page['header_text'] = _t( "_LINKS_H1", $site['title'] );
//$_page['header_text'] = ('g4' != $tmpl) ? _t( "_LINKS_H1", $site['title'] ) : "<img src=\"{$site['images']}links.gif\">";

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
	global $tmpl;

	$links_res = db_res( "SELECT * FROM `Links`" );
	
	if ( !mysql_num_rows( $links_res ) )
		$out .= "<div class=\"no_links\">"._t( "_NO_LINKS" )."</div>\n";

	else
	{
		while ( $link_arr = mysql_fetch_array( $links_res ) )
		{
			$link_url = process_line_output( $link_arr['URL'], 1000 );
			$link_title = process_line_output( $link_arr['Title'] );
			$link_desc = process_text_output( $link_arr['Description'] );
			$out .= "<div class=\"links_cont\">\n";
			$out .= "<div class=\"clear_both\"></div>\n";
			$out .= "<div class=\"links_header\">";
			$out .= "<a href=\"$link_url\">$link_title</a></div>\n";
			$out .= "<div class=\"links_snippet\">$link_desc</div>\n";
			$out .= "<div class=\"clear_both\"></div></div>\n";
		}
	}
	
	return $out;
}

?>