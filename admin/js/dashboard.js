function filterEvents() {
    var filter = $("#event-filter-field").val().toUpperCase();
    $("#events-all tr").each(function (item) {
        if ($(this).html().toUpperCase().indexOf(filter) > -1) {
            $(this).css('display', '');
        }
        else {
            $(this).css('display', 'none');
        }
    });
}
function filterEventsM() {
    var filter = $("#event-filter-field-m").val().toUpperCase();
    $("#events-all-m tr").each(function (item) {
        if ($(this).html().toUpperCase().indexOf(filter) > -1) {
            $(this).css('display', '');
        }
        else {
            $(this).css('display', 'none');
        }
    });
}

$(document).ready(function() {
    $(".nav li").removeClass("active");
    $('#dashboard').addClass('active');

    // For search function
    $('#events-all tr, #events-all-m tr').click(function () {
        window.location = $(this).attr('href');
        return false;
    });

    $(document).on('keyup', '#event-filter-field, #event-filter-field-m', function (e) {
        if ($(this).attr('id') == 'event-filter-field') {
            filterEvents();
        }
        else {
            filterEventsM();
        }
    });

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