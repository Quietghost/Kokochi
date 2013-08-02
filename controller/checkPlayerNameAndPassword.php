<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");

$myName = $_GET["name"];
$myPassword = $_GET["password"];

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("checkResult");
$parnode = $dom->appendChild($node); 

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("checkPlayerNameAndPassword: ".pg_get_result($connection), 3 ,"debug_file.log");
}

// Select all the rows in the markers table
$query = 'SELECT COUNT(id) FROM players WHERE "name"=\''.$myName.'\' AND "password"=\''.$myPassword.'\';';

$result = pg_query($connection, $query);

if (!$result) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
  	  error_log("checkPlayerNameAndPassword: ".pg_last_error(), 3 ,"debug_file.log");
}else{
	$status = pg_fetch_assoc($result);

	if ($status['count'] == "0") {
		// ADD TO XML DOCUMENT NODE
		$node = $dom->createElement("status");  
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("status", "false");
	}else{
		
		$query = 'SELECT id FROM players WHERE "name"=\''.$myName.'\' AND "password"=\''.$myPassword.'\';';

		$result = pg_query($connection, $query);
		
		while ($row = pg_fetch_row($result)){
			// ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("status");  
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("status", "ok");
			$newnode->setAttribute("id", $row[0]);
		}
	}
}

echo $dom->saveXML();

?>