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
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// --------------- page variables and login

$_page['name_index'] 	= 58;
$_page['css_name']		= 'profile_customize.css';

$logged['member'] = member_auth();

if ( !getParam('enable_customization') )
{
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = '';
	PageCode();
	exit();
}

$ID = (int)$_COOKIE['memberID'];

// -------------------------------------------------------------------------------------
// save profile visualization settings
// -------------------------------------------------------------------------------------

    if ( $_POST['reset'] )
    {

			$query = "SELECT `BackgroundFilename` FROM `ProfilesSettings` WHERE `IDMember` = '$ID';";
			$custom_arr = db_arr( $query );
			if ( strlen($custom_arr['BackgroundFilename']) && file_exists($dir['profileBackground'] . $custom_arr['BackgroundFilename']) && is_file($dir['profileBackground'] . $custom_arr['BackgroundFilename']) )
			    @unlink($dir['profileBackground'] . $custom_arr['BackgroundFilename']);

			$query = "DELETE FROM `ProfilesSettings` WHERE `IDMember`='$ID' LIMIT 1";
			db_res( $query );

    }
    else if ( $_POST['save'] )
    {

	$query = "SELECT * FROM `ProfilesSettings` WHERE `IDMember` = '$ID';";
	$custom_arr = db_arr( $query );
	$record_created = $custom_arr['IDMember'] ? 'ok' : '';

// bg image ----------------------------------------------------------------------------
    if ( $_FILES['bgimg']['name'] && !$_POST['bgdel'] )
    {

	    if ( strlen($custom_arr['BackgroundFilename']) && file_exists($dir['profileBackground'] . $custom_arr['BackgroundFilename']) && is_file($dir['profileBackground'] . $custom_arr['BackgroundFilename']) )
	        @unlink($dir['profileBackground'] . $custom_arr['BackgroundFilename']);

	srand(time());
	$pic_name = $ID . '_bg_' . rand(100, 999);

			if ( !is_int($ext = moveUploadedImage( $_FILES, 'bgimg', $dir['profileBackground'] . $pic_name, '',false) ) )
			{
			    if ( !$record_created )
			    {
			        $query = "INSERT INTO ProfilesSettings (`IDMember`, `BackgroundFilename` ) VALUES ( '$ID', '$pic_name$ext' )";
					$record_created = 'ok';
			    }
			    else
			    {
			        $query = "UPDATE ProfilesSettings SET `BackgroundFilename` = '$pic_name$ext', `Status` = 'Approval' WHERE `IDMember` = '$ID'";
				}
			    $res = db_res( $query );

			}

    }
    else if ( $_POST['bgdel'] )
    {

        if ( $custom_arr['BackgroundFilename'] )
		{
	    	if (file_exists($dir['profileBackground'] . $custom_arr['BackgroundFilename'])) unlink($dir['profileBackground'] . $custom_arr['BackgroundFilename']);

			    $query = "UPDATE ProfilesSettings SET `BackgroundFilename` = '' WHERE `IDMember` = '$ID'";
			    $res = db_res( $query );
			}

    }


// bg color ----------------------------------------------------------------------------
    if ( $_POST['bgcolor'] && $_POST['bgcolor'] != $custom_arr['BackgroundColor'] )
    {
	if ( !$record_created )
	{
	    $query = "INSERT INTO ProfilesSettings (`IDMember`, `BackgroundColor` ) VALUES ( '$ID', '{$_POST['bgcolor']}' )";
	    $record_created = 'ok';
	}
	else
	    $query = "UPDATE ProfilesSettings SET `BackgroundColor` = '{$_POST['bgcolor']}' WHERE `IDMember` = '$ID'";

	$res = db_res( $query );
    }

// font color ----------------------------------------------------------------------------
    if ( $_POST['fontcolor'] && $_POST['fontcolor'] != $custom_arr['FontColor'] )
    {
	if ( !$record_created )
	{
	    $query = "INSERT INTO ProfilesSettings (`IDMember`, `FontColor` ) VALUES ( '$ID', '{$_POST['fontcolor']}' )";
	    $record_created = 'ok';
	}
	else
	    $query = "UPDATE ProfilesSettings SET `FontColor` = '{$_POST['fontcolor']}' WHERE `IDMember` = '$ID'";

	$res = db_res( $query );
    }

// font size ----------------------------------------------------------------------------
    if ( $_POST['fontsize'] && $_POST['fontsize'] != $custom_arr['FontSize'] )
    {
	if ( !$record_created )
	{
	    $query = "INSERT INTO ProfilesSettings (`IDMember`, `FontSize` ) VALUES ( '$ID', '{$_POST['fontsize']}' )";
	    $record_created = 'ok';
	}
	else
	    $query = "UPDATE ProfilesSettings SET `FontSize` = '{$_POST['fontsize']}' WHERE `IDMember` = '$ID'";

	$res = db_res( $query );
    }

// font family ----------------------------------------------------------------------------
    if ( $_POST['fontfamily'] && $_POST['fontfamily'] != $custom_arr['FontFamily'] )
    {
	if ( !$record_created )
	{
	    $query = "INSERT INTO ProfilesSettings (`IDMember`, `FontFamily` ) VALUES ( '$ID', '{$_POST['fontfamily']}' )";
	    $record_created = 'ok';
	}
	else
	    $query = "UPDATE ProfilesSettings SET `FontFamily` = '{$_POST['fontfamily']}' WHERE `IDMember` = '$ID'";

	$res = db_res( $query );
    }


    }
// -------------------------------------------------------------------------------------
// ============================================================================== end ==
// -------------------------------------------------------------------------------------

$_page['header'] = _t("_Customize");
$_page['header_text'] = _t("_Customize");

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
	global $ID;
	global $site;

	$query = "SELECT * FROM `ProfilesSettings` WHERE `IDMember` = '$ID'";
	$custom_arr = db_arr( $query );

	$bgimage = $custom_arr['BackgroundFilename'];
	$bgimage = $bgimage ? "<img src=\"{$site['profileBackground']}$bgimage\" alt=\"background\" width=\"110\" height=\"110\">" : '';

	$bgcolor = $custom_arr['BackgroundColor'] ? $custom_arr['BackgroundColor'] : '#FFFFFF';
	$bgcolorselect = makeColorSelect( 'bgcolor', $bgcolor );
	$bgcolor = $bgcolor ? "<div style=\"background: $bgcolor;width:25px;height:12px;border: solid 1px #000000;\">&nbsp;</div>" : "<div style=\"width:30px;height:12px;border: solid 1px #000000;\">none</div>";


	$fontcolor = $custom_arr['FontColor'];
	$fontcolor = $fontcolor ? "<div style=\"background: $fontcolor;width:25px;height:12px;border: solid 1px #000000;\">&nbsp;</div>" : "<div style=\"width:30px;height:12px;border: solid 1px #000000;\">none</div>";
	$fontcolorselect = makeColorSelect( 'fontcolor', $custom_arr['FontColor'] );

	$fontsize = $custom_arr['FontSize'] ? $custom_arr['FontSize'] : '11';
	$fontsizeselect = "<select id=\"fontsize\" name=\"fontsize\">";
	for ( $i = 8; $i<=16; $i++ )
	{
		$selected = $i == $fontsize ? 'selected="selected"' : '';
		$fontsizeselect .= "<option value=\"{$i}\" $selected style=\"font-size: {$i}px;font-weight: bold;\">{$i}px</option>";
	}
	$fontsizeselect .= '</select>';
	$fontsize = $fontsize ? "<font style=\"font-size: {$fontsize}px;\">{$fontsize}px</font>" : '';

	$fontfamily_arr[0] = 'Arial, Helvetica, sans-serif';
	$fontfamily_arr[1] = 'Times New Roman, Times, serif';
	$fontfamily_arr[2] = 'Courier New, Courier, monospace';
	$fontfamily_arr[3] = 'Georgia, Times New Roman, Times, serif';
	$fontfamily_arr[4] = 'Verdana, Arial, Helvetica, sans-serif';
	$fontfamily_arr[5] = 'Geneva, Arial, Helvetica, sans-serif';

	$fontfamily = $custom_arr['FontFamily'];
	$fontfamily = $fontfamily ? "<font style=\"font-family: {$fontfamily};\">{$fontfamily}</font>" : '';
	$fontfamilyselect = "<select id=\"fontfamily\" name=\"fontfamily\">";
	for ( $i = 0; $i<count($fontfamily_arr); $i++ )
	{
		$selected = $fontfamily_arr[$i] == $custom_arr['FontFamily'] ? 'selected="selected"' : '';
		$fontfamilyselect .= "<option value=\"{$fontfamily_arr[$i]}\" $selected style=\"font-family: {$fontfamily_arr[$i]};font-weight: bold;\">$fontfamily_arr[$i]</option>";
	}
	$fontfamilyselect .= '</select>';

	ob_start();

?>
	<div class="customize_content">
		<form id="cprofile" name="cprofile" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
			<input name="ID" value="<?= $ID ?>" type="hidden" />
			<div class="customize_header_box">
				<b><?= _t('_Customize Profile') ?>:</b>
			</div>

			<div id="profile_bg_color" class="customize_box">
				<span class="left_box"><b><?= _t('_Background color') ?></b><br /><?= $bgcolor ?></span>
				<span class="right_box"><?= $bgcolorselect ?></span>
				<div class="devider"></div>
			</div>

			<div id="profile_bg_image" class="customize_box_pic">
				<div><b><?= _t('_Background picture') ?></b><br /><?= $bgimage ?></div>
				<span class="right_box">
					<input class="no" id="bgimg" name="bgimg" value="upload" type="file" /><br />
					<input type="checkbox" id="bgdel" name="bgdel" /><label for="bgdel"><?= _t('_Delete') ?></label>
				</span>
			</div>

			<div id="profile_font_color" class="customize_box">
				<span class="left_box"><b><?= _t('_Font color') ?></b><br /><?= $fontcolor ?></span>
				<span class="right_box"><?= $fontcolorselect ?></span>
			</div>

			<div id="profile_font_size" class="customize_box">
				<span class="left_box"><b><?= _t('_Font size') ?></b><br /><?= $fontsize ?></span>
				<span class="right_box"><?= $fontsizeselect ?></span>
			</div>

			<div id="profile_font_family" class="customize_box">
				<span class="left_box"><b><?= _t('_Font family') ?></b><br /><?= $fontfamily ?></span>
				<span class="right_box"><?= $fontfamilyselect ?></span>
			</div>

			<div class="customize_footer_box">
				<input class="button" name="save" value="<?= _t('_Save Changes') ?>" type="submit" style="width: 100px;" />&nbsp;
				<input class="button" name="reset" value="<?= _t('_Reset') ?>" type="submit" style="width: 60px;" />
				<br /><br />
				<a href="<?=getProfileLink($ID); ?>" style="font-weight: bold;"><?= _t('_View profile') ?></a>
			</div>
		</form>
	</div>
<?

	$ret = ob_get_contents();
	ob_end_clean();

	return $ret;
}

function makeColorSelect( $name, $sel )
{
	$res = db_res( "SELECT `ColorName`, `ColorCode` FROM `ColorBase` ORDER BY `ColorCode`" );

	$ret = "<select id=\"$name\" name=\"$name\">\n";
	while ( $arr = mysql_fetch_assoc($res) )
	{
		$selected = ($sel == $arr['ColorCode'] ? 'selected="selected"' : '');
		$ret .= "<option value=\"{$arr['ColorCode']}\" $selected style=\"color: {$arr['ColorCode']}; font-weight: bold;\">{$arr['ColorName']}</option>\n";
	}
	$ret .= "</select>\n";

	return $ret;
}

?>