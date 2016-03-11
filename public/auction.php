<?php include 'C:/wamp/www/Db_project/includes/sessions.php' ?>
<?php
include "templateClass.php";
$auctionID = $_GET["a_id"];
// TODO: include item name in title
$page = new DBayPage( "Auction", "auction-CONTENT.php" );
$page -> show();
?>