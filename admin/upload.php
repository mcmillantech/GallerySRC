<?php
// ------------------------------------------------------
//  Project	Lupe Cunha
//	File	upload.php
//			Upload CSV file
//
//	Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Upload spreadsheet</title>
<link type="text/css" rel="stylesheet" href="Cunha.css">
<link type="text/css" rel="stylesheet" href="../Cunha.css">
<script src="../Cunha.js"></script>
</head>

<body onload="adminLoad()">
<h3>Lupe Web Site: Upload Paintings</h3>
<?php
	include "adminmenus.php";
?>
<br>
<form action='upload2.php' method='post' enctype='multipart/form-data'>
    Select CSV file to upload:
    <input type='file' name='csvFile' id='csvFile' accept='.csv'>
    <input type='submit' value='Upload' name='submit'>
</form>

<?php
echo "Upload";
?>
</body>
</html>
