<?php
    /*********************
    Processes an update to an event's announcements
    **********************/
    include dirname(__DIR__) . "/header.php";
    
    $tableRow = $_GET['row'];
    $type = $_GET['type'];
    $eventAnnouncements = $currentEvent . '_announcements';

    $numAnnouncements = 0;
    $sql = $mysqli->query("SELECT * FROM `".$eventAnnouncements."`");
    while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
        $numAnnouncements++;
    }
    
    if ($type == 'up'){
        if ($tableRow != 0) {
            $sql = $mysqli->query("SELECT * FROM `".$eventAnnouncements."` ORDER BY `position` ASC");
            while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
                $currentPosition = $row['position'];
                if ($currentPosition == $tableRow - 1) {
                    $sql2 = $mysqli->query("UPDATE `".$eventAnnouncements."` SET `position` = '-100' WHERE `position` = '$currentPosition'");
                }
                if ($currentPosition == $tableRow) {
                    $targetPosition = $tableRow - 1;
                    $sql2 = $mysqli->query("UPDATE `".$eventAnnouncements."` SET `position` = '$targetPosition' WHERE `position` = '$currentPosition'");
                    $sql2 = $mysqli->query("UPDATE `".$eventAnnouncements."` SET `position` = '$currentPosition' WHERE `position` = '-100'");
                }
            }
        }
    }

    else if ($type == 'down') {
        if ($numAnnouncements - 1 != $tableRow) {
            $sql = $mysqli->query("SELECT * FROM `".$eventAnnouncements."` ORDER BY `position` ASC");
            while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
                $currentPosition = $row['position'];
                if ($currentPosition == $tableRow) {
                    $sql2 = $mysqli->query("UPDATE `".$eventAnnouncements."` SET `position` = '-100' WHERE `position` = '$currentPosition'");
                }
                if ($currentPosition == $tableRow + 1) {
                    $sql2 = $mysqli->query("UPDATE `".$eventAnnouncements."` SET `position` = '$tableRow' WHERE `position` = '$currentPosition'");
                    $sql2 = $mysqli->query("UPDATE `".$eventAnnouncements."` SET `position` = '$currentPosition' WHERE `position` = '-100'");
                }
            }
        }
    }

    else if ($type == 'delete') {
        $sql = $mysqli->query("DELETE FROM `".$eventAnnouncements."` WHERE `position` = '$tableRow';");
        $sql = $mysqli->query("SELECT * FROM `".$eventAnnouncements."` ORDER BY `position` ASC");
        while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
            $currentPosition = $row['position'];
            if ($tableRow < $currentPosition) {
                $newPosition = $currentPosition - 1;
                $sql2 = $mysqli->query("UPDATE `".$eventAnnouncements."` SET `position` = '$newPosition' WHERE `position` = '$currentPosition'");
            }
        }
    }

    else if ($type == 'save') {
        $announcementText = protect($_POST['new-announcement']);
        if ($announcementText != "" && $announcementText != " " && $announcementText != "  ") {
            $sql = $mysqli->query("INSERT into `".$eventAnnouncements."` (`position`, `text`) VALUES ('$numAnnouncements', '$announcementText');");
            $query = $mysqli->query($sql);
        }
    }
    
    //Returns user to the manage announcements page
    echo '<script>window.location="/peachpits/admin/manage-announcements?event='.$currentEvent.'"</script>';

?>