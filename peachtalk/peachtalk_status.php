<?php
    /*********************
    Toggles PeachTalk status (enabled/disabled) for an event
    **********************/
    include dirname(__DIR__) . "/header.php";
    
    $type = $_GET['type'];
    $events = 'events';
    
    if ($type == 'enable'){
      $sql = $mysqli->query("UPDATE `".$events."` SET `peachtalkstatus` = 'Enabled' WHERE `eventid` = '$currentEvent';");
    } elseif ($type == 'disable') {
      $sql = $mysqli->query("UPDATE `".$events."` SET `peachtalkstatus` = 'Disabled' WHERE `eventid` = '$currentEvent';");
    }
    echo '<script>window.location="/peachpits/peachtalk/peachtalk-home?event='.$currentEvent.'"</script>';
?>