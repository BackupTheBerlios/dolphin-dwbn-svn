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

/*

PayPal

*/

// It is recommended to put full path to header file here
// You SHOULD put full path if this script is not in default folder
if ( !defined('PAYMENT_MODULE_AS_HEADER') )
{
	require_once( "../inc/header.inc.php" );
}
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'checkout.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'languages.inc.php' );

define( 'PAYMENT_MODULE_NAME', 'paypal' );
initProviderConfiguration();
if ( strlen(trim($providerConf['CheckoutFilename'])) )
	$checkoutFilename = trim($providerConf['CheckoutFilename']);
else
	$checkoutFilename = $dir['checkout'] . PAYMENT_MODULE_NAME . '.php';
if ( strlen(trim($providerConf['CheckoutURL'])) )
	$checkoutURL = trim($providerConf['CheckoutURL']);
else
	$checkoutURL = $site['checkout'] . PAYMENT_MODULE_NAME . '.php';
$debugFilename = $dir['checkout'] . 'debug/' . PAYMENT_MODULE_NAME . '_debug.txt';


/**	
 * Performs payment module specific configuration validation
 * 
 * @param string &$errorMessage			- error message when return result is not true
 * 
 * @return bool 						- true if configuration is valid, false otherwise
 * 
 * 
 */
function moduleValidateConfiguration( &$errorMessage )
{
	global $providerConf;
	
	$commomResult = commonValidateConfiguration( $errorMessage );
	if ( !$commomResult )
		return false;
	
	if ( $providerConf['Mode'] == 'live' && strlen(trim($providerConf['Param_business'])) == 0 )
	{
		$errorMessage = '\'Business\' field is empty';
		return false;
	}
	
	if ( !in_array($providerConf['Param_process_type'], array('Direct', 'PDT', 'IPN')) )
	{
		$errorMessage = '\'Process type\' field has incorrect value';
		return false;
	}
	
	if ( $providerConf['Param_process_type'] == 'PDT' && strlen(trim($providerConf['Param_auth_token'])) == 0 )
	{
		$errorMessage = '\'Identity token\' should be non-empty for PDT process type';
		return false;
	}
	
	if ( !in_array($providerConf['Param_connection_type'], array('SSL', 'HTTP')) )
	{
		$errorMessage = '\'Connection type\' field has incorrect value';
		return false;
	}
	
	if ( $providerConf['Param_connection_type'] == 'SSL' && !extension_loaded('openssl') )
	{
		$errorMessage = 'Your server doesn\'t support SSL connection type';
		return false;
	}
	
	if ( $providerConf['Mode'] != 'live' && strlen(trim($providerConf['Param_test_business'])) == 0 )
	{
		$errorMessage = '\'SandBox Business\' field is empty';
		return false;
	}
	
	return true;
}

/**	
 * Selects payment sum according site currency code
 * 
 * @param array $dataArr				- array with received data
 * 
 * @return string 						- payment sum in specified currency
 * 
 * WARNING! If currency code on the site not supported by PayPal, or automatical exchange is
 * not set on the account, then return result will be empty
 * 
 * 
 */
function getPaymentAmount( $dataArr )
{
	global $currency_code;
	
	if ( $dataArr['mc_currency'] == 'USD' && $currency_code == 'USD' && strlen($dataArr['payment_gross']) )
	{
		return $dataArr['payment_gross'];
	}
	elseif ( $dataArr['mc_currency'] == $currency_code && strlen($dataArr['mc_gross']) )
	{
		return $dataArr['mc_gross'];
	}
	elseif ( $dataArr['settle_currency'] == $currency_code && strlen($dataArr['settle_amount']) )
	{
		return $dataArr['settle_amount'];
	}
	return '';
}

/**	
 * Performs MD5 hash custom verification
 * 
 * @param array $dataArr				- array with received data
 * @param string &$errorMessage			- error message when return result is not true
 * 
 * @return bool 						- true if verification is passed, false otherwise
 * 
 * 
 */
function customCheck( $dataArr, &$errorMessage )
{
	global $cryptKey;
	global $date_format;
	
	$tranRes = db_res( "SELECT DATE_FORMAT(`Date`,  '$date_format' ) AS 'Date', `Data` FROM `Transactions`
							WHERE `ID` = {$dataArr['item_number']}" );
	$tranArr = mysql_fetch_assoc( $tranRes );
	
	if ( $dataArr['custom'] != md5( $tranArr['Date'] . $tranArr['Data'] . $cryptKey ) )
	{
		$errorMessage = 'Custom check failed';
		return false;
	}
	
	return true;
}

/**	
 * Validate if transaction real and successful
 * 
 * @param array $dataArr				- array with data to validate
 * @param string &$errorMessage			- error message when return result is not 1
 * 
 * @return int 							- validation result
 * 										  possible variants:
 * 											-1 - fraud attempt
 * 											 0 - transaction was declined
 * 											 1 - transaction was approved
 * 											 2 - inner error
 * 
 * 
 */
function moduleValidateTransaction( &$dataArr, &$errorMessage )
{
	global $providerConf;
	
	$maxReadSize = 8192;
	
	if ( $providerConf['Param_process_type'] == 'Direct' || $providerConf['Param_process_type'] == 'IPN' )
	{
		if ( $dataArr['payment_status'] != 'Completed' )
		{
			$errorMessage = 'Payment is not completed';
			return 0;
		}
		
		if ( $providerConf['Mode'] != 'live' )
			$businessValue = $providerConf['Param_test_business'];
		else
			$businessValue = $providerConf['Param_business'];
		if ( $dataArr['business'] != $businessValue )
		{
			$errorMessage = 'Wrong receiver email';
			return -1;
		}
		
		if ( $providerConf['Debug'] )
		{
			writeDebugLog( 'Direct/IPN validation for transaction', $dataArr, false );
		}
		
		$req = 'cmd=_notify-validate';
		foreach ( $dataArr as $key => $value )
			$req .= '&'. urlencode($key) .'='. urlencode( process_pass_data($value) );
		
		// post back to PayPal system to validate
		$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Host: www.paypal.com\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n";
		$header .= "Connection: close\r\n\r\n";
		
		// open socket
		if ( $providerConf['Mode'] != 'live' )
			$connectURL = 'www.sandbox.paypal.com';
		else
			$connectURL = 'www.paypal.com';
		if ( $providerConf['Param_connection_type'] == 'SSL' )
			$fp = fsockopen("ssl://{$connectURL}", 443, $errno, $errstr, 60);
		else
			$fp = fsockopen("tcp://{$connectURL}", 80, $errno, $errstr, 60);
		
		if ( !$fp )
		{
			$errorMessage = "Can't connect to remote host for validation ($errstr)";
			return 2;
		}
		
		// send data
		fputs($fp, $header . $req);
		
		// read the body data
		$response = fread( $fp, $maxReadSize );
		$responseArr = explode( "\r\n\r\n", $response );
		$responseHeader = $responseArr[0];
		$res = $responseArr[1];
		
		// parse the data
		$lines = explode("\n", $res);
		array_walk( $lines, create_function('&$arg', "\$arg = trim(\$arg);") );
		if ( $providerConf['Debug'] )
		{
			writeDebugLog( 'Direct/IPN reply lines', $lines, false );
		}
		
		if ( strcmp($lines[0], "INVALID") == 0 )
		{
			$errorMessage = 'Transaction verification failed';
			fclose($fp);
			return -1;
		}
		elseif ( strcmp($lines[0], "VERIFIED") != 0 )
		{
			$errorMessage = 'No verification status received';
			fclose($fp);
			return 2;
		}
		
		$paymentAmount = getPaymentAmount( $dataArr );
		if ( !commonValidateTransaction( $dataArr['item_number'], $paymentAmount, $errorMessage ) )
		{
			return -1;
		}
		
		if ( !customCheck($dataArr, $errorMessage) )
		{
			return -1;
		}
		
		fclose($fp);
		return 1;
	}
	elseif ( $providerConf['Param_process_type'] == 'PDT' )
	{
		if ( $providerConf['Debug'] )
		{
			writeDebugLog( 'PDT validation for transaction', $dataArr, false );
		}
		
		$req = 'cmd=_notify-synch';
		$req .= "&tx={$dataArr{'tx'}}&at={$providerConf['Param_auth_token']}";
		
		// post back to PayPal system to validate
		$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Host: www.paypal.com\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n";
		$header .= "Connection: close\r\n\r\n";
		
		// open socket
		if ( $providerConf['Mode'] != 'live' )
			$connectURL = 'www.sandbox.paypal.com';
		else
			$connectURL = 'www.paypal.com';
		if ( $providerConf['Param_connection_type'] == 'SSL' )
			$fp = fsockopen("ssl://{$connectURL}", 443, $errno, $errstr, 60);
		else
			$fp = fsockopen("tcp://{$connectURL}", 80, $errno, $errstr, 60);
		
		if ( !$fp )
		{
			$errorMessage = "Can't connect to remote host for validation ($errstr)";
			return 2;
		}
		
		// send data
		fputs($fp, $header . $req);
		
		// read the body data
		$res = '';
		$headerdone = false;
		while ( !feof($fp) )
		{
			$line = fgets($fp, 1024);
			
			if (strcmp( $line, "\r\n" ) == 0)
			{
				// read the header
				$headerdone = true;
			}
			elseif ( $headerdone )
			{
				// header has been read. now read the contents
				$res .= $line;
			}
		}
		
		// parse the data
		$lines = explode("\n", $res);
		if ( $providerConf['Debug'] )
		{
			writeDebugLog( 'PDT reply lines', $lines, false );
		}
		
		if ( strcmp($lines[0], "FAIL") == 0 )
		{
			$errorMessage = 'Transaction verification failed';
			fclose($fp);
			return -1;
		}
		elseif ( strcmp($lines[0], "SUCCESS") != 0 )
		{
			$errorMessage = 'No verification status received';
			fclose($fp);
			return 2;
		}
		
		fclose($fp);
		
		for ( $i = 1; $i < count($lines); $i++ )
		{
			list($key, $val) = explode("=", $lines[$i]);
			$keyarray[urldecode($key)] = urldecode($val);
		}
		
		$dataArr['item_name'] = $keyarray['item_name'];
		$dataArr['item_number'] = $keyarray['item_number'];
		$dataArr['payment_status'] = $keyarray['payment_status'];
		$dataArr['custom'] = $keyarray['custom'];
		$dataArr['memo'] = $keyarray['memo'];
		$dataArr['business'] = $keyarray['business']; 
		$dataArr['payment_gross'] = $keyarray['payment_gross'];
		$dataArr['mc_gross'] = $keyarray['mc_gross'];
		$dataArr['mc_currency'] = $keyarray['mc_currency'];
		$dataArr['settle_amount'] = $keyarray['settle_amount'];
		$dataArr['settle_currency'] = $keyarray['settle_currency'];
		$dataArr['exchange_rate'] = $keyarray['exchange_rate'];
		$dataArr['payer_email'] = $keyarray['payer_email'];
		$dataArr['txn_id'] = $keyarray['txn_id'];
		
		if ( $dataArr['payment_status'] != 'Completed' )
		{
			$errorMessage = 'Payment is not completed';
			return 0;
		}
		
		if ( $providerConf['Mode'] != 'live' )
			$businessValue = $providerConf['Param_test_business'];
		else
			$businessValue = $providerConf['Param_business'];
		if ( $dataArr['business'] != $businessValue )
		{
			$errorMessage = 'Wrong receiver email';
			return -1;
		}
		
		$paymentAmount = getPaymentAmount( $dataArr );
		if ( !commonValidateTransaction( $dataArr['item_number'], $paymentAmount, $errorMessage ) )
		{
			return -1;
		}
		
		if ( !customCheck($dataArr, $errorMessage) )
		{
			return -1;
		}
		
		return 1;
	}
	
	return 2;
}

/**	
 * Starts transaction process for specified transaction
 * 
 * @param int $localTranID				- starting transaction database ID
 * @param bool $recurring				- indicates whether transaction recurring or not
 * @param int $recurringDays			- if $recurring true, then this value specifies
 * 										  subscription days
 * 
 * @return bool 						- true if start is successful, false otherwise
 * 
 * 
 */
function moduleStartTransaction( $localTranID, $recurring = false, $recurringDays = 0 )
{
	global $providerConf;
	global $checkoutURL;
	global $memberID; // defined in checkout.inc.php
	global $cryptKey;
	global $currency_code;
	global $enable_recurring;
	global $date_format;
	
	// validate arguments
	$localTranID = (int)$localTranID;
	$recurringDays = (int)$recurringDays;
	
	$tranRes = db_res( "SELECT DATE_FORMAT(`Date`,  '$date_format' ) AS 'Date', `Amount`, `Currency`, `Status`, `Data`, `Description` FROM `Transactions`
							WHERE `ID` = {$localTranID}
							AND `Status` = 'pending'
							AND `IDProvider` = {$providerConf['ID']}" );
	if ( !$tranRes || mysql_num_rows($tranRes) == 0 )
		return false;
	$tranArr = mysql_fetch_assoc($tranRes);
	$tranData = transStringToData( $tranArr['Data'] );
	
	if ( $providerConf['Mode'] != 'live' )
		$actionURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	else
		$actionURL = 'https://www.paypal.com/cgi-bin/webscr';
	
	$formData = array();
	// command and recurring parameters
	if ( $recurring )
	{
		$formData['cmd'] = '_xclick-subscriptions';
		$formData['a3'] = sprintf( "%.2f", (float)$tranArr['Amount'] );
		$formData['p3'] = $recurringDays;
		$formData['t3'] = 'D';
		$formData['src'] = '1'; // repeat billings unles member cancels subscription
		$formData['sra'] = '1'; // reattempt on failure
	}
	else
	{
		$formData['cmd'] = '_xclick';
		$formData['amount'] = sprintf( "%.2f", (float)$tranArr['Amount'] );
	}
	// business (merchant ID)
	if ( $providerConf['Mode'] != 'live' )
		$formData['business'] = $providerConf['Param_test_business'];
	else
		$formData['business'] = $providerConf['Param_business'];
	// transaction common data
	$formData['item_name'] = $tranArr['Description'];
	$formData['item_number'] = $localTranID;
	$formData['currency_code'] = $currency_code;
	$formData['no_note'] = $providerConf['Param_no_note'] ? '1' : '0';
	$formData['no_shipping'] = '1';
	$formData['custom'] = md5( $tranArr['Date'] . $tranArr['Data'] . $cryptKey );
	
	// return and redirect
	switch ( $providerConf['Param_process_type'] )
	{
		case 'Direct':
			$formData['return'] = $checkoutURL;
			$formData['rm'] = '2';
			break;
		
		case 'IPN':
			$returnURL = returnURLByAction( $tranData['action'], $tranData['data'] );
			$formData['return'] = $returnURL;
			$formData['notify_url'] = $checkoutURL;
			$formData['rm'] = '1';
			break;
		
		case 'PDT':
			$formData['return'] = $checkoutURL;
			$formData['rm'] = '2';
			break;
	}
	
	Redirect($actionURL, $formData, 'post', $providerConf['Caption']);
	exit();
}

/**	
 * Performs server side call payment processing
 * 
 * @param bool $subscribe				- indicates if payment is subcriptional payment
 * @param int $newTrandID				- if payment subscriptional, then $newTrandID specfies
 * 										  new transaction ID created by script
 * 
 * @return bool 						- true if payment is successful, false otherwise
 * 
 * 
 */
function moduleAcceptPayment( $subscribe, $newTrandID = 0 )
{
	global $providerConf;
	
	$errorMessage = '';
	
	if ( $providerConf['Debug'] )
	{
		writeDebugLog( 'Payment event', 'Payment start. Subscriptional: ' . ($subscribe ? 'true' : 'false'), false );
	}
	
	if ( $providerConf['Param_process_type'] == 'Direct' || $providerConf['Param_process_type'] == 'IPN' )
	{
		if ( !isset($_POST['item_number']) || !isset($_POST['txn_id']) )
		{
			PrintErrorPage( _t('_no data given') );
			return false;
		}
		
		$transactionData = $_POST;
		$res = moduleValidateTransaction( $transactionData, $errorMessage );
		
		if ( $subscribe && $newTrandID )
			$localTranID = $newTrandID;
		else
			$localTranID = (int)$transactionData['item_number'];
		
		if ( $res != 2 )
		{
			if ( $subscribe )
			{
				finishTransaction(
					$transactionData['item_number'],
					'dummy',
					false,
					'This dummy transaction was created on subscription and contains subscriptional data.' );
				finishSubscriptionTransaction(
					$localTranID,
					$transactionData['item_number'],
					$transactionData['txn_id'],
					$res == 1,
					$transactionData['memo'] );
			}
			else
			{
				finishTransaction(
					$localTranID,
					$transactionData['txn_id'],
					$res == 1,
					$transactionData['memo'] );
			}
		}
		
		if ( $res == 1 )
		{
			$purchaseRes = purchaseTransaction( $localTranID, $res );
			if ( !$purchaseRes )
			{
				$errorMessage = 'Purchase failed';
				$res = 0;
			}
		}
		
		processValidationResult( $res, $errorMessage, $localTranID );
		return ( $res == 1 );
	}
	elseif ( $providerConf['Param_process_type'] == 'PDT' )
	{
		if ( !isset($_GET['tx']) )
		{
			PrintErrorPage( _t('_no data given') );
			return false;
		}
		
		$transactionData = $_GET;
		$res = moduleValidateTransaction( $transactionData, $errorMessage );
		
		if ( $subscribe && $newTrandID )
			$localTranID = $newTrandID;
		else
			$localTranID = (int)$transactionData['item_number'];
		
		if ( $res != 2 )
		{
			if ( $subscribe )
			{
				finishTransaction(
					$transactionData['item_number'],
					'dummy',
					false,
					'This dummy transaction was created on subscription and contains subscriptional data.' );
				finishSubscriptionTransaction(
					$localTranID,
					$transactionData['item_number'],
					$transactionData['txn_id'],
					$res == 1,
					$transactionData['memo'] );
			}
			else
			{
				finishTransaction(
					$localTranID,
					$transactionData['txn_id'],
					$res == 1,
					$transactionData['memo'] );
			}
		}
		
		if ( $res == 1 )
		{
			purchaseTransaction( $localTranID, $res );
		}
		
		processValidationResult( $res, $errorMessage, $localTranID );
		return ( $res == 1 );
	}
	
	return false;
}



if ( !defined('PAYMENT_MODULE_AS_HEADER') )
{
	$validateRes = moduleValidateConfiguration( $errorMessage );
	if ( !$validateRes )
	{
		PrintErrorPage( $errorMessage );
		exit();
	}
	
	if ( $providerConf['Debug'] )
	{
		writeDebugLog( 'PayPal checkout external call', '--------------', true );
		writeDebugLog( 'Conf', $providerConf, false );
		writeDebugLog( 'GET', $_GET, false );
		writeDebugLog( 'POST', $_POST, false );
	}
	
	if ( $_POST['txn_type'] == 'subscr_signup' )
	{
		if ( $providerConf['Debug'] )
		{
			writeDebugLog( 'Subscription event', 'Subscription signup', false );
		}
		// do nothing as subscription already initiated
	}
	elseif ( $_POST['txn_type'] == 'subscr_cancel' )
	{
		if ( $providerConf['Debug'] )
		{
			writeDebugLog( 'Subscription event', 'Subscription cancellation', false );
		}
		cancelSubscription( $_REQUEST['item_number'] );
	}
	elseif ( $_POST['txn_type'] == 'subscr_payment' )
	{
		$res = initiateSubscriptionTransaction( $_REQUEST['item_number'] );
		if ( $providerConf['Debug'] )
		{
			writeDebugLog( 'Subscription event', "Subscription payment. Base transaction ID: {$_REQUEST['item_number']} New transaction ID: {$res}", false );
		}
		if ( $res !== false )
			moduleAcceptPayment( true, $res );
	}
	else // $_POST['txn_type'] == 'web_accept'
	{
		moduleAcceptPayment( false );
	}
}

?>