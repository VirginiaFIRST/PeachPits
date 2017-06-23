<?php
    /*********************
    Runs when an event admin clicks the Approve link for a Event/Role Request
    **********************/
    
    include dirname(__DIR__) . "/header.php";

    $check = 0;

    //Get variables from the url
	$email = $_GET['user'];
    $email = base64_decode($email);
	$event = $_GET['eventReq'];
	$role = $_GET['role'];

    //Pull all info about that user from the database and update
    $sql = "SELECT * FROM `users` WHERE `email` LIKE '$email'";
    $query = $mysqli->query($sql);
    $row = mysqli_fetch_assoc($query);
    $eventStr = $row['events'];
    if($eventStr == 'No Event'){
        $eventStr = '';
    }

    $addStr = $role . '@' . $event;
    
    $eventStr .= ';' . $addStr;
    $eventStr = trim($eventStr,';');

    if($row['role'] != 'n/a'){
        $role = 'n/a';
    }
    else{
        $role = 'n/a';
    }

    $sql = $mysqli->query("UPDATE `users` SET `role` = '$role', `events` = '$eventStr' WHERE email = '$email'");
    
    //Delete the request from the database
    $sql = $mysqli->query("DELETE FROM requests WHERE email = '$email'");

    echo '<script type="text/javascript">window.location="admin/dashboard?event='.$currentEvent.'"</script></div>';
?>