<?php
    /*********************
    Processes form submission for editing a team
    **********************/
    
   include dirname(__DIR__) . "/header.php";

    //Sends to data from form to variables
    $teamid = protect($_POST['teamid']);
    $teamname = protect($_POST['teamname']);
    $schoolname = protect($_POST['schoolname']);
    $location = protect($_POST['location']);

    //Form Validation
    $errors = array();

    if(!$teamid || !$teamname || !$schoolname || !$location){
        $errors[] = "You did not fill out the required fields";
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
        //If everything is good, update the database
        $sql = "UPDATE `teams` SET `teamid` = '$teamid', `teamname` = '$teamname', `schoolname` = '$schoolname', `location` = '$location' WHERE `teamid` = '$teamid';";

        $query = $mysqli->query($sql);

        echo '<script type="text/javascript">window.location="admin/manage-teams?event='.$currentEvent.'"</script></div>';
    }
    
?>