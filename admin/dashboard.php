<title>PeachPits - Admin</title>
<?php 
  //Include the header file IMPORTANT
	include dirname(__DIR__) . "/header.php";
	
  //If a user is properly signed in display the page
	if(loggedOn()) {
    include "menu.php";
?>   
    <head>
      <script src="admin/js/dashboard.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		  <script src="js/selectize.js"></script>
      <link rel="stylesheet" type="text/css" href="css/selectize.css" />
      <style>
        .request-role-field{
          display: inline-block;
          width: 200px;
        }
        .request-role-field-large{
          display: inline-block;
          width: 400px;
        }
        .request-event-field{
          display: inline-block;
          width: 35%;
        }
        @media screen and (max-width: 768px) {
          .request-role-field{
            display: block;
            width: calc(100% - 15px);
            min-width: 200px;
          }
          .request-role-field-large{
            display: block;
            width: calc(100% - 15px);
            min-width: 200px;
          }
          .request-event-field{
            display: block;
            width: calc(100% - 15px);
            min-width: 200px;
          }
          
          #btn-submit-request{
            border-radius: 0px;
            width: calc(100% - 15px);
            min-width: 200px;
          }
        }
      </style>
    </head>
    <body>
		  <div class="col-md-10 container-dashboard-content">
        <div class="dashboard-content">
          <div class="row row-account">
            <div class="col-md-2"><h3 class="dashboard-usertitle-name" style="margin:0px 0px 10px 0px;">Account Info</h3></div>
              <div class="col-md-10">
                <p class="dashboard-usertitle-name">Your Email Address</p>
                <p style="margin-left:10px;"><?php echo $sessionEmail; ?></p>
                <p class="dashboard-usertitle-name">Password</p>
                <p style="margin-left:10px;"><a href="#" data-toggle="modal" data-target="#newPasswordBox">Change Your Password</a></p>
              </div>
            </div>       
            <div class="row row-account">
              <div class="col-md-2"><h3 class="dashboard-usertitle-name" style="margin:0px 0px 10px 0px;">Roles</h3></div>
                <div class="col-md-10">
                  <p class="dashboard-usertitle-name">Your Current Role</p>
                  <p style="margin-left:10px;"><?php echo $role; ?></p>
                  <?php if(!isSuperAdmin($role)){ ?>
                  <p class="dashboard-usertitle-name">Request a Role</p>
                  <p style="margin-left:10px;">
                  <form class="form-inline" id="change-role-form" action="/peachpits/admin/change_role?event=<?php echo $currentEvent ?>" method="post">
                    <div class="request-role-field inner-addon left-addon">
                      <i class="glyphicon glyphicon-user"></i>
                      <select name="roleChange" id="roleChange" onchange="liaisonFunction()">
                        <option value="">Your Role</option>
                        <option value="Communication Liaison">Communication Liaison</option>
                        <option>Inspector</option>
                        <option>Lead Inspector</option>
                        <option>Pit Admin</option>
                        <option>Event Admin</option>
                      </select>
                    </div>
                    
                    <!--<p style="display:inline-block;">
                      for
                    </p>-->

                    <div class="request-event-field inner-addon left-addon" id="normal-request-form">
                      <i class="glyphicon glyphicon-star"></i>       
                      <?php $sql = $mysqli->query("SELECT * FROM events ORDER BY eventname ASC"); ?> 
                      <select onchange="formChange(this)" name="addevent" id="addevent">
                        <option value="">Desired Event<option>
                        <?php 
                        while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){ 
                          $eventYear = (int) filter_var($row['eventid'], FILTER_SANITIZE_NUMBER_INT);
                          if ($eventYear == date(Y)) {
                            echo "<option value='" . $row['eventname'] . "'>". $row['eventname'] ."</option>"; 
                          }
                        } 
                        ?>
                      </select>
                    </div>
                    <button style="vertical-align:top;" type="button" class="btn btn-default" onclick="document.getElementById('change-role-form').submit()" id='btn-submit-request' >
                      Request
                    </button>
                    <br>
                    <div class="hidden" id="liaison-form">
                      <div class="form-group resize-form-group" style="width:100%">
                        <div class="inner-addon left-addon">
                          <i class="glyphicon glyphicon-asterisk"></i>
                          <input class="form-control input-lg no-radius" onchange="formChange(this)" name="teamid" id="teamid" style="width:100%" placeholder="Team Number"></input>
                        </div>
                      </div>
                      <div class="form-group resize-form-group" style="width:100%">
                        <div class="inner-addon left-addon">
                          <i class="glyphicon glyphicon-user"></i>
                          <input class="form-control input-lg no-radius" onchange="formChange(this)" name="liaison-name" id="liaison-name" style="width:100%" placeholder="Your Name" value="<?php echo $firstname . ' ' . $lastname;?>"></input>
                        </div>
                      </div>
                      <div class="form-group resize-form-group" style="width:100%">
                        <div class="inner-addon left-addon">
                          <i class="glyphicon glyphicon-earphone"></i>
                          <input class="form-control input-lg no-radius" onchange="formChange(this)" name="liaison-cell" id="liaison-cell" style="width:100%" placeholder="Your Phone Number"></input>
                        </div>
                      </div>
                      <button type="button" style="vertical-align:top;" class="btn btn-default" id="liaison-btn-submit-request" data-toggle='modal' data-target='#read-terms-of-use' disabled="disabled">
                        Request
                      </button>
                    </div>
                  </form>
                  </p>
                  <p class="dashboard-usertitle-name">Your Pending Requests</p>
                  <p style="margin-left:10px;">
                    <?php
                      $sql = $mysqli->query("SELECT * FROM `requests` WHERE `email` LIKE '$sessionEmail' AND `status` LIKE 'Pending'");
                      if(mysqli_num_rows($sql) != 0){
                        while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
                          if($row['type']=='Event'){
                            echo "<p>You've requested to join " .$row['event'].".";
                          }
                          else if($row['type']=='Role'){
                            echo "<p>You've requested to join " .$row['event']." as a ".$row['requestedrole'].".";
                          }
                        }	
                      }
                      else if(mysqli_num_rows($sql) == 0){
                        echo "You don't have any pending requests.";
                      }
                    ?>
                  </p>
                  <?php } ?>
                </div>
              </div>         
              <?php 
                //Checks to see if there are any pending requests and displays them (only visible for event admins, super admins, and lead inspectors)
                if(isEventAdmin($role)){ 
                  $check = false;
              ?>
                  <div class="row row-account">
                    <div class="col-md-2"><h3 class="dashboard-usertitle-name" style="margin:0px 0px 10px 0px;">Requests Needing Approval</h3></div>
                      <div class="col-md-10">
                        <p style="margin-left:10px;">
                        <?php
                          $sql = $mysqli->query("SELECT * FROM requests WHERE `requestedrole` NOT LIKE 'Event Admin' AND `status` LIKE 'Pending'");
                          if(mysqli_num_rows($sql) != 0){
                            while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
                              if(in_array($row['event'],$userEventsArr)){
                                if($row['type']=='Event'){
                                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to join your event, <b>'. $row['event'] .'</b>, as a </b>'. $row['existingrole'] .' <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Deny</a></p>';
                                  $check = true;
                                }
                                else if($row['type']=='Role'){
                                  if ($row['requestedrole'] == "Communication Liaison") {
                                    $liaisonInfo = $row['liaison_info'];
                                    $liaisonInfoArr = explode(";", $liaisonInfo);
                                    $teamid = $liaisonInfoArr[0];
                                    $liaisonCell = $liaisonInfoArr[2];
                                    $mentorName = $liaisonInfoArr[3];
                                    $mentorCell = $liaisonInfoArr[4];
                                    echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> (Cell: '.$liaisonCell.') has requested to be a Communication Liaison for <b>Team '.$teamid.' </b>(Lead Mentor: '.$mentorName.' @ '.$mentorCell.') and join your event <b>'. $row['event'] .'</b> <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'&info='. $liaisonInfo .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Deny</a></p>';
                                  }
                                  else {
                                    echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to be promoted to </b>'. $row['requestedrole'] .' and join your event <b>'. $row['event'] .'</b> <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Deny</a></p>';
                                  }
                                  $check = true;
                                }
                              }
                            }	
                            if($check){
                              echo '</p></div></div>';
                            }
                          }
                          if(!$check || mysqli_num_rows($sql) == 0){
                            echo "No pending requests found.";
                            echo '</p></div></div>';
                          }
                  //</div> 
                //<div>
                }
        if(isSuperAdmin($role)){
        ?>
        <div class="row row-account">
          <div class="col-md-2"><h3 class="dashboard-usertitle-name" style="margin:0px 0px 10px 0px;">Requests Needing Approval</h3></div>
          <div class="col-md-10">
            <p style="margin-left:10px;">
        <?php
          $sql = $mysqli->query("SELECT * FROM `requests` WHERE `status` LIKE 'Pending'");
          if(mysqli_num_rows($sql) != 0){
            while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
                if($row['type']=='Event'){
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to join the event, <b>'. $row['event'] .'</b>, as a </b>'. $row['existingrole'] .' <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Deny</a></p>';
                }
                else if($row['type']=='Role'){
                  if ($row['requestedrole'] == "Communication Liaison") {
                    $liaisonInfo = $row['liaison_info'];
                    $liaisonInfoArr = explode(";", $liaisonInfo);
                    $teamid = $liaisonInfoArr[0];
                    $liaisonCell = $liaisonInfoArr[2];
                    $mentorName = $liaisonInfoArr[3];
                    $mentorCell = $liaisonInfoArr[4];
                    echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> (Cell: '.$liaisonCell.') has requested to be a Communication Liaison for <b>Team '.$teamid.' </b>(Lead Mentor: '.$mentorName.' @ '.$mentorCell.') and join the event <b>'. $row['event'] .'</b> <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'&info='. $liaisonInfo .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Deny</a></p>';
                  }
                  else {
                    echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to be promoted to </b>'. $row['requestedrole'] .' and join the event <b>'. $row['event'] .'</b> <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Deny</a></p>';
                  }
                }
            }	
            echo '</p></div></div>';
          }
          else if(mysqli_num_rows($sql) == 0){
            echo "No pending requests found.";
            echo '</p></div></div>';
          }
        }
        if(isLeadInspector($role)){
          $check = false;
        ?>
        <div class="row row-account">
          <div class="col-md-2"><h3 class="dashboard-usertitle-name" style="margin:0px 0px 10px 0px;">Requests Needing Approval</h3></div>
          <div class="col-md-10">
            <p style="margin-left:10px;">
        <?php
          $sql = $mysqli->query("SELECT * FROM `requests` WHERE (`requestedrole` LIKE 'Inspector' OR `existingrole` LIKE 'Inspector') AND `status` LIKE 'Pending'");
          if(mysqli_num_rows($sql) != 0){
            while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
              if(in_array($row['event'],$userEventsArr)){
                if($row['type']=='Event'){
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to join your event, <b>'. $row['event'] .'</b>, as a </b>'. $row['existingrole'] .' <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Deny</a></p>';
                  $check = true;
                }
                else if($row['type']=='Role'){
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to be promoted to </b>'. $row['requestedrole'] .' and join your event <b>'. $row['event'] .'</b> <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Deny</a></p>';
                  $check = true;
                }
              }
            }	
            if($check){
              echo '</p></div></div>';
            }
            else{
              echo "No pending requests found.";
              echo '</p></div></div>';
            }
          }
          else if(mysqli_num_rows($sql) == 0){
            echo "No pending requests found.";
            echo '</p></div></div>';
          }
        }
        if(isPitAdmin($role)){
          $check = false;
        ?>
        <div class="row row-account">
          <div class="col-md-2"><h3 class="dashboard-usertitle-name" style="margin:0px 0px 10px 0px;">Requests Needing Approval</h3></div>
          <div class="col-md-10">
            <p style="margin-left:10px;">
        <?php
          $sql = $mysqli->query("SELECT * FROM `requests` WHERE (`requestedrole` LIKE 'Communication Liaison' OR `existingrole` LIKE 'Communication Liaison') AND `status` LIKE 'Pending'");
          if(mysqli_num_rows($sql) != 0){
            while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
              if(in_array($row['event'],$userEventsArr)){
                if($row['type']=='Role'){
                  $liaisonInfo = $row['liaison_info'];
                  $liaisonInfoArr = explode(";", $liaisonInfo);
                  $teamid = $liaisonInfoArr[0];
                  $liaisonCell = $liaisonInfoArr[2];
                  $mentorName = $liaisonInfoArr[3];
                  $mentorCell = $liaisonInfoArr[4];
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> (Cell: '.$liaisonCell.') has requested to be a Communication Liaison for <b>Team '.$teamid.' </b>(Lead Mentor: '.$mentorName.' @ '.$mentorCell.') and join the event <b>'. $row['event'] .'</b> <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'&info='. $liaisonInfo .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Deny</a></p>';
                  $check = true;
                }
              }
            }	
            if($check){
              echo '</p></div></div>';
            }
            else{
              echo "No pending requests found.";
              echo '</p></div></div>';
            }
          }
          else if(mysqli_num_rows($sql) == 0){
            echo "No pending requests found.";
            echo '</p></div></div>';
          }
        }
        ?>
        
        <div class="row row-account">
          <div class="col-md-2"><h3 class="dashboard-usertitle-name" style="margin:0px 0px 10px 0px;">Events</h3></div>
          <div class="col-md-10">
            <p style="margin-left:10px;">
            <?php if(!isSuperAdmin($role)){
                for($j=0; $j < count($eventsArr); $j++){
                  echo "<b>Event ".($j+1).": </b>". $eventsArr[$j] ."<br/>";
                }
              } if(isSuperAdmin($role)){ ?>
                <button class="btn btn-danger clearall">Clear All Events</button>
                <button class="btn btn-danger deleteall">Delete All Events</button>
              <?php } ?>
            </p>
          </div>
        </div>
      </div>
    <script>
		  $('#addevent').selectize({
         highlight: false,
         hideselected: true,
         preload: false,
         placeholder: "Desired Event"
      });
      $('#roleChange').selectize({
         highlight: false,
         hideselected: true,
         preload: false,
         placeholder: "Your Role"
      });
      $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
      });
      $('#change-role-form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
          e.preventDefault();
          return false;
        }
      });
      function liaisonFunction() {
        if (document.getElementById('roleChange').value == 'Communication Liaison') {
          document.getElementById('liaison-form').classList.remove('hidden');
          document.getElementById('btn-submit-request').classList.add('hidden');
        }
        else {
          document.getElementById('liaison-form').classList.add('hidden');
          document.getElementById('btn-submit-request').classList.remove('hidden');
        }
      }
      function formChange(elem) {
        if (document.getElementById('roleChange').value == 'Communication Liaison') {
          var empty = false;
          if (document.getElementById('liaison-name').value == "" || document.getElementById('liaison-cell').value == "" || document.getElementById('teamid').value == "" || document.getElementById('addevent').value == "") {
            empty = true;
          }
          if (empty == true) {
            $('#liaison-btn-submit-request').attr('disabled', 'disabled');
          }
          else {
            $('#liaison-btn-submit-request').removeAttr('disabled');
          }
        }
      }
      function formChangeTermsOfUse(elem) {
          if (elem.checked == true) {
              $('#submit-agree-form').removeAttr('disabled');
          }
          else {
              $('#submit-agree-form').attr('disabled', 'disabled');            
          }
      }
    </script>
		</body>	
    </div>
	</div>
	
	
<!-- Popup box for changing a password -->
<div class="modal fade" id="newPasswordBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Change Your Password</h4>
      </div>
      <div class="modal-body">
        <form action="/peachpits/admin/change_password?event=<?php echo $currentEvent ?>" method="post">
          <input type="text" name="oldpassword" id="oldpassword" class="form-control" placeholder="Old Password"><br/>
    			<input type="text" name="newpassword" id="newpassword" class="form-control" placeholder="New Password"><br/>
    			<input type="text" name="confirmnewpassword" id="confirmnewpassword" class="form-control" placeholder="Confirm New Password"><br/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-default" name="submit">Change Password</button></form>
      </div>
    </div>
  </div>
</div>

<!-- PeachTalk Terms of Use Modal -->
<div class="modal fade" id="read-terms-of-use" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 style="display:inline" class="modal-title" id="myModalLabel">Terms of Use</h3>
      </div>
      <div class="modal-body">
			  <h4 class="text-center">Before using PeachTalk, you must read and accept the Terms of Use.</h4>
            <br><br>
            <ol>
                <li>GAFIRST owns all messaging.</li>
                <li>GAFIRST will maintain an archive of all messages.</li>
                <li>Users will be deleted if deemed inappropriate.</li>
                <li>Users will be restricted from sending messages on specified messaging channels if deemed inappropriate.</li>
                <li>Messages will be deleted if deemed inappropriate.</li>
                <li>Users must practice Gracious Professionalism at all times.</li>
                <li>GAFIRST will NOT use users' contact information for anything other than GAFIRST business.</li>
            </ol>
      </div>
      <div class="modal-footer">
            <label class="label-no-bold pull-left"><input class="form-check-input pull-left" type="checkbox" onchange="formChangeTermsOfUse(this)"><span class="pull-left">&nbsp;I agree to the Terms of Use</span></input></label>
            <button class="btn btn-default" id="submit-agree-form" onclick="document.getElementById('change-role-form').submit()" disabled="disabled">Continue</button>
      </div>
    </div>
  </div>
</div>

<?php
  //If the user isn't signed in redirect them to the signin page
	} else { echo '<script>document.location.href="/peachpits/signin"</script>'; }
	
	include dirname(__DIR__) . "/footer.php"; 
?>