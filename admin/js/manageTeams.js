var teamid;
var teamname;
var schoolname;
var location; 
$(document).ready(function() {
	$(".nav li").removeClass("active");
	$('#teams').addClass('active');
	
	$(".edit").click(function() {
		var row = $(this).closest("tr");  
		var teamid = row.find("#teamid").text(); 
		var teamname= row.find("#teamname").text(); 
		var schoolname = row.find("#schoolname").text(); 
		var location = row.find("#location").text(); 
		
		$('#teamnumbermodal').text(teamid);
		document.getElementById('teamnamemodal').value = teamname;
		document.getElementById('schoolnamemodal').value = schoolname;
		document.getElementById('locationmodal').value = location;

		$('#inspectionstatus option[value="' + teamsArr[index][3] + '"]').prop('selected', true);
		
		$('.remove-team').attr('id', teamid);
	});
	$('#addTeam').on('click', function() {
		$('.container-add').css('display','table');
	});
	$('#addTeam_cancel').on('click', function(e) {
		e.preventDefault();
		$('.container-add').css('display','none');
	});
	$('.remove-team').on('click', function(e) {
		var confirmRemove = confirm("Are you sure you want to remove this team from the event?");
		if (confirmRemove){
			var removeTeam = $(this).attr('id');
			$.post("admin/remove_team.php?event="+currentEvent, {
				removeTeam: removeTeam,
			}, function () {
				alert('The team has been removed. Don\'t forget to remove them from the pit map!');
				location.reload();
			});	
		}
	});
	$('#lookup-team').on('click',function(e){
		e.preventDefault();
		var teamNum = $('#teamid').val();
		var call = 'https://www.thebluealliance.com/api/v2/team/frc' + teamNum;
		$.ajax({
			url:  call,
			type: 'GET',
			beforeSend: function(xhr){ xhr.setRequestHeader('X-TBA-App-Id', 'gabriel_arkanum:peachpits:v1'); },
			success: function(data) {
				$('#teamname').val(data.nickname);
				$('#schoolname').val(data.name);
				$('#location').val(data.location);
			}
		});
	});
	$("#populate").on('click',function() {
		var call = 'https://www.thebluealliance.com/api/v2/event/' + currentEvent + '/teams';
		//var call = 'https://frc-api.firstinspires.org/v2.0/season/events?eventCode=CMP&teamNumber=1&districtCode=PNW&excludeDistrict=true';
		var data;
		var length;
		var eventid = currentEvent;
		$.ajax({
			url:  call,
			type: 'GET',
			beforeSend: function(xhr){ xhr.setRequestHeader('X-TBA-App-Id', 'gabriel_arkanum:peachpits:v1'); },
			//beforeSend: function(xhr){ xhr.setRequestHeader('Authorization', 'Basic ' + window.btoa('peachpits:87A9A9B0-E253-46A8-9D40-38C91B5B5837')); },
			success: function(data) { 
				//alert(data);
				length = data.length;
				for (i=0; i < length; i++){
					$.post("admin/add_team.php", {
						eventid: eventid,
						teamid: data[i].team_number,
						teamname: data[i].nickname,
						schoolname: data[i].name,
						location: data[i].location,
						eventList: true,
						auto: true
					}, function () {
						
					});
				}
				alert("Finished! Click 'Ok' to refresh the page.");
				location.reload();
			}
		});

		//var request = new XMLHttpRequest();

		//request.open('GET', 'https://frc-api.firstinspires.org/v2.0/2016/events?eventCode=CMP&teamNumber=1&districtCode=PNW&excludeDistrict=true');
		
		//request.setRequestHeader('Accept', 'application/json');
		//request.setRequestHeader('Authorization', 'cGVhY2hwaXRzOjg3QTlBOUIwLUUyNTMtNDZBOC05RDQwLTM4QzkxQjVCNTgzNw==');
		
		//request.onreadystatechange = function () {
		//	if (this.readyState === 4) {
		//		console.log('Status:', this.status);
		//		console.log('Headers:', this.getAllResponseHeaders());
		//		console.log('Body:', this.responseText);
		//	}
		//};
		
		//request.send();

	
	});

	$('.change-status, .save-note').on('click', function () {
	    var inspectnotes = $('.map-inspectnotes').val();
	    var inspectionstatus = $('#inspectionstatus').val();
	    var teamid = $('#teamnumbermodal').text();
	    console.log(teamid);
	    $.post("admin/inspection_status.php?event=" + currentEvent, {
	        teamid: teamid,
	        inspectionnotes: inspectnotes,
	        inspectionstatus: inspectionstatus
	    }, function () {
	        location.reload();
	    });
	});




});