<?php //header("Content-Type: text/xml; charset=UTF-8");

function getTimeoutStatus($playerID, $currentPlayerTimeTemp){

	include("../model/postgresql_dbinfo.php");
	
	$currentPlayerTimeArray = explode('~', $currentPlayerTimeTemp);
	$timestampString = $currentPlayerTimeArray[0].' '.$currentPlayerTimeArray[1];
	$currentPlayerTime = strtotime($timestampString);
	
	$status = "false";
	
	// Opens a connection to a postgresql server
	$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";
	
	$connection = pg_connect($conn_string);
	
	if (!$connection) {
	  die('Not connected : ' .pg_get_result($connection));
	  //DEBUG
	  error_log("getTimeoutstatus: ".pg_get_result($connection), 3 ,"debug_file.log");
	}
	
	$query = 'SELECT timestamp FROM players WHERE id='.$playerID;
	
	$result = pg_query($connection, $query);
	
	if (!$result) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getTimeoutstatus: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}
	
	// Iterate through the rows, printing XML nodes for each
	while ($row = pg_fetch_row($result)){
		
		if ($row[0] != ""){
			$oldTimestampArray = explode('+', $row[0]);
			$oldPlayerTime = strtotime($oldTimestampArray[0]);
			
			if ((intval($currentPlayerTime)-intval($oldPlayerTime)) > 1200){
				$status = "true";
			}else{
				$status = "false";
			}
		}else{
			$tempCurrentPlayerTime = 0;
			$oldPlayerTime = 0;
			$status = "true";
		}
	}
	return $status;
}
?>