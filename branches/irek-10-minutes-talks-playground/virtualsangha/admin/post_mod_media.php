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

$logged['admin'] = member_auth( 1 );

$navigationStep = 12; // count of objects to show per page

$_page['header'] = "Photos PostModeration";
$_page['header_text'] = "Unapproved profile photos";

if (isset($_GET['media']))
{
	$sType = htmlspecialchars_adv($_GET['media']);
}

if (isset($_GET['status']))
{
	$sStatus = htmlspecialchars_adv($_GET['status']);
}

if (isset($_GET['iUser']))
{
	$iUser = htmlspecialchars_adv($_GET['iUser']);
}

if (isset($_POST['check']) && is_array($_POST['check']))
{
	foreach($_POST['check'] as $iKey => $iVal)
	{
		switch (true)
		{
			case isset($_POST['Delete']):
				deleteItem((int)$iVal);
				break;
			case isset($_POST['Approve']):
				approveItem((int)$iVal);
				break;	
		}
	}
}

TopCodeAdmin();
ContentBlockHead("Profile media");
	echo getPostModMediaPage($sType,$sStatus,$iUser);
ContentBlockFoot();
BottomCode();



function getPostModMediaPage($sType = 'photo', $sStatus = 'passive', $iUser = 0)
{
	global $dir;
	global $site, $max_thumb_width, $max_thumb_height, $max_photo_width, $max_photo_height;
	
	$ret = '';

	$aFiles = getUnapprovedFilesArray($sType, $sStatus, $iUser);

	$style = '
		float:left;
		margin:5px 11px;
		padding:5px;
		border:1px solid silver;
		text-align:center;

	';
	$style2 = '
		width:' . $max_thumb_width  . 'px;
		height:' . $max_thumb_height . 'px;
		background-color:#f1f1f1;
		border:1px solid silver;
	';
	$ret .= '<div style="clear:both;"></div>';
	$ret .= "<script>
	function checkAll( _pref, do_check )
	{
		aElems = document.getElementsByTagName( 'input' );
		
		for( i = 0; i < aElems.length; i ++ )
		{
			elt = aElems[i];
			if( elt.name.substr( 0, _pref.length ) == _pref )
				elt.checked = do_check;
		}
	}
	</script>"
	;
	$ret .= '<div><form method="post" action="'.$_HTTP['REFERER'].'">';
	foreach( $aFiles as $aMedia )
	{
		$sThumbFile = $dir['profileImage'] . $aMedia['med_prof_id'] . '/thumb_' . $aMedia['med_file'];
		if( extFileExists( $sThumbFile ) )
		{
			$sThumbUrl = $site['profileImage'] . $aMedia['med_prof_id'] . '/thumb_' . $aMedia['med_file'];
			$sMediaUrl = $site['profileImage'] . $aMedia['med_prof_id'] . '/photo_' . $aMedia['med_file'];
			$ret .= '<div style="' . $style . '">';
				$ret .= '<div style="' . $style2 . '">';
					$ret .= '<img src="' . $sThumbUrl . '" onclick="window.open(\'' . $sMediaUrl . '\', \'photo\',\'width=' . ($max_photo_width+10) . ',height=' . ($max_photo_height+10) . ',left=100,top=100,copyhistory=no,directories=no,menubar=no,location=no,resizable=yes,scrollbars=yes\');" />';
				$ret .= '</div>';
				$ret .= '<br />';
			$ret .= '<div><input type="checkbox" name="check[]" id="ch'.$aMedia['med_id'].'" value="'.$aMedia['med_id'].'"></div>';	
			$ret .= '</div>';
		}
	}
	$ret .= '<div style="clear:both;"></div>';
	
	if( !empty( $aFiles ) )
	{
		$sAppBut = $sStatus == 'passive' ? '<input type="submit" name="Approve" value="Approve">' : '';
		$sCheck  = count($aFiles) > 1 ? '<input type="checkbox" name=\"ch_all" onclick="checkAll( \'ch\', this.checked )" />Check all' : '';
		$ret .= '<div style="clear:both;font-weight:bold; text-align:center;">'.$sCheck.'
		<input type="submit" name="Delete" value="Delete">';
		$ret .= $sAppBut.'</div>';
	}
	$ret .= '</form></div>';
	return $ret;
}

function getUnapprovedFilesArray($sType, $sStatus, $iUser)
{
	$ret = '';
	$sAdd  = "AND `med_status` = '$sStatus'";
	$sAdd .= " AND `med_type` = '$sType'";
	$sAdd .= $iUser != 0 ? " AND `med_prof_id`='$iUser'" : "";
	
	$sQuery = "
		SELECT
	           `media`.`med_id`,
	           `media`.`med_prof_id`,
	           `media`.`med_type`,
	           `media`.`med_file`,
	           `media`.`med_title`
	    FROM `media`
	    WHERE 1
	    $sAdd
  
	    ORDER BY `media`.`med_date`
	";

	$res = db_res( $sQuery );
	$ret = fill_assoc_array( $res );
	return $ret;
}

function deleteItem( $iMedia )
{
	global $dir;

	$aFile = db_arr("SELECT * FROM `media` WHERE `med_id`='$iMedia'");
	
	$sIconFile = $dir['profileImage'] . $aFile['med_prof_id'] . '/icon_' . $aFile['med_file'];
	$sThumbFile = $dir['profileImage'] . $aFile['med_prof_id'] . '/thumb_' . $aFile['med_file'];
	$sPhotoFile = $dir['profileImage'] . $aFile['med_prof_id'] . '/photo_' . $aFile['med_file'];

	$sqlQuery = "
	DELETE FROM `media` WHERE `med_id` = '$iMedia';
	";

	$res = db_res( $sqlQuery );

    // delete votings
    require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolVoting.php' );
    $oVotingMedia = new BxDolVoting ('media', 0, 0);
    $oVotingMedia->deleteVotings ($aMedia['med_id']);

	@unlink( $sIconFile );
	@unlink( $sThumbFile );
	@unlink( $sPhotoFile );

	return $res;
}

function approveItem( $iMedia )
{
	$sQuery = "
		UPDATE `media`
		SET
			`med_status` = 'active'
		WHERE `med_id` = '$iMedia'
	";

	return db_res( $sQuery );
}

function approveAllItems()
{
	$sQuery = "
		UPDATE `media`
		SET
			`med_status` = 'active'
	";

	return db_res( $sQuery );
}


?>