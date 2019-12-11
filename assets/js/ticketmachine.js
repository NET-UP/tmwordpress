jQuery(document).ready(function(){
    jQuery(document).on('click', '.allow-google-maps', function(){
        createCookie('allow_google_maps', 1);
        var url = jQuery(this).data("embed");
        $('.allow-google-maps-container').html("<iframe width='100%' height='300' id='mapcanvas' src='" + url + "' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'></iframe><a class='disallow-google-maps float-right' href='#'>Google Maps nicht erlauben</a>");
    });

    jQuery(document).on('click', '.disallow-google-maps', function(){
        eraseCookie('allow_google_maps');
        location.reload();
    });

    var t = jQuery('.card-text');
    if(t.height() > 202) {
        t.addClass('closed');
    }
});