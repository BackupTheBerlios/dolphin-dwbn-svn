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
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'modules.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'membership_levels.inc.php' );

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
			mail( $site[email], "{$site['title']}: Periodic Report", $output, "From: Periodic <$site[email]>", "-f$site[email]" );
        }
	}
	exit;
}

function clean_database()
{
	$db_clean_vkiss = getParam("db_clean_vkiss");
	$db_clean_profiles = getParam("db_clean_profiles");
	$db_clean_msg = getParam("db_clean_msg");
	$db_clean_views = getParam("db_clean_views");
	$db_clean_priv_msg = getParam("db_clean_priv_msg");

    // profile_delete
    if ( $db_clean_profiles > 0)
    {
        $res = db_res("SELECT ID FROM Profiles WHERE (TO_DAYS(NOW()) - TO_DAYS(DateLastLogin)) > $db_clean_profiles");
        if ( $res )
        {
            $db_clean_profiles_num = mysql_num_rows($res);
            while ( $arr = mysql_fetch_array($res) )
            {
                profile_delete($arr['ID']);
            }
        }
    }


	if ( $db_clean_vkiss > 0 )
	{
		$res = db_res("DELETE FROM VKisses WHERE (TO_DAYS(NOW()) - TO_DAYS(Arrived)) > $db_clean_vkiss");
		if ( $res )
			$db_clean_vkiss_num = mysql_affected_rows();
	}

    if ( $db_clean_msg > 0 )
    {
        $res = db_res("DELETE FROM Messages WHERE (TO_DAYS(NOW()) - TO_DAYS(`Date`)) > $db_clean_msg");
        if ( $res )
            $db_clean_msg_num = mysql_affected_rows();
    }

    if ( $db_clean_views > 0 )
    {
        $res = db_res("DELETE FROM ProfilesTrack WHERE (TO_DAYS(NOW())-TO_DAYS(`Arrived`)) > $db_clean_views");
        if ( $res )
            $db_clean_views_num = mysql_affected_rows();
    }

    if ( $db_clean_priv_msg > 0 )
    {
        $res = db_res("DELETE FROM IMessages WHERE (TO_DAYS(NOW()) - TO_DAYS(`When`)) > $db_clean_priv_msg");
        if ( $res )
            $db_clean_priv_msg_num = mysql_affected_rows();
    }


    echo "\n- Database cleaning -\n";

    echo "Deleted profiles: $db_clean_profiles_num\n";
    echo "Deleted virtual kisses: $db_clean_vkiss_num\n";
    echo "Deleted messages: $db_clean_msg_num\n";
    echo "Deleted private messages: $db_clean_priv_msg_num\n";
    echo "Deleted profile views: $db_clean_views_num\n";
}

function del_old_all_files()
{
    global $dir;

    $num_tmp = 0;
    $num_del = 0;

    $file_life = 86400;  // one day
    $dirToClean = array();
	$dirToClean[] = $dir['tmp'];
	$dirToClean[] = $dir['cache'];
		
	foreach( $dirToClean as $value )
	{
		if ( !( $lang_dir = opendir( $value ) ) )
		{
			continue;
		}
		else
		{
			while ($lang_file = readdir( $lang_dir ))
			{
		    	$diff = time() - filectime( $value . $lang_file);
		    	if ( $diff > $file_life && '.' != $lang_file && '..' != $lang_file && '.htaccess' !== $lang_file )
		    	{
		    		@unlink ($value . $lang_file);
		    		++$num_del;
		    	}
		    	++$num_tmp;
		    }
		    closedir( $lang_dir );
		}
	}
	
    echo "\n- Temporary files check -\n";

    echo "Total temp files: $num_tmp\n";
    echo "Deleted temp files: $num_del\n";
}



function modules_proceed()
{
	// select all profiles and check who can't use chat or forum
	$p_res = db_res( "SELECT `ID` FROM `Profiles`");
    while ( $p_arr = mysql_fetch_array($p_res) )
	{
		$check_res = checkAction($p_arr['ID'], ACTION_ID_USE_CHAT);
		if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED )
		{
			modules_block($p_arr['ID'], 'chat');
		}
		else 
		{
			modules_unblock($p_arr['ID'], 'chat');
		}
		
		$check_res = checkAction($p_arr['ID'], ACTION_ID_USE_FORUM);
		if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED )
		{
			modules_block($p_arr['ID'], 'forum');
		}
		else 
		{
			modules_unblock($p_arr['ID'], 'forum');
		}		
	}

    echo "\n- Modules check -\n";
    echo "Profiles processed successfully\n";
}

// -------------

// - Defaults -
$MODE	= "_MAIL_";
//$MODE = "_LIVE_";
$DAY	= "_OBEY_";
//$DAY	= "_FORCE_";
define('NON_VISUAL_PROCESSING', 'YES');


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
		case "--force-day": $DAY = "_FORCE_"; break;
		case "--obey-day": $DAY = "_OBEY_"; break;
	}
}


if ( $MODE != "_LIVE_" )
	ob_start();


$day = date( "d" );
if ( getParam( "cmdDay" ) == $day && $DAY == "_OBEY_" )
{
	echo "Already done today, bailing out\n";
	finish();
}
else
	setParam( "cmdDay", $day );


//========================================================================================================================

// - Membership check -
echo "\n- Membership expiration letters -\n";

$expire_notification_days = getParam("expire_notification_days");
$expire_notify_once = getParam("expire_notify_once");

$expire_letters = 0;

$exp_res = db_res( "SELECT `ID` FROM `Profiles`", 0 );

while( $row = mysql_fetch_array( $exp_res ) )
{
	$current_membership_arr = getMemberMembershipInfo( $row['ID'] );
	// If expire_notification_days is -1 then notify after expiration
	if ( $current_membership_arr['ID'] == MEMBERSHIP_ID_STANDARD && $expire_notification_days == -1 )
	{
		// Calculate last UNIX Timestamp
		$last_timestamp = time() - 24 * 3600;
		$last_membership_arr = getMemberMembershipInfo( $row['ID'], $last_timestamp );
		if ( $current_membership_arr['ID'] != $last_membership_arr['ID'] )
		{
			modules_update($row['ID']); // Handle membership level change
			if ($further_membership_arr['ID'] == MEMBERSHIP_ID_STANDARD)		
			{
				$mail_ret = mem_expiration_letter($row['ID'], $last_membership_arr['Name'], -1);
				if ( $mail_ret )
					$expire_letters++;
			}
		}
	}
	// If memberhip is not standard then check if it will change
	elseif ( $current_membership_arr['ID'] != MEMBERSHIP_ID_STANDARD )
	{
		// Calculate further UNIX Timestamp
		$further_timestamp = time() + $expire_notification_days * 24 * 3600;
		$further_membership_arr = getMemberMembershipInfo( $row['ID'], $further_timestamp );
		if ( $current_membership_arr['ID'] != $further_membership_arr['ID']
			&& $further_membership_arr['ID'] == MEMBERSHIP_ID_STANDARD )
		{
			if ( !$expire_notify_once || abs($further_timestamp - time()) < 24 * 3600 )
			{
				$mail_ret = mem_expiration_letter( $row['ID'], $current_membership_arr['Name'],
					(int)( ($current_membership_arr['DateExpires'] - time()) / (24 * 3600) ) );
				if ( $mail_ret )
					$expire_letters++;
			}
		}
	}
}

echo "Send membership expire letters: $expire_letters letters\n";


//========================================================================================================================

// clear tmp folder --------------------------------------------------------------------------

del_old_all_files();

// ----------------------------------------------------------------------------------

clean_database();

modules_proceed();

finish();

?>