<?php

include 'include/sessions.php';
include 'include/connections.php';
// Set database connection, user id, auction id
//$_SESSION["user_id"] = 3; // Delete this line from final app
$userID = $_SESSION["user_id"];
$auctionData = $_SESSION["auction_data"];
if (!isset($auctionData)){
    echo "problem getting auction data";
    //header("Location:noauction.php");
}

// Retrieve user's bid from form
$userBid = $_POST["amount"];
// Unpack values from auction data array
$auctionID = $auctionData["auction_id"];
$itemName = $auctionData["item_name"];

// Set, prepare bind and execute SQL to find item name, auction end date & start price
// (Assuming mysqli)
// Optional: also fetch seller id if sellers restricted from bidding in own auctions
/*
$stmt = mysqli_stmt_init($connection);
$stmt = mysqli_prepare($connection, "SELECT a.end_date, a.start_price, i.item_name
                                FROM `auction` a JOIN `item` i ON a.item_id = i.item_id
                                WHERE `auction_id` = ?
                               ");
mysqli_stmt_bind_param($stmt, "i", $auctionID);
mysqli_stmt_execute($stmt);
// TODO: Exception handling for missing auction / duplicate auctions
mysqli_stmt_bind_result($stmt, $endDate, $startPrice, $itemName);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
*/

// Optional stop & return to auction page if seller id = buyer id
/*
if ( $userID == $sellerID ){
$_SESSION["bidStatus"] = array("auction" => $auctionID, "status" => "RESTRICTED", "when" => date("Y-m-d H:i:s"));
header("Location: auction.php?a_iduction-id=$auctionID");
}
*/

// Set, prepare bind and execute SQL to find current high bid
// (Assuming mysqli)
$stmt = mysqli_stmt_init($connection);
$stmt = mysqli_prepare($connection, "SELECT MAX(`price`)
                                FROM `bid`
                                WHERE `auction_id` = ?
                               ");
mysqli_stmt_bind_param($stmt, "i", $auctionID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $maxBid);
mysqli_stmt_fetch($stmt);
$minNewBid = max($maxBid+0.01,$auctionData["start_price"]);


// Enter bid or fail according to bid value (and date)
if(date("Y-m-d H:i:s") < $auctionData["end_date"]){
    if ($userBid >= $minNewBid){
        $stmt = mysqli_stmt_init($connection);
        $stmt = mysqli_prepare($connection, "INSERT INTO `bid`
                                             VALUES (?, ?, ?, NOW())
                                            ");//   user, auction, amount 
        mysqli_stmt_bind_param($stmt, "iid", $userID, $auctionID, $userBid);
        mysqli_stmt_execute($stmt);
        $stmt = mysqli_stmt_init($connection);
        $stmt = mysqli_prepare($connection, "UPDATE `auction` SET `buyer_id` = ?
	    	                             WHERE auction_id = ?");
        mysqli_stmt_bind_param($stmt,"ii",$userID,$auctionID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $status = "success";
        $message = "You have successfully placed a bid of &pound;$userBid for $itemName.";
    } else {
        $status = "warning";
	$message = "Your bid was too low. The minimum acceptable bid is &pound;$minNewBid";
    }
} else {
    $status = "danger";
    $message = "You have run out of time to bid for $itemName.";
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>DBay - Bid</title>

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href="css/style.css" rel="stylesheet">
    </head>


    <body>
        <div class="container-fluid">
            <?php include "include/header.php"; ?>
            <section class="row">
                <div class="col-md-12">
                    <?php echo "<div class=\"alert alert-$status\"><p>$message</p></div>
                    <p><a href=\"auction.php?a_id=$auctionID\">Return to auction page</a></p>"; ?>
                </div>
            </section>
            <?php include "include/footer.php"; ?>
        </div>
    
        <script src=\"js/jquery.min.js\"></script>
        <script src=\"js/bootstrap.min.js\"></script>
        <script src=\"js/scripts.js\"></script>
    </body>
</html>
