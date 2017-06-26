<title>PeachPits - Teams</title>
<?php 
	include "header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="chooseevent"</script>';
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
	<div class="table-teams">
		<table class="table">
			<thead>
				<th style="width:15%"><strong>Team #</strong></th>
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
					echo '<td style="width:5%" class="text-center" id="pitmap"><a href="pitmap?event='.$currentEvent. '&team='. $row['teamid'] .'"><span class="glyphicon glyphicon-map-marker"></span></a></td>';
					echo "<td style='width:5%;' class='text-center btn-teaminfo' id='". $row["teamid"] ."'><span class='glyphicon glyphicon-triangle-bottom closed'</span></td>";
					echo "</tr>";
          echo '<tr style="display: none;" class="team-info" id="info-'. $row['teamid'] .'">';?>
          <td colspan="5">
            <div class="team-tab">
                <?php echo'<input type="button" style="width: 50%" class="tablinks" id="tabinfo-'. $row['teamid'] .'" onclick="openTab(event, \'teaminfo-\' + '. $row['teamid'] .')" value="Team Info">';?>
                <?php echo'<input type="button" style="width: 50%" class="tablinks" id="tabmatches-'. $row['teamid'] .'" onclick="openTab(event, \'teammatches-\' + '. $row['teamid'] .')" value="Matches">';?>
            </div>
            <?php echo'<div id="teaminfo-'. $row['teamid'] .'" class="tabcontent">';?>
                <?php echo'<h4><b>Location: </b></h4><p class="pull-left map-teamlocation">'. $row['location'] .'</p>';?>
                <div class="clearfix"></div>
                <?php echo'<h4><b>School Name: </b></h4><div class="map-schoolname" style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 8; -webkit-box-orient: vertical;">'. $row['schoolname'] .'</div>';?>
            </div>
            <?php echo'<div id="teammatches-'. $row['teamid'] .'" class="tabcontent">';?>
              <?php echo'<div class="team-eventname"><h3>Match Schedule: ' . $eventname . '</h3></div>';?>
                <?php echo'<table id="table-team-matches-'. $row['teamid'] .'" class="table table-team-matches" style="margin-top:20px;">';?>
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
                <?php echo'<table id="table-team-matches-mobile-'. $row['teamid'] .'" class="table table-team-matches-mobile">';?>
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
      var i, id, tabcontent, tablinks;
      // Get all elements with class="tabcontent" and hide them
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
          tabcontent[i].style.display = "none";
      }
      // Get all elements with class="tablinks" and remove the class "active"
      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
          tablinks[i].className = tablinks[i].className.replace(" active", "");
      }
      // Show the current tab, and add an "active" class to the button that opened the tab
      evt.currentTarget.className += " active";
      document.getElementById(tab).style.display = "block";
      id = tab.substring(12);
      
    }
    $('.btn-teaminfo').on('click', function(){
      var id = this.id;
      $('.teams').css('table-layout', 'auto');


      $('#table-team-matches-mobile-' + id + ' .matchrow').remove();
      $('#table-team-matches-' + id + ' .matchrow').remove();
      var teamSchd;
      $.ajax({
        url: 'getTeamSchedule?event=' + currentEvent + '&team=' + id,
        type: 'POST',
        success: function (data) {
          teamSchd = [];
          teamSchd = data;
          for (var i = 0; i < teamSchd.length; i++) {
            //Mobile Table
            var row = '';
            row = '<tr class="matchrow"><td rowspan="2" id="matchnumber">' + teamSchd[i]['matchnumber'] + '</td><td rowspan="2" id="starttime">' + teamSchd[i]['start'] + '</td>';
            if (id == teamSchd[i]['red1']) { row = row + '<td id="red1" class="red text-center"><b>' + teamSchd[i]['red1'] + '</b></td>' } else { row = row + '<td id="red1" class="red text-center">' + teamSchd[i]['red1']+'</td>' }
            if (id == teamSchd[i]['red2']) { row = row + '<td id="red2" class="red text-center"><b>' + teamSchd[i]['red2'] + '</b></td>' } else { row = row + '<td id="red2" class="red text-center">' + teamSchd[i]['red2'] + '</td>' }
            if (id == teamSchd[i]['red3']) { row = row + '<td id="red3" class="red text-center"><b>' + teamSchd[i]['red3'] + '</b></td>' } else { row = row + '<td id="red3" class="red text-center">' + teamSchd[i]['red3'] + '</td>' }
            row = row + '</tr><tr class="matchrow">';
            if (id == teamSchd[i]['blue1']) { row = row + '<td id="blue1" class="blue text-center"><b>' + teamSchd[i]['blue1'] + '</b></td>' } else { row = row + '<td id="blue1" class="blue text-center">' + teamSchd[i]['blue1'] + '</td>' }
            if (id == teamSchd[i]['blue2']) { row = row + '<td id="blue2" class="blue text-center"><b>' + teamSchd[i]['blue2'] + '</b></td>' } else { row = row + '<td id="blue2" class="blue text-center">' + teamSchd[i]['blue2'] + '</td>' }
            if (id == teamSchd[i]['blue3']) { row = row + '<td id="blue3" class="blue text-center"><b>' + teamSchd[i]['blue3'] + '</b></td>' } else { row = row + '<td id="blue3" class="blue text-center">' + teamSchd[i]['blue3'] + '</td>' }
            row = row + '</tr>';
            $('#table-team-matches-mobile-' + id).append(row);
            //Desktop Table
            var row = '';
            row = '<tr class="matchrow"><td id="matchnumber" class="text-center">' + teamSchd[i]['matchnumber'] + '</td><td id="starttime" class="text-center">' + teamSchd[i]['start'] + '</td>';
            if (id == teamSchd[i]['red1']) { row = row + '<td id="red1" class="red text-center"><b>' + teamSchd[i]['red1'] + '</b></td>' } else { row = row + '<td id="red1" class="red text-center">' + teamSchd[i]['red1'] + '</td>' }
            if (id == teamSchd[i]['red2']) { row = row + '<td id="red2" class="red text-center"><b>' + teamSchd[i]['red2'] + '</b></td>' } else { row = row + '<td id="red2" class="red text-center">' + teamSchd[i]['red2'] + '</td>' }
            if (id == teamSchd[i]['red3']) { row = row + '<td id="red3" class="red text-center"><b>' + teamSchd[i]['red3'] + '</b></td>' } else { row = row + '<td id="red3" class="red text-center">' + teamSchd[i]['red3'] + '</td>' }
            if (id == teamSchd[i]['blue1']) { row = row + '<td id="blue1" class="blue text-center"><b>' + teamSchd[i]['blue1'] + '</b></td>' } else { row = row + '<td id="blue1" class="blue text-center">' + teamSchd[i]['blue1'] + '</td>' }
            if (id == teamSchd[i]['blue2']) { row = row + '<td id="blue2" class="blue text-center"><b>' + teamSchd[i]['blue2'] + '</b></td>' } else { row = row + '<td id="blue2" class="blue text-center">' + teamSchd[i]['blue2'] + '</td>' }
            if (id == teamSchd[i]['blue3']) { row = row + '<td id="blue3" class="blue text-center"><b>' + teamSchd[i]['blue3'] + '</b></td>' } else { row = row + '<td id="blue3" class="blue text-center">' + teamSchd[i]['blue3'] + '</td>' }
            row = row + '</tr>';
            $('#table-team-matches-' + id).append(row);
          }
        },
        dataType: 'json'
      });


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
    <style>
    .table-team-matches {
      display: table;
      border: 1px solid lightgray;
      padding: 10px;
    }
    
    .table-team-matches-mobile {
      display: none;
    }
    
    .team-matches-none {
      display: block;
    }
    
    .team-matches-none-mobile {
      display: none;
    }
    
    @media screen and (max-width: 768px) {
      .table-team-matches {
        display: none;
      }
      
      .table-team-matches-mobile {
        word-wrap: break-word;
        display: table;
      }
      
      .team-matches-none {
        display: none;
      }
      
      .team-matches-none-mobile {
        display: block;
      }
    }    
    </style>
	</div>
</div>


<?php } include "footer.php"; ?>