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
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );


function explodeTags( $text )
{
	//$text = preg_replace( '/[^a-zA-Z0-9_\'-]/', ' ', $text );
	
	$aTags = preg_split( '/[,\s]/', $text, 0, PREG_SPLIT_NO_EMPTY );
	
	foreach( $aTags as $iInd => $sTag )
	{
		if( strlen( $sTag ) < 3 )
			unset( $aTags[$iInd] );
		else
			$aTags[$iInd] = mb_strtolower( $sTag , 'UTF-8');
	}
	$aTags = array_unique( $aTags );
	$sTagsNotParsed = getParam( 'tags_non_parsable' );
	$aTagsNotParsed = preg_split( '/[, ]/', $sTagsNotParsed, 0, PREG_SPLIT_NO_EMPTY );
	
	$aTags = array_diff( $aTags, $aTagsNotParsed ); //drop non parsable tags
	
	return $aTags;
}

function storeTags( $iID, $sTags, $sType )
{
	$aTags = explodeTags( $sTags );
	db_res( "DELETE FROM `Tags` WHERE `ID` = $iID AND `Type` = '$sType'" ); //re-store if exist
	
	foreach( $aTags as $sTag )
	{
		$sTag = addslashes( $sTag );
		db_res( "INSERT INTO `Tags` VALUES ( '$sTag', $iID, '$sType' )", 0 );
	}
}


function reparseObjTags( $sType, $iID )
{
	$iID = (int)$iID;
	
	switch( $sType )
	{
		case 'profile': $sQuery = "SELECT `Tags`     FROM `Profiles`        WHERE `ID`     = $iID AND `Status`     = 'Active'  "; break;
		case 'photo':   $sQuery = "SELECT `medTags`  FROM `sharePhotoFiles` WHERE `medID`  = $iID AND `Approved`   = 'true'    "; break;
		case 'video':   $sQuery = "SELECT `Tags`     FROM `RayMovieFiles`   WHERE `ID`     = $iID AND `Approved`   = 'true'    "; break;
		case 'music':   $sQuery = "SELECT `Tags`     FROM `RayMusicFiles`   WHERE `ID`     = $iID AND `Approved`   = 'true'    "; break;
		case 'event':   $sQuery = "SELECT `Tags`     FROM `SDatingEvents`   WHERE `ID`     = $iID AND`Status`      = 'Active'  "; break;
		case 'blog':    $sQuery = "SELECT `Tags`     FROM `BlogPosts`       WHERE `PostID` = $iID AND `PostStatus` = 'approval'"; break;
		case 'ad':      $sQuery = "SELECT `Tags`     FROM `ClassifiedsAdvertisements` WHERE `ID` = $iID AND `status` = 'active'"; break;
		default: return;
	}
	
	db_res( "DELETE FROM `Tags` WHERE `ID` = $iID AND `Type` = '$sType'" );
	
	$sTags = db_value( $sQuery );
	if( !strlen( $sTags ) )
		return;
	
	$aTags = explodeTags( $sTags );
	
	if( !$aTags )
		return;
	
	foreach( $aTags as $sTag )
	{
		$sTag = addslashes( $sTag );
		db_res( "INSERT INTO `Tags` VALUES ( '$sTag', $iID, '$sType' )", 0 );
	}
}
?>