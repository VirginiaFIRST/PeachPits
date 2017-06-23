<?php
    /*********************
    Runs when a user requests a role change
    **********************/
    
    include dirname(__DIR__) . "/header.php";

    //Setting all necessary variables
    $requestedRole = protect($_POST['roleChange']);
    $requestedEvent = protect($_POST['addevent']);
    
    $sql = "SELECT * FROM `users` WHERE `email`='$sessionEmail'";
    $query = $mysqli->query($sql);
    $row = mysqli_fetch_assoc($query);
    $existingRole = $row['role'];
    
    $status = "Pending";
    $type = "Role";
    
    //Form Validation
    $errors = array();

    if(!$requestedRole){
        $errors[] = "You did not fill out the required fields";
    }

    //Makes sure the user is requesting a new role
    $sql = "SELECT * FROM `users` WHERE `email`='{$sessionEmail}'";
    $query = $mysqli->query($sql) or die(mysql_error());

    if($existingRole == $requestedRole && ($requestedEvent)){
        $errors[] = "You already have this role!";
    }

    if(count($errors) > 0){
        echo "The following errors occured with your registration";
        echo '<font color="red">';
        foreach($errors AS $error){
            echo $error . "\n";
        }
        echo "</font>";
        echo "<a href=\"javascript:history.go(-1)\">Try again</a>";
    }

    else{
        $sql = $mysqli->query("INSERT into `requests`(`email`, `firstname`, `lastname`, `existingrole`, `requestedrole`, `status`, `type`, `event`)
        VALUES ('$sessionEmail', '$firstname', '$lastname', '$existingRole', '$requestedRole', '$status', '$type', '$requestedEvent')");

        echo '<script type="text/javascript">window.location="admin/dashboard?event='.$currentEvent.'"</script></div>';
    }
    
?>