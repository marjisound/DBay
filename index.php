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

    <?php
      $query = "SELECT item.item_name, item.item_description, auction.end_date, auction.auction_id, image.file_name
              FROM item JOIN auction 
              ON item.item_id = auction.item_id 
              JOIN image ON item.item_id = image.item_id
              WHERE auction.end_date > now() 
              ORDER BY RAND() LIMIT 3";
              
      $result = mysqli_query($connection, $query);
      while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        echo '<div class="col-sm-4">
                <img src="uploads/'.$row['file_name'].'" height="200" width="200"><br>
                <a href="auction.php?a_id='.$row['auction_id'].'">'.$row['item_name'].'</a>
                 <p>'.$row['item_description'].'</p>
                 <p>End date: '.$row['end_date'].'</p>
              </div>';

      }
      mysqli_free_result($result);


    ?>


    <!-- Footer section -->
    <footer>
    <?php include 'include/footer.php'; ?>
    </footer>
  </div>
   
</body>

</html>