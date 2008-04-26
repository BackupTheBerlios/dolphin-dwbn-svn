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

// My Groups

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'groups.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// --------------- page variables and login


$_page['name_index']	= 71;
$_page['css_name']		= 'groups.css';


if ( $logged['member'] = member_auth( 0, false ) )
	$memberID = (int)$_COOKIE['memberID'];
else
	$memberID = 0;

$logged['admin'] = member_auth( 1, false );

$groupID = (int)$_REQUEST['ID'];


if ( !$groupID )
{
	Header( "Location: {$site['url']}groups_home.php" );
	exit;
}

// --------------- page components
$_ni = $_page['name_index'];

$bcd = getParam('breadCrampDivider');
$date_format_php = getParam('php_date_format');

if ( !$arrGroup = getGroupInfo( $groupID ) )
{
	$_page['name_index'] = 0;
	$_page['header'] = _t( "_Group not found" );
	$_page['header_text'] = _t( "_Group not found" );
	$_page_cont[0]['page_main_code'] = _t( "_Group not found_desc" );
}
else
{
	if( (int)$arrGroup['hidden_group'] and !isGroupMember( $memberID, $groupID ) and !$logged['admin'] )
	{
		$_page['name_index'] = 0;
		$_page['header'] = _t( "_Group is hidden" );
		$_page['header_text'] = _t( "_Group is hidden" );
		$_page_cont[0]['page_main_code'] = _t( "_Sorry, group is hidden" );
	}
	else
	{
		if( $arrGroup['status'] == 'Active' or $arrGroup['creatorID'] == $memberID or $logged['admin'] )
		{
			$arrGroup['Name_html'] = htmlspecialchars_adv( $arrGroup['Name'] );
			
			$breadCrumbs = <<<EOJ
				<div class="groups_breadcrumbs">
					<a href="{$site['url']}">{$site['title']}</a> $bcd
					<a href="{$site['url']}groups_home.php">__Groups__</a> $bcd
					<span class="active_link">{$arrGroup['Name_html']}</span>
				</div>
EOJ;
			
			$breadCrumbs = str_replace( "__Groups__", _t( "_Groups" ), $breadCrumbs );
			
			$_page['header'] = "{$site['title']} $bcd " . _t( "_Groups" ) . " $bcd {$arrGroup['Name_html']}";
			$_page['header_text'] = $arrGroup['Name_html'];
			
			$_page_cont[$_ni]['groups_breadcrumbs'] = $breadCrumbs;
			
			// begin group info
			
			if( (int)$arrGroup['hidden_group'] )
				$typeHelp = 7;
			else
				if( (int)$arrGroup['open_join'] )
					$typeHelp = 5;
				else
					$typeHelp = 6;
			
			$typeHelpLink = "{$site['url']}groups_help.php?i=$typeHelp";
			
			// labels	
			$_page_cont[$_ni]['category_l']      = _t( "_Category" );
			$_page_cont[$_ni]['created_l']       = _t( "_Created" );
			$_page_cont[$_ni]['location_l']      = _t( "_Location" );
			$_page_cont[$_ni]['members_count_l'] = _t( "_Members count" );
			$_page_cont[$_ni]['group_creator_l'] = _t( "_Group creator" );
			$_page_cont[$_ni]['group_about_l']   = _t( "_About group" );
			$_page_cont[$_ni]['group_type_l']    = _t( "_Group type" );
			$_page_cont[$_ni]['group_type_help'] = '<a href="'.$typeHelpLink.'" target="_blank" onclick="window.open(this.href,\'helpwin\',\'width=350,height=200\');return false;" >'._t( "_help" ).'</a>';
			
			//info
			if ( $arrGroup['thumb'] and file_exists(BX_DIRECTORY_PATH_GROUPS_GALLERY . "{$groupID}_{$arrGroup['thumb']}_{$arrGroup['seed']}_.{$arrGroup['thumbExt']}" ) )
				$groupImageUrl = "{$site['groups_gallery']}{$groupID}_{$arrGroup['thumb']}_{$arrGroup['seed']}_.{$arrGroup['thumbExt']}";
			else
				$groupImageUrl = "{$site['groups_gallery']}no_pic.gif";
			
			$arrMem = getProfileInfo( $arrGroup['creatorID'] ); //db_assoc_arr( "SELECT `NickName` FROM `Profiles` WHERE `ID`={$arrGroup['creatorID']};" );
			$creatorNick = $arrMem['NickName'];
			$sSpacerPath = 'templates/base/images/icons/spacer.gif';
			$sSpacerName = $site['url'].$sSpacerPath;
			$_page_cont[$_ni]['group_image']         = "<a href=\"{$site['url']}group_gallery.php?ID={$groupID}\">
															<!--<img src=\"$groupImageUrl\" />-->
															<img src=\"{$sSpacerName}\" style=\"width:110px;height:110px; background-image: url({$groupImageUrl});\" class=\"photo1\"/>
														</a>";
			$_page_cont[$_ni]['group_gallery_link']  = "<a href=\"{$site['url']}group_gallery.php?ID={$groupID}\">" . _t( "_Group gallery" ) . "</a>";
            $_page_cont[$_ni]['group_gallery_link'] .= "<br /><a href=\"{$site['url']}group_files.php?ID={$groupID}\">Uploaded files</a>";

			$_page_cont[$_ni]['group_creator_thumb'] = get_member_thumbnail( $arrGroup['creatorID'], 'none' );
			$_page_cont[$_ni]['group_creator_link']  = "<a href=\"{$site['url']}$creatorNick\">".htmlspecialchars_adv($creatorNick)."</a>";
			
			$_page_cont[$_ni]['category']            = htmlspecialchars_adv(  $arrGroup['categName'] );
			$_page_cont[$_ni]['category_link']       = "<a href=\"{$site['url']}groups_browse.php?categID={$arrGroup['categID']}\">{$arrGroup['categName']}</a>";

			$_page_cont[$_ni]['group_type']          = _t( ( ( (int)$arrGroup['open_join'] and !(int)$arrGroup['hidden_group'] ) ? '_Public group' : '_Private group' ) );
			$_page_cont[$_ni]['created']             = date( $date_format_php, strtotime( $arrGroup['created'] ) );
			$_page_cont[$_ni]['country']             = _t( '__'.$prof['countries'][ $arrGroup['Country'] ] );
			$_page_cont[$_ni]['city']                = htmlspecialchars_adv( $arrGroup['City'] );
			$_page_cont[$_ni]['members_count']       = $arrGroup['membersCount'];
			$_page_cont[$_ni]['group_about']         = htmlspecialchars_adv( $arrGroup['About'] );
			$_page_cont[$_ni]['group_description']   = $arrGroup['Desc']; //no htmlspecialchars
			
			if( $arrGroup['status'] != 'Active' )
			{
				$_page_cont[$_ni]['group_status']    = _t( '_Group status' ) . ': ' .
				  '<span style="color:red;font-weight:bold;">' . _t( '_' . $arrGroup['status'] ) .'</span>' .
				  " (<a href=\"{$site['url']}groups_help.php?i=8\" target=\"_blank\" onclick=\"window.open(this.href,'helpwin','width=350,height=200');return false;\">"._t( "_Explanation" )."</a>)";
			}
			else
				$_page_cont[$_ni]['group_status']    = '';
			
			//end group info
			
			$_page_cont[$_ni]['group_actions']       = PageCompGroupActions();
			$_page_cont[$_ni]['group_members']       = PageCompGroupMembers();
			$_page_cont[$_ni]['group_forum']         = PageCompGroupForum();
		}
		else
		{
			$_page['name_index'] = 0;
			$_page['header'] = _t( "_Group is suspended" );
			$_page['header_text'] = _t( "_Group is suspended" );
			$_page_cont[0]['page_main_code'] = _t( "_Sorry, group is suspended" );
		}
	}
}


// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */

function PageCompGroupMembers()
{
	global $memberID;
	global $groupID;
	global $arrGroup;
	global $site;
	global $oTemplConfig;
	
	ob_start();
	?>
		<div class="group_members_pre1">
	<?php
	
	$numberMembers = $oTemplConfig -> iGroupMembersPreNum;
	$query = "
		SELECT
			`GroupsMembers`.`memberID` AS `ID`,
			`Profiles`.`NickName`
		FROM `GroupsMembers`, `Profiles`
		WHERE
			`GroupsMembers`.`groupID` = $groupID AND
			`GroupsMembers`.`Status` = 'Active' AND
			`GroupsMembers`.`memberID` = `Profiles`.`ID`
		ORDER BY RAND()
		LIMIT $numberMembers
	;";
	
	$resMembers = db_res( $query );
	
	while ( $arrMember = mysql_fetch_assoc( $resMembers ) )
	{
		?>
			<div class="group_member_pre">
				<?=get_member_thumbnail( $arrMember['ID'],'none' )?>
				<a href="<?= getProfileLink( $arrMember['ID'] )?>"><?=htmlspecialchars_adv( $arrMember['NickName'] )?></a>
			</div>
		<?php
	}
	?>
		</div>
		<div class="clear_both"></div>
		<div class="view_all_link">
			<a href="<?=$site['url']?>group_members.php?ID=<?=$groupID?>"><?=_t( "_View all members" )?></a>
		</div>
	<?php
	
	$ret = ob_get_clean();
	
	if( $arrGroup['creatorID'] == $memberID )
		$creatorEditMembers = "<div class=\"caption_item\"><a href=\"{$site['url']}group_members.php?mode=edit&amp;ID=$groupID\">". _t('_Edit members'). "</a></div>";
	else
		$creatorEditMembers = '';
		
	return DesignBoxContent( _t("_Group members"), $ret, 1, $creatorEditMembers );
}

function PageCompGroupForum()
{
	global $memberID;
	global $groupID;
	global $arrGroup;
	global $site;
	
	$ret = file_get_contents( "{$site['groups']}orca/?action=group_last_topics&forum=$groupID&virtID=$memberID&virtPass={$_COOKIE['memberPassword']}&trans=1" );
	
	$sViewAllForum = _t( '_View all topics' );
	$sPostNewTopic = _t( '_Post a new topic' );
	
	$caption_item = '<div class="caption_item">';
	
	if ( isGroupMember( $memberID, $groupID ) )
		$caption_item .= "<a href=\"{$site['groups']}orca/?action=goto&amp;forum_id=$groupID#action=goto&amp;new_topic=$groupID\">$sPostNewTopic</a> | ";
	$caption_item .= "<a href=\"{$site['groups']}orca/?action=goto&amp;forum_id=$groupID\">$sViewAllForum</a>";
	$caption_item .= '</div>';
	
	return DesignBoxContent( _t("_Group forum"), $ret, 1, $caption_item );
}

function PageCompGroupActions()
{
	global $memberID;
	global $groupID;
	global $arrGroup;
	global $site;
	global $dirGroups;
	global $logged;
	
	ob_start();
	
	if ( $logged['member'] )
	{
		if ( isGroupMember( $memberID, $groupID, false ) )
		{
			if ( isGroupMember( $memberID, $groupID ) ) //if Active member
			{
				if( (int)$arrGroup['members_invite'] or $arrGroup['creatorID'] == $memberID )
					genGroupActionBtn( 'Invite others', "group_actions.php?a=invite&amp;ID=$groupID" );
				
				if( (int)$arrGroup['members_post_images'] or $arrGroup['creatorID'] == $memberID )
                {
					genGroupActionBtn( 'Upload image', "group_actions.php?a=upload&amp;ID=$groupID" );
                    genGroupActionBtn( 'Upload files', "group_actions.php?a=uploadFile&amp;ID=$groupID" );
                }
				
				genGroupActionBtn( 'Post topic', "{$dirGroups}orca/?action=goto&amp;forum_id=$groupID#action=goto&amp;new_topic=$groupID" );
			}
			
			if ( $arrGroup['creatorID'] == $memberID )
				genGroupActionBtn( 'Edit group', "group_edit.php?ID=$groupID" );
			else
				genGroupActionBtn( 'Resign group', "group_actions.php?a=resign&amp;ID=$groupID", true );
		}
		else
			genGroupActionBtn( 'Join group', "group_actions.php?a=join&amp;ID=$groupID", true );
	}
	
	return ob_get_clean();
}

function genGroupActionBtn( $title, $url, $ask = false )
{
	global $site;
	
	if( $ask )
		$onclick = 'onclick="return confirm(\''._t("_Are you sure want to $title?").'\')"';
	else
		$onclick = '';
	?>
				<div class="group_action">
					<a href="<?="{$site['url']}{$url}"?>" <?=$onclick?>><?=_t('_'.$title)?></a>
				</div>
	<?php
}

?>
