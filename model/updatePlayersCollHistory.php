<?php

include("postgresql_dbinfo.php");

// Gets data from URL parameters
$collPlayers = $_GET['coll_players'];
$emotionString = $_GET['emotion_string'];
$emotionNumberString = $_GET['emotion_number_string'];
$timestamp = $_GET['timestamp'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];

$timestampArray = explode('~', $timestamp);

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("updatePlayersCollHistory: ".pg_get_result($connection), 3 ,"debug_file.log");
}

$query = 'SELECT COUNT(id) FROM "players_coll_history" WHERE coll_players=\''.$collPlayers.'\' AND required_emotions=\''.
				$emotionString.'\' AND number_of_required_emotions=\''.$emotionNumberString.'\' AND "timestamp"=\''.$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\';';

$result = pg_query($query);

$statusSelect = pg_fetch_assoc($result);

if ($statusSelect['count'] == "0") {
	if ($lat !="" && $lng !=""){
		// Insert new row with user data
		$query = 'INSERT INTO "players_coll_history" ('.
					' "timestamp", coll_players, required_emotions, number_of_required_emotions, "location")'.
					' VALUES (TIMESTAMP WITH TIME ZONE \''.
					$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\',  \''.$collPlayers.'\', \''.$emotionString.'\', \''.$emotionNumberString.'\', ST_GeomFromText(\'POINT('.$lng.' '.$lat.')\'));';
					
		$result = pg_query($query);
		
		if (!$result) {
		  error_log("updatePlayersCollHistory: ".pg_last_error(), 3 ,"debug_file.log");
		  die('Invalid query: ' . pg_last_error());
		}
	}else{
		// Insert new row with user data
		$query = 'INSERT INTO "players_coll_history" ('.
					' "timestamp", coll_players, required_emotions, number_of_required_emotions)'.
					' VALUES (TIMESTAMP WITH TIME ZONE \''.
					$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\',  \''.$collPlayers.'\', \''.$emotionString.'\', \''.$emotionNumberString.'\');';
					
		$result = pg_query($query);
		
		if (!$result) {
		  error_log("updatePlayersCollHistory: ".pg_last_error(), 3 ,"debug_file.log");
		  die('Invalid query: ' . pg_last_error());
		}
	}
}
?>