<?php
include 'include/sessions.php';
include 'include/connections.php';
include 'include/functions.php';
confirm_login();
//include "connect.php";

$auctionID = $_GET["a_id"];
$userID = $_SESSION["user_id"];
if (!isset($auctionID)){
    echo "no such auction";
    //header("Location:noauction.php");
}

// Fetch auction details
$stmt = mysqli_stmt_init($connection);
$stmt = mysqli_prepare($connection, "SELECT * FROM `auction` WHERE `auction_id` = ?");
mysqli_stmt_bind_param($stmt, "i", $auctionID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $auctionID, $itemID, $startPrice,
                     $reservePrice, $endDate, $viewCount,
                     $buyerComment, $buyerRate, $buyerReviewDate,
                     $sellerComment, $sellerRate, $sellerReviewDate, $winnerID, $startDate);
if (!(mysqli_stmt_fetch($stmt))){
    echo "failed to fetch acution details";
    //header("Location:noconnect.php");
}
mysqli_stmt_close($stmt);

// Fetch item details
$stmt = mysqli_stmt_init($connection);
$stmt = mysqli_prepare($connection, "SELECT item.*, `image`.`file_name` FROM `item` LEFT JOIN `image` ON `item`.`item_id` = `image`.`item_id` AND `image`.`is_cover_image` = 1 WHERE `item`.`item_id` = ?");
mysqli_stmt_bind_param($stmt, "i", $itemID);
mysqli_stmt_execute($stmt );
mysqli_stmt_bind_result($stmt, $itemID, $sellerID, $itemName, $itemDescription, $itemBrand, $itemCondition, $item_image);
if (!(mysqli_stmt_fetch($stmt))){
    echo "failed to fetch item details";
    //header("Location:noconnect.php");
}
mysqli_stmt_close($stmt);

// Fetch seller name
$stmt = mysqli_stmt_init($connection);
$query = "SELECT `user_email` FROM `users` WHERE `user_id` = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $sellerID);
mysqli_stmt_execute($stmt );
mysqli_stmt_bind_result($stmt, $sellerName);
if (!(mysqli_stmt_fetch($stmt))){
    echo "failed to fetch seller name";
    //header("Location:noconnect.php");
}
mysqli_stmt_close($stmt);


// Update view count (unless auction is over)
$auctionOver = $endDate < date("Y-m-d H:i:s");
if (!$auctionOver){
    $stmt = mysqli_stmt_init($connection);
    $stmt = mysqli_prepare($connection, "UPDATE `auction` SET `view_count` = ?
                           WHERE `auction_id` = ?");
    $viewCount++;
    mysqli_stmt_bind_param($stmt, "ii", $viewCount, $auctionID);
    mysqli_stmt_execute($stmt );
    mysqli_stmt_close($stmt);
}

// Fetch item categories
$catStmt = mysqli_stmt_init($connection);
$catStmt = mysqli_prepare($connection, "SELECT c.category_name
                                   FROM `item_category` ic JOIN `category` c
                                        ON ic.category_id = c.category_id
                                   WHERE ic.item_id = ?");
mysqli_stmt_bind_param($catStmt, "i", $itemID);
mysqli_stmt_execute($catStmt);
$catResult = mysqli_stmt_get_result($catStmt);
mysqli_stmt_close($catStmt);
$categories = array();
$n=0;
while ($row = mysqli_fetch_array($catResult, MYSQLI_NUM)){
    foreach($row as $r){
        $categories[$n] = $r;
        $n++;
    }
}
//mysqli_stmt_bind_result($catStmt, $categoryName);

// Fetch bid details
$bidStmt = mysqli_stmt_init($connection);
$bidStmt = mysqli_prepare($connection, "SELECT * FROM `bid` WHERE `auction_id` = ? AND `price` != -0.01
                                   ORDER BY `price` DESC LIMIT 20");
mysqli_stmt_bind_param($bidStmt, "i", $auctionID);
mysqli_stmt_execute($bidStmt);
$bidResult = mysqli_stmt_get_result($bidStmt);
mysqli_stmt_close($bidStmt);
$bids = array();
$n=0;
while ($row = mysqli_fetch_array($bidResult, MYSQLI_NUM)){
    $bids[$n] = $row;
    $n++;
}
//mysqli_stmt_bind_result($bidStmt, $buyerID, $auctionID, $bidAmount, $bidDate);

// Set flags to determine display
$loggedIn = isset($userID);
$selling = $loggedIn ? ($sellerID == $userID) : false;
$winning = isset($winnerID) ? ($loggedIn ? ($winnerID == $userID) : false) : false;
$anyBids = (count($bids) != 0);
$hiBid = $anyBids ? $bids[0][2] : 0.00;
$gotReserve = $anyBids ? ($hiBid >= $reservePrice) : false;

// Determine if user placed a bid in this auction or is watching
if ($loggedIn){
    $stmt = mysqli_prepare($connection, "SELECT MAX(`price`) FROM `bid`
                                    WHERE `buyer_id` = ? AND `auction_id` = ?");
    mysqli_stmt_bind_param($stmt, "ii", $userID, $auctionID);
    mysqli_stmt_execute($stmt );
    mysqli_stmt_bind_result($stmt, $userHiBid);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    $watching = ($userHiBid == -0.01);
    $hasBid = isset($userHiBid) ? ($userHiBid != -0.01) : false;
} else {
    $hasBid = false;
    $watching = false;
}

$avg_rate = 0;
$rate_query = "SELECT avg(buyer_rate) FROM `auction` JOIN `item` ON `auction`.`item_id` = `item`.`item_id` WHERE `item`.`seller_id` = ?";
                $avg_stmt = mysqli_prepare($connection, $rate_query);
                mysqli_stmt_bind_param($avg_stmt, "i", $sellerID);
                mysqli_stmt_execute($avg_stmt);
                mysqli_stmt_bind_result($avg_stmt, $avg_rate);
                mysqli_stmt_fetch($avg_stmt);
                mysqli_stmt_close($avg_stmt);

// Set attributes used in page display
if ($auctionOver){ // auction over
    if ($selling){ // user is seller
        if ($gotReserve){
            $alertType = "success";
            $alertMessage = "<p>You sold $itemName for &pound;$hiBid</p>";
            $userStatus = "sold";
        } else {
            $alertType = "danger";
            $alertMessage = "<p>You didn't sell $itemName.</p>";
            $userStatus = "no sale";
        }
    } else if ($winning) { // user has highest bid
        if ($gotReserve) {
            $alertType = "success";
            $alertMessage = "<p>You won the auction.</p>
                             <p>Your winning bid was &pound;$hiBid</p>";
            $userStatus = "won";
        } else {
            $alertType = "danger";
            $alertMessage = "<p>Although your bid was the highest,
                             it was below the reserve price of &pound;$reservePrice.
                             Consequently, this auction ends in no sale.</p>";
            $userStatus = "too low";
        }
    } else if ($watching) { // was watching
        $alertType = "info";
        $alertMessage = "";
        $userStatus = "watched";
    } else if ($hasBid){// user has bid which is not highest
        $alertType = "danger";
        $alertMessage = $gotReserve ? "<p>You lost the auction. The winning
                                       bid was &pound;$hiBid</p>"
                        : "<p>Nobody won this auction as all bids were below
                           the reserve price of &pound;$reservePrice.</p>
                           <p>The highest bid was &pound;$hiBid, and your
                           highest bid was &pound;$userHiBid</p>";
        $userStatus = "lost";
    } else { // not logged in / did not participate
        $alertType = "info";
        $alertMessage = "";
        $userStatus = "";
    }
} else { // auction ongoing
    if ($selling){ // user is seller
        if ($gotReserve){
            $alertType = "success";
            $userStatus = "will sell";
        } else {
            $alertType = $anyBids ? "info" : "warning";
            $userStatus = "waiting";
        }
    } else if ($winning){ // user has highest bid
        $alertType = "success";
        $userStatus = "winning";
    } else if ($watching){
        $alertType = "info";
        $userStatus = "watching";
    } else if ($hasBid) { // user has bid which is not highest
        $alertType = "warning";
        $userStatus = "losing";
    } else { // user has not bid
        $alertType = "info";
        $userStatus = "";
    }
}

// store auction data in SESSION for use in bid.php and watch.php
$_SESSION["auction_data"] = array ("auction_id" => $auctionID,
                                   "item_name" => $itemName,
                                   "start_price" => $startPrice,
                                   "end_date" => $endDate
                                   );


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>DBay - <?php echo "$itemName"; ?></title>

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href="css/style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/raty/jquery.raty.css"/>
        
        <script type="text/javascript" src="js/jquery-1.12.0.js"></script>
        <script src="js/jquery.raty.js"></script>
        <script src="js/bootstrap/bootstrap.js"></script>
        <script src="js/countdown.js"></script>
        <script type="text/javascript">
        $(document).ready(function(){
            $('#getting-started').countdown($("#getting-started").html(), function(event) {
                $(this).html(event.strftime('%d days %H:%M:%S'));
            })
            .on('finish.countdown', function(event) {
                setTimeout(function(){
                        document.location.href = document.location.href
                    },
                    1000
                );
 
            });
            

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
        <div class="container-fluid">
            <?php include "include/header.php"; ?>
            <section class="row">
                <div class="col-md-12">
                    <?php include "auction-CONTENT.php"; ?>
                </div>
            </section>
            <?php //include "footer.php"; ?>
        </div>
    </body>
</html>
