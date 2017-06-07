<?php    
    include dirname(__DIR__) . "/header.php";
    
	$delete = $_POST['eventDelete'];
  
    $sql = $mysqli->query("DELETE FROM `events` WHERE `eventid` = '$delete'");
	
	$sql = $mysqli->query("DROP TABLE `".$delete."_teams`");
	$sql = $mysqli->query("DROP TABLE `".$delete."_matches`");
?>