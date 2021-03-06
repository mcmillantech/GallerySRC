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
    session_start();
    require_once "common.php";
    require "top2.php";
    require_once "admin/artgroup.php";

    $config = setConfig();				// Connect to database
    $mysqli = dbConnect($config);

    $band = $_GET['band'];
    $col = $_SESSION['collection'];
    $sql = "SELECT * FROM shipping WHERE sizeband=$band AND artist=$col";
//    echo "$sql<br>";
    $result = $mysqli->query($sql)
            or die("Error fetching shipping $sql: " . $mysqli->error);
    if (mysqli_num_rows($result) < 1){
        $msg = "There is an error with this painting. Please tell "
        . "support@newartforyou.co.uk, stating the name of the painting";
        die($msg);
    }
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);

//    $col = $_SESSION['collection'];
    $terrs = getTerritories($col);

    echo "The rate varies according to country:<br><br>";

    echo "<span>No shipping, will collect</span>";
    $charge = number_format($record['collect'] / 100.0, 2);
    echo "<span class='amount'>&pound;$charge</span><br>";
    echo "<span>" . $terrs['territory1'] . "</span>";
    $charge = number_format($record['uk'] / 100.0, 2);
    echo "<span class='amount'>&pound;$charge</span><br>";

    echo "<span>" . $terrs['territory2'] . "</span>";
    $charge = number_format($record['eu'] / 100.0, 2);
    echo "<span class='amount'>GBP $charge</span><br>";

    echo "<span>" . $terrs['territory3'] . "</span>";
    $charge = number_format($record['usa'] / 100.0, 2);
    echo "<span class='amount'>GBP $charge</span><br>";

    echo "<span>" . $terrs['territory4'] . "</span>";
    $charge = number_format($record['aus'] / 100.0, 2);
    echo "<span class='amount'>GBP $charge</span><br>";

    echo "<span>Other Countries</span>";
    $charge = number_format($record['eu'] / 100.0, 2);
    echo "<span class='amount'>On application</span><br>";
?>
