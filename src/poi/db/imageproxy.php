<?php

$api_key = "AIzaSyD5zx3VInOrWQQwzTbxVrDfgygeyeIL2sA";

header('Content-type: image/jpeg');
$url = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=" . $_GET['size'] . "&photoreference=" . $_GET['ref'] . "&sensor=true&key=" . $api_key;                    
$jpeg = file_get_contents($url);
echo $jpeg;
?>