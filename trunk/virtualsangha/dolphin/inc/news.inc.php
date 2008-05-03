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
require_once( 'header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );


function getNewsUrl($iNewsId, $sNewsUri, $bPermalink = true)
{
	global $site;
	
	$sMainUrl = $site['url'];
	
	if ($bPermalink)
	{
		$sUrl = $sMainUrl.'news/'.$sNewsUri;
	}
	else
	{
		$sUrl = $sMainUrl.'news.php?ID='.$iNewsId;
	}
	
	return $sUrl;
}

function getNewsList($iLimit = 0)
{
	$sqlQuery = "SELECT `News`.`ID` AS `newsID`,
						`Header`,
						`NewsUri`,
						`Snippet`,
						UNIX_TIMESTAMP( `Date` ) AS 'Date'
				 FROM `News`
				 ORDER BY `Date`DESC";
	
	$sqlLimit = $iLimit > 0 ? " LIMIT $iLimit" : "";
	
	$rNews = db_res($sqlQuery . $sqlLimit);
	
	return $rNews;
}

function printNewsPanel($iLimit = 0, $iPreview = 128)
{
	global $site;
	
	$php_date_format = getParam( 'php_date_format' );	

	// news
	$news_limit_chars = getParam("max_news_preview");
	$bNewsFriendly = getParam('permalinks_news') == 'on' ? true : false;
	
	$news_res = getNewsList($iLimit);
	
	$news_count = db_arr("SELECT COUNT(*) FROM `News`");
	$news_counter = $news_count['0'];

	$ret = '';
	
	if( $news_counter > 0 )
	{
		while( $news_arr = mysql_fetch_assoc($news_res) )
		{			
			$ret .= '<div class="newsWrap">';
				$ret .= '<div class="newsHead">';
						$ret .= '<a href="' . getNewsUrl($news_arr['newsID'], $news_arr['NewsUri'], $bNewsFriendly) . '">';
							$ret .= process_line_output( $news_arr['Header'] );
						$ret .= '</a>';
				$ret .= '</div>';
				
				$ret .= '<div class="newsInfo"><img src="' . getTemplateIcon( 'clock.gif' ) . '" />' . date( $php_date_format, $news_arr['Date'] ) . '</div>';
				
				$ret .= '<div class="newsText">';
					//$ret .= process_text_withlinks_output( $news_arr['Snippet'] );
					$ret .= process_html_output( $news_arr['Snippet'] );
				$ret .= '</div>';
			$ret .= '</div>';
			
		}
		
		if( $news_counter > $max_news_on_home )
		{
			$sNewsLink = $bNewsFriendly ? $site['url'].'news/' : $site['url'].'news.php';
			$ret .= '<div class="newsReadMore">';
				$ret .= '<a href="' . $sNewsLink . '">' . _t("_Read news in archive") . '</a>';
			$ret .= '</div>';
		}
	}
	else
	{
		$ret .= '<div class="no_result"><div>' . _t("_No news available") . '</div></div>';
	}
	
	return $ret;
}

?>