<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");
include("../util/sortMultiArray.php");

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("Analysis_Results");
$parnode = $dom->appendChild($node); 

$emoTable = $_GET["emo_table"];
$game = $_GET["game"];


$allHistory = array();

// Opens a connection to a postgresql server
switch($game){
	
	case "test":
		$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";
	break;
	case "ccil":
		$conn_string = "host=localhost port=5432 dbname=".$databaseCcil." user=".$usernameCcil." password=".$passwordCcil."";
	break;
	case "naist";
		$conn_string = "host=localhost port=5432 dbname=".$databaseNaist." user=".$usernameNaist." password=".$passwordNaist."";
	break;
}



$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("getCollPlayersHistory: ".pg_get_result($connection), 3 ,"debug_file.log");
}
else{
	
	$numberOfDaysPlayed = 0;
	
	$queryAllPlayerCount = "SELECT count(id) FROM players;";
	
	$resultAllPlayerCount = pg_query($connection, $queryAllPlayerCount);
		
	if (!$resultAllPlayerCount) {
	  echo "An error occured.\n". pg_last_error();
	  exit;
	}
			
	$allPlayerCount = pg_fetch_assoc($resultAllPlayerCount);
	
	//--------------------------------------------

	$queryActivePlayerCount = "SELECT count(id) FROM players WHERE last_login != '' AND last_hug != '';";
	
	$resultActivePlayerCount = pg_query($connection, $queryActivePlayerCount);
		
	if (!$resultActivePlayerCount) {
	  echo "An error occured.\n". pg_last_error();
	  exit;
	}
			
	$activePlayerCount = pg_fetch_assoc($resultActivePlayerCount);
	
	//--------------------------------------------
	$queryStatedEmotionsCount = "SELECT count(player_id) FROM players_emo_statements_history;";
	
	$resultStatedEmotionsCount = pg_query($connection, $queryStatedEmotionsCount);
		
	if (!$resultStatedEmotionsCount) {
	  echo "An error occured.\n". pg_last_error();
	  exit;
	}
			
	$statedEmotionsCount = pg_fetch_assoc($resultStatedEmotionsCount);
	//--------------------------------------------
	$queryCollCount = "SELECT count(id) FROM players_coll_history;";
	
	$resultCollCount = pg_query($connection, $queryCollCount);
		
	if (!$resultCollCount) {
	  echo "An error occured.\n". pg_last_error();
	  exit;
	}
			
	$collCount = pg_fetch_assoc($resultCollCount);
	//--------------------------------------------
	$queryHugCount = "SELECT count(id) FROM players_hug_history;";
	
	$resultHugCount = pg_query($connection, $queryHugCount);
		
	if (!$resultHugCount) {
	  echo "An error occured.\n". pg_last_error();
	  exit;
	}
			
	$hugCount = pg_fetch_assoc($resultHugCount);
	//--------------------------------------------
	$queryPositiveEmotionsCount = "SELECT count(emotion_id) FROM players_emo_statements_history WHERE emotion_id IN 
						(Select id from emotions Where type = 1);";
	
	$resultPositiveEmotionsCount = pg_query($connection, $queryPositiveEmotionsCount);
		
	if (!$resultPositiveEmotionsCount) {
	  echo "An error occured.\n". pg_last_error();
	  exit;
	}
			
	$positiveEmotionsCount = pg_fetch_assoc($resultPositiveEmotionsCount);
	//--------------------------------------------
	$queryNegativeEmotionsCount = "SELECT count(emotion_id) FROM players_emo_statements_history WHERE emotion_id IN 
						(Select id from emotions Where type = 2);";
	
	$resultNegativeEmotionsCount = pg_query($connection, $queryNegativeEmotionsCount);
		
	if (!$resultNegativeEmotionsCount) {
	  echo "An error occured.\n". pg_last_error();
	  exit;
	}
			
	$negativeEmotionsCount = pg_fetch_assoc($resultNegativeEmotionsCount);
	
	/*$node = $dom->createElement("gamestats");  
	$newnode = $parnode->appendChild($node);
	$newnode->setAttribute("active_players", $activePlayerCount["count"] );
	$newnode->setAttribute("number_of_emotions", $statedEmotionsCount["count"]);
	$newnode->setAttribute("number_of_positive_emotions", $positiveEmotionsCount["count"]);
	$newnode->setAttribute("number_of_negative_emotions", $negativeEmotionsCount["count"]);
	$newnode->setAttribute("number_of_collaborations", $collCount["count"]);
	$newnode->setAttribute("number_of_hugs_send", $hugCount["count"]);*/
	
	//--------------------------------------------
	$timeline = array();
	$uniqueDays = array();
	
	$query = 'SELECT emotion_id, timestamp FROM "players_emo_statements_history" ORDER BY timestamp ASC;';
	
	$result = pg_query($connection, $query);
	
	if (!$result) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getOptions: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}
	
	// Iterate through the rows
	while ($row = pg_fetch_row($result)){
		
		$timeEmo = explode(" ",$row[1]);
		$timeEmoDateTemp = explode("-",$timeEmo[0]);
		$timeEmoDate = $timeEmoDateTemp[1].$timeEmoDateTemp[2];
		
		$timeline[] = array("emo_id"=>$row[0],"date"=>$timeEmoDate);
		
		if (!in_object($timeEmoDate, $uniqueDays)){
			$uniqueDays[] = $timeEmoDate;
		}
	}
	//-----------------------------------------------
	$numberOfDaysPlayedEmotion = sizeof($uniqueDays);
	//-----------------------------------------------
	$timelineDays = array();
	
	for($i = 0, $size = sizeof($uniqueDays); $i < $size; ++$i){
		
		$day = array();
		$day[] = array("date"=>$uniqueDays[$i]);
		
		for($j = 0, $sizeTimeline = sizeof($timeline); $j < $sizeTimeline; ++$j){
			
			if($uniqueDays[$i] == $timeline[$j]["date"]){
				
				$temp = search_array($day,"emo_id", $timeline[$j]["emo_id"]);
				
				if(sizeof($temp) == 0){
					$day[] = array("emo_id"=>$timeline[$j]["emo_id"],"number"=>1);
				}else{
					$key = array_keys($temp);
					$number = intval($temp[$key[0]]["number"]);
					
					$day[$key[0]] = array("emo_id"=>$timeline[$j]["emo_id"],"number"=>($number + 1));
					
				}
			}
		}
		$timelineDays[] = $day;
	}
	
	for($i = 0, $size = sizeof($timelineDays); $i < $size; ++$i){
		$emoDayCount = 0;
		// ADD TO XML DOCUMENT NODE
		$node = $dom->createElement("timeline");  
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("day", $timelineDays[$i][0]["date"]);
		
		for($j = 1, $sizeDay = sizeof($timelineDays[$i]); $j < $sizeDay; ++$j){
			
			$newnode->setAttribute("emotion_".$j, $timelineDays[$i][$j]["emo_id"]."~".$timelineDays[$i][$j]["number"]);
			$emoDayCount = $emoDayCount + intval($timelineDays[$i][$j]["number"]);
		}
		
		$newnode->setAttribute("total", $emoDayCount);
	}
	
	//---------Individual Player Statistics-------------
	$players = array();
	
	// Select all the rows in the markers table
	$query = 'SELECT id, flower_points, name FROM "players" ORDER BY flower_points DESC;';
	
	$result = pg_query($connection, $query);
	
	if (!$result) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getOptions: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}
	
	// Iterate through the rows
	while ($row = pg_fetch_row($result)){
		$players[] = array("id"=>$row[0],"flower_points"=>$row[1],"name"=>$row[2]);
	}
	
	for($i = 0, $size = sizeof($players); $i < $size; ++$i){
		
		$myFlowerPoints = 0;
		$myEmoCount = 0;
		$myCollCount = 0;
		$myHugSend = 0;
		$myHugRecieved = 0;
		
		// Select all the rows in the markers table
		$query = 'SELECT count(*) as count_emo 
		FROM players_emo_statements_history
		WHERE player_id='.$players[$i]["id"].';';
		
		$result = pg_query($connection, $query);
		
		if ($result) {
			$myEmoCountTemp = pg_fetch_assoc($result);
			$myEmoCount = intval($myEmoCountTemp['count_emo']);
			
		}
		
			
		// Select all the rows in the markers table
		$query = 'SELECT count(*) as count_hug_send 
		FROM players_hug_history
		WHERE sender='.$players[$i]["id"].';';
		
		$result = pg_query($connection, $query);
		
		if ($result) {
			$myHugSendTemp = pg_fetch_assoc($result);
			$myHugSend = intval($myHugSendTemp['count_hug_send']);
		
		}
			
		// Select all the rows in the markers table
		$query = 'SELECT count(*) as count_hug_recieved 
		FROM players_hug_history
		WHERE reciever='.$players[$i]["id"].';';
		
		$result = pg_query($connection, $query);
		
		if ($result) {
			$myHugRecievedTemp = pg_fetch_assoc($result);
			$myHugRecieved = intval($myHugRecievedTemp['count_hug_recieved']);
		}
			
		// Select all the rows in the markers table
		$query = 'SELECT count(id) FROM players_coll_history WHERE coll_players LIKE \'%!_'.$players[$i]["id"].'\' escape \'!\' OR coll_players LIKE \''.$players[$i]["id"].'!_%\' escape \'!\' OR coll_players LIKE \'%!_'.$players[$i]["id"].'!_%\' escape \'!\'';
		
		$result = pg_query($connection, $query);
		
		if ($result) {
			$myCollCountTemp = pg_fetch_assoc($result);
			$myCollCount = intval($myCollCountTemp['count']);
		}
		
		// ADD TO XML DOCUMENT NODE
		$node = $dom->createElement("playerstats");  
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("id", $players[$i]["id"] );
		$newnode->setAttribute("name", $players[$i]["name"] );
		$newnode->setAttribute("my_flower_points", $players[$i]["flower_points"] );
		$newnode->setAttribute("number_of_emotions", $myEmoCount);
		$newnode->setAttribute("number_of_collaborations", $myCollCount);
		$newnode->setAttribute("number_of_hugs_send", $myHugSend);
		$newnode->setAttribute("number_of_hugs_recieved", $myHugRecieved);
		
		
	}
	
	//-----------------------------------------------------------------
		$emotions = array();
		// Select unique emotions
		$query = 'SELECT DISTINCT t1.emotion_id as emotionID, t2.name as emotionName, t2.type as type
					FROM players_emo_statements_history t1, emotions t2 WHERE t1.emotion_id = t2.id ORDER BY emotionid;';
		
		$result = pg_query($connection, $query);
	
		if (!$result) {
		  echo "An error occured.\n". pg_last_error();
		  exit;
		}
		
		// Iterate through the rows
		while ($row = pg_fetch_row($result)){
			
			// Select all the rows in the markers table
			$queryCount = 'SELECT count(emotion_id) FROM players_emo_statements_history WHERE emotion_id='.$row[0];
			
			$resultCount = pg_query($connection, $queryCount);
			
			if ($resultCount) {
				$emotionCount = pg_fetch_assoc($resultCount);
			}
			
			$emotions[] = array("name"=>$row[1],"count"=>$emotionCount['count'],"type"=>$row[2] );
			
		}
		
		$emotionsSorted = array_sort($emotions,'count', SORT_DESC);
		$emotionsSortedZero = array_values($emotionsSorted);
	
		for($i = 0, $size = sizeof($emotionsSortedZero); $i < $size; ++$i){
			
			// ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("emotionstats");  
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("name",$emotionsSortedZero[$i]["name"] );
			$newnode->setAttribute("count",$emotionsSortedZero[$i]["count"] );
			$newnode->setAttribute("type", $emotionsSortedZero[$i]["type"] );
			
		}
		
		
	//--------------------------------------------
	$timeline = array();
	$uniqueDays = array();
	
	$query = 'SELECT coll_players, timestamp FROM "players_coll_history" ORDER BY timestamp ASC;';
	
	$result = pg_query($connection, $query);
	
	if (!$result) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getOptions: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}
	
	// Iterate through the rows
	while ($row = pg_fetch_row($result)){
		
		$timeEmo = explode(" ",$row[1]);
		$timeEmoDateTemp = explode("-",$timeEmo[0]);
		$timeEmoDate = $timeEmoDateTemp[1].$timeEmoDateTemp[2];
		
		$timeline[] = array("coll_id"=>$row[0],"date"=>$timeEmoDate);
		
		if (!in_object($timeEmoDate, $uniqueDays)){
			$uniqueDays[] = $timeEmoDate;
		}
	}
	//-----------------------------------------------
	$numberOfDaysPlayedColl = sizeof($uniqueDays);
	//-----------------------------------------------
	$timelineDays = array();
	
	for($i = 0, $size = sizeof($uniqueDays); $i < $size; ++$i){
		
		$counter = 0;
		
		for($j = 0, $sizeTimeline = sizeof($timeline); $j < $sizeTimeline; ++$j){
			
			if($uniqueDays[$i] == $timeline[$j]["date"]){
				$counter++;
			}
		}
		
		$node = $dom->createElement("coll_timeline");  
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("day", $uniqueDays[$i]);
		$newnode->setAttribute("total", $counter);
	}
	
	//--------------------------------------------
	$timeline = array();
	$uniqueDays = array();
	
	$query = 'SELECT sender, timestamp FROM "players_hug_history" ORDER BY timestamp ASC;';
	
	$result = pg_query($connection, $query);
	
	if (!$result) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getOptions: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}
	
	// Iterate through the rows
	while ($row = pg_fetch_row($result)){
		
		$timeEmo = explode(" ",$row[1]);
		$timeEmoDateTemp = explode("-",$timeEmo[0]);
		$timeEmoDate = $timeEmoDateTemp[1].$timeEmoDateTemp[2];
		
		$timeline[] = array("sender_id"=>$row[0],"date"=>$timeEmoDate);
		
		if (!in_object($timeEmoDate, $uniqueDays)){
			$uniqueDays[] = $timeEmoDate;
		}
	}
	
	//-----------------------------------------------
	$numberOfDaysPlayedHugs = sizeof($uniqueDays);
	//-----------------------------------------------
	
	$timelineDays = array();
	
	for($i = 0, $size = sizeof($uniqueDays); $i < $size; ++$i){
		
		$counter = 0;
		
		for($j = 0, $sizeTimeline = sizeof($timeline); $j < $sizeTimeline; ++$j){
			
			if($uniqueDays[$i] == $timeline[$j]["date"]){
				$counter++;
			}
		}
		
		$node = $dom->createElement("hug_timeline");  
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("day", $uniqueDays[$i]);
		$newnode->setAttribute("total", $counter);
	}
	
	$numbers[] = $numberOfDaysPlayedEmotion;
	$numbers[] = $numberOfDaysPlayedColl;
	$numbers[] = $numberOfDaysPlayedHugs;
	
	sort($numbers);
	
	//---------------------------------------------------------------------
	
	$uniqueCollPlayers = array();
	
	$query = 'SELECT coll_players FROM "players_coll_history";';
	
	$result = pg_query($connection, $query);
	
	if (!$result) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getOptions: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}
	
	// Iterate through the rows
	while ($row = pg_fetch_row($result)){
	
		$collPlayersTemp = explode("_",$row[0]);
		
		foreach($collPlayersTemp as $player) {
			if (!in_array($player,$uniqueCollPlayers)){
				$uniqueCollPlayers[] = $player;
			}else{
			}
		}
	}
	
	foreach($uniqueCollPlayers as $player) {
		// Select all the rows in the markers table
		$query = 'SELECT name FROM players WHERE id='.$player;
		
		$result = pg_query($connection, $query);
		
		if ($result) {
			$collPlayersTemp = pg_fetch_assoc($result);
		}
		
		$node = $dom->createElement("collplayers");  
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("id", $player );
		$newnode->setAttribute("name", $collPlayersTemp["name"]);
		
	}
	
	
	$node = $dom->createElement("gamestats");  
	$newnode = $parnode->appendChild($node);
	$newnode->setAttribute("all_players", $allPlayerCount["count"] );
	$newnode->setAttribute("active_players", $activePlayerCount["count"] );
	$newnode->setAttribute("number_of_emotions", $statedEmotionsCount["count"]);
	$newnode->setAttribute("number_of_positive_emotions", $positiveEmotionsCount["count"]);
	$newnode->setAttribute("number_of_negative_emotions", $negativeEmotionsCount["count"]);
	$newnode->setAttribute("number_of_collaborations", $collCount["count"]);
	$newnode->setAttribute("number_of_hugs_send", $hugCount["count"]);
	$newnode->setAttribute("number_of_days_played", $numbers[2]);
	$newnode->setAttribute("number_of_coll_players", sizeof($uniqueCollPlayers));
	
	
	echo $dom->saveXML();
}


//-------------------------------------------------------------------
function in_object($val, $obj){

    if($val == ""){
        trigger_error("in_object expects parameter 1 must not empty", E_USER_WARNING);
        return false;
    }
    if(!is_object($obj)){
        $obj = (object)$obj;
    }

    foreach($obj as $key => $value){
        if(!is_object($value) && !is_array($value)){
            if($value == $val){
                return true;
            }
        }else{
            return in_object($val, $value);
        }
    }
    return false;
}

function multidimensional_search($parents, $searched) { 
  if (empty($searched) || empty($parents)) { 
    return false; 
  } 
  
  foreach ($parents as $key => $value) { 
    $exists = true; 
    foreach ($searched as $skey => $svalue) { 
      $exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue); 
    } 
    if($exists){ return $key; } 
  } 
  
  return false; 
} 

function search($array, $key, $value)
{
    $results = array();

    if (is_array($array))
    {
        if (isset($array[$key]) && $array[$key] == $value)
            $results[] = $array;

        foreach ($array as $subarray)
            $results = array_merge($results, search($subarray, $key, $value));
    }

    return $results;
}

function search_array($array, $key, $value) {
  $return = array();   
  foreach ($array as $k=>$subarray){  
    if (isset($subarray[$key]) && $subarray[$key] == $value) {
      $return[$k] = $subarray;
      return $return;
    } 
  }
}

?>