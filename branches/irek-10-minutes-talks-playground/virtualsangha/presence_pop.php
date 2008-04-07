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

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

$iId = (int)$_COOKIE['memberID'];
$sPassword = getPassword($iId);
$bEnableRay = (getParam( 'enable_ray' ) == 'on');
$check_res = checkAction( $iId, ACTION_ID_USE_RAY_PRESENCE );
if($bEnableRay && $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)	
	echo getApplicationContent("presence", "user", array('id' => $iId, 'password' => $sPassword), false);
else
{
?>
<center>
	<table width=100% height=100% cellpadding=0 cellspacing=0>
		<td align=center valign=center>
			<table width="90%" height="70" cellpadding="5" cellspacing="1" class="table">
				<tr>
					<td class="panel" width="100%" align="center" valign="middle">
						<div align="center" class="small"><?= $check_res[CHECK_ACTION_MESSAGE]; ?></div>
					</td>
				</tr>
			</table>
		</td>
	</table>
</center>
<?
}
?>
