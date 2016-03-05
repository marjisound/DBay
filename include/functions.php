<?php
// This page contains most of the functions relavent to validating inputs, constructing sql queries and 
// inserting these in to the database.
$errors = array();
// Validation functions
function str_to_replace($namestring)
{
	//Just replace _ with space and capitalize the first word.
	// Just for user friendly purpose
	$namestring = str_replace("_"," ", $namestring);
	$namestring = ucfirst($namestring);
	return $namestring;
}
function has_presence($value)
{
  return isset($value) || $value === "";
}

// string length
function has_max_lenth($value,$max)
{
	return strlen($value) <= $max;
} 
function validate_max_length($fields_with_max_length)
{
	global $errrors;
	foreach($fields_with_max_length as $field => $max)
	{
		$value = trim($_POST[$field]);
		if(!has_max_lenth($value,$max))
		{
			$errors[$field] = str_to_replace($field)." is too long";
		}
	}
}
function has_min_length($value, $min)
{
	return strlen($value) >= $min;
}
function validate_min_length($fields_with_min_length)
{
	global $errors;
	foreach($fields_with_min_length as $field=> $min)
	{
		$value = trim($_POST[$field]);
		if(!has_min_length($value,$min))
		{
			$errors[$field] = str_to_replace($field)." should have more than "."$min "."characters";
		}
	}
}
function validate_repeat()
{
	global $errors;
	$Email = trim($_POST['Email']);
	$REmail = trim($_POST['REmail']);
	$Password = trim($_POST['Password']);
	$RPassword = trim($_POST['RPassword']);
	if($Email != $REmail || $Password != $RPassword):
    	$errors['repeat'] = "either one of repeated email/password did not match";
	endif;
}

 //type
//inclusion in a set
function has_inclusion_in($value, $set)
// checks if $value in $set
{
return in_array($value,$set);
}

function form_errors($errors)
{
	// returns errors as a string 
	$output="";
	foreach ($errors as $error) {
		$output .= $error."<br/>";	
	}
	return $output;
}

function login_errors()
{
	$error = $_SESSION['login_errors'];
 	echo "<div style=\"color:red\">";
    echo form_errors($error);
    echo "</div>";
    $_SESSION['login_errors'] = null;                   
}
function redirect_to($new_location)
{
	// redirection
	header("Location: ". $new_location);
	exit;
}

//sql functions
function confirm_query($results)
{
	global $connection;
	if(!$results):
		die("Database query failed. ".mysqli_error($connection));
	endif;
}
function mysqli_prep($string)
{ //escapes the string
	global $connection;
	$escaped_string = mysqli_real_escape_string($connection,$string);
	return $escaped_string;
}
function user_reg()
{// forms a query for inserting user details and inserts in to database
// This function is called once $_POST['submit'] is true - check account.php
	global $connection;
	$Email = mysqli_prep(trim($_POST['Email']));    
	$Password = mysqli_prep(trim($_POST['Password']));
	$Password = hash("sha256",$Password);
    $REmail = mysqli_prep(trim($_POST['REmail']));
    $RPassword = mysqli_prep(trim($_POST['RPassword']));
    $RPassword = hash("sha256",$Password);
    $First_Name = mysqli_prep(trim($_POST['FName']));
    $Last_Name = mysqli_prep(trim($_POST['LName']));
    $Phone = mysqli_prep(trim($_POST['Phone']));
    $First_Address = mysqli_prep(trim($_POST['FAdd']));
    $Postcode = mysqli_prep(trim($_POST['Postcode']));
	$query ="INSERT INTO users (user_email,user_password,tel,Postcode,is_buyer) ";
    $query .="VALUES ('$Email','$Password','$Phone','$Postcode','1')";  
    $result = mysqli_query($connection,$query);
    return $result;
}
function find_user($user_email)
{
	// Takes user_email as a paremater, constructs a query to search
	// database 
	global $connection;
	$query = " SELECT * ";
	$query .= "FROM users ";
	$query .= "WHERE user_email = '$user_email'";
	$result = mysqli_query($connection,$query);
	return $result;
}
function get_id($user_email)
{
	global $connection;
	$query = " SELECT user_id ";
	$query .= "FROM users ";
	$query .= "WHERE user_email = '$user_email'";
	$result = mysqli_query($connection,$query);
	confirm_query($result);
	return $result;
}
?>