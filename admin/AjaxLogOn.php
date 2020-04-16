<?php
// -------------------------------------------------------------
//  Project	Online Gallery
//  File	AjaxLogOn.php
//
//  Author	John McMillan
//  McMillan Technology Ltd
//
//  Ajax server for log on
//
//  Checks the password
// --------------------------------------------------------------
    session_start();

    global $name;
    checkPassword();

// -------------------------------------------
// Check the password
//  If OK, log on
//
// -------------------------------------------
function checkPassword()
{
    $pw = $_GET['pw'];
    if ($pw == "small-garden")
	{
            logOn();
            $_SESSION['userLevel'] = 1;
	}
	else
            echo "Error: wrong password";
}

// -------------------------------------------
//  Log the user on
//	
// -------------------------------------------
function logOn()
{
    $tm = time();
    $_SESSION['MLastAccess'] = $tm;		// Initialise the time out

    echo 'OK';
}

?>
