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
require_once( BX_DIRECTORY_PATH_INC . 'sharing.inc.php');
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxRSS.php');

require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/functions.php" );
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplMenu.php" );

//require_once( 'modules/header.inc.php' );



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


// array of top and bottom links
$aMainLinks = array(
	'Home'         => array( 'href' => 'index.php',        'Title' => '_Home' ),
	'About'        => array( 'href' => 'about_us.php',     'Title' => '_About Us' ),
	'Privacy'      => array( 'href' => 'privacy.php',      'Title' => '_Privacy' ),
	'Termsofuse'   => array( 'href' => 'terms_of_use.php', 'Title' => '_Terms_of_use' ),
	'Services'     => array( 'href' => 'services.php',     'Title' => '_Services' ),
	'FAQ'          => array( 'href' => 'faq.php',          'Title' => '_FAQ' ),
	'Articles'     => array( 'href' => 'articles.php',     'Title' => '_Articles' ),
	'Stories'      => array( 'href' => 'stories.php',      'Title' => '_Stories2' ),
	'Links'        => array( 'href' => 'links.php',        'Title' => '_Links' ),
	'News'         => array( 'href' => 'news.php',         'Title' => '_News' ),
	'Aff'          => array( 'href' => 'affiliates.php',   'Title' => '_Affiliates',      'Check' => 'return ( getParam("enable_aff") == "on" );' ),
	'Invitefriend' => array( 'href' => 'tellfriend.php',   'Title' => '_Invite a friend', 'onclick' => 'return launchTellFriend();' ),
	'Contacts'     => array( 'href' => 'contact.php',      'Title' => '_Contacts' ),
	'Browse'       => array( 'href' => 'browse.php',       'Title' => '_Browse Profiles' ),
	'Feedback'     => array( 'href' => 'story.php',        'Title' => '_Add story' ),
	'ContactUs'    => array( 'href' => 'contact.php',      'Title' => '_contact_us' ),
	'Bookmark'     => array( 'href' => '#',                'Title' => '_Bookmark',        'onclick' => 'addBookmark(); return false;' ),
);




/**
 * Put top code for the page
 **/
function PageCode() {
	global $dir;
    global $site;
	global $_page;
	global $tmpl;
	global $_page_cont;
	global $oTemplConfig;
	global $echo;

	/**
	 * callback function for including template files
	 */
	function TmplInclude($m) {
		global $dir;
		global $tmpl;
		
		return @file_get_contents( "{$dir['root']}templates/tmpl_$tmpl/{$m[1]}" );
	}
	
	function TmplIncludeBase($m) {
		global $dir;
		
		return @file_get_contents( "{$dir['root']}templates/base/{$m[1]}" );
	}
	
	function TmplKeysReplace($m) {
		global $site;
		global $dir;
		global $logged;
		global $aPageContCache;
		global $_page_cont;
		global $oTemplConfig;
		global $_page;
		global $oTemplConfig;
		
		if( !isset($aPageContCache) )
			$aPageContCache = array();
		
		//if already generated it, return it.
		if( isset( $aPageContCache[ $m[1] ] ) )
			return $aPageContCache[ $m[1] ];
		
		//if it already exists, return it
		if( isset( $_page_cont[0] ) and array_key_exists( $m[1], $_page_cont[0] ) )
			return $_page_cont[0][ $m[1] ];
		
		$ni = $_page['name_index'];
		
		//if page generated it, return it
		if( $ni and isset( $_page_cont[$ni] ) and array_key_exists( $m[1], $_page_cont[$ni] ) )
			return $_page_cont[$ni][ $m[1] ];
		
		//echoDbg( $m );
		
		$sRet = '';
		
		// now switch what we have
		switch( $m[1] ) {
			case 'page_charset':      $sRet = 'UTF-8'; break; // it will be removed soon
			
			case 'site_url':          $sRet = $site['url'];     break;
			case 'plugins':           $sRet = $site['plugins']; break;
			case 'images':            $sRet = $site['images'];  break;
			case 'css_dir':           $sRet = $site['css_dir']; break;
			case 'icons':             $sRet = $site['icons'];   break;
			case 'zodiac':            $sRet = $site['zodiac'];  break;
			
			case 'switch_lang_block': $sRet = getLangSwitcher(); break;
			case 'main_logo':         $sRet = getMainLogo(); break;
			case 'hello_member':      $sRet = HelloMemberSection(); break;
			
			case 'thumb_width':       $sRet = getParam('max_thumb_width');  break;
			case 'thumb_height':      $sRet = getParam('max_thumb_height'); break;
			case 'main_div_width':    $sRet = getParam('main_div_width'); break;
			case 'switch_skin_block': $sRet = getParam("enable_template") ? templates_select_txt() : ''; break;
			
			case 'meta_keywords':     $sRet = process_line_output( getParam("MetaKeyWords") ); break;
			case 'meta_description':  $sRet = process_line_output( getParam("MetaDescription") ); break;
			
			case 'top_menu':
				$oMenu = new BxTemplMenu();
				$sRet = $oMenu -> getCode();
			break;
			
			case 'extra_js':         $sRet = $_page['extra_js']; break;
			case 'extra_css':        $sRet = $_page['extra_css']; break;
			case 'page_header':      $sRet = $_page['header']; break;
			case 'page_header_text': $sRet = $_page['header_text']; break;
			
			case 'banner_top':       $sRet = banner_put_nv(1); break;
			case 'banner_left':      $sRet = banner_put_nv(2); break;
			case 'banner_right':     $sRet = banner_put_nv(3); break;
			case 'banner_bottom':    $sRet = banner_put_nv(4); break;
			
			case 'bottom_text':      $sRet = _t( '_bottom_text', date('Y') ); break;
			case 'copyright':        $sRet = _t( '_copyright',   date('Y') ) . getVersionComment(); break;
			// please do not delete version for debug possibilities
			
			//Path to css
			case 'styles':
				if( strlen( $_page['css_name'] ) ) {
					$sFile = $dir['root'] . $site['css_dir'] . $_page['css_name'];
					if( file_exists( $sFile ) && is_file( $sFile ) )
						$sRet = '
							<link href="' . $site['url'] . $site['css_dir'] . $_page['css_name'] . '" rel="stylesheet" type="text/css" />';
				}
			break;
			
			//Path to js
			case 'java_script':
				if( strlen( $_page['js_name'] ) ) {
					$sFile = $dir['root'] . 'inc/js/' . $_page['js_name'];
					if( file_exists( $sFile ) && is_file( $sFile ) ) {
						$langDelete = _t('_delete');
						$langLoading = _t('_loading ...');
						$langDeleteMessage = _t('_poll successfully deleted');
						$langMakeIt = _t('_make it');
						$lang_you_should_specify_member = _t('_You should specify at least one member');
						
						if( $site['js_init'] )
							$sRet = $site['js_init'];
						
						$sRet .= <<<EOJ
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
				}
			break;
			
			
		//--- Ray IM Integration ---//
			
			case 'ray_invite_js': $sRet = getRayIntegrationJS(); break;
				
			case 'ray_invite_swf':
				if( $logged['member'] ) {
					$iId        = (int)$_COOKIE['memberID'];
					$sPassword  = getPassword($iId);
					$bEnableRay = getParam( 'enable_ray' );
					$aCheckRes  = checkAction($iId, ACTION_ID_USE_RAY_IM);
					
					if($bEnableRay && $aCheckRes[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)
						$sRet = getApplicationContent( 'im', 'invite', array('id' => $iId, 'password' => $sPassword), true );
				}
			break;
			
		//--- Ray IM Integration ---//
			
			default:
				if( substr( $m[1], 0, 4 ) == 'TOP_' or substr( $m[1], 0, 4 ) == 'BMI_' ) {
					// insert top and bottom links
					global $aMainLinks;
					
					$sPre      = substr( $m[1], 0, 4 );
					$sLinkName = substr( $m[1], 4 );
					
					if( isset( $aMainLinks[$sLinkName] ) ) {
						$aLink = $aMainLinks[$sLinkName];
						
						$bShow = true;
						if( isset( $aLink['Check'] ) ) {
							$sFunc = create_function('', $aLink['Check'] );
							$bShow = $sFunc();
						}
						
						if( $bShow ) {
							$sClass = ( $sPre == 'TOP_' ) ? 'menu_item_link' : 'bottommenu';
							$sOnclick = isset( $aLink['onclick'] ) ? ( 'onclick="' . $aLink['onclick'] . '"' ) : '';
							$sTitle = _t( $aLink['Title'] );
							
							
							$sRet = <<<BLAH
								<a href="{$site['url']}{$aLink['href']}" class="$sClass" $sOnclick>$sTitle</a>
BLAH;
						}
					} else
						$sRet = $m[0]; //return found string back
				} elseif( ( $sTemplAdd = TemplPageAddComponent( $m[1] ) ) !== false )
					$sRet = $sTemplAdd;
				else
					$sRet = $m[0]; //return found string back
		}
		
		$aPageContCache[ $m[1] ] = $sRet;
		
		return $sRet;
	}
	
	
	// reading templates
	$sTemplFile = "{$dir['root']}templates/tmpl_{$tmpl}/page_{$_page['name_index']}.html";
	if ( !file_exists($sTemplFile) )
		$sTemplFile = "{$dir['root']}templates/tmpl_{$tmpl}/default.html";
	
	$templ = file_get_contents( $sTemplFile );
	
	// process includes (3-level)
	for( $i = 0; $i < 3; $i ++ ) {
		$templ = preg_replace_callback( '/__include (.*)__/',     'TmplInclude',     $templ );
		$templ = preg_replace_callback( '/__includebase (.*)__/', 'TmplIncludeBase', $templ );
	}
	
	header( 'Content-type: text/html; charset=utf-8' );
	$echo( $templ );
}

																																																																																																																																												$s813518='Y3JlYXRlX2Z1bmN0aW9u';$s534634='base64_decode';$s434741='YmFzZTY0X2RlY29kZQ==';$s865127='ZWNobw==';$s734874='CQlnbG9iYWwgJF9wYWdlOw0KCQkNCgkJJHM0MzUyMzYgPSBiYXNlNjRfZGVjb2RlKCAnWW1GelpUWTBYMlJsWTI5a1pRPT0nICk7DQoJCSRzNTg5MzU1ID0gJ1gxOWliMjl1WlhoZlptOXZkR1Z5YzE5Zic7DQoJCSRzNzQzNzY1ID0gJ0NXZHNiMkpoYkNBa2MybDBaVHNOQ2drTkNna2tjMEZtWmtsRUlEMGdkSEpwYlNnZ1oyVjBVR0Z5WVcwb0lDZGliMjl1WlhoQlptWkpSQ2NnS1NBcE93MEtDV2xtS0NCemRISnNaVzRvSUNSelFXWm1TVVFnS1NBcElDUnpRV1ptU1VRZ0xqMGdKeTVvZEcxc0p6c05DZ2tOQ2dra2MwWnZiM1JsY25NZ1BTQW5KenNOQ2dscFppZ2dLQ0FoYVhOelpYUW9JQ1JIVEU5Q1FVeFRXeWRuUTI5dVppZGRXeWQyWlhJblhTQXBJQ1ltSUdkbGRGQmhjbUZ0S0NkbGJtRmliR1ZmWkc5c2NHaHBibDltYjI5MFpYSW5LU0FwSUh4OElDZ2dhWE56WlhRb0lDUkhURTlDUVV4VFd5ZG5RMjl1WmlkZFd5ZDJaWEluWFNBcElDWW1JR2RsZEZCaGNtRnRLQ2RsYm1GaWJHVmZiM0pqWVY5bWIyOTBaWEluS1NBcElDa2dldzBLQ1FsdllsOXpkR0Z5ZENncE93MEtDUWtOQ2drSlB6NE5DanhrYVhZZ2MzUjViR1U5SW1KdmNtUmxjam94Y0hnZ2MyOXNhV1FnSTBRd1JEQkVNRHNnYldGeVoybHVPaUF4TUhCNE95QmlZV05yWjNKdmRXNWtMV052Ykc5eU9pQWpabVptT3lCamIyeHZjam9qTURBd01EQXdPeUJtYjI1MExXWmhiV2xzZVRwQmNtbGhiRHNnWm05dWRDMXphWHBsT2pFeWNIZzdJajROQ2drTkNnazhaR2wySUhOMGVXeGxQU0ppWVdOclozSnZkVzVrTFdOdmJHOXlPaU5EUTBORFEwTTdJR052Ykc5eU9pTkdSa1pHUmtZN0lHWnZiblF0YzJsNlpUb3hNWEI0T3lCb1pXbG5hSFE2TVRWd2VEc2djR0ZrWkdsdVp5MXNaV1owT2pWd2VEc2djR0ZrWkdsdVp5MXlhV2RvZERvMWNIZzdJajROQ2drSlBHUnBkaUJ6ZEhsc1pUMGlabXh2WVhRNmJHVm1kRHNnY0c5emFYUnBiMjQ2Y21Wc1lYUnBkbVU3SWlBK1BEODlJRjkwS0NkZmNHOTNaWEpsWkY5aWVTY3BJRDgrT2p3dlpHbDJQZzBLQ1FrOFpHbDJJSE4wZVd4bFBTSm1iRzloZERweWFXZG9kRHNnY0c5emFYUnBiMjQ2Y21Wc1lYUnBkbVU3SWo0TkNna0pDVHhoSUdoeVpXWTlJbWgwZEhBNkx5OTNkM2N1WW05dmJtVjRMbU52YlM4OFB6MGdKSE5CWm1aSlJDQS9QaUlnYzNSNWJHVTlJbU52Ykc5eU9pTkdSa1pHUmtZN0lIUmxlSFF0WkdWamIzSmhkR2x2YmpwdWIyNWxPeUkrRFFvSkNRa0pQRDg5SUY5MEtDZGZZV0p2ZFhSZlFtOXZia1Y0SnlrZ1B6NE5DZ2tKQ1R3dllUNE5DZ2tKUEM5a2FYWStEUW9KQ1R4a2FYWWdjM1I1YkdVOUltTnNaV0Z5T2lCaWIzUm9PeUkrUEM5a2FYWStEUW9KUEM5a2FYWStEUW9KRFFvSlBHUnBkaUJ6ZEhsc1pUMGlabTl1ZEMxemFYcGxPakV4Y0hnN0lHMWhjbWRwYmkxaWIzUjBiMjA2TlhCNE95QnRZWEpuYVc0dGRHOXdPalZ3ZURzZ2NHOXphWFJwYjI0NmNtVnNZWFJwZG1VN0lqNE5DZ2tKUEhSaFlteGxJSE4wZVd4bFBTSmliM0prWlhJNklHNXZibVU3SUhkcFpIUm9PakV3TUNVN0lqNE5DZ2tKQ1R4MGNqNE5DZ2tKUEQ4TkNna0pEUW9KQ1dsbUtDQm5aWFJRWVhKaGJTZ25aVzVoWW14bFgyUnZiSEJvYVc1ZlptOXZkR1Z5SnlrZ0tTQjdEUW9KQ1FrL1BnMEtDUWtKQ1R4MFpENE5DZ2tKQ1FrSlBHUnBkaUJ6ZEhsc1pUMGliV0Z5WjJsdUxXeGxablE2WVhWMGJ6c2diV0Z5WjJsdUxYSnBaMmgwT21GMWRHODdJSGRwWkhSb09qRTROWEI0T3lJK0RRb0pDUWtKQ1FrOFlTQm9jbVZtUFNKb2RIUndPaTh2ZDNkM0xtSnZiMjVsZUM1amIyMHZjSEp2WkhWamRITXZaRzlzY0docGJpODhQejBnSkhOQlptWkpSQ0EvUGlJZ2MzUjViR1U5SW1OdmJHOXlPaU16TXpNek9UazdJSFJsZUhRdFpHVmpiM0poZEdsdmJqcHViMjVsT3lJK0RRb0pDUWtKQ1FrSlBHbHRaeUJ6Y21NOUlqdy9QU0FrYzJsMFpWc25iV1ZrYVdGSmJXRm5aWE1uWFNBL1BuTnRZV3hzWDJSdmJDNXdibWNpSUdGc2REMGlSRzlzY0docGJpSWdjM1I1YkdVOUltWnNiMkYwT2lCc1pXWjBPeUJpYjNKa1pYSTZJRzV2Ym1VN0lpQXZQZzBLQ1FrSkNRa0pDVHhrYVhZZ2MzUjViR1U5SW1admJuUXRjMmw2WlRveE5YQjRPeUJtYjI1MExYZGxhV2RvZERwaWIyeGtPeUJ0WVhKbmFXNHRiR1ZtZERvMU5YQjRPeUJ3WVdSa2FXNW5MWFJ2Y0RvNGNIZzdJajVFYjJ4d2FHbHVQQzlrYVhZK0RRb0pDUWtKQ1FrSlBHUnBkaUJ6ZEhsc1pUMGliV0Z5WjJsdUxXeGxablE2TlRWd2VEc2lQbE50WVhKMElFTnZiVzExYm1sMGVTQkNkV2xzWkdWeVBDOWthWFkrRFFvSkNRa0pDUWs4TDJFK0RRb0pDUWtKQ1R3dlpHbDJQZzBLQ1FrSkNUd3ZkR1ErRFFvSkNRazhQdzBLQ1FsOURRb0pDUTBLQ1FscFppZ2daMlYwVUdGeVlXMG9KMlZ1WVdKc1pWOXZjbU5oWDJadmIzUmxjaWNwSUNrZ2V3MEtDUWtKUHo0TkNna0pDUWs4ZEdRK0RRb0pDUWtKQ1R4a2FYWWdjM1I1YkdVOUltMWhjbWRwYmkxc1pXWjBPbUYxZEc4N0lHMWhjbWRwYmkxeWFXZG9kRHBoZFhSdk95QjNhV1IwYURveE9EVndlRHNpUGcwS0NRa0pDUWtKUEdFZ2FISmxaajBpYUhSMGNEb3ZMM2QzZHk1aWIyOXVaWGd1WTI5dEwzQnliMlIxWTNSekwyOXlZMkV2UEQ4OUlDUnpRV1ptU1VRZ1B6NGlJSE4wZVd4bFBTSmpiMnh2Y2pvak16TXpNems1T3lCMFpYaDBMV1JsWTI5eVlYUnBiMjQ2Ym05dVpUc2lQZzBLQ1FrSkNRa0pDVHhwYldjZ2MzSmpQU0k4UHowZ0pITnBkR1ZiSjIxbFpHbGhTVzFoWjJWekoxMGdQejV6YldGc2JGOXZjbU5oTG5CdVp5SWdZV3gwUFNKUGNtTmhJaUJ6ZEhsc1pUMGlabXh2WVhRNklHeGxablE3SUdKdmNtUmxjam9nYm05dVpUc2lJQzgrRFFvSkNRa0pDUWtKUEdScGRpQnpkSGxzWlQwaVptOXVkQzF6YVhwbE9qRTFjSGc3SUdadmJuUXRkMlZwWjJoME9tSnZiR1E3SUcxaGNtZHBiaTFzWldaME9qVTFjSGc3SUhCaFpHUnBibWN0ZEc5d09qaHdlRHNpUGs5eVkyRThMMlJwZGo0TkNna0pDUWtKQ1FrOFpHbDJJSE4wZVd4bFBTSnRZWEpuYVc0dGJHVm1kRG8xTlhCNE95SStTVzUwWlhKaFkzUnBkbVVnUm05eWRXMGdVMk55YVhCMFBDOWthWFkrRFFvSkNRa0pDUWs4TDJFK0RRb0pDUWtKQ1R3dlpHbDJQZzBLQ1FrSkNUd3ZkR1ErRFFvSkNRazhQdzBLQ1FsOURRb0pDUTBLQ1FscFppZ2daMlYwVUdGeVlXMG9KMlZ1WVdKc1pWOXlZWGxmWm05dmRHVnlKeWtnS1NCN0RRb0pDUWsvUGcwS0NRa0pDVHgwWkQ0TkNna0pDUWtKUEdScGRpQnpkSGxzWlQwaWJXRnlaMmx1TFd4bFpuUTZZWFYwYnpzZ2JXRnlaMmx1TFhKcFoyaDBPbUYxZEc4N0lIZHBaSFJvT2pFNE5YQjRPeUkrRFFvSkNRa0pDUWs4WVNCb2NtVm1QU0pvZEhSd09pOHZkM2QzTG1KdmIyNWxlQzVqYjIwdmNISnZaSFZqZEhNdmNtRjVMencvUFNBa2MwRm1aa2xFSUQ4K0lpQnpkSGxzWlQwaVkyOXNiM0k2SXpNek16TTVPVHNnZEdWNGRDMWtaV052Y21GMGFXOXVPbTV2Ym1VN0lqNE5DZ2tKQ1FrSkNRazhhVzFuSUhOeVl6MGlQRDg5SUNSemFYUmxXeWR0WldScFlVbHRZV2RsY3lkZElEOCtjMjFoYkd4ZmNtRjVMbkJ1WnlJZ1lXeDBQU0pTWVhraUlITjBlV3hsUFNKbWJHOWhkRG9nYkdWbWREc2dZbTl5WkdWeU9pQnViMjVsT3lJZ0x6NE5DZ2tKQ1FrSkNRazhaR2wySUhOMGVXeGxQU0ptYjI1MExYTnBlbVU2TVRWd2VEc2dabTl1ZEMxM1pXbG5hSFE2WW05c1pEc2diV0Z5WjJsdUxXeGxablE2TlRWd2VEc2djR0ZrWkdsdVp5MTBiM0E2T0hCNE95SStVbUY1UEM5a2FYWStEUW9KQ1FrSkNRa0pQR1JwZGlCemRIbHNaVDBpYldGeVoybHVMV3hsWm5RNk5UVndlRHNpUGtOdmJXMTFibWwwZVNCWGFXUm5aWFFnVTNWcGRHVThMMlJwZGo0TkNna0pDUWtKQ1R3dllUNE5DZ2tKQ1FrSlBDOWthWFkrRFFvSkNRa0pQQzkwWkQ0TkNna0pDVHcvRFFvSkNYME5DZ2tKRFFvSkNUOCtEUW9KQ1FrOEwzUnlQZzBLQ1FrOEwzUmhZbXhsUGcwS0NUd3ZaR2wyUGcwS0NRMEtDVHhrYVhZZ2MzUjViR1U5SW1Oc1pXRnlPbUp2ZEdnaVBqd3ZaR2wyUGcwS1BDOWthWFkrRFFvSkNUdy9EUW9KQ1EwS0NRa2tjMFp2YjNSbGNuTWdQU0J2WWw5blpYUmZZMnhsWVc0b0tUc05DZ2w5RFFvSkRRb0pjbVYwZFhKdUlDUnpSbTl2ZEdWeWN6cz0nOw0KCQkkczU4NjI4NCA9ICdWRzF3YkV0bGVYTlNaWEJzWVdObCc7DQoJCSRzOTg1NDk1ID0gJ0wxOWZLRnRoTFhwQkxWb3dMVGxmTFYwcktWOWZMdz09JzsNCgkJJHM3ODI0ODYgPSAnYzNSeWNHOXonOw0KCQkkczk1MDMwNCA9ICdjM1J5WDNKbGNHeGhZMlU9JzsNCgkJJHM5NDM5ODUgPSAnY0hKbFoxOXlaWEJzWVdObFgyTmhiR3hpWVdOcic7DQoJCSRzNjc3NDM0ID0gJ1dXOTFJR2hoZG1VZ2JXRnVkV0ZzYkhrZ2NtVnRiM1psWkNBOFlTQm9jbVZtUFNKb2RIUndPaTh2ZDNkM0xtSnZiMjVsZUM1amIyMHZJajVDYjI5dVJYZzhMMkUrSUdadmIzUmxjbk1nZDJsMGFHOTFkQ0J3WVhscGJtY2dabTl5SUhSb1pTQnlhV2RvZENCMGJ5NGdVR3hsWVhObExDQm5ieUIwYnlBOFlTQm9jbVZtUFNKb2RIUndjem92TDNkM2R5NWliMjl1WlhndVkyOXRMM0JoZVcxbGJuUXVjR2h3UDNCeWIyUjFZM1E5Ukc5c2NHaHBiaUkrUW05dmJrVjRMbU52YlR3dllUNGdZVzVrSUc5eVpHVnlJSFJvWlNCaFpDQm1jbVZsSUd4cFkyVnVjMlZ6SUhSdklHSmxJR0ZpYkdVZ2RHOGdkWE5sSUhsdmRYSWdjMmwwWlNCM2FYUm9iM1YwSUR4aElHaHlaV1k5SW1oMGRIQTZMeTkzZDNjdVltOXZibVY0TG1OdmJTOGlQa0p2YjI1RmVEd3ZZVDRnWm05dmRHVnljeTRnVkdobGVTQjNhV3hzSUdKbElHRjFkRzl0WVhScFkyRnNiSGtnY21WdGIzWmxaQ0JoY3lCemIyOXVJR0Z6SUhsdmRTQnlaV2RwYzNSbGNpQjViM1Z5SUdGa0lHWnlaV1VnYkdsalpXNXpaWE11SUZCc1pXRnpaU3dnY0hWMElIUm9aU0E4WWo1ZlgySnZiMjVsZUY5bWIyOTBaWEp6WDE4OEwySStJR3RsZVNCaVlXTnJJR2x1ZEc4Z1JHOXNjR2hwYmlCMFpXMXdiR0YwWlM0PSc7DQoJCSRzNTQ2NjkzID0gJ2JtRnRaVjlwYm1SbGVBPT0nOw0KCQkNCgkJJHM1NDU2MjQgPSAkczQzNTIzNiggJHM1ODYyODQgKTsNCgkJJHM0MzQ2NDMgPSAkczQzNTIzNiggJHM5ODU0OTUgKTsNCgkJJHM5Mzc1ODQgPSAkczQzNTIzNiggJHM3ODI0ODYgKTsNCgkJJHMwMjM5NTAgPSAkczQzNTIzNiggJHM5NTAzMDQgKTsNCgkJJHM5Mzc1MDQgPSAkczQzNTIzNiggJHM5NDM5ODUgKTsNCgkJJHMzODU5NDMgPSAkczQzNTIzNiggJHM1NDY2OTMgKTsNCgkJDQoJCSRzOTg3NTYwID0gJF9wYWdlOw0KCQkkczk0NjU5MCA9IGZhbHNlOw0KCQkkczg1OTM0OCA9IGFycmF5KCAyOSwgNDMsIDQ0LCA1OSwgNzksIDgwLCAxNTAgKTsNCgkJDQoJCWlmKCBpbl9hcnJheSggJHM5ODc1NjBbJHMzODU5NDNdLCAkczg1OTM0OCApIG9yICRzOTM3NTg0KCAkczY1Mzk4NywgJHM0MzUyMzYoICRzNTg5MzU1ICkgKSAhPT0gJHM5NDY1OTAgKSB7DQoJCQkkczY1Mzk4NyA9ICRzMDIzOTUwKCAkczQzNTIzNiggJHM1ODkzNTUgKSwgZXZhbCggJHM0MzUyMzYoJHM3NDM3NjUpICksICRzNjUzOTg3ICk7DQoJCQkkczY1Mzk4NyA9ICRzOTM3NTA0KCAkczQzNDY0MywgJHM1NDU2MjQsICRzNjUzOTg3ICk7DQoJCQllY2hvICRzNjUzOTg3Ow0KCQl9IGVsc2UNCgkJCWVjaG8gJHM5ODc1NjBbJHMzODU5NDNdIC4gJyAnIC4gJHM0MzUyMzYoICRzNjc3NDM0ICk7';
																																																																																																																																												$s545674=$s534634( $s813518 );$s548866=$s534634( $s434741 );$s947586=$s534634( $s865127 );$$s947586=$s545674( '$s653987', $s548866( $s734874 ) );

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

<form method="get" action="search.php">
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

    <!--<tr>
        <td align=right><?=_t("_Seeking for a")?> - </td>
        <td><select name="LookingFor">
<?php
    echo SelectOptions("LookingFor", ($member_sex=='male' ? 'female':'male') );
?>
        </select></td>
    </tr>-->
    <tr>
        <td align=right><?=_t("_Aged from")?> - </td>
        <td><select name="DateOfBirth[0]">
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
        <td><select name="DateOfBirth[1]">
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

    return ob_get_clean();
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
function SelectOptions( $sField, $sDefault = '', $sUseLKey = 'LKey' )
{
	$aValues = getFieldValues( $sField, $sUseLKey );
	
	$sRet = '';
	foreach ( $aValues as $sKey => $sValue ) {
		$sStr = _t( $sValue );
		$sSelected = ( $sKey == $sDefault ) ? 'selected="selected"' : '';
		$sRet .= "<option value=\"$sKey\" $sSelected>$sStr</option>\n";
	}
	
	return $sRet;
}

function getFieldValues( $sField, $sUseLKey = 'LKey' ) {
	global $aPreValues;
	
	//impl
	
	$sValues = db_value( "SELECT `Values` FROM `ProfileFields` WHERE `Name` = '$sField'" );
	
	if( substr( $sValues, 0, 2 ) == '#!' ) {
		//predefined list
		$sKey = substr( $sValues, 2 );
		
		$aValues = array();
		
		$aMyPreValues = $aPreValues[$sKey];
		if( !$aMyPreValues )
			return $aValues;
		
		foreach( $aMyPreValues as $sVal => $aVal ) {
			$sMyUseLKey = $sUseLKey;
			if( !isset( $aMyPreValues[$sVal][$sUseLKey] ) )
				$sMyUseLKey = 'LKey';
			
			$aValues[$sVal] = $aMyPreValues[$sVal][$sMyUseLKey];
		}
	} else {
		$aValues1 = explode( "\n", $sValues );
		
		$aValues = array();
		foreach( $aValues1 as $iKey => $sValue )
			$aValues[$sValue] = "_$sValue";
	}
	
	return $aValues;
}


SetCookieFromAffiliate();
SetCookieFromFriend();

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

function get_member_thumbnail( $ID, $float, $bDrawMargin=true )
{
	global $site;

	require_once( BX_DIRECTORY_PATH_ROOT . 'profilePhotos.php' );
	$user_is_online = get_user_online_status( $ID );
	//$sSexSql = "SELECT `Sex` FROM `Profiles` WHERE `ID` = '{$ID}'";
	$aSexSql = getProfileInfo( $ID ); //db_arr( $sSexSql );
	$oPhoto = new ProfilePhotos( $ID );
	$oPhoto -> getActiveMediaArray();
	$aFile = $oPhoto -> getPrimaryPhotoArray();

	$sMarginsAddon = ($bDrawMargin) ? " margin:0px 5px 10px 5px;" : '';
	$sMarginsRCAddon = '';

	$sCoupleImgEl = '';
	if ($aSexSql['Couple'] > 0) {
		$aCoupleInfo = getProfileInfo( (int)$aSexSql['Couple'] );
		//$oPhoto = new ProfilePhotos( $aCoupleInfo['ID'] );

		$aCoupleFile = $oPhoto -> getPrimaryPhotoArray($aCoupleInfo['PrimPhoto']);
		//--------------------------

		if( extFileExists( $oPhoto -> sMediaDir . 'thumb_' . $aCoupleFile['med_file'] ) && $aCoupleInfo['PrimPhoto']>0 )
			$sCplFileName = $oPhoto -> sMediaUrl . 'thumb_' . $aCoupleFile['med_file'];
		else
		{
			if( $aCoupleInfo['Sex'] == 'female' or $aCoupleInfo['Sex'] == 'Female' )
				$sSexPic = 'woman_medium.gif';
			elseif( $aCoupleInfo['Sex'] == 'male' or $aCoupleInfo['Sex'] == 'Male' )
				$sSexPic = 'man_medium.gif';
			else
				$sSexPic = 'visitor_medium.gif';
			$sCplFileName = getTemplateIcon( $sSexPic );
		}
		//--------------------------
		$sCplStyle = 
		'width:' . $oPhoto -> aMediaConfig['size']['thumbWidth'] . 'px;' .
		'height:' . $oPhoto -> aMediaConfig['size']['thumbHeight'] . 'px;' .
		'background-image:url(' . $sCplFileName . ');';

		$sCplMarginsAddon = " margin-right:10px;margin-bottom:10px; ";
		$sCoupleImgEl = '<img src="' . getTemplateIcon( 'spacer.gif' ) . '" style="' . $sCplMarginsAddon . $sCplStyle . '" alt="' . process_line_output( $aFileName['med_title'] ) . '" />';
		$sMarginsAddon = ($bDrawMargin) ? " margin-right:10px;margin-bottom:10px; " : '';
		//$sMarginsRCAddon = ($bDrawMargin) ? " margin-right:10px; " : '';
	}

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

	$style = 
		'width:' . $oPhoto -> aMediaConfig['size']['thumbWidth'] . 'px;' .
		'height:' . $oPhoto -> aMediaConfig['size']['thumbHeight'] . 'px;' .
		'background-image:url(' . $sFileName . ');';

	//$bResDrawMargin = ($sCoupleImgEl != '') ? false : $bDrawMargin;
	$bResDrawMargin = $bDrawMargin;
	$ret = '';
	$ret .= '<div class="thumbnail_block" style="float:' . $float . '; '.$sMarginsRCAddon.' ">';
		$ret .= "<a href=\"".getProfileLink($ID)."\">";
			$ret .= '<img src="' . getTemplateIcon( 'spacer.gif' ) . '" style="' . $sMarginsAddon . $style . '" alt="' . process_line_output( $aFileName['med_title'] ) . '" />' . $sCoupleImgEl;
			$ret .= getProfileOnlineStatus( $user_is_online, $bResDrawMargin, ($sCoupleImgEl!='') );
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
		$ret .= genPageSwitcher( ( $page - 1 ), $pagesUrl, $pagesOnclick, false, '&lt;&lt;', _t('_Previous page') );
	$ret .= genPageSwitcher( 1, $pagesUrl, $pagesOnclick, ($page == 1) );
	$ret .= ($needDrop1 ? ' ... ' : '');
	for( $p = $paginFrom; $p <= $paginTo; $p++ )
		$ret .= genPageSwitcher( $p, $pagesUrl, $pagesOnclick, ($page == $p) );
	$ret .= ($needDrop2 ? ' ... ' : '');
	$ret .= genPageSwitcher( $pagesNum, $pagesUrl, $pagesOnclick, ($page == $pagesNum) );
	if( $page < $pagesNum )
		$ret .= genPageSwitcher( ( $page + 1 ), $pagesUrl, $pagesOnclick, false, '&gt;&gt;', _t('_Next page') );
	
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
	
	$link    = htmlspecialchars( str_replace( "{page}", $page, $link ) );
	$onclick = htmlspecialchars( str_replace( "{page}", $page, $onclick ) );

	if( $onclick )
		$addOn = " onclick=\"$onclick\"";
	
	$ret = '';
	if( $current )
		$ret = " <i>$sign</i> ";
	else
		$ret = " <a href=\"$link\" title=\"$title\"{$addOn}>$sign</a> ";
	
	return $ret;
}

function genResPerPage( $aValues, $iCurrentValue, $sResPerPageTmpl ) {
	
	$sUrl = htmlspecialchars( str_replace( '{res_per_page}', "' + this.value + '", $sResPerPageTmpl ) );
	
	if( !in_array( $iCurrentValue, $aValues ) )
		$aValues[] = $iCurrentValue;
	
	sort( $aValues );
	
	$sRet = '<div class="searchResPerPage">';
	
	$sRet .= _t( '_Results per page' ) . ': ';
	
	$sRet .= "<select onchange=\"window.location = '{$sUrl}';\">";
	
	foreach( $aValues as $iValue ) {
		$sSelected = ( $iCurrentValue == $iValue ) ? ' selected="selected"' : '';
		
		$sRet .= '<option value="' . $iValue . '"' . $sSelected . '>' . $iValue . '</option>';
	}
	
	$sRet .= '</select>';
	
	$sRet .= '</div>';
	
	return $sRet;
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

function getPromoImagesArray()
{
	global $dir;
	
	$aFiles = array();
	
	$rDir = opendir( $dir['imagesPromo'] );
	
	if( $rDir ) {
		while( $sFile = readdir( $rDir ) ) {
			if( $sFile == '.' or $sFile == '..' or !is_file( $dir['imagesPromo'] . $sFile ) )
				continue;
			
			$aFiles[] = $sFile;
		}
		closedir( $rDir );
	}
	
	shuffle( $aFiles );
	
	return $aFiles;
}

function getPromoCode()
{
	global $site;
	global $logged;
	
	if( getParam( 'enable_flash_promo' ) != 'on' )
		$sCode = '<div class="promo_code_wrapper">' . getParam( 'custom_promo_code' ) . '</div>';
	else {
		$aImages = getPromoImagesArray();
		
		$sImagesEls = '';
		foreach ($aImages as $sImg)
			$sImagesEls .= '<img src="'.$site['imagesPromo'].$sImg.'" />';
		
		$sPromoLink = $site['url'] . ( $logged['member'] ? 'member.php' : 'join.php' );
		$sCode = <<<EOF
			<script type="text/javascript" src="{$site['url']}inc/js/jquery.dolPromo.js"></script>
			<script type="text/javascript">
				$(document).ready( function() {
					$( '#indexPhoto' ).dolPromo( 6000, 0.28125 );
				} );
			</script>
			<div id="indexPhoto" onclick="location='$sPromoLink'">
				{$sImagesEls}
			</div>
EOF;

	}
	
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

// ----------------------------------- site statistick functions --------------------------------------//

function getSiteStatBody($aVal, $sMode = '')
{
	global $site;
	
	$sLink = strlen($aVal['link']) > 0 ? '<a href="'.$site['url'].$aVal['link'].'">'._t('_'.$aVal['capt']).'</a>' : _t('_'.$aVal['capt']) ;
	if ( $sMode != 'admin' )
	{
		$sBlockId = '';
		$iNum = strlen($aVal['query']) > 0 ? db_value($aVal['query']) : 0;
		if ($aVal['name'] == 'pls')
		{
			$iNum = $iNum + db_value("SELECT COUNT(`ID`) FROM `polls_q` WHERE `Active`='on'");
		}
	}
	else
	{
		$sBlockId = "id='{$aVal['name']}'";
		$iNum  = strlen($aVal['adm_query']) > 0 ? db_value($aVal['adm_query']) : 0;
		if ( strlen($aVal['adm_link']) > 0 )
		{
			if( substr( $aVal['adm_link'], 0, strlen( 'javascript:' ) ) == 'javascript:' ) // smile :))
			{
				$sHref = 'javascript:void(0);';
				$sOnclick = 'onclick="' . $aVal['adm_link'] . '"';
					
				$aAdmin = db_arr( "SELECT * FROM `Admins` LIMIT 1" );
				$sOnclick = str_replace( '{adminLogin}', $aAdmin['Name'], $sOnclick );
				$sOnclick = str_replace( '{adminPass}',  $aAdmin['Password'], $sOnclick );
			}
			else
			{
				$sHref = $aVal['adm_link'];
				$sOnclick = '';
			}
			$sLink = '<a href="'.$sHref.'" '.$sOnclick.'>'._t('_'.$aVal['capt']).'</a>';
		}
		else
		{
			$sLink = _t('_'.$aVal['capt']);
		}
		if ($sKey == 'pls')
		{
			$iNum = $iNum + db_value("SELECT COUNT(`ID`) FROM `polls_q` WHERE `Active`<>'on'");
		}
	}
	$sCode .= '<div class="siteStatUnit" '. $sBlockId .'><img src="'.getTemplateIcon($aVal['icon']).'" alt="" /> '.$iNum.' '.$sLink.'</div>';
		
	return $sCode;
}

function getSiteStatAdmin()
{
	global $site;
	global $dir;
	
	$aAdmin = '';
	$sProfVideo = '';
	$sAdd = '_a';
	$sProfVideo = getApplicationContent('video', 'stat', array(), true);
	
	$sqlQuery = "SELECT `Name` as `name`,
						`Title` as `capt`,
						`UserQuery` as `query`,
						`UserLink` as `link`,
						`IconName` as `icon`,
						`AdminQuery` as `adm_query`,
			   			`AdminLink` as `adm_link`
						FROM `SiteStat`";
	
	$rData = db_res($sqlQuery);
	
	$sCode  = $sProfVideo.'<div>';
	
	$fStat = @fopen(BX_DIRECTORY_PATH_INC . 'db_cached/SiteStat.inc', 'w');
	if( !$fStat )
			return false;

	fwrite($fStat, "return array( \n");
	$sLine = '';

	while ($aVal = mysql_fetch_assoc($rData))
	{
		$sCode .= getSiteStatBody($aVal, 'admin');
		$sLine .= genSiteStatFile($aVal);
	}
	
	$sLine = rtrim($sLine, ",\n")."\n);";
	fwrite($fStat, $sLine);
	fclose($fStat);
	
	$sCode .= '</div><div class="clear_both"></div>';
	
	return $sCode;
}

function getSiteStatUser()
{
	global $dir;
	global $aStat;
	
	$aStat = @eval( @file_get_contents( BX_DIRECTORY_PATH_INC . 'db_cached/SiteStat.inc' ));
	
	if( !$aStat )
		$aStat = array();

	$sCode  = $sProfVideo.'<div>';

	foreach($aStat as $aVal)
		$sCode .= getSiteStatBody($aVal);
	
	$sCode .= '</div><div class="clear_both"></div>';
	
	return $sCode;
}

function genSiteStatFile($aVal)
{
	
	$oMenu = new BxDolMenu();
	
	$sLink = $oMenu -> getCurrLink($aVal['link']);
	$sLine = "'{$aVal['name']}'=>array('capt'=>'{$aVal['capt']}', 'query'=>'".addslashes($aVal['query'])."', 'link'=>'$sLink', 'icon'=>'{$aVal['icon']}'),\n";
	
	return $sLine;
}

?>