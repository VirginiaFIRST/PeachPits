<?php
    /*********************
    Processes an update to a teams inspection status by an inspector
    **********************/
    include dirname(__DIR__) . "/header.php";
    
    $refer = $_GET['refer'];
    //$type = $_GET['type'];
    $eventDB = $currentEvent . '_teams';
    
    $sqlInitial = $mysqli->query("SELECT * FROM `".$eventDB."` WHERE `teamid`='$teamid'");
    $row = mysqli_fetch_assoc($sqlInitial);
    $initial = $row['initial_inspector'];
    
    $lastModifiedBy = $firstname . ' ' . $lastname;
    date_default_timezone_set("America/New_York");
    $lastModifiedTime = date("m-d-Y") . ' @ ' . date("h:i:sa") . ' EDT';
    
    $teamid = protect($_POST['teamid']);
    $inspectionstatus = protect($_POST['inspectionstatus']);
    $inspectionnotes = protect($_POST['inspectionnotes']);

    $sql = $mysqli->query("UPDATE `".$eventDB."` SET `inspectionstatus` = '$inspectionstatus', `inspectionnotes` = '$inspectionnotes', `last_modified_by` = '$lastModifiedBy', `last_modified_time` = '$lastModifiedTime' WHERE `teamid` = '$teamid';");
    
    if(empty($initial)){
        $sql = $mysqli->query("UPDATE `".$eventDB."` SET `initial_inspector` = '$lastModifiedBy' WHERE `teamid` = '$teamid';");
    }
    if ($refer = 'manageinspect'){
        echo '<script>window.location="admin/manage-inspection.php?event='.$currentEvent.'"</script>';
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
    
    //if ($refer == 'pitmap'){
    //    echo '<script>window.location="pitmap.php?event='.$currentEvent.'"</script></div>';
    //}
    //else if ($refer == 'manageinspect'){
    //    echo '<script>window.location="admin/manage-inspection.php?event='.$currentEvent.'"</script></div>';
    //}
?>