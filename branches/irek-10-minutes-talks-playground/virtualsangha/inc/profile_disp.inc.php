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

require_once('header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'admin.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'params.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'profiles.inc.php');

$c_search_table = "#d7d3fa";

function get_input_name (  $arr )
{
    $vals = split (",", $arr['name']);
    if ( strlen($vals[1]) )
        return $vals[1];
    else
        return $vals[0];
}

function get_field_name (  $arr )
{
    $vals = split (",", $arr['name']);
    return $vals[0];
}

function get_user_name( $id )
{
	$arr = getProfileInfo( $id );
	return $arr['NickName'];
}

function get_user_status( $id )
{
	$arr = getProfileInfo( $id );
	return $arr['Status'];
}

function is_in_set ( $vv, $set )
{
	$vals = preg_split ("/[,\']+/", $set, -1, PREG_SPLIT_NO_EMPTY);
	foreach ( $vals as $v )
		if ( $v == $vv ) return 1;
	return 0;
}

function print_row_content( $first_row, $arr, $content, $tr_class = "", $columns = 3, $width_first = "" )
{
    global $_page;

    $ret = "";
    $is_edit_form = $_page['name_index'] != 7 ? true : false;

    if ( !$is_edit_form ) {
        if ( $first_row ) {
            $class1 = "class=\"profile_td_1_first\"";
            $class2 = "class=\"profile_td_2_first\"";
        } else {
            $class1 = "class=\"profile_td_1\"";
            $class2 = "class=\"profile_td_2\"";
        }
    } else {
        if ( $first_row ) {
            $class1 = "class=\"join_td_1_first\"";
            $class2 = "class=\"join_td_2_first\"";
        } else {
            $class1 = "class=\"join_td_1\"";
            $class2 = "class=\"join_td_2\"";
        }
    }

    if ( ( $arr['group_mark'] == "" || $arr['group_mark'] == "b" ) && $columns > 1 )
    {
        if ( strlen($width_first) ) $w = "width='$width_first'";
        $ret .= "<tr><td $w $class1>";
        if ( $is_edit_form && strlen(trim($arr['check'])) )
            $ret .= '<font color="red">*</font>&nbsp;';
        if ( strlen($arr['namedisp']) > 0 ) $ret .= _t( $arr['namedisp'] ) . ': ';
                                     else $ret .= "&nbsp;";
        $ret .= "</td><td $class2>\n";
    }

    if ( $arr['group_mark'] == "b" || $arr['group_mark'] == "c"   || $arr['group_mark'] == "e" )
    {
        $ret .= $arr['group_text_b'];
    }
    $ret .= $content;

    if ( $arr['group_mark'] == "c" || $arr['group_mark'] == "e" || $arr['group_mark'] == "b")
    {
        $ret .= $arr['group_text_a'];
    }

    if ( ($arr['group_mark'] == "" || $arr['group_mark'] == "e")  && $columns > 2 )
    {
        $ret .= "</td><td $class2>";
        if ( strlen($arr['namenote']) > 0 ) $ret .= _t( $arr['namenote'], $arr['min_length'], $arr['max_length'] );
                                     else $ret .= "&nbsp;";
        $ret .= "</td></tr>\n";
    }
    elseif ( $arr['group_mark'] == '' && $columns > 2 && ($arr['type'] == 'r' || $arr['type'] == 'rb' || $arr['type'] == 'set') )
    {
		$ret .= "</td><td $class2>&nbsp;</td></tr>\n";
    }
    elseif ( $arr['group_mark'] == "e" || $arr['group_mark'] == "" )
    {
        $ret .= "</td></tr>\n";
    }

    return $ret;
}

function print_row_enum( $first_row, $arr, $val = "", $tr_class = "",  $javascript = "", $rd = 0, $columns = 3, $width_first = "", $read_only = 0 )
{
    $fname = get_input_name ( $arr );

    $ret = "";

    $vals = preg_split ("/[,\']+/", $arr['extra'], -1, PREG_SPLIT_NO_EMPTY);

    if ( !$rd )
    {
    	if ( !$read_only )
    	{
	        $ret .= "<select name=\"$fname\" id=\"$fname\" $javascript>\n";
	        foreach ( $vals as $v )
	        {
	            if ( strlen(trim($v)) <= 0 ) continue;
	            if ( $v == $val ) $sel = " selected=\"selected\" ";
	                         else $sel = "";
	            $ret .= "<option value=\"$v\" $sel>"._t("_".$v)."</option>\n";
	        }
	        $ret .= "</select>\n";
    	}
    	else
    	{
    		$ret .= "<input type=\"text\" name=\"$fname\" id=\"$fname\" $javascript value=\"". _t("_".$val) ."\" readonly=\"readonly\" />\n";
    	}
    }
	else
	{
        foreach ( $vals as $v )
        {
            if ( strlen(trim($v)) <= 0 ) continue;
            if ( $v == $val )
            {
                $ret .= _t("_".$v);
                break;
            }
        }
    }

    return print_row_content($first_row, $arr, $ret, $tr_class, $columns, $width_first);
}

function print_row_radio_button($first_row,$arr, $val = "", $tr_class = "", $rd = 0, $columns = 3, $width_first = "", $read_only = 0 )
{
    $fname = get_input_name ( $arr );
    
    $vals = preg_split ("/[,\']+/", $arr['extra'], -1, PREG_SPLIT_NO_EMPTY);
    $ret = "";
    $disabled = ($read_only ? " disabled=\"disabled\" " : "");

    if ( !$rd )
    {
        foreach ( $vals as $v )
        {
            if ( strlen(trim($v)) <= 0 ) continue;
            if ( strlen($val) ) {
                if ( $v == $val ) $sel = " checked=\"checked\" ";
                             else $sel = "";
            } else {
                if ( !$bfirst )
                {
                    $sel = " checked=\"checked\" ";
                    $bfirst = 1;
                }
                else
                    $sel = "";
            }

            $ret .= "<input type=\"radio\" name=\"$fname\" value=\"$v\" $sel $disabled />"._t("_".$v)."&nbsp; &nbsp;\n";
        }
    }
    else
    {
        foreach ( $vals as $v )
        {
            if ( strlen(trim($v)) <= 0 ) continue;
            if ( strlen($val) )
            {
                if ( $v == $val )
                {
                    $ret .= _t("_".$v);
                    break;
                }
            }
        }
    }

    return print_row_content($first_row, $arr, $ret, $tr_class, $columns, $width_first);
}


function print_row_set( $first_row, $arr, $val = "", $tr_class = "", $rd = 0, $columns = 3, $width_first = "", $read_only = 0 )
{
	$fname = get_input_name ( $arr );

	$vals = preg_split ("/[,\']+/", $arr['extra'], -1, PREG_SPLIT_NO_EMPTY);
	$disabled = ($read_only ? " disabled=\"disabled\" " : "");
	$ret = "";

	if ( !$rd )
	{
		$ret .= "<table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" align=\"left\">\n";
		foreach ( $vals as $v )
		{
			if ( strlen(trim($v)) <= 0 ) continue;
			if ( is_in_set( $v, $val ) )
				$sel = " checked=\"checked\" ";
			else
				$sel = "";
			$ret .= "<tr><td><input type=\"checkbox\" name=\"{$fname}_{$v}\" id=\"{$fname}_{$v}\" $sel $disabled /></td><td>&nbsp;<label for=\"{$fname}_{$v}\">"._t("_".$v)."</label></td></tr>\n";
		}
		$ret .= "</table>\n";
	}
	else
	{
		$sel_vals = preg_split ("/[,\']+/", $val, -1, PREG_SPLIT_NO_EMPTY);
		foreach ( $sel_vals as $v )
		{
			if ( strlen($ret) )
				$ret .= ", ". _t("_".$v);
			else
				$ret = _t("_".$v);
		}
	}

	if ( !strlen($ret) ) $ret = "&nbsp;";
	return print_row_content($first_row, $arr, $ret, $tr_class, $columns, $width_first);
}

function print_row_enum_years( $first_row, $arr, $val = "", $tr_class = "", $rd = 0, $columns = 3, $width_first = "", $read_only = 0,  $sRealFName='' )
{
	global $short_date_format;
	global $p_arr;

    $fname = get_input_name ( $arr );

    $ret = "";

    if ( !$rd )
    {
    	if ( !$read_only )
    	{
	        $ret .= "<select name=\"$fname\" class=\"select_years\">\n";
	        $vals = preg_split ("/[,\']+/", $arr[extra], -1, PREG_SPLIT_NO_EMPTY);

	        $vals[0] = date('Y') - $vals[0];
	        $vals[1] = date('Y') - $vals[1];

			for ( $v=$vals[1] ; $v >= $vals[0] ; $v-- )
		    {
		    	if ( strlen(trim($v)) <= 0 ) continue;
				if ( $v == $val ) $sel = ' selected="selected" ';
					else $sel = "";
				
				$ret .= "<option value=\"$v\" $sel>$v</option>\n";
			}
	        $ret .= "</select>\n";
    	}
    	else
    	{
    		$ret .= "<input type=\"text\" name=\"$fname\" id=\"$fname\" value=\"$val\" readonly=\"readonly\" class=\"input_years_readonly\" />\n";
    	}
    }
    else
    {
        $YearOfBirth = $val;

		if ( $sRealFName )
		{
		    $res = db_res('SELECT * FROM ProfilesDesc WHERE `name` LIKE "' . $sRealFName . ',%" AND `name` != "' . $arr[name] . '" ORDER BY `ID`;');

		    while ( $aArr = mysql_fetch_array($res) )
		    {

				if ( $aArr['get_value_db'] )
				{
				    $funcbody = $aArr['get_value_db'];
				    $func = create_function('$arg0',$funcbody);
			        $val = $func($p_arr);
				}

		        $item = get_input_name ( $aArr ); // get_input_name returns MonthOfBirth and DayOfBirth
				$$item = $val; // create variable $MonthOfBirth and $DayOfBirth
		    }
			
			$ret = $short_date_format;
			$ret = str_replace( '%m', $MonthOfBirth, $ret );
			$ret = str_replace( '%d', $DayOfBirth, $ret );
			$ret = str_replace( '%y', $YearOfBirth, $ret );
			
			$arr['group_mark'] = '';
			
			return print_row_content($first_row, $arr, $ret, $tr_class, 2, $width_first);
		}
    }

//=============================================================
//=============================================================

    return print_row_content($first_row, $arr, $ret, $tr_class, $columns, $width_first);
}


function print_row_date( $first_row, $arr, $val = "", $tr_class = "", $rd = 0, $columns = 3, $width_first = "", $read_only = 0 )
{
    $fname = get_input_name( $arr );
	$php_date_format = getParam( 'php_date_format' );
	
	list( $sYear, $sMonth, $sDay ) = explode( '-', $val );
	
	$ret = '';
    if ( !$rd )
    {
    	if ( !$read_only )
    	{
	        $aYears = preg_split ("/[,\']+/", $arr['extra'], -1, PREG_SPLIT_NO_EMPTY);
			
	        $sYearStart = (int)( date('Y') - $aYears[0] );
	        $sYearEnd   = (int)( date('Y') - $aYears[1] );
			
			$aMonthes = preg_split ("/[,\']+/", '01,02,03,04,05,06,07,08,09,10,11,12', -1, PREG_SPLIT_NO_EMPTY);
			
			//monthes
	        $ret .= "<select name=\"{$fname}_month\" id=\"{$fname}_month\">\n";
	        foreach ( $aMonthes as $v )
	        {
	            if ( $v == $sMonth )
					$sel = " selected=\"selected\" ";
	            else
					$sel = "";
				
	            $ret .= "<option value=\"$v\" $sel>"._t("_".$v)."</option>\n";
	        }
	        $ret .= "</select>\n";
			
	        //days
			$ret .= "<select name=\"{$fname}_day\" class=\"select_num\">\n";
	        for ( $v = 1; $v <= 31; $v++ )
	        {
	            if ( $v == $sDay )
					$sel = ' selected="selected"';
	            else
					$sel = "";
	            $ret .= "<option value=\"$v\" $sel>$v</option>\n";
	        }
	        $ret .= "</select>\n";
			
			
			//years
	        $ret .= "<select name=\"{$fname}_year\" class=\"select_years\">\n";
			for ( $v = $sYearEnd; $v >= $sYearStart ; $v-- )
		    {
		    	if ( strlen(trim($v)) <= 0 ) continue;
				if ( $v == $sYear )
					$sel = ' selected="selected" ';
				else
					$sel = "";
				
				$ret .= "<option value=\"$v\" $sel>$v</option>\n";
			}
	        $ret .= "</select>\n";
			
		}
    	else
    	{
    		$ret .= "<input type=\"text\" name=\"{$fname}\" id=\"$fname\" value=\"{$val}\" readonly=\"readonly\" class=\"input_date_readonly\" />\n";
    	}
    }
	else
	{
		$aDate = explode( '-', $val, 2 );
		$iYear = (int)$aDate[0];
		
		if( $iYear < 1970 ) //cannot apply timestamp
		{
			$sMyDate = ( (string)($iYear + 65) ) . '-' . $aDate[1]; //plus 65 years
			$iTime = strtotime( $sMyDate ); //max understandable date by strtotime is 2038-01-19
			$sConvDate = date( $php_date_format, $iTime );
			$sConvDate = str_replace( (string)($iYear + 65), (string)$iYear, $sConvDate ); //then minus 100 years
		}
		else
			$sConvDate = date( $php_date_format, strtotime( $val ) ); //can apply timestamp
		
		$ret .= $sConvDate;
	}
    return print_row_content($first_row, $arr, $ret, $tr_class, $columns, $width_first);
}


function print_row_enum_n( $first_row, $arr, $val = "", $tr_class = "", $rd = 0, $columns = 3, $width_first = "", $read_only = 0 )
{
    $fname = get_input_name( $arr );
    $val_array = split('-', $val);
    $val = $val_array[0];

    $ret = "";

    if ( !$rd )
    {
    	if ( !$read_only )
    	{
	        $ret .= "<select name=\"$fname\" class=\"select_num\">\n";
	        $vals = preg_split ("/[,\']+/", $arr['extra'], -1, PREG_SPLIT_NO_EMPTY);

	        for ( $v=$vals[0] ; $v <= $vals[1] ; ++$v )
	        {
	            if ( strlen(trim($v)) <= 0 ) continue;
	            if ( $v == $val ) $sel = ' selected="selected"';
	                         else $sel = "";
	            $ret .= "<option value=\"$v\" $sel>$v</option>\n";
	        }
	        $ret .= "</select>\n";
    	}
    	else
    	{
    		$ret .= "<input type=\"text\" name=\"$fname\" id=\"$fname\" value=\"$val\" readonly=\"readonly\" class=\"select_input_num_readonly\" />\n";
    	}
    }
    else
    {
        $vals = preg_split ("/[,\']+/", $arr['extra'], -1, PREG_SPLIT_NO_EMPTY);

        for ( $v=$vals[0] ; $v <= $vals[1] ; ++$v )
        {
            if ( strlen(trim($v)) <= 0 ) continue;
            if ( $v == $val ) {
                $ret .= $v;
                break;
            }
        }
    }

    return print_row_content($first_row, $arr, $ret, $tr_class, $columns, $width_first);
}

function print_row_edit( $first_row, $arr, $val = "", $tr_class = "", $rd = 0, $columns = 3, $width_first = "", $read_only = 0 )
{
    $fname = get_input_name ( $arr );
    $disabled = ($read_only ? "readonly=\"readolny\"" : "");

    if ( !strlen($arr['extra']) ) $arr['extra'] = 10;

    if ( strlen($arr['max_length']) ) {$ml = "maxlength='$arr[max_length]'"; }else $ml="";

    if ($arr['name'] == 'Password')
    {
    	$val = "";
    }
    if ( !$rd )
        $ret =  "<input type=\"text\" size=\"{$arr['extra']}\" $ml name=\"$fname\" value=\"". htmlspecialchars($val) ."\" $disabled class=\"input_text_edit\" />\n";
    else
        $ret = "$val\n";

    return print_row_content($first_row, $arr, $ret, $tr_class, $columns, $width_first);
}

function print_row_area( $first_row, $arr, $val = "", $tr_class = "", $rd = 0, $columns = 3, $width_first = "", $read_only = 0 )
{
    $fname = get_input_name ( $arr );
    $disabled = ($read_only ? "readonly=\"readonly\"" : "");

    $vals = split ("x", $arr['extra']);
// at second page of joinform hidden input with name $fname already exist !!!! thats why i need name different from $fname.
//    $fname .= 'original';

    if ( !$rd )
    {

        $ret  = '<table cellspacing="0" cellpadding="0">';
			$ret .= "<tr><td><textarea class=\"textarea_edit\" id=\"$fname\" name=\"$fname\" cols=\"$vals[0]\" rows=\"$vals[1]\"
            onKeyDown=\"javascript: document.getElementById('{$fname}counter').value = document.getElementById('{$fname}').value.length;\"
            onKeyUp=\"javascript: document.getElementById('{$fname}counter').value = document.getElementById('{$fname}').value.length;\" $disabled>". htmlspecialchars($val) ."</textarea>\n";
            $ret .= "</td></tr>";
			$ret .= '<tr>';
				$ret .= '<td align="right">';
					
					$ret  .= '<table cellpadding="0" cellspacing="2" border="0" align="right">';
						$ret .= '<tr>';
							$ret .= '<td align="right" valign="middle">';
								$ret .= _t("_Character counter");
							$ret .= '</td>';
							$ret .= '<td width="30">';
								$ret .= "<input type=\"text\" readonly=\"readonly\" id=\"{$fname}counter\" name=\"{$fname}counter\" size=\"3\" value=" . strlen($val) . " class=\"input_textarea_counter\" />";
							$ret .= '</td>';
						$ret .= '</tr>';
					$ret .= '</table>';
					
				$ret .= '</td>';
			$ret .= '</tr>';
            $ret .= "<tr><td style=\"text-align:center;\">".put_smiles($fname, 11);
            $ret .= "</td></tr></table>";

    }
    else
        $ret = process_smiles( "$val\n" );

    return print_row_content($first_row, $arr, $ret, $tr_class, $columns, $width_first);
}


function print_row_area2( $arr, $val = "", $tr_class = "", $rd = 0, $columns = 3, $width_first = "", $read_only = 0 )
{
    global $profile_view_color;

    $fname = get_input_name ( $arr );
    $disabled = ($read_only ? "readonly=\"readonly\"" : "");

    $vals = split ("x", $arr['extra']);

    $ret = "";

    if ( !$rd )
       $ret .= "<textarea name=\"$fname\" cols=\"$vals[0]\" rows=\"$vals[1]\" $disabled>". htmlspecialchars($val) ."</textarea>\n";
    else
    {
        $ret .= "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";
        $ret .= "<tr><td align=left class=profile_desc_header>"._t( $arr['namedisp'] ).": </td></tr>";
        $ret .= "<tr><td class=profile_desc_text>\n";
        $ret .= "<div style=\"width:100%; overflow: hidden; text-align: left; padding: 2px;\">$val</div><br /><br />\n";
        $ret .= "</td></tr>\n";
        $ret .= "</table>\n";
    }

    return $ret;
}


function print_row_pwd( $first_row, $arr, $val = "", $tr_class = "", $rd = 0, $columns = 3, $width_first = "", $read_only = 0 )
{
    $fname = get_input_name ( $arr );
    $disabled = ($read_only ? "readonly=\"readonly\"" : "");

    if ( strlen($arr['max_length']) )
    {
    	$ml = "maxlength='$arr[max_length]'";
    }
    else
    	$ml = "";

    if ( !$rd )
        $ret = "<input type=\"password\" size=\"$arr[extra]\" $ml name=\"$fname\" $disabled class=\"input_type_password\" />\n";

    return print_row_content($first_row, $arr, $ret, $tr_class, $columns, $width_first);
}


function print_row_delim( $first_row, $arr, $tr_class = "", $colspan = "", $rd = 0 )
{
    global $_page;

    $str_colspan = "";
    if ( strlen($colspan) > 0 ) $str_colspan = " colspan='$colspan' ";

    if ( $_page['name_index'] == 7 ) {
        if ( $first_row ) {
            $class1 = "class=\"profile_header_first\"";
        } else {
            $class1 = "class=\"profile_header\"";
        }
    } else {
        if ( $first_row ) {
            $class1 = "class=\"join_header_first\"";
        } else {
            $class1 = "class=\"join_header\"";
        }
    }


    return "<tr class=$tr_class><td align=center $str_colspan $class1><b>"._t($arr['namedisp'])."</b></td></tr>";
}

function print_row_ref( $first_row, $arr, $val = "", $tr_class = "", $rd = 0, $columns = 3, $width_first = "", $read_only = 0, $onchange = '', $imagecode = '')
{
    $fname = get_input_name ( $arr );

    $funcbody = $arr[extra];
    $func = create_function("", $funcbody);
    $ar = $func();

    $ret = "";
    // if user did not specified this field, then hide it
    if ( "I prefer not to say" == $ar[$val] && $rd ) return '';

    if ( !$rd )
    {
    	if ( !$read_only )
    	{
    		if ( strlen($onchange) )
    			$onchange = "onchange=\"javascript: {$onchange}\"";
	        $ret .= "<select name=\"{$fname}\" {$onchange} class=\"select_prof\">\n";
	        foreach ( $ar as $key => $value )
	        {
	            $sel = "";
	            if ( $val == $key ) $sel = " selected=\"selected\" ";
	                           else $sel = "";
	            $ret .= "<option value=\"$key\" $sel>"._t("__".$value)."</option>\n";
	        }
	        $ret .= "</select>\n";
	        if ( strlen($imagecode) )
	        	$ret .= "&nbsp;{$imagecode}\n";
    	}
    	else
    	{
    		$ret .= "<input type=\"text\" name=\"$fname\" id=\"$fname\" value=\"". _t("__".$ar[$val]) ."\" readonly=\"readonly\" class=\"input_select_prof_readonly\" />\n";
	        if ( strlen($imagecode) )
	        	$ret .= "&nbsp;{$imagecode}\n";
    	}
    } else {
        foreach ( $ar as $key => $value )
        {
            $sel = "";
            if ( $val == $key ) {
                $ret .= _t("__".$value);
                break;
            }
        }
	    if ( strlen($imagecode) )
	        $ret .= "&nbsp;{$imagecode}\n";
    }

    return print_row_content( $first_row, $arr, $ret, $tr_class, $columns, $width_first);
}

function print_rows_set_membership ( $first_row, $memberships_arr, $membership_info, $columns, $tr_class, $delim_class, $width_first = "" )
{
	$ret = "";
	if ( strlen($width_first) )
		$w = "width='$width_first'";

	// Print delimiter
	if ( $first_row )
	{
		$class1 = "class=join_header_first";
	}
	else
	{
		$class1 = "class=join_header";
	}
	$ret .= "
		<tr class=$delim_class>
			<td align=center colspan=$columns $class1>
				<b>". _t("_Membership2"). "</b>
			</td>
		</tr>";

	// Print existing membership information
	$first_row = 1;
	$class1 = "class=join_td_1_first";
	$class2 = "class=join_td_2_first";

	$current_membership = $membership_info['Name'];
	if ( $membership_info['ID'] != MEMBERSHIP_ID_STANDARD )
	{
		if ( is_null($membership_info['DateExpires']) )
			$current_membership .= ", ". _t( "_MEMBERSHIP_EXPIRES_NEVER" );
		else
		{
			$days_left = (int)( ($membership_info['DateExpires'] - time()) / (24 * 3600) );
			$current_membership .= ", ". _t( "_MEMBERSHIP_EXPIRES_IN_DAYS", $days_left );
		}
	}

	$ret .= "
		<tr>
			<td $w $class1>
				". _t("_Current membership") ."
			</td>
			<td $class2 colspan=". ($columns - 1) .">
				$current_membership
			</td>
		</tr>";

	// Print set new membership controls
	$first_row = 0;
	$class1 = "class=join_td_1";
	$class2 = "class=join_td_2";

	$ret .= "
		<tr>
			<td $w $class1>
				". _t("_Set membership") ."
			</td>
			<td $class2 colspan=". ($columns - 1) .">
			<script type=\"text/javascript\">
			function checkStandard()
			{
				selectMembership = document.getElementById('MembershipID');
				if(selectMembership.value == ".MEMBERSHIP_ID_STANDARD.")
				{
					document.getElementById('MembershipDays').disabled = true;
					document.getElementById('MembershipImmediately').disabled = true;
				}
				else
				{
					document.getElementById('MembershipDays').disabled = false;
					document.getElementById('MembershipImmediately').disabled = false;
				}
			}
			</script>
				<select id=\"MembershipID\" name=\"MembershipID\" onchange=\"checkStandard()\" class=\"select_set_membership\">";

	foreach ( $memberships_arr as $membershipID => $membershipName )
	{
		if ( $membershipID == MEMBERSHIP_ID_NON_MEMBER )
			continue;
		$selected = ( $membership_id == $membershipID ? " selected=\"selected\" " : "" );
		$ret .= "
					<option value=\"$membershipID\" {$selected}>$membershipName</option>";
	}

	$ret .= "
				</select>
				&nbsp;". _t("_for") ."&nbsp;
				<input disabled=\"disabled\" id=\"MembershipDays\" type=\"text\" class=\"no\" size=\"7\" name=\"MembershipDays\" value=\"unlimited\"
				onFocus=\"javascript: if (MembershipDays.value == 'unlimited') { MembershipDays.value = ''; }\"
				onBlur=\"javascript: if (MembershipDays.value == '') { MembershipDays.value = 'unlimited'; }\">". _t("_days") ."<br />
				<div style=\"padding-left: 15px;\">
					<input disabled=\"disabled\" id=\"MembershipImmediately\" type=\"checkbox\" name=\"MembershipImmediately\" style=\"vertical-align: middle;\" />&nbsp;<label for=\"MembershipImmediately\">". _t("_starts immediately") ."</label>
				</div>
			</td>
		</tr>";

	return $ret;
}

// Search option table start
function print_row_search_content( $name, $content, $search_hide, $class = "", $section_hide = 0, $display_name = "" )
{
    global $odd;
    global $c_search_table;


    $name2 = $name;
    $name = _t($display_name);

    if ($search_hide)
    {
        $div_hide = "none";
        $div_show = "block";
    }
    else
    {
        $div_hide = "block";
        $div_show = "none";
    }

    if ( $section_hide )
    {
    	$section_display = 'none';
    }
    else
    {
    	$section_display = 'block';
    }

	$ret = "
		<div class=\"search_show_hide\">
			<div id=\"div_hide_$name2\" style=\"display:$div_hide\">
				<a href=\"javascript:void(0)\" onClick=\"javascript: ShowHideHide(document.getElementById('div_show_$name2'),document.getElementById('table_hide_$name2'),document.getElementById('div_hide_$name2'));\">"._t("_Hide")."</a>
			</div>

			<div id=\"div_show_$name2\" style=\"display:$div_show\">
				<a href=\"javascript:void(0)\" onClick=\"javascript: ShowShowHide(document.getElementById('table_hide_$name2'),document.getElementById('div_hide_$name2'),document.getElementById('div_show_$name2'));\">"._t("_Show")."</a>
			</div>
		</div>

		<table id=\"table_hide_$name2\" cellspacing=\"1\" cellpadding=\"0\" style=\"display:$div_hide;\" align=\"center\" width=\"85%\" border=\"0\">
				". $content ."
		</table>";

    return "<div id=\"section_{$name2}\" style=\"display:{$section_display}\">". DesignBoxContentBorder( $name, $ret ) . "</div>";
}

function print_row_search_list_ref ( $arr, $val = "", $class = "" )
{
    $align="center";
    $fname = get_field_name ( $arr );
    $funcbody = $arr[extra];
    $func = create_function("", $funcbody);
    $ar = $func();
    $ret = "";

    $ret .= "<tr><td align=\"$align\"><select name=\"".$fname."[]\" size=\"8\" 	multiple=\"multiple\" class=\"select_multiple\">";
    foreach ( $ar as $key => $value )
    {
        if ( $key == 0 && $value == "I prefer not to say") continue;
        $sel = "";
        if ( $val == $key ) $sel = "selected=\"selected\"";
                       else $sel = "";
        $ret .= "<option $sel value=\"$key\">"._t("__".$value)."</option>";
    }
    $ret .= "</select></td></tr>";

    return $ret;
}


function print_row_search_check_enum ( $arr, $val = "", $class = "" )
{
    $align="center";
    $b = 0;
    $i = 0;
    $fname = get_field_name ( $arr );
    $ret = "";

    $vals = preg_split ("/[,\']+/", $arr['extra'], -1, PREG_SPLIT_NO_EMPTY);

    if ( !$arr['search_cols'] ) $arr['search_cols'] = 1;
    $col_w = "".sprintf("%.0f", 100 / $arr['search_cols'])."%";

    foreach ( $vals as $value )
    {
        if ( !strlen(trim($value)) ) continue;

        if ( !($i % $arr['search_cols']) && !$b ) {
            $ret .= "<tr>";
            $b = 1;
        }
        $sel = "";

        $ret .= "<td align=\"$align\" width=\"$col_w\" style=\"border:0px solid red;\"><input type=\"checkbox\" name=\"{$fname}_{$value}\" id=$fname$value style=\"border:0px solid red;\" />&nbsp;<label for=$fname$value style=\"white-space:nowrap;\">"._t("_".$value)."</label></td>";
        ++$i;
        if ( !($i % $arr['search_cols']) && $b) {
            $ret .= "</tr>";
            $b = 0;
        }
    }

    return $ret;
}

function print_row_search_text_text ( $arr, $val = "", $class = "" )
{
    $fname = get_field_name ( $arr );
    return  "<tr><td align=\"$align\"><input name=\"$fname\" size=\"12\"  class=\"no\" /></td></tr>\n";
}

function print_row_search_check_ref ( $arr, $val = "", $class = "" )
{
    $align="left";
    $b = 0;
    $i = 0;
    $fname = get_field_name ( $arr );
    $funcbody = $arr['extra'];
    $func = create_function('', $funcbody);
    $ar = $func();
    if ( !$arr['search_cols'] ) $arr['search_cols'] = 1;
    $col_w = "".sprintf("%.0f", 100 / $arr['search_cols'])."%";
    $ret = "";

    foreach ( $ar as $key => $value )
    {
        if ( $key == 0 && $value == "I prefer not to say") continue;
        if ( !($i % $arr[search_cols]) && !$b )
        {
            $ret .= "<tr>";
            $b = 1;
        }
        $sel = "";
        if ( !strcmp($val,$key) ) $sel = "checked=\"checked\"";
                       else $sel = "";
        $ret .= "<td align=\"$align\" width=\"$col_w\"><input type=\"checkbox\" name=\"{$fname}_{$key}\" id=\"$fname$key\" $sel />&nbsp;<label for=$fname$key style=\"white-space:nowrap;\">"._t("__".$value)."</label></td>";
        ++$i;
        if ( !($i % $arr['search_cols']) && $b)
        {
            $ret .= "</tr>";
            $b = 0;
        }
    }

    return $ret;
}

function print_row_search_text ( $arr, $val = "", $class = "", $section_hide = 0 )
{
    switch ($arr['type'])
    {
        case 'a': // text area
        case 'c': // input box
            $out = print_row_search_text_text ( $arr, $val, $class );
            break;
    }
    return print_row_search_content( get_field_name($arr), $out, $arr['search_hide'], $class, $section_hide, $arr['namedisp'] );
}

function print_row_search_check_set_set ( $arr, $val = "", $class = "", $input_value = "")
{
	$align="left";
	$b = 0;
	$i = 0;
	$fname = get_field_name ( $arr );
	$vals = preg_split ("/[,\']+/", $arr['extra'], -1, PREG_SPLIT_NO_EMPTY);
	$ret = "";

	foreach ( $vals as $v )
	{
		if ( !($i % $arr['search_cols']) && !$b )
		{
			$ret .= "<tr>";
			$b = 1;
		}

		if ( !strlen(trim($v)) ) continue;
		if ( is_in_set( $v, $val ) )
			$sel = "checked=\"checked\"";
		else
			$sel = "";

		if ( 'caption' == $input_value )
		{
			$ret .= "<td align=\"$align\"><input name=\"{$fname}_{$i}\" id=\"{$fname}_{$i}\" type=\"checkbox\" style=\"vertical-align: middle;\" $sel value=\""._t("_$v")."\" />&nbsp;<label for=\"{$fname}_{$i}\" style=\"white-space:nowrap;\">"._t("_$v")."</label></td>";
		}
		elseif ( "" != $input_value )
		{
			$ret .= "<td align=$align><input name=\"{$fname}_{$i}\" id=\"{$fname}_{$i}\" type=\"checkbox\" style=\"vertical-align:middle;\" $sel value=\"". $input_value ."\" />&nbsp;<label for=\"{$fname}_{$i}\" style=\"white-space:nowrap;\">"._t("_$v")."</label></td>";
		}
		else
		{
			$ret .= "<td align=$align><input name=\"{$fname}_{$i}\" id=\"{$fname}_{$i}\" type=\"checkbox\" style=\"vertical-align: middle;\" $sel />&nbsp;<label for=\"{$fname}_{$i}\" style=\"white-space:nowrap;\">"._t("_$v")."</label></td>";
		}

		$i++;
		if ( !($i % $arr['search_cols']) && $b )
		{
			$ret .= "</tr>";
			$b = 0;
		}
	}
	if ( $b )
	{
		$ret .= "</td>";
	}

	return $ret;
}

function print_row_search_radio_enum ( $arr, $val = "", $class = "", $javascript = "" )
{
    $align="left";
    $b = 0;
    $i = 0;

    $fname = get_field_name ( $arr );
    $vals = preg_split ("/[,\']+/", $arr['extra'], -1, PREG_SPLIT_NO_EMPTY);
    $ret = "";

    foreach ( $vals as $v )
    {
        if ( !($i % $arr['search_cols']) && !$b ) {
            $ret .= "<tr>";
            $b = 1;
        }

        if ( strlen(trim($v)) <= 0 ) continue;
        if ( $v == $val ) $sel = " checked=\"checked\"";
                     else $sel = "";
        $ret .= "<td align=\"$align\" ><input name=\"$fname\" id=\"{$fname}_{$v}\" type=\"radio\" value=\"$v\" style=\"vertical-align: middle;\" $sel $javascript />&nbsp;<label for=\"{$fname}_{$v}\"  style=\"white-space:nowrap;\">"._t("_$v")."</label></td>";
        ++$i;
        if ( !($i % $arr['search_cols']) && $b) {
            $ret .= "</tr>\n";
            $b = 0;
        }
    }

    if ( $b )
    {
    $ret .= "</tr>\n";
    }

    return $ret;

}

function print_row_search_daterange_en ( $arr, $val = "", $class = "" )
{
    $align="center";
    $fname = get_field_name ( $arr );
    $ret = "";

    $ret .= "<tr><td align=\"$align\">";
    $vals = preg_split ("/[,\']+/", $arr[search_extra], -1, PREG_SPLIT_NO_EMPTY);
    $ret .= _t("_from")."&nbsp; <select name=${fname}_start>\n";

    for ( $i = $vals[0] ; $i <= $vals[1] ; ++$i )
    {
        if ( $i == $vals[0] ) $sel = " selected=\"selected\" ";
                         else $sel = "";
        $ret .= "<option value=\"$i\" $sel>$i</option>\n";
    }
    $ret .= "</select>\n";
    if ( $arr[search_cols] <= 1 ) {
        $ret .= "</td></tr><tr><td align=$align>";
    }
    $ret .= _t("_to")."&nbsp; <select name=${fname}_end>\n";
    for ( $i = $vals[0] ; $i <= $vals[1] ; ++$i )
    {
        if ( $i == $val ) $sel = " selected=\"selected\" ";
                         else $sel = "";
        $ret .= "<option value=\"$i\" $sel>$i</option>\n";
    }
    $ret .= "</select>\n";
    $ret .= "</td></tr>";

    return $ret;
}

function print_row_search_check ( $arr, $val = "", $class = "", $section_hide = 0 )
{
    switch ($arr['type'])
    {
        case 'r': // reference to array
            $out = print_row_search_check_ref ( $arr, $val, $class );
            break;
        case 'e': // reference to array
            $out = print_row_search_check_enum ( $arr, $val, $class );
            break;
    }
    return print_row_search_content( get_field_name($arr), $out, $arr['search_hide'], $class, $section_hide, $arr['namedisp'] );
}

function print_row_search_check_set ( $arr, $val = "", $class = "", $input_value = "", $section_hide = 0 )
{

    switch ($arr['type'])
    {
    	case 'r': // reference to array
    		$out = print_row_search_check_ref ( $arr, $val, $class );
    	break;
    	case 'set': // reference to array
    		$out = print_row_search_check_set_set ( $arr, $val, $class, $input_value );
    	break;
    }
    return print_row_search_content( get_field_name($arr), $out, $arr['search_hide'], $class, $section_hide, $arr['namedisp'] );
}

function print_row_search_radio ( $arr, $val = "", $class = "", $javascript = "", $section_hide = 0 )
{
    switch ($arr['type'])
    {
        case 'rb': // radio buttons
        case 'e': // enum
            $out = print_row_search_radio_enum ( $arr, $val, $class, $javascript );
            break;
    }
    return print_row_search_content( get_field_name($arr), $out, $arr['search_hide'], $class, $section_hide, $arr['namedisp'] );
}

function print_row_search_list_enum($arr, $val="", $class="")
{
    $align="center";
	$fname = get_field_name ( $arr );

    $options = $arr['extra'];

    $options = trim($options, "'");

    $options = preg_split("/'\s*,\s*'/", $options);

	$ret = "<tr><td align=\"$align\"><select name=\"{$fname}[]\" class=\"$class\" multiple=\"multiple\">";
	foreach ($options as $value)
	{
		$ret .= "<option value=\"$value\">"._t('_'.$value)."</option>";
	}
	$ret .= "</select></td></tr>";

	return $ret;
}

function print_row_search_list ( $arr, $val = "", $class = "", $section_hide = 0 )
{
    switch ($arr['type'])
    {
        case 'r': // reference to array
            $out = print_row_search_list_ref ( $arr, $val, $class );
            break;
        case 'e': // enum
        	$out = print_row_search_list_enum($arr, $val, $class);
        	break;
    }
    return print_row_search_content( get_field_name($arr), $out, $arr['search_hide'], $class, $section_hide, $arr['namedisp'] );
}

function print_row_search_daterange( $arr, $val = "", $class = "", $section_hide = 0 )
{
    $out = print_row_search_daterange_en ( $arr, $val, $class );

    return print_row_search_content( get_field_name($arr), $out, $arr['search_hide'], $class, $section_hide, $arr['namedisp'] );
}


?>