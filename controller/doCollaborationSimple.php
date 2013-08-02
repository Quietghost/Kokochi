<?php header("Content-Type: text/xml; charset=UTF-8");
include("../model/postgresql_dbinfo.php");
include("../util/sortMultiArray.php");
include("../model/createRequiredEmotionsSimple.php");
include("../controller/getFlowerPointsForCollPlayers.php");
include("../model/updatePlayerPowerDecrease.php");

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("collaboration");
$parnode = $dom->appendChild($node); 

// Gets data from URL parameters
$me = $_GET['player_id'];
$collPlayerIdString = $_GET['collPlayerIdString'];
$emoTable = $_GET["emo_table"];
$requiredEmotionsTimestamp = $_GET['timestamp'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];

$collPlayerString = str_replace("~","_",$collPlayerIdString);
$collPlayerIdStringSQL = str_replace("~",",",$collPlayerIdString);
$collPlayerArray = explode("~", $collPlayerIdString);
$collPlayers = str_replace("~","_",$collPlayerIdString);

$timestampArray = explode("~",$requiredEmotionsTimestamp);

$requiredEmotionsIdString ="";
$requiredEmotionsNumberString = "";
$requiredEmotionsIdArray = array();
$requiredEmotionsNumberArray = array();
$emotionOfPlayerNumber;
$flowerCount = 0;
$myReward = 0;

$rewardedPlayers = array();
$contributingPlayers = array();

$emotionNumberString ="";
$emotionNameString ="";
$emotionString ="";
$baseReward=0;
$emoReward=0;
$globalRewardMulti=0;

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("setCollCurrentEmotion: ".pg_get_result($connection), 3 ,"debug_file.log");
}

//echo "collPlayerString: ".$collPlayerString;

$queryTable = 'SELECT COUNT(*) FROM pg_tables WHERE tablename = \'collplayers_'.$collPlayerString.'\';';
$resultTable = pg_query($queryTable);

if (!$resultTable) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
      error_log("setCollCurrentEmotion: ".pg_last_error(), 3 ,"debug_file.log");
}else{
	$statusTable = pg_fetch_assoc($resultTable);
}

if ($statusTable['count'] == "0"){
	$createTable = 'create table "collplayers_'.$collPlayerString.'"'.
        '('.
            'player_id bigint not null,'.
            'emotion_id int,'.
			'number int,'.
			'flower_points int,'.
			'checked int,'.
			'reward int'.
        ');';
	$tableCreated = pg_query($createTable);
	
	if (!$tableCreated) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
      error_log("setCollCurrentEmotion: ".pg_last_error(), 3 ,"debug_file.log");
	}else{
		try{
			$tempRequiredEmotionArray = createRequiredEmotionsSimple($collPlayers, $collPlayerIdString, $requiredEmotionsTimestamp, $emoTable);
			
			$requiredEmotionsIdString = $tempRequiredEmotionArray[0]["requiredemotions"];
			$requiredEmotionsNumberString = $tempRequiredEmotionArray[1]["numberofrequiredemotions"];
			
			$emotionString = $tempRequiredEmotionArray[0]["requiredemotions"];
			$emotionNumberString = $tempRequiredEmotionArray[1]["numberofrequiredemotions"];
			$emotionNameString = $tempRequiredEmotionArray[2]["namesofrequiredemotions"];
			
			$requiredEmotionsIdArray = explode(",",$requiredEmotionsIdString);
			$requiredEmotionsNumberArray = explode(",",$requiredEmotionsNumberString);
			
			//$sumFlowerPointsForCollPlayers = getFlowerPointsForCollPlayers($collPlayerIdStringSQL);
			
		}catch(Exception $e){
			// ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("collaborationResult");  
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("flowerpoints", $e);
		}
		
		for($j = 0, $sizeJ = sizeof($collPlayerArray); $j < $sizeJ; ++$j){
			$query = 'DELETE FROM "collPlayers" WHERE player_id_you='.$collPlayerArray[$j];
			$result = pg_query($query);
			
			if (!$result) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("resetCollPlayersByPlayerID: ".pg_last_error(), 3 ,"debug_file.log");
			}
		}
		//Every player inputs himself here, but only the first one deletes all the old entries
		for($j = 0, $sizeJ = sizeof($collPlayerArray); $j < $sizeJ; ++$j){
			if($collPlayerArray[$j] != $me){
				$query = 'INSERT INTO "collPlayers"('.
					'player_id_you, other_player)'.
					'VALUES ('.$me.', '.$collPlayerArray[$j].');';
					
				$result = pg_query($query);
			
				if (!$result) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("setCollPlayer: ".pg_last_error(), 3 ,"debug_file.log");
				}
			}
		}
		//Insert a dummy state for each player so that the table doesn´t get deleted if all contributing players have taken their rewards (only relevant for more than 2 players)
		for($j = 0, $sizeJ = sizeof($collPlayerArray); $j < $sizeJ; ++$j){
			$query = 'INSERT INTO "collplayers_'.$collPlayerString.'"('.
										'player_id, emotion_id, number, flower_points, checked)'.
										'VALUES ('.$collPlayerArray[$j].', 0,0,0,0);';
				
			$result = pg_query($query);
		
			if (!$result) {
			  die('Invalid query: ' . pg_last_error());
			  //DEBUG
			  error_log("setCollPlayer: ".pg_last_error(), 3 ,"debug_file.log");
			}
		}
		
		$querySelect = 'Select base_reward, emo_reward, global_reward_multi, flower_point_cost '.
   		'FROM globalscore;';
   
		$resultSelect = pg_query($querySelect);
		
		if (!$resultSelect) {
			  die('Invalid query: ' . pg_last_error());
			  //DEBUG
			  error_log("checkGlobalScore: ".pg_last_error(), 3 ,"debug_file.log");
		}
		
		$row = pg_fetch_assoc($resultSelect);
		$baseReward = intval($row['base_reward']);
		$emoReward = intval($row['emo_reward']);
		$globalRewardMulti = intval($row['global_reward_multi']);
		$flowerPointCost = intval($row['flower_point_cost']);
		
		
		//Insert dummy entry just in case the players have none of the required emotions, so that they can get the flower points
		$queryInsertDummy = 'INSERT INTO "collplayers_'.$collPlayerString.'"('.
											'player_id, emotion_id, number, flower_points, checked)'.
											'VALUES (0, 0,0,0,0);';
		$resultInsertDummy = pg_query($queryInsertDummy);
				
		if (!$resultInsertDummy) {
			die('Invalid query: ' . pg_last_error());
		}else{
		}
				
		for($i = 0, $size = sizeof($requiredEmotionsIdArray); $i < $size; ++$i)
		{
			$rewardedPlayers = array();
			$rewardedPlayersSorted = array();
			
			$queryEmotionsOfPlayersCount = 'SELECT count("player_id") FROM powers WHERE "emotion_id" = '.$requiredEmotionsIdArray[$i].' AND number > 0 AND player_id IN ('.$collPlayerIdStringSQL.');';
			$resultEmotionsOfPlayersCount = pg_query($queryEmotionsOfPlayersCount);
			
			if (!$resultEmotionsOfPlayersCount) {
				  die('Invalid query: ' . pg_last_error());
			}else{
				$statusEmotionsOfPlayersCount = pg_fetch_assoc($resultEmotionsOfPlayersCount);
			}
			
			if ($statusEmotionsOfPlayersCount['count'] != "0"){
				$contributingPlayersByEmotion = array();
				$emotionOfPlayerNumber = 0;
				
				$queryEmotionsOfPlayers = 'SELECT "player_id", number FROM powers WHERE "emotion_id" = '.$requiredEmotionsIdArray[$i].' AND number > 0 AND "player_id" IN ('.$collPlayerIdStringSQL.');';
				$resultEmotionsOfPlayers = pg_query($queryEmotionsOfPlayers);
				
				if (!$resultEmotionsOfPlayers) {
					  die('Invalid query: ' . pg_last_error());
				}else{
					while ($row = pg_fetch_row($resultEmotionsOfPlayers)){
						$emotionOfPlayerNumber += intval($row[1]);
						
						//Keep track of the players rewards if the collaboration is successful in the end
						if (in_array_field($rewardedPlayers,$row[0],"player_id") && in_array_field($rewardedPlayers,$requiredEmotionsIdArray[$i],"emotion_id")){
							
						}
						else{
							$queryScoreOfPlayers = 'SELECT flower_points FROM players WHERE "id" = '.$row[0].';';
							$resultScoreOfPlayers = pg_query($queryScoreOfPlayers);
							
							if (!$resultScoreOfPlayers) {
								  die('Invalid query: ' . pg_last_error());
							}else{
								$scoreOfPlayer = pg_fetch_assoc($resultScoreOfPlayers);
								//echo $scoreOfPlayer." ";
								$rewardedPlayers[] = array("player_id"=>$row[0],"count"=>intval($row[1]),"emotion_id"=>$requiredEmotionsIdArray[$i],"score"=>intval($scoreOfPlayer['flower_points']));
							}
						}
					}
					
					//Lower ranked players can contribute first, $topScorePlayer is actually the player with the lowest overall score
					$rewardedPlayersSorted = array_sort($rewardedPlayers,'score', SORT_ASC);
					
					$numberForEmotion = intval($requiredEmotionsNumberArray[$i]);
					while ($numberForEmotion > 0) {
						if (sizeof($rewardedPlayersSorted) != 0){
							$topScorePlayer = array_shift($rewardedPlayersSorted);
							
							if ($topScorePlayer['count'] > $numberForEmotion){
								//$topScorePlayer['count'] = ($topScorePlayer['count'] - $requiredEmotionsNumberArray[$i]);
								$topScorePlayer['count'] = $numberForEmotion;
							}
							$numberForEmotion = $numberForEmotion - $topScorePlayer['count'];
							
							$contributingPlayersByEmotion[] = $topScorePlayer;
							
						}//Not enough emotions stated by the collaborating players
						else{
							$numberForEmotion = 0;
						}
					}
					
					if ($emotionOfPlayerNumber >= intval($requiredEmotionsNumberArray[$i])){
						
					}//Players don´t have stated enough emotions, increase flower count
					else{
						$flowerCount += (intval($requiredEmotionsNumberArray[$i]) - $emotionOfPlayerNumber);
					}
				}
				//For every emotion there is an array with the contributing players
				$contributingPlayers[] = $contributingPlayersByEmotion;
			}//No player has the required emotion, increase flower count
			else{
				$flowerCount += $flowerPointCost;
			}
		}//all required emotions have been checked
		
		for($i = 0, $size = sizeof($contributingPlayers); $i < $size; ++$i)
		{
			$tempPlayerForEmotion = $contributingPlayers[$i];
			//print_r($tempPlayerForEmotion);
			
			for($j = 0, $sizeJ = sizeof($tempPlayerForEmotion); $j < $sizeJ; ++$j)
			{
				$newFPs = $emoReward*intval($tempPlayerForEmotion[$j]['count']);
				
				$queryUpdatePlayersCount = 'INSERT INTO "collplayers_'.$collPlayerString.'"('.
						'player_id, emotion_id, number, flower_points, checked, reward)'.
						'VALUES ('.$tempPlayerForEmotion[$j]['player_id'].', '.$tempPlayerForEmotion[$j]['emotion_id'].', '.$tempPlayerForEmotion[$j]['count'].',0,0,'.$newFPs.');';
						
				$resultUpdateOfPlayersCount = pg_query($queryUpdatePlayersCount);
					
				if (!$resultUpdateOfPlayersCount) {
					die('Invalid query: ' . pg_last_error());
				}else{
				}
			}
		}
		
		//The players have all of the required emotions (type and number)
		if ($flowerCount == 0){
			
			$query = 'SELECT DISTINCT player_id FROM "collplayers_'.$collPlayerString.'" WHERE emotion_id > 0';
							
			$result = pg_query($connection, $query);
							
			if (!$result) {
				echo "An error occured.\n". pg_last_error();
				 //DEBUG
				error_log("getCollCurrentEmotions: ".pg_last_error(), 3 ,"debug_file.log");
				exit;
			}
			
			$collPlayersReward = array();
			
			while ($row = pg_fetch_row($result)){
				if (intval($row[0]) != 0){//Ignore the dummy entry
					$collPlayersReward[] = $row[0];
				}
			}
			//print_r($contributingPlayers);
			$decreaseResult = decreasePlayerPowers($contributingPlayers);
			//$decreaseResult = true;
			
			if ($decreaseResult){
				
				for($j = 0, $sizeJ = sizeof($collPlayerArray); $j < $sizeJ; ++$j)
				{
					$increaseFPValue = 0;
					
					$queryIncreaseFP = 'SELECT sum(reward) FROM "collplayers_'.$collPlayerString.'" WHERE player_id='.$collPlayerArray[$j];
									
					$resultIncreaseFP = pg_query($connection, $queryIncreaseFP);
										
					if (!$resultIncreaseFP) {
						echo "An error occured.\n". pg_last_error();
						//DEBUG
						error_log("getCollCurrentEmotions: ".pg_last_error(), 3 ,"debug_file.log");
						exit;
					}
										
					$rowIncreaseFP = pg_fetch_assoc($resultIncreaseFP);
					//echo "Vergleich: ".$rowIncreaseFP['sum'] != "";
					if ($rowIncreaseFP['sum'] != ""){
						$increaseFPValue = $baseReward + intval($rowIncreaseFP['sum']);
					}
					else{
						$increaseFPValue = $baseReward;
					}
					
					$queryBaseFP = 'SELECT flower_points FROM players WHERE id='.$collPlayerArray[$j];
									
					$resultBaseFP = pg_query($connection, $queryBaseFP);
										
					if (!$resultBaseFP) {
						echo "An error occured.\n". pg_last_error();
						//DEBUG
						error_log("getCollCurrentEmotions: ".pg_last_error(), 3 ,"debug_file.log");
						exit;
					}
										
					$rowBaseFP = pg_fetch_assoc($resultBaseFP);
					
					$increaseFPValueSum = intval($rowBaseFP['flower_points']) + $increaseFPValue;
					
					$queryUpdateBaseFP = 'UPDATE players
   										SET flower_points='.$increaseFPValueSum.'
 										WHERE id='.$collPlayerArray[$j];
									
					$resultUpdateBaseFP = pg_query($connection, $queryUpdateBaseFP );
										
					if (!$resultUpdateBaseFP ) {
						echo "An error occured.\n". pg_last_error();
						//DEBUG
						error_log("getCollCurrentEmotions: ".pg_last_error(), 3 ,"debug_file.log");
						exit;
					}
					
					if ($collPlayerArray[$j] == $me){
						$myReward = $increaseFPValue;
						
						$queryMeChecked = 'UPDATE "collplayers_'.$collPlayerString.'" 
   										SET checked=1
 										WHERE player_id='.$collPlayerArray[$j];
									
						$resultMeChecked = pg_query($connection, $queryMeChecked);
											
						if (!$resultMeChecked) {
							echo "An error occured.\n". pg_last_error();
							//DEBUG
							error_log("getCollCurrentEmotions: ".pg_last_error(), 3 ,"debug_file.log");
							exit;
						}
						
						//Log my reward for this collaboration
						$queryCollHistoryRewards = 'INSERT INTO players_coll_rewards('.
											'coll_players, me, reward, "timestamp")'.
											"VALUES ('".$collPlayerString."', '".$me."', '".$myReward."', '".$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2]."');";
									
						$resultCollHistoryRewards = pg_query($connection, $queryCollHistoryRewards);
											
						if (!$resultCollHistoryRewards) {
							echo "An error occured.\n". pg_last_error();
							//DEBUG
							error_log("getCollCurrentEmotions: ".pg_last_error(), 3 ,"debug_file.log");
							exit;
						}
						
					}
				}
			}
			
			$score = $globalRewardMulti*count($collPlayerArray);
			
			$querySelect = 'Select score '.
			   'FROM globalscore;';
			   
			$resultSelect = pg_query($querySelect);
			
			if (!$resultSelect) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("updateGlobalScore: ".pg_last_error(), 3 ,"debug_file.log");
			}
			
			while($myrow = pg_fetch_assoc($resultSelect)) {
				$newScore = $myrow['score'] + $score;	
			}
				 
			$queryUpdate = 'UPDATE globalscore '.
			   'SET score='.$newScore.';';
			$resultUpdate = pg_query($queryUpdate);
			
			if (!$resultUpdate) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("updateGlobalScore: ".pg_last_error(), 3 ,"debug_file.log");
			}
			
			if ($lat !="" && $lng !=""){
				// Insert new row with user data
				$query = 'INSERT INTO "players_coll_history" ('.
							' "timestamp", coll_players, required_emotions, number_of_required_emotions, "location")'.
							' VALUES (\''.
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
							' VALUES (\''.
							$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\',  \''.$collPlayers.'\', \''.$emotionString.'\', \''.$emotionNumberString.'\');';
							
				$result = pg_query($query);
				
				if (!$result) {
				  error_log("updatePlayersCollHistory: ".pg_last_error(), 3 ,"debug_file.log");
				  die('Invalid query: ' . pg_last_error());
				}
			}
			
			$emotionNumberStringNew = str_replace(",",", ",$emotionNameString);
			$emotionNameStringNew = str_replace(",",", ",$emotionNumberString);
			
			// ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("collaborationResult");  
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("flowerpoints", $flowerCount);
			$newnode->setAttribute("requiredemotions", $emotionString);
			$newnode->setAttribute("numberofrequiredemotions", $emotionNumberStringNew);
			$newnode->setAttribute("namesofrequiredemotions", $emotionNameStringNew);
			//$newnode->setAttribute("sumFlowerPointsForCollPlayers", strval($sumFlowerPointsForCollPlayers));
			$newnode->setAttribute("myReward", $myReward);
			
		}//Some required emotions are missing either completely or in number
		else{
			
			decreasePlayerPowers($contributingPlayers);
			
			$queryUpdateFlowerPoints = 'UPDATE "collplayers_'.$collPlayerString.'"
			 	SET flower_points='.$flowerCount.';';
				
			$resultUpdateFlowerPoints = pg_query($queryUpdateFlowerPoints);
				
			if (!$resultUpdateFlowerPoints) {
				die('Invalid query: ' . pg_last_error());
			}else
			{
				
				$queryMeChecked = 'UPDATE "collplayers_'.$collPlayerString.'" 
   										SET checked=1
 										WHERE player_id='.$me;
									
				$resultMeChecked = pg_query($connection, $queryMeChecked);
											
				if (!$resultMeChecked) {
					echo "An error occured.\n". pg_last_error();
					//DEBUG
					error_log("getCollCurrentEmotions: ".pg_last_error(), 3 ,"debug_file.log");
					exit;
				}
				
				$emotionNumberStringNew = str_replace(",",", ",$emotionNameString);
				$emotionNameStringNew = str_replace(",",", ",$emotionNumberString);
				
				
				if ($lat !="" && $lng !=""){
					// Insert new row with user data
					$query = 'INSERT INTO "players_miss_coll_history" ('.
								' "timestamp", coll_players, required_emotions, number_of_required_emotions, "location")'.
								' VALUES (\''.
								$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\',  \''.$collPlayers.'\', \''.$emotionString.'\', \''.$emotionNumberString.'\', ST_GeomFromText(\'POINT('.$lng.' '.$lat.')\'));';
								
					$result = pg_query($query);
					
					if (!$result) {
					  error_log("updatePlayersCollHistory: ".pg_last_error(), 3 ,"debug_file.log");
					  die('Invalid query: ' . pg_last_error());
					}
				}else{
					// Insert new row with user data
					$query = 'INSERT INTO "players_miss_coll_history" ('.
								' "timestamp", coll_players, required_emotions, number_of_required_emotions)'.
								' VALUES (\''.
								$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\',  \''.$collPlayers.'\', \''.$emotionString.'\', \''.$emotionNumberString.'\');';
								
					$result = pg_query($query);
					
					if (!$result) {
					  error_log("updatePlayersCollHistory: ".pg_last_error(), 3 ,"debug_file.log");
					  die('Invalid query: ' . pg_last_error());
					}
				}
				
				
				// ADD TO XML DOCUMENT NODE
				$node = $dom->createElement("collaborationResult");  
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("flowerpoints", $flowerCount);
				$newnode->setAttribute("requiredemotions", $emotionString);
				$newnode->setAttribute("numberofrequiredemotions", $emotionNumberStringNew);
				$newnode->setAttribute("namesofrequiredemotions", $emotionNameStringNew);
				//$newnode->setAttribute("sumFlowerPointsForCollPlayers", strval($sumFlowerPointsForCollPlayers));
				$newnode->setAttribute("myReward", "-1");
			}
		}
	}
}//Table already created, check if all players are ready if yes delete everything from this collaboration otherwise just get my reward
else{
	$delete = false;
	
	$queryMeChecked = 'UPDATE "collplayers_'.$collPlayerString.'" 
   					SET checked=1
 					WHERE player_id='.$me;
			
	$resultMeChecked = pg_query($connection, $queryMeChecked);
											
	if (!$resultMeChecked) {
		echo "An error occured.\n". pg_last_error();
		//DEBUG
		error_log("getCollCurrentEmotions: ".pg_last_error(), 3 ,"debug_file.log");
		exit;
	}			
	
	try{
		$tempRequiredEmotionArray = createRequiredEmotionsSimple($collPlayers, $collPlayerIdString, $requiredEmotionsTimestamp, $emoTable);
		
		$emotionString = $tempRequiredEmotionArray[0]["requiredemotions"];
		$emotionNumberString = $tempRequiredEmotionArray[1]["numberofrequiredemotions"];
		$emotionNameString = $tempRequiredEmotionArray[2]["namesofrequiredemotions"];
		
		//$sumFlowerPointsForCollPlayers = getFlowerPointsForCollPlayers($collPlayerIdStringSQL);
		
	}catch(Exception $e){
	}
	//Every player inputs himself here
	for($j = 0, $sizeJ = sizeof($collPlayerArray); $j < $sizeJ; ++$j){
		if($collPlayerArray[$j] != $me){
			$query = 'INSERT INTO "collPlayers"('.
				'player_id_you, other_player)'.
				'VALUES ('.$me.', '.$collPlayerArray[$j].');';
				
			$result = pg_query($query);
		
			if (!$result) {
			  die('Invalid query: ' . pg_last_error());
			  //DEBUG
			  error_log("setCollPlayer: ".pg_last_error(), 3 ,"debug_file.log");
			}
		}
	}
	
	//Ignoring the dummy entry
	$queryTestChecked = 'SELECT count(*) FROM "collplayers_'.$collPlayerString.'" WHERE checked=0 AND NOT player_id=0;';
	$resultTestChecked = pg_query($queryTestChecked);
								
	if (!$resultTestChecked) {
		die('Invalid query: ' . pg_last_error());
	}else{
		$testChecked = pg_fetch_assoc($resultTestChecked);
		
		//Check if all players already checked their rewards
		if (intval($testChecked['count']) == 0){
			$delete = true;
		}
			
		$queryFlowerPoints = 'SELECT flower_points FROM "collplayers_'.$collPlayerString.'";';
		$resultFlowerPoints = pg_query($queryFlowerPoints);
										
		if (!$resultFlowerPoints) {
			die('Invalid query: ' . pg_last_error());
		}else{
			$flowerPointsForPlayer = pg_fetch_assoc($resultFlowerPoints);
			
			//If the collaboration was successful
			if ($flowerPointsForPlayer['flower_points'] == "0"){
				
				$querySelect = 'Select base_reward, emo_reward, global_reward_multi, flower_point_cost '.
				'FROM globalscore;';
		   
				$resultSelect = pg_query($querySelect);
				
				if (!$resultSelect) {
					  die('Invalid query: ' . pg_last_error());
					  //DEBUG
					  error_log("checkGlobalScore: ".pg_last_error(), 3 ,"debug_file.log");
				}
				
				$row = pg_fetch_assoc($resultSelect);
				$baseReward = intval($row['base_reward']);
				$emoReward = intval($row['emo_reward']);
				$globalRewardMulti = intval($row['global_reward_multi']);
				$flowerPointCost = intval($row['flower_point_cost']);
				
				$queryIncreaseFP = 'SELECT sum(reward) FROM "collplayers_'.$collPlayerString.'" WHERE player_id='.$me;
								
				$resultIncreaseFP = pg_query($connection, $queryIncreaseFP);
									
				if (!$resultIncreaseFP) {
					echo "An error occured.\n". pg_last_error();
					//DEBUG
					error_log("getCollCurrentEmotions: ".pg_last_error(), 3 ,"debug_file.log");
					exit;
				}
									
				$rowIncreaseFP = pg_fetch_assoc($resultIncreaseFP);
				
				if ($rowIncreaseFP['sum'] != ""){
					$increaseFPValue = $baseReward + intval($rowIncreaseFP['sum']);
				}
				else{
					$increaseFPValue = $baseReward;
				}
				
				$emotionNumberStringNew = str_replace(",",", ",$emotionNameString);
				$emotionNameStringNew = str_replace(",",", ",$emotionNumberString);
				
				//Log my reward for this collaboration
				$queryCollHistoryRewards = 'INSERT INTO players_coll_rewards('.
						'coll_players, me, reward, "timestamp")'.
						"VALUES ('".$collPlayerString."', '".$me."', '".$increaseFPValue."', '".$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2]."');";
							
				$resultCollHistoryRewards = pg_query($connection, $queryCollHistoryRewards);
									
				if (!$resultCollHistoryRewards) {
					echo "An error occured.\n". pg_last_error();
					//DEBUG
					error_log("getCollCurrentEmotions: ".pg_last_error(), 3 ,"debug_file.log");
					exit;
				}
						
				
				// ADD TO XML DOCUMENT NODE
				$node = $dom->createElement("collaborationResult");  
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("flowerpoints", $flowerPointsForPlayer['flower_points']);
				$newnode->setAttribute("requiredemotions", $emotionString);
				$newnode->setAttribute("numberofrequiredemotions", $emotionNumberStringNew);
				$newnode->setAttribute("namesofrequiredemotions", $emotionNameStringNew);
				//$newnode->setAttribute("sumFlowerPointsForCollPlayers", strval($sumFlowerPointsForCollPlayers));
				$newnode->setAttribute("myReward", $increaseFPValue);
					
			}else{
				
				$emotionNumberStringNew = str_replace(",",", ",$emotionNameString);
				$emotionNameStringNew = str_replace(",",", ",$emotionNumberString);	
				
				// ADD TO XML DOCUMENT NODE
				$node = $dom->createElement("collaborationResult");  
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("flowerpoints", $flowerPointsForPlayer['flower_points']);
				$newnode->setAttribute("requiredemotions", $emotionString);
				$newnode->setAttribute("numberofrequiredemotions", $emotionNumberStringNew);
				$newnode->setAttribute("namesofrequiredemotions", $emotionNameStringNew);
				//$newnode->setAttribute("sumFlowerPointsForCollPlayers", strval($sumFlowerPointsForCollPlayers));
				$newnode->setAttribute("myReward", "-1");	
			}
			
			//If all other players have already checked their rewards delete everything for this collaboration
			if ($delete){
				$queryDropTable = 'DROP TABLE "collplayers_'.$collPlayerString.'";';
				$resultDropTable = pg_query($queryDropTable);
						
				if (!$resultDropTable) {
					die('Invalid query: ' . pg_last_error());
					//DEBUG
					error_log("setCollCurrentEmotion: ".pg_last_error(), 3 ,"debug_file.log");
				}else
				{}
					
				$queryDelete = 'DELETE FROM "required_emotions_collcurrent" WHERE collplayers = \''.$collPlayerString.'\'; ';

				$resultDelete = pg_query($queryDelete);
					
				if (!$resultDelete) {
					die('Invalid query: ' . pg_last_error());
				 	//DEBUG
					error_log("deleteRequiredEmotionsTableEntries: ". pg_last_error(), 3 ,"debug_file.log");
				}
				
				for($i = 0, $size = sizeof($collPlayerArray); $i < $size; ++$i)
				{
					$query = 'DELETE FROM "player_'.$collPlayerArray[$i].'_collCurrent";';
					$result = pg_query($query);
						
					if (!$result) {
						die('Invalid query: ' . pg_last_error());
						//DEBUG
						error_log("clearTable: ".pg_last_error(), 3 ,"debug_file.log");
					}
				}
			}
		}
	}
}
echo $dom->saveXML();

?>