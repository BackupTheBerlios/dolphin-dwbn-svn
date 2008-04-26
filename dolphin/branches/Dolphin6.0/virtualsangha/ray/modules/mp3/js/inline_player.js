function changePlayList()
{
	var obj;
	if (navigator.appName.indexOf("Microsoft") != -1)
		obj = window['ray_player_object'];
	else
		obj = document['ray_player'];
	obj.reloadPlayList();
}