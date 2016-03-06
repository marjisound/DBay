<?php

// Set database connection, user id, auction id
$dbConnection = $_SESSION["dbConnection"];
$userID     = $_SESSION["userID"];
$auctionID  = $_SESSION["currAuctionID"];

// Retrieve user's bid from form
$userBid = $_POST["amount"];

// Set, prepare bind and execute SQL to find auction end date & start price
// (Assuming mysqli)
// Optional: also fetch seller id if sellers restricted from bidding in own auctions
$stmt = $dbConnection -> prepare("SELECT `end_date`, `start_price`
                                FROM `auction`
                                WHERE `auction_id` = ?
                               ");
$stmt -> bind_param("i", $auctionID);
$stmt -> execute();
// TODO: Exception handling for missing auction / duplicate auctions
$stmt -> bind_result($endDate, $startPrice);
$stmt -> fetch();

// Optional stop & return to auction page if seller id = buyer id
/*
if ( $userID == $sellerID ){
$_SESSION["bidStatus"] = array("auction" => $auctionID, "status" => "RESTRICTED", "when" => date("Y-m-d H:i:s"));
header("Location: auction.php?auction-id=$auctionID");
}
*/

// Set, prepare bind and execute SQL to find current high bid
// (Assuming mysqli)
$stmt = $dbConnection -> prepare("SELECT MAX(`amount`)
                                FROM `bid`
                                WHERE `auction_id` = ?
                                GROUP BY `auction_id`
                               ");
$stmt -> bind_param("i", $auctionID);
$dbConnection -> execute($stmt);
$stmt -> bind_result($maxBid);
if ($stmt -> fetch){
	// A previous bid exists
} else {
	$maxbid = $startPrice;
}


// Enter bid or fail according to bid value (and date)
if ($userBid > $maxBid){
    if(date("Y-m-d H:i:s") < $endDate){
        $stmt = $dbConnection -> prepare("INSERT INTO `bid`
                                       VALUES (?, ?, ?, NOW())
                                       ");//   user, auction, amount 
        $stmt -> bind_param("iid", $userID, $auctionID, $userBid);
        $dbConnection -> execute($stmt);
        // TODO: error handling for insertion failure
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
// nb: header could redirect to seperate bid confirmation page

?>
