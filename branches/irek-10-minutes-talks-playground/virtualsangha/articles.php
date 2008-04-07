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
//require_once( BX_DIRECTORY_PATH_INC . 'articles.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolArticles.php' );

// --------------- page variables and login
$_page['name_index']	= 11;
$_page['css_name']		= 'articles.css';

check_logged();

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompArticles();
//$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorMiniJS;

$_page['header'] = _t( "_ARTICLES_H", $site['title'] );
$_page['header_text'] = _t( "_ARTICLES_H1" );

// --------------- page components

function PageCompArticles() {
	$oArticles = new BxDolArticles(false);
	$sRetHtml = '';
	switch( $_GET['action']) {
		case 'viewcategory':
			$iCategoryID = (int)$_REQUEST['catID'];
			$sRetHtml = $oArticles->getArticlesList( $iCategoryID );
		break;
		case 'viewarticle':
			$iArticleID = (int)$_REQUEST['articleID'];
			$sRetHtml = $oArticles->getArticle( $iArticleID );
		break;
		default:
			$sRetHtml = $oArticles->getArticlesCategiriesList();
		break;
	}
	return $sRetHtml;
}

PageCode();
?>