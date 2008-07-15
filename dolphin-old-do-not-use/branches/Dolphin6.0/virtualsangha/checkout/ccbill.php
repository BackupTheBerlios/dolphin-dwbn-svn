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

CCBill

*/

// It is recommended to put full path to header file here
// You SHOULD put full path if this script is not in default folder
{
	require_once( "../inc/header.inc.php" );
}
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'checkout.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'languages.inc.php' );

define( 'PAYMENT_MODULE_NAME', 'ccbill' );
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
	
	if ( strlen(trim($providerConf['Param_client_accnum'])) == 0 )
	{
		$errorMessage = '\'Account number\' field is empty';
		return false;
	}
	
	if ( strlen(trim($providerConf['Param_client_subacc'])) == 0 )
	{
		$errorMessage = '\'Subaccount number\' field is empty';
		return false;
	}
	
	if ( strlen(trim($providerConf['Param_form_name'])) == 0 )
	{
		$errorMessage = '\'Form name\' field is empty';
		return false;
	}
	
	if ( strlen(trim($providerConf['Param_allowed_types'])) == 0 )
	{
		$errorMessage = '\'Allowed types\' field is empty';
		return false;
	}
	
	if ( strlen(trim($providerConf['Param_subscription_type_id'])) == 0 || (int)$providerConf['Param_subscription_type_id'] == 0 )
	{
		$errorMessage = '\'Subscription type id\' field is empty or has incorrect value';
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
	
	if ( $providerConf['Debug'] )
	{
		writeDebugLog( 'Validation for transaction', $dataArr, false );
	}
	
	if ( !commonValidateTransaction( $dataArr['productDesc'], $dataArr['initialPrice'], $errorMessage ) )
	{
		return -1;
	}
	
	if ( $dataArr['clientAccnum'] != $providerConf['Param_client_accnum'] )
	{
		$errorMessage = 'Wrong recipient account number';
		return -1;
	}
	
	if ( $dataArr['clientSubacc'] != $providerConf['Param_client_subacc'] )
	{
		$errorMessage = 'Wrong recipient subaccount number';
		return -1;
	}
	
	return 1;
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
	
	$actionURL = 'https://bill.ccbill.com/jpost/signup.cgi';
	
	$formData = array();
	
	// account ID
	$formData['clientAccnum'] = $providerConf['Param_client_accnum'];
	$formData['clientSubacc'] = $providerConf['Param_client_subacc'];
	
	// transaction common data
	$formData['formName'] = $providerConf['Param_form_name'];
	$formData['allowedTypes'] = $providerConf['Param_allowed_types'];
	$formData['subscriptionTypeId'] = $providerConf['Param_subscription_type_id'];
	$formData['productDesc'] = $localTranID;
	$formData['member_id'] = $memberID;
	
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
		writeDebugLog( 'Payment event', 'Payment start', false );
	}
	
	if ( !isset($_POST['productDesc']) || !isset($_POST['initialPrice']) )
	{
		PrintErrorPage( _t('_no data given') );
		return false;
	}
	
	$transactionData = $_POST;
	$res = moduleValidateTransaction( $transactionData, $errorMessage );
	
	$localTranID = (int)$transactionData['productDesc'];
	
	if ( $res != 2 )
	{
		finishTransaction( $localTranID, $transactionData['subscription_id'], $res == 1 );
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
		writeDebugLog( 'CCBill checkout external call', '--------------', true );
		writeDebugLog( 'Conf', $providerConf, false );
		writeDebugLog( 'GET', $_GET, false );
		writeDebugLog( 'POST', $_POST, false );
	}
	
	moduleAcceptPayment( false );
}

?>