function setVideosNumber(iApproved, iPending)
{
	_s = document.getElementById("pvd_a").innerHTML;
	_s = _s.replace( / 0 /, ' ' + iPending + ' ' );
	document.getElementById("pvd_a").innerHTML = _s;
}