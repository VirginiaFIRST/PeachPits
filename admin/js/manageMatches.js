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
		var blue2 = row.find("#blue2").text();
		var blue3 = row.find("#blue3").text(); 
		
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
		var call = 'https://www.thebluealliance.com/api/v2/event/' + currentEvent + '/matches';
		var data;
		var length;
		var eventid = currentEvent;
		$.ajax({
			url: call,
			type: "GET",
			beforeSend: function(xhr){ xhr.setRequestHeader('X-TBA-App-Id', 'gabriel_arkanum:peachpits:v1'); },
			success: function(data) { 
				length = data.length;
				for (i=0; i < length; i++){
					var pos = (data[i].key).indexOf("_");
					var key = (data[i].key).substring(pos+1);
					$.post("admin/add_match.php", {
						eventid: eventid,
						matchid: key,
						matchnumber: data[i].match_number,
						setnumber: data[i].set_number,
						starttime: data[i].time_string,
						red1: data[i].alliances.red.teams[0].substring(3),
						red2: data[i].alliances.red.teams[1].substring(3),
						red3: data[i].alliances.red.teams[2].substring(3),
						blue1: data[i].alliances.blue.teams[0].substring(3),
						blue2: data[i].alliances.blue.teams[1].substring(3),
						blue3: data[i].alliances.blue.teams[2].substring(3),
						matchtype: data[i].comp_level,
						auto: true
					}, function () {
						
					});
				}
				alert("Finished! Click 'Ok' to refresh the page.");
				location.reload();
			}
		});
	});
});