<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	uploadRootImage.php
//		Frame to pick and upload image files
//		from picture edit
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
    session_start();
    require_once "../common.php";
ini_set("display_errors", "1");
error_reporting(E_ALL);
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Upload images</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
<script>
function postBack(file, imgField)
{
    var parent = window.parent;
    var el = parent.document.getElementById(imgField);
    el.value = file;
    el = parent.document.getElementById('frame');
    el.style.visibility="hidden";
}
</script>

</head>

<body>
<h3>Upload Image</h3>

<?php

    $config = setConfig();			// Connect to database
    $dbCon = dbConnect($config);

    $file = uploadFiles();

// ---------------------------------------
//	Upload the file
//
//	Requires posting from html input file
// ---------------------------------------
function uploadFiles()
{
    global $config;
    
    $targetDir = "../images/";
//    $imgPath = $config['images'];
//    $targetDir = " ../" . $imgPath . "/";
//    echo " $targetDir <br>";
 
    $imgField = 'image';           // Default - historical
    if (array_key_exists('imgfield', $_GET))
            $imgField = $_GET['imgfield'];
    echo "$imgField<br>";
    $upload = $_FILES['upload'];
    $fname = $upload['name'][0];
    $tmpName = $upload['tmp_name'][0];
//    echo "$fname<br>";
    $targetFile = $targetDir . $fname;
                                            // Store the image
    if (!move_uploaded_file($tmpName, $targetFile)) 
        die ("There was an error uploading $fname to $targetFile");
    echo "$fname uploaded<br><br>";
    echo "<button onClick='postBack(\"$fname\", \"$imgField\")'>Done</button>";

}

?>
</body>
</html>
