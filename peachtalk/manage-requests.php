<title>PeachTalk - Requests</title>
<?php 
	include dirname(__DIR__) . "/header.php"; 
	
	if (empty($currentEvent)) {
		echo '<script>window.location="/peachpits/chooseevent"</script>';
	}
	else {
        if (!(isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role))) {
            echo '<script>window.location="/peachpits/peachtalk/peachtalk-home?event='.$currentEvent.'"</script>';
        }
		$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '".$currentEvent."'");
		$row = mysqli_fetch_assoc($sql);
		$eventname = $row['eventname'];
        $eventLiaisons = $currentEvent . "_liaisons";
?>

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
                        <h4 class="channel-header-text">Manage Requests</h4>
                    </td>
                    <td style="padding-right:15px;width:10%" id="right-cell"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="container content" style="margin-top:20px">
    <div class="table-responsive">
		<table style="border: 1px solid #ddd" class="table table-hover" id="inspections-table">
			<thead style="background-color:white;border-top:none">
				<th></th>
				<th class="text-center" colspan="2" style="border-left:2px solid #ddd;border-right:2px solid #ddd"><strong>Communication Liaison</strong></th>
                <!-- <th class="text-center" colspan="2" style="border-left:2px solid #ddd;border-right:2px solid #ddd"><strong>Lead Mentor</strong></th> -->
				<th colspan="2"></th>
			</thead>
            <thead style="background-color:white;border-top:none">
				<th class="text-center" style="width:8%"><strong>Team #</strong></th>
				<th class="text-center" style="width:19%;border-left:2px solid #ddd"><strong>Name</strong></th>
                <th class="text-center" style="width:19%;border-right:2px solid #ddd"><strong>Phone Number</strong></th>
				<!-- <th class="text-center" style="width:19%;border-left:2px solid #ddd"><strong>Name</strong></th>
				<th class="text-center" style="width:19%;border-right:2px solid #ddd"><strong>Phone Number</strong></th> -->
                <th class="text-center" style="width:8%"><strong>Approve</strong></th>
                <th class="text-center" style="width:8%"><strong>Deny</strong></th>
			</thead>
			<tbody>
				<?php 
					$sql = $mysqli->query("SELECT * FROM `$eventLiaisons` WHERE `status` = 'Pending'");
                    while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                        echo '<tr>';
                        echo '<td class="text-center" style="width:8%">'.$row['teamid'].'</td>';
                        echo '<td class="text-center" style="width:19%">'.$row['user'].'</td>';
                        echo '<td class="text-center" style="width:19%">'.$row['cell'].'</td>';
                       // echo '<td class="text-center" style="width:19%">'.$row['leadmentor_name'].'</td>';
                       // echo '<td class="text-center" style="width:19%">'.$row['leadmentor_cell'].'</td>';
                        echo '<td class="text-center" style="width:8%"><a href="/peachpits/admin/approve_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $eventname .'&role=Communication Liaison&refer=manage_requests">Approve</a></td>';
                        echo '<td class="text-center" style="width:8%"><a href="/peachpits/admin/deny_request?event='.$currentEvent.'&user='. base64_encode($row['email']) .'&eventReq='. $eventname .'&role=Communication Liaison&refer=manage_requests">Deny</a></td>';
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
</script>

<?php } include dirname(__DIR__) . "/footer.php"; ?>