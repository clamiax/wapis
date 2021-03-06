<?php
define('OWMAP_BASE', 'http://api.openweathermap.org/data/2.5/forecast/daily');

function owmap_query($lat, $lon, $cnt) {
	$qry = [
		'lat' => $lat,
		'lon' => $lon,
		'cnt' => $cnt,
		'mode' => 'json',
		'units' => 'metric'
	];
	if(defined('OWMAP_APIKEY'))
		$qry['APPID'] = OWMAP_APIKEY;
	$uri = OWMAP_BASE.'?'.http_build_query($qry);
	if(!($d = file_get_contents($uri)))
		return NULL;
	return owmap_refine(json_decode($d));
}

function owmap_refine($data) {
	$ret = [
		'service' => 'owmap',
		'weather' => []
	];

	$cnt = $data->cnt;
	for($i = 0; $i < $cnt; ++$i) {
		$hourly = []; /* XXX to be filled */
		$ret['weather'][] = [
			'ts' => $data->list[$i]->dt,
			'temp' => $data->list[$i]->temp->day,
			'windspeed' => number_format($data->list[$i]->speed / 1000 * 3600, 2), /* mps -> kmph */
			'hourly' => $hourly
		];
	}
	return $ret;
}

?>
