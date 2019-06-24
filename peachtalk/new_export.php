<?php
    include dirname(__DIR__) . "/includes/config.php"; 

    $currentEvent = protect($_GET['event']);
    $eventLiaisons = $currentEvent . "_liaisons";
    $eventGroups = $currentEvent . "_groups";
    $type = protect($_POST['type']);
    
    if ($type == 'messages') {
        $tableName = $currentEvent . "_messages";
        $filename = $currentEvent . "_messages";
    }
    elseif ($type == 'users') {
        $tableName = $eventLiaisons;
        $filename = $currentEvent . "_users";
    }
    elseif ($type == 'groups') {
        $tableName = $eventGroups;
        $filename = $currentEvent . "_groups";
    }

    $sql = $mysqli->query("SELECT * FROM `".$tableName."`");
    //header info for browser
    header('Content-Transfer-Encoding: none');
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Type: application/x-msexcel");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment; filename=$filename.xls");

    ini_set('zlib.output_compression','Off');
    header('Pragma: public');
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    //header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: pre-check=0, post-check=0, max-age=0');
    header("Pragma: no-cache"); 
    header("Expires: 0");
    $sep = "\t"; 
    if ($type == 'messages') {
        echo "Sent By".$sep;
        echo "Sent To".$sep;
        echo "Message".$sep;
        echo "Date".$sep;
        echo "Time\n";
        while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
            $sentByArr = explode(";", $row['sent_by']);
            $sentByEmail = $sentByArr[0];
            $sentBy = $sentByArr[1];
            $sentByName = $sentByArr[2];
            if (!($sentBy == 'Super Admin' || $sentBy == 'Event Admin' || $sentBy == 'Pit Admin')) {
                $sentBy = $sentByName . " - Team " . $sentBy;
                echo $sentBy.$sep;
            }
            else {
                $sql2 = $mysqli->query("SELECT * FROM `users` WHERE `email`='$sentByEmail'");
                $row2 = mysqli_fetch_assoc($sql2);
                echo $row2['firstname']." ".$row2['lastname']." - ".$sentBy.$sep;
            }
            if ($row['channel'] != 'Private') {
                echo $row['channel'] . " Channel".$sep;
            }
            else {
                $groupid = $row['groupid'];
                $sql2 = $mysqli->query("SELECT * FROM `".$eventGroups."` WHERE `groupid`='$groupid'");
                $row2 = mysqli_fetch_assoc($sql2);
                if ($row2['private_users'] != "") {
                    $usersArr = explode(";", $row2['private_users']);
                    $newUsersArr = array();
                    foreach ($usersArr as $user) {
                        if (!($user == 'Super Admin' || $user == 'Event Admin' || $user == 'Pit Admin')) {
                            $sql3 = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `userid`='$user'");
                            $row3 = mysqli_fetch_assoc($sql3);
                            $newUsersArr[] = $row3['user'];
                        }
                        else {
                            $newUsersArr[] = $user;
                        }
                    }
                    unset($user);
                    $strUsers = implode(", ", $newUsersArr);
                    echo "Private Message: ".$strUsers.$sep;
                }
                else {
                    $teams = str_replace(";", ", ", $row2['private_teams']);
                    echo "Private Message: ".$teams.$sep;
                }
            }
            echo $row['message'].$sep;
            $date = new DateTime($row['time_sent']);
            $time = $date->format('g:i:s A');
            $day = $date->format('m/d/Y');
            echo $day.$sep;
            echo $time;
            echo "\n";
            
        }
    }
    elseif ($type == 'groups') {
        echo "Group ID".$sep;
        echo "Group Name".$sep;
        echo "Members\n";
        while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
            echo $row['groupid'].$sep;
            echo $row['name'].$sep;
            if ($row['private_users'] != "") {
                $usersArr = explode(";", $row['private_users']);
                $newUsersArr = array();
                foreach ($usersArr as $user) {
                    if (!($user == "Super Admin" || $user == "Event Admin" || $user == "Pit Admin")) {
                        $sql2 = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `userid`='$user'");
                        $row2 = mysqli_fetch_assoc($sql2);
                        $newUsersArr[] = $row2['user'];
                    }
                    else {
                        $newUsersArr[] = $user;
                    }
                }
                unset($user);
                $strUsers = implode(", ", $newUsersArr);
                echo $strUsers;
                echo "\n";
            }
            else {
                $teams = str_replace(";", ", ", $row['private_teams']);
                echo $teams;
                echo "\n";
            }
        }   
    }
    elseif ($type == 'users') {
        echo "Name".$sep;
        echo "Team Number".$sep;
        echo "Email".$sep;
        echo "Cell".$sep;
        echo "Lead Mentor's Name".$sep;
        echo "Lead Mentor's Cell".$sep;
        echo "Status".$sep;
        echo "Restricted From\n";
        $sql = $mysqli->query("SELECT * FROM `".$eventLiaisons."` ORDER BY `teamid` ASC");
        while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
            echo $row['user'].$sep;
            echo $row['teamid'].$sep;
            echo $row['email'].$sep;
            echo $row['cell'].$sep;
            echo $row['leadmentor_name'].$sep;
            echo $row['leadmentor_cell'].$sep;
            echo $row['status'].$sep;
            echo $row['restrictions'];
            echo "\n";
        }
    }
?>