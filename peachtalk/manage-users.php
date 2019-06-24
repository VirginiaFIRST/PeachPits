<title>PeachTalk - Users</title>
<?php 
	include dirname(__DIR__) . "/header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="/peachpits/chooseevent"</script>';
	}
	else {
        if (!(isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role))) {
            echo '<script>window.location="/peachpits/peachtalk/peachtalk-home?event='.$currentEvent.'"</script>';
        }
        else {
            echo '<script>var role = "'.$role.'";</script>';
        }
        echo '<script>var username = "'.$peachtalkUsername.'";</script>';
        
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventname = $row['eventname'];
        $eventActivity = $currentEvent . "_activity";
        $eventLiaisons = $currentEvent . "_liaisons";
        $eventTeams = $currentEvent . "_teams";
        $teamList = array();
        $sql = $mysqli->query("SELECT * FROM `".$eventTeams."`");
        while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
            $teamList[] = $row['teamid'];
        }
        $currentDate = new DateTime(null, new DateTimeZone('America/New_York'));
?>

<script src="peachtalk/js/manageUsers.js"></script>
<div class="page-head" style="margin-bottom:0px">
	<div class="container">
		<h1>PeachTalk for <?php echo $eventname; ?></h1>
	</div>
</div>
<div class="header-btn-container text center">
    <div class="container">
        <div class="row">
            <table width="100%">
                <tr>
                    <td id="back-btn-cell" style="padding-left:15px;width:10%">
                        <a class="pull-left btn btn-default back-btn" id="back-btn" href="/peachpits/peachtalk/peachtalk-home?event=<?php echo $currentEvent; ?>"><span class="glyphicon glyphicon-chevron-left"></span><span id="back-btn-text"> Back</span></a>
                    </td>
                    <td class="text-center">
                        <a class="btn btn-default" id="add-team" href="/peachpits/admin/manage-teams?event=<?php echo $currentEvent; ?>">Add a Team</a>
                    </td>
                    <td style="padding-right:15px;width:10%" id="right-cell"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="container content" style="margin-top:20px">
    <div class="table-responsive">
		<table style="border: 1px solid #ddd" class="table table-hover sortable">
			<thead style="background-color:white;border-top:none">
				<th><strong>User</strong></th>
                <th style="width:10%"><strong>Team #</strong></th>
				<th><strong>Last Active</strong></th>
				<th style="width:10%" class="text-center"><strong>Message User</strong></th>
                <th style="width:10%" class="text-center"><strong>Message Team</strong></th>
                <th style="width:10%" class="text-center"><strong>Inhibit User</strong></th>
				<th style="width:10%" class="text-center"><strong>Delete User</strong></th>
				<th style="width:10%" class="text-center"><strong>More Info</strong></th>
			</thead>
			<tbody>
				<?php 
					//Fetches all teams in order from the database
					$sql = $mysqli->query("SELECT * FROM `".$eventLiaisons."` WHERE `status`='Approved' ORDER BY `teamid` ASC");	
					while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                        $teamid = $row['teamid'];
                        $email = $row['email'];
						echo '<tr>';
                        echo '<td id="user">'.$row['user'].'</td>';
						echo '<td id="teamid">'.$teamid.'</td>';
                        $sql2 = $mysqli->query("SELECT * FROM `".$eventActivity."` WHERE `user` = '$email'");
                        $lastActive = "";
                        while ($row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH)) {
                            if ($lastActive == "") {
                                $lastActive = $row2['last_visited'];
                            }
                            else {
                                if ($lastActive < $row2['last_visited']) {
                                    $lastActive = $row2['last_visited'];
                                }
                            }
                        }
                        if ($lastActive == "" || $lastActive == '2018-01-01 00:00:00') {
                            echo '<td>Never</td>';
                        }
                        else {
                            $last = new DateTime($lastActive, new DateTimeZone('America/New_York'));
                            $timeDiff = date_diff($last, $currentDate);
                            $days = $timeDiff->format('%a');
                            $hours = $timeDiff->format('%h');
                            $minutes = $timeDiff->format('%i');
                            $output = "";
                            if ($days != 0) {
                                $output = $output . $days . " day";
                                if ($days > 1) {
                                    $output .= "s ";
                                }
                                else {
                                    $output .= " ";
                                }
                            }
                            if ($hours != 0) {
                                $output = $output . $hours . " hour";
                                if ($hours > 1) {
                                    $output .= "s ";
                                }
                                else {
                                    $output .= " ";
                                }
                            }
                            if ($minutes != 0) {
                                $output = $output . $minutes . " minute";
                                if ($minutes > 1) {
                                    $output .= "s ";
                                }
                                else {
                                    $output .= " ";
                                }
                            }
                            if ($output == "") {
                                $output = "Now";
                            }
                            else {
                                $output .= "ago";
                            }
						    echo '<td>'.$output.'</td>';
                        }
						echo '<td style="width:10%" class="text-center">';
                        echo '<form id="messageUserForm" action="/peachpits/peachtalk/new_message.php?event='.$currentEvent.'" method="post" style="margin:0px">';
                        echo '<input type="hidden" name="username" value="'.$peachtalkUsername.'">';
                        echo '<input type="hidden" name="users" value="'.$row['userid'].'">';
                        echo '<button class="blue-link" style="background-color:transparent;border:none" type="submit"><span class="glyphicon glyphicon-comment"></span></button></form></td>';
						if (in_array($teamid, $teamList)) {
                            $teamList = array_diff($teamList, [$teamid]);
                        }
                        echo '<td style="width:10%" class="text-center">';
                        echo '<form id="messageUserForm" action="/peachpits/peachtalk/new_message.php?event='.$currentEvent.'" method="post" style="margin:0px">';
                        echo '<input type="hidden" name="username" value="'.$peachtalkUsername.'">';
                        echo '<input type="hidden" name="teams" value="'.$teamid.'">';
                        echo '<button class="blue-link" style="background-color:transparent;border:none" type="submit"><span class="glyphicon glyphicon-comment"></span></button></form></td>';
                        echo '<td style="width:10%" class="text-center"><a id="inhibit-user" style="text-decoration:none" href="#" data-toggle="modal" data-target="#inhibit-user-modal"><span class="glyphicon glyphicon-ban-circle" style="color:red"></span></a></td>';
                        echo '<td style="width:10%" class="text-center"><a id="delete-user" style="text-decoration:none" href="#" data-toggle="modal" data-target="#delete-user-modal"><span style="font-size:30px;color:red;line-height:18px">&times;</span></a></td>';
						echo '<td style="width:10%" class="text-center"><span class="glyphicon glyphicon-triangle-bottom btn-teaminfo closed" id="'.$row['userid'].'" style="cursor:pointer"></span></td>';
						echo '<td class="hidden" id="userid">'.$row['userid'].'</td>';
                        echo '<td class="hidden" id="restrictions">'.$row['restrictions'].'</td>';
                        echo '</tr>';
                        echo '<tr style="display: none;" class="user-info" id="info-'.$row['userid'].'">';
                        echo '<td colspan="8">';
                        echo '<div id="userinfo-'.$row['userid'].'" class="tabcontent">';
                        echo '<h4><b>User: </b></h4><p>'.$row['user'].'</p><p><span style="text-decoration:underline">Phone Number:</span> '.$row['cell'].'</p>';
                        echo '<div class="clearfix"></div>';
                        //echo '<h4><b>Lead Mentor: </b></h4><p">'.$row['leadmentor_name'].'</p><p><span style="text-decoration:underline">Phone Number:</span> '.$row['leadmentor_cell'].'</p>';
                        echo '</div>';
                        echo '</td>';
                        echo '</tr>';
					}
                    $sql = $mysqli->query("SELECT * FROM `".$eventTeams."`");
                    while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                        if (in_array($row['teamid'], $teamList)) {
                            echo '<tr>';
                            echo '<td>-----</td>';
                            echo '<td id="'.$row['teamid'].'">'.$row['teamid'].'</td>';
                            echo '<td>Never</td>';
                            echo '<td style="width:10%" class="text-center"><span class="glyphicon glyphicon-comment"></span></td>';                             
                            echo '<td style="width:10%" class="text-center"><span class="glyphicon glyphicon-comment"></span></td>';
                            echo '<td style="width:10%" class="text-center"><span class="glyphicon glyphicon-ban-circle"></span></td>';
                            echo '<td style="width:10%" class="text-center"><span style="font-size:30px;line-height:18px">&times;</span></td>';
                            echo '<td></td>';
                            echo '</tr>';
                        }
                    }
				?>
            </tbody>
        </table>
    </div>
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
    function messageUser(elem) {
        var userid = elem.id;
        var users = role + ';' + userid;
        console.log("Users: " + users);
        console.log("Username: " + username);
        $.post('/peachpits/peachtalk/new_message.php?event=' + currentEvent, {
            users: users,
            username: username
        }, function() {
            //window.location = '/peachpits/peachtalk/new_message.php?event=' + currentEvent;
        });
    }
    function messageTeam(elem) {
        var teamid = elem.id;
        var teams = role + ';' + teamid;
        $.post('/peachpits/peachtalk/new_message.php?event=' + currentEvent, {
            teams: teams,
            username: username
        }, function() {
            //window.location = '/peachpits/peachtalk/new_message.php?event=' + currentEvent;
        });
    }
</script>

<!-- Inhibit user popup -->
<div class="modal fade" id="inhibit-user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 style="display:inline" class="modal-title" id="myModalLabel">Inhibit</h4>
        <h4 style="display:inline" class="modal-title" name="user" id="getuser">User</h4>
        <h4 style="display:inline" class="modal-title" id="myModalLabel">from Team</h4>
				<h4 style="display:inline" class="modal-title" name="teamid" id="getteamid">#</h4>
      </div>
      <div class="modal-body">
          <h4>Select all of the channels you wish to restrict this user from sending messages in.</h4>
        <form action="/peachpits/peachtalk/inhibit_user?event=<?php echo $currentEvent; ?>" method="post">
			<input type="hidden" name="userid" id="inhibit-userid">
            <h4>
            <label class="label-no-bold"><input class="form-check-input" type="checkbox" name="channel1" id="parts-checkbox" value="Parts">&nbsp;Parts</input></label><br>
            <label class="label-no-bold"><input class="form-check-input" type="checkbox" name="channel2" id="safety-checkbox" value="Safety">&nbsp;Safety</input></label><br>
            <label class="label-no-bold"><input class="form-check-input" type="checkbox" name="channel3" id="private-checkbox" value="Private">&nbsp;Private Messages</input></label><br>
            </h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-default" name="submit">Save Changes</button></form>
      </div>
    </div>
  </div>
</div>

<!-- Delete user popup -->
<div class="modal fade" id="delete-user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 style="display:inline" class="modal-title" id="myModalLabel">Delete</h4>
        <h4 style="display:inline" class="modal-title" name="user" id="getuser2">User</h4>
        <h4 style="display:inline" class="modal-title" id="myModalLabel">from Team</h4>
		<h4 style="display:inline" class="modal-title" name="teamid" id="getteamid2">#</h4>
      </div>
      <div class="modal-body text-center">
        <h4 style="display:inline">This will remove the Communication Liaison role from the user. Are you sure you want to continue?</h4>
        <form action="/peachpits/peachtalk/delete_user?event=<?php echo $currentEvent; ?>" method="post">
			<input type="hidden" name="userid" id="delete-userid">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger" name="submit">Delete User</button></form>
      </div>
    </div>
  </div>
</div>

<?php } include dirname(__DIR__) . "/footer.php"; ?>