<?php
// ------------------------------------------------------
//  Project	OnLIne Gallery
//  File	voucherlist.php
//		List vouchers
//
//  Parameters	default - show list
//		mode - ins or upd
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
public function showListLine($line)
public function showHeading()
protected function insertItem()
protected function upDateItem()
protected function deleteItem()
protected function SQLDate($dt)
*/
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<code>Gallery Vouchers</code>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<script src="../Cunha.js"></script>
<style>
.lscode
{
	position:		absolute;
	left:			20px;
	width:			320px;
}

.lsDate
{
	position:		absolute;
	left:			380px;
}

.lsButton
{
	position:		absolute;
	left:			480px;
}

</style>
</head>
<body>
<?php
	session_start();
	include "adminmenus.php";
	require_once "../common.php";
	require_once "DataList.php";

class EvList extends DataList
{
	function __construct($mysqli)
	{
		parent::__construct($mysqli);
	}

	public function showListLine($line)
	{
		$id = $line['id'];
		$code = substr($line['code'], 0, 38);
		$dt = $line['expires'];
		$sDate = substr($dt, 8, 2) . '/' . substr($dt, 5, 2) . '/' . substr($dt, 0, 4);
		$onEdit = "window.location=\"voucheredit.php?mode=upd&item=$id\"";
		$onDelete = "window.location=\"voucherlist.php?mode=del&item=$id\"";
	
		echo "\n<span class='lscode'>$code</span>";
		echo "\n<span class='lsDate'>$sDate</span>";
		echo "<span class='lsButton'>";
			echo "<button onClick='$onEdit'>Edit</button>";
			echo "&nbsp;";
			echo "<button onClick='$onDelete'>Delete</button>";
		echo "</span><br>";
	}

	public function showHeading()
	{
		echo "\n<b><span class='lscode'>Code</span>";
		echo "<span class='lsDate'>Expires</span>";
		echo "<span class='lsButton'> Edit</span>";
		echo "</b><br><br>";
	}

	// -------------------------------------------
	//	Insert new item
	//
	// -------------------------------------------
	protected function insertItem()
	{
		$uselowprice = $this->getCheckBox('uselowprice');

		$sql = "INSERT INTO vouchers "
			. "(code, expires, discount, amount, freeship) "
			. " VALUES (?, ?, ?, ?, ?)";
		if (!($stmt = $this->mysqli->prepare($sql)))
			echo "SQL prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

		if (!$stmt->bind_param('ssiii', $code, $expires, $discount, $amount, $freeship))
			echo "SQL bind failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

		$code = $_POST['code'];
		$expires = $_POST['expires'];
		$expires = $this->SQLDate($expires);
		$discount = $_POST['discount'] * 100;
		$amount = $_POST['amount'] * 100;
		$freeship = $this->getCheckBox('freeship');

		if (!$stmt->execute())
			$this->sqlError("Insert discount failed");
		$newId = $this->mysqli->insert_id;
		$stmt->close();
	}

	protected function upDateItem()
	{
		$id = $_GET['item'];

		$sql = "UPDATE vouchers SET code=?, expires=?, discount=?, freeship=?, "
			. "amount=? WHERE id=$id";

		if (!($stmt = $this->mysqli->prepare($sql)))
			echo "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

		if (!$stmt->bind_param('ssiii', $code, $expires, $discount, $freeship, $amount))
			"Bind failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

		$code = $_POST['code'];
		$expires = $_POST['expires'];
		$expires = $this->SQLDate($expires);
		$discount = $_POST['discount'] * 100;
		$amount = $_POST['amount'] * 100;
		$freeship = $this->getCheckBox('freeship');

		$status = $stmt->execute();
		if ($status === false)
			$this->sqlError ("Execute failed");
		$stmt->close();
	}
	
	protected function deleteItem()
	{
		$id = $_GET['item'];
		$sql = "DELETE FROM vouchers WHERE id=$id";

		$this->mysqli->query($sql)
			or die ("Error deleting item " . mysqli_error($this->mysqli));
	}

	

	protected function SQLDate($dt)
	{
		$dt = str_replace('"', '', $dt);
		list($day, $mon, $year) = explode("/", $dt);
		$dtm = "$year-$mon-$day";
		return $dtm;
	}
	
}

	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

	echo "<h3>Vouchers</h3>";
	$lst = new EvList($mysqli);
	$lst->sqlShow("SELECT * FROM vouchers  ORDER BY code DESC");
	$lst->editPage("voucheredit.php");
	$lst->run();
?>
</body>
</html>
