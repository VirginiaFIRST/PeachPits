<title>PeachTalk - Settings</title>
<?php 
	include dirname(__DIR__) . "/header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="/peachpits/chooseevent"</script>';
	}
	else {
		if (!loggedOn()) {
			echo '<script>window.location="/peachpits/peachtalk/peachtalk-home?event='.$currentEvent.'"</script>';
		}
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventname = $row['eventname'];
		$userArr = explode(";", $peachtalkUsername);
        $userEmail = $userArr[0];
        $userTeam = $userArr[1];
        $userId = $userArr[3];
		$userRestrictions = $userArr[4];
?>

<div class="header-btn-container text center">
    <div class="container">
      <div class="row">
	  		<table width="100%">
                <tr>
                    <td id="back-btn-cell" style="padding-left:15px;width:10%">
                        <a class="pull-left btn btn-default back-btn" id="back-btn" href="/peachpits/peachtalk/peachtalk-home?event=<?php echo $currentEvent; ?>"><span class="glyphicon glyphicon-chevron-left"></span><span id="back-btn-text"> Back</span></a>
                    </td>
                    <td class="text-center">
                        <h4 class="channel-header-text">Settings</h4>
                    </td>
                    <td style="padding-right:15px;width:10%" id="right-cell"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="container content text-center" style="margin-top:20px">
	<?php 
		if ($peachtalkUsername != "none") {
			if ($userRestrictions != "none") {
				$restrictionsArr = array();
				if (strpos($userRestrictions, "Parts") !== false) {
					$restrictionsArr[] = "Parts";
				}
				if (strpos($userRestrictions, "Safety") !== false) {
					$restrictionsArr[] = "Safety";
				}
				if (strpos($userRestrictions, "Private") !== false) {
					$restrictionsArr[] = "Private Message";
				}
				if (count($restrictionsArr) > 1) {
					$last = end($restrictionsArr);
					array_pop($restrictionsArr);
					$restrictionsStr = implode(", ", $restrictionsArr) . " and " . $last;
					echo '<div class="alert alert-danger"><h4>Alert: You have been restricted from sending messages in the '.$restrictionsStr.' channels.</h4></div>';
				}
				else {
					echo '<div class="alert alert-danger"><h4>Alert: You have been restricted from sending messages in the '.end($restrictionsArr).' channel.</h4></div>';
				}
			} 
		echo '<h4><b>Team:</b> '.$userTeam.'</h4>';
		echo '<h4><b>Event:</b> '.$eventname.'</h4>';
		
		}
		else {
			//for users logged in who are not communication liaisons
			echo '<h4><b>Event:</b> '.$eventname.'</h4>';			
		}
	?>
	<br>
	<br>
	<button class="btn btn-default" data-toggle="modal" data-target="#read-terms-of-use">View Terms of Use</button>
	<br>
	<br>
	<a class="btn btn-danger" href="signout">Sign Out</a>
</div>
<script>
	if (window.innerWidth <= 500 && document.getElementById('back-btn-text').style.display != 'none') {
        document.getElementById('back-btn').classList.add('back-btn-minimized');
        document.getElementById('back-btn-text').style.display = 'none';
        document.getElementById('back-btn-cell').style.width = 28;
        document.getElementById('right-cell').style.width = 28;
    }
    else {
        document.getElementById('back-btn-cell').style.width = 90;
        document.getElementById('right-cell').style.width = 90;
    }
    $(window).resize(function () {
        if (window.innerWidth <= 500 && document.getElementById('back-btn-text').style.display != 'none') {
            document.getElementById('back-btn').classList.add('back-btn-minimized');
            document.getElementById('back-btn-text').style.display = 'none';
            document.getElementById('back-btn-cell').style.width = 28;
            document.getElementById('right-cell').style.width = 28;
        }
        else if (window.innerWidth > 500 && document.getElementById('back-btn-text').style.display == 'none') {
            document.getElementById('back-btn').classList.remove('back-btn-minimized');
            document.getElementById('back-btn-text').style.display = 'initial';
            document.getElementById('back-btn-cell').style.width = 90;
            document.getElementById('right-cell').style.width = 90;
        }
    });
</script>

<!-- PeachTalk Terms of Use Modal -->
<div class="modal fade" id="read-terms-of-use" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	  <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    	<h3 style="display:inline" class="modal-title" id="myModalLabel">Terms of Use</h3>
      </div>
      <div class="modal-body">
            <ol>
                <li>GAFIRST owns all messaging.</li>
                <li>GAFIRST will maintain an archive of all messages.</li>
                <li>Users will be deleted if deemed inappropriate.</li>
				<li>Users will be restricted from sending messages on specified messaging channels if deemed inappropriate.</li>
				<li>Messages will be deleted if deemed inappropriate.</li>
                <li>Users must practice Gracious Professionalism at all times.</li>
                <li>GAFIRST will NOT use users' contact information for anything other than GAFIRST business.</li>
            </ol>
      </div>
      <div class="modal-footer">
            <button class="btn btn-default" id="submit-agree-form" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php } include dirname(__DIR__) . "/footer.php"; ?>