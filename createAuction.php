<?php 
include 'include/sessions.php';
include 'include/connections.php';
require_once'include/functions.php';
confirm_login();
confirm_isseller();

//$_SESSION['userid'] = 1;

    //shows all the variable 
    extract($_POST);

    //if the variable is not defined it will error without the isset, 
    //with iseet it just doesn't go into the if statement
    if (isset($btnSubmit)) {
        $error="";

        
        if(empty($itemname)) {
            $error.="<br /> Item title";
        }
        if($condition==='0'){
            $error.="<br /> Item condition";
        }
        if(empty($categories)){
            $error.="<br /> Item category";
        }
        // if(empty($itemBrand)) {
        //     $error.="<br /> Item brand";
        // }
        if(empty($itemdescription)) {
            $error.="<br />description";
        }
        //filter var is for validation
        if (!filter_var($start_price, FILTER_VALIDATE_FLOAT)) { 
        $error.="<br /> Please enter a valid start price"; 
        }

        if (!filter_var($reservePrice, FILTER_VALIDATE_FLOAT)) { 
        $error.="<br /> Please enter a valid reserved price"; 
        }
        
        if (!empty($error)) {
            $result='<div class="alert-danger"><strong>You did not entered the following data: </strong>'.$error.'</div>';
        } else { 
           
            // $query = "INSERT INTO `item` ("
            
            $target_dir = "uploads/";
            //if a file has been submitted among other data, it will be stored in the $_FILES
            //pathinfo returns 
            if(isset($_FILES["flImage1"])):
            $path_parts = pathinfo($_FILES["flImage1"]["name"]);
            $extension = $path_parts['extension'];
            //uniqid() produces a random number as an id
            $filename = 'image_' . date('Y-m-d-H-i-s') . '_' . uniqid() . '.'.$extension;
            $target_file = $target_dir.$filename;

            //
            move_uploaded_file($_FILES["flImage1"]["tmp_name"], $target_file);
            endif;
            $query = "INSERT INTO `item` (`seller_id`, `item_name`, `item_description`, `item_brand`, `item_condition`) VALUES(?, ?, ?, ?, ?)";
           
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt,'isssi' , $_SESSION['user_id'], $itemname, $itemdescription, $itemBrand, $condition);
            //$resultset could get only true or false
            $result_set = mysqli_stmt_execute($stmt);

            if($result_set) {
                //it gets the id of the last inserted record in the database
                //id means a field that is auto incremented
                $item_id = mysqli_insert_id($connection);

                $query = "INSERT INTO `image` (`item_id`, `file_name`, `is_cover_image`) VALUES(?, ?, 1)";
                $stmt = mysqli_prepare($connection, $query);
                //mysqli_insert_id outputs the last id stored in this set of code
                mysqli_stmt_bind_param($stmt,'is' , $item_id, $filename);
                $result_set = mysqli_stmt_execute($stmt);

                $query = "INSERT INTO `item_category` (`item_id`, `category_id`) VALUES(?, ?)";
                $stmt = mysqli_prepare($connection, $query);
                foreach($categories as $category){
                    mysqli_stmt_bind_param($stmt,'ii' , $item_id, $category);
                    $result_set = mysqli_stmt_execute($stmt);
                }


                //now() + interval ? day will calculate the exact time and date
                $query = "INSERT INTO `auction` (`item_id`, `start_price`, `reserve_price`, `end_date`, `start_date`) VALUES(?, ?, ?, now() + interval ? day, now())";
                $stmt = mysqli_prepare($connection, $query);
                //mysqli_insert_id outputs the last id stored in this set of code
                mysqli_stmt_bind_param($stmt,'idds' , $item_id, $start_price, $reservePrice, $duration);
                $result_set = mysqli_stmt_execute($stmt);

                $result='<div class="alert-success">Form submitted</div>';
            } 
            //mysqli_free_result($result_set);

        }

    }
    else{
        $itemBrand = '';
        $itemname = '';
        $itemdescription = '';
        $reservePrice = '';
        $start_price = '';
        $result = '';
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
            <form method="post" enctype="multipart/form-data">
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
                                <input id="title" type="text" maxlength="80" size="70" name="itemname" value="<?php echo $itemname; ?>">
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
                                        <input type="file" name="flImage1" />
                                        <!--input id="btnAdd1" type="button" value="Add a photo" name="btnAdd1"></input-->
                                    </div>
                                </div>
                                <!--div id="picFrame2" class="picFrame">
                                    <div class="picwell" style="background-color:#fecb00">
                                        <img class="camIcon" src="http://pics.ebaystatic.com/aw/pics/easylister/camera.gif">
                                       
                                    </div>
                                     <div class="btnPicture">
                                        <input id="btnAdd1" type="button" value="Add a photo" name="btnAdd1"></input>
                                    </div>
                                </div-->
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
                                    <option value="0">Please select one</option>
                                    <option value="10">New</option>
                                    <option value="20">Refurbished</option>
                                    <option value="30">Used</option>
                                </select>
                            </div>

                             <div>
                                <label style="font-weight:bold" for="cmbCategory">Item categories *</label>
                                <div>
                                    <br>
                                </div>
                                <?php
                                $query = "select * from category";
                                $result_set = mysqli_query($connection, $query);
                                while($row=mysqli_fetch_assoc($result_set)){
                                    echo '<label><input type="checkbox"
                                                        name="categories[]"
                                                        value="'
                                                         . $row['category_id']
                                                         . '">'
                                                         . $row['category_name']
                                            . '</label><br>';
                                }
                                      mysqli_free_result($result_set);
                                ?>
                            </div>
                            <div id="mainDesSection" class="fullWidth" tabindex="-1">
                                 <div id="desType">
                                    <label style="font-weight:bold" for="itemBrand">Brand</label>
                                </div>
                                <div class="dropDown" role="dropdown">
                                    <input id="type-0" type="text" size="49" maxlength="50" autocomplete="off" name="itemBrand" value="<?php echo $itemBrand; ?>"></input>
                                    <a id="downArrow" href=""></a>
                                </div>
                                 <div id="desType">
                                    <label style="font-weight:bold" for="itemdescription">Item description *</label>
                                </div>
                                <div class="dropDown" role="dropdown">
                                    <textarea rows="4" cols="50" name="itemdescription"><?php echo $itemdescription; ?></textarea>
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
                                <input id="start_price" type="text" maxlength="6" value="0.99" name="start_price" value="<?php echo $start_price; ?>"> 
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
                                <input id="reservePrice" type="text" maxlength="6" value="0.99" name="reservePrice" value="<?php echo $reservePrice; ?>"> 
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





