<?php

require_once( './inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC     . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC     . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC     . 'match.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfileFields.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfilesController.php' );
require_once( BX_DIRECTORY_PATH_ROOT    . "templates/tmpl_{$tmpl}/scripts/BxTemplFormView.php" );

$_page['name_index'] = 25;
$_page['css_name']   = 'pedit.css';
//$_page['extra_js']   = '<script type="text/javascript" language="JavaScript" src="' . $site['plugins'] . 'jquery/jquery.js"></script>';
$_page['extra_js']  .= '<script type="text/javascript" language="JavaScript" src="' . $site['plugins'] . 'jquery/ui.datepicker.js"></script>';
$_page['extra_js']  .= '<script type="text/javascript" language="JavaScript" src="' . $site['plugins'] . 'jquery/jquery.form.js"></script>';
$_page['extra_js']  .= '<script type="text/javascript" language="JavaScript" src="inc/js/pedit.js"></script>';

//init datepicker
$iMinAge  = (int)getParam( 'search_start_age' );
$iMaxAge  = (int)getParam( 'search_end_age'   );
$iCurYear = (int)date('Y');
$iMinYear = $iCurYear - $iMaxAge - 1;
$iMaxYear = $iCurYear - $iMinAge;

$sDatepickerInit = $oTemplConfig -> customize['join']['datepickerInit'];

$sDatepickerInit = str_replace( '{min_year}', $iMinYear, $sDatepickerInit );
$sDatepickerInit = str_replace( '{max_year}', $iMaxYear, $sDatepickerInit );

$_page['extra_js']  .= '
	<script type="text/javascript" language="JavaScript">
		$( document ).ready( function(){
			' . $sDatepickerInit . '
		} );
	</script>';

check_logged();

//$_page['header'] = _t( "_JOIN_H" );
//$_page['header_text'] = _t( "_JOIN_H" );



$oEditProc = new BxDolPEditProcessor();

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = $oEditProc -> process();


PageCode();



class BxDolPEditProcessor {
	var $iProfileID; // id of profile which will be edited
	var $iArea = 0;  // 2=owner, 3=admin, 4=moderator
	var $bCouple = false; // if we edititng couple profile
	var $aCoupleMutualFields; // couple mutual fields
	
	var $oPC;        // object of profiles controller
	var $oPF;        // object of profile fields
	
	var $aBlocks;    // blocks of page (with items)
	var $aItems;     // all items within blocks
	
	var $aProfiles;  // array with profiles (couple) data
	var $aValues;    // values
	var $aOldValues; // values before save
	var $aErrors;    // generated errors
	
	var $bAjaxMode;  // if the script was called via ajax
	
	function BxDolPEditProcessor() {
		global $logged;
		
		$this -> aProfiles = array( 0 => array(), 1 => array() ); // double arrays (for couples)
		$this -> aValues   = array( 0 => array(), 1 => array() );
		$this -> aErrors   = array( 0 => array(), 1 => array() );
		
		$this -> iProfileID = (int)$_REQUEST['ID'];
		
		// basic checks
		if( $logged['member'] ) {
			$iMemberID = (int)$_COOKIE['memberID'];
			if( !$this -> iProfileID ) {
				//if profile id is not set by request, edit own profile
				$this -> iProfileID = $iMemberID;
				$this -> iArea = 2;
			} else {
				// check if this member is owner
				if( $this -> iProfileID == $iMemberID )
					$this -> iArea = 2;
			}
		} elseif( $logged['admin'] )
			$this -> iArea = 3;
		elseif( $logged['moderator'] )
			$this -> iArea = 4;
		
		
		$this -> bAjaxMode = ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' );
	}
	
	function process() {
		if( !$this -> iProfileID )
			return _t( '_Profile not specified' );
		
		if( !$this -> iArea )
			return _t( '_You cannot edit this profile' );
		
		/* @var $this->oPC BxDolProfilesController */
		$this -> oPC = new BxDolProfilesController();
		
		//get profile info array
		$this -> aProfiles[0] = $this -> oPC -> getProfileInfo( $this -> iProfileID );
		if( !$this -> aProfiles[0] )
			return _t( '_Profile not found' );
		
		if( $this -> aProfiles[0]['Couple'] ) { // load couple profile
			$this -> aProfiles[1] = $this -> oPC -> getProfileInfo( $this -> aProfiles[0]['Couple'] );
			
			if( !$this -> aProfiles[1] )
				return _t( '_Couple profile not found' );
			
			$this -> bCouple = true; //couple enabled
		}
		
		/* @var $this->oPF BxDolProfileFields */
		$this -> oPF = new BxDolProfileFields( $this -> iArea );
		if( !$this -> oPF -> aArea )
			return 'Profile Fields cache not loaded. Cannot continue.';
		
		$this -> aCoupleMutualFields = $this -> oPF -> getCoupleMutualFields();
		
		//collect blocks
		$this -> aBlocks = $this -> oPF -> aArea;
		
		//collect items
		$this -> aItems = array();
		foreach ($this -> aBlocks as $aBlock) {
			foreach( $aBlock['Items'] as $iItemID => $aItem )
				$this -> aItems[$iItemID] = $aItem;
		}
		
		$this -> aValues[0] = $this -> oPF -> getValuesFromProfile( $this -> aProfiles[0] ); // set default values
		if( $this -> bCouple )
			$this -> aValues[1] = $this -> oPF -> getValuesFromProfile( $this -> aProfiles[1] ); // set default values
		
		$this -> aOldValues = $this -> aValues;
		
		$sStatusText = '';
		if( isset($_POST['do_submit']) ) {
			$this -> oPF -> processPostValues( $this -> bCouple, $this -> aValues, $this -> aErrors, 0, $this -> iProfileID );
			
			if( empty( $this -> aErrors[0] ) and empty( $this -> aErrors[1] ) and !$this -> bAjaxMode ) { // do not save in ajax mode
				$this -> saveProfile();
				$sStatusText = '_Save profile successful';

				reparseObjTags( 'profile', $this->iProfileID );
			}
		}
		
		if( $this -> bAjaxMode ) {
			//print_r( $_POST );
			$this -> showErrorsJson();
			exit;
		} else {
			ob_start();
			$this -> showEditForm( $sStatusText );
			return ob_get_clean();
		}
	}
	
	function showErrorsJson() {
		header('Content-Type:text/javascript');
		
		echo $this -> oPF -> genJsonErrors( $this -> aErrors, $this -> bCouple );
	}
	
	function showEditForm( $sStatusText ) {
		//echoDbg( $this -> aValues );exit;
		
		$aFormAttrs = array(
			'id' => 'edit_form',
			'onsubmit' => 'return validateEditForm(this);'
		);
		
		$aTableAttrs = array(
			'id' => 'edit_form_table',
			'action' => $_SERVER['PHP_SELF']
		);
		
		$aFormParams = array(
			'hidden' => array( 'ID' => $this -> iProfileID, 'do_submit' => '1' )
		);
		
		$aTableParams = array(
			'double'         => $this -> bCouple,
			'second_enabled' => $this -> bCouple
		);
		
		$aTableParams['headers']     = array( '', _t( '_First Person' ), _t( '_Second Person' ) );
		$aTableParams['headers_add'] = 'class="header form_second_col"' . ( $this -> bCouple ? '' : ' style="display: none;"' );
		
		$aButtons = array(
			array(
				'type' => 'submit',
				'value' => _t( '_Save' ),
				'class' => 'input_submit'
			)
		);
		
		/* @var $oForm BxTemplFormView */
		$oForm = new BxTemplFormView( 'edit_form' );
		$oForm -> begin( $aFormAttrs, $aTableAttrs, $aFormParams, $aTableParams );
		
		foreach( $this -> aBlocks as $aBlock ) {
			$oForm -> beginBlock( _t( $aBlock['Caption'] ) );
			
			foreach( $aBlock['Items'] as $aItem ) {
				
				$aCol0 = array();
				
				$aCol0['Type']      = $aItem['Type'];
				$aCol0['Name']      = ( $aItem['Type'] == 'system' ) ? $aItem['Name'] : ( $aItem['Name'] . '[0]' );
				$aCol0['Mandatory'] = $aItem['Type'] == 'pass' ? false : $aItem['Mandatory'];
				$aCol0['Control']   = $aItem['Control'];
				$aCol0['Values']    = $aItem['Values'];
				$aCol0['UseLKey']   = $aItem['UseLKey'];
				
				$aCol0['Caption']   = _t( $aItem['Caption'] );
				$aCol0['Desc']      = _t( $aItem['Desc'] );
				if( $aCol0['Desc'] == $aItem['Desc'] )
					$aCol0['Desc'] = '';
				
				// set value
				if( isset( $this -> aValues[0][ $aItem['Name'] ] ) )
					$aCol0['Value']   = $this -> aValues[0][ $aItem['Name'] ];
				elseif ( $aItem['Name'] == 'Couple' )
					$aCol0['Value'] = $this -> bCouple;
				
				// set error
				if( isset( $this -> aErrors[0][ $aItem['Name'] ] ) )
					$aCol0['Error']   = $this -> aErrors[0][ $aItem['Name'] ];
				
				// check second person's field
				if( $this -> bCouple and !in_array( $aItem['Name'], $this -> aCoupleMutualFields ) ) {
					$aCol1 = array();
					
					$aCol1['Type']    = $aItem['Type'];
					$aCol1['Name']    = $aItem['Name'] . '[1]';
					$aCol1['Control'] = $aItem['Control'];
					$aCol1['Values']  = $aItem['Values'];
					$aCol1['UseLKey'] = $aItem['UseLKey'];
					
					// set value
					if( isset( $this -> aValues[1][ $aItem['Name'] ] ) )
						$aCol1['Value']   = $this -> aValues[1][ $aItem['Name'] ];
					
					// set error
					if( isset( $this -> aErrors[1][ $aItem['Name'] ] ) )
						$aCol1['Error']   = $this -> aValues[1][ $aItem['Name'] ];
					
					//echoDbg( $aCol0 );
					$oForm -> addRow( $aCol0, $aCol1 );
				} else
					$oForm -> addRow( $aCol0 );
			} 
			
			$oForm -> endBlock();
		}
		
		$oForm -> end( $aButtons );
		
		if( $sStatusText )
			echo '<div class="notice_text">' . _t($sStatusText) . "</div>";
		
		echo $oForm -> getCode();
	}
	
	function saveProfile() {
		$aDiff = $this -> getDiffValues(0);
		$aUpd = $this -> oPF -> getProfileFromValues( $aDiff );
		
		$aUpd['DateLastEdit'] = date( 'Y-m-d H:i:s' );
		if( !getParam('autoApproval_ifProfile') && $this -> iArea == 2 )
			$aUpd['Status'] = 'Approval';

		$this -> oPC -> updateProfile( $this -> iProfileID, $aUpd );
		
		clearProfileMatchCache( $this -> iProfileID );
		
		if( $this -> bCouple ) {
			$aDiff = $this -> getDiffValues(1);
			$aUpd = $this -> oPF -> getProfileFromValues( $aDiff );

			$aUpd['DateLastEdit'] = date( 'Y-m-d H:i:s' );
			if( !getParam('autoApproval_ifProfile') && $this -> iArea == 2 )
				$aUpd['Status'] = 'Approval';

			$this -> oPC -> updateProfile( $this -> aProfiles[0]['Couple'], $aUpd );
			
			clearProfileMatchCache( $this -> aProfiles[0]['Couple'] );
		}
	}
	
	function getDiffValues($iInd) {
		$aOld = $this -> aOldValues[$iInd];
		$aNew = $this -> aValues[$iInd];
		
		$aDiff = array();
		foreach( $aNew as $sName => $mNew ){
			$mOld = $aOld[$sName];
			
			if( is_array($mNew) ) {
				if( count($mNew) == count($mOld) ) {
					//compare each value
					$mOldS = $mOld;
					$mNewS = $mNew;
					sort( $mOldS ); //sort them for correct comparison
					sort( $mNewS );
					
					foreach( $mNewS as $iKey => $sVal )
						if( $mNewS[$iKey] != $mOld[$iKey] ) {
							$aDiff[$sName] = $mNew; //found difference
							break;
						}
				} else
					$aDiff[$sName] = $mNew;
			} else {
				if( $mNew != $mOld )
					$aDiff[$sName] = $mNew;
			}
		}
		
		return $aDiff;
	}
	
}