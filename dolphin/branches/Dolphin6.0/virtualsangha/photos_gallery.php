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

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_ROOT . 'profilePhotos.php' );
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplVotingView.php" );

// --------------- page variables and login

$img_num = $pic_num;

$_page['name_index'] 	= 62;
$_page['css_name']		= 'upload_media.css';


if ( !( $logged['admin'] = member_auth( 1, false ) ) )
{
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
	{
		if ( !( $logged['aff'] = member_auth( 2, false )) )
		{
			$logged['moderator'] = member_auth( 3, false );
		}
	}
}


$_page['header'] = _t( "_Profile Photos" );
//$_page['header_text'] = _t( "_PIC_GALLERY_H1" );

$oVotingView = new BxTemplVotingView('media', 0, 0);
$_page['extra_js'] 	= $oVotingView->getExtraJs();

// --------------- GET/POST actions



$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = getPageMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions


function getPageMainCode()
{
	$aPhotoConf = array();
	$aPhotoConf['profileID'] = (int)$_REQUEST['ID'];
	$aPhotoConf['visitorID'] = (int)$_COOKIE['memberID'];
	$aPhotoConf['isOwner'] = ( $aPhotoConf['profileID'] == $aPhotoConf['visitorID'] ) ? true : false;
	
	if( $aPhotoConf['isOwner'] )
	{
		header( "Location:upload_media.php?show=photos" );
		exit;
	}
	
	$check_res = checkAction( $aPhotoConf['visitorID'], ACTION_ID_VIEW_PHOTOS );
	if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED )
	{
		$ret = '
			<table width="100%" cellpadding="4" cellspacing="4" border="0">
				<tr>
					<td align="center">' . $check_res[CHECK_ACTION_MESSAGE] . '</td>
				</tr>
			</table>';
		return $ret;
	}

	$oPhotos = new ProfilePhotos( $aPhotoConf['profileID'] );
	$oPhotos -> getActiveMediaArray();

	$ret = '';
	if( $_REQUEST['voteSubmit'] && $_REQUEST['photoID'] )
	{
		$oPhotos -> setVoting();
		header('Location:' . $_SERVER['PHP_SELF'] . '?ID=' . $oPhotos -> iProfileID . '&photoID=' . $_REQUEST['photoID'] );
	}

	if( !$aPhotoConf['isOwner'] )
	{
		$ret .= ProfileDetails( $oPhotos -> iProfileID );
		$ret .= '<div class="clear_both"></div>';
	}


	if( 0 < $_REQUEST['photoID'] )
	{
		$iPhotoID = $_REQUEST['photoID'];
		$ret .= $oPhotos -> getMediaPage( $iPhotoID );
	}
	else
	{
		$ret .= $oPhotos -> getMediaPage();
	}



	return $ret;
}
?>
