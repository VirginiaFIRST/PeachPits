<?php
	require_once ("includes/session.php");

    global $sessionEmail;

    if (isset($_SESSION['email'])){
        $sessionEmail = $_SESSION['email'];
    }

    error_reporting(0);

    //Fetch some general information about the user from the database for later use
    $sql="SELECT * FROM `users` WHERE `email`='$sessionEmail'";
    $query= $mysqli->query($sql);
    $row = mysqli_fetch_assoc($query);

    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $username = $row['username'];
    $role = $row['role'];
    $event1 = $row['event1'];
    $event2 = $row['event2'];
    $event3 = $row['event3'];
    $event4 = $row['event4'];

    //Checks if a user is a super admin when called
    function isSuperAdmin($role){
        if($role == "Super Admin"){
            return true;
        }
    }

    //Checks if a user is an event admin when called
    function isEventAdmin($role){
        if($role == "Event Admin"){
            return true;
        }
    }

    //Checks if a user is an inspector when called
    function isInspector($role){
        if($role == "Inspector"){
            return true;
        }
    }
?>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
        <title>PeachPits</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet"/>
        <link href="css/footer.css" rel="stylesheet" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
		<style>
			html,
			body {
				height: calc(100% - 110px);
			}
			body{
				background-image:url(imgs/peaches.jpg);
			}
			@media screen and (min-width: 768px) {
				body{
					background-image:url(imgs/peaches.jpg);
					background-size:100%;
				}
			}
			.navbar{
				background-color:transparent;
			}
		</style>
	</head>
	<body>
		<div style="display:table; width:100%; height:100%;">
			<div style="display:table-cell;vertical-align:middle; ">
				<div style="margin-left:auto;margin-right:auto;width:70%;text-align:center;">
					<h2 style="color:white;">Choose an Event Before Continuing</h2>
					<div class="dropdown">
						<button class="btn-dropdown-nav btn-lg dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Select an Event<span class="caret"></span>
						</button>
						<ul class="dropdown-menu pull-center" aria-labelledby="dropdownMenu1">
                            <?php
							$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventstatus` LIKE 'Live'");
							while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
								echo '<li><a href="' . $_SERVER['HTTP_REFERER'] . $row['eventid'] . '">' . $row['eventname'] . '</a></li>';
							}
                            ?>
                            <li class="divider"></li>
							<li><a href="contact?topic=event" style="color:red;">Don't see your event?</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php 	include "footer.php"; ?>