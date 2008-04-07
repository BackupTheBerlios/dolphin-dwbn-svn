<?

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

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'members.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'sharing.inc.php' );

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolClassifieds.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolEvents.php' );

// --------------- page variables and login

$_page['name_index'] = 6;
$_page['css_name'] = 'member_panel.css';

$_page['extra_js'] = '<script type="text/javascript">urlIconLoading = "'.getTemplateIcon('loading.gif').'";</script>';

$_page['header'] = _t( "_My Account" );
//$_page['header_text'] = _t( "_MEMBER_PANEL_H1", $site['title'] );
//$_page['header_text'] = ('g4' != $tmpl) ? _t( "_MEMBER_PANEL_H1", $site['title'] ) : "<img src=\"{$site['images']}cp.gif\">";

// --------------- GET/POST actions

$member['ID']	    = $_POST['ID'];
$member['Password']   = md5( process_pass_data( $_POST['Password'] ) );

if ( !( $_POST['ID'] && $_POST['Password'] ) && ( $_COOKIE['memberID'] && $_COOKIE['memberPassword'] ) )
{
    if ( !( $logged['member'] = member_auth( 0, false ) ) )
	login_form( _t( "_LOGIN_OBSOLETE" ) );
}
else
{
    if ( !$_POST['ID'] && !$_POST['Password'] )
	{
		// this is dynamic page -  send headers to do not cache this page
		send_headers_page_changed();

		login_form();
	}
    else
    {
        $member['ID'] = getID( $member['ID'] );

        // Check if ID and Password are correct (addslashes already inside)
        if ( check_login( $member['ID'], $member['Password'] ) )
        {
			setcookie( "memberID", $_COOKIE['memberID'], time() - 3600, '/' );
			setcookie( "memberPassword", $_COOKIE['memberPassword'], time() - 3600, '/' );
			setcookie( "memberID", $member['ID'], 0, '/' );
			setcookie( "memberPassword", $member['Password'], 0, '/' );
			//setcookie( 'userArray', 'aUser' . $member['ID'] );
			$update_res = db_res( "UPDATE `Profiles` SET `LastLoggedIn` = NOW() WHERE `ID` = {$member['ID']}" );
			createUserDataFile( $member['ID'] );

			$p_arr = getProfileInfo( $member['ID'] ); //db_arr( "SELECT `NickName` From `Profiles` WHERE `ID` = {$member['ID']}" );
			
			if( !$sUrlRelocate = $_POST['relocate'] or basename( $_POST['relocate'] ) == 'index.php' or basename( $_POST['relocate'] ) == 'join_form.php' )
				 $sUrlRelocate = $_SERVER['PHP_SELF'];
			
			$_page['name_index'] = 150;
			$_page['css_name'] = '';
			
			$_ni = $_page['name_index'];
			$_page_cont[$_ni]['page_main_code'] = MsgBox( _t( '_Please Wait' ) );
			$_page_cont[$_ni]['url_relocate'] = $sUrlRelocate;
			PageCode();
		}
		exit;
    }
}

$member['ID'] = (int)$_COOKIE['memberID'];
$member['Password'] = $_COOKIE['memberPassword'];

$p_arr = getProfileInfo( $member['ID'] );

// --------------- [END] GET/POST actions


//Ajax loaders

if( $_GET['show_only'] )
{
	switch( $_GET['show_only'] )
	{
		case 'shareMusic':
			$sCaption = db_value( "SELECT `Caption` FROM `AccountCompose` WHERE `Func` = 'ShareMusic'" );
			echo PageCompShareMusicContent( $sCaption, $member['ID'] );
		break;
		case 'sharePhotos':
			$sCaption = db_value( "SELECT `Caption` FROM `AccountCompose` WHERE `Func` = 'SharePhotos'" );
			echo PageCompSharePhotosContent($sCaption, $member['ID']);
		break;
		case 'shareVideos':
			$sCaption = db_value( "SELECT `Caption` FROM `AccountCompose` WHERE `Func` = 'ShareVideos'" );
			echo PageCompShareVideosContent($sCaption, $member['ID']);
		break;
	}
	
	exit;
}

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['content_column_1'] = getPageBlocks( 1 );
$_page_cont[$_ni]['content_column_2'] = getPageBlocks( 2 );



// --------------- [END] page components

// this is dynamic page -  send headers to do not cache this page
send_headers_page_changed();

PageCode();

// --------------- page components functions


function getPageBlocks( $iCol )
{
	global $member;
	
	$ret = '';
	
	$rBlocks = db_res( "SELECT * FROM `AccountCompose` WHERE `Column` = $iCol ORDER BY `Order`" );
	
	while( $aBlock = mysql_fetch_assoc( $rBlocks ) )
	{
		$func = 'PageComp' . $aBlock['Func'];
		
		if( strpos( $aBlock['Func'], 'Share' ) === false )
		{
			$sSH = '';
			$cont = $func( $aBlock['Content'], $sSH );
			if( $cont ) {
				$block = DesignBoxContent ( _t( $aBlock['Caption'] ), $cont, 1, $sSH );}
			else
				$block = '';
		}
		else
		{
			$func = 'PageComp' . $aBlock['Func'];
			$block = $func( $aBlock['Caption'], $member['ID'] );
		}
		
		$ret .= $block;
	}
	
	return $ret;
}

function PageCompRSS( $sContent )
{
    global $p_arr, $site;

	list( $sUrl, $iNum ) = explode( '#', $sContent );
	$iNum = (int)$iNum;
	
    $sUrl = str_replace(array('{SiteUrl}', '{NickName}'),array($site['url'], $p_arr['NickName']), $sUrl);

	return genRSSHtmlOut( $sUrl, $iNum );
}

function PageCompEcho( $sContent )
{
	return $sContent;
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


	function PageCompClassifieds( $sCaption , &$sShowHide)
	{
		global $site;
		global $short_date_format;
		global $p_arr;
		$php_date_format = getParam( 'php_date_format' );
		
		//$iBlogLimitChars = (int)getParam("max_classified_preview");
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		$ID = $p_arr['ID'];
		if ($ID > 0) {
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
				`ClassifiedsAdvertisements`.`IDProfile`  = '{$ID}' AND
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

				$sBlogSnippet = substr( strip_tags( $aBlog['Message'] ), 0, $iBlogLimitChars ) . $sLinkMore;
				?>
					<div class="icon_block">
						<div  class="thumbnail_block" style="float:left;">
							<? echo '<a href="' . $site['url'] . 'classifieds.php?ShowAdvertisementID=' . $aBlog['ID'] . '" class="bottom_text">' ?>
								<?= $sPic ?>
							</a>
						</div>
					</div>
					<div class="blog_wrapper_n">
						<div class="blog_subject_n">
							<? echo '<a href="' . $site['url'] . 'classifieds.php?ShowAdvertisementID=' . $aBlog['ID'] . '" class="bottom_text">' ?>
								<?= $aBlog['Subject'] ?>
							</a>
						</div>
						<div class="blogInfo">
							<span><img src="<?= getTemplateIcon( 'clock.gif' ) ?>" /><?= date( $php_date_format, $aBlog['DateTime_f'] ) . ' ' ?></span>
							<span><?= _t( '_in Category', getTemplateIcon( 'ad_category.gif' ), 'classifieds.php?bClassifiedID='.$aBlog['CatID'], process_line_output($aBlog['Name']) ).' / '.
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

			$show_hide = genShowHideItem( 'container_classifieds' );
			$sShowHide = $show_hide;
			return $ret;
		} else {
			return MsgBox( _t('_im_textNoCurrUser') );
		}
		//return DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
	}

	function PageCompEvents( $sCaption, &$sShowHide )
	{
		global $site;
		global $short_date_format;
		$php_date_format = getParam( 'php_date_format' );
		//$iBlogLimitChars = (int)getParam("max_classified_preview");
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		global $p_arr;
		$ID = $p_arr['ID'];
		if ($ID > 0) {
			/*$sQuery = "
				SELECT DISTINCT `SDatingEvents`. * , `Profiles`.`NickName`,
				UNIX_TIMESTAMP( `SDatingEvents`.`EventStart` ) as `DateTime_f`
				FROM `SDatingEvents` 
				LEFT JOIN `Profiles` ON `Profiles`.`ID` = `SDatingEvents`.`ResponsibleID` 
				WHERE `SDatingEvents`.`ResponsibleID` = {$ID} AND
				`SDatingEvents`.`Status` = 'Active'
				ORDER BY `EventStart` DESC 
				LIMIT 5
			";*/

			$sQuery = "
				SELECT DISTINCT `SDatingEvents`. * , `Profiles`.`NickName` ,
				UNIX_TIMESTAMP( `SDatingEvents`.`EventStart` ) AS `DateTime_f` 
				FROM `SDatingEvents` 
				INNER JOIN `Profiles` ON `Profiles`.`ID` = `SDatingEvents`.`ResponsibleID` 
				INNER JOIN `SDatingParticipants` ON `SDatingParticipants`.`IDEvent` = `SDatingEvents`.`ID` 
				WHERE (
				`SDatingEvents`.`ResponsibleID` = '{$ID}'
				OR `SDatingParticipants`.`IDMember` = '{$ID}'
				)
				AND `SDatingEvents`.`Status` = 'Active'
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
					$sLinkMore = "... <a href=\"".$site['url']."events.php?action=show_info&event_id=".$aBlog['ID']."\">"._t('_Read more')."</a>";

				$sBlogSnippet = substr( strip_tags( $aBlog['Description'] ), 0, $iBlogLimitChars ) . $sLinkMore;
				?>
					<div class="icon_block">
						<?= $sPic ?>
					</div>
					<div class="blog_wrapper_n">
						<div class="blog_subject_n">
							<? echo '<a href="' . $site['url'] . 'events.php?action=show_info&event_id=' . $aBlog['ID'] . '" class="bottom_text">' ?>
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

			$show_hide = genShowHideItem( 'container_events' );

			$sShowHide = $show_hide;
			return $ret;
			//echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
		} else {
			return MsgBox( _t('_im_textNoCurrUser') );
		}
	}

	function PageCompGroups( $sCaption, &$sShowHide )
	{
		global $site;
		global $short_date_format;
		$php_date_format = getParam( 'php_date_format' );		
		//$iBlogLimitChars = (int)getParam("max_classified_preview");
		$iBlogLimitChars = (int)getParam("max_blog_preview");
		global $p_arr;
		$ID = $p_arr['ID'];
		if ($ID > 0) {
			$sQuery = "
				SELECT DISTINCT `Groups`.`ID`, `Groups`.`Name`, `Groups`.`Desc`,
				UNIX_TIMESTAMP( `Groups`.`created` ) as `DateTime_f`,
				`Profiles`.`NickName`,
				`GroupsCateg`.`Name` AS 'CategName', `GroupsCateg`.`ID` AS `CategID`
				FROM `GroupsMembers`, `Groups`
				LEFT JOIN `GroupsCateg` ON `GroupsCateg`.`ID` = `Groups`.`categID` 
				LEFT JOIN `Profiles` ON `Profiles`.`ID` = `Groups`.`creatorID` 
				WHERE
				`GroupsMembers`.`memberID` = '{$ID}' AND
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

				$sBlogSnippet = substr( strip_tags( $aBlog['Desc'] ), 0, $iBlogLimitChars ) . $sLinkMore;
				?>
					<div class="icon_block">
						<?= $sPic ?>
					</div>
					<div class="blog_wrapper_n">
						<div class="blog_subject_n">
							<? echo '<a href="' . $site['url'] . 'group.php?ID=' . $aBlog['ID'] . '" class="bottom_text">' ?>
								<?= $aBlog['Name'] ?>
							</a>
						</div>
						<div class="blogInfo">
							<span><img src="<?= getTemplateIcon( 'clock.gif' ) ?>" />
							<?= date( $php_date_format, $aBlog['DateTime_f'] ) . ' ' ?></span>
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

			$show_hide = genShowHideItem( 'container_groups' );

			$sShowHide = $show_hide;
			return $ret;
			//echo DesignBoxContent( _t( $sCaption ), $ret, 1, $show_hide );
		} else {
			return MsgBox( _t('_im_textNoCurrUser') );
		}
	}

function PageCompMyPhotos()
{
	global $p_arr;
	
	return get_member_primary_photo( $p_arr['ID'], 'none');
}

function get_member_primary_photo( $ID, $float )
{
	global $site;
	require_once( BX_DIRECTORY_PATH_ROOT . 'profilePhotos.php' );
	$oPhoto = new ProfilePhotos( $ID );
	$oPhoto -> getActiveMediaArray();
	$aFile = $oPhoto -> getPrimaryPhotoArray();

	if( extFileExists( $oPhoto -> sMediaDir . 'photo_' . $aFile['med_file'] ) )
		$sFileName = $oPhoto -> sMediaUrl . 'photo_' . $aFile['med_file'];
	else
		$sFileName = getTemplateIcon( $oPhoto -> sSexPic );

	$style = 
		'width:' . $oPhoto -> aMediaConfig['size']['photoWidth'] . 'px;' .
		'height:' . $oPhoto -> aMediaConfig['size']['photoHeight'] . 'px;' .
		'background-image:url(' . $sFileName . ');';
		
	$ret = '';
	$ret .= '<div class="thumbnail_block" style="float:' . $float . '; ">';
		$ret .= "<a href=\"{$site['url']}upload_media.php\">";
			$ret .= '<img src="' . getTemplateIcon( 'spacer.gif' ) . '" style="' . $style . '" alt="' . process_line_output( $aFileName['med_title'] ) . '" />';
		$ret .= '</a>';
	$ret .= '</div>';

	return $ret;
}


function PageCompMembersStats()
{
	return getSiteStat();
}

/**
 * contr panel "contacts"
 */
function PageCompContacts()
{
	global $site;
	global $p_arr;

	$free_mode = getParam("free_mode") == "on" ? 1 : 0;

	// new messages attention
	$new_mess_arr = db_arr( "SELECT ID FROM `Messages` WHERE Recipient = $p_arr[ID] AND New = '1' ORDER BY Date DESC LIMIT 1" );
	if ( $new_mess_arr )
		$mess = $new_mess_arr[ID];

	// new kisses attention
	$new_kiss_arr = db_arr( "SELECT ID FROM `VKisses` WHERE Member = $p_arr[ID] AND New = '1' LIMIT 1" );
	if ( $new_kiss_arr )
		$vkiss = 1;

	// new friends attention
	$new_friend_arr = db_arr( "SELECT `ID` FROM `FriendList` WHERE `Profile` = $p_arr[ID] AND  `Check` = '0' LIMIT 1" );
	if ( $new_friend_arr )
		$frd = 1;

	// request for private photo
	$new_ppr_arr = db_arr( "SELECT IDTo FROM `PrivPhotosRequests` WHERE `IDTo` = $p_arr[ID] AND `Grant` = '0' LIMIT 1" );
	if ( $new_ppr_arr )
		$ppr = 1;


	ob_start();

?>
	<table class=control_panel_table width="100%" cellspacing=0 cellpadding=1>

    <tr class=table>
    <td class=control_panel_td_1_first valign=top align="left"><? echo _t( "_Messages" ); ?>:</td>
    <td class=control_panel_td_2_first valign=top align="left"><?
        if ( $mess )
            echo _t( "_ATT_MESSAGE", $mess, $site['url'] );
        else
            echo _t( "_ATT_MESSAGE_NONE", $site['url'] );?>
    </td>
    </tr>


    <tr class=table>
    <td class=control_panel_td_1 valign=top align="left"><? echo _t( "_Kisses" ); ?>:</td>
    <td class=control_panel_td_2 valign=top align="left"><?
        if ( $vkiss )
            echo _t( "_ATT_VKISS", $site['url'] );
        else
            echo _t( "_ATT_VKISS_NONE", $site['url'] );?>
    </td>
    </tr>

    <tr class=table>
    <td class=control_panel_td_1 valign=top align="left"><? echo _t( "_Friends" ); ?>:</td>
    <td class=control_panel_td_2 valign=top align="left"><?
        if ( $frd )
            echo _t( "_ATT_FRIEND", $site['url'] );
        else
            echo _t( "_ATT_FRIEND_NONE", $site['url'] );?>
    </td>
    </tr>
	</table>

<?php

    $ret = ob_get_contents();
    ob_end_clean();

    return $ret;
}

/**
 * contr panel "member info"
 */
function PageCompMemberInfo()
{
	global $site;
	global $p_arr;
	global $oTemplConfig;
	global $en_sdating;

	$free_mode = getParam("free_mode") == "on" ? 1 : 0;

	ob_start();

?>
    <table class="control_panel_table" width="100%" cellspacing="0" cellpadding="1">

	<tr class=table>

<!-- Profile Status -->

    <td valign="top" align="left" class="control_panel_td_1_first"><? echo _t( "_Profile status" ); ?>: </td>
    <td valign="top" align="left" class="control_panel_td_2_first">
		<b><font class="prof_stat_<? echo $p_arr['Status']; ?>">&nbsp;<? echo _t( "__{$p_arr['Status']}" ); ?>&nbsp;</font></b>
<?

	switch ( $p_arr['Status'] )
	{
		case 'Unconfirmed':	echo _t( "_ATT_UNCONFIRMED", $oTemplConfig -> popUpWindowWidth, $oTemplConfig -> popUpWindowHeight ); break;
		case 'Approval': echo _t( "_ATT_APPROVAL", $oTemplConfig -> popUpWindowWidth, $oTemplConfig -> popUpWindowHeight ); break;
		case 'Active': echo _t( "_ATT_ACTIVE", $oTemplConfig -> popUpWindowWidth, $oTemplConfig -> popUpWindowHeight ); break;
		case 'Rejected': echo _t( "_ATT_REJECTED", $oTemplConfig -> popUpWindowWidth, $oTemplConfig -> popUpWindowHeight ); break;
		case 'Suspended': echo _t( "_ATT_SUSPENDED", $oTemplConfig -> popUpWindowWidth, $oTemplConfig -> popUpWindowHeight ); break;
	}

?>
	</td>
	</tr>

<!-- Membership -->

<?php if ( !$free_mode ) { ?>

<tr class=table>
	<td valign=top align="left" class=control_panel_td_1><? echo _t( "_Membership2" ); ?>:</td>
	<td valign=top align="left" class=control_panel_td_2>
<?
	echo GetMembershipStatus($p_arr['ID']);
?>
	</td>
</tr>

<?php } ?>

<!-- Last login -->

	<tr class=table>
		<td valign=top align="left" class=control_panel_td_1><? echo _t( "_Last login" ); ?>: </td>
		<td valign=top align="left" class=control_panel_td_2>
<?
	if ( !$p_arr['LastLoggedIn'] || $p_arr['LastLoggedIn'] == "0000-00-00 00:00:00" )
		$p_arr['LastLoggedIn'] = 'never';
	echo $p_arr['LastLoggedIn'];
?>
		</td>
	</tr>

<!-- SpeedDating info -->

<?
if ( $en_sdating )
{
?>
	<tr class="table">
		<td valign="top" align="left" class="control_panel_td_1"><?= _t('_SpeedDating tickets') ?>: </td>
		<td valign="top" align="left" class="control_panel_td_2">
<?
	$membership_arr = getMemberMembershipInfo( $p_arr['ID'] );
	$events_query = "SELECT `SDatingEvents`.`ID`, `Title`, (NOW() > `EventEnd` AND NOW() < DATE_ADD(`EventEnd`, INTERVAL `ChoosePeriod` DAY)) AS `ChooseActive`, (`SDatingParticipants`.`ID` IS NOT NULL) AS `IsParticipant`
					FROM `SDatingEvents`
					LEFT JOIN `SDatingParticipants` ON `SDatingParticipants`.`IDEvent` = `SDatingEvents`.`ID` AND `SDatingParticipants`.`IDMember` = {$p_arr['ID']}
					WHERE `SDatingEvents`.`Status` = 'Active'
					AND NOW() < DATE_ADD(`SDatingEvents`.`EventEnd`, INTERVAL `SDatingEvents`.`ChoosePeriod` DAY)
					AND FIND_IN_SET('{$p_arr['Sex']}', `SDatingEvents`.`EventSexFilter`)
					AND ( TO_DAYS('{$p_arr['DateOfBirth']}')
						BETWEEN TO_DAYS(DATE_SUB(NOW(), INTERVAL `SDatingEvents`.`EventAgeUpperFilter` YEAR))
						AND TO_DAYS(DATE_SUB(NOW(), INTERVAL `SDatingEvents`.`EventAgeLowerFilter` YEAR)) )
					AND ( INSTR(`SDatingEvents`.`EventMembershipFilter`, '\'all\'') OR INSTR(`SDatingEvents`.`EventMembershipFilter`, '\'{$membership_arr['ID']}\'') )
					ORDER BY `SDatingEvents`.`EventStart` DESC";
	$events_res = db_res( $events_query );
	if ( mysql_num_rows($events_res) == 0 )
	{
		echo _t('_none');
	}
	else
	{
		$events_links = '';
		while ( $event_arr = mysql_fetch_assoc($events_res) )
		{
			if ( $event_arr['IsParticipant'] )
			{
				$events_links .= strlen($events_links) ? ', ' : '';
				$events_links .= "<a href=\"{$site['url']}events.php?action=show_info&amp;event_id={$event_arr['ID']}\">". process_line_output($event_arr['Title']) ."</a>";
			}
		}
		echo strlen($events_links) ? $events_links : _t('_none');
	}
?>
		</td>
	</tr>
<?
}
?>

<!--  Affiliate Program starts here -->
<?

	if ( 0 == $free_mode)
	{
		echo "<tr class=table>
			<td valign=top align=\"left\" class=control_panel_td_1>" . _t( "_Affiliate Program" ) . ": </td>
			<td valign=top align=\"left\" class=control_panel_td_2>";

    $ar = db_arr ( "SELECT aff_num FROM Profiles WHERE ID='{$p_arr['ID']}' LIMIT 1" );

    $res = db_res ( "SELECT * FROM members_as_aff WHERE num_of_mem <= '$ar[0]'" );

    $txt = _t( "_Got_members_part_1" ).$ar[0]._t ( "_Got_members_part_2" );

    if ( mysql_num_rows( $res ) > 0 )
    {
            $txt  = _t ( "_Congratulation" ).$txt;
            $txt .= _t('_Click here to change your membership status');
    }
    else
    {
            $txt .= _t ( "_Need_more_members" );
    }

    echo $txt;

		echo "</td>
			</tr>";
	}

//<!--  Affiliate Program ends here -->

echo "</table>";

    $ret = ob_get_contents();
    ob_end_clean();

    return $ret;
}

/**
 * contr panel "member info"
 */
function contr_panel_member_info_g4()
{
	global $site;
	global $p_arr;
	global $en_sdating;

	$free_mode = getParam("free_mode") == "on" ? 1 : 0;

	// new messages attention
	$new_mess_arr = db_arr( "SELECT ID FROM `Messages` WHERE Recipient = $p_arr[ID] AND New = '1' ORDER BY Date DESC LIMIT 1" );
	if ( $new_mess_arr )
		$mess = $new_mess_arr[ID];

	// new kisses attention
	$new_kiss_arr = db_arr( "SELECT ID FROM `VKisses` WHERE Member = $p_arr[ID] AND New = '1' LIMIT 1" );
	if ( $new_kiss_arr )
		$vkiss = 1;

	// new friends attention
	$new_friend_arr = db_arr( "SELECT `ID` FROM `FriendList` WHERE `Profile` = $p_arr[ID] AND  `Check` = '0' LIMIT 1" );
	if ( $new_friend_arr )
		$frd = 1;

	ob_start();

?>
    <table width="100%" cellspacing="0" cellpadding="3" border="0" class="cp_table">

<!-- Profile status -->
    <tr>
	<td valign="middle" class="cp_td" align="left"><img src="<? echo $site['images']; ?>cp_status.gif"></td>
    <td valign="middle" class="cp_td" align="left"><? echo _t( "_Profile status" ); ?>: </td>
    <td valign="middle" class="cp_td_r" align="left">
    	<b><font class=prof_stat_<? echo $p_arr['Status']; ?>>&nbsp;<? echo _t( "__$p_arr[Status]" ); ?>&nbsp;</font></b>
<?
		switch ( $p_arr['Status'] )
		{
			case 'Unconfirmed':	echo _t( "_ATT_UNCONFIRMED"); break;
			case 'Approval': echo _t( "_ATT_APPROVAL"); break;
		    case 'Active': echo _t( "_ATT_ACTIVE"); break;
			case 'Rejected': echo _t( "_ATT_REJECTED"); break;
		    case 'Suspended': echo _t( "_ATT_SUSPENDED"); break;
		}
?>
    </td>
    </tr>
<!-- Membership -->

<?php
	if ( !$free_mode )
	{
?>
<tr>
	<td valign="top" class="cp_td" width="22" align="left"><img src="<? echo $site['images']; ?>cp_membership.gif"></td>
	<td valign="top" class="cp_td" align="left" style="padding-top:7px;"><? echo _t( "_Membership2" ); ?>:</td>
    <td valign="top" class="cp_td_r" align="left">
<?
		echo GetMembershipStatus($p_arr['ID']);
?>
	</td>
</tr>
<?php
	}
?>
<!-- Last login -->
    <tr>
	<td valign="middle" class="cp_td" align="left"><img src="<? echo $site['images']; ?>cp_llogin.gif"></td>
    <td valign="middle" class="cp_td" align="left"><? echo _t( "_Last login" ); ?>: </td>
    <td valign="middle" class="cp_td_r" align="left">
<?
		if ( !$p_arr['LastLoggedIn'] || $p_arr['LastLoggedIn'] == "0000-00-00 00:00:00" )
			$p_arr['LastLoggedIn'] = _t( "_never" );
		echo $p_arr['LastLoggedIn'];
?>
    </td>
    </tr>

<!-- SpeedDating info -->

<?
if ( $en_sdating )
{
?>
	<tr class="table">
		<td valign="middle" class="cp_td" align="left"><img src="<? echo $site['images']; ?>cp_lchanges.gif"></td>
		<td valign="middle" class="cp_td" align="left"><?= _t('_SpeedDating tickets') ?>: </td>
		<td valign="middle" class="cp_td_r" align="left">
<?
	$membership_arr = getMemberMembershipInfo( $p_arr['ID'] );
	$events_query = "SELECT `SDatingEvents`.`ID`, `Title`, (NOW() > `EventEnd` AND NOW() < DATE_ADD(`EventEnd`, INTERVAL `ChoosePeriod` DAY)) AS `ChooseActive`
					FROM `SDatingEvents`
					LEFT JOIN `SDatingParticipants` ON `SDatingParticipants`.`IDEvent` = `SDatingEvents`.`ID` AND `SDatingParticipants`.`IDMember` = {$p_arr['ID']}
					WHERE `SDatingEvents`.`Status` = 'Active'
					AND `SDatingParticipants`.`ID` IS NOT NULL
					AND NOW() < DATE_ADD(`SDatingEvents`.`EventEnd`, INTERVAL `SDatingEvents`.`ChoosePeriod` DAY)
					AND FIND_IN_SET('{$p_arr['Sex']}', `SDatingEvents`.`EventSexFilter`)
					AND ( TO_DAYS('{$p_arr['DateOfBirth']}')
						BETWEEN TO_DAYS(DATE_SUB(NOW(), INTERVAL `SDatingEvents`.`EventAgeUpperFilter` YEAR))
						AND TO_DAYS(DATE_SUB(NOW(), INTERVAL `SDatingEvents`.`EventAgeLowerFilter` YEAR)) )
					AND ( INSTR(`SDatingEvents`.`EventMembershipFilter`, '\'all\'') OR INSTR(`SDatingEvents`.`EventMembershipFilter`, '\'{$membership_arr['ID']}\'') )
					ORDER BY `SDatingEvents`.`EventStart` DESC";
	$events_res = db_res( $events_query );
	if ( mysql_num_rows($events_res) == 0 )
	{
		echo _t('_none');
	}
	else
	{
		$events_links = '';
		while ( $event_arr = mysql_fetch_assoc($events_res) )
		{
			$events_links .= strlen($events_links) ? ', ' : '';
			$events_links .= "<a href=\"{$site['url']}events.php?action=show_info&amp;event_id={$event_arr['ID']}\">". process_line_output($event_arr['Title']) ."</a>";
		}
		echo $events_links;
	}
?>
		</td>
	</tr>
<?
}
?>
    <tr>
	<td valign="middle" class="cp_td" align="left"><img src="<? echo $site['images']; ?>cp_messages.gif"></td>
    <td valign="middle" class="cp_td" align="left"><? echo _t( "_Messages" ); ?>:</td>
    <td valign="middle" class="cp_td_r" align="left"><?
        if ( $mess )
            echo _t( "_ATT_MESSAGE", $mess, $site['url'] );
        else
            echo _t( "_ATT_MESSAGE_NONE", $site['url'] );?>
    </td>
    </tr>
    <tr>
	<td valign="middle" class="cp_td" align="left"><img src="<? echo $site['images']; ?>cp_kisses.gif"></td>
    <td valign="middle" class="cp_td" align="left"><? echo _t( "_Kisses" ); ?>:</td>
    <td valign="middle" class="cp_td_r" align="left"><?
        if ( $vkiss )
            echo _t( "_ATT_VKISS", $site['url'] );
        else
            echo _t( "_ATT_VKISS_NONE", $site['url'] );?>
    </td>
    </tr>
    <tr>
	<td valign="middle" class="cp_td" align="left"><img src="<? echo $site['images']; ?>cp_friends.gif"></td>
    <td valign="middle" class="cp_td" align="left"><? echo _t( "_Friends" ); ?>:</td>
    <td valign="middle" class="cp_td_r" align="left"><?
        if ( $frd )
            echo _t( "_ATT_FRIEND", $site['url'] );
        else
            echo _t( "_ATT_FRIEND_NONE", $site['url'] );?>
    </td>
    </tr>
	</table>

<?php

    $ret = ob_get_contents();
    ob_end_clean();

    return $ret;
}

/**
 * contr panel "search_profiles"
 */
function contr_panel_search_profiles()
{
    global $site;

    $ret = DesignQuickSearch();

	return $ret;
}

/**
 * contr panel "latest news"
 */
function PageCompNews()
{
	global $site;
	global $news_resl;
	global $oTemplConfig;


	// news
	//$news_limit_chars = getParam("max_news_preview");
	$news_res = db_res("SELECT `ID` AS `newsID`, `Header`, `Snippet` FROM `News` ORDER BY `Date` DESC LIMIT " . $oTemplConfig -> iMaxNewsOnMemberPanel . "");
	$news_count = db_arr("SELECT COUNT(`ID`) FROM `News`");


	$ret = '';
//	$i = 1;
	while( $news_arr = mysql_fetch_assoc($news_res) )
	{
	/* News snippets delimiter
		if( ($i%2) == 0 )
		{
			$style_add = '';
		}
		else
		{
			$style_add = 'style="border-bottom:2px solid #e4e4e4; margin-bottom:5px;"';
		}
	News snippets delimiter */
		//if ( strlen($news_arr['Text']) == $news_limit_chars ) $news_arr['Text'] .= "...";
		$ret .= '<div class="news_head">';
			//$ret .= '<img src="' . $site['icons'] . 'news.gif" alt="" />';
			$ret .= '<a href="' . $site['url'] . 'news_view.php?ID=' . $news_arr['newsID'] . '">';
				$ret .= process_line_output($news_arr['Header']);
			$ret .= '</a>';
		$ret .= '</div>';
		$ret .= '<div class="news_text" ' . $style_add . '>';
			$ret .= process_text_output($news_arr['Snippet']);
		$ret .= '</div>';

//		$i ++;
	}

	if( $news_count['0'] > $max_news_on_cp )
	{
		$ret .= '<div style="position:relative; text-align:center; line-height:20px;"><a href="' . $site['url'] . 'news.php">' . _t("_Read news in archive") . '</a></div>';
	}

	return $ret;

}

function displayRayMP3Player()
{
    global $member;

    $chechActionRes = checkAction($member['ID'], ACTION_ID_USE_RAY_MP3);

    if ($chechActionRes[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)
    {

	    $ret = '';

	    $ret .= '<div id="playerBlock">' . "\n";
	    	$ret .= getRayMP3Player( $member['ID'], getPassword($member['ID']), $member['ID']) . "\n";
	    $ret .= '</div>' . "\n";

	    $langShow = _t('_Show');
	    $langHide = _t('_Hide');

	    $menu = '<div class="block_menu" onclick="javascript: if ( \'none\' == document.getElementById(\'playerBlock\').style.display ) { document.getElementById(\'playerBlock\').style.display=\'block\'; this.innerHTML=\''. $langHide .'\'; } else { document.getElementById(\'playerBlock\').style.display=\'none\'; this.innerHTML=\''. $langShow .'\'; }" >'. $langHide .'</div>';

	    return DesignBoxContentBorder( _t('_mp3_player'), $ret, $menu );

    }

    return '';

}

function PageCompShareMusic( $sCaption )
{
	global $member;
	return '<div id="show_shareMusic">'. PageCompShareMusicContent( $sCaption, $member['ID'] ).'</div>';
}
	
function PageCompSharePhotos( $sCaption )
{
	global $member;
	return '<div id="show_sharePhotos">'.PageCompSharePhotosContent( $sCaption, $member['ID'] ).'</div>';
}
	
function PageCompShareVideos( $sCaption )
{
	global $member;
	return '<div id="show_shareVideos">'.PageCompShareVideosContent( $sCaption, $member['ID'] ).'</div>';
}

?>