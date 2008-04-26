<?

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfileView.php' );
require_once( BX_DIRECTORY_PATH_ROOT . 'profilePhotos.php' );

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolClassifieds.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolEvents.php' );

class BxBaseProfileView extends BxDolProfileView
{
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

	function BxBaseProfileView( $ID )
	{
		$this -> aMutualFriends = array();
		BxDolProfileView::BxDolProfileView( $ID );		
		$this -> FindMutualFriends();
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
	
	function genColumns()
	{
		ob_start();
		
		?>
		<div id="thin_column">
			<? $this -> showColumnBlocks( 1 ); ?>
		</div>
		
		<div id="thick_column">
			<? $this -> showColumnBlocks( 2 ); ?>
		</div>
		<?
		
		return ob_get_clean();
	}
	
	function showColumnBlocks( $column )
	{
		global $logged;
		if( $logged['member'] )
			$sVisible = 'memb';
		else
			$sVisible = 'non';
		
		$rBlocks = db_res( "SELECT * FROM `ProfileCompose` WHERE `Column`=$column AND FIND_IN_SET( '$sVisible', `Visible` ) ORDER BY `Order`" );
		while( $aBlock = mysql_fetch_assoc( $rBlocks ) )
		{
			$func = 'showBlock' . $aBlock['Func'];
			$this -> $func( $aBlock['Caption'], $aBlock['Content'] );
		}
	}
	
	function showBlockPhoto( $sCaption )
	{
		global $memberID;
		global $p_arr;
		
		$oPhotos = new ProfilePhotos( $this -> _iProfileID );
		$oPhotos -> getActiveMediaArray();
/*
		//perform photo voting
		if( $_REQUEST['voteSubmit'] && $_REQUEST['photoID'] )
		{
			$oPhotos -> setVoting();
			$oPhotos -> getActiveMediaArray();
		}
*/
		$iPhotoID = (int)$_REQUEST['photoID'];
		$ret = $oPhotos -> getMediaBlock( $iPhotoID );
		
		echo DesignBoxContent( _t( $sCaption, $p_arr['NickName'] ), $ret, 1 );
	}


	function showBlockRSS( $sCaption, $sContent )
    {
        global $p_arr, $site;

		list( $sUrl, $iNum ) = explode( '#', $sContent );
		$iNum = (int)$iNum;
        
        $sUrl = str_replace(array('{SiteUrl}', '{NickName}'),array($site['url'], $p_arr['NickName']), $sUrl);

		$ret = genRSSHtmlOut( $sUrl, $iNum );
		
		echo DesignBoxContent( _t($sCaption), $ret, 1 );
	}
	
	function showBlockEcho( $sCaption, $sContent )
	{
		echo DesignBoxContent( _t($sCaption), $sContent, 1 );
	}
	
	function showBlockLookingForDetails( $sCaption )
	{
		$aFields  = $this -> collectProfileFieldsByCateg( 4 );
		$sDetails = $this -> showProfileFields( $aFields );
		
		if( strlen( $sDetails ) )
		{
			ob_start();
			?>
				<div id="profile_details_wrapper">
					<div class="clear_both"></div>
					<?= $sDetails ?>
					<div class="clear_both"></div>
				</div>
			<?
			$ret = ob_get_clean();
			
			echo DesignBoxContent( _t( $sCaption ), $ret, 1 );
		}
	}
	
	function showBlockProfilePolls( $sCaption )
	{
		$sqlPolls = "SELECT `id_poll` FROM `ProfilesPolls` WHERE `id_profile` = {$this -> _iProfileID} AND `poll_status` = 'active' AND `poll_approval`";
		$rPolls = db_res( $sqlPolls );
		
		if( !mysql_num_rows( $rPolls ) )
			return ;
		
		$ret = '<div id="profile_poll_wrap">';
		while( $aPoll = mysql_fetch_assoc( $rPolls ) )
		{
			$ret .= ShowPoll( $aPoll['id_poll'] );
			$ret .= '<div class="clear_both"></div>';
		}
		$ret .= '</div>';
		
		$show_hide = $this -> genShowHideItem( 'profile_poll_wrap' );
		
		echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
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
	
	function showBlockActionsMenu( $sCaption )
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
			
			$IMNow = $oTemplMenu -> getMenuItem( 'action_im.gif', _t( '_ChatNow', $p_arr['NickName'] ), "javascript:void(0);", '', '', "openRayWidget( 'im', 'user', '$memberID', '$sSndPassword', '$profileID' );" );
		}
		else
			$IMNow = '';
		
		/* * * * Ray IM Integration [END]* * * */
		
		$ret = '<div class="menuBlock">';
			$ret .= '<div class="menu_item_block">';
			$ret .= '<div class="menu_item_block_left">';
				$ret .= $oTemplMenu -> getMenuItem( 'action_send.gif', _t('_SendLetter'),     "compose.php?ID=$profileID" );
				$ret .= $oTemplMenu -> getMenuItem( 'action_fave.gif', _t('_Fave'),     "javascript:void(0);", '', '', "window.open( 'list_pop.php?action=hot&amp;ID=$profileID',    '', 'width={$this -> oTemplConfig -> popUpWindowWidth},height={$this -> oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );" );
				$ret .= $oTemplMenu -> getMenuItem( 'action_friends.gif', _t('_Befriend'),"javascript:void(0);", '', '', "window.open( 'list_pop.php?action=friend&amp;ID=$profileID', '', 'width={$this -> oTemplConfig -> popUpWindowWidth},height={$this -> oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );" );
				$ret .= $oTemplMenu -> getMenuItem( 'action_greet.gif', _t('_Greet'),     "javascript:void(0);", '', '', "window.open( 'greet.php?sendto=$profileID',                  '', 'width={$this -> oTemplConfig -> popUpWindowWidth},height={$this -> oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );" );
				$ret .= $IMNow;
				if ( !$this -> oTemplConfig -> bAnonymousMode )
					$ret .= $oTemplMenu -> getMenuItem( 'action_email.gif', _t('_Get E-mail'),   "javascript:void(0);", '', '', "window.open( 'freemail.php?ID=$profileID', '', 'width={$this -> oTemplConfig -> popUpWindowWidth},height={$this -> oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );" );
				$ret .= '</div>';
				$ret .= '<div class="menu_item_block_right">';
				$ret .= $oTemplMenu -> getMenuItem( 'action_photos.gif', _t('_ProfilePhotos'), "photos_gallery.php?ID=$profileID");
				$ret .= $oTemplMenu -> getMenuItem( 'action_videos.gif', _t('_ProfileVideos'),   "javascript:void(0);", '', '', "openRayWidget( 'video', 'player', '$profileID' );" );
				//$ret .= $oTemplMenu -> getMenuItem( 'action_videos.gif', _t('_ProfileVideos'), "media_gallery.php?show=video&ID=$profileID");
				$ret .= $oTemplMenu -> getMenuItem( 'action_music.gif', _t('_ProfileMusic'), "javascript:void(0);", '', '', "openRayWidget( 'mp3', 'player', '$profileID', '" . getPassword( $memberID ) . "', '$memberID');");
				$ret .= $oTemplMenu -> getMenuItem( 'action_share.gif', _t('_Share'),   "javascript:void(0);", '', '', "return launchTellFriendProfile($profileID);" );
				$ret .= $oTemplMenu -> getMenuItem( 'action_report.gif', _t('_Report'),   "javascript:void(0);", '', '', "window.open( 'list_pop.php?action=spam&amp;ID=$profileID',   '', 'width={$this -> oTemplConfig -> popUpWindowWidth},height={$this -> oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );" );
				$ret .= $oTemplMenu -> getMenuItem( 'action_block.gif', _t('_Block'),    "javascript:void(0);", '', '', "window.open( 'list_pop.php?action=block&amp;ID=$profileID',  '', 'width={$this -> oTemplConfig -> popUpWindowWidth},height={$this -> oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no,location=no' );" );
			$ret .= '</div>';
			$ret .= '<div class="clear_both"></div>';
			$ret .= '</div>';
		$ret .= '</div>';
		echo DesignBoxContent( _t( $sCaption ), $ret, 1 );
	}
	
	function showBlockRateProfile( $sCaption )
	{
		global $site;
		global $votes;
		
        // Check if profile votes enabled
        if (!$votes || !$this->oVotingView->isEnabled()) return;

        $ret = $this->oVotingView->getBigVoting();

        echo DesignBoxContent( _t( $sCaption ), $ret, 1 );
	}
	
	function showBlockProfileDetails( $sCaption )
	{
		global $prof;
		global $enable_zodiac;
		global $p_arr;
		global $site;
		
		$aFields  = $this -> collectProfileFieldsByCateg( 0 );
		$sDetails = $this -> showProfileFields( $aFields );
		
		$sTagsAddon = "";
		$sTagsQuery = "SELECT `Tag` FROM `Tags` WHERE `ID`='{$p_arr['ID']}' AND `Type` = 'profile'";
		$rTags = db_res( $sTagsQuery );
		while( $aTags = mysql_fetch_assoc( $rTags ) )
			$sTagsAddon .= "<a href='" . $site['url'] . "search_result.php?tag=" . $aTags['Tag'] . "'>" . $aTags['Tag'] . "</a>, ";
		$sTagsAddon = rtrim ($sTagsAddon, ", "); 
		$bOnlineStatus = get_user_online_status($p_arr['ID']);
		$sOnlineStatus = ($bOnlineStatus == true) ? _t('Online') : _t('Offline');
		ob_start();
		?>
			<div id="profile_details_wrapper">
				<table>
					<?= $sDetails ?>
					<tr>
						<td class="profile_td_1">Tags:</td>
						<td class="profile_td_2"><?php echo $sTagsAddon ?></td>
					</tr>
					<tr>
						<td class="profile_td_1">Status:</td>
						<td class="profile_td_2"><div class="member_status"><?php echo $sOnlineStatus ?></div></td>
					</tr>
				</table>
			</div>
		<?
		$ret = ob_get_clean();
		
		echo DesignBoxContent( _t( $sCaption, $p_arr['NickName'] ), $ret, 1 );
	}
	
	function showBlockDescriptions( $sCaption )
	{
		if( strlen(  $this -> _aProfile['DescriptionMe'] ) )
		{
			$text = '<div class="discr">' . 
				'<div class="rss_item_header">' . process_text_output( $this -> _aProfile['Headline'] ) . '</div>' .
				process_smiles( process_text_output( $this -> _aProfile['DescriptionMe'] ) ) .
			'</div>';
			echo DesignBoxContent( _t( $sCaption ),  $text, 1 );
		}
		
		if( strlen( $this -> _aProfile['DescriptionYou'] ) )
		{
			$text = '<div class="discr">' . process_smiles( process_text_output( $this -> _aProfile['DescriptionYou'] ) ) . '</div>';
			echo DesignBoxContent( _t( '_Ideal match description' ), $text, 1 );
		}
	}
	
	function showBlockFriends( $sCaption )
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
			
			echo DesignBoxContent( _t( $sCaption ), $ret, 1, $sFriendInfo );
		}
	}

	function showBlockProfileBlog( $sCaption )
	{
		global $site;
		global $short_date_format;
		$php_date_format = getParam( 'php_date_format ');
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		$ID = $this -> _iProfileID;
		$sQuery = "
			SELECT DISTINCT
				`BlogPosts`.`PostID`,
				`BlogPosts`.`CategoryID`,
				`BlogPosts`.`PostText`,
				UNIX_TIMESTAMP( `BlogPosts`.`PostDate` ) AS `PostDate`,
				`BlogPosts`.`PostCaption`,
				`BlogCategories`.`CategoryName`,
				COUNT( `BlogPostComments`.`CommentID` ) AS `CommentsNum`
			FROM `BlogCategories`
			INNER JOIN `BlogPosts` ON
				 `BlogCategories`.`CategoryID` = `BlogPosts`.`CategoryID`
			LEFT JOIN `BlogPostComments` ON
				`BlogPosts`.`PostID` = `BlogPostComments`.`PostID`
			WHERE
				`BlogCategories`.`OwnerID`  = {$ID} AND
				`BlogPosts`.`PostReadPermission` = 'public' AND
				`BlogPosts`.`PostStatus`         = 'approval'
			GROUP BY
				`BlogPosts`.`PostID`
			ORDER BY
				`BlogPosts`.`PostDate` DESC
			LIMIT 5
			";
		
		$rBlogs = db_res( $sQuery );
		
		if( !mysql_num_rows( $rBlogs ) )
			return '';
		
		ob_start();
		?>
		<div id="container_blogs">
		<?
		
		while( $aBlog = mysql_fetch_assoc( $rBlogs ) )
		{
			$sLinkMore = '';
			if( strlen( $aBlog['PostText']) > $iBlogLimitChars ) 
				//$sLinkMore = "... <a href=\"".$site['url']."blog.php?owner=".$ID."&show=blog&blogID=".$aBlog['PostID']."\">"._t('_Read more')."</a>";
				$sLinkMore = "... <a href=\"".$site['url']."blogs.php?action=show_member_post&amp;ownerID=".$ID."&amp;post_id=".$aBlog['PostID']."\">"._t('_Read more')."</a>";

			$sBlogSnippet = mb_substr( strip_tags( $aBlog['PostText'] ), 0, $iBlogLimitChars ) . $sLinkMore;
			?>
				<div class="blogBlock">
					<div class="blogHead">
						<? echo '<a href="' . $site['url'] . 'blogs.php?action=show_member_post&amp;ownerID='.$ID.'&amp;post_id=' . $aBlog['PostID'] . '" class="bottom_text">' ?>
							<?= $aBlog['PostCaption'] ?>
						</a>
					</div>
					<div class="blogInfo">
						<span><img src="<?= getTemplateIcon( 'clock.gif' ) ?>" /><?= date( $php_date_format, $aBlog['PostDate'] ) . ' ' ?></span>
						<span><?= _t( '_in Category', getTemplateIcon( 'folder_small.png' ), 'blogs.php?action=show_member_blog&ownerID='.$ID.'&category='.$aBlog['CategoryID'], $aBlog['CategoryName'] ) . '; ' ?></span>
						<span><?= _t( '_comments N', getTemplateIcon( 'add_comment.gif' ), $aBlog['CommentsNum'] ) ?></span>
					</div>
					<div class="blogSnippet">
						<?= $sBlogSnippet ?>
					</div>
				</div>
			<?
		}		
		?>
		</div>
		<?
		$ret = ob_get_clean();
		
		$show_hide = $this -> genShowHideItem( 'container_blogs' );
		
		echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
		
	}
	
	function showBlockClassifieds( $sCaption )
	{
		global $site;
		global $short_date_format;
		$php_date_format = getParam( 'php_date_format ');
		//$iBlogLimitChars = (int)getParam("max_classified_preview");
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		$ID = $this -> _iProfileID;
		$sQuery = "
			SELECT DISTINCT
			`ClassifiedsAdvertisements`.`ID`,
			`ClassifiedsAdvertisements`.`Subject`,
			`ClassifiedsAdvertisements`.`Media`,
			`Profiles`.`NickName`,
			UNIX_TIMESTAMP( `ClassifiedsAdvertisements`.`DateTime` ) as `DateTime_f`,
			`ClassifiedsAdvertisements`.`DateTime`,
			`Classifieds`.`Name`, `Classifieds`.`ID` AS `CatID`,
			`ClassifiedsSubs`.`NameSub`, `ClassifiedsSubs`.`ID` AS `SubCatID`,
			`ClassifiedsAdvertisements`.`Message`,
			COUNT(`ClsAdvComments`.`ID`) AS 'CommCount'
			FROM `ClassifiedsAdvertisements`
			LEFT JOIN `ClassifiedsSubs`
			ON `ClassifiedsSubs`.`ID`=`ClassifiedsAdvertisements`.`IDClassifiedsSubs`
			LEFT JOIN `Classifieds`
			ON `Classifieds`.`ID`=`ClassifiedsSubs`.`IDClassified`
			LEFT JOIN `Profiles` ON `Profiles`.`ID`=`ClassifiedsAdvertisements`.`IDProfile`
			LEFT JOIN `ClsAdvComments` ON `ClsAdvComments`.`IDAdv`=`ClassifiedsAdvertisements`.`ID`
			WHERE
			`ClassifiedsAdvertisements`.`IDProfile`  = {$ID} AND
			`ClassifiedsAdvertisements`.`Status` = 'active'
			GROUP BY `ClassifiedsAdvertisements`.`ID`
			ORDER BY `DateTime` DESC
			LIMIT 5
		";

		$rBlogs = db_res( $sQuery );
		
		if( !mysql_num_rows( $rBlogs ) )
			return '';
		
		ob_start();
		?>
		<div id="container_classifieds">
		<?

		$oClassifieds = new BxDolClassifieds();

		while( $aBlog = mysql_fetch_assoc( $rBlogs ) )
		{
			$sPic = $oClassifieds->getImageCode($aBlog['Media'],TRUE);

			$sLinkMore = '';
			if( strlen( $aBlog['Message']) > $iBlogLimitChars ) 
				//$sLinkMore = "... <a href=\"".$site['url']."blog.php?owner=".$ID."&show=blog&blogID=".$aBlog['PostID']."\">"._t('_Read more')."</a>";
				$sLinkMore = "... <a href=\"".$site['url']."classifieds.php?ShowAdvertisementID=".$aBlog['ID']."\">"._t('_Read more')."</a>";

			$sBlogSnippet = mb_substr( strip_tags( $aBlog['Message'] ), 0, $iBlogLimitChars ) . $sLinkMore;
			?>
				<div class="thumbnail_block" style="float:left;width:45px;height:45px;margin-right:10px;margin-top:10px;position:relative;">
					<?= $sPic ?>
				</div>
				<div class="blog_wrapper" style="width:290px;float:left;position:relative;">
					<div class="blogHead1" style="">
						<? echo '<a href="' . $site['url'] . 'classifieds.php?ShowAdvertisementID=' . $aBlog['ID'] . '" class="bottom_text">' ?>
							<?= $aBlog['Subject'] ?>
						</a>
					</div>
					<div class="blogInfo">
						<span><img src="<?= getTemplateIcon( 'clock.gif' ) ?>" /><?= date( $php_date_format, $aBlog['DateTime_f'] ) . ' ' ?></span>
						<span><?= _t( '_in Category', getTemplateIcon( 'folder_small.png' ), 'classifieds.php?bClassifiedID='.$aBlog['CatID'], process_line_output($aBlog['Name']) ).' / '.
						'<a href="classifieds.php?bSubClassifiedID=' . $aBlog['SubCatID'].'">'.process_line_output($aBlog['NameSub']).'</a>' ?></span>
						<span><?= _t( '_comments N', getTemplateIcon( 'add_comment.gif' ), $aBlog['CommCount'] ) ?></span>
					</div>
					<div class="blogSnippet">
						<?= $sBlogSnippet ?>
					</div>
				</div>
				<div class="clear_both"></div>
			<?
		}		
		?>
		</div>
		<?
		$ret = ob_get_clean();
		
		$show_hide = $this -> genShowHideItem( 'container_classifieds' );
		
		echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
		
	}

	function showBlockEvents( $sCaption )
	{
		global $site;
		global $short_date_format;
		$php_date_format = getParam( 'php_date_format' );
		//$iBlogLimitChars = (int)getParam("max_classified_preview");
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		$ID = $this -> _iProfileID;
		$sQuery = "
			SELECT DISTINCT `SDatingEvents`. * , `Profiles`.`NickName`,
			UNIX_TIMESTAMP( `SDatingEvents`.`EventStart` ) as `DateTime_f`
			FROM `SDatingEvents` 
			LEFT JOIN `Profiles` ON `Profiles`.`ID` = `SDatingEvents`.`ResponsibleID` 
			WHERE `SDatingEvents`.`ResponsibleID` = {$ID} AND
			`SDatingEvents`.`Status` = 'Active'
			ORDER BY `EventStart` DESC 
			LIMIT 5
		";
		
		$rBlogs = db_res( $sQuery );
		
		if( !mysql_num_rows( $rBlogs ) )
			return '';
		
		ob_start();
		?>
		<div id="container_events">
		<?

		$oEvents = new BxDolEvents();

		while( $aBlog = mysql_fetch_assoc( $rBlogs ) )
		{
			$sPic = $oEvents->GetEventPicture($aBlog['ID']);

			$sLinkMore = '';
			if( strlen( $aBlog['Description']) > $iBlogLimitChars ) 
				//$sLinkMore = "... <a href=\"".$site['url']."blog.php?owner=".$ID."&show=blog&blogID=".$aBlog['PostID']."\">"._t('_Read more')."</a>";
				$sLinkMore = "... <a href=\"".$site['url']."events.php?action=show_info&amp;event_id=".$aBlog['ID']."\">"._t('_Read more')."</a>";

			$sBlogSnippet = mb_substr( strip_tags( $aBlog['Description'] ), 0, $iBlogLimitChars ) . $sLinkMore;
			?>
				<div class="thumbnail_block" style="float:left;width:45px;height:45px;margin-right:10px;margin-top:10px;position:relative;">
					<?= $sPic ?>
				</div>
				<div class="blog_wrapper" style="width:290px;float:left;position:relative;">
					<div class="blogHead1">
						<? echo '<a href="' . $site['url'] . 'events.php?action=show_info&amp;event_id=' . $aBlog['ID'] . '" class="bottom_text">' ?>
							<?= $aBlog['Title'] ?>
						</a>
					</div>
					<div class="blogInfo">
						<span><img src="<?= getTemplateIcon( 'clock.gif' ) ?>" /><?= date( $php_date_format, $aBlog['DateTime_f'] ) . ' ' ?></span>
					</div>
					<div class="blogSnippet">
						<?= $sBlogSnippet ?>
					</div>
				</div>
				<div class="clear_both"></div>
			<?
		}		
		?>
		</div>
		<?
		$ret = ob_get_clean();
		
		$show_hide = $this -> genShowHideItem( 'container_events' );
		
		echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
	}

	function showBlockGroups( $sCaption )
	{
		global $site;
		global $short_date_format;
		$php_date_format = getParam( 'php_date_format ' );
		//$iBlogLimitChars = (int)getParam("max_classified_preview");
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		$ID = $this -> _iProfileID;
		$sQuery = "
			SELECT DISTINCT `Groups`.`ID`, `Groups`.`Name`, `Groups`.`Desc`,
			UNIX_TIMESTAMP( `Groups`.`created` ) as `DateTime_f`,
			`Profiles`.`NickName`,
			`GroupsCateg`.`Name` AS 'CategName', `GroupsCateg`.`ID` AS `CategID`
			FROM `GroupsMembers`, `Groups`
			LEFT JOIN `GroupsCateg` ON `GroupsCateg`.`ID` = `Groups`.`categID` 
			LEFT JOIN `Profiles` ON `Profiles`.`ID` = `Groups`.`creatorID` 
			WHERE 
			`Groups`.`status` = 'Active' AND
			`GroupsMembers`.`memberID` = {$ID} AND
			`GroupsMembers`.`groupID`  = `Groups`.`ID` AND
			`GroupsMembers`.`status`   = 'Active'
			ORDER BY `created` DESC 
			LIMIT 5
		";
		
		$rBlogs = db_res( $sQuery );
		
		if( !mysql_num_rows( $rBlogs ) )
			return '';
		
		ob_start();
		?>
		<div id="container_groups">
		<?

		$oEvents = new BxDolEvents();

		while( $aBlog = mysql_fetch_assoc( $rBlogs ) )
		{
			$sPic = $oEvents->GetGroupPicture($aBlog['ID']);

			$sLinkMore = '';
			if( strlen( $aBlog['Description']) > $iBlogLimitChars ) 
				//$sLinkMore = "... <a href=\"".$site['url']."blog.php?owner=".$ID."&show=blog&blogID=".$aBlog['PostID']."\">"._t('_Read more')."</a>";
				$sLinkMore = "... <a href=\"".$site['url']."group.php?ID=".$aBlog['ID']."\">"._t('_Read more')."</a>";

			$sBlogSnippet = mb_substr( strip_tags( $aBlog['Desc'] ), 0, $iBlogLimitChars ) . $sLinkMore;
			?>
				<div class="thumbnail_block" style="float:left;width:45px;height:45px;margin-right:10px;margin-top:10px;position:relative;">
					<?= $sPic ?>
				</div>
				<div class="blog_wrapper" style="width:290px;float:left;position:relative;">
					<div class="blogHead1">
						<? echo '<a href="' . $site['url'] . 'group.php?ID=' . $aBlog['ID'] . '" class="bottom_text">' ?>
							<?= $aBlog['Name'] ?>
						</a>
					</div>
					<div class="blogInfo">
						<span><img src="<?= getTemplateIcon( 'clock.gif' ) ?>" /><?= date( $php_date_format, $aBlog['DateTime_f'] ) . ' ' ?></span>
						<span><?= _t( '_in Category', getTemplateIcon( 'folder_small.png' ), 'groups_browse.php?categID='.$aBlog['CategID'], process_line_output($aBlog['CategName']) ) ?></span>
					</div>
					<div class="blogSnippet">
						<?= $sBlogSnippet ?>
					</div>
				</div>
				<div class="clear_both"></div>
			<?
		}		
		?>
		</div>
		<?
		$ret = ob_get_clean();
		
		$show_hide = $this -> genShowHideItem( 'container_groups' );

		echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
	}

	function showBlockComments( $sCaption )
	{
		ob_start();
		
		?>
		<div id="comments_wrapper">
			<?= $this -> getComments(); ?>
			<?= $this -> getCommentActions() ?>
			<?= $this -> getAddCommentForm() ?>
			<!--     navigation will be here soon    -->
		</div>
		<?
		
		$ret = ob_get_clean();
		$show_hide = $this -> genShowHideItem( 'comments_wrapper' );
		
		echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
	}
	
	function showBlockShareMusic( $sCaption )
	{
		echo '<div id="show_shareMusic">';
		echo PageCompShareMusicContent( $sCaption, $this -> _iProfileID );
		echo '</div>';
	}
	
	function showBlockSharePhotos( $sCaption )
	{
		echo '<div id="show_sharePhotos">';
		echo PageCompSharePhotosContent( $sCaption, $this -> _iProfileID );
		echo '</div>';
	}
	
	function showBlockShareVideos( $sCaption )
	{
		echo '<div id="show_shareVideos">';
		echo PageCompShareVideosContent( $sCaption, $this -> _iProfileID );
		echo '</div>';
	}

	function getComments( $iReplyTo = 0 )
	{
		global $site;
		
		$iPerPage = 5;
		
		//if( $iReplyTo )
			$sqlLimit = ''; // navigation will be here very very soon
		/*else
		{
			$this -> comm_page = (int)$_GET['comm_page'];
			
			if( $this -> comm_page < 1 )
				$this -> comm_page = 1;
			
			$sqlLimitFrom = ( $this -> comm_page - 1 ) * $iPerPage;
			
			$sqlLimit = "LIMIT $sqlLimitFrom, $iPerPage";
		}*/
		
		if( $iReplyTo == 0 )
			$sOrder = "ASC";
		else
			$sOrder = "DESC";
		
		$sQuery = "
			SELECT
				`ProfilesComments`.`ID`,
				`ProfilesComments`.`Date`,
				UNIX_TIMESTAMP(`Date`) AS `UnixDate`,
				`ProfilesComments`.`IP`,
				`ProfilesComments`.`Text`,
				`ProfilesComments`.`New`,
				`ProfilesComments`.`ReplyTO`,
				`Profiles`.`ID` AS `senderID`,
				`Profiles`.`NickName` AS `senderNick`
			FROM `ProfilesComments`
			LEFT JOIN `Profiles` ON
				`ProfilesComments`.`Sender` = `Profiles`.`ID`
			WHERE
				`ProfilesComments`.`Recipient` = {$this -> _iProfileID} AND
				`ReplyTO` = $iReplyTo
			ORDER BY `Date` $sOrder
			$sqlLimit
			";
		$rComments = db_res( $sQuery );
		ob_start();
		while( $aComment = mysql_fetch_assoc( $rComments ) )
		{
		?>
			<div class="comment_row">
				<div class="comment_author">
					<?php echo get_member_icon($aComment['senderID'])?>
				</div>
				<div class="comment_view">
					<a href="<?= getProfileLink($aComment['senderID']) ?>">
						<?= trim (process_line_output($aComment['senderNick']))?>
					</a>
				<?php echo '(' . $this -> defineTimeInterval($aComment['UnixDate']) . ')' ?>
			<?
			if( $this -> owner && $aComment['New'] )
			{
				?>
					<span class="commentNew"><?= _t("_new") ?></span>
				<?
				db_res( "UPDATE `ProfilesComments` SET `New` = '0' WHERE `ID` = {$aComment['ID']}" );
			}
			
			?>
				</div>
				
				<div class="comment_text">
					<?= process_smiles( $aComment['Text'])  ?>
				</div>
			
			<?= $this -> getCommentActions( $aComment['ID'] ) ?>
			
			</div>
			<?
			echo $this -> getAddCommentForm( $aComment['ID'] );
			
			if( $answers = $this -> getComments( $aComment['ID'] ) )
			{
				?>
			<div id="replies_to_<?= $aComment['ID'] ?>" class="comment_replies">
				<?= $answers ?>
			</div>
				<?
			}
		}
		
		return ob_get_clean();
	}

	function getCommentActions( $ID = 0 )
	{
		global $logged;
		
		ob_start();
		?>
				<div class="comment_actions" >
		<?
		if( $ID )
		{
			if( $logged['member'] )
			{
				?>
					<a href="javascript:void(0);"
					  onclick="document.getElementById('answer_form_to_<?= $ID ?>').style.display = 'block'; this.style.display = 'none';"
					  ><?= _t( '_answer' ) ?></a>
				<?
			}
			
			if( $logged['admin'] || $this -> owner )
			{
				?>
					<a href="<?= $_SERVER['PHP_SELF'] ?>?ID=<?= $this -> _iProfileID ?>&amp;action=commentdelete&amp;commentID=<?= $ID ?>&amp;comm_page=<?= $this -> comm_page ?>"
					  onclick="return confirm( '<?= _t( '_are you sure?' ) ?>' );"
					  ><?= _t( '_delete' ) ?></a>
				<?
			}
		}
		else
		{
			if( $logged['member'] )
			{
				?>
					</div>
					<div class="comment_add_comment" >
					<a href="javascript:void(0);"
					  onclick="document.getElementById('answer_form_to_0').style.display = 'block'; this.style.display = 'none';"
					  ><?= _t( '_Post Comment' ) ?></a>
				<?
			}
		}
		?>
				</div>
				<div class="clear_both"></div>
		<?
		
		return ob_get_clean();
	}

	function getAddCommentForm( $ID = 0 )
	{
		$ret = '';
		
		if( $this -> comm_page )
			$sFormAdd = '&amp;comm_page=' . $this -> comm_page;
		else
			$sFormAdd = '';
		
		ob_start();
		?>
				<div style="display:none;margin-left:8px; " class="addcomment_textarea" id="answer_form_to_<?= $ID ?>">
					<form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?ID=<?= $this -> _iProfileID . $sFormAdd ?>">
<!--					<form method="post" action="<?= 'profile.php' ?>?ID=<?= $this -> _iProfileID . $sFormAdd ?>"> -->
						<textarea name="commenttext" class="comment_textarea" id="commenttext_to_<?= $ID ?>"></textarea>
						<div class="addcomment_submit" style="text-align:center;">
							<input type="hidden" name="ID" value="<?= $this -> _iProfileID ?>" />
							<input type="hidden" name="replyTO" value="<?= $ID ?>" />
							<input type="submit" name="commentsubmit" value="add comment" />
						</div>
					</form>
				</div>
		<?
		
		return ob_get_clean();
	}

	function collectProfileFieldsByCateg( $categ )
	{
		$rFields = db_res( "SELECT * FROM `ProfilesDesc` WHERE `visible` AND ( FIND_IN_SET('0',show_on_page) OR FIND_IN_SET('7',show_on_page) ) ORDER BY `order`" );
		$aFields = array();
		$doCollect = false;
		
		while( $aField = mysql_fetch_assoc( $rFields ) )
		{
			if( is_numeric( $aField['name'] ) and (int)$aField['name'] == $categ )
			{
				$doCollect = true; //begin collect fields
				continue;
			}
			
			if( !$doCollect )
				continue; //do not collect
			
			if( is_numeric( $aField['name'] ) )
				break; //stop collect fields
			
			$aFields[] = $aField; //do collect
		}
		
		return $aFields;
	}
	
	function showProfileFields( $aFields )
	{
		global $p_arr;
		global $site;
		
		
		$rd = 1;
		$first_row = 1;
		
	    $aRedundantFields = array();
		
		$ret = '';
		
	    foreach( $aFields as $arrpd )
		{
	        $fname = get_input_name ( $arrpd );
			$sRealFName = get_field_name ( $arrpd );

			if ( !in_array( $sRealFName, $aRedundantFields ) )
			{
		        if ( $arrpd['get_value_db'] )
		        {
		            $funcbody = $arrpd['get_value_db'];
		            $func = create_function('$arg0',$funcbody);

		            $p_arr[$fname] = $func($p_arr);
		        }

				if( !strlen( $p_arr[$fname] ) )
					continue;
				
		        //if ( !strlen($p_arr[$fname]) ) $p_arr[$fname] = $p_arr[$fname];
				$not_first_row = 0;
				
		        switch ($arrpd['type'])
		        {
			        case 'set': // set of checkboxes
			            $ret .= print_row_set ( $first_row, $arrpd, $p_arr[$fname], "table", $rd, 2, "50%" );
			            break;
			        case 'rb': // radio buttons
			            $ret .= print_row_radio_button ( $first_row, $arrpd, $p_arr[$fname], "table", $rd, 2, "50%" );
			            break;
			        case 'r': // reference to array for combo box
						if ( $fname == 'Country' )
							$imagecode = '<img src="'. ($site['flags'].strtolower($p_arr[$fname])) .'.gif" alt="flag" />';
						else
							$imagecode = '';
			            $ret .= print_row_ref ( $first_row, $arrpd, $p_arr[$fname], "table", $rd, 2, "50%", 0, '', $imagecode );
			            break;
					case '0': // divider
					    $ret .= print_row_delim( $first_row, $arrpd, "panel", 2 );
				            $not_first_row = 1;
				            $first_row = 1;
					    break;
			        case 'e': // enum combo box
			            $ret .= print_row_enum( $first_row, $arrpd, $p_arr[$fname], "table", '', $rd, 2, "50%" );
			            break;
			        case 'en': // enum combo box with numbers
			            $ret .= print_row_enum_n( $first_row, $arrpd,$p_arr[$fname], "table", $rd, 2, "50%" );
			            break;
			        case 'eny': // enum combo box with years
			            $ret .= print_row_enum_years( $first_row, $arrpd, $p_arr[$fname], "table", $rd, 2, "50%", '', $sRealFName );
				    	$aRedundantFields[] = $sRealFName;
			            break;
					case 'date':
			            $ret .= print_row_date( $first_row, $arrpd, $p_arr[$fname], "table", $rd, 2, "50%", '', $sRealFName );
						break;
					case 'a': // memo
			            $p_arr[$fname] = process_line_output( $p_arr[$fname] );
						
			            if( strlen( $p_arr[$fname] ) )
							$ret .= print_row_area( $first_row, $arrpd, $p_arr[$fname], "table", $rd, 2, "50%" );
			            break;   
			        case 'c': // input box
			            $p_arr[$fname] = process_line_output( $p_arr[$fname] );
						
			            if( strlen( $p_arr[$fname] ) )
			            {
							if ( 'HomePage' == $fname )
								$p_arr[$fname] = '<a href="http://' . $p_arr[$fname] . '">' . $p_arr[$fname] . '</a>';
							$ret .= print_row_edit( $first_row, $arrpd, $p_arr[$fname], "table", $rd, 2, "50%" );
			            }
			            break;   
			        case 'p': // input box password
			            $p_arr[$fname] = process_line_output( $p_arr[$fname] );
			            $ret .= print_row_pwd( $first_row, $arrpd, $p_arr[$fname], "table", $rd, 2, "50%" );
			            break;
					default:
					    $not_first_row = 1;
				}
				if ( !$not_first_row && $first_row == 1 )
					$first_row = 0;
		    }
	    }
		return $ret;
	}
	
	function showBlockMp3( $sCaption )
	{
		global $logged;
		
		$iMemberId = (int)$_COOKIE['memberID'];
		$ret = getApplicationContent('mp3', 'player', array('id' => $this -> _iProfileID, 'password' => getPassword($iMemberId), 'vId' => $iMemberId), true);
		echo DesignBoxContent( _t( $sCaption ), '<div align="center">' . $ret . '</div>', 1, $show_hide );
	}

	function showBlockMutualFriends( $sCaption ) {
		global $site;
		$iFriendNums = getFriendNumber( $this -> _iProfileID );

		$ret = '';
		$iCounter = 0;
		$iTotalCounter = 0;
		foreach ($this -> aMutualFriends as $key => $value) {
			$iCounter ++;
			$sKey = '1';
			if( $iCounter == 3 ) $sKey = '2';

			$ret .= '<div class="friends_thumb_'.$sKey.'">' . get_member_thumbnail($key, 'left') . '<div class="browse_nick"><a href="' . getProfileLink($key) . '">' . $value . '</a></div><div class="clear_both"></div></div>';
			if( $iCounter == 3)  $iCounter = 0; 
			$iTotalCounter ++;
			if( $iTotalCounter >= 12 ) break;
		}
		if ($ret) {
			$ret .= '<div class="clear_both"></div>';
			$sFriendInfo = '<div class="caption_item"><a href="'.$site['url'].'viewFriends.php?iUser='.$this -> _iProfileID.'">'.$iFriendNums.' '._t("_Friends").'</a></div>';
			echo DesignBoxContent( _t( $sCaption ), $ret, 1, $sFriendInfo);
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
					IF( `FriendList`.`ID` = $memberID, `FriendList`.`Profile` , `FriendList`.`ID` ) AS `friendID`,
					`Profiles`.`NickName`
				FROM `FriendList`
				INNER JOIN `FriendList` AS `FriendListMy` ON
				(
				`FriendListMy`.`ID`      = IF( `FriendList`.`ID` = $memberID, `FriendList`.`Profile` , `FriendList`.`ID` ) OR
				`FriendListMy`.`Profile` = IF( `FriendList`.`ID` = $memberID, `FriendList`.`Profile` , `FriendList`.`ID` )
				) AND
				`FriendListMy`.`Check` = 1
				INNER JOIN `Profiles` ON
					`Profiles`.`ID` = IF( `FriendList`.`ID` = $memberID, `FriendList`.`Profile` , `FriendList`.`ID` )
				WHERE
				(
				(
				`FriendList`.`ID` = $memberID OR
				`FriendList`.`Profile` = $memberID
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
				IF( `FriendList`.`ID` = $memberID, `FriendList`.`Profile` , `FriendList`.`ID` ) != {$this -> _iProfileID}
				";
					
				  $rResult = db_res( $sQuery );
				  while( $aRow = mysql_fetch_assoc( $rResult ) )
					$this -> aMutualFriends[ $aRow['friendID'] ] = $aRow['NickName'];
				 
	        }

}
?>