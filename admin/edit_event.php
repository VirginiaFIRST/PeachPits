<?php
    /*********************
    Processes form submission for editing a team
    **********************/
    
   include dirname(__DIR__) . "/header.php";

    //Sends to data from form to variables
    $eventid = protect($_POST['eventid']);
    $eventdistrict = protect($_POST['eventDistrict']);
    $eventlocation = protect($_POST['eventLocation']);
    $eventaddress = protect($_POST['eventAddress']);
    $eventstart = protect($_POST['eventStart']);
    $eventend = protect($_POST['eventEnd']);
    //$eventyear = protect($_POST['eventyear']);
    $eventtype = protect($_POST['eventType']);

    $sql = $mysqli->query("UPDATE `events` SET `eventdistrict` = '$eventdistrict', `eventlocation` = '$eventlocation', `eventaddress` = '$eventaddress', `eventstart` = '$eventstart', `eventend` = '$eventend', `eventtype` = '$eventtype' WHERE `eventid` = '$eventid';");
    
?>