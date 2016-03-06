<?php

include "connect.php";
include "notificationClasses.php"
$userID = $_SESSION["userID"];
$numResults = 20;
$getMoreBuy = 0;
$getMoreSell = 0;

echo "<h1>Notifications</h1><hl>";

// Fetch data about joined/ watched auctions for buyer notifications
$stmt = $connection -> prepare("SELECT `auction_id`, `item_name`, `seller_id`,
                                       `start_price`, `reserve_price`,
                                       `end_date`, `view_count`, `winner_id`,
                                       MAX(b.price)
                                FROM `auction`   a
                                     JOIN `bid`  b ON a.auction_id == b.auction_id
                                     JOIN `item` i ON a.item_id == i.item_id
                                WHERE b.buyer_id = ?
                                GROUP BY b.auction_id
                                ORDER BY a.end_date
                                LIMIT ?
                                OFFSET = ?
                               ");
$stmt -> bind_param("iii", $userID, $numResults, $getMoreBuy*$numResults);
$stmt -> execute();
$stmt -> bind_result($auctionID, $itemName, $sellerID, $startPrice,
                     $reservePrice, $endDate, $viewCount, $winnerID,
                     $userHiBid);

echo "<h2>Buyer notifications</h2>";

if (!($stmt -> fetch())){ // No notifications to show
    echo "You aren't watching any auctions.";
} else { // Loop over watched auctions, determine notification type & display
    do{
        $watching    = $userHiBid == 0.00;
        $auctionOver = $endDate < date("Y-m-d H:i:s");
        $winning     = $userID == $winnerID;
        if ($watching){
            if ($auctionOver){
                new NotifyWatchEnd($auctionID, $itemName, $startPrice,
                                          $reservePrice, $endDate, $viewCount)
                -> show();
            } else {
                new NotifyWatchCont($auctionID, $itemName, $endDate,
                                    $viewCount) -> show();
            }
        } else {
            if ($auctionOver){
                if ($winning){
                    if ($userHiBid >= $reservePrice){
                        new NotifyWon($auctionID, $itemName, $sellerID,
                                      $userHiBid) -> show();
                    } else {
                        new NotifyTooLow($auctionID, $itemName, $reservePrice,
                                         $endDate, $userHiBid) -> show();
                    }
                } else {
                    new NotifyLost($auctionID, $itemName, $reservePrice,
                                   $endDate, $userHiBid) -> show();
                }
            } else {
                if ($winning){
                    new NotifyWinning($auctionID, $itemName, $endDate,
                                      $userHiBid) -> show();
                } else {
                    new NotifyOutbid($auctionID, $itemName, $endDate,
                                     $userHiBid) -> show();
                }
            }
        }
    } while ($stmt -> fetch());
}



// Fetch data about auctions set up by this user
$stmt = $connection -> prepare("SELECT `auction_id`, `item_name`,
                                       `start_price`, `reserve_price`,
                                       `start_date`, `end_date`, `view_count`,
                                       `winner_id`, MAX(b.price)
                                FROM `auction`   a
                                     JOIN `bid`  b ON a.auction_id == b.auction_id
                                     JOIN `item` i ON a.item_id == i.item_id
                                WHERE a.seller_id = ?
                                GROUP BY b.auction_id
                                ORDER BY a.end_date
                                LIMIT ?
                                OFFSET = ?
                               ");
$stmt -> bind_param("iii", $userID, $numResults, $getMoreSell*$numResults);
$stmt -> execute();
$stmt -> bind_result($auctionID, $itemName, $startPrice, $reservePrice,
                     $startDate, $endDate, $viewCount, $winnerID, $hiBid);

echo "<hl><h2>Seller notifications</h2>";

if (!($stmt -> fetch())){ // No notifications to show
    echo "You aren't selling anything.";
} else { // Loop over auctions, determine notification type & display
    do{
        $auctionOver = $endDate < date("Y-m-d H:i:s");
        $gotBids     = isset($hiBid) and ($hiBid != 0.00);
        $gotReserve  = isset($hiBid) ? ($hiBid >= $reservePrice) : false;
        if ($auctionOver){
            if ($gotReserve){
                new NotifySold($auctionID, $itemName, $winnerID, $hiBid)
                -> show();
            } else {
                if ($gotBids){
                    new NotifyTooHigh($auctionID, $itemName, $reservePrice,
                                      $endDate, $hiBid) -> show();
                } else {
                    new NotifyNoBidEver($auctionID, $itemName, $startPrice,
                                        $startDate, $endDate, $viewCount);
                }
            }
        } else {
            if ($gotReserve){
                new NotifySelling($auctionID, $itemName, $endDate, $hiBid)
                -> show();
            } else {
                if ($gotBids){
                    new NotifyWaiting($auctionID, $itemName, $reservePrice,
                                      $endDate, $hiBid) -> show();
                } else {
                    new NotifyNoBidYet($auctionID, $itemName, $startPrice,
                                       $startDate, $endDate, $viewCount)
                    -> show();
                }
            }
        }
    } while ($stmt -> fetch());
}

?>
