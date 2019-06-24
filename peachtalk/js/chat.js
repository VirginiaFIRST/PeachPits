var eventid;
var message;
var channel;
var user;
var groupid;
var reply;

function Chat (eventidInput, channelInput, userInput, groupidInput) {
	eventid = eventidInput;
    channel = channelInput;
    user = userInput || 'none';
    groupid = groupidInput || 'none';
    this.initialize = initializeChat;
    this.update = updateChat;
    this.send = sendChat;
}

$.ajaxSetup({
    cache: false // for ie
});

//Calls updateChat periodically
function initializeChat() {
    updateChat();
    updateTimer = setTimeout('initializeChat()', 5000);
}

//Updates the chat
function updateChat(){
    console.log("Update");
     $.ajax({
        type: "POST",
        url: "peachtalk/update_chat.php",
        data: {
            'eventid': eventid,
            'channel' : channel,
            'user': user,
            'groupid': groupid
            },
        dataType: "json",
        success: function(data) {
            if (data != 'Nothing to report...') {
                if (document.getElementById('chat-container').scrollTop >= (document.getElementById('chat-container').scrollHeight - document.getElementById('chat-container').offsetHeight)) {
                    $('#chat-wrap').append($(data));
                    chatContainer.scrollTop = 99999;
                }
                else {
                    $('#chat-wrap').append($(data));
                }
                console.log(data);
            }
            else {
                console.log('Nothing to report...');
            }
        },
    });
}

//send the message
function sendChat(messageInput, replyInput) {
    reply = replyInput || 'none';
    message = messageInput;
     $.ajax({
		   type: "POST",
		   url: "peachtalk/send_message.php",
		   data: {  
					'eventid': eventid,
                    'message': message,
                    'user': user,
					'channel': channel,
                    'groupid': groupid,
                    'reply': reply
					},
		   dataType: "json",
		   success: function(data){
                clearTimeout(updateTimer);
                initializeChat();
           },
		});
}

function newCleanInput($data) {
	return htmlspecialchars($data, ENT_QUOTES, "UTF-8");
}

$(document).ready(function() {
    $('#chat-textbox').keydown(function (e) {
        if (e.which == 13 && !e.shiftKey) {
            e.preventDefault();
            console.log('Submit with enter');
            processMessage();
        }
        else if (e.which == 13 && e.shiftKey) {
            e.preventDefault();
        }
    });
    $('#send-message-btn').on('click', function () {
        console.log('Submit with button');
        processMessage();
    });
});
function processMessage() {
    var message = document.getElementById('chat-textbox').value;
    if (message == '') {
        console.log('The message box was empty.')
    }
    else if (message == '\n' || message == '\n\n' || message == '\n\n\n') {
    console.log('The message only had a newline character(s).');
    }
    else if (message.length >= 1000) {
        console.log('The message is too long to be saved.')
    }
    else {
        console.log(message.length);
        console.log(message);
        if (channel == 'Parts') {
            if (replyMessageId != "") {
                console.log("This is a reply!");
                chat.send(message, replyMessageId);
                document.getElementById(replyMessageId).innerHTML = 'Reply&nbsp;';
                document.getElementById('message-info-' + replyMessageId).classList.remove("replying");
                document.getElementById('message-' + replyMessageId).classList.remove("replying");
                document.getElementById('chat-label').classList.add('hidden');
                document.getElementById('chat-textbox').style.textIndent = 26;
                document.getElementById('chat-textbox').placeholder = "Send Message...";
                replyMessageId = "";
            }
            else {
                chat.send(message);
            }
        }
        else {
            chat.send(message);
        }
        document.getElementById('chat-textbox').value = '';
        document.getElementById('new-message-alert').style.marginBottom = 0;
        document.getElementById('comment-glyphicon').style.marginTop = 0;
        document.getElementById('send-message-btn').style.marginTop = 5;
        if (document.getElementById('send-message-btn').className == 'glyphicon glyphicon-send') {
            document.getElementById('send-message-btn').className = 'glyphicon glyphicon-send hidden';
        }
        document.getElementById('chat-textbox').rows = 1;
    }
}