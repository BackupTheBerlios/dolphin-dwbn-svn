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

$logged['admin'] = member_auth( 1 );
$ADMIN = $logged['admin'];

/*-----------------------member      as      affiliates----------------------------*/

        if ( !$demo_mode && $_POST['mem_form_submit'] == "Delete")
        {
                $i = 0;
                while( list( $key, $val ) = each( $_POST ) )
                {
                        if ( (int)$key && $val == "on" )
                        {
                                $res = db_res("DELETE FROM members_as_aff WHERE ID='$key'");
                                if (!$res)
                                {
                                        $err = 1;
                                        break;
                                }
                                ++$i;
                        }
                }

        if ($err)
            $status_text = "Membership rule was NOT successfully deleted.";
        else
            $status_text = "$i membership rules was successfully deleted.";

        }

    if ( !$demo_mode && $_POST['AddMem'] )
    {
		$maa_num_of_mem = process_db_input( $_POST['num_of_mem'] );
		$maa_num_of_days = process_db_input( $_POST['num_of_days'] );
		$maa_mid = process_db_input( $_POST['MID'] );
    	$res = db_res("INSERT INTO members_as_aff (`num_of_mem`, `num_of_days`, `MID`) VALUES ('$maa_num_of_mem', '$maa_num_of_days', '$maa_mid')");
		if (!$res)
		{
			$err = 1;
			break;
		}

		if ($err)
			$status_text = "Membership rule was NOT successfully added.";
		else
			$status_text = "Membership rule was successfully added.";

    }


    if ( !$demo_mode && (int)$_GET['editmem'] )
        {
        	$iEditmem = (int)$_GET['editmem'];
        	$editmem_arr = db_arr( "SELECT * FROM members_as_aff WHERE ID='$iEditmem';" );
        }

        if ( $_POST['EditMemS'] )
        {
        	    $maa_num_of_mem = process_db_input( $_POST['num_of_mem'] );
		        $maa_num_of_days = process_db_input( $_POST['num_of_days'] );
		        $maa_mid = process_db_input( $_POST['MID'] );
                $res = db_res("UPDATE members_as_aff SET `num_of_mem`='$maa_num_of_mem',`num_of_days`='$maa_num_of_days',`MID`='$maa_mid' WHERE ID = '$maa_mid'");
                if ( $res )
                        $status_text = "Membership rule was successfully updated.";
                else
                        $status_text = "Membership rule was NOT successfully updated.";
        }


/*-----------------------member      as      affiliates----------------------------*/


// - delete transaction ----------------
	if ( !$demo_mode && $_POST[prf_form_submit] == "Delete")
	{
		$i = 0;
		while( list( $key, $val ) = each( $_POST ) )
		{
			if ( (int)$key && $val == "on" )
			{
				$res = db_res("DELETE FROM aff WHERE ID='$key'");
				if (!$res)
				{
					$err = 1;
					break;
				}
				++$i;
			}
		}

		if ($err)
            $status_text = "Partners was NOT successfully deleteed.";
        else
            $status_text = "$i partners was successfully deleted.";


	}


// - add transaction  ------------------

    if ( !$demo_mode && $_POST[AddDiscount] )
    {
		$aff_name = process_db_input( $_POST['Name'] );
		$aff_email = process_db_input( $_POST['email'] );
		$aff_password = process_db_input( $_POST['Password'] );
		$aff_percent = process_db_input( $_POST['Percent'] );
		$aff_status = process_db_input( $_POST['Status'] );
    	$res = db_res("INSERT INTO `aff` (`Name`,`email`,`Password`,`Percent`,`Status`) VALUES ('$aff_name', '$aff_email', md5( '$aff_password' ), '$aff_percent', '$aff_status')");
		if (!$res)
		{
			$err = 1;
			break;
		}

		if ($err)
		    $status_text = "Partner was NOT successfully added.";
		else
		    $status_text = "Partner was successfully added.";

    }

// - edit transaction  ------------------

    if ( !$demo_mode && (int)$_GET[editdis] )
	{
    	$editdis_arr = db_arr( "SELECT `ID`, `Name`, `email`, `Password`, `Percent`, `seed`, DATE_FORMAT(`RegDate`, '$date_format' ) AS RegDate, `Status`, `www1`, `www2` FROM aff WHERE ID='".$_GET[editdis]."'" );
	}

	if ( $_POST[EditDiscount] )
	{
		if( !strlen( $_POST['Password'] ) )
			$sPassword = "";
		else
			$sPassword = "`Password`=md5( '$_POST[Password]' ),";
		$res = db_res("UPDATE aff SET `Name`='$_POST[Name]',`email`='$_POST[email]'," . $sPassword . "`Percent`='$_POST[Percent]',`Status`='$_POST[Status]' WHERE ID=$_POST[ID]");
		if ( $res )
			$status_text = "Partner was successfully updated.";
		else
			$status_text = "Partner was NOT successfully updated.";
	}

// - GET variables --------------

$page	    = (int)$_GET['page'];
$p_per_page = (int)$_GET['p_per_page'];
$profiles   = (int)$_GET['profiles'];
$sex	    = (int)$_GET['sex'];
$featured   = $_GET['featured'];

if ( !$page )
    $page = 1;

if ( !$p_per_page )
    $p_per_page = 30;

// ------------------------------

$p_num = db_arr( "SELECT COUNT(*) FROM aff" );
$p_num = $p_num[0];
$pages_num = ceil( $p_num / $p_per_page );

$real_first_p = (int)($page - 1) * $p_per_page;
$page_first_p = $real_first_p + 1;

$result = db_res( "SELECT ID, Name, email, Password, Percent, www1, www2, Status, DATE_FORMAT(`RegDate`, '$date_format' ) AS RegDate, COUNT(idProfile) AS m_count FROM aff LEFT JOIN aff_members ON (ID=idAff) GROUP BY ID $part_addon ORDER BY ID ASC LIMIT $real_first_p, $p_per_page" );
$page_p_num = mysql_num_rows( $result );

$memberships_arr = getMemberships();

$_page['header'] = "Partners List";
$_page['header_text'] = "Partners List";

TopCodeAdmin();

?>

<SCRIPT language="JavaScript">
function setCheckboxess(the_form, do_check)
{
    var elts      = document.forms[the_form].elements;
    var elts_cnt  = elts.length;

    for (var i = 0; i < elts_cnt; i++) {
        elts[i].checked = do_check;
    } // end for

    return true;
} // end of the 'setCheckboxes()' function
</SCRIPT>

<?
if ( strlen($status_text) )
	echo "<br><center><div class=\"err\">$status_text</div></center><br>";
if (getParam("enable_aff") != 'on')
{
	$sHead = "(<span style=\"color:red;\">Affiliate system was disabled</span>)";
}
ContentBlockHead("Manage Affiliates ".$sHead)
?>
<center>
<? echo ResNavigationRet( 'PartnersAffUpper', 0 ); ?>
</center>
<form action="<?=$_SERVER[PHP_SELF]?>" method=post name="prf_form">
<table align="center" cellspacing=2 cellpadding=0 class=small1 width=90% background="<?php global $site; echo $site['url_admin']; ?>images/dot_bg2.gif">
<?

if ( !$p_num )
    echo "<td class=panel>No partners </td>";
else
{
?>
<tr class=panel>
<td align=center >&nbsp;ID&nbsp;</td>
<td align=center >&nbsp;Name&nbsp;</td>
<td align=center >&nbsp;E-Mail&nbsp;</td>

<td align=center >&nbsp;Percent&nbsp;</td>
<td align=center >&nbsp;Memb&nbsp;</td>
<td align=center >&nbsp;Status&nbsp;</td>
<td align=center >&nbsp;Fin&nbsp;</td>
<td align=center>&nbsp;&nbsp;</td>
</tr>
<?
    while ( $p_arr = mysql_fetch_array( $result ) )
    {
?>
<tr class=table>

<td align=center bgcolor="#ffffff">&nbsp;<a href="partners.php?editdis=<?=$p_arr[ID]?>"><?=$p_arr[ID]?>&nbsp</td>
<td align=center bgcolor="#ffffff"><?= process_line_output($p_arr['Name']) ?></td>
<td align=center bgcolor="#ffffff"><?= process_line_output($p_arr['email']) ?></td>

<td align=center bgcolor="#ffffff"><? echo sprintf( "%.2f", $p_arr['Percent'] ); ?></td>
<td align=center bgcolor="#ffffff">
<?php if ( $p_arr[m_count] > 0 ) { ?>
<a href="<?php echo $site['url_admin']."profiles.php?showAffMembers=$p_arr[ID]"; ?>"><?=$p_arr[m_count]?></a>
<?php } else {
	echo "-";
} ?>
</td>
<td align=center bgcolor="#ffffff"><?=$p_arr[Status]?></td>
<td align=center bgcolor="#ffffff"><a href=finance.php?affID=<?php echo $p_arr[ID]; ?>>fin</a></td>
<td align=center bgcolor="#ffffff"><input type=checkbox name="<? echo $p_arr[ID]?>"></td>
</tr>
<?
    }
}
?>
</table>

<center>
<table class=text>
<tr>

	<td>&nbsp;<a href="javascript: void(0);" onclick="setCheckboxess( 'prf_form', true ); return false;">Check all</a> / <a href="javascript: void(0);" onclick="setCheckboxess( 'prf_form', false ); return false;">Uncheck all</a>&nbsp;</td>
    <td>Selected partners:</td>
    <td><input class=no type=submit onclick="return confirm( 'Are you sure?' );" name="prf_form_submit" value="Delete"></td>
</tr>
</table>
</form>
</center>


<center>
<? echo ResNavigationRet( 'PartnersAffLower', 0 ); ?>
</center>




<? if ( (int)$_GET['editdis'] )
{ ?>
<form method=post action=<?=$_SERVER[PHP_SELF]?>>
<table align="center" cellspacing=1 cellpadding=2 class=small width=90%>
<tr class=panel>
<td align=center width=10%>&nbsp;ID&nbsp;</td>
<td align=center width=30%>&nbsp;Name&nbsp;</td>
<td align=center width=30%>&nbsp;E-Mail&nbsp;</td>
<td align=center width=10%>&nbsp;Change Password&nbsp;</td>
<td align=center width=10%>&nbsp;Percent&nbsp;</td>
<td align=center width=10%>&nbsp;Status&nbsp;</td>
</tr>
<tr class=panel>
<input type=hidden name=ID value=<?=$editdis_arr[ID]?>>
<td align=center width=10%><?=$editdis_arr[ID]?></td>
<td align=center width=30%><input class=no size=10 name=Name value=<?= htmlspecialchars($editdis_arr['Name']) ?>></td>
<td align=center width=30%><input class=no size=10 name=email value=<?= htmlspecialchars($editdis_arr['email']) ?>></td>
<td align=center width=10%><input class=no size=8 name=Password value=""></td>
<td align=center width=10%><input class=no size=5 name=Percent value=<?= htmlspecialchars($editdis_arr['Percent']) ?>></td>
<td align=center width=10%>

<select class=no name=Status>
    <option value=approval  <?=$editdis_arr[Status] == 'approval'  ? 'selected' : '' ?>>approval</option>
    <option value=active    <?=$editdis_arr[Status] == 'active'    ? 'selected' : ''?>>active</option>
    <option value=suspended <?=$editdis_arr[Status] == 'suspended' ? 'selected' : ''?>>suspended</option>
</select>

</td>
</tr>

<tr class=panel>
<td align=center>Details</td>
<td align=center><a href="http://<?=process_line_output($editdis_arr[www1]) ?>">Link to Site 1</a></td>
<td align=center><a href="http://<?=process_line_output($editdis_arr[www2]) ?>">Link to Site 2</a></td>
<td align=center></td>
<td align=center></td>
<td align=center></td>
</tr>


</table>
<br>
<center><input class=no type=submit name=EditDiscount value=Update></center>
</form>
<?
} else {
?>

<form method=post action=<?=$_SERVER[PHP_SELF]?>>
<table align="center" cellspacing=1 cellpadding=2 class=small width=90%>
<tr class=panel>
<td align=center width=28%>&nbsp;Name&nbsp;</td>
<td align=center width=28%>&nbsp;E-Mail&nbsp;</td>
<td align=center width=24%>&nbsp;Password&nbsp;</td>
<td align=center width=10%>&nbsp;Percent&nbsp;</td>
<td align=center width=10%>&nbsp;Status&nbsp;</td>

</tr>
<tr class=panel>
<td align=center width=28%><input class=text size=15 name=Name></td>
<td align=center width=28%><input class=text size=15 name=email></td>
<td align=center width=15%><input class=test size=8 name=Password></td>
<td align=center width=10%><input class=text size=5 name=Percent></td>
<td align=center width=10%>

<select class=no name=Status>
	<option value=approval>approval</option>
	<option value=active>active</option>
	<option value=suspended>suspended</option>
</select>

</td>
</tr>
</table>
<br>
<center><input class=no type=submit name=AddDiscount value=Add></center>
</form>

<?
}
ContentBlockFoot();
?>

<?php

     $p_num = db_arr( "SELECT COUNT(*) FROM members_as_aff" );
     $p_num = $p_num[0];
     $pages_num = ceil( $p_num / $p_per_page );

     $real_first_p = (int)($page - 1) * $p_per_page;
     $page_first_p = $real_first_p + 1;

     $result = db_res( "SELECT * FROM members_as_aff ORDER BY ID ASC LIMIT $real_first_p, $p_per_page" );
     $page_p_num = mysql_num_rows( $result );

ContentBlockHead("Members as Affiliates");
?>
<center>
<? echo ResNavigationRet( 'PartnersMemUpper', 0 ); ?>
</center>
<form action="<?=$_SERVER[PHP_SELF]?>" method=post name="mem_form">
<table align="center" cellspacing=2 cellpadding=0 class=small1 width=90% background="<?php global $site; echo $site['url_admin']; ?>images/dot_bg2.gif">
<?

if ( !$p_num )
    echo "<td class=panel>No members as affiliates</td>";
else
{
?>
<tr class=panel>
<td align=center >&nbsp;ID&nbsp;</td>
<td align=center >&nbsp;Num of members&nbsp;</td>
<td align=center >&nbsp;Num of days&nbsp;</td>
<td align=center >&nbsp;Type of membership&nbsp;</td>
<td align=center>&nbsp;&nbsp;</td>
</tr>
<?
    while ( $p_arr = mysql_fetch_array( $result ) )
    {
?>
<tr class=table>

<td align=center bgcolor="#ffffff">&nbsp;<a href="partners.php?editmem=<?=$p_arr['ID']?>"><?=$p_arr['ID']?>&nbsp</td>
<td align=center bgcolor="#ffffff"><?= $p_arr['num_of_mem'] ?></td>
<td align=center bgcolor="#ffffff"><?= $p_arr['num_of_days'] ?></td>
<td align=center bgcolor="#ffffff"><?php
		$membership_info = getMembershipInfo($p_arr['MID']);
		echo $membership_info['Name'];
?>
</td>
<td align=center bgcolor="#ffffff"><input type=checkbox name="<? echo $p_arr[ID];?>"></td>
</tr>
<?
    }
}
?>
</table>

<center>
<table class=text>
<tr>

        <td>&nbsp;<a href="javascript: void(0);" onclick="setCheckboxess( 'mem_form', true ); return false;">Check all</a> / <a href="javascript: void(0);" onclick="setCheckboxess( 'mem_form', false ); return false;">Uncheck all</a>&nbsp;</td>
    <td>Selected partners:</td>
    <td><input class=no type="submit" onclick="return confirm( 'Are you sure?' );" name="mem_form_submit" value="Delete"></td>
	</tr>
</table>
</form>
</center>


<center>
<? echo ResNavigationRet( 'PartnersMemLower', 0 ); ?>
</center>




<? if ( (int)$_GET['editmem'] )
{ ?>
<form method=post action=<?=$_SERVER['PHP_SELF']?>>
<table align="center" cellspacing=1 cellpadding=2 class=small width=90%>
<tr class=panel>
<td align=center width=10%>&nbsp;ID&nbsp;</td>
<td align=center width=30%>&nbsp;num_of_mem&nbsp;</td>
<td align=center width=30%>&nbsp;num_of_days&nbsp;</td>
<td align=center width=10%>&nbsp;Membership Type&nbsp;</td>
</tr>
<tr class=panel>
<input type=hidden name=ID value=<?=$editmem_arr[ID]?>>
<td align=center width=10%><?=$editmem_arr[ID]?></td>
<td align=center width=30%><input class=no size=10 name=num_of_mem value=<?=$editmem_arr['num_of_mem']?>></td>
<td align=center width=30%><input class=no size=10 name=num_of_days value=<?=$editmem_arr['num_of_days']?>></td>
<td align=center width=10%>
<select class=no name=MID>
<?php
		foreach ( $memberships_arr as $membershipID => $membershipName )
		{
			$out = "<option value=\"". $membershipID . "\" " . ( $membershipID == $editmem_arr['MID'] ? "selected" : "" ) .">";
			$out .= $membershipName ."</option>\n";
			echo $out;
		}
?>
</select>

</td>

</tr>


</table>
<br>
<center><input class=no type=submit name=EditMemS value=Update></center>
</form>
<?
} else {
?>

<form method=post action=<?= $_SERVER['PHP_SELF'] ?>>
<table align="center" cellspacing=1 cellpadding=2 class=small width=90%>
<tr class=panel>
<td align=center width=28%>&nbsp;Number of members&nbsp;</td>
<td align=center width=28%>&nbsp;Number of days&nbsp;</td>
<td align=center width=24%>&nbsp;Membership Type&nbsp;</td>

</tr>
<tr class=panel>
<td align=center width=28%><input class=text size=15 name=num_of_mem></td>
<td align=center width=28%><input class=text size=15 name=num_of_days></td>
<td align=center width=10%>
<select class=no name=MID>
<?php
		foreach ( $memberships_arr as $membershipID => $membershipName )
		{
			echo "<option value=\"$membershipID\">$membershipName</option>\n";
		}
?>
</select>

</td>
</tr>
</table>
<br>
<center><input class=no type=submit name=AddMem value=Add></center>
</form>

<?
}
?>

<table align="center" cellspacing=1 cellpadding=2 class="small1" width=90%>

<tr>

    <td align=center>Member</td>
    <td align=center>Number of members engaged</td>
    <td align=center>Current membership status</td>

</tr>

<?php
     $profaff_res = db_res ( "SELECT `ID`, `NickName`, `aff_num` FROM `Profiles` WHERE `aff_num` > 0 ORDER BY `ID`" );

     while (  $profaff_arr = mysql_fetch_array ( $profaff_res ) )
     {
?>
              <tr>

              <td align=center><a href="<?= getProfileLink($profaff_res['ID'])?>"><?= $profaff_arr['NickName'] ?></a></td>
              <td align=center><?= $profaff_arr['aff_num'] ?></td>
              <td align=center>
                  <?php
                       $membership_info = getMemberMembershipInfo($profaff_arr['ID']);
                       echo $membership_info['Name'];
                  ?>
              </td>

              </tr>
<?php
     }
?>


</table>
<?
ContentBlockFoot();
ContentBlockHead("AFF Approved banners");

?>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method=post name="prf_form1">
<table align="center" cellspacing=2 cellpadding=0 class=small1 width=90% background="<?php global $site; echo $site['url_admin']; ?>images/dot_bg2.gif">
<center>
<a href="aff_banners.php">Edit Banners</a>
<br><hr size=1 width=100% NOSHADE><br>

<table class="small1" align="center" cellpadding="0" cellspacing="2" width="90%">
<tbody>

<?php

     $respd = db_res("SELECT * FROM aff_banners WHERE Added='1' AND Status='Active' ORDER BY ID DESC");

     while( $arrpd = mysql_fetch_array($respd) )
     {
       ?>

         <tr><td align=center>
         <?php echo $arrpd[BannerName].' '.$arrpd[XSize].'x'.$arrpd[YSize];?>
         </td></tr>
         <tr class="table">
         <td colspan="7" align="center">

         <?php
         if($arrpd[XSize]>400)
         {
         ?>
             <a href="<?php echo $site['banners'] . $arrpd[Banner];?>"><h2>Large Banner</h2></a>
         <?php
         }
         else
         {
         ?>
             <img align=middle src="<?php echo $site['banners'] . $arrpd[Banner];?>"><br>
         <?php
         }
         ?>

        <img align=middle src="<?php echo $site['banners'] . $arrpd[Banner];?>"><br>

         </td>
         </tr>
         <tr><td align=center><a href="<?php echo $site['url']; ?>"><?php echo $arrpd[Text]; ?></a></td></tr>
       <?php
     }


?>


</tbody>


</center>

<?

?>
</table>
</form>
<?
ContentBlockFoot();
BottomCode();
?>