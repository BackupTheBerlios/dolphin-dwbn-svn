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

$navigationStep = 12; // count of objects to show per page

$_page['header'] = "Gallery PostModeration";
$_page['header_text'] = "Unapproved gallery objects";
$_page['css_name'] = 'post_mod_gallery.css';

if ( isset($_POST['confirm']) )
{
	$objectIDs = '';
	foreach ($_POST as $key => $value)
	{
		if ( strpos($key, 'check') !== false && $value == 'on' )
		{
			$objectID = (int) substr( $key, 5 );
			$objectIDs .= strlen($objectIDs) ? ",{$objectID}" : $objectID;
		}
	}
	if ( strlen($objectIDs) )
	{
		$query = "UPDATE `GalleryObjects` SET `Approved` = 1 WHERE `ID` IN ({$objectIDs})";
		//echo $query;
		db_res( $query );
	}
}
elseif ( isset($_POST['delete']) )
{
	foreach ($_POST as $key => $value)
	{
		if ( strpos($key, 'check') !== false && $value == 'on' )
		{
			$objectID = (int) substr( $key, 5 );
			$objectArr = db_arr( "SELECT `IDAlbum`, `Filename`, `ThumbFilename`, `OrderInAlbum` FROM `GalleryObjects` WHERE `ID` = {$objectID}" );
			db_res( "DELETE FROM `GalleryObjects` WHERE `ID` = {$objectID}" );
			db_res( "UPDATE `GalleryObjects`
						SET `OrderInAlbum` = `OrderInAlbum` - 1
						WHERE `IDAlbum` = {$objectArr['IDAlbum']}
							AND `OrderInAlbum` > {$objectArr['OrderInAlbum']}" );
			@unlink( "{$dir['gallery']}{$objectArr['Filename']}" );
			if ( strlen($objectArr['ThumbFilename']) && file_exists("{$dir['gallery']}{$objectArr['ThumbFilename']}") )
				@unlink( "{$dir['gallery']}{$objectArr['ThumbFilename']}" );
		}
	}
}

$page = isset($_REQUEST['page']) ? ((int)$_REQUEST['page'] > 0 ? (int)$_REQUEST['page'] : 1) : 1;
$p_per_page = isset($_REQUEST['p_per_page']) ? ((int)$_REQUEST['p_per_page'] > 0 ? (int)$_REQUEST['p_per_page'] : 10) : 10;

$totalQuery = "SELECT COUNT(*) FROM `GalleryObjects` WHERE `GalleryObjects`.`Approved` = 0";
$totalArr = db_arr( $totalQuery );
$totalObjects = $totalArr[0];

$p_num = $totalObjects;
$pages_num = ceil( $p_num / $p_per_page );
$real_first_p = (int)($page - 1) * $p_per_page;
$page_first_p = $real_first_p + 1;

$approveQuery = "
		SELECT
				`GalleryObjects`.`ID`,
				`GalleryObjects`.`Filename`,
				`GalleryObjects`.`ThumbFilename`,
				`GalleryObjects`.`ObjectType`,
				`GalleryObjects`.`Comment`,
				LEFT(`GalleryObjects`.`Comment`, 20) AS `CommentCut`,
				DATE_FORMAT(`GalleryObjects`.`Modified`,  '$date_format' ) AS 'Modified',
				`GalleryAlbums`.`IDMember`
		FROM
				`GalleryObjects`
		LEFT JOIN `GalleryAlbums` ON `GalleryObjects`.`IDAlbum` = `GalleryAlbums`.`ID`
		WHERE `GalleryObjects`.`Approved` = 0
		LIMIT {$real_first_p}, {$p_per_page};";
$approveRes = db_res( $approveQuery );

TopCodeAdmin();

ContentBlockHead("Unapproved gallery objects");

?>
<script type="text/javascript" language="JavaScript">
<!--
	function viewObject(owner, objectID)
	{
		var winParams = 'toolbar=no,resizable=yes,scrollbars=yes,width=400,height=300';
		var objectWin = window.open('<?= "{$site['url']}gallery.php?owner=' + owner + '&action=view_object&object_id=" ?>' + objectID, 'galleryObject', winParams);
	}

	function navigationSubmit(fromParam)
	{
		location.href = '<?= $_SERVER['PHP_SELF'] ?>?from=' + fromParam;
	}
-->
</script>
<?

if ( mysql_num_rows($approveRes) == 0 )
{
	echo '<center>No objects to approve</center>';
}
else
{
	echo '<center>'. ResNavigationRet( 'ObjectsUpper', 0 ) .'</center>';

?>
	<div class="galleryObjectsContainer">
	<form name="approveGalleryForm" action="<? $getVars = get_vars(); $getVars = substr($getVars, 0, strlen($getVars) - 1); echo "{$_SERVER['PHP_SELF']}{$getVars}" ?>" method="post">
<?

	while ( $objectArr = mysql_fetch_assoc($approveRes) )
	{
		$objectIcon = ( $objectArr['ObjectType'] == 'photo' && strlen($objectArr['ThumbFilename']) && file_exists("{$dir['gallery']}{$objectArr['ThumbFilename']}") ) ? "{$site['gallery']}{$objectArr['ThumbFilename']}" : "{$site['url_admin']}images/{$objectArr['ObjectType']}.jpg";
		$objectTitle = process_line_output($objectArr['CommentCut']);
		if ( strlen($objectArr['Comment']) > strlen($objectArr['CommentCut']) )
			$objectTitle .= '...';
		$objectTitle = "<a href=\"javascript:void(null);\" onclick=\"javascript: viewObject({$objectArr['IDMember']}, {$objectArr['ID']}); return false;\">{$objectTitle}</a>";
?>
	<div class="galleryObjectBlock">
		<div class="galleryObjectInfo"><?= $objectTitle ?></div>
		<div class="galleryObjectIcon" title="<?= process_line_output($objectArr['Comment']) ?>" style="background: url('<?= $objectIcon ?>') no-repeat center;" onclick="javascript: viewObject(<?= "{$objectArr['IDMember']}, {$objectArr['ID']}" ?>);"></div>
		<div class="galleryObjectInfo"><?= $objectArr['Modified'] ?></div>
		<div class="galleryObjectInfo">
			<input type="checkbox" name="check<?= $objectArr['ID'] ?>" id="check<?= $objectArr['ID'] ?>" class="no" />
		</div>
	</div>
<?
	}

	echo '<div class="clearBoth"></div>';

?>
<div class="galleryControls">
	<div style="position: relative; float: left;">
		<a href="javascript:void(0);" onclick="setCheckboxes('approveGalleryForm', true); return false;">Check all</a>
		&nbsp;/&nbsp;
		<a href="javascript:void(0);" onclick="setCheckboxes('approveGalleryForm', false); return false;">Uncheck all</a>
	</div>
	<div style="position: relative; float: right">
		Selected objects:&nbsp;<input type="submit" name="confirm" value="Confirm" />&nbsp;|&nbsp;<input type="submit" name="delete" value="Delete" />
	</div>
	<div class="clearBoth"></div>
</div>
</form>
<?

	echo '<center>'. ResNavigationRet( 'ObjectsLower', 0 ) .'</center>';
}

?>
</form>
</div>
<?

ContentBlockFoot();

BottomCode();
?>