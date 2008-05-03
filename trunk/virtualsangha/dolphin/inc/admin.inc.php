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

require_once( 'header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'modules.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'sharing.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolBlogs.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolClassifieds.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolEvents.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolSharedMedia.php' );

$menu_color_0 = "#dddddd";

function login_form( $text = "", $member = 0, $bbAjaxMode = false )
{
	global $site;
	global $_page_cont;
	global $_page;
	global $admin_dir;
	global $logged;
	global $dir_dir;
	global $dir;
	global $tmpl;
	global $l,$d;

	switch( $member )
	{
		case 0:
			$mem         = _t("_Member");
			$table       = "Profiles";
			$login_page  = "{$site['url']}member.php";
			$join_page   = "{$site['url']}join.php";
			$forgot_page = "{$site['url']}forgot.php";
			$template    = "{$dir['root']}templates/tmpl_{$tmpl}/login_form.html";
		break;
		case 1:
			$mem         = 'Admin';
			$table	     = 'Admins';
			$login_page  = "{$site['url_admin']}index.php";
			$join_page   = '';
			$forgot_page = '';
			$template    = "{$dir['root']}{$admin_dir}/login_form.html";
		break;
		case 2:
			$mem         = "Affiliate";
			$table       = 'aff';
			$login_page  = "{$site['url_aff']}index.php";
			$join_page   = '';
			$forgot_page = '';
			$template    = "{$dir['root']}{$admin_dir}/login_form.html";
		break;
		case 3:
			$mem         = "Moderator";
			$table       = 'moderators';
			$login_page  = "{$site['url']}moderators/index.php";
			$join_page   = '';
			$forgot_page = '';
			$template    = "{$dir['root']}{$admin_dir}/login_form.html";
		break;
	}

	$_page['header'] = "{$site['title']} "._t("_Member Login");
	$_page['header_text'] = _t("_Member Login");

	// If path is divided with \\ (Windows) then use \\ instead of /
	$script_filename = strlen($_SERVER['PATH_TRANSLATED']) ? $_SERVER['PATH_TRANSLATED'] : $_SERVER['SCRIPT_NAME'];
	if ( strstr($script_filename, '/') )
	{
		$arr1 = split("/", $script_filename);
		$s1 = $arr1[count($arr1)-2];
	}
	elseif ( strstr($script_filename, '\\\\') )
	{
		$arr1 = split("\\\\", $script_filename);
		$s1 = $arr1[count($arr1)-3];
	}

	if ($bbAjaxMode && $member==0) {
		$template = "{$dir['root']}templates/tmpl_{$tmpl}/login_form_ajax.html";
	} elseif ($bbAjaxMode && $member==1) {
		$template = "{$dir['root']}templates/base/login_form_ajax_a.html";
	}
	$_page_cont[0]['page_main_code'] = PageCompLoginForm($text,$member,$mem,$table,$login_page,$join_page,$forgot_page,$template);

	if ($bbAjaxMode == true && $member == 1) {
		print TopCodeAdmin();
		print '<link href="'.$site['url_admin'].'styles/login_form_ajax.css" rel="stylesheet" type="text/css" />';
		print '<div style="border:0px solid #666;width:350px;margin:250px auto 0px;">';
		print $_page_cont[0]['page_main_code'];
		print '</div>';
		print BottomCode();
		exit;
	}

	if ($bbAjaxMode) {
		// if ($member == 1) {
			// print TopCodeAdmin();
			// print '<link href="'.$site['url_admin'].'styles/login_form_ajax.css" rel="stylesheet" type="text/css" />';
		// }
		print '<div style="border:0px solid #666;width:350px;margin:250px auto 0px;">';
		print $_page_cont[0]['page_main_code'];
		print '</div>';
		// if ($member == 1) {
			// print BottomCode();
		// }
		exit;
	}

	if ( $s1 != $admin_dir && $s1 != "aff" && $s1 != $dir_dir && $s1 != "events" && $s1 != "moderators")
	{
		$_page['name_index'] = 0;
		PageCode();
		exit;
	}
	else
	{
		require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
		
		$_page['header'] = "{$site['title']} $mem Login";
		
		TopCodeAdmin();
		echo $_page_cont[0]['page_main_code'];
		
		if( strlen( $text ) )
		{
			?>
			<script type="text/javascript">
				addEvent( window, 'load', function(){ alert( '<?= $text ?>' ); } );
			</script>
			<?
		}
		BottomCode();
	}
}

function PageCompLoginForm( $text, $member, $mem, $table, $login_page, $join_page, $forgot_page, $template = '' )
{
	global $site;
	global $dir;
	global $tmpl;

	$aFormReplace = array();
	
	if ( $member == 1 )
		$name_label = _t("_Log In");
	else
		if ( $member == 2 )
			$name_label = _t("_ID");
		else
			$name_label = _t("_E-mail or ID");
	
	$aFormReplace['header_text']    = $site['title'] . ' ' . $mem . ' Login';
	$aFormReplace['warning_text']   = $text;
	$aFormReplace['action_url']     = $login_page;

	if( !$sUrlRelocate = $_REQUEST['relocate'] or basename( $_REQUEST['relocate'] ) == 'index.php' or basename( $_REQUEST['relocate'] ) == 'join.php' )
		$sUrlRelocate = $_SERVER['PHP_SELF'];

	$aFormReplace['site_a_url']   = $site['url']; //$_SERVER['PHP_SELF'];
	$aFormReplace['relocate_url']   = htmlspecialchars( $sUrlRelocate ); //$_SERVER['PHP_SELF'];
	$aFormReplace['images']			= $site['images'];
	$aFormReplace['name_label']     = $name_label;
	$aFormReplace['password_label'] = _t("_Password");
	$aFormReplace['submit_label']   = _t("_Log In");
	$aFormReplace['remeber_label']	= _t("_Remember password");
	$sLoginC = _t('_PROFILE_ERR');
	$aFormReplace['form_onsubmit']   = <<<EOF
EcecuteAjax('check_login', '', '{$sLoginC}');
return false;
EOF;
	
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
	
	if( $join_page )
	{
		$aFormReplace['not_a_member']  = _t('_not_a_member');
		
		$aFormReplace['or']            = _t( '_or' );
		$aFormReplace['join_label']    = _t( '_Join now' );
		$aFormReplace['join_page_url'] = $join_page;
	}
	else
	{
		$aFormReplace['not_a_member']  = '';
		$aFormReplace['join_label']    = '';
		$aFormReplace['join_page_url'] = '';
	}
	
	
	if( !strlen( $template ) )
		$template = "{$dir['root']}templates/tmpl_{$tmpl}/login_form.html";
	
	$ret = file_get_contents( $template );
	
	foreach( $aFormReplace as $key => $val )
		$ret = str_replace( "__{$key}__", $val, $ret );
	
	return $ret;
}

function activation_mail( $ID, $text = 1 )
{
	global $site;
	global $ret;

	$ID = (int)$ID;
	$p_arr = db_arr( "SELECT `Email` FROM `Profiles` WHERE `ID` = '$ID'" );
	if ( !$p_arr )
	{
		$ret['ErrorCode'] = 7;
	    return false;
	}



	$message    = getParam( 't_Confirmation' );
	$subject	= getParam( 't_Confirmation_subject' );
	$recipient  = $p_arr['Email'];

	$sConfirmationCode	= base64_encode( base64_encode( crypt( $recipient, "secret_confirmation_string" ) ) );
	$sConfirmationLink	= "{$site['url']}profile_activate.php?ConfID={$ID}&ConfCode=" . urlencode( $sConfirmationCode );

	$aPlus = array();
	$aPlus['ConfCode'] = $sConfirmationCode;
	$aPlus['ConfirmationLink'] = $sConfirmationLink;

	$mail_ret = sendMail( $recipient, $subject, $message, $ID, $aPlus );

	if ( $mail_ret )
	{
		if ( $text )
		{
			$page_text .= '<div class="Notice">' . _t("_EMAIL_CONF_SENT") . "</div>";
			
			$page_text .= "<center><form method=get action=\"{$site['url']}profile_activate.php\">";
			$page_text .= "<table class=text2 cellspacing=0 cellpadding=0><td><b>"._t("_ENTER_CONF_CODE").":</b>&nbsp;</td><td><input type=hidden name=\"ConfID\" value=\"{$ID}\">";
			$page_text .= '<input class=no type="text" name="ConfCode" size=30></td><td>&nbsp;</td>';
			$page_text .= '<td><input class=no type="submit" value="'._t("_Submit").'"></td></table>';
			$page_text .= '</form></center><br />';
	    }
	    else
			return true;
	}
	else
	{
	    if ( $text )
			$page_text .= "<br /><br />"._t("_EMAIL_CONF_NOT_SENT");
	    else
		{
			$ret['ErrorCode'] = 10;
			return false;
		}
	}

	if ( $text )
		return $page_text;
	else
		return true;
}


function mem_expiration_letter( $ID, $membership_name, $expire_days )
{
	global $site;

	$ID = (int)$ID;

	if ( !$ID )
		return false;

	$p_arr = db_arr( "SELECT `Email` FROM `Profiles` WHERE `ID` = $ID", 0 );
	if ( !$p_arr )
		return false;

	// Get notification message body and subject from global settings.
	$message    = getParam('t_MemExpiration');
	$subject    = getParam('t_MemExpiration_subject');

	$recipient  = $p_arr['Email'];

	$aPlus = array();
	$aPlus['MembershipName'] = $membership_name;
	$aPlus['ExpireDays'] = $expire_days;

	$mail_ret = sendMail( $recipient, $subject, $message, $ID, $aPlus  );

	if ($mail_ret)
		return true;
	else
		return false;
}

function get_vars($with_page = 1, $with_per_page = 1, $with_sort_by = 1, $with_sort_order = 1, $with_gallery_view = 1)
{
	global $affID;

	$get_parameters = "?";

	// common parameters
	if ( isset($_REQUEST['ID']) && (int)$_REQUEST['ID'] > 0 )
		$get_parameters .= 'ID='. (int)$_REQUEST['ID'] .'&amp;';
	if ( isset($_REQUEST['NickName']) && strlen($_REQUEST['NickName']) > 0 )
		$get_parameters .= 'NickName='. process_pass_data($_REQUEST['NickName']) .'&amp;';
	if ( isset($_REQUEST['gallery_view']) && (int)$_REQUEST['gallery_view'] > 0 && $with_gallery_view )
		$get_parameters .= 'gallery_view='. (int)$_REQUEST['gallery_view'] .'&amp;';
	if ( isset($_REQUEST['photos_only']) && strlen($_REQUEST['photos_only']) > 0 )
		$get_parameters .= ($_REQUEST['photos_only'] == 'on' ? 'photos_only=on&amp;' : '');
	if ( isset($_REQUEST['online_only']) && strlen($_REQUEST['online_only']) > 0 )
	{
		$get_parameters .= 'online_only=on&amp;';
	}
	// navigation parameters
	if ( isset($_REQUEST['page']) && (int)$_REQUEST['page'] > 0 && $with_page )
		$get_parameters .= 'page='. (int)$_REQUEST['page'] .'&amp;';
	if ( isset($_REQUEST['p_per_page']) && (int)$_REQUEST['p_per_page'] > 0 && $with_per_page )
		$get_parameters .= 'p_per_page='. (int)$_REQUEST['p_per_page'] .'&amp;';
	if ( isset($_REQUEST['sortby']) && strlen($_REQUEST['sortby']) > 0 && $with_sort_by )
		$get_parameters .= 'sortby='. process_pass_data($_REQUEST['sortby']) .'&amp;';
	if ( isset($_REQUEST['sortorder']) && strlen($_REQUEST['sortorder']) > 0 && $with_sort_order )
		$get_parameters .= ($_REQUEST['sortorder'] == 'ASC' ? 'sortorder=ASC&amp;' : 'sortorder=DESC&amp;');

	// ZIP search parameters
	if ( isset($_REQUEST['zip']) && strlen($_REQUEST['zip']) > 0 )
		$get_parameters .= 'zip='. process_pass_data($_REQUEST['zip']) .'&amp;';
	if ( isset($_REQUEST['metric']) && strlen($_REQUEST['metric']) > 0 )
		$get_parameters .= ($_REQUEST['metric'] == 'miles' ? 'metric=miles&amp;' : 'metric=km&amp;');
	if ( isset($_REQUEST['distance']) )
		$get_parameters .= 'distance='. (int)$_REQUEST['distance'] .'&amp;';

	// extra parameters
	if ( isset($_REQUEST['profiles']) && strlen($_REQUEST['profiles']) > 0 )
		$get_parameters .= 'profiles='. process_pass_data($_REQUEST['profiles']) .'&amp;';
	if ( isset($_REQUEST['status']) && strlen($_REQUEST['status']) > 0 )
		$get_parameters .= 'status='. process_pass_data($_REQUEST['status']) .'&amp;';

	// admin parameters
	if ( isset($_REQUEST['search']) && strlen($_REQUEST['search']) > 0 )
		$get_parameters .= 'search='. process_pass_data($_REQUEST['search']) .'&amp;';
	if ( isset($_REQUEST['s_nickname']) && strlen($_REQUEST['s_nickname']) > 0 )
		$get_parameters .= 's_nickname='. process_pass_data($_REQUEST['s_nickname']) .'&amp;';

	// affiliate parameters
	if ( isset($affID) && (int)$affID > 0 )
		$get_parameters .= 'affID='. (int)$affID .'&amp;';

	$respd = db_res("SELECT * FROM `ProfileFields` WHERE `Type` <> 'none' ORDER BY `Type` ASC");
	while ( $arrpd = mysql_fetch_array($respd) )
	{
		//$fname = get_field_name ( $arrpd );
		$fname = $arrpd['Name'];
		switch ($arrpd['search_type'])
		{
			case 'select_one':
			case 'text':
			case 'area':
				$fval = process_pass_data($_REQUEST[$fname]);
				if ( isset($_REQUEST[$fname]) && strlen($fval) )
				{
					$get_parameters .= "{$fname}={$fval}&amp;";
				}
				break;

			/*case 'list':
				$fval = $_REQUEST[$fname];
				if ( is_array($fval) && count($fval) > 0 )
				{
					while ( list( $key, $val ) = each( $fval ) )
						$get_parameters .= "{$fname}[]=". process_pass_data($val) ."&amp;";
				}
				break;*/

			/*case 'check':
				if (  $arrpd['type'] == 'r' )
				{
					$findok = 0;
					$funcbody = $arrpd['extra'];
					$func = create_function("", $funcbody);
					$ar = $func();

					foreach ( $ar as $key => $value )
					{
						if ( $_REQUEST["{$fname}_{$key}"] == "on" )
						{
							$findok = 1;
							break;
						}
					}

					if ( is_array($ar) && $findok )
					{
						foreach ( $ar as $key => $value )
						{
							if ( $_REQUEST["{$fname}_{$key}"] == "on" )
								$get_parameters .= "{$fname}_{$key}=on&amp;";
						}
					}
				}
				elseif ( $arrpd['type'] == 'e' )
				{
					$findok = 0;

					$vals = preg_split ("/[,\']+/", $arr['extra'], -1, PREG_SPLIT_NO_EMPTY);

					foreach ( $vals as $key )
					{
						if ( $_REQUEST["{$fname}_{$key}"] == "on" )
						{
							$findok = 1;
							break;
						}
					}

					if ( $findok )
					{
						foreach ( $vals as $key )
						{
							if ( $_REQUEST["{$fname}_{$key}"] == "on" )
								$get_parameters .= "{$fname}_{$key}=on&amp;";
						}
					}
				}
				break;*/

			/*case 'check_set':
				$vals = preg_split ("/[,\']+/", $arrpd['extra'], -1, PREG_SPLIT_NO_EMPTY);
				$offset = 0;

				foreach ( $vals as $v )
				{
					if ( $_REQUEST["{$fname}_{$offset}"] == "on" )
						$get_parameters .= "{$fname}_{$offset}=on&amp;";
					$offset++;
				}

				break;*/

			case 'date':
				$fval_s = process_pass_data($_REQUEST["{$fname}_start"]);
				$fval_e = process_pass_data($_REQUEST["{$fname}_end"]);

				if ( strlen($fval_s) )
				{
					$get_parameters .= "{$fname}_start={$fval_s}&amp;";
				}
				if ( strlen($fval_e) )
				{
					$get_parameters .= "{$fname}_end={$fval_e}&amp;";
				}
				break;
		}
	}

	// exclude last character from the string
	return $get_parameters;
}

function get_vars_controls($with_page = 1, $with_per_page = 1, $with_sort_by = 1, $with_sort_order = 1, $with_gallery_view = 1)
{
	global $affID;

	$get_inputs = "";

	// common parameters
	if ( isset($_REQUEST['ID']) && (int)$_REQUEST['ID'] > 0 )
		$get_inputs .= '<input type="hidden" name="ID" value="'. (int)$_REQUEST['ID'] .'" />';
	if ( isset($_REQUEST['NickName']) && strlen($_REQUEST['NickName']) > 0 )
		$get_inputs .= '<input type="hidden" name="NickName" value="'. process_pass_data($_REQUEST['NickName']) .'" />';
	if ( isset($_REQUEST['gallery_view']) && (int)$_REQUEST['gallery_view'] > 0 && $with_gallery_view )
		$get_inputs .= '<input type="hidden" name="gallery_view" value="'. (int)$_REQUEST['gallery_view'] .'" />';
	if ( isset($_REQUEST['photos_only']) && strlen($_REQUEST['photos_only']) > 0 )
		$get_inputs .= ($_REQUEST['photos_only'] == 'on' ? '<input type="hidden" name="photos_only" value="on" />' : '');
	if ( isset($_REQUEST['online_only']) && strlen($_REQUEST['online_only']) > 0 )
	{
		$get_inputs .= '<input type="hidden" name="online_only" value="on" />';
	}
	// navigation parameters
	if ( isset($_REQUEST['page']) && (int)$_REQUEST['page'] > 0 && $with_page )
		$get_inputs .= '<input type="hidden" name="page" value="'. (int)$_REQUEST['page'] .'" />';
	if ( isset($_REQUEST['p_per_page']) && (int)$_REQUEST['p_per_page'] > 0 && $with_per_page )
		$get_inputs .= '<input type="hidden" name="p_per_page" value="'. (int)$_REQUEST['p_per_page'] .'" />';
	if ( isset($_REQUEST['sortby']) && strlen($_REQUEST['sortby']) > 0 && $with_sort_by )
		$get_inputs .= '<input type="hidden" name="sortby" value="'. process_pass_data($_REQUEST['sortby']) .'" />';
	if ( isset($_REQUEST['sortorder']) && strlen($_REQUEST['sortorder']) > 0 && $with_sort_order )
		$get_inputs .= ($_REQUEST['sortorder'] == 'ASC' ? '<input type="hidden" name="sortorder" value="ASC" />' : '<input type="hidden" name="sortorder" value="DESC" />');

	// ZIP search parameters
	if ( isset($_REQUEST['zip']) && strlen($_REQUEST['zip']) > 0 )
		$get_inputs .= '<input type="hidden" name="zip" value="'. process_pass_data($_REQUEST['zip']) .'" />';
	if ( isset($_REQUEST['metric']) && strlen($_REQUEST['metric']) > 0 )
		$get_inputs .= ($_REQUEST['metric'] == 'miles' ? '<input type="hidden" name="metric" value="miles" />' : '<input type="hidden" name="metric" value="km" />');
	if ( isset($_REQUEST['distance']) )
		$get_inputs .= '<input type="hidden" name="distance" value="'. (int)$_REQUEST['distance'] .'" />';

	// extra parameters
	if ( isset($_REQUEST['profiles']) && strlen($_REQUEST['profiles']) > 0 )
		$get_inputs .= '<input type="hidden" name="profiles" value="'. process_pass_data($_REQUEST['profiles']) .'" />';
	if ( isset($_REQUEST['status']) && strlen($_REQUEST['status']) > 0 )
		$get_inputs .= '<input type="hidden" name="status" value="'. process_pass_data($_REQUEST['status']) .'" />';

	// admin parameters
	if ( isset($_REQUEST['search']) && strlen($_REQUEST['search']) > 0 )
		$get_inputs .= '<input type="hidden" name="search" value="'. process_pass_data($_REQUEST['search']) .'" />';
	if ( isset($_REQUEST['s_nickname']) && strlen($_REQUEST['s_nickname']) > 0 )
		$get_inputs .= '<input type="hidden" name="s_nickname" value="'. process_pass_data($_REQUEST['s_nickname']) .'" />';

	// affiliate parameters
	if ( isset($affID) && (int)$affID > 0 )
		$get_inputs .= '<input type="hidden" name="affID" value="'. (int)$affID .'" />';

	$respd = db_res("SELECT * FROM `ProfileFields` WHERE `Type` <> 'none' ORDER BY `Type` ASC");
	while ( $arrpd = mysql_fetch_array($respd) )
	{
		//$fname = get_field_name( $arrpd );
		$fname = $arrpd['Name'];
		switch ($arrpd['Type'])
		{
			case 'select_one':
			case 'text':
			case 'area':
				$fval = process_pass_data($_REQUEST[$fname]);
				if ( isset($_REQUEST[$fname]) && strlen($fval) )
				{
					$get_inputs .= "<input type=\"hidden\" name=\"$fname\" value=\"$fval\" />";
				}
				break;

			/*case 'list':
				$fval = $_REQUEST[$fname];
				if ( is_array($fval) && count($fval) > 0 )
				{
					while ( list( $key, $val ) = each( $fval ) )
						$get_inputs .= "<input type=\"hidden\" name=\"{$fname}[]\" value=\"". process_pass_data($val) ."\" />";
				}
				break;*/

			/*case 'check':
				if ($arrpd['type'] == 'r')
				{
					$findok = 0;
					$funcbody = $arrpd['extra'];
					$func = create_function("", $funcbody);
					$ar = $func();

					foreach ( $ar as $key => $value )
					{
						if ( $_REQUEST["{$fname}_{$key}"] == "on" )
						{
							$findok = 1;
							break;
						}
					}

					if ( $findok )
					{
						foreach ( $ar as $key => $value )
						{
							if ( $_REQUEST["{$fname}_{$key}"] == "on" )
								$get_inputs .= "<input type=\"hidden\" name=\"{$fname}_{$key}\" value=\"on\" />";
						}
					}
				}
				elseif ($arrpd['type'] == 'e')
				{
					$findok = 0;

					$vals = preg_split ("/[,\']+/", $arr['extra'], -1, PREG_SPLIT_NO_EMPTY);

					foreach ( $vals as $key )
					{
						if ( $_REQUEST["{$fname}_{$key}"] == "on" )
						{
							$findok = 1;
							break;
						}
					}

					if ( $findok )
					{
						foreach ( $vals as $key )
						{
							if ( $_REQUEST["{$fname}_{$key}"] == "on" )
								$get_inputs .= "<input type=\"hidden\" name=\"{$fname}_{$key}\" value=\"on\" />";
						}
					}
				}
				break;*/

			/*case 'check_set':
				$vals = preg_split ("/[,\']+/", $arrpd['extra'], -1, PREG_SPLIT_NO_EMPTY);
				$offset = 0;

				foreach ( $vals as $v )
				{
					if ( $_REQUEST["{$fname}_{$offset}"] == "on" )
						$get_inputs .= "<input type=\"hidden\" name=\"{$fname}_{$offset}\" value=\"on\" />";
					$offset++;
				}
				break;*/

			case 'date':
				$fval_s = process_pass_data($_REQUEST["{$fname}_start"]);
				$fval_e = process_pass_data($_REQUEST["{$fname}_end"]);

				if ( isset($_REQUEST["{$fname}_start"]) && strlen($fval_s) )
				{
					$get_inputs .= "<input type=\"hidden\" name=\"{$fname}_start\" value=\"$fval_s\" />";
				}
				if ( isset($_REQUEST["{$fname}_end"]) && strlen($fval_e) )
				{
					$get_inputs .= "<input type=\"hidden\" name=\"{$fname}_end\" value=\"$fval_e\" />";
				}
				break;
		}
	}

	return $get_inputs;
}

function ResNavigationRet( $form_name, $short = 0, $function='', $aVar = '' )
{
	global $p_num;
	global $page;
	global $_page;
	global $p_per_page;
	global $page_first_p;
	global $pages_num;
	global $logged;
	global $oTemplConfig;
	global $gallery_view;

	$pages_around = 5;
	if ( !is_array($aVar) )
	{
		$per_page_array = array(10, 15, 20, 30, 50);
	}
	else
	{
		$per_page_array = $aVar;
	}
	
	if ( $logged['admin'] )
		array_push($per_page_array, 100);

	if ( !$function )
	{
	    $get_vars_controls = get_vars_controls(1, 0, 1, 1, 1);
	    $get_vars = get_vars(0, 1, 1, 1, 0);
	}
	else
	{
	    $func = create_function( '', $function );
	    $get_vars = $func();
	}

	$gallery_view = (int)$gallery_view;

	ob_start();

	if ( $p_num )
	{
?>
		<form name="<?= 'NavForm' . $form_name ?>" id="<?= 'NavForm' . $form_name ?>" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" style="margin: 0px;">
			<?= $get_vars_controls ?>
			<table class="text2" cellpadding="0" cellspacing="0" border="0">
<?
		if ( !$short )
		{
?>
				<tr>
					<td><?= _t("_Results") ?>:&nbsp;<b><?= (int)$page_first_p ?></b>-<b><?= min((int)($page_first_p + $p_per_page - 1), (int)$p_num) ?></b>&nbsp;|&nbsp;<?= _t("Total") ?>:&nbsp;<b><?= (int)$p_num ?></b></td>
					<td>&nbsp;|&nbsp;<?= _t("_Results per page") ?>:&nbsp;</td>
					<td>
						<select name="p_per_page" onchange="javascript: document.forms['<?= 'NavForm' . $form_name ?>'].submit();">
<?
			foreach ( $per_page_array as $per_page_elem )
			{
				echo "<option value=\"{$per_page_elem}\" ". ($p_per_page == $per_page_elem ? 'selected="selected"' : '') .">{$per_page_elem}</option>";
			}
?>
						</select>
					</td>
					<td>&nbsp;</td>
				</tr>
<?
		}
?>
				<tr>
					<td align="center" colspan="3">
						<?= _t('_Pages') ?>:&nbsp;
<?
		if ( $page > 1 )
		{
			echo "
						<a href=\"{$_SERVER['PHP_SELF']}{$get_vars}page=1&amp;gallery_view={$gallery_view}\">". _t("_First") ."</a>&nbsp;|&nbsp;
						<a href=\"{$_SERVER['PHP_SELF']}{$get_vars}page=". (int)($page - 1) ."&amp;gallery_view={$gallery_view}\">". _t("_Prev") ."</a>&nbsp;|&nbsp;";
		}

		// print all previous pages
		for ( $i = max($page - $pages_around, 1); $i < $page ; $i++ )
		{
			echo "
						<a href=\"{$_SERVER['PHP_SELF']}{$get_vars}page={$i}&amp;gallery_view={$gallery_view}\">{$i}</a>&nbsp;";
		}
		// print current page
		echo "
						[{$page}]&nbsp;";
		// print all next pages
		for ( $i = $page + 1; $i <= min($page + $pages_around, $pages_num); $i++ )
		{
			echo "
						<a href=\"{$_SERVER['PHP_SELF']}{$get_vars}page={$i}&amp;gallery_view={$gallery_view}\">{$i}</a>&nbsp;";
		}

		if ( $page < $pages_num )
		{
			echo "
						<a href=\"{$_SERVER['PHP_SELF']}{$get_vars}page=". (int)($page + 1) ."&amp;gallery_view={$gallery_view}\">". _t("_Next") ."</a>&nbsp;|&nbsp;
						<a href=\"{$_SERVER['PHP_SELF']}{$get_vars}page={$pages_num}&amp;gallery_view={$gallery_view}\">". _t("_Last") ."</a>";
		}
?>
					</td>
<?
		// print gallery option for search page
		if ( $_page['name_index'] == 32 && $oTemplConfig -> show_gallery_link_in_page_navigation )
		{
			if ( $gallery_view )
			{
				echo "
					<td style=\"padding-left: 10px;\"><a href=\"{$_SERVER['PHP_SELF']}{$get_vars}page={$page}&amp;gallery_view=0\">". _t("_view as profile details") ."</a></td>";
			}
			else
			{
				echo "
					<td style=\"padding-left: 10px;\"><a href=\"{$_SERVER['PHP_SELF']}{$get_vars}page={$page}&amp;gallery_view=1\">". _t("_view as photo gallery") ."</a></td>";
			}
		}
		else
		{
			echo "
					<td>&nbsp;</td>";
		}
?>
				</tr>
			</table>
		</form>
<?
	}

	$ret = ob_get_contents();
	ob_end_clean();

	return $ret;
}

function getID( $str, $with_email = 1 )
{
	if ( $with_email )
	{
		if ( eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,4}$", $str) )
		{
			$str = process_db_input($str);
	    	$mail_arr = db_arr( "SELECT `ID` FROM `Profiles` WHERE `Email` = '$str'" );
			if ( (int)$mail_arr['ID'] )
			{
				return (int)$mail_arr['ID'];
			}
		}
	}

	$ID = (int)$str;
    if ( strcmp("$str", "$ID") == 0 )
		return $ID;
	else
    {
		$str = process_db_input($str);
		$nick_arr = db_arr( "SELECT `ID` FROM `Profiles` WHERE `NickName` = '$str'" );
		if ( (int)$nick_arr['ID'] )
		{
			return (int)$nick_arr['ID'];
		}
	}

	return 0;
}

function check_login( $ID, $passwd, $db = 'Profiles', $error_handle = true )
{
	global $site;
	global $logged;
	global $_page;

	switch( $db )
	{
		case 'Profiles': $member = 0; break;
		case 'Admins': $member = 1; break;
		case 'aff': $member = 2; break;
		case 'moderators': $member = 3; break;
	}

	if ( 0 == strcmp( $db, 'Profiles' ) && !(int)$ID )
	{
		if ( $error_handle )
			login_form( _t("_PROFILE_ERR"), $member );
		return false;
	}

	if ( strcmp( $db, 'Admins' ) == 0 )
	{
		$ID = process_db_input( $ID );
		$cond = "`Name` = '$ID'";
	}
	elseif ( strcmp( $db, 'Profiles' ) == 0 )
	{
		$ID = (int)$ID;
		$cond = "`ID` = $ID";
	}
	elseif ( strcmp( $db, 'moderators') == 0 )
	{
		$ID = process_db_input( $ID );
		$cond = "`name` = '$ID'";
	}
	else
	{
		if ( is_numeric($ID) )
		{
			$ID = (int)$ID;
			$cond = "`ID` = " . (int)$ID;
		}
		elseif ( strstr($ID, "@") )
		{
			$ID = process_db_input( $ID );
			$cond = "`email` = '$ID'";
		}
		else
		{
			$ID = process_db_input( $ID );
			$cond = "`NickName` = '$ID'";
		}
	}

	$query = "SELECT `Password` FROM $db WHERE $cond";
	$pwd_arr = db_arr( $query );

	// If no such members
	if ( !$pwd_arr )
	{
		if ( $error_handle )
			login_form( _t("_PROFILE_ERR"), $member );
		return false;
	}

	// If password is incorrect
	$real_pwd = $pwd_arr['Password'];
	
	if ( strcmp( $real_pwd, $passwd ) != 0 )
	{
		if ( $error_handle )
			login_form( _t("_INVALID_PASSWD"), $member );
		return false;
	}

	// Update last navigation time
	if ( !$member ) {
		$query = "UPDATE $db SET `DateLastNav` = NOW() WHERE `ID` = $ID";
		return db_res( $query );
	}

	return true;
}

function check_logged(){
	global $logged;
	$aAccTypes = array(1 => 'admin', 0 => 'member', 2 => 'aff', 3 => 'moderator');
	foreach ($aAccTypes as $key => $value) {
		if ($logged[$value] = member_auth( $key, false )) break;
	}
}

// 0 - member, 1 - admin
function member_auth ( $member = 0, $error_handle = true, $bAjx = false )
{
   	global $site;
   	global $dir;
   	global $tab;
	global $logged;

   	switch( $member )
   	{
	    case 0:
	   		$mem	    = 'member';
	   		$table	    = 'Profiles';
	   		$login_page = "{$site['url']}member.php";
	    break;
	    case 1:
	   		$mem	    = 'admin';
	   		$table	    = 'Admins';
	   		$login_page = "{$site['url_admin']}index.php";
	    break;
	    case 2:
	        $mem        = 'aff';
	        $table      = 'aff';
	        $login_page = "{$site['url_aff']}index.php";
	    break;
	    //
	    case 3:
	        $mem = 'moderator';
	        $table = 'moderators';
	        $login_page = "{$site['url']}moderators/index.php";
		break;
    }

    if ( !$_COOKIE[ $mem . "ID" ] || !$_COOKIE[ $mem . "Password" ] )
    {

        if ( $error_handle )
        {
           $text = _t("_LOGIN_REQUIRED_AE1");
           if ( !$member )
               $text .= "<br />"._t("_LOGIN_REQUIRED_AE2", $site['images'], $site['url'], $site['title']);
			$bAjxMode = ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ? true : false;
			if ($member=1 && $bAjx==true) $bAjxMode = true;
           login_form( $text, $member, $bAjxMode );
        }
        return false;
    }

    return check_login( $_COOKIE[ $mem . 'ID' ], $_COOKIE[ $mem . 'Password' ], $table, $error_handle );
}

function MemberContacted( $ID )
{
	$ID = (int)$ID;
	if ( !$ID )
	    return;

	$c_arr = db_arr( "SELECT COUNT(*) FROM `BoughtContacts` WHERE `IDBuyer` = $ID" );
	return $c_arr[0];
}

function MemberWasContacted( $ID )
{
	$ID = (int)$ID;
	if ( !$ID )
	    return;

	$c_arr = db_arr( "SELECT COUNT(*) FROM `BoughtContacts` WHERE `IDContact` = $ID" );
	return $c_arr[0];
}


function profile_delete( $ID )
{
	global $MySQL;
	global $dir;
	global $site;
	global $logged;
	
	$ID = (int)$ID;
	
	if ( !$ID )
	    return false;
	
	if ( !getProfileInfo( $ID ) )
	    return false;

	modules_del($ID);

	db_res( "DELETE FROM `VKisses` WHERE `ID` = '{$ID}' OR `Member` = '{$ID}'" );
	db_res( "DELETE FROM `Profiles` WHERE `ID` = '{$ID}'" );
	
	if ( !mysql_affected_rows() )
		return false;
	
    db_res( "DELETE FROM `BlockList` WHERE `ID` = '{$ID}' OR `Profile` = '{$ID}';" );
	db_res( "DELETE FROM `ProfilesTrack` WHERE `Member` = '{$ID}' OR `Profile` = '{$ID}'" );
	db_res( "DELETE FROM `Messages` WHERE Recipient = {$ID} " );
	db_res( "DELETE FROM `Guestbook` WHERE Recipient = {$ID} " );
	db_res( "DELETE FROM `aff_members` WHERE idProfile = {$ID}" );
	db_res( "DELETE FROM `HotList` WHERE ID = {$ID} OR Profile = {$ID}" );
	db_res( "DELETE FROM `FriendList` WHERE ID = {$ID} OR Profile = {$ID}" );
	db_res( "DELETE FROM `BlockList` WHERE ID = {$ID} OR Profile = {$ID}" );
	db_res( "DELETE FROM `BoughtContacts` WHERE `IDBuyer` = {$ID} OR `IDContact` = {$ID}" );
	db_res( "DELETE FROM `ProfileMemLevels` WHERE `IDMember` = {$ID}" );
	db_res( "DELETE FROM `ProfilesComments` WHERE `Sender` = {$ID} OR `Recipient` = {$ID}" );
	db_res( "DELETE FROM `ProfilesPolls` WHERE `id_profile` = {$ID}" );
	db_res( "DELETE FROM `Tags` WHERE `ID` = {$ID} AND `Type` = 'profile'" );
    db_res( "DELETE FROM `GroupsMembers` WHERE `memberID` = {$ID}" );

    // delete profile votings
    require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolVoting.php' ); 
    $oVotingProfile = new BxDolVoting ('profile', 0, 0);
    $oVotingProfile->deleteVotings ($ID);

    // delete profile comments 
    require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolCmts.php' ); 
    $oCmts = new BxDolCmts ('profile', $ID);
    $oCmts->onObjectDelete ();
    // delete all comments in all comments' systems, this user posted
    $oCmts->onAuthorDelete ($ID);

	// Clean gallery
	$albumsRes = db_res( "SELECT `ID` FROM `GalleryAlbums` WHERE `IDMember` = {$ID}" );
	while ( $albumArr = mysql_fetch_assoc($albumsRes) )
	{
		$albumID = $albumArr['ID'];
		$objectsRes = db_res( "SELECT `Filename`, `ThumbFilename` FROM `GalleryObjects` WHERE `IDAlbum` = {$albumID}" );
		while ( $objectArr = mysql_fetch_assoc($objectsRes) )
		{
			@unlink( "{$dir['gallery']}{$objectArr['Filename']}" );
			if ( strlen($objectArr['ThumbFilename']) && file_exists("{$dir['gallery']}{$objectArr['ThumbFilename']}") )
				@unlink( "{$dir['gallery']}{$objectArr['ThumbFilename']}" );
		}
		db_res( "DELETE FROM `GalleryObjects` WHERE `IDAlbum` = {$albumID}" );
	}
	db_res( "DELETE FROM `GalleryAlbums` WHERE `IDMember` = {$ID}" );

	// Clean customizations
	$customArr = db_arr( "SELECT `BackgroundFilename` FROM `ProfilesSettings` WHERE `IDMember` = {$ID}" );
	if ( strlen($customArr['BackgroundFilename']) && file_exists($dir['profileImage'] . $customArr['BackgroundFilename']) && is_file($dir['profileImage'] . $customArr['BackgroundFilename']) )
		unlink( $dir['profileImage'] . $customArr['BackgroundFilename'] );
	db_res( "DELETE FROM `ProfilesSettings` WHERE `IDMember` = {$ID}" );
	
	// delete media
	$rMedia = db_res( "SELECT `med_id`, `med_file`, `med_type` FROM `media` WHERE `med_prof_id` = {$ID}" );

    $oVotingMedia = new BxDolVoting ('media', 0, 0);
	while( $aMedia = mysql_fetch_assoc( $rMedia ) )
	{
		switch( $aMedia['med_type'] )
		{
			case 'photo':
				$medDir = $dir['profileImage'] . $ID . "/";
				@unlink( $medDir . 'icon_' . $aMedia['med_file'] );
				@unlink( $medDir . 'photo_' . $aMedia['med_file'] );
				@unlink( $medDir . 'thumb_' . $aMedia['med_file'] );
			break;
			case 'audio':
				$medDir = $dir['profileSound'] . $ID . "/";
				@unlink( $medDir . $aMedia['med_file'] );
			break;
			case 'video':
				$medDir = $dir['profileVideo'] . $ID . "/";
				@unlink( $medDir . $aMedia['med_file'] );
			break;
		}
	    // delete media voting	        
        $oVotingMedia->deleteVotings ($aMedia['med_id']);
	}
	$aMem = array();
	$aMedia = array('photo','music','video');
	foreach ($aMedia as $sVal) {
		$oMedia = new BxDolSharedMedia($sVal, $site, $dir, $aMem);
		$oMedia->deleteUserGallery($ID, $logged);
	}
	
	db_res( "DELETE FROM `media` WHERE `med_prof_id` = {$ID}" );
	
	@rmdir( $dir['profileImage'] . $ID );
	@rmdir( $dir['profileVideo'] . $ID );
	@rmdir( $dir['profileSound'] . $ID );
	
	
	//Clean blogs
	$aBlog = db_arr("SELECT `ID` FROM `Blogs` WHERE `OwnerID` = {$ID}");
	$iBlogID = $aBlog['ID'];
	if ($iBlogID>0) {
		//Clean blogs
		$oBlogs = new BxDolBlogs(TRUE);
		$oBlogs->bAdminMode = TRUE;
		(int)$_REQUEST['DeleteBlogID'] = $iBlogID;
		$oBlogs->ActionDeleteBlogSQL();
	}

	//delete user classifieds
	$oClassifieds = new BxDolClassifieds();
	$oClassifieds->bAdminMode = TRUE;
	$oClassifieds->DeleteProfileAdvertisement($ID);

	//delete user events
	$oEvents = new BxDolEvents();
	$oEvents->bAdminMode = TRUE;
	$oEvents->DeleteProfileEvents($ID);

	//delete cache file
	$sCacheFile = $dir['cache'] . 'user' . $ID . '.php';
	@unlink( $sCacheFile );
	reparseObjTags( 'profile', $ID );
}

function profile_send_message( $ID, $message )
{
	global $site;

	if ( !(int)$ID )
	    return false;

	if ( !strlen( $message ) )
	    return false;

	$p_arr = getProfileInfo( $ID ); //db_arr( "SELECT `ID`, `Email` FROM `Profiles` WHERE `ID` = '$ID'" );

	if ( !$p_arr )
	    return false;

	$text = getParam("t_AdminEmail");
	$subject = getParam("t_AdminEmail_subject");

	$recipient = $p_arr['Email'];

	$aPlus = array();
	$aPlus['MessageText'] = "\r\n$message\r\n";

	$mail_ret = sendMail( $recipient, $subject, $text, $p_arr['ID'], $aPlus );

	if ( !$mail_ret )
	    return false;
	return true;
}

function get_user_online_status ($ID)
{
	$ID = (int)$ID;
	$min = getParam( "member_online_time" );
	$onl_arr = db_arr("SELECT count(ID) as count_id FROM Profiles WHERE DateLastNav > SUBDATE(NOW(), INTERVAL $min MINUTE) AND ID=$ID");
	return (int)( $onl_arr[count_id] == 1 );
}


/**
  * who
  *   0 - all
  *   1 - man
  *   2 - woman
  **/
function get_users_online_number ( $who = 0, $country = "")
{
	global $dir;

	if ( !$who ) $who = '';

	$min = getParam( "member_online_time" );
	switch ( $who )
	{
		// chatting
		case 't':
		case 'T':
			$onl_arr = db_arr("SELECT  count(DISTINCTROW IDFrom) AS count_onl FROM IMessages  WHERE `When` > SUBDATE(NOW(), INTERVAL $min MINUTE)");
			break;
		// Members with non standart membership
		case 'g':
		case 'G':
			$onl_arr = db_arr( "SELECT	COUNT(DISTINCT IDMember) AS count_onl
								FROM	ProfileMemLevels
								INNER JOIN Profiles ON Profiles.ID = ProfileMemLevels.IDMember
								WHERE
									(DateExpires IS NULL OR DateExpires > NOW()) AND
									(DateStarts IS NULL OR DateStarts <= NOW()) AND
									(Profiles.Status = 'Active') AND
									(DateLastNav > SUBDATE(NOW(), INTERVAL $min MINUTE))" );
			break;
		// from country
		case 'c':
		case 'C':
			$country = process_db_input($country);
			$onl_arr = db_arr("SELECT count(ID) as count_onl FROM Profiles WHERE Status='Active' AND `Country` = '$country' AND DateLastNav > SUBDATE(NOW(), INTERVAL $min MINUTE)");
			break;
		// men
		case 'm':
		case 'M':
			$onl_arr = db_arr("SELECT count(ID) as count_onl FROM Profiles WHERE Status='Active' AND Sex='male' AND DateLastNave > SUBDATE(NOW(), INTERVAL $min MINUTE)");
			break;
		// momen
		case 'w':
		case 'W':
			$onl_arr = db_arr("SELECT count(ID) as count_onl FROM Profiles WHERE Status='Active' AND Sex='female' AND DateLastNave > SUBDATE(NOW(), INTERVAL $min MINUTE)");
			break;
		// all
		default:
			$onl_arr = db_arr("SELECT count(ID) as count_onl FROM Profiles WHERE Status='Active' AND DateLastNave > SUBDATE(NOW(), INTERVAL $min MINUTE)");
			break;
	}
	return (int)$onl_arr['count_onl'];
}

function get_users_online_res ( $lim = 0 )
{
	if ( !$lim )
		$lim = getParam( "default_online_users_num" );
	$min = getParam( "member_online_time" );

	return db_res("SELECT ID, NickName FROM Profiles WHERE Status='Active' AND DateLastNave > SUBDATE(NOW(), INTERVAL $min MINUTE) ORDER BY DateLastNave DESC LIMIT $lim");
}

function AddNotifyEmail ( $email,  $Name = "", $EmailFlag = "NotifyMe", $EmailText = "HTML" )
{
	$email = process_db_input(trim($email));
	$Name = process_db_input($Name);

	if ( !strlen($email) || !strstr($email,"@") || !strstr($email,".") )
		return 0;

	$arr = db_arr("SELECT ID FROM Profiles WHERE Email LIKE '$email' LIMIT 1");
	if ( $arr['ID'] )
		return 0;

	$arr = db_arr("SELECT ID FROM NotifyEmails WHERE Email LIKE '$email' LIMIT 1");
	if ( $arr['ID'] )
		return 0;

	return db_res("INSERT INTO NotifyEmails SET Email = '$email', Name = '$Name', EmailFlag = '$EmailFlag', EmailText = '$EmailText'");
}

function Replace_Vars( $path, $target, $value )
{

    $content = array();

    $f = fopen ( $path, "r" );

    while (!feof ($f)) { $templ .= fgets($f, 4096); }

    fclose ( $f );

    $content = explode("\n", $templ);

//------ REPLACEMENT ================================================
    for ($i = 0; $i < count($content); $i++ )
    {
    	$t = $content[$i];
        if ( preg_match("/^\\".$target."\s/", $t) )
        {
            $content[$i] = $target . $value;
        }
    }

//------ RECORD =====================================================
    $f = fopen ( $path, "w" );
    for ($i = 0; $i < count($content)-1; $i++ )
    {
        fwrite($f,"$content[$i]"."\n");
    }

    fwrite($f,"$content[$i]");

    fclose ( $f );

    return true;
}

?>
