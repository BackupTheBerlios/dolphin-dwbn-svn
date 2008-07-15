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

$sCurrentLanguage = getCurrentLangName();
if( !$sCurrentLanguage ) {
	echo '<br /><b>Fatal error:</b> Cannot apply localization.';
	exit;
}

require_once( "{$dir['root']}langs/lang-{$sCurrentLanguage}.php" );






function getCurrentLangName() {
	$sLang = '';
	
	if( !$sLang ) $sLang = tryToGetLang( $_GET['lang'], true );
	if( !$sLang ) $sLang = tryToGetLang( $_POST['lang'], true );
	if( !$sLang ) $sLang = tryToGetLang( $_COOKIE['lang'] );
	if( !$sLang ) $sLang = tryToGetLang( $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
	if( !$sLang ) $sLang = tryToGetLang( getParam( 'lang_default' ) );
	if( !$sLang ) $sLang = tryToGetLang( 'en' );
	
	return $sLang;
}

function tryToGetLang( $sLangs, $bSetCookie = false ) {
	$sLangs = trim( $sLangs );
	if( !$sLangs )
		return '';
	
	$sLangs = preg_replace( '/[^a-zA-Z0-9,;-]/m', '', $sLangs ); // we do not need 'q=0.3'. we are using live queue :)
	$sLangs = strtolower( $sLangs );
	
	if( !$sLangs )
		return '';
	
	$aLangs = explode( ',', $sLangs ); // ru,en-us;q=0.7,en;q=0.3 => array( 'ru' , 'en-us;q=0.7' , 'en;q=0.3' );
	foreach( $aLangs as $sLang ) {
		if( !$sLang ) continue;
		
		list( $sLang ) = explode( ';', $sLang, 2 ); // en-us;q=0.7 => en-us
		if( !$sLang ) continue;
		
		// check with country
		if( checkLangExists( $sLang ) ) {
			if( $bSetCookie )
				setLangCookie( $sLang );
			return $sLang;
		}
		
		//drop country
		list( $sLang, $sCntr ) = explode( '-', $sLang, 2 ); // en-us => en
		if( !$sLang or !$sCntr ) continue; //no lang or nothing changed
		
		//check again. without country
		if( checkLangExists( $sLang ) ) {
			if( $bSetCookie )
				setLangCookie( $sLang );
			return $sLang;
		}
	}
	
	return '';
}

function checkLangExists( $sLang ) {
	global $dir;
	
	if( file_exists( "{$dir['root']}langs/lang-{$sLang}.php" ) )
		return true;
	
	$sQuery = "SELECT `ID` FROM `LocalizationLanguages` WHERE `Name` = '$sLang'";
	$iLangID = (int)db_value( $sQuery );
	
	if( !$iLangID )
		return false;
	
	if( compileLanguage( $iLangID ) )
		return true;
	
	return false;
}

function setLangCookie( $sLang ) {
	setcookie( 'lang', '',     time() - 60*60*24,    '/' );
	setcookie( 'lang', $sLang, time() + 60*60*24*365, '/' );
}

function getLangSwitcher() {
	global $sCurrentLanguage;
	global $site;
	
	$aLangs = getLangsArr(true);
	if( count( $aLangs ) < 2 )
		return '';

	$sCurLink = $_SERVER['REQUEST_URI'];
	$sCurLink = preg_replace( '/[\?&]lang=[a-z-]{2,5}/i', '', $sCurLink ); //remove old lang from uri
	$sCurLink .= strpos( $sCurLink, '?', 1 ) ? '&' : '?';
	
	$sRet = '';
	foreach( $aLangs as $sName => $sLang ) {
		$sTitle = htmlspecialchars( substr( $sLang, 0, -2 ) );
		$sFlag  = $site['flags'] . substr( $sLang, -2 ) . '.gif';
		
		if( $sCurrentLanguage == $sName ) {
			$sRet .= '
				<img class="lang_selected" src="' . $sFlag . '" alt="' . $sTitle . '" title="' . $sTitle . '" />';
		} else {
			$sLink = $sCurLink . 'lang=' . $sName;
			$sRet .= <<<EOF
<a href="{$sLink}" title="{$sTitle}">
	<img class="lang_not_selected" src="{$sFlag}" alt="{$sTitle}" title="{$sTitle}" /></a>
EOF;
		}
	}
	
	return $sRet;
}

function getLangsArr( $bAddFlag = false, $bRetIDs = false ) {
	$rLangs = db_res('SELECT * FROM `LocalizationLanguages` ORDER BY `Title` ASC');
	
	$aLangs = array();
	while( $aLang = mysql_fetch_assoc($rLangs) ) {
		$sFlag = '';
		$sFlag = $bAddFlag ? ( $aLang['Flag'] ? $aLang['Flag'] : 'xx' ) : '';
		
		$sKey = ($bRetIDs) ? $aLang['ID'] : $aLang['Name'];
		$aLangs[ $sKey ] = $aLang['Title'] . $sFlag;
	}
	
	return $aLangs;
}

function deleteLanguage($langID = 0) {
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

function getLocalizationKeys() {
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

function getLocalizationStringParams($keyID) {
	$keyID = (int)$keyID;

	$resParams = mysql_query("
		SELECT	`IDParam`,
				`Description`
		FROM	`LocalizationStringParams`
		WHERE	`IDKey` = $keyID
		ORDER BY `IDParam`
	");

	$arrParams = array();

	while ($arr = mysql_fetch_assoc($resParams))
	{
		$arrParams[(int)$arr['IDParam']] = $arr['Description'];
	}

	return $arrParams;
}

function getLocalizationCategories() {
	$resCategories = db_res('SELECT `ID`, `Name` FROM `LocalizationCategories` ORDER BY `Name`');

	$arrCategories = array();

	while ($arr = mysql_fetch_assoc($resCategories))
	{
		$arrCategories[$arr['ID']] = $arr['Name'];
	}

	return $arrCategories;
}

function compileLanguage($langID = 0) {
	global $dir;

	$langID = (int)$langID;

	$newLine = "\r\n";
	
	if($langID <= 0) {
		$resLangs = db_res('SELECT `ID`, `Name` FROM `LocalizationLanguages`');
	} else {
		$resLangs = db_res('
			SELECT	`ID`, `Name`
			FROM	`LocalizationLanguages`
			WHERE	`ID` = '.$langID
		);
	}

	if ( mysql_num_rows($resLangs) <= 0 )
		return false;

	while($arrLanguage = mysql_fetch_assoc($resLangs)) {
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

function addStringToLanguage($langKey, $langString, $langID = -1, $categoryID = LANGUAGE_CATEGORY_ID_PROFILE_FIELDS) {
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

function updateStringInLanguage($langKey, $langString, $langID = -1) {
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

function _t_action( $str, $arg0 = "", $arg1 = "", $arg2 = "" ) {
    return "<div class=\"action\"><div>"._t($str,$arg0,$arg1,$arg2)."</div></div>";
}

function _t_echo_action( $str, $arg0 = "", $arg1 = "", $arg2 = "" ) {
    echo "<div class=\"action\"><div>"._t($str,$arg0,$arg1,$arg2)."</div></div>";
}

function echo_t_err( $str, $arg0 = "", $arg1 = "", $arg2 = "" ) {
    echo "<div class=\"err\"><div>"._t($str,$arg0,$arg1,$arg2)."</div></div>";
}

function _t_err( $str, $arg0 = "", $arg1 = "", $arg2 = "" ) {
    return '<div class="err"><div>' . _t( $str, $arg0, $arg1, $arg2 ) . '</div></div>';
}

function _t($key, $arg0 = "", $arg1 = "", $arg2 = "") {
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

function _t_ext($key, $args) {
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
