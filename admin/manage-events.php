<title>PeachPits - Events</title>
<?php 
	/*********************
    Allows event admins to create and edit matches
    **********************/
	include dirname(__DIR__) . "/header.php";
	 
	if(loggedOn()) {
    	include "menu.php";
?>
<head>
	<script>
		var eventsArr;
		<?php 
			$i = 0;
			$eventData;
			
			$sqlEvents = $mysqli->query("SELECT * FROM `events`");
			while($rowEvents = mysqli_fetch_array($sqlEvents, MYSQLI_BOTH)){
				$eventData[$i][0] = $rowEvents['eventid']; 
				$eventData[$i][1] = $rowEvents['eventname']; 
				$eventData[$i][2] = $rowEvents['eventstatus']; 
				$eventData[$i][3] = $rowEvents['eventdistrict']; 
				$eventData[$i][4] = $rowEvents['eventlocation']; 
				$eventData[$i][5] = $rowEvents['eventaddress']; 
				$eventData[$i][6] = $rowEvents['eventstart'];
				$eventData[$i][7] = $rowEvents['eventend'];  
				$eventData[$i][8] = $rowEvents['eventyear']; 
				$eventData[$i][9] = $rowEvents['eventtype']; 
				$i = $i + 1;
			}
			$jsArr = json_encode($eventData);
			echo "var eventsArr = ". $jsArr . ";\n";				
        ?>

	</script>
	<script src="admin/js/manageEvents.js"></script>
</head>

		<div class="col-md-10 container-dashboard-content event-list">
			<?php if(isSuperAdmin($role)){ ?>
			<div class="dashboard-toolbar">
				<div class="container-fluid text-center">
					<button id="addEvent" class="btn btn-default">Add a Event</button>
					<a href="#" id="populate" class="btn btn-default" data-toggle="modal">Auto Fill Events</a>
				</div>
			</div>
			<div class="container-add text-center">
				<form class="form-inline" action="admin/add_event?event=<?php echo $currentEvent; ?>" method="post">
					<input type="text" name="eventid" id="eventid" class="form-control" style="width:100px;" placeholder="Event Id">
					<input type="text" name="eventname" id="teamname" class="form-control" style="width:160px;" placeholder="Event Name">
					<input type="text" name="eventdistrict" id="eventdistrict" class="form-control" style="width:120px;" placeholder="District">
					<input type="text" name="eventlocation" id="eventlocation" class="form-control" style="width:120px;" placeholder="Location">
					<input type="text" name="eventaddress" id="eventaddress" class="form-control" style="width:120px;" placeholder="Address">
					<input type="text" name="eventstart" id="eventstart" class="form-control" style="width:100px;" placeholder="Start">
					<input type="text" name="eventend" id="eventend" class="form-control" style="width:100px;" placeholder="End">
					<input type="text" name="eventyear" id="eventyear" class="form-control" style="width:80px;" placeholder="Year">
					<input type="text" name="eventtype" id="eventtype" class="form-control" style="width:100px;" placeholder="Type">
					<input type="hidden" name="auto" value="false">
					<button type="submit" class="btn btn-default" name="submit">Add</button>
					<a id="addEvent_cancel" href="#" class="btn btn-default btn-add-cancel">Cancel</a>
				</form>
			</div>
			<div class="dashboard-content">
				<h2>Live Events</h2>
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<td><strong>Event Id</strong></td>
							<td><strong>Name</strong></td>
							<td><strong>District</strong></td>
							<td><strong>Location</strong></td>
							<td><strong>Start Date</strong></td>
							<td><strong>End Date</strong></td>
							<td></td>
						</thead>
						<?php 
							$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventstatus` LIKE 'Live' ORDER BY eventname ASC");
							while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
								echo "<tr id='". $row['eventid'] ."' class='event-info'>";
								echo "<td id='eventid'>". $row['eventid'] ."</td>";
								echo "<td id='eventname'>". $row['eventname'] ."</td>";
								echo "<td id='eventdistrict'>". $row['eventdistrict'] ."</td>";
								echo "<td id='eventlocation'>". $row['eventlocation'] ."</td>";
								echo "<td id='eventstart'>". $row['eventstart'] ."</td>";
								echo "<td id='eventend'>". $row['eventend'] ."</td>";
								echo "<td id='eventstatus' style='display:none;'>". $row['eventstatus'] ."</td>";
								echo "<td><a href='#' class='event-details-link'>Details</a>";
								echo "</tr>";
							}	
						?>
					</table>
				</div>
                <div class="row">
                    <div class="col-md-9">
                        <h2 style="margin-top:0px;">Not Live Events</h2>
                    </div>
				    <div class="col-md-3 year-filter">
            <input style="width: 55%; float: left" type="text" class="form-control" id="events-notlive-search-field" placeholder="Search Not Live Events"></input>

                    </div>
                </div>
                <div class="clearfix"></div>
				<div class="table-responsive">
					<table class="table table-hover sortable not-live" id="table-not-live">
						<thead>
							<th><strong>Event Id</strong></th>
							<th><strong>Name</strong></th>
							<!--<th><strong>Status</strong></th>-->
							<th><strong>District</strong></th>
							<th><strong>Location</strong></th>
							<th><strong>Start Date</strong></th>
							<th><strong>End Date</strong></th>
							<th data-defaultsort='disabled'></th>
						</thead>
						<tbody>
						<?php 
							$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventstatus` LIKE 'Not Live' ORDER BY eventname ASC");
							while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
								echo "<tr id='". $row['eventid'] ."' class='event-info'>";
								echo "<td id='eventid'>". $row['eventid'] ."</td>";
								echo "<td id='eventname'>". $row['eventname'] ."</td>";
								//echo "<td id='eventstatus'>". $row['eventstatus'] ."</td>";
								echo "<td id='eventdistrict'>". $row['eventdistrict'] ."</td>";
								echo "<td id='eventlocation'>". $row['eventlocation'] ."</td>";
								echo "<td id='eventstart'>". $row['eventstart'] ."</td>";
								echo "<td id='eventend'>". $row['eventend'] ."</td>";
								echo "<td id='eventstatus' style='display:none;'>". $row['eventstatus'] ."</td>";
                                echo "<td id='eventyear' style='display:none;'>". $row['eventyear'] ."</td>";
								echo "<td><a href='#' class='event-details-link'>Details</a>";
								echo "</tr>";
							}	
						?>
						</tbody>
					</table>
				</div>
			</div>
			<?php } ?>
			<?php if(isEventAdmin($role)){ ?>
				<div class="dashboard-content">
				<h2>Your Events</h2>
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<th><strong>Event Id</strong></th>
							<th><strong>Name</strong></th>
							<th><strong>District</strong></th>
							<th><strong>Location</strong></th>
							<th><strong>Start Date</strong></th>
							<th><strong>End Date</strong></th>
							<th><strong>Status</strong></th>
							<th></th>
						</thead>
						<tbody>
						<?php 
							$sql = $mysqli->query("SELECT * FROM `events` ORDER BY eventname ASC");
							while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
								if(in_array($row['eventname'],$eventsArr)){
									echo "<tr id='". $row['eventid'] ."' class='event-info'>";
									echo "<td id='eventid'>". $row['eventid'] ."</td>";
									echo "<td id='eventname'>". $row['eventname'] ."</td>";
									echo "<td id='eventdistrict'>". $row['eventdistrict'] ."</td>";
									echo "<td id='eventlocation'>". $row['eventlocation'] ."</td>";
									echo "<td id='eventstart'>". $row['eventstart'] ."</td>";
									echo "<td id='eventend'>". $row['eventend'] ."</td>";
									echo "<td id='eventstatus'>". $row['eventstatus'] ."</td>";
									echo "<td><a href='#' class='event-details-link'>Details</a>";
									echo "</tr>";
								}
							}	
						?>
						</tbody>
					</table>
				</div>
				</div>
			<?php } ?>
		</div>
		
		<div class="col-md-10 container-dashboard-content event-details">
			<div class="dashboard-toolbar">
				<div class="container-fluid">
					<button class="btn btn-default event-details-return pull-left">Back to Event List</button>
					<button class="btn btn-default delete-event pull-right" style="margin-left:10px;">Delete Event</button>
					<button class="btn btn-default clear-event pull-right" style="margin-left:10px;">Clear Event</button>
					<button class="btn btn-default edit-event pull-right">Edit Event</button>
					<button class="btn btn-default save-event pull-right">Save Event</button>
				</div>
			</div>
			<div class="dashboard-content">
				<h2 class="event-name pull-left" style="margin-top:0px;"></h2><p class="pull-right"><strong>This event is:</strong><span class="event-status btn-event-status"></span><a href="#" class="toggle-status" style="margin-left:10px;">Toggle Status</a></p>
				<div class="clearfix"></div>
				<p><strong>Date: </strong><span class="event-start"></span> to <span class="event-end"></span></p>
				<p><strong>Event Type: </strong><span class="event-type"></span></p>
				<br/>
				<p><strong>Location: </strong><span class="event-location"></span></p>
				<p><strong>Address: </strong><span class="event-address"></span></p>
				<p><strong>District: </strong><span class="event-district"></span></p>
				<hr/>
				<div class="row text-center">
						<a id="event-teams" href="" class="btn btn-default" style="margin:10px;">
							<div class="btn-event-manage">
								<div class="display: inline-block;"><span class="glyphicon glyphicon-list-alt btn-event-manage-icon"></span><span class="btn-event-manage-text"> Manage Teams</span></div>
							</div>
						</a>
						<a id="event-matches" href="" class="btn btn-default" style="margin:10px;">
							<div class="btn-event-manage">
								<div class="display: inline-block;"><span class="glyphicon glyphicon-calendar btn-event-manage-icon"></span><span class="btn-event-manage-text"> Manage Matches</span></div>
							</div>
						</a>
						<a id="event-inspections" href="" class="btn btn-default" style="margin:10px;">
							<div class="btn-event-manage">
								<div class="display: inline-block;"><span class="glyphicon glyphicon-search btn-event-manage-icon"></span><span class="btn-event-manage-text"> Manage Inspections</span></div>
							</div>
						</a>
						<a id="event-map" href="" class="btn btn-default" style="margin:10px;">
							<div class="btn-event-manage">
								<div class="display: inline-block;"><span class="glyphicon glyphicon-map-marker btn-event-manage-icon"></span><span class="btn-event-manage-text"> Pit Map Creator</span></div>
							</div>
						</a>
				</div>
			</div>
		</div>
	</div>
</div>	
	
<?php 
	} else { echo '<script>document.location.href="signin"</script>'; }
	
	include dirname(__DIR__) . "/footer.php"; 
?>