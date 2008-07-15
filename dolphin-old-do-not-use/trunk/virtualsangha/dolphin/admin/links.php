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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

$logged['admin'] = member_auth( 1, true, true );
$_page['css_name'] = 'links.css';

TopCodeAdmin();
ContentBlockHead("Manage links");

if( !$demo_mode && 'add' == $_GET['action'] )
{
	echo GetLinkForm();
}
elseif( !$demo_mode && 'addnew' == $_POST['action'])
{
	if( 1 > strlen($_POST['title']) )
	{
		echo GetActionText( 'Please enter title' );
		echo GetLinkForm( false, 0, true );

	}
	elseif( 1 > strlen($_POST['URL']) )
	{
		echo GetActionText( 'Please enter URL' );
		echo GetLinkForm( false, 0, true );
	}
	elseif( 1 > strlen($_POST['desc']) )
	{
		echo GetActionText( 'Please enter description' );
		echo GetLinkForm( false, 0, true );
	}
	else
	{
		if( db_res( "INSERT INTO `Links` SET `Title` = '" . $_POST['title'] . "', `URL` = '" . $_POST['URL'] . "', `Description` = '" . $_POST['desc'] . "';" ) )
		{
			echo GetActionText( 'link successfully added', 1);
			echo GetLinkList();
		}
		else
		{
			echo GetActionText( 'action failed');
			echo GetLinkList();
		}
	}

}
elseif( !$demo_mode && 'edit' == $_GET['action'])
{
	echo GetLinkForm( true, $_GET['linkID'] );
}
elseif( !$demo_mode && 'update' == $_POST['action'] )
{
	if( 1 > strlen($_POST['title']) )
	{
		echo GetActionText( 'Please enter title' );
		echo GetLinkForm( false, 0, true );

	}
	elseif( 1 > strlen($_POST['URL']) )
	{
		echo GetActionText( 'Please enter URL' );
		echo GetLinkForm( false, 0, true );
	}
	elseif( 1 > strlen($_POST['desc']) )
	{
		echo GetActionText( 'Please enter description' );
		echo GetLinkForm( false, 0, true );
	}
	else
	{
		if( db_res( "UPDATE `Links` SET `Title` = '" . process_db_input($_POST['title']) . "', `URL` = '" . process_db_input($_POST['URL']) . "', `Description` = '" . process_db_input($_POST['desc']) . "' WHERE `ID` = " . (int)$_POST['linkID'] . ";" ) )
		{
			echo GetActionText( 'link updated successfully', 1 );
			echo GetLinkList();
		}
		else
		{
			echo GetActionText( 'action failed');
			echo GetLinkList();
		}
	}
}
elseif( !$demo_mode && 'delete' == $_GET['action'] )
{
	if( db_res( "DELETE FROM `Links` WHERE `ID` = " . (int)$_GET['linkID'] ) )
	{
		echo GetActionText( 'link deleted successfully', 1 );
		echo GetLinkList();
	}
	else
	{
		echo GetActionText( 'action failed');
		echo GetLinkList();
	}
}
else
{
	echo GetLinkList();
}

ContentBlockFoot();
BottomCode();

function GetLinkList()
{
	global $site;

	$link_query = "SELECT `ID`, `Title`, `URL`, `Description` FROM `Links`";
	$link_res = db_res($link_query);

	$link_count = db_arr("SELECT COUNT(ID) FROM `Links`");
	$link_count = $link_count['0'];
	$links_onpage = 10;

	$ret = '';
	$ret .= '<div class="linkAdd">';
		$ret .= '<a href="' . $site['url_admin'] . 'links.php?action=add">';
			$ret .= 'Add New Link';
		$ret .= '</a>';
	$ret .= '</div>';
	$j = 1;
	while( $link_arr = mysql_fetch_assoc($link_res))
	{
		if( ($j%2) == 0 )
		{
			$add = 'style="background-color:#E6E6E6;"';
		}
		else
		{
			$add = '';
		}

		$ret .= '<div class="link_block" ' . $add . '>';
			$ret .= '<div class="link_title">';
				$ret .= '<a href="' . $link_arr['URL'] . '">';
					$ret .= process_line_output($link_arr['Title']);
				$ret .= '</a>';
			$ret .= '</div>';
			$ret .= '<div>';
				$ret .= process_text_output($link_arr['Description']);
			$ret .= '</div>';
			$ret .= '<div style="text-align:right;">';
				$ret .= '<a href="' . $site['url_admin']  . 'links.php?linkID=' . $link_arr['ID'] . '&action=edit">';
					$ret .= 'Edit';
				$ret .= '</a>';
				$ret .= '&nbsp;||&nbsp;';
				$ret .= '<a href="' . $site['url_admin']  . 'links.php?linkID=' . $link_arr['ID'] . '&action=delete">';
					$ret .= 'Delete';
				$ret .= '</a>';
			$ret .= '</div>';
		$ret .= '</div>';

		$j++;
	}

	return $ret;
}

function GetLinkForm( $edit = false, $linkID = 0, $use_post_data = false )
{
	global $site;

	$ret = '';
	if( $edit )
	{
		$link_arr = db_arr( "SELECT `ID`, `Title`, `URL`, `Description` FROM `Links` WHERE `ID` = '" . (int)$linkID . "';");
		$value_title = htmlspecialchars($link_arr['Title']);
		$value_URL = htmlspecialchars($link_arr['URL']);
		$value_desc = htmlspecialchars($link_arr['Description']);
	}
	elseif( $use_post_data )
	{
		$value_title = htmlspecialchars( process_pass_data($_POST['title']) );
		$value_URL = htmlspecialchars( process_pass_data($_POST['URL']) );
		$value_desc = htmlspecialchars( process_pass_data($_POST['desc']) );
	}
	else
	{
		$value_title = '';
		$value_URL = '';
		$value_desc = '';
	}


	$ret .= '<div class="link_block">';
		$ret .= '<form action="' . $site['url_admin'] . 'links.php" method="post">';
			$ret .= '<table cellpadding="0" cellspacing="1" border="0" width="70%" align="center">';
				$ret .= '<tr>';
					$ret .= '<td>';
						$ret .= 'Title';
					$ret .= '</td>';
					$ret .= '<td>';
						$ret .= '<input type="text" class="link_text_input" name="title" value="' . $value_title . '" />';
					$ret .= '</td>';
				$ret .= '</tr>';
				$ret .= '<tr>';
					$ret .= '<td>';
						$ret .= 'URL';
					$ret .= '</td>';
					$ret .= '<td>';
						$ret .= '<input type="text" class="link_text_input" name="URL" value="' . $value_URL . '"  />';
					$ret .= '</td>';
				$ret .= '</tr>';
				$ret .= '<tr>';
					$ret .= '<td>';
						$ret .= 'Description';
					$ret .= '</td>';
					$ret .= '<td>';
						$ret .= '<textarea name="desc" class="link_textarea">' . $value_desc  . '</textarea>';
					$ret .= '</td>';
				$ret .= '</tr>';
				$ret .= '<tr>';
					$ret .= '<td colspan="2" align="center">';
					if( $edit )
					{
						$ret .= '<input type="hidden" name="linkID" value="' . $linkID . '">';
						$ret .= '<input type="hidden" name="action" value="update">';
					}
					else
					{
						$ret .= '<input type="hidden" name="action" value="addnew">';
					}

						$ret .= '<input type="submit" value="Save" />';
					$ret .= '</td>';

				$ret .= '</tr>';
			$ret .= '</table>';
		$ret .= '';
	$ret .= '</div>';

	return $ret;
}

function GetActionText( $text, $success = '')
{
	global $site;

	$ret = '';
	if( $success )
	{
		$ret .= '<div style="position:relative; border:1px solid green; margin-bottom:20px; font-weight:bold; text-align:center; color:green; padding:3px;">';
			//$ret .= '<div style="font-weight:bold; text-align:center; color:green;">';
				$ret .= $text;
			//$ret .= '</div>';
		$ret .= '</div>';
/*
		$ret .= '<div style="position:relative; text-align:center;">';
			$ret .= '<a href="' . $site['url_admin'] . 'links.php">Back to link list</a>';
		$ret .= '</div>';
*/
	}
	else
	{
		$ret .= '<div style="position:relative; border:1px solid red; margin-bottom:20px; font-weight:bold; text-align:center; color:red; padding:3px;">';
			$ret .= '<div style="font-weight:bold; text-align:center; color:red;">';
				$ret .= $text;
			$ret .= '</div>';
		$ret .= '</div>';
/*		$ret .= '<div style="position:relative; text-align:center;">';
			$ret .= '<a href="' . $site['url_admin'] . 'links.php">Back to link list</a>';
		$ret .= '</div>';
*/
	}

	return $ret;

}

?>
