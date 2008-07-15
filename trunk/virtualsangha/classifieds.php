<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolClassifieds.php' );

// --------------- page variables and login
$_page['name_index'] 	= 151;
$_page['css_name'] = 'classifieds_view.css';
$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorJS;

check_logged();

$oClassifieds = new BxDolClassifieds();

$_page['header'] = $oClassifieds -> GetHeaderString();//_t( "_CLASSIFIEDS_VIEW_H" );
$_page['header_text'] = $oClassifieds -> GetHeaderString();

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

PageCode();

	/**
	 * Generating Main page code
	 *
	 * @return ALL presentation of data
	 */
	function PageCompPageMainCode() {
		$sRetHtml = '';
		global $oClassifieds;

		$oAPV = new BxDolAdPageView($oClassifieds);

		$oClassifieds -> sCurrBrowsedFile = $_SERVER['PHP_SELF'];
		$sRetHtml .= $oClassifieds -> PrintCommandForms();

		if ($_REQUEST) {
			//print functions
			if (isset($_REQUEST['action'])) {
				if ((int)$_REQUEST['action']==3) {
					$sRetHtml .= $oClassifieds -> PrintFilterForm();
					$sRetHtml .= $oClassifieds -> PrintFilteredAllAdvertisements();
				}
				elseif ((int)$_REQUEST['action']==2) {
					$iClassifiedSubID = (int)$_REQUEST['FilterSubCat'];
					//$iClassifiedSubID = ($oClassifieds->bUseFriendlyLinks) ? (int)db_value("SELECT `ID` FROM `ClassifiedsSubs` WHERE `ÑEntryUri`='" . $oClassifieds->process_html_db_input($_REQUEST['FilterCat']) . "' LIMIT 1") : (int)$_REQUEST['FilterSubCat'];
					$sRetHtml .= $oClassifieds -> PrintSubRecords($iClassifiedSubID, TRUE);
				}
				elseif ((int)$_REQUEST['action']==1) {
					$iClassifiedID = (int)$_REQUEST['FilterCat'];
					//$iClassifiedID = ($oClassifieds->bUseFriendlyLinks) ? (int)db_value("SELECT `ID` FROM `Classifieds` WHERE `SEntryUri`='" . $oClassifieds->process_html_db_input($_REQUEST['SEntryUri']) . "' LIMIT 1") : (int)$_REQUEST['FilterCat'];
					$sRetHtml .= $oClassifieds -> PrintAllSubRecords($iClassifiedID);
				}
				elseif ($_REQUEST['action']=='report') {
					$iCommentID = (int)$_REQUEST['commentID'];
					print $oClassifieds -> GenReportSubmitForm($iCommentID);
					exit();
				}
				elseif ($_REQUEST['action']=='post_report') {
					print $oClassifieds->ActionReportSubmit();
					exit();
				}
			}
			elseif ((isset($_GET['bClassifiedID']) AND (int)$_GET['bClassifiedID'] > 0) OR (isset($_GET['catUri']) && $_GET['catUri']!='')) {
				//$iClassifiedID = (int)$_GET['bClassifiedID'];
				$iClassifiedID = ($oClassifieds->bUseFriendlyLinks) ? (int)db_value("SELECT `ID` FROM `Classifieds` WHERE `CEntryUri`='" . $oClassifieds->process_html_db_input($_REQUEST['catUri']) . "' LIMIT 1") : (int)$_REQUEST['bClassifiedID'];
				if ($iClassifiedID > 0) {
					$sRetHtml .= $oClassifieds -> PrintFilterForm($iClassifiedID);
					$sRetHtml .= $oClassifieds -> PrintAllSubRecords($iClassifiedID);
				}
			}
			elseif ((isset($_GET['bSubClassifiedID']) AND (int)$_GET['bSubClassifiedID'] > 0) OR (isset($_GET['scatUri']) && $_GET['scatUri']!='')) {
				//$iSubClassifiedID = (int)$_GET['bSubClassifiedID'];
				$iSubClassifiedID = ($oClassifieds->bUseFriendlyLinks) ? (int)db_value("SELECT `ID` FROM `ClassifiedsSubs` WHERE `SEntryUri`='" . $oClassifieds->process_html_db_input($_REQUEST['scatUri']) . "' LIMIT 1") : (int)$_REQUEST['bSubClassifiedID'];
				if ($iSubClassifiedID > 0) {
					$sRetHtml .= $oClassifieds -> PrintFilterForm(0, $iSubClassifiedID);
					$sRetHtml .= $oClassifieds -> PrintSubRecords($iSubClassifiedID, TRUE);
				}
			}
			elseif ((isset($_REQUEST['ShowAdvertisementID']) && (int)$_REQUEST['ShowAdvertisementID']>0) OR (isset($_GET['entryUri']) && $_GET['entryUri']!='')) {
				//$id = (int)$_REQUEST['ShowAdvertisementID'];
				$id = ($oClassifieds->bUseFriendlyLinks) ? (int)db_value("SELECT `ID` FROM `ClassifiedsAdvertisements` WHERE `EntryUri`='" . $oClassifieds->process_html_db_input($_REQUEST['entryUri']) . "' LIMIT 1") : (int)$_REQUEST['ShowAdvertisementID'];
				/*$sRetHtml .=*/ $oClassifieds -> ActionPrintAdvertisement($id);
				$sRetHtml .= $oAPV -> getCode();
			}
			elseif (isset($_GET['SearchForm'])) {
				if (((int)$_GET['SearchForm']) == 1) {
					$sRetHtml .= $oClassifieds -> PrintFilterForm();
				}
			}
			elseif (isset($_REQUEST['UsersOtherListing'])) {
				$iProfileID = (int)$_REQUEST['IDProfile'];
				if ($iProfileID > -1) {
					$sRetHtml .= $oClassifieds -> PrintMyAds($iProfileID);
				}
			}
			//non safe functions
			elseif (isset($_REQUEST['DeleteAdvertisementID'])) {
				$id = (int)$_REQUEST['DeleteAdvertisementID'];
				if ($id > 0) {
					$sRetHtml .= $oClassifieds -> ActionDeleteAdvertisement($id);
				}
			}
			elseif (isset($_REQUEST['BuyNow'])) {
				$advId = (int)$_REQUEST['IDAdv'];
				if ($advId > 0) {
					$sRetHtml .= $oClassifieds -> ActionBuyAdvertisement($advId);
				}
			}
			elseif (isset($_REQUEST['BuySendNow'])) {
				$advId = (int)$_REQUEST['IDAdv'];
				if ($advId > 0) {
					$sRetHtml .= $oClassifieds -> ActionBuySendMailAdvertisement($advId);
				}
			}
			else {
				$sRetHtml .= $oClassifieds -> PrintClassifieds();
			}
		}
		else {
			$sRetHtml .= $oClassifieds -> PrintClassifieds();
		}

		return $sRetHtml;
	}
?>