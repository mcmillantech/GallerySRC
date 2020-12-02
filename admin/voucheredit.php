<?php
    session_start();
// ------------------------------------------------------
//  Project	Lupe Cunha
//	File	voucheredit.php
//			Edit voucher
//
//	Parameters	
//				mode - ins or upd
//				id - index of item, upd mode only
//
//	Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
protected function setNewItem()
protected function fetchItem()
protected function dispDate($dt)
protected function showForm($mode)
*/
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Lupe Voucher Edit</title>
<link type="text/css" rel="stylesheet" href="../Cunha.css">
<script src="../Cunha.js"></script>
</head>
<body onload="adminLoad()">
<?php
	include "adminmenus.php";
	require_once "../common.php";
	require_once "DataEdit.php";

class VoucherEdit extends DataEdit
{
	function __construct($mysqli)
	{
		parent::__construct($mysqli);
	}

	protected function setNewItem()
	{
		$this->record = array
		(
			'code' => '',
			'expires' => '',
			'discount' => '',
			'freeship' => '',
			'amount' => '',
			'used' => ''
		);
	}

// -------------------------------------------
//	Override of DataEdit virtual function
//
//	Reads voucher into class member record
// -------------------------------------------
	protected function fetchItem()
	{
		$id = $_GET['item'];
		$sql = "SELECT * FROM vouchers WHERE id=$id";
		$result = $this->mysqli->query($sql)
			or die ("Error fetching item" . mysqli_error($this->mysqli));
		$this->record =  mysqli_fetch_array($result, MYSQLI_ASSOC);
						// Convert dates to display
		$date = $this->dispDate($this->record['expires']);
		$this->record['expires'] = $date;
		$this->record['discount'] = number_format($this->record['discount'] / 100, 2, '.', '');
		$this->record['amount'] = number_format($this->record['amount'] / 100, 2, '.', '');
	}

// -------------------------------------------
//	Convert SQL date to display format
//
// -------------------------------------------
	protected function dispDate($dt)
	{
		if ($dt == '')			// Null date
			return '';
		list($year, $mon, $day) = explode("-", $dt);
		$dtm = "$day/$mon/$year";
		return $dtm;
	}

// -------------------------------------------
//	Show the edit form
//
// -------------------------------------------
	protected function showForm($mode)
	{
		$dta = $this->record;
		if ($mode=='ins')
			$action = "voucherlist.php?mode=ins";
		else
		{
			$id = $_GET['item'];
			$action = "voucherlist.php?mode=upd&item=$id";
		}
		echo "<form method='post' action='$action'>";
			$this->showLine('Voucher code', $dta, 'code', 30);
			$this->showLine('Expires (dd/mm/yyyy)', $dta, 'expires', 12);
			$this->showLine('Discount', $dta, 'discount', 12);
			$this->showLine('Reduction (&pound;)', $dta, 'amount', 12);
			$this->showCheckBox('Free Shipping', $dta, 'freeship');
			echo "<button type='submit'>Post</button>";
		echo "</form>\n";

	}
}

	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

	echo "<h3>Edit Voucher</h3>";
	$lst = new VoucherEdit($mysqli);
	$lst->run();
?>
</body>
</html>

