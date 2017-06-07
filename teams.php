<?php 
	include "header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="chooseevent.php"</script>';
	}
	else {
		$event = $currentEvent."_teams";
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventname = $row['eventname'];
?>

<div class="page-head">
	<div class="container">
		<h1>Teams at <?php echo $eventname; ?></h1>
	</div>
</div>
<div class="container content">
	<div class="table-responsive">
		<table class="table table-hover sortable">
			<thead>
				<th><strong>Team #</strong></th>
				<th><strong>Team Name</strong></th>
				<th><strong>Inspection Status</strong></th>
				<th><strong>Pit Location</strong></th>
				<th class="text-right"></th>
			</thead>
			<tbody>
			<?php 
				//Fetches all teams in order from the database
				$sql = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus FROM `".$event."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
				while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
					echo "<tr id='". $row['teamid'] ."'>";
					echo "<td id='teamid'>". $row['teamid'] ."</td>";
					echo "<td id='teamname'>". $row['teamname'] ."</td>";
					echo "<td id='inspectionstatus'>". $row['inspectionstatus'] ."</td>";
					echo '<td><a href="pitmap.php?event='.$currentEvent. '&team='. $row['teamid'] .'">Pit Location ></a></td>';
					echo "<td class='text-right'><a href='team.php?team=". $row['teamid'] ."&event=".$currentEvent."'>More Info</a></td>";
					echo "</tr>";
				}	
			?>
			</tbody>
		</table>
	</div>
</div>

<?php } include "footer.php"; ?>