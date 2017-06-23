<title>PeachPits - Matches</title>
<?php 
	include "header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="chooseevent"</script>';
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
	<div id="tables-matches" class="table-responsive">
        <table id="table-team-matches" class="table table-hover">
			<?php 
				//Goes to the databse and fetches all matches in order
				$sql = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchtype` LIKE 'qm' ORDER BY matchnumber ASC");
				if(mysqli_num_rows($sql) != 0){
                    echo '<thead>';
                    echo '  <td class="text-center"><b>Match #</b></td>';
                    echo '  <td class="text-center"><b>Start Time</b></td>';
                    echo '  <td class="text-center"><b>Red 1</b></td>';
                    echo '  <td class="text-center"><b>Red 2</b></td>';
                    echo '  <td class="text-center"><b>Red 3</b></td>';
                    echo '  <td class="text-center"><b>Red 1</b></td>';
                    echo '  <td class="text-center"><b>Red 2</b></td>';
                    echo '  <td class="text-center"><b>Red 3</b></td>';
                    echo '  <td class="text-center"><b>Pit</b></td>';
                    echo '</thead>';
					while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
						echo "<tr id='". $row['matchid'] ."'>";
						echo "<td style='width:8%; padding-left: 15px;' class='text-center' id='matchnumber'>". $row['matchnumber'] ."</td>";
						echo "<td class='text-center' id='starttime'>". $row['start'] ."</td>";
						echo "<td id='red1' class='red text-center'>". $row['red1'] ."</td>";
						echo "<td id='red2' class='red text-center'>". $row['red2'] ."</td>";
						echo "<td id='red3' class='red text-center'>". $row['red3'] ."</td>";
                        echo "<td id='blue1' class='blue text-center'>". $row['blue1'] ."</td>";
						echo "<td id='blue2' class='blue text-center'>". $row['blue2'] ."</td>";
						echo "<td id='blue3' class='blue text-center'>". $row['blue3'] ."</td>";
						echo "<td style='text-align:center;'><a href='pitmap?event=".$currentEvent."&r1=".$row['red1']."&r2=".$row['red2']."&r3=".$row['red3']."&b1=".$row['blue1']."&b2=".$row['blue2']."&b3=".$row['blue3']."'><span class='glyphicon glyphicon-map-marker'></span></a></td>";
					}	
				}
				else{
					echo '<div id="team-matches-none" class="container-fluid" style="background-color:#F5B7B1; padding:10px;margin:20px;"><h2 class="text-center" style="margin-top:10px;">There\'s no match schedule yet! Please check back soon.</h2></div>';
				}
			?>
		</table>
		<table id="table-team-matches-mobile" class="table">
			<?php 
				//Goes to the databse and fetches all matches in order
				$sql = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchtype` LIKE 'qm' ORDER BY matchnumber ASC");
				if(mysqli_num_rows($sql) != 0){
                    echo '<thead>';
                    echo   '<tr>';
                    echo     '<td rowspan="2" style="vertical-align:middle"><b>Match</b></td>';
                    echo     '<td rowspan="2" class="text-center" style="vertical-align:middle"><b>Time</b></td>';
                    echo     '<td colspan="3" class="text-center"><b>Driver\'s Station</b></td>';
                    echo     '<td rowspan="2" rowclass="text-center" class="text-center" style="vertical-align:middle"><b>Pit</b></td>';
                    echo   '</tr>';
                    echo   '<tr>';
                    echo     '<td class="text-center"><b>1</b></td>';
                    echo     '<td class="text-center"><b>2</b></td>';
                    echo     '<td class="text-center"><b>3</b></td>';
                    echo   '</tr>';
                    echo '</thead>';
					while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
						echo "<tr id='". $row['matchid'] ."'>";
						echo "<td rowspan='2' style='width:8%; padding-left: 15px; vertical-align:middle;' class='text-left' id='matchnumber'>". $row['matchnumber'] ."</td>";
						echo "<td rowspan='2' 'class='text-center' style='vertical-align:middle' id='starttime'>". $row['start'] ."</td>";
						echo "<td id='red1' class='red text-center'>". $row['red1'] ."</td>";
						echo "<td id='red2' class='red text-center'>". $row['red2'] ."</td>";
						echo "<td id='red3' class='red text-center'>". $row['red3'] ."</td>";
						echo "<td rowspan='2' class='text-center' style='vertical-align:middle'><a href='pitmap?event=".$currentEvent."&r1=".$row['red1']."&r2=".$row['red2']."&r3=".$row['red3']."&b1=".$row['blue1']."&b2=".$row['blue2']."&b3=".$row['blue3']."'><span class='glyphicon glyphicon-map-marker'></span></a></td>";
						echo "</tr>";
                        echo "<tr id='". $row['matchid'] ."'>";
						echo "<td id='blue1' class='blue text-center'>". $row['blue1'] ."</td>";
						echo "<td id='blue2' class='blue text-center'>". $row['blue2'] ."</td>";
						echo "<td id='blue3' class='blue text-center'>". $row['blue3'] ."</td>";
						echo "</tr>";
					}	
				}
				else{
					echo '<div id="team-matches-none-mobile" class="container-fluid" style="background-color:#F5B7B1; padding:10px;margin:20px;"><h2 class="text-center" style="margin-top:10px;">There\'s no match schedule yet! Please check back soon.</h2></div>';
				}
			?>
		</table>
	</div>
</div>

<?php } include "footer.php"; ?>