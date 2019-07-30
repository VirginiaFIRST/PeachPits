<?php
	require_once ("includes/session.php");

    global $sessionEmail;

    if (isset($_SESSION['email'])){
        $sessionEmail = $_SESSION['email'];
    }

    error_reporting(0);

    //Fetch some general information about the user from the database for later use
    $sql="SELECT * FROM `users` WHERE `email`='$sessionEmail'";
    $query= $mysqli->query($sql);
    $row = mysqli_fetch_assoc($query);

    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $username = $row['username'];
    $role = $row['role'];

    $index = 0;
    $eventsTable;
    $sql = $mysqli->query("SELECT `eventname`, `eventid`, `eventtype`, `eventdistrict`, `eventstatus`, `eventend` FROM `events` ORDER BY `eventname` ASC");      
    while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
      $eventsTable[$index][0] = $row['eventid'];
      $eventsTable[$index][1] = $row['eventname'];
      $eventsTable[$index][2] = strtolower($row['eventtype']);
      $eventsTable[$index][3] = $row['eventdistrict'];
      $eventsTable[$index][4] = $row['eventstatus'];
      $eventsTable[$index][5] = $row['eventend'];
      $index++;
    }
    $jsEvents = json_encode($eventsTable);

    //Checks if a user is a super admin when called
    function isSuperAdmin($role){
        if($role == "Super Admin"){
            return true;
        }
    }

    //Checks if a user is an event admin when called
    function isEventAdmin($role){
        if($role == "Event Admin"){
            return true;
        }
    }

    //Checks if a user is an pit admin when called
    function isPitAdmin($role){
        if($role == "Pit Admin"){
            return true;
        }
    }

    //Checks if a user is an inspector when called
    function isInspector($role){
        if($role == "Inspector"){
            return true;
        }
    }
?>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
        <title>PeachPits</title>
        <link rel="icon" href="imgs/peach-icon.ico" type="image/x-icon">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet"/>
        <link href="css/footer.css" rel="stylesheet" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
		<style>
			html, body {
				height: calc(100% - 110px);
			}
			body {
				background-image:url(imgs/peaches.jpg);
        background-size:cover;
			}
			.navbar {
				background-color:transparent;
      }
      .dropdown {
        display: inline-block;
      }
    </style>
    <script>
      var currentEvent = '<?php echo $currentEvent; ?>';
      var eventTable = <?php echo $jsEvents; ?>;
    </script>
    <script>
      $(document).ready(function() {
        $('.select-category').on("click", function(e){
          e.preventDefault();
          $('.selected-category').removeClass('selected-category');
          $(this).addClass('selected-category');
          $('.dropdown-current-category').text($(this)[0].text);
          var type = $(this)[0].getAttribute("value");
          $('.select-event').addClass('hidden');
          var numEventsShown = 0;
          if (type == "district") {
            var districtName = $(this)[0].text;
            for (var i = 0; i < eventTable.length; i++) {
              var eventId = eventTable[i][0];
              var eventDistrict = eventTable[i][3];
              var eventStatus = eventTable[i][4];
              // var eventEnd = eventTable[i][5];
              // var eventEndDate = new Date(eventEnd);
              // var today = new Date();
              // if (eventStatus == "Live" && eventDistrict == districtName &&
              //         today - eventEndDate > 86400000) {
              if (eventStatus == "Live" && eventDistrict == districtName) {
                let eventListItem = document.getElementById(eventId);
                if (eventListItem) {
                  eventListItem.classList.remove('hidden');
                  numEventsShown++;
                }
              }
            }
          } else if (type == "all") {
            var districtName = this.innerText;
            for (var i = 0; i < eventTable.length; i++) {
              var eventId = eventTable[i][0];
              var eventDistrict = eventTable[i][3];
              var eventStatus = eventTable[i][4];
              // var eventEnd = eventTable[i][5];
              // var eventEndDate = new Date(eventEnd);
              // var today = new Date();
              // if (eventStatus == "Live" && today - eventEndDate > 86400000) {
              if (eventStatus == "Live") {
                let eventListItem = document.getElementById(eventId);
                if (eventListItem) {
                  eventListItem.classList.remove('hidden');
                  numEventsShown++;
                }
              }
            }
          } else {
            for (var i = 0; i < eventTable.length; i++) {
              var eventId = eventTable[i][0];
              var eventType = eventTable[i][2];
              var eventDistrict = eventTable[i][3];
              var eventStatus = eventTable[i][4];
              // var eventEnd = eventTable[i][5];
              // var eventEndDate = new Date(eventEnd);
              // var today = new Date();
              // if (eventStatus == "Live" && eventType.includes(type) &&
              //         !eventType.includes("district") && today - eventEndDate > 86400000) {
              if (eventStatus == "Live" && eventType.includes(type) &&
                      !eventType.includes("district")) {
                let eventListItem = document.getElementById(eventId);
                if (eventListItem) {
                  eventListItem.classList.remove('hidden');
                  numEventsShown++;
                }
              }
            }
          }
          if (numEventsShown == 0) {
            document.getElementById("no-events").classList.remove('hidden');
          }
        });
        $('#event-filter-field').on('keyup', function() {
          var filter = $("#event-filter-field").val().toUpperCase();
          $(".select-event").each(function (item) {
              if ($(this).text().toUpperCase().indexOf(filter) > -1) {
                  $(this).css('display', '');
              } else {
                  $(this).css('display', 'none');
              }
          });
        });
      });
    </script>
	</head>
	<body>
		<div style="display:table; width:100%; height:100%;">
			<div style="display:table-cell;vertical-align:middle; ">
				<div style="margin-left:auto;margin-right:auto;width:70%;text-align:center;">
					<h2 style="color:white;">Choose an Event Before Continuing</h2>
					<div class="dropdown">
            <button class="dropdown-toggle btn-dropdown-nav navbar-btn btn-lg" id="select-category-dropdown" type="button" id="dropdownCategories" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
              <?php
                $output = '<span class="dropdown-current-category">';
                $typeToCategory = [
                  "Offseason" => "Offseason Events",
                  "Preseason" => "Preseason Events",
                  "Regional" => "Regionals",
                  "Championship Division" => "World Championships",
                  "Championship Finals" => "World Championships"
                ];
                $sql = $mysqli->query("SELECT * FROM `events` WHERE `eventid` LIKE '$currentEvent'");
                $row = mysqli_fetch_assoc($sql);
                if (strlen($row['eventname']) > 0 && $currentEvent) {
                  $type = $row['eventtype'];
                  if (strpos($type, "District") !== false) {
                    $output .= $row['eventdistrict'];
                  } else {
                    $output .= $typeToCategory[$type];
                  }
                } else {
                  $output .= "Select a Category ";
                }
                $output .= ' </span>';
                echo $output;
              ?>
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" style="max-height:40vh;overflow-y:scroll;width:150%;" aria-labelledby="dropdownCategories">
              <?php
                echo '<li><a class="mobile-header-list select-category" value="all">All Events</li>';
                $sql = $mysqli->query("SELECT DISTINCT `eventdistrict` FROM `events` ORDER BY `eventdistrict` ASC");
                while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
                  if (strlen($row['eventdistrict']) > 0) {
                    echo '<li><a class="mobile-header-list select-category" value="district">' . $row['eventdistrict'] . '</a></li>';
                  }
                }
                echo '<li><a class="mobile-header-list select-category" value="offseason">Offseason Events</a></li>';
                echo '<li><a class="mobile-header-list select-category" value="preseason">Preseason Events</a></li>';
                echo '<li><a class="mobile-header-list select-category" value="regional">Regionals</a></li>';
                echo '<li><a class="mobile-header-list select-category" value="championship">World Championships</a></li>';
              ?>
            </ul>
          </div>
          <div class="dropdown">
            <button class="dropdown-toggle btn-dropdown-nav navbar-btn btn-lg dropdown-current-event" type="button" id="dropdownEvents" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
              <?php
                $sql = $mysqli->query("SELECT `eventname` FROM `events` WHERE `eventid` LIKE '$currentEvent'");
                $row = mysqli_fetch_assoc($sql);
                if (strlen($row['eventname']) > 0) {
                  echo $row['eventname'];
                } else {
                  echo "Select an Event";
                }
              ?>
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" style="max-height:40vh;overflow-y:scroll;width:150%;" id="dropdown-events" aria-labelledby="dropdownEvents">
              <?php
                echo '<li><input type="text" id="event-filter-field" class="form-control event-filter-field" placeholder="Search Events"></li>';
                echo '<li class="divider"></li>';
                $sql = $mysqli->query("SELECT * FROM `events` WHERE `eventstatus` LIKE 'Live' ORDER BY `eventname` ASC");
                while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
                  echo '<li><a class="mobile-header-list select-event" id="' . $row['eventid'] . '" href="pitmap?event=' . $row['eventid'] . '">' . $row['eventname'] . '</a></li>';
                }
                echo '<li><a class="mobile-header-list select-event hidden" style="color:red;" id="no-events">No Events Using PeachPits in this Category</a></li>';
              ?>
            </ul>
          </div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php 	include "footer.php"; ?>