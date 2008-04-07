<?php

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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolClassifieds.php' );

$logged['admin'] = member_auth( 1 );
$_page['header'] = _t('_Manage classifieds');

$path = "{$dir['root']}templates/tmpl_{$tmpl}/css/";
$cssPath = "../../templates/tmpl_{$tmpl}/css/";
$_page['css_name'] = $cssPath . "classifieds_view.css";

TopCodeAdmin();
ContentBlockHead('');

Main();

	/**
	 * Generating Main page code
	 *
	 * @return ALL presentation of data
	 */
	function Main() {
		$oClassifieds = new BxDolClassifieds();
		$oClassifieds -> sCurrBrowsedFile = $_SERVER['PHP_SELF'];
		$oClassifieds -> bAdminMode = TRUE;

		print $oClassifieds -> PrintCommandForms();

		if ($_REQUEST) {
			if (isset($_REQUEST['action'])) {
				if ((int)$_REQUEST['action']==3) {
					print $oClassifieds -> PrintFilterForm();
					print $oClassifieds -> PrintFilteredAllAdvertisements();
					ContentBlockFoot();
					BottomCode();
					return;
				}
				elseif ((int)$_REQUEST['action']==2) {
					$iClassifiedSubID = (int)$_REQUEST['FilterSubCat'];
					print $oClassifieds -> PrintSubRecords($iClassifiedSubID, TRUE);
					ContentBlockFoot();
					BottomCode();
					return;
				}
				elseif ((int)$_REQUEST['action']==1) {
					$iClassifiedID = (int)$_REQUEST['FilterCat'];
					print $oClassifieds -> PrintAllSubRecords($iClassifiedID);
					ContentBlockFoot();
					BottomCode();
					return;
				}
			}
			elseif (isset($_GET['bClassifiedID'])) {
				$iClassifiedID = (int)$_GET['bClassifiedID'];
				if ($iClassifiedID > 0) {
					print $oClassifieds -> PrintAllSubRecords($iClassifiedID);
					print $oClassifieds -> PrintBackLink();
					ContentBlockFoot();
					BottomCode();
					return;
				}
			}
			elseif (isset($_GET['bSubClassifiedID'])) {
				$iSubClassifiedID = (int)$_GET['bSubClassifiedID'];
				if ($iSubClassifiedID > 0) {
					print $oClassifieds -> PrintSubRecords($iSubClassifiedID, TRUE);
					print $oClassifieds -> PrintBackLink();
					ContentBlockFoot();
					BottomCode();
					return;
				}
			}
			elseif (isset($_REQUEST['iAction'])) {
				$sAddMainCatSuccC = MsgBox(_t('_AddMainCategory successfully added'));
				$sAddMainCatFailC = MsgBox(_t('_Failed to Insert AddMainCategory'));
				$sAddSubCatSuccC = MsgBox(_t('_AddSubCategory successfully added'));
				$sAddSubCatFailC = MsgBox(_t('_Failed to Insert AddSubCategory'));
				$sDelMainCatSuccC = MsgBox(_t('_DeleteMainCategory was successfully'));
				$sDelMainCatFailC = MsgBox(_t('_Failed to DeleteMainCategory'));
				$sDelSubCatSuccC = MsgBox(_t('_DeleteSubCategory was successfully'));
				$sDelSubCatFailC = MsgBox(_t('_Failed to DeleteSubCategory'));

				switch( $_REQUEST['iAction'] ) {
					case 'AddMainCategory':
						$sName = process_db_input( $_REQUEST['name1'] );
						$sDescription = process_db_input( $_REQUEST['description1'] );
						$sCustomName1 = process_db_input( $_REQUEST['CustomName1'] );
						$sCustomName2 = process_db_input( $_REQUEST['CustomName2'] );
						$sCustomAction1 = process_db_input( $_REQUEST['CustomAction1'] );
						$sCustomAction2 = process_db_input( $_REQUEST['CustomAction2'] );
						if ($sName=='') {
							print $sAddMainCatFailC;
							break;
						}
						$sqlRes = InsertC($sName, $sDescription, $sCustomName1, $sCustomName2, $sCustomAction1, $sCustomAction2);
						print (mysql_affected_rows()!=0) ? $sAddMainCatSuccC : $sAddMainCatFailC;
						break;
					case 'AddSubCategory':
						if (($_REQUEST['FilterCat']) AND (isset($_REQUEST['name2']))) {
							$sName = process_db_input( $_REQUEST['name2'] );
							$sDescription = process_db_input( $_REQUEST['description2'] );
							$sCategoryID = process_db_input( $_REQUEST['FilterCat'] );
							if ((int)$sCategoryID < 1) {
								print $sAddSubCatFailC; break;
							}
							$sqlRes = InsertCS($sCategoryID, $sName, $sDescription);
							print (mysql_affected_rows()!=0) ? $sAddSubCatSuccC : $sAddSubCatFailC;
						}
					break;
					case 'DeleteMainCategory':
						if ($_REQUEST['FilterCat']) {
							$sCategoryID = process_db_input( $_REQUEST['FilterCat'] );
							$query = "DELETE FROM `Classifieds` WHERE `ID` = '{$sCategoryID}'";
							$sqlRes = db_res( $query );
							print (mysql_affected_rows()!=0) ? $sDelMainCatSuccC : $sDelMainCatFailC;
						}
					break;
					case 'DeleteSubCategory':
						if ($_REQUEST['SubClassified']) {
							$sCategoryID = process_db_input( $_REQUEST['SubClassified'] );
							$query = "DELETE FROM `ClassifiedsSubs` WHERE `ClassifiedsSubs`.`ID` = {$sCategoryID}";
							$sqlRes = db_res( $query );
							print (mysql_affected_rows()!=0) ? $sDelSubCatSuccC : $sDelSubCatFailC;
						}
					break;
				}
			}
			elseif (isset($_REQUEST['DeleteAdvertisementID'])) {
				$id = (int)$_REQUEST['DeleteAdvertisementID'];
				if ($id > 0) {
					print ActionDeleteAdvertisement($id);
				}
			}
			elseif (isset($_REQUEST['ActivateAdvertisementID'])) {
				$id = (int)$_REQUEST['ActivateAdvertisementID'];
				if ($id > 0) {
					print ActionActivateAdvertisement($id);
				}
			}
			elseif (isset($_REQUEST['ActivateAdvWholesale'])) {
				print UpdateAllNewAdvIntoActive();
			}
			elseif (isset($_REQUEST['DeleteSelected'])) {
				print DeleteSelectedAdv();
			}
			elseif (isset($_REQUEST['ApproveSelected'])) {
				print ApproveSelectedAdv();
			}
			if (isset($_REQUEST['UpdatedAdvertisementID'])) {
				$id = (int)$_REQUEST['UpdatedAdvertisementID'];
				if ($id > 0) {
					if (isset($_REQUEST['DeletedPictureID']) && (int)$_REQUEST['DeletedPictureID']>0) {
						//delete a pic
						print $oClassifieds->ActionDeletePicture();
						print $oClassifieds->PrintEditForm($id);
						//break;
					} else {
						print $oClassifieds->ActionUpdateAdvertisementID($id);
					}
				}
				return;
			}
			elseif (isset($_REQUEST['EditAdvertisementID'])) {
				if (((int)$_REQUEST['EditAdvertisementID']) > 0) {
					print $oClassifieds -> PrintEditForm((int)$_REQUEST['EditAdvertisementID']);
					print $oClassifieds -> PrintBackLink();
					ContentBlockFoot();
					BottomCode();
					return;
				}
			}
			elseif (isset($_REQUEST['ShowAdvertisementID'])) {
				if ($_REQUEST['ShowAdvertisementID'] > 0) {
					print $oClassifieds -> ActionPrintAdvertisement($_REQUEST['ShowAdvertisementID']);
					print $oClassifieds -> PrintBackLink();
					ContentBlockFoot();
					BottomCode();
					return;
				}
			}
			elseif (isset($_REQUEST['BuyNow'])) {
				$advId = (int)$_REQUEST['IDAdv'];
				if ($advId > 0) {
					print $oClassifieds -> ActionBuyAdvertisement($advId);
					ContentBlockFoot();
					BottomCode();
					return;
				}
			}
			elseif (isset($_REQUEST['BuySendNow'])) {
				$advId = (int)$_REQUEST['IDAdv'];
				if ($advId > 0) {
					print $oClassifieds -> ActionBuySendMailAdvertisement($advId);
					ContentBlockFoot();
					BottomCode();
					return;
				}
			}
			elseif (isset($_REQUEST['postCommentAdv'])) {
				$advId = (int)$_REQUEST['CommAdvertisementID'];
				if ($advId > 0) {
					print $oClassifieds -> ActionPostCommAdvertisement($advId);
					print $oClassifieds -> PrintBackLink2Adv($advId);
					print $oClassifieds -> ActionPrintAdvertisement($advId);
					return;
				}
			}
			elseif (isset($_REQUEST['UsersOtherListing'])) {
				$iProfileID = (int)$_REQUEST['IDProfile'];
				if ($iProfileID > -1) {
					print $oClassifieds -> PrintMyAds($iProfileID);
					ContentBlockFoot();
					BottomCode();
					return;
				}
			}
			elseif (isset($_REQUEST['EditCommentID'])) {
				$iCommId = (int)$_REQUEST['EditCommentID'];
				if ($iCommId > 0) {
					// $sMessage = addslashes( clear_xss( process_pass_data( $_REQUEST['message'] ) ) );
					// $sMessage = str_replace( "\r\n", "<br>", $sMessage );
					// $query = "UPDATE `ClsAdvComments` SET `Message` = '{$sMessage}' WHERE `ClsAdvComments`.`ID` = {$iCommId} LIMIT 1 ;";
					// $sqlRes = db_res( $query );
					// $advId = (int)$_REQUEST['EAdvID'];
					print $oClassifieds->ActionEditComment();
					print $oClassifieds->ActionPrintAdvertisement($_REQUEST['EAdvID']);
					return;
				}
			}
			elseif (isset($_REQUEST['DeleteCommentID'])) {
				$iCommId = (int)$_REQUEST['DeleteCommentID'];
				if ($iCommId > 0) {
					print $oClassifieds->ActionDeleteComment();
					print $oClassifieds->ActionPrintAdvertisement($_REQUEST['DAdvID']);
					return;
				}
			}
		}
		print $oClassifieds -> PrintManageClassifiedsForm();

		$sCap1C = _t("_TREE_C_BRW");
		$sCap2C = _t("_MODERATING");

		print "<h2>{$sCap1C}</h2>";
		print $oClassifieds -> PrintTreeClassifiedsBrowse();

		$sAct = _t("_Activate");
		$sWhol = _t("_wholesale");
		$sActivateAdvWholesale = "<a href=\"{$_SERVER['PHP_SELF']}?ActivateAdvWholesale=1\">{$sAct} {$sWhol}</a>";
		print "<h2>{$sCap2C} ({$sActivateAdvWholesale})</h2>";

		print $oClassifieds -> PrintModeratingTable();
		print '<div style="clear: both;"></div>';
		return;
	}

	ContentBlockFoot();
	BottomCode();

	/**
	 * SQL deteting of Advertisement
	 *
	  * @param $id	ID`s of deleting Advertisement
	 * @return Text presentation of data
	 */
	function ActionDeleteAdvertisement($id) {
		$ret = '';
		$sSuccDel = _t("_SUCC_DEL_ADV");
		$sFailDel = _t("_FAIL_DEL_ADV");
		$iDeleteAdvertisementID = (int)$id;
		$query = "DELETE FROM `ClassifiedsAdvertisements` WHERE `ID` = {$iDeleteAdvertisementID} LIMIT 1";
		$sqlRes = db_res( $query );
		reparseObjTags( 'ad', $iDeleteAdvertisementID );
		$ret .= (mysql_affected_rows()!=0) ? _t($sSuccDel) : _t($sFailDel);
		return MsgBox($ret);
	}

	/**
	 * SQL activating of Advertisement
	 *
	  * @param $id	ID`s of activating Advertisement
	 * @return Text presentation of data
	 */
	function ActionActivateAdvertisement($id) {
		$ret = '';
		$sSuccAct = _t("_SUCC_ACT_ADV");
		$sFailAct = _t("_FAIL_ACT_ADV");
		$iActivateAdvertisementID = (int)$id;
		$query = "UPDATE `ClassifiedsAdvertisements` SET `Status` = 'active' WHERE `ClassifiedsAdvertisements`.`ID` = {$iActivateAdvertisementID} LIMIT 1 ;";
		$sqlRes = db_res( $query );
		reparseObjTags( 'ad', $iActivateAdvertisementID );
		$ret .= (mysql_affected_rows()!=0) ? _t($sSuccAct) : _t($sFailAct);
		return MsgBox($ret);
	}

	/*
	*safe SQL functions
	*/

	/**
	 * SQL Inserting new Classifieds
	 *
	  * @param $sName		Added Name FIeld value
	  * @param $sDescription	Added Description FIeld value
	  * @param $sCustomName1	Added CustomName1 FIeld value
	  * @param $sCustomName2	Added CustomName1 FIeld value
	  * @param $CustomAction1	Added CustomAction1 FIeld value
	  * @param $CustomAction2	Added CustomAction2 FIeld value
	 * @return SQL result
	 */
	function InsertC($sName, $sDescription, $sCustomName1, $sCustomName2, $sCustomAction1, $sCustomAction2) {
		$query = "INSERT INTO `Classifieds` SET
					`Name`='{$sName}',
					`Description`='{$sDescription}'"
					 .(($sCustomName1)?", `CustomFieldName1`='{$sCustomName1}'":"")
					 .(($sCustomName2)?", `CustomFieldName2`='{$sCustomName2}'":"")
					 .(($sCustomAction1)?", `CustomAction1`='{$sCustomAction1}'":"")
					 .(($sCustomAction2)?", `CustomAction2`='{$sCustomAction2}'":"");
		$sqlRes = db_res( $query );
		return $sqlRes;
	}

	/**
	 * SQL Inserting new SubClassifieds
	 *
	 * @param $sCategoryID	Added CategoryID FIeld value
	  * @param $sName		Added Name FIeld value
	  * @param $sDescription	Added Description FIeld value
	 * @return SQL result
	 */
	function InsertCS($sCategoryID, $sName, $sDescription) {
		$query = "INSERT INTO `ClassifiedsSubs` SET
					`IDClassified`='{$sCategoryID}',
					`NameSub`='{$sName}',
					`Description`='{$sDescription}'";
		$sqlRes = db_res( $query );
		return $sqlRes;
	}

	/**
	 * SQL Activate all 'new' Advertisements
	 *
	 * @return text result
	 */
	function UpdateAllNewAdvIntoActive() {
		$sSuccUpd = _t("_SUCC_UPD_ADV");
		$sFailUpd = _t("_FAIL_UPD_ADV");

		$sSelectedQuery = "
			SELECT `ClassifiedsAdvertisements`.`ID`
			FROM `ClassifiedsAdvertisements`
			WHERE `Status`='new'
		";
		$vSelectedAds = db_res ($sSelectedQuery);

		$query = "UPDATE `ClassifiedsAdvertisements` SET `Status` = 'active' WHERE `Status` = 'new'";
		$sqlRes = db_res( $query );

		while( $aSelectedAds = mysql_fetch_assoc($vSelectedAds) ) {
			reparseObjTags( 'ad', $aSelectedAds['ID'] );
		}

		$ret = (mysql_affected_rows()!=0) ? _t($sSuccUpd) : _t($sFailUpd);
		return MsgBox($ret);
	}

	function DeleteSelectedAdv() {
		$sSuccUpd = _t("_SUCC_UPD_ADV");
		$sFailUpd = _t("_FAIL_UPD_ADV");
		if (isset($_REQUEST['Check'])  && is_array($_REQUEST['Check'])) {
			foreach($_REQUEST['Check'] as $iKey => $iVal) {
				$query = "DELETE FROM `ClassifiedsAdvertisements` WHERE `ID` = {$iVal} LIMIT 1";
				$sqlRes = db_res( $query );
				reparseObjTags( 'ad', $iVal );
		 	}
		}

		$ret = (mysql_affected_rows()!=0) ? _t($sSuccUpd) : _t($sFailUpd);
		return MsgBox($ret);
	}

	function ApproveSelectedAdv() {
		$sSuccUpd = _t("_SUCC_UPD_ADV");
		$sFailUpd = _t("_FAIL_UPD_ADV");
		if (isset($_REQUEST['Check'])  && is_array($_REQUEST['Check'])) {
			foreach($_REQUEST['Check'] as $iKey => $iVal) {
				$query = "UPDATE `ClassifiedsAdvertisements` SET `Status` = 'active' WHERE `ID` = {$iVal} LIMIT 1";
				$sqlRes = db_res( $query );
				reparseObjTags( 'ad', $iVal );
		 	}
		}

		$ret = (mysql_affected_rows()!=0) ? _t($sSuccUpd) : _t($sFailUpd);
		return MsgBox($ret);
	}
?>