<?php

  //Query String Variables
  $extendLat = $_GET['extendLat'];
  $extendLng = $_GET['extendLng'];
  $southWestLat = $_GET['swLat'] - $extendLat;
  $northEastLat = $_GET['neLat'] + $extendLat;
  $southWestLng = $_GET['swLng'] - $extendLng;
  $northEastLng = $_GET['neLng'] + $extendLng;
  $cat = strtolower($_GET['cat']);
  try {
      header("Content-type: application/json");
	  require_once("db.config.php");
      $dbh = new PDO("mysql:host=" . db('hostname') . ";dbname=" . db('dbname'), db('username'), db('password'));
      $sql = "SELECT * FROM " . db('tablename') . " WHERE " . fields('latitude') . " BETWEEN '$southWestLat' AND '$northEastLat' AND " . fields('longitude') . " BETWEEN '$southWestLng' AND '$northEastLng' and " . fields('category') . " LIKE '%$cat%'";
      $xml_output = '{"html_attributions" : [], "results" : [';
      foreach ($dbh->query($sql) as $row) {
			$cleanHTML = htmlentities($row[fields('html')], ENT_QUOTES);
			$cleanHTML = fixstr($cleanHTML);
			$xml_output .= '{"geometry" : {"location" : ';
			$record_array = array("lat" => $row[fields('latitude')], "lng" => $row[fields('longitude')], "name" => $row[fields('title')] , "address" => $row[fields('address')] , "url" => htmlentities($row[fields('url')]) , "html" => $cleanHTML, "category" => $row[fields('category')] , "icon" => $row[fields('icon')]); 
			$xml_output .= json_encode($record_array) . "}},";
			}
	  $xml_output .= '{"geometry" : {"location" : ';
	  $record_array = array("lat" => 0, "lng" => 0, "name" => "" , "address" => "" , "url" => "", "html" => "", "category" => "" , "icon" => "blank"); 
      $xml_output .= json_encode($record_array) . '}}],   "status" : "OK"}';
      echo $xml_output;
      $dbh = null;
  }
  catch (PDOException $e) {
      echo $e->getMessage();
  }
function fixstr($str){
	$str = stripslashes($str); // strip out hash marks to fix apostropies and single quote marks
	$str = strtr ($str,chr(13),' '); // replace carriage return with dash
	$str = strtr ($str,chr(10),' '); // replace line feed with space
	$str = strtr ($str,chr(9),' '); // replace line feed with space
	$str = strtr ($str,'&&',''); // replace line feed with space
	return $str; // take it back home
}
?>

