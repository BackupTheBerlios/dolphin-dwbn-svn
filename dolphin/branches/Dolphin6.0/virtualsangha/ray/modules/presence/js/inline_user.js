function openMailBox(sUrl, ID)
{
	window.open (sUrl + "?ID=" + ID);
}
function downloadPresence(sUrl)
{
	var popupWindowTest = window.open( sUrl, '', '' );
	if( popupWindowTest == null ) alert( "You must disable your popup blocker software to be able to download RAY Desktop" );		
}