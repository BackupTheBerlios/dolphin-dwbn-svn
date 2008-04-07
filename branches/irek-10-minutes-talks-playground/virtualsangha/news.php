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

$_page['name_index'] 	= 20;
$_page['css_name']		= 'news.css';

$logged['member'] = member_auth( 0, false );

$_page['header'] = _t("_NEWS_H");
$_page['header_text'] = _t("_NEWS_H");

//$news_limit_chars = getParam("max_news_preview");

$max_l  = getParam( "max_news_text" );
//$max_p  = getParam( "max_news_preview" );
$max_h  = getParam( "max_news_header" );

if ( !$max_l )
	$max_l = 4096;
if ( !$max_h )
	$max_h = 32;

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = MemberPrintNewsList();

// --------------- [END] page components

PageCode();

// --------------- page components functions


/**
 * Print news
 */
function MemberPrintNewsList()
{
	global $news_limit_chars;
	global $short_date_format;
	global $tmpl;

	$query = "
		SELECT
			`ID`,
			`Header`,
			`Snippet`,
			DATE_FORMAT(`Date`, '$short_date_format' ) AS 'Date'
		FROM `News`
		ORDER BY `Date` DESC
		";
	
	$res = db_res( $query );

	ob_start();

    if ( !mysql_num_rows($res) )
    {
		?>
			<div class="no_news">
				<?=_t("_No news available")?>
			</div>
		<?
    }
    else
    {
		while ( $news_arr = mysql_fetch_array($res))
		{
			?>
			<div class="news_cont">
				<div class="clear_both"></div>
				<div class="news_header">
					<a href="<?="{$site['url']}news_view.php?ID={$news_arr['ID']}"?>">
						<?=process_line_output( $news_arr['Header'] )?>
					</a>
				</div>
				<div class="news_date"><?=$news_arr['Date']?></div>
				<div class="news_snippet">
					<?=process_text_output( $news_arr['Snippet'] )?>
				</div>
				<div class="clear_both"></div>
			</div>
			<?
		}
	}
	
	return ob_get_clean();
}

?>