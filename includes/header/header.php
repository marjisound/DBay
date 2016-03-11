<?php
require_once('C:/wamp/www/Db_project/includes/functions.php');
?>
<nav class="navbar navbar-default navbar-inverse" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
           <!-- <a class="navbar-brand" href="#">Welcome To Dbay</a> -->
           <h3 style="color:white"> Welcome To Dbay </h3>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php
                if(logged_in()):
                    echo
                        '<li class="active"><a href="../public/notifications.php">Notifications</a></li>';
                endif;
                ?>
                <li><a href="../public/index.php">Home Page</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="../public/notifications.php">Go to notifications</a></li>
                        <li><a href="#">Search By Category</a>
                            <ul>
                                <li><a href="../public/search.php">Cat 1 </a>
                                <li><a href="../public/search.php">Cat 1 </a>
                                <li><a href="../public/search.php">Cat 1 </a>
                                <li><a href="../public/search.php">Cat 1 </a>
                            </ul>
                        </li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
            <form class="navbar-form navbar-left" role="search" action="search.php" method="get">
                <div class="form-group">
                    <input type="text" class="form-control" name="user_query" placeholder="Search">
                </div>
                <button type="submit" name="search" class="btn btn-default">Search</button>
            </form>
            <ul class="nav navbar-nav navbar-right">

                <li class="dropdown">
                     
                    <?php
                        if(logged_in()):
                            echo 
                       '<a href="logout.php"><b>Logout</b></a>';
                        else:
                            echo 
                    '<li><p class="navbar-text">Already have an account?</p></li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
                    <ul id="login-dp" class="dropdown-menu">                            
                        <li>
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="form" role="form" method="post" action="../includes/login.php" accept-charset="UTF-8" id="login-nav">
                                        <div class="form-group">';
                                                           
                    // Store any log in errors in a sesssion and display these to the user, check out the file login.php and functions.php
                                                if(isset($_SESSION['login_errors'])):
                                                    login_errors();
                                                endif;
                                                echo
                                            '<label class="sr-only" for="exampleInputEmail2">Email address</label>
                                            <input type="email" class="form-control" name="user_email" placeholder="Email address" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="sr-only" for="exampleInputPassword2">Password</label>
                                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="Login" class="btn btn-primary btn-block">Sign in</button>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"> keep me logged-in
                                            </label>
                                        </div>
                                    </form>
                                </div>
                                <div class="bottom text-center">
                                    New here ? <a href="../public/account.php"><b>Join Us</b></a>
                                </div>
                            </div>
                        </li>';
                        endif;
                        ?>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
