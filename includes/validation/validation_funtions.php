<?php
$errors = array();
// presence
function has_presence($value)
{
  return isset($value) || $value === "";
}

// string length
function has_max_lenth($value,$max)
{
	return strlen($value) <= $max;
} 

 //type
//inclusion in a set
function has_inclusion_in($value, $set)
{
return in_array($value,$set);
}
//uniqueness
//format
?>