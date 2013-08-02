<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");
include("../util/sortMultiArray.php");

$playerID = $_GET['player_id'];

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("collPlayers");
$parnode = $dom->appendChild($node); 

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("getCollPlayersHistory: ".pg_get_result($connection), 3 ,"debug_file.log");
}

$query = 'SELECT playerid, playername FROM (SELECT A1.player_id_you as playerid, 
			A2.name as playername, A1.other_player as other_player
			FROM "collPlayers" A1, players A2
			WHERE A2.id = A1.player_id_you) as Temp WHERE other_player='.$playerID.'
			UNION
			SELECT playerid, playername FROM (SELECT A1.other_player as playerid, 
			A2.name as playername, A1.player_id_you as player_id_you
			FROM "collPlayers" A1, players A2
			WHERE A2.id = A1.other_player) as Temp WHERE player_id_you='.$playerID;

$result = pg_query($connection, $query);

if (!$result) {
  echo "An error occured.\n". pg_last_error();
  //DEBUG
  error_log("getCollPlayersHistory: ".pg_last_error(), 3 ,"debug_file.log");
  exit;
}

// Iterate through the rows, printing XML nodes for each
while ($row = pg_fetch_row($result)){
	
 // ADD TO XML DOCUMENT NODE
  $node = $dom->createElement("playerBlocked");  
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id", $row[0]);
  $newnode->setAttribute("name", $row[1]);

}

$query = 'SELECT id, name, ST_AsText(coord_last_emotion), flower_points FROM "players" WHERE NOT id='.$playerID;

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
		
  	$allPlayers[] = array("player_id"=>$row[0],"name"=>$row[1],"flower_points"=>$row[3]);
 
}

$allPlayersSorted = array_sort($allPlayers,'flower_points', SORT_DESC);

$allPlayersSortedZero = array_values($allPlayersSorted);

for($i = 0; $i < sizeof($allPlayersSorted); ++$i){
    
  // ADD TO XML DOCUMENT NODE
  $node = $dom->createElement("playerAll");  
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id", $allPlayersSortedZero[$i]['player_id']);
  $newnode->setAttribute("name", $allPlayersSortedZero[$i]['name']);
  $newnode->setAttribute("flower_points", $allPlayersSortedZero[$i]['flower_points']);
  //$newnode->setAttribute("dist", $idDistArray[$key]);

}


echo $dom->saveXML();

?>