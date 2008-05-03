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
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );


$logged['admin'] = member_auth( 1, true, true );





$_page['css_name'] = 'preValues.css';

$_page['header'] = 'Predefined Lists';

$_page['extraCodeInHead'] = <<<EOJ
<script type="text/javascript" src="{$site['plugins']}jquery/jquery.js"></script>
EOJ;




$aFields = array(
	'Value'  => 'The value stored in the database',
	'LKey'   => 'Primary language key used for displaying',
	'LKey2'  => 'Secondary language key used for displaying in some other places',
	'LKey3'  => 'Miscelaniuos language key used for displaying in some other places.',
	'Extra'  => 'Extra parameter. Used for example as link to profile image for Sex list.',
	'Extra2' => 'Miscelanious extra parameter',
	'Extra3' => 'Miscelanious extra parameter'
);






if( isset( $_REQUEST['popup'] ) and $_REQUEST['popup'] == 1 ) {
	$iAmInPopup = true;
	TopCodeAdminPopup();
	PageCompPageMainCode();
	BottomCodeAdminPopup();
} else {
	$iAmInPopup = false;
	TopCodeAdmin();
	PageCompPageMainCode();
	BottomCode();
}

function PageCompPageMainCode() {
	global $iAmInPopup;
	global $aFields;
	
	$sPopupAdd = $iAmInPopup ? '&popup=1' : '';
	
	if( isset( $_POST['action'] ) and $_POST['action'] == 'Save' and isset( $_POST['PreList'] ) and is_array( $_POST['PreList'] ) ) {
		saveList( $_POST['list'], $_POST['PreList'] );
		return;
	}
	
	//get lists
	$aLists = array( '' => '- Select -' );
	$sQuery = "SELECT DISTINCT `Key` FROM `PreValues`";
	$rLists = db_res( $sQuery );
	while( $aList = mysql_fetch_assoc( $rLists ) ) {
		$aLists[ $aList['Key'] ] = $aList['Key'];
	}
	
	if( isset( $_REQUEST['list'] ) ) {
		$sList_db = process_db_input(  $_REQUEST['list'] );
		$sList    = process_pass_data( $_REQUEST['list'] );
		
		$sQuery = "SELECT * FROM `PreValues` WHERE `Key` = '$sList_db' ORDER BY `Order`";
		$rValues = db_res( $sQuery );
		
		if( !mysql_num_rows( $rValues ) ) //if no rows returned...
			$aLists[ $sList ] = $sList; //create new list
	} else {
		$sList = '';
	}
	
	?>
	<script type="text/javascript">
		function createNewList() {
			var sNewList = prompt( 'Please enter name of new list' );
			
			if( sNewList == null )
				return false;
			
			sNewList = $.trim( sNewList );
			
			if( !sNewList.length ) {
				alert( 'You should enter correct name' );
				return false;
			}
			
			window.location = '<?= $_SERVER['PHP_SELF'] ?>?list=' + encodeURIComponent( sNewList ) + '<?= $sPopupAdd ?>';
		}
		
		function addRow( eImg ) {
			$( eImg ).parent().parent().before(
				'<tr>' +
				<?
				foreach( $aFields as $sField => $sHelp ) {
					?>
					'<td><input type="text" class="value_input" name="PreList[' + iNextInd + '][<?= $sField ?>]" value="" /></td>' +
					<?
				}
				?>
					'<th>' +
						'<img src="images/minus1.gif"     class="row_control" title="Delete"    alt="Delete" onclick="delRow( this );" />' +
						'<img src="images/arrow_up.gif"   class="row_control" title="Move up"   alt="Delete" onclick="moveUpRow( this );" />' +
						'<img src="images/arrow_down.gif" class="row_control" title="Move down" alt="Delete" onclick="moveDownRow( this );" />' +
					'</th>' +
				'</tr>'
			);
			
			iNextInd ++;
			
			sortZebra();
		}
		
		function delRow( eImg ) {
			$( eImg ).parent().parent().remove();
			sortZebra();
		}
		
		function moveUpRow( eImg ) {
			var oCur = $( eImg ).parent().parent();
			var oPrev = oCur.prev( ':not(.headers)' );
			if( !oPrev.length )
				return;
			
			// swap elements values
			var oCurElems  = $('input', oCur.get(0));
			var oPrevElems = $('input', oPrev.get(0));
			
			oCurElems.each( function(iInd) {
				var oCurElem  = $( this );
				var oPrevElem = oPrevElems.filter( ':eq(' + iInd + ')' );
				
				// swap them
				var sCurValue = oCurElem.val();
				oCurElem.val( oPrevElem.val() );
				oPrevElem.val( sCurValue );
			} );
		}
		
		function moveDownRow( eImg ) {
			var oCur = $( eImg ).parent().parent();
			var oPrev = oCur.next( ':not(.headers)' );
			if( !oPrev.length )
				return;
			
			// swap elements values
			var oCurElems  = $('input', oCur.get(0));
			var oPrevElems = $('input', oPrev.get(0));
			
			oCurElems.each( function(iInd) {
				var oCurElem  = $( this );
				var oPrevElem = oPrevElems.filter( ':eq(' + iInd + ')' );
				
				// swap them
				var sCurValue = oCurElem.val();
				oCurElem.val( oPrevElem.val() );
				oPrevElem.val( sCurValue );
			} );
		}
		
		function sortZebra() {
			$( '#listEdit tr:even' ).removeClass( 'even odd' ).addClass( 'even' );
			$( '#listEdit tr:odd'  ).removeClass( 'even odd' ).addClass( 'odd'  );
		}
		
		//just a design
		$( document ).ready( sortZebra );
	</script>
	
	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
		<table id="listEdit" cellpadding="0" cellspacing="0">
			<tr>
				<th colspan="<?= count( $aFields ) + 1 ?>">
					Select a list:
					<select name="list"
					  onchange="if( this.value != '' ) window.location = '<?= $_SERVER['PHP_SELF'] ?>' + '?list=' + encodeURIComponent( this.value ) + '<?= $sPopupAdd ?>';">
						<?= genListOptions( $aLists, $sList ) ?>
					</select>
					<input type="button" value="Create New" onclick="createNewList();" />
				</th>
			</tr>
	<?
	if( $sList !== '' ) {
		$iNextInd = genListRows( $sList_db );
		?>
			<tr>
				<th colspan="8">
					<input type="hidden" name="popup" value="<?= $iAmInPopup ?>" />
					<input type="submit" name="action" value="Save" />
				</th>
			</tr>
		<?
	} else
		$iNextInd = 0;
	?>
		</table>
		
		<script type="text/javascript">
			iNextInd = <?= $iNextInd ?>;
		</script>
	</form>
	<?
}

function genListOptions( $aLists, $sActive ) {
	$sRet = '';
	foreach( $aLists as $sKey => $sValue ) {
		$sRet .= '
			<option value="' .
			htmlspecialchars( $sKey ) .
			'"' . ( ( $sKey == $sActive ) ? ' selected="selected"' : '' ) .
			'>' . htmlspecialchars( $sValue ) . '</option>';
	}
	
	return $sRet;
}

function genListRows( $sList_db ) {
	global $aFields;
	
	$sQuery = "SELECT * FROM `PreValues` WHERE `Key` = '$sList_db' ORDER BY `Order`";
	$rList = db_res( $sQuery );
	
	?>
		<tr class="headers">
	<?
	foreach( $aFields as $sField => $sHelp ) {
		?>
			<th>
				<span class="tableLabel"
				  onmouseover="showFloatDesc( '<?= addslashes( htmlspecialchars( $sHelp ) ) ?>' );"
				  onmousemove="moveFloatDesc( event );"
				  onmouseout="hideFloatDesc();">
					<?= $sField ?>
				</span>
			</th>
		<?
	}
	?>
			<th>&nbsp;</th>
		</tr>
	<?
	
	$iCounter = 0;
	
	while( $aRow = mysql_fetch_assoc( $rList ) ) {
		?>
		<tr>
		<?
		foreach( $aFields as $sField => $sHelp ) {
			?>
			<td>
				<input type="text" class="value_input" name="PreList[<?= $iCounter ?>][<?= $sField ?>]" value="<?= htmlspecialchars( $aRow[$sField] ) ?>" />
			</td>
			<?
		}
		?>
			<th>
				<img src="images/minus1.gif"     class="row_control" title="Delete"    alt="Delete" onclick="delRow( this );" />
				<img src="images/arrow_up.gif"   class="row_control" title="Move up"   alt="Delete" onclick="moveUpRow( this );" />
				<img src="images/arrow_down.gif" class="row_control" title="Move down" alt="Delete" onclick="moveDownRow( this );" />
			</th>
		</tr>
		<?
		
		$iCounter ++;
	}
	?>
		<tr class="headers">
			<td colspan="<?= count( $aFields ) ?>">&nbsp;</td>
			<th>
				<img src="images/plus1.gif" class="row_control" title="Add" alt="Add" onclick="addRow( this );" />
			</th>
		</tr>
	<?
	
	return $iCounter;
}


function saveList( $sList, $aData ) {
	global $aFields;
	global $iAmInPopup;
	
	$sList_db = trim( process_db_input( $sList ) );
	
	if( $sList_db == '' )
		return false;
	
	$sQuery = "DELETE FROM `PreValues` WHERE `Key` = '$sList_db'";
	
	db_res( $sQuery );
	
	$sValuesAlter = '';
	
	foreach( $aData as $iInd => $aRow ) {
		$aRow['Value'] = str_replace( ',', '', trim( $aRow['Value'] ) );
		
		if( $aRow['Value'] == '' )
			continue;
		
		$sValuesAlter .= "'" . process_db_input( $aRow['Value'] ) . "', ";
		
		$sInsFields = '';
		$sInsValues = '';
		foreach( $aFields as $sField => $sTemp ) {
			$sValue = trim( process_db_input( $aRow[$sField] ) );
			
			$sInsFields .= "`$sField`, ";
			$sInsValues .= "'$sValue', ";
		}
		
		$sInsFields = substr( $sInsFields, 0, -2 ); //remove ', '
		$sInsValues = substr( $sInsValues, 0, -2 );
		
		$sQuery = "INSERT INTO `PreValues` ( `Key`, $sInsFields, `Order` ) VALUES ( '$sList_db', $sInsValues, $iInd )";
		
		//echo $sQuery . "<br />\n";
		db_res( $sQuery );
	}
	
	//alter Profiles table
	$sValuesAlter = substr( $sValuesAlter, 0, -2 ); //remove ', '
	$sQuery = "SELECT `Name` FROM `ProfileFields` WHERE `Type` = 'select_set' AND `Values` = '#!{$sList_db}'";
	$rFields = db_res( $sQuery );
	while( $aField = mysql_fetch_assoc( $rFields ) ) {
		$sField = $aField['Name'];
		
		$sQuery = "ALTER TABLE `Profiles` CHANGE `$sField` `$sField` set($sValuesAlter) NOT NULL default ''";
		db_res( $sQuery );
	}
	
	compilePreValues();
	
	if( $iAmInPopup ) {
		?>
		<script type="text/javascript">window.close()</script>
		<?
	} else {
		?>
		<div style="text-align:center;color:green;">
			Saved.
			<a href="<?= $_SERVER['PHP_SELF'] ?>?list=<?= urlencode( process_pass_data( $sList ) ) ?>">
				Return back
			</a>
		</div>
		<?
	}
}

function compilePreValues() {
	global $dir;
	
	$sQuery = "SELECT DISTINCT `Key` FROM `PreValues`";
	$rKeys = db_res( $sQuery );
	
	$rProf = @fopen( "{$dir['inc']}prof.inc.php", 'w' );
	if( !$rProf ) {
		echo '<b>Warning!</b> Couldn\'t compile prof.inc.php. Please check permissions.';
		return false;
	}
		
	
	fwrite( $rProf, "<?\n\$aPreValues = array(\n" );
	
	while( $aKey = mysql_fetch_assoc( $rKeys ) ) {
		$sKey    = $aKey['Key'];
		$sKey_db = addslashes( $sKey );
		
		fwrite( $rProf, "  '$sKey_db' => array(\n" );
		
		$sQuery = "SELECT * FROM `PreValues` WHERE `Key` = '$sKey_db' ORDER BY `Order`";
		$rRows  = db_res( $sQuery );
		
		while( $aRow = mysql_fetch_assoc( $rRows ) ) {
			$sValue_db = addslashes( $aRow['Value'] );
			fwrite( $rProf, "    '{$sValue_db}' => array( " );
			
			foreach( $aRow as $sValKey => $sValue ) {
				if( $sValKey == 'Key' or $sValKey == 'Value' or $sValKey == 'Order' )
					continue; //skip key, value and order. they already used
				
				if( !strlen( $sValue ) )
					continue; //skip empty values
				
				fwrite( $rProf, "'$sValKey' => '" . addslashes( $sValue ) . "', " );
			}
			
			fwrite( $rProf, "),\n" );
		}
		
		fwrite( $rProf, "  ),\n" );
	}
	
	fwrite( $rProf, ");\n" );
	fwrite( $rProf, '
$aPreValues[\'Country\'] = sortArrByLang( $aPreValues[\'Country\'] );

function sortArrByLang( $aArr ) {
	if( !function_exists( \'_t\' ) )
		return $aArr;
	
	$aSortArr = array();
	foreach( $aArr as $sKey => $aValue )
		$aSortArr[$sKey] = _t( $aValue[\'LKey\'] );
	
	asort( $aSortArr );
	
	$aNewArr = array();
	foreach( $aSortArr as $sKey => $sVal )
		$aNewArr[$sKey] = $aArr[$sKey];
	
	return $aNewArr;
}
	' );

	fclose( $rProf );
	
	return true;
}