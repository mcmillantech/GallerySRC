<style>
.amount
{
	position:	absolute;
	left:		220px;
	width:		120px;
	text-align:	right;
}
</style>
<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	ajaxcharges.php
//		Presents shipping charges
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------

    require_once "common.php";
    require "top2.php";

    $config = setConfig();					// Connect to database
    $mysqli = dbConnect($config);

    $band = $_GET['band'];
    $sql = "SELECT * FROM shipping WHERE sizeband=$band";
    $result = $mysqli->query($sql)
            or die("Collections error " . $mysqli->error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);

    echo "The rate varies according to country:<br><br>";

    echo "<span>No shipping, will collect</span>";
    echo "<span class='amount'> 0.00</span><br>";
    echo "<span>United Kingdom</span>";
    $charge = number_format($record['uk'] / 100.0, 2);
    echo "<span class='amount'>&pound;$charge</span><br>";

    echo "<span>European Union</span>";
    $charge = number_format($record['eu'] / 100.0, 2);
    echo "<span class='amount'>GBP $charge</span><br>";

    echo "<span>America, Canada</span>";
    $charge = number_format($record['usa'] / 100.0, 2);
    echo "<span class='amount'>GBP $charge</span><br>";

    echo "<span>Australia, Brazil</span>";
    $charge = number_format($record['aus'] / 100.0, 2);
    echo "<span class='amount'>GBP $charge</span><br>";

    echo "<span>Other Countries</span>";
    $charge = number_format($record['eu'] / 100.0, 2);
    echo "<span class='amount'>On application</span><br>";
?>
