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
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Lupe Cunha's Picture</title>
<link type="text/css" rel="stylesheet" href="Gallery.css">
<script src="Cunha.js"></script>
</head>

<body onLoad='sizeCheck()'>
<p>&nbsp;</p>
	<div id='container'>
<?php
//	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);
	
	$id = $_GET['id'];
	$sql = "SELECT * FROM paintings WHERE id=$id";
	$result = $mysqli->query($sql)
		or myError(ERR_COLLECT_ENLARGE, $mysqli->error);
	$record = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$title = $record['name'];
   	mysqli_free_result($result);

	showTop($title, $title);
	showImage($id, $record);

function showImage($id, $record)
{
	global $impath;
	
	$img = $impath . '/large/' . $record['image'];
	$col = $_GET['col'];
	$dsold = $record['datesold'];
	echo "\n<div id='mainPanel'>";
		echo "<div id='largeImage'>";
			echo "<img src='$img'  style='width:98%;' onload='loadLargeImage(this)' onClick='back($col)'>";
			if ($dsold == null)
				echo "<p><button onClick='buy($id)'>Buy</button>&nbsp;&nbsp;";
			else
				echo "<p style='color:red;font-size:150%'>Sold</p><p>";
			echo "<button onClick='back($col)'>Back to collection</button></p>";
		echo "</div>";
	echo "</div>";
}

?>
	</div>
<script>
function back(col)
{
	var str = "collections.php?col=" + col;
	document.location = str;
}

</script>

</body>
</html>
