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
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'tags.inc.php' );

$logged['admin'] = member_auth( 1, true, true );
$ADMIN = $logged[admin];

$_page['css_name'] = 'profiles.css';


if ( $_POST['prf_form_submit'] && !$demo_mode)
{
    $sel_str = "";
    while( list( $key, $val ) = each( $_POST ) )
        if ( (int)$key && $val )
	    $sel_str .= ",$key";
    $sel_str = substr( $sel_str, 1 );
    $sel_arr = explode( ",", $sel_str );

    $owner = $PARTNER ? $_COOKIE['partnerID'] : 0;
    while( list( $key, $val ) = each( $sel_arr ) )
    {
        switch ( $_POST['prf_form_submit'] )
        {
		    case "Delete":
		    	profile_delete( $val );
		    break;
		    case "Confirm Email":
		    	activation_mail( $val, 0 );
		    break;
		    case "Send Message":
		    	profile_send_message( $val, $_POST['Message'] );
		    break;
		    case 'Activate':
		    	db_res( "UPDATE `Profiles` SET `Status` = 'Active' WHERE `ID` = '" . (int)$val . "'" );
				createUserDataFile((int)$val);
				reparseObjTags( 'profile', (int)$val );
		    break;
		    case 'Approval':
		    	db_res( "UPDATE `Profiles` SET `Status` = 'Approval' WHERE `ID` = '" . (int)$val . "'" );
				createUserDataFile((int)$val);
				reparseObjTags( 'profile', (int)$val );
			break;
		    case 'Ban':
				if ((int)$val>0 && (int)$_REQUEST['time_hrs']>0) {
					$iBanTime = ((int)$_REQUEST['time_hrs']) * 3600;
					$sCheckBanSQL = "SELECT * FROM `AdminBanList` WHERE `ProfID`='{$val}' LIMIT 1";
					db_res($sCheckBanSQL);
					if (mysql_affected_rows()==1) {
						$sBamSQL = "UPDATE `AdminBanList` SET `Time`='{$iBanTime}', `DateTime`=NOW() WHERE `ProfID`='{$val}'";
					} else {
						$sBamSQL = "INSERT INTO `AdminBanList` SET `Time`='{$iBanTime}', `DateTime`=NOW(), `ProfID`='{$val}'";
					}
					db_res($sBamSQL);
				}
			break;
		    case 'UnBan':
				if ((int)$val>0) {
					$sCheckBanSQL = "DELETE FROM `AdminBanList` WHERE `ProfID`='{$val}' LIMIT 1";
					db_res($sCheckBanSQL);
				}
			break;
		}
    }
}

// - GET variables --------------

$page		    = (int)$_GET['page'];
$p_per_page		= (int)$_GET['p_per_page'];
$profiles  		= $_GET['profiles'];
$sorttype		= $_COOKIE['sorttype'];
$sortor  		= $_GET['sortor'] ? $_GET['sortor'] : $_COOKIE['sortor'];
$sex		    = $_GET['sex'];
$search			= $_GET['search'];
$showAffMembers = (int)$_GET['showAffMembers'];

if ( !$page )
    $page = 1;

if ( !$p_per_page )
    $p_per_page = 30;

if ( $showAffMembers > 0 )
{
	$aff_part_w = " AND idAff = $showAffMembers AND idProfile = ID";
	$aff_part_f = ",aff_members ";
}

switch( $profiles )
{
	case 'Active':
		$prof_part = "AND `Status` = '$profiles'";
		break;
	case 'Unconfirmed':
	case 'Approval':
	case 'Rejected':
	case 'Suspended':
		$prof_part = "AND `Status` <> 'Active'";
		break;
	default:
		$prof_part = "";
}

if ( strlen($sex) )
	$sex_part = "AND Sex = '" . process_db_input($sex, 1) . "'";


if (strlen($search))
{
    if ($_GET['s_mail'])
		$email_part = " AND `Email` LIKE '%$search%' ";
    elseif ($_GET['s_nickname'])
		$email_part = " AND `NickName` LIKE '%$search%' ";
    elseif ($_GET[s_id])
        $email_part = " AND `ID` = '$search' ";

}

if (isset($_GET['media']) && isset($_GET['status']))
{
	$sType       = htmlspecialchars_adv($_GET['media']);
	$sStatus      = htmlspecialchars_adv($_GET['status']);
	$sqlJoinPart = "LEFT JOIN `media` ON (`media`.`med_prof_id` = `Profiles`.`ID`)";
	$sqlWhere    = " AND `med_status` = '$sStatus' AND `med_type`='$sType'";
	$sqlGroup    = " GROUP BY `Profiles`.`ID`";
}


$sqlCouple = "(`Couple`=0 OR `Couple`>`Profiles`.`ID`)";
$sqlWhere .= ' AND ' . $sqlCouple;

// ------------------------------

$sQuery = "SELECT `Profiles`.`ID` as `ID`, 
				  `NickName`,
				  `Email`,
				  `Sex`,
				   DATE_FORMAT(`DateLastLogin`,  '$date_format' ) AS `DateLastLoginCur`,
				   DATE_FORMAT(`DateReg`,  '$date_format' ) AS `DateReg`,
				   `Status`
				   $sMemField
				   FROM `Profiles` $aff_part_f
   				   $sqlJoinPart
				   $sMemJoin
				   WHERE 1 $email_part $aff_part_w $prof_part $sex_part $sqlWhere $sqlGroup";

$rData = db_res($sQuery);
$p_num = mysql_num_rows($rData);
$pages_num = ceil( $p_num / $p_per_page );

$real_first_p = (int)($page - 1) * $p_per_page;
$page_first_p = $real_first_p + 1;

/* checking for incoming value for sort order
 * if we open this page without sortor param
 * we use ID as default
 * after that we put old value to cookie
 * $sortoder - stands for value for ORDER BY query
*/

if ( $sortor == "" )
{
    $sortor = "DateLastLogin";
}
setcookie( "sortor", $sortor );

/* here we make check for the first click on the value
 * in case we change our incoming value we set order type to ASCENDING
 * in other case we check for additional clicks and change the image
 * $sorttype - ASCENDING or DESCENDING
*/
if ( $_GET['sortor'] && ($_GET['sortor'] != $_COOKIE['sortor']) )
{
    $sorttype = "DESC";
}
elseif ( $_GET['sortor'] )
{
    if ( $_COOKIE['sorttype'] == "ASC" )
    {
   		$sorttype = "DESC";
   		$sortor_image = "<img src=\"./images/desc_order.gif\">";
    }
    else
    {
    	$sorttype = "ASC";
    	$sortor_image = "<img src=\"./images/asc_order.gif\">";
    }
}
setcookie( "sorttype", $sorttype );

/* the check for member status
 * in case we have the full member list without any statuses we set it to nothing
 * in other case we add another option to the query
 * $inc_profiles - variable returning the incoming parameter for profile status
*/
if ( $profiles != "" )
{
	$inc_profiles = "profiles=$profiles&";
}
else
{
	$inc_profiles = "";
}
$n_arr = db_arr( 'SELECT COUNT(*) FROM `Profiles`' . " WHERE {$sqlCouple}" );
$status_arr[0] = "Active";
$status_arr[1] = "Approval";
$status_arr[2] = "Unconfirmed";
$status_arr[3] = "Rejected";
$status_arr[4] = "Suspended";

if (getParam("free_mode") != 'on') {
	$sMemField = ", `IDLevel`, IF(ISNULL(`MemLevels`.`Name`),'', `MemLevels`.`Name`) AS `MemName`";
	$sMemJoin  = "LEFT JOIN `ProfileMemLevels` ON `ProfileMemLevels`.`IDLevel`=`Profiles`.`ID` 
				  LEFT JOIN `MemLevels` ON `ProfileMemLevels`.`IDLevel` = `MemLevels`.`ID` ";
	$sMemLevelShow = 'style="display:block;"';
}
else
	$sMemLevelShow = 'style="display:none;"';


$sQuery = "SELECT `Profiles`.`ID` as `ID`, 
				  `NickName`,
				  `Email`,
				  `Sex`,
				   DATE_FORMAT(`DateLastLogin`,  '$date_format' ) AS `DateLastLoginCur`,
				   DATE_FORMAT(`DateReg`,  '$date_format' ) AS `DateReg`,
				   `Status`
				   $sMemField
				   FROM `Profiles` $aff_part_f
   				   $sqlJoinPart
				   $sMemJoin
				   WHERE 1 $email_part $aff_part_w $prof_part $sex_part $sqlWhere $sqlGroup ORDER BY $sortor $sorttype LIMIT $real_first_p, $p_per_page;";

$result = db_res($sQuery);

$_page['header'] = "Members' Profiles";
$_page['header_text'] = "Members profiles sorted by modification date";
$_page['js'] = 1;
TopCodeAdmin();


ContentBlockHead("Total registered members");
?>
								<center><table cellspacing="1" cellpadding="2" border="0" width="70%" align="center" bgcolor="#cccccc" >
									<tr>
										<td bgcolor="#E5E5E5" class="text" align="left"><a href="<?php echo $site['url_admin']; ?>profiles.php">Total registered members:</a></td>
										<td bgcolor="#E5E5E5" width="50" class="text" align="right"><b><?php echo $n_arr[0]; ?></b></td>
									</tr>
<?php
$i = 0;
$iK = 1;

$sActEmColor = " #FFFFFF";

while( list( $key, $val ) = each( $status_arr ) )
{
	if ( $val == 'Active' )
	{
		$sAdd = " `Status` = '$val' AND {$sqlCouple}";
		$sCapt = $val;
	}
	else
	{
		if ( $iK <= 1 )
		{
			$sAdd =  " `Status` <> 'Active' AND {$sqlCouple}";
			$iK++;
			$sCapt = 'Inactive';
		}
		else
		{
			continue;
		}
	}
	
	$n_arr = db_arr( "SELECT COUNT(*) FROM `Profiles` WHERE $sAdd" );

	if ( $n_arr[0])
    {
?>
									<tr>
										<td class="text"  bgcolor="#ffffff" align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;<img src=images/arrow.gif>
											<a href="profiles.php?profiles=<? echo $val; ?>"><? echo $sCapt; ?></a>
										</td>
										<td class="prof_stat_<? echo $val; ?>" width="50" align="right"><? echo $n_arr[0]; ?></td>
									</tr>
<?
    }
    if ( getParam("autoApproval_ifNoConfEmail") != 'on' && $val != 'Active' )
    {
    	$sActEmColor = ' #66CC66';
    	$n_arr = db_arr( "SELECT COUNT(*) FROM `Profiles` WHERE `Status`='Unconfirmed'" );

		if ( $n_arr[0])
	    {
	?>
										<tr>
											<td class="text"  bgcolor="#ffffff" align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;<img src=images/arrow.gif>
												<a href="profiles.php?profiles=Unconfirmed">With unconfirmed emails</a>
											</td>
											<td class="prof_stat_<? echo $val; ?>" width="50" align="right"><? echo $n_arr[0]; ?></td>
										</tr>
	<?
	    }
    }
}
$aMedia = array('photo');
foreach ($aMedia as $iK=>$sVal)
{
	$sqlUnp = "SELECT * FROM `media` WHERE `med_status` = 'passive' AND `med_type`= '$sVal' GROUP BY `med_prof_id`";
	$rUnp = db_res($sqlUnp);
	
	if ($rUnp && mysql_num_rows($rUnp))
	{
		?>
		<tr>
			<td class="text"  bgcolor="#ffffff" align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;<img src=images/arrow.gif>
				<a href="profiles.php?media=photo&status=passive"><? echo 'With unapproved '.$sVal; ?></a>
			</td>
			<td class="text" width="50" align="right"><? echo mysql_num_rows($rUnp); ?></td>
		</tr>
		<?
	}
}

?>
								</table></center>

<?
ContentBlockFoot();
ContentBlockHead("Search profile");
?>
	<table cellspacing=0 cellpadding=0 border="0" align="right" class="text"><tr><td><a href="<? echo $site[url]; ?>join.php">Add a new profile</a></td></tr></table><br>

<form method="get" action="profiles.php">
<table align="center" width="100%" cellspacing=2 cellpadding=2  border=0>
<tr>
    <td align=center colspan="3"> <input class=text name='search' size=50> </td>
	</tr>
	<tr>
	<td align="right"> <input name='s_nickname' type=submit value="Search by Nickname"> </td>
    <td align="center"> <input name='s_mail' type=submit value="Search by Email"> </td>
    <td align=left> <input name='s_id' type=submit value="Search by ID"> </td>
</tr>
</table>
</form>
<?
ContentBlockFoot();
ContentBlockHead("Profiles");
?>


<center>
<?  echo ResNavigationRet( 'ProfilesUpper', 0 ); ?>
</center>
<form action="profiles.php<? $get_vars = get_vars(); echo substr($get_vars, 0, strlen($get_vars) - 1); ?>" method=post name="prf_form">
<table align="center" width=590 cellspacing=1 cellpadding=0 class=small1 border=0 bgcolor="#EEEEEE">
<?
if ( !$p_num )
    echo "<td class=panel>No profiles available</td>";
else
{
?>
<tr class=panel>
	<td>&nbsp;</td>
	<td align=center>&nbsp;<a href="profiles.php?<? echo "$inc_profiles"?>sortor=ID&p_per_page=<? echo "$p_per_page"?>">ID</a>&nbsp;<? if ( $sortor=="ID" ) echo "$sortor_image" ?></td>
	
	<td align=center>&nbsp;<a href="profiles.php?<? echo "$inc_profiles"?>sortor=NickName&p_per_page=<? echo "$p_per_page"?>">NickName</a>&nbsp;<? if ( $sortor =="NickName" ) echo "$sortor_image" ?></td>
	
	<td align=center>&nbsp;E-mail&nbsp;</td>
	
	<td align=center>Registered</td>
	
	<td align=center>&nbsp;<a href="profiles.php?<? echo "$inc_profiles"?>sortor=DateLastLogin&p_per_page=<? echo "$p_per_page"?>">Last Visited</a>&nbsp;<? if ( $sortor=="DateLastLogin" ) echo "$sortor_image" ?></td>
	
	<td align=center>Photos</td>
	
	<td align=center>Audio</td>
	
	<td align=center>BGs</td>
	
	<td align=center <?=$sMemLevelShow?>style="display:none;">Membership</td>
</tr>
<?
    
	while ( $p_arr = mysql_fetch_array( $result ) )
    {
    	$col = "table";
		$sEmail = $p_arr['Status'] == 'Unconfirmed' ? '<span style="background-color: #FF6666;">'.$p_arr['Email'].'</span>' : '<span style="background-color:'.$sActEmColor.';">'.$p_arr['Email'].'</span>';
		$sBannedColor = (isLoggedBanned($p_arr['ID'])==true) ? '#F99' : '#fff';
?>
<tr class=<?=$col?> bgcolor="<?= $sBannedColor ?>">
	<td align=center><input type=checkbox name="<? echo $p_arr[ID]?>"></td>
	
	<td>&nbsp;<a href="../pedit.php?ID=<? echo $p_arr[ID]; ?>"><? echo $p_arr[ID]; ?></a>&nbsp;</td>
	
	<td>&nbsp;<?=$p_arr['NickName']?>&nbsp;</td>
	
	<td>&nbsp;<?=$sEmail?>&nbsp;</td>
	
	<td align="center"><?=$p_arr['DateReg']?></td>
	
	<td align="center"><?=$p_arr['DateLastLoginCur']?></td>
	
	<td><?=getUserMedia($p_arr['ID'], 'photo')?></td>
	
	<td align="center"><?=getUserMedia($p_arr['ID'], 'audio')?></td>
	
	<td align="center"><?=getUserMedia($p_arr['ID'], 'bg')?></td>
	<td align="center" <?=$sMemLevelShow?>><? if (strlen($p_arr['MemName']) > 0) {echo $p_arr['MemName'];} else echo 'Standard';?></td>

</tr>
<?
    }
}
?>
</table>

<table class=text border=0 width=590 align=center>
<tr>
	<td>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="60">&nbsp;<a href="javascript: void(0);" onclick="setCheckboxes( 'prf_form', true ); return false;">Check all</a></td>
				<td align="left" width="140"> <a href="javascript: void(0);" onclick="setCheckboxes( 'prf_form', false ); return false;">Uncheck all</a>&nbsp;</td>
				<td width="90" align="center"><input class=text type=submit name="prf_form_submit" value="Delete"></td>
			    <td width="90" align="center"><input class=text type=submit name="prf_form_submit" value="Confirm Email"></td>
			    <td width="90" align="center"><input class=text type=submit name="prf_form_submit" value="Activate"></td>
			    <td width="90" align="center"><input class=text type=submit name="prf_form_submit" value="Approval"></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td align="center">Ban time<br />(in hours)<input class=text type=text name="time_hrs" value="1" style="width:70px;"></td>
				<td align="center"><input class=text type=submit name="prf_form_submit" value="Ban"></td>
				<td align="center"><input class=text type=submit name="prf_form_submit" value="UnBan"></td>
			</tr>
		</table>
	</td>
</tr>
<tr>

    <td  align="center"><hr style="width:90%; color:#e4e4e4; height:1px;"><textarea name="Message" style="width:540px; height:100px;"></textarea></td>
</tr>
<tr>
    <td  align="center"><input class=text type=submit name="prf_form_submit" value="Send Message"></td>
</tr>
</table>
</form>
<center>
<?
echo ResNavigationRet( 'ProfilesLower', 0 );
?>
</center>
<?
ContentBlockFoot();
ContentBlockFoot();
BottomCode();

function getUserMedia($ID, $sType = '')
{
	switch ($sType)
	{
		case 'video': 
			break;
		case 'audio':
			$sAct 	 = " AND `Owner`='$ID'";
			$sPas 	 = " AND `Owner`='$ID'";
			$sqlAct  = "SELECT COUNT(*) as `Num` FROM `RayMp3Files` WHERE 1 $sAct";
			//$sqlPas  = "SELECT COUNT(*) as `Num` FROM `RayMp3Files` WHERE 1 $sPas";
			$sqlPas  = "SELECT COUNT(*) as `Num` FROM `RayMp3Files` WHERE 0";
			$sHrefA  = 'post_mod_audio.php?iUser='.$ID;
			$sHrefP  = 'post_mod_audio.php?iUser='.$ID;
			break;
		case 'photo':
			$sAct    = " AND `med_status`='active' AND `med_prof_id`='$ID'";
			$sPas    = " AND `med_status`='passive' AND `med_prof_id`='$ID'";
			$sqlAct  = "SELECT COUNT(*) as `Num` FROM `media` WHERE `med_type`='$sType' $sAct";
			$sqlPas  = "SELECT COUNT(*) as `Num` FROM `media` WHERE `med_type`='$sType' $sPas";
			$sHrefA  = 'post_mod_photos.php?media=photo&status=active&iUser='.$ID;
			$sHrefP  = 'post_mod_photos.php?media=photo&status=passive&iUser='.$ID;
			break;
			
		case 'bg':
			$sqlAct  = "SELECT COUNT(*) as `Num` FROM `ProfilesSettings` WHERE `IDmember`='$ID' AND `Status`='Active' AND `BackgroundFilename`<>''";
			$sqlPas  = "SELECT COUNT(*) as `Num` FROM `ProfilesSettings` WHERE `IDmember`='$ID' AND (`Status` IS NULL OR `Status`<>'Active') AND `BackgroundFilename`<>''";
			$sHrefA  = 'post_mod_profiles.php?iUser='.$ID.'&status=active';
			$sHrefP  = 'post_mod_profiles.php?iUser='.$ID;
			break;
	}
	$sActPart = db_value($sqlAct) > 0 ? '<div class="activeMedia"><a href="'.$sHrefA.'">'.db_value($sqlAct).'</a></div>' : '';
	$sPasPart = strlen($sqlPas) && db_value($sqlPas) > 0 ? '<div class="passiveMedia"><a href="'.$sHrefP.'">'.db_value($sqlPas).'</a></div>' : '';
	
	return $sActPart.$sPasPart;
}

?>