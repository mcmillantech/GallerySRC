<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	order2.php
//		Check order details and send to Paypal
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
function shippingPrice($sizeband)
function checkVoucher($order, $painting)
function showDetails($order, $id)
function getNextOrder()
*/
    session_start();
    require_once "common.php";
    require_once "view.php";
    require_once "admin/artgroup.php";

?>
<?php

	$config = setConfig();			// Connect to database
	$mysqli = dbConnect($config);
	
	$id = $_GET['id'];			// Fetch the painting details
	$sql = "SELECT * FROM paintings WHERE id=$id";
	$result = $mysqli->query($sql)
		or myError (ERR_ORD2_READ_PAINTINGS, $mysqli->error);
	$record = $result->fetch_array(MYSQLI_ASSOC);

	require "top2.php";			// Show the top of page
	$title = $record['name'];
	showTop("Purchase from " . ARTIST, "Buy $title");
	
	$order = $_POST;	// Set up order details - this is the view array
	$order['ref'] = getNextOrder();
	$order['id'] = $id;

	if ($record['quantity'] > 1)	// Multiple copies of painting
	{				// If so, create details for the view
            $order['multi'] = true;	// <span> is set in the view
            $order['quantities'] = "Quantity " . $order['quantity'] . "</span><br>";
	}
	else  {
            $order['multi'] = false;
            $order['quantities'] = '</span>';
	}
	if (!array_key_exists('quantity', $order)) // ... otherwise order the sole work
            $order['quantity'] = 1;

	$shipping = shippingPrice($record['shippingrate']);
	$order['shipping']= sprintf('%5.2f', $shipping / 100.0);

	$price = $record['priceweb'] * $order['quantity'];
	$order['price'] = sprintf('%5.2f', $price / 100.0);
	$order['discounts'] = 0;
	$order['discounta'] = 0;

//# option 1
	checkVoucher ($record);
//# alt 1
	$order['voucher'] = '';
//# end 1

	$order['picture'] = $record['name'];
	$order['total'] = $order['price'] + $order['shipping'];
	$_SESSION['order'] = $order;

	showDetails($order, $id);
	mysqli_free_result($result);

// ----------------------------------------------
// Fetch the shipping price
//
// Parameter	Band for this work
//
// Return	The price
// ----------------------------------------------
function shippingPrice($sizeband)
{
    global $mysqli;

    $region = $_POST['region'];
    $sql = "SELECT * FROM shipping WHERE sizeband=$sizeband";
//# option 11
    $coll = $_SESSION['collection'];
    $artist = userFromCollection($coll);
    $sql .= " AND artist=$artist";
//# end 11
    echo "$sql<br>";
    $result = $mysqli->query($sql)
            or die("Collections error " . $mysqli->error);
    $record = $result->fetch_array(MYSQLI_ASSOC);
    $price = $record[$region];

    mysqli_free_result($result);
    return $price;
}

//# option 1
// ----------------------------------------------
// ----------------------------------------------
function checkVoucher ($painting)
{
	global $mysqli, $order;

	$code = $order['voucher'];
	if (strlen ($code) < 2)
		return;					// No voucher code
                                                        // Validate the voucher
	$sql = "SELECT * FROM vouchers WHERE code='$code'";
	$result = $mysqli->query($sql)
		or myError (ERR_VOUCHER, $mysqli->error);
	if (mysqli_num_rows($result) < 1)		// Voucher not found
	{
		die (V_INVALID);
	}

	$order['voucher'] = $code;
	$voucher = $result->fetch_array(MYSQLI_ASSOC);
	mysqli_free_result ($result);
	
	$today = date('Y-m-d');			// Makes today's date
	if ($today > $voucher['expires']) {
            echo V_EXPIRED;
            return;
	}
	$discount = $voucher['discount'];
	$amount = $voucher['amount'];
	$freeship = $voucher['freeship'];

	if ($discount > 0) {
            $order['price'] *= (1.0 - ($discount / 10000));
            $order['discounts'] = $discount;
	}
	else if ($amount > 0) {
            $order['price'] -= ($amount / 100.0);
            $order['discounta'] = $amount;
	}
	if ($freeship)
            $order['shipping'] = 0;

	return $order;
}
//# end 1

// ----------------------------------------------
//	Show the order details, invite correction
//
//	Parameters	Order array
//				id of picture (for correction)
// ----------------------------------------------
function showDetails($order, $id)
{

	$order["id"] = $id;
	$total = $order['total'];
	$order["link"] = "'carddetails.php?price=$total'";
	showView("order2view.html", $order);
}

// ----------------------------------------------
//	Generate next order number from system table
//
// ----------------------------------------------
function getNextOrder()
{
	global $mysqli;

	$result = $mysqli->query("SELECT * FROM system")
		or die ("System read error");
	$record = $result->fetch_array(MYSQLI_ASSOC);
	$ordId = $record['nextorder'] + 1;
	$sql = "UPDATE system SET nextorder = $ordId";
	$mysqli->query($sql);

	return $ordId;
}

?>
</div>
</body>
</html>
