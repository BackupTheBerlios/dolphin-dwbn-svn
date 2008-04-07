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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'checkout.inc.php' );

$logged['admin'] = member_auth( 1 );

$_page['header'] = "Payment providers";
$_page['header_text'] = "Manage payment providers";

define( 'PAYMENT_MODULE_AS_HEADER', 'on' );

/**
 * Prints payment provider setup box
 *
 * @param array $providerArr				- array with provider data obtained from the database
 * @param bool $usePostData					- indicates if POST data should be used to show configuration
 *
 *
 */
function PPShowProviderBox( $providerArr, $usePostData = false )
{
	global $dir;
	global $site;

	$providerActive = $usePostData ? $_POST['provider_active'] == 'on' : $providerArr['Active'];
	$providerMode = $usePostData ? $_POST['provider_mode'] : $providerArr['Mode'];
	$providerDebug = $usePostData ? $_POST['provider_debug'] == 'on' : $providerArr['Debug'];

?>
<form id="f<?= $providerArr['Name'] ?>ProviderForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" style="margin: 10px;">
<input type="hidden" name="action" value="validate_or_save" />
<input type="hidden" name="prov_id" value="<?= $providerArr['ID'] ?>" />
<div class="section_header" style="width: 500px; text-align: left; margin: 2px;">
	<?= process_line_output($providerArr['Caption']) ?>
</div>
<div class="section_row" style="width: 500px; padding: 2px;">
	<table cellpadding="4" cellspacing="0" border="0" width="500" class="text">
		<tr>
			<td align="left" width="130">Active</td>
			<td align="left" width="270"><input type="checkbox" class="no" name="provider_active" id="provider<?= $providerArr['ID'] ?>_active_id" <?= $providerActive ? 'checked="checked"' : '' ?> /></td>
			<td rowspan="4" width="100" align="right" valign="top"><?= strlen($providerArr['LogoFilename']) > 0 && file_exists($dir['checkout'] . 'images/' . $providerArr['LogoFilename']) ? "<img src=\"{$site['checkout']}images/{$providerArr['LogoFilename']}\" alt=\"". process_line_output($providerArr['Caption']) ."\" />" : '&nbsp;' ?></td>
		</tr>
		<tr>
			<td align="left" width="130">Mode</td>
			<td align="left">
				<select name="provider_mode" id="provider<?= $providerArr['ID'] ?>_mode_id">
					<option value="live" <?= $providerMode == 'live' ? 'selected="selected"' : '' ?> >Live</option>
					<option value="test-approve" <?= $providerMode == 'test-approve' ? 'selected="selected"' : '' ?> >Test (approve)</option>
					<option value="test-decline" <?= $providerMode == 'test-decline' ? 'selected="selected"' : '' ?> >Test (decline)</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="left" width="130">Debug output</td>
			<td align="left"><input type="checkbox" class="no" name="provider_debug" id="provider<?= $providerArr['ID'] ?>_debug_id" <?= $providerDebug ? 'checked="checked"' : '' ?> /></td>
		</tr>
		<tr>
			<td align="left" width="130">Supports recurring</td>
			<td align="left" height="24"><?= $providerArr['SupportsRecurring'] ? 'yes' : 'no' ?></td>
		</tr>
<?
	$paramRes = db_res( "SELECT `Name`, `Caption`, `Type`, `Extra`, `Value` FROM `PaymentParameters` WHERE `IDProvider` = {$providerArr['ID']} AND `Changable` ORDER BY `ID`" );
	while ( $paramArr = mysql_fetch_assoc($paramRes) )
	{
?>
		<tr>
			<td align="left" width="130"><?= process_line_output($paramArr['Caption']) ?></td>
			<td align="left" colspan="2">
<?
		$paramValue = $usePostData ? $_POST["provider_{$paramArr['Name']}"] : $paramArr['Value'];
		switch ( $paramArr['Type'] )
		{
			case 'check':
				echo "<input type=\"checkbox\" class=\"no\" name=\"provider_{$paramArr['Name']}\" id=\"provider{$providerArr['ID']}_{$paramArr['Name']}_id\" ". ($paramValue == 'on' ? 'checked="checked"' : '') ." />";
				break;

			case 'enum':
				echo "<select name=\"provider_{$paramArr['Name']}\" id=\"provider{$providerArr['ID']}_{$paramArr['Name']}_id\">\n";
				$vals = preg_split("/[,\']+/", $paramArr['Extra'], -1, PREG_SPLIT_NO_EMPTY);
				foreach ( $vals as $v )
				{
					echo "<option value=\"{$v}\" ". ($paramValue == $v ? 'selected="selected"' : '') .">". htmlspecialchars($v) ."</option>";
				}
				echo "</select>\n";
				break;

			case 'text':
				echo "<input type=\"text\" class=\"no\" name=\"provider_{$paramArr['Name']}\" id=\"provider{$providerArr['ID']}_{$paramArr['Name']}_id\" value=\"". htmlspecialchars($paramValue) ."\" style=\"width: 250px;\" />";
				break;

			default:
				echo '&nbsp;';
				break;
		}
?>
			</td>
		</tr>
<?
	}
?>
		<tr>
			<td colspan="3">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
						<td align="left"><a href="javascript:void(null);" onclick="javascript: window.open('<?= $_SERVER['PHP_SELF'] ?>?action=help&amp;prov_id=<?= $providerArr['ID'] ?>', 'w<?= $providerArr['Name'] ?>HelpWindow', 'width=500, height=400, left=350, top=100, scrollbars=yes, copyhistory=no, directories=no, menubar=no, location=no, resizable=no');">Payment provider setup instructions</a></td>
						<td align="right" width="60" style="padding-right: 2px;"><input type="submit" class="no" name="provider_save" value="Save" style="width: 60px; vertical-align: middle;" /></td>
						<td align="right" width="190"><input type="submit" class="no" name="provider_validate" value="Validate saved configuration" style="width: 190px; vertical-align: middle;" /></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
</form>
<?
}

/**
 * Saves payment provider settings and parameters to the database
 *
 * @param int $providerID				- payment provider ID
 *
 *
 */
function PPSaveProviderData( $providerID )
{
	$providerActive = $_POST['provider_active'] == 'on' ? '1' : '0';
	switch ( $_POST['provider_mode'] )
	{
		case 'live':
		case 'test-approve':
		case 'test-decline':
			$providerMode = $_POST['provider_mode'];
			break;
		default:
			$providerMode = 'live';
			break;
	}
	$providerDebug = $_POST['provider_debug'] == 'on' ? '1' : '0';

	$res = db_res( "UPDATE `PaymentProviders` SET
						`Active` = {$providerActive},
						`Mode` = '{$providerMode}',
						`Debug` = {$providerDebug}
					WHERE `ID` = {$providerID}" );

	if ( !$res )
		return false;

	$paramRes = db_res( "SELECT `ID`, `Name`, `Type` FROM `PaymentParameters` WHERE `IDProvider` = {$providerID} AND `Changable` ORDER BY `ID`" );
	while ( $paramArr = mysql_fetch_assoc($paramRes) )
	{
		$paramValue = process_db_input( $_POST["provider_{$paramArr['Name']}"] );
		$res = db_res( "UPDATE `PaymentParameters` SET `Value` = '{$paramValue}' WHERE `ID` = {$paramArr['ID']}" );
		if ( !$res )
			return false;
	}

	return true;
}

/**
 * Prints payment provider setup help message
 *
 * @param int $providerID				- payment provider ID
 *
 * @return string 						- HTML content of help body
 *
 *
 */
function PPShowHelp( $providerID )
{
	$styles = <<<EOS
	p.help_text
	{
		font-family: Arial;
		font-size: small;
		font-weight: normal;
		color: black;
		text-align: justify;
	}

	p.help_caption
	{
		font-family: Arial;
		font-size: medium;
		font-weight: bold;
		color: black;
		text-align: left;
	}
EOS;

	ob_start();

	$providerRes = db_res( "SELECT `Caption`, `Help` FROM `PaymentProviders` WHERE `ID` = $providerID" );
	$providerArr = mysql_fetch_assoc($providerRes);
?>
<h3 style="font-family: Arial; font-size: large; font-weight: normal; color: black;"><b><?= process_line_output($providerArr['Caption']) ?></b> setup instructions</h3>
<?= process_html_output($providerArr['Help'], 255) ?>
<?

	$content = ob_get_contents();
	ob_end_clean();
	return PopupPageTemplate( 'Payment provider setup instructions', $content, '', $styles );
}


$settings_status_text = '';
$status_text = '';

if ( $_REQUEST['action'] == 'help' && (int)$_REQUEST['prov_id'] )
{
	$provider_id = (int)$_REQUEST['prov_id'];
	echo PPShowHelp($provider_id);
	exit();
}
elseif ( $_REQUEST['action'] == 'validate_or_save' && isset($_REQUEST['provider_save']) && (int)$_REQUEST['prov_id'] )
{
	$provider_id = (int)$_REQUEST['prov_id'];
	$save_res = PPSaveProviderData( $provider_id );
	if ( $save_res )
		$status_text = 'Payment provider settings were successfully saved';
	else
		$status_text = 'Failed to save payment provider settings';
}
elseif ( $_REQUEST['action'] == 'validate_or_save' && isset($_REQUEST['provider_validate']) && (int)$_REQUEST['prov_id'] )
{
	$provider_id = (int)$_REQUEST['prov_id'];
	$provider_res = db_res( "SELECT `Name`, `CheckoutFilename` FROM `PaymentProviders` WHERE `ID` = {$provider_id}" );
	if ( !$provider_res || mysql_num_rows($provider_res) == 0 )
	{
		$status_text = 'Wrong payment provider specified';
	}
	else
	{
		$provider_arr = mysql_fetch_assoc( $provider_res );
		if ( strlen(trim($provider_arr['CheckoutFilename'])) )
			$checkout_filename = $provider_arr['CheckoutFilename'];
		else
			$checkout_filename = $dir['checkout'] . $provider_arr['Name'] . '.php';
		if ( !file_exists( $checkout_filename ) )
		{
			$status_text = 'Checkout file not found';
		}
		else
		{
			require_once( $checkout_filename );

			$validate_res = moduleValidateConfiguration( $status_text );
			if ( $validate_res )
			{
				$status_text = 'Configuration is valid';
			}
		}
	}
}
elseif ( $_REQUEST['action'] == 'save_settings' )
{
	$res_setparam1 = setParam( 'currency_code', $_POST['currency_code'] );
	$res_setparam3 = setParam( 'enable_recurring', $_POST['enable_recurring'] );
	if ( $res_setparam1 && $res_setparam3 )
	{
		$currency_code = process_pass_data($_POST['currency_code']);
		$enable_recurring = $_POST['enable_recurring'] == 'on';
		$settings_status_text = 'Settings successfully saved';
	}
	else
	{
		$settings_status_text = 'Failed to save settings';
	}
}

TopCodeAdmin();
ContentBlockHead("Common settings");

if ( strlen($settings_status_text) )
	echo "
<center>
	<div class=\"err\">{$settings_status_text}</div>
</center>";
?>

<center>
<form id="settingsForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" style="margin: 0px;">
<input type="hidden" name="action" value="save_settings" />
<div class="section_header" style="width: 440px; margin: 2px;">Settings</div>
<div class="section_row" style="width: 440px; padding: 2px;">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="text">
		<tr>
			<td align="left"><?= getParamDesc('currency_code') ?></td>
			<td align="right" width="24">
				<select name="currency_code" style="width: 200px;">
<?
	$code_res = db_res( "SELECT `CurrencyCode`, `Currency` FROM `Countries`  WHERE `CurrencyCode` IS NOT NULL GROUP BY `CurrencyCode`" );
	while ( $code_arr = mysql_fetch_assoc($code_res) )
	{
		echo "<option value=\"{$code_arr['CurrencyCode']}\" ". ($currency_code == $code_arr['CurrencyCode'] ? 'selected="selected"' : '') .">{$code_arr['CurrencyCode']} ({$code_arr['Currency']})</option>\n";
	}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="left">
				<label for="enable_recurring_id"><?= getParamDesc('enable_recurring') ?></label>
			</td>
			<td align="right" width="24">
				<input type="checkbox" class="no" name="enable_recurring" id="enable_recurring_id" <?= $enable_recurring ? 'checked="checked"' : '' ?> />
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2"><input type="submit" class="no" name="settings_save" value="Save" style="width: 60px;" /></td>
		</tr>
	</table>
</div>
</form>
</center>

<?
ContentBlockFoot();
ContentBlockHead("Manage payment providers");

if ( strlen($status_text) )
	echo "
<center>
	<div class=\"err\">{$status_text}</div>
</center>";
?>

<center>

<?
$payments_res = db_res( "SELECT `ID`, `Name`, `Caption`, `Active`, `Mode`, `Debug`, `SupportsRecurring`, `LogoFilename` FROM `PaymentProviders`" );
while ( $payment_arr = mysql_fetch_assoc($payments_res) )
{
	PPShowProviderBox( $payment_arr );
}
?>

</center>

<?

ContentBlockFoot();
BottomCode();
?>