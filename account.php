<?php include 'include/sessions.php' ?>
<?php include 'include/connections.php' ?>
<?php require_once'include/functions.php' ?>
<?php 
    if(isset($_POST['submit'])):
        //Validation functions
        validate_repeat();
        if(!logged_in()){
            $fields_with_max_length = array("Email" => 20);
            validate_max_length($fields_with_max_length);
            $fields_with_min_length = array("Password"=> 8);
            validate_min_length($fields_with_min_length);
        }
    endif;
?>
<?php global $errors;
    //If after validation there are no errors then enter in to database
   if(!empty($errors)):
    $_SESSION['errors'] = $errors;
    redirect_to('account.php');
    endif;
    if(isset($_POST['submit']) && empty($errors)):
        $result = user_reg();
        confirm_query($result);
        if(!logged_in())
            $message = "You have registered! ";
        else
            $message = "You have updated your profile ";
        $_SESSION['message'] = $message;
    endif;

    if(isset($_SESSION['user_id'])){
        // Fetch auction details
        $stmt = mysqli_stmt_init($connection);
        $stmt = mysqli_prepare($connection, "SELECT `users`.`user_email`, `users`.`is_seller`, `users`.`addressLine1`, `users`.`postcode`, `users`.`tel`, `users`.`first_name`, `users`.`last_name`, `address`.`addressLine2`, `address`.`city` FROM `users` JOIN `address` ON `users`.`postcode` = `address`.`postcode`  WHERE `user_id` = ?");

        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $email, $is_seller, $addressLine1,
        $postcode, $tel, $first_name, $last_name, $addressLine2, $city);
        if (!(mysqli_stmt_fetch($stmt))){
        echo "failed to fetch user details";
        //header("Location:noconnect.php");
        }
        mysqli_stmt_close($stmt);
    }
    else{
        $email = '';
        $is_seller = '';
        $addressLine1 = '';
        $postcode = '';
        $tel = '';
        $first_name = '';
        $last_name = '';
        $addressLine2 = '';
        $city = '';
    }
?>
<?php
if(isset($_SESSION['message']) && !logged_in()):
    redirect_to("success.php");
endif;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>DBay Account sss</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="css/bootstrap/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/account.css"/>
        <link rel="stylesheet" type="text/css" href="css/header.css"/>
        <script type="text/javascript" src="js/jquery-1.12.0.js"></script>
         <script src="js/bootstrap/bootstrap.js"></script>
        <script type="text/javascript" src="js/crafty_clicks_js_class_v4_9_2/crafty_postcode.class.js"></script>
        <script type="text/javascript" src="js/main.js"></script> 
    </head>
    <body>
        <?php 
        $form_error = errors();
        if(!empty($form_error))
        {
            echo "<div class=\"errors\">" ;   
            echo form_errors($form_error);
            echo "</div>";
        }

        include 'include/header.php';
        ?>
        <div class="container">
            <form method = "post" action= "account.php" class="register">
                <?php
                if(isset($_SESSION['message']) && logged_in()):
                    echo '<div style="font-size: 15px;" class="alert alert-info">' . $_SESSION['message'].'</div>';
                    $_SESSION['message'] = null;
                endif;
                ?>
                <h1><?php echo (!isset($_SESSION['user_id']))? 'Registration' : 'Update Profile';?></h1>
                
                <fieldset class="row1">
                    <legend>Account Details</legend>
                    <?php if(!isset($_SESSION['user_id'])):?>
                    <p>
                        <label>Email *</label>
                        <input type="text" required = "" name="Email" value = "<?php echo $email;?>"/>
                        <label>Repeat email *</label>
                        <input type="text" required = "" name="REmail" value="<?php echo $email;?>"/>
                    </p>
                    <p>
                        <label>Password*</label>
                        <input type="password" required = "" name ="Password" value = ""/>
                        <label>Repeat Password*</label>
                        <input type="password" required = "" name="RPassword" value=""/>
                        <label class="obinfo">* obligatory fields</label>
                    </p>
                    <?php else:?>
                    <p>
                        <label>Email </label>
                        <?php echo $email;?>
                    </p>
                    <?php endif;?>
                </fieldset>
                <fieldset class="row2">
                    <legend>Personal Details</legend>
                    <p>
                        <label>First Name *</label>
                        <input type="text" class="long" required = "" name="FName" value="<?php echo $first_name;?>"/>
                    </p>
                    <p>
                        <label>Last Name *</label>
                        <input type="text" class="long" required = "" name="LName" value="<?php echo $last_name;?>"/>
                    </p>
                    <p>
                        <label>Phone *</label>
                        <input type="text" maxlength="10" required = "" name="Phone" value="<?php echo $tel;?>"/>
                    </p>
                    <!-- Postcode field -->
                    <div id="postcode_lookup"></div> 
                    <p>
                     <label>Postcode *</label>
                    <input type="text" name="postcode" id="postcode" value="<?php echo $postcode;?>" />
                    <button type="button" id="btnFindPostCode">Find Address</button>
                    <span id="spnPostcode_result_display" style="display:none;">
                        <span id="spnAJAXWait">Please Wait...</span>
                       <select id="cmbPostCode" style="display:none;">
                       </select>
                    </span><br/>
                    </p>
                    <p>
                     <label>Address Line 1 *</label>
                    <input id="line1" type="text" required = "" name="FAdd" value="<?php echo $addressLine1;?>" />
                    </p>
                    <p>
                        <label>Address Line 2</label>
                        <input id="line2" type="text" name="addressLine2" value="<?php echo $addressLine2;?>">   
                    </p>                                    
                    <p>
                        <label>City *</label>
                        <input id="town" type="text" name="city" value="<?php echo $city;?>">
                    </p>
                </fieldset>
               <!--  <fieldset class="row3">
                    
                    <div class="infobox"><h4>Helpful Information</h4>
                        <p>Here comes some explaining text, sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                    </div>
                 
                </fieldset> -->
                <fieldset class="row4">
                    <?php if(!isset($_SESSION['user_id'])):?>
                    <legend>Terms and Mailing
                    </legend>
                     <?php endif;?>
                    <p class="agreement">
                         <?php 
                        
                       
                        if (!$is_seller) {
                            echo '<input type="checkbox" name = "Seller" value=""  />
                            <label> Do you also want to be a seller? <a href="#"></a></label>';
                        }
                        ?>
                    </p>
                    <?php if(!isset($_SESSION['user_id'])):?>
                    <p class="agreement">
                        <input type="checkbox" value="" required=""/>
                        <label>*  I accept the <a href="#">Terms and Conditions</a></label>
                    </p>
                    <?php endif;?>
                </fieldset>
                <input type="submit" name="submit" value="submit" />
            </form>
        </div>
    </body>
</html>





