<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("endofgame");
$parnode = $dom->appendChild($node); 


$playerID = $_GET['player_id'];


// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("getOptions: ".pg_get_result($connection), 3 ,"debug_file.log");
}
else{

	// Select all the rows in the markers table
	$query = 'SELECT id, flower_points, name FROM "players" ORDER BY flower_points DESC;';
	
	$result = pg_query($connection, $query);
	
	if (!$result) {
	  echo "An error occured.\n". pg_last_error();
	  //DEBUG
	  error_log("getOptions: ".pg_last_error(), 3 ,"debug_file.log");
	  exit;
	}
	
	$rank = 1;
	$myFlowerPoints = 0;
	$myEmoCount = 0;
	$myCollCount = 0;
	$myHugSend = 0;
	$myHugRecieved = 0;
	$topPlayer = "";
	$topPlayerFlowerPoints = 0;
	
	// Iterate through the rows
	while ($row = pg_fetch_row($result)){
		if ($rank == 1){
			$topPlayer = $row[2];
			$topPlayerFlowerPoints = intval($row[1]);
		}
		
		if ($row[0] == $playerID){
			$myFlowerPoints = intval($row[1]);
			break;
		}
		$rank++;
	}
	
	
	// Select all the rows in the markers table
	$query = 'SELECT count(*) as count_emo 
	FROM players_emo_statements_history
	WHERE player_id='.$playerID.';';
	
	$result = pg_query($connection, $query);
	
	if ($result) {
		$myEmoCountTemp = pg_fetch_assoc($result);
		$myEmoCount = intval($myEmoCountTemp['count_emo']);
		
	}
	
		
	// Select all the rows in the markers table
	$query = 'SELECT count(*) as count_hug_send 
	FROM players_hug_history
	WHERE sender='.$playerID.';';
	
	$result = pg_query($connection, $query);
	
	if ($result) {
		$myHugSendTemp = pg_fetch_assoc($result);
		$myHugSend = intval($myHugSendTemp['count_hug_send']);
	
	}
		
	// Select all the rows in the markers table
	$query = 'SELECT count(*) as count_hug_recieved 
	FROM players_hug_history
	WHERE reciever='.$playerID.';';
	
	$result = pg_query($connection, $query);
	
	if ($result) {
		$myHugRecievedTemp = pg_fetch_assoc($result);
		$myHugRecieved = intval($myHugRecievedTemp['count_hug_recieved']);
	}
		
	// Select all the rows in the markers table
	$query = 'SELECT count(id) FROM players_coll_history WHERE coll_players LIKE \'%!_'.$playerID.'\' escape \'!\' OR coll_players LIKE \''.$playerID.'!_%\' escape \'!\' OR coll_players LIKE \'%!_'.$playerID.'!_%\' escape \'!\'';
	
	$result = pg_query($connection, $query);
	
	if ($result) {
		$myCollCountTemp = pg_fetch_assoc($result);
		$myCollCount = intval($myCollCountTemp['count']);
	}
	
	
	// ADD TO XML DOCUMENT NODE
	$node = $dom->createElement("endofgamestatus");  
	$newnode = $parnode->appendChild($node);
	$newnode->setAttribute("my_flower_points", $myFlowerPoints );
	$newnode->setAttribute("my_ranking", $rank);
	$newnode->setAttribute("top_player", $topPlayer);
	$newnode->setAttribute("top_player_flower_points", $topPlayerFlowerPoints);
	$newnode->setAttribute("number_of_emotions", $myEmoCount);
	$newnode->setAttribute("number_of_collaborations", $myCollCount);
	$newnode->setAttribute("number_of_hugs_send", $myHugSend);
	$newnode->setAttribute("number_of_hugs_recieved", $myHugRecieved);
}
	
echo $dom->saveXML();

?>