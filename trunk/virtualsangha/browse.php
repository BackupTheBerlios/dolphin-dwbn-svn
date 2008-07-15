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

$_page['name_index'] = 60;
$_page['header'] = _t("_Browse Profiles", $site['title']);
$_page['header_text'] = _t("_Browse Profiles");
$_page['css_name'] = 'browse.css';

check_logged();

// ================================================ parse variables ================
// =================================================================================

    foreach( $_GET as $key => $value )
    {
		if ( 'sex' == substr($key,0,3) )
		    $sex_sel .= $value . ',';
		else if ( 'country' == $key )
		    $country_sel = $value;
		else if ( 'age' == substr($key,0,3) )
		{
		    if ( 'start' == substr($key,4) )
		        $age_start_sel = (int)$value;

		    if ( 'end' == substr($key,4) )
		        $age_end_sel = (int)$value;
		}
		else if ( 'online_only' == $key )
		    $onl_only = 'on';
		else if ( 'photo_only' == $key )
		    $pic_only = 'on';
    }


// =================================================================================
// =================================================================================

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();


PageCode();


function PageCompPageMainCode()
{
    global $sex_sel;
    global $country_sel;
    global $age_start_sel;
    global $age_end_sel;
    global $onl_only;
    global $pic_only;

    global $site;

    global $p_num;
    global $page;
    global $pages_num;
    global $p_per_page;
    global $page_first_p;
    global $pages_num;

    global $search_start_age;
    global $search_end_age;
    global $max_thumb_width;
    global $max_thumb_height;
	
	
	$sex_options = makeCheckbox( 'sex', 'Sex', ( $sex_sel ? $sex_sel : 'male,female' ) );
	$country_options = makeList( 'country', '' , 'Country', ( $country_sel ? $country_sel : 'all' ), 'onchange="javascript: flagImage = document.getElementById(\'flagImageId\'); if (this.value == \'all\') {flagImage.src = \''. $site['images'] .'spacer.gif\';} else {flagImage.src = \''. $site['flags'] .'\' + this.value.toLowerCase() + \'.gif\';}"' );
	
    $age_start_sel = $age_start_sel ? $age_start_sel : $search_start_age;
    $age_end_sel = $age_end_sel ? $age_end_sel : $search_end_age;

    $age_option_start = makeList( 'age_start', "{$search_start_age}-{$search_end_age}", '', $age_start_sel);
    $age_option_end = makeList( 'age_end', "{$search_start_age}-{$search_end_age}", '', $age_end_sel);

    $photo_only_check = '<input type="checkbox" name="photo_only" id="photo_only_id" ' . ( $pic_only ? 'checked="checked"' : '' ) . ' /><label for="photo_only_id"><b>' . _t('_With photos only') . '</b></label>';
    $online_only_check = '<input type="checkbox" name="online_only" id="online_only_id" ' . ( $onl_only ? 'checked="checked"' : '' ) . ' /><label for="online_only_id"><b>' . _t('_online only') . '</b></label>';

	$country_def_flag = strlen($country_sel) == 0 || $country_sel == 'all' ? $site['images'].'spacer.gif' : $site['flags'] . strtolower($country_sel).'.gif';

    $ret = '
	    <form id="browse_form" action="' . $_SERVER['PHP_SELF'] . '" method="get">
			<div class="browse_form_wrapper">
			
				<div class="browse_form_row">
					<div class="clear_both"></div>
						<div class="label">' . _t('_Sex') . ':</div>
						<div class="value">' . $sex_options .'</div>
					<div class="clear_both"></div>
				</div>
				
				<div class="browse_form_row">
					<div class="clear_both"></div>
						<div class="label">' . _t('_DateOfBirth') . ':</div>
						<div class="value">' .
							_t("_from") . '&nbsp;' . $age_option_start .	'&nbsp;' . _t("_to") . '&nbsp;' . $age_option_end .
						'</div>
					<div class="clear_both"></div>
				</div>

				<div class="browse_form_row">
					<div class="clear_both"></div>
		    			<div class="label">' . _t('_Country') . ':</div>'.
						'<div class="value">' .
							$country_options .'&nbsp;<img id="flagImageId" src="'. $country_def_flag .'" alt="flag" />
						</div>
					<div class="clear_both"></div>
				</div>

				<div class="only">
				    ' . $photo_only_check . '
				    ' . $online_only_check . '
				</div>

				<div class="submit">
		    		<input id="search" name="search" type="submit" value="'. _t('_Show') .'" />
				</div>

			</div>
		</form>
	    ';


//====================================================================================================
//----------------------- search results -------------------------------------------------
//====================================================================================================


    $page = (int)$_GET[page];
    $p_per_page	= (int)$_GET[p_per_page];

    $aVar = array(30,60,90);
	if ( !$page )
	$page = 1;

    if ( !$p_per_page )
		$p_per_page = 30;

    $real_first_p = (int)($page - 1) * $p_per_page;
    $page_first_p = $real_first_p + 1;


    $temp_arr = explode(',', $sex_sel);
	foreach ( $temp_arr as $value )
	    if ( $value )
		$sex_add .= " `Sex` = '$value' OR ";


    if ( $country_sel && 'all' != $country_sel )
        $country_add = " `Country` = '{$country_sel}' AND ";



    if ( $age_start_sel )
        //$age_add .= " ( (YEAR(NOW()) - {$age_start_sel}) >= YEAR(`DateofBirth`) ) AND ";
        $age_add .= " DATEDIFF( NOW(), `Profiles`.`DateOfBirth` ) >= ". ($age_start_sel * 365.25) ." AND ";



    if ( $age_end_sel )
        //$age_add .= " ( (YEAR(NOW()) - {$age_end_sel}) <= YEAR(`DateofBirth`) ) AND ";
        $age_add .= " DATEDIFF( NOW(), `Profiles`.`DateOfBirth` ) <= " . ($age_end_sel * 365.25) . " AND ";

	if ( $pic_only )
		$pic_add = " AND `PrimPhoto` <> '0' ";
	if ( $onl_only )
		$onl_add = " AND (DateLastNav > SUBDATE(NOW(), INTERVAL " . getParam( "member_online_time" ) . " MINUTE)) ";


    $sex_add = $sex_add ? '(' . $sex_add . ' 1=0 ) AND ' : '';
    $age_add = $age_add ? '(' . $age_add . ' 1=1 )' : '';

    $sql_add = $sex_add . $country_add . $age_add . $pic_add . $onl_add . " AND (`Profiles`.`Couple`='0' OR `Profiles`.`Couple`>`Profiles`.`ID`)";

    $p_num = db_arr( "SELECT COUNT(*) FROM `Profiles` WHERE {$sql_add} AND `Profiles`.`Status` = 'Active'" );
    $p_num = $p_num[0];
    $pages_num = ceil( $p_num / $p_per_page );

    $profiles_list_query = "SELECT `ID`, `NickName`, `Sex`, `DateOfBirth`, `Couple` FROM `Profiles` WHERE {$sql_add} AND `Profiles`.`Status`='Active' ORDER BY `Couple` ASC, `Picture` DESC LIMIT {$real_first_p}, {$p_per_page}";

    $function = '
		    $ret = \'?\';
		    foreach ( $_GET as $key => $value )
			if ( $value )
			    $ret .= $key . \'=\' . $value . \'&amp;\';

		    return $ret;
		';




    $ret .= '<div id="container_result" style="border: 0px solid #000000">';

    $ret .= '<div style="margin-bottom:10px;"><center>' . ResNavigationRet( 'ProfilesUpper', 0, $function, $aVar ) . '</center></div>';

    if ( $p_num > 0)
    {

    $res = db_res( $profiles_list_query );
	$iI = 1;
    while ( $arr = mysql_fetch_array( $res ) )
    {
		//$user_is_online = get_user_online_status($arr['ID']);

		$iNewWidth = 0;
		if (isset($arr['Couple']) && $arr['Couple'] > 0) {
			$iMaxThumbWidth = getParam('max_thumb_width');
			$iNewWidth = 2 * ( $iMaxThumbWidth + 10 + 2 ) ;
			//$templ = str_replace ( "__ext_st__", 'style="width:' . $iNewWidth . 'px;"', $templ );
		} else {
			$iMaxThumbWidth = getParam('max_thumb_width');
			$iNewWidth = 1 * ( $iMaxThumbWidth +12 ) ;
			//$templ = str_replace ( "__ext_st__", 'style="width:' . $iNewWidth . 'px;"', $templ );
		}
		$sWidthStyle = ($iNewWidth>0) ? 'style="width:'.$iNewWidth.'px;"' : '';

		$cont = '<div class="browse_thumb" '.$sWidthStyle.'>';
		//$cont .= getProfileOnlineStatus( $user_is_online ) . get_member_thumbnail($arr['ID'], 'none' ) . '<div class="browse_nick"><a href="' . getProfileLink($arr['ID']) . '">' . $arr['NickName'] . '</a></div></div>';
		$cont .= get_member_thumbnail($arr['ID'], 'none', true ) . '<div class="browse_nick"><a href="' . getProfileLink($arr['ID']) . '">' . $arr['NickName'] . '</a></div></div>';
		$ret .= $cont;
		$iI++;
		if( $iI > 6 )
			$iI = 1;
	}

    }
    else
	{
		$ret .= '<div class="no_result">';
			$ret .= '<div>' . _t('_No results found') . '</div>';
		$ret .= '</div>';
	}


    $ret .= '<div style="clear:both;margin-top:10px;"><center>' . ResNavigationRet( 'ProfilesLower', 0, $function, $aVar ) . '</center></div>';


    $ret .= '</div>';


    return $ret;

}

function makeCheckbox( $name, $sField, $sSelected ) {

	$arr = getFieldValues( $sField );
    $aSelected = explode(',', $sSelected);

    $ret = '';
    foreach ( $arr as $value => $lang_v ) {
			$sel = ( in_array($value, $aSelected) ) ? 'checked="checked"' : '';

			$ret .= "
				<span>
					<input type=\"checkbox\" name=\"{$name}_{$value}\" id=\"{$name}_{$value}\" value=\"{$value}\" $sel />
				</span>
				<span>
					<label for=\"{$name}_{$value}\">" . _t($lang_v) . '</label>
				</span>';
    }

    return $ret;
}



function makeList( $name, $digit_range, $sField, $selected='', $js='' )
{
	$ret = '';
	$ret .= "<select id=\"$name\" name=\"$name\" $js>";

	if ( $digit_range )
	{
		list($a, $b) = preg_split( "/[\.,\-]/", $digit_range);

		for ( $i = $a; $i <= $b; $i++ )
		{
			if ( $selected && $selected == $i )
				$sel = "selected=\"selected\"";
			else
				$sel = '';
			$ret .= "<option value=\"$i\" $sel>$i</option>";
		}
	}
	elseif ( $sField )
	{
		$arr = getFieldValues( $sField );
		$arr = array_merge( array('all' => '__All'), $arr);

		foreach ( $arr as $key => $value )
		{
			if ( $selected == $key )
				$sel = "selected=\"selected\"";
			else
				$sel = '';

			$ret .= "<option value=\"$key\" $sel>". _t($value) ."</option>";
		}
	}
	else
		return false;

	$ret .= '</select>';

	return $ret;
}

?>