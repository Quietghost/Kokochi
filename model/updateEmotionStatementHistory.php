<?php

function updateEmotionStatementHistory($playerID, $emotion_id, $timestamp, $lat, $lng){

	include("postgresql_dbinfo.php");
	
	$timestampArray = explode('~', $timestamp);
	
	// Opens a connection to a postgresql server
	$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";
	
	$connection = pg_connect($conn_string);
	
	if (!$connection) {
	  die('Not connected : ' .pg_get_result($connection));
	  //DEBUG
	  error_log("updateEmotionStatementHistory: ".pg_get_result($connection), 3 ,"debug_file.log");
	}
	else {
	
		// Insert new row with user data
		if ($lat !="" &&  $lng !=""){
		$query = 'INSERT INTO "players_emo_statements_history" ('.
						' emotion_id, "timestamp", player_id, "location")'.
						' VALUES ('.$emotion_id.', \''.
						$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\', '.$playerID.','. 
						'ST_GeomFromText(\'POINT('.$lng.' '.$lat.')\'));';
						
			$result = pg_query($query);
			
			if (!$result) {
			  die('Invalid query: '.pg_last_error());
			  //DEBUG
			  error_log("updateEmotionStatementHistory: ".pg_last_error(), 3 ,"debug_file.log");
			}
		}
		else{
			$query = 'INSERT INTO "players_emo_statements_history" ('.
						' emotion_id, "timestamp", player_id)'.
						' VALUES ('.$emotion_id.', \''.
						$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\', '.$playerID.');';
						
			$result = pg_query($query);
			
			if (!$result) {
			  die('Invalid query: '.pg_last_error());
			  //DEBUG
			  error_log("updateEmotionStatementHistory: ".pg_last_error(), 3 ,"debug_file.log");
			}
		}
	}
}

?>