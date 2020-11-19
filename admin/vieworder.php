<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	vieworder.php
//		View an orders
//
//  Parameters	ref - id of order to display
//		action=ship - mark the order as shipped
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
function doShip()
function viewOne()
function showOrder($record, $order)
function postForm($order)
*/
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Art Site Admin Orders</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
</head>

<body>

<?php
    session_start();
    include "adminmenus.php";
    require_once "../common.php";

    echo "<h3>Art Web Site: order</h3>";
    $config = setConfig();					// Connect to database
    $mysqli = dbConnect($config);

    if (array_key_exists('action', $_GET))	// Check for shipped parameter
    {
        if ($_GET['action'] == 'ship')
            doShip();
    }

    if (array_key_exists('ref', $_GET))
            viewOne();

// ----------------------------------------------
//	Mark the order shipped
//
// ----------------------------------------------
function doShip()
{
	global $mysqli;

	$ref = $_GET['ref'];
	$dIn = $_POST['shipdate'];
	$dSql = substr($dIn, 6, 4) . '-' . substr($dIn, 3, 2) . '-' . substr($dIn, 0, 2);

	$sql = "UPDATE orders SET shipped='$dSql', status=1 WHERE ref=$ref";
	$mysqli->query($sql);
}

// ----------------------------------------------
//	View one order
//
//	Specified in $_GET
// ----------------------------------------------
function viewOne()
{
    global $mysqli;

    $ref = $_GET['ref'];
    $sql = "SELECT r.*, p.name as wrk FROM orders r "
        . "JOIN paintings p ON p.id = r.product "
        . "WHERE r.ref=$ref";
echo "$sql<br>";
    $reply = $mysqli->query($sql)
        or myError ("viewOrder.viewOne " . $mysql-->error);
    $record = mysqli_fetch_array($reply, MYSQLI_ASSOC);

    showOrder($record, $ref);
}

// ----------------------------------------------
//  Show details of an order
//
// ----------------------------------------------
function showOrder($record, $order)
{
    global $mysqli;

    $price = number_format($record['price'] /100.0, 2);
    $shipping = number_format($record['shippingprice'] / 100.0, 2);
    $total = $record['price'] + $record['shippingprice'];
    $total = number_format($total / 100.0, 2);

    echo "<span class='prompt'>Order ref</span>";
    echo "<span class='input'>" . $record['ref'] . "</span><br>";
    echo "<span class='prompt'>Work</span>";
    echo "<span class='input'>" . $record['wrk'] . "</span><br>";
    echo "<span class='prompt'>Quantity</span>";
    echo "<span class='input'>" . $record['quantity'] . "</span><br>";
    echo "<span class='prompt'>Customer</span>";
    echo "<span class='input'>" . $record['name'] . "</span><br>";
    echo "<span class='prompt'>Address</span>";
    echo "<span class='input'>" . $record['addr1'] . "</span><br>";
    echo "<span class='input'>" . $record['addr2'] . "</span><br>";
    echo "<span class='input'>" . $record['addr3'] . "</span><br>";
    echo "<span class='input'>" . $record['addr4'] . "</span><br>";
    echo "<span class='prompt'>Post code</span>";
    echo "<span class='input'>" . $record['postcode'] . "</span><br>";
    echo "<span class='prompt'>Phone</span>";
    echo "<span class='input'>" . $record['phone'] . "</span><br>";
    echo "<span class='prompt'>Shipping region</span>";
    echo "<span class='input'>" . $record['region'] . "</span><br>";
    echo "<span class='prompt'>Email</span>";
    echo "<span class='input'>" . $record['email'] . "</span><br>";
    echo "<span class='prompt'>Price</span>";
    echo "<span class='input'>$price</span><br>";
    echo "<span class='prompt'>Shipping charge</span>";
    echo "<span class='input'>$shipping</span><br>";
    echo "<span class='prompt'>Total price</span>";
    echo "<span class='input'>$total</span><br>";
    $dt = $record['date'];
    $dt = substr($dt, 8, 2) . '/' . substr($dt, 5, 2) . '/' . substr($dt, 0, 4);
    echo "<span class='prompt'>Date ordered</span>";
    echo "<span class='input'>$dt</span><br>";
    $status = ($record['status'] == 1) ? 'Shipped' : 'To ship';
    echo "<span class='prompt'>Status</span>";
    echo "<span class='input'>$status</span><br>";

    if ($record['status'] == 0)		// For an order that's not shipped
            postForm($order);

    echo "<br><br>";
//# option 11
//# end
    $loc = '"orders.php"';
    echo "<span class='prompt'>";
    echo "<button onClick='document.location=$loc'>Back to list</button>";
    echo "</span>";
//
}

// ----------------------------------------------
//	Form to set shipped data
//
// ----------------------------------------------
function postForm($order)
{
	echo "<br>Enter the date goods are shipped:<br><br>";
	$dt = date('d/m/Y');

	echo "<form method='post' action='vieworder.php?action=ship&ref=$order'>";
		echo "<span class='prompt'>Shipped date</span>";
		echo "<span class='input'>";
		echo "<input type='text' name='shipdate' value=$dt></span><br><br>";
		echo "<span class='prompt'>";
		echo "<button type='submit'>Mark shipped</button>";
		echo "</span>";
	echo "</form>";
}

?>
</body>
</html>
