<title>PeachPits - Contact</title>
<?php include "header.php"; ?>

<script src="//code.jquery.com/jquery-1.12.4.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="js/selectize.js"></script>
<link rel="stylesheet" type="text/css" href="css/selectize.css" />
<link rel="stylesheet" type="text/css" href="css/contact.css" />


<?php
$prevURL = $_SERVER['HTTP_REFERER'];
?>

<div class="page-head">
  <div class="container">
    <h1>Contact Us</h1>
  </div>
</div>
<div id="startchange" class="container">
	<div class="page-header" style="margin-top: 0px">
		<h1 class="text-center"><small>Can't find your event? Need help or have a question?<br><br>Fill out the form below and we'll get back in touch with you as soon as possible.</small></h1>
  </div>
  <br>
  <form name="htmlform" method="post" action="contact_send">
    <div id="form-name" class="form-group">
      <div style="padding:5px;" class="col-sm-offset-1 col-sm-10 inner-addon left-addon">
        <i class="glyphicon glyphicon-user"></i>
        <input type="text" class="form-control input-lg no-radius" name="name" id="name" placeholder="Name" value=<?php if (loggedOn()){ echo $firstname, '&nbsp;', $lastname;}?> >
      </div>
    </div>
    <div class="form-group">
      <div style="padding:5px;" class="col-sm-offset-1 col-sm-10 inner-addon left-addon">
        <i class="glyphicon glyphicon-envelope"></i>
        <input type="email" class="form-control input-lg no-radius" name="email" id="email" placeholder="Email" value=<?php if (loggedOn()){ echo $sessionEmail;}?>></input>
      </div>
    </div>
    <div id="form-topic" class="form-group">
      <div style="padding:5px" class="col-sm-offset-1 col-sm-10 inner-addon left-addon" id="topicselect">
        <i class="glyphicon glyphicon-list-alt"></i>
        <select style="padding-left: 38px;" name="topic" id="topic" onchange="updateForm()">
          <option selected hidden value="">How can we help you?</option>
          <option id="event" value="event">Add your Event</option>
          <option id="bug" value="bug">Report a bug</option>
          <option id="other" value="other">Other</option>
        </select>
      </div>
    </div>
    <div id="event-eventname" class="form-group event">
      <div style="padding:5px;" class="col-sm-offset-1 col-sm-10 inner-addon left-addon">
        <i class="glyphicon glyphicon-star"></i>
        <select style="padding-left: 38px" name="events" id="events" onChange="clearMissing($('#events-selectized').parent())">
          <option selected hidden value="">Select an Event</option>
          <?php
            $sql = $mysqli->query("SELECT * FROM `events`");   
            $row = mysqli_fetch_assoc($sql);
            while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
              echo '<option><td><font color= #000000 >' . $row['eventname'] . '</font><td></option>';
            }
          ?>
        </select>
      </div>
    </div>
    <div id="event-eventadmin" class="form-group event">
      <div style="padding:5px; vertical-align: middle;" class="who col-sm-offset-1 col-sm-10">
        <button id="event-btn-yes" style="float:left vertical-align: middle" type="button" class="btn btn-default contact-btn" value="event-eventadmin-yes">Yes</button>
        <button id="event-btn-no"  style="float:left" type="button" class="btn btn-default" value="event-eventadmin-no">No</button>
         &nbsp;&nbsp;Will you be the event admin?
      </div>
    </div>
    <div id="event-eventadmin-who" class="form-group event-who">
      <div style="padding:5px" class="special col-sm-offset-1 col-sm-10 inner-addon left-addon">
        <i class="glyphicon glyphicon-king"></i>
        <input style="float:left;" type="text" class="form-control input-lg no-radius" name="eventadmin" id="eventadmin" placeholder="Who will?"></input>
      </div>
    </div>
	  <div class="form-group event">
      <div style="padding:5px;" class="col-sm-offset-1 col-sm-10 inner-addon left-addon">
        <div class="glyphicon glyphicon-comment" id="eventmessage-glyphicon"></div>
        <textarea style="resize:none; text-indent: 26px;" class="form-control input-lg no-radius" name="eventmessage" id="eventmessage" rows="7" placeholder="Anything else?"></textarea>
      </div>
    </div>
    <div class="form-group bug">
      <div style="padding:5px;" class="col-sm-offset-1 col-sm-10 inner-addon left-addon">
        <i class="glyphicon glyphicon-search"></i>
        <select name="bugtopic" id="bugtopic">
          <option selected hidden value="">Where?</option>
          <option id="event" value="pitmap">Pitmap</option>
          <option id="bug" value="teamlist">Team List</option>
          <option id="match-schedule" value="matchschedule">Match Schedule</option>
          <option id="admin-dashboard" value="admin">Admin/Inspector Dashboard</option>
          <option id="other" value="other">Other</option>
        </select>
      </div>
    </div>
    <div class="form-group bug">
      <div style="padding:5px;" class="col-sm-offset-1 col-sm-10 inner-addon left-addon">
        <i class="glyphicon glyphicon-phone"></i>
        <select name="bugdevice" id="bugdevice">
          <option selected hidden value="">What device are you on?</option>
          <option id="event" value="mobile">Mobile</option>
          <option id="bug" value="tablet">Tablet</option>
          <option id="other" value="desktop">Desktop</option>
          <option id="other" value="other">Other</option>
        </select>
      </div>
    </div>
    <div class="form-group event bug">
      <div style="padding:5px;" class="col-sm-offset-1 col-sm-10 inner-addon left-addon">
        <div class="glyphicon glyphicon-comment" id="bugdesc-glyphicon"></div>
        <textarea style="resize:none; text-indent: 26px;" class="form-control input-lg no-radius" name="bugdesc" id="bugdesc" rows="7" placeholder="Description of the Bug"></textarea>
      </div>
    </div>
    <div class="form-group other">
      <div style="padding:5px;" class="col-sm-offset-1 col-sm-10 inner-addon left-addon">
        <div class="glyphicon glyphicon-comment" id="othermsg-glyphicon"></div>
        <textarea style="resize:none; text-indent: 26px;" class="form-control input-lg no-radius" name="othermsg" id="othermsg" rows="7" placeholder="Message"></textarea>
      </div>
    </div>
    <div style="padding:5px;" class="form-group">
      <div style="padding:5px;" class="col-sm-offset-1 col-sm-10">
        <button id="submit" style="padding:5px; " type="submit" class="btn btn-default btn-contact-submit">Submit</button>
      </div>
    </div>
</form>
<script>
  $('#events').selectize({
    highlight: false,
    hideselected: true,
    preload: false,
    placeholder: "Select/Add an event",
    create: function(input) {
        return {
            value: input,
            text: input
        }
    }
  });
  var $topic_select = $('#topic').selectize({
    highlight: false,
    hideselected: true,
    preload: 'event',
    placeholder: "How can we help you?",
  });
  var control_topic = $topic_select[0].selectize;
  var $bug_select = $('#bugtopic').selectize({
    highlight: false,
    hideselected: true,
    preload: false,
    placeholder: "Where?",
  });
  var control_bug = $bug_select[0].selectize;
  $('#bugdevice').selectize({
    highlight: false,
    hideselected: true,
    preload: false,
    placeholder: "What device are you on?",
  });
  
  function clearMissing(element){
    element.parent().removeClass('missing');
  }

  function updateForm(){
    $('textarea#othermsg').on('scroll', function(){
      if(this.scrollTop != 0){
        $('#name').val('test');
        $('#othermsg-glyphicon').css('display', 'none');
      } else {
        $('#othermsg-glyphicon').css('display', 'initial');
      }
    });
    $('textarea#bugdesc').on('scroll', function(){
      if(this.scrollTop != 0){
        $('#name').val('test');
        $('#bugdesc-glyphicon').css('display', 'none');
      } else {
        $('#bugdesc-glyphicon').css('display', 'initial');
      }
    });
    $('textarea#eventmessage').on('scroll', function(){
      if(this.scrollTop != 0){
        $('#name').val('test');
        $('#eventmessage-glyphicon').css('display', 'none');
      } else {
        $('#eventmessage-glyphicon').css('display', 'initial');
      }
    });
    $('.selectize-control').removeClass('missing');
    var name = $('#name').val();
    if($('.item').attr('data-value') == 'event'){
      $('#events').attr('name', 'events');
      $('#eventadmin').attr('name', 'eventadmin');
      $('#eventmessage').attr('name', 'eventmessage');
      $('.event').css('display', 'block');
    } else {
      $('.event-who').css('display', 'none');
      $('#event-btn-yes').addClass('contact-btn');
      $('#event-btn-no').removeClass('contact-btn');
      $('#events').removeAttr('name');
      $('#eventadmin').removeAttr('name');
      $('#eventmessage').removeAttr('name');
      $('.event').css('display', 'none');
    }
    if($('.item').attr('data-value') == 'bug'){
      $('#bugtopic').attr('name', 'bugtopic');
      $('#bugdevice').attr('name', 'bugdevice');
      $('#bugdesc').attr('name', 'bugdesc');
      $('.bug').css('display', 'block');
    } else {
      $('#bugtopic').removeAttr('name');
      $('#bugdevice').removeAttr('name');
      $('#bugdesc').removeAttr('name');
      $('.bug').css('display', 'none');
    }
    if($('.item').attr('data-value') == 'other'){
      $('#othermsg').attr('name', 'othermsg');
      $('.other').css('display', 'block');
    } else {
      $('#othermsg').removeAttr('name');
      $('.other').css('display', 'none');
    }
  }
  updateForm();
  $('#name').blur(function(){
    if($('#name').val() != ''){
      $('#name').removeClass('missing');
      if($('#event-btn-yes').hasClass('contact-btn') && $('.item').attr('data-value') == 'event'){
        $('#eventadmin').removeClass('missing');
      }
    }
    
  });
  $('#email').blur(function(){
    if($('#email').val() != ''){
      $('#email').removeClass('missing');
    }
  });
  $('#bugdesc').blur(function(){
    if($('#bugdesc').val() != ''){
      $('#bugdesc').removeClass('missing');
    }
  });
  $('#othermsg').blur(function(){
    if($('#othermsg').val() != ''){
      $('#othermsg').removeClass('missing');
    }
  });
  
  $('#event-btn-no').on('click', function(e){
    $('#event-btn-no').addClass('contact-btn');
    $('#event-btn-yes').removeClass('contact-btn');
    $('.event-who').css('display', 'block');
    $('#eventadmin').val("");
  });
  $('#event-btn-yes').on('click', function(e){
    $('#event-btn-yes').addClass('contact-btn');
    $('#event-btn-no').removeClass('contact-btn');
    $('.event-who').css('display', 'none');
  });
  $('#submit').on('click', function(e){
    if($('#event-btn-yes').hasClass('contact-btn') && $('.item').attr('data-value') == 'event'){
      var name = $('#name').val();
      $('#eventadmin').val(name);
    }
    if($('#name').val() == ''){
      $('#name').addClass('missing');
      e.preventDefault();
    }
    if($('#email').val() == ''){
      $('#email').addClass('missing');
      e.preventDefault();
    }
    if($('#events-selectized').parent().hasClass('full')){
      $('#events-selectized').parent().parent().removeClass('missing');
    }
    if($('.item').attr('data-value') == 'event'){
      if($('#eventadmin').val() == ''){
        $('#eventadmin').addClass('missing');
        e.preventDefault();
      }
      if($('#events-selectized').parent().hasClass('not-full')){
        $('#events-selectized').parent().parent().addClass('missing');
        e.preventDefault();
      }
    } else if($('.item').attr('data-value') == 'bug'){
      if($('#bugdesc').val() == ''){
        $('#bugdesc').addClass('missing');
        e.preventDefault();
      }
    } else if($('.item').attr('data-value') == 'other'){
      if($('#othermsg').val() == ''){
        $('#othermsg').addClass('missing');
        e.preventDefault();
      }
    } else {
      $('.selectize-control').addClass('missing');
      e.preventDefault();
    }
    if($('.missing').length == 0){
      if(confirm("Submit")){
        if($('#event-btn-yes').hasClass('year-showing') && $('#topic').val() == 'event'){
          var name = $('#name').val();
          $('#eventadmin').val(name);
        }
      } else {
        e.preventDefault();
      
      }
    }
  });
  $('#submit').click(function() {
    $('.missing').parent().effect( "shake", {distance: 10}, 750 );
  });
</script>
</div>


<?php
  $topic = $_GET["topic"];
  $bug = $_GET["bug"];
  if($topic == "bug"){
    echo'<script>control_topic.setValue("bug");</script>';
    if($bug == "404"){
      echo'<script>$("#bugdesc").val("404 Error: ' . $prevURL . ' ")</script>';
    }
  }
  if($topic == "event"){
    echo'<script>control_topic.setValue("event");</script>';
    echo'<script>$("#events-selectized").parent().parent().addClass("missing");</script>';
  }
  if($topic == "other"){
    echo'<script>control_topic.setValue("other");</script>';
  }
?>



<?php include "footer.php"; ?>