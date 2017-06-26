<title>PeachPits - Pitmap</title>
<?php 
	include "header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="chooseevent"</script>';
	}
	else {
		$event = $currentEvent."_teams";
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventName = $row['eventname'];
        $teamid = $row['teamid'];
		
		$eventMatches = $currentEvent."_matches";
		$sqlMatches = $mysqli->query("SELECT * FROM `".$eventMatches."` WHERE `matchtype` LIKE 'qm' ORDER BY matchnumber ASC");
		
		$sql = $mysqli->query("SELECT * FROM `maps` WHERE `eventid` LIKE '$currentEvent'");
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
                $inspectStatuses[$i][8] = $rowTeams['schoolname'];
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

<div class="page-head" style="margin-bottom:0px;">
	<div class="container">
        <div class="row">
            <div class="col-md-9"><h1><?php echo $eventName; ?> Pit Map</h1></div>
            <div class="col-md-3">
                <a href="display?event=<?php echo $currentEvent; ?>" class="btn btn-default btn-display display-hd" data-toggle="tooltip" title="Updates status automatically every 15 seconds">Display Mode</a>
                <a href="display?event=<?php echo $currentEvent; ?>" class="btn btn-default btn-xs btn-display display-vs" data-toggle="tooltip" title="Updates status automatically every 15 seconds">Display Mode</a>
            </div>
        </div>
	</div>
</div>
<div class="pitmap-btn-container text-center">
	<div class="container">
		<div class="row">
			<div class="col-xs-2 btn-back"></div>
			<div class="col-xs-8">
				<div class="dropdown dropdown-teams">
					<button class="btn btn-default dropdown-toggle btn-st btn-pitmap" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Select a Team <span class="caret"></span>
					</button>
					<ul class="dropdown-menu pull-center dropdown-scrollable" aria-labelledby="dropdownMenu1">
					<?php 
						$sql = $mysqli->query("SELECT * FROM `".$event."`");
						while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
							echo '<li id="team' . $row['teamid'] . '" class="select-teams"><a href="#">' . $row['teamid'] . '</a></li>';
						}	 
					?>
					</ul>
				</div>				
				<div class="dropdown dropdown-matches">
					<button class="btn btn-default dropdown-toggle btn-m btn-pitmap" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Select a Match <span class="caret"></span>
					</button>
					<ul class="dropdown-menu pull-center dropdown-scrollable" aria-labelledby="dropdownMenu1">
					<?php 
						while($row = mysqli_fetch_array($sqlMatches, MYSQLI_BOTH)){
							echo '<li id="'.$row['red1'].'-'.$row['red2'].'-'.$row['red3'].'-'.$row['blue1'].'-'.$row['blue2'].'-'.$row['blue3'].'-" class="select-matches"><a href="#" class="a-sm">#' . $row['matchnumber'] . '<span class="sm-vs">:</span> <span class="sm-red">'.$row['red1'].'|'.$row['red2'].'|'.$row['red3'].'</span> <span class="sm-vs">vs.</span> <span class="sm-blue">'.$row['blue1'].'|'.$row['blue2'].'|'.$row['blue3'].'</span></a></li>';
						}	 
                    ?>	
					</ul>
				</div>
			</div>
			<div class="col-xs-2">
				<button class="btn btn-default btn-inspection pull-right btn-pitmap">View Status</button>
				<button class="btn btn-default btn-inspection-hide pull-right btn-pitmap">Hide Status</button>
			</div>
		</div>
	</div>
</div>
<div class="status-text-container">
	<div class="text-center">
		<p class="status-text">Viewing Inspection Status</p>
	</div>
</div>
<div class="container" style="padding:0px !important;">
	<div class="container-map-centered map-main">
		<div class="container-map-outer"><div id="frame" class="container-map map-page"></div></div>     
	</div>
    <div class="map-page-team">
        <a class="return btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Return to Map</a>
        <div class="team-title">
            <h3 style="margin-top:0px;"><span class="map-teamnum"></span> <small class="map-teamname"></small></h3>
        </div>
        <div class="team-tab">
            <input type="button" class="tablinks" id="tabinfo" onclick="openTab(event, 'teaminfo')" value="Team Info">
            <input type="button" class="tablinks active" id="tabinspection" onclick="openTab(event, 'teaminspection' )" value="Inspection">
            <input type="button" class="tablinks" id="tabmatches" onclick="openTab(event, 'teammatches')" value="Matches">
        </div>
        <div id="teaminfo" class="tabcontent">
            <h4><b>Location: </b></h4><p class="pull-left map-teamlocation"></p>
            <div class="clearfix"></div>
            <h4><b>School Name: </b></h4><p class="map-schoolname" style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 8; -webkit-box-orient: vertical;"></p>
        </div>
        <div id="teaminspection" class="tabcontent">
            <h4><b>Inspection Status: </b></h4><p class="map-inspectstatus text-center"></p>
            <?php if(isInspector($role) || isLeadInspector($role) || isSuperAdmin($role)){ ?>
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
                <button type="submit" class="btn btn-default change-status pull-right" name="submit">Change Status</button>

                <div class="clearfix"></div>
                <h4><b>Inspection Notes: </b></h4>
                <textarea class="form-control map-inspectnotes" name="inspectionnotes"></textarea>
                <button type="submit" class="btn btn-default pull-right save-note" name="submit">Save Note</button>
                <div class="clearfix"></div>
                <h4><b>Initial Inspector: </b></h4><p class="map-initialinspector"></p>
                <h4><b>Last Modified By: </b></h4><p class="map-inspectmodifiedby"></p>
                <h4><b>Last Modified Time: </b></h4><p class="map-inspectmodifiedtime"></p>
            <?php } ?>
        </div>
        <div id="teammatches" class="tabcontent">
            <table id="table-team-matches" class="table table-hover" style="margin-top:20px;">
                <thead>
                    <tr>
                        <td class="text-center"><b>Match #</b></td>
                        <td class="text-center"><b>Start Time</b></td>
                        <td class="text-center"><b>Red 1</b></td>
                        <td class="text-center"><b>Red 2</b></td>
                        <td class="text-center"><b>Red 3</b></td>
                        <td class="text-center"><b>Blue 1</b></td>
                        <td class="text-center"><b>Blue 2</b></td>
                        <td class="text-center"><b>Blue 3</b></td>
                    </tr>
                </thead>
            </table>
            <table id="table-team-matches-mobile" class="table">
                <thead>
                    <tr>
                        <td rowspan="2" style="vertical-align:middle"><b>Match</b></td>
                        <td rowspan="2" class="text-center" style="vertical-align:middle"><b>Time</b></td>
                        <td colspan="3" class="text-center"><b>Driver's Station</b></td>
                    </tr>
                    <tr>
                        <td class="text-center"><b>1</b></td>
                        <td class="text-center"><b>2</b></td>
                        <td class="text-center"><b>3</b></td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    
</div>

<?php } include "footer.php"; ?>