<?php include 'C:/wamp/www/Db_project/includes/sessions.php' ?>
<?php include 'C:/wamp/www/Db_project/includes/connections.php' ?>
<?php require_once 'C:/wamp/www/Db_project/includes/functions.php' ?>
<?php confirm_login() ?>
<?php
include "templateClass.php";
$page = new DBayPage( "Notifications", "not-content.php" );
$page -> show();
?>