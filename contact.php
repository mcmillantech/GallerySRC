<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	contact.php
//		Contact details
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
	session_start();
	require_once "common.php";
	require "top2.php";

	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);
	showTop(ARTIST, "Contact details");
	
	echo "<h3>" . ARTIST . "</h3>";
	$address = USER_ADDRESS;
	$address = str_replace(", ", "<br>", USER_ADDRESS);
	echo $address;
	echo "<br><br>";
	
	echo "Email <a href='mailto:" . USER_EMAIL . "'>" . USER_EMAIL . "</a>";


?>
