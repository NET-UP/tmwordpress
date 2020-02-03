jQuery(document).ready(function($){
    jQuery('.color-field').wpColorPicker();

    jQuery('.tm-admin-page').on("click", ".close", function(){
        jQuery(this).parents(".box").remove();
    });
});