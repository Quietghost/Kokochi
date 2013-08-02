<?php

include("../model/postgresql_dbinfo.php");
include("../controller/getTimeoutStatus.php");

// Gets data from URL parameters
$playerID = $_GET['player_id'];
$requiredTimestamp = $_GET['timestamp'];

$timestampArray = explode('~', $requiredTimestamp);
$timestampString = $timestampArray[0].' '.$timestampArray[1];
$tempCurrentPlayerTime = strtotime($timestampString);

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
}
else{
	$queryUpdate = 'UPDATE players '.
	'SET "last_login"=\''.$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\''.
	' WHERE id='.$playerID.';';
		 
	$resultUpdate = pg_query($queryUpdate);
			
	if (!$resultUpdate) {
		die('Invalid query: ' . pg_last_error());
		//DEBUG
		error_log("updatePlayerScore: ".pg_last_error(), 3 ,"debug_file.log");
	}
	
	$queryLastHug = 'SELECT last_hug FROM players WHERE id='.$playerID.';';

	$resultLastHug = pg_query($connection, $queryLastHug);
	
	if (!$resultLastHug) {
	  //DEBUG
	  error_log("getTimeoutstatus: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}else{
		
		$rowLastHug = pg_fetch_assoc($resultLastHug);
		
		if ($rowLastHug["last_hug"] != ""){
			$oldTimestampArray = explode('+', $rowLastHug["last_hug"]);
			$oldPlayerTime = strtotime($oldTimestampArray[0]);
			
			if (($tempCurrentPlayerTime-$oldPlayerTime) >= 86400){
				$queryHugBaseUpdate = 'UPDATE players '.
					   'SET current_hug_base=10'.
					 ' WHERE id='.$playerID.';';
				$resultHugBaseUpdate = pg_query($queryHugBaseUpdate);
						
				if (!$resultHugBaseUpdate) {
					die('Invalid query: ' . pg_last_error());
					//DEBUG
					error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
				}
			}
		}
	}
	
	$timeOut = getTimeoutStatus($playerID, $requiredTimestamp);
		
	if ($timeOut == "true"){
		
		$queryEmoCounter = 'UPDATE players
   			SET emocounter=3
 			WHERE id='.$playerID.';';
		
		$resultEmoCounter = pg_query($connection, $queryEmoCounter);
	
		if (!$resultEmoCounter) {
		  //DEBUG
		  error_log("getTimeoutstatus: ".pg_last_error(), 3 ,"debug_file.log");
		  exit;
		}else{}
	}
}

?>