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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'params.inc.php' );

$logged['admin'] = member_auth( 1, true, true );
$ADMIN = $logged[admin];

// ===================================================================
// ===================================================================

if ( $_POST['confirm'] )
{
    $query = '';
    foreach ( $_POST as $key => $value )
    {

	if ( 'mem' == $value )
	    $query .= " IDMember = '$key' OR ";
    }

    $query = "UPDATE ProfilesSettings SET `Status` = 'Active' WHERE " . $query . " '1'='0' ";

    db_res( $query );

}
elseif ( $_POST['delete'] )
{

    $query = '';
    foreach ( $_POST as $key => $value )
    {

	if ( 'mem' == $value )
	    $query .= " IDMember = '$key' OR ";
    }

    $query1 = "SELECT `BackgroundFilename` FROM ProfilesSettings WHERE " . $query . " '1'='0' ";
    $res = db_res( $query1 );
    while( $arr = mysql_fetch_array( $res ) )
    {
		if( file_exists($dir['profileBackground'] . $arr['BackgroundFilename']) )
			unlink($dir['profileBackground'] . $arr['BackgroundFilename']);
    }

    $query2 = "UPDATE ProfilesSettings SET `BackgroundFilename` = '' WHERE " . $query . " '1'='0' ";

    db_res( $query2 );

}

// ===================================================================
// - GET variables --------------
// ===================================================================

$page		= (int)$_GET[page];
$p_per_page	= (int)$_GET[p_per_page];

//$real_first_p = (int)($page - 1) * $p_per_page;
//$page_first_p = $real_first_p + 1;

$max_photo_height	= $max_photo_height + 2;
$max_photo_width	= $max_photo_width + 2;

if ( !$page )
    $page = 1;

if ( !$p_per_page )
    $p_per_page = 10;

// ------------------------------

if ( isset($_GET['iUser']) )
{
	$iUser = (int)$_GET['iUser'];
	$sqlUser = " AND `IDMember` = '$iUser'";
}

if (isset($_GET['status']) && $_GET['status'] == 'active')
{
	$sqlMain = "`Status` = 'Active'";
	$sConf   = '';
}
else
{
	$sqlMain = "( `Status` != 'Active' OR `Status` IS NULL )";
	$sConf   = '<td width="1">|</td>
	<td width="120"><input class=text type=submit name="confirm" value="Confirm"></td>';
}

// ------------------------------


$sql = "SELECT COUNT(*) FROM ProfilesSettings WHERE `BackgroundFilename` != '' && `BackgroundFilename` IS NOT NULL
	    && $sqlMain $sqlUser";
$p_num = db_arr( $sql );
$p_num = $p_num[0];
$pages_num = ceil( $p_num / $p_per_page );

$real_first_p = (int)($page - 1) * $p_per_page;
$page_first_p = $real_first_p + 1;

$result = db_res( "SELECT `IDMember`, `BackgroundFilename` FROM ProfilesSettings WHERE `BackgroundFilename` != '' && `BackgroundFilename` IS NOT NULL
	    && $sqlMain $sqlUser LIMIT $real_first_p, $p_per_page" );

$_page['header'] = "Profiles PostModeration";
$_page['header_text'] = "Profiles that need to be checked by admin";

$_page['js'] = 1;

TopCodeAdmin();

ContentBlockHead("Uploaded (and not yet approved) background images:");

?>

<center>
<?= ResNavigationRet( 'ProfilesUpper', 0 ); ?>
</center>

<form name="prf_form" method="post" action="<? echo $_SERVER["REQUEST_URI"]; ?>">
<?
//"

    $ret = '<div style="border: solid 0px #000000;">';


	if( 0 < mysql_num_rows($result) )
	{
	    while ( $arr = mysql_fetch_array( $result ) )
	    {

		$ret .= '<div style="float:left;margin-right:5px;margin-top:5px;border: solid 1px #9b9a9a;background-color: #edecec;">
			    <div style="position:relative; width:' . $max_photo_width . 'px; height:' . $max_photo_height . 'px;">
			    <div style="position:absolute; top:5px; left:5px;">
				<input type="checkbox" id="' . $arr['IDMember'] . '" value="mem" name="' . $arr['IDMember'] . '">
			    </div>
			    <label for="' . $arr['IDMember'] . '"><img src="' . $site['profileBackground'] . $arr['BackgroundFilename'] . '" title="'. $arr['IDMember'] . '" alt="background image" width="340" height="340"></label>
				</div>
			<div style="position:relative; text-align:center; background-color:#FFFFFF; line-height:20px; vertical-align:middle;"><a href="' . $site['url'] . 'profile.php?ID=' . $arr['IDMember'] . '" target="_blank">View Profile</a> |<a href="' . $site['url'] . 'pedit.php?ID=' . $arr['IDMember'] . '" target="_blank">Edit Profile<a></div>
			</div>';

	    }
	}
	else
	{
		$ret .= '<div style="text-align:center; line-height:25px; vertical-align:middle; background-color:#c2daeb; font-weight:bold;">There is nothing to approve </div>';
	}
    $ret .= '</div>';

    echo $ret;

    if ( $p_num )
    {
?>
<div style="clear:left;border: solid 0px #000000;">
<table class=text border=0 width=590 align=center>
<tr>
    <td width="65">&nbsp;<a href="javascript: void(0);" onclick="setCheckboxes( 'prf_form', true ); return false;">Check all</a>/</td>
	<td align="left" width="70"> <a href="javascript: void(0);" onclick="setCheckboxes( 'prf_form', false ); return false;">Uncheck all</a>&nbsp;</td>
    <td width="100">Selected images:</td>
    <td width="55"><input class=text type=submit name="delete" value="Delete"></td>
    <? echo "$sConf"; ?>
    <td>&nbsp;</td>
</tr>
</table>
</div>
<?
    }
?>

</form>

<center>
<?= ResNavigationRet( 'ProfilesLower', 0 ); ?>
</center>

<?
ContentBlockFoot();

BottomCode();
?>
