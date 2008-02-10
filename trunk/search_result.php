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
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profile_disp.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'members.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$enable_match = getParam("enable_match") == "on" ? 1 : 0;
if ( $enable_match )
	require_once( BX_DIRECTORY_PATH_INC . 'match.inc.php' );
if ( $en_ziploc )
	require_once( BX_DIRECTORY_PATH_INC . 'RadiusAssistant.inc' );

$is_first_call = !( (int)$_GET['p_per_page'] && (int)$_GET['page'] );

if ( !(int)$_GET['p_per_page'] ) $_GET['p_per_page'] = $oTemplConfig -> def_p_per_page;
if ( !(int)$_GET['page'] ) $_GET['page'] = 1;

// --------------- page variables and login

$_page['name_index'] = 32;
$_page['css_name'] = 'search_result.css';

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
{
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
	{
		if ( !( $logged['aff'] = member_auth( 2, false ) ) )
		{
			$logged['moderator'] = member_auth( 3, false );
		}
	}
}


$_page['header'] = _t("_Search result");
$_page['header_text'] = _t("_SEARCH_SORTED");
//$_page['header_text'] = ('g4' != $tmpl) ? _t("_SEARCH_SORTED") : "<img src=\"{$site['images']}search_res.gif\">";

// --------------- GET/POST actions


// check if user poset correct data
if ( !isset($_GET['LookingFor']) && !$_GET['ID'] && !$_GET['NickName'] && !$_GET['distance'] && !$_GET['zip']
	&& !$_GET['online_only'] && !$_GET['gallery_view'] && !$_GET['view_friends'] && !$_GET['tag'] )
{
	echo '<script language="Javascript">location.href=\'search.php\';</script>';
	exit;
}

// check for gallery view
$gallery_cols = $oTemplConfig -> iSearchResultGalleryCols;
$gallery_view = $_REQUEST['gallery_view'];

// --- move

// check for membership restrictions
$member['ID'] = (int)$_COOKIE['memberID'];
$check_res = checkAction( $member['ID'], ACTION_ID_MAKE_SEARCH );
if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED && !$logged['admin'] && !$logged['moderator'] )
{
    $ret  = "
    	<table width=100% height=100% cellpadding=0 cellspacing=0 class=text2>
    		<td align=center bgcolor=$boxbg2>
    			". $check_res[CHECK_ACTION_MESSAGE] ."<br />
    		</td>
    	</table>\n";

	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = $ret;
	PageCode();
	exit();
}

// Number of profiles which will be shown in one search result
$profiles_limit = 0;
if ( $check_res[CHECK_ACTION_PARAMETER] && !$logged['admin'] && !$logged['moderator'] )
	$profiles_limit = (int)$check_res[CHECK_ACTION_PARAMETER];

// --- move

// search vasriables and page navigation data

$ID = getID( htmlspecialchars_adv($_GET['ID']), 0 );
$NickName = htmlspecialchars_adv($_GET['NickName']);
$LookingFor = htmlspecialchars_adv($_GET['LookingFor']);
$Tag = $_GET['tag'];

$zip = htmlspecialchars_adv($_GET['zip']);
$distance = htmlspecialchars_adv($_GET['distance']);
$metric = htmlspecialchars_adv($_GET['metric']);

$sortorder = htmlspecialchars_adv($_GET['sortorder']);
$sortby = htmlspecialchars_adv($_GET['sortby']);
$photos_only = htmlspecialchars_adv($_GET['photos_only']);
$online_only = htmlspecialchars_adv($_GET['online_only']);

$page = (int)$_GET['page'];
$p_per_page = (int)$_GET['p_per_page'];

// build search SQL query

if ( $_GET['view_friends'] )
{
	$profile_id = (int)$_GET['view_friends'];
	$total_query = "RIGHT JOIN FriendList ON (Profiles.ID = FriendList.Profile AND FriendList.ID = $profile_id OR Profiles.ID = FriendList.ID AND FriendList.Profile = $profile_id)
						WHERE (FriendList.Profile = $profile_id OR FriendList.ID = $profile_id) AND FriendList.Check = '1' AND Profiles.Status = 'Active'";
}
elseif ( isset($_GET['ID']) || $NickName )
{
	$total_query = "WHERE Status = 'Active' ";
	
	if ( isset($_GET['ID']) )
	{
		$aIDs = explode( ',', $_GET['ID'] );
		foreach ( $aIDs as $key => $sID )
		{
			$sID = trim( $sID );
			$sID = process_db_input( $sID );
			if( !strlen( $sID ) )
				unset( $aIDs[$key] );
			else
				$aIDs[$key] = $sID;
		}
		$total_query .= "AND ( `ID` = '" . implode( "' OR `ID` = '", $aIDs ) . "' ) ";
	}
	if ( $NickName )
	{
		$aNicks = explode( ',', $NickName );
		foreach ( $aNicks as $key => $sNick )
		{
			$sNick = trim( $sNick );
			$sNick = process_db_input( $sNick );
			if( !strlen( $sNick ) )
				unset( $aNicks[$key] );
			else
				$aNicks[$key] = $sNick;
		}
		$total_query .= "AND ( `NickName` = '" . implode( "' OR `NickName` = '", $aNicks ) . "' ) ";
	}

	if ( $photos_only || $gallery_view )
		$total_query .= "AND Picture = '1' ";
}
elseif( $Tag )
{
	$total_query = "INNER JOIN `Tags` USING( `ID` ) WHERE Status = 'Active' AND `Tags`.`Type` = 'profile' ";
	
	$aTags = explode( ',', $Tag );
	foreach ( $aTags as $key => $sTag )
	{
		$sTag = trim( $sTag );
		$sTag = process_db_input( $sTag );
		if( !strlen( $sTag ) )
			unset( $aTags[$key] );
		else
			$aTags[$key] = $sTag;
	}
	$total_query .= "AND ( `Tags`.`Tag` = '" . implode( "' OR `Tags`.`Tag` = '", $aTags ) . "' ) ";

	if ( $photos_only || $gallery_view )
		$total_query .= "AND Picture = '1' ";

}
else  // general search using all searchable profile fields
{

	if ( $zip )
	{
		$zip = process_db_input( strtoupper( str_replace(' ', '', $zip) ), 1);
		$z_arr = db_arr("SELECT `Latitude`, `Longitude` FROM `ZIPCodes` WHERE REPLACE(`ZIPCode`,' ','') = '$zip'");

		if ( $z_arr )
		{
			// ZIP code exists
			$miles2km = 0.7; // miles/kilometers ratio

			$Miles = $metric == "km" ? $distance * $miles2km : $distance;
			$Latitude = $z_arr["Latitude"];
			$Longitude = $z_arr["Longitude"];

			$zcdRadius = new RadiusAssistant( $Latitude, $Longitude, $Miles );
			$minLat = $zcdRadius->MinLatitude();
			$maxLat = $zcdRadius->MaxLatitude();
			$minLong = $zcdRadius->MinLongitude();
			$maxLong = $zcdRadius->MaxLongitude();

			$total_query = "LEFT JOIN `ZIPCodes` ON UPPER( REPLACE(`Profiles`.`zip`, ' ', '') ) = REPLACE(`ZIPCodes`.`ZIPCode`,' ', '')
								WHERE `ZIPCodes`.`ZIPCode` IS NOT NULL AND `ZIPCodes`.`Latitude` >= $minLat AND `ZIPCodes`.`Latitude` <= $maxLat AND `ZIPCodes`.`Longitude` >= $minLong AND `ZIPCodes`.`Longitude` <= $maxLong ";
		}
		else
		{
			// ZIP code doesn't exist
			$total_query = "WHERE 0 ";
		}
	}
	else
	{
		$total_query = "WHERE 1 ";
	}

	$total_query .= "AND `Profiles`.`Status` = 'Active' ";
	if ( $photos_only || $gallery_view )
		$total_query .= "AND `Profiles`.`Picture` = '1' ";

	if ( $online_only )
	{
		$online_time = getParam( "member_online_time" );
		$total_query .= "AND `Profiles`.`LastNavTime` > SUBDATE(NOW(), INTERVAL $online_time MINUTE) ";
	}

	$respd = db_res("SELECT * FROM ProfilesDesc WHERE `search_type` <> 'none' ORDER BY `search_order` ASC");
	while ( $arrpd = mysql_fetch_array($respd) )
	{
		$fname = get_field_name ( $arrpd );
		$process_field = 1;
		$all_query_add = '';

		switch ( $arrpd['search_type'] )
		{
			case 'radio':
				$fval = process_db_input( $_GET[$fname], 1 );
				if ( strlen($fval) && $process_field )
				{
					if ( $arrpd['search_where'] )
						$s = sprintf($arrpd['search_where'], $fval, $fval, $fval, $fval );
					else
						$s = " $fname = '$fval' ";
					$total_query .= "AND ($s $all_query_add) ";
				}
				break;

			case 'list':
				$fval = $_GET[$fname];
				if ( count($fval) > 0 && is_array($fval) && $process_field )
				{
					$total_query .= " AND (`Profiles`.`{$fname}` IN ('-1'";
					while ( list( $key, $val ) = each( $fval ) )
						$total_query .= ",'". process_db_input($val, 1) ."'";
					$total_query .= ") $all_query_add) ";
				}
				break;

			case 'check':
				if ( $arrpd['type'] == 'r' )
				{
					$findok = 0;
					$funcbody = $arrpd['extra'];
					$func = create_function('', $funcbody);
					$ar = $func();

					foreach ( $ar as $key => $value )
					{
						if ( $_GET["{$fname}_{$key}"] == "on" )
						{
							$findok = 1;
							break;
						}
					}

					if ( $findok && $process_field )
					{
						$total_query_tmp = '';
						foreach ( $ar as $key => $value )
						{
							if ( $_GET["{$fname}_{$key}"] == "on" )
								$total_query_tmp .= ",$key";
						}

						if ( strlen($arrpd['search_where']) > 0 )
						{
							$s = sprintf($arrpd['search_where'], $total_query_tmp, $total_query_tmp, $total_query_tmp );
							$s = str_replace( '{all_add}', $all_query_add, $s );
							$total_query .= $s . ' ';
						}
						else
						{
							$total_query .= "AND (`Profiles`.`{$fname}` IN (-1{$total_query_tmp}) $all_query_add) ";
						}
					}
				}
				elseif ( $arrpd['type'] == 'e' && strlen($_GET[$fname]) && $process_field )
				{
					$total_query .= "AND (`Profiles`.`{$fname}` = '". process_db_input($_GET[$fname], 1) ."' $all_query_add) ";
				}
				elseif ( $arrpd['type'] == 'e')
				{
					$findok = 0;

					$vals = preg_split ("/[,\']+/", $arrpd['extra'], -1, PREG_SPLIT_NO_EMPTY);

					foreach ( $vals as $key )
					{
						if ( $_GET["${fname}_$key"] == "on" )
						{
							$findok = 1;
							break;
						}
					}

					if ( $findok && $process_field )
					{
						$total_query_tmp = '';
						foreach ( $vals as $key )
						{
							if ( $_GET["{$fname}_$key"] == "on" )
								$total_query_tmp .= ",'$key'";
						}

						if ( strlen($arrpd['search_where']) > 0 )
						{
							$s = sprintf($arrpd['search_where'], $total_query_tmp, $total_query_tmp, $total_query_tmp );
							$s = str_replace( '{all_add}', $all_query_add, $s );
							$total_query .= $s . ' ';
						}
						else
						{
							$total_query .= "AND (`Profiles`.`{$fname}` IN (-1{$total_query_tmp}) $all_query_add) ";
						}
					}
				}
				break;

			case 'check_set':
				$findok = 0;
				$vals = preg_split ("/[,\']+/", $arrpd['extra'], -1, PREG_SPLIT_NO_EMPTY);

				// Check if any of set checked
				$i = 0;
				foreach ( $vals as $v )
				{
					if ( strlen(trim($v)) <= 0 ) continue;
					if ( $_GET["{$fname}_{$i}"] == "on" )
					{
						$findok = 1;
						break;
					}
					$i++;
				}

				if ( $findok && $process_field )
				{
					if ( $fname != 'Zodiac' )
					{
						$fval = 0;
						$hex_string = "";
						$offset = 0;
						$mask = 1;

						foreach ( $vals as $v )
						{
							if ( strlen(trim($v)) <= 0 ) continue;

							if ( $_GET["{$fname}_{$offset}"] == 'on' )
								$fval |= $mask;

							$offset++;
							// MySQL allows only 64 bits for one set field
							if ( $offset == 64 )
								break;
							// Limit bit field with BYTE length and output BYTE by BYTE
							if ( $offset % 8 == 0 )
							{
								$hex_string = sprintf("%02x", $fval) . $hex_string;
								$mask = 1;
								$fval = 0;
							}
							else
							{
								$mask <<= 1;
							}
						}

						if ( $offset % 8 != 0 )
						{
							$hex_string = sprintf("%02x", $fval) . $hex_string;
						}
						if ( !strlen($hex_string) )
							$hex_string = "0";

						if ( $hex_string != "0" )
							$total_query .= "AND (`Profiles`.`{$fname}` & (0 + 0x$hex_string) $all_query_add) ";
					}
					else // if zodiac
					{
						$query_buffer = "AND (";
						$offset = 0;
						$any_zodiac = 0;

						foreach ( $vals as $v )
						{
							if ( $_GET["{$fname}_{$offset}"] == "on" )
							{
								$days_start = 0;
								$days_end = 0;
								switch ( strtolower($v) )
								{
									case 'aries':
										$days_start = 80;
										$days_end = 109;
										break;
									case 'taurus':
										$days_start = 110;
										$days_end = 140;
										break;
									case 'gemini':
										$days_start = 141;
										$days_end = 172;
										break;
									case 'cancer':
										$days_start = 173;
										$days_end = 203;
										break;
									case 'leo':
										$days_start = 204;
										$days_end = 234;
										break;
									case 'virgo':
										$days_start = 235;
										$days_end = 265;
										break;
									case 'libra':
										$days_start = 266;
										$days_end = 296;
										break;
									case 'scorpio':
										$days_start = 297;
										$days_end = 325;
										break;
									case 'sagittarius':
										$days_start = 326;
										$days_end = 355;
										break;
									case 'capricorn':
										$days_start = 356;
										$days_end = 19;
										break;
									case 'aquarius':
										$days_start = 20;
										$days_end = 49;
										break;
									case 'pisces':
										$days_start = 50;
										$days_end = 79;
										break;
								}

								if ( $any_zodiac )
								{
									$query_buffer .= "OR ";
								}
								if ( strtolower($v) == 'capricorn' )
								{
									$query_buffer .= "(DAYOFYEAR(`DateOfBirth`) >= $days_start OR DAYOFYEAR(`DateOfBirth`) <= $days_end) ";
								}
								else
								{
									$query_buffer .= "(DAYOFYEAR(`DateOfBirth`) BETWEEN $days_start AND $days_end) ";
								}

								$any_zodiac = 1;
							}

							$offset++;
						}

						if ( $any_zodiac )
						{
							$query_buffer .= "$all_query_add) ";
							$total_query .= $query_buffer;
						}
					}
				}

				break;

			case 'daterange':
				$fval_s = $_GET["${fname}_start"];
				$fval_e = $_GET["${fname}_end"];
				if ( $fval_s || $fval_e )
				{
					$fval_s = (int)( date( "Y" ) - $fval_s );
					$fval_e = (int)( date( "Y" ) - $fval_e - 1 );
					$fval_s = $fval_s . date( "-m-d" );
					$fval_e = $fval_e . date( "-m-d" );
					if ( strlen($fval_s) && $fval_e && $process_field )
					{
						$total_query .= "AND (TO_DAYS(`Profiles`.`{$fname}`) BETWEEN TO_DAYS('$fval_e') AND (TO_DAYS('$fval_s')+1) $all_query_add) ";
					}
				}
				break;

			case 'text':
				$fval = process_db_input($_GET[$fname], 1);
				if ( strlen($fval) && $process_field )
				{
					if ( $arrpd['search_where'] )
						$s = sprintf($arrpd['search_where'], $fval, $fval, $fval );
					else
						$s = " `Profiles`.`{$fname}` LIKE '%$fval%' ";
					$total_query .= "AND ($s $all_query_add) ";
				}
				break;

		}
	}
}

// Perform action if it's first search call (not navigation call)
if ( $is_first_call && !$logged['admin'] && !$logged['moderator'] )
	checkAction( $member['ID'], ACTION_ID_MAKE_SEARCH, true );

$query = "SELECT COUNT(DISTINCT Profiles.ID) FROM Profiles $total_query";

$p_num = db_arr( $query );
$p_num = (int)$p_num[0];

$pages_num = ceil( ($profiles_limit > 0 && $profiles_limit < $p_num ? $profiles_limit : $p_num) / $p_per_page );
$page = $page > $pages_num && $pages_num > 0 ? $pages_num : $page;

$real_first_p = (int)($page - 1) * $p_per_page;
$page_first_p = $real_first_p + 1;

// sorting
$sort_order = ($_GET['sortorder'] == 'ASC' ? 'ASC' : 'DESC');

switch ( $_GET['sortby'] )
{
	case 'Sort_By_Age':
		$sort_by = '( 0 - TO_DAYS(DateOfBirth) )';
		break;
	default:
		$sort_by = '1';
		break;
}

// determine number of pages ( for page navigation )
if( $profiles_limit > 0 && $real_first_p + $p_per_page > $profiles_limit)
{
	$p_per_page = $profiles_limit - $real_first_p;
}

$page_query = "
	SELECT DISTINCT
		`Profiles`.*,
		(LastNavTime > SUBDATE(NOW(), INTERVAL 5 MINUTE)) as is_onl
		$query_add
	FROM Profiles
	$total_query
	ORDER BY
		$sort_by $sort_order,
		Profiles.LastLoggedIn DESC
	LIMIT $real_first_p, $p_per_page
	";
$result = db_res( $page_query );
$page_p_num = mysql_num_rows( $result );

$w_ex = 12;

// profile actions
if ( $pa_icon_preload )
{
        $add_to_header = "<script language=\"JavaScript\"><!--\n";

        $add_to_header .= "pa_profile=new Image;\n";
        $add_to_header .= "pa_profile.src=\"".$site['images']."pa_profile2.gif\";\n";

        $add_to_header .= "pa_kiss=new Image;\n";
        $add_to_header .= "pa_kiss.src=\"".$site['images']."pa_kiss2.gif\";\n";

        $add_to_header .= "pa_send=new Image;\n";
        $add_to_header .= "pa_send.src=\"".$site['images']."pa_send2.gif\";\n";

        $add_to_header .= "pa_send=new Image;\n";
        $add_to_header .= "pa_send.src=\"".$site['images']."pa_send2.gif\";\n";

        $add_to_header .= "pa_addtocart=new Image;\n";
        $add_to_header .= "pa_addtocart.src=\"".$site['images']."pa_addtocart2.gif\";\n";

    $add_to_header .= "pa_hotlost=new Image;\n";
    $add_to_header .= "pa_hotlost.src=\"".$site['images']."pa_hot2.gif\";\n";

    $add_to_header .= "pa_friendlost=new Image;\n";
    $add_to_header .= "pa_friendlost.src=\"".$site['images']."pa_friend2.gif\";\n";

    $add_to_header .= "pa_blocklist=new Image;\n";
    $add_to_header .= "pa_blocklist.src=\"".$site['images']."pa_block2.gif\";\n";

        $add_to_header .= "--></script>\n";
}

$row_spacer = "<table cellspacing=0 cellpadding=0 height=10><td><img src=\"".$site['images']."spacer.gif\"></td></table>";

//---------------------

$get_vars = get_vars();

$ai_gallery  ="<img alt=\""._t("_view as photo gallery")."\" name=is01 src=\"$site[images]search_gallery.gif\" border=0>";
$al_gallery  ="<a href=\"search_result.php{$get_vars}gallery_view=1\" ";
if ( $pa_icon_preload )
{
        $al_gallery.="onMouseOver=\"javascript: is01.src='$site[images]search_gallery2.gif';\"";
        $al_gallery.="onMouseOut =\"javascript: is01.src='$site[images]search_gallery.gif';\"";
}
$al_gallery .= ">";
$ai_gallery  = $al_gallery.$ai_gallery."</a>";
$al_gallery .= _t("_view as photo gallery")."</a>";


$ai_profiles  ="<img alt=\""._t("_view as profile details")."\" name=is02 src=\"$site[images]search_profiles.gif\" border=0>";
$al_profiles  ="<a href=\"search_result.php{$get_vars}gallery_view=0\" ";
if ( $pa_icon_preload )
{
        $al_profiles.="onMouseOver=\"javascript: is02.src='$site[images]search_profiles2.gif';\"";
        $al_profiles.="onMouseOut =\"javascript: is02.src='$site[images]search_profiles.gif';\"";
}
$al_profiles .= ">";
$ai_profiles  = $al_profiles.$ai_profiles."</a>";
$al_profiles .= _t("_view as profile details")."</a>";

// --------------- GET/POST actions [ END ]

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = $p_num ? PageCompPageMainCode() : PageCompNoResults();
$_page_cont[$_ni]['sorting'] = $p_num > 1 ? PageCompSorting() : "";
$_page_cont[$_ni]['page_navigation'] = $pages_num > 1 ? PageCompNavigation( 'First' ) : "";
$_page_cont[$_ni]['page_navigation_short'] = $pages_num > 1 ? PageCompNavigationShort( 'FirstShort' ) : "";
$_page_cont[$_ni]['page_navigation2'] = $pages_num > 1 ? PageCompNavigation( 'Second' ) : "";
$_page_cont[$_ni]['page_navigation_short2'] = $pages_num > 1 ? PageCompNavigationShort( 'SecondShort' ) : "";
$_page_cont[$_ni]['add_to_header'] = $add_to_header;
$_page_cont[$_ni]['row_spacer'] = $row_spacer;

$_page_cont[$_ni]['ai_gallery'] = $ai_gallery;
$_page_cont[$_ni]['ai_profiles'] = $ai_profiles;

$_page_cont[$_ni]['db_color'] = $search_result_db_color;

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * Place HTML code for Sorting
 */
function PageCompSorting ()
{
	global $p_num;
	global $site;




	//ob_start();

	$ret = '';
	$ret .= "<!-- Sorting -->\n";
	$ret .= '<form method="get" action="search_result.php' . get_vars() . '">';

	if($_REQUEST['sortby'])
	{
		$sort_sel_age = $_REQUEST['sortby'] == 'Sort_By_Age' ? 'checked="checked"' : '';
		$sort_sel_price = $_REQUEST['sortby'] == 'Sort_By_Price' ? 'checked="checked"' : '';
		$sort_sel_cont = $_REQUEST['sortby'] == 'Sort_By_Cont' ? 'checked="checked"' : '';
	}
	else
	{
		$sort_sel_age = 'checked="checked"';
	}

	$ret .= get_vars_controls();
	$ret .= '<table border="0" width="100%"  cellspacing="1" cellpadding="2">';
		$ret .= '<tr>';
			$ret .= '<td align="left"><b>' . _t("_Sort results") . '</b></td>';
			$ret .= '<td align="left">';
				$ret .= '<input type="radio" name="sortby" value="Sort_By_Age" id="Sort_By_Age" ' . $sort_sel_age . ' />&nbsp;<label for="Sort_By_Age">' . _t("_by age") . '</label>';
			$ret .= '</td>';
			$ret .= ' <td align="left"><b>' . _t("_Sort order") . '</b></td>';
			$asc_sel = $_GET['sortorder'] == 'ASC' ? 'checked="checked"' : '';
			$desc_sel = $asc_sel ? '' : 'checked="checked"';
			$ret .= '<td align="left">';
				$ret .= '<input type="radio" name="sortorder" value="ASC" id="ASC" ' . $asc_sel . ' />&nbsp;<label for="ASC">' . _t("_ascending") . '</label>';
			$ret .= '</td>';
			$ret .= '<td rowspan="2" align="center" valign="middle"><input class=no type=submit value=" ' . _t("_Go") . '! " /></td>';
			//$ret .= '<td>';
			//$ret .= '</td>';
		$ret .= '</tr>';
		$ret .= '<tr>';
			$ret .= '<td>&nbsp;</td>';
			$ret .= '<td align="left">';
				$ret .= '<input type="radio" name="sortorder" value="DESC" id="DESC" ' . $desc_sel . ' />&nbsp;<label for="DESC">' . _t("_descending") . '</label>';
            $ret .= '</td>';
		$ret .= '</tr>';
	$ret .= '</table>';
	$ret .= '</form>';
	$ret .= "\n<!--  /Sorting -->\n";


	return DesignBoxContentBorder( _t("_sort"), $ret );
}


/**
 * Place HTML code for "page navigation"
 */
function PageCompNavigation( $navBoxId )
{

	$out = "<center>";
	$out .= ResNavigationRet( 'Search'.$navBoxId, 0 );
	$out .= "</center>";
	return DesignBoxContentBorder( _t("_page navigation"), $out );
}

/**
 * Place HTML code for short version of "page navigation"
 */
function PageCompNavigationShort( $navBoxId )
{
	return ResNavigationRet( 'Search'.$navBoxId, 1 );
}

/**
 * Place HTML code for search result
 */
function PageCompGallery ( )
{
	global $result;
	global $gallery_cols;
	global $boxbg;
	global $boxbg2;
	global $boxbg3;
	global $site;
	global $tmpl;
	global $logged;
	global $max_thumb_width;
	global $max_thumb_height;

	$ret = "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">";
	$count = 0;

	while ( $p_arr = mysql_fetch_array( $result ) )
	{
		// get user online status
		$user_is_online = (int)$p_arr['is_onl'];//get_user_online_status($p_arr['ID']);

		// online/offline status
		if ( $user_is_online )
		{
			$offline_online = "<img border=\"0\" src=\"{$site['icons']}online.gif\" alt=\"" . _t("_Online") . "\" />";
			$off_on_text = _t("_Online");
		}
		else
		{
			$offline_online = "<img border=\"0\" src=\"{$site['icons']}offline.gif\" alt=\"" . _t("_Offline") . "\" />";
			$off_on_text = _t("_Offline");
		}

                if ($tmpl=='act')
        {
            $offline_online =  "<table style=\"margin-top:2px; margin-bottom:2px;\" border=0 cellspacing=0 cellpadding=0><tr height=15><td width=4 height=15><img src=templates/tmpl_act/images_act/button2_left.gif width=4 height=15></td><td width=10 height=15 valign=middle background=templates/tmpl_act/images_act/button2_fill.gif>".$offline_online."</td>";
            $offline_online =  $offline_online."<td height=15 valign=top background=templates/tmpl_act/images_act/button2_fill.gif class=small3>&nbsp;".$off_on_text."</td><td width=4 height=15><img src=templates/tmpl_act/images_act/button2_right.gif width=4 height=15></td></tr></table>";

        }

                if ( !($count % $gallery_cols) )
                        $ret .= "<tr>
                        			<td>
                        				<table cellspacing=\"2\" cellpadding=\"0\" border=\"0\" width=\"100%\"><tr><td align=\"center\">";
                else
                        $ret .= "<td align=\"center\">";

// -----------------

                $out = "";
                $out .= "<table border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" width=\"$max_thumb_width\">\n";


                $out .= "<tr>";
				$out .= "<td height=\"$max_thumb_height\" align=\"center\">\n";
				$out .= get_member_thumbnail( $p_arr['ID'], none );
				$out .= "</td>";
				$out .= "</tr>\n";

        $out .= "<tr><td align=\"center\">\n";
        $out .= $offline_online;
        $out .= "</td></tr>\n";

                $out .= "</table>\n";

                $ret .= DesignBoxContentBorder( "<div STYLE=\"font-weight:normal; text-transform:none; overflow : hidden\">$p_arr[NickName]: ".age( $p_arr[DateOfBirth] )." "._t("_y/o")."</div>", $out );
                // -------------------

                if ( !(($count+1) % $gallery_cols) )
                        $ret .= "</td></table> </td></tr>";
                else
                        $ret .= "";
                $count += 1;
        }

    if ( (($count) % $gallery_cols) )
        $ret .= "</td></table>\n </td></tr>\n";

        $ret .= "</table>\n\n";

        return $ret;
}

/**
 * Place HTML code for search result
 */
function PageCompPageMainCode ()
{
	global $p_num;
	global $gallery_view;
	global $dir;
	global $result;
	global $tmpl;
	global $site;
	global $prof;
	global $enable_match;
	global $logged;
	global $pa_icon_preload;
	global $boxbg2;
	global $profiles_limit;

	global $NickName;

	global $oTemplConfig;
	if ( $gallery_view )
	{
		$out = PageCompGallery();
		return DesignBoxContent ( _t("_Gallery"), $out , $oTemplConfig -> PageSearcResultGallery_db_num );
	}

        // design box
        $fs = filesize ( "{$dir['root']}templates/tmpl_{$tmpl}/searchrow.html" );
        $f = fopen ( "{$dir['root']}templates/tmpl_{$tmpl}/searchrow.html", "r" );
        $templ_search = fread ( $f, $fs );
        fclose ( $f );

		$ret = '';
		if( $profiles_limit > 0 )
		{
			$ret .= '<div class="no_result">';
				$ret .= '<div>';
					$ret .= _t("_SEARCH_RESULT_ALLOWED_PROFILES", $profiles_limit);
				$ret .= '</div>';
			$ret .= '</div>';
		}
		else
		{
			$ret .= '';
		}

// get nickname for IM
	$NickName = getNickName();

        while ( $p_arr = mysql_fetch_assoc( $result ) )
        {
            $ret .= PrintSearhResult( $p_arr, $templ_search, 1 );
        }

	return $ret;
}



/**
 * Place HTML code for no results
 */
function PageCompNoResults( )
{

	$ret = '';
	$ret .= '<div class="no_result">';
		$ret .= '<div>';
			$ret .= _t("_NO_RESULTS");
		$ret .= '</div>';
	$ret .= '</div>';

	return $ret;
}

?>