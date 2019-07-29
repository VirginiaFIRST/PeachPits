<?php
  /*********************
  Runs when an event admin clicks the Deny link for a Event/Role Request
  **********************/

  include dirname(__DIR__) . "/header.php";

  $check = 0;

  //Get variables from the url
  $email = $_GET['user'];
  $email = base64_decode($email);
  $event = $_GET['eventReq'];
  $role = $_GET['role'];
  $refer = $_GET['refer'];

  if ($role == "Communication Liaison") {
    $sql = $mysqli->query("SELECT * FROM `events` WHERE `eventname` = '$event'");
    $row = mysqli_fetch_assoc($sql);
    $eventid = $row['eventid'];
    $eventLiaisons = $eventid . "_liaisons";
    $sql = $mysqli->query("UPDATE `".$eventLiaisons."` SET `status`= 'Denied' WHERE `email`='$email'");
  }

  //$sql = $mysqli->query("UPDATE `requests` SET `status` = 'Denied' WHERE `email` = '$email' AND `event` = '$event' AND (`existingrole` = '$role' OR `requestedrole` = '$role')");

  //Delete the request from the database
  $sql = $mysqli->query("DELETE FROM `requests` WHERE `email` = '$email' AND `event` = '$event' AND (`existingrole` = '$role' OR `requestedrole` = '$role')");

  if ($refer == 'manage_requests') {
    echo '<script>window.location="/peachpits/peachtalk/manage-requests?event='.$currentEvent.'"</script></div>';
  }
  else {
    echo '<script>window.location="/peachpits/admin/dashboard?event='.$currentEvent.'"</script></div>';
  }
?>