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


$_page['name_index']	= 62;
$_page['css_name']		= 'upload_media.css';

$_page['extra_js'] = '
	<script type="text/javascript">
		active= new Image();
  		active.src="' . $site['url'] . 'templates/base/images/media_tab_active.png;";
  		passive= new Image();
  		passive.src="' . $site['url'] . 'templates/base/images/media_tab_passive.png;";
  		hover= new Image();
  		hover.src="' . $site['url'] . 'templates/base/images/media_tab_hover.png;";
	</script>
';

$logged['member'] = member_auth( 0 );

$_page['header'] = _t( "_Profile Photos" );
//$_page['header_text'] = _t( "_UPLOAD_MEDIA", $site['title'] );


$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();

PageCode();



function PageCompMainCode()
{
	global $tmpl, $_page, $oTemplConfig;

	$iProfileID = (int)$_COOKIE['memberID'];

	$show = $_REQUEST['show'];
	switch( $show )
	{
		case 'photo':
		default:
				require_once( BX_DIRECTORY_PATH_ROOT . 'uploadPhoto.php' );
				$oMedia = new UploadPhoto( $iProfileID );
				$oMedia -> getMediaArray();
				$_page['header_text'] = _t( "_My Photos" );
		break;
	}

	$ret = '';

			$ret .= '
			<script type="text/javascript">

				function checkForm()
				{


					var el;
					var hasErr = false;
					var fild = "";

					el = document.getElementById("mediaTitle");
					if( el.value.length < ' . $oMedia -> aMediaConfig['min']['mediaTitle'] . ' )
					{
						el.style.backgroundColor = "pink";
						el.style.border = "1px solid silver";
						hasErr = true;
						fild += "\n ' . _t( '_title_min_lenght', $oMedia -> aMediaConfig['min']['mediaTitle'] ) . '";
					}
					else
					{
						el.style.backgroundColor = "#fff";
					}

					if (hasErr)
					{
						alert( fild )
						return false;

					}
					else
					{
						return true;
					}


					return false;
				}
			</script>
';
	
	if( $oTemplConfig -> customize['upload_media']['showMediaTabs'] )
	{
		$ret .= '<div class="choiseBlock">';
			$ret .= getMediaTabs( $oMedia -> sMediaType, $oMedia -> aMediaConfig['enable'] );
		$ret .= '</div>' . "\n";
		$ret .= '<div class="clear_both"></div>';
	}
	
	
	if( $oTemplConfig -> customize['upload_media']['showAddButton'] )
	{
		$ret .= '<div class="addNew">';
			$ret .= '<a href="javascript:void(0);" onclick="return BxShowBlock(\'addNewBlock\');">' . _t('_add_new', _t('_' . $oMedia -> sMediaType . '')) . '</a>';
		$ret .= '</div>' . "\n";
	}
	
	$ret .= '<div id="addNewBlock" style="display:' . $oTemplConfig -> customize['upload_media']['addNewBlock_display'] . ';">';

	if( $oMedia -> iMediaCount >= $oMedia -> aMediaConfig['max'][$oMedia -> sMediaType] )
	{
		$ret .= _t_action('_too_many_files');
	}
	else
	{
		$ret .= '<form enctype="multipart/form-data" action="' . $oMedia -> aMediaConfig['url']['media'] . '?show=' . $oMedia -> sMediaType . '" method="post" onsubmit="return checkForm();">';
			$ret .= '<div>';
				$ret .= _t('_Title');
				$ret .= '&nbsp;(&nbsp;<span id="charCount" class="charCount">' . $oMedia -> aMediaConfig['max']['mediaTitle'] . '</span>';
				$ret .= '<span class="charCount">' . _t('_characters_left') . '</span>&nbsp;)';
			$ret .= '</div>' . "\n";
			$ret .= '<div>';
				$ret .= '<input type="text" name="title" class="title" id="mediaTitle" onkeydown="return charCounter(\'mediaTitle\', ' . $oMedia -> aMediaConfig['max']['mediaTitle'] . ', \'charCount\');" onkeyup="return charCounter(\'mediaTitle\', ' . $oMedia -> aMediaConfig['max']['mediaTitle'] . ', \'charCount\');"  />';
			$ret .= '</div>' . "\n";
			$ret .= '<div>';
				$ret .= '<input type="file" size="34" name="' . $oMedia -> sMediaType . '" class="file" />';
			$ret .= '</div>' . "\n";
			$ret .= '<div>';
				$ret .= '<input type="submit" value="' . _t('_Submit') . '">';
				$ret .= '<input type="hidden" name="media_type" value="' . $oMedia -> sMediaType . '" />';
				$ret .= '<input type="hidden" name="show" value="' . $oMedia -> sMediaType . '" />';
				$ret .= '<input type="hidden" name="action" value="upload" />';
			$ret .= '</div>' . "\n";
		$ret .= '</form>';

	}
	$ret .= '</div>' . "\n";

	$action = $_REQUEST['action'];
	if( $_POST['makePrim'] || isset($_POST['makePrim_x'] ) )
	{
		$action = 'makePrim';
	}
	elseif( $_POST['makePrimCouple'] || isset($_POST['makePrimCouple_x'] ) )
	{
		$action = 'makePrimCouple';
	}
	elseif( $_POST['makeRable'] )
	{
		$action = 'makeRable';
	}
	elseif( $_POST['deletePhoto'] || isset($_POST['deletePhoto_x']) )
	{
		$action = 'deletePhoto';
	}

	$iPhotoID = (int)$_REQUEST['photoID'];
	$iMediaID = (int)$_REQUEST['mediaID'];

	switch($action)
	{
		case 'upload':
			$ret .= $oMedia -> uploadMedia();
			header('Location:' . $_SERVER['PHP_SELF'] . '?show=' . $oMedia -> sMediaType );
		break;
		case 'makePrim':
			$ret .= $oMedia -> makePrimPhoto( $iPhotoID );
			header('Location:' . $_SERVER['PHP_SELF'] . '?show=' . $oMedia -> sMediaType);
		break;
		case 'makeRable':
			$ret .= $oMedia -> makeRablePhoto( $iPhotoID );
			header('Location:' . $_SERVER['PHP_SELF'] . '?show=' . $oMedia -> sMediaType);
		break;
		case 'makePrimCouple':
			$ret .= $oMedia -> makePrimPhoto( $iPhotoID, true );
			header('Location:' . $_SERVER['PHP_SELF'] . '?show=' . $oMedia -> sMediaType);
		break;
		case 'deletePhoto':
			$ret .= $oMedia -> deleteMedia( $iPhotoID );
			header('Location:' . $_SERVER['PHP_SELF'] . '?show=' . $oMedia -> sMediaType);
		break;
		case 'delete':
			$ret .= $oMedia -> deleteMedia( $iMediaID );
			header('Location:' . $_SERVER['PHP_SELF'] . '?show=' . $oMedia -> sMediaType);
		break;
	}


	switch( $show )
	{
		case 'photo':
		default:
			if( $_REQUEST['photoID'] )
			{
				$iPhotoId = (int)$_REQUEST['photoID'];
				$ret .= $oMedia -> getMediaPage($iPhotoId);
			}
			else
			{
				$ret .= $oMedia -> getMediaPage();
			}
		break;
	}

	return $ret;
}

function getMediaTabs( $show = 'photo', $aMediaConf ) {
	$aItems = array( 'photo' );

	foreach( $aItems as $sItem )
	{
		if( $aMediaConf[$sItem] )
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
						$ret .= '<a href="' . $_SERVER['PHP_SELF'] . '?show=' . $sItem . '">' . _t('_' . $sItem . '') . '</a>';
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