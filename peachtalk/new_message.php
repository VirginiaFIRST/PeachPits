<?php

    include dirname(__DIR__) . "/header.php";

    $eventMessages = $currentEvent . "_messages";
    $eventGroups = $currentEvent . "_groups";
    $eventActivity = $currentEvent . "_activity";
    $eventLiaisons = $currentEvent . "_liaisons";

    $userInfo = $_POST['username'];
    $userArr = explode(";", $userInfo);
    $userEmail = $userArr[0];
    $username = $userArr[1];
    $groupName = $_POST['groupname'];
    if ($groupName == '') {
        $groupName = "none";
    }
    $oldDate = new DateTime('2018-01-01 00:00:00', new DateTimeZone('America/New_York'));
    $lastVisited = $oldDate->format('Y-m-d H:i:s');

    $users = array();
    if (is_array($_POST['users'])) {
        foreach ($_POST['users'] as $user) {
            $users[] = $user;
            $numUsers += 1;
        }
    }
    else {
        $users[] = $_POST['users'];
    }
    $twoUsers = false;
    if ($numUsers == 2) {
        $twoUsers = true;
    }
    $teams = array();
    if (is_array($_POST['teams'])) {
        foreach ($_POST['teams'] as $team) {
            $teams[] = $team;
        }
    }
    else {
        $teams[] = $_POST['teams'];
    }
    if ($_POST['matchteams'] != null) {
        $matchteams = explode(";", $_POST['matchteams']);
        $teams = array($matchteams[2], $matchteams[3], $matchteams[4]);
    }
    if ($users[0] != '' && $teams[0] != '') {}
    elseif ($users[0] != '') {
        if (!(isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role))) {
            $sql = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `email`='$userEmail'");
            $row = mysqli_fetch_assoc($sql);
            $userid = $row['userid'];
            $users[] = $userid;
            sort($users);
        }
        else {
            sort($users);
            array_unshift($users, $username);
        }
        $strUsers = implode(";", $users);
        if ($twoUsers == true) {
            $groupName = $strUsers;
        }
        $sql2 = $mysqli->query("SELECT * FROM `".$eventGroups."` WHERE `private_users`='$strUsers'");
        if (!mysqli_fetch_row($sql2)) {
            $sql3 = $mysqli->query("INSERT into `".$eventGroups."` (`name`, `private_users`) VALUES ('$groupName', '$strUsers');");
        }
        $sql2 = $mysqli->query("SELECT * FROM `".$eventGroups."` WHERE `private_users`='$strUsers'");
        $row2 = mysqli_fetch_assoc($sql2);
        $groupid = $row2['groupid'];
        foreach ($users as $user) {
            if ($user == 'Super Admin' || $user == 'Event Admin' || $user == 'Pit Admin') {
                $sql3 = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user`='$userEmail' AND `channel`='Private' AND `groupid`='$groupid'");
                if (!(mysqli_fetch_row($sql3))) {
                    $sql3 = $mysqli->query("INSERT into `".$eventActivity."` (`user`, `channel`, `groupid`, `last_visited`) VALUES ('$userEmail', 'Private', '$groupid', '$lastVisited');");
                }
            }
            else {
                $sql3 = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `userid`='$user'");
                $row3 = mysqli_fetch_assoc($sql3);
                $loopEmail = $row3['email'];
                $sql3 = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user`='$loopEmail' AND `channel`='Private' AND `groupid`='$groupid'");
                if (!(mysqli_fetch_row($sql3))) {
                    $sql4 = $mysqli->query("INSERT into `".$eventActivity."` (`user`, `channel`, `groupid`, `last_visited`) VALUES ('$loopEmail', 'Private', '$groupid', '$lastVisited');");
                }
            }
        }
        echo '<script>window.location="/peachpits/peachtalk/private-message?event='.$currentEvent.'&groupid='.$groupid.'";</script>';
    }
    elseif ($teams[0] != '') {
        if (!(isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role))) {
            if ($_POST['matchteams'] == null) {
                $teams[] = $username;
            }
            sort($teams);
        }
        else {
            sort($teams);
            array_unshift($teams, $username);
        }
        $strTeams = implode(";", $teams);
        $sql2 = $mysqli->query("SELECT * FROM `".$eventGroups."` WHERE `private_teams`='$strTeams'");
        if (!mysqli_fetch_row($sql2)) {
            $sql3 = $mysqli->query("INSERT into `".$eventGroups."` (`name`, `private_teams`) VALUES ('$groupName', '$strTeams');");
        }
        $sql2 = $mysqli->query("SELECT * FROM `".$eventGroups."` WHERE `private_teams`='$strTeams'");
        $row2 = mysqli_fetch_assoc($sql2);
        $groupid = $row2['groupid'];
        foreach ($teams as $teamid) {
            if ($teamid == 'Super Admin' || $teamid == 'Event Admin' || $teamid == 'Pit Admin') {
                $sql2 = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user`='$userEmail' AND `channel`='Private' AND `groupid`='$groupid'");
                if (!(mysqli_fetch_row($sql2))) {
                    $sql3 = $mysqli->query("INSERT into `".$eventActivity."` (`user`, `channel`, `groupid`, `last_visited`) VALUES ('$userEmail', 'Private', '$groupid', '$lastVisited');");
                }
            }
            else {
                $sql2 = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `teamid`='$teamid'");
                while ($row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH)) {
                    $loopEmail = $row2['email'];
                    $sql3 = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user`='$loopEmail' AND `channel`='Private' AND `groupid`='$groupid'");
                    if (!(mysqli_fetch_row($sql3))) {
                        $sql4 = $mysqli->query("INSERT into `".$eventActivity."` (`user`, `channel`, `groupid`, `last_visited`) VALUES ('$loopEmail', 'Private', '$groupid', '$lastVisited');");
                    }
                }
            }
        }
        echo '<script>window.location="/peachpits/peachtalk/private-message?event='.$currentEvent.'&groupid='.$groupid.'";</script>';
    }

?>