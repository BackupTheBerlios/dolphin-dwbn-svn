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

require_once('./global.php');
require_once(DIR.'/includes/functions.php');
require_once(DIR.'/includes/functions_user.php');

define('errArgCountNotMatch', 'Arguments count do not match: [%d]');
define('errInvalidInputData', 'Invalid input data (vbregister.php): %s');

function FatalError($ErrorMessage)
{
	echo "<br /><p>Fatal error: <b>{$ErrorMessage}</b></p><br />";
	$file = fopen("ae_error_log.log", "a");
	if ($file)
	{
		fwrite($file, 'vbregister.php error: '.$ErrorMessage."\n");
		fclose($file);
	}
	exit($ErrorMessage);
}

function DebugPrint($message)
{			
	echo "DEBUG MESSAGE: {$message} <br />";
	$file = fopen("ae_debug_log.log", "a");
	if ($file)
	{
		fwrite($file, $message."\n");
		fclose($file);
	}
}

/* Process input data */

// $argc, $argc might me unset, because:
// 	 - 'register_argc_argv' option might be 'Off'
// 	 - vBulletin unsets all super globals if 'register_globals' option is 'On'
if (! isset($argc) || ! isset($argv))
{
	$argc = $_SERVER['argc'];
	$argv = $_SERVER['argv'];
}

if ($argc < 4)
	FatalError(sprintf(errArgCountNotMatch, $argc));

$Action = $argv[1];
$Action = trim(strtolower($Action));
if (! in_array($Action, array('add', 'update', 'delete')))
	FatalError(sprintf(errInvalidInputData, "action = '{$Action}'"));

$IsAdmin = (bool)$argv[2];

$Username = $argv[3];
$Username = trim($Username);
if (strlen($Username) <= 0)
		FatalError(sprintf(errInvalidInputData, "blank username"));

if ($Action != 'delete')
{
	if ($argc !== 16)
		FatalError(sprintf(errArgCountNotMatch, $argc));
		
	$Password = $argv[4];	
	if (strlen($Password) <= 0)
		FatalError(sprintf(errInvalidInputData, "blank password"));

	$Email = $argv[5];
	$BirthYear = (int)$argv[6];
	$BirthMonth = (int)$argv[7];
	$BirthDay = (int)$argv[8];
	$AvatarImagePath = (int)$argv[9];
	$CustomUserGroupTitle = $argv[10];
	
	$Config['ShowEMail'] = (bool)$argv[11];
	$Config['ReceiveMailFromAdmin'] = (bool)$argv[12];
	
	$Config['DefaultMemberData']['ShowBirthday'] = (bool)$argv[13];
	
	$Config['DefaultMemberData']['DSTMode'] = (int)$argv[14];
	if (! in_array($Config['DefaultMemberData']['DSTMode'], array(0, 1, 2)))
		FatalError(sprintf(errInvalidInputData, "DSTMode = ".$Config['DefaultMemberData']['DSTMode']));	
		
	$Config['DefaultMemberData']['TimeZoneOffset'] = (int)$argv[15];
	if (! in_array($Config['DefaultMemberData']['TimeZoneOffset'], range(-12, 12)))
		FatalError(sprintf(errInvalidInputData, "TimeZoneOffset = ".$Config['DefaultMemberData']['TimeZoneOffset']));
}

/* Manage vBulletin */

$UserData =& datamanager_init('User', $vbulletin, ERRTYPE_ARRAY);

if ($Action == 'update' || $Action == 'delete')
{	
	/* Denote, that we are going to update or delete existing user, not to add new one. (call set_existing) */
		
	// Find vBulletin user ID by user name
	$SearchQueryResult = $db->query_read("
		SELECT userid
		FROM `".TABLE_PREFIX."user`
		WHERE `username` = '{$Username}'");
	
	$FoundUsersCount = $db->num_rows($SearchQueryResult);

	if ($FoundUsersCount)
	{			
		/*
		for ($i = 0; $i <= $FoundUsersCount - 1; $i++)
		{
			...
		}
		*/
		$FoundUserData = $db->fetch_array($SearchQueryResult);
		$UserID = $FoundUserData['userid'];
			
		$UserInfo = fetch_userinfo($UserID);				
		$UserData->set_existing($UserInfo); // main call
	}
	else if ($Action == 'update')
		$Action = 'add';
	else
	{
		FatalError("Cannot delete: invalid vBulletin user name ('{$Username}')");
	}
	
	if ($Action == 'delete')
	{		
		$UserData->delete();
		unset($UserData);
		exit;
	}
}

$UserData->set('password', $Password);
$UserData->set('email', $Email);
$UserData->set('username', $Username);

$UserGroupID = ($IsAdmin) ? 6 : 2; /* 6 - 'Administrators', 2 - 'Registered users' */
$UserData->set('usergroupid', $UserGroupID);

$UserData->set('birthday', array(
	'day'   => $BirthDay,
	'month' => $BirthMonth,
	'year'  => $BirthYear
));

// Set user title
if (strlen(trim($CustomUserGroupTitle)) > 0)
{	
	$UserData->set('usergroupid', $UserGroupID);
	$UserData->set('displaygroupid', 1);
	$UserData->set('customtitle', 1);
	$UserData->set('usertitle', $CustomUserGroupTitle);
}
else 
{
	// Use default usergroup title
	$UserData->set_usertitle('', true, $vbulletin->usergroupcache["$UserGroupID"], false, $IsAdmin);
}

// Set specifiñ options
$Options = array('adminemail' => $Config['ReceiveMailFromAdmin'],
				 'showemail' => $Config['ShowEMail'],
				 'verifyemail' => 0 // Do not verify member's e-mail
				 );
if (is_array($Options))
{
	foreach ($Options AS $OptionName => $Value)
	{
		$UserData->set_bitfield('options', $OptionName, $Value);
	}
}

if ($Action == 'add')
{
	/* 
		These values we set only once: when registering user.
		We have no such info in Dolphin profiles and it might be changed by a forum user/admin, so we should not lose this info.
	*/
	$UserData->set('showbirthday', $Config['DefaultUserData']['ShowBirthday']); 
	
	// Set COPPA (Children's Online Privacy Protection Act) option
	$UserData->set_info('coppauser', 0);
	$UserData->set_bitfield('options', 'coppauser', 0);
	$UserData->set('parentemail', '');

	// Set interface language (default)
	$UserData->set('languageid', 0);

	// Set Daylight Saving Time and time zone offset (default)
	$DSTMode = $Config['DefaultUserData']['DSTMode'];
	$UserData->set_dst($DSTMode);
	$UserData->set('timezoneoffset', $Config['DefaultUserData']['TimeZoneOffset']);

	// Register IP address
	$UserData->set('ipaddress', IPADDRESS);
}

// Check if there are any errors in inputted data	            
$UserData->pre_save();
if (!empty($UserData->errors))
{
	$errorlist = "Username = '{$Username}'\n";
	foreach ($UserData->errors AS $index => $error)
	{
		$errorlist .= "<li>$error</li>\n";
	}
	FatalError($errorlist);		
}

// Save the data
$vbulletin->userinfo['userid'] = $UserID = $UserData->save();

if (! $UserID)
{
	FatalError("Error: \$UserData->save() returned [$UserID]\n");	
}

unset($UserData);

if ($IsAdmin)
{
	// Set administrator permissions
	$User = $db->query_first("
		SELECT user.userid, 
			   user.username, administrator.*,
			  IF(administrator.userid IS NULL, 0, 1) AS isadministrator
		FROM ".TABLE_PREFIX."user AS user
		LEFT JOIN ".TABLE_PREFIX."administrator AS administrator ON(administrator.userid = user.userid)
		WHERE user.userid = ".$UserID);

	if (! $User)
	{
		FatalError("Cannot set administrator permissions: no user with ID [{$UserID}]");
	}

	$admindm =& datamanager_init('Admin', $vbulletin, ERRTYPE_CP);
	$admindm->set_existing($User);

	// Set permissions array (grant all priviledges)
	$AdminPermissions = array(
		"canadminsettings" => 1,
		"canadminstyles" => 1,
		"canadminlanguages" => 1,
		"canadminforums" => 1,
		"canadminthreads" => 1,
		"canadmincalendars" => 1,
		"canadminusers" => 1,
		"canadminpermissions" => 1,
		"canadminfaq" => 1,
		"canadminimages" => 1,
		"canadminbbcodes" => 1,
		"canadmincron" => 1,
		"canadminmaintain" => 1,
		"canadminplugins" => 1
	);
	
	foreach ($AdminPermissions AS $key => $value)
	{
		$admindm->set_bitfield('adminpermissions', $key, $value);
	}

	$admindm->set('cssprefs', ''); // set default admin panel skin

	$admindm->pre_save();
	if (!empty($admindm->errors))
	{
		$errorlist = '';
		foreach ($admindm->errors AS $index => $error)
		{
			$errorlist .= "<li>$error</li>\n";
		}
		FatalError($errorlist);		
	}

	$admindm->save();
}
?>