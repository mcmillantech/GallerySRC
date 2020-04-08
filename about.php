<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	about.php
//  		About the artist
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
    session_start();
    require_once "common.php";
    require "top2.php";

    $config = setConfig();					// Connect to database
    $mysqli = dbConnect($config);
    showTop("About " . ARTIST, ARTIST);

    $sql = "SELECT * FROM text WHERE type='abouttext'";
    $result = $mysqli->query($sql)
            or myError(ERR_ABOUT_TEXT, $mysqli->error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $html = $record['text'];
    echo "<div style='margin: 8px'>";
            echo $html;
    echo "</div>";
    echo footer();

?>
</div>		<!-- container -->
</body>
</html>
