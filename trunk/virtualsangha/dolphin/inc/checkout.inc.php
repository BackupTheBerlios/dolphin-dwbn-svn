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
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'sdating.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'membership_levels.inc.php' );

$separator = '|';
$dir['checkout'] = $dir['root'] . 'checkout/';
$site['checkout'] = $site['url'] . 'checkout/';

$memberID = (int)$_COOKIE['memberID'];
$currency_code = getParam( 'currency_code' );
$enable_recurring = getParam( 'enable_recurring' ) == 'on';

$cryptKey = 'secret_string';

/**
 * Implodes data array key elements using global separator
 *
 * @param array $data					- array with transaction data
 *
 * @return string 						- imploded string
 *
 *
 */
function transDataToString( $data )
{
	global $separator;

	$res = $data['memberID'] . $separator;
	$res .= $data['action'] . $separator;
	$res .= $data['amount'] . $separator;
	$res .= $data['cryptedAmount'] . $separator;
	$res .= $data['currency'] . $separator;
	$res .= $data['data'] . $separator;
	$res .= $data['cryptedData'] . $separator;
	$res .= $data['tranID'];

	return $res;
}

/**
 * Explodes string into data array using global separator
 *
 * @param string $string				- string to explode
 *
 * @return array 						- exploded associative array
 *
 *
 */
function transStringToData( $string )
{
	global $separator;

	$arr = explode( $separator, $string );
	$res = array();
	$res['memberID'] = $arr[0];
	$res['action'] = $arr[1];
	$res['amount'] = $arr[2];
	$res['cryptedAmount'] = $arr[3];
	$res['currency'] = $arr[4];
	$res['data'] = $arr[5];
	$res['cryptedData'] = $arr[6];
	$res['tranID'] = $arr[7];

	return $res;
}

/**
 * Encodes data string to pass it through checkout system
 *
 * @param string $string				- string to encode
 *
 * @return string 						- encoded string
 *
 *
 */
function encodeString( $string )
{
	return urlencode( base64_encode( $string ) );
}

/**
 * Decodes data string received from checkout system
 *
 * @param string $string				- string to decode
 *
 * @return string 						- decoded string
 *
 *
 */
function decodeString( $string )
{
	return base64_decode( urldecode( $string ) );
}

/**
 * Returns URL where member should be directed after payment completion
 *
 * @param string $checkoutAction		- payment type action (e.g memership, credits, etc)
 * @param string $data					- payment action data (e.g. membership ID or event ID)
 *
 * @return string 						- return URL
 *
 *
 */
function returnURLByAction( $checkoutAction, $data )
{
	global $site;

	switch ( $checkoutAction )
	{
		case 'membership':
			return "{$site['url']}membership.php";
		case 'speeddating':
			return "{$site['url']}events.php?action=show_info&event_id={$data}";
		case 'profiles':
			return "{$site['url']}result.php";
		default:
			return '';
	}
}

/**
 * Returns transaction description
 *
 * @param string $checkoutAction		- payment type action (e.g memership, credits, etc)
 * @param string $data					- payment action data (e.g. membership ID or event ID)
 * @param bool $languageParse			- indicates if description should be language-parsed
 *
 * @return string 						- return URL
 *
 *
 */
function returnDescByAction( $checkoutAction, $data, $languageParse )
{
	switch ( $checkoutAction )
	{
		case 'membership':
			$membershipArr = getMembershipInfo( $data );
			if ( $languageParse )
				return _t('_Membership purchase') . ' - ' . $membershipArr['Name'];
			else
				return 'Membership purchase - ' . $membershipArr['Name'];
		case 'speeddating':
			$eventArr = db_arr( 'SELECT `Title` FROM `SDatingEvents` WHERE `ID` = '. (int)$data );
			if ( $languageParse )
				return _t('_SpeedDating ticket purchase') . ' - ' . $eventArr['Title'];
			else
				return 'SpeedDating ticket purchase - ' . $eventArr['Title'];
		case 'profiles':
			if ( $languageParse )
				return _t('_Profiles purchase') . ' - ' . $data;
			else
				return 'Profiles purchase - ' . $data;
		default:
			return '';
	}
}

/**
 * Puts initial transaction data into the database
 *
 * @param array $checkoutData			- transaction data array
 * 		( 'checkout_action',			- action for which transaction need to be created
 * 		  'amount',						- payment sum
 * 		  'data',						- action specific data
 * 		  'description'					- transaction description
 * 		)
 *
 * @param int $memberID					- initiating member ID
 * @param int $providerID				- payment provider ID
 *
 * @return int/bool 					- ID of initiated transaction on success, false otherwise
 *
 *
 */
function initiateTransaction( $checkoutData, $memberID, $providerID )
{
	global $MySQL;
	global $cryptKey;
	global $currency_code;

	// arguments validation
	$providerID = (int)$providerID;
	$memberID = (int)$memberID;

	$transactionData['memberID'] = $memberID;
	$transactionData['action'] = $checkoutData['checkout_action'];
	$transactionData['amount'] = sprintf( "%.2f", (float)$checkoutData['amount'] );
	$transactionData['cryptedAmount'] = crypt( $transactionData['amount'], $cryptKey );
	$transactionData['currency'] = $currency_code;
	$transactionData['data'] = $checkoutData['data'];
	$transactionData['cryptedData'] = crypt( $transactionData['data'], $cryptKey );
	list($usec, $sec) = explode(' ', microtime());
	srand( $usec + $sec );
	$transactionData['tranID'] = time() . rand(1000, 9999);

	$transactionString = transDataToString( $transactionData );
	$transactionDesc = process_db_input( $checkoutData['description'], 0, 1 );
	$res = db_res( "INSERT INTO `Transactions` SET
						`IDMember` = {$memberID},
						`IDProvider` = {$providerID},
						`gtwTransactionID` = '{$transactionData['tranID']}',
						`Date` = NOW(),
						`Amount` = {$transactionData['amount']},
						`Currency` = '{$transactionData['currency']}',
						`Status` = 'pending',
						`Data` = '{$transactionString}',
						`Description` = '{$transactionDesc}'" );
	if ( $res && mysql_affected_rows( $MySQL->link ) > 0 )
	{
		return mysql_insert_id( $MySQL->link );
	}
	else
		return false;
}

/**
 * Updates transaction state and data in the database
 *
 * @param int $localTranID				- transaction ID in the database
 * @param string $gtwTranID				- payment gateway transaction identifier
 * @param bool $approved				- indicates if transaction was successful
 * @param string $note					- customer note for the transaction
 *
 * @return bool 						- true on success, false otherwise
 *
 *
 */
function finishTransaction( $localTranID, $gtwTranID, $approved = true, $note = '' )
{
	global $MySQL;

	// arguments validation
	$localTranID = (int)$localTranID;

	$gtwTranID = process_db_input( $gtwTranID );
	$note = process_db_input( $note );
	$status = ($approved ? 'approved' : 'declined');

	$res = db_res( "UPDATE `Transactions` SET
						`Status` = '{$status}',
						`Date` = NOW(),
						`gtwTransactionID` = '{$gtwTranID}',
						`Note` = '{$note}'
					WHERE `ID` = {$localTranID}
						AND `Status` = 'pending'" );
	if ( $res && mysql_affected_rows( $MySQL->link ) > 0 )
	{
		return true;
	}
	else
		return false;
}

/**
 * Performs script purchase actions for specified transaction
 *
 * @param int $localTranID				- transaction ID in the database
 * @param int $result					- purchase result from checkout file
 * 										  (it could be changed inside)
 *
 * @return bool 						- true on success, false otherwise
 *
 *
 */
function purchaseTransaction( $localTranID, &$result )
{
	// arguments validation
	$localTranID = (int)$localTranID;

	$tranRes = db_res( "SELECT `Data` FROM `Transactions`
							WHERE `ID` = {$localTranID}" );
	if ( !$tranRes || mysql_num_rows($tranRes) == 0 )
		return false;

	$tranArr = mysql_fetch_assoc( $tranRes );
	$tranData = transStringToData( $tranArr['Data'] );

	return performPurchase( $tranData['memberID'], $tranData['action'], $tranData['data'],
		$tranData['amount'], $result, $localTranID );
}

/**
 * Puts initial subscription data into the database
 *
 * @param int $localTranID				- ID of transaction which was initiated on checkout
 * 										  request
 * @param int $recurringDays			- subscription period days
 *
 * @return bool 						- true on success, false otherwise
 *
 *
 */
function initiateSubscription( $localTranID, $recurringDays )
{
	global $MySQL;

	// arguments validation
	$localTranID = (int)$localTranID;
	$recurringDays = (int)$recurringDays;

	// link subscription info to dummy transaction
	$res = db_res( "INSERT INTO `PaymentSubscriptions` SET
							`TransactionID` = {$localTranID},
							`StartDate` = NOW(),
							`Period` = {$recurringDays},
							`ChargesNumber` = 0" );
	if ( $res && mysql_affected_rows( $MySQL->link ) > 0 )
	{
		return true;
	}
	else
		return false;
}

/**
 * Removes subscription data from the database
 *
 * @param int $localTranID				- ID of transaction which was created on subscription
 *
 * @return bool 						- true on success, false otherwise
 *
 *
 */
function cancelSubscription( $localTranID )
{
	global $MySQL;

	// arguments validation
	$localTranID = (int)$localTranID;

	// remove subscription info with dummy transaction
	$res = db_res( "DELETE FROM `PaymentSubscriptions`
						WHERE `TransactionID` = {$localTranID}" );
	if ( !$res || mysql_affected_rows( $MySQL->link ) == 0 )
	{
		return false;
	}

	// delete dummy transaction
	$res = db_res( "DELETE FROM `Transactions`
						WHERE `ID` = {$localTranID}" );
	if ( $res && mysql_affected_rows( $MySQL->link ) > 0 )
	{
		return true;
	}
	else
		return false;
}

/**
 * Checks if specified member was subscribed by specified provider and then
 * initiates new transaction basing on data from transaction, which was created
 * on subscription
 *
 * @param int $localTranID				- ID of transaction which was created on subscription
 * 										  Function initiates new transaction using data from
 * 										  this transaction
 *
 * @return int/bool 					- ID of initiated transaction on success, false otherwise
 *
 *
 */
function initiateSubscriptionTransaction( $localTranID )
{
	// arguments validation
	$localTranID = (int)$localTranID;

	// select dummy transaction info
	$tranRes = db_res( "SELECT `IDProvider`, `Data` FROM `Transactions`
							WHERE `ID` = {$localTranID}" );
	if ( !$tranRes || mysql_num_rows($tranRes) == 0 )
		return false;

	$tranArr = mysql_fetch_assoc( $tranRes );
	$tranData = transStringToData( $tranArr['Data'] );

	// check if member subscribed
	$subsArr = db_arr( "SELECT `TransactionID`, `ChargesNumber` FROM `PaymentSubscriptions`
							WHERE `TransactionID` = {$localTranID}" );
	if ( !$subsArr )
		return false;

	$checkoutData['checkout_action'] = $tranData['action'];
	$checkoutData['amount'] = $tranData['amount'];
	$checkoutData['data'] = $tranData['data'];
	$checkoutData['description'] = returnDescByAction( $tranData['action'], $tranData['data'], true );

	$res = initiateTransaction( $checkoutData, $tranData['memberID'], $tranArr['IDProvider'] );
	if ( !$res )
		return false;
	else
		return $res;
}

/**
 * Checks if specified member was subscribed by specified provider then
 * updates database info about subscription and calls finishTransaction function
 *
 * @param int $localTranID				- transaction ID in the database
 * @param int $subsTranID				- ID of transaction which was created on subscription
 * @param string $gtwTranID				- payment gateway transaction identifier
 * @param bool $approved				- indicates if transaction was successful
 * @param string $note					- customer note for the transaction
 *
 * @return bool 						- true on success, false otherwise
 *
 *
 */
function finishSubscriptionTransaction( $localTranID, $subsTranID, $gtwTranID, $approved = true, $note = '' )
{
	global $MySQL;

	// arguments validation
	$localTranID = (int)$localTranID;
	$subsTranID = (int)$subsTranID;

	$res = true;
	if ( $approved )
	{
		$res = db_res( "UPDATE `PaymentSubscriptions` SET
								`ChargesNumber` = `ChargesNumber` + 1
							WHERE `TransactionID` = {$subsTranID}" );
		$res = ( $res && mysql_affected_rows( $MySQL->link ) > 0);
	}

	if ( $res )
	{
		return finishTransaction( $localTranID, $gtwTranID, $approved, $note );
	}
	else
		return false;
}

/**
 * Performs script purchase actions for specified parameters and makes fraud check also
 *
 * @param int $memberID					- member ID
 * @param string $checkoutAction		- payment type action (e.g memership, credits, etc)
 * @param string $data					- payment action data (e.g. membership ID or event ID)
 * @param string $amount				- payment sum
 * @param int $result					- purchase result from checkout file
 * 										  (it could be changed inside)
 * @param int $localTranID				- transaction ID in the database
 *
 * @return bool 						- true on success, false otherwise
 *
 *
 */
function performPurchase( $memberID, $checkoutAction, $data, $amount, &$result, $localTranID = 0 )
{
	global $site;

	// arguments validation
	$amount = sprintf( '%.2f', (float)$amount );

	switch ( $checkoutAction )
	{
		case 'membership':
			$membershipID = (int)$data;
			$res = buyMembership( $memberID, $membershipID, $amount, $localTranID );
			return $res;

		case 'speeddating':
			$eventID = (int)$data;
			$res = isTicketAvailable( $memberID, $eventID );
			// fraud check
			if ( $res === false )
				return false;
			if ( $res != $amount )
				return false;

			$res = purchaseTicket( $memberID, $eventID, $localTranID );
			// if ticket purchased, but email wasn't sent then change result value
			if ( $res === 3 )
			{
				$result = 3;
				return true;
			}
			else
			{
				return $res;
			}

		default:
			return false;
	}
}

/**
 * Performs appropriate action for payment result
 *
 * If database transaction ID specified and it is possible to determine return URL
 * then it posts result to return page. Otherwise it just shows error message.
 *
 * @param int $result					- payment result
 * 		-1 - fraud attempt
 * 		 0 - transaction declined or not completed
 * 		 1 - transaction successful
 * 		 2 - internal error
 * @param string $errorMessage			- error message which was
 * @param int $localTranID				- transaction ID
 *
 *
 */
function processValidationResult( $result, $errorMessage, $localTranID = 0, $returnURL = '' )
{
	global $site;

	// arguments validation
	$result = (int)$result;
	$localTranID = (int)$localTranID;

	$formData = array( 'result' => $result );

	if ( $localTranID )
	{
		$tranRes = db_res( "SELECT `Data` FROM `Transactions`
								WHERE `ID` = {$localTranID}" );
		if ( $tranRes && mysql_num_rows($tranRes) > 0 )
		{
			$tranArr = mysql_fetch_assoc($tranRes);
			$tranData = transStringToData( $tranArr['Data'] );
			$returnURL = returnURLByAction( $tranData['action'], $tranData['data'] );
		}
	}

	switch ( $result )
	{
		case -1:
			reportFraudAttempt( $errorMessage );
			if ( strlen($returnURL) )
				Redirect( $returnURL, $formData, 'post' );
			else
				PrintErrorPage( _t('_RESULT-1') );
			break;

		case 0:
			if ( strlen($returnURL) )
				Redirect( $returnURL, $formData, 'post' );
			else
				PrintErrorPage( _t('_RESULT0') );
			break;

		case 1:
			if ( strlen($returnURL) )
				Redirect( $returnURL, $formData, 'post' );
			else
				Redirect( $site['url'] . 'member.php', $formData, 'post' );
			break;

		case 2:
			PrintErrorPage( 'Internal error occured: ' . $errorMessage );
			break;

		case 1000:
			if ( strlen($returnURL) )
				Redirect( $returnURL, $formData, 'post' );
			else
				PrintErrorPage( _t('_RESULT1000') );
			break;
	}
}

/**
 * Sends email to site admin with payment and user information
 *
 * @return bool 						- true if sending succeed, false otherwise
 *
 *
 */
function reportFraudAttempt( $message )
{
	global $site;

	$paymentModuleName = PAYMENT_MODULE_NAME;
	$userIP = getenv('HTTP_CLIENT_IP') ? getenv('HTTP_CLIENT_IP') : getenv('REMOTE_ADDR');
	$currentDatetime = date("l dS of F Y h:i:s A");


	$subject = 'Fraud attempt report';

	$message = <<<EOM
Fraud attempt was detected in {$site['title']} checkout system.
Here is details:
	Date/time: {$currentDatetime}
	IP: {$userIP}
	Payment module name: {$paymentModuleName}
	Message: {$message}
EOM;

	$ret = sendMail( $site['email'], $subject, $message );


	return $ret;
}

/**
 * Selects all or only active payment providers and returns array with their
 * names and captions
 *
 * @param bool $activeOnly				- indicates if only active payment providers should be
 * 										  selected
 *
 * @return array 						- array of providers
 * 				( providerID => Array('Name' => providerName, 'Caption' => providerCaption),
 * 				  ... )
 *
 *
 */
function getPaymentProviders( $activeOnly = false )
{
	$result = array();
	if ( $activeOnly )
		$queryFilter = 'WHERE `Active`';

	$providerRes = db_res( "SELECT `ID`, `Name`, `Caption` FROM `PaymentProviders` {$queryFilter}" );
	while ( $providerArr = mysql_fetch_assoc($providerRes) )
	{
		$result[$providerArr['ID']]['Name'] = $providerArr['Name'];
		$result[$providerArr['ID']]['Caption'] = $providerArr['Caption'];
	}

	return $result;
}

/**
 * Reads current module configuration from the database
 *   (according to the 'PAYMENT_MODULE_NAME' constant)
 *
 *
 */
function initProviderConfiguration()
{
	global $providerConf;

	$providerConf = array();

	if ( !defined( 'PAYMENT_MODULE_NAME' ) )
		return;
	$providerRes = db_res( "SELECT `ID`, `Name`, `Caption`, `Active`, `Mode`, `Debug`, `CheckoutFilename`, `CheckoutURL`, `SupportsRecurring` FROM `PaymentProviders` WHERE `Name` = '". PAYMENT_MODULE_NAME ."'" );
	$providerArr = mysql_fetch_assoc($providerRes);
	foreach ( $providerArr as $key => $value )
		$providerConf[$key] = $value;

	$paramRes = db_res( "SELECT `Name`, `Type`, `Value` FROM `PaymentParameters` WHERE `IDProvider` = {$providerConf['ID']}" );
	while ( $paramArr = mysql_fetch_assoc($paramRes) )
	{
		if ( $paramArr['Type'] == 'check' )
			$providerConf['Param_' . $paramArr['Name']] = ($paramArr['Value'] == 'on' ? true : false);
		else
			$providerConf['Param_' . $paramArr['Name']] = $paramArr['Value'];
	}
}

/**
 * Performs common payment module configuration validation
 *
 * @param string &$errorMessage			- error message when return result is not true
 *
 * @return bool 						- true if configuration is valid, false otherwise
 *
 *
 */
function commonValidateConfiguration( &$errorMessage )
{
	global $providerConf;
	global $debugFilename;

	if ( !defined( 'PAYMENT_MODULE_NAME' ) )
		return false;

	if ( $providerConf['Debug'] )
	{
		$fileExists = file_exists( $debugFilename );
		if ( $fileExists )
		{
				clearstatcache();
				$perms = fileperms( $debugFilename );
				$fileRWAccessible = ($perms & 0x0004 && $perms & 0x0002) ? true : false;
		}
		if ( !$fileExists || !$fileRWAccessible )
		{
			$errorMessage = 'Debug mode enabled, but debug file is not writable';
			return false;
		}
	}

	return true;
}

/**
 * Performs common payment transaction validation
 *
 * @param int $localTranID				- transaction ID
 * @param string $amount				- payment sum, received from the gateway
 * @param string &$errorMessage			- error message when return result is not true
 *
 * @return bool 						- true if configuration is valid, false otherwise
 *
 *
 */
function commonValidateTransaction( $localTranID, $amount, &$errorMessage )
{
	global $cryptKey;

	// arguments validation
	$localTranID = (int)$localTranID;

	$tranRes = db_res( "SELECT `Data` FROM `Transactions`
							WHERE `ID` = {$localTranID}" );
	if ( !$tranRes || mysql_num_rows($tranRes) == 0 )
	{
		$errorMessage = 'Invalid transaction ID';
		return false;
	}

	$tranArr = mysql_fetch_assoc( $tranRes );
	$tranData = transStringToData( $tranArr['Data'] );

	if ( $tranData['amount'] != sprintf( "%.2f", (float)$amount ) )
	{
		$errorMessage = 'Invalid payment sum';
		return false;
	}

	$res = db_res( "SELECT `ID` FROM `Profiles` WHERE `ID` = {$tranData['memberID']}" );
	if ( !$res )
	{
		$errorMessage = 'Invalid member ID';
		return false;
	}

	if ( crypt($tranData['amount'], $cryptKey) != $tranData['cryptedAmount'] ||
		 crypt($tranData['data'], $cryptKey) != $tranData['cryptedData'] )
	 {
		$errorMessage = 'Invalid verification data';
		return false;
	 }

	return true;
}

/**
 * Writes debug output to the appropriate text file
 *
 * @param string $dataName				- name of data to be written
 * @param undef $data					- data to be written
 * @param bool $includeDateTime			- indicates if date and time should be included into the output
 *
 * @return bool 						- true if write success, false otherwise
 *
 *
 */
function writeDebugLog( $dataName, $data, $includeDateTime = true )
{
	global $providerConf;
	global $debugFilename;

	if ( !defined( 'PAYMENT_MODULE_NAME' ) )
		return false;

	$fileExists = file_exists( $debugFilename );
	if ( $fileExists )
	{
		clearstatcache();
		$perms = fileperms( $debugFilename );
		$fileRWAccessible = ($perms & 0x0004 && $perms & 0x0002) ? true : false;
	}

	if ( !$fileExists || !$fileRWAccessible )
		return false;

	$fp = fopen( $debugFilename, 'a' );

	if ( $includeDateTime )
		fwrite( $fp, 'Debug started at ' . date ("l dS of F Y h:i:s A") . "\n" );

	if ( is_array($data) )
	{
		fwrite( $fp, "\t{$dataName}: Array\n" );
		foreach ( $data as $key => $value )
			fwrite( $fp, "\t\t{$key}: {$value}\n" );
	}
	else
	{
		fwrite( $fp, "\t{$dataName}: {$data}\n" );
	}

	fclose( $fp );

	return true;
}

/**
 * Calculates financial statistic for specified query parameters grouped by
 * transaction type for specified affiliate
 *
 * @param int $affID					- affiliate ID
 * @param array $tranArray				- array with data for transaction query
 * 			( 'order_num',				- select transaction with specified order number
 * 			  'last_days',				- select transactions for specified last days
 * 			  'exact_date',				- select transactions for specified date
 * 			  'between_date1',			- select transactions between date1 and date2
 * 			  'between_date2' )
 *
 * @return array 						- array with financial statistic
 * 			( 'membership_amount',		- sum of membership transactions
 * 			  'sales_amount',			- sum of profile purchase transactions
 * 			  'sdating_amount',			- sum of SpeedDating ticket purchase transactions
 * 			  'total' )					- total sum
 *
 *
 */
function getFinanceAffStat( $affID, $tranArray )
{
	global $currency_code;
	global $separator;

	$affID = (int)$affID;

	if ( isset($tranArray['order_num']) )
	{
		$tranFilter = "`gtwTransactionID` = '". process_db_input( $tranArray['order_num'], 0, 1 ) ."'";
	}
	elseif ( isset($tranArray['last_days']) )
	{
		$tranFilter = "( TO_DAYS( NOW() ) - TO_DAYS( `Date` ) <= ". ((int)$tranArray['last_days']) ." )";
	}
	elseif ( isset($tranArray['exact_date']) )
	{
		$exactDate = strtotime( $tranArray['exact_date'] );
		if ( $exactDate != -1 )
			$tranFilter = "TO_DAYS( FROM_UNIXTIME($exactDate) ) = TO_DAYS( `Date` )";
		else
			$tranFilter = "1";
	}
	elseif ( isset($tranArray['between_date1']) && isset($tranArray['between_date2']) )
	{
		if ( $tranArray['between_date1'] == 'start' )
			$betweenDate1 = 0;
		elseif ( $tranArray['between_date1'] == 'now' )
			$betweenDate1 = time();
		else
			$betweenDate1 = strtotime( $tranArray['between_date1'] );
		if ( $tranArray['between_date2'] == 'start' )
			$betweenDate2 = 0;
		elseif ( $tranArray['between_date2'] == 'now' )
			$betweenDate2 = time();
		else
			$betweenDate2 = strtotime( $tranArray['between_date2'] );

		if ( $betweenDate1 != -1 && $betweenDate2 != -1 )
			$tranFilter = "( TO_DAYS( FROM_UNIXTIME($betweenDate1) ) <= TO_DAYS( `Date` ) AND TO_DAYS( FROM_UNIXTIME($betweenDate2) ) >= TO_DAYS( `Date` ) )";
		else
			$tranFilter = "1";
	}
	else
	{
		$tranFilter = "1";
	}

	// Fill the array
	$fin = array();
	$arr = db_arr( "SELECT `Percent` FROM `aff` WHERE `ID` = $affID");
	$percent = (float)$arr['Percent'] / 100.0;
	$arr = db_arr( "SELECT SUM( `Amount` ) FROM `Transactions` INNER JOIN `aff_members` ON (`idProfile` = `IDMember`) WHERE {$tranFilter} AND `idAff` = {$affID} AND `Status` = 'approved' AND `Data` LIKE '%{$separator}membership{$separator}%' AND `Currency` = '{$currency_code}'" );
	$fin['membership_amount'] = sprintf( "%.2f", $percent * (float)$arr[0] );
	$arr = db_arr( "SELECT SUM( `Amount` ) FROM `Transactions` INNER JOIN `aff_members` ON (`idProfile` = `IDMember`) WHERE {$tranFilter} AND `idAff` = {$affID} AND `Status` = 'approved' AND `Data` LIKE '%{$separator}profiles{$separator}%' AND `Currency` = '{$currency_code}'" );
	$fin['sales_amount'] = sprintf( "%.2f", $percent * (float)$arr[0] );
	$arr = db_arr( "SELECT SUM( `Amount` ) FROM `Transactions` INNER JOIN `aff_members` ON (`idProfile` = `IDMember`) WHERE {$tranFilter} AND `idAff` = {$affID} AND `Status` = 'approved' AND `Data` LIKE '%{$separator}speeddating{$separator}%' AND `Currency` = '{$currency_code}'" );
	$fin['sdating_amount'] = sprintf( "%.2f", $percent * (float)$arr[0] );
	$arr = db_arr( "SELECT SUM( `Amount` ) FROM `Transactions` INNER JOIN `aff_members` ON (`idProfile` = `IDMember`) WHERE {$tranFilter} AND `idAff` = {$affID} AND `Status` = 'approved' AND `Currency` = '{$currency_code}'" );
	$fin['total'] = sprintf( "%.2f", $percent * (float)$arr[0] );

	return $fin;
}

/**
 * Calculates financial statistic for specified query parameters grouped by
 * transaction type
 *
 * @param array $tranArray				- array with data for transaction query
 * 			( 'order_num',				- select transaction with specified order number
 * 			  'last_days',				- select transactions for specified last days
 * 			  'exact_date',				- select transactions for specified date
 * 			  'between_date1',			- select transactions between date1 and date2
 * 			  'between_date2' )
 *
 * @return array 						- array with financial statistic
 * 			( 'membership_amount',		- sum of membership transactions
 * 			  'sales_amount',			- sum of profile purchase transactions
 * 			  'sdating_amount',			- sum of SpeedDating ticket purchase transactions
 * 			  'total' )					- total sum
 *
 *
 */
function getFinanceStat( $tranArray )
{
	global $currency_code;
	global $separator;

	if ( isset($tranArray['order_num']) )
	{
		$tranFilter = "`gtwTransactionID` = '". process_db_input( $tranArray['order_num'], 0, 1 ) ."'";
	}
	elseif ( isset($tranArray['last_days']) )
	{
		$tranFilter = "( TO_DAYS( NOW() ) - TO_DAYS( `Date` ) <= ". ((int)$tranArray['last_days']) ." )";
	}
	elseif ( isset($tranArray['exact_date']) )
	{
		$exactDate = strtotime( $tranArray['exact_date'] );
		if ( $exactDate != -1 )
			$tranFilter = "TO_DAYS( FROM_UNIXTIME($exactDate) ) = TO_DAYS( `Date` )";
		else
			$tranFilter = "1";
	}
	elseif ( isset($tranArray['between_date1']) && isset($tranArray['between_date2']) )
	{
		if ( $tranArray['between_date1'] == 'start' )
			$betweenDate1 = 0;
		elseif ( $tranArray['between_date1'] == 'now' )
			$betweenDate1 = time();
		else
			$betweenDate1 = strtotime( $tranArray['between_date1'] );
		if ( $tranArray['between_date2'] == 'start' )
			$betweenDate2 = 0;
		elseif ( $tranArray['between_date2'] == 'now' )
			$betweenDate2 = time();
		else
			$betweenDate2 = strtotime( $tranArray['between_date2'] );

		if ( $betweenDate1 != -1 && $betweenDate2 != -1 )
			$tranFilter = "( TO_DAYS( FROM_UNIXTIME($betweenDate1) ) <= TO_DAYS( `Date` ) AND TO_DAYS( FROM_UNIXTIME($betweenDate2) ) >= TO_DAYS( `Date` ) )";
		else
			$tranFilter = "1";
	}
	else
	{
		$tranFilter = "1";
	}

	// Fill the array
	$fin = array();
	$arr = db_arr( "SELECT SUM( `Amount` ) FROM `Transactions` WHERE {$tranFilter} AND `Status` = 'approved' AND `Data` LIKE '%{$separator}membership{$separator}%' AND `Currency` = '{$currency_code}'" );
	$fin['membership_amount'] = sprintf( "%.2f", (float)$arr[0] );
	$arr = db_arr( "SELECT SUM( `Amount` ) FROM `Transactions` WHERE {$tranFilter} AND `Status` = 'approved' AND `Data` LIKE '%{$separator}profiles{$separator}%' AND `Currency` = '{$currency_code}'" );
	$fin['sales_amount'] = sprintf( "%.2f", (float)$arr[0] );
	$arr = db_arr( "SELECT SUM( `Amount` ) FROM `Transactions` WHERE {$tranFilter} AND `Status` = 'approved' AND `Data` LIKE '%{$separator}speeddating{$separator}%' AND `Currency` = '{$currency_code}'" );
	$fin['sdating_amount'] = sprintf( "%.2f", (float)$arr[0] );
	$arr = db_arr( "SELECT SUM( `Amount` ) FROM `Transactions` WHERE {$tranFilter} AND `Status` = 'approved' AND `Currency` = '{$currency_code}'" );
	$fin['total'] = sprintf( "%.2f", (float)$arr[0] );

	return $fin;
}

?>