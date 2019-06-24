var matchnumber;
var starttime;
var red1;
var red2;
var red3;
var blue1;
var blue2;
var blue3;

$(document).ready(function() {
	$(".nav li").removeClass("active");
	$('#matches').addClass('active');
	
	$(".edit").click(function() {
		var row = $(this).closest("tr");  
		var matchid = $(this).closest("tr").attr('id');
		var matchnumber = row.find("#matchnumber").text(); 
		var starttime = row.find("#starttime").text(); 
		var red1 = row.find("#red1").text();
		var red2 = row.find("#red2").text();
		var red3 = row.find("#red3").text();
		var blue1 = row.find("#blue1").text();
		if (blue1 == '') {
			var row2 = row.next('tr');
			blue1 = row2.find("#blue1").text();
			blue2 = row2.find("#blue2").text();
			blue3 = row2.find("#blue3").text();
		}
		else {
			var blue2 = row.find("#blue2").text();
			var blue3 = row.find("#blue3").text();
		}
		
		document.getElementById('matchidmodal').value = matchid;
		$('#matchnumbermodal').text(matchnumber);
		document.getElementById('starttimemodal').value = starttime;
		document.getElementById('red1modal').value = red1;
		document.getElementById('red2modal').value = red2;
		document.getElementById('red3modal').value = red3;
		document.getElementById('blue1modal').value = blue1;
		document.getElementById('blue2modal').value = blue2;
		document.getElementById('blue3modal').value = blue3;
	});
	$('#addMatch').on('click', function() {
		$('.container-add').css('display','table');
	});
	$('#addMatch_cancel').on('click', function(e) {
		e.preventDefault();
		$('.container-add').css('display','none');
	});
	$("#populate").click(function() {
		$('#processing-modal').modal({
			backdrop: 'static',
			keyboard: false
		});
		$('#processing-modal').modal('show');
		var call = 'https://www.thebluealliance.com/api/v3/event/' + currentEvent + '/matches';
		var data;
		var length;
		var index = 0;
		var eventid = currentEvent;
		$.ajax({
			url: call,
			type: "GET",
			beforeSend: function(xhr){ xhr.setRequestHeader('X-TBA-Auth-Key', '8sMHphq38VPol9skertutXEJLGQFZVFubLIaUhiV0igM4SnPdzr2wvkzwyc64jZz'); },
			success: function(data) {
				length = data.length;
				document.getElementById('total-matches').innerHTML = length;
				$('.progress-bar').attr('ariavalue-max', length);
				console.log('Length: ' + length);
				for (i=0; i < length; i++){
					var pos = (data[i].key).indexOf("_");
					var key = (data[i].key).substring(pos+1);
					console.log(data[i].match_number);
					$.post("admin/add_match", {
						eventid: eventid,
						matchid: key,
						matchnumber: data[i].match_number,
						setnumber: data[i].set_number,
						starttime: data[i].time_string,
						red1: data[i].alliances.red.team_keys[0].substring(3),
						red2: data[i].alliances.red.team_keys[1].substring(3),
						red3: data[i].alliances.red.team_keys[2].substring(3),
						blue1: data[i].alliances.blue.team_keys[0].substring(3),
						blue2: data[i].alliances.blue.team_keys[1].substring(3),
						blue3: data[i].alliances.blue.team_keys[2].substring(3),
						matchtype: data[i].comp_level,
						auto: true
					}, function () {
						index++;
						document.getElementById('current-matches').innerHTML = index;
						$('.progress-bar').css('width', index*100/length + '%').attr('aria-valuenow', index);
						console.log('Index: ' + index);
						if (index == length) {
							document.getElementById('processing-modal-title').innerHTML = 'Finished!';
							document.getElementById('modal-body-text').innerHTML = 'The matches have been added! Click "Refresh" or refresh the page to see the changes.';
							document.getElementById('processing-modal-footer').classList.remove('hidden');
							document.getElementById('progressbar').classList.remove('progress-bar-warning');
							document.getElementById('progressbar').classList.remove('active');
							document.getElementById('progressbar').classList.add('progress-bar-success');
						}
					});
				}
			}
		});
	});
});