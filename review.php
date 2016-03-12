<?php include 'include/sessions.php' ?>
<?php include 'include/connections.php' ?>
<?php require_once'include/functions.php' ?>
<?php extract($_POST);

    if (isset($btnSubmit)) {
         if(empty($score)) {
            $error="Please rate and then click submit.";
        } else { 

            $query = "UPDATE `auction`";
            if($role_type == '1') {
                $query .= " set `seller_rate` = ?, `seller_comment` = ?, seller_review_date = now()";
            }
            else {
                $query .= " set `buyer_rate` = ?, `buyer_comment` = ?, buyer_review_date = now()";
            }
               
            $query .= " where auction_id = ?";

            $stmt = mysqli_prepare($link, $query);

            mysqli_stmt_bind_param($stmt,'isi', $score, $comments, $auction_id);

            mysqli_stmt_execute($stmt);
 
        }
     
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
        $pageTitle = 'Auction Creation';
        include('include/head.php');
        ?>
        <script src="js/jquery.raty.js"></script>
        <link rel="stylesheet" type="text/css" href="css/raty/jquery.raty.css"/>
        <link rel="stylesheet" type="text/css" href="css/review.css"/>
    </head>
    <body> 
        <?php 
            include ('include/header.php');
        ?>
        <div id="box">
            <div class="container">
                <form method="post">
                    <input type="hidden" name="auction_id" value="<?php echo $_GET['auction_id'];?>" />
                    <input type="hidden" name="role_type" value="<?php echo $_GET['type'];?>" />
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="rate-star" tabindex="0" role="button" aria-label="select to rate item one star">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-9">
                            <textarea class="comment-field" name="comments" id="comments" style="font-family:sans-serif;font-size:1.2em;" placeholder="Please add your comment!"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <input class="btnSubmit" type="submit" value="Submit" name="btnSubmit">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>





