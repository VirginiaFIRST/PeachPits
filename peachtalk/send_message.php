<?php
	 
    header("Content-Type: application/json");
    include dirname(__DIR__) . "/includes/session.php";

    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
    $blankexp = "/^\n/";
    $eventid = protect($_POST['eventid']);
    $message = protect(strip_tags($_POST['message']));
    $userInfo = protect($_POST['user']);
    $channel = protect($_POST['channel']);
    $groupid = protect($_POST['groupid']);
    $reply = protect($_POST['reply']);

    $eventMessages = $eventid . "_messages";
    $acceptedChannels = ['General', 'Parts', 'Schedule', 'Safety', 'Private'];
    $currentDate = new DateTime(null, new DateTimeZone('America/New_York'));
    $currentDateStamp = $currentDate->format('Y-m-d H:i:s.');
    list($msec, $milliseconds) = explode(".", microtime(true));
    $currentDateStamp .= $milliseconds;
    $currentDateDay = $currentDate->format('Y-m-d');
    $currentDateTime = $currentDate->format('g:i A');
    
    // if (strpos($message, "\n") != -1) {
    //     $messageArr = explode("\n", $message);
    //     $message = implode("<br />\n", $messageArr);
    // }

    // $message = str_replace("\n", "&#13;", $message);

    if (!preg_match($blankexp, $message)) {
        if (preg_match($reg_exUrl, $message, $url)) {
            $message = preg_replace($reg_exUrl, '<a href="'.$url[0].'" target="_blank">'.$url[0].'</a>', $message);
        }
        if (in_array($channel, $acceptedChannels)) {
            
            if ($reply != 'none' && $channel == 'Parts') {
                $sql = $mysqli->query("INSERT into `".$eventMessages."` (`message`, `time_sent`, `sent_by`, `channel`, `reply`) VALUES ('$message', '$currentDateStamp', '$userInfo', '$channel', '$reply');");
                $sql = $mysqli->query("UPDATE `".$eventMessages."` SET `reply`='Yes' WHERE `messageid`='$reply'");
            }
            elseif ($groupid != 'none' && $channel == 'Private') {
                $sql = $mysqli->query("INSERT into `".$eventMessages."` (`message`, `time_sent`, `sent_by`, `channel`, `groupid`) VALUES ('$message', '$currentDateStamp', '$userInfo', '$channel', '$groupid');");
            }
            else {
                $sql = $mysqli->query("INSERT into `".$eventMessages."` (`message`, `time_sent`, `sent_by`, `channel`) VALUES ('$message', '$currentDateStamp', '$userInfo', '$channel');");
            }
        }
    }
    echo json_encode('Finished');
?>