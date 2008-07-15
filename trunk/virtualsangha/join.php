<?php

require_once( './inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC     . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC     . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfileFields.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfilesController.php' );
require_once( BX_DIRECTORY_PATH_ROOT    . "templates/tmpl_{$tmpl}/scripts/BxTemplFormView.php" );

//aa blya join nah!

$_page['name_index'] = 3;
$_page['css_name']   = 'join.css';
$_page['extra_js']  .= '<script type="text/javascript" language="JavaScript" src="' . $site['plugins'] . 'jquery/ui.datepicker.js"></script>';
$_page['extra_js']  .= '<script type="text/javascript" language="JavaScript" src="' . $site['plugins'] . 'jquery/jquery.form.js"></script>';
$_page['extra_js']  .= '<script type="text/javascript" language="JavaScript" src="inc/js/join.js"></script>';

//init datepicker
$iMinAge  = (int)getParam( 'search_start_age' );
$iMaxAge  = (int)getParam( 'search_end_age'   );
$iCurYear = (int)date('Y');
$iMinYear = $iCurYear - $iMaxAge - 1;
$iMaxYear = $iCurYear - $iMinAge;

$iSelectedRel = ceil( $iMinAge * 365.25 ); //get relative days number for default date

$sDatepickerInit = $oTemplConfig -> customize['join']['datepickerInit'];

$sDatepickerInit = str_replace( '{min_year}', $iMinYear,     $sDatepickerInit );
$sDatepickerInit = str_replace( '{max_year}', $iMaxYear,     $sDatepickerInit );
$sDatepickerInit = str_replace( '{dfl_days}', $iSelectedRel, $sDatepickerInit );

$_page['extra_js']  .= '
	<script type="text/javascript" language="JavaScript">
		$( document ).ready( function(){
			' . $sDatepickerInit . '
		} );
	</script>';

check_logged();

$_page['header'] = _t( '_JOIN_H' );
$_page['header_text'] = _t( '_JOIN_H' );

if( $logged['member'] )
{
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = _t( '_Sorry, you\'re already joined' );
	PageCode();
	exit;
}

if ( getParam('reg_by_inv_only') == 'on' && getID($_COOKIE['idFriend'])==0 ) {
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = MsgBox(_t('registration by invitation only'));
	PageCode();
	exit;
}

$oJoinProc = new BxDolJoinProcessor();

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = $oJoinProc -> process();


PageCode();




class BxDolJoinProcessor {
	
	var $oPF; //profile fields
	var $iPage; //currently shown page
	var $aPages; //available pages
	var $aValues; //inputted values
	var $aErrors; //errors generated on page
	var $bAjaxMode; // defines if the script were requested by ajax
	
	var $bCoupleEnabled;
	var $aCoupleMutualItems;
	var $bCouple;
	
	function BxDolJoinProcessor() {
		$this -> aValues = array( 0 => array(), 1 => array() ); // double arrays (for couples)
		$this -> aErrors = array( 0 => array(), 1 => array() ); 
		
		/* @var $this->oPF BxDolProfileFields */
		$this -> oPF = new BxDolProfileFields(1);
		
		$this -> bAjaxMode = ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' );
	}
	
	function process() {
		if( !$this -> oPF -> aArea )
			return 'Profile Fields cache not loaded. Cannot continue.';
		
		$this -> aPages = array_keys( $this -> oPF -> aArea );
		
		$this -> iPage = ( isset( $_POST['join_page'] ) ) ? $_POST['join_page'] : 0; // get current working page from POST
		
		if( $this -> iPage !== 'done' )
			$this -> iPage = (int)$this -> iPage;
		
		$this -> getCoupleOptions();
		
		$this -> processPostValues();
		
		if( $this -> bAjaxMode ) {
			$this -> showErrorsJson();
			exit;
		} else {
			ob_start();
			
			if( $this -> iPage === 'done' ) { //if all pages are finished and no errors found
				list( $iMemID, $sStatus ) = $this -> registerMember();
				
				if( !$iMemID )
					$this -> showFailPage();
				else
					$this -> showFinishPage( $iMemID, $sStatus );
			} else
				$this -> showJoinForm();
			
			return ob_get_clean();
		}
	}
	
	function getCoupleOptions() {
		//find Couple item
		$aCoupleItem = false;
		foreach ($this -> aPages as $iPageInd => $iPage) { //cycle pages
			$aBlocks = $this -> oPF -> aArea[ $iPage ];
			foreach ($aBlocks as $iBlockID => $aBlock) {   //cycle blocks
				$aItems = $aBlock['Items'];
				foreach ($aItems as $iItemID => $aItem) {  //cycle items
					if( $aItem['Name'] == 'Couple' ) { // we found it!
						$aCoupleItem = $aItem;
						break;
					}
				}
				
				if( $aCoupleItem ) // we already found it
					break;
			}
			
			if( $aCoupleItem ) // we already found it
				break;
		}
		
		if( $aCoupleItem ) {
			$this -> bCoupleEnabled      = true;
			$this -> bCouple             = ( isset( $_REQUEST['Couple'] ) and $_REQUEST['Couple'] == 'yes' ) ? true : false;
		} else {
			$this -> bCoupleEnabled      = false;
			$this -> bCouple             = false;
		}
		
		$this -> aCoupleMutualItems = $this -> oPF -> getCoupleMutualFields();
	}
	
	function processPostValues() {
		
		foreach ($this -> aPages as $iPage) { //cycle pages
			
			if( $this -> iPage !== 'done' and $iPage >= $this -> iPage ) {
				$this -> iPage = $iPage; // we are on the current page. dont process these values, dont go further, just show form.
				break;
			}
			
			// process post values by Profile Fields class
			$this -> oPF -> processPostValues( $this -> bCouple, $this -> aValues, $this ->aErrors, $iPage );
			
			if( !empty( $this -> aErrors[0] ) or ( $this -> bCouple and !empty( $this -> aErrors[1] ) ) ) { //we found errors on previous page
				// do not process further values, just go to erroneous page.
				$this -> iPage = $iPage;
				break;
			}
		}
	}
	
	function showErrorsJson() {
		header('Content-Type:text/javascript');
		
		echo $this -> oPF -> genJsonErrors( $this -> aErrors, $this -> bCouple );
	}
	
	function showJoinForm() {
		
		//echoDbg( $this -> aValues );exit;
		
		$aFormAttrs = array(
			'id' => 'join_form',
			'onsubmit' => 'return validateJoinForm(this);'
		);
		
		$aTableAttrs = array(
			'id' => 'join_form_table'
		);
		
		$aFormParams = array(
			'hidden' => $this -> genHiddenFieldsArray()
		);
		
		$aTableParams = array(
			'double' => $this ->bCoupleEnabled,
			'second_enabled' => $this -> bCouple
		);
		
		$aTableParams['headers']     = array( '', _t( '_First Person' ), _t( '_Second Person' ) );
		$aTableParams['headers_add'] = 'class="header form_second_col"' . ( $this -> bCouple ? '' : ' style="display: none;"' );
		
		$aButtons = array(
			array(
				'type' => 'submit',
				'value' => _t( '_Submit' ),
				'class' => 'input_submit'
			)
		);
		
		/* @var $oForm BxTemplFormView */
		$oForm = new BxTemplFormView( 'join_form' );
		$oForm -> begin( $aFormAttrs, $aTableAttrs, $aFormParams, $aTableParams );
		
		$aBlocks = $this -> oPF -> aArea[ $this -> iPage ];
		foreach( $aBlocks as $aBlock ) {
			$oForm -> beginBlock( _t( $aBlock['Caption'] ) );
			
			foreach( $aBlock['Items'] as $aItem ) {
				
				$aCol0 = array();
				
				$aCol0['Type']      = $aItem['Type'];
				$aCol0['Name']      = ( $aItem['Type'] == 'system' ) ? $aItem['Name'] : ( $aItem['Name'] . '[0]' );
				$aCol0['Mandatory'] = $aItem['Mandatory'];
				$aCol0['Control']   = $aItem['Control'];
				$aCol0['Values']    = $aItem['Values'];
				$aCol0['UseLKey']   = $aItem['UseLKey'];
				
				$aCol0['Caption']   = _t( $aItem['Caption'] );
				$aCol0['Desc']      = _t( $aItem['Desc'], $aItem['Min'], $aItem['Max'] );
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
				if( $this -> bCoupleEnabled and !in_array( $aItem['Name'], $this -> aCoupleMutualItems ) ) {
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
					
					$oForm -> addRow( $aCol0, $aCol1 );
				} else
					$oForm -> addRow( $aCol0 );
			} 
			
			$oForm -> endBlock();
		}
		
		$oForm -> end( $aButtons );
		
		echo $oForm -> getCode();
		
		//boonex id
		/* if( getParam( 'enable_get_boonex_id' ) )
		{
			global $tmpl;
			
			echo "<div class=\"import_boonex_id\">";
			$action = "boonex";
			$text = '<div class="boonex_id">' . _t( '_Import BoonEx ID' ) . '</div>';
			$table       = "Profiles";
			$login_page  = "{$site['url']}member.php";
			$join_page   = "{$site['url']}join_form.php";
			$forgot_page = '';
			$template    = "{$dir['root']}templates/tmpl_{$tmpl}/join_login_form.html";

			echo LoginForm( $text,$action,$table,$login_page,$forgot_page,$template );

			echo "</div>";
		} */
	}
	
	function genHiddenFieldsArray() {
		$aHiddenFields = array();
		
		//retrieve next page
		$iPageInd = (int)array_search( $this -> iPage, $this -> aPages );
		$iNextInd = $iPageInd + 1;
		
		if( array_key_exists( $iNextInd, $this -> aPages ) )
			$sNextPage = $this -> aPages[ $iNextInd ];
		else
			$sNextPage = 'done';
		
		// insert next page
		$aHiddenFields['join_page'] = $sNextPage;
		
		//echoDbg( $this -> aValues );
		
		// insert entered values
		$iHumans = $this -> bCouple ? 2 : 1;
		for( $iHuman = 0; $iHuman < $iHumans; $iHuman ++ ) {
			foreach( $this -> aPages as $iPage ) {
				if( $iPage == $this -> iPage )
					break; // we are on this page
				
				$aBlocks = $this -> oPF -> aArea[ $iPage ];
				foreach( $aBlocks as $aBlock ) {
					foreach( $aBlock['Items'] as $aItem ) {
						$sItemName = $aItem['Name'];
						
						if( isset( $this -> aValues[$iHuman][ $sItemName ] ) ) {
							$mValue = $this -> aValues[$iHuman][ $sItemName ];
							
							switch( $aItem['Type'] ) {
								case 'pass':
									$aHiddenFields[ $sItemName . '_confirm[' . $iHuman . ']' ] = $mValue;
								case 'text':
								case 'area':
								case 'date':
								case 'select_one':
								case 'num':
									$aHiddenFields[ $sItemName . '[' . $iHuman . ']' ] = $mValue;
								break;
								
								case 'select_set':
									foreach( $mValue as $iInd => $sValue )
										$aHiddenFields[ $sItemName . '[' . $iHuman . '][' . $iInd . ']' ] = $sValue;
								break;
								
								case 'range':
									$aHiddenFields[ $sItemName . '[' . $iHuman . '][0]' ] = $mValue[0];
									$aHiddenFields[ $sItemName . '[' . $iHuman . '][1]' ] = $mValue[1];
								break;
								
								case 'bool':
									$aHiddenFields[ $sItemName . '[' . $iHuman . ']' ] = $mValue ? 'yes' : '';
								break;
								
								case 'system':
									switch( $aItem['Name'] ) {
										case 'Couple':
										case 'TermsOfUse':
											$aHiddenFields[ $sItemName ] = $mValue ? 'yes' : '';
										break;
										
										case 'Captcha':
											$aHiddenFields[ $sItemName ] = $mValue;
										break;
									}
								break;
							}
						}
					}
				}
			}
		}
		return $aHiddenFields;
	}
	
	function registerMember() {
		$bEnAff = ( getParam('en_aff') == 'on' );

		$oPC = new BxDolProfilesController();
		
		//convert to profile
		$aProfile = $this -> oPF -> getProfileFromValues( $this -> aValues[0] );
		//create it
		list( $iMemID, $sStatus ) = $oPC -> createProfile( $aProfile );
		
		if( !$iMemID )
			return array( false, 'Fail' );
		
		if( $this -> bCouple ) {
			//convert
			$aProfile = $this -> oPF -> getProfileFromValues( $this -> aValues[1] );
			//create
			list( $iMem1ID, $sStatus1 ) = $oPC -> createProfile( $aProfile, false, $iMemID );
			
			if( !$iMem1ID ) {
				$oPC -> deleteProfile( $iMemID );
				return array( false, 'Fail' );
			}
		}
		
		//send new user notification
		if( getParam('newusernotify') == 'on' )
			$oPC -> sendNewUserNotify( $iMemID );

		// Affiliate and friend checking
		if ( $bEnAff && $_COOKIE['idAff'] ) {
			$vRes = db_res("SELECT `ID` FROM `aff` WHERE `ID` = {$_COOKIE['idAff']} AND `Status` = 'active'");
			if ( mysql_num_rows( $vRes ) ) {
				$vRes = db_res("INSERT INTO `aff_members` (`idAff`,`idProfile`) VALUES ('{$_COOKIE['idAff']}', '{$iMemID}')");
			}
		}
		
		if ( $bEnAff && $_COOKIE['idFriend'] ) {
			$iFriendID = getID( $_COOKIE['idFriend'] );
			if ( $iFriendID ) {
				$vRes = db_res( "UPDATE `Profiles` SET `aff_num` = `aff_num` + 1 WHERE `ID` = '{$iFriendID}'" );
				createUserDataFile( $iFriendID );
			}
		}
		
		reparseObjTags( 'profile', $iMemID );

		return array( $iMemID, $sStatus );
	}
	
	function showFailPage() {
		echo _t( '_Join failed' );
	}
	
	function showFinishPage( $iMemID, $sStatus ) {
		switch( $sStatus ) {
			case 'Active':      $sStatusText = ('_USER_ACTIVATION_SUCCEEDED'); break; //activated automatically
			case 'Approval':    $sStatusText = ('_USER_CONF_SUCCEEDED');       break; //automatically confirmed
			case 'Unconfirmed': $sStatusText = ('_EMAIL_CONF_SENT');           break; //conf mail succesfully sent
			case 'NotSent':     $sStatusText = ('_EMAIL_CONF_NOT_SENT');       break; //failed to send conf mail
		}
		
		echo _t( '_Join complete' );
		echo '<br />';
		echo _t( $sStatusText );
	}
	
}

function LoginForm( $text, $action, $table, $login_page, $forgot_page, $template = '' )
{
	global $site;
	global $dir;
	global $tmpl;

	$aFormReplace = array();
	
	$name_label = _t("_Nickname");
	
	$aFormReplace['header_text']    = $site['title'] . ' ' . $mem . ' Login';
	if( $action == "login" )
	{
		$aFormReplace['warning_text']   = $text;
		$aFormReplace['submit_label']   = _t("_Log In");
		$aFormReplace['form_onsubmit']  = 'return true;';
	}
	elseif( $action == 'boonex' )
	{
		$aFormReplace['warning_text']   = $text .
			'<div class="id">' .
				'<a href="javascript:void(0);"
				  onclick="window.open(\'http://www.boonex.com/unity/express/XML.php?module=form&amp;action=joinForm&amp;community=3\', \'Boonex_Sign_Up\', \'width=400,height=593,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=0\');">' .
					_t( '_Get BoonEx ID' ) .
				'</a>'.
			'</div>';
		
		$aFormReplace['submit_label']   = _t("_Import");
		
		$aFormReplace['form_onsubmit']  = 'getBoonexId( this, document.forms.join_form ); return false;';
	}
	$aFormReplace['action_url']     = $login_page;
	$aFormReplace['relocate_url']   = $_SERVER['PHP_SELF'];
	$aFormReplace['name_label']     = $name_label;
	$aFormReplace['password_label'] = _t("_Password");
	
	if( $forgot_page )
	{
		$aFormReplace['forgot_page_url'] = $forgot_page;
		$aFormReplace['forgot_label']    = _t("_forgot_your_password") . '?';
	}
	else
	{
		$aFormReplace['forgot_page_url'] = '';
		$aFormReplace['forgot_label']    = '';
	}
	
	if( !strlen( $template ) )
		$template = "{$dir['root']}templates/tmpl_{$tmpl}/join_login_form.html";
	
	$ret = file_get_contents( $template );
	
	foreach( $aFormReplace as $key => $val )
		$ret = str_replace( "__{$key}__", $val, $ret );
	
	return $ret;
}