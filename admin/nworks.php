<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	artistpaid.php
//		Return from Braintree
//
//  Author	John McMillan, McMillan Technology
// ------------------------------------------------------

    session_start();
    ini_set("display_errors", "1");
    error_reporting(E_ALL);
    require '../common.php';
    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title>Art site display numbers</title>
    <link type="text/css" rel="stylesheet" href="../Gallery.css">
    <link type="text/css" rel="stylesheet" href="../Menus.css">
    <link type="text/css" rel="stylesheet" href="../custom.css">
</head>
<body>
    <h1>Change number of pictures</h1>
<?php
    $userId = $_SESSION['userId'];
    $sql = "SELECT * FROM users WHERE id=$userId";
    $result = $mysqli->query($sql);
    if ($result=== FALSE)
        echo "There has been an error in nworks: $sql. Please pass this message to admin";
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    
    $nworks1 = $user['nworks'];
    echo "<p>You currently are allowed to show $nworks1 works<p>";
    echo "<p>Please select the new number</p>";
?>    
    <form action='artistfee.php?mode=number' method='post'>
        <input type='radio' name='number' value='6'>6
        <input type='radio' name='number' value='9'>9
        <input type='radio' name='number' value='12'>12
        <br><br>
        <input type='submit' name='submit' value='submit'>
        
    </form>
<?php
?>
</body>
</html>
