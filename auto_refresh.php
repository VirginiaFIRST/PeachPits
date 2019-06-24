<?php 
    //header("Content-Type: application/json", true);
    require_once (dirname(__FILE__) .  "/includes/session.php");
    
    global $sessionEmail;
    
    if (isset($_SESSION['email'])){
        $sessionEmail = $_SESSION['email'];
    }
    
    $currentEvent = $_GET['event'];
   
    $eventTeams = $currentEvent."_teams";

	$i = 0;
	$inspectStatuses;
			
	$sqlTeams = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus, e.inspectionnotes, e.initial_inspector, e.last_modified_by, e.last_modified_time FROM `".$eventTeams."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
	while($rowTeams = mysqli_fetch_array($sqlTeams, MYSQLI_BOTH)){
		$inspectStatuses[$i][0] = $rowTeams['teamid']; 
		$inspectStatuses[$i][1] = $rowTeams['teamname']; 
		$inspectStatuses[$i][2] = $rowTeams['location']; 
		$inspectStatuses[$i][3] = $rowTeams['inspectionstatus']; 
		$inspectStatuses[$i][4] = $rowTeams['inspectionnotes']; 
		$inspectStatuses[$i][5] = $rowTeams['initial_inspector']; 
		$inspectStatuses[$i][6] = $rowTeams['last_modified_by']; 
		$inspectStatuses[$i][7] = $rowTeams['last_modified_time']; 
		$i = $i + 1;
	}
	echo json_encode($inspectStatuses);
?>	
