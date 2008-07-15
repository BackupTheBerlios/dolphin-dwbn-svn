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
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'languages.inc.php' );

$member['ID'] = (int)$_COOKIE['memberID'];


if ( 'vote' == $_GET['action'] ) {
	if (  '' != $_GET['ID'] ) {
	    if ( '' != $_GET['param'] ) {
			$query = "SELECT poll_results FROM ProfilesPolls WHERE id_poll='" . (int)$_GET['ID'] . "'";
			$res_arr = db_arr( $query );

			$results = explode( ';', $res_arr['poll_results'] );
			$results[$_GET['param']]++;
			$poll_total_votes = array_sum($results);
			$results = implode(';', $results);

			$iPollID = (int)$_GET['ID'];
			if ( $_COOKIE["profile_polls_question_{$iPollID}"] > 0 ) {
				//print 'Dublicate';
			} else {
				$query = "UPDATE ProfilesPolls SET poll_results = '{$results}', poll_total_votes = '{$poll_total_votes}' WHERE id_poll='{$iPollID}'";
				$vProfPollRes = db_res( $query );
				if ( $vProfPollRes ) setcookie("profile_polls_question_{$iPollID}", 1 , time() + ( 10000 * 3600 ), '/' );
			}
	    }
	}
}


header('Content-Type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';

// =====================================================================
// TODO : protect from malicious calls !!!!!!
// =====================================================================
// DELETE action -------------------------------------------------------
    if ( 'delete' == $_GET['action'] )
    {
		if (  '' != $_GET['ID'] )
		{
		    $query = "DELETE FROM ProfilesPolls WHERE id_poll = '" . (int)$_GET['ID'] . "' AND id_profile = '{$member['ID']}' LIMIT 1";
		    if ( db_res( $query ) )
			echo '<answer>no results</answer>';
		}
    }
// CHANGE STATUS action ------------------------------------------------
    elseif ( 'status' == $_GET['action'] )
    {
		if (  '' != $_GET['ID'] && $_GET['param'] )
		{
		    $query = "UPDATE ProfilesPolls SET `poll_status` = '{$_GET['param']}' WHERE id_poll = '" . (int)$_GET['ID'] . "' AND id_profile = '{$member['ID']}' LIMIT 1";
		    if ( db_res( $query ) )
			echo '<answer>no results</answer>';
		}
    }
// VOTE action --------------------------------------------------------
    elseif ( 'vote' == $_GET['action'] )
    {

	if (  '' != $_GET['ID'] )
	{
	   /* if ( '' != $_GET['param'] )
	    {
			$query = "SELECT poll_results FROM ProfilesPolls WHERE id_poll='" . (int)$_GET['ID'] . "'";
			$res_arr = db_arr( $query );

			$results = explode( ';', $res_arr['poll_results'] );
			$results[$_GET['param']]++;
			$poll_total_votes = array_sum($results);
			$results = implode(';', $results);

			$iPollID = (int)$_GET['ID'];
			if ( $_COOKIE["profile_polls_question_{$iPollID}"] > 0 ) {
				//print 'Dublicate';
			} else {
				$query = "UPDATE ProfilesPolls SET poll_results = '{$results}', poll_total_votes = '{$poll_total_votes}' WHERE id_poll='{$iPollID}'";
				$vProfPollRes = db_res( $query );
				if ( $vProfPollRes ) setcookie("profile_polls_question_{$iPollID}", 1 , time() + ( 10000 * 3600 ), '/' );
			}
	    }*/

	    $query = "SELECT * FROM ProfilesPolls WHERE id_poll='" . (int)$_GET['ID'] . "'";
	    $res_arr = db_arr( $query );

	    $answers_points = explode(';', $res_arr['poll_results'] );

	    $answers_names = explode('<delim>', $res_arr['poll_answers'] );

	    echo '<results>';

	    $poll_total_votes = $res_arr['poll_total_votes'];

	    foreach ($answers_points as $value)
	    {
			if  ( '' != $value )
			{
			    echo '<answer_point>';
			    echo round( (0 != $poll_total_votes ? (( $value / $poll_total_votes ) * 100) : 0), 1);
			    echo '</answer_point>';

			    echo '<answer_num>';
			    echo htmlspecialchars ( $value );
			    echo '</answer_num>';
			}
	    }


	    foreach ($answers_names as $value)
	    {
			if  ( '' != $value )
			{
			    echo '<answer_name>';
			    echo htmlspecialchars ( $value );
			    echo '</answer_name>';
			}
	    }

	    echo '</results>';

	}

    }
    else if ( 'questions' == $_GET[action] && $_GET[ID] )
    {
		$query = "SELECT * FROM ProfilesPolls WHERE id_poll = '" . (int)$_GET[ID] . "'";

		$res_arr = db_arr( $query );

        echo '<poll>';

        echo '<question>';
		echo htmlspecialchars ( $res_arr['poll_question'] );
		echo '</question>';

		$questions = explode( '<delim>', $res_arr['poll_answers']);

        foreach ($questions as $value)
        {
		    if ( '' != $value )
		    {
				echo '<answer>';
				echo htmlspecialchars ( $value );
				echo '</answer>';
		    }

		}

		echo '</poll>';
    }
    else
    {
		echo '<answer>no results</answer>';
    }
?>