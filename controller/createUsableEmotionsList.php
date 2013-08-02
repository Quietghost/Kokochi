<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");
include("../util/sortMultiArray.php");
include("../controller/getTimeoutStatus.php");

$playerID = $_GET["player_id"];
$emoTable = $_GET["emo_table"];
$timestamp = $_GET["timestamp"];

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("PlayerProfiles");
$parnode = $dom->appendChild($node); 

$allPlayers = array();

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("getLastEmotionStatementByPlayer: ".pg_get_result($connection), 3 ,"debug_file.log");
}
else{
	
	
	// Select the last emotion id to disable the button in the state your emotion table
	$queryLastEmotion = 'SELECT last_emotion_id, emocounter FROM "players" WHERE id='.$playerID;
	
	$resultLastEmotion = pg_query($connection, $queryLastEmotion);
	
	if (!$resultLastEmotion) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getLastEmotionStatementByPlayer: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}
	else{
	
	// Iterate through the rows, printing XML nodes for each
		$rowLastEmotion = pg_fetch_assoc($resultLastEmotion);
		
		if ($rowLastEmotion['last_emotion_id'] != "0"){
			
			// Select all the rows in the markers table
			$query = 'SELECT name FROM '.$emoTable.' WHERE id='.intval($rowLastEmotion['last_emotion_id']).';';
			
			$result = pg_query($connection, $query);
			
			if (!$result) {
			  echo "An error occured.\n". pg_last_error();
			  //DEBUG
			  error_log("getEmotionsByPlayerID: ".pg_last_error(), 3 ,"debug_file.log");
			  exit;
			}
			
			$rowLastEmotionName = pg_fetch_assoc($result);
			
			//Check if the timeout is over and if yes don´t disable the players last emotion
			$timeout = getTimeoutStatus($playerID, $timestamp);
			
			if ($timeout == "false"){
				// ADD TO XML DOCUMENT NODE
				$node = $dom->createElement("playerprofile");  
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("playerid", $playerID);
				$newnode->setAttribute("lastemoid", $rowLastEmotion['last_emotion_id']);
				$newnode->setAttribute("emocounter", $rowLastEmotion['emocounter']);
				$newnode->setAttribute("lastemoname", $rowLastEmotionName['name']);
			}//Timeout over
			else{
				// ADD TO XML DOCUMENT NODE
				$node = $dom->createElement("playerprofile");  
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("playerid", $playerID);
				$newnode->setAttribute("lastemoid", "0");
				$newnode->setAttribute("emocounter", "3");
				$newnode->setAttribute("lastemoname", $rowLastEmotionName['name']);
			}
			
			
		}//No emotion stated yet
		else{
			// ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("playerprofile");  
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("playerid", $playerID);
			$newnode->setAttribute("lastemoid", "0");
			$newnode->setAttribute("emocounter", "3");
			$newnode->setAttribute("lastemoname", "none");
		 }
	}
	
	
	$queryHugs = 'SELECT hugs, current_hug_base
	FROM "players"
	WHERE id = '.$playerID;
	
	$resultHugs = pg_query($connection, $queryHugs);
	
	if (!$resultHugs) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getEmotionsByPlayerID: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}else{
	  $statusHugs = pg_fetch_assoc($resultHugs);
	  $newnode->setAttribute("hugs", $statusHugs['hugs']);
	  $newnode->setAttribute("currenthugbase", $statusHugs['current_hug_base']);	
	}
	
	$querySelect = 'Select flower_points '.
	   'FROM players '.
	 'WHERE id='.$playerID.';';
	$resultSelect = pg_query($querySelect);
	
	if (!$resultSelect) {
		  die('Invalid query: ' . pg_last_error());
		  //DEBUG
		  error_log("updatePlayerScore: ".pg_last_error(), 3 ,"debug_file.log");
	}
	else{
		$myrow = pg_fetch_assoc($resultSelect);
		$newnode->setAttribute("flower_points", $myrow['flower_points']);
	}
	
	// 
	$queryPlayerEmotions = 'SELECT emoname, emonumber, emoid FROM (SELECT A1.name as emoname, 
	A2.number as emonumber, A2.player_id as playerid, A2.emotion_id as emoid
	FROM '.$emoTable.' A1, powers A2
	WHERE A1.id = A2.emotion_id) as Temp WHERE playerid = '.$playerID;
	
	$resultPlayerEmotions = pg_query($connection, $queryPlayerEmotions);
	
	if (!$resultPlayerEmotions) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getEmotionsByPlayerID: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}
	else{
		
		// Iterate through the rows, printing XML nodes for each
		while ($rowPlayerEmotions = pg_fetch_row($resultPlayerEmotions)){
			
			// ADD TO XML DOCUMENT NODE
			if ($rowPlayerEmotions[1] != 0) {
				 $node = $dom->createElement("emoNamePlayer");  
				 $newnode = $parnode->appendChild($node); 
				 $newnode->setAttribute("name", $rowPlayerEmotions[0]);
				 $newnode->setAttribute("number", $rowPlayerEmotions[1]);
				 $newnode->setAttribute("emoid", $rowPlayerEmotions[2]);
			}
			else{}
		}
	}
	
	$query = 'SELECT id, name, last_emotion_id, last_login, hugs, flower_points FROM "players" WHERE NOT id='.$playerID.' ORDER BY last_login DESC';

	$result = pg_query($connection, $query);
	//error_log($result);
	
	if (!$result) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getAllPlayersWithLocationTimestamp: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}
	
	// Iterate through the rows, printing XML nodes for each
	while ($row = pg_fetch_row($result)){
		
		$lastEmotionName = "";
		if ($row[2] != "0"){
			$queryPlayerEmotion = 'SELECT name FROM emotions WHERE id='.$row[2];
			
			$resultPlayerEmotion = pg_query($connection, $queryPlayerEmotion);
			
			if (!$resultPlayerEmotion) {
			  echo "An error occured.\n". pg_last_error();
			  //DEBUG
			  error_log("getEmotionsByPlayerID: ".pg_last_error(), 3 ,"debug_file.log");
			  exit;
			}
			
			$myrow = pg_fetch_assoc($resultPlayerEmotion);
			$lastEmotionName = $myrow['name'];
			
		}
		else{
			$lastEmotionName = "none";
		}
		
		if ($row[3] != ""){
			$lastLoginArray = explode("+",$row[3]);
			$lastLoginStringTemp = explode(" ",$lastLoginArray[0]);
			$lastLoginString = $lastLoginStringTemp[1]." (".$lastLoginStringTemp[0].")";
		}
		else{
			$lastLoginString = "No login yet...";
		}
		
	 	$allPlayers[] = array("player_id"=>$row[0],"name"=>$row[1],"last_emotion_id"=>$row[2],"last_emotion_name"=>$lastEmotionName ,"last_login"=>$lastLoginString,"hugs"=>$row[4],"flower_points"=>$row[5]);
		
	}
	
	/*$allPlayersSorted = array_sort($allPlayers,'score', SORT_DESC);
	
	$allPlayersSortedZero = array_values($allPlayersSorted);*/
	
	for($i = 0; $i < sizeof($allPlayers); ++$i){
		
		  // ADD TO XML DOCUMENT NODE
		  $node = $dom->createElement("player");  
		  $newnode = $parnode->appendChild($node);
		  $newnode->setAttribute("id", $allPlayers[$i]['player_id']);
		  $newnode->setAttribute("name", $allPlayers[$i]['name']);
		  $newnode->setAttribute("last_emotion_id", $allPlayers[$i]['last_emotion_id']);
		  $newnode->setAttribute("last_emotion_name", $allPlayers[$i]['last_emotion_name']);
		  $newnode->setAttribute("last_login", $allPlayers[$i]['last_login']);
		  $newnode->setAttribute("hugs", $allPlayers[$i]['hugs']);
		  $newnode->setAttribute("flower_points", $allPlayers[$i]['flower_points']);
	}
}

echo $dom->saveXML();

?>