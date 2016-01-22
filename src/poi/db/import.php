<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Upload page</title>
<style type="text/css">
body {color: #333;background: #ddd; font-family: 'Trebuchet MS', sans-serif; font-size: 16px; font-style: normal; font-weight: bold; text-transform: normal; letter-spacing: -1px; line-height: 1.2em;}
#container {width: 750px; margin: 20px; background: #eee; border: 1px solid #aaa; padding: 0px}
#header {margin: 0px 20px; color: #111}
#success {width: 720px; margin: 15px; border: 1px solid #aaa; background: #fff; height: 320px; overflow-y: scroll; background: #bcdabc}
#success span {display: block; padding: 4px; border-bottom: 1px solid #aaa}
#failures{width: 720px; margin: 15px; border: 1px solid #aaa; background: #fff; height: 120px; overflow-y: scroll; background: #f7d9da}
#failures span {display: block; padding: 4px; border-bottom: 1px solid #888}
form {margin: 20px 0px !important}
#main {margin: 40px 25px}
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
<h1>POI Automap Database Management - Import</h1>
<div id="container">
<div id='cssmenu'>
<ul>
   <li class='active'><a href='import.php'><span>Upload</span></a></li>
   <li><a href='export.php'><span>Download</span></a></li>
   <li><a href='geo.php'><span>Geocode</span></a></li>
</ul>
</div>
<div id="form">

<?php
//Upload File
if (isset($_POST['submit'])) {
require_once("db.config.php");

$db = mysql_connect( db('hostname'), db('username'), db('password') ) or die( mysql_error( ) );



if(!$db) 

	die("no db");

if(!mysql_select_db( db('dbname'),$db))

 	die("No database selected.");

$deleterecords = "TRUNCATE TABLE " . db('tablename'); //empty the table of its current records
mysql_query($deleterecords);


	if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
		echo "<div id='header'><h1>" . "File ". $_FILES['filename']['name'] ." uploaded successfully." . "</h1></div><div id='main'>";
		//echo "<h2>Displaying contents:</h2>";
		//readfile($_FILES['filename']['tmp_name']);
	}
	$num_records = 0;
	//Import uploaded file to Database
	$handle = fopen($_FILES['filename']['tmp_name'], "r");

	while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
		$import="INSERT into " . db('tablename') . " (" . fields('latitude') . "," . fields('longitude') . "," . fields('title') . "," . fields('address') . ", " . fields('url') . ", " . fields('html') . ", " . fields('category') . ", " . fields('icon') . ") values('$data[1]','$data[2]','" .mysql_real_escape_string($data[3]). "','" .mysql_real_escape_string($data[4]). "','" .mysql_real_escape_string($data[5]). "','" .mysql_real_escape_string($data[6]). "','" .mysql_real_escape_string($data[7]). "','$data[8]')";
		$num_records = $num_records + 1;
		mysql_query($import) or die(mysql_error());
	}
	fclose($handle);

	print "Import done: " . $num_records . " records imported";
	
	print "<br /><br /><a href='geo.php'>Click Here To Geocode Addresses (Convert Addresses To Map Co-ordinates)</a></div>";

	//view upload form
}else {

	print "<div id='header'><h1>Import Database (CSV)</h1></div>\n";
	print "<div id='main'>Upload new csv by browsing to file and clicking on Upload<br />\n";

	print "<form enctype='multipart/form-data' action='import.php' method='post'>";

	print "File name to import:<br />\n";

	print "<input size='50' type='file' name='filename'><br />\n";

	print "<input type='submit' name='submit' value='Upload'></form><br />";
	print "<a href='export.php'>Click Here To Download Current Database CSV file</a></div>";

}

?>

</div>
</div>
</body>
</html>