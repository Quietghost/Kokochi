<?php header("Content-Type: text/xml; charset=UTF-8");

include("../model/postgresql_dbinfo.php");

$emoTable = $_GET["emo_table"];

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("Emotions");
$parnode = $dom->appendChild($node); 

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("getEmotions: ".pg_get_result($connection), 3 ,"debug_file.log");
}

// Select all the rows in the markers table
$query = 'SELECT * FROM '.$emoTable.' ORDER BY id';

$result = pg_query($connection, $query);

if (!$result) {
  echo "An error occured.\n". pg_last_error();
  //DEBUG
  error_log("getEmotions: ".pg_last_error(), 3 ,"debug_file.log");
  exit;
}

// Iterate through the rows, printing XML nodes for each
while ($row = pg_fetch_row($result)){
	
	if ($emoTable == "emotions"){
		$nameLength = strlen($row[1]);
		
		if ($nameLength > 7){
			
			$nameTempSub1 = substr($row[1], 0, ceil($nameLength/2));
			$nameTempSub2 = substr($row[1], ceil($nameLength/2),$nameLength);
			
			$name = $nameTempSub1." ".$nameTempSub2;
			
			// ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("emotion");  
			$newnode = $parnode->appendChild($node); 
			$newnode->setAttribute("id", $row[0]);
			$newnode->setAttribute("name", $name);
		}
		else{
			// ADD TO XML DOCUMENT NODE
			$node = $dom->createElement("emotion");  
			$newnode = $parnode->appendChild($node); 
			$newnode->setAttribute("id", $row[0]);
			$newnode->setAttribute("name", $row[1]);
		}
	}else{
		// ADD TO XML DOCUMENT NODE
		$node = $dom->createElement("emotion");  
		$newnode = $parnode->appendChild($node); 
		$newnode->setAttribute("id", $row[0]);
		$newnode->setAttribute("name", $row[1]);
	}
}

echo $dom->saveXML();

?>