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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'languages.inc.php' );

// Check if administrator is logged in.  If not display login form.
$logged['admin'] = member_auth( 1, true, true );

$_page['css_name'] = 'pageCompose.css';

$sMyPage = $_REQUEST['page'];
switch( $sMyPage ) {
	case 'account':
		$_page['header'] = 'Account Page Builder';
		$sTableName = 'AccountCompose';
	break;
	
	case 'profile':
		$_page['header'] = 'Profile Page Builder';
		$sTableName = 'ProfileCompose';
	break;
	
	case 'photo':
		$_page['header'] = 'Photo File Page Builder';
		$sTableName = 'sharePhotoCompose';
	break;
	
	case 'music':
		$_page['header'] = 'Music File Page Builder';
		$sTableName = 'shareMusicCompose';
	break;
	
	case 'video':
		$_page['header'] = 'Video File Page Builder';
		$sTableName = 'shareVideoCompose';
	break;
	
	case 'ads':
		$_page['header'] = 'Classifieds Advertisement Page Builder';
		$sTableName = 'ClsAdvCompose';
	break;
	
	case 'index':
	default:
		$_page['header'] = 'Index Page Builder';
		$sTableName = 'IndexCompose';
		$sMyPage = 'index';
}

if( $_REQUEST['action'] )
{
	switch( $_REQUEST['action'] )
	{
		case 'edit_form':
			$id = (int)$_REQUEST['id'];
			
			$aItem = db_assoc_arr( "SELECT * FROM `$sTableName` WHERE `ID` = $id", 0 );

			if( $aItem )
			{
				$aItem['Deletable'] = false;
				if( $aItem['Func'] == 'Echo' )
					$aItem['Deletable'] = true;
				elseif( $aItem['Func'] != 'PFBlock' ) {
					$iTypeNum = (int)db_value( "SELECT COUNT( * ) FROM `$sTableName` WHERE `Func` = '{$aItem['Func']}'" );
					if( $iTypeNum > 1 )
						$aItem['Deletable'] = true;
				}
				
				showEditForm( $aItem );
			}
			else
				echoMenuEditMsg( 'Error', 'red' );
		exit;
		
		case 'create_item':
			$newID = createNewElement( (int)$_GET['source'] );
			echo $newID;
		exit;
		
		case 'deactivate_item':
			echo "OK"; //moved it to Col 0
		exit;
		
		case 'save_item':
			$id = (int)$_POST['id'];
			if( !$id ) {
				echoMenuEditMsg( 'Error', 'red' );
				exit;
			}
			
			$aItemFields = array( 'Title', 'Caption', 'Content' );
			$aItem = array();
			foreach( $aItemFields as $field )
				$aItem[$field] = $_POST[$field];
			
			$aVis = array();
			if( (int)$_POST['Visible_non'] )
				$aVis[] = 'non';
			if( (int)$_POST['Visible_memb'] )
				$aVis[] = 'memb';
			
			if( isset( $_POST['Url'] ) and isset( $_POST['Num'] ) )
				$aItem['Content'] = $_POST['Url'] . '#' . (int)$_POST['Num'];
			
			
			$aItem['Visible'] = implode( ',', $aVis );
			$res = saveItem( $id, $aItem );
			updateLangFile( $_POST['Caption'], $_POST['LangCaption'] );
			echo $res;
		exit;
		
		case 'delete_item':
			$id = (int)$_GET['id'];
			echo deleteItem( $id );
		exit;
		
		case 'save_orders':
			$sTop = $_GET['top'];
			$aCustom = $_GET['custom'];
			saveOrders( $sTop, $aCustom );
			echo 'OK';
		exit;
		
		case 'reset':
			$res = execSqlFile( "{$site['url_admin']}builders_dfl/{$sTableName}.sql" );
			
			if( $res )
				header( "Location:{$_SERVER['PHP_SELF']}?page=$sMyPage" );
			else
				echo 'Sorry, some error occurred';
		exit;
	}
}


$sAllQuery = "SELECT `ID`, `Title` FROM `$sTableName`";
$rAllItems = db_res( $sAllQuery );

$sComposerInit = "
	<script type=\"text/javascript\">
		topParentID = 'menu_app_wrapper';
		urlIconLoading = '{$site['url_admin']}images/loading.gif';
		parserUrl = '{$_SERVER['PHP_SELF']}?page=$sMyPage';
		allowNewItem = true;
		allowAddToTop = false;
		iInactivePerRow = 4;
		sendSystemOrder = true;
		sNewItemTitle = 'NEW BLOCK';
		
		aCoords = new Array();
		aCoords['startX'] = 275;
		aCoords['startY'] = 200;
		aCoords['width']  = 117;
		aCoords['height'] = 21;
		aCoords['diffX']  = 145;
		aCoords['diffY']  = 32;
		
		aTopItems = new Array();
		aCustomItems = new Array();
		aSystemItems = new Array();
		aAllItems = new Array();
";
		
for( $iColumn = 1; $iColumn <= 2; $iColumn ++ ) {
	$sComposerInit .= "
		
		aSystemItems[100{$iColumn}] = '';
		aCustomItems[100{$iColumn}] = new Array();";
	
	$sQuery = "SELECT `ID`, `Title` FROM `$sTableName` WHERE `Column` = $iColumn ORDER BY `Order`";
	
	$rCustomItems = db_res( $sQuery );
	while( $aCustomItem = mysql_fetch_assoc( $rCustomItems ) )
	{
		$sComposerInit .= "
		aCustomItems[100{$iColumn}][{$aCustomItem['ID']}] = '" . addslashes( _t( $aCustomItem['Title'] ) ) . "';";
	}
}

$sComposerInit .= "\n";
while( $aAllItem = mysql_fetch_assoc( $rAllItems ) )
{
	$sComposerInit .= "
		aAllItems[{$aAllItem['ID']}] = '" . addslashes( _t( $aAllItem['Title'] ) ) . "';";
}

	$sComposerInit .= "
	</script>
";


$_page['extraCodeInHead'] = <<<EOJ
	$sComposerInit
	<script type="text/javascript" src="{$site['url']}inc/js/classes/BxDolMenu.js"></script>
	<script type="text/javascript" src="menu_compose.js"></script>
	
	<!-- tinyMCE gz -->
	<script type="text/javascript" src="{$site['plugins']}tiny_mce/tiny_mce_gzip.js"></script>
	<script type="text/javascript">
		tinyMCE_GZ.init({
			plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",
			themes : "simple,advanced",
			languages : "en",
			disk_cache : true,
			debug : false
		});
	</script>

	<script language="javascript" type="text/javascript">
		tinyMCE.init({
			mode : "textareas",
			theme : "advanced",
			
			editor_selector : "form_input_html",
			content_css : "{$site['plugins']}tiny_mce/dolphin.css",
			
			plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,visualchars,nonbreaking,xhtmlxtras",
			relative_urls : false,
			
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "forecolor,backcolor,|,bullist,numlist,|,outdent,indent,|,link,unlink,image,hr,|,sub,sup,|,insertdate,inserttime,|,styleprops",
			theme_advanced_buttons3 : "charmap,emotions,|,cite,abbr,acronym,attribs,|,preview,removeformat,|,code,help",
			theme_advanced_buttons4 : "table,row_props,cell_props,delete_col,delete_row,delete_table,col_after,col_before,row_after,row_before,row_after,row_before,split_cells,merge_cells",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "center",
			valid_elements : "*[*]"
		});
	</script>
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
		<div class="pseudo_head">Active items (<a href="javascript:void(0);" onclick="resetItems();return false;">Reset</a>)</div>
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
	?>
<form
  onsubmit="if( this.form_input_html ) tinyMCE.execCommand('mceRemoveControl', false, 'form_input_html'); saveItemByPost( <?= $aItem['ID'] ?> ); return false;"
  onreset="if( this.form_input_html ) tinyMCE.execCommand('mceRemoveControl', false, 'form_input_html'); hideEditForm(); return false;"
  name="formItemEdit" id="formItemEdit">
	<table class="popup_form_wrapper">
		<tr>
			<td class="corner"><img src="images/op_cor_tl.png" /></td>
			<td class="side_ver"><img src="images/spacer.gif" alt="" /></td>
			<td class="corner"><img src="images/op_cor_tr.png" /></td>
		</tr>
		<tr>
			<td class="side"><img src="images/spacer.gif" alt="" /></td>
			
			<td class="container">
				<div class="edit_item_table_cont">
				
					<table class="edit_item_table" id="tmp_id_name" >
						<tr>
							<td class="form_label">System Name:</td>
							<td>
								<input type="text" class="form_input_text" name="Title" value="<?= $aItem['Title'] ?>"
								  <?= $aItem['Func'] == 'PFBlock' ? 'readonly="readonly"' : '' ?> />
							</td>
						</tr>
						<tr>
							<td class="form_label">Description:</td>
							<td><?= $aItem['Desc'] ?></td>
						</tr>
						<tr>
							<td class="form_label">Language Key:</td>
							<td>
								<input type="text" class="form_input_text" name="Caption" value="<?= $aItem['Caption'] ?>"
								  <?= $aItem['Func'] == 'PFBlock' ? 'readonly="readonly"' : '' ?> />
							</td>
						</tr>
						<tr>
							<td class="form_label">Default Name:</td>
							<td>
								<input type="text" class="form_input_text" name="LangCaption" value="<?= _t( $aItem['Caption'] ) ?>" />
							</td>
						</tr>
						<tr>
							<td class="form_label">Visible for:</td>
							<td>
								<input type="checkbox" name="Visible_non"  value="on" <?= ( ( strpos( $aItem['Visible'], 'non'  ) === false ) ? '' : 'checked="checked"' ) ?> /> Guest
								<input type="checkbox" name="Visible_memb" value="on" <?= ( ( strpos( $aItem['Visible'], 'memb' ) === false ) ? '' : 'checked="checked"' ) ?> /> Member
							</td>
						</tr>
	<?
	if( $aItem['Func'] == 'Echo' )
	{
		?>
						<tr>
							<td class="form_label">HTML-content:</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class="form_colspan" colspan="2">
								<textarea class="form_input_html" id="form_input_html" name="Content"><?= htmlspecialchars_adv( $aItem['Content'] ) ?></textarea>
							</td>
						</tr>
		<?
	}
	elseif( $aItem['Func'] == 'RSS' )
	{
		list( $sUrl, $iNum ) = explode( '#', $aItem['Content'] );
		$iNum = (int)$iNum;
		
		?>
						<tr>
							<td class="form_label">Url of RSS feed:</td>
							<td><input type="text" class="form_input_text" name="Url" value="<?= $sUrl ?>" /></td>
						</tr>
						<tr>
							<td class="form_label">Number of RSS items (0 - all):</td>
							<td><input type="text" class="form_input_text" name="Num" value="<?= $iNum ?>" /></td>
						</tr>
		<?
	}
	?>
						<tr>
							<td class="form_colspan" colspan="2">
								<input type="submit" value="Save" />
	<?
	if( $aItem['Deletable'] )
	{
		?>
								<input type="button"
								  onclick="if( deleteItem( <?= $aItem['ID'] ?> ) && this.form.form_input_html ) tinyMCE.execCommand('mceRemoveControl', false, 'form_input_html');"
								  value="Delete" />
		<?
	}
	?>
								<input type="reset" value="Cancel" />
							</td>
						</tr>
					</table>
				
				</div>
			</td>
			
			<td class="side"><img src="images/spacer.gif" alt="" /></td>
		</tr>
		<tr>
			<td class="corner"><img src="images/op_cor_bl.png" /></td>
			<td class="side_ver"><img src="images/spacer.gif" alt="" /></td>
			<td class="corner"><img src="images/op_cor_br.png" onload="if( navigator.appName == 'Microsoft Internet Explorer' && version >= 5.5 && version < 7 ) png_fix();" /></td>
		</tr>
	</table>
</form>
<script type="text/javascript">if( document.forms.formItemEdit.form_input_html ) tinyMCE.execCommand('mceAddControl', false, 'form_input_html');</script>
	<?
}

function createNewElement( $source )
{
	global $sTableName;

	if( $source )
	{
		$aSource = db_assoc_arr( "SELECT `Column`, `Func` FROM `$sTableName` WHERE `ID`=$source" );
		if( $aSource['Column'] )
		{
			if( $aSource['Func'] == 'PFBlock' )
				return 0; // do not let copy profile blocks
			
			$sQuery = "
				INSERT INTO `$sTableName`
					( `Title`, `Desc`, `Caption`, `Func`, `Content`, `Visible` )
				SELECT
					  `Title`, `Desc`, `Caption`, `Func`, `Content`, `Visible`
				FROM `$sTableName`
				WHERE `ID` = $source
				";
				
			db_res( $sQuery );
			
			$newID = mysql_insert_id();
		}
		else
			$newID = $source; //return the source
	}
	else
	{
		$sQuery = "
			INSERT INTO `$sTableName` SET
				`Title` = 'NEW BLOCK',
				`Desc`  = 'Place here your custom HTML-block',
				`Visible` = 'non,memb',
				`Func`  = 'Echo'
			";
		
		db_res( $sQuery );
		
		$newID = mysql_insert_id();
	}
	
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
	global $sTableName;

	$aOldItem = db_arr( "SELECT * FROM `$sTableName` WHERE `ID`=$id" );
	
	if( !$aOldItem )
		return echoMenuEditMsg( 'Error. Item not found', 'red' );
	
	$sQuerySet = '';
	foreach( $aItem as $field => $value )
		$sQuerySet .= ", `$field`='" . process_db_input( $value ) ."'";
	
	$sQuerySet = substr( $sQuerySet, 1 );
	
	$sQuery = "UPDATE `$sTableName` SET $sQuerySet WHERE `ID` = $id";
	
	db_res( $sQuery );
	
	$ret = echoMenuEditMsg( 'Saved', 'green' );
	$ret .= '<script type="text/javascript">updateItem( ' . $id . ', \'' . process_db_input( $aItem['Title'] ) . '\' );</script>';
	return $ret;
}

function deleteItem( $id )
{
	global $sTableName;

	if( !$id )
		return 'ID not specified';
	
	$aItem = db_arr( "SELECT * FROM `$sTableName` WHERE `ID` = $id" );
	
	if( !$aItem )
		return 'Item not found';
	
	db_res( "DELETE FROM `$sTableName` WHERE `ID` = $id" );
	
	if( mysql_affected_rows() )
		return 'OK';
	else
		return 'Unknown error';
}

function updateLangFile( $key, $string )
{
	global $sTableName;

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
	global $sTableName;

	db_res( "UPDATE `$sTableName` SET `Column` = 0, `Order` = 0" );
	
	$sTop = trim( $sTop, ' ,' );
	$aTopIDs = explode( ',', $sTop );
	foreach( $aTopIDs as $iCol => $iID )
	{
		$iID = trim( $iID, ' ,' );
		$iID = (int)$iID;
		
		if( !$iID )
			continue;
		
		$iCol ++;
		
		$sCustom = $aCustom[$iID];
		
		$sCustom = trim( $sCustom, ' ,' );
		$aCustomIDs = explode( ',', $sCustom );
		
		foreach( $aCustomIDs as $iOrd => $iCID )
		{
			$iCID = trim( $iCID, ' ,' );
			$iCID = (int)$iCID;
			
			if( !$iCID )
				continue;
			
			db_res( "UPDATE `$sTableName` SET `Column` = $iCol, `Order` = $iOrd WHERE `ID` = $iCID" );
		}
	}
}

?>