<?
require_once(BX_DIRECTORY_PATH_INC . 'header.inc.php' );
require_once(BX_DIRECTORY_PATH_INC . 'db.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'utils.inc.php');
require_once(BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplVotingView.php" );
require_once(BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplCmtsView.php" );

class BxDolSharedMedia {
	
	// can be music, photo, video
	var $sType;

	// name of css file
	var $sCssName;
	
	// viewer ID
	var $iViewer;
	// viewer password
	var $sViewerPass;
	
	// config dir array - copy of global $dir array
	var $aConfigDir;
	
	// config site array - copy of global $dir array
	var $aConfigSite;
	
	// path to media files
	var $sFilesPath;
	
	// URL to media files
	var $sFilesUrl;
	
	// permalink parametername
	var $sPrLinkPar;
	
	// name of section's main table
	var $sMainTable;
	
	// name of main table's fields
	var $aTableFields;
	
	// name of comments table
	var $sCommentsTable;
	
	// name of favorite table
	var $sFavoriteTable;
	
	// actions array
	var $aMainActions = array(
		'Fave'  => array('icon'=>'action_fave.gif',  'link'=>'javascript:void(0);', 'onClick'=>'action=favorite__fileID__'),
		'Share' => array('icon'=>'action_share.gif', 'link'=>'javascript:void(0);', 'onClick'=>'action=share__fileID____fileUrl__'),
		'Report'=> array('icon'=>'action_report.gif','link'=>'javascript:void(0);', 'onClick'=>'action=report__fileID____fileUrl__')
	);
	
	// addon actions array
	var $aAddActions;
	
	// membership action for view file
	var $sViewActionName;
	
	// array of edited fields
	var $aInfo;
	
	/*
	 	constructor
		* @param string $sMediaType	- type of application
		* @param aSite $iFile	- ID of a file
		* @param int $iFile	- ID of a file
		* @param int $iFile	- ID of a file	
		* @return array
	*/
	
	function BxDolSharedMedia($sMediaType, &$aSite, &$aDir, &$aMember) {
		$sMediaType = process_db_input($sMediaType);
		$sName = ucfirst($sMediaType);
		
		$this->aConfigSite = $aSite;
		$this->aConfigDir  = $aDir;

		$this->sType       = $sMediaType;
		$this->sCssName    = 'view'.$sName.'.css';
		$this->iViewer     = (int)$aMember['ID'];
		$this->sViewerPass = $aMember['Password'];
		
		$this->sCommentsTable = 'CmtsShared'.$sName;
		$this->sFavoriteTable = 'share'.$sName.'Favorites';
		
		$this->aEditInfo = array('medProfId'=>'', 'medTitle'=>'Title', 'medTags'=>'Tags', 'medDesc'=>'Description', 'medUri'=>'');

		
		switch($sMediaType) {
			case 'photo':
				$this->sPrLinkPar = 'permalinks_gallery_photos';
				$this->sFilesPath = $this->aConfigDir['sharingImages'];
				$this->sFilesUrl  = $this->aConfigSite['sharingImages'];
				$this->sMainTable = 'sharePhotoFiles';
				
				$this->sViewActionName = ACTION_ID_VIEW_GALLERY_PHOTO;
				
				$this->aTableFields = array(
					'medID'    => 'medID',
					'medProfId'=> 'medProfId',
					'medExt'   => 'medExt',
					'medTitle' => 'medTitle',
					'medUri'   => 'medUri',
					'medDesc'  => 'medDesc',
					'medTags'  => 'medTags',
					'medDate'  => 'medDate',
					'medViews' => 'medViews',
					'Approved' => 'Approved'
				);
				
				$this->aAddActions = array(
					'Original_Size'=>array( 'icon'=>'action_download.gif','link'=>$this->sFilesUrl.'__file__','onClick'=>'', 'add' => 'target="_blank"')
				);
				
				break;
				
			case 'music':
				$this->sPrLinkPar = 'permalinks_gallery_music';
				$sAddPath 		  = 'ray/modules/music/files/';
				$this->sFilesPath = BX_DIRECTORY_PATH_ROOT . $sAddPath;
				$this->sFilesUrl  = $this->aConfigSite['url'] . $sAddPath;
				$this->sMainTable = 'RayMusicFiles';
				
				$this->sViewActionName = ACTION_ID_VIEW_GALLERY_MUSIC;
					
				$this->aTableFields = array(
					'medID'    => 'ID',
					'medProfId'=> 'Owner',
					'medTitle' => 'Title',
					'medUri'   => 'Uri',
					'medDesc'  => 'Description',
					'medTags'  => 'Tags',
					'medDate'  => 'Date',
					'medViews' => 'Listens',
					'Approved' => 'Approved'
				);
				
				break;	
			
			case 'video':
				$this->sPrLinkPar = 'permalinks_gallery_videos';
				$sAddPath 		  = 'ray/modules/movie/files/';
				$this->sFilesPath = BX_DIRECTORY_PATH_ROOT . $sAddPath;
				$this->sFilesUrl  = $this->aConfigSite['url'] . $sAddPath;
				$this->sMainTable = 'RayMovieFiles';

				$this->sViewActionName = ACTION_ID_VIEW_GALLERY_VIDEO;
				
				$this->aTableFields = array(
					'medID'    => 'ID',
					'medProfId'=> 'Owner',
					'medTitle' => 'Title',
					'medUri'   => 'Uri',
					'medDesc'  => 'Description',
					'medTags'  => 'Tags',
					'medDate'  => 'Date',
					'medViews' => 'Views',
					'Approved' => 'Approved'
				);
				
				break;	
		}
	}
	
	/*
		Get info about file from corresponding media table
		* @param int $iFile	- ID of a file
		* @return array $aFile
	*/
	
	function getFileInfo($iFile) {
		$iFile = (int)$iFile;
		
		$sqlQuery = "SELECT ";
		foreach ($this->aTableFields as $sKey=>$sVal) {
			$sqlQuery .= "`{$this->sMainTable}`.`$sVal` as `$sKey`, ";
		}
		
		$sqlQuery .= " 
				COUNT(`share1`.`{$this->aTableFields['medID']}`) as `medCount`, 
				`Profiles`.`NickName`
			FROM `{$this->sMainTable}`
			LEFT JOIN `{$this->sMainTable}` as `share1` USING (`{$this->aTableFields['medProfId']}`)
			INNER JOIN `Profiles` ON `Profiles`.`ID`=`{$this->sMainTable}`.`{$this->aTableFields['medProfId']}`
			WHERE `{$this->sMainTable}`.`{$this->aTableFields['medID']}` = $iFile
			GROUP BY `share1`.`{$this->aTableFields['medProfId']}`
			";
		
		$aFile = db_arr($sqlQuery);	
		
		return $aFile;
	}
	
	/*
		Show media file
		* @param int $iFile	- ID of a file
		* @return string $sCode - html output
	*/
	
	function showFile($iFile) {
		$iFile = (int)$iFile;
		$sCode = '';
		
		switch ($this->sType) {
			case 'photo':
				$aFile  = $this->getFileInfo($iFile);
				$sImage = $this->sFilesUrl.$iFile.'_m.'.$aFile[$this->aTableFields['medExt']];
				$sCode  = '<div id="viewFile" style="background-image: url(\''.$sImage.'\')">&nbsp;</div>';
				break;
			case 'music':
				$sCode  = '<div class="viewFile" style="text-align:center;">'.getApplicationContent('music','player',array('id'=>$iFile,'password'=>$this->sViewerPass,'vId'=>$this->iViewer,'song'=>'true'),true).'</div>';
				break;
			case 'video':
				$sCode  = '<div class="viewFile" style="text-align:center;">'.getApplicationContent('movie','player',array('id' => $iFile, 'file' => 'true'),true).'</div>';
				break;
		}
		
		return $sCode;
	}
    
    /*
		Show file info block
		* @param array aFile - file info array
		* @return string $sCode - html output
	*/
	
	function showFileInfo($aFile) {
		$sTitle = strlen($aFile['medTitle']) > 0 ? $aFile['medTitle'] : _t("_Untitled");
		$iTime  = defineTimeInterval($aFile['medDate']);
		
		$sNumberAlt = _t("_Views");
		
		switch ($this->sType) {
			case 'photo':
				$sView = _t("_Photos");
				$sEmbedCode = '<img src="'.$this->sFilesUrl.$aFile['medID'].'.'.$aFile['medExt'].'">';
				break;
			case 'music':
				$sView = _t("_Music files");
				$sNumberAlt = _t("_Playbacks");
				$sEmbedCode = getEmbedCode('music', 'player', array('id'=>$aFile['medID'],'song'=>'true'));
				break;
			case 'video':
				$sView = _t("_Videos");
				$sEmbedCode = getEmbedCode('movie', 'player', array('file'=>$aFile['medID']));
				break;
		}
		
		
		if ($aFile['medCount'] - 1 > 0)
			$sLinkMore = '<a href="'.$this->getMoreFilesUrl($aFile['medProfId'], $aFile['NickName']).'">'.$aFile['medCount'].'</a>';
		else
			$sLinkMore = $aFile['medCount'];
		ob_start();
		?>
		<div id="videoInfo">
			<div id="fileTop">
				<div class="fileTitle"><?=$sTitle?></div>
				<div class="userPic">
					<?=get_member_icon($aFile['medProfId'],'left')?>
				</div>
				<div class="fileUserInfo">
					<a href="<?=getProfileLink($aFile['medProfId'])?>"><?=$aFile['NickName']?></a>
				</div>
				<div>
					<?=$sView?>: <b><?=$sLinkMore?></b>
				</div>
			</div>
			<div class="clear_both"></div>
			<div id="serviceInfo">
				<div>
					<?=_t("_Added")?>: <b><?=defineTimeInterval($aFile['medDate'])?></b>
				</div>
				<div>
					<?=$sNumberAlt?>: <?=$aFile['medViews']?>
				</div>
				<div>
					<?=_t("_URL")?>: 
					<input type="text" onClick="this.focus(); this.select();" readonly="true" value="<?=$this->getFileUrl($aFile['medID'], $aFile['medUri'])?>"/>
				</div>
				<div>
					<?=_t("_Embed")?>: 
					<input type="text" onClick="this.focus(); this.select();" readonly="true" value="<?=htmlspecialchars($sEmbedCode)?>"/>
				</div>
				<div>
					<?=_t("_Tags")?>: 
					<?=$this->getTagLinks($aFile['medTags'])?>
				</div>
				<div>
					<?=_t("_DescriptionMedia")?>: 
					<?=$aFile['medDesc']?>
				</div>
			</div>
		</div>
		<?
		$sCode = ob_get_clean();
		
		return $sCode;
	}
	
	/*
		Permalink checkin
		* @return true of false
	*/
	
	function isPermalinkEnabled() {
        return isset($this->_isPermalinkEnabled) ? $this->_isPermalinkEnabled : ($this->_isPermalinkEnabled = (getParam($this->sPrLinkPar) == 'on'));
    }
    
    /*
    	Get media file URL
    	* @param int $iFileId - ID of file
    	* @param string $sFileUri - Uri of file
    	* @return string $sLink - full URL of file page
    */
    
    function getFileUrl($iFileId, $sFileUri) {
		if ($this->isPermalinkEnabled())
			$sLink = $this->sType.'/gallery/'.$sFileUri;
		else
			$sLink = 'view'.ucfirst($this->sType).'.php?fileID='.$iFileId;

		return $GLOBALS['site']['url'].$sLink;
	}
	
	 /*
    	Get more file from this user URL
    	* @param int $iUserId - user ID
    	* @param string $sNickName - fiel NickName
    	* @return string $sLinkMoreUrl - full URL of file page
    */

	function getMoreFilesUrl($iUserId, $sNickName) {
		if ($this->isPermalinkEnabled())
			$sLinkMoreUrl = $this->sType.'/gallery/all/'.$sNickName;
		else
			$sLinkMoreUrl = 'browse'.ucfirst($this->sType).'.php?userID='.$iUserId;
	
		return $sLinkMoreUrl;
	}
	
	/*
    	Get tags URL
    	* @param string $sTagList - all tags of file
    	* @param string $sNickName - fiel NickName
    	* @return string $sLinkMoreUrl - full URL of file page
    */
	
	function getTagLinks($sTagList) {
		if (strlen($sTagList)) {
			$aTags = explode(' ', $sTagList);
			foreach ($aTags as $iKey => $sVal) {
				$sVal   = trim($sVal,',');
				$sLink = $this->isPermalinkEnabled() ? $this->sType.'/gallery_tag/'.$sVal : 'browse'.ucfirst($this->sType).'.php?tag='.$sVal;
				$sCode .= '<a href="'.$GLOBALS['site']['url'].$sLink.'">'.$sVal.'</a> ';
			}
		}
		
		return $sCode;
	}
	
	/* 
		Show rate section
		* @param int $iFile - file ID
		* return $sCode - html output
		
	*/
	
	function showRateSection($iFile) {
		$sCode = '<center>' . _t('_Gallery video rating is not enabled') . '</center>';
  	
		$oVotingView = new BxTemplVotingView ('g'.$this->sType, (int)$iFile);
    	if( $oVotingView->isEnabled())
        	$sCode = $oVotingView->getBigVoting ();

		return $sCode;
	}
	
	/*
		Show another latest files from user
		* @param array $aFile - current file info
		* return $sCode - html output
	*/

	function showLatestFiles($aFile) {
		$iLimit  = 2;
		$sCode   = '';
		$sqlBody = '';

		$sNumberAlt = _t("_Views");
		
		$sHeadTempl = '<div class="lastFilesPic">
							<a href="__link__">__image__</a>
					   </div>';
		
		
		switch ($this->sType) {
			case 'photo':
				$sMoreFilesAlt = _t("_See all photos of this user");
				$sHeadTempl = '<a href="__link__">__image__</a>';
				$sImage = '<img class="lastFilesPic"
				 style="background-image: url(\''.$this->sFilesUrl.'__image__\');" src="'.getTemplateIcon( 'spacer.gif' ) . '" />';
				break;
			case 'music':
				$sMoreFilesAlt = _t("_See all music of this user");
				$sNumberAlt    = _t("_Playbacks");
				$sImage = '<img src="'.$this->aConfigSite['images'].'music.png">';
				break;
			case 'video':
				$sMoreFilesAlt = _t("_See all videos of this user");
				$sImage = '<img src="'.$this->sFilesUrl.'__image___small.jpg">';
				break;
		}
		
		if ($aFile['medCount'] - 1 > $iLimit)
			$sLinkMore = '<a href="'.$this->getMoreFilesUrl($aFile['medProfId'], $aFile['NickName']).'">'.$sMoreFilesAlt.'</a>';
		
		foreach ($this->aTableFields as $sKey => $sVal) {
			$sqlBody .= "`{$this->sMainTable}`.`$sVal` as `$sKey`,";
		}
		$sqlQuery = "SELECT ".rtrim($sqlBody, ',')." FROM `{$this->sMainTable}`
			WHERE `{$this->aTableFields['medID']}`<>{$aFile['medID']} 
			  AND `{$this->aTableFields['medProfId']}`={$aFile['medProfId']}
			  AND `{$this->aTableFields['Approved']}`='true'
			ORDER BY `{$this->aTableFields['medDate']}` DESC
			LIMIT $iLimit
			";
		
		$rLast = db_res($sqlQuery);
		
		while ($aLast = mysql_fetch_assoc($rLast)) {
			$sImagePatt  = isset($aLast['medExt']) ? $aLast['medID'].'_t.'.$aLast['medExt'] : $aLast['medID'] ;
			$sImageBlock = str_replace('__image__', $sImagePatt, $sImage);
			$sFileUrl = $this->getFileUrl($aLast['medID'], $aLast['medUri']);
			
			$sHead = str_replace('__link__', $sFileUrl, $sHeadTempl);
			$sHead = str_replace('__image__', $sImageBlock, $sHead);
			
			$sTitle = strlen($aLast['medTitle']) > 0 ? $aLast['medTitle'] : _t("_Untitled");
			$oVotingView = new BxTemplVotingView ('g'.$this->sType, $aLast['medID']);
			
			if( $oVotingView->isEnabled() ) {
				$sRate = $oVotingView->getSmallVoting(0);
				$sShowRate = '<div class="galleryRate">'. $sRate . '</div>';
			}
			ob_start();
			?>
			<div class="lastFileUnit">
				<?=$sHead?>
				<div>
					<a href="<?=$sFileUrl?>"><b><?=$sTitle?></b></a>
				</div>
				<div><?=_t("_Added")?>: <b><?=defineTimeInterval($aLast['medDate'])?></b></div>
				<div><?=$sNumberAlt?>: <?=$aLast['medViews']?></div>
				<?=$sShowRate?>
			</div>
			<div class="clear_both"></div>
			<?
			$sCode .= ob_get_clean();
		}
		$sCode .= '<div class="lastFilesLink">'.$sLinkMore.'</div>';
		
		return $sCode;
	}
	
	/*
		Show fiel action list
		* @param array $aFile - current file info
		* return $sCode - html output
	*/
	
	function showActionList($aFile) {
		if ($this->iViewer) {
			if ($this->sType =='photo')
					$this->aAddActions['Original_Size']['link'] = str_replace('__file__', $aFile['medID'].'.'.$aFile['medExt'], $this->aAddActions['Original_Size']['link']);
			
			if ($aFile['medProfId'] == $this->iViewer) {
				$aOtherActions = array(
					'Edit'=>array('icon'=>'edit.gif', 'link'=>'javascript:void(0);', 'onClick'=>'action=edit__fileID__')
				);
			}
			$aActions = is_array($this->aAddActions) ? array_merge($this->aMainActions, $this->aAddActions) : $this->aMainActions;
			$aActions = is_array($aOtherActions)     ? array_merge($aActions, $aOtherActions) : $aActions;
			
			$sOnClickTempl = "javascript: window.open( '{$this->aConfigSite['url']}mediaActions.php?{action}', 'photo', 'width=500, height=380, menubar=no,status=no,resizable=yes,scrollbars=yes,toolbar=no,location=no' );";
			$sCode = '<div id="actionList">';
			foreach ($aActions as $sKey => $aVal) {
				$sTarget  = isset($aVal['add']) ? $aVal['add'] : '';
				$sOnClick = strlen($aVal['onClick']) > 0 ? 'onclick="' . str_replace('{action}', $aVal['onClick'], $sOnClickTempl) . '"' : '' ;
				
				$sOnClick = str_replace('__fileID__',  '&amp;fileID='.$aFile['medID'].'&amp;type='.$this->sType, $sOnClick);
				$sOnClick = str_replace('__fileUrl__', '&amp;fileUrl='.urlencode($this->getFileUrl($aFile['medID'], $aFile['medUri'])), $sOnClick);
				
				$sCode .= '<div><img src="'.$this->aConfigSite['icons'].$aVal['icon'].'" alt="'._t('_'.$sKey).'" /><a href="'.$aVal['link'].'" '.$sTarget.' '.$sOnClick.'>'._t('_'.$sKey).'</a></div>';
			}
			$sCode .= '</div><div class="clear_both"></div>';
			
			return $sCode;
		}
	}
	
	/*
		Get several files info
		* @param array aCond - array of MySQL parts
		* @param array aManage (if exists) - array of exact fields
		* @return resource rData
	*/
	
	function getFilesList($aCond = array(), $aManage = array()) {
		if (empty($aManage))
			$aList = array('medID', 'medProfId', 'medTitle', 'medUri', 'medDate', 'medViews', 'medExt');
		else
			$aList = $aManage;

		$sqlTempl = "SELECT __main__ __rate_fields__ __from__ __main_join__ __rate_join__ __where__ __order__ __limit__";
		
		$aSql = array(
				'__main__'=>'', 
				'__rate_fields__'=>'',
				'__from__'=>'',
				'__main_join__'=>'',
				'__rate_join__'=>'',
				'__where__'=>'',
				'__order__'=>'',
				'__limit__'=>''
			);

		
		foreach ($aList as $sVal) {
			$aSql['__main__'] .= key_exists($sVal, $this->aTableFields) ? "`{$this->sMainTable}`.`{$this->aTableFields[$sVal]}` as `$sVal`, " : "";
		}
		$aSql['__main__'] .= '`Profiles`.`NickName`';
		
		if (isset($aCond['rateFields']) && isset($aCond['rateJoin'])) {
			$aSql['__rate_fields__'] = $aCond['rateFields'];
			$aSql['__rate_join__']   = $aCond['rateJoin'];
		}
		$aSql['__from__']      = "FROM `{$this->sMainTable}`";
		$aSql['__main_join__'] = "LEFT JOIN `Profiles` ON `Profiles`.`ID` = `{$this->sMainTable}`.{$this->aTableFields['medProfId']}";
		if (isset($aCond['sqlWhere'])) 
			$aSql['__where__'] = $aCond['sqlWhere'];
		if (isset($aCond['sqlOrder']))
			$aSql['__order__'] = $aCond['sqlOrder'];
		if ($aCond['sqlLimit'])
			$aSql['__limit__'] = $aCond['sqlLimit'];
			
		$aKeys    = array_keys($aSql);
		$sqlQuery = str_replace($aKeys, $aSql, $sqlTempl);
//		echo "<br/>$sqlQuery";
		$rData = db_res($sqlQuery);

		return $rData;
	}
	
	/*
		Show 1 file in browse
		* @param array $aData - info array about
		* @param boolean $bAdmin - admin mode
		* @return @sCode - html output
	*/
	
	function showBrowseUnit($aData, $bAdmin = false) {
		$sHref  = $this->getFileUrl($aData['medID'], $aData['medUri']);
		$sTitle = strlen($aData['medTitle']) > 0 ? $aData['medTitle'] : _t("_Untitled");
		$sViews = _t('_Views');

		$sActionLinks = $this->iViewer == $aData['medProfId'] ? '<div><a href="javascript: void(0);"
						onClick="window.open(\''.$this->aConfigSite['url'].'mediaActions.php?fileID='.$aData['medID'].'&amp;action=edit&amp;type='.$this->sType.'\', 
						\'photo\', \'width=500, height=380, menubar=no,status=no,resizable=yes,scrollbars=yes,toolbar=no,location=no\');">'
					._t("_Edit").'</a></div><div><a href="'.$_SERVER['PHP_SELF'].'?action=del&fileID='.$aData['medID'].'"
			onClick="return confirm( \''._t("_are you sure?").'\');">'._t("_Delete").'</a></div>' : '' ;
		
		if (!$bAdmin) {
			$oVotingView = new BxTemplVotingView ('g'.$this->sType, $aData['medID']);
			if( $oVotingView->isEnabled()) {
				$sRate = $oVotingView->getSmallVoting (0);
				$sShowRate = '<div class="galleryRate">'. $sRate . '</div>';
			}
			$sProfLink = '<div class="addInfo">'._t("_By").': <a href="'.getProfileLink($aData['medProfId']).'">'.$aData['NickName'].'</a></div>';
			$sCheck    = '';
		}
		else {
			$sShowRate = '';
			$sStyle    = isset($aData['Approved']) && $aData['Approved'] == 'true' ? ' style="border: 2px solid #00CC00;"' : ' style="border: 2px solid #CC0000;"';
			$sProfLink = '<div>'._t("_By").': <a href="'.$this->aConfigSite['url'].'pedit.php?ID='.$aData['medProfId'].'">'.$aData['NickName'].'</a></div>';
			$sCheck    = '<div class="browseCheckbox"><input type="checkbox" name="Check[]" value="'.$aData['medID'].'" id="ch'.$aData['medID'].'"></div>';
		}
		switch ($this->sType) {
			case 'photo':
				$sImg   = $this->sFilesUrl.$aData['medID'].'_t.'.$aData['medExt'];
				$sImage = '<div class="lastFilesPic" style="background-image: url(\''.$sImg.'\');">
					  <a href="'.$sHref.'"><img src="'.$this->aConfigSite['images'].'spacer.gif" width="110" height="110"></a></div>';
				break;
			case 'music':
				$sImage = '<div class="lastFilesPic"><a href="'.$sHref.'"><img src="'.$this->aConfigSite['images'].'music.png"></a></div>';
				break;
			case 'video':
				$sImage = '<div class="lastFilesPic"><a href="'.$sHref.'"><img src="'.$this->sFilesUrl.$aData['medID'].'_small.jpg"></a></div>';
				break;
		}
		
		ob_start();
		?>
		<div class="browseUnit"<?=$sStyle?>>
			<?=$sCheck?>
			<?=$sImage?>
			<div class="addInfo">
				<a href="<?=$sHref?>"><b><?=$sTitle?></b></a>
			</div>
			<?=$sProfLink?>
			<div class="addInfo"><?=_t("_Added")?>: <b><?=defineTimeInterval($aData['medDate'])?></b></div>
			<div class="addInfo"><?=$sViews?>: <b><?=$aData['medViews']?></b></div>
			<?=$sShowRate.$sActionLinks?>
		</div>
		<?
		$sCode = ob_get_clean();
		
		return $sCode;
	}
	
	/*
		Show pagination for current browse page
		* @param int $iTotalPages - number of total elements
		* @param int $iCurPage	  - number of current browse page
		* @param int $iPerPage	  - number of total elements
		* @param boolean $bAdmin  - admin mode
		
		* return $sCode - html output
	*/
	
	function showPagination($iTotalPages, $iCurPage, $iPerPage = 10, $bAdmin = false) {
		$sMainUrl = '';
		$iTotalPages = (int)$iTotalPages;
		$iCurPage	 = (int)$iCurPage;
		$iPerPage	 = (int)$iPerPage;
		
		$bLinkMode = $this->isPermalinkEnabled() ? true : false;
		$bLinkMode = $bAdmin ? false : $bLinkMode;
		if ($iTotalPages > 1) {
			if ($bLinkMode) {
					$sMainUrl = $this->sType.'/all';
					
					$aFields = array( 'ownerName', 'tag', 'rate' );
			
					foreach ($aFields as $field) {
						if( isset( $_GET[$field] ) ) {
							$sParam = htmlentities( process_pass_data( $_GET[$field] ));
							switch ($field) {
								case 'ownerName':
									$sMainUrl = $this->sType.'/gallery/all/'.$sParam;
									break;
								case 'tag':
									$sMainUrl = $this->sType.'/gallery_tag/'.$sParam;
									break;
								case 'rate':
									$sMainUrl = $this->sType.'/gallery_top';
									break;
							}
						}
					}
					$sMainUrl = $GLOBALS['site']['url'].$sMainUrl;
					$sReloc = "'$sMainUrl/'+this.value+'/$iCurPage'";
					$sLinkTempl = $sMainUrl ."/$iPerPage/{page}";
			}
			else {
				$sRequest = $_SERVER['PHP_SELF'] . '?';
		
				$aFields = array('userID', 'tag', 'rate');
				if ($bAdmin) {
					$aFields[] = 'type';			
				}
				foreach( $aFields as $field ) {
					if (isset( $_GET[$field])) 
						$sRequest .= "&{$field}=" . htmlentities( process_pass_data( $_GET[$field] ) );
				}
				$sReloc = "'$sRequest&page=$iCurPage&per_page='+this.value";
				$sLinkTempl = $sRequest . "&page={page}&per_page=$iPerPage";
			}
				
			$sPagination = '<div style="text-align: center; position: relative; margin: 10px 0px;">'._t("_Results per page").':
					<select name="per_page" onchange="window.location=' . htmlspecialchars( $sReloc ) . ';">
						<option value="10"' . ( $iPerPage == 10 ? ' selected="selected"' : '' ) . '>10</option>
						<option value="20"' . ( $iPerPage == 20 ? ' selected="selected"' : '' ) . '>20</option>
						<option value="50"' . ( $iPerPage == 50 ? ' selected="selected"' : '' ) . '>50</option>
						<option value="100"' . ( $iPerPage == 100 ? ' selected="selected"' : '' ) . '>100</option>
					</select></div>';
				
			$sPagination .= genPagination( $iTotalPages, $iCurPage, $sLinkTempl );
		}
		else
			return '';
		return $sPagination;
	}
	
	/*
		Get list of media files for files
		* @param int $iUser - user ID
		* @ return string $sCode - html output
	*/
	
	function getBlockCode_SharedMedia($iUser = 0) {
		$iUser   = (int)$iUser;
		
		$aManage = array('medID','medExt','medTitle','medUri');
		
		$max_num = (int)getParam("top_photos_max_num");
		$mode	 = process_db_input( getParam("top_photos_mode") );
		
		$mode = $_GET['sh_'.$this->sType.'Mode'];
		if( $mode != 'rand' && $mode != 'top' && $mode != 'last')
			$mode = 'last';
		
		$aCond['sqlWhere'] = " WHERE `{$this->aTableFields['Approved']}`='true'";
		
		if ($iUser != 0)
			$aCond['sqlWhere'] .= " AND `{$this->aTableFields['medProfId']}`='$iUser'";
		
		$sqlFrom = " FROM `{$this->sMainTable}`";
		
		$aDBTopMenu = array();
		 foreach (array( 'last', 'top', 'rand' ) as $myMode) {
		  switch ( $myMode ) {
		   case 'last':
		    if( $mode == $myMode )
		     $aCond['sqlOrder'] = "
		  		ORDER BY `{$this->aTableFields['medDate']}` DESC";
		     $modeTitle = '_Latest';
		   	 break;
		   case 'rand':
		    if( $mode == $myMode )
		     $aCond['sqlOrder'] = "
		  		ORDER BY RAND()";
		     $modeTitle = '_Random';
		     break;
		   case 'top':
		    if( $mode == $myMode ) {
				$oVotingView = new BxTemplVotingView ('g'.$this->sType, 0, 0);
				$aSql        = $oVotingView->getSqlParts('`'.$this->sMainTable.'`', '`'.$this->aTableFields['medID'].'`');
				$sHow        = "DESC";
				$aCond['sqlOrder']   = $oVotingView->isEnabled() ? "ORDER BY `voting_rate` $sHow, `voting_count` $sHow, `{$this->aTableFields['medDate']}` $sHow" : $aCond['sqlOrder'] ;
				$aCond['rateFields'] = $aSql['fields'];
				$aCond['rateJoin']   = $aSql['join'];
				$sqlFrom .= $aSql['join'];
		    }
		    $modeTitle = '_Top';
		    break;
		  }
			if( basename( $_SERVER['PHP_SELF'] ) == 'rewrite_name.php' || basename( $_SERVER['PHP_SELF'] ) == 'profile.php' )
				$sLink = "profile.php?ID={$iUser}&";
			else
				$sLink  = "{$_SERVER['PHP_SELF']}?";
				$sLink .= "sh_".$this->sType."Mode=$myMode";
			
		  	$aDBTopMenu[$modeTitle] = array('href' => $sLink, 'dynamic' => true, 'active' => ( $myMode == $mode ));
		 }
		 
		$aNum = db_arr( "SELECT COUNT(`$this->sMainTable`.`{$this->aTableFields['medID']}`) $sqlFrom {$aCond['sqlWhere']}" );
		$num = (int)$aNum[0];
		
		$ret = '';
		if( $num ) {
			$pages = ceil( $num / $max_num );
			$page = (int)$_GET['page'];
			
			if( $page < 1 or $mode == 'rand' )
				$page = 1;
			if( $page > $pages )
				$page = $pages;
			
			$sqlLimitFrom = ( $page - 1 ) * $max_num;
			$aCond['sqlLimit'] = "LIMIT $sqlLimitFrom, $max_num";
		 
		 $rData = $this->getFilesList($aCond, $aManage);
		 	 
		 $ret .= '<div class="clear_both"></div>';
		 $iCounter = 1;
		 $sAddon = '';
		 while ($aData = mysql_fetch_array($rData)) {
			$sTitle = strlen($aData['medTitle']) > 0 ? $aData['medTitle'] : _t("_Untitled");	
			$sHref  = $this->getFileUrl($aData['medID'], $aData['medUri']);
			
			$oVotingView = new BxTemplVotingView ('g'.$this->sType, $aData['medID']);
		    if( $oVotingView->isEnabled()) {
				$sRate = $oVotingView->getSmallVoting (0);
				$sShowRate = '<div class="galleryRate">'. $sRate . '</div>';
			}
			switch ($this->sType) {
				case 'photo':
				 	$sImage = $this->sFilesUrl.$aData['medID'].'_t.'.$aData['medExt'];
				 	$sPic = '<div class="lastFilesPic" style="background-image: url(\''.$sImage.'\');">
					  <a href="'.$sHref.'"><img src="'.$this->aConfigSite['images'].'spacer.gif" alt="" width="110" height="110"></a></div><div class="clear_both"></div>';
					break;
				case 'music':
					$sPic = '<div class="lastMusicPic"><a href="'.$sHref.'"><img src="'.$this->aConfigSite['images'].'music.png"></a></div>';
					break;
				case 'video':
					$sPic = '<div class="lastVideoPic"><a href="'.$sHref.'"><img src="'.$this->sFilesUrl.$aData['medID'].'.jpg" width="112px" height="80px"></a></div>';
					break;
			}
		 	
			
			$ret .= '<div class="sharePhotosContent_1">';
			$ret .= $sPic.'<div class="shareMediaAdd"><a href="'.$sHref.'">'.$sTitle.'</a></div>'.$sShowRate.'</div>';
		 }
		 
		 $ret .= '<div class="clear_both"></div>';
		 
		 $aDBBottomMenu = array();
		 
		 $sUserAddon = ($iUser > 0) ? "&amp;ID={$iUser}" : '';
		 if( $pages > 1 ) {
			if( $page > 1 ) {
				$prevPage = $page - 1;
				$aDBBottomMenu[ _t('_Back') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?sh_".$this->sType."Mode=$mode&amp;page=$prevPage{$sUserAddon}", 'dynamic' => true, 'class' => 'backMembers' );
			}
				
			if( $page < $pages ) {
				$nextPage = $page + 1;
				$aDBBottomMenu[ _t('_Next') ] = array( 'href' => "{$_SERVER['PHP_SELF']}?sh_".$this->sType."Mode=$mode&amp;page=$nextPage{$sUserAddon}", 'dynamic' => true, 'class' => 'moreMembers' );
			}
			if ($iUser != 0) {
				$sAllUrl = $this->isPermalinkEnabled() ? $this->aConfigSite['url'].$this->sType.'/gallery/all/'. getNickName($iUser) : $this->aConfigSite['url'].'browse'.ucfirst($this->sType).'.php?userID='.$iUser;		
			}
			else
				$sAllUrl = $this->isPermalinkEnabled() ? $this->aConfigSite['url'].$this->sType.'/all/10/1' : $this->aConfigSite['url'].'browse'.ucfirst($this->sType).'.php';
			
			$aDBBottomMenu[ _t('_View All') ] = array( 'href' => "$sAllUrl", 'class' => 'viewAllMembers' );	
		}
	 }
	
	 return array( $ret, $aDBTopMenu, $aDBBottomMenu );
	}
	
	//------------------------------------------- actions -------------------------------------------//
	
	/*
		Delete file, his string in DB and all his child files
		* @param int iFile - file ID
		* @param array $logged - array of login
	*/
	
	function deleteMedia($iFile, $logged = array()) {
		$iFile = (int)$iFile;
		$sqlQuery  = "SELECT `{$this->aTableFields['medProfId']}`";
	    $sqlQuery .= isset($this->aTableFields['medExt']) ? ", `{$this->aTableFields['medExt']}`" : "";
	    $sqlQuery .= "FROM `{$this->sMainTable}` WHERE `{$this->aTableFields['medID']}`=$iFile";
	    
	    $aFile = db_arr($sqlQuery);
  		if (!$aFile)
  			return false;

		if ($logged['admin']) {
		}
		elseif ($logged['member']) {
	  		$iMemberID = (int)$_COOKIE['memberID'];
	  		
	  		if ($aFile[$this->aTableFields['medProfId']] != $iMemberID)
		   		return false;
		}
		else
	  		return false;
		
		switch ($this->sType) {
			case 'photo':
				$aFName[] = $iFile . '.'.$aFile['medExt'];
				$aFName[] = $iFile.'_t.'.$aFile['medExt'];
            	$aFName[] = $iFile.'_m.'.$aFile['medExt'];
            	$sCmtsName = 'sharedPhoto';
				break;
			case 'music':
				$aFName[] = $iFile.'.mp3';
				$sCmtsName = 'sharedMusic';
				break;
			case 'video':
				$aFName[] = $iFile.'.flv';
	            $aFName[] = $iFile.'.mpg';
				$aFName[] = $iFile.'.jpg';
				$aFName[] = $iFile.'_small.jpg';
				$sCmtsName = 'sharedVideo';
				break;		
		}
		foreach($aFName as $sVal) {
			$sFilePath = $this->sFilesPath.$sVal;
			@unlink($sFilePath);
    	}
    	db_res("DELETE FROM `{$this->sMainTable}` WHERE `{$this->aTableFields['medID']}`=$iFile");
	
		reparseObjTags( $this->sType, $iFile );
		
    	$oVoting = new BxDolVoting ('g'.$this->sType, 0, 0);
		$oVoting->deleteVotings($iFile);

		$oCmts = new BxDolCmts($sCmtsName, $iFile);
		$oCmts->onObjectDelete();
		
    	header('Location:' . $_SERVER["HTTP_REFERER"]);
	}
	
	/*
		Approved media by admin
		* @param int iFile - file ID
	*/
	
	function approveMedia($iFile) {
		$iFile = (int)$iFile;
		$sqlQuery = "UPDATE `{$this->sMainTable}` SET `{$this->aTableFields['Approved']}` = IF(`{$this->aTableFields['Approved']}`='true','false','true') WHERE `{$this->aTableFields['medID']}`='$iFile'";
		db_res($sqlQuery);
		header('Location:' . $_SERVER["HTTP_REFERER"]);
	}
	
	/*
		Get favorite files conditon
		* @param int $iMember - view ID
		* @return $sqlQuery - sqlQuery addon
	*/
	
	function getFavoriteCondition($iMember) {
		$iMember  = (int)$iMember;
		$sqlQuery = "SELECT `medID` FROM `{$this->sFavoriteTable}` WHERE `userID`=$iMember";
		$rList = db_res($sqlQuery);
		while ($aList = mysql_fetch_row($rList)) {
			$sParam .= $aList[0] . ',';
		}
		$sRes = " AND `{$this->sMainTable}`.`{$this->aTableFields['medID']}` IN(";
		if (strlen($sParam) > 0)
			$sRes = $sRes.trim($sParam,',').')';
		else
			$sRes = ' AND 0';

		return $sRes;
	}
	
	/*
		Delete all user's file in current media gallery
		* @param int $iMember - view ID
		* @param array $logged - array of logins
	*/
	
	function deleteUserGallery($iUser, $logged) {
		$iUser = (int)$iUser;
		$sqlQuery = "SELECT `{$this->aTableFields['medID']}` from `{$this->sMainTable}` WHERE `{$this->aTableFields['medProfId']}`=$iUser";
		$rFiles = db_res($sqlQuery);
		while ($aFile = mysql_fetch_assoc($rFiles)) {
			$this->deleteMedia($aFile[$this->aTableFields['medID']], $logged);
			$oCmts = new BxDolCmts('shared'.ucfirst($this->sType), $iFile);
			$oCmts->onObjectDelete();
		}
	}
	
	/*
		Add file to favorite list
		* @param int $iFile - file ID
		* @return $sCode - html output
	*/
	
	function addToFavorites($iFile) {
		$iFile =(int)$iFile;
		if ($iFile) {
			$sqlQuery = "SELECT * FROM `{$this->sFavoriteTable}` WHERE `medID`='$iFile' AND `userID`='{$this->iViewer}'";
			$aCheck = db_arr($sqlQuery);
			if ($aCheck)
				$sCode = '<div class="mediaInfo">'._t('_File already is favorite').'</div>';
			else {
				$sqlQuery = "INSERT INTO `{$this->sFavoriteTable}` (`medID`,`userID`,`favDate`) VALUES('$iFile','{$this->iViewer}',NOW())";
				db_res($sqlQuery);
				$sCode = '<div class="mediaInfo">'._t("_File was added to favorite").'</div>';
			}
		}
		
		return $sCode;
	}
	
	/*
		Send file info
		* @param int $iFile - file ID
		* @param string $sEmail - email(s)
		* @param string $sMessage - message
		* @param string $sUrl - link to file
	*/
	
	function sendFileInfo($iFile, $sEmail, $sMessage, $sUrl) {	
		$aUser = getProfileInfo($this->iViewer);
		$sUrl  = urldecode($sUrl);
		
		$sMailHeader		= "From: {$this->aConfigSite['title']} <{$this->aConfigSite['email_notify']}>";
		$sMailParameters	= "-f{$this->aConfigSite['email_notify']}";
		
		$sMailHeader = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=UTF-8\r\n" . $sMailHeader;
		$sMailSubject = $aUser['NickName'].' shared a Photo with you';
		
		$sMailBody    = "Hello,\n
					{$aUser['NickName']} shared a {$this->sType} with you: <a href=\"$sUrl\">See it</a>\n
					$sMessage\n
					Regards";
		
		$aEmails = explode(",", $sEmail);
		foreach ($aEmails as $iKey => $sMail) {
			$sMail = trim($sMail);
			$iSendingResult = mail( $sMail, $sMailSubject, nl2br($sMailBody), $sMailHeader, $sMailParameters );
		if ($iSendingResult)
			$sCode = '<div class="mediaInfo">'._t("_File info was sent").'</div>';
		}
		
		return $sCode;
	}
	
	/*
		Show submit form
		* @param array $aActions - array of all action variable
		* @return $sCode - html output
	*/
	
	function showSubmitForm($aAction) {
		$iFile = (int)$aAction['fileID'];
		$sAction = htmlspecialchars_adv($aAction['action']);
		if ($iFile && strlen($sAction) > 0) {
			switch ($sAction) {
				case 'share' : 
					$sAddr  = '<div>'._t("_Enter email(s)").':</div><div><input type="text" size="40" name="email"></div>';
					$sSites = '<div style="margin-top:10px; margin-bottom:10px;">'.$this->getSitesArray($aAction['fileUrl']).'</div>';
					break;
				case 'report': 
					$sAddr  = '<input type="hidden" name="email" value="'.$this->aConfigSite['email_notify'].'">';
					$sSites = '';
					break;
			}
			
			ob_start();
			?>
			<div class="mediaInfo">
				<form name="submitAction" method="post" action="<?=$_SERVER['PHP_SELF']?>">
					<input type="hidden" name="type" value="<?=$this->sType?>">	
					<input type="hidden" name="fileID" value="<?=$iFile?>">
					<input type="hidden" name="fileUrl" value="<?=$aAction['fileUrl']?>">
					<?=$sAddr.$sSites?>
					<div><?=_t("_Message text")?></div>
					<div><textarea cols="30" rows="10" name="messageText"></textarea></div>
					<div><input type="submit" size="15" name="send" value="Send">
					<input type="reset" size="15" name="send" value="Reset"></div>
				</form>
			</div>	
			<?
			$sCode = ob_get_clean();
		}
		return $sCode;
	}
	
	/*
		Get shared sites array
		* @param string $sLink - file encode URL
		* @return string $sCode - html output
	*/
	
	function getSitesArray($sLink) {
		$sLink = htmlentities(($sLink));
		$aSites = array(
			array(
			'image'=>'digg.png',
			'link'=>'http://digg.com/submit?phase=2&url='.$sLink
			),
			array(
			'image'=>'delicious.png',
			'link' =>'http://del.icio.us/post?url='.$sLink
			),
			array(
			'image'=>'blinklist.png',
			'link' =>'http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Url='.$sLink
			),
			array(
			'image'=>'furl.png',
			'link' =>'http://www.furl.net/storeIt.jsp?u='.$sLink
			),
			array(
			'image'=>'netscape.gif',
			'link' =>'http://www.netscape.com/submit/?U='.$sLink
			),
			array(
			'image'=>'newsvine.png',
			'link' =>'http://www.newsvine.com/_tools/seed&save?u='.$sLink
			),
			array(
			'image'=>'reddit.png',
			'link' =>'http://reddit.com/submit?url='.$sLink
			),
			array(
			'image'=>'shadows.png',
			'link' =>'http://www.shadows.com/features/tcr.htm?url='.$sLink
			),
			array(
			'image'=>'slashdot.png',
			'link' =>'http://slashdot.org/bookmark.pl?url='.$sLink
			),
			array(
			'image'=>'sphere.png',
			'link' =>'http://www.sphere.com/search?q=sphereit:'.$sLink
			),
			array(
			'image'=>'stumbleupon.png',
			'link' =>'http://www.stumbleupon.com/url/http'.$sLink
			),
			array(
			'image'=>'technorati.png',
			'link' =>'http://technorati.com/faves?add='.$sLink
			)
		);
		$sLink = '<a href="{Link}"><div class="shareLink" style="background-image:url(\'{Image}\')"></div></a>';
	
		foreach ($aSites as $iKey =>$aVal) {
			$sLinkCur = str_replace('{Image}', getTemplateIcon($aVal['image']),$sLink);
			$sLinkCur = str_replace('{Link}', $aVal['link'],$sLinkCur);
			$sCode   .= $sLinkCur;
		}
		$sCode .= '<div class="clear_both"></div>';
		
		return $sCode;
	}
	
	/*
		Get array of sql parts, total pages, current page, per page
		* @param array $logged - array of logins
		* @return array('query'(sql query),'total' (totalpages), 'per_page' (in 1 page), 'cur_page' (current page num))
	*/
	
	function getConditionArray($logged) {
		$aWhere = array();
		$aWhere[] = '1';
		
		$iUser = 0;
		
		if (isset($_GET['ownerName'])) {
			$sName = process_db_input($_GET['ownerName']);
			$iUser = (int)db_value("SELECT `ID` FROM `Profiles` WHERE `NickName`='$sName'");
		}
		elseif (isset($_GET['userID']))
			$iUser = (int)$_GET['userID'];
		
		if ($iUser)
			$aWhere[] = "`{$this->sMainTable}`.`{$this->aTableFields['medProfId']}`=$iUser";   
		
		if (isset($_GET['tag'])) {
			$sTag = htmlspecialchars_adv($_GET['tag']);
			$aWhere[] = "`{$this->sMainTable}`.`{$this->aTableFields['medTags']}` like '%$sTag%'";
		}
		
		if (isset($_GET['action'])) {
			$sAct = htmlspecialchars_adv($_GET['action']);
			switch ($sAct) {
				case 'fav':
					$sAddon = $this->getFavoriteCondition($this->iViewer);
					break;
				case 'del':
					$sAddon = '';
					if (isset($_GET['fileID'])) 
						$this->deleteMedia($_GET['fileID'], $logged);
					break;	
			}
		}
		
		$aSqlQuery['sqlWhere'] = "WHERE " . implode( ' AND ', $aWhere ).$sAddon." AND `Approved`= 'true'";
		
		$iTotalNum = db_value( "SELECT COUNT( * ) FROM `{$this->sMainTable}` {$aSqlQuery['sqlWhere']}" );
		if (!$iTotalNum)
			return false;
		
		$iPerPage = (int)$_GET['per_page'];
		if (!$iPerPage)
			$iPerPage = 10;
		
		$iTotalPages = ceil( $iTotalNum / $iPerPage );
		
		$iCurPage = (int)$_GET['page'];
		
		if ($iCurPage > $iTotalPages)
			$iCurPage = $iTotalPages;
		
		if ($iCurPage < 1)
			$iCurPage = 1;
		
		$sLimitFrom = ( $iCurPage - 1 ) * $iPerPage;
		$aSqlQuery['sqlLimit'] = "LIMIT $sLimitFrom, $iPerPage";
		
		$aSqlQuery['sqlOrder'] = 'ORDER BY `medDate` DESC';
		
		if (isset($_GET['rate'])) {
			$oVotingView = new BxTemplVotingView ('g'.$this->sType, 0, 0);
			
			$aSql        = $oVotingView->getSqlParts('`'.$this->sMainTable.'`', '`'.$this->aTableFields['medID'].'`');
			$sHow        = $_GET['rate'] == 'top' ? "DESC" : "ASC";
			$aSqlQuery['sqlOrder']   = $oVotingView->isEnabled() ? "ORDER BY `voting_rate` $sHow, `voting_count` $sHow, `medDate` $sHow" : $aSqlQuery['sqlOrder'] ;
			$aSqlQuery['rateFields']  = $aSql['fields'];
			$aSqlQuery['rateJoin']    = $aSql['join'];
		}
		
		return array('query'=>$aSqlQuery, 'total'=>$iTotalPages, 'per_page'=>$iPerPage, 'cur_page'=>$iCurPage);
	}
	
	/* 
		Display media edit form
		* @param int $iFile - file ID
		* @return string $sCode - html output
	*/		
	
	function displayMediaEditForm($iFile) {
		$iFile = (int)$iFile; 
		
		$sqlQuery = "SELECT ";
		
		$sTempl = '<div>__Key__</div>';
		$sLine = '';
		
		foreach ($this->aEditInfo as $sKey => $sVal) {
			$sqlQuery .= "`{$this->aTableFields[$sKey]}`,";
			if ($sKey != 'medProfId' && $sKey != 'medUri') {
				$sHead  = str_replace('__Key__', _t('_'.$sVal), $sTempl);
				$sPatt  = $sKey != 'medDesc' ? '<input type="text" size="40" name="'.$sKey.'" value="'.$sKey.'Val"/>' : '<textarea cols="30" rows="10" name="'.$sKey.'">'.$sKey.'Val</textarea>' ;
				$sMain  = str_replace('__Key__', $sPatt, $sTempl);
				$sLine .= $sHead.$sMain;
			}	
		}
		
		$sqlQuery = trim($sqlQuery, ','). "FROM `{$this->sMainTable}` WHERE `{$this->aTableFields['medID']}`='$iFile'";
		
		$aData = db_arr($sqlQuery);
		
		if ($aData[$this->aTableFields['medProfId']] != $this->iViewer)
			exit;
	
		foreach ($this->aEditInfo as $sKey => $sVal)
			$sLine = str_replace($sKey.'Val', $aData[$this->aTableFields[$sKey]], $sLine);
	
		ob_start();
		?>
		<div class="mediaInfo">
			
		<iframe name="Edit" style="display: none;"></iframe>
				<form target="Edit" name="submitAction" method="post" action="<?=$_SERVER['PHP_SELF']?>">
				<?=$sLine?>
				<div><input type="submit" size="15" name="save" value="<?=_t('_Save Changes')?>">
				<input type="reset" size="15" name="send" value="Reset"></div>
				<input type="hidden" name="type" value="<?=$this->sType?>">	
				<input type="hidden" name="fileID" value="<?=$iFile?>">
				<input type="hidden" name="mediaAction" value="edit">
			</form>
		</div>
		<?
		$sCode = ob_get_clean();
		
		return $sCode;
	}
	
	/*
		Save content of media file
		* @param int $iFile - file ID
	*/
	
	function saveChanges($iFile) {
		$iFile = (int)$iFile;
		
		$sqlQuery = "UPDATE `$this->sMainTable` SET ";
		foreach ($this->aEditInfo as $sKey => $sVal) {
			switch ($sKey) {
				case 'medProfId': break;
				case 'medUri' : break;
				default: 
					$sInput    = process_db_input($_POST[$sKey]);
					$sqlQuery .= "`{$this->aTableFields[$sKey]}` = '$sInput',";
			}
		}	
	
		$sqlQuery = rtrim($sqlQuery,',')." WHERE `{$this->aTableFields['medID']}` = '$iFile' AND `{$this->aTableFields['medProfId']}`='{$this->iViewer}'";
		db_res($sqlQuery);
		if (!mysql_affected_rows())
			exit;
	}
}

?>