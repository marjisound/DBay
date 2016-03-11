<?php
	// 1.Create a database connection
	define("DBHOST","localhost");
	define("DBUSER","db");
	define("DBPASS","ucl");
	define("DBNAME","auctionn_system");
	$connection = mysqli_connect(DBHOST,DBUSER,DBPASS,DBNAME);
	// Test if connect occuered.
	if(mysqli_connect_errno()){
		die("Db connection failed: ". mysqli_connect_error() ."(" . mysqli_connect_errno().")"
			);
	}
?>