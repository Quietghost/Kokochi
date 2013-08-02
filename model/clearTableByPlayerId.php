<?php

include("postgresql_dbinfo.php");

// Gets data from URL parameters
$tableName = $_GET['tableName'];
$playerId = $_GET['player_id'];

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("clearTable: ".pg_get_result($connection), 3 ,"debug_file.log");
}
	 
$query = 'DELETE FROM "'.$tableName.'" WHERE player_id='.$playerId;
$result = pg_query($query);

if (!$result) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
  	  error_log("clearTable: ".pg_last_error(), 3 ,"debug_file.log");
}


?>