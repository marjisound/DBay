<?php

// Server script called from auction page to place a bid on an item

// Set database connection, user id, auction id
// TODO: check existence of all variables
$dbConnection = $_SESSION["dbConnection"];
$userID     = $_SESSION["userID"];
$auctionID  = $_SESSION["currAuctionID"];

// Retrieve user's bid from form
$userBid = $_POST["amount"];

// Set, prepare bind and execute SQL to find auction end date
// (Assuming PDO)
$stmt = $connection -> prepare("SELECT `end_date`
                                FROM `auction`
                                WHERE `auction_id` = :auction
                               ");
$stmt -> bind_param(':auction', $auctionID);
$dbConnection -> execute($stmt);
// TODO: Error handling if auction not found
$endDate = $dbConnection -> fetchColumn();

// Set, prepare bind and execute SQL to find current high bid
// (Assuming PDO)
$stmt = $connection -> prepare("SELECT MAX(`amount`)
                                FROM `bid`
                                WHERE `auction_id` = :auction
                                GROUP BY `auction_id`
                               ");
$stmt -> bind_param(':auction', $auctionID);
$dbConnection -> execute($stmt);
// TODO: Checking for first bid placed (i.e. $maxbid does not exist)
$maxBid = $dbConnection -> fetchColumn();

// Enter bid or fail according to bid value (and date)

if ($userBid > $maxBid){
    if(date("Y-m-d H:i:s") < $endDate){
        $stmt = $connection -> prepare("INSERT INTO `bid`
                                       VALUES (:user, :auction, :amount, NOW())
                                       ");
        $stmt -> bind_param(':user', $userID);
        $stmt -> bind_param(':auction', $auctionID);
        $stmt -> bind_param(':amount', $userBid);
        $dbConnection -> execute($stmt);
        $_SESSION["bidStatus"] = array("auction" => $auctionID, "status" => "SUCCESS", "when" => date("Y-m-d H:i:s"));
        header("Location: auction.php?auction-id=$auctionID");
    } else {
        $_SESSION["bidStatus"] = array("auction" => $auctionID, "status" => "TOO LATE", "when" => date("Y-m-d H:i:s"));
        header("Location: auction.php?auction-id=$auctionID");
    }
} else {
    $_SESSION["bidStatus"] = array("auction" => $auctionID, "status" => "TOO LOW", "when" => date("Y-m-d H:i:s"));
    header("Location: auction.php?auction-id=$auctionID");
}

?>
