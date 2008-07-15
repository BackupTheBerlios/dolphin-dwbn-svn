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
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$logged['admin'] = member_auth( 1, true, true );

// this is dynamic page -  send headers to do not cache this page

send_headers_page_changed();

$title			= "";
$url			= "";
$description	= "";
$action			= "";

// get start & end dates

$start_date_default = "2005-01-01";
$end_date_default = "2010-01-01";

$start_date = $_POST['start_date'] ? $_POST['start_date'] : $start_date_default;
$start_date = strtotime($start_date);
$start_date = $start_date != -1 ? $start_date : strtotime($start_date_default);

$end_date = $_POST['end_date'] ? $_POST['end_date'] : $end_date_default;
$end_date = strtotime($end_date);
$end_date = $end_date != -1 ? $end_date : strtotime($end_date_default);
$end_date = $end_date < $start_date ? $start_date : $end_date;

$start_date = "FROM_UNIXTIME($start_date)";
$end_date = "FROM_UNIXTIME($end_date)";

$banner_pos = "";

if ( $_POST['pos_top']    == "on" ) $banner_pos .= '1';
if ( $_POST['pos_left']   == "on" ) $banner_pos .= '2';
if ( $_POST['pos_right']  == "on" ) $banner_pos .= '3';
if ( $_POST['pos_bottom'] == "on" ) $banner_pos .= '4';

$banner_pos = (int)$banner_pos;

if ( !$demo_mode && $_POST['action'] == 'modify' && $_POST['as_new'] != "on" )
{
    $banner_title = process_db_input( $_POST['Title'] );
    $banner_lhshift = (int)$_POST['lhshift'];
    $banner_lvshift = (int)$_POST['lvshift'];
    $banner_rhshift = (int)$_POST['rhshift'];
    $banner_rvshift = (int)$_POST['rvshift'];
    $banner_text = process_db_input( $_POST['Text'] );
    $banner_active = ( $_POST['Active'] == 'on' ? 1 : 0 );
    $banner_url = process_db_input( $_POST['Url'] );
	db_res( "UPDATE `Banners` SET `Title` = '$banner_title', `lhshift` = '$banner_lhshift', `lvshift` = '$banner_lvshift', `rhshift` = '$banner_rhshift', `rvshift` = '$banner_rvshift', `Position` = '$banner_pos' , `Text` = '$banner_text', `Active` = '$banner_active', Url = '$banner_url', campaign_start = $start_date, campaign_end = $end_date WHERE ID = {$_POST['banner_id']}" );
}

if ( !$demo_mode && $_POST['action'] == 'new' || $_POST['as_new'] == "on" && $_POST['action'] == 'modify' )
{
    $banner_title = process_db_input( $_POST['Title'] );
    $banner_lhshift = (int)$_POST['lhshift'];
    $banner_lvshift = (int)$_POST['lvshift'];
    $banner_rhshift = (int)$_POST['rhshift'];
    $banner_rvshift = (int)$_POST['rvshift'];
    $banner_text = process_db_input( $_POST['Text'] );
    $banner_active = ( $_POST['Active'] == 'on' ? 1 : 0 );
    $banner_url = process_db_input( $_POST['Url'] );
    db_res( "INSERT INTO `Banners` SET `Title` = '$banner_title', `lhshift` = '$banner_lhshift', `lvshift` = '$banner_lvshift', `rhshift` = '$banner_rhshift', `rvshift` = '$banner_rvshift', `Position` = '$banner_pos' , `Text` = '$banner_text', `Active` = '$banner_active', Url = '$banner_url', campaign_start = $start_date, campaign_end = $end_date, `Created` = NOW()" );
}

if ( !$demo_mode && (int)$_GET['banner_id'] && $_GET['action'] == "delete" )
{
    $banner_id = (int)$_GET['banner_id'];
    db_res( "DELETE FROM `Banners` WHERE ID = $banner_id" );
}

// Get banner info from database.
$q_str = "SELECT * FROM `Banners` ORDER BY `ID` DESC";

$banners_res = db_res( $q_str );

$_page['header'] = "Banners";
$_page['header_text'] = "Manage banners";

TopCodeAdmin();
ContentBlockHead("Preview:");

if (  $_GET['action'] == 'preview' && $_GET['banner_id'] )
{
	$bann_arr = db_arr( "SELECT * FROM `Banners` WHERE `ID` = {$_GET['banner_id']}" );
	echo "<br><table cellspacing=1 cellpadding=1 width=90% height=200 align=center style=\"border: 1px solid #cccccc\">\n";
	echo process_line_output( $bann_arr['Title'] );
	echo "<tr><td align=center bgcolor=white>". banner_put($bann_arr['ID'], 0) ."</td></tr>";
	echo "</table><br>\n";

}
ContentBlockFoot();
ContentBlockHead("Banners");

if ( !mysql_num_rows( $banners_res ) )
{
    echo "<center>No banners available.</center>";
}
else
{
	echo "<table cellspacing=1 cellpadding=2 border=0 class=small1 width=100%>\n";
    while ( $banns_arr = mysql_fetch_array( $banners_res ) )
    {
		$imp = db_arr("SELECT COUNT(*) FROM `BannersShows` WHERE `ID` = {$banns_arr['ID']}");
		$clicks = db_arr("SELECT COUNT(*) FROM `BannersClicks` WHERE `ID` = {$banns_arr['ID']}");

		if ( !$banns_arr['Active'] )
			$class = 'table_err';
		else
			$class = 'panel';
       	echo "<tr class={$class}><td><li>(<a href=\"banners.php?action=preview&banner_id={$banns_arr['ID']}\">Preview</a> | ";
       	echo "<a href=\"banners.php?banner_id={$banns_arr['ID']}\">Modify</a> | ";
       	echo "<a href=\"banners.php?banner_id={$banns_arr['ID']}&action=delete\">Delete</a>)&nbsp;";
       	echo process_line_output( $banns_arr['Title'] );
       	echo "</a></td>\n";
		echo "<td><b>{$clicks[0]}</b> clicks </td>\n";
		echo "<td><b>{$imp[0]}</b> impressions </td>\n";
       	echo "</tr>\n";

		if ( $banns_arr['ID'] == $_GET['banner_id'] && !strlen($_GET['action']) )
		{
			$action	= "modify";

			$Title	= $banns_arr['Title'];
			$Url	= $banns_arr['Url'];
			$Text	= $banns_arr['Text'];
			$Active = $banns_arr['Active'];
			$Position = $banns_arr['Position'];

			$lhshift = $banns_arr['lhshift'];
			$lvshift = $banns_arr['lvshift'];
			$rhshift = $banns_arr['rhshift'];
			$rvshift = $banns_arr['rvshift'];

			$start_date = $banns_arr['campaign_start'];
			$end_date = $banns_arr['campaign_end'];
       	}
    }
	echo "</table>\n";
}
echo '<br><br>';
if ( !$action )
{
    $action = "new";
	$Title = "";
	$Url = "";
	$Active = "";
	$Text = "";
	$Position = "";

	$start_date = '';
	$end_date = '';
}

ContentBlockFoot();
ContentBlockHead("Manage banners");
?>

<form action="banners.php" method="POST">
<center>
<table cellspacing=2 cellpadding=2 class=text border="0">
	<tr class=table>
		<td align="left">&nbsp;Banner Title&nbsp;</td>
		<td align="left"><input class=no type=text size=40 name=Title value="<?= htmlspecialchars($Title) ?>"></td>
	</tr>
	<tr class=table>
		<td align="left">&nbsp;Banner Url&nbsp;</td>
		<td align="left"><input class=no type=text size=40 name=Url value="<?= htmlspecialchars($Url) ?>"></td>
	</tr>
	<tr class=table>
		<td  align="left">&nbsp;Banner Active&nbsp;</td>
		<td align="left"><input type=checkbox name=Active <? if ($Active) echo "checked"; ?>></td>
	</tr>
	<tr class=table>
		<td valign=top>&nbsp;*Banner Text (HTML Only)&nbsp;</td>
		<td><textarea cols=40 rows=10 name=Text><?= htmlspecialchars($Text) ?></textarea></td>
	</tr>

<!-- Campaign start date -->
	<tr class="table">
		<td>&nbsp;Start Date&nbsp;</td>
		<td  align="left">
			<input id="dateStartText" type="text" name="start_date" size="19" maxlength="19" class="no" value="<?= $start_date?>" />
			<input id="dateStartChoose" type="button" value="Choose" />
			<input type="button" value="Clear" onclick="javascript: document.getElementById('dateStartText').value='';" />
			<br />default: <?=$start_date_default?>
		</td>
	</tr>
<!-- Campaign end date -->
	<tr class="table">
		<td>&nbsp;End Date&nbsp;</td>
		<td  align="left">
			<input id="dateEndText" type="text" name="end_date" size="19" maxlength="19" class="no" value="<?= $end_date?>" />
			<input id="dateEndChoose" type="button" value="Choose" />
			<input type="button" value="Clear" onclick="javascript: document.getElementById('dateEndText').value='';" />
			<br />default: <?=$end_date_default?>
		</td>
	</tr>
	<tr>

		<td>&nbsp;Position on the Page</td>
		<td>
			<table border=0 width=100% cellspacing=10 cellpading=20>
				<tr>
					<td colspan=5 align=center><input type=checkbox name=pos_top <?php if (substr_count($Position,"1") > 0 ) echo "checked";?>>Top</td>
				</tr>
				<tr>
					<td colspan=2 align=center><input type=checkbox name=pos_left <?php if (substr_count($Position,"2") > 0 ) echo "checked";?>>Left</td>
					<td>&nbsp;</td>
					<td colspan=2 align=center><input type=checkbox name=pos_right <?php if (substr_count($Position,"3") > 0 ) echo "checked";?>>Right</td>
				</tr>
				<tr>
					<td>HShift</td>
					<td>VShift</td>
					<td>&nbsp;</td>
					<td>HShift</td>
					<td>VShift</td>
				</tr>
				<tr>
					<td><input name=lhshift type=input size=5 value=<?php if (substr_count($Position,"2") > 0 ) echo $lhshift;?>></td>
					<td><input name=lvshift type=input size=5 value=<?php if (substr_count($Position,"2") > 0 ) echo $lvshift;?>></td>
					<td>&nbsp;</td>
					<td><input name=rhshift type=input size=5 value=<?php if (substr_count($Position,"3") > 0 ) echo $rhshift;?>></td>
					<td><input name=rvshift type=input size=5 value=<?php if (substr_count($Position,"3") > 0 ) echo $rvshift;?>></td>
				</tr>
				<tr>
					<td colspan=5 align=center><input type=checkbox name=pos_bottom <?php if (substr_count($Position,"4")> 0 ) echo "checked";?>>Bottom</td>
				</tr>

			</table>
		</td>
	</tr>
<?
if ( $action == "modify" )
	echo '
	<tr class=table>
		<td>&nbsp;Insert as new&nbsp;</td>
		<td><input type=checkbox name=as_new></td>
	</tr>
		';
?>
	<tr>
		<td colspan=2 align=center><input class=text type=submit value="Submit"></td>
	</tr>
</table>

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
        inputField        : "dateStartText",
        button            : "dateStartChoose",
        ifFormat          : "%Y-%m-%d",
        daFormat          : "%Y/%m/%d",
        align             : "Br"
      });
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
        inputField        : "dateEndText",
        button            : "dateEndChoose",
        ifFormat          : "%Y-%m-%d",
        daFormat          : "%Y/%m/%d",
        align             : "Br"
      });
//]]>
</script>
<input type=hidden name=action value="<? echo $action; ?>">
<input type=hidden name=banner_id value="<? echo $_GET['banner_id']; ?>">
</center>
</form><br><br>

<?
ContentBlockFoot();

/*
ContentBlockHead("&nbsp;");

?>

				For more information on how to place banners on your pages please read our "How to..."<br>
				<a href="http://www.aewebworks.com/login/howto.php">www.aewebworks.com/login/howto.php</a>
				<hr align="center" color="#666666" size="1" width="90%">
				* You should write the path to banner image only in this area.<br>
				For exmple: &lt;img src="http://www.some_domain/some_path/some_banner"&gt;

<?
ContentBlockFoot();
*/
BottomCode();
?>