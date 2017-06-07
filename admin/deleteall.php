<?php    
    include dirname(__DIR__) . "/header.php";

    $sql = $mysqli->query("SELECT * FROM `events`");
    while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
        $sql = $mysqli->query("DROP TABLE `".$row['eventid']."_teams`");
	    $sql = $mysqli->query("DROP TABLE `".$row['eventid']."_matches`");
    }	

    $sql = $mysqli->query("DELETE FROM `events`");
?>