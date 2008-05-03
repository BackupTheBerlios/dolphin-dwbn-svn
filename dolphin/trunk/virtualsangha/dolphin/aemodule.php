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
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'modules.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'membership_levels.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$pageIndex = $_page['name_index'] = 23;


$_page['header'] = _t('_Choose forum');

$enable_ray = (getParam( 'enable_ray' ) == 'on');

function LaunchRayChat()
{
	global $site;

	$iId = (int)$_COOKIE['memberID'];
	$sPassword = getPassword( $iId );
	$aPostVals = array( 'module' => 'chat', 'app' => 'user', 'id' => $iId, 'password' => $sPassword );

	Redirect( $site['url'] . 'ray/index.php', $aPostVals, 'get', 'Ray chat' );
}

function showError($errorMessage)
{
	global $pageIndex;
	global $_page_cont;
	global $_page;

	//$_page['header_text'] = _t('_Module_access_error');
	$_page['header_text'] = '';
	$_page_cont[$pageIndex]['page_main_code'] = $errorMessage;
	PageCode();
}

/**
 *	A simple class-container of HTML hyperlink
 */
class CHyperLink
{
	var $linkReference;

	var $linkDescription;

	/**
	 * Returns properly constructed HTML code of hyperlink
	 *
	 * @return string HTML code
	 */
	function GetHTMLcode()
	{
		$code = '<a href="'.addslashes(htmlspecialchars($this->linkReference)).'">';
		if (strlen($this->linkDescription) > 0)
		{
			$code .= $this->linkDescription;
		}
		else
		{
			$code .= $this->linkReference;
		}
		$code .= '</a>';

		return $code;
	}

	function CHyperLink($linkReference, $linkDescription)
	{
		$this->linkReference = $linkReference;
		$this->linkDescription = $linkDescription;
	}
};

/** //MOVE THIS FUNCTION TO utils.inc.php!!!
 * Displays list of links, using current template
 * (design.inc.php module should be included)
 *
 * @param string $pageHeaderText
 *
 * @param string $listHeaderText
 *
 * @param array of CHyperLink $links - array of URLs. Array length must be equal to $linksDescriptions length
 *
 * @return bool	Returns true on success and false in case of any error
 */
function showLinksList($pageIndex, $pageHeaderText, $listHeaderText, $links)
{
	global $_page_cont;
	global $_page;

	if (! class_exists('CHyperLink'))
	{
		return false;
	}

	if ((! is_array($links)) || count($links) == 0)
	{
		return false;
	}

	$pageMainCode = '';
	$_page['header_text'] = $pageHeaderText;
	$pageMainCode .= $listHeaderText.'<br />';

	foreach ($links as $hyperLink)
	{
		if (! is_a($hyperLink, 'CHyperLink'))
		{
			return false;
		}
		$pageMainCode .= $hyperLink->GetHTMLcode().'<br />';
	}

	$_page_cont[$pageIndex]['page_main_code'] = '<div align="center">'. $pageMainCode .'</div>';

	PageCode();

	return true;
}

$isAdmin = member_auth(1, false);
$logged['admin'] = $isAdmin;
if ($isAdmin)
{
	$adminName = $_COOKIE['adminID'];
}
else
{
	$isMember = member_auth(0, false);
	$logged['member'] = $isMember;
	if ($isMember)
	{
		$memberID = $_COOKIE['memberID'];
	}
}

if (array_key_exists('ModuleName', $_GET))
{

	$moduleName = $_GET['ModuleName'];
	$dbModuleName = process_db_input($_GET['ModuleName']);

	// Ray support
	if ($moduleName == 'ray')
	{
		if ($enable_ray)
		{
			if ($isMember)
			{
				$chechActionRes = checkAction($memberID, ACTION_ID_USE_RAY_CHAT);
				if ($chechActionRes[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)
				{
					LaunchRayChat();
					checkAction($memberID, ACTION_ID_USE_RAY_CHAT, true);
				}
				else
				{
					showError($chechActionRes[CHECK_ACTION_MESSAGE]);
				}
			}
			else
			{
				showError(_t('_Please login before using Ray chat'));
			}
		}
		else
		{
			showError(_t('_Ray is not enabled. Select <link> another module', $_SERVER['PHP_SELF']));
		}
		exit();
	}
	// end of Ray support

	if ($isAdmin)
	{
		modules_login($adminName, $moduleName, 1);
	}
	else if ($isMember)
	{
		$memberID = $_COOKIE['memberID'];

		// Extract module type from database by the module name, do not rely on GET,
		// because it is a hole: user can crack membership restrictions
		list($moduleType) = db_arr("SELECT `Type` FROM `Modules` WHERE `Name` = '{$dbModuleName}'");
		if (strlen(trim($moduleType)) == 0)
		{
			showError(_t('_Invalid module name or invalid row in database') . 'ModuleName = "'.$moduleName.'"');
		}

		// If module is forum or chat, then check if member is allowed to use the module
		switch ($moduleType)
		{
			case 'forum':
				$check_res = checkAction($memberID, ACTION_ID_USE_FORUM);
				if ($check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
				{
					showError($check_res[CHECK_ACTION_MESSAGE]);
					exit();
				}
				checkAction($memberID, ACTION_ID_USE_FORUM, true);
				break;
			case 'chat':
				$check_res = checkAction($memberID, ACTION_ID_USE_CHAT);
				if ($check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
				{
					showError($check_res[CHECK_ACTION_MESSAGE]);
					exit();
				}
				checkAction($memberID, ACTION_ID_USE_CHAT, true);
				break;
			default:
				{
					showError(_t('_Unknown module type selected').": '".$moduleType."'");
				}
		}
		modules_login($memberID, $moduleName, 0);
	}
	else
	{
		// If not a member and not an admin, so merely redirect to a module index page
		if (is_array($mods) && array_key_exists($moduleName, $mods) &&
			is_array($mods[$moduleName]) && array_key_exists('ModuleDirectory', $mods[$moduleName]))
		{
			Redirect($site['url'] . $mods[$moduleName]['ModuleDirectory']);
		}
		else
		{
			showError(_t('_Module directory was not set. Module must be re-configurated'));
		}
	}
}
else
{
	if (array_key_exists('ModuleType', $_GET))
	{
		$moduleType = $_GET['ModuleType'];
		$dbModuleType = process_db_input($_GET['ModuleType']);

		if (! in_array($moduleType, array('forum', 'chat')))
		{
			showError(_t('_Invalid module type selected.').'<br /><a href="'.$_SERVER['PHP_SELF'].'">'._t('_Select module type').'</a>');
			exit();
		}

		// User should select certain module
		$resModules = db_res("SELECT `Name`,
									 `ReadableName`
			 				  FROM `Modules`
							  WHERE `Type` = '{$dbModuleType}'");
		$modulesLinks = array();
		while ($arrModule = mysql_fetch_array($resModules))
		{
			$modulesLinks[] = new CHyperLink($_SERVER['PHP_SELF'].'?ModuleName='.$arrModule['Name'],
				$arrModule['ReadableName']);
		}

		// Ray support
		if ($moduleType == 'chat' && $enable_ray)
		{
			$modulesLinks[] = new CHyperLink($_SERVER['PHP_SELF'].'?ModuleName=ray', 'Ray');
		}
		// end of Ray support

		switch (count($modulesLinks))
		{
			case 0:
				showError(_t('_No modules of this type installed', $moduleType)); // ARGUMENT!
				break;
			case 1:
				// Do not ask member if there is only one module
				Redirect($modulesLinks[0]->linkReference, null, 'post');
				break;
			default:
				showLinksList($pageIndex, _t('_Module selection'), _t('Choose module to log in'), $modulesLinks);
		}
	}
	else
	{
		// User should select certain module type
		$resModulesTypes = db_res("SELECT DISTINCT `Type`
			 				  	   FROM `Modules`");
		$modulesTypesLinks = array();
		while ($arrModulesType = mysql_fetch_array($resModulesTypes))
		{
			$modulesTypesLinks[] = new CHyperLink($_SERVER['PHP_SELF'].'?ModuleType='.$arrModulesType['Type'],
				$arrModulesType['Type']);
		}

		switch (count($modulesTypesLinks))
		{
			case 0:
				showError(_t('_No modules found', $moduleType)); // ARGUMENT!
				break;
			case 1:
				// Do not ask member if there is only one type of modules
				Redirect($modulesTypesLinks[0]->linkReference, null, 'post');
				break;
			default:
				showLinksList($pageIndex, _t('_Module type selection'), _t('_Choose module type'), $modulesTypesLinks);
		}
	}
}

?>