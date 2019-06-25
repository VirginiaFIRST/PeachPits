$(document).ready(function() {
	$(".nav li").removeClass("active");
	$('#events').addClass('active');


    var yearsArr = eventsArr.map(function (value, index) { return value[8]; }); //get all years (column 8)
    var years = yearsArr.filter(function (elem, index, self) { return (index == self.indexOf(elem) && elem.length == 4); });
    years = years.sort(function (a, b) { return b - a });
    console.log(years);
    var date = new Date();
    var currentYear = date.getFullYear();

    jQuery.each(years, function (index, item) {
        $('.year-filter').append('<button id="'+item+'" class="btn btn-default btn-year-filter">' + item + '</button>');
        if (currentYear == parseInt(item)) {
            $("#"+item).addClass('year-showing');
        }
    });

    $('.not-live .event-info').each(function () {
        if ($(this).attr('id').indexOf(currentYear) < 0) {
            $(this).css('display', 'none'); 
        }
    });

    $('.btn-year-filter').on('click', function (e) {
        currentYear = $(this).attr('id');
        $('.not-live .event-info').each(function () {
            if ($(this).attr('id').indexOf(currentYear) < 0) {
                $(this).css('display', 'none');
            }
            else {
                $(this).css('display', '');
            }
        });
        $('.year-showing').removeClass('year-showing');
        $(this).addClass('year-showing');
    });

	$('.event-details-link').on('click', function(e){
		e.preventDefault();
		$('.event-details').css('display','block');
		$('.event-list').css('display','none');
		
		$('.status-green').removeClass('status-green');
		$('.status-red').removeClass('status-red');
		
		var index;
		var id = $(this).closest("tr").attr('id');
		for(var j = 0; j < eventsArr.length; j++) {
			if(eventsArr[j][0] == id) {
				index = j;
				break;
			}
		}
		
		$('.event-name').html(eventsArr[index][1]);
		$('.event-location').html(eventsArr[index][4]);
		$('.event-address').html(eventsArr[index][5]);
		if(eventsArr[index][3]){$('.event-district').html(eventsArr[index][3]);}
		$('.event-start').html(eventsArr[index][6]);
		$('.event-end').html(eventsArr[index][7]);
		$('.event-status').html($('#'+id+'.event-info').children('#eventstatus').html());
		$('.event-type').html(eventsArr[index][9]);
		
		$('.toggle-status').attr('id', id);
		$('.delete-event').attr('id', id);
		$('.save-event').attr('id', id);
		
		if($('#'+id+'.event-info').children('#eventstatus').html() == 'Live'){
			$('.btn-event-status').addClass('status-green');
		}
		else if($('#'+id+'.event-info').children('#eventstatus').html() == 'Not Live'){
			$('.btn-event-status').addClass('status-red');
		}
		
		$('#event-teams').attr('href',"admin/manage-teams?event=" + id);
		$('#event-matches').attr('href', "admin/manage-matches?event=" + id);
		$('#event-inspections').attr('href', "admin/manage-inspection?event=" + id);
		$('#event-announcements').attr('href', "admin/manage-announcements?event=" + id);
		$('#event-map').attr('href', "admin/manage-map?event=" + id);
		
	});
	$('.toggle-status').on('click', function(e){
		e.preventDefault();
		var currentStatus = $(this).siblings('.event-status').text();
		var eventid = $(this).attr('id');
		$.post("admin/toggle_status", {
			eventid: eventid,
			currentStatus: currentStatus
		}, function () {
			if(currentStatus=='Live'){
				$('.btn-event-status').removeClass('status-green');
				$('.btn-event-status').addClass('status-red');
				$('.event-status').html('Not Live');
				$('#'+eventid+'.event-info').children('#eventstatus').html('Not Live');
			}
			else if(currentStatus=='Not Live'){
				$('.btn-event-status').removeClass('status-red');
				$('.btn-event-status').addClass('status-green');
				$('.event-status').html('Live');
				$('#'+eventid+'.event-info').children('#eventstatus').html('Live');
			}
		});
	});
	$('.event-details-return').on('click', function(){
		$('.event-details').css('display','none');
		$('.event-list').css('display','block');
	});
	$('#addEvent').on('click', function() {
		$('.container-add').css('display','table');
	});
	$('#addEvent_cancel').on('click', function(e) {
		e.preventDefault();
		$('.container-add').css('display','none');
	});
	$(".edit-event").click(function() {
		$('.event-start, .event-end, .event-type, .event-location, .event-address, .event-district').addClass('editable-event');
		$('.event-start, .event-end, .event-type, .event-location, .event-address, .event-district').attr('contenteditable','true');
		$('.edit-event').css('display','none');
		$('.save-event').css('display', 'initial');
	});
	$(".save-event").click(function() {
		$('.event-start, .event-end, .event-type, .event-location, .event-address, .event-district').removeClass('editable-event');
		$('.event-start, .event-end, .event-type, .event-location, .event-address, .event-district').attr('contenteditable','false');
		$('.event-start, .event-end, .event-type, .event-location, .event-address, .event-district').removeAttr('contenteditable');

		var eventid = $(this).attr('id');
		var eventStart = $('.event-start').text();
		var eventEnd = $('.event-end').text();
		var eventType = $('.event-type').text();
		var eventLocation = $('.event-location').text();
		var eventAddress = $('.event-address').text();
		var eventDistrict = $('.event-district').text();

		$('.edit-event').css('display','initial');
		$('.save-event').css('display', 'none');

		$.post("admin/edit_event?event="+currentEvent, {
			eventid: eventid,
			eventStart: eventStart,
			eventEnd: eventEnd,
			eventType: eventType,
			eventLocation: eventLocation,
			eventAddress: eventAddress,
			eventDistrict: eventDistrict
		}, function () {
			alert('Save Successful!')
			location.reload();
		});	
	});
	$('.delete-event').on('click', function(e) {
		var confirmDelete = confirm("Are you sure you want to delete this event? This cannot be undone.");
		if (confirmDelete){
			var eventDelete = $(this).attr('id');
			$.post("admin/delete_event?event="+currentEvent, {
				eventDelete: eventDelete,
			}, function () {
				location.reload();
			});	
		}
	});
	$('.clear-event').on('click', function(e) {
		var confirmClear = confirm("Are you sure you want to clear this event? This cannot be undone.");
		if (confirmClear){
			$.post("admin/clear_event?event="+currentEvent, {
			}, function () {
				alert('Event Cleared!' +currentEvent);
			});	
		}
	});
  function filterEvents() {
    var filter = $("#events-notlive-search-field").val().toUpperCase();
    $("#table-not-live tr").each(function (item) {
      
      if ($(this).html().toUpperCase().indexOf(filter) > -1) {
        $(this).css('display', '');
      }
      else {
        if (!($(this).parent().is('thead')))
          
        $(this).css('display', 'none');
      }
    });
    var visibleRows = $('#table-not-live tbody tr:visible').length;
    if (visibleRows <= 1) {
      //code for no results found
    }
  }
  $(document).on('keyup', '#events-notlive-search-field', function (e) {
    filterEvents();
  });
	$("#populate").click(function() {
		var date = new Date();
		var year = date.getFullYear();
		var confirmAutoFill = confirm("Are you sure you want to do this? This will get all of the events for " + year + " and delete all events that are before " + year + ".");
		if (confirmAutoFill){
      $('#processing-modal').modal({
        backdrop: 'static',
        keyboard: false
      });
      $('#processing-modal').modal('show');
			$.post("admin/delete_old_events", {
			}, function () {
				var call = 'https://www.thebluealliance.com/api/v3/events/' + year ;
				var data;
				var length;
				var index = 0;
				//var eventid = currentEvent;
				$.ajax({
					url: call,
					type: "GET",
					beforeSend: function(xhr){ xhr.setRequestHeader('X-TBA-Auth-Key', '8sMHphq38VPol9skertutXEJLGQFZVFubLIaUhiV0igM4SnPdzr2wvkzwyc64jZz'); },
					success: function(data) { 
						length = data.length;
						document.getElementById('total-events').innerHTML = length;
						$('.progress-bar').attr('ariavalue-max', length);
						console.log('Length: ' + length);
						for (i=0; i < length; i++){
							console.log(data[i].key);
							$.post("admin/add_event", {
								eventid: data[i].key,
								eventname: data[i].name,
								eventlocation: data[i].location,
								eventaddress: data[i].venue_address,
								eventstart: data[i].start_date,
								eventend: data[i].end_date,
								eventdistrict: data[i].district.display_name,
								eventyear: data[i].year,
								eventtype: data[i].event_type_string,
								auto: true
							}, function () {
								index++;
								document.getElementById('current-events').innerHTML = index;
								$('.progress-bar').css('width', index*100/length + '%').attr('aria-valuenow', index);
								console.log('Index: ' + index);
								if (index == length) {
									document.getElementById('processing-modal-title').innerHTML = 'Finished!';
									document.getElementById('modal-body-text').innerHTML = 'The events have been added! Click "Refresh" or refresh the page to see the changes.';
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
		}			
	});
});