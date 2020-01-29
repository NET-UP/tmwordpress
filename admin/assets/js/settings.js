jQuery(document).ready(function($){
    $('.color-field').wpColorPicker();

    $('.tm-admin-page').on("click", ".close", function(){
        $(this).parents(".box").remove();
    })
});