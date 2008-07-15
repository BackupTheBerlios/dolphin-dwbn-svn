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

require_once('header.inc.php');
require_once('db.inc.php');
require_once('utils.inc.php');

define('LANGUAGE_CATEGORY_ID_PROFILE_FIELDS', 24);

$langHTMLCharset = '';
$currentLanguage = '';
$currentLanguageFile = '';

function setCurrentLanguage()
{
	global $langHTMLCharset;
	global $currentLanguage;
	global $currentLanguageFile;

	function langFile($language)
	{
		global $dir;
		return $dir['root'].'langs/lang-'.$language.'.php';
	}

	function assureCompiled($language)
	{
		if (file_exists(langFile($language)))
		{
			return true;
		}
		else
		{
			$language = get_magic_quotes_gpc() ? $language : addslashes($language);
			$langID = mysql_query("SELECT `ID` FROM `LocalizationLanguages` WHERE `Name` = '$language'");

			if ( mysql_num_rows($langID) <= 0) return false;


			$langID = mysql_fetch_row($langID);
			$langID = $langID[0];


			return compileLanguage($langID);
		}
	}

	$LANG_SWITCHING_ENABLED = (int)getParam( "lang_enable" );

	$defaultLanguage = getParam('lang_default');

	if ( $LANG_SWITCHING_ENABLED )
	{
		$currentLanguage = (isset($_POST['new_sLanguage']) ? $_POST['new_sLanguage'] : $_GET['new_sLanguage']);

		//check if a new language is set via get/post variables

		if ( strlen($currentLanguage) > 0 )
		{
			//set a cookie with a new current language value

			setcookie( "sLanguage", '', time() - 3600, "/" );
			setcookie( "sLanguage", $currentLanguage, time() + 10000 * 3600, "/" );
		}
		elseif ( isset($_COOKIE['sLanguage']) )
		{
			//get current language from a previously set cookie

			$currentLanguage = $_COOKIE['sLanguage'];
		}
		else
		{
			//set current language to default one

			$currentLanguage = $defaultLanguage;
		}
	}
	else
	{
		$currentLanguage = $defaultLanguage;
	}

	if ( !assureCompiled($currentLanguage) )
	{
		//current language is not available

		if ( $currentLanguage == $defaultLanguage || !assureCompiled($defaultLanguage) )
		{
			//current language is a default one or the default one is not available also

			echo '<font color="red">Error: Could not compile the default language. The script cannot execute any further.</font>';
			exit();
		}
		else
		{
			//default language is available

			$currentLanguage = $defaultLanguage;
		}
	}

	$langHTMLCharset = 'UTF-8';

	$currentLanguageFile = langFile($currentLanguage);
}

setCurrentLanguage();

require_once($currentLanguageFile);

/**
 * Put language switching code : txt links
 **/
function lang_select_txt()
{
    global $currentLanguage;
    global $site;

    $arrLangs = getLocalizationLanguages('1');
    
    if ( count( $arrLangs ) < 2 )
        return false;

	// Generate list of GET parameters that should be appended to language link

	$params = '';

	foreach ( $_GET as $key => $value )
	{
		if ( $key != 'new_sLanguage' )
		{
			if ( get_magic_quotes_gpc() )$value = stripslashes($value);

			$params .= htmlspecialchars("&$key=$value");
		}
	}

    foreach($arrLangs as $value)
    {
    	$sName = substr($value,0,strlen($value)-2);
    	$sFlag = substr($value,-2);
    	
    	$htmlValue = htmlspecialchars($sName);
    	$sIcon = "<img alt=\"$sName\" title=\"$sName\" src=\"".$site['flags'].$sFlag.".gif\">";
    	
        if ( !strcmp( $currentLanguage, $sName ) )
			echo "<img alt=\"$sName\" title=\"$sName\" src=\"".$site['flags'].$sFlag.".gif\" style=\"margin-left:5px;\">";
		else
			echo '<a href="'.
				$_SERVER['PHP_SELF'].
				'?new_sLanguage='.
				$htmlValue.$params.'" class="menu_item_block" title="'.$sName.'">'.$sIcon.'</a>';
	}
}

function deleteLanguage($langID = 0)
{
	global $dir;

	$langID = (int)$langID;

	if($langID <= 0) return false;

	$resLangs = mysql_query('
			SELECT	`ID`, `Name`
			FROM	`LocalizationLanguages`
			WHERE	`ID` = '.$langID);

	if(mysql_num_rows($resLangs) <= 0) return false;

	$arrLang = mysql_fetch_assoc($resLangs);

	$numStrings = mysql_query('
		SELECT COUNT(`IDKey`)
		FROM `LocalizationStrings`
		WHERE `IDLanguage` = '.$langID);
	$numStrings = mysql_fetch_row($numStrings);
	$numStrings = $numStrings[0];

	mysql_query('DELETE FROM `LocalizationStrings` WHERE `IDLanguage` = '.$langID);

	if(mysql_affected_rows() < $numStrings) return false;

	mysql_query('DELETE FROM `LocalizationLanguages` WHERE `ID` = '.$langID);

	if(mysql_affected_rows() <= 0) return false;

	@unlink($dir['root'].'langs/lang-'.$arrLang['Name'].'.php');

	return true;
}

function getLocalizationLanguages($sWithFlag = '')
{
	$resLangs = db_res('SELECT `ID`, `Name`, `Flag` FROM `LocalizationLanguages` ORDER BY `Name` ASC');

	$arrLangs = array();

	while ($arr = mysql_fetch_assoc($resLangs))
	{
		$sFlag = '';
		if ($sWithFlag == '1')
		{
			$sFlag = strlen($arr['Flag']) ? $arr['Flag'] : 'xx' ;
		}
		$arrLangs[$arr['ID']] = $arr['Name'].$sFlag;
	}

	return $arrLangs;
}

function getLocalizationKeys()
{
	$resKeys = db_res('SELECT `ID`, `IDCategory`, `Key` FROM `LocalizationKeys`');

	$arrKeys = array();

	while($arr = mysql_fetch_assoc($resKeys))
	{
		$ID = $arr['ID'];
		unset($arr['ID']);
		$arrKeys[$ID] = $arr;
	}

	return $arrKeys;
}

function getLocalizationStringParams($keyID)
{
	$keyID = (int)$keyID;

	$resParams = mysql_query("
		SELECT	`IDParam`,
				`Description`

		FROM	`LocalizationStringParams`

		WHERE	`IDKey` = $keyID

		ORDER BY `IDParam`");

	$arrParams = array();

	while ($arr = mysql_fetch_assoc($resParams))
	{
		$arrParams[(int)$arr['IDParam']] = $arr['Description'];
	}

	return $arrParams;
}

function getLocalizationCategories()
{
	$resCategories = db_res('SELECT `ID`, `Name` FROM `LocalizationCategories` ORDER BY `Name`');

	$arrCategories = array();

	while ($arr = mysql_fetch_assoc($resCategories))
	{
		$arrCategories[$arr['ID']] = $arr['Name'];
	}

	return $arrCategories;
}

function compileLanguage($langID = 0)
{
	global $dir;

	$langID = (int)$langID;

	$newLine = "\r\n";

	if($langID <= 0)
	{
		$resLangs = db_res('SELECT `ID`, `Name` FROM `LocalizationLanguages`');
	}
	else
	{
		$resLangs = db_res('
			SELECT	`ID`, `Name`
			FROM	`LocalizationLanguages`
			WHERE	`ID` = '.$langID);
	}

	if ( mysql_num_rows($resLangs) <= 0 ) return false;

	while($arrLanguage = mysql_fetch_assoc($resLangs))
	{
		$resKeysStrings = db_res("
			SELECT	`LocalizationKeys`.`Key` AS `Key`,
					`LocalizationStrings`.`String` AS `String`
			FROM	`LocalizationStrings` INNER JOIN
					`LocalizationKeys` ON
					`LocalizationKeys`.`ID` = `LocalizationStrings`.`IDKey`
			WHERE `LocalizationStrings`.`IDLanguage` = {$arrLanguage['ID']}");

		$handle = fopen("{$dir['root']}langs/lang-{$arrLanguage['Name']}.php", 'w');

		if($handle === false) return false;

		$fileContent = "<?{$newLine}\$LANG = array(";

		while($arrKeyString = mysql_fetch_assoc($resKeysStrings))
		{
			$langKey = str_replace("\\", "\\\\", $arrKeyString['Key']);
			$langKey = str_replace("'", "\\'", $langKey);

			$langStr = str_replace("\\", "\\\\", $arrKeyString['String']);
			$langStr = str_replace("'", "\\'", $langStr);

			$fileContent .= "{$newLine}\t'$langKey' => '$langStr',";
		}

		$fileContent = trim($fileContent, ',');

		$writeResult = fwrite($handle, $fileContent."{$newLine});?>");
		if($writeResult === false) return false;

		if(fclose($handle) === false) return false;

		@chmod("{$dir['root']}langs/lang-{$arrLanguage['Name']}.php", 0666);
	}

	return true;
}

function addStringToLanguage($langKey, $langString, $langID = -1, $categoryID = LANGUAGE_CATEGORY_ID_PROFILE_FIELDS)
{
	// input validation
	$langID = (int)$langID;
	$categoryID = (int)$categoryID;

	if ( $langID == -1 )
	{
		$resLangs = db_res('SELECT `ID`, `Name` FROM `LocalizationLanguages`');
	}
	else
	{
		$resLangs = db_res('
			SELECT	`ID`, `Name`
			FROM	`LocalizationLanguages`
			WHERE	`ID` = '.$langID);
	}

	$langKey = addslashes($langKey);
	$langString = process_db_input($langString);

	$resInsertKey = db_res( "
		INSERT INTO	`LocalizationKeys`
		SET			`IDCategory` = $categoryID,
					`Key` = '$langKey'", false );
	if ( !$resInsertKey || mysql_affected_rows() <= 0 )
		return false;

	$keyID = mysql_insert_id();

	while($arrLanguage = mysql_fetch_assoc($resLangs))
	{
		$resInsertString = db_res( "
			INSERT INTO	`LocalizationStrings`
			SET			`IDKey` = $keyID,
						`IDLanguage` = {$arrLanguage['ID']},
						`String` = '$langString'", false );
		if ( !$resInsertString || mysql_affected_rows() <= 0 )
			return false;
	}

	return true;
}

function updateStringInLanguage($langKey, $langString, $langID = -1)
{
	// input validation
	$langID = (int)$langID;

	if ( $langID == -1 )
	{
		$resLangs = db_res('SELECT `ID`, `Name` FROM `LocalizationLanguages`');
	}
	else
	{
		$resLangs = db_res('
			SELECT	`ID`, `Name`
			FROM	`LocalizationLanguages`
			WHERE	`ID` = '.$langID);
	}

	$langKey = addslashes($langKey);
	$langString = process_db_input($langString);

	$arrKey = db_arr( "
		SELECT	`ID`
		FROM	`LocalizationKeys`
		WHERE	`Key` = '$langKey'", false );

	if ( !$arrKey )
		return false;

	$keyID = $arrKey['ID'];

	while($arrLanguage = mysql_fetch_assoc($resLangs))
	{
		$resUpdateString = db_res( "
			UPDATE	`LocalizationStrings`
			SET			`String` = '$langString'
			WHERE		`IDKey` = $keyID
			AND			`IDLanguage` = {$arrLanguage['ID']}", false );
		if ( !$resUpdateString || mysql_affected_rows() <= 0 )
			return false;
	}

	return true;
}

function _t_action( $str, $arg0 = "", $arg1 = "", $arg2 = "" )
{
        return "<div class=\"action\"><div>"._t($str,$arg0,$arg1,$arg2)."</div></div>";
}

function _t_echo_action( $str, $arg0 = "", $arg1 = "", $arg2 = "" )
{
        echo "<div class=\"action\"><div>"._t($str,$arg0,$arg1,$arg2)."</div></div>";
}

function echo_t_err( $str, $arg0 = "", $arg1 = "", $arg2 = "" )
{
        echo "<div class=\"err\"><div>"._t($str,$arg0,$arg1,$arg2)."</div></div>";
}

function _t_err( $str, $arg0 = "", $arg1 = "", $arg2 = "" )
{
    return '<div class="err"><div>' . _t( $str, $arg0, $arg1, $arg2 ) . '</div></div>';
}

function _t($key, $arg0 = "", $arg1 = "", $arg2 = "")
{
	global $LANG;

	if(isset($LANG[$key]))
	{
		$str = $LANG[$key];
		$str = str_replace('{0}', $arg0, $str);
		$str = str_replace('{1}', $arg1, $str);
		$str = str_replace('{2}', $arg2, $str);
		return $str;
	}
	else
	{
		return $key;
	}
}

function _t_ext($key, $args)
{
	global $LANG;

	if(isset($LANG[$key]))
	{
		$str = $LANG[$key];

		if(!is_array($args))
		{
			return str_replace('{0}', $args, $str);
		}

		foreach ($args as $key => $val)
		{
			$str = str_replace('{'.$key.'}', $val, $str);
		}

		return $str;
	}
	else
	{
		return $key;
	}
}
?>
