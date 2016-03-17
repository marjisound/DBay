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
   
<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
    <li data-target="#myCarousel" data-slide-to="3"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="http://science-all.com/image.php?pic=/images/sky/sky-01.jpg" alt="">
    </div>

    <div class="item">
      <img src="http://science-all.com/image.php?pic=/images/sky/sky-01.jpg" alt="">
    </div>

    <div class="item">
      <img src="http://science-all.com/image.php?pic=/images/sky/sky-01.jpg" alt="">
    </div>

    <div class="item">
      <img src="http://science-all.com/image.php?pic=/images/sky/sky-01.jpg" alt="">
    </div>
  </div>

  <!-- Left and right controls -->
 <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a> 
</div>

<!-- Footer section -->
 <footer>
  <?php include 'include/footer.php'; ?>
 </footer>
</div>
   
</body>

</html>