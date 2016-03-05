<?php
 if (isset($_POST['btnSubmit'])) {
    if($_POST['condition']===''){
        $_POST['condition']=null;
    }
    if($_POST['category']===''){
        $_POST['category']=null;
    }

    
    if(!$_POST['itemname']) {
        $error="<br /> Item title";
    }
    if(!$_POST['condition']) {
        $error.="<br /> Item condition";
    }
    if(!$_POST['category']) {
        $error.="<br /> Item category";
    }
    if(!$_POST['itemBrand']) {
        $error.="<br /> Item brand";
    }
    if(!$_POST['itemdescription']) {
        $error.="<br />description";
    }
    if (!filter_var($_POST['start_price'], FILTER_VALIDATE_FLOAT)) { 
    $error.="<br /> Please enter a valid start price"; 
    }

    if (!filter_var($_POST['reservePrice'], FILTER_VALIDATE_FLOAT)) { 
    $error.="<br /> Please enter a valid reserved price"; 
    }
    
    if ($error) {
        $result='<div class="alert-danger"><strong>You did not entered the following data: </strong>'.$error.'</div>';
    } else { 
        if($_POST["btnSubmit"]) {
            $result='<div class="alert-success">Form submitted</div>';

            $link = mysqli_connect("localhost", "cl43-dbay", "s3bYge/Bq", "cl43-dbay");

            if(mysqli_connect_error()) {

                die("Could not connect to database");
            }

            $itemname = $_POST[itemname];
            $itemdescription = $_POST[itemdescription];
            $condition = $_POST[condition]; 
            $start_price = $_POST[start_price];
            $query = "INSERT INTO `item` ("
            $query = "INSERT INTO `auction` (`start_price`) VALUES('$start_price')";
            $query = "INSERT INTO `item` (`itemname`, `itemdescription`) VALUES('$itemname', '$itemdescription')";

            mysqli_query($link, $query);

            $query = "SELECT * FROM auction";

        }

    }

 }
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
        $pageTitle = 'Auction Creation';
        include('include/head.php');
        ?>
        <link rel="stylesheet" type="text/css" href="css/createAuction.css"/>
    </head>
    <body> 
        <?php 
            include ('include/header.php');
        ?>
        <div id="box">
            <form method="post">
                <div id="header">
                    <div id="headerTxt">
                        <h1>Create your listing</h1>
                    </div>
                    
                </div>
                <?php echo $result; ?>
                <div id="wrapper">
                    <section>
                        <div id="auctionTitle" class="sectionHead">
                            <div>
                                <h2>Create a descriptive title for your item</h2>
                            </div>
                        </div>
                        <div id="titleField">
                            <!-- <div id="desType">
                                <label style="font-weight:bold" for="itemname">Item title *</label>
                            </div> -->
                            <div>
                                <input id="title" type="text" maxlength="80" size="70" name="itemname" value="<?php echo $_POST['itemname']; ?>">
                                </input>
                            </div>
                        </div>
                    </section>
                    <section>
                        <div id="auctionPic" class="sectionHead">
                            <div>
                                <h2>Add pictures for your item</h2>
                            </div>
                        </div>
                        <div id="picBox" class="clearfix">
                            <div>
                                Click <b>Add a photo</b> to upload your images
                            </div>
                            <div id="pictures">
                                <div id="picFrame1" class="picFrame">
                                    <div class="picwell" style="background-color:#fecb00">
                                        <img class="camIcon" src="http://pics.ebaystatic.com/aw/pics/easylister/camera.gif">
                                       
                                    </div>
                                     <div class="btnPicture">
                                        <input id="btnAdd1" type="button" value="Add a photo" name="btnAdd1"></input>
                                    </div>
                                </div>
                                <div id="picFrame2" class="picFrame">
                                    <div class="picwell" style="background-color:#fecb00">
                                        <img class="camIcon" src="http://pics.ebaystatic.com/aw/pics/easylister/camera.gif">
                                       
                                    </div>
                                     <div class="btnPicture">
                                        <input id="btnAdd1" type="button" value="Add a photo" name="btnAdd1"></input>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </section>
                    <section>
                         <div id="auctionPic" class="sectionHead">
                            <div>
                                <h2>Describe your item</h2>
                            </div>
                        </div>
                        <fieldset id="desBox" class="boxTag" style="display: block;">
                            <div>
                                <label style="font-weight:bold" for="Condition">Item condition</label>
                                <div>
                                    <br>
                                </div>
                                <select name="condition">
                                    <option value="">-</option>
                                    <option value="100">New</option>
                                    <option value="200">Refurbished</option>
                                    <option value="400">Used</option>
                                </select>
                            </div>

                             <div>
                                <label style="font-weight:bold" for="itemCategory">Item category *</label>
                                <div>
                                    <br>
                                </div>
                                <select name="category">
                                    <option value="">-</option>
                                    <option value="100">Collectables &amp; antiques</option>
                                    <option value="200">Home &amp; garden </option>
                                    <option value="300">Sporting goods</option>
                                    <option value="400">Electronics</option>
                                    <option value="500">Jewellery &amp; watches</option>
                                    <option value="600">Toys &amp; games</option>
                                    <option value="700">Fashion</option>
                                    <option value="800">Motors</option>
                                    <option value="900">Other</option>
                                </select>
                            </div>
                            <div id="mainDesSection" class="fullWidth" tabindex="-1">
                                 <div id="desType">
                                    <label style="font-weight:bold" for="itemBrand">Brand</label>
                                </div>
                                <div class="dropDown" role="dropdown">
                                    <input id="type-0" type="text" size="49" maxlength="50" autocomplete="off" name="itemBrand" value="<?php echo $_POST['itemBrand']; ?>"></input>
                                    <a id="downArrow" href=""></a>
                                </div>
                                 <div id="desType">
                                    <label style="font-weight:bold" for="itemdescription">Item description *</label>
                                </div>
                                <div class="dropDown" role="dropdown">
                                    <textarea rows="4" cols="50" name="itemdescription"><?php echo $_POST['itemdescription']; ?></textarea>
                                </div>

                            </div>
                        </fieldset>
                        <fieldset id="typeBox" class="inputField" style="display: block;">
                            <div></div>
                        </fieldset> 
                    </section>
                    <section>
                        <div id="auctionPic" class="sectionHead">
                            <div>
                                <h2>Set your price</h2>
                            </div>
                        </div>
                        <fieldset id="priceSec">
                            <div>
                                <label for="start_price">Start auction bid at: </label>
                                £
                                <input id="start_price" type="text" maxlength="6" value="0.99" name="start_price" value="<?php echo $_POST['start_price']; ?>"> 
                                <label for="duration">lasting for</label>
                                <select id="duration" name="duration">
                                    <option value="1">1 day</option>
                                    <option value="2">2 days</option>
                                    <option value="3">3 days</option>
                                    <option value="4">4 days</option>
                                    <option value="5">5 days</option>
                                    <option value="6">6 days</option>
                                    <option value="7">7 days</option>
                                    <option value="8">8 days</option>
                                    <option value="9">9 days</option>
                                    <option value="10">10 days</option>
                                </select>
                            </div>
                            <div>
                                 <label for="reservePrice">Set your reserved price at: </label>
                                £
                                <input id="reservePrice" type="text" maxlength="6" value="0.99" name="reservePrice" value="<?php echo $_POST['reservePrice']; ?>"> 
                            </div>
                        </fieldset>
                    </section>
                    <span class="btnAuction">
                        <input id="btnSubmit" type="submit" value="Submit" name="btnSubmit">
                    </span>
                </div>
            </form>
        </div>
    </body>
</html>





