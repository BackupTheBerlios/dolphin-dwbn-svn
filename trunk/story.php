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

// --------------- page variables and login

$_page['name_index'] 	= 20;
$_page['css_name']		= 'story.css';
$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorCompactJS;

$ADMIN = member_auth( 1, false );
$logged['admin'] = $ADMIN;
if ( !$ADMIN )
	$logged['member'] = member_auth();

$member['ID'] = (int)$_COOKIE['memberID'];
$member['Password'] = $_COOKIE['memberPassword'];

$_page['header'] = _t( "_COMPOSE_STORY_H" );
$_page['header_text'] = _t( "_COMPOSE_STORY_H1" );
//$_page['header_text'] = ('g4' != $tmpl) ? _t( "_COMPOSE_STORY_H1" ) : "<img src=\"{$site['images']}feedback.gif\">";


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
	global $dir;
	global $tmpl;
	global $ADMIN;
	global $max_l;
	global $max_h;
	global $short_date_format;

	$max_l  = getParam( "max_story_text" );
	$max_h  = getParam( "max_story_header" );

	ob_start();

	if ( !$max_l ) $max_l = 4096;
	if ( !$max_h ) $max_h = 32;
	$action_result = "";

	if ( $_POST['action'] == 'new' )
	{
		if ( strlen($_POST['header']) )
		{
			if ( MemberAddStory() )
				$action_result .= _t( "_STORY_ADDED" );
			else
				$action_result .= _t_err( "_STORY_ADDED_FAILED" );
		}
		else
			$action_result .= _t_err( "_STORY_EMPTY_HEADER" );
	}

	if ( $_POST['action'] == 'edit' && ((int)$_POST['edit_id'] != 0) )
	{
		if ( strlen($_POST['header']) )
		{
			if ( MemberEditStory() )
				$action_result .= _t( "_STORY_UPDATED" );
			else
				$action_result .= _t_err( "_STORY_UPDATED_FAILED" );
		}
		else
			$action_result .= _t_err( "_STORY_EMPTY_HEADER" );
	}

	if ( $_POST['do_delete'] and $_POST['select_story'] and is_array($_POST['select_story']) )
	{
		$sqlAdd = $ADMIN ? '' : "AND `Sender`=" . (int)$_COOKIE['memberID'];
		
		foreach( $_POST['select_story'] as $iStoryID )
		{
			$iStoryID = (int)$iStoryID;
			if( !$iStoryID )
				continue;
			
			$sQuery = "DELETE FROM `Stories` WHERE `ID`=$iStoryID $sqlAdd";
			db_res( $sQuery );
		}
	}

	if ( $ADMIN and $_POST['do_activate'] and $_POST['select_story'] and is_array($_POST['select_story']) )
	{
		foreach( $_POST['select_story'] as $iStoryID )
		{
			$iStoryID = (int)$iStoryID;
			if( !$iStoryID )
				continue;
			
			$sQuery = "UPDATE `Stories` SET `active`='on' WHERE `ID`=$iStoryID";
			db_res( $sQuery );
		}
	}

	if ( $ADMIN and $_POST['do_deactivate'] and $_POST['select_story'] and is_array($_POST['select_story']) )
	{
		foreach( $_POST['select_story'] as $iStoryID )
		{
			$iStoryID = (int)$iStoryID;
			if( !$iStoryID )
				continue;
			
			$sQuery = "UPDATE `Stories` SET `active`='' WHERE `ID`=$iStoryID";
			db_res( $sQuery );
		}
	}
	
	
	
	
	
	if ( !$ADMIN )
		echo "<table width=\"100%\" cellpadding=4 cellspacing=4><td align=center class=text2>\n";
	else
		echo "<div style=\"padding: 10px 10px 10px 10px;\">";

	if ( strlen($action_result) )
		echo "<br /><center><div>{$action_result}</div></center><br />\n";

	echo MemberPrintStories();

	if ( !$ADMIN || ((int)$_GET['edit_id']) != 0 )
	{
		if ( (int)$_GET['edit_id'] != 0 )
			$story_arr = db_arr( "SELECT * FROM `Stories` WHERE `ID` = ". (int)$_GET['edit_id'] );
		
		$actions = '';
		if ( (int)$_GET['edit_id'] )
		{
			$actions .= '<input type="hidden" name="action"  value="edit" />';
			$actions .= '<input type="hidden" name="sender"  value="' . $story_arr['Sender'] . '" />';
			$actions .= '<input type="hidden" name="edit_id" value="' . $story_arr['ID'] . '" />';
			$actions .= '<input class="no" type="submit" value="'._t('_Update story').'" />';
		}
		else
		{
			$actions .= '<input type="hidden" name="action" value="new" />';
			$actions .= '<input class="no" type="submit" value="'._t( "_Add story" ) .'" />';
		}
		
		
		$aReplace = array();
		
		$aReplace['icons']    = $site['icons'];
		$aReplace['header_l'] = _t( "_Header" );
		$aReplace['text_l']   = _t( "_Text" );
		
		$aReplace['form_action']  = $_SERVER['PHP_SELF'];
		$aReplace['story_header'] = htmlspecialchars( $story_arr['Header'] );
		$aReplace['story_text']   = htmlspecialchars( $story_arr['Text'] );
		
		$aReplace['actions'] = $actions;
		
		
		$sForm = file_get_contents( "{$dir['root']}templates/tmpl_{$tmpl}/story_edit.html" );
		foreach( $aReplace as $key => $val )
			$sForm = str_replace( "__{$key}__", $val, $sForm );
		
		echo $sForm;
	}

	if ( !$ADMIN )
		echo "</td></table>\n";
	else
		echo "</div>\n";

	$ret = ob_get_clean();

	return $ret;
}

/**
 * Print Stories
 */
function MemberPrintStories()
{
	global $member;
	global $ADMIN;
	
	$php_date_format = getParam( 'php_date_format' );

	if ( $ADMIN )
		$res = db_res( "SELECT `ID`, UNIX_TIMESTAMP( `Date` ) AS `Date`, `Sender`, `Header`, `Text`, `active` FROM `Stories`                                  ORDER BY `Date` DESC" );
	else
		$res = db_res( "SELECT `ID`, UNIX_TIMESTAMP( `Date` ) AS `Date`, `Sender`, `Header`, `Text`, `active` FROM `Stories` WHERE `Sender` = {$member['ID']} ORDER BY `Date` DESC" );
	
	if ( $ADMIN && !mysql_num_rows($res) )
		return MsgBox( 'No stories available' );
	
	ob_start();
	
	?>
<form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
	<table class="stories_list">
		<tr>
			<th><?= _t( '_Select' ) ?></th>
			<th><?= _t( '_Status' ) ?></th>
			<th><?= _t( '_Date' ) ?></th>
			<th><?= _t( '_Title' ) ?></th>
		</tr>
	<?
	
	$story_line_class = 'odd';
	
	while ( $story_arr = mysql_fetch_array($res) )
	{
		$story_status = _t( $story_arr['active'] ? '_active_story' : '_not_active_story' );
		$story_status_class = $story_arr['active'] ? 'status_active' : 'status_inactive';
		$story_header = process_line_output( $story_arr['Header'] );
		
		?>
		<tr class="line_<?= $story_line_class ?>">
			<td>
				<input type="checkbox" name="select_story[]" value="<?= $story_arr['ID'] ?>" />
			</td>
			<td class="<?= $story_status_class ?>">
				<?= $story_status ?>
			</td>
			<td><?= date( $php_date_format, $story_arr['Date'] ) ?></td>
			<td>
				<b><a href="story_view.php?ID=<?= $story_arr['ID'] ?>"><?= $story_header ?></a></b>
				(<a href="story.php?edit_id=<?= $story_arr['ID'] ?>&sender=<?= $story_arr['Sender'] ?>"><?= _t('_Edit') ?></a>)
			</td>
		</tr>
		<?
		
		$story_line_class = $story_line_class == 'odd' ? 'even' : 'odd';
	}
	?>
		<tr>
			<td colspan="4">
	<?
	if( $ADMIN )
	{
		?>
				<input type="submit" name="do_activate" value="Make active" />
				<input type="submit" name="do_deactivate" value="Make inactive" />
				
		<?
	}
	?>
				<input type="submit" name="do_delete" value="<?= _t( '_Delete' ) ?>" onclick="return confirm( '<?= _t('_Are you sure') ?>?' );" />
			</td>
		</tr>
	</table>
</form>
	<!-- <a href="story.php?action=delete&delete_id=<?= $story_arr['ID'] ?>&sender=<?= $story_arr['Sender'] ?>"><?= _t('_Delete') ?></a> -->
	<?
	
	return ob_get_clean();
}

/**
 * Add story
 */
function MemberAddStory()
{
    global $max_l;
    global $max_h;
    global $member;
    global $ADMIN;

	if ( $ADMIN )
		return 0;

	$story_text = strlen( $_POST['text'] ) > $max_l ? "LEFT ( '". addslashes(clear_xss( process_pass_data($_POST['text']) )) ."', $max_l )" : "'". addslashes(clear_xss( process_pass_data($_POST['text'] ))) ."'";
	$story_header = strlen( $_POST['header'] ) > $max_h ? "LEFT ( '". process_db_input( $_POST['header'] ) ."', $max_h )" : "'". process_db_input( $_POST['header'] ). "'";
	$story_sender = (int)$member['ID'];
	$sQuery = "
		INSERT INTO		`Stories`
		SET				`Date` = NOW(),
						`Sender` = '$story_sender',
						`Header` = $story_header,
						`Text` = $story_text
	";
	$res = db_res( $sQuery );

	return $res;
}

/**
 * Cange story
 */
function MemberEditStory()
{
	global $max_l;
	global $max_h;
	global $member;
	global $ADMIN;

	$story_id = (int)$_POST['edit_id'];
	$story_text = strlen( $_POST['text'] ) > $max_l ? "LEFT ( '". addslashes(clear_xss( process_pass_data($_POST['text'] ))) ."', $max_l )" : "'". addslashes(clear_xss( process_pass_data($_POST['text'] ))) ."'";
	$story_header = strlen( $_POST['header'] ) > $max_h ? "LEFT ( '". process_db_input( $_POST['header'] ) ."', $max_h )" : "'". process_db_input( $_POST['header'] ). "'";
	$story_sender = ( $ADMIN ? (int)$_POST['sender'] : $member['ID'] );
	$story_active_add = ( $ADMIN ? '' : ", `active`=''" ); //if admin logged, don't update status. if member - set inactive
	
	$sQuery = "UPDATE `Stories` SET `Date` = NOW(), `Header` = $story_header, `Text` = $story_text $story_active_add WHERE `ID` = $story_id AND `Sender` = $story_sender";
	$res = db_res( $sQuery );

	return $res;
}

?>
