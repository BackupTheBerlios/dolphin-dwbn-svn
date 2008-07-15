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

require_once('inc/header.inc.php');
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'sharing.inc.php' );

$_page['name_index']	= 82;
$_page['css_name']		= 'viewPhoto.css';

$oVotingView = new BxTemplVotingView('gphoto', 0, 0);
$_page['extra_js'] 	= $oVotingView->getExtraJs();

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
{
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
	{
		if ( !( $logged['aff'] = member_auth( 2, false ) ) )
		{
			$logged['moderator'] = member_auth( 3, false );
		}
	}
}


$_page['header'] = _t( "_browsePhoto" );
$_page['header_text'] = _t("_browsePhoto");

$_ni = $_page['name_index'];

$member['ID'] = (int)$_COOKIE['memberID'];

$aWhere = array();
$aWhere[] = '1';

if (isset($_GET['userID']))
{
	$iUser = (int)$_GET['userID'];
	$aWhere[] = "`sharePhotoFiles`.`medProfId`=$iUser";   
}

if (isset($_GET['tag']))
{
	$sTag = htmlspecialchars_adv($_GET['tag']);
	$aWhere[] = "`sharePhotoFiles`.`medTags` like '%$sTag%'";
}

if (isset($_GET['action']))
{
	$sAct = htmlspecialchars_adv($_GET['action']);
	$sAddon = defineBrowseAction($sAct,'Photo',$member['ID']);
}

$sqlWhere = "WHERE " . implode( ' AND ', $aWhere ).$sAddon." AND `Approved`= 'true'";

$iTotalNum = db_value( "SELECT COUNT( * ) FROM `sharePhotoFiles` $sqlWhere" );
if( !$iTotalNum )
{
	$_page_cont[$_ni]['page_main_code'] = _t( '_Sorry, nothing found' );

	PageCode();
	exit;
}

$iPerPage = (int)$_GET['per_page'];
if( !$iPerPage )
	$iPerPage = 10;

$iTotalPages = ceil( $iTotalNum / $iPerPage );

$iCurPage = (int)$_GET['page'];

if( $iCurPage > $iTotalPages )
	$iCurPage = $iTotalPages;

if( $iCurPage < 1 )
	$iCurPage = 1;

$sLimitFrom = ( $iCurPage - 1 ) * $iPerPage;

$sqlOrderBy = 'ORDER BY `medDate` DESC';

if (isset($_GET['rate']))
{
	$oVotingView = new BxTemplVotingView ('gphoto', 0, 0);
	
	$aSql        = $oVotingView->getSqlParts('`sharePhotoFiles`', '`medID`');
	$sHow        = $_GET['rate'] == 'top' ? "DESC" : "ASC";
	$sqlOrderBy  = $oVotingView->isEnabled() ? "ORDER BY `voting_rate` $sHow, `voting_count` $sHow, `medDate` $sHow" : $sqlOrderBy ;
	$sqlFields   = $aSql['fields'];
	$sqlLJoin    = $aSql['join'];
}	
$sqlLimit = "LIMIT $sLimitFrom, $iPerPage";

$sQuery = "
	SELECT
		`sharePhotoFiles`.`medID`,
		`sharePhotoFiles`.`medProfId`,
		`sharePhotoFiles`.`medTitle`,
		UNIX_TIMESTAMP(`sharePhotoFiles`.`medDate`) as `medDate`,
		`sharePhotoFiles`.`medViews`,
		`sharePhotoFiles`.`medExt`,
		`Profiles`.`NickName`
		$sqlFields
	FROM `sharePhotoFiles`
	LEFT JOIN `Profiles` ON
		`Profiles`.`ID` = `sharePhotoFiles`.`medProfId`
	$sqlLJoin
	$sqlWhere
	$sqlOrderBy
	$sqlLimit
	";

$rData = db_res($sQuery);

$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

PageCode();

function PageCompPageMainCode()
{
	global $site;
	global $rData;
	global $iTotalPages;
	global $iCurPage;
	global $iPerPage;
	global $member;
	
	$sCode = '<div style="position: relative; float: left;">';

	if (mysql_num_rows($rData))
	{
		while ($aData = mysql_fetch_array($rData))
		{
			$sImage = $site['sharingImages'].$aData['medID'].'_t.'.$aData['medExt'];
			$sProfLink = '<div>'._t("_By").': <a href="'.getProfileLink($aData['medProfId']).'">'.$aData['NickName'].'</a></div>';
			
			$oVotingView = new BxTemplVotingView ('gphoto', $aData['medID']);
		    if( $oVotingView->isEnabled())
	    	{
				$sRate = $oVotingView->getSmallVoting (0);
				$sShowRate = '<div class="galleryRate">'. $sRate . '</div>';
			}
			$sHref = $site['url'].'viewPhoto.php?fileID='.$aData['medID'];
			$sImg  = '<div class="lastFilesPic" style="background-image: url(\''.$sImage.'\');">
					  <a href="'.$sHref.'"><img src="'.$site['images'] .'spacer.gif" width="110" height="110"></a></div>';
			
			$sPicTitle = strlen($aData['medTitle']) > 0 ? $aData['medTitle'] : _t("_Untitled");
			$sDelLink = $member['ID'] == $aData['medProfId'] ? '<div><a href="'.$_SERVER['PHP_SELF'].'?action=del&fileID='.$aData['medID'].'"
			onClick="return confirm( \''._t("_are you sure?").'\');">'._t("_Delete").'</a></div>'  : "" ;
			$sCode .= '<div class="browseUnit">';
				$sCode .= $sImg;
				$sCode .= '<div><a href="'.$sHref.'"><b>'.$sPicTitle.'</b></a></div>';
				$sCode .= $sProfLink;
				$sCode .= '<div>'._t("_Added").': <b>'.defineTimeInterval($aData['medDate']).'</b></div>';
				$sCode .= '<div>'._t("_Views").': <b>'.$aData['medViews'].'</b></div>';
				$sCode .= $sShowRate;
				$sCode .= $sDelLink;
			$sCode .= '</div>';	
		}
	}
	$sCode .= '<div class="clear_both"></div>';
	
	// generate pagination
	if( $iTotalPages > 1)
	{
		$sRequest = $_SERVER['PHP_SELF'] . '?';
		$aFields = array( 'userID', 'tag', 'rate' );
		
		foreach( $aFields as $field )
			if( isset( $_GET[$field] ) )
				$sRequest .= "&amp;{$field}=" . htmlentities( process_pass_data( $_GET[$field] ) );
		
		$pagination = '<div style="text-align: center; position: relative;">'._t("_Results per page").':
				<select name="per_page" onchange="window.location=\'' . $sRequest . '&amp;per_page=\' + this.value;">
					<option value="10"' . ( $iPerPage == 10 ? ' selected="selected"' : '' ) . '>10</option>
					<option value="20"' . ( $iPerPage == 20 ? ' selected="selected"' : '' ) . '>20</option>
					<option value="50"' . ( $iPerPage == 50 ? ' selected="selected"' : '' ) . '>50</option>
					<option value="100"' . ( $iPerPage == 100 ? ' selected="selected"' : '' ) . '>100</option>
				</select></div>' .
			genPagination( $iTotalPages, $iCurPage, ( $sRequest . '&amp;page={page}&amp;per_page='.$iPerPage ) );
	}
	else
		$pagination = '';
	
	return $sCode . $pagination.'</div>';
}

?>
