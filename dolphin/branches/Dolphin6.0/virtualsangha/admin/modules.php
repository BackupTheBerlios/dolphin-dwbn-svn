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
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'modules.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$ADMIN = member_auth( 1 );
$logged['admin'] = $ADMIN;

$_page['header'] = "Modules";
$_page['header_text'] = "Modules";

TopCodeAdmin();

/* Interface functions */

function PrintModulesListBlock()
{
	$res = db_res("SELECT * FROM `Modules` ORDER BY `Type` ASC");

	if (! $res)
		return;
?>
	<table cellspacing="1" cellpadding="2" class="small" width="100%">
<?

	if (!mysql_num_rows($res))
	{
?>
			<tr class="panel"><td align="center"> No modules available </td></tr>
<?
	}

	while ($arr = mysql_fetch_array($res))
	{
?>
			<form method="post" name="Module<?=$arr['ID']?>">
				<input type="hidden" name="conf_id" value="<?=$arr['ID']?>">
			</form>
			<tr class="panel">
				<td align="center" width="25%">
					<a href="javascript: void(0);" onClick="javascript: document.forms['Module<?=$arr['ID']?>'].submit(); return false;">Configure</a> | <a href="modules.php?delete_id=<?=$arr['ID']?>"> Delete </a>
				</td>
				<td aling="left">&nbsp;<?=$arr['Name']?></td>
			</tr>
<?
	}
?>
	</table>
<?
}

function PrintCommonModulesActionsBlock()
{
?>
<br />
<br />

<table cellspacing="2" cellpadding="4" width="500" align="center" class="text">
	<tr class="panel">
	    <td align="left" width="396" class="small1">Refresh all modules after settings were changed:</td>
	    <td align="center" width="100">
	    	<form method="post" enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF']; ?>" style="margin: 0px;">
	    		<input type="hidden" name="MODULES_REFRESH" value="YES" />
	    		<input class="text" type="submit" value="Refresh" style="width: 80px;" />
	    	</form>
	    </td>
	</tr>
	<tr class="panel">
    	<td align="left" width="396" class="small1">Compare databases (for phpBB module only):</td>
    	<td align="center" width="100">
    		<form method="post" action="<?=$_SERVER['PHP_SELF']?>" style="margin: 0px;">
    			<input type="hidden" name="MODULES_COMPARE_DATABASES" value="YES" />
    			<input class="text" type="submit" value="Compare" style="width: 80px;" />
    		</form>
    	</td>
	</tr>
	<tr class="panel">
		<td colspan="2" align="left">
			<form method="post" enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF']; ?>" style="margin: 0px;">
				<input type="hidden" name="MODULE_UPLOAD" value="YES" />
				<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="text" bgcolor="#FFFFFF">
					<tr class="panel">
						<td align="left" class="small1">Upload new module:</td>
						<td align="right" style="padding-left: 4px; padding-right: 10px;"><input class="no" type="file" name="file_module" /></td>
						<td align="center" width="100"><input class=text type="submit" value="Upload" style="width: 80px;" /></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
</table>
<br />
<?
}

function PrintModuleConfigBlock()
{
	$conf_arr = db_arr("SELECT `Conf`, `Name` FROM Modules WHERE ID = '{$_POST['conf_id']}' LIMIT 1;");
?>
<form method=post action="<?=$_SERVER['PHP_SELF']?>">

	<table cellspacing=1 cellpadding=1 class="text" width=100%>
		<tr>
			<td colspan=2 class="text">&nbsp; Configure module: <b> <?=$conf_arr['Name']?></b> </td>
		</tr>

		<tr class="panel">
			<td><textarea name=text rows=30 cols=60><?=htmlspecialchars($conf_arr['Conf'])?></textarea> </td>
			<td valign="top" class="small">
				<div class=err style="padding: 5px;" >
					Edit text carefully
					<br /><br />
					lines that begin with "#" are comments
				</div>
			</td>
		</tr>

		<tr class=panel>
			<td align=center colspan=2>
				<input type=hidden name=CONF_ID value="<?=((int)$_POST['conf_id'])?>">
				<input class=text type=submit value='Update'>
			</td>
		</tr>

	</table>
</form>
<?
}

/* Modules management functions */

function ModuleDelete()
{
    global $demo_mode;

    if ( $demo_mode ) return 0;

    $_GET['delete_id'] = (int)$_GET['delete_id'];

    $res = db_res( "DELETE FROM Modules WHERE ID={$_GET['delete_id']}" );

    return $res;
}

function ModuleUpload()
{
    global $result;
    global $dir;

    $tmp_file = "{$dir['tmp']}module.tmp";

    if ( move_uploaded_file( $_FILES['file_module']['tmp_name'], $tmp_file ) )
    {

        if ( !($f = fopen ( $tmp_file, "r" )) )
        {
            $result .= "<font color=red>Could not open file with sql instructions: $tmp_file </font>";
            unlink($tmp_file);
            return 0;
        }

        // run mysql inctructions
        while ( $s = fgets ( $f, 10240) )
        {
            $s = trim ($s);
            if ( $s[0] == '#' ) continue;
            if ( !strlen($s) ) continue;


            if ( $s[strlen($s)-1] == ';' )
            {
                $s_sql .= $s;
            }
            else
            {
                $s_sql .= $s;
                continue;
            }

            $res = db_res ( $s_sql, 0 );
            if ( !$res )
            {
                $result.="<b>Error</b> <br /><pre>".mysql_error()."</pre><hr>";
            }
            $s_sql = "";
        }

        fclose($f);

        unlink($tmp_file);

        if ( strlen($result) ) return 0;
        return 1;
    }
    else
        return 0;
}

function ModuleConfigure()
{
    global $demo_mode;

    if ( $demo_mode ) return 0;

    $_POST['CONF_ID'] = (int)$_POST['CONF_ID'];

    $res = db_res( "UPDATE Modules SET `Conf`='". process_db_input($_POST['text'], 1). "' WHERE ID = {$_POST['CONF_ID']}" );

    return $res;
}

/**
 * Compares profile tables of Dolphin and phpBB. Shows result of comparison
 *
 * @return int
 * 		0 - Tables compared successfully, No difference found.
 * 		1 - Tables compared successfully, there have been found profiles that are not in phpBB users table.
 * 		<other> - An error occured while comparing.
 */
function ModuleCompareDatabases()
{
	function debugPrintProfileInfo($ID, $nickname, $email, $registeredEmail, $status)
	{
		global $bottom_result;
		global $site;
		static $ColoredRow = false;

		$ColoredRow = !$ColoredRow;

		$HTMLcode = "<tr bgcolor=\"".(($ColoredRow) ? '#EEEEEE' : '#FFFFFF')."\" height=\"20\">
						<td>
							<a href=\"{$site['url']}profile_edit.php?ID=".addslashes($ID)."\" target=\"_blank\"> [{$ID}] </a>
						</td>
						<td>
							[".addslashes(htmlspecialchars($nickname))."]
						</td>
						<td>
							[".addslashes(htmlspecialchars($email))."]
						</td>
						<td>
							[".addslashes(htmlspecialchars($registeredEmail))."]
						</td>
						<td>
							".addslashes(htmlspecialchars($status))."
						</td>
					</tr>";
		$bottom_result .= $HTMLcode;
	}

	global $mods;
	global $bottom_result;
	global $site;

	$bottom_result .= '<table>
		<tr>
			<td align="center" colspan="5">List of profiles not included into module database:</td>
		</tr>
		<tr style="font-weight:bold">
			<td>ID</td>
			<td>Password</td>
			<td>Email</td>
			<td>Email of nickname owner</td>
			<td>Status</td>
		</tr>';

	$mysqlLink = mysql_pconnect($mods['phpbb']['Database']['Host'],
							$mods['phpbb']['Database']['Username'],
							$mods['phpbb']['Database']['Password']);

	if (! $mysqlLink)
		modules_err("ModuleCompareDatabases error:\n mysql_pconnect\n\n".mysql_error($mysqlLink));

	mysql_select_db($mods['phpbb']['Database']['Name'], $mysqlLink) or
		modules_err("ModuleCompareDatabases error:\n mysql_select_db({$mods['phpbb']['Database']['Name']}\n\n)".mysql_error($mysqlLink));

	$queryGetAllUsers = "SELECT `username`,
								`user_email`
						 FROM {$mods['phpbb']['Database']['TablePrefix']}users";
	$dbresult = mysql_query($queryGetAllUsers, $mysqlLink) or modules_err("ModuleCompareDatabases error: mysql_query({$queryGetAllUsers})\n\n".mysql_error($mysqlLink));
	if (! $dbresult)
		modules_err("ModuleCompareDatabases() error:\n empty result on query {$queryGetAllUsers} \n\n".mysql_error($mysqlLink));

	$phpBBUsers = array();
	while ($row = mysql_fetch_assoc($dbresult))
	{
		$phpBBUsers[strtolower($row['username'])] = $row['user_email'];
	}

	$dbresult = db_res("SELECT `Status`, `ID`, `NickName`, `Email` FROM `Profiles`");
	$missedProfiles = array();
	while ($profileInfo = mysql_fetch_assoc($dbresult))
	{
		if (! array_key_exists(strtolower($profileInfo['NickName']), $phpBBUsers) ||
			$profileInfo['Email'] != $phpBBUsers[strtolower($profileInfo['NickName'])])
		{
			$profileInfo['RegisteredEMail'] = $phpBBUsers[strtolower($profileInfo['NickName'])];
			$missedProfiles[] = $profileInfo;
		}
	}
	sort($missedProfiles);

	foreach ($missedProfiles as $profile)
	{
		debugPrintProfileInfo($profile['ID'], $profile['NickName'], $profile['Email'], $profile['RegisteredEMail'], $profile['Status']);
	}

	$bottom_result .= "</table><br />";

	return (count($missedProfiles) > 0) ? 1 : 0;
}

/* Output */

ContentBlockHead("");

$result = '';

if ( $_POST['MODULE_UPLOAD'] == "YES" && $_FILES['file_module']['name'] )
{
    if ( ModuleUpload() )
        $result .= "Module was uploaded";
    else
        $result .= "Module upload failed";
}

if ( $_POST['MODULES_REFRESH'] == "YES" )
{
    modulesRefresh();
    $result .= "All profiles were updated.";
}

if ( (int)$_POST['CONF_ID'] != 0 && $_POST['text'] )
{
    if ( ModuleConfigure() )
        $result .= "Module was configured";
    else
        $result .= "Module configuration failed";
}

if ( ((int)($_GET['delete_id'])) != 0 )
{
    if ( ModuleDelete() )
        $result .= "Module was deleted";
    else
        $result .= "Module deletion failed";
}

if (strlen($result))
{
?>
	<center>
		<div class="err"><?= $result ?></div>
	</center>
	<br />
<?
}

PrintModulesListBlock();

if (((int)$_POST['conf_id']) != 0)
{
	PrintModuleConfigBlock();
}
else
{
	PrintCommonModulesActionsBlock();
}

$bottom_result = '';

if ($_POST['MODULES_COMPARE_DATABASES'] == "YES")
{
	switch (ModuleCompareDatabases())
	{
		case 0:
			$bottom_result .= 'Comparing completed successfully. No difference found.';
			break;
		case 1:
			$bottom_result .= 'Comparison completed. There have been found profiles that are not in phpBB users table.';
			break;
		default:
			$bottom_result .= 'An error was occured while comparing.';
	};
}

ContentBlockFoot();

if (strlen($bottom_result))
{
?>
	<center>
		<div class="err"><?= $bottom_result ?></div>
	</center>
	<br />
<?
}

BottomCode();

?>