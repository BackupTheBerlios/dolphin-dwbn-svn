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

// --------------- page variables and login

$_page['name_index']	= 44;

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );


if ( $_GET['explain'] != 'imadd' )
{
	$_page['header'] = _t( "_EXPLANATION_H" ).": "._t("_".$_GET['explain']);
	$_page['header_text'] = _t( "_EXPLANATION_H" ).": "._t("_".$_GET['explain']);
}
else
{
	$_page['header'] = _t("_User was added to im");
	$_page['header_text'] = _t("_User was added to im");
}



// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['body_onload'] = 'javascript: void(0)';
$_page_cont[$_ni]['page_main_code'] = DesignBoxContent($_page['header_text'], PageMainCode(), $oTemplConfig -> PageExplanation_db_num);

// --------------- [END] page components

PageCode();

// --------------- page components functions

function im_up()
{

 ob_start();
?>

 <table align="center" cellspacing=4 cellpadding=4 class=small width=90% border=0>
 <tr><td>

 Your current membership status doesn't allow You to use this feature.
 Please <a href="<?=$site[url]?>membership.php" target="_blank">click here</a> to upgrade your membership.
 </td></tr>
 </table>

<?php

 $ret = ob_get_contents();
 ob_end_clean();

 return $ret;

}

function membershipActionsList($membershipID)
{
	$resLevelActions = db_res("
		SELECT	IDAction,
				Name,
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

          ob_start();
?>
<!-- [START] List Membership Actions -->

<style type="text/css">
table.allowedActionsTable{
	border-bottom:1px solid;
	border-right:1px solid;
}
table.allowedActionsTable td{
	padding: 5px;
	text-align: center;
	border-top:1px solid;
	border-left:1px solid;
}
</style>
<table cellpadding="0" cellspacing="0" border="0" style="font-size: 8pt" class="allowedActionsTable" align="center" width="100%">
<tr>
		<td colspan="5" align="center"><?= _t("_Allowed actions") ?></td>
</tr>
<tr>
		<td><b><?= _t("_Action") ?></b></td>
		<td><b><?= _t("_Times allowed") ?></b></td>
		<td><b><?= _t("_Period (hours)") ?></b></td>
		<td><b><?= _t("_Allowed Since") ?></b></td>
		<td><b><?= _t("_Allowed Until") ?></b></td>
</tr>
<?
	if(mysql_num_rows($resLevelActions) <= 0)
	{
?>
<tr>
		<td colspan="5"><?= _t("_No actions allowed for this membership") ?></td>
</tr>
<?
	}
	while($membershipAction = mysql_fetch_assoc($resLevelActions))
	{
?>
<tr>
		<td style="text-align: left;"><b><?= _t("_".$membershipAction['Name']) ?></b></td>
		<td><?= $membershipAction['AllowedCount'] ? $membershipAction['AllowedCount'] : _t("_no limit") ?></td>
		<td><?= $membershipAction['AllowedPeriodLen'] ? $membershipAction['AllowedPeriodLen'] : _t("_no limit") ?></td>
		<td><?= $membershipAction['AllowedPeriodStart'] ? $membershipAction['AllowedPeriodStart'] : _t("_no limit") ?></td>
		<td><?= $membershipAction['AllowedPeriodEnd'] ? $membershipAction['AllowedPeriodEnd'] : _t("_no limit") ?></td>
</tr>
<?
	}
?>
</table>

<?
	$result = ob_get_contents();
         ob_end_clean();

	return $result;
}

/**
 * Prints HTML Code for explanation
 */
function PageMainCode()
{
	global $site;
	global $oTemplConfig;

	$b = "<table width=".($oTemplConfig -> expl_db_w-5)." class=text cellspacing=0 cellpadding=0><td width=5><img src={$site['images']}spacer.gif alt=\"\" width=5></td><td width=".($oTemplConfig -> expl_db_w-15)."><div width=".($oTemplConfig -> expl_db_w-15)." align=justify>";
	$a = "</div></td><td width=5><img src={$site['images']}spacer.gif  alt=\"\" width=5></td></table>";
	switch ( $_GET['explain'] )
	{
		case 'Unconfirmed': return $b._t("_ATT_UNCONFIRMED_E").$a;
		case 'Approval': return $b._t("_ATT_APPROVAL_E").$a;
		case 'Active': return $b._t("_ATT_ACTIVE_E").$a;
		case 'Rejected': return $b._t("_ATT_REJECTED_E").$a;
		case 'Suspended': return $b._t("_ATT_SUSPENDED_E", $site['title']).$a;
		case 'membership': return membershipActionsList((int)$_GET['type']);
		case 'im_up': return im_up();
		case 'imadd':
?>
    <script type="text/javascript">
    <!--
    var amount = 8
    function pause()
    {
        myID = setTimeout( "close_win()", amount*1000 )
    }
    function close_win()
    {
        window.close()
    }
	pause();
    //-->
    </script>
<?php
			$ID = (int)$_GET['ID'];
			db_res("INSERT INTO IMessages SET IDFrom = ".(int)$_COOKIE['memberID'].", IDTo = $ID, `When`=NOW(), Msg=''");
			return $b._t("_User was added to im").$a;
	}
	return "";
}

?>