<?

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

$_page['name_index'] = 10;
$_page['css_name'] = 'membership.css';

$logged['member'] = member_auth( 0 );

$_page['header'] = _t( "_MEMBERSHIP_H" );
$_page['header_text'] = _t( "_MEMBERSHIP_H1" );

// free mode enabled then don't show anything
if ( getParam('free_mode') == 'on' )
{
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = '';
	PageCode();
	exit();
}

// --------------- GET/POST actions

$subscriptionStatus = '';
if ( isset($_REQUEST['action']) )
{
	switch ( $_REQUEST['action'] )
	{
		case 'cancel_subscription':
			$transactionID = (int)$_REQUEST['tran_id'];
			$subscriptionArr = db_arr( "SELECT `PaymentSubscriptions`.`TransactionID`, DATE_FORMAT(`PaymentSubscriptions`.`StartDate`,  '$date_format' ) AS StartDate, `PaymentSubscriptions`.`Period`, `Transactions`.`Description`, `PaymentProviders`.`Caption` AS `ProviderCaption` FROM `PaymentSubscriptions`
											LEFT JOIN `Transactions` ON `PaymentSubscriptions`.`TransactionID` = `Transactions`.`ID`
											LEFT JOIN `PaymentProviders` ON `Transactions`.`IDProvider` = `PaymentProviders`.`ID`
											WHERE `PaymentSubscriptions`.`TransactionID` = {$transactionID}" );

			if ( $subscriptionArr )
			{
				$recipient = $site['email'];
				$subject = 'Unsubscription request';

				$message = <<<EOM
Dear Admin, please unsubscribe me from recurring payments.
Below my subscription information:
	Subscription date: {$subscriptionArr['StartDate']}
	Payment provider: {$subscriptionArr['ProviderCaption']}
	Subscription period: {$subscriptionArr['Period']}
	Payment description: {$subscriptionArr['Description']}
EOM;
				$res = sendMail( $recipient, $subject, $message );
			}
			else
			{
				$res = false;
			}

			$subscriptionStatus = $res ? _t('_Subscription cancellation request was successfully sent') : _t('_Fail to sent subscription cancellation request');
			break;
	}
}

// --------------- [ END ] GET/POST actions

// --------------- page components

$_ni = $_page['name_index'];

// NOTE: $memberID is defined in checkout.inc.php file
$_page_cont[$_ni]['status'] = PageCompStatus( $memberID );
$_page_cont[$_ni]['subscriptions'] =  ( $enable_recurring ? PageCompSubscriptions( $memberID, $subscriptionStatus ) : '' );
$_page_cont[$_ni]['memberships'] = PageCompMemberships();

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * membership status
 */
function PageCompStatus( $memberID )
{
	global $site;
	global $oTemplConfig;

	$ret = '';

	if ( isset( $_REQUEST['result'] ) )
	{
		$resultMessage = '';
		switch( $_REQUEST['result'] )
		{
			case '1000':
				$resultMessage = _t( '_RESULT1000' );
				break;
			case '0':
				$resultMessage = _t( '_RESULT0' );
				break;
			case '-1':
				$resultMessage = _t( '_RESULT-1' );
				break;
			case '1':
				$resultMessage = _t( '_RESULT1_THANK', $site['title'] );
				break;
		}
		if ( strlen($resultMessage) )
			$ret .= "<div class=\"result_message\">{$resultMessage}</div>\n";
	}

	$ret .= GetMembershipStatus($memberID, false);

	$ret = "<div class=\"status_box\">\n{$ret}\n</div>\n";

	return DesignBoxContent( _t("_Membership Status"), $ret, $oTemplConfig -> PageCompStatus_db_num );
}

/**
 * payment subscriptions
 */
function PageCompSubscriptions( $memberID, $statusMessage )
{
	global $oTemplConfig;
	global $date_format;
	ob_start();

	$subscriptionsRes = db_res( "SELECT `PaymentSubscriptions`.`TransactionID`, DATE_FORMAT(`PaymentSubscriptions`.`StartDate`, '$date_format' ) AS StartDate, `PaymentSubscriptions`.`Period`, `PaymentSubscriptions`.`ChargesNumber`, `Transactions`.`Description` FROM `PaymentSubscriptions`
									LEFT JOIN `Transactions` ON `PaymentSubscriptions`.`TransactionID` = `Transactions`.`ID`
									WHERE `Transactions`.`IDMember` = {$memberID}
										AND `Transactions`.`Status` = 'declined'" );
	$daysLangString = _t('_days');
	$cancelLangString = _t('_Cancel');
?>
<div class="subscriptions_box">
<?
	if ( strlen($statusMessage) )
		echo "<div class=\"result_message\">{$statusMessage}</div>\n";
?>
<form id="cancelSubscriptionForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" style="margin: 0px;">
	<input type="hidden" name="action" value="cancel_subscription" />
	<input type="hidden" name="tran_id" id="cancel_transaction_id" value="0" />
</form>
<script type="text/javascript">
<!--
	function sendCancelForm( tranID )
	{
		document.getElementById('cancel_transaction_id').value = tranID;
		document.forms['cancelSubscriptionForm'].submit();
	}
-->
</script>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
	<tr class="subscriptions_row_header">
		<td align="left" width="110"><?= _t('_Start date') ?></td>
		<td align="left"><?= _t('_Payment description') ?></td>
		<td align="right" width="40"><?= _t('_Period') ?></td>
		<td align="right" width="95"><?= _t('_Charges number') ?></td>
		<td align="right" width="40">&nbsp;</td>
	</tr>
<?
	while ( $subscriptionArr = mysql_fetch_assoc($subscriptionsRes) )
	{
?>
	<tr class="subscriptions_row">
		<td align="left" width="110"><?= $subscriptionArr['StartDate'] ?></td>
		<td align="left"><?= process_line_output($subscriptionArr['Description']) ?></td>
		<td align="right"><?= $subscriptionArr['Period'] . $daysLangString ?></td>
		<td align="right"><?= $subscriptionArr['ChargesNumber'] ?></td>
		<td align="right" width="40"><a href="javascript:void(0);" onclick="javascript: sendCancelForm(<?= $subscriptionArr['TransactionID'] ?>); return false;"><?= $cancelLangString ?></a></td>
	</tr>
<?
	}
?>
</table>
</div>
<?

	$ret = ob_get_contents();
	ob_end_clean();

	return DesignBoxContent( _t('_Subscriptions'), $ret, $oTemplConfig -> PageCompSubscriptions_db_num );
}

/**
 * purchase memberships
 */
function PageCompMemberships()
{
	global $site;
	global $doll;
	global $oTemplConfig;

	$expl_win_h = 400; //not global

	$purchasableOnly = true;
	$purchasableMemberships = getMemberships($purchasableOnly);

	$ret = '';

	foreach ($purchasableMemberships as $ID => $name)
	{
		ob_start();
?>
<div class="membership_line">
	<form id="membership<?= $ID ?>Form" action="<?= $site['url'] . 'checkout.php' ?>" method="post" style="margin: 2px">
	<input type="hidden" name="action" value="calculate" />
	<input type="hidden" name="checkout_action" value="membership" />
	<input type="hidden" name="allow_subscribe" value="on" />
	<input type="hidden" name="data" value="<?= $ID ?>" />
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td align="left"><?= htmlspecialchars($name) ?></td>
			<td align="right">
				<select class="no" name="amount" style="vertical-align: middle;">
<?
		$prices = getMembershipPrices($ID);
		foreach ($prices as $days => $price)
		{
			$optionText = $days ? $days . ' '._t('_days') : _t('_Lifetime');
			$optionText .= ' - ' . $doll . $price;
			echo "<option value=\"{$price}\">{$optionText}</option>\n";
		}
?>
				</select>
			</td>
			<td align="right" width="110">
				<input type="submit" class="no" value="<?= _t( "_Check Out" ) ?>" style="width: 100px; vertical-align: middle;" />
			</td>
			<td align="right" width="60">
				<a href="javascript:void(0);" onClick="javascript: window.open('explanation.php?explain=membership&type=<?= $ID ?>', '', 'width=<?= $oTemplConfig -> popUpWindowWidth ?>,height=<?= $expl_win_h ?>,menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no' );"><?= _t("_Explanation"); ?></a>
			</td>
		</tr>
	</table>
	</form>
</div>
<?
		$ret .= ob_get_contents();
		ob_end_clean();
	}

	$ret = "<div class=\"memberships_box\">\n{$ret}\n</div>\n";

	return DesignBoxContent( _t("_Membership NEW"), $ret, $oTemplConfig -> PageCompMemberships_db_num );
}

?>