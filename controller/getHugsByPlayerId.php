<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");

$playerID = $_GET["player_id"];

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("players");
$parnode = $dom->appendChild($node); 

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("getHugsByPlayerId: ".pg_get_result($connection), 3 ,"debug_file.log");
}

// Select all the rows in the markers table
$query = 'SELECT hugs FROM "players" WHERE id='.$playerID;

$result = pg_query($connection, $query);

if (!$result) {
  echo "An error occured.\n". pg_last_error();
  //DEBUG
  error_log("getHugsByPlayerId: ".pg_last_error(), 3 ,"debug_file.log");
  exit;
}

// Iterate through the rows, printing XML nodes for each
while ($row = pg_fetch_row($result)){
	
 // ADD TO XML DOCUMENT NODE
  $node = $dom->createElement("player");  
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("hugs", $row[0]);

}

echo $dom->saveXML();

?>