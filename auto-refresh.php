<?php 
    //header("Content-Type: application/json", true);
    require_once (dirname(__FILE__) .  "/includes/session.php");
    
    global $sessionEmail;
    
    if (isset($_SESSION['email'])){
        $sessionEmail = $_SESSION['email'];
    }
    
    error_reporting(0);
    $filePath = "http://" . $_SERVER['SERVER_NAME'] . "/peachpits/";
    $currentEvent = $_GET['event'];
    
    //Fetch some general information about the user from the database for later use
    $sql = $mysqli->query("SELECT * FROM `users` WHERE `email`='$sessionEmail'");
    $row = mysqli_fetch_assoc($sql);
    
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $superRole = $row['role'];
    $events = $row['events'];
    $eventsArr = explode(';', $events);

    if($currentEvent != '' && isset($_SESSION['email'])){
        $sqlEvents = $mysqli->query("SELECT * FROM `events` WHERE `eventid` = '$currentEvent'");
        $rowEvents = mysqli_fetch_assoc($sqlEvents);
        //$index = in_array('Event', $eventsArr);
        foreach($eventsArr as $index => $string) {
            if (strpos($string, $rowEvents['eventname']) !== FALSE){
                $index;
                break;
            }
        }
        $str = $eventsArr[$index];
        $arr = explode('@',$str);
        $role = $arr[0];
    }
    else if ($currentEvent == '' && isset($_SESSION['email'])){
        $role = 'None selected';
    }
    
    if(($role == 'No Event' || $role == 'None selected') && $superRole == 'Super Admin'){
        $role = 'Super Admin';
    }
    
    //Checks if a user is a super admin
    function isSuperAdmin($role){
        if($role == "Super Admin"){
            return true;
        }
    }    
    //Checks if a user is an event admin
    function isEventAdmin($role){
        if($role == "Event Admin"){
            return true;
        }
    }  
    //Checks if a user is a lead inspector
    function isLeadInspector($role){
        if($role == "Lead Inspector"){
            return true;
        }
    }
    //Checks if a user is an inspector
    function isInspector($role){
        if($role == "Inspector"){
            return true;
        }
    }
   
    $event = $currentEvent."_teams";

	$i = 0;
	$inspectStatuses;
			
	$sqlTeams = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus, e.inspectionnotes, e.initial_inspector, e.last_modified_by, e.last_modified_time FROM `".$event."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
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
