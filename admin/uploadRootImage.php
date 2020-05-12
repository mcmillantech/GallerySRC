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
function postBack(file)
{
    var parent = window.parent;
    var el = parent.document.getElementById('image');
    el.value = file;
    el = parent.document.getElementById('frame');
    el.style.visibility="hidden";
}
</script>

</head>

<body>
<h3>Art Web Site: Import</h3>

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
    $targetDir = "../images/";
    $upload = $_FILES['upload'];
    $fname = $upload['name'][0];
    $tmpName = $upload['tmp_name'][0];
    $targetFile = $targetDir . $fname;
                                            // Store the image
    if (!move_uploaded_file($tmpName, $targetFile)) 
    die ("There was an error uploading $fname from $tmpName to $targetFile");
    echo "$fname uploaded<br>";
    echo "<button onClick='postBack(\"$fname\")'>Done</button>";

}

?>
</body>
</html>
