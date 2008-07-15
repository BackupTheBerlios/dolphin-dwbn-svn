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
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolMedia.php' );


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


$_page['header'] = _t( "_MEDIA_GALLERY_H" );
$_page['header_text'] = _t( "_MEDIA_GALLERY_H" );

// --------------- GET/POST actions

$ID = (int)$_REQUEST['ID'];
$member['ID'] = (int)$_COOKIE['memberID'];

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = getPageMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

function getPageMainCode()
{
	global $oTemplConfig;
	$ret = '';

	$aPhotoConf = array();
	$aPhotoConf['profileID'] = (int)$_REQUEST['ID'];
	$aPhotoConf['visitorID'] = (int)$_COOKIE['memberID'];
	$aPhotoConf['isOwner'] = ( $aPhotoConf['profileID'] == $aPhotoConf['visitorID'] ) ? true : false;

	$check_res = checkAction( $aPhotoConf['visitorID'], ACTION_ID_USE_GALLERY );
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

	$oMedia = new BxDolMedia();
	$oMedia -> iProfileID = $aPhotoConf['profileID'];
	$oMedia -> sMediaType =  (isset($_REQUEST['show'])) ? $_REQUEST['show'] : 'video';
	$oMedia -> BxDolMedia();
	$oMedia -> getActiveMediaArray();
	
	$ret .= ProfileDetails( $oMedia -> iProfileID );
	$ret .= '<div class="clear_both"></div>';
	
	if( $oTemplConfig -> customize['media_gallery']['showMediaTabs'] )
	{
		$ret .= '<div class="choiseBlock">';
			$ret .= getMediaTabs( $oMedia -> sMediaType, $oMedia -> aMediaConfig );
		$ret .= '</div>';
	}
	
	//print_r( $oMedia -> aMedia);
	$i = 1;
	foreach( $oMedia -> aMedia as $aValue )
	{
		$ret .= '<div class="mediaBlock">';
			$ret .= '<div class="mediaTitleVA">';
				$ret .= process_line_output($aValue['med_title'] );
			$ret .= '</div>' . "\n";
			$ret .= '<div class="mediaActions">';
				$ret .= '<span>';
					$ret .= '<a href="' . $oMedia -> getMediaLink( $aValue ) . '">';
						$ret .= _t('_download');
					$ret .= '</a>';
				$ret .= '</span>';
				/*
				$ret .= '<span>';
					$ret .= '<a href="' . $_SERVER['PHP_SELF'] . '?show=' . $oMedia -> sMediaType . '&amp;action=delete&amp;mediaID=' . $aValue['med_id'] . '">';
						$ret .= _t('_delete');
					$ret .= '</a>';
				$ret .= '</span>';
				*/
			$ret .= '</div>' . "\n";
		$ret .= '</div>' . "\n";

		if( $i >= $oMedia -> aMediaConfig['max'][$oMedia -> sMediaType] )
		{
			break;
		}
		$i ++;
	}
	$ret .= '';

	return  $ret;
}

function getMediaTabs( $show = 'video', $aMediaConf )
{
	$aItems = array( 'audio', 'video' );
/*
	echo '<hr>';
	print_r($aMediaConf);
	echo '<hr>';
	*/
	foreach( $aItems as $sItem )
	{
		if( $aMediaConf['enable'][$sItem] )
		{
			if( $sItem == $show )
			{
				$ret .= '<div class="item">';
					$ret .= '<div class="active">';
						$ret .= _t('_' . $sItem . '');
					$ret .= '</div>';
				$ret .= '</div>';
			}
			else
			{
				$ret .= '<div class="item">';
					$ret .= '<div class="passive" onmouseover="this.className=\'hover\'" onmouseout="this.className=\'passive\'">';
						$ret .= '<a href="' . $_SERVER['PHP_SELF'] . '?ID=' . $aMediaConf['profile']['ID'] . '&amp;show=' . $sItem . '">' . _t('_' . $sItem . '') . '</a>';
					$ret .= '</div>';
				$ret .= '</div>';
			}
		}
		else
		{
			$ret .= '';
		}
	}

	return $ret;
}


?>