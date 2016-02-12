<?php

	$test = "I\"m a variable!";

	$test2 = "Me too!";

	$numer = 75;

	$name = "Marjan";

	echo $test2.$numer;

	echo $numer+42;

	echo "My name is $name <br />";

	///////////////Array

	$myArray = array("ehsani", "marjani", "soori jooni");

	print_r($myArray);

	echo "<br />";

	echo $myArray[2];

	echo "<br /><br /><br />";

	$anotherArray[1]="apple";

	echo $anotherArray[1];

	$anotherArray[] = "orange";

	print_r($anotherArray);

	$thirdArray = array(
			"Marjan" => "good",
			"Ehsan"  => "khol",
			"Soori" => "gol"
		);

	echo "<br /><br /><br />";

	echo '<pre>';
	print_r($thirdArray);
	echo '</pre>';

	//////// to remove an item from an array or a variable
	unset($thirdArray["Soori"]);

	echo "<br /><br /><br />";

	print_r($thirdArray);

	//////// if statement
	$number2 = 6;
	$number3 = 79;
	if ($numer != $number2 AND $number2 == $number3) {
		echo "True";
	}else {
		echo "False";
	}


	//////// for loop

	for($i=1; $i<=10; $i++){
		echo $i."<br />";
	}

	/////// foreach

	foreach ($thirdArray as $key => $value) {
		echo $value."<br />";
	}

	/////while loop
	$i=1;
	while ($i <= 10) {
		echo $i;

		$i++;
	}

	$forthArray = array("cat", "dog", "bird");

	$j = 0;
	while ($forthArray[$j]) {
		echo "Key: $i Value: $forthArray[$j] <br />";

		$j++;
	}


?>