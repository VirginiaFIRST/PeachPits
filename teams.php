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
				<th><strong>Status</strong></th>
				<th><strong>Pit</strong></th>
				<th><strong>Info</strong></th>
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
					echo '<td><a href="pitmap.php?event='.$currentEvent. '&team='. $row['teamid'] .'"><span class="glyphicon glyphicon-map-marker"></span></a></td>';
					echo "<td allign=justify'><a href='team.php?team=". $row['teamid'] ."&event=".$currentEvent."'><span class='glyphicon glyphicon-info-sign'</span></a></td>";
					echo "</tr>";
				}	
			?>
			</tbody>
		</table>
	</div>
</div>

<style>
  
  @media screen and (max-width:768px){
    #teamname{
    max-width: 20px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
}
</style>


<?php } include "footer.php"; ?>