<?php
    $current_locale = get_locale();
    $parsed_locale = substr($current_locale, 0, strpos($current_locale, '_'));

    $authorize_url = $api->auth->proxy;
    $authorize_url .= "?";
    $authorize_url .= http_build_query($api->auth->data);
?>

<a class="button button-primary mt-4 px-3 py-md-1" style="font-size:14px" href="<?php echo $authorize_url; ?>">
    <?php echo __("Sync events", "ticketmachine"); ?>
</a>