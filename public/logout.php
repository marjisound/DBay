<?php include 'C:/wamp/www/Db_project/includes/sessions.php' ?>
<?php require_once'C:/wamp/www/Db_project/includes/functions.php' ?>

<?php
		$_SESSION['user_id'] = null;
		echo "You have logged out"."<br/>";
		echo "<a href='index.php'>click here</a>"."<br/>";
		echo "To go back to home page";
		//redirect_to("C:/wamp/www/Db_project/public/index.php");
?>
