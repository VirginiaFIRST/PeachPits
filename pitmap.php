<title>PeachPits - Pitmap</title>
<?php 
	include "header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="chooseevent"</script>';
	}
	else {
		$eventTeams = $currentEvent."_teams";
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventName = $row['eventname'];
        $teamid = $row['teamid'];
		
		$eventMatches = $currentEvent."_matches";
		$eventInspections = $currentEvent."_inspections";
		$sqlMatches = $mysqli->query("SELECT * FROM `".$eventMatches."` WHERE `matchtype` LIKE 'qm' ORDER BY matchnumber ASC");
		
		$sql = $mysqli->query("SELECT * FROM `maps` WHERE `eventid` LIKE '$currentEvent'");
		$row = mysqli_fetch_assoc($sql);
		
		$red1 = $_GET['r1'];
		$red2 = $_GET['r2'];
		$red3 = $_GET['r3'];
		$blue1 = $_GET['b1'];
		$blue2 = $_GET['b2'];
		$blue3 = $_GET['b3'];
		$team = $_GET['team'];

		$statusCount = ['Complete'=> 0, 'Minor Issue'=> 0, 'Major Issue'=> 0, 'Weighed and Sized'=> 0, 'Ok to unbag'=> 0, 'Not Started'=> 0];
		$sqlInspections = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus FROM `".$eventTeams."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
		while($rowInspections = mysqli_fetch_array($sqlInspections, MYSQLI_BOTH)){
			if ($rowInspections['inspectionstatus'] == 'Complete'){
				$statusCount['Complete'] += 1;
			}
			elseif ($rowInspections['inspectionstatus'] == 'Minor Issue'){
				$statusCount['Minor Issue'] += 1;
			}
			elseif ($rowInspections['inspectionstatus'] == 'Major Issue'){
				$statusCount['Major Issue'] += 1;
			}
			elseif ($rowInspections['inspectionstatus'] == 'Weighed and Sized'){
				$statusCount['Weighed and Sized'] += 1;
			}
			elseif ($rowInspections['inspectionstatus'] == 'Ok to unbag'){
				$statusCount['Ok to unbag'] += 1;
			}
			elseif ($rowInspections['inspectionstatus'] == 'Not Started'){
				$statusCount['Not Started'] += 1;
			}
		}
?>
<head>
	<script>
		var teamsArr;
		var r1 = "#<?php echo $red1; ?>";
		var r2 = "#<?php echo $red2; ?>";
		var r3 = "#<?php echo $red3; ?>";
		var b1 = "#<?php echo $blue1; ?>";
		var b2 = "#<?php echo $blue2; ?>";
		var b3 = "#<?php echo $blue3; ?>";
		var team = "#<?php echo $team; ?>";		
		<?php 
			$i = 0;
			$inspectStatuses;
			
			$sqlTeams = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus, e.inspectionnotes, e.initial_inspector, e.last_modified_by, e.last_modified_time FROM `".$eventTeams."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
			while($rowTeams = mysqli_fetch_array($sqlTeams, MYSQLI_BOTH)){
				$inspectStatuses[$i][0] = $rowTeams['teamid']; 
				$inspectStatuses[$i][1] = $rowTeams['teamname']; 
				$inspectStatuses[$i][2] = $rowTeams['location']; 
				$inspectStatuses[$i][3] = $rowTeams['inspectionstatus']; 
				$inspectStatuses[$i][4] = $rowTeams['inspectionnotes']; 
				$inspectStatuses[$i][5] = $rowTeams['initial_inspector']; 
				$inspectStatuses[$i][6] = $rowTeams['last_modified_by']; 
				$inspectStatuses[$i][7] = $rowTeams['last_modified_time']; 
                $inspectStatuses[$i][8] = $rowTeams['schoolname'];
				$i = $i + 1;
			}
			$jsArr = json_encode($inspectStatuses);
			echo "var teamsArr = ". $jsArr . ";\n";	
      
		?>	
		var mapCode = '<?php echo $row['mapcode']; ?>';
		var frameHeight = '<?php echo $row['height']; ?>';
		var frameWidth = '<?php echo $row['width']; ?>';
	</script>
	<script src="js/map.js"></script>
</head>
<div class="page-head" style="margin-bottom:0px;">
	<div class="container">
        <div class="row">
            <div class="col-md-9"><h1><?php echo $eventName; ?> Pit Map</h1></div>
            <div class="col-md-3">
                <a href="display?event=<?php echo $currentEvent; ?>" class="btn btn-default btn-display display-hd" data-toggle="tooltip" title="Updates status automatically every 15 seconds">Display Mode</a>
                <a href="display?event=<?php echo $currentEvent; ?>" class="btn btn-default btn-xs btn-display display-vs" data-toggle="tooltip" title="Updates status automatically every 15 seconds">Display Mode</a>
            </div>
        </div>
	</div>
</div>
<div class="pitmap-btn-container text-center">
	<div class="container">
		<div class="row">
			<div class="col-xs-3 btn-back"></div>
			<div class="col-xs-6">
				<div class="dropdown dropdown-teams">
					<button class="btn btn-default dropdown-toggle btn-st btn-pitmap" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Select a Team <span class="caret"></span>
					</button>
					<ul class="dropdown-menu pull-center dropdown-scrollable" aria-labelledby="dropdownMenu1">
					<?php 
						$sql = $mysqli->query("SELECT * FROM `".$eventTeams."`");
						while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
							echo '<li id="team' . $row['teamid'] . '" class="select-teams"><a href="#">' . $row['teamid'] . '</a></li>';
						}	 
					?>
					</ul>
				</div>				
				<div class="dropdown dropdown-matches">
					<button class="btn btn-default dropdown-toggle btn-m btn-pitmap" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Select a Match <span class="caret"></span>
					</button>
					<ul class="dropdown-menu pull-center dropdown-scrollable" aria-labelledby="dropdownMenu1">
					<?php 
						while($row = mysqli_fetch_array($sqlMatches, MYSQLI_BOTH)){
							echo '<li id="-'.$row['red1'].'-'.$row['red2'].'-'.$row['red3'].'-'.$row['blue1'].'-'.$row['blue2'].'-'.$row['blue3'].'-" class="select-matches"><a href="#" class="a-sm">#' . $row['matchnumber'] . '<span class="sm-vs">:</span> <span class="sm-red">'.$row['red1'].'|'.$row['red2'].'|'.$row['red3'].'</span> <span class="sm-vs">vs.</span> <span class="sm-blue">'.$row['blue1'].'|'.$row['blue2'].'|'.$row['blue3'].'</span></a></li>';
						}	 
                    ?>	
					</ul>
				</div>
			</div>
			<div class="col-xs-3">
				<button class="btn btn-default btn-inspection pull-right btn-pitmap" style="margin-left:10px">View Status</button>
				<button class="btn btn-default btn-inspection-hide pull-right btn-pitmap" style="margin-left:10px">Hide Status</button>
				<button class="btn btn-default btn-inspection-header-show pull-right">Show Legend</button>
				<button style="display:none" class="btn btn-default btn-inspection-header-hide pull-right">Hide Legend</button>
			</div>
		</div>
	</div>
</div>
<div id="all-inspection-headers" style="display:none">
    <!--Inspection Legend size 1: desktop-->
    <div class="dashboard-toolbar" id="inspection-legend-header1">
        <div class="container-fluid text-center">
          <span style="font-weight:bold;font-size:16px">
            <span style="white-space:nowrap"><div class="keyColor levelSixKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Complete (</div><div class="key-text count-complete" style="display:inline" id="count-complete"></div><div class="key-text" style="display:inline">)</div></span>
            <span style="white-space:nowrap"><div class="keyColor levelFiveKey" style="float:none;display:inline-block;vertical-align:middle;margin-left:20px"></div><div class="key-text" style="display:inline">Minor Issue (</div><div class="key-text count-minor" style="display:inline" id="count-minor"></div><div class="key-text" style="display:inline">)</div></span>
            <span style="white-space:nowrap"><div class="keyColor levelFourKey" style="float:none;display:inline-block;vertical-align:middle;margin-left:20px"></div><div class="key-text" style="display:inline">Major Issue (</div><div class="key-text count-major" style="display:inline" id="count-major"></div><div class="key-text" style="display:inline">)</div></span>
            <span style="white-space:nowrap"><div class="keyColor levelThreeKey" style="float:none;display:inline-block;vertical-align:middle;margin-left:20px"></div><div class="key-text" style="display:inline">Weighed and Sized (</div><div class="key-text count-weighed" style="display:inline" id="count-weighed"></div><div class="key-text" style="display:inline">)</div></span>
            <span style="white-space:nowrap"><div class="keyColor levelTwoKey" style="float:none;display:inline-block;vertical-align:middle;margin-left:20px"></div><div class="key-text" style="display:inline">Ok to unbag (</div><div class="key-text count-ok" style="display:inline" id="count-ok"></div><div class="key-text" style="display:inline">)</div></span>
            <span style="white-space:nowrap"><div class="keyColor levelOneKey" style="float:none;display:inline-block;vertical-align:middle;margin-left:20px"></div><div class="key-text" style="display:inline">Not Started (</div><div class="key-text count-notstarted" style="display:inline" id="count-notstarted"></div><div class="key-text" style="display:inline">)</div></span>
          </span>
        </div>
    </div>
    <!--Inspection Legend size 2: small monitors/tablets-->
    <div class="dashboard-toolbar" id="inspection-legend-header2">
        <div class="container-fluid text-center">
          <span style="font-weight:bold;font-size:16px">
            <div style="float:left;width:33.33%">
              <div style="margin-left:auto;margin-right:auto;width:167px">
                <span style="white-space:nowrap;float:left"><div class="keyColor levelSixKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Complete (</div><div class="key-text count-complete" style="display:inline" id="count-complete"></div><div class="key-text" style="display:inline">)</div></span>
                <span style="white-space:nowrap;float:left"><div class="keyColor levelFiveKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Minor Issue (</div><div class="key-text count-minor" style="display:inline" id="count-minor"></div><div class="key-text" style="display:inline">)</div></span>
              </div>
            </div>
            <div style="float:left;width:33.33%">
              <div style="margin-left:auto;margin-right:auto;width:221px">
                <span style="white-space:nowrap;float:left"><div class="keyColor levelFourKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Major Issue (</div><div class="key-text count-major" style="display:inline" id="count-major"></div><div class="key-text" style="display:inline">)</div></span>
                <span style="white-space:nowrap;float:left"><div class="keyColor levelThreeKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Weighed and Sized (</div><div class="key-text count-weighed" style="display:inline" id="count-weighed"></div><div class="key-text" style="display:inline">)</div></span>
              </div>
            </div>
            <div style="float:right;width:33.33%">
              <div style="margin-left:auto;margin-right:auto;width:168px">
                <span style="white-space:nowrap;float:left"><div class="keyColor levelTwoKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Ok to unbag (</div><div class="key-text count-ok" style="display:inline" id="count-ok"></div><div class="key-text" style="display:inline">)</div></span>
                <span style="white-space:nowrap;float:left"><div class="keyColor levelOneKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Not Started (</div><div class="key-text count-notstarted" style="display:inline" id="count-notstarted"></div><div class="key-text" style="display:inline">)</div></span>
              </div>
            </div>
          </span>
        </div>
    </div>
    <!--Inspection Legend size 3: tablets-->
    <div class="dashboard-toolbar" id="inspection-legend-header3">
        <div class="container-fluid text-center">
          <span style="font-weight:bold;font-size:16px">
            <div style="float:left;width:50%">
              <div style="margin-left:auto;margin-right:auto;width:167px">
                <span style="white-space:nowrap;float:left"><div class="keyColor levelSixKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Complete (</div><div class="key-text count-complete" style="display:inline" id="count-complete"></div><div class="key-text" style="display:inline">)</div></span>
                <span style="white-space:nowrap;float:left"><div class="keyColor levelFiveKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Minor Issue (</div><div class="key-text count-minor" style="display:inline" id="count-minor"></div><div class="key-text" style="display:inline">)</div></span>
                <span style="white-space:nowrap;float:left"><div class="keyColor levelFourKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Major Issue (</div><div class="key-text count-major" style="display:inline" id="count-major"></div><div class="key-text" style="display:inline">)</div></span>
              </div>
            </div>
            <div style="float:right;width:50%">
              <div style="margin-left:auto;margin-right:auto;width:221px">
                <span style="white-space:nowrap;float:left;margin-right:20px"><div class="keyColor levelThreeKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Weighed and Sized (</div><div class="key-text count-weighed" style="display:inline" id="count-weighed"></div><div class="key-text" style="display:inline">)</div></span>
                <span style="white-space:nowrap;float:left;margin-right:20px"><div class="keyColor levelTwoKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Ok to unbag (</div><div class="key-text count-ok" style="display:inline" id="count-ok"></div><div class="key-text" style="display:inline">)</div></span>
                <span style="white-space:nowrap;float:left;margin-right:20px"><div class="keyColor levelOneKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Not Started (</div><div class="key-text count-notstarted" style="display:inline" id="count-notstarted"></div><div class="key-text" style="display:inline">)</div></span>
              </div>
            </div>
          </span>
        </div>
    </div>
    <!--Inspection Legend size 4: mobile phones-->
    <div class="dashboard-toolbar" id="inspection-legend-header4">
        <div class="container-fluid text-center">
          <div style="margin-left:auto;margin-right:auto;width:220px">
            <span style="font-weight:bold;font-size:13px">
              <span style="float:left"><div class="keyColor levelSixKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Complete (</div><div class="key-text count-complete" style="display:inline" id="count-complete"></div><div class="key-text" style="display:inline">)</div></span>
              <span style="float:left"><div class="keyColor levelFiveKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Minor Issue (</div><div class="key-text count-minor" style="display:inline" id="count-minor"></div><div class="key-text" style="display:inline">)</div></span>
              <span style="float:left"><div class="keyColor levelFourKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Major Issue (</div><div class="key-text count-major" style="display:inline" id="count-major"></div><div class="key-text" style="display:inline">)</div></span>
              <span style="float:left"><div class="keyColor levelThreeKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Weighed and Sized (</div><div class="key-text count-weighed" style="display:inline" id="count-weighed"></div><div class="key-text" style="display:inline">)</div></span>
              <span style="float:left"><div class="keyColor levelTwoKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Ok to unbag (</div><div class="key-text count-ok" style="display:inline" id="count-ok"></div><div class="key-text" style="display:inline">)</div></span>
              <span style="float:left"><div class="keyColor levelOneKey" style="float:none;display:inline-block;vertical-align:middle"></div><div class="key-text" style="display:inline">Not Started (</div><div class="key-text count-notstarted" style="display:inline" id="count-notstarted"></div><div class="key-text" style="display:inline">)</div></span>
            </span>
          </div>
        </div>
    </div>
</div>
<div class="status-text-container">
	<div class="text-center">
		<p class="status-text">Viewing Inspection Status</p>
	</div>
</div>
<div class="container" style="padding:0px !important;">
	<div class="container-map-centered map-main">
		<div class="container-map-outer"><div id="frame" class="container-map map-page"></div></div>     
	</div>
    <div class="map-page-team">
        <a class="return btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Return to Map</a>
        <div class="team-title">
            <h3 style="margin-top:0px;"><span class="map-teamnum"></span> <small class="map-teamname"></small></h3>
        </div>
        <div class="team-tab">
            <input type="button" class="tablinks" id="tabinfo" onclick="openTab(event, 'teaminfo')" value="Team Info">
            <input type="button" class="tablinks active" id="tabinspection" onclick="openTab(event, 'teaminspection' )" value="Inspection">
            <input type="button" class="tablinks" id="tabmatches" onclick="openTab(event, 'teammatches')" value="Matches">
        </div>
        <div id="teaminfo" class="tabcontent">
            <h4><b>Location: </b></h4><p class="pull-left map-teamlocation"></p>
            <div class="clearfix"></div>
            <h4><b>School Name: </b></h4><p class="map-schoolname" style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 8; -webkit-box-orient: vertical;"></p>
        </div>
        <div id="teaminspection" class="tabcontent">
            <h4><b>Inspection Status: </b></h4><p class="map-inspectstatus text-center"></p>
            <?php if(isInspector($role) || isLeadInspector($role) || isEventAdmin($role) || isSuperAdmin($role)){ ?>
                <form action="admin/inspection_status?event=<?php echo $currentEvent; ?>&refer=pitmap&type=changestatus" method="post">
				<input type="hidden" name="teamid" id="inspectNumInline">
                <select name="inspectionstatus" id="inspectionstatus" class="form-control pull-left">
                    <option value="Complete">Complete</option>
                    <option value="Minor Issue">Minor Issue</option>
					<option value="Major Issue">Major Issue</option>
					<option value="Weighed and Sized">Weighed and Sized</option>
                    <option value="Ok to unbag">Ok to unbag</option>
                    <option value="Not Started">Not Started</option>
                </select>
                <button type="submit" class="btn btn-default pull-right" name="submit">Change Status</button></form>

                <div class="clearfix"></div>
                <h4><b>Inspection Notes: </b></h4>
                <textarea class="form-control map-inspectnotes" name="inspectionnotes"></textarea>
                <button type="submit" class="btn btn-default pull-right save-note" name="submit">Save Note</button>
                <div class="clearfix"></div>
                <h4><b>Inspection Status Changes: </b></h4>
				<div class="table-responsive">
				<table style="border: 1px solid #ddd" class="table table-hover" id="inspections-table">
					<thead style="background-color:white;border-top:none">
						<th><strong>Inspection Status</strong></th>
						<th><strong>Inspection Notes</strong></th>
						<th><strong>Modified By</strong></th>
						<th><strong>Modified Time</strong></th>
					</thead>
					<tbody>
						<?php 
							//Fetches all teams in order from the database
							$sql = $mysqli->query("SELECT * FROM `".$eventInspections."` ORDER BY `modified_time` ASC");	
							while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
								echo "<tr name='".$row['teamid']."' class='inspections-row'>";
								echo "<td id='inspectionstatus'>". $row['inspectionstatus'] ."</td>";
								echo "<td id='inspectionnotes'>". $row['inspectionnotes'] ."</td>";
								echo "<td>". $row['modified_by'] ."</td>";
								echo "<td>". $row['modified_time'] ."</td>";
								echo "</tr>";
							}				
						?>
					</tbody>
				</table>
				</div>

                <h4><b>Initial Inspector: </b></h4><p class="map-initialinspector"></p>
            <?php } ?>
        </div>
        <div id="teammatches" class="tabcontent">
            <table id="table-team-matches" class="table table-hover" style="margin-top:20px;">
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
            <table id="table-team-matches-mobile" class="table">
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
    </div>
    
</div>
<script>
	//Adds counts to the inspection legend
    var i = 0;
    while (i < 4) {
      document.getElementsByClassName('key-text count-complete')[i].innerHTML = <?php echo $statusCount['Complete']; ?>;
      document.getElementsByClassName('key-text count-minor')[i].innerHTML = <?php echo $statusCount['Minor Issue']; ?>;
      document.getElementsByClassName('key-text count-major')[i].innerHTML = <?php echo $statusCount['Major Issue']; ?>;
      document.getElementsByClassName('key-text count-weighed')[i].innerHTML = <?php echo $statusCount['Weighed and Sized']; ?>;
      document.getElementsByClassName('key-text count-ok')[i].innerHTML = <?php echo $statusCount['Ok to unbag']; ?>;
      document.getElementsByClassName('key-text count-notstarted')[i].innerHTML = <?php echo $statusCount['Not Started']; ?>;
      i++;
    }
</script>
<?php } include "footer.php"; ?>