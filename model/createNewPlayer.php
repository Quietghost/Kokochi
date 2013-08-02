<?php header("Content-Type: text/xml; charset=UTF-8");

include("postgresql_dbinfo.php");

// Gets data from URL parameters
$playerName = $_GET['player_name'];
$playerPassword = $_GET['player_password'];
$newID;

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("newPlayers");
$parnode = $dom->appendChild($node); 


// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);
//DEBUG
error_log("createNewPlayer: ".$connection, 3 ,"debug_file.log");

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("createNewPlayer: ".pg_get_result($connection), 3 ,"debug_file.log");
}
	 
$query = 'INSERT INTO "players"('.
				'"name", "password", flower_points, pic, last_emotion_id, emocounter, hugs, current_hug_base)'.
				'VALUES (\''.$playerName.'\', \''.$playerPassword.'\', 0, \'DefaultPlayerIcon.png\', 0, 3, 0, 10);';
				
$result = pg_query($query);

if (!$result) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
	  error_log("createNewPlayer: ".pg_last_error(), 3 ,"debug_file.log");
}else{
}

$querySelect = 'SELECT id FROM "players" '.
				'WHERE "name"=\''.$playerName.'\' AND "password"=\''.$playerPassword.'\';';
				
$resultSelect = pg_query($querySelect);

if (!$resultSelect) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
	  error_log("createNewPlayer: ".pg_last_error(), 3 ,"debug_file.log");
}else{
}

// Iterate through the rows, printing XML nodes for each
while ($row = pg_fetch_row($resultSelect)){
	
  // ADD TO XML DOCUMENT NODE
  $node = $dom->createElement("newPlayer");  
  $newnode = $parnode->appendChild($node); 
  $newnode->setAttribute("id", $row[0]);
  
  $newID = $row[0];
  
}

$createTable = 'CREATE TABLE "player_'.$newID.'_collCurrent"'.
				'('.
				  'player_id bigint NOT NULL,'.
				  'ready integer'.
				');';

$tableCreated = pg_query($createTable);
	
if (!$tableCreated) {
  die('Invalid query: ' . pg_last_error());
  //DEBUG
  error_log("createNewPlayer: ".pg_last_error(), 3 ,"debug_file.log");
}


echo $dom->saveXML();

?>