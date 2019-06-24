<?php    
    include dirname(__DIR__) . "/header.php";
    
	$delete = $_POST['eventDelete'];
  
    $sql = $mysqli->query("DELETE FROM `events` WHERE `eventid` = '$delete'");
	
	$sql = $mysqli->query("DROP TABLE `".$delete."_teams`");
	$sql = $mysqli->query("DROP TABLE `".$delete."_inspections`");
	$sql = $mysqli->query("DROP TABLE `".$delete."_matches`");
	$sql = $mysqli->query("DROP TABLE `".$delete."_announcements`");
	$sql = $mysqli->query("DROP TABLE `".$delete."_messages`");
	$sql = $mysqli->query("DROP TABLE `".$delete."_groups`");
	$sql = $mysqli->query("DROP TABLE `".$delete."_activity`");
	$sql = $mysqli->query("DROP TABLE `".$delete."_liaisons`");
?>