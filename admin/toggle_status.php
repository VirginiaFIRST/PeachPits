<?php
    /*********************
    Processes form submission for adding an event
    **********************/
    include dirname(__DIR__) . "/header.php";

    $eventid = protect($_POST['eventid']);
    $currentStatus = protect($_POST['currentStatus']);
    
    if($currentStatus=='Live'){
        $sql = $mysqli->query("UPDATE `events` SET `eventstatus` = 'Not Live' WHERE `eventid` = '$eventid'");
    }
    else if($currentStatus=='Not Live'){
        $sql = $mysqli->query("UPDATE `events` SET `eventstatus` = 'Live' WHERE `eventid` = '$eventid'");
    }
    
?>