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

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolBlogs.php' );

// --------------- page variables and login

$_page['name_index']	= 49;
$_page['css_name']		= 'blogs.css';


check_logged();

$oBlogs = new BxDolBlogs(false);
$oBlogs->sCommonCSS = <<<EOF
<link href="{$site['url']}templates/tmpl_{$tmpl}/css/blogs_common.css" rel="stylesheet" type="text/css" />
EOF;

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompBlogs();
$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorJS;

$_page['header'] = $oBlogs->GetHeaderString();
$_page['header_text'] = $oBlogs->GetHeaderString();

function PageCompBlogs() {
    global $site;
	global $date_format;
	global $oBlogs;

	$sRetHtml = '';
	$sRetHtml .= $oBlogs->GenCommandForms();

	switch ( $_REQUEST['action'] ) {
		//print functions
		case 'top_blogs':
			$sRetHtml .= $oBlogs->GenBlogLists('top');
			break;
		case 'show_member_blog':
			$sRetHtml .= $oBlogs->GenMemberBlog();
			break;
		case 'top_posts':
			$sRetHtml .= $oBlogs->GenPostLists('top');
			break;
		case 'new_post':
			$sRetHtml .= $oBlogs->AddNewPostForm();
			break;
		case 'show_member_post':
			$sRetHtml .= $oBlogs->GenPostPage();
			break;
		case 'search_by_tag':
			$sRetHtml .= $oBlogs->GenSearchResult();
			break;

		//forms of editing
		case 'add_category':
			$sRetHtml .= $oBlogs->GenEditCategoryForm();
			break;
		case 'edit_category':
			$iCategoryID = (int)($_REQUEST['categoryID']);
			$sRetHtml .= $oBlogs->GenEditCategoryForm($iCategoryID);
			break;
		case 'edit_post':
			$iPostID = (int)($_POST['EditPostID']);
			$sRetHtml .= $oBlogs->AddNewPostForm($iPostID);
			break;

		//non safe functions
		case 'create_blog':
			$sRetHtml .= $oBlogs->ActionCreateBlog();
			break;
		case 'edit_blog':
			$sRetHtml .= $oBlogs->ActionEditBlog();
			$iBlogID = (int)($_POST['EditBlogID']);
			$iOwnerID = (int)($_REQUEST['EOwnerID']);
			$sRetHtml .= $oBlogs->GenMemberBlog($iOwnerID);
			break;
		case 'delete_blog':
			$sRetHtml .= $oBlogs->ActionDeleteBlogSQL();
			$sRetHtml .= $oBlogs->GenBlogLists('last');
			break;
		case 'addcategory':
			$sRetHtml .= $oBlogs->ActionUpdateCategory();
			$iOwnerID = (int)($_REQUEST['OwnerID']);
			$_REQUEST['category'] = mysql_insert_id();
			$sRetHtml .= $oBlogs->GenMemberBlog($iOwnerID);
			break;
		case 'editcategory':
			$sRetHtml .= $oBlogs->ActionUpdateCategory(TRUE);
			$iOwnerID = (int)($_REQUEST['OwnerID']);
			$_REQUEST['category'] = mysql_insert_id();
			$sRetHtml .= $oBlogs->GenMemberBlog($iOwnerID);
			break;
		case 'delete_category':
			$sRetHtml .= $oBlogs->ActionDeleteCategory();
			$iOwnerID = (int)$_COOKIE['memberID'];
			$sRetHtml .= $oBlogs->GenMemberBlog($iOwnerID);
			break;
		case 'del_img':
			$sRetHtml .= $oBlogs->ActionDelImg();
			$sRetHtml .= $oBlogs->GenPostPage();
			break;
		case 'add_post':
			$arrPostAdv = $oBlogs->GetPostArrByPostValues();
			$arrErr = $oBlogs->GetCheckErrors($arrPostAdv);
			if( empty( $arrErr ) ) {
				$iLastID = -1;
				$sRetHtml .= $oBlogs->ActionAddNewPost($iLastID);
				$_REQUEST['post_id'] = $iLastID;
				$sRetHtml .= $oBlogs->GenPostPage();
			} else {
				$sRetHtml .= $oBlogs->AddNewPostForm(-1, $arrErr);
			}
			break;
		case 'post_updated':
			$iPostID = (int)($_POST['EditedPostID']);

			$arrPostAdv = $oBlogs->GetPostArrByPostValues();
			$arrErr = $oBlogs -> GetCheckErrors($arrPostAdv);
			if( empty( $arrErr ) ) {
				$sRetHtml .= $oBlogs->ActionEditPost();
				$_REQUEST['post_id'] = $iPostID;
				$sRetHtml .= $oBlogs->GenPostPage();
			} else {
				$sRetHtml .= $oBlogs->AddNewPostForm($iPostID, $arrErr);
			}
			break;
		case 'delete_post':
			$sRetHtml .= $oBlogs->ActionDeletePost();
			$iPostID = (int)($_POST['DeletePostID']);
			$_REQUEST['ownerID'] = $oBlogs->aBlogConf['visitorID'];
			$sRetHtml .= $oBlogs->GenMemberBlog();
			break;
		/*case 'addcomment':
			$sRetHtml .= $oBlogs->ActionAddBlogComment();
			$iPostID = (int)($_POST['CommPostID']);
			$iOwnerID = (int)($_POST['ownerID']);
			$_REQUEST['post_id'] = $iPostID;
			$sRetHtml .= $oBlogs->GenPostPage();
			break;
		case 'editcomment':
			$sRetHtml .= $oBlogs->ActionEditComment();
			$iPostID = (int)($_POST['EPostID']);
			$iOwnerID = (int)($_POST['ownerID']);
			$_REQUEST['post_id'] = $iPostID;
			$sRetHtml .= $oBlogs->GenPostPage();
			break;
		case 'delete_comment':
			$sRetHtml .= $oBlogs->ActionDeleteComment();
			$iPostID = (int)($_POST['DPostID']);
			$iOwnerID = (int)($_POST['ownerID']);
			$_REQUEST['post_id'] = $iPostID;
			$sRetHtml .= $oBlogs->GenPostPage();
			break;*/
		default:
			$sRetHtml .= $oBlogs->GenBlogLists('last');
			break;
	}

	return $sRetHtml;
}

PageCode();

?>