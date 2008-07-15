<?php

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfileFields.php' );

class BxDolProfilesController {
	var $oPF;
	var $aItems;
	
	function BxDolProfilesController() {
		
	}
	
	function createProfile( $aData, $bSendMails = true, $iMainMemberID = 0 ) {
		
		if( !$aData or !is_array($aData) or empty($aData) )
			return false;
		
		unset( $aData['Couple'] );
		unset( $aData['Captcha'] );
		unset( $aData['TermsOfUse'] );
		
		/* @var $this->oPF BxDolProfileFields */
		$this -> oPF = new BxDolProfileFields(100);
		
		if( !$this -> oPF -> aArea ) {
			echo 'Profile Fields cache not loaded. Cannot continue.';
			return false;
		}
		
		$this -> aItems = $this -> oPF -> aArea[0]['Items'];
		
		if( $iMainMemberID )
			$aMainMember = $this -> getProfileInfo( $iMainMemberID );
		else
			$aMainMember = false;
		
		// begin profile info collecting
		$aNewProfile = array();
		
		foreach( $this -> aItems as $aItem ) {
			$sItemName = $aItem['Name'];
			
			if( array_key_exists( $sItemName, $aData ) ) {
				$aNewProfile[$sItemName] = $aData[$sItemName];
			} elseif( $aMainMember and array_key_exists( $sItemName, $aMainMember ) and $aItem['Type'] != 'system' ) {
				if( $aItem['Unique'] )
					$aNewProfile[$sItemName] = $this -> genUniqueValue( $sItemName, $aMainMember[$sItemName] );
				else
					$aNewProfile[$sItemName] = $aMainMember[$sItemName];
			} else {
				switch( $aItem['Type'] ) {
					case 'pass':
						$aNewProfile[$sItemName] = $this -> genRandomPassword();
					break;
					
					case 'num':
						$aNewProfile[$sItemName] = (int)$aItem['Default'];
					break;
					
					case 'bool':
						$aNewProfile[$sItemName] = (bool)$aItem['Default'];
					break;
					
					case 'system':
						switch( $sItemName ) {
							case 'ID': //set automatically
							case 'Captcha': //not been inserted
							case 'Location': //not been inserted
							case 'Keyword': //not been inserted
							case 'TermsOfUse': //not been inserted
								//pass
							break;
							
							case 'DateReg':
								$aNewProfile[$sItemName] = date( 'Y-m-d H:i:s' ); // set current date
							break;
							
							case 'DateLastEdit':
							case 'DateLastLogin':
								$aNewProfile[$sItemName] = '0000-00-00';
							break;
							
							case 'Couple':
								$aNewProfile[$sItemName] = $aMainMember ? $iMainMemberID : 0; //if main member exists, set him as a couple link
							break;
							
							case 'Featured':
								$aNewProfile[$sItemName] = false;
							break;
							
							case 'Status':
								if ( getParam('autoApproval_ifNoConfEmail') == 'on' ) {
									if ( getParam('autoApproval_ifJoin') == 'on' )
										$aNewProfile[$sItemName] = 'Active';
									else
										$aNewProfile[$sItemName] = 'Approval';
								} else
									$aNewProfile[$sItemName] = 'Unconfirmed';
							break;
						}
					break;
					
					default:
						$aNewProfile[$sItemName] = $aItem['Default'];
				}
			}
		} //we completed collecting
		
		$sSet = $this -> collectSetString( $aNewProfile );
		$sQuery = "INSERT INTO `Profiles` SET \n$sSet";
		$rRes = db_res( $sQuery );
		
		if( $rRes ) {
			$iNewID = mysql_insert_id();
			
			$this -> createProfileCache( $iNewID );
			
			if( $aMainMember )
				$this -> updateProfile( $iMainMemberID, array('Couple' => $iNewID ) ); //set main member's couple. they will be linked each other
			
			//collect status text
			if( $bSendMails and !$aMainMember ) { //send mail only to main member, not to couple
				if( getParam('autoApproval_ifNoConfEmail') == 'on' ) {
					if ( getParam('autoApproval_ifJoin') == 'on' ) {
						$sStatusText = 'Active';
						$this -> sendActivationMail( $iNewID );
					} else {
						$sStatusText = 'Approval';
						$this -> sendApprovalMail( $iNewID );
					}
				} else {
					if( $this -> sendConfMail( $iNewID ) )
						$sStatusText = 'Unconfirmed';
					else
						$sStatusText = 'NotSent';
				}
			} else
				$sStatusText = 'OK';
			
			//set crypted password
			$this -> updateProfile( $iNewID, array( 'Password' => md5( $aNewProfile['Password'] ) ) );
			
			return array( $iNewID, $sStatusText );
		} else
			return array( false, 'Failed' );
	}
	
	function createProfileCache( $iMemID ) {
		createUserDataFile( $iMemID );
	}
	
	function sendConfMail( $iMemID ) {
		global $site;
		
		$iMemID = (int)$iMemID;
		$aMember = $this -> getProfileInfo( $iMemID );
		if( !$aMember )
			return false;
		
		$sEmail    = $aMember['Email'];
		$sSubject  = getParam( 't_Confirmation_subject' );
		$sMessage  = getParam( 't_Confirmation' );
		
		$sConfCode = base64_encode( base64_encode( crypt( $sEmail, 'se' ) ) );
		$sConfLink = "{$site['url']}profile_activate.php?ConfID={$iMemID}&ConfCode=" . urlencode( $sConfCode );
		
		$aPlus = array( 'ConfCode' => $sConfCode, 'ConfirmationLink' => $sConfLink );
		
		return sendMail( $sEmail, $sSubject, $sMessage, $iMemID, $aPlus );
	}
	
	// sent when user status changed to active
	function sendActivationMail( $iMemID ) {
		$iMemID = (int)$iMemID;
		$aMember  = $this -> getProfileInfo( $iMemID );
		if( !$aMember )
			return false;
		
		$sEmail   = $aMember['Email'];
       	$sSubject = getParam('t_Activation_subject');
		$sMessage = getParam('t_Activation');
       	
       	return sendMail( $sEmail, $sSubject, $sMessage, $iMemID );
	}
	
	//sent if member in approval status
	function sendApprovalMail( $iMemId ) {
		
	}
	
	// sent to admin
	function sendNewUserNotify( $iMemID ) {
		global $site;
		
		$iMemID = (int)$iMemID;
		$aMember = $this -> getProfileInfo( $iMemID );
		if( !$aMember )
			return false;
			
		$sSubject	= "New user confirmed";
		$sMessage = 
"New user {$aMember['NickName']} with email {$aMember['Email']} has been confirmed,
his/her ID is {$iMemID}.
--
{$site['title']} mail delivery system
Auto-generated e-mail, please, do not reply
";
		return sendMail( $site['email_notify'], $sSubject, $sMessage );
	}
	
	function updateProfile( $iMemberID, $aData ) {
		if( !$aData or !is_array($aData) or empty($aData) )
			return false;
		
		$sSet = $this -> collectSetString( $aData );
		$sQuery = "UPDATE `Profiles` SET {$sSet} WHERE `ID` = " . (int)$iMemberID;
		//echo $sQuery ;
		db_res($sQuery);
		$this -> createProfileCache( $iMemberID );
		return (bool)mysql_affected_rows();
	}
	
	function collectSetString( $aData ) {
		$sRequestSet = '';
		
		foreach( $aData as $sField => $mValue ) {
			if( is_string($mValue) )
				$sValue = "'" . addslashes( $mValue ) . "'";
			elseif( is_bool($mValue) )
				$sValue = (int)$mValue;
			elseif( is_array($mValue) ) {
				$sValue = '';
				foreach( $mValue as $sStr )
					$sValue .= addslashes( str_replace( ',', '', $sStr ) ) . ',';
					
				$sValue = "'" . substr($sValue,0,-1) . "'";
			} elseif( is_int($mValue) ) {
				$sValue = $mValue;
			} else
				$sValue = 'NULL';
			
			$sRequestSet .= "`$sField` = $sValue,\n";
		}
		
		$sRequestSet = substr( $sRequestSet,0, -2 );// remove ,\n
		
		return $sRequestSet;
	}
	
	function deleteProfile( $iMemberID ) {
		
	}
	
	function genRandomPassword() {
		return 'aaaaaa';
	}
	
	function getProfileInfo( $iMemberID ) {
		return db_assoc_arr( "SELECT * FROM `Profiles` WHERE `ID` = " . (int)$iMemberID );
	}
	
	function genUniqueValue( $sFieldName, $sValue, $bRandMore = false ) {
		if( $bRandMore )
			$sRand = '(' . rand(1000, 9999) . ')';
		else
			$sRand = '(2)';
			
		$sNewValue = $sValue . $sRand;
		
		$iCount = (int)db_value( "SELECT COUNT(*) FROM `Profiles` WHERE `$sFieldName` = '" . addslashes($sNewValue) . "'" );
		if( $iCount )
			return genUniqueValue( $sFieldName, $sValue, true );
		else
			return $sNewValue;
	}
}