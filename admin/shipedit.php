<?php
    session_start();
// ------------------------------------------------------
//  Project	Lupe Cunha
//	File	shipedit.php
//			Edit shipping grid
//
//	Parameters	
//				mode - ins or upd
//				id - index of item, upd mode only
//
//	Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
protected function setNewItem
protected function fetchItem()
protected function showForm($mode)

*/
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Lupe Collection Edit</title>
<link type="text/css" rel="stylesheet" href="../Cunha.css">
<script src="../Cunha.js"></script>
<style>
.input
{
	position:			absolute;
	left:				200px;
}
</style>
</head>

<body onload="adminLoad()">
<?php
	include "adminmenus.php";
	require_once "../common.php";
	require_once "DataEdit.php";

class shipedit extends DataEdit
{
	function __construct($mysqli)
	{
		parent::__construct($mysqli);
	}

	protected function setNewItem()
	{
		$this->record = array
		(
			'sizeband' => '',
			'description' => '',
			'collect' => '0',
			'uk' => '0',
			'eu' => '0',
			'usa' => '0',
			'aus' => '0'
		);
	}

// -------------------------------------------
//	Override of DataEdit virtual function
//
//	Reads event into class member record
// -------------------------------------------
	protected function fetchItem()
	{
		$id = $_GET['item'];
		$sql = "SELECT * FROM shipping WHERE sizeband=$id";
		$result = $this->mysqli->query($sql)
			or die ("Error fetching item" . mysqli_error($this->mysqli));
		$this->record =  mysqli_fetch_array($result, MYSQLI_ASSOC);
	}

// -------------------------------------------
//	Show the event edit form
//
// -------------------------------------------
	protected function showForm($mode)
	{
		if ($mode=='ins')
			$action = "shiplist.php?mode=ins";
		else
		{
			$id = $_GET['item'];
			$action = "shiplist.php?mode=upd&item=$id";
		}

		$dta = $this->record;
		$dta['collect'] /= 100.0;
		$dta['uk'] /= 100.0;
		$dta['eu'] /= 100.0;
		$dta['usa'] /= 100.0;
		$dta['aus'] /= 100.0;

		echo "<form method='post' action='$action'>";
			$this->showLine('Size Band', $dta, 'sizeband', 4);
			$this->showLine('Description', $dta, 'description', 45);
			$this->showLine('Collect', $dta, 'collect', 10);
			$this->showLine('UK', $dta, 'uk', 10);
			$this->showLine('EU', $dta, 'eu', 10);
			$this->showLine('USA', $dta, 'usa', 10);
			$this->showLine('Australia', $dta, 'aus', 10);
			echo "<button type='submit'>Post</button>";
		echo "</form>";

	}
	
/*	private function collectionList()
	{
		$ar = array();
		array_push($ar, '');

		$sql = "SELECT * FROM collections";
		$result = $this->mysqli->query($sql)
			or die ("Error reading link" . mysqli_error($this->mysqli));
		while ($coll = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			array_push($ar, $coll['name']);
		}
		
		mysqli_free_result($result);
		return $ar;
	} */
}

	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

	echo "<h3>Edit Collection</h3>";
	$lst = new shipedit($mysqli);
	$lst->run();
?>
</body>
</html>

