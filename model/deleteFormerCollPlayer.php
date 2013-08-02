<?php

include("postgresql_dbinfo.php");

// Gets data from URL parameters
$playerID = $_GET['player_id'];
$collPlayer = $_GET['collPlayer_id'];

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("deleteFormerCollPlayer: ".pg_get_result($connection), 3 ,"debug_file.log");
}

$querySelect = 'DELETE FROM "collPlayers"
 WHERE player_id_you = '.$playerID.' AND NOT (player_id_you = '.$playerID.' AND other_player = '.$collPlayer.');';
   
$resultSelect = pg_query($querySelect);

if (!$resultSelect) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
  	  error_log("deleteFormerCollPlayer: ".pg_last_error(), 3 ,"debug_file.log");
}

?>