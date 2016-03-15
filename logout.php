<?php include 'include/sessions.php' ?>
<?php require_once'include/functions.php' ?>

<?php

		session_unset();
		session_destroy();
		session_write_close();
		echo "You have logged out"."<br/>";
		echo "<a href='index.php'>click here</a>"."<br/>";
		echo "To go back to home page";
		//redirect_to("C:/wamp/www/Db_project/public/index.php");
?>
