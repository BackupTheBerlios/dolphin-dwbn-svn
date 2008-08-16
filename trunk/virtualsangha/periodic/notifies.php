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

require_once( '/Library/WebServer/Documents/inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

// -------------

// - Always finish
set_time_limit( 36000 );
ignore_user_abort();

// - Defaults -
$MODE	= "_MAIL_";
$DAY	= "_OBEY_";

// - Parameters check -
for ( $i = 0; strlen( $argv[$i] ); $i++ ) {
	switch( $argv[$i] ) {
		case '--live': $MODE = "_LIVE_"; break;
		case '--mail': $MODE = "_MAIL_"; break;
	}
}

// - Begin -
if ( $MODE == '_MAIL_' )
	ob_start();

$msgs_per_start = getParam( 'msgs_per_start' );

$iFullCount = (int)db_value( 'SELECT COUNT(*) FROM NotifyQueue', 0 );
if ( !$iFullCount ) exit;

echo "\n- Start email send -\n";
echo "Total queued emails: $iFullCount\n";
$total_count = ($iFullCount < $msgs_per_start ? $iFullCount : $msgs_per_start);
$total_per_query = round( $total_count / 3 ) + 1;
echo "Ready for send: ". $total_count ."\n";

if ( $iFullCount ) {
	$count_ok = 0;
	$err = 0;

	if ( $count_ok < $total_count ) {
		// Notify Messages
		$nfs_res = db_res("
			SELECT
				NotifyQueue.Email as ID1,
				NotifyQueue.Msg as ID2,
				NotifyEmails.Name as Name,
				NotifyEmails.Email,
				NotifyMsgs.Subj,
				NotifyMsgs.HTML as Body
			FROM NotifyQueue
			INNER JOIN NotifyMsgs ON
				(NotifyMsgs.ID =  NotifyQueue.Msg)
			INNER JOIN NotifyEmails ON
				(NotifyEmails.ID = NotifyQueue.Email)
			WHERE
				NotifyQueue.`From` = 'NotifyEmails' AND
				NotifyEmails.EmailFlag = 'NotifyMe'
			LIMIT $total_per_query
		",0 );
		
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


	if ( $count_ok < $total_count ) {
	    // Profiles Messages
		$nfs_res = db_res("
			SELECT
				NotifyQueue.Email as ID1,
				NotifyQueue.Msg as ID2,
				Profiles.NickName as Name,
				Profiles.Email,
				NotifyMsgs.Subj,
				NotifyMsgs.HTML as Body
			FROM NotifyQueue			
			INNER JOIN NotifyMsgs ON
				(NotifyMsgs.ID =  NotifyQueue.Msg)
			INNER JOIN Profiles ON
				(Profiles.ID = NotifyQueue.Email)
			WHERE
				NotifyQueue.`From` = 'Profiles' AND
				Profiles.EmailNotify  = 'NotifyMe'
			",0 );
		
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



    if ( $count_ok < $total_count ) {
        // Profiles Messages
        $nfs_res = db_res("
			SELECT
				NotifyQueue.Email as ID1,
				NotifyQueue.Msg as ID2,
				NotifyQueue.MsgText as Body,
				NotifyQueue.MsgSubj as Subj,
				Profiles.NickName as Name,
				Profiles.Email
			FROM NotifyQueue
			INNER JOIN Profiles ON
				(Profiles.ID = NotifyQueue.Email)
			WHERE
				NotifyQueue.`From` = 'ProfilesMsgText' AND
				Profiles.EmailNotify  = 'NotifyMe'
			",0);
		
        while( $row = mysql_fetch_array( $nfs_res ) )
        {
            $body = $row['Body'];
            $headers = "From: {$site['title']} <{$site['email_notify']}>";

           	$headers = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=UTF-8\r\n" . $headers;

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

if( $err and $MODE == '_MAIL_' ) {
	$output = ob_get_clean();
	mail( $site['email'], "{$site['title']}: Periodic Report (Notify Letters)", $output, "From: Periodic(Notify Letters) <$site[email]>", "-f$site[email]" );
}

periodic_check_ban();

