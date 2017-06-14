//Adds inspection status color to each box
function boxColor() {
    console.log('boxColor run');
	for (var i=0; i < teamsArr.length; i++){
		if (teamsArr[i][3] == 'Complete'){
			$("#" + teamsArr[i][0]).addClass('levelFive');
		}
		else if (teamsArr[i][3] == 'Major Issue'){
			$("#" + teamsArr[i][0]).addClass('levelFour');
		}
		else if (teamsArr[i][3] == 'Minor Issue'){
			$("#" + teamsArr[i][0]).addClass('levelThree');
		}
		else if (teamsArr[i][3] == 'In Progress') {
		    $("#" + teamsArr[i][0]).addClass('levelSix');
		}
		else if (teamsArr[i][3] == 'Weighed and Sized') {
		    $("#" + teamsArr[i][0]).addClass('levelSeven');
		}
		else if (teamsArr[i][3] == 'Ok to unbag'){
			$("#" + teamsArr[i][0]).addClass('levelTwo');
		}
		else if (teamsArr[i][3] == 'Not Started'){
			$("#" + teamsArr[i][0]).addClass('levelOne');
		}
	}
}
//Adds inspection status color to team detail
function mapTeamInspectStatus(index){
	if (teamsArr[index][3] == 'Complete'){
		$('.map-inspectstatus').addClass('levelFive');
	}
	else if (teamsArr[index][3] == 'Major Issue'){
		$('.map-inspectstatus').addClass('levelFour');
	}
	else if (teamsArr[index][3] == 'Minor Issue'){
		$('.map-inspectstatus').addClass('levelThree');
	}
	else if (teamsArr[index][3] == 'In Progress') {
	    $('.map-inspectstatus').addClass('levelSix');
	}
	else if (teamsArr[index][3] == 'Weighed and Sized') {
	    $('.map-inspectstatus').addClass('levelSeven');
	}
	else if (teamsArr[index][3] == 'Ok to unbag'){
		$('.map-inspectstatus').addClass('levelTwo');
	}
	else if (teamsArr[index][3] == 'Not Started'){
		$('.map-inspectstatus').addClass('levelOne');
	}
}
function clear(){
	$('.red').removeClass('red');
	$('.blue').removeClass('blue');
	$('.key').css('display','none');
}
function clearInspectionStatus(){
	$('#frame .levelFive').removeClass('levelFive');
	$('#frame .levelFour').removeClass('levelFour');
	$('#frame .levelThree').removeClass('levelThree');
	$('#frame .levelTwo').removeClass('levelTwo');
	$('#frame .levelOne').removeClass('levelOne');
	$('#frame .levelSix').removeClass('levelSix');
	$('#frame .levelSix').removeClass('levelSeven');
}
function resizeMap(viewportWidth) {
    var widthFrame = frameWidth;
    var heightFrame = frameHeight;
    var scale = viewportWidth / widthFrame;
    console.log("Viewport: " + viewportWidth);
    console.log("Frame: " + widthFrame);
    console.log("Scale: " + scale);
    var translateX = ((widthFrame * scale) - widthFrame) / 2;
    var translateY = ((heightFrame * scale) - heightFrame) / 2;
    $('.map-page').css({ 'transform': 'translate('+translateX+'px, '+translateY+'px) scale(' + scale + ',' + scale + ')', 'margin-right': '0px !important'  });
    $('.container-map-outer').css({ 'height': heightFrame * scale+'px', 'width':widthFrame*scale+'px'});
}
$(document).ready(function() {		
    $('#frame').html(mapCode);
	if((frameWidth && frameWidth != 0) && (frameHeight && frameHeight !=0)){
		$('#frame').css('width', frameWidth);
		$('#frame').css('height', frameHeight);
	}
	$('.btn-edit').remove();
	$('.btn-ok').remove();
	$('.btn-remove').remove();
	$('.btn-rotate').remove();
	$('.btn-edit-table').remove();
	$('.box').removeClass('item ui-draggable ui-draggable-handle')
	$('.box-num').removeAttr('contenteditable');
	$('.box').css('cursor','pointer');
	
	boxColor();
	$('[data-toggle="tooltip"]').tooltip();
	var lastPage = document.URL;
	var back = lastPage.includes('r1') || lastPage.includes('r2') || lastPage.includes('r3') || lastPage.includes('b1') || lastPage.includes('b2') || lastPage.includes('b3') || lastPage.includes('team');
	if (back){
		$('.btn-back').html('<a class="btn btn-default pull-left" href="javascript:history.back()"><span class="glyphicon glyphicon-chevron-left"></span>Back</a>');
		back = false;
	}	
	$(r1).addClass('red');
	$(r2).addClass('red');
	$(r3).addClass('red');
	$(b1).addClass('blue');
	$(b2).addClass('blue');
	$(b3).addClass('blue');
	$(team).addClass('blue');


    var viewportWidth = $(window).width();
    if (viewportWidth < 768) {
        resizeMap(viewportWidth);
    }


	$('.select-teams').on('click',function(e){
		e.preventDefault();
		clear();
		boxColor();
		$('.selected-team').removeClass('selected-team');
		$('.btn-m').html('Select a Match <span class="caret"></span>');
		var id = $(this).attr('id');
		var idStr = "#" + id;
		id = id.substring(4)
		$('.btn-st').html('Team ' + id  + ' <span class="caret"></span>');
		$('.dropdown-matches').css('display','inline');
		$('.select-matches').css('display','none');
		$('.select-matches:contains('+id+')').css('display','block');
		$("#"+id).parent('.box').addClass('selected-team');
	});
	$('.select-matches').on('click',function(e){
		e.preventDefault();
		var matchTitle = $(this).html();
		$('.btn-m').html(matchTitle + ' <span class="caret"></span>');
		var id = $(this).attr('id');
		idArr = id.split("-");
		var r1 = "#" + idArr[0];
		var r2 = "#" + idArr[1];
		var r3 = "#" + idArr[2];
		var b1 = "#" + idArr[3];
		var b2 = "#" + idArr[4];
		var b3 = "#" + idArr[5];
		clear();
		clearInspectionStatus();
		$(r1).addClass('red');
		$(r2).addClass('red');
		$(r3).addClass('red');
		$(b1).addClass('blue');
		$(b2).addClass('blue');
		$(b3).addClass('blue');
		$('.btn-inspection').css('display','block');
		$('.btn-inspection-hide').css('display','none');
        var currTeamId = $('.btn-st').html().replace(/\D/g, '');
        $('.status-text').html('Viewing Alliance Partners, ' + currTeamId + ', ' + $('.a-sm').html());
	});
	
	//Shows/hides inspection statuses on the map
	$('.btn-inspection').on('click',function(){
		clear();
		$('.btn-m').html('Select a Match <span class="caret"></span>');
		boxColor();
		$('.key').css({'display':'block', 'margin-left': 'auto', 'margin-right': 'auto'});
		$('.btn-inspection').css('display','none');
		$('.btn-inspection-hide').css('display','block');
		$('.status-text').html('Viewing Inspection Status');
	});
	$('.btn-inspection-hide').on('click',function(){
		clear();
		clearInspectionStatus();
		$('.btn-m').html('Select a Match <span class="caret"></span>');
		$('.btn-inspection').css('display','block');
		$('.btn-inspection-hide').css('display','none');
		$('.status-text').html('Viewing Pit Map');
	});
	
	//Fires when a team pit is clicked, shows detailed team info
	$('.box').on('click',function(){ 
		$('.levelFive').removeClass('levelFive');
		$('.levelFour').removeClass('levelFour');
		$('.levelThree').removeClass('levelThree');
		$('.levelTwo').removeClass('levelTwo');
		$('.levelOne').removeClass('levelOne');
        $('.levelSix').removeClass('levelSix');
		
		$('.map-page-team').css('display','block');
		$('.container-map-outer').css('display','none');
		
		var index;
		var id = $(this).children('.box-num').attr('id');
		for(var j = 0; j < teamsArr.length; j++) {
			if(teamsArr[j][0] == id) {
				index = j;
				break;
			}
		}
		$('.map-teamnum').html("Team " + teamsArr[index][0]);
		$('.map-teamname').html(teamsArr[index][1].substr(0,22));
		$('.map-teamlocation').html(teamsArr[index][2]);
		$('.map-inspectstatus').html(teamsArr[index][3]);
		$('.map-inspectnotes').html(teamsArr[index][4]);
		$('.map-initialinspector').html(teamsArr[index][5]);
		$('.map-inspectmodifiedby').html(teamsArr[index][6]);
		$('.map-inspectmodifiedtime').html(teamsArr[index][7]);
		mapTeamInspectStatus(index);
		
		var link = 'team.php?team=' + teamsArr[index][0] + '&event=' + currentEvent;
		$('.map-moreinfo').attr('href', link);
		
		document.getElementById('inspectNumInline').value = id;
		//document.getElementById('inspectNumNotes').value = id;
		$('#inspectionstatus option[value="'+teamsArr[index][3]+'"]').prop('selected',true);
	});
	$('.return').on('click',function(){
		$('.map-page-team').css('display','none');
		$('.container-map-outer').css('display','block');
		boxColor();
	});
	$(".inspect").click(function() {
		var team = $(".map-teamnum").text();  
		var teamid = team.substring(5);
		document.getElementById('inspectnumbermodal').value = teamid;
	});
	$('.change-status, .save-note').on('click', function(){
		var inspectnotes = $('.map-inspectnotes').val();
		var inspectionstatus = $('#inspectionstatus').val();
		var teamid = $('#inspectNumInline').val();
		$.post("admin/inspection_status.php?event="+currentEvent, {
			teamid: teamid,
			inspectionnotes: inspectnotes,
			inspectionstatus: inspectionstatus
		}, function () {
			location.reload();
		});
	});


    ////// DISPLAY MODE //////
	$("#btn-landscape-view").click(function () {
	    $('.container-map-centered').removeClass('portrait-view');
	    $('.container-map-centered').addClass('landscape-view');
	    $('#btn-landscape-view').css('display', 'none');
	    $('#btn-portrait-view').css('display', 'initial');
	    $('.box').addClass('rotate');
	});
	$("#btn-portrait-view").click(function () {
	    $('.container-map-centered').removeClass('landscape-view');
	    $('.container-map-centered').addClass('portrait-view');
	    $('#btn-landscape-view').css('display', 'initial');
	    $('#btn-portrait-view').css('display', 'none');
	    $('.box').removeClass('rotate');
	});
	$(".scale-up").click(function () {
	    scaleVal = Number($(this).attr('id'));
	    scaleVal = scaleVal + .1;
	    $(this).attr('id', scaleVal);
	    $('.scale-down').attr('id', scaleVal);
	    if ($('.container-map-centered').hasClass('landscape-view')) {
	        $('.landscape-view').css('transform', 'rotate(90deg)scale(' + scaleVal + ',' + scaleVal + ')');
	    }
	    else {
	        $('.portrait-view').css('transform', 'scale(' + scaleVal + ',' + scaleVal + ')');
	    }
	});
	$(".scale-down").click(function () {
	    scaleVal = Number($(this).attr('id'));
	    scaleVal = scaleVal - .1;
	    $(this).attr('id', scaleVal);
	    $('.scale-up').attr('id', scaleVal);
	    if ($('.container-map-centered').hasClass('landscape-view')) {
	        $('.landscape-view').css('transform', 'rotate(90deg)scale(' + scaleVal + ',' + scaleVal + ')');
	    }
	    else {
	        $('.portrait-view').css('transform', 'scale(' + scaleVal + ',' + scaleVal + ')');
	    }
	});
});