<?php
error_reporting(-1);
ini_set('display_errors', 'On');

include "notificationClasses.php";
$_SESSION["user_id"] = 2; // NB: remove this line in real app
$userID = $_SESSION["user_id"];
$numResults = 20;
$getMoreBuy = 0;
$getMoreSell = 0;

echo "<h1>Notifications</h1><hl>";

// Fetch data about joined/ watched auctions for buyer notifications
$stmt = mysqli_stmt_init($connection);
//echo gettype($stmt);
$stmt = mysqli_prepare($connection, "SELECT a.auction_id, i.item_name, i.seller_id,
                                            a.start_price, a.reserve_price,
                                            a.end_date, a.view_count, a.buyer_id,
                                            MAX(b.price)
                                     FROM `auction`   a
                                          JOIN `bid`  b ON a.auction_id = b.auction_id
                                          JOIN `item` i ON a.item_id = i.item_id
                                     WHERE b.buyer_id = ?
                                     GROUP BY b.auction_id
                                     ORDER BY a.end_date DESC
                                     LIMIT ?
                                     OFFSET ?");
//echo gettype($stmt);
$offset = $getMoreBuy*$numResults;
mysqli_stmt_bind_param($stmt, "iii", $userID, $numResults, $offset);
//echo gettype($stmt);/*
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $auctionID, $itemName, $sellerID, $startPrice,
                     $reservePrice, $endDate, $viewCount, $winnerID,
                     $userHiBid);

echo "<h2>Buyer notifications</h2>";

if (!(mysqli_stmt_fetch($stmt))){ // No notifications to show
    echo "<p>You aren't watching any auctions.</p>";
} else { // Loop over watched auctions, determine notification type & display
    do{
        $watching    = $userHiBid == 0.00;
        $auctionOver = $endDate < date("Y-m-d H:i:s");
        $winning     = $userID == $winnerID;
        if ($watching){
            if ($auctionOver){
                $bidStmt = mysqli_stmt_init($connection);
                $bidStmt = mysqli_prepare($connection, "SELECT * FROM `bid`");
                echo ($bidStmt ? "true" : "false");
                echo "<p>A watched auction ended</p>";
                /*$note = new NotifyWatchEnd($auctionID, $itemName, $startPrice,
                                          $reservePrice, $endDate, $viewCount)
               ;    $note -> show();*/
            } else {
                $note = new NotifyWatchCont($auctionID, $itemName, $endDate,
                                    $viewCount);    $note -> show();
            }
        } else {
            if ($auctionOver){
                if ($winning){
                    if ($userHiBid >= $reservePrice){
                        $note = new NotifyWon($auctionID, $itemName, $sellerID,
                                      $userHiBid);    $note -> show();
                    } else {
                        $note = new NotifyTooLow($auctionID, $itemName, $reservePrice,
                                         $endDate, $userHiBid);    $note -> show();
                    }
                } else {
                    $note = new NotifyLost($auctionID, $itemName, $reservePrice,
                                   $endDate, $userHiBid);    $note -> show();
                }
            } else {
                if ($winning){
                    $note = new NotifyWinning($auctionID, $itemName, $endDate,
                                      $userHiBid);    $note -> show();
                } else {
                    $note = new NotifyOutbid($auctionID, $itemName, $endDate,
                                     $userHiBid);    $note -> show();
                }
            }
        }
    } while (mysqli_stmt_fetch($stmt));
}
mysqli_stmt_close($stmt);



// Fetch data about auctions set up by this user
$stmt = mysqli_stmt_init($connection);
$stmt = mysqli_prepare($connection, "SELECT a.auction_id, i.item_name,
                                          a.start_price, a.reserve_price,
                                          a.start_date, a.end_date, a.view_count,
                                          a.buyer_id, MAX(b.price)
                                   FROM `auction`   a
                                        JOIN `item` i ON a.item_id = i.item_id
                                        LEFT JOIN `bid`  b ON a.auction_id = b.auction_id
                                   WHERE i.seller_id = ?
                                   GROUP BY a.auction_id
                                   ORDER BY a.end_date DESC
                                     LIMIT ?
                                     OFFSET ?");
$offset = $getMoreSell*$numResults;
mysqli_stmt_bind_param($stmt, "iii", $userID, $numResults, $offset);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $auctionID, $itemName, $startPrice, $reservePrice,
                     $startDate, $endDate, $viewCount, $winnerID, $hiBid);

echo "<hl><h2>Seller notifications</h2>";

if (!(mysqli_stmt_fetch($stmt))){ // No notifications to show
    echo "<p>You aren't selling anything.</p>";
} else { // Loop over auctions, determine notification type & display
    do{
        $auctionOver = $endDate < date("Y-m-d H:i:s");
        $gotBids     = isset($hiBid) and ($hiBid != 0.00);
        $gotReserve  = isset($hiBid) ? ($hiBid >= $reservePrice) : false;
        if ($auctionOver){
            if ($gotReserve){
                $note = new NotifySold($auctionID, $itemName, $winnerID, $hiBid);
                $note -> show();
            } else {
                if ($gotBids){
                    $note = new NotifyTooHigh($auctionID, $itemName, $reservePrice,
                                      $endDate, $hiBid);    $note -> show();
                } else {
                    $note = new NotifyNoBidEver($auctionID, $itemName, $startPrice,
                                        $startDate, $endDate, $viewCount);
                    $note -> show();
                }
            }
        } else {
            if ($gotReserve){
                $note = new NotifySelling($auctionID, $itemName, $endDate, $hiBid)
               ;    $note -> show();
            } else {
                if ($gotBids){
                    $note = new NotifyWaiting($auctionID, $itemName, $reservePrice,
                                      $endDate, $hiBid);    $note -> show();
                } else {
                    $note = new NotifyNoBidYet($auctionID, $itemName, $startPrice,
                                       $startDate, $endDate, $viewCount)
                   ;    $note -> show();
                }
            }
        }
    } while (mysqli_stmt_fetch($stmt));
}
mysqli_stmt_close($stmt);


/*/ Function to find highest bid given auction id
public function getHiBid($auctionID){
    global $connection;
    $stmt = mysqli_stmt_init($connection);
    $stmt = mysqli_prepare($connection, "SELECT MAX(`price`)
                           FROM `bid`
                           WHERE `auction_id` = ?
                           GROUP BY `auction_id`");
    mysqli_stmt_bind_param($stmt,"i",$auctionID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $hiBid);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return isset($hiBid) ? $hiBid : false;
}
*/
?>
