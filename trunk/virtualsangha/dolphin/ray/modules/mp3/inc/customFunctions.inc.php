<?
require_once("../inc/header.inc.php");
require_once( BX_DIRECTORY_PATH_INC . 'tags.inc.php' );

function parseTags($iId)
{
	reparseObjTags( 'music', $iId );
}
?>