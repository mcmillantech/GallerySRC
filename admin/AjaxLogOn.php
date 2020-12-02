<?php
// -------------------------------------------------------------
//  Project	Mells Roofing
//	File	AjaxLogOn.php
//
//	Author	John McMillan
//  McMillan Technology Ltd
//
//  Ajax server for log on
//
//	Checks the password
// --------------------------------------------------------------
    session_start();

    global $name;
    if (array_key_exists('pw', $_GET))
        checkPassword();
    else {
        checkLoggedOn();
    }

// -------------------------------------------
// -------------------------------------------
function checkLoggedOn()
{
                                            // See if a user is logged on
    if (!array_key_exists('MLastAccess', $_SESSION)) {
        echo "No logon";
        return;
    }
                    // Now there should be a session - process timeout
    $tm = time();
    $lastTime = 0;
//    if (array_key_exists('MLastAccess', $_SESSION))
            $lastTime = $_SESSION['MLastAccess'];
    if (($tm - $lastTime) > 3600) {
        session_unset();
        session_destroy();
        echo "Timeout";
        return;
    }

    $_SESSION['MLastAccess'] = $tm;
    echo "OK";
    return;

}
// -------------------------------------------
//	Check the password
//	If OK, log on
//
// -------------------------------------------
function checkPassword()
{
    $pw = $_GET['pw'];
    if ($pw == "small-garden") {
        logOn();
        $_SESSION['userLevel'] = 1;
    }
    else
        echo "Error: wrong password";
}

// -------------------------------------------
//	Log the user on
//
//	
// -------------------------------------------
function logOn()
{
    $tm = time();
    $_SESSION['MLastAccess'] = $tm;		// Initialise the time out

    echo 'OK';
}


?>
