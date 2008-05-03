<?

/*
	Profile Fields Manager
*/

class BxDolPFM {
	function BxDolPFM( $iArea ) {
		$this -> aColNames = array (
			1  => array( 'Page' => 'Join',   'Order' => 'JoinOrder',         'Block' => 'JoinBlock',         'ShowSysItems' => 'Couple,Captcha,TermsOfUse', 'EditAdd' => array( 'JoinPage' ) ),
			
			2  => array( 'Page' => 'Edit',   'Order' => 'EditOwnOrder',      'Block' => 'EditOwnBlock' ),
			3  => array( 'Page' => 'Edit',   'Order' => 'EditAdmOrder',      'Block' => 'EditAdmBlock',      'ShowSysItems' => 'Featured,Status' ),
			4  => array( 'Page' => 'Edit',   'Order' => 'EditModOrder',      'Block' => 'EditModBlock',      'ShowSysItems' => 'Featured,Status' ),
			
			5  => array( 'Page' => 'View',   'Order' => 'ViewAdmOrder',      'Block' => 'ViewAdmBlock',      'ShowSysItems' => 'ID,DateReg,DateLastEdit,DateLastLogin,Status' ),
			6  => array( 'Page' => 'View',   'Order' => 'ViewMembOrder',     'Block' => 'ViewMembBlock',     'ShowSysItems' => 'ID,DateReg,DateLastEdit,DateLastLogin,Status' ),
			7  => array( 'Page' => 'View',   'Order' => 'ViewModOrder',      'Block' => 'ViewModBlock',      'ShowSysItems' => 'ID,DateReg,DateLastEdit,DateLastLogin,Status' ),
			8  => array( 'Page' => 'View',   'Order' => 'ViewVisOrder',      'Block' => 'ViewVisBlock',      'ShowSysItems' => 'ID,DateReg,DateLastEdit,DateLastLogin,Status' ),
			
			9  => array( 'Page' => 'Search', 'Order' => 'SearchSimpleOrder', 'Block' => 'SearchSimpleBlock', 'EditAdd' => array( 'SearchParams' ), 'ShowSysItems' => 'ID,Keyword,Location,Couple' ),
			10 => array( 'Page' => 'Search', 'Order' => 'SearchQuickOrder',  'Block' => 'SearchQuickBlock',  'EditAdd' => array( 'SearchParams' ), 'ShowSysItems' => 'ID,Keyword,Location,Couple' ),
			11 => array( 'Page' => 'Search', 'Order' => 'SearchAdvOrder',    'Block' => 'SearchAdvBlock',    'EditAdd' => array( 'SearchParams' ), 'ShowSysItems' => 'ID,Keyword,Location,Couple' ),
		);
		
		$this -> sLinkPref = '#!'; //prefix for values links
		
		$this -> aTypes = array(
			'text'       => 'Text',
			'area'       => 'TextArea',
			'pass'       => 'Password',
			'date'       => 'Date',
			'select_one' => 'Selector',
			'select_set' => 'Multiple Selector',
			'num'        => 'Number',
			'range'      => 'Range',
			'bool'       => 'Boolean (checkbox)'
		);
		
		//altering table properties
		$this -> aTypesAlter = array(
			'text'       => "varchar(255) NOT NULL default '{default}'",
			'area'       => "text NOT NULL",
			'pass'       => "varchar(32) NOT NULL",
			'date'       => "date NOT NULL default '{default}'",
			'select_one' => "enum({values})",
			'select_one_linked' => "varchar(255) NOT NULL default ''",
			'select_set' => "set({values}) NOT NULL default ''",
			'select_set_linked' => "set({values}) NOT NULL default ''",
			'num'        => "int(10) unsigned NOT NULL default '{default}'",
			'range'      => "varchar(255) NOT NULL default '{default}'",
			'bool'       => "tinyint(1) NOT NULL default '{default}'"
		);
		
		$this -> iAreaID = (int)$iArea;
		if( !( $this -> iAreaID > 0 and isset( $this -> aColNames[$this -> iAreaID] ) ) )
			return false;
		
		// retrieve default language
		$sLangDfl = addslashes( getParam('lang_default') );
		$sQuery = "SELECT `ID` FROM `LocalizationLanguages` WHERE `Name` = '$sLangDfl'";
		$this -> sLangID = (int)db_value( $sQuery );
		
		if( !$this -> sLangID )
			return print 'Cannot continue. Default language not found.';
		
		$this -> areaPageName    = $this -> aColNames[$this -> iAreaID]['Page'];
		$this -> areaOrderCol    = $this -> aColNames[$this -> iAreaID]['Order'];
		$this -> areaBlockCol    = $this -> aColNames[$this -> iAreaID]['Block'];
		$this -> areaSysItems    = $this -> aColNames[$this -> iAreaID]['ShowSysItems'];
		
		$this -> areaEditAddCols = $this -> aColNames[$this -> iAreaID]['EditAdd'];
		
		$this -> aBlocks = array();
		$this -> aItems  = array();
		
		$this -> aBlocksInac = array();
		$this -> aItemsInac  = array();
		
	}
	
	function genJSON() {
		$this -> fillMyArrays();
		$this -> oJSONObject = new BxDolPFMAreaJSONObj( $this );
		$oJSONConv = new Services_JSON();
		return $oJSONConv -> encode( $this -> oJSONObject );
	}
	
	function fillMyArrays() {
		//collect active fields
		
		//blocks
		$sBlocksQuery = "
			SELECT
				`ID`,
				`Name`
			FROM `ProfileFields`
			WHERE
				`{$this -> areaOrderCol}` IS NOT NULL AND
				`Type` = 'block'
			ORDER BY
				`{$this -> areaOrderCol}`
		";
		
		$rBlocks = db_res( $sBlocksQuery );
		
		while( $aBlock = mysql_fetch_assoc( $rBlocks ) ) {
			$iBlockID = $aBlock['ID'];
			
			$this -> aBlocks[ $iBlockID ] = $aBlock['Name'];
			
			//get items of this block
			$sItemsQuery = "
				SELECT
					`ID`,
					`Name`
				FROM `ProfileFields`
				WHERE
					`Type` != 'block' AND
					`{$this -> areaOrderCol}` IS NOT NULL AND
					`{$this -> areaBlockCol}` = $iBlockID AND
					(
						`Type` != 'system' OR
						(
							`Type` = 'system' AND
							FIND_IN_SET( `Name`, '{$this -> areaSysItems}' )
						)
					)
				ORDER BY
					`{$this -> areaOrderCol}`
			";
			
			$rItems = db_res( $sItemsQuery );
			
			while( $aItem = mysql_fetch_assoc( $rItems ) )
				$this -> aItems[ $aItem['ID'] ] = array( $aItem['Name'], $iBlockID );
		}
		
		//collect inactive fields
		
		//blocks
		$sBlocksInacQuery = "
			SELECT
				`ID`,
				`Name`
			FROM `ProfileFields`
			WHERE
				`{$this -> areaOrderCol}` IS NULL AND
				`Type` = 'block'
		";
		
		$rBlocksInac = db_res( $sBlocksInacQuery );
		
		while( $aBlock = mysql_fetch_assoc( $rBlocksInac ) )
			$this -> aBlocksInac[ $aBlock['ID'] ] = $aBlock['Name'];
		
		//items
		$sActiveBlocksList = implode( ',', array_keys( $this -> aBlocks ) );
		if( $sActiveBlocksList == '' )
			$sActiveBlocksList = "NULL";
		
		$sItemsInacQuery = "
			SELECT
				`ID`,
				`Name`
			FROM `ProfileFields`
			WHERE
				`Type` != 'block' AND (
					`{$this -> areaBlockCol}` = 0 OR
					`{$this -> areaBlockCol}` NOT IN ($sActiveBlocksList)
				) AND (
					`Type` != 'system' OR (
						`Type` = 'system' AND
						FIND_IN_SET( `Name`, '{$this -> areaSysItems}' )
					)
				)
		";
		
		$rItemsInac = db_res( $sItemsInacQuery );
		
		while( $aItem = mysql_fetch_assoc( $rItemsInac ) )
			$this -> aItemsInac[ $aItem['ID'] ] = $aItem['Name'];
		
		//echoDbg( $this );exit;
	}
	
	function savePositions( $aInArrays ) {
		db_res( "UPDATE `ProfileFields` SET `{$this -> areaOrderCol}` = NULL" );
		db_res( "UPDATE `ProfileFields` SET `{$this -> areaBlockCol}` = 0" );
		
		if( is_array( $aInArrays['blocks'] ) ) {
			foreach( $aInArrays['blocks'] as $iBlockID ) {
				$iBlockID = (int)$iBlockID;
				
				$iBlockOrd = (int)db_value( "
					SELECT MAX( `{$this -> areaOrderCol}` )
					FROM `ProfileFields`
					WHERE `Type` = 'block'
				" ) + 1;
				
				db_res( "
					UPDATE `ProfileFields`
					SET `{$this -> areaOrderCol}` = $iBlockOrd
					WHERE `ID` = $iBlockID
				" );
			}
			
			if( is_array( $aInArrays['items'] ) and is_array( $aInArrays['items_blocks'] ) ) {
				foreach( $aInArrays['items'] as $iItemID ) {
					$iItemID = (int)$iItemID;
					$iItemBlockID = (int)$aInArrays['items_blocks'][$iItemID];
					
					if( in_array( $iItemBlockID, $aInArrays['blocks'] ) ) {
						$iItemOrd = db_value( "
							SELECT MAX( `{$this -> areaOrderCol}` )
							FROM `ProfileFields`
							WHERE `Type` != 'block' AND `{$this -> areaBlockCol}` = $iItemBlockID
						" ) + 1;
						
						db_res( "
							UPDATE `ProfileFields`
							SET
								`{$this -> areaOrderCol}` = $iItemOrd,
								`{$this -> areaBlockCol}` = $iItemBlockID
							WHERE `ID` = $iItemID
						" );
					}
				}
			}
		}
		
		echo 'OK';
	}
	
	function genFieldEditForm( $iItemID ) {
		$sQuery = "
			SELECT
				`Name`,
				`Type`,
				`Control`,
				`Extra`,
				`Min`,
				`Max`,
				`Values`,
				`UseLKey`,
				`Check`,
				`Unique`,
				`Default`,
				`Mandatory`,
				`Deletable`,
				`MatchField`,
				`MatchPercent`" .
				
				( $this -> areaEditAddCols ?
					( ', `' . implode( '`, `', $this -> areaEditAddCols ) . '`' ) :
					''
				) . "
				
			FROM `ProfileFields` WHERE `ID` = $iItemID";
		
		$aField = db_assoc_arr( $sQuery );
		
		if( !$aField ) {
			echo 'Error. Field not found';
			return;
		}
		
		// field title and description
		$this -> fieldCaption = "_FieldCaption_{$aField['Name']}_{$this -> areaPageName}"; // _FieldCaption_Sex_Join
		$this -> fieldDesc    = "_FieldDesc_{$aField['Name']}_{$this -> areaPageName}";    // _FieldDesc_Sex_Join
		
		$this -> showFormTabs = ( $aField['Type'] != 'block' and $aField['Type'] != 'system' );
		
		
		?>
		<input type="hidden" name="action" value="saveItem" />
		<input type="hidden" name="id" value="<?= $iItemID ?>" />
		<input type="hidden" name="area" value="<?= $this -> iAreaID ?>" />
		
		<?
		if( $this -> showFormTabs ) {
			?>
		<div class="form_tabs_cont">
			<ul id="form_tabs_switcher">
				<li><a href="#f1">General</a></li>
				<li><a href="#f2">Advanced</a></li>
				<li><a href="#f3">Messages</a></li>
				<li><a href="#f4">Matching</a></li>
			</ul>
		</div>
			<?
		}
		?>
		
		
		<table class="field_edit_tab" id="f1"> <!-- General -->
			<tr>
				<td class="label">Name:</td>
				<td class="value">
					<input type="text" maxlength="255" class="input_text" name="Name"
					  value="<?= htmlspecialchars( $aField['Name'] ); ?>"
					  <? if( $aField['Type'] == 'system' or !$aField['Deletable'] ) echo 'readonly="readonly"'; ?> />
				</td>
				<td class="info">
		<?
		if( $aField['Type'] != 'block' and $aField['Type'] != 'system' )
			echo $this -> getInfoIcon( 'System name used for database. It must begin with a latin letter and contain only latin letters, numbers or underscores.');
		else
			echo '&nbsp;';
		?>
				</td>
			</tr>
			<tr>
				<td class="label">Caption:</td>
				<td class="value">
					<input type="text" maxlength="255" class="input_text" name="Caption"
					  value="<?= htmlspecialchars( $this -> getLangString( $this -> fieldCaption ) ); ?>" />
				</td>
				<td class="info">
					<?= $this -> getInfoIcon( "Translation of the item caption on the {$this -> areaPageName} page to default language. Used key: <b>{$this -> fieldCaption}</b>. Do not type HTML code here!" ) ?>
				</td>
			</tr>
			<tr>
				<td class="label">Description:</td>
				<td class="value">
					<textarea class="input_text" name="Desc"><?= htmlspecialchars( $this -> getLangString( $this -> fieldDesc ) ); ?></textarea>
				</td>
				<td class="info">
					<?= $this -> getInfoIcon( "Translation of the item description on the {$this -> areaPageName} page to default language. Used key: <b>{$this -> fieldDesc}</b>." ) ?>
				</td>
			</tr>
		<?
		
		if( $aField['Type'] == 'block' ) {
			if( $this -> iAreaID == 1 ) { //Join
				?>
			<tr>
				<td class="label">Join Page:</td>
				<td class="value">
					<?= $this -> getJoinPagesSelector( $aField['JoinPage'] ) ?>
				</td>
				<td class="info">&nbsp;</td>
			</tr>
				<?
			}
		} else {
			?>
			<tr>
				<td class="label">Type:</td>
				<td class="value">
			<?
			if( $aField['Type'] == 'system' )
				echo 'System';
			else {
				?>
					<select name="Type" class="select_type" onchange="changeFieldType( this.value );">
						<?= $this -> getTypeOptions( $aField['Type'] ) ?>
					</select>
				<?
			}
			?>
				</td>
				<td class="info">&nbsp;</td>
			</tr>
			<?
		}
		
		//system fields properties
		if( $aField['Name'] == 'Keyword' ) {
			?>
			<tr>
				<td class="label">Search in fields:</td>
				<td class="value">
					<select name="KeywordFields[]" class="select_multiple" multiple="multiple">
						<?= $this -> getFieldsOptionsList( $aField['Extra'], 'Keyword' ) ?>
					</select>
				</td>
				<td class="info">
					<?= $this -> getInfoIcon( 'Select fields in which the Keyword will be able to search. Use Ctrl key to select few fields.' ) ?>
				</td>
			</tr>
			<?
		} elseif( $aField['Name'] == 'Couple' ) {
			?>
			<tr>
				<td class="label">Mutual couple fields:</td>
				<td class="value">
					<select name="CoupleFields[]" class="select_multiple" multiple="multiple">
						<?= $this -> getFieldsOptionsList( $aField['Extra'], 'Couple' ) ?>
					</select>
				</td>
				<td class="info">
					<?= $this -> getInfoIcon( 'Select fields which will be used as mutual for both persons in a couple. Use Ctrl key to select few fields.<br /><b>Note:</b> NickName, Password and Email are mandatory mutual.' ) ?>
				</td>
			</tr>
			<?
		}
		
		?>
		</table>
		
		<?
		
		if( $this -> showFormTabs ) {
			$this -> genFormAdvTab( $aField );
			$this -> genFormMiscTab( $aField );
			$this -> genFormMatchTab( $aField );
		}
		
		?>
		<table class="field_edit_tab"> <!--Controls-->
			<tr>
				<td class="buttons" colspan="3">
					<input type="submit" name="action" value="Save" />
		<?
		
		if( $aField['Type'] != 'system' and $aField['Deletable'] ) {
			?>
					<input type="submit" name="action" value="Delete" onclick="return confirm('Are you sure want to delete this item?\nAttention! The field will be deleted forever. This operation irreversible!');" />
			<?
		}
		?>
					<input type="reset" value="Cancel" />
				</td>
			</tr>
		</table>
		
		<script type="text/javascript">
			$(document).ready( function(){
				$('#form_tabs_switcher').tabs();
				changeFieldType( '<?= $aField['Type'] ?>' );
			} );
		</script>
		<?
	}
	
	function getFieldsOptionsList( $sSelected, $sType ) {
		$aSelected = explode( "\n", $sSelected );
		foreach( $aSelected as $iKey => $sValue )
			$aSelected[$iKey] = trim( $sValue );
		
		switch( $sType ) {
			case 'Keyword': $sWhere = "`Type` = 'text' OR `Type` = 'area'"; break;
			case 'Couple' : $sWhere = "`Type` != 'block' AND `Type` != 'system' AND `Deletable` = 1"; break;
			default       : $sWhere = "0";
		}
		
		
		
		$sQuery = "SELECT `Name` FROM `ProfileFields` WHERE $sWhere";
		$rFields = db_res( $sQuery );
		
		$sRet = '';
		while( $aField = mysql_fetch_assoc( $rFields ) ) {
			$sRet .= '<option value="' . $aField['Name'] . '"' .
			( in_array( $aField['Name'], $aSelected ) ? 'selected="selected"' : '' ) . '>' .
			$aField['Name'] . '</option>';
		}
		
		return $sRet;
	}
	
	function getJoinPagesSelector( $iCurrent ) {
		$sQuery = "SELECT MAX( `JoinPage` ) FROM `ProfileFields`";
		$iMaxPage = (int)db_value( $sQuery );
		
		$sRet = '<select name="JoinPage" class="select_page">';
		for( $iPage = 0; $iPage <= ( $iMaxPage + 1 ); $iPage ++ ) {
			$sRet .= 
				'<option value="' . $iPage . '"' .
				( ( $iPage == $iCurrent ) ? ' selected="selected"' : '' ) . '>' .
				$iPage . '</option>';
		}
		$sRet .= '</select>';
		
		return $sRet;
	}
	
	function genFormMatchTab( $aField ) {
		$aForm = array(
			'MatchField' => array(
				'label' => 'Match with field',
				'type'  => 'select',
				'info'  => 'Select a field which will be matched with the current.<br /> <b>Note:</b> We recommend using the same field.',
				'value' => $aField['MatchField'],
				'values' => $this -> getMatchFields( $aField )
			),
			'MatchPercent' => array(
				'label' => 'Match percent',
				'type'  => 'text',
				'info'  => 'Enter percentage that this field will match',
				'value' => $aField['MatchPercent']
			)
		);
		
		$this -> genTableEdit( $aForm, 'f4' );
	}
	
	function getMatchFields( $aField ) {
		$aSelectFields = array( $aField['Type'] );
		
		switch( $aField['Type'] ) {
			case 'select_set':
				$aSelectFields[] = 'select_one';
			break;
			
			case 'select_one':
				$aSelectFields[] = 'select_set';
			break;
			
			case 'range':
				$aSelectFields[] = 'num';
				$aSelectFields[] = 'date';
			break;
		}
		
		$sQuery = "SELECT `ID`, `Name` FROM `ProfileFields` WHERE FIND_IN_SET( `Type`, '" . implode( ',', $aSelectFields ) . "' )";
		$rMyFields = db_res( $sQuery );
		
		$aMyFields = array( '0' => '-Not set-' );
		while( $aMyField = mysql_fetch_assoc( $rMyFields ) ) {
			$aMyFields[ $aMyField['ID'] ] = $aMyField['Name'];
		}
		
		return $aMyFields;
	}
	
	function genFormAdvTab( $aField ) {
		
		$aForm = array(
			'Control_one' => array(
				'label'  => 'Selector control',
				'type'   => 'select',
				'info'   => 'The type of form input (HTML-element).',
				'value'  => $aField['Control'],
				'row_id' => 'field_control_select_one',
				'values' => array( 
					'select' => 'Select (Dropdown box)',
					'radio'  => 'Radio-buttons'
				)
			),
			'Control_set' => array(
				'label'  => 'Multiple selector control',
				'type'   => 'select',
				'info'   => 'The type of form input (HTML-element).',
				'value'  => $aField['Control'],
				'row_id' => 'field_control_select_set',
				'values' => array( 
					'select' => 'Select (Box)',
					'checkbox' => 'Checkboxes'
				)
			),
			'Mandatory' => array(
				'label'  => 'Mandatory',
				'type'   => 'checkbox',
				'info'   => 'Red asterisk indicates a field which requires a mandatory entry.',
				'value'  => $aField['Mandatory']
			),
			'Min' => array(
				'label'  => 'Minimum value',
				'type'   => 'text',
				'info'   => 'For "text" or "password" - minimum length, for "number" - minimum number, etc. Leave it empty for no restriction.',
				'value'  => $aField['Min'],
				'row_id' => 'field_minimum'
			),
			'Max' => array(
				'label'  => 'Maximum value',
				'type'   => 'text',
				'info'   => 'For "text" or "password" - maximum length, for "number" - maximum number, etc. Leave it empty for no restriction.',
				'value'  => $aField['Max'],
				'row_id' => 'field_maximum'
			),
			'Unique' => array(
				'label'  => 'Unique',
				'type'   => 'checkbox',
				'info'   => 'Define if field value for member must be unique. For example, NickName or Email.',
				'value'  => $aField['Unique'],
				'row_id' => 'field_unique'
			),
			'Check' => array(
				'label'  => 'Check',
				'type'   => 'textarea',
				'info'   => 'Some specific check (PHP-expression). For example, for checking Email, etc. Attention! Do not put here min/max/unique checks. They are defined above.',
				'value'  => $aField['Check'],
				'row_id' => 'field_check'
			),
			'Values' => array(
				'label'  => 'Possible values',
				'type'   => 'values',
				'info'   => 'List of possible values for selectors. Use new line as divider. Also it can be link to predefined list. Please do not change it if in doubt!',
				'value'  => $aField['Values'],
				'row_id' => 'field_values'
			),
			'UseLKey' => array(
				'label'  => 'Used lang. key',
				'type'   => 'select',
				'info'   => 'For selectors with "linked" values list you can use different language keys. We recommend to not change it if you are in doubt. The default is "LKey".',
				'value'  => $aField['UseLKey'],
				'row_id' => 'field_lkey',
				'values' => array( 
					'LKey'  => 'LKey',
					'LKey2' => 'LKey2',
					'LKey3' => 'LKey3',
				)
			),
			'Default' => array(
				'label'  => 'Default value',
				'type'   => 'text',
				'info'   => 'Default value set into database(!) if not set by user. Non-mandatory. For boolean fields use 0 or 1. For dates use format: YYYY-MM-DD. Please do not change it if in doubt!',
				'value'  => $aField['Default'],
				'row_id' => 'field_default'
			)
		);
		
		$this -> genTableEdit( $aForm, 'f2' );
	}
	
	function genTableEdit( $aForm, $sID = '' ) {
		?>
		<table class="field_edit_tab" <?= $sID ? ( 'id="' . $sID . '"' ) : '' ?>>
		
		<?
		foreach( $aForm as $sInputName => $aInput ) {
			?>
			<tr <?= $aInput['row_id'] ? ( 'id="' . $aInput['row_id'] . '"' ) : '' ?>>
				<td class="label"><?= $aInput['label'] ?>:</td>
				<td class="value">
			<?
			switch( $aInput['type'] ) {
				case 'textarea':
					?>
					<textarea name="<?= $sInputName ?>" class="input_text"><?= htmlspecialchars( $aInput['value'] ) ?></textarea>
					<?
				break;
				case 'checkbox':
					?>
					<input type="checkbox" name="<?= $sInputName ?>" value="yes" class="input_checkbox"
					  <?= $aInput['value'] ? 'checked="checked"' : '' ?> />
					<?
				break;
				case 'text':
					?>
					<input type="text" name="<?= $sInputName ?>" value="<?= htmlspecialchars( $aInput['value'] ) ?>" class="input_text" />
					<?
				break;
				case 'select':
					?>
					<select name="<?= $sInputName ?>" class="select_type">
					<?
					foreach( $aInput['values'] as $sKey => $sValue ) {
						?>
						<option value="<?= $sKey ?>" <?= ( $sKey == $aInput['value'] ) ? 'selected="selected"' : '' ?>><?= $sValue ?></option>
						<?
					}
					?>
					</select>
					<?
				break;
				case 'values':
					if( substr( $aInput['value'], 0, 2 ) == $this -> sLinkPref ) { //it is link
						$sLink = substr( $aInput['value'], 2 );
						?>
					<input type="hidden" name="<?= $sInputName ?>" value="<?= htmlspecialchars( $aInput['value'] ) ?>" />
					<a href="preValues.php?list=<?= urlencode( $sLink ) ?>" target="_blank"
					  onclick="return !window.open( this.href + '&popup=1', 'preValuesEdit', 'width=750,height=700,resizable=yes,scrollbars=yes,toolbar=no,status=no,menubar=no' );"
					  title="Edit list"><?= $sLink ?></a>
					
					<a href="javascript:void(0);" onclick="activateValuesEdit( this );" title="Change link" style="margin-left:20px;">
						<img src="images/edit.gif" alt="Change" />
					</a>
						<?
					}
					else { //it is simple list
						?>
					<textarea class="input_text" name="<?= $sInputName ?>"><?= htmlspecialchars( $aInput['value'] ) ?></textarea>
						<?
					}
				break;
			}
			?>
				</td>
				<td class="info">
					<?= $this -> getInfoIcon( $aInput['info'] ) ?>
				</td>
			</tr>
			<?
		}
		?>
		
		</table>
		<?
	}
	
	//used for parsing extra parameters
	function parseParams( $sParams ) {
		if( $sParams == '' )
			return array();
		
		$aParams = array();
		
		$aParamLines = explode( "\n", $sParams );
		
		foreach( $aParamLines as $sLine ) {
			list( $sKey, $sValue) = explode( ':', $sLine, 2 );
			$aParams[$sKey] = $sValue;
		}
		
		return $aParams;
	}
	
	function genFormMiscTab( $aField ) {
		$aForm = array(
			'Mandatory_msg' => array(
				'label'  => 'Mandatory error message',
				'type'   => 'textarea',
				'info'   => 'Error message returned if user didn\'t specify value when field is mandatory. Translated to default language (for other languages - use key: _FieldError_' . $aField['Name'] . '_Mandatory). This texts is non-mandatory field is non-mandatory.',
				'value'  => $this -> getLangString( "_FieldError_{$aField['Name']}_Mandatory" ),
				/*'row_id' => 'field_mandatory_msg'*/
			),
			'Min_msg' => array(
				'label'  => 'Minimum exceed error message',
				'type'   => 'textarea',
				'info'   => 'Error message returned when field minimum limit exceeded. Translated to default language (for other languages - use key: _FieldError_' . $aField['Name'] . '_Min). Non-mandatory if minimum limit is not defined.',
				'value'  => $this -> getLangString( "_FieldError_{$aField['Name']}_Min" ),
				'row_id' => 'field_minimum_msg'
			),
			'Max_msg' => array(
				'label'  => 'Maximum exceed error message',
				'type'   => 'textarea',
				'info'   => 'Error message returned when field maximum limit exceeded. Translated to default language (for other languages - use key: _FieldError_' . $aField['Name'] . '_Max). Non-mandatory if maximum limit is not defined.',
				'value'  => $this -> getLangString( "_FieldError_{$aField['Name']}_Max" ),
				'row_id' => 'field_maximum_msg'
			),
			'Unique_msg' => array(
				'label'  => 'Non-unique error message',
				'type'   => 'textarea',
				'info'   => 'Error message returned if user entered non-unique value. Translated to default language (for other languages - use key: _FieldError_' . $aField['Name'] . '_Unique). Non-mandatory if Unique is not set.',
				'value'  => $this -> getLangString( "_FieldError_{$aField['Name']}_Unique" ),
				'row_id' => 'field_unique_msg'
			),
			'Check_msg' => array(
				'label'  => 'Check error message',
				'type'   => 'textarea',
				'info'   => 'Error message returned if field Check is failed (for other languages - use key: _FieldError_' . $aField['Name'] . '_Check). Translated to default language. Non-mandatory if Check is not defined.',
				'value'  => $this -> getLangString( "_FieldError_{$aField['Name']}_Check" ),
				'row_id' => 'field_check_msg'
			)
		);
			
		$this -> genTableEdit( $aForm, 'f3' );
	}
	
	function getInfoIcon( $sText ) {
		return '
		<img src="images/info.gif" class="info_icon"
		  onmouseover="showFloatDesc(\'' . htmlspecialchars( $sText ) . '\');"
		  onmousemove="moveFloatDesc( event )"
		  onmouseout="hideFloatDesc();" />
		';
	}
	
	function getTypeOptions( $sActive ) {
		$sRet = '';
		
		foreach( $this -> aTypes as $sKey => $sValue ) {
			$sRet .= '<option value="' . $sKey . '" ' . ( $sActive == $sKey ? 'selected="selected"' : '') . '>' . $sValue . '</option>';
		}
		
		return $sRet;
	}
	
	function getLangString( $sKey ) {
		if( $sKey == '' )
			return '';
		
		$sKey_db    = addslashes( $sKey );
		$sString_db = addslashes( $sString );
		
		$sQuery = "SELECT `ID` FROM `LocalizationKeys` WHERE `Key` = '$sKey_db'";
		$iKeyID = (int)db_value( $sQuery );
		
		if( !$iKeyID )
			return '';
		
		$sQuery = "
			SELECT `String` FROM `LocalizationStrings`
			WHERE `IDKey` = $iKeyID AND `IDLanguage` = {$this -> sLangID}";
		
		return (string)db_value( $sQuery );
	}
	
	function saveItem( $aData ) {
		$this -> genSaveItemHeader();
		$this -> isHaveErrors = false;
		//echoDbg( $aData );
		$iItemID = (int)$aData['id'];
		
		$aItem = db_assoc_arr( "SELECT * FROM `ProfileFields` WHERE `ID` = $iItemID" );
		
		if( !$aItem ) {
			$this -> genSaveItemError( 'Warning! Field not found.' );
			$this -> genSaveItemFooter();
			return false;
		}
		
		// just a flag
		$bHaveErrors = false;
		
		// this array will be put into db
		$aUpdate = array();
		
		// check name
		if( $aItem['Type'] != 'system' and $aItem['Deletable'] ) { //we can change the name
			
			$sName = trim( strip_tags( process_pass_data( $aData['Name'] ) ) );
			
			if( $sName === '' ) {
				$this -> genSaveItemError( 'You must enter the name.', 'Name' );
				$bHaveErrors = true;
			} elseif( $aItem['Type'] != 'block' and !preg_match( '/^[a-z][a-z0-9_]*$/i', $sName ) ) {
				$this -> genSaveItemError( 'The name must begin with a latin letter and contain only latin letters, numbers or underscores.', 'Name' );
				$bHaveErrors = true;
			} elseif( db_value( "SELECT COUNT(*) FROM `ProfileFields` WHERE `Name` = '$sName' and `ID` != $iItemID" ) ) {
				$this -> genSaveItemError( 'A field with such name already exists. Please select another.', 'Name' );
				$bHaveErrors = true;
			} elseif( $sName == $aItem['Name'] ) {
				// all ok. don't change
			} else
				$aUpdate['Name'] = $sName; //change
		}
		
		$sNewName = isset( $aUpdate['Name'] ) ? $aUpdate['Name'] : $aItem['Name'];
		
		$this -> fieldCaption = "_FieldCaption_{$sNewName}_{$this -> areaPageName}"; // _FieldCaption_Sex_Join
		$this -> fieldDesc    = "_FieldDesc_{$sNewName}_{$this -> areaPageName}";    // _FieldDesc_Sex_Join
		
		// check Caption
		$sCaption = trim( process_pass_data( $aData['Caption'] ) );
		
		if( $sCaption === '' ) {
			$this -> genSaveItemError( 'You must enter the caption.', 'Caption' );
			$bHaveErrors = true;
		} elseif( $this -> getLangString( $this -> fieldCaption ) == $sCaption ) {
			// all ok dont change
		} else
			$this -> updateLangString( $this -> fieldCaption, $sCaption );
		
		// check Description
		$sDesc = trim( process_pass_data( $aData['Desc'] ) );
		if( $this -> getLangString( $this -> fieldDesc ) != $sDesc )
			$this -> updateLangString( $this -> fieldDesc, $sDesc );
		
		// check type
		if( $aItem['Type'] != 'system' and $aItem['Type'] != 'block' ) {
			
			//we can change the type
			$sType = trim( strip_tags( process_pass_data( $aData['Type'] ) ) );
			
			if( !isset( $this -> aTypes[$sType] ) ) {
				$this -> genSaveItemError( 'Don\'t fuck with my brain! I don\'t know this type.', 'Type' );
				$bHaveErrors = true;
			} elseif( $sType == $aItem['Type'] ) {
				// all ok. don't change
			} else
				$aUpdate['Type'] = $sType; //change
		
			// check the additional properties
			if( !$bHaveErrors ) { // do not continue if have errors
				
				// check selectors controls
				if( $sType == 'select_one' ) {
					if( $aData['Control_one'] == $aItem['Control'] ) {
						//all ok
					} elseif( $aData['Control_one'] == 'select' or $aData['Control_one'] == 'radio' ) {
						$aUpdate['Control'] = $aData['Control_one'];
					} else {
						$this -> genSaveItemError( 'I don\'t know this control type.', 'Control_one' );
						$bHaveErrors = true;
					}
				} elseif( $sType == 'select_set' ) {
					if( $aData['Control_set'] == $aItem['Control'] ) {
						//all ok
					} elseif( $aData['Control_set'] == 'select' or $aData['Control_set'] == 'checkbox' ) {
						$aUpdate['Control'] = $aData['Control_set'];
					} else {
						$this -> genSaveItemError( 'I don\'t know this control type.', 'Control_set' );
						$bHaveErrors = true;
					}
				} else
					$aUpdate['Control'] = '';
				
				//check Min
				$iMin = trim( $aData['Min'] );
				
				if( $iMin === '' or $sType == 'bool' or $sType == 'select_one' or $sType == 'select_set' )
					$iMin = null;
				else {
					$iMin = (int)$iMin;
					if( $sType == 'area' and $iMin > 65534 )
						$iMin = 65535;
					elseif( $sType != 'area' and $iMin > 254 )
						$iMin = 65535;
					elseif( $iMin < 0 )
						$iMin = 0;
				}
				
				$aUpdate['Min'] = $iMin;
				
				
				//check Max
				$iMax = trim( $aData['Max'] );
				
				if( $iMax === '' or $sType == 'bool' or $sType == 'select_one' or $sType == 'select_set' )
					$iMax = null;
				else {
					$iMax = (int)$iMax;
					if( $sType == 'area' and $iMax > 65534 )
						$iMax = 65535;
					elseif( $sType != 'area' and $iMax > 254 )
						$iMax = 65535;
					elseif( $iMax < 0 )
						$iMax = 0;
				}
				
				$aUpdate['Max'] = $iMax;
				
				//check Check :)
				if( $sType == 'select_one' or $sType == 'select_set' or $sType == 'bool' )
					$aUpdate['Check'] = '';
				else {
					$sCheck = trim( process_pass_data( $aData['Check'] ) );
					
					if( $aItem['Check'] != $sCheck )
						$aUpdate['Check'] = $sCheck;
				}
				
				//Unique
				$aUpdate['Unique'] = (
					isset( $aData['Unique'] ) and
					$aData['Unique'] == 'yes' and
					( $sType == 'text' or $sType == 'area' or $sType == 'num' )
				) ? 1 : 0;
				
				//Mandatory
				$aUpdate['Mandatory'] = ( isset( $aData['Mandatory'] ) and $aData['Mandatory'] == 'yes' ) ? 1 : 0;
				
				//check Values
				if( $sType == 'select_one' or $sType == 'select_set' ) {
					$sValues = trim( strip_tags( process_pass_data( $aData['Values'] ) ) );
					
					$sValues = str_replace( "\r", "\n", $sValues );   // for mac
					$sValues = str_replace( "\n\n", "\n", $sValues ); // for win
					                                                ; // for *nix ;)
					
					if( $sValues === '' ) {
						$this -> genSaveItemError( 'You must enter values', 'Values' );
						$bHaveErrors = true;
					} elseif( $sValues != $aItem['Values'] ) {
						if( substr( $sValues, 0, 2 ) == $this -> sLinkPref and !$this -> checkValuesLink( substr( $sValues, 2 ) ) ) {
							$this -> genSaveItemError( 'You entered incorrect link', 'Values' );
							$bHaveErrors = true;
						} else
							$aUpdate['Values'] = $sValues;
					}
					
					// get LKey
					$sUseLKey = trim( process_pass_data( $aData['UseLKey'] ) );
					if( !$sUseLKey )
						$sUseLKey = 'LKey';
					
					$aUpdate['UseLKey'] = $sUseLKey;
				} elseif( $aItem['Values'] != '' )
					$aUpdate['Values'] = '';
				
				if( !$bHaveErrors ) {
					//Default
					switch( $sType ) {
						case 'text':
							$aUpdate['Default'] = trim( process_pass_data( $aData['Default'] ) );
						break;
						case 'pass':
						case 'area':
						case 'select_set':
							$aUpdate['Default'] = '';
						break;
						case 'num':
							$aUpdate['Default'] = (int)$aData['Default'];
						break;
						case 'bool':
							$aUpdate['Default'] = (int)(bool)$aData['Default'];
						break;
						case 'range':
							if( trim( $aData['Default'] ) == '' )
								$aUpdate['Default'] = '';
							else {
								list( $sFirst, $sSecond ) = explode( '-', trim( $aData['Default'], 2 ) ); 
								$sFirst  = (int)trim( $sFirst );
								$sSecond = (int)trim( $sSecond );
								$aUpdate['Default'] = "$sFirst-$sSecond";
							}
						break;
						case 'date':
							if( $aData['Default'] === '' )
								$aUpdate['Default'] = '';
							else
								$aUpdate['Default'] = date( 'Y-m-d', strtotime( trim( process_pass_data( $aData['Default'] ) ) ) );
						break;
						
						case 'select_one':
							$sDefault = trim( process_pass_data( $aData['Default'] ) );
							if( $sDefault === '' )
								$aUpdate['Default'] = '';
							else {
								if( $this -> checkSelectDefault( $sValues, $sDefault ) )
									$aUpdate['Default'] = $sDefault;
								else {
									$this -> genSaveItemError( 'You entered an incorrect value', 'Default' );
									$bHaveErrors = true;
								}
							}
						break;
					}
					
					//matching. not implemented yet
				}
			}
		}
		
		if( $aItem['Type'] == 'block' and $this -> iAreaID == 1 ) { //Join
			//get JoinPage
			$iJoinPage = (int)$aData['JoinPage'];
			if( $aItem['JoinPage'] != $iJoinPage )
				$aUpdate['JoinPage'] = $iJoinPage;
		}
		
		//system fields properties
		if( $aItem['Name'] == 'Keyword' ) {
			if( is_array( $aData['KeywordFields'] ) ) {
				$sKeywordFields = implode( "\n", $aData['KeywordFields'] );
				
				if( process_pass_data( $sKeywordFields ) != $aItem['Extra'] )
					$aUpdate['Extra'] = $sKeywordFields;
			}
		}
		
		if( $aItem['Name'] == 'Couple' ) {
			if( is_array( $aData['CoupleFields'] ) ) {
				$sKeywordFields = implode( "\n", $aData['CoupleFields'] );
				
				if( process_pass_data( $sKeywordFields ) != $aItem['Extra'] )
					$aUpdate['Extra'] = $sKeywordFields;
			}
		}
		
		// update error messages
		foreach( array( 'Mandatory', 'Min', 'Max', 'Unique', 'Check' ) as $sErrName ) {
			$sErrMsg = trim( process_pass_data( $aData[$sErrName . '_msg'] ) );
			if( empty($sErrMsg) )
				continue;
			
			$sErrKey = "_FieldError_{$sNewName}_{$sErrName}";
			
			$this -> updateLangString( $sErrKey, $sErrMsg );
		}
		
		// add matching
		if( isset( $aData['MatchField'] ) and (int)$aData['MatchField'] != $aItem['MatchField'] )
			$aUpdate['MatchField'] = (int)$aData['MatchField'];
		
		if( isset( $aData['MatchPercent'] ) and (int)$aData['MatchPercent'] != $aItem['MatchPercent'] )
			$aUpdate['MatchPercent'] = (int)$aData['MatchPercent'];
		
		if( !empty( $aUpdate ) and !$bHaveErrors ) {
			$this -> doUpdateItem( $aItem, $aUpdate );
			if( isset( $aUpdate['Name'] ) )
				$this -> genSaveItemFormUpdate( 'updateItem', $iItemID, $aUpdate['Name'] );
			
			if( $aItem['Type'] == 'block' and $aUpdate['Name'] ) {
				$sQuery = "
					UPDATE `PageCompose` SET
						`Caption` = '_FieldCaption_" . addslashes( $sNewName ) . "_View'
					WHERE
						`Func` = 'PFBlock' AND
						`Content` = '$iItemID'
					";
				
				db_res( $sQuery );
			}
		}
		
		if( !$bHaveErrors )
			$this -> genSaveItemFormClose();
		
		$this -> genSaveItemFooter();
	}
	
	function checkValuesLink( $sKey ) {
		global $aPreValues;
		
		return isset( $aPreValues[$sKey] );
	}
	
	function checkSelectDefault( $sValues, $sDefault ) {
		global $aPreValues;
		
		if( substr( $sValues, 0, 2 ) == $this -> sLinkPref ) { //it is link
			$sKey = substr( $sValues, 2 );
			return isset( $aPreValues[$sKey][$sDefault] );
		} else {
			$aValues = explode( "\n", $sValues );
			return in_array( $sDefault, $aValues );
		}
	}
	
	function doUpdateItem( $aItem, $aUpdate ) {
		global $aPreValues;
		
		$aUpdateStrs = '';
		foreach( $aUpdate as $sKey => $sValue ) {
			if( is_null( $sValue ) )
				$aUpdateStrs[] = "`$sKey` = NULL";
			else
				$aUpdateStrs[] = "`$sKey` = '" . addslashes( $sValue ) . "'";
		}
		
		$sQuery = "
			UPDATE `ProfileFields` SET 
				" . implode( ", 
				", $aUpdateStrs ) . "
			WHERE `ID` = {$aItem['ID']}";
		
		db_res( $sQuery );
		
		if( //we need alter Profiles table
			$aItem['Type'] != 'block' and (
				isset( $aUpdate['Type'] ) or
				isset( $aUpdate['Name'] ) or
				isset( $aUpdate['Values'] ) or
				isset( $aUpdate['Default'] )
			)
		) {
			$aAlter = array(
				'Type'    => isset( $aUpdate['Type']    ) ? $aUpdate['Type']    : $aItem['Type'],
				'Name'    => isset( $aUpdate['Name']    ) ? $aUpdate['Name']    : $aItem['Name'],
				'Values'  => isset( $aUpdate['Values']  ) ? $aUpdate['Values']  : $aItem['Values'],
				'Default' => isset( $aUpdate['Default'] ) ? $aUpdate['Default'] : $aItem['Default'],
			);
			
			if( substr( $aAlter['Values'], 0, 2 ) == $this -> sLinkPref )
				$aAlter['Type'] .= '_linked';
			
			$sQuery = "ALTER TABLE `Profiles` CHANGE `{$aItem['Name']}` `{$aAlter['Name']}` {$this -> aTypesAlter[$aAlter['Type']]}";
			$sQuery = str_replace( '{default}', addslashes( $aAlter['Default'] ), $sQuery );
			
			if( $aAlter['Type'] == 'select_one' or $aAlter['Type'] == 'select_set' ) { //insert values
				$aValuesAlter = explode( "\n", $aAlter['Values'] ); //explode values to array
				
				foreach( $aValuesAlter as $iKey => $sValue ){ //add slashes to every value
					$sValue = str_replace( '\\', '\\\\', $sValue );
					$sValue = str_replace( '\'', '\\\'', $sValue );
					$aValuesAlter[$iKey] = $sValue;
				}
				
				$sValuesAlter = " '" . implode( "', '", $aValuesAlter ) . "' "; // implode values to string like 'a','b','c\'d'
				$sQuery = str_replace( '{values}', $sValuesAlter, $sQuery ); //replace it in place
			} elseif( $aAlter['Type'] == 'select_set_linked' ) {
				$sLink = substr( $aAlter['Values'], 2 );
				$aValuesAlter = array_keys( $aPreValues[$sLink] );
				
				$sValuesAlter = implode( ', ', $aValuesAlter );
				$sValuesAlter = str_replace( '\\', '\\\\', $sValuesAlter );
				$sValuesAlter = str_replace( '\'', '\\\'', $sValuesAlter );
				
				$sValuesAlter = "'" .str_replace( ', ', "', '", $sValuesAlter ) ."'";
				
				$sQuery = str_replace( '{values}', $sValuesAlter, $sQuery ); //replace it in place
			}
			
			db_res( $sQuery );
		}
	}
	
	function createNewField() {
		$iNewID = 0;
		
		//try to insert new item
		if( db_res( "INSERT INTO `ProfileFields` (`Name`, `Type` ) VALUES ('NEW_ITEM', 'text')", 0 ) and $iNewID = mysql_insert_id() ) {
			//if success - try to alter table
			if( !db_res( "ALTER TABLE `Profiles` ADD `NEW_ITEM` varchar(255) NOT NULL default ''", 0 ) ) {
				//if couldn't alter - delete inserted field
				db_res( "DELETE FROM `ProfileFields` WHERE `ID` = $iNewID" );
				$iNewID = 0;
			}
		}
		
		return $iNewID;
	}
	
	function createNewBlock() {
		db_res( "INSERT INTO `ProfileFields` (`Name`, `Type` ) VALUES ('NEW BLOCK', 'block')", 0 );
		$iNewID = mysql_insert_id();
		
		db_res( "
			INSERT INTO `PageCompose`
				( `Desc`, `Caption`, `Visible`, `Func`, `Content`, `Page` )
			VALUES
				( 'Profile Fields Block', '_FieldCaption_NEW BLOCK_View', 'non,memb', 'PFBlock', '$iNewID', 'profile' )
		" );
		
		return $iNewID;
	}
	
	function updateLangString( $sKey, $sString ) {
		
		if( $sKey == '' )
			return false;
		
		$sKey_db    = addslashes( $sKey );
		$sString_db = addslashes( $sString );
		
		$sQuery = "SELECT `ID` FROM `LocalizationKeys` WHERE `Key` = '$sKey_db'";
		$iKeyID = (int)db_value( $sQuery );
		
		if( !$iKeyID ) { //create key
			$sQuery = "INSERT INTO `LocalizationKeys` (`IDCategory`,`Key`) VALUES (32,'$sKey_db')";
			db_res( $sQuery );
			$iKeyID = mysql_insert_id();
		}
		
		$sQuery = "
			SELECT COUNT( * ) FROM `LocalizationStrings`
			WHERE `IDKey` = $iKeyID AND `IDLanguage` = {$this -> sLangID}";
		
		$iCount = (int)db_value( $sQuery );
		
		if( $iCount ) {
			$sQuery = "
				UPDATE `LocalizationStrings`
				SET `String` = '$sString_db'
				WHERE `IDKey` = $iKeyID AND `IDLanguage` = {$this -> sLangID}";
			
			db_res( $sQuery );
		} else {
			$sQuery = "INSERT INTO `LocalizationStrings` VALUES ( $iKeyID, {$this -> sLangID}, '$sString_db' )";
			db_res( $sQuery );
		}
		
		compileLanguage( $this -> sLangID );
	}
	
	function genSaveItemHeader() {
		?>
<html><script type="text/javascript">
		<?
	}
	
	function genSaveItemError( $sText, $sField = '' ) {
		$this -> isHaveErrors = true;
		
		if( !$sField )
			echo "alert( '" . addslashes( $sText ) . "' );";
		else {
			?>
			parent.genEditFormError( '<?= $sField ?>', '<?= addslashes( $sText ) ?>' );
			<?
		}
	}
	
	function genSaveItemFooter() {
		?>

</script></html>
		<?
	}
	
	function deleteItem( $iItemID ) {
		$this -> genSaveItemHeader();
		
		$aItem = db_assoc_arr( "SELECT * FROM `ProfileFields` WHERE `ID` = $iItemID" );
		
		if( !$aItem )
			$this -> genSaveItemError( 'Warning! Item not found.' );
		elseif( $aItem['Type'] == 'system' or !(int)$aItem['Deletable'] )
			$this -> genSaveItemError( 'The field cannot be deleted.' );
		else{
			$sQuery = "DELETE FROM `ProfileFields` WHERE `ID` = $iItemID";
			db_res( $sQuery );
			
			if( $aItem['Type'] == 'block' )
				db_res( "DELETE FROM `PageCompose` WHERE `Func` = 'PFBlock' AND `Content` = '$iItemID'" );
			else
				db_res( "ALTER TABLE `Profiles` DROP `{$aItem['Name']}`" );
			
			$this -> genSaveItemFormUpdate( 'deleteItem', $iItemID );
			//$this -> genSaveItemFormClose();
		}
		$this -> genSaveItemFooter();
	}
	
	function genSaveItemFormUpdate( $sText, $iItemID, $sNewName = '' ) {
		?>
		parent.updateBuilder( '<?= $sText ?>', <?= $iItemID ?>, '<?= addslashes( $sNewName ) ?>' );
		<?
	}
	
	function genSaveItemFormClose() {
		?>
		parent.hideEditForm();
		<?
	}
}



class BxDolPFMAreaJSONObj {
	
	function BxDolPFMAreaJSONObj( $oArea ) {
		$this -> id = $oArea -> iAreaID;
		
		$this -> active_blocks   = array();
		$this -> inactive_blocks = array();
		$this -> active_items    = array();
		$this -> inactive_items  = array();
		
		foreach( $oArea -> aBlocks as $iID => $sName )
			$this -> active_blocks[] = new BxDolPFMItem( $iID, $sName );
		
		foreach( $oArea -> aItems as $iID => $aItem )
			$this -> active_items[] = new BxDolPFMItem( $iID, $aItem[0], $aItem[1] );
		
		foreach( $oArea -> aBlocksInac as $iID => $sName )
			$this -> inactive_blocks[] = new BxDolPFMItem( $iID, $sName );
		
		foreach( $oArea -> aItemsInac as $iID => $sName )
			$this -> inactive_items[] = new BxDolPFMItem( $iID, $sName );
	}
}

/* Used for JSON generation */
class BxDolPFMItem {
	
	function BxDolPFMItem( $iID, $sName, $iBlock = 0 ) {
		$this -> id = $iID;
		$this -> name = $sName;
		
		$this -> block = $iBlock;
	}
}



/*
	Cacher created only for creating a cache :)
*/

class BxDolPFMCacher {
	
	var $aAreasProps;
	var $sCacheFile;
	
	function BxDolPFMCacher() {
		
		//additional properties for caching blocks
		$aAddBlockProps = array( 
			'Join' => array(
				'Caption'   => '_FieldCaption_{Name}_Join',
				'Desc'      => '_FieldDesc_{Name}_Join'
			),
			'Edit' => array(
				'Caption'   => '_FieldCaption_{Name}_Edit',
				'Desc'      => '_FieldDesc_{Name}_Edit'
			),
			'View' => array(
				'Caption'   => '_FieldCaption_{Name}_View',
				'Desc'      => '_FieldDesc_{Name}_View'
			),
			'Search' => array(
				'Caption'   => '_FieldCaption_{Name}_Search',
				'Desc'      => '_FieldDesc_{Name}_Search'
			)
		);

		//additional properties for caching items
		$aAddProps = array( 
			'Join' => array(
				'Caption'   => '_FieldCaption_{Name}_Join',
				'Desc'      => '_FieldDesc_{Name}_Join',
				'MandatoryMsg' => '_FieldError_{Name}_Mandatory',
				'MinMsg'    => '_FieldError_{Name}_Min',
				'MaxMsg'    => '_FieldError_{Name}_Max',
				'UniqueMsg' => '_FieldError_{Name}_Unique',
				'CheckMsg'  => '_FieldError_{Name}_Check'
			),
			'Edit' => array(
				'Caption'   => '_FieldCaption_{Name}_Edit',
				'Desc'      => '_FieldDesc_{Name}_Edit',
				'MandatoryMsg'  => '_FieldError_{Name}_Mandatory',
				'MinMsg'    => '_FieldError_{Name}_Min',
				'MaxMsg'    => '_FieldError_{Name}_Max',
				'UniqueMsg' => '_FieldError_{Name}_Unique',
				'CheckMsg'  => '_FieldError_{Name}_Check'
			),
			'View' => array(
				'Caption'   => '_FieldCaption_{Name}_View',
				'Desc'      => '_FieldDesc_{Name}_View'
			),
			'Search' => array(
				'Caption'   => '_FieldCaption_{Name}_Search',
				'Desc'      => '_FieldDesc_{Name}_Search'
			)
		);
		
		$this -> aAreasProps = array (
			1  => array( 'Title' => 'Join',   'Order' => 'JoinOrder', 'Block' => 'JoinBlock', 'AddSelect' => 'Control,Extra,Min,Max,Values,Check,Unique,Mandatory,UseLKey',               'AddBlockProps' => $aAddBlockProps['Join'],   'AddProps' => $aAddProps['Join'] ),
			
			2  => array( 'Title' => 'Edit (Owner)',   'Order' => 'EditOwnOrder', 'Block' => 'EditOwnBlock', 'AddSelect' => 'Control,Extra,Min,Max,Values,Check,Unique,Mandatory,UseLKey', 'AddBlockProps' => $aAddBlockProps['Edit'],   'AddProps' => $aAddProps['Edit'] ),
			3  => array( 'Title' => 'Edit (Admin)',   'Order' => 'EditAdmOrder', 'Block' => 'EditAdmBlock', 'AddSelect' => 'Control,Extra,Min,Max,Values,Check,Unique,Mandatory,UseLKey', 'AddBlockProps' => $aAddBlockProps['Edit'],   'AddProps' => $aAddProps['Edit'] ),
			4  => array( 'Title' => 'Edit (Moder)',   'Order' => 'EditModOrder', 'Block' => 'EditModBlock', 'AddSelect' => 'Control,Extra,Min,Max,Values,Check,Unique,Mandatory,UseLKey', 'AddBlockProps' => $aAddBlockProps['Edit'],   'AddProps' => $aAddProps['Edit'] ),
			
			5  => array( 'Title' => 'View (Admin)',     'Order' => 'ViewAdmOrder',  'Block' => 'ViewAdmBlock',  'AddSelect' => 'Values,UseLKey',                         'AddBlockProps' => $aAddBlockProps['View'],   'AddProps' => $aAddProps['View'] ),
			6  => array( 'Title' => 'View (Member)',    'Order' => 'ViewMembOrder', 'Block' => 'ViewMembBlock', 'AddSelect' => 'Values,UseLKey',                         'AddBlockProps' => $aAddBlockProps['View'],   'AddProps' => $aAddProps['View'] ),
			7  => array( 'Title' => 'View (Moder)',     'Order' => 'ViewModOrder',  'Block' => 'ViewModBlock',  'AddSelect' => 'Values,UseLKey',                         'AddBlockProps' => $aAddBlockProps['View'],   'AddProps' => $aAddProps['View'] ),
			8  => array( 'Title' => 'View (Visitor)',   'Order' => 'ViewVisOrder',  'Block' => 'ViewVisBlock',  'AddSelect' => 'Values,UseLKey',                         'AddBlockProps' => $aAddBlockProps['View'],   'AddProps' => $aAddProps['View'] ),
			
			9  => array( 'Title' => 'Search (Simple)', 'Order' => 'SearchSimpleOrder', 'Block' => 'SearchSimpleBlock', 'AddSelect' => 'Extra,Control,UseLKey',             'AddBlockProps' => $aAddBlockProps['Search'], 'AddProps' => $aAddProps['Search'] ),
			10 => array( 'Title' => 'Search (Quick)',  'Order' => 'SearchQuickOrder',  'Block' => 'SearchQuickBlock',  'AddSelect' => 'Extra,Control,UseLKey',             'AddBlockProps' => $aAddBlockProps['Search'], 'AddProps' => $aAddProps['Search'] ),
			11 => array( 'Title' => 'Search (Adv)',    'Order' => 'SearchAdvOrder',    'Block' => 'SearchAdvBlock',    'AddSelect' => 'Extra,Control,UseLKey',             'AddBlockProps' => $aAddBlockProps['Search'], 'AddProps' => $aAddProps['Search'] ),
			
			//special areas
			100 => array( 'Title' => 'All Fields. PC cache', 'AddSelect' => 'Default,Unique,Extra' ),
			101 => array( 'Title' => 'Matching Fields',       'AddSelect' => 'MatchField,MatchPercent' ),
		);
		
		$this -> sCacheFile = BX_DIRECTORY_PATH_INC . 'db_cached/ProfileFields.inc';
		
	}
	
	function createCache() {
		$rCacheFile = @fopen( $this -> sCacheFile, 'w' );
		if( !$rCacheFile ) {
			echo '<br /><b>Warning!</b> Cannot open Profile Fields cache file (' . $this -> sCacheFile . ') for write.';
			return false;
		}
		
		$sCacheString = "// cache of Profile Fields\n\nreturn array(\n  //areas\n";
		
		// get areas
		foreach ($this -> aAreasProps as $iAreaID => $aArea) {
			$oArea = new BxDolProfileFieldsArea( $iAreaID, $this );
			
			$sCacheString .= $oArea -> getCacheString();
		}
		
		$sCacheString .= ");\n";
		
		
		$iRes = fwrite( $rCacheFile, $sCacheString );
		
		fclose( $rCacheFile );
		
		if( $iRes === false ) {
			echo '<br /><b>Warning!</b> Cannot write to Profile Fields cache file (' . $this -> sCacheFile . ').';
			return false;
		}
		
		return true;
	}
	
}

/*
	ProfileFieldsArea
	Used primarily to create cache strings
*/

class BxDolProfileFieldsArea {
	
	var $id;
	var $oParent;
	var $sTitle;
	var $sBlockCol;
	var $sOrderCol;
	var $aBlocks;
	var $aPages;
	
	function BxDolProfileFieldsArea( $iAreaID, &$oParent ) {
		$this -> id = $iAreaID;
		$this -> oParent = &$oParent;
		
		$this -> sTitle         = $this -> oParent -> aAreasProps[ $this -> id ]['Title'];
		$this -> sBlockCol      = $this -> oParent -> aAreasProps[ $this -> id ]['Block'];
		$this -> sOrderCol      = $this -> oParent -> aAreasProps[ $this -> id ]['Order'];
		$this -> sAddSelect     = $this -> oParent -> aAreasProps[ $this -> id ]['AddSelect'];
		$this -> aAddBlockProps = $this -> oParent -> aAreasProps[ $this -> id ]['AddBlockProps'];
		$this -> aAddProps      = $this -> oParent -> aAreasProps[ $this -> id ]['AddProps'];
	}
	
	function getCacheString() {
		
		$sCacheString = "\n  //{$this -> sTitle}\n  {$this -> id} => array(\n"; //!pasd
		
		if( $this -> id == 1 ) {
			$this -> aPages = $this -> getJoinPages();
			
			$sCacheString .= "    //pages\n";
			foreach( $this -> aPages as $iPage ){
				$this -> aBlocks = $this -> getBlocks( "`JoinPage` = $iPage" );
				
				$sCacheString .= "    $iPage => array(\n"; //!pasd
					$sCacheString .= $this -> getBlocksCacheString( '  ' );
				$sCacheString .= "    ),\n";
			}
		} else {
			if( $this -> id == 100 or $this -> id == 101 )
				$this -> aBlocks = array( 0 => '' );
			else
				$this -> aBlocks = $this -> getBlocks();
			
			$sCacheString .= $this -> getBlocksCacheString();
		}
		
		$sCacheString .= "  ),\n";
		
		return $sCacheString;
	}
	
	function getBlocksCacheString( $sPrefix = '' ) {
		$sCacheString = "$sPrefix    //blocks\n";
		
		foreach ($this -> aBlocks as $iBlockID => $sBlockName) {
			$sBlockName = $this -> addSlashes( $sBlockName );
			$sCacheString .= "$sPrefix    $iBlockID => array(\n"; //!pasd
			$sCacheString .= "$sPrefix      //block properties\n";
			
			// add additional properties
			if( is_array($this -> aAddBlockProps) )
				foreach ($this -> aAddBlockProps as $sProp => $sValue) {
					$sPropValue = str_replace( '{Name}', $sBlockName, $sValue );
					$sCacheString .= "$sPrefix      '$sProp' => '$sPropValue',\n";
				}
			
			//process items
			$aItems = $this -> getItemsOfBlock($iBlockID);
			
			$sCacheString .= "$sPrefix      'Items' => array(\n";
			foreach ($aItems as $iBlockID => $aItem) {
				$sCacheString .= "$sPrefix        $iBlockID => array(\n"; //!pasd
				$sCacheString .= "$sPrefix          //item properties\n";
				
				// add additional properties
				if( is_array($this -> aAddProps) )
					foreach ($this -> aAddProps as $sProp => $sValue) {
						$aItem[ $sProp ] = str_replace( '{Name}', $aItem['Name'], $sValue );
					}
				
				foreach ($aItem as $sProp => $sValue) {
					if( $sProp == 'ID' )
						continue; //do not process ID it is already in key
					
					$sCacheString .= "$sPrefix          '$sProp' => " . $this -> processThisFuckingValue( $sProp, $sValue, "$sPrefix          " ) . ",\n";
				}
				
				$sCacheString .= "$sPrefix        ),\n";
			}
			
			$sCacheString .= "$sPrefix      ),\n"; //close items
			$sCacheString .= "$sPrefix    ),\n"; //close block
		}
		
		return $sCacheString;
	}
	
	function processThisFuckingValue( $sProp, $sValue, $sPrefix = '' ) {
		if( is_null( $sValue ) )
			return 'null'; //just a null
		
		switch( $sProp ) {
			case 'Name':
			case 'Type':
			case 'Caption':
			case 'Desc':
			case 'MandatoryMsg':
			case 'MinMsg':
			case 'MaxMsg':
			case 'UniqueMsg':
			case 'CheckMsg':
				return "'$sValue'"; // string in single quotes, simple text (without quotes)
			case 'Min':
			case 'Max':
			case 'MatchPercent':
			case 'MatchField':
				return "$sValue"; //integer
			case 'Mandatory':
			case 'Unique':
				return ( $sValue == '1' ? 'true' : 'false' ); //boolean
			case 'Values':
				if( $sValue == '' )
					return "''";
				elseif( substr( $sValue, 0, 2) == '#!' )
					return '"' . $this -> addSlashesDblQuot( $sValue ) . '"'; //string in double quotes
				else {
					// WOOW! Lets make it array! >:-E
					$aValues = explode( "\n", $sValue );
					
					$sRet = "array(\n";
					
					foreach( $aValues as $iKey => $sValue1 ) {
						$sValue1 = $this -> addSlashes( $sValue1 );
						$sRet .= "$sPrefix  v$iKey => '$sValue1',\n";
					}
					
					$sRet .= "$sPrefix)";
					
					return $sRet;
				}
			default:
				return '"' . $this -> addSlashesDblQuot( $sValue ) . '"'; //string in double quotes
		}
	}
	
	function addSlashes( $sText ) {
		$sText = str_replace( "\\", "\\\\", $sText );
		$sText = str_replace( "'", "\\'", $sText );
		
		return $sText;
	}
	
	function addSlashesDblQuot( $sText ){
		$sText = str_replace( '\\',  '\\\\', $sText );
		$sText = str_replace( '"',   '\"',   $sText );
		$sText = str_replace( '$',   '\$',   $sText );
		$sText = str_replace( "\r",  '\r',   $sText );
		$sText = str_replace( "\n",  '\n',   $sText );
		$sText = str_replace( "\t",  '\t',   $sText );
		$sText = str_replace( "\x0", '\x0',  $sText );
		
		return $sText;
	}

	
	function getJoinPages() {
		$aPages = array();
		
		$sQuery = "
			SELECT
				DISTINCT `JoinPage`
			FROM `ProfileFields`
			WHERE
				`Type` = 'block' AND
				`JoinOrder` IS NOT NULL
		";
		
		$rPages = db_res( $sQuery );
		
		while( $aPage = mysql_fetch_assoc( $rPages ) ) {
			$aPages[] = (int)$aPage['JoinPage'];
		}
		
		return $aPages;
	}
	
	//`JoinPage` = $iPage
	function getBlocks( $sAddSort = '1' ) {
		$aBlocks = array();
		
		$sQuery = "
			SELECT
				`ID`,
				`Name`
			FROM `ProfileFields`
			WHERE
				`Type` = 'block' AND
				`{$this -> sOrderCol}` IS NOT NULL AND
				$sAddSort
			ORDER BY
				`{$this -> sOrderCol}`
		";
		
		$rBlocks = db_res( $sQuery );
		
		while( $aBlock = mysql_fetch_assoc( $rBlocks ) ) {
			$aBlocks[ $aBlock['ID'] ] = $aBlock['Name'];
		}
		
		return $aBlocks;
	}
	
	function getItemsOfBlock( $iBlockID ) {
		$aItems = array();
		
		$sAddSelect = '`' . str_replace( ',', '`, `', $this -> sAddSelect ) . '`';
		
		if( $this -> id == 100 )
			$sWhere = '1';
		elseif( $this -> id == 101 )
			$sWhere = "`MatchField` != ''";
		else
			$sWhere = "`{$this -> sBlockCol}` = $iBlockID AND `{$this -> sOrderCol}` IS NOT NULL";
		
		$sOrderCol = isset( $this -> sOrderCol ) ? $this -> sOrderCol : 'ID';
		
		$sQuery = "
			SELECT
				`ID`,
				`Name`,
				`Type`,
				$sAddSelect
			FROM
				`ProfileFields`
			WHERE
				`Type` != 'block' AND
				$sWhere
			ORDER BY
				`$sOrderCol`
		";
		
		$rItems = db_res( $sQuery );
		
		while( $aItem = mysql_fetch_assoc($rItems) ) {
			$aItems[ $aItem['ID'] ] = $aItem;
		}
		
		return $aItems;
	}
}
