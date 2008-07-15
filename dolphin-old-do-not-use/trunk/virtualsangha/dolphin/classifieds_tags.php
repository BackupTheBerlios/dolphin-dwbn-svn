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
$_page['name_index'] 	= 26;
$_page['css_name'] = 'classifieds_view.css';
$_page['extra_js'] = $oTemplConfig -> sTinyMceEditorJS;

check_logged();

$oClassifieds = new BxDolClassifieds();

$_page['header'] = $oClassifieds -> GetHeaderString();
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
		$oClassifieds -> sCurrBrowsedFile = $_SERVER['PHP_SELF'];
		$oClassifieds -> sCurrBrowsedFile = 'classifieds.php';
		$sRetHtml .= $oClassifieds -> PrintCommandForms();

		if ($_REQUEST) {
			if (isset($_GET['tag'])) {
				$sTag = process_db_input( $_GET['tag'] );
				$sRetHtml .= $oClassifieds->PrintAdvertisementsByTag($sTag);
			}
		}
		// else {
			// $sRetHtml .= $oClassifieds -> PrintClassifieds();
		// }

		return $sRetHtml;
	}
?>