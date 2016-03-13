<?php
include 'include/session.php';
include 'include/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>DBay - Auction</title>

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href="css/style.css" rel="stylesheet">
    </head>


    <body>
        <div class="container-fluid">
            <?php include "include/header/header.php"; ?>
            <section class="row">
                <div class="col-md-12">
                    <?php include "auction-CONTENT.php"; ?>
                </div>
            </section>
            <?php //include "footer.php"; ?>
        </div>
    
        <script src=\"js/jquery.min.js\"></script>
        <script src=\"js/bootstrap.min.js\"></script>
        <script src=\"js/scripts.js\"></script>
    </body>
</html>
