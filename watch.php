<?php

include 'include/sessions.php';
include 'include/connections.php';
$auctionData = $_SESSION["auction_data"];
if (!isset($auctionData)){
    $msg = "Problem getting auction data.";
}
$userID = $_SESSION['user_id'];
$auctionID = $auctionData["auction_id"];
$itemName = $auctionData["item_name"];
if (!isset($userID)){
    $msg = isset($msg) ? $msg : "You are not logged in.";
    } else {
        
        // Check if user has already bid
        $stmt = mysqli_stmt_init($connection);
        $stmt = mysqli_prepare($connection, "SELECT MAX(`price`) FROM `bid`
                               WHERE `auction_id` = ?
                               AND `buyer_id` = ?");
        mysqli_stmt_bind_param($stmt, "ii", $auctionID, $userID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $userHiBid);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        $hasBid = isset($userHiBid);
        
        if ($hasBid){
            $msg = isset($msg) ? $msg : "You are already watching this auction.";
            } else {
                $stmt = mysqli_stmt_init($connection);
                $stmt = mysqli_prepare($connection, "INSERT INTO `bid`
                                       VALUES (?, ?, -0.01, NOW())");
                mysqli_stmt_bind_param($stmt, "ii", $userID, $auctionID);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $msg = isset($msg) ? $msg : "You are now watching the auction for $itemName.";
                }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>DBay - Watch <?php echo "$itemName"; ?></title>

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href="css/style.css" rel="stylesheet">
    </head>


    <body>
        <div class="container-fluid">
            <?php include "include/header.php"; ?>
            <section class="row">
                <div class="col-md-12">
                    <p><?php echo $msg; ?></p>
                </div>
            </section>
            <?php include "include/footer.php"; ?>
        </div>
    
        <script src=\"js/jquery.min.js\"></script>
        <script src=\"js/bootstrap.min.js\"></script>
        <script src=\"js/scripts.js\"></script>
    </body>
</html>
<?php } ?>
