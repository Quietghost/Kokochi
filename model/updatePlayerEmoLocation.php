<?php

function updatePlayerEmoLocation($playerID, $emoID, $timestamp, $lat, $lng, $update){

	include("../model/postgresql_dbinfo.php");
	
	/*// Gets data from URL parameters
	$playerID = $_GET['player_id'];
	$emoID = $_GET['emotion_id'];
	$timestamp = $_GET['timestamp'];
	$lat = $_GET["lat"];
	$lng = $_GET["lng"];
	$update = $_GET["update"];*/
	
	$timestampArray = explode('~', $timestamp);
	
	// Opens a connection to a postgresql server
	$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";
	
	$connection = pg_connect($conn_string);
	
	if (!$connection) {
	  die('Not connected : ' .pg_get_result($connection));
	  //DEBUG
	  error_log("updatePlayerEmoLocation: ".pg_get_result($connection), 3 ,"debug_file.log");
	}
	else{
	
		if ($lat !="" && $lng !=""){
			if ($update == "true"){
				$queryUpdate = 'UPDATE players '.
				   "SET coord_last_emotion=ST_GeomFromText('POINT(".$lng." ".$lat.")')".
				 ', "timestamp"=\''.
							$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\', coord_last_emoupdate=ST_GeomFromText(\'POINT('.$lng.' '.$lat.')\') WHERE id='.$playerID.';';
				 
				$resultUpdate = pg_query($queryUpdate);
				
				if (!$resultUpdate) {
					  die('Invalid query: ' . pg_last_error());
					  //DEBUG
					  error_log("updatePlayerEmoLocation: ".pg_last_error(), 3 ,"debug_file.log");
				}
			}else{
				$queryUpdate = 'UPDATE players '.
				   "SET coord_last_emotion=ST_GeomFromText('POINT(".$lng." ".$lat.")')".
				 ', "timestamp"=\''.
							$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\' WHERE id='.$playerID.';';
				 
				$resultUpdate = pg_query($queryUpdate);
				
				if (!$resultUpdate) {
					  die('Invalid query: ' . pg_last_error());
					  //DEBUG
					  error_log("updatePlayerEmoLocation: ".pg_last_error(), 3 ,"debug_file.log");
				}
			}
		}//No location data available
		else{
			$queryUpdate = 'UPDATE players '.
			   "SET ".
			 '"timestamp"=\''.
						$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\' WHERE id='.$playerID.';';
			 
			$resultUpdate = pg_query($queryUpdate);
			
			if (!$resultUpdate) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("updatePlayerEmoLocation: ".pg_last_error(), 3 ,"debug_file.log");
			}
		}
	}
}


?>