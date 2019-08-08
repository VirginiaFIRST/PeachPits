<?php
    /*********************
    Processes an update to a teams inspection status by an inspector
    **********************/
    include dirname(__DIR__) . "/header.php";
    
    $refer = $_GET['refer'];
    $type = $_GET['type'];
    $eventDB = $currentEvent . '_teams';
    $eventInspections = $currentEvent . '_inspections';
    
    $lastModifiedBy = $firstname . ' ' . $lastname;
    date_default_timezone_set("America/New_York");
    $lastModifiedTime = date("m-d-Y") . ' @ ' . date("H:i:sa");
    
    //Resets inspectionstatus to 'Not Started' for all teams
    if ($type == 'resetstatus'){

        $resetStatusTo = 'Not Started'; //Change this value to change what the inspectionstatus is reset to. Make sure capitalization is correct
        $sql = $mysqli->query("UPDATE `".$eventDB."` SET `inspectionstatus` = '$resetStatusTo', `last_modified_by` = '$lastModifiedBy', `last_modified_time` = '$lastModifiedTime';");
        
        // Creates a time stamp for changes in Inspection Status
        $sql = $mysqli->query("SELECT * FROM `".$eventDB."`");
        while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
            $thisTeamId = $row['teamid'];
            $sql2 = $mysqli->query("INSERT into `".$eventInspections."` (`teamid`, `inspectionstatus`, `modified_by`, `modified_time`) VALUES ('$thisTeamId', '$resetStatusTo', '$lastModifiedBy', '$lastModifiedTime');");
            $query = $mysqli->query($sql2);
        }
        //Returns user to the manage-inspection page
        echo '<script>window.location="/peachpits/admin/manage-inspection?event='.$currentEvent.'"</script>';
    }
    
    $teamid = protect($_POST['teamid']);
    $inspectionstatus = protect($_POST['inspectionstatus']);
    $robotweight = protect($_POST['robotweight']);
    $redbumperweight = protect($_POST['redbumperweight']);
    $bluebumperweight = protect($_POST['bluebumperweight']);
    $inspectionnotes = protect($_POST['inspectionnotes']);

    if ($type == 'changeall') {
      $sql = $mysqli->query("UPDATE `".$eventDB."` SET `inspectionstatus` = '$inspectionstatus', `robotweight` = '$robotweight', `redbumperweight` = '$redbumperweight', `bluebumperweight` = '$bluebumperweight', `inspectionnotes` = '$inspectionnotes', `last_modified_by` = '$lastModifiedBy', `last_modified_time` = '$lastModifiedTime' WHERE `teamid` = '$teamid';");
      
      $sql = $mysqli->query("INSERT into `".$eventInspections."` (`teamid`, `inspectionstatus`, `robotweight`, `redbumperweight`, `bluebumperweight`, `inspectionnotes`, `modified_by`, `modified_time`) VALUES ('$teamid', '$inspectionstatus', '$robotweight', '$redbumperweight', '$bluebumperweight', '$inspectionnotes', '$lastModifiedBy', '$lastModifiedTime');");
      $query = $mysqli->query($sql);
    }

    $sqlInitial = $mysqli->query("SELECT * FROM `".$eventDB."` WHERE `teamid`='$teamid'");
    $row = mysqli_fetch_assoc($sqlInitial);
    $initial = $row['initial_inspector'];

    if(empty($initial)){
        $sql = $mysqli->query("UPDATE `".$eventDB."` SET `initial_inspector` = '$lastModifiedBy' WHERE `teamid` = '$teamid';");
    }
    
    //Returns user to the pitmap page
    if ($refer == 'pitmap'){
        echo '<script>window.location="/peachpits/pitmap.php?event='.$currentEvent.'"</script>';
    }

    //Returns user to the manage-inspection page
    if ($refer = 'manageinspect'){
        echo '<script>window.location="/peachpits/admin/manage-inspection?event='.$currentEvent.'"</script>';
    }

    //if($type == 'changestatus'){
    //    $inspectionstatus = protect($_POST['inspectionstatus']);
    //    $sql = $mysqli->query("UPDATE `".$eventDB."` SET inspectionstatus = '$inspectionstatus', last_modified_by = '$lastModifiedBy', last_modified_time = '$lastModifiedTime' WHERE teamid = '$teamid';");
        
    //    if(empty($initial)){
    //        $sql = $mysqli->query("UPDATE `".$eventDB."` SET initial_inspector = '$lastModifiedBy' WHERE teamid = '$teamid';");
    //    }
    //}
    //else if($type == 'addnote'){
    //    $inspectionnotes = protect($_POST['inspectionnotes']);
    //    if(empty($inspectionnotes)){
    //        $inspectionnotes = 'No Inspection Notes';
    //    }
        
    //    $sql = $mysqli->query("UPDATE `".$eventDB."` SET inspectionnotes = '$inspectionnotes', last_modified_by = '$lastModifiedBy', last_modified_time = '$lastModifiedTime' WHERE teamid = '$teamid';");  
        
    //    if(empty($initial)){
    //        $sql = $mysqli->query("UPDATE `".$eventDB."` SET initial_inspector = '$lastModifiedBy' WHERE teamid = '$teamid';");
    //    }
    //}
    
    //else if ($refer == 'manageinspect'){
    //    echo '<script>window.location="admin/manage-inspection.php?event='.$currentEvent.'"</script></div>';
    //}
?>