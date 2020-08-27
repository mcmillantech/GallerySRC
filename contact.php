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

	$config = setConfig();			// Connect to database
	$mysqli = dbConnect($config);
	showTop(ARTIST, "Contact details");
	
	echo "<div class='contact2'>";
        echo "<h3>" . ARTIST . "</h3>";
	$address = USER_ADDRESS;
	$address = str_replace(", ", "<br>", USER_ADDRESS);
	echo $address;
	echo "<br><br>\n";
	
	echo "Email <a href='mailto:" . USER_EMAIL . "'>" . USER_EMAIL . "</a>";
        echo "</div>";

        ?>
	<div class='contact2'>
            <p>We would like to keep you updated about new artworks and events.</p>
            <p>Please click the link below to subscribe to our mailing list.</p>
            <p><button class='signupButton'
                onclick="window.location.href='https://eepurl.com/hbgk4v';">
                Please keep me updated</button></p>
        </div>
        
</div>
