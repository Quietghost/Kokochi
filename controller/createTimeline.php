<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");
include("../util/sortMultiArray.php");
include('../util/phayes-geoPHP-2da9b06/geoPHP.inc');

$emoTable = $_GET["emo_table"];

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("allHistoryEntries");
$parnode = $dom->appendChild($node); 

$allHistory = array();

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("getCollPlayersHistory: ".pg_get_result($connection), 3 ,"debug_file.log");
}
else{

	$queryCollHistory = 'SELECT timestamp, coll_players, ST_AsText(location) as location FROM players_coll_history ORDER BY timestamp DESC';
	
	$resultCollHistory = pg_query($connection, $queryCollHistory);
	
	if (!$resultCollHistory) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getCollPlayersHistory: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}
	
	// Iterate through the rows, printing XML nodes for each
	while ($rowCollHistory = pg_fetch_row($resultCollHistory)){
		$lng = "0";
		$lat = "0";
		
		$timeCollHistoryArray = explode("+",$rowCollHistory[0]);
		$timeCollHistoryTemp = explode(" ",$timeCollHistoryArray[0]);
		$timeCollHistoryString = $timeCollHistoryTemp[1]." (".$timeCollHistoryTemp[0].")";
		
		$playersCollHistoryArray = explode("_",$rowCollHistory[1]);
		$playersCollHistoryString = "";
		
		if ($rowCollHistory[2] != ""){
			$latestGeom = $rowCollHistory[2];
			$latestLocation = geoPHP::load($latestGeom,'wkt');
			
			$lng = $latestLocation->getX();
			$lat = $latestLocation->getY();
		}
		else{
			$lng = "0";
			$lat = "0";
		}
		
		for($i = 0, $size = sizeof($playersCollHistoryArray); $i < $size; ++$i){
			$queryPlayerName = 'SELECT name FROM players WHERE id='.$playersCollHistoryArray[$i];
			
			$resultPlayerName= pg_query($connection, $queryPlayerName);
		
			if (!$resultPlayerName) {
			  echo "An error occured.\n". pg_last_error();
			  //DEBUG
			  error_log("getCollPlayersHistory: ".pg_last_error(), 3 ,"debug_file.log");
			  exit;
			}
			
			$myrowPlayerName = pg_fetch_assoc($resultPlayerName);
			
			if ($i==($size-1)){
				$playersCollHistoryString .= " and ".$myrowPlayerName['name'];
			}
			else{
				if ($i==0){
					$playersCollHistoryString .= $myrowPlayerName['name'];
				}
				else{
					$playersCollHistoryString .= ", ".$myrowPlayerName['name'];
				}
			}
		}
		
		$allHistory[] = array("id"=>"coll","timestamp"=>$rowCollHistory[0],"collplayers"=>$playersCollHistoryString,"lng"=>$lng,"lat"=>$lat);
	}
	
	$queryEmoHistory = 'SELECT playername, emoname, emotimstamp, emolocation FROM (SELECT A1.name as playername, 
					A2.timestamp as emotimstamp, A3.name as emoname, A2.emotion_id as emoid, ST_AsText(location) as emolocation
					FROM players_emo_statements_history A2, players A1, '.$emoTable.' A3
					WHERE A1.id = A2.player_id AND A2.emotion_id = A3.id ORDER BY emotimstamp DESC) as Temp;';
		
	$resultEmoHistory = pg_query($connection, $queryEmoHistory);
		
	if (!$resultEmoHistory) {
		 echo "An error occured.\n". pg_last_error();
		 //DEBUG
		 error_log("getCollPlayersHistory: ".pg_last_error(), 3 ,"debug_file.log");
		 exit;
	}
		
	while ($rowEmoHistory = pg_fetch_row($resultEmoHistory)){
		if ($rowEmoHistory[3] != ""){
			$latestGeom = $rowEmoHistory[3];
			$latestLocation = geoPHP::load($latestGeom,'wkt');
			
			$lng = $latestLocation->getX();
			$lat = $latestLocation->getY();
			
			$allHistory[] = array("id"=>"emo","player"=>$rowEmoHistory[0],"emotion"=>$rowEmoHistory[1],"timestamp"=>$rowEmoHistory[2],"lng"=>$lng,"lat"=>$lat);
		}
		else{
			$allHistory[] = array("id"=>"emo","player"=>$rowEmoHistory[0],"emotion"=>$rowEmoHistory[1],"timestamp"=>$rowEmoHistory[2],"lng"=>"0","lat"=>"0");	
		}
	}
	
	$queryHugHistory = 'SELECT sender, A2.name as reciever, time, location FROM (
				SELECT A2.name as sender, A1.reciever as reciever, A1.timestamp as time, ST_AsText(location) as location FROM players A2, players_hug_history A1 WHERE A1.sender = A2.id) 
				as temp, players A2 WHERE temp.reciever = A2.id ORDER BY time DESC;';
		
	$resultHugHistory = pg_query($connection, $queryHugHistory);
		
	if (!$resultHugHistory) {
		 echo "An error occured.\n". pg_last_error();
		 //DEBUG
		 error_log("getCollPlayersHistory: ".pg_last_error(), 3 ,"debug_file.log");
		 exit;
	}
		
	while ($rowHugHistory = pg_fetch_row($resultHugHistory)){
		
		if ($rowHugHistory[3] != ""){
			$latestGeom = $rowHugHistory[3];
			$latestLocation = geoPHP::load($latestGeom,'wkt');
			
			$lng = $latestLocation->getX();
			$lat = $latestLocation->getY();
			
			$allHistory[] = array("id"=>"hug","sender"=>$rowHugHistory[0],"reciever"=>$rowHugHistory[1],"timestamp"=>$rowHugHistory[2],"lng"=>$lng,"lat"=>$lat);
		}else{
			$allHistory[] = array("id"=>"hug","sender"=>$rowHugHistory[0],"reciever"=>$rowHugHistory[1],"timestamp"=>$rowHugHistory[2],"lng"=>"0","lat"=>"0");
		}
		
	}
	
	$allHistorySorted = array_sort($allHistory,'timestamp', SORT_DESC);
	$allHistorySortedZero = array_values($allHistorySorted);
	//print_r($allHistorySortedZero);
}

$emocount = 0;
$collcount = 0;
$hugcount = 0;

for($i = 0, $size = sizeof($allHistorySortedZero); $i < $size; ++$i){
	
	switch($allHistorySortedZero[$i]["id"]){
	
	case "emo":
	if ($emocount < 10){
		$timeEmoHistoryArray = explode("+",$allHistorySortedZero[$i]["timestamp"]);
		$timeEmoHistoryTemp = explode(" ",$timeEmoHistoryArray[0]);
		$timeEmoHistoryString = $timeEmoHistoryTemp[1]." (".$timeEmoHistoryTemp[0].")";
		
		// ADD TO XML DOCUMENT NODE
	  	$node = $dom->createElement("allHistory");  
	  	$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("id", "emo");
	  	$newnode->setAttribute("player", $allHistorySortedZero[$i]["player"]);
	  	$newnode->setAttribute("emotion",$allHistorySortedZero[$i]["emotion"]);
		$newnode->setAttribute("timestamp", $timeEmoHistoryString);
		$newnode->setAttribute("lng", $allHistorySortedZero[$i]["lng"]);
		$newnode->setAttribute("lat", $allHistorySortedZero[$i]["lat"]);
		$emocount++;
	}
		break;
	case "coll":
	if ($collcount < 10){
		$timeCollHistoryArray = explode("+",$allHistorySortedZero[$i]["timestamp"]);
		$timeCollHistoryTemp = explode(" ",$timeCollHistoryArray[0]);
		$timeCollHistoryString = $timeCollHistoryTemp[1]." (".$timeCollHistoryTemp[0].")";
		
		// ADD TO XML DOCUMENT NODE
	  	$node = $dom->createElement("allHistory");  
	  	$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("id", "coll");
	  	$newnode->setAttribute("collplayers", $allHistorySortedZero[$i]["collplayers"]);
		$newnode->setAttribute("timestamp", $timeCollHistoryString);
		$newnode->setAttribute("lng", $allHistorySortedZero[$i]["lng"]);
		$newnode->setAttribute("lat", $allHistorySortedZero[$i]["lat"]);
		$collcount++;
	}
		break;
	case "hug":
	if ($hugcount < 10){
		$timeHugHistoryArray = explode("+",$allHistorySortedZero[$i]["timestamp"]);
		$timeHugHistoryTemp = explode(" ",$timeHugHistoryArray[0]);
		$timeHugHistoryString = $timeHugHistoryTemp[1]." (".$timeHugHistoryTemp[0].")";
		
		// ADD TO XML DOCUMENT NODE
		$node = $dom->createElement("allHistory");  
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("id", "hug");
		$newnode->setAttribute("sender", $allHistorySortedZero[$i]["sender"]);
		$newnode->setAttribute("reciever", $allHistorySortedZero[$i]["reciever"]);
		$newnode->setAttribute("timestamp", $timeHugHistoryString);
		$newnode->setAttribute("lng", $allHistorySortedZero[$i]["lng"]);
		$newnode->setAttribute("lat", $allHistorySortedZero[$i]["lat"]);
		$hugcount++;
	}
		break;
	}
}

echo $dom->saveXML();

?>