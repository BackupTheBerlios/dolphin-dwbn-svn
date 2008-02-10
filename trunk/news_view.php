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

// --------------- page variables

$_page['name_index'] 	= 21;
$_page['css_name']		= 'news_view.css';

$logged['member'] = member_auth( 0, false );

$_page['header'] = _t( "_COMPOSE_NEWS_VIEW_H" );
$_page['header_text'] = _t( "_COMPOSE_NEWS_VIEW_H1" );

// --------------- page components

$_ni = $_page['name_index'];

$query = "
	SELECT
		`Header`,
		`Snippet`,
		`Text`,
		DATE_FORMAT(`Date`, '$date_format' ) AS 'Date'
	FROM `News`
	WHERE `ID`=".(int)$_GET['ID'];

$news_arr = db_arr( $query );

$_page_cont[$_ni]['news_date']    = $news_arr['Date'];
$_page_cont[$_ni]['news_header']  = process_line_output( $news_arr['Header'] );
$_page_cont[$_ni]['news_text']    = process_text_withlinks_output( $news_arr['Text'] );
$_page_cont[$_ni]['news_snippet'] = process_text_output( $news_arr['Snippet'] );

// --------------- [END] page components

PageCode();

// --------------- page components functions



?>
