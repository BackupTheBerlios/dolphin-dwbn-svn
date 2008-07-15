<?

/** simple function description
 * Return string for Header, depends at POST params
 *
 * @param $arr	array with data
 * @param $tag	main tag <main tag>XML data</main tag>
 * @return XML presentation of data
 */

require_once( BX_DIRECTORY_PATH_INC . 'header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolMediaQuery.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolComments.php' );
require_once( BX_DIRECTORY_PATH_INC . 'tags.inc.php' );

require_once( BX_DIRECTORY_PATH_CLASSES . "BxDolPageView.php" );
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplCmtsView.php" );


class BxDolAdPageView extends BxDolPageView {
	var $oClassifieds;

	function BxDolAdPageView(&$oAd) {
		$this->oClassifieds = &$oAd;
		parent::BxDolPageView('ads');
	}

	function getBlockCode_AdPhotos() {
		return $this->oClassifieds->sTAPhotosContent;
	}

	function getBlockCode_ActionList() {
		return $this->oClassifieds->sTAActionsContent;
	}

	function getBlockCode_ViewComments() {
		return $this->oClassifieds->sTACommentsContent;
	}

	function getBlockCode_AdInfo() {
		return $this->oClassifieds->sTAInfoContent;
	}

	function getBlockCode_Description() {
		return $this->oClassifieds->sTADescriptionContent;
	}

	function getBlockCode_UserOtherAds() {
		return $this->oClassifieds->sTAOtherListingContent;
	}
}

/*
 * parent object for all classes
 */
class BxDolClassifieds {
	/*
	* Global(common) params part, settings
	*/
	//variables

	//max sizes of pictures for resizing during upload
	var $iIconSize = 45;
	var $iThumbSize = 110;
	var $iBigThumbSize = 340;
	var $iImgSize = 800;

	//upload URL to dir
	var $sUploadDir = '';

	//max upload file size
	var $iMaxUplFileSize = 1000000;

	//path to image with Point
	var $sSpacerPath = 'templates/base/images/icons/spacer.gif';

	//path to image pic_not_avail.gif
	var $sPicNotAvail = 'images/icons/group_no_pic.gif';
	//path to plus.gif image
	var $sPicPlus = 'images/plus.gif';
	//path to folder.gif image
	var $sPicFolder = 'images/folder.gif';

	//width of scroll boxes in preview of Advertisment
	//var $iScrollWidth=355;

	//admin mode, can All actions
	var $bAdminMode = FALSE;

	//current file, for actions of forms and other
	var $sCurrBrowsedFile = '';

	var $iPerPageElements = 10;

	//use permalink
	var $bUseFriendlyLinks;

	//for templater //1310
	var $sTAPhotosContent = '';
	var $sTAActionsContent = '';
	var $sTACommentsContent = '';
	var $sTAInfoContent = '';
	var $sTADescriptionContent = '';
	var $sTAOtherListingContent = '';

	var $oCmtsView;

	/**
	 * constructor
	 */
	function BxDolClassifieds() {
		$this->sUploadDir = 'media/images/classifieds/';

		$this->bUseFriendlyLinks = getParam('permalinks_classifieds') == 'on' ? true : false;
	}

	function GetSQLError($sQuery) {
		return _t_err('_FAILED_RUN_SQL', $sQuery, mysql_error());
	}

	function UseDefaultCF() {
		$this -> sCurrBrowsedFile = 'classifieds.php';
	}

	/**
	 * Generate common forms and includes js
	 *
	 * @return HTML presentation of data
	 */
	function PrintCommandForms() {
		global $site;

		$sActionFile = ($this -> bAdminMode) ? "{$this -> sCurrBrowsedFile}" : "classifiedsmy.php";
		//$sJSPath = ($this -> bAdminMode) ? "../" : "";
		$sJSPath = $site['url'] . 'inc/';

		$sExtraCss = ($this -> bAdminMode) ? '<link type="text/css" rel="stylesheet" href="'.$site['url_admin'].'styles/msgbox.css">' : '';
		$sRetHtml = <<<EOF
{$sExtraCss}
<script src="{$sJSPath}js/dynamic_core.js.php" type="text/javascript"></script>
<form action="{$this->sCurrBrowsedFile}" method="post" name="command_activate_advertisement">
	<input type="hidden" name="ActivateAdvertisementID" id="ActivateAdvertisementID" value="" />
</form>
<form action="{$this->sCurrBrowsedFile}" method="post" name="command_delete_advertisement">
	<input type="hidden" name="DeleteAdvertisementID" id="DeleteAdvertisementID" value="" />
</form>
<form action="{$sActionFile}" method="post" name="command_edit_advertisement">
	<input type="hidden" name="EditAdvertisementID" id="EditAdvertisementID" value="" />
</form>
<form action="{$this -> sCurrBrowsedFile}" method="post" name="command_delete_comment">
	<input type="hidden" name="DeleteCommentID" id="DeleteCommentID" value=""/>
	<input type="hidden" name="DAdvID" id="DAdvID" value=""/>
</form>
EOF;
		return $sRetHtml;
	}

	/**
	 * Return string for Header, depends at POST params
	 *
	 * @return Textpresentation of data
	 */
	function GetHeaderString() {
		if (isset($_GET['PostAd'])) {
			if (((int)$_GET['PostAd']) == 1) {
				return _t( "_PostAd" );
			}
		}
		elseif (isset($_POST['PostAdMessage'])) {
			if (((int)$_GET['PostAdMessage']) == 'Send') {
				return _t( "_PostAd" );
			}
		}
		elseif (isset($_GET['MyAds'])) {
			if (((int)$_GET['MyAds']) == 1) {
				return _t('_My Advertisements');
			}
		}
		elseif (isset($_GET['SearchForm'])) {
			if (((int)$_GET['SearchForm']) == 1) {
				return _t('_Filter');
			}
		}
		elseif (isset($_REQUEST['action']) AND $_REQUEST['action']=="3") {
			$sFilteredC = _t('_Filtered');
			$sListingC = _t('_Listing');
			return $sFilteredC.' '.$sListingC;
		}
		elseif (isset($_REQUEST['ShowAdvertisementID']) || isset($_GET['entryUri'])) {
			return _t('_Offer Details');
		}
		elseif (isset($_GET['bClassifiedID']) || isset($_GET['catUri'])) {
			//$iClassifiedID = (int)$_GET['bClassifiedID'];
			$iClassifiedID = ($this->bUseFriendlyLinks) ? (int)db_value("SELECT `ID` FROM `Classifieds` WHERE `CEntryUri`='" . $this->process_html_db_input($_REQUEST['catUri']) . "' LIMIT 1") : (int)$_REQUEST['bClassifiedID'];
			if ($iClassifiedID > 0) {
				$sQuery = "SELECT `Name` FROM `Classifieds` WHERE `ID` = {$iClassifiedID}";
				$aSqlCatInfo = db_arr ($sQuery);
				if ($aSqlCatInfo) {
					return $aSqlCatInfo['Name'];
				}
			}
		}
		elseif (isset($_GET['bSubClassifiedID']) || isset($_GET['scatUri'])) {
			//$iSubClassifiedID = (int)$_GET['bSubClassifiedID'];
			$iSubClassifiedID = ($this->bUseFriendlyLinks) ? (int)db_value("SELECT `ID` FROM `ClassifiedsSubs` WHERE `SEntryUri`='" . $this->process_html_db_input($_REQUEST['scatUri']) . "' LIMIT 1") : (int)$_REQUEST['bSubClassifiedID'];
			if ($iSubClassifiedID > 0) {
				$sSql = "
					SELECT `Classifieds`.`Name` , `ClassifiedsSubs`.`NameSub`
					FROM `Classifieds` 
					INNER JOIN `ClassifiedsSubs` ON ( `Classifieds`.`ID` = `ClassifiedsSubs`.`IDClassified` ) 
					WHERE `ClassifiedsSubs`.`ID` = {$iSubClassifiedID}
					LIMIT 1
				";
				$aSubcatRes = db_arr($sSql);

				if ($aSubcatRes ) {
					$sFilterC = _t('_Filter');
					return "{$sFilterC}: ".$aSubcatRes['Name'] . ' -> ' . $aSubcatRes['NameSub'];
				}
			}
		}
		else {
			return _t( "_CLASSIFIEDS_VIEW_H1" );
		}
	}

	function RestrictAction($iMemberID) {
		if ($this->bAdminMode==true) return FALSE;
		$vCheckRes = checkAction( $iMemberID, ACTION_ID_CAN_AD_CLASSIFIEDS );
		if ( $vCheckRes[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED ) {
			$ret = MsgBox(strip_tags($vCheckRes[CHECK_ACTION_MESSAGE]));
			//login_form();
			return $ret;
		}
		return '';
	}

	/**
	 * Generate one of variant of Tree presentation of Classifieds
	 *
	 * @return HTML presentation of data
	 */
	function PrintTreeClassifiedsBrowse() {
		$vSqlRes = $this -> GetDataOfCls();
		if (mysql_affected_rows()==-1) {
			return $this -> GetSQLError("");
		}

		$sTreeRows = '';
		while( $aSqlResCls = mysql_fetch_assoc($vSqlRes) ) {
			$iID = $aSqlResCls['ID'];
			$sName = $aSqlResCls['Name'];
			$sCUri = $aSqlResCls['CEntryUri'];

			$sSqlCntClsAdv = "SELECT COUNT(`ClassifiedsAdvertisements`.`ID`) AS 'Count'
									FROM `Classifieds`
									INNER JOIN `ClassifiedsSubs`
									ON (`Classifieds`.`ID` = `ClassifiedsSubs`.`IDClassified`)
									INNER JOIN `ClassifiedsAdvertisements`
									ON (`ClassifiedsSubs`.`ID` = `ClassifiedsAdvertisements`.`IDClassifiedsSubs`)
									WHERE `Classifieds`.`ID` = '{$iID}'";
			$aSqlResCnt = db_arr($sSqlCntClsAdv);
			$iCnt = $aSqlResCnt['Count'];
			$sCnt = ( $aSqlResCnt['Count'] > 0 ) ? "({$iCnt})" : "(0)";

			//$sCategLink = ($this->bUseFriendlyLinks) ? $site['url'].'ads/c/'.$sCUri : "{$this->sCurrBrowsedFile}?bClassifiedID={$iID}";
			$sCategLink = "{$this->sCurrBrowsedFile}?bClassifiedID={$iID}";

			$sTreeRows .= <<<EOF
<tr>
	<td class="cls_tree_left">
		<img id="tree_action_img_{$iID}" src="{$this->sPicPlus}" onClick="UpdateListCommon('ReloadSubTree','tree_sub_tr_{$iID}','IDClassified', {$iID});"/>
		<img src="{$this->sPicFolder}" />
	</td>
	<td class="cls_tree_right">
		<a href="{$sCategLink}">{$sName} {$sCnt}</a>
	</td>
</tr>
<tr id="1tree_sub_tr_{$iID}">
	<td class="cls_tree_left"></td>
	<td class="cls_tree_right">
		<div id="tree_sub_tr_{$iID}">
		</div>
	</td>
</tr>
EOF;
		}

		$sRetHtml .= <<<EOF
<table class="cls_tree_table">
	{$sTreeRows}
</table>
EOF;
		return $sRetHtml;
	}

	/**
	 * Generate form to adding a new Advertisement
	 *
	 * @return HTML presentation of data
	 */
	function PrintPostAdForm( $arrErr = NULL ) {
		$sCategory = (int)process_db_input( $_POST['FilterCat'] );
		$sCategorySub = process_db_input( $_POST['FilterCatSub'] );
		$sKeywords = process_db_input( $_POST['FilterKeywords'] );
		$sCustomFieldCaption1 = process_db_input( $_POST['ad_CustomFieldCaption1'] );
		$sCustomFieldCaption2 = process_db_input( $_POST['ad_CustomFieldCaption2'] );
		$sSubject = $this->process_html_db_input($_POST['subject']);
		$sMessage = $this->process_html_db_input($_POST['message']);
		$iLifeTime = (int)process_db_input( $_POST['lifetime'] );
		$sTags = process_db_input( $_POST['tags'] );
		if (ereg ("([0-9]+)", process_db_input( $_POST['CustomFieldValue1'] ), $aRegs)) {
			$sCustomFieldValue1 = $aRegs[1];
		}
		if (ereg ("([0-9]+)", process_db_input( $_POST['CustomFieldValue2'] ), $aRegs)) {
			$sCustomFieldValue2 = $aRegs[1];
		}

		$iMemberID = (int)$_COOKIE['memberID'];
		$sRestrictRes = $this->RestrictAction($iMemberID);
		if ($sRestrictRes != '') return $sRestrictRes;
		//if ($this->RestrictAction($iMemberID)) return;

		$iMaxLt = (int)getParam('a_max_live_days_classifieds');

		$vSqlRes = $this -> GetDataOfCls();
		if (mysql_affected_rows()==-1)
			return $this -> GetSQLError("");
		$sCatOptions = '';
		while( $aSqlResStr = mysql_fetch_assoc($vSqlRes) ) {
			$sCatOptions .= "<option value=\"{$aSqlResStr['ID']}\">{$aSqlResStr['Name']}</option>\n";
		}

		$sSubDspStyle = ($sCategorySub!="")?'':'none';
		$sCst1DspStyle = ($sCustomFieldCaption1!="")?'':'none';
		$sCst2DspStyle = ($sCustomFieldCaption2!="")?'':'none';

		$sStepOptions = '';
		for ($i=5; $i<$iMaxLt; $i+=5) {
			$sStepOptions .= "<option value=\"{$i}\"";
			if ($iLifeTime>0 AND $i==$iLifeTime) $sStepOptions .= "selected";
			$sStepOptions .= ">{$i}</option>";
		}
		$sMinStep = '<option value="1">1</option>';
		$sMaxedSelected = ($iLifeTime==$iMaxLt OR $iLifeTime==NULL) ? "selected" : "";
		$sMaxedStep = "<option value=\"{$iMaxLt}\" {$sMaxedSelected}>{$iMaxLt}</option>";
		$sMaxedString = ($iMaxLt) ? _t('_WARNING_MAX_LIVE_DAYS', $iMaxLt) : "db sql error";

		$sCatN = _t('_Category');
		$sSbjN = _t('_Subject');
		$sTagsN = _t('_Tags');
		$sLtN = _t('_Life Time');
		$sDaysN = _t('_days');
		$sMsgN = _t('_Message');
		$sPictN = _t('_Pictures');
		$sAddFileFieldN = _t('_Add');
		//$sSendThisN = _t('_Send these files');
		$sSendN = _t('_Send');
		$sChooseN = _t('_choose');
		$sDeleteC = _t('_Delete');

		$arr = array('SubClassified' => $iSubClassifiedID, 'membID' => $iMemberID, 'Subject' => $sSubject,
			'Message' => $sMessage, 'custVal1' => $sCustomFieldValue1, 'custVal2' => $sCustomFieldValue2, 'Life Time' => $iLifeTime);
		$sSCstyle = ($arrErr['SubClassified'] ? 'block' : 'none');
		$sMSGstyle = ($arrErr['Message'] ? 'block' : 'none');
		$sSBJstyle = ($arrErr['Subject'] ? 'block' : 'none');
		$sLTstyle = ($arrErr['Life Time'] ? 'block' : 'none');
		$sSCmsg = ($arrErr['SubClassified'] ? _t( '_'.$arrErr['SubClassified'] ) : '' );
		$sSBJmsg = ($arrErr['Subject'] ? _t( '_'.$arrErr['Subject'] ) : '' );
		$sMSGmsg = ($arrErr['Message'] ? _t( '_'.$arrErr['Message'] ) : '' );
		$sLTmsg = ($arrErr['Life Time'] ? _t( '_'.$arrErr['Life Time'] ) : '' );

		$sCatHndlVal = $_REQUEST['Classified'];
		$sSubCatHndlVal = $_REQUEST['SubClassified'];
		$sScriptHandle='';
		if (isset ($_REQUEST['SubClassified'])) {
			$sScriptHandle = <<<EOF
<script type="text/javascript">
	addEvent( window, 'load', function(){ UpdateFields('Classified',{$sCatHndlVal},'SubClassified',{$sSubCatHndlVal}); } );
</script>
EOF;
		}

		$sRetHtml = <<<EOF
<form action="{$this -> sCurrBrowsedFile}" name="PostAdForm" method="post" enctype="multipart/form-data" onsubmit="return verify_adding_new_adv('lifetime_tr', 'lifetime', {$iMaxLt});">
	<table class="cls_100_cp">
		<tr class="vc">
			<td class="cls_tbl_left_t">{$sCatN}:</td>
			<td class="cls_tbl_right_m">
				<select name="Classified" id="Classified" onChange="UpdateListCommon('ReloadClassifiedsAndCustomsFields','SubClassified','IDClassified',this.value,'ad_CustomFieldCaption1','ad_CustomFieldCaption2');">
					<option value="-1">{$sChooseN}</option>
					<option value="-1">----------</option>
					{$sCatOptions}
				</select>&nbsp;&nbsp;&nbsp;&nbsp;
				<select class="size20" name="SubClassified" id="SubClassified" style="display:{$sSubDspStyle};">
				</select>
				{$sScriptHandle}
				<div class="edit_error" style="display:{$sSCstyle}">
					{$sSCmsg}
				</div>
			</td>
		</tr>
		<tr class="vc" id="tr1" style="display:{$sCst1DspStyle};">
			<td class="cls_tbl_left_t" id="ad_CustomFieldCaption1"></td>
			<td class="cls_tbl_right_m"><input type="text" name="CustomFieldValue1" value="{$sCustomFieldValue1}" /></td>
		</tr>
		<tr class="vc" id="tr2" style="display:{$sCst2DspStyle};">
			<td class="cls_tbl_left_t" id="ad_CustomFieldCaption2"></td>
			<td class="cls_tbl_right_m"><input type="text" name="CustomFieldValue2" value="{$sCustomFieldValue2}" /></td>
		</tr>
		<tr class="vc">
			<td class="cls_tbl_left_t">{$sSbjN}:</td>
			<td class="cls_tbl_right_m">
				<div class="edit_error" style="display:{$sSBJstyle}">
					{$sSBJmsg}
				</div>
				<input type="text" name="subject" value="{$sSubject}" size="60" maxlength="60" />
			</td>
		</tr>
		<tr class="vc" id="lifetime_tr">
			<td class="cls_tbl_left_t">{$sLtN}:</td>
			<td class="cls_tbl_right_m">
				<div class="edit_error" style="display:{$sLTstyle}">
					{$sLTmsg}
				</div>
				<select name="lifetime" id="lifetime">
					{$sMinStep}
					{$sStepOptions}
					{$sMaxedStep}
				</select> {$sDaysN}. {$sMaxedString}
			</td>
		</tr>
		<tr class="vc">
			<td class="cls_tbl_left_t">{$sTagsN}:</td>
			<td class="cls_tbl_right_m">
				<input type="text" name="tags" value="{$sTags}" size="60" maxlength="60" />
			</td>
		</tr>
		<tr class="vc">
			<td class="cls_tbl_left_t">{$sMsgN}:</td>
			<td class="cls_tbl_right_m">
				<div class="edit_error" style="display:{$sMSGstyle}">
					{$sMSGmsg}
				</div>
				<textarea name="message" rows="20" cols="60" class="classfiedsTextArea" style="width:700px;height:400px;">{$sMessage}</textarea>
			</td>
		</tr>
		<tr class="vc">
			<td class="cls_tbl_left_t">{$sPictN}:</td>
			<td class="cls_tbl_right_m">
				<div id="browse_file_div"></div>
				<a href="#" onclick="AddFilesFields('{$sDeleteC}'); return false;">{$sAddFileFieldN}</a><br />
				<script type="text/javascript">
					AddFilesFields('{$sDeleteC}');
				</script>
			</td>
		</tr>
		<tr class="vc">
			<td class="cls_tbl_left_t"></td>
			<td class="cls_tbl_right_m">
				<input type="submit" name="SendText" value="{$sSendN}" />
				<input type="hidden" name="PostAdMessage" value="Send" />
			</td>
		</tr>
	</table>
</form>
EOF;
		/*<div class="edit_error" style="display:{$sSBJstyle}">
			{$sSBJmsg}
		</div>*/
		//<input type="button" value="{$sAddFileFieldN}" onclick="AddFilesFields()" />
		return $sRetHtml;
	}

	/**
	 * Parsing uploaded files, store its with temp names, fill data into SQL tables
	 *
	 * @param $iMemberID	current member ID
	 * @return Text presentation of data (enum ID`s)
	 */
	function parseUploadedFiles($iMemberID) {
		global $dir;

		$sCurrentTime = time();

		if ( $_FILES) {
			$iIDs='';

			for ($i=0; $i<count($_FILES['userfile']['tmp_name']); $i++) {
				if( $_FILES['userfile']['error'][$i] )
					continue;
				if( $_FILES['userfile']['size'][$i] > $this -> iMaxUplFileSize ) { //if size more than 1mb
					print _t_err('_WARNING_MAX_SIZE_FILE', $_FILES['userfile']['name'][$i]);
					continue;
				}

				list( $width, $height, $type, $attr ) = getimagesize( $_FILES['userfile']['tmp_name'][$i] );

				if ( $type != 1 and $type != 2 and $type != 3 )
					continue;

				$sBaseName = $iMemberID .'_'. $sCurrentTime .'_'. ($i+1);
				$sExt = strrchr($_FILES['userfile']['name'][$i], '.');
				$sExt = strtolower(trim($sExt));

				$vResizeRes = imageResize( $_FILES['userfile']['tmp_name'][$i], "{$dir['root']}{$this -> sUploadDir}img_{$sBaseName}{$sExt}", $this -> iImgSize, $this -> iImgSize );
				$vThumbResizeRes = imageResize( $_FILES['userfile']['tmp_name'][$i], "{$dir['root']}{$this -> sUploadDir}thumb_{$sBaseName}{$sExt}", $this -> iThumbSize, $this -> iThumbSize );
				$vBigThumbResizeRes = imageResize( $_FILES['userfile']['tmp_name'][$i], "{$dir['root']}{$this -> sUploadDir}big_thumb_{$sBaseName}{$sExt}", $this -> iBigThumbSize, $this -> iBigThumbSize );
				$vIconResizeRes = imageResize( $_FILES['userfile']['tmp_name'][$i], "{$dir['root']}{$this -> sUploadDir}icon_{$sBaseName}{$sExt}", $this -> iIconSize, $this -> iIconSize );
				if ( $vResizeRes || $vThumbResizeRes || $vBigThumbResizeRes || $vIconResizeRes ) {
					print _t_err("_ERROR_WHILE_PROCESSING"); 
					continue;
				}

				$vSqlRes = $this -> InsertCAM($iMemberID, $sBaseName, $sExt);
				if ($vSqlRes) {
					$iIDs .= mysql_insert_id().',';
				}
			}
			return $iIDs;
		}
	}

	/**
	 * Insert POSTed data into table `ClassifiedsAdvertisements`
	 *
	 * @return Text presentation of result
	 */
	function ActionPostAdMessage() {
		$sRetTxt = '';
		$iSubClassifiedID = (int)$_POST['SubClassified'];
		$sSuccAdd = _t("_SUCC_ADD_ADV");
		$sFailAdd = _t("_FAIL_ADD_ADV");
		$iVisitorID = (int)$_COOKIE['memberID'];
		if ($iVisitorID > 0 || $this->bAdminMode) {
			$sMedIds = $this -> parseUploadedFiles($iVisitorID);
			$sSubject = $this->process_html_db_input($_POST['subject']);
			if ($sSubject != '' AND $iSubClassifiedID > 0) {
				$sMessage = $this->process_html_db_input($_POST['message']);
				if (ereg ("([0-9]+)", process_db_input( $_POST['CustomFieldValue1'] ), $aRegs)) {
					$sCustomFieldValue1 = $aRegs[1];
				}
				if (ereg ("([0-9]+)", process_db_input( $_POST['CustomFieldValue2'] ), $aRegs)) {
					$sCustomFieldValue2 = $aRegs[1];
				}
				$iLifeTime = process_db_input( $_POST['lifetime'] );
				$sCustomFieldValue1 = ($sCustomFieldValue1==null) ? 'NULL' : "'{$sCustomFieldValue1}'";
				$sCustomFieldValue2 = ($sCustomFieldValue2==null) ? 'NULL' : "'{$sCustomFieldValue2}'";
				$sTags = process_db_input( $_POST['tags'] );
				$aTags = explodeTags($sTags);
				$sTags = implode(",", $aTags);
				$vSqlRes = $this -> InsertCA($iVisitorID, $iSubClassifiedID, $sSubject, $sMessage, $sCustomFieldValue1, $sCustomFieldValue2, $iLifeTime, $sMedIds, $sTags);

				$sRetTxt .= (mysql_affected_rows()>0) ? $sSuccAdd : $sFailAdd;
				$iLastId = mysql_insert_id();
				$this->UseDefaultCF();
				if ($iLastId > 0) {
					reparseObjTags( 'ad', $iLastId );
					return  MsgBox($sRetTxt) . $this -> ActionPrintAdvertisement($iLastId);
				}
				return MsgBox($sRetTxt) . MsgBox(_t('_Error Occured'));
			}
			return MsgBox($sFailAdd);
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}

	/**
	 * Generate list of My Advertisements
	 *
	 * @return HTML presentation of data
	 */
	function PrintMyAds($iOtherProfileID=-1, $iRandLim=-1) {
		global $site;
		$iMemberID = (int)$_COOKIE['memberID'];
		$sRestrictRes = $this->RestrictAction($iMemberID);
		if ($sRestrictRes != '') return $sRestrictRes;
		//if ($this->RestrictAction($iMemberID)) return;

		$sUserListC = ($iOtherProfileID==-1)? _t('_Browse My Ads'): _t('_Users other listing');
		$sBrowseAllAds = _t('_Browse All Ads');

		$sHomeLink = ($this->bUseFriendlyLinks && $this->bAdminMode == false) ? $site['url'].'ads/' : "{$this->sCurrBrowsedFile}?Browse=1";

		$sBreadCrumbs = <<<EOF
<div class="breadcrumbs">
<a href="{$site['url']}">{$site['title']}</a>
/
<a href="{$sHomeLink}">{$sBrowseAllAds}</a>
/
{$sUserListC}
</div>
EOF;

		$sRetHtml = '';
		$iProfileID = ($iOtherProfileID>0)?$iOtherProfileID:$iMemberID;
		$vSqlRes = $this -> GetAdvDataOfProfile($iProfileID, $iRandLim);
		if (mysql_affected_rows()==0) {
			$sRetHtml =  MsgBox(_t('_no posts'));
		} else {
			$bRandLim = ($iRandLim>0) ? TRUE : FALSE;
			while( $aSqlResStr = mysql_fetch_assoc($vSqlRes) ) {
				$sRetHtml .= $this -> ComposeResultStringAdv($aSqlResStr, $bRandLim);
			}
			if ($iOtherProfileID > 0) {
				$sRetHtml = ($iRandLim>0) ? $sRetHtml : $sBreadCrumbs . $sRetHtml;
			}
		}
		if ($iRandLim==-1)
			return  DesignBoxContent ( _t('_My Classifieds'), $sRetHtml, 1);
		else
			return $sRetHtml;
	}

	function DeleteProfileAdvertisement($iProfileID) {
		if ($this->bAdminMode==true) {
			$vDelSQL = db_res("SELECT `ID` FROM `ClassifiedsAdvertisements` WHERE `IDProfile` = '{$iProfileID}'");
			while( $aAdv = mysql_fetch_assoc($vDelSQL) ) {
				$this->ActionDeleteAdvertisement($aAdv['ID']);
			}
		}
	}

	/**
	 * Deleting Advertisement from `ClassifiedsAdvertisements`
	 *
	 * @param $id	ID of deleting Advertisement
	 * @return Text presentation of result
	 */
	function ActionDeleteAdvertisement($id) {
		global $dir;

		$sCheckPostSQL = "SELECT `IDProfile`
							FROM `ClassifiedsAdvertisements`
							WHERE `ID`={$id}
						";
		$aAdvOwner = db_arr($sCheckPostSQL);
		$iAdvOwner = $aAdvOwner['IDProfile'];
		$iVisitorID = (int)$_COOKIE['memberID'];
		if (($iVisitorID == $iAdvOwner || $this->bAdminMode) && $id > 0) {
			if ($this->bAdminMode==false) {
				$sRestrictRes = $this->RestrictAction($iVisitorID);
				if ($sRestrictRes != '') return $sRestrictRes;
				//if ($this->RestrictAction($iVisitorID)) return;
			}

			$sSuccDel = _t("_SUCC_DEL_ADV");
			$sFailDel = _t("_FAIL_DEL_ADV");

			$sRetHtml = '';
			$iDeleteAdvertisementID = (int)$id;
			$sQueryMedia = "
				SELECT `Media` 
				FROM `ClassifiedsAdvertisements` 
				WHERE `ID` = {$iDeleteAdvertisementID}
				LIMIT 1
			";

			$aSqlResAdv = db_assoc_arr( $sQueryMedia );
			$sMediaIDs = $aSqlResAdv['Media'];
			if ($sMediaIDs != '') {
				$aChunks = preg_split ("/[,]+/", $sMediaIDs, -1, PREG_SPLIT_NO_EMPTY);

				foreach ( $aChunks as $iMedId ) {
					$sAdminCut2 = ($this->bAdminMode==false) ? "AND `MediaProfileID` = {$iVisitorID}" : "";
					$sQueryChunkFile = "
						SELECT `MediaFile` 
						FROM `ClassifiedsAdvertisementsMedia` 
						WHERE `MediaID` = {$iMedId}
						{$sAdminCut2}
						LIMIT 1
					";
					$aSqlResMediaName = db_assoc_arr( $sQueryChunkFile );
					$sMediaFileName = $aSqlResMediaName['MediaFile'];
					if ($sMediaFileName != '') {
						if (unlink ( $dir['root'].$this->sUploadDir . 'img_'.$sMediaFileName ) == FALSE) {
							$sRetHtml .= MsgBox(_t('_FAILED_TO_DELETE_PIC', $sMediaFileName));
						}
						if (unlink ( $dir['root'].$this->sUploadDir . 'thumb_'.$sMediaFileName ) == FALSE) {
							$sRetHtml .= MsgBox(_t('_FAILED_TO_DELETE_PIC', $sMediaFileName));
						}
						if (unlink ( $dir['root'].$this->sUploadDir . 'big_thumb_'.$sMediaFileName ) == FALSE) {
							$sRetHtml .= MsgBox(_t('_FAILED_TO_DELETE_PIC', $sMediaFileName));
						}
						if (unlink ( $dir['root'].$this->sUploadDir . 'icon_'.$sMediaFileName ) == FALSE) {
							$sRetHtml .= MsgBox(_t('_FAILED_TO_DELETE_PIC', $sMediaFileName));
						}
					}
					$sQueryMediaID = "DELETE FROM `ClassifiedsAdvertisementsMedia` WHERE `MediaID` = {$iMedId} AND `MediaProfileID` = {$iVisitorID} LIMIT 1";
					$aSqlResMediaID = db_res( $sQueryMediaID );
				}
			}

			$sAdminCut3 = ($this->bAdminMode==false) ? "AND `IDProfile` = {$iVisitorID}" : "";
			$sQuery = "DELETE FROM `ClassifiedsAdvertisements` WHERE `ID` = {$iDeleteAdvertisementID} {$sAdminCut3} LIMIT 1";
            $vSqlResCA = db_res( $sQuery );

			$sRetHtml .= (mysql_affected_rows()>0) ? MsgBox(_t($sSuccDel)) : MsgBox(_t($sFailDel));
			reparseObjTags( 'ad', $iDeleteAdvertisementID );

            if ($vSqlResCA)
            {
    			$oCmts = new BxDolCmts('classifieds', $iDeleteAdvertisementID);
	    		$oCmts->onObjectDelete();
            }

			return $sRetHtml;
		} elseif($iVisitorID != $iAdvOwner) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}

	function getImageManagingCode($sMediaIDs) {
		global $site;
		global $tmpl;
		global $dir;

		$sAddFileFieldN = _t('_Add file field');
		//$sSendThisN = _t('_Send these files');
		$sChooseC = _t('_Upload File');
		$sDeleteC = _t('_Delete');
		$sPhotosC = _t('_photos');

		$sSpacerName = $site['url'].$this->sSpacerPath;

		if ($sMediaIDs != '') {
			$aChunks = preg_split ("/[,]+/", $sMediaIDs, -1, PREG_SPLIT_NO_EMPTY);
			foreach ( $aChunks as $iMedId ) {
				$sSql = "SELECT * FROM `ClassifiedsAdvertisementsMedia` WHERE `MediaID` = {$iMedId}";
				$aSqlRes = db_arr ($sSql);
				if (mysql_affected_rows()>0) {
					$sFileName = $site['url']. $this->sUploadDir.'thumb_'.$aSqlRes['MediaFile'];
					$sImgTag .= <<<EOF
<div style="float:left;">
<img class="photo1" src="{$sSpacerName}" style="width:{$this->iThumbSize}px;height:{$this->iThumbSize}px;background-image:url({$sFileName});" alt="" />
<br />
<a href="#" onclick="UpdateField('DeletedPictureID','{$iMedId}'); document.forms.EditForm.submit(); return false;">{$sDeleteC}</a>
<!-- <input type="button" value="{$sDeleteC}" onclick="UpdateField('DeletedPictureID','{$iMedId}'); document.forms.EditForm.submit(); return false;" /> -->
</div>
EOF;
				}
			}
		}
		//if ($sImgTag != '') {
			$sOldCode = $sImgTag;
			$iEditedClsID = $_REQUEST['EditAdvertisementID'];

			$sImgTag = <<<EOF
<tr class="vc">
	<td class="cls_tbl_left_t">{$sPhotosC}:</td>
	<td class="cls_tbl_right_m">
		<div class="clear_both"></div>
		<div class="thumbnail_block">{$sOldCode}</div>
		<input type="hidden" id="DeletedPictureID" name="DeletedPictureID" value="" />
		<input type="hidden" name="action" value="delete_picture" />
		<div class="clear_both"></div>
	</td>
</tr>
<tr class="vc">
	<td class="cls_tbl_left_t">{$sChooseC}</td>
	<td class="cls_tbl_right_m">
		<div id="browse_file_div"></div>
		<br />
		<a href="#" onclick="AddFilesFields('{$sDeleteC}'); return false;">{$sAddFileFieldN}</a>
		<div class="clear_both"></div>
<script type="text/javascript">
	AddFilesFields('{$sDeleteC}');
</script>
	</td>
</tr>
EOF;
		//<input name="userfile[]" type="file" id="userfile0" /><br />
		//}
		//<input type="button" value="{$sAddFileFieldN}" onclick="AddFilesFields();" />
		return $sImgTag;
	}


	/**
	 * Generate DIV overlapped code of images
	 *
	 * @param $sMediaIDs	string array of images ID
	 * @param $bOnlyFirst	a key that say - show only first picture in array
	 * @return HTML presentation of data
	 */
	function getImageCode($sMediaIDs, $bOnlyFirst = false, $bDisableJS = false) {
		//$imgtag = '';
		global $site;
		global $tmpl;
		global $dir;
		$sSpacerName = $site['url'].$this -> sSpacerPath;

		if ($sMediaIDs != '') {
			$aChunks = preg_split ("/[,]+/", $sMediaIDs, -1, PREG_SPLIT_NO_EMPTY);
			foreach ( $aChunks as $iMedId ) {
				$sSql = "SELECT * FROM `ClassifiedsAdvertisementsMedia` WHERE `MediaID` = {$iMedId}";
				$aSqlRes = db_arr ($sSql);
				if (mysql_affected_rows()>0) {
					if (file_exists($dir['url'].$this -> sUploadDir.'icon_'.$aSqlRes['MediaFile']) == false) {
						$sFileName = "{$site['url']}templates/tmpl_{$tmpl}/{$this -> sPicNotAvail}";
						$sSwitcherCode = '';
					} else {
						$sFileName = $site['url']. $this -> sUploadDir.'icon_'.$aSqlRes['MediaFile'];
						$sSwitcherCode = "onclick=\"changeBigPicTo('{$site['url']}{$this -> sUploadDir}big_thumb_{$aSqlRes['MediaFile']}', '{$site['url']}{$this -> sUploadDir}img_{$aSqlRes['MediaFile']}');\"";
					}
					if ($bDisableJS) $sSwitcherCode = '';
					if ($bOnlyFirst==TRUE) {
						$sImgTag .= "<img class=\"icons\" src=\"{$sSpacerName}\" style=\"width:{$this -> iIconSize}px;height:{$this -> iIconSize}px;background-image:url({$sFileName});\" alt=\"\" />";
					}
					else {
						$sImgTag .= "<img class=\"icons\" src=\"{$sSpacerName}\" {$sSwitcherCode} style=\"width:{$this -> iIconSize}px;height:{$this -> iIconSize}px;background-image:url({$sFileName});\" alt=\"\" />";
					}
				}
				if ($bOnlyFirst==TRUE) {
					break;
				}
			}
		}
		if ($sImgTag == '' AND $bOnlyFirst==TRUE) {
			$sImgTag = "<img class=\"icons\" src=\"{$sSpacerName}\" style=\"width:{$this -> iIconSize}px;height:{$this -> iIconSize}px;background-image:url({$site['url']}templates/tmpl_{$tmpl}/{$this -> sPicNotAvail});\" alt=\"\" />";
		}
		if ($bOnlyFirst==FALSE AND $sImgTag != '') {
			$sOldCode = $sImgTag;

			$sImgTag = '<div class="iconBlock" id="aIconBlock">'.$sOldCode."</div>";
		}
		return $sImgTag;
	}

	function getImageCodeSimple($sMediaIDs) {
		global $site;
		global $tmpl;
		global $dir;
		$sSpacerName = $site['url'].$this -> sSpacerPath;
		$sRet = '';

		if ($sMediaIDs != '') {
			$aChunks = preg_split ("/[,]+/", $sMediaIDs, -1, PREG_SPLIT_NO_EMPTY);
			foreach ( $aChunks as $iMedId ) {
				$sSql = "SELECT * FROM `ClassifiedsAdvertisementsMedia` WHERE `MediaID` = {$iMedId}";
				$aSqlRes = db_arr ($sSql);
				if (mysql_affected_rows()>0) {
					if (file_exists($dir['url'].$this -> sUploadDir.'big_thumb_'.$aSqlRes['MediaFile']) == true) {
						$sFileName = $site['url']. $this -> sUploadDir.'big_thumb_'.$aSqlRes['MediaFile'];

						$sFileNameFullSize = $site['url']. $this -> sUploadDir .'img_'.$aSqlRes['MediaFile'];
						$sDirFileNameFullSize = $dir['url']. $this -> sUploadDir .'img_'.$aSqlRes['MediaFile'];
						list( $width, $height, $type, $attr ) = getimagesize( $sDirFileNameFullSize );
						$iNewWidth = $width+20;
						$iNewHeight = $height+20;

						$sRet .= <<<EOF
<img id="AdvBigImg" class="icons" src="{$sSpacerName}" style="position:absolute;width:{$this -> iBigThumbSize}px;height:{$this -> iBigThumbSize}px;background-image:url({$sFileName});" alt=""
	onclick="window.open('{$sFileNameFullSize}', 'picView', 'width={$iNewWidth},height={$iNewHeight}'); return false;" />
EOF;
						//$sRet .= "<img id=\"AdvBigImg\" class=\"icons\" src=\"{$sSpacerName}\" {$sSwitcherCode} style=\"position:absolute;width:{$this -> iBigThumbSize}px;height:{$this -> iBigThumbSize}px;background-image:url({$sFileName});\" alt=\"\" />";
					}
				}
			}
		}
		return $sRet;
	}


	/**
	 * Generate DIV overlapped code of thumb-image (first only)
	 *
	 * @param $sMediaIDs	string array of images ID
	 * @param $iID	Id of picture that generate a href added code (for link-image)
	 * @return HTML presentation of data
	 */
	function getBigImageCode($sMediaIDs, $iID=0) {
		$sImgTag = '';
		global $site;
		global $tmpl;
		global $dir;
		$sSpacerName = $site['url'].$this -> sSpacerPath;

		$sGenUrl = $this->genUrl($iID, '', 'entry', true);

		if ($sMediaIDs != '') {
			$aChunks = preg_split ("/[,]+/", $sMediaIDs, -1, PREG_SPLIT_NO_EMPTY);
			$iMedId = $aChunks[0];
			$sSql = "SELECT * FROM `ClassifiedsAdvertisementsMedia` WHERE `MediaID` = {$iMedId}";
			$aSqlRes = db_arr ($sSql);
			if ($aSqlRes) {
				$sFileName = $site['url']. $this -> sUploadDir .'thumb_'.$aSqlRes['MediaFile'];
				if (file_exists($dir['url'].$this -> sUploadDir .'thumb_'.$aSqlRes['MediaFile']) == false)
					$sFileName = "{$site['url']}templates/tmpl_{$tmpl}/{$this -> sPicNotAvail}";
				if ((int)$iID == 0) {
					if (file_exists($dir['url'].$this -> sUploadDir .'big_thumb_'.$aSqlRes['MediaFile']) == false) {
						$sFileName = "{$site['url']}templates/tmpl_{$tmpl}/{$this -> sPicNotAvail}";
					} else {
						$sFileName = $site['url']. $this -> sUploadDir .'big_thumb_'.$aSqlRes['MediaFile'];
					}

					if (file_exists($dir['url'].$this -> sUploadDir .'img_'.$aSqlRes['MediaFile']) == false) {
						$sImgTag .= <<<EOF
<img id="AdvBigImg" class="photo" style="width:{$this -> iBigThumbSize}px;height:{$this -> iBigThumbSize}px;background-image:url({$sFileName});" src="{$sSpacerName}" alt="" />
EOF;
					} else {
						$sFileNameFullSize = $site['url']. $this -> sUploadDir .'img_'.$aSqlRes['MediaFile'];
						$sDirFileNameFullSize = $dir['url']. $this -> sUploadDir .'img_'.$aSqlRes['MediaFile'];
						list( $width, $height, $type, $attr ) = getimagesize( $sDirFileNameFullSize );
						$iNewWidth = $width+20;
						$iNewHeight = $height+20;
						$sImgTag .= <<<EOF
<a id="AdvBigImgFullSize" href="{$sFileNameFullSize}" target="_blank" onclick="window.open(this.href, 'picView', 'width={$iNewWidth},height={$iNewHeight}'); return false;">
	<img id="AdvBigImg" class="photo" style="width:{$this -> iBigThumbSize}px;height:{$this -> iBigThumbSize}px;background-image:url({$sFileName});" src="{$sSpacerName}" alt="" />
</a>
EOF;
					}

				}
				else {
					//"{$this -> sCurrBrowsedFile}?ShowAdvertisementID={$iID}"
					$sImgTag .= <<<EOF
<a href="{$sGenUrl}">
	<img src="{$sSpacerName}" style="width:{$this->iThumbSize}px;height:{$this->iThumbSize}px; background-image: url({$sFileName});" class="photo1" alt="" />
</a>
EOF;
				}
			}
		}
		if ($sImgTag == '') {
			$sNaname = $site['url'].'templates/tmpl_'.$tmpl.'/'.$this -> sPicNotAvail;
			if ((int)$iID == 0) {
				$sImgTag .= "<img id=\"AdvBigImg\" class=\"photo\" style=\"width:{$this->iBigThumbSize}px;height:{$this->iBigThumbSize}px;background-image:url({$sNaname});\" src=\"{$sSpacerName}\" alt=\"\" />";
			}
			else {
				//"{$this -> sCurrBrowsedFile}?ShowAdvertisementID={$iID}"
				$sImgTag .= <<<EOF
<a href="{$sGenUrl}">
	<img src="{$sSpacerName}" style="width:{$this->iThumbSize}px;height:{$this->iThumbSize}px; background-image: url({$sNaname});" class="photo1" alt="" />
</a>
EOF;
			}
		}
		return $sImgTag;
	}

	/**
	 * Generate first paid page
	 *
	 * @param $id	ID of Advertisement
	 * @return HTML presentation of data
	 */
	function ActionBuyAdvertisement($iAdvertisementID) {
		$iMemberID = (int)$_COOKIE['memberID'];
		$sRetHtml = ''; //_t('_WARNING');
		if ($iMemberID > 0 /*AND $iMemberID != $nameRet['ID']*/ ) {
			$aSqlResStr = $this -> GetAdvertisementData( $iAdvertisementID );
			if ($aSqlResStr) {
				$sCustDetails = ($aSqlResStr['CustomFieldName1'] != NULL AND $aSqlResStr['CustomFieldValue1'])?"{$aSqlResStr['Unit']} {$aSqlResStr['CustomFieldValue1']}":'';
				$sCustDetails .= ($aSqlResStr['CustomFieldName2'] != NULL AND $aSqlResStr['CustomFieldValue2'])?" - {$aSqlResStr['Unit']} {$aSqlResStr['CustomFieldValue2']}":'';
				$sBuyMsg1 = _t('_CLS_BUYMSG_1');
				$sBuyDet1 = _t('_CLS_BUY_DET1');
				$sContinue = _t('_Continue');

				$sBoxTag = <<<EOF
<div>
	<b>{$sBuyMsg1}</b>
</div><br/>
<div>
	<b>{$sBuyDet1}</b>&nbsp;&nbsp;&nbsp;{$sCustDetails}
</div><br/>
<div>
	<input class="button" type="submit" onclick="javascript:this.value='Wait...';this.disabled=true;document.bid_form.submit();" value="{$sContinue}" />
</div>
EOF;

				$sRetHtml .= DesignBoxContent ( $aSqlResStr['Subject'], $sBoxTag, 1);
				$sRetHtml .= <<<EOF
<form action="{$this->sCurrBrowsedFile}" name="bid_form" method="post">
	<input type="hidden" name="BuySendNow" value="BuySendNow" />
	<input type="hidden" name="IDAdv" value="{$iAdvertisementID}" />
	<input type="hidden" name="IDSeller" value="{$aSqlResStr['IDProfile']}" />
</form>
EOF;
			}
		}
		return $sRetHtml;
	}

	/**
	 * Generate second paid page
	 *
	 * @param $id	ID of Advertisement
	 * @return HTML presentation of data
	 */
	function ActionBuySendMailAdvertisement($iAdvertisementID) {
		global $site;
		$iSellerId = (int)$_REQUEST['IDSeller'];
		$iMemberID = (int)$_COOKIE['memberID'];
		$sRetHtml = _t('_WARNING');
		if ($iMemberID > 0 /*AND $iMemberID != $nameRet['ID']*/ ) {
			$aSqlResStr = $this -> GetAdvertisementData($iAdvertisementID);
			$aSqlSellerRes = $this -> GetProfileData($iSellerId);
			$aSqlMemberRes = $this -> GetProfileData($iMemberID);
			if ($aSqlResStr) {
				$sCustDetails = ($aSqlResStr['CustomFieldName1'] != NULL AND $aSqlResStr['CustomFieldValue1'])?"{$aSqlResStr['Unit']} {$aSqlResStr['CustomFieldValue1']}":'';
				$sCustDetails .= ($aSqlResStr['CustomFieldName2'] != NULL AND $aSqlResStr['CustomFieldValue2'])?" - {$aSqlResStr['Unit']} {$aSqlResStr['CustomFieldValue2']}":'';

				$sCopyright = _t('_copyright', 2007);
				$sBottom = _t('_bottom_text', 2007);
				$sPowDol = _t('_powered_by_Dolphin');
				$sBuyMsg2 = _t('_CLS_BUYMSG_2');
				$sBuyDet1 = _t('_CLS_BUY_DET1');
				$sReturnBackC = _t('_Return Back');

				// Send email notification
				$sMessageB	= getParam( "t_BuyNow" );
				$sMessageS	= getParam( "t_BuyNowS" );
				$sSubject	= getParam('t_BuyNow_subject');
				$sSubjectS	= getParam('t_BuyNowS_subject');

				$aPlus = array();
				$aPlus['Subject'] = $aSqlResStr['Subject'];
				$aPlus['NickName'] = $aSqlSellerRes['NickName'];
				$aPlus['EmailS'] = $aSqlSellerRes['Email'];
				$aPlus['NickNameB'] = $aSqlMemberRes['NickName'];
				$aPlus['EmailB'] = $aSqlMemberRes['Email'];
				$aPlus['sCustDetails'] = $sCustDetails;

				$sGenUrl = $this->genUrl($iAdvertisementID, $aSqlResStr['EntryUri']);
				$aPlus['ShowAdvLnk'] = $sGenUrl; //"{$site['url']}classifieds.php?ShowAdvertisementID={$iAdvertisementID}";

				$aPlus['sPowDol'] = $sPowDol;
				$aPlus['site[\'email\']'] = $site['email'];
				$aPlus['sCopyright'] = $sCopyright;
				$aPlus['sBottom'] = $sBottom;

				$sRetHtml = '';
				$aPlus['Who'] = 'buyer';
				$aPlus['String1'] = 'You have purchased an item';
				if (sendMail( $aSqlMemberRes['Email'], $sSubject, $sMessageB, $aSqlSellerRes['ID'], $aPlus, 'html')) {
					//$sRetHtml .= MsgBox(_t('_Email was successfully sent'));
				}
				$aPlus['Who'] = 'seller';
				$aPlus['String1'] = 'Someone wants to purchase an item that you have offered for sale';
				if (sendMail( $aSqlSellerRes['Email'], $sSubjectS, $sMessageS, $aSqlSellerRes['ID'], $aPlus, 'html')) {
					$sRetHtml .= MsgBox(_t('_Email was successfully sent'));
				}
				//"{$this->sCurrBrowsedFile}?ShowAdvertisementID={$iAdvertisementID}"
				$sBoxContent = <<<EOF
<div>
	<b>{$sBuyMsg2}</b>
</div><br/>
<div>
	<b>{$sBuyDet1}</b>&nbsp;&nbsp;&nbsp;{$sCustDetails}
</div><br/>
<div>
	<a href="{$sGenUrl}">{$sReturnBackC}</a>
</div>
EOF;

				$sRetHtml .= DesignBoxContent ( $sBuyMsg2, $sBoxContent, 1);
			}
		}
		return $sRetHtml;
	}

	/**
	 * Generate presentation Advertisement code with images and other
	 *
	 * @param $id	ID of Advertisement
	 * @return HTML presentation of data
	 */
	function ActionPrintAdvertisement($id) {
		global $site;
		global $aPreValues;
		global $logged;
		
		$iAdvertisementID = (int)$id;
		$sRetHtml = '';

		$aSqlResStr = $this -> GetAdvertisementData($iAdvertisementID);
		if ($aSqlResStr) {
			$sAdminLocalAreaC = _t('_AdminArea');
			$sBigImg = $this -> getBigImageCode($aSqlResStr['Media']);
			$bUseNewFeature = getParam('ads_gallery_feature') == 'on' ? true : false;
			$sImg = $this -> getImageCode($aSqlResStr['Media'], false, $bUseNewFeature);

			$aNameRet = $this -> GetProfileData($aSqlResStr['IDProfile']);
			$sCountryName = ($aNameRet['Country']=="")?$sAdminLocalAreaC:_t($aPreValues['Country'][ $aNameRet['Country'] ]['LKey'] );
			$sCountryPic = ($aNameRet['Country']=='')?'':' <img alt="'.$aNameRet['Country'].'" src="'.($site['flags'].strtolower($aNameRet['Country'])).'.gif"/>';

			/*if( $logged['member'] )
				$sVisible = 'memb';
			else
				$sVisible = 'non';
			$sSqlCols = "
				SELECT * 
				FROM `ClsAdvCompose` 
				WHERE
					`Column` != 0 AND
					FIND_IN_SET( '{$sVisible}', `Visible` )
				ORDER BY `Column` , `Order`
			";

			$sqlCaptRes = db_res($sSqlCols);
			$aCaptions = array();
			while($aCapSelect = mysql_fetch_assoc($sqlCaptRes)) {
				$aCaptions[$aCapSelect['Func']] = _t( $aCapSelect['Caption'] );
			}*/

			$sPostedByC = _t('_Posted by');
			$sAdminC = _t('_Admin');
			$sPhoneC = _t('_Phone');
			$sICQC = _t('_ICQ');
			$sDetailsC = _t('_Details');
			$sReplyC = _t('_Reply');
			$sSubjectC = _t('_Subject');
			$sDateC = _t('_Date');
			$sLocationC = _t('_Location');
			$sDescriptionC = _t('_Description');
			$sUserOtherListC = _t('_Users other listing');
			$sActionsC = _t('_Actions');
			$sPhotosC = _t('_Photos');
			$sEditC = _t('_Edit');
			$sAdvertisementC = _t('_Advertisement');
			$sTagsC = _t('_Tags');
			$sBuyNowC = _t('_Buy Now');
			$sDeleteC = _t('_Delete');
			$sSureC = _t("_Are you sure");
			$sActivateC = _t('_Activate');

			$sPostedBy = '';
			if (! $aNameRet['NickName']) {
				$sPostedBy .= '<div class="cls_res_info">'.$sPostedByC.': '.$sAdminC.'</div>';
			}
			else {
				$sPostedBy .= '<div class="cls_res_info">';
				$sPostedBy .= $sPostedByC.': <span style="color:#333333;"><a href="'.getProfileLink($aNameRet['ID']).'">'.$aNameRet['NickName'].'</a></span>';
				$sPostedBy .= '</div>';
				if ($aNameRet['Phone'] != "") {
					$sPostedBy .= '<div class="cls_res_info">';
					$sPostedBy .= $sPhoneC.": <div class=\"clr3\">{$aNameRet['Phone']}</div>";
					$sPostedBy .= '</div>';
				}
				if ($aNameRet['IcqUIN'] != "") {
					$sPostedBy .= '<div class="cls_res_info">';
					$sPostedBy .= $sICQC."#<div class=\"clr3\">{$aNameRet['IcqUIN']}</div>";
					$sPostedBy .= '</div>';
				}
			}
			$sTimeAgo = _format_when($aSqlResStr['sec']);

			$sCustDetails .= ($aSqlResStr['CustomFieldName1'] || $aSqlResStr['CustomFieldName2']) ? "{$sDetailsC}: " : '';
			$sCustDetails .= ($aSqlResStr['CustomFieldName1'] && $aSqlResStr['CustomFieldValue1'])?"<div class=\"clr3\">{$aSqlResStr['Unit']} {$aSqlResStr['CustomFieldValue1']}</div>":'';
			$sCustDetails .= ($aSqlResStr['CustomFieldName1'] && $aSqlResStr['CustomFieldName2'] && $aSqlResStr['CustomFieldValue1'] && $aSqlResStr['CustomFieldValue2']) ? " - " : '';
			$sCustDetails .= ($aSqlResStr['CustomFieldName2'] && $aSqlResStr['CustomFieldValue2'])?"<div class=\"clr3\">{$aSqlResStr['Unit']} {$aSqlResStr['CustomFieldValue2']}</div>":'';

			$sTags .= $sTagsC.': <div class="clr3">';
			$aTags = array();
			$aTagsLinks = array();
			$aTags = explode(",", $aSqlResStr['Tags']);
			foreach ( $aTags as $sTag ) {
				$sSubLink = ($this->bUseFriendlyLinks) ? "ads/tag/" : "classifieds_tags.php?tag=";
				$aTagsLinks[] = '<a href="'."{$site['url']}{$sSubLink}{$sTag}".'">'.$sTag.'</a>';
			}
			$sTags .= implode(", ", $aTagsLinks);
			$sTags .= '</div>';

			$sMemberActions = '';
			$iMemberID = ($logged['member']) ? (int)$_COOKIE['memberID'] : -1;
			//$iMemberID = (int)$_COOKIE['memberID'];
			if ($iMemberID > 0 AND $iMemberID != $aNameRet['ID']) {//print Send PM button and other actions
				$bBnp = getParam('enable_paid_system');

				$sBuyNow = '';
				if ($bBnp=='on') {
					$sBuyNow = <<<EOF
<img src="{$site['icons']}cool.gif" alt="Buy" title="Buy" class="marg_icon" />
<a class="actions" href="{$this -> sCurrBrowsedFile}" onclick="document.forms['BuyNowForm'].submit(); return false;">
	{$sBuyNowC}
</a>
<form action="{$this -> sCurrBrowsedFile}" name="BuyNowForm" method="post">
	<input type="hidden" name="BuyNow" value="BuyNow" />
	<input type="hidden" name="IDAdv" value="{$id}" />
	<input type="hidden" name="IDSeller" value="{$aSqlResStr['IDProfile']}" />
</form>
<br/>
EOF;
				}
				$sMemberActions .= <<<EOF
{$sBuyNow}
<img src="{$site['icons']}action_send.gif" alt="Post PM" title="Post PM" class="marg_icon" />
<a class="actions" href="{$this -> sCurrBrowsedFile}" onclick="document.forms['post_pm'].submit(); return false;">
{$sReplyC}
</a>
<form action="{$site['url']}compose.php" name="post_pm" id="post_pm" method="post">
	<input type="hidden" name="ID" value="{$aSqlResStr['IDProfile']}" />
	<input type="hidden" name="subject" value="{$aSqlResStr['Subject']}" />
	<input type="hidden" value="{$sReplyC}" />
</form>
EOF;
			}
			elseif ($iMemberID == $aNameRet['ID'] AND $this -> bAdminMode==FALSE) {
				$sMemberActions .= '<div class="cls_result_row">';
				$sMemberActions .= $this->CenteredActionsBlock('<img src="'.$site['icons'].'description_edit.png" alt="'.$sEditC.
				'" title="'.$sEditC.'" class="marg_icon" />', '<a class="actions" href="'.$this->sCurrBrowsedFile.
				'" onclick="UpdateField(\'EditAdvertisementID\','.$iAdvertisementID.
				'); document.forms.command_edit_advertisement.submit(); return false;">'.$sEditC.'</a>');
				$sMemberActions .= '</div>';
				$sMemberActions .= '<div class="cls_result_row">';
				$sMemberActions .= $this->CenteredActionsBlock('<img src="'.$site['icons'].'action_block.gif" alt="'.$sDeleteC.
				'" title="'.$sDeleteC.'" class="marg_icon" />', '<a class="actions" href="'.$this->sCurrBrowsedFile.
				'" onclick="if (confirm(\''.$sSureC.'\')) { UpdateField(\'DeleteAdvertisementID\','.$iAdvertisementID.');document.forms.command_delete_advertisement.submit(); } return false;">'.$sDeleteC.'</a>');
				$sMemberActions .= '</div>';
			}

			$sAdminPart = '';
			if ($this -> bAdminMode) {

				$sActivateAbil = ($aSqlResStr['Status']=='active') ? '' : <<<EOF
<div class="ar">
	<span style="vertical-align: middle;margin-right:5px;">
	<img src="{$site['icons']}online.gif" style="position:static;" />
	</span>
	<span>
	<a class="actions" href="{$this -> sCurrBrowsedFile}" onclick="UpdateField('ActivateAdvertisementID',{$iAdvertisementID});document.forms.command_activate_advertisement.submit(); return false;">
	{$sActivateC}</a>
	</span>
</div>
EOF;

				$sAdminPart .= <<<EOF
<div class="clear_both"></div>
{$sActivateAbil}
<div class="ar">
	<span style="vertical-align: middle;margin-right:5px;">
	<img src="{$site['icons']}delete.png" style="position:static;" />
	</span>
	<span>
	<a class="actions" href="{$this -> sCurrBrowsedFile}" onclick="if (confirm('{$sSureC}')) { UpdateField('DeleteAdvertisementID',{$iAdvertisementID});document.forms.command_delete_advertisement.submit(); } return false;">
	{$sDeleteC}</a>
	</span>
</div>
<div class="ar">
	<span style="vertical-align: middle;margin-right:5px;">
	<img src="{$site['icons']}_logout.jpg" style="position:static;" />
	</span>
	<span>
	<a class="actions" href="{$this -> sCurrBrowsedFile}" onclick="UpdateField('EditAdvertisementID',{$iAdvertisementID}); document.forms.command_edit_advertisement.submit(); return false;">
	{$sEditC}</a>
	</span>
</div>
<div class="clear_both"></div>
EOF;
			}

			$sPictureSectContent = '';
			if ($bUseNewFeature==true) {
				/////Gall Ench
				$sGallEnchImg = $this->getImageCodeSimple($aSqlResStr['Media']);

				$sPictureSectContent = <<<EOF
<script type="text/javascript" src="{$site['url']}inc/js/jquery.dolPromoT.js"></script>
<script type="text/javascript">
	$(document).ready( function() {
		$( '#indexPhoto' ).dolPromo( 3000, 1 );
	} );
</script>
<script type="text/javascript">
	if (window.attachEvent)
		window.attachEvent( "onload", onloadPhotos );
	else
		window.addEventListener( "load", onloadPhotos, false);

	function onloadPhotos()
	{
		hideScroll();
	}

	//hide scrollers if needed
	function hideScroll()
	{
		b = document.getElementById( "aIconBlock" );
		s = document.getElementById( "aScrollCont" );
		
		if( !b || !s ) {
			aibc = document.getElementById( "aIconBlockCont" );
			aibc.style.display = "none";
			s.style.display = "none";
			return false;
		}

		if( b.parentNode.clientWidth >= b.clientWidth )
			s.style.display = "none";
		else
			s.style.display = "block";
	}
</script>

<div style="position:relative;width:100%;height:450px;overflow:hidden;">
	<div class="photoBlock" id="indexPhoto">
		{$sGallEnchImg}
	</div>
</div>

<div class="clear_both"></div>
<div class="iconBlockCont" id="aIconBlockCont">
	{$sImg}
</div>

<div id="aScrollCont" class="scrollCont">
	<div class="scrollLeft" onmouseover="moveScrollLeftAuto('aIconBlock', true);" onmouseout="moveScrollLeftAuto('aIconBlock', false);">
		<img src="{$site['icons']}left_arrow.gif" style="position:static;" alt="" />
	</div>
	<div class="scrollRight" onmouseover="moveScrollRightAuto('aIconBlock', true);" onmouseout="moveScrollRightAuto('aIconBlock', false);">
		<img src="{$site['icons']}right_arrow.gif" style="position:static;" alt="" />
	</div>
</div>

<div class="clear_both"></div>
EOF;
				/////Gall Ench end
			} else {
				$sPictureSectContent = <<<EOF
<script type="text/javascript">
	if (window.attachEvent)
		window.attachEvent( "onload", onloadPhotos );
	else
		window.addEventListener( "load", onloadPhotos, false);

	function onloadPhotos()
	{
		hideScroll();
	}

	//hide scrollers if needed
	function hideScroll()
	{
		b = document.getElementById( "aIconBlock" );
		s = document.getElementById( "aScrollCont" );
		
		if( !b || !s ) {
			aibc = document.getElementById( "aIconBlockCont" );
			aibc.style.display = "none";
			s.style.display = "none";
			return false;
		}

		if( b.parentNode.clientWidth >= b.clientWidth )
			s.style.display = "none";
		else
			s.style.display = "block";
	}
</script>
<div class="photoBlock">
	{$sBigImg}
</div>
<div class="iconBlockCont" id="aIconBlockCont">
	{$sImg}
</div>
<div id="aScrollCont" class="scrollCont">
	<div class="scrollLeft" onmouseover="moveScrollLeftAuto('aIconBlock', true);" onmouseout="moveScrollLeftAuto('aIconBlock', false);">
		<img src="{$site['icons']}left_arrow.gif" style="position:static;" alt="" />
	</div>
	<div class="scrollRight" onmouseover="moveScrollRightAuto('aIconBlock', true);" onmouseout="moveScrollRightAuto('aIconBlock', false);">
		<img src="{$site['icons']}right_arrow.gif" style="position:static;" alt="" />
	</div>
</div>
<div class="clear_both"></div>
EOF;
			}

			$sPictureSect = DesignBoxContent ( _t('_Advertisement Photos'), $sPictureSectContent, 1);
			$this->sTAPhotosContent = $sPictureSectContent;

			$sActionsSect = ($iMemberID>0 || $this->bAdminMode) ? DesignBoxContent ( $sActionsC, $sMemberActions.$sAdminPart, 1) : '';
			$this->sTAActionsContent = ($iMemberID>0 || $this->bAdminMode) ? $sMemberActions.$sAdminPart : '';

			if ($iMemberID>0 || $this->bAdminMode) {
				$oComments = new BxDolComments(1, $this->sCurrBrowsedFile);
				$oComments->bAdminMode = $this->bAdminMode;
				//$this->sTACommentsContent = $oComments->PrintCommentSection($iAdvertisementID, $aCaptions['Comments'], false);
				$this->oCmtsView = new BxTemplCmtsView ('classifieds', (int)$iAdvertisementID);

				$this->sTACommentsContent = $this->oCmtsView->getExtraCss();
				$this->sTACommentsContent .= $this->oCmtsView->getExtraJs();
				$this->sTACommentsContent .= (!$this->oCmtsView->isEnabled()) ? '' : $this->oCmtsView->getCommentsFirst();

				$show_hide = $oComments->genShowHideItem( 'comments_section' );
				$sCommSect = DesignBoxContent ( $aCaptions['Comments'], $this->sTACommentsContent, 1, $show_hide);

				//$sCommSect = $oComments->PrintCommentSection($iAdvertisementID, $aCaptions['Comments'], true);
			}
			$sUserOtherListing = $this -> PrintMyAds($aSqlResStr['IDProfile'], 2);

			$sSubjectSectContent = <<<EOF
{$sPostedBy}
<div class="cls_res_info">
	{$sDateC}: <div class="clr3">{$aSqlResStr['DateTime']} ({$sTimeAgo})</div>
</div>
<div class="cls_res_info">
	{$sLocationC}: <div class="clr3">{$sCountryName}{$sCountryPic}</div>
</div>
<div class="cls_res_info">
	{$sTags}
</div>
<div class="cls_res_info">
	{$sCustDetails}
</div>
EOF;
			$sSubjectSect = DesignBoxContent ( $aSqlResStr['Subject'], $sSubjectSectContent, 1);
			$this->sTAInfoContent = $sSubjectSectContent;

			$sDescriptionSect = DesignBoxContent ( $sDescriptionC, $aSqlResStr['Message'], 1);
			$this->sTADescriptionContent = $aSqlResStr['Message'];

			$sOtherListingContent = <<<EOF
{$sUserOtherListing}
<a class="actions" href="{$this -> sCurrBrowsedFile}" onclick="document.forms['UsersOtherListingForm'].submit(); return false;">{$sUserOtherListC}</a>
<form action="{$this -> sCurrBrowsedFile}" name="UsersOtherListingForm" method="post">
	<input type="hidden" name="UsersOtherListing" value="1" />
	<input type="hidden" name="IDProfile" value="{$aSqlResStr['IDProfile']}" />
</form>
EOF;
			$sOtherListingSect = DesignBoxContent ( $sUserOtherListC, $sOtherListingContent, 1);
			$this->sTAOtherListingContent = $sOtherListingContent;

			$sHomeLink = ($this->bUseFriendlyLinks && $this->bAdminMode == false) ? $site['url'].'ads/' : "{$this->sCurrBrowsedFile}?Browse=1";
			$sCategLink = ($this->bUseFriendlyLinks && $this->bAdminMode == false) ? $site['url'].'ads/cat/'.$aSqlResStr['CEntryUri'] : "{$this->sCurrBrowsedFile}?bClassifiedID={$aSqlResStr['CatID']}";
			$sSCategLink = ($this->bUseFriendlyLinks && $this->bAdminMode == false) ? $site['url'].'ads/subcat/'.$aSqlResStr['SEntryUri'] : "{$this->sCurrBrowsedFile}?bSubClassifiedID={$aSqlResStr['SubID']}";

			$sBrowseAllAds = _t('_Browse All Ads');
			$sBreadCrumbs = <<<EOF
<div class="breadcrumbs">
<a href="{$site['url']}">{$site['title']}</a>
/
<a href="{$sHomeLink}">{$sBrowseAllAds}</a>
/
<a href="{$sCategLink}">{$aSqlResStr['Name']}</a>
/
<a href="{$sSCategLink}">{$aSqlResStr['NameSub']}</a>
</div>
EOF;


			//$iScrollWidth = $this->iScrollWidth;
			//$iScrollHeight = $this->iScrollWidth+0;

			/*$sqlColRes = db_res($sSqlCols);
			$aBlocks = array();
			while($aBlockSelect = mysql_fetch_assoc($sqlColRes)) {
				switch( $aBlockSelect['Func'] ) {
					case 'Advertisement Photos': $aBlocks[$aBlockSelect['Column']] .= $sPictureSect;      break;
					case 'Actions':              $aBlocks[$aBlockSelect['Column']] .= $sActionsSect;      break;
					case 'Comments':             $aBlocks[$aBlockSelect['Column']] .= $sCommSect;         break;
					case 'Adv Info':             $aBlocks[$aBlockSelect['Column']] .= $sSubjectSect;      break;
					case 'Description':          $aBlocks[$aBlockSelect['Column']] .= $sDescriptionSect;  break;
					case 'Users Other Listing':  $aBlocks[$aBlockSelect['Column']] .= $sOtherListingSect; break;
					case 'Echo':
						$aBlocks[$aBlockSelect['Column']] .= DesignBoxContent( _t( $aBlockSelect['Caption'] ), $aBlockSelect['Content'], 1 );
					break;
					case 'RSS':
						list( $sUrl, $iNum ) = explode( '#', $aBlockSelect['Content'] );
						$iNum = (int)$iNum;
						
						$ret = genRSSHtmlOut( $sUrl, $iNum );
						
						$aBlocks[$aBlockSelect['Column']] .= DesignBoxContent( _t( $aBlockSelect['Caption'] ), $ret, 1 );
					break;
				}
			}*/

			$aBlocks[1] .= $sPictureSect;
			$aBlocks[1] .= $sActionsSect;
			$aBlocks[1] .= $sCommSect;
			$aBlocks[2] .= $sSubjectSect;
			$aBlocks[2] .= $sDescriptionSect;
			$aBlocks[2] .= $sOtherListingSect;

			$sRetHtml = <<<EOF
{$sBreadCrumbs}
<div>
	<div class="clear_both"></div>
	<div class="cls_info_left">
		{$aBlocks['1']}
	</div>
	<div class="cls_info">
		{$aBlocks['2']}
	</div>
	<div class="clear_both"></div>
</div>
<div class="clear_both"></div>
EOF;
		}
		return $sRetHtml;
	}

	/**
	 * Generate array of Advertisements of some Classified
	 *
	 * @param $iClassifiedID	ID of Classified
	 * @return HTML presentation of data
	 */
	function PrintAllSubRecords($iClassifiedID) {
		global $site;

		////////////////////////////
		$sSQLl = $this->GetAdvByDateCnt($iClassifiedID );

		$aTotalNum = db_arr( $sSQLl );
		$iTotalNum = $aTotalNum['Cnt'];
		if( !$iTotalNum ) {
			return MsgBox(_t( '_Sorry, nothing found' ));
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
		$sqlLimit = "LIMIT $sLimitFrom, $iPerPage";
		////////////////////////////

		$vSqlRes = $this -> GetAdvByDate($iClassifiedID, $sqlLimit);

		$sNameCat = '';
		$sDescCat = '';

		$sSubsHtml = '';
		while( $aSqlResStr = mysql_fetch_assoc($vSqlRes) ) {
			$sSubsHtml .= $this -> ComposeResultStringAdv($aSqlResStr);
			if ($sNameCat=='') {
				$sNameCat = $aSqlResStr['Name'];
				$sDescCat = $aSqlResStr['Description'];
			}
		}

		$sCaption = "<div class=\"fl\">{$sNameCat}</div>\n";

		$sDesc = "<div class=\"cls_result_row\">{$sDescCat}</div>";

		$sHomeLink = ($this->bUseFriendlyLinks && $this->bAdminMode == false) ? $site['url'].'ads/' : "{$this->sCurrBrowsedFile}?Browse=1";

		$sBrowseAllAds = _t('_Browse All Ads');
		$sBreadCrumbs = <<<EOF
<div class="breadcrumbs">
<a href="{$site['url']}">{$site['title']}</a>
/
<a href="{$sHomeLink}">{$sBrowseAllAds}</a>
/
<span class="active_link">{$sNameCat}</span>
</div>
EOF;

		///////////////////////////
		if ($this->bUseFriendlyLinks==false || $this -> bAdminMode==true) { //old variant
			if( $iTotalPages > 1)
			{
				$sRequest = $_SERVER['PHP_SELF'] . '?';
				$aFields = array( 'bClassifiedID', 'bSubClassifiedID', 'catUri', 'scatUri' );
				
				foreach( $aFields as $field )
					if( isset( $_GET[$field] ) )
						$sRequest .= "&amp;{$field}=" . htmlentities( process_pass_data( $_GET[$field] ) );
				
				$pagination = '<div style="text-align: center; position: relative;">'._t("_Results per page").':
						<select name="per_page" onchange="window.location=\'' . $sRequest . '&amp;per_page=\' + this.value;">
							<option value="10"' . ( $iPerPage == 10 ? ' selected="selected"' : '' ) . '>10</option>
							<option value="20"' . ( $iPerPage == 20 ? ' selected="selected"' : '' ) . '>20</option>
							<option value="50"' . ( $iPerPage == 50 ? ' selected="selected"' : '' ) . '>50</option>
							<option value="100"' . ( $iPerPage == 100 ? ' selected="selected"' : '' ) . '>100</option>
						</select></div>' .
					genPagination( $iTotalPages, $iCurPage, ( $sRequest . '&amp;page={page}&amp;per_page='.$iPerPage ) );
			}
			else
				$pagination = '';
		} else {
			if( $iTotalPages > 1)
			{
				$sRequest = 'ads/all/cat/';
				$sPaginAddon = '/' . process_db_input($_GET['catUri']);
				/*$aFields = array( 'bClassifiedID', 'bSubClassifiedID', 'catUri', 'scatUri' );
				
				foreach( $aFields as $field )
					if( isset( $_GET[$field] ) )
						$sRequest .= "&amp;{$field}=" . htmlentities( process_pass_data( $_GET[$field] ) );*/
				
				$pagination = '<div style="text-align: center; position: relative;">'._t("_Results per page").':
						<select name="per_page" onchange="window.location=\'' . $sRequest . '\' + this.value + \'/1'.$sPaginAddon.'\';">
							<option value="10"' . ( $iPerPage == 10 ? ' selected="selected"' : '' ) . '>10</option>
							<option value="20"' . ( $iPerPage == 20 ? ' selected="selected"' : '' ) . '>20</option>
							<option value="50"' . ( $iPerPage == 50 ? ' selected="selected"' : '' ) . '>50</option>
							<option value="100"' . ( $iPerPage == 100 ? ' selected="selected"' : '' ) . '>100</option>
						</select></div>' .
					genPagination( $iTotalPages, $iCurPage, ( $sRequest.$iPerPage . '/{page}' . $sPaginAddon  ) );
			}
			else
				$pagination = '';
		}
		///////////////////////////

		$sSubsHtml .= '<div class="clear_both"></div>';
		$sTagsSect = DesignBoxContent ( $sBreadCrumbs, /*$sDesc.*/$sSubsHtml.$pagination, 1);
		return $sTagsSect;
	}

	/**
	 * Generate array of Advertisements of some SubClassified
	 *
	 * @param $iIDClassifiedsSubs	ID of SubClassified
	 * @param $bTabledView	fill collected data into another table
	 * @return HTML presentation of data
	 */
	function PrintSubRecords($iIDClassifiedsSubs, $bTabledView=FALSE) {
		global $site;
		$iIDClassifiedsSubs = (int)$iIDClassifiedsSubs;
		$sRetHtml = '';

		////////////////////////////
		$sSQLl = $this->GetAdvByDateCnt($iIDClassifiedsSubs,TRUE );

		$aTotalNum = db_arr( $sSQLl );
		$iTotalNum = $aTotalNum['Cnt'];
		if( !$iTotalNum ) {
			return MsgBox(_t( '_Sorry, nothing found' ));
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
		$sqlLimit = "LIMIT $sLimitFrom, $iPerPage";
		////////////////////////////

		$vSqlResCA = $this -> GetAdvByDate($iIDClassifiedsSubs, $sqlLimit, TRUE);

		while( $aSqlResStrCA = mysql_fetch_assoc($vSqlResCA) ) {
			$sRetHtml .= $this -> ComposeResultStringAdv($aSqlResStrCA);
		}

		if ($bTabledView==TRUE) {
			$sSql = "SELECT `Classifieds`.`ID` AS 'ClassifiedsID', `Classifieds`.`Name`, `Classifieds`.`CEntryUri` , `ClassifiedsSubs`.`ID` AS 'ClassifiedsSubsID', `ClassifiedsSubs`.`NameSub` , `ClassifiedsSubs`.`Description` 
				FROM `Classifieds` 
				INNER JOIN `ClassifiedsSubs` ON ( `Classifieds`.`ID` = `ClassifiedsSubs`.`IDClassified` ) 
				WHERE `ClassifiedsSubs`.`ID` = {$iIDClassifiedsSubs}
				LIMIT 1";
			$aSubcatRes = db_arr($sSql);
			$sCaption = "<div class=\"fl\">{$aSubcatRes['Name']} -> {$aSubcatRes['NameSub']}</div>\n";

			$sDesc = "<div class=\"cls_result_row\">{$aSubcatRes['Description']}</div>";

			$sHomeLink = ($this->bUseFriendlyLinks && $this->bAdminMode == false) ? $site['url'].'ads/' : "{$this->sCurrBrowsedFile}?Browse=1";
			$sCategLink = ($this->bUseFriendlyLinks && $this->bAdminMode == false) ? $site['url'].'ads/cat/'.$aSubcatRes['CEntryUri'] : "{$this->sCurrBrowsedFile}?bClassifiedID={$aSubcatRes['ClassifiedsID']}";

			$sBrowseAllAds = _t('_Browse All Ads');
			$sBreadCrumbs = <<<EOF
<div class="breadcrumbs">
<a href="{$site['url']}">{$site['title']}</a>
/
<a href="{$sHomeLink}">{$sBrowseAllAds}</a>
/
<a href="{$sCategLink}">{$aSubcatRes['Name']}</a>
/
<span class="active_link">{$aSubcatRes['NameSub']}</span>
</div>
EOF;

		///////////////////////////
		if ($this->bUseFriendlyLinks==false || $this -> bAdminMode==true) { //old variant
			if( $iTotalPages > 1)
			{
				$sRequest = $_SERVER['PHP_SELF'] . '?';
				$aFields = array( 'bClassifiedID', 'bSubClassifiedID', 'catUri', 'scatUri' );
				
				foreach( $aFields as $field )
					if( isset( $_GET[$field] ) )
						$sRequest .= "&amp;{$field}=" . htmlentities( process_pass_data( $_GET[$field] ) );
				
				$pagination = '<div style="text-align: center; position: relative;">'._t("_Results per page").':
						<select name="per_page" onchange="window.location=\'' . $sRequest . '&amp;per_page=\' + this.value;">
							<option value="10"' . ( $iPerPage == 10 ? ' selected="selected"' : '' ) . '>10</option>
							<option value="20"' . ( $iPerPage == 20 ? ' selected="selected"' : '' ) . '>20</option>
							<option value="50"' . ( $iPerPage == 50 ? ' selected="selected"' : '' ) . '>50</option>
							<option value="100"' . ( $iPerPage == 100 ? ' selected="selected"' : '' ) . '>100</option>
						</select></div>' .
					genPagination( $iTotalPages, $iCurPage, ( $sRequest . '&amp;page={page}&amp;per_page='.$iPerPage ) );
			}
			else
				$pagination = '';
		} else {
			if( $iTotalPages > 1)
			{
				$sRequest = 'ads/all/subcat/';
				$sPaginAddon = '/' . process_db_input($_GET['scatUri']);
				/*$aFields = array( 'bClassifiedID', 'bSubClassifiedID', 'catUri', 'scatUri' );
				
				foreach( $aFields as $field )
					if( isset( $_GET[$field] ) )
						$sRequest .= "&amp;{$field}=" . htmlentities( process_pass_data( $_GET[$field] ) );*/
				
				$pagination = '<div style="text-align: center; position: relative;">'._t("_Results per page").':
						<select name="per_page" onchange="window.location=\'' . $sRequest . '\' + this.value + \'/1'.$sPaginAddon.'\';">
							<option value="10"' . ( $iPerPage == 10 ? ' selected="selected"' : '' ) . '>10</option>
							<option value="20"' . ( $iPerPage == 20 ? ' selected="selected"' : '' ) . '>20</option>
							<option value="50"' . ( $iPerPage == 50 ? ' selected="selected"' : '' ) . '>50</option>
							<option value="100"' . ( $iPerPage == 100 ? ' selected="selected"' : '' ) . '>100</option>
						</select></div>' .
					genPagination( $iTotalPages, $iCurPage, ( $sRequest.$iPerPage . '/{page}' . $sPaginAddon  ) );
			}
			else
				$pagination = '';
		}
		///////////////////////////

			$sRetHtml .= '<div class="clear_both"></div>';
			$sTagsSect = DesignBoxContent ( $sBreadCrumbs, /*$sDesc.*/ $sRetHtml.$pagination, 1);
		}
		return $sTagsSect;
	}

	/**
	 * Generate a href to Back Link
	 *
	 * @return HTML presentation of data
	 */
	function PrintBackLink() {
		$sHomeLink = ($this->bUseFriendlyLinks && $this->bAdminMode == false) ? $site['url'].'ads/' : "{$this->sCurrBrowsedFile}?Browse=1";

		$sReturnBackC = _t('_Return Back');
		$sRetHtml = <<<EOF
<div>
	<b>
		<a href="{$sHomeLink}">{$sReturnBackC}</a>
	</b>
</div>
EOF;
		return $sRetHtml;
	}

	/**
	 * Generate a href link to Back to some Advertisement
	 *
	 * param $iAdvertisementID
	 * @return HTML presentation of data
	 */
	function PrintBackLink2Adv($iAdvertisementID) {
		$sReturnBackC = _t('_Return Back');
		$sGenUrl = $this->genUrl($iAdvertisementID, '', 'entry', true);
		//{$this->sCurrBrowsedFile}?ShowAdvertisementID={$iAdvertisementID}
		$sRetHtml = <<<EOF
<div>
	<b>
		<a href="{$sGenUrl}">{$sReturnBackC}</a>
	</b>
</div>
EOF;
		return $sRetHtml;
	}

	/**
	 * Generate array of Classified in lists doubled form
	 *
	 * @return HTML presentation of data
	 */
	function PrintClassifieds() {
		global $site;

		$sRetHtml = '';

		$vSqlRes = $this -> GetDataOfCls();
		if (mysql_affected_rows()==-1)
			return $this -> GetSQLError("");

		$iCounter = 0;
		while( $aSqlResStr = mysql_fetch_assoc($vSqlRes) ) {
			$iID = $aSqlResStr['ID'];
			$sCatName = $aSqlResStr['Name'];
			$sCatUri = $aSqlResStr['CEntryUri'];

			$sCategLink = ($this->bUseFriendlyLinks && $this->bAdminMode == false) ? $site['url'].'ads/cat/'.$sCatUri : "{$this->sCurrBrowsedFile}?bClassifiedID={$iID}";

			$sHref1 = "<a href=\"{$sCategLink}\">\n";
			$sqlCountCatRec = "
				SELECT COUNT(`ClassifiedsAdvertisements`.`ID`) AS 'Count'
				FROM `Classifieds` INNER JOIN `ClassifiedsSubs`
				ON (`Classifieds`.`ID` = `ClassifiedsSubs`.`IDClassified`)
				INNER JOIN `ClassifiedsAdvertisements`
				ON (`ClassifiedsSubs`.`ID` = `ClassifiedsAdvertisements`.`IDClassifiedsSubs`)
				WHERE `Classifieds`.`ID` = '{$aSqlResStr['ID']}'
				AND DATE_ADD(`ClassifiedsAdvertisements`.`DateTime`, INTERVAL `ClassifiedsAdvertisements`.`LifeTime` DAY) > NOW()";

			$aCountCatRes = db_arr($sqlCountCatRec);
			$sCnt =  ( $aCountCatRes['Count'] > 0 ) ? " ({$aCountCatRes['Count']})" : '';
			$sHref2 = "</a>";

			$sOrderBy = (getParam('enable_classifieds_sort') == 'on') ? 'ORDER BY `ClassifiedsSubs`.`NameSub` ASC' : '' ;
			$sQuerySubs = "SELECT * FROM `ClassifiedsSubs` WHERE `IDClassified` = {$aSqlResStr['ID']} {$sOrderBy}";
			$sqlResSubs = db_res ($sQuerySubs);
			if (mysql_affected_rows()==-1) {
				return $this -> GetSQLError($sQuerySubs);
			}
			$sSubsHtml = '';
			while( $aSqlResSubsStr = mysql_fetch_assoc($sqlResSubs) ) {
				$iSubID = $aSqlResSubsStr['ID'];
				$sSqlCountRecSub = "
					SELECT COUNT(`ID`) AS 'Count'
					FROM `ClassifiedsAdvertisements`
					WHERE `IDClassifiedsSubs`='{$aSqlResSubsStr['ID']}'
					AND DATE_ADD(`ClassifiedsAdvertisements`.`DateTime`, INTERVAL `ClassifiedsAdvertisements`.`LifeTime` DAY) > NOW()";
				$sCountResSub = db_arr($sSqlCountRecSub);
				$sNameSubUp = ucwords($aSqlResSubsStr['NameSub']);
				$sCntSub =  ( $sCountResSub['Count'] > 0 ) ? " ({$sCountResSub['Count']})" : '';
				$sSCategLink = ($this->bUseFriendlyLinks) ? $site['url'].'ads/subcat/'.$aSqlResSubsStr['SEntryUri'] : "{$this->sCurrBrowsedFile}?bSubClassifiedID={$iSubID}";

				$sSubsHtml .= <<<EOF
<div class="fs_13">
	<a href="{$sSCategLink}">
		{$sNameSubUp}
	</a>
	{$sCntSub}
</div>
EOF;
			}
			$sCaption = $sHref1.$sCatName.$sHref2.$sCnt;
			$iCounter++;
			$sMargin = '';
			$sDiv1 = '';
			$sDiv2 = '';
			$sDivider = '';
			if (($iCounter % 3) == 1) {
				$sDiv1 = '<div style="width:100%;margin-bottom:10px;">';
			}
			if (($iCounter % 4) == 0) {
				$sDiv2 = '</div>';
				$sDivider = '<div class="clear_both"></div>';
			}
			if (($iCounter % 4) != 0) {
				$sMargin = 'margin-right:12px;';
			}

			//$sRetHtml .= $this->DecorateAsTable2($sCaption, $sSubsHtml, $sMargin) . $sDivider;
			$sRetHtml .= "<div class=\"disignBoxFirstABlock\" style=\"{$sMargin}\">" . DesignBoxContent($sCaption, $sSubsHtml, 1) . '</div>' . $sDivider;
		}
		return $sRetHtml;
	}

	/**
	 * Generate Filter form with ability of searching by Category, Country and keyword (in Subject and Message)
	 *
	 * @return HTML presentation of form
	 */
	function PrintFilterForm($iClassifiedID = -1, $iSubClassifiedID = -1) {
		global $aPreValues;

		$sAnyC = _t('_Any');
		$iClassifiedID = (isset( $_REQUEST['FilterCat'] )) ? (int)process_db_input( $_REQUEST['FilterCat'] ) : $iClassifiedID;
		$iSubClassifiedID = (isset( $_REQUEST['FilterSubCat'] )) ? (int)process_db_input( $_REQUEST['FilterSubCat'] ) : $iSubClassifiedID;
		$sCountry = process_db_input( $_REQUEST['FilterCountry'] );
		$sKeywords = process_db_input( $_REQUEST['FilterKeywords'] );
		$sCustomFieldCaption1 = process_db_input( $_REQUEST['CustomFieldCaption1'] );
		$sCustomFieldCaption2 = process_db_input( $_REQUEST['CustomFieldCaption2'] );
		if (ereg ("([0-9]+)", process_db_input( $_REQUEST['CustomFieldValue1'] ), $aRegs)) {
			$sCustomFieldValue1 = $aRegs[1];
		}
		if (ereg ("([0-9]+)", process_db_input( $_REQUEST['CustomFieldValue2'] ), $aRegs)) {
			$sCustomFieldValue2 = $aRegs[1];
		}

		//$sCustomFieldValue2 = process_db_input( $_REQUEST['CustomFieldValue2'] );
		$sSubDspStyle = ($sCategorySub!="")?'':'none';

		$sCategoriesC = _t('_Categories');
		$sCountryC = _t('_Country');
		$sKeywordsC = _t('_Keywords');
		$sApplyC = _t('_Apply');
		$sResetC = _t('_Reset');
		$sFilterC = _t('_Filter');

		$sClassifiedsOptions = '';
		$vSqlRes = $this -> GetDataOfCls();
		if (mysql_affected_rows()==-1)
			return $this -> GetSQLError("");

		while( $aSqlResStr = mysql_fetch_assoc($vSqlRes) ) {
			$sClassifiedsOptions .= "<option value=\"{$aSqlResStr['ID']}\"".(($aSqlResStr['ID']==$iClassifiedID)?" selected":'').">{$aSqlResStr['Name']}</option>\n";
		}

		/*$sCountryInfos = '';
		$sQueryC = "SELECT `ISO2` AS 'ID', `Country` AS 'Name' FROM `Countries`";

		$vSqlResC = db_res ($sQueryC);
		if (mysql_affected_rows()==-1)
			return $this -> GetSQLError($sQueryC);

		while( $sqlResStrC = mysql_fetch_assoc($vSqlResC) ) {
			$sCountryInfos .= "<option value=\"{$sqlResStrC['ID']}\"".(($sqlResStrC['ID']==$sCountry)?" selected":'').">{$sqlResStrC['Name']}</option>\n";
		}*/

		$sCountryOptions = '';
		$sSelCountry = $sCountry;
		foreach ( $aPreValues['Country'] as $key => $value ) {
			$sCountrySelected = ( $sSelCountry == $key ) ? 'selected="selected"' : '';
			$sCountryOptions .= "<option value=\"{$key}\" ". $sCountrySelected ." >". _t($value['LKey']) ."</option>";
		}

		$sCF1DispStyle = ($sCustomFieldCaption1!="")?'':'none';
		$sCF2DispStyle = ($sCustomFieldCaption2!="")?'':'none';
		$sKeywordsStr = (($sKeywords!='')?"{$sKeywords}":'');
		$sCateg = '';
		$sSubCateg = '';
		if ($iClassifiedID==-1 AND $iSubClassifiedID==-1) {
			$sOnChange = ($iClassifiedID==-1) ? "onChange=\"UpdateListCommon('ReloadClassifiedsAndCustomsFields', 'FilterSubCat', 'IDClassified', this.value, 'CustomFieldCaption1', 'CustomFieldCaption2', 'unit');\"" : "";
			$sCateg = <<<EOF
<td class="w50_fs8">
	<b>{$sCategoriesC}:</b>
	<div>
		<select name="FilterCat" id="FilterCat" {$sOnChange} style="width:250px;">
			<option value="-1">VIEW ALL</option>
			{$sClassifiedsOptions}
		</select>
	</div>
</td>
EOF;

			$sSubCateg = <<<EOF
<tr id="tr0">
	<td class="w50_fs8">
		<div>
			<select name="FilterSubCat" id="FilterSubCat" style="display:{$sSubDspStyle};">
			</select>
			<input id="unit" type="text" value="" size="3" maxlength="8" style="display:none;" />
		</div>
	</td>
</tr>
EOF;
		}

		if ($iClassifiedID != -1) {
			$sCateg .= '<input type="hidden" name="FilterCat" value="'.$iClassifiedID.'" />'; $sSubCateg='';
		}
		if ($iSubClassifiedID != -1) {
			$sCateg .= '<input type="hidden" name="FilterSubCat" value="'.$iSubClassifiedID.'" />'; $sSubCateg='';
		}

		$sRetHtml = <<<EOF
<form action="{$this -> sCurrBrowsedFile}" method="get" name="filter_form" OnSubmit="UpdateFieldByInnerHtml('CustomFieldCaption_1','CustomFieldCaption1');UpdateFieldByInnerHtml('CustomFieldCaption_2','CustomFieldCaption2');return true;">
	<table style="margin-bottom:10px;">
		<tr>
			{$sCateg}
			<td class="w50_fs8">
				<b>{$sCountryC}:</b>
				<div>
					<select name="FilterCountry" id="FilterCountry">
						<option value="-1">{$sAnyC}</option>
						{$sCountryOptions}
					</select>
				</div>
			</td>
		</tr>
		{$sSubCateg}
		<tr id="tr1" style="display:{$sCF1DispStyle};height:30px;">
			<td class="w50_fs8" id="CustomFieldCaption1">{$sCustomFieldCaption1}</td>
			<td class="w50_fs8"><input type="text" name="CustomFieldValue1" value="{$sCustomFieldValue1}" /></td>
		</tr>
		<tr id="tr2" style="display:{$sCF2DispStyle};height:30px;">
			<td class="w50_fs8" id="CustomFieldCaption2">{$sCustomFieldCaption2}</td>
			<td class="w50_fs8"><input type="text" name="CustomFieldValue2" value="{$sCustomFieldValue2}" /></td>
		</tr>
		<tr>
			<td class="w50_fs8">
				<b>{$sKeywordsC}:</b>
				<div>
					<input type="text" value="{$sKeywordsStr}" id="FilterKeywords" name="FilterKeywords"/>
				</div>
			</td>
			<td class="w50_fs8">
				<div class="fr">
					<input type="hidden" value="" id="CustomFieldCaption_1" name="CustomFieldCaption1" />
					<input type="hidden" value="" id="CustomFieldCaption_2" name="CustomFieldCaption2" />
					<input type="hidden" name="action" value="3"/>
					<input type="submit" class="cur_pnt" value="{$sApplyC} {$sFilterC}" name="butAction"/>
					<input type="button" class="cur_pnt" value="{$sResetC} {$sFilterC}" name="action" onclick="reset(); FilterReset(); return false;" />
				</div>
			</td>
		</tr>
	</table>
</form>
EOF;
		return $sRetHtml;
	}

	/**
	 * Generate array of filtered Advertisements
	 *
	 * @return HTML presentation of data
	 */
	function PrintFilteredAllAdvertisements() {
		global $site;
		global $aPreValues;
		$sRetHtml = '';

		$sCategory = (int)process_db_input( $_REQUEST['FilterCat'] );
		$sSubCategory = (int)process_db_input( $_REQUEST['FilterSubCat'] );
		$sCountry = process_db_input( $_REQUEST['FilterCountry'] );
		$sKeywords = process_db_input( $_REQUEST['FilterKeywords'] );

		if ($sSubCategory<=0) {
			return MsgBox(_t('SubCategory is required'));
		}

		if (ereg ("([0-9]+)", process_db_input( $_REQUEST['CustomFieldValue1'] ), $aRegs)) {
			$sCustomFieldValue1 = $aRegs[1];
		}
		if (ereg ("([0-9]+)", process_db_input( $_REQUEST['CustomFieldValue2'] ), $aRegs)) {
			$sCustomFieldValue2 = $aRegs[1];
		}

		$sCustomFieldCaption1 = process_db_input( $_REQUEST['CustomFieldCaption1'] );
		$sCustomFieldCaption2 = process_db_input( $_REQUEST['CustomFieldCaption2'] );
		$sCustAction1 = "";
		$sCustAction2 = "";

		if ($sCustomFieldCaption1 != '' and $sCustomFieldCaption1!='NaN') $sCustAction1 = mb_substr(html_entity_decode($sCustomFieldCaption1), 0, 3);
		if ($sCustomFieldCaption2 != '' and $sCustomFieldCaption2!='NaN') $sCustAction2 = mb_substr(html_entity_decode($sCustomFieldCaption2), 0, 3);

		$aAllowActions = array('<', '>', '=');
		switch ($sCustAction1) {
			case 'Max': $sCustAction1='<'; break;
			case 'Min': $sCustAction1='>'; break;
			case 'Equ': $sCustAction1='='; break;
		}
		switch ($sCustAction2) {
			case 'Max': $sCustAction2='<'; break;
			case 'Min': $sCustAction2='>'; break;
			case 'Equ': $sCustAction2='='; break;
		}

		$sCategoryCat = ($sCategory > 0)?"`ClassifiedsSubs`.`IDClassified` = '{$sCategory}'" : '';
		$sSubCategoryCat = ($sSubCategory > 0)?"`ClassifiedsSubs`.`ID` = '{$sSubCategory}'": '';
		$sCountryCat = ($sCountry == '-1')?'':"`Profiles`.`Country`='{$sCountry}'";
		$sKeywordCat = ($sKeywords == '')?'':"(`ClassifiedsAdvertisements`.`Subject` LIKE '%{$sKeywords}%' OR `ClassifiedsAdvertisements`.`Message` LIKE '%{$sKeywords}%')";
		$sCustom1Cat = (in_array($sCustAction1, $aAllowActions)==false OR $sCustomFieldValue1 == '')?'':"CAST(`ClassifiedsAdvertisements`.`CustomFieldValue1`AS UNSIGNED) {$sCustAction1} {$sCustomFieldValue1}";
		$sCustom2Cat = (in_array($sCustAction2, $aAllowActions)==false OR $sCustomFieldValue2 == '')?'':"CAST(`ClassifiedsAdvertisements`.`CustomFieldValue2`AS UNSIGNED) {$sCustAction2} {$sCustomFieldValue2}";

		$aWheres = array($sCategoryCat, $sSubCategoryCat, $sCountryCat, $sKeywordCat, $sCustom1Cat, $sCustom2Cat);

		$sQuery = "
			FROM `ClassifiedsAdvertisements`
			INNER JOIN `ClassifiedsSubs`
			ON (`ClassifiedsAdvertisements`.`IDClassifiedsSubs`=`ClassifiedsSubs`.`ID`)
			INNER JOIN `Classifieds`
			ON (`Classifieds`.`ID`=`ClassifiedsSubs`.`IDClassified`)
			LEFT JOIN `Profiles`
			ON (`ClassifiedsAdvertisements`.`IDProfile`=`Profiles`.`ID`)
			WHERE
			DATE_ADD(`ClassifiedsAdvertisements`.`DateTime`, INTERVAL `ClassifiedsAdvertisements`.`LifeTime` DAY) > NOW()";

		foreach ($aWheres as $val) {
			$sQuery.=($val=='')?'':" AND ".$val;
		}
		$sQuery.=" ORDER BY `ClassifiedsAdvertisements`.`DateTime` ";

		////////////////////////////
		$sQueryCnt = "SELECT COUNT( `ClassifiedsAdvertisements`.`ID` ) AS 'Cnt' ".$sQuery;

		$aTotalNum = db_arr( $sQueryCnt );
		$iTotalNum = $aTotalNum['Cnt'];
		if( !$iTotalNum ) {
			return MsgBox(_t( '_Sorry, nothing found' ));
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
		$sqlLimit = "LIMIT $sLimitFrom, $iPerPage";
		////////////////////////////

		$sQuery = "SELECT `ClassifiedsAdvertisements`.*, `Classifieds`.`Unit`, (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`DateTime`)) AS 'sec' ".$sQuery.$sqlLimit;

		$vSqlRes = db_res ($sQuery);
		if (mysql_affected_rows()==-1)
			return $this -> GetSQLError($sQuery);
		if (mysql_affected_rows()>0) {

			$sFilteredC = _t('_Filtered');
			$sListingC = _t('_Listing');
			$sOutC = _t('_out');
			$sOfC = _t('_of');

			$sContStrs = '';
			while( $aSqlResStrA = mysql_fetch_assoc($vSqlRes) ) {
				$sContStrs .= $this -> ComposeResultStringAdv($aSqlResStrA);
			}

			///////////////////////////
			if( $iTotalPages > 1)
			{
				$sRequest = $_SERVER['PHP_SELF'] . '?';
				$aFields = array( 'action', 'FilterCat', 'FilterSubCat', 'FilterCountry', 'FilterKeywords', 'CustomFieldValue1', 'CustomFieldValue2', 'CustomFieldCaption1', 'CustomFieldCaption2' );
				
				foreach( $aFields as $field )
					if( isset( $_GET[$field] ) )
						$sRequest .= "&amp;{$field}=" . htmlentities( process_pass_data( $_GET[$field] ) );
				
				$pagination = '<div style="text-align: center; position: relative;">'._t("_Results per page").':
						<select name="per_page" onchange="window.location=\'' . $sRequest . '&amp;per_page=\' + this.value;">
							<option value="10"' . ( $iPerPage == 10 ? ' selected="selected"' : '' ) . '>10</option>
							<option value="20"' . ( $iPerPage == 20 ? ' selected="selected"' : '' ) . '>20</option>
							<option value="50"' . ( $iPerPage == 50 ? ' selected="selected"' : '' ) . '>50</option>
							<option value="100"' . ( $iPerPage == 100 ? ' selected="selected"' : '' ) . '>100</option>
						</select></div>' .
					genPagination( $iTotalPages, $iCurPage, ( $sRequest . '&amp;page={page}&amp;per_page='.$iPerPage ) );
			}
			else
				$pagination = '';
			///////////////////////////

			$sRetHtml .= DesignBoxContent ( _t('_SEARCH_RESULT_H'), $sContStrs. $pagination, 1);
		}
		else {
			$sRetHtml .= '<div>No listing..</div>';
		}
		return $sRetHtml;
	}

	/**
	 * Generate composed table element of Advertisement
	 *
	 * @param $aSqlResStrA	SQL result string of Advertisement
	 * @return HTML presentation of data
	 */
	function ComposeResultStringAdv($aSqlResStrA, $bVeryShortView=FALSE) {
		global $aPreValues;
		global $site;

		$sActivateC = _t('_Activate');
		$sDeleteC = _t('_Delete');
		$sEditC = _t('_Edit');
		$sSbjN = _t('_Subject');
		$sPostedByC = _t('_Posted by');
		$sDateC = _t('_Date');
		$sLocationC = _t('_Location');
		$sAdminLocalAreaC = _t('_AdminArea');
		$sAdminC = _t('_Admin');
		$sSureC = _t("_Are you sure");
		$sTimeAgo = _format_when($aSqlResStrA['sec']);

		$iID = $aSqlResStrA['ID'];

		$iIDProfile = $aSqlResStrA['IDProfile'];

		$sImageCode = $this -> getBigImageCode($aSqlResStrA['Media'], $iID);

		$sDelAbil='&nbsp;';

		if ($this -> bAdminMode == false) {
			//$iMemberID = (int)$_COOKIE['memberID'];
			$iMemberID = ($logged['member']) ? (int)$_COOKIE['memberID'] : 0;
			if (($this->bAdminMode==true OR $iMemberID!=0) AND $iIDProfile==$iMemberID) {//ability to delete Adv for owner
				$sDelAbil .= <<<EOF
<a href="{$this -> sCurrBrowsedFile}" onclick="if (confirm('{$sSureC}')) { UpdateField('DeleteAdvertisementID',{$iID});document.forms.command_delete_advertisement.submit(); } return false;">{$sDeleteC}</a>
<a href="{$this -> sCurrBrowsedFile}" onclick="UpdateField('EditAdvertisementID',{$iID}); document.forms.command_edit_advertisement.submit(); return false;">{$sEditC}</a>
EOF;
			}
		} elseif ($this -> bAdminMode==true) {
			//ability to Activate/Delete/Edite adv
			$sActivateAbil = ($aSqlResStrA['Status']=='active') ? '' : <<<EOF
<a href="{$this -> sCurrBrowsedFile}" onclick="UpdateField('ActivateAdvertisementID',{$iID});document.forms.command_activate_advertisement.submit(); return false;">{$sActivateC}</a>&nbsp;&nbsp;
EOF;

			$sDelAbil = <<<EOF
<div class="tar">
	{$sActivateAbil}
	<a href="{$this -> sCurrBrowsedFile}" onclick="if (confirm('{$sSureC}')) { UpdateField('DeleteAdvertisementID',{$iID});document.forms.command_delete_advertisement.submit(); } return false;">{$sDeleteC}</a>&nbsp;&nbsp;
	<a href="{$this -> sCurrBrowsedFile}" onclick="UpdateField('EditAdvertisementID',{$iID}); document.forms.command_edit_advertisement.submit(); return false;">{$sEditC}</a>
</div>
EOF;
		}

		$aProfileRes = $this -> GetProfileData($iIDProfile);
		$sPostedBy = ($iIDProfile==0) ? $sAdminC : "<a href=\"".getProfileLink($iIDProfile)."\">{$aProfileRes['NickName']}</a>\n";
		$sCountryName = ($aProfileRes['Country']=="")?$sAdminLocalAreaC:_t($aPreValues['Country'][ $aProfileRes['Country'] ]['LKey'] );
		$sCountryPic = ($aProfileRes['Country']=='')?'':' <img alt="'.$aProfileRes['Country'].'" src="'.($site['flags'].strtolower($aProfileRes['Country'])).'.gif"/>';

		$sCustDetails = '';
		$sDetailsC = _t('_Details');
		if ($aSqlResStrA['CustomFieldValue1'] OR $aSqlResStrA['CustomFieldValue2']) {
			$sCustDetails .= "{$sDetailsC}:";
			$sCustDetails .= ($aSqlResStrA['CustomFieldValue1'])?" <div class=\"clr3\">{$aSqlResStrA['Unit']} {$aSqlResStrA['CustomFieldValue1']}</div>":'';
			$sCustDetails .= ($aSqlResStrA['CustomFieldValue1'] && $aSqlResStrA['CustomFieldValue2'])?' - ':'';
			$sCustDetails .= ($aSqlResStrA['CustomFieldValue2'])?" <div class=\"clr3\">{$aSqlResStrA['Unit']} {$aSqlResStrA['CustomFieldValue2']}</div>":'';
		}

		//$sDataStyle=($bVeryShortView)?'':' style="margin-bottom:10px;"';
		$sDataStyleWidth=($bVeryShortView)?' style="width:230px;"':'';

		$sSubjectBlock = ($bVeryShortView) ? '' : <<<EOF
<div class="cls_res_info_p">
	{$sPostedByC}: <div class="clr3">{$sPostedBy}</div>
</div>
EOF;

		$sPostedByBlock = ($bVeryShortView) ? '' : <<<EOF
<div class="cls_res_info_p">
	{$sPostedByC}: <div class="clr3">{$sPostedBy}</div>
</div>
EOF;

		$sSubjectBlockValue = $aSqlResStrA['Subject'];//($bVeryShortView) ? mb_substr($aSqlResStrA['Subject'], 0, 15)."..." : $aSqlResStrA['Subject'];

		$sActionsBlock = ($bVeryShortView) ? '' : <<<EOF
<div class="cls_res_info_p">
	{$sDelAbil}
</div>
EOF;
		$sDetailsBlock = ($bVeryShortView) ? '' : <<<EOF
<div class="cls_res_info_p">
	{$sCustDetails}
</div>
EOF;
		//{$sDataStyle}

		$sGenUrl = $this->genUrl($iID, $aSqlResStrA['EntryUri']);
		//{$this -> sCurrBrowsedFile}?ShowAdvertisementID={$iID}

		$sCBStyle = ($this->bAdminMode==true) ? 'float:left' : '';
		$sRetHtml = <<<EOF
<div class="cls_result_row" style="{$sCBStyle}">
	<div class="clear_both"></div>
	<div class="thumbnail_block"  style="float: left;">
		{$sImageCode}
	</div>
	<div class="cls_res_info_nowidth" {$sDataStyleWidth}>
		<div class="cls_res_info_p">
			<!-- {$sSbjN}: -->
			<a class="actions" href="{$sGenUrl}">
				{$sSubjectBlockValue}
			</a>
		</div>
		{$sPostedByBlock}
		<div class="cls_res_info_p">
			{$sDateC}: <div class="clr3">{$aSqlResStrA['DateTime']} ({$sTimeAgo})</div>
		</div>
		{$sLocationBlock}
		{$sDetailsBlock}
		{$sActionsBlock}
	</div>
	<div class="clear_both"></div>
</div>
EOF;
		return $sRetHtml;
	}

	/**
	 * Compose result into disignBoxFirstA class
	 *
	 * @param $sCaption	caption of Box
	 * @param $sValue		inner text of box
	 * @return HTML presentation of data
	 */
	function DecorateAsTable2($sCaption, $sValue, $sMargin) {
		$sDecTbl = <<<EOF
<div class="disignBoxFirstA" style="{$sMargin}">
	<div class="boxFirstHeader">{$sCaption}</div>
	<div class="boxContent">
		<div class="cls_result_wrapper">
			{$sValue}
		</div>
	</div>
</div>
EOF;
		return $sDecTbl;
	}

	/**
	 * Compose Form to managing with Classifieds, subs, and custom fields
	 *
	 * @return HTML presentation of data
	 */
	function PrintManageClassifiedsForm() {
		$vSqlResCls = $this -> GetDataOfCls();
		if (mysql_affected_rows()==-1)
			return $this -> GetSQLError("");

		$sClassifiedsOptions = '';
		while( $aSqlResStr = mysql_fetch_assoc($vSqlResCls) ) {
			$iID = $aSqlResStr['ID'];
			$sName = $aSqlResStr['Name'];
			$sClassifiedsOptions .= "<option value=\"{$iID}\">{$sName}</option>\n";
		}

		$sCategoriesC = _t('_Categories');
		$sSubCategoriesC = _t('_SubCategories');
		$sTitleC = _t('_Title');
		$sDescC = _t('_Desctiption');
		$sAddC = _t('_Add');
		$sAddThisC = _t('_Add this');
		$sDeleteC = _t('_Delete');
		$sCustomField1C = _t('_CustomField1');
		$sCustomField2C = _t('_CustomField2');
		$sNameC = _t('_Name');
		$sActionC = _t('_Action');
		$sApplyC = _t('_Apply');
		$sEqualC = _t('_equal');
		$sSmallerC = _t('_smaller');
		$sBiggerC = _t('_bigger');
		$sChooseC = _t('_choose');
		$sUnitC = _t('_Unit');

		$sRetHtml = <<<EOF
<form action="{$this -> sCurrBrowsedFile}" name="ManageClassifiedsForm" id="ManageClassifiedsForm" method="post">
	<table class="cls_100_cp" id="admin_managing">
		<tr class="vc">
			<td class="cls_tbl_left_t">{$sCategoriesC}:</td>
			<td class="cls_tbl_right_m">
				<div class="fl">
					<select name="FilterCat" id="FilterCat" onChange="UpdateListCommon('ReloadClassifiedsAndCustomsFields', 'SubClassified', 'IDClassified', this.value, 'customRow1', 'customRow2', 'unit');">
						<option value="-1">{$sChooseC}</option>
						<option value="-1">------------</option>
						{$sClassifiedsOptions}
					</select>
				</div>
				<div class="fl" id="fieldCadAdd">
					&nbsp;<a onclick="AddCatFields('fieldsCat', 'fieldCadAdd', 'fieldsCatDel'); return false;" href="{$this -> sCurrBrowsedFile}" id="addHref">{$sAddC}</a>&nbsp;&nbsp;&nbsp;
				</div>
				<div id="fieldsCat" style="display:none;">
					{$sTitleC}: <input type="text" name="name1" id="nameCat" value="" size="12" maxlength="20">
					{$sDescC}: <input type="text" name="description1" id="descCat" value="" size="12" maxlength="50">
					<a onclick="UpdateField('iAction','AddMainCategory');document.forms.ManageClassifiedsForm.submit(); return false;" id="AddCatHref" href="{$this -> sCurrBrowsedFile}">{$sAddC}</a>&nbsp;&nbsp;&nbsp;
				</div>
				<div id="fieldsCatDel" class="fl">
					<a onclick="UpdateField('iAction','DeleteMainCategory');document.forms.ManageClassifiedsForm.submit(); return false;" href="{$this -> sCurrBrowsedFile}">{$sDeleteC}</a>
				</div>
			</td>
		</tr>
		<tr class="vc" id="customRow1">
			<td class="cls_tbl_left_m">{$sCustomField1C}:</td>
			<td class="cls_tbl_right_m">{$sNameC}: 
				<input type="text" id="CustomName1" name="CustomName1" size="25">&nbsp;{$sActionC}: 
				<select size="1" id="CustomAction1" name="CustomAction1">
					<option value="-1"></option>
					<option value="=">{$sEqualC}</option>
					<option value="&gt;">{$sBiggerC}</option>
					<option value="&lt;">{$sSmallerC}</option>
				</select>
				<input type="button" value="{$sApplyC}" onClick="AdmTryApplyChanges('ApplyChanges', 1);">
				<input type="button" value="{$sDeleteC}" onClick="AdmTryApplyChanges('DeleteCustom', 1);">
			</td>
		</tr>
		<tr class="vc" id="customRow2">
			<td class="cls_tbl_left_m">{$sCustomField2C}:</td>
			<td class="cls_tbl_right_m">{$sNameC}: 
				<input type="text" id="CustomName2" name="CustomName2" size="25">&nbsp;{$sActionC}: 
				<select size="1" id="CustomAction2" name="CustomAction2">
					<option value="-1"></option>
					<option value="=">{$sEqualC}</option>
					<option value="&gt;">{$sBiggerC}</option>
					<option value="&lt;">{$sSmallerC}</option>
				</select>
				<input type="button" value="{$sApplyC}" onClick="AdmTryApplyChanges('ApplyChanges', 2);">
				<input type="button" value="{$sDeleteC}" onClick="AdmTryApplyChanges('DeleteCustom', 2);">
			</td>
		</tr>
		<tr class="vc">
			<td class="cls_tbl_left_m">{$sUnitC}:</td>
			<td class="cls_tbl_right_m">{$sNameC}: 
				<input id="unit" type="text" value="" size="3" maxlength="8" />
				<input type="button" value="{$sApplyC}" onClick="AdmTryApplyUnitChanges('ApplyUnitChanges');">
				<input type="button" value="{$sDeleteC}" onClick="AdmTryApplyUnitChanges('DeleteUnit');">
			</td>
		</tr>
		<tr class="vc">
			<td class="cls_tbl_left_t">{$sSubCategoriesC}:</td>
			<td class="cls_tbl_right_m">
				<div class="fl">
					<select name="SubClassified" id="SubClassified">
					</select>
				</div>
				<div class="fl" id="fieldSubCadAdd">
					<a onclick="AddCatFields('fieldsSubCat', 'fieldSubCadAdd', 'fieldsSubCatDel'); return false;" href="{$this -> sCurrBrowsedFile}">{$sAddC}</a>&nbsp;&nbsp;&nbsp;
				</div>
				<div id="fieldsSubCat" style="display:none;" class="fl">
					{$sTitleC}: <input type="text" name="name2" id="nameSubCat" value="" size="20" maxlength="128" />
					{$sDescC}: <input type="text" name="description2" id="descSubCat" value="" size="20" maxlength="150" />
					<a onclick="UpdateField('iAction','AddSubCategory');document.forms.ManageClassifiedsForm.submit(); return false;" id="AddSubCatHref" href="{$this -> sCurrBrowsedFile}">{$sAddC}</a>&nbsp;&nbsp;&nbsp;
				</div>
				<div id="fieldsSubCatDel" class="fl">
					<a onclick="UpdateField('iAction','DeleteSubCategory');document.forms.ManageClassifiedsForm.submit(); return false;" href="{$this -> sCurrBrowsedFile}">{$sDeleteC}</a>
				</div>
			</td>
		</tr>
	</table>
	<input type="hidden" name="iAction" id="iAction" value="">
</form>
EOF;
		return $sRetHtml;
	}

	/**
	 * Compose table with all New Advertisements
	 *
	 * @return HTML presentation of data
	 */
	function PrintModeratingTable() {
		global $site;
		$sRetHtml = '';

		$sQuery = "
			SELECT `ClassifiedsAdvertisements`.*,
			DATE_ADD(`ClassifiedsAdvertisements`.`DateTime`, INTERVAL `ClassifiedsAdvertisements`.`LifeTime` DAY) < NOW() AS 'IsOld',
			`Classifieds`.`Unit`, (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`DateTime`)) AS `sec`
			FROM `ClassifiedsAdvertisements`
			INNER JOIN `ClassifiedsSubs`
			ON (`ClassifiedsAdvertisements`.`IDClassifiedsSubs`=`ClassifiedsSubs`.`ID`)
			INNER JOIN `Classifieds`
			ON (`Classifieds`.`ID`=`ClassifiedsSubs`.`IDClassified`)
			WHERE `Status`='new'
		";
		$vSqlResCA = db_res ($sQuery);
		$sRetHtml .= '<form action="'.$this->sCurrBrowsedFile.'" method="post" name="commands_couple_advertisements" id="commands_couple_advertisements">';
		while( $aSqlResStr = mysql_fetch_assoc($vSqlResCA) ) {
			$sRetHtml .= <<<EOF
<div class="browseCheckbox">
	<input type="checkbox" value="{$aSqlResStr['ID']}" name="Check[]"/>
</div>
EOF;
			$sRetHtml .= $this -> ComposeResultStringAdv($aSqlResStr);
			$sRetHtml .= '<div class="clear_both"></div>';
		}

		$sRetHtml .= <<<EOF
<a onclick="javascript: checkAll('commands_couple_advertisements', '', true); return false;" href="{$this->sCurrBrowsedFile}">Check All</a>
/
<a onclick="javascript: checkAll('commands_couple_advertisements', '', false); return false;" href="{$this->sCurrBrowsedFile}">Uncheck All</a>
&nbsp;&nbsp;<input type="submit" value="Delete" name="DeleteSelected"/>
&nbsp;&nbsp;<input type="submit" value="Approve" name="ApproveSelected"/>
</form>
EOF;

		return $sRetHtml;
	}

	/**
	 * Compose Edit form for Advertisement
	 *
	 * @param $iEditAdvertisementID	ID of edited Advertisement
	 * @return HTML presentation of data
	 */
	function PrintEditForm($iEditAdvertisementID, $arrErr=NULL) {
		$aSqlResAdv = $this -> GetAdvertisementData($iEditAdvertisementID);

		$vSqlResCls = $this -> GetDataOfCls();
		if (mysql_affected_rows()==-1)
			return $this -> GetSQLError("");

		$sClassifiedsOptions = '';
		while( $aSqlResStr = mysql_fetch_assoc($vSqlResCls) ) {
			$sClassifiedsOptions .= "\n<option value=\"{$aSqlResStr['ID']}\">{$aSqlResStr['Name']}</option>";
		}

		$sSbjC = _t('_Subject');
		$sOrC = _t('_or');
		$sChangeC = _t('_Change');
		$sCategoryC = _t('_Category');
		$sSubCategoryC = _t('_SubCategories');
		$sMsgC = _t('_Message');
		$sCommitC = _t('_Apply Changes');
		$sTagsC = _t('_Tags');
		$sPhotosC = _t('_photos');

		$sMSGstyle = ($arrErr['Message'] ? 'block' : 'none');
		$sSBJstyle = ($arrErr['Subject'] ? 'block' : 'none');
		$sSBJmsg = ($arrErr['Subject'] ? _t( '_'.$arrErr['Subject'] ) : '' );
		$sMSGmsg = ($arrErr['Message'] ? _t( '_'.$arrErr['Message'] ) : '' );

		//$sCategory = (int)process_db_input( $_POST['FilterCat'] );
		//$sCategorySub = process_db_input( $_POST['FilterCatSub'] );
		//$sKeywords = process_db_input( $_POST['FilterKeywords'] );
		//$sCustomFieldCaption1 = process_db_input( $_POST['ad_CustomFieldCaption1'] );
		//$sCustomFieldCaption2 = process_db_input( $_POST['ad_CustomFieldCaption2'] );
		$sSubject = $this->process_html_db_input($_POST['subject']);
		$sMessage = $this->process_html_db_input($_POST['message']);
		$sTags = process_pass_data($_POST['Tags']);
		//$iLifeTime = (int)process_db_input( $_POST['lifetime'] );

		$sSubject = ($sSubject=="") ? $aSqlResAdv['Subject'] : $sSubject;
		$sMessage = ($sMessage=="") ? $aSqlResAdv['Message'] : $sMessage;
		$sTags = ($sTags=="") ? $aSqlResAdv['Tags'] : $sTags;

		/////photos/////////
		$sImg = $this->getImageManagingCode($aSqlResAdv['Media']);
		///////////////////

		$sCatHndlVal = (isset($arrErr)) ? $_REQUEST['Classified'] : $aSqlResAdv['CatID'];
		$sSubCatHndlVal = (isset($arrErr)) ? $_REQUEST['SubClassified'] : $aSqlResAdv['SubID'];
		$sScriptHandle = <<<EOF
<script type="text/javascript">
	addEvent( window, 'load', function(){ UpdateListCommon('ReloadClassifieds','SubClassified','IDClassified',{$sCatHndlVal}); } );
	addEvent( window, 'load', function(){ UpdateField('Classified',{$sCatHndlVal}); } );
	addEvent( window, 'load', function(){ UpdateField('SubClassified',{$sSubCatHndlVal}); } );
</script>
EOF;

		$sRetHtml = <<<EOF
<form action="{$this -> sCurrBrowsedFile}" name="EditForm" method="post" enctype="multipart/form-data">
	<table class="cls_100_cp" cellspacing="10">
		<tr class="vc">
			<td class="cls_tbl_left_t">{$sCategoryC}:</td>
			<td class="cls_tbl_right_m">
				<select name="Classified" id="Classified" onChange="UpdateListCommon('ReloadClassifiedsAndCustomsFields','SubClassified','IDClassified',this.value,'ad_CustomFieldCaption1','ad_CustomFieldCaption2');">
					<option value="-1">&nbsp;</option>{$sClassifiedsOptions}
				</select>
				<select name="SubClassified" id="SubClassified">
				</select>
				{$sScriptHandle}
			</td>
		</tr>
		<tr class="vc">
			<td class="cls_tbl_left_t" id="ad_CustomFieldCaption1" name="ad_CustomFieldCaption1">{$aSqlResAdv['CustomFieldName1']}{$aSqlResAdv['CustomAction1']}</td>
			<td class="cls_tbl_right_m">
				<input type="text" name="CustomFieldValue1" value="{$aSqlResAdv['CustomFieldValue1']}" size="20" maxlength="20" />
			</td>
		</tr>
		<tr class="vc">
			<td class="cls_tbl_left_t" id="ad_CustomFieldCaption2" name="ad_CustomFieldCaption2">{$aSqlResAdv['CustomFieldName2']}{$aSqlResAdv['CustomAction2']}</td>
			<td class="cls_tbl_right_m">
				<input type="text" name="CustomFieldValue2" value="{$aSqlResAdv['CustomFieldValue2']}" size="20" maxlength="20" />
			</td>
		</tr>
		<tr class="vc">
			<td class="cls_tbl_left_t">{$sSbjC}:</td>
			<td class="cls_tbl_right_m">
				<div class="edit_error" style="display:{$sSBJstyle}">
					{$sSBJmsg}
				</div>
				<input type="text" name="subject" value="{$sSubject}" size="60" maxlength="60" />
			</td>
		</tr>
		<tr class="vc">
			<td class="cls_tbl_left_t">{$sTagsC}:</td>
			<td class="cls_tbl_right_m">
				<input type="text" name="Tags" value="{$sTags}" size="60" maxlength="60" />
			</td>
		</tr>
		<tr class="vc">
			<td class="cls_tbl_left_t">{$sMsgC}:</td>
			<td class="cls_tbl_right_m">
				<div class="edit_error" style="display:{$sMSGstyle}">
					{$sMSGmsg}
				</div>
				<textarea name="message" rows="20" cols="60" class="classfiedsTextArea">{$sMessage}</textarea>
			</td>
		</tr>
		{$sImg}
		<tr class="vc">
			<td class="cls_tbl_left_t"></td>
			<td class="cls_tbl_right_m">
				<input type="hidden" name="UpdatedAdvertisementID" value="{$iEditAdvertisementID}" />
				<input type="submit" name="UpdateAdvertisement" value="{$sCommitC}" />
			</td>
		</tr>
	</table>
</form>
EOF;
		return $sRetHtml;
	}

	function GenReportSubmitForm($iCommentID) {
		global $member;
		global $site;

		if ($iCommentID ) {
			$iClsID = $_REQUEST['clsID'];
			$sAddr  = '<input type="hidden" name="email" value="'.$site['email_notify'].'">';
			$sCode  = '<div class="mediaInfo">';
			$sCode .= '<form name="submitAction" method="post" action="'.$_SERVER['PHP_SELF'].'">';
			$sCode .= '<input type="hidden" name="commentID" value="'.$iCommentID.'">';
			$sCode .= '<input type="hidden" name="clsID" value="'.$iClsID.'">';
			$sCode .= '<input type="hidden" name="action" value="post_report">';
			$sCode .= '<div>'._t("_Message text").'</div>';
			$sCode .= '<div><textarea cols="30" rows="10" name="messageText"></textarea></div>';
			$sCode .= '<div><input type="submit" size="15" name="send" value="Send">';
			$sCode .= '<input type="reset" size="15" name="send" value="Reset"></div>';
			$sCode .= '</form>';
			$sCode .= '</div>';
		}
		return $sCode;
	}

	function ActionReportSubmit() {
		global $site;

		$iMemberID = (int)$_COOKIE['memberID'];
		$iClsID = $_REQUEST['clsID'];
		$iCommID = $_REQUEST['commentID'];
		$aUser = $this->GetProfileData($iMemberID);

		$sMailHeader		= "From: {$site['title']} <{$site['email_notify']}>";
		$sMailParameters	= "-f{$site['email_notify']}";

		$sMessage = $this->process_html_db_input($_REQUEST['messageText']);

		$sMailHeader = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=UTF-8\r\n" . $sMailHeader;
		$sMailSubject = $aUser['NickName'].' bad comment report';

		$sGenUrl = $this->genUrl($iClsID, '', 'entry', true);

		//{$site['url']}classifieds.php?ShowAdvertisementID={$iClsID}
		 $sMailBody    = "Hello,\n
					{$aUser['NickName']} bad classified comment (comm num {$iCommID}): <a href=\"{$sGenUrl}\">See it</a>\n
					{$sMessage}\n
					Regards";

		$sMail = $site['email_notify'];

		/*$iSendingResult = mail( $sMail, $sMailSubject, nl2br($sMailBody), $sMailHeader, $sMailParameters );

		if ($iSendingResult)
		{
			$sCode = '<div class="mediaInfo">'._t("_File info was sent").'</div>';
		}
		return MsgBox($sCode);*/


		if (sendMail( $sMail, sMailSubject, nl2br($sMailBody), '', '', 'html')) {
			$sCode = '<div class="mediaInfo">'._t("_File info was sent").'</div>';
			return MsgBox($sCode);
		}
	}

	function CenteredActionsBlock($sPicElement, $sHrefElement) {
		$sResElement = <<<EOF
<span style="vertical-align: middle;">{$sPicElement}</span>
<span>{$sHrefElement}</span>
EOF;
		return $sResElement;
	}

	/**
	 * Compose Array of posted data before validating (post ad or edit)
	 *
	 * @return Array
	 */
	function FillPostAdvertismentArrByPostValues() {
		$iSubClassifiedID = (int)$_POST['SubClassified'];
		$iMemberID = (int)$_COOKIE['memberID'];
		$sSubject = $this->process_html_db_input($_POST['subject']);
		$sMessage = process_db_input( $_POST['message'] );
		if (ereg ("([0-9]+)", process_db_input( $_POST['CustomFieldValue1'] ), $aRegs)) {
			$sCustomFieldValue1 = $aRegs[1];
		}
		if (ereg ("([0-9]+)", process_db_input( $_POST['CustomFieldValue2'] ), $aRegs)) {
			$sCustomFieldValue2 = $aRegs[1];
		}

		$iLifeTime = process_db_input( $_POST['lifetime'] );
		$arr = array('SubClassified' => $iSubClassifiedID, 'membID' => $iMemberID, 'Subject' => $sSubject, 'Message' => $sMessage,
						'custVal1' => $sCustomFieldValue1, 'custVal2' => $sCustomFieldValue2, 'Life Time' => $iLifeTime);
		return $arr;
	}

	/**
	 * Compose Array of errors during filling (validating)
	 *
	 * @param $arrAdv	Input Array with data
	 * @param $bEditMode	like a simple mode, not all fields are tested
	 * @return Array with errors
	 */
	function checkGroupErrors( $arrAdv, $bEditMode=FALSE ) {
		$arrErr = array();
		foreach( $arrAdv as $sFieldName => $sFieldValue ) {
			switch( $sFieldName ) {
				case 'SubClassified':
					if( $sFieldValue < 1 AND $bEditMode==FALSE )
						$arrErr[ $sFieldName ] = "{$sFieldName} is required";
				break;
				case 'Subject':
					if( !strlen($sFieldValue) )
						$arrErr[ $sFieldName ] = "{$sFieldName} is required";
				break;
				case 'Message':
					if( strlen($sFieldValue) < 50 )
						$arrErr[ $sFieldName ] = "{$sFieldName} must be 50 symbols at least";
				break;
				case 'Life Time':
					if( $sFieldValue < 1 AND $bEditMode==FALSE )
						$arrErr[ $sFieldName ] = "{$sFieldName} must be positive";
				break;
			}
		}
		return $arrErr;
	}

	/**
	 * Compose result of searching Advertisements by Tag
	 *
	 * @param $sTag	selected tag string
	 * @return HTML result
	 */
	function PrintAdvertisementsByTag($sTag) {
		global $site;
		$sRetHtml='';
		$sTag = addslashes(trim(strtolower($sTag)));
		$sTagResultC = _t('_Tags') . _t('_Search') . _t('_Results');
		$sBrowseAllAds = _t('_Browse All Ads');

		$sHomeLink = ($this->bUseFriendlyLinks) ? $site['url'].'ads/' : "{$this->sCurrBrowsedFile}?Browse=1";

		$sBreadCrumbs = <<<EOF
<div class="breadcrumbs">
<a href="{$site['url']}">{$site['title']}</a>
/
<a href="{$sHomeLink}">{$sBrowseAllAds}</a>
/
{$sTagResultC}
</div>
EOF;
		$vSqlRes = $this -> GetAdvByTags();
		while( $aSqlResStr = mysql_fetch_assoc($vSqlRes) ) {
			$sTags = $aSqlResStr['Tags'];
			$aTags = array();
			$aTags = explode(",", $sTags);
			if (in_array($sTag, $aTags)) {
				$sRetHtml .= $this -> ComposeResultStringAdv($aSqlResStr);
			}
		}
		return $sBreadCrumbs.$sRetHtml;
	}

	/*
	*safe SQL functions
	*/

	/**
	 * Write SQL data into ClassifiedsAdvertisementsMedia
	 *
	 * @param $iMemberID	Member ID
	 * @param $sBaseName	Base Name of picture without extension
	 * @param $sExt		Extension of picture
	 * @return SQL result
	 */
	function InsertCAM($iMemberID, $sBaseName, $sExt) {
		$sQuery = "INSERT INTO `ClassifiedsAdvertisementsMedia` SET
					`MediaProfileID`='{$iMemberID}',
					`MediaType`='photo',
					`MediaFile`='{$sBaseName}{$sExt}',
					`MediaDate`=NOW()";
		$vSqlRes = db_res( $sQuery );
		return $vSqlRes;
	}

	/**
	 * Write SQL data into ClassifiedsAdvertisements
	 *
	 * @param $iMemberID	Member ID
	 * @param $iSubClassifiedID	SubClassified ID, where Adv was added
	 * @param $sSubject		Subject of Adv
	 * @param $sMessage		Message of Adv
	 * @param $sCustomFieldValue1 Custom Value 1
	 * @param $sCustomFieldValue2 Custom Value 2
	 * @param $iLifeTime		Life time of Adv, time in days how many showed Adv
	 * @param $sMedIds		ID`s of attached pictures
	 * @return SQL result
	 */
	function InsertCA($iMemberID, $iSubClassifiedID, $sSubject, $sMessage, $sCustomFieldValue1, $sCustomFieldValue2, $iLifeTime, $sMedIds, $sTags) {

		$sNewUri = uriGenerate($sSubject, 'ClassifiedsAdvertisements', 'EntryUri', 50);

		$sStatus = (getParam('autoApproval_Classifieds') == 'on') ? 'active' : 'new';
		$sQuery = "INSERT INTO `ClassifiedsAdvertisements` SET
					`IDProfile`='{$iMemberID}',
					`IDClassifiedsSubs`='{$iSubClassifiedID}',
					`DateTime`=NOW(),
					`Subject`='{$sSubject}',
					`EntryUri`='{$sNewUri}',
					`Message`='{$sMessage}',
					`Status` = '{$sStatus}',
					`CustomFieldValue1`={$sCustomFieldValue1},
					`CustomFieldValue2`={$sCustomFieldValue2},
					`LifeTime`={$iLifeTime},
					`Media`='{$sMedIds}',
					`Tags`='{$sTags}'
		";
		$vSqlRes = db_res( $sQuery );
		return $vSqlRes;
	}

	function ActionDeletePicture() {
		global $dir;

		$iMediaID = (int)$_REQUEST['DeletedPictureID'];
		$iEditAdvertisementID = (int)$_REQUEST['UpdatedAdvertisementID'];

		$sCheckPostSQL = "SELECT `IDProfile`
							FROM `ClassifiedsAdvertisements`
							WHERE `ID`={$iEditAdvertisementID}
						";
		$aAdvOwner = db_arr($sCheckPostSQL);
		$iAdvOwner = $aAdvOwner['IDProfile'];
		$iVisitorID = (int)$_COOKIE['memberID'];
		if (($iVisitorID == $iAdvOwner || $this->bAdminMode) && $iEditAdvertisementID > 0) {
			if ($this -> bAdminMode == FALSE) {
				$sRestrictRes = $this->RestrictAction($iVisitorID);
				if ($sRestrictRes != '') return $sRestrictRes;
				//if ($this->RestrictAction($iVisitorID)) return;
			}
			//1. get media array
			$aAdvData = $this->GetAdvertisementData($iEditAdvertisementID);
			$sMediaIDs = $aAdvData['Media'];

			if ($sMediaIDs != '') {
				$aChunks = preg_split ("/[,]+/", $sMediaIDs, -1, PREG_SPLIT_NO_EMPTY);

				//2. don`t get deleted element
				$aNewMediaIDs = array();
				foreach ( $aChunks as $iMedId ) {
					if ($iMedId != $iMediaID) $aNewMediaIDs[] = $iMedId;
				}

				//3. collect new array of nedia
				$sNewMedia = implode(",", $aNewMediaIDs);

				//4. update field Media in classifieds with new array of media
				$sUpdateSQL = "UPDATE `ClassifiedsAdvertisements` SET `Media` = '{$sNewMedia}' WHERE `ClassifiedsAdvertisements`.`ID` = {$iEditAdvertisementID} LIMIT 1";
				db_res( $sUpdateSQL );
				if (mysql_affected_rows() == 1) {//continue
					$sAdminCut2 = ($this->bAdminMode==false) ? "AND `MediaProfileID` = {$iVisitorID}" : "";
					$sQueryChunkFile = "
						SELECT `MediaFile` 
						FROM `ClassifiedsAdvertisementsMedia` 
						WHERE `MediaID` = {$iMediaID}
						{$sAdminCut2}
						LIMIT 1
					";
					//5. delete physycally file
					$aSqlResMediaName = db_assoc_arr( $sQueryChunkFile );
					$sMediaFileName = $aSqlResMediaName['MediaFile'];
					if ($sMediaFileName != '') {
						if (unlink ( $dir['root'].$this->sUploadDir . 'img_'.$sMediaFileName ) == FALSE) {
							$sRetHtml .= MsgBox(_t('_FAILED_TO_DELETE_PIC', $sMediaFileName));
						}
						if (unlink ( $dir['root'].$this->sUploadDir . 'thumb_'.$sMediaFileName ) == FALSE) {
							$sRetHtml .= MsgBox(_t('_FAILED_TO_DELETE_PIC', $sMediaFileName));
						}
						if (unlink ( $dir['root'].$this->sUploadDir . 'big_thumb_'.$sMediaFileName ) == FALSE) {
							$sRetHtml .= MsgBox(_t('_FAILED_TO_DELETE_PIC', $sMediaFileName));
						}
						if (unlink ( $dir['root'].$this->sUploadDir . 'icon_'.$sMediaFileName ) == FALSE) {
							$sRetHtml .= MsgBox(_t('_FAILED_TO_DELETE_PIC', $sMediaFileName));
						}
					}
					//6. delete record from table with media of Classifieds about deleted object
					$sQueryMediaID = "DELETE FROM `ClassifiedsAdvertisementsMedia` WHERE `MediaID` = {$iMediaID} AND `MediaProfileID` = {$iVisitorID} LIMIT 1";
					$aSqlResMediaID = db_res( $sQueryMediaID );
					if (mysql_affected_rows() == 1) {//continue
						return MsgBox(_t('_Photo successfully deleted'));
					}
				} else return MsgBox(_t('_Error Occured'));
			}
		} elseif($iVisitorID != $iAdvOwner) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}

	/**
	 * SQL Updating fields of Advertisement
	 *
	  * @param $iEditAdvertisementID	ID`s of editing Advertisement
	 * @return Text presentation of data
	 */
	function ActionUpdateAdvertisementID($iEditAdvertisementID) {
		$sCheckPostSQL = "SELECT `IDProfile`
							FROM `ClassifiedsAdvertisements`
							WHERE `ID`={$iEditAdvertisementID}
						";
		$aAdvOwner = db_arr($sCheckPostSQL);
		$iAdvOwner = $aAdvOwner['IDProfile'];
		$iVisitorID = (int)$_COOKIE['memberID'];
		if (($iVisitorID == $iAdvOwner || $this->bAdminMode) && $iEditAdvertisementID > 0) {
			if ($this -> bAdminMode == FALSE) {
				$sRestrictRes = $this->RestrictAction($iVisitorID);
				if ($sRestrictRes != '') return $sRestrictRes;
				//if ($this->RestrictAction($iVisitorID)) return;
			}

			require_once( BX_DIRECTORY_PATH_INC . 'tags.inc.php' );

			$sSuccUpd = _t("_SUCC_UPD_ADV");
			$sFailUpd = _t("_FAIL_UPD_ADV");
			$sCategoryID = process_db_input( $_POST['Classified'] );
			$sSubCategoryID = process_db_input( $_POST['SubClassified'] );
			$sCustomFieldValue1 = (int)$_POST['CustomFieldValue1'];
			$sCustomFieldValue2 = (int)$_POST['CustomFieldValue2'];
			$sTags = process_db_input($_POST['Tags']);
			$aTags = explodeTags($sTags);
			$sTags = implode(",", $aTags);
			$sSubject = $this->process_html_db_input($_POST['subject']);
			$sMessage = $this->process_html_db_input($_POST['message']);
			$changeCat = ($sCategoryID>0 AND $sSubCategoryID>0) ? "`IDClassifiedsSubs`='{$sSubCategoryID}', " : '';

			//1. get a new files and return string-array
			$sNewMedias = $this->parseUploadedFiles($iAdvOwner);

			//2. get current media datas from cls
			$aAdvData = $this->GetAdvertisementData($iEditAdvertisementID);
			$sMediaIDs = $aAdvData['Media'];
			//3. merge both
			$aOldChunks = preg_split ("/[,]+/", $sMediaIDs, -1, PREG_SPLIT_NO_EMPTY);
			$aNewChunks = preg_split ("/[,]+/", $sNewMedias, -1, PREG_SPLIT_NO_EMPTY);
			$aResultChunks = array_merge ($aNewChunks, $aOldChunks);
			$sResultChunks = implode(",", $aResultChunks);
			$sPicsAddSQL = (count($aNewChunks)>0) ? "`Media` = '{$sResultChunks}'," : '';

			//$sNewUri = uriGenerate($sSubject, 'ClassifiedsAdvertisements', 'EntryUri', 50);

			//4. update result
			$sQuery = "
				UPDATE `ClassifiedsAdvertisements` SET
				{$changeCat}
				`Subject`='{$sSubject}',
				`Message`='{$sMessage}',
				`CustomFieldValue1`={$sCustomFieldValue1},
				`CustomFieldValue2`={$sCustomFieldValue2},
				{$sPicsAddSQL}
				`Tags`='{$sTags}'
				WHERE `ID`={$iEditAdvertisementID}
			";
			$vSqlRes = db_res( $sQuery );
			$sRet = (mysql_affected_rows()>0) ? _t($sSuccUpd) : _t($sFailUpd);
			reparseObjTags( 'ad', $iEditAdvertisementID );
			$this->UseDefaultCF();
			return  MsgBox($sRet) . $this -> ActionPrintAdvertisement($iEditAdvertisementID);
		} elseif($iVisitorID != $iAdvOwner) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}

	/*function ActionEditComment() {
		return '';
		$iCommentID = (int)$_REQUEST['EditCommentID'];
		$iEditAdvCommID = (int)$_REQUEST['EAdvID'];
		$sCommSQL = "SELECT `IDProfile`
							FROM `ClsAdvComments`
							WHERE `ID`={$iCommentID}
						";
		$aCommOwner = db_arr($sCommSQL);
		$iCommOwner = $aCommOwner['IDProfile'];

		$sAdvCommSQL = "SELECT `IDProfile`
							FROM `ClassifiedsAdvertisements`
							WHERE `ID`={$iEditAdvCommID}
						";
		$aAdvOwner = db_arr($sAdvCommSQL);
		$iAdvOwner = $aAdvOwner['IDProfile'];
		$iVisitorID = (int)$_COOKIE['memberID'];
		if (($iVisitorID == $iAdvOwner || $iVisitorID == $iCommOwner || $this->bAdminMode) && $iCommentID > 0) {
			if ($this -> bAdminMode == FALSE) {
				$sRestrictRes = $this->RestrictAction($iVisitorID);
				if ($sRestrictRes != '') return $sRestrictRes;
				//if ($this->RestrictAction($iVisitorID)) return;
			}

			$sSuccUpd = _t("_SUCC_UPD_ADV");
			$sFailUpd = _t("_FAIL_UPD_ADV");
			$sMessage = $this->process_html_db_input($_REQUEST['commentText']);
			//$sMessage = str_replace( "\r\n", "<br>", $sMessage );
			$query = "UPDATE `ClsAdvComments` SET `Message` = '{$sMessage}' WHERE `ClsAdvComments`.`ID` = {$iCommentID} LIMIT 1 ;";
			$sqlRes = db_res( $query );
			$sRet = (mysql_affected_rows()>0) ? _t($sSuccUpd) : _t($sFailUpd);
			return  MsgBox($sRet) . $this -> ActionPrintAdvertisement($iEditAdvertisementID);
		} elseif($iVisitorID != $iAdvOwner && $iVisitorID != $iCommOwner) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}*/

	/*function ActionDeleteComment() {
		return '';
		$iCommentID = (int)$_REQUEST['DeleteCommentID'];
		$iEditAdvCommID = (int)$_REQUEST['DAdvID'];
		$sCommSQL = "SELECT `IDProfile`
							FROM `ClsAdvComments`
							WHERE `ID`={$iCommentID}
						";
		$aCommOwner = db_arr($sCommSQL);
		$iCommOwner = $aCommOwner['IDProfile'];

		$sAdvCommSQL = "SELECT `IDProfile`
							FROM `ClassifiedsAdvertisements`
							WHERE `ID`={$iEditAdvCommID}
						";
		$aAdvOwner = db_arr($sAdvCommSQL);
		$iAdvOwner = $aAdvOwner['IDProfile'];
		$iVisitorID = (int)$_COOKIE['memberID'];
		if (($iVisitorID == $iAdvOwner || $iVisitorID == $iCommOwner || $this->bAdminMode) && $iCommentID > 0) {
			if ($this -> bAdminMode == FALSE) {
				$sRestrictRes = $this->RestrictAction($iVisitorID);
				if ($sRestrictRes != '') return $sRestrictRes;
				//if ($this->RestrictAction($iVisitorID)) return;
			}

			$sSuccUpd = _t("_SUCC_UPD_ADV");
			$sFailUpd = _t("_FAIL_UPD_ADV");
			$sMessage = $this->process_html_db_input($_REQUEST['commentText']);
			//$sMessage = str_replace( "\r\n", "<br>", $sMessage );
			$query = "DELETE FROM `ClsAdvComments` WHERE `ID` = {$iCommentID}";
			$sqlRes = db_res( $query );
			$sRet = (mysql_affected_rows()>0) ? _t($sSuccUpd) : _t($sFailUpd);
			return  MsgBox($sRet) . $this -> ActionPrintAdvertisement($iEditAdvertisementID);
		} elseif($iVisitorID != $iAdvOwner && $iVisitorID != $iCommOwner) {
			return MsgBox(_t('_Hacker String'));
		} else {
			return MsgBox(_t('_Error Occured'));
		}
	}*/

	/**
	 * SQL Insert a comment to Advertisement
	 *
	  * @param $iAdvertisementID
	 * @return Text result of action
	 */
	/*function ActionPostCommAdvertisement($iAdvertisementID) {
		return '';
		if ($this -> bAdminMode == FALSE) {
			$iMemberID = (int)$_COOKIE['memberID'];
			$sRestrictRes = $this->RestrictAction($iMemberID);
			if ($sRestrictRes != '') return $sRestrictRes;
			//if ($this->RestrictAction($iMemberID)) return;
		}
		else {
			$iMemberID = 0;
		}

		$sSuccAC = _t("_SUCC_ADD_COMM");
		$sFailAC = _t("_FAIL_ADD_COMM");
		$sMessage = $this->process_html_db_input($_POST['message']);

		$sQuery = "INSERT INTO `ClsAdvComments` SET
					`IDAdv`={$iAdvertisementID},
					`IDProfile`={$iMemberID},
					`Message`='{$sMessage}',
					`DateTime`=NOW()";
		$vSqlRes = db_res( $sQuery );
		$sRet = (mysql_affected_rows()>0) ? $sSuccAC : $sFailAC;
		return MsgBox($sRet);
	}*/

	/**
	 * SQL Get all Advertisement data, custom fields, units by Advertisement ID
	 *
	  * @param $iAdvertisementID
	 * @return SQL data
	 */
	function GetAdvertisementData($iAdvertisementID) {
		$sQuery = "
			SELECT `ClassifiedsAdvertisements`.*, `Classifieds`.`CustomFieldName1`, `Classifieds`.`CustomFieldName2`, `Classifieds`.`CustomAction1`, `Classifieds`.`CustomAction2`, `ClassifiedsSubs`.`NameSub`, `ClassifiedsSubs`.`SEntryUri`, `ClassifiedsSubs`.`ID` AS 'SubID', `Classifieds`.`Name`, `Classifieds`.`CEntryUri`, `Classifieds`.`ID` AS 'CatID', `Classifieds`.`Unit`, (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`ClassifiedsAdvertisements`.`DateTime`)) AS 'sec'
			FROM `ClassifiedsAdvertisements`
			INNER JOIN `ClassifiedsSubs`
			ON (`ClassifiedsAdvertisements`.`IDClassifiedsSubs`=`ClassifiedsSubs`.`ID`)
			INNER JOIN `Classifieds`
			ON (`Classifieds`.`ID`=`ClassifiedsSubs`.`IDClassified`)
			WHERE `ClassifiedsAdvertisements`.`ID`={$iAdvertisementID}";

		$aSqlResStr = db_assoc_arr( $sQuery );
		return $aSqlResStr;
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
	 * SQL Get all Advertisement data, custom fields, units by Profile Id
	 *
	  * @param $iProfileId
	 * @return SQL data
	 */
	function GetAdvDataOfProfile($iProfileId, $iRandLim=-1) {
		$sRL = ($iRandLim>0) ? " ORDER BY RAND() LIMIT {$iRandLim}" : '';
		$sQuery = "
			SELECT `ClassifiedsAdvertisements`.*, `Classifieds`.`CustomFieldName1`, `Classifieds`.`CustomFieldName2`, `Classifieds`.`CustomAction1`, `Classifieds`.`CustomAction2`, `Classifieds`.`Unit`, (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`ClassifiedsAdvertisements`.`DateTime`)) AS 'sec'
			FROM `ClassifiedsAdvertisements`
			INNER JOIN `ClassifiedsSubs`
			ON (`ClassifiedsAdvertisements`.`IDClassifiedsSubs`=`ClassifiedsSubs`.`ID`)
			INNER JOIN `Classifieds`
			ON (`Classifieds`.`ID`=`ClassifiedsSubs`.`IDClassified`)
			WHERE `IDProfile` ={$iProfileId}
			{$sRL}
		";
		$aSqlResStr = db_res( $sQuery );
		return $aSqlResStr;
	}

	/**
	 * SQL Get all Advertisement data, units take into mind LifeDate of Adv
	 *
	  * @param $iClsID
	  * @param $sAddon - string addon of Limits (for pagination)
	  * @param $bSub - present that current ID is SubCategory
	 * @return SQL data
	 */
	function GetAdvByDate($iClsID, $sAddon, $bSub=FALSE) {
		$sWhereAdd = ($bSub) ? "`ClassifiedsSubs`" : "`Classifieds`" ;
		$sTimeRestriction = ($this->bAdminMode==true) ? '' : 'AND DATE_ADD( `ClassifiedsAdvertisements`.`DateTime` , INTERVAL `ClassifiedsAdvertisements`.`LifeTime` DAY ) > NOW( )';
		$sQuery = "
			SELECT `ClassifiedsAdvertisements`.* , `Classifieds`.`Name`, `Classifieds`.`Description`, `Classifieds`.`Unit`, (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`ClassifiedsAdvertisements`.`DateTime`)) AS 'sec'
			FROM `ClassifiedsAdvertisements` 
			INNER JOIN `ClassifiedsSubs` ON ( `ClassifiedsAdvertisements`.`IDClassifiedsSubs` = `ClassifiedsSubs`.`ID` ) 
			INNER JOIN `Classifieds` ON ( `ClassifiedsSubs`.`IDClassified` = `Classifieds`.`ID` ) 
			WHERE {$sWhereAdd}.`ID` = {$iClsID}
			{$sTimeRestriction}
".$sAddon;

		$vSqlRes = db_res ($sQuery);
		return $vSqlRes;
	}
	function GetAdvByDateCnt($iClsID, $bSub=FALSE) {
		$sWhereAdd = ($bSub) ? "`ClassifiedsSubs`" : "`Classifieds`" ;
		$sTimeRestriction = ($this->bAdminMode==true) ? '' : 'AND DATE_ADD( `ClassifiedsAdvertisements`.`DateTime` , INTERVAL `ClassifiedsAdvertisements`.`LifeTime` DAY ) > NOW( )';
		$sQuery = "
			SELECT COUNT(`ClassifiedsAdvertisements`.`ID`) AS 'Cnt'
			FROM `ClassifiedsAdvertisements` 
			INNER JOIN `ClassifiedsSubs` ON ( `ClassifiedsAdvertisements`.`IDClassifiedsSubs` = `ClassifiedsSubs`.`ID` ) 
			INNER JOIN `Classifieds` ON ( `ClassifiedsSubs`.`IDClassified` = `Classifieds`.`ID` ) 
			WHERE {$sWhereAdd}.`ID` = {$iClsID}
			{$sTimeRestriction}
		";

		return $sQuery;
	}

	/**
	 * SQL Get all Classifieds by LifeTime (for tag searching)
	 *
	 * @return SQL data
	 */
	function GetAdvByTags() {
		$sQuery = "
			SELECT `ClassifiedsAdvertisements`.* , `Classifieds`.`Name`, `Classifieds`.`Description`, `Classifieds`.`Unit`, (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`ClassifiedsAdvertisements`.`DateTime`)) AS `sec`
			FROM `ClassifiedsAdvertisements` 
			INNER JOIN `ClassifiedsSubs` ON ( `ClassifiedsAdvertisements`.`IDClassifiedsSubs` = `ClassifiedsSubs`.`ID` ) 
			INNER JOIN `Classifieds` ON ( `ClassifiedsSubs`.`IDClassified` = `Classifieds`.`ID` ) 
			AND DATE_ADD( `ClassifiedsAdvertisements`.`DateTime` , INTERVAL `ClassifiedsAdvertisements`.`LifeTime` DAY ) > NOW( )
		";

		$vSqlRes = db_res ($sQuery);
		return $vSqlRes;
	}

	/**
	 * SQL Get all Classifieds
	 *
	 * @return SQL data
	 */
	function GetDataOfCls() {
		$sOrderBy = (getParam('enable_classifieds_sort') == 'on') ? 'ORDER BY `Classifieds`.`Name` ASC' : '' ;
		$sQuery = "SELECT * FROM `Classifieds` {$sOrderBy}";
		$vSqlRes = db_res ($sQuery);
		return $vSqlRes;
	}

	function genUrl($iEntryId, $sEntryUri, $sType='entry', $bForce = false) {
		global $site;

		if ($bForce) {
			$sEntryUri = db_value("SELECT `EntryUri` FROM `ClassifiedsAdvertisements` WHERE `ID`='{$iEntryId}' LIMIT 1");
		}

		$sMainUrl = $site['url'];
		if ($this->bAdminMode) $sMainUrl = $site['url_admin'];

		if ($this->bUseFriendlyLinks && $this->bAdminMode == false) {
			$sUrl = $sMainUrl."ads/{$sType}/{$sEntryUri}";
		} else {
			$sUrl = '';
			switch ($sType) {
				case 'entry':
					$sUrl = "{$sMainUrl}{$this->sCurrBrowsedFile}?ShowAdvertisementID={$iEntryId}";
					break;
				/*case 'part':
					$sUrl = "{$sMainUrl}events.php?action=show_part&amp;event_id={$iEntryId}";
					break;
				case 'search':
					$sUrl = "{$sMainUrl}events.php?action=search_by_tag&amp;tagKey={$sEntryUri}";
					break;*/
			}
		}
		return $sUrl;
	}

	function GenAnyBlockContent($sOrder='last', $iProfileID=0, $sLimit="LIMIT 5" ) {
		global $site;
		global $short_date_format;

		$php_date_format = getParam( 'php_date_format' );
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		$sClockIcon = getTemplateIcon( 'clock.gif' );

		$sOrderS = '';
		switch ($sOrder) {
			case 'last':
				$sOrderS = "ORDER BY `DateTime` DESC";
				break;
			case 'latest':
				$sOrderS = "ORDER BY `DateTime` DESC";
				break;
			case 'rand':
				$sOrderS = "ORDER BY RAND()";
				break;
			case 'top':
				$sOrderS = "ORDER BY `CommCount` DESC";
				break;
		}
		$sProfileS = ($iProfileID>0) ? "`ClassifiedsAdvertisements`.`IDProfile` = '{$iProfileID}'" : '1';

		$sTimeAddon = ($iProfileID>0) ? '' : "AND DATE_ADD(`ClassifiedsAdvertisements`.`DateTime` , INTERVAL `ClassifiedsAdvertisements`.`LifeTime` DAY) > NOW()";

        $oCmts = new BxDolCmts ('classifieds', 0, 0);

		$sQuery = "
			SELECT DISTINCT
			`ClassifiedsAdvertisements`.`ID`,
			`ClassifiedsAdvertisements`.`Subject`,
			`ClassifiedsAdvertisements`.`EntryUri`,
			`ClassifiedsAdvertisements`.`Media`,
			`Profiles`.`NickName`,
			UNIX_TIMESTAMP( `ClassifiedsAdvertisements`.`DateTime` ) as `DateTime_f`,
			`ClassifiedsAdvertisements`.`DateTime`,
			`Classifieds`.`Name`, `Classifieds`.`CEntryUri`, `Classifieds`.`ID` AS `CatID`,
			`ClassifiedsSubs`.`NameSub`, `ClassifiedsSubs`.`SEntryUri`, `ClassifiedsSubs`.`ID` AS `SubCatID`,
			`ClassifiedsAdvertisements`.`Message`,
			COUNT(`tc`.`cmt_id`) AS 'CommCount'
			FROM `ClassifiedsAdvertisements`
			LEFT JOIN `ClassifiedsSubs`
			ON `ClassifiedsSubs`.`ID`=`ClassifiedsAdvertisements`.`IDClassifiedsSubs`
			LEFT JOIN `Classifieds`
			ON `Classifieds`.`ID`=`ClassifiedsSubs`.`IDClassified`
			LEFT JOIN `Profiles` ON `Profiles`.`ID`=`ClassifiedsAdvertisements`.`IDProfile`
			LEFT JOIN `" . $oCmts->getCommentsTableName() . "` AS `tc` ON `tc`.`cmt_object_id`=`ClassifiedsAdvertisements`.`ID`
			WHERE
			{$sProfileS}
			AND `ClassifiedsAdvertisements`.`Status` = 'active'
			{$sTimeAddon}
			GROUP BY `ClassifiedsAdvertisements`.`ID`
			{$sOrderS}
			{$sLimit}
		";

		$rBlogs = db_res( $sQuery );

		if( !mysql_num_rows( $rBlogs ) )
			return '';

		$sBlocks = '';

		while( $aBlog = mysql_fetch_assoc( $rBlogs ) ) {
			if ($sOrder == 'top' && $aBlog['CommCount'] == 0)
				continue;

			$sPic = $this->getImageCode($aBlog['Media'],TRUE);

			$sGenUrl = $this->genUrl($aBlog['ID'], $aBlog['EntryUri']);
			$sGenCUrl = ($this->bUseFriendlyLinks && $this->bAdminMode == false) ? $site['url'].'ads/cat/'.$aBlog['CEntryUri'] : "{$this->sCurrBrowsedFile}?bClassifiedID={$aBlog['CatID']}";
			$sGenSCUrl = ($this->bUseFriendlyLinks && $this->bAdminMode == false) ? $site['url'].'ads/subcat/'.$aBlog['SEntryUri'] : "{$this->sCurrBrowsedFile}?bSubClassifiedID={$aBlog['SubCatID']}";

			$sLinkMore = '';
			if( strlen( $aBlog['Message']) > $iBlogLimitChars ) 
				$sLinkMore = "... <a href=\"{$sGenUrl}\">"._t('_Read more')."</a>";

			$sBlogSnippet = mb_substr( strip_tags( $aBlog['Message'] ), 0, $iBlogLimitChars ) . $sLinkMore;
			$sDataTimeFormatted = date( $php_date_format, $aBlog['DateTime_f'] );
			$sInCatFormatted = _t( '_in Category', getTemplateIcon( 'ad_category.gif' ), $sGenCUrl, process_line_output($aBlog['Name']) );
			$sSubNameF = process_line_output($aBlog['NameSub']);
			$sCommentsF = _t( '_comments N', getTemplateIcon( 'add_comment.gif' ), $aBlog['CommCount'] );
			$sSubjectF = process_line_output( $arr['Subject'] );

			$sBlocks .= <<<EOF
<div class="blog_block">
	<div class="icon_block">
		<div class="thumbnail_block" style="float:left;">
			<a href="{$sGenUrl}" class="bottom_text">
				{$sPic}
			</a>
		</div>
	</div>
	<div class="blog_wrapper_n">
		<div class="blog_subject_n">
			<a href="{$sGenUrl}" class="bottom_text">
				{$sSubjectF}
			</a>
		</div>
		<div class="blogInfo">
			<span><img src="{$sClockIcon}" alt="" />{$sDataTimeFormatted} </span>
			<span>{$sInCatFormatted} / <a href="{$sGenCUrl}">{$sSubNameF}</a></span>
			<span>{$sCommentsF}</span>
		</div>
		<div class="blogSnippet">
			{$sBlogSnippet}
		</div>
	</div>
</div>
<div class="clear_both"></div>
EOF;
		}

		return $sBlocks;
	}

	function process_html_db_input( $sText ) {
		return addslashes( clear_xss( trim( process_pass_data( $sText ))));
	}
}
?>
