<?php

include("postgresql_dbinfo.php");

// Gets data from URL parameters
$playerMe = $_GET['playerID'];
$collPlayer = $_GET['collPlayer'];

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("setCollPlayer: ".pg_get_result($connection), 3 ,"debug_file.log");
}

$querySelect = 'SELECT COUNT(*) FROM "collPlayers" WHERE "other_player"='.$collPlayer.' AND "player_id_you"='.$playerMe;
$resultSelect = pg_query($querySelect);

if (!$resultSelect) {
	  die('Invalid query: ' . pg_last_error());
}else{
	$status = pg_fetch_assoc($resultSelect);
}

if ($status['count'] == "0"){
	// Insert new row with user data
	$query = 'INSERT INTO "collPlayers"('.
				'player_id_you, other_player)'.
				'VALUES ('.$playerMe.', '.$collPlayer.');';
				
	$result = pg_query($query);

	if (!$result) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
      error_log("setCollPlayer: ".pg_last_error(), 3 ,"debug_file.log");
	}
}

?>
