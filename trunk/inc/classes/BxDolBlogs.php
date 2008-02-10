<?

require_once(BX_DIRECTORY_PATH_INC . 'header.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'admin.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'tags.inc.php' );
require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolComments.php' );



/*
 * class for Events
 */
class BxDolBlogs {
	//variables

	//max sizes of pictures for resizing during upload
	var $iIconSize = 45;
	var $iThumbSize = 110;
	var $iBigThumbSize = 340;
	var $iImgSize = 800;

	//admin mode, can All actions
	var $bAdminMode;

	//path to image pic_not_avail.gif
	var $sPicNotAvail;
	//path to spacer image
	var $sSpacerPath = 'templates/base/images/icons/spacer.gif';

	var $aBlogConf = array();

	var $iLastPostedPostID = -1;

	var $iPerPageElements = 10;

	/**
	 * constructor
	 */
	function BxDolBlogs($bAdmMode = FALSE) {
		//$this->bAdminMode = TRUE;

		$this->aBlogConf['visitorID']   = (int)$_COOKIE['memberID'];
		$this->aBlogConf['ownerID']     = isset($_REQUEST['ownerID']) ? (int)$_REQUEST['ownerID'] : $this->aBlogConf['visitorID'];
		$this->aBlogConf['isOwner']     = ( $this->aBlogConf['ownerID'] == $this->aBlogConf['visitorID'] ) ? true : false;
		$this->aBlogConf['editAllowed'] = ( $this->aBlogConf['isOwner'] );// || $logged['admin'] ) ? true : false; // deprecated to admin edit/delete blogs
		$this->aBlogConf['period']      = 1; // time period before user can add another record (in minutes)

		if( !$this->aBlogConf['isOwner'] )
			$this->aBlogConf['isFriend'] = is_friends( $this->aBlogConf['visitorID'], $this->aBlogConf['ownerID'] );

		$this->aBlogConf['loggedMember']             = $logged['member'];
		$this->aBlogConf['commentMaxLenght']         = (int)getParam('blogCommentMaxLenght');
		$this->aBlogConf['categoryCaptionMaxLenght'] = (int)getParam('blogCategoryCaptionMaxLenght');
		//$this->aBlogConf['categoryDescMaxLenght']    = (int)getParam('blogCategoryDescMaxLenght');
		$this->aBlogConf['blogCaptionMaxLenght']     = (int)getParam('blogCaptionMaxLenght');
		$this->aBlogConf['blogTextMaxLenght']        = (int)getParam('blogTextMaxLenght');
		$this->aBlogConf['breadCrampDivider']        = getParam('breadCrampDivider');
	}

	function CheckLogged() {
		global $logged;
		if( !$logged['member'] && !$logged['admin'] ) {
			member_auth(0);
		}
	}

	/**
	 * Return string for Header, depends at POST params
	 *
	 * @return Textpresentation of data
	 */
	function GetHeaderString() {
		switch ( $_REQUEST['action'] ) {
			//print functions
			case 'top_blogs':
				$sCaption = _t('_Top Blogs');
				break;
			case 'show_member_blog':
				$sCaption = _t('_my_blog');
				break;
			case 'top_posts':
				$sCaption = _t('_Top Posts');
				break;
			case 'new_post':
				$sCaption = _t('_Add Post');
				break;
			case 'show_member_post':
				$sCaption = _t('_Post');
				break;
			case 'search_by_tag':
				$sCaption = _t('_Search result');
				break;
			default:
				$sCaption = _t('_Blogs');
				break;
		}
		return $sCaption;
	}

	/**
	 * Generate common forms and includes js
	 *
	 * @return HTML presentation of data
	 */
	function GenCommandForms() {
		//unnecessary
		/*<form action="{$_SERVER['PHP_SELF']}" method="post" name="command_activate_post">
			<input type="hidden" name="ActivateAdvertisementID" id="ActivateAdvertisementID" value=""/>
		</form>
		<!-- <form action="{$_SERVER['PHP_SELF']}" method="post" name="command_edit_blog">
			<input type="hidden" name="action" id="action" value="edit_blog" />
			<input type="hidden" name="EditBlogID" id="EditBlogID" value=""/>
			<input type="hidden" name="Description" id="Description" value=""/>
		</form> -->
		if ($this -> bAdminMode) {*/

		$sJSPath = ($this -> bAdminMode) ? "../" : "";

		$sRetHtml = <<<EOF
		<script src="{$sJSPath}inc/js/dynamic_core.js.php" type="text/javascript"></script>

		<form action="{$_SERVER['PHP_SELF']}" method="post" name="command_delete_post">
			<input type="hidden" name="action" value="delete_post" />
			<input type="hidden" name="DOwnerID" id="DOwnerID" value=""/>
			<input type="hidden" name="DeletePostID" id="DeletePostID" value=""/>
		</form>
		<form action="{$_SERVER['PHP_SELF']}" method="post" name="command_delete_category">
			<input type="hidden" name="action" value="delete_category" />
			<input type="hidden" name="DeleteCategoryID" id="DeleteCategoryID" value=""/>
		</form>
		<form action="{$_SERVER['PHP_SELF']}" method="post" name="command_edit_post">
			<input type="hidden" name="action" value="edit_post" />
			<input type="hidden" name="EditPostID" id="EditPostID" value=""/>
		</form>
		<form action="{$_SERVER['PHP_SELF']}" method="post" name="command_delete_blog">
			<input type="hidden" name="action" id="action" value="delete_blog" />
			<input type="hidden" name="DeleteBlogID" id="DeleteBlogID" value="" />
		</form>
EOF;
		return $sRetHtml;
	}

	function CheckRestrictionToUse($iMemberID) {
		if ($this->bAdminMode==true) return FALSE;
		$vCheckRes = checkAction( $iMemberID, ACTION_ID_USE_BLOG );
		if ( $vCheckRes[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED ) {
			$ret = MsgBox(strip_tags($vCheckRes[CHECK_ACTION_MESSAGE]));
			return $ret;
		}
		return '';
	}

	function CheckRestrictionToView($iMemberID) {
		if ($this->bAdminMode==true) return FALSE;
		$vCheckRes = checkAction( $iMemberID, ACTION_ID_VIEW_BLOG );
		if ( $vCheckRes[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED ) {
			$ret = MsgBox(strip_tags($vCheckRes[CHECK_ACTION_MESSAGE]));
			return $ret;
		}
		return '';

/*
		$iCheckedMemberID = $this->aBlogConf['visitorID'];
		$sRestrictRes = $this->CheckRestrictionToView($iCheckedMemberID);
		if ($sRestrictRes != '') return $sRestrictRes;
*/
	}

	/**
	 * Generate List of Blogs
	 *
	 * @param $sType - tyle of list ('top', 'last')
	 * @return HTML presentation of data
	 */
	function GenBlogLists($sType = '') {
		$sDescriptionC = _t('_Description');
		$sPostsC = _t('_Posts');
		$sNoBlogsC = _t('_Sorry, nothing found');
		$sAllBlogsC = _t('_All Blogs');
		$sTopBlogsC = _t('_Top Blogs');

		$iCheckedMemberID = $this->aBlogConf['visitorID'];
		$sRestrictRes = $this->CheckRestrictionToView($iCheckedMemberID);
		if ($sRestrictRes != '') return $sRestrictRes;

		$sBlogsSQL = "
			SELECT `Blogs`. * , `Profiles`.`Nickname` 
			FROM `Blogs` 
			INNER JOIN `Profiles` ON `Blogs`.`OwnerID` = `Profiles`.`ID`
		";

		//////////////////pagination addition//////////////////////////
		$iTotalNum = db_value( "SELECT COUNT(*) FROM `Blogs` INNER JOIN `Profiles` ON `Blogs`.`OwnerID` = `Profiles`.`ID`" );
		if( !$iTotalNum ) {
			return MsgBox($sNoBlogsC);
		}

		$iPerPage = (int)$_GET['per_page'];
		if( !$iPerPage )
			$iPerPage = $this->iPerPageElements;
		$iTotalPages = ceil( $iTotalNum / $iPerPage );

		$iCurPage = (int)$_GET['page'];

		if( $iCurPage > $iTotalPages )
			$iCurPage = $iTotalPages;

		if( $iCurPage < 1 )
			$iCurPage = 1;

		$sLimitFrom = ( $iCurPage - 1 ) * $iPerPage;
		$sqlLimit = "LIMIT {$sLimitFrom}, {$iPerPage}";
		////////////////////////////

		$sCaption = $sAllBlogsC;

		if ($sType == 'top') {
			$sBlogsSQL = "
				SELECT `Blogs`.`ID` , `Blogs`.`OwnerID` , `Blogs`.`Description` , `Profiles`.`Nickname` , MAX( `PostDate` ) AS 'MPD', COUNT( `BlogPosts`.`PostID` ) AS 'PostCount'
				FROM `Blogs` 
				INNER JOIN `BlogCategories` ON `BlogCategories`.`OwnerID` = `Blogs`.`OwnerID` 
				INNER JOIN `Profiles` ON `Profiles`.`ID` = `Blogs`.`OwnerID` 
				INNER JOIN `BlogPosts` ON `BlogPosts`.`CategoryID` = `BlogCategories`.`CategoryID`
				GROUP BY `Blogs`.`ID` 
				ORDER BY `PostCount` DESC
				{$sqlLimit}
			";
			$sCaption = $sTopBlogsC;
		} elseif ($sType == 'last') {
			$sBlogsSQL = "
				SELECT `Blogs`.`ID` , `Blogs`.`OwnerID` , `Blogs`.`Description` , `Profiles`.`Nickname` , MAX( `PostDate` ) AS 'MPD', COUNT( `BlogPosts`.`PostID` ) AS 'PostCount'
				FROM `Blogs` 
				INNER JOIN `BlogCategories` ON `BlogCategories`.`OwnerID` = `Blogs`.`OwnerID` 
				INNER JOIN `Profiles` ON `Profiles`.`ID` = `Blogs`.`OwnerID` 
				INNER JOIN `BlogPosts` ON `BlogPosts`.`CategoryID` = `BlogCategories`.`CategoryID`
				GROUP BY `Blogs`.`ID`
				ORDER BY `MPD` DESC
				{$sqlLimit}
			";
		}

		// process database queries
		$vBlogsRes = db_res( $sBlogsSQL );
		if (mysql_affected_rows()==0) {
			return MsgBox($sNoBlogsC);
		}
		while ( $aBlogsRes = mysql_fetch_assoc($vBlogsRes) ) {
			if ($aBlogsRes['PostCount'] == 0 && $sType == 'top') //in Top blogs skip blogs with 0 comments
				continue;

			$sCont = get_member_thumbnail($aBlogsRes['OwnerID'], 'left', TRUE );

			$sRetHtml .= <<<EOF
<div>
	<div class="clear_both"></div>
		{$sCont}
	<div class="cls_res_info_nowidth" {$sDataStyleWidth}>
		<div>
			<a href="{$_SERVER['PHP_SELF']}?action=show_member_blog&amp;ownerID={$aBlogsRes['OwnerID']}">
				{$aBlogsRes['Nickname']} Blog
			</a>
		</div>
		<div>
			{$aBlogsRes['PostCount']} {$sPostsC}
		</div>
		<div>
			{$sDescriptionC}: <div class="clr3">{$aBlogsRes['Description']}</div>
		</div>
	</div>
	<div class="clear_both"></div>
</div>
EOF;
		}

		/////////pagination addition//////////////////
		if( $iTotalPages > 1) {
			$sRequest = $_SERVER['PHP_SELF'] . '?';
			$aFields = array( 'action' );

			foreach( $aFields as $vField )
				if( isset( $_GET[$vField] ) )
					$sRequest .= "&amp;{$vField}=" . htmlentities( process_pass_data( $_GET[$vField] ) );

			$sPagination = '<div style="text-align: center; position: relative;">'._t("_Results per page").':
					<select name="per_page" onchange="window.location=\'' . $sRequest . '&amp;per_page=\' + this.value;">
						<option value="10"' . ( $iPerPage == 10 ? ' selected="selected"' : '' ) . '>10</option>
						<option value="20"' . ( $iPerPage == 20 ? ' selected="selected"' : '' ) . '>20</option>
						<option value="50"' . ( $iPerPage == 50 ? ' selected="selected"' : '' ) . '>50</option>
						<option value="100"' . ( $iPerPage == 100 ? ' selected="selected"' : '' ) . '>100</option>
					</select></div>' .
				genPagination( $iTotalPages, $iCurPage, ( $sRequest . '&amp;page={page}&amp;per_page='.$iPerPage ) );
		} else
			$sPagination = '';
		///////////////////////////

		return DesignBoxContent( $sCaption, $sRetHtml.$sPagination, 1 );
	}

	/**
	 * Generate List of Posts
	 *
	 * @param $sType - tyle of list ('top', 'last'), but now realized only Top posts
	 * @return HTML presentation of data
	 */
	function GenPostLists($sType = '') {
		global $site;

		$iCheckedMemberID = $this->aBlogConf['visitorID'];
		$sRestrictRes = $this->CheckRestrictionToView($iCheckedMemberID);
		if ($sRestrictRes != '') return $sRestrictRes;

		$sEditC = _t('_Edit');
		$sDeleteC = _t('_Delete');
		$sSureC = _t("_Are you sure");
		$sPostCommentC = _t('_Post Comment');
		$sDescriptionC = _t('_Description');
		$sAddCommentC = _t('_Add comment');
		$sNewPostC = _t('_New Post');
		$sTagsC = _t('_Tags');
		$sPostsC = _t('_Posts');
		$sCaption = _t('_Top Posts');

		$sDateFormatPhp = getParam('php_date_format');

		$sTopPostSQL = "
			SELECT `BlogPosts`.*, COUNT(`CommentID`) AS `CountComments`, `BlogCategories`.`OwnerID`,`BlogCategories`.`CategoryName`
			FROM `BlogPosts`
			LEFT JOIN `BlogPostComments` ON `BlogPosts`.`PostID`=`BlogPostComments`.`PostID`
			LEFT JOIN `BlogCategories` ON `BlogCategories`.`CategoryID`=`BlogPosts`.`CategoryID`
			GROUP BY `BlogPostComments`.`PostID`
			ORDER BY `CountComments` DESC, `PostDate` DESC
		";
		$vTopPostSQL = db_res($sTopPostSQL);

		if (mysql_affected_rows()>0) {
			while ( $aResSQL = mysql_fetch_assoc($vTopPostSQL) ) {
				if ($aResSQL['CountComments'] > 0) {
					$bFriend = is_friends( $this->aBlogConf['visitorID'], $aResSQL['OwnerID'] );
					$bOwner = ($this->aBlogConf['visitorID']==$aResSQL['OwnerID']) ? true : false;
					$sBlogPosts .= $this->GenPostString($aResSQL, 2);
				}
			}
			$sRetHtml = $sBlogPosts;
		} else {
			$sRetHtml = MsgBox(_t('_Sorry, nothing found'));
		}
		if ($sBlogPosts=='') {
			$sRetHtml = MsgBox(_t('_Sorry, nothing found'));
		}

		return DesignBoxContent ($sCaption, $sRetHtml, 1);
	}

	/**
	 * Generate Post Block by Array of data
	 *
	 * @param $aResSQL - Post Array Data
	 * @param $iView - type of view(1 is short view, 2 is full view, 3 is post view(short))
	 * @return HTML presentation of data
	 */
	function GenPostString($aResSQL, $iView=1) {
		global $site;

		$sEditC = _t('_Edit');
		$sDeleteC = _t('_Delete');
		$sSureC = _t("_Are you sure");
		$sPostCommentC = _t('_Post Comment');
		$sDescriptionC = _t('_Description');
		$sAddCommentC = _t('_Add comment');
		$sNewPostC = _t('_New Post');
		$sTagsC = _t('_Tags');
		$sPostsC = _t('_Posts');
		$sCommentsC = _t('_comments');

		$sDateFormatPhp = getParam('php_date_format');
		$sDateTime = date( $sDateFormatPhp, strtotime( $aResSQL['PostDate'] ) );

		$aProfileRes = $this -> GetProfileData($aResSQL['OwnerID']);

		$sTagsCommas = $aResSQL['Tags'];
		$aTags = split(',', $sTagsCommas);
		$sTagsHrefs = '';
		foreach( $aTags as $sTagKey ) {
			$sTagsHrefs .= <<<EOF
<a href="{$_SERVER['PHP_SELF']}?action=search_by_tag&amp;tagKey={$sTagKey}&amp;ownerID={$aResSQL['OwnerID']}" >{$sTagKey}</a>&nbsp;
EOF;
		}

		$sActions = '';
		if ($this->aBlogConf['visitorID']==$aResSQL['OwnerID'] || $this->bAdminMode==TRUE) {

			$sActions = <<<EOF
<div class="fr_small">
	<a href="{$_SERVER['PHP_SELF']}" onclick="javascript: UpdateField('EditPostID','{$aResSQL['PostID']}');document.forms.command_edit_post.submit();return false;" style="text-transform:none;">{$sEditC}</a>&nbsp;
	<a href="{$_SERVER['PHP_SELF']}" onclick="javascript: if (confirm('{$sSureC}')) {UpdateField('DeletePostID','{$aResSQL['PostID']}');UpdateField('DOwnerID','{$aResSQL['OwnerID']}');document.forms.command_delete_post.submit(); } return false;" style="text-transform:none;">{$sDeleteC}</a>
</div>
EOF;
		}

		$sAuthor = '';
		if ($iView==2) {
			$aMem = getProfileInfo( $aResSQL['OwnerID'] );
			//$sAuthorC = _t( '_By Author', $aMem['NickName'] );
			//$sAuthor = $sAuthorC; //"{$sAuthorC}<a class=\"\" style=\"font-weight:bold;\" href=\"".getProfileLink($aResSQL['OwnerID'])."\">{$aProfileRes['NickName']}</a>&nbsp;&nbsp;" ;
			$sAuthor = getProfileLink($aResSQL['OwnerID']);
			$sAuthor = '<a href="'.$sAuthor.'">'.$aMem['NickName'].'</a>';
		}

		//339933 color green
		$sPostText = ($iView==2) ? $aResSQL['PostText'] : $aResSQL['PostText'];
		if ($iView==2) $sActions='';
		$sTags = <<<EOF
<div class="fr_small_gray_centered">
	<span style="vertical-align:middle;"><img src="{$site['icons']}tag_small.png" class="marg_icon" alt="{$sTagsC}" /></span>{$sTagsC}:&nbsp;{$sTagsHrefs}
</div>
EOF;

		$sFriendStyle = "";
		$bFriend = is_friends( $this->aBlogConf['visitorID'], $aResSQL['OwnerID'] );
		$bOwner = ($this->aBlogConf['visitorID']==$aResSQL['OwnerID']) ? true : false;
		if( 'friends' == $aResSQL['PostReadPermission'] && !$bFriend && !$bOwner && !$this->bAdminMode ) {
			$sFriendStyle="1";
			$sMsgBox = MsgBox(_t('_this_blog_only_for_friends'));
			$sPostText = <<<EOF
<div class="clear_both"></div>
	<img src="{$site['icons']}lock.gif" alt="" class="marg_icon" style="float:right;position:relative;" />
	{$sMsgBox}
<div class="clear_both"></div>
EOF;
		}

		$sPostCaptionHref = <<<EOF
<a class="actions" href="{$_SERVER['PHP_SELF']}?action=show_member_post&amp;ownerID={$aResSQL['OwnerID']}&amp;post_id={$aResSQL['PostID']}">{$aResSQL['PostCaption']}</a>
EOF;

		$sPostPhoto = '';
		if ($iView==3) {
			$sPostCaptionHref = '<div class="actions">'.$aResSQL['PostCaption'].'</div>';

			if ( $aResSQL['PostPhoto'] ) {
				$sSpacerName = $site['url'].$this -> sSpacerPath;
				$sPostPhoto = <<<EOF
<div class="marg_both_left">
	<img alt="{$aResSQL['PostPhoto']}" style="width: {$this->iThumbSize}px; height: {$this->iThumbSize}px; background-image: url({$site['blogImage']}big_{$aResSQL['PostPhoto']});" src="{$sSpacerName}"/>
</div>
EOF;
			}
		}

		$sBlogPosts .= <<<EOF
<div>
<div>
	<div class="cls_res_thumb">
		{$sPostCaptionHref}
	</div>
	{$sActions}
	<div class="clear_both"></div>
</div>
<div class="fr_small_gray_centered">
	{$sAuthor}
	<span style="vertical-align:middle;"><img src="{$site['icons']}clock.gif" class="marg_icon" alt="{$sDateTime}" /></span><span class="margined">{$sDateTime}</span>
	<span style="vertical-align:middle;"><img src="{$site['icons']}add_comment.gif" class="marg_icon" alt="{$sAddCommentC}" title="{$sAddCommentC}" /></span><span class="margined">{$aResSQL['CountComments']} {$sCommentsC}</span>
	<span style="vertical-align:middle;"><img src="{$site['icons']}folder_small.png" class="marg_icon" alt="{$aResSQL['CategoryName']}" /></span>
	<a href="{$_SERVER['PHP_SELF']}?action=show_member_blog&amp;ownerID={$aResSQL['OwnerID']}&amp;category={$aResSQL['CategoryID']}">
		{$aResSQL['CategoryName']}
	</a>
</div>
<div class="blog_text{$sFriendStyle}">
	{$sPostPhoto}
	{$sPostText}
</div>
<div class="clear_both"></div>
{$sTags}
</div>
EOF;

		return $sBlogPosts;
	}

	/**
	 * Generate User Right Part for Blogs
	 *
	 * @param $aBlogsRes - Blog Array Data
	 * @param $iView - type of view(1 is short view, 2 is full view, 3 is post view(short))
	 * @return HTML presentation of data
	 */
	function GenMemberDescrAndCat($aBlogsRes, $iCategID = -1) {
		global $site;

		$sEditBlogC = _t('_Edit');
		$sDeleteBlogC = _t('_Delete');
		$sSureC = _t("_Are you sure");
		$sApplyChangesC = _t('_Apply Changes');
		$sDescriptionC = _t('_Description');
		$sCategoriesC = _t('_Categories');
		$sCategoryC = _t('_Category');
		$sTagsC = _t('_Tags');
		$sEditCategoryC = _t('_edit_category');
		$sAddCategoryC = _t('_add_category');
		$sCommentsC = _t('_Comments');
		$sPostsC = _t('_Posts');
		

		$sNewC = ucfirst(_t('_new'));

		$iMemberID = $aBlogsRes['OwnerID'];
		$sCont = get_member_icon($aBlogsRes['OwnerID'], 'left' );

		//tested, right
		$sCountPostSQL = "
			SELECT COUNT(*)
			FROM `BlogPosts`
			LEFT JOIN `BlogCategories` ON `BlogCategories`.`CategoryID` = `BlogPosts`.`CategoryID`
			WHERE `BlogCategories`.`OwnerId` = {$iMemberID}
		";

		$aCountPost = db_arr( $sCountPostSQL );
		$iCountPost = (int)$aCountPost[0];

		//tested, right
		$sCountCommentsSQL = "
			SELECT COUNT( * ) 
			FROM `BlogPostComments` 
			LEFT JOIN `BlogPosts` ON `BlogPosts`.`PostID` = `BlogPostComments`.`PostID` 
			LEFT JOIN `BlogCategories` ON `BlogCategories`.`CategoryID` = `BlogPosts`.`CategoryID` 
			WHERE `BlogCategories`.`OwnerId` = {$iMemberID}
		";

		$aCountComments = db_arr( $sCountCommentsSQL );
		$iCountComments = (int)$aCountComments[0];

		$sCategories = '';
		$sCategoriesSQL = "
			SELECT * 
			FROM `BlogCategories`
			WHERE `OwnerId` = {$iMemberID}
		";
		$vCategories = db_res( $sCategoriesSQL );
		$aTagsPost = array();
		$sTagsVals = '';

		if ($iCategID  > 0)
			$sPostsSQL = "SELECT `Tags`,`PostReadPermission`,`BlogCategories`.`OwnerID` FROM `BlogPosts`
				LEFT JOIN `BlogCategories` ON `BlogCategories`.`CategoryID` = `BlogPosts`.`CategoryID`
				WHERE `BlogCategories`.`CategoryID` = {$iCategID}";
		else
			$sPostsSQL = "SELECT `Tags`,`PostReadPermission`,`BlogCategories`.`OwnerID` FROM `BlogPosts`
				LEFT JOIN `BlogCategories` ON `BlogCategories`.`CategoryID` = `BlogPosts`.`CategoryID`
				WHERE `BlogCategories`.`OwnerID` = {$aBlogsRes['OwnerID']}";

		$vTags = db_res( $sPostsSQL );
		$aTagsPost = array();
		while ( $aPost = mysql_fetch_assoc($vTags) ) {
			$bFriend = is_friends( $this->aBlogConf['visitorID'], $aPost['OwnerID'] );
			$bOwner = ($this->aBlogConf['visitorID']==$aPost['OwnerID']) ? true : false;
			if( 'friends' == $aPost['PostReadPermission'] && !$bFriend && !$bOwner && !$this->bAdminMode ) {
			} else {
				$sTagsCommas = trim($aPost['Tags']);
				$aTags = explode(',', $sTagsCommas);
				foreach( $aTags as $sTagKey ) {
					if ($sTagKey!='') {
						if( isset($aTagsPost[$sTagKey]) )
							$aTagsPost[$sTagKey]++;
						else
							$aTagsPost[$sTagKey] = 1;
					}
				}
			}
		}
		foreach( $aTagsPost as $varKey => $varValue ) {
			$sTagImg = '<img src="'.$site['icons'].'tag.png" class="static_icon" alt="'.$varValue.'" /></span>';
			$sTagName = '<a class="actions" href="'.$_SERVER['PHP_SELF'].'?action=search_by_tag&amp;tagKey='.$varKey.'&amp;ownerID='.$iMemberID.'" style="text-transform:capitalize;" >'.$varKey.'</a>&nbsp;('.$varValue.')';
			$sTagsImgName = $this->GenCenteredActionsBlock($sTagImg, $sTagName);
			$sTagsVals .= <<<EOF
<div style="vertical-align:middle;height:25px;margin-bottom:5px;">
	{$sTagsImgName}
</div>
EOF;
		}
		while ( $aCategories = mysql_fetch_assoc($vCategories) ) {
			$sCountPostCatSQL = "
				SELECT COUNT(*)
				FROM `BlogPosts`
				LEFT JOIN `BlogCategories` ON `BlogCategories`.`CategoryID` = `BlogPosts`.`CategoryID`
				WHERE `BlogCategories`.`CategoryID` = {$aCategories['CategoryID']}
			";

			$aCountCatPost = db_arr( $sCountPostCatSQL );
			$iCountCatPost = (int)$aCountCatPost[0];

			$sAbilEditDelCateg = '';
			if ($this->aBlogConf['visitorID']==$aBlogsRes['OwnerID'] || $this->bAdminMode==TRUE) {
				$sDelAbilOnly = '';
				if ($aCategories['CategoryType']==1) {
					$sDelAbilOnly = <<<EOF
<a href="{$_SERVER['PHP_SELF']}" onclick="javascript: if (confirm('{$sSureC}')) {UpdateField('DeleteCategoryID','{$aCategories['CategoryID']}');document.forms.command_delete_category.submit(); } return false;">
	<span style="vertical-align:middle;"><img src="{$site['icons']}categ_delete.png" style="border:0 solid;position:static;" alt="{$sDeleteBlogC} {$sCategoryC}" /></span>
</a>
EOF;
				}
				$sAbilEditDelCateg = <<<EOF
<a href="{$_SERVER['PHP_SELF']}?action=edit_category&amp;categoryID={$aCategories['CategoryID']}&amp;ownerID={$iMemberID}" style="text-transform:none;">
	<span style="vertical-align:middle;"><img src="{$site['icons']}categ_edit.png" style="border:0 solid;position:static;" alt="{$sEditCategoryC}" /></span>
</a>
EOF;
				$sAbilEditDelCateg .= $sDelAbilOnly;
			}
			$sCatPic = ($aCategories['CategoryPhoto'])?$site['blogImage'].'small_'.$aCategories['CategoryPhoto']:"{$site['icons']}folder.png";

			$sCatName = $aCategories['CategoryName'];
			$sSpacerName = $site['url'].$this->sSpacerPath;

			$sCategories .= <<<EOF
<div class="pic_centered">
	<div class="clear_both"></div>
	<div class="cls_res_thumb" style="padding-right: 5px; padding-bottom: 5px;">
		<div class="thumbnail_block">
			<span style="vertical-align:middle;">
				<img class="icons" src="{$sSpacerName}" style="border:0 solid;width: 25px; height: 25px; background-image:url({$sCatPic});" alt="" />
			</span>
		</div>
	</div>

	<div class="cls_res_info_nowidth" style="width: 120px;">
		<a class="actions" href="{$_SERVER['PHP_SELF']}?action=show_member_blog&amp;ownerID={$iMemberID}&amp;category={$aCategories['CategoryID']}">{$sCatName}</a>&nbsp;({$iCountCatPost})
	</div>

	<div class="cls_res_thumb" style="float: right; position: relative;width:48px;vertical-align:middle;">
		<div class="cls_thumb">
			<div class="thumbnail_block">
				<span style="vertical-align:middle;">
					{$sAbilEditDelCateg}
				</span>
			</div>
		</div>
	</div>

	<div class="clear_both"></div>
</div>
EOF;
		}

		$sActions = '';
		if ($this->aBlogConf['visitorID']==$aBlogsRes['OwnerID'] || $this->bAdminMode==TRUE) {
			$sDescrAct = $this->ActionPrepareForEdit($aBlogsRes['Description']);
			$sDescrAct = str_replace( "\r\n", '', $sDescrAct );
			$sActions = <<<EOF
<div class="caption_item">
	<span style="vertical-align:middle;"><img src="{$site['icons']}description_edit.png" class="marg_icon" alt="{$sEditBlogC} {$sDescriptionC}" /></span>
	<a href="{$_SERVER['PHP_SELF']}" onclick="javascript: UpdateField('EditBlogID','{$aBlogsRes['ID']}'); UpdateField('Description','{$sDescrAct}'); UpdateField('EOwnerID','{$iMemberID}'); document.getElementById('edited_blog_div').style.display = 'block'; document.getElementById('SmallDesc').style.display = 'none'; return false;" style="text-transform:none;">{$sEditBlogC}</a>&nbsp;
	<a href="{$_SERVER['PHP_SELF']}" onclick="javascript: if (confirm('{$sSureC}')) {UpdateField('DeleteBlogID','{$aBlogsRes['ID']}');document.forms.command_delete_blog.submit(); } return false;" style="text-transform:none;">{$sDeleteBlogC}</a>
</div>
EOF;
		}

		$sProfLink = getProfileLink($aBlogsRes['OwnerID']);
		$sDescriptionContent = <<<EOF
<div class="cls_res_thumb">
	<div class="marg_both">
		{$sCont}
	</div>
</div>
<a class="actions" href="{$sProfLink}">{$aBlogsRes['Nickname']}</a>
<br />{$sPostsC}: {$iCountPost}<br />{$sCommentsC}: {$iCountComments}<br />
<span class="cls_res_info_p22" id="SmallDesc" style="display:block;text-align:justify;">
	{$aBlogsRes['Description']}
</span>
<div class="clear_both"></div>
<div id="edited_blog_div" style="display: none; position:relative;">
	<form action="{$_SERVER['PHP_SELF']}" method="post" name="EditBlogForm">
		<input type="hidden" name="action" id="action" value="edit_blog" />
		<input type="hidden" name="EditBlogID" id="EditBlogID" value=""/>
		<input type="hidden" name="EOwnerID" id="EOwnerID" value=""/>
		<textarea name="Description" id="Description" rows="4" cols="10" class="classfiedsTextArea1" style="width:210px;height:85px;">{$aBlogsRes['Description']}</textarea>
		<div style="text-align:center"><input type="submit" value="{$sApplyChangesC}"/></div>
	</form>
</div>
EOF;

		$sDescriptionSect = DesignBoxContent ( _t($sDescriptionC), $sDescriptionContent, 1 , $sActions);

		$sCategoriesActions = '';
		if ($this->aBlogConf['visitorID']==$aBlogsRes['OwnerID'] /*|| $this->bAdminMode==TRUE*/) {
			$sCategoriesActions = <<<EOF
<div class="caption_item">
	<span style="vertical-align:middle;"><img src="{$site['icons']}categ_add.png" class="marg_icon" alt="{$sAddCategoryC}" /></span>
	<a href="{$_SERVER['PHP_SELF']}?action=add_category&amp;ownerID={$iMemberID}" style="text-transform:none;">{$sNewC}</a>
</div>
EOF;
		}
		$sCategoriesSect = DesignBoxContent ( _t($sCategoriesC), $sCategories, 1 , $sCategoriesActions);
		$sTagsSect = DesignBoxContent ( _t($sTagsC), $sTagsVals, 1);

		return $sDescriptionSect . $sCategoriesSect . $sTagsSect;
	}

	/**
	 * Generate User`s Blog Page
	 *
	 * @param $iUserID - User ID
	 * @return HTML presentation of data
	 */
	function GenMemberBlog($iUserID = -1) {
		global $site;

		$iCheckedMemberID = $this->aBlogConf['visitorID'];
		$sRestrictRes = $this->CheckRestrictionToView($iCheckedMemberID);
		if ($sRestrictRes != '') return $sRestrictRes;

		$sRetHtml = '';
		$iMemberID = (int)$_REQUEST['ownerID'];
		if ($iUserID>0)
			$iMemberID = $iUserID;
		$iCategoryID = (int)$_REQUEST['category'];

		$sEditC = _t('_Edit');
		$sDeleteC = _t('_Delete');
		$sSureC = _t("_Are you sure");
		$sPostCommentC = _t('_Post Comment');
		$sDescriptionC = _t('_Description');
		$sAddCommentC = _t('_Add comment');
		$sNewPostC = _t('_New Post');
		$sTagsC = _t('_Tags');
		$sPostsC = _t('_Posts');

		$sBlogsSQL = "
			SELECT `Blogs`. * , `Profiles`.`Nickname` 
			FROM `Blogs` 
			LEFT JOIN `Profiles` ON `Blogs`.`OwnerID` = `Profiles`.`ID`
			WHERE `Blogs`.`OwnerID` = {$iMemberID}
			LIMIT 1
		";
		$aBlogsRes = db_arr( $sBlogsSQL );
		if (mysql_affected_rows()==0) {
			return $this->GenCreateBlogForm();
		}

		$sDateFormatPhp = getParam('php_date_format');

		$sCategoryAddon = ($iCategoryID>0) ? "AND `BlogPosts`.`CategoryID` = {$iCategoryID}" : '';
		$sBlogPosts = '';

		$sBlogPostsSQL = "
			SELECT `BlogPosts`.*, COUNT(`CommentID`) AS `CountComments`, `BlogCategories`.`OwnerID`,`BlogCategories`.`CategoryName`
			FROM `BlogPosts`
			LEFT JOIN `BlogPostComments`
			ON `BlogPosts`.`PostID`=`BlogPostComments`.`PostID`
			LEFT JOIN `BlogCategories`
			ON `BlogCategories`.`CategoryID`=`BlogPosts`.`CategoryID`
			WHERE `BlogCategories`.`OwnerId` = {$iMemberID}
			{$sCategoryAddon}
			GROUP BY `BlogPosts`.`PostID`
			ORDER BY `PostDate` DESC, `CountComments` DESC
		";

		$vBlogPosts = db_res( $sBlogPostsSQL );
		$sCurCategory = '';
		if ($iCategoryID>0) {
			$sBlogCategSQL = "
				SELECT `BlogCategories`.`CategoryName`
				FROM `BlogCategories`
				WHERE `BlogCategories`.`CategoryID` = {$iCategoryID}
				LIMIT 1
			";
			$aBlogCateg = db_arr($sBlogCategSQL);
			$sCurCategory = $aBlogCateg['CategoryName'];
		}
		while ( $aResSQL = mysql_fetch_assoc($vBlogPosts) ) {
			if( 'friends' == $aResSQL['PostReadPermission'] && !$this->aBlogConf['isFriend'] && !$this->aBlogConf['isOwner'] && !$this->bAdminMode ) {
				$sBlogPosts .= $this->GenPostString($aResSQL);
			} else {
				$sBlogPosts .= $this->GenPostString($aResSQL);
			}
		}

		$sNewPost = '';
		if ($this->aBlogConf['visitorID']==$aBlogsRes['OwnerID'] /*|| $this->bAdminMode==TRUE*/ ) {
			$sNewPost = <<<EOF
<div class="caption_item">
	<span style="vertical-align:middle;"><img src="{$site['icons']}post_new.png" class="marg_icon" alt="{$sNewPostC}" /></span>
	<a href="{$_SERVER['PHP_SELF']}?action=new_post" style="text-transform:none;">{$sNewPostC}</a>
</div>
EOF;
		}

		//<span style="vertical-align:middle;"><img src="{$site['icons']}rss.png" style="position:static;" alt="" /></span>
		$sCurCategory = ($sCurCategory!='')?' - '.$sCurCategory:'';
		$sPostsSect = DesignBoxContent ( _t($sPostsC), $sBlogPosts, 1, $sNewPost);

		$sRightSect = $this->GenMemberDescrAndCat($aBlogsRes,$iCategoryID);

		$sRetHtml = <<<EOF
<div>
	<div class="clear_both"></div>
	<div class="cls_info_left">
		{$sPostsSect}
	</div>
	<div class="cls_info">
		{$sRightSect}
	</div>
	<div class="clear_both"></div>
</div>
<div class="clear_both"></div>
EOF;

		return $sRetHtml;
	}

	/**
	 * SQL: Updating post by POSTed data
	 *
	 * @return MsgBox of result
	 */
	function ActionEditPost() {
		global $dir;

		$this->CheckLogged();

		$sSuccUpdPost = _t('_SUCC_UPD_POST');
		$sFailUpdPost = _t('_FAIL_UPD_ADV');

		$iCategoryID = process_db_input( (int)$_POST['categoryID'] );
		$sPostCaption = $this->process_html_db_input($_POST['caption'] );
		$sPostText = $this->process_html_db_input($_POST['blogText'] );
		$commentPerm = process_db_input( $_POST['commentPerm'] );
		$readPerm = process_db_input( $_POST['readPerm'] );
		$sTagsPerm = process_db_input( $_POST['tags'] );
		$aTags = explodeTags($sTagsPerm);
		$sTagsPerm = implode(",", $aTags);
		$iPostID = (int)($_POST['EditedPostID']);

		$sCheckPostSQL = "SELECT `BlogCategories`.`OwnerID`
							FROM `BlogPosts`
							LEFT JOIN `BlogCategories` ON `BlogCategories`.`CategoryID`=`BlogPosts`.`CategoryID`
							WHERE `PostID`={$iPostID}
						";
		$PostID = db_arr($sCheckPostSQL);
		$iPostOwnerID = $PostID['OwnerID'];
		if (($this->aBlogConf['visitorID'] == $iPostOwnerID || $this->bAdminMode) && $iPostID > 0) {
			$sFileNameExt = '';
			if ( 0 < $_FILES['BlogPic']['size'] && 0 < strlen( $_FILES['BlogPic']['name'] ) ) {
				$sPhotosSQL = "SELECT `PostPhoto` FROM `BlogPosts` WHERE `PostID` = {$iPostID} LIMIT 1";
				$aFiles = db_arr($sPhotosSQL);
				$sFileName = $aFiles['PostPhoto'];
				if ($sFileName=='') {
					$sFileName = 'blog_' . $iPostID;
				}
				$sExt = moveUploadedImage( $_FILES, 'BlogPic', $dir['blogImage'] . $sFileName, '', false );
				if( strlen( $sExt ) && !(int)$sExt ) {
					$sFileNameExt = $sFileName.$sExt;
					imageResize( $dir['blogImage'] . $sFileName.$sExt, $dir['blogImage'] . 'small_' . $sFileName.$sExt, $this->iIconSize / 2, $this->iIconSize / 2);
					imageResize( $dir['blogImage'] . $sFileName.$sExt, $dir['blogImage'] . 'big_' . $sFileName.$sExt, $this->iThumbSize, $this->iThumbSize);

					chmod( $dir['blogImage'] . 'small_' . $sFileName.$sExt, 0644 );
					chmod( $dir['blogImage'] . 'big_' . $sFileName.$sExt, 0644 );

					@unlink( $dir['blogImage'] . $sFileName . $sExt );
				}
			}

			$sAutoApprovalVal = (getParam('blogAutoApproval')=='on') ? "approval" : "disapproval";
			$sPostPic = ($sFileNameExt=='') ? '' : "`PostPhoto`='{$sFileNameExt}',";
			$sQuery = "
				UPDATE `BlogPosts` SET
				`CategoryID`={$iCategoryID},
				`PostCaption`='{$sPostCaption}',
				`PostText`='{$sPostText}',
				`PostCommentPermission`='{$commentPerm}',
				`PostReadPermission`='{$readPerm}',
				`Tags`='{$sTagsPerm}',
				{$sPostPic}
				`PostStatus`='{$sAutoApprovalVal}'
				WHERE `PostID`={$iPostID}
			";

			$vSqlRes = db_res( $sQuery );
			$sRet = (mysql_affected_rows()>0) ? _t($sSuccUpdPost) : _t($sFailUpdPost);
			reparseObjTags( 'blog', $iPostID );
			return MsgBox($sRet);
		} elseif($this->aBlogConf['visitorID'] != $iPostOwnerID) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}

	/**
	 * SQL: Delete post by POSTed data
	 *
	 * @return MsgBox of result
	 */
	function ActionDeletePost() {
		$this->CheckLogged();

		$iPostID = (int)($_POST['DeletePostID']);

		$sCheckPostSQL = "SELECT `BlogCategories`.`OwnerID`
							FROM `BlogPosts`
							LEFT JOIN `BlogCategories` ON `BlogCategories`.`CategoryID`=`BlogPosts`.`CategoryID`
							WHERE `PostID`={$iPostID}
						";
		$PostID = db_arr($sCheckPostSQL);
		$iPostOwnerID = $PostID['OwnerID'];
		if (($this->aBlogConf['visitorID'] == $iPostOwnerID || $this->bAdminMode) && $iPostID > 0) {
			db_res( "DELETE FROM `BlogPostComments` WHERE `PostID` = {$iPostID}" );
			$sQuery = "DELETE FROM `BlogPosts` WHERE `BlogPosts`.`PostID` = {$iPostID} LIMIT 1";
			$vSqlRes = db_res( $sQuery );
			$sRet = (mysql_affected_rows()>0) ? _t('_post_successfully_deleted') : _t('_failed_to_delete_post');
			reparseObjTags( 'blog', $iPostID );
			return MsgBox($sRet);
		} elseif($this->aBlogConf['visitorID'] != $iPostOwnerID) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}

	/**
	 * Generate User`s Blog Post Page
	 *
	 * @return HTML presentation of data
	 */
	function GenPostPage() {
		global $site;
		global $aBreadCramp;

		$iCheckedMemberID = $this->aBlogConf['visitorID'];
		$sRestrictRes = $this->CheckRestrictionToView($iCheckedMemberID);
		if ($sRestrictRes != '') return $sRestrictRes;

		$iPostID = (int)$_REQUEST['post_id'];
		if ($this->iLastPostedPostID>0) {
			$iPostID = $this->iLastPostedPostID;
			$this->iLastPostedPostID = -1;
		}

		$sCategoryC = _t( '_Category' );
		$sPostC = _t( '_Post' );
		$sEditC = _t('_Edit');
		$sDeleteC = _t('_Delete');

		$sRetHtml = '';

		$sPostedBySQL = "
			SELECT `BlogCategories`.`OwnerID` FROM `BlogCategories`
			LEFT JOIN `BlogPosts` ON `BlogCategories`.`CategoryID`=`BlogPosts`.`CategoryID`
			WHERE `BlogPosts`.`PostID` = {$iPostID}
			LIMIT 1
		";
		$aPostBy = db_arr($sPostedBySQL);

		if (mysql_affected_rows()==0) {
			return MsgBox(_t('_No such blog post'));
		}

		$sBlogsSQL = "
			SELECT `Blogs`. * , `Profiles`.`Nickname` 
			FROM `Blogs` 
			LEFT JOIN `Profiles` ON `Blogs`.`OwnerID` = `Profiles`.`ID`
			WHERE `Blogs`.`OwnerID` = {$aPostBy['OwnerID']}
			LIMIT 1
		";
		$aBlogInfo = db_arr($sBlogsSQL);

		$sBlogPostSQL = "
				SELECT `BlogPosts`.*, COUNT(`CommentID`) AS `CountComments`, `BlogCategories`.`OwnerID`,`BlogCategories`.`CategoryName`
				FROM `BlogPosts`
				LEFT JOIN `BlogPostComments`
				ON `BlogPosts`.`PostID`=`BlogPostComments`.`PostID`
				LEFT JOIN `BlogCategories`
				ON `BlogCategories`.`CategoryID`=`BlogPosts`.`CategoryID`
				WHERE `BlogPosts`.`PostID` = {$iPostID}
				GROUP BY `BlogPostComments`.`PostID`
				ORDER BY `CountComments` DESC, `PostDate` DESC
		";

		$aBlogPost = db_arr( $sBlogPostSQL );

		if( 'friends' == $aBlogPost['PostReadPermission'] && !$this->aBlogConf['isFriend'] && !$this->aBlogConf['isOwner'] && !$this->bAdminMode ) {
			$sFriendsC .= MsgBox(_t('_this_blog_only_for_friends'));
			$sRightSection = $this->GenMemberDescrAndCat($aBlogInfo);
			$sRetHtml .= <<<EOF
<div>
	<div class="clear_both"></div>
	<div class="cls_info_left">
		<div>
			<div class="disignBoxFirst">
				<div class="boxFirstHeader">{$sPostC}</div>
				<div class="boxContent">
					{$sFriendsC}
				</div>
			</div>
		</div>
		<div class="clear_both"></div>
	</div>
	<div class="cls_info">
		{$sRightSection}
	</div>
	<div class="clear_both"></div>
</div>
<div class="clear_both"></div>
EOF;
		} else {

			$oComments = new BxDolComments(2);
			$oComments->bAdminMode = $this->bAdminMode;
			$sPostComm = $oComments->PrintCommentSection($iPostID);
			$sPostString = $this->GenPostString($aBlogPost,3);
			$sRightSection = $this->GenMemberDescrAndCat($aBlogInfo);

			$sRetHtml = <<<EOF
<div>
	<div class="clear_both"></div>
	<div class="cls_info_left">
		<div>
			<div class="disignBoxFirst">
				<div class="boxFirstHeader">{$sPostC}</div>
				<div class="boxContent">
					{$sPostString}
				</div>
			</div>
		</div>
		<div class="clear_both"></div>
		<div>
			{$sPostComm}
		</div>
	</div>
	<div class="cls_info">
		{$sRightSection}
	</div>
	<div class="clear_both"></div>
</div>
<div class="clear_both"></div>
EOF;
		}

		return $sRetHtml;
	}

	/**
	 * Generate Form for NewPost/EditPost
	 *
	 * @param $iPostID - Post ID
	 * @param $arrErr - Array for PHP validating
	 * @return HTML presentation of data
	 */
	function AddNewPostForm($iPostID=-1, $arrErr = NULL) {
		global $site;

		$this->CheckLogged();

		$iCheckedMemberID = $this->aBlogConf['visitorID'];
		$sRestrictRes = $this->CheckRestrictionToUse($iCheckedMemberID);
		if ($sRestrictRes != '') return $sRestrictRes;

		$sPostCaptionC = _t('_Post') . ' ' . _t('_Caption');
		$sCharactersLeftC = _t('_characters_left');
		$sPostTextC = _t('_Post') . ' ' . _t('_Text');
		$sPleaseSelectC = _t('_please_select');
		$sAssociatedImageC = _t('_associated_image');
		$sPostCommentPerC = _t('_post_comment_per');
		$sPublicC = _t('_public');
		$sFriendsOnlyC = _t('_friends only');
		$sPostReadPerC = _t('_post_read_per');
		$sAddBlogC = _t('_Add Post');
		$sCommitC = _t('_Apply Changes');
		$sTagsC = _t('_Tags');
		$sNewPostC = _t('_New Post');

		$sBlogsSQL = "
			SELECT `Blogs`. *
			FROM `Blogs` 
			WHERE `Blogs`.`OwnerID` = {$this->aBlogConf['visitorID']}
			LIMIT 1
		";
		$aBlogsRes = db_arr( $sBlogsSQL );
		if (mysql_affected_rows()==0) {
			return $this->GenCreateBlogForm();
		}

		$sRetHtml = '';

		$sCATIDstyle = ($arrErr['CategoryID'] ? 'block' : 'none');
		$sCPTstyle = ($arrErr['Caption'] ? 'block' : 'none');
		$sPTstyle = ($arrErr['PostText'] ? 'block' : 'none');
		$sCPstyle = ($arrErr['CommentPerm'] ? 'block' : 'none');
		$sRPstyle = ($arrErr['ReadPerm'] ? 'block' : 'none');

		$sCATIDmsg = ($arrErr['CategoryID'] ? _t( '_'.$arrErr['CategoryID'] ) : '' );
		$sCPTmsg = ($arrErr['Caption'] ? _t( '_'.$arrErr['Caption'] ) : '' );
		$sPTmsg = ($arrErr['PostText'] ? _t( '_'.$arrErr['PostText'] ) : '' );
		$sCPmsg = ($arrErr['CommentPerm'] ? _t( '_'.$arrErr['CommentPerm'] ) : '' );
		$sRPmsg = ($arrErr['ReadPerm'] ? _t( '_'.$arrErr['ReadPerm'] ) : '' );

		$sPostCaption = '';
		$sPostText = '';
		$sPostImage = '';
		$sPostTags = '';
		$sCheckedCommPermP = 'checked ';
		$sCheckedReadPostPermP = 'checked ';
		$sCheckedCommPermF = '';
		$sCheckedReadPostPermF = '';
		$sPostPicture = '';
		$sPostPictureTag = '';
		$sPostAction = 'add_post';
		$iSavedCategoryID = -1;

		if ($iPostID>0) {
			$sBlogPostsSQL = "SELECT * FROM `BlogPosts` WHERE `PostID` = {$iPostID} LIMIT 1";
			$aBlogPost = db_arr( $sBlogPostsSQL );
			$sPostCaption = $aBlogPost['PostCaption'];
			$sPostText = $aBlogPost['PostText'];
			$sPostImage = $aBlogPost['PostPhoto'];
			$sPostTags = $aBlogPost['Tags'];
			$sPostPicture = $aBlogPost['PostPhoto'];
			$sSpacerName = $site['url'].$this -> sSpacerPath;
			if ($sPostImage != '') 
				$sPostPictureTag = '<div class="marg_both_left"><img alt="" style="width: 110px; height: 110px; background-image: url('.$site['blogImage'].'big_'.$sPostImage.');" src="'.$sSpacerName.'"/></div>';
			$sCheckedCommPerm = $aBlogPost['PostCommentPermission'];
			$sCheckedReadPostPerm = $aBlogPost['PostReadPermission'];
			if ($sCheckedCommPerm=='public')
				$sCheckedCommPermP = 'checked ';
			else
				$sCheckedCommPermF = 'checked ';
			if ($sCheckedReadPostPerm=='public')
				$sCheckedReadPostPermP = 'checked ';
			else
				$sCheckedReadPostPermF = 'checked ';

			$sAddBlogC = $sCommitC;
			$sPostAction = 'post_updated';
			$sEditIdStr = '<input type="hidden" name="EditedPostID" value="'.$iPostID.'">';
		} else {
			$iSavedCategoryID = process_db_input( (int)$_POST['categoryID'] );

			$sPostCaption = process_db_input( $_POST['caption'] );
			$sPostText = process_db_input( $_POST['blogText'] );
			$sPostImage = '';
			$sPostTags = process_db_input( $_POST['tags'] );
			if (isset($_POST['commentPerm']) && isset($_POST['readPerm'])) {
				if (process_db_input($_POST['commentPerm'])=='public') {
					$sCheckedCommPermP = 'checked ';
				} else {
					$sCheckedCommPermF = 'checked ';
				}
				if (process_db_input($_POST['readPerm'])=='public') {
					$sCheckedReadPostPermP = 'checked ';
				} else {
					$sCheckedReadPostPermF = 'checked ';
				}
			}
		}

		$iOwner = $this->aBlogConf['visitorID'];
		if ($iPostID>0)
			$iOwner = db_value("SELECT `OwnerID` FROM `BlogCategories`
								INNER JOIN `BlogPosts` ON `BlogPosts`.`CategoryID` = `BlogCategories`.`CategoryID` 
								WHERE `BlogPosts`.`PostID` = {$iPostID}");

		//$sCategories = '';
		$sCategoriesSQL = "
			SELECT * 
			FROM `BlogCategories`
			WHERE `OwnerId` = {$iOwner}
		";

		$vCategories = db_res( $sCategoriesSQL );
		$sCategOptions = '';
		while ( $aCategories = mysql_fetch_assoc($vCategories) ) {
			if ($iSavedCategoryID>0 && $iSavedCategoryID==$aCategories['CategoryID'] )
				$sSelected = ' selected="selected"';
			else
				$sSelected = '';
			$sCategOptions .= '<option value="'.$aCategories['CategoryID'].'"'.$sSelected.'>'.process_line_output(strmaxtextlen($aCategories['CategoryName'])).'</option>'."\n";
		}

		$sCategPicture = '<img src="'.$site['icons'].'folder.png" style="position:static;" alt="'.$sPleaseSelectC.'" />';
		$sCategSelect = '<select name="categoryID" id="categoryID" >'.$sCategOptions.'</select>';
		$sCategPictSpans = $this->GenCenteredActionsBlock($sCategPicture, $sCategSelect);

		$sRetHtml .= <<<EOF
<div class="categoryBlock">
	<form action="{$_SERVER['PHP_SELF']}" enctype="multipart/form-data" method="post">
		<div class="margin_bottom_10">
			{$sPostCaptionC} ( <span id="captCounter">{$this->aBlogConf['blogCaptionMaxLenght']}</span> {$sCharactersLeftC} )
		</div>
		<div class="margin_bottom_10">
			<div class="edit_error" style="display:{$sCPTstyle}">
				{$sCPTmsg}
			</div>
			<input type="text" size="70" name="caption" id="caption" class="categoryCaption1" value="{$sPostCaption}" onkeydown="return charCounter('caption', '{$this->aBlogConf['categoryCaptionMaxLenght']}', 'captCounter');" />
		</div>
		<div class="margin_bottom_10">
			{$sTagsC}
		</div>
		<div class="margin_bottom_10">
			<input type="text" size="70" name="tags" id="tags" value="{$sPostTags}" />
		</div>
		<div class="margin_bottom_10">
			{$sPostTextC}
		</div>
		<div class="blogTextAreaKeeper">
			<div class="edit_error" style="display:{$sPTstyle}">
				{$sPTmsg}
			</div>
			<textarea name="blogText" rows="20" cols="60" class="classfiedsTextArea" style="width:630px;" id="desc">{$sPostText}</textarea>
		</div>
		<br />
		<div class="clear_both"></div>
		<div class="margin_bottom_10">
			<div class="edit_error" style="display:{$sCATIDstyle}">
				{$sCATIDmsg}
			</div>
			{$sCategPictSpans}
		</div>
		<div class="assocImageBlock">
			<div class="margin_bottom_10">
				{$sAssociatedImageC}
			</div>
			<div class="margin_bottom_10">
				<input type="file" name="BlogPic">
			</div>
			{$sPostPictureTag}
			<div class="clear_both"></div>
		</div>
		<div class="margin_bottom_10">
			<div class="margined_left">{$sPostCommentPerC}
			</div>
			<div class="margined_left">
				<div class="edit_error" style="display:{$sCPstyle}">
					{$sCPmsg}
				</div>
				<input type="radio" {$sCheckedCommPermP} name="commentPerm" value="public" checked="checked" />
				{$sPublicC}<br />
				<input type="radio" {$sCheckedCommPermF} name="commentPerm" value="friends" />
				{$sFriendsOnlyC}
			</div>
			<div class="margined_left">{$sPostReadPerC}
			</div>
			<div class="margined_left">
				<div class="edit_error" style="display:{$sRPstyle}">
					{$sRPmsg}
				</div>
				<input type="radio" {$sCheckedReadPostPermP} name="readPerm" value="public" />
				{$sPublicC}<br />
				<input type="radio" {$sCheckedReadPostPermF} name="readPerm" value="friends" />
				{$sFriendsOnlyC}
			</div>
			<div class="clear_both"></div>
		</div>
		<!-- <table cellpadding="2" cellspacing="0" border="0">
			<tr>
				<td rowspan="2">
					{$sPostCommentPerC}
				</td>
				<td>
					<div class="edit_error" style="display:{$sCPstyle}">
						{$sCPmsg}
					</div>
					<input type="radio" {$sCheckedCommPermP} name="commentPerm" value="public" checked="checked" />
				</td>
				<td>
					{$sPublicC}
				</td>
			</tr>
			<tr>
				<td>
					<input type="radio" {$sCheckedCommPermF} name="commentPerm" value="friends" />
				</td>
				<td>
					{$sFriendsOnlyC}
				</td>
			</tr>
			<tr>
				<td rowspan="2">
					{$sPostReadPerC}
				</td>
				<td>
					<div class="edit_error" style="display:{$sRPstyle}">
						{$sRPmsg}
					</div>
					<input type="radio" {$sCheckedReadPostPermP} name="readPerm" value="public" />
				</td>
				<td>
					{$sPublicC}
				</td>
			</tr>
			<tr>
				<td>
					<input type="radio" {$sCheckedReadPostPermF} name="readPerm" value="friends" />
				</td>
				<td>
					{$sFriendsOnlyC}
				</td>
			</tr>
		</table> -->
		<div class="margin_bottom_10" style="text-align: center;">
			<input type="submit" value="{$sAddBlogC}" />
			<input type="hidden" name="action" value="{$sPostAction}" />
			<input type="hidden" name="show" value="blogList" />
			{$sEditIdStr}
		</div>
	</form>
</div>
EOF;

		return DesignBoxContent ($sNewPostC, $sRetHtml, 1);
	}

	/**
	 * Compose Array of posted data before validating (add/delete a post)
	 *
	 * @return Array
	 */
	function GetPostArrByPostValues() {
		$iCategoryID = process_db_input( (int)$_POST['categoryID'] );
		$sPostCaption = process_db_input( $_POST['caption'] );
		$sPostText = process_db_input( $_POST['blogText'] );
		$commentPerm = process_db_input( $_POST['commentPerm'] );
		$readPerm = process_db_input( $_POST['readPerm'] );
		$sTags = process_db_input( $_POST['tags'] );

		$arr = array('CategoryID' => $iCategoryID, 'Caption' => $sPostCaption, 'PostText' => $sPostText,
			'CommentPerm' => $commentPerm, 'ReadPerm' => $readPerm, 'Tags' => $sTags);
		return $arr;
	}

	/**
	 * Compose Array of errors during filling (validating)
	 *
	 * @param $arrAdv	Input Array with data
	 * @return Array with errors
	 */
	function GetCheckErrors( $arrAdv ) {
		$arrErr = array();
		foreach( $arrAdv as $sFieldName => $sFieldValue ) {
			switch( $sFieldName ) {
				case 'CategoryID':
					if( $sFieldValue < 1)
						$arrErr[ $sFieldName ] = "{$sFieldName} is required";
				break;
				case 'Caption':
					if( !strlen($sFieldValue) )
						$arrErr[ $sFieldName ] = "{$sFieldName} is required";
				break;
				case 'PostText':
					if( strlen($sFieldValue) < 50 )
						$arrErr[ $sFieldName ] = "{$sFieldName} must be 50 symbols at least";
				break;
				case 'CommentPerm':
					if( !strlen($sFieldValue) )
						$arrErr[ $sFieldName ] = "{$sFieldName} is required";
				break;
				case 'ReadPerm':
					if( !strlen($sFieldValue) )
						$arrErr[ $sFieldName ] = "{$sFieldName} is required";
				break;
			}
		}
		return $arrErr;
	}

	/**
	 * Adding a New Post SQL
	 *
	 * @param $iLastID - returning Last Inserted ID (SQL) (just try)
	 * @return HTML presentation of data
	 */
	function ActionAddNewPost(&$iLastID) {
		global $dir;

		$this->CheckLogged();

		$iCategoryID = process_db_input( (int)$_POST['categoryID'] );

		$sCheckPostSQL = "SELECT `OwnerID`
							FROM `BlogCategories`
							WHERE `CategoryID`={$iCategoryID}
						";
		$aCategoryOwner = db_arr($sCheckPostSQL);
		$iCategoryOwnerID = $aCategoryOwner['OwnerID'];
		if ($this->aBlogConf['visitorID'] == $iCategoryOwnerID && $iCategoryID > 0) {
			$sPostCaption = process_db_input( $_POST['caption'] );
			$sPostText = process_db_input( $_POST['blogText'] );
			$commentPerm = process_db_input( $_POST['commentPerm'] );
			$readPerm = process_db_input( $_POST['readPerm'] );
			$sTagsPerm = process_db_input( $_POST['tags'] );
			$aTags = explodeTags($sTagsPerm);
			$sTagsPerm = implode(",", $aTags);
			$queryActionAdd = " INSERT INTO ";

			//$queryImgAdd = " `blogPhoto` = '$blogImage', ";

			$sAutoApprovalVal = (getParam('blogAutoApproval')=='on') ? "approval" : "disapproval";
			$addQuery = "
				{$queryActionAdd} `BlogPosts`
				SET
					`CategoryID` = '{$iCategoryID}',
					`PostCaption` = '{$sPostCaption}',
					`PostText` = '{$sPostText}',
					/*$queryImgAdd*/
					`PostReadPermission` = '{$readPerm}',
					`PostCommentPermission` = '{$commentPerm}',
					`PostStatus` = '{$sAutoApprovalVal}',
					`Tags` = '{$sTagsPerm}',
					`PostDate` = NOW()
			";

			$sRet = _t('_failed_to_add_post');
			if( db_res( $addQuery ) ) {
				$iLastId = mysql_insert_id();
				$this->iLastPostedPostID = $iLastId;
				if ( 0 < $_FILES['BlogPic']['size'] && 0 < strlen( $_FILES['BlogPic']['name'] ) && 0 < $iLastId ) {
					$sFileName = 'blog_' . $iLastId;
					$sExt = moveUploadedImage( $_FILES, 'BlogPic', $dir['blogImage'] . $sFileName, '', false );
					if( strlen( $sExt ) && !(int)$sExt ) {
						imageResize( $dir['blogImage'] . $sFileName.$sExt, $dir['blogImage'] . 'small_' . $sFileName.$sExt, $this->iIconSize / 2, $this->iIconSize / 2);
						imageResize( $dir['blogImage'] . $sFileName.$sExt, $dir['blogImage'] . 'big_' . $sFileName.$sExt, $this->iThumbSize, $this->iThumbSize);

						chmod( $dir['blogImage'] . 'small_' . $sFileName . $sExt, 0644 );
						chmod( $dir['blogImage'] . 'big_' . $sFileName . $sExt, 0644 );

						$query = "UPDATE `BlogPosts` SET `PostPhoto` = '" . $sFileName . $sExt . "' WHERE `PostID` = '$iLastId'";
						db_res( $query );
						@unlink( $dir['blogImage'] . $sFileName . $sExt );
					}
				}
				if ($iLastId>0) {
					$sRet = _t('_post_successfully_added');
					reparseObjTags( 'blog', $iLastId );
				}
			}
			return MsgBox($sRet);
		} elseif($this->aBlogConf['visitorID'] != $iCategoryOwnerID) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}

	/**
	 * Generate a Form to Editing/Adding of Category of Blog
	 *
	 * @param $categoryID - category ID
	 * @return HTML presentation of data
	 */
	function GenEditCategoryForm( $iCategoryID = '' ) {
		global $aBlogConfig;
		global $aBreadCramp;
		global $site;

		$this->CheckLogged();

		$iCheckedMemberID = $this->aBlogConf['visitorID'];
		$sRestrictRes = $this->CheckRestrictionToUse($iCheckedMemberID);
		if ($sRestrictRes != '') return $sRestrictRes;

		$sBlogsSQL = "
			SELECT `Blogs`. *
			FROM `Blogs` 
			WHERE `Blogs`.`OwnerID` = {$this->aBlogConf['visitorID']}
			LIMIT 1
		";
		$aBlogsRes = db_arr( $sBlogsSQL );
		if (mysql_affected_rows()==0) {
			return $this->GenCreateBlogForm();
		}

		$sRetHtml = '';
		if( !$this->aBlogConf['editAllowed'] && !$this->bAdminMode) {
			$ret .= _t_err( '_you_have_no_permiss_to_edit' );
			$sRetHtml = $ret;
		} else {
			if( $_REQUEST['action'] == 'edit_category' ) {
				$sCategorySQL = "
					SELECT * 
					FROM `BlogCategories`
					WHERE `CategoryID` = {$iCategoryID}
					LIMIT 1
				";
				$aCategory = db_arr( $sCategorySQL );
				$categCaption = $aCategory['CategoryName'];
				$categImg = $aCategory['CategoryPhoto'];
			} else {
				$categCaption = '';
				$categDesc = '';
				$categImg = '';
			}

			$sCategoryCaptionC = _t('_category_caption');
			$sPleaseFillFieldsC = _t('_please_fill_next_fields_first');

			$sRetHtml .= <<<EOF
<script type="text/javascript">
	function checkForm() {
		var el;
		var hasErr = false;
		var fild = "";

		el = document.getElementById("caption");
		if( el.value.length < 3 ) {
			el.style.backgroundColor = "pink";
			el.style.border = "1px solid silver";
			hasErr = true;
			fild += "{$sCategoryCaptionC}";
		} else {
			el.style.backgroundColor = "#fff";
		}

		if (hasErr) {
			alert( "{$sPleaseFillFieldsC}!" + fild )
			return false;
		} else {
			return true;
		}
		return false;
	}
</script>
EOF;

			$sCategoryCaptionC = _t('_category_caption');
			$sCharactersLeftC = _t('_characters_left');
			//$sCategoryDescriptionC = _t('_category_description');
			$sAssociatedImageC = _t('_associated_image');
			$sApplyChangesC = _t('apply changes');
			$sAddCategoryC = _t('_add_category');
			$sEditCategoryC = _t('_edit_category');

			$sBlogPhoto = '';
			if ( $categImg ) {
				$sBlogPhoto = <<<EOF
<div class="blogPhoto">
		<img src="{$site['blogImage']}big_{$categImg}" alt="" />
</div>
EOF;
			}

			$sEditCategory = '';
			if( 'edit_category' == $_REQUEST['action']  ) {
				$sEditCategory = <<<EOF
<input type="submit" value="{$sApplyChangesC}" />
<input type="hidden" name="action" value="editcategory" />
<input type="hidden" name="categoryID" value="{$iCategoryID}" />
<input type="hidden" name="categoryPhoto" value="{$categImg}" />
EOF;
			} else {
				$sEditCategory = <<<EOF
<input type="submit" value="{$sAddCategoryC}" />
<input type="hidden" name="action" value="addcategory" />
EOF;
			}

			$iMemberID = (int)process_db_input( $_REQUEST['ownerID']);

			$sCategImg = '<img src="'.$site['icons'].'folder.png" style="position:static;" />';
			$sCategInput = '<input type="" name="categoryCaption" id="caption" value="'.$categCaption.'" class="categoryCaption1" onkeydown="return charCounter(\'caption\', 150, \'captCounter\');" />';
			$sCategInputImg = $this->GenCenteredActionsBlock($sCategImg, $sCategInput);

			$sRetHtml .= <<<EOF
<div>
	<form action="{$_SERVER['PHP_SELF']}?action=show_member_blog&ownerID={$this->aBlogConf['visitorID']}" enctype="multipart/form-data" method="post" onsubmit="return checkForm();">
		<div class="margin_bottom_10">
			{$sCategoryCaptionC} (<span id="captCounter">150</span>{$sCharactersLeftC})
		</div>
		<div class="margin_bottom_10">
			{$sCategInputImg}
		</div>
		<div class="assocImageBlock">
			<div style="margin-bottom:5px;">
				{$sAssociatedImageC}
			</div>
			{$sBlogPhoto}
			<div class="margin_bottom_10">
				<input type="file" name="CategPic" />
			</div>
			<div class="clear_both"></div>
		</div>
		{$sEditCategory}
		<input type="hidden" name="ownerID" value="{$iMemberID}" />
	</form>
</div>
EOF;
		}
		return DesignBoxContent ($sEditCategoryC, $sRetHtml, 1);
		//return $sRetHtml;
	}

	/**
	 * Update (Adding or Editing) a Category
	 *
	 * @param $bEditMode - Update (Editing) mode
	 * @return MsgBox result
	 */
	function ActionUpdateCategory($bEditMode=FALSE) {
		global $aBlogConfig;
		global $dir;

		$this->CheckLogged();

		$ownerID = (int)process_db_input( $_REQUEST['ownerID']);
		$iCategoryID = process_db_input( (int)$_POST['categoryID'] );

		$sCheckPostSQL = "SELECT `BlogCategories`.`OwnerID`
							FROM `BlogCategories`
							WHERE `BlogCategories`.`CategoryID`={$iCategoryID}
						";
		$aCategoryOwner = db_arr($sCheckPostSQL);
		$iCategoryOwnerID = $aCategoryOwner['OwnerID'];

		if ((($this->aBlogConf['visitorID'] == $iCategoryOwnerID || $this->bAdminMode==TRUE) && $iCategoryID > 0 && $bEditMode==TRUE) || ($bEditMode==FALSE && $iCategoryID==0 && $ownerID==$this->aBlogConf['visitorID'])) {
			$ret = '';

			$categoryCaption = process_db_input( $_POST['categoryCaption']);
			$categoryPhoto = process_db_input( $_POST['categoryPhoto']);

			if ($bEditMode==TRUE) {
				$addQuery = "
					UPDATE `BlogCategories` SET
					`CategoryName` = '{$categoryCaption}',
					`Date` = NOW( ) WHERE `CategoryID` = '{$iCategoryID}'
					LIMIT 1
				";
			} else {
				$addQuery = "
					INSERT INTO `BlogCategories` SET
					`CategoryID` = '{$iCategoryID}',
					`OwnerID` = '{$ownerID}',
					`CategoryName` = '{$categoryCaption}',
					`CategoryPhoto` = '{$categoryPhoto}',
					`Date` = NOW()
				";
			}

		if ($bEditMode==true) {
			$aCatInfo = db_arr("SELECT `CategoryPhoto`, `CategoryID` FROM `BlogCategories` WHERE `CategoryID`= {$iCategoryID}");
			$sFileName = $aCatInfo['CategoryPhoto'];
			if ($sFileName == '') {
				$sFileName = 'category_' . $aCatInfo['CategoryID'];
			}
		}

			db_res( $addQuery );
			$iID = ($bEditMode == true) ? $iCategoryID : mysql_insert_id();
			if( mysql_affected_rows() == 1 || $_FILES['CategPic']['size'] > 0) {
				if ( 0 < $_FILES['CategPic']['size'] && 0 < strlen( $_FILES['CategPic']['name'] ) ) {
					if ($bEditMode==false) {
						$iCategoryID = mysql_insert_id();
						$sFileName = 'category_' . $iID;
					}
					//$sFileName = 'category_' . $iLastId;
					$sExt = moveUploadedImage( $_FILES, 'CategPic', $dir['blogImage'] . $sFileName, '', false );
					if ( strlen( $sExt ) && !(int)$sExt ) {
						imageResize( $dir['blogImage'] . $sFileName . $sExt, $dir['blogImage'] . 'small_' . $sFileName . $sExt, 25, 25, false );
						imageResize( $dir['blogImage'] . $sFileName . $sExt, $dir['blogImage'] . 'big_' . $sFileName . $sExt, 150, 150, false );

						chmod( $dir['blogImage'] . 'small_' . $sFileName . $sExt, 0644 );
						chmod( $dir['blogImage'] . 'big_' . $sFileName . $sExt, 0644 );

						$query = "UPDATE `BlogCategories` SET `CategoryPhoto` = '" . $sFileName . $sExt . "' WHERE `categoryID` = '{$iID}'";
						db_res( $query );

						@unlink( $dir['blogImage'] . $sFileName . $sExt );
					}
				}

				$ret .= ($bEditMode==true) ? _t( '_changes_successfully_applied' ) : _t( '_category_successfully_added' );
			} else {
				$ret .= _t( '_failed_to_add_category' );
			}
			return MsgBox($ret);
		} elseif($this->aBlogConf['visitorID'] != $iCategoryOwnerID) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}

	/**
	 * Deleting a Category
	 *
	 * @return MsgBox result
	 */
	function ActionDeleteCategory() {
		global $dir;

		$this->CheckLogged();

		$iCategID = process_db_input( (int)$_POST['DeleteCategoryID'] );

		$aCatType = db_arr("SELECT `CategoryType`,`OwnerID`, `CategoryPhoto` FROM `BlogCategories` WHERE `CategoryID` = {$iCategID} LIMIT 1");
		if ($aCatType['CategoryType'] == 1 && $aCatType['OwnerID']==$this->aBlogConf['visitorID']) {
			$vPosts = db_res( "SELECT `PostID`,`PostPhoto` FROM `BlogPosts` WHERE `CategoryID` = {$iCategID}" );
			while( $aBlog = mysql_fetch_assoc( $vPosts ) ) {
				$iPostID = $aBlog['PostID'];
				db_res( "DELETE FROM `BlogPostComments` WHERE `PostID` = {$iPostID}" );

				$sFileNamePost = $aBlog['PostPhoto'];
				if ($sFileName != '') {
					@unlink( $dir['blogImage'] . 'big_' . $sFileNamePost );
					@unlink( $dir['blogImage'] . 'small_' . $sFileNamePost );
				}
			}

			db_res( "DELETE FROM `BlogPosts` WHERE `CategoryID` = {$iCategID}" );
			$sQuery = "DELETE FROM `BlogCategories` WHERE `BlogCategories`.`CategoryID` = {$iCategID} LIMIT 1";

			if ($aCatType['CategoryPhoto'] != '') {
				@unlink( $dir['blogImage'] . 'big_' . $aCatType['CategoryPhoto'] );
				@unlink( $dir['blogImage'] . 'small_' . $aCatType['CategoryPhoto'] );
			}

			db_res( $sQuery );
			return MsgBox(_t('_category_deleted'));
		} elseif ($aCatType['OwnerID']!=$this->aBlogConf['visitorID']) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_category_delete_failed'));
		}
	}

	/**
	 * Generate a Block of searching result by Tag (GET is tagKey)
	 *
	 * @return HTML presentation of data
	 */
	function GenSearchResult() {
		global $site;

		$iCheckedMemberID = $this->aBlogConf['visitorID'];
		$sRestrictRes = $this->CheckRestrictionToView($iCheckedMemberID);
		if ($sRestrictRes != '') return $sRestrictRes;

		$bNoProfileMode = (isset($_REQUEST['ownerID'])) ? false : true;

		$sRetHtml = '';
		$sSearchedTag = process_db_input( $_REQUEST['tagKey'] );
		$iMemberID = (int)$_REQUEST['ownerID'];

		$sDateFormatPhp = getParam('php_date_format');
		$sTagsC = _t('_Tags');
		$sNoBlogC = _t('_No blogs available');

		$sBlogPosts = '';
		$sWhereAddon = ($iMemberID>0) ? "WHERE `BlogCategories`.`OwnerID` = {$iMemberID}" : '' ;
		$sBlogPostsSQL = "
			SELECT `BlogPosts`. * , `BlogCategories`.`CategoryName`, `BlogCategories`.`CategoryID`, `BlogCategories`.`OwnerID` AS 'OwnID'
			FROM `BlogPosts` 
			LEFT JOIN `BlogCategories` ON `BlogCategories`.`CategoryID` = `BlogPosts`.`CategoryID` 
			{$sWhereAddon}
		";
		$vBlogPosts = db_res( $sBlogPostsSQL );
		while ( $aResSQL = mysql_fetch_assoc($vBlogPosts) ) {
			/* $bFriend = is_friends( $this->aBlogConf['visitorID'], $aResSQL['OwnID'] );
			// $bOwner = ($this->aBlogConf['visitorID']==$aResSQL['OwnID']) ? true : false;
			// if( 'friends' == $aResSQL['PostReadPermission'] && !$bFriend && !$bOwner && !$this->bAdminMode ) {
				// $sMsgBox = MsgBox(_t('_this_blog_only_for_friends'));
				// $sBlogPosts .= <<<EOF
// <div class="clear_both"></div>
	// <img src="{$site['icons']}lock.gif" alt="" class="marg_icon" style="float:right;position:relative;" />
	// {$sMsgBox}
// <div class="clear_both"></div>
// EOF;
			 }  else*/ {
				$sDateTime = date( $sDateFormatPhp, strtotime( $aResSQL['BlogDate'] ) );

				$sCountBlogCommentsSQL = "
					SELECT COUNT( * ) 
					FROM `BlogPosts`
					LEFT JOIN `BlogPostComments` ON `BlogPosts`.`PostID` = `BlogPostComments`.`PostID` 
					WHERE `BlogPostComments`.`PostID` = {$aResSQL['PostID']}
				";
				$aCountBlogComments = db_arr( $sCountBlogCommentsSQL );
				$iCountBlogComments = (int)$aCountBlogComments[0];

				$sTagsCommas = $aResSQL['Tags'];
				$aTags = split(',', $sTagsCommas);

				$sTagsHrefs = '';
				foreach( $aTags as $sTagKey ) {
					$sOwnerAdd = ($iMemberID>0) ? "&amp;ownerID={$iMemberID}" : '';
					$sTagsHrefs .= <<<EOF
<a href="{$_SERVER['PHP_SELF']}?action=search_by_tag&amp;tagKey={$sTagKey}{$sOwnerAdd}" >{$sTagKey}</a>&nbsp;
EOF;
				}
				$sActions = '';
				if (($this->aBlogConf['visitorID']==$aBlogsRes['OwnerID'] || $this->bAdminMode==TRUE) && $iMemberID>0) {
					$sActions = <<<EOF
<div class="fr">
		<a href="{$_SERVER['PHP_SELF']}" onclick="javascript: UpdateField('EditPostID','{$aResSQL['PostID']}');document.forms.command_edit_post.submit();return false;" style="text-transform:none;">{$sEditC}</a>&nbsp;
		<a href="{$_SERVER['PHP_SELF']}" onclick="javascript: if (confirm('{$sSureC}')) {UpdateField('DeletePostID','{$aResSQL['PostID']}');UpdateField('DOwnerID','{$aBlogsRes['OwnerID']}');document.forms.command_delete_post.submit(); } return false;" style="text-transform:none;">{$sDeleteC}</a>
</div>
EOF;
				}

				if (in_array($sSearchedTag,$aTags)) {
				//$sProfileLink = ($iMemberID>0) ? '' : getProfileLink($aResSQL['OwnID']);
					$sProfileLink = ($iMemberID>0) ? '' : "<a href=".getProfileLink($aResSQL['OwnID']).">".getNickName($aResSQL['OwnID'])."</a>";

					$bFriend = is_friends( $this->aBlogConf['visitorID'], $aResSQL['OwnID'] );
					$bOwner = ($this->aBlogConf['visitorID']==$aResSQL['OwnID']) ? true : false;
					if( 'friends' == $aResSQL['PostReadPermission'] && !$bFriend && !$bOwner && !$this->bAdminMode ) {
						$sMsgBox = MsgBox(_t('_this_blog_only_for_friends'));
						$sBlogPosts .= <<<EOF
<div class="clear_both"></div>
	<img src="{$site['icons']}lock.gif" alt="" class="marg_icon" style="float:right;position:relative;" />
	{$sMsgBox}
<div class="clear_both"></div>
EOF;
					} else {

					$sBlogPosts .= <<<EOF
<div class="cls_res_info_p1">
	<div class="cls_res_thumb">
		<a class="actions" href="{$_SERVER['PHP_SELF']}?action=show_member_post&amp;ownerID={$aResSQL['OwnID']}&amp;post_id={$aResSQL['PostID']}">
			{$aResSQL['PostCaption']} 
		</a>
	</div>
	{$sActions}
	<div class="clear_both"></div>
</div>
<div class="fr_small_gray_centered">
	{$sProfileLink}
	<span style="vertical-align:middle;"><img src="{$site['icons']}clock.gif" style="position:static;margin-right:7px;" alt="{$sDateTime}" /></span>{$sDateTime}&nbsp;
	<span style="vertical-align:middle;"><img src="{$site['icons']}add_comment.gif" alt="{$sAddCommentC}" title="{$sAddCommentC}" style="position:static;margin-right:7px;" /></span>{$iCountBlogComments} comments&nbsp;
	<span style="vertical-align:middle;"><img src="{$site['icons']}folder_small.png" style="position:static;margin-right:7px;" /></span>
	<a href="{$_SERVER['PHP_SELF']}?action=show_member_blog&amp;ownerID={$aResSQL['OwnID']}&amp;category={$aResSQL['CategoryID']}">
		{$aResSQL['CategoryName']}
	</a>
</div>
<div class="cls_res_info_p1">
	{$aResSQL['PostText']}
</div>
<div class="cls_res_info_p1">
	<span style="vertical-align:middle;"><img src="{$site['icons']}tag_small.png" style="position:static;margin-right:7px;" alt="" /></span>{$sTagsC}:&nbsp;{$sTagsHrefs}
</div>
<br />
EOF;
					}
				} else {
					//return MsgBox(_t('_Sorry, nothing found'));
				}
			}
		}

		if ($sBlogPosts=='') {
			$sBlogPosts = MsgBox(_t('_Sorry, nothing found'));
		}
		$sContentSect = DesignBoxContent ($sTagsC.' - '.$sSearchedTag, $sBlogPosts, 1);
		if ($bNoProfileMode == false) {
			$sRightSect='';
			if ($iMemberID>0 && $a = $this->GetProfileData($iMemberID)) {
				$sBlogsSQL = "
					SELECT `Blogs`. * , `Profiles`.`Nickname` 
					FROM `Blogs` 
					INNER JOIN `Profiles` ON `Blogs`.`OwnerID` = `Profiles`.`ID`
					WHERE `Blogs`.`OwnerID` = {$iMemberID}
					LIMIT 1
				";

				$aBlogsRes = db_arr( $sBlogsSQL );
				if (mysql_affected_rows()==0) {
					$sNoBlogC = MsgBox($sNoBlogC);
					$sRetHtml = <<<EOF
<div>
	<div class="clear_both"></div>
	<div class="{$sWidthClass}">
		{$sNoBlogC}
	</div>
	<div class="clear_both"></div>
</div>
<div class="clear_both"></div>
EOF;
				} else {
					$sRightSect = $this->GenMemberDescrAndCat($aBlogsRes);
					$sWidthClass = ($iMemberID>0) ? 'cls_info_left' : 'cls_res_thumb' ;
					$sRetHtml = <<<EOF
<div>
	<div class="clear_both"></div>
	<div class="{$sWidthClass}">
		{$sContentSect}
	</div>
	<div class="cls_info">
		{$sRightSect}
	</div>
	<div class="clear_both"></div>
</div>
<div class="clear_both"></div>
EOF;
				}


			} else {
				$sRetHtml = MsgBox(_t('_Profile Not found Ex'));
			}
		} else {
			$sRetHtml = <<<EOF
<div>
	<div class="clear_both"></div>
	<div class="{$sWidthClass}">
		{$sContentSect}
	</div>
	<div class="clear_both"></div>
</div>
<div class="clear_both"></div>
EOF;
		}

		return $sRetHtml;
	}

	/**
	 * Generate a Form to Create Blog
	 *
	 * @return HTML presentation of data
	 */
	function GenCreateBlogForm() {
		$this->CheckLogged();

		$sRetHtml = '';
		$sActionsC = _t('_Actions');
		$sPleaseCreateBlogC = _t('_Please, Create a Blog');
		$sNoBlogC = _t('_No blogs available');
		$sCreateMyBlogC = _t('_Create My Blog');
		$sCreateBlogC = _t('_Create Blog');
		$sMyBlogC = _t('_My Blog');
		$sNewBlogDescC = _t('_Write a description for your Blog.');

		$sRetHtml .= MsgBox($sNoBlogC);

		if ($this->aBlogConf['isOwner']) {
			$sRetHtml = MsgBox($sPleaseCreateBlogC);

			$sCreateBlogContent = <<<EOF
<div class="padds">
			<a class="actions" onclick="javascript: document.getElementById('CreateBlogFormDiv').style.display = 'block';return false;" href="{$_SERVER['PHP_SELF']}">
		{$sCreateMyBlogC}
	</a>
</div>
<div id="CreateBlogFormDiv" style="display: none;">
	<div class="padds">
		<div style="font-size:11px;">{$sNewBlogDescC}</div>
	</div>
	<form action="{$_SERVER['PHP_SELF']}" method="post" name="CreateBlogForm">
		<input type="hidden" name="action" id="action" value="create_blog" />
		<textarea name="Description" id="Description" rows="3" class="classfiedsTextArea1" style="width:90%;margin-bottom:10px;"></textarea>
		<input type="submit" value="{$sCreateBlogC}"/>
	</form>
</div>
EOF;

			$sRetHtml .= DesignBoxContent ( $sActionsC, $sCreateBlogContent, 1);
		}

		return DesignBoxContent($sMyBlogC, $sRetHtml, 1);
	}

	function GenCenteredActionsBlock($sPicElement, $sHrefElement) {
		$sResElement = <<<EOF
<span style="vertical-align: middle;margin-right:5px;">{$sPicElement}</span>
<span>{$sHrefElement}</span>
EOF;
		return $sResElement;
	}

	/**
	 * Creating a Blog
	 *
	 * @return MsgBox result
	 */
	function ActionCreateBlog() {
		$this->CheckLogged();

		$sErrorC = _t('_Error Occured');
		$sUncategorizedC = _t('_Uncategorized');
		$sDescription = $this->process_html_db_input($_POST['Description'] );
		$iOwnID = $this->aBlogConf['visitorID'];

		$sRequest = "INSERT INTO `Blogs` SET `OwnerID` = '{$iOwnID}', `Description` = '{$sDescription}', `Other` = 'nothing' ";
		db_res($sRequest, false);

		if (mysql_affected_rows()==1) {
			$sAddQuery = "INSERT INTO `BlogCategories` SET
				`OwnerID` = {$iOwnID}, `CategoryName` = '{$sUncategorizedC}', `CategoryType` = '5',
				`CategoryPhoto` = '', `Date` = NOW()";
			db_res($sAddQuery);
			return $this->GenMemberBlog($iOwnID, false);
		} else {
			return MsgBox($sErrorC);
		}
	}

	/**
	 * Adding a Comment to Post
	 *
	 * @return MsgBox result
	 */
	function ActionAddBlogComment() {
		$this->CheckLogged();

		$blogID = (int)$_POST['CommPostID'];
		$senderID = $this->aBlogConf['visitorID'];
		$commentText = $this->process_html_db_input($_POST['message']);
		$replyTo = (int)$_POST['replyTo'];
		$ip = getVisitorIP(); // ( getenv('HTTP_CLIENT_IP') ? getenv('HTTP_CLIENT_IP') : getenv('REMOTE_ADDR') );

		if ( !$ip ) {
			$ret = _t_err("_sorry, i can not define you ip adress. IT'S TIME TO COME OUT !");
			return $ret;
		}

		if( 0 >= $senderID ) {
			return _t_err('_im_textLogin');
		}

		if( 0 >= $blogID ) {
			return '';
		}

		$last_count = db_arr( "SELECT COUNT( * ) AS `last_count` FROM `BlogPostComments` WHERE `IP` = '{$ip}' AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`Date`) < 1*60)" );
		if ( $last_count['last_count'] != 0 ) {
			$ret = _t("_You have to wait for PERIOD minutes before you can write another message!", 1);
			return MsgBox($ret);
		}

		$addCommentQuery = "
			INSERT INTO `BlogPostComments`
			SET
				`PostID` = '$blogID',
				`SenderID` = '$senderID',
				`CommentText` = '$commentText',
				`ReplyTo` = '$replyTo',
				`IP` = '$ip',
				`Date` = NOW()
		";

		//echo $addCommentQuery;
		if( db_res( $addCommentQuery ) ) {
			$ret = _t('_comment_added_successfully');
		} else {
			$ret = _t('_failed_to_add_comment');
		}

		return MsgBox($ret);
	}

	/**
	 * Editing a Comment to Post
	 *
	 * @return MsgBox result
	 */
	function ActionEditComment(){
		$iCommId = (int)$_REQUEST['EditCommentID'];
		$sCheckSQL = "SELECT `SenderID`,`PostID` FROM `BlogPostComments` WHERE `CommentID`={$iCommId}";
		$aSenderID = db_arr($sCheckSQL);
		$iSenderID = $aSenderID['SenderID'];
		$iPostID = $aSenderID['PostID'];
		$sCheckPostSQL = "SELECT `BlogCategories`.`OwnerID`
							FROM `BlogPosts`
							LEFT JOIN `BlogCategories` ON `BlogCategories`.`CategoryID`=`BlogPosts`.`CategoryID`
							WHERE `PostID`={$iPostID}
						";
		$PostID = db_arr($sCheckPostSQL);
		$iPostOwnerID = $PostID['OwnerID'];
		if (( $this->aBlogConf['visitorID']==$iPostOwnerID || $this->aBlogConf['visitorID']==$iSenderID || $this->bAdminMode==true) && $iCommId > 0) {
			$sMessage = $this->process_html_db_input( $_POST['commentText'] );
			$query = "UPDATE `BlogPostComments` SET `CommentText` = '{$sMessage}' WHERE `BlogPostComments`.`CommentID` = {$iCommId} LIMIT 1 ;";
			$sqlRes = db_res( $query );
		} elseif($this->aBlogConf['visitorID'] != $iSenderID || $this->aBlogConf['visitorID'] != $iPostOwnerID) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}

	/**
	 * Deleting a Comment to Post
	 *
	 * @return MsgBox result
	 */
	function ActionDeleteComment(){
		$iCommId = (int)$_REQUEST['DeleteCommentID'];
		$sCheckSQL = "SELECT `SenderID`,`PostID` FROM `BlogPostComments` WHERE `CommentID`={$iCommId}";
		$aSenderID = db_arr($sCheckSQL);
		$iSenderID = $aSenderID['SenderID'];
		$iPostID = $aSenderID['PostID'];
		$sCheckPostSQL = "SELECT `BlogCategories`.`OwnerID`
							FROM `BlogPosts`
							LEFT JOIN `BlogCategories` ON `BlogCategories`.`CategoryID`=`BlogPosts`.`CategoryID`
							WHERE `PostID`={$iPostID}
						";
		$PostID = db_arr($sCheckPostSQL);
		$iPostOwnerID = $PostID['OwnerID'];
		if (( $this->aBlogConf['visitorID'] == $iPostOwnerID || $this->aBlogConf['visitorID'] == $iSenderID) && $iCommId > 0) {
			$query = "DELETE FROM `BlogPostComments` WHERE `BlogPostComments`.`CommentID` = {$iCommId} LIMIT 1";
			$sqlRes = db_res( $query );
		} elseif($this->aBlogConf['visitorID'] != $iSenderID || $this->aBlogConf['visitorID'] != $iPostOwnerID) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}

	/**
	 * SQL Get all Profiles data by Profile Id
	 *
	  * @param $iProfileId
	 * @return SQL data
	 */
	function GetProfileData($iProfileId) {
		return getProfileInfo( $iProfileId );
	}

	/**
	 * Editing a Description of Blog
	 *
	 * @return MsgBox result
	 */
	function ActionEditBlog() {
		$this->CheckLogged();

		$iBlogID = (int)($_POST['EditBlogID']);

		$sCheckPostSQL = "SELECT `OwnerID`
							FROM `Blogs`
							WHERE `ID`={$iBlogID}
						";
		$aBlogOwner = db_arr($sCheckPostSQL);
		$iBlogOwner = $aBlogOwner['OwnerID'];
		if (($this->aBlogConf['visitorID'] == $iBlogOwner || $this->bAdminMode) && $iBlogID > 0) {
			$sDescription = process_db_input($_REQUEST['Description']);
			$sQuery = "UPDATE `Blogs` SET
			`Description` = '{$sDescription}' WHERE `Blogs`.`ID` = {$iBlogID} LIMIT 1";
			db_res($sQuery);
		} elseif($this->aBlogConf['visitorID'] != $iBlogOwner) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}

	/**
	 * Deleting a Full Blog
	 *
	 * @return MsgBox result
	 */
	function ActionDeleteBlogSQL() {
		$this->CheckLogged();
		global $dir;

		$iBlogID = (int)$_REQUEST['DeleteBlogID'];

		$sCheckPostSQL = "SELECT `OwnerID`
							FROM `Blogs`
							WHERE `ID`={$iBlogID}
						";
		$aBlogOwner = db_arr($sCheckPostSQL);
		$iBlogOwner = $aBlogOwner['OwnerID'];
		if (($this->aBlogConf['visitorID'] == $iBlogOwner || $this->bAdminMode) && $iBlogID > 0) {
			//Clean blogs
			$vBlogCategs = db_res( "SELECT `CategoryID`,`CategoryPhoto` FROM `BlogCategories` LEFT JOIN `Blogs` ON `Blogs`.`OwnerID` = `BlogCategories`.`OwnerID` WHERE `Blogs`.`ID` = {$iBlogID} " );
			while( $aBlogCateg = mysql_fetch_assoc( $vBlogCategs ) ) {
				$iCategID = $aBlogCateg['CategoryID'];
				$vPosts = db_res( "SELECT `PostID`,`PostPhoto` FROM `BlogPosts` WHERE `CategoryID` = {$iCategID}" );
				while( $aBlog = mysql_fetch_assoc( $vPosts ) ) {
					$iPostID = $aBlog['PostID'];
					db_res( "DELETE FROM `BlogPostComments` WHERE `PostID` = {$iPostID}" );
					$sFilePathPost = 'big_'.$aBlog['PostPhoto'];
					if ($sFilePathPost!='' && file_exists($dir['blogImage'].$sFilePathPost) && is_file($dir['blogImage'].$sFilePathPost))
						unlink( $dir['blogImage'] . $sFilePathPost );
					$sFilePathPost = 'small_'.$aBlog['PostPhoto'];
					if ($sFilePathPost!='' && file_exists($dir['blogImage'].$sFilePathPost) && is_file($dir['blogImage'].$sFilePathPost))
						unlink( $dir['blogImage'] . $sFilePathPost );
				}
				db_res( "DELETE FROM `BlogPosts` WHERE `CategoryID` = {$iCategID}" );
				db_res( "DELETE FROM `BlogCategories` WHERE `CategoryID` = {$iCategID}" );

				$sFilePath = 'big_'.$aBlogCateg['CategoryPhoto'];
				if ($sFilePath!='' && file_exists($dir['blogImage'].$sFilePath) && is_file($dir['blogImage'].$sFilePath))
					unlink( $dir['blogImage'] . $sFilePath );
				$sFilePath = 'small_'.$aBlogCateg['CategoryPhoto'];
				if ($sFilePath!='' && file_exists($dir['blogImage'].$sFilePath) && is_file($dir['blogImage'].$sFilePath))
					unlink( $dir['blogImage'] . $sFilePath );
			}
			db_res( "DELETE FROM `Blogs` WHERE `ID` = {$iBlogID}" );
		} elseif($this->aBlogConf['visitorID'] != $iBlogOwner) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}

	/* common features function
	*/
	function process_html_db_input( $sText ) {
		return addslashes( clear_xss( trim( process_pass_data( $sText ))));
	}

	function ActionPrepareForEdit($sInput) {
		$sResJSHTML = addslashes(htmlspecialchars($sInput));
		$sResJSHTML = str_replace( "\r\n", '', $sResJSHTML );
		return $sResJSHTML;
	}
}

?>