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
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$ADMIN = member_auth( 1, false );
$logged['admin'] = $ADMIN;

// Check if moderator logged in.
$moderator = member_auth(3, false);
$logged['moderator'] = $moderator;
// Give moderator all admin rights for this page.
$ADMIN = $ADMIN || $moderator;

if ( !$ADMIN )
$logged['member'] = member_auth( 0 );

// --------------- page variables and login

$_page['name_index']	= 72;
$_page['css_name']	= 'profile_poll.css';
$_page['js_name']	= 'profile_poll.js';


$_page['header'] = _t("_Polls");
$_page['header_text'] = _t("_Polls");



// --------------- handle post values


    $member['ID'] = (int)$_COOKIE['memberID'];

    if ( $_POST['question'] )
    {

	$poll_answers = '';
	for ( $i = 0; $i < $_POST['next_val']; $i++ )
	{
	    if ( $_POST[ 'v' . $i ] )
	    {
		$poll_answers .= process_db_input($_POST[ 'v' . $i ]) . '<delim>';
		$poll_results .= '0;';
	    }
	}

	if ( $poll_answers )
	{

	    $query = "SELECT COUNT(*) FROM ProfilesPolls WHERE id_profile = '" . $member['ID'] . "'";
	    $polls_num = db_arr( $query );

	    $profile_poll_num = getParam("profile_poll_num");
	    if ( $profile_poll_num && $polls_num[0] < $profile_poll_num ) // limit number of polls
	    {
			$iAct = getParam("profile_poll_act") == 'on' ? 1 : 0;
	    	$query = "INSERT INTO ProfilesPolls ( id_profile, poll_question, poll_answers, poll_results, poll_status, poll_approval )
			VALUES ( '{$member['ID']}', '" . process_db_input($_POST['question']) . "', '$poll_answers', '$poll_results', 'active', '$iAct')";
	        db_res( $query );

		$message = _t("_poll created");

	    }
	}
    }




// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = ThisPageMainCode();

// --------------- [END] page components


PageCode();


// --------------- page components functions

/**
 * page code function
 */
function ThisPageMainCode()
{
	global $logged;
	global $member;


// create poll section -------------------------------------------------------------------
	$ret .= '<div class="createPollSection">';

	$query = "SELECT COUNT(id_poll) FROM ProfilesPolls WHERE id_profile = '{$member['ID']}'";
	$ras_arr = db_arr( $query );

	$polls_num = db_arr( $query );
	$profile_poll_num = getParam("profile_poll_num");

	if ( $profile_poll_num && $polls_num[0] < $profile_poll_num ) // limit number of polls
	    $ret .= ShowPollCreationForm();
	else
	    $ret .= _t('_max_poll_reached');

	$ret .= '</div>';


// show polls section --------------------------------------------------------------------

	$ret .= '<div class="pollContainer" id="pol_container">';
	$ret .= '<div class="clear_both"></div>';
	$query = "SELECT id_poll, poll_status FROM ProfilesPolls WHERE id_profile = '{$member['ID']}'";
	$polls_num = db_res( $query );

	while( $poll_arr = mysql_fetch_array ( $polls_num ) )
	{

	    $uID = $poll_arr['id_poll'];

	    $ret .= '<div id="pol_container_pol_' . $uID . '" class="controlsDiv">';
	    $ret .= ShowPoll( $uID );

	    $ret .= '<div class="innerControlBlock">';

	    //$ret .= '<div class="controlsHeader">' . _t('_controls') . ':</div>';

		$sCurStatus = '';
		$sCTStatus = '';
		$status_change_to = '';
		if ( 'active' == $poll_arr['poll_status'] ) {
			$sCurStatus = _t('_Active');
			$sCTStatus = _t('_Disabled');
			$status_change_to = 'disabled';
		} else {
			$sCurStatus = _t('_Disabled');
			$sCTStatus = _t('_Active');
			$status_change_to = 'active';
		}

	    $ret .= "<span id=\"poll_status_$uID\" style=\"padding: 0px 2px;\"></span>";

	    $ret .= '<span class="deleteDiv"><a href="#" onclick=" if ( window.confirm(\'' . _t('_are you sure?') . '\') ) { send_data( \'\', \'delete\', \'\', \'' . $uID . '\' ); del_question_bar( document.getElementById(\'pol_container\'), document.getElementById(\'pol_container_pol_' . $uID . '\') ); } return false;">'. _t('_delete') .'</a></span>';
	    
		$ret .= '<script language="javascript">
			poll_status_show( \'' . $uID . '\', \'poll_status_' . $uID . '\', \'' . $poll_arr['poll_status'] . '\', \'' . $status_change_to . '\', \'' . $sCurStatus . '\', \'' . $sCTStatus . '\' );
	    	    </script>';


	    $ret .= '</div>';


	    $ret .= '</div>';

	}

// if no polls
	if ( !$uID )
	    $ret .= _t_err('_no poll');
	$ret .= '<div class="clear_both"></div>';
	$ret .= '</div>';

	return $ret;


}

function ShowPollCreationForm()
{
	ob_start();
	?>
	<form id="poll" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
		<input id="next_val" name="next_val" type="hidden" value="1" />
		<table id="questions">
			<tr>
				<th colspan="2"><?= _t('_create poll') ?></th>
			</tr>
			<tr>
				<td class="form_label"><?= _t('_question') ?>:</td>
				<td class="form_value">
					<input id="question" name="question">
				</td>
			</tr>
			<tr>
				<td class="form_label"><?= _t('_answer variants') ?>:</td>
				<td class="form_value">
					<div id="questions_bar_cont"></div>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="form_colspan">
				<a href="javascript:void(0);"
				  onclick="add_question_bar( 'questions_bar_cont', 'next_val', true ); return false;"><?= _t('_add answer') ?></a>
				|
				<a href="javascript:void(0);" onclick="document.getElementById('poll').submit(); return false;"><?= _t('_generate poll') ?></a>
				</td>
			</tr>
		</table>
	</form>
	<script type="text/javascript">
		add_question_bar( 'questions_bar_cont', 'next_val', false );
		add_question_bar( 'questions_bar_cont', 'next_val', false );
	</script>
	<?
	
	return ob_get_clean();
}

?>
