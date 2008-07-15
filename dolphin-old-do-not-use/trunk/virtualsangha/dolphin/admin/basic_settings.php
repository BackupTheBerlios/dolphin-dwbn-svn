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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );

//$_page['header'] = "Main Logo";

$logged['admin'] = member_auth( 1, true, true );
$_page['header'] = 'Basic Settings';


$_page['extraCodeInHead'] = <<<EOJ
	<!-- tinyMCE gz -->
	<script type="text/javascript" src="{$site['plugins']}tiny_mce/tiny_mce_gzip.js"></script>
	<script type="text/javascript">
		tinyMCE_GZ.init({
			plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",
			themes : "simple,advanced",
			languages : "en",
			disk_cache : true,
			debug : false
		});
	</script>

	<script language="javascript" type="text/javascript">
		tinyMCE.init({
			mode : "textareas",
			theme : "advanced",
			
			editor_selector : "custom_promo_code",
			content_css : "{$site['plugins']}tiny_mce/dolphin.css",
			
			plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,visualchars,nonbreaking,xhtmlxtras",
			relative_urls : false,
			
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "forecolor,backcolor,|,bullist,numlist,|,outdent,indent,|,link,unlink,image,hr,|,sub,sup,|,insertdate,inserttime,|,styleprops",
			theme_advanced_buttons3 : "charmap,emotions,|,cite,abbr,acronym,attribs,|,preview,removeformat,|,code,help",
			theme_advanced_buttons4 : "table,row_props,cell_props,delete_col,delete_row,delete_table,col_after,col_before,row_after,row_before,row_after,row_before,split_cells,merge_cells",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "center",
			valid_elements : "*[*]"
		});
	</script>
EOJ;



TopCodeAdmin();
ContentBlockHead("Main Logo");
if( $_REQUEST['do_submit'] )
{
	if( !$_FILES['new_file'] or empty( $_FILES['new_file'] ) )
		echo 'File not uploaded';
	else
	{
		if( $_FILES['new_file']['error'] != 0 )
			echo 'File upload error';
		else
		{
			$aFileInfo = getimagesize( $_FILES['new_file']['tmp_name'] );
			if( !$aFileInfo )
				echo 'You uploaded not image file';
			else
			{
				$ext = false;
				switch( $aFileInfo['mime'] )
				{
					case 'image/jpeg': $ext = 'jpg'; break;
					case 'image/gif':  $ext = 'gif'; break;
					case 'image/png':  $ext = 'png'; break;
				}
				
				if( !$ext )
					echo 'You uploaded not JPEG, GIF or PNG file';
				else
				{
					echo 'Upload successful. ';
					setNewMainLogo( $_FILES['new_file']['tmp_name'], $ext );
				}
			}
		}
	}
	echo '<br /> (<a href="'.$_SERVER['PHP_SELF'].'">Back</a>)';
}
else
{
	
	?>
		<b>Current logo:</b><br />
		<?=getMainLogo()?>
	<?
	ContentBlockFoot();
	ContentBlockHead("Change Main Logo");
	?>
	<script>
		function checkLogoForm()
		{
			_form = document.forms.logoForm;
			
			if( _form.file.value == '' )
			{
				alert( 'Please select file' );
				return false;
			}
			
			if( _form.resize.checked )
			{
				width  = parseInt( _form.new_width.value );
				height = parseInt( _form.new_height.value );
				
				if( !( width > 0 && height > 0 ) )
				{
					alert( 'Please enter correct sizes' )
					return false;
				}
			}
			
			_form.do_submit.value = 'Wait...';
			_form.do_submit.disabled = true;
			
			return true;
			
		}
	</script>
	<div style="width: 275px; margin: 0px auto;">
	<div style="border:0px solid red;width:275px;">
		Here you can change the main logo of your site<br />
		<form name="logoForm" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onsubmit="return checkLogoForm();">
			Select file:
			<input type="file" name="new_file" /><br />
			<b>Note:</b> File must be in JPEG, GIF or PNG format<br /><br />
			<fieldset style="width:200px;">
				<legend style="line-height:15px;">
					<input type="checkbox" name="resize" value="yes" checked="checked" style="vertical-align:middle;" />
					Resize
				</legend>
				New size:
				<input type="text" value="64" name="new_width" style="width:30px;" />
				x
				<input type="text" value="64" name="new_height" style="width:30px;" />px<br />
				<b>Note:</b> Resize is proportional
			</fieldset>
			<div style="text-align:center;margin-top:5px;">
				<input type="submit" name="do_submit" value="Upload" />
			</div>
		</form>
	</div>
	</div>
	<?
}
ContentBlockFoot();

ContentBlockHead("Promo block");

if( $_GET['delete'] and strlen( $_GET['delete'] ) )
{
	$file = process_pass_data( $_GET['delete'] );
	
	//secure deleting
	$file = str_replace( '\\', '', $file );
	$file = str_replace( '/', '', $file );
	
	$res = @unlink( $dir['imagesPromo'] . $file );
	
	if( !$res )
		echo 'Couldn\'t delete file. <br />';
}

if( $_REQUEST['prDoSubmit'] )
{
	if( !$_FILES['prNewFile'] or empty( $_FILES['prNewFile'] ) )
		echo 'File not uploaded';
	else
	{
		if( $_FILES['prNewFile']['error'] != 0 )
			echo 'File upload error';
		else
		{
			$aFileInfo = getimagesize( $_FILES['prNewFile']['tmp_name'] );
			if( !$aFileInfo )
				echo 'You uploaded not image file';
			else
			{
				$ext = false;
				switch( $aFileInfo['mime'] )
				{
					case 'image/jpeg': $ext = 'jpg'; break;
					case 'image/gif':  $ext = 'gif'; break;
					case 'image/png':  $ext = 'png'; break;
					default:           $ext = false;
				}
				
				if( !$ext )
					echo 'You uploaded not JPEG, GIF or PNG file';
				else
				{
					echo 'Upload successful. ';
					
					$newFileName = $dir['imagesPromo'] . 'original/' . $_FILES['prNewFile']['name'];
					
					if( !move_uploaded_file( $_FILES['prNewFile']['tmp_name'], $newFileName ) )
						echo 'Couldn\'t move file.';
					else
					{
						ResizeAnyPromo($newFileName, $dir['imagesPromo'] . $_FILES['prNewFile']['name']);
						/*if( $_REQUEST['prResize'] )
						{
							$width  = (int)$_REQUEST['prNewWidth'];
							$height = (int)$_REQUEST['prNewHeight'];
							if( !( $width > 0 and $height > 0 ) )
								echo 'You entered incorrect sizes';
							else
							{
								if( imageResize( $newFileName, $newFileName, $width, $height ) != IMAGE_ERROR_SUCCESS )
									echo 'Resize failed';
								else
									echo 'Resize successful';
							}
						}*/
					}
				}
			}
		}
	}
	echo '<br /> (<a href="'.$_SERVER['PHP_SELF'].'">Back</a>)';
}
else
{
	if( $_POST['doSavePromoSett'] )
	{
		setParam( 'enable_flash_promo', $_POST['enable_flash_promo'] );
		setParam( 'custom_promo_code',  $_POST['custom_promo_code'] );
		echo '<div style="margin:5px;text-align:center;color:green;">Settings saved</div>';
	}
	
	?>
	<script>
		function checkPromoForm()
		{
			_form = document.forms.promoForm;
			
			if( _form.file.value == '' )
			{
				alert( 'Please select file' );
				return false;
			}
			
			if( _form.resize.checked )
			{
				width  = parseInt( _form.new_width.value );
				height = parseInt( _form.new_height.value );
				
				if( !( width > 0 && height > 0 ) )
				{
					alert( 'Please enter correct sizes' )
					return false;
				}
			}
			
			_form.do_submit.value = 'Wait...';
			_form.do_submit.disabled = true;
			
			return true;
			
		}
		
		function ask()
		{
			return confirm( 'Are you sure want to delete this image?' );
		}
	</script>
	<style>
		div#FloatDesc
		{
			width:auto;
		}
	</style>
	
	<form name="promoForm" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onsubmit="return checkPromoForm();">
		<fieldset>
			<legend style="line-height:15px;">
				<input type="radio" name="enable_flash_promo" id="enable_flash_promo" value="on"
				  <?= ( getParam( 'enable_flash_promo' ) == 'on' ) ? 'checked="checked"' : '' ?>
				  />
				<label for="enable_flash_promo">Use Promo Flash Reloader</label>
			</legend>
			
			<b>Current Images</b>
			<?= getCurrentPromoImages() ?>
			<br />
			
			<div style="width: 350px;">
				<b>Here you can upload new images</b><br /><br />
					Select file:
					<input type="file" name="prNewFile" /><br />
					<b>Note:</b> File must be in JPEG, GIF or PNG format<br /><br />
					<!-- <fieldset style="width:280px;">
						<legend style="line-height:15px;">
							<input type="checkbox" name="prResize" id="prResize" value="yes" checked="checked" style="vertical-align:middle;" />
							<label for="prResize">Resize</label>
						</legend>
						New size:
						<input type="text" value="754" name="prNewWidth" style="width:30px;" />
						x
						<input type="text" value="207" name="prNewHeight" style="width:30px;" />px<br />
						<b>Note:</b> Resize is proportional
					</fieldset> -->
					<div style="text-align:center;margin-top:5px;">
						<input type="submit" name="prDoSubmit" value="Upload" />
					</div>
			</div>
		</fieldset>
		
		<fieldset>
			<legend style="line-height:15px;">
				<input type="radio" name="enable_flash_promo" id="disable_flash_promo" value=""
				  <?= ( getParam( 'enable_flash_promo' ) == 'on' ) ? '' : 'checked="checked"' ?>
				  />
				<label for="disable_flash_promo">Use custom HTML block</label>
			</legend>
			
			<textarea style="width:100%;height:300px;" name="custom_promo_code" class="custom_promo_code"><?= htmlspecialchars_adv( getParam( 'custom_promo_code' ) ) ?></textarea>
		</fieldset>
		
		<div style="text-align:center;margin-top:10px;">
			<input type="submit" name="doSavePromoSett" value="Save" />
		</div>
	</form>
	<?
}
ContentBlockFoot();

$saveSettings = ('yes' == $_POST['save_settings']);

if (FALSE != $saveSettings)
{
        saveIndexPageSettings();
}

ContentBlockHead("Index page settings");
displayIndexPageSettings();
ContentBlockFoot();

BottomCode();

function setNewMainLogo( $filename, $ext )
{
	global $dir;
	
	$newFileName = "{$dir['mediaImages']}logo_tmp.$ext";
	$sLogoName   = "{$dir['mediaImages']}logo.$ext";
	
	if( !move_uploaded_file( $filename, $newFileName ) )
		echo 'Couldn\'t move file.';
	else
	{
		if( $_REQUEST['resize'] )
		{
			$width  = (int)$_REQUEST['new_width'];
			$height = (int)$_REQUEST['new_height'];
			if( !( $width > 0 and $height > 0 ) )
				echo 'You entered incorrect sizes';
			else
			{
				if( imageResize( $newFileName, $newFileName, $width, $height ) != IMAGE_ERROR_SUCCESS )
					echo 'Resize failed';
				else
				{
					echo 'Resize successful';
					doReplaceLogo( $newFileName, $sLogoName );
				}
			}
		}
		else
			doReplaceLogo( $newFileName, $sLogoName );
	}
}

function doReplaceLogo( $tmpFile, $newFile )					
{
	global $dir;
	
	@unlink( "{$dir['mediaImages']}logo.jpg" );
	@unlink( "{$dir['mediaImages']}logo.png" );
	@unlink( "{$dir['mediaImages']}logo.gif" );
	
	rename( $tmpFile, $newFile );
}

function getCurrentPromoImages()
{
	global $site;
	?>
	<div style="margin-left:12px;">
	<?
	
	$aFiles = getPromoImagesArray();
	if( $aFiles )
	{
		foreach( $aFiles as $sFile )
		{
			?>
			<a href="javascript:void(0);"
			  onmouseover="showFloatDesc('<img src=&quot;<?= $site['imagesPromo'] . $sFile ?>&quot; />');"
			  onmousemove="moveFloatDesc( event);"
			  onmouseout="hideFloatDesc();"
			  ><?= $sFile ?></a>
			(<a href="<?= $_SERVER['PHP_SELF'] ?>?delete=<?= urlencode( $sFile ) ?>" style="color:red;" onclick="return ask();">delete</a>)
			<br />
			<?
		}
	}
	else
	{
		echo 'No images found';
	}
	?>
	</div>
	<?
}

function displayIndexPageSettings()
{
	global $site;

    ?>
    <center>
	<script type="text/javascript">
	<!--
		function changeFlag(flagISO)
		{
			flagImage = document.getElementById('flagImageId');
			flagImage.src = '<?= $site['flags'] ?>' + flagISO.toLowerCase() + '.gif';
		}
	-->
	</script>
    <form method="post" action="<? echo $_SERVER[PHP_SELF]; ?>">
    <input type="hidden" name="save_settings" value="yes">
    <table width="100%" cellspacing="2" cellpadding="3" class="text">
        <!-- <tr class="panel">
            <td colspan="2">&nbsp;<b>Index Page</b></td>
        </tr>-->
        <tr class="table">
            <td align="right" width="40%"> <?=getParamDesc("default_country") ?>: </td>
            <td align="left">
                <select name="default_country" onchange="javascript: changeFlag(this.value);" >
                    <?php
                    global $aPreValues;
                    $sCurCountry = getParam('default_country');
                    $aCountries = $aPreValues['Country'];
					
                    foreach ($aCountries as $sKey => $aCountry) {
						$sSelected = ( $sKey == $sCurCountry ) ? 'selected="selected"' : '';
                        ?>
							<option value="<?=$sKey?>" <?=$sSelected?>><?=_t($aCountry['LKey'])?></option>
                        <?
                    }
                    ?>
                </select>
                &nbsp;<img id="flagImageId" src="<?= $site['flags'].strtolower($sCurCountry) ?>.gif" alt="flag" />
            </td>
        </tr>
        <tr>
            <td align="right" width="40%"> <?=getParamDesc("top_members_mode") ?>: </td>
            <td align="left">
                <select name="top_members_mode">
                    <?php
                    $old_val = getParam('top_members_mode');
                    $mode_choices = array(
                        'online' => 'Online members',
                        'rand' => 'Random members',
                        'last' => 'Latest members',
                        'top' => 'Top members');
                    foreach ($mode_choices as $key => $value)
                    {
                        if ($old_val == $key)
                        {
                            echo "<option value=\"$key\" selected>$value</option>\n";
                        }
                        else
                        {
                            echo "<option value=\"$key\">$value</option>\n";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
        	<td align="right" width="40%"> <?=getParamDesc("featured_mode") ?>: </td>
            <td align="left">
                <select name="featured_mode">
                    <?php
                    $old_val = getParam('featured_mode');
                    $mode_choices = array(
                        'vertical' => 'Vertical',
                        'horizontal' => 'Horizontal');
                    foreach ($mode_choices as $key => $value)
                    {
                        if ($old_val == $key)
                        {
                            echo "<option value=\"$key\" selected>$value</option>\n";
                        }
                        else
                        {
                            echo "<option value=\"$key\">$value</option>\n";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
        	<td align="right" width="40%"> <?=getParamDesc("featured_num") ?>: </td>
            <td align="left">
            	<input type="text" class="no" name="featured_num" size="15" value="<?= htmlspecialchars(getParam('featured_num')) ?>" />
            </td>
        </tr>
		<tr>
        	<td align="right" width="40%"> <?=getParamDesc("top_members_max_num") ?>: </td>
            <td align="left">
            	<input type="text" class="no" name="top_members_max_num" size="15" value="<?= htmlspecialchars(getParam('top_members_max_num')) ?>" />
            </td>
        </tr>
    </table>
    <br>
    <input class="no" type="submit" value="Save changes">
    </form>
    </center>
    <?php
	
	return 'Index Page';
}

function saveIndexPageSettings()
{
    setParam('default_country', $_POST['default_country']);
    setParam('top_members_mode', $_POST['top_members_mode']);
    setParam('featured_mode', $_POST['featured_mode']);
    setParam('featured_num', $_POST['featured_num']);
	setParam('top_members_max_num', $_POST['top_members_max_num']);
    ?>
    <div class="succ">Index page parameters successfully changed.</div><br />
    <?php
}

?>
	</div>
	<?
?>