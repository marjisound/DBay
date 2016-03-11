<?php

session_start();



//include "connect.php";
include "include/db_connect.php";
include "notificationClasses.php";
//llater get it from login page
$_SESSION["userID"] = 1;
$userID = $_SESSION["userID"];
// not used in current version tells page how many to load
$numResults = 20;
$getMoreBuy = 0;
$getMoreSell = 0;

echo "<h1>Notifications</h1><hl>";

// Fetch data about joined/ watched auctions for buyer notifications
$stmt = $connection->prepare("SELECT `a`.`auction_id`, `i`.`itemname`, `i`.`sellerid`,
                                       `start_price`, `reserve_price`,
                                       `end_date`, `view_count`, `a`.`buyer_id`,
                                       MAX(b.price)
                                FROM `auction`   a
                                     JOIN `bid`  b ON a.auction_id = b.auction_id
                                     JOIN `item` i ON a.item_id = i.itemid
                                WHERE b.buyer_id = ?
                                GROUP BY b.auction_id
                                ORDER BY a.end_date
                                LIMIT ?
                                OFFSET ?
                               ");
$offset = $getMoreBuy*$numResults;
$stmt -> bind_param("iii", $userID, $numResults, $offset);
$stmt -> execute();
$stmt -> bind_result($auctionID, $itemName, $sellerID, $startPrice,
                     $reservePrice, $endDate, $viewCount, $winnerID,
                     $userHiBid);

echo "<h2>Buyer notifications</h2>";

if (!($stmt -> fetch())){ // No notifications to show
    echo "<p>You aren't watching any auctions.</p>";
} else { // Loop over watched auctions, determine notification type & display
    do{
        $watching    = $userHiBid == 0.00;
        $auctionOver = $endDate < date("Y-m-d H:i:s");
        $winning     = $userID == $winnerID;
        if ($watching){
            if ($auctionOver){
               $x = new NotifyWatchEnd($auctionID, $itemName, $startPrice,
                                          $reservePrice, $endDate, $viewCount);
               $x->show();
            } else {
               $x = new NotifyWatchCont($auctionID, $itemName, $endDate,
                                    $viewCount);
                                    $x -> show();
            }
        } else {
            if ($auctionOver){
                if ($winning){
                    if ($userHiBid >= $reservePrice){
                      $x =  new NotifyWon($auctionID, $itemName, $sellerID,
                                      $userHiBid);
                      $x -> show();
                    } else {
                      $x =   new NotifyTooLow($auctionID, $itemName, $reservePrice,
                                         $endDate, $userHiBid);
                      $x -> show();
                    }
                } else {
                   $x =  new NotifyLost($auctionID, $itemName, $reservePrice,
                                   $endDate, $userHiBid);
                                   $x -> show();
                }
            } else {
                if ($winning){
                   $x = new NotifyWinning($auctionID, $itemName, $endDate,
                                      $userHiBid);
                                      $x -> show();
                } else {
                  $x =  new NotifyOutbid($auctionID, $itemName, $endDate,
                                     $userHiBid);
                                     $x -> show();
                }
            }
        }
    } while ($stmt -> fetch());
}



// Fetch data about auctions set up by this user
$stmt = $connection -> prepare("SELECT `a`.`auction_id`, `i`.`itemname`,
                                       `start_price`, `reserve_price`,
                                       `start_date`, `end_date`, `view_count`,
                                       `a`.`buyer_id`, MAX(b.price)
                                FROM `auction`   a
                                     JOIN `bid`  b ON a.auction_id = b.auction_id
                                     JOIN `item` i ON a.item_id = i.itemid
                                WHERE i.sellerid = ?
                                GROUP BY b.auction_id
                                ORDER BY a.end_date
                                LIMIT ?
                                OFFSET ?
                               ");
$offset = $getMoreSell*$numResults;
$stmt -> bind_param("iii", $userID, $numResults, $offset);
$stmt -> execute();
$stmt -> bind_result($auctionID, $itemName, $startPrice, $reservePrice,
                     $startDate, $endDate, $viewCount, $winnerID, $hiBid);

echo "<hl><h2>Seller notifications</h2>";

if (!($stmt -> fetch())){ // No notifications to show
    echo "<p>You aren't selling anything.</p>";
} else { // Loop over auctions, determine notification type & display
    do{
        $auctionOver = $endDate < date("Y-m-d H:i:s");
        $gotBids     = isset($hiBid) and ($hiBid != 0.00);
        $gotReserve  = isset($hiBid) ? ($hiBid >= $reservePrice) : false;
        if ($auctionOver){
            if ($gotReserve){
               $x =  new NotifySold($auctionID, $itemName, $winnerID, $hiBid);

               $x -> show();
            } else {
                if ($gotBids){
                   $x =  new NotifyTooHigh($auctionID, $itemName, $reservePrice,
                                      $endDate, $hiBid);
                                      $x -> show();
                } else {
                  $x =  new NotifyNoBidEver($auctionID, $itemName, $startPrice,
                                        $startDate, $endDate, $viewCount);
                }
            }
        } else {
            if ($gotReserve){
               $x = new NotifySelling($auctionID, $itemName, $endDate, $hiBid);

               $x -> show();
            } else {
                if ($gotBids){
                  $x =  new NotifyWaiting($auctionID, $itemName, $reservePrice,
                                      $endDate, $hiBid);
                                      $x -> show();
                } else {
                   $x = new NotifyNoBidYet($auctionID, $itemName, $startPrice,
                                       $startDate, $endDate, $viewCount);
                  $x  -> show();
                }
            }
        }
    } while ($stmt -> fetch());
}

?>
