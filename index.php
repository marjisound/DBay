<?php include 'include/sessions.php' ?>
<?php include 'include/connections.php' ?>
<?php require_once'include/functions.php' ?>

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
  
    <div class="container" style="padding:0px" >
         <ul class="nav navbar-nav">
         <li class="dropdown" style="background-color:green">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Category1  <span class="caret"></span></a>
          <ul class="dropdown-menu forAnimate" role="menu">
            <li><a href="#">subcat1</a></li>
            <li><a href="#">subcat2</a></li>
          </ul>
        <li class="dropdown" style="background-color:yellow;color:black">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Category2  <span class="caret"></span></a>
          <ul class="dropdown-menu forAnimate" role="menu">
            <li><a href="#">subcat1</a></li>
            <li><a href="#">subcat2</a></li>
          </ul>
           <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Category3  <span class="caret"></span></a>
          <ul class="dropdown-menu forAnimate" role="menu">
            <li><a href="#">subcat1</a></li>
            <li><a href="#">subcat2</a></li>
          </ul>
        </li>          
       
      </ul>
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
      <img src="" alt="">
    </div>

    <div class="item">
      <img src="" alt="">
    </div>

    <div class="item">
      <img src="" alt="">
    </div>

    <div class="item">
      <img src="" alt="">
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
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>More</h3>

            <div class="tabbable-panel">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs ">
                        <li class="active">
                            <a href="#tab_default_1" data-toggle="tab">
                            Help </a>
                        </li>
                        <li>
                            <a href="#tab_default_2" data-toggle="tab">
                            About </a>
                        </li>
                        <li>
                            <a href="#tab_default_3" data-toggle="tab">
                            Legal </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_default_1">
                            <p>
                                I'm in Tab 1.
                            </p>
                            <p>
                                Duis autem eum iriure dolor in hendrerit in vulputate velit esse molestie consequat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.
                            </p>
                            <p>
                                <a class="btn btn-success" href="http://j.mp/metronictheme" target="_blank">
                                    Learn more...
                                </a>
                            </p>
                        </div>
                        <div class="tab-pane" id="tab_default_2">
                            <p>
                                Howdy, I'm in Tab 2.
                            </p>
                            <p>
                                Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat. Ut wisi enim ad minim veniam, quis nostrud exerci tation.
                            </p>
                            <p>
                                <a class="btn btn-warning" href="http://j.mp/metronictheme" target="_blank">
                                    Click for more features...
                                </a>
                            </p>
                        </div>
                        <div class="tab-pane" id="tab_default_3">
                            <p>
                                Howdy, I'm in Tab 3.
                            </p>
                            <p>
                                Duis autem vel eum iriure dolor in hendrerit in vulputate. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat
                            </p>
                            <p>
                                <a class="btn btn-info" href="http://j.mp/metronictheme" target="_blank">
                                    Learn more...
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>
 </footer>
</div>
   
</body>

</html>