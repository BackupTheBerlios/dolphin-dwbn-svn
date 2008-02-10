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
require_once( BX_DIRECTORY_PATH_INC . 'profile_disp.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );

$IDNonMember = 1;

// Check if admin is logged in and save this info into $logged var.
$logged['admin'] = member_auth(1);
$_page['css_name'] = 'memb_levels.css';

$_page['header'] = 'Manage Membership Types'; // Set page title.

function displayOptions()
{
	$sCat = "`kateg` IN ('5','7') ";
	
	$sClear = '
		<div style="
			position:relative;
			clear:both;
			height:0px;
			line-height:0px;
			margin:0px;
			padding:0px;
			font-size:0px;">
		</div>';
	
	$sMain = 'position:relative; float:left;';
	
	$sQuery = "SELECT
    				`GlParams`.`Name`,
    				`VALUE` as `Value`,
    				`desc`,
    				`Type`,
    				`GlParamsKateg`.`name` AS `kategName`,
    				`order_in_kateg` as `Order`
    		FROM
    				`GlParams`
    		LEFT JOIN `GlParamsKateg` ON `GlParamsKateg`.`ID` = `GlParams`.`kateg`
    		WHERE
    				".$sCat." AND `GlParams`.`Name` NOT LIKE '%_subject' OR `GlParams`.`Name` = 'free_mode' ORDER BY `kateg`,`order_in_kateg` ASC";
	
	$sCode = '<form method="post" action="'. $_SERVER['SCRIPT_NAME'].'">
        <input type="hidden" name="save_settings" value="yes">
        <input type="hidden" name="category" value="'.$iCat.'">
        <div>';
	
	$rData = db_res($sQuery);
	while($aData = mysql_fetch_assoc($rData))
	{
		$sField = '';
		$sCode .= '<div style="margin: 10px 10px 10px 10px;">';
		$sCode .= '<div style="'.$sMain.'width:400px; height:20px;">'.$aData['desc'].'</div>';

		switch($aData['Type'])
		{
			case 'checkbox':
				$sField = '<div style="'.$sMain.'text-align:right; width:170px;">
					<input type="checkbox" name="'.$aData['Name'].'"'. ('on' == $aData['Value'] ? 'checked="checked"' : '').'"></div>';
				break;
			case 'digit':
				$sField = '<div style="'.$sMain.'text-align:right; width:170px;">
					<input type="text" name="'.$aData['Name'].'" size="15" value="'.$aData['Value'].'" />
				</div>';
				break;
			default:
		}
		
		$sCode .= $sField.$sClear.'</div>';
	}
	
	$sCode .= '</div>';
	
	$sCode .= '<div style="text-align: center;">
	<input type="submit" value="Save Changes" class="text" name="saveMemSet"></form></div>';
	
	return $sCode;
}

function saveMemSettings()
{
	$aDigit = array('expire_notification_days','promotion_membership_days');
	
	$aCheck = array('expire_notify_once','enable_promotion_membership','free_mode');
	
	
	foreach ($aDigit as $i => $sVal)
	{
		if ($_POST[$sVal])
    	{
        	setparam($sVal, htmlspecialchars_adv($_POST[$sVal]));
    	}
	}
	
	foreach ($aCheck as $i => $sVal)
	{
		if ('on' == $_POST[$sVal])
    	{
        	setparam($sVal, 'on');
    	}
    	else
    	{
        	setparam($sVal, '');
    	}
	}
}

function addMembership($membershipName)
{
	$membershipName = trim($membershipName);

	if(!$membershipName) return '';

	if(!get_magic_quotes_gpc()) $membershipName = addslashes($membershipName);

	@mysql_query("INSERT INTO MemLevels (Name) VALUES ('$membershipName')");

	if(mysql_affected_rows() > 0) {
		if(!get_magic_quotes_gpc()) $membershipName = stripslashes($membershipName);
		return "\n<div class=\"actionSuccess\"> '".htmlspecialchars($membershipName)."' membership has been added</div>";
	}

	return "\n<div class=\"actionFailure\">Error: membership has not been added</div>";
}

function deleteMembership($membershipID)
{
	$membershipID = (int)$membershipID;

	if($membershipID <= 0) return '';

	$resMemLevel = mysql_query("SELECT Removable FROM MemLevels WHERE ID = $membershipID");

	if(mysql_num_rows($resMemLevel) < 1) {
		return "\n<div class=\"actionFailure\">Error: no such membership</div>";
	}

	//check if membership can be removed

	$removable = mysql_fetch_assoc($resMemLevel);
	$removable = $removable['Removable'] == 'yes' ? true : false;

	if(!$removable) {
		return "\n<div class=\"actionFailure\">Error: this membership cannot be removed</div>";
	}

	//check if there are still members using this membership

	$resMaxDateExpires = @mysql_query("
		SELECT UNIX_TIMESTAMP(MAX(DateExpires)) as MaxDateExpires
		FROM ProfileMemLevels WHERE IDLevel = $membershipID");

	//mysql_num_rows() for the query above is always 1

	$maxDateExpires = mysql_fetch_assoc($resMaxDateExpires);
	$maxDateExpires = $maxDateExpires['MaxDateExpires'];

	if($maxDateExpires > time()) {
		return "\n
		<div class=\"actionFailure\">
			There are currently members using this membership so it cannot be deleted.<br>
			If you want to delete this membership, please make it inactive and wait until
			it expires for all members currently using it (".date("F j, Y, g:i a", $maxDateExpires).").
		</div>";
	}

	@mysql_query("DELETE FROM MemLevelPrices WHERE IDLevel = $membershipID");
	@mysql_query("DELETE FROM MemLevelActions WHERE IDLevel = $membershipID");
	@mysql_query("DELETE FROM MemLevels WHERE ID = $membershipID");

	if(mysql_affected_rows() > 0) {
		return "\n<div class=\"actionSuccess\">Membership has been deleted</div>";
	}

	return "\n<div class=\"actionFailure\">Error: membership has not been deleted</div>";
}

function activateMembership($membershipID, $active)
{
	$membershipID = (int)$membershipID;
	if($active != 'yes' && $active != 'no') return '';

	if($membershipID <= 0) return '';

	$resMemLevel = mysql_query("SELECT Purchasable FROM MemLevels WHERE ID = $membershipID");

	if(mysql_num_rows($resMemLevel) < 1) {
		return "\n<div class=\"actionFailure\">Error: no such membership</div>";
	}

	//check if membership can be purchased

	$purchasable = mysql_fetch_assoc($resMemLevel);
	$purchasable = $purchasable['Purchasable'] == 'yes' ? true : false;

	if(!$purchasable) {
		return "\n<div class=\"actionFailure\">Error: this membership cannot be (de)activated because it's not purchasable.</div>";
	}

	@mysql_query("UPDATE MemLevels SET Active = '$active' WHERE ID = $membershipID");

	if(mysql_affected_rows() <= 0){
		$de = $active ? '' : 'de';
		return "\n<div class=\"actionFailure\">Error: membership has not been {$de}activated</div>";
	}

	return '';
}

function membershipList()
{
	$editLevel = (int)$_GET['edit_level'];

	ob_start();

//ContentBlockHead("Manage Membership Types");
?>
<!--
<div class="sectionHeader">Manage Membership Types</div>
<div class="sectionBody"><div style="padding: 10px"> -->
	<?= addMembership($_POST['add_membership']) ?>
	<?= deleteMembership($_POST['delete_membership']) ?>
	<?= activateMembership($_POST['activate_membership_id'], $_POST['activate_membership_active']) ?>
	<div align="right">
		<form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
			New membership:
			<input type="text" name="add_membership" />
			<input type="submit" value="Add" />
		</form>
	</div>

	<table class="membershipList" cellspacing="0" align="center">
		<tr class="headerRow">
			<td></td>
			<td>
				Membership Types
			</td>
		</tr>
		<tr>
			<td colspan="5" class="spacer"></td>
		</tr>
<?
	$resMemLevels = db_res ( "SELECT ID, Name, Active, Purchasable, Removable FROM MemLevels ORDER BY ID" );

	if (mysql_num_rows($resMemLevels) <= 0) {
?>
		<tr>
			<td align="center">
				There are no membership levels available at the moment
			</td>
		</tr>
<?
	} else {
		while($arrMemLevel = mysql_fetch_assoc($resMemLevels))
		{
			$ID = $arrMemLevel['ID'];

			$selectedRow = $editLevel == $ID ? 'class="activeRow"' : '';
?>
		<tr <?= $selectedRow ?>>
<?
			//print 'Active/Inactive' cell

			if ($arrMemLevel['Purchasable'] == 'yes')
			{
				$membershipActive = $arrMemLevel['Active'] == 'yes' ? true : false;
?>
			<td class="<?= $membershipActive ? 'deactivate' : 'activate' ?>">
				<form name="formActivate<?= $ID ?>" action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post" style="margin: 0">
					<input type="hidden" name="activate_membership_id" value="<?= $ID ?>" />
					<input type="hidden" name="activate_membership_active" value="<?= $membershipActive ? 'no' : 'yes' ?>" />
				</form>
				<a href="javascript:void(0)" onclick="document.forms['formActivate<?= $ID ?>'].submit(); return false;">
					<?= $membershipActive ? '[&nbsp;Active&nbsp;]' : '[Inactive]'; ?>
				</a>
			</td>
<?
			}else{
?>
			<td class="activate"></td>
<?
			}
?>
			<td class="name">
				<a href="<?= $_SERVER['SCRIPT_NAME'] ?>?edit=actions&edit_level=<?= $ID ?>">
					<div style="padding: 0; margin: 0; width: 100%; cursor: pointer">
						<?= htmlspecialchars($arrMemLevel['Name']) ?>
					</div>
				</a>
			</td>
<?
			if ($arrMemLevel['Purchasable'] == 'yes')
			{
?>
			<td class="pricing">
				<a href="<?= $_SERVER['SCRIPT_NAME'] ?>?edit=pricing&edit_level=<?= $ID ?>">
					Pricing
				</a>
			</td>
			<td class="delete">
				<form name="formDelete<?= $ID ?>" action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST" style="margin: 0">
					<input type="hidden" name="delete_membership" value="<?= $ID ?>" />
				</form>
				<a href="javascript:void(0)" onclick="confirmAndSubmit('formDelete<?= $ID ?>', 'Please confirm membership deletion.'); return false;">Delete</a>
			</td>
<?
			} else {
?>
			<td class="pricing"></td><td class="delete"></td>
<?
			}
?>
		</tr>
		<tr>
			<td colspan="4" class="spacer"></td>
		</tr>
<?
		}
	}
?>
	</table>

<?
//ContentBlockFoot();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

function addPaymentOption($membershipID, $days, $price)
{
	$membershipID = (int)$membershipID;
	$days = (int)$days;
	$price = round((float)$price, 2);

	if( $membershipID <= 0 || $days < 0 || $price <= 0 ) return '';

	if($days < 0 || $price <= 0) return "\n<div class=\"actionFailure\">Error: payment option has not been added</div>";

	@mysql_query("INSERT INTO MemLevelPrices (IDLevel, Days, Price) VALUES ($membershipID, $days, $price)");

	if(mysql_affected_rows() > 0)
	{
		if ( $days > 0 )
		{
			return "\n<div class=\"actionSuccess\">{$days}-day payment option added successfully</div>";
		}
		else
		{
			return "\n<div class=\"actionSuccess\">Lifetime membership payment option added successfully</div>";
		}
	}

	return "\n<div class=\"actionFailure\">Error: payment option has not been added</div>";
}

function deletePaymentOption($membershipID, $days)
{
	$membershipID = (int)$membershipID;
	$days = (int)$days;

	if($membershipID <= 0 || $days < 0) return '';

	@mysql_query("DELETE FROM MemLevelPrices WHERE IDLevel = $membershipID AND Days = $days");

	if(mysql_affected_rows() > 0)
	{
		if ( $days > 0 )
		{
			return "\n<div class=\"actionSuccess\">{$days}-day payment option deleted successfully</div>";
		}
		else
		{
			return "\n<div class=\"actionSuccess\">Lifetime membership payment option deleted successfully</div>";
		}
	}

	return "\n<div class=\"actionFailure\">Error: payment option has not been deleted</div>";
}

function membershipPricing($membershipID)
{
	$membershipID = (int)$membershipID;
	if($membershipID <= 0) return '';

	//check if membership can be purchased

	$resMemLevel = mysql_query("SELECT Purchasable FROM MemLevels WHERE ID = $membershipID");

	if(mysql_num_rows($resMemLevel) < 1) {
		return "\n<div class=\"actionFailure\">Error: no such membership</div>";
	}

	$purchasable = mysql_fetch_assoc($resMemLevel);
	$purchasable = $purchasable['Purchasable'] == 'yes' ? true : false;

	if(!$purchasable) {
		return "\n<div class=\"actionFailure\">Error: this membership cannot be purchased</div>";
	}

	ob_start();

ContentBlockHead("Pricing");
?>
<!--
<div class="sectionHeader">Pricing</div>
<div class="sectionBody"><div style="padding: 10px">
-->
<?
	if($_POST['add_payment_option']) echo addPaymentOption($membershipID, $_POST['payment_days'], $_POST['payment_price']);
	if($_POST['delete_payment_option']) echo deletePaymentOption($membershipID, $_POST['delete_payment_days']);

	$resLevelPrices = db_res("SELECT Days, Price FROM MemLevelPrices WHERE IDLevel = $membershipID ORDER BY Days");
?>
	<div align="right">
		<form action="<?= $_SERVER['SCRIPT_NAME'] ?>?edit=pricing&edit_level=<?= $membershipID ?>" method="POST">
			<input type="hidden" name="add_payment_option" value="yes">
			Number of days (0 = life-time membership):
			<input type="text" size="4" name="payment_days">
			Price:
			<input type="text" size="7" name="payment_price">
			<input type="submit" value="Add payment option">
		</form>
	</div>
	<table align="center" cellspacing="0" class="priceList">
		<tr class="headerRow">
			<td>
				Days
			</td>
			<td>
				Price
			</td>
		</tr>
		<tr>
			<td colspan="3" class="spacer"></td>
		</tr>
<?
	if(!mysql_num_rows($resLevelPrices)) {
?>
		<tr>
			<td colspan="2">
					There are no payment options defined for this membership
			</td>
		</tr>
<?
	}

	while($arrPaymentOption = mysql_fetch_assoc($resLevelPrices)) {
?>
		<tr>
			<td>
				<?= $arrPaymentOption['Days'] > 0 ? $arrPaymentOption['Days'] : 'Life-Time Membership' ?>
			</td>
			<td>
				<?= getParam("currency_sign").$arrPaymentOption['Price'] ?>
			</td>
			<td class="deleteCell">
				<form id="formDeletePaymentOption<?= $arrPaymentOption['Days'] ?>" action="<?= $_SERVER['SCRIPT_NAME'] ?>?edit=pricing&edit_level=<?= $membershipID ?>" method="POST">
					<input type="hidden" name="delete_payment_option" value="yes" />
					<input type="hidden" name="delete_payment_days" value="<?= $arrPaymentOption['Days'] ?>" />
				</form>
				<a href="javascript:void(0)" onclick="document.forms['formDeletePaymentOption<?= $arrPaymentOption['Days'] ?>'].submit(); return false;">
					Delete
				</a>
			</td>
		</tr>
<?
	}
?>
	</table>

	<!--
	</div></div>

<div style="padding: 0"><img src="images/foot_block_green.gif" height="6" width="602"></div>
-->
<?
ContentBlockFoot();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

function addMembershipAction($membershipID, $actionID)
{
	$membershipID = (int)$membershipID;
	$actionID = (int)$actionID;

	$msgNotAdded = "\n<div class=\"actionFailure\">Error: membership action has not been added</div>";
	$msgAdded = "\n<div class=\"actionSuccess\"> Membership action has been added</div>";

	if($membershipID <= 0 || $actionID <= 0) return '';

	@mysql_query("INSERT INTO MemLevelActions
				  (IDLevel, IDAction, AllowedCount, AllowedPeriodLen, AllowedPeriodStart, AllowedPeriodEnd)
				  VALUES ($membershipID, $actionID, NULL, NULL, NULL, NULL)");

	if(mysql_affected_rows() > 0)
		return $msgAdded;
	else
		return $msgNotAdded;
}

function deleteMembershipAction($membershipID, $actionID)
{
	$membershipID = (int)$membershipID;
	$actionID = (int)$actionID;

	$msgNotDeleted = "\n<div class=\"actionFailure\">Error: membership action has not been deleted</div>";
	$msgDeleted = "\n<div class=\"actionSuccess\"> Membership action has been deleted</div>";

	if($membershipID <= 0 || $actionID <= 0) return '';

	@mysql_query("DELETE FROM MemLevelActions WHERE IDLevel = $membershipID AND IDAction = $actionID");

	if(mysql_affected_rows() > 0)
		return $msgDeleted;
	else
		return $msgNotDeleted;
}

function updateMembershipAction($membershipID, $actionID, $cnt, $periodLen, $periodStart, $periodEnd, $additionalParam='')
{
	$membershipID = (int)$membershipID;
	$actionID = (int)$actionID;

	$msgNotUpdated = "\n<div class=\"actionFailure\">Error: action has not been updated</div>";
	$msgUpdated = "\n<div class=\"actionSuccess\"> Action settings have been updated</div>";

	if($membershipID <= 0 || $actionID <= 0) return '';

	$cnt = (int)$cnt;
	$periodLen = (int)$periodLen;

	if($cnt <= 0) $cnt = 'NULL';
	if($periodLen <= 0) $periodLen = 'NULL';

	if($periodStart == '')
		$periodStart = 'NULL';
	else
		$periodStart = strtotime($periodStart);

	if($periodEnd == '')
		$periodEnd = 'NULL';
	else
		$periodEnd = strtotime($periodEnd);

	if($additionalParam == '') {
		$additionalParam = 'NULL';
	} else {
		if(!get_magic_quotes_gpc()) $additionalParam = addslashes($additionalParam);
		$additionalParam = "'".$additionalParam."'";
	}

	if($periodStart == -1 || $periodEnd == -1) return $msgNotUpdated;

	$periodStart = "FROM_UNIXTIME($periodStart)";
	$periodEnd = "FROM_UNIXTIME($periodEnd)";

	@mysql_query("
		UPDATE	MemLevelActions
		SET		AllowedCount = $cnt,
				AllowedPeriodLen = $periodLen,
				AllowedPeriodStart = $periodStart,
				AllowedPeriodEnd = $periodEnd,
				AdditionalParamValue = $additionalParam
		WHERE IDLevel = $membershipID AND IDAction = $actionID");

	if(mysql_affected_rows() > 0) {
		return $msgUpdated;
	}else {
		if(mysql_errno() == 0)
			return '';
		return $msgNotUpdated;
	}
}

function membershipActions($membershipID)
{
	global $IDNonMember;
	global $site;

	$membershipID = (int)$membershipID;

	if($membershipID <= 0) return '';
	ob_start();
ContentBlockHead("Membership Actions");
?>
<!-- <div class="sectionHeader">Membership Actions</div>
<div class="sectionBody"><div style="padding: 10px"> -->
<?
	$resActions = db_res("SELECT ID, Name FROM MemActions ORDER BY Name");

	$arrActions = array();

	while (list($ID, $desc) = mysql_fetch_row($resActions))
	{
		$arrActions[(int)$ID] = $desc;
	}

?>
	<div align="left">
		<?= $_POST['add_action'] ? addMembershipAction($membershipID, $_POST['add_action_id']) : '' ?>
		<?= $_POST['update_action'] ? updateMembershipAction($membershipID,
															 $_POST['update_action_id'],
															 $_POST['allowedCnt'],
															 $_POST['period'],
															 $_POST['dateStart'],
															 $_POST['dateEnd'],
															 $_POST['additionalParamValue']) : '';?>
		<?= $_POST['delete_action'] ? deleteMembershipAction($membershipID, $_POST['delete_action_id']) : '' ?>
	</div>
<?

	$resLevelActions = db_res("
		SELECT	IDAction,
				AllowedCount,
				AllowedPeriodLen,
				AllowedPeriodStart,
				AllowedPeriodEnd,
				AdditionalParamName,
				AdditionalParamValue
		FROM	MemLevelActions
				INNER JOIN MemActions
				ON MemLevelActions.IDAction = MemActions.ID
		WHERE MemLevelActions.IDLevel = $membershipID
		ORDER BY MemActions.Name");

	$arrLevelActions = array();

	while ($arr = mysql_fetch_assoc($resLevelActions))
	{
		$ID = $arr['IDAction'];
		unset($arr['IDAction']);
		$arrLevelActions[(int)$ID] = $arr;
	}

	$optionsText = "";
	foreach ($arrActions as $ID => $desc)
	{
		if( !$arrLevelActions[$ID] )
			$optionsText .= "\n<option value=\"$ID\">$desc</option>";
	}

	if ( strlen($optionsText) )
	{
?>
	<div align="right">
		<form action="<?= $_SERVER['SCRIPT_NAME'] ?>?edit=actions&edit_level=<?= $membershipID ?>" method="POST">
			<input type="hidden" name="add_action" value="yes" />
			<select name="add_action_id">
				<?= $optionsText ?>
			</select>
			<input type="submit" value="Add action" />
		</form>
	</div>
<?
	}

	foreach ($arrLevelActions as $ID => $arrAction) {
?>
	<div class="actionRowHeader">
		<table width="100%" style="height: 100%" cellpadding="0" cellspacing="0">
			<tr>
				<td valign="middle" align="left" width="100%"><b><?= $arrActions[$ID] ?></b></td>
				<td valign="middle" align="right" style="padding-right: 5px;">
					<a id="showHide<?= $ID ?>" href="" onclick="showHide('showHide<?= $ID ?>', 'actionRow<?= $ID ?>'); return false;">Show</a>
				</td>
				<td>
					<form action="<?= $_SERVER['SCRIPT_NAME'] ?>?edit=actions&edit_level=<?= $membershipID ?>" method="POST" style="padding:0; margin:0;">
						<input type="hidden" name="delete_action" value="yes" />
						<input type="hidden" name="delete_action_id" value="<?= $ID ?>" />
						<input style="width: 57px" type="submit" value="Delete" />
					</form>
				</td>
			</tr>
		</table>
	</div>
	<div class="actionRow" id="actionRow<?= $ID ?>">
		<form id="updateActionForm<?= $ID ?>" action="<?= $_SERVER['SCRIPT_NAME'] ?>?edit=actions&edit_level=<?= $membershipID ?>" method="POST" style="padding:0; margin:0">
		<input type="hidden" name="update_action" value="yes" />
		<input type="hidden" name="update_action_id" value="<?= $ID ?>" />
		<table class="actionForm">
			<tr>
				<td class="caption">Number of allowed actions:</td>
				<td class="allowedCnt">
					<input id="allowedCnt<?= $ID ?>" name="allowedCnt" value="<?= $arrAction['AllowedCount'] ?  $arrAction['AllowedCount'] : 'no limit' ?>" onfocus="clearUnlimited('allowedCnt<?= $ID ?>')" onblur="fillUnlimited('allowedCnt<?= $ID ?>')" <?= $membershipID == $IDNonMember ? 'disabled' : '' ?> />
				</td>
			</tr>
			<tr>
				<td class="caption">Number of actions is reset every:</td>
				<td class="period">
					<input id="period<?= $ID ?>" name="period" value="<?= $arrAction['AllowedPeriodLen'] ?  $arrAction['AllowedPeriodLen'] : 'no limit' ?>" onfocus="clearUnlimited('period<?= $ID ?>')" onblur="fillUnlimited('period<?= $ID ?>')" <?= $membershipID == $IDNonMember ? 'disabled' : '' ?> />
					&nbsp;hours
				</td>
			</tr>
			<tr>
				<td class="caption">This action is available since:</td>
				<td class="dateStart">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td>
								<input style="width: 130px" id="dateStart<?= $ID ?>" name="dateStart" value="<?= $arrAction['AllowedPeriodStart'] ?  $arrAction['AllowedPeriodStart'] : 'no limit' ?>" onfocus="clearUnlimited('dateStart<?= $ID ?>')" onblur="fillUnlimited('dateStart<?= $ID ?>')" />
							</td>
							<td>
								<input style="width: 70px" type="button" id="dateStart<?= $ID ?>trigger" value="Choose"/>
							</td>
							<td>
								<input style="width: 70px" type="button" onclick="document.getElementById('dateStart<?= $ID ?>').value = 'no limit'" value="Clear"/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="caption">This action is available until:</td>
				<td class="dateEnd">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td>
								<input style="width: 130px" id="dateEnd<?= $ID ?>" name="dateEnd" value="<?= $arrAction['AllowedPeriodEnd'] ?  $arrAction['AllowedPeriodEnd'] : 'no limit' ?>" onfocus="clearUnlimited('dateEnd<?= $ID ?>')" onblur="fillUnlimited('dateEnd<?= $ID ?>')" />
							</td>
							<td>
								<input style="width: 70px" type="button" id="dateEnd<?= $ID ?>trigger" value="Choose"/>
							</td>
							<td>
								<input style="width: 70px" type="button" onclick="document.getElementById('dateEnd<?= $ID ?>').value = 'no limit'" value="Clear"/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
<?
		if(!is_null($arrAction['AdditionalParamName'])) {
?>
			<tr>
				<td class="caption"><?= htmlspecialchars($arrAction['AdditionalParamName'])?></td>
				<td class="additionalParam">
					<input style="width: 130px" type="text" name="additionalParamValue" value="<?= htmlspecialchars($arrAction['AdditionalParamValue']) ?>" />
				</td>
			</tr>
<?
		}
?>
		<tr>
			<td colspan="2" align="center">
				<input style="width: 160px" type="submit" value="Save action settings" onclick="clearFormUnlimited('updateActionForm<?= $ID ?>')"/>
			</td>
		</tr>
		</table>
	</div>
<script type="text/javascript">
//<![CDATA[
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
        inputField        : "dateStart<?= $ID ?>",
        button            : "dateStart<?= $ID ?>trigger",
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
        inputField        : "dateEnd<?= $ID ?>",
        button            : "dateEnd<?= $ID ?>trigger",
        ifFormat          : "%Y-%m-%d %H:%M:%S",
        daFormat          : "%Y/%m/%d",
        align             : "Br"
      });
//]]>
</script>
	</form>
<?
	}
	if (!count($arrLevelActions)){
?>
		<div align="center">No actions allowed for this membership</div>
<?
	}
?>
<!--
</div></div>
<div style="padding: 0"><img src="images/foot_block_green.gif" height="6" width="602"></div>
-->
<?

ContentBlockFoot();
	$contents = ob_get_contents();
	ob_end_clean();
	return $contents;
}

TopCodeAdmin();
?>
<!-- Loading Calendar JavaScript files -->
    <script type="text/javascript" src="<?= $site['plugins'] ?>calendar/calendar_src/utils.js"></script>
    <script type="text/javascript" src="<?= $site['plugins'] ?>calendar/calendar_src/calendar.js"></script>
    <script type="text/javascript" src="<?= $site['plugins'] ?>calendar/calendar_src/calendar-setup.js"></script>

<!-- Loading language definition file -->
    <script type="text/javascript" src="<?= $site['plugins'] ?>calendar/calendar_lang/calendar-en.js"></script>
<script type="text/javascript">
function confirmAndSubmit(strFormName, strConfirmationMessage)
{
	var result = confirm(strConfirmationMessage);
	if(result == true) document.forms[strFormName].submit();
}

function clearUnlimited(textControlID)
{
	textControl = document.getElementById(textControlID);

	if(textControl.value == 'no limit')
	{
		textControl.value = '';
	}
}

function fillUnlimited(textControlID)
{
	textControl = document.getElementById(textControlID);

	if(textControl.value == '')
	{
		textControl.value = 'no limit';
	}
}

function clearFormUnlimited(strFormID)
{
	form = document.getElementById(strFormID);

	inputTags = form.getElementsByTagName('input');

	for(i=0; i < inputTags.length; i++)
	{
		if(inputTags[i].value == 'no limit')
			inputTags[i].value = '';
	}
}

function showHide(strLabelID, strDivID)
{
	div = document.getElementById(strDivID);
	label = document.getElementById(strLabelID);

	if(div.style.display == '')
		div.style.display = 'none';

	if(div.style.display == 'none'){
		div.style.display = 'block';
		label.innerHTML = 'Hide';
	}else{
		div.style.display = 'none';
		label.innerHTML = 'Show';
	}
}
</script>
<?

if(isset($_POST['saveMemSet']) && isset($_POST['category']))
{
	saveMemSettings();
}

echo ContentBlockHead("Membership settings");
echo displayOptions();
echo ContentBlockFoot();


echo ContentBlockHead("Manage Membership Types");
echo membershipList();
echo ContentBlockFoot();

switch ($_GET['edit'])
{
	case 'pricing': echo membershipPricing($_GET['edit_level']);
	break;

	case 'actions': echo membershipActions($_GET['edit_level']);
}
BottomCode();
?>