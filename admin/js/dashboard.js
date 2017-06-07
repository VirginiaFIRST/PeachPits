
$(document).ready(function() {
    $(".nav li").removeClass("active");
    $('#dashboard').addClass('active');

    $('.deleteall').on('click', function(e) {
		var confirmDelete = confirm("Are you sure you want to DELETE ALL events? This cannot be undone.");
		if (confirmDelete){
			$.post("admin/deleteall.php", {
			}, function () {
				alert('All events have been deleted.')
			});	
		}
	});
	$('.clearall').on('click', function(e) {
		var confirmClear = confirm("Are you sure you want to CLEAR ALL events? This cannot be undone.");
		if (confirmClear){
			$.post("admin/clearall.php?event="+currentEvent, {
			}, function () {
				alert('All events have been cleared.');
			});	
		}
	});
});