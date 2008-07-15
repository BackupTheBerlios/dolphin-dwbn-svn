<?

require_once(BX_DIRECTORY_PATH_INC . 'header.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'admin.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'tags.inc.php' );

/*
 * class for Events
 */
class BxDolComments {

	//comments type (1-Classifieds, 2-Blogs, )
	var $iCType;

	//name of table with comments
	var $sTableCommentsName;

	//admin mode, can All actions
	var $bAdminMode;

	//friends ability
	var $bFriendsMode;

	var $sCurrBrowsedFile;

	var $iVisitorID;
	//var $iBigThumbSize = 110;

	//constructor
	function BxDolComments( $iCType, $sCurrBrowsedFile='' ) {
		$this->iCType = $iCType;
		$this->iVisitorID = (int)$_COOKIE['memberID'];
		$this->sCurrBrowsedFile = ($sCurrBrowsedFile=='') ? $_SERVER['PHP_SELF'] : $sCurrBrowsedFile;
	}

	/**
	 * Generate Comments Section
	 *
	 * @param $iElementID - Post ID
	 * @param $iOwnerID - Owner ID
	 * @return HTML presentation of data
	 */
	function PrintCommentSection($iElementID, $sCommentLbl='', $bDesignBoxed = true ) {
		global $aPreValues;
		global $site;

		$sRetHtml = '';

		$sCommentsC = ($sCommentLbl=='') ? _t('_comments') : $sCommentLbl;
		$sSbjN = _t('_Subject');
		$sPostedByC = _t('_Posted by');
		$sDateC = _t('_Date');
		$sLocationC = _t('_Location');
		$sAdminLocalAreaC = _t('_AdminArea');
		$sAdminC = _t('_Admin');
		$sSureC = _t("_Are you sure");
		$sPostCommentC = _t('_Post Comment');
		$sLeaveCommentC = _t('_LeaveComment');
		$sAddCommentC = _t('_Add comment');
		$sEditC = _t('_Edit');
		$sDeleteC = _t('_Delete');
		$sCommentFriendsOnlyC = _t('_commenting_this_blog_allowed_only_for_friends');
		$sReportC = _t('_Report');

		$sPostDataSQL = $this->SelectionObjectSQL($iElementID);

		$aPostData = db_arr($sPostDataSQL);
		$iOwnerID = $aPostData['OwnerID'];

		//$bFriend = is_friends( $this->iVisitorID, $aPostData['OwnerID'] );
		//$bOwner = ($this->iVisitorID==$aPostData['OwnerID']) ? true : false;

		$bCanPostComment = $this->GetPostNewCommentPermission($aPostData);
		$bCanPostCommentReport = $this->GetCommentReportPermission($aPostData);

		$sFullPermissions = false;
		$sFullPermissions2Forms = false;

		$sQuery = $this->SelectionCommentsObjectSQL($iElementID);

		$vSqlRes = db_res ($sQuery);
		$sCommsHtml = '';
		while( $aSqlResStr = mysql_fetch_assoc($vSqlRes) ) {
			$aCommentData = $this->FillCommentsData($aSqlResStr);
			$aProfileInfo = getProfileInfo($aCommentData['ProfID']);

			$sPostedBy = ($aCommentData['ProfID']==0) ? $sAdminC : '<a href="'.getProfileLink($aCommentData['ProfID']).'">'.$aProfileInfo['NickName'].'</a>';
			$sCountryName = ($aProfileInfo['Country']=="")?$sAdminLocalAreaC:_t($aPreValues['Country'][ $aProfileInfo['Country'] ]['LKey'] );
			$sCountryPic = ($aProfileInfo['Country']=='')?'':' <img alt="'.$aProfileInfo['Country'].'" src="'.($site['flags'].strtolower($aProfileInfo['Country'])).'.gif"/>';
			$sUserIcon = get_member_icon($aCommentData['ProfID'], 'left', true);
			$aCommentData['Text'] = ( $aCommentData['Text']);
			$sTimeAgo = _format_when($aCommentData['Time']);

			$sMessageBR = $this->ActionPrepareForEdit($aCommentData['Text']);

			$sFullPermissions = $this->GetElementFullPermission($aPostData, $aCommentData);
			$sFullPermissions2Forms = ($sFullPermissions==true) ?  $sFullPermissions: $sFullPermissions2Forms;

			$sAdminActions = '';
			if ($sFullPermissions) {
				if ($this->iCType==1) {
					$sAdminActions = <<<EOF
<!-- <span class="comment_text_r"> -->
<div class="comment_actions">
	<a href="{$this->sCurrBrowsedFile}" onclick="javascript: UpdateField('EditCommentID',{$aCommentData['ID']});UpdateField('EAdvID',{$iElementID}); UpdateFieldStyle('answer_form_to_1','block');UpdateFieldTiny('commentText','{$sMessageBR}'); return false;">{$sEditC}</a>|
	<a href="{$_SERVER['PHP_SELF']}?ShowAdvertisementID={$aCommentData['ID']}" onclick="javascript: UpdateField('DeleteCommentID',{$aCommentData['ID']});UpdateField('DAdvID',{$iElementID});document.forms.command_delete_comment.submit(); return false;">{$sDeleteC}</a>
<!-- </span> -->
</div>
EOF;
				}
				if ($this->iCType==2) {
					$sAdminActions = <<<EOF
<span class="comment_text_r">
	<a href="{$this->sCurrBrowsedFile}" onclick="javascript: UpdateField('EditCommentID',{$aCommentData['ID']});UpdateField('EPostID',{$iElementID});UpdateFieldStyle('answer_form_to_1','block');UpdateFieldTiny('commentText','{$sMessageBR}'); return false;">{$sEditC}</a>|
	<a href="{$this->sCurrBrowsedFile}" onclick="javascript: UpdateField('DeleteCommentID',{$aCommentData['ID']});UpdateField('DPostID',{$iElementID});document.forms.command_delete_comment.submit(); return false;">{$sDeleteC}</a>
</span>
EOF;
				}
			}

			if ($bCanPostCommentReport) {
				if ($this->iCType==1) {
					$sReport = <<<EOF
<div class="comment_actions">
	<a onclick="javascript: window.open( 'classifieds.php?commentID={$aCommentData['ID']}&clsID={$iElementID}&action=report', 'comment', 'width=500, height=380, menubar=no,status=no,resizable=yes,scrollbars=yes,toolbar=no,location=no' );return false;" href="#{$aCommentData['ID']}">{$sReportC}</a>
</div>
EOF;
				}
			}

			$sCommsHtml .= <<<EOF
<div class="comment_row">
	{$sUserIcon}
	{$sPostedBy} ({$sTimeAgo})<br />
	{$aCommentData['Text']}<br />
	{$sReport}<br />
	{$sAdminActions}
	<div class="clear_both"></div>
</div>
EOF;

		}

		$sPostNewComm = '';
		if ($bCanPostComment==true) {
			if ($this->iCType==1) {
				$sPostNewComm = <<<EOF
<form action="{$this->sCurrBrowsedFile}" method="post" name="post_comment_adv_form">
	<input type="hidden" name="CommAdvertisementID" value="{$iElementID}" />
	<textarea name="message" id="postNewComm" rows="5" cols="30" style="width:100%;" class="classfiedsTextArea"></textarea>
	<input id="postCommentAdv" name="postCommentAdv" type="submit" value="{$sPostCommentC}"/>
</form>
EOF;
			}
			if ($this->iCType==2) {
				$sPostNewComm = <<<EOF
<form action="{$this->sCurrBrowsedFile}?action=show_member_post&amp;ownerID={$iOwnerID}&amp;post_id={$iElementID}" method="post" name="post_comment_adv_form">
	<input type="hidden" name="action" value="addcomment" />
	<input type="hidden" name="CommPostID" value="{$iElementID}" />
	<input type="hidden" name="ownerID" value="{$iOwnerID}" />
	<textarea name="message" id="postNewComm" rows="5" cols="30" style="width:100%;" class="classfiedsTextArea"></textarea>
	<input id="postCommentPost" name="postCommentPost" type="submit" value="{$sPostCommentC}"/>
</form>
EOF;
			}
		}

		if ($sFullPermissions2Forms==true) {
			if ($this->iCType==1) {
				$sAdminFormActions = <<<EOF
<form action="{$this->sCurrBrowsedFile}" method="post" name="command_edit_comment_adv_form">
	<input type="hidden" name="EditCommentID" id="EditCommentID" value=""/>
	<input type="hidden" name="EAdvID" id="EAdvID" value=""/>
	<textarea name="commentText" id="commentText" rows="20" cols="60" class="classfiedsTextArea" style="width:100%;"></textarea>
	<input type="submit" value="{$sPostCommentC}"/>
</form>
EOF;
			}
			if ($this->iCType==2) {
				$sAdminFormActions = <<<EOF
<form action="{$this->sCurrBrowsedFile}?action=show_member_post&amp;ownerID={$iOwnerID}&amp;post_id={$iElementID}" method="post" name="command_edit_comment_post_form">
	<input type="hidden" name="action" value="editcomment" />
	<input type="hidden" name="EditCommentID" id="EditCommentID" value=""/>
	<input type="hidden" name="EPostID" id="EPostID" value=""/>
	<input type="hidden" name="ownerID" value="{$iOwnerID}" />
	<textarea name="commentText" id="commentText" rows="10" cols="60" class="classfiedsTextArea" style="width:100%;"></textarea>
	<input type="submit" value="{$sPostCommentC}"/>
</form>
<form action="{$this->sCurrBrowsedFile}?action=show_member_post&amp;ownerID={$iOwnerID}&amp;post_id={$iElementID}" method="post" name="command_delete_comment">
	<input type="hidden" name="DeleteCommentID" id="DeleteCommentID" value=""/>
	<input type="hidden" name="DPostID" id="DPostID" value=""/>
	<input type="hidden" name="action" id="action" value="delete_comment" />
	<input type="hidden" name="ownerID" value="{$iOwnerID}" />
</form>
EOF;
			}
		}


		$sCommentActions = '';
		if( $bCanPostComment==false && $aPostData['PostCommentPermission']=='friends' ) {
			$sImgFriend = <<<EOF
<img src="{$site['icons']}lock32.gif" alt="{$sCommentFriendsOnlyC}" title="{$sCommentFriendsOnlyC}" class="marg_icon" style="margin-top:10px;" />
EOF;
			$sCommentActions = $sImgFriend.MsgBox($sCommentFriendsOnlyC);
		} else {
			$sCommentActions = <<<EOF
<div id="add_comment_label">
	<img src="{$site['icons']}add_comment.gif" alt="{$sAddCommentC}" title="{$sAddCommentC}" class="marg_icon" />
	<a class="actions" onclick="document.getElementById('answer_form_to_0').style.display = 'block'; document.getElementById('add_comment_label').style.display = 'none'; return false;" href="{$this->sCurrBrowsedFile}">{$sAddCommentC}</a>
</div>
EOF;
		}

		$sCommentsContent = <<<EOF
<div id="comments_section">
	{$sCommsHtml}
	{$sCommentActions}
	<div id="answer_form_to_0" style="display: none;">
		{$sPostNewComm}
	</div>
	<div id="answer_form_to_1" style="display: none;">
		{$sAdminFormActions}
	</div>
</div>
EOF;
		$show_hide = $this -> genShowHideItem( 'comments_section' );

		$sRetHtml = ($bDesignBoxed == true) ? DesignBoxContent ( $sCommentsC, $sCommentsContent, 1, $show_hide) : $sCommentsContent;
		return $sRetHtml;
	}

	function GetPostNewCommentPermission($aPostData){
		$bCanPostComment = true;

		$bFriend = is_friends( $this->iVisitorID, $aPostData['OwnerID'] );
		$bOwner = ($this->iVisitorID==$aPostData['OwnerID']) ? true : false;

		switch ($this->iCType) {
			case 2:
				if ($aPostData['PostCommentPermission']=='public' || $bFriend || $bOwner || $this->bAdminMode) {
					$bCanPostComment = true;
				} else {
					$bCanPostComment = false;
				}
				break;
		}
		return $bCanPostComment;
	}

	function GetCommentReportPermission($aPostData){
		$bReportAccess = false;

		//$bFriend = is_friends( $this->iVisitorID, $aPostData['OwnerID'] );
		$bOwner = ($this->iVisitorID==$aPostData['OwnerID']) ? true : false;

		switch ($this->iCType) {
			case 1:
				if ( $bOwner ) {
					$bReportAccess = true;
				} else {
					$bReportAccess = false;
				}
				break;
			/*case 2:
				if ($bOwner || $this->bAdminMode || $aCommentData['ProfID']==$this->iVisitorID) {
					$bReportAccess = true;
				} else {
					$bReportAccess = false;
				}
				break;*/
		}
		return $bReportAccess;
	}

	function GetElementFullPermission($aPostData, $aCommentData){
		$bFullAccess = true;

		//$bFriend = is_friends( $this->iVisitorID, $aPostData['OwnerID'] );
		$bOwner = ($this->iVisitorID==$aPostData['OwnerID']) ? true : false;

		switch ($this->iCType) {
			case 1:
				if ( /*$bOwner ||*/ $this->bAdminMode /*|| $aCommentData['ProfID']==$this->iVisitorID*/ ) {
					$bFullAccess = true;
				} else {
					$bFullAccess = false;
				}
				break;
			case 2:
				if ($bOwner || $this->bAdminMode || $aCommentData['ProfID']==$this->iVisitorID) {
					$bFullAccess = true;
				} else {
					$bFullAccess = false;
				}
				break;
		}
		return $bFullAccess;
	}

	/**
	 * Fill Array by comments data
	 *
	 * @param $aSqlResStr - comment data
	 * @return SQL request
	 */
	function FillCommentsData($aSqlResStr) {
		$aCommentData = array();
		switch ($this->iCType) {
			case 1:
				$aCommentData['ProfID'] = $aSqlResStr['IDProfile'];
				$aCommentData['Text'] = $aSqlResStr['Message'];
				$aCommentData['ID'] = $aSqlResStr['ID'];
				$aCommentData['Time'] = $aSqlResStr['sec'];
				//$aCommentData[''] = $aSqlResStr[''];
				break;
			case 2:
				$aCommentData['ProfID'] = $aSqlResStr['SenderID'];
				$aCommentData['Text'] = $aSqlResStr['CommentText'];
				$aCommentData['ID'] = $aSqlResStr['CommentID'];
				$aCommentData['Time'] = $aSqlResStr['sec'];
				//$aCommentData[''] = $aSqlResStr[''];
				break;
		}
		return $aCommentData;
	}

	function genShowHideItem( $wrapperID, $default = '' ) {
		$sHideC = _t( '_Hide' );
		$sShowC = _t( '_Show' );
		if( !$default )
			$default = $sHideC;

		return <<<EOF
<div class="caption_item">
	<a href="{$this->sCurrBrowsedFile}"
	  onclick="javascript: el = document.getElementById('{$wrapperID}');
				if(el.style.display == 'none') {el.style.display = 'block'; this.innerHTML='{$sHideC}'; }
				else {el.style.display = 'none'; this.innerHTML = '{$sShowC}';} return false;">{$default}</a>
</div>
EOF;
	}

	/**
	 * SAFE SQL - Get Main data about Element: owner, category, 
	 *
	 * @param $iElementID - Post ID
	 * @return SQL request
	 */
	function SelectionObjectSQL($iElementID){
		$sPostDataSQL = '';
		switch ($this->iCType) {
			case 1:
				$sPostDataSQL = "SELECT `ClassifiedsAdvertisements`.`IDProfile` AS 'OwnerID'
				FROM `ClassifiedsAdvertisements`
				/* INNER JOIN `ClassifiedsSubs` ON `ClassifiedsSubs`.`ID` = `ClassifiedsAdvertisements`.`IDClassifiedsSubs`
				INNER JOIN `Classifieds` ON `Classifieds`.`ID` = `ClassifiedsSubs`.`IDClassified` */
				WHERE `ClassifiedsAdvertisements`.`ID` = {$iElementID} LIMIT 1";
				break;
			case 2:
				$sPostDataSQL = "SELECT `BlogPosts`.`PostCommentPermission`, `BlogCategories`.`OwnerID`
				FROM `BlogPosts`
				INNER JOIN `BlogCategories` ON `BlogCategories`.`CategoryID` = `BlogPosts`.`CategoryID`
				WHERE `BlogPosts`.`PostID` = {$iElementID} LIMIT 1";
				break;
		}
		return $sPostDataSQL;
	}

	/**
	 * SAFE SQL - Get Comments data of Element:
	 *
	 * @param $iElementID - Post ID
	 * @return SQL request
	 */
	function SelectionCommentsObjectSQL($iElementID){
		$sPostDataSQL = '';
		switch ($this->iCType) {
			case 1:
				$sQuery = "
					SELECT `ClsAdvComments`.*, (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`ClsAdvComments`.`DateTime`)) AS `sec`
					FROM `ClsAdvComments`
					WHERE `ClsAdvComments`.`IDAdv` = {$iElementID}
					ORDER BY 'sec' DESC
				";
				break;
			case 2:
				$sQuery = "
					SELECT `BlogPostComments`.*, (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`BlogPostComments`.`Date`)) AS `sec`
					FROM `BlogPostComments`
					WHERE `BlogPostComments`.`PostID` = {$iElementID}
					ORDER BY 'sec' DESC
				";
				break;
		}
		return $sQuery;
	}

	function ActionPrepareForEdit($sInput) {
		$sResJSHTML = addslashes(htmlspecialchars($sInput));
		$sResJSHTML = str_replace( "\r\n", '', $sResJSHTML );
		return $sResJSHTML;
	}

}

?>