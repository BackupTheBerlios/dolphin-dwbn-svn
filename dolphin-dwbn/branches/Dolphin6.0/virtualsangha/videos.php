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

// --------------- page variables and login

$_page['name_index']	= 84;
$_page['css_name']		= 'videos.css';

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );

$_page['header'] = _t( "_Videos" );
$_page['header_text'] = _t( "_Videos" );

$_ni = $_page['name_index'];

$_page_cont[$_ni]['page_main_code'] = PageCompVideos();

PageCode();


function PageCompVideos()
{
    global $site;
	global $dir;
	global $tmpl;

	// number of videos
	$max_num	= 12;
	$mode		= 'rand';
		
	$sqlSelect = "
		SELECT
			`media`.`med_id`,
			`med_prof_id`,
			`med_file`,
			`med_title`";
	
	$sqlFrom = "
		FROM `media`";
	
	$sqlWhere = "
		WHERE
			`med_type` = 'video'";

	
	if ( $_GET['mode'] == 'rand' or
		 $_GET['mode'] == 'last' or
		 $_GET['mode'] == 'top' )
			$mode = $_GET['mode'];
	
	$menu = '';
	switch ( $mode )
	{
		case 'last': $sqlOrder = " ORDER BY `med_date` DESC"; break;
		case 'rand': $sqlOrder = " ORDER BY RAND()";          break;
		case 'top':
				$sqlSelect .= ",
	(`med_rating_sum`/`med_rating_count`) AS `avg_mark`";
				$sqlFrom .= "
	INNER JOIN `media_rating` USING (`med_id`) ";
				$sqlOrder = "
	ORDER BY `avg_mark` DESC";
		break;
	}
	
	$aNum = db_arr( "SELECT COUNT(`media`.`med_id`) $sqlFrom $sqlWhere" );
	$num = (int)$aNum[0];
	if( $num )
	{
		$pages = ceil( $num / $max_num );
		$page = (int)$_GET['page'];
		
		if( $page < 1 or $mode == 'rand' )
			$page = 1;
		if( $page > $pages )
			$page = $pages;
		
		$sqlLimitFrom = ( $page - 1 ) * $max_num;
		$sqlLimit = "
		LIMIT $sqlLimitFrom, $max_num";
		
		//$max_thumb_width  = (int)getParam( 'max_thumb_width' );
		//$max_thumb_height = (int)getParam( 'max_thumb_height' );
		
		$ret = '<div class="clear_both"></div>';
		$tmplBlock = file_get_contents( "{$dir['root']}templates/tmpl_{$tmpl}/media_page_block.html" );
		
		$result = db_res( $sqlSelect.$sqlFrom.$sqlWhere.$sqlOrder.$sqlLimit );
		while ( $ph_arr = mysql_fetch_assoc( $result ) )
		{
			$block = $tmplBlock;
			
			$memNickName = getNickName( $ph_arr['med_prof_id'] );
			
			$aReplace['media_title'] = process_line_output( $ph_arr['med_title'] );
			$aReplace['media_icon']  = "<a href=\"{$site['url']}media/video/{$ph_arr['med_prof_id']}/{$ph_arr['med_file']}\" title=\""._t('_download')."\"><img src=\"".getTemplateIcon( 'video.jpg' )."\" alt=\"video\" /></a>";
			$aReplace['nickname']    = "<a href=\"".getProfileLink($ph_arr['med_prof_id'])."\">$memNickName</a>";
			$aReplace['download']    = '';
			$aReplace['delete']      = '';
			
			foreach( $aReplace as $key => $val )
				$block = str_replace( "__{$key}__", $val, $block );
			
			$ret .= $block;
			
			
		}
		
		$ret .= '<div class="clear_both"></div>';
		
		if( $pages > 1 )
		{
			$pagination = 
				'<div class="video_pages">'.
					genPagination( $pages, $page, $_SERVER['PHP_SELF']."?mode=$mode&amp;page={page}" ).
				'</div>';
			
			$ret = $pagination . $ret . $pagination;
		}
	}
	else
	{
		$ret .= '<div class="no_result">';
			$ret .= '<div>';
				$ret .= _t("_No results found");
			$ret .= '</div>';
		$ret .= '</div>';
	}
	
	return $ret;
}