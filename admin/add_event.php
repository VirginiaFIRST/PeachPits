<?php
    /*********************
    Processes form submission for adding an event
    **********************/
    include dirname(__DIR__) . "/header.php";

    //Sends data from form to variables
    $eventid = protect($_POST['eventid']);
    $eventname = protect($_POST['eventname']);
    $eventstatus = 'Not Live';
    $eventdistrict = protect($_POST['eventdistrict']);
    //$eventadmin = protect($_POST['eventadmin']);
    $eventlocation = protect($_POST['eventlocation']);
    $eventaddress = protect($_POST['eventaddress']);
    $eventstart = protect($_POST['eventstart']);
    $eventend = protect($_POST['eventend']);
    $eventyear = protect($_POST['eventyear']);
    $eventtype = protect($_POST['eventtype']);
    $auto = protect($_POST['auto']);
    

    if ($auto == 'false') {
        $errors = array();
    
        if(!$eventid || !$eventname) {
            $errors[] = "You did not fill out the required fields";
        }
    
        $sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid`='$eventid'");
        if(mysqli_num_rows($sql) > 0) {
            $errors[] = "Event already added, please add a different team";
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
            $sql = "INSERT into `events` (`eventid`,`eventname`, `eventstatus`, `eventdistrict`, `eventlocation`, `eventaddress`, `eventstart`, `eventend`, `eventyear`, `eventtype`) VALUES ('$eventid','$eventname', '$eventstatus', '$eventdistrict', '$eventlocation', '$eventaddress', '$eventstart', '$eventend', '$eventyear', '$eventtype')";
            $query = $mysqli->query($sql);
    
            $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_teams (
            teamid INT(6) PRIMARY KEY,
            inspectionstatus VARCHAR(72) NOT NULL,
            inspectionnotes VARCHAR(1000) NOT NULL,
            initial_inspector VARCHAR(150) NOT NULL,
            last_modified_by VARCHAR(100) NOT NULL,
            last_modified_time VARCHAR(100) NOT NULL
            )";
            
            $query = $mysqli->query($sql);
            
            $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_matches (
            matchid VARCHAR(12) PRIMARY KEY, 
            matchnumber INT(6),
            setnumber INT(6),
            start VARCHAR(32) NOT NULL,
            red1 INT(6),
            red2 INT(6),
            red3 INT(6),
            blue1 INT(6),
            blue2 INT(6),
            blue3 INT(6),
            matchtype VARCHAR(32)
            )";
            
            $query = $mysqli->query($sql);
        
            echo '<script>window.location="admin/manage-events.php?event='.$currentEvent.'"</script></div>';
        }
    }
    else if ($auto == 'true') {
        $sql = "INSERT into `events` (`eventid`,`eventname`, `eventstatus`, `eventdistrict`, `eventlocation`, `eventaddress`, `eventstart`, `eventend`, `eventyear`, `eventtype`) VALUES ('$eventid','$eventname', '$eventstatus', '$eventdistrict', '$eventlocation', '$eventaddress', '$eventstart', '$eventend', '$eventyear', '$eventtype') ON DUPLICATE KEY UPDATE `eventid`='$eventid', `eventname`='$eventname', `eventstatus`='$eventstatus', `eventdistrict`='$eventdistrict', `eventlocation`='$eventlocation', `eventaddress`='$eventaddress',`eventstart`='$eventstart', `eventend`='$eventend', `eventyear`='$eventyear', `eventtype`='$eventtype'";
        $query = $mysqli->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_teams (
        teamid INT(6) PRIMARY KEY,
        inspectionstatus VARCHAR(72) NOT NULL,
        inspectionnotes VARCHAR(1000) NOT NULL,
        initial_inspector VARCHAR(150) NOT NULL,
        last_modified_by VARCHAR(100) NOT NULL,
        last_modified_time VARCHAR(100) NOT NULL
        )";
        
        $query = $mysqli->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_matches (
        matchid VARCHAR(12) PRIMARY KEY, 
        matchnumber INT(6),
        setnumber INT(6),
        start VARCHAR(32) NOT NULL,
        red1 INT(6),
        red2 INT(6),
        red3 INT(6),
        blue1 INT(6),
        blue2 INT(6),
        blue3 INT(6),
        matchtype VARCHAR(32)
        )";
        
        $query = $mysqli->query($sql);
        
    }
?>