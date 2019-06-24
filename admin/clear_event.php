<?php    
    include dirname(__DIR__) . "/header.php";
	
	$sql = $mysqli->query("DELETE FROM `".$currentEvent."_teams`");
	$sql = $mysqli->query("DELETE FROM `".$currentEvent."_inspections`");
	$sql = $mysqli->query("DELETE FROM `".$currentEvent."_matches`");
	$sql = $mysqli->query("DELETE FROM `".$currentEvent."_announcements`");
	$sql = $mysqli->query("DELETE FROM `".$currentEvent."_messages`");
	$sql = $mysqli->query("DELETE FROM `".$currentEvent."_groups`");
	$sql = $mysqli->query("DELETE FROM `".$currentEvent."_activity`");
	$sql = $mysqli->query("DELETE FROM `".$currentEvent."_liaisons`");
	$sql = $mysqli->query("DELETE * FROM `maps` WHERE `eventid` = '$currentEvent'");
?>