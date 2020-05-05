<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	admin/admingroupmenus.php
//		Replaces adminmenus.php
//		Every page includes this file
//		
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------

    logCheck();
    $userLevel = $_SESSION['userLevel'];
    if ($userLevel == 3)
        superMenu ();
    else
        artistMenu();

// --------------------------------------------
// Show menu for superadmin
// 
// --------------------------------------------
function superMenu()
{
    require_once 'artgroup.php';
    $config = setConfig();
    $mysqli = dbConnect($config);


?>
<div id='menu'>
    <ul>
	<li><a href='index.php'>Admin home</a></li>
	<li><a href='#' onmouseover='mopen("m1")' onmouseout='mclosetime()'>View orders</a>
            <div id='m1' onmouseover='mcancelclosetime()' onmouseout='mclosetime()'>
<?php
            $dd = makeDropDown('orders', $mysqli);
            echo $dd;
?>
            </div>
<!--	<li><a href='eventlist.php'>Events</a></li> -->
        <li><a href='collist.php'>Artists</a></li>
	<li><a href='#' onmouseover='mopen("m2")' onmouseout='mclosetime()'>Pictures</a>
            <div id='m2' onmouseover='mcancelclosetime()' onmouseout='mclosetime()'>
<?php
            $dd2 = makeDropDown('piclist', $mysqli);
            echo $dd2;
?>
            </div>
        </li>
	<li><a href='#' onmouseover='mopen("m3")' onmouseout='mclosetime()'>Other</a>
            <div id='m3' onmouseover='mcancelclosetime()' onmouseout='mclosetime()'>
<!--        <a href='voucherlist.php'>Vouchers xx</a>  -->
            <a href='shiplist.php'>Shipping grid</a>
            <a href='aboutedit.php?type=hometext'>Home Page Text</a>
            <a href='aboutedit.php?type=abouttext'>About Page Text</a>
            </div>
	<li><a href='logout.php'>Log Out</a></li>
    </ul>
</div>
<?php
}

// --------------------------------------------
// Show menu for an artist
// 
// --------------------------------------------
function artistMenu()
{
?>
<div id='menu'>
    <ul>
	<li><a href='index.php'>Admin home</a></li>
	<li><a href='orders.php'>View orders</a></li>
<!--	<li><a href='eventlist.php'>Events</a></li> -->
        <li><a href='piclist.php'>Pictures</a></li>
        </li>
	<li><a href='#' onmouseover='mopen("m3")' onmouseout='mclosetime()'>Other</a>
            <div id='m3' onmouseover='mcancelclosetime()' onmouseout='mclosetime()'>
            <a href='shiplist.php'>Shipping grid</a>
            <a href='aboutedit.php?type=abouttext'>About Text</a>
            <a href='aboutedit.php?type=signupprompt'>Sign up Prompt</a>
            <a href='aboutedit.php?type=signupsubject'>Sign up Subject</a>
            <a href='aboutedit.php?type=signuptext'>Signup Email Text</a>
            </div>
	<li><a href='logout.php'>Log Out</a></li>
    </ul>
</div>
<?php
}

function logCheck()
{
                            // See if a user is logged on
    if (!array_key_exists('userLevel', $_SESSION)) {
        echo "<button onClick='logon(\"index.php\")'>Please log on</button> ";
        die();
    }
                            // Now there should be a session - process timeout
    $tm = time();
    $lastTime = 0;
    if (array_key_exists('MLastAccess', $_SESSION))
        $lastTime = $_SESSION['MLastAccess'];
    if (($tm - $lastTime) > 3600) {
        session_unset();
        session_destroy();
        header('Location: logon.php');
    }

    $_SESSION['MLastAccess'] = $tm;

}

?>

