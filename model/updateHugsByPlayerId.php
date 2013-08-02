<?php header("Content-Type: text/xml; charset=UTF-8");

include("postgresql_dbinfo.php");

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("hugPlayer");
$parnode = $dom->appendChild($node);

// Gets data from URL parameters
$playerID = $_GET['player_id'];
$requiredHugTimestamp = $_GET['timestamp'];
$me = $_GET["me"];
$lat = $_GET["lat"];
$lng = $_GET["lng"];

$timestampArray = explode('~', $requiredHugTimestamp);
$timestampString = $timestampArray[0].' '.$timestampArray[1];
$tempCurrentPlayerTime = strtotime($timestampString);


// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
}


$queryPlayerName = 'SELECT name FROM "players" WHERE id='.$playerID;

$resultPlayerName = pg_query($connection, $queryPlayerName);

if (!$resultPlayerName) {
  echo "An error occured.\n". pg_last_error();
  //DEBUG
  error_log("getPlayerNameById: ".pg_last_error(), 3 ,"debug_file.log");
  exit;
}

$rowPlayerName = pg_fetch_assoc($resultPlayerName);

$hugedPlayerName = $rowPlayerName['name'];


$query = 'SELECT last_hug FROM players WHERE id='.$me;

$result = pg_query($connection, $query);

if (!$result) {
  //echo "An error occured.\n". pg_last_error();
  //DEBUG
  error_log("getTimeoutstatus: ".pg_last_error(), 3 ,"debug_file.log");
  exit;
}else{
	
	$row = pg_fetch_assoc($result);
	
	if ($row["last_hug"] != ""){
		//echo "huged before";
		$oldTimestampArray = explode('+', $row["last_hug"]);
		$oldPlayerTime = strtotime($oldTimestampArray[0]);
		
		if (($tempCurrentPlayerTime-$oldPlayerTime) >= 86400){
			//echo "huged before greater than one day";
			$querySelect = 'Select hugs '.
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
				$newScoreHugs = $myrow['hugs'] + 1;	
				
				$queryUpdate = 'UPDATE players '.
					   'SET hugs='.$newScoreHugs.
					 ' WHERE id='.$playerID.';';
				$resultUpdate = pg_query($queryUpdate);
						
				if (!$resultUpdate) {
					die('Invalid query: ' . pg_last_error());
					//DEBUG
					error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
				}
			}
			
			$querySelect = 'Select flower_points '.
				   'FROM players '.
				 'WHERE id='.$playerID.';';
			$resultSelect = pg_query($querySelect);
					
			if (!$resultSelect) {
				die('Invalid query: ' . pg_last_error());
				//DEBUG
				error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
					
			$myrow = pg_fetch_assoc($resultSelect);
			$myFlowerPoints = $myrow['flower_points'];
					
			$newScoreFlowerPoints = intval($myFlowerPoints) + 4;	
					
			$queryUpdate = 'UPDATE players '.
					   'SET flower_points='.$newScoreFlowerPoints.
					 ' WHERE id='.$playerID.';';
			$resultUpdate = pg_query($queryUpdate);
					
			if (!$resultUpdate) {
				die('Invalid query: ' . pg_last_error());
				//DEBUG
				error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
			
			$queryUpdate = 'UPDATE players '.
			'SET "last_hug"=\''.$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\''.
			' WHERE id='.$me.';';
		 
			$resultUpdate = pg_query($queryUpdate);
			
			if (!$resultUpdate) {
				  die('Invalid query: ' . pg_last_error());
				  //DEBUG
				  error_log("updatePlayerScore: ".pg_last_error(), 3 ,"debug_file.log");
			}
			$querySelect = 'Select current_hug_base '.
				   'FROM players '.
				 'WHERE id='.$me.';';
			$resultSelect = pg_query($querySelect);
					
			if (!$resultSelect) {
				die('Invalid query: ' . pg_last_error());
				//DEBUG
				error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
					
			$myrow = pg_fetch_assoc($resultSelect);
			$myCurrentHugs = intval($myrow['current_hug_base']);
			
			$newScoreHugBase = 9;	
	
			$queryUpdate = 'UPDATE players '.
					   'SET current_hug_base='.$newScoreHugBase.
					 ' WHERE id='.$me.';';
			$resultUpdate = pg_query($queryUpdate);
					
			if (!$resultUpdate) {
				die('Invalid query: ' . pg_last_error());
				//DEBUG
				error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
			
			if ($lat != "" && $lng != ""){
				$queryHistoryInsert = 'INSERT INTO players_hug_history(
            		sender, reciever, "timestamp", "location")
   					VALUES ('.$me.', '.$playerID.', \''.$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\', ST_GeomFromText(\'POINT('.$lng.' '.$lat.')\'));';
			}
			else{
				$queryHistoryInsert = 'INSERT INTO players_hug_history(
            		sender, reciever, "timestamp")
   					VALUES ('.$me.', '.$playerID.', \''.$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\');';
			}
			
			$resultHistoryInsert = pg_query($queryHistoryInsert);
					
			if (!$resultHistoryInsert) {
				die('Invalid query: ' . pg_last_error());
				//DEBUG
				error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
			
			// ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("hugsLeft");  
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("hugsLeftForPlayer", $newScoreHugBase);
			$newnode->setAttribute("hugedPlayerName", $hugedPlayerName);
		
			
		}//Player makes the hug at the same day
		else{
			//echo "same day";
			$querySelect = 'Select current_hug_base '.
				   'FROM players '.
				 'WHERE id='.$me.';';
			$resultSelect = pg_query($querySelect);
					
			if (!$resultSelect) {
				die('Invalid query: ' . pg_last_error());
				//DEBUG
				error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
					
			$myrow = pg_fetch_assoc($resultSelect);
			$myCurrentHugs = intval($myrow['current_hug_base']);
			
			if ($myCurrentHugs > 0){
				//echo "same day hugs > 0";
				
				$querySelect = 'Select hugs '.
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
					$newScoreHugs = intval($myrow['hugs']) + 1;	
					
					$queryUpdate = 'UPDATE players '.
						   'SET hugs='.$newScoreHugs.
						 ' WHERE id='.$playerID.';';
					$resultUpdate = pg_query($queryUpdate);
							
					if (!$resultUpdate) {
						die('Invalid query: ' . pg_last_error());
						//DEBUG
						error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
					}
				}
				
				$querySelect = 'Select flower_points '.
				   'FROM players '.
				 'WHERE id='.$playerID.';';
				$resultSelect = pg_query($querySelect);
						
				if (!$resultSelect) {
					die('Invalid query: ' . pg_last_error());
					//DEBUG
					error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
				}
						
				$myrow = pg_fetch_assoc($resultSelect);
				$myFlowerPoints = $myrow['flower_points'];
						
				$newScoreFlowerPoints = intval($myFlowerPoints) + 4;	
						
				$queryUpdate = 'UPDATE players '.
						   'SET flower_points='.$newScoreFlowerPoints.
						 ' WHERE id='.$playerID.';';
				$resultUpdate = pg_query($queryUpdate);
						
				if (!$resultUpdate) {
					die('Invalid query: ' . pg_last_error());
					//DEBUG
					error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
				}
				
				$newScoreHugBase = $myCurrentHugs -1;	
	
				$queryUpdate = 'UPDATE players '.
					   'SET current_hug_base='.$newScoreHugBase.
					 ' WHERE id='.$me.';';
				$resultUpdate = pg_query($queryUpdate);
					
				if (!$resultUpdate) {
					die('Invalid query: ' . pg_last_error());
					//DEBUG
					error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
				}
				
				if ($lat != "" && $lng != ""){
					$queryHistoryInsert = 'INSERT INTO players_hug_history(
						sender, reciever, "timestamp", "location")
						VALUES ('.$me.', '.$playerID.', \''.$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\', ST_GeomFromText(\'POINT('.$lng.' '.$lat.')\'));';
				}
				else{
					$queryHistoryInsert = 'INSERT INTO players_hug_history(
						sender, reciever, "timestamp")
						VALUES ('.$me.', '.$playerID.', \''.$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\');';
				}
				
				$resultHistoryInsert = pg_query($queryHistoryInsert);
					
				if (!$resultHistoryInsert) {
					die('Invalid query: ' . pg_last_error());
					//DEBUG
					error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
				}
					
				// ADD TO XML DOCUMENT NODE
				$node = $dom->createElement("hugsLeft");  
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("hugsLeftForPlayer", $newScoreHugBase);
				$newnode->setAttribute("hugedPlayerName", $hugedPlayerName);
			
			}//Player has no hugs for this day left
			else{
				//echo "no hugs left";
				// ADD TO XML DOCUMENT NODE
				$node = $dom->createElement("hugsLeft");  
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("hugsLeftForPlayer", "-1");
				$newnode->setAttribute("hugedPlayerName", $hugedPlayerName);
			}
		}
	}//Player has never huged someone else before
	else{
		//echo "never";
		$querySelect = 'Select hugs '.
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
			$newScoreHugs = $myrow['hugs'] + 1;	
			
			$queryUpdate = 'UPDATE players '.
				   'SET hugs='.$newScoreHugs.
				 ' WHERE id='.$playerID.';';
			$resultUpdate = pg_query($queryUpdate);
					
			if (!$resultUpdate) {
				die('Invalid query: ' . pg_last_error());
				//DEBUG
				error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
			}
		}
		
		$querySelect = 'Select flower_points '.
				   'FROM players '.
				 'WHERE id='.$playerID.';';
		$resultSelect = pg_query($querySelect);
				
		if (!$resultSelect) {
			die('Invalid query: ' . pg_last_error());
			//DEBUG
			error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
		}
				
		$myrow = pg_fetch_assoc($resultSelect);
		$myFlowerPoints = $myrow['flower_points'];
				
		$newScoreFlowerPoints = intval($myFlowerPoints) + 4;	
				
		$queryUpdate = 'UPDATE players '.
				   'SET flower_points='.$newScoreFlowerPoints.
				 ' WHERE id='.$playerID.';';
		$resultUpdate = pg_query($queryUpdate);
				
		if (!$resultUpdate) {
			die('Invalid query: ' . pg_last_error());
			//DEBUG
			error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
		}
		
		$queryUpdate = 'UPDATE players '.
		'SET "last_hug"= \''.$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\''.
		' WHERE id='.$me.';';
	 
		$resultUpdate = pg_query($queryUpdate);
		
		if (!$resultUpdate) {
			  die('Invalid query: ' . pg_last_error());
			  //DEBUG
			  error_log("updatePlayerScore: ".pg_last_error(), 3 ,"debug_file.log");
		}
		
		$querySelect = 'Select current_hug_base '.
				   'FROM players '.
				 'WHERE id='.$me.';';
		$resultSelect = pg_query($querySelect);
				
		if (!$resultSelect) {
			die('Invalid query: ' . pg_last_error());
			//DEBUG
			error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
		}
				
		$myrow = pg_fetch_assoc($resultSelect);
		$myCurrentHugs = intval($myrow['current_hug_base']);
		
		$newScoreHugBase = 9;	

		$queryUpdate = 'UPDATE players '.
				   'SET current_hug_base='.$newScoreHugBase.
				 ' WHERE id='.$me.';';
		$resultUpdate = pg_query($queryUpdate);
				
		if (!$resultUpdate) {
			die('Invalid query: ' . pg_last_error());
			//DEBUG
			error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
		}
		
		if ($lat != "" && $lng != ""){
			$queryHistoryInsert = 'INSERT INTO players_hug_history(
            	sender, reciever, "timestamp", "location")
   				VALUES ('.$me.', '.$playerID.', \''.$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\', ST_GeomFromText(\'POINT('.$lng.' '.$lat.')\'));';
		}
		else{
			$queryHistoryInsert = 'INSERT INTO players_hug_history(
            	sender, reciever, "timestamp")
   				VALUES ('.$me.', '.$playerID.', \''.$timestampArray[0].' '.$timestampArray[1].'+'.$timestampArray[2].'\');';
		}
			
		$resultHistoryInsert = pg_query($queryHistoryInsert);
					
		if (!$resultHistoryInsert) {
			die('Invalid query: ' . pg_last_error());
			//DEBUG
			error_log("updatePlayerPowerDecrease: ".pg_last_error(), 3 ,"debug_file.log");
		}
		
		// ADD TO XML DOCUMENT NODE
		$node = $dom->createElement("hugsLeft");  
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("hugsLeftForPlayer", $newScoreHugBase);
		$newnode->setAttribute("hugedPlayerName", $hugedPlayerName);
	
	}
}

echo $dom->saveXML();
?>