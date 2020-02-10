<?php
    global $globals, $api;
    $current_locale = get_locale();
    $parsed_locale = substr($current_locale, 0, strpos($current_locale, '_'));

    $authorize_url = $api->auth->proxy;
    $authorize_url .= "?";
    $authorize_url .= http_build_query($api->auth->data);
?>

<a class="button button-primary mt-4" style="font-size:14px" href="<?php echo $authorize_url; ?>">
    <i class="fas fa-sync-alt"></i> &nbsp;<?php echo __("Sync events", "ticketmachine"); ?>
</a>