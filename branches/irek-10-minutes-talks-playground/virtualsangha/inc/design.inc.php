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
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'banners.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'membership_levels.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'params.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'languages.inc.php');
require_once( BX_DIRECTORY_PATH_INC . 'menu.inc.php');
require_once( BX_DIRECTORY_PATH_INC . 'sharing.inc.php');
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxRSS.php');

require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/functions.php" );
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplMenu.php" );





$db_color_index = 0;

$_page['js'] = 1;

/**
 * Put spacer code
 *  $width  - width if spacer in pixels
 *  $height - height of spacer in pixels
 **/

function spacer( $width, $height )
{
	global $site;

    return '<img src="' . $site['images'] . 'spacer.gif" width="' . $width . '" height="' . $height . '" alt="" />';
}

/**
 * Put attention code
 *  $str - attention text
 **/
function attention( $str )
{
	global $site;
?>
<table cellspacing="2" cellpadding="1">
	<tr>
		<td valign="top">
			<img src="<?= $site['icons'] ?>sign.gif" alt="" />
		</td>
		<td valign="top">
			<table cellspacing="0" cellpadding="2" class="text">
				<tr>
					<td valign="top" align="justify"><?= $str ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?
}

/**
 * Put design progress bar code
 *  $text     - progress bar text
 *  $width    - width of progress bar in pixels
 *  $max_pos  - maximal position of progress bar
 *  $curr_pos - current position of progress bar
 **/
function DesignProgressPos ( $text, $width, $max_pos, $curr_pos, $progress_num = '1' )
{
	if( $max_pos )
		$percent = $curr_pos * 100 / $max_pos;
	else
		$percent = 0;
	
	return DesignProgress( $text, $width, $percent, $progress_num );
}

/**
 * Put design progress bar code
 *  $text     - progress bar text
 *  $width    - width of progress bar in pixels
 *  $percent  - current position of progress bar in percents
 **/
function DesignProgress ( $text, $width, $percent, $progress_num, $id = ''  )
{
	global $site;
	
	$ret = "";
	$ret .= '<div class="rate_block" style="width:' . $width . 'px;">';
		$ret .= '<div class="rate_text"' . ( $id ? " id=\"{$id}_text\"" : '' ) . '>';
			$ret .= $text;
		$ret .= '</div>';
		$ret .= '<div class="rate_scale"' . ( $id ? " id=\"{$id}_scale\"" : '' ) . '>';
			$ret .= '<div' . ( $id ? " id=\"{$id}_bar\"" : '' ) . ' style="position:relative; height:10px; font-size:1px; width:' . round($percent) . '%; background-image:url(' . getTemplateIcon("scale_index_{$progress_num}.gif") . '); background-repeat:repeat-x;"></div>';
		$ret .= '</div>';
	$ret .= '</div>';

	return $ret;
}


// design box
$fs = filesize ( "{$dir['root']}templates/tmpl_{$tmpl}/designbox_0.html" );
$f = fopen ( "{$dir['root']}templates/tmpl_{$tmpl}/designbox_0.html", "r" );
$templ_designbox0 = fread ( $f, $fs );
fclose ( $f );

$fs = filesize ( "{$dir['root']}templates/tmpl_{$tmpl}/designbox_1.html" );
$f = fopen ( "{$dir['root']}templates/tmpl_{$tmpl}/designbox_1.html", "r" );
$templ_designbox1 = fread ( $f, $fs );
fclose ( $f );

$fs = filesize ( "{$dir['root']}templates/tmpl_{$tmpl}/designbox_2.html" );
$f = fopen ( "{$dir['root']}templates/tmpl_{$tmpl}/designbox_2.html", "r" );
$templ_designbox2 = fread ( $f, $fs );
fclose ( $f );


/**
 * Put "design box" HTML code
 *  $title        - title text
 *  $width        - width in pixels
 *  $height       - height in pixels
 *  $align        - align or other <td> formatter
 *			  	    for example: "align=center"
 * $db_num        - number of design box template (for "act" skin only)
 **/
function DesignBoxContent ( $title, $content, $db_num = 0, $caption_item = '' )
{
	global $site;
	global $templ_designbox0;
	global $templ_designbox1;
	global $templ_designbox2;
    global $dbh_letters;
    global $tmpl;

    switch ($dbh_letters)
    {
        case "upper": $f = "strtoupper"; break;
        case "lower": $f = "strtolower"; break;
        case "fupper": $f = "ucfirst"; break;
        case "aupper": $f = "ucwords"; break;
        default: $f = "sprintf";
    }


	if ( strlen($height) ) $height = " height=\"$height\" ";

    if ($db_num == 2) // && !$templ_designbox2 )
    {
    	$templ = $templ_designbox2;
    }
    elseif( $db_num == 1) // && !$templ_designbox1 )
    {
	    $templ = $templ_designbox1;
    }
    else//if( !$templ_designbox0 )
    {
    	$templ = $templ_designbox0;
    }

    // replace path to the images
	$s = $site['images'];
    $s = str_replace ( $site['url'], "", $site['images'] );
	$templ = str_replace ( $s, $site['images'], $templ );

    // replace template variables
	$templ = str_replace ( "__title__", $f($title), $templ );
	$templ = str_replace ( "__caption_item__", $caption_item, $templ );
	$templ = str_replace ( "__designbox_content__", $content, $templ );
    $templ = str_replace ( "__images__", $site['images'], $templ );
	if ( function_exists( 'colors_select' ) )
		$templ = str_replace ( "__designBoxColor__", colors_select(), $templ );

    if ($tmpl == 'act')
    {
    	if ($index_db_color_randomize == 1)
        {
            $templ = str_replace ( "__db_color__", get_active_color(), $templ );
        }
        else
        {
             $templ = str_replace ( "__db_color__", $index_db_color, $templ );
        }
    }

	return  $templ;
}


/**
 * Put "design box" with border HTML code
 *  $title        - title text
 *  $width        - width in pixels
 *  $height       - height in pixels
 *  $align        - align or other <td> formatter
 *                  for example: "align=center"
 **/
function DesignBoxContentBorder ( $title, $content, $caption_item='' )
{
    global $site;
    global $dir;
    global $tmpl;
    global $service_db_color;

    global $dbh_letters;

    switch ($dbh_letters)
    {
        case "upper": $f_case = "strtoupper"; break;
        case "lower": $f_case = "strtolower"; break;
        case "fupper": $f_case = "ucfirst"; break;
        case "aupper": $f_case = "ucwords"; break;
        default: $f_case = "sprintf";
    }

	// design box with border: reading template
	$fs = filesize ( "{$dir['root']}templates/tmpl_{$tmpl}/designbox_border.html" );
	$f = fopen ( "{$dir['root']}templates/tmpl_{$tmpl}/designbox_border.html", "r" );
	$templ_designbox_border = fread ( $f, $fs );
	fclose ( $f );


    $templ = $templ_designbox_border;

    // replace template variables
    $templ = str_replace ( "__title__", $f_case($title), $templ );
    $templ = str_replace ( "__caption_item__", $caption_item, $templ );
    $templ = str_replace ( "__designbox_content__", $content, $templ );

    return  $templ;
}


/**
 * members statistic block
 */
function PageCompMemberStatN( $w, $h)
{
        global $site;
        global $prof;
        global $tmpl;


        $free_mode = getParam("free_mode") == "on" ? 1 : 0;

// members statistics

	$total_c = strlen( $_POST['total_c'] ) ? $_POST['total_c'] : getParam( "default_country" );
	$total_c2 = strlen( $_POST['total_c2'] ) ? $_POST['total_c2'] : getParam( "default_country" );
	$total_arr = db_arr( "SELECT COUNT(ID) FROM `Profiles` WHERE Status = 'Active'" );
	$total_arr_week = db_arr( "SELECT COUNT(ID) FROM `Profiles` WHERE Status = 'Active' AND (TO_DAYS(NOW()) - TO_DAYS(LastReg)) <= 7" );
	$total_arr_gold = db_arr( "SELECT	COUNT(DISTINCT IDMember)
									FROM	ProfileMemLevels
									INNER JOIN Profiles ON Profiles.ID = ProfileMemLevels.IDMember
									WHERE
										(DateExpires IS NULL OR DateExpires > NOW()) AND
										(DateStarts IS NULL OR DateStarts <= NOW()) AND
										(Profiles.Status = 'Active')" );
        $total_c_arr = db_arr( "SELECT COUNT(ID) FROM `Profiles` WHERE Status = 'Active' AND `Country` = '". process_db_input($total_c) ."'" );
        $total_members = $total_arr[0];
        $total_c_members = $total_c_arr[0];

        $total_c_members_onl = get_users_online_number('c', $total_c2 );
        $total_arr_chatting = get_users_online_number('t');
        $members_online = get_users_online_number();

        $c_arr = $prof[countries];

        $out = "";
		$out .= '<table cellpadding="0" cellspacing="0" border="0" align="center" width="150"><tr><td>';
    $out .= "<table cellpadding=1 cellspacing=1 class=text border=0 align=center>";
    $out .= "<tr><td width=17><img src=$site[images]group1.gif></td><td width=100%><b>&nbsp;"._t("_Currently Online")."</b></td></tr>\n";
    $out .= "<tr><td height=5 class=memb_stat colspan=2><img src=$site[images]spacer.gif width=6 height=1></td></tr>\n";
        $out .= "<tr><td width=17 align='right'><img src='$site[images]us2.gif'></td><td width=100% align=\"left\">&nbsp;"._t("_Total")." -  $members_online</td></tr>\n";
    $out .= "<tr><td width=17 align='right'><img src='$site[images]us3.gif'></td><td width=100% align=\"left\">&nbsp;"._t("_Chatting")." - $total_arr_chatting </td></tr>\n";
    $out .= "<tr><td width=17 align='right'><img src='$site[images]us4.gif'></td><td width=100% align=\"left\">&nbsp;$total_c_members_onl "._t("_members")." "._t("_from").":</td></tr>\n";
    $out .= '<form action="'.$_SERVER[PHP_SELF].'" method="post" name="cForm2"><tr><td align=right colspan=2>';
    $out .= "<input type=hidden name=total_c value=$total_c>";
    $out .= '<select class=index name=total_c2 onChange="javascript: document.forms[\'cForm2\'].submit();">';
    foreach ( $c_arr as $key => $value )
    {
            $out .= "<option value=$key";
            if ( $key == $total_c2 )
                $out .= " selected";
            $out .= '>'._t( '__'.$value ).'</option>';
    }
    $out .= '</select>';
    $out .= '</td></form></tr>';
    $out .= "<tr><td height=1 colspan=2><img src=$site[images]spacer.gif width=1 height=1></td></tr>\n";
    $out .= "</table>\n";


    $out .= "<br />";

    $out .= "<table cellpadding=1 cellspacing=1 class=text>";
    $out .= "<tr><td height=2><img src=$site[images]spacer.gif height=2></td></tr>\n";
    $out .= "<tr><td width=17><img src=$site[images]group2.gif></td><td width=100%><b>&nbsp;"._t("_Total Registered")."</b></td></tr>\n";
    $out .= "<tr><td height=1 class=memb_stat colspan=2><img src=$site[images]spacer.gif width=1 height=1></td></tr>\n";
    $out .= "<tr><td width=17 align='right'><img src=$site[images]us6.gif></td><td width=100% align=\"left\">&nbsp;"._t("_Total")." - $total_arr[0]</td></tr>\n";

        if ( !$free_mode )
    $out .= "<tr><td width=17 align='right'><img src=$site[images]us4.gif></td><td width=100% align=\"left\">&nbsp;"._t("_Gold Members")." - $total_arr_gold[0] </td></tr>\n";

    $out .= "<tr><td width=17 align='right'><img src=$site[images]us7.gif></td><td width=100% align=\"left\">&nbsp;"._t("_New this week")." - $total_arr_week[0] </td></tr>\n";
    $out .= "<tr><td width=17 align='right'><img src=$site[images]us1.gif></td><td width=100% align=\"left\">&nbsp;$total_c_members "._t("_members")." "._t("_from").":</td></tr>\n";
    if ($tmpl == 'g4') $out .= "<tr><td><img src=\"" . $site[images] . "spacer.gif\" height=\"5\"></td></tr>";
    $out .= '<form action="'.$_SERVER[PHP_SELF].'" method="post" name="cForm"><tr><td align=right colspan=2>';
    $out .= "<input type=hidden name=total_c2 value=$total_c2>";
    $out .= '<select class=index name=total_c onChange="javascript: document.forms[\'cForm\'].submit();">';
    foreach ( $c_arr as $key => $value )
    {
            $out .= "<option value=$key";
            if ( $key == $total_c )
                $out .= " selected";
                        $out .= '>'._t( '__'.$value ).'</option>';
    }
    $out .= '</select>';
    $out .= '</td></form></tr>';
    $out .= "<tr><td height=1 colspan=2><img src=$site[images]spacer.gif width=1 height=1></td></tr>\n";
    $out .= '</table>';
    $out .= '</td></tr></table>';

    return DesignBoxContent ( _t("_members"), $w, $out, $h );


}

/**
 * Put top code for the page
 **/
function PageCode( $admintmpl=0 )
{
	global $dir;
    global $site;
	global $_page;
	global $_page_comp;
	global $logged;
	global $langHTMLCharset;
	global $tmpl;
	global $ADMIN;
	global $tmi_letters;
	global $dbh_letters;
	global $max_thumb_height;
	global $max_thumb_width;
	global $_page_cont;

	$ni = $_page['name_index'];
	global $oTemplConfig;
	$oTemplMenu = new BxTemplMenu( $oTemplConfig );

	$free_mode = getParam("free_mode") == "on" ? 1 : 0;

	// reading templates
    if(!$admintmpl)
	{
	    $fn = "{$dir['root']}templates/tmpl_{$tmpl}/page_{$ni}.html";
	    if ( !file_exists($fn) )
			$fn = "{$dir['root']}templates/tmpl_{$tmpl}/default.html";
	}
	else
		$fn = "{$dir['root']}admin/tmpl_admin.html";

	$templ = file_get_contents( $fn );

	// process includes (multi-level)
	do
	{
		$templ1 = $templ;
		$templ = preg_replace_callback( "/__include (.*)__/", "TmplInclude", $templ1 );
		$templ = preg_replace_callback ("/__includebase (.*)__/", "TmplIncludeBase", $templ);
	} while( $templ1 != $templ );
	
	
	//insert to your template page key:    __t: lang_key__     and you will get translated string
	$templ = preg_replace_callback(
		"/__t: (_.+)__/", 
		create_function(
			'$matches',
			'return _t($matches[1]);'
			),
		$templ );

	PageStaticComponents();

	// lang block
	if ( (int)getParam('lang_enable')  )
	{
		ob_start();
		lang_select_txt();
		$_page_cont[0]['switch_lang_block'] = ob_get_clean();
	}
	else
		$_page_cont[0]['switch_lang_block'] = '';
	
	// charset
	$_page_cont[0]['page_charset'] = $langHTMLCharset;


	//change templates
	if ( 'on' == getParam("enable_template"))
		$_page_cont[0]['switch_skin_block'] = templates_select_txt();
	else
		$_page_cont[0]['switch_skin_block'] = '';

	//Path to css
	if( strlen( $_page['css_name'] ) > 0 )
	{
		$filename = $dir['root'] . $site['css_dir'] . $_page['css_name'];
		if ( file_exists( $filename ) && is_file( $filename ) )
			$_page_cont[0]['styles'] = '<link href="' . $site['css_dir'] . $_page['css_name'] . '" rel="stylesheet" type="text/css" />';
		else
			$_page_cont[0]['styles'] = '';
	}
	else
		$_page_cont[0]['styles'] = '';
	
	//Path to js
	if( strlen( $_page['js_name'] ) > 0 )
	{
		$filename = $dir['root'] . 'inc/js/' . $_page['js_name'];
		if ( file_exists( $filename ) && is_file( $filename ) )
		{
			$langDelete = _t('_delete');
			$langLoading = _t('_loading ...');
			$langDeleteMessage = _t('_poll successfully deleted');
			$langMakeIt = _t('_make it');
			$lang_you_should_specify_member = _t('_You should specify at least one member');
			
			if ( $site['js_init'] )
				$_page_cont[0]['java_script'] = $site['js_init'];
			
			$_page_cont[0]['java_script'] .= <<<EOJ
<script type="text/javascript" language="javascript">
	var site_url = '{$site['url']}';
	var lang_delete = '{$langDelete}';
	var lang_loading = '{$langLoading}';
	var lang_delete_message = '{$langDeleteMessage}';
	var lang_make_it = '{$langMakeIt}';
	var lang_you_should_specify_member = '{$lang_you_should_specify_member}';
	
	var iQSearchWindowWidth  = {$oTemplConfig->iQSearchWindowWidth};
	var iQSearchWindowHeight = {$oTemplConfig->iQSearchWindowHeight};
</script>
<script src="{$site['url']}inc/js/{$_page['js_name']}" type="text/javascript" language="javascript"></script>
EOJ;
		}
		else
			$_page_cont[0]['java_script'] = '';
	}
	else
		$_page_cont[0]['java_script'] = '';


	$_page_cont[0]['css_dir'] = $site['css_dir'];
	$_page_cont[0]['plugins'] = $site['plugins'];

	$_page_cont[0]['thumb_width']  = $max_thumb_width;
	$_page_cont[0]['thumb_height'] = $max_thumb_height;

	$_page_cont[0]['site_url']     = $site['url'];
	$_page_cont[0]['images']       = $site['images'];
	$_page_cont[0]['icons']        = $site['icons'];
	$_page_cont[0]['zodiac']       = $site['zodiac'];

	$_page_cont[0]['bottom_text']  = _t( "_bottom_text", date("Y") );
	$_page_cont[0]['copyright']    = _t( "_copyright", date("Y") ) . getVersionComment(); // please do not delete version for debug possibilities
	$_page_cont[0]['powered']      = getParam( 'enable_boonex_footers' ) ? _t( "_powered_by_Dolphin" ) : '';

	$_page_cont[0]['main_logo']    = getMainLogo();

	//place meta data on site pages
	$_page_cont[0]['meta_keywords']    = process_line_output( getParam("MetaKeyWords") );
	$_page_cont[0]['meta_description'] = process_line_output( getParam("MetaDescription") );

	if( strlen( $_page['extra_js'] ) )
		$_page_cont[0]['extra_js'] = $_page['extra_js'];
	else
		$_page_cont[0]['extra_js'] = '';

	if( strlen( $_page['extra_css'] ) )
		$_page_cont[0]['extra_css'] = $_page['extra_css'];
	else
		$_page_cont[0]['extra_css'] = '';
	
	
	// top menu items
	$_page_cont[0]['top_menu']     = $oTemplMenu -> getTopMenu();
	$_page_cont[0]['hidden_menu']  = getAllMenus();
	$_page_cont[0]['custom_menu']  = $oTemplMenu -> getCustomMenu();


	// topest menu items
	{
        $_page_cont[0]['TOP_Home']			= '<a class="menu_item_link" href="' . $site['url'] . 'index.php">' . _t("_Home") . '</a>';
        $_page_cont[0]['BMI_Home']			= '<a class="bottommenu"     href="' . $site['url'] . 'index.php">' . _t("_Home") . '</a>';
		
        $_page_cont[0]['TOP_About']			= '<a class="menu_item_link" href="' . $site['url'] . 'about_us.php">' . _t("_About Us") . '</a>';
        $_page_cont[0]['BMI_About']			= '<a class="bottommenu"     href="' . $site['url'] . 'about_us.php">' . _t("_About Us") . '</a>';
		
        $_page_cont[0]['TOP_Privacy']		= '<a class="menu_item_link" href="' . $site['url'] . 'privacy.php">' . _t("_Privacy") . '</a>';
        $_page_cont[0]['BMI_Privacy']		= '<a class="bottommenu"     href="' . $site['url'] . 'privacy.php">' . _t("_Privacy") . '</a>';
		
        $_page_cont[0]['TOP_Termsofuse']	= '<a class="menu_item_link" href="' . $site['url'] . 'terms_of_use.php">' . _t("_Terms_of_use") . '</a>';
        $_page_cont[0]['BMI_Termsofuse']	= '<a class="bottommenu"     href="' . $site['url'] . 'terms_of_use.php">' . _t("_Terms_of_use") . '</a>';
		
        $_page_cont[0]['TOP_Services']		= '<a class="menu_item_link" href="' . $site['url'] . 'services.php">' . _t("_Services") . '</a>';
        $_page_cont[0]['BMI_Services']		= '<a class="bottommenu"     href="' . $site['url'] . 'services.php">' . _t("_Services") . '</a>';
		
        $_page_cont[0]['TOP_FAQ']			= '<a class="menu_item_link" href="' . $site['url'] . 'faq.php">' . _t("_FAQ") . '</a>';
        $_page_cont[0]['BMI_FAQ']			= '<a class="bottommenu"     href="' . $site['url'] . 'faq.php">' . _t("_FAQ") . '</a>';
		
        $_page_cont[0]['TOP_Articles']		= '<a class="menu_item_link" href="' . $site['url'] . 'articles.php">' . _t("_Articles") . '</a>';
        $_page_cont[0]['BMI_Articles']		= '<a class="bottommenu"     href="' . $site['url'] . 'articles.php">' . _t("_Articles") . '</a>';
		
        $_page_cont[0]['TOP_Stories']		= '<a class="menu_item_link" href="' . $site['url'] . 'stories.php">' . _t("_Stories2") . '</a>';
        $_page_cont[0]['BMI_Stories']		= '<a class="bottommenu"     href="' . $site['url'] . 'stories.php">' . _t("_Stories2") . '</a>';
		
        $_page_cont[0]['TOP_Links']			= '<a class="menu_item_link" href="' . $site['url'] . 'links.php">' . _t("_Links") . '</a>';
        $_page_cont[0]['BMI_Links']			= '<a class="bottommenu"     href="' . $site['url'] . 'links.php">' . _t("_Links") . '</a>';
		
        $_page_cont[0]['TOP_News']			= '<a class="menu_item_link" href="' . $site['url'] . 'news.php">' . _t("_News") . '</a>';
        $_page_cont[0]['BMI_News']			= '<a class="bottommenu"     href="' . $site['url'] . 'news.php">' . _t("_News") . '</a>';
		
        $_page_cont[0]['TOP_Aff']			= getParam("enable_aff") == 'on' ? '<a class="menu_item_link" href="' . $site['url'] . 'affiliates.php">' . _t("_Affiliates") . '</a>' : '';
        $_page_cont[0]['BMI_Aff']			= getParam("enable_aff") == 'on' ? '<a class="bottommenu"     href="' . $site['url'] . 'affiliates.php">' . _t("_Affiliates") . '</a>' : '';
		
		$_page_cont[0]['TOP_Invitefriend']	= '<a class="menu_item_link" href="javascript:void(0);" onclick="return launchTellFriend();">' . _t("_Invite a friend") . '</a>';
		$_page_cont[0]['BMI_Invitefriend']	= '<a class="bottommenu"     href="javascript:void(0);" onclick="return launchTellFriend();">' . _t("_Invite a friend") . '</a>';
		
        $_page_cont[0]['TOP_Contacts']		= '<a class="menu_item_link" href="' . $site['url'] . 'contact.php">' . _t("_Contacts") . '</a>';
        $_page_cont[0]['BMI_Contacts']		= '<a class="bottommenu"     href="' . $site['url'] . 'contact.php">' . _t("_Contacts") . '</a>';
		
        $_page_cont[0]['TOP_Browse']		= '<a class="menu_item_link" href="' . $site['url'] . 'browse.php">' . _t("_Browse Profiles") . '</a>';
        $_page_cont[0]['BMI_Browse']		= '<a class="bottommenu"     href="' . $site['url'] . 'browse.php">' . _t("_Browse Profiles") . '</a>';
		
        $_page_cont[0]['TOP_Feedback']		= '<a class="menu_item_link" href="' . $site['url'] . 'story.php">' . _t("_Add story") . '</a>';
        $_page_cont[0]['BMI_Feedback']		= '<a class="bottommenu"     href="' . $site['url'] . 'story.php">' . _t("_Add story") . '</a>';
		
        $_page_cont[0]['TOP_ContactUs']		= '<a class="menu_item_link" href="' . $site['url'] . 'contact.php">' . _t("_contact_us") . '</a>';
        $_page_cont[0]['BMI_ContactUs']		= '<a class="bottommenu"     href="' . $site['url'] . 'contact.php">' . _t("_contact_us") . '</a>';
		
        $_page_cont[0]['TOP_Bookmark']		= '<a class="menu_item_link" href="javascript:void(0);" onclick="addBookmark();">' . _t("_Bookmark") . '</a>';
        $_page_cont[0]['BMI_Bookmark']		= '<a class="bottommenu"     href="javascript:void(0);" onclick="addBookmark();">' . _t("_Bookmark") . '</a>';
	}

	// bottom menu items
	{
	}

	$_page_cont[0]['hello_member'] = HelloMemberSection();
	
	// member/visitor menu
	if ( $logged['admin'] )
		$_page_cont[0]['menu_right'] = $oTemplMenu -> loggedAdminMenu();
	elseif ( $logged['aff'] )
		$_page_cont[0]['menu_right'] = $oTemplMenu -> loggedAffMenu();
	elseif ( $logged['moderator'] )
		$_page_cont[0]['menu_right'] = $oTemplMenu -> loggedModeratorMenu();
	elseif ( $logged['member'] )
		$_page_cont[0]['menu_right'] = $oTemplMenu -> loggedMemberMenu();
	else
		$_page_cont[0]['menu_right'] = $oTemplMenu -> visitorMenu();
	
	/*if ( !strlen($_page_cont[$ni]['actions_menu']) )
		$_page_cont[0]['actions_menu'] = $oTemplMenu -> actionsMenu();*/
	
	if ( !strlen($_page_cont[$ni]['add_to_header']) )
		$_page_cont[0]['add_to_header'] = '';

	$check_res = checkAction( (int)$_COOKIE['memberID'], ACTION_ID_USE_IM );
	$enable_im = getParam("enable_im");


	if ( $enable_im && $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED && !$ADMIN )
	{
		$_page_cont[0]['IM_title'] = _t("_IM title");
		$_page_cont[0]['IM'] = RetIM();
	}
	else
	{
		$_page_cont[0]['IM_title'] = "";
        $_page_cont[0]['IM'] = "";
	}

	$enable_shoutBox = ('on' == getParam("enable_shoutBox")) ? 1 : 0;
	if( $enable_shoutBox )
		$_page_cont[0]['shout_box'] = loadShoutbox();
	else
		$_page_cont[0]['shout_box'] = '';
	
	$_page_cont[0]['top_page_head']  = getTopPageHead();
	
	// page header
	$_page_cont[0]['page_header']		= $_page['header'];
	$_page_cont[0]['page_header_text']	= $_page['header_text'];

	    // banner rotation/shifting system
        if ( strstr($templ, "__banner_top__") )
                $_page_cont[0]['banner_top'] = banner_put_nv(1);

        if ( strstr($templ, "__banner_left__") )
                $_page_cont[0]['banner_left'] = banner_put_nv(2);

        if ( strstr($templ, "__banner_right__") )
                $_page_cont[0]['banner_right'] = banner_put_nv(3);

        if ( strstr($templ, "__banner_bottom__") )
                $_page_cont[0]['banner_bottom'] = banner_put_nv(4);


        //end of banner rotation/shifting system


	//--- Ray IM Integration ---//
	global $sRayHomeDir;

	$_page_cont[0]['ray_invite_js'] = "
		<script type=\"text/javascript\" language=\"javascript\">
			var sRayUrl = '" . $site['url'] . $sRayHomeDir . "';
		</script>
		<script src=\"" . $site['url'] . "ray/modules/global/js/integration.js\" type=\"text/javascript\" language=\"javascript\"></script>";
	$_page_cont[0]['ray_invite_swf'] = "";

	$iId = (int)$_COOKIE['memberID'];
	$sPassword = getPassword($iId);
	$bEnableRay = (getParam( 'enable_ray' ) == 'on');
	$check_res = checkAction($iId, ACTION_ID_USE_RAY_IM);
	if($bEnableRay && $check_res[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)
		$_page_cont[0]['ray_invite_swf'] = getApplicationContent("im", "invite", array('id' => $iId, 'password' => $sPassword), true);
	//--- Ray IM Integration ---//

	foreach ( $_page_cont[0] as $key => $value )
	{
	    $templ = str_replace ( "__${key}__", $value, $templ );
	}

	if ( is_array($_page_cont[$ni]) )
	{
	    foreach ( $_page_cont[$ni] as $key => $value )
	    {
			$templ = str_replace ( "__${key}__", $value, $templ );
	    }
	}
	
	header('Content-type: text/html; charset=utf-8');
    echo $templ;
}


/**
 * callback function for including template files
 */
function TmplInclude($m)
{
	global $dir;
	global $tmpl;

	// read include file

	$fn = "{$dir['root']}templates/tmpl_$tmpl/{$m[1]}";
	if (file_exists ($fn))
	{
		$fp = fopen ($fn, "r");
		if ($fp)
		{
			$s = fread ($fp, filesize ($fn));
			fclose ($fp);
			return $s;
		}
	}

	return "<b>error reading {$m[1]}</b>";
}

function TmplIncludeBase($m)
{
	global $dir;
	global $tmpl;

	// read include file


	$fn = "{$dir['root']}templates/base/{$m[1]}";
	if (file_exists ($fn))
	{
		$fp = fopen ($fn, "r");
		if ($fp)
		{
			$s = fread ($fp, filesize ($fn));
			fclose ($fp);
			return $s;
		}
	}

	return "<b>error reading {$m[1]}</b>";
}


/**
 * Affiliate's member authentocation and setting up cookies
 **/
function SetCookieFromAffiliate()
{
	global $en_aff;

    if ($en_aff && $_GET['idAff'])
    {
		if ( !strstr($_GET['idAff'],"@") )
		{
			$_GET['idAff'] = (int)$_GET['idAff'];
			$res = db_res("SELECT ID FROM aff WHERE ID={$_GET['idAff']} AND `Status`='active'");
		}
		else
			$res = db_res("SELECT ID FROM aff WHERE email='{$_GET['idAff']}' AND `Status`='active'");

		if ( mysql_num_rows($res) )
		{
			setcookie( "idAff", $_GET['idAff'], time() + 10000 * 3600, "/" );
		}
    }
}

/**
 * Friend's member authentocation and setting up cookies
 **/
function SetCookieFromFriend()
{
	global $en_aff;

	if ( $en_aff && $_GET['idFriend'] )
	{
		$idFriend = getID( $_GET['idFriend'], 1 );
		if ( $idFriend )
			setcookie( "idFriend", $idFriend, time() + 10000 * 3600, "/" );
    }
}

/**
 * Custom Menu Function for Profile
 **/
function DesignQuickSearch()
{
    global $site;
	global $search_start_age;
	global $search_end_age;

    $gl_search_start_age    = (int)$search_start_age;
    $gl_search_end_age      = (int)$search_end_age;

	if ( $_COOKIE['memberID'] > 0 )
	{
		$arr_sex = getProfileInfo( $_COOKIE['memberID'] ); //db_arr("SELECT Sex FROM Profiles WHERE ID = ".(int)$_COOKIE['memberID']);
	    $member_sex = $arr_sex['Sex'];
	}
	else
	    $member_sex = 'male';

	ob_start();

?>

<!-- Quick Search -->

<form method="get" action="search_result.php">
<table cellspacing=2 cellpadding=0 border=0 align="center">

    <tr>
        <td align=right><?=_t("_I am a")?> - </td>
        <td><select name="Sex">
<?php
    echo SelectOptions("Sex", $member_sex);
?>
        </select>
        </td>
    </tr>

    <tr>
        <td align=right><?=_t("_Seeking for a")?> - </td>
        <td><select name="LookingFor">
<?php
    echo SelectOptions("LookingFor", ($member_sex=='male' ? 'female':'male') );
?>
        </select></td>
    </tr>
    <tr>
        <td align=right><?=_t("_Aged from")?> - </td>
        <td><select name="DateOfBirth_start">
        <?
        for ( $i = $gl_search_start_age ; $i < $gl_search_end_age ; $i++ )
        {
            $sel = $i == $gl_search_start_age ? 'selected="selected"' : '';
            echo "<option value=\"$i\" $sel>$i</option>";
        }
        ?>
        </select>
        </td>
    </tr>
    <tr>
        <td align=right><?=_t("_to")?> - </td>
        <td><select name="DateOfBirth_end">
        <?
        for ( $i = $gl_search_start_age ; $i <= $gl_search_end_age ; $i++ )
	{
            $sel = ($i == $gl_search_end_age) ? 'selected="selected"' : '';
            echo "<option value=\"$i\" $sel>$i</option>";
        }
        ?>
        </select>
        </td>
    </tr>

    <tr>
        <td align=right><?=_t("_With photos only")?> - </td>
        <td><input type=checkbox name=photos_only /></td>
    </tr>

    <tr>
        <td></td>
        <td><input class=no type=submit value="<?=_t("_Find")?>!" /></td>
    </tr>
    </table></form>

<!-- [ END ] Quick Search -->

<?php

    $ret = ob_get_contents();
    ob_end_clean();

    return $ret;

}

/**
 * Use this function in pages if you want to not cache it.
 **/
function send_headers_page_changed()
{
	$now        = gmdate('D, d M Y H:i:s') . ' GMT';

	header("Expires: $now");
	header("Last-Modified: $now");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
}

/**
 * return code for "SELECT" html element
 *  $fieldname - field name for wich will be retrived values
 *  $default   - default value to be selected, if empty then default value will be retrived from database
 **/
function SelectOptions( $fieldname, $default = "" )
{
	$ret = "";
	$arr = db_arr ("SELECT extra". (strlen($default) ? "" : ", search_default") ." FROM `ProfilesDesc` WHERE `name` = '$fieldname'");
	if ( !strlen($default) )
		$default = $arr['search_default'];
	
	$vals = preg_split ("/[,\']+/", $arr['extra'], -1, PREG_SPLIT_NO_EMPTY);
	foreach ( $vals as $v )
	{
		$v = trim($v);
		if ( !strlen($v) ) continue;
		if ( $v == $default )
			$ret .= "<option selected=\"selected\" value=\"$v\">"._t("_$v")."</option>\n";
		else
			$ret .= "<option value=\"$v\">"._t("_$v")."</option>\n";
	}
	return $ret;
}

SetCookieFromAffiliate();
SetCookieFromFriend();

/**
 * Return code for IM window
 */
function RetIM()
{
	global $site;
	global $tmpl;
	global $oTemplConfig;

	$ID = (int)$_COOKIE['memberID'];

    $im_width = strstr($oTemplConfig -> im_width,'%') ? $oTemplConfig -> im_width : $oTemplConfig -> im_width-1;
    $im_height = $oTemplConfig -> im_height;

	$im_win = $_COOKIE['im_win'] ? $_COOKIE['im_win'] : 'none';

	$langOpenNewWindow = _t("_Open in new window");
	$langShow = _t("_Show");
	$langHide = _t("_Hide");
	$linkDivider = "&nbsp;|&nbsp;";

	if ($im_win == 'none')
	{
		$div_hide = "none";
		$div_show = "inline";
	}
	else
	{
		$div_hide = "inline";
		$div_show = "none";
	}

	$out .= <<<EOF
<script type="text/javascript">
<!--
	function imShowHide()
	{

		var im_win = document.getElementById('im_win');
		var show = document.getElementById('show');
		var hide = document.getElementById('hide');

		if (im_win.style.display == 'none')
		{
			document.cookie = "im_win=inline;";
			im_win.style.display = 'inline';
			show.style.display= 'none';
			hide.style.display = 'inline';
		}
		else
		{
			document.cookie = "im_win=none;";
			im_win.style.display = 'none';
			show.style.display= 'inline';
			hide.style.display = 'none';
		}
	}
//-->
</script>

<table width="$im_width" cellspacing="0" cellpadding="0" class="text" border="0">
<tr>
	<td width="$im_width" align="center" height="20">
		<a target="_blank" href="{$site['url']}im.php">{$langOpenNewWindow}</a>{$linkDivider}<a href="javascript:void(0);" onclick="javascript: imShowHide();"><span id="show" style="display:{$div_show}">{$langShow}</span><span id="hide" style="display:{$div_hide}">{$langHide}</span></a>
	</td>
</tr>
<tr>
	<td width="$im_width">
		<div id="im_win" style="display: $im_win;">
			<iframe id="IFrameIMFL" name="IFrameIMFL" frameborder="0" scrolling="no" height="$im_height" width="$im_width" src="{$site['url']}im.php">
			</iframe>
		</div>
	</td>
</tr>
</table>
EOF;

    $im_width = strstr($im_width,'%') ? $im_width : $im_width+1;

    return DesignBoxContent( _t("_IM title"), $out, $oTemplConfig -> PageRetIM_db_num );
}


function loadShoutbox( $framewidth = 0, $frameheight = 0 )
{
	$iId = (int)$_COOKIE['memberID'];
	$sPassword = getPassword($iId);
	return getApplicationContent('shoutbox', 'user', array('id' => $iId, 'password' => $sPassword));
}


/**
 * parse string and replace text to smiles where possible
 */
function process_smiles( $str )
{
	global $site;

	$res = db_res("SELECT `code`, `smile_url`, `emoticon` FROM `smiles` ORDER BY LENGTH(`code`) DESC");
	while ( $arr = mysql_fetch_array($res) )
	{
		$str = str_replace( $arr['code'], "<img border=\"0\" alt=\"{$arr['emoticon']}\" src=\"{$site['smiles']}{$arr['smile_url']}\" />", $str );
	}
	return $str;
}

/**
 * put html code for inserting smiles
 */
function put_smiles ( $textarea, $br = 999 )
{
	global $site;

    $res = db_res("SELECT `code`, `smile_url`, `emoticon` FROM smiles ORDER BY `ID` ASC,`smile_url`");
	$i = 0;
    while ( $arr = mysql_fetch_array($res) )
    {
        if ( $smile_url == $arr['smile_url'] ) continue;
        $smile_url = $arr['smile_url'];
	$counter = " var counter = document.getElementById('{$textarea}counter'); if (counter) { counter.value=document.getElementById('{$textarea}').value.length; }";
        $ret .=  "<a
            href=\"javascript:void(null);\"
            onClick=\"emoticon(document.getElementById('{$textarea}'),'{$arr['code']}'); $counter;\"
            title=\"{$arr['emoticon']}\"
            ><img border=0
            alt=\"{$arr['emoticon']}\"
            src=\"{$site['smiles']}{$arr['smile_url']}\" /></a> \n";

		if ( ((++$i) % $br) == 0 ) $ret .= "<br />";

    }
	return $ret;
}

function get_active_color()
{
    global $db_color_index;
    $db_colors = array ( 'green', 'magenta', 'orange', 'violet', 'yellow' );
    $index = $db_color_index;

	// Update color index.
    if ( 4 == $db_color_index)
    {
        $db_color_index = 0;
    }
    else
    {
        $db_color_index++;
    }
    return $db_colors[$index];
}


function get_member_thumbnail( $ID, $float, $bDrawMargin=FALSE )
{
	global $site;
	require_once( BX_DIRECTORY_PATH_ROOT . 'profilePhotos.php' );
	$user_is_online = get_user_online_status( $ID );
	//$sSexSql = "SELECT `Sex` FROM `Profiles` WHERE `ID` = '{$ID}'";
	$aSexSql = getProfileInfo( $ID ); //db_arr( $sSexSql );
	$oPhoto = new ProfilePhotos( $ID );
	$oPhoto -> getActiveMediaArray();
	$aFile = $oPhoto -> getPrimaryPhotoArray();

	if( extFileExists( $oPhoto -> sMediaDir . 'thumb_' . $aFile['med_file'] ) )
		$sFileName = $oPhoto -> sMediaUrl . 'thumb_' . $aFile['med_file'];
	else
	{
		if( $aSexSql['Sex'] == 'female' or $aSexSql['Sex'] == 'Female' )
			$sSexPic = 'woman_medium.gif';
		elseif( $aSexSql['Sex'] == 'male' or $aSexSql['Sex'] == 'Male' )
			$sSexPic = 'man_medium.gif';
		else
			$sSexPic = 'visitor_medium.gif';
		
		$sFileName = getTemplateIcon( $sSexPic );
	}

	$sMarginsAddon = ($bDrawMargin) ? " margin-right:10px;margin-bottom:10px; " : '';
	$style = 
		'width:' . $oPhoto -> aMediaConfig['size']['thumbWidth'] . 'px;' .
		'height:' . $oPhoto -> aMediaConfig['size']['thumbHeight'] . 'px;' .
		'background-image:url(' . $sFileName . ');';

	$ret = '';
	$ret .= '<div class="thumbnail_block" style="float:' . $float . '; ">';
	$ret .= getProfileOnlineStatus( $user_is_online, $bDrawMargin );
		$ret .= "<a href=\"".getProfileLink($ID)."\">";
			$ret .= '<img src="' . getTemplateIcon( 'spacer.gif' ) . '" style="' . $sMarginsAddon . $style . '" alt="' . process_line_output( $aFileName['med_title'] ) . '" />';
		$ret .= '</a>';
	$ret .= '</div>';

	return $ret;
}

function get_member_icon( $ID, $float = 'none', $bDrawMargin=FALSE )
{
	global $site;
	require_once( BX_DIRECTORY_PATH_ROOT . 'profilePhotos.php' );
	//$sSexSql = "SELECT `Sex` FROM `Profiles` WHERE `ID` = '{$ID}'";
	$aSexSql = getProfileInfo( $ID ); //db_arr( $sSexSql );
	$oPhoto = new ProfilePhotos( $ID );
	$oPhoto -> getActiveMediaArray();
	$aFile = $oPhoto -> getPrimaryPhotoArray();

	if( extFileExists( $oPhoto -> sMediaDir . 'icon_' . $aFile['med_file'] ) )
		$sFileName = $oPhoto -> sMediaUrl . 'icon_' . $aFile['med_file'];
	else
	{
		if( $aSexSql['Sex'] == 'female' or $aSexSql['Sex'] == 'Female' )
			$sSexPic = 'woman_small.gif';
		elseif( $aSexSql['Sex'] == 'male' or $aSexSql['Sex'] == 'Male' )
			$sSexPic = 'man_small.gif';
		else
			$sSexPic = 'visitor_small.gif';
		
		$sFileName = getTemplateIcon( $sSexPic );
	}

	$sMarginsAddon = ($bDrawMargin) ? " margin-right:10px;margin-bottom:10px; " : '';
	$style = 
		'width:' . $oPhoto -> aMediaConfig['size']['iconWidth'] . 'px;' .
		'height:' . $oPhoto -> aMediaConfig['size']['iconHeight'] . 'px;' .
		'background-image:url(' . $sFileName . ');';
	
	$ret = '';
	$ret .= '<div class="thumbnail_block" style="float:' . $float . '; position:relative; ">';
		$ret .= "<a href=\"".getProfileLink($ID)."\">";
			$ret .= '<img src="' . getTemplateIcon( 'spacer.gif' ) . '" style="' . $sMarginsAddon. $style . '" alt="' . process_line_output( $aFileName['med_title'] ) . '" />';
		$ret .= '</a>';
	$ret .= '</div>';

	return $ret;
}

/*
	Generate Pagination function.
	Generates string like:
	<< 1 ... 3 4 [5] 6 7 ... 10 >>
	with links.
	attrlist:
	$pagesNum - total number of pages.
	$page - current page in set.
	$pagesUrl - template of link for all pages.
		Template should contain {page} instruction which
		will be replaced by real page number.
	$oTemplConfig -> paginationDifference - defines number
		of pages shown before and after current page.
		If paginationDifference = 0 then all pages numbers will be shown without skipping.
*/
function genPagination( $pagesNum, $page, $pagesUrl, $pagesOnclick = '' )
{
	global $oTemplConfig;
	
	$paginDiff = $oTemplConfig -> paginationDifference;
	$paginFrom = ( $paginDiff ? ( $page - $paginDiff ) : 2 );
	$paginTo   = ( $paginDiff ? ( $page + $paginDiff ) : ( $pagesNum - 1 ) );
	$needDrop1 = $needDrop2 = true;
	
	if( $paginFrom <= 2 )
	{
		$paginFrom = 2;
		$needDrop1 = false;
	}
	
	if( $paginTo >= ( $pagesNum - 1 ) )
	{
		$paginTo = $pagesNum - 1;
		$needDrop2 = false;
	}
	
	$ret = '<div class="paginate">';
	
	if( $page > 1 )
		$ret .= genPageSwitcher( ( $page - 1 ), $pagesUrl, $pagesOnclick, false, '<<', _t('_Previous page') );
	$ret .= genPageSwitcher( 1, $pagesUrl, $pagesOnclick, ($page == 1) );
	$ret .= ($needDrop1 ? ' ... ' : '');
	for( $p = $paginFrom; $p <= $paginTo; $p++ )
		$ret .= genPageSwitcher( $p, $pagesUrl, $pagesOnclick, ($page == $p) );
	$ret .= ($needDrop2 ? ' ... ' : '');
	$ret .= genPageSwitcher( $pagesNum, $pagesUrl, $pagesOnclick, ($page == $pagesNum) );
	if( $page < $pagesNum )
		$ret .= genPageSwitcher( ( $page + 1 ), $pagesUrl, $pagesOnclick, false, '>>', _t('_Next page') );
	
	$ret .= '</div>';
	return $ret;
}

/* This function generates page link or just a text (if page is current)
	attrlist:
	$page - integer number of page (will be put instead of {page} instruction to template
	$link - template of page link
	$current - defines if page link is active or not (current page)
	$sign - string which is shown instead of page number in link text ( Ex.: << )
	$title - alternative link tip text (<a title="..." ). Ex.: Previous page
*/
function genPageSwitcher( $page, $link, $onclick = '', $current = false, $sign = null, $title = null )
{
	if( !isset( $sign ) )
		$sign = $page;
	
	if( !isset( $title ) )
		$title = $sign;
	
	$link    = str_replace( "{page}", $page, $link );
	$onclick = str_replace( "{page}", $page, $onclick );

	if( $onclick )
		$addOn = " onclick=\"$onclick\"";
	
	$ret = '';
	if( $current )
		$ret = " <i>$sign</i> ";
	else
		$ret = " <a href=\"$link\" title=\"$title\"{$addOn}>$sign</a> ";
	
	return $ret;
}

function getMainLogo()
{
	global $dir;
	global $site;
	
	$ret = '';
	
	foreach( array( 'gif', 'jpg', 'png' ) as $ext )
		if( file_exists( $dir['mediaImages']."logo.$ext" ) )
		{
			$ret .= '<a href="' . $site['url'] . '">';
				$ret .= "<img src=\"{$site['mediaImages']}logo.$ext\" class=\"mainLogo\" alt=\"logo\" />";
			$ret .= '</a>';
			break;
		}
	return $ret;
}

function getPromoImagesList ()
{
	$photos = getPromoImagesArray();
	
	shuffle( $photos );
	
	return implode( ',', $photos );
}


function getPromoImagesArray()
{
	global $dir;
	
	$aFiles = array();
	
	$rDir = opendir( $dir['imagesPromo'] );
	
	if( $rDir )
	{
		while( $sFile = readdir( $rDir ) )
		{
			if( $sFile == '.' or $sFile == '..' or !is_file( $dir['imagesPromo'] . $sFile ) )
				continue;
			
			$aFiles[] = $sFile;
		}
		closedir( $rDir );
	}
	
	return $aFiles;
}

function genRSSHtmlOut( $sUrl, $iNum = 0 )
{
	$php_date_format = getParam( 'php_date_format' );
	
	$oRSS = new BxRSS( $sUrl );
	if( !$oRSS )
		return '';
	
	ob_start()
	?>
		<div class="rss_feed_wrapper">
	<?
	
	$iCounter = 0;
	
	foreach( $oRSS -> items as $oItem )
	{
		$sDate = date( $php_date_format, strtotime( $oItem -> pubDate ) );
		
		?>
			<div class="rss_item_wrapper">
				<div class="rss_item_header">
					<a href="<?= $oItem -> link ?>"><?= htmlspecialchars_adv( $oItem -> title ) ?></a>
				</div>
				<div class="rss_item_info">
					<span><img src="<?= getTemplateIcon( 'clock.gif' ) ?>" /><?= $sDate ?></span>
				</div>
				<div class="rss_item_desc">
					<?= htmlspecialchars_adv( $oItem -> description ) ?>
				</div>
			</div>
		<?
		
		$iCounter ++;
		if( $iNum != 0 and $iCounter >= $iNum )
			break;
	}
	
	?>
			<div class="rss_read_more">
				<a href="<?= $oRSS -> link ?>"><?= _t( '_Visit Source' ) ?></a>
			</div>
		</div>
	<?
	
	return ob_get_clean();
}

function getSiteStat($sMode = '')
{
	global $site;
	
	$iMin = getParam("member_online_time");
	
	$aStat = array(
	
	'all' => array('capt'=>_t("_Members"),     'query'=>"SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = 'Active'",'link'=>'browse.php','adm_query'=> "",'adm_link'=>"profiles.php"),
	'pph' => array('capt'=>_t("_Photos"),      'query'=>"SELECT COUNT(`medID`) FROM `sharePhotoFiles` WHERE `Approved`='true'",'link'=>'browsePhoto.php','adm_query'=> "",'adm_link'=>""),
	'evs' => array('capt'=>_t("_Events"),      'query'=>"SELECT COUNT(`ID`) FROM `SDatingEvents` WHERE `Status`='Active'",'link'=>'events.php?show_events=all&action=show','adm_query'=> "",'adm_link'=>""),
	'onl' => array('capt'=>_t("_Online"),      'query'=>"SELECT COUNT(`ID`) AS `count_onl` FROM `Profiles`  WHERE `LastNavTime` > SUBDATE(NOW(), INTERVAL $iMin MINUTE)",'link'=>'search_result.php?online_only=1','adm_query'=> "", 'adm_link'=>""),
	'pvi' => array('capt'=>_t("_Videos"),      'query'=>"SELECT COUNT(`ID`) FROM `RayMovieFiles` WHERE `Approved`='true'",'link'=>'browseVideo.php','adm_query'=> "", 'adm_link'=>""),
	'pls' => array('capt'=>_t("_Polls"),       'query'=>"SELECT COUNT(`id_poll`) FROM `ProfilesPolls` WHERE `poll_approval`='1'", 'link'=>'polls.php','adm_query'=> "",'adm_link'=>""),
	'ntd' => array('capt'=>_t("_New Today"),   'query'=>"SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = 'Active' AND (TO_DAYS(NOW()) - TO_DAYS(`LastReg`)) <= 1",'link'=>'','adm_query'=> "",'adm_link'=>""),
	'pmu' => array('capt'=>_t("_Music"),       'query'=>"SELECT COUNT(`ID`) FROM `RayMusicFiles` WHERE `Approved`='true'",'link'=>'browseMusic.php','adm_query'=> "",'adm_link'=>""),
	'tps' => array('capt'=>_t("_Topics"),      'query'=>"SELECT IF( NOT ISNULL( SUM(`forum_topics`)), SUM(`forum_posts`), 0) AS `Num` FROM `pre_forum`",'link'=>'orca','adm_query'=> "",'adm_link'=>""),
	'nwk' => array('capt'=>_t("_This Week"),   'query'=>"SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = 'Active' AND (TO_DAYS(NOW()) - TO_DAYS(`LastReg`)) <= 7",'link'=>'','adm_query'=> "",'adm_link'=>""),
	'pvd' => array('capt'=>_t("_Profile Videos"), 'query'=>"SELECT `Approved` FROM `RayVideoStats`",'link'=>'','adm_query'=> "",'adm_link'=>"",'hide'=>false),
	'pts' => array('capt'=>_t("_Posts"),       'query'=>"SELECT IF( NOT ISNULL( SUM(`forum_posts`)), SUM(`forum_posts`), 0) AS `Num` FROM `pre_forum` ",'link'=>'orca','adm_query'=> "",'adm_link'=>""),
	'nmh' => array('capt'=>_t("_This Month"),  'query'=>"SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = 'Active' AND (TO_DAYS(NOW()) - TO_DAYS(`LastReg`)) <= 30",'link'=>'','adm_query'=> "",'adm_link'=>""),
	'tgs' => array('capt'=>_t("_Tags"),        'query'=>"SELECT COUNT( DISTINCT `Tag` ) FROM `Tags`",'link'=>'','adm_query'=> "",'adm_link'=>""),
	'ars' => array('capt'=>_t("_Articles"),    'query'=>"SELECT COUNT(`ArticlesID`) FROM `Articles`",'link'=>'articles.php','adm_query'=> "",'adm_link'=>""),
	'nyr' => array('capt'=>_t("_This Year"),   'query'=>"SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status` = 'Active' AND (TO_DAYS(NOW()) - TO_DAYS(`LastReg`)) <= 365",'link'=>'','adm_query'=> "",'adm_link'=>""),
	'grs' => array('capt'=>_t("_Groups"),      'query'=>"SELECT COUNT(`ID`) FROM `Groups` WHERE `status`='Active'",'link'=>'groups_home.php','adm_query'=> "",'adm_link'=>""),
	'cls' => array('capt'=>_t("_Classifieds"), 'query'=>"SELECT COUNT(`ID`) FROM `ClassifiedsAdvertisements` WHERE `Status`='active'",'link'=>'classifieds.php?Browse=1','adm_query'=> "",'adm_link'=>""),
	'frs' => array('capt'=>_t("_Friends"),     'query'=>"SELECT COUNT(`ID`) FROM `FriendList` WHERE `Check`='1'",'link'=>'','adm_query'=> "",'adm_link'=>"")
	
	);
	
	if ($sMode == 'admin')
	{
		$aAdmin = array(
		
		'all'=>array('adm_query'=>"SELECT COUNT(`ID`) FROM `Profiles` WHERE `Status`!='Active'",'adm_link'=>'profiles.php?profiles=Approval'),
		'pph'=>array('adm_query'=>"SELECT COUNT(`medID`) FROM `sharePhotoFiles` WHERE `Approved`='false'",'adm_link'=>'browsePhoto.php'),
		'evs'=>array('adm_query'=>"SELECT COUNT(`ID`) FROM `SDatingEvents` WHERE `Status`!='Active'",'adm_link'=>'sdating_admin.php'),
		'onl'=>array('adm_query'=>"",'adm_link'=>''),
		'pvi'=>array('adm_query'=>"SELECT COUNT(`ID`) FROM `RayMovieFiles` WHERE `Approved`!='true'",'adm_link'=>'browseVideo.php'),
		'pls'=>array('adm_query'=>"SELECT COUNT(`id_poll`) FROM `ProfilesPolls` WHERE `poll_approval`!='1'",'adm_link'=>'post_mod_ppolls.php'),
		'ntd'=>array('adm_query'=>"",'adm_link'=>''),
		'pmu'=>array('adm_query'=>"SELECT COUNT(`ID`) FROM `RayMusicFiles` WHERE `Approved`!='true'",'adm_link'=>'browseMusic.php'),
		'tps'=>array('adm_query'=>"",'adm_link'=>''),
		'nwk'=>array('adm_query'=>"",'adm_link'=>''),
		'tgs'=>array('adm_query'=>"",'adm_link'=>''),
		'pts'=>array('adm_query'=>"",'adm_link'=>''),
		'nmh'=>array('adm_query'=>"",'adm_link'=>''),
		'frs'=>array('adm_query'=>"",'adm_link'=>''),
		'ars'=>array('adm_query'=>"",'adm_link'=>'articles.php'),
		'nyr'=>array('adm_query'=>"",'adm_link'=>''),
		'grs'=>array('adm_query'=>"SELECT COUNT(`ID`) FROM `Groups` WHERE `status`!='Active'",'adm_link'=>'groups.php'),
		'cls'=>array('adm_query'=>"SELECT COUNT(`ID`) FROM `ClassifiedsAdvertisements` WHERE `Status` != 'active'",'adm_link'=>'manage_classifieds.php'),
		'pvd'=>array('adm_query'=>"",'adm_link'=>'javascript:window.open(\'../ray/modules/video/app/admin.swf?nick={adminLogin}&password={adminPass}&url=../../../XML.php\',\'RayVideoAdmin\',\'width=700,height=330,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=0\');')
		);
		$sAdd = '_a';
		$sProfVideo = getApplicationContent('video', 'stat', array(), true);
	}
	else
	{
		$aAdmin = '';
		$sProfVideo = '';
	}
	$sCode  = $sProfVideo.'<div>';

	foreach ($aStat as $sKey => $sVal)
	{
		$sLink = strlen($sVal['link']) > 0 ? '<a href="'.$site['url'].$sVal['link'].'">'.$sVal['capt'].'</a>' : $sVal['capt'] ;
		if ($sVal['hide'] === true && $sMode != 'admin') continue;
		if ( !is_array($aAdmin) )
		{
			$iNum = strlen($sVal['query']) > 0 ? db_value($sVal['query']) : 0;
			if ($sKey == 'pls')
			{
				$iNum = $iNum + db_value("SELECT COUNT(`ID`) FROM `polls_q` WHERE `Active`='on'");
			}
		}
		else
		{
			$iNum  = strlen($aAdmin[$sKey]['adm_query']) > 0 ? db_value($aAdmin[$sKey]['adm_query']) : 0;
			if ( strlen($aAdmin[$sKey]['adm_link']) > 0 )
			{
				if( substr( $aAdmin[$sKey]['adm_link'], 0, strlen( 'javascript:' ) ) == 'javascript:' ) // smile :))
				{
					$sHref = 'javascript:void(0);';
					$sOnclick = 'onclick="' . $aAdmin[$sKey]['adm_link'] . '"';
					
					$aAdmin = db_arr( "SELECT * FROM `Admins` LIMIT 1" );
					$sOnclick = str_replace( '{adminLogin}', $aAdmin['Name'], $sOnclick );
					$sOnclick = str_replace( '{adminPass}',  $aAdmin['Password'], $sOnclick );
				}
				else
				{
					$sHref = $aAdmin[$sKey]['adm_link'];
					$sOnclick = '';
				}
				$sLink = '<a href="'.$sHref.'" '.$sOnclick.'>'.$sVal['capt'].'</a>';
			}
			else
			{
				$sLink = $sVal['capt'];
			}
			if ($sKey == 'pls')
			{
				$iNum = $iNum + db_value("SELECT COUNT(`ID`) FROM `polls_q` WHERE `Active`<>'on'");
			}
		}
		
		switch ($sKey)
		{
			case 'all':
			case 'onl':
			case 'ntd':
			case 'nwk':
			case 'nmh':
			case 'nyr':
				$sIcon = 'mbs.gif';
				break;
			case 'all':
				$sIcon = 'us.gif';
				break;
			case 'pvi':
			case 'pvd':
				$sIcon = 'pvi.gif';
				break;
			default:
				$sIcon = $sKey.'.gif';	
		}
		
		$sCode .= '<div class="siteStatUnit" id="'.$sKey.$sAdd.'"><img src="'.getTemplateIcon($sIcon).'" /> '.$iNum.' '.$sLink.'</div>';
	}
	
	$sCode .= '</div><div class="clear_both"></div>';
	
	return $sCode;
}

function getPromoCode()
{
	global $site;
	
	if( getParam( 'enable_flash_promo' ) == 'on' )
		$sCode = getParam( 'flash_promo_code' );
	else
		$sCode = '<div class="promo_code_wrapper">' . getParam( 'custom_promo_code' ) . '</div>';
	
	$aReplace = array(
		'images'           => $site['images'],
		'Welcome'          => _t( '_Welcome' ),
		'To_The_Community' => _t( '_To The Community' ),
		'promo_url'        => $site['imagesPromo'],
		'promo_images'     => getPromoImagesList(),
		);
	
	
	foreach( $aReplace as $sKey => $sValue )
		$sCode = str_replace( "__{$sKey}__", $sValue, $sCode );
	
	return $sCode;
}

function getTemplateIcon( $sFileName )
{
	global $site;
	global $dir;
	global $tmpl;

	$sBase = $dir['root'] . 'templates/base/images/icons/' . $sFileName;
	$sTemplate = $dir['root'] . 'templates/tmpl_' . $tmpl . '/images/icons/' . $sFileName;

	if( extFileExists( $sTemplate ) )
	{
		$iconUrl = $site['icons'] . $sFileName;
	}
	else
	{
		if( extFileExists( $dir['base'] . 'images/icons/' . $sFileName ) )
		{
			$iconUrl = $site['base'] . 'images/icons/' . $sFileName;
		}
		else
		{
			$iconUrl = getTemplateIcon( 'spacer.gif' );
		}
	}

	return $iconUrl;
}

function getVersionComment()
{
	global $site;
	$aVer = explode( '.', $site['ver'] );
	
	// version output made for debug possibilities.
	// randomizing made for security issues. do not change it...
	$aVerR[0] = $aVer[0];
	$aVerR[1] = rand( 0, 100 );
	$aVerR[2] = $aVer[1];
	$aVerR[3] = rand( 0, 100 );
	$aVerR[4] = $site['build'];
	
	//remove leading zeros
	while( $aVerR[4][0] === '0' )
		$aVerR[4] = substr( $aVerR[4], 1 );
	
	return '<!-- ' . implode( ' ', $aVerR ) . ' -->';
}

?>