<?php

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
require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'sdating.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );


require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolEvents.php' );

// --------------- page variables and login

$_page['name_index'] = 55;
$_page['css_name'] = 'sdating.css';
$_page['extra_css'] = $oTemplConfig -> sCalendarCss;
$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorJS;

check_logged();

$_page['header'] = _t("_sdating_h");
$_page['header_text'] = _t("_sdating_h");

$oEvents = new BxDolEvents();
if ($logged['admin'])
	$oEvents->bAdminMode = TRUE;

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

function PageCompPageMainCode() {
	global $oEvents;
	$bEventCreating = (getParam('enable_event_creating') == 'on');
	$sRetHtml = '';
	$sRetHtml .= $oEvents -> PrintCommandForms();

	switch ( $_REQUEST['action'] ) {
		//print functions
		case 'show':
			$sRetHtml .= $oEvents->PageSDatingShowEvents();
			break;
		case 'show_info':
			$sRetHtml .= $oEvents->PageSDatingShowInfo();
			break;
		case 'show_part':
			$sRetHtml .= $oEvents->PageSDatingShowParticipants();
			break;
		case 'search':
			$sRetHtml .= $oEvents->PageSDatingShowForm();
			$sRetHtml .= $oEvents->PageSDatingShowEvents();
			break;
		case 'search_by_tag':
			$sRetHtml .= $oEvents->ShowSearchResult();
			break;
		case 'calendar':
			$sRetHtml .= $oEvents->PageSDatingCalendar();
			break;

		/*case 'select_match':
			$sRetHtml .= $oEvents->PageSDatingSelectMatches();
			break;*/

		//forms of editing
		case 'new':
			if ($bEventCreating) {
				if (isset($_POST['event_save'])) {
					$aPostAdv = $oEvents -> FillPostEventArrByPostValues();
					$aErr = $oEvents -> CheckEventErrors( $aPostAdv );
					if( empty( $aErr ) ) {
						$add_res = $oEvents->SDAddEvent();
						$_REQUEST['event_id'] = mysql_insert_id();
						$sRetHtml .= $oEvents->PageSDatingShowInfo();
					} else {
						$sRetHtml .= $oEvents->PageSDatingNewEventForm(-1, $aErr);
					}
				} else {
					$sRetHtml .= $oEvents->PageSDatingNewEventForm(-1);
				}
			} else
				$sRetHtml .= '';
			break;
		case 'edit_event':
			$iEventID = (int)($_POST['EditEventID']);
			$sRetHtml .= $oEvents->PageSDatingNewEventForm($iEventID);
			break;

		//non safe functions
		case 'event_save':
			$iEventID = (int)($_POST['EditedEventID']);
			$aPostAdv = $oEvents -> FillPostEventArrByPostValues();
			$aErr = $oEvents -> CheckEventErrors( $aPostAdv );
			if( empty( $aErr ) ) {
				$add_res = $oEvents->SDAddEvent($iEventID);//like update
				$_REQUEST['event_id'] = $iEventID;
				$sRetHtml .= $oEvents->PageSDatingShowInfo();
			} else {
				$sRetHtml .= $oEvents->PageSDatingNewEventForm($iEventID, $aErr);
			}
			break;
		case 'delete_event':
			$sRetHtml .= $oEvents->PageSDatingDeleteEvent();
			$sRetHtml .= $oEvents->PageSDatingShowEvents();
			break;
		default:
			$sRetHtml .= $oEvents->PageSDatingShowEvents();
	}
	return $sRetHtml;
}

PageCode();

?>