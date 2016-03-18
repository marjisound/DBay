<?php 
include 'include/sessions.php';
include 'include/connections.php';
require_once'include/functions.php';
?>
<!doctype html>
<html>
<head>
  <title>Dbay</title>
  <meta charset="utf-8" />
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/header.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body>
  <div class = "container">
    <div>
        <?php
        include ('include/header.php');
        ?>
    </div>

    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="3000">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
      </ol>
 
      <!-- Wrapper for slides -->
      <div class="carousel-inner">
        <div class="item active">
          <img src="http://placehold.it/1200x315" alt="...">
          <div class="carousel-caption">
              <h3>Caption Text</h3>
          </div>
        </div>
        <div class="item">
          <img src="http://placehold.it/1200x315" alt="...">
          <div class="carousel-caption">
              <h3>Caption Text</h3>
          </div>
        </div>
        <div class="item">
          <img src="http://placehold.it/1200x315" alt="...">
          <div class="carousel-caption">
              <h3>Caption Text</h3>
          </div>
        </div>
      </div>
 
      <!-- Controls -->
      <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
      </a>
      <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
      </a>
    </div> <!-- Carousel -->


    <!-- Footer section -->
    <footer>
    <?php include 'include/footer.php'; ?>
    </footer>
  </div>
   
</body>

</html>