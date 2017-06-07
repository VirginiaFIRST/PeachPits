<?php 
	include "header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="chooseevent.php"</script>';
	}
	else {
		$event = $currentEvent."_matches";	
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventname = $row['eventname'];
?>

<div class="page-head">
	<div class="container">
		<h1>Match Schedule for <?php echo $eventname; ?></h1>
	</div>
</div>
<div class="container content">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<td><b>Match #</b></td>
				<td><b>Start Time</b></td>
				<td class="text-center"><b>Red 1</b></td>
				<td class="text-center"><b>Red 2</b></td>
				<td class="text-center"><b>Red 3</b></td>
				<td class="text-center"><b>Blue 1</b></td>
				<td class="text-center"><b>Blue 2</b></td>
				<td class="text-center"><b>Blue 3</b></td>
				<td></td>
			</thead>
			<?php 
				//Goes to the databse and fetches all matches in order
				$sql = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchtype` LIKE 'qm' ORDER BY matchnumber ASC");
				if(mysqli_num_rows($sql) != 0){
					while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
						echo "<tr id='". $row['matchid'] ."'>";
						echo "<td id='matchnumber'>". $row['matchnumber'] ."</td>";
						echo "<td id='starttime'>". $row['start'] ."</td>";
						echo "<td id='red1' class='red text-center'>". $row['red1'] ."</td>";
						echo "<td id='red2' class='red text-center'>". $row['red2'] ."</td>";
						echo "<td id='red3' class='red text-center'>". $row['red3'] ."</td>";
						echo "<td id='blue1' class='blue text-center'>". $row['blue1'] ."</td>";
						echo "<td id='blue2' class='blue text-center'>". $row['blue2'] ."</td>";
						echo "<td id='blue3' class='blue text-center'>". $row['blue3'] ."</td>";
						echo "<td class='text-center'><a href='pitmap.php?event=".$currentEvent."&r1=".$row['red1']."&r2=".$row['red2']."&r3=".$row['red3']."&b1=".$row['blue1']."&b2=".$row['blue2']."&b3=".$row['blue3']."'>View Map</a></td>";
						echo "</tr>";
					}	
				}
				else{
					echo '<div class="container-fluid" style="background-color:#F5B7B1; padding:10px;margin:20px;"><h2 class="text-center" style="margin-top:10px;">There\'s no match schedule yet! Please check back soon.</h2></div>';
				}
			?>
		</table>
	</div>
</div>

<?php } include "footer.php"; ?>