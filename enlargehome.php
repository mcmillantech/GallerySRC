<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	enlarge.php
//		Show large picture
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
	session_start();
	require_once "common.php";
	require "top2.php";

        $mysqli = dbConnect($config);
        $title = "NewArtForYou";
	showTop($title, $title);
	showImage();

function showImage()
{
    $img = $_GET['img'];;
    global $impath;

    $img = "$impath/$img";
    echo "\n<div id='mainPanel'>";
        echo "<div id='largeImage'>";
        echo "<img src='$img'  style='width:98%;'>";
        echo "<button onClick='back()'>Back </button></p>";
        echo "</div>";
    echo "</div>";
}

?>
    </div>
<script>
function back()
{
	var str = "index.php";
	document.location = str;
}

</script>

</body>
</html>
