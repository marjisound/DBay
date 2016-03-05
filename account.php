<?php include 'C:/wamp/www/Db_project/includes/sessions.php' ?>
<?php include 'C:/wamp/www/Db_project/includes/connections.php' ?>
<?php require_once'C:/wamp/www/Db_project/includes/functions.php' ?>
<?php 
    if(isset($_POST['submit'])):
        //Validation functions
        validate_repeat();
        $fields_with_max_length = array("Email" => 20);
        validate_max_length($fields_with_max_length);
        $fields_with_min_length = array("Password"=> 8);
        validate_min_length($fields_with_min_length);
    endif;
?>

<?php
    global $errors;
    //If after validation there are no errors then enter in to database
   if(!empty($errors)):
    $_SESSION['errors'] = $errors;
    redirect_to('account.php');
    endif;
    if(isset($_POST['submit']) && empty($errors)):
        $result = user_reg();
        echo isset($result);
        confirm_query($result);
        $message = "You have registered! ";
        $_SESSION['message'] = $message;
    endif;
?>
<?php
if(isset($_SESSION['message'])):
    redirect_to("success.php");
endif;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>DBay Account sss</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="css/bootstrap/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../public/css/account.css"/>
         <script type="text/javascript" src="js/jquery-1.12.0.js"></script>
         <script src="https://getaddress.io/js/jquery.getAddress-2.0.1.min.js"></script>
        <script src="js/bootstrap/bootstrap.js"></script>
        
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
        ?>
        <div class="container">
            <form method = "post" action= "account.php" class="register">
                <h1>Registration</h1>
                <fieldset class="row1">
                    <legend>Account Details</legend>
                    <p>
                        <label>Email *</label>
                        <input type="text" required = "" name="Email" value = ""/>
                        <label>Repeat email *</label>
                        <input type="text" required = "" name="REmail" value=""/>
                    </p>
                    <p>
                        <label>Password*</label>
                        <input type="password" required = "" name ="Password" value = ""/>
                        <label>Repeat Password*</label>
                        <input type="password" required = "" name="RPassword" value=""/>
                        <label class="obinfo">* obligatory fields</label>
                    </p>
                </fieldset>
                <fieldset class="row2">
                    <legend>Personal Details</legend>
                    <p>
                        <label>First Name *</label>
                        <input type="text" class="long" required = "" name="FName" value=""/>
                    </p>
                    <p>
                        <label>Last Name *</label>
                        <input type="text" class="long" required = "" name="LName" value=""/>
                    </p>
                    <p>
                        <label>Phone *</label>
                        <input type="text" maxlength="10" required = "" name="Phone" value=""/>
                    </p>
                    <!-- Postcode field -->
                    <div id="postcode_lookup"></div>  
                    <p>
                     <label>First Address *</label>
                    <input id="line1" type="text" required = "" name="FAdd" value="" />
                    </p>
                    <p>
                        <label>Second Address Line</label>
                        <input id="line2" type="text">   
                    </p>                                    
                    <p>
                        <label>Town</label>
                        <input id="town" type="text">
                    </p>
                    <p>
                        <label>County</label>
                        <input id="county" type="text">
                    </p>
                    <p>
                        <label>Postcode</label>
                        <input id="postcode" type="text" required = "" name="Postcode" value="">
                    </p>
                    
                </fieldset>
                <fieldset class="row3">
                    
                    <div class="infobox"><h4>Helpful Information</h4>
                        <p>Here comes some explaining text, sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                    </div>
                 
                </fieldset>
                <fieldset class="row4">
                    <legend>Terms and Mailing
                    </legend>
                    <p class="agreement">
                        <input type="checkbox" name = "Seller" value=""  />
                        <label> Do you also want to be a seller? <a href="#"></a></label>
                    </p>
                    <p class="agreement">
                        <input type="checkbox" value="" required=""/>
                        <label>*  I accept the <a href="#">Terms and Conditions</a></label>
                    </p>
                </fieldset>
                <input type="submit" name="submit" value="submit" />
            </form>
        </div>
    </body>
</html>





