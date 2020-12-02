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
<link type="text/css" rel="stylesheet" href="../custom.css">
<script src="../Cunha.js"></script>
	<script src="../../ckeditor/ckeditor.js"></script>

</head>
<body>
<h1>Edit</h1>
<?php
	include "adminmenus.php";
	
	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

	$type = $_GET['type'];
        switch ($type)
        {
            case 'hometext':
                $imgRec = 'homeimage';
                break;
            case 'abouttext':
                $imgRec = 'aboutimage';
                break;
            default:
                $imgRec = '';
                break;
        }
        
	$html = addslashes( $_POST['htmltext']);
        $image = addslashes( $_POST['image']);

	$sql = "UPDATE text SET text=\"$html\" WHERE type='$type'";
//    echo "$sql<br>";
	$result = $mysqli->query($sql)
            or die("Text table error " . $mysqli->error());

	$sql2 = "UPDATE text SET text=\"$image\" WHERE type='$imgRec'";
//    echo "$sql<br>";
	$result = $mysqli->query($sql2)
            or die("Text table error " . $mysqli->error());

	echo "Record updated";
?>
</body>
</html>
