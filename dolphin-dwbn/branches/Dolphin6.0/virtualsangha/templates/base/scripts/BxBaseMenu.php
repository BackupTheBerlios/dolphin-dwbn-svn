<?php

$aGlobalVars = array();
$aGlobalVars['free_mode'] 				= getParam("free_mode") == 'on' ? 1 : 0;
$aGlobalVars['enable_customization'] 	= getParam('enable_customization') == 'on' ? 1 : 0;
$aGlobalVars['enable_gallery'] 			= getParam('enable_gallery') == 'on' ? 1 : 0;
$aGlobalVars['enable_poll'] 			= getParam('enable_poll') == 'on' ? 1 : 0;
$aGlobalVars['enable_im'] 				= getParam("enable_im") == "on" ? 1 : 0;
$aGlobalVars['enable_profileComments'] 	= getParam("enable_profileComments") == "on" ? 1 : 0;
$aGlobalVars['enable_guestbook'] 		= $enable_guestbook;
$aGlobalVars['enable_blog'] 			= $enable_blog;
$aGlobalVars['enable_sdating'] 			= $en_sdating;
$aGlobalVars['enable_video'] 			= $enable_video_upload;
$aGlobalVars['enable_audio'] 			= $enable_audio_upload;
$aGlobalVars['anonymous_mode'] 			= $anon_mode;
$aGlobalVars['popUpWindowWidth']		= $oTemplConfig -> popUpWindowWidth;
$aGlobalVars['popUpWindowHeight']		= $oTemplConfig -> popUpWindowHeight;

$aChat  = db_arr("SELECT `Name` FROM `Modules` WHERE `Type` = 'chat'");
$aForum = db_arr("SELECT `Name` FROM `Modules` WHERE `Type` = 'forum'");

$aGlobalVars['enable_chat']				= is_array($aChat) ?  1 : 0;
$aGlobalVars['enable_forum']			= is_array($aForum) ? 1 : 0;



class BxBaseMenu
{
	var $aMenuConfig;
	var $bWithIcon;
	var $sSelfPageName;

	var $oTemplConfig;


	function BxBaseMenu( $oTemplConfig )
	{
		$this -> oTemplConfig = $oTemplConfig;
		$this -> sSelfPageName = basename( $_SERVER['SCRIPT_NAME'], '.php' );
	}

	/**
	 * collect link item
	 *
	 * @param string $text
	 * @param string $link
	 * @param string $path
	 * @param string $target
	 * @param String $onclick
	 * @return string
	 */
	function getMenuItem( $sText, $sLink, $sPath = '', $sTarget = '', $onclick = '', $iconName = '' )
	{
		if( strlen( $sTarget ) )
			$sTarget  = ' target="' . $sTarget . '" ';

		if( strlen( $onclick ) )
			$onclick = ' onclick="' . $onclick . '" ';

		if ( !strlen( $sPath ) && !strlen($onclick) )
			$sPath = $this -> oTemplConfig -> aSite['url'];
		
		if( !$this -> isMenuItemActive( $sLink ) or strlen( $onclick ) )
		{
			list( $sRealLink ) = explode( '|', $sLink );
			$ret = "
			<div class=\"menuLine\">
				<div class=\"menuLinkBlock\"><a href=\"{$sPath}{$sRealLink}\" title=\"{$sText}\"{$sTarget}{$onclick} class=\"menuLink\">$sText</a></div>
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

	function getTopMenuItem( $sText, $sLink, $sTarget, $onclick, $isActive )
	{
		if( strlen( $sTarget ) )
			$sTarget  = ' target="' . $sTarget . '" ';
		
		if( strlen( $onclick ) )
			$onclick = ' onclick="' . $onclick . '" ';
		
		if ( !strlen( $sPath ) && !strlen($onclick) )
			$sPath = $this -> oTemplConfig -> aSite['url'];

		$ret = '';
		if( $this -> isTopMenuItemActive( $sLink, $sPath ) )
		{
			$ret .= '<li class="activeTopMenuItem">';
				$ret .= '<div>';
					$ret .= $sText;
				$ret .= '</div>';
			$ret .= '</li>';
		}
		else
		{
			list( $sRealLink ) = explode( '|', $sLink );
			$ret .= '<li class="inactiveTopMenuItem" onmouseover="this.className=\'hoverTopMenuItem\'" onmouseout="this.className=\'inactiveTopMenuItem\'">';
				$ret .= '<div>';
					$ret .= '<a href="' . $sPath . $sRealLink . '" ' . $sTarget . $onclick . ' title="' . $sText .'">';
						$ret .= $sText;
					$ret .= '</a>';
				$ret .= '</div>';
			$ret .= '</li>';
		}




		return $ret;
	}

	function getTopMenu()
	{
		global $logged;
		$ret = '';


		$ret .= '<ul id="topMenuBlock">';

		$ret .= $this -> getTopMenuItem( _t('_Home'), 'index.php'  );
		if ( $logged['member'] )
		{
			$ret .= $this -> getTopMenuItem( _t('_Control Panel'), 'member.php' );
		}
		else
		{
			$ret .= $this -> getTopMenuItem( _t('_Log In'), 'member.php' );
		}

		if ( $logged['member'] )
		{
			$ret .= $this -> getTopMenuItem( _t('_FAQ'), 'faq.php' );
		}
		else
		{
			$ret .= $this -> getTopMenuItem( _t('_Join'), 'join_form.php' );
		}

		$ret .= $this -> getTopMenuItem( _t('_Search'), 'search.php' );
		$ret .= $this -> getTopMenuItem( _t('_Chat'), 'aemodule.php?ModuleType=chat', '', '_blank' );
		$ret .= $this -> getTopMenuItem( _t('_browse'), 'browse.php' );
		$ret .= $this -> getTopMenuItem( _t('_rate'), 'rate.php' );
		$ret .= '</ul>';

		return $ret;
	}

	function getCustomMenu( $forPage = '' )
	{
		
	}
	
	function loggedMemberMenu()
	{
		$iUserId = (int)$_COOKIE['memberID'];
		$ret = '';
		$ret .= '<div class="menuBlock">';
			$ret .= MemberMenuDesign($iUserId);
		$ret .= '</div>';
		return DesignBoxContent( _t('_Member menu'), $ret, $this -> oTemplConfig -> memberMenu_db_num);
	}

	function loggedAdminMenu()
	{
		$ret = '';
		$ret .= '<div class="menuBlock">';
			$ret .= $this -> getMenuItem( "Control panel", 'index.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Global Settings", 'global_settings.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Profiles", 'profiles.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Groups", 'groups.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    if ( $this -> oTemplConfig -> bEnableCustomization )
		        $ret .= $this -> getMenuItem( "Post Moderate", 'post_mod_profiles.php', $this -> oTemplConfig -> aSite['url_admin'] );

		    $ret .= $this -> getMenuItem( "Affiliates", 'partners.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    if ( !$this -> oTemplConfig -> bFreeMode )
		        $ret .= $this -> getMenuItem( "Finance", 'finance.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Pricing Policy", 'contact_discounts.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Manage PPs", 'payment_providers.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Mass mailer", 'notifies.php', $this -> oTemplConfig -> aSite['url_admin']  );
		    $ret .= $this -> getMenuItem( "Membership Levels", 'memb_levels.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Index Compose", 'index_compose.php', $this -> oTemplConfig -> aSite['url_admin']  );
		    $ret .= $this -> getMenuItem( "Profile Fields", 'profile_fields.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Split Join", 'split_join.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Language File", 'lang_file.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "CSS File", 'css_file.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Links", 'links.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Banners", 'banners.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "News", 'news.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Articles", 'articles.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Feedback", 'story.php', $this -> oTemplConfig -> aSite['url'] );
		    $ret .= $this -> getMenuItem( "Polls", 'polls.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Quotes", 'quotes.php', $this -> oTemplConfig -> aSite['url_admin'] );
		    $ret .= $this -> getMenuItem( "Log out", 'logout.php?action=admin_logout', $this -> oTemplConfig -> aSite['url'] );
		$ret .= '</div>';

		return DesignBoxContent( 'admin menu', $ret, $this->oTemplConfig->adminMenu_db_num);
	}

	function loggedAffMenu()
	{
		$ret = '';
		$ret .= '<div class="menuBlock">';
			$ret .= $this -> getMenuItem( "Home", 'index.php', '', '_home.gif' );
    		$ret .= $this -> getMenuItem( "Control panel", 'index.php', $this -> oTemplConfig -> aSite['url_aff'] );
    		$ret .= $this -> getMenuItem( "Profiles", 'profiles.php', $this -> oTemplConfig -> aSite['url_aff'] );
    		$ret .= $this -> getMenuItem( "Finance", 'finance.php', $this -> oTemplConfig -> aSite['url_aff'] );
    		$ret .= $this -> getMenuItem( "Help", 'help.php', $this -> oTemplConfig -> aSite['url_aff'] );
    		$ret .= $this -> getMenuItem( "Log out", 'logout.php?action=aff_logout' );
		$ret .= '</div>';
		return DesignBoxContent( 'affiliate menu', $ret, $this->oTemplConfig->affMenu_db_num );
	}

	function visitorMenu()
	{
		$ret = '';
		$ret .= '<div class="menuBlock">';
			$ret .= MemberMenuDesign(0);
		$ret .= '</div>';
		return DesignBoxContent( _t('_Visitor menu'), $ret, $this->oTemplConfig->visitorMenu_db_num );
	}

	function loggedModeratorMenu()
	{
		$ret = '';
		$ret .= '<div class="menuBlock">';
			$ret .= $this -> getMenuItem( "Moderator panel", 'index.php', $this -> oTemplConfig -> aSite['url'].'moderators/' );
    		$ret .= $this -> getMenuItem( _t("_Log Out"), 'logout.php?action=moderator_logout' );
		$ret .= '</div>';
		return DesignBoxContent( 'moderator menu', $ret, $this->oTemplConfig->moderatorMenu_db_num );
	}
	
	
/*		switch( $forPage )
		{
			case 'cc':
			case 'inbox':
			case 'outbox':
			case 'compose':
			case 'messages_inbox':
			case 'messages_outbox':
				return $this -> customMailMenu();
			break;
			case 'profile':
			case 'rewrite_name':
			case 'blog':
			case 'media_gallery':
				return $this -> customAdvancedProfileMenu();
			break;
			case 'profile_edit':
				return $this -> customSimpleProfileMenu();
			break;
			default:
				return '';
		}
*/
	function actionsMenu( $forPage = '' )
	{
		return '';
	}
	
	function isMenuItemActive( $sLink )
	{
		$sSelfName = htmlspecialchars( basename( $_SERVER['REQUEST_URI'] ) );
		
		$aLinks = explode( '|', $sLink );
		
		foreach( $aLinks as $sLinkTemp )
			if( substr( $sSelfName, 0, strlen( $sLinkTemp ) ) == $sLinkTemp )
				return true;
		
		return false;
	}
	
	function isTopMenuItemActive( $sLink )
	{
		return $this -> isMenuItemActive( $sLink );
	}
	
	function isCustomMenuItemActive( $sLink, $strict )
	{
		$sSelfName = htmlspecialchars( basename( $_SERVER['REQUEST_URI'] ) );
		
		$aLinks = explode( '|', $sLink );
		
		foreach( $aLinks as $sLinkTemp )
		{
			if( $strict ) //strict comparison
			{
				if( strcmp( $sSelfName, $sLinkTemp ) == 0 )
					return true;
			}
			else
			{
				if( substr( $sSelfName, 0, strlen( $sLinkTemp ) ) == $sLinkTemp )
					return true;
			}
		}
		
		return false;
	}


}

?>