<<<<<<< HEAD
<?php include 'C:/wamp/www/Db_project/includes/sessions.php' ?>
<?php include 'C:/wamp/www/Db_project/includes/connections.php' ?>
<?php require_once 'C:/wamp/www/Db_project/includes/functions.php' ?>
=======
<?php include 'include/sessions.php' ?>
<?php include 'include/connections.php' ?>
<?php require_once'include/functions.php' ?>
>>>>>>> origin/master
<?php
//Login.php 
//Processing user login

	if(isset($_POST['Login'])):
		$user_email = trim($_POST['user_email']);
<<<<<<< HEAD
		$password 	= trim($_POST['password']);
		//escape sql
		$user_email = mysqli_prep($user_email);
		$password = mysqli_prep($password);
=======
		//$password 	= mysqli_prep(trim($_POST['password']));
		echo $password."<br/>";
		//escape sql
		$user_email = mysqli_prep($user_email);
>>>>>>> origin/master
		// Now check database to see if user exists
		$result = find_user($user_email);
		if($result->num_rows === 0):
			global $errors;
			$errors['username'] = "user not found or incorrect user email";
			$_SESSION['login_errors'] = $errors;
		    redirect_to('index.php');
		else:
			$row = $result->fetch_assoc();
			$prev_password = $row['user_password'];
<<<<<<< HEAD
			if(password_verify($password,$prev_password)):
=======
			echo $prev_password."<br/>";
			echo $password."<br/>";
			$curr_password = hash("sha256",$_POST['password']);
			echo $curr_password;
			if($prev_password === $curr_password):
>>>>>>> origin/master
			//Once authenticated, get their user_id and store this in a session,redirect to 
			//notifications page.
				$user_id = get_id($user_email);
				$id_row = $user_id->fetch_assoc();
				$_SESSION['user_id'] = $id_row['user_id'];
<<<<<<< HEAD
				redirect_to('notifications.php');
			else:
				$errors['password'] = "Password does not match";
				$_SESSION['login_errors'] = $errors;
				redirect_to('index.php');
=======
				redirect_to('notificationss.php');
			else:
				$errors['password'] = "Password does not match";
				$_SESSION['login_errors'] = $errors;
				//redirect_to('index.php');
>>>>>>> origin/master
			endif;
		endif;
	endif;
	// We don't need to explicitly check for values in fields, because of the way it's set up, user is forced to enter a value before submitting.
?>