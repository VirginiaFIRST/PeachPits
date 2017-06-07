<?php 
	include "header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="chooseevent.php"</script>';
	}
	else {
		$event = $currentEvent."_teams";
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventName = $row['eventname'];
		
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
    <script>
        var refresh = window.setInterval(update, 15000);

        function update() {
            var teamsArr2;
            $.ajax({
                url: 'auto-refresh.php?event=<?php echo $currentEvent; ?>',
                type: 'POST',
                success: function(data) {
                    teamsArr2 = [];
                    teamsArr2 = data;
                    //console.log('refresh');
                    //console.log(teamsArr2);
                    for (var i=0; i < teamsArr2.length; i++){
                        //console.log(teamsArr2[i][0] + ": " + teamsArr2[i][3]);
                        $("#" + teamsArr2[i][0]).removeClass('levelFive');
                        $("#" + teamsArr2[i][0]).removeClass('levelFour');
                        $("#" + teamsArr2[i][0]).removeClass('levelThree');
                        $("#" + teamsArr2[i][0]).removeClass('levelTwo');
                        $("#" + teamsArr2[i][0]).removeClass('levelOne');
                        $("#" + teamsArr2[i][0]).removeClass('levelSix');
                        $("#" + teamsArr2[i][0]).removeClass('levelSeven');
		                if (teamsArr2[i][3] == 'Complete'){
			                $("#" + teamsArr2[i][0]).addClass('levelFive');
		                }
		                else if (teamsArr2[i][3] == 'Major Issue'){
			                $("#" + teamsArr2[i][0]).addClass('levelFour');
		                }
		                else if (teamsArr2[i][3] == 'Minor Issue'){
			                $("#" + teamsArr2[i][0]).addClass('levelThree');
		                }
                        else if (teamsArr2[i][3] == 'In Progress'){
			                $("#" + teamsArr2[i][0]).addClass('levelSix');
		                }
                        else if (teamsArr2[i][3] == 'Weighed and Sized'){
			                $("#" + teamsArr2[i][0]).addClass('levelSeven');
		                }
		                else if (teamsArr2[i][3] == 'Ok to unbag'){
			                $("#" + teamsArr2[i][0]).addClass('levelTwo');
		                }
		                else if (teamsArr2[i][3] == 'Not Started'){
			                $("#" + teamsArr2[i][0]).addClass('levelOne');
		                }
	                }  
                },
                dataType:'json'
            })
        }
    </script>
</head>
<nav class="navbar" style="z-index:100; margin-bottom:60px !important;">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="pitmap.php?event=<?php echo $currentEvent; ?>" class="navbar-brand"><span class="glyphicon glyphicon-chevron-left"></span>PeachPits | <?php echo $eventName; ?></a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse-1" style="margin-left:0px !important;">
            <ul class="nav navbar-nav navbar-right ">
                <li>
                    <p class="scale-title">Map Size</p>
                    <button id="1" class="btn-scale scale-up"><span class="glyphicon glyphicon-plus"></span></button>
                    <button id="1" class="btn-scale scale-down"><span class="glyphicon glyphicon-minus"></span></button></li>
                <li><button id="btn-landscape-view" class="btn btn-default btn-display-control">Landscape View</button></li>
                <li><button id="btn-portrait-view" class="btn btn-default btn-display-control">Portrait View</button></li>
            </ul>
        </div>
    </div>      
</nav>
<div class="map-key">
    <div class="key-container"><div class="keyColor levelFive"></div><div class="key-text">Complete</div></div>
    <div class="key-container"><div class="keyColor levelFour"></div><div class="key-text">Major Issue</div></div>
    <div class="key-container"><div class="keyColor levelThree"></div><div class="key-text">Minor Issue</div></div>
    <div class="key-container"><div class="keyColor levelSix"></div><div class="key-text">In Progress</div></div>
    <div class="key-container"><div class="keyColor levelSeven"></div><div class="key-text">Weighed and Sized</div></div>
    <div class="key-container"><div class="keyColor levelTwo"></div><div class="key-text">Ok to unbag</div></div>
    <div class="key-container"><div class="keyColor levelOne"></div><div class="key-text">Not Started</div></div>
</div>
<div class="container" style="padding:0px !important;">
	<div class="container-map-centered map-main">
		<div class="container-map-outer portrait-view"><div id="frame" class="container-map map-page"></div></div>
	</div>
</div>
	<section id="sponsors">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<h2>PeachPits is presented by</h2>
					<a href="http://www.automationdirect.com/adc/Home/Home"><img src="adc_logo.png" style="width:100%;"></a>
				</div>
			</div>
		</div>
	</section>
<?php } ?>
	<script src="js/bootstrap.min.js"></script>
	</body>
</html>