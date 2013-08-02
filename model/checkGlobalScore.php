<?php header("Content-Type: text/xml; charset=UTF-8");

include("postgresql_dbinfo.php");

// Start XML file, create parent node
$dom = new DOMDocument("1.0", 'UTF-8');
$node = $dom->createElement("endstatus");
$parnode = $dom->appendChild($node); 

// Opens a connection to a postgresql server
$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password."";

$connection = pg_connect($conn_string);

if (!$connection) {
  die('Not connected : ' .pg_get_result($connection));
  //DEBUG
  error_log("checkGlobalScore: ".pg_get_result($connection), 3 ,"debug_file.log");
}

$querySelect = 'Select score, maxscore '.
   'FROM globalscore;';
   
$resultSelect = pg_query($querySelect);

if (!$resultSelect) {
	  die('Invalid query: ' . pg_last_error());
	  //DEBUG
  	  error_log("checkGlobalScore: ".pg_last_error(), 3 ,"debug_file.log");
}
else{
	
	$row = pg_fetch_assoc($resultSelect);
	$score = $row['score'];
	$maxscore = $row['maxscore'];
	
	if ($score >= $maxscore){
		
		/*$queryUpdate = 'UPDATE globalscore '.
   		'SET theend=1;';
		$resultUpdate = pg_query($queryUpdate);

		if (!$resultUpdate) {
	  		die('Invalid query: ' . pg_last_error());
			 //DEBUG
  	  		 error_log("checkGlobalScore: ".pg_last_error(), 3 ,"debug_file.log");
		}else{*/
			// ADD TO XML DOCUMENT NODE
		    $node = $dom->createElement("status");  
		    $newnode = $parnode->appendChild($node);
		    $newnode->setAttribute("end", "yes");
			
			//echo $dom->saveXML();
		//}
	}else{
		// ADD TO XML DOCUMENT NODE
		$node = $dom->createElement("status");  
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("end", "no");	
		
		//echo $dom->saveXML();
	}
	
echo $dom->saveXML();

}

?>
