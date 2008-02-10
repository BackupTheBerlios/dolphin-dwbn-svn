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
require_once( BX_DIRECTORY_PATH_INC . 'profile_disp.inc.php' );

// --------------- page variables and login

$_page['name_index'] = 4;
$_page['css_name'] = 'search.css';

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );

$_page['header'] = _t("_SEARCH_FOR", $site['title']);
$_page['header_text'] = _t("_SEARCH_H");

// --------------- page components

$w_ex = 20;

//simple and advanced search
if( $_GET['mode'] == 'simple' )
{
	$_page['name_index'] = 124;
	$_page['header_text'] = _t("_Simple Search");
}
elseif( $_GET['mode'] == 'adv' )
{
	$_page['name_index'] = 125;
	$_page['header_text'] = _t("_Advanced search");
}

$_ni = $_page['name_index'];

$_page_cont[$_ni]['search']           = _t("_Search");
$_page_cont[$_ni]['with_photos_only'] = _t("_With photos only");
$_page_cont[$_ni]['online_only']      = _t("_online only");
$_page_cont[$_ni]['extended_search']  = _t("_Extended search");

$_page_cont[$_ni]['search_by_id']     = PageCodeSearchByID();
$_page_cont[$_ni]['search_by_nick']   = PageCodeSearchByNick();
$_page_cont[$_ni]['page_main_code']   = PageCompPageMainCode();

$_page_cont[$_ni]['zip_locator']      = ($en_ziploc) ? PageCodeZipLocator() : '';

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
	global $en_ziploc;
	global $_page_cont;
	global $_ni;
	global $w_ex;
	global $member_sex;
	global $logged;
	global $search_start_age;
	global $search_end_age;

	$gl_search_start_age    = (int)$search_start_age;
	$gl_search_end_age      = (int)$search_end_age;

	if ( $logged['member'] )
	{
		//$arr_sex = db_arr("SELECT Sex FROM Profiles WHERE ID = ".(int)$_COOKIE['memberID']);
		$member_sex = getSex( $_COOKIE['memberID'] );//$arr_sex['Sex'];
	}
	else
	{
		$member_sex = 'male';
	}

	$ret = "";

	// Get LookingFor profile type
	$default_looking_for = '';
	if ( strlen($_GET['LookingFor']) )
	{
		$default_looking_for = $_GET['LookingFor'];
	}
	elseif ( $fname == 'LookingFor' )
	{
		switch ( $member_sex )
		{
			case 'male':
				$default_looking_for = "female";
				break;
			case 'female':
				$default_looking_for = "male";
				break;
		}
	}

	$respd = db_res("SELECT * FROM `ProfilesDesc` WHERE `search_type` <> 'none' ORDER BY `search_order` ASC");
	while ( $arrpd = mysql_fetch_array($respd) )
	{
		$fname = get_field_name ( $arrpd );

		if ($fname == "Sex" && !$_GET['LookingFor'])
		{
			$arrpd['search_default'] = $member_sex;
		}

		if ( $fname == 'LookingFor' && strlen($default_looking_for) )
		{
			$arrpd['search_default'] = $default_looking_for;
		}

		$section_hide = 0;

		switch ($arrpd['search_type'])
		{
			case 'radio':
				$ret .= print_row_search_radio( $arrpd, $arrpd['search_default'], "table", $javascript, $section_hide );
				break;
			case 'list':
				$ret .= print_row_search_list( $arrpd, $arrpd['search_default'], "text", $section_hide );
				break;
			case 'check':
				$ret .= print_row_search_check( $arrpd, $arrpd['search_default'], "text", $section_hide );
				break;
			case 'check_set':
				$ret .= print_row_search_check_set( $arrpd, $arrpd['search_default'], "text", "", $section_hide );
				break;
			case 'daterange':
				$ret .= print_row_search_daterange( $arrpd, $arrpd['search_default'], "text", $section_hide );
				break;
	        case 'text':
	            $ret .= print_row_search_text( $arrpd, $arrpd['search_default'], "text", $section_hide );
	            break;
		}
	}
	return $ret;
}

/**
 * Zip Locator Code
 */
function PageCodeZipLocator()
{
	global $w_ex;
	global $member_sex;
	global $bgcolor;
	global $tmpl;
	global $site;
	global $search_start_age;
	global $search_end_age;

	$gl_search_start_age    = (int)$search_start_age;
	$gl_search_end_age      = (int)$search_end_age;

$ret = <<<EOF

<!-- Search By Distanvce -->

EOF;

	ob_start();
?>

<form method="get" action="search_result.php">
<center>
<table class=small cellspacing=3 cellpadding=0 border="0">
	<tr>
    <td><?=_t("_I am")?>&nbsp;</td>
    <td><select name="Sex">
<?php
    echo SelectOptions("Sex", $member_sex);
?>
    </select></td>
    <td>&nbsp;<?=_t("_seeking a")?>&nbsp;</td>
    <td><select name="LookingFor">
<?php
    echo SelectOptions("LookingFor", ($member_sex=='male' ? 'female' : 'male') );
?>
    </select></td>
    <td>&nbsp;<?=_t("_from")?>&nbsp;</td>
    <td><select name="DateOfBirth_start">
<?

for ( $i = $gl_search_start_age ; $i <= $gl_search_end_age ; $i++ )
{
    $sel = $i == $gl_search_start_age ? 'selected' : '';
    echo "<option value=$i $sel>$i</option>";
}
?>
    </select></td>
    <td>&nbsp;<?=_t("_to")?>&nbsp;</td>
    <td><select name="DateOfBirth_end">
<?
for ( $i = $gl_search_start_age ; $i <= $gl_search_end_age ; $i++ )
{
    $sel = $i == $gl_search_end_age ? 'selected' : '';
    echo "<option value=$i $sel>$i</option>";
}
?>
    </select></td>
    </table>

<table class=small cellspacing=3 cellpadding=0 border="0">
<tr>
<td>
	&nbsp;<?=_t("_living within")?>&nbsp;
    <input class=no type=text name=distance  size=12 />
<select name="metric">
	<option selected="selected" value="miles"><?php echo _t("_miles"); ?> </option>
	<option value="km"><?php echo _t("_kilometers"); ?> </option>
</select>
	&nbsp;<?=_t("_from zip/postal code")?>&nbsp;
    <input class=no type=text name=zip size=12 />
</td>
</tr>
</table>


<table class=small cellspacing=3 cellpadding=0>
<tr>

    <td>
		<input type=checkbox name="online_only" /><?php echo _t("_online only"); ?>
	</td>

    <td>
		<input type=checkbox name=photos_only /><?php echo _t("_With photos only"); ?>
	</td>

	<td>
		<input class=no type=submit value="<?= _t("_Search"); ?>" />
	</td>

</tr>
</table>

</center></form>



<?php

    $out = ob_get_clean();
	if ( $tmpl=='adl' )
		$w_ex1 = 0;

	$ret .= DesignBoxContentBorder( _t("_Search by distance"), $out );


	return $ret;
}

/**
 * "Search by NickName"
 */
function PageCodeSearchByNick()
{
	global $w_ex;
	global $tmpl;

	ob_start();

?>
    <form method=get action="search_result.php"><br />
    <input class=no name="NickName" size=13 id="by_nick_id" /><br /><br />

<?php
   echo "<input class=no type=\"submit\" value=". _t("_Fetch")." />";
?>


    </form><br />
<?php

    $out = ob_get_contents();
    ob_end_clean();
	if ( $tmpl=='adl' )
		$w_ex = 0;

	$ret = DesignBoxContentBorder( _t("_Search by Nickname"), $out );

    return $ret;

}

/**
 * "Search by ID"
 */
function PageCodeSearchByID()
{
	global $w_ex;
	global $tmpl;


	ob_start();
?>
    <form method=get action="search_result.php"><br />
    <input class=no name="ID" size=13 id="by_nick_id" /><br />
	<br />
<?php

   echo "<input class=no type=\"submit\" value=". _t("_Fetch")." />";
?>

    </form><br />
<?php

    $out = ob_get_contents();
    ob_end_clean();
	if ( $tmpl=='adl' )
		$w_ex = 0;

	$ret = DesignBoxContentBorder( _t("_Search by ID"), $out );

	return $ret;
}

?>