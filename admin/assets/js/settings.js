jQuery(document).ready(function ($) {
  jQuery(".color-field").wpColorPicker();

  jQuery(".tm-admin-page").on("click", ".close", function () {
    jQuery(this).parents(".box").remove();
  });

  jQuery(".tm-admin-page").on("change", "#full_day", function () {
    tm_isFullDayEvent();
  });

  tm_isFullDayEvent();
});

function tm_isFullDayEvent() {
  if (jQuery("#full_day").is(":checked")) {
    jQuery('input[name="entrytime[date]"').val(jQuery('input[name="ev_date[date]"').val()).attr("readonly", true);
    jQuery('input[name="entrytime[time]"').val("00:00").attr("readonly", true);
    jQuery('input[name="ev_date[time]"').val("00:00").attr("readonly", true);
    jQuery('input[name="endtime[date]"').val(jQuery('input[name="ev_date[date]"').val()).attr("readonly", true);
    jQuery('input[name="endtime[time]"').val("23:59").attr("readonly", true);
  } else {
    jQuery('input[name="entrytime[date]"').attr("readonly", false);
    jQuery('input[name="entrytime[time]"').attr("readonly", false);
    jQuery('input[name="ev_date[time]"').attr("readonly", false);
    jQuery('input[name="endtime[date]"').attr("readonly", false);
    jQuery('input[name="endtime[time]"').attr("readonly", false);
  }
}

jQuery(document).on("submit", "#event", function (e) {
  const errors = [];

  inputs = {};
  input_serialized = jQuery(this).serializeArray();
  input_serialized.forEach((field) => {
    const field_name = field.name.replace("[", ".").replace("]", "");
    inputs[field_name] = field.value;
  });

  if (!inputs["ev_name"]) {
    errors.push("no_title");
  }

  const now = Date();
  const start = tm_convertDate(inputs["ev_date.date"], inputs["ev_date.time"]);
  const entry = tm_convertDate(inputs["entrytime.date"], inputs["entrytime.time"]);
  const end = tm_convertDate(inputs["endtime.date"], inputs["endtime.time"]);

  if (+start >= +end) {
    errors.push(tm_t("start_too_late"));
  }
  if (+entry >= +end) {
    errors.push(tm_t("entry_too_late"));
  }
  if (+now >= +end) {
    errors.push(tm_t("end_too_early"));
  }

  if (errors.length > 0) {
    errors.forEach((error) => {
      alert(error);
    });
    e.preventDefault();
    return false;
  }
});

function tm_convertDate(date, time) {
  const dmy = date.split(".");
  const hm = time.split(":");
  return new Date(dmy[2], dmy[1] - 1, dmy[0], hm[0], hm[1]);
}

function tm_t(key) {
  const docLang = document.documentElement.lang.split("-")[0];
  const de = {
    start_too_late: "Das Beginndatum muss vor dem Enddatum liegen.",
    entry_too_late: "Die Einlasszeit muss vor dem Enddatum liegen.",
    end_too_early: "Das Enddatum darf nicht in der Vergangenheit liegen",
  };
  const en = {
    start_too_late: "The start date must be before the end date.",
    entry_too_late: "The entry date must be before the end date.",
    end_too_early: "The end date can not be in the past.",
  };
  const translate = { en, de };

  return translate[docLang][key];
}
