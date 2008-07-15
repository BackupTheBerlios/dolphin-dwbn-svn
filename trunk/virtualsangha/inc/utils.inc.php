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

/*
 * Common functions
 */
function PrintErrorPageCode( $errorText )
{
	ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Error</title>

		<style type="text/css">
			div.error_container
			{
				margin-top: 2px;
				margin-left: auto;
				margin-right: auto;
				width: 400px;
			}
			div.error_head
			{
				font-weight: bold;
				font-family: Arial, Helvetica, sans-serif;
				font-size: 11px;
				padding: 2px;
				margin-left: auto;
				margin-right: auto;
				margin-bottom: 2px;
				text-align: left;
				color: white;
				background-color: red;
				width: 100%;
				border: 1px solid red;
			}
			div.error_body
			{
				font-weight: normal;
				font-family: Arial, Helvetica, sans-serif;
				font-size: 11px;
				padding: 2px;
				margin-left: auto;
				margin-right: auto;
				text-align: left;
				color: black;
				background-color: white;
				width: 100%;
				border: 1px solid red;
			}
		</style>

	</head>
	<body>

		<div class="error_container">
			<div class="error_head">Error</div>
			<div class="error_body"><?= $errorText ?></div>
		</div>

	</body>
</html>
<?

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

/*
 * Print error page with message
 */
function PrintErrorPage( $errorText )
{
	echo PrintErrorPageCode( $errorText );
}

/*
 * function for work with profile
 */
function is_friends($id1, $id2)
{
	$cnt = db_arr("SELECT SUM(`Check`) AS 'cnt' FROM `FriendList` WHERE `ID`='{$id1}' AND `Profile`='{$id2}' OR `ID`='{$id2}' AND `Profile`='{$id1}'");
	return ($cnt['cnt'] > 0 ? true : false);
}

/*
 * functions for limiting maximal word length
 */
function strmaxwordlen($input, $len = 100)
{
	return $input;
}

/*
 * functions for limiting maximal text length
 */
function strmaxtextlen($input, $len = 60)
{
	if ( strlen($input) > $len )
		return mb_substr($input, 0, $len - 4) . "...";
	else
		return $input;
}

function html2txt($content, $tags = "")
{
	while($content != strip_tags($content, $tags))
	{
		$content = strip_tags($content, $tags);
	}

	return $content;
}

function html_encode($text)
{
     $searcharray =  array(
    "'([-_\w\d.]+@[-_\w\d.]+)'",
    "'((?:(?!://).{3}|^.{0,2}))(www\.[-\d\w\.\/]+)'",
    "'(http[s]?:\/\/[-_~\w\d\.\/]+)'");

    $replacearray = array(
    "<a href=\"mailto:\\1\">\\1</a>",
    "\\1http://\\2",
    "<a href=\"\\1\" target=_blank>\\1</a>");

   return preg_replace($searcharray, $replacearray, stripslashes($text));
}

/*
 * functions for input data into database
 */
function process_db_input( $text, $strip_tags = 0, $force_addslashes = 0 )
{
	if ( $strip_tags )
		$text = strip_tags($text);

	if ( !get_magic_quotes_gpc() || $force_addslashes )
		return addslashes($text);
	else
		return $text;
}

/*
 * function for processing pass data
 *
 * This function cleans the GET/POST/COOKIE data if magic_quotes_gpc() is on
 * for data which should be outputed immediately after submit
 */
function process_pass_data( $text, $strip_tags = 0 )
{
	if ( $strip_tags )
		$text = strip_tags($text);

	if ( !get_magic_quotes_gpc() )
		return $text;
	else
		return stripslashes($text);
}

/*
 * function for output data from database into html
 */
function htmlspecialchars_adv( $string )
{
	$patterns = array( "/(?!&#\d{2,};)&/m", "/>/m", "/</m", "/\"/m", "/'/m" );
	$replaces = array( "&amp;", "&gt;", "&lt;", "&quot;", "&#039;" );
	return preg_replace( $patterns, $replaces, $string );
}

function process_text_output( $text, $maxwordlen = 100 )
{
	return nl2br( htmlspecialchars_adv( strmaxwordlen( $text, $maxwordlen ) ) );
}

function process_textarea_output( $text, $maxwordlen = 100 )
{
	return htmlspecialchars_adv( strmaxwordlen( $text, $maxwordlen ) );
}

function process_text_withlinks_output( $text, $maxwordlen = 100 )
{
	return nl2br( html_encode( htmlspecialchars_adv( strmaxwordlen( $text, $maxwordlen ) ) ) );
}

function process_line_output( $text, $maxwordlen = 100 )
{
	return htmlspecialchars_adv( strmaxwordlen( $text, $maxwordlen ) );
}

function process_html_output( $text, $maxwordlen = 100 )
{
	return strmaxwordlen( $text, $maxwordlen );
}

/*
 * functions for work with arrays
 */
function array_stick($base_arr, $add_arr)
{
	if(is_array($base_arr) && is_array($add_arr))
	{
		foreach($add_arr as $key => $value)
		{
			$base_arr[$key] = array_merge($base_arr[$key], $value);
		}
	}
	else
	{
		print_err("Input arguments are not arrays");
	}
	return $base_arr;
}

/**
*	Used to construct sturctured arrays in GET or POST data. Supports multidimensional arrays.
*
*	@param array	$Values	Specifies values and values names, that should be submitted. Can be multidimensional.
*
*	@return string	HTML code, which contains <input type="hidden"...> tags with names and values, specified in $Values array.
*/
function ConstructHiddenValues($Values)
{
	/**
	*	Recursive function, processes multidimensional arrays
	*
	*	@param string $Name	Full name of array, including all subarrays' names
	*
	*	@param array $Value	Array of values, can be multidimensional
	*
	*	@return string	Properly consctructed <input type="hidden"...> tags
	*/
	function ConstructHiddenSubValues($Name, $Value)
	{
		if (is_array($Value))
		{
			$Result = "";
			foreach ($Value as $KeyName => $SubValue)
			{
				$Result .= ConstructHiddenSubValues("{$Name}[{$KeyName}]", $SubValue);
			}
		}
		else
			// Exit recurse
			$Result = "<input type=\"hidden\" name=\"".htmlspecialchars($Name)."\" value=\"".htmlspecialchars($Value)."\" />\n";

		return $Result;
	}
	/* End of ConstructHiddenSubValues function */


	$Result = '';
	if (is_array($Values))
	{
		foreach ($Values as $KeyName => $Value)
		{
			$Result .= ConstructHiddenSubValues($KeyName, $Value);
		}
	}

	return $Result;
}

/**
*	Returns HTML/javascript code, which redirects to another URL with passing specified data (through specified method)
*
*	@param string	$ActionURL	destination URL
*
*	@param array	$Params	Parameters to be passed (through GET or POST)
*
*	@param string	$Method	Submit mode. Only two values are valid: 'get' and 'post'
*
*	@return mixed	Correspondent HTML/javascript code or false, if input data is wrong
*/
function RedirectCode($ActionURL, $Params = NULL, $Method = "get", $Title = 'Redirect')
{
	if ((strcasecmp(trim($Method), "get") && strcasecmp(trim($Method), "post")) || (trim($ActionURL) == ""))
		return false;

	ob_start();

?>
<html>
	<head>
		<title><?= $Title ?></title>
	</head>
	<body>
		<form name="RedirectForm" action="<?= htmlspecialchars($ActionURL) ?>" method="<?= $Method ?>">

<?= ConstructHiddenValues($Params) ?>

		</form>
		<script type="text/javascript">
			<!--
			document.forms['RedirectForm'].submit();
			-->
		</script>
	</body>
</html>
<?

	$Result = ob_get_contents();
	ob_end_clean();

	return $Result;
}

/**
*	Redirects browser to another URL, passing parameters through POST or GET
*	Actually just prints code, returned by RedirectCode (see RedirectCode)
*/
function Redirect($ActionURL, $Params = NULL, $Method = "get", $Title = 'Redirect')
{
	$RedirectCodeValue = RedirectCode($ActionURL, $Params, $Method, $Title);
	if ($RedirectCodeValue !== false)
		echo $RedirectCodeValue;
}

function ErrorHandler($errno, $errstr, $errfile, $errline)
{
    switch ($errno)
    {
    case FATAL:
            echo "<b>FATAL</b> [$errno] $errstr<br>\n";
        echo "  Fatal error in line ".$errline." of file ".$errfile;
        echo ", PHP ".PHP_VERSION." (".PHP_OS.")<br>\n";
        echo "Aborting...<br>\n";
        exit(1);
    break;
    case ERROR:
         echo "<b>ERROR</b> [$errno] $errstr<br>\n";
    break;
    case WARNING:
    //    echo "<b></b> [$errno] $errstr<br>\n";
    break;
    default:
    break;
    }

}

function isRWAccessible($filename)
{

    clearstatcache();
    $perms = fileperms($filename);
    return ( $perms & 0x0004 && $perms & 0x0002 ) ? true : false;

}

/**
 * Send email function
 *
 * @param string $sRecipientEmail		- Email where email should be send
 * @param string $sMailSubject			- subject of the message
 * @param string $sMailBody				- Body of the message
 * @param integer $iRecipientID			- ID of recipient profile
 * @param array $aPlus					- Array of additional information
 *
 *
 * @return boolean 						- trie if message was send
 * 										- false if not
 */
function sendMail( $sRecipientEmail, $sMailSubject, $sMailBody, $iRecipientID = '', $aPlus = '', $sEmailFlag = 'text' ) {
	global $site;

	if( $iRecipientID )
		$aRecipientInfo = getProfileInfo( $iRecipientID );

	$sMailHeader		= "From: =?UTF-8?B?" . base64_encode( $site['title'] ) . "?= <{$site['email_notify']}>";
	$sMailParameters	= "-f{$site['email_notify']}";

	$sMailSubject	= str_replace( "<SiteName>", $site['title'], $sMailSubject );

	$sMailBody		= str_replace( "<SiteName>", $site['title'], $sMailBody );
	$sMailBody		= str_replace( "<Domain>", $site['url'], $sMailBody );
	$sMailBody		= str_replace( "<recipientID>", $aRecipientInfo['ID'], $sMailBody );
	$sMailBody		= str_replace( "<RealName>", $aRecipientInfo['NickName'], $sMailBody );
	$sMailBody		= str_replace( "<NickName>", $aRecipientInfo['NickName'], $sMailBody );
	$sMailBody		= str_replace( "<Email>", $aRecipientInfo['Email'], $sMailBody );
	$sMailBody		= str_replace( "<Password>", $aRecipientInfo['Password'], $sMailBody );

	if( is_array($aPlus) ) {
		foreach ( $aPlus as $key => $value ) {
			$sMailBody = str_replace( '<' . $key . '>', $value, $sMailBody );
		}
	}

	$sMailSubject = '=?UTF-8?B?' . base64_encode( $sMailSubject ) . '?=';

	$sMailHeader = "MIME-Version: 1.0\r\n" . $sMailHeader;

	if( 'html' == $sEmailFlag) {
		$sMailHeader = "Content-type: text/html; charset=UTF-8\r\n" . $sMailHeader;
		$iSendingResult = mail( $sRecipientEmail, $sMailSubject, $sMailBody, $sMailHeader, $sMailParameters );
	} else {
		$sMailHeader = "Content-type: text/plain; charset=UTF-8\r\n" . $sMailHeader;
		$iSendingResult = mail( $sRecipientEmail, $sMailSubject, html2txt($sMailBody), $sMailHeader, $sMailParameters );
	}

	return $iSendingResult;
}

/*
 * Getting Array with Templates Names
*/

function get_templates_array()
{
	global $dir;

	$path = $dir['root'].'templates/';
	$templ_choices = array();
	$handle = opendir( $path );

	while ( false !== ($filename = readdir($handle)) )
	{
		if ( is_dir($path.$filename) && substr($filename, 0, 5) == 'tmpl_' )
		{
			$sTemplName = '';
			@include( $path.$filename.'/scripts/BxTemplName.php' );
			if( $sTemplName )
				$templ_choices[substr($filename, 5)] = $sTemplName;
		}
	}
	closedir( $handle );
    return $templ_choices;
}

/*
 * The Function Show a Line with Templates Names
*/

function templates_select_txt()
{
	global $dir;

	$templ_choices = get_templates_array();
	$current_template = ( strlen( $_GET['skin'] ) ) ? $_GET['skin'] : $_COOKIE['skin'];


	foreach ($templ_choices as $tmpl_key => $tmpl_value)
	{
		if ($current_template == $tmpl_key)
		{
			$ReturnResult .= $tmpl_value;
			$ReturnResult .= ' | ';
		}
		else
		{
			foreach ($_GET as $param_key => $param_value)
			{

				if ( 'skin' != $param_key ) $sGetTransfer .= "&{$param_key}={$param_value}";

			}

			$ReturnResult .= '<a href="' . $_SERVER['PHP_SELF'] . '?skin='. $tmpl_key . $sGetTransfer . '">' . $tmpl_value . '</a>';
			$ReturnResult .= ' | ';

		}
	}

	return $ReturnResult;


}

/**
 * callback function for including template files
 */
function getTemplateIncludedFile( $aFile )
{
	global $tmpl;

	// read include file

	$sFile = BX_DIRECTORY_PATH_ROOT . 'templates/tmpl_' . $tmpl . '/'. $aFile['1'];

	if( file_exists ($sFile) && is_file( $sFile ) )
	{
		$fp = fopen ($sFile, "r");
		if ($fp)
		{
			$s = fread ($fp, filesize ($sFile));
			fclose ($fp);
			return $s;
		}
	}

	return "<b>error reading {$aFile[1]}</b>";
}

function getTemplateBaseFile( $aFile )
{
	global $dir;
	global $tmpl;

	// read include file
	$sFile = BX_DIRECTORY_PATH_ROOT . 'templates/base/' . $aFile['1'];

	if (file_exists ($sFile) && is_file( $sFile ))
	{
		$fp = fopen ($sFile, "r");
		if ($fp)
		{
			$s = fread ($fp, filesize ($sFile));
			fclose ($fp);
			return $s;
		}
	}

	return "<b>error reading base {$aFile[1]}</b>";
}

function extFileExists( $sFileSrc )
{

	if( file_exists( $sFileSrc ) && is_file( $sFileSrc ) )
	{
		$ret = true;
	}
	else
	{
		$ret = false;
	}

	return $ret;
}

function extDirExists( $sDirSrc )
{

	if( file_exists( $sDirSrc ) && is_dir( $sDirSrc ) )
	{
		$ret = true;
	}
	else
	{
		$ret = false;
	}

	return $ret;
}

function getVisitorIP()
{
	$ip = '';
	if( ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) && ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) )
	{
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	elseif( ( isset( $_SERVER['HTTP_CLIENT_IP'])) && (!empty($_SERVER['HTTP_CLIENT_IP'] ) ) )
	{
		$ip = explode(".",$_SERVER['HTTP_CLIENT_IP']);
		$ip = $ip[3].".".$ip[2].".".$ip[1].".".$ip[0];
	}
	elseif((!isset( $_SERVER['HTTP_X_FORWARDED_FOR'])) || (empty($_SERVER['HTTP_X_FORWARDED_FOR'])))
	{
		if ((!isset( $_SERVER['HTTP_CLIENT_IP'])) && (empty($_SERVER['HTTP_CLIENT_IP'])))
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	}
	else
	{
		$ip = "0.0.0.0";
	}

	return $ip;
}

function genFlag( $country )
{
	global $site;
	$country = strtolower( $country );
	
	return "<img src=\"{$site['flags']}{$country}.gif\" />";
}

// print debug information ( e.g. arrays )
function echoDbg( $what, $desc = '' )
{
	if ( $desc )
		echo "<b>$desc:</b> ";
	echo "<pre>";
		print_r( $what );
	echo "</pre>\n";
}

function clear_xss($val)
{
	global $dir;
	require_once( "{$dir['plugins']}safehtml/safehtml.php" );
	$safehtml =& new safehtml();
	$res = $safehtml->parse($val);
	
	return $res;
}

function _format_when ($iSec) {
	$s = '';

	if ($iSec>0) {
		if ($iSec < 3600) {
			$i = round($iSec/60);
			if (0 == $i || 1 == $i) $s .= _t('_x_minute_ago', '1');
			else $s .= _t('_x_minute_ago', $i, 's');
		}
		else if ($iSec < 86400) {
			$i = round($iSec/60/60);
			if (0 == $i || 1 == $i) $s .= _t('_x_hour_ago', '1');
			else $s .= _t('_x_hour_ago', $i, 's');
		}
		else {
			$i = round($iSec/60/60/24);
			if (0 == $i || 1 == $i) $s .= _t('_x_day_ago', '1');
			else $s .= _t('_x_day_ago', $i, 's');
		}
	}else {
		if ($iSec > -3600) {
			$iSec = -$iSec;
			$i = round($iSec/60);
			if (0 == $i || 1 == $i) $s .= _t('_in_x_minute', '1');
			else $s .= _t('_in_x_minute', $i, 's');
		}
		else if ($iSec > -86400) {
			$iSec = -$iSec;
			$i = round($iSec/60/60);
			if (0 == $i || 1 == $i) $s .= _t('_in_x_hour', '1');
			else $s .= _t('_in_x_hour', $i, 's');
		}
		elseif ($iSec < -86400) {
			$iSec = -$iSec;
			$i = round($iSec/60/60/24);
			if (0 == $i || 1 == $i) $s .= _t('_in_x_day', '1');
			else $s .= _t('_in_x_day', $i, 's');
		}
	}
	return $s;
}

function execSqlFile( $filename )
{
    if ( !$f = fopen ( $filename, "r" ) )
    	return false;
	db_res( "SET NAMES 'utf8'" );
	
	$s_sql = "";
    while ( $s = fgets ( $f, 10240) )
    {
		$s = trim( $s ); //Utf with BOM only
		
		if( !strlen( $s ) ) continue;
        if ( mb_substr( $s, 0, 1 ) == '#'  ) continue; //pass comments
        if ( mb_substr( $s, 0, 2 ) == '--' ) continue;

		$s_sql .= $s;
		
        if ( mb_substr( $s, -1 ) != ';' ) continue;
		
        db_res( $s_sql );
		$s_sql = "";
    }

    fclose($f);
	return true;
}

function replace_full_uris( $text )
{
	$text = preg_replace_callback( '/([\s\n\r]src\=")([^"]+)(")/', 'replace_full_uri', $text );
	return $text;
}

function replace_full_uri( $matches )
{
	global $site;
	
	if( substr( $matches[2], 0, 7 ) != 'http://' and substr( $matches[2], 0, 6 ) != 'ftp://' )
		$matches[2] = $site['url'] . $matches[2];
	
	return $matches[1] . $matches[2] . $matches[3];
}

//--------------------------------------- friendly permalinks --------------------------------------//
//------------------------------------------- main functions ---------------------------------------//
function uriGenerate ($s, $sTable, $sField, $iMaxLen = 255)
{
  //$s = get_mb_replace ('/[^\pL^\pN]+/u', '-', $s); // unicode characters
  $s = get_mb_replace ('/([^\d^\w]+)/', '-', $s); // latin characters
  $s = get_mb_replace ('/([-^]+)/', '-', $s);
  if (!$s) $s = '-';

  if (uriCheckUniq($s, $sTable, $sField)) return $s;

  // try to add date

  if (get_mb_len($s) > 240)
     $s = get_mb_substr ($s, 0, 240);

  $s .= '-' . date('Y-m-d');
        
  if (uriCheckUniq($s, $sTable, $sField)) return $s;

  // try to add number

  for ($i = 0 ; $i < 999 ; ++$i)
  {        
    if (uriCheckUniq($s . '-' . $i, $sTable, $sField)) 
    {
       return ($s . '-' . $i);                
    }
  }
   return rand(0, 999999999);
}

function uriCheckUniq ($s, $sTable, $sField)
{
	return !db_arr("SELECT 1 FROM $sTable WHERE $sField = '$s' LIMIT 1");
}

function get_mb_replace ($sPattern, $sReplace, $s)
{
    return preg_replace ($sPattern, $sReplace, $s);
}

function get_mb_len ($s)
{
    if (function_exists('mb_strlen'))
        return mb_strlen ($s);
    else
        return strlen ($s);
}    

function get_mb_substr ($s, $iStart, $iLen)
{
    if (function_exists('mb_substr'))
        return mb_substr ($s, $iStart, $iLen);
    else
        return substr ($s, $iStart, $iLen);
}

/*function LocaledTime( $sDateTime="2008-04-15 16:26:27" ) {
	$sDateKey = _t('_day_of_01', $iDay, $iYear); //
	return $sDateKey;
}*/

function LocaledDataTime( $sTimestamp='', $iDType = 1 ) { //1. "April 7, 2008" "7 Апреля 2008"; 2. "22:30"; 3. "April 7, 2008" "7 Апреля 2008, 22:30"
	$aDateTime = getdate($sTimestamp);
	$sResult = '';
	if ($aDateTime['year']) {
		switch ($iDType) {
			case 1:
				$sResult .= _t('_day_of_'.$aDateTime['mon'], $aDateTime['mday'], $aDateTime['year']); //"April 7, 2008" "7 Апреля 2008"
				break;
			case 2:
				$sResult .= $aDateTime['hours'] . ':' . $aDateTime['minutes']; //"22:30"
				break;
			case 3:
				$sResult .= _t('_day_of_'.$aDateTime['mon'], $aDateTime['mday'], $aDateTime['year']) . ', ' . $aDateTime['hours'] . ':' . $aDateTime['minutes']; //"7 Апреля 2008, 22:30"
				break;
		}
	}
	return $sResult;
/*

INSERT INTO `LocalizationKeys` SET `IDCategory`='1', `Key`='_day_of_1';
SET @last_id = LAST_INSERT_ID();
INSERT INTO `LocalizationStrings` VALUES (@last_id, 1, '{0} января {1}');

INSERT INTO `LocalizationKeys` SET `IDCategory`='1', `Key`='_day_of_2';
SET @last_id = LAST_INSERT_ID();
INSERT INTO `LocalizationStrings` VALUES (@last_id, 1, '{0} февраля {1}');

INSERT INTO `LocalizationKeys` SET `IDCategory`='1', `Key`='_day_of_3';
SET @last_id = LAST_INSERT_ID();
INSERT INTO `LocalizationStrings` VALUES (@last_id, 1, '{0} марта {1}');

INSERT INTO `LocalizationKeys` SET `IDCategory`='1', `Key`='_day_of_4';
SET @last_id = LAST_INSERT_ID();
INSERT INTO `LocalizationStrings` VALUES (@last_id, 1, '{0} апреля {1}');

INSERT INTO `LocalizationKeys` SET `IDCategory`='1', `Key`='_day_of_5';
SET @last_id = LAST_INSERT_ID();
INSERT INTO `LocalizationStrings` VALUES (@last_id, 1, '{0} мая {1}');

INSERT INTO `LocalizationKeys` SET `IDCategory`='1', `Key`='_day_of_6';
SET @last_id = LAST_INSERT_ID();
INSERT INTO `LocalizationStrings` VALUES (@last_id, 1, '{0} июня {1}');

INSERT INTO `LocalizationKeys` SET `IDCategory`='1', `Key`='_day_of_7';
SET @last_id = LAST_INSERT_ID();
INSERT INTO `LocalizationStrings` VALUES (@last_id, 1, '{0} июля {1}');

INSERT INTO `LocalizationKeys` SET `IDCategory`='1', `Key`='_day_of_8';
SET @last_id = LAST_INSERT_ID();
INSERT INTO `LocalizationStrings` VALUES (@last_id, 1, '{0} августа {1}');

INSERT INTO `LocalizationKeys` SET `IDCategory`='1', `Key`='_day_of_9';
SET @last_id = LAST_INSERT_ID();
INSERT INTO `LocalizationStrings` VALUES (@last_id, 1, '{0} сентября {1}');

INSERT INTO `LocalizationKeys` SET `IDCategory`='1', `Key`='_day_of_10';
SET @last_id = LAST_INSERT_ID();
INSERT INTO `LocalizationStrings` VALUES (@last_id, 1, '{0} октября {1}');

INSERT INTO `LocalizationKeys` SET `IDCategory`='1', `Key`='_day_of_11';
SET @last_id = LAST_INSERT_ID();
INSERT INTO `LocalizationStrings` VALUES (@last_id, 1, '{0} ноября {1}');

INSERT INTO `LocalizationKeys` SET `IDCategory`='1', `Key`='_day_of_12';
SET @last_id = LAST_INSERT_ID();
INSERT INTO `LocalizationStrings` VALUES (@last_id, 1, '{0} декабря {1}');

*/
}

?>
