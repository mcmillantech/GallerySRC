<?php
// ------------------------------------------------------
//  Project	OnLine Gallery Admin
//  File	admin/aboutedit.php
//  		Text editor
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
	session_start();
	require_once "../common.php";
	
	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);
	$cked = $config['ckeditor'];
	
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Art site Admin</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<script src="../Cunha.js"></script>
<?php
	echo "<script src='$cked'></script>";
?>

</head>
<body>
<h1>Edit</h1>
<?php
	include "adminmenus.php";
	
	$type = $_GET['type'];
	$sql = "SELECT * FROM text WHERE type='$type'";
	$result = $mysqli->query($sql)
		or die("Text table error " . $mysqli->error());
	$record = mysqli_fetch_array($result, MYSQLI_ASSOC);

	$html = $record['text'];
	$action = "textpost.php?type=$type";

	echo "<br><br>";
	echo "<div style='width: 800px; margin-left:20px'>";
	echo "<form id='edForm' action='$action' method='post'>\n";
	switch ($type)
	{
	case "hometext";				// Use CKEditor for html types
	case "abouttext":
	case "signupprompt":
		echo "<textarea name='htmltext' id='editTA' rows='25' cols='60'>$html</textarea>";
		echo "<script>";
		echo "  CKEDITOR.replace( 'htmltext' );";
		echo "  </script>";
		break;
	case "signupsubject":			// Single line
		echo "<input type='text' name='htmltext' size='64' value='$html'>";
		break;
	case "signuptext":				// Multi line 
		echo "<textarea name='htmltext' id='editTA' rows='10' cols='60'>$html</textarea>";
		break;
	}

	echo "<br><br>";
	echo "<button onClick='bmSend()' name='btnSave'>Save</button>";
	
	echo "</form>";
	echo "</div>";

?>
</body>
</html>
