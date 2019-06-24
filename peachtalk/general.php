<title>PeachTalk - General</title>
<?php 
	include dirname(__DIR__) . "/header.php";
	
	if (empty($currentEvent)) {
		echo '<script>window.location="/peachpits/chooseevent"</script>';
	}
	else {
    $eventMessages = $currentEvent . "_messages";
    $eventActivity = $currentEvent . "_activity";
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventname = $row['eventname'];
    $userArr = explode(";", $peachtalkUsername);
    $userEmail = $userArr[0];
    $userTeam = $userArr[1];
    $channel = 'General';
    $peachtalkDisabled = $row['peachtalkstatus'];
    if ($peachtalkDisabled && !isPeachTalkAdmin($role)) {
      echo '<script>window.location="/peachpits/peachtalk/peachtalk-home?event=' + $currentEvent + '"</script>';
    }

    echo '<script>var username = "'.$peachtalkUsername.'";var channel = "'.$channel.'";</script>';     
    if (!(isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role))) {
?>
<style>
    @media screen and (max-height: 650px) {
        #chat-container {
            height:calc(100% - 10px);
        }
    }
</style>
<?php } ?>
<div class="header-btn-container text-center">
    <div class="container">
        <div class="row">
            <table width="100%">
                <tr>
                    <td id="back-btn-cell" style="padding-left:15px;width:10%">
                        <a class="pull-left btn btn-default back-btn" id="back-btn" href="/peachpits/peachtalk/peachtalk-home?event=<?php echo $currentEvent; ?>"><span class="glyphicon glyphicon-chevron-left"></span><span id="back-btn-text"> Back</span></a>
                    </td>
                    <td class="text-center">
                        <h4 class="channel-header-text">General Messages</h4>
                    </td>
                    <td style="padding-right:15px;width:10%" id="right-cell"></td>
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
                echo '<h5>'.$userTeam.' | Event: '.$eventName.'</h5>';
            }
            elseif ($peachtalkUsername != 'none') {
                echo '<h5>Team: '.$userTeam.' | Event: '.$eventName.'</h5>';
            }
            else {
                echo '<h5>Event: '.$eventname.'</h5>';
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
                $currentDate = new DateTime(null, new DateTimeZone('America/New_York'));
                $currentDateDay = new DateTime($currentDate->format('Y-m-d'));
                $currentDateStamp = $currentDate->format('Y-m-d H:i:s.');
                $currentDateStamp .= '0000';
                if ($peachtalkUsername == "none") {
                    $lastVisit = $currentDateStamp;
                }
                else {
                    $sql = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user`='$userEmail' AND `channel`='$channel'");
                    $row = mysqli_fetch_assoc($sql);
                    $lastVisit = $row['last_visited'];
                }
                $loopDate;
                $printedDates = array();
                $sql = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel`='$channel'");
                while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                    $sentByArr = explode(";", $row['sent_by']);
                    $sentBy = $sentByArr[1];
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
                        echo '<h4 class="message-info"><b>'.$sentBy.'</b> - '.$loopTime.'</h4><p class="height2"><h3 class="message"></p>'.$row['message'].'</h3><p class="height3"></p>';
                    }                        
                }
            ?>
        </div>
    </div>
    <?php if (isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role)) { ?>
    <div id="send-message-wrap">
        <div class="glyphicon glyphicon-comment" id="comment-glyphicon"></div>
        <textarea class="form-control input-lg no-radius autoExpand" id="chat-textbox" rows="1" placeholder="Send Message..."></textarea>
        <div class="glyphicon glyphicon-send hidden" id="send-message-btn"></div>
    </div>
    <?php } ?>
</div>
<script type="text/javascript" src="peachtalk/js/chat.js"></script>
<script>
    if (username == "none") {
        var chat = new Chat(currentEvent, channel);
    }
    else {
        var chat = new Chat(currentEvent, channel, username);
    }
    chat.initialize();
    var chatContainer = document.getElementById("chat-container");
    $(window).load(function() {
        chatContainer.scrollTop = 99999;
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
<?php } include dirname(__DIR__) . "/footer.php"; ?>