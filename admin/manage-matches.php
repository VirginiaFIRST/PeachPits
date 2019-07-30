<title>PeachPits - Matches</title>
<?php 
	/*********************
    Allows event admins to create and edit matches
    **********************/
	include dirname(__DIR__) . "/header.php";
	 
	if(loggedOn()) {
    include "menu.php";
    $event = $currentEvent . "_matches";

    $desktopTableHead = '
      <thead>
        <td class="text-center"><b>Match #</b></td>
        <td class="text-center"><b>Start Time</b></td>
        <td class="text-center"><b>Red 1</b></td>
        <td class="text-center"><b>Red 2</b></td>
        <td class="text-center"><b>Red 3</b></td>
        <td class="text-center"><b>Blue 1</b></td>
        <td class="text-center"><b>Blue 2</b></td>
        <td class="text-center"><b>Blue 3</b></td>
        <td class="text-center"><b></b></td>
      </thead>
    ';
    $mobileTableHead = '
    <thead>
      <tr>
        <td rowspan="2" style="vertical-align:middle"><b>Match</b></td>
        <td rowspan="2" class="text-center" style="vertical-align:middle"><b>Time</b></td>
        <td colspan="3" class="text-center"><b>Driver\'s Station</b></td>
        <td rowspan="2" rowclass="text-center" class="text-center" style="vertical-align:middle"><b>Pit</b></td>
      </tr>
      <tr>
        <td class="text-center"><b>1</b></td>
        <td class="text-center"><b>2</b></td>
        <td class="text-center"><b>3</b></td>
      </tr>
    </thead>
    ';
?>
<head><script src="admin/js/manageMatches.js"></script></head>

		<div class="col-md-10 container-dashboard-content">
			<div class="dashboard-toolbar">
				<div class="container-fluid text-center">
					<button id="addMatch" class="btn btn-default">Add a Match</button>
					<a href="#" id="populate" class="btn btn-default" data-toggle="modal">Auto Fill Matches</a>
				</div>
			</div>
			<div class="container-add text-center">
				<form class="form-inline" action="/peachpits/admin/add_match?event=<?php echo $currentEvent; ?>" method="post">
					<input type="text" name="matchid" id="matchid" class="form-control" style="width:100px;" placeholder="Match Id">
					<input type="text" name="matchnumber" id="matchnumber" class="form-control" style="width:80px;" placeholder="Match #">
					<input type="text" name="setnumber" id="setnumber" class="form-control" style="width:80px;" placeholder="Set #">
					<input type="text" name="starttime" id="starttime" class="form-control" style="width:120px;" placeholder="Start Time">
					<?php $sql = $mysqli->query("SELECT t.teamid FROM `".$currentEvent."_teams` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC") ?>
					Red 1: <select name="red1" id="red1" class="form-control">
								<?php while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){ echo "<option>". $row['teamid'] ."</option>"; } ?>
								</select>
					<?php $sql = $mysqli->query("SELECT t.teamid FROM `".$currentEvent."_teams` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC") ?>
					Red 2: <select name="red2" id="red2" class="form-control">
								<?php while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){ echo "<option>". $row['teamid'] ."</option>"; } ?>
								</select>
					<?php $sql = $mysqli->query("SELECT t.teamid FROM `".$currentEvent."_teams` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC") ?>
					Red 3: <select name="red3" id="red3" class="form-control">
								<?php while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){ echo "<option>". $row['teamid'] ."</option>"; } ?>
								</select>
					<?php $sql = $mysqli->query("SELECT t.teamid FROM `".$currentEvent."_teams` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC") ?>
					Blue 1: <select name="blue1" id="blue1" class="form-control">
								<?php while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){ echo "<option>". $row['teamid'] ."</option>"; } ?>
								</select>
					<?php $sql = $mysqli->query("SELECT t.teamid FROM `".$currentEvent."_teams` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC") ?>
					Blue 2: <select name="blue2" id="blue2" class="form-control">
								<?php while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){ echo "<option>". $row['teamid'] ."</option>"; } ?>
								</select>
					<?php $sql = $mysqli->query("SELECT t.teamid FROM `".$currentEvent."_teams` AS e, teams AS t WHERE e.teamid = t.teamid ORDER BY `teamid` ASC") ?>
					Blue 3: <select name="blue3" id="blue3" class="form-control">
								<?php while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){ echo "<option>". $row['teamid'] ."</option>"; } ?>
								</select>
					Match Type: <select name="matchtype" class="form-control">
						<option>Qualification Match</option>
						<option>Quarterfinals</option>
						<option>Semifinals</option>
						<option>Finals</option>
					</select>
					<input type="hidden" name="auto" value="false">
					<input type="hidden" name="eventid" value="<?php echo $currentEvent; ?>">
					<button type="submit" class="btn btn-default" name="submit">Add</button>
					<a id="addMatch_cancel" href="#" class="btn btn-default btn-add-cancel">Cancel</a>
				</form>
			</div>
			<div class="dashboard-content">
        <?php
          $sql = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchtype` LIKE 'f' ORDER BY matchid ASC");
          $data = array();
          if (mysqli_num_rows($sql) > 0) {
            echo '<h2 class="text-center">Final Matches</h2>';
            echo '<div class="table-responsive">';
            echo '<table id="table-team-matches" class="table table-hover">';
            echo $desktopTableHead;
            while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
              $data[] = $row;
              echo "<tr id='". $row['matchid'] ."'>";
              echo "<td style='width:12%; padding-left: 15px;' class='text-center' id='matchnumber'>". $row['matchnumber'] ."</td>";
              echo "<td class='text-center' id='starttime'>". $row['start'] ."</td>";
              echo "<td id='red1' class='red text-center'>". $row['red1'] ."</td>";
              echo "<td id='red2' class='red text-center'>". $row['red2'] ."</td>";
              echo "<td id='red3' class='red text-center'>". $row['red3'] ."</td>";
              echo "<td id='blue1' class='blue text-center'>". $row['blue1'] ."</td>";
              echo "<td id='blue2' class='blue text-center'>". $row['blue2'] ."</td>";
              echo "<td id='blue3' class='blue text-center'>". $row['blue3'] ."</td>";
              echo "<td class='text-center'><a href='#' class='edit' data-toggle='modal' data-target='#editMatch'>Edit</a>";
              echo "</tr>";
            }
            echo '</table><table id="table-team-matches-mobile" class="table">';
            echo $mobileTableHead;
            foreach ($data as $row) {
              echo "<tr id='". $row['matchid'] ."'>";
              echo "<td rowspan='2' style='width:12%; padding-left: 15px; vertical-align:middle;' class='text-left' id='matchnumber'>". $row['matchnumber'] ."</td>";
              echo "<td rowspan='2' 'class='text-center' style='vertical-align:middle' id='starttime'>". $row['start'] ."</td>";
              echo "<td id='red1' class='red text-center'>". $row['red1'] ."</td>";
              echo "<td id='red2' class='red text-center'>". $row['red2'] ."</td>";
              echo "<td id='red3' class='red text-center'>". $row['red3'] ."</td>";
              echo "<td rowspan='2' style='vertical-align:middle;' class='text-center'><a href='#' class='edit' data-toggle='modal' data-target='#editMatch'>Edit</a>";
              echo "</tr>";
              echo "<tr id='". $row['matchid'] ."'>";
              echo "<td id='blue1' class='blue text-center'>". $row['blue1'] ."</td>";
              echo "<td id='blue2' class='blue text-center'>". $row['blue2'] ."</td>";
              echo "<td id='blue3' class='blue text-center'>". $row['blue3'] ."</td>";
              echo "</tr>";
            }
            echo '</table></div>';
          }

          $sql = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchtype` LIKE 'sf' ORDER BY matchid ASC");
          $data = array();
          if (mysqli_num_rows($sql) > 0) {
            echo '<h2 class="text-center">Semifinal Matches</h2>';
            echo '<div class="table-responsive">';
            echo '<table id="table-team-matches" class="table table-hover">';
            echo $desktopTableHead;
            while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
              $data[] = $row;
              echo "<tr id='". $row['matchid'] ."'>";
              $matchid = "<span class='text-nowrap'>Bracket " . $row['setnumber'] . "</span> <span class='text-nowrap'>Match " . $row['matchnumber'] . "</span>";
              echo "<td style='width:12%; padding-left: 15px;' class='text-center' id='matchnumber'>". $matchid ."</td>";
              echo "<td class='text-center' id='starttime'>". $row['start'] ."</td>";
              echo "<td id='red1' class='red text-center'>". $row['red1'] ."</td>";
              echo "<td id='red2' class='red text-center'>". $row['red2'] ."</td>";
              echo "<td id='red3' class='red text-center'>". $row['red3'] ."</td>";
              echo "<td id='blue1' class='blue text-center'>". $row['blue1'] ."</td>";
              echo "<td id='blue2' class='blue text-center'>". $row['blue2'] ."</td>";
              echo "<td id='blue3' class='blue text-center'>". $row['blue3'] ."</td>";
              echo "<td class='text-center'><a href='#' class='edit' data-toggle='modal' data-target='#editMatch'>Edit</a>";
              echo "</tr>";
            }
            echo '</table><table id="table-team-matches-mobile" class="table">';
            echo $mobileTableHead;
            foreach ($data as $row) {
              echo "<tr id='". $row['matchid'] ."'>";
              $matchid = "<span class='text-nowrap'>Bracket " . $row['setnumber'] . "</span> <span class='text-nowrap'>Match " . $row['matchnumber'] . "</span>";
              echo "<td rowspan='2' style='width:12%; padding-left: 15px; vertical-align:middle;' class='text-left' id='matchnumber'>". $matchid ."</td>";
              echo "<td rowspan='2' 'class='text-center' style='vertical-align:middle' id='starttime'>". $row['start'] ."</td>";
              echo "<td id='red1' class='red text-center'>". $row['red1'] ."</td>";
              echo "<td id='red2' class='red text-center'>". $row['red2'] ."</td>";
              echo "<td id='red3' class='red text-center'>". $row['red3'] ."</td>";
              echo "<td rowspan='2' style='vertical-align:middle;' class='text-center'><a href='#' class='edit' data-toggle='modal' data-target='#editMatch'>Edit</a>";
              echo "</tr>";
              echo "<tr id='". $row['matchid'] ."'>";
              echo "<td id='blue1' class='blue text-center'>". $row['blue1'] ."</td>";
              echo "<td id='blue2' class='blue text-center'>". $row['blue2'] ."</td>";
              echo "<td id='blue3' class='blue text-center'>". $row['blue3'] ."</td>";
              echo "</tr>";
            }
            echo '</table></div>';
          }
          
          $sql = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchtype` LIKE 'qf' ORDER BY matchid ASC");
          $data = array();
          if (mysqli_num_rows($sql) > 0) {
            echo '<h2 class="text-center">Quarterfinal Matches</h2>';
            echo '<div class="table-responsive">';
            echo '<table id="table-team-matches" class="table table-hover">';
            echo $desktopTableHead;
            while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
              $data[] = $row;
              echo "<tr id='". $row['matchid'] ."'>";
              $matchid = "<span class='text-nowrap'>Bracket " . $row['setnumber'] . "</span> <span class='text-nowrap'>Match " . $row['matchnumber'] . "</span>";
              echo "<td style='width:12%; padding-left: 15px;' class='text-center' id='matchnumber'>". $matchid ."</td>";
              echo "<td class='text-center' id='starttime'>". $row['start'] ."</td>";
              echo "<td id='red1' class='red text-center'>". $row['red1'] ."</td>";
              echo "<td id='red2' class='red text-center'>". $row['red2'] ."</td>";
              echo "<td id='red3' class='red text-center'>". $row['red3'] ."</td>";
              echo "<td id='blue1' class='blue text-center'>". $row['blue1'] ."</td>";
              echo "<td id='blue2' class='blue text-center'>". $row['blue2'] ."</td>";
              echo "<td id='blue3' class='blue text-center'>". $row['blue3'] ."</td>";
              echo "<td class='text-center'><a href='#' class='edit' data-toggle='modal' data-target='#editMatch'>Edit</a>";
              echo "</tr>";
            }
            echo '</table><table id="table-team-matches-mobile" class="table">';
            echo $mobileTableHead;
            foreach ($data as $row) {
              echo "<tr id='". $row['matchid'] ."'>";
              $matchid = "<span class='text-nowrap'>Bracket " . $row['setnumber'] . "</span> <span class='text-nowrap'>Match " . $row['matchnumber'] . "</span>";
              echo "<td rowspan='2' style='width:12%; padding-left: 15px; vertical-align:middle;' class='text-left' id='matchnumber'>". $matchid ."</td>";
              echo "<td rowspan='2' 'class='text-center' style='vertical-align:middle' id='starttime'>". $row['start'] ."</td>";
              echo "<td id='red1' class='red text-center'>". $row['red1'] ."</td>";
              echo "<td id='red2' class='red text-center'>". $row['red2'] ."</td>";
              echo "<td id='red3' class='red text-center'>". $row['red3'] ."</td>";
              echo "<td rowspan='2' style='vertical-align:middle;' class='text-center'><a href='#' class='edit' data-toggle='modal' data-target='#editMatch'>Edit</a>";
              echo "</tr>";
              echo "<tr id='". $row['matchid'] ."'>";
              echo "<td id='blue1' class='blue text-center'>". $row['blue1'] ."</td>";
              echo "<td id='blue2' class='blue text-center'>". $row['blue2'] ."</td>";
              echo "<td id='blue3' class='blue text-center'>". $row['blue3'] ."</td>";
              echo "</tr>";
            }
            echo '</table></div>';
          }
        ?>
                <h2 class="text-center">Qualifying Matches</h2>
                <div class="table-responsive">
                    <table id="table-team-matches" class="table table-hover">
			            <?php 
                        //Goes to the databse and fetches all matches in order
                        $sql = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchtype` LIKE 'qm' ORDER BY matchnumber ASC");
                        echo $desktopTableHead;
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
                            echo "<td class='text-center'><a href='#' class='edit' data-toggle='modal' data-target='#editMatch'>Edit</a>";
                            echo "</tr>";
                        }	
                        ?>
		            </table>
		            <table id="table-team-matches-mobile" class="table">
			            <?php 
                        //Goes to the databse and fetches all matches in order
                        $sql = $mysqli->query("SELECT * FROM `".$event."` WHERE `matchtype` LIKE 'qm' ORDER BY matchnumber ASC");
                        echo $mobileTableHead;
                        while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
                            echo "<tr id='". $row['matchid'] ."'>";
                            echo "<td rowspan='2' style='width:8%; padding-left: 15px; vertical-align:middle;' class='text-left' id='matchnumber'>". $row['matchnumber'] ."</td>";
                            echo "<td rowspan='2' 'class='text-center' style='vertical-align:middle' id='starttime'>". $row['start'] ."</td>";
                            echo "<td id='red1' class='red text-center'>". $row['red1'] ."</td>";
                            echo "<td id='red2' class='red text-center'>". $row['red2'] ."</td>";
                            echo "<td id='red3' class='red text-center'>". $row['red3'] ."</td>";
                            echo "<td rowspan='2' style='vertical-align:middle;' class='text-center'><a href='#' class='edit' data-toggle='modal' data-target='#editMatch'>Edit</a>";
                            echo "</tr>";
                            echo "<tr id='". $row['matchid'] ."'>";
                            echo "<td id='blue1' class='blue text-center'>". $row['blue1'] ."</td>";
                            echo "<td id='blue2' class='blue text-center'>". $row['blue2'] ."</td>";
                            echo "<td id='blue3' class='blue text-center'>". $row['blue3'] ."</td>";
                            echo "</tr>";
                        }	
                        ?>
		            </table>
	            </div>
			</div>
		</div>
	</div>
</div>	

<!-- Popup for editing an existing match -->
<div class="modal fade" id="editMatch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Match Info</h4>
      </div>
      <div class="modal-body text-center">
        <form action="/peachpits/admin/edit_match?event=<?php echo $currentEvent; ?>" method="post">
            <b>Match Number: </b><p id="matchnumbermodal"></p>
			<input type="text" name="starttime" id="starttimemodal" class="form-control" placeholder="Start Time"><br/>
      <p class="pull-left" style="margin-bottom:0px">Red Team 1:</p>
			<input type="text" name="red1" id="red1modal" class="form-control" placeholder="Red Team 1"><br/>
      <p class="pull-left" style="margin-bottom:0px">Red Team 2:</p>
			<input type="text" name="red2" id="red2modal" class="form-control" placeholder="Red Team 2"><br/>
      <p class="pull-left" style="margin-bottom:0px">Red Team 3:</p>
			<input type="text" name="red3" id="red3modal" class="form-control" placeholder="Red Team 3"><br/>
      <p class="pull-left" style="margin-bottom:0px">Blue Team 1:</p>
			<input type="text" name="blue1" id="blue1modal" class="form-control" placeholder="Blue Team 1"><br/>
      <p class="pull-left" style="margin-bottom:0px">Blue Team 2:</p>
			<input type="text" name="blue2" id="blue2modal" class="form-control" placeholder="Blue Team 2"><br/>
      <p class="pull-left" style="margin-bottom:0px">Blue Team 3:</p>
			<input type="text" name="blue3" id="blue3modal" class="form-control" placeholder="Blue Team 3"><br/>
			<input type="hidden" name="matchid" id="matchidmodal">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-default" name="submit">Edit</button></form>
      </div>
    </div>
  </div>
</div>		

<!-- Processing Request Popup -->
<div class="modal fade" id="processing-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="processing-modal-title">Processing...</h3>
      </div>
      <div class="modal-body text-center">
        <h4 id="modal-body-text">Please do not leave or refresh the page while the matches are added...</h4>
		<hr>
		<h4>Progress:</h4>
		<h4><span id="current-matches">0</span>/<span id="total-matches">0</span></h4>
		<div class="progress center-block" style="width:75%;height:25px">
			<div class="progress-bar progress-bar-striped progress-bar-warning active" role="progressbar" id="progressbar" style="width:0%" aria-valuenow="0" ariavalue-min="0" ariavalue-max="100"></div>
		</div>
      </div>
	  <div class="modal-footer hidden" id="processing-modal-footer">
        <button class="btn btn-default pull-right" onclick="location.reload()">Refresh</button>
      </div>
    </div>
  </div>
</div>	
	
<?php 
	} else { echo '<script>document.location.href="signin"</script>'; }
	
	include dirname(__DIR__) . "/footer.php"; 
?>