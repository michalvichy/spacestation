<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Geocoding your database</title>
<style>
body {color: #333;background: #ddd; font-family: 'Trebuchet MS', sans-serif; font-size: 16px; font-style: normal; font-weight: bold; text-transform: normal; letter-spacing: -1px; line-height: 1.2em;}
#container {width: 750px; margin: 20px; background: #eee; border: 1px solid #aaa}
#header {margin: 0px 20px; color: #111}
#success {width: 720px; margin: 15px; border: 1px solid #aaa; background: #fff; height: 320px; overflow-y: scroll; background: #bcdabc}
#success span {display: block; padding: 4px; border-bottom: 1px solid #aaa}
#failures{width: 720px; margin: 15px; border: 1px solid #aaa; background: #fff; height: 120px; overflow-y: scroll; background: #f7d9da}
#failures span {display: block; padding: 4px; border-bottom: 1px solid #888}
@import url(http://fonts.googleapis.com/css?family=Lato:300,400,700);
@charset 'UTF-8';
/* Base Styles */
#cssmenu > ul,
#cssmenu > ul li,
#cssmenu > ul ul {
  list-style: none;
  margin: 0;
  padding: 0;
}
#cssmenu > ul {
  position: relative;
  z-index: 597;
  float: left;
}
#cssmenu > ul li {
  float: left;
  min-height: 1px;
  line-height: 1.3em;
  vertical-align: middle;
  padding: 10px;
}
#cssmenu > ul li.hover,
#cssmenu > ul li:hover {
  z-index: 599;
  cursor: default;
}
#cssmenu > ul ul {
  visibility: hidden;
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 598;
}
#cssmenu > ul ul li {
  float: none;
}
#cssmenu > ul li:hover > ul {
  visibility: visible;
}
/* Align last drop down RTL */
/* Theme Styles */
#cssmenu > ul a:link {
  text-decoration: none;
}
#cssmenu > ul a:active {
  color: #ffa500;
}
#cssmenu li {
  padding: 0;
  color: #000;
}
#cssmenu {
  font-family: 'Lato', sans-serif;
  width: auto;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  -ms-border-radius: 3px;
  -o-border-radius: 3px;
  border-radius: 3px;
  background: #1b9bff;
  font-size: 13px;
  -moz-box-shadow: inset 0 2px 2px rgba(255, 255, 255, 0.3);
  -webkit-box-shadow: inset 0 2px 2px rgba(255, 255, 255, 0.3);
  box-shadow: inset 0 2px 2px rgba(255, 255, 255, 0.3);
}
#cssmenu > ul {
  padding: 0 5px;
  -moz-box-shadow: inset 0 -2px 2px rgba(0, 0, 0, 0.3);
  -webkit-box-shadow: inset 0 -2px 2px rgba(0, 0, 0, 0.3);
  box-shadow: inset 0 -2px 2px rgba(0, 0, 0, 0.3);
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  -ms-border-radius: 3px;
  -o-border-radius: 3px;
  border-radius: 3px;
  display: block;
  float: none;
  zoom: 1;
}
#cssmenu > ul:before {
  content: '';
  display: block;
}
#cssmenu > ul:after {
  content: '';
  display: table;
  clear: both;
}
#cssmenu > ul > li {
  padding: 8px 5px;
}
#cssmenu > ul > li > a,
#cssmenu > ul > li > a:link,
#cssmenu > ul > li > a:visited {
  text-shadow: 0 -1px 1px #004881;
  color: #fff;
  padding: 7px 20px;
  display: block;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  -ms-border-radius: 3px;
  -o-border-radius: 3px;
  border-radius: 3px;
}
#cssmenu > ul > li > a:hover,
#cssmenu > ul > li:hover > a {
  background-color: #0082e7;
}
#cssmenu li li a {
  color: #8b8b8b;
  font-size: 13px;
}
#cssmenu li li a:hover {
  color: #5c5c5c;
  border-color: #5c5c5c;
}
#cssmenu ul ul {
  margin: 0 10px;
  padding: 0 10px;
  float: none;
  background: #efefef;
  border: 2px solid #1b9bff;
  border-top: none;
  right: 0;
  left: 0;
  -webkit-border-radius: 0 0 3px 3px;
  -moz-border-radius: 0 0 3px 3px;
  -ms-border-radius: 0 0 3px 3px;
  -o-border-radius: 0 0 3px 3px;
  border-radius: 0 0 3px 3px;
  -moz-box-shadow: 0 2px 3px rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: 0 2px 3px rgba(0, 0, 0, 0.2);
  box-shadow: 0 2px 3px rgba(0, 0, 0, 0.2);
}
#cssmenu ul > li > ul > li {
  margin: 0 10px 0 0;
  position: relative;
  padding: 0;
  float: left;
}
#cssmenu ul > li > ul > li > a {
  padding: 10px 20px 10px 10px;
  display: block;
}
#cssmenu ul > li > ul > li.has-sub > a:before {
  content: '';
  position: absolute;
  top: 18px;
  right: 6px;
  border: 5px solid transparent;
  border-top: 5px solid #8b8b8b;
}
#cssmenu ul > li > ul > li.has-sub > a:hover:before {
  border-top: 5px solid #5c5c5c;
}
#cssmenu ul ul ul {
  width: 200px;
  top: 100%;
  border: 2px solid #1b9bff;
}
#cssmenu ul ul ul li {
  float: none;
}
.admin-logo {margin: 0px 20px -20px !important;}
</style>
</head>
<body>
<h1>POI Automap Database Management - GeoCode</h1>
<div id="container">
<div id='cssmenu'>
<ul>
   <li class='active'><a href='import.php'><span>Upload</span></a></li>
   <li><a href='export.php'><span>Download</span></a></li>
   <li><a href='geo.php'><span>Geocode</span></a></li>
</ul>
</div>
<div id="header">
<h1>Database Geocoding</h1>
</div>
<div id="main">
<?php
  require_once("db.config.php");
  define("MAPS_HOST", "maps.google.com");
  try {
      $dbh = new PDO("mysql:host=" . db('hostname') . ";dbname=" . db('dbname'), db('username'), db('password'));
      $sql = "SELECT * FROM " . db('tablename') . " WHERE " . fields('latitude') . " = '' OR " . fields('latitude') . " = '0'";
      $failures = "<h3 style='margin-left: 14px'>The Following addresses failed to Geocode</h3><div id='failures'>";
	  echo '<div id="success">';
	  foreach ($dbh->query($sql) as $row) {
          $geocode_pending = true;
          while ($geocode_pending) {
              $delay = 100;
              $base_url = "http://" . MAPS_HOST . "/maps/api/geocode/xml?address=";
              $address = $row[fields('address')];
			  $recordID = $row[fields('id')];
              $request_url = $base_url . urlencode($address) . "&sensor=true";
              $xml = simplexml_load_file($request_url) or die("url not loading");
              $status = $xml->status;
              if (strcmp($status, "OK") == 0) {
                  $geocode_pending = false;
                  $lat = $xml->result->geometry->location->lat;
                  $lng = $xml->result->geometry->location->lng;
                  $update_SQL = "UPDATE " . db('tablename') . " SET " . fields('latitude') . "='$lat', " . fields('longitude') . "='$lng' WHERE " . fields('id') . "='$recordID'";
                  //echo $update_SQL;
				  $count = $dbh->exec($update_SQL);
				  echo '<span><strong>'. $address . '</strong> - Geocode Successful ('. $lat . ',' . $lng . ')</span>';
              } elseif (strcmp($status, "OVER_QUERY_LIMIT") == 0) {
                  // sent geocodes too fast
                  $delay += 100000;
              } else {
                  // failure to geocode
                  $geocode_pending = false;
                  $failures .= "<span>" . $address . " failed to geocode. ";
                  $failures .= "Received status " . $status . "</span>";
              }
              usleep($delay);
          }
      }
      $dbh = null;
  }
  catch (PDOException $e) {
      echo $e->getMessage();
  }
echo "</div>";
  $failures .= "</div>";
  echo $failures;
?>
</div>
</body>
</html>
