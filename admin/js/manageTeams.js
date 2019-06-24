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
		document.getElementById('teamnumbermodal2').value = teamid;
		document.getElementById('teamnamemodal').value = teamname;
		document.getElementById('schoolnamemodal').value = schoolname;
		document.getElementById('locationmodal').value = location;

		//$('#inspectionstatus option[value="' + teamsArr[index][3] + '"]').prop('selected', true);
		
		$('.remove-team').attr('id', teamid);
	});
	$('#addTeam').on('click', function() {
		$('.container-add').css('display','table');
		$('.container-add-many').css('display','none');
	});
	$('#addTeam_cancel').on('click', function(e) {
		e.preventDefault();
		$('.container-add').css('display','none');
	});
	$('#add-many-teams').on('click', function() {
		$('.container-add-many').css('display','table');
		$('.container-add').css('display','none');
	});
	$('#add-many-dismiss').on('click', function(e) {
		e.preventDefault();
		$('.container-add-many').css('display','none');
	});
	$('.remove-team').on('click', function(e) {
		var confirmRemove = confirm("Are you sure you want to remove this team from the event?");
		if (confirmRemove){
			var removeTeam = $(this).attr('id');
			$.post("admin/remove_team?event="+currentEvent, {
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
		var call = 'https://www.thebluealliance.com/api/v3/team/frc' + teamNum;
		$.ajax({
			url:  call,
			type: 'GET',
			beforeSend: function(xhr){ xhr.setRequestHeader('X-TBA-Auth-Key', '8sMHphq38VPol9skertutXEJLGQFZVFubLIaUhiV0igM4SnPdzr2wvkzwyc64jZz'); },
			success: function(data) {
				$('#teamname').val(data.nickname);
				$('#schoolname').val(data.name);
				$('#location').val(data.city + ', ' + data.state_prov + ' ' + data.postal_code + ', ' + data.country);
			}
		});
	});
	$("#populate").on('click',function() {
		$('#processing-modal').modal({
			backdrop: 'static',
			keyboard: false
		});
		$('#processing-modal').modal('show');
		var call = 'https://www.thebluealliance.com/api/v3/event/' + currentEvent + '/teams';
		//var call = 'https://frc-api.firstinspires.org/v2.0/season/events?eventCode=CMP&teamNumber=1&districtCode=PNW&excludeDistrict=true';
		var data;
		var length;
		var index = 0;
		var eventid = currentEvent;
		$.ajax({
			url:  call,
			type: 'GET',
			beforeSend: function(xhr){ xhr.setRequestHeader('X-TBA-Auth-Key', '8sMHphq38VPol9skertutXEJLGQFZVFubLIaUhiV0igM4SnPdzr2wvkzwyc64jZz'); },
			//beforeSend: function(xhr){ xhr.setRequestHeader('Authorization', 'Basic ' + window.btoa('peachpits:87A9A9B0-E253-46A8-9D40-38C91B5B5837')); },
			success: function(data) { 
				//alert(data);
				length = data.length;
				document.getElementById('total-teams').innerHTML = length;
				$('.progress-bar').attr('ariavalue-max', length);
				console.log('Length: ' + length);
				for (i=0; i < length; i++){
					console.log(data[i].team_number);
					var datalocation = data[i].city + ', ' + data[i].state_prov + ' ' + data[i].postal_code + ', ' + data[i].country;
					$.post("admin/add_team", {
						eventid: eventid,
						teamid: data[i].team_number,
						teamname: data[i].nickname,
						schoolname: data[i].name,
						location: datalocation,
						eventList: true,
						auto: true
					}, function () {
						index++;
						document.getElementById('current-teams').innerHTML = index;
						$('.progress-bar').css('width', index*100/length + '%').attr('aria-valuenow', index);
						console.log('Index: ' + index);
						if (index == length) {
							document.getElementById('processing-modal-title').innerHTML = 'Finished!';
							document.getElementById('modal-body-text').innerHTML = 'The teams have been added! Click "Refresh" or refresh the page to see the changes.';
							document.getElementById('processing-modal-footer').classList.remove('hidden');
							document.getElementById('progressbar').classList.remove('progress-bar-warning');
							document.getElementById('progressbar').classList.remove('active');
							document.getElementById('progressbar').classList.add('progress-bar-success');
						}
					});
				}
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

	$("#submit-many-btn").on('click',function() {
		$('#processing-modal').modal({
			backdrop: 'static',
			keyboard: false
		});
		$('#processing-modal').modal('show');
		var items = document.getElementsByClassName('item');
		document.getElementById('total-teams').innerHTML = items.length;
		$('.progress-bar').attr('ariavalue-max', items.length);
		var index2 = 0;
		for (var index = 0; index < items.length; index++) {
			var teamid = items[index].innerText;
			var eventid = currentEvent;
			var call = 'https://www.thebluealliance.com/api/v3/team/frc' + teamid;
			$.ajax({
				url:  call,
				type: 'GET',
				beforeSend: function(xhr){ xhr.setRequestHeader('X-TBA-Auth-Key', '8sMHphq38VPol9skertutXEJLGQFZVFubLIaUhiV0igM4SnPdzr2wvkzwyc64jZz'); },
				success: function(data) {
					var datalocation = data.city + ', ' + data.state_prov + ' ' + data.postal_code + ', ' + data.country;
					console.log(data.team_number);
					$.post("admin/add_team", {
						eventid: eventid,
						teamid: data.team_number,
						teamname: data.nickname,
						schoolname: data.name,
						location: datalocation,
						eventList: true,
						auto: true
					}, function () {
						index2++;
						document.getElementById('current-teams').innerHTML = index2;
						$('.progress-bar').css('width', index2*100/items.length + '%').attr('aria-valuenow', index2);
						console.log('Index: ' + index2);
						if (index2 == index) {
							document.getElementById('processing-modal-title').innerHTML = 'Finished!';
							document.getElementById('modal-body-text').innerHTML = 'The teams have been added! Click "Refresh" or refresh the page to see the changes.';
							document.getElementById('processing-modal-footer').classList.remove('hidden');
							document.getElementById('progressbar').classList.remove('progress-bar-warning');
							document.getElementById('progressbar').classList.remove('active');
							document.getElementById('progressbar').classList.add('progress-bar-success');
						}
					});
				}
			});
		}
	});

	$('.change-status, .save-note').on('click', function () {
	    var inspectnotes = $('.map-inspectnotes').val();
	    var inspectionstatus = $('#inspectionstatus').val();
	    var teamid = $('#teamnumbermodal').text();
	    console.log(teamid);
	    $.post("admin/inspection_status?event=" + currentEvent, {
	        teamid: teamid,
	        inspectionnotes: inspectnotes,
	        inspectionstatus: inspectionstatus
	    }, function () {
	        location.reload();
	    });
	});




});