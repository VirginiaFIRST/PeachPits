<?php    
    include dirname(__DIR__) . "/header.php";
    
	$remove = $_POST['removeTeam'];
  
    $sql = $mysqli->query("DELETE FROM `".$currentEvent."_teams` WHERE `teamid` = '$remove'");
?>