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

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolMediaQuery.php' );

// --------------- page variables and login

$_page['name_index'] 	= 26;
$_page['css_name']		= 'upload_media.css';
$_ni = $_page['name_index'];


if ( !( $logged['admin'] = member_auth( 1, false ) ) )
	if ( !( $logged['member'] = member_auth( 0, false ) ) )
		if ( !( $logged['aff'] = member_auth( 2, false ) ) )
			$logged['moderator'] = member_auth( 3, false );

$max_thumb_width  = (int)getParam( 'max_thumb_width' );
$max_thumb_height = (int)getParam( 'max_thumb_height' );

$_page['header'] = _t( "_HOTORNOT_H" );
$_page['header_text'] = _t( "_HOTORNOT_H1" );


// --------------- GET/POST actions

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();


PageCode();


function PageCompPageMainCode()
{
	global $site;
	global $oTemplConfig;


	$aPhoto = getVotingItem();

	$check_res = checkAction( $_COOKIE['memberID'], ACTION_ID_RATE_PHOTOS );
	if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED )
	{
		$ret = '
			<table width="100%" cellpadding="4" cellspacing="4" border="0">
				<tr>
					<td align="center">' . $check_res[CHECK_ACTION_MESSAGE] . '</td>
				</tr>
			</table>';
		return $ret;
	}
	
	$ret = '';

	if ($_GET['prev_id'])
		$ret .= showPreviousRated((int)$_GET['prev_id']);

	if( $oTemplConfig -> customize['rate']['showSexSelector'] )
	{
		$ret .= '<div class="rate_sex_form">';
		$ret .= '<form method="post" name="FormVote" action="' . $_SERVER['PHP_SELF'] . '">';
			$ret .= '<span>';
			$ret .= _t("_Show me");
			$ret .= '</span>';
			$ret .= '<span>';
			
			$ret .= '<select name="showme" onchange="document.forms[\'FormVote\'].submit()">' . "\n";
				$selected_all = ( $_REQUEST['showme'] == 'all' || strlen(trim($_REQUEST['showme'])) == 0 ) ? ' selected="selected" ' : '';
				$ret .= '<option value="all"'. $selected_all . '>' . _t("_all") . '</option>';
				$arr = db_arr("SELECT `extra` FROM `ProfilesDesc` WHERE `name` = 'Sex'");
				$vals = preg_split ("/[,\']+/", $arr[0], -1, PREG_SPLIT_NO_EMPTY);
				foreach ( $vals as $v )
				{
					if ( strlen(trim($v)) <= 0 ) continue;
						$ret .= "<option value=\"$v\" ".($_REQUEST['showme'] == $v ? 'selected="selected"' : '').">"._t("_$v")."</option>\n";
				}
			$ret .= '</select>';
			$ret .= '</span>';
			$ret .= '</form>';
		$ret .= '</div>';
	}

	if( empty( $aPhoto ) )
	{
		$ret .= _t_action('_there_is_no_photo_that_you_can_rate');
	}
	else
	{
		if( $oTemplConfig -> customize['rate']['showProfileInfo'] )
		{
			$ret .= '<div class="clear_both"></div>';
			$ret .= ProfileDetails( $aPhoto['med_prof_id'] );
			$ret .= '<div class="clear_both"></div>';
		}
		
		$ret .= getRatingPhoto( $aPhoto );

		$oVotingView = new BxTemplVotingView ('media', (int)$aPhoto['med_id']);
		if( $oVotingView->isEnabled())
		{
			$sUrlAdd = '';
			if ($_REQUEST['showme']) 
				$sUrlAdd .= 'showme='.$_REQUEST['showme'].'&';

			$ret .= "
				<script>
					BxDolVoting.prototype.onvote = function (fRate, iCount)
					{
						document.location = '{$site['url']}rate.php?{$sUrlAdd}prev_id=' + this._iObjId;
					}
					BxDolVoting.prototype.onvotefail = function ()
					{
						document.location = '{$site['url']}rate.php?{$sUrlAdd}prev_id=' + this._iObjId;
					}
				</script>";

			$ret .= '<div style="margin-left:225px">' . $oVotingView->getBigVoting () . '</div>';
		}
	}

	return $ret;
}

function getVotingItem()
{
	if ( strlen($_REQUEST['showme']) && $_REQUEST['showme'] != 'all' )
	{
		$sSexOnly = process_db_input($_REQUEST['showme']);
	}
	$sVoted = getVotedItems();
	$oMediaQuery = new BxDolMediaQuery();
	$oDolVoting = new BxDolVoting ('media', 0, 0);
	return $oMediaQuery -> selectVotingItem( $oDolVoting, $sVoted, $sSexOnly );

}

function VotingTrack( $iMediaID )
{
	$oMediaQuery = new BxDolMediaQuery();
	$iMediaID = (int)$iMediaID;
	$ip = getVisitorIP();
	$oMediaQuery -> insertVotingTrack( $iMediaID, $ip );
}

function getVotedItems()
{
	$ip = getVisitorIP();

	$oDolVoting = new BxDolVoting ('media', 0, 0);
	$_aVotedItems = $oDolVoting -> getVotedItems ($ip);

	$aVotedItems = reviewArray( $_aVotedItems );	

	return $aVotedItems;
}

function reviewArray( $arrays )
{
	$line = '';
	foreach($arrays as $array)
	{
		$line .= '\'' . $array['med_id'] . '\',';
	}
	$line .= '\'\'';
	return $line;
}

function getRatingPhoto( $aPhoto )
{
	global $max_photo_width, $max_photo_height, $dir, $site;

	$sFileSrc = $dir['profileImage'] . $aPhoto['med_prof_id'] . '/photo_' . $aPhoto['med_file'];
	if( extFileExists($sFileSrc) )
	{
		$sPhotoUrl = $site['profileImage'] . $aPhoto['med_prof_id'] . '/photo_' . $aPhoto['med_file'];
	}
	else
	{
		header('Location:' . $_SERVER['PHP_SELF']);
	}


	$ret = '';

	$ret .= '<div class="mediaTitle">';
		$ret .= process_line_output( $aPhoto['med_title'] );
	$ret .= '</div>';
	$ret .= '<div class="photoBlock" style="text-align:center;">';
		$ret .= '<img src="' . getTemplateIcon('spacer.gif') . '" style="width:' . $max_photo_width . 'px; height:' . $max_photo_height . 'px; background-image:url(' . $sPhotoUrl . ');" class="photo" />';
	$ret .= '</div>';


	return $ret;
}



function showPreviousRated( $iPhotoID )
{
	global $site;
	global $oTemplConfig;
	global $max_thumb_width;
	global $max_thumb_height;
	
	$iBarWidth = $oTemplConfig -> iRateSmallRatingBar;
	$iBarNum   = $oTemplConfig -> iRateSmallRatingBarNum;
	
	$query = "
		SELECT
			`media`.`med_id`,
			`med_prof_id`,
			`med_file`,
			`med_title`,
			`med_rating_count`,
			`med_rating_sum`,
			`Profiles`.`NickName`
		FROM `media`
		LEFT JOIN `media_rating` USING (`med_id`)
		LEFT JOIN `Profiles` ON
			(`Profiles`.`ID`=`media`.`med_prof_id`)
		WHERE
			`med_status` = 'active'
			AND `media`.`med_id` = $iPhotoID
		";
	
	$ph_arr = db_arr( $query );
	if( !$ph_arr )
		return '';
	
	
	$urlImg = "{$site['profileImage']}{$ph_arr['med_prof_id']}/thumb_{$ph_arr['med_file']}";
	$urlSpacer = getTemplateIcon( 'spacer.gif' );
	
	$sProfLink = getProfileLink($ph_arr['med_prof_id']);

	$sRatingBar = '';
	$oVotingView = new BxTemplVotingView ('media', (int)$iPhotoID);
	if( $oVotingView->isEnabled())
		$sRatingBar = $oVotingView->getSmallVoting (false);

	

	$ret .= <<<EOJ
	<div class="rate_prev_photo_block">
		<div class="thumbnail_block" style="float:none;">
			<a href="{$site['url']}photos_gallery.php?ID={$ph_arr['med_prof_id']}&amp;photoID={$ph_arr['med_id']}" title="{$ph_arr['med_title']}">
				<img style="width:{$max_thumb_width}px;height:{$max_thumb_height}px;background-image:url($urlImg);" src="$urlSpacer" />
			</a>
		</div>
		<div class="rate_prev_photo_nickname">
			<a href="{$sProfLink}">{$ph_arr['NickName']}</a>
		</div>
		<div style="position:relative; width:50%; height:30px; overflow:visible; margin-top:10px;">
			<div style="position:absolute; right:-94px; width:400px; height:30px;">
				$sRatingBar
			</div>			
		</div>

	</div>
EOJ;
	
	return DesignBoxContent( _t('_Previous rated'), $ret, 1 );
}

?>
