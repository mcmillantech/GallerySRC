<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	admin/superadmin.php
//		Allows super user
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Art site Super Admin</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../Menus.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
<script src="../Cunha.js"></script>

</head>
<body>
<h1>Super Admin</h1>
<?php

    session_start();
    require_once '../common.php';

    logCheck();
    require_once "userlist.php";
    $userList = new UserList($mysqli);

function logCheck()
{
                            // See if a user is logged on
    if (!array_key_exists('userLevel', $_SESSION)) {
        echo "<button onClick='logon(\"superadmin.php\")'>Please log on</button> ";
//        header('Location: Logon.php?page=superadmin.php');
        die();
    }
                            // Now there should be a session - process timeout
                            // and check the user level
    $level = $_SESSION['userLevel'];
    if ($level < 3) {
        $redir = "logon.php?page=superadmin.php";
        echo "This user is not allowed in this page<br><br>";
        echo "<a href='$redir'> "
            . "Try again</a>";
        die();
    }

    $tm = time();
    $lastTime = 0;
    if (array_key_exists('MLastAccess', $_SESSION))
        $lastTime = $_SESSION['MLastAccess'];
    if (($tm - $lastTime) > 3600) {
        session_unset();
        session_destroy();
        header('Location: Logon.php?page=superadmin.php');
    }

    $_SESSION['MLastAccess'] = $tm;

}

