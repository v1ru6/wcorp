<?php  
	// Creating a DB connection
	define("DB_SERVER", "localhost");
	define("DB_USER", "root");
	define("DB_PASS", "candrbrsn11TT");
	define("DB_NAME", "widget_corp");
	// WE DEFINED CONSTANTS ABOVE SO NO NEED TO USE THE BELOW VARIABLES
		// $dbhost = "localhost";
		// $dbuser = "root";
		// $dbpass = "candrbrsn11TT";
	// 	// $dbname = "widget_corp";
	// $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME); // this is considered better practice
	// test if successful
	if(mysqli_connect_errno()) {
		die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
	}
?>