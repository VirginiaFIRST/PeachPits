<?php
	
	$eventTeams = $currentEvent."_teams";
	$sqlTeams = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
	$row = mysqli_fetch_assoc($sql);
	$eventName_teams = $row['eventname'];
	
	$event = $currentEvent."_matches";	
	$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
	$row = mysqli_fetch_assoc($sql);
	$eventName_matches = $row['eventname'];
	
	$eventMatches = $currentEvent."_matches";
	$sqlMatches = $mysqli->query("SELECT * FROM `".$eventMatches."` WHERE `matchtype` LIKE 'qm' ORDER BY matchnumber ASC");
	
	$sql = $mysqli->query("SELECT `mapcode` FROM `maps` WHERE `eventid` LIKE '$currentEvent'");
	$row = mysqli_fetch_assoc($sql);
		
?>