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
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'params.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'tags.inc.php' );


$aUser = array(); //global cache array


function ShowZodiacSign( $date )
{
	global $site;

	if ( $date == "0000-00-00" )
		return "";

	if ( strlen($date) )
	{
		$m = substr( $date, -5, 2 );
		$d = substr( $date, -2, 2 );

		switch ( $m )
		{
			case '01': if ( $d <= 20 ) $sign = "capricorn"; else $sign = "aquarius";
			break;
			case '02': if ( $d <= 20 ) $sign = "aquarius"; else $sign = "pisces";
			break;
			case '03': if ( $d <= 20 ) $sign = "pisces"; else $sign = "aries";
			break;
			case '04': if ( $d <= 20 ) $sign = "aries"; else $sign = "taurus";
			break;
			case '05': if ( $d <= 20 ) $sign = "taurus"; else $sign = "gemini";
			break;
			case '06': if ( $d <= 21 ) $sign = "gemini"; else $sign = "cancer";
			break;
			case '07': if ( $d <= 22 ) $sign = "cancer"; else $sign = "leo";
			break;
			case '08': if ( $d <= 23 ) $sign = "leo"; else $sign = "virgo";
			break;
			case '09': if ( $d <= 23 ) $sign = "virgo"; else $sign = "libra";
			break;
			case '10': if ( $d <= 23 ) $sign = "libra"; else $sign = "scorpio";
			break;
			case '11': if ( $d <= 22 ) $sign = "scorpio"; else $sign = "sagittarius";
			break;
			case '12': if ( $d <= 21 ) $sign = "sagittarius"; else $sign = "capricorn";
	    }


		return '<img src="' . $site['zodiac'] . 'zodiac_' . $sign . '.gif" alt="' . $sign . '" title="' . $sign . '" />';

	}
	else
	{
		return "";
	}
}

function ShowFriendList( $id, $aMutualFriends = 0 )
{
	global $site;
	global $max_thumb_width;
	global $max_thumb_height;

	if ($aMutualFriends == 0)
		$aMutualFriends = array();
	
	$id = (int)$id;
	$friend_list_query = "SELECT `Profiles`.* FROM `FriendList`
								 LEFT JOIN `Profiles` ON (`Profiles`.`ID` = `FriendList`.`Profile` AND `FriendList`.`ID` = '$id' OR `Profiles`.`ID` = `FriendList`.`ID` AND `FriendList`.`Profile` = '$id')
								 WHERE (`FriendList`.`Profile` = '$id' OR `FriendList`.`ID` = '$id') AND `FriendList`.`Check` = '1' ORDER BY `Profiles`.`Picture` DESC LIMIT 12";

	$friend_list_res = db_res("$friend_list_query");
	
	$iCounter = 0;
	$ret = '';
	
	while ( $friend_list_arr = mysql_fetch_assoc( $friend_list_res ) )
	{

       if (!array_key_exists($friend_list_arr['ID'], $aMutualFriends)){
		
		$iCounter ++;
		$sKey = '1';
		if( $iCounter == 3 )
			$sKey = '2';
		
		$ret .= '<div class="friends_thumb_'.$sKey.'">' . get_member_thumbnail($friend_list_arr['ID'], 'none') . '<div class="browse_nick"><a href="' . getProfileLink($friend_list_arr['ID']) . '">' . $friend_list_arr['NickName'] . '</a></div><div class="clear_both"></div></div>';
		
		if( $iCounter == 3)
			$iCounter = 0;
	}


	}

	return $ret;
}

function sendKissPopUp( $iRecipientID, $icon = true, $sText = '' )
{
	global $oTemplConfig;
	global $site;


	if(  $icon )
	{
		$ret = "<a href=\"javascript:void(0);\" onclick=\"javascript: window.open( 'greet.php?sendto=$iRecipientID', '', 'width={$oTemplConfig -> popUpWindowWidth},height={$oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no' );\"><img src=\"" . getTemplateIcon('kiss.gif') . "\" alt=\"" . _t( "_Send Kiss" ) . "\" title=\"" . _t( "_Send Kiss" ) . "\" /></a>";
	}
	else
	{
		$ret = "<a href=\"javascript:void(0);\" onclick=\"javascript: window.open( 'greet.php?sendto=$iRecipientID', '', 'width={$oTemplConfig -> popUpWindowWidth},height={$oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no' );\">" . _t( "_Send Kiss" ) . "</a>";
	}

	return $ret;
}

function cart_pop( $text, $action, $ID = 0, $javascript = "" )
{
	global $oTemplConfig;

	if ( $action == "add" )
	{
		if ( !$ID )
			return "";
		return "<a href=\"javascript:void(0);\" onClick=\"javascript: window.open( 'cart_pop.php?action=add&amp;ID=$ID', 'cart_$ID', 'width={$oTemplConfig -> popUpWindowWidth},height={$oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no' );\" $javascript>$text</a>";
	}
	elseif ( $action == "empty" )
		return "<a $class_part href=\"javascript:void(0);\" onClick=\"javascript: window.open( 'cart_pop.php?action=empty', 'cart', 'width={$oTemplConfig -> popUpWindowWidth},height={$oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no' );\" $javascript>$text</a>";
}

function sound_pop( $text, $member, $ID )
{
    if ( !$ID )    return;
    return "<a href=\"javascript:void(0);\" onClick=\"javascript:window.open( 'sound_pop.php?ID=$ID', '', 'width=280,height=200,menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no' );\">$text</a>";
}

function video_pop( $text, $member, $ID )
{
    if ( !$ID )
    	return;
    return "<a href=\"javascript:void(0);\" onClick=\"javascript:window.open( 'video_pop.php?ID=$ID', '', 'width=280,height=350,menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no' );\">$text</a>";
}

function getMediaPop( $text, $aMedia )
{
	global $oTemplConfig;

	$ret = '';

	$ret .= '<a href="javascript:void(0);" onClick="javascript:window.open( \'video_pop.php?mediaID=' . $aMedia['med_id'] . '\', \'\', \'width=' . $oTemplConfig -> popUpWindowWidth . ',height=' . $oTemplConfig -> popUpWindowHeight . ',menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no\' );">' . $text . '</a>';


	return $ret;
}

// get credits amount by option index
function getCreditsAmountByIndex( $credIndex )
{
	$credIndex = (int)$credIndex;
	$minCredits = (float)getParam( 'min_credit_a' );
	$maxCredits = (float)getParam( 'max_credit_a' );
	$creditStep = (float)getParam( 'credit_step' );
	$res = $minCredits + $credIndex * $creditStep;
	if ( $res > $maxCredits )
		$res = $maxCredits;
	return sprintf( "%.2f", $res );
}

// get credits price by option index
function getCreditsPriceByIndex( $credIndex )
{
	$credIndex = (int)$credIndex;
	$minCredits = (float)getParam( 'min_credit_a' );
	$maxCredits = (float)getParam( 'max_credit_a' );
	$creditStep = (float)getParam( 'credit_step' );
	$credit2money = (float)getParam( 'credit2money' ); // how many credits for one money unit
	$creditDiscountStep = (float)getParam( 'credit_discount_step' );
	$creditsAmount = $minCredits + $credIndex * $creditStep;
	if ( $creditsAmount > $maxCredits )
	{
		$creditsAmount = $maxCredits;
		$credIndex = (int)(($maxCredits - $minCredits) / $creditStep);
	}
	$res = $creditsAmount / $credit2money - $credIndex * $creditDiscountStep;
	return sprintf( "%.2f", $res );
}

// Check if this contact was bought
function contact_allowed( $memberID, $profileID )
{
	$memberID = (int)$memberID;
	$profileID = (int)$profileID;

	$exist_arr = db_arr( "SELECT COUNT(*) AS `count` FROM `BoughtContacts`
							WHERE `IDBuyer` = $memberID AND `IDContact` = $profileID
								OR `IDBuyer` = $profileID AND `IDContact` = $memberID" );
	if ( $exist_arr['count'] )
		return true;
	else
		return false;
}

// Buy contact in shopping cart
function buyContact( $memberID, $profileID, $transactionID = '' )
{
	$memberID = (int)$memberID;
	$profileID = (int)$profileID;
	$transactionID = (int)$transactionID;

	$exist_arr = db_arr( "SELECT * FROM `BoughtContacts` WHERE `IDBuyer` = $memberID AND `IDContact` = $profileID" );
	if ( $exist_arr )
	{
		db_res( "UPDATE `BoughtContacts` SET `HideFromBuyer` = 0 WHERE `IDBuyer` = $memberID AND `IDContact` = $profileID" );
		return false;
	}

	if ( $transactionID )
		$result = db_res( "INSERT INTO `BoughtContacts` SET `IDBuyer` = $memberID, `IDContact` = $profileID, `TransactionID` = $transactionID " );
	else
		$result = db_res( "INSERT INTO `BoughtContacts` SET `IDBuyer` = $memberID, `IDContact` = $profileID " );

	return $result;
}
/**
 * Function in depends on the profile type return age
 * or ages of profile.
 *
 * @param array $aProfile
 *
 * return array
 */
function getProfileAgeFromArray( $aProfile )
{
	//print_r( $aProfile );
	$ret = array();

	$ret[] = age( $aProfile['DateOfBirth']);

	return $ret;
}


function age( $birth_date )
{
	if ( $birth_date == "0000-00-00" )
		return _t("_uknown");

	$bd = explode( "-", $birth_date );
	$age = date("Y") - $bd[0] - 1;

	$arr[1] = "m";
	$arr[2] = "d";

	for ( $i = 1; $arr[$i]; $i++ )
	{
		$n = date( $arr[$i] );
		if ( $n < $bd[$i] )
			break;
		if ( $n > $bd[$i] )
		{
			++$age;
			break;
		}
	}

	return $age;
}


function conf_nick($nick, $ID = 0 )
{
	global $exist_arr;
	global $dir;

	if (file_exists($dir['root'].$nick))
	{
		return FALSE;
	}
		
	if ( $ID )
		$exist_arr = db_arr( "SELECT ID, NickName FROM `Profiles` WHERE NickName = '$nick' AND ID <> $ID" );
	else
		$exist_arr = db_arr( "SELECT `NickName` FROM `Profiles` WHERE NickName = '$nick'" );

	if ( $exist_arr )
		return false;
	
	return true;
}

function conf_email ($Email, $ID = 0)
{
	global $exist_arr;

	if ( $ID )
		$exist_arr = db_arr( "SELECT ID, NickName FROM `Profiles` WHERE UPPER(Email) = UPPER('$Email') AND ID <> $ID" );
	else
		$exist_arr = db_arr( "SELECT ID, NickName FROM `Profiles` WHERE UPPER(Email) = UPPER('$Email')" );

	if ( $exist_arr ) {
		return 0;
	}
	return 1;
}

function conf_email_queue ($Email)
{
	global $exist_arr;

    $exist_arr = db_arr( "SELECT ID FROM `NotifyQueue` WHERE UPPER(Email) = UPPER('$Email')" );
    if ( $exist_arr ) {
        return 0;
    }
    return 1;
}

function upload_photo($pic_index)
{
	global $pics;
	global $site;
	global $p_arr;
	global $gl_pic;
	global $dir;
	global $pictures_text;
	global $COMPOSITE;
	global $ADMIN;

	$autoApproval_ifPhoto = isAutoApproval('photo');
	$up_name = "file_$pic_index";
	$pic_addon_field = "Pic_{$pic_index}_addon";
	$temp_filename = tempnam(rtrim($dir['tmp'], '/'), $p_arr['ID']);
	unlink($temp_filename);
	$pic_name = $pics[$pic_index]['name'];
	$pic_tmp = $_FILES[$up_name]['tmp_name'];
	$ext = strrchr($_FILES[$up_name]['name'], '.');

	if ( $pics[$pic_index]['exist'] )
		unlink( "$pic_name.jpg" );


        $scan = getimagesize($_FILES[$up_name]['tmp_name']);
        if ( 1 != $scan[2] && 2 != $scan[2] && 3 != $scan[2] && 6 != $scan[2] )
            return 0;


	if ( move_uploaded_file( $pic_tmp, "{$temp_filename}{$ext}" ) )
	{
		if ( imageResize( "{$temp_filename}{$ext}", "{$temp_filename}.jpg", $gl_pic['pic']['width'], $gl_pic['pic']['height'], true ) )
		{
			echo _t("_FAILED_TO_UPLOAD_PIC", $_FILES[$up_name]['name'], "undef");
			return 0;
		}
		if ( strtolower($ext) != '.jpg' )
			unlink( "{$temp_filename}{$ext}" );

		if ( getParam( 'enable_watermark' ) == 'on' )
		{
			$transparent1 = getParam( 'transparent1' );
			$water_mark = $dir['profileImage'] . getParam( 'Water_Mark' );
			if (  strlen(getParam( 'Water_Mark' )) && file_exists($water_mark) )
			{
				applyWatermark( "{$temp_filename}.jpg", "{$temp_filename}.jpg", $water_mark, $transparent1 );
			}
		}

		srand(time());
		$p_arr[$pic_addon_field] = rand(10000, 99999);
		$pics[$pic_index]['name'] = "{$dir['profileImage']}{$p_arr['ID']}_{$pic_index}_". $p_arr[$pic_addon_field];
		$pic_name = $pics[$pic_index]['name'];
		if ( !rename( "{$temp_filename}.jpg", "$pic_name.jpg" ) )
		{
			echo _t("_FAILED_TO_UPLOAD_PIC", $_FILES[$up_name]['name'], "fail to rename");
			return 0;
		}
		db_res( "UPDATE `Profiles` SET $pic_addon_field = '". $p_arr[$pic_addon_field] ."' WHERE ID = {$p_arr['ID']}" );

		chmod( "$pic_name.jpg", 0644 );
		$pictures_text = _t_action('_Successfully uploaded');
        $pics[$pic_index]['exist'] = true;

		if ( $p_arr['Status'] == 'Active' && !$autoApproval_ifPhoto && !$ADMIN )
		{
			$update_res = db_res( "UPDATE `Profiles` SET `Status` = 'Approval' WHERE `ID` = {$p_arr['ID']}" );

			$p_arr['Status'] = 'Approval';
			modules_block($p_arr['ID']);
		}
		createUserDataFile( $p_arr['ID'] );
		return 1;
	}
	else
	{
		echo _t_err("_FAILED_TO_UPLOAD_PIC", $_FILES[$up_name]['name'], "undef");
	}

	return 0;
}

/**
 * Print code for membership status
 * $memberID - member ID
 * $offer_upgrade - will this code be printed at [c]ontrol [p]anel
 * $credits - will print credits status if $credits == 1
 */
function GetMembershipStatus($memberID, $offer_upgrade = true, $credits = 0 )
{
	global $oTemplConfig;

	$ret = "";

	$membership_info = getMemberMembershipInfo($memberID);

	$viewMembershipActions = "<br />(<a onclick=\"javascript:window.open('explanation.php?explain=membership&amp;type=".$membership_info['ID']."', '', 'width=500, height=400, menubar=no, status=no, resizable=no, scrollbars=yes, toolbar=no, location=no');\" href=\"javascript:void(0);\">"._t("_VIEW_MEMBERSHIP_ACTIONS")."</a>)<br />";

	// Show colored membership name
	if ( $membership_info['ID'] == MEMBERSHIP_ID_STANDARD )
	{
		$ret .= _t( "_MEMBERSHIP_STANDARD" ). $viewMembershipActions;
		if ( $offer_upgrade )
			$ret .= " ". _t( "_MEMBERSHIP_UPGRADE_FROM_STANDARD" );
	}
	else
	{
		$ret .= "<font color=\"red\">{$membership_info['Name']}</font>$viewMembershipActions";

		$days_left = (int)( ($membership_info['DateExpires'] - time()) / (24 * 3600) );

		if(!is_null($membership_info['DateExpires']))
		{
			if ( $days_left > 0 )
			{
				$ret .= _t( "_MEMBERSHIP_EXPIRES_IN_DAYS", $days_left );
			}
			else
			{
				$ret .= _t( "_MEMBERSHIP_EXPIRES_TODAY", date( "H:i", $membership_info['DateExpires'] ), date( "H:i" ) );
			}
		}
		else
		{
			$ret.= _t("_MEMBERSHIP_EXPIRES_NEVER");
		}

		if ( $offer_upgrade && !is_null($membership_info['DateExpires']))
			$ret .= " - <a href=\"membership.php\">". _t( "_MEMBERSHIP_BUY_MORE_DAYS" ) ."</a>";
	}

	if ( $credits )
	{
		$gold_arr_cred_amount = getProfileCredits( $memberID );
		$ret .= "<br /><u>" . _t( "_MEMBERSHIP_CREDITS" ) . "</u> (<a href=\"javascript: void(0);\" onClick=\"javascript: window.open('explanation.php?explain=credits', '', 'width={$oTemplConfig -> popUpWindowWidth},height={$oTemplConfig -> popUpWindowHeight},menubar=no,status=no,resizable=no,scrollbars=yes,toolbar=no, location=no' );\">"._t("_Explanation")."</a>)<br />";
		if ( $gold_arr_cred_amount )
			$ret .= _t( "_MEMBERSHIP_CREDITS_YES", $gold_arr_cred_amount )."<br />";
		else
			$ret .= _t( "_MEMBERSHIP_CREDITS_NO")."<br />";
		if ( $offer_upgrade )
			$ret .= " - <a href=\"membership.php\">". _t( "_MEMBERSHIP_CREDITS_BUY_MORE" ) ."</a>";
	}

	return $ret;
}

/**
 * return number of credits for profile
 */
function getProfileCredits( $ID )
{
	$ID = (int)$ID;
	$arr = db_arr( "SELECT `Amount` FROM `MemCredits` WHERE `ID` = $ID LIMIT 1" );
	return sprintf( "%.2f", (float)$arr[0] );
}

/**
 * increase number of credits for profile
 */
function incProfileCredits( $ID, $inc )
{
	$ID = (int)$ID;
	$inc = (float)$inc;
	$arr = db_arr( "SELECT COUNT(`ID`) FROM `MemCredits` WHERE `ID` = $ID" );
	if ( $arr[0] )
	{
		return db_res( "UPDATE `MemCredits` SET `Amount` = `Amount` + $inc WHERE `ID` = $ID" );
	}
	else
	{
		return db_res( "INSERT INTO `MemCredits` SET `Amount` = $inc, `ID` = $ID" );
	}
}

/**
 * decrease number of credits for profile
 */
function decProfileCredits( $ID, $dec )
{
	$ID = (int)$ID;
	$dec = (float)$dec;
	return db_res( "UPDATE `MemCredits` SET `Amount` = `Amount` - $dec WHERE `ID` = $ID" );
}

/**
 * Display profile details: thumbnail, nickname, sex, age, location.
 * @param $profile		Profile data as returned by mysql_fetch_array.
 */
function display_profile_details( $profile )
{
	global $dir; // Image folder
	global $site; // Image URL
	global $prof;

	// Get profile thumbnail name.
	$t_name = $profile['ID'].'_0_'.$profile['Pic_0_addon'].'.jpg';
	// Get profile thumbnail path.
	$t_path = $dir['profileImage'] . $t_name;
	// Get profile thumbnail URL.
	$t_url = $site['profileImage'].$t_name;
	// Get anonymous thumbnail url.
	$a_url = 'male' == $profile['Sex'] ? 'man.jpg' : 'woman.jpg';
	$a_url = $site['profileImage'].$a_url;
	// Select correct url.
	if ( file_exists( $t_path ) )
	{
		$url = $t_url;
	}
	else
	{
		$url = $a_url;
	}

	// Get profile details.
	$nickname = $profile['NickName'];

    $age_sex = _t("_y/o", $profile['Age'])."&nbsp;"._t("_".$profile['Sex']);

	$location = $profile['City'].', '._t( '__'.$prof['countries'][$profile['Country']] );

	// Display profile details.
	ob_start();
?>
	<table class="profile_details" cellspacing="0" cellpadding="2" border="0">
		<tr>
			<td align="center" class="profile_thumb">
				<img src="<?= $url ?>" />
			</td>
		</tr>
		<tr>
			<td class="profile_nickname" align="left">-&nbsp;<?= $nickname ?></td>
		</tr>
		<tr>
			<td class="profile_age_sex" align="left" style="width: 100px">-&nbsp;<?= $age_sex ?></td>
		</tr>
		<tr>
			<td class="profile_location" align="left">-&nbsp;<?= $location ?></td>
		</tr>
	</table>
<?
	$content = ob_get_contents();
	ob_end_clean();
	echo $content;
}


function ProfileDetails( $senderID )
{
	global $site;
	global $prof;
	global $dir;

	//$prof_query = "SELECT `NickName`, `Headline`, `DateOfBirth`, `Sex`, `Country`, `Pic_0_addon`, `Status`  FROM `Profiles` WHERE `ID` = '$senderID'";
	//$prof_arr = db_arr($prof_query);
	
	$prof_arr = getProfileInfo( $senderID );

	$country = _t( '__'.$prof['countries'][$prof_arr['Country']] );
	$prof_age = age($prof_arr['DateOfBirth']);
	$prof_sex = _t( "_" . $prof_arr['Sex']);

	if ( $prof_arr['Status'] == 'Active' )
	{
		$nick_link = '<a href="' . getProfileLink($senderID) . '" target="_blank">' . $prof_arr['NickName'] . '</a>';
	}
	else
	{
		$nick_link = $prof_arr['NickName'];
	}
	$ret .= '<div class="prof_details_wrapper">';
		$ret .= '<div class=\"clear_both\"></div>';
		$ret .= get_member_thumbnail( $senderID, 'left' );
		
		$ret .= '<div class="prof_ditails_block">';
			$ret .= '<div class="li_word">'._t('_NickName').':</div><div class="li_value">' . $nick_link . '</div>';
			$ret .= '<div class="li_word">'._t('_Sex').':</div><div class="li_value">' . $prof_sex . '</div>';
			$ret .= '<div class="li_word">'._t('_DateOfBirth').':</div><div class="li_value">' . $prof_age . '</div>';
			$ret .= '<div class="li_word">'._t('_From').':</div><div class="li_value"> ' . $country . '</div>';
		$ret .= '</div>';
		$ret .= '<div class=\"clear_both\"></div>';
	$ret .= '</div>';


	return $ret;
}



function ShowPoll( $uID )
{

    global $_page;
    global $site;

    $_page['js_name']   = 'profile_poll.js';
	
	$sButtonValue = _t( '_Vote' );

    $ret .=
<<<EOS
	<div id="dpol_{$uID}"  class="pollBlock" >
    	<div id="dpol_caption_{$uID}" class="pollCaption"></div>
    	<div id="dpol_question_{$uID}" class="pollAnswerBlock">
    		<div id="dpol_arr_up_{$uID}" class="pollUp" onmouseover="javascript: scroll_start(document.getElementById('dpol_question_text_{$uID}'), 'down');" onmouseout="javascript: scroll_stop();">
    			<img src="{$site['icons']}pollUp.gif" alt="" />
    		</div>
    		<div id="dpol_arr_down_{$uID}" class="pollDown" onmouseover="javascript: scroll_start(document.getElementById('dpol_question_text_{$uID}'), 'up');" onmouseout="javascript: scroll_stop();">
    			<img src="{$site['icons']}pollDown.gif" alt="" />
    		</div>
    		<div id="dpol_question_text_{$uID}" class="pollQuestionBlock"></div>
    	</div>
    	<div id="dpol_actions_{$uID}" class="pollSubmitBlock">
			<input type="button" value="$sButtonValue" onclick="javascript: send_data( 'dpol_question_text_{$uID}', 'vote', '&param=' + ( document.getElementById( 'current_vote_{$uID}' ) ? document.getElementById( 'current_vote_{$uID}' ).value : '' ), '{$uID}' ); return false;">
			
			<!--<a href="#" onclick="javascript: send_data( 'dpol_question_text_{$uID}', 'vote', '&param=' + ( document.getElementById( 'current_vote_{$uID}' ) ? document.getElementById( 'current_vote_{$uID}' ).value : '' ), '{$uID}' ); return false;">
				<img src="{$site['icons']}pollSubmit.gif" alt="rez" border="0">
			</a>-->
    	</div>
    </div>
    <script type="text/javascript" language="javascript">

    	send_data( 'dpol_question_text_{$uID}', 'questions', '', '{$uID}' );

    </script>

EOS;


    return $ret;

}

function isAutoApproval( $sAction )
{
	$autoApproval_ifPhoto = ( 'on' == getParam("autoApproval_ifPhoto") );
	$autoApproval_ifSound = ( 'on' == getParam("autoApproval_ifSound") );
	$autoApproval_ifVideo = ( 'on' == getParam("autoApproval_ifVideo") );
	$autoApproval_ifProfile = ( 'on' == getParam("autoApproval_ifProfile") );
	$autoApproval_ifJoin = ( 'on' == getParam("autoApproval_ifJoin") );

	switch ( $sAction )
	{
		case 'photo':
			return $autoApproval_ifPhoto;

		case 'sound':
			return $autoApproval_ifSound;

		case 'video':
			return $autoApproval_ifVideo;

		case 'profile':
			return $autoApproval_ifProfile;

		case 'join':
			return $autoApproval_ifJoin;

		default:
			return false;
	}
}


/* * * * Ray MP3 Integration (Begin) * * * */
function getRayMp3Player( $iId, $sPassword, $iViewerId)
{
	return getApplicationContent("mp3", "player", array('id' => $iId, 'password' => md5($sPassword), 'vId' => $iViewerId), true);
}
/* * * * Ray MP3 Integration (End) * * * */

function createUserDataFile( $userID )
{
	global $dir, $date_format, $aUser;
	

	$userID = (int)$userID;
	$fileName = $dir['cache'] . 'user' . $userID . '.php';
	if( $userID > 0 )
	{
		$userQuery = "
			SELECT
					*,
					DATE_FORMAT(`LastLoggedIn`,  '$date_format' ) AS `LastLoggedIn`,
					DATE_FORMAT(`LastModified`,  '$date_format' ) AS `LastModified`
			FROM
					`Profiles`
			WHERE `ID` = '$userID' LIMIT 1
		";
		
		$aPreUser = db_assoc_arr( $userQuery );
		
		if( isset( $aPreUser ) and is_array( $aPreUser ) )
		{
			
			$sUser = '<?';
			$sUser .= "\n\n";
			$sUser .= '$aUser[' . $userID . '] = array();';
			$sUser .= "\n";
			$sUser .= '$aUser[' . $userID . '][\'datafile\'] = true;';
			$sUser .= "\n";

			$replaceWhat = array( '\\',   '\''   );
			$replaceTo   = array( '\\\\', '\\\'' );
			
			foreach( $aPreUser as $key =>  $value )
				$sUser .= '$aUser[' . $userID . '][\'' . $key . '\']' . ' = ' . '\'' . str_replace( $replaceWhat, $replaceTo, $value )  . '\'' . ";\n";
			
			$sUser .= "\n" . '?>';
			
			if( $file = fopen( $fileName, "w" ) )
			{
				fwrite( $file, $sUser );
				fclose( $file );
				@chmod ($fileName, 0666);
				
				@include( $fileName );
				return true;
			}
			else
				return false;
			
		}
	}
	else
		return false;
}

function getProfileInfo( $iProfileID, $checkActiveStatus = false, $forceCache = false )
{
	global $aUser;
	global $dir;
	global $date_format;
	
	$iProfileID = (int)$iProfileID;
	if( !$iProfileID )
		return false;
	
	if( !isset( $aUser[$iProfileID] ) || !is_array( $aUser[$iProfileID] ) || $forceCache )
	{
		$sCacheFile = $dir['cache'] . 'user' . $iProfileID . '.php';
		
		if( !file_exists( $sCacheFile ) || $forceCache )
			if( !createUserDataFile( $iProfileID ) )
				return false;
		
		@include( $sCacheFile );
	}
	
	if( $checkActiveStatus and $aUser[$iProfileID]['Status'] != 'Active' )
		return false;
	
	return $aUser[$iProfileID];
}

function getNewLettersNum( $iID )
{
	$sqlQuery = "SELECT COUNT(`Recipient`) FROM `Messages` WHERE `Recipient`='$iID' AND `New`='1'";
	$iNum = db_value($sqlQuery);
	
	return $iNum;
}

function getFriendNumber ($iID)
{
	$sqlQuery = "SELECT COUNT(*) FROM `FriendList` WHERE ( `ID`='$iID' OR `Profile`='$iID' ) AND `Check`='1'";
	
	return db_value($sqlQuery);
}

/*
* The function returns NickName by given ID. If no ID specified, it tryes to get if from _COOKIE['memberID'];
*/

function getNickName( $ID = '' )
{
	global $dir;


    if ( !$ID && (int)$_COOKIE['memberID'] )
		$ID = $_COOKIE['memberID'];

    if ( !$ID )
		return '';
	
	$arr = getProfileInfo( $ID );
	return $arr['NickName'];
}

/*
 * The function returns Password by given ID.
 */
function getPassword( $ID = '' )
{
    if ( !(int)$ID )
		return '';

	$arr = getProfileInfo( $ID );
	return $arr['Password'];
}

function getSex( $ID = '' )
{
    if ( !(int)$ID )
		return '';

	$arr = getProfileInfo( $ID );
	return $arr['Sex'];
}

function getProfileLink( $iID, $sLinkAdd = '' )
{
	global $site;
	if ( getParam('enable_modrewrite') == 'on' )
		$sLink = $site['url'].getNickName($iID) . ( $sLinkAdd ? "?{$sLinkAdd}" : '' );
	else
		$sLink = $site['url'].'profile.php?ID='.$iID . ( $sLinkAdd ? "&{$sLinkAdd}" : '' );
	
	return $sLink;
}


/**
 * Shows how many days, hours, minutes member was onine last time
 *
 * @param  $lastNavTime
 *
 * @return int
 */
function  getProfileLastOnlinePeriod( $lastNavTime )
{
		if ( $lastNavTime != 0 )
	    {
			$time = date("U") - $lastNavTime;

			if ( $time <= 300 )
			{
			    //$ret = '<strong>' . _t('_Online') . '</strong>';
			    $ret = '<div class="online">' . _t('_Online') . '</div>';
			}
			else
			{
				$minutes = floor( $time / 60 );
				if ( $minutes > 60 )
				{
					$hours = floor( $time / 3600 );
			    	if ( $hours > 24 )
					{
					    $days = floor( $time / 86400 );
					    $hours = floor( ( $time - $days*86400 ) / 3600 );
					    $minutes = floor( ( $time - $days*86400 - $hours*3600 ) / 60 );
					}
					else
					{
						$minutes = floor ( ( $time - $hours*3600 ) / 60 );
					}
				}

				/*
				if( $days > 31 )
				{
					$lnt = '<strong>' . _t('_more_month_ago') . '</strong>';
				}
				else
				{
					if( $days > 7 )
			    	{
			    		$lnt = '<strong>' . _t('_more_week_ago') . '</strong>';
			    	}
			    	else
			    	{
			    		if ( $days >= 7 )
							$lnt = '<strong>' . _t('_week_ago') . '</strong>';
						else
						*/
							//$lnt = ( $days ? $days . ' day(s) ago' : ( $hours ? $hours . ' hour(s) ago' : $minutes . ' minutes ago' )) . '';
							if( $days )
							{
								$ret = _t( '_day(s)', $days );// $days .
							}
							elseif( $hours )
							{
								$ret = _t( '_hour(s)', $hours );
							}
							else
							{
								$ret = _t( '_minute(s)', $minutes );
							}

							/*
			    	}
				}
				*/

			}

	    }
	    else
	    {
			$ret = '<div class="never">' . _t('_never') . '</div>';
	    }

	    return $ret;
}

?>