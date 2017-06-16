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
                echo $row['eventname'];
              ?>
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenu1">             		    
			    <?php                       
                    if(isSuperAdmin($role)){
                        echo '<li><input type="text" id="event-filter-field" class="form-control" placeholder="Search All Events"></li><li class="divider"></li>';	
                        echo '<div class="dropdown-events-all"><table id="events-all">';
                        $sql = $mysqli->query("SELECT * FROM `events`");      
                        //echo'<tr href="#"><td><font color= #000000> <b> Current: </b> '. $row['eventname'] . ' </font></td></tr>';
				        while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
					        echo '<tr href="admin/dashboard.php?event=' . $row['eventid'] . '"><td><font color= #000000 >' . $row['eventname'] . '</font><td></tr>';
					    }
					    echo '<tr href="admin/manage-events.php?event="><td><font color= red> Manage Events </font></td></tr>';
                        echo '</table></div>';
                    }
                    else{
                        echo '<li class="disabled"><a href="#"><b>Current: </b>'.$row['eventname'].'</a></li><li role="separator" class="divider"></li>';
                        $sqlEventsStr;
                        $index = 0;
                        foreach($eventsArr as $singleEvent){
                            $str = $eventsArr[$index];
                            $arr = explode('@',$str);
                            $singleEvent = $arr[1];
                            $sqlEventsStr[] = "`eventname` LIKE '".$singleEvent."'";
                            $index++;
                        }
                        $sql = $mysqli->query("SELECT * FROM `events` WHERE " .implode(" OR ", $sqlEventsStr));
                        while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
                            if($row['eventid'] != $currentEvent){
                                echo '<li><a href="admin/dashboard.php?event=' . $row['eventid'] . '">' . $row['eventname'] . '</a></li>';
                            }
                        }
                    }
                ?> 
            </ul>
          </div>
        </div>
        <div class="dashboard-menu">
          <ul class="nav">
            <li id="dashboard" class="active"><a href="admin/dashboard.php?event=<?php echo $currentEvent; ?>"><i class="glyphicon glyphicon-user"></i> Account Settings </a></li>
            <?php if(isSuperAdmin($role) || isEventAdmin($role)){ echo '<li id="events"><a href="admin/manage-events.php?event=' . $currentEvent . '"><i class="glyphicon glyphicon-star"></i> Manage Events </a></li>';} ?>
            <?php if(isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="teams"><a href="admin/manage-teams.php?event=' . $currentEvent . '"><i class="glyphicon glyphicon-list-alt"></i> Manage Team List </a></li>';} ?>
            <?php if(isInspector($role) || isLeadInspector($role) || isSuperAdmin($role)){ echo '<li id="inspect"><a href="admin/manage-inspection.php?event=' . $currentEvent . '"><i class="glyphicon glyphicon-search"></i> Manage Inspections </a></li>';} ?>
            <?php if(isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="matches"><a href="admin/manage-matches.php?event=' . $currentEvent . '"><i class="glyphicon glyphicon-calendar"></i> Manage Match Schedule </a></li>';} ?>
            <?php if(isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="map"><a href="admin/manage-map.php?event=' . $currentEvent . '"><i class="glyphicon glyphicon-map-marker"></i> Pit Map Creator </a></li>';} ?>
            <li><a href="signout.php"><i class="glyphicon glyphicon-off"></i> Sign Out </a></li>
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
                    if(isSuperAdmin($role)){
                        echo '<li><input type="text" id="event-filter-field" class="form-control" placeholder="Search All Events"></li><li class="divider"></li>';	
                        echo '<div class="dropdown-events-all"><table id="events-all">';
                        $sql = $mysqli->query("SELECT * FROM `events`");      
                        //echo'<tr href="#"><td><font color= #000000> <b> Current: </b> '. $row['eventname'] . ' </font></td></tr>';
                        while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
                            echo '<tr href="admin/dashboard.php?event=' . $row['eventid'] . '"><td><font color= #000000 >' . $row['eventname'] . '</font><td></tr>';
                        }
                        echo '<tr href="admin/manage-events.php?event="><td><font color= red> Manage Events </font></td></tr>';
                        echo '</table></div>';
                    }
                    else{
                        echo '<li class="disabled"><a href="#"><b>Current: </b>'.$row['eventname'].'</a></li><li role="separator" class="divider"></li>';
                        $sqlEventsStr;
                        $index = 0;
                        foreach($eventsArr as $singleEvent){
                            $str = $eventsArr[$index];
                            $arr = explode('@',$str);
                            $singleEvent = $arr[1];
                            $sqlEventsStr[] = "`eventname` LIKE '".$singleEvent."'";
                            $index++;
                        }
                        $sql = $mysqli->query("SELECT * FROM `events` WHERE " .implode(" OR ", $sqlEventsStr));
                        while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
                            if($row['eventid'] != $currentEvent){
                                echo '<li><a href="admin/dashboard.php?event=' . $row['eventid'] . '">' . $row['eventname'] . '</a></li>';
                            }
                        }
                    }
                ?> 
              </ul>
            </div>
          </div>
          <div class="dashboard-menu">
            <ul class="nav">
              <li id="dashboard" class="active"><a href="admin/dashboard.php?event=<?php echo $currentEvent; ?>"><i class="glyphicon glyphicon-user"></i> Account Settings </a></li>
              <?php if(isSuperAdmin($role) || isEventAdmin($role)){ echo '<li id="events"><a href="admin/manage-events.php?event=' . $currentEvent . '"><i class="glyphicon glyphicon-star"></i> Manage Events </a></li>';} ?>
              <?php if(isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="teams"><a href="admin/manage-teams.php?event=' . $currentEvent . '"><i class="glyphicon glyphicon-list-alt"></i> Manage Team List </a></li>';} //Only visible for event admin ?>
              <?php if(isInspector($role) || isSuperAdmin($role)){ echo '<li id="inspect"><a href="admin/manage-inspection.php?event=' . $currentEvent . '"><i class="glyphicon glyphicon-list-alt"></i> Manage Inspection Status </a></li>';} //Only visible for inspector ?>
              <?php if(isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="matches"><a href="admin/manage-matches.php?event=' . $currentEvent . '"><i class="glyphicon glyphicon-calendar"></i> Manage Match Schedule </a></li>';} //Only visible for event admin ?>
              <?php if(isEventAdmin($role) || isSuperAdmin($role)){ echo '<li id="map"><a href="admin/manage-map.php?event=' . $currentEvent . '"><i class="glyphicon glyphicon-map-marker"></i> Pit Map Creator </a></li>';} //Only visible for event admin ?>
              <li><a href="signout.php"><i class="glyphicon glyphicon-off"></i> Sign Out </a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>