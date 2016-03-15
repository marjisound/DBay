<?php
include 'include/sessions.php';
include 'include/connections.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>DBay - Notifications</title>

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href="css/style.css" rel="stylesheet">

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
    </head>


    <body>
        <div class="container-fluid">
            <?php include "include/header.php"; ?>
            <section class="row">
                <div class="col-md-12">
                    <?php include "notifications-CONTENT.php"; ?>
                </div>
            </section>
            <?php //include "footer.php"; ?>
        </div>
    

    </body>
</html>
