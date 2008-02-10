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
require_once( BX_DIRECTORY_PATH_INC . 'modules.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$ADMIN = member_auth( 1 );
$logged['admin'] = $ADMIN;

$_page['header'] = "Modules";
$_page['header_text'] = "Modules";


TopCodeAdmin();
ContentBlockHead("Please Pay Attention");
?>
<span style="font-weight:bold; ">IMPORTANT.</span> You need to have Ray Pro to be able to use all components listed below.
<?
ContentBlockFoot();
ContentBlockHead("Ray components");

$accredit = db_arr( "SELECT `Name`, `Password` FROM Admins LIMIT 1;" );
$aRayComponents = array(
						array('name' => 'Chat', 'dir' => 'chat', 'admin' => true, 'lite' => true),
						array('name' => 'Instant messenger', 'dir' => 'im', 'admin' => false, 'lite' => true),
						array('name' => 'MP3 player', 'dir' => 'mp3', 'admin' => false, 'lite' => false),
						array('name' => 'Presence', 'dir' => 'presence', 'admin' => false, 'lite' => true),
						array('name' => 'Video recorder', 'dir' => 'video', 'admin' => false, 'lite' => false)
						);
?>
<div>
	<script src="<?= $site['url']; ?>ray/modules/global/js/integration.js" type="text/javascript" 	language="javascript"></script>
	<div><div style="clear:left; float:left; margin: 5px; width:200px; font-weight:bold;"">Name</div><div style="float:left; margin: 5px; width:100px; font-weight:bold; text-align:center;">Admin panel</div><div style="float:left; margin:5px; width:100px; font-weight:bold; text-align:center;">Status</div></div>
<?
foreach($aRayComponents as $aRayComponent)
{
	$bInstalled = file_exists($dir['root'] . "ray/modules/" . $aRayComponent['dir'] . "/inc/header.inc.php");
	$sRes = "<div><div style=\"clear:left; float:left; margin: 5px; width:200px;\">" . $aRayComponent['name'] . "</div><div style=\"float:left; margin: 5px; width:100px; text-align:center; \">";
	$sRes .= (getParam("enable_ray_pro") == "on" || $aRayComponent['lite']) && $aRayComponent['admin'] ? "<a href=\"javascript: void(0)\" onClick=\"javascript: openRayWidget('chat', 'admin', '" . $accredit['Name'] . "', '" . $accredit['Password'] . "');\">open</a></div>" : "</div>";
	$sRes .= (getParam("enable_ray_pro") == "on" || $aRayComponent['lite']) && $bInstalled ? "<div style=\"float:left; margin:5px; width:100px; color:green; font-weight:bold; text-align:center;\">Installed</div></div>" : "<div style=\"float:left; margin:5px; width:100px; color:red; font-weight:bold; text-align:center;\"><span style=\"color:red;\">Ray Pro only</span></div></div>";
	echo $sRes;
}
?>
</div>
<?
ContentBlockFoot();
BottomCode();
?>