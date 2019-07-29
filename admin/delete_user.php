<?php    
  include dirname(__DIR__) . "/header.php";

  $userEmail = protect($_POST['email']);
  $userRole = protect($_POST['role']);

  $eventUsers = $currentEvent . "_users";

  $sql = $mysqli->query("SELECT * FROM `users` WHERE `email` = '$userEmail'");
  $row = mysqli_fetch_assoc($sql);
  $events = $row['events'];
  $eventsArr = explode(';', $events);

  $roleString = $userRole . "@" . $currentEvent;

  $eventsArr = array_diff($eventsArr, array($roleString));
  $updatedEvents = implode(";", $eventsArr);

  $sql = $mysqli->query("UPDATE `users` SET `events` = '$updatedEvents' WHERE `email` = '$userEmail'");

  $sql = $mysqli->query("DELETE FROM `".$eventUsers."` WHERE `email` = '$userEmail'");

  echo "<script>window.location='/peachpits/admin/manage-users?event=".$currentEvent."'</script>";
?>