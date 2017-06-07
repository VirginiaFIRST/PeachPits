<?php 
	/*********************
    Allows an event admin to create and edit teams
    **********************/	
	include dirname(__DIR__) . "/header.php";
	 
	if(loggedOn()) {
    	include "menu.php";	
?>
<head><script src="admin/js/manageTeams.js"></script></head>

		<div class="col-md-10 container-dashboard-content">
			<?php if(isEventAdmin($role) || isSuperAdmin($role)){ echo'
				<div class="dashboard-toolbar">
					<div class="container-fluid text-center">
						<button id="addTeam" class="btn btn-default">Add a Team</button>
						<a href="#" id="populate" class="btn btn-default" data-toggle="modal">Auto Fill Teams</a>
					</div>
				</div>
			';} ?>
			<div class="container-add text-center">
				<form class="form-inline" action="admin/add_team.php?event=<?php echo $currentEvent; ?>" method="post">
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
			<div class="dashboard-content">
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<td><b>Team #</b></td>
							<td><b>Team Name</b></td>
							<td><b>Inspection Status</b></td>
							<?php if(isSuperAdmin($role)){ echo '<td></td>';} ?>
						</thead>
						<?php 
							$event = $currentEvent . '_teams';
							$sql = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus FROM `".$event."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
							while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
								echo "<tr id='". $row['teamid'] ."'>";
								echo "<td id='teamid'>". $row['teamid'] ."</td>";
								echo "<td id='teamname'>". $row['teamname'] ."</td>";
								echo "<td id='inspectionstatus'>". $row['inspectionstatus'] ."</td>";
								if(isEventAdmin($role) || isSuperAdmin($role)){ echo "<td><a href='#' class='edit' data-toggle='modal' data-target='#editTeam'>Edit</a>";} //Displays edit link for event admins
							}	
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Edit team popup -->
<div class="modal fade" id="editTeam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Team Info</h4>
      </div>
      <div class="modal-body text-center">
        <form action="admin/edit_team.php?event=<?php echo $currentEvent; ?>" method="post">
            <b>Team Number: </b><p id="teamnumbermodal"></p>
			<input type="text" name="teamname" id="teamnamemodal" class="form-control" placeholder="Team Name"><br/>
			<input type="text" name="schoolname" id="schoolnamemodal" class="form-control" placeholder="School Name"><br/>
			<input type="text" name="location" id="locationmodal" class="form-control" placeholder="Location"><br/>
            
      </div>
      <div class="modal-footer">
        <span class="pull-right"><button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-default" name="submit">Edit</button></span></form>
		<span class="pull-left"><button class="btn btn-default remove-team">Remove Team</button></span>
      </div>
    </div>
  </div>
</div>	
	
<?php 
	} else { echo '<script>document.location.href="signin.php"</script>'; }
	
	include dirname(__DIR__) . "/footer.php"; 
?>