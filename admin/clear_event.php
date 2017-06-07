<?php    
    include dirname(__DIR__) . "/header.php";
	
	$sql = $mysqli->query("DELETE FROM `".$currentEvent."_teams`");
	$sql = $mysqli->query("DELETE FROM `".$currentEvent."_matches`");
	$sql = $mysqli->query("DELETE * FROM `maps` WHERE `eventid` = '$currentEvent'");
?>