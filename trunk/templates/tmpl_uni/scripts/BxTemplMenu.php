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


class BxTemplMenu extends BxBaseMenu
{
	function BxTemplMenu( $oTemplConfig )
	{
		parent::BxBaseMenu( $oTemplConfig );
	}
	
	function getTopMenu()
	{
		global $logged;
		
		if( $logged['member'] )
			$memberID = (int)$_COOKIE['memberID'];
		else
			$memberID = 0;
		
		$ret = '
				<div class="topMenu">
					<table class="topMenuCont" cellpadding="0" cellspacing="0">
						<tr>';
		
		$ret .= TopMenuDesign( (int)getParam('topmenu_items_perline'), '</tr><tr>' );
		
		$ret .= '
						</tr>
					</table>
				</div>';
		
		return $ret;
	}
	
	function getCustomMenu( $parent = 0 )
	{
		$ret = '<div class="topCustomMenu">';
			$ret .= CustomMenuDesign( $parent );
		$ret .= '</div>';
		
		return $ret;
	}
		
	function getTopMenuItem( $sText, $sLink, $sTarget, $onclick, $isActive, $iItemID )
	{
		if( strlen( $sTarget ) )
			$sTarget  = ' target="' . $sTarget . '" ';

		if( strlen( $onclick ) )
			$onclick = ' onclick="' . $onclick . '" ';

		if ( strpos( $sLink, 'http://' ) === false && !strlen($onclick) )
			$sLink = ( $this -> oTemplConfig -> aSite['url'] ) . $sLink;
		
		$ret = '';
		if( $isActive )
		{
			$ret .= '<td class="topMenuItemActive">';
				$ret .= '<div class="topMenuItemCont">';
					$ret .= $sText;
				$ret .= '</div>';
			$ret .= '</td>';
		}
		else
		{
			$ret .= '
			<td class="topMenuItem"
			  onmouseover="this.className = \'topMenuItemHover\'; holdHiddenMenu = ' . $iItemID . '; showHiddenMenu( ' . $iItemID . ' );"
			  onmouseout="this.className = \'topMenuItem\'; holdHiddenMenu = currentTopItem; hideHiddenMenu( ' . $iItemID . ' );">';
				$ret .= '<div class="topMenuItemCont">';
					$ret .= '<a href="' . $sLink . '" class="menu_item_link" ' . $sTarget . $onclick . '>';
						$ret .= $sText;
					$ret .= '</a>';
				$ret .= '</div>';
			$ret .= '</td>';
		}
		
		return $ret;
	}
	
	function getCustomMenuItem( $sText, $sLink, $sTarget, $onclick, $isActive )
	{
		if( strlen( $sTarget ) )
			$sTarget  = ' target="' . $sTarget . '" ';

		if( strlen( $onclick ) )
			$onclick = ' onclick="' . $onclick . '" ';

		if ( strpos( $sLink, 'http://' ) === false && !strlen($onclick) )
			$sLink = ( $this -> oTemplConfig -> aSite['url'] ) . $sLink;
	
		if( $isActive )
			$ret = "<span class=\"customMenuItemActive\">$sText</span> ";
		else
			$ret = '<a class="customMenuItem" href="' . $sLink . '" ' . $sTarget . $onclick . '>' . $sText . '</a> ';
		
		return $ret;
	}

	function visitorMenu()
	{
		global $logged;
		return LoginSection( $logged );
	}
	
	function loggedMemberMenu()
	{
		$memberID = (int)$_COOKIE['memberID'];
		$ret = '<div class="menuBlock">';
			$ret .= MemberMenuDesign( $memberID );
		$ret .= '</div>';
		return DesignBoxContent( _t('_Member menu'), $ret, $this -> oTemplConfig -> memberMenu_db_num);
	}
	

	function wrapCustomMenu( $cont )
	{
		return "<div class=\"topCustomMenu\">$cont</div>";
	}
	
	function getMenuItem( $sPicturePath, $sText, $sLink = '', $sPath = '', $sTarget = '', $onclick = '', $iconName = '' )
	{
		if( strlen( $sTarget ) )
			$sTarget  = ' target="' . $sTarget . '" ';
			
		if( strlen( $sPicturePath ) )
			$sPicturePath = getTemplateIcon( $sPicturePath );

		if( strlen( $onclick ) )
			$onclick = ' onclick="' . $onclick . '" ';

		if ( !strlen( $sPath ) && !strlen($onclick) )
			$sPath = $this -> oTemplConfig -> aSite['url'];
		
		if( !$this -> isMenuItemActive( $sLink ) or strlen( $onclick ) )
		{
			list( $sRealLink ) = explode( '|', $sLink );
			$ret = "
			<div class=\"menuLine\">
				<div class=\"menuLinkBlock\" style=\"background-image: url('{$sPicturePath}')\"><a href=\"{$sPath}{$sRealLink}\" title=\"{$sText}\"{$sTarget}{$onclick} class=\"menuLink\">$sText</a></div>
				<div class=\"clear_both\"></div>
			</div>";
		}
		else
		{
			$ret = "
			<div class=\"menuLineActive\">
				<div class=\"menuLinkBlock\">$sText</div>
				<div class=\"clear_both\"></div>
			</div>";
		}
		
		$ret .= '<div class="menuLineDivider"></div>';
		
		return $ret;
	}
}

?>