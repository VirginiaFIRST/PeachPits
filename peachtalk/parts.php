<title>PeachTalk - Parts</title>
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
    $userRestrictions = $userArr[4];
    $peachtalkDisabled = $row['peachtalkstatus'];
    if ($peachtalkDisabled && !isPeachTalkAdmin($role)) {
      echo '<script>window.location="/peachpits/peachtalk/peachtalk-home?event=' + $currentEvent + '"</script>';
    }
    $channel = 'Parts';
    if (strpos($userRestrictions, "Parts") !== false) {
        echo '<script>var restricted = true;</script>';
    }
    else {
        echo '<script>var restricted = false;</script>';            
    }

    echo '<script>var username = "'.$peachtalkUsername.'";var channel = "'.$channel.'";var replyMessageId = "";</script>';
    if ($peachtalkUsername == 'none') {
?>
<style>
    @media screen and (max-height: 650px) {
        #chat-container {
            height:calc(100% - 10px);
        }
    }
    .reply-link, .glyphicon-share-alt, .reply-link-casing {
        display:none;
    }
</style>
<?php } ?>
<div class="header-btn-container text center">
    <div class="container">
        <div class="row">
            <table width="100%">
                <tr>
                    <td id="back-btn-cell" style="padding-left:15px;width:10%">
                        <a class="pull-left btn btn-default back-btn" id="back-btn" href="/peachpits/peachtalk/peachtalk-home?event=<?php echo $currentEvent; ?>"><span class="glyphicon glyphicon-chevron-left"></span><span id="back-btn-text"> Back</span></a>
                    </td>
                    <td class="text-center">
                        <h4 class="channel-header-text">Parts Requests</h4>
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
                    if ($row['reply'] == 'Yes' || $row['reply'] == '') {
                        $messageid = $row['messageid'];
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
                            if ($row['reply'] == 'Yes') {
                                echo '<h4 class="message-info" id="message-info-'.$messageid.'"><b>'.$sentBy.'</b> - '.$loopTime.'<span class="reply-link-casing"> - <span class="reply-link" onclick="replyClick(this)" id="'.$messageid.'">Reply&nbsp;</span><span class="glyphicon glyphicon-share-alt"></span></span></h4>';
                                echo '<p class="height2"></p>';
                                echo '<h3 class="message" id="message-'.$messageid.'">'.$row['message'].'</h3>';
                                echo '<p class="height1"></p>';
                                echo '<div class="view-replies-btn" id="view-replies-btn-'.$messageid.'"><h5 class="view-replies" id="view-replies-'.$messageid.'" onclick="viewRepliesClick(this)">View Replies&nbsp;</h5><div class="glyphicon glyphicon-triangle-bottom" id="caret-'.$messageid.'" onclick="viewRepliesClick(this)"></div></div>';
                                echo '<p class="height3" id="p-'.$messageid.'"></p>';
                                echo '<div class="reply-group hidden" id="reply-group-'.$messageid.'">';
                                $sql2 = $mysqli->query("SELECT * FROM `".$eventMessages."` WHERE `channel`='$channel' AND `reply`='$messageid'");
                                while ($row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH)) {
                                    $messageid = $row2['messageid'];
                                    $sentByArr = explode(";", $row2['sent_by']);
                                    $sentByEmail = $sentByArr[0];
                                    $sentBy = $sentByArr[1];
                                    $sentByName = $sentByArr[2];
                                    $sentById = $sentByArr[3];
                                    if (!($sentBy == 'Pit Admin' || $sentBy == 'Event Admin' || $sentBy == 'Super Admin')) {
                                        $sentBy = $sentByName . " - Team " . $sentBy;
                                    }
                                    $loopDate = new DateTime($row2['time_sent']);
                                    $loopTime = $loopDate->format('g:i A');
                                    $loopDateDay = new DateTime($loopDate->format('Y-m-d'));
                                    $interval = date_diff($loopDateDay, $currentDateDay);
                                    $interval = $interval->format('%R%a');
                                    if ($row2['time_sent'] <= $lastVisit) {
                                        if (!($interval == '-0' || $interval == '+0')) {
                                            $loopTime = $loopDate->format('D, g:i A');
                                        }
                                        echo '<h4 class="message-info reply-message-info"><b>'.$sentBy.'</b> - '.$loopTime.'</h4>';
                                        echo '<p class="height2"></p>';
                                        echo '<h3 class="reply-message message">'.$row2['message'].'</h3><p class="height5"></p>';
                                    }
                                }
                                echo '</div>';
                            }
                            else {
                                echo '<h4 class="message-info" id="message-info-'.$messageid.'"><b>'.$sentBy.'</b> - '.$loopTime.'<span class="reply-link-casing"> - <span class="reply-link" onclick="replyClick(this)" id="'.$messageid.'">Reply&nbsp;</span><span class="glyphicon glyphicon-share-alt"></span></span></h4>';
                                echo '<p class="height2"></p>';
                                echo '<h3 class="message" id="message-'.$messageid.'">'.$row['message'].'</h3>';
                                echo '<p class="height1"></p>';
                                echo '<div class="view-replies-btn hidden" id="view-replies-btn-'.$messageid.'"><h5 class="view-replies" id="view-replies-'.$messageid.'" onclick="viewRepliesClick(this)">View Replies&nbsp;</h5><div class="glyphicon glyphicon-triangle-bottom" id="caret-'.$messageid.'" onclick="viewRepliesClick(this)"></div></div><p class="height3" id="p-'.$messageid.'"></p>';
                                echo '<div class="reply-group" id="reply-group-'.$messageid.'"></div>';
                            }
                        }
                    }
                }
            ?>
        </div>
    </div>
    <?php if (strpos($userRestrictions, "Parts") !== false) { ?>
    <div id="send-message-wrap">
        <div class="glyphicon glyphicon-comment" id="comment-glyphicon"></div>
        <textarea class="form-control input-lg no-radius autoExpand" id="chat-textbox" rows="1" placeholder="You have been restricted from sending messages here." disabled="disabled"></textarea>        
        <div class="glyphicon glyphicon-send hidden" id="send-message-btn"></div>
    </div>
    <?php } elseif ($peachtalkUsername != "none") { ?>
    <div id="send-message-wrap">
        <div class="glyphicon glyphicon-comment" id="comment-glyphicon"></div>
        <label class="label-no-bold lbl-reply hidden" id="chat-label">Reply:&nbsp;</label><textarea class="form-control input-lg no-radius autoExpand" id="chat-textbox" rows="1" placeholder="Send Message..."></textarea>
        <div class="glyphicon glyphicon-send hidden" id="send-message-btn"></div>
    </div>
    <?php } ?>
</div>
<script type="text/javascript" src="peachtalk/js/chat.js"></script>
<script>
    var replyingToMessageBool = false;
    var viewingRepliesBool = false;
    var oldTextValue = "Reply: ";
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
    function sortReplyMessages() {
        var newReplies = document.getElementsByClassName('new-reply');
        for (var i = 0; i < newReplies.length; i ++) {
            var replyId = newReplies[i].id;
            var originalId = replyId.split('-')[2];
            $('#'+replyId).removeClass('new-reply');
            $('#'+replyId).appendTo($('#reply-group-' + originalId));
            document.getElementById('p-' + originalId).style.lineHeight = 1;
            document.getElementById('view-replies-btn-' + originalId).classList.remove('hidden');
            document.getElementById('view-replies-' + originalId).innerHTML = 'Hide Replies&nbsp;';
            document.getElementById('caret-' + originalId).classList = 'glyphicon glyphicon-triangle-top';
            document.getElementById('reply-group-' + originalId).classList.remove('hidden');
        }
    }
    function replyClick(elem) {
        replyingToMessageBool = true;
        if (replyMessageId == elem.id) {
            elem.innerHTML = 'Reply&nbsp;';
            document.getElementById('message-info-' + replyMessageId).classList.remove("replying");
            document.getElementById('message-' + replyMessageId).classList.remove("replying");
            document.getElementById('chat-label').classList.add('hidden');
            document.getElementById('chat-textbox').style.textIndent = 26;
            document.getElementById('chat-textbox').placeholder = "Send Message...";
            replyMessageId = "";
        }
        else {
            if (replyMessageId != "") {
                document.getElementById(replyMessageId).innerHTML = 'Reply&nbsp;';
                document.getElementById('message-info-' + replyMessageId).classList.remove("replying");
                document.getElementById('message-' + replyMessageId).classList.remove("replying");
            }
            else {
                //document.getElementById('chat-textbox').value = "Reply: " + document.getElementById('chat-textbox').value;
                document.getElementById('chat-label').classList.remove('hidden');
                document.getElementById('chat-textbox').style.textIndent = 82;
                document.getElementById('chat-textbox').placeholder = "";
            }
            replyMessageId = elem.id;
            $('#chat-textbox').focus();
            elem.innerHTML = 'Replying&nbsp;';
            document.getElementById('message-info-' + replyMessageId).classList.add("replying");
            document.getElementById('message-' + replyMessageId).classList.add("replying");
        }
        
        console.log(replyMessageId);
    }
    function viewRepliesClick(elem) {
        viewingRepliesBool = true;
        var elemID = elem.id;
        if (elemID.indexOf('caret') == -1) {
            var messageid = elemID.split('-')[2];
        }
        else {
            var messageid = elemID.split('-')[1];                
        }
        if (document.getElementById('view-replies-' + messageid).innerHTML == 'View Replies&nbsp;') {
            document.getElementById('view-replies-' + messageid).innerHTML = 'Hide Replies&nbsp;';
            document.getElementById('caret-' + messageid).classList = 'glyphicon glyphicon-triangle-top';
            document.getElementById('reply-group-' + messageid).classList.remove('hidden');
        }
        else {
            document.getElementById('view-replies-' + messageid).innerHTML = 'View Replies&nbsp;';
            document.getElementById('caret-' + messageid).classList = 'glyphicon glyphicon-triangle-bottom';
            document.getElementById('reply-group-' + messageid).classList.add('hidden');
        }
    }
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            
            if (replyingToMessageBool == true || replyMessageId != "") {
                replyingToMessageBool = false;
            }
            else if (viewingRepliesBool == true) {
                viewingRepliesBool = false;
            }
            else {
                if (document.getElementById('chat-wrap').innerHTML.indexOf('<h2 class="text-center date-header">No Messages Yet!</h2>') != -1) {
                    document.getElementById('chat-wrap').innerHTML = document.getElementById('chat-wrap').innerHTML.replace('<h2 class="text-center date-header">No Messages Yet!</h2>', '');
                }
                sortReplyMessages();
                if (chatContainer.scrollHeight <= chatContainer.clientHeight) {
                    activateNewMessages();
                }
                else {
                    $('#new-message-alert').fadeIn();
                }
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
                document.getElementById('chat-label').style.marginTop = 0;
                document.getElementById('send-message-btn').style.marginTop = 5;
            }
            else {
                this.rows = rows;
                document.getElementById('new-message-alert').style.marginBottom = (rows * 24);
                document.getElementById('comment-glyphicon').style.marginTop = 24 - (rows * 24);
                document.getElementById('chat-label').style.marginTop = 24 - (rows * 24);
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