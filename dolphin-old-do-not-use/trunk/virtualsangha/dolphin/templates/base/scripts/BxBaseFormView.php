<?php

class BxBaseFormView {
	var $sCode; // code of this form
	var $sName; // form name
	var $iColsNum = 1; // number of columns of form (ex., for couple join and edit)
	var $bSecondEnabled = false; // defines if second profile column is enabled (in case profile type is single)
	
	function BxBaseFormView( $sName ) {
		$this -> sName = $sName;
		
	}
	
	function begin( $aFormAttrs = null, $aTableAttrs = null, $aFormParams = null, $aTableParams = null ) {
		
		if( $aFormAttrs   == null ) $aFormParams  = array();
		if( $aTableAttrs  == null ) $aFormParams  = array();
		if( $aFormParams  == null ) $aFormParams  = array();
		if( $aTableParams == null ) $aTableParams = array();
		
		// detect columns number
		if( isset( $aTableParams['double'] ) and $aTableParams['double'] )
			$this -> iColsNum = 2;
		
		// detect if second column enabled
		if( $this -> iColsNum == 2 and isset( $aTableParams['second_enabled'] ) and $aTableParams['second_enabled'] )
			$this -> bSecondEnabled = true;
		
		$sFormAttrs  = $this -> processFormAttrs( $aFormAttrs );
		$sTableAttrs = $this -> processFormTableAttrs( $aTableAttrs );
		
		$this -> genFormBegin( $sFormAttrs, $aFormParams );
		if( isset( $aFormParams['hidden'] ) and is_array( $aFormParams['hidden'] ) )
			$this -> insertHiddenFields( $aFormParams['hidden'] );
			
		$this -> genFormTableBegin( $sTableAttrs, $aTableParams );
		
		if( isset( $aTableParams['headers'] ) and is_array( $aTableParams['headers'] ) )
			$this -> insertTableHeaders( $aTableParams['headers'], $aTableParams['headers_add'] );
	}
	
	function insertHiddenFields( $aFields ) {
		foreach( $aFields as $sKey => $sValue )
			$this -> sCode .= '<input type="hidden" name="' . $sKey . '" value="' . $this -> value2html( $sValue ) . '" />';
	}
	
	function insertTableHeaders( $aHeaders, $sAdd ) {
		$this -> sCode .= '<tr class="headers">';
		
		for( $iInd = 0; $iInd <= $this -> iColsNum; $iInd ++ ) {
			$this -> sCode .=
			  "<th $sAdd>" .
			    $this -> value2html( $aHeaders[$iInd], true ) .
			  '</th>';
		}
		
		$this -> sCode .= '</tr>';
	}
	
	function processFormAttrs( $aFormAttrs ) {
		$sFormAttrs = '';
		
		// set default form attributes
		if( !isset( $aFormAttrs['method'] ) )
			$aFormAttrs['method'] = 'post';
		
		if( !isset( $aFormAttrs['action'] ) )
			$aFormAttrs['action'] = $_SERVER['PHP_SELF'];
		
		if( !isset( $aFormAttrs['enctype'] ) )
			$aFormAttrs['enctype'] = 'multipart/form-data';
		
		//add name
		$aFormAttrs['name'] = $this -> sName;
		
		//process form attributes
		foreach( $aFormAttrs as $sKey => $sValue )
			$sFormAttrs .= ' ' . $sKey . '="' . $this -> value2html( $sValue ) . '"'; // ' method="post"'
		
		return $sFormAttrs;
	}
	
	function processFormTableAttrs( $aTableAttrs ) {
		$sTableAttrs = '';
		
		// set default table attributes
		if( !isset( $aTableAttrs['cellspacing'] ) )
			$aTableAttrs['cellspacing'] = '0';
		
		if( !isset( $aTableAttrs['cellpadding'] ) )
			$aTableAttrs['cellpadding'] = '0';
		
		if( !isset( $aTableAttrs['border'] ) )
			$aTableAttrs['border'] = '0';
		
		//process table attributes
		foreach( $aTableAttrs as $sKey => $sValue )
			$sTableAttrs .= ' ' . $sKey . '="' . $this -> value2html( $sValue ) . '"'; // ' method="post"'
		
		return $sTableAttrs;
	}
	
	function value2html( $sText, $bNoEmpty = false ) {
		$sRet = htmlspecialchars( $sText );
		
		/*if( $sText && !$sRet ) {
			$aDbg = debug_backtrace();
			foreach( $aDbg as $sKey => $aValue ) {
				unset( $aDbg[$sKey]['object'] );
			}
			echoDbg( $aDbg );
		}*/
		
		if( $sRet === '' and $bNoEmpty )
			$sRet = '&nbsp;';
		return $sRet;
	}
	
	function end( $aButtons ) {
		$this -> genFormTableEnd( $aButtons );
		$this -> genFormEnd();
	}
	
	function getCode() {
		return $this -> sCode;
	}
	
	function genFormBegin( $sFormAttrs ) {
		$this -> sCode .= "<form $sFormAttrs>";
		ob_start();
		?>
		<script type="text/javascript" language="javascript">
			$(document).ready( function(){ //onload
				
				//apply counters to textareas
				$( 'textarea.input_area' ).each( function() {
					function setCounter() {
						if( $area.val() )
							$counter.show( 300 );
						else
							$counter.hide( 300 );
						
						$counterCont.html( $area.val().length );
					}
					
					var $area = $(this);
					$area.after(
						'<div class="counter" style="display:none;"><?=_t( '_Counter' )?>: <b></b></div>'
					);
					
					var $counter = $area.siblings('div.counter')
					var $counterCont = $counter.children('b');
					
					setCounter();
					$area.change( setCounter ).keyup( setCounter );
				} );
			} );
		</script>
		<?
		$this -> sCode .= ob_get_clean();
	}
	
	function genFormEnd() {
		$this -> sCode .= "</form>";
	}
	
	function genFormTableBegin( $sTableAttrs ) {
		$this -> sCode .= "<table $sTableAttrs>";
	}
	
	function genFormTableEnd( $aButtons ) {
		$this -> sCode .= '<tr><th class="bottom_controls" colspan="' . ( $this -> iColsNum + 1 ) . '">';
		$this -> insertBottomButtons( $aButtons );
		$this -> sCode .= '</th></tr>';
		$this -> sCode .= "</table>";
	}
	
	function insertBottomButtons( $aButtons ) {
		foreach ($aButtons as $sInd => $aButton) {
			$sAttrs = '';
			foreach ($aButton as $sKey => $sValue)
				$sAttrs .= ' ' . $sKey . '="' . $this -> value2html( $sValue ) . '"';
			
			$this -> sCode .= "<input $sAttrs />";
		}
	}
	
	function addRow( $aCol0, $aCol1 = null, $bCycled = false ) {
		$this -> sCode .= "<tr>\n";
		
		if( $aCol0['Type'] == 'system' and $aCol0['Name'] == 'TermsOfUse' ) {
			$this -> insertInputCol( $aCol0, 5 ); //special
		} else {
			$this -> insertRowCaption( $aCol0['Caption'], $aCol0['Desc'], $aCol0['Mandatory'] );
			
			if( $this -> iColsNum == 1 ) {
				// single
				$this -> insertInputCol( $aCol0, 0 );
				$aCol1 = null;
			} else {
				if( !is_array( $aCol1 ) )
					// mutual
					$this -> insertInputCol( $aCol0, 1 );
				else {
					// double
					$this -> insertInputCol( $aCol0, 2 );
					// second
					$this -> insertInputCol( $aCol1, 3 );
				}
			}
		}
		
		$this -> sCode .= "</tr>\n";
		
		
		
		if( $aCol0['Type'] == 'pass' and !$bCycled ) { //insert confirm password field
			
			$aColConf0 = $aCol0;
			$aColConf1 = $aCol1;
			
			$aColConf0['Name'] = str_replace( '[0]', '_confirm[0]', $aColConf0['Name'] );
			$aColConf0['Caption'] = _t( '_Confirm password' );
			$aColConf0['Desc'] = _t( '_Confirm password descr' );
			
			if( is_array( $aColConf1 ) ) {
				$aColConf1['Name'] = str_replace( '[1]', '_confirm[1]', $aColConf1['Name'] );
			}
			
			$this -> addRow( $aColConf0, $aColConf1, true );
		}
	}
	
	function insertRowCaption( $sCaption, $sDesc, $bMandatory ) {
		$this -> sCode .=
			'<th class="label">' . 
				$sCaption . ': ' .
				( $bMandatory ? '<span class="form_mandatory">*</span>' : '' ) .
				$this -> insertDescription( $sDesc ) .
			"</th>\n";
	}
	
	function insertDescription( $sDesc ) {
		if( empty( $sDesc ) )
			return '';
		
		$sDesc = str_replace( "'",  "\\'", $sDesc );
		$sDesc = str_replace( "\n", "\\n", $sDesc );
		$sDesc = str_replace( "\r", "",    $sDesc );
		
		return '
			<img class="form_info_icon" src="' . getTemplateIcon( 'info.gif' ) . '"
			  onmouseover="showFloatDesc(\'' . $this -> value2html( $sDesc ) . '\');"
			  onmousemove="moveFloatDesc( event )" onmouseout="hideFloatDesc()" />';
	}
	
	function beginBlock( $sCaption ) {
		$this -> sCode .= '<tr><th class="block" colspan="' . ( $this -> iColsNum + 1 ) . '">' . $this -> value2html( $sCaption ) . '</th></tr>';
	}
	
	function endBlock() {
		
	}
	
	function insertInputCol( $aInput, $iType = 0 ) {
		switch( $iType ) {
			case 0: //simple single column
			case 2: //first of double
				$this -> sCode .= '<td>';
			break;
			
			case 1: //mutual column
				$this -> sCode .= '<td colspan="2">';
			break;
			
			case 3: //second of double
				$this -> sCode .= '<td class="form_second_col"' . ( $this -> bSecondEnabled ? '' : ' style="display: none;"' ) . '>';
			break;
			
			case 5: //special - full row
				$this -> sCode .= '<td colspan="3" class="form_row_special">';
			break;
		}
		
		$this -> insertInput( $aInput );
		
		$this -> insertFieldErrorIcon( $aInput['Error'] );
		
		$this -> sCode .= '</td>';
	}
	
	function insertFieldErrorIcon( $sError = '' ) {
		
		$this -> sCode .= '<img src="' . getTemplateIcon( 'warn.gif' ) . '" class="form_warn_icon"';
		
		if( $sError ) {
			$sError = str_replace( "'",  "\\'", $sError );
			$sError = str_replace( "\n", "\\n", $sError );
			$sError = str_replace( "\r", "",    $sError );
			
			$this -> sCode .= ' onmouseover="showFloatDesc(\'' . $this -> value2html( $sError ) . '\')"';
		} else {
			$this -> sCode .= ' style="display: none;"';
		}
		
		$this -> sCode .= ' onmousemove="moveFloatDesc(event)" onmouseout="hideFloatDesc()" />';
	}
	
	function insertInput( $aInput ) {
		global $site;
		
		$sCode = '';
		switch( $aInput['Type'] ) {
			case 'text':
				$sCode = '<input type="text" class="input_text" name="' . $aInput['Name'] . '" value="' . $this ->value2html( $aInput['Value'] ) . '" />';
			break;
			
			case 'area':
				$sCode = '
					<textarea class="input_area" name="' . $aInput['Name'] . '">' .
						$this -> value2html( $aInput['Value'] ) .
					'</textarea>';
			break;
			
			case 'pass':
				$sCode = '<input type="password" class="input_pass" name="' . $aInput['Name'] . '" />';
			break;
			
			case 'date':
				$sCode = '<input type="text" class="input_date" name="' . $aInput['Name'] . '" value="' . $this ->value2html( $aInput['Value'] ) . '" />';
			break;
			
			case 'select_one':
				$this -> insertSelectOne( $aInput );
			break;
			
			case 'select_set':
				$this -> insertSelectSet( $aInput );
			break;
			
			case 'num':
				$sCode = '<input type="text" class="input_num" name="' . $aInput['Name'] . '" value="' . $this ->value2html( $aInput['Value'] ) . '" />';
			break;
			
			case 'range':
				$sCode  = '<input type="text" class="input_range_0" name="' . $aInput['Name'] . '[0]" value="' . $this ->value2html( $aInput['Value'][0] ) . '" />';
				$sCode .= ' - ';
				$sCode .= '<input type="text" class="input_range_1" name="' . $aInput['Name'] . '[1]" value="' . $this ->value2html( $aInput['Value'][1] ) . '" />';
			break;
			
			case 'bool':
				$sCode = '
					<input type="checkbox" name="' . $aInput['Name'] .'" value="yes"' .
					  ( $aInput['Value'] ? ' checked="checked"' : '' ) . ' />';
			break;
			
			case 'system':
				switch( $aInput['Name'] ) {
					case 'Couple':
						$sCode =
							'<select name="Couple" class="input_select_couple" onchange="doShowHideSecondProfile( this.value, this.form )">' .
								'<option value="no"'  . ( $aInput['Value'] ? '' : ' selected="selected"' ) . '>' . _t( '_Single' ) . '</option>' .
								'<option value="yes"' . ( $aInput['Value'] ? ' selected="selected"' : '' ) . '>' . _t( '_Couple' ) . '</option>' .
							'</select>';
					break;
					
					case 'Captcha':
						$sCode =
							'<img src="' . $site['url'] . 'simg/simg.php" class="form_captcha" /><br /><br />
							<input type="text" class="input_text" maxlength="6" name="Captcha" />';
					break;
					
					case 'Status':
						$this -> insertSelectOne( $aInput, false );
					break;
					
					case 'Featured':
						$aInput['Type'] = 'bool';
						$this -> insertInput( $aInput );
					break;
					
					case 'TermsOfUse':
						$sCode = '
							
							<input type="checkbox" name="TermsOfUse" value="yes" id="TermsOfUse_agree" />
							<label for="TermsOfUse_agree">
								' . $aInput['Caption'] . '
							</label>';
					break;
					
					default:
						$sCode = 'rr';
				}
			break;
			default:
				$sCode = 'You fucking my brain?';
		}
		
		$this -> sCode .= $sCode;
	}
	
	function insertSelectOne( $aInput, $bAddFirst = true ) {
		if( $bAddFirst )
			$aValues = array( '' => '_Select it' );
		
		if( is_array( $aInput['Values'] ) ) {
			foreach( $aInput['Values'] as $sKey )
				$aValues[$sKey] = "_$sKey";
		} else {
			$aValues += $this -> getPredefList( $aInput['Values'], $aInput['UseLKey'] );
		}
		
		switch( $aInput['Control'] ) {
			case 'select':
				$sCode = '<select class="input_select" name="' . $aInput['Name'] . '">';
					foreach( $aValues as $sKey => $sValue ) {
						$sSelected = ( $aInput['Value'] == $sKey ) ? ' selected="selected"' : '';
						$sCode .= '
							<option value="' . $this -> value2html( $sKey ) . '"' . $sSelected . '>' .
								$this -> value2html( _t( $sValue ) ) .
							'</option>';
					}
				$sCode .= '</select>';
			break;
			
			case 'radio':
				$sCode = '';
				foreach( $aValues as $sKey => $sValue ) {
					if( $sKey === '' )
						continue;
					
					$sSelected = ( $aInput['Value'] == $sKey ) ? ' checked="checked"' : '';
					$sID = $aInput['Name'] . '_' . $sKey;
					$sCode .= '
						<input type="radio" class="input_radio"
						  value="' . $this -> value2html( $sKey ) . '"
						  ' . $sSelected . '
						  id="' . $sID . '"
						  name="' . $aInput['Name'] . '" />
						<label class="input_radio_label" for="' . $sID . '">' . _t( $sValue ) . '</label>';
				}
			break;
		}
		
		$this -> sCode .= $sCode;
	}
	
	function insertSelectSet( $aInput ) {
		if( is_array( $aInput['Values'] ) ) {
			foreach( $aInput['Values'] as $sKey )
				$aValues[$sKey] = "_$sKey";
		} else {
			$aValues = $this -> getPredefList( $aInput['Values'], $aInput['UseLKey'] );
		}
		
		if( !is_array($aInput['Value']) )
			$aInput['Value'] = array();
		
		switch( $aInput['Control'] ) {
			case 'select':
				$sCode = '<select class="input_select_multi" multiple="multiple" name="' . $aInput['Name'] . '[]">';
					foreach( $aValues as $sKey => $sValue ) {
						$sSelected = in_array( $sKey, $aInput['Value'] ) ? ' selected="selected"' : '';
						$sCode .= '
							<option value="' . $this -> value2html( $sKey ) . '"' . $sSelected . '>' .
								$this -> value2html( _t( $sValue ) ) .
							'</option>';
					}
				$sCode .= '</select>';
			break;
			
			case 'checkbox':
				$sCode = '';
				foreach( $aValues as $sKey => $sValue ) {
					$sSelected = in_array( $sKey, $aInput['Value'] ) ? ' checked="checked"' : '';
					$sID = $aInput['Name'] . '_' . $sKey;
					$sCode .= '
						<input type="checkbox" class="input_checkbox"
						  value="' . $this -> value2html( $sKey ) . '"
						  ' . $sSelected . '
						  id="' . $sID . '"
						  name="' . $aInput['Name'] . '[]" />
						<label class="input_radio_label" for="' . $sID . '">' . _t( $sValue ) . '</label>';
				}
			break;
		}
		
		$this -> sCode .= $sCode;
	}
	
	function getPredefList( $sKey, $sUseLKey = 'LKey' ) {
		global $aPreValues;
		
		if( substr( $sKey, 0, 2 ) == '#!' )
			$sKey = substr( $sKey, 2 );
		
		$aList = array();
		
		if( !isset( $aPreValues[$sKey] ) )
			return $aList;
		
		foreach( $aPreValues[$sKey] as $sVal => $aVal ) {
			if( !isset( $aVal[$sUseLKey] ) )
				$sUseLKey = 'LKey';
			
			$aList[ $sVal ] = $aVal[ $sUseLKey ];
		}
		
		return $aList;
	}
}
