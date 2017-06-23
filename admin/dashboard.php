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
          width: 15%;
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
                  <form class="form-inline" action="admin/change_role?event=<?php echo $currentEvent ?>" method="post">
                    <div class="request-role-field inner-addon left-addon">
                      <i class="glyphicon glyphicon-user"></i>
                      <select name="roleChange" style="" id="roleChange">
                        <option value="">Your Role</option>
                        <option>Inspector</option>
                        <option>Lead Inspector</option>
                        <option>Admin</option>
                        <option>Event Admin</option>
                      </select>
                    </div>
                    
                    <!--<p style="display:inline-block;">
                      for
                    </p>-->

                    <div class="request-event-field inner-addon left-addon">
                      <i class="glyphicon glyphicon-star"></i>       
                      <?php $sql = $mysqli->query("SELECT * FROM events"); ?> 
                      <select name="addevent" id="addevent">
                        <option value="">Desired Event<option>
                        <?php while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){ echo "<option>". $row['eventname'] ."</option>"; } ?>
                      </select>
                    </div>
                      <button style="vertical-align:top;" type="submit" class="btn btn-default" name="submit" id='btn-submit-request' >
                        Request
                      </button>
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
                              if(in_array($row['event'],$eventsArr)){
                                if($row['type']=='Event'){
                                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to join your event, <b>'. $row['event'] .'</b> as a </b>'. $row['existingrole'] .' <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Deny</a></p>';
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
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to join the event, <b>'. $row['event'] .'</b> as a </b>'. $row['existingrole'] .' <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Deny</a></p>';
                }
                else if($row['type']=='Role'){
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to be promoted to </b>'. $row['requestedrole'] .' and join the event <b>'. $row['event'] .'</b> <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Deny</a></p>';
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
            $new_arr = implode(" ", $eventsArr);
          $sql = $mysqli->query("SELECT * FROM `requests` WHERE (`requestedrole` LIKE 'Inspector' OR `existingrole` LIKE 'Inspector') AND `status` LIKE 'Pending'");
          if(mysqli_num_rows($sql) != 0){
            while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
              if(strpos($new_arr,$row['event']) == true){
                if($row['type']=='Event'){
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to join your event, <b>'. $row['event'] .'</b> as a </b>'. $row['existingrole'] .' <a href="admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Approve</a> | <a href="admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Deny</a></p>';
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
                <button class="btn btn-default clearall">Clear All Events</button>
                <button class="btn btn-default deleteall">Delete All Events</button>
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
        <form action="admin/change_password?event=<?php echo $currentEvent ?>" method="post">
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
	

<?php
  //If the user isn't signed in redirect them to the signin page
	} else { echo '<script>document.location.href="signin"</script>'; }
	
	include dirname(__DIR__) . "/footer.php"; 
?>