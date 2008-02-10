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
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// --------------- page variables and login

$_page['name_index'] 	= 37;
$_page['css_name']		= 'forgot.css';

$logged['member'] = member_auth( 0, false );

$_page['header'] = _t( "_Forgot password?" );
$_page['header_text'] = _t( "_Password retrieval", $site['title'] );

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
	global $_page;
	global $site;

	$show_form = true;
	$action_result = _t( "_FORGOT", $site['title'] );

	ob_start();

	if ( $_POST['Email'] )
	{
		// Test if eneterd email is not valid
		if ( !eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,4}$", $_POST['Email']) )
		{
			$_page['header'] = _t( "_Error" );
			$_page['header_text'] = _t( "_Incorrect Email" );
			$action_result = _t( "_INCORRECT_EMAIL" );
		}
		else
		{
			// Check if entered email is in the base
			$sEmail = htmlspecialchars_adv($_POST['Email']);
		    $memb_arr = db_arr( "SELECT `ID` FROM `Profiles` WHERE `Email` = '$sEmail'" );
		    if ( $memb_arr['ID'] )
		    {
				$recipient = $sEmail;

				$message = getParam("t_Forgot");
				$subject = getParam('t_Forgot_subject');
				
				generateNewPwd($memb_arr['ID']);
				
				$mail_ret = sendMail( $recipient, $subject, $message, $memb_arr['ID'] );
				
				$sQuery = "UPDATE `Profiles` SET `Password` = md5(`Password`) WHERE `ID`='{$memb_arr['ID']}'";
				db_res( $sQuery );
				
				createUserDataFile( $memb_arr['ID'] );

				if (!$mail_ret)
				{
					$_page['header'] = _t( "_Recognized" );
					$_page['header_text'] = _t( "_RECOGNIZED", $site['title'] );
					$action_result = _t( "_MEMBER_RECOGNIZED_MAIL_NOT_SENT", $site['title'] );
					$show_form = false;
				}

				$_page['header'] = _t( "_Recognized" );
				$_page['header_text'] = _t( "_RECOGNIZED", $site['title'] );
				$action_result = _t( "_MEMBER_RECOGNIZED_MAIL_SENT", $site['url'], $site['title'] );
				$show_form = false;
		    }
		    else
		    {
				$_page['header'] = _t( "_Not Recognized" );
				$_page['header_text'] = _t( "_NOT_RECOGNIZED", $site['title'] );
				$action_result = _t( "_MEMBER_NOT_RECOGNIZED", $site['title'] );
		    }
		}
	}

	echo "<table width=\"100%\" cellpadding=4 cellspacing=4>
			<td align=center class=text2>\n";
	echo $action_result;
	if ( $show_form )
		send_form();
	echo "
			</td></table>\n";

    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
}

/**
 * Prints HTML form for forgot password function
 */
function send_form()
{
?>
<br />
<center>
<form action="<? echo $_SERVER['PHP_SELF']; ?>" method=post>
	<table cellspacing=0 cellpadding=0 class=text>
		<td><? echo _t( "_My Email" ); ?>:&nbsp;</td>
		<td><input class=no type=text name="Email" value="<? echo htmlspecialchars_adv($_POST['Email']); ?>"></td>
		<td>&nbsp;</td>
		<td><input class=no type=submit value="<? echo _t( "_Retrieve my information" ); ?>"></td>
	</table>
</form>
</center>
<?
}

function generateNewPwd($ID)
{
	$sCode = base64_encode( substr( base64_encode( substr( microtime(), 2, 8 ) ), 2, 6 ) );
	$sQuery = "UPDATE `Profiles` SET `Password` = '$sCode' WHERE `ID`='$ID'";
	
	db_res($sQuery);
	
	createUserDataFile( $ID );
}

?>