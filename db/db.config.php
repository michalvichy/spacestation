<?
function db($variable) {
	$db = array(
// Database Config
			'hostname' => 'localhost', 
			'username' => 'poi_user',
			'password' => 'password',
			'dbname' => 'poi_db',
			'tablename' => 'poi'
);
	return $db[$variable];
}

function fields($variable) {
	$fields = array(
// Field Mappings
			'id' 	=> 'id',
			'latitude' 	=> 'lat',
			'longitude' 	=> 'lng',
			'title' 	=> 'title',
			'address' 	=> 'address',
			'url' 	=> 'url',
			'html' 	=> 'html',
			'category' 	=> 'category',
			'icon' 	=> 'marker'
);
	return $fields[$variable];
}
?>