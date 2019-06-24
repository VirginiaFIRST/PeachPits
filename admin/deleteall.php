<?php    
    include dirname(__DIR__) . "/header.php";

    $sql = $mysqli->query("SELECT * FROM `events`");
    while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
        $sql2 = $mysqli->query("DROP TABLE `".$row['eventid']."_teams`");
		$sql2 = $mysqli->query("DROP TABLE `".$row['eventid']."_inspections`");
	    $sql2 = $mysqli->query("DROP TABLE `".$row['eventid']."_matches`");
        $sql2 = $mysqli->query("DROP TABLE `".$row['eventid']."_announcements`");
        $sql2 = $mysqli->query("DROP TABLE `".$row['eventid']."_messages`");
        $sql2 = $mysqli->query("DROP TABLE `".$row['eventid']."_groups`");
        $sql2 = $mysqli->query("DROP TABLE `".$row['eventid']."_activity`");
        $sql2 = $mysqli->query("DROP TABLE `".$row['eventid']."_liaisons`");
    }	

    $sql = $mysqli->query("DELETE FROM `events`");

?>