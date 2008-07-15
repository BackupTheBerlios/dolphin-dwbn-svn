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

$_page['css_name']		= 'post_moderation.css';

$navigationStep = 12; // count of objects to show per page

$_page['header'] = "Profile Photos PreModeration";
$_page['header_text'] = "";

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
ContentBlockHead("");
	echo getPostModMediaPage($sType,$sStatus,$iUser);
ContentBlockFoot();
BottomCode();



function getPostModMediaPage($sType = 'photo', $sStatus = 'passive', $iUser = 0)
{
	global $dir, $admin_dir;
	global $site, $max_thumb_width, $max_thumb_height, $max_photo_width, $max_photo_height;
	
	$ret = '';

	//////////////////pagination addition//////////////////////////
	$iTotalNum = getUnapprovedFilesCnt($sType, $sStatus, $iUser);
	if( !$iTotalNum ) {
		print $ret . MsgBox(_t( '_Sorry, nothing found' ));
		/*$sJS = <<<EOF
<script type="text/javascript">
	window.location.href = '{$site['url']}{$admin_dir}/profiles.php?media=photo&status=passive';
</script>
EOF;
		print $sJS;*/
		exit();
	}

	$iPerPage = (int)$_GET['per_page'];
	if( !$iPerPage )
		$iPerPage = 20;
	$iTotalPages = ceil( $iTotalNum / $iPerPage );

	$iCurPage = (int)$_GET['page'];

	if( $iCurPage > $iTotalPages )
		$iCurPage = $iTotalPages;

	if( $iCurPage < 1 )
		$iCurPage = 1;

	$sLimitFrom = ( $iCurPage - 1 ) * $iPerPage;
	$sqlLimit = "LIMIT {$sLimitFrom}, {$iPerPage}";
	////////////////////////////

	$aFiles = getUnapprovedFilesArray($sType, $sStatus, $iUser, $sqlLimit);

	if( !empty( $aFiles ) )
	{
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
			$sThumbUrl = $site['profileImage'] . $aMedia['medProfId'] . '/thumb_' . $aMedia['medFile'];
			$sMedia = '<div class="thumbBlock" style="width:'.$max_thumb_width.'px; height:'.$max_thumb_height.'px; background-image: url(\''.$sThumbUrl.'\');">&nbsp;</div>';
			$sProf = '<a href="'.$site['url'].'pedit.php?ID='.$aMedia['medProfId'].'">'.$aMedia['NickName'].'</a>';
			
			$ret .= '<div class="mainBlock">';
				$ret .= '<div class="checkBox">
							<input type="checkbox" name="check[]" id="ch'.$aMedia['medID'].'" value="'.$aMedia['medID'].'">
						</div>';
				$ret .= '<div class="checkBox">';
						$ret .= $sMedia;
				$ret .= '</div>';
				$ret .= '<div class="photoInfo">';
					$ret .= '<div>'.$aMedia['medTitle'].'</div>';
					$ret .= '<div>by '.$sProf.'</div>';
					$ret .= '<div>Added: '.date('y-m-d',$aMedia['medDate']).'</div>';
				$ret .= '</div>';
			$ret .= '</div>';
		}
		$ret .= '<div style="clear:both;"></div>';
		$sAppBut = $sStatus == 'passive' ? '<input type="submit" name="Approve" value="Approve">' : '';
		$sCheck  = count($aFiles) > 1 ? '<input type="checkbox" name=\"ch_all" onclick="checkAll( \'ch\', this.checked )" />Check all' : '';
		$ret .= '<div style="clear:both;font-weight:bold; text-align:center;">'.$sCheck.'
		<input type="submit" name="Delete" value="Delete">';
		$ret .= $sAppBut.'</div>';
		$ret .= '</form></div>';
	}
	else
	{
		$ret .= '<div style="text-align:center; line-height:25px; vertical-align:middle; background-color:#c2daeb; font-weight:bold;">There is nothing to approve </div>';
	}

	/////////pagination addition//////////////////
	if( $iTotalPages > 1) {
		$sRequest = $_SERVER['PHP_SELF'] . '?';
		$aFields = array( 'media', 'status', 'iUser', 'check', 'Delete', 'Approve', 'Replace' );

		foreach( $aFields as $vField )
			if( isset( $_GET[$vField] ) )
				$sRequest .= "&amp;{$vField}=" . htmlentities( process_pass_data( $_GET[$vField] ) );

		$sPagination = '<div style="text-align: center; position: relative;">'._t("_Results per page").':
				<select name="per_page" onchange="window.location=\'' . $sRequest . '&amp;per_page=\' + this.value;">
					<option value="10"' . ( $iPerPage == 10 ? ' selected="selected"' : '' ) . '>10</option>
					<option value="20"' . ( $iPerPage == 20 ? ' selected="selected"' : '' ) . '>20</option>
					<option value="50"' . ( $iPerPage == 50 ? ' selected="selected"' : '' ) . '>50</option>
					<option value="100"' . ( $iPerPage == 100 ? ' selected="selected"' : '' ) . '>100</option>
				</select></div>' .
			genPagination( $iTotalPages, $iCurPage, ( $sRequest . '&amp;page={page}&amp;per_page='.$iPerPage ) );
	} else
		$sPagination = '';
	///////////////////////////

	return $ret.$sPagination;
}

function getUnapprovedFilesCnt($sType, $sStatus, $iUser)
{
	$ret = '';
	$sAdd  = "AND `med_status` = '$sStatus'";
	$sAdd .= " AND `med_type` = '$sType'";
	$sAdd .= $iUser != 0 ? " AND `med_prof_id`='$iUser'" : "";
	
	$sQuery = "
		SELECT
	           COUNT(`media`.`med_id`) as `Cnt`
	    FROM `media`
	    LEFT JOIN `Profiles` ON `Profiles`.`ID`=`media`.`med_prof_id`
	    WHERE 1
	    $sAdd

	    ORDER BY `media`.`med_date`
	";

	$res = db_value( $sQuery );

	return $res;
}

function getUnapprovedFilesArray($sType, $sStatus, $iUser, $sqlLimit = '')
{
	$ret = '';
	$sAdd  = "AND `med_status` = '$sStatus'";
	$sAdd .= " AND `med_type` = '$sType'";
	$sAdd .= $iUser != 0 ? " AND `med_prof_id`='$iUser'" : "";
	
	$sQuery = "
		SELECT
	           `media`.`med_id` as `medID`,
	           `media`.`med_prof_id` as `medProfId`,
	           `media`.`med_type` as `medType`,
	           `media`.`med_file` as `medFile`,
	           `media`.`med_title` as `medTitle`,
	           UNIX_TIMESTAMP(`media`.`med_date`) as `medDate`,
	           `Profiles`.`NickName`
	    FROM `media`
	    LEFT JOIN `Profiles` ON `Profiles`.`ID`=`media`.`med_prof_id`
	    WHERE 1
	    $sAdd
  
	    ORDER BY `media`.`med_date`
		{$sqlLimit}
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
