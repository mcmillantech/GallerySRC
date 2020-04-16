<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	uploadImage.php
//		Upload JPEG files
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
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
<h3>Art Web Site: Upload Image Files</h3>
<?php
	include "adminmenus.php";
//    <input type='file' name='imgFile[]' multiple id='csvFile' accept='.jpg'>
//    <input type='submit' value='Upload' name='submit'>
?>
<br>
<form action='uploadImage2.php' method='post' enctype='multipart/form-data'>
    Select JPG files to upload:
    <input type='file' name='upload[]' multiple accept='.jpg'><br><br>
    <input type='submit' value='Upload'>
</form>


</body>
</html>
