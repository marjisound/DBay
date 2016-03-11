<?php
//start the session, should be included in everypage that makes use of Sessions.
	session_start();
?>
<?php

	function message()
	{
		if(isset($_SESSION['message'])):
		$output = "<div class = \" message \" > ";
		$output .= htmlentities($_SESSION['message']);
		$output .= "<a href = 'index.php'>click here</a> "."to go back to home page";
		$output .= "</div>";
		// clear message
		$_SESSION['message'] = null;
		endif;
		//$output = "";
		return $output;
	}
	function errors()
	{
		if(isset($_SESSION['errors'])):
			$errors = $_SESSION['errors'];
		//Clear once used.
			$_SESSION['errors'] = null;
		else:
			$errors = "";
		endif;
		return $errors;
	}
?>