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

require_once("header.inc.php");
require_once("db.inc.php");
require_once("utils.inc.php");
require_once("membership_levels.inc.php");

/**
*	Prepares strings to be passed through command line as arguments
*/
class CCommandLineArgs
{
	var $Arguments = array();

	/**
	*	Adds a new argument to command line
	*
	*	@param string	$Argument Argument itself
	*/
	function AddArgument($Argument)
	{
		$this->Arguments[] = (string)$Argument;
	}

	/**
	*	Returns count of stored arguments
	*
	*	@return int	Count of stored arguments
	*/
	function Count()
	{
		return count($this->Arguments);
	}

	/**
	*	Main method. Returns correctly constructed command line arguments
	*
	*	@return string	Command line arguments, ready to be passed
	*/
	function GetCommandLine()
	{
		$Result = '';
		foreach ($this->Arguments as $Argument)
		{
			if (preg_match('/["\s\\\\]/', $Argument))
			{
				// Replace  [" -> \"] and [\ -> \\] and enclose result in double quotes
				$Result .= '"'.str_replace(array('\\', '"'), array('\\\\', '\\"'), $Argument).'"';
			}
			elseif (strlen(trim($Argument)) <= 0)
			{
				$Result .= '""';
			}
			else
			{
				$Result .= $Argument;
			}
			$Result .= ' ';
		}
		return $Result;
	}
}

/**
*	Calls module function, stored in database. Supports arbitrary number of module function parameters.
*	For internal use only. Use 'modules_xxx' functions instead.
*
*	@param string	$FuncName		Name of module function (actually name of column of `Modules` table)
*
*	@param string	$ParametersDeclaration		Parameters section of function declaration (see create_function)
*
*	@param array	$Parameters		Parameters values, will be passed to module function
*
*	@param string	$ModuleType		If not equal to empty string, only specified type of modules will be processed
*
*	@param string	$ModuleName		If not equal to empty string, only module with specified name will be processed,
*									parameter $ModuleType will be ignored
*
*	@param out	&$ErrorMessage	Will contain error message in case of any error
*
*	@return mixed	Number of processed modules or returns false in case of any error. &$ErrorMessage will contain
*					error message, if false is returned
*/
function CallModuleFunction($FuncName, $ParametersDeclaration, $Parameters, $ModuleType, $ModuleName, &$ErrorMessage)
{
	$FuncName = addslashes($FuncName);
	$ModuleType = addslashes($ModuleType);
	$ModuleName = addslashes($ModuleName);

	if (! is_array($Parameters))
	{
		$ErrorMessage = "$Parameters is not array";
		return false;
	}

	$ValidIdentifierPattern = '/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/';
	if (! preg_match($ValidIdentifierPattern, $FuncName))
	{
		$ErrorMessage = "\$FuncName is not a valid identifier: '{$FuncName}'";
		return false;
	}

	// Check $ParametersDeclaration for correctness
	$ValidParametersDeclarationPattern = '/(?:^|,\s*)(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*/'; // Set of valid identifiers, separated by commas
	$Dummy = array();
	$DeclaredParametersCount = preg_match_all($ValidParametersDeclarationPattern, $ParametersDeclaration, $Dummy, PREG_PATTERN_ORDER);
	if (! $DeclaredParametersCount || $DeclaredParametersCount <> count($Parameters))
	{
		$ErrorMessage = "erroneous parameters' declaration: '$ParametersDeclaration'";
		return false;
	}

	if (strlen(trim($ModuleName)) > 0)
		$WhereClause = "`Name` = '{$ModuleName}'";
	elseif (strlen(trim($ModuleType)) > 0)
		$WhereClause = "`Type` = '{$ModuleType}'";
	else
		$WhereClause = '';

	$GetModulesQuery = "SELECT `Name`, `{$FuncName}` FROM `Modules`";
	if (strlen(trim($WhereClause)) > 0)
		$GetModulesQuery .= ' WHERE '.$WhereClause;

	$ResModules = db_res($GetModulesQuery);

	while ($ArrModule = mysql_fetch_array($ResModules))
	{
		$ModuleFunction = $FuncName.'_'.$ArrModule['Name'];
		global $$ModuleFunction;
		if (! isset($$ModuleFunction))
		{
			$$ModuleFunction = create_function($ParametersDeclaration, $ArrModule[$FuncName]);
		}

		$FunctionCallCode = '$$ModuleFunction(';
		$ProcessedKeysCount = 0;
		foreach ($Parameters as $Key => $Value)
		{
			if (! is_int($Key))
			{
				$Key = "'".$Key."'";
			}
			$FunctionCallCode .= "\$Parameters[{$Key}]";
			if ($ProcessedKeysCount != count($Parameters) - 1)
			{
				$FunctionCallCode .= ', ';
			}
			$ProcessedKeysCount++;
		}
		$FunctionCallCode .= ');';

		eval($FunctionCallCode);
	}

	return mysql_num_rows($ResModules);
}

/**
*	Registers member or admin in modules
*
*	@param mixed $ID	User identifier. Should be member ID if $IsAdmin is 0 or admin name if $IsAdmin is 1
*
*	@param string $ModuleType	If not equal to empty string, only specified type of modules will be processed
*
*	@param int	$IsAdmin	Defines, how to interpret $ID parameter.
*
*	@return int	Count of processed modules
*/
function modules_add($ID, $ModuleType = '', $IsAdmin = 0)
{
	if (strlen(trim($ID)) <= 0)
	{
		modules_err("modules_add(): invalid user identifier (\$ID = '{$ID}', \$IsAdmin = '{$IsAdmin}')");
	}

	$ProcessedModulesCount = CallModuleFunction('FuncAdd', '$UserIdentifier, $IsAdmin', array($ID, $IsAdmin),
		$ModuleType, '', $ErrorMessage);

	if ($ProcessedModulesCount === false)
	{
		modules_err("modules_add() (CallModuleFunction error): ".$ErrorMessage);
	}

	return $ProcessedModulesCount;
}

/**
*	Unregisters member or admin from modules
*
*	@param mixed $ID	User identifier. Should be member ID if $IsAdmin is 0 or admin name if $IsAdmin is 1
*
*	@param string $ModuleType	If not equal to empty string, only specified type of modules will be processed
*
*	@param int	$IsAdmin	Defines, how to interpret $ID parameter.
*
*	@return int	Count of processed modules
*/
function modules_del($ID, $ModuleType = '', $IsAdmin = 0)
{
	if (strlen(trim($ID)) <= 0)
	{
		modules_err("modules_del(): invalid user identifier (\$ID = '{$ID}', \$IsAdmin = '{$IsAdmin}')");
	}

	$ProcessedModulesCount = CallModuleFunction('FuncDel', '$UserIdentifier, $IsAdmin', array($ID, $IsAdmin),
		$ModuleType, '', $ErrorMessage);

	if ($ProcessedModulesCount === false)
	{
		modules_err("modules_del() (CallModuleFunction error): ".$ErrorMessage);
	}

	return $ProcessedModulesCount;
}

/**
*	Blocks member to use modules
*
*	@param mixed $ID	User identifier. Should be member ID if $IsAdmin is 0 or admin name if $IsAdmin is 1
*
*	@param string $ModuleType	If not equal to empty string, only specified type of modules will be processed
*
*	@return int	Count of processed modules
*/
function modules_block($ID, $ModuleType = '')
{
	if (! (int)$ID)
	{
		modules_err("modules_block(): invalid member ID (\$ID = '{$ID}')");
	}

	$ProcessedModulesCount = CallModuleFunction('FuncBlock', '$UserIdentifier', array($ID), $ModuleType, '', $ErrorMessage);

	if ($ProcessedModulesCount === false)
	{
		modules_err("modules_block() (CallModuleFunction error): ".$ErrorMessage);
	}

	return $ProcessedModulesCount;
}

/**
*	Allows member to use modules
*
*	@param mixed $ID	User identifier. Should be member ID if $IsAdmin is 0 or admin name if $IsAdmin is 1
*
*	@param string $ModuleType	If not equal to empty string, only specified type of modules will be processed
*
*	@return int	Count of processed modules
*/
function modules_unblock($ID, $ModuleType = '')
{
	if (! (int)$ID)
	{
		modules_err("modules_unblock(): invalid member ID (\$ID = '{$ID}')");
	}

	$ProcessedModulesCount = CallModuleFunction('FuncUnblock', '$UserIdentifier', array($ID), $ModuleType, '', $ErrorMessage );

	if ($ProcessedModulesCount === false)
	{
		modules_err("modules_unblock() (CallModuleFunction error): ".$ErrorMessage);
	}

	return $ProcessedModulesCount;
}

/**
*	Updates member's or admin's information
*
*	@param mixed $ID	User identifier. Should be member ID if $IsAdmin is 0 or admin name if $IsAdmin is 1
*
*	@param string $ModuleType	If not equal to empty string, only specified type of modules will be processed
*
*	@param string	$PreviousNickname	If not empty, denotes that nickname of member was changed
*
*	@param int	$IsAdmin	Defines, how to interpret $ID parameter.
*
*	@return int	Count of processed modules
*/
function modules_update($ID, $ModuleType = '', $PreviousNickname = '', $IsAdmin = 0)
{
	if (strlen(trim($ID)) <= 0)
	{
		modules_err("modules_update(): invalid user identifier (\$ID = '{$ID}', \$IsAdmin = '{$IsAdmin}')");
	}

	$ProcessedModulesCount = CallModuleFunction('FuncUpdate', '$UserIdentifier, $IsAdmin, $PreviousNickname',
		array($ID, $IsAdmin, $PreviousNickname), $ModuleType, '', $ErrorMessage);

	if ($ProcessedModulesCount === false)
	{
		modules_err("modules_update() (CallModuleFunction error): ".$ErrorMessage);
	}

	return $ProcessedModulesCount;
}

/**
*	Logs member or admin into module
*
*	@param mixed $ID	User identifier. Should be member ID if $IsAdmin is 0 or admin name if $IsAdmin is 1
*
*	@param string $ModuleName	Defines, which module to log in. Cannot be empty
*
*	@param int	$IsAdmin	Defines, how to interpret $ID parameter
*/
function modules_login($ID, $ModuleName, $IsAdmin)
{
	if (strlen(trim($ID)) <= 0)
	{
		modules_err("modules_login(): invalid user identifier (\$ID = '{$ID}', \$IsAdmin = '{$IsAdmin}')");
	}

	if (strlen(trim($ModuleName)) <= 0)
	{
		modules_err("modules_login(): module name was not specified");
	}

	$ProcessedModulesCount = CallModuleFunction('Login', '$UserIdentifier, $IsAdmin', array($ID, $IsAdmin), '',
		$ModuleName, $ErrorMessage);

	if ($ProcessedModulesCount === false)
	{
		modules_err("modules_login() (CallModuleFunction error): ".$ErrorMessage);
	}

	if ($ProcessedModulesCount === 0)
	{
		modules_err("modules_login(): no module with such name (\$ModuleName = '{$ModuleName}')");
	}
}

/**
*	Raises module error. Calls exit() function (finishes PHP code interperting)
*	For internal use only
*
*	@param string $ErrorMessage	Message to display
*/
function modules_err($ErrorMessage)
{
	global $site;


	mail($site['bugReportMail'], "{$site['title']} : error in module", $ErrorMessage);
	echo "Module error: $ErrorMessage";

	exit;
}

/**
*	Evaluates FuncConf procedure of modules (fills $mods array)
*	For internal use only
*/
function modules_read_config()
{
	global $mods;

	$ResModulesConfig = db_res("SELECT `Conf` FROM `Modules`");

	while ($ArrModuleConfig = mysql_fetch_array($ResModulesConfig))
	{
		eval($ArrModuleConfig['Conf']);
	}
}

/**
*
*	Synchronyzes _certain_ profiles with modules users set
*/
function synchronizeProfiles($idList, $isAdmin)
{
	global $dir;
	global $PHPBIN;

	$CommandLineArgs = new CCommandLineArgs;

	$CommandLineArgs->AddArgument((int)$isAdmin);
	foreach ($idList as $id)
	{
		$CommandLineArgs->AddArgument($id);
	}

	if (chdir($dir['root'].'modules/'))
	{
		$scriptReturnValue = 'value was not set';
		exec("{$PHPBIN} -f refresh.php ".$CommandLineArgs->GetCommandLine(), $scriptOutput, $scriptReturnValue);
		if ($scriptReturnValue !== 0)
		{
			echo "refresh.php returned: <br />\n";
			foreach ($scriptOutput as $outputLine)
			{
				echo $outputLine.'<br />';
			}
			modules_err("synchronizeProfiles(): exec({$PHPBIN} -f refresh.php ".$CommandLineArgs->GetCommandLine().") returned ".$scriptReturnValue);
		}
	}
	else
	{
		modules_err("synchronizeProfiles(): chdir({$dir['root']}modules/) returned false");
	}

	/* DEBUG
	echo "exec({$PHPBIN} -f refresh.php ".$CommandLineArgs->GetCommandLine().")";
	echo "<br />Return value = ".$scriptReturnValue."<br >";
	foreach ($scriptOutput as $outputLine)
	{
		echo $outputLine.'<br />';
	}
	echo '------------------------------------------ <br /><br />';
	//*/
}

/**
*	Synchronyzes _all_ profiles with modules users set. Synchronization is performed
*	part-by-part to avoid execution time limit exceeding
*/
function modulesRefresh($partSize = 20)
{
	/**
	*	Splits list of profiles into parts and calls synchronizeProfiles function for each part
	*/
	function splitAndSynchronize($idList, $isAdmin, $partSize)
	{
		if (! is_array($idList))
		{
			$idList = array($idList);
		}
		$fullPartsCount = intval(count($idList) / $partSize);
		for ($partIndex = 0; $partIndex <= $fullPartsCount - 1; $partIndex++)
		{
			$part = array();
			for ($idIndex = 0; $idIndex <= $partSize - 1; $idIndex++)
			{
				$part[] = $idList[$partIndex * $partSize + $idIndex];
			}
			synchronizeProfiles($part, $isAdmin);
		}

		$restSize = count($idList) % $partSize;
		if ($restSize)
		{
			$part = array();
			for ($idIndex = 0; $idIndex <= $restSize - 1; $idIndex++)
			{
				$part[] = $idList[$fullPartsCount * $partSize + $idIndex];
			}
			synchronizeProfiles($part, $isAdmin);
		}
	} /* function splitAndSynchronize */


	// Synchronize admins
	$resAdmins = db_res("SELECT `Name` FROM `Admins`");
	while ($adminInfo = mysql_fetch_array($resAdmins))
	{
		$admins[] = $adminInfo['Name'];
	}
	splitAndSynchronize($admins, 1, $partSize);

	// Synchronize members
	$resMembers = db_res("SELECT `ID`, `NickName` FROM `Profiles`");
	while ($arrMember = mysql_fetch_array($resMembers))
	{
		if (! in_array($arrMember['NickName'], $admins))
		{
			$members[] = $arrMember['ID'];
		}
	}
	splitAndSynchronize($members, 0, $partSize);
}

modules_read_config();

?>