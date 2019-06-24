<?php
    require_once (dirname(__FILE__) .  "/includes/session.php");

    $currentEvent = $_GET['event'];
    $teamid = $_GET['team'];

    $event = $currentEvent."_matches";

    $i = 0;
    $teamSchd;

    $sqlTeams = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchtype` LIKE 'qm' AND `red1` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND `red2` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND `red3` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND`blue1` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND `blue2` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND `blue3` LIKE '$teamid' ORDER BY matchnumber ASC");
    while($rowTeams = mysqli_fetch_array($sqlTeams, MYSQLI_BOTH)){
        $teamSchd[$i] = $rowTeams;
        $i = $i + 1;
    }
    echo json_encode($teamSchd);
?>
