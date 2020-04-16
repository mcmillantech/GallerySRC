<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	admin/adminmenus.php
//		Admin menus, also logon check
//		The latter because every page includes this
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------

	logCheck();
?>
<div id='menu'>
    <ul>
	<li><a href='index.php'>Admin home</a></li>
<!--	<li><a href='#' onmouseover='mopen("m1")' onmouseout='mclosetime()'>Upload</a>
            <div id='m1' onmouseover='mcancelclosetime()' onmouseout='mclosetime()'>
            <a href='upload.php'>Upload spreadsheet</a>
            <a href='uploadImage.php'>Upload images</a>
            </div> -->
	<li><a href='orders.php'>View orders</a></li>
	<li><a href='eventlist.php'>Events</a></li>
	<li><a href='#' onmouseover='mopen("m2")' onmouseout='mclosetime()'>Pictures</a>
            <div id='m2' onmouseover='mcancelclosetime()' onmouseout='mclosetime()'>
            <a href='piclist.php'>Pictures</a>
<?php
    //# option 2 
            echo "<a href='collist.php'>Collections</a>";
    //# end 2
?>
            </div>
	<li><a href='#' onmouseover='mopen("m3")' onmouseout='mclosetime()'>Other</a>
            <div id='m3' onmouseover='mcancelclosetime()' onmouseout='mclosetime()'>
<?php
    //# option 1 
            echo "<a href='voucherlist.php'>Vouchers xx</a>";
    //# end 1 
    //# option 4 
            echo "<a href='shiplist.php'>Shipping grid</a>";
    //# end 4
            echo "<a href='aboutedit.php?type=hometext'>Home Page Text</a>";
    //# option 5
            echo "<a href='aboutedit.php?type=abouttext'>About Page Text</a>";
    //# end 5
?>
            <a href='aboutedit.php?type=signupprompt'>Sign up Prompt</a>
            <a href='aboutedit.php?type=signupsubject'>Sign up Subject</a>
            <a href='aboutedit.php?type=signuptext'>Signup Email Text</a>
            </div>
	<li><a href='logout.php'>Log Out</a></li>
    </ul>
</div>
<?php

function logCheck()
{
                            // See if a user is logged on
    if (!array_key_exists('userLevel', $_SESSION)) {
        echo "No logon ";
        header('Location: logon.html');
    }
                            // Now there should be a session - process timeout
    $tm = time();
    $lastTime = 0;
    if (array_key_exists('MLastAccess', $_SESSION))
        $lastTime = $_SESSION['MLastAccess'];
    if (($tm - $lastTime) > 3600) {
        session_unset();
        session_destroy();
        header('Location: logon.html');//		exit;
    }

    $_SESSION['MLastAccess'] = $tm;

}

?>
