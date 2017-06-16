<?php 
	/*********************
    Allows an event admin to create and edit teams
    **********************/	
	include dirname(__DIR__) . "/header.php";
	 
	if(loggedOn()) {
    	include "menu.php";	
		$event = $currentEvent."_teams";
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventname = $row['eventname'];
		
		$eventMatches = $currentEvent."_matches";
		$sqlMatches = $mysqli->query("SELECT * FROM `".$eventMatches."` WHERE `matchtype` LIKE 'qm' ORDER BY matchnumber ASC");
		
		$sql = $mysqli->query("SELECT `mapcode` FROM `maps` WHERE `eventid` LIKE '$currentEvent'");
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
			$sqlTeams = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus, e.inspectionnotes, e.initial_inspector, e.last_modified_by, e.last_modified_time FROM `".$event."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
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
				<div class="container-fluid">
					<button class="btn btn-default list-view pull-right">List View</button>
					<button class="btn btn-default map-view pull-right">Map View</button>
				</div>
			</div>
			<div class="container-map-centered map-inspection">
				<div class="container-map-outer"><div id="frame" class="container-map map-page"></div></div>
				<div class="map-page-team">
					<div class="return"><span class="glyphicon glyphicon-chevron-left"></span> Return to Map (Cancel)</div>
					<div class="team-title">
						<h3 style="margin-top:0px;"><span class="map-teamnum"></span> <small class="map-teamname"></small></h3>
					</div>
					<div class="team-info">
						<p class="pull-left map-teamlocation"></p><p class="pull-right"><a class="btn btn-default btn-xs map-moreinfo" href="">More Info</a></p>
						<div class="clearfix"></div>
						<h4><b>Inspection Status: </b></h4><p class="map-inspectstatus text-center"></p>
							<input type="hidden" name="teamid" id="inspectNumInline">
							<select name="inspectionstatus" id="inspectionstatus" class="form-control pull-left">
								<option value="Complete">Complete</option>
								<option value="Major Issue">Major Issue</option>
								<option value="Minor Issue">Minor Issue</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Weighed and Sized">Weighed and Sized</option>
								<option value="Ok to unbag">Ok to unbag</option>
								<option value="Not Started">Not Started</option>
							</select>
							<button type="submit" class="btn btn-default pull-right change-status" name="submit">Change Status</button>
						<div class="clearfix"></div>
						<h4><b>Inspection Notes: </b></h4>
							<textarea class="form-control map-inspectnotes" name="inspectionnotes"></textarea>
							<button type="submit" class="btn btn-default pull-right save-note" name="submit">Save Note</button>
						<div class="clearfix"></div>
						<h4><b>Initial Inspector: </b></h4><p class="map-initialinspector"></p>
						<h4><b>Last Modified By: </b></h4><p class="map-inspectmodifiedby"></p>
						<h4><b>Last Modified Time: </b></h4><p class="map-inspectmodifiedtime"></p>
					</div>
				</div>
			</div>
			<div class="inspection-list-view">
				<div class="dashboard-content">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<td><b>Team #</b></td>
								<td><b>Team Name</b></td>
								<td><b>Inspection Status</b></td>
								<td></td>
								<td><b>Notes</b></td>
								<td></td>
							</thead>
							<?php 
								$sql = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus, e.inspectionnotes FROM `".$event."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
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

<!-- Change inspection status popup -->
<div class="modal fade" id="changeInspectionStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Change Inspection Status</h4>
      </div>
      <div class="modal-body text-center">
        <form action="admin/inspection_status.php?event=<?php echo $currentEvent; ?>&refer=manageinspect&type=changestatus" method="post">
			<input type="hidden" name="teamid" id="inspectnumbermodal">
            <select name="inspectionstatus" id="inspectionstatus" class="form-control">
				<option>Complete</option>
				<option>Major Issue</option>
				<option>Minor Issue</option>
                <option>In Progress</option>
                <option>Weighed and Sized</option>
				<option>Ok to unbag</option>
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
        <h4 class="modal-title" id="myModalLabel">Edit Inspection Notes</h4>
      </div>
      <div class="modal-body text-center">
        <form action="admin/inspection_status.php?event=<?php echo $currentEvent; ?>&refer=manageinspect&type=addnote" method="post">
			<input type="hidden" name="teamid" id="inspectnumbermodal-notes">
			<textarea class="form-control map-inspectnotes-modal" style="width:100%; height:100px; margin-bottom:5px;" name="inspectionnotes"></textarea><br/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-default" name="submit">Save Note</button></form>
      </div>
    </div>
  </div>
</div>	

<?php 
	} else { echo '<script>document.location.href="signin.php"</script>'; }
	
	include dirname(__DIR__) . "/footer.php"; 
?>