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
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolArticles.php' );

check_logged();

$_page['name_index'] = 0;
$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompArticles($logged);
$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorJS;
$_page['css_name'] = 'articles.css';

$_page['header'] = _t( "_ARTICLES_H", $site['title'] );
$_page['header_text'] = _t( "_ARTICLES_H1" );

// --------------- page components

function PageCompArticles($logged) {
	global $site;
	global $sActionText;

	$oArticles = new BxDolArticles($logged);
	$sRet = '';

	$sActions = $oArticles->ActionAddUpdateElements();
	$sRet .=  $sActions;

	switch ($_GET['action'] ) {
		case 'addcategory':
			$sRet .= $oArticles->getArticlesCategoryEditForm();
		break;

		case 'categoryedit':
			$iCategoryID = (int)$_REQUEST['catID'];
			$sRet .= $oArticles->getArticlesCategoryEditForm( $iCategoryID );
		break;

		case 'viewcategory':
			if (isset($_REQUEST['articleCatUri']))
				$iCategoryID = (int)$oArticles->getArticleCatIdByUri( $_REQUEST['articleCatUri'] );
			else
				$iCategoryID = (int)$_REQUEST['catID'];
			$sRet = $oArticles->getArticlesList( $iCategoryID );
		break;

		case 'viewarticle':
			if (isset($_REQUEST['articleUri']))
				$iArticleID = $oArticles->getArticleIdByUri( $_REQUEST['articleUri'] );
			else 
				$iArticleID = $_REQUEST['articleID'];
			
			$sRet = $oArticles->getArticle( $iArticleID );
		break;

		case 'addarticle':
			$sRet .= $oArticles->getArticleEditForm();
		break;

		case 'categorydelete':
			$iCategoryID = (int)$_REQUEST['catID'];
			$sRet .= $oArticles->deleteCategory( $iCategoryID );
			$sRet .= $oArticles->getArticlesCategoriesList();
		break;

		case 'editarticle':
			$iArticleID = (int)$_REQUEST['articleID'];
			$sRet .= $oArticles->getArticleEditForm( $iArticleID );
		break;

		case 'deletearticle':
			$iArticleID = (int)$_REQUEST['articleID'];
			$sRet .= $oArticles->deleteArticle( $iArticleID );
			$sRet .= $oArticles->getArticlesCategoriesList();
		break;

		default:
			$sRet .= $oArticles->getArticlesCategoriesList();
		break;
	}

	return $sRet;
}

PageCode();
?>