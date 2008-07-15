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

// --------------- page variables

$_page['name_index'] 	= 28;
$_page['css_name']		= 'polls.css';

$logged['member'] = member_auth( 0, false );

$_page['header'] = _t( "_Site Polls" );
$_page['header_text'] = _t( "_Site Polls" );

// this is dynamic page -  send headers to do not cache this page
send_headers_page_changed();

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = MemberPrintPolls( );

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function MemberPrintPolls( )
{
	$query = "SELECT `ID`, `Question` FROM `polls_q` WHERE `Active` = 'on' ORDER BY `Question`";

	$res = db_res($query);

	if ( !$res or !mysql_num_rows($res) )
		return "<div align=center>". _t("_No polls available") ."</div>";
	
	$ret =
			'<div style="position:relative;">
				<div class="clear_both"></div>';
	
	while ( $arr = mysql_fetch_array($res))
		$ret .= MemberPrintPoll( $arr['ID'] );
	
	$ret .= 
				'<div class="clear_both"></div>
			</div>';

	return $ret;
}

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
	<div class="tableVote_wrapper">
		<form method="post" name="FormVote" action="poll.php">
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
	</div>
	<?

	return ob_get_clean();
}


?>
