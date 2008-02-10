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

// My Groups

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'groups.inc.php' );

// --------------- page variables and login


$_page['name_index']	= 74;
$_page['css_name']		= 'groups.css';


if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );

$_page['header'] = _t( "_Groups Home" );
$_page['header_text'] = _t( "_Groups categories" );

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();
$_page_cont[$_ni]['page_top_groups'] = PageCompGroupsSearchResults( 0, 0, 0, 0, 0, 'membersCount', true );

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode()
{
	global $site;
	
	ob_start();
	?>
		<div class="groups_categs_wrapper">
			<div class="clear_both"></div>
			<?=genAllCategsList()?>
			<div class="clear_both"></div>
		</div>
		
		<div class="groups_search_simple">
			<form action="<?=$site['url']?>groups_browse.php" method="GET">
				<?=_t('_Keyword')?>:
				<input type="text" name="keyword" />
				<input type="submit" value="<?=_t('_Search')?>" />
				<a href="<?=$site['url']?>groups_browse.php"><?=_t('_Advanced search')?></a>
			</form>
		</div>
	<?php
	return ob_get_clean();
}
?>