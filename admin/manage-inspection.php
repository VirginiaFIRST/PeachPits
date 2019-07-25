<title>PeachPits - Inspections</title>
<?php 
	/*********************
    Allows an event admin to create and edit teams
    **********************/	
	include dirname(__DIR__) . "/header.php";
	 
	if(loggedOn()) {
    	include "menu.php";	
		$eventTeams = $currentEvent."_teams";
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventname = $row['eventname'];
		
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
?>
<head>
	<script src="admin/js/inspect.js"></script>
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

		<div class="col-md-10 container-dashboard-content">
			<div class="dashboard-toolbar">
				<div class="row text-center">
        <?php if(isLeadInspector($role) || isEventAdmin($role) || isSuperAdmin($role)) { ?>
          <div class="col-xs-12 col-sm-4" style="margin-bottom:10px;">
              <button id="thisbtn" class="btn btn-danger" data-toggle='modal' data-target='#resetInspectionStatus'>Reset Inspection Status</button>
          </div>
          <div class="col-xs-12 col-sm-4" style="margin-bottom:10px;">
            <form action="/peachpits/admin/export_inspections?event=<?php echo $currentEvent; ?>" id="export-form" method="post" style="margin:0;">
              <button class="btn btn-default"><span class="glyphicon glyphicon-save"></span> Download Inspection Data</button>
            </form>
          </div>
          <div class="col-xs-12 col-sm-4" style="margin-bottom:10px;">
            <button class="btn btn-default list-view">List View</button>
            <button class="btn btn-default map-view">Map View</button>
          </div>
        <?php } else { ?>
          <div class="col-xs-12">
            <button class="btn btn-default pull-right list-view">List View</button>
            <button class="btn btn-default pull-right map-view">Map View</button>
          </div>
        <?php } ?>
				</div>
			</div>
            <div class="inspection-map-view">
			    <div class="container-map-centered map-inspection">
				    <div class="container-map-outer"><div id="frame" class="container-map map-page"></div></div>
			    </div>
                <div class="map-page-team">
                    <a id="return-map-inspect" class="return btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Return to Map (Cancel)</a>
                    <div class="team-title">
                        <h3 style="margin-top:0px;"><span class="map-teamnum"></span> <small class="map-teamname"></small></h3>
                    </div>
                    <div class="team-tab">
                        <input type="button" class="tablinks" id="tabinfo" onclick="openTab(event, 'teaminfo')" value="Team Info" style="width:50%;">
                        <input type="button" class="tablinks active" id="tabinspection" onclick="openTab(event, 'teaminspection' )" value="Inspection" style="width:50%;">
                    </div>
                    <div id="teaminfo" class="tabcontent">
                        <h4><b>Location: </b></h4><p class="pull-left map-teamlocation"></p>
                        <div class="clearfix"></div>
                        <h4><b>School Name: </b></h4><p class="map-schoolname" style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 8; -webkit-box-orient: vertical;"></p>
                    </div>
                    <div id="teaminspection" class="tabcontent">
                        <h4><b>Inspection Status: </b></h4><p class="map-inspectstatus text-center"></p><?php if(isInspector($role) || isLeadInspector($role) || isSuperAdmin($role)){ ?>
                        <input type="hidden" name="teamid" id="inspectNumInline">
                        <select name="inspectionstatus" id="inspectionstatus" class="form-control pull-left">
                            <option value="Complete">Complete</option>
                            <option value="Minor Issue">Minor Issue</option>
														<option value="Major Issue">Major Issue</option>
                            <option value="Not Started">Not Started</option>
                        </select>
                        <button type="submit" class="btn btn-default change-status pull-right" name="submit">Change Status</button>

                        <div class="clearfix"></div>
                        <h4><b>Inspection Notes: </b></h4>
                        <textarea class="form-control map-inspectnotes" name="inspectionnotes"></textarea>
                        <button type="submit" class="btn btn-default pull-right save-note" name="submit">Save Note</button>
                        <div class="clearfix"></div>
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
                </div>
            </div>
			<div class="inspection-list-view">
				<div class="dashboard-content">
					<div class="table-responsive">
						<table class="table table-hover" style="border:1px solid #ddd;">
							<thead>
								<td><b>Team #</b></td>
								<td><b>Team Name</b></td>
								<td><b>Inspection Status</b></td>
								<td></td>
								<td><b>Notes</b></td>
								<td></td>
							</thead>
							<?php 
								$sql = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus, e.inspectionnotes FROM `".$eventTeams."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
								while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
									echo "<tr id='". $row['teamid'] ."'>";
									echo "<td id='teamid'>". $row['teamid'] ."</td>";
									echo "<td id='teamname'>". $row['teamname'] ."</td>";
									echo "<td id='inspectionstatus'>". $row['inspectionstatus'] ."</td>";
									echo "<td><a href='#' id='changeStatus' data-toggle='modal' data-target='#changeInspectionStatus'>Change</a></td>";
									echo "<td id='inspectionnotes'>". $row['inspectionnotes'] ."</td>";
									echo "<td><a href='#' id='editNotes' data-toggle='modal' data-target='#editInspectionNotes'>Edit Note</a></td>";
								}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
		
			

<!-- Reset all inspection statuses to Not Started -->
<div class="modal fade" id="resetInspectionStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Reset Inspection Status for ALL Teams?</h4>
      </div>
      <div class="modal-body text-center">
				<h5>This will RESET the inspection status of ALL teams back to "Not Started".</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <button class="btn btn-danger pull-right" data-toggle='modal' data-target='#reset-areyousure' data-dismiss="modal">Reset Inspection Status</button></form>
      </div>
    </div>
  </div>
</div>

<!-- Are you sure? Modal for resetting all inspection statuses to Not Started -->
<div class="modal fade" id="reset-areyousure" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title" id="myModalLabel">Are you sure?</h2>
      </div>
      <div class="modal-body text-center">
        <h3>This will RESET the inspection status of ALL teams back to "Not Started".</h3>
				<form action="/peachpits/admin/inspection_status?event=<?php echo $currentEvent; ?>&refer=manageinspect&type=resetstatus" method="post">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" id="btn-not-sure" data-dismiss="modal">No, I'm not sure</button>
        <button type="submit" class="btn btn-danger pull-right" id="btn-confirm" name="submit">Yes, Reset Inspection Status</button></form>
      </div>
    </div>
  </div>
</div>

<!-- Change inspection status popup -->
<div class="modal fade" id="changeInspectionStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 style="display:inline" class="modal-title" id="myModalLabel">Change Inspection Status for Team</h4>
				<h4 style="display:inline" class="modal-title" name="teamid" id="getteamid">#</h4>
      </div>
      <div class="modal-body text-center">
        <form action="/peachpits/admin/inspection_status?event=<?php echo $currentEvent; ?>&refer=manageinspect&type=changestatus" method="post">
			<input type="hidden" name="teamid" id="inspectnumbermodal">
            <select name="inspectionstatus" id="inspectionstatus" class="form-control">
				<option>Complete</option>
				<option>Minor Issue</option>
				<option>Major Issue</option>
				<option>Not Started</option>
			</select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-default" name="submit">Change</button></form>
      </div>
    </div>
  </div>
</div>

<!-- Change inspection notes popup -->
<div class="modal fade" id="editInspectionNotes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 style="display:inline" class="modal-title" id="myModalLabel">Edit Inspection Notes for Team </h4>
				<h4 style="display:inline" class="modal-title" name="teamid" id="getteamid2">#</h4>
      </div>
      <div class="modal-body text-center">
        <form action="/peachpits/admin/inspection_status?event=<?php echo $currentEvent; ?>&refer=manageinspect&type=addnote" method="post">
			<input type="hidden" name="teamid" id="inspectnumbermodal-notes">
			<textarea class="form-control map-inspectnotes-modal" style="width:100%; height:100px; margin-bottom:5px; resize:none;" name="inspectionnotes"></textarea><br/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-default" name="submit">Save Note</button></form>
      </div>
    </div>
  </div>
</div>	
<script>
	if (window.innerWidth <= 430 && document.getElementById('btn-not-sure').innerText != 'No, Cancel') {
		document.getElementById('btn-not-sure').innerText = 'No, Cancel';
		document.getElementById('btn-confirm').innerText = 'Yes, Reset';
	}
	$(window).resize(function () {
		if (window.innerWidth <= 430 && document.getElementById('btn-not-sure').innerText != 'No, Cancel') {
			document.getElementById('btn-not-sure').innerText = 'No, Cancel';
			document.getElementById('btn-confirm').innerText = 'Yes, Reset';
		}
		else if (window.innerWidth > 430 && document.getElementById('btn-not-sure').innerText == 'No, Cancel') {
			document.getElementById('btn-not-sure').innerText = 'No I\'m not sure';
			document.getElementById('btn-confirm').innerText = 'Yes, Reset Inspection Status';
		}
	})
</script>
<?php 
	} else { echo '<script>document.location.href="signin"</script>'; }
	
	include dirname(__DIR__) . "/footer.php"; 
?>