<?php
    require_once '../common.php';
    
    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);

    $sql = "SELECT COUNT(*) AS nrows FROM collections";
    $result = $mysqli->query($sql);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $rows = $record['nrows'];
    echo "Rows $rows";
    
    $sql2 = "UPDATE collections SET sequence=sequence - 1";
    $mysqli->query($sql2);
    
    $sql3 = "UPDATE collections SET sequence= $rows WHERE sequence=0";
    $mysqli->query($sql3);
?>

