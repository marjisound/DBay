<?php
include 'include/sessions.php';
include 'include/connections.php';
include 'include/functions.php';

extract($_GET);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
        $pageTitle = 'Seller';
        include('include/head.php');
        ?>
        <script src="js/jquery.raty.js"></script>
        <link rel="stylesheet" type="text/css" href="css/raty/jquery.raty.css"/>
        <link rel="stylesheet" type="text/css" href="css/review.css"/>
        <script type="text/javascript">
        $(document).ready(function(){
            $('.rate-star').raty({
            path: 'css/raty/images',
            starOff: 'star-off.png',
            starOn: 'star-on.png',
            readOnly: true,
            score: function(){
                return $(this).attr('data-score');
            }
            });
        });
        </script>
    </head>
    <body> 
        <a href="user.php?u=<?php echo $u;?>&amp;role_type=1">Seller</a>
        &nbsp;-&nbsp;
        <a href="user.php?u=<?php echo $u;?>&amp;role_type=2">Buyer</a>
        <?php
        if($role_type == '2'){
            // fetch buyer comments
            $query = "select auction.buyer_comment, auction.buyer_rate, auction.buyer_review_date";
            $query .= ", concat(u.first_name, ' ', u.last_name) as user_name";
            $query .= " from auction";
            $query .= " left join item";
            $query .= " on auction.item_id = item.item_id";
            $query .= " left join users as u";
            $query .= " on auction.buyer_id = u.user_id";
            $query .= " where item.seller_id = ?";
            $query .= " and auction.end_date < now()";
            $query .= " and auction.buyer_review_date is not null";
            $query .= " order by buyer_review_date desc";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "i", $u);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $comment, $rate, $date, $name);
        }
        else{
            // fetch seler comments
            $query = "select auction.seller_comment, auction.seller_rate, auction.seller_review_date";
            $query .= ", concat(u.first_name, ' ', u.last_name) as user_name";
            $query .= " from auction";
            $query .= " left join item";
            $query .= " on auction.item_id = item.item_id";
            $query .= " left join users as u";
            $query .= " on auction.buyer_id = u.user_id";
            $query .= " where item.seller_id = ?";
            $query .= " and auction.end_date < now()";
            $query .= " and auction.seller_review_date is not null";
            $query .= " order by seller_review_date desc";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "i", $u);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $comment, $rate, $date, $name);
        }

        while (mysqli_stmt_fetch($stmt)) {
        ?>

        <div class="row">
            <div class="col-sm-12">
                <b><?php echo $name.' - '.$date;?></b>:
                <div>
                <div class="rate-star" data-score="<?php echo $rate;?>"></div>
                </div>
                <?php 
                if(!empty($comment)){
                    echo '<div>'.nl2br($comment).'</div><br />';
                }
                ?>
            </div>
        </div>
        <?php
        }
        mysqli_stmt_close($stmt);
        ?>


    </body>
</html>