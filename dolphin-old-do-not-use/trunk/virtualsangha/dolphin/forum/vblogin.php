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

require_once('./global.php');
require_once(DIR . '/includes/functions_login.php');
require_once(DIR . '/includes/functions_misc.php');
	
function FatalError($ErrorMessage)
{		
	echo "<br /><p>Fatal error: <b>{$ErrorMessage}</b></p><br />";
	$file = fopen("ae_error_log.log", "a");
	if ($file)
	{
		fwrite($file, 'vblogin.php error: '.$ErrorMessage."\n");
		fclose($file);
	}
	exit($ErrorMessage);
}

function GetInputData($KeyName, &$Value)
{
	if (! array_key_exists($KeyName, $_POST))
	{
		FatalError("'$KeyName' value was not sent thru POST");	
	}
	$Value = $_POST[$KeyName];
}

// Process input data	
GetInputData('Config', $Config);
if (! array_key_exists('CookieMember', $Config))
	FatalError("Key 'CookieMember' is not exists in \$Config array");
$CookieUser = $Config['CookieMember'];

if (! array_key_exists('RedirectMethod', $Config))
	FatalError("Key 'RedirectMethod' is not exists in \$Config array");
$RedirectMethod = $Config['RedirectMethod'];
if (! in_array($RedirectMethod, array('SubmitForm', 'SendHeader')))
	FatalError("Invalid RedirectMethod option: '{$RedirectMethod}'");

GetInputData('UserIdentifier', $Username);
GetInputData('LoginMessage', $LoginMessage);

	
$Username = strip_blank_ascii($Username, ' ');
if ($vbulletin->userinfo = $vbulletin->db->query_first("SELECT userid, usergroupid, membergroupids, username, password, salt 
														FROM ".TABLE_PREFIX."user 
														WHERE username = '".$vbulletin->db->escape_string(htmlspecialchars_uni($Username))."'"))
{
	if ($CookieUser)
	{
		vbsetcookie('userid', $vbulletin->userinfo['userid']);
		vbsetcookie('password', md5($vbulletin->userinfo['password'] . COOKIE_SALT));
	}
	else if ($vbulletin->$_COOKIE[COOKIE_PREFIX.'userid'] AND $_COOKIE[COOKIE_PREFIX.'userid'] != $vbulletin->userinfo['userid'])
	{
		// If there is cookie from other user, delete it
		vbsetcookie('userid', '');
		vbsetcookie('password', '');
	}	
}
else 
	FatalError("Erroneous or empty query result: "."SELECT userid, usergroupid, membergroupids, username, password, salt FROM ".TABLE_PREFIX."user WHERE username = '".$vbulletin->db->escape_string(htmlspecialchars_uni($Username))."'");
	
// Create new session		
$vbulletin->db->query_write("DELETE FROM ".TABLE_PREFIX."session 
							 WHERE sessionhash = '".$vbulletin->db->escape_string($vbulletin->session->vars['dbsessionhash'])."'");

if ($vbulletin->session->created == true AND $vbulletin->session->vars['userid'] == 0)
{
	$newsession =& $vbulletin->session;
}
else
{
	$newsession =& new vB_Session($vbulletin, '', $vbulletin->userinfo['userid'], '', $vbulletin->session->vars['styleid']);
}
$newsession->set('userid', $vbulletin->userinfo['userid']);
$newsession->set('loggedin', 1);
$newsession->set('bypass', 0); // bypass = 0, because we are not logging in to the control panel
$newsession->set('loggedin', 2);
$newsession->set_session_visibility(($vbulletin->superglobal_size['_COOKIE'] > 0));
$vbulletin->session =& $newsession;
$vbulletin->session->save();

$vbulletin->url = $vbulletin->options['forumhome'] . '.php' . $vbulletin->session->vars['sessionurl_q'];

$phrase = $LoginMessage.' '.$vbulletin->userinfo['username'];

if ($RedirectMethod == 'SubmitForm')
	standard_redirect($phrase, 1);
else 
	standard_redirect($phrase, 0);
?>