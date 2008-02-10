<?
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

require_once( 'header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplVotingView.php" );

function defineTimeInterval($iTime)
{
	$iTime = time() - $iTime;
	if ( $iTime < 60 )
	  $sCode = "$iTime "._t("_seconds ago");
	else
	{
	  $iTime = round( $iTime / 60 ); // minutes
	  if ( $iTime < 60 )
	    $sCode = "$iTime "._t("_minutes ago");
	  else
	  {
	    $iTime = round( $iTime / 60 ); //hours
	    if ( $iTime < 24 )
			$sCode = "$iTime "._t("_hours ago");
	    else
	    {
		    $iTime = round( $iTime / 24 ); //days
		    $sCode = "$iTime "._t("_days ago");
	    }
	  }
	}
	
	return $sCode;
}


function deleteMedia($iFile, $sType, $sExt = '')
{
	global $dir;
	global $logged;
	
	$sType = $sType == 'Video' ? 'Movie' : $sType ;

	// delete voting
    require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolVoting.php' ); 

	if( $logged['admin'] )
	{
	
	}
	elseif( $logged['member'] )
	{
	  $iMemberID = (int)$_COOKIE['memberID'];
	  
	  if ($sType == 'Photo')
	  {
	  	$sTableName = "`share{$sType}Files`";
	  	$sQuery = "SELECT * FROM $sTableName WHERE `medID`='$iFile'";
	  }
	  else
	  {
	  	$sTableName = "`Ray{$sType}Files`";
	  	$sQuery = "SELECT `ID` as `medID`,
	  					  `Title` as `medTitle`,
	  					  `Description` as `medDesc`,
	  					  `Date` as `medDate`,
	  					  `Owner` as `medProfId`
	  			   FROM $sTableName 
	  			   WHERE `ID`='$iFile'";
	  }

	  $aFile = db_arr( $sQuery );

	  if( !$aFile )
	  	return false;
	
	  if( $aFile['medProfId'] != $iMemberID )
	   	return false;
	}
	else
	  return false;
	
	$aFName = array();
	switch ($sType)
	{
		case 'Music':
		  	$sTableName = "`Ray{$sType}Files`";
			$sModPath = 'ray/modules/music/files/';
            $aFName[] = $iFile.'.mp3';
            $oVoting = new BxDolVoting ('gmusic', 0, 0);
			$sTagsType = 'music';
			break;
		case 'Photo':
			$sTableName = "`share{$sType}Files`";
			$sModPath = 'media/images/sharingImages/';
			$aFName[] = $iFile.'.'.$sExt;
			$aFName[] = $iFile.'_t.'.$sExt;
            $aFName[] = $iFile.'_m.'.$sExt;
            $oVoting = new BxDolVoting ('gphoto', 0, 0);
			$sTagsType = 'photo';
			break;
		case 'Movie':
			$sTableName = "`Ray{$sType}Files`";
			$sModPath = 'ray/modules/movie/files/';
			$aFName[] = $iFile.'.jpg';
			$aFName[] = $iFile.'_small.jpg';
			$aFName[] = $iFile.'.flv';
            $aFName[] = $iFile.'.mpg';
            $oVoting = new BxDolVoting ('gvideo', 0, 0);
			$sTagsType = 'video';
			break;
	}

	foreach($aFName as $iK => $sVal)
	{
		$sFilePath = BX_DIRECTORY_PATH_ROOT.$sModPath.$sVal;
		@unlink($sFilePath);
    }    
	$sCond = $sType == 'Photo' ? " `medID`='$iFile'" : "`ID`='$iFile'" ;
    db_res("DELETE FROM $sTableName WHERE $sCond");
	
	reparseObjTags( $sTagsType, $iFile );
	
    $oVoting->deleteVotings ($iFile);
    header('Location:' . $_SERVER["HTTP_REFERER"]);
}

function deleteProfileGalleries($iUser)
{
	$aType = array(
	'Photo'=>array('medProfId','sharePhotoFiles'),
	'Video'=>array('Owner','RayMovieFiles'),
	'Music'=>array('Owner','RayMusicFiles')
	);
	
	foreach ($aType as $sKey=>$sVal)
	{
		$sqlQuery = "SELECT * FROM `{$sVal[1]}` WHERE `{$sVal[0]}`='$iUser'";
		$rFiles = db_res($sqlQuery);
		
		if (mysql_num_rows($rFiles))
		{
			while ($aFile = mysql_fetch_array($rFiles))
			{
				$iID = isset($aFile['medID']) ? $aFile['medID'] : $aFile['ID'] ;
				$sExt = isset($aFile['medExt']) ? $aFile['medExt'] : '';
				deleteMedia($iID, $sKey, $sExt);
			}
		}	
	}
}

function approveMedia($iFile, $sType)
{
	$sId = 'ID';
	switch ($sType)
	{
		case 'Photo':
			$sTableName = '`sharePhotoFiles`';
			$sId = 'med'.$sId;
			break;
		case 'Music':
			$sTableName = '`RayMusicFiles`';
			break;
		case 'Video':
			$sTableName = '`RayMovieFiles`';
			break;
	}
	$sqlQuery = "UPDATE $sTableName SET `Approved` = IF(`Approved`='true','false','true') WHERE `$sId`='$iFile'";
	db_res($sqlQuery);
	header('Location:' . $_SERVER["HTTP_REFERER"]);
}

function addMediaComment($iFile, $iUser, $sText, $sType)
{
	$sQuery = "INSERT INTO `share".$sType."Comments` (`medID`, `commDate`, `profileID`, `commText`) 
	VALUES('$iFile', NOW(), '$iUser','$sText')";
	
	db_res($sQuery);
}

function commentNavigation($iNumber,$iDivis, $iCurr = 0)
{
	global $site;
	global $aFile;
	
	$iPages = $iNumber >= 2 ? round($iNumber/2) : 1;

	$sCode = '<div id="commentNavigation">';

	for ($i = 1; $i < $iPages + 1; $i++)
	{
		$sCapt = $i == 1 ? _t("_Page").': ' : '' ;
		$sCode .= '<div class="commentNavUnit">'.$sCapt;
		$sLink =  $i != $iCurr ? '<a href="'.$_SERVER['PHP_SELF'].'?fileID='.$aFile['medID'].'&commPage='.$i.'">'.$i.'</a>' : $iCurr;
		$sCode .= $sLink.'</div>';
	}
	$sCode .= '<div class="clear_both"></div>';
	$sCode .= '</div>';
	
	return $sCode;
}

function getTagLinks($sTagList, $sType)
{
	global $site;
	
	if (strlen($sTagList))
	{
		$aTags = explode(' ', $sTagList);
		foreach ($aTags as $iKey => $sVal)
		{
			$sVal   = trim($sVal,',');
			$sCode .= '<a href="'.$site['URL'].'browse'.$sType.'.php?tag='.$sVal.'">'.$sVal.'</a> ';
		}
	}
	
	return $sCode;
}

function defineBrowseAction($sAct, $sType, $iUser = 0)
{
	global $member;
	
	$sqlQuery = '';
	switch ($sAct)
	{
		case 'fav':
			$sUserCond = $iUser != 0 ? " AND `userID`=$iUser" : "" ;
			$sqlQuery = "SELECT `medID` FROM `share".$sType."Favorites` WHERE 1 ".$sUserCond;
			$sType = $sType == 'Video' ? 'Movie' : $sType;

			if ($sType == 'Photo')
			{
				$sRes = " AND `share{$sType}Files`.`medID` IN(";
			}
			else
			{
				$sRes = " AND `Ray{$sType}Files`.`ID` IN(" ;
			}
			$rList = db_res($sqlQuery);
			
			while ($aList = mysql_fetch_row($rList))
			{
				$sParam .= $aList[0] . ',';
			}
			if (strlen($sParam) > 0)
			{
				$sRes = $sRes.trim($sParam,',').')';
			}
			else
			{
				$sRes =' AND 0';
			}
			break;
		case 'del':
			if (isset($_GET['fileID']))
			{
				$iFile = (int)$_GET['fileID'];
				deleteMedia($iFile, $sType);
			}
			$sRes = '';
			break;	
	}
	
	return $sRes;
}

function getSitesArray($iFile, $sType)
{
	global $site;
	
	$sLink = $site['url'].'viewMusic.php?fileID='.$iFile;
	$aSites = array(
		array(
		'image'=>'digg.png',
		'link'=>'http://digg.com/submit?phase=2&url='.$sLink
		),
		array(
		'image'=>'delicious.png',
		'link' =>'http://del.icio.us/post?url='.$sLink
		),
		array(
		'image'=>'blinklist.png',
		'link' =>'http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Url='.$sLink
		),
		array(
		'image'=>'furl.png',
		'link' =>'http://www.furl.net/storeIt.jsp?u='.$sLink
		),
		array(
		'image'=>'netscape.gif',
		'link' =>'http://www.netscape.com/submit/?U='.$sLink
		),
		array(
		'image'=>'newsvine.png',
		'link' =>'http://www.newsvine.com/_tools/seed&save?u='.$sLink
		),
		array(
		'image'=>'reddit.png',
		'link' =>'http://reddit.com/submit?url='.$sLink
		),
		array(
		'image'=>'shadows.png',
		'link' =>'http://www.shadows.com/features/tcr.htm?url='.$sLink
		),
		array(
		'image'=>'slashdot.png',
		'link' =>'http://slashdot.org/bookmark.pl?url='.$sLink
		),
		array(
		'image'=>'sphere.png',
		'link' =>'http://www.sphere.com/search?q=sphereit:'.$sLink
		),
		array(
		'image'=>'stumbleupon.png',
		'link' =>'http://www.stumbleupon.com/url/http'.$sLink
		),
		array(
		'image'=>'technorati.png',
		'link' =>'http://technorati.com/faves?add='.$sLink
		)
	);
	$sLink = '<a href="{Link}"><div class="shareLink" style="background-image:url(\'{Image}\')"></div></a>';

	foreach ($aSites as $iKey =>$sVal)
	{
		$sLinkCur = str_replace('{Image}', getTemplateIcon($sVal['image']),$sLink);
		$sLinkCur = str_replace('{Link}', $sVal['link'],$sLinkCur);
		$sCode   .= $sLinkCur;
	}
	$sCode .= '<div class="clear_both"></div>';
	
	return $sCode;
}

function PageCompSharePhotosContent($sCaption, $iID = 0)
{
	global $site;
	
	$max_num	= (int)getParam("top_photos_max_num");
	$mode		= process_db_input( getParam("top_photos_mode") );
	
	$mode = $_GET['shPhotosMode'];
	if( $mode != 'rand' && $mode != 'top' && $mode != 'last')
		$mode = 'last';
	
	$sqlSelect = "SELECT `medID`,
						 `medExt`,
						 `medTitle`";
	$sqlFrom  = " FROM `sharePhotoFiles`";
	if ($iID != 0)
	{
		$sqlWhere = " WHERE `Approved`='true' AND `medProfId`='$iID'";
	}	
	
	$menu = '<div class="dbTopMenu">';
	 foreach( array( 'last', 'top', 'rand' ) as $myMode )
	 {
	  switch ( $myMode )
	  {
	   case 'last':
	    if( $mode == $myMode )
	     $sqlOrder = "
	  		ORDER BY `medDate` DESC";
	     $modeTitle = _t('_Latest');
	   break;
	   case 'rand':
	    if( $mode == $myMode )
	     $sqlOrder = "
	  		ORDER BY RAND()";
	     $modeTitle = _t('_Random');
	   break;
	   case 'top':
	    if( $mode == $myMode )
	    {
			$oVotingView = new BxTemplVotingView ('gphoto', 0, 0);
			$aSql        = $oVotingView->getSqlParts('`sharePhotoFiles`', '`medID`');
			$sHow        = "DESC";
			$sqlOrderBy  = $oVotingView->isEnabled() ? "ORDER BY `voting_rate` $sHow, `voting_count` $sHow, `medDate` $sHow" : $sqlOrderBy ;
			$sqlFields   = $aSql['fields'];
			$sqlLJoin    = $aSql['join'];
	
		    $sqlSelect  .= $sqlFields;
		    $sqlFrom    .= $sqlLJoin;
		    $sqlOrder    = $sqlOrderBy;
	    }
	    $modeTitle = _t('_Top');
	   break;
	  }
	
	  if( $myMode == $mode )
	   		$menu .= "<div class=\"active\">$modeTitle</div>";
	  else
	  {
	  	if( basename( $_SERVER['PHP_SELF'] ) == 'rewrite_name.php' || basename( $_SERVER['PHP_SELF'] ) == 'profile.php' )
	  		$menu .= "<div class=\"notActive\"><a href=\"profile.php?ID={$iID}&shPhotosMode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_sharePhotos', this.href+'&amp;show_only=sharePhotos'); return false;\">$modeTitle</a></div>";
	  	else
	   		$menu .= "<div class=\"notActive\"><a href=\"{$_SERVER['PHP_SELF']}?shPhotosMode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_sharePhotos', this.href+'&amp;show_only=sharePhotos'); return false;\">$modeTitle</a></div>";
	  }
	 }
	 $menu .= '</div>';
	 
	$aNum = db_arr( "SELECT COUNT(`sharePhotoFiles`.`medID`) $sqlFrom $sqlWhere" );
	$num = (int)$aNum[0];
	
	$ret = '';
	if( $num )
	{
		$pages = ceil( $num / $max_num );
		$page = (int)$_GET['page_p'];
		
		if( $page < 1 or $mode == 'rand' )
			$page = 1;
		if( $page > $pages )
			$page = $pages;
		
		$sqlLimitFrom = ( $page - 1 ) * $max_num;
		$sqlLimit = "
		LIMIT $sqlLimitFrom, $max_num";
	 
	 $rData = db_res($sqlSelect.$sqlFrom.$sqlWhere.$sqlOrder.$sqlLimit);
	 
	 $ret .= '<div class="clear_both"></div>';
	 $iCounter = 1;
	 $sAddon = '';
	 while ($aData = mysql_fetch_array($rData))
	 {
	 	$sImage = $site['sharingImages'].$aData['medID'].'_t.'.$aData['medExt'];
		$oVotingView = new BxTemplVotingView ('gphoto', $aData['medID']);
	    if( $oVotingView->isEnabled())
	   	{
			$sRate = $oVotingView->getSmallVoting (0);
			$sShowRate = '<div class="galleryRate">'. $sRate . '</div>';
		}
		$sHref = $site['url'].'viewPhoto.php?fileID='.$aData['medID'];
		$sImg  = '<div class="lastFilesPic" style="background-image: url(\''.$sImage.'\');">
				  <a href="'.$sHref.'"><img src="'.$site['images'] .'spacer.gif" width="110" height="110"></a></div><div class="clear_both"></div>';
		if( ($iCounter % 3) != 0 )
			$ret .= '<div class="sharePhotosContent_1">';
		else
		{
			$ret .= '<div class="sharePhotosContent_2">';
			$sAddon = '<div class="clear_both"></div>';
		}
		$sTitle = strlen($aData['medTitle']) > 0 ? $aData['medTitle'] : _t("_Untitled");
		$ret .= $sImg.'<div><a href="'.$sHref.'">'.$sTitle.'</a></div>'.$sShowRate.'</div>';
		$ret .= $sAddon;
		$sAddon = '';
		$iCounter++;
	 }
	 
	 $ret .= '<div class="clear_both"></div>';
	 $ret = DesignBoxContent( _t( $sCaption ), $ret, 1, $menu );
	} 

 return $ret;
}

function PageCompShareVideosContent($sCaption, $iID = 0)
{
	global $site;
	
	$max_num	= (int)getParam("top_photos_max_num");
	$mode		= process_db_input( getParam("top_photos_mode") );
	
	$mode = $_GET['shVideosMode'];
	if( $mode != 'rand' && $mode != 'top' && $mode != 'last')
		$mode = 'last';
	
	$sqlSelect = "SELECT `ID` as `medID`,
						 `Title` as `medTitle`,
						 `Date` as `medDate`
						 ";
	$sqlFrom  = " FROM `RayMovieFiles`";
	if ($iID != 0)
	{
		$sqlWhere = " WHERE `Approved`='true' AND `Owner`='$iID'";
	}	
	
	$menu = '<div class="dbTopMenu">';
	 foreach( array( 'last', 'top', 'rand' ) as $myMode )
	 {
	  switch ( $myMode )
	  {
	   case 'last':
	    if( $mode == $myMode )
	     $sqlOrder = "
	  		ORDER BY `medDate` DESC";
	     $modeTitle = _t('_Latest');
	   break;
	   case 'rand':
	    if( $mode == $myMode )
	     $sqlOrder = "
	  		ORDER BY RAND()";
	     $modeTitle = _t('_Random');
	   break;
	   case 'top':
	    if( $mode == $myMode )
	    {
			$oVotingView = new BxTemplVotingView ('gvideo', 0, 0);
			$aSql        = $oVotingView->getSqlParts('`RayMovieFiles`', '`ID`');
			$sHow        = "DESC";
			$sqlOrderBy  = $oVotingView->isEnabled() ? "ORDER BY `voting_rate` $sHow, `voting_count` $sHow, `medDate` $sHow" : $sqlOrderBy ;
			$sqlFields   = $aSql['fields'];
			$sqlLJoin    = $aSql['join'];
	
		    $sqlSelect  .= $sqlFields;
		    $sqlFrom    .= $sqlLJoin;
		    $sqlOrder    = $sqlOrderBy;
	    }
	    $modeTitle = _t('_Top');
	   break;
	  }
	
	  if( $myMode == $mode )
	   		$menu .= "<div class=\"active\">$modeTitle</div>";
	  else
	  {
	  	if( basename( $_SERVER['PHP_SELF'] ) == 'rewrite_name.php' || basename( $_SERVER['PHP_SELF'] ) == 'profile.php' )
	  		$menu .= "<div class=\"notActive\"><a href=\"profile.php?ID={$iID}&shVideosMode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_shareVideos', this.href+'&amp;show_only=shareVideos'); return false;\">$modeTitle</a></div>";
	  	else
	   		$menu .= "<div class=\"notActive\"><a href=\"{$_SERVER['PHP_SELF']}?shVideosMode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_shareVideos', this.href+'&amp;show_only=shareVideos'); return false;\">$modeTitle</a></div>";
	  }
	 }
	 $menu .= '</div>';
	 
	$aNum = db_arr( "SELECT COUNT(`RayMovieFiles`.`ID`) $sqlFrom $sqlWhere" );
	$num = (int)$aNum[0];
	
	$ret = '';
	if( $num )
	{
		$pages = ceil( $num / $max_num );
		$page = (int)$_GET['page_p'];
		
		if( $page < 1 or $mode == 'rand' )
			$page = 1;
		if( $page > $pages )
			$page = $pages;
		
		$sqlLimitFrom = ( $page - 1 ) * $max_num;
		$sqlLimit = "
		LIMIT $sqlLimitFrom, $max_num";
	 
	 $rData = db_res($sqlSelect.$sqlFrom.$sqlWhere.$sqlOrder.$sqlLimit);
	 
	 $ret .= '<div class="clear_both"></div>';
	 
	 $iCounter = 1;
	 $sAddon = '';
	 $ret .= '<div class="clear_both"></div>';
	 while ($aData = mysql_fetch_array($rData))
	 {
	 	$sHref = $site['url'].'viewVideo.php?fileID='.$aData['medID'];
	 	$sVidTitle = strlen($aData['medTitle']) > 0 ? $aData['medTitle'] : _t("_Untitled");
	 	$sImg  = '<a href="'.$sHref.'"><img src="'.$site['url'].'ray/modules/movie/files/'.$aData['medID'].'.jpg" width="112px" height="80px"></a>';
		
	 	$oVotingView = new BxTemplVotingView ('gvideo', $aData['medID']);
	    if( $oVotingView->isEnabled())
	   	{
			$sRate = $oVotingView->getSmallVoting (0);
			$sShowRate = '<div class="galleryRate">'. $sRate . '</div>';
		}

		if( ($iCounter % 3) != 0 )
			$ret .= '<div class="sharePhotosContent_1">';
		else
		{
			$ret .= '<div class="sharePhotosContent_2">';
			$sAddon = '<div class="clear_both"></div>';
		}
		$ret .= '<div class="lastVideoPic">'.$sImg.'</div>';
		$ret .= '<div><a href="'.$sHref.'">'.$sVidTitle.'</a></div>';
		$ret .= $sShowRate.'</div>';
		$ret .= $sAddon;
		$sAddon = '';
		$iCounter++;
	 }
	 
	 $ret .= '<div class="clear_both"></div>';
	 $ret = DesignBoxContent( _t( $sCaption ), $ret, 1, $menu );
	} 

 return $ret;
}

function PageCompShareMusicContent($sCaption, $iID = 0)
{
	global $site;
	
	$max_num	= (int)getParam("top_photos_max_num");
	$mode		= process_db_input( getParam("top_photos_mode") );
	
	$mode = $_GET['shMusicMode'];
	if( $mode != 'rand' && $mode != 'top' && $mode != 'last')
		$mode = 'last';
	
	$sqlSelect = "SELECT `ID` as `medID`,
						 `Title` as `medTitle`,
						 `Date` as `medDate`
						 ";
	$sqlFrom  = " FROM `RayMusicFiles`";
	if ($iID != 0)
	{
		$sqlWhere = " WHERE `Approved`='true' AND `Owner`='$iID'";
	}	
	
	$menu = '<div class="dbTopMenu">';
	 foreach( array( 'last', 'top', 'rand' ) as $myMode )
	 {
	  switch ( $myMode )
	  {
	   case 'last':
	    if( $mode == $myMode )
	     $sqlOrder = "
	  		ORDER BY `medDate` DESC";
	     $modeTitle = _t('_Latest');
	   break;
	   case 'rand':
	    if( $mode == $myMode )
	     $sqlOrder = "
	  		ORDER BY RAND()";
	     $modeTitle = _t('_Random');
	   break;
	   case 'top':
	    if( $mode == $myMode )
	    {
			$oVotingView = new BxTemplVotingView ('gmusic', 0, 0);
			$aSql        = $oVotingView->getSqlParts('`RayMusicFiles`', '`ID`');
			$sHow        = "DESC";
			$sqlOrderBy  = $oVotingView->isEnabled() ? "ORDER BY `voting_rate` $sHow, `voting_count` $sHow, `medDate` $sHow" : $sqlOrderBy ;
			$sqlFields   = $aSql['fields'];
			$sqlLJoin    = $aSql['join'];
	
		    $sqlSelect  .= $sqlFields;
		    $sqlFrom    .= $sqlLJoin;
		    $sqlOrder    = $sqlOrderBy;
	    }
	    $modeTitle = _t('_Top');
	   break;
	  }
		
	  //if( $_SERVER['PHP_SELF'] == 'rewrite_name.php' )
	  
	  if( $myMode == $mode )
	   		$menu .= "<div class=\"active\">$modeTitle</div>";
	  else
	  {
	  	if( basename( $_SERVER['PHP_SELF'] ) == 'rewrite_name.php' || basename( $_SERVER['PHP_SELF'] ) == 'profile.php' )
	  		$menu .= "<div class=\"notActive\"><a href=\"profile.php?ID={$iID}&shMusicMode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_shareMusic', this.href+'&amp;show_only=shareMusic'); return false;\">$modeTitle</a></div>";
	  	else
	   		$menu .= "<div class=\"notActive\"><a href=\"{$_SERVER['PHP_SELF']}?shMusicMode=$myMode\" class=\"top_members_menu\" onclick=\"getHtmlData( 'show_shareMusic', this.href+'&amp;show_only=shareMusic'); return false;\">$modeTitle</a></div>";
	  }
	   	
	 }
	 $menu .= '</div>';
	 
	$aNum = db_arr( "SELECT COUNT(`RayMusicFiles`.`ID`) $sqlFrom $sqlWhere" );
	$num = (int)$aNum[0];
	
	$ret = '';
	if( $num )
	{
		$pages = ceil( $num / $max_num );
		$page = (int)$_GET['page_p'];
		
		if( $page < 1 or $mode == 'rand' )
			$page = 1;
		if( $page > $pages )
			$page = $pages;
		
		$sqlLimitFrom = ( $page - 1 ) * $max_num;
		$sqlLimit = "
		LIMIT $sqlLimitFrom, $max_num";
	 
	 $rData = db_res($sqlSelect.$sqlFrom.$sqlWhere.$sqlOrder.$sqlLimit);
	 
	 $sAddon = '';
	 $iCounter = 1;
	 $ret .= '<div class="clear_both"></div>';
	 while ($aData = mysql_fetch_array($rData))
	 {
	 	$sHref = $site['url'].'viewMusic.php?fileID='.$aData['medID'];
	 	$sVidTitle = strlen($aData['medTitle']) > 0 ? $aData['medTitle'] : _t("_Untitled");
		$sImg = '<a href="'.$sHref.'"><img src="'.$site['images'].'music.png"></a>';
		
	 	$oVotingView = new BxTemplVotingView ('gmusic', $aData['medID']);
	    if( $oVotingView->isEnabled())
	   	{
			$sRate = $oVotingView->getSmallVoting (0);
			$sShowRate = '<div class="galleryRate">'. $sRate . '</div>';
		}
		
		if( ($iCounter % 3) != 0 )
			$ret .= '<div class="shareMusicContent_1">';
		else
		{
			$ret .= '<div class="shareMusicContent_2">';
			$sAddon = '<div class="clear_both"></div>';			
		}
		$ret .= '<div class="lastMusicPic">'.$sImg.'</div>';
		$ret .= '<div><a href="'.$sHref.'">'.$sVidTitle.'</a></div>';
		$ret .= $sShowRate.'</div>';
		$ret .= $sAddon;
		$sAddon = '';
		$iCounter++;
	 }
	 
	 $ret .= '<div class="clear_both"></div>';
	 $ret = DesignBoxContent( _t( $sCaption ), $ret, 1, $menu );
	} 

 return $ret;
}

?>