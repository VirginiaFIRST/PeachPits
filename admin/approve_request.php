<?php
  /*********************
  Runs when an event admin clicks the Approve link for a Event/Role Request
  **********************/
  
  include dirname(__DIR__) . "/header.php";

  //Get variables from the url
  $email = $_GET['user'];
  $email = base64_decode($email);
  $event = $_GET['eventReq'];
  $role = $_GET['role'];
  $refer = $_GET['refer'];
  $eventUsers = $event . "_users";

  if ($role == "Communication Liaison") {
    $sql = $mysqli->query("SELECT * FROM `events` WHERE `eventname` = '$event'");
    $row = mysqli_fetch_assoc($sql);
    $eventid = $row['eventid'];
    $eventLiaisons = $eventid . "_liaisons";
    $sql = $mysqli->query("UPDATE `".$eventLiaisons."` SET `status`= 'Approved' WHERE `email`='$email' AND `status`='Pending'");
    
    $sql = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `email`='$email' AND `status`='Approved'");
    $row = mysqli_fetch_assoc($sql);
    $teamid = $row['teamid'];
    $eventActivity = $eventid . "_activity";
    $oldDate = new DateTime('2018-01-01 00:00:00', new DateTimeZone('America/New_York'));
    $lastVisited = $oldDate->format('Y-m-d H:i:s');
    $channels = array("General", "Schedule", "Parts", "Safety");
    foreach ($channels as $channel) {
      $sql = $mysqli->query("INSERT into `".$eventActivity."` (`user`, `channel`, `last_visited`) VALUES ('$email', '$channel', '$lastVisited');");
    }
    $teamChannels = array();
    $sql = $mysqli->query("SELECT * FROM `".$eventActivity."`");
    while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
      $teams = $row['private_teams'];
      if ($teams != "" && !(in_array($teams, $teamChannels))) {
        $teamChannels[] = $teams;
      }
    }
    foreach ($teamChannels as $teams) {
      if (in_array($teamid, explode(";", $teams))) {
        $sql3 = $mysqli->query("INSERT into `".$eventActivity."` (`user`, `channel`, `private_teams`, `last_visited`) VALUES ('$email', 'Private', '$teams', '$lastVisited');");
      }
    }
  }

  //Pull all info about that user from the database and update
  $sql = "SELECT * FROM `users` WHERE `email` LIKE '$email'";
  $query = $mysqli->query($sql);
  $row = mysqli_fetch_assoc($query);
  $firstname = $row['firstname'];
  $lastname = $row['lastname'];
  $newRole = $role;
  $eventStr = $row['events'];
  if ($eventStr == 'No Event') {
    $eventStr = '';
  }

  $addStr = $role . '@' . $event;
  
  $eventStr .= ';' . $addStr;
  $eventStr = trim($eventStr,';');

  if ($row['role'] != 'n/a') {
    $role = 'n/a';
  } else {
    $role = 'n/a';
  }

  $sql = $mysqli->query("UPDATE `users` SET `role` = '$role', `events` = '$eventStr' WHERE email = '$email'");
  $sql = $mysqli->query("INSERT into `".$eventUsers."`(`email`, `firstname`, `lastname`, `role`) VALUES ('$email', '$firstname', '$lastname', '$newRole')");
  
  //Delete the request from the database
  $sql = $mysqli->query("DELETE FROM requests WHERE email = '$email' AND `event` = '$event'");

  if ($refer == 'manage_requests') {
    echo '<script>window.location="/peachpits/peachtalk/manage-requests?event='.$currentEvent.'"</script></div>';
  } else {
    echo '<script>window.location="/peachpits/admin/dashboard?event='.$currentEvent.'"</script></div>';
  }
?>