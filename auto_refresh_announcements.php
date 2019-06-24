<?php 
    //header("Content-Type: application/json", true);
    require_once (dirname(__FILE__) .  "/includes/session.php");
    
    global $sessionEmail;
    
    if (isset($_SESSION['email'])){
        $sessionEmail = $_SESSION['email'];
    }
    //test
    error_reporting(0);
    $filePath = "http://" . $_SERVER['SERVER_NAME'] . "/peachpits/";
    $currentEvent = $_GET['event'];
    $eventAnnouncements = $currentEvent."_announcements";
    
    $newAnnouncements = " ";
    $sqlAnnouncements = $mysqli->query("SELECT * FROM `".$eventAnnouncements."` ORDER BY `position` ASC");
    while($row = mysqli_fetch_array($sqlAnnouncements, MYSQLI_BOTH)){
        $newAnnouncements = $newAnnouncements . $row['text'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }
    $varA = -56.08185; $varB = 0.1618459; $varC = 39493630000; $varD = 1745.794; $varX = strlen($newAnnouncements);
    $newAnimationTime = $varD + ($varA - $varD)/(1 + pow(($varX/$varC), $varB));
    $returnData;
    $returnData[0] = $newAnnouncements;
    $returnData[1] = $newAnimationTime;
	echo json_encode($returnData);
?>	
