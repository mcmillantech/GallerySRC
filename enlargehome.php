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
/*
	$id = $_GET['id'];
	$sql = "SELECT * FROM paintings WHERE id=$id";
	$result = $mysqli->query($sql)
		or myError(ERR_COLLECT_ENLARGE, $mysqli->error);
	$record = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$title = $record['name'];
   	mysqli_free_result($result);
*/
        $title = "NewArtForYou";
	showTop($title, $title);
	showImage();

function showImage()
{
    $img = $_GET['img'];;
    global $impath;

    $img = "$impath/$img";
//    $col = $_GET['col'];
 //   $dsold = $record['datesold'];
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
