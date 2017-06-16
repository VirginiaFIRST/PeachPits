<?php
require_once (dirname(__FILE__) .  "/includes/session.php");

global $sessionEmail;

if (isset($_SESSION['email'])){
    $sessionEmail = $_SESSION['email'];
}
//test
error_reporting(0);
$filePath = "http://" . $_SERVER['SERVER_NAME'] . "/peachpits/";
$currentEvent = $_GET['event'];
$teamid = $_GET['team'];

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
