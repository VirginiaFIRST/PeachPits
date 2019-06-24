<title>PeachTalk - Messages</title>
<?php 
	include dirname(__DIR__) . "/header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="/peachpits/chooseevent"</script>';
	}
	else {
    $sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
    $row = mysqli_fetch_assoc($sql);
    $eventname = $row['eventname'];
    $eventMatches = $currentEvent . "_matches";
    $eventMessages = $currentEvent . "_messages";
    $eventGroups = $currentEvent . "_groups";
    $eventActivity = $currentEvent . "_activity";
    $eventLiaisons = $currentEvent . "_liaisons";
    $eventTeams = $currentEvent . "_teams";
    $userArr = explode(";", $peachtalkUsername);
    $userEmail = $userArr[0];
    $userTeam = $userArr[1];
    $userName = $userArr[2];
    $userId = $userArr[3];
    $teamList = array();
    $sql = $mysqli->query("SELECT * FROM `".$eventTeams."`");
    while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
        $teamList[] = $row['teamid'];
    }
?>

<script src="js/selectize.js"></script>
<link rel="stylesheet" type="text/css" href="css/selectize.css" />
<div class="header-btn-container text-center">
    <div class="container">
        <div class="row">
            <table width="100%">
                <tr>
                    <td id="back-btn-cell" style="padding-left:15px;width:10%">
                        <a class="pull-left btn btn-default back-btn" id="back-btn" href="/peachpits/peachtalk/peachtalk-home?event=<?php echo $currentEvent; ?>"><span class="glyphicon glyphicon-chevron-left"></span><span id="back-btn-text"> Back</span></a>
                    </td>
                    <td class="text-center">
                        <h4 class="channel-header-text">Manage Private Messages</h4>
                    </td>
                    <td style="padding-right:15px;width:10%" id="right-cell"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="container content" style="margin-top:20px">
    <div class="text-center">
        <div class="container">
            <div class="row">
                <a class="btn btn-default new-message-btn" id="new-message-btn" href="#" data-toggle="modal" data-target="#new-message-modal"><span class="glyphicon glyphicon-plus"></span><span id="new-message-text"> Start New Message</span></a>
            </div>
        </div>
    </div>
    <div id="ordered-channels"></div>
    <?php
        $sql = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user`='$userEmail' AND `channel`='Private'");
        while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
            $lastVisit = $row['last_visited'];
            $groupid = $row['groupid'];
            $sqlGroups = $mysqli->query("SELECT * FROM `".$eventGroups."` WHERE `groupid`='$groupid'");
            $rowGroups = mysqli_fetch_assoc($sqlGroups);
            $groupName = $rowGroups['name'];
            $users = $rowGroups['private_users'];
            $teams = $rowGroups['private_teams'];
            $lastActive = "";
            $unread = 0;
            $usersOrTeams = "";
            $inChannel = false;
            if (!(isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role))) {
                //for users
                if (in_array($userId, explode(";", $users))) {
                    $inChannel = true;
                    $usersOrTeams = "users";
                    $sql2 = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel` = 'Private' AND `groupid`='$groupid'");
                    while ($row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH)) {
                        if ($lastActive == "" || $row2['time_sent'] > $lastActive) {
                            $lastActive = $row2['time_sent'];
                        }
                        if ($row2['time_sent'] > $lastVisit) {
                            $unread += 1;
                        }
                    }
                }
                elseif (in_array($userTeam, explode(";", $teams))) {
                    $inChannel = true;
                    $usersOrTeams = "teams";
                    $sql2 = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel` = 'Private' AND `groupid`='$groupid'");
                    while ($row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH)) {
                        if ($lastActive == "" || $row2['time_sent'] > $lastActive) {
                            $lastActive = $row2['time_sent'];
                        }
                        if ($row2['time_sent'] > $lastVisit) {
                            $unread += 1;
                        }
                    }
                }
            }
            else {
                //for admin
                if (in_array($userTeam, explode(";", $users))) {
                    $inChannel = true;
                    $usersOrTeams = "users";
                    $sql2 = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel` = 'Private' AND `groupid`='$groupid'");
                    while ($row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH)) {
                        if ($lastActive == "" || $row2['time_sent'] > $lastActive) {
                            $lastActive = $row2['time_sent'];
                        }
                        if ($row2['time_sent'] > $lastVisit) {
                            $unread += 1;
                        }
                    }
                }
                elseif (in_array($userTeam, explode(";", $teams))) {
                    $inChannel = true;
                    $usersOrTeams = "teams";
                    $sql2 = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel` = 'Private' AND `groupid`='$groupid'");
                    while ($row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH)) {
                        if ($lastActive == "" || $row2['time_sent'] > $lastActive) {
                            $lastActive = $row2['time_sent'];
                        }
                        if ($row2['time_sent'] > $lastVisit) {
                            $unread += 1;
                        }

                    }
                }
            }
            if ($inChannel) {
                echo '<div class="private-channel-box" id="'.str_replace(" ", "-", $groupid).'" onclick="goToChannel(this)">';
                echo '<div class="box-wrap">';
                if ($usersOrTeams == "users") {
                    $usersArr = explode(";", $users);
                    if (count($usersArr) == 2) {
                        if ($usersArr[0] == $userId || in_array($userTeam, ['Super Admin', 'Event Admin', 'Pit Admin'])) {
                            $members = $usersArr[1];
                        }
                        else {
                            $members = $usersArr[0];
                        }
                        $sql2 = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `userid`='$members'");
                        $row2 = mysqli_fetch_assoc($sql2);
                        $members = $row2['user'];
                    }
                    else {
                        foreach ($usersArr as &$user) {
                            if (!in_array($user, ['Super Admin', 'Event Admin', 'Pit Admin'])) {
                                $sql2 = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `userid`='$user'");
                                $row2 = mysqli_fetch_assoc($sql2);
                                $user = $row2['user'];
                            }
                        }
                        $lastUser = end($usersArr);
                        array_pop($usersArr);
                        $members = implode(", ", $usersArr) . " and " . $lastUser;
                    }
                }
                elseif ($usersOrTeams == "teams") {
                    $teamsArr = explode(";", $teams);
                    if (count($teamsArr) == 2) {
                        if ($teamsArr[0] == $userTeam || in_array($userTeam, ['Super Admin', 'Event Admin', 'Pit Admin'])) {
                            $members = $teamsArr[1];
                        }
                        else {
                            $members = $teamsArr[0];
                        }
                        if (is_numeric($members)) {
                            $members = 'Team '.$members;
                        }
                    }
                    else {
                        $lastTeam = end($teamsArr);
                        array_pop($teamsArr);
                        $members = implode(", ", $teamsArr) . " and " . $lastTeam;
                    }
                }
                if ($groupName == 'none') {
                    $groupName = $members;
                }
                if (strlen($groupName) > 35) {
                    $groupName = substr($groupName, 0, 30) . '...';
                }
                echo '<h3 class="last-active hidden">'.$lastActive.'</h3>';
                echo '<h3 class="private-channel-box-text">'.$groupName.'</h3>';
                echo '<h3 class="private-channel-box-text">Unread Messages: ('.$unread.')</h3>';
                echo '</div>';
                echo '</div>';
            }
        }
    ?>
</div>

<!-- New message popup -->
<div class="modal fade" id="new-message-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 style="display:inline" class="modal-title" id="myModalLabel">New Message</h4>
      </div>
      <div class="modal-body text-center">
        <h4 style="display:inline">Choose the Teams/Users/Alliance to message</h4>
        <form action="/peachpits/peachtalk/new_message?event=<?php echo $currentEvent; ?>" method="post">
            <input type="hidden" name="username" value="<?php echo $peachtalkUsername; ?>">
            <h5 class="pull-left">Choose Team(s)</h5>
			<select id="teams-select" name="teams[]" multiple>
                <?php
                    $teamList = array();
                    $sql = $mysqli->query("SELECT * FROM `".$eventTeams."`");
                    while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                        $teamList[] = $row['teamid'];
                        if ($row['teamid'] != $userTeam) {
                            echo '<option value="'.$row['teamid'].'">'.$row['teamid'].'</option>';
                        }
                    }
                ?>
            </select>
            <br>
            <h4>OR</h4>
            <h5 class="pull-left">Choose User(s)</h5>
			<select id="users-select" name="users[]" multiple>
                <?php
                    $currentTeam = 0;
                    if (!(isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role))) {
                        $sql = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `status`='Approved' AND NOT `email`='$userEmail' ORDER BY `teamid` ASC, `user` ASC");
                    }
                    else {
                        $sql = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `status`='Approved' ORDER BY `teamid` ASC, `user` ASC");                        
                    }
                    while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                        $teamid = $row['teamid'];
                        if ($currentTeam == 0) {
                            $currentTeam = $teamid;
                        }
                        if (in_array($teamid, $teamList)) {
                            echo '<optgroup label="'.$teamid.'">';
                            $teamList = array_diff($teamList, [$teamid]);
                        }
                        if ($currentTeam != $teamid) {
                            echo '</optgroup>';
                            $currentTeam = $teamid;
                        }
                        echo '<option value="'.$row['userid'].'">'.$row['user'].'</option>';
                    }
                ?>
            </select>
            <br>
            <h4>OR</h4>
            <h5 class="pull-left">Choose a Match</h5>
			<select id="match-select" name="matchteams">
                <option value="">Choose a Match</option>
                <?php
                    $sql = $mysqli->query("SELECT * FROM `".$eventMatches."` WHERE `matchtype` LIKE 'qm' ORDER BY matchnumber ASC");
                    while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                        if (isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role)) {
                            //for admin
                            echo '<option value="'.$row['matchnumber'].';Red;'.$row['red1'].';'.$row['red2'].';'.$row['red3'].'"><span class="red-alliance-text">#'.$row['matchnumber'].' Red Alliance : '.$row['red1'].' | '.$row['red2'].' | '.$row['red3'].'</span></option>';
                            echo '<option value="'.$row['matchnumber'].';Blue;'.$row['blue1'].';'.$row['blue2'].';'.$row['blue3'].'">#'.$row['matchnumber'].' Blue Alliance : '.$row['blue1'].' | '.$row['blue2'].' | '.$row['blue3'].'</option>';
                        }
                        else {
                            //for users
                            if (in_array($userTeam, array($row['red1'], $row['red2'], $row['red3']))) {
                                echo '<option value="'.$row['matchnumber'].';Red;'.$row['red1'].';'.$row['red2'].';'.$row['red3'].'"><span class="red-alliance-text">#'.$row['matchnumber'].' Red Alliance : '.$row['red1'].' | '.$row['red2'].' | '.$row['red3'].'</span></option>';
                            }
                            elseif (in_array($userTeam, array($row['blue1'], $row['blue2'], $row['blue3']))) {
                                echo '<option value="'.$row['matchnumber'].';Blue;'.$row['blue1'].';'.$row['blue2'].';'.$row['blue3'].'">#'.$row['matchnumber'].' Blue Alliance : '.$row['blue1'].' | '.$row['blue2'].' | '.$row['blue3'].'</option>';
                            }
                        }
                    }
                ?>
            </select>
        <br>
        <hr>
        <h5 class="pull-left">Group Name (Optional)</h5>
        <textarea class="form-control input-lg no-radius text-center" id="group-name" style="resize:none" name="groupname" rows="1" maxlength="50" placeholder="Group Name (Optional)"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-default" name="submit" id="create-channel-btn" disabled="disabled">Create</button></form>
      </div>
    </div>
  </div>
</div>

<script>
    if (window.innerWidth <= 500 && document.getElementById('back-btn-text').style.display != 'none') {
        document.getElementById('back-btn').classList.add('back-btn-minimized');
        document.getElementById('back-btn-text').style.display = 'none';
        document.getElementById('back-btn-cell').style.width = 28;
        document.getElementById('right-cell').style.width = 28;
    }
    else {
        document.getElementById('back-btn-cell').style.width = 90;
        document.getElementById('right-cell').style.width = 90;
    }
    $(window).resize(function () {
        if (window.innerWidth <= 500 && document.getElementById('back-btn-text').style.display != 'none') {
            document.getElementById('back-btn').classList.add('back-btn-minimized');
            document.getElementById('back-btn-text').style.display = 'none';
            document.getElementById('back-btn-cell').style.width = 28;
            document.getElementById('right-cell').style.width = 28;
        }
        else if (window.innerWidth > 500 && document.getElementById('back-btn-text').style.display == 'none') {
            document.getElementById('back-btn').classList.remove('back-btn-minimized');
            document.getElementById('back-btn-text').style.display = 'initial';
            document.getElementById('back-btn-cell').style.width = 90;
            document.getElementById('right-cell').style.width = 90;
        }
    });
    $('#users-select').selectize({
        maxItems: null,
        placeholder: 'Choose User(s)',
        onChange: function(value) {
            if (value == null) {
                $('#teams-select')[0].selectize.enable();
                $('#match-select')[0].selectize.enable();
                $('#create-channel-btn').attr('disabled', 'disabled');
            }
            else {
                $('#teams-select')[0].selectize.disable();
                $('#match-select')[0].selectize.disable();
                $('#create-channel-btn').removeAttr('disabled');
            }
        }
    });
    $('#teams-select').selectize({
        maxItems: null,
        placeholder: 'Choose Team(s)',
        onChange: function(value) {
            if (value == null) {
                $('#users-select')[0].selectize.enable();
                $('#match-select')[0].selectize.enable();
                $('#create-channel-btn').attr('disabled', 'disabled');
            }
            else {
                $('#users-select')[0].selectize.disable();
                $('#match-select')[0].selectize.disable();
                $('#create-channel-btn').removeAttr('disabled');
            }
        }
    });
    $('#match-select').selectize({
        placeholder: 'Choose a Match',
        onChange: function(value) {
            if (value == '') {
                document.getElementById('group-name').value = value;
                $('#users-select')[0].selectize.enable();
                $('#teams-select')[0].selectize.enable();
                $('#create-channel-btn').attr('disabled', 'disabled');
            }
            else {
                valueArr = value.split(';');
                document.getElementById('group-name').value = '#' + valueArr[0] + ' ' + valueArr[1] + ' Alliance : ' + valueArr[2] + ' | ' + valueArr[3] + ' | ' + valueArr[4];
                $('#users-select')[0].selectize.disable();
                $('#teams-select')[0].selectize.disable();
                $('#create-channel-btn').removeAttr('disabled');
            }
        },
        render: {
            option: function(option) {
                if (option.text.indexOf('Blue') != -1) {
                    return '<div class="blue-alliance-text">' + option.text + '</div>';
                }
                else {
                    return '<div class="red-alliance-text">' + option.text + '</div>';
                }
            },
            item: function(item) {
                if (item.text.indexOf('Blue') != -1) {
                    return '<div class="blue-alliance-text">' + item.text + '</div>';
                }
                else {
                    return '<div class="red-alliance-text">' + item.text + '</div>';
                }
            }
        }
    });
    $('#new-message-modal').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
            if (keyCode === 13) { 
                e.preventDefault();
                return false;
            }
    });
    function goToChannel (elem) {
        window.location = "/peachpits/peachtalk/private-message?event=<?php echo $currentEvent; ?>&groupid=" + elem.id;
    }
    document.getElementsByClassName('navbar')[0].style.display = 'none';

    var lastActiveArr = document.getElementsByClassName('last-active');
    var timesArr = [];
    for (var index = 0; index < lastActiveArr.length; index++) {
        timesArr.push(lastActiveArr[index].innerHTML);
    }
    timesArr.sort();
    timesArr.reverse();
    for (var index = 0; index < timesArr.length; index++) {
        for (var j = 0; j < lastActiveArr.length; j++) {
            if (timesArr[index] == lastActiveArr[j].innerHTML) {
                $(lastActiveArr[j].parentElement.parentElement).appendTo('#ordered-channels');
            }
        }
    }
</script>

<?php } include dirname(__DIR__) . "/footer.php"; ?>