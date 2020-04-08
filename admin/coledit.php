<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	coledit.php
//		Edit collection
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

JS function showFileFrame()
*/
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Gallery Collection Edit</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
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
	require_once "../common.php";
	include "adminmenus.php";
	require_once "DataEdit.php";

class colEdit extends DataEdit
{
	function __construct($mysqli)
	{
		parent::__construct($mysqli);
		$_SESSION['colEdit'] = 1;
	}

	protected function setNewItem()
	{
		$this->record = array
		(
			'name' => '',
			'image' => '',
			'sequence' => '',
			'search' => '',
			'uselowprice' => '',
			'text' => ''
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
		$sql = "SELECT * FROM collections WHERE id=$id";
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
			$action = "collist.php?mode=ins";
		else
		{
			$id = $_GET['item'];
			$action = "collist.php?mode=upd&item=$id";
		}

		$dta = $this->record;

		echo "<form method='post' action='$action'>";
			$this->showLine('Name', $dta, 'name', 45);
			$this->showLine('Image File', $dta, 'image', 45);
			$this->showLine('Hover image', $dta, 'search', 45);
			$this->showLine('Sequence', $dta, 'sequence', 8);
			$this->showCheckBox('Use Ebay price', $dta, 'uselowprice');
			$this->textArea('Text', $dta, 'text', 10, 60);
			echo "<button type='submit'>Post</button>";
		echo "</form>";

		echo "<button onClick='showFileFrame()' "
			. "style='position:absolute; top:135px; left:510px;'>Pick</button>";
		echo "<div style='position:absolute; left:70px; top:300px; visibility: hidden'>";
		echo "<iframe id='frame' style='background-color:white; height:300px; width:500px;' "
			. "src='eventImageFrame.php?which=col'></iframe> ";
		echo "</div>";

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

	echo "<h3>Edit Collection</h3>";
	$lst = new colEdit($mysqli);
	$lst->run();
?>
</body>
</html>

