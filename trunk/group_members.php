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

// --------------- page variables and login


$_page['name_index']	= 77;
$_page['css_name']		= 'groups.css';


if( $logged['member'] = member_auth( 0, false ) )
	$memberID = (int)$_COOKIE['memberID'];
else
{
	$memberID = 0;
	$logged['admin'] = member_auth( 1, false );
}


$groupID = (int)$_REQUEST['ID'];

if ( !$groupID )
{
	Header( "Location: {$site['url']}groups_home.php" );
	exit;
}

$bcd = getParam('breadCrampDivider');

$_page['header_text'] = _t( "_Group members" );

$_page['header'] = _t( "_Group members" );
$_ni = $_page['name_index'];

if ( $arrGroup = getGroupInfo( $groupID ) )
{
	$arrGroup['Name_html'] = htmlspecialchars_adv( $arrGroup['Name'] );
	
	if ( (int)$arrGroup['hidden_group'] and !isGroupMember( $memberID, $groupID ) and !$logged['admin'] )
		$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot view group members while not a group member" );
	else
	{
		if( $arrGroup['status'] == 'Active' or $arrGroup['creatorID'] == $memberID or $logged['admin'] )
		{
			$_page['header'] = _t( "_Group members" );
			PageCompMainCode();
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
else
	$_page_cont[$_ni]['page_main_code'] = _t( "_Group not found_desc" );

// --------------- page components

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode()
{
	global $memberID;
	global $groupID;
	global $arrGroup;
	global $site;
	global $bcd;
	global $oTemplConfig;
	global $_page_cont;
	global $_ni;
	
	if( $_REQUEST['mode'] == 'edit' and $arrGroup['creatorID'] == $memberID )
	{
		$editMode = true;
		$editModeReq = 'mode=edit&amp;';
		$editModeSql = "`memberID`!={$arrGroup['creatorID']} AND";
	}
	else
	{
		$editMode = false;
		$editModeReq = '';
		$editModeSql = '';
	}
	
	$breadCrumbs = <<<EOJ
		<div class="groups_breadcrumbs">
			<a href="{$site['url']}">{$site['title']}</a> $bcd
			<a href="{$site['url']}groups_home.php">__Groups__</a> $bcd
			<a href="{$site['url']}group.php?ID=$groupID">{$arrGroup['Name_html']}</a> $bcd
			<span class="active_link">__Group members__</span>
		</div>
EOJ;
	
	$breadCrumbs = str_replace( "__Groups__", _t( "_Groups" ), $breadCrumbs );
	$breadCrumbs = str_replace( "__Group members__", _t( "_Group members" ), $breadCrumbs );
	
	
	$arrMemNum = db_arr( "SELECT COUNT(`memberID`) FROM `GroupsMembers` WHERE $editModeSql `groupID`=$groupID  AND `status`='Active'" );
	
	$totalNum = (int)$arrMemNum[0];
	if( $totalNum )
	{
		$resPerPage = $oTemplConfig -> iGroupMembersResPerPage;
		$pagesNum = ceil( $totalNum / $resPerPage );
		$page = (int)$_REQUEST['page'];
		
		if( $page < 1 )
			$page = 1;
		if( $page > $pagesNum )
			$page = $pagesNum;
		
		$sqlFrom = ( ( $page - 1 ) * $resPerPage );
		
		$query = "
			SELECT
				`GroupsMembers`.`memberID`,
				`Profiles`.`NickName`,
				IF( `GroupsMembers`.`memberID`={$arrGroup['creatorID']}, 1, 0 ) AS `isCreator`
			FROM
				`GroupsMembers`, `Profiles`
			WHERE
				$editModeSql
				`GroupsMembers`.`groupID`=$groupID AND
				`GroupsMembers`.`status`='Active' AND
				`GroupsMembers`.`memberID`=`Profiles`.`ID`
			ORDER BY
				`isCreator` DESC,
				`GroupsMembers`.`Date` DESC
			LIMIT $sqlFrom, $resPerPage
			";
		
		$resMembers = db_res( $query );
		
		$numOnPage = mysql_num_rows( $resMembers );
		$showingFrom = $sqlFrom + 1;
		$showingTo   = $sqlFrom + $numOnPage;
		
		$showingResults = _t( '_Showing results:', $showingFrom, $showingTo, $totalNum );
		
		
		if( $pagesNum > 1 )
		{
			$pagesUrl = "{$_SERVER['PHP_SELF']}?{$editModeReq}ID={$groupID}&amp;page={page}";
			$pagination = genPagination( $pagesNum, $page, $pagesUrl );
		}

		$_page_cont[$_ni]['bread_crumbs']    = $breadCrumbs;
		$_page_cont[$_ni]['showing_results'] = $showingResults;
		$_page_cont[$_ni]['pagination']      = $pagination;
		
		ob_start();
		?>
				<div class="clear_both"></div>
		<?php
		while( $arrMember = mysql_fetch_assoc( $resMembers ) )
		{
			?>
				<div class="group_member">
			<?
			echo get_member_thumbnail( $arrMember['memberID'], 'none' );
			echo "<a href=\"".getProfileLink($arrMember['memberID'])."\">{$arrMember['NickName']}</a>";
			if( (int)$arrMember['isCreator'] )
				echo '<div class="mygroup_leader_is">'._t('_group creator').'</div>';
			if( $editMode )
				echo '<div class="group_member_edit"><a href="'."{$site['url']}group_actions.php?ID=$groupID&amp;a=delmem&amp;mem={$arrMember['memberID']}".'" onclick="return confirm(\''._t('_Are you sure want to delete this member?').'\')">' ._t('_Delete member').'</a></div>';
			?>
				</div>
			<?php
		}
		?>
				<div class="clear_both"></div>
		<?php
		$_page_cont[$_ni]['page_main_code'] = ob_get_clean();
	}
	else
	{
		$_page_cont[$_ni]['bread_crumbs']    = '';
		$_page_cont[$_ni]['pagination']      = '';
		$_page_cont[$_ni]['showing_results'] = '';
		$_page_cont[$_ni]['page_main_code']  = _t( '_Sorry, no members are found' );
	}
}

?>