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
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );

// --------------- page variables

$_page['name_index'] 	= 30;
$_page['css_name']		= 'mem_polls.css';

$logged['member'] = member_auth( 0, false );

$_page['header'] = _t( "_Members Polls H" );
$_page['header_text'] = _t( "_Members Polls H1" );

// this is dynamic page -  send headers to do not cache this page
send_headers_page_changed();

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
	global $site;
	global $aPreValues;
	
    $query = "
		SELECT
			`id_poll`,
			`id_profile`,
			`poll_question`,
			`Profiles`.*
		FROM `ProfilesPolls`
		LEFT JOIN `Profiles` ON
			`id_profile` = `Profiles`.`ID`
		WHERE
			`poll_status` = 'active'
			AND `poll_approval`
		ORDER BY `id_poll` DESC
		";
	//$query = "SELECT `ID`, `Question` FROM `polls_q` WHERE `Active` = 'on' ORDER BY `Question`";

	$res = db_res($query);

	if ( $res and mysql_num_rows($res) )
	{
		$ret = '<div class="clear_both"></div>';
		
		while ( $arr = mysql_fetch_array($res) )
		{
			$age_str = _t("_y/o", age( $arr['DateOfBirth'] ));
			$y_o_sex = $age_str . '&nbsp;' . _t("_".$arr['Sex']);
			
			$poll_coutry = _t($aPreValues['Country'][$arr['Country']]['LKey']);
			
			
			$ret .= '<div class="pollBody">';
				$ret .= '<div class="clear_both"></div>';
				
					$ret .= '<div class="pollInfo">';
						$ret .= get_member_icon( $arr['id_profile'], 'left' );
						$ret .= '<div class="pollInfo_nickname">';
							$ret .= _t( '_Submitted by', $arr['NickName'] );
						$ret .= '</div>';
						$ret .= '<div class="pollInfo_info">';
							$ret .= $y_o_sex . '<br />' . $poll_coutry;
						$ret .= '</div>';
					$ret .= '</div>';
					
				$ret .= '<div class="clear_both"></div>';
					$ret .= ShowPoll( $arr['id_poll'] );
				$ret .= '<div class="clear_both"></div>';
				
			$ret .= '</div>';
			
		}
		$ret .= '<div class="clear_both"></div>';
	}
	else
	{
		$ret = "<div align=center>". _t("_No polls available") ."</div>\n";
	}
	
	return $ret;
}

?>
