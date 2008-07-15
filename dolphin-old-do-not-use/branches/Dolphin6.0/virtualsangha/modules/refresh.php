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
require_once( BX_DIRECTORY_PATH_INC . 'modules.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'membership_levels.inc.php' ); 

define('errArgCountNotMatch', 'Arguments count do not match: %d');
define('errInvalidInputData', 'Invalid input data (refresh.php): %s');

function FatalError($ErrorMessage)
{
	echo "<br /><p>Fatal error: <b>{$ErrorMessage}</b></p><br />";
	exit($ErrorMessage);
}

if ($argc < 3)
{
	FatalError(sprintf(errArgCountNotMatch, $argc));
}

$isAdmin = $argv[1];

for ($argIndex = 2; $argIndex <= $argc - 1; $argIndex++)
{
	$userID = $argv[$argIndex];
	if ($isAdmin)
	{
		modules_update($userID, '', '', 1);
	}	
	else 
	{
		if ($userID != (int)$userID)
		{
			FatalError(sprintf(errInvalidInputData, " invalid member ID: [{$userID}]"));
		}
		modules_update($userID); // User will be added if he does not exist
		$resCheckAction = checkAction($userID, ACTION_ID_USE_CHAT);
		if ($resCheckAction[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
		{
			modules_block($userID, 'chat');
		}
		else 
		{
			modules_unblock($userID, 'chat');
		}
		$resCheckAction = checkAction($userID, ACTION_ID_USE_FORUM);
		if ($resCheckAction[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
		{
			modules_block($userID, 'forum');
		}
		else 
		{
			modules_unblock($userID, 'forum');
		}
	}
}

?>