<?php

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

/*
 * Page for displaying and editing profile fields.
 */

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'languages.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'menu.inc.php' );

// Check if administrator is logged in.  If not display login form.
$logged['admin'] = member_auth( 1 );


$_page['header']   = 'Menu Builder';
$_page['css_name'] = 'menu_compose.css';



if( $_REQUEST['action'] )
{
	switch( $_REQUEST['action'] )
	{
		case 'edit_form':
			$id = (int)$_REQUEST['id'];
			
			$aItem = db_assoc_arr( "SELECT * FROM `TopMenu` WHERE `ID` = $id", 0 );
			if( $aItem )
				showEditForm( $aItem );
			else
				echoMenuEditMsg( 'Error', 'red' );
		exit;
		case 'create_item':
			$newID = createNewElement( $_GET['type'], (int)$_GET['source'] );
			echo $newID;
		exit;
		case 'deactivate_item':
			$res = db_res( "UPDATE `TopMenu` SET `Active`=0 WHERE `ID`=" . (int)$_GET['id'] );
			echo mysql_affected_rows();
			compileMenus();
		exit;
		case 'save_item':
			$id = (int)$_GET['id'];
			if( !$id )
			{
				echoMenuEditMsg( 'Error', 'red' );
				exit;
			}
			
			$aItemFields = array( 'Name', 'Caption', 'Link', 'Target' );
			$aItem = array();
			foreach( $aItemFields as $field )
				$aItem[$field] = $_GET[$field];
			
			$aVis = array();
			if( (int)$_GET['Visible_non'] )
				$aVis[] = 'non';
			if( (int)$_GET['Visible_memb'] )
				$aVis[] = 'memb';
			
			$aItem['Visible'] = implode( ',', $aVis );
			$res = saveItem( $id, $aItem );
			updateLangFile( $_GET['Caption'], $_GET['LangCaption'] );
			echo $res;
		exit;
		case 'delete_item':
			$id = (int)$_GET['id'];
			if( !$id )
			{
				echo 'Item ID is not specified';
				exit;
			}
			
			$aItem = db_arr( "SELECT `Deletable` FROM `TopMenu` WHERE `ID` = $id" );
			if( !$aItem )
			{
				echo 'Item not found';
				exit;
			}
			
			if( !(int)$aItem['Deletable'] )
			{
				echo 'Item is non-deletable';
				exit;
			}
			
			db_res( "DELETE FROM `TopMenu` WHERE `ID` = $id" );
			if( mysql_affected_rows() )
				echo 'OK';
			else
				echo 'Couldn\'t delete the item';
			compileMenus();
		exit;
		case 'save_orders':
			$sTop = $_GET['top'];
			$aCustom = $_GET['custom'];
			saveOrders( $sTop, $aCustom );
			echo 'OK';
		exit;
	}
}


$sTopQuery = "SELECT `ID`, `Name` FROM `TopMenu` WHERE `Active`=1 AND `Type`='top' ORDER BY `Order`";
$rTopItems = db_res( $sTopQuery );

$sSysQuery = "SELECT `ID`, `Name` FROM `TopMenu` WHERE `Active`=1 AND `Type`='system' ORDER BY `Order`";
$rSysItems = db_res( $sSysQuery );

$sAllQuery = "SELECT `ID`, `Name` FROM `TopMenu` WHERE `Type`!='system'";
$rAllItems = db_res( $sAllQuery );

$sComposerInit = "
	<script type=\"text/javascript\">
		topParentID = 'menu_app_wrapper';
		urlIconLoading = '{$site['url_admin']}images/loading.gif';
		parserUrl = '{$_SERVER['PHP_SELF']}';
		
		allowNewItem = true;
		allowAddToTop = true;
		iInactivePerRow = 7;
		sendSystemOrder = false;
		
		aCoords = new Array();
		aCoords['startX'] = 275;
		aCoords['startY'] = 200;
		aCoords['width']  = 92;
		aCoords['height'] = 21;
		aCoords['diffX']  = 122;
		aCoords['diffY']  = 32;
		
		aTopItems = new Array();
		aCustomItems = new Array();
		aSystemItems = new Array();
		aAllItems = new Array();
";
		
while( $aTopItem = mysql_fetch_assoc( $rTopItems ) )
{
	$sComposerInit .= "
		
		aTopItems[{$aTopItem['ID']}] = '" . addslashes( $aTopItem['Name'] ) . "';
		aCustomItems[{$aTopItem['ID']}] = new Array();";
	$sQuery = "SELECT `ID`, `Name` FROM `TopMenu` WHERE `Active`=1 AND `Type`='custom' AND `Parent`={$aTopItem['ID']} ORDER BY `Order`";
	
	$rCustomItems = db_res( $sQuery );
	while( $aCustomItem = mysql_fetch_assoc( $rCustomItems ) )
	{
		$sComposerInit .= "
		aCustomItems[{$aTopItem['ID']}][{$aCustomItem['ID']}] = '" . addslashes( $aCustomItem['Name'] ) . "';";
	}
}

while( $aSystemItem = mysql_fetch_assoc( $rSysItems ) )
{
	$sComposerInit .= "
		
		aSystemItems[{$aSystemItem['ID']}] = '" . addslashes( $aSystemItem['Name'] ) . "';
		aCustomItems[{$aSystemItem['ID']}] = new Array();";
	$sQuery = "SELECT `ID`, `Name` FROM `TopMenu` WHERE `Active`=1 AND `Type`='custom' AND `Parent`={$aSystemItem['ID']} ORDER BY `Order`";
	
	$rCustomItems = db_res( $sQuery );
	while( $aCustomItem = mysql_fetch_assoc( $rCustomItems ) )
	{
		$sComposerInit .= "
		aCustomItems[{$aSystemItem['ID']}][{$aCustomItem['ID']}] = '" . addslashes( $aCustomItem['Name'] ) . "';";
	}
}

$sComposerInit .= "\n";
while( $aAllItem = mysql_fetch_assoc( $rAllItems ) )
{
	$sComposerInit .= "
		aAllItems[{$aAllItem['ID']}] = '" . addslashes( $aAllItem['Name'] ) . "';";
}
	$sComposerInit .= "
	</script>
";


$_page['extraCodeInHead'] = <<<EOJ
	$sComposerInit
	<script type="text/javascript" src="{$site['url']}inc/js/classes/BxDolMenu.js"></script>
	<script type="text/javascript" src="menu_compose.js"></script>
EOJ;


$_page['extraCodeInBody'] = <<<EOJ
	<div id="edit_form_wrapper" style="display:none;" onclick="e = event; t = ( e.target || e.srcElement ); if ( t.id == this.id ) hideEditForm();">
		<div id="edit_form_cont"></div>
	</div>
	<div id="menu_app_wrapper"></div>
EOJ;

TopCodeAdmin();
	?>
	<div class="pseudo_wrapper">
		<div class="pseudo_head">Active Items</div>
		<div id="pseudo1">
			<img src="<?= $site['url_admin'] . 'images/loading.gif' ?>" />
		</div>
		<div class="pseudo_head">All Items</div>
		<div id="pseudo2">
			<img src="<?= $site['url_admin'] . 'images/loading.gif' ?>" />
		</div>
	</div>
	<?
BottomCode();



function showEditForm( $aItem )
{
	if( !$aItem['Editable'] )
		$disabled = 'disabled="disabled"';
	else
		$disabled = '';
	
	?>
<form name="formItemEdit" id="formItemEdit">
	<table class="popup_form_wrapper">
		<tr>
			<td class="corner"><img src="images/op_cor_tl.png" /></td>
			<td class="side_ver"><img src="images/spacer.gif" /></td>
			<td class="corner"><img src="images/op_cor_tr.png" /></td>
		</tr>
		<tr>
			<td class="side"><img src="images/spacer.gif" /></td>
			
			<td class="container">
				<div class="edit_item_table_cont">
				
					<table class="edit_item_table" id="tmp_id_name" >
						<tr>
							<td class="form_label">System Name:</td>
							<td>
								<input type="text" class="form_input_text" name="Name" value="<?= $aItem['Name'] ?>" <?=$disabled?> />
							</td>
						</tr>
						<tr>
							<td class="form_label">Language Key:</td>
							<td>
								<input type="text" class="form_input_text" name="Caption" value="<?= $aItem['Caption'] ?>" <?=$disabled?> />
							</td>
						</tr>
						<tr>
							<td class="form_label">Default Name:</td>
							<td>
								<input type="text" class="form_input_text" name="LangCaption" value="<?= _t( $aItem['Caption'] ) ?>" <?=$disabled?> />
							</td>
						</tr>
						<tr>
							<td class="form_label">URL:</td>
							<td>
								<input type="text" class="form_input_text" name="Link" value="<?= htmlspecialchars_adv( $aItem['Link'] ) ?>" <?=$disabled?> />
							</td>
						</tr>
						<tr>
							<td class="form_label">Target Window:</td>
							<td>
								<input type="radio" name="Target" value=""       <?= ( ( $aItem['Target'] == ''       ) ? 'checked="checked"' : '' ) ?> <?=$disabled?> /> Same
								<input type="radio" name="Target" value="_blank" <?= ( ( $aItem['Target'] == '_blank' ) ? 'checked="checked"' : '' ) ?> <?=$disabled?> /> New
							</td>
						</tr>
						<tr>
							<td class="form_label">Visible for:</td>
							<td>
								<input type="checkbox" name="Visible_non"  value="on" <?= ( ( strpos( $aItem['Visible'], 'non'  ) === false ) ? '' : 'checked="checked"' ) ?> <?=$disabled?> /> Guest
								<input type="checkbox" name="Visible_memb" value="on" <?= ( ( strpos( $aItem['Visible'], 'memb' ) === false ) ? '' : 'checked="checked"' ) ?> <?=$disabled?> /> Member
							</td>
						</tr>
						<tr>
							<td class="form_colspan" colspan="2">
								<? if( $aItem['Editable'] ){ ?><input type="button" value="Save Changes" onclick="saveItem(<?= $aItem['ID'] ?>);" /><? } ?>
								<input type="button" onclick="hideEditForm();" value="Cancel" />
								<? if( $aItem['Deletable'] ){ ?><input type="button" value="Delete" onclick="deleteItem(<?= $aItem['ID'] ?>);" /><? } ?>
							</td>
					</table>
				
				</div>
			</td>
			
			<td class="side"><img src="images/spacer.gif" /></td>
		</tr>
		<tr>
			<td class="corner"><img src="images/op_cor_bl.png" /></td>
			<td class="side_ver"><img src="images/spacer.gif" /></td>
			<td class="corner"><img src="images/op_cor_br.png" onload="if( navigator.appName == 'Microsoft Internet Explorer' && version >= 5.5 && version < 7 ) png_fix();" /></td>
		</tr>
	</table>
</form>
	<?
}

function createNewElement( $type, $source )
{
	if( $source )
	{
		$sourceActive = db_value( "SELECT `Active` FROM `TopMenu` WHERE `ID`=$source" );
		if( !$sourceActive )
		{
			//convert to active
			db_res( "UPDATE `TopMenu` SET `Active`=1, `Type`='$type' WHERE `ID`=$source" );
			$newID = $source;
		}
		else
		{
			//create from source
			db_res( "INSERT INTO `TopMenu`
						( `Name`, `Caption`, `Link`, `Visible`, `Target`, `Onclick`, `Check`, `Type` )
					SELECT
						  `Name`, `Caption`, `Link`, `Visible`, `Target`, `Onclick`, `Check`, '$type'
					FROM `TopMenu`
					WHERE `ID`=$source" );
			$newID = mysql_insert_id();
		}
	}
	else
	{
		//create new
		db_res( "INSERT INTO `TopMenu` ( `Name`, `Type` ) VALUES ( 'NEW ITEM', '$type' )" );
		$newID = mysql_insert_id();
	}
	
	compileMenus();
	return $newID;
}

function echoMenuEditMsg( $text, $color = 'black' )
{
	?>
		<div onclick="hideEditForm();" style="color:<?= $color ?>;text-align:center;"><?= $text ?></div>
		<script type="text/javascript">setTimeout( 'hideEditForm();', 1000 )</script>
	<?
}

function saveItem( $id, $aItem )
{
	$aOldItem = db_arr( "SELECT * FROM `TopMenu` WHERE `ID`=$id" );
	
	if( !$aOldItem )
		return echoMenuEditMsg( 'Error. Item not found', 'red' );
	
	if( !(bool)(int)$aOldItem['Editable'] )
		return echoMenuEditMsg( 'Error. Item is non-editable', 'red' );
	
	$sQuerySet = '';
	foreach( $aItem as $field => $value )
		$sQuerySet .= ", `$field`='" . process_db_input( $value ) ."'";
	
	$sQuerySet = substr( $sQuerySet, 1 );
	
	$sQuery = "UPDATE `TopMenu` SET $sQuerySet WHERE `ID` = $id";
	
	db_res( $sQuery );
	compileMenus();
	
	$ret = echoMenuEditMsg( 'Saved', 'green' );
	$ret .= '<script type="text/javascript">updateItem( ' . $id . ', \'' . process_db_input( $aItem['Name'] ) . '\' );</script>';
	return $ret;
}

function updateLangFile( $key, $string )
{
	$langName = getParam( 'lang_default' );
	$langID = db_value( "SELECT `ID` FROM `LocalizationLanguages` WHERE `Name` = '" . addslashes( $langName ) . "'" );
	
	$keyID = db_value( "SELECT `ID` FROM `LocalizationKeys` WHERE `Key` = '" . process_db_input( $key ) . "'" );
	if( $keyID )
	{
		db_res( "UPDATE `LocalizationStrings` SET `String` = '" .process_db_input( $string ) . "' WHERE `IDKey`=$keyID AND `IDLanguage`=$langID" );
	}
	else
	{
		db_res( "INSERT INTO `LocalizationKeys` SET `IDCategory` = 2, `Key` = '" . process_db_input( $key ) . "'" );
		db_res( "INSERT INTO `LocalizationStrings` SET `IDKey` = " . mysql_insert_id() . ", `IDLanguage` = $langID, `String` = '" .process_db_input( $string ) . "'" );
	}
	
	compileLanguage($langID);
}

function saveOrders( $sTop, $aCustom )
{
	db_res( "UPDATE `TopMenu` SET `Order` = 0, `Parent` = 0" );
	
	$sTop = trim( $sTop, ' ,' );
	$aTopIDs = explode( ',', $sTop );
	foreach( $aTopIDs as $iOrd => $iID )
	{
		$iID = trim( $iID, ' ,' );
		$iID = (int)$iID;
		
		if( !$iID )
			continue;
		
		db_res( "UPDATE `TopMenu` SET `Order` = $iOrd, `Type` = 'top' WHERE `ID` = $iID" );
	}
	
	foreach( $aCustom as $iParent => $sCustom )
	{
		$iParent = (int)$iParent;
		$sCustom = trim( $sCustom, ' ,' );
		$aCustomIDs = explode( ',', $sCustom );
		foreach( $aCustomIDs as $iOrd => $iID )
		{
			$iID = trim( $iID, ' ,' );
			$iID = (int)$iID;
			
			if( !$iID )
				continue;
			
			db_res( "UPDATE `TopMenu` SET `Order` = $iOrd, `Type` = 'custom', `Parent`=$iParent WHERE `ID` = $iID" );
		}
	}
	compileMenus();
}

?>