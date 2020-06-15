<?php
// ------------------------------------------------------
//  Project	OnLine Gallery Admin
//  File	admin/artistfee.php
//  		Take payment from artist
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
    session_start();
ini_set("display_errors", "1");
error_reporting(E_ALL);
    require_once "../common.php";

    $config = setConfig();					// Connect to database
    $mysqli = dbConnect($config);
	
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
    logCheck();

    echo "<h3>Artist fee</h3>";
    echo "<p>Please give your card details to activate your account/p>";

?>
    
    <div id='dropin-container' style='width:600px; margin-left:50px;'></div>
        <button id="submit-button">Pay now</button>
    <form id="checkout" method="post" action="artistpaid.php">
        <input type='hidden' value='testnonce' name='nonce' id='nonceFld'>

    <br>
    </form>

<script>
    var domain = document.domain;
    var token;
//    token = "sandbox_79k7qx4p_nymy4h8qq7ck73sn";
    token = "production_38zqhm8t_fdyvvs3rm4gs2c5n";

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


