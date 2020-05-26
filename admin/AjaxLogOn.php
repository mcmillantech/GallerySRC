<?php
// -------------------------------------------------------------
//  Project	Online Gallery
//  File	AjaxLogOn.php
//
//  Ajax server for log on
//  Checks the username and password
//
//  Author	John McMillan
//  McMillan Technology Ltd
// --------------------------------------------------------------
    session_start();

    require_once '../common.php';
    
    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);

    global $name;
    checkPassword();

// -------------------------------------------
// Check the password
//  If OK, log on
//
// -------------------------------------------
function checkPassword()
{
    global $mysqli;

    $uname = $_GET['user'];
    $pw = $_GET['pw'];
    
    $sql = "SELECT * FROM users WHERE username='$uname'";
    $result = $mysqli->query($sql);
    if (mysqli_num_rows($result) < 1) {
        echo "User not recognised";
        return;
    }

    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($pw == $record['password']) {
        logOn();
        $level = $record['level'];
        $_SESSION['userLevel'] = $level;
        $_SESSION['fullName'] = $record['fullname'];
        $_SESSION['userId'] = $record['id'];
        if ($level == 3)
            $collection = 0;
        else 
            $collection = $record['collection'];
        $_SESSION['loggedColl'] = $collection;
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
