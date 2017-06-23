<?php
    /*********************
    Processes form submission for editing a match
    **********************/
    
    include dirname(__DIR__) . "/header.php";
    
    //Sends to data from form to variables
    $matchid = protect($_POST['matchid']);
    $start = protect($_POST['starttime']);
    $red1 = protect($_POST['red1']);
    $red2 = protect($_POST['red2']);
	$red3 = protect($_POST['red3']);
	$blue1 = protect($_POST['blue1']);
	$blue2 = protect($_POST['blue2']);
	$blue3 = protect($_POST['blue3']);
    
    $event = $currentEvent . "_matches";

    //Form Validation
    $errors = array();

    if(!$red1 || !$red2 || !$red3 || !$blue1 || !$blue2 || !$blue3) {
        $errors[] = "You did not fill out the required fields";
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
        //If everything is good, update the database
        $sql = "UPDATE `".$event."` SET `start` = '$start', `red1` = '$red1', `red2` = '$red2', `red3` = '$red3', `blue1` = '$blue1', `blue2` = '$blue2', `blue3` = '$blue3' WHERE `matchid` = '$matchid';";

        $query = $mysqli->query($sql);

        echo '<script type="text/javascript">window.location="admin/manage-matches?event='.$currentEvent.'"</script></div>';
    }
    
?>