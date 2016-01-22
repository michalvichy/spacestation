<?php
require_once("db.config.php");

$conn = mysql_connect( db('hostname'), db('username'), db('password') ) or die( mysql_error( ) );
mysql_select_db( db('dbname'), $conn ) or die( mysql_error( $conn ) );

$result = mysql_query( 'SELECT * FROM ' .db('tablename'), $conn ) or die( mysql_error( $conn ) );

header( 'Content-Type: text/csv' ); // tell the browser to treat file as CSV
header( 'Content-Disposition: attachment;filename=poi-database.csv' ); // tell browser to download a file in user's system with name export.csv

$row = mysql_fetch_assoc( $result ); // Get the column names
if ( $row )
{
	//outputcsv( array_keys( $row ) ); // It wil pass column names to outputcsv function
}

while ( $row )
{
	outputcsv( $row );    // loop is used to fetch all the rows from <span id="IL_AD5" class="IL_AD">table</span> and pass them to outputcsv func
	$row = mysql_fetch_assoc( $result );
}
function outputcsv( $fields )
{
	$separator = '';
	foreach ( $fields as $field )
	{
		echo $separator . '"' . $field . '"';
		$separator = ',';        // Separate values with a comma
	}
	echo "\r\n";     //Give a carriage return and new line space after each record
}
?>