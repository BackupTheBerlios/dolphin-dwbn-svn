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

Authorize.Net

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
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );

define( 'PAYMENT_MODULE_NAME', 'authorizenet' );
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
	
	if ( strlen(trim($providerConf['Param_x_login'])) == 0 )
	{
		$errorMessage = '\'Login\' field is empty';
		return false;
	}
	
	if ( strlen(trim($providerConf['Param_x_tran_key'])) == 0 )
	{
		$errorMessage = '\'Transaction key\' field is empty';
		return false;
	}
	
	if ( !in_array($providerConf['Param_implementation'], array('SIM', 'AIM')) )
	{
		$errorMessage = '\'Implementation\' field has incorrect value';
		return false;
	}
	
	// check if cURL installed
	if ( $providerConf['Param_implementation'] == 'AIM' && !extension_loaded('curl') )
	{
		if ( strlen( trim($providerConf['Param_curl_binary']) ) == 0 || !file_exists($providerConf['Param_curl_binary']) || !is_file($providerConf['Param_curl_binary']) )
		{
			$errorMessage = 'cUrl extension not loaded and \'cURL binary location\' field is empty or invalid, but it\'s needed for AIM implementation';
			return false;
		}
	}
	
	// check if Mhash installed
	if ( $providerConf['Param_implementation'] == 'SIM' && !extension_loaded('mhash') )
	{
		$errorMessage = 'Mhash extension not loaded, but it\'s needed for SIM implementation';
		return false;
	}
	
	if ( strlen(trim($providerConf['Param_x_delim_char'])) == 0 )
	{
		$errorMessage = '\'Delimiter char\' field is empty';
		return false;
	}
	
	if ( strlen(trim($providerConf['Param_x_encap_char'])) == 0 )
	{
		$errorMessage = '\'Encapsulate char\' field is empty';
		return false;
	}
	
	if ( strlen(trim($providerConf['Param_md5_hash_value'])) == 0 )
	{
		$errorMessage = '\'MD5 Hash\' field is empty';
		return false;
	}
	
	return true;
}

/**	
 * Validates credit card information and email entered by customer
 * 
 * @param array $dataArr				- array with data to validate
 * 
 * @return bool 						- true if data is valid, false otherwise
 * 
 * 
 */
function validateCheckoutData( $dataArr )
{
	if ( strlen($dataArr['auth_card_num']) <= 6 )
	{
		return false;
	}
	
	if ( !eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$", $dataArr['auth_email']) )
	{
		return false;
	}
	
	return true;
}

/**	
 * Sends request via cURL and returns received data
 * 
 * @param string $url					- send data URL
 * @param string $post					- data to be sent
 * 
 * @return string 						- received data
 * 
 * 
 */
function sendCurlRequest( $url, $post = '' )
{
	global $providerConf;
	
	if ( extension_loaded('curl') )
	{
		$curlRes = curl_init( $url );
		curl_setopt( $curlRes, CURLOPT_HEADER, 0 );
		curl_setopt( $curlRes, CURLOPT_POSTFIELDS, $post );
		curl_setopt( $curlRes, CURLOPT_RETURNTRANSFER, 1 );
		// curl_setopt( $curlRes, CURLOPT_SSL_VERIFYPEER, FALSE ); // uncomment this line if you get no gateway response
		$res = curl_exec( $curlRes );
		curl_close( $curlRes );
		return $res;
	}
	else
	{
		if ( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' )
		{
			$curl = $providerConf['Param_curl_binary'];
		}
		else
		{
			$curl = escapeshellcmd( $providerConf['Param_curl_binary'] );
			$url = escapeshellcmd( $url );
		}
		$res = `$curl -d "$post" $url`;
		return $res;
	}
}

/**	
 * Generates fingerprint hash basing on current Authorize.net account data
 * 
 * @param int $timestamp				- current timestamp
 * @param int $sequence					- randomly generated number
 * @param string $amount				- payment sum
 * @param string $currency				- currency code
 * 
 * @return string 						- fingerprint hash
 * 
 * 
 */
function calculateFingerPrint( $timestamp, $sequence, $amount, $currency )
{
	global $providerConf;
	
	$data = $providerConf['Param_x_login'] . '^' . $sequence . '^' . $timestamp . '^' . $amount . '^' . $currency;
	return bin2hex( mhash( MHASH_MD5, $data, $providerConf['Param_x_tran_key'] ) );
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
	
	if ( $providerConf['Param_implementation'] == 'AIM' )
	{
		if ( $providerConf['Debug'] )
		{
			writeDebugLog( 'AIM validation for transaction', $dataArr, false );
		}
		
		if ( !commonValidateTransaction( $dataArr[7], $dataArr[9], $errorMessage ) )
		{
			return -1;
		}
		
		if ( $dataArr[0] != 1 )
		{
			$errorMessage = 'Transaction declined. Reason: ' . $dataArr[3];
			return 0;
		}
		
		$localTranID = (int)$dataArr[7];
		$tranArr = db_arr( "SELECT `Data` FROM `Transactions`
								WHERE `ID` = {$localTranID}" );
		$tranData = transStringToData( $tranArr['Data'] );
		if ( $dataArr[12] != $tranData['memberID'] )
		{
			$errorMessage = 'Customer validation failed';
			return -1;
		}
		
		$MD5String = $providerConf['Param_md5_hash_value'] . $providerConf['Param_x_login'] . $dataArr[6] . $dataArr[9];
		$generatedMD5 = md5($MD5String);
		
		if ( $providerConf['Debug'] )
		{
			writeDebugLog( 'Calculated MD5 hash', $generatedMD5, false );
			writeDebugLog( 'Received MD5 hash', $dataArr[37], false );
		}
		
		if ( $dataArr[37] != $generatedMD5 )
		{
			$errorMessage = 'MD5 validation not passed';
			return -1;
		}
		
		return 1;
	}
	elseif ( $providerConf['Param_implementation'] == 'SIM' )
	{
		if ( $providerConf['Debug'] )
		{
			writeDebugLog( 'SIM validation for transaction', $dataArr, false );
		}
		
		if ( !commonValidateTransaction( $dataArr['x_invoice_num'], $dataArr['x_amount'], $errorMessage ) )
		{
			return -1;
		}
		
		if ( $dataArr['x_response_code'] != 1 )
		{
			$errorMessage = 'Transaction declined. Reason: ' . $dataArr['x_response_reason_text'];
			return 0;
		}
		
		$localTranID = (int)$dataArr['x_invoice_num'];
		$tranArr = db_arr( "SELECT `Data` FROM `Transactions`
								WHERE `ID` = {$localTranID}" );
		$tranData = transStringToData( $tranArr['Data'] );
		if ( $dataArr['x_cust_id'] != $tranData['memberID'] )
		{
			$errorMessage = 'Customer validation failed';
			return -1;
		}
		
		$MD5String = $providerConf['Param_md5_hash_value'] . $providerConf['Param_x_login'] . $dataArr['x_trans_id'] . $dataArr['x_amount'];
		$generatedMD5 = md5($MD5String);
		
		if ( $providerConf['Debug'] )
		{
			writeDebugLog( 'Calculated MD5 hash', $generatedMD5, false );
			writeDebugLog( 'Received MD5 hash', $dataArr['x_md5_hash'], false );
		}
		
		if ( $dataArr['x_md5_hash'] != $generatedMD5 )
		{
			$errorMessage = 'MD5 validation not passed';
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
	global $_page;
	global $_page_cont;
	global $_ni;
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
	
	if ( $providerConf['Param_implementation'] == 'AIM' )
	{
		$profileEmail = '';
		
		$_page = array();
		$_page_cont = array();
		$_page['name_index'] = 0;
		$_page['css_name'] = 'checkout.css';
		$_ni = $_page['name_index'];
		$_page['header'] = $providerConf['Caption'];
		$_page['header_text'] = $providerConf['Caption'];
		
		ob_start();
		
		$expDateMonthes = array( '01','02','03','04','05','06','07','08','09','10','11','12' );
		$expDateYears = array( '2004','2005','2006','2007','2008','2009','2010' );
?>
<center>
	<form id="authSendForm" action="<?= $checkoutURL ?>" method="post" style="margin: 0px;">
	<input type="hidden" name="auth_tran_id" value="<?= $localTranID ?>" />
	<table cellpadding="4" cellspacing="0" border="0" width="360">
		<tr>
			<td align="center" colspan="2" class="field_value"><?= $tranArr['Description'] ?></td>
		</tr>
		<tr>
			<td align="left"><?= _t('_Credit card number') ?>:</td>
			<td align="left" width="160"><input type="text" class="no" name="auth_card_num" style="width: 150px" /></td>
		</tr>
		<tr>
			<td align="left"><?= _t('_Expiration date') ?>:</td>
			<td align="left" width="160">
				<select class="no" name="auth_expire_month">
<?
	foreach ( $expDateMonthes as $month )
		echo "<option value=\"{$month}\">{$month}</option>";
?>
				</select>&nbsp;
				<select class="no" name="auth_expire_year">
<?
	foreach ( $expDateYears as $year )
		echo "<option value=\"{$year}\">{$year}</option>";
?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="left"><?= _t('_E-mail') ?>:</td>
			<td align="left" width="160"><input type="text" class="no" name="auth_email" value="<?= $profileEmail ?>" style="width: 150px" /></td>
		</tr>
		<tr>
			<td align="center" colspan="2"><input type="submit" class="no" name="send_data" value="<?= _t('_Check Out') ?>" style="width: 100px" /></td>
		</tr>
	</table>
	</form>
</center>
<?
		
		$content = ob_get_contents();
		ob_end_clean();
		$designBox = DesignBoxContentBorder( $providerConf['Caption'], $content );
		$_page_cont[$_ni]['page_main_code'] = '<div style="width: 380px; margin-left: auto; margin-right: auto;">'. $designBox .'</div>';
		
		PageCode();
		exit();
	}
	elseif ( $providerConf['Param_implementation'] == 'SIM' )
	{
		$actionURL = 'https://secure.authorize.net/gateway/transact.dll';
		
		$formData = array();
		
		$timestamp = time();
		srand( $timestamp );
		$sequence = rand(1, 1000);
		
		// account ID
		$formData['x_login'] = $providerConf['Param_x_login'];
		$formData['x_fp_sequence'] = $sequence;
		$formData['x_fp_timestamp'] = $timestamp;
		$formData['x_fp_hash'] = calculateFingerPrint( $timestamp, $sequence, $tranArr['Amount'], $currency_code );
		
		// transaction common data
		$formData['x_method'] = 'CC';
		$formData['x_type'] = 'AUTH_CAPTURE';
		$formData['x_amount'] = sprintf( "%.2f", (float)$tranArr['Amount'] );
		$formData['x_description'] = $tranArr['Description'];
		$formData['x_invoice_num'] = $localTranID;
		$formData['x_version'] = '3.1';
		$formData['x_show_form'] = 'PAYMENT_FORM';
		$formData['x_relay_response'] = 'TRUE';
		$formData['x_email_customer'] = 'FALSE';
		$formData['x_cust_id'] = $memberID;
		
		// return and redirect
		$formData['x_relay_url'] = $checkoutURL;
		
		// test mode
		$formData['x_test_request'] = ($providerConf['Mode'] == 'live' ? 'FALSE' : 'TRUE');
		
		Redirect($actionURL, $formData, 'post', $providerConf['Caption']);
		exit();
	}
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
	global $date_format;
	
	$errorMessage = '';
	
	if ( $providerConf['Debug'] )
	{
		writeDebugLog( 'Payment event', 'Payment start', false );
	}
	
	if ( $providerConf['Param_implementation'] == 'AIM' )
	{
		if ( !isset($_POST['send_data']) || !isset($_POST['auth_card_num']) || !isset($_POST['auth_tran_id']) )
		{
			PrintErrorPage( _t('_no data given') );
			return false;
		}
		
		$transactionData = $_POST;
		if ( !validateCheckoutData($transactionData) )
		{
			PrintErrorPage( _t('_no data given') );
			return false;
		}
		
		$localTranID = (int)$transactionData['auth_tran_id'];
		$tranRes = db_res( "SELECT DATE_FORMAT(`Date`,  '$date_format' ) AS 'Date', `Amount`, `Currency`, `Status`, `Data`, `Description` FROM `Transactions`
								WHERE `ID` = {$localTranID}
								AND `Status` = 'pending'
								AND `IDProvider` = {$providerConf['ID']}" );
		if ( !$tranRes || mysql_num_rows($tranRes) == 0 )
			return false;
		$tranArr = mysql_fetch_assoc($tranRes);
		$tranData = transStringToData( $tranArr['Data'] );
		
		$postURL = 'https://secure.authorize.net/gateway/transact.dll';
		
		$postParameters = "x_login={$providerConf['Param_x_login']}";
		$postParameters .= "&x_tran_key={$providerConf['Param_x_tran_key']}";
		$postParameters .= "&x_version=3.1";
		$postParameters .= "&x_method=CC";
		$postParameters .= "&x_type=AUTH_CAPTURE";
		$postParameters .= "&x_amount=" . sprintf( "%.2f", (float)$tranArr['Amount'] );
		$postParameters .= "&x_invoice_num={$localTranID}";
		$postParameters .= "&x_description={$tranArr['Description']}";
		$postParameters .= "&x_relay_response=FALSE";
		$postParameters .= "&x_email_customer=FALSE";
		$postParameters .= "&x_delim_data=TRUE";
		$postParameters .= "&x_delim_char={$providerConf['Param_x_delim_char']}";
		$postParameters .= "&x_encap_char={$providerConf['Param_x_encap_char']}";
		$postParameters .= "&x_card_num={$transactionData['auth_card_num']}";
		$postParameters .= "&x_exp_date={$transactionData['auth_expire_month']}-{$transactionData['auth_expire_year']}";
		$postParameters .= "&x_cust_id={$tranData['memberID']}";
		$postParameters .= "&x_test_request=" . ($providerConf['Mode'] == 'live' ? 'FALSE' : 'TRUE');
		
		$response = sendCurlRequest( $postURL, $postParameters );
		
		if ( $providerConf['Debug'] )
		{
			writeDebugLog( 'AIM request response', $response, false );
		}
		
		$responseArr = explode( $providerConf['Param_x_delim_char'], $response );
		$encapChar = $providerConf['Param_x_encap_char'];
		if ( $encapChar == '\'' || $encapChar == '\\' )
			$encapChar = '\\' . $encapChar;
		array_walk( $responseArr, create_function('&$arg', "\$arg = trim(\$arg, '{$encapChar}');") );
		
		$transactionData = $responseArr;
		$res = moduleValidateTransaction( $transactionData, $errorMessage );
		
		$localTranID = (int)$transactionData[7];
		
		if ( $res != 2 )
		{
			finishTransaction( $localTranID, $transactionData[6], $res == 1 );
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
	elseif ( $providerConf['Param_implementation'] == 'SIM' )
	{
		if ( !isset($_POST['x_response_code']) || !isset($_POST['x_invoice_num']) )
		{
			PrintErrorPage( _t('_no data given') );
			return false;
		}
		
		$transactionData = $_POST;
		$res = moduleValidateTransaction( $transactionData, $errorMessage );
		
		$localTranID = (int)$transactionData['x_invoice_num'];
		
		if ( $res != 2 )
		{
			finishTransaction( $localTranID, $transactionData['x_trans_id'], $res == 1 );
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
		writeDebugLog( 'Authorize.net checkout external call', '--------------', true );
		writeDebugLog( 'Conf', $providerConf, false );
		writeDebugLog( 'GET', $_GET, false );
		writeDebugLog( 'POST', $_POST, false );
	}
	
	moduleAcceptPayment( false );
}

?>