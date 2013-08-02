<?php

function getFlowerPointsForCollPlayers($collPlayerIdStringSQL){
	include("../model/postgresql_dbinfo.php");
	$sumFlowerPoints = -1;
	
	// Opens a connection to a postgresql server
	$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";
	
	$connection = pg_connect($conn_string);
	
	if (!$connection) {
	  die('Not connected : ' .pg_get_result($connection));
	  //DEBUG
	  error_log("getHugsByPlayerId: ".pg_get_result($connection), 3 ,"debug_file.log");
	}
	else{
		// Select all the rows in the markers table
		$query = 'SELECT sum(flower_points) FROM "players" WHERE id IN ('.$collPlayerIdStringSQL.');';
		
		$result = pg_query($connection, $query);
		
		if (!$result) {
		  echo "An error occured.\n". pg_last_error();
		  //DEBUG
		  error_log("getHugsByPlayerId: ".pg_last_error(), 3 ,"debug_file.log");
		  exit;
		}
		else{
			$sumFlowerPointsTemp = pg_fetch_assoc($result);
			$sumFlowerPoints = $sumFlowerPointsTemp['sum'];
		}
	}
	return $sumFlowerPoints;
}

?>