<?php    
    include dirname(__DIR__) . "/header.php";
	
    $userid = protect($_POST['userid']);
    $parts = protect($_POST['channel1']);
    $safety = protect($_POST['channel2']);
    $private = protect($_POST['channel3']);

    $restrictions = '';
    $eventLiaisons = $currentEvent . "_liaisons";

    if ($parts == 'Parts') {
        $restrictions .= $parts;
    }
    if ($safety == 'Safety') {
        $restrictions .= $safety;
    }
    if ($private == 'Private') {
        $restrictions .= $private;
    }
    $sql = $mysqli->query("UPDATE `".$eventLiaisons."` SET `restrictions`='$restrictions' WHERE `userid`='$userid'");

    echo "<script>window.location='/peachpits/peachtalk/manage-users?event=".$currentEvent."'</script>";
?>