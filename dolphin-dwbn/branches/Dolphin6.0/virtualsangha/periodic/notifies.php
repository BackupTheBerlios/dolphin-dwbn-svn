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

require_once( '/proj/www/dolphin.mahamudra.de/home/htdocs/inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// - Functions -
function finish()
{
	global $site;
    global $MODE;

	if ( $MODE != "_LIVE_" )
	{
		$output = ob_get_contents();
		ob_end_clean();
		if ( $MODE == "_MAIL_" )
        {
			mail( $site['email'], "{$site['title']}: Periodic Report (Notify Letters)", $output, "From: Periodic(Notify Letters) <$site[email]>", "-f$site[email]" );
        }
	}
	exit;
}

// -------------

// - Defaults -
$MODE	= "_MAIL_";
$DAY	= "_OBEY_";

// - Always finish
set_time_limit( 36000 );
ignore_user_abort();


// - Parameters check -
for ( $i = 0; strlen( $argv[$i] ); $i++ )
{
	switch( $argv[$i] )
	{
		case "--live": $MODE = "_LIVE_"; break;
		case "--mail": $MODE = "_MAIL_"; break;
	}
}

if ( $MODE != "_LIVE_" )
	ob_start();

$msgs_per_start = getParam( "msgs_per_start" );

$arr = db_arr( "SELECT COUNT(*) FROM NotifyQueue", 0 );
if ( $arr[0] <= 0 ) exit;

echo "\n- Start email send -\n";
echo "Total queued emails: $arr[0]\n";
$total_count = ($arr[0] < $msgs_per_start ? $arr[0] : $msgs_per_start);
$total_per_query = round( $total_count / 4 ) + 1;
echo "Ready for send: ". $total_count ."\n";

if ( $arr[0] > 0 )
{
	$count_ok = 0;
	$err = 0;

	if ( $count_ok < $total_count )
	{
		// Notify Messages - HTML 
		$nfs_res = db_res("
			SELECT
				NotifyQueue.Email as ID1,
				NotifyQueue.Msg as ID2,
				NotifyEmails.Name as Name,
				NotifyEmails.Email,
				NotifyMsgs.Subj,
				NotifyMsgs.HTML as Body
			FROM NotifyQueue
			
			INNER JOIN NotifyMsgs
			ON (NotifyMsgs.ID =  NotifyQueue.Msg)
			
			INNER JOIN NotifyEmails
			ON (NotifyEmails.ID = NotifyQueue.Email)
			
			WHERE
				NotifyQueue.`From` = 'NotifyEmails' AND
				NotifyEmails.EmailFlag = 'NotifyMe' AND
				NotifyEmails.EmailText = 'HTML'
			LIMIT $total_per_query",0 );
		while( $row = mysql_fetch_array( $nfs_res ) )
		{
			$headers = "From: {$site['title']} <{$site['email_notify']}>";
			$headers = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=UTF-8\r\n" . $headers;
			if ( !mail( $row['Email'], $row['Subj'], $row['Body'], $headers, "-f{$site['email_notify']}") )
				++$err;
		    if ( $row[ID1] && $row[ID2] )
				db_res("DELETE FROM NotifyQueue WHERE `Email` = $row[ID1] AND `Msg` = $row[ID2] AND `From` = 'NotifyEmails'", 0 );
			else
				echo "ERROR: while deleting from 'NotifyQueue' ( Email ID: $row[ID1], Msg ID: $row[ID2] )\n";
			++$count_ok;
			if ( $count_ok >= $total_count ) break;
		}
	}


	if ( $count_ok < $total_count )
	{
	    // Notify Messages - TEXT
		$nfs_res = db_res("SELECT NotifyQueue.Email as ID1, NotifyQueue.Msg as ID2, NotifyEmails.Name as Name, NotifyEmails.Email, NotifyMsgs.Subj, NotifyMsgs.Text as Body FROM  NotifyQueue INNER JOIN NotifyMsgs ON (NotifyMsgs.ID =  NotifyQueue.Msg) INNER JOIN NotifyEmails ON (NotifyEmails.ID = NotifyQueue.Email) WHERE  NotifyQueue.`From` = 'NotifyEmails' AND  NotifyEmails.EmailFlag = 'NotifyMe' AND NotifyEmails.EmailText <> 'HTML'",0);
	    while( $row = mysql_fetch_array( $nfs_res ) )
		{
			if ( !mail( $row['Email'], $row['Subj'], $row['Body'], "From: {$site['title']} <{$site['email_notify']}>", "-f{$site['email_notify']}") )
				++$err;
	        if ( $row[ID1] && $row[ID2] )
		        db_res("DELETE FROM NotifyQueue WHERE Email = $row[ID1] AND Msg = $row[ID2] AND `From` = 'NotifyEmails'", 0 );
			else
				echo "ERROR: while deleting from 'NotifyQueue' ( Email ID: $row[ID1], Msg ID: $row[ID2] )\n";
	        ++$count_ok;
			if ( $count_ok >= $total_count ) break;
		}
	}

	if ( $count_ok < $total_count )
	{
	    // Profiles Messages - HTML
		$nfs_res = db_res("
			SELECT
				NotifyQueue.Email as ID1,
				NotifyQueue.Msg as ID2,
				Profiles.NickName as Name,
				Profiles.Email,
				NotifyMsgs.Subj,
				NotifyMsgs.HTML as Body
			FROM NotifyQueue
			
			INNER JOIN NotifyMsgs
			ON (NotifyMsgs.ID =  NotifyQueue.Msg)
			
			INNER JOIN Profiles
			ON (Profiles.ID = NotifyQueue.Email)
			
			WHERE
				NotifyQueue.`From` = 'Profiles' AND
				Profiles.EmailNotify  = 'NotifyMe' AND
				Profiles.EmailFlag = 'HTML'",0 );
		
	    while( $row = mysql_fetch_array( $nfs_res ) )
		{
			$headers = "From: {$site['title']} <{$site['email_notify']}>";
			$headers = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=UTF-8\r\n" . $headers;
			if ( !mail( $row['Email'], $row['Subj'], $row['Body'], $headers, "-f{$site['email_notify']}") )
	            ++$err;
		    if ( $row[ID1] && $row[ID2] )
				db_res("DELETE FROM NotifyQueue WHERE Email = $row[ID1] AND Msg = $row[ID2] AND NotifyQueue.`From` = 'Profiles'", 0 );
	        else
		        echo "ERROR: while deleting from 'NotifyQueue' ( Email ID: $row[ID1], Msg ID: $row[ID2] )\n";
			++$count_ok;
			if ( $count_ok >= $total_count ) break;
	    }
	}

	if ( $count_ok < $total_count )
	{
	    // Profiles Messages - TEXT
		$nfs_res = db_res("SELECT NotifyQueue.Email as ID1, NotifyQueue.Msg as ID2, Profiles.NickName as Name, Profiles.Email, NotifyMsgs.Subj, NotifyMsgs.Text as Body FROM  NotifyQueue INNER JOIN NotifyMsgs ON (NotifyMsgs.ID =  NotifyQueue.Msg) INNER JOIN Profiles ON (Profiles.ID = NotifyQueue.Email) WHERE  NotifyQueue.`From` = 'Profiles' AND Profiles.EmailNotify  = 'NotifyMe' AND Profiles.EmailFlag <> 'HTML'",0);
	    while( $row = mysql_fetch_array( $nfs_res ) )
		{
			if ( !mail( $row['Email'], $row['Subj'], $row['Body'], "From: {$site['title']} <{$site['email_notify']}>", "-f{$site['email_notify']}") )
	            ++$err;
		    if ( $row[ID1] && $row[ID2] )
			    db_res("DELETE FROM NotifyQueue WHERE Email = $row[ID1] AND Msg = $row[ID2] AND NotifyQueue.`From` = 'Profiles'", 0 );
	        else
		        echo "ERROR: while deleting from 'NotifyQueue' ( Email ID: $row[ID1], Msg ID: $row[ID2] )\n";
	        ++$count_ok;
			if ( $count_ok >= $total_count ) break;
		}
	}


    if ( $count_ok < $total_count )
    {
        // Profiles Messages - TEXT or HTML
        $nfs_res = db_res("SELECT NotifyQueue.Email as ID1, NotifyQueue.Msg as ID2, NotifyQueue.MsgText as Body, NotifyQueue.MsgSubj as Subj, Profiles.NickName as Name, Profiles.Email FROM  NotifyQueue INNER JOIN Profiles ON (Profiles.ID = NotifyQueue.Email) WHERE  NotifyQueue.`From` = 'ProfilesMsgText' AND Profiles.EmailNotify  = 'NotifyMe'",0);
        while( $row = mysql_fetch_array( $nfs_res ) )
        {
            $email_flag = db_arr("SELECT EmailFlag FROM Profiles WHERE Email = '" . $row['Email'] . "' LIMIT 1");
            $body = $row['Body'];
            $headers = "From: {$site['title']} <{$site['email_notify']}>";

            if ( $email_flag['EmailFlag'] == "HTML" )
            {
            	$headers = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=UTF-8\r\n" . $headers;
            }

            if ( !mail( $row['Email'], $row['Subj'], $body, $headers, "-f{$site['email_notify']}") )
                ++$err;
            if ( !db_res("DELETE FROM NotifyQueue WHERE Email = $row[ID1] AND NotifyQueue.`From` = 'ProfilesMsgText'", 0 ) )
                echo "ERROR: while deleting from 'NotifyQueue' ( Email ID: $row[ID1], Msg ID: $row[ID2] )\n";
            ++$count_ok;
            if ( $count_ok >= $total_count ) break;
        }
    }

	echo "Processed emails: $count_ok\n";
	echo "Processed emails with errors: $err\n";
}

if ( $err ) finish();

?>
