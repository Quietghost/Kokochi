<?php

function decreasePlayerPowers($collPlayers){
	include("postgresql_dbinfo.php");
	
	// Opens a connection to a postgresql server
	$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";
		
	$connection = pg_connect($conn_string);
		
	if (!$connection) {
		die('Not connected : ' .pg_get_result($connection));
		//DEBUG
		 error_log("updatePlayerPowerDecrease: ".pg_get_result($connection), 3 ,"debug_file.log");
	}
	else{
		
		//print_r($collPlayers);
		
		for($j = 0, $size = sizeof($collPlayers); $j < $size; ++$j){
			
			$playerID = $collPlayers[$j][0]['player_id'];
			$emotionID = $collPlayers[$j][0]['emotion_id'];
			$number = $collPlayers[$j][0]['count'];
			
			$querySelect = 'Select number '.
			   'FROM powers '.
			 'WHERE player_id='.$playerID.' AND emotion_id='.$emotionID .';';
			$resultSelect = pg_query($querySelect);
			
			if (!$resultSelect) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
			
			$myrow = pg_fetch_assoc($resultSelect);
			$newScore = intval($myrow['number']) - intval($number);	
			// echo "newScore:".$newScore;
			 
			$queryUpdate = 'UPDATE powers '.
			   'SET number='.$newScore.
			 ' WHERE player_id='.$playerID.' AND emotion_id='.$emotionID.';';
			$resultUpdate = pg_query($queryUpdate);
			
			if (!$resultUpdate) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
		}
		return true;
	}
}

function decreasePlayerFlowerPoints($collPlayerArray,$minFlowerPoints){
	include("postgresql_dbinfo.php");
	$localMinFlowerPoints = intval($minFlowerPoints);
	
	// Opens a connection to a postgresql server
	$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";
		
	$connection = pg_connect($conn_string);
		
	if (!$connection) {
		die('Not connected : ' .pg_get_result($connection));
		//DEBUG
		 error_log("updatePlayerPowerDecrease: ".pg_get_result($connection), 3 ,"debug_file.log");
	}
	else{
		for($j = 0, $size = sizeof($collPlayerArray); $j < $size; ++$j){
			
			$playerID = $collPlayerArray[$j];
			
			$querySelect = 'Select flower_points '.
			   'FROM players '.
			 'WHERE id='.$playerID.';';
			$resultSelect = pg_query($querySelect);
			
			if (!$resultSelect) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
			
			$myrow = pg_fetch_assoc($resultSelect);
			$myFlowerPoints = $myrow['flower_points'];
			
			if ($myFlowerPoints >= $localMinFlowerPoints){
				$newScore = $myFlowerPoints - $localMinFlowerPoints;	
				
				$queryUpdate = 'UPDATE players '.
				   'SET flower_points='.$newScore.
				 ' WHERE id='.$playerID.';';
				$resultUpdate = pg_query($queryUpdate);
				
				if (!$resultUpdate) {
					  die('Invalid query: ' . pg_last_error());
					  //DEBUG
					  error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
				}
			}//Player has not enough flower points
			else{
				$newScore = 0;
				$localMinFlowerPoints = $localMinFlowerPoints + ($localMinFlowerPoints - $myFlowerPoints);
				
				$queryUpdate = 'UPDATE players '.
				   'SET flower_points='.$newScore.
				 ' WHERE id='.$playerID.';';
				$resultUpdate = pg_query($queryUpdate);
				
				if (!$resultUpdate) {
					  die('Invalid query: ' . pg_last_error());
					  //DEBUG
					  error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
				}
			}
		}
	}
}

function increasePlayerFlowerPoints($collPlayerArray){
	include("postgresql_dbinfo.php");
	$localMinFlowerPoints = intval($minFlowerPoints);
	
	// Opens a connection to a postgresql server
	$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";
		
	$connection = pg_connect($conn_string);
		
	if (!$connection) {
		die('Not connected : ' .pg_get_result($connection));
		//DEBUG
		 error_log("updatePlayerPowerDecrease: ".pg_get_result($connection), 3 ,"debug_file.log");
	}
	else{
		for($j = 0, $size = sizeof($collPlayerArray); $j < $size; ++$j){
			
			$playerID = $collPlayerArray[$j];
			
			$querySelect = 'Select flower_points '.
			   'FROM players '.
			 'WHERE id='.$playerID.';';
			$resultSelect = pg_query($querySelect);
			
			if (!$resultSelect) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
			
			$myrow = pg_fetch_assoc($resultSelect);
			$myFlowerPoints = $myrow['flower_points'];
			
			$newScore = intval($myFlowerPoints) +10;	
			
			$queryUpdate = 'UPDATE players '.
			   'SET flower_points='.$newScore.
			 ' WHERE id='.$playerID.';';
			$resultUpdate = pg_query($queryUpdate);
			
			if (!$resultUpdate) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
			
			$querySelect = 'Select score '.
		   	'FROM players '.
			'WHERE id='.$playerID.';';
			$resultSelect = pg_query($querySelect);
			
			if (!$resultSelect) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("updatePlayerScore: ".pg_last_error(), 3 ,"debug_file.log");
			}
			
			while($myrow = pg_fetch_assoc($resultSelect)) {
				$newScore = $myrow['score'] + 10;	
			}
				 
			$queryUpdate = 'UPDATE players '.
			   'SET score='.$newScore.
			 ' WHERE id='.$playerID.';';
			 
			$resultUpdate = pg_query($queryUpdate);
			
			if (!$resultUpdate) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("updatePlayerScore: ".pg_last_error(), 3 ,"debug_file.log");
			}
		}
	}
}
?>