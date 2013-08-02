<?php

include("postgresql_dbinfo.php");

// Gets data from URL parameters
$playerMe = $_GET['playerID'];

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("createCollCurrentTable: ".pg_get_result($connection), 3 ,"debug_file.log");
}

$queryTable = 'SELECT COUNT(*) FROM "player_'.$playerMe.'_collCurrent"';
$resultTable = pg_query($queryTable);

if (!$resultTable) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
      error_log("createCollCurrentTable: ".pg_last_error(), 3 ,"debug_file.log");
}else{
	$status = pg_fetch_assoc($resultTable);
}

if ($status['count'] == "0") {
	$createTable = 'create table "player_'.$collPlayer.'_collCurrent"'.
        '('.
            'player_id bigint not null,'.
            'ready int'.
        ');';
	$tableCreated = pg_query($createTable);
}else{
}

?>