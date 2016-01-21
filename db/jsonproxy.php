<?php

$api_key = "AIzaSyD5zx3VInOrWQQwzTbxVrDfgygeyeIL2sA";

header('Content-type: application/json');
$url = $_GET['url'] . "&key=" . $api_key;                    
$json = file_get_contents($url);
if (isset($_GET['debug'])) {
	$json .= "{'URL' : '$url'}";	
}
echo $json;
?>