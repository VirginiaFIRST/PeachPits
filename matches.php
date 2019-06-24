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

		$eventTeams = $currentEvent."_teams";
		$eventInspections = $currentEvent."_inspections";

		$i = 0;
		$inspectStatuses;
		$completedTeams;
			
		$sqlTeams = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus, e.inspectionnotes, e.initial_inspector, e.last_modified_by, e.last_modified_time FROM `".$eventTeams."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
		while($rowTeams = mysqli_fetch_array($sqlTeams, MYSQLI_BOTH)){
			$inspectStatuses[$i][0] = $rowTeams['teamid']; 
			$inspectStatuses[$i][1] = $rowTeams['inspectionstatus']; 
			$i = $i + 1;
		}
		for($i = 0; $i < count($inspectStatuses); $i++){
			if ($inspectStatuses[$i][1] == 'Complete') {
				$completedTeams[] = $inspectStatuses[$i][0];
			}
		}
		
?>
<div class="page-head">
	<div class="container">
		<div class="row">
            <div class="col-md-9"><h1>Match Schedule for <?php echo $eventname; ?></h1></div>
        </div>
	</div>
	<div class="dashboard-toolbar" id="status-btns-container">
		<div class="container-fluid text-center" style="padding-top:10px;padding-bottom:10px">
			<a style="display:none" class="btn btn-default btn-inspection-matches">View Status</a>
			<a style="display:inline" class="btn btn-default btn-inspection-matches-hide">Hide Status</a>
			<?php if(isSuperAdmin($role) || isEventAdmin($role)) { ?> 
            <div class="dropdown dropdown-teams">
				<button id="btn-refresh-time" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Refresh Time <span class="caret"></span></button>
					<!--<button class="btn btn-default dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">-->
					<ul class="dropdown-menu">
					<?php
					echo '<li class="disabled"><a href="display?event='.$currentEvent.'"><b>Current: </b>'.$refreshTime.'</a></li>';
					echo '<li role="separator" class="divider"></li>';
					foreach($refreshArr as $option) {
						if ($option != $refreshTime) {
						$optionAsInt = (int) filter_var($option, FILTER_SANITIZE_NUMBER_INT);
						echo '<li><a href="update_refresh_time?event='.$currentEvent.'&returnLocation=matches&newRefreshTime='.$optionAsInt.'">'.$option.'</a></li>';
						}
					}
					?>
					</ul>
				</div>
			<?php } ?>
		</div>
		<div class="container-fluid text-center" id="status-info-text">
			<h4>Teams who have passed inspection are highlighted in <span>green</span>. Teams who have not passed inspection are highlighted in <span>gray</span>.</h4>
		</div>
	</div>
</div>
<div class="container content">
	<div id="tables-matches" class="table-responsive">
		<table id="table-team-matches-with-status" class="team-matches table table-hover">
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
                    echo '  <td class="text-center"><b>Blue 1</b></td>';
                    echo '  <td class="text-center"><b>Blue 2</b></td>';
                    echo '  <td class="text-center"><b>Blue 3</b></td>';
                    echo '  <td class="text-center"><b>Pit</b></td>';
                    echo '</thead>';
					while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
						echo "<tr id='". $row['matchid'] ."'>";
						echo "<td style='width:8%; padding-left: 15px;' class='text-center' id='matchnumber'>". $row['matchnumber'] ."</td>";
						echo "<td class='text-center' id='starttime'>". $row['start'] ."</td>";
						if (in_array($row['red1'], $completedTeams)) {
							echo "<td id='red1' class='levelSix text-center'>". $row['red1'] ."</td>";
						} else {
							echo "<td id='red1' class='gray text-center'>". $row['red1'] ."</td>";
						}
						if (in_array($row['red2'], $completedTeams)) {
							echo "<td id='red2' class='levelSix text-center'>". $row['red2'] ."</td>";
						} else {
							echo "<td id='red2' class='gray text-center'>". $row['red2'] ."</td>";
						}
						if (in_array($row['red3'], $completedTeams)) {
							echo "<td id='red3' class='levelSix text-center'>". $row['red3'] ."</td>";
						} else {
							echo "<td id='red3' class='gray text-center'>". $row['red3'] ."</td>";
						}
						if (in_array($row['blue1'], $completedTeams)) {
							echo "<td id='blue1' class='levelSix text-center'>". $row['blue1'] ."</td>";
						} else {
							echo "<td id='blue1' class='gray text-center'>". $row['blue1'] ."</td>";
						}
						if (in_array($row['blue2'], $completedTeams)) {
							echo "<td id='blue2' class='levelSix text-center'>". $row['blue2'] ."</td>";
						} else {
							echo "<td id='blue2' class='gray text-center'>". $row['blue2'] ."</td>";
						}
						if (in_array($row['blue3'], $completedTeams)) {
							echo "<td id='blue3' class='levelSix text-center'>". $row['blue3'] ."</td>";
						} else {
							echo "<td id='blue3' class='gray text-center'>". $row['blue3'] ."</td>";
						}
						echo "<td style='text-align:center;'><a href='pitmap?event=".$currentEvent."&r1=".$row['red1']."&r2=".$row['red2']."&r3=".$row['red3']."&b1=".$row['blue1']."&b2=".$row['blue2']."&b3=".$row['blue3']."'><span class='glyphicon glyphicon-map-marker'></span></a></td>";
					}	
				}
			?>
		</table>
		<table id="table-team-matches-without-status" class="team-matches hidden">
			<?php 
				//Goes to the database and fetches all matches in order
				$sql = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchtype` LIKE 'qm' ORDER BY matchnumber ASC");
				if(mysqli_num_rows($sql) != 0){
                    echo '<thead>';
                    echo '  <td class="text-center"><b>Match #</b></td>';
                    echo '  <td class="text-center"><b>Start Time</b></td>';
                    echo '  <td class="text-center"><b>Red 1</b></td>';
                    echo '  <td class="text-center"><b>Red 2</b></td>';
                    echo '  <td class="text-center"><b>Red 3</b></td>';
                    echo '  <td class="text-center"><b>Blue 1</b></td>';
                    echo '  <td class="text-center"><b>Blue 2</b></td>';
                    echo '  <td class="text-center"><b>Blue 3</b></td>';
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
					echo "<script>document.getElementById('status-btns-container').classList.add('hidden');</script>";
				}
			?>
		</table>
		<table id="table-team-matches-mobile-with-status" class="team-matches-mobile hidden">
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
						if (in_array($row['red1'], $completedTeams)) {
							echo "<td id='red1' class='levelSix text-center'>". $row['red1'] ."</td>";
						} else {
							echo "<td id='red1' class='gray text-center'>". $row['red1'] ."</td>";
						}
						if (in_array($row['red2'], $completedTeams)) {
							echo "<td id='red2' class='levelSix text-center'>". $row['red2'] ."</td>";
						} else {
							echo "<td id='red2' class='gray text-center'>". $row['red2'] ."</td>";
						}
						if (in_array($row['red3'], $completedTeams)) {
							echo "<td id='red3' class='levelSix text-center'>". $row['red3'] ."</td>";
						} else {
							echo "<td id='red3' class='gray text-center'>". $row['red3'] ."</td>";
						}
						echo "<td rowspan='2' class='text-center' style='vertical-align:middle'><a href='pitmap?event=".$currentEvent."&r1=".$row['red1']."&r2=".$row['red2']."&r3=".$row['red3']."&b1=".$row['blue1']."&b2=".$row['blue2']."&b3=".$row['blue3']."'><span class='glyphicon glyphicon-map-marker'></span></a></td>";
						echo "</tr>";
                        echo "<tr id='". $row['matchid'] ."'>";
						if (in_array($row['blue1'], $completedTeams)) {
							echo "<td id='blue1' class='levelSix text-center'>". $row['blue1'] ."</td>";
						} else {
							echo "<td id='blue1' class='gray text-center'>". $row['blue1'] ."</td>";
						}
						if (in_array($row['blue2'], $completedTeams)) {
							echo "<td id='blue2' class='levelSix text-center'>". $row['blue2'] ."</td>";
						} else {
							echo "<td id='blue2' class='gray text-center'>". $row['blue2'] ."</td>";
						}
						if (in_array($row['blue3'], $completedTeams)) {
							echo "<td id='blue3' class='levelSix text-center'>". $row['blue3'] ."</td>";
						} else {
							echo "<td id='blue3' class='gray text-center'>". $row['blue3'] ."</td>";
						}
						echo "</tr>";
					}	
				}
			?>
		</table>
		<table id="table-team-matches-mobile-without-status" class="team-matches-mobile hidden">
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
<script>
	$(window).resize(function () {
		if ($(window).width() < 960) {
			if (document.getElementsByClassName('btn-inspection-matches')[0].style.display == 'inline') {
				document.getElementById("table-team-matches-mobile-with-status").className = "hidden";
				document.getElementById("table-team-matches-mobile-without-status").className = "table";
				document.getElementById("table-team-matches-with-status").className = "team-matches hidden";
				document.getElementById("table-team-matches-without-status").className = "team-matches hidden";
			}
			else {
				document.getElementById("table-team-matches-mobile-with-status").className = "table";
				document.getElementById("table-team-matches-mobile-without-status").className = "hidden";
				document.getElementById("table-team-matches-with-status").className = "team-matches hidden";
				document.getElementById("table-team-matches-without-status").className = "team-matches hidden";
			}
		}
		else {
			if (document.getElementsByClassName('btn-inspection-matches')[0].style.display == 'inline') {
				document.getElementById("table-team-matches-mobile-with-status").className = "hidden";
				document.getElementById("table-team-matches-mobile-without-status").className = "hidden";
				document.getElementById("table-team-matches-with-status").className = "team-matches hidden";
				document.getElementById("table-team-matches-without-status").className = "team-matches table table-hover";
			}
			else {
				document.getElementById("table-team-matches-mobile-with-status").className = "hidden";
				document.getElementById("table-team-matches-mobile-without-status").className = "hidden";
				document.getElementById("table-team-matches-with-status").className = "team-matches table table-hover";
				document.getElementById("table-team-matches-without-status").className = "team-matches hidden";
			}
		}
	});
	$(document).ready(function() {
    	$('.btn-inspection-matches').on('click',function(){
			if ($(window).width() < 960) {
				document.getElementById("table-team-matches-mobile-with-status").className = "table";
				document.getElementById("table-team-matches-mobile-without-status").className = "hidden";
			} else {
				document.getElementById("table-team-matches-with-status").className = "team-matches table table-hover";
				document.getElementById("table-team-matches-without-status").className = "team-matches hidden";
			}
			$('.btn-inspection-matches').css('display','none');
			$('.btn-inspection-matches-hide').css('display','inline');
			$('#status-info-text').css('display','block');
		});
		$('.btn-inspection-matches-hide').on('click',function(){
			if ($(window).width() < 960) {
				document.getElementById("table-team-matches-mobile-with-status").className = "hidden";
				document.getElementById("table-team-matches-mobile-without-status").className = "table";
			} else {
				document.getElementById("table-team-matches-with-status").className = "team-matches hidden";
				document.getElementById("table-team-matches-without-status").className = "team-matches table table-hover";
			}
			$('.btn-inspection-matches').css('display','inline');
			$('.btn-inspection-matches-hide').css('display','none');
			$('#status-info-text').css('display','none');
		});
		if ($(window).width() < 960) {
  			document.getElementById("table-team-matches-mobile-with-status").className = "table";
			document.getElementById("table-team-matches-mobile-without-status").className = "hidden";
			document.getElementById("table-team-matches-with-status").className = "hidden";
			document.getElementById("table-team-matches-without-status").className = "hidden";
		}
		$('[data-toggle="tooltip"]').tooltip();
	});
	<?php if (isSuperAdmin($role) || isEventAdmin($role)) { ?>
        <!-- Updates the page based on the session refresh time -->
        var refreshTime = <?php echo $_SESSION['refreshTime'] ?>000;
        var refresh = window.setInterval(update, refreshTime);

          function update() {
          var teamsArr2;
		  var incompleteArr = [];
                $.ajax({
                    url: 'auto_refresh?event=<?php echo $currentEvent; ?>',
                    type: 'POST',
                    success: function(data) {
                        teamsArr2 = [];
                        teamsArr2 = data;
                        console.log('refresh');
                        //console.log(teamsArr2);
                        for (var i=0; i < teamsArr2.length; i++){
                            //console.log(teamsArr2[i][0] + ": " + teamsArr2[i][3]);
                            if (teamsArr2[i][3] == 'Minor Issue' || teamsArr2[i][3] == 'Major Issue' || teamsArr2[i][3] == 'Weighed and Sized' || teamsArr2[i][3] == 'Ok to unbag' || teamsArr2[i][3] == 'Not Started'){
                                //add team from teamArr2 to new arr
								incompleteArr.push(teamsArr2[i][0]);
                            }
                      }  
          //Changes colors of cells in tables based on if the teams passed inspection
		  var tables = [document.getElementById('table-team-matches-with-status'), document.getElementById('table-team-matches-mobile-with-status')];
		  for (var tableIndex = 0; tableIndex < tables.length; tableIndex++) {
			  tr = tables[tableIndex].getElementsByTagName('tr');
			  for (var trIndex = 0; trIndex < tr.length; trIndex++) {
				  td = tr[trIndex].getElementsByTagName('td');
				  for (var tdIndex = 0; tdIndex < td.length; tdIndex++) {
					  cell = td[tdIndex];
					  cellID = cell.id;
					  acceptedIDs = ['red1', 'red2', 'red3', 'blue1', 'blue2', 'blue3'];
					  if (acceptedIDs.indexOf(cellID) != -1) {
						  if (incompleteArr.indexOf(cell.innerHTML) != -1) {
							  cell.classList.remove('red');
							  cell.classList.remove('blue');
							  cell.classList.add('gray');
						  }
						  else {
							cell.classList.remove('gray');
							cell.classList.add('levelSix');
						  }
					  }
				  }
			  }
		  }
                    },
                    dataType:'json'
                })
            }
        update();
        <?php } ?>
</script>
<?php } include "footer.php"; ?>