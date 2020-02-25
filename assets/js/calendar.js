var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

jQuery("#ticketmachine_spinner").show();
var locale= jQuery(".ticketmachine_page").data("locale");
var ev_url= jQuery("#ticketmachine_ev_url").val();
var cal_query = "";
var cal_sort = "";
var cal_tag = "";
jQuery.getJSON(ajax_object.ajax_url, { "q": cal_query, "sort": cal_sort, "tag": cal_tag, "action": "ticketmachine_calendar_ajax_events"}).success(function(data) {

	alert("test");
	jQuery("#ticketmachine_spinner").hide();
	var events_array = data;

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
		locale: locale,
		height: "auto",
		firstDay: 1,
		eventLimit: false, // allow "more" link when too many events
		events: events_array,
		defaultDate: events_array[events_array.length - 1]['defaultDate'],
		loading: function(bool) {
			document.getElementById('loading').style.display =
			bool ? 'block' : 'none';
		}
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

}).fail(function(jqXHR, status, error){
	jQuery("#ticketmachine_spinner").hide();
	jQuery("#ticketmachine_cal_error").show();
});
  
  