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

require_once( 'header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'banners.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'membership_levels.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'params.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'languages.inc.php');
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php');
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/functions.php" );
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplMenu.php" );

if( !@eval( file_get_contents( BX_DIRECTORY_PATH_INC . 'menu_content.inc.php' ) ) )
{
	if( basename( dirname( $_SERVER['PHP_SELF'] ) ) != $admin_dir )
	{
		$aTopMenu = array();
		$aMenu    = array();
		echo '<b>Warning:</b> Please rebuild the menu from Admin Panel -> Builders -> Navigation Menu Builder';
	}
}

$aMenuInfo = array();

function MemberMenuDesign ( $iUserId = 0 )
{
	global $site;
	global $aMenu;
	global $tmpl;


	global $oTemplConfig;
	$oTemplMenu = new BxTemplMenu( $oTemplConfig );

	$aMenuIcon = array(
        '1'=>'_member_panel.gif',
        '2'=>'_profile_edit.gif',
        '3'=>'_profile_customize.gif',
        '4'=>'_communicator.gif',
        '5'=>'_chat.gif',
        '6'=>'_forum.gif',
        '7'=>'_membership.jpg',
        '8'=>'_cart.gif',
        '9'=>'_feedback.jpg',
        '10'=>'_gallery.gif',
        '11'=>'_blog.gif',
        '12'=>'_gallery.gif',
        '13'=>'_polls.gif',
        '14'=>'_polls.gif',
        '15'=>'_speed_dating.gif',
        '16'=>'_logout.gif',
        '17'=>'_join.gif',
        '18'=>'_login.gif',
        '19'=>'_profile_photos.gif',
        '20'=>'_forum.gif',
        '21'=>'_chat.gif',
        '22'=>'_groups.gif',
    );

	$sMenuLink = "";
	$sPath = "";
	$sVisible = $iUserId == 0 ? "non": "memb";

	$ret .= '<div class="menu_item_block">';
	foreach ($aMenu as $iVal => $aValue)
	{
		$sIconName = $tmpl == 'dol' ? $aMenuIcon[$iVal] : '';
		if ($aValue['MenuGroup'] != 0) continue;
		$sMenuLink = $iUserId != 0 ? add_id($aValue['Link'], $iUserId) : $aValue['Link'];
		if ( strpos($sMenuLink,'http://') === FALSE )
		{
			$sPath =  "" ;
		}
		else
		{
			$sPath = $sMenuLink;
			$sMenuLink = "";
		}
		$jFunc = strlen($aValue['Onclick']) > 0 ? $aValue['Onclick'] : "";
		
		$memberPass = getPassword( $iUserId );
		$jFunc = str_replace( '{*}',          $iVal,        $jFunc);
		$jFunc = str_replace( '{URL}',        $site['url'], $jFunc);
		$jFunc = str_replace( '{memberID}',   $iUserId,     $jFunc);
		$jFunc = str_replace( '{memberPass}', $memberPass,  $jFunc);
		if (check_condition(str_replace('\$','$',$aValue['Check'])) == TRUE && strrpos($aValue['Visible'],$sVisible) !== FALSE)
		{
			$ret .= $oTemplMenu -> getMenuItem( _t($aValue['Caption']), $sMenuLink, $sPath, $aValue['Target'], $jFunc, $sIconName );
			$ret .= ('group' == $aValue['MenuType']) ? '<div id="submenu_' . $iVal . '" style="display: none;" class="member_submenu"></div>' : '';
		}
	}
	$ret .= "</div>";
	return $ret;
}


function getMenuInfo()
{
	global $logged;
	global $aMenuInfo;
	global $p_arr;
	global $aTopMenu;
	global $site;
	
	if( $logged['member'] )
	{
		$aMenuInfo['memberID']   = (int)$_COOKIE['memberID'];
		$aMenuInfo['memberNick'] = getNickName( $aMenuInfo['memberID'] );
		$aMenuInfo['memberLink'] = getProfileLink( $aMenuInfo['memberID'] );
		$aMenuInfo['visible']    = 'memb';
	}
	else
	{
		$aMenuInfo['memberID'] = 0;
		$aMenuInfo['memberNick'] = '';
		$aMenuInfo['memberLink'] = '';
		$aMenuInfo['visible']  = 'non';
	}
	
	
	$selfFile = basename( $_SERVER['PHP_SELF'] );
	
	//get viewed profile ID
	if( $p_arr and $p_arr['ID'] )
	{
		$aMenuInfo['profileID']   = (int)$p_arr['ID'];
		$aMenuInfo['profileNick'] = $p_arr['NickName'];
		$aMenuInfo['profileLink'] = getProfileLink( $aMenuInfo['profileID'] );
	}
	elseif( $selfFile == 'browseVideo.php' or $selfFile == 'browsePhoto.php' or $selfFile == 'browseMusic.php' )
	{
		$aMenuInfo['profileID']   = (int)$_GET['userID'];
		$aMenuInfo['profileNick'] = getNickName( $aMenuInfo['profileID'] );
		$aMenuInfo['profileLink'] = getProfileLink( $aMenuInfo['profileID'] );
	}
	elseif( $selfFile == 'guestbook.php' )
	{
		$aMenuInfo['profileID']   = $_REQUEST['owner'] ? (int)$_REQUEST['owner'] : $aMenuInfo['profileID'];
		$aMenuInfo['profileNick'] = getNickName( $aMenuInfo['profileID'] );
		$aMenuInfo['profileLink'] = getProfileLink( $aMenuInfo['profileID'] );
	}
	elseif( $selfFile == 'blogs.php' )
	{
		$aMenuInfo['profileID']   = $_REQUEST['ownerID'] ? (int)$_REQUEST['ownerID'] : $aMenuInfo['profileID'];
		$aMenuInfo['profileNick'] = getNickName( $aMenuInfo['profileID'] );
		$aMenuInfo['profileLink'] = getProfileLink( $aMenuInfo['profileID'] );
	}
	elseif( $selfFile == 'viewFriends.php' )
	{
		$aMenuInfo['profileID']   = (int)$_GET['iUser'];
		$aMenuInfo['profileNick'] = getNickName( $aMenuInfo['profileID'] );
		$aMenuInfo['profileLink'] = getProfileLink( $aMenuInfo['profileID'] );
	}
	elseif( $selfFile == 'photos_gallery.php' )
	{
		$aMenuInfo['profileID']   = (int)$_GET['ID'];
		$aMenuInfo['profileNick'] = getNickName( $aMenuInfo['profileID'] );
		$aMenuInfo['profileLink'] = getProfileLink( $aMenuInfo['profileID'] );
	}
	else
	{
		$aMenuInfo['profileID']   = 0;
		$aMenuInfo['profileNick'] = '';
		$aMenuInfo['profileLink'] = '';
	}
	
	// detect current menu
	$aMenuInfo['currentCustom'] = 0;
	$aMenuInfo['currentTop'] = 0;
	
	$aSiteUrl = parse_url( $site['url'] );
	$sRequestUriFile = htmlspecialchars_adv( substr( $_SERVER['REQUEST_URI'], strlen( $aSiteUrl['path'] ) ) );
	
	foreach( $aTopMenu as $iItemID => $aItem )
	{
		if( $aItem['Type'] == 'top' and $aMenuInfo['currentTop'] and $aMenuInfo['currentTop'] != $iItemID )
			break;
			
		$aItemUris = explode( '|', $aItem['Link'] );
		foreach( $aItemUris as $sItemUri )
		{
			if( $aMenuInfo['memberID'] )
			{
				$sItemUri = str_replace( "{memberID}",    $aMenuInfo['memberID'],    $sItemUri );
				$sItemUri = str_replace( "{memberNick}",  $aMenuInfo['memberNick'],  $sItemUri );
				$sItemUri = str_replace( "{memberLink}",  $aMenuInfo['memberLink'],  $sItemUri );
			}
			
			if( $aMenuInfo['profileID'] )
			{
				$sItemUri = str_replace( "{profileID}",   $aMenuInfo['profileID'],   $sItemUri );
				$sItemUri = str_replace( "{profileNick}", $aMenuInfo['profileNick'], $sItemUri );
				$sItemUri = str_replace( "{profileLink}", $aMenuInfo['profileLink'], $sItemUri );
			}
			
			//echo $sItemUri . '<br />';
			
			if( $sItemUri == $sRequestUriFile or
				( substr( $sRequestUriFile, 0, strlen( $sItemUri ) ) == $sItemUri and !(int)$aItem['Strict'] ) )
			{
				if( $aItem['Type'] == 'custom' )
				{
					$aMenuInfo['currentCustom'] = $iItemID;
					$aMenuInfo['currentTop']    = (int)$aItem['Parent'];
					break;
				}
				else //top or system
				{
					if( $aMenuInfo['currentTop'] and $aMenuInfo['currentTop'] != $iItemID )
					{
						break;
					}
					else
					{
						$aMenuInfo['currentTop'] = $iItemID;
					}
				}
			}
		}
		
		if( $aMenuInfo['currentCustom'] )
			break;
	}

	//echoDbg( $aMenuInfo );
}



function TopMenuDesign( $iDivide, $sDivider )
{
	global $aTopMenu;
	global $aMenuInfo;
	global $oTemplConfig;
	
	if( !$aMenuInfo )
		getMenuInfo();
	
	$sScriptCurrent = ( $aTopMenu[ $aMenuInfo['currentTop'] ]['Link'] == 'index.php' ) ? 0 : $aMenuInfo['currentTop'];
	
	$oTemplMenu = new BxTemplMenu( $oTemplConfig );
	$ret = '
		<script type="text/javascript">
			currentTopItem = ' . $sScriptCurrent . ';
		</script>
	';
	
	$iCount = 0;
	
	foreach( $aTopMenu as $iItemID => $aItem )
	{
		if( $aItem['Type'] != 'top' )
			continue;
		
		if( strpos( $aItem['Visible'], $aMenuInfo['visible'] ) === false )
			continue;
		
		if( strlen( $aItem['Check'] ) )
		{
			$sCheck = $aItem['Check'];
			$sCheck = str_replace( '\$', '$', $sCheck );
			
			$func = create_function('', $sCheck );
			if( !$func() )
				continue;
		}
		
		//generate
		list( $aItem['Link'] ) = explode( '|', $aItem['Link'] );
		
		$aItem['Link'] = str_replace( "{memberID}",    $aMenuInfo['memberID'],    $aItem['Link'] );
		$aItem['Link'] = str_replace( "{memberNick}",  $aMenuInfo['memberNick'],  $aItem['Link'] );
		
		$aItem['Link'] = str_replace( "{profileID}",   $aMenuInfo['profileID'],   $aItem['Link'] );
		$aItem['Link'] = str_replace( "{profileNick}", $aMenuInfo['profileNick'], $aItem['Link'] );
		
		$aItem['Onclick'] = str_replace( "{memberID}",    $aMenuInfo['memberID'],    $aItem['Onclick'] );
		$aItem['Onclick'] = str_replace( "{memberNick}",  $aMenuInfo['memberNick'],  $aItem['Onclick'] );
		$aItem['Onclick'] = str_replace( "{memberPass}",  getPassword( $aMenuInfo['memberID'] ),  $aItem['Onclick'] );
		
		$aItem['Onclick'] = str_replace( "{profileID}",   $aMenuInfo['profileID'],   $aItem['Onclick'] );
		$aItem['Onclick'] = str_replace( "{profileNick}", $aMenuInfo['profileNick'], $aItem['Onclick'] );
		
		$ret .= $oTemplMenu -> getTopMenuItem( _t( $aItem['Caption'] ), $aItem['Link'], $aItem['Target'], $aItem['Onclick'], ( $iItemID == $aMenuInfo['currentTop'] ), $iItemID );
		
		if( $iDivide > 0 and ( ++$iCount % $iDivide ) == 0 )
		{
			$ret .= $sDivider;
			$iCount = 0;
		}
	}
	
	return $ret;
}

function CustomMenuDesign( $parent = 0 )
{
	global $aTopMenu;
	global $aMenuInfo;
	global $oTemplConfig;
	
	if( !$aMenuInfo )
		getMenuInfo();
	
	if( !$parent )
		$parent = $aMenuInfo['currentTop'];
	
	$oTemplMenu = new BxTemplMenu( $oTemplConfig );
	$ret = '';
	
	foreach( $aTopMenu as $iItemID => $aItem )
	{
		if( $aItem['Type'] != 'custom' )
			continue;
		
		if( $aItem['Parent'] != $parent )
			continue;
		
		if( strpos( $aItem['Visible'], $aMenuInfo['visible'] ) === false )
			continue;
		
		if( strlen( $aItem['Check'] ) )
		{
			$sCheck = $aItem['Check'];
			$sCheck = str_replace( '\$', '$', $sCheck );
			
			$func = create_function('', $sCheck );
			
			if( !$func() )
				continue;
		}
		
		//generate
		list( $aItem['Link'] ) = explode( '|', $aItem['Link'] );
		
		$aItem['Link'] = str_replace( "{memberID}",    $aMenuInfo['memberID'],    $aItem['Link'] );
		$aItem['Link'] = str_replace( "{memberNick}",  $aMenuInfo['memberNick'],  $aItem['Link'] );
		$aItem['Link'] = str_replace( "{memberLink}",  $aMenuInfo['memberLink'],  $aItem['Link'] );
		
		$aItem['Link'] = str_replace( "{profileID}",   $aMenuInfo['profileID'],   $aItem['Link'] );
		$aItem['Link'] = str_replace( "{profileNick}", $aMenuInfo['profileNick'], $aItem['Link'] );
		$aItem['Link'] = str_replace( "{profileLink}", $aMenuInfo['profileLink'], $aItem['Link'] );
		
		$aItem['Onclick'] = str_replace( "{memberID}",    $aMenuInfo['memberID'],    $aItem['Onclick'] );
		$aItem['Onclick'] = str_replace( "{memberNick}",  $aMenuInfo['memberNick'],  $aItem['Onclick'] );
		$aItem['Onclick'] = str_replace( "{memberPass}",  getPassword( $aMenuInfo['memberID'] ),  $aItem['Onclick'] );
		
		$aItem['Onclick'] = str_replace( "{profileID}",   $aMenuInfo['profileID'],   $aItem['Onclick'] );
		$aItem['Onclick'] = str_replace( "{profileNick}", $aMenuInfo['profileNick'], $aItem['Onclick'] );
		
		$ret .= $oTemplMenu -> getCustomMenuItem( _t( $aItem['Caption'] ), $aItem['Link'], $aItem['Target'], $aItem['Onclick'], ( $iItemID == $aMenuInfo['currentCustom'] ) );
	}
	
	return $ret;
}

function getAllMenus()
{
	global $aTopMenu;
	global $aMenuInfo;
	global $oTemplConfig;
	global $site;
	
	if( !$aMenuInfo )
		getMenuInfo();
	
	$aSiteUrl = parse_url( $site['url'] );
	$sSelfFile = htmlspecialchars_adv( substr( $_SERVER['PHP_SELF'], strlen( $aSiteUrl['path'] ) ) );
	
	$oTemplMenu = new BxTemplMenu( $oTemplConfig );
	$ret = '';
	
	$aTTopMenu = $aTopMenu;
	
	foreach( $aTTopMenu as $iTItemID => $aTItem )
	{
		if( $aTItem['Type'] != 'top' && $aTItem['Type'] !='system')
			continue;
		
		if( strpos( $aTItem['Visible'], $aMenuInfo['visible'] ) === false )
			continue;
		
		if( strlen( $aTItem['Check'] ) )
		{
			$sCheck = $aTItem['Check'];
			$sCheck = str_replace( '\$', '$', $sCheck );
			
			$func = create_function('', $sCheck );
			if( !$func() )
				continue;
		}
		
		if( $aMenuInfo['currentTop'] == $iTItemID && $sSelfFile != 'index.php' )
			$display = 'block';
		else
			$display = 'none';
		
		$sCaption = _t( $aTItem['Caption'] );
		
		$sCaption = str_replace( "{memberNick}",  $aMenuInfo['memberNick'],  $sCaption );
		$sCaption = str_replace( "{profileNick}", $aMenuInfo['profileNick'], $sCaption );
		
		
		//generate
		$ret .= '
			<div class="hiddenMenu" style="display:' . $display . ';" id="hiddenMenu_' . $iTItemID . '"
			  onmouseover="holdHiddenMenu = ' . $iTItemID . ';"
			  onmouseout="holdHiddenMenu = currentTopItem; hideHiddenMenu( ' . $iTItemID . ' )">
				<div class="hiddenMenuBgCont">
					<div class="hiddenMenuCont">
						<div class="topPageHeader">' . $sCaption . '</div>' .
						$oTemplMenu -> getCustomMenu( $iTItemID ) . '
					</div>
				</div>
				<div class="clear_both"></div>
			</div>';
	}
	
	
	return $ret;
}


//-----Replacement {ID} on current id in menu-link (array of menu items, users id)
function add_id ( $sMenuLink = "", $iId = 0 )
{
	if ( strpos($sMenuLink, "{ID}") !== FALSE && strpos($sMenuLink, "=") !== FALSE)
	{
		$sMenuLink = str_replace("{ID}", $iId, $sMenuLink);
	}
	return $sMenuLink;
}



//-----Check condition of menu item (function from `Check` field of `MemberMenu`)
function check_condition ( $sCon = "" )
{
	if ( strlen($sCon) > 0 )
	{
		$func = create_function('', $sCon);
		return $func();
	}
	else
	{
		return TRUE;
	}
}


function compileMenus()
{
	global $dir;
	
	$fMenu = @fopen($dir['inc']. "/menu_content.inc.php", "w");
	if( !$fMenu )
		return false;
	
	//write member menu
	fwrite( $fMenu, "\$aMenu = array(\n" );
	$aFields = array('Name','Caption','Link','MenuOrder','MenuType','MenuGroup','Visible','Target','Onclick','Check');
		
	$sQuery = "
		SELECT
			`ID`,
			`" . implode('`,
			`', $aFields ) . "`
		FROM `MemberMenu`
		ORDER BY `MenuOrder`
		";
	
	$rMenu = db_res( $sQuery );
	while( $aMenuItem = mysql_fetch_assoc( $rMenu ) )
	{
		fwrite( $fMenu, "\t" . str_pad( $aMenuItem['ID'], 2 ) . " => array(\n" );
		foreach( $aFields as $sKey => $sField )
		{
			$sCont = $aMenuItem[$sField];
			
			if( $sField == 'Link' )
				$sCont = htmlspecialchars_adv( $sCont );
			
			$sCont = str_replace( '\\', '\\\\', $sCont );
			$sCont = str_replace( '"', '\\"', $sCont );
			$sCont = str_replace( '$', '\\$', $sCont );
			
			fwrite( $fMenu, "\t\t" . str_pad( "'$sField'", 11 ) . " => \"$sCont\"" );
			
			if( $sKey < ( count( $aFields ) - 1 ) )
				fwrite( $fMenu, "," );
			
			fwrite( $fMenu, "\n" );
		}
		fwrite( $fMenu, "\t),\n" );
	}
	fwrite( $fMenu, ");\n\n" );
	
	fwriteCompileTopMenu($fMenu);
	
	fwrite( $fMenu, "return true;\n" );
	fclose( $fMenu );
}

function fwriteCompileTopMenu($fMenu)
{
	fwrite( $fMenu, "\$aTopMenu = array(\n" );
	$aFields = array('Type','Caption','Link','Visible','Target','Onclick','Check','Strict','Parent');
		
	$sQuery = "
		SELECT
			`ID`,
			`" . implode('`,
			`', $aFields ) . "`
		FROM `TopMenu`
		WHERE `Active` = 1 AND ( `Type` = 'system' OR `Type` = 'top' )
		ORDER BY `Type`,`Order`
		";
	
	$rMenu = db_res( $sQuery );
	while( $aMenuItem = mysql_fetch_assoc( $rMenu ) )
	{
		fwrite( $fMenu, "\t" . str_pad( $aMenuItem['ID'], 2 ) . " => array(\n" );
		foreach( $aFields as $sKey => $sField )
		{
			$sCont = $aMenuItem[$sField];
			
			if( $sField == 'Link' )
				$sCont = htmlspecialchars_adv( $sCont );
			
			$sCont = str_replace( '\\', '\\\\', $sCont );
			$sCont = str_replace( '"', '\\"', $sCont );
			$sCont = str_replace( '$', '\\$', $sCont );
			
			fwrite( $fMenu, "\t\t" . str_pad( "'$sField'", 11 ) . " => \"$sCont\"" );
			
			if( $sKey < ( count( $aFields ) - 1 ) )
				fwrite( $fMenu, "," );
			
			fwrite( $fMenu, "\n" );
		}
		fwrite( $fMenu, "\t),\n" );
		
		
		// write it's children
		$sQuery = "
			SELECT
				`ID`,
				`" . implode('`,
				`', $aFields ) . "`
			FROM `TopMenu`
			WHERE `Active` = 1 AND `Type` = 'custom' AND `Parent` = {$aMenuItem['ID']}
			ORDER BY `Order`
			";
		
		$rCMenu = db_res( $sQuery );
		while( $aMenuItem = mysql_fetch_assoc( $rCMenu ) )
		{
			fwrite( $fMenu, "\t" . str_pad( $aMenuItem['ID'], 2 ) . " => array(\n" );
			foreach( $aFields as $sKey => $sField )
			{
				$sCont = $aMenuItem[$sField];
				
				if( $sField == 'Link' )
					$sCont = htmlspecialchars_adv( $sCont );
				
				$sCont = str_replace( '\\', '\\\\', $sCont );
				$sCont = str_replace( '"', '\\"', $sCont );
				$sCont = str_replace( '$', '\\$', $sCont );
				
				fwrite( $fMenu, "\t\t" . str_pad( "'$sField'", 11 ) . " => \"$sCont\"" );
				
				if( $sKey < ( count( $aFields ) - 1 ) )
					fwrite( $fMenu, "," );
				
				fwrite( $fMenu, "\n" );
			}
			fwrite( $fMenu, "\t),\n" );
		}
		
		
	}
	fwrite( $fMenu, ");\n\n" );
}


function getTopPageHead()
{
	global $aMenuInfo;
	global $aTopMenu;
	
	if( !$aMenuInfo )
		getMenuInfo();
	
	if( $aTopMenu[ $aMenuInfo['currentTop'] ]['Caption'] == '{profileNick}' )
		return $aMenuInfo['profileNick'];
	elseif( $aTopMenu[ $aMenuInfo['currentTop'] ]['Caption'] == '{memberNick}' )
		return $aMenuInfo['memberNick'];
	else
		return _t( $aTopMenu[ $aMenuInfo['currentTop'] ]['Caption'] );
}
	

?>
