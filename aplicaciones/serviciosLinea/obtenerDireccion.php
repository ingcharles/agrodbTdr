<?php
function getaddress($lat, $lng){
	$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($lat) . ',' . trim($lng);
	$json = @file_get_contents($url);
	$data = json_decode($json);
	if ($data->status == "OK")
		return $data->results[0]->formatted_address;
	else
		return false;
}

$lat=$_POST['lat'];
$lng=$_POST['lng'];
echo getaddress($lat, $lng);
?>
