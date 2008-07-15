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
require_once(BX_DIRECTORY_PATH_INC . 'profile_disp.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'utils.inc.php');

/**
 * match profiles
 * return number ( 0-100 ) in percent, how match this profiles
 */
function match_profiles ( $Member, $Profile )
{

	$fields = array();
	$extras = array();
	$match_fields = array();
	$match_types = array();
	$match_extras = array();
	$i = 0;

	$res = db_res("SELECT `name`, `match_field`, `extra`, `match_type`, `match_extra` FROM `ProfilesDesc` WHERE `match_type` <> '' AND `match_type` <> 'none'");
	while ($arr = mysql_fetch_array($res))
	{
		$fields[$i] = get_field_name($arr);
		$extras[$i] = $arr['extra'];
		$match_fields[$i] = $arr['match_field'];
		$match_types[$i] = $arr['match_type'];
		$match_extras[$i] = $arr['match_extra'];
		$i++;
	}

	foreach ( $match_fields as $n => $m_field )
	{
        $m_field = trim($m_field);
        if (!strlen($m_field)) continue;

		if ( !$n ) {
			$sql_add_m .= " $m_field";
			$sql_add_p .= " $fields[$n]";
		} else {
			$sql_add_m .= ", $m_field";
			$sql_add_p .= ", $fields[$n]";
		}
	}

	$arr_m = db_arr("SELECT $sql_add_p FROM Profiles WHERE ID = $Member");
	$arr_p = db_arr("SELECT $sql_add_m FROM Profiles WHERE ID = $Profile");

	if ( !$arr_m || !$arr_p )
		return 0;

	$ret = 0;

	foreach ( $match_fields as $n => $m_field )
	{
		switch ( $match_types[$n] )
		{
			case "enum":
			case "enum_ref":
				if ( $arr_m[$fields[$n]] == $arr_p[$match_fields[$n]] )
					$ret +=  $match_extras[$n];
				break;
			case "set":
				$vals = preg_split ("/[,\']+/", $extras[$n], -1, PREG_SPLIT_NO_EMPTY);
				$vals_m = preg_split ("/[,\']+/", $arr_m[$fields[$n]], -1, PREG_SPLIT_NO_EMPTY);
				$vals_p = preg_split ("/[,\']+/", $arr_p[$match_fields[$n]], -1, PREG_SPLIT_NO_EMPTY);

				$count = count($vals);
				$count_m = count($vals_m);
				$count_p = count($vals_p);

				if ( $count_p + $count_m > 0 )
				{
					$per = $match_extras[$n] / max($count_p, $count_m);
					foreach ( $vals as $key => $val )
					{
						if ( strlen( strstr($arr_m[$fields[$n]], $val) ) > 0
								&& strlen( strstr($arr_p[$match_fields[$n]], $val) ) > 0 )
							$ret += $per;
					}
				}

				break;
			case "daterange":
				$rg = split ( "-", $arr_m[$fields[$n]] );
				$age = age($arr_p[$match_fields[$n]]);
				if ( $age >= $rg[0] && $age <= $rg[1] )
					$ret += $match_extras[$n];
				break;
		}
	}

	return (int)$ret;
}

function cupid_email( $profile )
{
	global $ret;
	global $data;
	global $site;

    $profile  = (int)$profile;
	$match_min = (int)getParam("match_percent");

    $prof_arr = getProfileInfo( $profile, true );

    if ( ((int)$prof_arr['ID']) <= 0 )
        return false;

	$add = '';
	if ( 'all' != $prof_arr['LookingFor'] )
		$add = "AND `Sex` = '{$prof_arr['LookingFor']}' ";
	$add .= "AND ( `LookingFor` = '{$prof_arr['Sex']}' OR `LookingFor` = 'all' )";
	$memb_res = db_res("SELECT ID, NickName, Email, EmailFlag
				FROM Profiles
				WHERE EmailNotify = 'NotifyMe' AND Status = 'Active' AND ID <> $profile $add");
    if ( mysql_num_rows($memb_res) < 1 )
        return false;

	while ( $memb_arr = mysql_fetch_array($memb_res) )
	{
	    $match = match_profiles( $memb_arr['ID'], $profile );
		if ( $match < $match_min )
		{
			// If the profile matches less then predefined
			// percent then go to next iteration (i.e. next profile)
			continue;
		}


		$message = getParam( "t_CupidMail" );
		$subject = getParam('t_CupidMail_subject');
		$subject = addslashes($subject);

		$recipient	= $memb_arr['Email'];
		$headers	= "From: {$site['title']} <{$site['email_notify']}>";
		$headers2	= "-f{$site['email_notify']}";

		$message	= str_replace( "<SiteName>", $site['title'], $message );
		$message	= str_replace( "<Domain>", $site['url'], $message );
		$message	= str_replace( "<RealName>", $memb_arr['NickName'], $message );
		$message	= str_replace( "<StrID>", $memb_arr['ID'], $message );
		$message	= str_replace( "<MatchProfileLink>", getProfileLink($prof_arr['ID']), $message );
		$message	= addslashes($message);

		if ('Text' == $memb_arr['EmailFlag'])
		{
			$message = html2txt($message);
		}
		if ('HTML' == $memb_arr['EmailFlag'])
		{
			$headers = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=UTF-8\r\n" . $headers;
		}

		$sql = "INSERT INTO `NotifyQueue` SET `Email` = {$memb_arr['ID']}, Msg = 0, `From` = 'ProfilesMsgText', Creation = NOW(), MsgText = '$message', MsgSubj = '$subject'";

		$res = db_res($sql);
	}

	return true;
}

?>
