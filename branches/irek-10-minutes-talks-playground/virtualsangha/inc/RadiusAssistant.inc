<?php
class RadiusAssistant {

	var $maxLat;
	var $minLat;
	var $maxLong;
	var $minLong;

	function RadiusAssistant($Latitude, $Longitude, $Miles) {
		global $maxLat, $minLat, $maxLong, $minLong;
		$EQUATOR_LAT_MILE = 69.172;
		$maxLat = $Latitude + $Miles / $EQUATOR_LAT_MILE;
		$minLat = $Latitude - ($maxLat - $Latitude);
		$maxLong = $Longitude + $Miles / (cos($minLat * M_PI / 180) * $EQUATOR_LAT_MILE);
		$minLong = $Longitude - ($maxLong - $Longitude);
	}

	function MaxLatitude() {
		return $GLOBALS["maxLat"];
	}
	function MinLatitude() {
		return $GLOBALS["minLat"];
	}
	function MaxLongitude() {
		return $GLOBALS["maxLong"];
	}
	function MinLongitude() {
		return $GLOBALS["minLong"];
	}

}

?>