<?php 
include("../model/postgresql_dbinfo.php");

// Gets data from URL parameters
$me = $_GET['player_id'];

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("setCollCurrentEmotion: ".pg_get_result($connection), 3 ,"debug_file.log");
}

//echo "collPlayerString: ".$collPlayerString;

$queryTable = "SELECT COUNT(*) FROM pg_tables WHERE tablename LIKE 'collplayers_%".$me."%';";
$resultTable = pg_query($queryTable);

if (!$resultTable) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
      error_log("setCollCurrentEmotion: ".pg_last_error(), 3 ,"debug_file.log");
}else{
	$statusTable = pg_fetch_assoc($resultTable);
}

if ($statusTable['count'] == "0"){
}else{
	$queryOldTable = "SELECT tablename FROM pg_tables WHERE tablename LIKE 'collplayers_%".$me."%';";
	$resultOldTable = pg_query($queryOldTable);
	
	if (!$resultOldTable) {
		  die('Invalid query: ' . pg_last_error());
		  //DEBUG
		  error_log("setCollCurrentEmotion: ".pg_last_error(), 3 ,"debug_file.log");
	}else{
		while ($row = pg_fetch_row($resultOldTable)){
			$collPlayerString = "";
			
			$collPlayerStringTemp = explode("_",$row[0]);
			
			for($i = 1, $size = sizeof($collPlayerStringTemp); $i < $size; ++$i) {
				if ($i == 1){
					$collPlayerString .= $collPlayerStringTemp[$i];
				}else{
					$collPlayerString .= "_".$collPlayerStringTemp[$i];
				}
			}
			//echo 'DELETE FROM "required_emotions_collcurrent" WHERE collplayers = \''.$collPlayerString.'\'; ';
			
			$queryDropTable = 'DROP TABLE "'.$row[0].'";';
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
					
			for($i = 1, $size = sizeof($collPlayerStringTemp); $i < $size; ++$i)
			{
				$query = 'DELETE FROM "player_'.$collPlayerStringTemp[$i].'_collCurrent";';
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