$(document).ready(function() {	
	$(".nav li").removeClass("active");
	$('#inspect').addClass('active');
			
	$(document).on("click", "#changeStatus", function () {
		var row = $(this).closest("tr");  
		var teamid = row.find("#teamid").text();
		var inspectionstatus = row.find("#inspectionstatus").text();
		var robotweight = row.find("#robotweight").text();
		var redbumperweight = row.find("#redbumperweight").text();
		var bluebumperweight = row.find("#bluebumperweight").text();
    var notes = row.find("#inspectionnotes").text();
		console.log(teamid);
		document.getElementById('getteamid').innerHTML = teamid;
    document.getElementById('inspectnumbermodal').value = teamid;
    document.getElementById('edit-status').value = inspectionstatus;
    document.getElementById('edit-robotweight').value = robotweight;
    document.getElementById('edit-redbumperweight').value = redbumperweight;
    document.getElementById('edit-bluebumperweight').value = bluebumperweight;
    document.getElementById('edit-notes').value = notes;
	});
	$(document).on("click", "#editNotes", function() {
		var row = $(this).closest("tr");  
		var teamid = row.find("#teamid").text(); 
		console.log(teamid);
		document.getElementById('getteamid2').innerHTML = teamid;
		document.getElementById('inspectnumbermodal-notes').value = teamid;
		var notes = row.find("#inspectionnotes").text(); 
		$('.map-inspectnotes-modal').html(notes);
	});
	$('.list-view').click(function() {
		$('.list-view').css('display','none');
		$('.map-view').css('display','inline-block');
		$('.inspection-map-view').css('display','none');
		$('.inspection-list-view').css('display','initial');
	});
	$('.map-view').click(function() {
		$('.list-view').css('display','inline-block');
		$('.map-view').css('display','none');
		$('.inspection-map-view').css('display','initial');
		$('.inspection-list-view').css('display','none');
    });
});