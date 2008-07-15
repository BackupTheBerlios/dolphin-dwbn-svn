<?

require_once( './inc/header.inc.php' );
require_once( './inc/db.inc.php' );
require_once( './inc/profiles.inc.php' );

$sQuery = "SELECT `Content` FROM `PageCompose` WHERE `ID` = " . (int)$_GET['ID'];
$sCont = db_value( $sQuery );

if( !$sCont )
	exit;

list( $sUrl ) = explode( '#', $sCont );
$sUrl = str_replace( '{SiteUrl}', $site['url'], $sUrl );

$iMemID = (int)$_GET['member'];
if( $iMemID ) {
	$aMember = getProfileInfo( $iMemID );
	$sUrl = str_replace( '{NickName}', $aMember['NickName'], $sUrl );
}

header( 'Content-Type: text/xml' );
readfile( $sUrl );
