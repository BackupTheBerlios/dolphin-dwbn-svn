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


$_page['header']   = 'Admin Menu Builder';
$_page['css_name'] = 'menu_compose.css';



if( $_REQUEST['action'] )
{
	switch( $_REQUEST['action'] )
	{
		case 'edit_form':
			$id = (int)$_REQUEST['id'];
			
			if( $id < 1000 )
			{
				$aItem = db_assoc_arr( "SELECT * FROM `AdminMenu` WHERE `ID` = $id", 0 );
				if( $aItem )
					showEditFormCustom( $aItem );
				else
					echoMenuEditMsg( 'Error', 'red' );
			}
			else
			{
				$id = $id - 1000;
				$aItem = db_assoc_arr( "SELECT * FROM `AdminMenuCateg` WHERE `ID` = $id", 0 );
				if( $aItem )
					showEditFormTop( $aItem );
				else
					echoMenuEditMsg( 'Error', 'red' );
			}
		exit;
		case 'create_item':
			$newID = createNewElement( $_GET['type'], (int)$_GET['source'] );
			echo $newID;
		exit;
		case 'deactivate_item':
			$id = (int)$_GET['id'];
			if( $id > 1000 )
			{
				$id = $id - 1000;
				db_res( "DELETE FROM `AdminMenuCateg` WHERE `ID`=$id" );
				echo mysql_affected_rows();
			}
			else
				echo 1;
		exit;
		case 'save_item':
			$id = (int)$_GET['id'];
			if( !$id )
			{
				echoMenuEditMsg( 'Error', 'red' );
				exit;
			}
			
			if( $id < 1000 )
			{
				$aItemFields = array( 'Title', 'Url', 'Desc', 'Check', 'Icon' );
				$aItem = array();
				foreach( $aItemFields as $field )
					$aItem[$field] = $_GET[$field];
				
				$res = saveItemCustom( $id, $aItem );
				echo $res;
			}
			else
			{
				$id = $id - 1000;
				$aItemFields = array( 'Title', 'Icon', 'Icon_thumb', 'User' );
				$aItem = array();
				foreach( $aItemFields as $field )
					$aItem[$field] = $_GET[$field];
				
				$res = saveItemTop( $id, $aItem );
				echo $res;
			}
		exit;
		case 'delete_item':
			$id = (int)$_GET['id'];
			if( !$id )
			{
				echo 'Item ID is not specified';
				exit;
			}
			
			if( $id > 1000 )
			{
				$id = $id - 1000;
				
				db_res( "DELETE FROM `AdminMenuCateg` WHERE `ID` = $id" );
			}
			else
			{
				db_res( "DELETE FROM `AdminMenu` WHERE `ID` = $id" );
			}
			
			if( mysql_affected_rows() )
				echo 'OK';
			else
				echo 'Couldn\'t delete the item';
		exit;
		case 'save_orders':
			$sTop = $_GET['top'];
			$aCustom = $_GET['custom'];
			saveOrders( $sTop, $aCustom );
			echo 'OK';
		exit;
	}
}


$sTopQuery = "SELECT `ID`, `Title` FROM `AdminMenuCateg` ORDER BY `User`, `Order`";
$rTopItems = db_res( $sTopQuery );

$sAllQuery = "SELECT `ID`, `Title` FROM `AdminMenu`";
$rAllItems = db_res( $sAllQuery );

$sAllTopQuery = "SELECT `ID`, `Title` FROM `AdminMenuCateg`";
$rAllTopItems = db_res( $sAllTopQuery );

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
		
		aTopItems[" . ( $aTopItem['ID'] + 1000 ) . "] = '{$aTopItem['Title']}';
		aCustomItems[" . ( $aTopItem['ID'] + 1000 ) . "] = new Array();";
	$sQuery = "SELECT `ID`, `Title` FROM `AdminMenu` WHERE `Categ`={$aTopItem['ID']} ORDER BY `Order`";
	
	$rCustomItems = db_res( $sQuery );
	while( $aCustomItem = mysql_fetch_assoc( $rCustomItems ) )
	{
		$sComposerInit .= "
		aCustomItems[" . ( $aTopItem['ID'] + 1000 ) . "][{$aCustomItem['ID']}] = '{$aCustomItem['Title']}';";
	}
}

$sComposerInit .= "\n";
while( $aAllTopItem = mysql_fetch_assoc( $rAllTopItems ) )
{
	$sComposerInit .= "
		aAllItems[" . ( $aAllTopItem['ID'] + 1000 ) . "] = '{$aAllTopItem['Title']}';";
}

$sComposerInit .= "\n";
while( $aAllItem = mysql_fetch_assoc( $rAllItems ) )
{
	$sComposerInit .= "
		aAllItems[{$aAllItem['ID']}] = '{$aAllItem['Title']}';";
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



function showEditFormCustom( $aItem )
{
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
							<td class="form_label">Title:</td>
							<td>
								<input type="text" class="form_input_text" name="Title" value="<?= $aItem['Title'] ?>" />
							</td>
						</tr>
						<tr>
							<td class="form_label">Url:</td>
							<td>
								<input type="text" class="form_input_text" name="Url" value="<?= $aItem['Url'] ?>" />
							</td>
						</tr>
						<tr>
							<td class="form_label">Check:</td>
							<td>
								<input type="text" class="form_input_text" name="Check" value="<?= htmlspecialchars_adv( $aItem['Check'] ) ?>" />
							</td>
						</tr>
						<tr>
							<td class="form_label">Description:</td>
							<td>
								<textarea name="Desc" class="form_input_area"><?= htmlspecialchars_adv( $aItem['Desc'] ) ?></textarea>
							</td>
						</tr>
						<tr>
							<td class="form_label">Icon:</td>
							<td>
								<input type="text" class="form_input_text" name="Icon" value="<?= $aItem['Icon'] ?>" />
							</td>
						</tr>
						<tr>
							<td class="form_colspan" colspan="2">
								<input type="button" value="Save Changes" onclick="saveItem(<?= $aItem['ID'] ?>);" />
								<input type="button" value="Cancel" onclick="hideEditForm();" />
								<input type="button" value="Delete" onclick="deleteItem(<?= $aItem['ID'] ?>);" />
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


function showEditFormTop( $aItem )
{
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
							<td class="form_label">Title:</td>
							<td>
								<input type="text" class="form_input_text" name="Title" value="<?= $aItem['Title'] ?>" />
							</td>
						</tr>
						<tr>
							<td class="form_label">Big Icon:</td>
							<td>
								<input type="text" class="form_input_text" name="Icon" value="<?= $aItem['Icon'] ?>" />
							</td>
						</tr>
						<tr>
							<td class="form_label">Small Icon:</td>
							<td>
								<input type="text" class="form_input_text" name="Icon_thumb" value="<?= $aItem['Icon_thumb'] ?>" />
							</td>
						</tr>
						<tr>
							<td class="form_label">User:</td>
							<td>
								<select class="form_input_select" name="User">
								<?
								foreach( array( 'admin', 'moderator', 'aff' ) as $sUser )
								{
									echo "<option value=\"$sUser\" ";
									if( $sUser == $aItem['User'] )
										echo 'selected="selected"';
									echo ">$sUser</option>";
								}
								?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="form_colspan" colspan="2">
								<input type="button" value="Save Changes" onclick="saveItem(<?= $aItem['ID'] + 1000 ?>);" />
								<input type="button" value="Cancel" onclick="hideEditForm();" />
								<input type="button" value="Delete" onclick="deleteItem(<?= $aItem['ID'] ?>);" />
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
		if( $type == 'top' and $source > 1000 )
		{
			$source = $source - 1000;
			
			db_res( "
				INSERT INTO `AdminMenuCateg`
					( `Title`, `Icon`, `Icon`, `Icon_thumb`, `User` )
				SELECT
					  `Title`, `Icon`, `Icon`, `Icon_thumb`, `User`
				FROM `AdminMenuCateg`
				WHERE `ID` = $source
				" );
			
			$newID = mysql_insert_id();
		}
		elseif( $type == 'custom' and $source < 1000 )
		{
			$aItem = db_res( "SELECT * FROM `AdminMenu` WHERE `ID` = $source" );
			
			if( $aItem['Categ'] == 0 )
				$newID = $source;
			else
			{
				db_res( "
					INSERT INTO `AdminMenu`
						( `Title`, `Url`, `Desc`, `Check`, `Icon` )
					SELECT
						  `Title`, `Url`, `Desc`, `Check`, `Icon`
					FROM `AdminMenu`
					WHERE `ID` = $source
					" );
				
				$newID = mysql_insert_id();
			}
		}
		elseif( $type == 'custom' and $source > 1000 )
		{
			$source = $source - 1000;
			
			db_res( "
				INSERT INTO `AdminMenu`
					( `Title` )
				SELECT
					  `Title`
				FROM `AdminMenuCateg`
				WHERE `ID` = $source
				" );
			
			$newID = mysql_insert_id();
		}
		elseif( $type == 'top' and $source < 1000 )
		{
			db_res( "
				INSERT INTO `AdminMenuCateg`
					( `Title` )
				SELECT
					  `Title`
				FROM `AdminMenu`
				WHERE `ID` = $source
				" );
			
			$newID = mysql_insert_id();
		}
	}
	else
	{
		if( $type == 'top' )
			db_res( "INSERT INTO `AdminMenuCateg` SET `Title` = 'NEW ITEM'" );
		elseif( $type == 'custom' )
			db_res( "INSERT INTO `AdminMenu` SET `Title` = 'NEW ITEM'" );
			
		$newID = mysql_insert_id();
	}
	
	return $newID;
}

function echoMenuEditMsg( $text, $color = 'black' )
{
	?>
		<div onclick="hideEditForm();" style="color:<?= $color ?>;text-align:center;"><?= $text ?></div>
	<?
}

function saveItemCustom( $id, $aItem )
{
	$aOldItem = db_arr( "SELECT * FROM `AdminMenu` WHERE `ID`=$id" );
	
	if( !$aOldItem )
		return echoMenuEditMsg( 'Error. Item not found', 'red' );
	
	$sQuerySet = '';
	foreach( $aItem as $field => $value )
		$sQuerySet .= ", `$field`='" . process_db_input( $value ) ."'";
	
	$sQuerySet = substr( $sQuerySet, 1 );
	
	$sQuery = "UPDATE `AdminMenu` SET $sQuerySet WHERE `ID` = $id";
	
	db_res( $sQuery );
	
	$ret = echoMenuEditMsg( 'Save succesfull', 'green' );
	$ret .= '<script type="text/javascript">updateItem( ' . $id . ', \'' . process_db_input( $aItem['Title'] ) . '\' );</script>';
	return $ret;
}

function saveItemTop( $id, $aItem )
{
	$aOldItem = db_arr( "SELECT * FROM `AdminMenuCateg` WHERE `ID`=$id" );
	
	if( !$aOldItem )
		return echoMenuEditMsg( 'Error. Item not found', 'red' );
	
	$sQuerySet = '';
	foreach( $aItem as $field => $value )
		$sQuerySet .= ", `$field`='" . process_db_input( $value ) ."'";
	
	$sQuerySet = substr( $sQuerySet, 1 );
	
	$sQuery = "UPDATE `AdminMenuCateg` SET $sQuerySet WHERE `ID` = $id";
	$res .= $sQuery;
	db_res( $sQuery );
	
	$ret = echoMenuEditMsg( 'Save succesfull', 'green' );
	$ret .= '<script type="text/javascript">updateItem( ' . ( $id + 1000 ) . ', \'' . process_db_input( $aItem['Title'] ) . '\' );</script>';
	return $ret;
}



function saveOrders( $sTop, $aCustom )
{
	db_res( "UPDATE `AdminMenuCateg` SET `Order` = 0" );
	db_res( "UPDATE `AdminMenu` SET `Order` = 0, `Categ` = 0" );
	
	$sTop = trim( $sTop, ' ,' );
	$aTopIDs = explode( ',', $sTop );
	foreach( $aTopIDs as $iOrd => $iID )
	{
		$iID = trim( $iID, ' ,' );
		$iID = (int)$iID;
		
		if( !$iID )
			continue;
		
		$iID = $iID - 1000;
		
		db_res( "UPDATE `AdminMenuCateg` SET `Order` = $iOrd WHERE `ID` = $iID" );
	}
	
	foreach( $aCustom as $iParent => $sCustom )
	{
		$iParent = (int)$iParent;
		$iParent = $iParent - 1000;
		
		$sCustom = trim( $sCustom, ' ,' );
		$aCustomIDs = explode( ',', $sCustom );
		
		foreach( $aCustomIDs as $iOrd => $iID )
		{
			$iID = trim( $iID, ' ,' );
			$iID = (int)$iID;
			
			if( !$iID )
				continue;
			
			db_res( "UPDATE `AdminMenu` SET `Order` = $iOrd, `Categ`=$iParent WHERE `ID` = $iID" );
		}
	}
}

?>