<script src="admin/js/dashboard.js"></script>

<div class="container-fluid" style="padding-left:0px; padding-right:0px; min-height:100%; position:relative;height: auto !important; height: 100%;">
  <!-- Dashboard Sidebar -->
  <div class="col-md-2 profile-sidebar">
    <div class="hidden-xs">
      <div class="dashboard-usertitle">
        <div class="dashboard-usertitle-name"><?php echo $firstname; ?> <?php echo $lastname; ?></div>
        <div class="dashboard-usertitle-job"><?php echo $role; ?></div>
        <div class="dropdown">
          <button class="btn btn-default dropdown-toggle" style="max-width:90%; white-space:normal;" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <?php
            $sql = $mysqli->query("SELECT `eventname` FROM `events` WHERE `eventid` LIKE '$currentEvent'");
            $row = mysqli_fetch_assoc($sql);
            if ($row['eventname'].length > 0) {
              echo $row['eventname'];
            } else {
              echo "Select an Event";
            }
            ?>
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenu1">
            <?php
              if (isSuperAdmin($role)) {
                echo '<li><input type="text" id="event-filter-field" class="form-control event-filter-field" placeholder="Search All Events"></li><li class="divider"></li>';	
                echo '<div class="dropdown-events-all"><table id="events-all" class="events-all">';
                $sql = $mysqli->query("SELECT * FROM `events` ORDER BY `eventname` ASC");
                //echo'<tr href="#"><td><font color= #000000> <b> Current: </b> '. $row['eventname'] . ' </font></td></tr>';
                while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                  echo '<tr href="/peachpits/admin/dashboard?event=' . $row['eventid'] . '" data-toggle="tooltip" data-placement="right" title="' . $row['eventid'] . '"><td><font color= #000000 >' . $row['eventname'] . '</font><td></tr>';
                }
                echo '<tr href="/peachpits/admin/manage-events?event="><td><font color= red> Manage Events </font></td></tr>';
                echo '</table></div>';
              } else {
                echo '<li class="disabled"><a href="#"><b>Current: </b>'.$row['eventname'].'</a></li><li role="separator" class="divider"></li>';
                $sqlEventsStr;
                $index = 0;
                foreach ($eventsArr as $singleEvent) {
                  $str = $eventsArr[$index];
                  $arr = explode('@',$str);
                  $singleEvent = $arr[1];
                  $sqlEventsStr2[] = $singleEvent;
                  $sqlEventsStr[] = "`eventname` LIKE '".$singleEvent."'";
                  $index++;
                }
                $sql = $mysqli->query("SELECT * FROM `events` ORDER BY `eventname` ASC");
                while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                  if ($row['eventid'] != $currentEvent && in_array($row['eventname'], $sqlEventsStr2)) {
                    echo '<li><a href="/peachpits/admin/dashboard?event=' . $row['eventid'] . '" data-toggle="tooltip" data-placement="right" title="' . $row['eventid'] . '">' . $row['eventname'] . '</a></li>';
                  }
                }
              }
            ?> 
          </ul>
        </div>
      </div>
      <div class="dashboard-menu">
        <ul class="nav">
          <li id="dashboard" class="active"><a href="/peachpits/admin/dashboard?event=<?php echo $currentEvent; ?>"><i class="glyphicon glyphicon-user"></i> Account Settings </a></li>
          <?php if(isSuperAdmin($role) || isEventAdmin($role)){ echo '<li id="events"><a href="/peachpits/admin/manage-events?event=' . $currentEvent . '"><i class="glyphicon glyphicon-star"></i> Manage Events </a></li>';} ?>
          <?php if(isSuperAdmin($role) || isEventAdmin($role)  || isLeadInspector($role)){ echo '<li id="users"><a href="/peachpits/admin/manage-users?event=' . $currentEvent . '"><i class="glyphicon glyphicon-cog"></i> Manage Users </a></li>';} ?>
          <?php if(isPitAdmin($role) || isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="teams"><a href="/peachpits/admin/manage-teams?event=' . $currentEvent . '"><i class="glyphicon glyphicon-list-alt"></i> Manage Team List </a></li>';} ?>
          <?php if(isInspector($role) || isLeadInspector($role) || isSuperAdmin($role)){ echo '<li id="inspect"><a href="/peachpits/admin/manage-inspection?event=' . $currentEvent . '"><i class="glyphicon glyphicon-search"></i> Manage Inspections </a></li>';} ?>
          <?php if(isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="matches"><a href="/peachpits/admin/manage-matches?event=' . $currentEvent . '"><i class="glyphicon glyphicon-calendar"></i> Manage Match Schedule </a></li>';} ?>
          <?php if(isPitAdmin($role) || isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="announcements"><a href="/peachpits/admin/manage-announcements?event=' . $currentEvent . '"><i class="glyphicon glyphicon-bullhorn"></i> Manage Announcements </a></li>';} ?>
          <?php if(isLeadInspector($role) || isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="map"><a href="/peachpits/admin/manage-map?event=' . $currentEvent . '"><i class="glyphicon glyphicon-map-marker"></i> Pit Map Creator </a></li>';} ?>
          <li><a href="signout"><i class="glyphicon glyphicon-off"></i> Sign Out </a></li>
        </ul>
      </div>
    </div>


    <div class="dashboard-mobile-menu visible-xs">
      <button class="btn btn-mobile-menu center-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        Dashboard Menu <span class="caret"></span>
      </button>
      <div class="collapse" id="collapseExample">
        <div class="dashboard-usertitle">
          <div class="dashboard-usertitle-name"><?php echo $firstname; ?> <?php echo $lastname; ?></div>
          <div class="dashboard-usertitle-job"><?php echo $role; ?></div>
          <div class="dropdown">     
            <button class="btn btn-default dropdown-toggle" style="max-width:90%; white-space:normal;" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
              <?php
                $sql = $mysqli->query("SELECT `eventname` FROM `events` WHERE `eventid` LIKE '$currentEvent'");
                $row = mysqli_fetch_assoc($sql);
                echo $row['eventname'];
              ?>
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenu1">
              <?php
                if (isSuperAdmin($role)) {
                  echo '<li><input type="text" id="event-filter-field-m" class="form-control event-filter-field" placeholder="Search All Events"></li><li class="divider"></li>';	
                  echo '<div class="dropdown-events-all"><table id="events-all-m" class="events-all">';
                  $sql = $mysqli->query("SELECT * FROM `events`");
                  //echo'<tr href="#"><td><font color= #000000> <b> Current: </b> '. $row['eventname'] . ' </font></td></tr>';
                  while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                    $eventYear = (int) filter_var($row['eventid'], FILTER_SANITIZE_NUMBER_INT);
                    if ($eventYear == date(Y)) {
                      echo '<tr href="/peachpits/admin/dashboard?event=' . $row['eventid'] . '" data-toggle="tooltip" data-placement="bottom" title="' . $row['eventid'] . '"><td><font color= #000000 >' . $row['eventname'] . '</font><td></tr>';
                    }
                  }
                  echo '<tr href="/peachpits/admin/manage-events?event="><td><font color= red> Manage Events </font></td></tr>';
                  echo '</table></div>';
                } else {
                  echo '<li class="disabled"><a href="#"><b>Current: </b>'.$row['eventname'].'</a></li><li role="separator" class="divider"></li>';
                  $sqlEventsStr;
                  $index = 0;
                  foreach ($eventsArr as $singleEvent) {
                    $str = $eventsArr[$index];
                    $arr = explode('@',$str);
                    $singleEvent = $arr[1];
                    $sqlEventsStr[] = "`eventname` LIKE '".$singleEvent."'";
                    $index++;
                  }
                  $sql = $mysqli->query("SELECT * FROM `events` WHERE " .implode(" OR ", $sqlEventsStr));
                  while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                    if ($row['eventid'] != $currentEvent) {
                      echo '<li><a href="/peachpits/admin/dashboard?event=' . $row['eventid'] . '" data-toggle="tooltip" data-placement="bottom" title="' . $row['eventid'] . '">' . $row['eventname'] . '</a></li>';
                    }
                  }
                }
              ?> 
            </ul>
          </div>
        </div>
        <div class="dashboard-menu">
          <ul class="nav">
            <li id="dashboard" class="active"><a href="/peachpits/admin/dashboard?event=<?php echo $currentEvent; ?>"><i class="glyphicon glyphicon-user"></i> Account Settings </a></li>
            <?php if(isSuperAdmin($role) || isEventAdmin($role)){ echo '<li id="events"><a href="/peachpits/admin/manage-events?event=' . $currentEvent . '"><i class="glyphicon glyphicon-star"></i> Manage Events </a></li>';} ?>
            <?php if(isSuperAdmin($role) || isEventAdmin($role) || isLeadInspector($role)){ echo '<li id="users"><a href="/peachpits/admin/manage-users?event=' . $currentEvent . '"><i class="glyphicon glyphicon-cog"></i> Manage Users </a></li>';} ?>
            <?php if(isPitAdmin($role) || isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="teams"><a href="/peachpits/admin/manage-teams?event=' . $currentEvent . '"><i class="glyphicon glyphicon-list-alt"></i> Manage Team List </a></li>';} //Only visible for event admin ?>
            <?php if(isInspector($role) || isLeadInspector($role) || isSuperAdmin($role)){ echo '<li id="inspect"><a href="/peachpits/admin/manage-inspection?event=' . $currentEvent . '"><i class="glyphicon glyphicon-search"></i> Manage Inspection Status </a></li>';} //Only visible for inspector ?>
            <?php if(isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="matches"><a href="/peachpits/admin/manage-matches?event=' . $currentEvent . '"><i class="glyphicon glyphicon-calendar"></i> Manage Match Schedule </a></li>';} //Only visible for event admin ?>
            <?php if(isPitAdmin($role) || isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="announcements"><a href="/peachpits/admin/manage-announcements?event=' . $currentEvent . '"><i class="glyphicon glyphicon-bullhorn"></i> Manage Announcements </a></li>';} //Only visible for event admin ?>
            <?php if(isLeadInspector($role) || isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="map"><a href="/peachpits/admin/manage-map?event=' . $currentEvent . '"><i class="glyphicon glyphicon-map-marker"></i> Pit Map Creator </a></li>';} //Only visible for event admin and lead inspector ?>
            <li><a href="signout"><i class="glyphicon glyphicon-off"></i> Sign Out </a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
<script>
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>