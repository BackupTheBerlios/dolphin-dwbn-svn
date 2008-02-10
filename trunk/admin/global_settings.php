<?php

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

/*
 * Page for displaying and editing Global Settings in admin panel.
 */
require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );

// Check if administrator is logged in.  If not display login form.
$logged['admin'] = member_auth(1);

$_page['css_name'] = 'global_settings.css';

// Get category.

if( $_GET['cat'] )
{
	$cat = $_GET['cat'];
	if ( is_numeric($cat) && (int)$cat <= 0 )
	{
		$cat = '';
	}
}
else
{
	$cat = '';
}

if ($cat=='1' || $cat == '16')
{
	$_page['header'] = 'Advanced Settings';
}
else 
{
	$_page['header'] = 'Settings';
}

// Constants.
define('EMAIL_TEMPLATE_CATEGORY', 4);
define('WATERMARK_CATEGORY', 16);

define('E_INVALID_OLD_PASSWORD', 1);
define('E_PASSWORD_CONFIRMATION_FAILED', 2);
define('E_INVALID_PASSWORD_LENGTH', 3);
define('S_OK', 0);
define('E_INVALID_PARAMETER', 1);

TopCodeAdmin();

// DISPLAY GLOBAL SETTINGS MENU
/*ContentBlockHead("Global Settings Menu");
display_menu($cat);
ContentBlockFoot();*/

ob_start();

// SAVE CHANGES
$save_settings = ('yes' == $_POST['save_settings']);
if (strlen($cat) > 0)
{
    // Admin password was changed.  Try to save it.
    if (FALSE != $save_settings && 'ap' == $cat)
    {
    	if ( $demo_mode )
    	{
    		echo '<span class="succ">Password can\'t be changed.</span><br />';
    	}
    	else
    	{
    		$result = save_admin_password($_COOKIE['adminID'], $_POST['pwd_old'], $_POST['pwd_new'], $_POST['pwd_new_confirm']);
	        switch ($result)
	        {
	            case S_OK:
	            {
	                // No error.  Display message.
	                echo '<span class="succ">Password changed.</span><br />';
	            }
	            break;
	            case E_INVALID_OLD_PASSWORD:
	            {
	                echo '<div class="err">Error saving new password: wrong old password!</div><br>';
	            }
	            break;
	            case E_INVALID_PASSWORD_LENGTH:
	            {
	                echo '<div class="err">Error saving new password: invalid password length (between 3 and 11 characters).</div><br>';
	            }
	            break;
	            case E_PASSWORD_CONFIRMATION_FAILED:
	            {
	                echo '<div class="err">Error saving new passowrd: password not confirmed.</div><br>';
	            }
	        }
    	}
    }
    // Language settings changed.  Try to save them.
    else if (FALSE != $save_settings && 'ls' == $cat)
    {
        save_language_settings();
    }
    else if (FALSE != $save_settings && 'ip' == $cat)
    {
        save_index_page_settings();
    }
    else if (FALSE != $save_settings && WATERMARK_CATEGORY == $cat)
    {
        save_water_mark();
    }
    else if (FALSE != $save_settings && 'all' == $cat)
    {
        save_settings_all();
        // Check $errors array.  If it's not empty display error messages,
        // otherwise display success message.
        if (count($errors) > 0)
        {
            echo get_error_messages();
        }
        else
        {
            echo '<div class="succ">Settings saved.</div><br />';
        }
    }
    else if (FALSE != $save_settings)
    {
   		switch ($cat)
       	{
       		case '1' : 
       		case '16': $aCat = array('16','1','23','3','12','15'); break;
       		case '4' : $aCat = array('8','17','4'); break;
       		default  : $aCat = array($cat);
       	}
   		foreach ($aCat as $i => $sKey)
		{
			if ( WATERMARK_CATEGORY == $sKey )
   			{
				save_water_mark();
   			}
			else
			{
				save_settings($sKey);
			}	
		}
        // Check $errors array.  If it's not empty display error messages,
        // Otherwise display success message.
        if (count($errors) > 0)
        {
            echo get_error_messages();
        }
        else
        {
            echo '<div class="succ">Settings saved.</div><br />';
        }
    }
}

// Display forms and controls for editing settings.
if ( strlen($cat) > 0)
{
    if ( 'ap' == $cat )
        $pageHeader = display_admin_password();
    elseif ('ls' == $cat)
        $pageHeader = display_language_settings();
    elseif ('ip' == $cat)
        $pageHeader = display_index_page_settings();
    elseif ('all' == $cat)
    {
		$pageHeader = 'All settings';
		
        display_admin_password();
        display_language_settings();
        display_index_page_settings();
        display_watermark_settings();
        ?>
        <form method="post" action="<? echo $_SERVER['PHP_SELF']."?cat=all"; ?>">
        <input type="hidden" name="save_settings" value="yes">
        <?php
        // Get a list of categories.
        $q_str = 'SELECT `ID` FROM `GlParamsKateg` WHERE `ID` <> '. WATERMARK_CATEGORY;
        $res = db_res($q_str);
        // Iterate thru categories.
        while ($row = mysql_fetch_array($res))
        {
            display_category_settings($row['ID']);
        }
        ?>
        <center><input type="submit" value="Save Changes" class="text"></center>
        </form>
        <?php
    }
    else
    {?>
        <form method="post" action="<? echo $_SERVER[PHP_SELF]."?cat=$cat"; ?>">
        <input type="hidden" name="save_settings" value="yes">
        <?php
			switch($cat)
          	{
        		case '1':
        		case '16': $aCat = array('1','23','16','3','12','15'); break;
        		case '4' : $aCat = array('8','17','4'); break;
        		default  : $aCat = array($cat);
        	}
       		foreach ($aCat as $i => $sKey)
   			{
   				if (WATERMARK_CATEGORY == $sKey)
   				{
   					continue;
   				}
   				else
   				{
   					$pageHeader .= display_category_settings($sKey);
   				}
   			}

        ?>
        <center><input type="submit" value="Save Changes" class="text"></center>
        </form>
        <?
        if ('1' == $cat || '16' == $cat)
   		{
   			$pageHeader .= display_watermark_settings();
   		}
    }
}

$mainContent = ob_get_clean();

ContentBlockHead( $pageHeader );
	echo $mainContent;
ContentBlockFoot();


BottomCode();
// END OF DOCUMENT

// UTILITY FUNCTIONS SECTION

/**
 * Display admin global settings menu.
 * @param $cat -- Currently displayed category.
 */
function display_menu($cat)
{
    // Get a list of global setting categories and generate a menu list.
    $q_str = 'SELECT `ID`, `name` FROM `GlParamsKateg` ORDER BY `menu_order` ASC;';
    $res = mysql_query($q_str);
    $menu = '<div class="gs_menu_outer">';
    $menu .= '<div class="gs_menu_inner">';
    $menu .= '<div class="gs_menu"><ul>';
    // Add two menu items manually because they don't exist in database.
    // They are 'Change admin password', 'Change language settings', and 'Index Page'.
    $custom = array (
        'ap' => 'Change admin password',
        'ls' => 'Change language settings',
        'ip' => 'Index Page');
    foreach ($custom as $key => $value)
    {
        if ($cat != $key)
        {
            $href = "{$_SERVER['PHP_SELF']}?cat=$key";
            $menu .= "<li><a href=\"$href\">$value</a></li>\n";
        }
        else
        {
            $menu .= "<li>$value</li>";
        }
    }
    // Get number of items.
    $num_items = mysql_num_rows($res) + 5;

    $num_cols = 3;
    $items_per_col = floor($num_items / $num_cols);
    $i = 3;
    // Iterate thru result recordset and add items to the menu.
    while ($row = mysql_fetch_assoc($res))
    {
        if ($i == $items_per_col)
        {
            $i = 0;
            $menu .= '</ul></div>'; // Finish item group.
            $menu .= '<div class="gs_menu"><ul>'; // Start new item group.
        }
        if ($cat != $row['ID'])
        {
            $href = "$_SERVER[PHP_SELF]?cat=$row[ID]";
            $menu .= "<li><a href=\"$href\">$row[name]</a></li>\n";
        }
        else
        {
            $menu .= "<li>$row[name]</li>";
        }
        $i++;
    }
    // Add "View all" item.
    if ('all' == $cat)
    {
        $menu .= '<li>View all</li>';
    }
    else
    {
        $menu .= "<li><a href=\"$_SERVER[PHP_SELF]?cat=all\" style=\"color:orange\">View all</a></li>";
    }
    $menu .= '</ul></div></div></div>';

    echo $menu;

}

/**
 * Display form and conrols for editing admin password.
 */
function display_admin_password()
{
    ?>
    <center>
    <form method="post" action="<? echo $_SERVER[PHP_SELF].'?cat=ap'; ?>">
    <input type="hidden" name="save_settings" value="yes">
    <table width="100%" cellspacing="2" cellpadding="3" class="text">
        <!--<tr class="panel">
            <td colspan="2"><b>Change administrator password</b></td>
        </tr>-->
        <tr>
            <td align="right" width="50%"> Old Password: </td>
            <td align="left" width="50%"><input type="password" size="14" name="pwd_old"></td>
        </tr>
        <tr>
            <td align="right" width="50%"> New Password: </td>
            <td align="left"><input type="password" size="14" name="pwd_new"></td>
        </tr>
        <tr>
            <td align="right" width="50%"> Confirm New Password: </td>
            <td align="left"><input type="password" size="14" name="pwd_new_confirm"></td>
        </tr>
    </table>
    <br />
    <input class=no type="submit" value="Save Password" class=text>
    </form>
    </center>
    <?php
	
	return 'Passwords';
}

function display_watermark_settings()
{
	global $site;
	global $dir;

    ?>
	<br/>
    <center>
    <form method="post" enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF'].'?cat=16'; ?>">
<table width="100%" cellspacing="0" cellpadding="3" class="text" style="border-collapse: collapse; border: 1px solid silver" border="0">     
<tr><td></td></tr>        <!--<tr class="panel">
            <td colspan="2"><b>Change Watermark</b></td>
        </tr>-->
<?
	if ( strlen(trim(getParam("Water_Mark"))) && file_exists( $dir['profileImage'] . getParam("Water_Mark") ) )
	{
?>
        <tr>
        	<td colspan="2" align="center" valign="middle">
        		<img src="<?= $site['profileImage'] . getParam("Water_Mark") ?>" border="0">
        	</td>
        </tr>
<?
	}
?>
        <tr>
            <td align="right" width="50%"><?echo getParamDesc("Water_Mark")?>:</td>
            <td align="left" width="50%"><input class="no" name="file_watermark" size="10" type="file"></td>
        </tr>
        <tr>
            <td align="right" width="50%"><?echo getParamDesc("transparent1")?></td>
            <td align="left" width="50%"><input class="no" name="transp1" size="10" type="text" value="<?echo getParam("transparent1")?>">%</td>
        </tr>
         <tr>
            <td align="right" width="50%"><?echo getParamDesc("enable_watermark")?></td>
            <td align="left" width="50%"><input class="no" name="enable_wm" size="10" type="checkbox" <?if(getParam("enable_watermark")) echo "checked";?>></td></tr>
    <tr><td align="center" colspan="2"><input type="hidden" name="save_settings" value="yes">
    <input name="MAX_FILE_SIZE" value="2097152" type="hidden">
    <input class="no" value="Save" type="submit">
    </form>
    </center>
    <br/>
        </td></tr>
       </table>
      <br />

    <?php
	
	return '';
}

/**
 * Display form and controls for editing language settings.
 */
function display_language_settings()
{
    ?>
    <center>
    <form method="post" action="<? echo $_SERVER[PHP_SELF].'?cat=ls'; ?>">
    <input type="hidden" name="save_settings" value="yes">
    <table width="100%" cellspacing="2" cellpadding="3" class="text">
        <!--<tr class="panel">
            <td colspan=2><b> Change language settings </b></td>
        </tr>-->
        <tr class="table">
            <td align="right" width="50%"> <?php echo getParamDesc("lang_default") ?>: </td>
            <td align="left">
                <select name="lang_default">
                    <?php
                    $lang_arr = getLocalizationLanguages();
                    $old_val = getParam("lang_default");
                    foreach ($lang_arr as $val)
                    {
                        if ($old_val == $val)
                        {
                            echo "<option value=\"$val\" selected>$val</option>\n";
                        }
                        else
                        {
                            echo "<option value=\"$val\">$val</option>";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr class="table">
            <td align="right" width="30%"> <?=getParamDesc("lang_enable") ?>: </td>
            <td align="left">
                <input type="checkbox" name="lang_enable" <?=(getParam("lang_enable")==1?"checked":"")?>>
            </td>
        </tr>
    </table>
    <br />
    <input class=no type="submit" value="Save changes" class=text>
    </form>
    </center>
    <?php
	
	return 'Languages';
}

/**
 * Display form and controls for editing index page settings.
 */
function display_index_page_settings()
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
    <form method="post" action="<? echo $_SERVER[PHP_SELF].'?cat=ip'; ?>">
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
                    global $prof;
                    $old_val = getParam('default_country');
                    $country_arr = $prof['countries'];
                    foreach ($country_arr as $key => $value)
                    {
                        if ($old_val == $key)
                        {
                            echo "<option value=\"$key\" selected>". _t('__'.$value) ."</option>\n";
                        }
                        else
                        {
                            echo "<option value=\"$key\">". _t('__'.$value) ."</option>\n";
                        }
                    }
                    ?>
                </select>
                &nbsp;<img id="flagImageId" src="<?= $site['flags'].strtolower($old_val) ?>.gif" alt="flag" />
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
    </table>
    <br>
    <input class="no" type="submit" value="Save changes">
    </form>
    </center>
    <?php
	
	return 'Index Page';
}

/**
 * Display controls for editing category settings.
 * The enclosing form must be defined outside of the function, this is done to be able
 * to display controls for editing a single category or a group of categories.
 * @param $cat -- Category for which to display controls.
 * The function makes use of $errors global variable.  This is an array of invalid parameters and
 * it is used to highligh those paramters while displaying.
 */
function display_category_settings($cat)
{
    global $errors;
    global $dir;

    $cat = (int)$cat;

    // Get category name.
    $q_str = "SELECT `name` FROM `GlParamsKateg` WHERE `ID` = '$cat'";
    $row = db_arr($q_str);
    $cat_name = $row['name'];

    // Get a list of category items.
    $q_str = "
    		SELECT
    				`Name`,
    				`VALUE`,
    				`desc`,
    				`Type`
    		FROM
    				`GlParams`
    		WHERE
    				`kateg` = '$cat' AND `Name` NOT LIKE '%_subject' ORDER BY `order_in_kateg` ASC";
    $items = db_res($q_str);
    // Display controls for editing items.
    ?>
    <center>
    <table width="100%" cellspacing="0" cellpadding="3" class="text" style="border-collapse: collapse; border: 1px solid silver" border="0">
        
        <?php
        // Iterate thru items.
        $i = 0;
        while ($row = mysql_fetch_array($items))
        {
            $param_name = $row['Name'];
            $param_value = $row['VALUE'];
            $param_type = $row['Type'];
            $param_desc = $row['desc'];
            if (1 == $errors[$param_name])
            {
                $class = 'table_err';
            }
            else
            {
                if (0 == $i % 2)
                {
                    $class = 'table';
                }
                else
                {
                    $class = 'table_odd';
                }
            }
            $i++;
            echo "<tr class=\"$class\"><td align=\"left\" class=\"small1\"";
            // Display item caption.
            if ('text' == $param_type)
            {
                echo ' valign="top"';
            }
            echo ">$param_desc</td><td align=\"right\" width=\"200\">";
            // Display item control.
            if ('text' == $param_type)
            {
                if (EMAIL_TEMPLATE_CATEGORY == $cat)
                {
                    // Get email subject.
                    $subject_name = $param_name.'_subject';
                    $q_str = "SELECT `VALUE` FROM `GlParams` WHERE `Name` = '$subject_name'";
                    $res = db_res($q_str);
                    if ($row = mysql_fetch_array($res))
                    {
                        $subject_value = $row['VALUE'];
                    }
                    else
                    {
                        $subject_value = '';
                    }
                    echo "<b>HTML version</b>&nbsp;(";
                    echo "<a href=\"javascript:void(0);\" onClick=\"javascript:docOpen('". rawurlencode($param_value) ."');\">";
                    echo _t("_Preview");
                    echo "</a>)<br>";

                    echo 'Subject:<br />';
                    echo "<input type=\"text\" class=\"no\" size=\"50\" name=\"{$param_name}_subject\" value=\"$subject_value\"/><br /><br />";
                    echo 'Body:<br />';
                    echo "<textarea cols=\"50\" rows=\"10\" class=\"no\" name=\"$param_name\">". htmlspecialchars($param_value) ."</textarea>";

                    $tags = '<RealName> <SiteName> <ConfCode> <ConfirmationLink> <StrID> <Email> <Password>    <YourRealName> <NickName>
                        <Domain> <ID> <TEXT> <VKissLink> <ContactInfo> <DomainName> <FromName> <Link> <NickSpamer> <IDspamer>
                        <LinkSpamer> <MatchProfileLink>    <Requester> <Profile> <site> <PrivPhotosMember> <LoginLink> <Title>
                        <Message_Text> <MessageText> <LinkSDatingEvent> <NameSDating> <PlaceSDating> <WhenStarSDating> <PersonalUID>
                        <MatchLink>';
                    echo '<br><br><br><b>Text version</b><br>';
                    echo 'Subject:<br />';
                    echo "<input readonly type=\"text\" class=\"no\" size=\"50\" name=\"{$param_name}_subject_ro\" value=\"$subject_value\"/><br /><br />";
                    echo 'Body:<br />';
                    echo "<textarea readonly cols=\"50\" rows=\"10\" class=\"no\" name=\"{$param_name}_ro\">". htmlspecialchars(html2txt($param_value, $tags)) ."</textarea>";
                }
                else
                {
                    echo "<textarea cols=\"50\" rows=\"10\" class=\"no\" name=\"$param_name\">$param_value</textarea>";
                }
            }
            elseif ('digit' == $param_type)
            {
                echo "<input type=\"text\" class=\"no\" name=\"$param_name\" size=\"15\" value=\"$param_value\" />";
            }
            elseif ('checkbox' == $param_type)
            {
                echo "<input type=\"checkbox\" name=\"$param_name\" ".('on' == $param_value ? 'checked' : '').'>';
            }
            elseif ('select' == $param_type)
            {
                if ( 'chicks_for_free' == $param_name )
                {
                    $vals = getParam('chicks_for_free', false);
                    $query = "SELECT `extra` FROM `ProfilesDesc` WHERE name='Sex'";
                    $result = db_arr($query);
                    $result['extra'] =  str_replace('\'', '', $result['extra']);
                    $sex = split(',' , $result['extra']);

                    echo "<select name=\"{$param_name}[]\" multiple>";

                    foreach ($sex as $sex_key)
                    {
                        if ( strstr($vals, '\''.$sex_key.'\'') )
                        {
                            $selected = 'selected';
                        }
                        else
                        {
                            $selected = '';
                        }
                        echo "<option value=\"$sex_key\" $selected >$sex_key</option>";
                    }

                    echo "</select>";
                }
                else
                {
                    echo "&nbsp;";
                }
            }
            elseif ('combobox' == $param_type)
            {
                if ( 'chicks_for_free_membership' == $param_name )
                {
                    $memberships_arr = getMemberships();
                    $vals = getParam('chicks_for_free_membership', false);
                    echo "<select name=\"$param_name\">";
                    foreach ( $memberships_arr as $membershipID => $membershipName )
                    {
                        if ( $membershipID == MEMBERSHIP_ID_NON_MEMBER )
                            continue;
                        $selected = ( (int)$vals == $membershipID ) ? ' selected ' : '';
                        echo "<option value=\"{$membershipID}\" $selected >{$membershipName}</option>";
                    }
                    echo "</select>";
                }
                elseif ( 'template' == $param_name )
                {
                    $old_val = getParam('template');
                    $templ_choices = get_templates_array();

                    echo "<select name=\"$param_name\">";
                    foreach ( $templ_choices as $key => $value )
                    {
                    	echo "<option value=\"{$key}\" ". ($old_val == $key ? 'selected="selected"' : '') .">{$value}</option>\n";
                    }
                    echo "</select>";
                }
            }
            else
            {
                echo "<input type=\"text\" name=\"$param_name\" value=\"$param_value\" size=\"30\" />";
            }
            echo "</td></tr>";
        }
        ?>
    </table>
    </center><br />
    <?php
	
	return ' ';
}

/**
 * Display error messages.
 * @global $erros -- An array of param names with invalid values.
 * @retval -- HTML formatted error messages.
 */
function get_error_messages()
{
    global $errors;
    // Iterate thru param names.
    foreach ($errors as $key => $val)
    {
        if (1 == $val)
        {
            // Get error message.
            $q_str = "SELECT `desc`, `err_text` FROM `GlParams` WHERE `Name`='$key'";
            $row = db_arr($q_str);
            //
            $err_text .= "<div class=\"err\">$row[desc]: $row[err_text]</div><br />";
        }
    }
    return $err_text;
}

/**
 * Save administrator password into database.
 * The function checks if the newly entered password confirmed correctly,
 * if the new password is of allowable length.
 * In case all the conditions are met the new password is written to the database.
 * @param admin_name -- Administrator name (there can be many admins).
 * @param $pwd_old -- Old password.
 * @param $pwd_new -- New password.
 * @param $pwd_new_confirm -- New password confirmation.
 * @retval Returns 0 in case of success, otherwise returns error code:
 * E_INVALID_OLD_PASSWORD -- In case of wrong old password,
 * E_PASSWORD_CONFIRMATION_FAILED -- In case $new_pwd and $new_pwd_confirm are not equal,
 * E_INVALID_PASSWORD_LENGTH -- In case password is too short, or too long.
 */
function save_admin_password($admin_name, $pwd_old, $pwd_new, $pwd_new_confirm)
{
    // Get previous admin password from database.
    $q_str = "SELECT Password FROM Admins WHERE Name = '$admin_name'";
    $row = db_arr($q_str);
    // Check password and save it if check conditions are met.
    if ($row['Password'] != md5($pwd_old)) // Check old password
    {
        $result = E_INVALID_OLD_PASSWORD;
    }
    else if (strlen($pwd_new) > 10 || strlen($pwd_new) < 3) // Check password length.
    {
        $result = E_INVALID_PASSWORD_LENGTH;
    }
    else if ($pwd_new != $pwd_new_confirm) // Check if password confirmed correctly.
    {
        $result = E_PASSWORD_CONFIRMATION_FAILED;
    }
    else // no errors, save new password
    {
        // Write new password to database.
        $q_str = "UPDATE Admins SET Password = md5('$pwd_new') WHERE Name = '$admin_name'";
        mysql_query($q_str);
        $result = S_OK;
    }
    return $result;
}

/**
 * Save language settings.
 * Save current language and flag indicating if to enable/disalbe language switching.
 */
function save_language_settings()
{
    // save default language.
    if ($_POST['lang_default'])
    {
        setparam('lang_default', $_POST['lang_default']);
    }
    //
    if ('on' == $_POST['lang_enable'])
    {
        setparam('lang_enable', '1');
    }
    else
    {
        setparam('lang_enable', '0');
    }
    ?>
    <div class="succ">Language settings changed.</div><br />
    <?php
}

/**
 * Save index page settings.
 */
function save_index_page_settings()
{
    setParam('default_country', $_POST['default_country']);
    setParam('top_members_mode', $_POST['top_members_mode']);
    setParam('featured_mode', $_POST['featured_mode']);
    setParam('featured_num', $_POST['featured_num']);
    ?>
    <div class="succ">Index page parameters successfully changed.</div><br />
    <?php
}

/**
 * Save changes for certain category of global settings.
 * @param $cat -- Category to save.
 * @global $errors -- an array of param names with invalid values.
 * @global $_POST -- an array of param names and values.
 */
function save_settings($cat)
{
    global $dir;

    assert(strlen($cat) > 0);
    assert((int)$cat > 0);

    global $errors;

    // Get checking conditions and error messages for every item in the category.
    $q_str = "SELECT `Name`, `check` FROM `GlParams` WHERE `kateg` = $cat";
    $res = db_res($q_str);


    $q_str = "SELECT `ID` FROM `GlParamsKateg` WHERE `name` = 'Variables'";
    $vid = db_arr($q_str);
    $q_str = "SELECT `ID` FROM `GlParamsKateg` WHERE `name` = 'Profiles'";
    $pid = db_arr($q_str);
    $q_str = "SELECT `ID` FROM `GlParamsKateg` WHERE `name` = 'SpeedDating'";
    $sid = db_arr($q_str);
    $mid = 23;

    // Iterate thru items and check if values entered are valid.
    while ($row = mysql_fetch_array($res))
    {
        $param_name = $row['Name'];
        $checked = TRUE;
        // Generate function out of check condition (if the one is not empty).
        $f_body = $row['check']; // Get function body.
        if ( strlen($f_body) > 0 )
        {
            $check_func = create_function('$arg0', $f_body);
            $checked = FALSE;
        }
        // Get new value.
        // Then check if the value is valid.  If it is then save it,
        // otherwise generate error message.
            $param_value = $_POST[$param_name];
			if ( is_array( $param_value ) ) $param_value = '\'' . implode( '\',\'', $param_value ) . '\'';
        if ($checked || $check_func($param_value))
        {
            // Save parameter
            setParam($param_name, $param_value);
            // update header file
			/**
			 * Rewrite global vars at params.inc.php from Variables Category
			 * $vid['ID'] 	- ID of Variables Category
			 * $cat 		- ID of Category that currently under changes
			 *
			 */
            if ( $vid['ID'] == $cat )
            {
                if ( $param_value == 'on' ) $vval = 1;
                elseif ( $param_value == '' ) $vval = 0;
                elseif ( !preg_match ("/^\d+$/i", $param_value) ) $vval = "'$param_value'";
                else $vval = $param_value;

                switch ( $param_name )
                {
                case 'currency_sign': $vtarget = '$doll';
                    break;
                case 'profile_pic_num': $vtarget = '$pic_num';
                    break;
                case 'enable_zip_loc': $vtarget = '$en_ziploc';
                    break;
                case 'enable_aff': $vtarget = '$en_aff';
                    break;
                case 'enable_inbox_notify': $vtarget = '$en_inbox_notify';
                    break;
                case 'enable_dir': $vtarget = '$en_dir';
                    break;
                case 'vote_pic_max': $vtarget = '$max_voting_mark';
                    break;
                case 'template': $vtarget = '$tmpl';
                    break;
                case 'friendlist': $vtarget = '$enable_friendlist';
                	break;
                case 'enable_sdating': $vtarget = '$en_sdating';
                    break;
                case 'enable_thumb': $vtarget = '$enable_auto_thumbnail';
                    break;
                case 'date_format': $vtarget = '$date_format';
                	break;
                case 'short_date_format': $vtarget = '$short_date_format';
                	break;
                default: $vtarget = "";
                break;
                }

                if ( $vtarget )
					Replace_Vars( BX_DIRECTORY_PATH_INC . 'params.inc.php', $vtarget, " = $vval;");
            }
            elseif ( $pid['ID'] == $cat )
            {
			    /**
			     * Rewrite global vars at params.inc.php from Profiles Category
			     * $pid['ID'] 	- ID of Profiles Category
			     * $cat 		- ID of Category that currently under changes
			     *
			     */
                
				if ( $param_value == 'on' ) $vval = 1;
                elseif ( $param_value == '' ) $vval = 0;
                elseif ( !preg_match ("/^\d+$/i", $param_value) ) $vval = "'$param_value'";
                else $vval = $param_value;
	            switch ( $param_name )
                {
	                case 'thumb_width': $vtarget = '$max_thumb_width';
	                    break;
	                case 'thumb_height': $vtarget = '$max_thumb_height';
	                    break;
	                case 'search_start_age': $vtarget = '$search_start_age';
	                    break;
	                case 'search_end_age': $vtarget = '$search_end_age';
	                    break;
	                case 'pic_width': $vtarget = '$max_photo_width';
	                    break;
	                case 'pic_height': $vtarget = '$max_photo_height';
	                    break;
	                case 'track_profile_view': $vtarget = '$track_profile_view';
	                    break;
	                case 'votes': $vtarget = '$votes';
	                    break;
	                case 'votes_pic': $vtarget = '$votes_pic';
	                    break;
	                case 'anon_mode': $vtarget = '$anon_mode';
	                    break;
	                case 'enable_video': $vtarget = '$enable_video_upload';
	                    break;
	                case 'enable_audio': $vtarget = '$enable_audio_upload';
	                    break;
	                case 'zodiac': $vtarget = '$enable_zodiac';
	                    break;
	                case 'newusernotify': $vtarget = '$newusernotify';
	                    break;
	                case 'blog_step': $vtarget = '$blog_step';
	                	break;
	                case 'enable_blog': $vtarget = '$enable_blog';
	                    break;
	                case 'enable_guestbook': $vtarget = '$enable_guestbook';
	                    break;
	                default: $vtarget = "";
	                break;
                }

                if ( $vtarget ) Replace_Vars( BX_DIRECTORY_PATH_INC . 'params.inc.php', $vtarget, " = $vval;");
            }
            elseif ( $sid['ID'] == $cat )
            {
			    /**
			     * Rewrite global vars at params.inc.php from SpeedDating Category
			     * $pid['ID'] 	- ID of SpeedDating Category
			     * $cat 		- ID of Category that currently under changes
			     *
			     */

                if ( $param_value == 'on' ) $vval = 1;
                elseif ( $param_value == '' ) $vval = 0;
                elseif ( !preg_match ("/^\d+$/i", $param_value) ) $vval = "'$param_value'";
                else $vval = $param_value;
                switch ( $param_name )
                {
	                case 'enable_sdating': $vtarget = '$en_sdating';
	                	break;
	                default: $vtarget = "";
	                break;
                }

                if ( $vtarget ) Replace_Vars( BX_DIRECTORY_PATH_INC . 'params.inc.php', $vtarget, " = $vval;");
            }
            elseif( $mid == $cat )
            {
            	if ( $param_value == 'on' ) $vval = 1;
                elseif ( $param_value == '' ) $vval = 0;
                elseif ( !preg_match ("/^\d+$/i", $param_value) ) $vval = "'$param_value'";
                else $vval = $param_value;
                /*
                echo '<hr>';
                	echo '[' . $param_name . '] => ' . $vval;
                echo '<hr>';
                */
				switch ( $param_name )
				{
					case 'enable_video_upload':
						$vtarget = '$enable_video_upload';
					break;
					case 'enable_audio_upload':
						$vtarget = '$enable_audio_upload';
					break;
					case 'max_voting_mark':
						$vtarget = '$max_voting_mark';
					break;
					case 'min_voting_mark':
						$vtarget = '$min_voting_mark';
					break;
					case 'max_voting_period':
						$vtarget = '$max_voting_period';
					break;
					case 'max_icon_width':
						$vtarget = '$max_icon_width';
					break;
					case 'max_icon_height':
						$vtarget = '$max_icon_height';
					break;
					case 'max_thumb_width':
						$vtarget = '$max_thumb_width';
					break;
					case 'max_thumb_height':
						$vtarget = '$max_thumb_height';
					break;
					case 'max_photo_width':
						$vtarget = '$max_photo_width';
					break;
					case 'max_photo_height':
						$vtarget = '$max_photo_height';
					break;
					case 'max_photo_files':
						$vtarget = '$max_photo_files';
					break;
					case 'max_photo_size':
						$vtarget = '$max_photo_size';
					break;
					case 'max_video_files':
						$vtarget = '$max_video_files';
					break;
					case 'max_video_size':
						$vtarget = '$max_video_size';
					break;
					case 'max_audio_files':
						$vtarget = '$max_audio_files';
					break;
					case 'max_audio_size':
						$vtarget = '$max_audio_size';
					break;
					case 'max_media_title':
						$vtarget = '$max_media_title';
					break;
					case 'min_media_title':
						$vtarget = '$min_media_title';
					break;
					default: $vtarget = '';
	                break;
				}

				if ( $vtarget ) Replace_Vars( BX_DIRECTORY_PATH_INC . 'params.inc.php', $vtarget, " = $vval;");
            }

        }
        else
        {
            $errors[$param_name] = 1;
        }
    } // while
}

/**
 * Save all settings.
 * The function gets a list of all setting categories from database, iterates thru them,
 * and saves changes for every category.
 */
function save_settings_all()
{
    // Get a list of categories.
    $q_str = 'SELECT `ID` FROM `GlParamsKateg` WHERE `ID` <> '. WATERMARK_CATEGORY;
    $res = db_res($q_str);
    // Iterate thru categories.
    while ($row = mysql_fetch_array($res))
    {
        save_settings($row['ID']);
    }
}

/**
 * Upload WaterMark.
 */
function save_water_mark()
{
	global $dir;


	$gl_pic['pic']['width']	= getParam( 'max_photo_width' );
	$gl_pic['pic']['height'] = getParam( 'max_photo_height' );
	
	$scan = getimagesize( $_FILES['file_watermark']['tmp_name'] );

	if ( $scan && ( 1 == $scan[2] || 2 == $scan[2] || 3 == $scan[2] || 6 == $scan[2] ) )
	{
		$uploadfile = $dir['tmp'] . $_FILES['file_watermark']['name'];
		$targetfile = $dir['profileImage'] . $_FILES['file_watermark']['name'];
		if ( move_uploaded_file($_FILES['file_watermark']['tmp_name'], $uploadfile) )
		{
			$query = "UPDATE `GlParams` SET  `VALUE` ='". addslashes($_FILES['file_watermark']['name']) ."' WHERE `Name` = 'Water_Mark'";
			db_res($query);
			imageResize( $uploadfile, $targetfile, $gl_pic['pic']['width'], $gl_pic['pic']['height'] );
			unlink( $uploadfile );
			@chmod($targetfile, 0644);
		}
	}

	$query = "UPDATE `GlParams` SET `VALUE` ='". (int)$_POST['transp1'] ."' WHERE `Name` = 'transparent1'";
	db_res($query);
	$query = "UPDATE `GlParams` SET `VALUE` ='". process_db_input($_POST['enable_wm']) ."' WHERE `Name` = 'enable_watermark'";
	db_res($query);
    ?>
    <div class="succ">Watermark settings saved.</div><br />
    <?php
}

?>