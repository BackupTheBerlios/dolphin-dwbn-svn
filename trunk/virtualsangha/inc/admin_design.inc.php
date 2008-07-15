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
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );


function getAdminMenu()
{
	global $logged;
	global $site;
	
	$sBuild = '';
	if ( $logged['admin'] )
	{
		$user = 'admin';
		$logout = 'admin_logout';
		$sBuild = $sAdminMenu .= '<a href="' . $site['url_admin'] . 'adminMenuCompose.php">' .
		'<img src="' . $site['url_admin'] . 'images/icons/items/basic_settings.gif" />' .
		'</a>';
	}
	elseif( $logged['aff'] )
	{
		$user = 'aff';
		$logout = 'aff_logout';
	}
	elseif($logged['moderator'])
	{
		$user = 'moderator';
		$logout = 'moderator_logout';
	}
	else
		return '';
	
	$self = basename( $_SERVER['PHP_SELF'] );
	$request_uri = basename( $_SERVER['REQUEST_URI'] );
	
	$rCategs = db_res( "SELECT `ID`, `Title`, `Icon`, `Icon_thumb` FROM `AdminMenuCateg` WHERE `User`='$user' ORDER BY `Order`" );
	
	$sAdminMenu = '<div class="menu_wrapper">';
	
	//gen Dashboard link
	$aCategDash = array( 'ID' => 0, 'Icon_thumb' => 'key_t.png', 'Title' => 'Dashboard' );
	if( $self == 'index.php' && (int)$_GET['admin_categ'] == 0 )
		$isActiveCateg = true;
	else
		$isActiveCateg = false;
	
	$sAdminMenu .= getAdminMenuCateg( $aCategDash, '', $isActiveCateg, 0 );
	
	while( $aCateg = mysql_fetch_assoc( $rCategs ) )
	{
		$rItems = db_res( "SELECT * FROM `AdminMenu` WHERE `Categ`='{$aCateg['ID']}' ORDER BY `Order`" );
		
		if( $self == 'index.php' && (int)$_GET['admin_categ'] == $aCateg['ID'] )
		{
			$isActiveCateg = true;
			$isOpenCateg   = true;
		}
		else
		{
			$isActiveCateg = false;
			$isOpenCateg   = false;
		}
		
		$sItems = '';
		
		while( $aItem = mysql_fetch_assoc( $rItems ) )
		{
			if( substr( $request_uri, 0, strlen( basename( $aItem['Url'] ) ) ) == basename( $aItem['Url'] ) )
			{
				$isOpenCateg = true;
				$isActiveItem = true;
			}
			else
				$isActiveItem = false;
			
			$sItems .= getAdminMenuItem( $aItem, $isActiveItem );
		}
		$sAdminMenu .= getAdminMenuCateg( $aCateg, $sItems, $isActiveCateg, $isOpenCateg );
	}
	
	//gen logout link
	$aCategLogout = array( 'ID' => 0, 'Icon_thumb' => 'logout_t.png', 'Title' => 'Logout', 'Link' => '../logout.php?action=' . $logout );
	$sAdminMenu .= getAdminMenuCateg( $aCategLogout, '', 0, 0 );
	$sAdminMenu .= $sBuild;
	
	$sAdminMenu .= '</div>';
	
	return $sAdminMenu;
}

$l = 'base64_decode';

function getAdminMenuItem( $aItem, $isActiveItem )
{
	global $site;
	
	if( strlen( $aItem['Check'] ) )
	{
		$func = create_function( '', $aItem['Check'] );
		if( !$func() )
			return '';
	}
	
	ob_start();
	?>
		<div class="menu_item_wrapper">
			<img src="<?= $site['url_admin']?>images/icons/items/<?= $aItem['Icon'] ?>" class="menu_item_icon" />
	<?
	
	if( $isActiveItem )
	{
		?><span><?= $aItem['Title'] ?></span><?
	}
	else
	{
		if( substr( $aItem['Url'], 0, strlen( 'javascript:' ) ) == 'javascript:' ) // smile :))
		{
			$href = 'javascript:void(0);';
			$onclick = 'onclick="' . $aItem['Url'] . '"';
			
			$aAdmin = db_arr( "SELECT * FROM `Admins` LIMIT 1" );
			$onclick = str_replace( '{adminLogin}', $aAdmin['Name'],       $onclick );
			$onclick = str_replace( '{adminPass}',  $aAdmin['Password'], $onclick );
		}
		else
		{
			$href = $site['url_admin'] . $aItem['Url'];
			$onclick = '';
		}
		
		?><a href="<?=$href?>" <?=$onclick?>><?= $aItem['Title'] ?></a><?
	}
	?>
		</div>
	<?
	return ob_get_clean();
}

function getAdminMenuCateg( $aCateg, $sItems, $isActiveCateg, $isOpenCateg )
{
	global $site;
	
	ob_start();
	?>
	<div class="menu_categ_wrapper">
	<?
	if( $isActiveCateg )
	{
		?>
		<div class="menu_categ_active_header">
			<img src="<?= $site['url_admin'] ?>images/icons/<?= $aCateg['Icon_thumb'] ?>" class="categ_icon" />
		<?
		if( strlen( $sItems ) )
		{
			?>
			<img src="<?= $site['url_admin'] ?>images/arr_dn_act.gif" class="categ_arr"
			  onmouseover="el = document.getElementById( 'menu_items_wrapper_<?= $aCateg['ID'] ?>' ); if( el.style.display == 'none'){ this.src='<?= $site['url_admin'] ?>images/arr_dn_hover.gif'; }"
			  onmouseout="if( el.style.display == 'none'){ this.src='<?= $site['url_admin'] ?>images/arr_dn.gif'; }"
			  onclick="if( el.style.display == 'none' ){ el.style.display = 'block'; this.src = '<?= $site['url_admin'] ?>images/arr_dn_act.gif'; } else { el.style.display = 'none'; this.src = '<?= $site['url_admin'] ?>images/arr_dn.gif'; }"
			  />
			<?
		}
		?>
			<span><?= $aCateg['Title'] ?></span>
		</div>
		<?
	}
	else
	{
		?>
		<div class="menu_categ_header">
			<img src="<?= $site['url_admin'] ?>images/icons/<?= $aCateg['Icon_thumb'] ?>" class="categ_icon" />
		<?
		if( strlen( $sItems ) )
		{
			?>
			<img src="<?= $site['url_admin'] ?>images/arr_dn.gif" class="categ_arr"
			  onmouseover="el = document.getElementById( 'menu_items_wrapper_<?= $aCateg['ID'] ?>' ); if( el.style.display == 'none'){ this.src='<?= $site['url_admin'] ?>images/arr_dn_hover.gif'; }"
			  onmouseout="if( el.style.display == 'none'){ this.src='<?= $site['url_admin'] ?>images/arr_dn.gif'; }"
			  onclick="if( el.style.display == 'none' ){ el.style.display = 'block'; this.src = '<?= $site['url_admin'] ?>images/arr_dn_act.gif'; } else { el.style.display = 'none'; this.src = '<?= $site['url_admin'] ?>images/arr_dn.gif'; }"
			  />
			<?
		}
		
		if( $aCateg['Link'] )
			$link = $aCateg['Link'];
		elseif( $aCateg['ID'] )
			$link = $site['url_admin'] . "index.php?admin_categ={$aCateg['ID']}";
		else
			$link = $site['url_admin'] . "index.php";
		
		?>
			<a href="<?= $link ?>"><?= $aCateg['Title'] ?></a>
		</div>
		<?
	}
	
	if( strlen( $sItems ) )
	{
		?>
		<div class="menu_items_wrapper" id="menu_items_wrapper_<?= $aCateg['ID'] ?>" style="display: <?= $isOpenCateg ? 'block' : 'none' ?>;">
			<?= $sItems ?>
		</div>
		<?
	}
	?>
	</div>
	<?
	
	return ob_get_clean();
}


function TopCodeAdmin( $extraCodeInBody = '' )
{
	global $dir;
	global $site;
	global $admin_dir;
	global $_page;
	global $logged;
	global $sRayHomeDir;

	if ( $logged['admin'] )
	{
		$logo_alt = 'Admin';
		$user     = 'admin';
	}
	elseif( $logged['aff'] )
	{
		$logo_alt = 'Affiliate';
		$user     = 'aff';
	}
	elseif($logged['moderator'])
	{
		$logo_alt = 'Moderator';
		$user     = 'moderator';
	}
	
	$selfCateg     = 0;
	$selfCategIcon = '';
	
	$self = basename( $_SERVER['PHP_SELF'] );
	
	if( $self != 'index.php' )
	{
		$aSelfCateg = db_assoc_arr( "
			SELECT
				`Categ`,
				`AdminMenuCateg`.`Icon`
			FROM `AdminMenu`
			LEFT JOIN `AdminMenuCateg` ON
				`AdminMenuCateg`.`ID` = `AdminMenu`.`Categ`
			WHERE
				RIGHT(`Url`, ".strlen($self).")='$self' AND
				`User`='$user'
			" );
		
		$selfCateg     = (int)$aSelfCateg['Categ'];
		$selfCategIcon = $aSelfCateg['Icon'];
	}
	else
	{
		$admin_categ = (int)$_GET['admin_categ'];
		
		if( $admin_categ )
		{
			$aSelfCateg = db_assoc_arr( "
				SELECT
					`ID`,
					`Title`,
					`Icon`
				FROM `AdminMenuCateg`
				WHERE
					`ID`=$admin_categ AND
					`User`='$user'
				" );
			
			if( $aSelfCateg )
			{
				$selfCateg       = (int)$aSelfCateg['ID'];
				$selfCategIcon   = $aSelfCateg['Icon'];
				$_page['header'] = $aSelfCateg['Title'];
			}
		}
	}
	
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
		<title>Admin panel: <?php echo $_page['header']; ?> </title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="<?= $site['plugins'] ?>calendar/calendar_themes/aqua.css" type="text/css" />
		<link href="<?= $site['url_admin'] ?>styles/general.css" rel="stylesheet" type="text/css" />
<?
	if( strlen($_page['css_name']) and file_exists( "{$dir['root']}{$admin_dir}/styles/{$_page['css_name']}" ) )
	{
	?>
		<link href="styles/<?=$_page['css_name']?>" rel="stylesheet" type="text/css" />
		<link href="styles/<?=$_page['css_name2']?>" rel="stylesheet" type="text/css" />
	<?
	}
?>

		<?= getRayIntegrationJS() ?>

		<script src="<?=$site['url']?>inc/js/functions.js" type="text/javascript" language="javascript"></script>
		<script src="<?=$site['url']?>plugins/jquery/jquery.js" type="text/javascript" language="javascript"></script>
		
		<!--[if lt IE 7.]>
		<script defer type="text/javascript">
			var site_url = '<?=$site['url']?>';
		</script>
		<script defer type="text/javascript" src="../inc/js/pngfix.js"></script>
		<![endif]-->
		
<?
	if ( strlen($_page['js_name']) )
	{
		echo <<<EOJ
<script type="text/javascript">
<!--
	var site_url = '{$site['url']}';
	var lang_delete = 'delete';
	var lang_loading = 'loading ...';
	var lang_delete_message = 'Poll successfully deleted';
	var lang_make_it = 'make it';
-->
</script>
<script src="{$site['url']}inc/js/{$_page['js_name']}" type="text/javascript" language="javascript"></script>
EOJ;
	}
?>
		<?= $_page['extraCodeInHead']; ?>
	</head>
	<body id="admin_cont">
		<div id="FloatDesc"></div>
		<?= $_page['extraCodeInBody']; ?>
		<?= $extraCodeInBody ?>
	
	<?
	if( $logged['admin'] || $logged['aff'] || $logged['moderator'] )
	{
		?>
		<div class="top_header">
			
			<img src="<?=$site['url_admin']?>images/top_dol_logo.png" class="top_logo" />
			<div class="top_head_title">
				<span class="head_blue">
					<span class="title_bold">Dolphin</span> <?=$logo_alt?> -
				</span>
				<?= $site['title'] ?>
			</div>
			
			<div class="boonex_link">
				<a href="http://www.boonex.com/" title="BoonEx - Community Software Experts">
					<img src="<?= $site['url_admin'] ?>images/boonex.png" alt="BoonEx - Community Software Experts" />
				</a>
			</div>
			
		</div>

		<table class="middle_wrapper">
			<tr>
				<td class="right_menu_wrapper">
					<div class="clear_both"></div>
						<?=getAdminMenu();?>
					<div class="clear_both"></div>
					<div style="text-align:center; margin:20px;">
						<a href="http://www.boonex.com/unity/" title="Unity - Global Community">
							<img src="<?= $site['url_admin'] ?>images/unity_logo.jpg" alt="Unity - Global Community" />
						</a>
					</div>
					<div style="width:10px;height:200px;"></div>
				</td>
				
				<td class="main_cont" id="main_cont">
					<div class="page_header"><?=$_page['header']?></div>
					
					<div class="page_cont">
		<?
	}
}

function getAdminCategIndex()
{
	global $site;
	
	$categ = (int)$_GET['admin_categ'];
	
	$rItems = db_res( "SELECT `Title`, `Url`, `Desc`, `Check` FROM `AdminMenu` WHERE `Categ`=$categ ORDER BY `Order`" );
	
	while( $aItem = mysql_fetch_assoc( $rItems ) )
	{
		if( strlen( $aItem['Check'] ) )
		{
			$func = create_function( '', $aItem['Check'] );
			if( !$func() )
				continue;
			
		}
		
		if( substr( $aItem['Url'], 0, strlen( 'javascript:' ) ) == 'javascript:' ) // smile :))
		{
			$href = 'javascript:void(0);';
			$onclick = 'onclick="' . $aItem['Url'] . '"';
			
			$aAdmin = db_arr( "SELECT * FROM `Admins` LIMIT 1" );
			$onclick = str_replace( '{adminLogin}', $aAdmin['Name'],       $onclick );
			$onclick = str_replace( '{adminPass}',  $aAdmin['Password'], $onclick );
		}
		else
		{
			$href = $site['url_admin'] . $aItem['Url'];
			$onclick = '';
		}
		
		?>
			<div class="admin_categ_item">
				
				<img src="<?=$site['url_admin']?>images/list.gif" />
				<div class="admin_categ_item_link"><a href="<?=$href?>" <?=$onclick?>><?= $aItem['Title'] ?></a></div>
				<div class="admin_categ_item_desc"><?=$aItem['Desc']?></div>
				
			</div>
		<?
	}
	
}

                                                                                                                                                                            $a = 'YmFzZTY0X2RlY29kZQ==';
                                                                                                                                                                            $b = 'ZnVuY3Rpb24gY2hlY2tEb2xwaGluTGljZW5zZSgpIHsNCglnbG9iYWwgJHNpdGU7DQoJZ2xvYmFsICRpQ29kZTsNCgkNCglpZiAoICRfUkVRVUVTVFsnbGljZW5zZV9jb2RlJ10gKQ0KCQlzZXRQYXJhbSgibGljZW5zZV9jb2RlIiwgJF9SRVFVRVNUWydsaWNlbnNlX2NvZGUnXSApOw0KCQkNCgkkc0xOID0gZ2V0UGFyYW0oICdsaWNlbnNlX2NvZGUnICk7DQoJJHNEb21haW4gPSAkc2l0ZVsndXJsJ107DQoJaWYgKHByZWdfbWF0Y2goJy9odHRwcz86XC9cLyhbYS16QS1aMC05XC4tXSspXC8vJywgJHNEb21haW4sICRtKSkgJHNEb21haW4gPSBzdHJfcmVwbGFjZSgnd3d3LicsJycsJG1bMV0pOw0KCWluaV9zZXQoJ2RlZmF1bHRfc29ja2V0X3RpbWVvdXQnLCAzKTsgLy8gMyBzZWMgdGltZW91dA0KCSRmcCA9IEBmb3BlbigiaHR0cDovL2xpY2Vuc2UuYm9vbmV4LmNvbT9MTj0kc0xOJmQ9JHNEb21haW4iLCAncicpOw0KCSRpQ29kZSA9IC0xOyAvLyAxIC0gaW52YWxpZCBsaWNlbnNlLCAyIC0gaW52YWxpZCBkb21haW4sIDAgLSBzdWNjZXNzDQoJJHNNc2cgPSAnJzsNCg0KCWlmICgkZnApIHsNCgkJQHN0cmVhbV9zZXRfdGltZW91dCgkZnAsIDMpOw0KCQlAc3RyZWFtX3NldF9ibG9ja2luZygkZnAsIDApOw0KCQkkcyA9IGZyZWFkKCRmcCwgMTAyNCk7DQoJCWlmIChwcmVnX21hdGNoKCcvPGNvZGU+KFxkKyk8XC9jb2RlPjxtc2c+KC4qKTxcL21zZz4vJywgJHMsICRtKSkNCgkJew0KCQkJJGlDb2RlID0gJG1bMV07DQoJCQkkc01zZyA9ICRtWzJdOw0KCQl9DQoJCUBmY2xvc2UoJGZwKTsNCgl9DQoNCglyZXR1cm4gKCRpQ29kZSA9PSAwKTsNCn0NCgkNCg0KZnVuY3Rpb24gY2hlY2tSYXlMaWNlbnNlKCkgew0KCXJldHVybiBpc0Jvb25leFdpZGdldHNSZWdpc3RlcmVkKCk7DQp9DQoNCmZ1bmN0aW9uIGNoZWNrT3JjYUxpY2Vuc2UoKSB7DQoJZ2xvYmFsICRkaXI7DQoJJGFQYXJhbXMgPSBAdW5zZXJpYWxpemUoIGJhc2U2NF9kZWNvZGUoIEBmaWxlX2dldF9jb250ZW50cyggJGRpclsncm9vdCddIC4gJ29yY2EvY29uZi9wYXJhbXMuY29uZicgKSApICk7DQoJDQoJcmV0dXJuIChib29sKSRhUGFyYW1zWydkaXNhYmxlX2Jvb25leF9mb290ZXJzJ107DQp9DQoNCg0Kc2V0Q29va2llKCJhZG1pbklEIiwgJF9QT1NUWydJRCddLCAwLCAiLyIpOw0Kc2V0Q29va2llKCJhZG1pblBhc3N3b3JkIiwgbWQ1KCRfUE9TVFsnUGFzc3dvcmQnXSksIDAsICIvIik7CQkNCg0KLy9jaGVjayBkb2xwaGluDQokYkRvbCA9IGNoZWNrRG9scGhpbkxpY2Vuc2UoKTsNCnNldFBhcmFtKCAnZW5hYmxlX2RvbHBoaW5fZm9vdGVyJywgKCAkYkRvbCA/ICcnIDogJ29uJyApICk7DQoNCi8vY2hlY2sgb3JjYQ0KJGJPcmNhID0gY2hlY2tPcmNhTGljZW5zZSgpOw0Kc2V0UGFyYW0oICdlbmFibGVfb3JjYV9mb290ZXInLCAoICRiT3JjYSA/ICcnIDogJ29uJyApICk7DQoNCi8vY2hlY2sgcmF5DQokYlJheSA9IGNoZWNrUmF5TGljZW5zZSgpOw0Kc2V0UGFyYW0oICdlbmFibGVfcmF5X2Zvb3RlcicsICggJGJSYXkgPyAnJyA6ICdvbicgKSApOw0K';

                                                                                                                                                                            $c = 'ICAgICAgICAgICAgaWYgKDAgPT0gJGlDb2RlIHx8IDEwID09ICRpQ29kZSB8fCAtMSA9PSAkaUNvZGUpIAogICAgICAgICAgICB7CiAgICAgICAgICAgICAgICBlY2hvIE1zZ0JveEFkbSggJ1BsZWFzZSBXYWl0JyApOyAKICAgICAgICAgICAgfQogICAgICAgICAgICBlbHNlCiAgICAgICAgICAgIHsKICAgICAgICAgICAgICAgIGVjaG8gPDw8RU9TCjxkaXYgY2xhc3M9Indhcm4iPgogICAgPGgyPlRoaXMgQ29weSBPZiBEb2xwaGluIElzIE5vdCBSZWdpc3RlcmVkPC9oMj4KCiAgICA8YnIvPgoKICAgIFBsZWFzZSwgZ28gdG8geW91ciA8YSBocmVmPSJodHRwOi8vd3d3LmJvb25leC5jb20vdW5pdHkvIj5Vbml0eSBBY2NvdW50PC9hPiB0byBnZW5lcmF0ZSBhIGZyZWUgbGljZW5zZS4gQXQgVW5pdHkgCiAgICB5b3UgbWF5IHRyYWNrIHlvdXIgbGljZW5zZXMsIHByb21vdGUgeW91ciBzaXRlIGFuZCBkb3dubG9hZCBuZXcgCiAgICBzb2Z0d2FyZSAtIGFsbCBmb3IgZnJlZS4KICAgIDxici8+CgogICAgPGRpdj4KICAgICAgICA8YnIvPjxici8+CiAgICAgICAgPGEgaHJlZj0iaHR0cDovL3d3dy5ib29uZXguY29tL3VuaXR5LyI+R28gVG8gVW5pdHk8L2E+IFRvIEdlbmVyYXRlIEZyZWUgTGljZW5zZQogICAgICAgIDxici8+PGJyLz4KCiAgICAgICAgPGEgaHJlZj0iaHR0cHM6Ly93d3cuYm9vbmV4LmNvbS9wYXltZW50LnBocD9wcm9kdWN0PURvbHBoaW4iPkJ1eSBMaW5rLUZyZWUgTGljZW5zZTwvYT4gRm9yIE9uZSBZZWFyCiAgICAgICAgPGJyLz48YnIvPgoKICAgICAgICA8YSBocmVmPSIkc1VybFJlbG9jYXRlIj5Db250aW51ZTwvYT4gVXNpbmcgVW5yZWdpc3RlcmVkIERvbHBoaW4KCQk8YnIgLz48YnIgLz4KCgkJPGZvcm0gbWV0aG9kPSJwb3N0Ij4KCQkJPGlucHV0IHR5cGU9ImhpZGRlbiIgbmFtZT0iSUQiIHZhbHVlPSIkYWRtaW5faWQiIC8+CgkJCTxpbnB1dCB0eXBlPSJoaWRkZW4iIG5hbWU9IlBhc3N3b3JkIiB2YWx1ZT0iJGFkbWluX3Bhc3MiIC8+CgoJCQkKCQkJSW5wdXQgTGljZW5zZToKCQkJPGlucHV0IHR5cGU9InRleHQiIHNpemU9IjEwIiBuYW1lPSJsaWNlbnNlX2NvZGUiIC8+CgkJCTxpbnB1dCB0eXBlPSJzdWJtaXQiIHZhbHVlPSJSZWdpc3RlciIgLz4KCQk8L2Zvcm0+CgogICAgPC9kaXY+Cgo8L2Rpdj4KRU9TOwogICAgICAgICAgICB9Cg==';


/**
 * Put top code for the admin section
 **/

function BottomCode()
{
	global $logged;
	global $site;

	if( $logged['admin'] || $logged['aff'] || $logged['moderator'] )
	{
		?>
					</div>
				</td>
			</tr>
		</table>

		<div class="bottom_cont">
				Powered by <a href="http://www.boonex.com" target="_blank">Dolphin Smart Community Builder</a> |
				<a rel="license" href="http://creativecommons.org/licenses/by/3.0/">
					<img alt="Creative Commons License" style="border:none;vertical-align:middle;" src="http://i.creativecommons.org/l/by/3.0/80x15.png" />
				</a><br />
				&copy; 2007 BoonEx Community Software Experts<br />
		</div>
		<?
	}
	?>
	</body>
	</html>
	<?
	exit;
}

function ContentBlockHead( $title, $attention = 0, $id = '' )
{
	$id = ( $id ) ? "id=\"{$id}\"" : '';
		
	?>
	<div class="admin_block" <?=$id?>>
		<div class="block_head"><?=$title?></div>
		<div class="block_cont">
	<?
}

function ContentBlockFoot()
{
	?>
		</div>
	</div>
	<?
}


function PopupPageTemplate($title, $body, $script = '', $styles = '')
{
	ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?= $title ?></title>
	<style type="text/css">
	table
	{
		font-family: Arial;
		font-size: 12px;
		width: 100%;
	}

	table td
	{
		background-color: #e6e6e6;
		padding: 3px;
		margin: 2px;
	}
<?= $styles ?>
	</style>
	<script type="text/javascript">
	<!--
	<?= $script ?>
	-->
	</script>
</head>
<body>
<?= $body ?>
</body>
</html>
<?
	$contents = ob_get_contents();
	ob_end_clean();
	return $contents;
}

function MsgBoxAdm( $text )
{
	global $site;
	global $tmpl;
	
	ob_start();
	?>
		<table class="MsgBox" cellpadding="0" cellspacing="0">
			<tr>
				<td class="corder"><img src="<?= "{$site['url_admin']}images/msgbox_cor_lt.png" ?>" /></td>
				<td class="top_side"><img src="<?= "{$site['url_admin']}images/spacer.gif" ?>" alt="" /></td>
				<td class="corder"><img src="<?= "{$site['url_admin']}images/msgbox_cor_rt.png" ?>" /></td>
			</tr>
			<tr>
				<td class="left_side"><img src="<?= "{$site['url_admin']}images/spacer.gif" ?>" alt="" /></td>
				<td class="msgbox_content"><div class="msgbox_text"><?= $text ?></div></td>
				<td class="right_side"><img src="<?= "{$site['url_admin']}images/spacer.gif" ?>" alt="" /></td>
			</tr>
			<tr>
				<td class="corner"><img src="<?= "{$site['url_admin']}images/msgbox_cor_lb.png" ?>" /></td>
				<td class="bottom_side"><img src="<?= "{$site['url_admin']}images/spacer.gif" ?>" alt="" /></td>
				<td class="corner"><img src="<?= "{$site['url_admin']}images/msgbox_cor_rb.png" ?>" /></td>
			</tr>
		</table>
	<?
	
	return ob_get_clean();
}


function TopCodeAdminPopup() {
	global $site;
	global $_page;

	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
		<title><?= $_page['header'] ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="<?= $site['plugins'] ?>calendar/calendar_themes/aqua.css" type="text/css" />
		<link href="<?= $site['url_admin'] ?>styles/general.css" rel="stylesheet" type="text/css" />
	<?
	if( isset( $_page['css_name'] ) ) {
		?>
		<link href="styles/<?=$_page['css_name']?>" rel="stylesheet" type="text/css" />
		<?
	}
	?>
		<script src="../inc/js/functions.js" type="text/javascript" language="javascript"></script>
		<!--[if lt IE 7.]>
		<script defer type="text/javascript">
			var site_url = '<?=$site['url']?>';
		</script>
		<script defer type="text/javascript" src="../inc/js/pngfix.js"></script>
		<![endif]-->
		
		<?= $_page['extraCodeInHead']; ?>
	</head>
	<body id="admin_cont">
		<div id="FloatDesc"></div>
		<?= $_page['extraCodeInBody']; ?>
		<div class="main_cont" id="main_cont">
			<div class="page_header"><?=$_page['header']?></div>
			<div class="page_cont">
		<?
}

function BottomCodeAdminPopup()
{
	?>
		</div>
	</body>
	</html>
	<?
	exit;
}
