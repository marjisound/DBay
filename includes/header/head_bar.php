<?php
require_once('C:/wamp/www/Db_project/includes/functions.php');
?>

<!-- This is the header file, this could just be included in different pages usring '<?php //include() ?>' -->

<div class="container" style="height:200px;padding:6px" >
    <div class = "row" style="background-color:#00FF00">
        <div class ="col-md-4">
            <h1>Welcome to DBay </h1> <br />
            <p style="font-style:italic">You can search for products using the seach field <br/>
             alternatively you may browse by category !</p>
        </div>
 <!-- login_page.php deals with the action with validating user login -->
        <div class="col-md-4">
            <form style="text-align:left" action = "../public/search.php" method = "post">
                 <h4 class="form-signin-heading">Enter the name of an item</h4>
                <input type="text" class="form-control" name="user_query" placeholder="Search" required="" autofocus="" />
                
                 <button class="btn btn-lg btn-primary" name="search" value="search" type="Submit"><span class="glyphicon glyphicon-search"></span>Search
                </button>     
            </form>
        </div>
        <div class="col-md-4">                
            <div class="wrapper" style ="width:300px">
                <?php              
                // Store any log in errors in a sesssion and display these to the user, check out the file login.php and functions.php
                if(isset($_SESSION['login_errors'])):
                    login_errors();
                endif;
                ?>
                <form action="../includes/login.php" method="post" name="Login_Form" class="form-signin">       
                <h4 class="form-signin-heading">Please Sign In</h4>
              
                <input type="text" class="form-control" name="user-email" placeholder="user-email" required="" autofocus="" />
                <input type="password" class="form-control" name="password" placeholder="password" required=""/>            
             
                <button class="btn btn-lg btn-primary btn-block"  name="Login" value="Login" type="Submit">Login</button>            
               </form>        
               <a href=../public/account.php>sign up</a> <br/>
               <a href=#>forgot login details </a>
            </div>
            
        </div>
    </div>
</div>