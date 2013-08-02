<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");
include("../controller/getTimeoutStatus.php");
include("../model/updatePlayerPowerIncrease.php");
include("../model/updatePlayerLastEmotionStatement.php");
include("../model/updateEmotionStatementHistory.php");
include("../model/updatePlayerEmoLocation.php");

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("emotionstatement");
$parnode = $dom->appendChild($node); 

$playerID = $_GET["player_id"];
$emoID = $_GET["emotion_id"];
$timestamp = $_GET['timestamp'];
$lat = $_GET["lat"];
$lng = $_GET["lng"];

$lastEmotionId = "";
$emocounter = 0;

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("getLastEmotionStatementByPlayer: ".pg_get_result($connection), 3 ,"debug_file.log");
}else{

	// Select all the rows in the markers table
	$query = 'SELECT last_emotion_id, emocounter FROM "players" WHERE id='.$playerID;
	
	$result = pg_query($connection, $query);
	
	if (!$result) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getLastEmotionStatementByPlayer: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}
	
	// Iterate through the rows, printing XML nodes for each
	$row = pg_fetch_assoc($result);
	
	$emocounter = $row['emocounter'];
	$lastEmotionId = $row['last_emotion_id'];
	
	$timeOut = getTimeoutStatus($playerID, $timestamp);
		
	if ($timeOut == "true"){
	
		updatePlayerPower($playerID, $emoID);
		
		$querySelect = 'Select flower_points '.
		   'FROM players '.
			'WHERE id='.$playerID.';';
		$resultSelect = pg_query($querySelect);
			
		$myrow = pg_fetch_assoc($resultSelect);
		$newScore = $myrow['flower_points'] + 2;	
		
		$queryUpdate = 'UPDATE players
							SET flower_points='.$newScore.'
						WHERE id='.$playerID.';';
		$resultUpdate = pg_query($queryUpdate);
				
		if (!$resultUpdate) {
			die('Invalid query: ' . pg_last_error());
			//DEBUG
			error_log("updatePlayerPowerIncrease: ".pg_last_error(), 3 ,"debug_file.log");
		}
			
		$emocounter = 2;
			
		updatePlayerLastEmotionStatement($playerID, $emoID, $emocounter);
		updateEmotionStatementHistory($playerID, $emoID, $timestamp, $lat, $lng);
		updatePlayerEmoLocation($playerID, $emoID, $timestamp, $lat, $lng, "true");
			
		// ADD TO XML DOCUMENT NODE
		$node = $dom->createElement("emotionstatementresult");  
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("result", "new");
		$newnode->setAttribute("emocounter", $emocounter);
		$newnode->setAttribute("lastemotionid", $lastEmotionId);
			
	}//Time out intervall is not over yet
	else{
		//if ($row['last_emotion_id'] != $emoID){
		if ($row['emocounter'] > 0){
			updatePlayerPower($playerID, $emoID);
		
			$emocounter = intval($row['emocounter']) - 1;
			
			$querySelect = 'Select flower_points '.
			   'FROM players '.
				'WHERE id='.$playerID.';';
			$resultSelect = pg_query($querySelect);
				
			$myrow = pg_fetch_assoc($resultSelect);
			$newScore = $myrow['flower_points'] + 2;	
			
			$queryUpdate = 'UPDATE players
								SET flower_points='.$newScore.'
							WHERE id='.$playerID.';';
			$resultUpdate = pg_query($queryUpdate);
					
			if (!$resultUpdate) {
				die('Invalid query: ' . pg_last_error());
				//DEBUG
				error_log("updatePlayerPowerIncrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
			
			updatePlayerLastEmotionStatement($playerID, $emoID, $emocounter);
			updateEmotionStatementHistory($playerID, $emoID, $timestamp, $lat, $lng);
			updatePlayerEmoLocation($playerID, $emoID, $timestamp, $lat, $lng, "true");
			
			// ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("emotionstatementresult");  
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("result", "stated");
			$newnode->setAttribute("emocounter", $emocounter);
			$newnode->setAttribute("lastemotionid", $emoID);
		}//Time out intervall is not over, but the emocounter is already full
		else{
			// ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("emotionstatementresult");  
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("result", "counter_full");
			$newnode->setAttribute("emocounter", $emocounter);
			$newnode->setAttribute("lastemotionid", $lastEmotionId);
		}
		/*}//The same emotion stated (shoudl never happen as previously used emotion buttons are normally disabled)
		else{
			// ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("emotionstatementresult");  
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("result", "same");
			$newnode->setAttribute("emocounter", $emocounter);
			$newnode->setAttribute("lastemotionid", $lastEmotionId);
		}*/
	}
}

echo $dom->saveXML();

?>