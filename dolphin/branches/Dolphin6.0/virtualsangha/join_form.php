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
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// --------------- page variables and login

$_page['name_index'] 	= 3;
$_page['css_name']		= 'join_form.css';


if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );

$_page['header'] = _t( "_JOIN_H" );
$_page['header_text'] = _t( "_JOIN_H" );

if( $logged['member'] )
{
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = _t( '_Sorry, you\'re already joined' );
	PageCode();
	exit;
}

// --------------- GET/POST actions

$page = (int)htmlspecialchars_adv($_POST['page']);

// create number of current page and additional sql conditions for join page
$page = (!$page) ? '1' : $page + 1;

// determine maximum number of join page parts
$query = "SELECT MAX( FLOOR( `join_page` / 1000 ) ) FROM `ProfilesDesc` WHERE `visible` & 2 AND (FIND_IN_SET('0',`show_on_page`) OR FIND_IN_SET('3',`show_on_page`))";
$row = db_arr($query);
$join_pages_num = $row[0];
// make last page of join form
if ( $page > $join_pages_num )
	$page = 'done';

// check fields from previous page
if ( 'done' != $page )
	$join_page_check_limit = " AND `join_page` < '". ($page * 1000) . "' AND `join_page` >= 1000";
else
	$join_page_check_limit = '';

//-----------------------------------------------------------

// --------------- [ END ] GET/POST actions

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();


// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
	global $site;
	global $dir;
	global $tmpl;
	global $page;
	global $join_page_check_limit;
	global $join_pages_num;
	global $p_arr;
	global $_page;
	global $en_aff;
	global $oTemplConfig;
	global $newusernotify;

	$enable_security_image = getParam('enable_security_image');
	$autoApproval_ifJoin = isAutoApproval('join');

	ob_start();	

switch ( $page )
{
// fill inputs with values from precede join pages
	case ( $page > 1 ) :
	    $hidden_vals = '';
	// inputs with POST values
    	    $respd = db_res("SELECT * FROM ProfilesDesc WHERE `visible` & 2 AND ( FIND_IN_SET('0',show_on_page) OR FIND_IN_SET('".(int)$_page['name_index']."',show_on_page)) $join_page_check_limit ORDER BY `order` ASC");
		while ( $arrpd = mysql_fetch_array($respd) )
		{
			$fname = get_input_name( $arrpd );

			switch ( $arrpd['type'] )
			{
				case 'set': // set of checkboxes
					$vals = preg_split ("/[,\']+/", $arrpd['extra'], -1, PREG_SPLIT_NO_EMPTY);
					$p_arr[$fname] = '';
					foreach ( $vals as $v )
					{
						if ( strlen(trim($v)) <= 0 ) continue;
						$hidden_vals .= '<input type="hidden" name="' . ($fname."_".$v) . '" value="' . process_pass_data($_POST[$fname."_".$v]) . '">';
						$p_arr[$fname."_".$v] = process_pass_data($_POST[$fname."_".$v]);
						if ( $_POST[$fname."_".$v] == 'on' )
						{
							if ( strlen($p_arr[$fname]) )
								$p_arr[$fname] .= ",$v";
							else
								$p_arr[$fname] .= $v;
						}
					}
					break;
				case 'date':
						$p_arr[$fname] = sprintf("%04d-%02d-%02d",
							(int)$_POST[$fname . '_year'],
							(int)$_POST[$fname . '_month'],
							(int)$_POST[$fname . '_day']
							);
						$hidden_vals .= '<input type="hidden" name="' . $fname . '_year"  value="' . (int)$_POST[$fname . '_year'] . '" />';
						$hidden_vals .= '<input type="hidden" name="' . $fname . '_month" value="' . (int)$_POST[$fname . '_month'] . '" />';
						$hidden_vals .= '<input type="hidden" name="' . $fname . '_day"   value="' . (int)$_POST[$fname . '_day'] . '">';
					break;

				default:
					if ( $arrpd['get_value'] )
					{
						$funcbody = $arrpd['get_value'];
						$func = create_function('$arg0', $funcbody);
						$hidden_vals .= '<input type="hidden" name="' . $fname . '" value="' . process_pass_data($_POST[$fname]) . '">';
						$p_arr[$fname] = process_pass_data($func($_POST));
					}
					else
					{
						$hidden_vals .= '<input type="hidden" name="' . $fname . '" value="' . process_pass_data($_POST[$fname]) . '">';
						$p_arr[$fname] = process_pass_data($_POST[$fname]);
					}
					break;
			}
		}


// check values

	    $query = "SELECT * FROM ProfilesDesc
				WHERE `visible` & 2 AND ( FIND_IN_SET('0',show_on_page) OR FIND_IN_SET('".(int)$_page['name_index']."',show_on_page)) $join_page_check_limit
				ORDER BY `join_page` ASC";
		$respd = db_res($query);

		while ( $arrpd = mysql_fetch_array($respd) )
		{
			if ( !strlen($arrpd['check']) ) continue;
			$fname = get_input_name ( $arrpd );

			$funcbody = $arrpd[check];
			$func = create_function('$arg0', $funcbody);
			if ( !$func($p_arr[$fname]))
			{
				$add_on .= report_err( _t($arrpd['because'], $arrpd['min_length'],$arrpd['max_length']) );
			}
		}

		$page = (!$add_on) ? $page : $page-1;

		break;

	break;

	case 'done':
	// fill array with POST values
    $respd = db_res("SELECT * FROM ProfilesDesc WHERE `visible` & 2 AND ( FIND_IN_SET('0',show_on_page) OR FIND_IN_SET('".(int)$_page['name_index']."',show_on_page)) $join_page_check_limit ORDER BY `order` ASC");
		while ( $arrpd = mysql_fetch_array($respd) )
		{
			$fname = get_input_name( $arrpd );

			switch ( $arrpd['type'] )
			{
				case 'set': // set of checkboxes
					$vals = preg_split ("/[,\']+/", $arrpd['extra'], -1, PREG_SPLIT_NO_EMPTY);
					$p_arr[$fname] = '';
					foreach ( $vals as $v )
					{
						if ( strlen(trim($v)) <= 0 ) continue;
						$hidden_vals .= '<input type="hidden" name="' . ($fname."_".$v) . '" value="' . process_pass_data($_POST[$fname."_".$v]) . '">';
						$p_arr[$fname."_".$v] = process_pass_data($_POST[$fname."_".$v]);
						if ( $_POST[$fname."_".$v] == 'on' )
						{
							if ( strlen($p_arr[$fname]) )
								$p_arr[$fname] .= ",$v";
							else
								$p_arr[$fname] .= $v;
						}
					}
					break;
				case 'date':
						$p_arr[$fname] = sprintf("%04d-%02d-%02d",
							(int)$_POST[$fname . '_year'],
							(int)$_POST[$fname . '_month'],
							(int)$_POST[$fname . '_day']
							);
						$hidden_vals .= '<input type="hidden" name="' . $fname . '_year"  value="' . (int)$_POST[$fname . '_year'] . '" />';
						$hidden_vals .= '<input type="hidden" name="' . $fname . '_month" value="' . (int)$_POST[$fname . '_month'] . '" />';
						$hidden_vals .= '<input type="hidden" name="' . $fname . '_day"   value="' . (int)$_POST[$fname . '_day'] . '">';
					break;
					
				
				default:
					if ( $arrpd['get_value'] )
					{
						$funcbody = $arrpd['get_value'];
						$func = create_function('$arg0',$funcbody);
						$hidden_vals .= '<input type="hidden" name="' . $fname . '" value="' . process_pass_data($_POST[$fname]) . '">';
						$p_arr[$fname] = process_pass_data($func($_POST));
					}
					else
					{
						$hidden_vals .= '<input type="hidden" name="' . $fname . '" value="' . process_pass_data($_POST[$fname]) . '">';
						$p_arr[$fname] = process_pass_data($_POST[$fname]);
					}
					break;
			}
		}

	// check values
	if ( $enable_security_image )
	{
		if (!isset($_POST['securityImageValue']) || !isset($_COOKIE['strSec']) || md5($_POST['securityImageValue']) != $_COOKIE['strSec'])
		{
			$page = $join_pages_num;
			$add_on .= report_err ( _t("_SIMG_ERR") );
		}
	}

	$respd = db_res("SELECT * FROM ProfilesDesc WHERE `visible` & 2 AND ( FIND_IN_SET('0',show_on_page) OR FIND_IN_SET('".(int)$_page['name_index']."',show_on_page)) $join_page_check_limit AND `join_page` > 0 ORDER BY `order` ASC");
	while ( $arrpd = mysql_fetch_array($respd) )
	{
		if ( !strlen(trim($arrpd['check'])) ) continue;
		$fname = get_input_name ( $arrpd );

	    $funcbody = $arrpd['check'];
		$func = create_function('$arg0', $funcbody);
		if ( !$func($p_arr[$fname]))
		{
			$page = floor($arrpd['join_page'] / 1000);
			$add_on .= report_err( _t($arrpd['because'], $arrpd['min_length'], $arrpd['max_length']) );
		}

	}

    break;

    default:
    break;

}

switch( $page )
{
	default:
		global $tmpl;
		if( $oTemplConfig -> customize['join_page']['showPageText'] )
			$page_text = _t( "_JOIN1", $page );
		echo $add_on;
		break;
}

switch ( $page )
{
	case 'done':
	// new profile creation

	$cl_values = "INSERT INTO `Profiles` SET ";
	$cl_first = 0;

	$respd = db_res("SELECT * FROM ProfilesDesc WHERE `visible` & 2 AND `to_db` = 1 AND ( FIND_IN_SET('0',show_on_page) OR FIND_IN_SET('".(int)$_page['name_index']."',show_on_page)) $join_page_check_limit ORDER BY `order` ASC");
	while ( $arrpd = mysql_fetch_array($respd) )
	{
		$fname = get_input_name( $arrpd );
		$dbname = get_field_name( $arrpd );
		$fval = $p_arr[$fname];

		if ($dbname == 'zip')
			$fval = strtoupper( str_replace(' ', '', $fval) );

		switch ( $arrpd['type'] )
		{
			case 'set': // set of checkboxes
			case 'r': // reference to array for combo box
			case 'a': // text Area
			case 'c': // input box
			case 'rb': // radio buttons
			case 'e': // enum combo box
			case 'en': // enum combo box with numbers
			case 'eny': // enum combo box with numbers
			case 'date': // date
				$fval = process_db_input( $fval, 0, 1 );
				$cl_values .= " `$dbname` = '$fval'";
				$cl_values .= ", ";
				break;
			case 'p':
				$fval = md5( process_pass_data( $fval ) );
				$cl_values .= " `$dbname` = '$fval'";
				$cl_values .= ", ";
				break;
		}
	}

	$cl_values .= " `LastReg` = NOW()";

	db_res($cl_values);
	$IDnormal = mysql_insert_id();

    $IDcrypt = crypt( $IDnormal, "secret_string" );  // encrypted ID for security purposes
    setcookie( "IDc", $IDcrypt, 0 , "/" );
	$_COOKIE['IDc'] = $IDcrypt;

	// Affiliate and friend checking
	if ( $en_aff && $_COOKIE['idAff'] )
	{
		$res = db_res("SELECT `ID` FROM `aff` WHERE `ID` = {$_COOKIE['idAff']} AND `Status` = 'active'");
		if ( mysql_num_rows( $res ) )
        {
			$res = db_res("INSERT INTO `aff_members` (`idAff`,`idProfile`) VALUES ({$_COOKIE['idAff']}, $IDnormal)");
		}
	}
	if ( $en_aff && $_COOKIE['idFriend'] )
	{
		$idFriend = getID( $_COOKIE['idFriend'] );
		if ( $idFriend )
        {
			$res = db_res( "UPDATE `Profiles` SET `aff_num` = `aff_num` + 1 WHERE `ID` = '$idFriend'" );
			createUserDataFile( $idFriend );
		}
	}

	if ( strcmp( crypt( $IDnormal, 'secret_string' ), $_COOKIE['IDc'] ) != 0 )
    {
		ob_end_clean();

		$_page['header'] = _t( "_Error" );

		$ret = "<table width=\"100%\" cellpadding=4 cellspacing=4><tr><td align=center class=text2>";
        $ret .= _t( "_MUST_HAVE_COOKIES" );
		$ret .= "</td></tr></table>";

        return $ret;
	}

	if ( getParam('autoApproval_ifNoConfEmail') == 'on' )
	{
		if ( getParam('autoApproval_ifJoin') )
		{
			db_res("UPDATE `Profiles` SET `Status`='Active' WHERE `ID`='{$IDnormal}'");
			$page_text =  _t( "_USER_ACTIVATION_SUCCEEDED" ) . $ret . $add_on;
			$message = getParam("t_Activation");
           	$subject = getParam('t_Activation_subject');
           	sendMail( $p_arr['Email'], $subject, $message, $IDnormal );
		}
		else
		{
			db_res("UPDATE `Profiles` SET `Status`='Approval' WHERE `ID`='{$IDnormal}'");
			$page_text = _t( "_USER_CONF_SUCCEEDED" ) . $add_on;
		}
		
		if ( $newusernotify )
		{
			$message = 
"New user {$p_arr['NickName']} with email {$p_arr['Email']} has been confirmed,
his/her ID is {$IDnormal}.
--
{$site['title']} mail delivery system
<Auto-generated e-mail, please, do not reply>
";
			$subject	= "New user confirmed";
			sendMail( $site['email_notify'], $subject, $message );
		}
	}
	
	else
	{	
	    $page_text = _t( "_JOIN3" ) . $add_on;
	    $page_text .= activation_mail( $IDnormal );
	    $page_text .= "<br /><br /><br /><br /><center>" . _t( "_UPLOAD_WHILE_WAITING", $site['url'] ) . "</center>";
	}

   	modules_add($IDnormal);
	if ( !$autoApproval_ifJoin )
		modules_block($IDnormal);

	createUserDataFile( $IDnormal );


// ----------------------------------------------------------
	echo "<div id=\"first_column\">";
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td align=center class=text2>";
    echo "<div align=justify>$page_text</div>";
    break;

    default:
	echo "<div id=\"first_column\">";
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td align=center class=text2>";
//-----------------------------------------------------------

	do
	{
		$join_page_limit = ('done' == $page ) ? " AND join_page > '" . ($join_pages_num * 1000) . "'" :
			" AND join_page > '" . ($page * 1000) . "' AND join_page < '" . (($page + 1) * 1000) . "'";

		$query = "SELECT COUNT(*) FROM `ProfilesDesc` WHERE `visible` & 2 $join_page_limit AND (FIND_IN_SET('0',show_on_page) OR FIND_IN_SET('3',show_on_page))";
		$res = db_res($query);
		$item_num = mysql_fetch_row($res);

		if ($item_num[0] <=0 && $page < $join_pages_num) $page++;
	}
	while ( $item_num[0] <= 0 && $page < $join_pages_num );

	$join_page_limit = ('done' == $page ) ? " AND join_page > '" . ($join_pages_num * 1000) . "'" :
		" AND join_page > '" . ($page * 1000) . "' AND join_page < '" . (($page + 1) * 1000) . "'";

	$hidden_vals .= "<input type=\"hidden\" name=\"page\" value=\"$page\" />";

//-----------------------------------------------------------

    echo "
	    <form name=\"jform\" method=\"post\" action=\"{$_SERVER['PHP_SELF']}\" " . (($join_pages_num == $page) ? "onSubmit=\"return validateJoinForm();\">" : ">") . "

	    $hidden_vals

	    <input type=hidden name=\"ID\" value=\"$IDnormal\" />

	    <div align=justify>$page_text</div>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\">";

	$first_row = 1;
	$respd = db_res("SELECT * FROM ProfilesDesc
			    WHERE `visible` & 2 AND ( FIND_IN_SET('0',show_on_page) OR FIND_IN_SET('".(int)$_page['name_index']."',show_on_page)) $join_page_limit
			    ORDER BY `join_page` ASC");
	
	if( $oTemplConfig -> customize['join_page']['show_3rd_col'] )
		$columns = 3;
	else
		$columns = 2;
	
	while ( $arrpd = mysql_fetch_array($respd) )
	{
		$fname = get_input_name( $arrpd );

        if ( $arrpd['get_value'] && $arrpd['to_db'] == 0 )
        {
            $funcbody = $arrpd['get_value'];
            $func = create_function('$arg0',$funcbody);
            $p_arr[$fname] = $func($p_arr);

        }

		$not_first_row = 0;
		switch ($arrpd['type'])
		{
    	case 'set': // set of checkboxes
	        echo print_row_set ( $first_row, $arrpd, $p_arr[$fname], "table", 0, $columns );
			break;
	    case 'rb': // radio buttons
        	echo print_row_radio_button ( $first_row, $arrpd, $p_arr[$fname], "table", 0, $columns );
	        break;
		case 'r': // reference to array for combo box
			if ( $fname == 'Country' )
			{
				$onchange = "flagImage = document.getElementById('flagImageId'); flagImage.src = '{$site['flags']}' + this.value.toLowerCase() + '.gif';";
				if ( strlen($p_arr[$fname]) == 0 )
					$p_arr[$fname] = getParam( 'default_country' );
				$imagecode = '<img id="flagImageId" src="'. ($site['flags'].strtolower($p_arr[$fname])) .'.gif" alt="flag" />';
			}
			else
			{
				$onchange = '';
				$imagecode = '';
			}
			echo print_row_ref ( $first_row, $arrpd, $p_arr[$fname], "table", 0, $columns, '', 0, $onchange, $imagecode );
			break;
		case '0': // divider
			echo print_row_delim( $first_row, $arrpd, "panel", $columns );
			$not_first_row = 1;
    			$first_row = 1;
			break;
		case 'e': // enum combo box
			echo print_row_enum( $first_row, $arrpd, $p_arr[$fname], "table", $javascript, 0 );
			break;
		case 'en': // enum combo box with numbers
			echo print_row_enum_n( $first_row, $arrpd, $p_arr[$fname], "table", 0, $columns );
			break;
		case 'eny': // enum combo box with years
			echo print_row_enum_years( $first_row, $arrpd, $p_arr[$fname], "table", 0, $columns );
			break;
		case 'date': //date
			echo print_row_date( $first_row, $arrpd, $p_arr[$fname], "table", 0, $columns );
			break;
		case 'a': // text Area
			echo print_row_area( $first_row, $arrpd, $p_arr[$fname], "table", 0, $columns );
			break;
		case 'c': // input box
			echo print_row_edit( $first_row, $arrpd, $p_arr[$fname], "table", 0, $columns );
			break;
	    case 'p': // input box password
            echo print_row_pwd( $first_row, $arrpd, $p_arr[$fname], "table", 0, $columns );
            break;
    	default:
        		$not_first_row = 1;
			break;
    		}

    	    if ( !$not_first_row && $first_row == 1 )
				$first_row = 0;

	}


	echo 	"</table>";

	// show on the last page of join form
	if ( $join_pages_num == $page )
	{

?>
<script language=javascript>
<!--
    function validateJoinForm()
    {
        if ( document.forms['jform'].elements['i_agree'].checked ) return true;
        alert('<?php echo _t("_CLICK_AGREE"); ?>');
        return false;
    }
-->
</script>
<?
		echo "<br /><div class=\"security_image_block\"><center>\n";
		if ( $enable_security_image )
		{
			echo "
		    <img alt=\"Security Image\" src=\"simg/simg.php\" /><br /><br />" .
		    _t( "_Enter what you see:" ) . "<input name=\"securityImageValue\" type=\"text\" size=\"15\"><br /><br />";
		}
		echo "</center>";
		$ret = <<<ID
		<script type="text/javascript">
			function id_registration()
			{
				oCheckBox = document.getElementById( "boonex_id" );
				if( oCheckBox.checked )
					window.open( 'http://www.boonex.com/id/', '', 'width=800, height=600, menubar=yes, status=yes, resizable=yes, scrollbars=yes, toolbar=yes, location=yes')
			}
		</script>
ID;
		echo $ret;
		echo "
		    <div style=\"text-align:center;\"><input type=checkbox name=i_agree id=i_agree /><label for=i_agree>" . _t("_I AGREE", $site['url']) . "</label>&nbsp;&nbsp;</div>\n";

// BoonEx ID implementation
//		    <div style=\"text-align:center;\"><input type=checkbox name=\"boonex_id\" id=\"boonex_id\" /><label for=boonex_id>" . _t("_ID_CREATE", "http://www.boonex.com/id/" ) . "</label>&nbsp;&nbsp;</div>";
	}

	echo	"<br /><center><input onclick=\"id_registration();\" type=\"submit\" value=\"" . _t( "_Join" ) . "\"  /></center></form></div>";

    break;
}
	global $memberID;
	
    echo "</td></tr></table>";
    echo "</div>";
    echo "<div id=\"second_column\">";
    	echo "<div class=\"member_login\">";
    	$action = "login";
		$text = _t( '_Member Login' );
		$table       = "Profiles";
		$login_page  = "{$site['url']}member.php";
		$join_page   = "{$site['url']}join_form.php";
		$forgot_page = "{$site['url']}forgot.php";
		$template    = "{$dir['root']}templates/tmpl_{$tmpl}/join_login_form.html";

		echo LoginForm( $text,$action,$table,$login_page,$forgot_page,$template );

		echo "</div>";
		if( getParam( 'enable_get_boonex_id' ) )
		{
			echo "<div class=\"import_boonex_id\">";
			$action = "boonex";
			$text = '<div class="boonex_id">' . _t( '_Import BoonEx ID' ) . '</div>';
			$table       = "Profiles";
			$login_page  = "{$site['url']}member.php";
			$join_page   = "{$site['url']}join_form.php";
			$forgot_page = '';
			$template    = "{$dir['root']}templates/tmpl_{$tmpl}/join_login_form.html";

			echo LoginForm( $text,$action,$table,$login_page,$forgot_page,$template );

			echo "</div>";
		}
    echo "</div>";

    $ret = ob_get_clean();

    return $ret;
}

/**
 * print error message
 */
function report_err( $str )
{
    return "<font color=\"#880000\"><b>" . _t( "_Error" ) . ":</b> $str</font><br />";
}

function LoginForm( $text, $action, $table, $login_page, $forgot_page, $template = '' )
{
	global $site;
	global $dir;
	global $tmpl;

	$aFormReplace = array();
	
	$name_label = _t("_Nickname");
	
	$aFormReplace['header_text']    = $site['title'] . ' ' . $mem . ' Login';
	if( $action == "login" )
	{
		$aFormReplace['warning_text']   = $text;
		$aFormReplace['submit_label']   = _t("_Log In");
		$aFormReplace['form_onsubmit']  = 'return true;';
	}
	elseif( $action == 'boonex' )
	{
		$aFormReplace['warning_text']   = $text .
			'<div class="id">' .
				'<a href="javascript:void(0);"
				  onclick="window.open(\'http://www.boonex.com/unity/express/XML.php?module=form&amp;action=joinForm&amp;community=3\', \'Boonex_Sign_Up\', \'width=400,height=593,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=0\');">' .
					_t( '_Get BoonEx ID' ) .
				'</a>'.
			'</div>';
		
		$aFormReplace['submit_label']   = _t("_Import");
		
		$aFormReplace['form_onsubmit']  = 'getBoonexId( this, document.forms.jform ); return false;';
	}
	$aFormReplace['action_url']     = $login_page;
	$aFormReplace['relocate_url']   = $_SERVER['PHP_SELF'];
	$aFormReplace['name_label']     = $name_label;
	$aFormReplace['password_label'] = _t("_Password");
	
	if( $forgot_page )
	{
		$aFormReplace['forgot_page_url'] = $forgot_page;
		$aFormReplace['forgot_label']    = _t("_forgot_your_password") . '?';
	}
	else
	{
		$aFormReplace['forgot_page_url'] = '';
		$aFormReplace['forgot_label']    = '';
	}
	
	if( !strlen( $template ) )
		$template = "{$dir['root']}templates/tmpl_{$tmpl}/join_login_form.html";
	
	$ret = file_get_contents( $template );
	
	foreach( $aFormReplace as $key => $val )
		$ret = str_replace( "__{$key}__", $val, $ret );
	
	return $ret;
}

?>
