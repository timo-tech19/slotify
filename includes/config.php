<?php
    // Turns on output buffering
    ob_start();
    session_start();
    $timezone = date_default_timezone_set('Africa/Douala');

    // opens DB connection
    $con = mysqli_connect('localhost', 'root', '', 'slotify');
    if(mysqli_connect_errno()) {
        echo 'Failed to connect to database ' . mysqli_connect_errno();
    }