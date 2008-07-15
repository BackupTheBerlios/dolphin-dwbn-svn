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
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profile_disp.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'params.inc.php' );

$logged[admin] = member_auth( 1 );
$ADMIN = $logged[admin];

$_page['css_name'] = 'post_mod_polls.css';

// ===================================================================
// ===================================================================

if ( $_POST['confirm'] )
{
    $query = '';
    foreach ( $_POST as $key => $value )
    {

	if ( 'poll' == $value )
	    $query .= " `id_poll` = '$key' or ";
    }

    $query = "UPDATE ProfilesPolls SET `poll_approval` = 1 WHERE " . $query . " 1 = 0 ";

    db_res( $query );

}
elseif ( $_POST['delete'] )
{

    $query = '';
    foreach ( $_POST as $key => $value )
    {

	if ( 'poll' == $value )
	    $query .= " id_poll = '$key' or ";
    }

    $query = "DELETE FROM ProfilesPolls WHERE " . $query . " 1 = 0 ";

    db_res( $query );

}

// ===================================================================
// - GET variables --------------
// ===================================================================

$page		= (int)$_GET[page];
$p_per_page	= (int)$_GET[p_per_page];

//$real_first_p = (int)($page - 1) * $p_per_page;
//$page_first_p = $real_first_p + 1;

$max_photo_height	= $max_photo_height + 2;
$max_photo_width	= $max_photo_width + 2;

if ( !$page )
    $page = 1;

if ( !$p_per_page )
    $p_per_page = 10;

// ------------------------------


$sql = "SELECT COUNT(*) FROM ProfilesPolls WHERE `poll_approval` = '0'";

$p_num = db_arr( $sql );
$p_num = $p_num[0];
$pages_num = ceil( $p_num / $p_per_page );

$real_first_p = (int)($page - 1) * $p_per_page;
$page_first_p = $real_first_p + 1;

$result = db_res( "SELECT `id_profile`, `id_poll` FROM ProfilesPolls WHERE `poll_approval` = 0 LIMIT $real_first_p, $p_per_page;" );

$_page['header'] = "Polls PostModeration";
$_page['header_text'] = "Polls that need to be checked by admin";

$_page['js'] = 1;

$_page['js_name'] = 'profile_poll.js';

TopCodeAdmin();

ContentBlockHead("Created (and yet not approved) profile polls:");

?>

<center>
<?= ResNavigationRet( 'PollsUpper', 0 ); ?>
</center>

<form name="prf_form" method="post" action="post_mod_ppolls.php<? $get_vars = get_vars(); echo substr($get_vars, 0, strlen($get_vars) - 1); ?>">

<?
//"

    ?>
		<div style="border: solid 0px #000000;">
	<?


	if( 0 < mysql_num_rows($result) )
	{
	    while ( $arr = mysql_fetch_array( $result ) )
	    {
			?>
			<div style="float:left;margin-right:5px;margin-top:5px;border: solid 1px #cccccc;background-color: #f6f6ff;">
			    <div style="position:relative; padding-top:25px;width:260px; height:160px;">
					<div style="position:absolute; top:4px; left:4px;">
						<input type="checkbox" id="<?=$arr['id_poll']?>" value="poll" name="<?=$arr['id_poll']?>">
					</div>
					<label for="<?=$arr['id_profile']?>"><?=ShowPoll($arr['id_poll'])?></label>
			    </div>
				<div style="text-align:center; background-color:#FFFFFF;padding:3px;">
					<a href="<?=$site['url']?>profile.php?ID=<?=$arr['id_profile']?>" target="_blank">View Profile</a> |
					<a href="<?=$site['url']?>profile_edit.php?ID=<?=$arr['id_profile']?>" target="_blank">Edit Profile<a>
				</div>
			</div>
			<?
	    }
	}
	else
	{
		?>
			<div style="text-align:center; line-height:25px; vertical-align:middle; background-color:#c2daeb; font-weight:bold;">There is nothing to approve </div>
		<?
	}
    ?>
	</div>
	<?

    if ( $p_num )
    {
?>
<div class="clear_both"></div>
<div style="">
<table class=text border=0 width=590 align=center>
<tr>
    <td width="65">&nbsp;<a href="javascript: void(0);" onclick="setCheckboxes( 'prf_form', true ); return false;">Check all</a>/</td>
	<td align="left" width="70"> <a href="javascript: void(0);" onclick="setCheckboxes( 'prf_form', false ); return false;">Uncheck all</a>&nbsp;</td>
    <td width="100">Selected polls:</td>
    <td width="55"><input class=text type=submit name="delete" value="Delete"></td>
    <td width="1">|</td>
    <td width="110"><input class=text type=submit name="confirm" value="Confirm"></td>
    <td>&nbsp;</td>
</tr>
</table>
</div>
<?
    }
?>

</form>

<center>
<?= ResNavigationRet( 'PollsLower', 0 ); ?>
</center>

<?
ContentBlockFoot();

BottomCode();
?>
