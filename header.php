<?php 
    /*********************
    This file must be included at the top of each page
    **********************/
    require_once (dirname(__FILE__) .  "/includes/session.php");
    
    global $sessionEmail;
    
    if (isset($_SESSION['email'])){
        $sessionEmail = $_SESSION['email'];
    }
    
    error_reporting(0);
    $filePath = "http://" . $_SERVER['SERVER_NAME'] . "/peachpits/";
    $currentEvent = $_GET['event'];

    $sql = $mysqli->query("SELECT `eventname` FROM `events` WHERE `eventid` LIKE '$currentEvent'");
    $row = mysqli_fetch_assoc($sql);
    $eventName = $row['eventname'];
    
    //Fetch some general information about the user from the database for later use
    $sql = $mysqli->query("SELECT * FROM `users` WHERE `email`='$sessionEmail'");
    $row = mysqli_fetch_assoc($sql);
    
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $superRole = $row['role'];
    $events = $row['events'];
    $eventsArr = explode(';', $events);
    $userEventsArr = array();
    for ($index = 0; $index < count($eventsArr); $index++) {
        $loopEventArr = explode('@',$eventsArr[$index]);
        $userEventsArr[] = $loopEventArr[1];
    }
    
    //Sets the options for refresh times
    $refreshArr = ["1 second", "5 seconds", "10 seconds", "15 seconds", "30 seconds", "60 seconds"];
		$refreshArrInt = [];
		$refreshTime = 0;
        //Creates an array with only the ints of the $refreshArr
		foreach($refreshArr as $option) {
			$refreshArrInt[] = (int) filter_var($option, FILTER_SANITIZE_NUMBER_INT);
		}
        //If refreshTime is not set, default is 15s
		if (!isset($_SESSION['refreshTime'])) {
			$_SESSION['refreshTime'] = 15;
		}
        //Checks if chosen refreshTime is in specified array & sets $refreshTime
		for($i = 0; $i < count($refreshArr); $i++) {
			if ($_SESSION['refreshTime'] == $refreshArrInt[$i]){
				$refreshTime = $refreshArr[$i];
			}
		}
    
    //Compiles all the announcements from the table into one string
    $eventAnnouncements = $currentEvent."_announcements";
    $allAnnouncementsString = " ";
    $sqlAnnouncements = $mysqli->query("SELECT * FROM `".$eventAnnouncements."` ORDER BY `position` ASC");
    while($row = mysqli_fetch_array($sqlAnnouncements, MYSQLI_BOTH)){
      $allAnnouncementsString = $allAnnouncementsString . $row['text'] . "&emsp;&emsp;&emsp;";
    }
    //Calculates the animation time (mobile) for scrolling text based on a curve of best fit
    $varAMobile = -15.96452; $varBMobile = 0.4108012; $varCMobile = 15987670000; $varDMobile = 53131.18; $varX = strlen($allAnnouncementsString);
    $animationTimeMobile = $varDMobile + ($varAMobile - $varDMobile)/(1 + pow(($varX/$varCMobile),$varBMobile));
    //Calculates the animation time (desktop) for scrolling text based on a curve of best fit
    $varA = -56.08185; $varB = 0.1618459; $varC = 39493630000; $varD = 1745.794;
    $animationTime = $varD + ($varA - $varD)/(1 + pow(($varX/$varC), $varB));

    if($currentEvent != '' && isset($_SESSION['email'])){
        $sqlEvents = $mysqli->query("SELECT * FROM `events` WHERE `eventid` = '$currentEvent'");
        $rowEvents = mysqli_fetch_assoc($sqlEvents);
        //$index = in_array('Event', $eventsArr);
        $eventIndex = '';
        foreach($eventsArr as $index => $string) {
            if (strpos($string, $rowEvents['eventname']) !== FALSE){
                $eventIndex = $index;
                break;
            }
        }
        if ($eventIndex !== '') {
            $str = $eventsArr[$eventIndex];
            $arr = explode('@',$str);
            $role = $arr[0];
            $roleEvent = $arr[1];
        }
        else {
            if ($superRole == 'Super Admin') {
                $role = 'No Event';
            }
        }
    }
    else if ($currentEvent == '' && isset($_SESSION['email'])){
        $role = 'None selected';
    }
    
    if(($role == 'No Event' || $role == 'None selected') && $superRole == 'Super Admin'){
        $role = 'Super Admin';
    }
    //Checks if a user is a super admin
    function isSuperAdmin($role){
        if($role == "Super Admin"){
            return true;
        }
    }    
    //Checks if a user is an event admin
    function isEventAdmin($role){
        if($role == "Event Admin"){
            return true;
        }
    }  
    //Checks if a user is an pit admin
    function isPitAdmin($role){
        if($role == "Pit Admin"){
            return true;
        }
    }
    //Checks if a user is a lead inspector
    function isLeadInspector($role){
        if($role == "Lead Inspector"){
            return true;
        }
    }
    //Checks if a user is an inspector
    function isInspector($role){
        if($role == "Inspector"){
            return true;
        }
    }

    function isPeachTalkAdmin($role) {
      return isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role);
    }

    $peachtalkUsername = "";
    if (isSuperAdmin($role) || isEventAdmin($role) || isPitAdmin($role)) {
        $peachtalkUsername = $sessionEmail.";".$role.";none;none;none";
    }
    elseif ($role == "Communication Liaison" && $roleEvent == $eventName) {
        $sql = $mysqli->query("SELECT * FROM `".$currentEvent."_liaisons` WHERE `email`='$sessionEmail'");
        $row = mysqli_fetch_assoc($sql);
        if ($row['restrictions'] == "") {
            $restrictions = "none";
        }
        else {
            $restrictions = $row['restrictions'];
        }
        $peachtalkUsername = $sessionEmail.";".$row['teamid'].";".$row['user'].";".$row['userid'].";".$restrictions;
    }
    else {
        $peachtalkUsername = "none";
    }

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

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#DC7633">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <title>PeachPits</title>
        <base href="<?php echo $filePath ?>">
        <link rel="icon" href="imgs/peachicon.png" sizes="192x192">
        <link rel="apple-touch-startup-image" href="imgs/peachicon.png" sizes="192x192">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet"/>
        <link href="css/admin.css" rel="stylesheet"/>
        <link href="css/home.css" rel="stylesheet"/>
        <link href="css/map.css" rel="stylesheet"/>
        <link href="css/footer.css" rel="stylesheet"/>
        <link href="css/peachtalk.css" rel="stylesheet"/>
        <link href="css/bootstrap-sortable.css" rel="stylesheet"/>
        <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="js/bootstrap-sortable.js"></script>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script>
          var currentEvent = '<?php echo $currentEvent; ?>';
          var eventTable = <?php echo $jsEvents; ?>;
          var currentPage = document.URL;
          console.log(currentPage);
        </script>
        <script>
          $(document).ready(function() {
            $('.select-category').on("click", function(e){
              e.preventDefault();
              filterEvents(this);
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
            let categories = $('.select-category');
            let currentCategory = $('.dropdown-current-category').attr("value");
            if (currentCategory) {
              for (let i = 0; i < categories.length; i++) {
                let category = categories[i];
                if (category.innerText == currentCategory) {
                  filterEvents(category);
                }
              }
            }
          });

          function filterEvents(selectCategory) {
            $('.selected-category').removeClass('selected-category');
            $(selectCategory).addClass('selected-category');
            $('.dropdown-current-category').text($(selectCategory)[0].text);
            var type = $(selectCategory)[0].getAttribute("value");
            $('.select-event').addClass('hidden');
            var numEventsShown = 0;
            if (type == "district") {
              var districtName = $(selectCategory)[0].text;
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
              var districtName = selectCategory.innerText;
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
          }
        </script>
        <style>      
            @media (max-width: 766px) {
                .mobile-header-list {
                    color:#ffffff !important;
                }

                .mobile-current-list {
                    color:#e0e0e0 !important;
                }
            }
        </style>
    </head>
    <body id="body-header">
    <?php 
    $actualLink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if(strpos($actualLink, 'display.php')==false){
    ?>
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="index?event=<?php echo $currentEvent; ?>" class="navbar-brand">PeachPits</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse-1" style="margin-left:0px !important;">
                <ul class="nav navbar-nav navbar-right cl-effect-4">
                    <li class="dropdown">
                        <button class="dropdown-toggle btn-dropdown-nav navbar-btn" id="select-category-dropdown" type="button" id="dropdownCategories" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                          <?php
                            $output = '<span class="dropdown-current-category" value="';
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
                                $output .= $row['eventdistrict'] . '">' . $row['eventdistrict'];
                              } else {
                                $output .= $typeToCategory[$type] . '">' . $typeToCategory[$type];
                              }
                            } else {
                              $output .= '">Select a Category ';
                            }
                            $output .= ' </span>';
                            echo $output;
                          ?>
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="max-height:55vh;overflow-y:scroll;width:150%;" aria-labelledby="dropdownCategories">
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
                    </li>
                    <li class="dropdown">
                      <button class="dropdown-toggle btn-dropdown-nav navbar-btn dropdown-current-event" type="button" id="dropdownEvents" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
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
                      <ul class="dropdown-menu" style="max-height:55vh;overflow-y:scroll;width:150%;" id="dropdown-events" aria-labelledby="dropdownEvents">
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
                    </li>
                    <!-- <li class="dropdown">
                        <button data-toggle="dropdown" class="dropdown-toggle btn-dropdown-nav navbar-btn dropdown-current-event" id='select-event-dropdown'>
                            <?php
                                $sql = $mysqli->query("SELECT `eventname` FROM `events` WHERE `eventid` LIKE '$currentEvent'");
                                $row = mysqli_fetch_assoc($sql);
                                echo '<span class="">'.$row['eventname'].'</span>';
                            ?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li class="disabled"><a href="#" class="mobile-header-list mobile-current-list"><b>Current: </b><?php echo $row['eventname']; ?></a></li>
                            <li role="separator" class="divider"></li>
                            <?php
                                $sql = $mysqli->query("SELECT * FROM `events` WHERE `eventstatus` LIKE 'Live'");
                                while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
                                    if($row['eventid'] != $currentEvent){
                                        echo '<li><a class="mobile-header-list" href="pitmap?event=' . $row['eventid'] . '">' . $row['eventname'] . '</a></li>';
                                    }
                                }
                            ?>
                        </ul>
                    </li> -->
                    <li><a href="teams?event=<?php echo $currentEvent; ?>">Team List</a></li>
                    <li><a href="matches?event=<?php echo $currentEvent; ?>">Match Schedule</a></li>
                    <li><a href="pitmap?event=<?php echo $currentEvent; ?>">Pit Map</a></li>
                    <?php if (loggedOn()){ ?>
                    <li>
                      <a href="admin/dashboard?event=<?php echo $currentEvent; ?>">Dashboard</a>
                    </li>
                    <?php } else { ?>
                    <li>
                      <a href="signin?event=<?php echo $currentEvent; ?>" style="padding-top:10px;" title="Sign In">
                        <i class="glyphicon glyphicon-user" style="font-size: 25px;"></i>
                      </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>      
    </nav>
<?php } ?>
<!-- Popup box for selecting an event -->
<div class="modal fade" id="event-select" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Change Your Password</h4>
      </div>
      <div class="modal-body">
    <?php
        $sqlEventsStr;
        $index = 0;
        foreach($eventsArr as $singleEvent){
            $str = $eventsArr[$index];
            $arr = explode('@',$str);
            $singleEvent = $arr[1];
            $sqlEventsStr[] = "`eventname` LIKE '".$singleEvent."'";
            $index++;
        }
        $sql = $mysqli->query("SELECT * FROM `events` WHERE " .implode(" OR ", $sqlEventsStr));
        while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
            echo '<li><a href="admin/dashboard?event=' . $row['eventid'] . '">' . $row['eventname'] . '</a></li>';
        }
    ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-default" name="submit">Change Password</button></form>
      </div>
    </div>
  </div>
</div>