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

require_once( 'header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// SpeedDating photo thumbnail size
$sdatingThumbWidth = 50;
$sdatingThumbHeight = 50;

// SpeedDating parameters
$enable_event_creating = (getParam('enable_event_creating') == 'on');

// Function error codes
define( 'SDATING_ERROR_SUCCESS',					0 );
define( 'SDATING_ERROR_QUERY_ERROR',				1 );
define( 'SDATING_ERROR_NOT_AFFECTED',				2 );
define( 'SDATING_ERROR_WRONG_DATE_FORMAT',			3 );
define( 'SDATING_ERROR_PHOTO_PROCESS',				4 );

/**
 * Retrieves thumbnail name by specified picture name
 *
 * @param string $pictureName				- name of original picture
 *
 * @return string 							- name of thumbnail for specified picture
 *
 *
 */
function getThumbNameByPictureName( $pictureName, $bVer2=FALSE )
{
	$dotPos = strrpos( $pictureName, '.' );

	// if dot not found then return original name
	if ( $dotPos === false )
		return $pictureName;

	$basePart = substr( $pictureName, 0, $dotPos );
	$ext = substr( $pictureName, $dotPos, strlen($pictureName) - $dotPos );

	$sRet = ($bVer2) ? 'thumb_' . $basePart . $ext : $basePart . '_thumb' . $ext;
	return $sRet;
}

/**
 * Determines if ticket available to specified member for specified event
 *
 * @param int $memberID					- member ID
 * @param int $eventID					- event ID
 *
 * @return float/bool 					- ticket price if ticket available, false otherwise
 *
 *
 */
function isTicketAvailable( $memberID, $eventID )
{
	global $date_format;

	// argument validation
	$memberID = (int)$memberID;
	$eventID = (int)$eventID;

	$memberArr = getProfileInfo( $memberID );
	$memberSex = $memberArr['Sex'];
	$membershipArr = getMemberMembershipInfo( $memberID );
	$eventArr = db_arr( "SELECT `ID`, `Title`, `Place`, `EventStart`, `TicketPriceFemale`, `TicketPriceMale`, `TicketCountFemale`, `TicketCountMale` FROM `SDatingEvents`
					WHERE `ID` = $eventID
					AND `Status` = 'Active'
					AND NOW() > `TicketSaleStart` AND NOW() < `TicketSaleEnd`
					AND FIND_IN_SET('{$memberSex}', `EventSexFilter`)
					AND ( TO_DAYS('{$memberArr['DateOfBirth']}')
						BETWEEN TO_DAYS(DATE_SUB(NOW(), INTERVAL `EventAgeUpperFilter` YEAR))
						AND TO_DAYS(DATE_SUB(NOW(), INTERVAL `EventAgeLowerFilter` YEAR)) )
					AND ( INSTR(`EventMembershipFilter`, '\'all\'') OR INSTR(`EventMembershipFilter`, '\'{$membershipArr['ID']}\'') )" );
	$partNumArr = db_arr( "SELECT COUNT(*) FROM `SDatingParticipants`
								LEFT JOIN `Profiles` ON `SDatingParticipants`.`IDMember` = `Profiles`.`ID`
								WHERE `SDatingParticipants`.`IDEvent` = $eventID
								AND `Profiles`.`Sex` = '{$memberSex}'" );
	$ticketsLeft = ( $memberArr['Sex'] == 'male' ? $eventArr['TicketCountMale'] - $partNumArr[0] : $eventArr['TicketCountFemale'] - $partNumArr[0] );
	$ticketPrice = (float)( $memberArr['Sex'] == 'male' ? $eventArr['TicketPriceMale'] : $eventArr['TicketPriceFemale'] );
	$ticketPrice = sprintf( "%.2f", $ticketPrice );

	if ( $eventArr['ID'] && $ticketsLeft > 0 )
		$res = $ticketPrice;
	else
		$res = false;
	return $res;
}

/**
 * Stores specified member as participant of specified event and sends notification
 * letter to him
 *
 * @param int $memberID					- member ID
 * @param int $eventID					- event ID
 * @param int $transactionID			- transaction ID in the database
 *
 * @return int/bool 					- true on success, 3 on mail wasn't sent, false otherwise
 *
 *
 */
function purchaseTicket( $memberID, $eventID, $transactionID )
{
	global $site;
	global $date_format;

	// argument validation
	$memberID = (int)$memberID;
	$eventID = (int)$eventID;
	$transactionID = (int)$transactionID;

	if ( $transactionID == 0 )
		$transactionID = 'NULL';

	$memberArr = getProfileInfo( $memberID );
	$eventArr = db_arr( "SELECT `ID`, `Title`, `Place`, DATE_FORMAT(`EventStart`, '$date_format' ) AS EventStart, `TicketPriceFemale`, `TicketPriceMale`, `TicketCountFemale`, `TicketCountMale` FROM `SDatingEvents`
					WHERE `ID` = $eventID
					AND `Status` = 'Active'" );
	$participantUID = $memberArr['NickName'] . $eventID . rand(100, 999);
	$res = db_res( "INSERT INTO `SDatingParticipants` SET `IDEvent` = {$eventID}, `IDMember` = {$memberID}, `ParticipantUID` = '{$participantUID}', `TransactionID` = {$transactionID}", 0 );

	$subject = getParam( 't_SDatingCongratulation_subject' );
	$messageText = getParam( 't_SDatingCongratulation' );


	$aPlus = array();
	$aPlus['NameSDating'] = $eventArr['Title'];
	$aPlus['PlaceSDating'] = $eventArr['Place'];
	$aPlus['WhenStarSDating'] = $eventArr['EventStart'];
	$aPlus['PersonalUID'] = $participantUID;
	$aPlus['LinkSDatingEvent'] = $site['url'] . 'events.php?action=show_info&amp;event_id=' . $eventID;

	$mailRes = sendMail( $memberArr['Email'], $subject, $messageText, $memberID, $aPlus );

	if ( $res )
		return ($mailRes ? true : 3);
	else
		return false;
}

?>
