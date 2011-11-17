<?php
// Main error configuration
	define("DEBUG", false);
	$allowedDebugIP = Array("127.0.0.1", "::1");


// Database errors
	define("EDBCONN", 1); // connecting to DB
	define("EDBSEL", 2);  // selecting databas
	define("EQUERY", 3);  // performing query

// User input errors
	define("EBADUNAME", 11);
	define("EBADEMAIL", 12);
	define("EDIFFPASS", 13);
	define("EWEAKPASS", 14);
?>
