<?
require_once("../inc/header.inc.php");
require_once( BX_DIRECTORY_PATH_INC . 'tags.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

function parseTags($iId)
{
	reparseObjTags( 'music', $iId );
}

function genUri($s)
{
	return uriGenerate($s, MODULE_DB_PREFIX.'Files', 'Uri', 255);
}

?>