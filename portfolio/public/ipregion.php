<?php
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
$data = json_decode(@file_get_contents("https://stat.ripe.net/data/rir-geo/data.json?resource=$ip"), true);
$country = $data['data']['located_resources'][0]['location'] ?? 'UNKNOWN';

echo $country;
?>