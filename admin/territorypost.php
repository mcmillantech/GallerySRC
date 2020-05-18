<?php
// ------------------------------------------------------
//  Project OnLine Gallery
//  File    territoriepost.php
//          Post territories
//
//  Author  John McMillan, McMillan Technolo0gy
// ------------------------------------------------------

?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Shipping Territories</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
</head>

<body>
<?php
    session_start(0);
    include "adminmenus.php";
    require_once "../common.php";
    require_once "DataEdit.php";

    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);
    $artist = $_SESSION['artistId'];

    echo "<h3>Post Territory Values</h3>";
    
    $t1 = $_POST['territory1'];
    $t2 = $_POST['territory2'];
    $t3 = $_POST['territory3'];
    $t4 = $_POST['territory4'];
       
    $sql = "UPDATE users SET "
        . "territory1='$t1', " 
        . "territory2='$t2', " 
        . "territory3='$t3', " 
        . "territory4='$t4' " 
        . "WHERE id = $artist";

    $result = $mysqli->query($sql)
        or die ("Error updating territory" . mysqli_error($mysqli));
    
    echo "Record updated";
    exit();
?>

</body>
</html>

