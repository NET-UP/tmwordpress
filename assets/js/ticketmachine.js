jQuery(document).ready(function(){

    jQuery(document).on('click', '.allow-google-maps', function(e){
        createCookie('allow_google_maps', 1);
        var url = jQuery(this).data("embed");
        $('.allow-google-maps-container').html("<iframe width='100%' height='300' id='mapcanvas' src='" + url + "' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'></iframe><a class='disallow-google-maps float-right' href='#'>Google Maps nicht erlauben</a>");
        e.preventDefault();
    });

    jQuery(document).on('click', '.disallow-google-maps', function(){
        eraseCookie('allow_google_maps');
        location.reload();
    });

    var t = jQuery('.card-text');
    if(t.height() > 170) {
        t.addClass('closed');
        t.parent().find('.read-more-container').removeClass('hidden');
    }

    jQuery(document).on('click', '.read-more:not(.open)', function(e){
        $(this).html('<i class="fas fa-chevron-up"></i>').addClass('open');
        t.parent().find('.card-text').removeClass('closed');
        if ($('.card-text').height() > 720 && $(window).width() > 991) {
            $('.tm_actions').clone().appendTo('.tm_left');
        }
        e.preventDefault();
    });

    jQuery(document).on('click', '.read-more.open', function(e){
        $(this).html('<i class="fas fa-chevron-down"></i>').removeClass('open');
        t.parent().find('.card-text').addClass('closed');
        if ($('.tm_actions').length === 2) {
            $('.tm_left').find(".tm_actions").remove();
        }
        e.preventDefault();
    });

    jQuery(".share-popup").click(function(){
    var window_size = "width=585,height=511";
    var url = this.href;
    var domain = url.split("/")[2];
    switch(domain) {
        case "www.facebook.com":
            window_size = "width=585,height=368";
            break;
        case "www.twitter.com":
            window_size = "width=585,height=261";
            break;
    }
    window.open(url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,' + window_size);
    return false;
});

});