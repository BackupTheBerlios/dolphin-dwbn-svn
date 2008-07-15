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
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

define( 'DB_FULL_VISUAL_PROCESSING', true );
define( 'DB_FULL_DEBUG_MODE', false );
define( 'DB_DO_EMAIL_ERROR_REPORT', true );


$MySQL = new CMySQL;

if( !$MySQL->connect() )
	genMySQLErr( 'Database connect failed' );

if( !$MySQL->select_db() )
	genMySQLErr( 'Database select failed' );

db_res( "SET NAMES 'utf8'" );

$cacheParam = array();




class CMySQL
{
	var $host      = DATABASE_HOST;
	var $sock      = DATABASE_SOCK;
	var $port      = DATABASE_PORT;
	var $user      = DATABASE_USER;
	var $passwd    = DATABASE_PASS;
	var $db        = DATABASE_NAME;
	var $connected = false;
	var $link      = null;
	
	function connect()
	{
		if ( $this->connected )
			return;

		if( strlen($this->port) )
			$this->port = ":".$this->port;

		if ( strlen($this->sock) )
			$this->sock = ":".$this->sock;

		$this->link = @mysql_pconnect( $this->host . $this->port . $this->sock, $this->user, $this->passwd );

		if ( $this->link )
			$this->connected = true;

		return $this->connected;
	}

	function select_db()
	{
		return @mysql_select_db( $this->db );
	}
}

function db_list_tables( $error_checking = true )
{
	global $MySQL;

	$res = mysql_list_tables($MySQL->db);
	if ( $error_checking && !$res )
		genMySQLErr( 'Database list tables failed' );

	return $res;
}

function db_get_encoding ( $error_checking = true )
{
	global $MySQL;

	$res = mysql_client_encoding($MySQL->link);
	if ( $error_checking && !$res )
		genMySQLErr( 'Database get encoding error' );

	return $res;
}

function db_res( $query, $error_checking = true )
{
	global $MySQL;

	$res = mysql_query( $query, $MySQL->link );
	if ( $error_checking && !$res )
		genMySQLErr( 'Database query error', $query );

	return $res;
}

function db_arr( $query, $error_checking = true )
{
	$res = db_res( $query, $error_checking );
	if( !$res )
		return false;
	$arr = mysql_fetch_array( $res );
	return $arr;
}

function db_assoc_arr( $query, $error_checking = true )
{
	$res = db_res( $query, $error_checking );
	if( !$res )
		return false;
	$arr = mysql_fetch_assoc( $res );
	return $arr;
}

function db_value( $query, $error_checking = true, $index = 0 )
{
	$arr = db_arr( $query, $error_checking );
	$val = $arr[$index];
	return $val;
}

function fill_array( $res )
{
	global $MySQL;

	if (!$res)
		return false;

	$i = 0;
	$arr = array();
	while( $r = mysql_fetch_array( $res ) )
		$arr[$i++] = $r;

	return $arr;
}

function fill_assoc_array( $res )
{
	global $MySQL;

	if (!$res)
		return false;

	$i = 0;
	$arr = array();
	while( $r = mysql_fetch_assoc( $res ) )
		$arr[$i++] = $r;

	return $arr;
}

function getParam( $param_name, $use_cache = true )
{
	global $cacheParam;

	if ( $use_cache && isset($cacheParam[$param_name]) )
		return $cacheParam[$param_name];
	elseif ( !$line = db_assoc_arr( "SELECT `VALUE` FROM `GlParams` WHERE `Name` = '$param_name'" ) )
		return false;
	$cacheParam[$param_name] = $line['VALUE'];
	return $line['VALUE'];
}

function getParamDesc( $param_name )
{
	if ( !$line = db_assoc_arr( "SELECT `desc` FROM `GlParams` WHERE `Name` = '$param_name'" ) )
		return false;
	return $line['desc'];
}

function setParam( $param_name, $param_val )
{
	global $cacheParam;
	
	if ( !$res = db_res( "UPDATE `GlParams` SET `VALUE` = '".process_db_input($param_val)."' WHERE `Name` = '$param_name'" ) )
		return false;
	
	$cacheParam[$param_name] = $line[$param_val];
	return true;
}

function mysqlErrorReport()
{
		mail( $site['bugReportMail'], "Error", "Error in $_SERVER[PHP_SELF]: " . mysql_error() . "\nQuery: '$query'" );
}

function genMySQLErr( $out, $query ='' )
{
	global $site;
	
	$aBackTrace = debug_backtrace();
	unset( $aBackTrace[0] );
	
	if( $query )
	{
		//try help to find error
		
		$aFoundError = array();
		
		foreach( $aBackTrace as $aCall )
		{
			foreach( $aCall['args'] as $argNum => $argVal )
			{
				if( is_string($argVal) and strcmp( $argVal, $query ) == 0 )
				{
					$aFoundError['file']     = $aCall['file'];
					$aFoundError['line']     = $aCall['line'];
					$aFoundError['function'] = $aCall['function'];
					$aFoundError['arg']      = $argNum;
				}
			}
		}
		
		if( $aFoundError )
		{
			$sFoundError = <<<EOJ
<b>Found error</b> in file <b>{$aFoundError['file']}</b><br />
at line <b>{$aFoundError['line']}</b>. Called <b>{$aFoundError['function']}</b> function 
with erroneous argument #<b>{$aFoundError['arg']}</b><br />
<br />

EOJ;
		}
	}

	
	if( DB_FULL_VISUAL_PROCESSING )
	{
		?>
			<div style="border:2px solid red;padding:4px;width:600px;margin:0px auto;">
				<div style="text-align:center;background-color:red;color:white;font-weight:bold;">Error</div>
				<div style="text-align:center;"><?=$out?></div>
		<?
		if( DB_FULL_DEBUG_MODE )
		{
			if( strlen( $query ) )
				echo "<div><b>Query:</b><br />{$query}</div>";
			
			echo '<div><b>Mysql error:</b><br />'.mysql_error().'</div>';
			echo '<div style="overflow:scroll;height:300px;border:1px solid gray;">';
				echo $sFoundError;
				echo "<b>Debug backtrace:</b><br />";
				echoDbg( $aBackTrace );
				
				echo "<b>Called script:</b> {$_SERVER['PHP_SELF']}<br />";
				echo "<b>Request parameters:</b><br />";
				echoDbg( $_REQUEST );
			echo '</div>';
		}
		?>
			</div>
		<?
	}
	else
		echo $out;
	
	if( DB_DO_EMAIL_ERROR_REPORT )
	{
		$sMailBody = "Database error in <SiteName>\n";

		if( strlen( $query ) )
			$sMailBody .= "Query:\n{$query}\n\n";
		
		$sMailBody .= "Mysql error:\n" . mysql_error() . "\n\n";
		
		$sMailBody .= strip_tags( $sFoundError );
		
		$sMailBody .= "Debug backtrace:\n" . print_r( $aBackTrace, true ) . "\n\n";
		$sMailBody .= "Called script: {$_SERVER['PHP_SELF']}\n\n";
		$sMailBody .= "Request parameters:\n" . print_r( $_REQUEST, true ) . "\n\n";
		
		$sMailBody .= "--\nAuto-report system\n";
		
		sendMail( $site['bugReportMail'], "Database error in <SiteName>", $sMailBody );
	}
	
	exit;
}

?>