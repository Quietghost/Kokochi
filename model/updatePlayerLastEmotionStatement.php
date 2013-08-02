<?php

function updatePlayerLastEmotionStatement($playerID, $emoID, $emoCounter){
	include("../model/postgresql_dbinfo.php");
	
	/*// Gets data from URL parameters
	$playerID = $_GET['player_id'];
	$emoID = $_GET['emotion_id'];
	$emoCounter = $_GET['emocounter'];*/
	
	// Opens a connection to a postgresql server
	$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";
	
	$connection = pg_connect($conn_string);
	
	if (!$connection) {
	  die('Not connected : ' .pg_get_result($connection));
	  //DEBUG
	  error_log("updatePlayerLastEmotionStatement: ".pg_get_result($connection), 3 ,"debug_file.log");
	}
	else{
		$queryUpdate = 'UPDATE players '.
		   "SET last_emotion_id=".$emoID.", emocounter=".$emoCounter.
		 ' WHERE id='.$playerID.';';
		 
		$resultUpdate = pg_query($queryUpdate);
		
		if (!$resultUpdate) {
			  die('Invalid query: ' . pg_last_error());
			  //DEBUG
			  error_log("updatePlayerLastEmotionStatement: ".pg_last_error(), 3 ,"debug_file.log");
		}
	}
}

?>