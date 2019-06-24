<?php
    require_once (dirname(__FILE__) .  "/includes/session.php");

    $currentEvent = $_GET['event'];
    $teamid = $_GET['team'];

    $eventTeams = $currentEvent."_teams";
    $eventInspections = $currentEvent."_inspections";

    $index = 0;
    $teamInspections;

    $sql = $mysqli->query("SELECT * FROM `".$eventInspections."` WHERE `teamid` = '$teamid' ORDER BY `modified_time` ASC");	
	while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
        $teamInspections[$index] = $row;
        $index++;
    }
    $sql = $mysqli->query("SELECT * FROM `".$eventTeams."` WHERE `teamid` = '$teamid'");
    $row = mysqli_fetch_assoc($sql);
    $returnData = [$teamInspections, $row['initial_inspector']];
    echo json_encode($returnData);
?>
