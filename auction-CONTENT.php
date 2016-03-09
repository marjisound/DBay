<?php

include "connect.php";
// $auction ID set in auction.php with: $auctionID = $_GET["a"];
$userID = $_SESSION["userID"];
if (!isset($auction_id)){
    header("Location:noauction.php");
}

// Fetch auction details
$stmt = $connection -> prepare("SELECT * FROM `auction` WHERE `auction_id` == ?");
$stmt -> bind_param("i", $auctionID);
$stmt -> execute();
$stmt -> bind_result("iiddssiisissis", $auctionID, $itemID, $startPrice,
                     $reservePrice, $startDate, $endDate, $viewCount,
                     $winnerID, $buyerComment, $buyerRate, $buyerReviewDate,
                     $sellerComment, $sellerRate, $sellerReviewDate);
if (!($stmt -> fetch())){
    header("Location:noconnect.php");
}

// Fetch item details
$stmt = $connection -> prepare("SELECT * FROM `item` WHERE `item_id` == ?");
$stmt -> bind_param("i", $itemID);
$stmt -> execute();
$stmt -> bind_result("iiss", $itemID, $sellerID, $itemName, $itemDescription);
if (!($stmt -> fetch())){
    header("Location:noconnect.php");
}
// Fetch seller name
$stmt = $connection -> prepare("SELECT `user_name` FROM `user`
                                WHERE `user_id` == ?");
$stmt -> bind_param("i", $sellerID);
$stmt -> execute();
$stmt -> bind_result("s",$sellerName);
if (!($stmt -> fetch())){
    header("Location:noconnect.php");
}

// Update view count
$stmt = $connection -> prepare("UPDATE `auction` SET `view_count` = ?
                                WHERE `auction_id` == ?");
$stmt -> bind_param("ii", 1+$viewCount, $auctionID);
$stmt -> execute();

// Prepare to fetch item categories
$catStmt = $connection -> prepare("SELECT c.category_name
                                   FROM `item_category` ic JOIN `category` c
                                        ON ic.category_id == c.category_id
                                   WHERE ic.item_id == ?");
$catStmt -> bind_param("i", $itemID);
$catStmt -> execute();
$catStmt -> bind_result("s", $categoryName);

// Prepare to fetch bid details
$bidStmt = $connection -> prepare("SELECT * FROM `bid` WHERE `auction_id` == ?
                                   ORDER BY `price` LIMIT 20");
$bidStmt -> bind_param("i", $auctionID);
$bidStmt -> execute();
$bidStmt -> bind_result("iids", $buyerID, $auctionID, $bidAmount, $bidDate);

// Set flags to determine display
$loggedIn = isset($userID);
$auctionOver = $endDate < date("Y-m-d H:i:s");
$selling = $loggedIn and ($sellerID = $userID);
$winning = isset($winnerID) and ($loggedIn and ($winnerID == $userID));
$anyBids = ($bidStmt -> fetch());
$hiBid = $anyBids ? $bidAmount : 0.00;
$gotReserve = $anyBids and ($hiBid >= $reservePrice);

// Determine if user placed a bid in this auction or is watching
if ($loggedIn){
    $stmt = $connection -> prepare("SELECT MAX(`price`) FROM `bid`
                                    WHERE `buyer_id` == ?");
    $stmt -> bind_param("i", $userID);
    $stmt -> execute();
    $stmt -> bind_result("i" $userHiBid);
    $hasBid = $stmt -> fetch();
    $watching = isset($userHiBid) and ($userHiBid == 0.00)
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
              . strtoupper($userStatus) . "</span>" .
     "</h1>";

echo "<div class=\"row\">";
// Seller's info about item
// TODO: Picture, other info
echo "<div class=\"col-sm-8\"><p>$itemDescription</p><h4>Categories</h4><ul>";
while ($catStmt -> fetch()){
    echo "<li>$categoryName</li>";
}
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
              // special formatting for top bid
    echo "    <tr class=\"$alertType\"><td>$bidDate</td><td>&pound;$bidAmount</td></tr>";
    while ($bidStmt -> fetch()){
        $rowType = ($buyerID == $userID) ? "info" : "default";
        echo "<tr class=\"$rowType\"><td>$bidDate</td><td>&pound;$bidAmount</td></tr>";
        }
    echo "</table>";
} else {
    echo "<p><small>No bids for this item</small></p>";
}
?>
