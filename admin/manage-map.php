<title>PeachPits - Pitmap</title>
<?php 
	/*********************
    Pit Map Creator page
    **********************/
	include dirname(__DIR__) . "/header.php";
	 
	if(loggedOn()) {
    	include "menu.php";
		
		$sql = $mysqli->query("SELECT * FROM `maps` WHERE `eventid` LIKE '$currentEvent'");
		$row = mysqli_fetch_assoc($sql);
		$eventTeams = $currentEvent . "_teams";
?>
<head>
	<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>
		var teamsArr;
		var frameHeight;
		var frameWidth;
		<?php 
			$i = 0;
			$teamNums;			
			$sqlTeams = $mysqli->query("SELECT * FROM `".$eventTeams."` ORDER BY `teamid` ASC");
			while($rowTeams = mysqli_fetch_array($sqlTeams, MYSQLI_BOTH)){
				$teamNums[$i] = $rowTeams['teamid'];
				$i = $i + 1;
			}
			$jsArr = json_encode($teamNums);
			echo "var teamsArr = ". $jsArr . ";\n";			
		?>
		var mapCode = '<?php echo $row['mapcode']; ?>';
		var frameHeight = '<?php echo $row['height']; ?>';
		var frameWidth = '<?php echo $row['width']; ?>';
	</script>
	<script src="admin/js/map-creator.js"></script>
</head>

		<div class="col-md-10 container-dashboard-content">
			<div class="hidden-xs">
				<div class="dashboard-toolbar">
					<div class="container-fluid text-center">
						<button class="btn btn-default" style="margin-right:5px;" id="save">Save</button>
						<button class="btn btn-default" style="margin-left:5px; margin-right:5px;" id="reset">Reset</button>
						<button class="btn btn-default" style="margin-left:5px;" id="clear">Clear</button>
					</div>
				</div>
				<div class="dashboard-content map-creator-container">
					<div class="col-md-3">
						<div class="map-creator-tools">
							<div class="map-creator-size">
								<p style="padding-left:10px;"><b>Enter a size in feet:</b></p>
								<div class="pull-left" style="margin-left:10px;">
									<div style="margin-bottom:5px;"><span style="display:inline-block; width:50px;"><b>Width: </b></span><input type="text" class="form-control" id="width" style="display:inline;width:70px;"/></div>
									<div style="margin-top:5px;"><span style="display:inline-block; width:50px;"><b>Height: </b></span><input type="text" class="form-control" id="height" style="display:inline;width:70px;"/></div>
								</div>
								<button class="btn btn-default pull-right" id="change" style="margin-top:19px; margin-right:20px;">Change</button>
							</div>
							<div class="clearfix"></div>
							<div class="map-creator-elements">
								<p style="padding-left:10px;padding-top:10px;"><b>Drag & Drop Elements:</b></p>
								<div class="element-box">
									<div class="single-line tables drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text" contenteditable="true">Machine Shop</p>
									</div>
								</div>
								<div class="element-box">
									<div class="single-line tables drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text" contenteditable="true">Spare Parts</p>
									</div>
								</div>
								<div class="element-box">
									<div class="single-line tables drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text" contenteditable="true">Pit Admin</p>
									</div>
								</div>
								<div class="element-box">
									<div class="single-line tables drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text" contenteditable="true">Inspection</p>
									</div>
								</div>
								<div class="element-box" style="height:60px">
									<div class="double-line tables drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text table-text-double" contenteditable="true">Robot Traffic This Way</p>
									</div>
								</div>
								<div class="element-box-num">
									<div class="box drag">
										<div class="btn-edit"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<div class="box-num" contenteditable="true">----</div>
									</div>
								</div>
								<div class="element-box" style="width:30px;">
									<div class="tables arrows drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text"><span class="glyphicon glyphicon-arrow-up" style="color:white;"></span></p>
									</div>
								</div>
								<div class="element-box" style="width:30px;">
									<div class="tables arrows drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text"><span class="glyphicon glyphicon-arrow-down" style="color:white;"></span></p>
									</div>
								</div>
								<div class="element-box" style="width:30px;">
									<div class="tables arrows drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text"><span class="glyphicon glyphicon-arrow-left" style="color:white;"></span></p>
									</div>
								</div>
								<div class="element-box" style="width:30px;">
									<div class="tables arrows drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text"><span class="glyphicon glyphicon-arrow-right" style="color:white;"></span></p>
									</div>
								</div>
								<div class="element-box" style="width:30px;">
									<div class="tables arrows drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
									</div>
								</div>
								<div class="element-box" style="width:40px; padding-top:10px;">
									<div class="tables wall-horz drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok" style="left:-8px !important;"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
									</div>
								</div>
								<div class="element-box" style="width:5px;">
									<div class="tables wall-vert drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok" style="left:-8px !important;"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
									</div>
								</div>
							</div>
						</div>
					</div>			   
					<div class="col-md-9">
						<div id="frame" class="container-map"></div>
					</div>
				</div>
			</div>
			<div class="visible-xs text-center" style="height:100px; margin-top: 50%;">
				<h2>Map Creator is only available on desktops.</h2>
			</div>
		</div>
	</div>
</div>		
	
<?php 
	} else { echo '<script>document.location.href="signin"</script>'; }
	
	include dirname(__DIR__) . "/footer.php"; 
?>