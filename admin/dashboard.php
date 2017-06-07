<?php 
  //Include the header file IMPORTANT
	include dirname(__DIR__) . "/header.php";
	
  //If a user is properly signed in display the page
	if(loggedOn()) {
    include "menu.php";
?>   
    <head>
      <script src="admin/js/dashboard.js"></script>
    </head>
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
              <form class="form-inline" action="admin/change_role.php?event=<?php echo $currentEvent ?>" method="post">
                <select class="form-control" name="roleChange" id="roleChange">
                  <option>Inspector</option>
                  <option>Lead Inspector</option>
                  <option>Admin</option>
                  <option>Event Admin</option>
                </select>
                <?php $sql = $mysqli->query("SELECT * FROM events"); ?>
                <p style="display:inline; margin-left:10px; margin-right:10px;">for</p>
                <select class="form-control" style="width:250px;" name="addevent" id="addevent">
                  <?php while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){ echo "<option>". $row['eventname'] ."</option>"; } ?>
                </select>
                <button type="submit" class="btn btn-default" name="submit" style="margin-left:20px;">Request</button>
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
          if(mysqli_num_rows($sql) != 0) 
          {
            while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
              if(in_array($row['event'],$eventsArr)){
                if($row['type']=='Event'){
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to join your event, <b>'. $row['event'] .'</b> as a </b>'. $row['existingrole'] .' <a href="admin/approve_request.php?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Approve</a> | <a href="admin/deny_request.php?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Deny</a></p>';
                  $check = true;
                }
                else if($row['type']=='Role'){
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to be promoted to </b>'. $row['requestedrole'] .' and join your event <b>'. $row['event'] .'</b> <a href="admin/approve_request.php?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Approve</a> | <a href="admin/deny_request.php?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Deny</a></p>';
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
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to join the event, <b>'. $row['event'] .'</b> as a </b>'. $row['existingrole'] .' <a href="admin/approve_request.php?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Approve</a> | <a href="admin/deny_request.php?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Deny</a></p>';
                }
                else if($row['type']=='Role'){
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to be promoted to </b>'. $row['requestedrole'] .' and join the event <b>'. $row['event'] .'</b> <a href="admin/approve_request.php?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Approve</a> | <a href="admin/deny_request.php?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Deny</a></p>';
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
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to join your event, <b>'. $row['event'] .'</b> as a </b>'. $row['existingrole'] .' <a href="admin/approve_request.php?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Approve</a> | <a href="admin/deny_request.php?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['existingrole'] .'">Deny</a></p>';
                  $check = true;
                }
                else if($row['type']=='Role'){
                  echo '<p><b>'. $row['firstname'] .' '. $row['lastname'] . '</b> has requested to be promoted to </b>'. $row['requestedrole'] .' and join your event <b>'. $row['event'] .'</b> <a href="admin/approve_request.php?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Approve</a> | <a href="admin/deny_request.php?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $row['event'] .'&role='. $row['requestedrole'] .'">Deny</a></p>';
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
        <form action="admin/change_password.php?event=<?php echo $currentEvent ?>" method="post">
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
	} else { echo '<script>document.location.href="signin.php"</script>'; }
	
	include dirname(__DIR__) . "/footer.php"; 
?>