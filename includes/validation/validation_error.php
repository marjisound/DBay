<!DOCTYPE html>
<html lang="en">
<head>
	<title>Valications</title>
</head>
<body>
<?php
$errors = array();
// presence
 $value = trim("");
 if(!isset($value) || $value === "")
 {
 	$errors['value'] = "value can't be blank";
 } 
//string length
 //type
//inclusion in a set
//uniqueness
//format
function is_equal($a,$b)
{
	return $a == $b;
}
echo is_equal("",false)."<br/>";
echo is_equal(0,"")."<br/>";
echo (0 === false);
?>
<?php 
if(!isempty($errors))
{
	include("form_single.php");
}

?>





</body>
</html>
