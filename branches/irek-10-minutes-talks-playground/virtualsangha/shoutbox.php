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

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

//--------------------------------- constants


$delay = 2;

$class_name = 'shoutbg';

//-------------------------------------------

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false )) )
			$logged['moderator'] = member_auth( 3, false );


if ( $_POST['shout'] )
{
	$id = (int)$_COOKIE['memberID'];
	// This is for anti-spam purpose
	$count_arr = db_arr( "SELECT COUNT( * ) AS `my_count` FROM `shoutbox` WHERE `id` = '$id' AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date) < $delay)" );
	if ( $count_arr['my_count'] == 0 )
	{
		$text = strmaxtextlen( $_POST['shout'], $oTemplConfig -> maxtextlength );
		$text = process_db_input( $text );
		// Delete the oldest record if count more than $maxrecords
		$count_arr = db_arr("SELECT COUNT( * ) AS `total_count` FROM `shoutbox`");
		if ( $count_arr['total_count'] >= $oTemplConfig -> maxrecords )
		{
			$oldest_date = db_arr("SELECT DATE_FORMAT(`date`, '$date_format' ) AS 'date' FROM `shoutbox` ORDER BY `date` ASC LIMIT 1");
			db_res( "DELETE FROM `shoutbox` WHERE `date` = '{$oldest_date['date']}'" );
		}
		// Select last class
		$last_class = db_arr("SELECT `class` FROM `shoutbox` ORDER BY `date` DESC LIMIT 1");
		if ( ($cn = substr($last_class['class'], -1)) >= $oTemplConfig -> classes )
			$cn = $class_name . '1';
		else
			$cn = $class_name . ($cn + 1);
		// Insert to database
		$query = "INSERT INTO `shoutbox` VALUES ('$id', '$text', NOW(), '$cn')";
		db_res( $query );
	}
}


echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
	\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en-US\" lang=\"en-US\">
		<head>
		<title></title>
		<meta http-equiv=\"Refresh\" content=\"60\" />
		<link href=\"templates/tmpl_".$tmpl."/css/shoutbox.css\" rel=\"stylesheet\" type=\"text/css\" />
		<link href=\"templates/tmpl_".$tmpl."/css/anchor.css\" rel=\"stylesheet\" type=\"text/css\" />
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset={$langHTMLCharset}\" />
	</head>
	<body>\n";

echo ThisPageMainCode();

echo "
	</body>
</html>\n";

// --------------- page components functions

/**
 * page code function
 */
function ThisPageMainCode()
{
	global $site;
	global $oTemplConfig;
	global $logged;
	global $tmpl;
	global $date_format;

    $content = "
    	<form name=\"shoutbox\" method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">
    		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
    			<tr><td>
    				<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";

	$class = 'shoutbg1';
	// Print input box
    $content .= "
    					<tr><td align=\"center\" class=\"$class\">";
    if ($_COOKIE['memberID'] && $logged['member'])
    {
		if ($tmpl == 'g4')
		{
			$content .= "
							<table border=\"0\" cellpadding=\"7\" cellspacing=\"0\" width=\"100%\" bgcolor=\"#FFFFFF\">
								<tr>
									<td align=\"left\"><input maxlength=\"" . $oTemplConfig -> maxtextlength . "\" type=\"text\" name=\"shout\" class=\"shoutinp\"></td>
									<td><strong>" . _t('_shout_box_title') . "</td>
								</tr>
							</table>";
		}
		else
		{
			$content .= "
							<input maxlength=\"" . $oTemplConfig -> maxtextlength . "\" type=\"text\" name=\"shout\" />";
		}
    }
	else
	{
		$content .= _t('_to_post');
	}

	$content .= "
						</td></tr>";
	// End of print input box
	$query = "SELECT `shoutbox`.`id`, `shoutbox`.`text`, DATE_FORMAT(`shoutbox`.`date`,  '$date_format' ) AS 'date', `shoutbox`.`class`, `Profiles`.`ID` as `prID`, `Profiles`.`NickName` FROM `shoutbox` LEFT JOIN `Profiles` ON `Profiles`.`ID` = `shoutbox`.`id` ORDER BY `shoutbox`.`date` DESC";
	$shout_res = db_res( $query );

	while ( $shout_arr = mysql_fetch_array($shout_res) )
    {
		$shout_text = process_smiles( process_line_output( $shout_arr['text'], $oTemplConfig -> maxwordlength) );
		$content .= "
						<tr><td title=\"{$shout_arr['date']}\" class=\"{$shout_arr['class']}\">
							<a target=\"_blank\" href=\"".getProfileLink($shout_arr['prID'])."\" class=\"membermenu\">{$shout_arr['NickName']}</a>: {$shout_text}
						</td></tr>";
    }

	$content .= "
					</table>
				</td></tr>
			</table>
		</form>\n";

    return $content;
}

?>