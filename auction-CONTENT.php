<?php

//include "connect.php";
$auctionID = $_GET["a"];
$_SESSION["user_id"] = 3; // Delete this line from final app
$userID = $_SESSION["user_id"];
if (!isset($auctionID)){
    echo "no such auction";
    //header("Location:noauction.php");
}
$_SESSION["current_auction"] = $auctionID;

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
mysqli_stmt_bind_result($stmt, $itemID, $sellerID, $itemName, $itemDescription, $itemBrand, $itemCondition);
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
    $watching = ($userHiBid == -0.01);
    $hasBid = isset($userHiBid) ? ($userHiBid != -0.01) : false;
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
foreach ($categories as $c){
    echo "<li>$c</li>";
}
//mysqli_stmt_close($catStmt);
echo "</ul></div>";

// Site's stats about auction
// TODO: Calculate time remaining
echo "<div class=\"col-sm-4\"><h2>Auction details</h2><dl>
          <dt>Highest bid</dt><dd>" . ($anyBids ? "&pound;" . $hiBid :
              "<small>No bids for this item</small>") . "</dd>"
          . ($hasBid ? ($watching ? "" : "<dt>Your highest bid</dt><dd>&pound;$userHiBid</dd>") : "") .
         "<dt>End date</dt><dd>$endDate</dd>
          <dt>View count</dt><dd>$viewCount</dd>
          <dt>Seller</dt><dd><a href=\"user.php?u=$sellerID\">$sellerName</a></dd>
      </dl></div>";

echo "</div>";

// Bid form
if ($loggedIn ? !($auctionOver or $selling) : false)
echo "<div id=\"bid-form\" >
        <form action=\"place-bid.php\" method=\"post\" class=\"form-inline\">
            <label>&pound;</label>
            <input type=\"number\" name=\"amount\" step=\"0.01\"
                min=\"" . (max($hiBid + 0.01,$startPrice)) ."\" value=\"" . (max($hiBid + 0.01,$startPrice)) ."\">
            <button type=\"submit\" class=\"btn btn-primary btn-sm\">Place bid</button>
        </form>
    </div>";

// Bid history
echo "<h2>Bid history</h2>";
if ($anyBids){
    echo "<table class=\"table\">
              <thead><tr><th>Date</th><th>Amount</th></tr></thead>";
    $firstRow = true;
    foreach ($bids as $bid){
        $buyerID = $bid[0];
        $bidDate = $bid[3];
        $bidAmount = $bid[2];
        if ($firstRow){ // special formatting for top row
            $rowType = $winning ? "success" :
                       ($selling ? ($gotReserve ? "success" : ($auctionOver ? "danger" : "warning")) :
                       ($hasBid ? ($auctionOver ? "danger" : "warning") : "info"));
            $lastBidDate = $bids[0][3];
            $lastBidAmount = $bids[0][2];
            echo "<tr class=\"$alertType\"><td>$lastBidDate</td><td>&pound;$lastBidAmount</td></tr>";
            $firstRow = false;
        } else {
            $rowType = ($buyerID == $userID) ? "info" : "default";
            echo "<tr class=\"$rowType\"><td>$bidDate</td><td>&pound;$bidAmount</td></tr>";
        }
    }
    echo "</table>";
} else {
    echo "<p><small>No bids for this item</small></p>";
}
//mysqli_stmt_close($bidStmt);
?>
