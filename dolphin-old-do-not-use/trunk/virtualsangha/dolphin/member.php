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
require_once( BX_DIRECTORY_PATH_INC . 'news.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'sharing.inc.php' );

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolClassifieds.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolEvents.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolGroups.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolPageView.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolSharedMedia.php' );

//--------------------------------------- member account class ------------------------------------------//

class BxDolMember extends BxDolPageView {

	// member ID
	var $iMember;

	// member info
	var $aMemberInfo;

	// config site array
	var $aConfSite;

	// config dir array
	var $aConfDir;

	var $bAjaxMode;

	/*
		constructor
		* @param int $iMember - member ID		
	*/
	
	function BxDolMember($iMember, &$aSite, &$aDir) {
		$this->iMember     = (int)$iMember;
		$this->aMemberInfo = getProfileInfo($this->iMember);

		$this->aConfSite = $aSite;
		$this->aConfDir  = $aDir;

		parent::BxDolPageView('member');
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

	function getBlockCode_Classifieds() {
		if ($this->iMember > 0) {
			$sqlQuery = "
				SELECT DISTINCT
				COUNT(`ClassifiedsAdvertisements`.`ID`)
				FROM `ClassifiedsAdvertisements`
				WHERE
				`ClassifiedsAdvertisements`.`IDProfile`  = '{$this->iMember}' AND `ClassifiedsAdvertisements`.`Status` = 'active'
				GROUP BY `ClassifiedsAdvertisements`.`ID`
			";
			$iBlogs = db_value( $sqlQuery );

			if( $iBlogs > 0 ) {
				$oClassifieds = new BxDolClassifieds();
				$sBlocks = $oClassifieds->GenAnyBlockContent('last', $this->iMember);
				$ret = <<<EOF
<div id="container_classifieds">
	{$sBlocks}
</div>
EOF;

				$show_hide = $this->genShowHideItem( 'container_classifieds' );
				$sShowHide = $show_hide;
				return $ret;
			} else
				return '';
		} else {
			return MsgBox( _t('_im_textNoCurrUser') );
		}
	}
	
	function getBlockCode_Events() {
		if ($this->iMember > 0) {
			$sqlQuery = "
				SELECT COUNT(`SDatingEvents`.`ID`) AS 'Cnt'
				FROM `SDatingEvents` 
				LEFT JOIN `SDatingParticipants` ON `SDatingParticipants`.`IDEvent` = `SDatingEvents`.`ID` 
				WHERE (`SDatingEvents`.`ResponsibleID` = '{$this->iMember}' OR `SDatingParticipants`.`IDMember` = '{$this->iMember}')
				AND `SDatingEvents`.`Status` = 'Active'
			";
			$iBlogs = db_value( $sqlQuery );

			if( $iBlogs > 0 ) {
				$oEvents = new BxDolEvents();
				$sBlocks = $oEvents->GenAnyBlockContent('last', $this->iMember);
				$ret = <<<EOF
<div id="container_events">
	{$sBlocks}
</div>
EOF;

				$show_hide = $this->genShowHideItem( 'container_events' );
				$sShowHide = $show_hide;
				return $ret;
			} else
				return '';
		} else {
			return MsgBox( _t('_im_textNoCurrUser') );
		}
	}
	
	function getBlockCode_Groups() {
		if ($this->iMember > 0) {
			$sqlQuery = "
				SELECT COUNT(`Groups`.`ID`) AS 'Cnt'
				FROM `GroupsMembers`, `Groups`
				INNER JOIN `GroupsCateg` ON `GroupsCateg`.`ID` = `Groups`.`categID` 
				WHERE
				`GroupsMembers`.`memberID` = '{$this->iMember}' AND `GroupsMembers`.`groupID`  = `Groups`.`ID` AND `GroupsMembers`.`status`   = 'Active'
			";
			$iBlogs = db_value( $sqlQuery );

			if( $iBlogs > 0 ) {
				$oGroups = new BxDolGroups();
				$sBlocks = $oGroups->GenAnyBlockContent('latest', $this->iMember);
				$ret = <<<EOF
<div id="container_groups">
	{$sBlocks}
</div>
EOF;

				$show_hide = $this->genShowHideItem( 'container_groups' );
				$sShowHide = $show_hide;
				return $ret;
			} else
				return '';
		} else {
			return MsgBox( _t('_im_textNoCurrUser') );
		}
	}
	
	/*function getBlockCode_MyPhoto($iCol, $bNoDB = false) {*/
	function getBlockCode_Topest($iCol) {
		if(  $iCol == 1 )
			$iPID = $this->iMember;
		else {
			if( !$this->aMemberInfo['Couple'] )
				return '';
			
			$iPID = $this->aMemberInfo['ID'];
		}
		$sRet = $this->get_member_primary_photo( $iPID, 'none', $iCol);
		return '<div class="page_block_container">' . DesignBoxContent ( _t( '_My Photos' ), $sRet, 1) . '</div>';
		//return $sRet;
	}
	
	function getBlockCode_Contacts () {
		$sSiteUrl = $GLOBALS['site']['url'];
	
		$free_mode = getParam("free_mode") == "on" ? 1 : 0;

		$iChMemberID = 0;
		$iChMemberID = ($this->aMemberInfo['ID'] > 0) ? $this->aMemberInfo['ID'] : $this->iMember;
		if ($iChMemberID == 0) {
			return MsgBox(_t('_Sorry, nothing found'));
		}

		/*if ($_REQUEST['debug']=='1') {
		}*/

		// new messages attention
		$new_mess_arr = db_arr( "SELECT ID FROM `Messages` WHERE Recipient = {$iChMemberID} AND New = '1' ORDER BY Date DESC LIMIT 1" );
		if ( $new_mess_arr )
			$mess = $new_mess_arr[ID];
	
		// new kisses attention
		$new_kiss_arr = db_arr( "SELECT ID FROM `VKisses` WHERE Member = {$iChMemberID} AND New = '1' LIMIT 1" );
		if ( $new_kiss_arr )
			$vkiss = 1;
	
		// new friends attention
		$new_friend_arr = db_arr( "SELECT `ID` FROM `FriendList` WHERE `Profile` = {$iChMemberID} AND  `Check` = '0' LIMIT 1" );
		if ( $new_friend_arr )
			$frd = 1;
	
		// request for private photo
		$new_ppr_arr = db_arr( "SELECT IDTo FROM `PrivPhotosRequests` WHERE `IDTo` = {$iChMemberID} AND `Grant` = '0' LIMIT 1" );
		if ( $new_ppr_arr )
			$ppr = 1;
	
	
		ob_start();
	
	?>
		<table class=control_panel_table width="100%" cellspacing=0 cellpadding=1>
	
	    <tr class=table>
	    <td class=control_panel_td_1_first valign=top align="left"><? echo _t( "_Messages" ); ?>:</td>
	    <td class=control_panel_td_2_first valign=top align="left"><?
	        if ( $mess )
	            echo _t( "_ATT_MESSAGE", $mess, $sSiteUrl );
	        else
	            echo _t( "_ATT_MESSAGE_NONE", $sSiteUrl );?>
	    </td>
	    </tr>
	
	
	    <tr class=table>
	    <td class=control_panel_td_1 valign=top align="left"><? echo _t( "_Kisses" ); ?>:</td>
	    <td class=control_panel_td_2 valign=top align="left"><?
	        if ( $vkiss )
	            echo _t( "_ATT_VKISS", $sSiteUrl );
	        else
	            echo _t( "_ATT_VKISS_NONE", $sSiteUrl );?>
	    </td>
	    </tr>
	
	    <tr class=table>
	    <td class=control_panel_td_1 valign=top align="left"><? echo _t( "_Friends" ); ?>:</td>
	    <td class=control_panel_td_2 valign=top align="left"><?
	        if ( $frd )
	            echo _t( "_ATT_FRIEND", $sSiteUrl );
	        else
	            echo _t( "_ATT_FRIEND_NONE", $sSiteUrl );?>
	    </td>
	    </tr>
		</table>
	
	<?php
	
	    $ret = ob_get_contents();
	    ob_end_clean();
	
	    return $ret;
	}
	
	function getBlockCode_MemberInfo () {
		global $oTemplConfig;
	
		$free_mode = getParam("free_mode")  == "on" ? 1 : 0;
		$en_aff    = getParam("enable_aff") == 'on' ? 1 : 0;
	
		ob_start();
	
	?>
	    <table class="control_panel_table" width="100%" cellspacing="0" cellpadding="1">
	
		<tr class=table>
	
	<!-- Profile Status -->
	
	    <td valign="top" align="left" class="control_panel_td_1_first"><? echo _t( "_Profile status" ); ?>: </td>
	    <td valign="top" align="left" class="control_panel_td_2_first">
			<b><font class="prof_stat_<? echo $this->aMemberInfo['Status']; ?>">&nbsp;<? echo _t( "__{$this->aMemberInfo['Status']}" ); ?>&nbsp;</font></b>
	<?
	
		switch ( $this->aMemberInfo['Status'] )
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
		echo GetMembershipStatus($this->aMemberInfo['ID']);
	?>
		</td>
	</tr>
	
	<?php } ?>
	
	<!-- Last login -->
	
		<tr class=table>
			<td valign=top align="left" class=control_panel_td_1><? echo _t( "_Last login" ); ?>: </td>
			<td valign=top align="left" class=control_panel_td_2>
	<?
		if ( !$this->aMemberInfo['DateLastLogin'] || $this->aMemberInfo['DateLastLogin'] == "0000-00-00 00:00:00" )
			$this->aMemberInfo['DateLastLogin'] = 'never';
		echo $this->aMemberInfo['DateLastLogin'];
	?>
			</td>
		</tr>
	
	<!--  Affiliate Program starts here -->
	<?
	
		if ( 0 == $free_mode && 1 == $en_aff )
		{
			echo "<tr class=table>
				<td valign=top align=\"left\" class=control_panel_td_1>" . _t( "_Affiliate Program" ) . ": </td>
				<td valign=top align=\"left\" class=control_panel_td_2>";
	
	    $ar = db_arr ( "SELECT aff_num FROM Profiles WHERE ID='{$this->aMemberInfo['ID']}' LIMIT 1" );
	
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
	
	function getBlockCode_News () {
		global $oTemplConfig;
		
		return printNewsPanel($oTemplConfig->iMaxNewsOnMemberPanel);
	}
	
	function getBlockCode_SharePhotos () {
		$aMem = array('ID'=>$this->iMember);
		$oNew = new BxDolSharedMedia('photo', $this->aConfSite, $this->aConfDir, $aMem);
		$aRes = $oNew->getBlockCode_SharedMedia($oNew->iViewer);
		
		return $aRes;
	}

	function getBlockCode_ShareVideos () {
		$aMem = array('ID'=>$this->iMember);
		$oNew = new BxDolSharedMedia('video', $this->aConfSite, $this->aConfDir, $aMem);
		$aRes = $oNew->getBlockCode_SharedMedia($oNew->iViewer);
		
		return $aRes;
	}
	
	function getBlockCode_ShareMusic () {
		$aMem = array('ID'=>$this->iMember, 'Password'=>$this->aMemberInfo['Password']);
		$oNew = new BxDolSharedMedia('music', $this->aConfSite, $this->aConfDir, $aMem);
		$aRes = $oNew->getBlockCode_SharedMedia($oNew->iViewer);

		return $aRes;
	}
	
	function getBlockCode_Friends () {
		global $site;
		
		$sFriendList = ShowFriendList( $this -> iMember);
		$iFriendNums = getFriendNumber( $this -> iMember );
		
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
			
			$aDbTopMenu = array(
				_t("_Friends") => array( 
					'href' => "{$site['url']}viewFriends.php?iUser={$this -> iMember}"
				)
			);
				
			return array( $ret, $aDbTopMenu );
		}
	}
	
	function showMyPhotos($iCol) {
		if(  $iCol == 1 ){
			$iPID = $this->aMemberInfo['ID'];
		} else {
			if( !$this->aMemberInfo['Couple'] )
				return '';
		
			$iPID = $this->aMemberInfo['ID'];
		}
	
		return $this->get_member_primary_photo( $iPID, 'none', $iCol);
	}
	
	function get_member_primary_photo ($iPID, $float, $iCol = 0) {
		require_once( BX_DIRECTORY_PATH_ROOT . 'profilePhotos.php' );

		$oPhoto = new ProfilePhotos( $iPID );
		$oPhoto -> getActiveMediaArray();
		$iDesc = 0;
		//echoDbg($this->aMemberInfo);
		if ($this->aMemberInfo['Couple']>0 && $iCol == 2) {
			$aCoupleInfo = getProfileInfo($this->aMemberInfo['Couple']);
			$iDesc = $aCoupleInfo['PrimPhoto'];
		}
		$aFile = $oPhoto -> getPrimaryPhotoArray($iDesc);

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
			$ret .= "<a href=\"{$this->aConfSite['url']}upload_media.php\">";
				$ret .= '<img src="' . getTemplateIcon( 'spacer.gif' ) . '" style="' . $style . '" alt="' . process_line_output( $aFileName['med_title'] ) . '" />';
			$ret .= '</a>';
		$ret .= '</div>';
	
		return $ret;
	}
}

//-------------------------------------------------------------------------------------------------------//

// --------------- page variables and login

$_page['name_index'] = 81;
$_page['css_name'] = 'member_panel.css';

$_page['extra_js'] = '<script type="text/javascript">urlIconLoading = "'.getTemplateIcon('loading.gif').'";</script>';

$_page['header'] = _t( "_My Account" );

// --------------- GET/POST actions

$member['ID']	    = $_POST['ID'];
$member['Password']   = md5( process_pass_data( $_POST['Password'] ) );

$bAjxMode = ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ? true : false;

if ( !( $_POST['ID'] && $_POST['Password'] ) && ( $_COOKIE['memberID'] && $_COOKIE['memberPassword'] ) )
{
    if ( !( $logged['member'] = member_auth( 0, false ) ) )
	login_form( _t( "_LOGIN_OBSOLETE" ), 0, $bAjxMode );
}
else
{
    if ( !$_POST['ID'] && !$_POST['Password'] )
	{
		// this is dynamic page -  send headers to do not cache this page
		send_headers_page_changed();

		$bAjxMode = ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ? true : false;
		login_form('', 0, $bAjxMode);

	} else {
        $member['ID'] = getID( $member['ID'] );
		if (isLoggedBanned($member['ID'])) {
			$_page['name_index'] = 55;
			$_page['css_name'] = '';
			$_ni = $_page['name_index'];
			$_page_cont[$_ni]['page_main_code'] = MsgBox(_t('_member_banned'));
			PageCode();
			exit;
		}

        // Check if ID and Password are correct (addslashes already inside)
        if ( check_login( $member['ID'], $member['Password'] ) )
        {
			$iCookieTime = 0;
       	
        	if (isset($_POST['rememberMe']) && $_POST['rememberMe'])
				$iCookieTime = time() + 24*60*60*30;
        	
        	setcookie( "memberID", $_COOKIE['memberID'], time() - 24*60*60, '/' );
			setcookie( "memberPassword", $_COOKIE['memberPassword'], time() - 24*60*60, '/' );
			setcookie( "memberID", $member['ID'], $iCookieTime, '/' );
			setcookie( "memberPassword", $member['Password'], $iCookieTime, '/' );
			//setcookie( 'userArray', 'aUser' . $member['ID'] );
			$update_res = db_res( "UPDATE `Profiles` SET `DateLastLogin` = NOW() WHERE `ID` = {$member['ID']}" );
			createUserDataFile( $member['ID'] );

			$p_arr = getProfileInfo( $member['ID'] );
			
			if( !$sUrlRelocate = $_REQUEST['relocate'] or basename( $_REQUEST['relocate'] ) == 'index.php' or basename( $_REQUEST['relocate'] ) == 'join.php' )
				 $sUrlRelocate = $_SERVER['PHP_SELF'];

			$_page['name_index'] = 150;
			$_page['css_name'] = '';
			
			$_ni = $_page['name_index'];
			$_page_cont[$_ni]['page_main_code'] = MsgBox( _t( '_Please Wait' ) );
			$_page_cont[$_ni]['url_relocate'] = htmlspecialchars( $sUrlRelocate );
			PageCode();
		}
		exit;
    }
}

$member['ID'] = (int)$_COOKIE['memberID'];
$member['Password'] = $_COOKIE['memberPassword'];

$_ni = $_page['name_index'];

// --------------- [END] page components

// this is dynamic page -  send headers to do not cache this page

// --------------- page components functions


send_headers_page_changed();

$oNew = new BxDolMember($member['ID'], $site, $dir);

/*
$sPhotoBlock = '<div style="width:49%;">'.DesignBoxContent ( _t( '_My Photos' ), $oNew->showMyPhotos(1), 1).'</div>';
if (strlen($oNew->showMyPhotos(2)) > 0)
	$sPhotoBlock .= '<div style="width:49%; border:=1px solid red;">'.DesignBoxContent ( _t( '_My Photos' ), $oNew->showMyPhotos(2), 1).'</div>';*/

$_page_cont[$_ni]['page_main_code'] = $oNew->getCode();
PageCode();

?>