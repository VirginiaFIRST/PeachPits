<?php
    
    header("Content-Type: application/json");
    include (dirname(__DIR__) .  "/includes/session.php");

    $eventid = protect($_POST['eventid']);
    $userInfo = protect($_POST['user']);
    $channel = protect($_POST['channel']);
    $groupid = protect($_POST['groupid']);

    $eventMessages = $eventid . "_messages";
    $eventLiaisons = $eventid . "_liaisons";
    $eventActivity = $eventid . "_activity";
    $eventGroups = $eventid . "_groups";
    $acceptedChannels = ['General', 'Parts', 'Schedule', 'Safety', 'Private'];
    if ($userInfo == "none") {
        $userEmail = "none";
        $user = "none";
        $userName = "none";
        $userId = "none";
    }
    else {
        $userArr = explode(";", $userInfo);
        $userEmail = $userArr[0];
        $user = $userArr[1];
        $userName = $userArr[2];
        $userId = $userArr[3];
    }
    $messagesSentToday = 0;
    $numNewMessages = 0;
    $currentDate = new DateTime(null, new DateTimeZone('America/New_York'));
    $currentDateStamp = $currentDate->format('Y-m-d H:i:s.');
    list($msec, $milliseconds) = explode(".", microtime(true));
    $currentDateStamp .= $milliseconds;
    $currentDateDay = $currentDate->format('Y-m-d');
    $currentDateTime = $currentDate->format('g:i A');

    if ($user == "none") {
        $pastDate = new DateTime(null, new DateTimeZone('America/New_York'));
        $pastDate = $pastDate->modify('-5 seconds');
        $pastDateStamp = $pastDate->format('Y-m-d H:i:s.');
        list($msec, $milliseconds) = explode(".", microtime(true));
        $pastDateStamp .= $milliseconds;
        $lastVisit = $pastDateStamp;
    }
    else {
        if ($groupid != 'none' && $channel == 'Private') {
            $sql = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user` = '$userEmail' AND `channel` = '$channel' AND `groupid` = '$groupid'");
        }
        else {
            $sql = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user` = '$userEmail' AND `channel` = '$channel'");
        }
        $row = mysqli_fetch_assoc($sql);
    
        $lastVisit = $row['last_visited'];
    }
    $newMessages = '';
    if ($groupid != 'none' && $channel == 'Private') {
        $sql = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel` = '$channel' AND `groupid` = '$groupid'");
        while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
            $loopDay = substr($row['time_sent'], 0, 10);
            if ($loopDay == $currentDateDay) {
                $messagesSentToday += 1;
            }
            if ($row['time_sent'] > $lastVisit) {
                $sentByArr = explode(";", $row['sent_by']);
                $sentByEmail = $sentByArr[0];
                $sentBy = $sentByArr[1];
                $sentByName = $sentByArr[2];
                $sentById = $sentByArr[3];
                if (!($sentBy == 'Pit Admin' || $sentBy == 'Event Admin' || $sentBy == 'Super Admin')) {
                    $sentBy = $sentByName . " - Team " . $sentBy;
                }
                $loopDate = new DateTime($row['time_sent']);
                $loopTime = $loopDate->format('g:i A');
                $numNewMessages += 1;
                $newMessages = $newMessages . '<h4 class="message-info new-message-info" id="message-info-'.$row['messageid'].'"><b>'.$sentBy.'</b> - '.$loopTime.'</h4><p class="height2"></p><h3 class="new-message message" id="message-'.$row['messageid'].'">'.$row['message'].'</h3><p class="height3"></p>';
            }
        }
    }
    else {
        $sql = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel` = '$channel'");
        while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
            $loopDay = substr($row['time_sent'], 0, 10);
            if ($loopDay == $currentDateDay) {
                $messagesSentToday += 1;
            }
            if ($row['time_sent'] > $lastVisit) {
                $sentByArr = explode(";", $row['sent_by']);
                $sentByEmail = $sentByArr[0];
                $sentBy = $sentByArr[1];
                $sentByName = $sentByArr[2];
                $sentById = $sentByArr[3];
                if (!($sentBy == 'Pit Admin' || $sentBy == 'Event Admin' || $sentBy == 'Super Admin')) {
                    $sentBy = $sentByName . " - Team " . $sentBy;
                }
                $loopDate = new DateTime($row['time_sent']);
                $loopTime = $loopDate->format('g:i A');
                $numNewMessages += 1;
                if ($channel == 'Parts') {
                    if (is_numeric($row['reply'])) {
                        $newMessages = $newMessages . '<div class="new-reply" id="new-reply-'.$row['reply'].'-'.$row['messageid'].'"><h4 class="message-info new-message-info reply-message-info" id="message-info-'.$row['messageid'].'"><b>'.$sentBy.'</b> - '.$loopTime.'</h4><p class="height2"></p><h3 class="new-message reply-message message" id="message-'.$row['messageid'].'">'.$row['message'].'</h3><p class="height5"></p></div>';
                    }
                    elseif ($row['reply'] == 'Yes') {
                        $newMessages = $newMessages . '<h4 class="message-info new-message-info" id="message-info-'.$row['messageid'].'"><b>'.$sentBy.'</b> - '.$loopTime.'<span class="reply-link-casing"> - <span class="reply-link" onclick="replyClick(this)" id="'.$row['messageid'].'">Reply&nbsp;</span><span class="glyphicon glyphicon-share-alt"></span></span></h4><p class="height2"></p><h3 class="new-message message" id="message-'.$row['messageid'].'">'.$row['message'].'</h3><div class="view-replies-btn" id="view-replies-btn-'.$row['messageid'].'"><h5 class="view-replies" id="view-replies-'.$row['messageid'].'" onclick="viewRepliesClick(this)">View Replies&nbsp;</h5><div class="glyphicon glyphicon-triangle-bottom" id="caret-'.$row['messageid'].'" onclick="viewRepliesClick(this)"></div></div><p class="height3" id="p-'.$row['messageid'].'"></p><div class="reply-group" id="reply-group-'.$row['messageid'].'"></div>';
                    }
                    else {
                        $newMessages = $newMessages . '<h4 class="message-info new-message-info" id="message-info-'.$row['messageid'].'"><b>'.$sentBy.'</b> - '.$loopTime.'<span class="reply-link-casing"> - <span class="reply-link" onclick="replyClick(this)" id="'.$row['messageid'].'">Reply&nbsp;</span><span class="glyphicon glyphicon-share-alt"></span></span></h4><p class="height2"></p><h3 class="new-message message" id="message-'.$row['messageid'].'">'.$row['message'].'</h3><div class="view-replies-btn hidden" id="view-replies-btn-'.$row['messageid'].'"><h5 class="view-replies" id="view-replies-'.$row['messageid'].'" onclick="viewRepliesClick(this)">View Replies&nbsp;</h5><div class="glyphicon glyphicon-triangle-bottom" id="caret-'.$row['messageid'].'" onclick="viewRepliesClick(this)"></div></div><p class="height3" id="p-'.$row['messageid'].'"></p><div class="reply-group" id="reply-group-'.$row['messageid'].'"></div>';
                    }
                    
                }
                else {
                    $newMessages = $newMessages . '<h4 class="message-info new-message-info" id="message-info-'.$row['messageid'].'"><b>'.$sentBy.'</b> - '.$loopTime.'</h4><p class="height2"></p><h3 class="new-message message" id="message-'.$row['messageid'].'">'.$row['message'].'</h3><p class="height3"></p>';
                }
            }
        }
    }
    if ($user != 'none') {
        if (in_array($channel, $acceptedChannels)) {
            if ($groupid != 'none' && $channel == 'Private') {
                $sql = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user` = '$userEmail' AND `channel` = '$channel' AND `groupid` = '$groupid'");
                if (mysqli_fetch_row($sql)) {
                    $sql2 = $mysqli->query("UPDATE `".$eventActivity."` SET `last_visited` = '$currentDateStamp' WHERE `user` = '$userEmail' AND `channel` = '$channel' AND `groupid` = '$groupid'");
                }
                else {
                    $sql2 = $mysqli->query("INSERT into `".$eventActivity."` (`user`, `channel`, `groupid`) VALUES ('$userEmail', '$channel', '$groupid');");
                }
            }
            else {
                $sql = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user` = '$userEmail' AND `channel` = '$channel'");
                if (mysqli_fetch_row($sql)) {
                    $sql2 = $mysqli->query("UPDATE `".$eventActivity."` SET `last_visited` = '$currentDateStamp' WHERE `user` = '$userEmail' AND `channel` = '$channel'");
                }
                else {
                    $sql2 = $mysqli->query("INSERT into `".$eventActivity."` (`user`, `channel`) VALUES ('$userEmail', '$channel');");          
                }
            }
        }
    }
    
    if ($newMessages != '') {
        if ($numNewMessages != 0 && $messagesSentToday == $numNewMessages) {
            $newMessages = '<h2 class="text-center date-header">Today</h2>' . $newMessages;
        }
        echo json_encode($newMessages);
    }
    else {
        echo json_encode('Nothing to report...');
    }
?>