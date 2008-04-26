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

require_once( 'header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$dirGroups = 'groups/';

define( 'BX_DIRECTORY_PATH_GROUPS', BX_DIRECTORY_PATH_ROOT . $dirGroups );
define( 'BX_DIRECTORY_PATH_GROUPS_GALLERY', BX_DIRECTORY_PATH_GROUPS . "gallery/" );

$site['groups']         = "{$site['url']}{$dirGroups}";
$site['groups_gallery'] = "{$site['groups']}gallery/";

function showMyGroups( $memberID )
{
	global $site;
	
	$memberID = (int)$memberID;
	if ( !$memberID )
		return false;
	
	$arrGroups = getMyGroups( $memberID );
	
	ob_start();
	if ( !$arrGroups )
	{
		?>
			<div class="mygroups_no"><?=_t("_No my groups found")?></div>
		<?php
	}
	else
	{
		?>
			<div class="mygroups_container">
				<div class="clear_both"></div>
		<?php
		foreach ( $arrGroups as $arrGroup )
		{
			$groupID = $arrGroup['ID'];
			$groupUrl = "{$site['url']}group.php?ID=$groupID";
			
			if ( $arrGroup['thumb'] and file_exists(BX_DIRECTORY_PATH_GROUPS_GALLERY . "{$arrGroup['ID']}_{$arrGroup['thumb']}_{$arrGroup['seed']}_.{$arrGroup['thumbExt']}" ) )
				$fileGroupThumb = "{$site['groups_gallery']}{$arrGroup['ID']}_{$arrGroup['thumb']}_{$arrGroup['seed']}_.{$arrGroup['thumbExt']}";
			else
				$fileGroupThumb = "{$site['groups_gallery']}no_pic.gif";
			$sSpacerPath = $site['url'].'templates/base/images/icons/spacer.gif';
			$sGrpImg = <<<EOF
<img class="photo1" alt="{$arrGroup['Name']}" src="{$sSpacerPath}" style="width: 110px; height: 110px; background-image: url({$fileGroupThumb});"/>
EOF;
			?>
				<div class="mygroup_container">
					<div class="mygroup_name">
						<a href="<?=$groupUrl?>" class="actions">
							<?=htmlspecialchars_adv( $arrGroup['Name'] )?>
						</a>
					</div>
					<div class="thumbnail_block">
						<a href="<?=$groupUrl?>">
							<?=$sGrpImg?>
						</a>
					</div>
			<?php
			if ( (int)$arrGroup['isCreator'] )
			{
				?>
					<div class="mygroup_leader_is"><?=_t("_group creator") ?></div>
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
	}
	
	return ob_get_clean();
}

function getMyGroups( $memberID )
{
	$memberID = (int)$memberID;
	if ( !$memberID )
		return null;
	
	$query = "
		SELECT
			`Groups`.`ID`,
			`Groups`.`Name`,
			IF( `Groups`.`creatorID` = $memberID, 1, 0 ) AS `isCreator`,
			`Groups`.`thumb`,
			`GroupsGallery`.`seed`,
			`GroupsGallery`.`ext` AS `thumbExt`
			
		FROM `GroupsMembers`, `Groups`
		LEFT JOIN `GroupsGallery`
		ON `Groups`.`thumb` = `GroupsGallery`.`ID`
		
		WHERE 
			`GroupsMembers`.`memberID` = $memberID AND
			`GroupsMembers`.`groupID`  = `Groups`.`ID` AND
			`GroupsMembers`.`status`   = 'Active'
		";
	
	$resGroups = db_res( $query );
	
	if ( !$resGroups or !mysql_num_rows( $resGroups ) )
		return null;
	
	$arrGroups = array();
	
	while ( $arrGroup = mysql_fetch_assoc( $resGroups ) )
	{
		$groupID = $arrGroup['ID'];
		$arrGroups[ $groupID ] = $arrGroup;
	}
	
	return $arrGroups;
}

function getGroupInfo( $groupID )
{
	$groupID = (int)$groupID;
	if ( !$groupID )
		return null;
	
	$query = "
		SELECT
			`Groups`.*,
			`GroupsCateg`.`Name` AS `categName`,
			COUNT( `GroupsMembers`.`memberID` )  AS  `membersCount`,
			`GroupsGallery`.`ext`  AS  `thumbExt`,
			`GroupsGallery`.`seed`
		FROM `Groups`
		INNER JOIN `GroupsCateg` ON
			`GroupsCateg`.`ID` = `Groups`.`categID`
		LEFT JOIN `GroupsGallery` ON
			`Groups`.`thumb` = `GroupsGallery`.`ID`
		LEFT JOIN `GroupsMembers` ON
			`GroupsMembers`.`groupID` = `Groups`.`ID` AND
			`GroupsMembers`.`status` = 'Active'
		WHERE
			`Groups`.`ID` = $groupID
		GROUP BY `Groups`.`ID`
		";
	
	$arrGroup = db_assoc_arr( $query );
	return $arrGroup;
}

function isGroupNameExists( $Name )
{
	$res = db_res( "SELECT `Name` FROM `Groups` WHERE UPPER(`Name`)='" . addslashes(strtoupper($Name)) . "' LIMIT 1" );
	
	if( $res and mysql_num_rows( $res ) )
		return true;
	else
		return false;
}

function isGroupMember( $memberID, $groupID, $checkActiveStatus = true )
{
	/*global $aMemStatusCache;
	
	if( !is_array( $aMemStatusCache ) )
	{
		$aMemStatusCache = array();
		$rStatus = db_res( "SELECT * FROM `GroupsMembers` ORDER BY `groupID`,`memberID`" );
		while( $aStatus = mysql_fetch_assoc( $rStatus ) )
			$aMemStatusCache[$aStatus['groupID']][$aStatus['memberID']] = $aStatus['status'];
	}*/
	
	$memberID = (int)$memberID;
	$groupID = (int)$groupID;
	
	if ( !$memberID or !$groupID )
		return false;
	
	$query = "SELECT `Status` FROM `GroupsMembers`
		WHERE `memberID` = $memberID AND `groupID` = $groupID" .
		( $checkActiveStatus ? " AND `Status`='Active'" : '' );
	
	$res = db_res( $query );
	if ( $res and mysql_num_rows( $res ) )
		return true;
	else
		return false;
	
	/*if( isset( $aMemStatusCache[$groupID][$memberID] ) )
		if( $checkActiveStatus )
			if( $aMemStatusCache[$groupID][$memberID] == 'Active' )
				return true;
			else
				return false;
		else
			return true;*/
	
	//return false;
	
	//echo "isMemberResults: .$result. .$result1.";
	
}

function getDefaultGroupEditArr()
{
	$arr = array(
		'Name' => array(
			'Name' => 'Name',
			'Caption' => 'Group name',
			'Type' => 'text',
			'Len' => 64
		),
		'categID' => array(
			'Name' => 'categID',
			'Caption' => 'Category',
			'Type' => 'dropdown'
		),
		'open_join' => array(
			'Name' => 'open_join',
			'Caption' => 'Open join',
			'Type' => 'bool',
			'Value' => true,
			'HelpIndex' => 1
		),
		'hidden_group' => array(
			'Name' => 'hidden_group',
			'Caption' => 'Hidden group',
			'Type' => 'bool',
			'Value' => false,
			'HelpIndex' => 2
		),
		'members_post_images' => array(
			'Name' => 'members_post_images',
			'Caption' => 'Members can post images',
			'Type' => 'bool',
			'Value' => true,
			'HelpIndex' => 3
		),
		'members_invite' => array(
			'Name' => 'members_invite',
			'Caption' => 'Members can invite',
			'Type' => 'bool',
			'Value' => true,
			'HelpIndex' => 4
		),
		'Country' => array(
			'Name' => 'Country',
			'Caption' => 'Country',
			'Type' => 'dropdown'
		),
		'City' => array(
			'Name' => 'City',
			'Caption' => 'City',
			'Type' => 'text',
			'Len' => 64
		),
		'About' => array(
			'Name' => 'About',
			'Caption' => 'About group',
			'Type' => 'text',
			'Len' => 255
		),
		'Desc' => array(
			'Name' => 'Desc',
			'Caption' => 'Group description',
			'Type' => 'html'
		)
	);
	
	return $arr;
		
}

function genGroupsDropdown( $arrField, $showChoose = true )
{
	global $prof;
	$res = <<<EOJ
		<select name="{$arrField['Name']}" class="group_edit_dropdown">
EOJ;
	switch ( $arrField['Name'] )
	{
		case 'Country':
			$arrVals = $prof['countries'];
			foreach ( $arrVals as $key => $val )
				$arrVals[$key] = htmlspecialchars_adv( _t( "__{$val}" ) );
		break;
		case 'categID':
			$arrVals = array();
			$resVals = db_res( "SELECT * FROM `GroupsCateg` ORDER BY `Name`" );
			while ( $arrVal = mysql_fetch_assoc( $resVals ) )
				$arrVals[ $arrVal['ID'] ] = htmlspecialchars_adv( $arrVal['Name'] );
		break;
	}
	
	$res .= '<option value="" '. ( $arrField['Value'] ? '' : 'selected="selected"' ) .
	  '>' . _t("_Choose") . "</option>\n";
	
	
	foreach ( $arrVals as $Val => $Opt )
	{
		$isSel = ( $arrField['Value'] == $Val ? 'selected="selected"' : '' );
		$res .= "<option value=\"$Val\" $isSel>$Opt</option>\n";
	}
	
	$res .= "</select>";
	
	return $res;
}

function genGroupEditForm( $arrGroup, $arrErr = false, $showSImg = false, $groupID = 0 )
{
	ob_start();
	?>
	<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
		<table class="group_edit_table">
	<?php
	if ( $groupID )
		{
			?>
			<input type="hidden" name="ID" value="<?=$groupID?>" />
			<?php
		}
	
	$checked = 'checked="checked"';
	$nowTR = "odd";
	
	foreach ( $arrGroup as $arrField )
	{
		?>
			<tr class="group_edit_tr_<?=$nowTR?>">
				<td class="group_edit_td_label"><?=_t( '_'.$arrField['Caption'] )?>:</td>
				<td class="group_edit_td_value">
					<div class="group_edit_error"
					  style="display:<?=($arrErr[$arrField['Name']] ? 'block' : 'none')?>">
						<?=($arrErr[$arrField['Name']] ? _t( '_'.$arrErr[$arrField['Name']] ) : '' )?>
					</div>
		<?php
		switch( $arrField['Type'] )
		{
			case 'text':
				?>
					<input type="text" name="<?=$arrField['Name']?>" class="group_edit_text" value="<?=htmlspecialchars_adv($arrField['Value']);?>" maxlength="<?=$arrField['Len']?>" />
				<?php
			break;
			case 'bool':
				?>
					<input type="radio" name="<?=$arrField['Name']?>" value="yes" id="<?=$arrField['Name']?>_yes" <?=$arrField['Value'] ? $checked : ''?>>
					<label for="<?=$arrField['Name']?>_yes"><?=_t('_Yes')?></label>
					&nbsp;
					<input type="radio" name="<?=$arrField['Name']?>" value="no"  id="<?=$arrField['Name']?>_no" <?=$arrField['Value'] ? '' : $checked?>>
					<label for="<?=$arrField['Name']?>_no"><?=_t('_No')?></label>
				<?php
				if( $arrField['HelpIndex'] )
				{
					?>
					&nbsp; <span class="group_help_link">(<a href="<?="{$site['url']}groups_help.php?i={$arrField['HelpIndex']}"?>" target="_blank" onclick="window.open(this.href,'helpwin','width=350,height=200');return false;" ><?=_t('_help')?></a>)</span>
					<?php
				}
			break;
			case 'html':
				?>
					<textarea name="<?=$arrField['Name']?>" class="group_edit_html"><?=htmlspecialchars_adv($arrField['Value'])?></textarea>
				<?php
			break;
			case 'dropdown':
				echo genGroupsDropdown( $arrField );
			break;
		}
		?>
				</td>
			</tr>
		<?php
		
		$nowTR = ($nowTR == "odd") ? "even" : "odd";
	}
	
	if ( $showSImg )
	{
		?>
			<tr class="group_edit_tr_<?=$nowTR?>">
				<td class="group_edit_td_label"><?=_t( '_Enter what you see:' )?></td>
				<td class="group_edit_td_value" style="text-align:center;">
					<div class="group_edit_error"
					  style="display:<?=($arrErr['simg'] ? 'block' : 'none')?>">
						<?=($arrErr['simg'] ? _t( '_'.$arrErr['simg'] ) : '' )?>
					</div>
					<img src="<?=$site['url']?>simg/simg.php"><br />
					<input type="input" name="simg" class="group_edit_simg" maxlength="6" />
				</td>
			</tr>
		<?php
		$nowTR = ($nowTR == "odd") ? "even" : "odd";
	}
	?>
			<tr class="group_edit_tr_<?=$nowTR?>">
				<td class="group_edit_td_label">&nbsp;</td>
				<td class="group_edit_td_colspan">
					<input type="submit" name="do_submit" value="<?=_t('_Submit')?>" />
				</td>
			</tr>
		</table>
	</form>
	<?php
	
	return ob_get_clean();
}

function fillGroupArrByPostValues( &$arrGroup )
{
	foreach( $arrGroup as $fieldName => $arrField )
	{
		switch( $arrField['Type'] )
		{
			case 'text':
			case 'dropdown':
				$arrGroup[$fieldName]['Value'] = trim( process_pass_data( $_POST[$fieldName] ) );
			break;
			case 'html':
				$arrGroup[$fieldName]['Value'] = clear_xss( trim( process_pass_data( $_POST[$fieldName] ) ) );
			break;
			case 'bool':
				$arrGroup[$fieldName]['Value'] = (bool)( $_POST[$fieldName] == 'yes' );
			break;
		}
	}
}

function fillGroupArrByDBValues( &$arrFields, $arrGroup )
{
	foreach( $arrFields as $fieldName => $arrField )
	{
		switch( $arrField['Type'] )
		{
			case 'text':
			case 'html':
			case 'dropdown':
				$arrFields[$fieldName]['Value'] = $arrGroup[$fieldName];
			break;
			case 'bool':
				$arrFields[$fieldName]['Value'] = (bool)(int)$arrGroup[$fieldName];
			break;
		}
	}
}

function checkGroupErrors( &$arrGroup )
{
	global $prof;
	$arrErr = array();
	
	foreach( $arrGroup as $arrField )
	{
		$fieldName = $arrField['Name'];
		
		switch( $arrField['Type'] )
		{
			case 'text':
				if( !strlen( $arrGroup[$fieldName]['Value'] ) )
					$arrErr[ $fieldName ] = "{$fieldName} is required";
				else
				{
					if( $fieldName == 'Name' )
						if( isGroupNameExists( $arrGroup['Name']['Value'] ) )
							$arrErr[ $fieldName ] = "Group name already exists";
				}
			break;
			case 'dropdown':
				switch( $fieldName )
				{
					case 'Country':
						$arrGroup['Country']['Value'] = substr( $arrGroup['Country']['Value'], 0, 2 );
						if( !strlen( $arrGroup['Country']['Value'] ) )
							$arrErr['Country'] = 'Country is required';
						else
							if ( !isset( $prof['countries'][ $arrGroup['Country']['Value'] ] ) )
							{
								$arrErr['Country'] = "Country doesn't exists";
								unset( $arrGroup['Country']['Value'] );
							}
					break;
					case 'categID':
						$arrGroup['categID']['Value'] = (int)$arrGroup['categID']['Value'];
						if( !$arrGroup['categID']['Value'] )
							$arrErr['categID'] = "Category is required";
						else
							if( !isGroupsCategExists( $arrGroup['categID']['Value'] ) )
							{
								$arrErr['categID'] = "Category doesn't exists";
								unset( $arrGroup['categID']['Value'] );
							}
					break;
				}
			break;
			case 'html':
				//Commented for possible modifications
				/*if( !strlen( $arrGroup[$fieldName]['Value'] ) )
					$arrErr[ $fieldName ] = "{$fieldName} is required";*/
			break;
			case 'bool':
				
			break;
		}
	}
	
	return $arrErr;
}

function isGroupsCategExists( $ID )
{
	$ID = (int)$ID;
	
	if( !$ID )
		return false;
	
	$res = db_res( "SELECT `ID` FROM `GroupsCateg` WHERE `ID`=$ID" );
	
	if( $res and mysql_num_rows( $res ) )
		return true;
	else
		return false;
}

function saveGroup( $arrGroup, $groupID = 0 )
{
	$groupID = (int)$groupID;
	$sqlSetStr = 'SET ';
	
	foreach( $arrGroup as $fieldName => $arrField )
	{
		switch( $arrField['Type'] )
		{
			case 'text':
			case 'html':
			case 'dropdown':
				$setValue = addslashes( $arrField['Value'] );
			break;
			case 'bool':
				$setValue = (string)(int)$arrField['Value']; //convert true -> 1, false -> 0
			break;
			default:
				$setValue = addslashes( $arrField['Value'] );
		}
		$sqlSetStr .= "`{$arrField['Name']}`='$setValue', ";
	}
	$sqlSetStr = substr( $sqlSetStr, 0, -2 ); // remove last ", "
	
	if ( $groupID > 0 )
	{
		$query = "UPDATE `Groups` $sqlSetStr WHERE `ID`=$groupID";
		db_res( $query );
		if( mysql_affected_rows() )
		{
			saveGroupForum( $groupID, $arrGroup );
			return true;
		}
		else
			return false;
	}
	else
	{
		$query = "INSERT `Groups` $sqlSetStr, `created`=NOW()";
		db_res( $query );
		$groupID = mysql_insert_id();
		if( $groupID )
		{
			saveGroupForum( $groupID, $arrGroup, true );
			return $groupID;
		}
		else
			return false;
	}
}

function saveGroupForum( $groupID, $arrGroup, $isNew = false )
{
	$groupId = (int)$groupID;
	
	$sqlSetStr = '';
	
	foreach( $arrGroup as $fieldName => $arrField )
	{
		unset( $setValue );
		
		if( $fieldName == 'hidden_group' )
		{
			$setColumn = "forum_type";
			$setValue  = ( $arrField['Value'] ) ? 'private' : 'public';
		}
		elseif( $fieldName == 'Name' )
		{
			$setColumn = 'forum_title';
			$setValue  = addslashes( htmlspecialchars( $arrField['Value'] ) );
		}
		elseif( $fieldName == 'About' )
		{
			$setColumn = 'forum_desc';
			$setValue  = addslashes( htmlspecialchars( $arrField['Value'] ) );
		}
		
		if( isset( $setValue ) )
			$sqlSetStr .= "`{$setColumn}` = '{$setValue}', ";
	}
	
	if( !strlen( $sqlSetStr ) )
		return false;
	
	$sqlSetStr = "SET " . substr( $sqlSetStr, 0, -2 ); // remove last ", "
	
	if ( $isNew )
		$query = "INSERT `grp_forum` $sqlSetStr, `forum_id`=$groupID, `cat_id`=1";
	else
		$query = "UPDATE `grp_forum` $sqlSetStr WHERE `forum_id`=$groupID";
	
	db_res( $query );
}

function addMember2Group( $memberID, $groupID, $status = 'Active' )
{
	db_res( "INSERT `GroupsMembers` VALUES ( $memberID, $groupID, '$status', NOW() )" );
}

function resignGroupMember( $memberID, $groupID )
{
	db_res( "DELETE FROM `GroupsMembers` WHERE `memberID`=$memberID AND `groupID`=$groupID" );
}

function compareUpdatedGroupFields( $arrOldFields, $arrNewFields )
{
	$arrUpdFields = array();
	
	foreach( $arrOldFields as $fieldName => $arrOldField )
	{
		if( $arrOldField['Value'] != $arrNewFields[$fieldName]['Value'] )
			$arrUpdFields[$fieldName] = $arrNewFields[$fieldName];
	}
	
	return $arrUpdFields;
}

function genUploadForm( $groupID, $back_home = false, $set_def = false )
{
	global $site;
	
	ob_start();
	?>
		<div class="group_upload_form">
			<form action="<?=$site['url']?>group_actions.php" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="ID" value="<?=$groupID?>" />
				<input type="hidden" name="a" value="upload" />
	<?php
	if( $back_home )
	{
		?>
				<input type="hidden" name="back" value="home" />
		<?php
	}
	
	if( $set_def )
	{
		?>
				<input type="hidden" name="set_def" value="yes" />
		<?php
	}
	?>
				<?=_t( '_Select file' )?><br />
				<input type="file" name="file" />
				<input type="submit" name="do_submit" value="<?=_t('_Submit')?>" />
			</form>
		</div>
	<?php
	return ob_get_clean();
}

function setGroupThumb( $groupID, $img )
{
	$groupID = (int)$groupID;
	$img = (int)$img;
	
	if( $groupID and $img )
	{
		$arrImg = db_assoc_arr( "SELECT `ID` FROM `GroupsGallery` WHERE `groupID`=$groupID AND `ID`=$img" );
		if( $arrImg['ID'] == $img )
		{
			db_res( "UPDATE `Groups` SET `thumb`=$img WHERE `ID`=$groupID" );
		}
	}
}

function deleteGroupImage( $groupID, $img )
{
	$groupID = (int)$groupID;
	$img = (int)$img;
	
	if( $groupID and $img )
	{
		$arrImg = db_assoc_arr( "SELECT * FROM `GroupsGallery` WHERE `groupID`=$groupID AND `ID`=$img" );
		if( $arrImg['ID'] == $img )
		{
			db_res( "DELETE FROM `GroupsGallery` WHERE `ID`=$img AND `groupID`=$groupID" );
			unlink( BX_DIRECTORY_PATH_GROUPS_GALLERY . "{$groupID}_{$img}_{$arrImg['seed']}_.{$arrImg['ext']}" );
			unlink( BX_DIRECTORY_PATH_GROUPS_GALLERY . "{$groupID}_{$img}_{$arrImg['seed']}.{$arrImg['ext']}" );
		}
	}
}

function getGroupsCategList( $sOrderBy = 'ID' )
{
	$resCategs = db_res( "
		SELECT
			`GroupsCateg`.*,
			COUNT(`Groups`.`ID`) AS `groupsCount`
		FROM `GroupsCateg`
		LEFT JOIN `Groups`
		ON ( `Groups`.`categID` = `GroupsCateg`.`ID` AND `Groups`.`status` = 'Active' )
		GROUP BY `GroupsCateg`.`ID`
		ORDER BY `GroupsCateg`.`$sOrderBy`
		" );
	$arrCategs = fill_assoc_array( $resCategs );
	return $arrCategs;
}

function sendRequestToCreator( $groupID, $memberID )
{
	global $site;
	
	$subject = 'Group join request';
	$msg = getParam( 'group_creator_request' );
	
	$queryInfo = "
		SELECT
			`Groups`.`Name` AS `group`,
			`Profiles`.`ID` AS `creatorID`,
			`Profiles`.`NickName` AS `creator`,
			`Profiles2`.`NickName` AS `member`
		FROM `Groups`
		LEFT JOIN `Profiles`
		ON `Profiles`.`ID` = `Groups`.`creatorID`
		LEFT JOIN `Profiles` AS `Profiles2`
		ON `Profiles2`.`ID` = $memberID
		WHERE
			`Groups`.`ID` = $groupID
		";
	
	$arrInfo = db_arr( $queryInfo );
	
	$creatorID = $arrInfo['creatorID'];
	
	$group   = htmlspecialchars_adv( $arrInfo['group'] );
	$creator = htmlspecialchars_adv( $arrInfo['creator'] );
	$member  = htmlspecialchars_adv( $arrInfo['member'] );
	
	$member  = "<a href=\"{$site['url']}$member\">$member</a>";
	
	$approve = "<a href=\"{$site['url']}group_actions.php?a=approve&amp;ID=$groupID&amp;mem=$memberID\" >approve</a>";
	$reject  = "<a href=\"{$site['url']}group_actions.php?a=reject&amp;ID=$groupID&amp;mem=$memberID\" >reject</a>";
	
	$msg = str_replace( '{group}',   $group,   $msg );
	$msg = str_replace( '{creator}', $creator, $msg );
	$msg = str_replace( '{member}',  $member,  $msg );
	$msg = str_replace( '{approve}', $approve, $msg );
	$msg = str_replace( '{reject}',  $reject,  $msg );
	
	$msg = addslashes( $msg );
	
	db_res( "INSERT INTO `Messages`
		( `Date`, `Sender`, `Recipient`, `Text`, `Subject`, `New` )
		VALUES
		( NOW(), $memberID, $creatorID, '$msg', '$subject', '1' )" );
}

function genAllCategsList()
{
	global $site;
	
	$ret = '';
	
	$arrCategs = getGroupsCategList( 'Name' );
	
	foreach( $arrCategs as $arrCateg )
	{
		$ret .= '<div class="groups_category"><span class="groups_categ_name">' .
		  "<a href=\"{$site['url']}groups_browse.php?categID={$arrCateg['ID']}&amp;nf=1\">" .
		  htmlspecialchars_adv( $arrCateg['Name'] ) . '</a></span> '.
		  '<span class="groups_categ_info">(' .
		  _t('_groups count',$arrCateg['groupsCount']) .
		  ")</span></div>\n";
	}
	return $ret;
}

function PageCompGroupsSearchResults( $keyword, $searchby, $categID, $Country, $City, $sortby, $isTopGroupsPage = false )
{
	global $oTemplConfig;
	global $site;
	global $dir;
	global $tmpl;
	global $prof;
	
	$date_format_php = getParam('php_date_format');
	
	if( $sortby == 'created' or $sortby == 'membersCount' )
		$sortOrder = 'DESC';
	else
		$sortOrder = 'ASC';
	
	$aQueryWhere = array(); //array will contain search conditions combined by AND
	
	if( $keyword )
	{
		if( $searchby == 'name' )
			$aQueryWhere[] = "UPPER(`Groups`.`Name`) LIKE '%{$keyword}%'";
		else
			$aQueryWhere[] = "(UPPER(`Groups`.`Name`) LIKE '%{$keyword}%') OR (UPPER(`Groups`.`About`) LIKE '%{$keyword}%') OR (UPPER(`Groups`.`Desc`) LIKE '%{$keyword}%')";
	}
	
	if( $categID )
		$aQueryWhere[] = "`Groups`.`categID`='$categID'";
	
	if( $Country )
		$aQueryWhere[] = "`Groups`.`Country`='$Country'";
	
	if( $City )
		$aQueryWhere[] = "UPPER(`Groups`.`City`) LIKE '%{$City}%'";
	
	$aQueryWhere[] = "`Groups`.`status` = 'Active'";
	
	$sQueryWhere = "WHERE (" . implode( ") AND (", $aQueryWhere ) . ")";
	
	if( $isTopGroupsPage )
		$SRdbTitle = _t( '_Top Groups' );
	else
		$SRdbTitle = _t( '_Groups search results' ); //SearchResultDesignBoxTitle
	
	$arrNum = db_arr( "SELECT COUNT(`ID`) FROM `Groups` $sQueryWhere" );
	
	$totalNum = (int)$arrNum[0];
	if( $totalNum > 0 )
	{
		$resPerPage = $oTemplConfig -> iGroupsSearchResPerPage;
		$pagesNum = ceil( $totalNum / $resPerPage );
		$page = (int)$_REQUEST['page'];
		
		if( $page < 1 )
			$page = 1;
		if( $page > $pagesNum )
			$page = $pagesNum;
		
		$sqlFrom = ( ( $page - 1 ) * $resPerPage );
		
		$sQuery = "
			SELECT
				`Groups`.*,
				`GroupsCateg`.`Name` AS `categName`,
				COUNT( `GroupsMembers`.`memberID` ) AS `membersCount`,
				`GroupsGallery`.`seed`,
				`GroupsGallery`.`ext` AS `thumbExt`
			FROM `Groups`
			INNER JOIN `GroupsCateg` ON `GroupsCateg`.`ID` = `Groups`.`categID`
			LEFT JOIN `GroupsMembers`
				ON (`GroupsMembers`.`groupID` = `Groups`.`ID` AND `GroupsMembers`.`status`='Active')
			LEFT JOIN `GroupsGallery`
				ON (`Groups`.`thumb` = `GroupsGallery`.`ID`)
			$sQueryWhere
			GROUP BY `Groups`.`ID`
			ORDER BY `$sortby` $sortOrder, `Groups`.`ID` DESC
			LIMIT $sqlFrom, $resPerPage
			";
		
		$resGroups = db_res( $sQuery );
		
		$numOnPage = mysql_num_rows( $resGroups );
		$showingFrom = $sqlFrom + 1;
		$showingTo   = $sqlFrom + $numOnPage;
		
		$showingResults = _t( '_Showing results:', $showingFrom, $showingTo, $totalNum );
		
		if( $pagesNum > 1 and !$isTopGroupsPage )
		{
			$pagesUrl = "javascript:void(0);";
			$pagesOnclick = "switchGroupsSearchPage({page}); return false;";
			$pagination = genPagination( $pagesNum, $page, $pagesUrl, $pagesOnclick );
		}
		
		$sRowTmpl = file_get_contents("{$dir['root']}templates/tmpl_$tmpl/searchrow_group.html");
		
		$breadCrumbs = '';
		if( $categID )
		{
			$arrCateg = db_arr( "SELECT `Name` FROM `GroupsCateg` WHERE `ID`=$categID" );
			if( $arrCateg['Name'] )
			{
				$sCategName = _t( '_Category' ).': '.htmlspecialchars_adv( $arrCateg['Name'] );
				$SRdbTitle = $sCategName;
				
				$bcd = getParam( 'breadCrampDivider' );
				$breadCrumbs = <<<EOJ
		<div class="groups_breadcrumbs">
			<a href="{$site['url']}">{$site['title']}</a> $bcd
			<a href="{$site['url']}groups_home.php">__Groups__</a> $bcd
			<span class="active_link">$sCategName</span>
		</div>
EOJ;
				$breadCrumbs = str_replace( "__Groups__", _t( "_Groups" ), $breadCrumbs );
			}
		}
		
		ob_start();
		
		if( !$isTopGroupsPage )
		{
			echo $breadCrumbs;
			?>
			<div class="groups_showing_results">
				<?=$showingResults?>
			</div>
			<div class="groups_pagination">
				<?=$pagination?>
			</div>
			<?
		}
		?>
		<div class="groups_result_wrapper">
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
			
			$aRowTmpl['group_name_l']    = _t( '_Group name' );
			$aRowTmpl['category_l']      = _t( '_Category' );
			$aRowTmpl['about_group_l']   = _t( '_About group' );
			$aRowTmpl['members_count_l'] = _t( '_Members count' );
			$aRowTmpl['created_l']       = _t( '_Created' );
			$aRowTmpl['group_type_l']    = _t( '_Group type' );
			$aRowTmpl['location_l']      = _t( '_Location' );
			$aRowTmpl['group_type_help'] = '<a href="'.$typeHelpLink.'" target="_blank" onclick="window.open(this.href,\'helpwin\',\'width=350,height=200\');return false;" >'._t( "_help" ).'</a>';

			$sSpacerPath = 'templates/base/images/icons/spacer.gif';
			$sSpacerName = $site['url'].$sSpacerPath;
			$aRowTmpl['thumbnail']     = "<!--<div class=\"group_thumb\">--><a href=\"{$site['url']}group.php?ID={$arrGroup['ID']}\">
											<!--<img src=\"{$groupImageUrl}\" />-->
											<img src=\"{$sSpacerName}\" style=\"width:110px;height:110px; background-image: url({$groupImageUrl});\" class=\"photo1\"/>
											</a><!--</div>-->";
			$aRowTmpl['group_name']    = "<a class=\"actions\" href=\"{$site['url']}group.php?ID={$arrGroup['ID']}\">".htmlspecialchars_adv( $arrGroup['Name'] )."</a>";
			$aRowTmpl['group_about']   = htmlspecialchars_adv( $arrGroup['About'] );
			$aRowTmpl['category']      = "<a href=\"{$site['url']}groups_browse.php?categID={$arrGroup['categID']}\">".htmlspecialchars_adv( $arrGroup['categName'] )."</a>";
			$aRowTmpl['members_count'] = $arrGroup['membersCount'];
			$aRowTmpl['created']       = date( $date_format_php, strtotime( $arrGroup['created'] ) );
			$aRowTmpl['group_type']    = _t( ( ( (int)$arrGroup['open_join'] and !(int)$arrGroup['hidden_group'] ) ? '_Public group' : '_Private group' ) );
			$aRowTmpl['country']       = _t( '__'.$prof['countries'][ $arrGroup['Country'] ] );
			$aRowTmpl['city']          = htmlspecialchars_adv( $arrGroup['City'] );
			
			$sRow = $sRowTmpl;
			foreach( $aRowTmpl as $what => $to )
				$sRow = str_replace( "__{$what}__", $to, $sRow );
			
			echo $sRow;
		}
		?>
				<div class="clear_both"></div>
			</div>
		<?
		if( !$isTopGroupsPage )
		{
			?>
			<div class="groups_showing_results">
				<?=$showingResults?>
			</div>
			<div class="groups_pagination">
				<?=$pagination?>
			</div>
			<?php
		}
		$ret = ob_get_clean();
	}
	else
	{
		$ret = MsgBox(_t( '_Sorry, no groups found' ));
	}
	return DesignBoxContent( $SRdbTitle, $ret, $oTemplConfig->iGroupsSearchResults_dbnum );
}

?>