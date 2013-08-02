<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("globalscore");
$parnode = $dom->appendChild($node); 

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("getGlobalScore: ".pg_get_result($connection), 3 ,"debug_file.log");
}

$query = 'Select score from globalscore';

$result = pg_query($connection, $query);

if (!$result) {
  echo "An error occured.\n". pg_last_error();
  //DEBUG
  error_log("getGlobalScore: ".pg_last_error(), 3 ,"debug_file.log");
  exit;
}
else{
// Iterate through the rows, printing XML nodes for each
	$row = pg_fetch_assoc($result);
	
  	// ADD TO XML DOCUMENT NODE
	$node = $dom->createElement("score");  
	$newnode = $parnode->appendChild($node);
	$newnode->setAttribute("value", $row['score']);
}


echo $dom->saveXML();

?>