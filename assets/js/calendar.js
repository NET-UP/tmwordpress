var today = new Date()
var dd = String(today.getDate()).padStart(2, "0")
var mm = String(today.getMonth() + 1).padStart(2, "0") //January is 0!
var yyyy = today.getFullYear()

var ev_url = jQuery("#ticketmachine_ev_url").val()
var ev_params = jQuery("#ticketmachine_ev_params").val()
var urlParams
;(window.onpopstate = function () {
  var match,
    pl = /\+/g, // Regex for replacing addition symbol with a space
    search = /([^&=]+)=?([^&]*)/g,
    decode = function (s) {
      return decodeURIComponent(s.replace(pl, " "))
    },
    //query  = window.location.search.substring(1);
    query = ev_params

  urlParams = {}
  while ((match = search.exec(query)))
    urlParams[decode(match[1])] = decode(match[2])
})()

jQuery("#ticketmachine_spinner").show()
var locale = jQuery(".ticketmachine_page").data("locale")
var calendar
var events = []
var data = {
  action: "ticketmachine_calendar",
  q: urlParams["q"],
  pg: 1,
  per_page: 100,
  sort: "ev_date",
  tag: urlParams["tag"],
  approved: urlParams["approved"],
}
jQuery
  .getJSON(ticketmachine_calendar_data.ajaxurl, data)
  .success(function (data) {
    jQuery("#ticketmachine_spinner").hide()
    var events_array = data["data"]

    for (var event of events_array) {
      events.push(event)
    }

    var calendarEl = document.getElementById("calendar")

    calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: ["interaction", "dayGrid", "timeGrid", "list", "bootstrap"],
      themeSystem: "bootstrap",
      titleFormat: {
        month: "short",
        year: "numeric",
      },
      views: {
        listMonth: {
          noEventsMessage:
            "Für diesen Monat sind keine Veranstaltungen verfügbar",
        },
        dayGridMonth: {},
      },
      defaultView: (function () {
        if (jQuery(window).width() <= 768) {
          return (defaultView = "listMonth")
        } else {
          return (defaultView = "dayGridMonth")
        }
      })(),
      header: false,
      weekNumbers: false,
      locale: locale,
      height: "auto",
      firstDay: 1,
      eventLimit: false, // allow "more" link when too many events
      events,
      defaultDate: events_array[events_array.length - 1]["defaultDate"],
      loading: function (bool) {
        document.getElementById("loading").style.display = bool
          ? "block"
          : "none"
      },
    })

    calendar.render()

    var view = calendar.view

    jQuery("#calendar-title").html(view.title)

    // Next/Prev buttons
    jQuery("#calendar-next").on("click", function () {
      var view = calendar.view
      calendar.next()
      getMoreEvents()
      jQuery("#calendar-title").html(view.title)
    })
    jQuery("#calendar-prev").on("click", function () {
      var view = calendar.view
      calendar.prev()
      jQuery("#calendar-title").html(view.title)
    })
    jQuery(window).on("resize", function () {
      if (jQuery(window).width() <= 768) {
        calendar.changeView("listMonth")
      } else {
        calendar.changeView("dayGridMonth")
      }
    })
  })
  .fail(function (jqXHR, status, error) {
    jQuery("#ticketmachine_spinner").hide()
    jQuery("#ticketmachine_cal_error").show()
  })

let getMoreEvents = () => {
  data.pg++
  jQuery
    .getJSON(ticketmachine_calendar_data.ajaxurl, data)
    .success(function (data) {
      var events_array = data["data"]
      for (var event of events_array) {
        events.push(event)

        calendar.addEvent(event)
      }

      if (!events_array.length) {
        getMoreEvents = () => {
          return
        }
      }
    })
}
