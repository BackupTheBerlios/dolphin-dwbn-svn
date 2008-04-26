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

$_page['name_index']	= 44;
$_page['css_name']		= 'explanation.css';

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

$_page['header'] = _t( "_Video Actions" );
$_page['header_text'] = _t("_Video Actions");

$_ni = $_page['name_index'];

$member['ID'] = (int)$_COOKIE['memberID'];

if (isset($_POST['fileID']) && isset($_POST['send']) && isset($_POST['email']))
{
	$iFile    = (int)$_POST['fileID'];
	$sEmail   = $_POST['email'];
	$sMessage = htmlspecialchars_adv($_POST['messageText']);

	$sCode .= sendFileInfo($iFile, $sEmail, $sMessage);
}

if (isset($_GET['action']) && isset($_GET['fileID']))
{
	$sAct = htmlspecialchars_adv($_GET['action']);
	$iFile = (int)$_GET['fileID'];
	switch ($sAct)
	{
		case 'favorite': $sCode .= addToFavorites($iFile); break;
		case 'share':    $sCode .= displaySubmitForm($iFile,'share'); break;
		case 'report':   $sCode .= displaySubmitForm($iFile,'report'); break;
	}
}

$_page_cont[$_ni]['page_main_code'] = DesignBoxContent( _t( '_Notification' ), $sCode, 1);

PageCode();

function addToFavorites($iFile)
{
	global $member;
	
	if ($iFile)
	{
		$sQuery = "SELECT * FROM `shareVideoFavorites` WHERE `medID`='$iFile' AND `userID`='{$member['ID']}'";
		$rCheck = db_res($sQuery);
		if (mysql_num_rows($rCheck) > 0)
		{
			$sCode = '<div class="mediaInfo">'._t('_File already is favorite').'</div>';
		}
		else
		{
			$sQuery = "INSERT INTO `shareVideoFavorites` (`medID`,`userID`,`favDate`) VALUES('$iFile','{$member['ID']}',NOW())";
			db_res($sQuery);
			$sCode = '<div class="mediaInfo">'._t("_File was added to favorite").'</div>';
		}
	}
	
	return $sCode;
}

function displaySubmitForm($iFile, $sAct ='')
{
	global $member;
	global $site;
	
	if ($iFile && strlen($sAct) > 0)
	{
		switch ($sAct)
		{
			case 'share' : 
				$sAddr  = '<div>'._t("_Enter email(s)").':</div><div><input type="text" size="40" name="email"></div>';
				$sSites = '<div style="margin-top:10px; margin-bottom:10px;">'.getSitesArray($iFile,'Video').'</div>';
				break;
			case 'report': 
				$sAddr  = '<input type="hidden" name="email" value="'.$site['email_notify'].'">';
				$sSites = '';
				break;
		}
		
		$sCode  = '<div class="mediaInfo">';
		$sCode .= '<form name="submitAction" method="post" action="'.$_SERVER['PHP_SELF'].'">';
		$sCode .= '<input type="hidden" name="fileID" value="'.$iFile.'">';
		
		$sCode .= $sAddr.$sSites;
		$sCode .= '<div>'._t("_Message text").'</div>';
		$sCode .= '<div><textarea cols="30" rows="10" name="messageText"></textarea></div>';
		$sCode .= '<div><input type="submit" size="15" name="send" value="Send">';
		$sCode .= '<input type="reset" size="15" name="send" value="Reset"></div>';
				
		$sCode .= '</form>';
		
		$sCode .= '</div>';
	}
	
	return $sCode;
}


function sendFileInfo($iFile, $sEmail, $sMessage)
{
	global $site;
	global $member;
	
	/*$sQuery = "SELECT `NickName` 
			   FROM `Profiles` 
			   WHERE `ID`='{$member['ID']}'";
	
	$aUser = db_arr($sQuery);*/
	
	$aUser = getProfileInfo( $member['ID'] );
	
	$sMailHeader		= "From: {$site['title']} <{$site['email_notify']}>";
	$sMailParameters	= "-f{$site['email_notify']}";
	
	$sMailHeader = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=UTF-8\r\n" . $sMailHeader;
	$sMailSubject = $aUser['NickName'].' shared a Video with you';
	
	 $sMailBody    = "Hello,\n
				{$aUser['NickName']} shared a video with you: <a href=\"{$site['url']}viewVideo.php?fileID=$iFile\">See it</a>\n
				$sMessage\n
				Regards";
	
	$aEmails = explode(",",$sEmail);
	foreach ($aEmails as $iKey => $sMail)
	{
		$sMail = trim($sMail);
		$iSendingResult = mail( $sMail, $sMailSubject, nl2br($sMailBody), $sMailHeader, $sMailParameters );
	}
	if ($iSendingResult)
	{
		$sCode = '<div class="mediaInfo">'._t("_File info was sent").'</div>';
	}
	return $sCode;
}

?>