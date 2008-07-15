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
require_once( BX_DIRECTORY_PATH_INC . 'news.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// --------------- page variables / login

$_page['name_index'] 	= 20;
$_page['css_name']		= 'news.css';

$logged['member'] = member_auth( 0, false );

$_page['header'] = _t("_NEWS_H");
$_page['header_text'] = _t("_NEWS_H");

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = isset($_GET['ID']) || isset($_GET['newsUri']) ? getNews() : printNewsList();

// --------------- [END] page components

PageCode();

// --------------- page components functions


/**
 * Print news
 */
function printNewsList()
{
	global $news_limit_chars;
	global $short_date_format;
	global $tmpl;

	$bNewsFriendly = getParam('permalinks_news') == 'on' ? true : false;

	$res = getNewsList();

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
					<a href="<?=getNewsUrl($news_arr['newsID'], $news_arr['NewsUri'], $bNewsFriendly)?>">
						<?=process_line_output( $news_arr['Header'] )?>
					</a>
				</div>
				<div class="news_date"><?=date( str_replace('%','',$short_date_format), $news_arr['Date'] )?></div>
				<div class="news_snippet">
					<?=process_html_output( $news_arr['Snippet'] )?>
				</div>
				<div class="clear_both"></div>
			</div>
			<?
		}
	}
	
	return ob_get_clean();
}

function getNewsByField($sField, $sVal)
{
	global $date_format;
	
	$sField = process_db_input($sField);
	$sVal   = process_db_input($sVal);
	
	$sqlQuery = "
	SELECT
		`Header`,
		`Snippet`,
		`Text`,
		DATE_FORMAT(`Date`, '$date_format' ) AS 'Date'
	FROM `News`
	WHERE `$sField`='$sVal'";

	return db_arr( $sqlQuery );
}
	
function printNews($news_arr)
{
	if ($news_arr)
	{
		$sCode  = '<div class="news_cont">';
		$sCode .= '<div class="news_header">'.process_line_output( $news_arr['Header'] ).'</div>';
		$sCode .= '<div class="news_date">'.$news_arr['Date'].'</div>';
		//$sCode .= '<div class="news_snippet">'.process_text_output( $news_arr['Snippet'] ).'</div>';
		$sCode .= '<div class="news_snippet">'.process_html_output( $news_arr['Snippet'] ).'</div>';
		//$sCode .= '<div class="news_text">'.process_text_withlinks_output( $news_arr['Text'] ).'</div></div>';
		$sCode .= '<div class="news_text">'.process_html_output( $news_arr['Text'] ).'</div></div>';
	}
	else
	{
		$sCode = MsgBox( _t( '_No news available' ) );
	}

	return $sCode;
}

function getNews()
{
	if (isset($_GET['newsUri']))
	{
		$sCode = printNews(getNewsByField('NewsUri', $_GET['newsUri']));
	}
	elseif (isset($_GET['ID']))
	{
		$sCode = printNews(getNewsByField('ID', $_GET['ID']));
	}

	return $sCode;
}

?>