<?php
    session_start();
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        session_unset();
    } else {
        header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php?alert=abnormal');
        exit();
    }
    header('location:http://'.$_SERVER['HTTP_HOST'].'/MIRAEBOOKS/php/home/home.php');
?>