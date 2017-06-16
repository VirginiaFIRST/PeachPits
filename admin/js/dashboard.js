function filterEvents() {
    var input, filter, table, tr, td, i;
    input = document.getElementById("event-filter-field");
    filter = input.value.toUpperCase();
    table = document.getElementById("events-all");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length - 1; i++) { // un-comment when adding search all events button
        td = tr[i].getElementsByTagName("td")[0];
        if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
            console.log(tr[i]);
        }
    }

}

$(document).ready(function() {
    $(".nav li").removeClass("active");
    $('#dashboard').addClass('active');

    // For search function
    $('#events-all tr').click(function () {
        window.location = $(this).attr('href');
        return false;
    });

    $(document).on('keyup', '#event-filter-field', function (e) {
        filterEvents();
        console.log('keyup');
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