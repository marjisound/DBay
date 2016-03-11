<!DOCTYPE html>
<html lang="en">
<head>
	<title>Valications</title>
</head>
<body>
<?php
// presence
 
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


</body>
</html>
