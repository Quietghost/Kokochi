<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");

$playerMe = $_GET['playerID'];

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("collPlayersCount");
$parnode = $dom->appendChild($node); 

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("getCollCurrentCount: ".pg_get_result($connection), 3 ,"debug_file.log");
}

// Select all the rows in the markers table
$query = 'SELECT * FROM "player_'.$playerMe.'_collCurrent"';

$result = pg_query($connection, $query);
//error_log("getCollPlayers result: ".$result);

if (!$result) {
  echo "An error occured.\n". pg_last_error();
  //DEBUG
  error_log("getCollCurrentCount: ".pg_last_error(), 3 ,"debug_file.log");
  exit;
}

// Iterate through the rows, printing XML nodes for each
while ($row = pg_fetch_row($result)){
	
 // ADD TO XML DOCUMENT NODE
  $node = $dom->createElement("player");  
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id", $row[0]);

}

echo $dom->saveXML();

?>