<?php

    // $link = mysqli_connect("localhost", "cl43-dbay", "s3bYge/Bq", "cl43-dbay");

    $link = mysqli_connect("localhost", "root", "root", "auction_system");
    if(mysqli_connect_error()) {

        die("Could not connect to database");
    }
        $_SESSION['userid'] = 1;

?>