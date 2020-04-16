<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	picedit.php
//		Edit picture details
//
//  Parameters	
//		mode - ins or upd
//		id - index of item, upd mode only
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
protected function setNewItem
protected function fetchItem()
protected function dispDate($dt)
protected function showForm($mode)
private function getLinks($picId)
private function collectionList()

JS function showFileFrame()
*/
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Gallery Picture Edit</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
<script src="../Cunha.js"></script>
<style>
.input
{
	position:			absolute;
	left:				200px;
}
</style>
<script>
function showFileFrame()
{
	var el = document.getElementById("frame");
	el.style.visibility = "visible";
}

</script>
</head>
<body>
<?php
	session_start();
	include "adminmenus.php";
	require_once "../common.php";
	require_once "DataEdit.php";

class picEdit extends DataEdit
{
	function __construct($mysqli)
	{
		parent::__construct($mysqli);
		$_SESSION['picEdit'] = 1;
	}

	protected function setNewItem()
	{
		date_default_timezone_set("Europe/London");	// Today's date
		$dt = date('d-m-Y');

		$this->record = array
		(
			'name' => '',
			'coll1' => '',
			'coll2' => '',
			'coll3' => '',
			'recent' => 0,
			'year' => '',
			'dateset' => $dt,
			'media' => '',
			'size' => '',
			'mount' => '',
			'tags' => '',
			'priceweb' => '',
			'priceebay' => '',
			'costcovered' => 0,
			'datesold' => '',
			'archive' => 0,
			'image' => '',
			'notes' => '',
			'shippingrate' => 1,
			'quantity' => 1,
			'away' => ''
		);
	}

// -------------------------------------------
//	Override of DataEdit virtual function
//
//	Reads painting into class member record
// -------------------------------------------
	protected function fetchItem()
	{
		$id = $_GET['item'];
		$sql = "SELECT * FROM paintings WHERE id=$id";
		$result = $this->mysqli->query($sql)
			or die ("Error fetching item" . mysqli_error($this->mysqli));
		$this->record =  mysqli_fetch_array($result, MYSQLI_ASSOC);
						// Convert dates to display
		$this->record['priceweb'] = number_format($this->record['priceweb'] / 100, 2, '.', '');
		$this->record['priceebay'] = number_format($this->record['priceebay'] / 100, 2, '.', '');
		$date = $this->dispDate($this->record['datesold']);
		$this->record['datesold'] = $date;
		$date = $this->dispDate($this->record['dateset']);
		$this->record['dateset'] = $date;
		$date = $this->dispDate($this->record['away']);
		$this->record['away'] = $date;
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
		$day = substr($day, 0, 2);			// Strip out time
		$dtm = "$day/$mon/$year";
		return $dtm;
	}

// -------------------------------------------
//	Show the picture edit form
//
// -------------------------------------------
	protected function showForm($mode)
	{
		if ($mode=='ins')
			$action = "piclist.php?mode=ins";
		else
		{
			$id = $_GET['item'];
			$this->getLinks($id);
			$action = "piclist.php?mode=upd&item=$id";
		}

		$dta = $this->record;
		$collList = $this->collectionList();

		echo "<form method='post' action='$action'>";
			$this->showLine('Title', $dta, 'name', 45);
			$this->showDropDown('Collection', $dta, 'coll1', $collList);
			$this->showDropDown('', $dta, 'coll2', $collList);
			$this->showDropDown('', $dta, 'coll3', $collList);
			$this->showCheckBox('Recent', $dta, 'recent');
			$this->showLine('Year', $dta, 'year', 10);
			$this->showLine('Uploaded', $dta, 'dateset', 10);
			$this->showLine('Medium', $dta, 'media', 45);
			$this->showLine('Size', $dta, 'size', 45);
			$this->showLine('Mount', $dta, 'mount', 45);
			$this->showLine('Tags', $dta, 'tags', 45);
			$this->showLine('Web Price', $dta, 'priceweb', 15);
			$this->showLine('Ebay Price', $dta, 'priceebay', 15);
			$this->showLine('Shipping Rate', $dta, 'shippingrate', 2);
			$this->showCheckBox('Cost Covered?', $dta, 'costcovered');
			$this->showLine('Date Sold', $dta, 'datesold', 10);
			$this->showCheckBox('Archived', $dta, 'archive');
			$this->showLine('Away', $dta, 'away', 10);
			$this->showLine('Image File', $dta, 'image', 45);
			$this->showLine('Quantity', $dta, 'quantity', 12);
			$this->textArea('Notes', $dta, 'notes', 4, 60);
			echo "<button type='submit'>Post</button>";
		echo "</form>";

		echo "<button onClick='showFileFrame()' "
			. "style='position:relative; top:-190px; left:500px;'>Upload</button>";
		echo "<div style='position:absolute; left:600px; top:400px; visibility: hidden;'>";
		echo "<iframe id='frame' style='background-color:white;' src='imageframe.html'></iframe> ";
		echo "</div>";
		
	}
	
	private function getLinks($picId)
	{
		$this->record['coll1'] = '';
		$this->record['coll2'] = '';
		$this->record['coll3'] = '';

		$sql = "SELECT l.*, c.name FROM links l "
			. "JOIN collections c ON c.id = l.collection "
			. "WHERE l. picture=$picId";
		$result = $this->mysqli->query($sql)
			or die ("Error reading link" . mysqli_error($this->mysqli));
		$i = 1;
		while ($link = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$inx = "coll" . $i++;
			$this->record[$inx] =  $link['name'];
		}

		mysqli_free_result($result);
	}
	
	private function collectionList()
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
	}
}

	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

	echo "<h3>Edit Painting</h3>";
	$lst = new picEdit($mysqli);
	$lst->run();
?>
</body>
</html>

