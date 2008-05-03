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

$_page['name_index'] = 44;

$logged['member'] = member_auth(0);

$sourceID = (int)$_COOKIE['memberID'];
$targetID = (int)$_GET['ID'];
$action = $_GET['action'];

$_ni = $_page['name_index'];

	switch ($action)
	{
		case 'block':
			$_page['header'] = _t( "_Block list" );
			$_page_cont[$_ni]['page_main_code'] = DesignBoxContent( _t('_Block list'), PageListBlock($sourceID, $targetID), $oTemplConfig -> PageListPop_db_num );
		break;
		case 'hot':
			$_page['header'] = _t('_Hot list');
			$_page_cont[$_ni]['page_main_code'] = DesignBoxContent( _t('_Hot list'),  PageListHot($sourceID, $targetID), $oTemplConfig -> PageListPop_db_num );
		break;
		case 'friend':
			$_page['header'] = _t('_Friend list');
			$_page_cont[$_ni]['page_main_code'] = DesignBoxContent(_t('_Friend list'), PageListFriend($sourceID, $targetID), $oTemplConfig -> PageListPop_db_num );
		break;
		case 'spam':
			$_page['header'] = _t('_Spam report');
			$_page_cont[$_ni]['page_main_code'] = DesignBoxContent( _t('_Spam report'), PageListSpam($sourceID, $targetID), $oTemplConfig -> PageListPop_db_num);
		break;
	}

PageCode();

function PageListBlock( $sourceID, $targetID )
{
	$ret = '';
	$query = "REPLACE INTO `BlockList` SET `ID` = '$sourceID', `Profile` = '$targetID';";
	if( db_res($query, 0) )
	{
		$ret = _t_action('_User was added to block list');
	}
	else
	{
		$ret = _t_err('_Failed to apply changes');
	}

	return $ret;
}

function PageListHot($sourceID, $targetID)
{
	$ret = '';

	$query = "REPLACE INTO `HotList` SET `ID` = '$sourceID', `Profile` = '$targetID';";
	if( db_res($query, 0) )
	{
		$ret = _t_action('_User was added to hot list');
	}
	else
	{
		$ret = _t_err('_Failed to apply changes');
	}

	return $ret;
}

function PageListFriend($sourceID, $targetID)
{
	$ret = '';
	$query = "SELECT * FROM `FriendList` WHERE (`ID` = '$sourceID' and `Profile` = '$targetID') or ( `ID` = '$targetID' and `Profile` = '$sourceID')";
	$temp = db_assoc_arr($query);

	if( $sourceID == $temp['ID'] || $temp['Check'] == 1 )
	{
		$ret = _t_action('_already_in_friend_list');
	}
	elseif( $targetID == $temp['ID'] && 0 == $temp['Check'] )
	{
		$query = "UPDATE `FriendList` SET `Check` = '1' WHERE `ID` = '$targetID' AND `Profile` = '$sourceID';";
		if( db_res($query) )
		{
			$ret = _t_action('_User was added to friend list');
		}
		else
		{
			$ret = _t_err('_Failed to apply changes');
		}
	}
	else
	{
		$query = "INSERT INTO `FriendList` SET `ID` = '$sourceID', `Profile` = '$targetID', `Check` = '0';";
		if( db_res( $query ) )
		{
			$ret = _t_action('_User was invited to friend list');
		}
		else
		{
			$ret = _t_err('_Failed to apply changes');
		}

	}


	return $ret;
}

function PageListSpam($sourceID, $targetID)
{
	global $site;
	
	$reporterID = $sourceID;
	$spamerID = $targetID;

    $aReporter = getProfileInfo( $reporterID );// db_arr("SELECT `NickName` FROM `Profiles` WHERE `ID` = '$reporterID';", 0);
    $aSpamer   = getProfileInfo( $spamerID );//db_arr("SELECT `NickName` FROM `Profiles` WHERE `ID` = '$spamerID';", 0);

    $message = getParam( "t_SpamReport" );
	$subject = getParam('t_SpamReport_subject');


    $aPlus = array();
    $aPlus['reporterID'] = $reporterID;
    $aPlus['reporterNick'] = $aReporter['NickName'];

    $aPlus['spamerID'] = $spamerID;
    $aPlus['spamerNick'] = $aSpamer['NickName'];


	$mail_result = sendMail( $site['email'], $subject, $message, '', $aPlus );

	if ( $mail_result )
	    $ret = _t_action('_Report about spam was sent');
	else
	    $ret = _t_err('_Report about spam failed to sent');

	return $ret;
}

?>