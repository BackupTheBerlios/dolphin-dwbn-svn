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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'checkout.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$sHead = '';
if (getParam("enable_aff") != 'on')
{
	$sHead = "(<span style=\"color:red;\">"._t("_affiliate_system_was_disabled")."</span>)";
}

if ( !($logged['aff'] = member_auth( 2, false)) )
	$logged['admin'] = member_auth( 1 );

$AFF = $_COOKIE['affID'] > 0 ? $_COOKIE['affID'] : ( $_GET['affID'] > 0 ? $_GET['affID'] : $_POST['affID'] );

if ( !strstr($AFF, "@") )
{
	$affID = (int)$AFF;
	$aff_arr = db_arr("SELECT `Percent` FROM `aff` WHERE `ID` = $affID");
}
else
{
	$AFF = process_db_input($AFF);
	$aff_arr = db_arr("SELECT `ID`, `Percent` FROM `aff` WHERE `email` = '$AFF'");
	$affID = (int)$aff_arr['ID'];
}

$percent = (float)$aff_arr['Percent'] / 100.0;
$tr = array();
$tr_query = "";
$order_num_disabled = 'disabled="disabled"';
$last_disabled = 'disabled="disabled"';
$date_disabled = 'disabled="disabled"';
$between_disabled = 'disabled="disabled"';

switch ( $_POST['calculation_type'] )
{
	case 'total':
		$tr_query = "SELECT `ID`, `IDMember`, `IDProvider`, `gtwTransactionID`, DATE_FORMAT(`Date`,  '$date_format' ) AS 'Date', `Amount`, `Currency`, `Status`, `Data`, `Description`, `Note` FROM `Transactions` INNER JOIN `aff_members` ON (`idProfile` = `IDMember`) WHERE `idAff` = $affID AND `Status` = 'approved' ORDER BY `Date` DESC";
		break;

	case 'order_num':
		$order_id = process_db_input( $_POST['tr_order_num'] );
		$tr['order_num'] = process_pass_data( $_POST['tr_order_num'] );
		$tr_query = "SELECT `ID`, `IDMember`, `IDProvider`, `gtwTransactionID`, DATE_FORMAT(`Date`,  '$date_format' ) AS 'Date', `Amount`, `Currency`, `Status`, `Data`, `Description`, `Note` FROM `Transactions` INNER JOIN `aff_members` ON (`idProfile` = `IDMember`) WHERE `idAff` = $affID AND `gtwTransactionID` = '$order_id' AND `Status` = 'approved' ORDER BY `Date` DESC";
		$order_num_disabled = "";
		break;

	case 'last':
		$last_days = (int)$_POST['tr_last_days'];
		$tr['last_days'] = process_pass_data( $_POST['tr_last_days'] );
		$tr_query = "SELECT `ID`, `IDMember`, `IDProvider`, `gtwTransactionID`, DATE_FORMAT(`Date`,  '$date_format' ) AS 'Date', `Amount`, `Currency`, `Status`, `Data`, `Description`, `Note` FROM `Transactions` INNER JOIN `aff_members` ON (`idProfile` = `IDMember`) WHERE `idAff` = $affID AND ( TO_DAYS( NOW() ) - TO_DAYS( `Date` ) <= $last_days ) AND `Status` = 'approved' ORDER BY `Date` DESC";
		$last_disabled = "";
		break;

	case 'date':
		$exact_date = strtotime( $_POST['tr_exact_date'] );
		$tr['exact_date'] = process_pass_data( $_POST['tr_exact_date'] );
		if ( $exact_date != -1 )
		{
			$tr_query = "SELECT `ID`, `IDMember`, `IDProvider`, `gtwTransactionID`, DATE_FORMAT(`Date`,  '$date_format' ) AS 'Date', `Amount`, `Currency`, `Status`, `Data`, `Description`, `Note` FROM `Transactions` INNER JOIN `aff_members` ON (`idProfile` = `IDMember`) WHERE `idAff` = $affID AND TO_DAYS( FROM_UNIXTIME($exact_date) ) = TO_DAYS( `Date` ) AND `Status` = 'approved' ORDER BY `Date` DESC";
		}
		else
		{
			$tr_query = "";
			$tr['error_text'] = "Please specify correct dates";
		}
		$date_disabled = "";
		break;

	case 'between':
		// First date parse
		if ( $_POST['tr_between_date1'] == 'start' )
			$between_date1 = 0;
		elseif ( $_POST['tr_between_date1'] == 'now' )
			$between_date1 = time();
		else
			$between_date1 = strtotime( $_POST['tr_between_date1'] );
		// Second date parse
		if ( $_POST['tr_between_date2'] == 'start' )
			$between_date2 = 0;
		elseif ( $_POST['tr_between_date2'] == 'now' )
			$between_date2 = time();
		else
			$between_date2 = strtotime( $_POST['tr_between_date2'] );

		$tr['between_date1'] = process_pass_data( $_POST['tr_between_date1'] );
		$tr['between_date2'] = process_pass_data( $_POST['tr_between_date2'] );
		if ( $between_date1 != -1 && $between_date2 != -1 )
		{
			$tr_query = "SELECT `ID`, `IDMember`, `IDProvider`, `gtwTransactionID`, DATE_FORMAT(`Date`,  '$date_format' ) AS 'Date', `Amount`, `Currency`, `Status`, `Data`, `Description`, `Note` FROM `Transactions` INNER JOIN `aff_members` ON (`idProfile` = `IDMember`) WHERE `idAff` = $affID AND ( TO_DAYS( FROM_UNIXTIME($between_date1) ) <= TO_DAYS( `Date` ) AND TO_DAYS( FROM_UNIXTIME($between_date2) ) >= TO_DAYS( `Date` ) ) AND `Status` = 'approved' ORDER BY `Date` DESC";
		}
		else
		{
			$tr_query = "";
			$tr['error_text'] = "Please specify correct dates";
		}
		$between_disabled = "";
		break;
}

// Calculations
if ( strlen($tr_query) )
{
	$fin = getFinanceAffStat( $affID, $tr );
}

// Transactions
if ( strlen($tr_query) )
{
	$tr_res = db_res( $tr_query );
	$tr_num = mysql_num_rows( $tr_res );
}
else
{
	$tr_num = 0;
}

$_page['header'] = "Finance";
$_page['header_text'] = "Calculations and transactions";

send_headers_page_changed();

ob_start();
?>

<div id="helpDivId" style="width: 200px; height: auto; background-color: #F5F5F5; color: #000000; border: 1px solid silver; position: absolute; left: 0px; top: 0px; z-index: 1000; display: none;">
	<div id="helpTypeId" style="position: relative; margin: 2px; padding: 1px; white-space: nowrap;">CONTENT</div>
	<div id="helpDescId" style="position: relative; margin: 2px; padding: 1px;">CONTENT</div>
	<div id="helpNoteId" style="position: relative; margin: 2px; padding: 1px; text-align: justify;">CONTENT</div>
</div>

<?
$helpDivCode = ob_get_contents();
ob_end_clean();
/*
if ( $logged['admin'] )
	TopCodeAdmin( $helpDivCode );
else
	TopCodeAff( $helpDivCode );
*/
TopCodeAdmin( $helpDivCode );
ContentBlockHead("Finance ".$sHead);

if ( strlen($tr['error_text']) )
	echo "<br><center><div class=\"err\">{$tr['error_text']}</div></center><br>";

?>
<br>
<center>

<script type="text/javascript">
<!--
	function updateControls()
	{
		document.forms['calculations_form'].elements['tr_order_num'].disabled = !(document.getElementById('id_order').checked);
		document.forms['calculations_form'].elements['tr_last_days'].disabled = !(document.getElementById('id_last').checked);
		document.forms['calculations_form'].elements['tr_exact_date'].disabled = !(document.getElementById('id_date').checked);
		document.getElementById('exact_choose').disabled = !(document.getElementById('id_date').checked);
		document.getElementById('exact_clear').disabled = !(document.getElementById('id_date').checked);
		document.forms['calculations_form'].elements['tr_between_date1'].disabled = !(document.getElementById('id_between').checked);
		document.getElementById('between1_choose').disabled = !(document.getElementById('id_between').checked);
		document.getElementById('between1_clear').disabled = !(document.getElementById('id_between').checked);
		document.forms['calculations_form'].elements['tr_between_date2'].disabled = !(document.getElementById('id_between').checked);
		document.getElementById('between2_choose').disabled = !(document.getElementById('id_between').checked);
		document.getElementById('between2_clear').disabled = !(document.getElementById('id_between').checked);
	}
-->
</script>

<form id="calculations_form" action="<? echo $_SERVER['PHP_SELF'] ?>" method="post">
<input type="hidden" name="affID" value="<?= $affID ?>">
<table class="text" cellspacing=0 cellpadding=4 width=500 style="border: 1px silver solid;">
	<tr class=panel>
		<td colspan=2 nowrap>&nbsp;<b>Show transactions and calculate sums</b>&nbsp;</td>
	</tr>
	<tr class=table>
		<td align="center">
			<table cellpadding="5" cellspacing="0" border="0" width="400">
				<tr>
					<td align="left">
						<input type="radio" name="calculation_type" value="total" id="id_total" onClick="javascript: updateControls();" <?= $_POST['calculation_type'] == 'total' ? 'checked="checked"' : '' ?>>
					</td>
					<td align="left" colspan="2" nowrap><label for="id_total">Total amount</label></td>
				</tr>
				<tr>
					<td align="left">
						<input type="radio" name="calculation_type" value="order_num" id="id_order" onClick="javascript: updateControls();" <?= $_POST['calculation_type'] == 'order_num' ? 'checked="checked"' : '' ?>>
					</td>
					<td align="left" nowrap>
						<label for="id_order">For order number</label>
					</td>
					<td align="left" nowrap>
						<input name="tr_order_num" type=text class=no size=16 value="<?= htmlspecialchars($tr['order_num']) ?>" <?= $order_num_disabled ?>>
					</td>
				</tr>
				<tr>
					<td align="left">
						<input type="radio" name="calculation_type" value="last" id="id_last" onClick="javascript: updateControls();" <?= $_POST['calculation_type'] == 'last' ? 'checked="checked"' : '' ?>>
					</td>
					<td align="left" nowrap>
						<label for="id_last">During last</label>
					</td>
					<td align="left" nowrap>
						<input name="tr_last_days" type=text class=no size=6 value="<?= htmlspecialchars($tr['last_days']) ?>" <?= $last_disabled ?>>
						&nbsp;days (type <b>0</b> for today's transactions)
					</td>
				</tr>
				<tr>
					<td align="left">
						<input type="radio" id="id_date" name="calculation_type" value="date" id="id_date" onClick="javascript: updateControls();" <?= $_POST['calculation_type'] == 'date' ? 'checked="checked"' : '' ?>>
					</td>
					<td align="left" nowrap>
						<label for="id_date">On the date:</label>
					</td>
					<td align="left" nowrap>
						<input style="width: 130px" id="tr_exact_date" name="tr_exact_date" type=text class=no value="<?= htmlspecialchars($tr['exact_date']) ?>" <?= $date_disabled ?>>
						&nbsp;<input style="width: 70px" type="button" id="exact_choose" value="Choose" <?= $date_disabled ?>/>
						&nbsp;<input style="width: 70px" type="button" id="exact_clear" onClick="document.getElementById('tr_exact_date').value = ''; " value="Clear" <?= $date_disabled ?>/>
					</td>
				</tr>
				<tr>
					<td align="left">
						<input type="radio" name="calculation_type" value="between" id="id_between" onClick="javascript: updateControls();" <?= $_POST['calculation_type'] == 'between' ? 'checked="checked"' : '' ?>>
					</td>
					<td align="left" nowrap>
						<label for="id_between">Between dates</label>
					</td>
					<td align="left" nowrap>
						<input style="width: 130px" id="tr_between_date1" name="tr_between_date1" type=text class=no size=10 value="<?= htmlspecialchars($tr['between_date1']) ?>" <?= $between_disabled ?>>
						&nbsp;<input style="width: 70px" type="button" id="between1_choose" value="Choose" <?= $between_disabled ?>/>
						&nbsp;<input style="width: 70px" type="button" id="between1_clear" onClick="document.getElementById('tr_between_date1').value = ''; " value="Clear" <?= $between_disabled ?>/>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="right" nowrap>
						and
					</td>
					<td align="left" nowrap>
						<input style="width: 130px" id="tr_between_date2" name="tr_between_date2" type=text class=no size=10 value="<?= htmlspecialchars($tr['between_date2']) ?>" <?= $between_disabled ?>>
						&nbsp;<input style="width: 70px" type="button" id="between2_choose" value="Choose" <?= $between_disabled ?>/>
						&nbsp;<input style="width: 70px" type="button" id="between2_clear" onClick="document.getElementById('tr_between_date2').value = ''; " value="Clear" <?= $between_disabled ?>/>
					</td>
				</tr>
				<tr>
					<td align="left">&nbsp;</td>
					<td align="left" colspan="2">
						<div align=justify>The earlier date must be set first, the later one - second. Also you can use words "<b>start</b>" and "<b>now</b>" to indicate the beginning of your work and the present date. Do not use queries like "between start and now" because it can display a huge amount of transactions.</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br />
<center>
	<input class=no type=submit value="Submit">
</center>
</form>

<!-- Loading Calendar JavaScript files -->
    <script type="text/javascript" src="<?= $site['plugins'] ?>calendar/calendar_src/utils.js"></script>
    <script type="text/javascript" src="<?= $site['plugins'] ?>calendar/calendar_src/calendar.js"></script>
    <script type="text/javascript" src="<?= $site['plugins'] ?>calendar/calendar_src/calendar-setup.js"></script>

<!-- Loading language definition file -->
    <script type="text/javascript" src="<?= $site['plugins'] ?>calendar/calendar_lang/calendar-en.js"></script>

<script type="text/javascript">
//<![CDATA[
      Zapatec.Calendar.setup({
        firstDay          : 1,
        weekNumbers       : true,
        showOthers        : true,
        showsTime         : false,
        timeFormat        : "24",
        step              : 2,
        range             : [1900.01, 2999.12],
        electric          : false,
        singleClick       : true,
        inputField        : "tr_exact_date",
        button            : "exact_choose",
        ifFormat          : "%Y-%m-%d",
        daFormat          : "%Y/%m/%d",
        align             : "Br"
      });
      Zapatec.Calendar.setup({
        firstDay          : 1,
        weekNumbers       : true,
        showOthers        : true,
        showsTime         : true,
        timeFormat        : "24",
        step              : 2,
        range             : [1900.01, 2999.12],
        electric          : false,
        singleClick       : true,
        inputField        : "tr_between_date1",
        button            : "between1_choose",
        ifFormat          : "%Y-%m-%d %H:%M:%S",
        daFormat          : "%Y/%m/%d",
        align             : "Br"
      });
      Zapatec.Calendar.setup({
        firstDay          : 1,
        weekNumbers       : true,
        showOthers        : true,
        showsTime         : true,
        timeFormat        : "24",
        step              : 2,
        range             : [1900.01, 2999.12],
        electric          : false,
        singleClick       : true,
        inputField        : "tr_between_date2",
        button            : "between2_choose",
        ifFormat          : "%Y-%m-%d %H:%M:%S",
        daFormat          : "%Y/%m/%d",
        align             : "Br"
      });
//]]>
</script>

</center>

			</div>
		</div>
		<div class="block_foot"></div>
<?

if ( strlen($tr_query) )
{
?>
		<div class="block_red_head">Please Pay Attention</div>
		<div class="block_red_outer">
			<div class="block_inner">
				<b>NOTE:</b> Only transactions with site's current currency will be shown here.
			</div>
		</div>
		<div class="block_red_foot"></div>

		<div class="block_head">Transactions and Calculations</div>
		<div class="block_outer">
			<div class="block_inner">

	<script type="text/javascript">
	<!--
		function showHelpDiv( helpEvent, typeData, descData, noteData )
		{
			if ( !helpEvent )
				helpEvent = window.event;
			helpDiv = document.getElementById('helpDivId');

			var pos_X = 0, pos_Y = 0;
			if ( helpEvent )
			{
				if ( typeof(helpEvent.pageX) == 'number' )
				{
					pos_X = helpEvent.pageX; pos_Y = helpEvent.pageY;
				}
				else if ( typeof(helpEvent.clientX) == 'number' )
				{
					pos_X = helpEvent.clientX; pos_Y = helpEvent.clientY;
					if ( document.body && ( document.body.scrollTop || document.body.scrollLeft ) && !( window.opera || window.debug || navigator.vendor == 'KDE' ) )
					{
						pos_X += document.body.scrollLeft; pos_Y += document.body.scrollTop;
					}
					else if ( document.documentElement && ( document.documentElement.scrollTop || document.documentElement.scrollLeft ) && !( window.opera || window.debug || navigator.vendor == 'KDE' ) )
					{
						pos_X += document.documentElement.scrollLeft; pos_Y += document.documentElement.scrollTop;
					}
				}
			}

			var scroll_X = 0, scroll_Y = 0;
			if ( document.body && ( document.body.scrollTop || document.body.scrollLeft ) && !( window.debug || navigator.vendor == 'KDE' ) )
			{
				scroll_X = document.body.scrollLeft; scroll_Y = document.body.scrollTop;
			}
			else if ( document.documentElement && ( document.documentElement.scrollTop || document.documentElement.scrollLeft ) && !( window.debug || navigator.vendor == 'KDE' ) )
			{
				scroll_X = document.documentElement.scrollLeft; scroll_Y = document.documentElement.scrollTop;
			}

			var win_size_X = 0, win_size_Y = 0;
			if (window.innerWidth && window.innerHeight)
			{
				win_size_X = window.innerWidth; win_size_Y = window.innerHeight;
			}
			else if (document.documentElement && document.documentElement.clientWidth && document.documentElement.clientHeight)
			{
				win_size_X = document.documentElement.clientWidth; win_size_Y = document.documentElement.clientHeight;
			}
			else if (document.body && document.body.clientWidth && document.body.clientHeight)
			{
				win_size_X = document.body.clientWidth; win_size_Y = document.body.clientHeight;
			}

			pos_X += 15; pos_Y += 15;

			if (helpDiv.offsetWidth && helpDiv.offsetHeight)
			{
				if (pos_X - scroll_X + helpDiv.offsetWidth + 5 > win_size_X)
					pos_X -= (helpDiv.offsetWidth + 25);
				if (pos_Y - scroll_Y + helpDiv.offsetHeight + 5 > win_size_Y)
					pos_Y -= (helpDiv.offsetHeight + 20);
			}

			helpDiv.style.left = pos_X + 'px'; helpDiv.style.top = pos_Y + 'px';

			document.getElementById('helpTypeId').innerHTML = typeData;
			document.getElementById('helpDescId').innerHTML = descData;
			document.getElementById('helpNoteId').innerHTML = noteData;
			helpDiv.style.display = 'block';
		}

		function hideHelpDiv()
		{
			document.getElementById('helpDivId').style.display = 'none';
		}
	-->
	</script>

	<center>
	<table class=small cellspacing=1 cellpadding=2>
		<tr class=panel>
			<td colspan=2 align="center">&nbsp;<b>Calculations for the query</b></td>
		</tr>
		<tr class=table>
			<td align="left">&nbsp;Membership subscriptions&nbsp;</td>
			<td align=right nowrap>&nbsp;<b><?= $doll.$fin['membership_amount']; ?></b>&nbsp;</td>
		</tr>
		<tr class=table>
			<td align="left">&nbsp;Credits&nbsp;</td>
			<td align=right nowrap>&nbsp;<b><?= $doll.$fin['credits_amount']; ?></b>&nbsp;</td>
		</tr>
		<tr class=table>
			<td align="left">&nbsp;Contact sales&nbsp;</td>
			<td align=right nowrap>&nbsp;<b><?= $doll.$fin['sales_amount']; ?></b>&nbsp;</td>
		</tr>
		<tr class=table>
			<td align="left">&nbsp;Events tickets&nbsp;</td>
			<td align=right nowrap>&nbsp;<b><?= $doll.$fin['sdating_amount']; ?></b>&nbsp;</td>
		</tr>
		<tr class=table>
			<td align="left">&nbsp;Total&nbsp;</td>
			<td align=right nowrap>&nbsp;<b><?= $doll.$fin['total']; ?></b>&nbsp;</td>
		</tr>
	</table>
	<br>

	<table class=small cellspacing=2 cellpadding=4 width=520>
		<tr>
			<td class=panel colspan=6 align="center"><b>Transactions</b></td>
		</tr>
	<?
		if ( $tr_num == 0 )
		{
			echo "
		<tr>
			<td align=center>No transactions available</td>
		</tr>
			";
		}
		else
		{
			$paymentProviders = getPaymentProviders( false );
	?>
		<tr class=panel>
			<td align="left" nowrap>Order number</td>
			<td align="left" nowrap>Date</td>
			<td align="left" nowrap>Member</td>
			<td align="left" nowrap>Provider</td>
			<td align="center" nowrap>Info</td>
			<td align="right" nowrap>Sum paid, <?= $currency_code ?></td>
		</tr>
	<?
			while( $tr_arr = mysql_fetch_array( $tr_res ) )
			{
				$tranDataArray = transStringToData( $tr_arr['Data'] );
				$tranTypeString = '<b>Type:</b> ' . $tranDataArray['action'];
				$tranDataString = '<b>Description:</b> ' . returnDescByAction( $tranDataArray['action'], $tranDataArray['data'], false );
				$tranNoteString = strlen($tr_arr['Note']) ? '<b>Note:</b> ' . $tr_arr['Note'] : '';
	?>
		<tr class=table>
			<td><?= $tr_arr['gtwTransactionID'] ?></td>
			<td align="left" nowrap><?= $tr_arr['Date'] ?></td>
			<td align="left" nowrap><?= "<a href=\"{$site['url']}profile.php?ID={$tr_arr['IDMember']}\">{$tr_arr['IDMember']}</a>" ?></td>
			<td align="left" nowrap><?= $paymentProviders[$tr_arr['IDProvider']]['Caption'] ?></td>
			<td align="center" nowrap><a href="javascript:void(0);" onmouseover="javascript: showHelpDiv(event, '<?= $tranTypeString ?>', '<?= $tranDataString ?>', '<?= $tranNoteString ?>');" onmouseout="javascript: hideHelpDiv();">Info</a></td>
			<td align="right" nowrap><?= sprintf( "%.2f", $percent * (float)$tr_arr['Amount'] ) ?></td>
		</tr>
	<?
			}
		}
	?>
	</table>
	</center>
	<?
	ContentBlockFoot();
}

BottomCode();
?>