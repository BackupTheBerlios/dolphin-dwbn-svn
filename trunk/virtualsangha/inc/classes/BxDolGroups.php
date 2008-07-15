<?
require_once( BX_DIRECTORY_PATH_INC . 'header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

require_once( BX_DIRECTORY_PATH_INC . 'tags.inc.php' );

/*
 * class for Groups
 */
class BxDolGroups {
	//variables
	var $sCurrFile;

	//admin mode, can perform all actions
	var $bAdminMode;

	//use permalink
	var $bUseFriendlyLinks;

	var $sSubGrpPath;
	var $sGrpPath;
	var $sGrpGalPath;

	var $sSpacerIcon;

	/**
	 * constructor
	 */
	function BxDolGroups($bAdmMode = false) {
		global $site;

		//$dirGroups  -> $this->sGrpPath
		$this->sSpacerIcon = getTemplateIcon( 'spacer.gif' );

		$this->bAdminMode = $bAdmMode;

		$this->bUseFriendlyLinks = getParam('permalinks_groups') == 'on' ? true : false;

		$this->sSubGrpPath = 'groups/';

		$this->sGrpPath = BX_DIRECTORY_PATH_ROOT . $this->sSubGrpPath;
		$this->sGrpGalPath = $this->sGrpPath . 'gallery/';

		$site['groups']         = $site['url'].$this->sSubGrpPath;
		$site['groups_gallery'] = "{$site['groups']}gallery/";

		$this->sCurrFile = 'grp.php';
	}


	/********************Page functions***************************************/
	function GenIndexPageOfGroups() {
		$sCategories = $this->GenAllCategories();
		$sKW = $this->process_html_db_input($_GET['keyword']);
		$sKeyAddon = (isset($_GET['keyword']) && $sKW != '') ? $sKW : 0;
		$sAllNewGroups = $this->PageCompGroupsSearchResults( $_GET['keyword'], 0, 0, 0, 0, 'membersCount', true );
		return array($sCategories, $sAllNewGroups);
	}

	function GenAllCategories() {
		global $site;

		$sKeywordC = _t('_Keyword');
		$sSearchC = _t('_Search');
		$sAdvSearchC = _t('_Advanced search');

		$sCategories = $this->genAllCategsList();

		$sRet = <<<EOF
<div class="groups_categs_wrapper">
	<div class="clear_both"></div>
		{$sCategories}
	<div class="clear_both"></div>
</div>

<div class="groups_search_simple">
	<form action="{$site['url']}{$this->sCurrFile}?action=categ" method="get">
		{$sKeywordC}:
		<input type="text" name="keyword" />
		<input type="submit" value="{$sSearchC}" />
		&nbsp;&nbsp;&nbsp;<a href="{$site['url']}{$this->sCurrFile}?action=search">{$sAdvSearchC}</a>
	</form>
</div>
EOF;
		return $sRet;
	}

	function GenCategoryPage($bHiddenSearchForm = false) {
		$sForm = '';
		$sResults = '';

		list($sKeyword, $sSearchby, $iCategID, $sCountry, $sCityVal, $sSortby, $sKeywordDB, $sSearchbyDB, $sCategIDDB, $sCountryDB, $sCityDB, $sSortbyDB) = $this->CollectBrowseParams();
		$sForm = $this->GenSearchForm($sKeyword, $sSearchby, $iCategID, $sCountry, $sCityVal, $sSortby, $bHiddenSearchForm);

		if( $sKeywordDB or $sCategIDDB or $sCountryDB or $sCityVal )
			$sResults = $this->PageCompGroupsSearchResults( $sKeywordDB, $sSearchbyDB, $sCategIDDB, $sCountryDB, $sCityDB, $sSortbyDB );

		return array($sForm, $sResults);
	}

	function CollectBrowseParams() {
		global $aPreValues;

		// get search params
		$sKeyword  = $_REQUEST['keyword'];
		$sSearchby = $_REQUEST['searchby'];
		$sCountry  = $_REQUEST['Country'];
		$sCityVal     = $_REQUEST['City'];
		$sSortby   = $_REQUEST['sortby'];
		// [END] get search params

		if (isset($_REQUEST['categUri'])) {
			$sUri = process_db_input($_REQUEST['categUri']);
			$iCategID = (int)db_value("SELECT `ID` FROM `GroupsCateg` WHERE `Uri`='{$sUri}'");
		} else {
			$iCategID = (int)$_REQUEST['categID'];
		}	

		// check search params
		unset( $sKeywordDB );
		unset( $sSearchbyDB );
		unset( $sCategIDDB );
		unset( $sCountryDB );
		unset( $sCityDB );
		unset( $sSortbyDB );

		if( isset($sKeyword) and strlen($sKeyword) ) {
			$sKeyword = trim( $sKeyword );
			if( strlen( $sKeyword ) )
				$sKeywordDB = strtoupper( process_db_input( $sKeyword ) );
			$sKeyword = process_pass_data( $sKeyword );
		}

		if( $sSearchby == 'name' or $sSearchby == 'keyword' )
			$sSearchbyDB = $sSearchby;
		else
			$sSearchbyDB = $sSearchby = 'keyword';

		$sCategIDDB = $iCategID = (int)$iCategID;

		if( isset( $sCountry ) and isset( $aPreValues['Country'][$sCountry] ) )
			$sCountryDB = $sCountry;
		else
			$sCountry = '';

		if( isset($sCityVal) and strlen($sCityVal) ) {
			$sCityVal = trim( $sCityVal );
			if( strlen( $sCityVal ) )
				$sCityDB = strtoupper( process_db_input( $sCityVal ) );
			$sCityVal = process_pass_data( $sCityVal );
		}

		if( $sSortby == 'Name' or $sSortby == 'membersCount' or $sSortby == 'created' )
			$sSortbyDB = $sSortby;
		else
			$sSortbyDB = $sSortby = 'membersCount';
		// [END] check search params

		$aRes = array($sKeyword, $sSearchby, $iCategID, $sCountry, $sCityVal, $sSortby, $sKeywordDB, $sSearchbyDB, $sCategIDDB, $sCountryDB, $sCityDB, $sSortbyDB);
		return $aRes;
	}

	//PageCompGroupsSearchForm
	function GenSearchForm( $sKeyword, $sSearchby, $iCategID, $sCountry, $sCityVal, $sSortby, $bHiddenSearchForm = false ) {
		global $aPreValues;
		global $site;

		$sKeywordC = _t('_Keyword');
		$sSearchByC = _t('_Search by');
		$sByGrpNameC = _t('_by group name');
		$sByKeywC = _t('_by keyword');
		$sCategoryC = _t('_Category');
		$sAnyC = _t('_Any');
		$sCountryC = _t('_Country');
		$sCityC = _t('_City');
		$sSortByC = _t('_Sort by');
		$sByGrpNameC = _t('_by group name');
		$sByPopularC = _t('_by popular');
		$sByNewestC = _t('_by newest');
		$sSearchC = _t('_Search');
		$sEnterParamsC = _t('_Please select at least one search parameter');

		$sUnbrKeyw = $this->unbreak_js( str_replace( '\'','\\\'', str_replace( '\\','\\\\',$sKeyword ) ) );
		$sUnbrCity = $this->unbreak_js( str_replace( '\'','\\\'', str_replace( '\\','\\\\',$sCityVal ) ) );

		$sJSCode = <<<EOF
<script type="text/javascript">
	var keyword  = '{$sUnbrKeyw}';
	var searchby = '{$sSearchby}';
	var categID  = '{$iCategID}';
	var Country  = '{$sCountry}';
	var City     = '{$sUnbrCity}';
	var sortby   = '{$sSortby}';

	function checkSearchForm() {
		_form = document.forms.groups_search_form;
		if( !_form )
			return false;

		if( !_form.keyword.value && !_form.categID.value && !_form.Country.value && !_form.City.value ) {
			alert( '{$sEnterParamsC}' );
			return false;
		}
	}
</script>
EOF;

		/*$bNoFilter = false;
		if (isset($_REQUEST['categID']) && isset($_REQUEST['nf']) && (int)$_REQUEST['nf'] == 1) $bNoFilter = true;
		if ($bNoFilter == true) $sDisplayStyle='style="display:none"';
		if ($bNoFilter == true) $sNFelement = '<input type="hidden" name="nf" value="1" />';*/

		$sKeyword = htmlspecialchars_adv($sKeyword);
		$sCity = htmlspecialchars_adv($sCityVal);

		$sCategoriesOpt = '';
		$rVals = db_res( "SELECT * FROM `GroupsCateg` ORDER BY `Name`" );
		while ( $arr = mysql_fetch_assoc( $rVals ) ) {
			$sCategName = htmlspecialchars_adv( $arr['Name'] );
			$sSelected = ($iCategID == $arr['ID']) ? ' selected="selected"' : '';
			$sCategoriesOpt .= <<<EOF
<option value="{$arr['ID']}" {$sSelected}>{$sCategName}</option>
EOF;
		}

		$sCountries = '';
		foreach( $aPreValues['Country'] as $sKey => $sVal ) {
			$sSelecCnt = (strcmp($sCountry, $sKey)) ? '' : ' selected="selected"';
			$sCountryLocaliz = _t($sVal['LKey']);

			$sCountries .= <<<EOF
<option value="{$sKey}" {$sSelecCnt}>{$sCountryLocaliz}</option>
EOF;
		}

		$sChecked = 'checked="checked"';
		$sSBVal = ($sSearchby == 'name') ? $sChecked : '';
		$sSBKey = ($sSearchby == 'keyword') ? $sChecked : '';
		$sSortBySel = ($sSortby == 'Name') ? $sChecked : '';
		$sByMembConSel = ($sSortby == 'membersCount') ? $sChecked : '';
		$sByCreatedSel = ($sSortby == 'created') ? $sChecked : '';

		if ($bHiddenSearchForm == true) {
			$sRetHtml = <<<EOF
	<form action="{$site['url']}{$this->sCurrFile}?action=categ" method="get" name="groups_search_form" onsubmit="return checkSearchForm();">
		<input type="hidden" id="keyword" name="keyword" value="" />
		<input type="hidden" name="searchby" value="name" id="searchby_name" />
		<input type="hidden" name="searchby" value="keyword" id="searchby_keyword" />
		<input type="hidden" id="categID" name="categID" value="{$iCategID}" />
		<input type="hidden" id="Country" name="Country" value="" />
		<input type="hidden" id="City" name="City" value="" />
		<input type="hidden" name="sortby" value="Name" id="sortby_Name" />
		<input type="hidden" name="sortby" value="membersCount" id="sortby_membersCount" />
		<input type="hidden" name="sortby" value="created" id="sortby_created" />
		<input type="hidden" name="hidden_mode" value="1" />
		<input type="hidden" name="page" value="1" />
		<input type="hidden" value="search" id="action" name="action" />
		<!-- <input type="submit" value="{$sSearchC}" class="groups_search_labelfor" /> -->
	</form>
EOF;
		} else {
			$sRetHtml = <<<EOF
<div class="groups_search_adv" {$sDisplayStyle} >
	<div class="clear_both"></div>
	<form action="{$site['url']}{$this->sCurrFile}?action=categ" method="get" name="groups_search_form" onsubmit="return checkSearchForm();">
		<div class="groups_search_row">
			<div class="groups_search_label">{$sKeywordC}:</div>
			<div class="groups_search_value">
				<input type="text" id="keyword" name="keyword" class="groups_search_text" value="{$sKeyword}" />
			</div>
			<div class="clear_both"></div>
		</div>

		<div class="groups_search_row">
			<div class="groups_search_label">{$sSearchByC}:</div>
			<div class="groups_search_value">
				<input type="radio" name="searchby" class="groups_search_radio" value="name" id="searchby_name" {$sSBVal} />
				<label for="searchby_name" class="groups_search_labelfor">{$sByGrpNameC}</label>

				<input type="radio" name="searchby" class="groups_search_radio" value="keyword" id="searchby_keyword" {$sSBKey} />
				<label for="searchby_keyword" class="groups_search_labelfor">{$sByKeywC}</label>
			</div>
			<div class="clear_both"></div>
		</div>

		<div class="groups_search_row">
			<div class="groups_search_label">{$sCategoryC}:</div>
			<div class="groups_search_value">
				<select id="categID" name="categID" class="groups_search_select" >
					<option value="">{$sAnyC}</option>
					{$sCategoriesOpt}
				</select>
			</div>
			<div class="clear_both"></div>
		</div>

		<div class="groups_search_row">
			<div class="groups_search_label">{$sCountryC}:</div>
			<div class="groups_search_value">
				<select id="Country" name="Country" class="groups_search_select" >
					<option value="">{$sAnyC}</option>
					{$sCountries}
				</select>
			</div>
			<div class="clear_both"></div>
		</div>

		<div class="groups_search_row">
			<div class="groups_search_label">{$sCityC}:</div>
			<div class="groups_search_value">
				<input type="text" id="City" name="City" class="groups_search_text" value="{$sCity}" />
			</div>
			<div class="clear_both"></div>
		</div>

		<div class="groups_search_row">
			<div class="groups_search_label">{$sSortByC}:</div>
			<div class="groups_search_value">
				<input type="radio" name="sortby" class="groups_search_radio" value="Name" id="sortby_Name" {$sSortBySel} />
				<label for="sortby_Name" class="groups_search_labelfor">{$sByGrpNameC}</label>

				<input type="radio" name="sortby" class="groups_search_radio" value="membersCount" id="sortby_membersCount" {$sByMembConSel} />
				<label for="sortby_membersCount" class="groups_search_labelfor">{$sByPopularC}</label>

				<input type="radio" name="sortby" class="groups_search_radio" value="created" id="sortby_created" {$sByCreatedSel} />
				<label for="sortby_created" class="groups_search_labelfor">{$sByNewestC}</label>
			</div>
			<div class="clear_both"></div>
		</div>

		<input type="hidden" name="page" value="1" />

		<div class="groups_search_row_center">
			<input type="hidden" value="search" id="action" name="action" />
			<input type="submit" value="{$sSearchC}" class="groups_search_labelfor" />
			<div class="clear_both"></div>
		</div>
	</form>
	<div class="clear_both"></div>
</div>
EOF;
		}

		return ($bHiddenSearchForm == true) ? $sJSCode.$sRetHtml : DesignBoxContent( _t('_Search Groups'), $sJSCode . $sRetHtml, 1);
		//return ($bNoFilter == true) ? $sJSCode . $sRetHtml : DesignBoxContent ( _t('_Search Groups'), $sJSCode . $sRetHtml, 1);
	}

	// Ma-an it is crazy. I don't know what they'll enter in search form =)
	// Our testers entered <script>alert(1)</script> it has broken everything
	function unbreak_js( $sText ) {
		return str_replace( '</script>', "</scr'+'ipt>", $sText );
	}

	function PCGroupMembers($aGroupInfo, $iGroupID, $iMemberID) {
		global $site;
		global $oTemplConfig;

		$sViewAllC = _t("_View all members");
		$sEditMembC = _t('_Edit members');

		$sRetHtml = '<div class="group_members_pre1">';

		$iNumberMembers = $oTemplConfig -> iGroupMembersPreNum;
		$sQuerySQL = "
			SELECT
				`GroupsMembers`.`memberID` AS `ID`,
				`Profiles`.`NickName`
			FROM `GroupsMembers`
			INNER JOIN `Profiles` ON `GroupsMembers`.`memberID` = `Profiles`.`ID`
			WHERE
				`GroupsMembers`.`groupID` = '{$iGroupID}' AND `GroupsMembers`.`Status` = 'Active'
			ORDER BY RAND()
			LIMIT {$iNumberMembers}
		;";

		$vMembers = db_res( $sQuerySQL );

		while ( $aMemberInfo = mysql_fetch_assoc( $vMembers ) ) {
			$sMembThumb = get_member_thumbnail( $aMemberInfo['ID'],'none', true );
			$sMembLink = getProfileLink( $aMemberInfo['ID'] );
			$sMembNick = htmlspecialchars_adv( $aMemberInfo['NickName'] );
			$sRetHtml .= <<<EOF
<div class="group_member_pre">
	{$sMembThumb}
	<a href="{$sMembLink}">{$sMembNick}</a>
</div>
EOF;
		}
		$sRetHtml .= <<<EOF
	</div>
<div class="clear_both"></div>
<div class="view_all_link">
	<a href="{$site['url']}{$this->sCurrFile}?action=group_members&ID={$iGroupID}">{$sViewAllC}</a>
</div>
EOF;

		$sCreatorEditMembers = '';
		if( $aGroupInfo['creatorID'] == $iMemberID ) {
			$sCreatorEditMembers = <<<EOF
<div class="caption_item">
	<a href="{$site['url']}{$this->sCurrFile}?action=group_members&amp;mode=edit&amp;ID={$iGroupID}">{$sEditMembC}</a>
</div>
EOF;
		}

		return DesignBoxContent( _t("_Group members"), $sRetHtml, 1, $sCreatorEditMembers );
	}

	function PCGroupForum($aGroupInfo, $iGroupID, $iMemberID) {
		global $site;

		$sForumUri = urlencode(db_value("SELECT `forum_uri` FROM `grp_forum` WHERE `forum_id` = '{$iGroupID}' LIMIT 1"));

		//Замечание: Если вы открываете URI содержащий спецсимволы, такие как пробел, вам нужно закодировать URI при помощи urlencode(). 
		$sRet = file_get_contents("{$site['groups']}orca/?action=group_last_topics&forum={$sForumUri}&trans=1");

		$sViewAllForum = _t( '_View all topics' );
		$sPostNewTopic = _t( '_Post a new topic' );

		$caption_item = '<div class="caption_item">';
		if ( $this->isGroupMember( $iMemberID, $iGroupID ) )
			$caption_item .= "<a href=\"{$site['groups']}orca/forum/{$sForumUri}-0.htm#action=goto&amp;new_topic={$sForumUri}\">{$sPostNewTopic}</a> | ";
		$caption_item .= "<a href=\"{$site['groups']}orca/forum/{$sForumUri}-0.htm\">{$sViewAllForum}</a>";
		$caption_item .= '</div>';

		return DesignBoxContent( _t("_Group forum"), $sRet, 1, $caption_item );
	}

	function PCGroupActions($aGroupInfo, $iGroupID, $iMemberID) {
		global $site;
		global $logged;

		$sRetHtml = '';

		if ( $logged['member'] ) {
			if ( $this->isGroupMember( $iMemberID, $iGroupID, false ) ) {
				if ( $this->isGroupMember( $iMemberID, $iGroupID ) ) { //if Active member
					if( (int)$aGroupInfo['members_invite'] or $aGroupInfo['creatorID'] == $iMemberID )
						$sRetHtml .= $this->genGroupActionBtn( 'Invite others', "group_actions.php?a=invite&amp;ID={$iGroupID}" );
					
					if( (int)$aGroupInfo['members_post_images'] or $aGroupInfo['creatorID'] == $iMemberID )
						$sRetHtml .= $this->genGroupActionBtn( 'Upload image', "group_actions.php?a=upload&amp;ID={$iGroupID}" );
					//$sRetHtml .= $this->genGroupActionBtn( 'Post topic', "{$this->sSubGrpPath}orca/?action=goto&amp;forum_id={$iGroupID}#action=goto&amp;new_topic={$iGroupID}" );
					$sForumUri = db_value("SELECT `forum_uri` FROM `grp_forum` WHERE `forum_id` = '{$iGroupID}' LIMIT 1");
					$sRetHtml .= $this->genGroupActionBtn( 'Post topic', "groups/orca/forum/{$sForumUri}-0.htm#action=goto&amp;new_topic={$sForumUri}" );
				}

				if ( $aGroupInfo['creatorID'] == $iMemberID )
					$sRetHtml .= $this->genGroupActionBtn( 'Edit group', "{$this->sCurrFile}?action=edit&ID={$iGroupID}" );
				else
					$sRetHtml .= $this->genGroupActionBtn( 'Resign group', "group_actions.php?a=resign&amp;ID={$iGroupID}", true );
			} else
				$sRetHtml .= $this->genGroupActionBtn( 'Join group', "group_actions.php?a=join&amp;ID={$iGroupID}", true );
		}

		return $sRetHtml;
	}

	function genGroupActionBtn( $sTitle, $sUrl, $bAsk = false ) {
		global $site;

		$sOnclick = ($bAsk) ? 'onclick="return confirm(\''._t("_Are you sure want to {$sTitle}?").'\')"' : '';
		$sLocTitle = _t('_'.$sTitle);

		$sRetHtml = <<<EOF
<div class="group_action">
	<a href="{$site['url']}{$sUrl}" {$sOnclick}>{$sLocTitle}</a>
</div>
EOF;
		return $sRetHtml;
	}


	function ShowGroupGalleryPage() {
		global $logged;
		global $site;

		$iNameIndex = 75;
		$sHeaderT = _t( "_Group gallery" );
		$sHeader = _t( "_Group gallery" );
		$sMainCode = '';

		if( $logged['member'] = member_auth( 0, false ) )
			$iMemberID = (int)$_COOKIE['memberID'];
		else {
			$iMemberID = 0;
			$logged['admin'] = member_auth( 1, false );
		}

		$iGroupID = (int)$_REQUEST['ID'];

		if ( !$iGroupID ) {
			Header( "Location: {$site['url']}{$this->sCurrFile}" );
			exit;
		}

		if ( $aGroupInfo = $this->getGroupInfo( $iGroupID ) ) {
			$aGroupInfo['Name_html'] = htmlspecialchars_adv( $aGroupInfo['Name'] );

			if ( (int)$aGroupInfo['hidden_group'] and !$this->isGroupMember( $iMemberID, $iGroupID ) and !$logged['admin'] )
				$sMainCode = _t( "_You cannot view gallery while not a group member" );
			else {
				if( $aGroupInfo['status'] == 'Active' or $aGroupInfo['creatorID'] == $iMemberID or $logged['admin'] ) {
					$sHeader = _t( "_Group gallery" );
					$sMainCode = $this->PCGenGroupGallery($iMemberID, $iGroupID, $aGroupInfo);
				} else {
					$iNameIndex = 0;
					$sHeader = _t( "_Group is suspended" );
					$sHeaderT = _t( "_Group is suspended" );
					$sMainCode = _t( "_Sorry, group is suspended" );
				}
			}
		} else
			$sMainCode = _t( "_Group not found_desc" );

		return array($iNameIndex, $sHeader, $sHeaderT, $sMainCode);
	}

	function PCGenGroupGallery($iMemberID, $iGroupID, $aGroupInfo) {
		global $site;

		$sUploadedByC = _t('_Uploaded by');
		$sSetAsThumbC = _t('_Set as thumbnail');
		$sDelImgC = _t('_Delete image');
		$sUploadImageC = _t('_Upload image');

		$sGroupLink = $this->getGroupUrl($iGroupID, $aGroupInfo['Uri']);

		$sBreadCrumbs = <<<EOJ
<div class="groups_breadcrumbs">
	<a href="{$site['url']}">{$site['title']}</a> /
	<a href="{$site['url']}{$this->sCurrFile}">__Groups__</a> /
	<a href="{$sGroupLink}">{$aGroupInfo['Name_html']}</a> /
	<span class="active_link">__Group gallery__</span>
</div>
EOJ;

		$sBreadCrumbs = str_replace( "__Groups__", _t( "_Groups" ), $sBreadCrumbs );
		$sBreadCrumbs = str_replace( "__Group gallery__", _t( "_Group gallery" ), $sBreadCrumbs );

		$sQuerySQL = "
			SELECT `GroupsGallery`.*, `Profiles`.`NickName`
			FROM `GroupsGallery`
			LEFT JOIN `Profiles` ON `GroupsGallery`.`by`=`Profiles`.`ID`
			WHERE `GroupsGallery`.`groupID`='{$iGroupID}'
			ORDER BY `GroupsGallery`.`ID`
		";

		$resPics = db_res( $sQuerySQL );

		$sRetHtml = <<<EOF
{$sBreadCrumbs}
<div class="group_gallery_wrapper">
	<div class="clear_both"></div>
EOF;

		while( $arrPic = mysql_fetch_assoc( $resPics ) ) {
			$sGalNick = htmlspecialchars_adv($arrPic['NickName']);
			$iNewJSW = $arrPic['width']+20;
			$iNewJSH = $arrPic['height']+20;

			$sRetHtml .= <<<EOF
<div class="group_gallery_pic" style="">
	<a href="{$site['groups_gallery']}{$arrPic['groupID']}_{$arrPic['ID']}_{$arrPic['seed']}.{$arrPic['ext']}"
	  title="{$sUploadedByC} {$sGalNick}" onclick="window.open(this.href, '_blank', 'width={$iNewJSW},height={$iNewJSH}');return false;">
		<img src="{$site['groups_gallery']}{$arrPic['groupID']}_{$arrPic['ID']}_{$arrPic['seed']}_.{$arrPic['ext']}"
		  style="width:{$arrPic['width_']}px;height:{$arrPic['height_']}px" alt="" />
	</a>
EOF;
			if( $aGroupInfo['thumb'] != $arrPic['ID'] and $aGroupInfo['creatorID'] == $iMemberID ) {
				$sRetHtml .= <<<EOF
<br />
<a href="{$site['url']}group_actions.php?ID={$iGroupID}&amp;a=def&amp;img={$arrPic['ID']}" class="group_set_thumb">{$sSetAsThumbC}</a>
EOF;
			}

			if( $aGroupInfo['creatorID'] == $iMemberID or $arrPic['by'] == $iMemberID ) {
				$sRetHtml .= <<<EOF
<br />
<a href="{$site['url']}group_actions.php?ID={$iGroupID}&amp;a=delimg&amp;img={$arrPic['ID']}" class="group_set_thumb" onclick="return confirm('<?=_t('_Are you sure want to delete this image?')?>');">{$sDelImgC}</a>
EOF;
			}

			$sRetHtml .= '</div>';
		}

		$sRetHtml .= '<div class="clear_both"></div></div>';

		if( ( (int)$aGroupInfo['members_post_images'] and $this->isGroupMember( $iMemberID, $iGroupID ) ) or $aGroupInfo['creatorID'] == $iMemberID ) {
			$sRetHtml .= <<<EOF
<a href="{$site['url']}group_actions.php?a=upload&ID={$iGroupID}" class="actions">{$sUploadImageC}</a>
EOF;
		}

		return $sRetHtml;
	}

	function PCEditGroupFormPage($iGroupID, $iMemberID) {
		$sMainCode = '';
		if ( $aGroupInfo = $this->getGroupInfo( $iGroupID ) ) {
			if ( $aGroupInfo['creatorID'] == $iMemberID ) //only creator can edit group
				$sMainCode = $this->PCEditGroupForm($iGroupID, $iMemberID, $aGroupInfo);
			else
				$sMainCode = _t( "_You're not creator" );
		}
		else
			$sMainCode = _t( "_Group not found_desc" );
		return $sMainCode;
	}

	function PCEditGroupForm($iGroupID, $iMemberID, $aGroupInfo) {
		global $site;

		$arrGroupFields = $this->getDefaultGroupEditArr();
		$this->fillGroupArrByDBValues( $arrGroupFields, $aGroupInfo );
		$aError = array();

		if( isset( $_POST['do_submit'] ) ) {
			$arrOldGroupFields = $arrGroupFields;
			$this->fillGroupArrByPostValues( $arrGroupFields );
			$arrUpdGroupFields = $this->compareUpdatedGroupFields( $arrOldGroupFields, $arrGroupFields );

			$sGroupLink = $this->getGroupUrl($iGroupID, $aGroupInfo['Uri']);

			if( !empty( $arrUpdGroupFields ) ) {
				$aError = $this->checkGroupErrors( $arrUpdGroupFields );

				if( empty( $aError ) ) {
					$this->saveGroup( $arrUpdGroupFields, $iGroupID );
					Header( "Location: {$sGroupLink}" );
					exit;
				}
			} else {
				Header( "Location: {$sGroupLink}" );
				exit;
			}
		}

		$res = $this->genGroupEditForm( $arrGroupFields, $aError, false, $iGroupID );
		return $res;
	}

	function PCCreateForm($iMemberID) {
		global $site;

		$arrNewGroup = $this->getDefaultGroupEditArr();
		$aError = array();

		if( isset( $_POST['do_submit'] ) ) {
			$this->fillGroupArrByPostValues( $arrNewGroup );
			$aError = $this->checkGroupErrors( $arrNewGroup );

			if( md5( $_POST['simg'] ) != $_COOKIE['strSec'] )
				$aError['simg'] = 'SIMG_ERR';
			unset( $_COOKIE['strSec'] );

			if( empty( $aError ) ) {
				$arrNewGroup['creatorID'] = array('Name' => 'creatorID','Type' => 'text','Value' => $iMemberID);
				$arrNewGroup['Uri'] = array('Name' => 'Uri','Type' => 'text',
					'Value' => uriGenerate($arrNewGroup['Name']['Value'], 'Groups', 'Uri', 255) );
				$newGroupID = $this->saveGroup( $arrNewGroup );
				if( $newGroupID ) {
					$this->addMember2Group( $iMemberID, $newGroupID, 'Active' );

					$groupHomeLink = "{$site['url']}{$this->sCurrFile}?action=group&amp;ID={$newGroupID}";
					$res = _t( '_Group creation successful', $groupHomeLink );
					$res .= "<br />";
					$res .= _t('_Gallery upload_desc');
					$res .= $this->genUploadForm( $newGroupID, true, true );
				} else
					$res = _t('_Group creation unknown error');
				return $res;
			}
		}

		$res = $this->genGroupEditForm( $arrNewGroup, $aError, true );
		return $res;
	}

	function GenGroupMainPage($iGroupID, $iMemberID) {
		global $logged;
		global $site;
		global $aPreValues;

		//$bPermalink = getParam('permalinks_groups') == 'on' ? true : false;

		//ret vals
		$iNameIndex = 71;
		$sHeader = '';
		$sHeaderT = '';
		$sMainCode = '';

		$sGrpBrd = '';
		$sGrpLCat = '';
		$sGrpLCreated = '';
		$sGrpLLocation = '';
		$sGrpLMemberCount = '';
		$sGrpLCreator = '';
		$sGrpLAbout = '';
		$sGrpLType = '';
		$sGrpLTypeHelp = '';

		$sGrpVImage = '';
		$sGrpVGalLink = '';
		$sGrpVCreatorThumb = '';
		$sGrpVCreatorLink = '';
		$sGrpVCat = '';
		$sGrpVCatLink = '';
		$sGrpVType = '';
		$sGrpVCreated = '';
		$sGrpVCountry = '';
		$sGrpVCity = '';
		$sGrpVMCount = '';
		$sGrpVAbout = '';
		$sGrpVDesc = '';
		$sGrpVStatus = '';
		$sGrpVActions = '';
		$sGrpVMembers = '';
		$sGrpVForum = '';

		$date_format_php = getParam('php_date_format');

		if ( !$aGroupInfo = $this->getGroupInfo( $iGroupID ) ) {
			$iNameIndex = 0;
			$sHeader = _t( "_Group not found" );
			$sHeaderT = _t( "_Group not found" );
			$sMainCode = _t( "_Group not found_desc" );
		} else {
			if( (int)$aGroupInfo['hidden_group'] and !$this->isGroupMember( $iMemberID, $iGroupID ) and !$logged['admin'] ) { 
				$iNameIndex = 0;
				$sHeader = _t( "_Group is hidden" );
				$sHeaderT = _t( "_Group is hidden" );
				$sMainCode = _t( "_Sorry, group is hidden" );
			} else {
				if( $aGroupInfo['status'] == 'Active' or $aGroupInfo['creatorID'] == $iMemberID or $logged['admin'] ) {
					$aGroupInfo['Name_html'] = htmlspecialchars_adv( $aGroupInfo['Name'] );

					$sGroupsUrl = $this->bUseFriendlyLinks ? 'groups/all' : $this->sCurrFile ;
					$sBreadCrumbs = <<<EOJ
<div class="groups_breadcrumbs">
	<a href="{$site['url']}">{$site['title']}</a> /
	<a href="{$site['url']}{$sGroupsUrl}">__Groups__</a> /
	<span class="active_link">{$aGroupInfo['Name_html']}</span>
</div>
EOJ;

					$sBreadCrumbs = str_replace( "__Groups__", _t( "_Groups" ), $sBreadCrumbs );

					$sHeader = "{$site['title']} / " . _t( "_Groups" ) . " / {$aGroupInfo['Name_html']}";
					$sHeaderT = $aGroupInfo['Name_html'];

					//$_page_cont[$_ni]['groups_breadcrumbs'] = $sBreadCrumbs;
					$sGrpBrd = $sBreadCrumbs;

					// begin group info

					if( (int)$aGroupInfo['hidden_group'] )
						$typeHelp = 7;
					else
						if( (int)$aGroupInfo['open_join'] )
							$typeHelp = 5;
						else
							$typeHelp = 6;

					$typeHelpLink = "{$site['url']}{$this->sCurrFile}?action=help&amp;i={$typeHelp}";

					// labels
					$sGrpLCat = _t( "_Category" );
					//$_page_cont[$_ni]['category_l']      = _t( "_Category" );
					$sGrpLCreated = _t( "_Created" );
					//$_page_cont[$_ni]['created_l']       = _t( "_Created" );
					$sGrpLLocation = _t( "_Location" );
					//$_page_cont[$_ni]['location_l']      = _t( "_Location" );
					$sGrpLMemberCount = _t( "_Members count" );
					//$_page_cont[$_ni]['members_count_l'] = _t( "_Members count" );
					$sGrpLCreator = _t( "_Group creator" );
					//$_page_cont[$_ni]['group_creator_l'] = _t( "_Group creator" );
					$sGrpLAbout = _t( "_About group" );
					//$_page_cont[$_ni]['group_about_l']   = _t( "_About group" );
					$sGrpLType = _t( "_Group type" );
					//$_page_cont[$_ni]['group_type_l']    = _t( "_Group type" );
					$sGrpLTypeHelp = '<a href="'.$typeHelpLink.'" target="_blank" onclick="window.open(this.href,\'helpwin\',\'width=350,height=200\');return false;" >'._t( "_help" ).'</a>';
					//$_page_cont[$_ni]['group_type_help'] = '<a href="'.$typeHelpLink.'" target="_blank" onclick="window.open(this.href,\'helpwin\',\'width=350,height=200\');return false;" >'._t( "_help" ).'</a>';

					//info
					if ( $aGroupInfo['thumb'] and file_exists($this->sGrpGalPath . "{$iGroupID}_{$aGroupInfo['thumb']}_{$aGroupInfo['seed']}_.{$aGroupInfo['thumbExt']}" ) )
						$groupImageUrl = "{$site['groups_gallery']}{$iGroupID}_{$aGroupInfo['thumb']}_{$aGroupInfo['seed']}_.{$aGroupInfo['thumbExt']}";
					else
						$groupImageUrl = "{$site['groups_gallery']}no_pic.gif";
					
					$arrMem = getProfileInfo( $aGroupInfo['creatorID'] );
					$creatorNick = $arrMem['NickName'];

					//<!--<img src=\"$groupImageUrl\" />-->
					//$_page_cont[$_ni]['group_image']         = <<<EOF
					$sGrpVImage = <<<EOF
<a href="{$site['url']}{$this->sCurrFile}?action=gallery&ID={$iGroupID}"><img src="{$this->sSpacerIcon}" style="background-image: url({$groupImageUrl});" class="group_info_main_img" alt="" /></a>
EOF;
					$sGrpVGalLink = "<a href=\"{$site['url']}{$this->sCurrFile}?action=gallery&ID={$iGroupID}\">" . _t( "_Group gallery" ) . "</a>";
					//$_page_cont[$_ni]['group_gallery_link']  = "<a href=\"{$site['url']}{$this->sCurrFile}?action=gallery&ID={$iGroupID}\">" . _t( "_Group gallery" ) . "</a>";

					$sGrpVCreatorThumb = get_member_thumbnail( $aGroupInfo['creatorID'], 'none', false );
					//$_page_cont[$_ni]['group_creator_thumb'] = get_member_thumbnail( $aGroupInfo['creatorID'], 'none' );
					$sGrpVCreatorLink  = "<a href=\"{$site['url']}{$creatorNick}\">".htmlspecialchars_adv($creatorNick)."</a>";
					//$_page_cont[$_ni]['group_creator_link']  = "<a href=\"{$site['url']}{$creatorNick}\">".htmlspecialchars_adv($creatorNick)."</a>";

					$sGrpVCat = htmlspecialchars_adv(  $aGroupInfo['categName'] );
					//$_page_cont[$_ni]['category']            = htmlspecialchars_adv(  $aGroupInfo['categName'] );
					$sCategUrl = $this->getGroupsCategUrl($aGroupInfo['categID'], $aGroupInfo['categUri']);
					$sGrpVCatLink = "<a href=\"$sCategUrl\">{$aGroupInfo['categName']}</a>";
					//$_page_cont[$_ni]['category_link']       = "<a href=\"$sCategUrl\">{$aGroupInfo['categName']}</a>";

					$sGrpVType = _t( ( ( (int)$aGroupInfo['open_join'] and !(int)$aGroupInfo['hidden_group'] ) ? '_Public group' : '_Private group' ) );
					//$_page_cont[$_ni]['group_type']          = _t( ( ( (int)$aGroupInfo['open_join'] and !(int)$aGroupInfo['hidden_group'] ) ? '_Public group' : '_Private group' ) );
					//$sGrpVCreated = date( $date_format_php, strtotime( $aGroupInfo['created'] ) );
					$sGrpVCreated = LocaledDataTime($aGroupInfo['created_UTS']);
					//$_page_cont[$_ni]['created']             = date( $date_format_php, strtotime( $aGroupInfo['created'] ) );
					$sGrpVCountry = _t( $aPreValues['Country'][ $aGroupInfo['Country'] ]['LKey'] );
					//$_page_cont[$_ni]['country']             = _t( $aPreValues['Country'][ $aGroupInfo['Country'] ]['LKey'] );
					$sGrpVMCity = htmlspecialchars_adv( $aGroupInfo['City'] );
					//$_page_cont[$_ni]['city']                = htmlspecialchars_adv( $aGroupInfo['City'] );
					$sGrpVMCount = $aGroupInfo['membersCount'];
					//$_page_cont[$_ni]['members_count']       = $aGroupInfo['membersCount'];
					$sGrpVAbout = htmlspecialchars_adv( $aGroupInfo['About'] );
					//$_page_cont[$_ni]['group_about']         = htmlspecialchars_adv( $aGroupInfo['About'] );
					$sGrpVDesc = $aGroupInfo['Desc']; //no htmlspecialchars
					//$_page_cont[$_ni]['group_description']   = $aGroupInfo['Desc']; //no htmlspecialchars

					if( $aGroupInfo['status'] != 'Active' ) {
						//$_page_cont[$_ni]['group_status']    = _t( '_Group status' ) . ': ' .
						$sGrpVStatus = _t( '_Group status' ) . ': ' .
						  '<span style="color:red;font-weight:bold;">' . _t( '_' . $aGroupInfo['status'] ) .'</span>' .
						  " (<a href=\"{$site['url']}{$this->sCurrFile}?action=help&amp;i=8\" target=\"_blank\" onclick=\"window.open(this.href,'helpwin','width=350,height=200');return false;\">"._t( "_Explanation" )."</a>)";
					} else
						//$_page_cont[$_ni]['group_status']    = '';
						$sGrpVStatus = '';

					//end group info

					$sGrpVActions = $this->PCGroupActions($aGroupInfo, $iGroupID, $iMemberID);
					//$_page_cont[$_ni]['group_actions']       = $this->PCGroupActions($aGroupInfo);
					$sGrpVMembers = $this->PCGroupMembers($aGroupInfo, $iGroupID, $iMemberID);
					//$_page_cont[$_ni]['group_members']       = $this->PCGroupMembers($aGroupInfo);
					$sGrpVForum = $this->PCGroupForum($aGroupInfo, $iGroupID, $iMemberID);
					//$_page_cont[$_ni]['group_forum']         = $this->PCGroupForum();
				} else {
					$iNameIndex = 0;
					$sHeader = _t( "_Group is suspended" );
					$sHeaderT = _t( "_Group is suspended" );
					$sMainCode = _t( "_Sorry, group is suspended" );
				}
			}
		}

		return array($iNameIndex, $sHeader, $sHeaderT, $sMainCode, $sGrpBrd, $sGrpLCat, $sGrpLCreated, $sGrpLLocation, $sGrpLMemberCount, $sGrpLCreator, $sGrpLAbout, $sGrpLType, $sGrpLTypeHelp, $sGrpVImage, $sGrpVGalLink, $sGrpVCreatorThumb, $sGrpVCreatorLink, $sGrpVCat, $sGrpVCatLink, $sGrpVType, $sGrpVCreated, $sGrpVCountry, $sGrpVCity, $sGrpVMCount, $sGrpVAbout, $sGrpVDesc, $sGrpVStatus, $sGrpVActions, $sGrpVMembers, $sGrpVForum);
	}

	function GenMembersPage() {
		global $logged;

		if( $logged['member'] = member_auth( 0, false ) )
			$iMemberID = (int)$_COOKIE['memberID'];
		else {
			$iMemberID = 0;
			$logged['admin'] = member_auth( 1, false );
		}

		$iGroupID = (int)$_REQUEST['ID'];

		$sBreadCrumbRes = '';
		$sPaginationRes = '';
		$sShowingResultsRes = '';
		$sHeaderT = '';
		$sHeader = '';
		$sPageMainCode = '';
		$iNameIndex = 77;

		if ( !$iGroupID ) {
			Header( "Location: {$site['url']}{$this->sCurrFile}" );
			exit;
		}

		$sHeaderT = _t( "_Group members" );
		$sHeader = _t( "_Group members" );

		if ( $aGroupInfo = $this->getGroupInfo( $iGroupID ) ) {
			$aGroupInfo['Name_html'] = htmlspecialchars_adv( $aGroupInfo['Name'] );

			if ( (int)$aGroupInfo['hidden_group'] and !$this->isGroupMember( $iMemberID, $iGroupID ) and !$logged['admin'] )
				$sPageMainCode = _t( "_You cannot view group members while not a group member" );
			else {
				if( $aGroupInfo['status'] == 'Active' or $aGroupInfo['creatorID'] == $iMemberID or $logged['admin'] ) {
					$sHeader = _t( "_Group members" );
					list($sBreadCrumb, $sPagination, $sShowResult, $sMainCode) = $this->PCShowGroupMembers($iGroupID, $aGroupInfo, $iMemberID);
					$sBreadCrumbRes = $sBreadCrumb;
					$sPaginationRes = $sPagination;
					$sPageMainCode = $sMainCode;
					$sShowingResultsRes = $sShowResult;
				} else {
					$iNameIndex = 0;
					$sHeader = _t( "_Group is suspended" );
					$sHeaderT = _t( "_Group is suspended" );
					$sPageMainCode = _t( "_Sorry, group is suspended" );
				}
			}
		} else
			$sPageMainCode = _t( "_Group not found_desc" );

		return array($sHeaderT, $sHeader, $sPageMainCode, $iNameIndex, $sBreadCrumbRes, $sPaginationRes, $sShowingResultsRes);
	}

	function PCShowGroupMembers($iGroupID, $aGroupInfo, $iMemberID) {
		global $site;
		global $oTemplConfig;

		$sBreadCrumb = '';
		$sPagination = '';
		$sShowResult = '';
		$sMainCode = '';

		$sSureC = _t('_Are you sure want to delete this member?');
		$sDeleteMembC = _t('_Delete member');

		if( $_REQUEST['mode'] == 'edit' and $aGroupInfo['creatorID'] == $iMemberID ) {
			$bEditMode = true;
			$sEditModeReq = 'mode=edit&amp;';
			$sEditModeSql = "`memberID`!={$aGroupInfo['creatorID']} AND";
		} else {
			$bEditMode = false;
			$sEditModeReq = '';
			$sEditModeSql = '';
		}

		$sBreadCrumbs = <<<EOJ
<div class="groups_breadcrumbs">
	<a href="{$site['url']}">{$site['title']}</a> /
	<a href="{$site['url']}{$this->sCurrFile}">__Groups__</a> /
	<a href="{$site['url']}{$this->sCurrFile}?action=group&amp;ID={$iGroupID}">{$aGroupInfo['Name_html']}</a> /
	<span class="active_link">__Group members__</span>
</div>
EOJ;

		$sBreadCrumbs = str_replace( "__Groups__", _t( "_Groups" ), $sBreadCrumbs );
		$sBreadCrumbs = str_replace( "__Group members__", _t( "_Group members" ), $sBreadCrumbs );

		$aMemNum = db_arr( "SELECT COUNT(*) FROM `GroupsMembers` WHERE {$sEditModeSql} `groupID`={$iGroupID}  AND `status`='Active'" );

		$iTotalNum = (int)$aMemNum[0];
		if( $iTotalNum ) {
			$iPerPage = $oTemplConfig -> iGroupMembersResPerPage;
			$iPagesNum = ceil( $iTotalNum / $iPerPage );
			$iPage = (int)$_REQUEST['page'];

			if( $iPage < 1 )
				$iPage = 1;
			if( $iPage > $iPagesNum )
				$iPage = $iPagesNum;

			$iSqlFrom = ( ( $iPage - 1 ) * $iPerPage );

			$sQuerySQL = "
				SELECT
					`GroupsMembers`.`memberID`, `Profiles`.`NickName`,
					IF(`GroupsMembers`.`memberID`='{$aGroupInfo['creatorID']}', 1, 0 ) AS `isCreator`
				FROM
					`GroupsMembers`, `Profiles`
				WHERE
					{$sEditModeSql}
					`GroupsMembers`.`groupID`='{$iGroupID}' AND
					`GroupsMembers`.`status`='Active' AND
					`GroupsMembers`.`memberID`=`Profiles`.`ID`
				ORDER BY
					`isCreator` DESC,
					`GroupsMembers`.`Date` DESC
				LIMIT {$iSqlFrom}, {$iPerPage}
			";

			$vMembers = db_res( $sQuerySQL );

			$iNumOnPage = mysql_num_rows( $vMembers );
			$iShowingFrom = $iSqlFrom + 1;
			$iShowingTo   = $iSqlFrom + $iNumOnPage;

			$sShowingResults = _t( '_Showing results:', $iShowingFrom, $iShowingTo, $iTotalNum );

			if( $iPagesNum > 1 ) {
				$sPagesUrl = "{$_SERVER['PHP_SELF']}?action=group_members&{$sEditModeReq}ID={$iGroupID}&amp;page={page}";
				$sGenPagination = genPagination( $iPagesNum, $iPage, $sPagesUrl );
			}

			$sBreadCrumb = $sBreadCrumbs;
			$sPagination = $sGenPagination;
			$sShowResult = $sShowingResults;

			$sRetHtml = '<div class="clear_both"></div>';

			while( $aMemberInfo = mysql_fetch_assoc( $vMembers ) ) {
				$sMembThumb = get_member_thumbnail( $aMemberInfo['memberID'], 'none', true );
				$sMembLink = getProfileLink($aMemberInfo['memberID']);

				$sRetHtml .= <<<EOF
<div class="group_member">
	{$sMembThumb}
	<a href="{$sMembLink}">{$aMemberInfo['NickName']}</a>
EOF;

				if( (int)$aMemberInfo['isCreator'] )
					$sRetHtml .= '<div class="mygroup_leader_is">'._t('_group creator').'</div>';
				if( $bEditMode )
					$sRetHtml .= <<<EOF
<div class="group_member_edit">
	<a href="{$site['url']}group_actions.php?ID={$iGroupID}&amp;a=delmem&amp;mem={$aMemberInfo['memberID']}" onclick="return confirm('{$sSureC}')">{$sDeleteMembC}</a>
</div>
EOF;

				$sRetHtml .= '</div>';
			}
			$sRetHtml .= '<div class="clear_both"></div>';

			$sMainCode = $sRetHtml;
		} else {
			$sBreadCrumb = '';
			$sPagination = '';
			$sShowResult = '';
			$sMainCode = _t( '_Sorry, no members are found' );
		}
		return array($sBreadCrumb, $sPagination, $sShowResult, $sMainCode);
	}

	/*************************************************************************/


	///////////////////////old functional//////////////////////////////////////////////////

	//function GenMyGroups( $iMemberID ) {
	function showMyGroups( $iMemberID ) {
		global $site;

		$sNoMyGrpC = _t("_No my groups found");
		$sGroupCreatorC = _t("_group creator");

		$iMemberID = (int)$iMemberID;
		if ( !$iMemberID )
			return false;

		$aMyGroups = $this->GetMyGroups( $iMemberID );

		$sHtmlRet = '';
		if ( !$aMyGroups ) {
			$sHtmlRet .= <<<EOF
<div class="mygroups_no">{$sNoMyGrpC}</div>
EOF;
		} else {
			$sHtmlRet .= <<<EOF
<div class="mygroups_container">
	<div class="clear_both"></div>
EOF;

			foreach ( $aMyGroups as $aGroupInfo ) {
				$iGroupID = $aGroupInfo['ID'];
				$sGroupUrl = $this->getGroupUrl($aGroupInfo['ID'], $aGroupInfo['Uri']);

				if ( $aGroupInfo['thumb'] and file_exists($this->sGrpGalPath . "{$aGroupInfo['ID']}_{$aGroupInfo['thumb']}_{$aGroupInfo['seed']}_.{$aGroupInfo['thumbExt']}" ) )
					$fileGroupThumb = "{$site['groups_gallery']}{$aGroupInfo['ID']}_{$aGroupInfo['thumb']}_{$aGroupInfo['seed']}_.{$aGroupInfo['thumbExt']}";
				else
					$fileGroupThumb = "{$site['groups_gallery']}no_pic.gif";

				$sGrpImg = <<<EOF
<img class="photo1" alt="{$aGroupInfo['Name']}" src="{$this->sSpacerIcon}" style="width: 110px; height: 110px; background-image: url({$fileGroupThumb});" />
EOF;
				$sGrpName = htmlspecialchars_adv( $aGroupInfo['Name'] );
				$sHtmlRet .= <<<EOF
<div class="mygroup_container">
	<div class="mygroup_name">
		<a href="{$sGroupUrl}" class="actions">
			{$sGrpName}
		</a>
	</div>
	<div class="thumbnail_block">
		<a href="{$sGroupUrl}">
			{$sGrpImg}
		</a>
	</div>
EOF;
				if ( (int)$aGroupInfo['isCreator'] ) {
					$sHtmlRet .= <<<EOF
<div class="mygroup_leader_is">{$sGroupCreatorC}</div>
EOF;
				}

				$sHtmlRet .= '</div>';
			}

			$sHtmlRet .= '<div class="clear_both"></div></div>';
		}
		return $sHtmlRet;
	}

	function GetMyGroups( $iMemberID ) {
		$iMemberID = (int)$iMemberID;
		if ( !$iMemberID )
			return null;

		$sQuerySQL = "
			SELECT
				`Groups`.`ID`,
				`Groups`.`Name`,
				`Groups`.`Uri`,
				IF( `Groups`.`creatorID` = '{$iMemberID}', 1, 0 ) AS 'isCreator',
				`Groups`.`thumb`,
				`GroupsGallery`.`seed`,
				`GroupsGallery`.`ext` AS `thumbExt`
			FROM `GroupsMembers`, `Groups`
			LEFT JOIN `GroupsGallery` ON `Groups`.`thumb` = `GroupsGallery`.`ID`
			WHERE 
				`GroupsMembers`.`memberID` = '{$iMemberID}' AND
				`GroupsMembers`.`groupID`  = `Groups`.`ID` AND
				`GroupsMembers`.`status`   = 'Active'
		";

		$vResGroups = db_res( $sQuerySQL );

		if ( !$vResGroups or !mysql_num_rows( $vResGroups ) )
			return null;

		$aMyGroups = array();

		while ( $aGroupInfo = mysql_fetch_assoc( $vResGroups ) ) {
			$iGroupID = $aGroupInfo['ID'];
			$aMyGroups[ $iGroupID ] = $aGroupInfo;
		}

		return $aMyGroups;
	}

	function getGroupInfo( $iGroupID ) {
		$iGroupID = (int)$iGroupID;
		if ( !$iGroupID )
			return null;

		$sQuerySQL = "
			SELECT
				`Groups`.*,
				UNIX_TIMESTAMP( `Groups`.`created`) AS 'created_UTS',
				`GroupsCateg`.`Name` AS `categName`,
				`GroupsCateg`.`Uri` AS `categUri`,
				COUNT(`GroupsMembers`.`memberID`)  AS  `membersCount`,
				`GroupsGallery`.`ext`  AS  `thumbExt`,
				`GroupsGallery`.`seed`
			FROM `Groups`
			INNER JOIN `GroupsCateg` ON
				`GroupsCateg`.`ID` = `Groups`.`categID`
			LEFT JOIN `GroupsGallery` ON
				`Groups`.`thumb` = `GroupsGallery`.`ID`
			LEFT JOIN `GroupsMembers` ON
				`GroupsMembers`.`groupID` = `Groups`.`ID` AND
				`GroupsMembers`.`status` = 'Active'
			WHERE
				`Groups`.`ID` = '{$iGroupID}'
			GROUP BY `Groups`.`ID`
		";

		$aGroupInfo = db_assoc_arr( $sQuerySQL );
		return $aGroupInfo;
	}

	function isGroupNameExists( $Name ) {
		$vRes = db_res( "SELECT `Name` FROM `Groups` WHERE UPPER(`Name`)='" . addslashes(strtoupper($Name)) . "' LIMIT 1" );

		if( $vRes and mysql_num_rows( $vRes ) )
			return true;
		else
			return false;
	}

	function isGroupMember( $iMemberID, $iGroupID, $checkActiveStatus = true ) {
		/*global $aMemStatusCache;

		if( !is_array( $aMemStatusCache ) ) {
			$aMemStatusCache = array();
			$rStatus = db_res( "SELECT * FROM `GroupsMembers` ORDER BY `groupID`,`memberID`" );
			while( $aStatus = mysql_fetch_assoc( $rStatus ) )
				$aMemStatusCache[$aStatus['groupID']][$aStatus['memberID']] = $aStatus['status'];
		}*/

		$iMemberID = (int)$iMemberID;
		$iGroupID = (int)$iGroupID;

		if ( !$iMemberID or !$iGroupID )
			return false;

		$sQuerySQL = "SELECT `Status` FROM `GroupsMembers` WHERE `memberID` = '{$iMemberID}' AND `groupID` = '{$iGroupID}'" .
			( $checkActiveStatus ? " AND `Status`='Active'" : '' );

		$vRes = db_res( $sQuerySQL );
		if ( $vRes and mysql_num_rows( $vRes ) )
			return true;
		else
			return false;

		/*if( isset( $aMemStatusCache[$iGroupID][$iMemberID] ) )
			if( $checkActiveStatus )
				if( $aMemStatusCache[$iGroupID][$iMemberID] == 'Active' )
					return true;
				else
					return false;
			else
				return true;*/

		//return false;
		//echo "isMemberResults: .$result. .$result1.";
	}

	function getDefaultGroupEditArr() {
		$aFields = array(
			'Name' => array(
				'Name' => 'Name',
				'Caption' => 'Group name',
				'Type' => 'text',
				'Len' => 64
			),
			'categID' => array(
				'Name' => 'categID',
				'Caption' => 'Category',
				'Type' => 'dropdown'
			),
			'open_join' => array(
				'Name' => 'open_join',
				'Caption' => 'Open join',
				'Type' => 'bool',
				'Value' => true,
				'HelpIndex' => 1
			),
			'hidden_group' => array(
				'Name' => 'hidden_group',
				'Caption' => 'Hidden group',
				'Type' => 'bool',
				'Value' => false,
				'HelpIndex' => 2
			),
			'members_post_images' => array(
				'Name' => 'members_post_images',
				'Caption' => 'Members can post images',
				'Type' => 'bool',
				'Value' => true,
				'HelpIndex' => 3
			),
			'members_invite' => array(
				'Name' => 'members_invite',
				'Caption' => 'Members can invite',
				'Type' => 'bool',
				'Value' => true,
				'HelpIndex' => 4
			),
			'Country' => array(
				'Name' => 'Country',
				'Caption' => 'Country',
				'Type' => 'dropdown'
			),
			'City' => array(
				'Name' => 'City',
				'Caption' => 'City',
				'Type' => 'text',
				'Len' => 64
			),
			'About' => array(
				'Name' => 'About',
				'Caption' => 'About group',
				'Type' => 'text',
				'Len' => 255
			),
			'Desc' => array(
				'Name' => 'Desc',
				'Caption' => 'Group description',
				'Type' => 'html'
			)
		);
		return $aFields;
	}

	function genGroupsDropdown( $aField, $bShowChoose = true ) {
		global $aPreValues;

		$sChooseC = _t("_Choose");

		$sRes = <<<EOJ
<select name="{$aField['Name']}" class="group_edit_dropdown">
EOJ;
		switch ( $aField['Name'] ) {
			case 'Country':
				$aVals = $aPreValues['Country'];
				foreach ( $aVals as $sKey => $sVal )
					$aVals[$sKey] = htmlspecialchars_adv( _t( $sVal['LKey'] ) );
			break;
			case 'categID':
				$aVals = array();
				$rVals = db_res( "SELECT * FROM `GroupsCateg` ORDER BY `Name`" );
				while ( $aVal = mysql_fetch_assoc( $rVals ) )
					$aVals[ $aVal['ID'] ] = htmlspecialchars_adv( $aVal['Name'] );
			break;
		}

		$bSel = ($aField['Value']) ? '' : 'selected="selected"';
		$sRes .= '<option value="" '. $bSel . '>' . $sChooseC . "</option>\n";

		foreach ( $aVals as $Val => $Opt ) {
			$bSel2 = ( $aField['Value'] == $Val ? 'selected="selected"' : '' );
			$sRes .= "<option value=\"{$Val}\" {$bSel2}>{$Opt}</option>\n";
		}

		$sRes .= "</select>";
		return $sRes;
	}

	function genGroupEditForm( $aGroupInfo, $aError = false, $bShowSImg = false, $iGroupID = 0 ) {
		global $site;

		$sRetHtml = '';

		$sYesC = _t('_Yes');
		$sNoC = _t('_No');
		$sHelpC = _t('_help');
		$sEnterWhatSeeC = _t('_Enter what you see:');
		$sSubmitC = _t('_Submit');

		$sGetAction = '';
		$sPostActionForm = '';
		if ( $iGroupID ) {
			$sGetAction = "?action=edit&ID={$iGroupID}";
			$sPostActionForm = '<input type="hidden" value="edit" name="action" />';
		} else {
			$sGetAction = "?action=create";
			$sPostActionForm = '<input type="hidden" value="create" name="action" />';
		}

		$sRetHtml .= <<<EOF
<form action="{$_SERVER['PHP_SELF']}{$sGetAction}" method="POST">
	<table class="group_edit_table">
EOF;

		if ( $iGroupID ) {
			$sRetHtml .= <<<EOF
<input type="hidden" name="ID" value="{$iGroupID}" />
EOF;
		} else {
		}

		$sChecked = 'checked="checked"';
		$sNowTR = "odd";

		foreach ( $aGroupInfo as $aField ) {
			$sFieldCapt = _t( '_'.$aField['Caption'] );
			$sNameBlock = ($aError[$aField['Name']] ? 'block' : 'none');
			$sNameDVal = ($aError[$aField['Name']] ? _t( '_'.$aError[$aField['Name']] ) : '' );
			$sRetHtml .= <<<EOF
<tr class="group_edit_tr_{$sNowTR}">
	<td class="group_edit_td_label">{$sFieldCapt}:</td>
	<td class="group_edit_td_value">
		<div class="group_edit_error" style="display:{$sNameBlock}">
			{$sNameDVal}
		</div>
EOF;
			switch( $aField['Type'] ) {
				case 'text':
					$sVal = htmlspecialchars_adv($aField['Value']);
					$sRetHtml .= <<<EOF
<input type="text" name="{$aField['Name']}" class="group_edit_text" value="{$sVal}" maxlength="{$aField['Len']}" />
EOF;
				break;
				case 'bool':
					$sCheckedVal = $aField['Value'] ? $sChecked : '';
					$sCheckedVal2 = $aField['Value'] ? '' : $sChecked;
					$sRetHtml .= <<<EOF
<input type="radio" name="{$aField['Name']}" value="yes" id="{$aField['Name']}_yes" {$sCheckedVal} />
<label for="{$aField['Name']}_yes">{$sYesC}</label>
&nbsp;
<input type="radio" name="{$aField['Name']}" value="no"  id="{$aField['Name']}_no" {$sCheckedVal2} />
<label for="{$aField['Name']}_no">{$sNoC}</label>
EOF;
					if( $aField['HelpIndex'] ) {
						$sRetHtml .= <<<EOF
&nbsp; <span class="group_help_link">(<a href="{$site['url']}{$this->sCurrFile}?action=help&i={$aField['HelpIndex']}" target="_blank" onclick="window.open(this.href,'helpwin','width=350,height=200');return false;" >{$sHelpC}</a>)</span>
EOF;
					}
				break;
				case 'html':
					$sValue = htmlspecialchars_adv($aField['Value']);
					$sRetHtml .= <<<EOF
<textarea name="{$aField['Name']}" class="group_edit_html">{$sValue}</textarea>
EOF;
				break;
				case 'dropdown':
					$sRetHtml .= $this->genGroupsDropdown( $aField );
				break;
			}

			$sRetHtml .= <<<EOF
	</td>
</tr>
EOF;

			$sNowTR = ($sNowTR == "odd") ? "even" : "odd";
		}
		
		if ( $bShowSImg ) {
			$sSimgBlock = ($aError['simg'] ? 'block' : 'none');
			$sSimgL = ($aError['simg']) ? _t( '_'.$aError['simg'] ) : '';

			$sRetHtml .= <<<EOF
<tr class="group_edit_tr_{$sNowTR}">
	<td class="group_edit_td_label">{$sEnterWhatSeeC}</td>
	<td class="group_edit_td_value" style="text-align:center;">
		<div class="group_edit_error" style="display:{$sSimgBlock}">
			{$sSimgL}
		</div>
		<img src="{$site['url']}simg/simg.php"><br />
		<input type="input" name="simg" class="group_edit_simg" maxlength="6" />
	</td>
</tr>
EOF;
			$sNowTR = ($sNowTR == "odd") ? "even" : "odd";
		}

		$sRetHtml .= <<<EOF
		<tr class="group_edit_tr_{$sNowTR}">
			<td class="group_edit_td_label">&nbsp;</td>
			<td class="group_edit_td_colspan">
				<input type="submit" name="do_submit" value="{$sSubmitC}" />
				{$sPostActionForm}
			</td>
		</tr>
	</table>
</form>
EOF;

		return $sRetHtml;
	}

	function fillGroupArrByPostValues( &$aGroupInfo ) {
		foreach( $aGroupInfo as $sFieldName => $aField ) {
			switch( $aField['Type'] ) {
				case 'text':
				case 'dropdown':
					$aGroupInfo[$sFieldName]['Value'] = trim( process_pass_data( $_POST[$sFieldName] ) );
				break;
				case 'html':
					$aGroupInfo[$sFieldName]['Value'] = clear_xss( trim( process_pass_data( $_POST[$sFieldName] ) ) );
				break;
				case 'bool':
					$aGroupInfo[$sFieldName]['Value'] = (bool)( $_POST[$sFieldName] == 'yes' );
				break;
			}
		}
	}

	function fillGroupArrByDBValues( &$aFields, $aGroupInfo ) {
		foreach( $aFields as $sFieldName => $arrField ) {
			switch( $arrField['Type'] ) {
				case 'text':
				case 'html':
				case 'dropdown':
					$aFields[$sFieldName]['Value'] = $aGroupInfo[$sFieldName];
				break;
				case 'bool':
					$aFields[$sFieldName]['Value'] = (bool)(int)$aGroupInfo[$sFieldName];
				break;
			}
		}
	}

	function checkGroupErrors( &$aGroupInfo ) {
		global $aPreValues;

		$aError = array();

		foreach( $aGroupInfo as $aField ) {
			$sFieldName = $aField['Name'];
			
			switch( $aField['Type'] ) {
				case 'text':
					if( !strlen( $aGroupInfo[$sFieldName]['Value'] ) )
						$aError[ $sFieldName ] = "{$sFieldName} is required";
					else {
						if( $sFieldName == 'Name' )
							if( $this->isGroupNameExists( $aGroupInfo['Name']['Value'] ) )
								$aError[ $sFieldName ] = "Group name already exists";
					}
				break;
				case 'dropdown':
					switch( $sFieldName ) {
						case 'Country':
							$aGroupInfo['Country']['Value'] = substr( $aGroupInfo['Country']['Value'], 0, 2 );
							if( !strlen( $aGroupInfo['Country']['Value'] ) )
								$aError['Country'] = 'Country is required';
							else
								if ( !isset( $aPreValues['Country'][ $aGroupInfo['Country']['Value'] ] ) ) {
									$aError['Country'] = "Country doesn't exists";
									unset( $aGroupInfo['Country']['Value'] );
								}
						break;
						case 'categID':
							$aGroupInfo['categID']['Value'] = (int)$aGroupInfo['categID']['Value'];
							if( !$aGroupInfo['categID']['Value'] )
								$aError['categID'] = "Category is required";
							else
								if( !$this->isGroupsCategExists( $aGroupInfo['categID']['Value'] ) ) {
									$aError['categID'] = "Category doesn't exists";
									unset( $aGroupInfo['categID']['Value'] );
								}
						break;
					}
				break;
				case 'html':
					//Commented for possible modifications
					/*if( !strlen( $aGroupInfo[$sFieldName]['Value'] ) )
						$aError[ $sFieldName ] = "{$sFieldName} is required";*/
				break;
				case 'bool':
				break;
			}
		}

		return $aError;
	}

	function isGroupsCategExists( $ID ) {
		$iID = (int)$ID;

		if( !$iID )
			return false;

		$vRes = db_res( "SELECT `ID` FROM `GroupsCateg` WHERE `ID`='{$iID}'" );

		if( $vRes and mysql_num_rows( $vRes ) )
			return true;
		else
			return false;
	}

	function saveGroup( $aGroupInfo, $iGroupID = 0 ) {
		$iGroupID = (int)$iGroupID;
		$sSqlSet = 'SET ';

		foreach( $aGroupInfo as $sFieldName => $aField ) {
			switch( $aField['Type'] ) {
				case 'text':
				case 'html':
				case 'dropdown':
					$sSetValue = addslashes( $aField['Value'] );
				break;
				case 'bool':
					$sSetValue = (string)(int)$aField['Value']; //convert true -> 1, false -> 0
				break;
				default:
					$sSetValue = addslashes( $aField['Value'] );
			}
			$sSqlSet .= "`{$aField['Name']}`='{$sSetValue}', ";
		}
		$sSqlSet = substr( $sSqlSet, 0, -2 ); // remove last ", "

		if ( $iGroupID > 0 ) {
			$sQuerySQL = "UPDATE `Groups` {$sSqlSet} WHERE `ID`='{$iGroupID}'";
			db_res( $sQuerySQL );
			if( mysql_affected_rows() ) {
				$this->saveGroupForum( $iGroupID, $aGroupInfo );
				return true;
			} else
				return false;
		} else {
			$sQuerySQL = "INSERT `Groups` {$sSqlSet}, `created`=NOW()";
			db_res( $sQuerySQL );
			$iGroupID = mysql_insert_id();
			if( $iGroupID ) {
				$this->saveGroupForum( $iGroupID, $aGroupInfo, true );
				return $iGroupID;
			} else
				return false;
		}
	}

	function saveGroupForum( $iGroupID, $aGroupInfo, $bNew = false ) {
		$iGroupId = (int)$iGroupID;

		$sSqlSet = '';

		foreach( $aGroupInfo as $sFieldName => $arrField ) {
			unset( $sSetValue );

			if( $sFieldName == 'hidden_group' ) {
				$sSetColumn = "forum_type";
				$sSetValue  = ( $arrField['Value'] ) ? 'private' : 'public';
			} elseif( $sFieldName == 'Name' ) {
				$sSetColumn = 'forum_title';
				$sSetValue  = addslashes( htmlspecialchars( $arrField['Value'] ) );
			} elseif( $sFieldName == 'About' ) {
				$sSetColumn = 'forum_desc';
				$sSetValue  = addslashes( htmlspecialchars( $arrField['Value'] ) );
			} elseif( $sFieldName == 'Uri' ) {
				$sSetColumn = 'forum_uri';
				$sSetValue  = addslashes( htmlspecialchars( $arrField['Value'] ) );
			}

			if( isset( $sSetValue ) )
				$sSqlSet .= "`{$sSetColumn}` = '{$sSetValue}', ";
		}

		if( !strlen( $sSqlSet ) )
			return false;

		$sSqlSet = "SET " . substr( $sSqlSet, 0, -2 ); // remove last ", "

		if ( $bNew )
			$sQuerySQL = "INSERT `grp_forum` {$sSqlSet}, `forum_id`='{$iGroupId}', `cat_id`=1";
		else
			$sQuerySQL = "UPDATE `grp_forum` {$sSqlSet} WHERE `forum_id`='{$iGroupId}'";

		db_res( $sQuerySQL );
	}

	function addMember2Group( $iMemberID, $iGroupID, $status = 'Active' ) {
		db_res("INSERT INTO `GroupsMembers` SET `memberID`='{$iMemberID}', `groupID`='{$iGroupID}', `status`='{$status}', Date=NOW()");
	}

	function resignGroupMember( $iMemberID, $iGroupID ) {
		db_res( "DELETE FROM `GroupsMembers` WHERE `memberID`={$iMemberID} AND `groupID`={$iGroupID}" );
	}

	function compareUpdatedGroupFields( $aOldFields, $aNewFields ) {
		$aUpdFields = array();

		foreach( $aOldFields as $sFieldName => $arrOldField ) {
			if( $arrOldField['Value'] != $aNewFields[$sFieldName]['Value'] )
				$aUpdFields[$sFieldName] = $aNewFields[$sFieldName];
		}

		return $aUpdFields;
	}

	function genUploadForm( $iGroupID, $bBackHome = false, $bSetDef = false ) {
		global $site;

		$sSelectFileC = _t( '_Select file' );
		$sSubmitC = _t('_Submit');

		$sRetHtml .= <<<EOF
<div class="group_upload_form">
	<form action="{$site['url']}group_actions.php" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="ID" value="{$iGroupID}" />
		<input type="hidden" name="a" value="upload" />
EOF;

		if( $bBackHome ) {
			$sRetHtml .= <<<EOF
<input type="hidden" name="back" value="home" />
EOF;
		}

		if( $bSetDef ) {
			$sRetHtml .= <<<EOF
<input type="hidden" name="set_def" value="yes" />
EOF;
		}

		$sRetHtml .= <<<EOF
		{$sSelectFileC}<br />
		<input type="file" name="file" />
		<input type="submit" name="do_submit" value="{$sSubmitC}" />
	</form>
</div>
EOF;

		return $sRetHtml;
	}

	function setGroupThumb( $iGroupID, $vImg ) {
		$iGroupID = (int)$iGroupID;
		$iImg = (int)$vImg;

		if( $iGroupID and $iImg ) {
			$aImg = db_assoc_arr( "SELECT `ID` FROM `GroupsGallery` WHERE `groupID`='{$iGroupID}' AND `ID`='{$iImg}'" );
			if( $aImg['ID'] == $iImg ) {
				db_res( "UPDATE `Groups` SET `thumb`='{$iImg}' WHERE `ID`='{$iGroupID}'" );
			}
		}
	}

	function deleteGroupImage( $iGroupID, $vImg ) {
		$iGroupID = (int)$iGroupID;
		$iImg = (int)$vImg;

		if( $iGroupID and $iImg ) {
			$aImg = db_assoc_arr( "SELECT * FROM `GroupsGallery` WHERE `groupID`='{$iGroupID}' AND `ID`='{$iImg}'" );
			if( $aImg['ID'] == $iImg ) {
				db_res( "DELETE FROM `GroupsGallery` WHERE `ID`='{$iImg}' AND `groupID`='{$iGroupID}'" );
				unlink( $this->sGrpGalPath . "{$iGroupID}_{$iImg}_{$aImg['seed']}_.{$aImg['ext']}" );
				unlink( $this->sGrpGalPath . "{$iGroupID}_{$iImg}_{$aImg['seed']}.{$aImg['ext']}" );
			}
		}
	}

	function getGroupsCategList( $sOrderBy = 'ID' ) {
		$vCategs = db_res( "
			SELECT
				`GroupsCateg`.*,
				COUNT(`Groups`.`ID`) AS `groupsCount`
			FROM `GroupsCateg`
			LEFT JOIN `Groups`
			ON ( `Groups`.`categID` = `GroupsCateg`.`ID` AND `Groups`.`status` = 'Active' )
			GROUP BY `GroupsCateg`.`ID`
			ORDER BY `GroupsCateg`.`{$sOrderBy}`
			" );
		$aCategs = fill_assoc_array( $vCategs );
		return $aCategs;
	}

	function sendRequestToCreator( $iGroupID, $iMemberID ) {
		global $site;

		$subject = 'Group join request';
		$msg = getParam( 'group_creator_request' );

		$sQuerySQL = "
			SELECT
				`Groups`.`Name` AS `group`,
				`Profiles`.`ID` AS `creatorID`,
				`Profiles`.`NickName` AS `creator`,
				`Profiles2`.`NickName` AS `member`
			FROM `Groups`
			LEFT JOIN `Profiles`
			ON `Profiles`.`ID` = `Groups`.`creatorID`
			LEFT JOIN `Profiles` AS `Profiles2`
			ON `Profiles2`.`ID` = '{$iMemberID}'
			WHERE
				`Groups`.`ID` = '{$iGroupID}'
		";

		$arrInfo = db_arr( $sQuerySQL );

		$creatorID = (int)$arrInfo['creatorID'];

		$group   = htmlspecialchars_adv( $arrInfo['group'] );
		$creator = htmlspecialchars_adv( $arrInfo['creator'] );
		$member  = htmlspecialchars_adv( $arrInfo['member'] );

		$member  = "<a href=\"{$site['url']}{$member}\">{$member}</a>";

		$approve = "<a href=\"{$site['url']}group_actions.php?a=approve&amp;ID={$iGroupID}&amp;mem={$iMemberID}\" >approve</a>";
		$reject  = "<a href=\"{$site['url']}group_actions.php?a=reject&amp;ID={$iGroupID}&amp;mem={$iMemberID}\" >reject</a>";

		$msg = str_replace( '{group}',   $group,   $msg );
		$msg = str_replace( '{creator}', $creator, $msg );
		$msg = str_replace( '{member}',  $member,  $msg );
		$msg = str_replace( '{approve}', $approve, $msg );
		$msg = str_replace( '{reject}',  $reject,  $msg );

		$msg = addslashes( $msg );

		db_res( "INSERT INTO `Messages`
			( `Date`, `Sender`, `Recipient`, `Text`, `Subject`, `New` )
			VALUES
			( NOW(), {$iMemberID}, {$creatorID}, '{$msg}', '{$subject}', '1' )" );
	}

	function genAllCategsList() {
		global $site;

		$sRet = '';

		$arrCategs = $this->getGroupsCategList( 'Name' );

		foreach( $arrCategs as $arrCateg ) {
			$sLink = $this->getGroupsCategUrl($arrCateg['ID'], $arrCateg['Uri']);
			$sName = htmlspecialchars_adv( $arrCateg['Name'] );
			$sGroupCnt = _t('_groups count',$arrCateg['groupsCount']);

			$sRet .= <<<EOF
<div class="groups_category">
	<span class="groups_categ_name">
		<a href="{$sLink}">{$sName}</a>
	</span>
	&nbsp;<span class="groups_categ_info">({$sGroupCnt})</span>
</div>
EOF;
		}
		return $sRet;
	}

	function PageCompGroupsSearchResults( $sKeyword, $sSearchby, $iCategID, $sCountry, $sCityVal, $sSortby, $isTopGroupsPage = false ) {
		global $oTemplConfig;
		global $site;
		global $dir;
		global $tmpl;
		global $aPreValues;

		$sHtmlRet = '';

		$date_format_php = getParam('php_date_format');

		if( $sSortby == 'created' or $sSortby == 'membersCount' )
			$sortOrder = 'DESC';
		else
			$sortOrder = 'ASC';

		$aQueryWhere = array(); //array will contain search conditions combined by AND

		if( $sKeyword ) {
			if( $sSearchby == 'name' )
				$aQueryWhere[] = "UPPER(`Groups`.`Name`) LIKE '%{$sKeyword}%'";
			else
				$aQueryWhere[] = "(UPPER(`Groups`.`Name`) LIKE '%{$sKeyword}%') OR (UPPER(`Groups`.`About`) LIKE '%{$sKeyword}%') OR (UPPER(`Groups`.`Desc`) LIKE '%{$sKeyword}%')";
		}

		if( $iCategID )
			$aQueryWhere[] = "`Groups`.`categID`='{$iCategID}'";

		if( $sCountry )
			$aQueryWhere[] = "`Groups`.`Country`='{$sCountry}'";

		if( $sCityVal )
			$aQueryWhere[] = "UPPER(`Groups`.`City`) LIKE '%{$sCityVal}%'";

		$aQueryWhere[] = "`Groups`.`status` = 'Active'";

		$sQueryWhere = "WHERE (" . implode( ") AND (", $aQueryWhere ) . ")";

		if( $isTopGroupsPage )
			$SRdbTitle = _t( '_Top Groups' );
		else
			$SRdbTitle = _t( '_Groups search results' ); //SearchResultDesignBoxTitle

		$arrNum = db_arr( "SELECT COUNT(`ID`) FROM `Groups` {$sQueryWhere}" );

		$iTotalNum = (int)$arrNum[0];
		if( $iTotalNum > 0 ) {
			$iPerPage = $oTemplConfig -> iGroupsSearchResPerPage;
			$iPagesNum = ceil( $iTotalNum / $iPerPage );
			$iPage = (int)$_REQUEST['page'];

			if( $iPage < 1 )
				$iPage = 1;
			if( $iPage > $iPagesNum )
				$iPage = $iPagesNum;

			$iSqlFrom = ( ( $iPage - 1 ) * $iPerPage );

			$sQuery = "
				SELECT
					`Groups`.*,
					UNIX_TIMESTAMP( `Groups`.`created`) AS 'created_UTS',
					`GroupsCateg`.`Name` AS `categName`,
					`GroupsCateg`.`Uri` AS `categUri`,
					COUNT( `GroupsMembers`.`memberID` ) AS `membersCount`,
					`GroupsGallery`.`seed`,
					`GroupsGallery`.`ext` AS `thumbExt`
				FROM `Groups`
				INNER JOIN `GroupsCateg` ON `GroupsCateg`.`ID` = `Groups`.`categID`
				LEFT JOIN `GroupsMembers`
					ON (`GroupsMembers`.`groupID` = `Groups`.`ID` AND `GroupsMembers`.`status`='Active')
				LEFT JOIN `GroupsGallery`
					ON (`Groups`.`thumb` = `GroupsGallery`.`ID`)
				{$sQueryWhere}
				GROUP BY `Groups`.`ID`
				ORDER BY `{$sSortby}` {$sortOrder}, `Groups`.`ID` DESC
				LIMIT {$iSqlFrom}, {$iPerPage}
			";

			$vResGroups = db_res( $sQuery );

			$iNumOnPage = mysql_num_rows( $vResGroups );
			$iShowingFrom = $iSqlFrom + 1;
			$iShowingTo   = $iSqlFrom + $iNumOnPage;

			$sShowingResults = _t( '_Showing results:', $iShowingFrom, $iShowingTo, $iTotalNum );

			if( $iPagesNum > 1 and !$isTopGroupsPage ) {
				$sPagesUrl = "javascript:void(0);";
				$pagesOnclick = "switchGroupsSearchPage({page}); return false;";
				$sGenPagination = genPagination( $iPagesNum, $iPage, $sPagesUrl, $pagesOnclick );
			}

			$sRowTmpl = file_get_contents("{$dir['root']}templates/tmpl_{$tmpl}/searchrow_group.html");

			$sBreadCrumbs = '';
			if( $iCategID ) {
				$arrCateg = db_arr( "SELECT `Name` FROM `GroupsCateg` WHERE `ID`='{$iCategID}'" );
				if( $arrCateg['Name'] ) {
					$sCategName = _t( '_Category' ).': '.htmlspecialchars_adv( $arrCateg['Name'] );
					$SRdbTitle = $sCategName;

					$sGroupsUrl = $this->bUseFriendlyLinks ? 'groups/all' : $this->sCurrFile ;
					$sBreadCrumbs = <<<EOJ
<div class="groups_breadcrumbs">
	<a href="{$site['url']}">{$site['title']}</a> /
	<a href="{$site['url']}{$sGroupsUrl}">__Groups__</a> /
	<span class="active_link">{$sCategName}</span>
</div>
EOJ;

					$sBreadCrumbs = str_replace( "__Groups__", _t( "_Groups" ), $sBreadCrumbs );
				}
			}

			if( !$isTopGroupsPage ) {
				$sHtmlRet .= <<<EOF
{$sBreadCrumbs}

<script type="text/javascript" language="javascript">
	function switchGroupsSearchPage(page) {
		_form = document.forms.groups_search_form;
		if( !_form )
			return false;

		_form.keyword.value = keyword;
		_form.categID.value = categID;
		_form.Country.value = Country;
		_form.City.value    = City;

		for( i = 0; i < _form.searchby.length; i ++ )
			if( _form.searchby[i].value == searchby )
				_form.searchby[i].checked = true;

		for( i = 0; i < _form.sortby.length; i ++ )
			if( _form.sortby[i].value == sortby )
				_form.sortby[i].checked = true;

		_form.page.value = page;

		_form.submit();
		return true;
	}
</script>

<div class="groups_showing_results">
	{$sShowingResults}
</div>
<div class="groups_pagination">
	{$sGenPagination}
</div>
EOF;
			}

			$sHtmlRet .= <<<EOF
<div class="groups_result_wrapper">
EOF;

			while( $aGroupInfo = mysql_fetch_assoc( $vResGroups ) ) {
				$sGroupLink = $this->getGroupUrl($aGroupInfo['ID'], $aGroupInfo['Uri']);
				$sCategLink = $this->getGroupsCategUrl($aGroupInfo['categID'],$aGroupInfo['categUri']);

				$aRowTmpl = array();

				if ( $aGroupInfo['thumb'] and file_exists($this->sGrpGalPath . "{$aGroupInfo['ID']}_{$aGroupInfo['thumb']}_{$aGroupInfo['seed']}_.{$aGroupInfo['thumbExt']}" ) )
					$groupImageUrl = "{$site['groups_gallery']}{$aGroupInfo['ID']}_{$aGroupInfo['thumb']}_{$aGroupInfo['seed']}_.{$aGroupInfo['thumbExt']}";
				else
					$groupImageUrl = "{$site['groups_gallery']}no_pic.gif";

				if( (int)$aGroupInfo['hidden_group'] )
					$typeHelp = 7;
				else
					if( (int)$aGroupInfo['open_join'] )
						$typeHelp = 5;
					else
						$typeHelp = 6;

				$typeHelpLink = "{$site['url']}{$this->sCurrFile}?action=help&amp;i=$typeHelp";

				$aRowTmpl['group_name_l']    = _t( '_Group name' );
				$aRowTmpl['category_l']      = _t( '_Category' );
				$aRowTmpl['about_group_l']   = _t( '_About group' );
				$aRowTmpl['members_count_l'] = _t( '_Members count' );
				$aRowTmpl['created_l']       = _t( '_Created' );
				$aRowTmpl['group_type_l']    = _t( '_Group type' );
				$aRowTmpl['location_l']      = _t( '_Location' );
				$aRowTmpl['group_type_help'] = '<a href="'.$typeHelpLink.'" target="_blank" onclick="window.open(this.href,\'helpwin\',\'width=350,height=200\');return false;" >'._t( "_help" ).'</a>';

				$sGroupName = htmlspecialchars_adv( $aGroupInfo['Name'] );
				$aRowTmpl['thumbnail']     = <<<EOF
<a href="{$sGroupLink}">
	<img src="{$this->sSpacerIcon}" style="width:110px;height:110px; background-image: url({$groupImageUrl});" class="photo1" alt="{$sGroupName}" />
</a>
EOF;
				/*$aRowTmpl['thumbnail']     = "<!--<div class=\"group_thumb\">--><a href=\"$sGroupLink\">
												<!--<img src=\"{$groupImageUrl}\" />-->
												<img src=\"{$this->sSpacerIcon}\" style=\"width:110px;height:110px; background-image: url({$groupImageUrl});\" class=\"photo1\" alt=\"{$sGroupName}\" />
												</a><!--</div>-->";*/
				$aRowTmpl['group_name']    = "<a class=\"actions\" href=\"{$sGroupLink}\">".$sGroupName."</a>";
				$aRowTmpl['group_about']   = htmlspecialchars_adv( $aGroupInfo['About'] );
				$aRowTmpl['category']      = "<a href=\"{$sCategLink}\">".htmlspecialchars_adv( $aGroupInfo['categName'] )."</a>";
				$aRowTmpl['members_count'] = $aGroupInfo['membersCount'];
				//$aRowTmpl['created']       = date( $date_format_php, strtotime( $aGroupInfo['created'] ) );
				$aRowTmpl['created']       = LocaledDataTime($aGroupInfo['created_UTS']);
				$aRowTmpl['group_type']    = _t( ( ( (int)$aGroupInfo['open_join'] and !(int)$aGroupInfo['hidden_group'] ) ? '_Public group' : '_Private group' ) );
				$aRowTmpl['country']       = _t( $aPreValues['Country'][ $aGroupInfo['Country'] ]['LKey'] );
				$aRowTmpl['city']          = htmlspecialchars_adv( $aGroupInfo['City'] );

				$sRow = $sRowTmpl;
				foreach( $aRowTmpl as $what => $to )
					$sRow = str_replace( "__{$what}__", $to, $sRow );

				$sHtmlRet .= $sRow;
			}

			$sHtmlRet .= <<<EOF
	<div class="clear_both"></div>
</div>
EOF;

			if( !$isTopGroupsPage ) {
				$sHtmlRet .= <<<EOF
<div class="groups_showing_results">
	{$sShowingResults}
</div>
<div class="groups_pagination">
	{$sGenPagination}
</div>
EOF;
			}
			$sRet = $sHtmlRet;
		} else {
			$sRet = MsgBox(_t( '_Sorry, no groups found' ));
		}

		return DesignBoxContent( $SRdbTitle, $sRet, $oTemplConfig->iGroupsSearchResults_dbnum );
	}

	function getGroupIdByUri($sUri) {
		$sUri = process_db_input($sUri);
		$sqlQuery = "SELECT `ID` FROM `Groups` WHERE `Uri`='{$sUri}'";
		$iId = (int)db_value($sqlQuery);
		return $iId;
	}

	function getGroupUrl($iGroupId, $sGroupUri) {
		$sLink = $this->bUseFriendlyLinks ? 'groups/entry/'.$sGroupUri : $this->sCurrFile.'?action=group&ID='.$iGroupId;
		return $GLOBALS['site']['url'].$sLink;
	}

	function getGroupsCategUrl($iCatId, $sCatUri) {
		$sLink = $this->bUseFriendlyLinks ? 'groups/category/'.$sCatUri : $this->sCurrFile.'?action=categ&amp;categID='.$iCatId;
		return $GLOBALS['site']['url'].$sLink;
	}

	function GetGroupPicture($iGroupID) {
		global $dir;
		global $site;

		$sPicNotAvail = getTemplateIcon( 'group_no_pic.gif' );
		$sRequest = "SELECT `thumb`, `Uri` FROM `Groups` WHERE `ID` = {$iGroupID} LIMIT 1";

		$aResPic = db_arr($sRequest);
		$iGroupPicID = (int)$aResPic['thumb'];
		$sRequest = "SELECT * FROM `GroupsGallery` WHERE `ID` = {$iGroupPicID}";
		$aResPicName = db_arr($sRequest);
		$sPicName = $aResPicName['groupID'].'_'.$aResPicName['ID'].'_'.$aResPicName['seed'].'_icon.'.$aResPicName['ext'];
		$iThumbSize = 45;
		$sEventPicName = $sPicName;

		$sGroupLink = $this->getGroupUrl($iGroupID, $aResPic['Uri']);

		$sEventPicName = ( strlen(trim($sEventPicName)) && file_exists('groups/gallery/' . $sEventPicName) )
				? "<img class=\"icons\" alt=\"\" style=\"width:{$iThumbSize}px;height:{$iThumbSize}px;background-image:url(groups/gallery/{$sTypePic}{$sEventPicName});\" src=\"{$this->sSpacerIcon}\" />"
				: "<img class=\"icons\" alt=\"\" style=\"width:{$iThumbSize}px;height:{$iThumbSize}px;background-image:url({$sPicNotAvail});\" src=\"{$this->sSpacerIcon}\" />";
		$sEventPic = <<<EOF
<div  class="thumbnail_block" style="float:left;">
	<a href="{$sGroupLink}">
		{$sEventPicName}
	</a>
</div>
EOF;
		return $sEventPic;
	}

	function GenAnyBlockContent($sOrder='latest', $iProfileID=0, $sLimit="LIMIT 5" ) {
		global $site;

		$php_date_format = getParam( 'php_date_format' );
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		$sClockIcon = getTemplateIcon( 'clock.gif' );

		$sOrderS = '';
		switch ($sOrder) {
			case 'latest':
				$sOrderS = 'ORDER BY `Groups`.`created` DESC';
			break;
			case 'rand':
				$sOrderS = 'ORDER BY RAND()';
			break;
		}
		$sProfileS = '1';
		$sGrpJoin = '';
		if ($iProfileID>0) {
			$sGrpJoin = 'INNER JOIN `GroupsMembers` ON `GroupsMembers`.`groupID`=`Groups`.`ID` ';
			$sProfileS = <<<EOF
`GroupsMembers`.`memberID` = '{$iProfileID}' AND
`GroupsMembers`.`status`   = 'Active'
EOF;
		} else {
			$sProfileS = "`Groups`.`Status` = 'Active'";
		}

		//INNER JOIN `GroupsCateg` ON `GroupsCateg`.`ID` = `Groups`.`categID` 
		//`GroupsCateg`.`Name` AS 'CategName', `GroupsCateg`.`Uri` as `CategUri`
		$sQuery = "
			SELECT DISTINCT `Groups`.`ID`, `Groups`.`Name`, `Groups`.`Uri`, `Groups`.`categID` AS `CategID`,
			LEFT(`Groups`.`Desc`, {$iBlogLimitChars}) as 'Desc_f', 
			UNIX_TIMESTAMP( `Groups`.`created` ) as `DateTime_f`,
			`Profiles`.`NickName`
			FROM `Groups`
			INNER JOIN `Profiles` ON `Profiles`.`ID` = `Groups`.`creatorID` 
			{$sGrpJoin}
			WHERE
			{$sProfileS}
			{$sOrderS}
			{$sLimit}
		";

		$rBlog = db_res( $sQuery );
		if( !mysql_num_rows( $rBlog ) )
			return MsgBox(_t( '_Sorry, nothing found' ));

		$sRet = '';
		$sFolderIco = getTemplateIcon( 'folder_small.png' );
		//$sRet .= '<div class="clear_both"></div>';
		while ($arr = mysql_fetch_array($rBlog)) {
			$sPic = $this->GetGroupPicture($arr['ID']);
			$aCategInfo = db_arr("SELECT `GroupsCateg`.`Name` AS 'CategName', `GroupsCateg`.`Uri` as `CategUri` FROM `GroupsCateg` WHERE `ID`='{$arr['CategID']}' LIMIT 1;");
			$sCategName = $aCategInfo['CategName'];
			$sCategUri = $aCategInfo['CategUri'];

			$sLinkMore = '';
			$sGroupLink = $this->getGroupUrl($arr['ID'], $arr['Uri']);
			if ( strlen($arr['Desc']) == $iBlogLimitChars ) 
				$sLinkMore = "... <a href=\"".$sGroupLink."\">"._t('_Read more')."</a>";

			$sAuthor = ($iProfileID>0) ? '' : '<span>' . _t( '_By Author', $arr['NickName'], $arr['NickName'] ) . '</span>';
			$sName = process_line_output( $arr['Name'] );
			//$sDateF = date( $php_date_format, $arr['DateTime_f'] );
			$sDateF = LocaledDataTime($arr['DateTime_f']);
			//$sDateF = LocaledDataTime( $arr['DateTime_f'], 3 );
			$sCategory = _t( '_in Category', $sFolderIco, $this->getGroupsCategUrl($arr['CategID'], $sCategUri), process_line_output($sCategName) );
			$sDescr = strip_tags(process_html_output( $arr['Desc_f'] ));

			$sRet .= <<<EOF
<div>
	<div class="icon_block">
		{$sPic}
	</div>
	<div class="blog_wrapper_n">
		<div class="blog_subject_n">
			<a href="{$sGroupLink}" class="bottom_text">
				{$sName}
			</a>
		</div>
		<div class="blogInfo">
			{$sAuthor}
			<span>
				<img src="{$sClockIcon}" alt="" />{$sDateF}
			</span>
			<span>{$sCategory}</span>
		</div>
		<div class="blog_text">
			{$sDescr}{$sLinkMore}
		</div>
	</div>
</div>
<div class="clear_both"></div>
EOF;
		}
		if ($sBlocks == '') $sBlocks = MsgBox(_t('_Sorry, nothing found'));
		return $sRet;
	}

	function process_html_db_input( $sText ) {
		return addslashes( clear_xss( trim( process_pass_data( $sText ))));
	}
}
?>