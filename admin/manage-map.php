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
						<!--<button class="btn btn-default" style="float:right" data-toggle='modal' data-target='#choose-image' id="choose-image-btn">Choose Image</button>-->
					</div>
				</div>
				<div class="dashboard-content map-creator-container">
					<div class="col-md-3">
						<div class="map-creator-tools">
							<div class="map-creator-size" >
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
										<div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text" contenteditable="true">Machine Shop</p>
									</div>
								</div>
								<div class="element-box">
									<div class="single-line tables drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text" contenteditable="true">Spare Parts</p>
									</div>
								</div>
								<div class="element-box">
									<div class="single-line tables drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text" contenteditable="true">Pit Admin</p>
									</div>
								</div>
								<div class="element-box">
									<div class="single-line tables drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text" contenteditable="true">Inspection</p>
									</div>
								</div>
								<div class="element-box" style="height:60px">
									<div class="double-line tables drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div>
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
										<div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text"><span class="glyphicon glyphicon-arrow-up" style="color:white;"></span></p>
									</div>
								</div>
								<div class="element-box" style="width:30px;">
									<div class="tables arrows drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text"><span class="glyphicon glyphicon-arrow-down" style="color:white;"></span></p>
									</div>
								</div>
								<div class="element-box" style="width:30px;">
									<div class="tables arrows drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<p class="table-text"><span class="glyphicon glyphicon-arrow-left" style="color:white;"></span></p>
									</div>
								</div>
								<div class="element-box" style="width:30px;">
									<div class="tables arrows drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div>
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
								<div class="element-box" style="width:5px;">
									<div class="tables wall-vert drag">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok" style="left:-8px !important;"><span class="glyphicon glyphicon-ok circle"></span></div>
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
								<div class="element-box" style="height:81px;width:80px">
									<div class="tables img-box drag" style="height:81px;width:80px;border: solid 2px #555;">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<img src="imgs/adclogosquare.png" style="height:77px;width:76px" alt="Automation Direct Square Logo">
									</div>
								</div>
								<div class="element-box" style="height:81px;width:80px">
									<div class="tables img-box drag" style="height:81px;width:80px;border: solid 2px #555;">
										<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
										<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
										<div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div>
										<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
										<img src="http://firstinspires.org/sites/default/files/first-logo-200px.png" style="height:77px;width:76px" alt="FIRST Logo">
									</div>
								</div>

								<?php
									$imgurl = protect($_POST['imgurl']);
									echo '<img id="hidden-image" src="'.$imgurl.'" alt="Uploaded Image">';
									if ($imgurl == "" || $imgurl == null || $imgurl == "undefined") {
										echo '
										<div class="element-box text-center" style="width:120px">
											<button class="btn btn-default" style="width:120px" data-toggle="modal" data-target="#choose-image" id="choose-image-btn">Upload Image</button>
										</div>';
									}
									else {
										echo '
										<div id="upload-img-element-box" class="element-box" style="height:81px;width:80px">
											<div id="upload-img-drag-box" class="tables img-box drag" style="background-color:white;height:81px;width:80px;border: solid 2px #555;">
												<div class="btn-edit-table"><span class="glyphicon glyphicon-pencil circle"></span></div>
												<div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div>
												<div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div>
												<div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div>
												<img class="upload-img" src="'.$imgurl.'" style="height:77px;width:76px" alt="Uploaded Image">
											</div>
										</div>
										<div class="element-box text-center" style="width:152px">
											<button class="btn btn-default" style="width:152px" data-toggle="modal" data-target="#choose-image" id="choose-image-btn">Upload New Image</button>
										</div>';
									}
								?>
							</div>
						</div>
					</div>
					<div class="col-md-9">
						<div id="frame" class="container-map"></div>
					</div>
				</div>
			</div>
			<div class="visible-xs text-center" style="height:100px; margin-top: 100px">
				<h2>Map Creator is only available on desktops.</h2>
			</div>
		</div>
	</div>
</div>

<script>
	/*Resizes uploaded image*/
	var imgWidth = document.getElementById('hidden-image').offsetWidth;
	var imgHeight = document.getElementById('hidden-image').offsetHeight;
	var ratio = imgHeight/imgWidth;
	var newWidth = 76;
	var newHeight;
	var boxWidth;
	var boxHeight;
	document.getElementById('hidden-image').className = 'hidden';
	console.log("Width: " + imgWidth);
	console.log("Height: " + imgHeight);
	console.log("Ratio: " + ratio);
	if (ratio < 0.65) {
		newWidth = 130;
	}
	if (imgWidth != 0 && imgHeight != 0 && imgWidth >= newWidth) {
		newHeight = ratio * newWidth;
		boxWidth = newWidth + 4;
		boxHeight = newHeight + 4;
		document.getElementsByClassName('upload-img')[0].style.width = newWidth + "px";
		document.getElementsByClassName('upload-img')[0].style.height = newHeight + "px";
		document.getElementById('upload-img-element-box').style.width = boxWidth + "px";
		document.getElementById('upload-img-element-box').style.height = boxHeight + "px";
		document.getElementById('upload-img-drag-box').style.width = boxWidth + "px";
		document.getElementById('upload-img-drag-box').style.height = boxHeight + "px";
	}
	//Rotates the element
	function rotate(elem){  
				if ($(elem).parent().hasClass('vertical-text')) {
					console.log("1");
					$(elem).parent().removeClass('vertical-text');
					$(elem).removeClass('vertical-text-right');
					$(elem).siblings(".btn-edit-table").removeClass('vertical-text-right');
					$(elem).siblings(".btn-ok").removeClass('vertical-text-right');
					$(elem).siblings(".btn-remove").removeClass('vertical-text-right');
				}
				else {
					if ($(elem).parent().hasClass('upside-down-text')) {
						console.log("2");
						$(elem).parent().addClass('vertical-text');
						$(elem).addClass('vertical-text-right');
						$(elem).siblings(".btn-edit-table").addClass('vertical-text-right');
						$(elem).siblings(".btn-ok").addClass('vertical-text-right');
						$(elem).siblings(".btn-remove").addClass('vertical-text-right');
						$(elem).parent().removeClass('upside-down-text');
						$(elem).removeClass('upside-down-text');
						$(elem).siblings(".btn-edit-table").removeClass('upside-down-text');
						$(elem).siblings(".btn-ok").removeClass('upside-down-text');
						$(elem).siblings(".btn-remove").removeClass('upside-down-text');
					}
					else {
						if ($(elem).parent().hasClass('vertical-text-right')){
							console.log("3");
							$(elem).parent().addClass('upside-down-text');
							$(elem).addClass('upside-down-text');
							$(elem).siblings(".btn-edit-table").addClass('upside-down-text');
							$(elem).siblings(".btn-ok").addClass('upside-down-text');
							$(elem).siblings(".btn-remove").addClass('upside-down-text');
							$(elem).parent().removeClass('vertical-text-right');
							$(elem).removeClass('vertical-text');
							$(elem).siblings(".btn-edit-table").removeClass('vertical-text');
							$(elem).siblings(".btn-ok").removeClass('vertical-text');
							$(elem).siblings(".btn-remove").removeClass('vertical-text');
						}
						else {
							console.log("4");
							$(elem).parent().addClass('vertical-text-right');
							$(elem).addClass('vertical-text');
							$(elem).siblings(".btn-edit-table").addClass('vertical-text');
							$(elem).siblings(".btn-ok").addClass('vertical-text');
							$(elem).siblings(".btn-remove").addClass('vertical-text');
						}
					}
				}
			};
</script>

<!-- Choose image popup -->
<div class="modal fade" id="choose-image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 style="display:inline" class="modal-title" id="myModalLabel">Upload Image</h3>
      </div>
      <div class="modal-body text-center">
	  	<h3 style="margin-top:0;">Add your logo to the Pit Map!</h3>
	  	<h4>Copy an image URL into the textbox below, and the image you choose will appear in the Drag & Drop Elements!</h4>
		  <h4>The image will not save, so if you come back to this page later, you will have to copy the image URL again in order to add it to the pit map.</h4>
		  <h4 style="font-weight:bold">Make sure the pitmap is saved before clicking "Upload Image"!</h4>
        <form action="/peachpits/admin/manage-map?event=<?php echo $currentEvent; ?>" method="post">
			<input type="hidden" name="teamid" id="choose-image">
			<textarea class="form-control" style="width:100%; height:100px; margin-bottom:5px; resize:none;" placeholder="Paste Image URL Here" name="imgurl"></textarea><br/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-default" name="submit">Upload Image</button></form>
      </div>
    </div>
  </div>
</div>

<?php
	} else { echo '<script>document.location.href="signin"</script>'; }

	include dirname(__DIR__) . "/footer.php";
?>