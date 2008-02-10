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

$_page['name_index']	= 129;
$_page['css_name']		= 'contacts.css';

$logged['member'] = member_auth(0);

$memberID = (int)$_COOKIE['memberID'];

$_page['header'] = _t( "_My Contacts" );
$_page['header_text'] = _t( "_My Contacts" );

$free_mode = getParam("free_mode") == "on" ? 1 : 0;

$_ni = $_page['name_index'];


// do actions
if( $_POST['HotList_i_del'] )        delFromList( 'HotList',       'ID',     'Profile' );	
if( $_POST['FriendList_i_del'] )     delFromList( 'FriendList',    'ID',     'Profile' );
if( $_POST['BlockList_i_del'] )      delFromList( 'BlockList',     'ID',     'Profile' );
if( $_POST['VKisses_i_del'] )        delFromList( 'VKisses',       'ID',     'Member' );
if( $_POST['ProfilesTrack_i_del'] )  delFromList( 'ProfilesTrack', 'Member', 'Profile' );

if( $_POST['VKisses_me_del'] )       delFromList( 'VKisses',       'Member',  'ID' );
if( $_POST['FriendList_me_del'] )    delFromList( 'FriendList',    'Profile', 'ID' );
if( $_POST['ProfilesTrack_me_del'] ) delFromList( 'ProfilesTrack', 'Profile', 'Member' );

if( $_POST['FriendList__del'] )      delFromList( 'FriendList',    'ID',     'Profile', true );

if( $_POST['HotList_me_add'] )       addToList(   'HotList',       'ID',     'Profile' );
if( $_POST['BlockList_me_add'] )     addToList(   'BlockList',     'ID',     'Profile' );

if( $_POST['FriendList_me_add'] )    approveFriendInvites();


switch( $_GET['show'] )
{
	case 'hot':
		if( $_GET['list'] != 'me' )
			$ret .= PageCompListMembers( 'i', 'HotList', 'ID', 'Profile' );
		if( $_GET['list'] != 'i' )
			$ret .= PageCompListMembers( 'me', 'HotList', 'Profile', 'ID' );
	break;
	case 'friends_inv':
		if( $_GET['list'] != 'me' )
			$ret .= PageCompListMembers( 'i', 'FriendList', 'ID', 'Profile', '', 'AND `Check`=0' );
		if( $_GET['list'] != 'i' )
			$ret .= PageCompListMembers( 'me', 'FriendList', 'Profile', 'ID', '', 'AND `Check`=0' );
	break;
	case 'friends':
		$ret .= PageCompListMembers( '', 'FriendList', 'Profile', 'ID', '', 'AND `Check`=1' );
	break;
	case 'block':
		if( $_GET['list'] != 'me' )
			$ret .= PageCompListMembers( 'i', 'BlockList', 'ID', 'Profile' );
		if( $_GET['list'] != 'i' )
			$ret .= PageCompListMembers( 'me', 'BlockList', 'Profile', 'ID' );
	break;
	case 'greet':
		if( $_GET['list'] != 'me' )
			$ret .= PageCompListMembers( 'i', 'VKisses', 'ID', 'Member', ', `Arrived`, `Number`', '', '`Arrived` DESC, ' );
		if( $_GET['list'] != 'i' )
			$ret .= PageCompListMembers( 'me', 'VKisses', 'Member', 'ID', ', `Arrived`, `Number`, `New`', '', '`Arrived` DESC, ' );
	break;
	case 'view':
		if( $_GET['list'] != 'me' )
			$ret .= PageCompListMembers( 'i', 'ProfilesTrack', 'Member', 'Profile', ', `Arrived`', '', '`Arrived` DESC, ' );
		if( $_GET['list'] != 'i' )
			$ret .= PageCompListMembers( 'me', 'ProfilesTrack', 'Profile', 'Member', ', `Arrived`', '', '`Arrived` DESC, ' );
	break;
	case 'all':
	default:
		if( $_GET['list'] != 'me' )
		{
			$ret .= PageCompListMembers( 'i', 'HotList', 'ID', 'Profile' );
			$ret .= PageCompListMembers( 'i', 'FriendList', 'ID', 'Profile', '', 'AND `Check`=0' );
			$ret .= PageCompListMembers( 'i', 'BlockList', 'ID', 'Profile' );
			$ret .= PageCompListMembers( 'i', 'VKisses', 'ID', 'Member', ', `Arrived`, `Number`', '', '`Arrived` DESC, ' );
			$ret .= PageCompListMembers( 'i', 'ProfilesTrack', 'Member', 'Profile', ', `Arrived`', '', '`Arrived` DESC, ' );
		}
		
		if( $_GET['list'] != 'i' )
		{
			$ret .= PageCompListMembers( 'me', 'HotList', 'Profile', 'ID' );
			$ret .= PageCompListMembers( 'me', 'FriendList', 'Profile', 'ID', '', 'AND `Check`=0' );
			$ret .= PageCompListMembers( 'me', 'BlockList', 'Profile', 'ID' );
			$ret .= PageCompListMembers( 'me', 'VKisses', 'Member', 'ID', ', `Arrived`, `Number`, `New`', '', '`Arrived` DESC, ' );
			$ret .= PageCompListMembers( 'me', 'ProfilesTrack', 'Profile', 'Member', ', `Arrived`', '', '`Arrived` DESC, ' );
		}
		
		$ret .= PageCompListMembers( '', 'FriendList', 'Profile', 'ID', '', 'AND `Check`=1' );
}

$_page_cont[$_ni]['page_main_code'] = $ret;

send_headers_page_changed();
PageCode();

function PageCompListMembers( $list, $table, $sqlWho, $sqlWhom, $sqlSelectAdd = '', $sqlWhereAdd = '', $sqlOrderAdd = '' )
{
	global $memberID;
	global $site;
	global $dir;
	global $tmpl;
	
	$form = "{$table}_{$list}";
	
	if( $list == '' )
	{
		$query = "
			SELECT
				IF( `$table`.`$sqlWho` = $memberID, `$table`.`$sqlWhom`, `$table`.`$sqlWho` ) AS `$sqlWhom`,
				`Profiles`.`NickName`
				$sqlSelectAdd
			FROM `$table`
			LEFT JOIN `Profiles`
				ON `Profiles`.`ID` = IF( `$table`.`$sqlWho` = $memberID, `$table`.`$sqlWhom`, `$table`.`$sqlWho` )
			WHERE
				( `$table`.`$sqlWho` = $memberID OR `$table`.`$sqlWhom` = $memberID )
				$sqlWhereAdd
			ORDER BY $sqlOrderAdd `Profiles`.`NickName` ASC
		";
	}
	else
	{
		$query = "
			SELECT
				`$table`.`$sqlWho`,
				`$table`.`$sqlWhom`,
				`Profiles`.`NickName`
				$sqlSelectAdd
			FROM `$table`
			LEFT JOIN `Profiles`
				ON `Profiles`.`ID` = `$table`.`$sqlWhom`
			WHERE
				`$table`.`$sqlWho` = $memberID
				$sqlWhereAdd
			ORDER BY $sqlOrderAdd `Profiles`.`NickName` ASC
		";
	}
	
	$rMembers = db_res( $query );
	if( $num_res = mysql_num_rows( $rMembers ) )
	{
		$sWhole = file_get_contents( "{$dir['root']}templates/tmpl_{$tmpl}/contacts_tmpl.html" );
		
		$aMyTmpl = preg_split( "/\{\/?InsertRows\}/", $sWhole );
		$tmplRow = $aMyTmpl[1];
		$sWhole  = "{$aMyTmpl[0]}{InsertRows /}{$aMyTmpl[2]}";
		
		$InsertRows = '';
		$tr_class = 'odd';
		while( $aMember = mysql_fetch_assoc( $rMembers ) )
		{
			$aReplace = array();
			
			$aReplace['ID']            = $aMember[$sqlWhom];
			$aReplace['CheckBoxName']  = "mem[{$aMember[$sqlWhom]}]";
			
			if( $aMember[$sqlWhom] > 0 )
			{
				$aReplace['Thumbnail']     = get_member_icon( $aMember[$sqlWhom], 'left' );
				$aReplace['NickName']      = "<a href=\"".getProfileLink($aMember[$sqlWhom])."\">{$aMember['NickName']}</a>";
				$aReplace['SendGreet']     = sendKissPopUp( $aMember[$sqlWhom] );

				$aReplace['SendMsg']       = "
				  <a href=\"{$site['url']}compose.php?ID={$aMember[$sqlWhom]}\" title=\""._t('_Send Message')."\">
					<img src=\"".getTemplateIcon( 'compose.png' )."\" alt=\""._t('_Send Message')."\" />
				  </a>";
				
			}
			else
			{
				$aReplace['Thumbnail']     = '' ;
				$aReplace['NickName']      = _t( '_Visitor' );
				$aReplace['SendGreet']     = '';
				$aReplace['SendMsg']       = '';
			}
			
			$aReplace['Times']         = $aMember['Number'] ? _t( "_N times", $aMember['Number'] ) : '&nbsp;';
			$aReplace['PicNew']        = $aMember['New'] ? '<img src="'.getTemplateIcon('new.gif').'" class="pic_new" />' : '';
			$aReplace['Date']          = $aMember['Arrived'] ? $aMember['Arrived'] : '&nbsp;';
			$aReplace['tr_class']      = $tr_class;
			
			$sInsertRow = $tmplRow;
			foreach( $aReplace as $key => $val )
				$sInsertRow = str_replace( "{{$key}}", $val, $sInsertRow );
			
			$sInsertRows .= $sInsertRow;
			$tr_class = ( $tr_class == 'odd' ? 'even' : 'odd' );
		}
		
		
		$aReplace = array();
		
		$aReplace['InsertRows /'] = $sInsertRows;
		$aReplace['Self']         = $_SERVER['PHP_SELF'] . '?show=' . $_GET['show'] . '&amp;list=' . $_GET['list'];
		$aReplace['FormName']     = "{$form}_form";
		$aReplace['CheckAll']     = _t('_Check all');
		$aReplace['UncheckAll']   = _t('_Uncheck all');
		$aReplace['Actions']      = getButtons( $form );
		
		foreach( $aReplace as $key => $val )
			$sWhole = str_replace( "{{$key}}", $val, $sWhole );
		
		// unset "new" flag for kisses
		if( $table == 'VKisses' and $list == 'me' )
			db_res( "UPDATE `VKisses` SET `New`='0' WHERE `Member`=$memberID AND `New`='1'" );
	}
	else
	{
		$sWhole = file_get_contents( "{$dir['root']}templates/tmpl_{$tmpl}/contacts_tmpl_nores.html" );
		$aReplace = array();
		
		$aReplace['NoResults'] = _t('_No members found here');
		
		foreach( $aReplace as $key => $val )
			$sWhole = str_replace( "{{$key}}", $val, $sWhole );
	}
	
	$sWhole = str_replace( '{TableCaption}', getTableName( $form, $num_res ), $sWhole );
	return $sWhole;
}

function getTableName( $form, $num_res )
{
	switch( $form )
	{
		case 'HotList_i':        $ret = _t( '_MEMBERS_YOU_HOTLISTED' ); break;
		case 'FriendList_i':     $ret = _t( '_MEMBERS_INVITE_YOU_FRIENDLIST' ); break;
		case 'BlockList_i':      $ret = _t( '_MEMBERS_YOU_BLOCKLISTED' ); break;
		case 'VKisses_i':        $ret = _t( '_MEMBERS_YOU_KISSED' ); break;
		case 'ProfilesTrack_i':  $ret = _t( '_MEMBERS_YOU_VIEWED' ); break;
		
		case 'HotList_me':       $ret = _t( '_MEMBERS_YOU_HOTLISTED_BY' ); break;
		case 'FriendList_me':    $ret = _t( '_MEMBERS_YOU_INVITED_FRIENDLIST' ); break;
		case 'BlockList_me':     $ret = _t( '_MEMBERS_YOU_BLOCKLISTED_BY' ); break;
		case 'VKisses_me':       $ret = _t( '_MEMBERS_YOU_KISSED_BY' ); break;
		case 'ProfilesTrack_me': $ret = _t( '_MEMBERS_YOU_VIEWED_BY' ); break;
		
		case 'FriendList_':      $ret = _t( '_Friend list' ); break;
	}
	
	if( $num_res )
		$ret .= ": $num_res";
	
	return $ret;
}

function getButtons( $form )
{
	$aButton = array();
	
	switch( $form )
	{
		case 'HotList_i':        $aButton['del'] = _t('_Delete');      break;
		case 'FriendList_i':     $aButton['del'] = _t("_Back Invite"); break;
		case 'BlockList_i':      $aButton['del'] = _t("_Unblock");     break;
		case 'VKisses_i':        $aButton['del'] = _t('_Delete');      break;
		case 'ProfilesTrack_i':  $aButton['del'] = _t('_Delete');      break;
		
		case 'HotList_me':       $aButton['add'] = _t("_Add to Hot List"); break;
		case 'FriendList_me':    $aButton['add'] = _t("_Add to Friend List"); 
		                         $aButton['del'] = _t("_Reject Invite");   break;
		case 'BlockList_me':     $aButton['add'] = _t("_Block");           break;
		case 'VKisses_me':       $aButton['del'] = _t('_Delete');          break;
		case 'ProfilesTrack_me': $aButton['del'] = _t('_Delete');          break;
		
		case 'FriendList_':      $aButton['del'] = _t("_Delete from Friend List"); break;
	}
	
	$ret = '';
	
	foreach( $aButton as $sAct => $sTitle )
		$ret .= " <input type=\"submit\" name=\"{$form}_{$sAct}\" value=\"$sTitle\" disabled=\"disabled\" class=\"submit_button\" /> ";
	
	return $ret;
}

function delFromList( $table, $sqlWho, $sqlWhom, $two_way = false )
{
	global $memberID;
	
	if( $aMem = $_POST['mem'] and is_array( $aMem ) and !empty( $aMem ) )
	{
		foreach( $aMem as $ID => $val )
		{
			$ID = (int)$ID;
			/*if( !$ID or $val != 'on' )
				continue;*/
			
			$query = "DELETE FROM `$table` WHERE `$sqlWho`=$memberID AND `$sqlWhom`=$ID";
			db_res( $query );
			
			if( $two_way )
			{
				$query1 = "DELETE FROM `$table` WHERE `$sqlWhom`=$memberID AND `$sqlWho`=$ID";
				db_res( $query1 );
			}
		}
	}
}

function addToList( $table, $sqlWho, $sqlWhom )
{
	global $memberID;
	
	if( $aMem = $_POST['mem'] and is_array( $aMem ) and !empty( $aMem ) )
	{
		foreach( $aMem as $ID => $val )
		{
			$ID = (int)$ID;
			if( !$ID or $val != 'on' )
				continue;
			
			$tmpArr = db_arr( "SELECT COUNT(*) FROM `$table` WHERE `$sqlWho`=$memberID AND `$sqlWhom`=$ID" );
			
			if( (int)$tmpArr[0] == 0 )
			{
				$query = "INSERT INTO `$table` SET `$sqlWho`=$memberID, `$sqlWhom`=$ID";
				db_res( $query );
			}
		}
	}
}

function approveFriendInvites()
{
	global $memberID;
	
	if( $aMem = $_POST['mem'] and is_array( $aMem ) and !empty( $aMem ) )
	{
		foreach( $aMem as $ID => $val )
		{
			$ID = (int)$ID;
			if( !$ID or $val != 'on' )
				continue;
			
			$query = "UPDATE `FriendList` SET `Check`='1' WHERE `Profile`=$memberID AND `ID`=$ID";
			db_res( $query );
		}
	}
}

?>