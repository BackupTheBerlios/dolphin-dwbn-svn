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
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$logged['admin'] = member_auth( 1 );
$ADMIN = $logged['admin'];

if( $_POST['action'] == 'banner_upload' && strlen($_FILES['banner_upload']['name']) )
{
	$new_filename = "{$dir['banners']}{$_FILES['banner_upload']['name']}";

	$scan = getimagesize( $_FILES['banner_upload']['tmp_name'] );

	if ( $scan && ( 1 == $scan[2] || 2 == $scan[2] || 3 == $scan[2] || 4 == $scan[2] || 6 == $scan[2] )
		&& move_uploaded_file( $_FILES['banner_upload']['tmp_name'], $new_filename ) )
	{
		$f = fopen ($new_filename, "r");
		if (!$f)
		{
			$status_text = "Cannot open file for read";
		}
		else
		{
			$fsize = filesize ($new_filename);
			$buffer = fread ($f, $fsize);
			fclose ($f);
			chmod( $new_filename, 0644 );

			$i_arr = getimagesize( $new_filename );
			$filename = process_db_input($_FILES['banner_upload']['name']);

			$result = db_res("INSERT INTO aff_banners (Banner, XSize, YSize) VALUES ( '$filename', '$i_arr[0]', '$i_arr[1]' ) ");
			$status_text = "File {$_FILES['banner_upload']['name']} successfully uploaded.";
		}
	}
	else
	{
		$status_text = "Error: file wasn't uploaded";
	}
}

// - delete (uncheck) banner ----------------
if ( !$demo_mode && $_POST['prf_form_submit'] == "Delete" )
{
	$i = 0;
	while( list( $key, $val ) = each( $_POST ) )
	{
		if ( (int)$key && $val == "on" )
		{
			$res = db_res("DELETE FROM aff_banners WHERE `ID` = '$key'");
			if (!$res)
			{
				$err = 1;
				break;
			}
			++$i;
		}
	}

	if ( $err )
		$status_text = "Banner was NOT successfully deleted";
	else
		$status_text = "$i banner(s) was(were) successfully deleted.";
}

// - edit banner  ------------------
if ( !$demo_mode && (int)$_GET['EditBanner'] )
{
	$editban_arr = db_arr( "SELECT * FROM aff_banners WHERE ID = '". (int)$_GET['EditBanner']. "'" );
}

if ( $_POST['EditBannerSubmit'] )
{
	$xsize = (int)$_POST['XSize'];
	$ysize = (int)$_POST['YSize'];
	$banner_name = process_db_input( $_POST['BannerName'] );
	$status = $_POST['Status'] == 'active' ? 'Active' : 'Approval';
	$text = process_db_input( $_POST['Text'] );
	$banner_id = (int)$_POST['ID'];
	$query = "UPDATE aff_banners SET `XSize` = $xsize, `YSize` = $ysize, `BannerName` = '$banner_name', `Status` = '$status', `Text` = '$text' WHERE `ID` = $banner_id";
	$res = db_res($query);

	if ( $res )
		$status_text = "Banner was successfully updated";
	else
		$status_text = "Banner was NOT successfully updated.";
}



$page = (int)$_GET['page'];
$p_per_page = (int)$_GET['p_per_page'];

if ( !$page )
	$page = 1;

if ( !$p_per_page )
	$p_per_page = 30;

// ------------------------------

$p_num = db_arr( "SELECT COUNT(*) FROM aff_banners WHERE Added='1'" );
$p_num = $p_num[0];
$pages_num = ceil( $p_num / $p_per_page );

$real_first_p = (int)($page - 1) * $p_per_page;
$page_first_p = $real_first_p + 1;

$result = db_res( "SELECT ID, XSize, YSize, Banner, BannerName, Status, Text, Added, COUNT(ID) AS m_count FROM aff_banners GROUP BY ID $part_addon ORDER BY ID ASC LIMIT $real_first_p, $p_per_page" );
$page_p_num = mysql_num_rows( $result );

$_page['header'] = "Banner List";
$_page['header_text'] = "Banner List";

TopCodeAdmin();
ContentBlockHead("Upload new banner");

if ( strlen($status_text) )
	echo "<br><center><div class=\"err\">{$status_text}</div></center><br>";

?>
<form enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" >
<table class="text" align="center" cellpadding="0" cellspacing="2" width="450">
	<tr>
		<td align="left" colspan="2">Upload New Banner</td>
	</tr>
	<tr>
		<td align="left">
			<input type="hidden" name="action" value="banner_upload">
			<input name="banner_upload" type="file" size="60">
		</td>
		<td align="left">
			<input type="submit" value="Upload">
		</td>
	</tr>
</table>
</form>

<?
ContentBlockFoot();
ContentBlockHead("Modify existing banners");
?>

<center>
<? echo ResNavigationRet( 'AffBannersUpper', 0 ); ?>
</center>

<form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" name="prf_form">
<table align="center" cellspacing=2 cellpadding=0 class=text width=90% background="<?= $site['url_admin']; ?>images/dot_bg2.gif">
<?php
$respd = db_res("SELECT * FROM `aff_banners` ORDER BY ID DESC");
$num_rows = mysql_num_rows($respd);

if ( $num_rows == 0 )
{
?>
	<tr>
		<td align="center">No banners available</td>
	</tr>
<?
}
else
{
	while( $arrpd = mysql_fetch_array($respd) )
	{
?>
	<tr class="panel">
		<td align="center">&nbsp;ID&nbsp;</td>
		<td align="center">&nbsp;XSize&nbsp;</td>
		<td align="center">&nbsp;YSize&nbsp;</td>
		<td align="center">&nbsp;Banner&nbsp;</td>
		<td align="center">&nbsp;Banner Name&nbsp;</td>
		<td align="center">&nbsp;Status&nbsp;</td>
		<td align="center">&nbsp;Delete&nbsp;</td>
	</tr>
	<tr class="table">
		<td align="center" bgcolor="#ffffff">
			<a href="<?= $site['url'] ?>aff/aff_banners.php?EditBanner=<?= $arrpd['ID'] ?>"><?= $arrpd['ID'] ?></a>
		</td>
		<td align="center" bgcolor="#ffffff"><?php echo $arrpd['XSize']; ?></td>
		<td align="center" bgcolor="#ffffff"><?php echo $arrpd['YSize']; ?></td>
		<td align="center" bgcolor="#ffffff"><?php echo $arrpd['Banner']; ?></td>
		<td align="center" bgcolor="#ffffff"><?php echo $arrpd['BannerName']; ?></td>
		<td align="center" bgcolor="#ffffff"><?php echo $arrpd['Status'] == "Active" ? "Active" : "Approval"; ?></td>
		<td align="center" bgcolor="#ffffff"><input name="<?php echo $arrpd['ID'];?>" type="checkbox"></td>
	</tr>
	<tr class="table">
		<td colspan="7" align="center">
<?php
		if( $arrpd['XSize'] > 400 )
		{
?>
			<a href="<?= $site['banners'] . $arrpd['Banner'] ?>">Large Banner</a>
<?
		}
		else
		{
?>
			<img align=middle src="<?= $site['banners'] . $arrpd['Banner'] ?>">
<?
		}
?>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="7">
			<a href="<?= $site['url'] ?>"><?= $arrpd['Text'] ?></a>
		</td>
	</tr>
<?
	}
}
?>
</table>

<?
if ( $num_rows > 0 )
{
?>
<center>
<table class=text cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>&nbsp;<a href="javascript: void(0);" onclick="setCheckboxes( 'prf_form', true ); return false;">Check all</a> / <a href="javascript: void(0);" onclick="setCheckboxes( 'prf_form', false ); return false;">Uncheck all</a>&nbsp;</td>
		<td>Selected Banners:&nbsp;</td>
		<td><input class=no type=submit name="prf_form_submit" value="Delete"></td>
	</tr>
</table>
</center>
<?
}
?>
</form>

<center>
<? echo ResNavigationRet( 'AffBannersLower', 0 ); ?>
</center>
<?

ContentBlockFoot();

if ( (int)$_GET['EditBanner'] )
{
	ContentBlockHead("Edit Banner");
?>
<form method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
<table align="center" cellspacing=1 cellpadding=4 class="text" width=90%>
	<tr class=panel>
		<td align=center width=10%>&nbsp;ID&nbsp;</td>
		<td align=center width=10%>&nbsp;XSize&nbsp;</td>
		<td align=center width=10%>&nbsp;YSize&nbsp;</td>
		<td align=center width=50%>&nbsp;Banner file&nbsp;</td>
		<td align=center width=20%>&nbsp;Status&nbsp;</td>
	</tr>
	<tr>
		<td align=center width=10%><?=$editban_arr['ID']?></td>
		<td align=center width=10%><input type="text" class=no size=4 name=XSize value=<?=$editban_arr['XSize']?>></td>
		<td align=center width=10%><input type="text" class=no size=4 name=YSize value=<?=$editban_arr['YSize']?>></td>
		<td align=center width=50%><input type="text" class=no size=40 name=Banner value="<?=$editban_arr['Banner']?>" readonly></td>
		<td align=center width=20%>
			<select class=no name="Status">
				<option value="approval" <?= $editban_arr['Status'] == 'Approval' ? 'selected' : '' ?>>Approval</option>
				<option value="active" <?= $editban_arr['Status'] == 'Active' ? 'selected' : ''?>>Active</option>
			</select>
		</td>
	</tr>
	<tr>
		<td align=center width=10%>Banner name&nbsp;</td>
		<td align=center colspan=4 width=10%><input type="text" class=no size=73 name=BannerName value=<?=$editban_arr['BannerName']?>></td>
	</tr>
	<tr>
		<td colspan=5 align=center width=10%>&nbsp;
<?
	if( $editban_arr['XSize'] > 400 )
	{
?>
			<a href="<?= $site['banners'] . $editban_arr['Banner'] ?>">Large Banner</a>
<?
	}
	else
	{
?>
			<img align=middle src="<?= $site['banners'] . $editban_arr['Banner'] ?>" />
<?
	}
?>
		</td>
	</tr>
	<tr>
		<td align=center width=10%>Text</td>
		<td align=center colspan=4 width=10%><input type="text" class=no size=73 name=Text value=<?=$editban_arr['Text']?>></td>
	</tr>
</table>

<br>
<center><input type="submit" name="EditBannerSubmit" value="Update"></center>
<input type=hidden name=ID value=<?=$editban_arr['ID']?>>
</form>
<?php
	ContentBlockFoot();
}
BottomCode();
?>