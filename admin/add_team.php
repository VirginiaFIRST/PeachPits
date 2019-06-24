<?php
    /*********************
    Processes form submission for adding a team
    **********************/
    include dirname(__DIR__) . "/header.php";

    //Sends to data from form to variables
    $eventid = protect($_POST['eventid']);
    $teamid = protect($_POST['teamid']);
    $teamname = protect($_POST['teamname']);
    $schoolname = protect($_POST['schoolname']);
    $location = protect($_POST['location']);
    $eventList = protect($_POST['eventList']);
    $auto = protect($_POST['auto']);
    
    $inspectionstatus = "Not Started";
    $inspectionnotes = 'No Inspection Notes';
    $event = $eventid . '_teams';

    if ($auto == 'false') {
        $errors = array();
    
        if(!$teamid || !$teamname || !$schoolname || !$location) {
            $errors[] = "You did not fill out the required fields";
        }
    
        $sql = $mysqli->query("SELECT * FROM `'$event'` WHERE `teamid`='$teamid'");
        if(mysqli_num_rows($sql) > 0) {
            $errors[] = "Team already added, please add a different team";
        }  
        if(count($errors) > 0) {
            echo "The following errors occured with your registration";
            echo '<font color="red">';
            foreach($errors AS $error) {
                echo $error . "\n";
            }
            echo "</font>";
            echo '<a href="javascript:history.go(-1)">Try again</a>';
        } 
        else {
            //If everything is good, save to database
            if ($eventList == 'true') {
                $sql = "INSERT into `" .$event. "` (`teamid`,`inspectionstatus`) VALUES ('$teamid','$inspectionstatus')";
                $query = $mysqli->query($sql);
            }
            $sql = "INSERT into `teams` (`teamid`,`teamname`, `schoolname`, `location`) VALUES ('$teamid','$teamname', '$schoolname', '$location')";
            $query = $mysqli->query($sql);

            date_default_timezone_set("America/New_York");
            $lastModifiedTime = date("m-d-Y") . ' @ ' . date("H:i:sa") . ' EDT';
            
            $sqlInspections = $mysqli->query("INSERT into `".$eventid."_inspections` (`teamid`, `inspectionstatus`, `modified_time`) VALUES ('$teamid', 'Not Started', '$lastModifiedTime');");
    
            echo '<script>window.location="/peachpits/admin/manage-teams?event='.$currentEvent.'"</script></div>';
        }
    }
    else if ($auto == 'true') {
        $sql = "INSERT into `teams` (`teamid`,`teamname`, `schoolname`, `location`) VALUES ('$teamid','$teamname', '$schoolname', '$location') ON DUPLICATE KEY UPDATE `teamname`='$teamname', `schoolname`='$schoolname', `location`='$location'";
        $query = $mysqli->query($sql);
            
        $sql = "INSERT into `" .$event. "` (`teamid`,`inspectionstatus`) VALUES ('$teamid','$inspectionstatus')";
        $query = $mysqli->query($sql);

        date_default_timezone_set("America/New_York");
        $lastModifiedTime = date("m-d-Y") . ' @ ' . date("H:i:sa") . ' EDT';

        $sqlInspections = $mysqli->query("INSERT into `".$eventid."_inspections` (`teamid`, `inspectionstatus`, `modified_time`) VALUES ('$teamid', 'Not Started', '$lastModifiedTime');");
    }
?>