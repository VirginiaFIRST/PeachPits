var pits = '';

$(window).bind('beforeunload', function(){
	return 'Be sure the pit map has been saved!';
});

function populateMap(teams) {
	var totalCount = 0;
	var vert = 0;
	var cancel = false;
	var rows = Math.ceil(teams.length/6);
	var pitArea = '';
	
	for(i = 0; i <= rows; i++){
		var horz = 0;
		for(j = 0; j < 6; j++){
			var pitArea = '<div class="box item dragAdd ui-draggable ui-draggable-handle" style="position: absolute; left:' + horz + 'px; top:' + vert + 'px; width: 40px; right: auto; height: 40px; bottom: auto;"><div class="btn-edit" style="visibility: hidden;"><span class="glyphicon glyphicon-pencil circle"></span></div><div class="btn-ok" style="visibility: hidden;"><span class="glyphicon glyphicon-ok circle"></span></div><div class="btn-remove" style="visibility: hidden;"><span class="glyphicon glyphicon-remove circle"></span></div><div class="box-num" contenteditable="true" id="' + teams[totalCount] + '">' + teams[totalCount] + '</div></div>';
			if(teams[totalCount]=='' || teams[totalCount]=='undefined' || teams[totalCount]==null){
				cancel = true;
				break;
			}
			horz += 40;
			totalCount++;
			pits += pitArea;
		}
		if(cancel){
			break;
		}
		vert += 40;
	}
	pits += '<div class="tables img-box item stuck" style="height: 81px; width: 80px; border: 2px solid rgb(85, 85, 85); right: auto; bottom: auto; left: 240px; top: 0px;"><div class="btn-edit-table" style="visibility: hidden;"><span class="glyphicon glyphicon-pencil circle"></span></div><div class="btn-ok"><span class="glyphicon glyphicon-ok circle"></span></div><div class="btn-rotate" onclick="rotate(this)"><span class="glyphicon glyphicon-repeat circle"></span></div><div class="btn-remove"><span class="glyphicon glyphicon-remove circle"></span></div><img src="imgs/adclogosquare.png" style="height:77px;width:76px" alt="Automation Direct Square Logo"></div>';
}

$(document).ready(function() {
	$(".nav li").removeClass("active");
	$('#map').addClass('active');
	
	$(".ui-draggable-dragging").addClass("dragged1");

	if(teamsArr == null || teamsArr == 'undefined' || teamsArr == ''){
		alert("You must first add teams! You'll be redirected to the Manage Teams page.");
		$(window).unbind('beforeunload');
		window.location.href = "admin/manage-teams?event=" + currentEvent;
	}
	
	populateMap(teamsArr);
	
	if(mapCode==''){
		$('#frame').html(pits);
		if((frameWidth && frameWidth != 0) && (frameHeight && frameHeight !=0)){
			$('#frame').css('width', frameWidth);
			$('#frame').css('height', frameHeight);
		}
	}
	else{
		$('#frame').html(mapCode);
		if((frameWidth && frameWidth != 0) && (frameHeight && frameHeight !=0)){
			$('#frame').css('width', frameWidth);
			$('#frame').css('height', frameHeight);
		}
	}

	var currWidth = $('#frame').css('width');
	var currHeight = $('#frame').css('height');
	currWidth = parseInt(currWidth.replace(/\D/g,''));
	currHeight = parseInt(currHeight.replace(/\D/g,''));
	currWidth = currWidth/4;
	currHeight = currHeight/4;
	$('#width').val(currWidth);
	$('#height').val(currHeight);

	$('#frame .box').addClass('dragAdd');
	$('#frame .single-line').addClass('dragAdd');
	$('#frame .double-line').addClass('dragAdd');
	$('#frame .arrow').addClass('dragAdd');
	$('#frame .walls-horz').addClass('dragAdd');
	$('#frame .walls-vert').addClass('dragAdd');
	$('#frame .img-box').addClass('dragAdd');

	$("#frame").droppable({
		accept: '.drag, .dragAdd',
		drop: function(event, ui) {
			if ($(ui.draggable).hasClass("drag")){
				if ($(ui.draggable).hasClass("item")){
					var actualPosition = ui.offset.top-127;
					$(ui.draggable).addClass("stuck");
					$(ui.draggable).css("top", actualPosition);
					$(ui.draggable).removeClass("ui-draggable drag dragAdd");
					$(ui.draggable).removeClass("ui-draggable-handle ui-draggable");
				}
				else {
					$(this).append($(ui.draggable).clone());
				}
			}
			else if ($(ui.draggable).hasClass("dragAdd")){
				$(this).append($(ui.draggable));
			}
			//$("#frame div.box, #frame .single-line, #frame .double-line, #frame .arrows, #frame .wall-horz, #frame .wall-vert, #frame .img-box").css("position", "absolute");
			$("#frame .drag").addClass("item");
			$("#frame .dragAdd").addClass("item");
			$(".item").removeClass("ui-draggable dragAdd");
			$(".item").removeClass("ui-draggable-handle ui-draggable");
			$(".item").draggable({
        cursor: "move",
				containment: 'parent',
				grid: [5,5],
        drag: function (event, ui) {
          var snapTolerance = $(this).draggable('option', 'snapTolerance');
          var topRemainder = ui.position.top % 5;
          var leftRemainder = ui.position.left % 5;

          if (topRemainder <= snapTolerance) {
            ui.position.top = ui.position.top - topRemainder;
          }

          if (leftRemainder <= snapTolerance) {
            ui.position.left = ui.position.left - leftRemainder;
          }
        }  
			});
			$('#frame .box').hover(function(e){  
				$(this).children('.btn-edit').css('visibility','visible');
			}, function() {
				$(this).children('.btn-edit').css('visibility','hidden');
			});
			$('#frame .tables').hover(function(e){  
				$(this).children('.btn-edit-table').css('visibility','visible');
			}, function() {
				$(this).children('.btn-edit-table').css('visibility','hidden');
			});
			$('.btn-edit').click(function(e){  
				e.preventDefault();
				e.stopPropagation();
				var text = $(this).siblings(".box-num").text();
				if (text == '----'){
					$(this).siblings(".box-num").text('');
				}
				$(this).siblings(".box-num").focus();
				$(this).css('visibility', 'hidden');
				$(this).siblings(".btn-ok").css('visibility', 'visible');
				$(this).siblings(".btn-remove").css('visibility', 'visible');
			});
			$('.btn-edit-table').click(function(e){  
				e.preventDefault();
				e.stopPropagation();
				$(this).siblings(".table-text").focus();
				$(this).css('visibility', 'hidden');
				$(this).siblings(".btn-ok").css('visibility', 'visible');
				$(this).siblings(".btn-rotate").css('visibility', 'visible');
				$(this).siblings(".btn-remove").css('visibility', 'visible');
			});
			$('.btn-ok').click(function(e){  
				e.preventDefault();
				e.stopPropagation();
				$(this).siblings(".box-num").blur();
				$(this).siblings(".table-text").blur();
				$(this).siblings(".btn-edit").css('visibility', 'hidden');
				$(this).siblings(".btn-edit-table").css('visibility', 'hidden');
				$(this).siblings(".btn-rotate").css('visibility', 'hidden');
				$(this).siblings(".btn-remove").css('visibility', 'hidden');
				$(this).css('visibility', 'hidden');
				var text = $(this).siblings(".box-num").text();
				$(this).siblings(".box-num").attr("id", text);
			});
			$('.btn-remove').click(function(e){  
				e.preventDefault();
				e.stopPropagation();
				$(this).parent().remove();
			});
		}
	});
	$(".drag").draggable({
    cursor: "move",
		helper: 'clone',
    drag: function (event, ui) {
      var snapTolerance = $(this).draggable('option', 'snapTolerance');
      var topRemainder = ui.position.top % 5;
      var leftRemainder = ui.position.left % 5;

      if (topRemainder <= snapTolerance) {
        ui.position.top = ui.position.top - topRemainder;
      }

      if (leftRemainder <= snapTolerance) {
        ui.position.left = ui.position.left - leftRemainder;
      }
    }  
	});
	$(".dragAdd").draggable({
    cursor: "move",
		containment: 'parent',
		grid: [5,5],
    drag: function (event, ui) {
      var snapTolerance = $(this).draggable('option', 'snapTolerance');
      var topRemainder = ui.position.top % 5;
      var leftRemainder = ui.position.left % 5;

      if (topRemainder <= snapTolerance) {
        ui.position.top = ui.position.top - topRemainder;
      }

      if (leftRemainder <= snapTolerance) {
        ui.position.left = ui.position.left - leftRemainder;
      }
    }  
	});
	$('#frame .btn-edit').click(function(e){  
		e.preventDefault();
		e.stopPropagation();
		var text = $(this).siblings(".box-num").text();
		if (text == '----'){
			$(this).siblings(".box-num").text('');
		}
		$(this).siblings(".box-num").focus();
		$(this).css('visibility', 'hidden');
		$(this).siblings(".btn-ok").css('visibility', 'visible');
		$(this).siblings(".btn-remove").css('visibility', 'visible');
	});
	$('#frame .btn-edit-table ').click(function(e){  
		e.preventDefault();
		e.stopPropagation();
		$(this).siblings(".table-text").focus();
		$(this).css('visibility', 'hidden');
		$(this).siblings(".btn-ok").css('visibility', 'visible');
		$(this).siblings(".btn-rotate").css('visibility', 'visible');
		$(this).siblings(".btn-remove").css('visibility', 'visible');
	});
	$('.btn-ok').click(function(e){  
		e.preventDefault();
		e.stopPropagation();
		$(this).siblings(".box-num").blur();
		$(this).siblings(".table-text").blur();
		$(this).siblings(".btn-edit").css('visibility', 'visible');
		$(this).siblings(".btn-rotate").css('visibility', 'hidden');
		$(this).siblings(".btn-remove").css('visibility', 'hidden');
		$(this).css('visibility', 'hidden');
		var text = $(this).siblings(".box-num").text();
		$(this).siblings(".box-num").attr("id", text);
	});
	$('#frame .btn-remove').click(function(e){  
		e.preventDefault();
		e.stopPropagation();
		$(this).parent().remove();
	});
	$('#frame .box').hover(function(e){  
		$(this).children('.btn-edit').css('visibility','visible');
	}, function() {
		$(this).children('.btn-edit').css('visibility','hidden');
	});
	$('#frame .tables').hover(function(e){  
		$(this).children('.btn-edit-table').css('visibility','visible');
	}, function() {
		$(this).children('.btn-edit-table').css('visibility','hidden');
	});				
	$( "#save" ).on( "click", function() {
		jQuery.fn.htmlClean = function() {
			this.contents().filter(function() {
				if (this.nodeType != 3) {
					$(this).htmlClean();
					return false;
				}
				else {
					this.textContent = $.trim(this.textContent);
					return !/\S/.test(this.nodeValue);
				}
			}).remove();
			return this;
		}
		$('#frame').htmlClean();
		var map = $('#frame').html();
		if (map.indexOf("adclogosquare") != -1) {
			var mapWidth = $('#frame').css('width');
			var mapHeight = $('#frame').css('height');
			var eventid = currentEvent;
			$.post("admin/save_map", {
				eventid: eventid,
				map: map,
				width: mapWidth,
				height: mapHeight
			}, function () {
				alert("Map saved successfully!");
			});
		}
		else {
			alert("The pit map did not save. Please include the Automation Direct logo in the pit map before saving.");
		}
	});
	$( "#reset" ).on( "click", function() {
		$('#frame').empty();
		$('#frame').append(pits);
		$(".dragAdd").draggable({
      cursor: "move",
			containment: 'parent',
			grid: [5,5],
      drag: function (event, ui) {
        var snapTolerance = $(this).draggable('option', 'snapTolerance');
        var topRemainder = ui.position.top % 5;
        var leftRemainder = ui.position.left % 5;

        if (topRemainder <= snapTolerance) {
          ui.position.top = ui.position.top - topRemainder;
        }

        if (leftRemainder <= snapTolerance) {
          ui.position.left = ui.position.left - leftRemainder;
        }
      }  
		});
		$('#frame').css('width', '326px');
		$('#frame').css('height', '526px');
	});
	$( "#clear" ).on( "click", function() {
		$('#frame').empty();
	});
	$('#change').on('click', function() {
		var width = $('#width').val();
		var height = $('#height').val();
		width = Math.ceil(width * 4);
		height = Math.ceil(height * 4);
		$('#frame').css('width', width);
        $('#frame').css('height', height);
	});
});