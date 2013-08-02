<?php //header("Content-Type: text/plain; charset=UTF-8");

function createRequiredEmotionsSimple($collPlayers, $collPlayerIdString, $requiredEmotionsTimestamp, $emoTable){
	include("postgresql_dbinfo.php");
	
	/*// Start XML file, create parent node
	$dom = new DOMDocument("1.0", 'UTF-8');
	$node = $dom->createElement("collaboration");
	$parnode = $dom->appendChild($node); 
	
	// Gets data from URL parameters
	$collPlayers = $_GET['collPlayers'];
	$collPlayerIdString = $_GET['collPlayerIdString'];
	$emoTable = $_GET["emoTable"];
	$requiredEmotionsTimestamp = $_GET['timestamp'];*/
	
	$tempRequiredEmotionArray = array();
	$playerRanking = array();
	
	$emotionNameString ="";
	$emotionNumberString = "";
	$emotionString ="";
	
	$collPlayerIdStringArray = explode('_', $collPlayers);
	$collPlayersSQLString = str_replace('_',',',$collPlayers);
	
	$timestampArray = explode('~', $requiredEmotionsTimestamp);
	$numberOfCollPlayers = count($collPlayerIdStringArray);
	$sumOfEmotions = 0;
	$maxNumberOfEmotions = 0;
	$additionalEmotions = 0;

	// Opens a connection to a postgresql server
	$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";
	
	$connection = pg_connect($conn_string);
	
	if (!$connection) {
	  die('Not connected : ' .pg_get_result($connection));
	  //DEBUG
	  error_log("createRequiredEmotions: ".pg_get_result($connection), 3 ,"debug_file.log");
	}
	
	$querySelect = 'SELECT COUNT(*) FROM "required_emotions_collcurrent" '.
					'WHERE "collplayers"=\''.$collPlayers.'\';';
					
	$resultSelect = pg_query($querySelect);
	
	if (!$resultSelect) {
		  die('Invalid query: ' . pg_last_error());
		  //DEBUG
		  error_log("createRequiredEmotions: ".pg_last_error(), 3 ,"debug_file.log");
	}else{
		$statusSelect = pg_fetch_assoc($resultSelect);
	}	 
	
	if ($statusSelect['count'] == "0") {
			/*if ($openCollIdFlag =="") {*/
			
		$querySelectPowerCount = 'SELECT A1.player_id, A1.emotion_id, A1.number, A2.flower_points, A3.name FROM "powers" A1, players A2, emotions A3 
				WHERE A1.player_id IN ('.$collPlayersSQLString.') AND A1.player_id = A2.id AND A1.number > 0 AND A1.emotion_id = A3.id
				ORDER BY number DESC;';
					
		$resultSelectPowerCount = pg_query($querySelectPowerCount);		
			
		$sumOfEmotions = pg_fetch_all($resultSelectPowerCount);
		$emotionCollPlayersRatio = count($sumOfEmotions)/$numberOfCollPlayers;
		
		
		switch ($emotionCollPlayersRatio){
					case ($emotionCollPlayersRatio < 1):
					$maxNumberOfEmotions = 0;
					$additionalEmotions = 2;
					break;
					
					case ($emotionCollPlayersRatio <= 2):
					$maxNumberOfEmotions = 2;
					break;
					
					case ($emotionCollPlayersRatio <= 5):
					$maxNumberOfEmotions = 3;
					break;
					
					case ($emotionCollPlayersRatio <= 10):
					$maxNumberOfEmotions = 4;
					break;
					
					case ($emotionCollPlayersRatio <= 20):
					$maxNumberOfEmotions = (2*$numberOfCollPlayers)+5;
					break;
					
					case ($emotionCollPlayersRatio > 20):
					$maxNumberOfEmotions = (2*$numberOfCollPlayers)-2;
					$additionalEmotions = 2;
					break;
		}
		
		//echo $maxNumberOfEmotions;
		
		$querySelectPlayerRanking = 'SELECT id, flower_points FROM players 
		WHERE id IN ('.$collPlayersSQLString.') ORDER BY flower_points ASC;';
					
		$resultSelectPlayerRanking = pg_query($querySelectPlayerRanking);		
		
		while ($rowPlayerRanking = pg_fetch_row($resultSelectPlayerRanking)){
			$playerRanking[] = array("id"=>$rowPlayerRanking[0], "flower_points"=>$rowPlayerRanking[1]);
		}
		
		$usedEmotionsCount = 0;
		$blockedEmotionsArray = array();
		
		for ($i = 0; $i < $numberOfCollPlayers; $i++){
			
			if ($usedEmotionsCount <= $maxNumberOfEmotions && $maxNumberOfEmotions != 0) {
				
				$querySelectEmos = 'SELECT A1.player_id, A1.emotion_id, A1.number, A2.flower_points, A3.name FROM "powers" A1, players A2, emotions A3 
				WHERE A1.player_id IN ('.$playerRanking[$i]["id"].') AND A1.player_id = A2.id AND A1.number > 0 AND A1.emotion_id = A3.id
				ORDER BY RANDOM();';
					
				$resultSelectEmos = pg_query($querySelectEmos);		
				
				if ($resultSelectEmos){
					$rowEmos = pg_fetch_all($resultSelectEmos);
					
					$countEmoTable = count($rowEmos);
					
					if ($rowEmos[0]["emotion_id"] !=""){
						switch ($countEmoTable){
							case ($countEmoTable < 3):
							$end = false;
							for ($j = 0; $j < $countEmoTable;$j++){
								if (!$end){
									if (!in_array($rowEmos[$j]["emotion_id"],$blockedEmotionsArray)){
										$end = true;
										$usedEmotionsCount++;
										$blockedEmotionsArray[] = $rowEmos[$j]["emotion_id"];
										if ($emotionNameString == ""){
											$emotionNameString .= $rowEmos[$j]["name"];
										}else{
											$emotionNameString .= ",".$rowEmos[$j]["name"];
										}
										if ($emotionNumberString == ""){
											$emotionNumberString .= $rowEmos[$j]["number"];
										}else{
											$emotionNumberString .= ",".$rowEmos[$j]["number"];
										}
										if ($emotionString == ""){
											$emotionString .= $rowEmos[$j]["emotion_id"];
										}else{
											$emotionString .= ",".$rowEmos[$j]["emotion_id"];
										}
									}
								}
							}
							break;
							
							case ($countEmoTable >= 3):
							$usableEmotions = round((($countEmoTable/($i+1)) - $i));
							
							if ($usableEmotions  <= 0){
								$usableEmotions = 1;
							}
							
							$tempUsedEmotions = 0;
							
							for ($j = 0; $j < $countEmoTable;$j++){
								if ($usedEmotionsCount <= $maxNumberOfEmotions) {
									if ($tempUsedEmotions < $usableEmotions){
										if (!in_array($rowEmos[$j]["emotion_id"],$blockedEmotionsArray)){
											$usedEmotionsCount++;
											$tempUsedEmotions++;
											$blockedEmotionsArray[] = $rowEmos[$j]["emotion_id"];
											if ($emotionNameString == ""){
												$emotionNameString .= $rowEmos[$j]["name"];
											}else{
												$emotionNameString .= ",".$rowEmos[$j]["name"];
											}
											if ($emotionNumberString == ""){
												$emotionNumberString .= $rowEmos[$j]["number"];
											}else{
												$emotionNumberString .= ",".$rowEmos[$j]["number"];
											}
											if ($emotionString == ""){
												$emotionString .= $rowEmos[$j]["emotion_id"];
											}else{
												$emotionString .= ",".$rowEmos[$j]["emotion_id"];
											}
										}
									}
								}
							}
							break;
						};
					}
				}
			}
		}
		
		if ($additionalEmotions > 0){
			$positions = $additionalEmotions;
			//Used to make sure that no emotion is chosen more than once
			$emotionStringAdditionals = "";
			$minEmoCount = 1;
			$maxEmoCount = 3;
			$i=0;
			
			do {
				$randEmo = rand(1,28);
				
				if (in_array($randEmo, $blockedEmotionsArray)){
				}else{
					if ($emotionStringAdditionals == ""){
						$blockedEmotionsArray[] = $randEmo;
						$emotionStringAdditionals = $randEmo;
						$number = rand($minEmoCount,$maxEmoCount);
						if ($maxNumberOfEmotions != 0){
							$emotionNumberString .= ",".$number;
						}else{
							$emotionNumberString .= $number;
						}
					}else{
						$blockedEmotionsArray[] = $randEmo;
						$emotionStringAdditionals .= ",".$randEmo;
						$number = rand($minEmoCount,$maxEmoCount);
						$emotionNumberString .= ",".$number;
					}
				$i++;
				}
			}while ($i<$positions);
				
			// Select emotion names
			$queryEmoNames = 'SELECT name, id FROM "'.$emoTable.'" WHERE id IN('.$emotionStringAdditionals.')';
			
			$resultEmoNames = pg_query($connection, $queryEmoNames);
			
			if (!$resultEmoNames) {
			  echo "An error occured.\n". pg_last_error();
			  //DEBUG
			  error_log("createRequiredEmotions: ".pg_last_error(), 3 ,"debug_file.log");
			  exit;
			}else{
				
				while ($row = pg_fetch_row($resultEmoNames)){
					if ($emotionNameString == ""){
						$emotionNameString = $row[0];
					}else{
						$emotionNameString .= ",".$row[0];
					}
					
					if ($emotionString == ""){
						$emotionString = $row[1];
					}else{
						$emotionString .= ",".$row[1];
					}
				}
			}
		}
		
		$query = 'INSERT INTO "required_emotions_collcurrent"('.
						'"requiredemotions", "collplayers", "numberofrequiredemotions", "namesofrequiredemotions", "timestamp")'.
						'VALUES (\''.$emotionString.'\', \''.$collPlayers.'\', \''.$emotionNumberString.'\', \''.$emotionNameString.'\', \''.$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\');';
						
		$result = pg_query($query);
		
		if (!$result) {
			  die('Invalid query: ' . pg_last_error());
			   //DEBUG
			   error_log("createRequiredEmotions: ".pg_last_error(), 3 ,"debug_file.log");
		}
	
		$tempRequiredEmotionArray[] = array("requiredemotions"=>$emotionString);
		$tempRequiredEmotionArray[] = array("numberofrequiredemotions"=>$emotionNumberString);
		$tempRequiredEmotionArray[] = array("namesofrequiredemotions"=>$emotionNameString);
		
		/*// ADD TO XML DOCUMENT NODE
		$node = $dom->createElement("collaborationResult");  
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("requiredemotions", $tempRequiredEmotionArray[0]["requiredemotions"]);
		$newnode->setAttribute("numberofrequiredemotions", $tempRequiredEmotionArray[1]["numberofrequiredemotions"]);
		$newnode->setAttribute("namesofrequiredemotions", $tempRequiredEmotionArray[2]["namesofrequiredemotions"]);*/
		
		//print_r($tempRequiredEmotionArray);
		
		return $tempRequiredEmotionArray;
		
	}//If required emotions were already created
	else{
		
		$querySelect = 'SELECT "requiredemotions", "numberofrequiredemotions", "timestamp" FROM "required_emotions_collcurrent" '.
					'WHERE "collplayers"=\''.$collPlayers.'\';';
					
		$resultSelect = pg_query($querySelect);
		
		// 
		$queryEmoNames = 'SELECT namesofrequiredemotions FROM "required_emotions_collcurrent" WHERE "collplayers"=\''.$collPlayers.'\';';
		//error_log($queryEmoNames);
		
		$resultEmoNames = pg_query($connection, $queryEmoNames);
		
		if (!$resultEmoNames) {
		  echo "An error occured.\n". pg_last_error();
		  //DEBUG
		  error_log("createRequiredEmotions: ".pg_last_error(), 3 ,"debug_file.log");
		  exit;
		}
		while ($row = pg_fetch_row($resultEmoNames)){
			if ($emotionNameString == ""){
				$emotionNameString = $row[0];
			}else{
				$emotionNameString .= ",".$row[0];
			}
		}
		
		// Iterate through the rows, printing XML nodes for each
		while ($row = pg_fetch_row($resultSelect)){
			$tempRequiredEmotionArray[] = array("requiredemotions"=>$row[0]);
			$tempRequiredEmotionArray[] = array("numberofrequiredemotions"=>$row[1]);
			$tempRequiredEmotionArray[] = array("namesofrequiredemotions"=>$emotionNameString);
		}
		
		/*// ADD TO XML DOCUMENT NODE
		$node = $dom->createElement("collaborationResult");  
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("flowerpoints", $tempRequiredEmotionArray[0]["requiredemotions"]);
		$newnode->setAttribute("requiredemotions", $tempRequiredEmotionArray[1]["numberofrequiredemotions"]);
		$newnode->setAttribute("numberofrequiredemotions", $tempRequiredEmotionArray[2]["namesofrequiredemotions"]);*/
		
		return $tempRequiredEmotionArray;
	}
}

//echo $dom->saveXML();

?>