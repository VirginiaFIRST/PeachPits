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
    $peachtalkstatus = 'Enabled';
    $auto = protect($_POST['auto']);

    $sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid`='$eventid'");
    $row = mysqli_fetch_assoc($sql);
    if ($row['eventstatus'] == "Live") {
        $eventstatus = 'Live';
    }

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
            $sql = "INSERT into `events` (`eventid`,`eventname`, `eventstatus`, `eventdistrict`, `eventlocation`, `eventaddress`, `eventstart`, `eventend`, `eventyear`, `eventtype`, `peachtalkstatus`) VALUES ('$eventid','$eventname', '$eventstatus', '$eventdistrict', '$eventlocation', '$eventaddress', '$eventstart', '$eventend', '$eventyear', '$eventtype', '$peachtalkstatus')";
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

            $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_inspections (
            teamid INT(6) NOT NULL,
            inspectionstatus VARCHAR(72) NOT NULL,
            inspectionnotes VARCHAR(1000) NOT NULL,
            initial_inspector VARCHAR(150) NOT NULL,
            modified_by VARCHAR(100) NOT NULL,
            modified_time VARCHAR(100) NOT NULL
            )";
            
            $query = $mysqli->query($sql);
            
            $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_announcements (
            position INT(6) NOT NULL,
            text VARCHAR(1000) NOT NULL
            )";
            
            $query = $mysqli->query($sql);

            $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_messages (
            messageid INT(6) PRIMARY KEY AUTO_INCREMENT,
            message VARCHAR(1000) NOT NULL,
            time_sent VARCHAR(25) NOT NULL,
            sent_by VARCHAR(150) NOT NULL,
            channel VARCHAR(50) NOT NULL,
            groupid INT(4) NOT NULL,
            reply VARCHAR(6) NOT NULL,
            removed INT(1) NOT NULL
            )";
            
            $query = $mysqli->query($sql);

            $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_groups (
            groupid INT(4) PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(150) NOT NULL,
            private_users VARCHAR(1000) NOT NULL,
            private_teams VARCHAR(1000) NOT NULL
            )";
            
            $query = $mysqli->query($sql);

            $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_activity (
            user VARCHAR(150) NOT NULL,
            channel VARCHAR(50) NOT NULL,
            groupid INT(4) NOT NULL,
            last_visited VARCHAR(25) NOT NULL
            )";
            
            $query = $mysqli->query($sql);

            $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_liaisons (
            userid INT(6) PRIMARY KEY AUTO_INCREMENT,
            teamid INT(6) NOT NULL,
            user VARCHAR(150) NOT NULL,
            email VARCHAR(70) NOT NULL,
            cell VARCHAR(20) NOT NULL,
            leadmentor_name VARCHAR(150) NOT NULL,
            leadmentor_cell VARCHAR(20) NOT NULL,
            status VARCHAR(50) NOT NULL,
            restrictions VARCHAR(100) NOT NULL
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
        
            echo '<script>window.location="/peachpits/admin/manage-events?event='.$currentEvent.'"</script></div>';
        }
    }
    else if ($auto == 'true') {
        $sql = "INSERT into `events` (`eventid`,`eventname`, `eventstatus`, `eventdistrict`, `eventlocation`, `eventaddress`, `eventstart`, `eventend`, `eventyear`, `eventtype`, `peachtalkstatus`) VALUES ('$eventid','$eventname', '$eventstatus', '$eventdistrict', '$eventlocation', '$eventaddress', '$eventstart', '$eventend', '$eventyear', '$eventtype', '$peachtalkstatus') ON DUPLICATE KEY UPDATE `eventid`='$eventid', `eventname`='$eventname', `eventstatus`='$eventstatus', `eventdistrict`='$eventdistrict', `eventlocation`='$eventlocation', `eventaddress`='$eventaddress',`eventstart`='$eventstart', `eventend`='$eventend', `eventyear`='$eventyear', `eventtype`='$eventtype'";
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

        $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_inspections (
        teamid INT(6) NOT NULL,
        inspectionstatus VARCHAR(72) NOT NULL,
        inspectionnotes VARCHAR(1000) NOT NULL,
        initial_inspector VARCHAR(150) NOT NULL,
        modified_by VARCHAR(100) NOT NULL,
        modified_time VARCHAR(100) NOT NULL
        )";

        $query = $mysqli->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_announcements (
        position INT(6) NOT NULL,
        text VARCHAR(1000) NOT NULL
        )";

        $query = $mysqli->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_messages (
        messageid INT(6) PRIMARY KEY AUTO_INCREMENT,
        message VARCHAR(1000) NOT NULL,
        time_sent VARCHAR(25) NOT NULL,
        sent_by VARCHAR(150) NOT NULL,
        channel VARCHAR(50) NOT NULL,
        groupid INT(4) NOT NULL,
        reply VARCHAR(6) NOT NULL,
        removed INT(1) NOT NULL
        )";
        
        $query = $mysqli->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_groups (
        groupid INT(4) PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(150) NOT NULL,
        private_users VARCHAR(1000) NOT NULL,
        private_teams VARCHAR(1000) NOT NULL
        )";
        
        $query = $mysqli->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_activity (
        user VARCHAR(150) NOT NULL,
        channel VARCHAR(50) NOT NULL,
        groupid INT(4) NOT NULL,
        last_visited VARCHAR(25) NOT NULL
        )";
        
        $query = $mysqli->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS " . $eventid . "_liaisons (
        userid INT(6) PRIMARY KEY AUTO_INCREMENT,
        teamid INT(6) NOT NULL,
        user VARCHAR(150) NOT NULL,
        email VARCHAR(70) NOT NULL,
        cell VARCHAR(20) NOT NULL,
        leadmentor_name VARCHAR(150) NOT NULL,
        leadmentor_cell VARCHAR(20) NOT NULL,
        status VARCHAR(50) NOT NULL,
        restrictions VARCHAR(100) NOT NULL
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