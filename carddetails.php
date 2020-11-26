<?php
// ------------------------------------------------------
//  Project	Artist details
//  File	carddetails.php
//		Use Braintree API and fetch card details
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------

?>
<!doctype html>
<html>
<head>
  <title>Lupe Cunha Art: Payment</title>
  <meta charset="utf-8">
  <script src="https://js.braintreegateway.com/web/dropin/1.11.0/js/dropin.min.js"></script>
</head>
<body>
<?php
			// Provide a container for the Braintree form
			// Fetch and format the price
?>
	<div id="dropin-container"></div>
	<button id="submit-button">Pay now</button>
	<form id="checkout" method="post" action="paymentmade.php">
	<input type='hidden' value='testnonce' name='nonce' id='nonceFld'>
<?php
	$price = $_GET['price'];
	echo "<input type='text' value='" . $price . "' name='amount' id='amount'>";
?>
	<br>
	</form>
  
<script>
// ---------------------------------------------------
//	Detect whether running on development PC or server
//	Set the tokenisation ket (control panel > settings)
//
//	This is wrong - needs to be sandbox / production
// ---------------------------------------------------

	var domain = document.domain;
	var token;
//	if (domain == "localhost")
		token = "sandbox_79k7qx4p_nymy4h8qq7ck73sn";
//	else
//		token = "production_hcsjhmjj_rcy8fxhfbjfngsw4";
//        token = "production_38zqhm8t_fdyvvs3rm4gs2c5n";
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
