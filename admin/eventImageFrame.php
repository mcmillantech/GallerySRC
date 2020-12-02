<?php
// ------------------------------------------------------
//  Project	Lupe Cunha
//	File	eventImageFrame.php
//			Frame to pick image files for events
//			from eventedit.php
//
//	Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
	require_once "../common.php";
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Upload images</title>
<link type="text/css" rel="stylesheet" href="../Cunha.css">
<style>
.lsTitle
{
	position:		absolute;
	left:			20px;
	width:			300px;
}

.lsButton
{
	position:		absolute;
	left:			320px;
}
</style>
<script>
var selectedImage;

// ------------------------------------
//	Handler for close button
//
//	Hide this frame in the parent
// ------------------------------------
function done()
{
	var parent = window.parent;
	el = parent.document.getElementById('frame');
	el.style.visibility="hidden";
}

// -----------------------------------------
//	Hander for the pick button for events
//
//	Retrieve the selected image 
//  and show the select pane
// -----------------------------------------
function pick(image)
{
	selectedImage = image;

	var el = document.getElementById("pickDiv");
	el.style.visibility = "visible";
}

// -----------------------------------------
//	Hander for the pick button for collects
//
//	Retrieve the image name from select list
//  and show the select pane
// -----------------------------------------
function pickCol(image)
{
	selectedImage = image;

	var parent = window.parent;
	var el = parent.document.getElementById("image");
	el.value = image;

	el = parent.document.getElementById('frame');
	el.style.visibility = "hidden";
}

// ----------------------------------------------
//	Handler for select pane for events
//
//	Fetch the chosen field (imagen), set the
//	selected image into that and close the pane
// ----------------------------------------------
function selected()
{
	var el = document.getElementById("choose");
	var target = el.value;				// Target field

	var parent = window.parent;
	var el = parent.document.getElementById(target);
	el.value = selectedImage;

	el = document.getElementById("pickDiv");
	el.style.visibility = "hidden";
}

</script>

</head>

<body>
<h3>Lupe Web Site: Select images</h3>

<?php
	if (array_key_exists("which", $_GET))
		$which = $_GET['which'];
	else
		$which = '';

	echo "<div style='position:absolute;'>";

	$fileList = scandir("../Images/small/");
	foreach ($fileList as $image)
	{
		if (substr($image, 0, 1) == '.')			// Don't show the folders
			continue;
		if ($which == 'event')
			$button = "<button onClick='pick(\"$image\")'>Pick</button>";
		else if ($which == 'col')
			$button = "<button onClick='pickCol(\"$image\")'>Pick</button>";
		echo "<span class='lsTitle'>$image</span>";
		echo "<span class='lsButton'>$button</span><br>\n";
	}
	
	echo "<Button onClick='done()'>Close</button>";
	echo "\n</div>";

	if ($which == 'event')
		makePicDiv();

// -----------------------------------------
//	makePicDiv
//
//	Presents a div to show the list of
//	images for a picture
//	On click, calls the select function
// -----------------------------------------
function makePicDiv()
{
	echo "<div id='pickDiv' style='position:fixed; left:390px; top:80px; visibility: hidden'>";
	echo "Select the position<br><br>";
	echo "<select id='choose'>";
	for ($n=1; $n < 7; $n++)
	{
		$fld = "image" . $n;
		echo "\n<option value='$fld'>$fld</option>";
	}
	echo "</select><br><br>";
	
	echo "<button onClick='selected()'>Select</button>";
	echo "</div>";
}
?>
</body>
</html>
