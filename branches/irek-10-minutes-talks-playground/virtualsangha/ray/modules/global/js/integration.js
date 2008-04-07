function getCookie(name) {
	var leftPart = name + "=";
	var aCookies = document.cookie.split(";");

	for(var i=0; i<aCookies.length; i++) {
		var sCookie = aCookies[i];
		while(sCookie.charAt(0) == " ") sCookie = sCookie.substring(1, sCookie.length);
		if(sCookie.indexOf(leftPart) == 0) return sCookie.substring(leftPart.length, sCookie.length);
	}
	return "";
}
function updateRayUserStatus(sUserId) {
	var XMLHttpRequestObject = false;		
	if(sUserId != "") {			
		var d = new Date();
		var url = sRayUrl + "XML.php?action=updateOnlineStatus&id=" + sUserId + "&_t=" + d.getTime();
		if(window.XMLHttpRequest)
			XMLHttpRequestObject = new XMLHttpRequest();		
		else if(window.ActiveXObject)
			XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");			

		if(XMLHttpRequestObject) {
			XMLHttpRequestObject.open("GET", url);		
			XMLHttpRequestObject.send(null);	
		}
	}
}
function openRayWidget(sModule, sApp) {
	if(aRayApps[sModule][sApp] == undefined)return;
	
	var aInfo = aRayApps[sModule][sApp];
	var sUrl = sRayUrl + "index.php?module=" + sModule + "&app=" + sApp;			
	for(var i=0; i<arguments.length - 2; i++)
		sUrl += "&" + aInfo["params"][i] + "=" + arguments[i + 2];
		
	var popupWindow = window.open(sUrl, 'Ray_' + sModule + '_' + sApp + parseInt(Math.random()*100000), 'top=' + aInfo["top"] + ',left=' + aInfo["left"] + ',width=' + aInfo["width"] + ',height=' + aInfo["height"] + ',toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=' + aInfo["resizable"]);
	
	if( popupWindow == null )
		alert( "You should disable your popup blocker software" );
}

updateRayUserStatus(getCookie("memberID"));

var aRayApps = new Array();

//base begin
aRayApps["global"] = new Array();
aRayApps["global"]["admin"] = {"params": new Array('nick', 'password'), "top": 0, "left": 0, "width": 800, "height": 600, "resizable": 0};
//base end
//chat begin
aRayApps["chat"] = new Array();
aRayApps["chat"]["user"] = {"params": new Array('id', 'password'), "top": 0, "left": 0, "width": 730, "height": 515, "resizable": 1};
//chat end

//im begin
aRayApps["im"] = new Array();
aRayApps["im"]["user"] = {"params": new Array('sndId', 'password', 'rspId'), "top": 0, "left": 0, "width": 490, "height": 610, "resizable": 1};
//im end

//video begin
aRayApps["video"] = new Array();
aRayApps["video"]["recorder"] = {"params": new Array('id', 'password'), "top": 0, "left": 0, "width": 350, "height": 435, "resizable": 1};
aRayApps["video"]["player"] = {"params": new Array('id'), "top": 0, "left": 0, "width": 350, "height": 435, "resizable": 1};
aRayApps["video"]["admin"] = {"params": new Array('nick', 'password'), "top": 0, "left": 0, "width": 700, "height": 330, "resizable": 0};
//video end

//desktop begin
aRayApps["desktop"] = new Array();
//desktop end

//mp3 begin
aRayApps["mp3"] = new Array();
aRayApps["mp3"]["player"] = {"params": new Array('id', 'password', 'vId', 'song'), "top": 0, "left": 0, "width": 350, "height": 310, "resizable" : 0 };
aRayApps["mp3"]["editor"] = {"params": new Array('id', 'password'), "top": 0, "left": 0, "width": 440, "height": 570, "resizable": 1};
//mp3 end

//presence begin
aRayApps["presence"] = new Array();
aRayApps["presence"]["user"] = {"params": new Array('id', 'password'), "top": 0, "left": 0, "width": 240, "height": 600, "resizable": 1};
//presence end

//board begin
aRayApps["board"] = new Array();
aRayApps["board"]["user"] = {"params": new Array('id', 'password'), "top": 0, "left": 0, "width": 750, "height": 760, "resizable": 0};
//board end