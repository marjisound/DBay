<?php

//include "connect.php";
$auctionID = $_GET["a"];
$userID = $_SESSION["user_id"];
if (!isset($auctionID)){
    header("Location:noauction.php");
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
$stmt = mysqli_prepare($connection, "SELECT * FROM `item` WHERE `item_id` = ?");
mysqli_stmt_bind_param($stmt, "i", $itemID);
mysqli_stmt_execute($stmt );
mysqli_stmt_bind_result($stmt, $itemID, $sellerID, $itemName, $itemDescription, $itemBrand, $itemDescription);
if (!(mysqli_stmt_fetch($stmt))){
    echo "failed to fetch item details";
    //header("Location:noconnect.php");
}
mysqli_stmt_close($stmt);

// Fetch seller name
$stmt = mysqli_stmt_init($connection);
$query = "SELECT `user_email` FROM `user` WHERE `user_id` = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $sellerID);
mysqli_stmt_execute($stmt );
mysqli_stmt_bind_result($stmt, $sellerName);
if (!(mysqli_stmt_fetch($stmt))){
    echo "failed to fetch seller name";
    //header("Location:noconnect.php");
}
mysqli_stmt_close($stmt);

// Update view count
$stmt = mysqli_stmt_init($connection);
$stmt = mysqli_prepare($connection, "UPDATE `auction` SET `view_count` = ?
                                WHERE `auction_id` = ?");
$viewCount++;
mysqli_stmt_bind_param($stmt, "ii", $viewCount, $auctionID);
mysqli_stmt_execute($stmt );
mysqli_stmt_close($stmt);

// Prepare to fetch item categories
$catStmt = mysqli_stmt_init($connection);
$catStmt = mysqli_prepare($connection, "SELECT c.category_name
                                   FROM `item_category` ic JOIN `category` c
                                        ON ic.category_id = c.category_id
                                   WHERE ic.item_id = ?");
mysqli_stmt_bind_param($catStmt, "i", $itemID);
mysqli_stmt_execute($catStmt);
mysqli_stmt_bind_result($catStmt, $categoryName);

// Prepare to fetch bid details
$bidStmt = mysqli_stmt_init($connection);
$bidStmt = mysqli_prepare($connection, "SELECT * FROM `bid` WHERE `auction_id` = ?
                                   ORDER BY `price` LIMIT 20");
mysqli_stmt_bind_param($bidStmt, "i", $auctionID);
mysqli_stmt_execute($bidStmt);
mysqli_stmt_bind_result($bidStmt, $buyerID, $auctionID, $bidAmount, $bidDate);

// Set flags to determine display
$loggedIn = isset($userID);
$auctionOver = $endDate < date("Y-m-d H:i:s");
$selling = $loggedIn and ($sellerID = $userID);
$winning = isset($winnerID) and ($loggedIn and ($winnerID == $userID));
$anyBids = (mysqli_stmt_fetch($bidStmt));
$hiBid = $anyBids ? $bidAmount : 0.00;
$gotReserve = $anyBids and ($hiBid >= $reservePrice);

// Determine if user placed a bid in this auction or is watching
if ($loggedIn){
    $stmt = mysqli_prepare($connection, "SELECT MAX(`price`) FROM `bid`
                                    WHERE `buyer_id` = ?");
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt );
    mysqli_stmt_bind_result($stmt, $userHiBid);
    $hasBid = mysqli_stmt_fetch($stmt);
    $watching = isset($userHiBid) and ($userHiBid == 0.00);
} else {
    $hasBid = false;
    $watching = false;
}

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
                             it was below the reserve price. Consequently,
                             this auction ends in no sale.</p>";
            $userStatus = "too low";
        }
    } else if ($hasBid) { // user has bid which is not highest
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
    } else if ($hasBid) { // user has bid which is not highest
        $alertType = "warning";
        $userStatus = "losing";
    } else { // user has not bid
        $alertType = "info";
        $userStatus = $watching ? "watching" : "";
    }
}

// Display appropriate alert if auction is over
if ($auctionOver){
    echo "<div class=\"alert alert-$alertType\">
              <p>The auction for $itemName is over</p>" . $alertMessage .
         "</div>";
}


// Item details

// Header (including user status)
echo "<h1>$itemName" . (($userStatus == "") ? ""
          : "<span class=\"label label-$alertType\">"
              . strtoupper($userStatus) . "</span>") .
     "</h1>";

echo "<div class=\"row\">";
// Seller's info about item
// TODO: Picture, other info
echo "<div class=\"col-sm-8\"><p>$itemDescription</p><h4>Categories</h4><ul>";
while (mysqli_stmt_fetch($catStmt)){
    echo "<li>$categoryName</li>";
}
mysqli_stmt_close($catStmt);
echo "</ul></div>";

// Site's stats about auction
// TODO: Calculate time remaining
echo "<div class=\"col-sm-4\"><h2>Auction details</h2><dl>
          <dt>Highest bid</dt><dd>" . ($anyBids ? $hiBid :
              "<small>No bids for this item</small>") . "</dd>"
          . ($hasBid ? "<dt>Your highest bid</dt><dd>&pound;$userHiBid</dd>" : "") .
         "<dt>End date</dt><dd>$endDate</dd>
          <dt>View count</dt><dd>$viewCount</dd>
          <dt>Seller</dt><dd><a href=\"user.php?u=$sellerID\">$sellerName</a></dd>
      </dl></div>";

echo "</div>";

// Bid form
echo "<button type=\"button\"
           class=\"btn btn-primary btn-lg\"
           data-toggle=\"collapse\"
           data-target=\"#bid-form\">
           Place bid
       </button>
       <div id=\"bid-form\" class=\"collapse\">
           <form action=\"place-bid.php\" method=\"post\" class=\"form-inline\">
               <label>&pound;</label>
               <input type=\"number\" name=\"amount\" step=\"0.01\"
                   min=\"" . ($hiBid + 0.01) ."\" value=\"" . ($hiBid + 0.01) ."\">
               <button type=\"submit\" class=\"btn btn-primary btn-sm\">Submit</button>
           </form>
       </div>";

// Bid history
echo "<h2>Bid history</h2>";
if ($anyBids){
    echo "<table class=\"table\">
              <thead><tr><th>Date</th><th>Amount</th></tr></thead>";
              // special formatting for top row
              $rowType = $winning ? "success" : ($hasbid ? ($auctionOver ? "danger" : "warning") : "info");
    echo "    <tr class=\"$rowType\"><td>$bidDate</td><td>&pound;$bidAmount</td></tr>";
    while (mysqli_stmt_fetch($bidStmt)){
        $rowType = ($buyerID == $userID) ? "info" : "default";
        echo "<tr class=\"$rowType\"><td>$bidDate</td><td>&pound;$bidAmount</td></tr>";
        }
    echo "</table>";
} else {
    echo "<p><small>No bids for this item</small></p>";
}
mysqli_stmt_close($bidStmt);
?>
