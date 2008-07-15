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

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'checkout.inc.php' );

// --------------- page variables and login

$_page['name_index']	= 56;
$_page['css_name']		= 'checkout.css';

$logged['member'] = member_auth( 0 );

$_page['header'] = _t('_CHECKOUT_H');
$_page['header_text'] = _t('_CHECKOUT_H');

define( 'PAYMENT_MODULE_AS_HEADER', 'on' );

$collectDataArr = array(); // array with collected data
$collectDataInputs = '';  // string with inputs for data resending

// --------------- page components

$_ni = $_page['name_index'];

switch ( $_REQUEST['action'] )
{
	case 'calculate':
		$calculateArr = CalculateCheckoutInfo( $_REQUEST );
		if ( $calculateArr === false )
		{
			PageCompErrorMessage( _t('_no data given') );
		}
		$res = CollectCheckoutInfo( $calculateArr );
		if ( !$res )
		{
			PageCompErrorMessage( _t('_no data given') );
		}
		else
		{
			$_page_cont[$_ni]['checkout_info'] = DesignBoxContent( _t('_Payment info'), PageCompCheckoutInfo(), $oTemplConfig -> PageCompCheckoutInfo_db_num );
			$_page_cont[$_ni]['provider_list'] = DesignBoxContent( _t('_Payment methods'), PageCompProviderList(), $oTemplConfig -> PageCompProviderList_db_num );
		}
		break;

	case 'collect':
		$res = CollectCheckoutInfo( $_REQUEST );
		if ( !$res )
		{
			PageCompErrorMessage( _t('_no data given') );
		}
		else
		{
			$_page_cont[$_ni]['checkout_info'] = DesignBoxContent( _t('_Payment info'), PageCompCheckoutInfo(), $oTemplConfig -> PageCompCheckoutInfo_db_num );
			$_page_cont[$_ni]['provider_list'] = DesignBoxContent( _t('_Payment methods'), PageCompProviderList(), $oTemplConfig -> PageCompProviderList_db_num );
		}
		break;

	case 'start_checkout':
		$res = CollectCheckoutInfo( $_REQUEST );
		if ( !$res )
		{
			PageCompErrorMessage( _t('_no data given') );
		}
		else
		{
			$res = StartCheckout( $errorMessage );
			if ( !$res )
			{
				PageCompErrorMessage( $errorMessage );
			}
		}
		break;

	default:
		PageCompErrorMessage( _t('_no data given') );
		break;
}

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * calculate checkout info
 */
function CalculateCheckoutInfo( $source )
{
	if ( !isset($source['checkout_action']) || strlen($source['data']) == 0 )
		return false;

	$res = array();

	$res['checkout_action'] = $source['checkout_action'];
	$res['data'] = $source['data'];
	if ( isset($source['allow_subscribe']) )
	{
		$res['allow_subscribe'] = $source['allow_subscribe'];
	}
	if ( isset($source['subscribe_days']) )
	{
		$res['subscribe_days'] = $source['subscribe_days'];
	}

	switch ( $source['checkout_action'] )
	{
		case 'membership':
			$res['amount'] = $source['amount'];
			$prices = getMembershipPrices( $res['data'] );
			$pricingOptionExists = false;
			foreach ($prices as $days => $price)
			{
				if ( $res['amount'] == $price )
				{
					if ( $days == 0 )
					{
						$res['allow_subscribe'] = '';
						$res['subscribe_days'] = 0;
					}
					else
					{
						$res['allow_subscribe'] = 'on';
						$res['subscribe_days'] = $days;
					}
					$pricingOptionExists = true;
					break;
				}
			}
			if ( !$pricingOptionExists )
				return false;
			else
				return $res;

		case 'speeddating':
			return $res;

		case 'profiles':
			return $res;

		default:
			return false;
	}
}

/**
 * collect data from source
 */
function CollectCheckoutInfo( $source )
{
	global $collectDataArr;
	global $collectDataInputs;

	if ( !isset($source['checkout_action']) || strlen($source['data']) == 0 )
		return false;

	$collectDataArr['checkout_action'] = process_pass_data( $source['checkout_action'] );
	$collectDataArr['amount'] = process_pass_data( $source['amount'] );
	$collectDataArr['data'] = process_pass_data( $source['data'] );
	if ( isset($source['allow_subscribe']) )
	{
		$collectDataArr['allow_subscribe'] = process_pass_data( $source['allow_subscribe'] );
	}
	if ( isset($source['subscribe_days']) )
	{
		$collectDataArr['subscribe_days'] = process_pass_data( $source['subscribe_days'] );
	}

	$collectDataArr['description'] = returnDescByAction( $collectDataArr['checkout_action'], $collectDataArr['data'], true );
	if ( strlen($collectDataArr['description']) == 0 )
		return false;

	$collectDataInputs = '';
	$collectDataInputs .= "<input type=\"hidden\" name=\"checkout_action\" value=\"{$collectDataArr['checkout_action']}\" />\n";
	$collectDataInputs .= "<input type=\"hidden\" name=\"amount\" value=\"{$collectDataArr['amount']}\" />\n";
	$collectDataInputs .= "<input type=\"hidden\" name=\"data\" value=\"{$collectDataArr['data']}\" />\n";
	if ( isset($source['allow_subscribe']) )
	{
		$collectDataInputs .= "<input type=\"hidden\" name=\"allow_subscribe\" value=\"{$collectDataArr['allow_subscribe']}\" />\n";
	}
	if ( isset($source['subscribe_days']) )
	{
		$collectDataInputs .= "<input type=\"hidden\" name=\"subscribe_days\" value=\"{$collectDataArr['subscribe_days']}\" />\n";
	}

	return true;
}

/**
 * start checkout process
 */
function StartCheckout( &$errorMessage )
{
	global $dir;
	global $memberID; // defined in checkout.inc.php
	global $collectDataArr;
	global $enable_recurring;
	// these globals for module require call
	global $site;
	global $providerConf;
	global $checkoutFilename;
	global $checkoutURL;
	global $debugFilename;

	$providerID = (int)$_REQUEST['prov_id'];
	$providerRes = db_res( "SELECT `Name`, `CheckoutFilename` FROM `PaymentProviders` WHERE `ID` = {$providerID} AND `Active`" );
	if ( !$providerRes || mysql_num_rows($providerRes) == 0 )
	{
		$errorMessage = 'Wrong payment provider specified';
		return false;
	}
	$providerArr = mysql_fetch_assoc( $providerRes );
	if ( strlen(trim($providerArr['CheckoutFilename'])) )
		$checkoutFilename = $providerArr['CheckoutFilename'];
	else
		$checkoutFilename = $dir['checkout'] . $providerArr['Name'] . '.php';
	if ( !file_exists( $checkoutFilename ) )
	{
		$errorMessage = 'Checkout file not found';
		return false;
	}

	require_once( $checkoutFilename );

	$validateRes = moduleValidateConfiguration( $errorMessage );
	if ( !$validateRes )
	{
		return false;
	}

	$localTranID = initiateTransaction( $collectDataArr, $memberID, $providerID );
	if ( $localTranID === false )
	{
		$errorMessage = 'Transaction initiating error';
		return false;
	}
	$subscriptionalPayment = $enable_recurring && $collectDataArr['allow_subscribe'] == 'on'
		&& $_REQUEST['prov_recurring'] == 'on';

	if ( $subscriptionalPayment )
	{
		$subsRes = initiateSubscription( $localTranID, $collectDataArr['subscribe_days'] );
		if ( !$subsRes )
			{
				$errorMessage = 'Subscription initiating error';
				return false;
			}
		}

		$startRes = moduleStartTransaction( $localTranID, $subscriptionalPayment, $collectDataArr['subscribe_days'] );
		if ( !$startRes )
		{
			$errorMessage = 'Transaction starting error';
			return false;
		}

	return true;
}

/**
 * prints errom message in checkout info box end empty provider list box
 */
function PageCompErrorMessage( $message )
{
	global $_page_cont;
	global $_ni;
	global $oTemplConfig;

	$designBox = DesignBoxContentBorder( _t('_Error'), '<center>'. $message .'</center>' );
	$content = "<div class=\"error_box\">\n{$designBox}\n</div>\n";
	$_page_cont[$_ni]['checkout_info'] = DesignBoxContent( _t('_Payment info'), $content, $oTemplConfig -> PageCompErrorMessage_db_num );
	$_page_cont[$_ni]['provider_list'] = '';
}

/**
 * common checkout info
 */
function PageCompCheckoutInfo()
{
	global $collectDataArr;
	global $doll;

	ob_start();

?>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="field_caption" align="right" width="50%"><?= _t('_Payment description') ?>:</td>
		<td class="field_value" align="left" width="50%"><?= $collectDataArr['description'] ?></td>
	</tr>
	<tr>
		<td class="field_caption" align="right" width="50%"><?= _t('_Payment amount') ?>:</td>
		<td class="field_value" align="left" width="50%"><?= $doll . $collectDataArr['amount'] ?></td>
	</tr>
<?
	if ( $collectDataArr['allow_subscribe'] == 'on' && (int)$collectDataArr['subscribe_days'] > 0 )
	{
?>
	<tr>
		<td class="field_caption" align="right" width="50%"><?= _t('_Possible subscription period') ?>:</td>
		<td class="field_value" align="left" width="50%"><?= $collectDataArr['subscribe_days'] . _t('_days') ?></td>
	</tr>
<?
	}
?>
</table>
<?

	$content = ob_get_contents();
	ob_end_clean();

	$designBox = DesignBoxContentBorder( _t('_Payment info'), $content );

	$content = "<div class=\"checkout_info\">\n{$designBox}\n</div>\n";

	return $content;
}

/**
 * list of all active payment providers
 */
function PageCompProviderList()
{
	global $dir;
	global $site;
	global $enable_recurring;
	global $memberID; // defined in checkout.inc.php
	global $collectDataArr;
	global $collectDataInputs;

	$ret = '';

	$res = db_res( "SELECT `ID`, `Name`, `Caption`, `SupportsRecurring`, `LogoFilename` FROM `PaymentProviders` WHERE `Active`" );

	while ( $arr = mysql_fetch_assoc($res) )
	{
		if ( $enable_recurring && $collectDataArr['allow_subscribe'] == 'on' )
		{
			if ( $arr['SupportsRecurring'] )
			{
				$recurringField = "<input type=\"checkbox\" name=\"prov_recurring\" id=\"prov{$arr['ID']}_recurring_id\" style=\"vertical-align: middle;\" onclick=\"javascript: document.getElementById('subscribe{$arr['ID']}_days_id').disabled = !this.checked;\" />&nbsp;<label for=\"prov{$arr['ID']}_recurring_id\">". _t('_recurring payment') ."</label>";
				if ( (int)$collectDataArr['subscribe_days'] == 0 )
				{
					$daysVariants = array( 10, 20, 30, 60, 180 );
					$recurringField .= "&nbsp<select name=\"subscribe_days\" id=\"subscribe{$arr['ID']}_days_id\" disabled=\"disabled\" style=\"vertical-align: middle;\">\n";
					foreach ( $daysVariants as $days )
					{
						$recurringField .= "<option value=\"{$days}\">{$days} ". _t('_days') ."</option>";
					}
					$recurringField .= "</select>\n";
				}
			}
			else
			{
				$recurringField = _t('_recurring not supported');
			}
		}
		else
		{
			$recurringField = _t('_recurring not allowed');
		}

		ob_start();
?>
<form id="f<?= $arr['Name'] ?>ProviderForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" style="margin: 10px;">
<input type="hidden" name="action" value="start_checkout" />
<?= $collectDataInputs ?>
<input type="hidden" name="prov_id" value="<?= $arr['ID'] ?>" />
<table cellpadding="4" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left" width="35%" rowspan="2"><?= strlen($arr['LogoFilename']) > 0 && file_exists($dir['checkout'] . 'images/' . $arr['LogoFilename']) ? "<img src=\"{$site['checkout']}images/{$arr['LogoFilename']}\" alt=\"". process_line_output($arr['Caption']) ."\" />" : '&nbsp;' ?></td>
		<td class="field_caption" align="right" width="65%"><?= $recurringField ?></td>
	</tr>
	<tr>
		<td align="right" width="65%">
			<input type="submit" class="no" value="<?= _t('_Check Out') ?>" style="width: 100px; vertical-align: middle" />
		</td>
	</tr>
</table>
</form>
<?
		$content = ob_get_contents();
		ob_end_clean();

		$designBox = DesignBoxContentBorder( $arr['Caption'], $content );

		$ret .= "<div class=\"provider_box\">\n{$designBox}\n</div>\n";
	}

	return $ret;
}

?>