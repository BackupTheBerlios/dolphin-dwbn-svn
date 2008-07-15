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
require_once( BX_DIRECTORY_PATH_INC . 'members.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_ROOT . "templates/tmpl_{$tmpl}/scripts/BxTemplProfileView.php" );

$_page['name_index']	= 7;
$_page['css_name']		= 'profile_view.css';

check_logged();

$profileID = getID( $_REQUEST['ID'] );

if( $logged['member'] ) {
	$memberID = (int)$_COOKIE['memberID'];
} else {
	$memberID = 0;
}

if ( !$profileID ) {
	$_page['header'] = "{$site['title']} ". _t("_Member Profile");
	$_page['header_text'] = _t("_View profile");
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = MsgBox( _t("_Profile NA") );
	PageCode();
	exit;
}

// Check if member can view profile
$contact_allowed = contact_allowed($memberID, $profileID);
$check_res = checkAction( $memberID, ACTION_ID_VIEW_PROFILES, true, $profileID );

if ( $check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED
	&& !$logged['admin'] && !$logged['moderator'] && $memberID != $profileID && !$contact_allowed )
{
	$_page['header'] = "{$site['title']} "._t("_Member Profile");
	$_page['header_text'] = "{$site['title']} "._t("_Member Profile");
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = '<center>'. $check_res[CHECK_ACTION_MESSAGE] .'</center><br />';
	PageCode();
	exit;
}


$oProfile = new BxTemplProfileView( $profileID );

$_page['extra_js'] 	=
	$oTemplConfig -> sTinyMceEditorMiniJS .
    $oProfile->oVotingView->getExtraJs() .
    $oProfile->oCmtsView->getExtraJs() .
	'<script type="text/javascript">urlIconLoading = "'.getTemplateIcon('loading.gif').'";</script>';

$_page['extra_css'] = $oProfile -> genProfileCSS( $profileID ) . $oProfile->oCmtsView->getExtraCss();
$p_arr              = $oProfile -> _aProfile;

if ( !($p_arr['ID'] && ($logged['admin'] || $logged['moderator'] || $oProfile -> owner || $p_arr['Status'] = 'Active') ) )
{
	$_page['header'] = "{$site['title']} ". _t("_Member Profile");
	$_page['header_text'] = "{$site['title']} ". _t("_Member Profile");
	$_page['name_index'] = 0;
	$_page_cont[0]['page_main_code'] = '<div class="no_result"><div>' . _t("_Profile NA") .'.</div></div>';
	PageCode();
	exit;
}

$_page['header']      = process_line_output( $p_arr['NickName'] ) . ": ". htmlspecialchars_adv( $p_arr['Headline'] );
//$_page['header_text'] = process_line_output( $p_arr['Headline'] );

// track profile views
if ( $track_profile_view && $memberID && !$oProfile -> owner )
{
    db_res( "DELETE FROM `ProfilesTrack` WHERE `Member` = {$memberID} AND `Profile` = $profileID", 0);
    db_res( "INSERT INTO `ProfilesTrack` SET `Arrived` = NOW(), `Member` = {$memberID}, `Profile` = $profileID", 0);
}

$_ni = $_page['name_index'];

//$_page_cont[$_ni]['page_main_code_headers'] = $oProfile -> genColumns(true);
$oPPV = new BxDolProfilePageView($oProfile, $site, $dir);
$_page_cont[$_ni]['page_main_code'] = $oPPV -> getCode();
//$_page_cont[$_ni]['page_main_code'] = $oProfile -> genColumns();


PageCode();

?>
