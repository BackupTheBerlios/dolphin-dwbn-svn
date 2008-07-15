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

$bann_click_url = $site['url'] . "click.php";

function banner_put_nv($Position, $Track = 1)
{
	global $bann_click_url;

	$out = "";

	$query = "SELECT * FROM `Banners` WHERE `Active` <> 0 AND `campaign_start` <= NOW() AND `campaign_end` >= NOW() ";

	switch($Position)
	{
		case 1:
		case 2:
		case 3:
		case 4:
			$query .= " AND `Position` LIKE '%{$Position}%' ";
			break;

		default:
			return $out;
	}

	$query .= "ORDER BY RAND() LIMIT 1";

	$arr = db_arr( $query );

	if ( !$arr[0] )
		return $out;

	switch ($Position)
	{
		case 2:
			$hshift = $arr['lhshift'];
			$vshift = $arr['lvshift'];
			break;

		case 3:
			$hshift = $arr['rhshift'];
			$vshift = $arr['rvshift'];
			break;
	}

	if( $Position == 2 || $Position == 3 )
	{
		$out .= "<div style=\"position:relative; margin:0; padding:0; width:1px; height:1px\">\n";
		$out .= "	<div style=\"position:absolute; ". ($Position == 2 ? "left:" : "right:") . $hshift . "px; top:" . $vshift . "px; z-index:1\">\n";
		$out .= "		<a target=\"_blank\" href=\"{$bann_click_url}?{$arr['ID']}\" onmouseout=\"ce()\" onfocus=\"ss('{$arr['Url']}')\" onmouseover=\"return ss('{$arr['Url']}')\">{$arr['Text']}</a><br />\n";
		$out .= "	</div>\n";
		$out .= "</div>\n";
	}
	else
	{
		$out .= '<table width="100%" style="padding: 10px 0px 10px 0px;" align="center">' . "\n";
		$out .= "	<tr>\n";
		$out .= "		<td align=\"center\">\n";
		$out .= "			<a target=\"_blank\" href=\"{$bann_click_url}?{$arr['ID']}\" onmouseout=\"ce()\" onfocus=\"ss('{$arr['Url']}')\" onmouseover=\"return ss('{$arr['Url']}')\">{$arr['Text']}</a><br />";
		$out .= "		</td>\n";
		$out .= "	</tr>\n";
		$out .= "</table>\n";
	}

	if ( $Track )
	{
		db_res("INSERT INTO `BannersShows` SET `ID` = {$arr['ID']}, `Date` = NOW(), `IP` = '". $_SERVER['REMOTE_ADDR'] ."'", 0);
	}

	return $out;
}


function banner_put($ID = 0, $Track = 1)
{
	global $bann_click_url;

	if ( !$ID )
	{
		// Get only banners that are active and for which promotion period has not expired.
		$bann_arr = db_arr("SELECT `ID`, `Url`, `Text` FROM `Banners` WHERE `Active` <> 0 AND `campaign_start` <= NOW() AND `campaign_end` >= NOW() ORDER BY RAND() LIMIT 1");
	}
	else
	{
		$bann_arr = db_arr("SELECT `ID`, `Url`, `Text` FROM `Banners` WHERE `ID` = $ID LIMIT 1");
	}
	if ( !$bann_arr )
		return "";

	if ( $Track )
	{
		db_res("INSERT INTO `BannersShows` SET `ID` = {$bann_arr['ID']}, `Date` = NOW(), `IP` = '". $_SERVER['REMOTE_ADDR']. "'", 0);
	}

	return "<a target=\"_blank\" href=\"{$bann_click_url}?{$bann_arr['ID']}\" onmouseout=\"ce()\" onfocus=\"ss('{$bann_arr['Url']}')\" onmouseover=\"return ss('{$bann_arr['Url']}')\">{$bann_arr['Text']}</a>";
}

?>