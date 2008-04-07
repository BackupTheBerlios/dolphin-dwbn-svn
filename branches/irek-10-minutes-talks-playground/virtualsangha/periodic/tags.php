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

require_once( '/Library/WebServer/Documents/inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'tags.inc.php' );


$sLastParseTime = getParam( 'tags_last_parse_time' ); //get last parsing time
db_res( "UPDATE `GlParams` SET `VALUE` = NOW() WHERE `NAME` = 'tags_last_parse_time'" ); //save last parsing time

if( (int)$sLastParseTime == 0 )
	$sLastParseTimeTS = 0;
else
	$sLastParseTimeTS = strtotime( $sLastParseTime );

$rMembers = db_res( "SELECT `ID`,            `Tags` AS `Tags` FROM `Profiles`        WHERE `LastModified` >= '$sLastParseTime' OR `LastReg` >= '$sLastParseTime' AND `Status` = 'Active'" );
//$rPhotos  = db_res( "SELECT `medID` AS `ID`, `medTags`  AS `Tags` FROM `sharePhotoFiles` WHERE `medDate`      >= '$sLastParseTime'  AND `Approved` = 'true'" );
//$rVideos  = db_res( "SELECT `ID`,            `Tags`               FROM `RayMovieFiles`   WHERE `Date`         >=  $sLastParseTimeTS AND `Approved` = 'true'" );
//$rMusics  = db_res( "SELECT `ID`,            `Tags`               FROM `RayMusicFiles`   WHERE `Date`         >=  $sLastParseTimeTS AND `Approved` = 'true'" );
//$rBlogs   = db_res( "SELECT `PostID` AS `ID`,`Tags`               FROM `BlogPosts`       WHERE `PostDate`     >= '$sLastParseTime'  AND `PostStatus` = 'approval'" );
//$rAds     = db_res( "SELECT `ID`,            `Tags`               FROM `ClassifiedsAdvertisements` WHERE `DateTime` >= '$sLastParseTime' AND `status` = 'active'" );
//$rEvents  = db_res( "SELECT `ID`,            `Tags`               FROM `SDatingEvents`   WHERE `Status` = 'Active'" );


$aObjs = array(
	'profile' => 'rMembers'
	//'photo'   => 'rPhotos',
	//'video'   => 'rVideos',
	//'music'   => 'rMusics',
	//'blog'    => 'rBlogs',
	//'ad'      => 'rAds',
	//'event'   => 'rEvents'
	);
	
foreach( $aObjs as $sType => $sResource )
{
	//echo $sResource . ' ' . $$sResource .'<hr>';
	while( $aObj = mysql_fetch_assoc( $$sResource ) )
	{
		storeTags( $aObj['ID'], $aObj['Tags'], $sType );
	}
}

?>