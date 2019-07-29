<title>PeachPits - Users</title>
<?php 
  /*********************
    Allows an event admin & lead inspector to view and delete users
    **********************/  
  include dirname(__DIR__) . "/header.php";

  $eventUsers = $currentEvent . "_users";
   
  if(loggedOn()) {
    include "menu.php";  
?>
<head>
  <script src="admin/js/manageTeams.js"></script>
  <script src="js/selectize.js"></script>
  <link rel="stylesheet" type="text/css" href="css/selectize.css" />
</head>
      <div class="dashboard-content">
        <div class="table-responsive">
          <table class="table table-hover" style="border:1px solid #ddd;">
            <thead>
              <td><b>First Name</b></td>
              <td><b>Last Name</b></td>
              <td><b>Role</b></td>
              <td><b>Delete User</b></td>
            </thead>
            <?php
              $eventTeams = $currentEvent . '_teams';
              $enabledX = `
                <td style="width:10%" class="text-center">
                  <a style="text-decoration:none" href="#" data-toggle="modal" data-target="#delete-user-modal" id="delete-user">
                    <span style="font-size:30px;color:red;line-height:18px;">&times;</span>
                  </a>
                </td>
              `;
              $disabledX = `
                <td style="width:10%" class="text-center">
                  <span style="font-size:30px;color:gray;line-height:18px;">&times;</span>
                </td>
              `;
              $sql = $mysqli->query("SELECT * FROM `".$eventUsers."` ORDER BY `lastname` ASC");
              while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                $rowRole = $row['role'];
                if (!isEventAdmin($rowRole))
                echo "<tr>";
                echo `<td style="display:none;" id="email">`. $row['email'] .`</td>`;
                echo `<td id="firstname">`. $row['firstname'] .`</td>`;
                echo `<td id="lastname">`. $row['lastname'] .`</td>`;
                echo `<td id="role">`. $row['role'] .`</td>`;
                if (isSuperAdmin($role)) {
                  echo $enabledX;
                } else if (isEventAdmin($role)) {
                  if (isEventAdmin($rowRole)) {
                    echo $disabledX;
                  } else {
                    echo $enabledX;
                  }
                } else if (isLeadInspector($role)) {
                  if (isInspector($rowRole)) {
                    echo $enabledX;
                  } else {
                    echo $disabledX;
                  }
                } else {
                  echo $disabledX;
                }
                echo "</tr>";
              }
            ?>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit team popup -->
<div class="modal fade" id="delete-user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" style="display:inline" id="myModalLabel">Delete User: <span id="getuser"></span></h4>
      </div>
      <div class="modal-body text-center">
        <h4 style="display:inline">This will remove the role "<span id="getrole"></span>" from the user. Are you sure you want to continue?</h4>
        <form action="/peachpits/admin/delete_user?event=<?php echo $currentEvent; ?>" method="post">
        <input type="hidden" name="email" id="email-input">
        <input type="hidden" name="role" id="role-input">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger" name="submit">Delete User</button></form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $(document).on("click", "#delete-user", function() {
      var row = $(this).closest("tr");
      var userEmail = row.find("#email").text();
      var firstname = row.find("#firstname").text();
      var lastname = row.find("#lastname").text();
      var userRole = row.find("#role").text();
      var user = firstname + " " + lastname;
      document.getElementById('getuser').innerHTML = user;
      document.getElementById('getrole').innerHTML = userRole;
      document.getElementById('role-input').value = userRole;
      document.getElementById('email-input').value = userEmail;
    });
  });
</script>

<?php 
  } else {
    echo '<script>document.location.href="signin"</script>';
  }

  include dirname(__DIR__) . "/footer.php"; 
?>