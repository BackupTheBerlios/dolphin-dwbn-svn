<?php

/***************************************************************************
 *                            Dolphin Web Community Software
 *                              -------------------
 *     begin                : Mon Mar 23 2006
 *     copyright            : (C) 2006 BoonEx Group
 *     website              : http://www.boonex.com
 *
 *     
 *
 ****************************************************************************/

/***************************************************************************
 *
 *   This is a free software; you can modify it under the terms of BoonEx 
 *   Product License Agreement published on BoonEx site at http://www.boonex.com/downloads/license.pdf
 *   You may not however distribute it for free or/and a fee. 
 *   This notice may not be removed from the source code. You may not also remove any other visible 
 *   reference and links to BoonEx Group as provided in source code.
 *
 ***************************************************************************/

require_once( "../inc/header.inc.php" );
require_once( "{$dir['inc']}design.inc.php" );
require_once( "{$dir['inc']}db.inc.php" );
require_once( "{$dir['inc']}xml.inc.php" );

$query="";
$AddItems=array();
$dataType=$_GET["dataType"];

switch ($dataType) {
	case "ReloadClassifieds":
		$iIDClassified = process_db_input($_GET["IDClassified"]);

		if (!empty($iIDClassified)) {
			$query="SELECT `ID`, `NameSub` AS `Name` FROM `ClassifiedsSubs` WHERE `IDClassified`='{$iIDClassified}'";
		}
		else {
			$AddItems["na"]="Not Applicable";
		}
	break;
	case "ReloadSubTree":
		$iIDClassified = process_db_input($_GET["IDClassified"]);
		if (!empty($iIDClassified)) {
			$query="SELECT `ClassifiedsSubs`.`ID`, `ClassifiedsSubs`.`NameSub` AS `Name`,
			COUNT(`ClassifiedsAdvertisements`.`ID`) AS 'Count'
			FROM `ClassifiedsSubs`
			LEFT JOIN `ClassifiedsAdvertisements`
			ON (`ClassifiedsAdvertisements`.`IDClassifiedsSubs` = `ClassifiedsSubs`.`ID`)
			WHERE `ClassifiedsSubs`.`IDClassified`='{$iIDClassified}'
			GROUP BY `Name`";
		}
		else {
			$AddItems["na"]="Not Applicable";
		}
	break;
	case "ReloadClassifiedsAndCustomsFields":
		$iIDClassified = process_db_input($_GET["IDClassified"]);

		if (!empty($iIDClassified)) {
			$query="
				SELECT `ClassifiedsSubs`.`ID` , `ClassifiedsSubs`.`NameSub` AS `Name`, `CustomFieldName1`, `CustomFieldName2`, `CustomAction1`, `CustomAction2`, `Unit`
				FROM `ClassifiedsSubs` 
				INNER JOIN `Classifieds`
				ON (`Classifieds`.`ID`=`ClassifiedsSubs`.`IDClassified`)
				WHERE `ClassifiedsSubs`.`IDClassified` = '{$iIDClassified}'
				ORDER BY `Name` ASC
			";
		}
		else {
			$AddItems["na"]="Not Applicable";
		}
	break;
	case "ReloadOnlyCustomsFields":
		$iIDClassified = process_db_input($_GET["IDClassified"]);

		if (!empty($iIDClassified)) {
			$query="
				SELECT `CustomFieldName1` , `CustomFieldName2`, `CustomAction1`, `CustomAction2`, `Unit`
				FROM `Classifieds` 
				WHERE `ID` ='{$iIDClassified}'
			";
		}
		else {
			$AddItems["na"]="Not Applicable";
		}
	break;
	case "ApplyChanges":
		$iNumb = process_db_input($_GET["iNumb"]);
		$sAction = process_db_input($_GET["sAction"]);
		$sName = process_db_input($_GET["sName"]);
		$iIDcat = process_db_input($_GET["iIDcat"]);

		if (!empty($iIDcat)) {
			$query="
				UPDATE `Classifieds` SET `CustomFieldName{$iNumb}` = '{$sName}', `CustomAction{$iNumb}` = '{$sAction}' WHERE `Classifieds`.`ID` = {$iIDcat} LIMIT 1 ;
			";

			// global $dir;
			// $filename = $dir['root'] . 'temp/test.txt';
			// if ($handle = fopen($filename, 'w')) {
				// fwrite($handle, $query);
				// fclose($handle);
			// }

			if (!empty($query)) {
				$resData=db_res($query);
			}
			exit;
		}
	break;
	case "DeleteCustom":
		$iNumb = process_db_input($_GET["iNumb"]);
		$iIDcat = process_db_input($_GET["iIDcat"]);

		if (!empty($iIDcat)) {
			$query="
				UPDATE `Classifieds` SET `CustomFieldName{$iNumb}` = NULL, `CustomAction{$iNumb}` = NULL WHERE `Classifieds`.`ID` = {$iIDcat} LIMIT 1 ;
			";
			if (!empty($query)) {
				$resData=db_res($query);
			}
			exit;
		}
	break;
case "ApplyUnitChanges":
		$sUnit = process_db_input($_GET["sUnit"]);
		$iIDcat = process_db_input($_GET["iIDcat"]);

		if (!empty($iIDcat)) {
			$query="
				UPDATE `Classifieds` SET `Unit` = '{$sUnit}' WHERE `Classifieds`.`ID` = {$iIDcat} LIMIT 1 ;
			";

			if (!empty($query)) {
				$resData=db_res($query);
			}
			exit;
		}
	break;
	case "DeleteUnit":
		$sUnit = process_db_input($_GET["sUnit"]);
		$iIDcat = process_db_input($_GET["iIDcat"]);

		if (!empty($iIDcat)) {
			$query="
				UPDATE `Classifieds` SET `Unit` = '$' WHERE `Classifieds`.`ID` = {$iIDcat} LIMIT 1 ;
			";
			if (!empty($query)) {
				$resData=db_res($query);
			}
			exit;
		}
	break;
	case "login":
		$sUsername = process_db_input($_GET["u"]);
		$sPass = process_db_input($_GET["p"]);

		if (!empty($sUsername)) {
			$query="
				SELECT `ID` FROM `Profiles` WHERE `NickName`='{$sUsername}' AND `Password`=MD5('{$sPass}') LIMIT 1 ;
			";
			db_value($query);
			if (mysql_affected_rows()==0) {
				print 'failed';
			} else {
				print 'success';
			}
			exit;
		}
	break;
}

$resultNode = new XmlNode();
$resultNode->name = 'data';

if ( !empty($AddItems) and $_GET["noadd"] != 1 )
	foreach ($AddItems as $key => $val) {
		$AddNode = new XmlNode();
		$AddNode->name = $dataType;
		
		$AddNodeID = new XmlNode();
		$AddNodeID->name = "ID";
		$AddNodeID->value = $key;
		$AddNode->addChild($AddNodeID);
		
		$AddNodeName = new XmlNode();
		$AddNodeName->name = "Name";
		$AddNodeName->value = $val;
			
		$AddNode->addChild($AddNodeName);
		
		$resultNode->addChild($AddNode);
	}

if (!empty($query)) {
	$resData=db_res($query);
	if (mysql_affected_rows()==0 AND $dataType=="ReloadClassifiedsAndCustomsFields") {
		$iIDClassified = process_db_input($_GET["IDClassified"]);
		$query="
SELECT `CustomFieldName1` , `CustomFieldName2` , `CustomAction1` , `CustomAction2`, `Unit`
FROM `Classifieds` 
WHERE `ID` = '{$iIDClassified}'
";
		$resData=db_res($query);
	}
	fillXmlNodeWithDBData($resultNode, $resData, $dataType);
}
sendData($resultNode);

/**
 * Output XML data
 * 
 * @param XmlNode $xmlNode
 */
function sendData($xmlNode) {
	header("Content-type: application/xml");
	send_headers_page_changed();
	echo '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
	echo $xmlNode->getXMLText();
}

function fillXmlNodeWithDBData(&$xmlNode, $dbResource, $objectName) {
	if (! $dbResource) {
		return;
	}
	
	while ($arrObject = mysql_fetch_assoc($dbResource)) {
		$objectNode = new XmlNode();		

		$objectNode->name = $objectName;
		foreach ($arrObject as $dataName => $dataValue) {
			if ( $_GET['applylang'] and $dataName == 'Name' )
				$dataValue = _t( $_GET['applylang'] . $dataValue );
			
			$dataName = htmlspecialchars_adv($dataName);
			$dataValue = htmlspecialchars(htmlspecialchars($dataValue));

			$objectDataNode = new XmlNode();
			$objectDataNode->name = $dataName;
			$objectDataNode->value = $dataValue;

			$objectNode->addChild($objectDataNode);
		}
		$xmlNode->addChild($objectNode);
	}
}

?>