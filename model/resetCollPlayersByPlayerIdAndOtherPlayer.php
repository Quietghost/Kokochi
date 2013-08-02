<?php

include("postgresql_dbinfo.php");

// Gets data from URL parameters
$tableName = $_GET['tableName'];
$playerID = $_GET['player_id'];
$otherPlayer = $_GET['other_player'];

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("resetCollPlayersByPlayerID: ".pg_get_result($connection), 3 ,"debug_file.log");
}
	 
$query = 'DELETE FROM "'.$tableName.'" WHERE player_id_you='.$playerID.' AND other_player='.$otherPlayer;
$result = pg_query($query);

if (!$result) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
      error_log("resetCollPlayersByPlayerID: ".pg_last_error(), 3 ,"debug_file.log");
}


?>