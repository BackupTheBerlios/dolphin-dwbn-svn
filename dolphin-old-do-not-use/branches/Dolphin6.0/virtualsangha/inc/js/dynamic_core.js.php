<?php require_once("../header.inc.php"); ?>

var sFolder = "<?php echo $site['url']; ?>xml/";
var sURLGetList = sFolder + "get_list.php?";
var requestsQueue = new Array();
var arrInitialValuesOfAsyncFields = new Array();
var updatedFields = new Array();

var sPostName;
var sPostValue;
var bPostAction;

/*
*	updateList	- function updates options list in drop-down box
*/
function UpdateListCommon(dataType, targetField, filterFlag, iID, sCustomTargetField1, sCustomTargetField2, sUnit) {
	if (document.getElementById(targetField)) {//if targer exists
		switch (dataType) {
			case "ReloadClassifieds":
				ClearSelection(targetField);

				selection = document.getElementById(targetField);
				optionObject = new Option('Loading...', 'null', false, false);
				selection.options[selection.length] = optionObject;
				
				var sURLRequest = "dataType="+dataType+"&"+filterFlag+"="+iID;
				addRequestToQueue(sURLRequest, dataType, targetField);
			break;
			case "ReloadSubTree":
				selection = document.getElementById(targetField);
				if (selection.innerHTML == '') {
					oPicSelection = document.getElementById("tree_action_img_" + iID);
					oPicSelection.src = "images/minus.gif";
					var bReloadTree = true;
					var sURLRequest = "dataType="+dataType+"&"+filterFlag+"="+iID;
					addRequestToQueue(sURLRequest, dataType, targetField, '', '', '', bReloadTree);
				}
				else {
					oPicSelection = document.getElementById("tree_action_img_" + iID);
					oPicSelection.src = "images/plus.gif";
					selection.innerHTML = '';
				}
			break;
			case "ReloadClassifiedsAndCustomsFields":
				ClearSelection(targetField);

				selection = document.getElementById(targetField);

				if (selection) {
					optionObject = new Option('Loading...', 'null', false, false);
					selection.options[selection.length] = optionObject;
				}

				var bAdminMan = document.getElementById('admin_managing');
				if (bAdminMan) {
					//document.getElementById('customRow1').style.display = 'none';
					//document.getElementById('customRow2').style.display = 'none';
					document.getElementById('CustomName1').value="";
					document.getElementById('CustomName2').value="";
					document.getElementById('CustomAction1').value="-1";
					document.getElementById('CustomAction2').value="-1";
				}

				if (oElement = document.getElementById(targetField)) {
					oElement.style.display = 'none';
				}
				if (oElement = getCustomActionField(sCustomTargetField1)) {
					oElement.style.display = 'none';
				}
				if (oElement = getCustomActionField(sCustomTargetField2)) {
					oElement.style.display = 'none';
				}

				var sURLRequest = "dataType="+dataType+"&"+filterFlag+"="+iID;
				addRequestToQueue(sURLRequest, dataType, targetField, sCustomTargetField1, sCustomTargetField2, sUnit);
			break;
			case "ReloadOnlyCustomsFields":
				if (oElement = getCustomActionField(sCustomTargetField1)) {
					oElement.style.display = 'none';
				}
				if (oElement = getCustomActionField(sCustomTargetField2)) {
					oElement.style.display = 'none';
				}

				var sURLRequest = "dataType="+dataType+"&"+filterFlag+"="+iID;
				addRequestToQueue(sURLRequest, dataType, '', sCustomTargetField1, sCustomTargetField2);
			break;
		}
	}
}

function addRequestToQueue(sURLRequest, sXmlNodeName, toField, sCustomTargetField1, sCustomTargetField2, sUnit, bReloadTree) {
	requestsQueue[requestsQueue.length] = new QueueItem(sURLGetList+sURLRequest, sXmlNodeName, toField, sCustomTargetField1, sCustomTargetField2, sUnit, bReloadTree);
	if (requestsQueue.length == 1)
		doSendRequest();
}

function QueueItem(url, sXmlNodeName, toField, sCustomTargetField1, sCustomTargetField2, sUnit, bReloadTree) {
	this.url=url;
	this.sXmlNodeName=sXmlNodeName;
	this.toField=toField;
	this.bReloadTree=bReloadTree;
	this.sCustomTargetField1 = sCustomTargetField1;
	this.sCustomTargetField2 = sCustomTargetField2;
	this.sUnit = sUnit;
}

function doSendRequest() {
	globalObjXmlHttpRequest = createXmlHttpObject();
	if ( globalObjXmlHttpRequest ) {
		globalObjXmlHttpRequest.onreadystatechange = RecieveData;
		globalObjXmlHttpRequest.open("GET", requestsQueue[0].url);
		globalObjXmlHttpRequest.send(null);
	}
}

function createXmlHttpObject() { 	
	var objXmlHttp = false;
	
	if ( window.XMLHttpRequest )
		objXmlHttp = new XMLHttpRequest();
	else if ( window.ActiveXObject )
		objXmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	
	return objXmlHttp;
}

function RecieveData() {
	if(globalObjXmlHttpRequest.readyState == 4) {
		// if( requestsQueue[0] )
		// {
			// alert( requestsQueue[0].url );
			//window.open( requestsQueue[0].url );
		// }

		// Dequeue request and run the handler
		if ((requestData = requestsQueue.shift()) == null)
			throw new Error('Internal error: queue is empty');

		if (globalObjXmlHttpRequest.status == 200) {
			if (requestData.toField) {
				ClearSelection(requestData.toField, requestData.bReloadTree);
			}
			fillSelectionWithXmlData(requestData.sXmlNodeName, requestData.toField, globalObjXmlHttpRequest.responseXML, requestData.sCustomTargetField1, requestData.sCustomTargetField2, requestData.sUnit, requestData.bReloadTree);
		}
		if (requestsQueue.length > 0)
			doSendRequest();

		selection = document.getElementById(requestData.toField);
		if (arrInitialValuesOfAsyncFields[requestData.toField] != undefined &&
			arrInitialValuesOfAsyncFields[requestData.toField] != '')
				selection.value = arrInitialValuesOfAsyncFields[requestData.toField];
		arrInitialValuesOfAsyncFields[requestData.toField]=null;
		selection = document.getElementById(requestData.toField);
		if (selection) {
			if (typeof(selection.onchange) == 'function')
				selection.onchange();	// Perform chain action
		}
	}
}

function ClearSelection(selectionID, bReloadTree) {
	var selection = document.getElementById(selectionID);
	if (bReloadTree) {
		selection.innerHTML = '';
	}
	else {
		for (var i = selection.options.length - 1; i >= 0; i--) {
			selection.options[i] = null;
		}
	}
}

function fillSelectionWithXmlData(targetXmlTagName, selectionID, xmlNode, sCustomTargetField1, sCustomTargetField2, sUnit, bReloadTree) {
	var selection = document.getElementById(selectionID);
	var options = xmlNode.getElementsByTagName(targetXmlTagName);
	var custSel1;
	var custSel1;
	var strt='';
	var bAdminMan = document.getElementById('admin_managing');
	if (bAdminMan==null) bAdminMan = false;

	if (sCustomTargetField1 && sCustomTargetField2 && bAdminMan==false) {
		custSel1 = document.getElementById(sCustomTargetField1);
		custSel2 = document.getElementById(sCustomTargetField2);
		custSel1.innerHTML = '';
		custSel2.innerHTML = '';
	}

	for (var optionIndex = 0; optionIndex <= options.length - 1; optionIndex++) {
		var optionData = options[optionIndex].childNodes;
		var optionID = '';
		var optionValue = '';
		var optionCnt = '';
		var custFieldName1 = '';
		var custFieldName2 = '';
		var customAction1 = '';
		var customAction2 = '';
		var sUnitValue = '';

		for (var propertyIndex = 0; propertyIndex <= optionData.length - 1; propertyIndex++) {
			if (optionData[propertyIndex].nodeType == 1 && optionData[propertyIndex].nodeName == 'ID')
				optionID = getNodeText(optionData[propertyIndex]);
			if (optionData[propertyIndex].nodeType == 1 && optionData[propertyIndex].nodeName == 'Name')
				optionValue = getNodeText(optionData[propertyIndex]);
			if (optionData[propertyIndex].nodeType == 1 && optionData[propertyIndex].nodeName == 'Count')
				optionCnt = getNodeText(optionData[propertyIndex]);

			if (optionData[propertyIndex].nodeType == 1 && optionData[propertyIndex].nodeName == 'CustomFieldName1')
				custFieldName1 = getNodeText(optionData[propertyIndex]);
			if (optionData[propertyIndex].nodeType == 1 && optionData[propertyIndex].nodeName == 'CustomFieldName2')
				custFieldName2 = getNodeText(optionData[propertyIndex]);
			if (optionData[propertyIndex].nodeType == 1 && optionData[propertyIndex].nodeName == 'CustomAction1')
				customAction1 = getNodeText(optionData[propertyIndex]);
			if (optionData[propertyIndex].nodeType == 1 && optionData[propertyIndex].nodeName == 'CustomAction2')
				customAction2 = getNodeText(optionData[propertyIndex]);
			if (optionData[propertyIndex].nodeType == 1 && optionData[propertyIndex].nodeName == 'Unit')
				sUnitValue = getNodeText(optionData[propertyIndex]);
		}

		if (bReloadTree) {
			//var re = /(\d+$)/;
			//re.exec(selectionID);
			//var ahrefs = "<a href=" + document.location + " onclick=\"fillHiddenAndPostForm(" + RegExp.$1 + "," + optionID + "); return false;\">\n";
			var ahrefs = "<a href=\"" + self.location.pathname + "?bSubClassifiedID=" + optionID+"\">\n";
			var ahrefe = "</a>";
			strt +=
				'<div>' +
					'<img src="images/folder_s.gif" style="vertical-align:middle;"/>' +
					'<img src="images/folder.gif" style="vertical-align:middle;"/>' +
					'<span>' +
						ahrefs + optionValue + " (" + optionCnt + ")" + ahrefe +
					'</span>' +
				'</div>';
				
		}
		else if (selection){
			optionObject = new Option(optionValue, optionID, false, false);
			selection.options[selection.length] = optionObject;
			selection.style.display = '';
			if (el = document.getElementById('tr0')) {
				el.style.display=''
			}
		}

		if (bAdminMan) {
			if (custFieldName1 != undefined) {
				document.getElementById('CustomName1').value = custFieldName1;
				if (customAction1 == '&lt;') customAction1 = '<';
				if (customAction1 == '&gt;') customAction1 = '>';
				document.getElementById('CustomAction1').value = customAction1;
			}
			if (custFieldName2 != undefined) {
				document.getElementById('CustomName2').value=custFieldName2;
				if (customAction2 == '&lt;') customAction2 = '<';
				if (customAction2 == '&gt;') customAction2 = '>';
				document.getElementById('CustomAction2').value=customAction2;
			}
		}

		if (sCustomTargetField1 && bAdminMan==false) {
			if (customAction1 == '&lt;') customAction1 = 'Min';
			if (customAction1 == '&gt;') customAction1 = 'Max';
			if (customAction1 == '=') customAction1 = 'Equal';
			custSel1.innerHTML = (custFieldName1 != undefined) ? customAction1+' '+custFieldName1+' ('+sUnitValue+')' : "";

			if (typeof(custFieldName1)!='undefined' && typeof(customAction1)!='undefined' && customAction1!='') {
				if (oElement = getCustomActionField(sCustomTargetField1)) {
					oElement.style.display = '';
				}
			}
		}
		if (sCustomTargetField2 && bAdminMan==false) {
			if (customAction2 == '&lt;') customAction2 = 'Min';
			if (customAction2 == '&gt;') customAction2 = 'Max';
			if (customAction2 == '=') customAction2 = 'Equal';
			custSel2.innerHTML = (custFieldName2 != undefined) ? customAction2+' '+custFieldName2+' ('+sUnitValue+')' : "";

			if (typeof(custFieldName2)!='undefined' && typeof(customAction2)!='undefined' && customAction2!='') {
				if (oElement = getCustomActionField(sCustomTargetField2)) {
					oElement.style.display = '';
				}
			}
		}
		if (sUnit!=undefined &&  sUnit != '' && sUnitValue != ''){
			var eUnit = document.getElementById(sUnit);
			eUnit.value = sUnitValue;
		}
	}

	// if (selection) {
		// optionObject1 = new Option('select', -1, false, false);
		// selection.options[selection.length] = optionObject1;
	// }

	if (bReloadTree) {
		// strt += "</td>\n";
		selection.innerHTML = strt;
	}

	if (bPostAction==true) {
		var sPostSelection = document.getElementById(sPostName);
		sPostSelection.value = sPostValue;
		bPostAction = false; sPostName=''; sPostValue='';
	}
}

//	Retreives inner text of XML node (cross-browsing)
function getNodeText(node) {
	return (node.textContent || node.innerText || node.text) ;
}

function verify_adding_new_adv(lifetime_tr, lifetime, maxlifetime) {
	ilifetime = document.getElementById(lifetime).value;
	if (ilifetime>0) {
		if (ilifetime <= maxlifetime) {
			//alert(lifetime_tr);
			return true;
		}
		document.getElementById(lifetime_tr).style.backgroundColor='red';
	}
	return false;//errors in typing
}

/**
 * Update fields CustomFieldName1, CustomFieldName2, CustomAction1, CustomAction2 in table `Classifieds`
 *
 * @param $iEditAdvertisementID	ID of edited Advertisement
  */
function AdmTryApplyChanges(sActionType, customNumber) {
	if (sActionType=="DeleteCustom") {
		document.getElementById('CustomName'+customNumber).value="";
		document.getElementById('CustomAction'+customNumber).value="-1";
	}
	var sName = document.getElementById('CustomName' + customNumber).value;
	var sAction = document.getElementById('CustomAction' + customNumber).value;
	var iIDcat = document.getElementById('FilterCat').value;
	sName = encodeURIComponent( sName );
	sAction = encodeURIComponent ( sAction );
	var sURLRequest = "dataType="+sActionType+"&iNumb="+customNumber+"&sName="+sName+"&sAction="+sAction+"&iIDcat="+iIDcat;
	AddRequestToQueue2(sURLRequest);
}

/**
 * Update field Unit in table `Classifieds`
 *
  */
function AdmTryApplyUnitChanges(sActionType) {
	if (sActionType=="DeleteUnit") {
		document.getElementById('unit').value="";
	}
	var iIDcat = document.getElementById('FilterCat').value;
	var sUnit = document.getElementById('unit').value;
	var sURLRequest = "dataType="+sActionType+"&sUnit="+encodeURIComponent(sUnit)+"&iIDcat="+iIDcat;
	AddRequestToQueue2(sURLRequest);
}

function FilterReset(){
	document.getElementById('tr1').style.display='none';
	document.getElementById('tr2').style.display='none';
	var el = document.getElementById('tr0');
	if (el) {
		el.style.display='none';
	}
}

function AddRequestToQueue2(sURLRequest) {
	requestsQueue[requestsQueue.length] = new QueueItem2(sURLGetList+sURLRequest);
	if (requestsQueue.length == 1)
		DoSendRequest2();
}

function DoSendRequest2() {
	globalObjXmlHttpRequest = createXmlHttpObject();
	if ( globalObjXmlHttpRequest ) {
		globalObjXmlHttpRequest.onreadystatechange = OnReadyStateChange;
		globalObjXmlHttpRequest.open("GET", requestsQueue[0].url);
		globalObjXmlHttpRequest.send(null);
	}
}

function QueueItem2(url) {
	this.url=url;
}

function OnReadyStateChange() {
	if(globalObjXmlHttpRequest.readyState == 4) {
		// Dequeue request and run the handler
		if ((requestData = requestsQueue.shift()) == null)
			throw new Error('Internal error: queue is empty');
	}
}

function getCustomActionField(customTargetField) {
	var re = /(\d+)/;
	result = customTargetField.match(re);
	if (result[0]>0) {
		var oElement = document.getElementById('tr'+result[0]);
		return oElement;
	}
}

function AddCatFields(sFieldsCat, sAddLnk, sDelLnk) {
	if (document.getElementById(sFieldsCat).style.display == '') {
		document.getElementById(sFieldsCat).style.display = 'none';
		document.getElementById(sDelLnk).style.display = '';
	}
	else {//show and hide first Ad
		document.getElementById(sFieldsCat).style.display = '';
		document.getElementById(sAddLnk).style.display = 'none';
		document.getElementById(sDelLnk).style.display = 'none';
	}
}

function UpdateField(sName, sValue){
	var selection = document.getElementById(sName);
	selection.value = sValue;
	if (selection) {
		if (typeof(selection.onchange) == 'function')
			selection.onchange();	// Perform chain action
	}
}

function UpdateFieldTiny(sName, sValue){
	if (el = document.getElementById('commentText')) {
		document.getElementById('commentText').value = sValue;
		if (typeof tinyMCE != 'undefined') {
			tinyMCE.updateContent('commentText'); 
			tinyMCE.execInstanceCommand('commentText', "mceFocus"); 
		}
	}
}

function UpdateFields(sName, sValue, sName2, sValue2){
	sPostName = sName2;
	sPostValue = sValue2;
	bPostAction = true;

	var selection = document.getElementById(sName);
	selection.value = sValue;
	if (selection) {
		if (typeof(selection.onchange) == 'function')
			selection.onchange();	// Perform chain action
	}
}

function UpdateFieldByInnerHtml(sName, sValue){
	document.getElementById(sName).value = document.getElementById(sValue).innerHTML;
}

function UpdateFieldStyle(sName, sStyle){
	document.getElementById(sName).style.display = sStyle;
}