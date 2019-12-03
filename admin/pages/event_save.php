<?php 
    if(!isset($_POST['shown'])) {
        $_POST['shown'] = 0;
    }
    if(isset($_POST['tags'])) {
        $_POST['tags'] = explode(",", $_POST['tags']);
    }
    if(isset($_POST['entrytime'])) {
        $entrytime = new DateTime($_POST['entrytime']['date'] . $_POST['entrytime']['time']);
        $_POST['entrytime'] = $entrytime->format(DateTime::ATOM);
    }
    print_r($_POST); 

?>