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
$oClassifieds -> sCurrBrowsedFile = $_SERVER['PHP_SELF'];
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
		//$oClassifieds -> sCurrBrowsedFile = $_SERVER['PHP_SELF'];
		$sRetHtml .= $oClassifieds -> PrintCommandForms();

		if ($_REQUEST) {
			if (isset($_GET['PostAd'])) {
				if (((int)$_GET['PostAd']) == 1) {
					$sRetHtml .= $oClassifieds -> PrintPostAdForm();
				}
			}
			elseif (isset($_GET['MyAds'])) {
				if (((int)$_GET['MyAds']) == 1) {
					$oClassifieds -> UseDefaultCF();
					$sRetHtml .= $oClassifieds -> PrintMyAds();
				}
			}
			elseif (isset($_POST['PostAdMessage'])) {
				if ($_POST['PostAdMessage'] == 'Send') {
					$arrPostAdv = $oClassifieds -> FillPostAdvertismentArrByPostValues();//validating
					$arrErr = $oClassifieds -> checkGroupErrors( $arrPostAdv );
					if( empty( $arrErr ) ) {
						$oClassifieds -> UseDefaultCF();
						$sRetHtml .= $oClassifieds -> ActionPostAdMessage();
					}
					else {
						$sRetHtml .= $oClassifieds -> PrintPostAdForm($arrErr);
					}
				}
			}
			elseif (isset($_POST['EditAdvertisementID'])) {
				if (((int)$_POST['EditAdvertisementID']) > 0) {
					$sRetHtml .= $oClassifieds -> PrintEditForm((int)$_POST['EditAdvertisementID']);
					//$oClassifieds -> UseDefaultCF();
					//$sRetHtml .= $oClassifieds -> PrintBackLink2Adv((int)$_POST['EditAdvertisementID']);
				}
			}
			elseif (isset($_POST['UpdatedAdvertisementID'])) {
				$id = (int)$_POST['UpdatedAdvertisementID'];
				if ($id > 0) {
					if (isset($_REQUEST['DeletedPictureID']) && (int)$_REQUEST['DeletedPictureID']>0) {
						//delete a pic
						$sRetHtml .= $oClassifieds->ActionDeletePicture();
						$sRetHtml .= $oClassifieds -> PrintEditForm($id, $arrErr);
						//break;
					} else {
						$arrPostAdv = $oClassifieds -> FillPostAdvertismentArrByPostValues();//validating
						$arrErr = $oClassifieds -> checkGroupErrors( $arrPostAdv, TRUE );

						if( empty( $arrErr ) ) {
							$sRetHtml .= $oClassifieds -> ActionUpdateAdvertisementID($id);
						}
						else {
							$sRetHtml .= $oClassifieds -> PrintEditForm($id, $arrErr);
							$oClassifieds -> UseDefaultCF();
							$sRetHtml .= $oClassifieds -> PrintBackLink2Adv($id);
						}
					}
				}
			}
			elseif (isset($_POST['DeleteAdvertisementID'])) {
				$id = (int)$_POST['DeleteAdvertisementID'];
				if ($id > 0) {
					$sRetHtml .= $oClassifieds -> ActionDeleteAdvertisement($id);
				}
			}
		}
		else {
			$sRetHtml .= _t("_WARNING");
		}

		return $sRetHtml;
	}
?>