<?php

class BxDolMenu {
	var $aTopMenu;
	var $sCacheFile;
	var $aMenuInfo = array();
	var $oTemplConfig;
	var $sCode = '';
	var $iDivide; //divider or top items
	var $sRequestUriFile;
	var $sSelfFile;
	var $aNotShowSubsFor = array( 'index.php' );
	
	var $aPLinks = array(
		'articles.php'   => 	array('check'=>'permalinks_articles', 'link'=>'articles'),
		
		'browseMusic.php'=> 	array('check'=>'permalinks_gallery_music', 'link'=>'music/all/10/1'),
		'browseMusic.php?rate=top'=> array('check'=>'permalinks_gallery_music', 'link'=>'music/gallery_top'),
		'browseMusic.php?userID={profileID}'=> array('check'=>'permalinks_gallery_music', 'link'=>'music/gallery/all/{profileNick}'),
		'browseMusic.php?userID={memberID}'=> array('check'=>'permalinks_gallery_music', 'link'=>'music/gallery/all/{memberNick}'),
		
		'browsePhoto.php'=>		array('check'=>'permalinks_gallery_photos', 'link'=>'photo/all/10/1'),
		'browsePhoto.php?rate=top'=> array('check'=>'permalinks_gallery_photos', 'link'=>'photo/gallery_top'),
		'browsePhoto.php?userID={profileID}'=> array('check'=>'permalinks_gallery_photos', 'link'=>'photo/gallery/all/{profileNick}'),
		'browsePhoto.php?userID={memberID}'=> array('check'=>'permalinks_gallery_photos', 'link'=>'photo/gallery/all/{memberNick}'),
		
		'browseVideo.php'=>		array('check'=>'permalinks_gallery_videos', 'link'=>'video/all/10/1'),
		'browseVideo.php?rate=top'=> array('check'=>'permalinks_gallery_videos', 'link'=>'video/gallery_top'),
		'browseVideo.php?userID={profileID}'=> array('check'=>'permalinks_gallery_videos', 'link'=>'video/gallery/all/{profileNick}'),
		'browseVideo.php?userID={memberID}'=> array('check'=>'permalinks_gallery_videos', 'link'=>'video/gallery/all/{memberNick}'),
		
		'events.php?show_events=all&action=show'=>array('check'=>'permalinks_events', 'link'=>'events'),
		'events.php?action=show&show_events=my'=>array('check'=>'permalinks_events', 'link'=>'events/my'),
		'events.php?action=search'=>array('check'=>'permalinks_events', 'link'=>'events/search'),
		
		'classifieds.php?Browse=1'=>array('check'=>'permalinks_classifieds', 'link'=>'ads'),
		'classifieds.php?SearchForm=1'=>array('check'=>'permalinks_classifieds', 'link'=>'ads/search'),
		'classifiedsmy.php?MyAds=1'=>array('check'=>'permalinks_classifieds', 'link'=>'ads/my'),
		
		'blogs.php'		 =>			  array('check'=>'permalinks_blogs', 'link'=>'blogs'),
		'blogs.php?action=top_blogs'=>array('check'=>'permalinks_blogs', 'link'=>'blogs/top'),
		'blogs.php?action=top_posts'=>array('check'=>'permalinks_blogs', 'link'=>'blogs/top_posts'),
		'blogs.php?action=show_member_blog&ownerID={memberID}'=>array('check'=>'permalinks_blogs', 'link'=>'blogs/posts/{memberNick}'),
		'blogs.php?action=show_member_blog&ownerID={profileID}'=>array('check'=>'permalinks_blogs', 'link'=>'blogs/posts/{profileNick}'),
		
		'groups_home.php'=>			array('check'=>'permalinks_groups', 'link'=>'groups/all')
	);

	function BxDolMenu() {
		global $oTemplConfig;
		
		$this -> sCacheFile = BX_DIRECTORY_PATH_INC . 'db_cached/MenuContent.inc';
		$this -> oTemplConfig = &$oTemplConfig;
		
		$this -> iDivide = (int)getParam( 'topmenu_items_perline' );
		
		if( !$this -> load() )
			$this -> aTopMenu = array();
		
		$this -> getMenuInfo();
	}
	
	function load() {
		if( !file_exists( $this -> sCacheFile ) ) {
			echo '<b>Warning!</b> Cannot find Menu Cache file (' . $this -> sCacheFile . ').';
			return false;
		}
		
		$sCache = @file_get_contents( $this -> sCacheFile );
		if( !$sCache ) {
			echo '<b>Warning!</b> Cannot read Menu Cache file (' . $this -> sCacheFile . ').';
			return false;
		}
		
		$this -> aTopMenu = @eval( $sCache );
		if( !$this -> aTopMenu or !is_array( $this -> aTopMenu ) ) {
			echo '<b>Warning!</b> Cannot evaluate Menu Cache file (' . $this -> sCacheFile . ').';
			return false;
		}
		
		return true;
	}
	
	function getMenuInfo() {
		global $logged;
		global $p_arr;
		
		$aSiteUrl = parse_url( $this -> oTemplConfig -> aSite['url'] );
		$this -> sRequestUriFile = htmlspecialchars_adv( substr( $_SERVER['REQUEST_URI'], strlen( $aSiteUrl['path'] ) ) );
		$this -> sSelfFile       = htmlspecialchars_adv( substr( $_SERVER['PHP_SELF'],    strlen( $aSiteUrl['path'] ) ) );
		
		if( $logged['member'] ) {
			$this -> aMenuInfo['memberID']   = (int)$_COOKIE['memberID'];
			$this -> aMenuInfo['memberNick'] = getNickName( $this -> aMenuInfo['memberID'] );
			$this -> aMenuInfo['memberLink'] = getProfileLink( $this -> aMenuInfo['memberID'] );
			$this -> aMenuInfo['visible']    = 'memb';
		} else {
			$this -> aMenuInfo['memberID'] = 0;
			$this -> aMenuInfo['memberNick'] = '';
			$this -> aMenuInfo['memberLink'] = '';
			$this -> aMenuInfo['visible']  = 'non';
		}
		
		$selfFile = basename( $_SERVER['PHP_SELF'] );
		
		//get viewed profile ID
		if( $p_arr and $p_arr['ID'] ) {
			$this -> aMenuInfo['profileID']   = (int)$p_arr['ID'];
			$this -> aMenuInfo['profileNick'] = $p_arr['NickName'];
			$this -> aMenuInfo['profileLink'] = getProfileLink( $this -> aMenuInfo['profileID'] );
		
		} elseif( $selfFile == 'browseVideo.php' or $selfFile == 'browsePhoto.php' or $selfFile == 'browseMusic.php' ) {
			$this -> aMenuInfo['profileID']   = (int)$_GET['userID'];
			$this -> aMenuInfo['profileNick'] = getNickName(    $this -> aMenuInfo['profileID'] );
			$this -> aMenuInfo['profileLink'] = getProfileLink( $this -> aMenuInfo['profileID'] );
		
		} elseif( $selfFile == 'guestbook.php' ) {
			$this -> aMenuInfo['profileID']   = $_REQUEST['owner'] ? (int)$_REQUEST['owner'] : $this -> aMenuInfo['profileID'];
			$this -> aMenuInfo['profileNick'] = getNickName(    $this -> aMenuInfo['profileID'] );
			$this -> aMenuInfo['profileLink'] = getProfileLink( $this -> aMenuInfo['profileID'] );
		
		} elseif( $selfFile == 'blogs.php' ) {
			$this -> aMenuInfo['profileID']   = $_REQUEST['ownerID'] ? (int)$_REQUEST['ownerID'] : $this -> aMenuInfo['profileID'];
			$this -> aMenuInfo['profileNick'] = getNickName(    $this -> aMenuInfo['profileID'] );
			$this -> aMenuInfo['profileLink'] = getProfileLink( $this -> aMenuInfo['profileID'] );
		
		} elseif( $selfFile == 'viewFriends.php' ) {
			$this -> aMenuInfo['profileID']   = (int)$_GET['iUser'];
			$this -> aMenuInfo['profileNick'] = getNickName(    $this -> aMenuInfo['profileID'] );
			$this -> aMenuInfo['profileLink'] = getProfileLink( $this -> aMenuInfo['profileID'] );
		
		} elseif( $selfFile == 'photos_gallery.php' ) {
			$this -> aMenuInfo['profileID']   = (int)$_GET['ID'];
			$this -> aMenuInfo['profileNick'] = getNickName(    $this -> aMenuInfo['profileID'] );
			$this -> aMenuInfo['profileLink'] = getProfileLink( $this -> aMenuInfo['profileID'] );
		
		} else {
			$this -> aMenuInfo['profileID']   = 0;
			$this -> aMenuInfo['profileNick'] = '';
			$this -> aMenuInfo['profileLink'] = '';
		}
		
		// detect current menu
		$this -> aMenuInfo['currentCustom'] = 0;
		$this -> aMenuInfo['currentTop']    = 0;
		
		foreach( $this -> aTopMenu as $iItemID => $aItem ) {
			if( $aItem['Type'] == 'top' and $this -> aMenuInfo['currentTop'] and $this -> aMenuInfo['currentTop'] != $iItemID )
				break;
				
			$aItemUris = explode( '|', $aItem['Link'] );
			foreach( $aItemUris as $sItemUri ) {
				if( $this -> aMenuInfo['memberID'] ) {
					$sItemUri = str_replace( "{memberID}",    $this -> aMenuInfo['memberID'],    $sItemUri );
					$sItemUri = str_replace( "{memberNick}",  $this -> aMenuInfo['memberNick'],  $sItemUri );
					$sItemUri = str_replace( "{memberLink}",  $this -> aMenuInfo['memberLink'],  $sItemUri );
				}
				
				if( $this -> aMenuInfo['profileID'] ) {
					$sItemUri = str_replace( "{profileID}",   $this -> aMenuInfo['profileID'],   $sItemUri );
					$sItemUri = str_replace( "{profileNick}", $this -> aMenuInfo['profileNick'], $sItemUri );
					$sItemUri = str_replace( "{profileLink}", $this -> aMenuInfo['profileLink'], $sItemUri );
				}
				
				if( $sItemUri == $this -> sRequestUriFile or
				  ( substr( $this -> sRequestUriFile, 0, strlen( $sItemUri ) ) == $sItemUri and !(int)$aItem['Strict'] ) ) {
					if( $aItem['Type'] == 'custom' ) {
						$this -> aMenuInfo['currentCustom'] = $iItemID;
						$this -> aMenuInfo['currentTop']    = (int)$aItem['Parent'];
						break;
					} else { //top or system
						if( $this -> aMenuInfo['currentTop'] and $this -> aMenuInfo['currentTop'] != $iItemID )
							break;
						else
							$this -> aMenuInfo['currentTop'] = $iItemID;
					}
				}
			}
			
			if( $this -> aMenuInfo['currentCustom'] )
				break;
		}
	
		//echoDbg( $this -> aMenuInfo );
	}
	
	function getCode() {
		$this -> genTopHeader();
		$this -> genTopItems();
		$this -> genTopFooter();
		
		$this -> genSubContHeader();
		$this -> genSubMenus();
		$this -> genSubContFooter();
		
		return $this -> sCode;
	}
	
	function genTopItems() {
		$iCount = 0;
		
		foreach( $this -> aTopMenu as $iItemID => $aItem ) {
			if( $aItem['Type'] != 'top' )
				continue;
			
			if( !$this -> checkToShow( $aItem ) )
				continue;
			
			//generate
			list( $aItem['Link'] ) = explode( '|', $aItem['Link'] );
			
			$aItem['Link']    = $this -> replaceMetas( $aItem['Link'] );
			$aItem['Onclick'] = $this -> replaceMetas( $aItem['Onclick'] );
			
			$bActive = ( $iItemID == $this -> aMenuInfo['currentTop'] );
			$this -> genTopItem( _t( $aItem['Caption'] ), $aItem['Link'], $aItem['Target'], $aItem['Onclick'], $bActive, $iItemID );
			
			if( $this -> iDivide > 0 and ( ++$iCount % $this -> iDivide ) == 0 ) {
				$this -> genTopDivider();
				$iCount = 0;
			}
		}
	}
	
	function genSubMenus() {
		foreach( $this -> aTopMenu as $iTItemID => $aTItem ) {
			if( $aTItem['Type'] != 'top' && $aTItem['Type'] !='system')
				continue;
			
			if( !$this -> checkToShow( $aTItem ) )
				continue;
			
			if( $this -> aMenuInfo['currentTop'] == $iTItemID && $this -> checkShowCurSub() )
				$sDisplay = 'block';
			else
				$sDisplay = 'none';
			
			$sCaption = _t( $aTItem['Caption'] );
			
			$sCaption = $this -> replaceMetas( $sCaption );
			
			//generate
			$this -> genSubHeader( $iTItemID, $sCaption, $sDisplay );
			$this -> genSubItems( $iTItemID );
			$this -> genSubFooter();
			
			/*$ret .= '
				<div class="hiddenMenu" style="display:' . $sDisplay . ';" id="hiddenMenu_' . $iTItemID . '"
				  onmouseover="holdHiddenMenu = ' . $iTItemID . ';"
				  onmouseout="holdHiddenMenu = currentTopItem; hideHiddenMenu( ' . $iTItemID . ' )">
					<div class="hiddenMenuBgCont">
						<div class="hiddenMenuCont">
							<div class="topPageHeader">' . $sCaption . '</div>' .
							$this -> getCustomMenu( $iTItemID ) . '
						</div>
					</div>
					<div class="clear_both"></div>
				</div>';*/
		}
	}
	
	// check if to show current sub menu
	function checkShowCurSub() {
		foreach( $this -> aNotShowSubsFor as $sExcep )
			if( $this -> sSelfFile == $sExcep )
				return false;
		
		return true;
	}
	
	function checkToShow( $aItem ) {
		if( !$this -> checkVisible( $aItem['Visible'] ) )
			return false;
		
		if( !$this -> checkCond( $aItem['Check'] ) )
			return false;		
		
		return true;
	}
	
	function checkVisible( $sVisible ) {
		return ( strpos( $sVisible, $this -> aMenuInfo['visible'] ) !== false );
	}
	
	function checkCond( $sCheck ) {
		if( !$sCheck )
			return true;
		
		$sCheck = str_replace( '\$', '$', $sCheck );
		$fFunc = create_function('', $sCheck );
		
		return $fFunc();
	}
	
	function genSubItems( $iTItemID = 0 ) {
		if( !$iTItemID )
			$iTItemID = $this -> aMenuInfo['currentTop'];
		
		foreach( $this -> aTopMenu as $iItemID => $aItem ) {
			if( $aItem['Type'] != 'custom' )
				continue;
			
			if( $aItem['Parent'] != $iTItemID )
				continue;
			
			if( !$this -> checkToShow( $aItem ) )
				continue;
			
			//generate
			list( $aItem['Link'] ) = explode( '|', $aItem['Link'] );
			
			$aItem['Link']    = $this -> replaceMetas( $aItem['Link'] );
			$aItem['Onclick'] = $this -> replaceMetas( $aItem['Onclick'] );
			
			$bActive = ( $iItemID == $this -> aMenuInfo['currentCustom'] );
			
			$this -> genSubItem( _t( $aItem['Caption'] ), $aItem['Link'], $aItem['Target'], $aItem['Onclick'], $bActive );
		}
	}
	
	function replaceMetas( $sLink ) {
		$sLink = str_replace( '{memberID}',    $this -> aMenuInfo['memberID'],    $sLink );
		$sLink = str_replace( '{memberNick}',  $this -> aMenuInfo['memberNick'],  $sLink );
		$sLink = str_replace( '{memberLink}',  $this -> aMenuInfo['memberLink'],  $sLink );
		$sLink = str_replace( '{memberPass}',  getPassword( $this -> aMenuInfo['memberID'] ),  $sLink );
		
		$sLink = str_replace( '{profileID}',   $this -> aMenuInfo['profileID'],   $sLink );
		$sLink = str_replace( '{profileNick}', $this -> aMenuInfo['profileNick'], $sLink );
		$sLink = str_replace( '{profileLink}', $this -> aMenuInfo['profileLink'], $sLink );
		
		return $sLink;
	}

	function compile() {
		$fMenu = @fopen( $this -> sCacheFile , 'w' );
		if( !$fMenu )
			return false;
		
		fwrite( $fMenu, "return array(\n" );
		$aFields = array( 'Type','Caption','Link','Visible','Target','Onclick','Check','Strict','Parent' );
			
		$sQuery = "
			SELECT
				`ID`,
				`" . implode('`,
				`', $aFields ) . "`
			FROM `TopMenu`
			WHERE
				`Active` = 1 AND
				( `Type` = 'system' OR `Type` = 'top' )
			ORDER BY `Type`,`Order`
			";
	
		$rMenu = db_res( $sQuery );
		while( $aMenuItem = mysql_fetch_assoc( $rMenu ) ) {
			fwrite( $fMenu, "  " . str_pad( $aMenuItem['ID'], 2 ) . " => array(\n" );
			
			foreach( $aFields as $sKey => $sField ) {
				$sCont = $aMenuItem[$sField];
				
				if( $sField == 'Link' )
					$sCont = $this -> getCurrLink($sCont);
				
				$sCont = str_replace( '\\', '\\\\', $sCont );
				$sCont = str_replace( '"', '\\"',   $sCont );
				$sCont = str_replace( '$', '\\$',   $sCont );
				
				$sCont = str_replace( "\n", '',     $sCont );
				$sCont = str_replace( "\r", '',     $sCont );
				$sCont = str_replace( "\t", '',     $sCont );
				
				fwrite( $fMenu, "    " . str_pad( "'$sField'", 11 ) . " => \"$sCont\",\n" );
			}
			
			fwrite( $fMenu, "  ),\n" );
			
			// write it's children
			$sQuery = "
				SELECT
					`ID`,
					`" . implode('`,
					`', $aFields ) . "`
				FROM `TopMenu`
				WHERE
					`Active` = 1 AND
					`Type` = 'custom' AND
					`Parent` = {$aMenuItem['ID']}
				ORDER BY `Order`
				";
			
			$rCMenu = db_res( $sQuery );
			while( $aMenuItem = mysql_fetch_assoc( $rCMenu ) ) {
				fwrite( $fMenu, "  " . str_pad( $aMenuItem['ID'], 2 ) . " => array(\n" );
				
				foreach( $aFields as $sKey => $sField ) {
					$sCont = $aMenuItem[$sField];
					
					if( $sField == 'Link' )
						$sCont = $this -> getCurrLink($sCont);
					
					$sCont = str_replace( '\\', '\\\\', $sCont );
					$sCont = str_replace( '"', '\\"',   $sCont );
					$sCont = str_replace( '$', '\\$',   $sCont );
					
					$sCont = str_replace( "\n", '',     $sCont );
					$sCont = str_replace( "\r", '',     $sCont );
					$sCont = str_replace( "\t", '',     $sCont );
					
					fwrite( $fMenu, "    " . str_pad( "'$sField'", 11 ) . " => \"$sCont\",\n" );
				}
				
				fwrite( $fMenu, "  ),\n" );
			}
		}
		
		fwrite( $fMenu, ");\n" );
		
		fclose( $fMenu );
		return true;
	}
	
	// returns link according permalink settings
	function getCurrLink($sCont)
	{
		$aCurrLink = explode('|', $sCont);
		if ( array_key_exists( $aCurrLink[0], $this -> aPLinks ) ){
			$aCheck = $this -> aPLinks[$aCurrLink[0]];
			if (getParam($aCheck['check']) == 'on'){
				$aCurrLink[0] = $aCheck['link'];
				$sCont = implode( '|', $aCurrLink );
			}	
		}
		
		return htmlspecialchars_adv($sCont);
	}

}