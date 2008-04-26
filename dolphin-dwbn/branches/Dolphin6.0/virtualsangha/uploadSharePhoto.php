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
require_once( BX_DIRECTORY_PATH_INC . 'tags.inc.php' );


$_page['name_index']	= 82;
$_page['css_name']		= 'viewPhoto.css';

$_page['extra_js'] = '';

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


$_page['header'] = _t( "_upload Photo" );
$_page['header_text'] = _t("_upload Photo");

$_ni = $_page['name_index'];

$member['ID'] = (int)$_COOKIE['memberID'];
$check_res = checkAction( $member['ID'], ACTION_ID_UPLOAD_GALLERY_PHOTO );
if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED && !$logged['admin'] && !$logged['moderator'] )
{
    $ret  = "
    	<table width=100% height=100% cellpadding=0 cellspacing=0 class=text2>
    		<td align=center bgcolor=$boxbg2>
    			". $check_res[CHECK_ACTION_MESSAGE] ."<br />
    		</td>
    	</table>\n";

	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = $ret;
	PageCode();
	exit();
}

$sStatus = '';
if (isset($_POST['upload']) && isset($_POST['medProfId']))
{
	$sStatus = '<div>'._t("_File was uploaded").'</div>';
	$iUser = (int)$_POST['medProfId'];
	$sFile = htmlspecialchars_adv($_POST['title']);
	$sDesc = isset($_POST['description']) && strlen($_POST['description']) ? process_db_input(htmlspecialchars_adv($_POST['description'])) : '';
	$sTags = isset($_POST['tags']) && strlen($_POST['tags']) ? process_db_input(htmlspecialchars_adv($_POST['tags'])) : '';
	$sStatus = uploadFile($sFile, $sDesc, $sTags, $iUser);
}

$_page_cont[$_ni]['page_main_code'] = $sStatus.PageMainCode();

PageCode();

function PageMainCode()
{
	global $site;
	global $member;
	
	$sCode = '<div id="agreement" style="text-align: center;"><div style="font-weight: bold;">'._t("_Media upload Agreement",_t("_Photo")).'</div><div><textarea rows="20" cols="80" readonly="true">'._t("_License Agreement",$site['url']).'</textarea></div><div><input type="submit" id="agree" value="'._t("_I agree").'" onclick="document.getElementById(\'uploadShareMain\').style.display = \'block\'; 
	document.getElementById(\'agreement\').style.display = \'none\';""></div></div>';
	$sCode .= '<div id="uploadShareMain" style="display: none;">';
	
	$sCode .= "<form enctype=\"multipart/form-data\" method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">";
	
	$sCode .= '<div class="uploadLine"><div class="uploadText">'._t("_Title").': </div><div><input type="text" name="title" class="uploadForm"/></div></div>';
	$sCode .= '<div class="uploadLine"><div class="uploadText">'._t("_Description").': </div><div><textarea name="description" class="uploadForm"/></textarea></div></div>';
	$sCode .= '<div class="uploadLine"><div class="uploadText">'._t("_Tags").': </div><div><input type="text" name="tags" class="uploadForm"/></div></div>';
	$sCode .= '<div class="uploadLine"><div class="uploadText">'._t("_Select").': </div><div><input type="file" name="uploadFile" size="43"/></div></div>';
	$sCode .= '<input type="hidden" name="medProfId" value="'.$member['ID'].'"/>';
	$sCode .= '<div class="uploadLine"><div style="text-align: center;"><input type="submit" name="upload" value="'._t("_Upload File").'"/></div></div>';
	
	$sCode .= '</form>';
	
	$sCode .= '</div>';
	
	return $sCode;
	
}

function uploadFile($sFile, $sDesc, $sTags, $iUser)
{
	global $dir;
	
	if( $_FILES['uploadFile']['error'] != 0 )
			$sCode = '<div class="uploadStatus">'._t("_File upload error").'</div>';
		else
		{
			$aFileInfo = getimagesize( $_FILES['uploadFile']['tmp_name'] );
			if( !$aFileInfo )
				$sCode = '<div class="uploadStatus">'._t("_You uploaded not image file").'</div>';
			else
			{
				$ext = false;
				switch( $aFileInfo['mime'] )
				{
					case 'image/jpeg': $ext = 'jpg'; break;
					case 'image/gif':  $ext = 'gif'; break;
					case 'image/png':  $ext = 'png'; break;
					default:           $ext = false;
				}
				
				if( !$ext )
					$sCode = '<div class="uploadStatus">'._t("_You uploaded not JPEG, GIF or PNG file").'</div>';
				else
				{
					$sCode = '<div class="uploadStatus">'._t("_Upload successful").'</div>';
					
					$sActive = getParam("enable_shPhotoActivation") == 'on' ? 'true' : 'false' ;
					$sQuery = "INSERT INTO `sharePhotoFiles` (`medProfId`,`medTitle`,`medExt`,`medDesc`,`medTags`,`medDate`,`Approved`) VALUES('$iUser','$sFile','$ext','$sDesc','$sTags',NOW(),'$sActive')";
					db_res($sQuery);
					$iNew = mysql_insert_id();
					reparseObjTags( 'photo', $iNew );

					$sNewFileName = $dir['sharingImages'] . $iNew.'.'.$ext;
					$sNewMainName = $dir['sharingImages'] . $iNew.'_m.'.$ext;
					$sNewThumbName = $dir['sharingImages'] . $iNew.'_t.'.$ext;
							
					if( !move_uploaded_file( $_FILES['uploadFile']['tmp_name'], $sNewFileName ))
						$sCode = '<div class="uploadStatus">'._t("_Couldn\'t move file").'</div>';
					else
					{
							chmod( $sNewFileName, 0644 );
							$iWidth  = (int)getParam("max_photo_width");
							$iHeight = (int)getParam("max_photo_height");
							
							$iThumbW = (int)getParam("max_thumb_width");
							$iThumbH = (int)getParam("max_thumb_height");
							
							if( imageResize( $sNewFileName, $sNewMainName, $iWidth, $iHeight ) != IMAGE_ERROR_SUCCESS)
							{
								$sCode = '<div class="uploadStatus">'._t("_Upload failed").'</div>';
							}	
							else
							{
								imageResize( $sNewMainName, $sNewThumbName, $iThumbW, $iThumbH );
								header("Location:viewPhoto.php?fileID=".$iNew);
								exit;
							}	
					}
				}
			}
		}
	return $sCode;
}

?>