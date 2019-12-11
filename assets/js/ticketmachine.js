jQuery(document).on('click', '.allow-google-maps', function(){
    setCookie('allow_google_maps', 1)
    var url = jQuery(this).data("embed");
    $('.allow-google-maps-container').html("<iframe width='100%' height='300' id='mapcanvas' src='" + url + "' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'></iframe>");
});