<?php

// Display appropriate alert if auction is over

if ($auctionOver){
    echo "<div class=\"alert alert-$alertType\">
              <p>The auction for $itemName is over</p>" . $alertMessage .
         "</div>";
}


// Item details

// Heading (including user status)
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
if ($loggedIn ? !($auctionOver or $selling) : false){
echo "<form action=\"place-bid.php\" method=\"post\" class=\"form-inline\">
          <label>&pound;</label>
          <input type=\"number\" name=\"amount\" step=\"0.01\"
              min=\"" . (max($hiBid + 0.01,$startPrice)) ."\" value=\"" . (max($hiBid + 0.01,$startPrice)) ."\">
          <button type=\"submit\" class=\"btn btn-primary btn-sm\">Place bid</button>
      </form>";
}

// Watch button
if ($loggedIn ? !($auctionOver or $selling or $watching or $hasBid) : false){
    echo "<form action=\"watch.php?a=$auctionID\">
          <button class=\"btn btn-primary\">Watch auction</button></form>";
}


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
