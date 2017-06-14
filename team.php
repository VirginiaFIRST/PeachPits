<?php include "header.php"; ?>

<?php 
	$team = $_GET['team'];
	$event = $currentEvent."_teams";
	$sql = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus FROM teams AS t INNER JOIN `".$event."` AS e ON t.teamid=e.teamid WHERE t.teamid = '$team'");
	$row = mysqli_fetch_assoc($sql);
	
	$teamid = $row['teamid'];
	$teamname = $row['teamname'];
	$school = $row['schoolname'];
	$location = $row['location'];
	$inspection = $row['inspectionstatus'];	
	
	$event = $currentEvent."_matches";	
	$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
	$row = mysqli_fetch_assoc($sql);
	$eventname = $row['eventname'];
?>

<div class="page-head" style="padding-top:10px; padding-bottom:10px;">
	<div class="container">	
		<a class="btn btn-default" href="javascript:history.back()"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
	</div>
</div>
<div class="container content">
	<h1>Team <?php echo $teamid; ?></h1>
	<hr/>
	<p class="dashboard-usertitle-name">Team Name</p>
	<p style="margin-left:10px;"><?php echo $teamname; ?></p>
	
	<p class="dashboard-usertitle-name">School Name</p>
	<p style="margin-left:10px;"><?php echo $school; ?></p>
	
	<p class="dashboard-usertitle-name">Location</p>
	<p style="margin-left:10px;"><?php echo $location; ?></p>
	
	<p class="dashboard-usertitle-name">Inspection Status</p>
	<p style="margin-left:10px;"><?php echo $inspection; ?></p>
	
	<p style="margin-left:10px;"><a href="pitmap.php?event=<?php echo $currentEvent ?>&team=<?php echo $teamid ?>">Pit Location ></a></p>
	
	<div class="table-responsive">
		<table id="table-team-matches" class="table table-hover">
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
				$sql = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchtype` LIKE 'qm' AND `red1` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND `red2` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND `red3` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND`blue1` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND `blue2` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND `blue3` LIKE '$teamid' ORDER BY matchnumber ASC");
				if(mysqli_num_rows($sql) != 0){
					while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
						echo "<tr id='". $row['matchid'] ."'>";
						echo "<td id='matchnumber'>". $row['matchnumber'] ."</td>";
						echo "<td id='starttime'>". $row['start'] ."</td>";
						if($teamid == $row['red1']){echo "<td id='red1' class='red text-center'><b>". $row['red1'] ."</b></td>";} else {echo "<td id='red1' class='red text-center'>". $row['red1'] ."</td>";}
						if($teamid == $row['red2']){echo "<td id='red2' class='red text-center'><b>". $row['red2'] ."</b></td>";} else {echo "<td id='red2' class='red text-center'>". $row['red2'] ."</td>";}
						if($teamid == $row['red3']){echo "<td id='red3' class='red text-center'><b>". $row['red3'] ."</b></td>";} else {echo "<td id='red3' class='red text-center'>". $row['red3'] ."</td>";}
						if($teamid == $row['blue1']){echo "<td id='blue1' class='blue text-center'><b>". $row['blue1'] ."</b></td>";} else {echo "<td id='blue1' class='blue text-center'>". $row['blue1'] ."</td>";}
						if($teamid == $row['blue2']){echo "<td id='blue2' class='blue text-center'><b>". $row['blue2'] ."</b></td>";} else {echo "<td id='blue2' class='blue text-center'>". $row['blue2'] ."</td>";}
						if($teamid == $row['blue3']){echo "<td id='blue3' class='blue text-center'><b>". $row['blue3'] ."</b></td>";} else {echo "<td id='blue3' class='blue text-center'>". $row['blue3'] ."</td>";}
						echo "<td class='text-center'><a href='pitmap.php?event=".$currentEvent."&r1=".$row['red1']."&r2=".$row['red2']."&r3=".$row['red3']."&b1=".$row['blue1']."&b2=".$row['blue2']."&b3=".$row['blue3']."'><span class='glyphicon glyphicon-map-marker'></span></a></td>";
						echo "</tr>";
					}		
				}
				else{
					echo '<div class="container-fluid" style="background-color:#F5B7B1; padding:10px;margin:20px;"><h2 class="text-center" style="margin-top:10px;">There\'s no match schedule yet! Please check back soon.</h2></div>';
				}
				
			?>
		</table>
    <table id="table-team-matches-mobile" class="table">
			<thead>
        <tr>
				  <td rowspan="2" style="vertical-align: middle"><b>Match</b></td>
				  <td rowspan="2" style="vertical-align: middle; width: 10%"><b>Time</b></td>
          <td colspan="3" class="text-center"><b>Driver's Station</b></td>
          <td rowspan="2" style="vertical-align: middle" class="text-center"><b>Pit</b></td>
        </tr>
        <tr>
				  <td class="text-center"><b>1</b></td>
				  <td class="text-center"><b>2</b></td>
				  <td class="text-center"><b>3</b></td>
        </tr>
			</thead>
			<?php 
				$sql = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchtype` LIKE 'qm' AND `red1` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND `red2` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND `red3` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND`blue1` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND `blue2` LIKE '$teamid' OR `matchtype` LIKE 'qm' AND `blue3` LIKE '$teamid' ORDER BY matchnumber ASC");
				if(mysqli_num_rows($sql) != 0){
					while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
						echo "<tr id='". $row['matchid'] ."'>";
						echo "<td rowspan='2' style='width:7%; padding-left: 15px; vertical-align:middle;' id='matchnumber'>". $row['matchnumber'] ."</td>";
						echo "<td rowspan='2' style='width:7%; vertical-align: middle;' id='starttime'>". $row['start'] ."</td>";
						if($teamid == $row['red1']){echo "<td id='red1' class='red text-center'><b>". $row['red1'] ."</b></td>";} else {echo "<td id='red1' class='red text-center'>". $row['red1'] ."</td>";}
						if($teamid == $row['red2']){echo "<td id='red2' class='red text-center'><b>". $row['red2'] ."</b></td>";} else {echo "<td id='red2' class='red text-center'>". $row['red2'] ."</td>";}
						if($teamid == $row['red3']){echo "<td id='red3' class='red text-center'><b>". $row['red3'] ."</b></td>";} else {echo "<td id='red3' class='red text-center'>". $row['red3'] ."</td>";}
						echo "<td rowspan='2' class='text-center' style='vertical-align:middle'><a href='pitmap.php?event=".$currentEvent."&r1=".$row['red1']."&r2=".$row['red2']."&r3=".$row['red3']."&b1=".$row['blue1']."&b2=".$row['blue2']."&b3=".$row['blue3']."'><span class='glyphicon glyphicon-map-marker'></span></a></td>";
						echo "</tr>";
            echo "<tr id='". $row['mactchid'] ."'>";
           	if($teamid == $row['blue1']){echo "<td id='blue1' class='blue text-center'><b>". $row['blue1'] ."</b></td>";} else {echo "<td id='blue1' class='blue text-center'>". $row['blue1'] ."</td>";}
						if($teamid == $row['blue2']){echo "<td id='blue2' class='blue text-center'><b>". $row['blue2'] ."</b></td>";} else {echo "<td id='blue2' class='blue text-center'>". $row['blue2'] ."</td>";}
						if($teamid == $row['blue3']){echo "<td id='blue3' class='blue text-center'><b>". $row['blue3'] ."</b></td>";} else {echo "<td id='blue3' class='blue text-center'>". $row['blue3'] ."</td>";}
					}		
				}
				else{
					echo '<div class="container-fluid" style="background-color:#F5B7B1; padding:10px;margin:20px;"><h2 class="text-center" style="margin-top:10px;">There\'s no match schedule yet! Please check back soon.</h2></div>';
				}
				
			?>
		</table>
	</div>
</div>

<style>
  #table-team-matches{
    display: table;
    margin: auto;
    border: 1px solid lightgray;
    padding: 10px;
  }
  #table-team-matches-mobile{
    display: none;
  }
  @media screen and (max-width: 768px){
    #table-team-matches{
      display: none;
    }
    #table-team-matches-mobile{
      display: table;
    }
  }
</style>


<?php include "footer.php"; ?>