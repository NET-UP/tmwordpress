jQuery(document).on('click', '.allow-google-maps', function(){
    setCookie('allow_google_maps', 1)
    location.reload();
});