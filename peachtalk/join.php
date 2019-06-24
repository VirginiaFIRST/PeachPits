<title>PeachTalk - Join</title>
<?php 
	include dirname(__DIR__) . "/header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="/peachpits/chooseevent"</script>';
	}
	else {
		if ($peachtalkUsername != "none") {
			echo '<script>window.location="/peachpits/peachtalk/peachtalk-home?event='.$currentEvent.'"</script>';
		}
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventname = $row['eventname'];
    $eventTeams = $currentEvent . "_teams";
    $peachtalkDisabled = $row['peachtalkstatus'];
    if ($peachtalkDisabled && !isPeachTalkAdmin($role)) {
      echo '<script>window.location="/peachpits/peachtalk/peachtalk-home?event=' + $currentEvent + '"</script>';
    }
?>
<script src="js/selectize.js"></script>
<link rel="stylesheet" type="text/css" href="css/selectize.css" />
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
                        <h4 class="channel-header-text">Join PeachTalk</h4>
                    </td>
                    <td style="padding-right:15px;width:10%" id="right-cell"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="container content" style="margin-top:20px">
	<div class="text-center">
		<h2>Become a Communication Liaison for your team at <?php echo $eventname; ?>!</h2>
		<h3>Joining allows you to respond to messages in the General and Safety channels as well as start private messages with other users and teams!</h3>
		<?php if (!loggedOn()) { ?>
		<br>
		<hr>
		<br>
		<h3>You must be signed in to request access to PeachTalk.</h3>
		<h3><a href="/peachpits/signin?event=<?php echo $currentEvent; ?>&refer=peachtalk" name="SignIn">Sign In</a> or <a href="signup?event=<?php echo $currentEvent; ?>&refer=peachtalk" name="SignUp">Sign Up</a></h3>
	</div>
		<?php
			} else {
				$sql = $mysqli->query("SELECT * FROM `requests` WHERE `status`='Pending' AND `email`='$sessionEmail' AND `requestedrole`='Communication Liaison' AND `event`='$eventname'");
				if (mysqli_fetch_row($sql)) {
		?>
		<br>
		<hr>
		<br>
		<h3>You have submitted a request to join! Your request must be processed and approved by the Pit Admin before you gain access to PeachTalk. Check back soon!</h3>
	</div>
		<?php } else { ?>
	</div>
	<p style="margin-left:10px;">
	<form class="form-inline" id="change-role-form" action="/peachpits/admin/change_role?event=<?php echo $currentEvent ?>&refer=peachtalk" method="post">
	<input type="hidden" name="roleChange" value="Communication Liaison"></input>
	<input type="hidden" name="addevent" value="<?php echo $eventname; ?>"></input>
		<br>
		<div class="form-group resize-form-group" style="width:100%">
			<div class="col-sm-offset-1 col-sm-10 inner-addon left-addon">
				<i class="glyphicon glyphicon-star" style="padding-left:30px"></i>       
				<!-- <?php $sql = $mysqli->query("SELECT * FROM `".$eventTeams."` ORDER BY `teamid` ASC"); ?> 
				<select onchange="formChange()" name="teamid" id="teamid">
					<option value="">Team Number<option>
					<?php
					while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
						echo '<option class="pull-left" value="'.$row['teamid'].'">'.$row['teamid'].'</option>'; 
					}
					?>
				</select> -->
				<input class="form-control input-lg no-radius" name="teamid" id="teamid" style="width:100%" placeholder="Your Team Number"></input>
			</div>
		</div>
		<div class="form-group resize-form-group" style="width:100%">
			<div class="col-sm-offset-1 col-sm-10 inner-addon left-addon">
				<i class="glyphicon glyphicon-user" style="padding-left:30px"></i>
				<input class="form-control input-lg no-radius" name="liaison-name" id="liaison-name" style="width:100%" placeholder="Your Name" value="<?php echo $firstname . ' ' . $lastname;?>"></input>
			</div>
		</div>
		<div class="form-group resize-form-group" style="width:100%">
			<div class="col-sm-offset-1 col-sm-10 inner-addon left-addon">
				<i class="glyphicon glyphicon-earphone" style="padding-left:30px"></i>
				<input class="form-control input-lg no-radius" autocomplete="tel" maxlength="10" name="liaison-cell" id="liaison-cell" style="width:100%" placeholder="Your Phone Number"></input>
			</div>
		</div>
		<div class="col-sm-offset-1 col-sm-10">
			<h5 class="cell-warning-text hidden" style="font-weight:bold;color:red;margin-left:3em;margin-top:0;">Invalid Phone Number. Should be in the format 123-456-7890</h5>
		</div>
		<div class="text-center">
			<button type="button" class="btn btn-default" id="liaison-btn-submit-request" data-toggle='modal' data-target='#read-terms-of-use' disabled="disabled">
			Request Access
			</button>
		</div>
	</form>
	</p>
	<?php } } ?>
</div>

<script>
	// $('#teamid').selectize({
	// 	highlight: false,
	// 	hideselected: true,
	// 	preload: false,
	// 	placeholder: "Team Number"
	// });
	$('#change-role-form').on('keyup keypress', function(e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode === 13) {
			e.preventDefault();
			return false;
		}
	});
	$('input').on('keyup', function() {
		formChange();
	});
	function formChange() {
		console.log('Form change');
		var empty = false;
		if (document.getElementById('liaison-name').value == "" || document.getElementById('liaison-cell').value == "") {
			empty = true;
		}
		if (empty == false) {
			$('#liaison-btn-submit-request').removeAttr('disabled');
		}
		else {
			$('#liaison-btn-submit-request').attr('disabled', 'disabled');
		}
	}
	function formChangeTermsOfUse(elem) {
		if (elem.checked == true) {
			$('#submit-agree-form').removeAttr('disabled');
		}
		else {
			$('#submit-agree-form').attr('disabled', 'disabled');
		}
	}
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
        <h3 style="display:inline" class="modal-title" id="myModalLabel">Terms of Use</h3>
      </div>
      <div class="modal-body">
			  <h4 class="text-center">Before using PeachTalk, you must read and accept the Terms of Use.</h4>
            <br><br>
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
            <label class="label-no-bold pull-left"><input class="form-check-input pull-left" type="checkbox" onchange="formChangeTermsOfUse(this)"><span class="pull-left">&nbsp;I agree to the Terms of Use</span></input></label>
            <button class="btn btn-default" id="submit-agree-form" onclick="document.getElementById('change-role-form').submit()" disabled="disabled">Continue</button>
      </div>
    </div>
  </div>
</div>

<?php } include dirname(__DIR__) . "/footer.php"; ?>