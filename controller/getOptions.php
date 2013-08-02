<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("options");
$parnode = $dom->appendChild($node); 

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("getOptions: ".pg_get_result($connection), 3 ,"debug_file.log");
}

// Select all the rows in the markers table
$query = 'SELECT * FROM "options"';

$result = pg_query($connection, $query);

if (!$result) {
  echo "An error occured.\n". pg_last_error();
  //DEBUG
  error_log("getOptions: ".pg_last_error(), 3 ,"debug_file.log");
  exit;
}

// Iterate through the rows, printing XML nodes for each
while ($row = pg_fetch_row($result)){
	
 // ADD TO XML DOCUMENT NODE
  $node = $dom->createElement("option");  
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("max_required_emotions", $row[1]);
  $newnode->setAttribute("max_global_score", $row[2]);
  $newnode->setAttribute("image_folder", $row[3]);
  $newnode->setAttribute("emo_statement_buffer", $row[4]);
  $newnode->setAttribute("emo_timeout", $row[5]);
  $newnode->setAttribute("emo_repeat", $row[6]);
  $newnode->setAttribute("refresh_rate", $row[7]);
  $newnode->setAttribute("language", $row[8]);
}

// Select all the rows in the markers table
$query = 'SELECT score FROM "globalscore"';

$result = pg_query($connection, $query);

if (!$result) {
  echo "An error occured.\n". pg_last_error();
  //DEBUG
  error_log("getOptions: ".pg_last_error(), 3 ,"debug_file.log");
  exit;
}

$row = pg_fetch_assoc($result);

$newnode->setAttribute("current_score", $row['score']);

$query = 'SELECT count(id) FROM players_coll_history;';

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
	$newnode->setAttribute("totalcollcount", $row['count']);
}

echo $dom->saveXML();

?>