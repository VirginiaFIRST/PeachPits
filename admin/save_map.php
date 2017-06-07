<?php
	
	include dirname(__DIR__) . "/header.php";

	$eventid = $_POST['eventid'];
	$mapcode = $_POST['map'];
	$width = $_POST['width'];
	$height = $_POST['height'];

	$sql = $mysqli->query("INSERT into `maps` (`eventid`,`mapcode`,`width`,`height`) VALUES ('$eventid','$mapcode','$width','$height') ON DUPLICATE KEY UPDATE `mapcode`='$mapcode', `width`='$width',`height`='$height'");
	
?>