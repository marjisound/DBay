<?php

	$link = mysqli_connect("localhost", "cl43-dbay", "s3bYge/Bq", "cl43-dbay");

	if(mysqli_connect_error()) {

		die("Could not connect to database");
	}

	$query = "INSERT INTO `auction` (`start_price`) VALUES('2')";

	mysqli_query($link, $query);

	$query = "SELECT * FROM auction";

	//tells if the general query was successful
	if ($result=mysqli_query($link, $query)) {

		$row = mysqli_fetch_array($result);

		print_r($row);
	}

?>