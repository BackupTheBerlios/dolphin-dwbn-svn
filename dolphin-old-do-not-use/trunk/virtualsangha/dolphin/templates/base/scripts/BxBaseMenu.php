<?php

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolMenu.php' );

class BxBaseMenu extends BxDolMenu {
	function BxBaseMenu() {
		BxDolMenu::BxDolMenu();
	}
	
	
	
	
	function genTopHeader() {
		$iCurrent = $this -> checkShowCurSub() ? 0 : $this -> aMenuInfo['currentTop'];
		
		$this -> sCode .= '
			<script type="text/javascript">
				var currentTopItem = ' . $iCurrent . ';
			</script>
		';
		
		$this -> sCode .= '
			<div class="topMenu">';
	}
	
	function genTopItem( $sCaption, $sLink, $sTarget, $sOnclick, $bActive, $iItemID ) {
		if( !$bActive )
			$this -> sCode .= "
				<a href=\"$sLink\" target=\"$sTarget\" onclick=\"$sOnclick\">$sCaption</a>";
		else
			$this -> sCode .= "
				<b>$sCaption</b>";
	}
	
	function genTopDivider() {
		$this -> sCode .= '
				<br />';
	}
	
	function genTopFooter() {
		$this -> sCode .= '
			</div>';
	}
	
	
	
	
	
	function genSubContHeader() {
		$this -> sCode .= '
			<div class="subMenusContainer">';
	}
	
	function genSubHeader( $iTItemID, $sCaption, $sDisplay ) {
		$this -> sCode .= '
				<div class="subMenu" id="subMenu_' .$iTItemID . '" style="display: ' .$sDisplay . '">
					<div class="subMenuOvr">
					<h2>' . $sCaption . '</h2>';
	}
	
	function genSubItem( $sCaption, $sLink, $sTarget, $sOnclick, $bActive ) {
		if( !$bActive ) {
			$sOnclick = $sOnclick ? ( ' onclick="' . $sOnclick . '"' ) : '';
			$sTarget  = $sTarget  ? ( ' target="'  . $sTarget  . '"' ) : '';

			if ( strpos( $sLink, 'http://' ) === false && !strlen($sOnclick) )
				$sLink = ( $this -> oTemplConfig -> aSite['url'] ) . $sLink;
			
			$this -> sCode .= "
					<a href=\"$sLink\" {$sTarget} {$sOnclick}>$sCaption</a>";
		} else
			$this -> sCode .= "
					<b>$sCaption</b>";
	}
	
	function genSubFooter() {
		$this -> sCode .= '
				</div></div>';
	}
	
	function genSubContFooter() {
		$this -> sCode .= '
			</div>';
	}
	
}

?>