<?php

include "templateClass.php";
$auctionID = $_GET["a"];

// TODO: include item name in title
$page = new DBayPage( "Auction", "auction-CONTENT.php" );
$page -> show();

?>
