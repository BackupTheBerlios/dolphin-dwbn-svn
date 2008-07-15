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
require_once( BX_DIRECTORY_PATH_INC . 'sharing.inc.php' );

$logged['admin'] = member_auth( 1, true, true );
$ADMIN = $logged[admin];

$_page['css_name']		= 'post_moderation.css';

$_page['header'] = "Profile Music Postmoderation";
$_page['header_text'] = "";

if (isset($_GET['iUser']))
{
	$iUser = (int)$_GET['iUser'];
}

if (isset($_POST['check']) && is_array($_POST['check']))
{
	foreach($_POST['check'] as $iKey => $iVal)
	{
		switch (true)
		{
			case isset($_POST['Delete']):
				deleteAudio((int)$iVal);
				break;
			case isset($_POST['Approve']):
				approveAudio((int)$iVal);
				break;
		}
	}
}

$sqlQuery = "SELECT 
				`RayMp3Files`.`ID` as `medID`,
				`RayMp3Files`.`Title` as `medTitle`,
				`RayMp3Files`.`Description` as `medDesc`,
				`RayMp3Files`.`Owner` as `medProfId`,
				`RayMp3Files`.`Date` as `medDate`,
				`RayMp3Files`.`Approved`,
				`Profiles`.`NickName`
				FROM `RayMp3Files`
				LEFT JOIN `Profiles` ON `Profiles`.`ID`=`RayMp3Files`.`Owner`
				";
$sqlWhere = $iUser == 0 ? "WHERE `Owner`<>'0'" : "WHERE `Owner`='$iUser'";
	
$iTotalNum = db_value( "SELECT COUNT( * ) FROM `RayMp3Files` LEFT JOIN `Profiles` ON `Profiles`.`ID`=`RayMp3Files`.`Owner` $sqlWhere" );
$iPerPage = 10;
$iTotalPages = ceil( $iTotalNum / $iPerPage );
$iCurPage = (int)$_GET['page'];
	
if( $iCurPage > $iTotalPages )
	$iCurPage = $iTotalPages;
	
if( $iCurPage < 1 )
	$iCurPage = 1;
	
$sLimitFrom = ( $iCurPage - 1 ) * $iPerPage;
$sqlLimit = "LIMIT $sLimitFrom, $iPerPage";
	
$res = db_res( $sqlQuery.$sqlWhere.$sqlLimit );
$aFiles = fill_assoc_array( $res );

TopCodeAdmin();
ContentBlockHead("");
	echo getPostModMediaPage($iUser);
ContentBlockFoot();
BottomCode();


function getPostModMediaPage( $iUser = 0 )
{
	global $dir, $site, $max_thumb_width, $max_thumb_height, $max_photo_width, $max_photo_height;
	global $aFiles;
	global $iTotalPages, $iCurPage;
	
	$ret = '';

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

	$iCounter = 1;
	foreach( $aFiles as $aMedia )
	{
		$sPic = '<img src="'.$site['admin'] . 'images/music.png">';
		$sMediaLink = "<a href=\"javascript:openRayWidget('mp3','player','".$aMedia['medID']."','1','1','true')\">".$aMedia['medTitle']."</a>";
		$sProf = '<a href="'.$site['url'].'pedit.php?ID='.$aMedia['medProfId'].'">'.$aMedia['NickName'].'</a>';
		
		$sStyle = $aMedia['Approved'] == 'true' ? ' style="border: 2px solid #00CC00;"' : ' style="border: 2px solid #CC0000;"' ;
		$ret .= '<div class="mainBlock"'.$sStyle.'>';
			$ret .= '<div class="checkBox">
						<input type="checkbox" name="check[]" id="ch'.$aMedia['medID'].'" value="'.$aMedia['medID'].'">
					</div>';
			$ret .= '<div class="picture">';
				$ret .= $sPic;
			$ret .= '</div>';
			$ret .= '<div class="fileInfo">';
				$ret .= '<div>';
					$ret .= $sMediaLink;
				$ret .= '</div>';
				$ret .= '<div>by '.$sProf.'</div>';
				$ret .= '<div>Added: '.defineTimeInterval($aMedia['medDate']).'</div>';
			$ret .= '</div>';
		$ret .= '</div>';
		if( ( $iCounter % 2 ) == 0 )
			$ret .= '<div class="clear_both"></div>';
		$iCounter++;
	}
	if( !empty( $aFiles ) )
	{
		$sCheck  = count($aFiles) > 1 ? '<input type="checkbox" name=\"ch_all" onclick="checkAll( \'ch\', this.checked )" />Check all' : '';
		$ret .= '<div style="clear:both; font-weight:bold; text-align:center;">'.$sCheck.'<input type="submit" name="Approve" value="Change status">
		<input type="submit" name="Delete" value="Delete"></div>';
	} else {
		$ret .= MsgBox( 'Sorry, nothing found' );
	}
	
	if( $iTotalPages > 1)
	{
		$sRequest = $_SERVER['PHP_SELF'] . '?page={page}';
		$pagination = '<div style="text-align:center; margin: 10px 0px 10px 0px;">' . genPagination( $iTotalPages, $iCurPage, $sRequest ) . '</div>';
	}
	else
		$pagination = '';
		
	return $ret.$pagination.'</form></div>';
}


function deleteAudio( $iMedia )
{
	global $dir;

	$sFileName = $dir['root'] . 'ray/modules/mp3/files/'.$iMedia . '.mp3';

	$sqlQuery = "
	DELETE FROM `RayMp3Files` WHERE `ID` = '$iMedia';
	";

	$res = db_res( $sqlQuery );

	@unlink( $sFileName );

	return $res;
}

function approveAudio( $iMedia )
{
	$sqlQuery = "UPDATE `RayMp3Files` SET `Approved` = IF(`Approved`='true','false','true') WHERE `ID`='$iMedia'";
	$res = db_res($sqlQuery);
	
	return $res;
}

?>
