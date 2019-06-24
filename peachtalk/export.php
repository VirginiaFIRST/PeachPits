<title>PeachTalk - Export</title>
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
                        <h4 class="channel-header-text">Export</h4>
                    </td>
                    <td style="padding-right:15px;width:10%" id="right-cell"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="container content" style="margin-top:20px">
    <div class="export-desktop-view">
        <div class="text-center">
            <h3>Clicking these buttons will trigger a download of a Excel Spreadsheet</h3>
        </div>
        <div class="row">
            <form action="/peachpits/peachtalk/new_export?event=<?php echo $currentEvent; ?>" id="export-form" method="post">
                <input type="hidden" name="type" id="export-form-type">
                <div class="col-xs-4" id="boxes-container-left">
                    <div class="text-center">
                        <button class="btn btn-default btn-lg" id="messages" onclick="submitForm(this)"><span class="glyphicon glyphicon-save"></span> Download Messages</button>                
                    </div>
                </div>
                <div class="col-xs-4" id="boxes-container-right">
                    <div class="text-center">
                        <button class="btn btn-default btn-lg" id="groups" onclick="submitForm(this)"><span class="glyphicon glyphicon-save"></span> Download Group Info</button>
                    </div>
                </div>
                <div class="col-xs-4" id="boxes-container-right">
                    <div class="text-center">
                        <button class="btn btn-default btn-lg" id="users" onclick="submitForm(this)"><span class="glyphicon glyphicon-save"></span> Download User Info</button>
                    </div>
                </div>
        </div>
        <div class="text-center">
            <h4>When opening the spreadsheet, Excel might throw the error: <code>"The file format and extention of the file don't match... Do you want to open it anyway?"</code> If you receive this error, select "Yes". The file is safe to open.</h4>
        </div>
    </div>
    <div class="export-mobile-view">
        <div class="text-center">
            <h3>Clicking the following buttons will trigger a download of a Excel Spreadsheet</h3>
                <button class="btn btn-default btn-lg export-btn" id="messages" onclick="submitForm(this)"><span class="glyphicon glyphicon-save"></span> Download Messages</button>                
                <br>
                <button class="btn btn-default btn-lg export-btn" id="groups" onclick="submitForm(this)"><span class="glyphicon glyphicon-save"></span> Download Group Info</button>
                <br>
                <button class="btn btn-default btn-lg export-btn" id="users" onclick="submitForm(this)"><span class="glyphicon glyphicon-save"></span> Download User Info</button>
            </form>
            <h4>When opening the spreadsheet, Excel might throw the error: <code>"The file format and extention of the file don't match... Do you want to open it anyway?"</code> If you receive this error, select "Yes". The file is safe to open.</h4>
        </div>
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

    function submitForm(elem) {
        console.log(elem.id);
        document.getElementById('export-form-type').value = elem.id;
        document.getElementById('export-form').submit()
    }
</script>

<?php } include dirname(__DIR__) . "/footer.php"; ?>