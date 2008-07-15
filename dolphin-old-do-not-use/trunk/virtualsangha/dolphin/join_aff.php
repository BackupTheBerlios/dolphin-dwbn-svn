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

// --------------- page variables and login

if (getParam("enable_aff") != 'on')
{
	$sCode = MsgBox( _t( '_affiliate_system_was_disabled' ) );
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = $sCode;
	PageCode();
	exit();
}

$_page['name_index']	= 30;
$_page['css_name']		= 'join_aff.css';

$logged['member'] = member_auth(0, false);

$_page['header'] = _t( "_JOIN_AFF_H" );
$_page['header_text'] = _t( "_JOIN_AFF_H" );

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

	ob_start();	

	$min_nick   = 2;
	$max_nick   = 10;
	$min_passwd = 4;
	$max_passwd = 8;

	$page = $_POST[page];

	$Name = $_POST[Name];
	$Email = $_POST[Email];
	$Email2 = $_POST[Email2];
	$Pwd1 = $_POST[Password1];
	$Pwd2 = $_POST[Password2];

switch ( $page )
{
    case 2:
	if (!isset($_POST['securityImageValue']) || !isset($_COOKIE['strSec']) || md5($_POST['securityImageValue']) != $_COOKIE['strSec'])
	{
		$add_on .= report_err ( _t("_SIMG_ERR") );
		$page = 1;

	}
	if ( strlen($Name) > 10 || strlen($Name) < 2 )
	{
		$add_on .= report_err( _t("_NICK_LEAST2", $min_nick, $max_nick) );
		$page = 1;
	}
        if ( !strcmp($Email,$Email2) && (!eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$", $Email) || db_arr("SELECT ID FROM aff WHERE UPPER(email) = UPPER('$Email')")) )
        {
            $add_on .= report_err( _t("_EMAIL_INVALID_AFF") );
            $page = 1;
        }
        if ( !(strlen($Pwd1) >= 4 && strlen($Pwd1) <= 10 && !strcmp($Pwd1,$Pwd2)) )
        {
            $add_on .= report_err( _t("_PWD_INVALID2", $min_passwd, $max_passwd) );
            $page = 1;
        }


	break;

}

switch( $page )
{
    case 2:
	$page_text = _t( "_JOIN_AFF2", $site['url_aff'], $site['title'] );
    break;

    default:
	$page_text = _t( "_JOIN1_AFF" ).$add_on;
    break;
}

switch ( $page )
{
case 2:
	// new profile creation

	$cl_values = "INSERT INTO aff SET ";

    $seed = rand();
    $cl_values .= "  `seed`     = ".$seed;
	$cl_values .= ", `RegDate`  = NOW()";
	$cl_values .= ", `Percent`  = 10 ";
	$cl_values .= ", `Status`   = 'active' ";

	$cl_values .= ", `Name`     = '$Name' ";
	$cl_values .= ", `email`    = '$Email' ";
	$cl_values .= ", `Password` = '" . md5( $Pwd1 ) . "' ";

	$create_result = db_res( $cl_values );

	if ($create_result)
	{
		$arr = db_arr("SELECT ID FROM aff WHERE `seed` = $seed");
		if ( $arr )
			db_res("UPDATE aff SET `seed` = 0 WHERE ID = $arr[ID]");
		$ID_New_Aff = $arr[ID];
	}

	echo "<table width=\"100%\" cellpadding=4 cellspacing=4><td align=center class=text2>";
    echo "<div align=justify>$page_text</div>";
    echo "<div align=justify>"._t("_JOIN_AFF_ID",$ID_New_Aff)."</div>";
    break;

default:

	echo "<table width=\"100%\" cellpadding=4 cellspacing=4><td align=center class=text2>";
?>
<form method=post action="<? echo $_SERVER[PHP_SELF]; ?>">
<input type=hidden name=page value=2>
<div align=justify><?	echo $page_text; ?></div>
<br /><br />
<table width=100% cellspacing=1 cellpadding=3 class=text>

<tr class=table>
	<td width=30%><b><?php echo _t("_Name"); ?></b></td>
	<td width=40%><input class=no type=text size=40 name="Name" value="<?php echo $Name; ?>"></td>
	<td width=30%><?php echo _t("_chars_to_chars",$min_nick,$max_nick); ?></td>
</tr>

<tr><td><br /></td></tr>

<tr class=table>
    <td width=30%><b><?php echo _t("_E-mail"); ?></b></td>
    <td width=40%><input class=no type=text size=40 name="Email" value="<?php echo $Email; ?>"></td>
    <td width=30%><?php echo _t("_Must be valid"); ?></td>
</tr>

<tr class=table>
    <td width=30%><b><?php echo _t("_Confirm E-mail"); ?></b></td>
    <td width=40%><input class=no type=text size=40 name="Email2"></td>
    <td width=30%><?php echo _t("_Confirm your e-mail"); ?></td>
</tr>

<tr><td><br /></td></tr>

<tr class=table>
    <td width=30%><b><?php echo _t("_Password"); ?></b></td>
    <td width=40%><input class=no type=password size=40 name="Password1" value=""></td>
    <td width=30%></td>
</tr>

<tr class=table>
    <td width=30%><b><?php echo _t("_Confirm password"); ?></b></td>
    <td width=40%><input class=no type=password size=40 name="Password2" value=""></td>
    <td width=30%><?php echo _t("_Confirm your password"); ?></td>
</tr>


</table>
<br /><center>
<img alt="Security Image" src="simg/simg.php" /><br /><br />
<? echo _t( "_Enter what you see:" ); ?><input name="securityImageValue" type="text" size="15" ><br /><br />
<input class=no type="submit" value="<? echo _t( "_Submit" ); ?>" class="text"></center></form>
<?
    break;
}
echo "</td></table>";

    return ob_get_clean();
}


/**
 * Print error message
 */
function report_err( $str )
{
    return "<font color=\"#880000\"><b>" . _t( "_Error" ) . ":</b> $str</font><br />";
}


?>
