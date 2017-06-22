<?php include "header.php"; ?>
	<head>
		<style>
			html,
			body {
				height: calc(100% - 50px);
			}
			.navbar{
				background-color:transparent;
				z-index:1000;
			}
            .footer{
                position:relative; /* page specific positioning */
            }
            @media only screen and (max-width: 766px) {
                .navbar{background-color:#DC7633;}
                .collapsing, .in {background-color: #DC7633;}
                .collapsing ul li a, .in ul li a {color: #555 !important;}
                .collapsing ul li a:hover, .in ul li a:hover {color: #f1f1f1 !important;}
            }
		</style>
		<script>
		$(function() {
			$('.scroll').on('click', function(e) {
				e.preventDefault();
				$('html, body').animate({ scrollTop: $($(this).attr('href')).offset().top}, 500, 'linear');
			});
		});
		</script>
	</head>
	<div class="site-wrapper-container">
  <div class="site-wrapper">
    <div class="site-wrapper-inner">
      <div class="cover-container">
        <div class="inner cover">
          <h1 class="cover-heading">Choose an Event To Get Started!</h1>
          	<?php if (empty($currentEvent)){ ?>
						<div class="dropdown text-center" style="display:inline !important; float:left; width:100%;">
							<button class="btn-dropdown-nav btn-lg dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								Select an Event
							<span class="caret"></span>
							</button>
							<ul class="dropdown-menu pull-center" aria-labelledby="dropdownMenu1">
							<?php 
								$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventstatus` LIKE 'Live'");
								while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
									echo '<li><a href="pitmap?event=' . $row['eventid'] . '">' . $row['eventname'] . '</a></li>';
								}	 
							?>
							<li class="divider"></li>
							<li><a href="" style="color:red;">Don't see your event? Click Here -REPLACE THIS-</a></li>
							</ul>
						</div>
						<?php } else { ?>
						<div class="dropdown text-center" style="display:inline !important; float:left; width:100%;">
							<button class="btn-dropdown-nav btn-lg dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<?php
								$sql = $mysqli->query("SELECT `eventname` FROM `events` WHERE `eventid` LIKE '$currentEvent'");
								$row = mysqli_fetch_assoc($sql);
								echo $row['eventname'];
							?>
							<span class="caret"></span>
							</button>
							<ul class="dropdown-menu pull-center" aria-labelledby="dropdownMenu1">
							<li class="disabled"><a href="#"><b>Current: </b><?php echo $row['eventname']; ?></a></li>
							<li role="separator" class="divider"></li>
							<?php 
								$sql = $mysqli->query("SELECT * FROM `events` WHERE `eventstatus` LIKE 'Live'");
								while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
									if($row['eventid'] != $currentEvent){
										echo '<li><a href="pitmap.php?event=' . $row['eventid'] . '">' . $row['eventname'] . '</a></li>';
									}
								}	 
							?>
							<!--<li class="divider"></li>
							<li><a href="" style="color:red;">Don't see your event? Click Here -REPLACE THIS-</a></li>-->
							</ul>
						</div>
						<?php } ?>
        </div>
				
      </div>
    </div>
  </div>
	<div class="scroll-container text-center"><a href="#about" class="scroll"><span class="glyphicon glyphicon-menu-down"></span><span style="display:block;">Scroll</span></a></div>
	</div>
	<section id="about">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8 text-center">
					<p class="lead" style="margin-bottom:0px;">PeachPits makes it easy to manage inspections and create pit maps for your FRC event.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<img src ="imgs/screen_mockups-temp.png" style="width:100%;">
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<p class="lead">Ready to use PeachPits at your event?</p>
					<a href="signup.php?event=<?php echo $currentEvent ?>" class="btn btn-default btn-lg">Create an Account <span class="glyphicon glyphicon-chevron-right"></span></a>
				</div>
			</div>
		</div>
	</section>

	<section id="sponsors">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<h2>PeachPits is presented by</h2>
					<a href="http://www.automationdirect.com/adc/Home/Home"><img src="imgs/adc_logo.png" style="width:100%;"></a>
				</div>
			</div>
		</div>
	</section>

	
<?php include "footer.php"; ?>