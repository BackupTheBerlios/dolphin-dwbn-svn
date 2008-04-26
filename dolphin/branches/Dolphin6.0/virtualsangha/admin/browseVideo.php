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
require_once( BX_DIRECTORY_PATH_INC . 'profile_disp.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'sharing.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$_page['extra_js'] = '';

$logged[admin] = member_auth( 1 );
$ADMIN = $logged[admin];

$_page['css_name']		= 'browse.css';

$_page['header'] = "Browse Video";
$_page['header_text'] = "Browse Video";

$_ni = $_page['name_index'];

$sType = 'Video';

if (isset($_POST['Check'])  && is_array($_POST['Check']))
{
	foreach($_POST['Check'] as $iKey => $iVal)
 	{
 		switch (true)
		{
			case isset($_POST['Delete']):
 		deleteMedia((int)$iVal, $sType);
				break;
			case isset($_POST['Approve']):
				approveMedia((int)$iVal, $sType);
				break;	
		}	
 	}
}

$aWhere = array();

$aWhere[] = '1';

if (isset($_GET['userID']))
{
	$iUser = (int)$_GET['userID'];
	$aWhere[] = "`a`.`medProfId`=$iUser";   
}

if (isset($_GET['tag']))
{
	$sTag = htmlspecialchars_adv($_GET['tag']);
	$aWhere[] = "`a`.`medTags` like '%$sTag%'";
}

$sqlWhere = "WHERE " . implode( ', ', $aWhere );

$iTotalNum = db_value( "SELECT COUNT( * ) FROM `RayMovieFiles` AS `a` $sqlWhere" );
if( !$iTotalNum )
	$sCode .= '<div>There is no files</div>';

$iPerPage = 10;
$iTotalPages = ceil( $iTotalNum / $iPerPage );

$iCurPage = (int)$_GET['page'];

if( $iCurPage > $iTotalPages )
	$iCurPage = $iTotalPages;

if( $iCurPage < 1 )
	$iCurPage = 1;
	
$sLimitFrom = ( $iCurPage - 1 ) * $iPerPage;

$sqlOrder = " ORDER BY `medDate` DESC ";
$sqlLimit = "LIMIT $sLimitFrom, $iPerPage";

$sQuery = "
	SELECT
		`a`.`ID` as `medID`,
		`a`.`Owner` as `medProfId`,
		`a`.`Title` as `medTitle`,
		`a`.`Date` as `medDate`,
		`a`.`Views` as `medViews`,
        `a`.`Approved`,
		`b`.`NickName`
	FROM `RayMovieFiles` as `a`
	LEFT JOIN `Profiles` as `b` ON
		`b`.`ID` = `a`.`Owner`
	$sqlWhere
	$sqlOrder
	$sqlLimit
	";

$rData = db_res($sQuery);

TopCodeAdmin();
ContentBlockHead("List of Video files");

echo browseCode();

ContentBlockFoot();
BottomCode();

function browseCode()
{
	global $site;
	global $rData;
	global $iTotalPages;
	global $iCurPage;
	
	$sCode = '<div id = "browseMain">';
	
	$sCode .= '<form method="post" action="'.$_SERVER['PHP_SELF'].'">';
	
	if (mysql_num_rows($rData))
	{
		while ($aData = mysql_fetch_array($rData))
		{
			$sStyle = $aData['Approved'] == 'true' ? ' style="border: 2px solid #00CC00;"' : ' style="border: 2px solid #CC0000;"' ;
                        $sProf = '<a href="'.$site['url'].'profile_edit.php?ID='.$aData['medProfId'].'">'.$aData['NickName'].'</a>';
			$sCode .= '<div class="browseUnit"'.$sStyle.'>';
				$sCode .= '<div class="browseCheckbox"><input type="checkbox" name="Check[]" value="'.$aData['medID'].'"></div>';
				$sCode .= '<div class="lastFilesPic"><img src="'.$site['url'].'ray/modules/movie/files/'.$aData['medID'].'_small.jpg"></div>';
					$sCode .= '<div class="browseInfo"><div><a href="'.$site['url'].'viewVideo.php?fileID='.$aData['medID'].'"><b>'.$aData['medTitle'].'</b></a></div>';
					$sCode .= '<div>'._t("_Added").': <b>'.defineTimeInterval($aData['medDate']).'</b> by '.$sProf.'</div>';
					$sCode .= '<div>'._t("_Views").': <b>'.$aData['medViews'].'</b></div></div>';
			$sCode .= '</div>';	
		}
	}
	else
	{
		$sCode .= '<div>There are no files to approve</div>';
	}
	$sCode .= '</div>';
	$sCode .= '<div class="clear_both"></div>';
	$sCode .= '<div class="bottomPart"><input type="submit" name="Delete" value="Delete"><input type="submit" name="Approve" value="Change status"></div>';
	
	// generate pagination
	if( $iTotalPages > 1)
	{
		$sRequest = $_SERVER['PHP_SELF'] . '?page={page}';
		$aFields = array( 'userID', 'tag' );
		
		foreach( $aFields as $field )
			if( isset( $_GET[$field] ) )
				$sRequest .= "&{$field}=" . htmlentities( process_pass_data( $_GET[$field] ) );
		
		$pagination = '<div style="text-align:center;">' . genPagination( $iTotalPages, $iCurPage, $sRequest ) . '</div>';
	}
	else
		$pagination = '';
	
	return $sCode . $pagination.'</form>';
}

?>