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

$_page['name_index']	= 82;
$_page['css_name']		= 'viewMusic.css';

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


$_page['header'] = _t( "_upload Music" );
$_page['header_text'] = _t("_upload Music");

$_ni = $_page['name_index'];

$member['ID'] = (int)$_COOKIE['memberID'];
$member['Password'] = $_COOKIE['memberPassword'];

$check_res = checkAction( $member['ID'], ACTION_ID_UPLOAD_GALLERY_MUSIC );
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

$_page_cont[$_ni]['page_main_code'] = PageMainCode();

PageCode();

function PageMainCode()
{
	global $site;
	global $member;
	
	$sCode = '<div id="agreement" style="text-align: center;"><div style="font-weight: bold;">'._t("_Media upload Agreement",_t("_Music")).'</div><div><textarea rows="20" cols="80" readonly="true">'._t("_License Agreement",$site['url']).'</textarea></div><div><input type="submit" id="agree" value="'._t("_I agree").'" onclick="document.getElementById(\'uploadForm\').style.display = \'block\'; 
	document.getElementById(\'agreement\').style.display = \'none\';""></div></div>';
	$sCode .= '<div id="uploadForm" style="text-align: center; display: none;">'.getApplicationContent('music','editor',array('id' => $member['ID'],'password'=>$member['Password'])).'</div>';
	
	return $sCode;
}

?>