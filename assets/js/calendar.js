var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();
var events_array = new Array();

$.getJSON('/wp-content/plugins/ticketmachine/event.php' + location.search, function(data) {
	events_array = data;
	return events_array;
});
console.log(events_array);


  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
		plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list', 'bootstrap' ],
		themeSystem: 'bootstrap',
		titleFormat: {
			month: 'short',
			year: 'numeric'
		},
		views: {
			listMonth: { noEventsMessage: "Für diesen Monat sind keine Veranstaltungen verfügbar" },
			dayGridMonth: { }
		},
		defaultView: (function () { if (jQuery(window).width() <= 768) { return defaultView = 'listMonth'; } else { return defaultView = 'dayGridMonth'; } })(),
		header: false,
		weekNumbers: false,
		locale: "de",
		height: "auto",
		firstDay: 1,
		eventLimit: false, // allow "more" link when too many events
		events: events_array,
		loading: function(bool) {
			document.getElementById('loading').style.display =
			bool ? 'block' : 'none';
		}
    });

    var dateObj = new Date();
    var month = dateObj.getUTCMonth() + 1; //months from 1-12
    var day = dateObj.getUTCDate();
    var year = dateObj.getUTCFullYear();

    events_array.sort(function(x, y){
        return new Date(year, month, day).getTime() -  new Date(x.start).getTime();
	});

	calendar.render();
	
	var view = calendar.view;
	
	jQuery("#calendar-title").html(view.title);
	
	// Next/Prev buttons
	jQuery("#calendar-next").on('click', function() {
		var view = calendar.view;
		calendar.next();
		jQuery("#calendar-title").html(view.title);
	});
	jQuery("#calendar-prev").on('click', function() {
		var view = calendar.view;
		calendar.prev();
		jQuery("#calendar-title").html(view.title);
	});
	jQuery(window).on('resize', function() {
		if(jQuery(window).width() <= 768){
			calendar.changeView('listMonth');
		}else{
			calendar.changeView('dayGridMonth');
			
		}
	});
	
  });

  
  