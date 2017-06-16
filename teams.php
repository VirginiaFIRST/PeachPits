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
	<div class="table-responsive table-teams">
		<table class="table table-hover sortable">
			<thead>
				<th><strong>Team #</strong></th>
				<th><strong>Team Name</strong></th>
				<th><strong>Status</strong></th>
				<th style="width:5%" class="text-center"><strong>Pit</strong></th>
				<th style="width:5%" class="text-center"><strong>More</strong></th>
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
					echo '<td style="width:5%" class="text-center" id="pitmap"><a href="pitmap.php?event='.$currentEvent. '&team='. $row['teamid'] .'"><span class="glyphicon glyphicon-map-marker"></span></a></td>';
					echo "<td style='width:5%;' class='text-center btn-teaminfo' id='". $row["teamid"] ."'><span class='glyphicon glyphicon-triangle-bottom'</span></td>";
					echo "</tr>";

          echo '<tr style="display: none" class="team-info" id="info-'. $row['teamid'] .'">';?>

          <td colspan="5">
            <div class="team-tab">
                <input type="button" style="width: 50%" class="tablinks" id="tabinfo" onclick="openTab(event, 'teaminfo')" value="Team Info">
                <input type="button" style="width: 50%" class="tablinks" id="tabmatches" onclick="openTab(event, 'teammatches')" value="Matches">
            </div>
            <div id="teaminfo" class="tabcontent">
                <h4><b>Location: </b></h4><p class="pull-left map-teamlocation"></p>
                <div class="clearfix"></div>
                <h4><b>School Name: </b></h4><p class="map-schoolname" style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 8; -webkit-box-orient: vertical;"></p>
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
                            <td class="text-center"><b>Red 1</b></td>
                            <td class="text-center"><b>Red 2</b></td>
                            <td class="text-center"><b>Red 3</b></td>
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
          </td>
          <?php echo '</tr>';
				}	
			?>
			</tbody>
		</table>

    <script>
    function openTab(evt, tab) {
    // Declare all variables
    var i, tabcontent, tablinks;
    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
        tablinks[i].id
    }
    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tab).style.display = "block";
    evt.currentTarget.className += " active";
}
    $('.btn-teaminfo').on('click', function(){
      var id = this.id;
      
      if($('#info-' + id).css('display') == "none"){
        $('.team-info').css('display', 'none');
        $('#info-' + id).css('display', 'table-row');
        $('#tabinfo-' + id).addClass('active');
        $('#tabmatches-' + id).removeClass('active');
        $('#teaminfo-' + id).css('display', 'block');
        $('#teammatches-' + id).css('display', 'none');

      } else {
        
        $('#info-' + id).css('display', "none");
      }
      
    });
    
    </script>
	</div>
</div>


<?php } include "footer.php"; ?>