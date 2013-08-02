<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");
include("../util/sortMultiArray.php");

$playerID = $_GET["player_id"];
/*$lat = $_GET["lat"];
$lng = $_GET["lng"];*/

/*$myGeom = "POINT (".$lng." ".$lat.")";
$myLocation = geoPHP::load($myGeom, 'wkt');

$idDistArray = array();*/

$allPlayers = array();

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("players");
$parnode = $dom->appendChild($node); 

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("getAllPlayersWithLocationTimestamp: ".pg_get_result($connection), 3 ,"debug_file.log");
}

//////////////////////////////////////////////////////////////////////////////////////////////////

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
  $node = $dom->createElement("player");  
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id", $allPlayersSortedZero[$i]['player_id']);
  $newnode->setAttribute("name", $allPlayersSortedZero[$i]['name']);
  $newnode->setAttribute("flower_points", $allPlayersSortedZero[$i]['flower_points']);
  //$newnode->setAttribute("dist", $idDistArray[$key]);

}

  /*if ($row[2] != ""){
	  $collPlayerGeom = $row[2];
	  $collPlayerLocation = geoPHP::load($collPlayerGeom,'wkt');
	  
	  $startPoint = array( 'latitude'	=> $collPlayerLocation->getY(),
			 'longitude'	=> "".$collPlayerLocation->getX()
			   );
	  $endPoint = array( 'latitude'		=> $myLocation->getY(),
			'longitude'	=> "".$myLocation->getX()
			 );
	
	  $uom = 'm';
	  $dist = calculateDistanceFromLatLong ($startPoint,$endPoint,$uom);
	  
	 
	  $length = strpos($dist,".");
	  
	  $dist1 = substr($dist,0,$length);
	  $dist2 = substr($dist,$length,3);
	  
	  $dist = $dist1.$dist2;
	  
	  $idDistArray[$row[0]] = $dist;
  }else{
	  $dist = 0;
	  
	  $idDistArray[$row[0]] = $dist;
  }
}

///////////////////////////////////////////////////////////////////////////////////////////////////7


// Iterate through the rows, printing XML nodes for each
asort($idDistArray);*/

echo $dom->saveXML();

?>