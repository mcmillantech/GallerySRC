<?php
// ------------------------------------------------------
//  Project	OnLine Gallery Admin
//  File	admin/artistfee.php
//  		Take payment from artist
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title>New Art for You Join</title>
    <link type="text/css" rel="stylesheet" href="../Gallery.css">
    <script src="../Cunha.js"></script>
    <script src="https://js.braintreegateway.com/web/dropin/1.11.0/js/dropin.min.js">
    </script>

</head>
<body>
<?php

    session_start();
ini_set("display_errors", "1");
error_reporting(E_ALL);
    require_once "../common.php";

    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);
	
    logCheck();

    echo "<h3>Artist fee</h3>";
    $userId = $_SESSION['userId'];
    
    $sql = "SELECT * FROM users WHERE id=$userId";
    $result = $mysqli->query($sql)
        or die("Artistfee: cannot locate user $userId");
    $user = mysqli_fetch_array($result);
    mysqli_free_result($result);
    $nWorks = $user['nworks'];
        
    $mode = $_GET['mode'];
    switch ($mode) {
        case "invoice":
            $price = getPrice($nWorks);
            $action = "artistpaid.php?mode=invoice";
            echo "<p>Please give your card details to activate your account</p>";
            break;
        case "number":
            $nw = $_POST['number'];
            $price = processWorks();
            if ($price == 0){
                echo "<br><button onClick='window.location.assign"
                . "(\"index.php\")'>Done</button>";
                return;
            }
            $action = "artistpaid.php?mode=number&n=$nw";
            break;
    }
    
    showBraintree($price, $action);

// ----------------------------------------
//  Set the renewal price, according to
//  the number of works to show
//  
// ----------------------------------------
function getPrice($nWorks)
{
    switch ($nWorks) {
        case 9:
            $price = 45;
            break;
        case 12:
            $price = 60;
            break;
        default:
            $price = 30;
    }
    return $price;
}

// ----------------------------------------
// Update the number of works to show
// 
// ----------------------------------------
function processWorks()
{
    global $mysqli;
    
    $userId = $_SESSION['userId'];
    $sql = "SELECT * FROM users WHERE id=$userId";
    $result = $mysqli->query($sql);
    if ($result=== FALSE)
        echo "There has been an error in artistpaid: $sql. "
            . "Please pass this message to admin";
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    
    $nworks1 = $user['nworks'];
    $nworks2 = $_POST['number'];
    if ($nworks2 == $nworks1) {
        echo "You have chosen the same number as at present. "
            . "No further action is needed<br><br>";
        return 0;
    }
    if ($nworks2 < $nworks1) {
        postWorksChange($userId, $nworks2);
        echo "Thanks. We have updated your account<br><br>";
        return 0;
    } else {
        $change = $nworks2 - $nworks1;
        $fee = $change * 5;
        echo "<p>The fee to increase your list will be &pound;$fee.00</p>";
/*        $transRef = takePayment($gateway, $fee);
        postWorksChange($userId, $nworks2); */
        echo "<p>Please enter your payment details</p>";
        return $fee;
    }
}

//---------------------------------------
//  Show the Braintree form
//  
//----------------------------------------
function showBraintree($price, $action) {
    $sPrice = number_format($price, 2);

    echo "<div id='dropin-container' style='width:600px; margin-left:50px;'></div>";
    echo "<form id='checkout' method='post' action='$action'>";
        echo "<input type='hidden' value='testnonce' name='nonce' id='nonceFld'>";

        echo "<input type='input' value='$sPrice' name='fee' readonly>";
        echo "<br>";
    echo "</form>";
    echo "<button id='submit-button'>Pay now</button>";
}

// ----------------------------------------
// Post the works change to the user table
// and write the order
// 
// ----------------------------------------
function postWorksChange($userId, $nworks2)
{
    global $mysqli;
    
    $sql = "UPDATE users SET nworks=$nworks2 WHERE id=$userId";
    $errTxt = "There was a problem updating your record: $sql "
        . "Please pass this message to admin";
    $mysqli->query ($sql)
            or die($errTxt);

}

?>
<script>
    var domain = document.domain;
    var token;
    token = "sandbox_79k7qx4p_nymy4h8qq7ck73sn";
//    token = "production_38zqhm8t_fdyvvs3rm4gs2c5n";

// ---------------------------------------------------
//	Script taken from Braintree
//
// ---------------------------------------------------
    var button = document.querySelector('#submit-button');
    var form = document.querySelector('#checkout');
    var nonce = document.querySelector('#nonceFld');

    var result = braintree.dropin.create(
    {
        authorization: token,
        container: '#dropin-container'
    }, function (createErr, instance) 
     {
     	button.addEventListener('click', 
      	  function () 
            {
                instance.requestPaymentMethod(function (err, payload) 
        	{
          // Submit payload.nonce to your server
                nonce.value = payload.nonce;
                form.submit();
            });
        });
     }
     );
</script>
</body>
</html>
<?php
    
function logCheck()
{
                            // See if a user is logged on
    if (!array_key_exists('userLevel', $_SESSION)) {
        echo "<button onClick='logon(\"artistfee.php\")'>Please log on</button> ";
        die();
    }
                            // Now there should be a session - process timeout
    $tm = time();
    $lastTime = 0;
    if (array_key_exists('MLastAccess', $_SESSION))
        $lastTime = $_SESSION['MLastAccess'];
    if (($tm - $lastTime) > 3600) {
        session_unset();
        session_destroy();
        header('Location: logon.php?page=artistfee.php');
    }

    $_SESSION['MLastAccess'] = $tm;

}

?>


