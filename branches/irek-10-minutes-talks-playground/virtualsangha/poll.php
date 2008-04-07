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

// --------------- page variables / login

$_page['name_index'] 	= 39;
$_page['css_name']		= 'poll.css';

if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false ) ) )
			$logged['moderator'] = member_auth( 3, false );

$_page['header'] = _t( "_Site Poll" );
$_page['header_text'] = _t( "_Site Poll" );

$ID = (int)$_REQUEST['ID'];

if ( $_POST['vote'] )
{
	if ( PollsVoteAdd() )
		$actionText = _t_action("_Vote accepted");
	else
		$actionText = _t_err("_You already voted");
}
else
	$actionText = '';

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = $actionText . MemberPrintPoll( $ID );

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
	global $ID;

}

/**
 * Add vote
 */
function PollsVoteAdd ( )
{
	global $ID;

	if ( $_COOKIE["polls_question_{$ID}"] > 0 )
		return 0;

	if ( !(int)$_POST['vote'] )
		return 0;

	$res = db_res("UPDATE `polls_a` SET `Votes` = `Votes` + 1 WHERE `IDanswer` = ". (int)$_POST['vote']);

	if ( $res )
		setcookie("polls_question_{$ID}", 1 , time() + ( 10000 * 3600 ), '/' );

	return $res;
}



/**
 * Print a poll
 */
function MemberPrintPoll( $ID )
{

    $queryQuestion = "SELECT `Question` FROM `polls_q` WHERE `Active` = 'on' AND `ID` = $ID";
	$queryAnswers  = "SELECT `IDanswer`, `Answer`, `Votes` FROM `polls_a` WHERE `ID` = $ID";
	
	$aQuestion = db_arr( $queryQuestion );
	$rAnswers  = db_res( $queryAnswers );
	
	if ( !$aQuestion or !mysql_num_rows($rAnswers) )
		return _t_err("_Poll not available");
	
	$aVotes = db_arr( "SELECT SUM(`Votes`) FROM `polls_a` WHERE `ID` = $ID" );
	$iTotalVotes = (int)$aVotes[0];

	ob_start();
	?>
		<form method="post" name="FormVote" action="<?=$_SERVER['PHP_SELF']?>">
			<input type="hidden" name="ID" value="<?=$ID?>" />
			<table class="tableVote">
				<tr><th colspan="2"><?=process_line_output( $aQuestion['Question'] )?></th>
				</tr>
	<?
	
	$j = 1;
	while ( $aAnswer = mysql_fetch_array($rAnswers) )
	{
		if( ($j%2) == 0)
			$add = '2';
		else
			$add = '1';
		
		?>
				<tr>
					<td>
						<input type="radio" onclick="javascript: this.form.submit()" name="vote"
						  value="<?=$aAnswer['IDanswer']?>" ID="l<?=$aAnswer['IDanswer']?>" />
						<label for="l<?=$aAnswer['IDanswer']?>"><?=process_line_output( $aAnswer['Answer'] )?></label>
					</td>
					<td><?=DesignProgressPos( _t("_votes").": ".$aAnswer['Votes'], 100, $iTotalVotes, $aAnswer['Votes'], $add )?></td>
				</tr>
		<?
		$j++;
	}

	?>
			</table>
		</form>
	<?

	return ob_get_clean();
}

?>