<?php

    #wp_enqueue_style( 'core_CSS', plugins_url('assets/css/ticketmachine.css', __FILE__ ) );
    wp_enqueue_script( 'moment_JS', plugins_url('admin/assets/js/ext/moment-with-locales.js', __FILE__ ) );
    wp_enqueue_script( 'bootstrap3_JS', plugins_url('admin/assets/js/ext/bootstrap.min.js', __FILE__ ) );
    wp_enqueue_script( 'datetimepicker_JS', plugins_url('admin/assets/js/ext/bootstrap-datetimepicker.js', __FILE__ ) );

?>

<script type="text/javascript" src="/path/to/moment.js"></script>
<script type="text/javascript" src="/path/to/bootstrap/js/transition.js"></script>
<script type="text/javascript" src="/path/to/bootstrap/js/collapse.js"></script>
<script type="text/javascript" src="/path/to/bootstrap/dist/bootstrap.min.js"></script>
<script type="text/javascript" src="/path/to/bootstrap-datetimepicker.min.js"></script>