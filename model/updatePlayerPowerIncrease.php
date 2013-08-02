<?php

function updatePlayerPower($playerID, $emotionID){
	
	include("postgresql_dbinfo.php");
	
	/*// Gets data from URL parameters
	$playerID = $_GET['player_id'];
	$emotionID = $_GET['emoid'];*/
	
	// Opens a connection to a postgresql server
	$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";
	
	$connection = pg_connect($conn_string);
	
	if (!$connection) {
	  die('Not connected : ' .pg_get_result($connection));
	}
	else{
	
		$querySelect = 'Select COUNT(number) '.
		   'FROM powers '.
		 'WHERE player_id='.$playerID.' AND emotion_id='.$emotionID .';';
		$resultSelect = pg_query($querySelect);
		
		$status = pg_fetch_assoc($resultSelect);
		
		if ($status['count'] != "0"){
			
			$querySelect = 'Select number '.
		   'FROM powers '.
			'WHERE player_id='.$playerID.' AND emotion_id='.$emotionID .';';
				$resultSelect = pg_query($querySelect);
			
			while($myrow = pg_fetch_assoc($resultSelect)) {
				$newScore = $myrow['number'] + 1;	
			}
				 
			$queryUpdate = 'UPDATE "powers" '.
			   'SET number='.$newScore.
			 ' WHERE player_id='.$playerID.' AND emotion_id='.$emotionID .';';
			$resultUpdate = pg_query($queryUpdate);
			
			if (!$resultUpdate) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("updatePlayerPowerIncrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
		}else{
			$queryInsert = 'INSERT INTO "powers"('.
					'player_id, emotion_id, number)'.
					'VALUES ('.$playerID.', '.$emotionID.', 1);';
			
			$resultInsert = pg_query($queryInsert);
			
			if (!$resultInsert) {
			  die('Invalid query: ' . pg_last_error());
			  //DEBUG
			  error_log("updatePlayerPowerIncrease: ".pg_last_error(), 3 ,"debug_file.log");
			}	
		}
	}
}

?>