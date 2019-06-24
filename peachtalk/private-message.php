<title>PeachTalk - Private Message</title>
<?php 
	include dirname(__DIR__) . "/header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="/peachpits/chooseevent"</script>';
	}
	else {
    $eventGroups = $currentEvent . "_groups";
    $groupid = $_GET['groupid'];
    $sql = $mysqli->query("SELECT * FROM `".$eventGroups."` WHERE `groupid`='$groupid'");
    $row = mysqli_fetch_assoc($sql);
    if ($row['private_users'] != '') {
        $users = $row['private_users'];
    }
    else {
        $users = null;
    }
    if ($row['private_teams'] != '') {
        $teams = $row['private_teams'];
    }
    else {
        $teams = null;
    }
    $groupName = $row['name'];
    $userArr = explode(";", $peachtalkUsername);
    $userEmail = $userArr[0];
    $user = $userArr[1];
    $userName = $userArr[2];
    $userId = $userArr[3];
    $userRestrictions = $userArr[4];
    $channel = 'Private';
    if (strpos($userRestrictions, "Private") !== false) {
        echo '<script>var restricted = true;</script>';
    }
    else {
        echo '<script>var restricted = false;</script>';            
    }
    $twoUsers = false;
    if (count(explode(';', $users)) == 2) {
        $twoUsers = true;
    }
    if ($users != null && $teams != null || $peachtalkUsername == 'none') {
        echo '<script>window.location="/peachpits/peachtalk/peachtalk-home?event='.$currentEvent.'"</script>';
    }
    if (!(isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role)) && strpos($users, $userId) === false && strpos($teams, $user) === false) {
        echo '<script>window.location="/peachpits/peachtalk/peachtalk-home?event='.$currentEvent.'"</script>';
    } 
    echo '<script>var username = "'.$peachtalkUsername.'";var channel = "'.$channel.'";var groupid = "'.$groupid.'";</script>';
    
    $sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
    $row = mysqli_fetch_assoc($sql);
    $eventname = $row['eventname'];
    $eventMessages = $currentEvent . "_messages";
    $eventActivity = $currentEvent . "_activity";
    $eventLiaisons = $currentEvent . "_liaisons";
?>

<div class="header-btn-container text center">
    <div class="container">
        <div class="row">
            <table width="100%">
                <tr>
                    <td id="back-btn-cell" style="padding-left:15px;width:10%">
                        <a class="pull-left btn btn-default back-btn" id="back-btn" href="/peachpits/peachtalk/manage-messages?event=<?php echo $currentEvent; ?>"><span class="glyphicon glyphicon-chevron-left"></span><span id="back-btn-text"> Back</span></a>
                    </td>
                    <td class="text-center">
                        <?php
                            if ($groupName != 'none') {
                                echo '<h4 class="channel-header-text">'.$groupName.'</h4>';
                            }
                            else {
                                echo '<h4 class="channel-header-text">Private Message</h4>';
                            }
                        ?>
                    </td>
                    <td style="padding-right:15px;width:10%" id="right-cell">
                        <button class="pull-right blue-link" style="border:none;background-color:initial"><div class="glyphicon glyphicon-info-sign pull-right" style="font-size:18px" data-toggle="modal" data-target="#message-info-modal"></div></button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="header-btn-container text-center channel-info" style="padding:0px">
    <div class="container">
        <div class="row">
        <?php 
            if (isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role)) {
                echo '<h5>'.$user.' | Event: '.$eventName.'</h5>';
            }
            elseif ($peachtalkUsername != 'none') {
                echo '<h5>Team: '.$user.' | Event: '.$eventName.'</h5>';
            }
        ?>
        </div>
    </div>
</div>
<div class="container content" id="channel-container">
    <div id="chat-container" class="chat-container-color">
        <div class="new-message-alert" id="new-message-alert">New Messages <span class="glyphicon glyphicon-arrow-down"></span></div>
        <div id="chat-wrap">
            <?php
                if ($users != null) {
                    $query = "SELECT * FROM `".$eventActivity."` WHERE `user` = '$userEmail' AND `channel`='$channel' AND `groupid`='$groupid'";
                }
                else {
                    $query = "SELECT * FROM `".$eventActivity."` WHERE `user` = '$userEmail' AND `channel`='$channel' AND `groupid`='$groupid'";
                }
                $sql = $mysqli->query($query);
                $row = mysqli_fetch_assoc($sql);
                $lastVisit = $row['last_visited'];
                $loopDate;
                $printedDates = array();
                $currentDate = new DateTime(null, new DateTimeZone('America/New_York'));
                $currentDateDay = new DateTime($currentDate->format('Y-m-d'));
                if ($users != null) {
                    $query = "SELECT * FROM `".$eventMessages."` WHERE `channel`='$channel' AND `groupid`='$groupid'";
                }
                else {
                    $query = "SELECT * FROM `".$eventMessages."` WHERE `channel`='$channel' AND `groupid`='$groupid'";                    
                }
                $sql = $mysqli->query($query);
                while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                    $sentByArr = explode(";", $row['sent_by']);
                    $sentByEmail = $sentByArr[0];
                    $sentBy = $sentByArr[1];
                    $sentByName = $sentByArr[2];
                    $sentById = $sentByArr[3];
                    if (!($sentBy == 'Pit Admin' || $sentBy == 'Event Admin' || $sentBy == 'Super Admin')) {
                        $sentBy = $sentByName . " - Team " . $sentBy;
                    }
                    $loopDate = new DateTime($row['time_sent']);
                    $loopTime = $loopDate->format('g:i A');
                    $loopDateDay = new DateTime($loopDate->format('Y-m-d'));
                    //$interval = date_diff($loopDate, $currentDate);
                    $interval = date_diff($loopDateDay, $currentDateDay);
                    $interval = $interval->format('%R%a');
                    if ($row['time_sent'] <= $lastVisit) {
                        if (!in_array($interval, $printedDates)) {
                            $printedDates[] = $interval;
                            if ($interval == '-1' || $interval == '+1') {
                                $loopTime = $loopDate->format('D, g:i A');
                                echo '<h2 class="text-center date-header">Yesterday</h2>';
                            }
                            elseif ($interval == '-0' || $interval == '+0') {
                                echo '<h2 class="text-center date-header">Today</h2>';
                            }
                            else {
                                $writtenDate = $loopDate->format('D, F jS Y');
                                $loopTime = $loopDate->format('D, g:i A');
                                echo '<h2 class="text-center date-header">'.$writtenDate.'</h2>';
                            }
                        }
                        if (!($interval == '-0' || $interval == '+0')) {
                            $loopTime = $loopDate->format('D, g:i A');
                        }
                        echo '<h4 class="message-info"><b>'.$sentBy.'</b> - '.$loopTime.'</h4><p class="height2"></p><h3 class="message">'.$row['message'].'</h3><p class="height3"></p>';
                    }
                }
            ?>
        </div>
    </div>
    <?php if (strpos($userRestrictions, "Private") !== false) { ?>
    <div id="send-message-wrap">
        <div class="glyphicon glyphicon-comment" id="comment-glyphicon"></div>
        <textarea class="form-control input-lg no-radius autoExpand" id="chat-textbox" rows="1" placeholder="You have been restricted from sending messages here." disabled="disabled"></textarea>        
        <div class="glyphicon glyphicon-send hidden" id="send-message-btn"></div>
    </div>
    <?php } else { ?>
    <div id="send-message-wrap">
        <div class="glyphicon glyphicon-comment" id="comment-glyphicon"></div>
        <textarea class="form-control input-lg no-radius autoExpand" id="chat-textbox" rows="1" placeholder="Send Message..."></textarea>
        <div class="glyphicon glyphicon-send hidden" id="send-message-btn"></div>
    </div>
    <?php } ?>
</div>

<script type="text/javascript" src="peachtalk/js/chat.js"></script>
<script>
    var chat = new Chat(currentEvent, channel, username, groupid);
    chat.initialize();
    var chatContainer = document.getElementById("chat-container");
    $(window).load(function() {
        chatContainer.scrollTop = 99999;
        if (restricted) {
            if (document.getElementById('chat-textbox').scrollHeight > document.getElementById('chat-textbox').clientHeight) {
                document.getElementById('chat-textbox').rows = 2;
                document.getElementById('comment-glyphicon').style.marginTop = 24 - (2 * 24);
            }
            else {
                document.getElementById('chat-textbox').rows = 1;
                document.getElementById('comment-glyphicon').style.marginTop = 24 - (1 * 24);
            }
        }
    });

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
    document.getElementsByClassName('navbar')[0].style.display = 'none';

    if (document.getElementById('chat-wrap').innerHTML.indexOf("<h") == -1) {
        $('#chat-wrap').append('<h2 class="text-center date-header">No Messages Yet!</h2>');
    }

    function activateNewMessages() {
        $('#new-message-alert').fadeOut();
        var newInfos = document.getElementsByClassName('new-message-info');
        var newMessages = document.getElementsByClassName('new-message');
        var newInfosById = [];
        var newMessagesById = [];
        for (var i = 0; i < newInfos.length; i ++) {
            newInfosById.push(newInfos[i].id);
        }
        for (var i = 0; i < newMessages.length; i ++) {
            newMessagesById.push(newMessages[i].id);
        }
        setTimeout(function() {
            removeNewMessageStyle(newInfosById, newMessagesById);
        }, 10000);
    }

    function removeNewMessageStyle(newInfosById, newMessagesById) {
        for (var i = 0; i < newInfosById.length; i++) {
            document.getElementById(newInfosById[i]).classList.remove('new-message-info');
        }
        for (var i = 0; i < newMessagesById.length; i++) {
            document.getElementById(newMessagesById[i]).classList.remove('new-message');
        }
    }

    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (document.getElementById('chat-wrap').innerHTML.indexOf('<h2 class="text-center date-header">No Messages Yet!</h2>') != -1) {
                document.getElementById('chat-wrap').innerHTML = document.getElementById('chat-wrap').innerHTML.replace('<h2 class="text-center date-header">No Messages Yet!</h2>', '');
            }
            if (chatContainer.scrollHeight <= chatContainer.clientHeight) {
                activateNewMessages();
            }
            else {
                $('#new-message-alert').fadeIn();
            }
        });
    });
    observer.observe(document.getElementById('chat-wrap'), {
        subtree: true,
        childList: true
    });

    $(document).ready(function() {
        $(document)
        .one('focus.autoExpand', 'textarea.autoExpand', function(){
            var savedValue = this.value;
            this.value = '';
            this.baseScrollHeight = this.scrollHeight;
            this.value = savedValue;
        })
        .on('input.autoExpand', 'textarea.autoExpand', function(){
            this.rows = 1;
            rows = Math.ceil((this.scrollHeight - this.baseScrollHeight) / 23.99);
            if (rows == 0) {
                this.rows = 1;
                document.getElementById('new-message-alert').style.marginBottom = 0;
                document.getElementById('comment-glyphicon').style.marginTop = 0;
                document.getElementById('send-message-btn').style.marginTop = 5;
            }
            else {
                this.rows = rows;
                document.getElementById('new-message-alert').style.marginBottom = (rows * 24);
                document.getElementById('comment-glyphicon').style.marginTop = 24 - (rows * 24);
                document.getElementById('send-message-btn').style.marginTop = 29 - (rows * 24);
            }
            if (this.value == '') {
                if (document.getElementById('send-message-btn').className == 'glyphicon glyphicon-send') {
                    document.getElementById('send-message-btn').className = 'glyphicon glyphicon-send hidden';
                }
                this.rows = 1;
            }
            else {
                if (document.getElementById('send-message-btn').className == 'glyphicon glyphicon-send hidden') {
                    document.getElementById('send-message-btn').className = 'glyphicon glyphicon-send';
                }
            }
        });
        $('#new-message-alert').on('click', function() {
            chatContainer.scrollTop = 99999;
            activateNewMessages();
        });
        $('#chat-container').on('scroll', function() {
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                console.log("Scrolled to bottom");
                activateNewMessages();
            }
        });
    });

</script>

<!-- Message info popup -->
<div class="modal fade" id="message-info-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 style="display:inline" class="modal-title" id="myModalLabel">Group Info</h3>
      </div>
      <div class="modal-body text-center">
        <?php
            echo '<h3>Group Name</h3>';
            echo '<h4 id="groupname">'.$groupName.'</h4>';
            // echo '<div class="hidden" id="change-group-div">';
            // echo '<input class="form-control input-lg no-radius text-center" id="groupname-text" style="margin-bottom:10px" name="groupname">';
            // echo '<button class="btn btn-default" id="change-group-cancel" style="margin-right:10px">Cancel</button><button class="btn btn-default" id="change-group-submit">Save</button></div>';
            // echo '<button class="btn btn-default" id="change-group-name">Change Name</button>';
            echo '<h3>Members</h3>';
            if ($users != null) {
                $usersArr = explode(";", $users);
                foreach ($usersArr as &$member) {
                    if (is_numeric($member)) {
                        $sql = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `userid`='$member'");
                        $row = mysqli_fetch_assoc($sql);
                        $member = $row['user'] . " <em>(Team " .$row['teamid']. ")</em>";
                    }
                }
                if (in_array("Super Admin", $usersArr) !== false) {
                    //echo '';
                }
                $lastUser = end($usersArr);
                array_pop($usersArr);
                $members = implode(", ", $usersArr) . " and " . $lastUser;
            }
            else {
                $teamsArr = explode(";", $teams);
                foreach ($teamsArr as &$member) {
                    if (is_numeric($member)) {
                        $member = "Team " . $member;
                    }
                }
                $lastTeam = end($teamsArr);
                array_pop($teamsArr);
                $members = implode(", ", $teamsArr) . " and " . $lastTeam;
            }
            echo '<h4>'.$members.'</h4>';
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--<script>
    document.getElementById('groupname-text').value = document.getElementById('groupname').innerText;
    $('#change-group-name').on('click', function () {
        document.getElementById('groupname-text').value = document.getElementById('groupname').innerText;
        document.getElementById('change-group-div').classList.remove('hidden');
        document.getElementById('change-group-name').classList.add('hidden');
        document.getElementById('groupname').classList.add('hidden');
    });
    $('#change-group-cancel').on('click', function () {
        document.getElementById('change-group-div').classList.add('hidden');
        document.getElementById('change-group-name').classList.remove('hidden');
        document.getElementById('groupname').classList.remove('hidden');
    });
</script>-->

<?php } include dirname(__DIR__) . "/footer.php"; ?>