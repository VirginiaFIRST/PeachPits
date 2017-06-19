//function filterEvents() {
//    var input, filter, table, tr, td, i;
//    input = document.getElementById("event-filter-field");
//    filter = input.value.toUpperCase();
//    table = document.getElementById("events-all");
//    tr = table.getElementsByTagName("tr");
//    for (i = 0; i < tr.length - 1; i++) { // un-comment when adding search all events button
//        td = tr[i].getElementsByTagName("td")[0];
//        if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
//            tr[i].style.display = "";
//        } else {
//            tr[i].style.display = "none";
//            console.log(tr[i]);
//        }
//    }
//}

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