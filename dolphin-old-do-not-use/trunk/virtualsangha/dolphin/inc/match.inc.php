<?php

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolProfileFields.php' );

function getProfilesMatch( $iPID1 = 0, $iPID2 = 0 ) {
	$iPID1 = (int)$iPID1;
	$iPID2 = (int)$iPID2;

	if( !$iPID1 or !$iPID2 )
		return 0;

	if( $iPID1 == $iPID2 )
		return 0; //maybe need to return 100?? :)

	// try to find in cache
	$sQuery = "SELECT `Percent` FROM `ProfilesMatch` WHERE `PID1` = $iPID1 AND `PID2` = $iPID2";

	$aPercent = db_assoc_arr( $sQuery );
	if( $aPercent )
		return (int)$aPercent['Percent'];

	//not found in cache
	$aProf1 = getProfileInfo( $iPID1 );
	$aProf2 = getProfileInfo( $iPID2 );

	if( !$aProf1 or !$aProf2 )
		return 0;

	$oPF = new BxDolProfileFields( 101 ); //matching area

	$iMatch = $oPF -> getProfilesMatch( $aProf1, $aProf2 );

	//write to cache
	$sQuery = "INSERT INTO `ProfilesMatch` ( `PID1`, `PID2`, `Percent` ) VALUES ( $iPID1, $iPID2, $iMatch )";
	db_res( $sQuery );

	return $iMatch;
}

function clearProfileMatchCache( $iProfileID ) {
	$iProfileID = (int)$iProfileID;
	if( !$iProfileID )
		return false;
	
	$sQuery = "DELETE FROM `ProfilesMatch` WHERE `PID1` = $iProfileID OR `PID2` = $iProfileID";
	db_res( $sQuery );
}

function cupid_email_cron() {
	$iMinCupLevel = (int)getParam('match_percent'); 
	$sLastCupidCheckDate = getParam('cupid_last_cron'); 
	if (!strlen($sLastCupidCheckDate)) return;

	$sLastCupidCheckDateParam = "(`DateReg`>='{$sLastCupidCheckDate}' OR `DateLastEdit`>='{$sLastCupidCheckDate}') AND";

	$sSelectedSQL = "SELECT DISTINCT `Profiles`.`ID` FROM `Profiles` WHERE {$sLastCupidCheckDateParam} `Status`='Active'";

	$vSelCupProf = db_res($sSelectedSQL);
	$aSelCupids = array();
	while ($aSelCup = mysql_fetch_array($vSelCupProf)) {
		$aSelCupids[] = $aSelCup['ID'];
	}

	$sAllProfSQL = "SELECT DISTINCT `Profiles`.* FROM `Profiles` WHERE `Status`='Active' AND (`Couple`='0' OR `Couple`>`ID`)";
	$vAllProf = db_res($sAllProfSQL);
	while ($aAnyProf = mysql_fetch_array($vAllProf)) {
		foreach ( $aSelCupids as $iSelCupID ) {
			$iCurMatch = getProfilesMatch( $aAnyProf['ID'], $iSelCupID );
			if ( $iCurMatch >= $iMinCupLevel ) // If the profile matches less then predefined percent then go to next iteration (i.e. next profile)
				send_cupid_email($aAnyProf, $iSelCupID, $iCurMatch);
		}
	}

	$sUpdateCronValSQL = "UPDATE `GlParams` SET `VALUE`=NOW() WHERE `Name`='cupid_last_cron' LIMIT 1";
	db_res($sUpdateCronValSQL);
}

function send_cupid_email( $aAnyProf, $iSelCupID) {
	global $site;

	$message = getParam( "t_CupidMail" );
	$subject = getParam('t_CupidMail_subject');
	$subject = addslashes($subject);

	$recipient	= $aAnyProf['Email'];
	$headers	= "From: {$site['title']} <{$site['email_notify']}>";
	$headers2	= "-f{$site['email_notify']}";

	$message	= str_replace( "<SiteName>", $site['title'], $message );
	$message	= str_replace( "<Domain>", $site['url'], $message );
	$message	= str_replace( "<RealName>", $aAnyProf['NickName'], $message );
	$message	= str_replace( "<StrID>", $aAnyProf['ID'], $message );
	$message	= str_replace( "<MatchProfileLink>", getProfileLink($iSelCupID), $message );
	$message	= addslashes($message);

	if ('Text' == $aAnyProf['EmailFlag']) {
		$message = html2txt($message);
	}
	if ('HTML' == $aAnyProf['EmailFlag']) {
		$headers = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=UTF-8\r\n" . $headers;
	}

	$sql = "INSERT INTO `NotifyQueue` SET `Email` = {$aAnyProf['ID']}, Msg = 0, `From` = 'ProfilesMsgText', Creation = NOW(), MsgText = '$message', MsgSubj = '$subject'";
	$res = db_res($sql);
	return true;
}