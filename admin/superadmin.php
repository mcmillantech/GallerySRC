<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    session_start();
    require_once '../common.php';

    logCheck();
    require_once "userlist.php";
    $userList = new UserList($mysqli);

function logCheck()
{
                            // See if a user is logged on
    if (!array_key_exists('userLevel', $_SESSION)) {
        echo "No logon ";               // No point in this
        header('Location: Logon.php?page=superadmin.php');
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

