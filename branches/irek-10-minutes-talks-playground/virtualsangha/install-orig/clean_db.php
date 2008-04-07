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

$config_file = "../inc/header.inc.php";
$config_dir = "../inc/";

$config_arr[db_host] = array(
	name => "Database host name",
	ex => "localhost",
	desc => "Your MySQL database host name here.",
	def => "localhost",
	check => 'return strlen($arg0) >= 1 ? true : false;'
	);
$config_arr[db_user] = array(
	name => "Database user",
	ex => "YourName",
	desc => "Your MySQL database read/write user name here.",
	check => 'return strlen($arg0) >= 1 ? true : false;'
	);
$config_arr[db_password] = array(
	name => "Database password",
	ex => "YourPassword",
	desc => "Your MySQL database password here.",
	check => 'return strlen($arg0) >= 0 ? true : false;'
	);
$config_arr[db_name] = array(
	name => "Database name",
	ex => "YourDatabaseName",
	desc => "Your MySQL database name here.",
	check => 'return strlen($arg0) >= 1 ? true : false;'
	);


?>
<html>
<head>
	<title>Dolphin installation</title>
	<link href="../styles.css" rel=stylesheet type=text/css>
</head>
<body>
	<table width=100% height=100%><td align=center valign=center>
		<table width=70% bgcolor=#d7d3fa align=center cellpadding=0 cellspacing=5><td>
		<b>Dolphin clean db</b>.
		</td></table>
		<br>

<?php

function PrintInput()
{
	global $config_file;
	global $config_arr;
	global $templ;


	echo <<<EOS

	<form method=post>
        <table width=70% bgcolor=#d7d3fa align=center cellpadding=0 cellspacing=5><td>
        5) DB information <br>
        </td></table>
	<table width=70% height=70% bgcolor=#d7d3fa class=text align=center cellpadding=0 cellspacing=5 class=text>
	<td valign=top>
EOS;

	foreach ( $config_arr as $key => $val )
	{
		echo <<<EOS
		<tr class=text><td>$val[name]</td><td><input size=30 name="$key" value="$val[def]"></td></tr>
		<tr class=text><td>Description:</td><td>$val[desc]</td></tr>
		<tr class=text><td>Example:</td><td>$val[ex]</td></tr>
		<tr height=1><td colspan=2 bgcolor=black></td></tr>
EOS;
	}
	echo "</table>	
		<br>

          <center><input type=submit name=Save value=Save></center>
	      </form>	
	</td></table>
	</td></table>";

}



function run_sql ( )
{
	global $_POST;

	$db[host]        = $_POST[db_host];
	$db[user]        = $_POST[db_user];
	$db[passwd]      = $_POST[db_password];
	$db[db]          = $_POST[db_name];

    $link = mysql_connect ( $db[host], $db[user], $db[passwd]  );
	
	if ( !$link ) return ("Could not connect to MySQL server: " . mysql_error());

    if (!mysql_select_db ($db[db], $link))
        return ("Could not select database '$db[db]': " . mysql_error());

    // delete all nonnessary information from tables
    {
        if ( !(mysql_query ( "TRUNCATE TABLE `Articles`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>"; 
            
        if ( !(mysql_query ( "TRUNCATE TABLE `BannersClicks`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>"; 
            
        if ( !(mysql_query ( "TRUNCATE TABLE `BannersShows`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>"; 
            
//        if ( !(mysql_query ( "TRUNCATE TABLE `Links`", $link ) ) )
//          $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";
            
        if ( !(mysql_query ( "TRUNCATE TABLE `MemCredits`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>"; 
            
        if ( !(mysql_query ( "TRUNCATE TABLE `IMessages`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>"; 
            
        if ( !(mysql_query ( "TRUNCATE TABLE `Messages`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>"; 
            
        if ( !(mysql_query ( "TRUNCATE TABLE `News`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";
            
        if ( !(mysql_query ( "TRUNCATE TABLE `NotifyEmails`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";
            
        if ( !(mysql_query ( "TRUNCATE TABLE `NotifyQueue`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";
            
        if ( !(mysql_query ( "TRUNCATE TABLE `PrivPhotosRequests`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>"; 
            
        if ( !(mysql_query ( "TRUNCATE TABLE `Profiles`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>"; 
      /*      
        if ( !(mysql_query ( "TRUNCATE TABLE `ProfilesRelations`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";
        */  
        if ( !(mysql_query ( "TRUNCATE TABLE `Stories`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";

        if ( !(mysql_query ( "TRUNCATE TABLE `Transactions`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";

       if ( !(mysql_query ( "TRUNCATE TABLE `VKisses`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";

        if ( !(mysql_query ( "TRUNCATE TABLE `Votes`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";

        if ( !(mysql_query ( "TRUNCATE TABLE `ZIPCodes`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";

        if ( !(mysql_query ( "TRUNCATE TABLE `aff`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";

        if ( !(mysql_query ( "TRUNCATE TABLE `aff_members`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";

        if ( !(mysql_query ( "TRUNCATE TABLE `polls_a`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";

        if ( !(mysql_query ( "TRUNCATE TABLE `polls_q`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";

        if ( !(mysql_query ( "TRUNCATE TABLE `BlockList`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";


        if ( !(mysql_query ( "TRUNCATE TABLE `HotList`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";

        if ( !(mysql_query ( "TRUNCATE TABLE `FriendList`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";

        if ( !(mysql_query ( "TRUNCATE TABLE `VotesPhotos`", $link ) ) )
            $ret .= "<i><b>Error</b>:</i> ".mysql_error()."<hr>";

        if ( !(mysql_query("DELETE FROM `GlParamsKateg` WHERE `ID`=13", $link )))
            $ret .= "<font color=red><i><b>Error</b>:</i> ".mysql_error()."</font><hr>";

	}

    mysql_close($link);

    return $ret."<br><b>Truncating tables finished.</b><br>";
}


function PrintOutput ()
{
	global $config_file;
	global $config_arr;
	global $templ;
	global $_POST;


	
print <<<EOS
	<table width=70% bgcolor=#d7d3fa class=text align=center cellpadding=0 cellspacing=5 class=text>
	<td valign=top>
EOS;
		

	echo "<font color=red>".run_sql()."</font>";

print <<<EOS
	</td>
	</table>
	<br>
EOS;
}


$err = "";

if ( $_POST[Save] )
{

	foreach ( $config_arr as $key => $val )
	{
		if ( !strlen($val[check]) ) continue;
		$funcbody = $val[check];
        $func = create_function('$arg0', $funcbody);
        if ( !$func($_POST[$key]) )
		{
			$err .= "<font color=red>Please, input valid data to $val[name] field</font><br>";
		}
		$config_arr[$key][def] = $_POST[$key];
	}

}

if (strlen($err))
{

print <<<EOS
	<table width=70% bgcolor=#d7d3fa class=text align=center cellpadding=0 cellspacing=5 class=text>
	<td valign=top>
		$err
	</td>
	</table>
	<br>
EOS;

}

if ( $_POST[Save] && !strlen($err) )
{
	PrintOutput();
}
else
{
	PrintInput();
}

?>

</body>
</html>
