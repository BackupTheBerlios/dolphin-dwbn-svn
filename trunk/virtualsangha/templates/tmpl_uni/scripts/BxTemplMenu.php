<?php

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

require_once( BX_DIRECTORY_PATH_BASE . 'scripts/BxBaseMenu.php' );


class BxTemplMenu extends BxBaseMenu {
	function BxTemplMenu() {
		parent::BxBaseMenu();
	}
	
	function genTopHeader() {
		$this -> sCode .= '
					<table class="topMenu" cellpadding="0" cellspacing="1">
						<tr>';
	}
	
	function genTopDivider() {
		$this -> sCode .= '
						</tr>
						<tr>';
	}
	
	function genTopFooter() {
		$this -> sCode .= '
						</tr>
					</table>';
	}
	
	function genTopItem( $sText, $sLink, $sTarget, $sOnclick, $bActive, $iItemID ) {
		if( !$bActive ) {
			$sOnclick = $sOnclick ? ( ' onclick="' . $sOnclick . '"' ) : '';
			$sTarget  = $sTarget  ? ( ' target="'  . $sTarget  . '"' ) : '';
			
			if ( strpos( $sLink, 'http://' ) === false && !strlen($sOnclick) )
				$sLink = ( $this -> oTemplConfig -> aSite['url'] ) . $sLink;
			
			$sLinkA   = '<a href="' . $sLink . '"' . $sOnclick . $sTarget . ' showsub="#subMenu_' . $iItemID . '">' . $sText . '</a>';
		} else {
			$sShowSub = $this -> checkShowCurSub() ? ' showsub="#subMenu_' . $iItemID . '"' : '';
				
			$sLinkA   = '<b' . $sShowSub . '>' . $sText . '</b>';
		}
		
		$this -> sCode .= '
							<td>
								' . $sLinkA . '
							</td>';
		

		// onmouseover="this.className = \'topMenuItemHover\'; holdHiddenMenu = ' . $iItemID . '; showHiddenMenu( ' . $iItemID . ' );"
		// onmouseout="this.className = \'topMenuItem\'; holdHiddenMenu = currentTopItem; hideHiddenMenu( ' . $iItemID . ' );">';
	}
	
	function getActionsMenuItem( $sPicturePath, $sText, $sLink = '', $sPath = '', $sTarget = '', $onclick = '', $iconName = '' )
	{
		if( strlen( $sTarget ) )
			$sTarget  = ' target="' . $sTarget . '" ';
			
		if( strlen( $sPicturePath ) )
			$sPicturePath = getTemplateIcon( $sPicturePath );

		if( strlen( $onclick ) )
			$onclick = ' onclick="' . $onclick . '" ';

		if ( !strlen( $sPath ) && !strlen($onclick) )
			$sPath = $this -> oTemplConfig -> aSite['url'];
		
		$ret = "
		<div class=\"menuLine\">
			<div class=\"menuLinkBlock\" style=\"background-image: url('{$sPicturePath}')\">
				<a href=\"{$sPath}{$sLink}\" title=\"{$sText}\"{$sTarget}{$onclick} class=\"menuLink\">$sText</a>
			</div>
			<div class=\"clear_both\"></div>
		</div>";
		$ret .= '<div class="menuLineDivider"></div>';
		
		return $ret;
	}
}



?>