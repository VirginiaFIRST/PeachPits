<?php
    /*********************
    Runs when an event admin clicks the Deny link for a Event/Role Request
    **********************/
    
    include dirname(__DIR__) . "/header.php";

    $check = 0;

    //Get variables from the url
	$email = $_GET['user'];
    $email = base64_decode($email);
	$event = $_GET['eventReq'];
	$role = $_GET['role'];

    //$sql = $mysqli->query("UPDATE `requests` SET `status` = 'Denied' WHERE `email` = '$email' AND `event` = '$event' AND (`existingrole` = '$role' OR `requestedrole` = '$role')");

    //Delete the request from the database
    $sql = $mysqli->query("DELETE FROM `requests` WHERE `email` = '$email' AND `event` = '$event' AND (`existingrole` = '$role' OR `requestedrole` = '$role')");
    
    echo '<script type="text/javascript">window.location="admin/dashboard.php?event='.$currentEvent.'"</script></div>';
?>