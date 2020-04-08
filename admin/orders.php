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
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Gallery Admin Orders</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<style>
.orderLine
{
	height:		20px;
}
.orderEl
{
	position:	absolute;
}

</style>

<script>
function openOrder(id)
{
	loc = "vieworder.php?ref=" + id;
	document.location = loc;
}

</script>
<script src="../Cunha.js"></script>
</head>

<body>
<?php
	session_start();
	echo "<h3>Lupe Web Site: Orders</h3>";

	include "adminmenus.php";
	require_once "../common.php";
	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

	headings();
	
	$result = $mysqli->query("SELECT * FROM orders ORDER BY id DESC");
	while ($order = mysqli_fetch_array($result, MYSQLI_ASSOC))
		showOrder($order);
//
function headings()
{
	echo "<p style='font-weight:bold'>";
		echo "Ref";
		echo "<span class='orderEl' style='left:60px'>";
			echo 'Date';
		echo "</span>";
		echo "<span class='orderEl' style='left:190px'>";
			echo 'Price';
		echo "</span>";
		echo "<span class='orderEl' style='left:290px'>";
			echo 'Shipped';
		echo "</span>";
	echo "<p>";
}

function showOrder($order)
{
	$id = $order['ref'];
	$price = number_format($order['price'] / 100, 2);
	$shipped = ($order['status'] == 1) ? 'Yes' : 'No';
	$sqlDt = $order['date'];
	$sDate = substr($sqlDt, 8, 2) . '/' . substr($sqlDt, 5, 2) . '/' . substr($sqlDt, 0, 4);
	$sqlDt = $order['shipped'];
	if ($sqlDt == '')
		$shipDate = '';
	else
		$shipDate = substr($sqlDt, 8, 2) . '/' . substr($sqlDt, 5, 2) . '/' . substr($sqlDt, 0, 4);

	echo "<div class='orderLine'>";
	echo "$id";
	echo "<span class='orderEl' style='left:60px'>";
		echo $sDate;
	echo "</span>";
	echo "<span class='orderEl' style='left:170px; width:80px;text-align:right;'>&pound;";
		echo $price;
	echo "</span>";
	echo "<span class='orderEl' style='left:300px'>";
		echo $shipped;
	echo "</span>";
	echo "<span class='orderEl' style='left:350px'>";
		echo $shipDate;
	echo "</span>";
	echo "<span class='orderEl' style='left:460px'>";
		echo "<button onClick='openOrder($id)'>Open</button>";
	echo "</span>";
	echo "</div>\n";
}

?>
</body>
</html>
