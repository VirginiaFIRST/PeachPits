<title>PeachPits - Announcements</title>
<?php 
	/*********************
    Allows an event admin to create and edit teams
    **********************/	
	include dirname(__DIR__) . "/header.php";

    $eventAnnouncements = $currentEvent . "_announcements";

	if(loggedOn()) {
    	include "menu.php";	
?>
<script>
    $(document).ready(function() {
        $(".nav li").removeClass("active");
        $('#announcements').addClass('active');
    });
</script>
		<div class="col-md-10 container-dashboard-content">
			<div class="dashboard-content">
                <h3>Current Announcements:</h3>
                    <table class="table table-hover">
                        <tbody>
                        <?php
                            $sqlAnnouncements = $mysqli->query("SELECT * FROM `".$eventAnnouncements."` ORDER BY `position` ASC");
                            while($row = mysqli_fetch_array($sqlAnnouncements, MYSQLI_BOTH)){
                                echo '<tr>';
                                //echo '<td>'.$row['position'].'</td>';
                                echo '<td>'.$row['text'].'</td>';
                                echo '<td style="vertical-align:middle;width:5%" class="text-center"><a href="admin/change_announcements?event='.$currentEvent.'&type=up&row='.$row['position'].'"><span class="glyphicon glyphicon-arrow-up"></span></a></td>';
                                echo '<td style="vertical-align:middle;width:5%" class="text-center"><a href="admin/change_announcements?event='.$currentEvent.'&type=down&row='.$row['position'].'"><span class="glyphicon glyphicon-arrow-down"></span></a></td>';
                                echo '<td style="vertical-align:middle;width:5%" class="text-center"><a style="color:inherit;text-decoration:none" href="/peachpits/admin/change_announcements?event='.$currentEvent.'&type=delete&row='.$row['position'].'"><span style="font-size:30px;color:red">&times;</span></a></td>';
                                echo '</tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                <h3>Add Announcements:</h3>
                <form action="/peachpits/admin/change_announcements?event=<?php echo $currentEvent; ?>&type=save" method="post">
                    <input type="hidden" name="new-announcement-input" id="new-announcement-input">
                    <textarea class="form-control" style="width:100%; height:100px; margin-bottom:5px; resize:none;" name="new-announcement" id="new-announcement"></textarea><br/>
                    <div id="submit-announcement-btn">
                    <button type="submit" class="btn btn-default" name="submit" id="new-announcement-submit">Save Announcement</button>
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>
	
<?php 
	} else { echo '<script>document.location.href="signin"</script>'; }
	
    $numAnnouncements = 0;
    $sql = $mysqli->query("SELECT * FROM `".$eventAnnouncements."`");
    while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
        $numAnnouncements++;
    }
    if ($numAnnouncements >= 10) {
        echo "<script>document.getElementById('new-announcement').disabled = true; document.getElementById('new-announcement').placeholder = 'You can only have 10 announcements'; document.getElementById('submit-announcement-btn').className = 'hidden';</script>";
    }

    if ($currentEvent == "") {
        echo "<script>document.getElementById('new-announcement').disabled = true; document.getElementById('new-announcement').placeholder = 'You must select an event first before adding announcements'; document.getElementById('submit-announcement-btn').className = 'hidden';</script>";        
    }

	include dirname(__DIR__) . "/footer.php"; 
?>