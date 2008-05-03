<?

require_once( BX_DIRECTORY_PATH_CLASSES . "BxDolPageView.php" );

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfileView.php' );
require_once( BX_DIRECTORY_PATH_ROOT . 'profilePhotos.php' );

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolClassifieds.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolEvents.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolBlogs.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolGroups.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolSharedMedia.php' );

class BxDolProfilePageView extends BxDolPageView {
	var $oProfileV;
	
	var $aConfSite;
	var $aConfDir;

	function BxDolProfilePageView(&$oPr, &$aSite, &$aDir) {
		$this->oProfileV = &$oPr;
		$this->aConfSite = $aSite;
		$this->aConfDir  = $aDir;
		parent::BxDolPageView('profile');
	}

	function getBlockCode_ActionsMenu() {
		return $this->oProfileV->showBlockActionsMenu('', true);
	}
	function getBlockCode_Classifieds() {
		return $this->oProfileV->showBlockClassifieds('', true);
	}
	function getBlockCode_Events() {
		return $this->oProfileV->showBlockEvents('', true);
	}
	function getBlockCode_Groups() {
		return $this->oProfileV->showBlockGroups('', true);
	}
	function getBlockCode_ProfilePolls() {
		return $this->oProfileV->showBlockProfilePolls('', true);
	}
	function getBlockCode_ShareMusic() {
		$aMem = array('ID'=>$this->_iProfileID);
		$oNew = new BxDolSharedMedia('music', $this->aConfSite, $this->aConfDir, $aMem);
		$aRes = $oNew->getBlockCode_SharedMedia($this->oProfileV->_iProfileID);

		return $aRes;
	}
	function getBlockCode_SharePhotos() {
		$aMem = array('ID'=>$this->_iProfileID);
		$oNew = new BxDolSharedMedia('photo', $this->aConfSite, $this->aConfDir, $aMem);
		$aRes = $oNew->getBlockCode_SharedMedia($this->oProfileV->_iProfileID);
		
		return $aRes;
	}
	function getBlockCode_ShareVideos() {
		$aMem = array('ID'=>$this->_iProfileID);
		$oNew = new BxDolSharedMedia('video', $this->aConfSite, $this->aConfDir, $aMem);
		$aRes = $oNew->getBlockCode_SharedMedia($this->oProfileV->_iProfileID);
		
		return $aRes;
	}
	function getBlockCode_PFBlock( $iBlockID, $sContent ) {
		return $this->oProfileV->showBlockPFBlock('', $sContent, true);
	}
	function getBlockCode_RateProfile() {
		return $this->oProfileV->showBlockRateProfile('', true);
	}
	function getBlockCode_Blog() {
		return $this->oProfileV->showBlockProfileBlog('', true);
	}
	function getBlockCode_Friends() {
		return $this->oProfileV->showBlockFriends('', true);
	}
	function getBlockCode_MutualFriends() {
		return $this->oProfileV->showBlockMutualFriends('', true);
	}
	function getBlockCode_Mp3() {
		return $this->oProfileV->showBlockMp3('', true);
	}

	function getBlockCode_Topest($iColumn) {
		return '<div class="page_block_container">' . $this->oProfileV->showBlockPhoto($iColumn, true) . '</div>';
	}

	function getBlockCode_Comments() {
		return $this->oProfileV->showBlockComments('', true);
    }

    function getBlockCode_Cmts () {
        return $this->oProfileV->showBlockCmts();        
    }
}

class BxBaseProfileView extends BxDolProfileView
{
	function BxBaseProfileView( $ID )
	{
		$this -> aMutualFriends = array();
		BxDolProfileView::BxDolProfileView( $ID );
		$this -> FindMutualFriends();
	}
	
	function defineTimeInterval ($iTime)
	{
		$iTime = time() - $iTime;
		if ( $iTime < 60 )
			$sCode = "$iTime "._t("_seconds ago");
		else
		{
			$iTime = round( $iTime / 60 ); // minutes
			if ( $iTime < 60 )
				$sCode = "$iTime "._t("_minutes ago");
			else
			{
				$iTime = round( $iTime / 60 ); //hours
				if ( $iTime < 24 )
					$sCode = "$iTime "._t("_hours ago");
				else
				{
					$iTime = round( $iTime / 24 ); //days
					$sCode = "$iTime "._t("_days ago");
				}
			}
		}
		return $sCode;
	}

	function genProfileCSS( $ID )
	{
	    global $site;

	    $ret = '';

	    $query = "SELECT * FROM `ProfilesSettings` WHERE `IDMember` = '$ID'";
	    $arr = db_arr( $query );
	    if ( $arr['IDMember'] )
		$ret = 	"<style type=\"text/css\">
			    body
			    {
			    	background-image: url( {$site['profileBackground']}{$arr['BackgroundFilename']});
			    	background-color: {$arr['BackgroundColor']};
			    	background-repeat:repeat;
			    }
			    div#right_column_content
			    {
			    	color: {$arr['FontColor']};
			    	font-size: {$arr['FontSize']}px;
			    	font-family: {$arr['FontFamily']};
			    }
			    div#divUnderCustomization
			    {
			    	color: {$arr['FontColor']};
			    	font-size: {$arr['FontSize']}px;
			    	font-family: {$arr['FontFamily']};
			    }
				</style>";

	    return $ret;
	}
	
	function genColumns($sOldStyle = false) {
		ob_start();

		?>
		<div id="thin_column">
			<? $this -> showColumnBlocks( 1, $sOldStyle ); ?>
		</div>

		<div id="thick_column">
			<? $this -> showColumnBlocks( 2, $sOldStyle ); ?>
		</div>
		<?

		return ob_get_clean();
	}
	
	function showColumnBlocks( $column, $sOldStyle = false ) {
		global $logged;
		if( $logged['member'] )
			$sVisible = 'memb';
		else
			$sVisible = 'non';

		$this -> showBlockPhoto( $column );
		//return;
		$sAddSQL = ($sOldStyle == true) ? " AND `Func`='PFBlock' " : '';
		$rBlocks = db_res( "SELECT * FROM `PageCompose` WHERE `Page` = 'profile' AND `Column`=$column AND FIND_IN_SET( '$sVisible', `Visible` ) {$sAddSQL} ORDER BY `Order`" );
		while( $aBlock = mysql_fetch_assoc( $rBlocks ) ) {
			if ($aBlock['Func'] == 'ShareMusic' || $aBlock['Func'] == 'ShareVideos' || $aBlock['Func'] == 'SharePhotos')
				continue;
			$func = 'showBlock' . $aBlock['Func'];
			$this -> $func( $aBlock['Caption'], $aBlock['Content'] );
		}
	}

	function showBlockPhoto( $iCol, $bNoDB = false )
	{
		if( $iCol == 1 ) {
			$iPID = $this -> _iProfileID;
			$sNickName = $this -> _aProfile['NickName'];
		} elseif( $iCol == 2 ) {
			if( !$this -> _aProfile['Couple'] )
				return;

			$iPID = $this -> _iProfileID;
			//$iPID = (int)$this -> _aProfile['Couple'];
			$sNickName = $this -> _aProfile['NickName'] . '(2)';
		}

		$oPhotos = new ProfilePhotos( $iPID );
		$oPhotos -> getActiveMediaArray();

		if( $this -> _aProfile['Couple'] && $iCol != 1 ) {
			$aCoupleInfo = getProfileInfo($this->_aProfile['Couple']);
			if ($aCoupleInfo['Picture']==0) {
				$oPhotos = new ProfilePhotos( $this->_aProfile['Couple'] );
				$oPhotos -> getActiveMediaArray();
			}
			$ret = $oPhotos -> getMediaBlock($aCoupleInfo['PrimPhoto'], true);
		} else {
			$ret = $oPhotos -> getMediaBlock(0);
		}

		if ($bNoDB) {
			return DesignBoxContent( _t( '_PROFILE Photos', $sNickName ), $ret, 1 );
		} else {
			echo DesignBoxContent( _t( '_PROFILE Photos', $sNickName ), $ret, 1 );
		}
	}


	function showBlockRSS( $sCaption, $sContent, $bNoDB = false )
    {
        global $p_arr, $site;

		list( $sUrl, $iNum ) = explode( '#', $sContent );
		$iNum = (int)$iNum;
        
        $sUrl = str_replace(array('{SiteUrl}', '{NickName}'),array($site['url'], $p_arr['NickName']), $sUrl);

		$ret = genRSSHtmlOut( $sUrl, $iNum );
		
		if ($bNoDB) {
			return $ret;
		} else {
			echo DesignBoxContent( _t($sCaption), $ret, 1 );
		}
	}
	
	function showBlockEcho( $sCaption, $sContent )
	{
		echo DesignBoxContent( _t($sCaption), $sContent, 1 );
	}
	
	function showBlockPFBlock( $sCaption, $sContent, $bNoDB = false ) {
		$iBlockID = (int)$sContent;
		if( !isset( $this -> aPFBlocks[$iBlockID] ) or empty( $this -> aPFBlocks[$iBlockID]['Items'] ) )
			return '';
		$aItems = $this -> aPFBlocks[$iBlockID]['Items'];

		$sRet = '<table class="profile_info_block" cellspacing="0" cellpadding="1">';
		
		foreach( $aItems as $aItem ) {
			$sValue1 = $this -> oPF -> getViewableValue( $aItem, $this -> _aProfile[ $aItem['Name'] ] );
			if( !$sValue1 ) //if empty, do not draw
				continue;
			
			$sRet .= '<tr>';
			$sRet .=         '<td class="profile_info_label">' . htmlspecialchars( _t( $aItem['Caption'] ) ) . ':</td>';
			
			if( $this -> bCouple ) {
				if( in_array( $aItem['Name'], $this -> aCoupleMutualItems ) ) {
					$sRet .= '<td class="profile_info_value" colspan="2">' . $sValue1 . '</td>';
				} else {
					$sValue2 = $this -> oPF -> getViewableValue( $aItem, $this -> _aCouple[ $aItem['Name'] ] );
					
					$sRet .= '<td class="profile_info_value1">' . $sValue1 . '</td>';
					$sRet .= '<td class="profile_info_value2">' . $sValue2 . '</td>';
				}
			} else {
				$sRet .=     '<td class="profile_info_value" >' . $sValue1 . '</td>';
			}
			
			$sRet .= '</tr>';
		}
		
		$sRet .= '</table>';
		
		if ($bNoDB) {
			return $sRet;
		} else {
			echo DesignBoxContent( _t($sCaption), $sRet, 1 );
		}
	}
	
	function showBlockProfilePolls( $sCaption, $bNoDB = false ) {
		$sqlPolls = "SELECT `id_poll` FROM `ProfilesPolls` WHERE `id_profile` = {$this -> _iProfileID} AND `poll_status` = 'active' AND `poll_approval`";
		$rPolls = db_res( $sqlPolls );

		if( !mysql_num_rows( $rPolls ) )
			return ;

		$ret = '<div id="profile_poll_wrap">';
		while( $aPoll = mysql_fetch_assoc( $rPolls ) ) {
			$ret .= '<div class="blog_wrapper_n" style="width:80%;border:1px dashed #CCCCCC;">' . ShowPoll( $aPoll['id_poll'] );
			$ret .= '</div><div class="clear_both"></div>';
		}
		$ret .= '</div>';

		$show_hide = $this -> genShowHideItem( 'profile_poll_wrap' );

		if ($bNoDB) {
			return $ret;
		} else {
			echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
		}
	}
	
	function genShowHideItem( $wrapperID, $default = '' )
	{
		if( !$default )
			$default = _t( '_Hide' );
		
		return '
		<div class="caption_item">
			<a href="javascript:void(0);"
			  onclick="el = document.getElementById(\'' . $wrapperID . '\'); if( el.style.display == \'none\' ) {el.style.display = \'block\'; this.innerHTML = \'' . _t( '_Hide' ) . '\';} else {el.style.display = \'none\'; this.innerHTML = \'' . _t( '_Show' ) . '\';}"
			  >' . $default . '</a>
		</div>';
	}
	
	function showBlockActionsMenu( $sCaption, $bNoDB = false )
	{
		global $logged;
		global $p_arr;
		
		$oTemplMenu = new BxTemplMenu( $this -> oTemplConfig );
		
		if( !$logged['member'] or !$p_arr )
			return '';
		
		$memberID  = (int)$_COOKIE['memberID'];
		$profileID = (int)$p_arr['ID'];
		
		if( $memberID == $profileID )
			return '';
		
		/* * * * Ray IM Integration * * * */
		
		$check_res_im = checkAction( $memberID, ACTION_ID_USE_RAY_IM );

		if( ( getParam( 'enable_ray' ) == 'on' ) and
		   get_user_online_status( $profileID ) and
		   ( $check_res_im[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED ) )
		{
			$sSndPassword = getPassword( $memberID );
			
			$IMNow = $oTemplMenu -> getActionsMenuItem( 'action_im.gif', _t( '_ChatNow', $p_arr['NickName'] ), "javascript:void(0);", '', '', "openRayWidget( 'im', 'user', '$memberID', '$sSndPassword', '$profileID' );" );
		}
		else
			$IMNow = '';
		
		/* * * * Ray IM Integration [END]* * * */
		
		$ret = '<div class="menuBlock">';
			$ret .= '<div class="menu_item_block">';
			$ret .= '<div class="menu_item_block_left">';
				$ret .= $oTemplMenu -> getActionsMenuItem( 'action_send.gif', _t('_SendLetter'),     "compose.php?ID=$profileID" );
				$ret .= $oTemplMenu -> getActionsMenuItem( 'action_fave.gif', _t('_Fave'),     "javascript:void(0);", '', '', "window.open( 'list_pop.php?action=hot&amp;ID=$profileID',    '', 'width={$this -> oTemplConfig -> popUpWindowWidth},height={$this -> oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );" );
				$ret .= $oTemplMenu -> getActionsMenuItem( 'action_friends.gif', _t('_Befriend'),"javascript:void(0);", '', '', "window.open( 'list_pop.php?action=friend&amp;ID=$profileID', '', 'width={$this -> oTemplConfig -> popUpWindowWidth},height={$this -> oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );" );
				$ret .= $oTemplMenu -> getActionsMenuItem( 'action_greet.gif', _t('_Greet'),     "javascript:void(0);", '', '', "window.open( 'greet.php?sendto=$profileID',                  '', 'width={$this -> oTemplConfig -> popUpWindowWidth},height={$this -> oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );" );
				$ret .= $IMNow;
				if ( !$this -> oTemplConfig -> bAnonymousMode )
					$ret .= $oTemplMenu -> getActionsMenuItem( 'action_email.gif', _t('_Get E-mail'),   "javascript:void(0);", '', '', "window.open( 'freemail.php?ID=$profileID', '', 'width={$this -> oTemplConfig -> popUpWindowWidth},height={$this -> oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );" );
				$ret .= '</div>';
				$ret .= '<div class="menu_item_block_right">';
				$ret .= $oTemplMenu -> getActionsMenuItem( 'action_photos.gif', $this->getLinkStat('prPhoto', _t('_ProfilePhotos'), $profileID), "photos_gallery.php?ID=$profileID");
				$ret .= $oTemplMenu -> getActionsMenuItem( 'action_videos.gif', $this->getLinkStat('prVideo', _t('_ProfileVideos'), $profileID), "javascript:void(0);", '', '', "openRayWidget( 'video', 'player', '$profileID' );" );
				//				$ret .= $oTemplMenu -> getActionsMenuItem( 'action_videos.gif', _t('_ProfileVideos'),   "javascript:void(0);", '', '', "openRayWidget( 'video', 'player', '$profileID' );" );
				$ret .= $oTemplMenu -> getActionsMenuItem( 'action_music.gif', $this->getLinkStat('prMusic', _t('_ProfileMusic'), $profileID), "javascript:void(0);", '', '', "openRayWidget( 'mp3', 'player', '$profileID', '" . getPassword( $memberID ) . "', '$memberID');");
				$ret .= $oTemplMenu -> getActionsMenuItem( 'action_share.gif', _t('_Share'),   "javascript:void(0);", '', '', "return launchTellFriendProfile($profileID);" );
				$ret .= $oTemplMenu -> getActionsMenuItem( 'action_report.gif', _t('_Report'),   "javascript:void(0);", '', '', "window.open( 'list_pop.php?action=spam&amp;ID=$profileID',   '', 'width={$this -> oTemplConfig -> popUpWindowWidth},height={$this -> oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );" );
				$ret .= $oTemplMenu -> getActionsMenuItem( 'action_block.gif', _t('_Block'),    "javascript:void(0);", '', '', "window.open( 'list_pop.php?action=block&amp;ID=$profileID',  '', 'width={$this -> oTemplConfig -> popUpWindowWidth},height={$this -> oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );" );
			$ret .= '</div>';
			$ret .= '<div class="clear_both"></div>';
			$ret .= '</div>';
		$ret .= '</div>';

		if ($bNoDB) {
			return $ret;
		} else {
			echo DesignBoxContent( _t( $sCaption ), $ret, 1 );
		}
	}
	
	function showBlockRateProfile( $sCaption, $bNoDB = false )
	{
		global $site;
		global $votes;
		
        // Check if profile votes enabled
        if (!$votes || !$this->oVotingView->isEnabled()) return;

        $ret = $this->oVotingView->getBigVoting();

		if ($bNoDB) {
			return $ret;
		} else {
			echo DesignBoxContent( _t( $sCaption ), $ret, 1 );
		}
	}

    function showBlockCmts( )
    {
        if (!$this->oCmtsView->isEnabled()) return '';

        return $this->oCmtsView->getCommentsFirst ();
    }

	function showBlockFriends( $sCaption, $bNoDB = false )
	{
		global $site;
		
		$sFriendList = ShowFriendList( $this -> _iProfileID, $this -> aMutualFriends );
		$iFriendNums = getFriendNumber( $this -> _iProfileID );
		
		if( $sFriendList )
		{
			ob_start();
			
			?>
				<div class="clear_both"></div>
				<?= $sFriendList ?>
				<div class="clear_both"></div>
			<?
			
			$ret = ob_get_clean();
			
			$sFriendInfo = '<div class="caption_item"><a href="'.$site['url'].'viewFriends.php?iUser='.$this -> _iProfileID.'">'.$iFriendNums.' '._t("_Friends").'</a></div>';
			
			if ($bNoDB) {
				$aDbTopMenu = array(
					_t("_Friends") => array( 
						'href' => "{$site['url']}viewFriends.php?iUser={$this -> _iProfileID}"
					)
				);
				
				return array( $ret, $aDbTopMenu );
			} else {
				echo DesignBoxContent( _t( $sCaption ), $ret, 1, $sFriendInfo );
			}
		}
	}

	function showBlockProfileBlog( $sCaption, $bNoDB = false ) {

		global $site;

		$ID = $this -> _iProfileID;

		if ($ID > 0) {
			$sQuery = "
			SELECT DISTINCT
				COUNT(`BlogPosts`.`PostID`)
			FROM `BlogCategories`
			INNER JOIN `BlogPosts` ON
				 `BlogCategories`.`CategoryID` = `BlogPosts`.`CategoryID`
			WHERE
				`BlogCategories`.`OwnerID`  = {$ID} AND
				`BlogPosts`.`PostReadPermission` = 'public' AND
				`BlogPosts`.`PostStatus`         = 'approval'
			";
			$iBlogs = db_value( $sQuery );

			if( $iBlogs > 0 ) {
				$oBlogs = new BxDolBlogs();
				$sBlocks = $oBlogs->GenAnyBlockContent('last', $ID);
				$ret = <<<EOF
<div id="container_blogs">
	{$sBlocks}
</div>
EOF;

				$show_hide = $this->genShowHideItem( 'container_blogs' );
				if ($bNoDB) {
					return $ret;
				} else {
					echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
				}
			} else
				return;
		} else {
			if ($bNoDB) {
				return MsgBox( _t('_im_textNoCurrUser') );
			} else {
				echo MsgBox( _t('_im_textNoCurrUser') );
			}
		}
	}
	
	function showBlockClassifieds( $sCaption, $bNoDB = false ) {
		global $site;

		$ID = $this -> _iProfileID;

		if ($ID > 0) {
			$sQuery = "
				SELECT DISTINCT
				COUNT(*)
				FROM `ClassifiedsAdvertisements`
				WHERE
				`ClassifiedsAdvertisements`.`IDProfile`  = '{$ID}' AND `ClassifiedsAdvertisements`.`Status` = 'active'
				GROUP BY `ClassifiedsAdvertisements`.`ID`
			";
			$iBlogs = db_value( $sQuery );

			if( $iBlogs > 0 ) {
				$oClassifieds = new BxDolClassifieds();
				$sBlocks = $oClassifieds->GenAnyBlockContent('last', $ID);
				$ret = <<<EOF
<div id="container_classifieds">
	{$sBlocks}
</div>
EOF;

				$show_hide = $this->genShowHideItem( 'container_classifieds' );
				if ($bNoDB) {
					return $ret;
				} else {
					echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
				}
			} else
				return;
		} else {
			if ($bNoDB) {
				return MsgBox( _t('_im_textNoCurrUser') );
			} else {
				echo MsgBox( _t('_im_textNoCurrUser') );
			}
		}
	}

	function showBlockEvents( $sCaption, $bNoDB = false ) {
		global $site;

		$ID = $this -> _iProfileID;

		if ($ID > 0) {
			$sQuery = "
				SELECT COUNT(`SDatingEvents`.`ID`) AS 'Cnt'
				FROM `SDatingEvents` 
				LEFT JOIN `SDatingParticipants` ON `SDatingParticipants`.`IDEvent` = `SDatingEvents`.`ID` 
				WHERE (`SDatingEvents`.`ResponsibleID` = '{$ID}' OR `SDatingParticipants`.`IDMember` = '{$ID}')
				AND `SDatingEvents`.`Status` = 'Active'
			";
			$iBlogs = db_value( $sQuery );

			if( $iBlogs > 0 ) {
				$oEvents = new BxDolEvents();
				$sBlocks = $oEvents->GenAnyBlockContent('last', $ID);
				$ret = <<<EOF
<div id="container_events">
	{$sBlocks}
</div>
EOF;

				$show_hide = $this->genShowHideItem( 'container_events' );
				if ($bNoDB) {
					return $ret;
				} else {
					echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
				}
			} else
				return;
		} else {
			if ($bNoDB) {
				return MsgBox( _t('_im_textNoCurrUser') );
			} else {
				echo MsgBox( _t('_im_textNoCurrUser') );
			}
		}
	}

	function showBlockGroups( $sCaption, $bNoDB = false ) {
		global $site;
		
		$ID = $this -> _iProfileID;

		if ($ID > 0) {
			$sQuery = "
				SELECT COUNT(`Groups`.`ID`) AS 'Cnt'
				FROM `GroupsMembers`, `Groups`
				WHERE 
				`Groups`.`status` = 'Active' AND
				`GroupsMembers`.`memberID` = {$ID} AND
				`GroupsMembers`.`groupID`  = `Groups`.`ID` AND
				`GroupsMembers`.`status`   = 'Active'
			";
			$iBlogs = db_value( $sQuery );

			if( $iBlogs > 0 ) {
				$oGroups = new BxDolGroups();
				$sBlocks = $oGroups->GenAnyBlockContent('last', $ID);
				$ret = <<<EOF
<div id="container_groups">
	{$sBlocks}
</div>
EOF;

				$show_hide = $this->genShowHideItem( 'container_groups' );
				if ($bNoDB) {
					return $ret;
				} else {
					echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
				}
			} else
				return;
		} else {
			if ($bNoDB) {
				return MsgBox( _t('_im_textNoCurrUser') );
			} else {
				echo MsgBox( _t('_im_textNoCurrUser') );
			}
		}
	}

		
	function showBlockMp3( $sCaption, $bNoDB = false )
	{
		global $logged;
		
		$iMemberId = (int)$_COOKIE['memberID'];
		$ret = getApplicationContent('mp3', 'player', array('id' => $this -> _iProfileID, 'password' => getPassword($iMemberId), 'vId' => $iMemberId), true);
		if ($bNoDB) {
			return $ret;
		} else {
			echo DesignBoxContent( _t( $sCaption ), '<div align="center">' . $ret . '</div>', 1, $show_hide );
		}
	}

	function showBlockMutualFriends( $sCaption, $bNoDB = false ) {
		global $site;
		$iFriendNums = getFriendNumber( $this -> _iProfileID );

		$ret = '';
		$iCounter = 0;
		$iTotalCounter = 0;
		foreach ($this -> aMutualFriends as $key => $value) {
			$iCounter ++;
			$sKey = '1';
			//if( $iCounter == 3 ) $sKey = '2';

			$ret .= '<div class="friends_thumb_'.$sKey.'">' . get_member_thumbnail($key, 'none', true) . '<div class="clear_both"></div><div class="browse_nick"><a href="' . getProfileLink($key) . '">' . $value . '</a></div><div class="clear_both"></div></div>';
			if( $iCounter == 3)  $iCounter = 0; 
			$iTotalCounter ++;
			if( $iTotalCounter >= 12 ) break;
		}
		if ($ret) {
			$ret .= '<div class="clear_both"></div>';
			$sFriendInfo = '<div class="caption_item"><a href="'.$site['url'].'viewFriends.php?iUser='.$this -> _iProfileID.'">'.$iFriendNums.' '._t("_Friends").'</a></div>';
			if ($bNoDB) {
				return $ret;
			} else {
				echo DesignBoxContent( _t( $sCaption ), $ret, 1, $sFriendInfo);
			}
		}
	}

    function FindMutualFriends () {
		global $logged; 
       
 	    if( $logged['member'] )
              $memberID = (int)$_COOKIE['memberID'];
        else
        $memberID = 0;  
	   
	   
			  $sQuery = "
				SELECT
					IF( `FriendList`.`ID` = {$memberID}, `FriendList`.`Profile` , `FriendList`.`ID` ) AS `friendID`,
					`Profiles`.`NickName`
				FROM `FriendList`
				INNER JOIN `FriendList` AS `FriendListMy` ON
				(
				`FriendListMy`.`ID`      = IF( `FriendList`.`ID` = {$memberID}, `FriendList`.`Profile` , `FriendList`.`ID` ) OR
				`FriendListMy`.`Profile` = IF( `FriendList`.`ID` = {$memberID}, `FriendList`.`Profile` , `FriendList`.`ID` )
				) AND
				`FriendListMy`.`Check` = 1
				INNER JOIN `Profiles` ON
					`Profiles`.`ID` = IF( `FriendList`.`ID` = {$memberID}, `FriendList`.`Profile` , `FriendList`.`ID` )
				WHERE
				(
				(
				`FriendList`.`ID` = {$memberID} OR
				`FriendList`.`Profile` = {$memberID}
				) AND
				`FriendList`.`Check` = 1
				) AND
				(
				(
				`FriendListMy`.`ID` = {$this -> _iProfileID} OR
				`FriendListMy`.`Profile` = {$this -> _iProfileID}
				) AND
				`FriendListMy`.`Check` = 1
				) AND
				IF( `FriendList`.`ID` = {$memberID}, `FriendList`.`Profile` , `FriendList`.`ID` ) != {$this -> _iProfileID}
				";
					
				  $rResult = db_res( $sQuery );
				  while( $aRow = mysql_fetch_assoc( $rResult ) )
					$this -> aMutualFriends[ $aRow['friendID'] ] = $aRow['NickName'];
				 
	        }
	 function getLinkStat($sType, $sCaption, $iMember) {
	 	$sType = htmlspecialchars_adv($sType);
	 	$sCaption = _t(htmlspecialchars_adv($sCaption));
	 	$iMember = (int)$iMember;
	 	switch ($sType) {
	 		case 'prPhoto':
	 			$sqlQueryStat = "SELECT COUNT(*) FROM `media` WHERE `med_type`='photo' AND `med_status`='active' AND `med_prof_id`='$iMember'";
	 			break;
	 		case 'prMusic':
	 			$sqlQueryStat = "SELECT COUNT(*) FROM `RayMp3Files` WHERE `Approved`='true' AND `Owner`='$iMember'";
	 			break;
	 		case 'prVideo':
	 			$sqlQueryStat = "SELECT `Approved` FROM `RayVideoStats` WHERE `User`='$iMember'";
	 			break;
	 	}
	 	$iCount = (int)db_value($sqlQueryStat);
	 	$sFinalCapt = $iCount ? $sCaption . ' ('.$iCount.')' : $sCaption;
	 	
	 	return $sFinalCapt;
	 }
}
?>
