<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	uploadInage2.php
//		Upload image files
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
//	session_start();
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
<script src="../Cunha.js"></script>
</head>

<body>
<h3>Art Web Site: Import</h3>

<?php
	include "adminmenus.php";

	echo "<h3>Uploading Images</h3>";

	$config = setConfig();					// Connect to database
	$dbCon = dbConnect($config);

	$file = uploadFiles();
//	echo "$file uploaded OK";

//	process ($file);

// ---------------------------------------
//	Upload the file
//
//	Requires posting from html input file
// ---------------------------------------
function uploadFiles()
{
	$targetDir = "../Images/large/";
//print_r($_FILES);
	$upload = $_FILES['upload'];
	$number = count($upload['name']);
	for( $i=0; $i < $number; $i++ )
	{
		$fname = $upload['name'][$i];
		$tmpName = $upload['tmp_name'][$i];
		$targetFile = $targetDir . $fname;
											// Store the original large image
		if (!move_uploaded_file($tmpName, $targetFile)) 
        	die ("There was an error uploading $fname from $tmpName to $targetFile");
        resize($fname, $targetFile);		// And the small one
		echo "$fname uploaded<br>";
	}

}

// ---------------------------------------
//	All being well, make a small image
//
//	Parameters
//			File name
//			Original (large) file
// ---------------------------------------
function resize($fname, $fLarge)
{
	$targetDir = "../Images/small/";
	$targetFile = $targetDir . $fname;

	$img = imagecreatefromjpeg($fLarge);
	if (!$img)
		die ("Failed resize $fLarge");
    $small = imagescale($img , 300, -1);
	if ($small === FALSE)
		die ("Failed scaling $fLarge");
    $small = imagescale($img , 300, -1);
    if (!imagejpeg($small, $targetFile, 100))
    	die("Failed to save $targetFile");
	
    imagedestroy($img);
    imagedestroy($small);
}



?>
</body>
</html>
