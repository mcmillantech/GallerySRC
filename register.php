<?php
// ------------------------------------------------------
//  Project	New Art for You
//  File	about.php
//  		About the artist
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
    session_start();
    require_once "common.php";
    require "top2.php";

    $config = setConfig();				// Connect to database
    $mysqli = dbConnect($config);
    $title = "Register with New Art For You" ;
    showTop($title, $title);
    

    echo footer();

?>
</div>		<!-- container -->
</body>
</html>
