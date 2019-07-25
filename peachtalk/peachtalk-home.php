<title>PeachTalk</title>
<?php 
	include dirname(__DIR__) . "/header.php"; 
  
  // Remove when turning PeachTalk back on
  echo '<script>window.location="/peachpits/index"</script>';

	if (empty($currentEvent)) {
		echo '<script>window.location="/peachpits/chooseevent"</script>';
	}
	else {
    $eventMessages = $currentEvent . "_messages";
    $eventGroups = $currentEvent . "_groups";
    $eventActivity = $currentEvent . "_activity";
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventname = $row['eventname'];
    echo '<script>var username = "'.$peachtalkUsername.'";</script>';
    $userArr = explode(";", $peachtalkUsername);
    $userEmail = $userArr[0];
    $userTeam = $userArr[1];
    $userId = $userArr[3];
    $peachtalkDisabled = $row['peachtalkstatus'];
?>

<div class="page-head" style="margin-bottom:0px">
	<div class="container">
		<h1>PeachTalk for <?php echo $eventname; ?></h1>
	</div>
</div>
<?php if (!(isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role)) && $peachtalkUsername != 'none') { ?>
<div class="header-btn-container text-center" style="padding:0px">
    <div class="container">
        <div class="row">
        <?php echo '<h4>Communication Liaison for Team '.$userTeam.'</h4>';?>
        </div>
    </div>
</div>
<?php } ?>
<div class="container content" style="margin-top:20px">
    <?php
      if (isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role)) {
        if ($peachtalkDisabled) {
          echo `
          <div class="text-center" style="margin-bottom:20px;">
            <h3>PeachTalk has been disabled for this event. No one is able to view messages besides the Pit Admin and Event Admin.<br><br><a href="/peachpits/peachtalk/peachtalk_status?event=` + $currentEvent + `&type=enable">Click here to reenable PeachTalk for this event.</a></h3>
          </div>
          `;
        }
    ?>
    <div class="icons-admin"> 
        <div class="col-xs-4 col-xs-offset-2" id="boxes-container-left">
            <div class="text-center">
                <button class="messaging-box messaging-box-admin-row1" id="general-box">
                    <div class="glyphicon glyphicon-bullhorn messaging-box-icon icon-admin-row1" id="general-box-icon-admin"></div>
                    <div class="messaging-box-text messaging-box-text-rows" id="general-box-text-admin">General<span id="general-box-unread"></span></div>                
                </button>
                <button class="messaging-box messaging-box-admin-row2" id="schedule-box">
                    <div class="glyphicon glyphicon-time messaging-box-icon icon-admin-row2" id="schedule-box-icon-admin"></div>
                    <div class="messaging-box-text messaging-box-text-rows" id="schedule-box-text-admin">Schedule<span id="schedule-box-unread"></span></div>
                </button>
                <button class="messaging-box messaging-box-admin-row3" id="private-box">
                    <div class="glyphicon glyphicon-comment messaging-box-icon icon-admin-row3" id="private-box-icon-admin"></div>
                    <div class="messaging-box-text messaging-box-text-rows" id="private-box-text-admin">Message<span id="private-box-unread"></span></div>
                </button>
                <button class="messaging-box messaging-box-admin-row4" id="users-box">
                    <div class="glyphicon glyphicon-user messaging-box-icon icon-admin-row4" id="users-box-icon-admin"></div>
                    <div class="messaging-box-text messaging-box-text-rows" id="users-box-text-admin">Users</div>
                </button>
            </div>
        </div>
        <div class="col-xs-4" id="boxes-container-right">
            <div class="text-center">
                <button class="messaging-box messaging-box-admin-row1" id="parts-box">
                    <div class="glyphicon glyphicon-wrench messaging-box-icon icon-admin-row1" id="parts-box-icon-admin"></div>
                    <div class="messaging-box-text messaging-box-text-rows" id="parts-box-text-admin">Parts<span id="parts-box-unread"></span></div>
                </button>
                <button class="messaging-box messaging-box-admin-row2" id="safety-box">
                    <div class="glyphicon glyphicon-warning-sign messaging-box-icon icon-admin-row2" id="safety-box-icon-admin"></div>
                    <div class="messaging-box-text messaging-box-text-rows" id="safety-box-text-admin">Safety<span id="safety-box-unread"></span></div>
                </button>
                <button class="messaging-box messaging-box-admin-row3" id="requests-box">
                    <div class="glyphicon glyphicon-cog messaging-box-icon icon-admin-row3" id="requests-box-icon-admin"></div>
                    <div class="messaging-box-text messaging-box-text-rows" id="requests-box-text-admin">Requests<span id="requests-box-unread"></span></div>
                </button>
                <button class="messaging-box messaging-box-admin-row4" id="export-box">
                    <div class="glyphicon glyphicon-export messaging-box-icon icon-admin-row4" id="export-box-icon-admin"></div>
                    <div class="messaging-box-text messaging-box-text-rows" id="export-box-text-admin">Export</div>
                </button>
            </div>
        </div>
    <?php
      if (!$peachtalkDisabled) {
        echo `
        <div class="col-xs-12">
          <div class="text-center" style="margin-bottom:20px;">
            <h3><a href="/peachpits/peachtalk/peachtalk_status?event=` + $currentEvent + `&type=disable">Click here to disable PeachTalk for this event.</a><br><br>This will prevent other users from sending and viewing messages. PeachTalk can be reenabled from this page at any time.</h3>
          </div>
        </div>
        `;
      }
    ?>
    </div>
    <?php } elseif ($peachtalkDisabled) { ?>
    <div class="text-center" style="margin-bottom:20px;">
      <h3>PeachTalk has been disabled for this event.</h3>
    </div>
    <?php } elseif ($peachtalkUsername != "none") { ?>
    <div class="icons-user">
        <div class="col-xs-4 col-xs-offset-2" id="boxes-container-left">
            <div class="text-center">
                <button class="messaging-box messaging-box-row1" id="general-box">
                    <div class="glyphicon glyphicon-bullhorn messaging-box-icon general-box-icon"></div>
                    <div class="messaging-box-text" id="general-box-text">General<span id="general-box-unread"></span></div>                
                </button>
                <button class="messaging-box messaging-box-row2" id="schedule-box">
                    <div class="glyphicon glyphicon-time messaging-box-icon schedule-box-icon"></div>
                    <div class="messaging-box-text" id="schedule-box-text">Schedule<span id="schedule-box-unread"></span></div>
                </button>
                <button class="messaging-box messaging-box-row3" id="private-box">
                    <div class="glyphicon glyphicon-comment messaging-box-icon icon-row3 private-box-icon"></div>
                    <div class="messaging-box-text" id="private-box-text">Message<span id="private-box-unread"></span></div>
                </button>
            </div>
        </div>
        <div class="col-xs-4" id="boxes-container-right">
            <div class="text-center">
                <button class="messaging-box messaging-box-row1" id="parts-box">
                    <div class="glyphicon glyphicon-wrench messaging-box-icon parts-box-icon"></div>
                    <div class="messaging-box-text" id="parts-box-text">Parts<span id="parts-box-unread"></span></div>
                </button>
                <button class="messaging-box messaging-box-row2" id="safety-box">
                    <div class="glyphicon glyphicon-warning-sign messaging-box-icon safety-box-icon"></div>
                    <div class="messaging-box-text" id="safety-box-text">Safety<span id="safety-box-unread"></span></div>
                </button>
                <button class="messaging-box messaging-box-row3" id="settings-box">
                    <div class="glyphicon glyphicon-cog messaging-box-icon icon-row3 settings-box-icon"></div>
                    <div class="messaging-box-text" id="settings-box-text">Settings</div>
                </button>
            </div>
        </div>
    </div>
    <?php } elseif (loggedOn()) { ?>
    <div class="icons-default">
        <div class="col-xs-4 col-xs-offset-2" id="boxes-container-left">
            <div class="text-center">
                <button class="messaging-box messaging-box-row1" id="general-box">
                    <div class="glyphicon glyphicon-bullhorn messaging-box-icon general-box-icon"></div>
                    <div class="messaging-box-text" id="general-box-text">General</div>                
                </button>
                <button class="messaging-box messaging-box-row2" id="schedule-box">
                    <div class="glyphicon glyphicon-time messaging-box-icon schedule-box-icon"></div>
                    <div class="messaging-box-text" id="schedule-box-text">Schedule</div>
                </button>
                <button class="messaging-box messaging-box-row3" id="join-box">
                    <div class="glyphicon glyphicon-user messaging-box-icon icon-row3 join-box-icon"></div>
                    <div class="messaging-box-text" id="join-box-text">Join</div>
                </button>
            </div>
        </div>
        <div class="col-xs-4" id="boxes-container-right">
            <div class="text-center">
                <button class="messaging-box messaging-box-row1" id="parts-box">
                    <div class="glyphicon glyphicon-wrench messaging-box-icon parts-box-icon"></div>
                    <div class="messaging-box-text" id="parts-box-text">Parts</div>
                </button>
                <button class="messaging-box messaging-box-row2" id="safety-box">
                    <div class="glyphicon glyphicon-warning-sign messaging-box-icon safety-box-icon"></div>
                    <div class="messaging-box-text" id="safety-box-text">Safety</div>
                </button>
                <button class="messaging-box messaging-box-row3" id="settings-box">
                    <div class="glyphicon glyphicon-cog messaging-box-icon icon-row3 settings-box-icon"></div>
                    <div class="messaging-box-text" id="settings-box-text">Settings</div>
                </button>
            </div>
        </div>
    </div>
    <?php } else { ?>
    <div class="icons-no-user">
        <div class="col-xs-4 col-xs-offset-2" id="boxes-container-left">
            <div class="text-center">
                <button class="messaging-box messaging-box-row1" id="general-box">
                    <div class="glyphicon glyphicon-bullhorn messaging-box-icon general-box-icon"></div>
                    <div class="messaging-box-text" id="general-box-text">General</div>                
                </button>
                <button class="messaging-box messaging-box-row2" id="schedule-box">
                    <div class="glyphicon glyphicon-time messaging-box-icon schedule-box-icon"></div>
                    <div class="messaging-box-text" id="schedule-box-text">Schedule</div>
                </button>
                <button class="messaging-box messaging-box-row3" id="join-box">
                    <div class="glyphicon glyphicon-user messaging-box-icon icon-row3 join-box-icon"></div>
                    <div class="messaging-box-text" id="join-box-text">Join</div>
                </button>
            </div>
        </div>
        <div class="col-xs-4" id="boxes-container-right">
            <div class="text-center">
                <button class="messaging-box messaging-box-row1" id="parts-box">
                    <div class="glyphicon glyphicon-wrench messaging-box-icon parts-box-icon"></div>
                    <div class="messaging-box-text" id="parts-box-text">Parts</div>
                </button>
                <button class="messaging-box messaging-box-row2" id="safety-box">
                    <div class="glyphicon glyphicon-warning-sign messaging-box-icon safety-box-icon"></div>
                    <div class="messaging-box-text" id="safety-box-text">Safety</div>
                </button>
                <button class="messaging-box messaging-box-row3" id="login-box">
                    <div class="glyphicon glyphicon-log-in messaging-box-icon icon-row3 login-box-icon"></div>
                    <div class="messaging-box-text" id="login-box-text">Sign In</div>
                </button>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php if (isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role)) { ?>
<script>
    document.getElementById('general-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/general?event=<?php echo $currentEvent; ?>'});
    document.getElementById('schedule-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/schedule?event=<?php echo $currentEvent; ?>'});
    document.getElementById('parts-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/parts?event=<?php echo $currentEvent; ?>'});
    document.getElementById('safety-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/safety?event=<?php echo $currentEvent; ?>'});
    document.getElementById('private-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/manage-messages?event=<?php echo $currentEvent; ?>'});
    document.getElementById('users-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/manage-users?event=<?php echo $currentEvent; ?>'});
    document.getElementById('requests-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/manage-requests?event=<?php echo $currentEvent; ?>'});
    document.getElementById('export-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/export?event=<?php echo $currentEvent; ?>'});
</script>
<?php } elseif ($peachtalkUsername != "none" && !$peachtalkDisabled) { ?>
<script>
    document.getElementById('general-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/general?event=<?php echo $currentEvent; ?>'});
    document.getElementById('schedule-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/schedule?event=<?php echo $currentEvent; ?>'});
    document.getElementById('parts-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/parts?event=<?php echo $currentEvent; ?>'});
    document.getElementById('safety-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/safety?event=<?php echo $currentEvent; ?>'});
    document.getElementById('private-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/manage-messages?event=<?php echo $currentEvent; ?>'});
    document.getElementById('settings-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/settings?event=<?php echo $currentEvent; ?>'});
</script>
<?php } elseif (loggedOn() && !$peachtalkDisabled) { ?>
<script>
    document.getElementById('general-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/general?event=<?php echo $currentEvent; ?>'});
    document.getElementById('schedule-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/schedule?event=<?php echo $currentEvent; ?>'});
    document.getElementById('parts-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/parts?event=<?php echo $currentEvent; ?>'});
    document.getElementById('safety-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/safety?event=<?php echo $currentEvent; ?>'});
    document.getElementById('join-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/join?event=<?php echo $currentEvent; ?>'});
    document.getElementById('settings-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/settings?event=<?php echo $currentEvent; ?>'});
</script>
<?php }  elseif (!$peachtalkDisabled) { ?>
<script>
    document.getElementById('general-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/general?event=<?php echo $currentEvent; ?>'});
    document.getElementById('schedule-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/schedule?event=<?php echo $currentEvent; ?>'});
    document.getElementById('parts-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/parts?event=<?php echo $currentEvent; ?>'});
    document.getElementById('safety-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/safety?event=<?php echo $currentEvent; ?>'});
    document.getElementById('join-box').addEventListener('click', function () {window.location='/peachpits/peachtalk/join?event=<?php echo $currentEvent; ?>'});
    document.getElementById('login-box').addEventListener('click', function () {window.location='/peachpits/signin?event=<?php echo $currentEvent; ?>&refer=peachpits'});
</script>
<?php } ?>
<?php
    if ($peachtalkUsername != 'none') {
        $channels = array("General", "Parts", "Schedule", "Safety");
        $unreadCounts = array("General" => 0, "Parts" => 0, "Schedule" => 0, "Safety" => 0, "Private" => 0, "Requests" => 0);
        for ($index = 0; $index < count($channels); $index++) {
            $channel = $channels[$index];
            $sql = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user` = '$userEmail' AND `channel` = '$channel'");
            $row = mysqli_fetch_assoc($sql);
            $lastVisit = $row['last_visited'];
            $sql = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel` = '$channel'");
            while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                if ($row['time_sent'] > $lastVisit) {
                    $unreadCounts["".$channel.""] += 1;
                }
            }
        }
        
        $sql = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user`='$userEmail' AND `channel`='Private'");
        while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
            $lastVisit = $row['last_visited'];
            $groupid = $row['groupid'];
            $sqlGroups = $mysqli->query("SELECT * FROM `".$eventGroups."` WHERE `groupid`='$groupid'");
            $rowGroups = mysqli_fetch_assoc($sqlGroups);
            $users = $rowGroups['private_users'];
            $teams = $rowGroups['private_teams'];
            if (!(isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role))){
                //for users
                if (in_array($userId, explode(";", $users))) {
                    $sql2 = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel` = 'Private' AND `groupid`='$groupid'");
                    while ($row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH)) {
                        if ($row2['time_sent'] > $lastVisit) {
                            $unreadCounts['Private'] += 1;
                        }
                    }
                }
                elseif (in_array($userTeam, explode(";", $teams))) {
                    $sql2 = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel` = 'Private' AND `groupid`='$groupid'");
                    while ($row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH)) {
                        if ($row2['time_sent'] > $lastVisit) {
                            $unreadCounts['Private'] += 1;
                        }
                    }
                }
            }
            else {
                //for admin
                if (in_array($userTeam, explode(";", $users))) {
                    $sql2 = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel` = 'Private' AND `groupid`='$groupid'");
                    while ($row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH)) {
                        if ($row2['time_sent'] > $lastVisit) {
                            $unreadCounts['Private'] += 1;
                        }
                    }
                }
                elseif (in_array($userTeam, explode(";", $teams))) {
                    $sql2 = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel` = 'Private' AND `groupid`='$groupid'");
                    while ($row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH)) {
                        if ($row2['time_sent'] > $lastVisit) {
                            $unreadCounts['Private'] += 1;
                        }
                    }
                }
            }
        }

        if (isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role)) {
            $pendingRequests= 0;
            $sql = $mysqli->query("SELECT * FROM `requests` WHERE `status` = 'Pending' AND `requestedrole` = 'Communication Liaison' AND `event` = '$eventname'");
            while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                $unreadCounts["Requests"] += 1;
            }
        }
?>
    <script>
        unreadCounts = {'General': <?php echo $unreadCounts["General"]; ?>, 'Parts': <?php echo $unreadCounts["Parts"]; ?>, 'Schedule': <?php echo $unreadCounts["Schedule"]; ?>, 'Safety': <?php echo $unreadCounts["Safety"]; ?>, 'Private': <?php echo $unreadCounts["Private"]; ?>, 'Requests': <?php echo $unreadCounts["Requests"]; ?>};
        if (unreadCounts['General'] > 99) {
            unreadCounts['General'] = "99+";
        }
        if (unreadCounts['Parts'] > 99) {
            unreadCounts['Parts'] = "99+";
        }
        if (unreadCounts['Schedule'] > 99) {
            unreadCounts['Schedule'] = "99+";
        }
        if (unreadCounts['Safety'] > 99) {
            unreadCounts['Safety'] = "99+";
        }
        if (unreadCounts['Private'] > 99) {
            unreadCounts['Private'] = "99+";
        }
        if (unreadCounts['Requests'] > 99) {
            unreadCounts['Requests'] = "99+";
        }
        if (unreadCounts['General'] != 0) {
            document.getElementById('general-box-unread').innerHTML = '&nbsp;(' + unreadCounts['General'] + ')';
        }
        if (unreadCounts['Parts'] != 0) {
            document.getElementById('parts-box-unread').innerHTML = '&nbsp;(' + unreadCounts['Parts'] + ')';            
        }
        if (unreadCounts['Schedule'] != 0) {
            document.getElementById('schedule-box-unread').innerHTML = '&nbsp;(' + unreadCounts['Schedule'] + ')';            
        }
        if (unreadCounts['Safety'] != 0) {
            document.getElementById('safety-box-unread').innerHTML = '&nbsp;(' + unreadCounts['Safety'] + ')';
            document.getElementById('safety-box').classList.add('box-flash');
        }
        if (unreadCounts['Private'] != 0) {
            document.getElementById('private-box-unread').innerHTML = '&nbsp;(' + unreadCounts['Private'] + ')';
        }
        if (unreadCounts['Requests'] != 0) {
            document.getElementById('requests-box-unread').innerHTML = '&nbsp;(' + unreadCounts['Requests'] + ')';
        }
    </script>
<?php } ?>
<?php } include dirname(__DIR__) . "/footer.php"; ?>