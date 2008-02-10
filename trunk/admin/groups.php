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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'groups.inc.php' );

$logged['admin'] = member_auth( 1 );

$_page['header'] = 'Groups';
$_page['css_name'] = 'groups.css';
$actionMsg = '';

switch( $_REQUEST['action'] )
{
	case 'add_cat':
		$categName = trim( $_POST['Categ_Name'] );
		
		if( $categName )
		{
			db_res( "INSERT INTO `GroupsCateg` SET `Name`='".process_db_input($categName)."'" );
			$newID = mysql_insert_id();
			if( $newID )
				$actionMsg .= 'Added succesfully';
			else
				$actionMsg .= 'Some error occured while adding';
		}
		else
			$actionMsg .= 'Please enter category name';
	break;
	case 'del_cat':
		$catID = (int)$_GET['cat'];
		if( $catID )
		{
			db_res( "DELETE FROM `GroupsCateg` WHERE `ID`=$catID" );
			if( mysql_affected_rows() )
				$actionMsg .= 'Deleted succesfully';
			else
				$actionMsg .= 'Couldn\'t delete';
		}
	break;
	case 'edit_cat':
		$catID = (int)$_REQUEST['cat'];
		$name = trim( $_REQUEST['name'] );
		
		if( $catID and $name )
		{
			db_res( "UPDATE `GroupsCateg` SET `Name`='".process_db_input($name)."' WHERE `ID`=$catID" );
			if( mysql_affected_rows() )
				$actionMsg .= 'Renamed succesfully';
			else
				$actionMsg .= 'Error while renaming';
		}
	break;
	case 'suspend_group':
		$groupID = (int)$_REQUEST['group'];
		
		if( $groupID )
		{
			db_res( "UPDATE `Groups` SET `status`='Suspended' WHERE `ID`=$groupID" );
		}
	break;
	case 'activate_group':
		$groupID = (int)$_REQUEST['group'];
		
		if( $groupID )
		{
			db_res( "UPDATE `Groups` SET `status`='Active' WHERE `ID`=$groupID" );
		}
	break;
}

TopCodeAdmin();
ContentBlockHead("Groups Categories");
?>
			<div style="color:green;margin:5px auto;">
				<?=$actionMsg?>
			</div>
			
			<table style="width:350px;margin:5px auto;border-collapse:collapse;">
<?php
$arrCategs = getGroupsCategList();

$tr = 'odd';
		
foreach( $arrCategs as $arrCateg )
{
	?>
				<tr style="background:#<?=( $tr == 'odd' ? 'DDD' : 'FFF' )?>;">
					<td style="border:1px solid gray;padding:3px;">
						<a href="<?="{$_SERVER['PHP_SELF']}?view_cat={$arrCateg['ID']}#view_cat"?>"><b><?=htmlspecialchars_adv($arrCateg['Name'])?></b></a>
						(<?=$arrCateg['groupsCount']?> groups)
					</td>
					<td style="border:1px solid gray;padding:3px;width:16px;">
						<a href="javascript:void(0);" onclick="if( name = prompt('Enter new name', '<?=addslashes(htmlspecialchars($arrCateg['Name']))?>') ) { document.location='<?=$_SERVER['PHP_SELF']?>?action=edit_cat&amp;cat=<?=$arrCateg['ID']?>&amp;name='+encodeURIComponent(name);} return false;" title="edit"><img src="images/edit.gif" alt="edit" /></a>
					</td>
					<td style="border:1px solid gray;padding:3px;width:14px;">
						<a href="<?=$_SERVER['PHP_SELF']?>?action=del_cat&amp;cat=<?=$arrCateg['ID']?>" title="delete" onclick="return confirm('Are you sure want to delete this image?');"><img src="images/delete.gif" alt="delete" /></a>
					</td>
				</tr>
	<?php
	$tr = ( $tr == 'odd' ? 'even' : 'odd' );
}
?>
			</table>
			<div style="width:300px;margin:5px auto;padding:3px;border:1px solid silver;text-align:center;background-color:#EEE;">
				<b>Add new category:</b>
				<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
					<input type="hidden" name="action" value="add_cat" />
					<input type="text" name="Categ_Name" style="width:200px;" />
					<input type="submit" value="Add" />
				</form>
			</div>
<?php
ContentBlockFoot();


// if selected category - view it's groups.
if( $_REQUEST['view_cat'] )
{
	$cat = (int)$_REQUEST['view_cat'];
	
	$aCateg = db_arr( "SELECT * FROM `GroupsCateg` WHERE `ID`=$cat" );
	if( $aCateg['ID'] )
	{
		?><a name="view_cat"></a><?
		ContentBlockHead( "Groups of category &quot;" . htmlspecialchars_adv($aCateg['Name']) . "&quot;" );
		?><div style="text-align:right;"><a href="#">Top</a></div><?
		
		
		$aGroupsNum = db_arr( "SELECT COUNT(*) FROM `Groups` WHERE `categID`=$cat" );
		$iGroupsNum = (int)$aGroupsNum[0];
		if( $iGroupsNum ) // $iGroupsNum > 0
		{
			$resPerPage = 10;
			$pagesNum = ceil( $iGroupsNum / $resPerPage );
			$page = (int)$_REQUEST['page'];
			
			if( $page < 1 )
				$page = 1;
			if( $page > $pagesNum )
				$page = $pagesNum;
			
			$sqlFrom = ( ( $page - 1 ) * $resPerPage );
			
			$sQuery = "
				SELECT
					`Groups`.*,
					COUNT( `GroupsMembers`.`memberID` ) AS `membersCount`,
					`GroupsGallery`.`seed`,
					`GroupsGallery`.`ext` AS `thumbExt`
				FROM `Groups`
				LEFT JOIN `GroupsMembers`
					ON (`GroupsMembers`.`groupID` = `Groups`.`ID` AND `GroupsMembers`.`status`='Active')
				LEFT JOIN `GroupsGallery`
					ON (`Groups`.`thumb` = `GroupsGallery`.`ID`)
				WHERE `categID`=$cat
				GROUP BY `Groups`.`ID` DESC
				LIMIT $sqlFrom, $resPerPage
				";
			
			$resGroups = db_res( $sQuery );
			
			$numOnPage = mysql_num_rows( $resGroups );
			$showingFrom = $sqlFrom + 1;
			$showingTo   = $sqlFrom + $numOnPage;
			
			$showingResults = "Showing results: <b>$showingFrom</b> - <b>$showingTo</b> of <b>$iGroupsNum</b>";
			
			if( $pagesNum > 1 )
			{
				$pagesUrl = "{$_SERVER['PHP_SELF']}?view_cat=$cat&amp;page={page}#view_cat";
				$pagination = genPagination( $pagesNum, $page, $pagesUrl );
			}
			else
				$pagination = '';
			
			$sRowTmpl = file_get_contents("{$dir['root']}admin/group_searchrow.html");
			$date_format_php = getParam('php_date_format');
			
			?><div style="text-align:center;"><?=$pagination?></div><?
			?><div style="text-align:center;"><?=$showingResults?></div><?
			
			?>
				<div style="border:1px solid #CCC;margin:5px;position:relative;padding:3px;">
			<?php
			while( $arrGroup = mysql_fetch_assoc( $resGroups ) )
			{
				$aRowTmpl = array();
				
				if ( $arrGroup['thumb'] and file_exists(BX_DIRECTORY_PATH_GROUPS_GALLERY . "{$arrGroup['ID']}_{$arrGroup['thumb']}_{$arrGroup['seed']}_.{$arrGroup['thumbExt']}" ) )
					$groupImageUrl = "{$site['groups_gallery']}{$arrGroup['ID']}_{$arrGroup['thumb']}_{$arrGroup['seed']}_.{$arrGroup['thumbExt']}";
				else
					$groupImageUrl = "{$site['groups_gallery']}no_pic.gif";
				
				
				if( (int)$arrGroup['hidden_group'] )
					$typeHelp = 7;
				else
					if( (int)$arrGroup['open_join'] )
						$typeHelp = 5;
					else
						$typeHelp = 6;
				
				$typeHelpLink = "{$site['url']}groups_help.php?i=$typeHelp";
				
				if( $arrGroup['status'] == 'Active' )
				{
					$statusAct = 'suspend_group';
					$statusActTitle = 'Suspend';
				}
				else
				{
					$statusAct = 'activate_group';
					$statusActTitle = 'Activate';
				}
				
				$aRowTmpl['group_type_help'] = '<a href="'.$typeHelpLink.'" target="_blank" onclick="window.open(this.href,\'helpwin\',\'width=350,height=200\');return false;" >Help</a>';
				
				$aRowTmpl['thumbnail']     = "<div class=\"group_thumb\"><a href=\"{$site['url']}group.php?ID={$arrGroup['ID']}\"><img src=\"{$groupImageUrl}\" /></a></div>";
				$aRowTmpl['group_name']    = "<a href=\"{$site['url']}group.php?ID={$arrGroup['ID']}\">".htmlspecialchars_adv( $arrGroup['Name'] )."</a>";
				$aRowTmpl['group_about']   = htmlspecialchars_adv( $arrGroup['About'] );
				$aRowTmpl['members_count'] = $arrGroup['membersCount'];
				$aRowTmpl['created']       = date( $date_format_php, strtotime( $arrGroup['created'] ) );
				$aRowTmpl['group_type']    = ( ( (int)$arrGroup['open_join'] and !(int)$arrGroup['hidden_group'] ) ? 'Public group' : 'Private group' );
				$aRowTmpl['country']       = $prof['countries'][ $arrGroup['Country'] ];
				$aRowTmpl['city']          = htmlspecialchars_adv( $arrGroup['City'] );
				$aRowTmpl['status']        = $arrGroup['status'];
				$aRowTmpl['status_color']  = ( $arrGroup['status'] == 'Active' ? 'green' : 'red' );
				
				$aRowTmpl['status_action'] = "<a href=\"{$_SERVER['PHP_SELF']}?action=$statusAct&amp;group={$arrGroup['ID']}&amp;view_cat=$cat&amp;page=$page#view_cat\" onclick=\"return confirm('Are you sure want to $statusActTitle this group?');\">$statusActTitle</a>";
				
				
				$sRow = $sRowTmpl;
				foreach( $aRowTmpl as $what => $to )
					$sRow = str_replace( "__{$what}__", $to, $sRow );
				
				echo $sRow;
			}
			?>
				</div>
			<?
			
			?><div style="text-align:center;"><?=$showingResults?></div><?
			?><div style="text-align:center;"><?=$pagination?></div><?
		}
		else
		{
			?>
				<div style="text-align:center;font-weight:bold;padding:20px;">
					Sorry, no groups found in this category
				</div>
			<?
		}
		
		?><div style="text-align:right;"><a href="#">Top</a></div><?
		ContentBlockFoot();
	}
}
BottomCode();

?>