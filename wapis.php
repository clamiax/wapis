<?php

/* services */
include('services/forecast.php');
include('services/owmap.php');
include('services/wwonline.php');

/* globals */
global $WAPIS_SERVICES;
$WAPIS_SERVICES = ['forecast', 'owmap', 'wwonline'];

/* function implementations */
function wapis_services() {
	global $WAPIS_SERVICES;
	return $WAPIS_SERVICES;
}

function wapis_query($service, $lat, $lon, $cnt) {
	$fn = "${service}_query";
	if(!function_exists($fn))
		return null;
	return $fn($lat, $lon, $cnt);
}

function strgeo($str) {
        $str = rawurlencode($str);
        $uri = "http://maps.googleapis.com/maps/api/geocode/json?address=${str}&sensor=true";
        $d = json_decode(file_get_contents($uri), 1);
        $d = (@$d['results'][0]);
        if(!$d)
                return NULL;
        return [
                'targetf' => $d['formatted_address'],
                'coords' => $d['geometry']['location'],
        ];
}

?>
