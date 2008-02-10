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


$_page['name_index']	= 75;
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

$_page['header_text'] = _t( "_Group gallery" );

$_page['header'] = _t( "_Group gallery" );
$_ni = $_page['name_index'];

if ( $arrGroup = getGroupInfo( $groupID ) )
{
	$arrGroup['Name_html'] = htmlspecialchars_adv( $arrGroup['Name'] );
	
	if ( (int)$arrGroup['hidden_group'] and !isGroupMember( $memberID, $groupID ) and !$logged['admin'] )
		$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot view gallery while not a group member" );
	else
	{
		if( $arrGroup['status'] == 'Active' or $arrGroup['creatorID'] == $memberID or $logged['admin'] )
		{
			$_page['header'] = _t( "_Group gallery" );
			$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();
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
	
	$breadCrumbs = <<<EOJ
		<div class="groups_breadcrumbs">
			<a href="{$site['url']}">{$site['title']}</a> $bcd
			<a href="{$site['url']}groups_home.php">__Groups__</a> $bcd
			<a href="{$site['url']}group.php?ID=$groupID">{$arrGroup['Name_html']}</a> $bcd
			<span class="active_link">__Group gallery__</span>
		</div>
EOJ;
	
	$breadCrumbs = str_replace( "__Groups__", _t( "_Groups" ), $breadCrumbs );
	$breadCrumbs = str_replace( "__Group gallery__", _t( "_Group gallery" ), $breadCrumbs );
	
	ob_start();
	echo $breadCrumbs;
	
	$query = "
		SELECT
			`GroupsGallery`.*,
			`NickName`
		FROM `GroupsGallery`
		LEFT JOIN `Profiles`
		ON ( `by`=`Profiles`.`ID` )
		WHERE `groupID`=$groupID
		ORDER BY `GroupsGallery`.`ID`
		";
	
	$resPics = db_res( $query );
	
	?>
		<div class="group_gallery_wrapper">
			<div class="clear_both"></div>
	<?php
	while( $arrPic = mysql_fetch_assoc( $resPics ) )
	{
		?>
			<div class="group_gallery_pic" style="">
				<a href="<?="{$site['groups_gallery']}{$arrPic['groupID']}_{$arrPic['ID']}_{$arrPic['seed']}.{$arrPic['ext']}"?>"
				  title="<?=_t('_Uploaded by').' '.htmlspecialchars_adv($arrPic['NickName'])?>"
				  onclick="window.open(this.href, '_blank', 'width=<?=$arrPic['width']+20 ?>,height=<?=$arrPic['height']+20 ?>');return false;">
					<img src="<?="{$site['groups_gallery']}{$arrPic['groupID']}_{$arrPic['ID']}_{$arrPic['seed']}_.{$arrPic['ext']}"?>"
					  width="<?=$arrPic['width_']?>" height="<?=$arrPic['height_']?>"/>
				</a>
		<?php
		if( $arrGroup['thumb'] != $arrPic['ID'] and $arrGroup['creatorID'] == $memberID )
		{
			?>
				<br />
				<a href="<?="{$site['url']}group_actions.php?ID=$groupID&amp;a=def&img={$arrPic['ID']}"?>" class="group_set_thumb"><?=_t('_Set as thumbnail')?></a>
			<?php
		}
		
		if( $arrGroup['creatorID'] == $memberID or $arrPic['by'] == $memberID )
		{
			?>
				<br />
				<a href="<?="{$site['url']}group_actions.php?ID=$groupID&amp;a=delimg&img={$arrPic['ID']}"?>" class="group_set_thumb" onclick="return confirm('<?=_t('_Are you sure want to delete this image?')?>');"><?=_t('_Delete image')?></a>
			<?php
		}
		?>
			</div>
		<?php
	}
	?>
			<div class="clear_both"></div>
		</div>
	<?php
	if( ( (int)$arrGroup['members_post_images'] and isGroupMember( $memberID, $groupID ) ) or $arrGroup['creatorID'] == $memberID )
	{
		?>
		<a href="<?="{$site['url']}group_actions.php?a=upload&ID=$groupID"?>" class="actions"><?=_t('_Upload image')?></a>
		<?php
	}
	
	return ob_get_clean();
}

?>