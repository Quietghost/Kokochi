<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");
include("../model/updatePlayerPowerDecrease.php");

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("rewardPlayers");
$parnode = $dom->appendChild($node);


$flowerpoints = $_GET['flowerpoints'];
$collPlayerIdStringTable = $_GET['collPlayerIdStringTable'];
$collPlayerIdString = $_GET['collPlayerIdString'];

$collPlayerArray = explode("~", $collPlayerIdString);
$collPlayerCondition = str_replace("~","",$collPlayerIdString);

$collPlayersReward = array();

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";
				
$connection = pg_connect($conn_string);
			
if (!$connection) {
	die('Not connected : ' .pg_get_result($connection));
	//DEBUG
	error_log("getCollCurrentEmotions: ".pg_get_result($connection), 3 ,"debug_file.log");
}else{
	
	$queryTable = 'SELECT COUNT(*) FROM pg_tables WHERE tablename = \''.$collPlayerIdStringTable.'\';';
	$resultTable = pg_query($queryTable);

	if (!$resultTable) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
	  error_log("setCollCurrentEmotion: ".pg_last_error(), 3 ,"debug_file.log");
	}else
	{
		$statusTable = pg_fetch_assoc($resultTable);

		if ($statusTable['count'] != "0"){
		
			$queryChecked = 'SELECT checked FROM '.$collPlayerIdStringTable.';';
			$resultChecked = pg_query($queryChecked);
	
			if (!$resultChecked) {
				die('Invalid query: ' . pg_last_error());
				//DEBUG
				error_log("setCollCurrentEmotion: ".pg_last_error(), 3 ,"debug_file.log");
			}else
			{
				$statusChecked = pg_fetch_assoc($resultChecked);
				
				//echo "Check Status: ".$statusChecked['checked'];
				if ($statusChecked['checked'] == "0"){
					
					$queryUpdateChecked = 'UPDATE "'.$collPlayerIdStringTable.'"
					SET checked=1;';
								
					$resultUpdateChecked = pg_query($queryUpdateChecked);
								
					if (!$resultUpdateChecked) {
						die('Invalid query: ' . pg_last_error());
					}else
					{ 
					
						$query = 'SELECT * FROM '.$collPlayerIdStringTable;
						
						$result = pg_query($connection, $query);
						
						if (!$result) {
						  echo "An error occured.\n". pg_last_error();
						  //DEBUG
						  error_log("getCollCurrentEmotions: ".pg_last_error(), 3 ,"debug_file.log");
						  exit;
						}
						
						while ($row = pg_fetch_row($result)){
							if (intval($row[0]) != 0){//Ignore the dummy entry
								$collPlayersReward[] = array("player_id"=>$row[0],"emotion_id"=>$row[1],"number"=>$row[2]);
							}
						}
						//print_r($collPlayersReward);
						$decreaseResult = decreasePlayerPowers($collPlayersReward);
						
						if ($decreaseResult){
							
							$queryDropTable = 'DROP TABLE "'.$collPlayerIdStringTable.'";';
							$resultDropTable = pg_query($queryDropTable);
						
							if (!$resultDropTable) {
							  die('Invalid query: ' . pg_last_error());
							  //DEBUG
							  error_log("setCollCurrentEmotion: ".pg_last_error(), 3 ,"debug_file.log");
							}else
							{}
						}
					}
					
					if (intval($flowerpoints) == 0){//Players have all the required emotions (type and number)
						
						increasePlayerFlowerPoints($collPlayerArray);
						
					}//Flower points > 0
					else
					{
						$minFlowerPoints = intval(intval($flowerpoints)/intval(count($collPlayerArray)));
						//echo "minFlowerPoints: ".$minFlowerPoints;
						decreasePlayerFlowerPoints($collPlayerArray, $minFlowerPoints);
					}
					
					$query = 'DELETE FROM "required_emotions_collcurrent" WHERE collplayers = \''.$collPlayerCondition.'\'; ';

					$result = pg_query($query);
					
					if (!$result) {
						  die('Invalid query: ' . pg_last_error());
						  //DEBUG
						  error_log("deleteRequiredEmotionsTableEntries: ". pg_last_error(), 3 ,"debug_file.log");
					}
					
					// ADD TO XML DOCUMENT NODE
					$node = $dom->createElement("collPlayerUpdate");  
					$newnode = $parnode->appendChild($node);
					$newnode->setAttribute("result", "finished");
					
				}//Somebody checked it already
				else{
					// ADD TO XML DOCUMENT NODE
					$node = $dom->createElement("collPlayerUpdate");  
					$newnode = $parnode->appendChild($node);
					$newnode->setAttribute("result", "finished");
				}
			}
		}//Somebody deleted the table already
		else{
			// ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("collPlayerUpdate");  
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("result", "finished");
		}
	}
}

echo $dom->saveXML();

?>