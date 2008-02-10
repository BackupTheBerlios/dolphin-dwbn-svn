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

$_page['name_index']	= 81;
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


$_page['header'] = _t( "_view Photo" );

$_ni = $_page['name_index'];

$member['ID'] = (int)$_COOKIE['memberID'];

$iFile = isset($_GET['fileID']) ? (int)$_GET['fileID'] : 0;

if (isset($_POST['commentAdd']) && isset($_POST['commentText']) && strlen($_POST['commentText']) > 0)
{
	$iFileID = (int)$_POST['fileID'];
	$iUser = (int)$_POST['profileID'];
	$sText = htmlspecialchars_adv($_POST['commentText']);
	addMediaComment($iFileID, $iUser, $sText,'Photo');
	header('location:' . $_SERVER['PHP_SELF'].'?fileID='.$_POST['fileID']);
}

	$sQuery = "
		SELECT  `sharePhotoFiles`.*,
				UNIX_TIMESTAMP(`sharePhotoFiles`.`medDate`) as `medDate`,
				COUNT( `share1`.`medID` ) AS `medCount`,
				`Profiles`.`NickName`
		FROM `sharePhotoFiles`
		LEFT JOIN `sharePhotoFiles` AS `share1` USING ( `medProfId` )
		INNER JOIN `Profiles` ON `Profiles`.`ID`=`sharePhotoFiles`.`medProfId`
		WHERE `sharePhotoFiles`.`medID` = $iFile
		GROUP BY `share1`.`medProfId`
	";

$aFile = db_arr($sQuery);

$check_res = checkAction( $member['ID'], ACTION_ID_VIEW_GALLERY_PHOTO );
if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED && !$logged['admin'] && !$logged['moderator'] && $aFile['medProfId'] != $member['ID'])
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

if (is_array($aFile))
{
	$_page['header'] = $aFile['medTitle'];

	db_res("UPDATE `sharePhotoFiles` SET `medViews` = `medViews` + 1 WHERE `medID`='$iFile'");
	
	$_page_cont[$_ni]['pageSet1'] = PageCompCreateBlocks(1);
	$_page_cont[$_ni]['pageSet2'] = PageCompCreateBlocks(2);
	PageCode();
}
else
{
	$sCode = MsgBox( _t( '_No file' ) );
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = $sCode;
	PageCode();
	exit();
}


/*---------------------------- functions of block drawing ---------------------------------------------*/

function PageCompCreateBlocks($iCol = 1)
{
	global $logged;
	
	if( $logged['member'] )
		$sVisible = 'memb';
	else
		$sVisible = 'non';
	
	$sCode = '';
	$sPos = ' style = "float: left;"';
	
	$sQuery = "SELECT * FROM `sharePhotoCompose` WHERE `Column`='$iCol' AND FIND_IN_SET( '$sVisible', `Visible` ) ORDER BY `Order`";
	$rCompose = db_res($sQuery);
	while ($aCompose = mysql_fetch_array($rCompose))
	{
		$func  = 'PageComp' . $aCompose['Func'];
		$sFunc = $func( $aCompose['Content'] );
		$sCode .= DesignBoxContent(_t($aCompose['Caption']),$sFunc,1);
	}

	return "<div id=\"col$iCol\"".$sPos.">".$sCode."</div>";
}

function PageCompRSS( $sContent )
{
	list( $sUrl, $iNum ) = explode( '#', $sContent );
	$iNum = (int)$iNum;
	
	return genRSSHtmlOut( $sUrl, $iNum );
}

function PageCompEcho( $sContent )
{
	return $sContent;
}

function PageCompViewFile()
{
	global $aFile;
	global $site;
	
	$sImage = $site['sharingImages'].$aFile['medID'].'_m.'.$aFile['medExt'];
	$sCode = '<div id="viewFile" style="background-image: url(\''.$sImage.'\')">&nbsp;</div>';
		
	return $sCode;
}

function PageCompActionList()
{
	global $site;
	global $aFile;
	
	$sMain = 'viewPhoto.php?fileID='.$aFile['medID'];
	
    $sOnclick = "javascript: window.open( 'photoActions.php?fileID={$aFile['medID']}&{action}', 'photo', 'width=500, height=380, menubar=no,status=no,resizable=yes,scrollbars=yes,toolbar=no,location=no' );";
	$aActions = array(
		'Fave'=>array('icon'=>'action_fave.gif','link'=>'javascript:void(0);','onClick'=>str_replace('{action}','action=favorite',$sOnclick)),
		'Share'=>array('icon'=>'action_share.gif','link'=>'javascript:void(0);','onClick'=>str_replace('{action}','action=share',$sOnclick)),
		'Report'=>array('icon'=>'action_report.gif','link'=>'javascript:void(0);','onClick'=>str_replace('{action}','action=report',$sOnclick)),
		'Original_Size'=>array('icon'=>'action_download.gif','link'=>$site['sharingImages'].$aFile['medID'].'.'.$aFile['medExt'],'onClick'=>'')
		);

	$sCode = '<div id="actionList">';
	foreach ($aActions as $sKey => $sVal)
	{
		$sCode .= '<div><img src="'.$site['icons'].$sVal['icon'].'"><a href="'.$sVal['link'].'" onclick="'.$sVal['onClick'].'">'._t('_'.$sKey).'</a></div>';
	}
	$sCode .= '</div><div class="clear_both"></div>';
	
	return $sCode;
}

function PageCompRate()
{
    global $iFile;

	$sCode = '<center>' . _t('_Gallery photos rating is not enabled') . '</center>';

    $oVotingView = new BxTemplVotingView ('gphoto', (int)$iFile);
    if( $oVotingView->isEnabled())
        $sCode = $oVotingView->getBigVoting ();

	return $sCode;
}

function PageCompViewComments()
{
	global $site;
	global $aFile;
	global $member;
	global $logged;
	
	$iDivis = 2;
	$iCurr  = 1;
	
	if (!isset($_GET['commPage']))
	{
		$sLimit =  ' LIMIT 0,'.$iDivis;
	}
	else
	{
		$iCurr = (int)$_GET['commPage'];
		$sLimit =  ' LIMIT '.($iCurr - 1)*$iDivis.','.$iDivis;
	}
	
	$sQuery = "SELECT UNIX_TIMESTAMP(`commDate`) AS `commDate`,
					  `commText`,
					  `profileID`,
					  `Profiles`.`NickName`
					  FROM
					  `sharePhotoComments`
					  INNER JOIN `Profiles` ON `Profiles`.`ID`=`sharePhotoComments`.`profileID`
					  WHERE `medID`='{$aFile['medID']}' ORDER BY `commDate` DESC";

	$rComments = db_res($sQuery);
	$iNums = mysql_num_rows($rComments);
	$sNav = $iNums > $iDivis ? commentNavigation($iNums,$iDivis,$iCurr) : '';
	
	$sQuery .= $sLimit;

	$rComments = db_res($sQuery);
	
	$sCode = '<div id="comments">';
	
	while($aComments = mysql_fetch_array($rComments))
	{
		$sCode .= '<div class="commentUnit">';
			$sCode .= '<div class="userPic">'.get_member_icon($aComments['profileID'],'left').'</div>';
			$sCode .= '<div class="commentMain"><div class="commentInfo"><a href="'.getProfileLink($aComments['profileID']).'">'.$aComments['NickName'].'</a> ';
			$sCode .= '(' . defineTimeInterval($aComments['commDate']).')</div>';
			$sCode .= '<div class="commentText">'.$aComments['commText'].'</div></div>';
			$sCode .= '<div class="clear_both"></div>';
		$sCode .= '</div>';	
	}
	
	if ( $logged['member'] )
	{
		$sCode .= '<div id="commentArea"><div><a id="commentLink" onClick="document.getElementById(\'answerFormTo\').style.display = \'block\'; 
		this.style.display = \'none\';" href="javascript:void(0);">'._t("_Post Comment").'</a>'.'</div>';
		$sCode .= '
			<div id="answerFormTo" style="display:none;">
				<form method="post" id="postForm" action="'.$_SERVER['PHP_SELF'].'">
					<div>'._t("_Post Comment").'</div>
					<div>
						<textarea name="commentText" cols="50" rows="10"></textarea>
					</div>			
					<input type="hidden" name="profileID" value="'.$member['ID'].'">
					<input type="hidden" name="fileID" value="'.$aFile['medID'].'">
					<input type="submit" name="commentAdd" value="Post">
					<input type="button" value="Cancel" onClick="
					javascript: document.getElementById(\'answerFormTo\').style.display = \'none\';
					document.getElementById(\'commentLink\').style.display = \'block\'">
				</form>
			</div>
		</div>';
	}

	$sCode .= $sNav;
	$sCode .= '</div>';
	
	return $sCode;
}

function PageCompFileInfo()
{
	global $site;
	global $aFile;
	
	if ($aFile['medCount'] - 1 > 0)
	{
		$sLinkMore = '<a href="browsePhoto.php?userID='.$aFile['medProfId'].'">'.$aFile['medCount'].'</a>';
	}
	else
	{
		$sLinkMore = $aFile['medCount'];
	}
	
	$sImage = '<img src="'.$site['sharingImages'].$aFile['medID'].'.'.$aFile['medExt'].'">';
	$sTitle = strlen($aFile['medTitle']) > 0 ? $aFile['medTitle'] : _t("_Untitled");
	$sCode .= '<div id="videoInfo">';
	$sCode .= '<div id="fileTop">';
		$sCode .= '<div class="fileTitle">'.$sTitle.'</div>';
		$sCode .= '<div class="userPic" style="">'.get_member_icon($aFile['medProfId'],'left').'</div>';
		$sCode .= '<div class="fileUserInfo"><a href="'.getProfileLink($aFile['medProfId']).'">'.$aFile['NickName'].'</a></div>';
		$sCode .= '<div>'._t("_Photos").': <b>'.$sLinkMore.'</b></div>';
			
	$sCode .= '</div>';
	$sCode .= '<div class="clear_both"></div>';
			
	$sCode .= '<div id="serviceInfo">';
			
		$sCode .= '<div>'._t("_Added").': <b>'.defineTimeInterval($aFile['medDate']).'</b></div>';
		$sCode .= '<div>'._t("_Views").': '.$aFile['medViews'].'</div>';
		$sCode .= '<div>'._t("_URL").': <input type="text" onClick="this.focus(); this.select();" readonly="true" value="'.$site['url'].'viewPhoto.php?fileID='.$aFile['medID'].'"/></div>';
		$sCode .= '<div>'._t("_Embed").' : <input type="text" onClick="this.focus(); this.select();" readonly="true" value="'.htmlspecialchars($sImage).'"></div>';
		$sCode .= '<div>'._t("_Tags").': '.getTagLinks($aFile['medTags'],'Photo').'</div>';
		$sCode .= '<div>'._t("_DescriptionMedia").': '.$aFile['medDesc'].'</div>';
			
	$sCode .= '</div>';
		
	$sCode .= '</div>';

	return $sCode;
}

function PageCompLastFiles()
{
	global $site;
	global $aFile;
	
	$iLimit = 2;
	
	$sQuery = "SELECT `medID`,
					  `medTitle`,
					  UNIX_TIMESTAMP(`medDate`) as `medDate`,
					  `medExt`,
					  `medViews`
					  FROM `sharePhotoFiles` 
					  WHERE `medProfId`='{$aFile['medProfId']}' 
					  AND `medID`<>'{$aFile['medID']}' AND `Approved`='true' ORDER BY `medDate` DESC LIMIT $iLimit";
	$rLast = db_res($sQuery);
	
	$sLinkMore =  '';
	if ($aFile['medCount'] - 1 > $iLimit)
	{
		$sLinkMore = '<a href="browsePhoto.php?userID='.$aFile['medProfId'].'">'._t("_See all photos of this user").'</a>';
	}
	$sCode = '<div id="lastFiles">';
	
	while ($aLast = mysql_fetch_array($rLast))
	{
		$sImage = $site['sharingImages'].$aLast['medID'].'_t.'.$aLast['medExt'];
		$sTitle = strlen($aLast['medTitle']) > 0 ? $aLast['medTitle'] : _t("_Untitled");
		
		$oVotingView = new BxTemplVotingView ('gphoto', $aLast['medID']);
	    if( $oVotingView->isEnabled() )
    	{
			$sRate = $oVotingView->getSmallVoting(0);
			$sShowRate = '<div class="galleryRate">'. $sRate . '</div>';
		}

		$sCode .= '<div class="lastFileUnit">';
			
			$sCode .= '<a href="'.$site['url'].'viewPhoto.php?fileID='.$aLast['medID'].'">';
				$sCode .= '<img class="lastFilesPic" style="background-image: url(\''.$sImage.'\');" src="' .
				  getTemplateIcon( 'spacer.gif' ) . '" />';
			$sCode .= '</a>';
			
			$sCode .= '<div><a href="'.$site['url'].'viewPhoto.php?fileID='.$aLast['medID'].'"><b>'.$sTitle.'</b></a></div>';
			$sCode .= '<div>'._t("_Added").': <b>'.defineTimeInterval($aLast['medDate']).'</b></div>';
			$sCode .= '<div>'._t("_Views").': <b>'.$aLast['medViews'].'</b></div>';
			$sCode .= $sShowRate;
		$sCode .= '</div>';
		$sCode .= '<div class="clear_both"></div>';
	}
	$sCode .= '<div class="lastFilesLink">'.$sLinkMore.'</div>';
	$sCode .= '</div>';
	
	return $sCode;
}

?>
