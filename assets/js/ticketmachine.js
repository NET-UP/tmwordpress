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
        t.parent().find('.read-more-container').removeClass('hidden');
    }

    jQuery(document).on('click', '.read-more:not(.open)', function(){
        $(this).html('<i class="fas fa-chevron-up"></i>').addClass('open');
        t.parent().find('.card-text').removeClass('closed');
    });

    jQuery(document).on('click', '.read-more.open', function(){
        $(this).html('<i class="fas fa-chevron-down"></i>').removeClass('open');
        t.parent().find('.card-text').addClass('closed');
    });

});