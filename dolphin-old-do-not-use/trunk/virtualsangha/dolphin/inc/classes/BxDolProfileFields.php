<?php

require_once( 'BxDolPFM.php' );
require_once( $dir['plugins'] . 'Services_JSON.php' );
require_once( $dir['classes'] . 'Thing.php' );

class BxDolProfileFields {
	var $iAreaID;
	var $sCacheFile; // path to cache file
	var $aArea; // just a cache array
	var $aBlocks; // array of current blocks
	var $aCache; // full cache of profile fields
	var $aCoupleMutual; //couple mutual fields
	
	function BxDolProfileFields( $iAreaID ) {
		$this -> iAreaID = $iAreaID;
		
		$this -> sCacheFile = BX_DIRECTORY_PATH_INC . 'db_cached/ProfileFields.inc';
		
		if( !$this -> loadCache() )
			return false;
		
		
	}
	
	function loadCache( $bCycle = true ) {
		if(
			!file_exists( $this -> sCacheFile ) or
			!$sCache = file_get_contents( $this -> sCacheFile ) or
			!$this -> aCache = eval( $sCache ) or
			!is_array($this -> aCache)
		) {
			$oPFM = new BxDolPFMCacher();
			
			if( !$oPFM -> createCache() )
				return false;
			
			if( $bCycle ) //to prevent cycling
				return $this -> loadCache( false ); // try againg
			else
				return false;
		}
		
		$this -> aArea = $this -> aCache[ $this -> iAreaID ];
		
		//load blocks
		$this -> aBlocks = $this -> aArea;
		
		//get mutual fields
		$this -> _getCoupleMutualFields();
		
		return true;
	}
	
	function genJsonErrors( $aErrors, $bCouple ) {
		$aJsonErrors = array();
		
		$aJsonErrors[0] = $aErrors[0];
		if( $bCouple )
			$aJsonErrors[1] = $aErrors[1];
		
		$oParser = new Services_JSON();
		return $oParser -> encode( $aJsonErrors );
	}
	
	
	//sets to $Errors intuitive array
	function processPostValues( $bCouple, &$aValues, &$aErrors, $iPage = 0, $iProfileID = 0 ) {
		$iHumans = $bCouple ? 2 : 1; // number of members in profile (single/couple), made for double arrays
		
		if( $this -> iAreaID == 1 ) // join
			$this -> aBlocks = $this -> aArea[$iPage];
		
		foreach( $this -> aBlocks as $iBlockID => $aBlock ) {
			$aItems = $aBlock['Items'];
			foreach ($aItems as $iItemID => $aItem) {
				$sItemName = $aItem['Name'];
				
				for( $iHuman = 0; $iHuman < $iHumans; $iHuman ++ ) {
					if( $iHuman == 1 and in_array( $sItemName, $this -> aCoupleMutual ) )
						continue;
					
					$mValue = null;
					switch( $aItem['Type'] ) {
						case 'text':
						case 'area':
						case 'pass':
						case 'select_one':
							if( isset( $_POST[$sItemName] ) and isset( $_POST[$sItemName][$iHuman] ) )
								$mValue = process_pass_data( $_POST[$sItemName][$iHuman] );
						break;
						
						case 'bool':
							if( isset( $_POST[$sItemName] ) and isset( $_POST[$sItemName][$iHuman] ) and $_POST[$sItemName][$iHuman] == 'yes' )
								$mValue = true;
							else
								$mValue = false;
						break;
						
						case 'num':
							if( isset( $_POST[$sItemName] ) and isset( $_POST[$sItemName][$iHuman] ) and trim( $_POST[$sItemName][$iHuman] ) !== '' )
								$mValue = (int)trim( $_POST[$sItemName][$iHuman] );
						break;
						
						case 'date':
							if( isset( $_POST[$sItemName] ) and isset( $_POST[$sItemName][$iHuman] ) and trim( $_POST[$sItemName][$iHuman] ) !== '' ) {
								list( $iDay, $iMonth, $iYear ) = explode( '/', $_POST[$sItemName][$iHuman] );
								
								$iDay   = (int)$iDay;
								$iMonth = (int)$iMonth;
								$iYear  = (int)$iYear;
								
								$mValue = "$iDay/$iMonth/$iYear";
							}
						break;
						
						case 'select_set':
							$mValue = array();
							if( isset( $_POST[$sItemName] ) and isset( $_POST[$sItemName][$iHuman] ) and is_array( $_POST[$sItemName][$iHuman] ) ) {
								foreach ($_POST[$sItemName][$iHuman] as $sValue ) {
									$mValue[] = process_pass_data( $sValue );
								}
							}
						break;
						
						case 'range':
							if( isset( $_POST[$sItemName] ) and isset( $_POST[$sItemName][$iHuman] ) and is_array( $_POST[$sItemName][$iHuman] ) ) {
								$aRange = $_POST[$sItemName][$iHuman];
								$mValue = array( null, null );
								
								$aRange[0] = isset( $aRange[0] ) ? trim( $aRange[0] ) : '';
								$aRange[1] = isset( $aRange[1] ) ? trim( $aRange[1] ) : '';
								
								if( $aRange[0] !== '' )
									$mValue[0] = (int)$aRange[0];
								
								if( $aRange[1] !== '' )
									$mValue[1] = (int)$aRange[1];
							}
						break;
						
						case 'system':
							switch( $aItem['Name'] ) {
								case 'Couple':
								case 'TermsOfUse':
								case 'Featured': //they are boolean
									if( isset( $_POST[$sItemName] ) and $_POST[$sItemName] == 'yes' )
										$mValue = true;
									else
										$mValue = false;
								break;
								
								case 'Captcha':
								case 'Status': // they are select_one
									if( isset( $_POST[$sItemName] ) )
										$mValue = process_pass_data( $_POST[$sItemName] );
								break;
							}
						break;
					}
					
					$rRes = $this -> checkPostValue( $iBlockID, $iItemID, $mValue, $iHuman, $iProfileID );
					
					if( $rRes !== true )
						$aErrors[$iHuman][$sItemName] = $rRes; //it is returned error text
					
					
					
					//if password on edit page
					if( $aItem['Type'] == 'pass' and ( $this -> iAreaID == 2 or $this -> iAreaID == 3 or $this -> iAreaID == 4 ) ) {
						if( empty($mValue) )
							$mValue = $aValues[$iHuman][$sItemName];
						else
							$mValue = md5( $mValue );
					}
					
					$aValues[$iHuman][$sItemName] = $mValue;
				}
			}
		}
	}
	
	function checkPostValue( $iBlockID, $iItemID, $mValue, $iHuman, $iProfileID ) {
		// get item
		$aItem = $this -> aBlocks[$iBlockID]['Items'][$iItemID];
		if( !$aItem )
			return 'Item not found';
		
		$aChecks = array(
			'text' => array( 'Mandatory', 'Min', 'Max', 'Unique', 'Check' ),
			'area' => array( 'Mandatory', 'Min', 'Max', 'Unique', 'Check' ),
			'pass' => array( 'Mandatory', 'Min', 'Max', 'Check', 'PassConfirm' ),
			'date' => array( 'Mandatory', 'Min', 'Max', 'Check' ),
			'select_one' => array( 'Min', 'Max', 'Mandatory', 'Values', 'Check' ),
			'select_set' => array( 'Min', 'Max', 'Mandatory', 'Values', 'Check' ),
			'num'    => array( 'Mandatory', 'Min', 'Max', 'Unique', 'Check' ),
			'range'  => array( 'Mandatory', 'RangeCorrect', 'Min', 'Max', 'Check' ),
			'system' => array( 'System' ),
			'bool'   => array( 'Mandatory' )
		);
		
		$aMyChecks = $aChecks[ $aItem['Type'] ];
		
		foreach ($aMyChecks as $sCheck ) {
			$sFunc = 'checkPostValueFor' . $sCheck;
			
			$mRes = $this -> $sFunc( $aItem, $mValue, $iHuman, $iProfileID );
			
			if( $mRes !== true ) {
				if( is_bool( $mRes ) ) // it is false...
					return _t( $aItem[ $sCheck . 'Msg' ], $aItem[$sCheck] );
				else
					return $mRes; // returned as text
			}
		}
		
		return true;
	}
	
	function checkPostValueForPassConfirm( $aItem, $mValue, $iHuman ) {
		$sConfPass = process_pass_data( $_POST[ "{$aItem['Name']}_confirm" ][$iHuman] );
		if( $sConfPass != $mValue )
			return _t( '_Password confirmation failed' );
		else
			return true;
	}
	
	function checkPostValueForRangeCorrect( $aItem, $mValue ) {
		if( is_null($mValue[0]) or is_null($mValue[1]) )
			return true; // if not set, pass this check
		
		if( $mValue[0] > $mValue[1] )
			return _t( '_First value must be bigger' );
		
		return true;
	}
	
	function checkPostValueForMin( $aItem, $mValue ) {
		$iMin = $aItem['Min'];
		if( is_null($iMin) )
			return true;
		
		switch( $aItem['Type'] ) {
			case 'text':
			case 'area':
				if( strlen( $mValue ) < $iMin )
					return false;
			break;
			
			case 'pass':
				if( strlen( $mValue ) > 0 and strlen( $mValue ) < $iMin )
					return false;
			break;
			
			case 'num':
				if( $mValue < $iMin )
					return false;
			break;
			
			case 'date':
				if( $this -> getAge($mValue) < $iMin )
					return false;
			break;
			
			case 'range':
				if( $mValue[0] < $iMin || $mValue[1] < $iMin )
					return false;
			break;
			
			case 'select_set':
				if( count( $mValue ) < $iMin )
					return false;
			break;
		}
		
		return true;
	}
	
	function checkPostValueForMax( $aItem, $mValue ) {
		$iMax = $aItem['Max'];
		if( is_null($iMax) )
			return true;
		
		switch( $aItem['Type'] ) {
			case 'text':
			case 'area':
			case 'pass':
				if( strlen( $mValue ) > $iMax )
					return false;
			break;
			
			case 'num':
				if( $mValue > $iMax )
					return false;
			break;
			
			case 'date':
				if( $this -> getAge($mValue) > $iMax )
					return false;
			break;
			
			case 'range':
				if( $mValue[0] > $iMax || $mValue[1] > $iMax )
					return false;
			break;
			
			case 'select_set':
				if( count( $mValue ) > $iMax )
					return false;
			break;
		}
		
		return true;
	}
	
	function checkPostValueForUnique( $aItem, $mValue, $iHuman, $iProfileID ) {
		global $logged;
		
		if( !$aItem['Unique'] )
			return true;
		
		$iProfileID = (int)$iProfileID;
		if( $iProfileID ) {
			$sAdd = "AND `ID` != $iProfileID";
		} else
			$sAdd = '';
		
		$mValue_db = addslashes( $mValue );
		$sQuery = "SELECT COUNT(*) FROM `Profiles` WHERE `{$aItem['Name']}` = '$mValue_db' $sAdd";
		if( (int)db_value( $sQuery ) )
			return false;
		
		return true;
	}
	
	function checkPostValueForCheck( $aItem, $mValue ) {
		$sCheck = $aItem['Check'];
		if( empty($sCheck) )
			return true;
		
		$sFunc = create_function( '$arg0', $sCheck );
		
		if( !$sFunc( $mValue ) )
			return false;
		
		return true;
	}
	
	function checkPostValueForMandatory( $aItem, $mValue ) {
		if( !$aItem['Mandatory'] )
			return true;
		
		if( $aItem['Type'] == 'num' ) {
			if( is_null($mValue) )
				return false;
		} elseif( $aItem['Type'] == 'range' ) {
			if( is_null($mValue[0]) or is_null($mValue[1]) )
				return false;
		} elseif( $aItem['Type'] == 'pass' ) {
			if( $this -> iAreaID == 2 or $this -> iAreaID == 3 or $this -> iAreaID == 4 ) // if area is edit, non-mandatory
				return true;
			else
				if( empty($mValue) ) // standard check
					return false;
		} else {
			if( empty($mValue) )
				return false;
		}
		
		return true;
	}
	
	function checkPostValueForValues( $aItem, $mValue ) {
		if( empty($mValue) ) //it is not selected
			return true;
		
		if( is_array( $aItem['Values'] ) )
			$aValues = $aItem['Values'];
		else
			$aValues = $this -> getPredefinedKeysArr( $aItem['Values'] );
		
		if( !$aValues )
			return 'Cannot find list';
		
		if( $aItem['Type'] == 'select_one' ) {
			if( !in_array( $mValue, $aValues ) )
				return 'Value not in list. Hack attempt!';
		} elseif( $aItem['Type'] == 'select_set' ) {
			foreach( $mValue as $sValue )
				if( !in_array( $sValue, $aValues ) )
					return 'Value not in list. Hack attempt!';
		}
		
		return true;
	}
	
	function getPredefinedKeysArr( $sKey ) {
		global $aPreValues;
		
		if( substr( $sKey, 0, 2 ) == '#!' )
			$sKey = substr( $sKey, 2 );
		
		return @array_keys( $aPreValues[$sKey] );
	}
	
	function checkPostValueForSystem( $aItem, $mValue ) {
		
		switch( $aItem['Name'] ) {
			case 'Captcha':
				return ( $this -> checkCaptcha( $mValue ) ) ? true : _t( '_Captcha check failed' );
			break;
			
			case 'Status':
				if( !in_array($mValue, $aItem['Values'] ) )
					return 'Status hack attempt!';
			break;
			
			case 'TermsOfUse':
				return $mValue ? true : _t( '_You must agree with terms of use' );
			break;
		}
		
		return true;
	}
	
	function checkCaptcha( $mValue ) {
		if( $_COOKIE['strSec'] === md5( $mValue ) ) {
			return true;
		} else
			return false;
	}
	
	function getAge( $sBirthDate ) { // 28/10/1985
		$bd = explode( '/', $sBirthDate );
		foreach ($bd as $i => $v) $bd[$i] = (int)$v;
		
		if ( date('n') > $bd[1] || ( date('n') == $bd[1] && date('j') >= $bd[0] ) )
			$age = date('Y') - $bd[2];
		else
			$age = date('Y') - $bd[2] - 1;
		
		return $age;
	}
	
	// create intuitive array of values from default text profile array (getProfileInfo)
	function getValuesFromProfile( $aProfile ) {
		$aValues = array();
		
		foreach( $this -> aBlocks as $aBlock ) {
			foreach( $aBlock['Items'] as $aItem ) {
				$sItemName = $aItem['Name'];
				if( !array_key_exists( $sItemName, $aProfile ) )
					continue; //pass this
				
				$mValue = $aProfile[$sItemName];
				
				switch( $aItem['Type'] ) {
					case 'select_set':
						$mValue = explode( ',', $mValue );
					break;
					
					case 'range':
						$mValue = explode( ',', $mValue );
						foreach( $mValue as $iInd => $sValue )
							$mValue[$iInd] = (int)$sValue;
					break;
					
					case 'bool':
						$mValue = (bool)$mValue;
					break;
					
					case 'num':
						$mValue = (int)$mValue;
					break;
					
					case 'date':
						$aDate = explode( '-', $mValue ); //YYYY-MM-DD
						$mValue = (int)$aDate[2] . '/' . (int)$aDate[1] . '/' . $aDate[0];
					break;
					
					case 'system':
						switch( $sItemName ) {
							case 'Couple':
							case 'ID':
								$mValue = (int)$mValue;
							break;
							
							case 'Featured':
								$mValue = (bool)$mValue;
							break;
						}
					break;
				}
				
				$aValues[$sItemName] = $mValue;
			}
		}
		
		return $aValues;
	}
	
	// reverse of previous function. convert intuitive array to text array
	function getProfileFromValues( $aValues ) {
		$aProfile = array();
		
		foreach( $this -> aBlocks as $aBlock ) {
			foreach( $aBlock['Items'] as $aItem ) {
				$sItemName = $aItem['Name'];
				if( !array_key_exists( $sItemName, $aValues ) )
					continue; //pass this
				
				$mValue = $aValues[$sItemName];
				
				switch( $aItem['Type'] ) {
					case 'date':
						$aDate = explode( '/', $mValue );
						$mValue = sprintf( '%04d-%02d-%02d', $aDate[2], $aDate[1], $aDate[0] );
					break;
					
					//impl others
				}
				
				$aProfile[$sItemName] = $mValue;
			}
		}
		
		return $aProfile;
	}
	
	//internal function
	function _getCoupleMutualFields() {
		$aAllItems = $this -> aCache[100][0]['Items'];
		
		$this -> aCoupleMutual = array( 'NickName', 'Password', 'Email' );
		
		foreach( $aAllItems as $aItem ) {
			if( $aItem['Name'] == 'Couple' )
				$this -> aCoupleMutual = array_merge( $this -> aCoupleMutual, explode( "\n", $aItem['Extra'] ) ); // add specified values
			
			if( $aItem['Type'] == 'system' )
				$this -> aCoupleMutual[] = $aItem['Name'];
		}
		
		//echoDbg( $this -> aCoupleMutual );
	}
	
	//external function
	function getCoupleMutualFields() {
		return $this -> aCoupleMutual;
	}
	
	function getViewableValue( $aItem, $sValue ) {
		switch( $aItem['Type'] ) {
			case 'text':
			case 'num':
			case 'area':
				return htmlspecialchars_adv($sValue);
			
			case 'date':
				return $this -> getViewableDate( $sValue );
				
			case 'range':
				return htmlspecialchars_adv( strreplace( ',', ' - ',$sValue ) );
			
			case 'bool':
				return _t( $sValue ? '_Yes' : '_No' );
			
			case 'select_one':
				return $this -> getViewableSelectOne( $aItem['Values'], $sValue );
				
			case 'select_set':
				return $this -> getViewableSelectSet( $aItem['Values'], $sValue );
			
			
			case 'system':
				switch( $aItem['Name'] ) {
					case 'DateReg':
					case 'DateLastEdit':
					case 'DateLastLogin':
						return $this -> getViewableDate( $sValue );
						
					case 'Status':
						return _t( "_$sValue" );
					
					case 'ID':
						return $sValue;
					
					case 'Featured':
						return _t( $sValue ? '_Yes' : '_No' );
					
					default:
						return '&nbsp;';
				}
			break;
			
			case 'pass':
			default:
				return '&nbsp;';
		}
	}
	
	function getViewableDate( $sDate ) {
		return $sDate;
	}
	
	function getViewableSelectOne( $mValues, $sValue, $sUseLKey = 'LKey' ) {
		global $aPreValues;
		
		if( is_string($mValues) and substr($mValues, 0, 2) == '#!' ) {
			$sKey = substr($mValues, 2);
			
			if( !isset( $aPreValues[$sKey][$sUseLKey] ) )
				$sUseLKey = 'LKey';
			
			return htmlspecialchars_adv( _t( $aPreValues[$sKey][$sUseLKey] ) );
		} elseif( is_array($mValues) ) {
			if( in_array($sValue, $mValues) )
				return htmlspecialchars_adv( _t( "_$sValue" ) );
			else
				return '=wrong=';
		} else
			return '=wrong=';
	}
	
	function getViewableSelectSet( $mValues, $sValue, $sUseLKey = 'LKey' ) {
		global $aPreValues;
		
		if( is_string($mValues) and substr($mValues, 0, 2) == '#!' ) {
			$sKey = substr($mValues, 2);
			if( !isset( $aPreValues[$sKey] ) )
				return '&nbsp;';
			
			$aValues = explode( ',', $sValue );
			
			$aTValues = array();
			
			foreach( $aValues as $sValue )
				$aTValues[] = _t( $aPreValues[$sKey][$sValue][$sUseLKey] );
			
			return htmlspecialchars_adv( implode( ', ', $aTValues ) );
		} elseif( is_array($mValues) ) {
			$aValues = array();
			foreach( explode( ',', $sValue ) as $sValueOne )
				$aValues[] = _t( "_$sValueOne" );
			
			return htmlspecialchars_adv( implode( ', ', $aValues ) );
		} else
			return '=wrong=';
	}
	
	function collectSearchRequestParams() {
		$aParams = array();
		
		if( empty($_GET) and empty($_POST) )
			return $aParams;
		
		foreach( $this -> aBlocks as $aBlock ) {
			foreach( $aBlock['Items'] as $aItem ) {
				$sItemName = $aItem['Name'];
				$mValue = null;
				
				switch( $aItem['Type'] ) {
					case 'text':
					case 'area':
						if( isset( $_REQUEST[$sItemName] ) and $_REQUEST[$sItemName] )
							$mValue = process_pass_data( $_REQUEST[$sItemName] );
					break;
					
					case 'num':
					case 'date':
					case 'range':
						if( isset( $_REQUEST[$sItemName] ) and is_array( $_REQUEST[$sItemName] ) ) {
							$mValue = array();
							
							$mValue[0] = (int)$_REQUEST[$sItemName][0];
							$mValue[1] = (int)$_REQUEST[$sItemName][1];
							
							if( !$mValue[0] and !$mValue[1] )
								$mValue = null; // if no values entered, skip them
						}
					break;
					
					case 'select_one':
					case 'select_set':
						if( isset( $_REQUEST[$sItemName] ) and is_array( $_REQUEST[$sItemName] ) ) {
							$mValue = array();
							
							foreach( $_REQUEST[$sItemName] as $sValue ) {
								$sValue = trim( process_pass_data( $sValue ) );
								if( $sValue )
									$mValue[] = $sValue;
							}
							
							if( empty( $mValue ) )
								$mValue = null; //if nothing selected, skip
						}
					break;
					
					case 'bool':
						if( isset( $_REQUEST[$sItemName] ) and $_REQUEST[$sItemName] )
							$mValue = true;
					break;
					
					case 'system':
						switch( $sItemName ) {
							case 'ID':
								if( isset( $_REQUEST[$sItemName] ) and (int)$_REQUEST[$sItemName] )
									$mValue = (int)$_REQUEST[$sItemName];
							break;
							
							case 'Couple':
								if( isset( $_REQUEST[$sItemName] ) and is_array( $_REQUEST[$sItemName] ) ) {
									if( isset( $_REQUEST[$sItemName][0] ) and isset( $_REQUEST[$sItemName][1] ) )
										$mValue = '-1'; //pass
									elseif( isset( $_REQUEST[$sItemName][0] ) )
										$mValue = 0;
									elseif( isset( $_REQUEST[$sItemName][1] ) )
										$mValue = 1;
								}
							break;
							
							case 'Location':
								
							break;
							
							case 'Keyword':
								if( isset( $_REQUEST[$sItemName] ) and trim( $_REQUEST[$sItemName] ) )
									$mValue = trim( process_pass_data( $mValue ) );
							break;
							
						}
					break;
				}
				
				if( !is_null( $mValue ) )
					$aParams[ $sItemName ] = $mValue;
			}
		}
		
		return $aParams;
	}
	
	function getProfilesMatch( $aProf1, $aProf2 ) {
		if( !$this -> aArea )
			return 0;
		
		$aFields1 = $this -> aBlocks[0]['Items'];
		$aFields2 = $this -> aCache[100][0]['Items'];
		
		$iMyPercent = 0;
		$iTotalPercent = 0;
		foreach( $aFields1 as $aField1 ) {
			$aField2 = $aFields2[ $aField1['MatchField'] ];
			if( !$aField2 )
				continue;
			
			$iTotalPercent += $aField1['MatchPercent'];
			
			$sVal1 = $aProf1[ $aField1['Name'] ];
			$sVal2 = $aProf2[ $aField2['Name'] ];
			
			if( !strlen($sVal1) or !strlen($sVal2) )
				continue;
			
			$iAddPart = 0;
			switch( "{$aField1['Type']} {$aField1['Type']}" ) {
				case 'select_set select_one':
					$aVal1 = explode( ',', $sVal1 );
					
					if( in_array( $sVal2, $aVal1 ) )
						$iAddPart = 1;
				break;
				
				case 'select_one select_set':
					$aVal2 = explode( ',', $sVal2 );
					
					if( in_array( $sVal1, $aVal2 ) )
						$iAddPart = 1;
				break;
				
				case 'select_set select_set':
					$aVal1 = explode( ',', $sVal1 );
					$aVal2 = explode( ',', $sVal2 );
					
					$iFound = 0;
					foreach( $aVal1 as $sTempVal1 ) {
						if( in_array( $sTempVal1, $aVal2 ) )
							$iFound ++;
					}
					
					$iAddPart = $iFound / count( $aVal1 );
				break;
				
				case 'range num':
					$aVal1 = explode( ',', $sVal1 );
					$sVal2 = (int)$sVal2;
					
					if( (int)$aVal1[0] <= $sVal2 and $sVal2 <= (int)$aVal1[0] )
						$iAddPart = 1;
				break;
				
				case 'range date':
					$aVal1 = explode( ',', $sVal1 );
					
					$aDate = explode( '-', $sVal2 );
					$sVal2 = sprintf( '%d/%d/%d', $aDate[2], $aDate[1], $aDate[0] );
					$sAge = $this -> getAge( $sVal2 );
					
					if( (int)$aVal1[0] <= $sVal2 and $sVal2 <= (int)$aVal1[0] )
						$iAddPart = 1;
				break;
				
				default:
					if( $sVal1 == $sVal2 )
						$iAddPart = 1;
			}
			
			$iMyPercent    += round( $aField1['MatchPercent'] * $iAddPart );
		}
		
		if( $iTotalPercent != 100 && $iTotalPercent != 0 )
			$iMyPercent = (int)( ( $iMyPercent / $iTotalPercent ) * 100 );
		
		return $iMyPercent;
	}
}
