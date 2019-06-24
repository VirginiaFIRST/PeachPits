<?php    
    include dirname(__DIR__) . "/header.php";
	
    $userid = protect($_POST['userid']);

    $eventLiaisons = $currentEvent . "_liaisons";

    $sql = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `userid`='$userid'");
    $row = mysqli_fetch_assoc($sql);
    $userEmail = $row['email'];

    $sql = $mysqli->query("SELECT * FROM `users` WHERE `email` = '$userEmail'");
    $row = mysqli_fetch_assoc($sql);

    $events = $row['events'];
    $eventsArr = explode(';', $events);
    
    $sqlEvents = $mysqli->query("SELECT * FROM `events` WHERE `eventid` = '$currentEvent'");
    $rowEvents = mysqli_fetch_assoc($sqlEvents);
    $eventName = $rowEvents['eventname'];
    $liaisonString = 'Communication Liaison@' . $eventName;

    //Need to delete $liaisonString from $events
    //Need to consider if there are multiple instances of $liaisonString in $events
    //Need to consider ';'s in $events when deleting the $liaisonString
    
    $eventsArr = array_diff($eventsArr, array($liaisonString));
    $updatedEvents = implode(";", $eventsArr);

    $sql = $mysqli->query("UPDATE `users` SET `events` = '$updatedEvents' WHERE `email` = '$userEmail'");

    $sql = $mysqli->query("UPDATE `".$eventLiaisons."`SET `status`='Deleted' WHERE `userid`='$userid'");

    echo "<script>window.location='/peachpits/peachtalk/manage-users?event=".$currentEvent."'</script>";
?>