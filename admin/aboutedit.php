<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	admin/aboutedit.php
//		Text editor
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
    session_start();
    require_once "../common.php";

    $config = setConfig();				// Connect to database
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
<link type="text/css" rel="stylesheet" href="../custom.css">
<script src="../Cunha.js"></script>
<?php
    echo "<script src='$cked'></script>";
?>

<script>
function showFileFrame()
{
	var el = document.getElementById("frame");
	el.style.visibility = "visible";
}

</script>
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
        echo "<div style='width: 600px; margin-left:20px'>";
        echo "<form id='edForm' action='$action' method='post'>\n";

        switch ($type) {
            case "hometext";			// Use CKEditor for html types
            case "abouttext":
                showTextEditor($html);
                showImage("$type");
                break;
            case "signupprompt":
                showTextEditor($html);
                break;
            case "signupsubject":			// Single line
                echo "<input type='text' name='htmltext' size='64' value='$html'>";
                break;
            case "signuptext":			// Multi line 
                echo "<textarea name='htmltext' id='editTA' rows='10' cols='60'>$html</textarea>";
                break;
            }

            echo "<br><br>";
            echo "<button onClick='bmSend()' name='btnSave'>Save</button>";
        echo "</form>";
    echo "</div>";

    echo "<div style='position:absolute; left:600px; top:400px; visibility: hidden;'>";
        echo "<iframe id='frame' style='background-color:white;' "
        . "src='rootImageFrame.html'></iframe> ";
        echo "</div>";
    echo "</div>";

// -------------------------------------------
//  Show the CKEdit window
//  
//  Parameter   html text to be edited
// -------------------------------------------
function showTextEditor($html)
{
    echo "<div>";
    echo "<textarea name='htmltext' id='editTA' rows='25' cols='60'>$html</textarea>";
    echo "<script>";
    echo "  CKEDITOR.replace( 'htmltext' );";
    echo "  </script>";
    echo "<div>\n";
}

// -------------------------------------------
// Show the line for image input
// 
// -------------------------------------------
function showImage($type)
{
    global $mysqli;

    switch ($type){
        case "hometext";
            $newtype = "homeimage";
            break;
        case "abouttext":
            $newtype = "aboutimage";
            break;
    }
    $sql = "SELECT * FROM text WHERE type='$newtype'";
    $result = $mysqli->query($sql)
        or die("Text table error " . $mysqli->error());
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $value = $record['text'];

    echo "<br><br>";
    echo "Image file &nbsp;\n";
    echo "<input type='text' id='image' name='image' size='45' value='$value'>";
    echo " &nbsp;\n";
    echo "<input type='button' onClick='showFileFrame()' value='Upload'>";
}

?>
</body>
</html>
