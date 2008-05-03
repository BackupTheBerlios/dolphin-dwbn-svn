function EcecuteAjax(sAction, sCommands, sErrorString) {
	if (sAction == 'check_login') {
		sUsername = document.getElementById('ID').value;
		sPass = document.getElementById('Password').value;
		$.post("xml/get_list.php?dataType=login&u=" + sUsername + "&p=" + sPass, 
		  function(sResponse) {
			if (sResponse=='success') {
				document.forms.ajax_login.submit();
			} else {
				alert(sErrorString);
			}
		  } 
		);

	}
}