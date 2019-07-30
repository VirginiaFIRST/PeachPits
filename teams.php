<title>PeachPits - Teams</title>
<?php 
	include "header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="chooseevent"</script>';
	}
	else {
		$eventTeams = $currentEvent."_teams";
    $eventInspections = $currentEvent."_inspections";
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventname = $row['eventname'];

?>
<style>
  @media screen and (max-width: 767px) {
    .schoolname-text {
      width:100vw;
    }
  }
</style>
<div class="page-head" style="margin-bottom:0px">
	<div class="container">
		<h1>Teams at <?php echo $eventname; ?></h1>
	</div>
</div>
<div class="dashboard-toolbar hidden" id="dashboard-view">
		<div class="container-fluid text-center">
			<button class="btn btn-default btn-inspection-teams">View Status</button>
		</div>
</div>
<!--The header that sticks to the top of the screen when the user scrolls down-->
<div id="scrolling-header" style="position:sticky;position: -webkit-sticky;top:0px">
    <div class="dashboard-toolbar" id="dashboard-hide">
        <div class="container-fluid text-center">
          <button class="btn btn-default btn-inspection-teams-hide">Hide Status</button>
          <?php if(isSuperAdmin($role) || isEventAdmin($role)) { ?> 
            <div class="dropdown dropdown-teams">
              <button id="btn-refresh-time" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Refresh Time <span class="caret"></span></button>
                <ul class="dropdown-menu">
                <?php
                  echo '<li class="disabled"><a href="display?event='.$currentEvent.'"><b>Current: </b>'.$refreshTime.'</a></li>';
                  echo '<li role="separator" class="divider"></li>';
                  foreach($refreshArr as $option) {
                    if ($option != $refreshTime) {
                      $optionAsInt = (int) filter_var($option, FILTER_SANITIZE_NUMBER_INT);
                      echo '<li><a href="update_refresh_time?event='.$currentEvent.'&returnLocation=teams&newRefreshTime='.$optionAsInt.'">'.$option.'</a></li>';
                    }
                  }
                ?>
                </ul>
            </div>
          <?php } ?>
        </div>
    </div>
    <!--Inspection Legend size 1: desktop-->
    <div class="dashboard-toolbar" id="inspection-legend-header1">
        <div class="container-fluid text-center">
          <span style="font-weight:bold;font-size:16px">
            <span style="white-space:nowrap"><div class="keyColor levelFour" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Complete (</div><div class="key-text count-complete" style="display:inline" id="count-complete"></div><div class="key-text" style="display:inline">)</div></span>
            <span style="white-space:nowrap"><div class="keyColor levelThree" style="float:none;display:inline-block;vertical-align:middle;margin-left:20px"></div><div class="key-text" style="display:inline">Minor Issue (</div><div class="key-text count-minor" style="display:inline" id="count-minor"></div><div class="key-text" style="display:inline">)</div></span>
            <span style="white-space:nowrap"><div class="keyColor levelTwo" style="float:none;display:inline-block;vertical-align:middle;margin-left:20px"></div><div class="key-text" style="display:inline">Major Issue (</div><div class="key-text count-major" style="display:inline" id="count-major"></div><div class="key-text" style="display:inline">)</div></span>
            <span style="white-space:nowrap"><div class="keyColor levelOne" style="float:none;display:inline-block;vertical-align:middle;margin-left:20px"></div><div class="key-text" style="display:inline">Not Started (</div><div class="key-text count-notstarted" style="display:inline" id="count-notstarted"></div><div class="key-text" style="display:inline">)</div></span>
          </span>
        </div>
    </div>
    <!--Inspection Legend size 2: tablets-->
    <div class="dashboard-toolbar" id="inspection-legend-header2">
        <div class="container-fluid text-center">
          <span style="font-weight:bold;font-size:16px">
            <div style="float:left;width:50%">
              <div style="margin-left:auto;margin-right:auto;width:167px">
                <span style="white-space:nowrap;float:left"><div class="keyColor levelFour" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Complete (</div><div class="key-text count-complete" style="display:inline" id="count-complete"></div><div class="key-text" style="display:inline">)</div></span>
                <span style="white-space:nowrap;float:left"><div class="keyColor levelThree" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Minor Issue (</div><div class="key-text count-minor" style="display:inline" id="count-minor"></div><div class="key-text" style="display:inline">)</div></span>
              </div>
            </div>
            <div style="float:right;width:50%">
              <div style="margin-left:auto;margin-right:auto;width:221px">
                <span style="white-space:nowrap;float:left"><div class="keyColor levelTwo" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Major Issue (</div><div class="key-text count-major" style="display:inline" id="count-major"></div><div class="key-text" style="display:inline">)</div></span>
                <span style="white-space:nowrap;float:left;margin-right:20px"><div class="keyColor levelOne" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Not Started (</div><div class="key-text count-notstarted" style="display:inline" id="count-notstarted"></div><div class="key-text" style="display:inline">)</div></span>
              </div>
            </div>
          </span>
        </div>
    </div>
    <!--Inspection Legend size 3: mobile phones-->
    <div class="dashboard-toolbar" id="inspection-legend-header3">
        <div class="container-fluid text-center">
          <div style="margin-left:auto;margin-right:auto;width:200px">
            <span style="font-weight:bold;font-size:13px">
              <span style="float:left"><div class="keyColor levelFour" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Complete (</div><div class="key-text count-complete" style="display:inline" id="count-complete"></div><div class="key-text" style="display:inline">)</div></span>
              <span style="float:left"><div class="keyColor levelThree" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Minor Issue (</div><div class="key-text count-minor" style="display:inline" id="count-minor"></div><div class="key-text" style="display:inline">)</div></span>
              <span style="float:left"><div class="keyColor levelTwo" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Major Issue (</div><div class="key-text count-major" style="display:inline" id="count-major"></div><div class="key-text" style="display:inline">)</div></span>
              <span style="float:left"><div class="keyColor levelOne" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Not Started (</div><div class="key-text count-notstarted" style="display:inline" id="count-notstarted"></div><div class="key-text" style="display:inline">)</div></span>
            </span>
          </div>
        </div>
    </div>
</div>
<div class="container content" style="margin-top:20px">
    <!--Teams table with inspection status and legend-->
    <div class="table-responsive" id="div-teams-table-with-status">
      <table style="border: 1px solid #ddd" class="table"  id="teams-table-with-status">
        <thead style="background-color:white">
          <th style="width:15%"><strong>Team #</strong></th>
          <th><strong>Team Name</strong></th>
          <th><strong>Status</strong></th>
          <th style="width:5%" class="text-center"><strong>Pit</strong></th>
          <th style="width:5%" class="text-center"><strong>More</strong></th>
        </thead>
        <tbody>
        <?php 
          $statusCount = ['Complete'=> 0, 'Minor Issue'=> 0, 'Major Issue'=> 0, 'Not Started'=> 0];
          //Fetches all teams in order from the database
          $sql = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus FROM `".$eventTeams."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
          while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
            if ($row['inspectionstatus'] == 'Complete'){
              $statusCount['Complete'] += 1;
              echo "<tr class='levelFour' id='". $row['teamid'] ."'>";
            }
            elseif ($row['inspectionstatus'] == 'Minor Issue'){
              $statusCount['Minor Issue'] += 1;
              echo "<tr class='levelThree' id='". $row['teamid'] ."'>";
            }
            elseif ($row['inspectionstatus'] == 'Major Issue'){
              $statusCount['Major Issue'] += 1;
              echo "<tr class='levelTwo' id='". $row['teamid'] ."'>";
            }
            elseif ($row['inspectionstatus'] == 'Not Started'){
              $statusCount['Not Started'] += 1;
              echo "<tr class='levelOne' id='". $row['teamid'] ."'>";
            }
            else {
              echo "<tr id='". $row['teamid'] ."'>";
            }
            echo "<td id='teamid'>". $row['teamid'] ."</td>";
            echo "<td id='teamname'>". $row['teamname'] ."</td>";
            echo "<td id='".$row['teamid']."-inspectionstatus'>". $row['inspectionstatus'] ."</td>";
            echo '<td style="width:5%" class="text-center" id="pitmap"><a href="pitmap?event='.$currentEvent. '&team='. $row['teamid'] .'"><span class="glyphicon glyphicon-map-marker"></span></a></td>';
            echo "<td style='width:5%;' class='text-center btn-teaminfo' id='". $row["teamid"] ."'><span class='glyphicon glyphicon-triangle-bottom closed'</span></td>";
            echo "</tr>";
            echo '<tr style="display: none;" class="team-info" id="info-'. $row['teamid'] .'">';?>
            <td colspan="5" style="white-space:initial">
              <div class="team-tab">
                <?php 
                  if(isInspector($role) || isLeadInspector($role) || isEventAdmin($role) || isSuperAdmin($role)){ 
                    echo'<input type="button" style="width: 33.33333%" class="tablinks" id="tabinfo-'. $row['teamid'] .'" onclick="openTab(\'teaminfo-\' + '. $row['teamid'] .')" value="Team Info">';
                    echo'<input type="button" style="width: 33.33333%" class="tablinks" id="tabinspection-'. $row['teamid'] .'" onclick="openTab(\'teaminspection-\' + '. $row['teamid'] .')" value="Inspection">';
                    echo'<input type="button" style="width: 33.33333%" class="tablinks" id="tabmatches-'. $row['teamid'] .'" onclick="openTab(\'teammatches-\' + '. $row['teamid'] .')" value="Matches">';
                  } else {
                    echo'<input type="button" style="width: 50%" class="tablinks" id="tabinfo-'. $row['teamid'] .'" onclick="openTab(\'teaminfo-\' + '. $row['teamid'] .')" value="Team Info">';
                    echo'<input type="button" style="width: 50%" class="tablinks" id="tabmatches-'. $row['teamid'] .'" onclick="openTab(\'teammatches-\' + '. $row['teamid'] .')" value="Matches">';
                  }
                ?>
              </div>
              <?php echo'<div id="teaminfo-'. $row['teamid'] .'" class="tabcontent" style="overflow:initial;overflow-wrap:break-word">';?>
                  <?php echo'<h4><b>Location: </b></h4><p class="pull-left">'. $row['location'] .'</p>';?>
                  <div class="clearfix"></div>
                  <?php echo'<h4><b>School Name: </b></h4><p class="schoolname-text">'. $row['schoolname'] .'</p>';?>
              </div>
              <?php if(isInspector($role) || isLeadInspector($role) || isEventAdmin($role) || isSuperAdmin($role)){ ?>
              <?php echo '<div id="teaminspection-'. $row['teamid'] .'" class="tabcontent">'; ?>
                      <h4><b>Inspection Status Changes: </b></h4>
                      <div class="table-responsive">
                        <table style="border: 1px solid #ddd" class="table table-hover table-striped" id="inspections-table-<?php echo $row['teamid']; ?>">
                          <thead style="background-color:white;border-top:none">
                            <th><strong>Inspection Status</strong></th>
                            <th><strong>Inspection Notes</strong></th>
                            <th><strong>Modified By</strong></th>
                            <th><strong>Modified Time</strong></th>
                          </thead>
                        </table>
                      </div>

                <h4><b>Initial Inspector: </b></h4><p class="map-initialinspector" id="initial-inspector-<?php echo $row['teamid']; ?>"></p>
              </div>
              <?php } ?>
              <?php echo'<div id="teammatches-'. $row['teamid'] .'" class="tabcontent">';?>
                <?php echo'<div class="team-eventname"><h3>Match Schedule: ' . $eventname . '</h3></div>';?>
                  <?php echo'<table id="table-team-matches-'. $row['teamid'] .'" class="table table-team-matches" style="margin-top:20px;">';?>
                      <thead>
                          <tr>
                              <td class="text-center"><b>Match #</b></td>
                              <td class="text-center"><b>Start Time</b></td>
                              <td class="text-center"><b>Red 1</b></td>
                              <td class="text-center"><b>Red 2</b></td>
                              <td class="text-center"><b>Red 3</b></td>
                              <td class="text-center"><b>Blue 1</b></td>
                              <td class="text-center"><b>Blue 2</b></td>
                              <td class="text-center"><b>Blue 3</b></td>
                          </tr>
                      </thead>
                  </table>
                  <?php echo'<table id="table-team-matches-mobile-'. $row['teamid'] .'" class="table table-team-matches-mobile">';?>
                      <thead>
                          <tr>
                              <td rowspan="2" style="vertical-align:middle"><b>Match</b></td>
                              <td rowspan="2" class="text-center" style="vertical-align:middle"><b>Time</b></td>
                              <td colspan="3" class="text-center"><b>Driver's Station</b></td>
                          </tr>
                          <tr>
                              <td class="text-center"><b>1</b></td>
                              <td class="text-center"><b>2</b></td>
                              <td class="text-center"><b>3</b></td>
                          </tr>
                      </thead>
                  </table>
              </div>
            </td>
            <?php echo '</tr>';
          }	
        ?>
        </tbody>
      </table>
    </div>
    <!--Teams table without inspection status or legend-->
    <div class="table-responsive hidden" id="div-teams-table-without-status">
      <table style="border: 1px solid #ddd" class="table table-hover" id="teams-table-without-status">
        <thead style="background-color:white;border-top:none">
          <th style="width:15%"><strong>Team #</strong></th>
          <th><strong>Team Name</strong></th>
          <th><strong>Status</strong></th>
          <th style="width:5%" class="text-center"><strong>Pit</strong></th>
          <th style="width:5%" class="text-center"><strong>More</strong></th>
        </thead>
        <tbody>
        <?php 
          //Fetches all teams in order from the database
          $sql = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus FROM `".$eventTeams."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
          while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
            echo "<tr id='". $row['teamid'] ."'>";
            echo "<td id='teamid'>". $row['teamid'] ."</td>";
            echo "<td id='teamname'>". $row['teamname'] ."</td>";
            echo "<td id='".$row['teamid']."-inspectionstatus2'>". $row['inspectionstatus'] ."</td>";
            echo '<td style="width:5%" class="text-center" id="pitmap"><a href="pitmap?event='.$currentEvent. '&team='. $row['teamid'] .'"><span class="glyphicon glyphicon-map-marker"></span></a></td>';
            echo "<td style='width:5%;' class='text-center btn-teaminfo' id='". $row["teamid"] ."'><span class='glyphicon glyphicon-triangle-bottom closed'</span></td>";
            echo "</tr>";
            echo '<tr style="display: none;" class="team-info" id="info2-'. $row['teamid'] .'">';?>
            <td colspan="5" style="white-space:initial">
              <div class="team-tab">
                <?php 
                  if(isInspector($role) || isLeadInspector($role) || isEventAdmin($role) || isSuperAdmin($role)) {
                    echo'<input type="button" style="width: 33.33333%" class="tablinks" id="tabinfo2-'. $row['teamid'] .'" onclick="openTab(\'teaminfo-\' + '. $row['teamid'] .')" value="Team Info">';
                    echo'<input type="button" style="width: 33.33333%" class="tablinks" id="tabinspection2-'. $row['teamid'] .'" onclick="openTab(\'teaminspection-\' + '. $row['teamid'] .')" value="Inspection">';
                    echo'<input type="button" style="width: 33.33333%" class="tablinks" id="tabmatches2-'. $row['teamid'] .'" onclick="openTab(\'teammatches-\' + '. $row['teamid'] .')" value="Matches">';
                  }
                  else {
                    echo'<input type="button" style="width: 50%" class="tablinks" id="tabinfo2-'. $row['teamid'] .'" onclick="openTab(\'teaminfo-\' + '. $row['teamid'] .')" value="Team Info">';
                    echo'<input type="button" style="width: 50%" class="tablinks" id="tabmatches2-'. $row['teamid'] .'" onclick="openTab(\'teammatches-\' + '. $row['teamid'] .')" value="Matches">';
                  }
                ?>
              </div>
              <?php echo'<div id="teaminfo2-'. $row['teamid'] .'" class="tabcontent" style="overflow:initial;overflow-wrap:break-word">';?>
                  <?php echo'<h4><b>Location: </b></h4><p class="pull-left">'. $row['location'] .'</p>';?>
                  <div class="clearfix"></div>
                  <?php echo'<h4><b>School Name: </b></h4><p class="schoolname-text">'. $row['schoolname'] .'</p>';?>
              </div>
              <?php if(isInspector($role) || isLeadInspector($role) || isSuperAdmin($role)){ ?>
              <?php echo '<div id="teaminspection2-'. $row['teamid'] .'" class="tabcontent">'; ?>
                      <h4><b>Inspection Status Changes: </b></h4>
                      <div class="table-responsive">
                        <table style="border: 1px solid #ddd" class="table table-hover table-striped" id="inspections-table2-<?php echo $row['teamid']; ?>">
                          <thead style="background-color:white;border-top:none">
                            <th><strong>Inspection Status</strong></th>
                            <th><strong>Inspection Notes</strong></th>
                            <th><strong>Modified By</strong></th>
                            <th><strong>Modified Time</strong></th>
                          </thead>
                        </table>
                      </div>

                <h4><b>Initial Inspector: </b></h4><p class="map-initialinspector" id="initial-inspector2-<?php echo $row['teamid']; ?>"></p>
              </div>
              <?php } ?>
              <?php echo'<div id="teammatches2-'. $row['teamid'] .'" class="tabcontent">';?>
                <?php echo'<div class="team-eventname"><h3>Match Schedule: ' . $eventname . '</h3></div>';?>
                  <?php echo'<table id="table-team-matches2-'. $row['teamid'] .'" class="table table-team-matches" style="margin-top:20px;">';?>
                      <thead>
                          <tr>
                              <td class="text-center"><b>Match #</b></td>
                              <td class="text-center"><b>Start Time</b></td>
                              <td class="text-center"><b>Red 1</b></td>
                              <td class="text-center"><b>Red 2</b></td>
                              <td class="text-center"><b>Red 3</b></td>
                              <td class="text-center"><b>Blue 1</b></td>
                              <td class="text-center"><b>Blue 2</b></td>
                              <td class="text-center"><b>Blue 3</b></td>
                          </tr>
                      </thead>
                  </table>
                  <?php echo'<table id="table-team-matches-mobile2-'. $row['teamid'] .'" class="table table-team-matches-mobile">';?>
                      <thead>
                          <tr>
                              <td rowspan="2" style="vertical-align:middle"><b>Match</b></td>
                              <td rowspan="2" class="text-center" style="vertical-align:middle"><b>Time</b></td>
                              <td colspan="3" class="text-center"><b>Driver's Station</b></td>
                          </tr>
                          <tr>
                              <td class="text-center"><b>1</b></td>
                              <td class="text-center"><b>2</b></td>
                              <td class="text-center"><b>3</b></td>
                          </tr>
                      </thead>
                  </table>
              </div>
            </td>
            <?php echo '</tr>';
          }	
        ?>
        </tbody>
      </table>
    </div>

    <script>
    function openTab(tab) {
      var i, id, tabcontent, tablinks;
      // Get all elements with class="tabcontent" and hide them
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
          tabcontent[i].style.display = "none";
      }
      // Get all elements with class="tablinks" and remove the class "active"
      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
          tablinks[i].className = tablinks[i].className.replace(" active", "");
      }
      // Show the current tab, and add an "active" class to the button that opened the tab
      var tab2 = tab.replace("-", "2-");
      var targetBtn = tab.replace("team", "tab");
      var targetBtn2 = tab2.replace("team", "tab");
      document.getElementById(targetBtn).className += " active";
      document.getElementById(targetBtn2).className += " active";
      document.getElementById(tab).style.display = "block";
      document.getElementById(tab2).style.display = "block";
      id = tab.substring(12);
      
    }
    $('.btn-teaminfo').on('click', function(){
      var id = this.id;
      $('.teams').css('table-layout', 'auto');


      $('#table-team-matches-mobile-' + id + ' .matchrow').remove();
      $('#table-team-matches-mobile2-' + id + ' .matchrow').remove();
      $('#table-team-matches-' + id + ' .matchrow').remove();
      $('#table-team-matches2-' + id + ' .matchrow').remove();
      var teamSchd;
      $.ajax({
        url: 'get_team_schedule?event=' + currentEvent + '&team=' + id,
        type: 'POST',
        success: function (data) {
          teamSchd = [];
          teamSchd = data;
          for (var i = 0; i < teamSchd.length; i++) {
            //Mobile Table
            var row = '';
            row = '<tr class="matchrow"><td rowspan="2" id="matchnumber">' + teamSchd[i]['matchnumber'] + '</td><td rowspan="2" id="starttime">' + teamSchd[i]['start'] + '</td>';
            if (id == teamSchd[i]['red1']) { row = row + '<td id="red1" class="red text-center"><b>' + teamSchd[i]['red1'] + '</b></td>' } else { row = row + '<td id="red1" class="red text-center">' + teamSchd[i]['red1']+'</td>' }
            if (id == teamSchd[i]['red2']) { row = row + '<td id="red2" class="red text-center"><b>' + teamSchd[i]['red2'] + '</b></td>' } else { row = row + '<td id="red2" class="red text-center">' + teamSchd[i]['red2'] + '</td>' }
            if (id == teamSchd[i]['red3']) { row = row + '<td id="red3" class="red text-center"><b>' + teamSchd[i]['red3'] + '</b></td>' } else { row = row + '<td id="red3" class="red text-center">' + teamSchd[i]['red3'] + '</td>' }
            row = row + '</tr><tr class="matchrow">';
            if (id == teamSchd[i]['blue1']) { row = row + '<td id="blue1" class="blue text-center"><b>' + teamSchd[i]['blue1'] + '</b></td>' } else { row = row + '<td id="blue1" class="blue text-center">' + teamSchd[i]['blue1'] + '</td>' }
            if (id == teamSchd[i]['blue2']) { row = row + '<td id="blue2" class="blue text-center"><b>' + teamSchd[i]['blue2'] + '</b></td>' } else { row = row + '<td id="blue2" class="blue text-center">' + teamSchd[i]['blue2'] + '</td>' }
            if (id == teamSchd[i]['blue3']) { row = row + '<td id="blue3" class="blue text-center"><b>' + teamSchd[i]['blue3'] + '</b></td>' } else { row = row + '<td id="blue3" class="blue text-center">' + teamSchd[i]['blue3'] + '</td>' }
            row = row + '</tr>';
            $('#table-team-matches-mobile-' + id).append(row);
            $('#table-team-matches-mobile2-' + id).append(row);
            //Desktop Table
            var row = '';
            row = '<tr class="matchrow"><td id="matchnumber" class="text-center">' + teamSchd[i]['matchnumber'] + '</td><td id="starttime" class="text-center">' + teamSchd[i]['start'] + '</td>';
            if (id == teamSchd[i]['red1']) { row = row + '<td id="red1" class="red text-center"><b>' + teamSchd[i]['red1'] + '</b></td>' } else { row = row + '<td id="red1" class="red text-center">' + teamSchd[i]['red1'] + '</td>' }
            if (id == teamSchd[i]['red2']) { row = row + '<td id="red2" class="red text-center"><b>' + teamSchd[i]['red2'] + '</b></td>' } else { row = row + '<td id="red2" class="red text-center">' + teamSchd[i]['red2'] + '</td>' }
            if (id == teamSchd[i]['red3']) { row = row + '<td id="red3" class="red text-center"><b>' + teamSchd[i]['red3'] + '</b></td>' } else { row = row + '<td id="red3" class="red text-center">' + teamSchd[i]['red3'] + '</td>' }
            if (id == teamSchd[i]['blue1']) { row = row + '<td id="blue1" class="blue text-center"><b>' + teamSchd[i]['blue1'] + '</b></td>' } else { row = row + '<td id="blue1" class="blue text-center">' + teamSchd[i]['blue1'] + '</td>' }
            if (id == teamSchd[i]['blue2']) { row = row + '<td id="blue2" class="blue text-center"><b>' + teamSchd[i]['blue2'] + '</b></td>' } else { row = row + '<td id="blue2" class="blue text-center">' + teamSchd[i]['blue2'] + '</td>' }
            if (id == teamSchd[i]['blue3']) { row = row + '<td id="blue3" class="blue text-center"><b>' + teamSchd[i]['blue3'] + '</b></td>' } else { row = row + '<td id="blue3" class="blue text-center">' + teamSchd[i]['blue3'] + '</td>' }
            row = row + '</tr>';
            $('#table-team-matches-' + id).append(row);
            $('#table-team-matches2-' + id).append(row);
          }
        },
        dataType: 'json'
      });

      var teamInspections;
      var initialInspector;
      $.ajax({
        url: 'get_team_inspections?event=' + currentEvent + '&team=' + id,
        type: 'POST',
        success: function (data) {
          teamInspections = [];
          teamInspections = data[0];
          initialInspector = data[1];
          for (var index = 0; index < teamInspections.length; index++) {
            var row = '';
            row = '<tr name="' + teamInspections[index][0] + '" class="inspections-row">';
            row = row + '<td>' + teamInspections[index][1] + '</td>';
            row = row + '<td>' + teamInspections[index][2] + '</td>';
            row = row + '<td>' + teamInspections[index][4] + '</td>';
            row = row + '<td>' + teamInspections[index][5] + '</td>';
            row = row + '</tr>';
            $('#inspections-table-' + id).append(row);
            $('#inspections-table2-' + id).append(row);
          console.log('Initial Inspector: ' + initialInspector);
          }
          $('#initial-inspector-' + id).text(initialInspector);
          $('#initial-inspector2-' + id).text(initialInspector);
        },
        dataType: 'json'
      });


      if($('#info-' + id).css('display') == "none" || $('#info-' + id).css('display') == "none"){
        $('.team-info').css('display', 'none');
        $('#info-' + id).css('display', 'table-row');
        $('#tabinfo-' + id).addClass('active');
        $('#tabmatches-' + id).removeClass('active');
        $('#teaminfo-' + id).css('display', 'block');
        $('#teammatches-' + id).css('display', 'none');
        $('#info2-' + id).css('display', 'table-row');
        $('#tabinfo2-' + id).addClass('active');
        $('#tabmatches2-' + id).removeClass('active');
        $('#teaminfo2-' + id).css('display', 'block');
        $('#teammatches2-' + id).css('display', 'none');

      } else {
        $('#info2-' + id).css('display', "none");
        $('#info-' + id).css('display', "none");
      }
    });

      $(document).ready(function() {
        //When View Status buttom is clicked
        $('.btn-inspection-teams').on('click',function(){
          document.getElementById("div-teams-table-with-status").className = "table-responsive";
          document.getElementById("dashboard-view").className = "dashboard-toolbar hidden";
          document.getElementById("scrolling-header").className = "";
          document.getElementById("div-teams-table-without-status").className = "table-responsive hidden";
          $('.btn-inspection-teams').css('display','none');
          $('.btn-inspection-teams-hide').css('display','inline');
        });
        //When Hide Status button is clicked
        $('.btn-inspection-teams-hide').on('click',function(){
          document.getElementById("div-teams-table-with-status").className = "table-responsive hidden";
          document.getElementById("dashboard-view").className = "dashboard-toolbar";
          document.getElementById("scrolling-header").className = "hidden";
          document.getElementById("div-teams-table-without-status").className = "table-responsive";
          $('.btn-inspection-teams').css('display','inline');
          $('.btn-inspection-teams-hide').css('display','none');
        });

        //Adds counts to the inspection legend
        var i = 0;
        while (i < 4) {
          document.getElementsByClassName('key-text count-complete')[i].innerHTML = <?php echo $statusCount['Complete']; ?>;
          document.getElementsByClassName('key-text count-minor')[i].innerHTML = <?php echo $statusCount['Minor Issue']; ?>;
          document.getElementsByClassName('key-text count-major')[i].innerHTML = <?php echo $statusCount['Major Issue']; ?>;
          document.getElementsByClassName('key-text count-notstarted')[i].innerHTML = <?php echo $statusCount['Not Started']; ?>;
          i++;
        }

        //Removes the pit and more icons for each row when scrolling
        function removeIconsByHeader() {
          var headerHeight = document.getElementById('scrolling-header').clientHeight;
          var teamsTable = document.getElementById('teams-table-with-status');
          for (var i = 1, row; row = teamsTable.rows[i]; i = i + 2) {
            var rowPosition = $(row).offset().top - $(window).scrollTop();
            if (rowPosition + 9 < headerHeight) {
              row.cells[3].firstChild.style.visibility = "hidden";
              row.cells[4].firstChild.style.visibility = "hidden";
            }
            else {
              row.cells[3].firstChild.style.visibility = "visible";
              row.cells[4].firstChild.style.visibility = "visible";
            }
          }
        }
        $(window).load(removeIconsByHeader());
        window.onscroll = function() {
          var headerHeight = document.getElementById('scrolling-header').clientHeight;
          var teamsTable = document.getElementById('teams-table-with-status');
          for (var i = 1, row; row = teamsTable.rows[i]; i = i + 2) {
            var rowPosition = $(row).offset().top - $(window).scrollTop();
            if (rowPosition + 9 < headerHeight) {
              row.cells[3].firstChild.style.visibility = "hidden";
              row.cells[4].firstChild.style.visibility = "hidden";
            }
            else {
              row.cells[3].firstChild.style.visibility = "visible";
              row.cells[4].firstChild.style.visibility = "visible";
            }
          }
        }
        

      });
      <?php if (isSuperAdmin($role) || isEventAdmin($role)) { ?>
        <!-- Updates the page based on the session refresh time -->
        var refreshTime = <?php echo $_SESSION['refreshTime'] ?>000;
        var refresh = window.setInterval(update, refreshTime);

          function update() {
          var teamsArr2;
          var statusCount = {};
                $.ajax({
                    url: 'auto_refresh?event=<?php echo $currentEvent; ?>',
                    type: 'POST',
                    success: function(data) {
                        teamsArr2 = [];
                        teamsArr2 = data;
                        console.log('refresh');
                        //console.log(teamsArr2);
                        statusCount = {'Complete': 0, 'Minor Issue': 0, 'Major Issue': 0, 'Not Started': 0};
                        for (var i=0; i < teamsArr2.length; i++){
                            //console.log(teamsArr2[i][0] + ": " + teamsArr2[i][3]);
                            if (teamsArr2[i][3] == 'Complete'){
                                document.getElementById(teamsArr2[i][0]).className = "levelFour";
                                document.getElementById(teamsArr2[i][0] + "-inspectionstatus").innerHTML = "Complete";
                                document.getElementById(teamsArr2[i][0] + "-inspectionstatus2").innerHTML = "Complete";
                                statusCount['Complete'] += 1;
                            }
                            else if (teamsArr2[i][3] == 'Minor Issue'){
                                document.getElementById(teamsArr2[i][0]).className = "levelThree";
                                document.getElementById(teamsArr2[i][0] + "-inspectionstatus").innerHTML = "Minor Issue";
                                document.getElementById(teamsArr2[i][0] + "-inspectionstatus2").innerHTML = "Minor Issue";
                                statusCount['Minor Issue'] += 1;
                            }
                            else if (teamsArr2[i][3] == 'Major Issue'){
                                document.getElementById(teamsArr2[i][0]).className = "levelTwo";
                                document.getElementById(teamsArr2[i][0] + "-inspectionstatus").innerHTML = "Major Issue";
                                document.getElementById(teamsArr2[i][0] + "-inspectionstatus2").innerHTML = "Major Issue";
                                statusCount['Major Issue'] += 1;
                            }
                            else if (teamsArr2[i][3] == 'Not Started'){
                                document.getElementById(teamsArr2[i][0]).className = "levelOne";
                                document.getElementById(teamsArr2[i][0] + "-inspectionstatus").innerHTML = "Not Started";                  
                                document.getElementById(teamsArr2[i][0] + "-inspectionstatus2").innerHTML = "Not Started";                  
                                statusCount['Not Started'] += 1;
                            }
                      }  
          //Adds counts to the inspection legend
          var i = 0;
          while (i < 4) {
            document.getElementsByClassName('key-text count-complete')[i].innerHTML = statusCount['Complete'];
            document.getElementsByClassName('key-text count-minor')[i].innerHTML = statusCount['Minor Issue'];
            document.getElementsByClassName('key-text count-major')[i].innerHTML = statusCount['Major Issue'];
            document.getElementsByClassName('key-text count-notstarted')[i].innerHTML = statusCount['Not Started'];
            i++;
          }
                    },
                    dataType:'json'
                })
            }
        update();
        <?php } ?>
    </script>
    <style>
      .table-team-matches {
        display: table;
        border: 1px solid lightgray;
        padding: 10px;
      }
      
      .table-team-matches-mobile {
        display: none;
      }
      
      @media screen and (max-width: 768px) {
        .table-team-matches {
          display: none;
        }
        
        .table-team-matches-mobile {
          word-wrap: break-word;
          display: table;
        }
      }
    </style>
</div>

<?php } include "footer.php"; ?>