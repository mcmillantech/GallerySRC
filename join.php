<?php
// ------------------------------------------------------
//  Project	New Art for You
//  File	join.php
//  		Joining form
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
    session_start();
    require_once "common.php";
    require_once 'view.php';
    require "top2.php";

    $config = setConfig();				// Connect to database
    $mysqli = dbConnect($config);
    $title = "Join New Art For You" ;
    showTop($title, $title);
    
    $dta = array();
    showView("join.html", $dta);

    echo footer();
    

?>
</div>		<!-- container -->
</body>
</html>
