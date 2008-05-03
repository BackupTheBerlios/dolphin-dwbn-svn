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

define('errArgCountNotMatch', 'Arguments count do not match: %d');
define('errInvalidInputData', 'Invalid input data (vbblock.php): %s');

// $argc, $argc might me unset, because:
// 	 - 'register_argc_argv' option might be 'Off'
// 	 - vBulletin unsets all super globals if 'register_globals' option is 'On'
if (! isset($argc) || ! isset($argv))
{
	$argc = $_SERVER['argc'];
	$argv = $_SERVER['argv'];
}

function FatalError($ErrorMessage)
{
	echo "<br /><p>Fatal error: <b>{$ErrorMessage}</b></p><br />";
	$file = fopen("ae_error_log.log", "a");
	if ($file)
	{
		fwrite($file, 'vbblock.php error: '.$ErrorMessage."\n");
		fclose($file);
	}	
	exit($ErrorMessage);
}

/* Process data */

// $argc, $argc might me unset, because:
// 	 - 'register_argc_argv' option might be 'Off'
// 	 - vBulletin unsets all super globals if 'register_globals' option is 'On'
if (! isset($argc) || ! isset($argv))
{
	$argc = $_SERVER['argc'];
	$argv = $_SERVER['argv'];
}

if ($argc < 3)
{
	FatalError(sprintf(errArgCountNotMatch, $argc));
}

$Action = $argv[1];
if (! in_array($Action, array('block', 'unblock')))
{
	FatalError(sprintf(errInvalidInputData, "action = '{$Action}'"));
}

// Get nickname and extract vBulletin user ID
$Nickname = $argv[2];
$arrUser = $db->query_first("SELECT userid
							   FROM ".TABLE_PREFIX."user
							   WHERE username = '".addslashes($Nickname)."'");
if (! $arrUser)
{
	FatalError(sprintf(errInvalidInputData, "user '{$Nickname}' is not registered in vBulletin"));
}
$UserID = (int)$arrUser['userid'];

if ($Action == 'block')
{	
	if ($argc < 5)
	{
		FatalError(sprintf(errArgCountNotMatch, $argc));
	}
	$ReasonMessage = $argv[3];
	$BannedUserTitle = $argv[4];
}

/* Manage vBulletin */

$arrUser = $db->query_first("SELECT *		   	
							  FROM ".TABLE_PREFIX."user
							  WHERE userid = {$UserID}");

$objUser =& datamanager_init('User', $vbulletin, ERRTYPE_SILENT);
$objUser->set_existing($arrUser);

if ($Action == 'block')
{	
	$BanPeriod = 0; // Ban forever, until we explicitly unblock the user

	// check to see if there is already a ban record for this user in the userban table
	if ($check = $db->query_first("SELECT userid 
							   	   FROM ".TABLE_PREFIX."userban 
							   	   WHERE userid = {$UserID}"))
	{		
		$db->query_write("UPDATE ".TABLE_PREFIX."userban 
						  SET bandate = ".TIMENOW.",
							  liftdate = 0,
							  adminid = 0,
							  reason = '{$ReasonMessage}'
						  WHERE userid = {$UserID}");
	}
	else
	{
		// Current user group data is saved in userban table		
		$db->query_write("INSERT INTO ".TABLE_PREFIX."userban
							  (userid,
							   usergroupid,
							   displaygroupid,
							   customtitle,
							   usertitle,
							   adminid,
							   bandate,
							   liftdate,
							   reason)
						  VALUES
							  ({$UserID},
							   {$arrUser['usergroupid']},
							   1, 
							   1, 
							   '{$arrUser['usertitle']}', 
							   0, 
							   ".TIMENOW.", 
							   {$BanPeriod}, 
							   '".addslashes($ReasonMessage)."')");
	}
	
	$objUser->set('usergroupid', 8); // 8 - 'Banned users' group
	$objUser->set('displaygroupid', 1);
	$objUser->set('customtitle', 1);
	$objUser->set('usertitle', $BannedUserTitle);
}
elseif ($Action == 'unblock')
{		
	$BanningInfo = $db->query_first("SELECT userban.usergroupid AS UsergroupID,
			   								userban.usertitle AS UserTitle,
			   								IF(userban.userid, 1, 0) AS AlreadyBanned
									 FROM ".TABLE_PREFIX."user AS user	
									 LEFT JOIN ".TABLE_PREFIX."userban AS userban ON(userban.userid = user.userid)
									 WHERE user.userid = {$UserID}");
	
	if ($BanningInfo['AlreadyBanned'])
	{
		$db->query_write("DELETE FROM ".TABLE_PREFIX."userban
						  WHERE userid = {$UserID}");
	}
	
	// Restore user settings, that were before banning
	$objUser->set('usergroupid', $BanningInfo['UsergroupID']);
	$objUser->set('displaygroupid', 1);
	$objUser->set('customtitle', 1);
	$objUser->set('usertitle', $BanningInfo['UserTitle']);
};

$objUser->save();
unset($objUser);
?>