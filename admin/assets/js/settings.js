jQuery(document).ready(function($){
    jQuery('.color-field').wpColorPicker();

    jQuery('.tm-admin-page').on("click", ".close", function(){
        jQuery(this).parents(".box").remove();
    });
    
    jQuery('.tm-admin-page').on("change", "#full_day", function(){
        isFullDayEvent();
    });
    
    isFullDayEvent();
});

function isFullDayEvent() {
    if(jQuery("#full_day").is(':checked')) {
        jQuery('input[name="entrytime[date]"').val(jQuery('input[name="ev_date[date]"').val()).attr("disabled", true);
        jQuery('input[name="entrytime[time]"').val("00:00").attr("disabled", true);
        jQuery('input[name="ev_date[time]"').val("00:00").attr("disabled", true);
        jQuery('input[name="endtime[date]"').val(jQuery('input[name="ev_date[date]"').val()).attr("disabled", true);
        jQuery('input[name="endtime[time]"').val("23:59").attr("disabled", true);
    }else{
        jQuery('input[name="entrytime[date]"').attr("disabled", false);
        jQuery('input[name="entrytime[time]"').attr("disabled", false);
        jQuery('input[name="ev_date[time]"').attr("disabled", false);
        jQuery('input[name="endtime[date]"').attr("disabled", false);
        jQuery('input[name="endtime[time]"').attr("disabled", false);
    }
}