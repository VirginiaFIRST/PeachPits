<?php    
    include dirname(__DIR__) . "/header.php";

    $sql = $mysqli->query("SELECT * FROM `events`");
    while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
        $eventid = $row['eventid'];
        $sql = $mysqli->query("DELETE FROM `".$eventid."_teams`");
        $sql = $mysqli->query("DELETE FROM `".$eventid."_inspections`");
	    $sql = $mysqli->query("DELETE FROM `".$eventid."_matches`");
        $sql = $mysqli->query("DELETE FROM `".$eventid."_announcements`");
        $sql = $mysqli->query("DELETE FROM `".$eventid."_messages`");
        $sql = $mysqli->query("DELETE FROM `".$eventid."_groups`");
        $sql = $mysqli->query("DELETE FROM `".$eventid."_activity`");
        $sql = $mysqli->query("DELETE FROM `".$eventid."_liaisons`");
        $sql = $mysqli->query("DELETE * FROM `maps` WHERE `eventid` = '$eventid'");
    }	
?>