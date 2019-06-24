<title>PeachPits - Teams</title>
<?php 
	/*********************
    Allows an event admin to create and edit teams
    **********************/	
	include dirname(__DIR__) . "/header.php";
	 
	if(loggedOn()) {
    	include "menu.php";	
?>
<head>
	<script src="admin/js/manageTeams.js"></script>
	<script src="js/selectize.js"></script>
	<link rel="stylesheet" type="text/css" href="css/selectize.css" />
</head>

		<div class="col-md-10 container-dashboard-content">
			<?php if (isPitAdmin($role) || isEventAdmin($role) || isSuperAdmin($role)) { ?>
				<div class="dashboard-toolbar">
					<div class="container-fluid text-center">
						<button id="addTeam" class="btn btn-default">Add a Team</button>
						<button id="add-many-teams" class="btn btn-default">Add Many Teams</button>
						<a href="#" id="populate" class="btn btn-default" data-toggle="modal">Auto Fill Teams</a>
					</div>
				</div>
			<?php } ?>
			<div class="container-add text-center">
				<form class="form-inline" action="/peachpits/admin/add_team?event=<?php echo $currentEvent; ?>" method="post">
					<input type="text" name="teamid" id="teamid" class="form-control" placeholder="Team Number">
					<a id="lookup-team" href="#" class="btn btn-default" style="display:inline;">Lookup</a>
					<input type="text" name="teamname" id="teamname" class="form-control" placeholder="Team Name">
					<input type="text" name="schoolname" id="schoolname" class="form-control" placeholder="School Name">
					<input type="text" name="location" id="location" class="form-control" placeholder="Location">
					<input type="hidden" name="eventList" value="true">
					<input type="hidden" name="auto" value="false">
					<input type="hidden" name="eventid" value="<?php echo $currentEvent; ?>">
					<button type="submit" class="btn btn-default" name="submit">Add</button>
					<a id="addTeam_cancel" href="#" class="btn btn-default btn-add-cancel">Cancel</a>
				</form>
			</div>
			<div class="container-add-many text-center">
				<form class="form-inline" action="/peachpits/admin/add_team?event=<?php echo $currentEvent; ?>" method="post">
					<select id="select-teams" name="teams[]" multiple>
							<?php
									$sql = $mysqli->query("SELECT * FROM `teams`");
									while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
											echo '<option value="'.$row['teamid'].'">'.$row['teamid'].'</option>';
									}
							?>
					</select>
					<input type="hidden" name="eventList" value="true">
					<input type="hidden" name="auto" value="false">
					<input type="hidden" name="eventid" value="<?php echo $currentEvent; ?>">
					<button type="button" class="btn btn-default" name="submit" id="submit-many-btn" disabled="disabled">Add</button>
					<a id="add-many-dismiss" href="#" class="btn btn-default btn-add-cancel">Cancel</a>
				</form>
			</div>
			<div class="dashboard-content">
				<div class="table-responsive">
					<table class="table table-hover" style="border:1px solid #ddd;">
						<thead>
							<td><b>Team #</b></td>
							<td><b>Team Name</b></td>
							<td><b>Inspection Status</b></td>
							<?php if(isSuperAdmin($role)){ echo '<td></td>';} ?>
						</thead>
						<?php 
							$eventTeams = $currentEvent . '_teams';
							$sql = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus FROM `".$eventTeams."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
							while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
								echo "<tr id='". $row['teamid'] ."'>";
								echo "<td id='teamid'>". $row['teamid'] ."</td>";
								echo "<td id='teamname'>". $row['teamname'] ."</td>";
								echo "<td class='hidden' id='schoolname'>". $row['schoolname'] ."</td>";
								echo "<td class='hidden' id='location'>". $row['location'] ."</td>";
								echo "<td id='inspectionstatus'>". $row['inspectionstatus'] ."</td>";
								if(isPitAdmin($role) || isEventAdmin($role) || isSuperAdmin($role)){ echo "<td><a href='#' class='edit' data-toggle='modal' data-target='#editTeam'>Edit</a>";} //Displays edit link for event admins
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$('#select-teams').selectize({
        maxItems: null,
        placeholder: 'Choose Teams',
        onChange: function(value) {
            if (value == null) {
                $('#submit-many-btn').attr('disabled', 'disabled');
            }
            else {
                $('#submit-many-btn').removeAttr('disabled');
            }
        }
    });
</script>

<!-- Edit team popup -->
<div class="modal fade" id="editTeam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Team Info</h4>
      </div>
      <div class="modal-body text-center">
        <form action="/peachpits/admin/edit_team?event=<?php echo $currentEvent; ?>" method="post">
            <b>Team Number: </b><p id="teamnumbermodal"></p>
			<input type="hidden" name="teamid" id="teamnumbermodal2">
			<p class="pull-left" style="margin-bottom:0px">Team Name:</p>
			<input type="text" name="teamname" id="teamnamemodal" class="form-control" placeholder="Team Name"><br/>
			<p class="pull-left" style="margin-bottom:0px">School Name:</p>
			<input type="text" name="schoolname" id="schoolnamemodal" class="form-control" placeholder="School Name"><br/>
			<p class="pull-left" style="margin-bottom:0px">Location:</p>
			<input type="text" name="location" id="locationmodal" class="form-control" placeholder="Location"><br/>
            
      </div>
      <div class="modal-footer">
        <span class="pull-right"><button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-default" name="submit">Edit</button></span></form>
				<span class="pull-left"><button class="btn btn-danger remove-team">Remove Team</button></span>
      </div>
    </div>
  </div>
</div>

<!-- Processing Request Popup -->
<div class="modal fade" id="processing-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="processing-modal-title">Processing...</h3>
      </div>
      <div class="modal-body text-center">
        <h4 id="modal-body-text">Please do not leave or refresh the page while the teams are added...</h4>
		<hr>
		<h4>Progress:</h4>
		<h4><span id="current-teams">0</span>/<span id="total-teams">0</span></h4>
		<div class="progress center-block" style="width:75%;height:25px">
			<div class="progress-bar progress-bar-striped progress-bar-warning active" role="progressbar" id="progressbar" style="width:0%" aria-valuenow="0" ariavalue-min="0" ariavalue-max="100"></div>
		</div>
      </div>
	  <div class="modal-footer hidden" id="processing-modal-footer">
        <button class="btn btn-default pull-right" onclick="location.reload()">Refresh</button>
      </div>
    </div>
  </div>
</div>

<?php 
	} else { echo '<script>document.location.href="signin"</script>'; }
	
	include dirname(__DIR__) . "/footer.php"; 
?>