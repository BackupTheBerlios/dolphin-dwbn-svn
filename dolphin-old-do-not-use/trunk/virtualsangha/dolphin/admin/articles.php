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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolArticles.php' );


//$_page['header'] = "Articles";
$_page['header_text'] = "Manage site articles";
$_page['css_name'] = 'articles.css';

$_page['extraCodeInHead'] = '
	<!-- tinyMCE -->
	<script language="javascript" type="text/javascript" src="' . $site['plugins'] . 'tiny_mce/tiny_mce.js"></script>
	<script language="javascript" type="text/javascript">
	// Notice: The simple theme does not use all options some of them are limited to the advanced theme
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		content_css : "' . $site['base'] . 'css/tiny_mce.css",
		editor_selector : "articl",
		plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen",
		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,separator,forecolor,backcolor",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "emotions",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_disable : "link,unlink,insertanchor,image,subscript,superscript,help,anchor,code,styleselect",
		plugi2n_insertdate_dateFormat : "%Y-%m-%d",
	    plugi2n_insertdate_timeFormat : "%H:%M:%S",
		paste_use_dialog : false,
		theme_advanced_resizing : false,
		theme_advanced_resize_horizontal : false,
		theme_advanced_link_targets : "_something=My somthing;_something2=My somthing2;_something3=My somthing3;",
		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : false,
		paste_strip_class_attributes : "all",
		paste_remove_spans : false,
		paste_remove_styles : false

		});
	</script>
	<!-- /tinyMCE -->

';

$logged['admin'] = member_auth( 1, true, true );

TopCodeAdmin();
ContentBlockHead("Articles");
print getArticlesAdminContent($logged);
ContentBlockFoot();
BottomCode();

function getArticlesAdminContent($logged) {
	global $site;
	global $sActionText;

	$oArticles = new BxDolArticles($logged);
	$sRet = '';

	$sActions = $oArticles->ActionAddUpdateElements();
	$sRet .=  $sActions;

	switch ($_GET['action'] )
	{
		case 'addcategory':
			$sRet .= $oArticles->getArticlesCategoryEditForm();
		break;

		case 'categoryedit':
			$iCategoryID = (int)$_REQUEST['catID'];
			$sRet .= $oArticles->getArticlesCategoryEditForm( $iCategoryID );
		break;

		case 'viewcategory':
			$iCategoryID = (int)$_REQUEST['catID'];
			$sRet .= $oArticles->getArticlesList( $iCategoryID );
		break;

		case 'viewarticle':
			$iArticleID = (int)$_REQUEST['articleID'];
			$sRet .= $oArticles->getArticle( $iArticleID );
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

?>