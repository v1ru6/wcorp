<?php  
	// Creating a DB connection
	define("DB_SERVER", "/");
	define("DB_USER", "/");
	define("DB_PASS", "/");
	define("DB_NAME", "/");
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME); // this is considered better practice
	// test if successful
	if(mysqli_connect_errno()) {
		die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
	}
?>