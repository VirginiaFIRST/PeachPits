<?php
    /*********************
    Processes a join event form submission
    **********************/  
    include dirname(__DIR__) . "/header.php";

    //$requestedRole = protect($_POST['roleChange']);
    $joinevent = protect($_POST['joinevent']);
    
    $existingRole = "";
    
    $status = "Pending";
    $type = "Event";
    
    $errors = array();

    if(!$joinevent){
        $errors[] = "You did not fill out the required fields";
    }

    if(in_array($joinevent, $eventsArr)){
        $errors[] = "You have already joined this event!";
    }

    if(count($errors) > 0){
        echo "The following errors occured with your registration";
        echo '<font color="red">';
        foreach($errors AS $error){
            echo $error . "\n";
        }
        echo "</font>";
        echo '<a href="javascript:history.go(-1)">Try again</a>';
    }

    else{
        $sql = $mysqli->query("INSERT into `requests` (`email`,`firstname`, `lastname`, `existingrole`, `status`, `type`, `event`)
        VALUES ('$sessionEmail', '$firstname', '$lastname', '$role', '$status', '$type', '$joinevent');");
        
        echo '<script type="text/javascript">window.location="admin/dashboard?event='.$currentEvent.'"</script></div>';
    }
    
?>