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
// check that user is logged in
function logged_in(){
	return isset($_SESSION['user_id']);
}
function confirm_login()
{
	if(!isset($_SESSION['user_id'])):
		redirect_to('index.php');
	endif;
}
function logout(){
		$_SESSION['user_id'] = null;
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
	$HPassword = password_hash($Password,PASSWORD_DEFAULT);
    $REmail = mysqli_prep(trim($_POST['REmail']));
    $RPassword = mysqli_prep(trim($_POST['RPassword']));
    $First_Name = mysqli_prep(trim($_POST['FName']));
    $Last_Name = mysqli_prep(trim($_POST['LName']));
    $Phone = mysqli_prep(trim($_POST['Phone']));
    $First_Address = mysqli_prep(trim($_POST['FAdd']));
    $Postcode = mysqli_prep(trim($_POST['Postcode']));
    if(isset($_POST['Seller'])):
    	$is_seller = 1;
    else:
    	$is_seller = 0;
    endif;
	$query ="INSERT INTO users (user_email,user_password,tel,Postcode,is_seller,is_buyer) ";
    $query .="VALUES ('$Email','$HPassword','$Phone','$Postcode','$is_seller','1')";  
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
	$query .= "WHERE user_email = ?";
	$stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,'s' , $user_email);
    mysqli_stmt_execute($stmt);
    $result_set = mysqli_stmt_get_result($stmt);
	return $result_set;
}
function get_id($user_email)
{
	//We don't need this function in login
	global $connection;
	$query = " SELECT user_id ";
	$query .= "FROM users ";
	$query .= "WHERE user_email = '$user_email'";
	$result = mysqli_query($connection,$query);
	confirm_query($result);
	return $result;

}
function get_email($id)
{
	global $connection;
	$query = "SELECT user_email ";
	$query .= "FROM users ";
	$query .= "WHERE user_id = '$id' ";
	$result = mysqli_query($connection,$query);
	$row = $result->fetch_assoc();
	$name = $row['user_email'];
	return $name;
}
// searching

function user_search($user_query,$limString,$resString)
{
	global $connection;
	$user_query = mysqli_prep($user_query);
	echo "you searched for: ".$user_query;
	// Need only items that are currentyl auctioned.
	$query = "SELECT  * ";
	$query .= "FROM auction a  ";
	$query .= "JOIN item i ON ";
	$query .= "i.item_id = a.item_id ";
	$query .=  "JOIN image im ON im.item_id = i.item_id ";
	$query .= "WHERE item_name LIKE ".'"%'.$user_query.'%" ';
	$query .= "AND a.end_date >= Now() ";
	if(!empty($resString)):
	$query .= "AND ".$resString;
	endif;
	if(!empty($limString)):
	$query .= $limString;
	endif;
	echo $query;
	$result = mysqli_query($connection,$query);
	return $result;
}
function time_to_end($time)
{
	$datetime1 = date_create($time);
	$datetime2 = date_create("now");
	$interval = date_diff($datetime2,$datetime1);
	//$interval=$interval->fetch_assoc();
	if($interval->format('%y') >0):
		$format_string=$interval->format('%y')." years left";
	elseif($interval->format('%m')>0):
		$format_string=$interval->format('%m')." months and ".$interval->format('%d')." days left";
	elseif($interval->format('%d')>0):
		$format_string=$interval->format('%d')." days and ".$interval->format('%h')." hours left";
	elseif ($interval->format('%h')>0):
		$format_string=$interval->format('%h')." hours and ".$interval->format('%i')." minutes left";
	else:
		$format_string=$interval->format('%i')." mins and ".$interval->format('%s')." seconds left";
	endif;
	return $format_string;
}
function user_query()
{
	
		//return $result;
}
function freeresult($result)
{
	global $connection;
	mysqli_free_result($result);
}
//Generic Helper
function has_next($array) {
    if (is_array($array)) {
        if (next($array) === false) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}
//checks seller or buyer
function is_seller($id)
{
	global $connection;
	$id = mysqli_prep($id);
	$query = "SELECT is_seller FROM users WHERE user_id = ".$id;
	$result = mysqli_query($connection, $query);
	if(!empty($result)):
		$rows = $result->fetch_assoc();
		$seller_id =$rows['is_seller'];
		if($seller_id == 1):
			return True;
		else:
			return False;
		endif;
	endif;
}
function confirm_isseller()
{
	$isseller = is_seller($_SESSION['user_id']);
	if(!$isseller):
		redirect_to('index.php');
	endif;
}


?>