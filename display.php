<?php 
	include "header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="chooseevent"</script>';
	}
	else {
    echo '<script>$(".navbar").css("display", "none");</script>';
    echo '<script>$("html").css("min-height", "0");</script>';
    echo '<script>$("body").attr("style", "margin-bottom: 0px !important");</script>';
		$eventTeams = $currentEvent."_teams";
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventName = $row['eventname'];
		if (isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role) || isLeadInspector($role)) {
			$announcementsVisible = true;
			echo '<script>var announcementsVisible = true;</script>';
		}
		else {
			$announcementsVisible = false;
			echo '<script>var announcementsVisible = false;</script>';
		}
		
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
<!--[if IE]>
	<style>
		.scroll-left {
			display:none;
		}
	</style>
<![endif]-->
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
			
			$sqlTeams = $mysqli->query("SELECT t.teamid, t.teamname, t.schoolname, t.location, e.inspectionstatus, e.inspectionnotes, e.initial_inspector, e.last_modified_by, e.last_modified_time FROM `".$eventTeams."` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC");
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
</head>
<style>
/* Sets animation time for scroll text */
  .scroll-left h4 {
    /* Apply animation to this element */	
    -webkit-animation: scroll-left <?php echo $animationTime; ?>s linear infinite;
    animation: scroll-left <?php echo $animationTime; ?>s linear infinite;
  }
</style>
<nav class="navbar" style="z-index:100; margin-bottom:30px !important;">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="pitmap?event=<?php echo $currentEvent; ?>" class="navbar-brand"><span class="glyphicon glyphicon-chevron-left"></span>PeachPits | <?php echo $eventName; ?></a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse-1" style="margin-left:0px !important;">
            <ul class="nav navbar-nav navbar-right ">
                <li>
                    <p class="scale-title">Map Size</p>
                    <button id="1" class="btn-scale scale-up"><span class="glyphicon glyphicon-plus"></span></button>
                    <button id="1" class="btn-scale scale-down"><span class="glyphicon glyphicon-minus"></span></button></li>
                <li><button id="btn-landscape-view" class="btn btn-default btn-display-control">Landscape View</button></li>
                <li><button id="btn-portrait-view" class="btn btn-default btn-display-control">Portrait View</button></li>
				<?php if(isSuperAdmin($role) || isEventAdmin($role)) { ?> 
				<li class="dropdown"><button id="btn-refresh-time" class="btn btn-default btn-display-control" style="margin-left:10px;margin-right:10px" data-toggle="dropdown">Refresh Time <span class="caret"></span></button>
					<ul class="dropdown-menu">
					<?php
						echo '<li class="disabled"><a href="display?event='.$currentEvent.'" class="mobile-header-list mobile-current-list"><b>Current: </b>'.$refreshTime.'</a></li>';
						echo '<li role="separator" class="divider"></li>';
						foreach($refreshArr as $option) {
							if ($option != $refreshTime) {
								$optionAsInt = (int) filter_var($option, FILTER_SANITIZE_NUMBER_INT);
								echo '<li><a class="mobile-header-list" href="update_refresh_time?event='.$currentEvent.'&returnLocation=display&newRefreshTime='.$optionAsInt.'">'.$option.'</a></li>';
							}
						}
					?>
					</ul>
				</li>
				<?php } ?>
            </ul>
        </div>
    </div>      
<?php 
	if ($announcementsVisible == true) {
		echo '<div class="scroll-left" id="scroll-left">';
		if ($allAnnouncementsString == " ") {
			echo "<script>document.getElementById('scroll-left').className = 'hidden';</script>";
			echo "<h4 id='announcement-text' style='white-space:nowrap'>".$allAnnouncementsString."</h4>";
		}
		else {
			echo "<script>console.log('Number of Characters: ".$varX."');</script>";
			echo "<script>console.log('AnimationTimeMobile: ".$animationTimeMobile."');</script>";
			echo "<script>console.log('AnimationTime: ".$animationTime."');</script>";
			echo "<h4 id='announcement-text' style='white-space:nowrap'>".$allAnnouncementsString."</h4>";
		}
		echo '</div>';
	}
?>
</nav>
<div class="map-key">
    <div class="key-container"><div class="keyColor levelSix"></div><div class="key-text" style="display:inline">Complete (</div><div class="key-text" style="display:inline" id="count-complete"></div><div class="key-text" style="display:inline">)</div></div>
    <div class="key-container"><div class="keyColor levelFive"></div><div class="key-text"  style="display:inline">Minor Issue (</div><div class="key-text" style="display:inline" id="count-minor"></div><div class="key-text" style="display:inline">)</div></div>
    <div class="key-container"><div class="keyColor levelFour"></div><div class="key-text"  style="display:inline">Major Issue (</div><div class="key-text" style="display:inline" id="count-major"></div><div class="key-text" style="display:inline">)</div></div>
	<div class="key-container"><div class="keyColor levelThree"></div><div class="key-text" style="display:inline">Weighed and Sized (</div><div class="key-text" style="display:inline" id="count-weighed"></div><div class="key-text" style="display:inline">)</div></div>
    <div class="key-container"><div class="keyColor levelTwo"></div><div class="key-text"  style="display:inline">Ok to unbag (</div><div class="key-text" style="display:inline" id="count-ok"></div><div class="key-text" style="display:inline">)</div></div>
    <div class="key-container"><div class="keyColor levelOne"></div><div class="key-text"  style="display:inline">Not Started (</div><div class="key-text" style="display:inline" id="count-notstarted"></div><div class="key-text" style="display:inline">)</div></div>
</div>
<div class="container" style="padding:0px !important;">
	<div class="container-map-centered map-main">
		<div class="container-map-outer portrait-view"><div id="frame" class="container-map map-page"></div></div>
	</div>
</div>
	<section id="sponsors" style="padding: 0px !important;">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<h2>PeachPits is presented by</h2>
					<a href="http://www.automationdirect.com/adc/Home/Home"><img src="imgs/adc_logo.png" style="width:100%;" alt="Automation Direct Logo"></a>
				</div>
			</div>
		</div>
	</section>
<?php } ?>
	<script src="js/bootstrap.min.js"></script>
	<script>
        <!-- Updates the page based on the session refresh time -->
		var refreshTime = <?php echo $_SESSION['refreshTime'] ?>000;
		var refresh = window.setInterval(update, refreshTime);

        function update() {
            var teamsArr2;
			var statusCount = {};
            $.ajax({
                url: 'auto_refresh?event=<?php echo $currentEvent; ?>',
                type: 'POST',
                success: function(data) {
                    teamsArr2 = [];
                    teamsArr2 = data;
					statusCount = {'Complete': 0, 'Minor Issue': 0, 'Major Issue': 0, 'Weighed and Sized': 0, 'Ok to unbag': 0, 'Not Started': 0};
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
			                $("#" + teamsArr2[i][0]).addClass('levelSix');
							statusCount['Complete'] += 1;
		                }
		                else if (teamsArr2[i][3] == 'Minor Issue'){
			                $("#" + teamsArr2[i][0]).addClass('levelFive');
							statusCount['Minor Issue'] += 1;
		                }
						else if (teamsArr2[i][3] == 'Major Issue'){
			                $("#" + teamsArr2[i][0]).addClass('levelFour');
							statusCount['Major Issue'] += 1;
		                }
                        else if (teamsArr2[i][3] == 'Weighed and Sized'){
			                $("#" + teamsArr2[i][0]).addClass('levelThree');
							statusCount['Weighed and Sized'] += 1;
		                }
		                else if (teamsArr2[i][3] == 'Ok to unbag'){
			                $("#" + teamsArr2[i][0]).addClass('levelTwo');
							statusCount['Ok to unbag'] += 1;
		                }
		                else if (teamsArr2[i][3] == 'Not Started'){
			                $("#" + teamsArr2[i][0]).addClass('levelOne');
							statusCount['Not Started'] += 1;
		                }
	                }  
					document.getElementById('count-complete').innerHTML = statusCount['Complete'];
					document.getElementById('count-minor').innerHTML = statusCount['Minor Issue'];
					document.getElementById('count-major').innerHTML = statusCount['Major Issue'];
					document.getElementById('count-weighed').innerHTML = statusCount['Weighed and Sized'];
					document.getElementById('count-ok').innerHTML = statusCount['Ok to unbag'];
					document.getElementById('count-notstarted').innerHTML = statusCount['Not Started'];

					
						

                },
                dataType:'json'
            })
			if (announcementsVisible == true) {
				var newAnnouncements;
				var currentAnnouncements = document.getElementById('announcement-text').innerHTML;
				var newAnimationTime;
				$.ajax({
					url: 'auto_refresh_announcements?event=<?php echo $currentEvent; ?>',
					type: 'POST',
					success: function(data) {
						newAnnouncements = data[0];
						newAnimationTime = data[1];
						if (newAnnouncements == " " || newAnnouncements == "" || newAnnouncements == null || newAnnouncements == "undefined" || newAnnouncements == "&nbsp;") {
							document.getElementById('scroll-left').className = 'hidden';
						}
						else if (newAnnouncements != currentAnnouncements) {
							if (document.getElementById('scroll-left').className == 'hidden') {
								document.getElementById('scroll-left').className = 'scroll-left';
							}
							console.log(newAnimationTime);
							document.getElementById('announcement-text').innerHTML = newAnnouncements;
							document.getElementById('announcement-text').style.animationDuration = newAnimationTime + "s";
							document.getElementById('announcement-text').style.webkitTransitionDuration = newAnimationTime + "s";
						}
					},
					dataType:'json'
				})
			}
		}
		update();
    </script>
	</body>
</html>