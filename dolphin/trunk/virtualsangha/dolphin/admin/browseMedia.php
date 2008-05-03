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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'sharing.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolSharedMedia.php' );

$_page['extra_js'] = '';

$logged['admin'] = member_auth( 1, true, true );
$ADMIN = $logged[admin];

$_page['css_name']	= 'browse.css';

if (isset($_REQUEST['type']) && ($_REQUEST['type']=='photo' ||$_REQUEST['type']=='music' || $_REQUEST['type']=='video')) 
	$sType = htmlspecialchars_adv($_REQUEST['type']);
else
	$sType = 'photo';

$sBigType = ucfirst($sType);

$_page['header'] = "Browse $sBigType";
$_page['header_text'] = "Browse $sBigType";

$_ni = $_page['name_index'];

$aMem = array();
$oNew = new BxDolSharedMedia($sType, $site, $dir, $aMem);

if (isset($_POST['Check'])  && is_array($_POST['Check'])) {
	foreach($_POST['Check'] as $iKey => $iVal) {
 		switch (true) {
			case isset($_POST['Delete']):
 				$oNew->deleteMedia((int)$iVal, $logged);
				break;
			case isset($_POST['Approve']):
				$oNew->approveMedia((int)$iVal);
				break;	
		}	
 	}
}

$aWhere = array();

$aWhere[] = '1';

if (isset($_GET['userID'])) {
	$iUser = (int)$_GET['userID'];
	$aWhere[] = "`{$oNew->sMainTable}`.`{$oNew->aTableFields['medProfId']}`=$iUser";   
}

if (isset($_GET['tag'])) {
	$sTag = htmlspecialchars_adv($_GET['tag']);
	$aWhere[] = "`{$oNew->sMainTable}`.`{$oNew->aTableFields['medTags']}` like '%$sTag%'";
}

$aSqlQuery['sqlWhere'] = "WHERE " . implode( ' AND ', $aWhere );

$iTotalNum = db_value( "SELECT COUNT( * ) FROM `{$oNew->sMainTable}` {$aSqlQuery['sqlWhere']}" );
if( !$iTotalNum )
	$sCode .= '<div>There are no files</div>';

$iPerPage = (int)$_GET['per_page'];
if (!$iPerPage)
	$iPerPage = 10;
	
$iTotalPages = ceil( $iTotalNum / $iPerPage );

$iCurPage = (int)$_GET['page'];

if( $iCurPage > $iTotalPages )
	$iCurPage = $iTotalPages;

if( $iCurPage < 1 )
	$iCurPage = 1;
	
$sLimitFrom = ( $iCurPage - 1 ) * $iPerPage;
$aSqlQuery['sqlLimit'] = "LIMIT $sLimitFrom, $iPerPage";

$aSqlQuery['sqlOrder'] = "ORDER BY `{$oNew->aTableFields['medDate']}` DESC";

$aManage = array('medID', 'medProfId', 'medTitle', 'medUri', 'medDate', 'medViews', 'medExt', 'Approved');

if ($iTotalNum > 0) {
	$aCount = array('total'=>$iTotalPages, 'current'=>$iCurPage, 'per_page'=>$iPerPage);
	$rData = $oNew->getFilesList($aSqlQuery, $aManage);
	$sCode = browseCode($oNew, $rData, $aCount); 
}

TopCodeAdmin();
ContentBlockHead("List of $sBigType files");

echo $sCode;

ContentBlockFoot();
BottomCode();

function browseCode($oNew, $rData, $aCount) {
	$sCode = '<div id="browseMain"><form method="post" action="">';
	$iCount = mysql_num_rows($rData);
	
	$sCheckAll = $iCount > 1 ?'<input type="checkbox" name=\"ch_all" onclick="checkAll( \'Check[]\', this.checked )" />Check all' : '';
	
	if ($iCount) 
		while ($aData = mysql_fetch_array($rData)) 
			$sCode .= $oNew->showBrowseUnit($aData, true);
	else 
		$sCode .= '<div>There is are files</div>';

	$sCode .= '</div>';
	ob_start();
	?>
	<div class="clear_both"></div>
	<script>
		function checkAll( _pref, do_check ) {
			aElems = document.getElementsByTagName( 'input' );
			for( i = 0; i < aElems.length; i ++ ) {
				var elt = aElems[i];
				if( elt.name.substr( 0, _pref.length ) == _pref )
					elt.checked = do_check;
			}
		}
	</script>
	<div class="bottomPart">
		<?=$sCheckAll?>
		<input type="submit" name="Delete" value="Delete">
		<input type="submit" name="Approve" value="Change status">
		<input type="hidden" name="type" value="<?=$oNew->sType?>">
	</div>
	<?
	$sCode .= ob_get_clean();
	$sPagination = $oNew->showPagination($aCount['total'], $aCount['current'], $aCount['per_page'], true);
	
	return $sCode.$sPagination.'</form>';
}

?>