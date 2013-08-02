<?php

function calculateDistanceFromLatLong($point1,$point2,$uom='km') {
	//	Use Haversine formula to calculate the great circle distance
	//		between two points identified by longitude and latitude
	switch (strtolower($uom)) {
		case 'km'	:
			$earthMeanRadius = 6371.009; // km
			break;
		case 'm'	:
			$earthMeanRadius = 6371.009 * 1000; // km
			break;
		case 'miles'	:
			$earthMeanRadius = 3958.761; // miles
			break;
		case 'yards'	:
		case 'yds'	:
			$earthMeanRadius = 3958.761 * 1760; // miles
			break;
		case 'feet'	:
		case 'ft'	:
			$earthMeanRadius = 3958.761 * 1760 * 3; // miles
			break;
		case 'nm'	:
			$earthMeanRadius = 3440.069; // miles
			break;
	}
	$deltaLatitude = deg2rad($point2['latitude'] - $point1['latitude']);
	$deltaLongitude = deg2rad($point2['longitude'] - $point1['longitude']);
	$a = sin($deltaLatitude / 2) * sin($deltaLatitude / 2) +
		 cos(deg2rad($point1['latitude'])) * cos(deg2rad($point2['latitude'])) *
		 sin($deltaLongitude / 2) * sin($deltaLongitude / 2);
	$c = 2 * atan2(sqrt($a), sqrt(1-$a));
	$distance = $earthMeanRadius * $c;
	return $distance;
}

?>