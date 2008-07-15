<?

class BxDolPageView {
	var $sPageName;
	var $aPage; // cache of this page
	var $sCode = '';
	var $sWhoViews = 'non';
	var $iMemberID = 0;
	var $bAjaxMode = false;
	
	function BxDolPageView( $sPageName ) {
		$this -> sPageName = $sPageName;
		
		if( !$this -> load() )
			return false;
		
		$this -> getViewerInfo();
		
		$this -> checkAjaxMode();
	}
	
	function checkAjaxMode() {
		if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )
			$this -> bAjaxMode = true;
	}

	function load() {
		$sCacheFile = BX_DIRECTORY_PATH_INC . 'db_cached/PageView.inc';
		
		if( !file_exists( $sCacheFile ) ) {
			echo '<br /><b>Warning</b> PageView cache not found';
			return false;
		}
		
		$sCache = @file_get_contents( $sCacheFile );
		if( !strlen( $sCache ) ) {
			echo '<br /><b>Warning!</b> PageView cache cannot be loaded. Please recompile.';
			return false;
		}
		
		$aCache = @eval( $sCache );
		if( !$aCache ) {
			echo '<br /><b>Warning!</b> PageView cache cannot be evaluated. Please recompile.';
			return false;
		}
		
		if( !array_key_exists( $this -> sPageName, $aCache ) ) {
			echo '<br /><b>Warning!</b> The page not found in PageView cache.';
			return false;
		}
		
		$this -> aPage = $aCache[ $this->sPageName ];
		
		//echoDbg( $this -> aPage );
		
		return true;
	}
	
	function getViewerInfo() {
		global $logged;
		
		if( $logged['member'] ) {
			$this -> sWhoViews = 'memb';
			$this -> iMemberID = (int)$_COOKIE['memberID'];
		}
	}
	
	function gen() {
		global $_page_cont, $_ni;
		
		if( !$this -> aPage )
			return false;
		$this -> genColumnsHeader();
		
		$_page_cont[$_ni]['main_div_width'] = $this -> aPage['Width'];
		
		foreach( array_keys( $this -> aPage['Columns'] ) as $iColumn )
			$this -> genColumn( $iColumn );
		
		$this -> genColumnsFooter();
	}
	
	function genOnlyBlock( $iBlockID ) {
		if( !$iBlockID )
			return false;

		// search block
		foreach( array_keys( $this -> aPage['Columns'] ) as $iColumn ) {
			$aColumn = $this -> aPage['Columns'][ $iColumn ];
			if( !$aColumn )
				return false;
			
			foreach( $aColumn['Blocks'] as $iMyBlockID => $aBlock )
				if( $iBlockID == $iMyBlockID ) {
					$this -> genBlock( $iMyBlockID, $aBlock, false );
					return true;
				}
		}
		return false;
	}
	
	function getCode() {
		
		if( !$this -> bAjaxMode )
			$this -> gen();
		else {
			$this -> genOnlyBlock( (int)$_REQUEST['pageBlock'] );
			echo $this -> sCode;
			exit;
		}
		
		return $this -> sCode;
	}
	
	//for customizability
	function genColumnsHeader() {
	}
	
	//for customizability
	function genColumnsFooter() {
		
	}
	
	function genColumn( $iColumn ) {
		$aColumn = $this -> aPage['Columns'][ $iColumn ];
		if( !$aColumn )
			return false;
		
		$this -> genColumnHeader( $iColumn, $aColumn['Width'] );
		
		foreach( $aColumn['Blocks'] as $iBlockID => $aBlock )
			$this -> genBlock( $iBlockID, $aBlock );
		
		$this -> genColumnFooter( $iColumn );
	}

	function getBlockCode_Topest($iColumn) {
		return '';
	}

	function genColumnHeader( $iColumn, $iColumnWidth ) {
		if( $iColumn == 1 )
			$sAddClass = ' page_column_first';
		elseif( $iColumn == count( $this -> aPage['Columns'] ) )
			$sAddClass = ' page_column_last';
		else
			$sAddClass = '';
		
		$this -> sCode .= '<div class="page_column' . $sAddClass . '" id="page_column_' . $iColumn . '" style="width: ' . $iColumnWidth . '%;">';

		$sBlockFunction = 'getBlockCode_Topest';
		$this -> sCode .=  $this -> $sBlockFunction($iColumn);
	}
	
	function genColumnFooter( $iColumn ) {
		$this -> sCode .= '</div>';
	}
	
	function genBlock( $iBlockID, $aBlock, $bAddWrapper = true ) {
		if( !$this -> isBlockVisible( $aBlock['Visible'] ) )
			return false;
		
		$sBlockFunction = 'getBlockCode_' . $aBlock['Func'];
		
		$mBlockCode = '';
		if( method_exists( $this, $sBlockFunction ) )
			$mBlockCode = $this -> $sBlockFunction( $iBlockID, $aBlock['Content'] );
		// $sBlockFunction can return simple string or array with two values:
		// 0 - content, 1 - array of caption links, 2 - bottom links
		
		$sCaptionCode  = '';
		$sBottomCode = '';
		
		if( is_array( $mBlockCode ) ) {
			$sBlockCode    = $mBlockCode[0];
			$sCaptionCode  = $this -> getBlockCaptionItemCode( $iBlockID, $mBlockCode[1] );
			if( is_array( $mBlockCode[2] ) )
				$sBottomCode = $this -> getBlockBottomCode( $iBlockID, $mBlockCode[2] );
		} elseif( is_string( $mBlockCode ) ) {
			$sBlockCode    = $mBlockCode;
		} else
			$sBlockCode    = false;
		
		if( !$sBlockCode )
			return false;
		
		$this -> sCode .=
			( $bAddWrapper ? '<div class="page_block_container" id="page_block_' . $iBlockID . '">' : '' ) .
				DesignBoxContent( _t( $aBlock['Caption'] ), $sBlockCode . $sBottomCode, $aBlock['DesignBox'], $sCaptionCode ) .
			( $bAddWrapper ? '</div>' : '' );
	}
	
	function isBlockVisible( $sVisible ) {
		if( strpos( $sVisible, $this -> sWhoViews ) === false )
			return false;
		else
			return true;
	}
	
	function getBlockCaptionItemCode( $iBlockID, $aLinks ) {
		
		$sCode = '
			<div class="dbTopMenu">';
		
		foreach( $aLinks as $sTitle => $aLink ) {
			$sTitle = htmlspecialchars_adv( _t( $sTitle ) );
			
			if( $aLink['active'] ) {
				$sCode .= '
				<div class="active">' . $sTitle .'</div>
				';
			} else {
				$sTarget  = $aLink['target']  ? ( ' target="' . $aLink['target'] . '"' ) : '';
				$sOnclick = $aLink['dynamic'] ? ( ' onclick="return !loadDynamicBlock(' . $iBlockID . ', this.href);"' ) : '';
				
				$sCode .= '
				<div class="notActive">
					<a href="' . htmlspecialchars_adv($aLink['href']) . '" class="top_members_menu"' .
					  $sTarget . $sOnclick . '>' .
					  	$sTitle .
					'</a>
				</div>
				';
			}
		}
		
		$sCode .= '
			</div>';
		
		return $sCode;
	}
	
	function getBlockBottomCode( $iBlockID, $aLinks ) {
		$sCode = '
			<div class="dbBottomMenu">';
		
		foreach( $aLinks as $sTitle => $aLink ) {
			$sTitle = htmlspecialchars_adv( $sTitle );
			$sClass = $aLink['class'] ? $aLink['class'] : 'moreMembers';
			
			if( $aLink['active'] ) {
				$sCode .= <<<BLAH
				<span class="$sClass">$sTitle</span>
BLAH;
			} else {
				$sTarget  = $aLink['target']  ? ( 'target="' . $aLink['target'] . '"' ) : '';
				$sOnclick = $aLink['dynamic'] ? ( 'onclick="return !loadDynamicBlock(' . $iBlockID . ', this.href);"' ) : '';
				
				$sCode .= <<<BLAH
				<a href="{$aLink['href']}" class="$sClass" $sTarget $sOnclick>$sTitle</a>
BLAH;
			}
		}
		
		$sCode .= '
			</div>';
		
		return $sCode;
	}



	/* * * * Page Blocks * * * */
	
	

	/**
	 * members statistic block
	 */
	function getBlockCode_MemberStat() {
		return getSiteStatUser();
	}
	
	
	function getBlockCode_Echo( $iBlockID, $sContent ) {
		return $sContent;
	}
	
	function getBlockCode_PHP( $iBlockID, $sContent ) {
		ob_start();
		eval($sContent);
		return ob_get_clean();
	}
	
	function getBlockCode_RSS( $iBlockID, $sContent ) {
		global $tmpl;
		global $logged;
		
		//echoDbg( $this );
		
		list( $sUrl, $iNum ) = explode( '#', $sContent );
		$iNum = (int)$iNum;
		
		//echo $this -> oProfileV -> _iProfileID . 'zzzz ';
		
		if( isset( $this -> oProfileV -> _iProfileID ) )
			$iAddID = $this -> oProfileV -> _iProfileID;
		elseif( $logged['member'] )
			$iAddID = $_COOKIE['memberID'];
		else
			$iAddID = 0;
		
		$sCode = '
			<div class="RSSAggrCont" rssid="' . $iBlockID . '" rssnum="' . $iNum . '" member="' . $iAddID . '">
				<div style="text-align: center;">
					<img src="templates/tmpl_' . $tmpl . '/images/loading.gif" alt="' . _t( '_loading ...' ) . '" />
				</div>
			</div>';
		
		return $sCode;
	}
	
	function getBlockCode_Shoutbox() {
		$sPassword = getPassword( $this -> iMemberID );
		return getApplicationContent('shoutbox', 'user', array('id' => $this -> iMemberID, 'password' => $sPassword), true);
	}
	
	function getBlockCode_SiteStats() {
		return getSiteStatUser();
	}
}