<!DOCTYPE html>
<html lang="en">
    <head>
    <?php 
        $pageTitle = 'Auction Creation';
        include('include/head.php');
    ?>
    <link rel="stylesheet" type="text/css" href="css/account.css"/>
    <link rel="stylesheet" type="text/css" href="css/header.css">
    </head>
    <body> 
        <?php
            include('include/header.php');
        ?>
        <div class="container">
            <form action="" class="register" name="address">
                <h1>Registration</h1>
                <fieldset class="row1">
                    <legend>Account Details</legend>
                    <p>
                        <label>Email *</label>
                        <input type="text"/>
                        <label>Repeat email *</label>
                        <input type="text"/>
                    </p>
                    <p>
                        <label>Password*</label>
                        <input type="text"/>
                        <label>Repeat Password *</label>
                        <input type="text"/>
                        <label class="obinfo">* obligatory fields</label>
                    </p>
                </fieldset>
                <fieldset class="row2">
                    <legend>Personal Details</legend>
                    <p>
                        <label>First Name *</label>
                        <input type="text" class="long"/>
                    </p>
                    <p>
                        <label>Last Name *</label>
                        <input type="text" class="long"/>
                    </p>
                    <p>
                        <label>Phone *</label>
                        <input type="text" maxlength="10"/>
                    </p>
                    <p>
                     <label>Postcode *</label>
                    <input type="text" name="postcode" id="postcode"/>
                    <button type="button" id="btnFindPostCode">Find Address</button>
                    <span id="spnPostcode_result_display" style="display:none;">
                        <span id="spnAJAXWait">Please Wait...</span>
                       <select id="cmbPostCode" style="display:none;">
                       </select>
                    </span><br/>
                    </p>
                    <p>
                        <label>Address Line 1 *</label>
                        <input type="text" name="address1" id="address1" class="long"/>
                    </p>
                    <p>
                        <label class="optional">Address Line 2</label>
                        <input type="text" name="address2" id="address2" class="long"/>
                    </p>
                    <p>
                        <label>City *</label>
                        <input type="text" name="town" id="town" class="long"/>
                    </p>
                    <p>
                        <label>Country *</label>
                        <select>
                            <option>
                            </option>
                            <option value="1">United States
                            </option>
                        </select>
                    </p>
                </fieldset>
                <fieldset class="row4">
                    <p class="agreement">
                        <input type="checkbox" value=""/>
                        <label>*  I accept the <a href="#">Terms and Conditions</a></label>
                    </p>
                </fieldset>
                <div><button class="button">Register &raquo;</button></div>
            </form>
        </div>
    </body>
</html>





