<?php
    /*********************
    Processes form submission for adding a match
    **********************/   
    include dirname(__DIR__) . "/header.php";

    //Sends to data from form to variables
    $eventid = protect($_POST['eventid']);
    $matchid = protect($_POST['matchid']);
    $matchnumber = protect($_POST['matchnumber']);
    $setnumber = protect($_POST['setnumber']);
    $start = protect($_POST['starttime']);
    $red1 = protect($_POST['red1']);
    $red2 = protect($_POST['red2']);
	$red3 = protect($_POST['red3']);
	$blue1 = protect($_POST['blue1']);
	$blue2 = protect($_POST['blue2']);
	$blue3 = protect($_POST['blue3']);
    $matchtype = protect($_POST['matchtype']);
    $auto = protect($_POST['auto']);

    $event = $eventid . '_matches';
    
    if ($auto == 'false') {
        //Form Validation
        $errors = array();
        if(!$matchid || !$start || !$red1 || !$red2 || !$red3 || !$blue1 || !$blue2 || !$blue3) {
            $errors[] = "You did not fill out the required fields";
        }
    
        $sql = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchid`='{$matchid}'");
    
        if(mysqli_num_rows($sql) > 0) {
            $errors[] = "Match already added, please add a different match";
        }
    
        if(count($errors) > 0) {
            echo "The following errors occured with your registration";
            echo '<font color="red">';
            foreach($errors AS $error) {
                echo $error . "\n";
            }
            echo "</font>";
            echo "<a href=\"javascript:history.go(-1)\">Try again</a>";
        }
    
        else {
            //If everything is good, save to database
            $sql = "INSERT into `".$event."`(`matchid`,`matchnumber`,`setnumber`,`start`, `red1`, `red2`, `red3`, `blue1`, `blue2`, `blue3`,`matchtype`) VALUES ('$matchid','$matchnumber','$setnumber','$start', '$red1', '$red2', '$red3', '$blue1', '$blue2', '$blue3', '$matchtype')"; 
            $query = $mysqli->query($sql);
    
            echo '<script type="text/javascript">window.location="/peachpits/admin/manage-matches?event='.$currentEvent.'"</script></div>';
        }
    }
    else if ($auto == 'true') {
        $startTime = date("D @ g:i a", $start);
        $sql = "INSERT into `".$event."` (`matchid`,`matchnumber`,`setnumber`,`start`, `red1`, `red2`, `red3`, `blue1`, `blue2`, `blue3`, `matchtype`) VALUES ('$matchid','$matchnumber','$setnumber','$startTime', '$red1', '$red2', '$red3', '$blue1', '$blue2', '$blue3', '$matchtype') ON DUPLICATE KEY UPDATE `matchnumber`='$matchnumber', `setnumber`='$setnumber', `start`='$start', `red1`='$red1', `red2`='$red2', `red3`='$red3', `blue1`='$blue1', `blue2`='$blue2', `blue3`='$blue3', `matchtype`='$matchtype'";
        $query = $mysqli->query($sql);
    }

?>