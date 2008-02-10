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

$_page['name_index'] 	= 34;
$_page['css_name']		= 'unregister.css';

$logged['member'] = member_auth(0);

$_page['header'] = _t("_Delete account");
$_page['header_text'] = _t("_Delete account");

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

	if ( $_POST['DELETE'] )
	{
		profile_delete($_COOKIE['memberID']);

	    setcookie( 'memberID', $_COOKIE['memberID'], time() - 3600, '/' );
		setcookie( 'memberPassword', $_COOKIE['memberPassword'], time() - 3600, '/' );

		return "<center>"._t("_DELETE_SUCCESS")."<br />
				<a href=\"$site[url]\">$site[title]</a></center>";
	}


	ob_start();

	echo spacer(1,5);

?>

<table width="100%" cellpadding="4" cellspacing="4">
	<td align="center" class="text2">
		<form action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="DELETE" value="1">
			<center>
				<?= _t("_DELETE_TEXT"); ?><br /><br />
				<input class="no" type="submit" value="<?= _t("_Delete account"); ?>">
				<br />
				<br />
			</center>
		</form>
	</td>
</table>

<?php

    $ret = ob_get_clean();

    return $ret;
}

?>