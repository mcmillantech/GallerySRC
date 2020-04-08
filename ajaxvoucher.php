<?php
// ------------------------------------------------------
//  Project	OnLIne Gallery
//  File	ajaxvoucher.php
//		Check voucher code
//		and display the discounts
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------

	require_once "common.php";

	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

	$code = $_GET['code'];
	$today = date('Y-m-d');			// Makes today's date
	
	$sql = "SELECT * FROM vouchers WHERE code='$code'";
	$result = $mysqli->query($sql)
		or myError (ERR_VOUCHER, $mysqli->error);
	if (mysqli_num_rows($result) < 1)		// Voucher not found
	{
		echo V_INVALID;
		mysqli_free_result ($result);
		return;
	}
	$voucher = $result->fetch_array(MYSQLI_ASSOC);
	mysqli_free_result ($result);
	
	if ($today > $voucher['expires'])
	{
		echo V_EXPIRED;
		return;
	}
	
	$message = V_VALID;
	$discount = $voucher['discount'];
	$amount = $voucher['amount'];
	$freeship = $voucher['freeship'];

	if ($discount > 0)
		$message .= (V_DISCOUNT . $discount / 100.0 . "%\n");
	else if ($amount > 0)
		$message .= (V_DISCOUNT . "GBP " . $amount / 100.0 . "\n");
	if ($freeship)
		$message .= V_FREESHIP;

	echo $message;
?>
