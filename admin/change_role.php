<?php
    /*********************
    Runs when a user requests a role change
    **********************/
    
    include dirname(__DIR__) . "/header.php";

    //Setting all necessary variables
    $refer = $_GET['refer'];
    $requestedRole = protect($_POST['roleChange']);
    $requestedEvent = protect($_POST['addevent']);
    $teamid = protect($_POST['teamid']);
    $liaisonName = protect($_POST['liaison-name']);
    $liaisonCell = protect($_POST['liaison-cell']);
    //Preventing errors when exploding $liaisonInfo later
    $teamid = str_replace(";", "", $teamid);
    $liaisonName = str_replace(";", "", $liaisonName);
    $liaisonCell = str_replace(";", "", $liaisonCell);

    $liaisonInfo = $teamid.";".$liaisonName.";".$liaisonCell.";".$sessionEmail;

    $sql = $mysqli->query("SELECT * FROM `events` WHERE `eventname` = '$requestedEvent'");
    $row = mysqli_fetch_assoc($sql);
    $eventid = $row['eventid'];
    $eventLiaisons = $eventid . "_liaisons";

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
        echo "The following errors occured with your registration:";
        echo '<br>';
        echo '<font color="red">';
        foreach($errors AS $error){
            echo $error . "\n";
        }
        echo "</font>";
        echo '<br>';
        echo '<a style="font-size:20px" href="javascript:history.go(-1)">Try again</a>';
    }

    else {
        if ($requestedRole == "Communication Liaison") {
            $sql = $mysqli->query("INSERT into `requests`(`email`, `firstname`, `lastname`, `existingrole`, `requestedrole`, `status`, `type`, `event`, `liaison_info`) VALUES ('$sessionEmail', '$firstname', '$lastname', '$existingRole', '$requestedRole', '$status', '$type', '$requestedEvent', '$liaisonInfo')");
            $sql = $mysqli->query("INSERT into `".$eventLiaisons."` (`teamid`, `user`, `email`, `cell`, `status`) VALUES ('$teamid', '$liaisonName', '$sessionEmail', '$liaisonCell', 'Pending')");
        }
        else {
            $sql = $mysqli->query("INSERT into `requests`(`email`, `firstname`, `lastname`, `existingrole`, `requestedrole`, `status`, `type`, `event`)
            VALUES ('$sessionEmail', '$firstname', '$lastname', '$existingRole', '$requestedRole', '$status', '$type', '$requestedEvent')");
        }

        if ($refer == "peachtalk") {
            echo '<script type="text/javascript">window.location="/peachpits/peachtalk/join?event='.$currentEvent.'"</script></div>';
        }
        else {
            echo '<script type="text/javascript">window.location="/peachpits/admin/dashboard?event='.$currentEvent.'"</script></div>';
        }
    }
    
?>