<?php
// ------------------------------------------------------
//  Project	OnLIne Gallery
//  File	eventenlarge.php
//		Show large picture
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
	session_start();
	require_once "common.php";
	require "top2.php";
	$mysqli = dbConnect($config);

	$stream = $_GET['stream'];
	$title = ARTIST . " $stream";
	showTop($title, $title);
	showImage();

function showImage()
{
	global $stream, $impath;

	$image = $_GET['image'];
	$image = $impath . "/large/" . $image;
	echo "\n<div id='mainPanel'>";
		echo "<div id='largeImage'>";
			echo "<img src='$image' style='width:98%;'>";
			echo "<button onClick='back(\"$stream\")'>Back to $stream</button></p>";
		echo "</div>";
	echo "</div>";
}

?>
	</div>
<script>
function back(stream)
{
	var str = "events.php?mode=" + stream;
	window.location = str;
}

</script>

</body>
</html>
