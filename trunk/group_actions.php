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
require_once( BX_DIRECTORY_PATH_INC . 'groups.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );

// --------------- page variables and login


$_page['name_index']	= 76;
$_page['css_name']		= 'groups.css';

$_page['header']        = _t( "_Group action" );
$_page['header_text']   = _t( "_Group action" );


$logged['member'] = member_auth( 0, true );
$memberID = (int)$_COOKIE['memberID'];
$arrMember = getProfileInfo( $memberID );

$action = $_REQUEST['a'];
$groupID = (int)$_REQUEST['ID'];

if ( !$groupID )
{
	Header( "Location: {$site['url']}groups_home.php" );
	exit;
}

$_ni = $_page['name_index'];

if ( $arrGroup = getGroupInfo( $groupID ) )
{
	$getBackCode = " (<a href=\"{$site['url']}group.php?ID={$groupID}\">"._t('_Back to group')."</a>)";

	switch( $action )
	{
        // uploadFile
        // 2008-02-10
		case 'uploadFile':
			include dirname(__FILE__) . '/_dwbn/group.upload.php';
		break;

        // delFile
        // 2008-02-11
        case 'delFile':
            include dirname(__FILE__) . '/_dwbn/group.deletefile.php';
        break;

		case 'upload':
			if( isGroupMember( $memberID, $groupID ) )
			{
				if( $arrGroup['members_post_images'] or $arrGroup['creatorID'] == $memberID )
				{
					if( $_POST['do_submit'] )
					{
						if( $_FILES['file'] )
						{
							switch( $_FILES['file']['error'] )
							{
								case UPLOAD_ERR_NO_FILE:
									$_page['header']        = _t( "_Upload to group gallery error" );
									$_page['header_text']   = _t( "_Upload to group gallery error" );
									$_page_cont[$_ni]['page_main_code'] = _t('_You should specify file');
								break;
								
								case UPLOAD_ERR_OK:
									$arrImgInfo = getimagesize( $_FILES['file']['tmp_name'] );
									$Ext = '';
									
									switch ($arrImgInfo['mime'])
									{
										case 'image/gif':  $Ext = 'gif'; break;
										case 'image/jpeg': $Ext = 'jpg'; break;
										case 'image/png':  $Ext = 'png'; break;
									}
									
									if ( $Ext )
									{
										$tempImg = tempnam( '/tmp', 'Dol_' );
										$tempImg_ = $tempImg.'_';
										$tempImg_icon = $tempImg.'_icon';
										move_uploaded_file( $_FILES['file']['tmp_name'], $tempImg );

										imageResize( $tempImg, $tempImg_icon, 45, 45 );
										
										$imgWidth   = $arrImgInfo[0];
										$imgHeight  = $arrImgInfo[1];
										//$imgWidth_  = $arrImgInfo[0];
										//$imgHeight_ = $arrImgInfo[1];
										
										$maxWidth   = (int)getParam( 'group_img_width' ); //600
										$maxHeight  = (int)getParam( 'group_img_height' ); //600
										$maxWidth_  = (int)getParam( 'group_img_tmb_width' ); //600
										$maxHeight_ = (int)getParam( 'group_img_tmb_height' ); //600
										
										if( $imgWidth > $maxWidth or $imgHeight > $maxHeight )
										{
											imageResize( $tempImg, $tempImg, $maxWidth, $maxHeight );
											list( $imgWidth, $imgHeight ) = getimagesize( $tempImg );
										}
										
										if ( $imgWidth > $maxWidth_ or $imgHeight > $maxHeight_ )
										{
											imageResize( $tempImg, $tempImg_, $maxWidth_, $maxHeight_ );
											list( $imgWidth_, $imgHeight_ ) = getimagesize( $tempImg_ );
										}
										else
										{
											copy( $tempImg, $tempImg_ );
											$imgWidth_  = $imgWidth;
											$imgHeight_ = $imgWidth;
										}
										
										$seed = substr( md5( time() ), 0, 10 );
										
										$query = "
											INSERT INTO `GroupsGallery` SET 
												`groupID` = $groupID,
												`ext`     = '$Ext',
												`width`   = $imgWidth,
												`height`  = $imgHeight,
												`width_`  = $imgWidth_,
												`height_` = $imgHeight_,
												`by`      = $memberID,
												`seed`    = '$seed'
											";
										
										db_res( $query );
										
										$imgID = mysql_insert_id();
										
										rename( $tempImg,  BX_DIRECTORY_PATH_GROUPS_GALLERY."{$groupID}_{$imgID}_{$seed}.{$Ext}" );
										rename( $tempImg_, BX_DIRECTORY_PATH_GROUPS_GALLERY."{$groupID}_{$imgID}_{$seed}_.{$Ext}" );
										rename( $tempImg_icon, BX_DIRECTORY_PATH_GROUPS_GALLERY."{$groupID}_{$imgID}_{$seed}_icon.{$Ext}" );
										
										chmod( BX_DIRECTORY_PATH_GROUPS_GALLERY."{$groupID}_{$imgID}_{$seed}.{$Ext}", 0644 );
										chmod( BX_DIRECTORY_PATH_GROUPS_GALLERY."{$groupID}_{$imgID}_{$seed}_.{$Ext}", 0644 );
										chmod( BX_DIRECTORY_PATH_GROUPS_GALLERY."{$groupID}_{$imgID}_{$seed}_icon.{$Ext}", 0644 );
										
										if( $_POST['set_def'] == 'yes' and $arrGroup['creatorID'] == $memberID )
											setGroupThumb( $groupID, $imgID );
										
										if( $_POST['back'] == 'home' )
											$getBackUrl = "group.php?ID=$groupID";
										else
											$getBackUrl = "group_gallery.php?ID=$groupID";
										
										$getBackCode = " (<a href=\"$getBackUrl\">"._t('_Back to group')."</a>)";
										
										$_page['header']        = _t( "_Upload to group gallery" );
										$_page['header_text']   = _t( "_Upload to group gallery" );
										$_page_cont[$_ni]['page_main_code'] = _t('_Upload succesfull').$getBackCode;
									}
									else
									{
										$_page['header']        = _t( "_Upload to group gallery error" );
										$_page['header_text']   = _t( "_Upload to group gallery error" );
										$_page_cont[$_ni]['page_main_code'] = _t('_You should select correct image file');
									}
								break;
								default:
									$_page['header']        = _t( "_Upload to group gallery error" );
									$_page['header_text']   = _t( "_Upload to group gallery error" );
									$_page_cont[$_ni]['page_main_code'] = _t('_Upload error');
							}
						}
						else
						{
							$_page['header']        = _t( "_Upload to group gallery error" );
							$_page['header_text']   = _t( "_Upload to group gallery error" );
							$_page_cont[$_ni]['page_main_code'] = _t('_Upload error');
						}
					}
					else
					{
						$_page['header']        = _t( "_Upload to group gallery" );
						$_page['header_text']   = _t( "_Upload to group gallery" );
						
						$_page_cont[$_ni]['page_main_code'] =  _t('_Gallery upload_desc');
						$_page_cont[$_ni]['page_main_code'] .= genUploadForm( $groupID );
					}
				}
				else
				{
					$_page['header']        = _t( "_Upload to group gallery error" );
					$_page['header_text']   = _t( "_Upload to group gallery error" );
					$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot upload images because members of this group not allowed to upload images" );
				}
			}
			else
			{
				$_page['header']        = _t( "_Upload to group gallery error" );
				$_page['header_text']   = _t( "_Upload to group gallery error" );
				$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot upload images because you're not group member" );
			}
		break;
		case 'join':
			if( isGroupMember( $memberID, $groupID, false ) )
			{
				$_page['header']        = _t( "_Group join error" );
				$_page['header_text']   = _t( "_Group join error" );
				$_page_cont[$_ni]['page_main_code'] = _t( "_You're already in group" );
			}
			else
			{
				if( (int)$arrGroup['hidden_group'] )
				{
					$_page['header']        = _t( "_Group join error" );
					$_page['header_text']   = _t( "_Group join error" );
					$_page_cont[0]['page_main_code'] = _t( "_Sorry, group is hidden" );
				}
				else
				{
					if( (int)$arrGroup['open_join'] )
					{
						addMember2Group( $memberID, $groupID, 'Active' );
						$_page['header']        = _t( "_Group join" );
						$_page['header_text']   = _t( "_Group join" );
						$_page_cont[$_ni]['page_main_code'] = _t( "_Congrats. Now you're group member" ).$getBackCode;
					}
					else
					{
						sendRequestToCreator( $groupID, $memberID );
						addMember2Group( $memberID, $groupID, 'Approval' );
						$_page['header']        = _t( "_Group join" );
						$_page['header_text']   = _t( "_Group join" );
						$_page_cont[$_ni]['page_main_code'] = _t( "_Request sent to the group creator. You will become active group member when he approve you." ).$getBackCode;
					}
				}
			}
		break;
		case 'resign':
			if( isGroupMember( $memberID, $groupID, false ) )
			{
				if( $arrGroup['creatorID'] == $memberID )
				{
					$_page['header']        = _t( "_Group resign error" );
					$_page['header_text']   = _t( "_Group resign error" );
					$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot resign the group because you're creator" );
				}
				else
				{
					resignGroupMember( $memberID, $groupID );
					$_page['header']        = _t( "_Group resign" );
					$_page['header_text']   = _t( "_Group resign" );
					$_page_cont[$_ni]['page_main_code'] = _t( "_You succesfully resigned from group" ).$getBackCode;
				}
			}
			else
			{
				$_page['header']        = _t( "_Group resign error" );
				$_page['header_text']   = _t( "_Group resign error" );
				$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot resign the group because you're not group member" );
			}
		break;
		case 'def': //set group thumbnail image
			if( $arrGroup['creatorID'] == $memberID )
			{
				$img = (int)$_REQUEST['img'];
				if( $img )
					setGroupThumb( $groupID, $img );
				Header( "Location: {$site['url']}group_gallery.php?ID=$groupID" );
				exit;
			}
			else
			{
				$_page['header']        = _t( "_Group thumnail set" );
				$_page['header_text']   = _t( "_Group thumnail set" );
				$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot set group thumnail because you are not group creator" );
			}
		break;
		case 'delimg': //delete image from group gallery
			$img = (int)$_REQUEST['img'];
			$isAuthor = db_res("SELECT `ID` FROM `GroupsGallery` WHERE `ID`=$img AND `by`=$memberID");
			
			if( $arrGroup['creatorID'] == $memberID or mysql_num_rows($isAuthor) )
			{
				if( $img )
					deleteGroupImage( $groupID, $img );
				Header( "Location: {$site['url']}group_gallery.php?ID=$groupID" );
				exit;
			}
			else
			{
				$_page['header']        = _t( "_Group image delete" );
				$_page['header_text']   = _t( "_Group image delete" );
				$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot delete image because you are not group creator" );
			}
		break;
		case 'delmem':
			if( $arrGroup['creatorID'] == $memberID )
			{
				$mem = (int)$_REQUEST['mem'];
				if( $mem )
				{
					if( $mem != $memberID )
					{
						resignGroupMember( $mem, $groupID );
						if( $_SERVER['HTTP_REFERER'] )
							Header( "Location: {$_SERVER['HTTP_REFERER']}" );
						else
							Header( "Location: {$site['url']}group_members.php?ID=$groupID" );
						exit;
					}
					else
					{
						$_page['header']        = _t( "_Group member delete error" );
						$_page['header_text']   = _t( "_Group member delete error" );
						$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot delete yourself from group because you are group creator" );
					}
				}
				exit;
			}
			else
			{
				$_page['header']        = _t( "_Group member delete error" );
				$_page['header_text']   = _t( "_Group member delete error" );
				$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot delete group member because you are not group creator" );
			}
		break;
		case 'approve':
			if( $arrGroup['creatorID'] == $memberID )
			{
				$mem = (int)$_REQUEST['mem'];
				if( $mem )
				{
					$queryAppr = "
						UPDATE `GroupsMembers`
						SET `status`='Active', `Date` = NOW()
						WHERE
							`groupID`=$groupID AND
							`memberID`=$mem AND
							`status`='Approval'
						";
					
					$resAppr = db_res( $queryAppr );
					if( mysql_affected_rows() )
					{
						$_page['header']        = _t( "_Group member approve" );
						$_page['header_text']   = _t( "_Group member approve" );
						$_page_cont[$_ni]['page_main_code'] = _t( "_Member succesfully approved" ).$getBackCode;
						
						$msg_subj = 'You were approved';
						$msg_text = getParam( 'group_approve_notify' );
						
						$aPlus = array();
						$aPlus['group'] = "<a href=\"{$site['url']}group.php?ID=$groupID\">".htmlspecialchars_adv($arrGroup['Name'])."</a>";
						
						foreach( $aPlus as $key => $val )
							$msg_text = str_replace( "{{$key}}", $val, $msg_text );
						
						$msg_text = addslashes( $msg_text );
						
						db_res( "INSERT INTO `Messages`
							( `Date`, `Sender`, `Recipient`, `Subject`, `Text`, `New` )
							VALUES ( NOW(), $memberID, $mem, '$msg_subj', '$msg_text', '1' )" );
					}
					else
					{
						$_page['header']        = _t( "_Group member approve error" );
						$_page['header_text']   = _t( "_Group member approve error" );
						$_page_cont[$_ni]['page_main_code'] = _t( "_Some error occured" );
					}
				}
			}
			else
			{
				$_page['header']        = _t( "_Group member approve error" );
				$_page['header_text']   = _t( "_Group member approve error" );
				$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot approve group member because you are not group creator" );
			}
		break;
		case 'reject':
			if( $arrGroup['creatorID'] == $memberID )
			{
				$mem = (int)$_REQUEST['mem'];
				if( $mem )
				{
					$queryAppr = "
						DELETE FROM `GroupsMembers`
						WHERE
							`groupID`=$groupID AND
							`memberID`=$mem AND
							`status`='Approval'
						";
					
					$resAppr = db_res( $queryAppr );
					if( mysql_affected_rows() )
					{
						$_page['header']        = _t( "_Group member reject" );
						$_page['header_text']   = _t( "_Group member reject" );
						$_page_cont[$_ni]['page_main_code'] = _t( "_Member succesfully rejected" ).$getBackCode;
						
						$msg_subj = 'You were rejected';
						$msg_text = getParam( 'group_reject_notify' );
						
						$aPlus = array();
						$aPlus['group'] = "<a href=\"{$site['url']}group.php?ID=$groupID\">".htmlspecialchars_adv($arrGroup['Name'])."</a>";
						
						$arrMem = getProfileInfo( $mem ); //db_arr( "SELECT `NickName` FROM `Profiles` WHERE `ID`=$mem" );
						$aPlus['member'] = $arrMem['NickName'];
						
						foreach( $aPlus as $key => $val )
							$msg_text = str_replace( "{{$key}}", $val, $msg_text );
						
						$msg_text = addslashes( $msg_text );
						
						db_res( "INSERT INTO `Messages`
							( `Date`, `Sender`, `Recipient`, `Subject`, `Text`, `New` )
							VALUES ( NOW(), $memberID, $mem, '$msg_subj', '$msg_text', '1' )" );
					}
					else
					{
						$_page['header']        = _t( "_Group member reject error" );
						$_page['header_text']   = _t( "_Group member reject error" );
						$_page_cont[$_ni]['page_main_code'] = _t( "_Some error occured" );
					}
				}
			}
			else
			{
				$_page['header']        = _t( "_Group member reject error" );
				$_page['header_text']   = _t( "_Group member reject error" );
				$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot reject group member because you are not group creator" );
			}
		break;
		case 'invite':
			if( ( (int)$arrGroup['members_invite'] and isGroupMember( $memberID, $groupID ) ) or $arrGroup['creatorID'] == $memberID )
			{
				if( $_REQUEST['do_submit'] )
				{
					$_page['header']        = _t( "_Group invite" );
					$_page['header_text']   = _t( "_Group invite" );
					
					$arrInvites = $_REQUEST['invites'];
					if( $arrInvites )
					{
						foreach( $arrInvites as $i => $iMemID )
						{
							$arrInvites[$i] = (int)$iMemID;
							if( !$arrInvites[$i] )
								unset( $arrInvites[$i] );
						}
						
						$arrInvites = array_unique( $arrInvites );
						
						foreach( $arrInvites as $iMemID )
							sendGroupInvite( $groupID, $iMemID );
						
						$_page_cont[$_ni]['page_main_code'] = _t( '_Invites succesfully sent' ).$getBackCode;
					}
					else
					{
						$_page_cont[$_ni]['page_main_code'] = _t( '_You should specify at least one member' ).$getBackCode;
					}
				}
				else
				{
					$_page['header']        = _t( "_Group invite" );
					$_page['header_text']   = _t( "_Group invite" );
					$_page['js_name']       = "members_thrower.js";
					$_page['extra_js']      = <<<EOJ
<script type="text/javascript">
	var sForm = 'group_invite_form';
	var sFrom = 'friends';
	var sTo   = 'invites';
</script>
EOJ;
					$_page_cont[$_ni]['page_main_code'] = genGroupInviteForm();
				}
			}
			else
			{
				$_page['header']        = _t( "_Group invite error" );
				$_page['header_text']   = _t( "_Group invite error" );
				$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot invite members to the group" );
			}
		break;
		case 'acc_inv': //accept invite
			db_res( "
				UPDATE `GroupsMembers`
				SET `status`='Active', `Date` = NOW()
				WHERE
					`groupID`=$groupID AND
					`memberID`=$memberID AND
					`status`='Invited'
				" );
			if( mysql_affected_rows() )
			{
				$_page['header']        = _t( "_Group invite accept" );
				$_page['header_text']   = _t( "_Group invite accept" );
				$_page_cont[$_ni]['page_main_code'] = _t( "_You succesfully accepted group invite" ).$getBackCode;
			}
			else
			{
				$_page['header']        = _t( "_Group invite accept error" );
				$_page['header_text']   = _t( "_Group invite accept error" );
				$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot accept group invite" );
			}
		break;
		case 'rej_inv': //reject invite
			db_res( "
				DELETE FROM `GroupsMembers`
				WHERE
					`groupID`=$groupID AND
					`memberID`=$memberID AND
					`status`='Invited'
				" );
			
			$_page['header']        = _t( "_Group invite reject" );
			$_page['header_text']   = _t( "_Group invite reject" );
			$_page_cont[$_ni]['page_main_code'] = _t( "_You succesfully rejected group invite" );
		break;
		default:
			$_page['header']        = _t( "_Group action error" );
			$_page['header_text']   = _t( "_Group action error" );
			$_page_cont[$_ni]['page_main_code'] = _t( "_Unknown group action" );
	}
}
else
	$_page_cont[$_ni]['page_main_code'] = _t( "_Group not found_desc" );

// --------------- page components

// --------------- [END] page components

PageCode();


function genGroupInviteForm()
{
	global $groupID;
	global $memberID;
	
	$sFriendsQuery = 
	"SELECT
		/*`FriendList`.*,*/
		IF(`FriendList`.`ID`=$memberID, `FriendList`.`Profile`, `FriendList`.`ID`) AS `ID`,
		`Profiles`.`NickName`
	FROM `FriendList`
	LEFT JOIN `Profiles`
	ON (IF(`FriendList`.`ID`=$memberID, `FriendList`.`Profile`, `FriendList`.`ID`) = `Profiles`.`ID`)
	WHERE
		(`FriendList`.`ID`=$memberID OR `FriendList`.`Profile`=$memberID) AND `check` =1
	";
	
	$rFriends = db_res( $sFriendsQuery );
	
	$aFriends = array();
	
	while( $aFriend = mysql_fetch_assoc( $rFriends ) )
		$aFriends[$aFriend['ID']] = $aFriend['NickName'];
	
	ob_start();
	
	echoDbg( $_REQUEST['invites'] );
	?>
	<form name="group_invite_form" id="group_invite_form" action="<?=$_SERVER['PHP_SELF']?>" method="GET" onsubmit="return checkThrowerForm();">
		<input type="hidden" name="a" value="invite" />
		<input type="hidden" name="ID" value="<?=$groupID?>" />
		<div class="group_invite_wrapper">
			<div class="group_invite_desc"><?=_t('_Group invite_desc')?></div>
			<table class="group_invite_table">
				<tr class="group_invite_tr1">
					<td class="group_invite_td1">
						<div class="group_invite_head"><?=_t('_Your friends')?></div>
						<select name="friends[]" id="friends" class="group_friends_list" size="10" multiple="multiple">
	<?php
	foreach( $aFriends as $friendID => $friendNick )
	{
		echo "<option value=\"$friendID\">".htmlspecialchars($friendNick)."</option>\n";
	}
	?>
						</select>
					</td>
					<td class="group_invite_td2">
						<div><input type="button" value="<?=_t('_Add ->')?>" onclick="throwMembersFromTo();" /></div>
						<div><input type="button" value="<?=_t('_<- Remove')?>" onclick="unthrowMembersFromTo();" /></div>
						<div><input type="button" value="<?=_t('_Find more...')?>" onclick="findMoreMembers();" /></div>
					</td>
					<td class="group_invite_td3">
						<div class="group_invite_head"><?=_t('_Invite list')?></div>
						<select name="invites[]" id="invites" class="group_invites_list" size="10" multiple="multiple">
						</select>
					</td>
				</tr>
				<tr class="group_invite_tr2">
					<td colspan="3" class="group_invite_td4">
						<input type="submit" name="do_submit" value="<?=_t('_Send invites')?>" />
					</td>
				</tr>
			</table>
		</div>
	</form>
	<?php
	
	return ob_get_clean();
}

function sendGroupInvite( $groupID, $iMemID )
{
	global $arrMember;
	global $arrGroup;
	global $memberID;
	global $groupID;
	global $site;
	
	$aMemStatus = db_arr( "SELECT `status` FROM `GroupsMembers` WHERE `memberID`=$iMemID AND `groupID`=$groupID" );
	
	if( $aMemStatus['status'] == 'Active' or $aMemStatus['status'] == 'Invited' )
		return false;
	
	if( $aMemStatus['status'] == 'Approval' )
		db_res( "DELETE FROM `GroupsMembers` WHERE `memberID`=$iMemID AND `groupID`=$groupID" );
	
	db_res( "INSERT INTO `GroupsMembers` VALUES ( $iMemID, $groupID, 'Invited', NOW() )" );
	
	//send invitation message
	$msg_subj = "Group invitation";
	$msg_text = getParam( 'group_invitation_text' );
	
	$aPlus = array();
	$aPlus['sender'] = "<a href=\"".getProfileLink($arrMember['ID'])."\">".htmlspecialchars_adv( $arrMember['NickName'] )."</a>";
	$aPlus['group']  = "<a href=\"{$site['url']}group.php?ID={$groupID}\">".htmlspecialchars_adv( $arrGroup['Name'] )."</a>";
	
	$aPlus['accept'] = "<a href=\"{$site['url']}group_actions.php?a=acc_inv&amp;ID=$groupID\">accept</a>";
	$aPlus['reject'] = "<a href=\"{$site['url']}group_actions.php?a=rej_inv&amp;ID=$groupID\">reject</a>";
	
	foreach( $aPlus as $key => $val )
		$msg_text = str_replace( "{{$key}}", $val, $msg_text );
	
	$msg_text = addslashes( $msg_text );
	db_res( "INSERT INTO `Messages`
		( `Date`, `Sender`, `Recipient`, `Subject`, `Text`, `New` )
		VALUES ( NOW(), $memberID, $iMemID, '$msg_subj', '$msg_text', '1' )" );
}

?>
