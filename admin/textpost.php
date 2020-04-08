<?php
// ------------------------------------------------------
//  Project	Gallery Admin
//  File	admin/textpost.php
//		Text editor
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
<title>Art site Admin</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<script src="../Cunha.js"></script>
	<script src="../../ckeditor/ckeditor.js"></script>

</head>
<body>
<h1>Edit</h1>
<?php
	include "adminmenus.php";
	
	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

//	$type = $_GET['type'] . "text";
	$type = $_GET['type'];
	$html = addslashes( $_POST['htmltext']);

	$sql = "UPDATE text SET text=\"$html\" WHERE type='$type'";
	$result = $mysqli->query($sql)
		or die("Text table error " . $mysqli->error());

	echo "Record updated";
?>
</body>
</html>
