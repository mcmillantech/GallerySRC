<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	admin/biopost.php
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
<body>
<?php
    include "adminmenus.php";

    echo "<h3>Update bio</h3>";
    
    $txt = addslashes($_POST['htmltext']);
    $col = $_GET['col'];
    $tags = addslashes($_POST['tags']);
    
    $sql = "UPDATE collections SET text='$txt', tags='$tags' WHERE id=$col";
    if (! $mysqli->query($sql))
        die ("Error posting bio $sql" . $mysqli->error());
    
    echo "<br>Data has been updated";
    ?>
</body>
</html>

